<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "Server error";
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
                <!-- Warning triangle -->
                <path d="M90 48L130 118H50L90 48z" stroke="#d32f2f" stroke-width="2.5" stroke-linejoin="round"
                      fill="rgba(211,47,47,0.08)"/>
                <!-- Exclamation mark -->
                <line x1="90" y1="72" x2="90" y2="96" stroke="#d32f2f" stroke-width="3" stroke-linecap="round"/>
                <circle cx="90" cy="106" r="2.5" fill="#d32f2f"/>
                <!-- Crack lines -->
                <path d="M50 118l-8 14M130 118l8 14" stroke="rgba(211,47,47,0.3)" stroke-width="1.5"
                      stroke-linecap="round"/>
                <path d="M64 135l-3 6M116 135l3 6" stroke="rgba(211,47,47,0.2)" stroke-width="1.5"
                      stroke-linecap="round"/>
            </svg>
            <div class="error-page__code">500</div>
            <h1 class="error-page__title">Something broke</h1>
            <p class="error-page__desc">Our server ran into an unexpected problem. It's not your fault — try again in a
                moment.</p>
            <div class="button-group">
                <a href="/alpha/" class="button">Go to homepage</a>
            </div>
        </div>
    </section>
</main>

</body>
</html>
