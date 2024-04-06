<html>
<head>
    <?php
    $title = "Terms of Service – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "terms";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<script>
    document.addEventListener("readystatechange", (event) => {
        switch (document.readyState) {
            case "complete":
                expandContainer();
                break;
        }
    });
</script>

<main class="padding-top-menu">
    <div class="horizontal-center ">
        <div class="center-content justify">
            <h1 class="title-section center">Terms of Service</h1>
            <p>
                <i>Notefox: websites notes</i> is an open-source project developed by Saverio Morelli.
            </p>
            <p>
                The project is released under the <a href="https://www.gnu.org/licenses/gpl-3.0.html">GNU General Public License v3.0</a>. This mean you are free to use and modify it, but it is not allowed to sell it or use it for commercial purposes.
            </p>
            <p>
                The project is hosted on <a href="https://aruba.it">Aruba</a> servers, which is located in Italy and it is put effort to eco-sustainability: <a href="https://www.aruba.it/en/certification/eco-friendly-go-certification.aspx">read more</a>.
            </p>
            <p>
                Notefox add-on uses API services to store and retrieve data, hosted on the current domain. You can find the documentation <a href="/docs/">here</a>.
                <br>
                On your web browser it will be stored your password and your login-id, which is used to authenticate your requests, and data will be stored on your local storage encrypted in AES-256.
            </p>
            <p>
                To get more details, please <a href="/help/">contact me</a>.
            </p>
            <p>

            </p>
        </div>
    </div>
</main>


</body>
</html>

<?php
?>