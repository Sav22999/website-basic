<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "API Documentation – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <a href="/alpha/help/" class="back-link">Back to Help</a>

        <style>
            .docs-header {
                margin-bottom: 32px;
            }

            .docs-header p {
                color: var(--color-text-muted);
            }

            .docs-badge {
                display: inline-block;
                font-size: 0.75rem;
                font-weight: 600;
                padding: 2px 10px;
                border-radius: var(--radius-pill);
                text-transform: uppercase;
                letter-spacing: 0.5px;
                vertical-align: middle;
                margin-right: 8px;
            }

            .docs-badge--get {
                background: rgba(40, 167, 69, 0.15);
                color: #69c46d;
            }

            .docs-badge--post {
                background: rgba(66, 133, 244, 0.15);
                color: #6fa8f5;
            }

            .docs-endpoint-path {
                font-family: monospace;
                font-size: 0.9375rem;
                font-weight: 600;
            }

            .docs-toc {
                background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: var(--radius-md);
                padding: 20px 24px;
                margin-bottom: 32px;
            }

            .docs-toc h2 {
                font-size: 1rem;
                margin: 0 0 12px;
            }

            .docs-toc ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .docs-toc li {
                margin-bottom: 6px;
            }

            .docs-toc a {
                color: var(--color-primary);
                text-decoration: none;
                font-size: 0.9375rem;
            }

            .docs-toc a:hover {
                text-decoration: underline;
            }

            .docs-section {
                margin-top: 56px;
                padding-top: 32px;
                border-top: 1px solid var(--color-border);
            }

            .docs-section:first-of-type {
                border-top: none;
                margin-top: 32px;
                padding-top: 0;
            }

            .docs-section > h2 {
                margin-bottom: 20px;
            }

            .docs-section > p {
                margin-bottom: 16px;
            }

            .docs-card {
                background: var(--color-surface);
                border: 1px solid var(--color-border);
                border-radius: var(--radius-md);
                padding: 24px;
                margin-bottom: 24px;
            }

            .docs-card h4 {
                margin: 0 0 10px;
                display: flex;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
            }

            .docs-card > p {
                margin: 0 0 12px;
                color: var(--color-text-muted);
                font-size: 0.9375rem;
            }

            .docs-card > p:last-child {
                margin-bottom: 0;
            }

            .docs-label {
                font-size: 0.8125rem;
                font-weight: 600;
                color: var(--color-text-muted);
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin: 32px 0 8px;
                padding-top: 16px;
                border-top: 1px solid var(--color-border);
            }

            .docs-label:first-child {
                margin-top: 0;
                padding-top: 0;
                border-top: none;
            }

            pre.docs-code {
                background: var(--color-surface-raised);
                border: 1px solid var(--color-border);
                border-radius: var(--radius-sm);
                padding: 14px 16px;
                overflow-x: auto;
                font-size: 0.8125rem;
                line-height: 1.6;
                margin: 6px 0 0;
            }

            code {
                background: var(--color-surface-raised);
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 0.875em;
            }

            pre.docs-code code {
                background: none;
                padding: 0;
                border-radius: 0;
            }

            .docs-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 0.875rem;
                margin: 6px 0 0;
            }

            .docs-table th, .docs-table td {
                text-align: left;
                padding: 8px 12px;
                border: 1px solid var(--color-border);
            }

            .docs-table th {
                background: var(--color-surface);
                font-weight: 600;
                font-size: 0.8125rem;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }

            .docs-table tr:nth-child(even) td {
                background: var(--color-surface);
            }

            .docs-table code {
                font-size: 0.8125rem;
            }

            .docs-field-list {
                margin: 6px 0 0;
                padding: 0;
                list-style: none;
            }

            .docs-field-list li {
                padding: 6px 0;
                border-bottom: 1px solid var(--color-border);
                font-size: 0.9375rem;
            }

            .docs-field-list li:last-child {
                border-bottom: none;
            }

            .docs-field-name {
                font-family: monospace;
                font-weight: 600;
            }

            .docs-field-type {
                color: var(--color-text-muted);
                font-size: 0.8125rem;
            }

            .docs-field-opt {
                color: var(--color-text-muted);
                font-size: 0.75rem;
                font-style: italic;
            }

            .docs-errors {
                display: inline;
            }

            .docs-errors code {
                margin-right: 4px;
            }

            @media (max-width: 640px) {
                .docs-card {
                    padding: 16px;
                }

                pre.docs-code {
                    padding: 12px;
                    font-size: 0.75rem;
                }

                .docs-table {
                    font-size: 0.8125rem;
                }

                .docs-table th, .docs-table td {
                    padding: 6px 8px;
                }
            }
        </style>

        <div class="docs-header">
            <h1>API Documentation</h1>
            <p>Sav Account API v2 &mdash; the REST API that powers Notefox sync, accounts, and data management.</p>
        </div>

        <div class="docs-toc">
            <h2>Contents</h2>
            <ul>
                <li><a href="#overview">Overview</a></li>
                <li><a href="#authentication">Authentication</a></li>
                <li><a href="#conventions">Conventions</a></li>
                <li><a href="#errors">Error codes</a></li>
                <li><a href="#ep-status">Endpoints: Status</a></li>
                <li><a href="#ep-signup">Endpoints: Signup</a></li>
                <li><a href="#ep-login">Endpoints: Login</a></li>
                <li><a href="#ep-otp">Endpoints: Two-factor authentication</a></li>
                <li><a href="#ep-sync">Endpoints: Sync &amp; Data</a></li>
                <li><a href="#ep-account">Endpoints: Account management</a></li>
                <li><a href="#ep-diagnostics">Endpoints: Diagnostics</a></li>
                <li><a href="#rate-limits">Rate limits</a></li>
                <li><a href="#encryption">Encryption model</a></li>
            </ul>
        </div>

        <!-- Overview -->
        <section class="docs-section" id="overview">
            <h2>Overview</h2>
            <p>Base URL: <code>https://notefox.eu/api/v2</code></p>
            <p>The v2 API is a generic account platform called <strong>Sav Account</strong>. The account itself (signup,
                login, sessions, tokens, OTP, rate limiting) is shared and product-neutral. What is partitioned per
                service is only the synchronised data: every service owns its own snapshot and revision. Notefox is the
                first and default service.</p>
            <ul>
                <li>Every endpoint is <strong>POST with a JSON body</strong>, except <code>GET /status</code>.</li>
                <li><code>Content-Type: application/json</code></li>
                <li>Body limited to <strong>2 MB</strong>; the <code>data</code> field to <strong>1.5 MB</strong>
                    (<code>413</code> beyond that).
                </li>
                <li>Responses are always JSON with a real HTTP status.</li>
            </ul>
            <p class="docs-label">Success response</p>
            <pre class="docs-code"><code>{
  "status": "Successful",
  "code": 200,
  "data": { ... }
}</code></pre>
            <p class="docs-label">Error response</p>
            <pre class="docs-code"><code>{
  "status": "Error",
  "code": 402,
  "description": "...",
  "data": null
}</code></pre>
        </section>

        <!-- Authentication -->
        <section class="docs-section" id="authentication">
            <h2>Authentication</h2>
            <p>Most endpoints require a <code>login-id</code> and <code>token</code>, both 64 hexadecimal characters,
                obtained at login. Include them in the JSON body of every authenticated request.</p>
            <p>Sessions can optionally have an expiry date. A session without expiry stays valid until an explicit
                logout or a password change.</p>
        </section>

        <!-- Conventions -->
        <section class="docs-section" id="conventions">
            <h2>Conventions</h2>
            <ul>
                <li><strong>Dates</strong> &mdash; <code>YYYY-MM-DD HH:MM:SS</code> in the server timezone. <code>GET
                        /status</code> returns <code>server-time</code> for clock-skew measurement.
                </li>
                <li><strong>Verification codes</strong> &mdash; 6 characters from
                    <code>23456789ABCDEFGHJKLMNPQRSTUVWXYZ</code> (no ambiguous characters), compared
                    case-insensitively.
                </li>
                <li><strong><code>email</code> in the payload</strong> &mdash; The server only stores the SHA-512 hash.
                    The address is used exclusively as the email recipient and only if the hash matches the account;
                    otherwise <code>410</code>.
                </li>
                <li><strong><code>service</code> in data endpoints</strong> &mdash; Optional, defaults to
                    <code>notefox</code>. Must match <code>^[a-z0-9][a-z0-9_-]{0,31}$</code> and be declared in the
                    server registry.
                </li>
                <li><strong>Sync history</strong> &mdash; A permission, not a feature. The
                    <code>data/get/history*</code> endpoints require <code>history-enabled = 1</code> on the account
                    (default <code>0</code>, no endpoint to change it).
                </li>
            </ul>
        </section>

        <!-- Error codes -->
        <section class="docs-section" id="errors">
            <h2>Error codes</h2>
            <div style="overflow-x: auto;">
                <table class="docs-table">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>HTTP</th>
                        <th>Meaning</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><code>200</code></td>
                        <td>200</td>
                        <td>OK</td>
                    </tr>
                    <tr>
                        <td><code>201</code></td>
                        <td>200</td>
                        <td>No data for this account</td>
                    </tr>
                    <tr>
                        <td><code>400</code></td>
                        <td>400</td>
                        <td>Missing or invalid parameters</td>
                    </tr>
                    <tr>
                        <td><code>401</code></td>
                        <td>503</td>
                        <td>Database unreachable</td>
                    </tr>
                    <tr>
                        <td><code>402</code></td>
                        <td>401</td>
                        <td>login-id missing, disabled, expired or invalid</td>
                    </tr>
                    <tr>
                        <td><code>403</code></td>
                        <td>401</td>
                        <td>Account not found</td>
                    </tr>
                    <tr>
                        <td><code>404</code></td>
                        <td>401</td>
                        <td>Token missing, disabled or expired</td>
                    </tr>
                    <tr>
                        <td><code>405</code></td>
                        <td>401</td>
                        <td>Invalid token</td>
                    </tr>
                    <tr>
                        <td><code>406</code></td>
                        <td>405</td>
                        <td>HTTP method not allowed</td>
                    </tr>
                    <tr>
                        <td><code>407</code></td>
                        <td>413</td>
                        <td>Payload too large</td>
                    </tr>
                    <tr>
                        <td><code>409</code></td>
                        <td>409</td>
                        <td>Revision conflict (response carries current data)</td>
                    </tr>
                    <tr>
                        <td><code>410</code></td>
                        <td>401</td>
                        <td>Invalid credentials</td>
                    </tr>
                    <tr>
                        <td><code>411</code></td>
                        <td>403</td>
                        <td>Account not active / not verified</td>
                    </tr>
                    <tr>
                        <td><code>412</code></td>
                        <td>400</td>
                        <td>Code expired</td>
                    </tr>
                    <tr>
                        <td><code>413</code></td>
                        <td>400</td>
                        <td>Invalid code</td>
                    </tr>
                    <tr>
                        <td><code>414</code></td>
                        <td>409</td>
                        <td>Account already verified</td>
                    </tr>
                    <tr>
                        <td><code>415</code></td>
                        <td>400</td>
                        <td>No code has been requested</td>
                    </tr>
                    <tr>
                        <td><code>419</code></td>
                        <td>409</td>
                        <td>Signup not completed</td>
                    </tr>
                    <tr>
                        <td><code>420</code></td>
                        <td>429</td>
                        <td>Too many wrong attempts, request a new code</td>
                    </tr>
                    <tr>
                        <td><code>429</code></td>
                        <td>429</td>
                        <td>Rate limit reached</td>
                    </tr>
                    <tr>
                        <td><code>430</code></td>
                        <td>409</td>
                        <td>Encryption key unavailable for this account</td>
                    </tr>
                    <tr>
                        <td><code>431</code></td>
                        <td>409</td>
                        <td>OTP is already in the requested state</td>
                    </tr>
                    <tr>
                        <td><code>432</code></td>
                        <td>409</td>
                        <td>Sync history not available for this service</td>
                    </tr>
                    <tr>
                        <td><code>433</code></td>
                        <td>403</td>
                        <td>Sync history not enabled for this account</td>
                    </tr>
                    <tr>
                        <td><code>434</code></td>
                        <td>409</td>
                        <td>History entry encrypted with a previous password</td>
                    </tr>
                    <tr>
                        <td><code>452</code></td>
                        <td>429</td>
                        <td>A deletion code has already been requested</td>
                    </tr>
                    <tr>
                        <td><code>500</code></td>
                        <td>500</td>
                        <td>Internal error (details in server log only)</td>
                    </tr>
                    <tr>
                        <td><code>503</code></td>
                        <td>503</td>
                        <td>Service temporarily unavailable</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Status -->
        <section class="docs-section" id="ep-status">
            <h2>Status</h2>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--get">GET</span> <span class="docs-endpoint-path">/status</span>
                </h4>
                <p>Health check endpoint. No authentication required.</p>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "status": "Successful",
  "code": 200,
  "data": {
    "reachable": true,
    "database": true,
    "schema": true,
    "schema-details": {
      "keys": true,
      "snapshots": true,
      "snapshots-multi-service": true,
      "rate-limits": true,
      "otp": true,
      "otp-change-code": true,
      "password-change-code": true,
      "history-permission": true,
      "legacy-mirror": true
    },
    "mailer": true,
    "services": ["notefox"],
    "api-version": "2.0",
    "server-time": "2024-05-01 10:00:00",
    "server-timezone": "UTC"
  }
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">database</span> <span class="docs-field-type">boolean</span>
                        &mdash; Whether the DBMS is reachable.
                    </li>
                    <li><span class="docs-field-name">schema</span> <span class="docs-field-type">boolean</span> &mdash;
                        Whether the required DDL has been applied.
                    </li>
                    <li><span class="docs-field-name">schema-details</span> <span class="docs-field-type">object</span>
                        &mdash; Per-block DDL status. <code>false</code> means that piece is missing.
                    </li>
                    <li><span class="docs-field-name">mailer</span> <span class="docs-field-type">boolean</span> &mdash;
                        Whether SMTP is available (no emails go out if <code>false</code>).
                    </li>
                    <li><span class="docs-field-name">services</span> <span class="docs-field-type">array</span> &mdash;
                        Services configured on this installation.
                    </li>
                    <li><span class="docs-field-name">server-time</span> <span class="docs-field-type">string</span>
                        &mdash; Current server time for clock-skew measurement.
                    </li>
                </ul>
            </div>
        </section>

        <!-- Signup -->
        <section class="docs-section" id="ep-signup">
            <h2>Signup</h2>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span
                            class="docs-endpoint-path">/signup</span></h4>
                <p>Register a new account. The email verification code is always sent. The response is identical whether
                    the address is free, already registered, or registered but never verified (prevents account
                    enumeration).</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "username": "Sara",
  "password": "...",
  "email": "sara@example.com"
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">username</span> <span class="docs-field-type">string</span>
                        &mdash; Display name.
                    </li>
                    <li><span class="docs-field-name">password</span> <span class="docs-field-type">string</span>
                        &mdash; Account password (min 8 characters).
                    </li>
                    <li><span class="docs-field-name">email</span> <span class="docs-field-type">string</span> &mdash;
                        Email address (used as the identity root).
                    </li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{ "verification-required": true }</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>400</code> <code>429</code></p>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/signup/verify</span>
                </h4>
                <p>Verify the signup email code. Activates the account, consumes the code, and creates the encryption
                    key (DEK).</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "email": "...",
  "password": "...",
  "verification-code": "A1B2C3"
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">email</span> <span class="docs-field-type">string</span> &mdash; The email used at signup.</li>
                    <li><span class="docs-field-name">password</span> <span class="docs-field-type">string</span> &mdash; The account password.</li>
                    <li><span class="docs-field-name">verification-code</span> <span class="docs-field-type">string</span> &mdash; The 6-character code received by email.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "verified": true,
  "username": "Sara",
  "encryption-ready": true
}</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>410</code> <code>414</code> <code>412</code> <code>413</code>
                    <code>420</code></p>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/signup/verify/get-new-code</span>
                </h4>
                <p>Resend the signup verification code. Rate limited.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "email": "...",
  "password": "..."
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">email</span> <span class="docs-field-type">string</span> &mdash; The email used at signup.</li>
                    <li><span class="docs-field-name">password</span> <span class="docs-field-type">string</span> &mdash; The account password.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{ "verification-required": true }</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>410</code> <code>429</code></p>
            </div>
        </section>

        <!-- Login -->
        <section class="docs-section" id="ep-login">
            <h2>Login</h2>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/login</span>
                </h4>
                <p>Authenticate with email and password. If OTP is enabled (default), a verification code is emailed and
                    must be confirmed via <code>/login/verify</code>. If OTP is disabled, the session is returned
                    directly.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "email": "...",
  "password": "..."
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">email</span> <span class="docs-field-type">string</span> &mdash; Account email address.</li>
                    <li><span class="docs-field-name">password</span> <span class="docs-field-type">string</span> &mdash; Account password.</li>
                </ul>

                <p class="docs-label">Response (OTP enabled)</p>
                <pre class="docs-code"><code>{
  "otp-required": true,
  "login-id": "...",
  "verification-expiry": "..."
}</code></pre>

                <p class="docs-label">Response (OTP disabled)</p>
                <pre class="docs-code"><code>{
  "otp-required": false,
  "login-id": "...",
  "token": "...",
  "expiry": null,
  "username": "Sara",
  "encryption-ready": true
}</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>410</code> <code>411</code> <code>429</code></p>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span
                            class="docs-endpoint-path">/login/verify</span></h4>
                <p>Complete login by providing the emailed verification code. The code is consumed and attempts are
                    limited to 5.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "email": "...",
  "password": "...",
  "verification-code": "A1B2C3"
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">login-id</span> <span class="docs-field-type">string</span> &mdash; The <code>login-id</code> returned by <code>/login</code>.</li>
                    <li><span class="docs-field-name">email</span> <span class="docs-field-type">string</span> &mdash; Account email address.</li>
                    <li><span class="docs-field-name">password</span> <span class="docs-field-type">string</span> &mdash; Account password.</li>
                    <li><span class="docs-field-name">verification-code</span> <span class="docs-field-type">string</span> &mdash; The 6-character code received by email.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "expiry": null,
  "username": "Sara",
  "encryption-ready": true
}</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>410</code> <code>412</code> <code>413</code> <code>420</code></p>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/login/verify/get-new-code</span>
                </h4>
                <p>Resend the login verification code. Rate limited.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "email": "...",
  "password": "..."
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">login-id</span> <span class="docs-field-type">string</span> &mdash; The <code>login-id</code> returned by <code>/login</code>.</li>
                    <li><span class="docs-field-name">email</span> <span class="docs-field-type">string</span> &mdash; Account email address.</li>
                    <li><span class="docs-field-name">password</span> <span class="docs-field-type">string</span> &mdash; Account password.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "otp-required": true,
  "login-id": "...",
  "verification-expiry": "..."
}</code></pre>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/login/check-id</span>
                </h4>
                <p>Check whether a session is still valid.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "..."
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">login-id</span> <span class="docs-field-type">string</span> &mdash; Session identifier (64 hex chars).</li>
                    <li><span class="docs-field-name">token</span> <span class="docs-field-type">string</span> &mdash; Session token (64 hex chars).</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "valid": true,
  "username": "Sara",
  "expiry": null,
  "otp-enabled": true
}</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>402</code> <code>404</code> <code>405</code></p>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/login/set-expiry</span>
                </h4>
                <p>Set or remove the expiry date of a login session.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "expiry": "2025-01-01 00:00:00"
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">expiry</span> <span class="docs-field-type">string|null</span>
                        &mdash; Date string or <code>null</code> to remove the expiry.
                    </li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "expiry": "...",
  "old-expiry": null
}</code></pre>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/token/set-expiry</span>
                </h4>
                <p>Set or remove the expiry date of the session token.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "expiry": "2025-01-01 00:00:00"
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">expiry</span> <span class="docs-field-type">string|null</span> &mdash; Date string or <code>null</code> to remove the expiry.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "expiry": "...",
  "old-expiry": null
}</code></pre>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span
                            class="docs-endpoint-path">/logout</span></h4>
                <p>End the current session or all sessions. The token is mandatory (in v1, knowing only the login-id was
                    enough).</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "all-devices": false
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">all-devices</span> <span class="docs-field-type">boolean</span>
                        &mdash; If <code>true</code>, invalidates all sessions for the account.
                    </li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "logged-out": true,
  "all-devices": false
}</code></pre>
            </div>
        </section>

        <!-- OTP -->
        <section class="docs-section" id="ep-otp">
            <h2>Two-factor authentication (OTP)</h2>
            <p>OTP is enabled by default on every account. This setting affects <strong>only the login</strong>: it
                never disables the email verification required by signup, nor the confirmation code required to change
                the password or delete the account.</p>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span
                            class="docs-endpoint-path">/otp/status</span></h4>
                <p>Check whether OTP is enabled for the authenticated account.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "..."
}</code></pre>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{ "otp-enabled": true }</code></pre>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span
                            class="docs-endpoint-path">/otp/enable</span></h4>
                <p>Enable OTP for the account. Requires the password and sends a notification email.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "password": "...",
  "email": "..."
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">password</span> <span class="docs-field-type">string</span> &mdash; Account password.</li>
                    <li><span class="docs-field-name">email</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">optional</span> &mdash; Used only to send the notification email; validated against the account hash.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{ "otp-enabled": true }</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>431</code> (already enabled)</p>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span
                            class="docs-endpoint-path">/otp/disable</span></h4>
                <p>Request to disable OTP. Requires a valid token <strong>and</strong> the password, then emails a
                    confirmation code.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "password": "...",
  "email": "..."
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">password</span> <span class="docs-field-type">string</span> &mdash; Account password.</li>
                    <li><span class="docs-field-name">email</span> <span class="docs-field-type">string</span> &mdash; Used to send the confirmation code; validated against the account hash.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "verification-required": true,
  "verification-expiry": "..."
}</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>431</code> (already disabled) <code>503</code></p>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/otp/disable/verify</span>
                </h4>
                <p>Confirm OTP disable with the emailed code. Single-use, with expiry and max 5 attempts.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "password": "...",
  "verification-code": "A1B2C3",
  "email": "..."
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">password</span> <span class="docs-field-type">string</span> &mdash; Account password.</li>
                    <li><span class="docs-field-name">verification-code</span> <span class="docs-field-type">string</span> &mdash; The 6-character code received by email.</li>
                    <li><span class="docs-field-name">email</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">optional</span> &mdash; Used to send the confirmation notification.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{ "otp-enabled": false }</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>412</code> <code>413</code> <code>420</code> <code>503</code></p>
            </div>
        </section>

        <!-- Sync & Data -->
        <section class="docs-section" id="ep-sync">
            <h2>Sync &amp; Data</h2>
            <p>All sync endpoints accept the optional <code>service</code> field (default <code>notefox</code>) and echo
                it back. An unknown or malformed service is refused with <code>400</code>.</p>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span
                            class="docs-endpoint-path">/data/insert</span></h4>
                <p>Write or update data for a service. Uses revision-based conflict detection.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "service": "notefox",
  "data": "&lt;json string&gt;",
  "updated-locally": "2024-05-01 10:00:00",
  "base-revision": 41
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">service</span> <span class="docs-field-type">string</span> <span
                                class="docs-field-opt">optional, default "notefox"</span> &mdash; Target service.
                    </li>
                    <li><span class="docs-field-name">data</span> <span class="docs-field-type">string</span> &mdash;
                        JSON data to store (max 1.5 MB).
                    </li>
                    <li><span class="docs-field-name">updated-locally</span> <span class="docs-field-type">string</span>
                        &mdash; Client-side timestamp. Must be a valid date, not in the future.
                    </li>
                    <li><span class="docs-field-name">base-revision</span> <span class="docs-field-type">integer</span>
                        <span class="docs-field-opt">optional</span> &mdash; The revision the client currently owns.
                        Without it the write is always accepted (last-write-wins).
                    </li>
                </ul>

                <p class="docs-label">Success response</p>
                <pre class="docs-code"><code>{
  "service": "notefox",
  "revision": 42,
  "updated-server": "2024-05-01 10:00:02",
  "updated-locally": "2024-05-01 10:00:00"
}</code></pre>

                <p class="docs-label">Conflict response (HTTP 409)</p>
                <pre class="docs-code"><code>{
  "status": "Error",
  "code": 409,
  "description": "Revision conflict",
  "data": {
    "service": "notefox",
    "revision": 47,
    "updated-server": "...",
    "updated-locally": "...",
    "data": "&lt;current json&gt;"
  }
}</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>400</code> <code>407</code> <code>409</code> <code>430</code></p>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span
                            class="docs-endpoint-path">/data/get</span></h4>
                <p>Get the current data snapshot for a service.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "service": "notefox"
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">service</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">optional, default "notefox"</span> &mdash; Target service.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "service": "notefox",
  "data": "&lt;json string&gt;",
  "revision": 42,
  "updated-locally": "...",
  "updated-server": "..."
}</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>201</code> (no data) <code>430</code></p>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/data/get/last-update</span>
                </h4>
                <p>Get the revision and timestamps without decrypting the data. Token mandatory.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "service": "notefox"
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">service</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">optional, default "notefox"</span> &mdash; Target service.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "service": "notefox",
  "revision": 42,
  "updated-locally": "...",
  "updated-server": "..."
}</code></pre>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/data/services</span>
                </h4>
                <p>List all services that hold data for the authenticated account.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "..."
}</code></pre>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "services": [
    {
      "service": "notefox",
      "revision": 42,
      "updated-server": "...",
      "updated-locally": "..."
    }
  ],
  "supported": ["notefox"],
  "history-enabled": false
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">services</span> <span class="docs-field-type">array</span> &mdash;
                        Services that have stored data for this account.
                    </li>
                    <li><span class="docs-field-name">supported</span> <span class="docs-field-type">array</span>
                        &mdash; All services configured on this installation.
                    </li>
                    <li><span class="docs-field-name">history-enabled</span> <span
                                class="docs-field-type">boolean</span> &mdash; Whether sync history is enabled for this
                        account.
                    </li>
                </ul>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/data/get/history</span>
                </h4>
                <p>List past synced versions of a service (newest first, up to 30 entries). Requires <code>history-enabled</code>
                    permission on the account.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "service": "notefox"
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">service</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">optional, default "notefox"</span> &mdash; Target service.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "service": "notefox",
  "entries": [
    {
      "id": 512,
      "inserted-date": "2024-05-01 10:00:02",
      "updated-locally-date": "2024-05-01 10:00:00"
    }
  ]
}</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>432</code> <code>433</code></p>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/data/get/history/download</span>
                </h4>
                <p>Download a specific history entry by ID. The server decrypts before responding. Requires <code>history-enabled</code>
                    permission.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "service": "notefox",
  "id": 498
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">id</span> <span class="docs-field-type">integer</span> &mdash; One
                        of the IDs returned by <code>/data/get/history</code>.
                    </li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "service": "notefox",
  "id": 498,
  "inserted-date": "2024-04-28 09:12:44",
  "updated-locally-date": "2024-04-28 09:12:40",
  "data": "&lt;plaintext json&gt;"
}</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>201</code> (not found) <code>432</code> <code>433</code> <code>434</code>
                </p>
            </div>
        </section>

        <!-- Account management -->
        <section class="docs-section" id="ep-account">
            <h2>Account management</h2>
            <p>Changing the password and deleting the account are critical operations: both <strong>always</strong>
                require the emailed confirmation code, regardless of the OTP setting.</p>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/password/edit</span>
                </h4>
                <p>Request a password change. Emails a confirmation code. Nothing is written until the code is
                    verified.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "password": "...",
  "new-password": "...",
  "email": "..."
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">new-password</span> <span class="docs-field-type">string</span>
                        <span class="docs-field-opt">optional here</span> &mdash; At least 8 characters, different from
                        current. If sent, rules are checked immediately (no email sent for an invalid change).
                    </li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "verification-required": true,
  "verification-expiry": "..."
}</code></pre>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/password/edit/get-new-code</span>
                </h4>
                <p>Resend the password change confirmation code. Rate limited (3 per 15 minutes).</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "password": "...",
  "email": "..."
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">password</span> <span class="docs-field-type">string</span> &mdash; Current password.</li>
                    <li><span class="docs-field-name">email</span> <span class="docs-field-type">string</span> &mdash; Account email address.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "verification-required": true,
  "verification-expiry": "..."
}</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>415</code> (no change requested) <code>429</code></p>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/password/edit/verify</span>
                </h4>
                <p>Complete the password change. Only the encryption key (DEK) is re-wrapped &mdash; no note is
                    re-encrypted. All other sessions are invalidated and a new session is returned.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "login-id": "...",
  "token": "...",
  "password": "...",
  "new-password": "...",
  "verification-code": "A1B2C3",
  "email": "..."
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">password</span> <span class="docs-field-type">string</span> &mdash; Current password.</li>
                    <li><span class="docs-field-name">new-password</span> <span class="docs-field-type">string</span> &mdash; New password (at least 8 characters, different from current).</li>
                    <li><span class="docs-field-name">verification-code</span> <span class="docs-field-type">string</span> &mdash; Code received by email.</li>
                    <li><span class="docs-field-name">email</span> <span class="docs-field-type">string</span> &mdash; Account email address.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "login-id": "&lt;new&gt;",
  "token": "&lt;new&gt;",
  "expiry": null
}</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>412</code> <code>413</code> <code>420</code></p>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span
                            class="docs-endpoint-path">/delete</span></h4>
                <p>Request account deletion. Emails a confirmation code.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "email": "...",
  "password": "..."
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">email</span> <span class="docs-field-type">string</span> &mdash; Account email address.</li>
                    <li><span class="docs-field-name">password</span> <span class="docs-field-type">string</span> &mdash; Current password.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "verification-required": true,
  "verification-expiry": "..."
}</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>452</code> (code already requested)</p>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/delete/verify</span>
                </h4>
                <p>Confirm account deletion. Permanently removes the account, all sessions, all data snapshots,
                    encryption keys, and legacy data in one transaction.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "email": "...",
  "password": "...",
  "deleting-code": "A1B2C3"
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">email</span> <span class="docs-field-type">string</span> &mdash; Account email address.</li>
                    <li><span class="docs-field-name">password</span> <span class="docs-field-type">string</span> &mdash; Current password.</li>
                    <li><span class="docs-field-name">deleting-code</span> <span class="docs-field-type">string</span> &mdash; Confirmation code received by email.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{ "deleted": true }</code></pre>

                <p class="docs-label">Errors</p>
                <p class="docs-errors"><code>410</code> <code>412</code> <code>413</code> <code>420</code></p>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/delete/verify/get-new-code</span>
                </h4>
                <p>Resend the account deletion confirmation code.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "email": "...",
  "password": "..."
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">email</span> <span class="docs-field-type">string</span> &mdash; Account email address.</li>
                    <li><span class="docs-field-name">password</span> <span class="docs-field-type">string</span> &mdash; Current password.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{
  "verification-required": true,
  "verification-expiry": "..."
}</code></pre>
            </div>
        </section>

        <!-- Diagnostics -->
        <section class="docs-section" id="ep-diagnostics">
            <h2>Diagnostics</h2>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/error-logs/insert</span>
                </h4>
                <p>Submit client error logs. No authentication required. Rate limited per IP. Length limits on every
                    field.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "datetime": "2024-05-01T10:00:00Z",
  "context": "popup.js:handleSync",
  "error": "TypeError: Cannot read property ...",
  "url": "https://example.com",
  "notefox-version": "4.6.0",
  "anonymous-userid": "a1b2c3..."
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">datetime</span> <span class="docs-field-type">string</span> &mdash; Client date/time when the error occurred.</li>
                    <li><span class="docs-field-name">context</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">max 500</span> &mdash; Where the error happened (file, function, component).</li>
                    <li><span class="docs-field-name">error</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">max 65535</span> &mdash; Error message or stack trace.</li>
                    <li><span class="docs-field-name">url</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">optional, max 1000</span> &mdash; URL of the page where the error occurred.</li>
                    <li><span class="docs-field-name">notefox-version</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">optional, max 20</span> &mdash; Extension version.</li>
                    <li><span class="docs-field-name">anonymous-userid</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">optional, max 50</span> &mdash; Anonymous client identifier.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{ "data": null }</code></pre>
            </div>

            <div class="docs-card">
                <h4><span class="docs-badge docs-badge--post">POST</span> <span class="docs-endpoint-path">/telemetry/insert</span>
                </h4>
                <p>Submit client telemetry data. No authentication required. Rate limited per IP. Length limits on every
                    field.</p>

                <p class="docs-label">Request body</p>
                <pre class="docs-code"><code>{
  "notefox-account": true,
  "anonymous-userid": "a1b2c3...",
  "client-datetime": "2024-05-01T10:00:00Z",
  "language": "en",
  "action": "sync",
  "context": "popup",
  "url": "https://example.com",
  "browser": "firefox",
  "browser-version": "126.0",
  "notefox-version": "4.6.0",
  "os": "macos",
  "other": ""
}</code></pre>
                <ul class="docs-field-list">
                    <li><span class="docs-field-name">notefox-account</span> <span class="docs-field-type">boolean</span> &mdash; Whether the user is signed in to a Notefox account.</li>
                    <li><span class="docs-field-name">anonymous-userid</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">max 50</span> &mdash; Anonymous client identifier.</li>
                    <li><span class="docs-field-name">client-datetime</span> <span class="docs-field-type">string</span> &mdash; Client date/time of the event.</li>
                    <li><span class="docs-field-name">language</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">max 20</span> &mdash; Client language code.</li>
                    <li><span class="docs-field-name">action</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">max 500</span> &mdash; Action being tracked (e.g. "sync", "open-popup").</li>
                    <li><span class="docs-field-name">context</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">optional, max 500</span> &mdash; Additional context for the action.</li>
                    <li><span class="docs-field-name">url</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">optional, max 65535</span> &mdash; Page URL when the event fired.</li>
                    <li><span class="docs-field-name">browser</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">max 20</span> &mdash; Browser name.</li>
                    <li><span class="docs-field-name">browser-version</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">optional, max 20</span> &mdash; Browser version.</li>
                    <li><span class="docs-field-name">notefox-version</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">max 20</span> &mdash; Extension version.</li>
                    <li><span class="docs-field-name">os</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">optional, max 20</span> &mdash; Operating system.</li>
                    <li><span class="docs-field-name">other</span> <span class="docs-field-type">string</span> <span class="docs-field-opt">optional, max 65535</span> &mdash; Any additional data.</li>
                </ul>

                <p class="docs-label">Response</p>
                <pre class="docs-code"><code>{ "data": null }</code></pre>
            </div>
        </section>

        <!-- Rate limits -->
        <section class="docs-section" id="rate-limits">
            <h2>Rate limits</h2>
            <p>Rate limits protect the shared platform. Counters are per <strong>account</strong>, not per service.
                Beyond the limit: <code>429</code> with a temporary block.</p>

            <div style="overflow-x: auto;">
                <table class="docs-table">
                    <thead>
                    <tr>
                        <th>Bucket</th>
                        <th>Subject</th>
                        <th>Limit</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>login</td>
                        <td>email</td>
                        <td>10 / 15 min</td>
                    </tr>
                    <tr>
                        <td>login-ip</td>
                        <td>IP</td>
                        <td>30 / 15 min</td>
                    </tr>
                    <tr>
                        <td>otp-verify</td>
                        <td>login-id</td>
                        <td>5 / 15 min</td>
                    </tr>
                    <tr>
                        <td>otp-resend</td>
                        <td>email or login-id</td>
                        <td>3 / 15 min</td>
                    </tr>
                    <tr>
                        <td>signup-ip / signup-email</td>
                        <td>IP / email</td>
                        <td>10 and 5 per hour</td>
                    </tr>
                    <tr>
                        <td>signup-verify</td>
                        <td>email</td>
                        <td>10 / 15 min</td>
                    </tr>
                    <tr>
                        <td>otp-change / otp-change-verify</td>
                        <td>user</td>
                        <td>5&ndash;10 / 15 min</td>
                    </tr>
                    <tr>
                        <td>password-edit / password-edit-verify</td>
                        <td>user</td>
                        <td>5 / 15 min</td>
                    </tr>
                    <tr>
                        <td>delete-request / delete-verify</td>
                        <td>email</td>
                        <td>3 per hour / 5 per 15 min</td>
                    </tr>
                    <tr>
                        <td>data-insert</td>
                        <td>user</td>
                        <td>120 / min</td>
                    </tr>
                    <tr>
                        <td>data-services</td>
                        <td>user</td>
                        <td>60 / min</td>
                    </tr>
                    <tr>
                        <td>data-history</td>
                        <td>user</td>
                        <td>60 / min</td>
                    </tr>
                    <tr>
                        <td>data-history-download</td>
                        <td>user</td>
                        <td>30 / min</td>
                    </tr>
                    <tr>
                        <td>error-logs / telemetry</td>
                        <td>IP</td>
                        <td>30 / 10 min</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Encryption model -->
        <section class="docs-section" id="encryption">
            <h2>Encryption model</h2>
            <p>All user data is encrypted at rest. The server never stores the plaintext email (only its SHA-512 hash)
                or the plaintext password.</p>

            <pre class="docs-code"><code>password ──PBKDF2(sha256, 210k, salt)──&gt; KEK ──AES-256-CBC──&gt; wrapped DEK  (user_keys)
DEK ──AES-256-CBC──&gt; data, snapshot of every service                       (sav_data_current)
token ──AES-256-CBC──&gt; password                                            (tokens, v1 format)</code></pre>

            <ul>
                <li>The <strong>DEK</strong> (Data Encryption Key) is generated once with <code>random_bytes(32)</code>
                    and never changes. It encrypts all data across all services.
                </li>
                <li>The password only ever encrypts the DEK. Changing the password re-wraps ~100 bytes &mdash; no note
                    is re-encrypted.
                </li>
                <li><code>key-check</code> is the SHA-512 fingerprint of the DEK: it proves an unwrap produced the right
                    key without storing the key.
                </li>
                <li>The <code>tokens</code> table stores the password encrypted with the token (v1 compatibility), so a
                    v2-issued token works on v1 endpoints.
                </li>
            </ul>
        </section>

    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
