<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "Forbidden";
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
                <circle cx="90" cy="90" r="40" stroke="#d32f2f" stroke-width="3" fill="rgba(211,47,47,0.1)"/>
                <line x1="62" y1="62" x2="118" y2="118" stroke="#d32f2f" stroke-width="3" stroke-linecap="round"/>
            </svg>
            <div class="error-page__code">403</div>
            <h1 class="error-page__title">Access denied</h1>
            <p class="error-page__desc">You don't have permission to view this page. This area is off limits — but the
                homepage is always open.</p>
            <div class="button-group">
                <a href="/alpha/" class="button">Go to homepage</a>
            </div>
        </div>
    </section>
</main>

</body>
</html>
