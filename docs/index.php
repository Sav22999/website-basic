<html>
<head>
    <?php
    $title = "Docs – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "docs";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center">
        <div class="center-content">
            <h1 class="title-section center">Documentation</h1>
            <h2 class="subtitle-section no-bold font-small">
                Here you can find documentation about Notefox API services
                <br>
                The source code of Notefox –both the add-on and the API– is available on <a
                        href="https://github.com/Sav22999/website-basic/tree/notefox.eu">GitHub</a>.
            </h2>
            <hr class="hr-big-space">
            <button type="button" class="help-faq-item" onclick="goto('./v1/signup/')">/v1/signup/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/login/')">/v1/login/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/logout/')">/v1/logout/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/last-update/get')">/v1/last-update/get/
            </button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/data/get/')">/v1/data/get/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/data/update/')">/v1/data/update/</button>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>