<html>
<head>
    <?php
    $title = "The sticky-notes – Notefox";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/include/header.php");
    ?>
</head>
<body>
<?php
$selected_menu = "sticky-notes";
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/menu.php");
?>

<main class="padding-top-menu">
    <div class="horizontal-center">
        <div class="center-content justify">
            <h1 class="title-section center">The sticky-notes!</h1>
            <h2 class="subtitle-section no-bold font-small">Try the feature here!</h2>
            <p>
                Try to do the following actions:
                <br>
                • Move the sticky-note: click and drag the top bar – where you can see the tag color or the page/domain
                <br>
                • Resize the sticky-note: click and drag the bottom right corner – you can see a small orange triangle
                <br>
                • Change the opacity: use the slider on the bottom
                <br>
                • Close the sticky-note: click on the top right button
                <br>
                • Minimize the sticky-note: click on the top left button
                <br>
                • Write your notes: click on the text area and write your notes
                <br>
                • Format your notes using the keyboard shortcuts: Ctrl+B for bold, Ctrl+I for italic, Ctrl+U for underline, Ctrl+S for strikethrough, Ctrl+L for link
                <br>
                • Paste images: copy an image and paste it in the text area
            </p>
        </div>
    </div>
</main>

<script>
    function load() {
        if (document.getElementById("sticky-notes-notefox--website-test") && !document.getElementById("restore--sticky-notes-notefox--website-test")) {
            //already exists || update elements
        } else if (document.getElementById("sticky-notes-notefox--website-test") && document.getElementById("restore--sticky-notes-notefox--website-test")) {
            //it's exists as minimized
            openMinimized();
        } else {
            //create new
            let x = "20px";
            let y = "20px";
            let w = "300px";
            let h = "300x";
            let opacity = 0.8;
            createNewDescription(x, y, w, h, opacity);
        }
    }

    function createNewDescription(x, y, w, h, opacity) {
        let notes = {
            description: "This is a sticky-note! You can write here your notes!",
            url: "https://www.notefox.eu/sticky-notes/",
            page_domain_global: "Page",
            tag_colour: "gray",
            website: {},
            type: "page"
        };
        createNew(notes, x, y, w, h, opacity, {}, {});
    }


    /**
     * The sticky does NOT exist, so I need to create it totally
     */
    function createNew(notes, x = "10px", y = "10px", w = "200px", h = "300px", opacity = 0.8, websites_json, settings_json) {
        if (!document.getElementById("sticky-notes-notefox--website-test")) {
            let css = document.createElement("style");
            css.innerText = getCSS(notes, x, y, w, h, opacity, websites_json, settings_json);
            document.body.appendChild(css);

            if (document.getElementById("restore--sticky-notes-notefox--website-test")) document.getElementById("restore--sticky-notes-notefox--website-test").remove();

            let move = document.createElement("div");
            move.id = "move--sticky-notes-notefox--website-test";

            let resize = document.createElement("div");
            resize.id = "resize--sticky-notes-notefox--website-test";

            let textContainer = document.createElement("div");
            textContainer.id = "text-container--sticky-notes-notefox--website-test";
            listenerLinks(textContainer, settings_json);

            let text = document.createElement("pre");
            text.id = "text--sticky-notes-notefox--website-test";
            text.innerHTML = notes.description;
            text.contentEditable = true;

            checkDisableWordWrap(text, settings_json);
            checkLanguageSpellcheck(text, settings_json);

            text.oninput = function () {
                onInputText(text);
            }
            text.onchange = function () {
                onInputText(text);
            }
            text.onkeydown = function (e) {
                onKeyDownText(text, e);
            }
            text.onpaste = function (e) {
                onPasteText(text, e);
            }

            textContainer.appendChild(text);

            let stickyNote = document.createElement("div");
            stickyNote.id = "sticky-notes-notefox--website-test";

            let close = document.createElement("input");
            close.type = "button";
            close.id = "close--sticky-notes-notefox--website-test";
            close.onclick = function () {
                onClickClose(false);
            }
            //close.value = "⋏";
            stickyNote.appendChild(close);

            let minimize = document.createElement("input");
            minimize.type = "button";
            minimize.id = "minimize--sticky-notes-notefox--website-test";
            minimize.onclick = function () {
                stickyNote.remove();
                openMinimized();
            }
            //minimize.value = "≺";
            stickyNote.appendChild(minimize);

            //notes.tag_colour
            let tag = document.createElement("div");
            tag.id = "tag--sticky-notes-notefox--website-test";
            tag.style.backgroundColor = notes.tag_colour;
            stickyNote.appendChild(tag);

            let opacityRangeContainer = document.createElement("div");
            opacityRangeContainer.id = "slider-container--sticky-notes-notefox--website-test";

            let opacityRange = document.createElement("input");
            opacityRange.id = "slider--sticky-notes-notefox--website-test";
            opacityRange.type = "range";
            opacityRange.min = 1;
            opacityRange.max = 100;
            opacityRange.value = (opacity * 100);
            opacityRange.step = 1;

            opacityRangeContainer.appendChild(opacityRange);
            stickyNote.appendChild(opacityRangeContainer);

            let pageOrDomain = document.createElement("div");
            pageOrDomain.id = "page-or-domain--sticky-notes-notefox--website-test";

            let pageDomainGlobalToUse = notes.page_domain_global;
            if (pageDomainGlobalToUse === undefined) pageDomainGlobalToUse = "";
            pageOrDomain.innerText = pageDomainGlobalToUse;
            stickyNote.appendChild(pageOrDomain);

            let isDragging = false;

            move.addEventListener('mousedown', (e) => {
                isDragging = onMouseDownMove(e, stickyNote, isDragging)
            });
            let isResizing = false;
            resize.addEventListener('mousedown', (e) => {
                isResizing = onMouseDownResize(e, stickyNote, isResizing);
            });
            opacityRange.oninput = function () {
                var value = (this.value - this.min) / (this.max - this.min) * 100;
                setSlider(opacityRange, stickyNote, value, true);
            };
            stickyNote.appendChild(move);

            stickyNote.appendChild(resize);
            stickyNote.appendChild(textContainer);

            document.body.appendChild(stickyNote);
        }
    }

    function setSlider(opacityRange, stickyNote, value, update = true) {
        if (value < 20) value = 20;
        opacityRange.value = value;
        opacityRange.style.background = 'linear-gradient(to right, #ff6200 0%, #ff6200 ' + value + '%, #eeeeee ' + value + '%, #eeeeee 100%)';
        stickyNote.style.opacity = (value / 100);
        //console.log(value / 100);
    }

    function checkDisableWordWrap(text, settings_json) {
        let disable_word_wrap = false;
        if (settings_json !== undefined && settings_json["disable-word-wrap"] !== undefined && (settings_json["disable-word-wrap"] === "yes" || settings_json["disable-word-wrap"] === true)) {
            disable_word_wrap = true;
        } else {
            disable_word_wrap = false;
        }
        if (disable_word_wrap) {
            text.style.whiteSpace = "none";
        } else {
            text.style.whiteSpace = "pre-wrap";
        }
    }

    function checkLanguageSpellcheck(text, settings_json) {
        let spellcheck = true;
        if (settings_json !== undefined && (settings_json["spellcheck-detection"] === "no" || settings_json["spellcheck-detection"] === false)) spellcheck = false;
        else spellcheck = true;
        text.spellcheck = spellcheck;
    }

    function isAPage(url) {
        return (url.replace("http://", "").replace("https://", "").split("/").length > 1);
    }

    function getCSS(notes, x = "20px", y = "20px", w = "300px", h = "300px", opacity = 0.8, websites_json, settings_json) {
        let svg_image_close = `PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj48c3ZnIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIHZpZXdCb3g9IjAgMCAxMTIgMTEyIiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zOnNlcmlmPSJodHRwOi8vd3d3LnNlcmlmLmNvbS8iIHN0eWxlPSJmaWxsLXJ1bGU6ZXZlbm9kZDtjbGlwLXJ1bGU6ZXZlbm9kZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6MjsiPjxwYXRoIGQ9Ik05LjI1OSw4My4zMzNjMCwtOC43MjkgMCwtMTMuMDk0IDIuNzEyLC0xNS44MDdjMi43MTIsLTIuNzEyIDcuMDc3LC0yLjcxMiAxNS44MDcsLTIuNzEyYzguNzMsMCAxMy4wOTUsMCAxNS44MDcsMi43MTJjMi43MTIsMi43MTIgMi43MTIsNy4wNzcgMi43MTIsMTUuODA3YzAsOC43MyAwLDEzLjA5NSAtMi43MTIsMTUuODA3Yy0yLjcxMiwyLjcxMiAtNy4wNzcsMi43MTIgLTE1LjgwNywyLjcxMmMtOC43MywwIC0xMy4wOTQsMCAtMTUuODA3LC0yLjcxMmMtMi43MTIsLTIuNzEyIC0yLjcxMiwtNy4wNzcgLTIuNzEyLC0xNS44MDdaIiBzdHlsZT0iZmlsbDojZmZmO2ZpbGwtcnVsZTpub256ZXJvO3N0cm9rZTojZmZmO3N0cm9rZS13aWR0aDowLjE0cHg7Ii8+PHBhdGggZD0iTTE2LjAzOSwxNi4wMzljLTYuNzgsNi43OCAtNi43OCwxNy42OTIgLTYuNzgsMzkuNTE3YzAsMS44MzEgMCwzLjU4NiAwLjAwNCw1LjI2N2MyLjM1MiwtMS41NDIgNC45NDQsLTIuMjE3IDcuNDI5LC0yLjU1MmMyLjk4OSwtMC40MDIgNi42NjQsLTAuNDAxIDEwLjY3MSwtMC40MDFsMC44MjksMGM0LjAwNywtMCA3LjY4MiwtMC4wMDEgMTAuNjcxLDAuNDAxYzMuMjkxLDAuNDQzIDYuNzcsMS40ODQgOS42MzIsNC4zNDVjMi44NjEsMi44NjIgMy45MDIsNi4zNDEgNC4zNDUsOS42MzJjMC40MDEsMi45ODkgMC40MDEsNi42NjQgMC40LDEwLjY3MWwwLDAuODI5YzAuMDAxLDQuMDA4IDAuMDAxLDcuNjgyIC0wLjQsMTAuNjdjLTAuMzM1LDIuNDg2IC0xLjAxLDUuMDc3IC0yLjU1Miw3LjQzYzEuNjgyLDAuMDA0IDMuNDM2LDAuMDA0IDUuMjY3LDAuMDA0YzIxLjgyNCwtMCAzMi43MzYsLTAgMzkuNTE3LC02Ljc4YzYuNzgsLTYuNzggNi43OCwtMTcuNjkyIDYuNzgsLTM5LjUxN2MtMCwtMjEuODI1IC0wLC0zMi43MzYgLTYuNzgsLTM5LjUxN2MtNi43OCwtNi43NzkgLTE3LjY5MiwtNi43NzkgLTM5LjUxNywtNi43NzljLTIxLjgyNSwtMCAtMzIuNzM2LC0wIC0zOS41MTYsNi43NzlsLTAsMC4wMDFabTQ1LjMwMywxMi44OTZjLTEuOTE4LC0wIC0zLjQ3MywxLjU1NCAtMy40NzMsMy40NzJjMCwxLjkxOCAxLjU1NSwzLjQ3MiAzLjQ3MywzLjQ3Mmw4Ljk3OCwwbC0xNy4yMjEsMTcuMjIxYy0xLjM1NiwxLjM1NiAtMS4zNTYsMy41NTQgMCw0LjkxYzEuMzU2LDEuMzU2IDMuNTU0LDEuMzU2IDQuOTEsMGwxNy4yMjEsLTE3LjIybDAsOC45NzhjMCwxLjkxOCAxLjU1NSwzLjQ3MiAzLjQ3MiwzLjQ3MmMxLjkxOCwwIDMuNDczLC0xLjU1NCAzLjQ3MywtMy40NzJsLTAsLTE3LjM2MWMtMCwtMS45MTggLTEuNTU1LC0zLjQ3MiAtMy40NzMsLTMuNDcybC0xNy4zNjEsLTBsMC4wMDEsLTBaIiBzdHlsZT0iZmlsbDojZmZmO3N0cm9rZTojZmZmO3N0cm9rZS13aWR0aDowLjE0cHg7Ii8+PC9zdmc+`;
        let svg_image_minimize = `PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+Cjxzdmcgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDMzNCAzMzQiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgeG1sbnM6c2VyaWY9Imh0dHA6Ly93d3cuc2VyaWYuY29tLyIgc3R5bGU9ImZpbGwtcnVsZTpldmVub2RkO2NsaXAtcnVsZTpldmVub2RkO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoyOyI+CiAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgwLjQxNjY2NywwLDAsMC40MTY2NjcsMCwwKSI+CiAgICAgICAgPHBhdGggZD0iTTUzNy41LDQwMEM1MzcuNSwzODYuMTkzIDUyNi4zMDcsMzc1IDUxMi41LDM3NUwxNDYuNzQ4LDM3NUwyMTIuMTAzLDMxOC45ODFDMjIyLjU4NiwzMDkuOTk2IDIyMy44LDI5NC4yMTMgMjE0LjgxNSwyODMuNzNDMjA1LjgyOSwyNzMuMjQ3IDE5MC4wNDcsMjcyLjAzMyAxNzkuNTY0LDI4MS4wMTlMNjIuODk3LDM4MS4wMkM1Ny4zNTYsMzg1Ljc2NyA1NC4xNjcsMzkyLjcwMyA1NC4xNjcsNDAwQzU0LjE2Nyw0MDcuMjk3IDU3LjM1Niw0MTQuMjMzIDYyLjg5Nyw0MTguOThMMTc5LjU2NCw1MTguOThDMTkwLjA0Nyw1MjcuOTY3IDIwNS44MjksNTI2Ljc1MyAyMTQuODE1LDUxNi4yN0MyMjMuOCw1MDUuNzg3IDIyMi41ODYsNDkwLjAwMyAyMTIuMTAzLDQ4MS4wMkwxNDYuNzQ4LDQyNUw1MTIuNSw0MjVDNTI2LjMwNyw0MjUgNTM3LjUsNDEzLjgwNyA1MzcuNSw0MDBaIiBzdHlsZT0iZmlsbDp3aGl0ZTsiLz4KICAgICAgICA8cGF0aCBkPSJNMzEyLjUsMjY2LjY2N0MzMTIuNSwyOTAuMDczIDMxMi41LDMwMS43NzYgMzE4LjExNywzMTAuMTgzQzMyMC41NDksMzEzLjgyNCAzMjMuNjc1LDMxNi45NDkgMzI3LjMxNSwzMTkuMzgyQzMzNS43MjMsMzI0Ljk5OSAzNDcuNDI3LDMyNC45OTkgMzcwLjgzMywzMjQuOTk5TDUxMi41LDMyNC45OTlDNTUzLjkyLDMyNC45OTkgNTg3LjUsMzU4LjU3NyA1ODcuNSw0MDBDNTg3LjUsNDQxLjQyIDU1My45Miw0NzUgNTEyLjUsNDc1TDM3MC44MzMsNDc1QzM0Ny40MjcsNDc1IDMzNS43Miw0NzUgMzI3LjMxMyw0ODAuNjE3QzMyMy42NzQsNDgzLjA1IDMyMC41NSw0ODYuMTczIDMxOC4xMTgsNDg5LjgxM0MzMTIuNSw0OTguMjIgMzEyLjUsNTA5LjkyMyAzMTIuNSw1MzMuMzMzQzMxMi41LDYyNy42MTMgMzEyLjUsNjc0Ljc1MyAzNDEuNzksNzA0LjA0M0MzNzEuMDgsNzMzLjMzMyA0MTguMjEzLDczMy4zMzMgNTEyLjQ5Myw3MzMuMzMzTDU0NS44MjcsNzMzLjMzM0M2NDAuMTA3LDczMy4zMzMgNjg3LjI0Nyw3MzMuMzMzIDcxNi41MzcsNzA0LjA0M0M3NDUuODI3LDY3NC43NTMgNzQ1LjgyNyw2MjcuNjEzIDc0NS44MjcsNTMzLjMzM0w3NDUuODI3LDI2Ni42NjdDNzQ1LjgyNywxNzIuMzg2IDc0NS44MjcsMTI1LjI0NSA3MTYuNTM3LDk1Ljk1NkM2ODcuMjQ3LDY2LjY2NyA2NDAuMTA3LDY2LjY2NyA1NDUuODI3LDY2LjY2N0w1MTIuNDkzLDY2LjY2N0M0MTguMjEzLDY2LjY2NyAzNzEuMDgsNjYuNjY3IDM0MS43OSw5NS45NTZDMzEyLjUsMTI1LjI0NSAzMTIuNSwxNzIuMzg2IDMxMi41LDI2Ni42NjdaIiBzdHlsZT0iZmlsbDp3aGl0ZTtmaWxsLXJ1bGU6bm9uemVybzsiLz4KICAgIDwvZz4KPC9zdmc+Cg==`;
        let svg_background_image = `PHN2ZyBjbGlwLXJ1bGU9J2V2ZW5vZGQnIGZpbGwtcnVsZT0nZXZlbm9kZCcgZmlsbC1vcGFjaXR5PScwLjInIHN0cm9rZS1saW5lY2FwPSdyb3VuZCcgc3Ryb2tlLWxpbmVqb2luPSdyb3VuZCcgc3Ryb2tlLW1pdGVybGltaXQ9JzEuNScgdmlld0JveD0nMCAwIDEzMCAzNicgeG1sbnM9J2h0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnJz48ZyB0cmFuc2Zvcm09J21hdHJpeCguMDAzMDYxMjIzNzggMCAwIC0uMDAzMDYxMjIzNzggLTIuNzc0OTQxMTQ0NDEgOS44MTAxMDM3MzA3NSknPjxwYXRoIGQ9J205NzA3LjcgMjE2OS40cy02MDkwLjE2IDkyLjc0LTcxODUuNTMtNy43MmMtOTguMzQtOS4wMi0xNjUuNjItMzYuODUtMjAwLjcyLTE1Ny4yNy0zNi43LTEyNS45My03MS42NS0xMzQ0Ljg1MS03MS42NS0yMTIxLjgxIDAtMTY0Mi45LjEtNTQ3Mi4yLjEtNjI4My4zIDAtNTIxLjgyIDQ3LjU1LTkzOC45MSA2Ny42NC0xMDU5LjI5IDEyLjktNzcuMzMgMTAzLjc2LTY5LjQxIDEwMy43Ni02OS40MWgyMTY1LjVjMTg0My42IDAgNDQ0OC43Ny04Ny44NiA0OTc4LjUxIDM4LjQxIDE2Mi4yMSAzOC42NyAxOTUuNjEgMTE5LjkyIDIxOC41NiAyNjkuOTIgODEuNDEgNTMyLjE1IDExNi4xMyA0MzgyLjI3IDExNi4xMyA2MjEzLjI3djI5OTcuNGMxLjc4IDEyMC4zOC02NS44MyAxNzMuNjEtMTkyLjMgMTc5Ljh6JyBmaWxsPScjMDBhODFjJy8+PHBhdGggZD0nbTI0MjEuMy03ODU2LjA3Yy04NC4yMy43Ni0zNzUuODYgNDIuMDEtNDI1Ljk3IDM0Mi4zLTIxLjExIDEyNi41LTcyLjEgNTY0LjczLTcyLjEgMTExMy4wNyAwIDgxMS4xLS4xIDQ2NDAuNC0uMSA2MjgzLjMgMCA4MTAuNTU4IDQ2LjQyIDIwODEuODMgODQuNzEgMjIxMy4yMSA0NC40MiAxNTIuNDMgMTE5LjgxIDIzOS4yNCAyMDQuNjIgMjk3LjgxIDc2LjM2IDUyLjczIDE2Ny43OSA4My4wOCAyNzkuODggOTMuMzYgMTEwMC42NyAxMDAuOTUgNzIyMC4zMyA5LjA1IDcyMjAuMzMgOS4wNSAzLjY3LS4wNiA3LjMzLS4xNyAxMC45OS0uMzUgMTU0LjItNy41NSAyNjcuMTMtNTkuMTEgMzQ2LjM0LTEzMC4xNCA5NC4xLTg0LjMyIDE1OC4yLTIwMy4zOCAxNTYuNy0zNzYuNzV2LTI5OTYuNTljMC0xODQ1LjU3LTM3LjktNTcyNi4yOC0xMTkuOS02MjYyLjY3LTI2LjktMTc1LjU0LTgwLjUtMjk0LjcyLTE3MS44Ni0zODQuNzQtNjYuMzEtNjUuMy0xNTYuMTctMTIwLjcxLTI5My44OC0xNTMuNTQtNTM3LjgyLTEyOC4yLTMxODIuNTgtNDcuMzItNTA1NC4yNi00Ny4zMmwtMjE1NS4xNi4yMmMtMy4zNy0uMS02LjgyLS4xNy0xMC4zNC0uMjJ2MzI2LjY3aDIxNjUuNWMxODQzLjYgMCA0NDQ4Ljc3LTg3Ljg2IDQ5NzguNTEgMzguNDEgMTYyLjIxIDM4LjY3IDE5NS42MSAxMTkuOTIgMjE4LjU2IDI2OS45MiA4MS40MSA1MzIuMTUgMTE2LjEzIDQzODIuMjcgMTE2LjEzIDYyMTMuMjd2Mjk5Ny40YzEuNzggMTIwLjM4LTY1LjgzIDE3My42MS0xOTIuMyAxNzkuOCAwIDAtNjA5MC4xNiA5Mi43NC03MTg1LjUzLTcuNzItOTguMzQtOS4wMi0xNjUuNjItMzYuODUtMjAwLjcyLTE1Ny4yNy0zNi43LTEyNS45My03MS42NS0xMzQ0Ljg1MS03MS42NS0yMTIxLjgxIDAtMTY0Mi45LjEtNTQ3Mi4yLjEtNjI4My4zIDAtMzM5LjU4IDIwLjE0LTYzNC44MSAzOS43NC04MzIuNzgtMjA2LjI1LTEyOS4yMyAxMzEuNjYtNjIyLjU5IDEzMS42Ni02MjIuNTl6JyBmaWxsPScjZmZmJy8+PC9nPjxnIHRyYW5zZm9ybT0nbWF0cml4KC0uMDAyOTg2Mjk3MjYgMCAwIC4wMDMwMTI3NzY4NSAzMi4xODY0MTM5NjMyNCAyMi44MzE3MTgyNDMzOSknPjxwYXRoIGQ9J20yOTYzLjQ2LTI0ODMuMTljLTE0NTktOTAzLTI4MzMuMDYtMTU1NS41MS0yODQ1LjY2LTE1NjguMDEtMTIuNS04LjQtMjAuOS0xNTAuNS0xNi43LTMxMy41IDQuMi01MTguNCAyNTkuMi05MTUuNSA3MzUuOC0xMTUzLjhsMjUwLjgtMTI1LjRzMTQ0MS43MiAxMTA1LjY1IDI3NDAuMDcgMTkwMy4wNmwyNjAyLjUzIDE0MDMuNzRzNzg2Ljk4IDE0MDIuNTkzIDY1NC42IDE1MjIuMDMyYy0xMTEuMyAxMDAuNDItMTMyMy41NC0zLjA1Ny0xNjI0LjUtMzMuODMyLTcuNC0uNzU2LTgzMC41OS02MDIuOTYtMjQ5Ni45NC0xNjM0LjI5eicgZmlsbD0nIzAwMzYxYycvPjxwYXRoIGQ9J20tODguODE5LTM3OTEuOGMyMC45ODUgMTUuNDEgNTMuMzE1IDM2LjUgOTguNjcyIDU5LjI3IDI0Mi42MjkgMTIxLjggMTQ3NS40MDcgNzI1Ljc3IDI3NzYuMjU3IDE1MzAuODggMTQ5MC4yNiA5MjIuMzUgMjMwMS45IDE0OTkuMjYgMjQ1NC42OCAxNjA1Ljk3MyAxMDYuMDIgNzQuMDU1IDE4MC42NiA3Ni40NzUgMTg1LjI1IDc2Ljk0NCAyNTAuMDYgMjUuNTcxIDExMDUuOTcgOTcuNzY3IDE0OTcuNDggNzIuOTAyIDIwNS4wOC0xMy4wMjQgMzM4LjM1LTgwLjA3MiAzODYuNzktMTIzLjc3OSA1Ny44NC01Mi4xODYgMTA5LjU0LTEzMC4wMTkgMTIxLjg2LTI0NS4yNTcgNy4wNS02NS45MTQtMy4zOS0xNzIuOTA5LTQ1LjE1LTMwNC4xODMtMTM5LjgxLTQzOS40NS02NjQuMDctMTM3OS4zOC02NjQuMDctMTM3OS4zOC0zMC44NS01NC45OC03Ni44NC0xMDAuMTUtMTMyLjU5LTEzMC4yMiAwIDAtMjU5NC4xLTEzOTkuMTktMjU5Ni4zOS0xNDAwLjU5LTEyODIuMjItNzg4LjY1LTI3MDEuMzYtMTg3Ny4xOC0yNzAxLjM2LTE4NzcuMTgtMTAyLjA5LTc4LjI5LTI0MC40OC05MS40NS0zNTUuNzI1LTMzLjgzbC0yNTAuOCAxMjUuNGMtNTk2LjQzOSAyOTguMjItOTEzLjY0NSA3OTYuMzMtOTE5LjgxMyAxNDQ0LjY0LTQuOTE2IDIwMC42MyAxNi4zODEgMzc2Ljk0IDMwLjY3IDQxOC44NSAzNC41MzQgMTAxLjMgOTUuNzE4IDE0OS44NyAxMzIuOTQ5IDE3NC44OXptMzA1Mi4yNzkgMTMwOC42MWMtMTQ1OS05MDMtMjgzMy4wNi0xNTU1LjUxLTI4NDUuNjYtMTU2OC4wMS0xMi41LTguNC0yMC45LTE1MC41LTE2LjctMzEzLjUgNC4yLTUxOC40IDI1OS4yLTkxNS41IDczNS44LTExNTMuOGwyNTAuOC0xMjUuNHMxNDQxLjcyIDExMDUuNjUgMjc0MC4wNyAxOTAzLjA2bDI2MDIuNTMgMTQwMy43NHM3ODYuOTggMTQwMi41OTMgNjU0LjYgMTUyMi4wMzJjLTExMS4zIDEwMC40Mi0xMzIzLjU0LTMuMDU3LTE2MjQuNS0zMy44MzItNy40LS43NTYtODMwLjU5LTYwMi45Ni0yNDk2Ljk0LTE2MzQuMjl6JyBmaWxsPScjZmZmJy8+PC9nPjxnIGZpbGw9J25vbmUnPjxwYXRoIGQ9J203LjAyNSA3Ljk3NmMwLS4xMDEuMDk0LS4xNzkuMTQxLS4yNjkuMTYxLS4zMS4zNy0uNjA5LjU1Mi0uOTA1LjAxOC0uMDMuMDk5LS4yMDcuMTU1LS4yNTQuMDMzLS4wMjkuMDk5LS4xMjkuMDk5LS4wODUgMCAuNjYzLjA4NSAxLjMxNi4wODUgMS45OHYuODQ4YzAgLjAzNC4wMDguMjQ0LjA4NS4xODQuNDk3LS4zODcuOTM0LTEuMDY4IDEuNDg1LTEuMzQzLjA1MS0uMDI2LjEwMS4wNTguMTQxLjA5OS4xMzEuMTMuMjczLjI2OC40NjcuMzExLjIyMi4wNDkuMjI3LS4yMi4zNjgtLjI1NS4yNzYtLjA2OS41Ni4xODMuODYzLjA4NS40NjctLjE1MS43NjQtLjQ1OSAxLjE0NS0uNzUuMDYyLS4wNDcuMjU3LS4yNDguMzgyLS4xOTguMTI2LjA1MS4xNTQuMTU4LjI0MS4yNDEuMjM1LjIyNC41Mi40NzkuNzc4LjY1LjEwNC4wNy4zNTYtLjI5Mi40MS0uMzM5LjMyNC0uMjguNzE0LS41MSAxLjEzMS0uNjA4LjA5My0uMDIyLjIwNC0uMDk1LjI4My0uMDQzLjExNC4wNzYuMTExLjI4OS4xNDEuNDExLjA1OC4yMzEuMjg0Ljg3NC42MjEuOTE2LjY5OS4wODggMS4xMzEtLjkwMiAxLjc3LTEuMDQ0LjMxNi0uMDcuNDcuNTgzLjc3OC42NTEuMTg2LjA0MS4zNTQtLjA5My41MzctLjExMy4zMTctLjAzNS42NDQuMDE0Ljk2Mi4wMTQnIHN0cm9rZT0nI2ZmZicgdHJhbnNmb3JtPSdtYXRyaXgoLjk5OTk5OTYgMCAwIC45OTk5OTk2IC4wMDAwMDA2MDQ0OCAtLjAwMDAwMDI3ODE3KScvPjxwYXRoIGQ9J202Ljc1NiAxNC45MjFjLjA3Ny0uMDUyLjAzOC0uMTUzLjA0Mi0uMjQxLjAxMi0uMjE3LjAyMi0uNDM0LjA0My0uNjUxLjAzNy0uNC4xMjktLjgyNC4zNTYtMS4xNjQuMDU3LS4wODYuMjA0LS4yNzkuMzIzLS4yOTIuNzU2LS4wODguNzU4Ljc3MiAxLjE1OSAxLjE3NC4xLjA5OS42NDEtLjE1OC43MDgtLjE3LjE5My0uMDM1LjI1MS4yNTguNDUyLjMyNS4zMjIuMTA4IDEuMDA3LjA2MSAxLjI4Ny0uMDE0LjIxNS0uMDU4LjUyNi0uMTY2LjY3OS0uMzM5LjA2Ni0uMDc1LjA3My0uMzEuMTU2LS4yNTUuMzk0LjI2MyAxLjIzMi41MzYgMS42MjYuMzM5JyBzdHJva2U9JyNmZmYnIHRyYW5zZm9ybT0nbWF0cml4KC45OTk5OTk2IDAgMCAuOTk5OTk5NiAuMDAwMDAwNjA0NDggLS4wMDAwMDAyNzgxNyknLz48cGF0aCBkPSdtMzIuOTk5OTg3NDA0NDggNS45OTk5OTczMjE4M2gxOS45OTk5OTJ2MTUuOTk5OTkzNmgtMTkuOTk5OTkyeicgc3Ryb2tlLXdpZHRoPScuOTk5OTk5NicvPjwvZz48ZyBmaWxsPScjMDBhODFjJyBmaWxsLXJ1bGU9J25vbnplcm8nIHRyYW5zZm9ybT0nbWF0cml4KDEuMzE0MzE2MTQwOTQgMCAwIC42MDgwMzMwOTAxMiAtNy4zNzIzNjQ3Nzk5MSA2LjQ0NzM2MzgwOTU1KSc+PHBhdGggZD0nbTM1LjI5IDM0LjU1MWMtLjM3NyAwLS42NTYtLjIxMi0uODM1LS42MzYtLjE3OS0uNDIzLS4yNjgtLjk4NS0uMjY4LTEuNjg1IDAtLjU0Mi4wMjctMS4wODIuMDgyLTEuNjE4LjA1NS0uNTM3LjEyLTEuMjA0LjE5Ni0yLjAwMi4wNzUtLjc5Ny4xNC0xLjg0LjE5NS0zLjEyOS4wNTUtMS4yOS4wODItMi45NS4wODItNC45ODIgMC0xLjEzMS0uMDE1LTIuMzA5LS4wNDUtMy41MzMtLjAzMS0xLjIyMy0uMDY0LTIuMzk0LS4xMDEtMy41MTMtLjAzNi0xLjExOC0uMDctMi4wOTItLjEtMi45Mi0uMDMxLS44MjktLjA0Ni0xLjQwMi0uMDQ2LTEuNzE3IDAtLjc5LjEwMy0xLjQ2MS4zMS0yLjAxNC4yMDctLjU1Mi41MTItLjgyOC45MTMtLjgyOC41MDkgMCAuOTg0LjMgMS40MjUuODk5LjQ0LjYuODUxIDEuNDE5IDEuMjMyIDIuNDU2cy43NCAyLjIwOSAxLjA3OCAzLjUxN2MuMzM3IDEuMzA3LjY1OCAyLjY2NS45NjQgNC4wNzMuMzA1IDEuNDA4LjYwMyAyLjc5NS44OTQgNC4xNi4yOTEgMS4zNjYuNTc3IDIuNjI3Ljg1OSAzLjc4Mi4yODEgMS4xNTUuNTY3IDIuMTI1Ljg1OCAyLjkwOXMuNTkgMS4zMDIuODk3IDEuNTU1bC0xLjI1MyAxLjI3OWMuMTYxLS4zMjEuMzEtLjY5OS40NDktMS4xMzNzLjI1MS0xLjAxMy4zMzYtMS43MzcuMTI4LTEuNjc1LjEyOC0yLjg1NGMwLTEuNTY4LS4wMjEtMy4wNzMtLjA2NC00LjUxNXMtLjA3Ni0yLjg0LS4xLTQuMTkyYy0uMDI1LTEuMzUzLS4wMi0yLjY3Mi4wMTItMy45NTkuMDMzLTEuMjg3LjEyMS0yLjU3NS4yNjUtMy44NjQuMTA1LS44NjguMjQxLTEuNDg4LjQwOS0xLjg1OXMuMzkzLS41NTcuNjc2LS41NTdjLjM3IDAgLjY1MS4yMTUuODQzLjY0My4xOTMuNDI5LjMxOC45NDIuMzc3IDEuNTQuMDU4LjU5Ny4wNTkgMS4xNy4wMDMgMS43MTctLjEyMiAxLjI2My0uMTk3IDIuNDY1LS4yMjYgMy42MDdzLS4wMzUgMi4yNzctLjAxOCAzLjQwMy4wMzkgMi4zMDcuMDY3IDMuNTQxLjA0MiAyLjU4OC4wNDIgNC4wNjFjMCAxLjk1My0uMDU0IDMuNjkxLS4xNjMgNS4yMTQtLjEwOCAxLjUyNC0uMzI4IDIuNzIxLS42NiAzLjU5Mi0uMzMzLjg3MS0uODMxIDEuMzA3LTEuNDk2IDEuMzA3LS40NzcgMC0uOTA1LS4yNjctMS4yODQtLjgwMS0uMzc4LS41MzUtLjcyNi0xLjI3Mi0xLjA0Mi0yLjIxMS0uMzE3LS45MzktLjYxNS0yLjAxNS0uODk1LTMuMjI5LS4yOC0xLjIxMy0uNTU3LTIuNTA2LS44MzEtMy44OC0uMjc0LTEuMzczLS41NjItMi43NjgtLjg2NC00LjE4NC0uMzAxLTEuNDE1LS42MjgtMi43ODQtLjk4LTQuMTA1cy0uNzQ4LTIuNTI5LTEuMTg5LTMuNjIzbC40Mi0uMjA2Yy4wNzggMS4yLjEzNCAyLjI2NS4xNyAzLjE5NC4wMzUuOTI5LjA2MSAxLjc5MS4wNzggMi41ODUuMDE3Ljc5NS4wMjYgMS41OTUuMDI2IDIuNCAwIDIuNDc0LS4wNTEgNC43MTgtLjE1MyA2LjczNC0uMTAzIDIuMDE2LS4yNiAzLjc5NS0uNDcyIDUuMzM2LS4wNy42MTEtLjIwNSAxLjA5NC0uNDA1IDEuNDQ5cy0uNDY1LjUzMy0uNzk2LjUzM3onLz48cGF0aCBkPSdtNTIuNTY4IDM0LjQ3MmMtMS4wMTMgMC0xLjkxMi0uNDQ5LTIuNjk5LTEuMzQ2LS43ODYtLjg5Ny0xLjQwNC0yLjEzOC0xLjg1Mi0zLjcyMnMtLjY3Mi0zLjM5NS0uNjcyLTUuNDMxYzAtMS4zODQuMTMxLTIuNjg4LjM5My0zLjkxMi4yNjItMS4yMjMuNi0yLjMwNSAxLjAxNS0zLjI0NC40MTUtLjk0Ljg2MS0xLjY3NiAxLjMzNy0yLjIwNy40NzYtLjUzMi45MjUtLjc5OCAxLjM0Ni0uNzk4LjE4NSAwIC4zNTUuMDYyLjUwOS4xODYuMTU1LjEyNC4yOC4zMTYuMzc1LjU3Ni4wOTQuMjYxLjE0Mi42MTUuMTQyIDEuMDYyIDAgLjgtLjA3NyAxLjUwOC0uMjMgMi4xMjQtLjE1NC42MTYtLjQ2NSAxLjExOC0uOTM1IDEuNTA3LS4zMDkuMzM3LS41NzkuNzQ1LS44MDkgMS4yMjRzLS40MSAxLjAyLS41NDEgMS42MjJjLS4xMy42MDMtLjE5NSAxLjI0MS0uMTk1IDEuOTE1IDAgMS43MTEuMjQ3IDMuMDg1Ljc0MSA0LjEyNS40OTUgMS4wMzkgMS4xNjEgMS41NTkgMS45OTggMS41NTkuNzA0IDAgMS4yNzUtLjUyOSAxLjcxMy0xLjU4N3MuNjU3LTIuNDYzLjY1Ny00LjIxNWMwLTEuNTU4LS4xODctMi43OTQtLjU2Mi0zLjcwN3MtLjg3NC0xLjM3LTEuNDk3LTEuMzdjLS4zMTkgMC0uNTguMTU4LS43ODIuNDc0cy0uNDI1LjczNy0uNjY4IDEuMjYzYy0uMTguMzg5LS4zMjkuNzE3LS40NDguOTgzLS4xMTguMjY2LS4yMzYuNDY2LS4zNTYuNi0uMTE5LjEzNC0uMjY5LjIwMS0uNDQ5LjIwMS0uMjAyIDAtLjM3NS0uMTU5LS41Mi0uNDc3LS4xNDUtLjMxOS0uMjE4LS45MTItLjIxOC0xLjc4MSAwLS42OTkuMDkzLTEuNDExLjI3OC0yLjEzNS4xODUtLjcyMy40MzEtMS4zODQuNzM4LTEuOTgxLjMwNi0uNTk4LjY1LTEuMDgxIDEuMDMtMS40NDkuMzc5LS4zNjkuNzY2LS41NTMgMS4xNjEtLjU1My45NzkgMCAxLjgxNy40MDIgMi41MTQgMS4yMDQuNjk4LjgwMyAxLjIzMiAxLjkzOCAxLjYwNCAzLjQwNy4zNzEgMS40NjguNTU2IDMuMjEuNTU2IDUuMjI2IDAgMi4wMTUtLjIwNSAzLjgyOC0uNjE3IDUuNDM5LS40MTEgMS42MS0uOTY3IDIuODgzLTEuNjY5IDMuODE3LS43MDEuOTM0LTEuNDk3IDEuNDAxLTIuMzg4IDEuNDAxeicvPjxwYXRoIGQ9J202Mi41NTYgMzQuNTExYy0xLjAyNyAwLTEuODUyLS40OTQtMi40NzYtMS40ODQtLjYyMy0uOTg5LTEuMDcyLTIuMzc5LTEuMzQ2LTQuMTY4LS4yNzMtMS43ODktLjQxLTMuODY1LS40MS02LjIyOCAwLTEuMzUzLjAzMS0yLjU2NC4wOTMtMy42MzJzLjEyNy0yLjA5LjE5NS0zLjA2N2MuMDY4LS45NzYuMTE1LTIuMDAxLjEzOS0zLjA3NC4wMjktMS4xNDguMDMzLTIuMDc5LjAxMS0yLjc5NXMtLjAyNi0xLjM5NS0uMDExLTIuMDM3Yy4wMS0uNTU4LjA5NS0xLjA1My4yNTctMS40ODQuMTYyLS40MzIuNDI1LS42NDcuNzg4LS42NDcuNTAxIDAgLjg3LjQ3NiAxLjEwNiAxLjQyOS4yMzYuOTUyLjMzIDIuNTA4LjI4MSA0LjY2NS0uMDI5Ljg1OC0uMDY5IDEuNjc2LS4xMiAyLjQ1NS0uMDUyLjc3OS0uMTA0IDEuNTY3LS4xNTcgMi4zNjUtLjA1NC43OTctLjEgMS42NDktLjEzOSAyLjU1N3MtLjA1OSAxLjkyOC0uMDU5IDMuMDU5YzAgMS43MzcuMDc5IDMuMTE4LjIzOCA0LjE0NS4xNTggMS4wMjYuMzkzIDEuNzU4LjcwNiAyLjE5NC4zMTMuNDM3LjcwMi42NTYgMS4xNjcuNjU2LjI5IDAgLjUyLS4wNjIuNjkxLS4xODYuMTctLjEyMy4zMi0uMjQ0LjQ0OS0uMzYzLjEyOS0uMTE4LjI3Ni0uMTc3LjQ0Mi0uMTc3LjI1IDAgLjQ0Mi4xNjMuNTczLjQ4OS4xMzIuMzI2LjE5Ny44MTYuMTk3IDEuNDY4IDAgMS4wNTMtLjIwOSAxLjk1OS0uNjI4IDIuNzItLjQxOS43Ni0xLjA4MSAxLjE0LTEuOTg3IDEuMTR6bS0yLjA1Mi0xNC4zNjdjLS40NDMuNDA1LS43ODYuNDAxLTEuMDI4LS4wMTJzLS4zNjQtMS4wMDktLjM2NC0xLjc4OGMwLS42OTUuMDY2LTEuMzE4LjE5OC0xLjg3MS4xMzEtLjU1My40Ni0xLjAzNy45ODYtMS40NTIuNDc3LS4zODUgMS4wMS0uNjkzIDEuNTk3LS45MjQuNTg4LS4yMzIgMS4xNDQtLjM0OCAxLjY2OC0uMzQ4LjU4OSAwIDEuMDA3LjI0OSAxLjI1NC43NDYuMjQ3LjQ5OC4zNzEgMS4xMzYuMzcxIDEuOTE1IDAgLjU4OS0uMDg0IDEuMDQyLS4yNSAxLjM1OC0uMTY3LjMxNS0uMzk1LjUtLjY4NS41NTItLjUzNi4wNzktMS4wMS4xOTItMS40MjMuMzQtLjQxMi4xNDctLjguMzM5LTEuMTYzLjU3NnMtLjc1LjUzOS0xLjE2MS45MDh6Jy8+PHBhdGggZD0nbTY5LjQwOCAzNC4zOTNjLTEuMDE4IDAtMS44NS0uMzc1LTIuNDk2LTEuMTI1LS42NDctLjc1LTEuMTIzLTEuNzc2LTEuNDI4LTMuMDc5LS4zMDYtMS4zMDItLjQ1OS0yLjc5Ni0uNDU5LTQuNDggMC0xLjM5NC4xMjEtMi43OTYuMzYyLTQuMjAzLjI0MS0xLjQwOC41ODctMi42OTggMS4wMzctMy44NjkuNDUxLTEuMTcxLjk5Mi0yLjExMSAxLjYyNS0yLjgyMi42MzMtLjcxIDEuMzQyLTEuMDY2IDIuMTI2LTEuMDY2LjY1MiAwIDEuMjI0LjI0MyAxLjcxNC43MjcuNDkxLjQ4NC44NzUgMS4yMDUgMS4xNTMgMi4xNjMuMjc3Ljk1Ny40MTYgMi4xNTIuNDE2IDMuNTg0IDAgMS40NTgtLjIwOCAyLjY3MS0uNjIzIDMuNjM5cy0uOTcyIDEuNjk1LTEuNjcxIDIuMTc5Yy0uNjk4LjQ4NC0xLjQ3NC43MjYtMi4zMjYuNzI2LS42ODIgMC0xLjE4OS0uMjQ1LTEuNTIxLS43MzQtLjMzMi0uNDktLjQ5OS0xLjA1LS40OTktMS42ODIgMC0uMzUyLjAzOS0uNjE2LjExNy0uNzkzLjA3OC0uMTc2LjIxLS4yNjQuMzk1LS4yNjQuMTM0IDAgLjI5NS4wNC40ODQuMTIyLjE4OC4wODIuNDUxLjEyMi43ODcuMTIyLjQ3MiAwIC45MDUtLjEzMyAxLjMtLjM5OC4zOTQtLjI2Ni43MTQtLjYzNi45NTktMS4xMS4yNDQtLjQ3My4zNjctMS4wMS4zNjctMS42MSAwLS42ODQtLjA4LTEuMTk3LS4yNC0xLjUzOS0uMTU5LS4zNDItLjQyNi0uNTEzLS44MDEtLjUxMy0uNDEyIDAtLjc4OS4yMjMtMS4xMzMuNjcxLS4zNDMuNDQ3LS42NDEgMS4wNDgtLjg5NCAxLjgwNC0uMjU0Ljc1NS0uNDQ5IDEuNjEzLS41ODcgMi41NzMtLjEzNy45NjEtLjIwNiAxLjk1NC0uMjA2IDIuOTggMCAuNzIxLjA1OSAxLjM1NC4xNzcgMS44OTkuMTE5LjU0NS4zMTUuOTY2LjU5IDEuMjYzcy42NDUuNDQ2IDEuMTEuNDQ2Yy42ODkgMCAxLjI2Ny0uMTUgMS43MzItLjQ1LjQ2NC0uMy44NTMtLjY0NiAxLjE2NC0xLjAzOC4zMTItLjM5Mi41ODEtLjczOC44MDYtMS4wMzhzLjQ0Mi0uNDUuNjUyLS40NWMuMjE0IDAgLjM2OS4xNDMuNDY1LjQzcy4xNDQuNzIyLjE0NCAxLjMwN2MwIC42MzEtLjEyIDEuMjgxLS4zNjEgMS45NS0uMjQxLjY2OC0uNTc3IDEuMjc2LTEuMDA4IDEuODIzcy0uOTM4Ljk5My0xLjUyMSAxLjMzOC0xLjIxOS41MTctMS45MDguNTE3eicvPjxwYXRoIGQ9J203Ny4xOSAzNC4zN2MtLjQzMyAwLS43NzYtLjMzOS0xLjAzLTEuMDE1LS4yNTMtLjY3Ni0uMzc5LTEuNjkxLS4zNzktMy4wNDMgMC0xLjIwNS0uMDE0LTIuMjcxLS4wNDItMy4xOTctLjAyOC0uOTI3LS4wNjEtMS43OC0uMDk5LTIuNTYycy0uMDc3LTEuNTUtLjExOS0yLjMwNWMtLjA0MS0uNzU2LS4wNzYtMS41NTMtLjEwNC0yLjM5Mi0uMDI4LS44NC0uMDQyLTEuNzgzLS4wNDItMi44MyAwLTIuMDIxLjEyOS0zLjgzLjM4NS01LjQyOC4yNTctMS41OTcuNjEtMi45NjMgMS4wNi00LjA5Ny40NDktMS4xMzQuOTU4LTIuMDAxIDEuNTI2LTIuNjAxLjU2OS0uNiAxLjE2MS0uOSAxLjc3Ny0uOS4zMjkgMCAuNjMuMTkyLjkwNi41NzYuMjc1LjM4NS40MTIgMS4wMjEuNDEyIDEuOTExIDAgLjUyNi0uMDYzLjk2Ny0uMTkgMS4zMjItLjEyNi4zNTUtLjMxNy42MTctLjU3My43ODUtLjYzNS4zOS0xLjE0OS44NTctMS41NDEgMS40MDItLjM5Mi41NDQtLjY4OCAxLjIwMy0uODg4IDEuOTc3LS4xOTkuNzc0LS4zMzQgMS42OTItLjQwNSAyLjc1Ni0uMDcxIDEuMDYzLS4xMDYgMi4zMjMtLjEwNiAzLjc4IDAgLjg1My4wMjMgMS43MTQuMDY4IDIuNTgyLjA0NS44NjkuMDk5IDEuNzkzLjE2MiAyLjc3NS4wNjMuOTgxLjExOCAyLjA2NC4xNjMgMy4yNDhzLjA2NyAyLjUwNS4wNjcgMy45NjNjMCAxLjMwNS0uMDgxIDIuMTgtLjI0NCAyLjYyNS0uMTY0LjQ0NS0uNDE4LjY2OC0uNzY0LjY2OHptLS4yMDEtMTQuNDk0Yy0uMjgyIDAtLjUxNy0uMTY0LS43MDQtLjQ5LS4xODgtLjMyNy0uMjgyLS43NDgtLjI4Mi0xLjI2MyAwLS43MzcuMDkyLTEuNDA1LjI3Ni0yLjAwNXMuNDUyLTEuMDI0LjgwNS0xLjI3MWMuNDYzLS4zLjktLjUyIDEuMzEyLS42NTkuNDExLS4xNC44MS0uMjEgMS4xOTctLjIxLjI5MiAwIC41NDEuMTEyLjc0Ny4zMzYuMjA2LjIyMy4zNjIuNTM5LjQ2OC45NDdzLjE1OS44OTYuMTU5IDEuNDY1YzAgLjQ2My0uMDYzLjg2My0uMTg4IDEuMTk5LS4xMjYuMzM3LS4zMjUuNTIxLS41OTguNTUzLS41NC4wNjMtLjk4Ny4xNzEtMS4zNC4zMjQtLjM1My4xNTItLjY0MS4zMDktLjg2NC40NjktLjIyMi4xNjEtLjQwOS4zMDItLjU2LjQyM3MtLjI5NC4xODItLjQyOC4xODJ6Jy8+PHBhdGggZD0nbTg2LjAyMSAzNC40NzJjLTEuMDEzIDAtMS45MTItLjQ0OS0yLjY5OS0xLjM0Ni0uNzg2LS44OTctMS40MDMtMi4xMzgtMS44NTEtMy43MjJzLS42NzMtMy4zOTUtLjY3My01LjQzMWMwLTEuMzg0LjEzMS0yLjY4OC4zOTMtMy45MTIuMjYyLTEuMjIzLjYtMi4zMDUgMS4wMTUtMy4yNDQuNDE2LS45NC44NjEtMS42NzYgMS4zMzctMi4yMDcuNDc2LS41MzIuOTI1LS43OTggMS4zNDYtLjc5OC4xODUgMCAuMzU1LjA2Mi41MDkuMTg2LjE1NS4xMjQuMjguMzE2LjM3NS41NzYuMDk1LjI2MS4xNDIuNjE1LjE0MiAxLjA2MiAwIC44LS4wNzcgMS41MDgtLjIzIDIuMTI0cy0uNDY1IDEuMTE4LS45MzUgMS41MDdjLS4zMDkuMzM3LS41NzkuNzQ1LS44MDkgMS4yMjRzLS40MSAxLjAyLS41NCAxLjYyMmMtLjEzMS42MDMtLjE5NiAxLjI0MS0uMTk2IDEuOTE1IDAgMS43MTEuMjQ3IDMuMDg1Ljc0MiA0LjEyNS40OTQgMS4wMzkgMS4xNiAxLjU1OSAxLjk5OCAxLjU1OS43MDMgMCAxLjI3NC0uNTI5IDEuNzEyLTEuNTg3LjQzOS0xLjA1OC42NTgtMi40NjMuNjU4LTQuMjE1IDAtMS41NTgtLjE4OC0yLjc5NC0uNTYzLTMuNzA3cy0uODc0LTEuMzctMS40OTctMS4zN2MtLjMxOSAwLS41OC4xNTgtLjc4Mi40NzRzLS40MjUuNzM3LS42NjggMS4yNjNjLS4xOC4zODktLjMyOS43MTctLjQ0Ny45ODMtLjExOS4yNjYtLjIzNy40NjYtLjM1Ny42LS4xMTkuMTM0LS4yNjguMjAxLS40NDkuMjAxLS4yMDIgMC0uMzc1LS4xNTktLjUyLS40NzctLjE0NS0uMzE5LS4yMTctLjkxMi0uMjE3LTEuNzgxIDAtLjY5OS4wOTItMS40MTEuMjc3LTIuMTM1LjE4NS0uNzIzLjQzMS0xLjM4NC43MzgtMS45ODEuMzA3LS41OTguNjUtMS4wODEgMS4wMy0xLjQ0OS4zOC0uMzY5Ljc2Ny0uNTUzIDEuMTYxLS41NTMuOTc5IDAgMS44MTcuNDAyIDIuNTE1IDEuMjA0LjY5Ny44MDMgMS4yMzEgMS45MzggMS42MDMgMy40MDcuMzcxIDEuNDY4LjU1NyAzLjIxLjU1NyA1LjIyNiAwIDIuMDE1LS4yMDYgMy44MjgtLjYxNyA1LjQzOS0uNDEyIDEuNjEtLjk2OCAyLjg4My0xLjY2OSAzLjgxNy0uNzAyLjkzNC0xLjQ5OCAxLjQwMS0yLjM4OSAxLjQwMXonLz48cGF0aCBkPSdtOTIuMzA2IDM0LjYwNmMtLjIyOSAwLS40Mi0uMTMxLS41NzUtLjM5NHMtLjI0Ni0uNjcxLS4yNzYtMS4yMjRjLS4wMjktLjU1Mi4wMjctMS4yNTUuMTY5LTIuMTA4LjE0Ni0uOTQ3LjM4Ny0yLjA1NS43MjQtMy4zMjMuMzM4LTEuMjY4Ljc1Ny0yLjYwNSAxLjI1OS00LjAxMS41MDEtMS40MDUgMS4wNjgtMi43OTIgMS43MDEtNC4xNnMxLjMxMy0yLjYxOCAyLjAzOC0zLjc1Yy4zMDctLjUwNS41NzgtLjg5Ni44MTUtMS4xNzIuMjM2LS4yNzcuNDQ5LS40Ny42MzktLjU4LjE5LS4xMTEuMzYzLS4xNjYuNTE4LS4xNjYuMjg4IDAgLjUyOS4yMTEuNzI1LjYzMnMuMjk0IDEuMDMxLjI5NCAxLjgzMWMwIC41NTItLjA2NCAxLjAyMi0uMTkzIDEuNDA5cy0uMzExLjczMy0uNTQ0IDEuMDM4Yy0uODI2IDEuMDYzLTEuNTk3IDIuMzM3LTIuMzEyIDMuODIxLS43MTYgMS40ODQtMS4zNzYgMy4wOTYtMS45OCA0LjgzNS0uNjA0IDEuNzQtMS4xNiAzLjUxNC0xLjY2OSA1LjMyNS0uMjA5LjcyNi0uNDIzIDEuMjQxLS42NDEgMS41NDQtLjIxOC4zMDItLjQ0OC40NTMtLjY5Mi40NTN6bTcuMDA5LS4wNTVjLS4yNjMgMC0uNTA5LS4xNDUtLjczNi0uNDM0LS4yMjgtLjI5LS40Ny0uODQ1LS43MjUtMS42NjYtLjM0My0xLjA4OS0uNzQxLTIuMjQ3LTEuMTk0LTMuNDczLS40NTMtMS4yMjctLjkzLTIuNDM4LTEuNDMyLTMuNjM2LS41MDItMS4xOTctLjk5OS0yLjMyMy0xLjQ5NC0zLjM3OC0uNDk0LTEuMDU2LS45NTgtMS45NjItMS4zOTEtMi43Mi0uMy0uNTI2LS41MzYtMS4wMTYtLjcwOS0xLjQ2OC0uMTczLS40NTMtLjI1OS0xLjAxNi0uMjU5LTEuNjkgMC0uNjYzLjA4MS0xLjIyMS4yNDMtMS42NzQuMTYyLS40NTIuMzk5LS42NzkuNzEtLjY3OS4yNDQgMCAuNDcxLjEyMy42ODMuMzY4cy40OTcuNjg4Ljg1NSAxLjMzYy4yNTEuNDUyLjU2OCAxLjA4OS45NTEgMS45MS4zODQuODIxLjc5NSAxLjc0MiAxLjIzNSAyLjc2My40MzkgMS4wMjEuODc1IDIuMDY3IDEuMzA5IDMuMTM4LjQzMyAxLjA3MS44MzYgMi4wODYgMS4yMDcgMy4wNDQuMzcxLjk1Ny42NyAxLjc4Ni44OTYgMi40ODYuMzY4IDEuMTIxLjYwNCAxLjk3NS43MDkgMi41NjJzLjE1NyAxLjA2NC4xNTcgMS40MzNjMCAuNTY4LS4wOTEgMS4wMDgtLjI3MiAxLjMxOC0uMTgyLjMxMS0uNDI5LjQ2Ni0uNzQzLjQ2NnonLz48L2c+PC9zdmc+`;

        let font_family = "Shantell Sans";
        let visibility_immersive = "visible";

        let primary_color = "#fffd7d";
        let secondary_color = "#ff6200";
        let on_primary_color = "#111111";
        let on_secondary_color = "#ffffff";

        return `
            @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Source+Code+Pro:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap&family=Shantell+Sans:ital,wght@0,300..800;1,300..800&display=swap');

            #sticky-notes-notefox--website-test {
                position: fixed;
                top: 10px;
                left:  10px;
                width: 300px;
                height:  300px;
                background-color: ${primary_color};
                opacity: ${opacity};
                z-index: 99999999999;
                padding: 15px !important;
                margin: 0px !important;
                box-sizing: border-box !important;
                border-radius: 10px;
                border-bottom-right-radius: 0px;
                cursor: default;
                box-shadow: 0px 0px 5px rgba(255,98,0,0.27);
                font-family: inherit;
                color: ${on_primary_color};
                font-size: 17px;
                background-image: url('data:image/svg+xml;base64,${svg_background_image}');
                background-position: left 50% bottom 10px;
                background-repeat: no-repeat;
                background-size: 50% auto;
            }
            #sticky-notes-notefox--website-test * {
                min-width: 0px;
                min-height: 0px;
                line-height: normal;
            }
            #move--sticky-notes-notefox--website-test, #page-or-domain--sticky-notes-notefox--website-test {
                position: absolute;
                top: 0px;
                left: 40%;
                right: 40%;
                width: auto;
                height: 20px;
                background-color: ${secondary_color};
                opacity: 1;
                cursor: grab;
                border-radius: 0px 0px 10px 10px;
                z-index: 4;
                font-weight: bold !important;
                font-family: 'Open Sans', sans-serif;
                padding: 2px 5px !important;
                font-size: 10px !important;
                border: 0px solid transparent;
                color: ${on_secondary_color};
                margin: 0px !important;
                text-align: center;
                box-sizing: border-box !important;
            }
            #move--sticky-notes-notefox--website-test {
                opacity: 0;
                z-index: 5;
            }
            #move--sticky-notes-notefox--website-test:hover, #move--sticky-notes-notefox--website-test:active {
                /*opacity: 1;*/
            }
            #move--sticky-notes-notefox--website-test:active {
                cursor: grabbing;
                z-index: 6;
            }
            #resize--sticky-notes-notefox--website-test {
                position: absolute;
                right: 0px;
                bottom: 0px;
                width: 10px;
                height: 10px;
                background-color: transparent;
                opacity: 1;
                cursor: nwse-resize;
                z-index: 2;
                margin: 0px !important;
                padding: 0px !important;
                box-sizing: border-box !important;
                border-right-color: ${secondary_color};
                border-width: 0px !important;
            }
            #resize--sticky-notes-notefox--website-test:active, #resize--sticky-notes-notefox--website-test:focus{
                cursor: nwse-resize;
            }
            #resize--sticky-notes-notefox--website-test:before{
                cursor: nwse-resize;
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                border-top: 10px solid transparent;
                border-right-width: 10px;
                border-right-style: solid;
                border-right-color: inherit;
                width: 0;
            }
            #text--sticky-notes-notefox--website-test {
                scrollbar-color: ${secondary_color} transparent;
                scrollbar-width: thin;
            }
            #text--sticky-notes-notefox--website-test, #text-container--sticky-notes-notefox--website-test {
                position: relative;
                top: 0px;
                bottom: 0px;
                width: 100%;
                height: 100%;
                padding: 10px !important;
                margin: 0px !important;
                box-sizing: border-box !important;
                background-color: transparent;
                color: ${on_primary_color};
                opacity: 1;
                cursor: text;
                z-index: 1;
                border: 0px solid transparent !important;
                border-radius: 10px;
                overflow: auto;
                resize: none;
                transition: 0.2s;
                font-family: inherit;
                font-size: 14px !important;
            }
            #text--sticky-notes-notefox--website-test * {
                white-space: inherit;
                padding: 0px;
                margin: 0px;
                background-color: transparent;
                color: inherit;
                border: 0px solid transparent;
                font-size: inherit;
                text-decoration-thickness: 2px;
            }
            #text--sticky-notes-notefox--website-test b, #text--sticky-notes-notefox--website-test strong {
                font-family: inherit;
                font-weight: bolder;
            }

            #text--sticky-notes-notefox--website-test i, #text--sticky-notes-notefox--website-test em, #text--sticky-notes-notefox--website-test cite, #text--sticky-notes-notefox--website-test q, #text--sticky-notes-notefox--website-test blockquote {
                font-style: italic;
            }

            #text--sticky-notes-notefox--website-test code, #text--sticky-notes-notefox--website-test pre {
                font-family: 'Source Code Pro', monospace;
            }

            #text--sticky-notes-notefox--website-test h1 {
                font-family: inherit;
                font-weight: bolder;
                font-size: 2em !important;
            }

            #text--sticky-notes-notefox--website-test h2 {
                font-family: inherit;
                font-weight: bolder;
                font-size: 1.7em !important;
            }

            #text--sticky-notes-notefox--website-test h3 {
                font-family: inherit;
                font-weight: bolder;
                font-size: 1.4em !important;
            }

            #text--sticky-notes-notefox--website-test h4 {
                font-family: inherit;
                font-weight: bolder;
                font-size: 1.1em !important;
            }

            #text--sticky-notes-notefox--website-test h5 {
                font-family: inherit;
                font-weight: bolder;
                font-size: 0.85em !important;
            }

            #text--sticky-notes-notefox--website-test h6 {
                font-family: inherit;
                font-weight: bolder;
                font-size: 0.7em !important;
            }

            #text--sticky-notes-notefox--website-test big {
                font-size: 1.5em !important;
            }

            #text--sticky-notes-notefox--website-test small, #text--sticky-notes-notefox--website-test sup, #text--sticky-notes-notefox--website-test sub {
                font-size: 0.7em !important;
            }

            #text--sticky-notes-notefox--website-test img {
                border-radius: 5px;
                width: auto;
                max-width: 100%;
                height: auto;
            }

            #text--sticky-notes-notefox--website-test a {
                text-decoration: underline;
                text-decoration-style: dotted;
                color: inherit;
                text-decoration-thickness: 2px;
            }

            #text-container--sticky-notes-notefox--website-test {
                position: absolute;
                left: 0px;
                right: 0px;
                top: 20px;
                bottom: 20px;
                width: auto;
                height: auto;
                padding: 0px !important;
                overflow: visible;
            }
            #text--sticky-notes-notefox--website-test:focus {
                outline: none;
                box-shadow: 0px 0px 0px 3px ${secondary_color} inset;
            }

            #text--sticky-notes-notefox--website-test, #text--sticky-notes-notefox--website-test * {
                font-family: '${font_family}', sans-serif;
            }

            #close--sticky-notes-notefox--website-test, #minimize--sticky-notes-notefox--website-test {
                position: absolute;
                top: 0px ;
                right: 0px;
                width: 30px;
                height: 30px;
                background-image: url('data:image/svg+xml;base64,${svg_image_close}');
                background-size: auto 70%;
                background-repeat: no-repeat;
                background-position: center center;
                background-color: ${secondary_color};
                border: 0px solid transparent;
                color: ${on_secondary_color};
                z-index: 5;
                border-radius: 10px;
                cursor: pointer;
                margin: 0px !important;
                padding: 0px !important;
                box-sizing: border-box !important;
                font-size: 8px;
            }
            #close--sticky-notes-notefox--website-test:active, #close--sticky-notes-notefox--website-test:focus, #minimize--sticky-notes-notefox--website-test:active, #minimize--sticky-notes-notefox--website-test:focus {
                box-shadow: 0px 0px 0px 5px ${on_secondary_color};
                z-index: 6;
                transition: 0.5s;
            }
            #minimize--sticky-notes-notefox--website-test {
                left: 0px;
                right: auto;
                background-image: url('data:image/svg+xml;base64,${svg_image_minimize}');
            }

            #slider-container--sticky-notes-notefox--website-test {
                position: absolute;
                z-index: 2;
                width: auto !important;
                left: 8px !important;
                right: 8px !important;
                bottom: 7px !important;
                margin: 0px !important;
                padding: 0px !important;
                box-sizing: border-box !important;
                background-color: transparent;
            }

            #slider--sticky-notes-notefox--website-test {
                width: 100%;
                height: 5px;
                background: linear-gradient(to right, ${secondary_color} 0%, ${secondary_color} ${opacity * 100}%, #eeeeee ${opacity * 100}%, #eeeeee 100%);
                border: 0px solid ${secondary_color};
                outline: none;
                opacity: 0.7;
                transition: opacity .2s;
                cursor: pointer;
                border-radius: 10px;
                margin: 0px !important;
                padding: 0px !important;
                box-sizing: border-box !important;
            }

            #slider--sticky-notes-notefox--website-test:active {
                background: inherit;
                cursor: grabbing;
            }

            #slider--sticky-notes-notefox--website-test:hover {
                opacity: 1;
            }

            #slider--sticky-notes-notefox--website-test::-moz-range-thumb {
                width: 15px;
                height: 15px;
                background-color: ${secondary_color};
                cursor: grab;
                border: 0px solid #eeeeee;
                border-radius: 100%;
                margin: 0px;
            }
            #slider--sticky-notes-notefox--website-test::-moz-range-thumb:active {
                cursor: grabbing;
                box-shadow: 0px 0px 0px 4px ${secondary_color};
                transition: 0.5s;
            }
            #tag--sticky-notes-notefox--website-test {
                position: absolute;
                top: 3px;
                left: 30%;
                right: 30%;
                width: auto;
                height: 8px;
                opacity: 1;
                cursor: default;
                border-radius: 15px;
                z-index: 2;
            }

            #commands-container--sticky-notes-notefox--website-test {
                visibility: ${visibility_immersive};
                width: auto !important;
                height: auto !important;
                box-sizing: border-box;
                position: absolute;
                top: 0px;
                bottom: 0px;
                left: 0px;
                right: 0px;
            }

            #commands-container--sticky-notes-notefox--website-test:hover * {
                visibility: visible !important;
            }

            #text--sticky-notes-notefox--website-test {
                visibility: visible !important;
            }
            `;
    }

    /**
     *
     * @param type 0: close totally, 1: minimised
     */
    function onClickClose(minimized = false) {
        document.getElementById("sticky-notes-notefox--website-test").remove();
    }

    function onInputText(text) {
        listenerLinks(text);
    }

    function onKeyDownText(text, e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "b") {
            bold();
        } else if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "i") {
            italic();
        } else if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "u") {
            underline();
        } else if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "s") {
            strikethrough();
        } else if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "l") {
            insertLink();
        }
    }

    function onPasteText(text, e) {
        if (((e.originalEvent || e).clipboardData).getData("text/html") !== "") {
            e.preventDefault(); // Prevent the default paste action
            let clipboardData = (e.originalEvent || e).clipboardData;
            let pastedText = clipboardData.getData("text/html");
            let sanitizedHTML = sanitizeHTML(pastedText)
            document.execCommand("insertHTML", false, sanitizedHTML);
        }
    }

    /**
     * Make "movable" the sticky-notes
     */
    function onMouseDownMove(e, stickyNote, isDragging) {
        isDragging = true;
        const offsetX = e.clientX - stickyNote.getBoundingClientRect().left;
        const offsetY = e.clientY - stickyNote.getBoundingClientRect().top;
        const screenWidth = window.screen.width;
        const screenHeight = window.screen.height;

        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);

        function onMouseMove(e) {
            if (!isDragging) return;

            stickyNote.style.left = e.clientX - offsetX + 'px';
            stickyNote.style.top = e.clientY - offsetY + 'px';

            if (stickyNote.style.left.replace("px", "") < 0) stickyNote.style.left = "0px";
            if (stickyNote.style.top.replace("px", "") < 0) stickyNote.style.top = "0px";

            if (stickyNote.style.left.replace("px", "") > (screenWidth - stickyNote.offsetWidth)) stickyNote.style.left = (screenWidth - stickyNote.offsetWidth) + "px";
            if (stickyNote.style.top.replace("px", "") > (screenHeight - stickyNote.offsetHeight)) stickyNote.style.top = (screenHeight - stickyNote.offsetHeight) + "px";
        }

        function onMouseUp() {
            isDragging = false;
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);

            if (stickyNote.style.left.replace("px", "") < 0) stickyNote.style.left = "0px";
            if (stickyNote.style.top.replace("px", "") < 0) stickyNote.style.top = "0px";

            if (stickyNote.style.left.replace("px", "") > (screenWidth - stickyNote.offsetWidth)) stickyNote.style.left = (screenWidth - stickyNote.offsetWidth) + "px";
            if (stickyNote.style.top.replace("px", "") > (screenHeight - stickyNote.offsetHeight)) stickyNote.style.top = (screenHeight - stickyNote.offsetHeight) + "px";
        }

        return isDragging;
    }

    /**
     * Make "resizable" the sticky-notes
     */
    function onMouseDownResize(e, stickyNote, isResizing) {
        isResizing = true;
        const initialWidth = stickyNote.offsetWidth;
        const initialHeight = stickyNote.offsetHeight;
        const screenWidth = window.screen.width;
        const screenHeight = window.screen.height;
        const startX = e.clientX;
        const startY = e.clientY;

        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);

        function onMouseMove(e) {
            if (!isResizing) return;

            const deltaX = e.clientX - startX;
            const deltaY = e.clientY - startY;

            stickyNote.style.width = initialWidth + deltaX + 'px';
            stickyNote.style.height = initialHeight + deltaY + 'px';

            if (stickyNote.style.width.replace("px", "") < 200) stickyNote.style.width = "200px";
            if (stickyNote.style.height.replace("px", "") < 200) stickyNote.style.height = "200px";

            if (stickyNote.style.width.replace("px", "") > (screenWidth / 2)) stickyNote.style.width = (screenWidth / 2) + "px";
            if (stickyNote.style.height.replace("px", "") > (screenHeight / 2)) stickyNote.style.height = (screenHeight / 2) + "px";
        }

        function onMouseUp() {
            isResizing = false;
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);

            if (stickyNote.style.width.replace("px", "") < 200) stickyNote.style.width = "200px";
            if (stickyNote.style.height.replace("px", "") < 200) stickyNote.style.height = "200px";

            if (stickyNote.style.width.replace("px", "") > (screenWidth / 2)) stickyNote.style.width = (screenWidth / 2) + "px";
            if (stickyNote.style.height.replace("px", "") > (screenHeight / 2)) stickyNote.style.height = (screenHeight / 2) + "px";
        }

        return isResizing;
    }

    /**
     * Check correctness of the number and return a string: number+"px" (o otherwise)
     * @param number number to check
     * @param otherwise if the "number" is wrong; this is the "default" value
     * @returns {*} return a string: number+"px"
     */
    function checkCorrectNumber(number, otherwise) {
        let temp = number;
        if (parseInt(temp.toString().replace("px", "")) + "px" !== number) {
            temp = otherwise;
        }
        return temp;
    }

    function getInteger(number) {
        return parseInt(number.toString().replace("px", ""));
    }

    function bold() {
        //console.log("Bold B")
        document.execCommand("bold", false);
    }

    function italic() {
        //console.log("Italic I")
        document.execCommand("italic", false);
    }

    function underline() {
        //console.log("Underline U")
        document.execCommand("underline", false);
    }

    function strikethrough() {
        //console.log("Strikethrough S")
        document.execCommand("strikethrough", false);
    }


    function hasAncestorAnchor(element) {
        while (element) {
            if (element.tagName && element.tagName.toLowerCase() === 'a') {
                return true; // Found an anchor element
            }
            element = element.parentNode; // Move up to the parent node
        }
        return false; // Reached the top of the DOM tree without finding an anchor element
    }

    function getTheAncestorAnchor(element) {
        while (element) {
            if (element.tagName && element.tagName.toLowerCase() === 'a') {
                return [element, element.parentNode]; // Found an anchor element
            }
            element = element.parentNode; // Move up to the parent node
        }
        return [false, false]; // Reached the top of the DOM tree without finding an anchor element
    }

    function insertLink() {
        //if (isValidURL(value)) {
        let selectedText = "";
        if (window.getSelection) {
            selectedText = window.getSelection().toString();
        } else if (document.selection && document.selection.type !== 'Control') {
            // For older versions of Internet Explorer
            selectedText = document.selection.createRange().text;
        }

        // Check if the selected text is already wrapped in a link (or one of its ancestors is a link)
        let isLink = hasAncestorAnchor(window.getSelection().anchorNode);

        // If it's already a link, remove the link; otherwise, add the link
        if (isLink) {
            // Remove the link
            let elements = getTheAncestorAnchor(window.getSelection().anchorNode);
            let anchorElement = elements[0];
            let parentAnchor = elements[1];

            if (anchorElement && parentAnchor) {
                // Move children of the anchor element to its parent
                while (anchorElement.firstChild) {
                    parentAnchor.insertBefore(anchorElement.firstChild, anchorElement);
                }
                // Remove the anchor element itself
                parentAnchor.removeChild(anchorElement);
            }

            let text_input = document.getElementById("text--sticky-notes-notefox--website-test");
            onInputText(text_input);
        } else {
            /*let url = prompt("Enter the URL:");

            if (url) {
                document.execCommand('createLink', false, url);
            }*/
            //Creating link with the same selectedText
            document.execCommand('createLink', false, selectedText);
        }
        //}
    }

    function isValidURL(url) {
        var urlPattern = /^(https?:\/\/)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}(\/[^\s]*)?$/;
        return urlPattern.test(url);
    }

    function sanitizeHTML(input) {
        //console.log(input)

        let div_sanitize = document.createElement("div");
        div_sanitize.innerHTML = input;

        //console.log(input);

        let sanitizedHTML = sanitize(div_sanitize, -1, -1);

        //console.log(sanitizedHTML.innerHTML)

        return sanitizedHTML.innerHTML;
    }

    function listenerLinks(element, settings_json) {
        let notes = element;
        if (notes.innerHTML !== "" && notes.innerHTML !== "<br>") {
            let links = notes.querySelectorAll('a');
            links.forEach(link => {
                function onMouseOverDown(event, link) {
                    if (event.ctrlKey || event.metaKey) {
                        link.style.textDecorationStyle = "solid";
                        link.style.cursor = "pointer";
                    }
                }

                function onMouseLeaveUp(link) {
                    link.style.textDecorationStyle = "dotted";
                    link.style.cursor = "inherit";
                }

                link.onmousedown = function (event) {
                    onMouseOverDown(event, link);
                }
                link.onmouseover = function (event) {
                    onMouseOverDown(event, link);
                }
                link.onmouseup = function (event) {
                    onMouseLeaveUp(link);
                }
                link.onmouseleave = function (event) {
                    onMouseLeaveUp(link);
                }
                link.onclick = function (event) {
                    location.href = link.href;

                    event.preventDefault();
                }
            });
        }
    }

    function sanitize(element, allowedTags, allowedAttributes) {
        if (allowedTags === -1) allowedTags = ["b", "i", "u", "a", "strike", "code", "span", "div", "img", "br", "h1", "h2", "h3", "h4", "h5", "h6", "p", "small", "big", "em", "strong", "s", "sub", "sup", "blockquote", "q"];
        if (allowedAttributes === -1) allowedAttributes = ["src", "alt", "title", "cite", "href"];

        let sanitizedHTML = element;

        //console.log(input)
        for (var i = sanitizedHTML.childNodes.length - 1; i >= 0; i--) {
            var node = sanitize(sanitizedHTML.childNodes[i], allowedTags, allowedAttributes);

            if (node.nodeType === Node.ELEMENT_NODE) {
                if (allowedTags.includes(node.tagName.toLowerCase())) {
                    // Remove attributes unsupported of allowedTags
                    //console.log(`Checking tag ... ${node.tagName}`)
                    let attributes_to_remove = [];
                    for (var j = 0; j < node.attributes.length; j++) {
                        var attribute = node.attributes[j];
                        if (!allowedAttributes.includes(attribute.name.toLowerCase())) {
                            //console.log(`Removing attribute ... ${attribute.name} from ${node.tagName}`)
                            //element.removeAttribute(attribute.name);
                            attributes_to_remove.push(attribute.name);
                        } else {
                            //console.log(`OK attribute ${attribute.name} from ${node.tagName}`)
                        }
                    }
                    attributes_to_remove.forEach(attribute => {
                        node.removeAttribute(attribute);
                    });
                } else {
                    // Remove unsupported tags
                    //console.log(`Removing tag ... ${node.tagName}`)
                    //console.log(node.innerHTML)
                    let tmpNode = document.createElement("span");
                    if (node.innerHTML !== undefined) tmpNode.innerHTML = node.innerHTML;
                    else if (node.value !== undefined) tmpNode.innerHTML = node.value;
                    else tmpNode.innerText = "";
                    node.replaceWith(tmpNode);
                    //sanitizedHTML.remove(nod);
                }
            } else if (node.nodeType === Node.TEXT_NODE) {
                //console.log("Text supported")
                // Text nodes are allowed and can be kept
            } else {
                //console.log("????")
            }
        }
        return sanitizedHTML;
    }

    function openMinimized() {
        //console.log("Minimized!");
        let restore;
        if (!document.getElementById("restore--sticky-notes-notefox--website-test")) {
            let svg_image_restore = `base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+Cjxzdmcgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDMzNCAzMzQiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgeG1sbnM6c2VyaWY9Imh0dHA6Ly93d3cuc2VyaWYuY29tLyIgc3R5bGU9ImZpbGwtcnVsZTpldmVub2RkO2NsaXAtcnVsZTpldmVub2RkO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoyOyI+CiAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgwLjQxNjY2NywwLDAsMC40MTY2NjcsMCwwKSI+CiAgICAgICAgPHBhdGggZD0iTTU0LjE2Nyw0MDBDNTQuMTY3LDQxMy44MDcgNjUuMzYsNDI1IDc5LjE2Nyw0MjVMNDQ0LjkyLDQyNUwzNzkuNTYzLDQ4MS4wMkMzNjkuMDgsNDkwLjAwMyAzNjcuODY3LDUwNS43ODcgMzc2Ljg1Myw1MTYuMjdDMzg1LjgzNyw1MjYuNzUzIDQwMS42Miw1MjcuOTY3IDQxMi4xMDMsNTE4Ljk4TDUyOC43Nyw0MTguOThDNTM0LjMxLDQxNC4yMzMgNTM3LjUsNDA3LjI5NyA1MzcuNSw0MDBDNTM3LjUsMzkyLjcwMyA1MzQuMzEsMzg1Ljc2NyA1MjguNzcsMzgxLjAyTDQxMi4xMDMsMjgxLjAxOUM0MDEuNjIsMjcyLjAzMyAzODUuODM3LDI3My4yNDcgMzc2Ljg1MywyODMuNzNDMzY3Ljg2NywyOTQuMjEzIDM2OS4wOCwzMDkuOTk2IDM3OS41NjMsMzE4Ljk4MUw0NDQuOTIsMzc1TDc5LjE2NywzNzVDNjUuMzYsMzc1IDU0LjE2NywzODYuMTkzIDU0LjE2Nyw0MDBaIiBzdHlsZT0iZmlsbDp3aGl0ZTsiLz4KICAgICAgICA8cGF0aCBkPSJNMzEyLjUsMzI1LjAwMUwzMjUuMTA5LDMyNS4wMDFDMzE2LjQ5MSwzMDAuNTQ4IDMyMC44MDMsMjcyLjI5MiAzMzguODksMjUxLjE5MkMzNjUuODQ3LDIxOS43NDMgNDEzLjE5MywyMTYuMSA0NDQuNjQzLDI0My4wNTdMNTYxLjMxLDM0My4wNTdDNTc3LjkzMywzNTcuMzA3IDU4Ny41LDM3OC4xMDcgNTg3LjUsNDAwQzU4Ny41LDQyMS44OTcgNTc3LjkzMyw0NDIuNjk3IDU2MS4zMSw0NTYuOTQ3TDQ0NC42NDMsNTU2Ljk0N0M0MTMuMTkzLDU4My45MDMgMzY1Ljg0Nyw1ODAuMjYgMzM4Ljg5LDU0OC44MUMzMjAuODAzLDUyNy43MSAzMTYuNDkxLDQ5OS40NTMgMzI1LjEwOSw0NzVMMzEyLjUsNDc1TDMxMi41LDUzMy4zMzNDMzEyLjUsNjI3LjYxMyAzMTIuNSw2NzQuNzUzIDM0MS43OSw3MDQuMDQzQzM3MS4wOCw3MzMuMzMzIDQxOC4yMiw3MzMuMzMzIDUxMi41LDczMy4zMzNMNTQ1LjgzMyw3MzMuMzMzQzY0MC4xMTMsNzMzLjMzMyA2ODcuMjUzLDczMy4zMzMgNzE2LjU0Myw3MDQuMDQzQzc0NS44MzMsNjc0Ljc1MyA3NDUuODMzLDYyNy42MTMgNzQ1LjgzMyw1MzMuMzMzTDc0NS44MzMsMjY2LjY2N0M3NDUuODMzLDE3Mi4zODYgNzQ1LjgzMywxMjUuMjQ1IDcxNi41NDMsOTUuOTU2QzY4Ny4yNTMsNjYuNjY3IDY0MC4xMTMsNjYuNjY3IDU0NS44MzMsNjYuNjY3TDUxMi41LDY2LjY2N0M0MTguMjIsNjYuNjY3IDM3MS4wOCw2Ni42NjcgMzQxLjc5LDk1Ljk1NkMzMTIuNSwxMjUuMjQ1IDMxMi41LDE3Mi4zODYgMzEyLjUsMjY2LjY2N0wzMTIuNSwzMjUuMDAxWiIgc3R5bGU9ImZpbGw6d2hpdGU7ZmlsbC1ydWxlOm5vbnplcm87Ii8+CiAgICA8L2c+Cjwvc3ZnPgo=`;

            restore = document.createElement("input");
            restore.type = "button";
            restore.id = "restore--sticky-notes-notefox--website-test";
            //restore.value = "≻";
            let css = document.createElement("style");
            css.innerText = getCSSMinimized();
            document.body.appendChild(css);
            document.body.appendChild(restore);

        } else {
            restore = document.getElementById("restore--sticky-notes-notefox--website-test");
        }

        restore.onclick = function () {
            restore.remove();
            load();
        }
    }

    function getCSSMinimized() {
        let svg_image_restore = `base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+Cjxzdmcgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDMzNCAzMzQiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgeG1sbnM6c2VyaWY9Imh0dHA6Ly93d3cuc2VyaWYuY29tLyIgc3R5bGU9ImZpbGwtcnVsZTpldmVub2RkO2NsaXAtcnVsZTpldmVub2RkO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoyOyI+CiAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgwLjQxNjY2NywwLDAsMC40MTY2NjcsMCwwKSI+CiAgICAgICAgPHBhdGggZD0iTTU0LjE2Nyw0MDBDNTQuMTY3LDQxMy44MDcgNjUuMzYsNDI1IDc5LjE2Nyw0MjVMNDQ0LjkyLDQyNUwzNzkuNTYzLDQ4MS4wMkMzNjkuMDgsNDkwLjAwMyAzNjcuODY3LDUwNS43ODcgMzc2Ljg1Myw1MTYuMjdDMzg1LjgzNyw1MjYuNzUzIDQwMS42Miw1MjcuOTY3IDQxMi4xMDMsNTE4Ljk4TDUyOC43Nyw0MTguOThDNTM0LjMxLDQxNC4yMzMgNTM3LjUsNDA3LjI5NyA1MzcuNSw0MDBDNTM3LjUsMzkyLjcwMyA1MzQuMzEsMzg1Ljc2NyA1MjguNzcsMzgxLjAyTDQxMi4xMDMsMjgxLjAxOUM0MDEuNjIsMjcyLjAzMyAzODUuODM3LDI3My4yNDcgMzc2Ljg1MywyODMuNzNDMzY3Ljg2NywyOTQuMjEzIDM2OS4wOCwzMDkuOTk2IDM3OS41NjMsMzE4Ljk4MUw0NDQuOTIsMzc1TDc5LjE2NywzNzVDNjUuMzYsMzc1IDU0LjE2NywzODYuMTkzIDU0LjE2Nyw0MDBaIiBzdHlsZT0iZmlsbDp3aGl0ZTsiLz4KICAgICAgICA8cGF0aCBkPSJNMzEyLjUsMzI1LjAwMUwzMjUuMTA5LDMyNS4wMDFDMzE2LjQ5MSwzMDAuNTQ4IDMyMC44MDMsMjcyLjI5MiAzMzguODksMjUxLjE5MkMzNjUuODQ3LDIxOS43NDMgNDEzLjE5MywyMTYuMSA0NDQuNjQzLDI0My4wNTdMNTYxLjMxLDM0My4wNTdDNTc3LjkzMywzNTcuMzA3IDU4Ny41LDM3OC4xMDcgNTg3LjUsNDAwQzU4Ny41LDQyMS44OTcgNTc3LjkzMyw0NDIuNjk3IDU2MS4zMSw0NTYuOTQ3TDQ0NC42NDMsNTU2Ljk0N0M0MTMuMTkzLDU4My45MDMgMzY1Ljg0Nyw1ODAuMjYgMzM4Ljg5LDU0OC44MUMzMjAuODAzLDUyNy43MSAzMTYuNDkxLDQ5OS40NTMgMzI1LjEwOSw0NzVMMzEyLjUsNDc1TDMxMi41LDUzMy4zMzNDMzEyLjUsNjI3LjYxMyAzMTIuNSw2NzQuNzUzIDM0MS43OSw3MDQuMDQzQzM3MS4wOCw3MzMuMzMzIDQxOC4yMiw3MzMuMzMzIDUxMi41LDczMy4zMzNMNTQ1LjgzMyw3MzMuMzMzQzY0MC4xMTMsNzMzLjMzMyA2ODcuMjUzLDczMy4zMzMgNzE2LjU0Myw3MDQuMDQzQzc0NS44MzMsNjc0Ljc1MyA3NDUuODMzLDYyNy42MTMgNzQ1LjgzMyw1MzMuMzMzTDc0NS44MzMsMjY2LjY2N0M3NDUuODMzLDE3Mi4zODYgNzQ1LjgzMywxMjUuMjQ1IDcxNi41NDMsOTUuOTU2QzY4Ny4yNTMsNjYuNjY3IDY0MC4xMTMsNjYuNjY3IDU0NS44MzMsNjYuNjY3TDUxMi41LDY2LjY2N0M0MTguMjIsNjYuNjY3IDM3MS4wOCw2Ni42NjcgMzQxLjc5LDk1Ljk1NkMzMTIuNSwxMjUuMjQ1IDMxMi41LDE3Mi4zODYgMzEyLjUsMjY2LjY2N0wzMTIuNSwzMjUuMDAxWiIgc3R5bGU9ImZpbGw6d2hpdGU7ZmlsbC1ydWxlOm5vbnplcm87Ii8+CiAgICA8L2c+Cjwvc3ZnPgo=`;
        return `
            #restore--sticky-notes-notefox--website-test {
                position: fixed;
                height: 80px;
                width: 20px;
                z-index: 99999999999;
                top: 15%;
                left: 0px;
                right: auto;
                background-image: url('data:image/svg+xml;${svg_image_restore}');
                background-size: 70% auto;
                border-radius: 0px 10px 10px 0px;
                opacity: 0.2;
                background-repeat: no-repeat;
                background-position: center center;
                background-color: #ff6200;
                border: 0px solid transparent;
                color: #ffffff;
                cursor: pointer;
                margin: 0px !important;
                padding: 0px !important;
                box-sizing: border-box !important;
                box-shadow: 0px 0px 5px rgba(255,98,0,0.27);
                transition: 0.5s;
                font-size: 8px;

                min-width: 0px;
                min-height: 0px;
                line-height: normal;
            }
            #restore--sticky-notes-notefox--website-test:active, #restore--sticky-notes-notefox--website-test:focus {
                box-shadow: 0px 0px 0px 2px #ff6200, 0px 0px 0px 5px #ffb788;
            }
            #restore--sticky-notes-notefox--website-test:hover {
                opacity: 1;
                height: 80px;
                width: 30px;
            }`;
    }


    setTimeout(function () {
        load();
    }, 500);
</script>

</body>
</html>

<?php
?>