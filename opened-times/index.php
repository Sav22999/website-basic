<html>
<head>
    <?php
    $title = "Opened times – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "privacy";
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
            <h1 class="title-section center">Thank you!</h1>
            <p>
                You opened the addon popup so many times, so I hope you like Notefox.
            </p>
            <p>
                Because you are an usual user, maybe you have some suggestions or feedbacks: <b>I want to hear you</b>.
                Write to me via Telegram, via email or opening an issue on GitHub. You can also write a review on the
                Firefox Add-ons website or on your favourite add-ons store.
            </p>
            <p>
                In addition, I want to remember you that Notefox is a <b>totally free</b> and <b>open-source add-on</b>.
                This means that I don't earn anything directly from the add-on, but only from donations. You can support
                my work making me a donation.
            </p>
            <p>
                <b>Thank you for using Notefox!</b>
            </p>
            <p class="center">
                <input type="button" class="button button-with-icon button-liberapay" value="LiberaPay"
                       onclick="location.href='https://liberapay.com/Sav22999/'">
                <input type="button" class="button button-with-icon button-paypal" value="PayPal"
                       onclick="location.href='https://www.paypal.me/saveriomorelli'">
            </p>
        </div>
    </div>
</main>


</body>
</html>

<?php
?>