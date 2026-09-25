<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance — Notefox</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="/images/icon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Merienda:wght@700&display=swap"
          rel="stylesheet">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1e1e1e;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #f0f0f0;
            padding: 24px 16px;
        }

        .maintenance {
            max-width: 480px;
            width: 100%;
            text-align: center;
        }

        .maintenance-illustration {
            margin-bottom: 32px;
            opacity: 0;
            transform: translateY(16px);
            animation: fadeInUp 0.5s ease forwards 0.1s;
        }

        .maintenance-title {
            font-family: 'Merienda', cursive;
            font-size: 1.75rem;
            font-weight: 700;
            color: #ffa56f;
            margin-bottom: 12px;
            opacity: 0;
            transform: translateY(16px);
            animation: fadeInUp 0.5s ease forwards 0.3s;
        }

        .maintenance-desc {
            font-size: 1rem;
            line-height: 1.6;
            color: #999;
            margin-bottom: 48px;
            opacity: 0;
            transform: translateY(16px);
            animation: fadeInUp 0.5s ease forwards 0.5s;
        }

        .maintenance-brand {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #666;
            text-decoration: none;
            font-family: 'Merienda', cursive;
            font-size: 0.8125rem;
            transition: color 0.2s ease;
            opacity: 0;
            transform: translateY(16px);
            animation: fadeInUp 0.5s ease forwards 0.7s;
        }

        .maintenance-brand:hover {
            color: #999;
        }

        .maintenance-brand img {
            width: 18px;
            height: 18px;
            opacity: 0.5;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .maintenance-illustration svg {
            animation: gearSpin 60s linear infinite;
        }

        @keyframes gearSpin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
<div class="maintenance">
    <div class="maintenance-illustration">
        <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="#ffa56f" stroke-width="1.5"
             stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
        </svg>
    </div>

    <h1 class="maintenance-title">Be right back</h1>
    <p class="maintenance-desc">We're doing a little maintenance. The site will be back shortly — try again soon.</p>

    <a href="/" class="maintenance-brand">
        <img src="/images/icon.svg" alt="">
        Notefox
    </a>
</div>
</body>
</html>
