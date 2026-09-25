var currentExpanded = -1;
var action = "";

document.addEventListener("DOMContentLoaded", function () {
    //when document loaded
    sendStatistics();
}, false);

function expandContainer() {
    //Expand or reduce the container
    var items = document.getElementsByClassName("expanding-item");
    var items = document.querySelectorAll(".expanding-item");

    for (let i = 0; i < items.length; i++) {
        items[i].onclick = function () {
            this.classList.toggle("expanding-item--expanded");
            let detailsElement = document.getElementsByClassName("expanding-item--expanded-details")[i];
            detailsElement.classList.toggle("hidden");
        }
    }
}

function generateReviews() {
    //Generate review in "Reviews"
    //let allReviews = document.getElementsByClassName("review-container");
    //allReviews.length

    let allReviewsJSON = getAllReviews();

    const INDEX_TO_USE = getRandomInt(allReviewsJSON.length); //random number between 0 and (length-1)

    let currentReview = allReviewsJSON[INDEX_TO_USE];

    let allReviewsParent = document.getElementById("all-reviews");
    allReviewsParent.innerHTML = "";
    let reviewToAdd = '<div class="justify center-container review-container">\n' +
        '        <h2 class="subtitle-section no-bold center stars-review">' + getStarsToShow(currentReview.stars) + '</h2>\n' +
        '        <br class="big-space">\n' +
        '        <br>\n' +
        '        <blockquote>\n' +
        '            ' + currentReview.description + '\n' +
        '            <br class="big-space">\n' +
        '            <br>\n' +
        '            <span class="font-small"><i>' + currentReview.author + '</i> on <i>' + getAppStoreName(currentReview.source) + '</i></span>\n' +
        '        </blockquote>\n' +
        '    </div>';
    allReviewsParent.innerHTML += reviewToAdd;

    /*

    */
}

function loadFirstReview(allReviews) {
    loadReview(allReviews, 0);
}

function nextReview(allReviews, index) {
    if (index < (allReviews.length - 1)) {
        index++;
        loadReview(allReviews, index);

        if (index === (allReviews.length - 1)) {
            document.getElementById("next-btn-review").style.display = "none";
        }else{
            document.getElementById("next-btn-review").style.display = "inline-block";
        }
        if (index === 0) {
            document.getElementById("prev-btn-review").style.display = "none";
        }else{
            document.getElementById("prev-btn-review").style.display = "inline-block";
        }

        return true;
    }

    return false;
}

function prevReview(allReviews, index) {
    if (index > 0) {
        index--;
        loadReview(allReviews, index);

        if (index === (allReviews.length - 1)) {
            document.getElementById("next-btn-review").style.display = "none";
        }else{
            document.getElementById("next-btn-review").style.display = "inline-block";
        }
        if (index === 0) {
            document.getElementById("prev-btn-review").style.display = "none";
        }else{
            document.getElementById("prev-btn-review").style.display = "inline-block";
        }

        return true;
    }

    return false;
}

function loadReview(allReviews, index) {
    let currentReview = allReviews[index];

    let allReviewsParent = document.getElementById("all-reviews");
    allReviewsParent.innerHTML = "";
    let reviewToAdd = '<div class="justify center-container review-container">\n' +
        '        <h2 class="subtitle-section no-bold center stars-review">' + getStarsToShow(currentReview.stars) + '</h2>\n' +
        '        <br class="big-space">\n' +
        '        <br>\n' +
        '        <blockquote>\n' +
        '            ' + currentReview.description + '\n' +
        '            <br class="big-space">\n' +
        '            <br>\n' +
        '            <span class="font-small"><i>' + currentReview.author + '</i> on <i>' + getAppStoreName(currentReview.source) + '</i></span>\n' +
        '        </blockquote>\n' +
        '    </div>';
    allReviewsParent.innerHTML += reviewToAdd;
}

function getAllReviews() {
    json = [{
        "stars": 5,
        "description": "In the world of PDF this app I like. I only need a viewer so more than does its job and free with no permissions. Thanks dev.",
        "author": "Philip Hayes",
        "source": "google",
        "url": false,
        "original_language": "en",
        "date": "18/11/2021"
    }, {
        "stars": 5,
        "description": "Excellent app, much quicker than the one I used until now. I was about to suggest a sort of 'jump to' or a 'quick scroll' function but you added it before I even had a chance to suggest it. Nice to see there's no ads, tracking or telemetry too. Keep up the good work!",
        "author": "Zaragil von Slangenwald",
        "source": "google",
        "url": false,
        "original_language": "en",
        "date": "08/06/2021"
    }, {
        "stars": 5,
        "description": "A solid 5 since this app does the job as advertised and it does not require unnecessary permissions, infact it doesn't even require any permission at all..Thnx for making this world a lil bit more privacy friendly.",
        "author": "Shubham Chauhan",
        "source": "google",
        "url": false,
        "original_language": "en",
        "date": "12/06/2021"
    }, {
        "stars": 5,
        "description": "Open my PDF simple and easy to use if u only need to open PDF + Share works well.. I happy to give 5 as nill-permis works can sit in the back ground for the time I need to open PDF",
        "author": "Josh O'Niell",
        "source": "google",
        "url": false,
        "original_language": "en",
        "date": "01/05/2021"
    }, {
        "stars": 5,
        "description": "Lightweight and functional. Easy navigation in folders.",
        "author": "Francesco",
        "source": "google",
        "url": false,
        "original_language": "en",
        "date": "22/09/2021"
    }, {
        "stars": 5,
        "description": "Simple yet useful app. Loving it !",
        "author": "chintan gandhi",
        "source": "google",
        "url": false,
        "original_language": "en",
        "date": "22/09/2022"
    }, {
        "stars": 5,
        "description": "Works great! Very few documents it won't open. No complaints. Good work, dev!",
        "author": "John Bittner",
        "source": "google",
        "url": false,
        "original_language": "en",
        "date": "19/05/2023"
    }, {
        "stars": 5,
        "description": "Also very good for comics. Bravo.",
        "author": "Andre Furlan",
        "source": "google",
        "url": false,
        "original_language": "it",
        "date": "10/06/2021"
    }, {
        "stars": 5,
        "description": "Excellent application and excellent customer service, they give a quick solution, they solve the problem with the application.",
        "author": "David gonzalez",
        "source": "google",
        "url": false,
        "original_language": "es",
        "date": "29/06/2023"
    }, {
        "stars": 4,
        "description": "Great simplicity in this pdf reader! The scrolling bug was fixed. What I am missing is that it's not possible to see the top of a page without the app-menu overlapping. Maybe a simple tap anywhere on the page could hide the menu. Scrolling down only makes it transparent.",
        "author": "Lukas Zielonka",
        "source": "google",
        "url": false,
        "original_language": "en",
        "date": "30/07/2021"
    }, {
        "stars": 4,
        "description": "Although it doesn't offer as many options, found it opened pdfs quicker than my multi-format viewers and requires less disk space. Probably the best quick pdf viewer I've tried.",
        "author": "Klaatu58",
        "source": "google",
        "url": false,
        "original_language": "en",
        "date": "20/10/2023"
    }, {
        "stars": 5,
        "description": "Update 1.13.4 soluciona el problema que no permitía abrir archivos desde Gmail. Muy buen trabajo. Cumple en forma excelente con su función. Estaba esperando a que solucionaran este bug para eliminar otro gestor para PDFs, 👍👍👍👍👍",
        "author": "Ciro Alvarez",
        "source": "google",
        "url": false,
        "original_language": "en",
        "date": "18/10/2023"
    }, {
        "stars": 5,
        "description": "For those looking for a lightweight PDF reader that is easy to use and has no frills, here it is. It does exactly what the name says: view PDFs; those looking for something else (annotations, opening links, dividing pages, etc.) should look for another app. 5 stars well deserved.",
        "author": "Giovanni Galanti",
        "source": "google",
        "url": false,
        "original_language": "it",
        "date": "02/08/2023"
    }, {
        "stars": 5,
        "description": "Top what he promises. We need to innovate, as we can also share.",
        "author": "Jose Alves",
        "source": "google",
        "url": false,
        "original_language": "pt",
        "date": "29/07/2023"
    }, {
        "stars": 5,
        "description": "Great app, quick and clear view, easy to use, I like it.",
        "author": "宋志雲",
        "source": "google",
        "url": false,
        "original_language": "en",
        "date": "29/07/2023"
    }/*, {
        "stars": 5,
        "description": "",
        "author": "",
        "source": "google",
        "url": false,
        "original_language": "en",
        "date": ""
    }*/]

    return json;
}

function getStarsToShow(stars) {
    const MAX_STARS = 5;
    let starsToShow = "";
    let reviewedStars = stars;
    let residualStars = MAX_STARS - reviewedStars;
    let starYesSymbol = "★";
    let starNoSymbol = "☆";
    let index = MAX_STARS;
    while (index > residualStars) {
        index--;
        starsToShow += starYesSymbol;
    }
    while (index > 0) {
        index--;
        starsToShow += starNoSymbol;
    }
    return starsToShow;
}

function getAppStoreName(name) {
    storesSource = {
        "google": "Google Play",
        "github": "GitHub",
        "huawei": "Huawei AppGallery",
        "amazon": "Amazon AppStore"
    }
    return storesSource[name];
}

function getOriginalLanguage(code) {
    originalLanguages = {
        "en": "English",
        "it": "Italian",
        "fr": "French",
        "es": "Spanish",
        "ru": "Russian"
    }
    return originalLanguages[code];
}

function getRandomInt(max) {
    return Math.floor(Math.random() * max);
}

function sendStatistics() {

    let url = "/api/v1/statistics/insert/index.php?action=" + action;

    $.ajax({
        url: url,
        type: 'get',
        contentType: "application/json",
        dataType: "json",
        crossOrigin: true,
        processData: false,
        success: function (response) {
            if (response != 0) {
                if (response["status"] == "OK") {
                    //console.log("Statistic added correctly");
                } else {
                    //console.log(`Error: ${response["description"]}`);
                }
            } else {
                //console.log(`Unexpected error (1): ${JSON.stringify(response)}`);
            }
        },
        error: function (response) {
            //console.log(`Unexpected error (2) ${JSON.stringify(response)}`);
        }
    });
}

function setAction(actionToSet = "") {
    action = actionToSet
}