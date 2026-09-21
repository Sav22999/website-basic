<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $title = "Reviews";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/alpha/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "reviews";
include_once($_SERVER['DOCUMENT_ROOT'] . "/alpha/include/menu.php");
?>

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
    }

    function prev() {
        if (prevReview(allReviews, currentPosition)) {
            currentPosition--;
        }
    }

    document.addEventListener("readystatechange", function (event) {
        if (document.readyState === "complete") {
            allReviews = getAllReviews();
            allReviews.sort(randomSort);
            loadFirstReview(allReviews);
            updateReviewNav(allReviews, 0);
        }
    });
</script>

<main>
    <section class="hero">
        <div class="hero__inner" style="max-width: 680px;">
            <h1 class="title-section">Reviews</h1>
            <p class="subtitle-section no-bold" style="margin-bottom: 32px;">What people say about the app</p>

            <div id="all-reviews"></div>

            <div class="review-nav">
                <button type="button" class="button-nav-review"
                        id="prev-btn-review" aria-label="Previous review" onclick="prev()" style="display:none;">
                    <span class="nav-arrow nav-arrow--prev"></span>
                </button>
                <button type="button" class="button-nav-review"
                        id="next-btn-review" aria-label="Next review" onclick="next()">
                    <span class="nav-arrow nav-arrow--next"></span>
                </button>
            </div>
        </div>
    </section>
</main>

<footer>
    Developed with
    <span class="image-heart image-background-primary image-square-20px"></span>
    by <a href="https://saveriomorelli.com" class="author-name">Saverio Morelli</a>
</footer>

</body>
</html>
