<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "Service unavailable";
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
                <!-- Wrench -->
                <path d="M62 60l10 10-22 22a5 5 0 0 0 7 7l22-22 10 10 4-30z" stroke="#d32f2f" stroke-width="2.5" stroke-linejoin="round" fill="rgba(211,47,47,0.1)"/>
                <!-- Clock -->
                <circle cx="112" cy="100" r="24" stroke="#d32f2f" stroke-width="2.5" fill="rgba(211,47,47,0.08)"/>
                <line x1="112" y1="100" x2="112" y2="84" stroke="#d32f2f" stroke-width="2.5" stroke-linecap="round"/>
                <line x1="112" y1="100" x2="122" y2="106" stroke="#d32f2f" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="112" cy="100" r="2" fill="#d32f2f"/>
                <!-- Clock ticks -->
                <line x1="112" y1="78" x2="112" y2="81" stroke="#d32f2f" stroke-width="1.5" stroke-linecap="round" opacity="0.5"/>
                <line x1="112" y1="119" x2="112" y2="122" stroke="#d32f2f" stroke-width="1.5" stroke-linecap="round" opacity="0.5"/>
                <line x1="134" y1="100" x2="131" y2="100" stroke="#d32f2f" stroke-width="1.5" stroke-linecap="round" opacity="0.5"/>
                <line x1="90" y1="100" x2="93" y2="100" stroke="#d32f2f" stroke-width="1.5" stroke-linecap="round" opacity="0.5"/>
            </svg>
            <div class="error-page__code">503</div>
            <h1 class="error-page__title">Be right back</h1>
            <p class="error-page__desc">We're doing a little maintenance. The site will be back shortly — grab a coffee and try again soon.</p>
            <div class="button-group">
                <a href="/alpha/" class="button">Go to homepage</a>
            </div>
        </div>
    </section>
</main>

</body>
</html>
