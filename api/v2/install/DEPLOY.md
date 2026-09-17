# Sav Account API v2 - deploy runbook

Operational procedure to put `/api/v2` online on an installation that is
already serving `/api/v1`. The specification of the schema and of the endpoints
stays in [`../API.md`](../API.md); the SQL lives in
[`migration.sql`](migration.sql).

Two facts to keep in mind while reading:

- **v1 keeps working, with the same requests and the same answers.** Every DDL
  statement is additive: no v1 table is renamed, emptied or altered in a way v1
  can notice. The two APIs share the same database and the same accounts. Three
  v1 endpoints do gain a side effect - the cleanup of the new tables they could
  not know about - without a single change to what they accept and return: see
  [the v1 side](#the-v1-side-changes-done-from-the-old-api).
- **There is no data migration script**, and none is needed - see
  [Data migration](#5-data-migration-there-is-no-script).

---

## 0. Before starting

- [ ] **Full backup of the database** (phpMyAdmin → Export, or `mysqldump`).
      This is the only real rollback for block 2b.
- [ ] Row count of the snapshot table, if it already exists
      (`SELECT COUNT(*)` on `notefox_data_current`).
      Block 2b rebuilds the primary key, which rewrites and locks the whole
      table: on a large table, plan a short maintenance window.
- [ ] PHP ≥ 7.4 with `mysqli`, `openssl`, `json` and `mbstring`.
- [ ] `mod_rewrite` active: `api/v2/.htaccess` rewrites the URLs without a
      trailing slash internally, **without a redirect**, because a 301 would
      turn a POST into a GET and lose the body.

---

## 1. Upload the files

Upload the whole `api/v2/` folder, **including** `.htaccess` and `include/`.

Outside `api/v2/`, four more files change - they are what keeps v1 from
leaving the new tables inconsistent (see
[the v1 side](#the-v1-side-changes-done-from-the-old-api)):

| File | Why |
| --- | --- |
| `include/v1-v2-compat.php` | **new**, the bridge itself |
| `api/v1/password/edit/index.php` | re-wraps the data key with the new password |
| `api/v1/delete/verify/index.php` | deletes the key and the snapshots too |
| `api/v1/data/insert/index.php` | invalidates the stale mirror pointer |

Plus `include/credentials.php` (step 3). Uploading the three v1 files without
`include/v1-v2-compat.php` makes them fail with a fatal error: upload the
bridge **first**, or all four together.

---

## 2. Dependencies

```
cd api/v2 && composer install --no-dev --optimize-autoloader
```

Symfony Mailer 5.4 (the last version supporting PHP 7.4). If Composer cannot be
run on the hosting, `api/v2/include/mailer.php` also looks for the `vendor/` of
`test-email/` and the one at the project root, in that order: uploading one of
those is enough.

SMTP credentials: `$email_address`, `$email_password`, `$email_smtp`,
`$email_smtp_port` in `include/credentials.php` (the same ones `test-email/`
already uses). Without them `GET /status` answers `mailer: false` and **no
verification code can be emailed**, so no login and no signup can complete.

---

## 3. Configuration

In `include/credentials.php` (see `include/credentials-sample.php`):

```php
$user_keys_table    = "user_keys";
$data_current_table = "sav_data_current";   // was notefox_data_current
$rate_limits_table  = "rate_limits";

$services = array(
    "notefox" => array(
        "name"         => "Notefox",
        "legacy-table" => $data_table,   // the v1 mirror table
    ),
);
```

> **The most common mistake**: running block 2b (the `RENAME`) and forgetting to
> update `$data_current_table`. The table is perfectly fine but the API looks
> for the old name, so **every `data/*` endpoint answers `503`**. The preflight
> of step 6 catches it before any client does.

If `$services` is missing, the core falls back to the single `notefox` service
using `$data_table` as its legacy mirror: an installation that has not been
updated keeps working.

---

## 4. Run the SQL

Execute [`migration.sql`](migration.sql) in phpMyAdmin (or
`mysql -u USER -p DATABASE < api/v2/install/migration.sql`).

**Choose one single branch of block 2:**

| Situation | Branch |
| --- | --- |
| No snapshot table yet | **2a** - `CREATE TABLE sav_data_current` (already multi-service) |
| `notefox_data_current` already exists | **2b** - `RENAME`, then `ADD COLUMN service`, then rebuild the primary key |

The file ships with 2a active and 2b commented out: on an existing
installation, comment out 2a and uncomment 2b.

Inside 2b the order is not negotiable: the `service` column, **with its
`DEFAULT 'notefox'`**, must exist before the primary key is rebuilt, otherwise
the existing rows have no key. The `DEFAULT` is also what assigns every
pre-existing snapshot to the Notefox service, so **no `UPDATE` is needed** and
nothing is re-encrypted.

**Before blocks 4 to 10, set the names of the existing v1 tables.** Those blocks
only add columns and indexes to tables that already exist, and their names are
not the same on every installation: they are declared once in the
`SET @v2_users_table / @v2_logins_table / @v2_tokens_table / @v2_data_table`
block that precedes block 4. The data table is `data` on notefox.eu (it is
called `notefox_data` in the older documentation), so check with `SHOW TABLES;`
and keep those four values in sync with `$users_table`, `$logins_table`,
`$tokens_table` and `$data_table` of `include/credentials.php` - `$data_table`
is also the `"legacy-table"` of the notefox entry of `$services`.

Right after those four `SET`, the file runs a `SELECT` that prints `found` or
`MISSING` for each one: read it before anything else.

Blocks 4 to 10 are **re-runnable**: every column and every index is added only
when `information_schema` says it is missing, so running the file twice is a
no-op instead of stopping with `#1060 - Duplicate column name` (or `#1061 -
Duplicate key name`). A table that does not exist under the configured name is
skipped as well, instead of aborting with `#1146 - Table ... doesn't exist`.

> If you ran an older copy of `migration.sql` and it stopped with `#1060 -
> Duplicate column name 'otp-enabled'` or `#1146 - Table '....notefox_data'
> doesn't exist`, **the blocks after the failing one were never applied**: both
> phpMyAdmin and the `mysql` client abort the script at the first error. Just run
> the current `migration.sql` again (with the right branch of block 2 selected
> and the four table names set) and then check the result with
> `php api/v2/tests/schema-check.php`.
>
> `#1146` in particular means the name of a v1 table is wrong: on notefox.eu the
> data table is `data`, not `notefox_data`. Fixing `@v2_data_table` is not
> enough - `$data_table` and the notefox `"legacy-table"` of `$services` must
> carry the same name, otherwise `GET /status` keeps reporting
> `legacy-mirror: false` and the v1 clients stop seeing the notes written
> through v2.

Block 2b is the only non-idempotent one (`RENAME` + `DROP PRIMARY KEY`): it must
be run exactly once, and it ships commented out.

Mandatory blocks: **1, 2, 3, 4, 5, 8**. Optional: 6, 7, 9, 10 (see the header of
`migration.sql` for what degrades without each one).

> Block 10 (`users`.`history-enabled`) grants **nothing** by itself: the sync
> history is a per-account permission whose default is `0`, and a missing column
> is read as `0` too, so before and after this block the two
> `data/get/history*` endpoints answer `433` to everybody. What the block adds
> is the *possibility* of granting it - see
> [Granting the sync history](#7bis-granting-the-sync-history).

---

## 5. Data migration: there is no script

**Nothing has to be migrated by hand, and no batch job could do it.**

The encryption model is: the password derives the KEK (PBKDF2), the KEK wraps
the DEK, the DEK encrypts the data. The plaintext password exists **only**
during an authentication request, so a batch job has no way to derive the KEK
of an account: re-encrypting every account offline is impossible by design.

The migration is therefore **lazy, per account**. At the first successful v2
authentication - `api/v2/login/index.php`, `api/v2/login/verify/index.php`,
`api/v2/signup/verify/index.php` - `v2_get_or_create_dek()`
(`api/v2/include/keys.php`):

1. generates the DEK and stores it wrapped in `user_keys`;
2. calls `v2_sync_bootstrap_from_legacy()` (`api/v2/include/sync.php`) for every
   service that declares a `legacy-table`: the latest row of the legacy table
   (today the v1 `data` table) is promoted to the snapshot in `sav_data_current`,
   `revision = 1`, `service = 'notefox'`, re-encrypted with the DEK;
3. fills in `password-v2` (the modern hash, kept aligned with the SHA-512 one
   that v1 needs in its `WHERE` clauses).

Consequences, all of them intended:

- An account that never logs in through v2 has **no row** in `user_keys` and
  **no row** in `sav_data_current`. It is not broken: it keeps working on v1
  exactly as before, and it will be migrated the day it logs in.
- The historical rows of the v1 data table are **never** deleted or modified by
  the bootstrap: v1 keeps reading them.
- After the bootstrap, every v2 write also refreshes the single v1 mirror row,
  so a not-yet-updated extension keeps seeing the latest notes.
- `POST /data/get` also works **before** the key exists: the notes are then read
  from the v1 legacy row (encrypted with the password). A missing key is not an
  error by itself.

### The v1 side: changes done from the old API

The lazy migration covers the accounts that *arrive* at v2. The opposite
direction - an account that has already been migrated and then does something
**from v1** - needs the three side effects v1 cannot know about, because the
tables they live in did not exist when it was written. They are implemented in
`include/v1-v2-compat.php` and called from three v1 endpoints.

Request parameters and answers of those endpoints are **unchanged**: the bridge
never prints, never throws and never stops the script, and every one of its
functions is a **no-op when the v2 table (or column) does not exist**, so an
installation that has not run `migration.sql` behaves exactly as before. The
regression checks of `php api/v2/tests/smoke.php` enforce all of this.

| From v1 | What was left behind | What the bridge does |
| --- | --- | --- |
| `POST /password/edit` | `user_keys`.`wrapped-key` still encrypted with the **old** password (`encryption-ready: false`, snapshot undecryptable), a stale `password-v2`, and the mirror row possibly missed by the `LIMIT 50` | unwraps the DEK with the old password and re-wraps it with the new one (no note is re-encrypted), realigns `password-v2`, re-encrypts the newest 50 rows **and** the rows pointed at by `legacy-data-id` |
| `POST /delete/verify` | `user_keys` and `sav_data_current` rows survived; since the user-id is the SHA-512 of the email, a new signup with the same address **inherited** them | deletes both, in the same request |
| `POST /data/insert` | the snapshot kept pointing at an older row as its v1 mirror | clears `legacy-data-id`, so the next v2 write creates a fresh mirror row instead of overwriting the one v1 just wrote |

> The password change is the important one. Without the bridge, changing the
> password from an old extension makes the v2 data key unopenable: the account
> keeps working (v2 falls back to the v1 mirror row) but its snapshot is lost.
> If the unwrap fails anyway - the key was wrapped with some other password -
> the row is retired (`status` = 0) instead of being left broken, and the next
> v2 login creates a fresh key from the legacy data.

---

## 6. Verification

```
php api/v2/tests/schema-check.php
```

Connects to the configured database and prints the same verdict as
`GET /status`, block by block. Exit code `1` when a mandatory element is
missing, `0` (with warnings) when only the optional blocks or the legacy mirror
are. It writes nothing and sends no email.

```
php api/v2/tests/smoke.php
```

Pure functions only (envelope encryption, codes, validators, service registry)
plus the regression checks on the v1 bridge and on the sync history permission:
no database, no email. Must stay at `104/104` and exit `0`.

```
GET https://notefox.eu/api/v2/status
```

Expected: `database: true`, `schema: true`, `mailer: true` and, inside
`schema-details`, every flag `true` - including `snapshots-multi-service`,
`password-change-code`, `history-permission` and `legacy-mirror`.

`history-permission: false` only means block 10 has not been run: nothing
degrades, the sync history is simply denied to every account (`433`).

Then, on a test account: `POST /login` → `POST /login/verify` →
`POST /data/get` (the notes written through v1 must come back) →
`POST /data/insert` → check that the v1 extension still sees the new data.

---

## 7. Troubleshooting

Always start from `schema-details` of `GET /status` (or from the preflight,
which prints the same information with the name of the affected endpoint).

| Symptom | Flag | Cause |
| --- | --- | --- |
| every `data/*` answers `503` | `snapshots` / `snapshots-multi-service` false | block 2 not applied, or `$data_current_table` still pointing at `notefox_data_current` after the `RENAME` |
| `POST /password/edit` answers `500` | `password-change-code` false | block 8 not applied |
| `POST /otp/disable` answers `503` | `otp-change-code` false | block 6 not applied |
| `POST /data/get/history` answers `433`, the "Sync history" link is hidden | `history-permission` false | block 10 not applied - or, with the block applied, that account still has `history-enabled` = 0 (the default): see [Granting the sync history](#7bis-granting-the-sync-history) |
| the v1 extension no longer sees the new notes, the history is empty | `legacy-mirror` false | the table in `$services[...]["legacy-table"]` does not exist (wrong name - it is `data` on notefox.eu, not `notefox_data` - renamed, moved, never imported) or lost one of the columns the API reads (`id`, `user-id`, `data`, `inserted-date`, `updated-locally-date`, `ip-address`) |
| no code is ever received | `mailer` false | Composer not installed or SMTP credentials missing |
| every answer is `401`/HTTP `503` | `database` false | credentials, or the DBMS is down (details only in the server log) |
| POST bodies arrive empty | - | `mod_rewrite` off: the URL without the trailing slash is being 301-redirected |
| the accounts have `encryption-ready: false` | `keys` false | block 1 not applied |
| one single account has `encryption-ready: false` after a password change | - | its password was changed from v1 without `include/v1-v2-compat.php` on the server: upload the four files of step 1. The account recovers at the next v2 login (a new key is created from the legacy data) |
| a brand new account already sees somebody else's notes | - | the previous account with the same email was deleted from v1 without the bridge: delete its leftover rows in `user_keys` and `sav_data_current` by `user-id` |
| `Call to undefined function v1v2_...()` in the v1 log | - | `include/v1-v2-compat.php` was not uploaded |

---

## 7bis. Granting the sync history

The `POST /data/get/history` and `POST /data/get/history/download` endpoints -
and so the "Sync history" page of `my/account/` - are reserved to the accounts
that are **explicitly** allowed to use them. The permission is the
`users`.`history-enabled` column of block 10: `0` by default, and a column that
does not exist yet is read as `0` as well, so the history is denied until it is
granted. Everything else - the sync, `POST /data/get`, the snapshot, the v1
mirror - is completely unaffected.

There is **no endpoint** to change it, on purpose: a user must never be able to
grant it to themselves. It is a manual `UPDATE`, one account at a time. Keep in
mind that `users`.`email` stores the SHA-512 hex of the address, never the
address itself:

```sql
-- grant
UPDATE `users` SET `history-enabled` = 1
 WHERE `email` = SHA2(LOWER('someone@example.com'), 512);

-- revoke
UPDATE `users` SET `history-enabled` = 0
 WHERE `email` = SHA2(LOWER('someone@example.com'), 512);

-- who has it right now
SELECT `email` FROM `users` WHERE `history-enabled` = 1;
```

The change takes effect on the **next request**: the permission is read from the
`users` row at every authentication, so no session has to be invalidated and
nobody has to log out and in again.

A client reads the current value in the `history-enabled` field of
`POST /data/services` (read-only), which is how `my/account/` hides the "Sync
history" link for the accounts that do not have the permission.

---

## 8. Rollback

The additive part needs no rollback: v1 ignores the new tables and the new
columns, and v2 stops being reachable as soon as `api/v2/` is removed.

If block 2b has to be undone:

```sql
RENAME TABLE `sav_data_current` TO `notefox_data_current`;
```

and restore `$data_current_table = "notefox_data_current";` in
`include/credentials.php`. The `service` column and the composite primary key
can stay: the old snapshot table was only ever read by v2.

Optionally:

```sql
DROP TABLE `user_keys`;
DROP TABLE `rate_limits`;
```

> Dropping `user_keys` **destroys the wrapped DEKs**. The data written through
> v2 is mirrored in the v1 table and stays readable by v1, but the snapshots in
> `sav_data_current` become undecryptable. Restore from the backup of step 0
> instead of dropping it, unless the accounts have never logged in through v2.

The columns added to `users` and `logins` can always be left in place: v1 never
selects them by name and every one of them has a default.

`include/v1-v2-compat.php` and the three v1 endpoints can be left in place too:
once the v2 tables are gone every function of the bridge becomes a no-op, and
the answers of v1 never depended on it. Removing the bridge alone, without
reverting the three endpoints, is the one thing that must **not** be done: they
would fail with `Call to undefined function`.
