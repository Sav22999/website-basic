<html>
<head>
    <?php
    $title = "News";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "news";
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
    <div class="horizontal-center">
        <h1 class="title-section">News</h1>
        <h2 class="subtitle-section no-bold font-small">In this page you'll find all news about the app, grouped by
            release</h2>
    </div>
    <br class="big-space">
    <div class="expanding-container">
        <button type="button" class="expanding-item">Release 1.11α – •Not published yet•</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added "onTap" when tap in a generic point of the page hide and show the topbar
                <br>
                • Improved gestures
                <br>
                • Some fixes
                <br>
                • Updated the library
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.10.4 – 30 Mar 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added "Get help" in the menu panel
                <br>
                • Improved night theme
                <br>
                • Min SDK supported now 24 (early was 16)
                <br>
                • Updated languages
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.10.3 – 28 Mar 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Improved icons gestures
                <br>
                • New icon for "bookmarks"
                <br>
                • Added "All bookmarks" button in the menu panel
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.10.2 – 28 Mar 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Fixed a bug in scrollbar
                <br>
                • Added "number of page" during scrolling with scrollbar button
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.10.1 – 28 Mar 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                Fixed some bugs
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.10 – 27 Mar 2023</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added "Scrollbar button" feature to navigate faster
                <br>
                • Fixed many bugs
                <br>
                • Improved animations (gestures) of bookmarks
                <br>
                • Disabled Instagram popup
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.9 – 24 Apr 2022</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added "Bookmarks": now you can add, remove and manage bookmarks
                <br>
                • Created the <a href="https://www.instagram.com/savpdfviewer/">Intagram account</a> of the app <a
                    href="https://www.instagram.com/savpdfviewer/">@savpdfviewer</a>!
                <br>
                • Improved the backend code and the frontend UI
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.8.0.2 – 12 Oct 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                Published on F-Droid
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.8.0.1 – 2 Oct 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                Fixed a small bug
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.8 – 1 Oct 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added "menu panel" to optimise the app for small-display devices as well
                <br>
                • Fixed a bug with "Night light"
                <br>
                • Improved performance
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.7 – 30 Sep 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                Top bar disappears after 5 seconds of inactivity
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.6.3 / 1.6.4 – 30 Aug 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Fixed a bug (page is restored after orientation changing)
                <br>
                • Fixed a bug (the share feature didn't work correctly)
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.6.2 – 21 Jun 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                Added translations
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.6.1 – 21 Jun 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                Fixed fit width when change orientation
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.6 – 11 Jun 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                Added the night light
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.5.1 – 10 Jun 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                Added button "open new file"
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.5 – 8 Jun 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added support for PDF protected by password
                <br>
                • Added button "go to the top"
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.4.2 – 8 Jun 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                Added "go to" feature
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.4.1 – 8 Jun 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                Fixed bug with last position
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.4 – 7 Jun 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added "total pages"
                <br>
                • Fixed bug with orientation
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.3 – 6 Jun 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added "top bar"
                <br>
                • Added "full screen" mode
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.2 – 1 May 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                Fixed a crash in Android 11
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.1.1 – 11 Jan 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                • Added "Share" button
                <br>
                • Added "review message dialog"
            </p>
        </div>

        <button type="button" class="expanding-item">Release 1.0 – 20 Jan 2021</button>
        <div class="expanding-item--expanded-details hidden">
            <p>
                First release of the app
            </p>
        </div>
    </div>
</main>


</body>
</html>

<?php
?>