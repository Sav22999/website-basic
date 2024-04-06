var currentExpanded = -1;
var action = "";

document.addEventListener("DOMContentLoaded", function () {
    //when document loaded
    //sendStatistics();
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

function goto(url) {
    location.href = url;
}