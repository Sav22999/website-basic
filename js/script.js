var currentExpanded = -1;
var action = "";

document.addEventListener("DOMContentLoaded", function () {
    //when document loaded
    sendStatistics();
}, false);

function expandContainer() {
    //Expand or reduce the container
    var coll = document.getElementsByClassName("expanding-item");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function () {
            this.classList.toggle("expanding-item--expanded");
            var content = this.nextElementSibling;
            if (content.style.display === "block") {
                content.style.display = "none";
            } else {
                content.style.display = "block";
            }
        });
    }
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