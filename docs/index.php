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
            <button type="button" class="help-faq-item" onclick="goto('./v1/data/get/')">/v1/data/get/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/data/get/last-update/')">/v1/data/get/last-update/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/data/insert/')">/v1/data/insert/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/delete/')">/v1/delete/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/delete/verify/')">/v1/delete/verify/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/delete/verify/get-new-code/')">/v1/delete/verify/get-new-code/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/login/')">/v1/login/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/login/check-id/')">/v1/login/check-id/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/login/set-expiry/')">/v1/login/set-expiry/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/login/verify/')">/v1/login/verify/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/login/verify/get-new-code/')">/v1/login/verify/get-new-code/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/logout/')">/v1/logout/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/password/edit/')">/v1/password/edit/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/signup/')">/v1/signup/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/signup/verify/')">/v1/signup/verify/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/signup/verify/get-new-code/')">/v1/signup/verify/get-new-code/</button>
            <button type="button" class="help-faq-item" onclick="goto('./v1/token/set-expiry/')">/v1/token/set-expiry/</button>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>