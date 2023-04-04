var currentExpanded = -1;

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