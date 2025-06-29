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
                <b>The following terms of service refeeres to Notefox Account. If you don't use it, the add-on won't collect any data at all – data is not sent to the server.</b>
            </p>
            <p>
                The project is released under the <a href="https://www.gnu.org/licenses/gpl-3.0.html">GNU General Public
                    License v3.0</a>. This mean you are free to use and modify it, but it is not allowed to sell it or
                use it for commercial purposes.
            </p>
            <p>
                The project is hosted on <a href="https://aruba.it">Aruba</a> servers, which is located in Italy and it
                is put effort to eco-sustainability: <a
                        href="https://www.aruba.it/en/certification/eco-friendly-go-certification.aspx">read more</a>.
            </p>
            <p>
                Notefox add-on uses API services to store and retrieve data, hosted on the current domain. You can find
                the documentation <a href="/docs/">here</a>.
                <br>
                On your web browser it will be stored your token access and your login-id, which are used to authenticate
                your requests, and data will be stored on your local storage decrypted.
                <br>
                All data on server side is encrypted and it is not possible to read it without the password.
            </p>
            <p>
                The service now is provided for free, but it is not guaranteed the service will be available in the
                future, and it is not guaranteed the data will be stored forever.
                <br>
                <b>Notefox is not responsible for any data loss or data corruption, and it is not responsible for any
                    damage caused by the use of the service.</b>
            </p>
            <p>
                Notefox collects ip-addresses as unencrypted data, which are used for security purposes and for
                statistics. The data is not shared with third parties.
            </p>
            <p>
                To get more details, please <a href="/help/">contact me</a>.
            </p>
            <p>
                The current Terms of Service can be changed at any time, and it is your responsibility to check them
                periodically.
                <br>
                Last update: 10 Apr 2024
            </p>
        </div>
    </div>
</main>


</body>
</html>

<?php
?>