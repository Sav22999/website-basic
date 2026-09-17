<html>
<head>
    <?php
    $title = "Services Status & Health Detection – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
    <style>
        .status-hero {
            border-radius: var(--border-radius);
            padding: 24px 20px;
            margin: 25px 0px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        }

        .status-hero--loading {
            background-color: var(--tertiary-color);
            color: var(--on-tertiary-color);
            border: 1px solid var(--secondary-color-variant);
        }

        .status-hero--online {
            background-color: rgba(40, 167, 69, 0.15);
            border: 1px solid rgba(40, 167, 69, 0.45);
            color: var(--on-primary-color);
        }

        .status-hero--warning {
            background-color: rgba(255, 193, 7, 0.15);
            border: 1px solid rgba(255, 193, 7, 0.45);
            color: var(--on-primary-color);
        }

        .status-hero--offline {
            background-color: rgba(220, 53, 69, 0.18);
            border: 1px solid rgba(220, 53, 69, 0.45);
            color: var(--on-primary-color);
        }

        .status-hero-icon {
            font-size: 42px;
            line-height: 1;
            margin-bottom: 10px;
        }

        .status-hero-title {
            font-size: var(--font-size-big);
            font-weight: 700;
            margin: 0px 0px 8px 0px;
        }

        .status-hero-desc {
            font-size: var(--font-size-normal);
            margin: 0px 0px 14px 0px;
            opacity: 0.95;
        }

        .status-hero-meta {
            font-size: var(--font-size-very-small);
            opacity: 0.8;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }

        .status-hero-meta span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 18px;
            margin: 25px 0px;
        }

        .status-card {
            background-color: var(--tertiary-color);
            color: var(--on-tertiary-color);
            border-radius: var(--border-radius);
            padding: 18px 20px;
            box-sizing: border-box;
            border: 1px solid var(--secondary-color-variant);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .status-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .status-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .status-card-title {
            font-size: var(--font-size-normal);
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: var(--font-size-very-very-small);
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge--ok {
            background-color: rgba(40, 167, 69, 0.25);
            color: #b3f0c1;
            border: 1px solid rgba(40, 167, 69, 0.4);
        }

        .status-badge--warn {
            background-color: rgba(255, 193, 7, 0.25);
            color: #ffe082;
            border: 1px solid rgba(255, 193, 7, 0.4);
        }

        .status-badge--error {
            background-color: rgba(220, 53, 69, 0.25);
            color: #ffb3bb;
            border: 1px solid rgba(220, 53, 69, 0.4);
        }

        .status-badge--loading {
            background-color: rgba(120, 120, 120, 0.25);
            color: #d0d0d0;
            border: 1px solid rgba(120, 120, 120, 0.4);
        }

        .status-card-body {
            font-size: var(--font-size-small);
            line-height: 1.45;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .status-card-footer {
            font-size: var(--font-size-very-very-small);
            opacity: 0.75;
            border-top: 1px solid var(--secondary-color-variant);
            padding-top: 8px;
            margin-top: 6px;
        }

        .pulse-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            display: inline-block;
        }

        .pulse-dot--green {
            background-color: #28a745;
            box-shadow: 0 0 0 rgba(40, 167, 69, 0.5);
            animation: pulse-green 2s infinite;
        }

        .pulse-dot--yellow {
            background-color: #ffc107;
            box-shadow: 0 0 0 rgba(255, 193, 7, 0.5);
            animation: pulse-yellow 2s infinite;
        }

        .pulse-dot--red {
            background-color: #dc3545;
            box-shadow: 0 0 0 rgba(220, 53, 69, 0.5);
            animation: pulse-red 2s infinite;
        }

        .pulse-dot--gray {
            background-color: #888888;
        }

        @keyframes pulse-green {
            0% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
            }
            70% {
                box-shadow: 0 0 0 8px rgba(40, 167, 69, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
            }
        }

        @keyframes pulse-yellow {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
            }
            70% {
                box-shadow: 0 0 0 8px rgba(255, 193, 7, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
            }
        }

        @keyframes pulse-red {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }
            70% {
                box-shadow: 0 0 0 8px rgba(220, 53, 69, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0px;
            font-size: var(--font-size-small);
        }

        .details-table th, .details-table td {
            padding: 10px 14px;
            text-align: left;
            border-bottom: 1px solid var(--secondary-color-variant);
        }

        .details-table th {
            font-weight: 600;
            background-color: var(--tertiary-color);
        }

        .details-table tr:last-child td {
            border-bottom: none;
        }

        .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            vertical-align: middle;
            margin-right: 6px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .raw-debug-box {
            background-color: rgba(0, 0, 0, 0.25);
            border-radius: var(--border-radius);
            padding: 12px 16px;
            font-family: monospace;
            font-size: var(--font-size-very-small);
            white-space: pre-wrap;
            word-break: break-all;
            max-height: 220px;
            overflow-y: auto;
            border: 1px solid var(--secondary-color-variant);
            margin-top: 10px;
        }

        .check-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
<?php
$selected_menu = "help";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center">
        <div class="center-content justify">
            <h1 class="title-section center">Services Status &amp; Health Detection</h1>
            <p class="center" style="margin-top: -10px; margin-bottom: 20px; opacity: 0.9;">
                Real-time automatic diagnostic detection for Notefox sync servers, database, security, and messaging
                services.
            </p>

            <!-- Main Overall Hero Banner -->
            <div id="status-hero" class="status-hero status-hero--loading">
                <div id="status-hero-icon" class="status-hero-icon">
                    <span class="spinner"></span>
                </div>
                <div id="status-hero-title" class="status-hero-title">Checking Services...</div>
                <div id="status-hero-desc" class="status-hero-desc">Connecting to Notefox API check endpoint to test
                    system availability...
                </div>
                <div class="status-hero-meta">
                    <span id="hero-meta-time">Checking...</span>
                    <span id="hero-meta-latency">Latency: -- ms</span>
                    <span id="hero-meta-endpoint">Endpoint: /api/v2/status/</span>
                </div>
            </div>

            <div class="check-actions">
                <button type="button" id="btn-refresh-check" class="button button-with-icon button-sync">
                    Run Check Again
                </button>
            </div>

            <!-- Detailed Grid -->
            <h2 class="subtitle-section">Component Health Breakdown</h2>
            <div class="status-grid">
                <!-- API Gateway Card -->
                <div class="status-card" id="card-api">
                    <div>
                        <div class="status-card-header">
                            <h3 class="status-card-title">API Gateway</h3>
                            <span class="status-badge status-badge--loading" id="badge-api">Checking</span>
                        </div>
                        <div class="status-card-body" id="desc-api">
                            Testing connection to the central API server...
                        </div>
                    </div>
                    <div class="status-card-footer" id="footer-api">
                        Version: -- | Latency: --
                    </div>
                </div>

                <!-- Database Card -->
                <div class="status-card" id="card-db">
                    <div>
                        <div class="status-card-header">
                            <h3 class="status-card-title">Database (DBMS)</h3>
                            <span class="status-badge status-badge--loading" id="badge-db">Checking</span>
                        </div>
                        <div class="status-card-body" id="desc-db">
                            Verifying active database connections and transactions...
                        </div>
                    </div>
                    <div class="status-card-footer" id="footer-db">
                        Storage: --
                    </div>
                </div>

                <!-- Sync & Key Schema Card -->
                <div class="status-card" id="card-sync">
                    <div>
                        <div class="status-card-header">
                            <h3 class="status-card-title">Sync &amp; Encryption Engine</h3>
                            <span class="status-badge status-badge--loading" id="badge-sync">Checking</span>
                        </div>
                        <div class="status-card-body" id="desc-sync">
                            Checking multi-service snapshot schema, revision management, and DEK/KEK encryption keys...
                        </div>
                    </div>
                    <div class="status-card-footer" id="footer-sync">
                        Multi-service &amp; Revisions: --
                    </div>
                </div>

                <!-- Mailer Card -->
                <div class="status-card" id="card-mailer">
                    <div>
                        <div class="status-card-header">
                            <h3 class="status-card-title">Email Delivery (SMTP)</h3>
                            <span class="status-badge status-badge--loading" id="badge-mailer">Checking</span>
                        </div>
                        <div class="status-card-body" id="desc-mailer">
                            Checking mailer transport readiness for signup verification, 2FA codes, and password
                            resets...
                        </div>
                    </div>
                    <div class="status-card-footer" id="footer-mailer">
                        Service: Authenticated SMTP
                    </div>
                </div>

                <!-- Security & Rate Limiting Card -->
                <div class="status-card" id="card-security">
                    <div>
                        <div class="status-card-header">
                            <h3 class="status-card-title">Security &amp; Rate Limiting</h3>
                            <span class="status-badge status-badge--loading" id="badge-security">Checking</span>
                        </div>
                        <div class="status-card-body" id="desc-security">
                            Verifying brute-force prevention, token security, and request throttling rules...
                        </div>
                    </div>
                    <div class="status-card-footer" id="footer-security">
                        Rate limits: --
                    </div>
                </div>

                <!-- Clock Skew Card -->
                <div class="status-card" id="card-clock">
                    <div>
                        <div class="status-card-header">
                            <h3 class="status-card-title">Clock Synchronization</h3>
                            <span class="status-badge status-badge--loading" id="badge-clock">Checking</span>
                        </div>
                        <div class="status-card-body" id="desc-clock">
                            Comparing local client clock with server time to detect time skew...
                        </div>
                    </div>
                    <div class="status-card-footer" id="footer-clock">
                        Skew: --
                    </div>
                </div>
            </div>

            <!-- Technical Schema Diagnostics -->
            <h2 class="subtitle-section">Technical Subsystem Diagnostics</h2>
            <div style="background-color: var(--tertiary-color); border-radius: var(--border-radius); border: 1px solid var(--secondary-color-variant); overflow: hidden;">
                <table class="details-table">
                    <thead>
                    <tr>
                        <th>Subsystem Component</th>
                        <th>Description</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody id="subsystem-table-body">
                    <tr>
                        <td><strong>Data Encryption Keys (`keys`)</strong></td>
                        <td>Dedicated Data Encryption Key storage per account</td>
                        <td id="diag-keys"><span class="pulse-dot pulse-dot--gray"></span> Checking</td>
                    </tr>
                    <tr>
                        <td><strong>Snapshots (`snapshots`)</strong></td>
                        <td>Encrypted notes data storage table</td>
                        <td id="diag-snapshots"><span class="pulse-dot pulse-dot--gray"></span> Checking</td>
                    </tr>
                    <tr>
                        <td><strong>Multi-Service Partitioning</strong></td>
                        <td>Multi-service workspace isolation and independent sync</td>
                        <td id="diag-multi-service"><span class="pulse-dot pulse-dot--gray"></span> Checking</td>
                    </tr>
                    <tr>
                        <td><strong>Rate Limiting Engine</strong></td>
                        <td>Flood &amp; brute-force protection table</td>
                        <td id="diag-rate-limits"><span class="pulse-dot pulse-dot--gray"></span> Checking</td>
                    </tr>
                    <tr>
                        <td><strong>Two-Factor Authentication (2FA)</strong></td>
                        <td>Configurable login OTP &amp; verification columns</td>
                        <td id="diag-otp"><span class="pulse-dot pulse-dot--gray"></span> Checking</td>
                    </tr>
                    <tr>
                        <td><strong>Password Reset Workflow</strong></td>
                        <td>Secure password change tokens &amp; key re-wrapping</td>
                        <td id="diag-pwd"><span class="pulse-dot pulse-dot--gray"></span> Checking</td>
                    </tr>
                    <tr>
                        <td><strong>Sync History Permissions</strong></td>
                        <td>Account-level sync history inspection permissions</td>
                        <td id="diag-history"><span class="pulse-dot pulse-dot--gray"></span> Checking</td>
                    </tr>
                    <tr>
                        <td><strong>Legacy v1 Compatibility Mirror</strong></td>
                        <td>Real-time backward compatibility bridge for older extensions</td>
                        <td id="diag-legacy"><span class="pulse-dot pulse-dot--gray"></span> Checking</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Troubleshooting and Information Section -->
            <h2 class="subtitle-section">Troubleshooting &amp; FAQ</h2>
            <p>
                <strong>What should I do if a service is reported as Offline?</strong><br>
                1. Check your local internet connection and ensure your browser or ad-blocker is not blocking API
                requests to <code>notefox.eu</code>.<br>
                2. If the API or Database is temporarily unavailable, maintenance might be currently underway. Please
                wait a few moments and click <strong>"Run Check Again"</strong>.<br>
                3. If the problem persists, feel free to report it directly via our support channels.
            </p>
            <p>
                <strong>Why does Clock Synchronization matter?</strong><br>
                While Notefox Account v2 uses smart revision counters and conflict detection (HTTP 409) rather than
                relying exclusively on device timestamps, keeping your device's clock synchronized with network time
                ensures that your local edit history and logs reflect accurate times.
            </p>

            <br>
            <div class="center" style="margin-top: 15px;">
                <input type="button" class="button" value="Back to Help" onclick="location.href='../'">
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var heroEl = document.getElementById("status-hero");
        var heroIconEl = document.getElementById("status-hero-icon");
        var heroTitleEl = document.getElementById("status-hero-title");
        var heroDescEl = document.getElementById("status-hero-desc");
        var heroTimeEl = document.getElementById("hero-meta-time");
        var heroLatencyEl = document.getElementById("hero-meta-latency");
        var refreshBtn = document.getElementById("btn-refresh-check");

        function renderSubsystemStatus(elementId, isOk) {
            var el = document.getElementById(elementId);
            if (!el) return;
            if (isOk === true) {
                el.innerHTML = '<span class="pulse-dot pulse-dot--green"></span> <span style="color: #b3f0c1; font-weight: 600;">Operational</span>';
            } else if (isOk === false) {
                el.innerHTML = '<span class="pulse-dot pulse-dot--red"></span> <span style="color: #ffb3bb; font-weight: 600;">Degraded / Missing</span>';
            } else {
                el.innerHTML = '<span class="pulse-dot pulse-dot--gray"></span> <span>Unknown</span>';
            }
        }

        function runDetectionCheck() {
            // Set UI to loading state
            heroEl.className = "status-hero status-hero--loading";
            heroIconEl.innerHTML = '<span class="spinner"></span>';
            heroTitleEl.textContent = "Checking Services...";
            heroDescEl.textContent = "Connecting to Notefox API check endpoint to test system availability...";
            heroTimeEl.textContent = "Checking...";
            heroLatencyEl.textContent = "Latency: measuring...";

            document.getElementById("badge-api").className = "status-badge status-badge--loading";
            document.getElementById("badge-api").textContent = "Checking";
            document.getElementById("badge-db").className = "status-badge status-badge--loading";
            document.getElementById("badge-db").textContent = "Checking";
            document.getElementById("badge-sync").className = "status-badge status-badge--loading";
            document.getElementById("badge-sync").textContent = "Checking";
            document.getElementById("badge-mailer").className = "status-badge status-badge--loading";
            document.getElementById("badge-mailer").textContent = "Checking";
            document.getElementById("badge-security").className = "status-badge status-badge--loading";
            document.getElementById("badge-security").textContent = "Checking";
            document.getElementById("badge-clock").className = "status-badge status-badge--loading";
            document.getElementById("badge-clock").textContent = "Checking";

            refreshBtn.disabled = true;

            var startTime = performance.now();

            fetch("/api/v2/status/", {
                method: "GET",
                headers: {
                    "Accept": "application/json"
                },
                cache: "no-store"
            })
                .then(function (response) {
                    var duration = Math.round(performance.now() - startTime);
                    return response.json().then(function (json) {
                        return {
                            httpOk: response.ok,
                            httpStatus: response.status,
                            duration: duration,
                            body: json
                        };
                    }).catch(function () {
                        return {
                            httpOk: response.ok,
                            httpStatus: response.status,
                            duration: duration,
                            body: null
                        };
                    });
                })
                .then(function (result) {
                    refreshBtn.disabled = false;
                    var now = new Date();
                    heroTimeEl.textContent = "Last checked: " + now.toLocaleTimeString();
                    heroLatencyEl.textContent = "Latency: " + result.duration + " ms";

                    var body = result.body || {};
                    var data = body.data || {};

                    var isReachable = result.httpOk && body.status === "Successful" && data.reachable === true;
                    var isDatabaseOk = isReachable && data.database === true;
                    var isSchemaOk = isDatabaseOk && data.schema === true;
                    var isMailerOk = isReachable && data.mailer === true;
                    var schemaDetails = data["schema-details"] || {};
                    var isSecurityOk = isDatabaseOk && schemaDetails["rate-limits"] === true;

                    // Clock Skew Calculation
                    var clockSkewSec = 0;
                    var serverTimeStr = data["server-time"];
                    if (serverTimeStr) {
                        var serverDate = new Date(serverTimeStr.replace(" ", "T") + (data["server-timezone"] === "UTC" ? "Z" : ""));
                        if (!isNaN(serverDate.getTime())) {
                            clockSkewSec = Math.round(Math.abs((now.getTime() - serverDate.getTime()) / 1000));
                        }
                    }

                    // 1. API Server Card
                    if (isReachable) {
                        document.getElementById("badge-api").className = "status-badge status-badge--ok";
                        document.getElementById("badge-api").innerHTML = '<span class="pulse-dot pulse-dot--green"></span> Online';
                        document.getElementById("desc-api").innerHTML = 'Central API gateway is reachable and responding normally.';
                        document.getElementById("footer-api").innerHTML = 'Version: ' + (data["api-version"] || "2.0") + ' | Response: ' + result.duration + ' ms';
                    } else {
                        document.getElementById("badge-api").className = "status-badge status-badge--error";
                        document.getElementById("badge-api").innerHTML = '<span class="pulse-dot pulse-dot--red"></span> Offline';
                        document.getElementById("desc-api").innerHTML = 'Unable to reach the API server or server returned status ' + result.httpStatus + '.';
                        document.getElementById("footer-api").innerHTML = 'HTTP ' + result.httpStatus;
                    }

                    // 2. Database Card
                    if (isDatabaseOk) {
                        document.getElementById("badge-db").className = "status-badge status-badge--ok";
                        document.getElementById("badge-db").innerHTML = '<span class="pulse-dot pulse-dot--green"></span> Connected';
                        document.getElementById("desc-db").innerHTML = 'DBMS connection is active and responding to database transactions.';
                        document.getElementById("footer-db").innerHTML = 'Connectivity: Optimal';
                    } else {
                        document.getElementById("badge-db").className = "status-badge status-badge--error";
                        document.getElementById("badge-db").innerHTML = '<span class="pulse-dot pulse-dot--red"></span> Offline';
                        document.getElementById("desc-db").innerHTML = 'Database server is currently unreachable or rejecting connections.';
                        document.getElementById("footer-db").innerHTML = 'Connectivity: Disconnected';
                    }

                    // 3. Sync & Schema Card
                    if (isSchemaOk) {
                        document.getElementById("badge-sync").className = "status-badge status-badge--ok";
                        document.getElementById("badge-sync").innerHTML = '<span class="pulse-dot pulse-dot--green"></span> Operational';
                        document.getElementById("desc-sync").innerHTML = 'Multi-service snapshots, revision management, and user encryption keys are fully active.';
                        document.getElementById("footer-sync").innerHTML = 'Supported services: ' + ((data.services && data.services.join(", ")) || "notefox");
                    } else if (isDatabaseOk) {
                        document.getElementById("badge-sync").className = "status-badge status-badge--warn";
                        document.getElementById("badge-sync").innerHTML = '<span class="pulse-dot pulse-dot--yellow"></span> Degraded';
                        document.getElementById("desc-sync").innerHTML = 'Some database tables or additive schema partitions are missing.';
                        document.getElementById("footer-sync").innerHTML = 'Schema upgrade pending';
                    } else {
                        document.getElementById("badge-sync").className = "status-badge status-badge--error";
                        document.getElementById("badge-sync").innerHTML = '<span class="pulse-dot pulse-dot--red"></span> Inactive';
                        document.getElementById("desc-sync").innerHTML = 'Sync engine is unavailable due to database connectivity issue.';
                        document.getElementById("footer-sync").innerHTML = 'Status: Offline';
                    }

                    // 4. Mailer Card
                    if (isMailerOk) {
                        document.getElementById("badge-mailer").className = "status-badge status-badge--ok";
                        document.getElementById("badge-mailer").innerHTML = '<span class="pulse-dot pulse-dot--green"></span> Operational';
                        document.getElementById("desc-mailer").innerHTML = 'Authenticated mail transport is active and sending verification emails.';
                        document.getElementById("footer-mailer").innerHTML = 'SMTP: Connected';
                    } else {
                        document.getElementById("badge-mailer").className = "status-badge status-badge--warn";
                        document.getElementById("badge-mailer").innerHTML = '<span class="pulse-dot pulse-dot--yellow"></span> Unavailable';
                        document.getElementById("desc-mailer").innerHTML = 'Mail service is not configured or SMTP connection failed.';
                        document.getElementById("footer-mailer").innerHTML = 'SMTP: Offline';
                    }

                    // 5. Security Card
                    if (isSecurityOk) {
                        document.getElementById("badge-security").className = "status-badge status-badge--ok";
                        document.getElementById("badge-security").innerHTML = '<span class="pulse-dot pulse-dot--green"></span> Protected';
                        document.getElementById("desc-security").innerHTML = 'Granular rate limiting and anti-abuse mechanisms are running.';
                        document.getElementById("footer-security").innerHTML = 'Protection: Enabled';
                    } else {
                        document.getElementById("badge-security").className = "status-badge status-badge--warn";
                        document.getElementById("badge-security").innerHTML = '<span class="pulse-dot pulse-dot--yellow"></span> Partial';
                        document.getElementById("desc-security").innerHTML = 'Rate limit tables are not active.';
                        document.getElementById("footer-security").innerHTML = 'Protection: Disabled';
                    }

                    // 6. Clock Card
                    if (clockSkewSec <= 15) {
                        document.getElementById("badge-clock").className = "status-badge status-badge--ok";
                        document.getElementById("badge-clock").innerHTML = '<span class="pulse-dot pulse-dot--green"></span> In Sync';
                        document.getElementById("desc-clock").innerHTML = 'Local client time and server time are perfectly in sync (skew: ' + clockSkewSec + 's).';
                        document.getElementById("footer-clock").innerHTML = 'Server Time: ' + (serverTimeStr || "--");
                    } else {
                        document.getElementById("badge-clock").className = "status-badge status-badge--warn";
                        document.getElementById("badge-clock").innerHTML = '<span class="pulse-dot pulse-dot--yellow"></span> Skew (' + clockSkewSec + 's)';
                        document.getElementById("desc-clock").innerHTML = 'A time difference of ' + clockSkewSec + ' seconds was detected between your device and the server.';
                        document.getElementById("footer-clock").innerHTML = 'Server Time: ' + (serverTimeStr || "--");
                    }

                    // Subsystem Diagnostics
                    renderSubsystemStatus("diag-keys", schemaDetails["keys"]);
                    renderSubsystemStatus("diag-snapshots", schemaDetails["snapshots"]);
                    renderSubsystemStatus("diag-multi-service", schemaDetails["snapshots-multi-service"]);
                    renderSubsystemStatus("diag-rate-limits", schemaDetails["rate-limits"]);
                    renderSubsystemStatus("diag-otp", schemaDetails["otp"] && schemaDetails["otp-change-code"]);
                    renderSubsystemStatus("diag-pwd", schemaDetails["password-change-code"]);
                    renderSubsystemStatus("diag-history", schemaDetails["history-permission"]);
                    renderSubsystemStatus("diag-legacy", schemaDetails["legacy-mirror"]);

                    // Overall Hero Banner Decision
                    if (isReachable && isDatabaseOk && isSchemaOk && isMailerOk) {
                        heroEl.className = "status-hero status-hero--online";
                        heroIconEl.innerHTML = '<span class="pulse-dot pulse-dot--green" style="width: 22px; height: 22px; vertical-align: middle;"></span>';
                        heroTitleEl.textContent = "All Systems Operational";
                        heroDescEl.textContent = "All Notefox synchronization servers, database storage, email services, and security subsystems are online and healthy.";
                    } else if (isReachable && isDatabaseOk) {
                        heroEl.className = "status-hero status-hero--warning";
                        heroIconEl.innerHTML = '<span class="pulse-dot pulse-dot--yellow" style="width: 22px; height: 22px; vertical-align: middle;"></span>';
                        heroTitleEl.textContent = "Partially Degraded Performance";
                        heroDescEl.textContent = "The server is reachable, but one or more background subsystems (such as email delivery or secondary schema tables) require attention.";
                    } else {
                        heroEl.className = "status-hero status-hero--offline";
                        heroIconEl.innerHTML = '<span class="pulse-dot pulse-dot--red" style="width: 22px; height: 22px; vertical-align: middle;"></span>';
                        heroTitleEl.textContent = "Service Outage Detected";
                        heroDescEl.textContent = "The sync services or database are currently offline or unreachable. Please verify your connection or try again shortly.";
                    }
                })
                .catch(function (error) {
                    refreshBtn.disabled = false;
                    var now = new Date();
                    heroTimeEl.textContent = "Last checked: " + now.toLocaleTimeString();
                    heroLatencyEl.textContent = "Latency: Unreachable";

                    heroEl.className = "status-hero status-hero--offline";
                    heroIconEl.innerHTML = '<span class="pulse-dot pulse-dot--red" style="width: 22px; height: 22px; vertical-align: middle;"></span>';
                    heroTitleEl.textContent = "Unable to Connect to Services";
                    heroDescEl.textContent = "Failed to connect to the Notefox check API. Please check your internet connection or firewall settings.";

                    document.getElementById("badge-api").className = "status-badge status-badge--error";
                    document.getElementById("badge-api").innerHTML = '<span class="pulse-dot pulse-dot--red"></span> Offline';
                    document.getElementById("desc-api").innerHTML = 'Network error: ' + (error && error.message ? error.message : "Connection failed");

                    document.getElementById("badge-db").className = "status-badge status-badge--error";
                    document.getElementById("badge-db").innerHTML = '<span class="pulse-dot pulse-dot--red"></span> Unreachable';

                    document.getElementById("badge-sync").className = "status-badge status-badge--error";
                    document.getElementById("badge-sync").innerHTML = '<span class="pulse-dot pulse-dot--red"></span> Unreachable';

                    document.getElementById("badge-mailer").className = "status-badge status-badge--error";
                    document.getElementById("badge-mailer").innerHTML = '<span class="pulse-dot pulse-dot--red"></span> Unreachable';

                    document.getElementById("badge-security").className = "status-badge status-badge--error";
                    document.getElementById("badge-security").innerHTML = '<span class="pulse-dot pulse-dot--red"></span> Unreachable';

                    document.getElementById("badge-clock").className = "status-badge status-badge--loading";
                    document.getElementById("badge-clock").textContent = "N/A";

                    renderSubsystemStatus("diag-keys", null);
                    renderSubsystemStatus("diag-snapshots", null);
                    renderSubsystemStatus("diag-multi-service", null);
                    renderSubsystemStatus("diag-rate-limits", null);
                    renderSubsystemStatus("diag-otp", null);
                    renderSubsystemStatus("diag-pwd", null);
                    renderSubsystemStatus("diag-history", null);
                    renderSubsystemStatus("diag-legacy", null);
                });
        }

        refreshBtn.addEventListener("click", function () {
            runDetectionCheck();
        });

        // Run automatic detection immediately on page load
        runDetectionCheck();
    });
</script>

</body>
</html>
