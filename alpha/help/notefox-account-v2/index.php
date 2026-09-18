<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/root-path.php");
global $root_path, $path;
$title = "Notefox Account v2 (Sav Account) – Notefox";
$selected_menu = "help";
include_once($root_path . "/alpha/include/header.php");
?>
<body>
<?php include_once($root_path . "/alpha/include/menu.php"); ?>

<main id="main" class="page">
    <div class="container">
        <?php i18n_english_only_notice(); ?>
        <a href="/alpha/help/faq/" class="back-link">Back to FAQ</a>
        <h1>Notefox Account v2 (Sav Account)</h1>
        <div class="article-meta">
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16"
                                                                                                             y1="2"
                                                                                                             x2="16"
                                                                                                             y2="6"/><line
                            x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Updated: 2026-09-20</span>
            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16"
                                                                                                                y1="8"
                                                                                                                x2="2"
                                                                                                                y2="22"/><line
                            x1="17.5" y1="15" x2="9" y2="15"/></svg> Notefox 5.0+</span>
        </div>
        <p>
            <strong>Notefox Account v2</strong> (also known as <strong>Sav Account</strong>) is the modern,
            next-generation backend and synchronization system powering Notefox. It has been redesigned from the ground
            up to offer superior security, higher reliability, multi-service flexibility, and smart conflict management.
        </p>

        <h2>What's New in Version 2</h2>
        <p>
            Version 2 introduces major architectural improvements and powerful new features:
        </p>
        <ul>
            <li>
                <strong>Unified Multi-Service Platform (Sav Account):</strong> Rather than being tied exclusively to
                Notefox, the account system is now product-neutral. A single Sav Account can synchronize data across
                multiple different extensions and applications independently. Each service operates in its own isolated
                workspace with separate revision tracking, preventing data collisions.
            </li>
            <li>
                <strong>Smart Conflict Resolution &amp; Revision Tracking:</strong> Sync operations now use server-side
                revisions and atomic snapshot management. If notes are edited simultaneously across different devices or
                during network delays, the system detects revision mismatches (conflict <code>409</code>) instead of
                silently overwriting your changes, allowing clients to merge data safely.
            </li>
            <li>
                <strong>Two-Tier Encryption Model (DEK &amp; KEK):</strong> Your notes are encrypted using a dedicated,
                cryptographically generated Data Encryption Key (AES-256-CBC). This data key is wrapped with a Key
                Encryption Key derived from your master password (via PBKDF2 with 210,000 iterations). When you change
                your password, only the data key wrapper is re-encrypted—preventing past data loss and eliminating
                legacy limitations.
            </li>
            <li>
                <strong>Enhanced 2FA &amp; Verification Security:</strong> Two-Factor Authentication (2FA) for logins is
                now flexible and configurable (can be enabled or disabled per account), while account signup email
                verification remains strictly enforced. OTP codes use cryptographically secure random generation, expire
                automatically after a set duration, and enforce a 5-attempt limit against guessing.
            </li>
            <li>
                <strong>Protection Against Brute Force &amp; Mail Bombing:</strong> Strict rate limiting protects all
                sensitive authentication endpoints (signup, login, password changes, OTP verification) against automated
                attacks and spam. Account enumeration on registration is also mitigated.
            </li>
            <li>
                <strong>Modern Cryptographic Standards:</strong> User passwords benefit from state-of-the-art hashing
                (<code>argon2id</code> / <code>bcrypt</code> / <code>password_hash</code>), session IDs are generated
                using 32 cryptographically secure random bytes, and tokens are strictly validated on all operations.
            </li>
            <li>
                <strong>High Reliability &amp; Authenticated Email Delivery:</strong> System emails (verification codes,
                alerts) are delivered reliably via Symfony Mailer with authenticated SMTP connections, replacing legacy
                mail transports. Atomic database transactions with row-level locking ensure zero data corruption.
            </li>
            <li>
                <strong>Web Dashboard &amp; Account Management:</strong> You can now sign in to your account directly
                via the web portal (<a href="/my/">notefox.eu/my</a>) to manage your profile, security preferences (such
                as two-step verification), password, and sync history. Looking forward, this web interface lays the
                groundwork for even more capabilities—such as the possibility to access and view your notes on the fly
                directly from any browser.
            </li>
            <li>
                <strong>Live Systems Status &amp; Health Detection Page:</strong> A new dedicated page (<a
                        href="/alpha/help/status/">Services Status &amp; Health Detection</a>) is available in the Help
                section to test and monitor the operational state of all backend services live in real time (API
                gateway, database DBMS, sync engine, DEK/KEK encryption schemas, SMTP email delivery, security rate
                limits, and server clock synchronization).
            </li>
        </ul>

        <h2>What Happens to Existing (v1) Users?</h2>
        <p>
            If you already have a Notefox Account created with version 1, <strong>you do not need to do
                anything</strong>. The transition is designed to be completely seamless and risk-free:
        </p>
        <ul>
            <li>
                <strong>No Interruption &amp; Full Backward Compatibility:</strong> The legacy v1 API remains online and
                operational. If your browser extension is on an older release, it will continue to sync your notes
                exactly as before without any changes.
            </li>
            <li>
                <strong>Zero Data Loss:</strong> All your existing notes, accounts, and history are preserved intact. No
                notes are deleted, altered, or wiped during the upgrade.
            </li>
            <li>
                <strong>Transparent Automatic Migration:</strong> When you log in or sync using a v2-compatible client,
                your account is seamlessly and automatically upgraded in the background. A dedicated Data Encryption Key
                is created, your latest notes are assigned to the Notefox service snapshot, and updated security
                measures are activated without requiring any manual export/import steps.
            </li>
            <li>
                <strong>Same Credentials:</strong> Your existing email and password continue to work normally.
            </li>
            <li>
                <strong>Safe Password Updates:</strong> Once upgraded, you can change your password at any time without
                worrying about older notes becoming unreadable.
            </li>
        </ul>

        <h2>Summary of Key Differences</h2>
        <table class="details-table">
            <thead>
            <tr>
                <th>Feature</th>
                <th>Legacy Account (v1)</th>
                <th>Sav Account (v2)</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><strong>Supported Services</strong></td>
                <td>Notefox only</td>
                <td>Multi-service (Notefox + future extensions)</td>
            </tr>
            <tr>
                <td><strong>Sync Mechanism</strong></td>
                <td>Last-write-wins (sensitive to clock drift)</td>
                <td>Snapshot + Revision counter with conflict detection (409)</td>
            </tr>
            <tr>
                <td><strong>Password Change</strong></td>
                <td>Re-encrypts limited rows</td>
                <td>Re-wraps Data Encryption Key (all notes remain accessible)</td>
            </tr>
            <tr>
                <td><strong>2FA / Login OTP</strong></td>
                <td>Fixed / non-configurable</td>
                <td>Configurable per user, with expiry and attempt limits</td>
            </tr>
            <tr>
                <td><strong>Brute-Force Protection</strong></td>
                <td>None</td>
                <td>Granular rate limiting across all endpoints</td>
            </tr>
            <tr>
                <td><strong>Web Management</strong></td>
                <td>None (managed only via the extension)</td>
                <td>Web dashboard (<a href="/my/">/my/</a>) + future on-the-fly notes access</td>
            </tr>
            <tr>
                <td><strong>Password Security</strong></td>
                <td>SHA-512</td>
                <td>Modern secure hash (Argon2id / Bcrypt)</td>
            </tr>
            <tr>
                <td><strong>Live Systems Status Check</strong></td>
                <td>None</td>
                <td>Dedicated live health detection page (<a href="/alpha/help/status/">/help/status/</a>)</td>
            </tr>
            </tbody>
        </table>

        <h2>Frequently Asked Questions</h2>
        <p>
            <strong>Do I have to register a new account?</strong>
        </p>
        <p>
            No. Your existing Notefox Account works directly with the new v2 system.
        </p>
        <p>
            <strong>Will I lose my notes when my extension updates?</strong>
        </p>
        <p>
            No. Your notes are securely preserved on the server and will automatically synchronize with the new version.
            However, keeping a regular local backup via the <a href="/alpha/help/import-export-data/">Import &amp;
                Export</a> feature is always recommended as a good practice.
        </p>
        <p>
            <strong>Can I manage my account or view my notes from the web?</strong>
        </p>
        <p>
            Yes, you can log in to your account at <a href="/my/">notefox.eu/my</a> from any web browser to manage your
            profile, security settings (like 2FA), change your password, or download sync history. In the future, we
            also plan to allow accessing and viewing your notes on the fly directly from the web portal.
        </p>
        <p>
            <strong>What if I use Notefox on multiple devices with different versions?</strong>
        </p>
        <p>
            The v1 and v2 APIs are designed to coexist safely. A v1 client and a v2 client can both communicate with the
            sync infrastructure during the transitional period.
        </p>
    </div>
</main>

<?php include_once($root_path . "/alpha/include/footer.php"); ?>
<script src="/alpha/js/script.js"></script>
</body>
</html>
