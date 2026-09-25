<html>
<head>
    <?php
    $title = "Reviews";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/old/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "reviews";
include_once($_SERVER['DOCUMENT_ROOT'] . "/old/include/menu.php");
?>

<!--some random reviews-->
<script>
    var allReviews = [];
    var currentPosition = 0;

    function randomSort() {
        return Math.random() - 0.5;
    }

    function next() {
        if (nextReview(allReviews, currentPosition)) {
            currentPosition++;
        }
        console.log(currentPosition);
    }

    function prev() {
        if (prevReview(allReviews, currentPosition)) {
            currentPosition--;
        }
        console.log(currentPosition);
    }

    document.addEventListener("readystatechange", (event) => {
        switch (document.readyState) {
            case "complete":
                allReviews = getAllReviews();
                allReviews.sort(randomSort);
                loadFirstReview(allReviews);
                break;
        }
    });
</script>

<main>
    <div class="vertical-middle">
        <div class="horizontal-center">
            <input type="button" class="button button-with-icon-secondary button-secondary button-next"
                   id="next-btn-review" value="" onclick="next()">
            <input type="button" class="button button-with-icon-secondary button-secondary button-prev" value=""
                   id="prev-btn-review" onclick="prev()">
            <div id="all-reviews"></div>
        </div>
    </div>
</main>

</body>
</html>

<?php
?>