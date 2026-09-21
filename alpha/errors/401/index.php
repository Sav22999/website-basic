<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "Unauthorized";
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
                <rect x="60" y="78" width="60" height="50" rx="6" stroke="#d32f2f" stroke-width="3" fill="rgba(211,47,47,0.1)"/>
                <path d="M72 78V64a18 18 0 0 1 36 0v14" stroke="#d32f2f" stroke-width="3" stroke-linecap="round" fill="none"/>
                <circle cx="90" cy="100" r="5" fill="#d32f2f"/>
                <line x1="90" y1="105" x2="90" y2="115" stroke="#d32f2f" stroke-width="3" stroke-linecap="round"/>
            </svg>
            <div class="error-page__code">401</div>
            <h1 class="error-page__title">Not authorized</h1>
            <p class="error-page__desc">You need to sign in to access this page. If you think this is an error, try going back to the homepage.</p>
            <div class="button-group">
                <a href="/alpha/" class="button">Go to homepage</a>
            </div>
        </div>
    </section>
</main>

</body>
</html>
