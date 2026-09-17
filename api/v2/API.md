# Sav Account API v2 (Notefox Account)

Base URL: `https://notefox.eu/api/v2`

The v2 API replaces v1 (`/api/v1`, still online, with **exactly the same
requests and the same answers**): an extension that has not been updated keeps
working against v1 with the very same account, because the database only
received **additive** changes. Three v1 endpoints gained one side effect each -
the cleanup of the new tables they cannot know about - and nothing else (see
[§9](#9-coexistence-between-v1-and-v2)).

**From v2 on, this is a generic account platform: "Sav Account".** The account
itself - signup, login, sessions, tokens, data key, OTP, rate limiting, emails -
is **shared and product neutral**. What is **partitioned per service** is only
the synchronised data: every service owns its own snapshot and its own
`revision` on the same account. Notefox is simply the first service, and the
default one: a client that never mentions a service is a Notefox client.

The public paths do not change: everything stays under `/api/v2`.

- [1. What changes compared to v1](#1-what-changes-compared-to-v1)
- [2. Database (SQL to run)](#2-database-sql-to-run)
- [3. Encryption model](#3-encryption-model)
- [4. Sync model (snapshot + revision)](#4-sync-model-snapshot--revision)
- [5. Conventions](#5-conventions)
- [6. Error codes](#6-error-codes)
- [7. Endpoints](#7-endpoints)
- [8. Rate limits](#8-rate-limits)
- [9. Coexistence between v1 and v2](#9-coexistence-between-v1-and-v2)
- [10. Tests](#10-tests)

---

## 1. What changes compared to v1

| Area                             | v1                                                                                                                       | v2                                                                                                                           |
|----------------------------------|--------------------------------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------|
| Sync                             | one new row per save, read back with `ORDER BY updated-locally-date DESC`: **a device with a skewed clock wins forever** | one snapshot per account **and service**, with a server-side `revision`, conflicts reported with `409`                       |
| Conflicts                        | none, the last write silently wins                                                                                       | `base-revision` + `409` with the current data so the client can merge                                                        |
| Password change                  | decrypts and re-encrypts `LIMIT 50` rows: everything older becomes **unreadable forever**                                | only the data key is re-wrapped, the notes are never touched; all legacy history rows are re-encrypted with the new password |
| `logout`, `data/get/last-update` | authenticated with the `login-id` **alone**                                                                              | `login-id` **and** `token` always required                                                                                   |
| Confirmation email               | sent to the address in the payload                                                                                       | sent only to an address whose hash matches the account                                                                       |
| `login-id`                       | `sha512(email + ip + timestamp)`, guessable                                                                              | 32 random bytes                                                                                                              |
| OTP codes                        | `rand()`, no expiry, no attempt limit, not always consumed                                                               | `random_int()`, expiry, max 5 attempts, single use, constant-time compare                                                    |
| Brute force / mail bombing       | nothing                                                                                                                  | rate limiting on every sensitive endpoint                                                                                    |
| Signup                           | answers 416 or 419 → accounts can be enumerated                                                                          | one single answer                                                                                                            |
| 2FA at login                     | always on, not configurable                                                                                              | on by default, can be turned off (`otp/*`)                                                                                   |
| Email verification at signup     | required                                                                                                                 | **always required, and never affected by the 2FA setting**                                                                   |
| Multi-service                    | none, the data belongs to Notefox                                                                                        | one shared account, one independent snapshot per service                                                                     |
| Health check                     | none                                                                                                                     | `GET /status`                                                                                                                |
| Emails                           | `mail()`                                                                                                                 | Symfony Mailer over authenticated SMTP                                                                                       |
| Atomicity                        | `LOCK TABLES` placed around `bind_param()`, so ineffective                                                               | real transactions, `SELECT ... FOR UPDATE` on the snapshot                                                                   |
| Errors                           | `echo_error()` duplicated in 19 files, inconsistent codes, sometimes a literal `null`                                    | one single catalogue, real HTTP statuses, never `null`                                                                       |

---

## 2. Database (SQL to run)

Only **additions** to the tables used by v1: no existing table or column of v1 is
renamed, modified or removed. The only migration is on the v2 snapshot table,
which gains the `service` dimension. InnoDB, to be executed in phpMyAdmin.

The table names used by the migration are the ones declared in
`include/credentials.php`: change them there **and** in the SQL file if your
installation uses different ones. The names of the **existing v1 tables** live
in one single place at the top of the v1 section of `migration.sql`
(`SET @v2_users_table`, `@v2_logins_table`, `@v2_tokens_table`,
`@v2_data_table`): the data table is `data` on notefox.eu, while the older
documentation calls it `notefox_data`, so check it with `SHOW TABLES;` before
running the file - a wrong name there used to abort the whole script with
`#1146 - Table ... doesn't exist`.

The statements live in one single versioned file,
[`install/migration.sql`](install/migration.sql), which is the only place they
are written: paste it in phpMyAdmin or run
`mysql -u USER -p DATABASE < api/v2/install/migration.sql`. The operational
procedure (backup, order, verification, rollback) is
[`install/DEPLOY.md`](install/DEPLOY.md).

Blocks 1, 2a, 3 and 4 to 10 are **re-runnable**: every table, column and index is
created only when it is missing, so a second run is a no-op instead of aborting
the whole script with `#1060 - Duplicate column name`. A v1 table that does not
exist under the configured name is skipped instead of aborting with `#1146`, and
the file prints a `found` / `MISSING` line per table before touching anything.
Block 2b (`RENAME` + `DROP PRIMARY KEY`) is the only one that must be run
exactly once.

| Block              | What                                                                                                                                                                               | Mandatory | Without it                                                              |
|--------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|-----------|-------------------------------------------------------------------------|
| 1                  | `CREATE TABLE user_keys`                                                                                                                                                           | yes       | no account gets a data key (`keys: false`)                              |
| **2a** *or* **2b** | snapshot table: `CREATE TABLE sav_data_current` (fresh install) **or** `RENAME` of `notefox_data_current` + column `service` + primary key `(user-id, service)` (existing install) | yes       | every `data/*` endpoint answers `503`                                   |
| 3                  | `CREATE TABLE rate_limits`                                                                                                                                                         | yes       | no brute force protection                                               |
| 4                  | `users`.`otp-enabled`                                                                                                                                                              | yes       | the `otp/*` endpoints cannot read the setting                           |
| 5                  | `users`.`password-v2`                                                                                                                                                              | yes       | the modern hash is never kept aligned                                   |
| 6                  | `users`.`otp-change-*`                                                                                                                                                             | no        | `POST /otp/disable` and its verify step answer `503`                    |
| 7                  | `verification-expiry`, `verification-attempts`, `deleting-attempts`, `logins`.`verification-attempts`                                                                              | no        | the codes lose their expiry and their attempt limit                     |
| 8                  | `users`.`password-change-*`                                                                                                                                                        | yes       | `POST /password/edit` answers `500`                                     |
| 9                  | indexes on the v1 data table (`data`) and on `tokens`                                                                                                                              | no        | performance only                                                        |
| 10                 | `users`.`history-enabled`                                                                                                                                                          | no        | the two `data/get/history*` endpoints answer `433` to **every** account |

Run **exactly one** branch of block 2. Inside block 2b the order is not
negotiable: the `service` column, with its `DEFAULT 'notefox'`, must exist **before** the primary key is rebuilt,
otherwise the existing rows would have no
key. `DROP PRIMARY KEY` rewrites and locks the whole table, so estimate its size
first. After the `RENAME`, `$data_current_table` must be updated: leaving it on
the old name is the most common deploy mistake and makes every `data/*` endpoint
answer `503`.

> No data migration script is needed: the `DEFAULT 'notefox'` assigns every
> existing snapshot to the Notefox service, and nothing is re-encrypted.

> Blocks 6, 7, 9, 10 and the `legacy-data-id` column are **optional**: the API
> still answers (the core detects the missing columns), but the two
> `otp/disable` endpoints answer `503`, the codes lose their expiry/attempt
> limit and the sync history stays denied to every account. `GET /status` and
> `php api/v2/tests/schema-check.php` report exactly which block is missing.

Variables to add in `include/credentials.php` and `include/credentials-sample.php`
(additions only, they are already there):

```php
$user_keys_table    = "user_keys";
$data_current_table = "sav_data_current";   // was notefox_data_current
$rate_limits_table  = "rate_limits";

// Registry of the services sharing this account. "legacy-table" is the v1
// mirror table of the service, or absent when it has none.
$services = array(
    "notefox" => array(
        "name"         => "Notefox",
        "legacy-table" => $data_table,   // the v1 data table, `data` on notefox.eu
    ),
);
```

Adding a new service is a **configuration** change only: one more entry in
`$services`, no DDL, no new endpoint. A service without `legacy-table` never
touches any v1 table.

If `$services` is missing (an installation that has not been updated), the core
falls back to the single `notefox` service and nothing changes.

### Migration of existing accounts

No batch script. At the first successful v2 login (the only moment where the
plaintext password is available) the API:

1. generates the DEK and stores the wrapped key in `user_keys`;
2. promotes the latest row of the legacy table of every service that has one (today the v1 `data` table) to the snapshot
   in `sav_data_current`, with
   `revision = 1` and `service = 'notefox'`, re-encrypted with the DEK;
3. fills in `password-v2`.

The historical rows of the v1 data table are never touched: v1 keeps reading
them. An account that never logs in through v2 has no row in `user_keys` nor in
`sav_data_current` and keeps working on v1 exactly as before.

The operational procedure - backup, order of the steps, verification, rollback,
troubleshooting - is [`install/DEPLOY.md`](install/DEPLOY.md).

---

## 3. Encryption model

```
password ──PBKDF2(sha256, 210k, salt)──> KEK ──AES-256-CBC──> wrapped DEK  (user_keys)
DEK ──AES-256-CBC──> data, snapshot of every service                       (sav_data_current)
token ──AES-256-CBC──> password                                            (tokens, v1 format)
```

- The **DEK** ("XX") is generated once with `random_bytes(32)` and **never
  changes**: it is what encrypts the data. There is **one DEK per account**,
  shared by every service, so a password change re-wraps one single key no
  matter how many services the account uses.
- The password only ever encrypts the DEK. Changing the password re-wraps
  ~100 bytes: no note is re-encrypted, so the v1 data loss (`LIMIT 50`) cannot
  happen any more.
- `key-check` is the SHA-512 fingerprint of the DEK: it proves an unwrap
  produced the right key, without storing the key.
- The `tokens` table keeps the v1 semantics (the password encrypted with the
  token), so **a token issued by v2 is still valid on the v1 endpoints**.
- `users`.`password` stays SHA-512 because v1 uses it in its `WHERE` clauses;
  v2 verifies it and keeps the additive `password-v2` (`password_hash()`)
  aligned at every successful authentication.
- The server never stores the plaintext email: `users`.`email` is its SHA-512.
  That is why an email address supplied in a request is accepted only when its
  hash matches the authenticated account (see §5).

---

## 4. Sync model (snapshot + revision)

```
        ┌─────────── sav_data_current (1 row per user-id + service) ───────────┐
client ►│ notefox : revision 41 → 42   data (DEK)   updated-server   legacy-id │
service │ other   : revision  7 →  8   data (DEK)   updated-server   (no mirror)│
        └──────────────────────────────┬──────────────────────────────────────┘
                                       │ mirror, only for services with a
                                       │ "legacy-table" (password, v1 format)
                                       ▼
                    v1 data table (1 row per sync = history)
                       newest row  ◄── read by the v1 clients
```

The `revision` belongs to the pair **(account, service)**: writing on one
service never moves the revision of another one, and a `409` always carries the
revision and the data of the **requested** service.

1. The client sends `base-revision`, i.e. the revision it currently owns.
2. The server locks the snapshot (`SELECT ... FOR UPDATE`), and if a v1 client
   wrote in the meantime that row is promoted first (`revision + 1`).
3. If `base-revision` differs from the current revision → **409** with the
   current `revision` and `data`: the client merges and retries.
4. Otherwise the snapshot is written with `revision + 1` and, **only for a
   service that declares a legacy table** (today Notefox), the single legacy
   mirror row is refreshed so that the old extension keeps seeing the latest
   data. Any other service never touches a v1 table.

`updated-locally` is now only informational, **it never decides who wins**: this
is the actual fix for the reported bug. A client whose clock is behind is
accepted normally (in v1 its saves became invisible forever).

A client that does not send `base-revision` is always accepted (last write wins,
v1 behaviour), but it still receives the `revision` in the answer.

---

## 5. Conventions

- Every endpoint is **POST with a JSON body**, except `GET /status`.
- `Content-Type: application/json`. Body limited to **2 MB**, the `data` field
  to **1.5 MB** (`413` beyond that).
- Answers are always JSON and carry a real HTTP status:

```json
{ "status": "Successful", "code": 200, "data": { } }
{ "status": "Error", "code": 402, "description": "...", "data": null }
```

- `login-id` and `token` are 64 hexadecimal characters.
- Dates are `YYYY-MM-DD HH:MM:SS` in the server timezone; `GET /status` returns
  `server-time` so the client can measure its own clock skew.
- Codes are 6 characters out of `23456789ABCDEFGHJKLMNPQRSTUVWXYZ` (no
  ambiguous characters), compared case-insensitively.
- **`email` in the payload**: since the server only stores its hash, the address
  is used exclusively as the email recipient and only if
  `sha512(lowercase(email))` matches the account; otherwise the answer is `410`.
  It is never possible to have an email sent to a third-party address.
- **`service` in the payload** (data endpoints only): the service the data
  belongs to. It is **optional** and defaults to `notefox`, so a client written
  for the first release of v2 keeps working with no change at all. The name is
  lower-cased and must match `^[a-z0-9][a-z0-9_-]{0,31}$` **and** be declared in
  the `$services` registry; anything else is refused with `400` and nothing is
  written.
- **Signup vs 2FA**: the email verification code at signup is **always**
  required and is not a two-factor feature. `otp-enabled` is a **login** setting
  only: turning it off never skips the signup verification.
- **Sync history is a permission, not a feature**: the two `data/get/history*`
  endpoints are reserved to the accounts whose `users`.`history-enabled` is `1`
  (block 10). The default is `0` - and a **missing** column is read as `0` too -
  so the history is denied (`433`) until it is granted explicitly. There is no
  endpoint to change it, by design: it is granted per account with one `UPDATE`
  (see block 10 of `migration.sql`), so that nobody can grant it to themselves.
  `POST /data/services` reports the current value in `history-enabled`.

---

## 6. Error codes

| `code` | HTTP | Meaning                                                                           |
|--------|------|-----------------------------------------------------------------------------------|
| 200    | 200  | OK                                                                                |
| 201    | 200  | No data for this account                                                          |
| 400    | 400  | Missing or invalid parameters                                                     |
| 401    | 503  | Database unreachable                                                              |
| 402    | 401  | `login-id` missing, disabled, expired or invalid                                  |
| 403    | 401  | Account not found                                                                 |
| 404    | 401  | Token missing, disabled or expired                                                |
| 405    | 401  | Invalid token                                                                     |
| 406    | 405  | HTTP method not allowed                                                           |
| 407    | 413  | Payload too large                                                                 |
| 409    | 409  | Revision conflict (the answer carries the current data)                           |
| 410    | 401  | Invalid credentials                                                               |
| 411    | 403  | Account not active / not verified                                                 |
| 412    | 400  | Code expired                                                                      |
| 413    | 400  | Invalid code                                                                      |
| 414    | 409  | Account already verified                                                          |
| 415    | 400  | No code has been requested                                                        |
| 419    | 409  | Signup not completed                                                              |
| 420    | 429  | Too many wrong attempts, request a new code                                       |
| 429    | 429  | Rate limit reached                                                                |
| 430    | 409  | Encryption key unavailable for this account                                       |
| 431    | 409  | The OTP is already in the requested state                                         |
| 432    | 409  | Sync history is not available for this service                                    |
| 433    | 403  | Sync history is not enabled for this account (`users`.`history-enabled` = 0)      |
| 434    | 409  | This history entry was encrypted with a previous password and cannot be decrypted |
| 452    | 429  | A deletion code has already been requested                                        |
| 500    | 500  | Internal error (details only in the server log)                                   |
| 503    | 503  | Service temporarily unavailable                                                   |

---

## 7. Endpoints

### 7.1 `GET /status`

Health check, no authentication.

```json
{ "status": "Successful", "code": 200,
  "data": { "reachable": true, "database": true, "schema": true,
            "schema-details": { "keys": true, "snapshots": true,
                                "snapshots-multi-service": true,
                                "rate-limits": true, "otp": true,
                                "otp-change-code": true,
                                "password-change-code": true,
                                "history-permission": true,
                                "legacy-mirror": true },
            "mailer": true, "services": ["notefox"],
            "api-version": "2.0",
            "server-time": "2024-05-01 10:00:00",
            "server-timezone": "UTC" } }
```

`database: false` means the DBMS is unreachable (no detail is ever exposed),
`schema: false` that the additive DDL above has not been applied yet - including
the `service` column of the snapshot table, without which the data endpoints
answer `503` instead of failing - `mailer: false` that SMTP/Composer are not
available (no email will go out). `services` lists the services configured on
this installation.

`schema-details` tells **which** piece of the additive DDL is missing (booleans
only, no table name and no DBMS message): `keys` is the `user_keys` table,
`snapshots`/`snapshots-multi-service` the snapshot table and its `service`
column, `rate-limits` the counters table, `otp` the `otp-enabled` column,
`otp-change-code` the columns the OTP disable flow needs,
`password-change-code` the column the password change needs and
`history-permission` the `history-enabled` column of block 10. It is the first
thing to check when an endpoint degrades.

`history-permission: false` means the column does not exist yet: the sync
history is then denied to **every** account (`433`), because the core reads a
missing flag as "not granted". It is not part of `schema`, since nothing else
degrades without it.

`legacy-mirror` is **not** part of the additive DDL: it is the v1 mirror table
configured in `include/credentials.php` (`$services[...]["legacy-table"]`).
`false` means that table does not exist in this database (renamed, moved to
another database, never imported) or lost one of the columns the API reads (`id`, `user-id`, `data`, `inserted-date`,
`updated-locally-date`,
`ip-address`). The sync then works on the snapshot only: the history is empty
and the v1 clients stop seeing the notes written through v2. It used to make
`POST /data/get` answer `401`/HTTP `503` ("Database connection error") while
the connection was perfectly fine.

> The existence of a table or of a column is asked to `information_schema` and,
> when that answer is negative or the query is refused (some shared hostings
> restrict it), confirmed with `SHOW TABLES` / `SHOW COLUMNS`. An inconclusive
> answer is never cached, so a transient failure cannot make the API believe -
> for the whole request - that the schema is incomplete.

---

### 7.2 Signup

#### `POST /signup`

```json
{ "username": "Sara", "password": "...", "email": "sara@example.com" }
```

→ `{ "verification-required": true }`

**The email verification code is ALWAYS sent.** The address is the root of the
identity (`users`.`email` is the SHA-512 used as the user-id, and the whole
encryption chain depends on it), so an account can never become `verified`
without proving the ownership of the mailbox. This has nothing to do with
`otp-enabled`, which only concerns the login: even an account that will have
2FA disabled must go through `POST /signup/verify`. Until then it obtains no
token and cannot write any data.

**The answer is identical** whether the address is free, already registered or
registered but never verified (v1 replies 416/419 and lets anyone enumerate the
accounts). A pending, never verified signup is overwritten by the new
credentials and a new code is emailed.

#### `POST /signup/verify`

```json
{ "email": "...", "password": "...", "verification-code": "A1B2C3" }
```

→ `{ "verified": true, "username": "Sara", "encryption-ready": true }`

Activates the account, consumes the code and creates the DEK.
Errors: `410` wrong credentials, `414` already verified, `412`/`413`/`420` about
the code.

#### `POST /signup/verify/get-new-code`

```json
{ "email": "...", "password": "..." }
```

→ `{ "verification-required": true }` (always the same, rate limited).

---

### 7.3 Login

#### `POST /login`

```json
{ "email": "...", "password": "..." }
```

OTP enabled (default):

```json
{ "otp-required": true, "login-id": "...", "verification-expiry": "..." }
```

OTP disabled:

```json
{ "otp-required": false, "login-id": "...", "token": "...",
  "expiry": null, "username": "Sara", "encryption-ready": true }
```

Errors: `410` wrong credentials, `411` account not verified/active, `429` rate
limit.

#### `POST /login/verify`

```json
{ "login-id": "...", "email": "...", "password": "...",
  "verification-code": "A1B2C3" }
```

→ `{ "login-id": "...", "token": "...", "expiry": null, "username": "Sara",
     "encryption-ready": true }`

The "just logged in" email goes to the address of the account, not to the one
in the payload (v1 vulnerability). The code is consumed and the attempts are
limited to 5.

#### `POST /login/verify/get-new-code`

```json
{ "login-id": "...", "email": "...", "password": "..." }
```

→ `{ "otp-required": true, "login-id": "...", "verification-expiry": "..." }`

#### `POST /login/check-id`

```json
{ "login-id": "...", "token": "..." }
```

→ `{ "valid": true, "username": "Sara", "expiry": null, "otp-enabled": true }`

#### `POST /login/set-expiry`

```json
{ "login-id": "...", "token": "...", "expiry": "2025-01-01 00:00:00" }
```

`expiry: null` removes the expiry. The value is validated (v1 stores it as-is).
→ `{ "login-id": "...", "expiry": "...", "old-expiry": null }`

#### `POST /token/set-expiry`

Same body. It only updates the token of the session, matched by its primary key (v1 matches by ciphertext and always
returns a wrong `old_expiry` because of a
spurious `fetch_assoc()`).
→ `{ "expiry": "...", "old-expiry": null }`

#### `POST /logout`

```json
{ "login-id": "...", "token": "...", "all-devices": false }
```

→ `{ "logged-out": true, "all-devices": false }`

**The token is mandatory**: in v1 knowing a `login-id` was enough to terminate
somebody else's sessions.

---

### 7.4 Two-factor authentication (OTP) - **login only**

Enabled by default on every account, including the existing ones. This setting
affects **only the login**: it never disables the email verification required
by the signup (see 7.2), nor the confirmation code always required to change
the password or to delete the account (see 7.6).

#### `POST /otp/status`

```json
{ "login-id": "...", "token": "..." }
```

→ `{ "otp-enabled": true }`

#### `POST /otp/enable`

```json
{ "login-id": "...", "token": "...", "password": "...", "email": "..." }
```

→ `{ "otp-enabled": true }` — requires the password, sends a notification
email. `431` if it is already enabled.

#### `POST /otp/disable`

```json
{ "login-id": "...", "token": "...", "password": "...", "email": "..." }
```

→ `{ "verification-required": true, "verification-expiry": "..." }`

Requires a valid token **and** the password, then emails a confirmation code:
stealing a token is not enough to weaken the account.
`503` when block 6 of the DDL has not been applied yet (the code could neither
be stored nor checked, so no code is emailed and the 2FA stays enabled).

#### `POST /otp/disable/verify`

```json
{ "login-id": "...", "token": "...", "password": "...",
  "verification-code": "A1B2C3", "email": "..." }
```

→ `{ "otp-enabled": false }` — single-use code, expiry, max 5 attempts,
notification email. `503` when block 6 of the DDL is missing, exactly like the
first step.

---

### 7.5 Sync (per service)

All three endpoints accept the optional `service` field (default `notefox`) and
always echo it back, so a client can never mix up the answers of two services.
An unknown or malformed service is refused with `400` before anything is
written; if the `service` column has not been created yet the answer is `503`.

#### `POST /data/insert`

```json
{ "login-id": "...", "token": "...", "service": "notefox",
  "data": "<json>",
  "updated-locally": "2024-05-01 10:00:00", "base-revision": 41 }
```

Success:

```json
{ "status": "Successful", "code": 200,
  "data": { "service": "notefox", "revision": 42,
            "updated-server": "2024-05-01 10:00:02",
            "updated-locally": "2024-05-01 10:00:00" } }
```

Conflict (HTTP 409):

```json
{ "status": "Error", "code": 409, "description": "Revision conflict",
  "data": { "service": "notefox", "revision": 47,
            "updated-server": "...", "updated-locally": "...",
            "data": "<current json>" } }
```

`base-revision` is optional: without it the write is always accepted. It is
compared against the revision **of that service only**.
`updated-locally` must be a real date, not in the future (`400` otherwise);
`data` above 1.5 MB → `413` and nothing is written.
Only a service that declares a `legacy-table` (today Notefox) also inserts a
new row in the v1 data table, building the sync history; any other service
never touches a v1 table.

#### `POST /data/get`

```json
{ "login-id": "...", "token": "...", "service": "notefox" }
```

→ `{ "service": "notefox", "data": "<json>", "revision": 42,
     "updated-locally": "...", "updated-server": "..." }`

`201` when that service has never synced anything (a valid service with no data
is "no data", not an error about the service) - including the case of a v1
mirror table that cannot be read (`legacy-mirror: false` in `GET /status`):
what can be read is answered, what cannot is "no data", never a `401`/`503`.

The data key is **not** mandatory here: an account that has never logged in
through v2 (or an installation whose `user_keys` table has not been created yet)
has no key, and the notes are read from the v1 legacy row of the service, which
is encrypted with the account password. `430` is therefore only answered when
the payload really cannot be decrypted by any of the two paths - it used to be
answered for every account without a key, hiding a perfectly readable payload.

#### `POST /data/get/last-update`

Same body, **token mandatory** (in v1 the `login-id` alone was enough). Nothing
is decrypted here, so the answer never depends on the data key either.
→ `{ "service": "notefox", "revision": 42, "updated-locally": "...",
     "updated-server": "..." }`

#### `POST /data/services`

```json
{ "login-id": "...", "token": "..." }
```

→

```json
{ "status": "Successful", "code": 200,
  "data": { "services": [ { "service": "notefox", "revision": 42,
                            "updated-server": "...",
                            "updated-locally": "..." } ],
            "supported": ["notefox"],
            "history-enabled": false } }
```

Inventory of the services that hold data for the authenticated account.
`supported` is the registry of this installation. The payloads are never
decrypted here, so the answer is cheap and exposes no note.

`history-enabled` is the sync history permission of **this account**
(`users`.`history-enabled`, `false` by default): a client reads it here to hide
the history instead of discovering the `433` only after the user asked for it.
It is read-only - no endpoint can change it.

#### `POST /data/get/history`

```json
{ "login-id": "...", "token": "...", "service": "notefox" }
```

→

```json
{ "status": "Successful", "code": 200,
  "data": { "service": "notefox",
            "entries": [
              { "id": 512, "inserted-date": "2024-05-01 10:00:02", "updated-locally-date": "2024-05-01 10:00:00" },
              { "id": 498, "inserted-date": "2024-04-28 09:12:44", "updated-locally-date": "2024-04-28 09:12:40" }
            ] } }
```

Dated list (newest first, up to 30 entries) of the past synced versions of a
service, read from its legacy data table. Every v2 sync inserts a new row, so
the history grows with each save (the snapshot table only holds the current
version). A periodic cleanup keeps at most 200 entries per account. Only the
metadata is returned, never decrypted: cheap enough to be called on every page
load. `432` **only** when the service declares no `legacy-table`: a service that
declares one but has nothing stored yet - or whose table cannot be read at all (`legacy-mirror: false`) - answers
`entries: []`, never a conflict and never a
database error.

`433` when the account has no sync history permission (`users`.`history-enabled` = `0`, the default, or block 10 never
applied). The
check happens **before** anything is read, so a denied account cannot even tell
whether it has any stored version; `POST /data/services` reports the permission
in `history-enabled`. Denying the history changes nothing else: the sync itself,
`POST /data/get` and the current snapshot keep working exactly as before.

#### `POST /data/get/history/download`

```json
{ "login-id": "...", "token": "...", "service": "notefox", "id": 498 }
```

→

```json
{ "status": "Successful", "code": 200,
  "data": { "service": "notefox", "id": 498,
            "inserted-date": "2024-04-28 09:12:44",
            "updated-locally-date": "2024-04-28 09:12:40",
            "data": "<plaintext notes json>" } }
```

`id` is one of the ids returned by `POST /data/get/history`. The server
decrypts before answering, exactly like `POST /data/get`. `433` when the account
has no sync history permission (checked before any row is read or decrypted),
`432` when the service declares no `legacy-table`, `201` when `id` does not
exist (or does not belong to this account), `434` when the entry exists but was
encrypted with a previous password and cannot be decrypted (a password change
re-encrypts all legacy rows it can open, but entries encrypted with an even
older password remain unreadable).

---

### 7.6 Account

Changing the password and deleting the account are the two **critical**
operations of an account: both of them **always** require the code emailed to
the address of the account, whatever the value of `otp-enabled` (which is a **login** setting only). A stolen token, or
a session left open on a borrowed
device, is therefore never enough to take an account over.

#### `POST /password/edit`

```json
{ "login-id": "...", "token": "...", "password": "...",
  "new-password": "...", "email": "..." }
```

→ `{ "verification-required": true, "verification-expiry": "..." }`

First step: **nothing is written**, a confirmation code is emailed to the
address of the account (accepted only when its hash matches the authenticated
account). A valid token **and** the current password are both required.
`new-password` is optional here: when it is sent its rules (at least 8
characters, different from the current one) are checked right away, so no email
is sent for a change that could never be completed.

#### `POST /password/edit/get-new-code`

```json
{ "login-id": "...", "token": "...", "password": "...", "email": "..." }
```

→ `{ "verification-required": true, "verification-expiry": "..." }` — resend,
rate limited (3 per 15 minutes). `415` when no change has been requested.

#### `POST /password/edit/verify`

```json
{ "login-id": "...", "token": "...", "password": "...",
  "new-password": "...", "verification-code": "A1B2C3", "email": "..." }
```

→ `{ "login-id": "<new>", "token": "<new>", "expiry": null }`

Second and last step: single-use code, expiry, max 5 attempts. Only the DEK is
re-wrapped: **no note is re-encrypted**, and since the key is a single one for
the whole account the data of **every service** stays readable.
The username and **all** legacy rows of each service that has a legacy table are
re-encrypted with the new password (rows encrypted with an even older password
are silently skipped), every other session is invalidated and a new session is
returned to the caller. Everything in one transaction: on an error nothing is
left half-written (v1 could leave the account in a corrupt state and answer a
literal `null`).

#### `POST /delete`

```json
{ "email": "...", "password": "..." }
```

→ `{ "verification-required": true, "verification-expiry": "..." }` — emails a
deletion code. `452` if one has already been requested and is still valid.

#### `POST /delete/verify`

```json
{ "email": "...", "password": "...", "deleting-code": "A1B2C3" }
```

→ `{ "deleted": true }` — deletes, in one transaction, the tokens, the logins,
the legacy data of every service that has a mirror table, the snapshots of **every service**, the encryption keys and
the user row.

#### `POST /delete/verify/get-new-code`

```json
{ "email": "...", "password": "..." }
```

→ `{ "verification-required": true, "verification-expiry": "..." }`

---

### 7.7 Diagnostics

#### `POST /error-logs/insert`

Same parameters as v1, with length limits on every field and a rate limit per
IP. No authentication. → `data: null`.

#### `POST /telemetry/insert`

Same parameters as v1, with length limits and a rate limit per IP.
→ `data: null`.

---

## 8. Rate limits

| Bucket                                   | Subject           | Limit                     |
|------------------------------------------|-------------------|---------------------------|
| `login`                                  | email             | 10 / 15 min               |
| `login-ip`                               | IP                | 30 / 15 min               |
| `otp-verify`                             | login-id          | 5 / 15 min                |
| `otp-resend`                             | email or login-id | 3 / 15 min                |
| `signup-ip` / `signup-email`             | IP / email        | 10 and 5 per hour         |
| `signup-verify`                          | email             | 10 / 15 min               |
| `otp-change` / `otp-change-verify`       | user              | 5–10 / 15 min             |
| `password-edit` / `password-edit-verify` | user              | 5 / 15 min                |
| `delete-request` / `delete-verify`       | email             | 3 per hour / 5 per 15 min |
| `data-insert`                            | user              | 120 / min                 |
| `data-services`                          | user              | 60 / min                  |
| `data-history`                           | user              | 60 / min                  |
| `data-history-download`                  | user              | 30 / min                  |
| `error-logs` / `telemetry`               | IP                | 30 / 10 min               |

Beyond the limit: `429`, with a temporary block. The counters live in
`rate_limits` (`$rate_limits_table`) and the subject is always stored hashed.
The limits are per **account**, not per service: they protect the shared
platform.

---

## 9. Coexistence between v1 and v2

- The same account can be used **at the same time** by a v1 client and a v2
  client.
- Every v2 write **on the `notefox` service** inserts a new row in the v1 data
  table (`$data_table`, `data` on notefox.eu), in the v1 format (encrypted with
  the password), with a date that is never older than the existing ones: the old
  extension reads the newest row and keeps seeing up to date notes. A service
  without a `legacy-table` has no mirror at all. A periodic cleanup (`include/periodic-checks/check-data.php`) keeps at
  most 200 rows per account.
- Every v2 read of such a service checks whether a v1 client wrote more
  recently and, if so, promotes that row into the snapshot by increasing the
  revision.
- A token issued by v2 works on the v1 endpoints, and vice versa: the account
  layer is the same for everybody.
- The v1 data table stays the exclusive property of Notefox. Its rows are the
  sync history: a periodic cleanup removes the oldest when a user exceeds 200.
- A v2 client that never sends `service` behaves exactly as before the
  multi-service change.

### What v1 gained (and only that)

The v1 API has **no new parameter, no new field, no new error code**: what it
accepts and what it answers is byte for byte what it has always been. What it
gained is the cleanup of the two tables it predates (`user_keys`,
`sav_data_current`), implemented in `include/v1-v2-compat.php` - a bridge that
never prints, never throws, never stops the script and is a **no-op whenever
the v2 table or column does not exist**, so an installation that has not run
`install/migration.sql` behaves exactly as before.

| v1 endpoint           | Side effect added                                                                                                                                                                                                                                                                                                                             |
|-----------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `POST /password/edit` | the DEK is unwrapped with the old password and re-wrapped with the new one (no note is re-encrypted), `password-v2` is realigned, and the rows used as the v1 mirror of a snapshot are re-encrypted even when the `LIMIT 50` would miss them - without this the account would become `encryption-ready: false` and its snapshot undecryptable |
| `POST /delete/verify` | the key and the snapshots of every service are deleted too: the user-id is the SHA-512 of the email, so a new signup with the same address used to **inherit** them                                                                                                                                                                           |
| `POST /data/insert`   | the stale `legacy-data-id` of the snapshot is cleared, so the next v2 write creates a fresh mirror row instead of overwriting the one v1 has just written. The promotion of that row into the snapshot (`revision` + 1) keeps happening on the v2 side, at the first read                                                                     |

The operational consequences (which files to upload, what breaks if they are
not) are in [`install/DEPLOY.md`](install/DEPLOY.md).

---

## 10. Tests

```bash
php api/v2/tests/smoke.php
```

Checks, without any database and without sending any email: envelope
encryption (including the password change), one-time codes, identifiers, date
validation, the service registry (default, normalisation, refused names, which
services own a legacy mirror), the regression check that the signup never reads
`otp-enabled`, the regression check that the password change and the account
deletion always require the emailed code (and never look at `otp-enabled`),
revision conflict resolution, the error catalogue and the email template. It
also checks the v1 bridge (`include/v1-v2-compat.php`): that it never prints,
never stops the script and never loads the v2 bootstrap, that every one of its
functions is a no-op without the additive tables, and that the three patched v1
endpoints still answer with their own `echo_result()`/`echo_error()`. It exits
with a non-zero status when a check fails.

```bash
php api/v2/tests/schema-check.php
```

The deploy preflight. Unlike `smoke.php`, it **needs a configured database**:
it connects with the credentials of `include/credentials.php` and prints, block
by block, the same verdict as `GET /status` - it reuses the very same helpers (`db_has_table()`, `db_has_column()`,
`v2_sync_legacy_mirrors_ready()`), so the
two diagnoses cannot diverge. It also checks the configuration itself (`$user_keys_table`, `$data_current_table`,
`$rate_limits_table`, the
`$services` registry, the mailer). It writes nothing and sends no email. Exit
code `1` when a mandatory block is missing, `0` with warnings when only the
optional blocks or the legacy mirror are.
