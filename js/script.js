var currentExpanded = -1;

function getDevicePlatform() {
    var ua = navigator.userAgent || "";
    if (/android/i.test(ua)) return "android";
    if (/iPad|iPhone|iPod/.test(ua) || (navigator.platform === "MacIntel" && navigator.maxTouchPoints > 1)) return "ios";
    return "desktop";
}

function getPlatformNotice(platform) {
    var s = window._i18n || {};
    var notices = {
        "ios": s["platform.not_ios"] || "Not available on iOS",
        "desktop": ""
    };
    var ua = navigator.userAgent || "";
    if (platform === "desktop") {
        if (/Macintosh/i.test(ua)) notices.desktop = s["platform.not_macos"] || "Not available on macOS";
        else if (/Windows/i.test(ua)) notices.desktop = s["platform.not_windows"] || "Not available on Windows";
        else if (/Linux/i.test(ua)) notices.desktop = s["platform.not_linux"] || "Not available on Linux";
    }
    return notices[platform] || "";
}

function applyPlatformMessages() {
    var platform = getDevicePlatform();
    document.querySelectorAll("[data-platform-android]").forEach(function (el) {
        if (platform === "android") {
            el.textContent = el.getAttribute("data-platform-android");
        } else if (platform === "ios") {
            el.textContent = el.getAttribute("data-platform-ios") || el.textContent;
        } else {
            el.textContent = el.getAttribute("data-platform-desktop") || el.textContent;
        }
    });

    var notice = getPlatformNotice(platform);
    document.querySelectorAll(".platform-notice").forEach(function (el) {
        if (notice) {
            el.textContent = notice;
            el.style.display = "";
        }
    });
}

document.addEventListener("DOMContentLoaded", applyPlatformMessages);

function expandContainer() {
    var items = document.querySelectorAll(".expanding-item");
    for (let i = 0; i < items.length; i++) {
        items[i].addEventListener("click", function () {
            this.classList.toggle("expanding-item--expanded");
            let detailsElement = document.querySelectorAll(".expanding-item--expanded-details")[i];
            detailsElement.classList.toggle("hidden");
        });
    }
}

function loadFirstReview(allReviews) {
    loadReview(allReviews, 0);
}

function nextReview(allReviews, index) {
    if (index < (allReviews.length - 1)) {
        index++;
        loadReview(allReviews, index);
        updateReviewNav(allReviews, index);
        return true;
    }
    return false;
}

function prevReview(allReviews, index) {
    if (index > 0) {
        index--;
        loadReview(allReviews, index);
        updateReviewNav(allReviews, index);
        return true;
    }
    return false;
}

function updateReviewNav(allReviews, index) {
    var nextBtn = document.getElementById("next-btn-review");
    var prevBtn = document.getElementById("prev-btn-review");
    if (nextBtn) nextBtn.style.display = (index >= allReviews.length - 1) ? "none" : "inline-flex";
    if (prevBtn) prevBtn.style.display = (index <= 0) ? "none" : "inline-flex";
}

function loadReview(allReviews, index) {
    var currentReview = allReviews[index];
    var allReviewsParent = document.getElementById("all-reviews");
    allReviewsParent.innerHTML =
        '<div class="review-container">' +
        '<div class="review-body">' +
        '<p class="review-text">' + currentReview.description + '</p>' +
        '</div>' +
        '<div class="review-footer">' +
        '<div class="review-stars">' + getStarsToShow(currentReview.stars) + '</div>' +
        '<div class="review-author">' +
        '<span class="review-author__name">' + currentReview.author + '</span>' +
        '<span class="review-author__source">' + getAppStoreName(currentReview.source) + '</span>' +
        '</div>' +
        '</div>' +
        '</div>';
}

function getAllReviews() {
    return [{
        "stars": 5,
        "description": "In the world of PDF this app I like. I only need a viewer so more than does its job and free with no permissions. Thanks dev.",
        "author": "Philip Hayes",
        "source": "google",
        "original_language": "en",
        "date": "18/11/2021"
    }, {
        "stars": 5,
        "description": "Excellent app, much quicker than the one I used until now. I was about to suggest a sort of 'jump to' or a 'quick scroll' function but you added it before I even had a chance to suggest it. Nice to see there's no ads, tracking or telemetry too. Keep up the good work!",
        "author": "Zaragil von Slangenwald",
        "source": "google",
        "original_language": "en",
        "date": "08/06/2021"
    }, {
        "stars": 5,
        "description": "A solid 5 since this app does the job as advertised and it does not require unnecessary permissions, infact it doesn't even require any permission at all..Thnx for making this world a lil bit more privacy friendly.",
        "author": "Shubham Chauhan",
        "source": "google",
        "original_language": "en",
        "date": "12/06/2021"
    }, {
        "stars": 5,
        "description": "Open my PDF simple and easy to use if u only need to open PDF + Share works well.. I happy to give 5 as nill-permis works can sit in the back ground for the time I need to open PDF",
        "author": "Josh O'Niell",
        "source": "google",
        "original_language": "en",
        "date": "01/05/2021"
    }, {
        "stars": 5,
        "description": "Lightweight and functional. Easy navigation in folders.",
        "author": "Francesco",
        "source": "google",
        "original_language": "en",
        "date": "22/09/2021"
    }, {
        "stars": 5,
        "description": "Simple yet useful app. Loving it !",
        "author": "chintan gandhi",
        "source": "google",
        "original_language": "en",
        "date": "22/09/2022"
    }, {
        "stars": 5,
        "description": "Works great! Very few documents it won't open. No complaints. Good work, dev!",
        "author": "John Bittner",
        "source": "google",
        "original_language": "en",
        "date": "19/05/2023"
    }, {
        "stars": 5,
        "description": "Also very good for comics. Bravo.",
        "author": "Andre Furlan",
        "source": "google",
        "original_language": "it",
        "date": "10/06/2021"
    }, {
        "stars": 5,
        "description": "Excellent application and excellent customer service, they give a quick solution, they solve the problem with the application.",
        "author": "David gonzalez",
        "source": "google",
        "original_language": "es",
        "date": "29/06/2023"
    }, {
        "stars": 4,
        "description": "Great simplicity in this pdf reader! The scrolling bug was fixed. What I am missing is that it's not possible to see the top of a page without the app-menu overlapping. Maybe a simple tap anywhere on the page could hide the menu. Scrolling down only makes it transparent.",
        "author": "Lukas Zielonka",
        "source": "google",
        "original_language": "en",
        "date": "30/07/2021"
    }, {
        "stars": 4,
        "description": "Although it doesn't offer as many options, found it opened pdfs quicker than my multi-format viewers and requires less disk space. Probably the best quick pdf viewer I've tried.",
        "author": "Klaatu58",
        "source": "google",
        "original_language": "en",
        "date": "20/10/2023"
    }, {
        "stars": 5,
        "description": "Update 1.13.4 soluciona el problema que no permitía abrir archivos desde Gmail. Muy buen trabajo. Cumple en forma excelente con su función.",
        "author": "Ciro Alvarez",
        "source": "google",
        "original_language": "es",
        "date": "18/10/2023"
    }, {
        "stars": 5,
        "description": "For those looking for a lightweight PDF reader that is easy to use and has no frills, here it is. It does exactly what the name says: view PDFs; those looking for something else should look for another app. 5 stars well deserved.",
        "author": "Giovanni Galanti",
        "source": "google",
        "original_language": "it",
        "date": "02/08/2023"
    }, {
        "stars": 5,
        "description": "Top what he promises. We need to innovate, as we can also share.",
        "author": "Jose Alves",
        "source": "google",
        "original_language": "pt",
        "date": "29/07/2023"
    }, {
        "stars": 5,
        "description": "Great app, quick and clear view, easy to use, I like it.",
        "author": "宋志雲",
        "source": "google",
        "original_language": "en",
        "date": "29/07/2023"
    }, {
        "stars": 5,
        "description": "This is how all apps should be. I love this app because the loading time of files is incredible, and the viewing experience is one of the best. The auto hide top bar is simply genius. Making it my default PDF viewer. RECOMMEND!",
        "author": "Bento Pereira",
        "source": "google",
        "original_language": "en",
        "date": "21/08/2026"
    }, {
        "stars": 5,
        "description": "Best PDF viewer. Simple and quick with plenty of features when you need them. I had a small issue and the app developer was super responsive.",
        "author": "Ben G",
        "source": "google",
        "original_language": "en",
        "date": "10/08/2026"
    }, {
        "stars": 5,
        "description": "Simple, lightweight and feature rich. One-off payment. Ideal!",
        "author": "Daina Byrne",
        "source": "google",
        "original_language": "en",
        "date": "24/11/2024"
    }, {
        "stars": 5,
        "description": "Does what it's designed for. Keep up the good work dev. Cheers!",
        "author": "Phillip C",
        "source": "google",
        "original_language": "en",
        "date": "04/06/2026"
    }, {
        "stars": 5,
        "description": "Developer is fast and responsive, the PDFs load smoothly and quickly. I definitely recommend.",
        "author": "Kyle Oyama",
        "source": "google",
        "original_language": "en",
        "date": "01/05/2021"
    }, {
        "stars": 5,
        "description": "The app speed is fantastic. Thank you very much for updates. I am going to recommend this app to my family and friends too.",
        "author": "NPC# 0091",
        "source": "google",
        "original_language": "en",
        "date": "30/09/2021"
    }, {
        "stars": 5,
        "description": "I have used this app for years. This is the best PDF viewer in the Play Store.",
        "author": "John Keisers",
        "source": "google",
        "original_language": "en",
        "date": "23/03/2026"
    }, {
        "stars": 5,
        "description": "Very compact app. Highly recommended.",
        "author": "Balaji Narasimhan",
        "source": "google",
        "original_language": "en",
        "date": "18/10/2025"
    }, {
        "stars": 5,
        "description": "Very simple and effective. Have been using it for years.",
        "author": "Kyle Janniere",
        "source": "google",
        "original_language": "en",
        "date": "13/12/2024"
    }, {
        "stars": 4,
        "description": "One of the best PDF viewer in my opinion. Although I would like a rename option for the bookmarks, it does everything I need.",
        "author": "Vanessa Zakeva",
        "source": "google",
        "original_language": "en",
        "date": "06/03/2025"
    }];
}

function getStarsToShow(stars) {
    var filled = '<svg class="star-icon star-icon--filled" viewBox="0 0 20 20"><path d="M10 1.5l2.47 5.01 5.53.8-4 3.9.94 5.49L10 13.77 5.06 16.7l.94-5.49-4-3.9 5.53-.8L10 1.5z"/></svg>';
    var empty = '<svg class="star-icon star-icon--empty" viewBox="0 0 20 20"><path d="M10 1.5l2.47 5.01 5.53.8-4 3.9.94 5.49L10 13.77 5.06 16.7l.94-5.49-4-3.9 5.53-.8L10 1.5z"/></svg>';
    var result = "";
    for (var i = 0; i < 5; i++) {
        result += (i < stars) ? filled : empty;
    }
    return result;
}

function getAppStoreName(name) {
    var stores = {
        "google": "Google Play",
        "github": "GitHub",
        "huawei": "Huawei AppGallery",
        "amazon": "Amazon AppStore"
    };
    return stores[name] || name;
}
