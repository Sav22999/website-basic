<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "Page not found";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/alpha/include/header.php");
    ?>
</head>
<body>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/alpha/include/menu.php");
?>

<main>
    <section class="error-page">
        <div class="error-page__inner">
            <svg class="error-page__illustration" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="90" cy="90" r="80" stroke="rgba(255,255,255,0.06)" stroke-width="2"/>
                <!-- PDF document with folded corner -->
                <rect x="58" y="40" width="64" height="84" rx="4" stroke="#d32f2f" stroke-width="2.5"
                      fill="rgba(211,47,47,0.08)"/>
                <path d="M102 40v20h20" stroke="#d32f2f" stroke-width="2.5" stroke-linecap="round"
                      stroke-linejoin="round" fill="none"/>
                <path d="M102 40l20 20" stroke="#d32f2f" stroke-width="2.5" fill="none"/>
                <!-- PDF label -->
                <rect x="68" y="52" width="24" height="10" rx="2" fill="#d32f2f" opacity="0.6"/>
                <text x="80" y="60" font-family="sans-serif" font-size="7" font-weight="700" fill="white"
                      text-anchor="middle">PDF
                </text>
                <!-- Question mark -->
                <path d="M83 86a8 8 0 0 1 14-5.3 7 7 0 0 1-4 12.3v3" stroke="#d32f2f" stroke-width="2.5"
                      stroke-linecap="round" fill="none"/>
                <circle cx="90" cy="102" r="1.5" fill="#d32f2f"/>
                <!-- Scattered dots suggesting lost content -->
                <circle cx="44" cy="130" r="2" fill="rgba(211,47,47,0.25)"/>
                <circle cx="136" cy="135" r="1.5" fill="rgba(211,47,47,0.2)"/>
                <circle cx="50" cy="145" r="1.5" fill="rgba(211,47,47,0.15)"/>
            </svg>
            <div class="error-page__code">404</div>
            <h1 class="error-page__title">Page not found</h1>
            <p class="error-page__desc">We looked everywhere, but this page seems to have wandered off. Let's get you
                back on track.</p>
            <div class="button-group">
                <a href="/alpha/" class="button">Go to homepage</a>
            </div>
        </div>
    </section>
</main>

</body>
</html>
