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

    function getCSS(notes, x = "10px", y = "10px", w = "200px", h = "300px", opacity = 0.8, websites_json, settings_json) {
        let svg_image_close = `base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj48c3ZnIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIHZpZXdCb3g9IjAgMCAxMTIgMTEyIiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zOnNlcmlmPSJodHRwOi8vd3d3LnNlcmlmLmNvbS8iIHN0eWxlPSJmaWxsLXJ1bGU6ZXZlbm9kZDtjbGlwLXJ1bGU6ZXZlbm9kZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6MjsiPjxwYXRoIGQ9Ik05LjI1OSw4My4zMzNjMCwtOC43MjkgMCwtMTMuMDk0IDIuNzEyLC0xNS44MDdjMi43MTIsLTIuNzEyIDcuMDc3LC0yLjcxMiAxNS44MDcsLTIuNzEyYzguNzMsMCAxMy4wOTUsMCAxNS44MDcsMi43MTJjMi43MTIsMi43MTIgMi43MTIsNy4wNzcgMi43MTIsMTUuODA3YzAsOC43MyAwLDEzLjA5NSAtMi43MTIsMTUuODA3Yy0yLjcxMiwyLjcxMiAtNy4wNzcsMi43MTIgLTE1LjgwNywyLjcxMmMtOC43MywwIC0xMy4wOTQsMCAtMTUuODA3LC0yLjcxMmMtMi43MTIsLTIuNzEyIC0yLjcxMiwtNy4wNzcgLTIuNzEyLC0xNS44MDdaIiBzdHlsZT0iZmlsbDojZmZmO2ZpbGwtcnVsZTpub256ZXJvO3N0cm9rZTojZmZmO3N0cm9rZS13aWR0aDowLjE0cHg7Ii8+PHBhdGggZD0iTTE2LjAzOSwxNi4wMzljLTYuNzgsNi43OCAtNi43OCwxNy42OTIgLTYuNzgsMzkuNTE3YzAsMS44MzEgMCwzLjU4NiAwLjAwNCw1LjI2N2MyLjM1MiwtMS41NDIgNC45NDQsLTIuMjE3IDcuNDI5LC0yLjU1MmMyLjk4OSwtMC40MDIgNi42NjQsLTAuNDAxIDEwLjY3MSwtMC40MDFsMC44MjksMGM0LjAwNywtMCA3LjY4MiwtMC4wMDEgMTAuNjcxLDAuNDAxYzMuMjkxLDAuNDQzIDYuNzcsMS40ODQgOS42MzIsNC4zNDVjMi44NjEsMi44NjIgMy45MDIsNi4zNDEgNC4zNDUsOS42MzJjMC40MDEsMi45ODkgMC40MDEsNi42NjQgMC40LDEwLjY3MWwwLDAuODI5YzAuMDAxLDQuMDA4IDAuMDAxLDcuNjgyIC0wLjQsMTAuNjdjLTAuMzM1LDIuNDg2IC0xLjAxLDUuMDc3IC0yLjU1Miw3LjQzYzEuNjgyLDAuMDA0IDMuNDM2LDAuMDA0IDUuMjY3LDAuMDA0YzIxLjgyNCwtMCAzMi43MzYsLTAgMzkuNTE3LC02Ljc4YzYuNzgsLTYuNzggNi43OCwtMTcuNjkyIDYuNzgsLTM5LjUxN2MtMCwtMjEuODI1IC0wLC0zMi43MzYgLTYuNzgsLTM5LjUxN2MtNi43OCwtNi43NzkgLTE3LjY5MiwtNi43NzkgLTM5LjUxNywtNi43NzljLTIxLjgyNSwtMCAtMzIuNzM2LC0wIC0zOS41MTYsNi43NzlsLTAsMC4wMDFabTQ1LjMwMywxMi44OTZjLTEuOTE4LC0wIC0zLjQ3MywxLjU1NCAtMy40NzMsMy40NzJjMCwxLjkxOCAxLjU1NSwzLjQ3MiAzLjQ3MywzLjQ3Mmw4Ljk3OCwwbC0xNy4yMjEsMTcuMjIxYy0xLjM1NiwxLjM1NiAtMS4zNTYsMy41NTQgMCw0LjkxYzEuMzU2LDEuMzU2IDMuNTU0LDEuMzU2IDQuOTEsMGwxNy4yMjEsLTE3LjIybDAsOC45NzhjMCwxLjkxOCAxLjU1NSwzLjQ3MiAzLjQ3MiwzLjQ3MmMxLjkxOCwwIDMuNDczLC0xLjU1NCAzLjQ3MywtMy40NzJsLTAsLTE3LjM2MWMtMCwtMS45MTggLTEuNTU1LC0zLjQ3MiAtMy40NzMsLTMuNDcybC0xNy4zNjEsLTBsMC4wMDEsLTBaIiBzdHlsZT0iZmlsbDojZmZmO3N0cm9rZTojZmZmO3N0cm9rZS13aWR0aDowLjE0cHg7Ii8+PC9zdmc+`;
        let svg_image_minimize = `base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+Cjxzdmcgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDMzNCAzMzQiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgeG1sbnM6c2VyaWY9Imh0dHA6Ly93d3cuc2VyaWYuY29tLyIgc3R5bGU9ImZpbGwtcnVsZTpldmVub2RkO2NsaXAtcnVsZTpldmVub2RkO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoyOyI+CiAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgwLjQxNjY2NywwLDAsMC40MTY2NjcsMCwwKSI+CiAgICAgICAgPHBhdGggZD0iTTUzNy41LDQwMEM1MzcuNSwzODYuMTkzIDUyNi4zMDcsMzc1IDUxMi41LDM3NUwxNDYuNzQ4LDM3NUwyMTIuMTAzLDMxOC45ODFDMjIyLjU4NiwzMDkuOTk2IDIyMy44LDI5NC4yMTMgMjE0LjgxNSwyODMuNzNDMjA1LjgyOSwyNzMuMjQ3IDE5MC4wNDcsMjcyLjAzMyAxNzkuNTY0LDI4MS4wMTlMNjIuODk3LDM4MS4wMkM1Ny4zNTYsMzg1Ljc2NyA1NC4xNjcsMzkyLjcwMyA1NC4xNjcsNDAwQzU0LjE2Nyw0MDcuMjk3IDU3LjM1Niw0MTQuMjMzIDYyLjg5Nyw0MTguOThMMTc5LjU2NCw1MTguOThDMTkwLjA0Nyw1MjcuOTY3IDIwNS44MjksNTI2Ljc1MyAyMTQuODE1LDUxNi4yN0MyMjMuOCw1MDUuNzg3IDIyMi41ODYsNDkwLjAwMyAyMTIuMTAzLDQ4MS4wMkwxNDYuNzQ4LDQyNUw1MTIuNSw0MjVDNTI2LjMwNyw0MjUgNTM3LjUsNDEzLjgwNyA1MzcuNSw0MDBaIiBzdHlsZT0iZmlsbDp3aGl0ZTsiLz4KICAgICAgICA8cGF0aCBkPSJNMzEyLjUsMjY2LjY2N0MzMTIuNSwyOTAuMDczIDMxMi41LDMwMS43NzYgMzE4LjExNywzMTAuMTgzQzMyMC41NDksMzEzLjgyNCAzMjMuNjc1LDMxNi45NDkgMzI3LjMxNSwzMTkuMzgyQzMzNS43MjMsMzI0Ljk5OSAzNDcuNDI3LDMyNC45OTkgMzcwLjgzMywzMjQuOTk5TDUxMi41LDMyNC45OTlDNTUzLjkyLDMyNC45OTkgNTg3LjUsMzU4LjU3NyA1ODcuNSw0MDBDNTg3LjUsNDQxLjQyIDU1My45Miw0NzUgNTEyLjUsNDc1TDM3MC44MzMsNDc1QzM0Ny40MjcsNDc1IDMzNS43Miw0NzUgMzI3LjMxMyw0ODAuNjE3QzMyMy42NzQsNDgzLjA1IDMyMC41NSw0ODYuMTczIDMxOC4xMTgsNDg5LjgxM0MzMTIuNSw0OTguMjIgMzEyLjUsNTA5LjkyMyAzMTIuNSw1MzMuMzMzQzMxMi41LDYyNy42MTMgMzEyLjUsNjc0Ljc1MyAzNDEuNzksNzA0LjA0M0MzNzEuMDgsNzMzLjMzMyA0MTguMjEzLDczMy4zMzMgNTEyLjQ5Myw3MzMuMzMzTDU0NS44MjcsNzMzLjMzM0M2NDAuMTA3LDczMy4zMzMgNjg3LjI0Nyw3MzMuMzMzIDcxNi41MzcsNzA0LjA0M0M3NDUuODI3LDY3NC43NTMgNzQ1LjgyNyw2MjcuNjEzIDc0NS44MjcsNTMzLjMzM0w3NDUuODI3LDI2Ni42NjdDNzQ1LjgyNywxNzIuMzg2IDc0NS44MjcsMTI1LjI0NSA3MTYuNTM3LDk1Ljk1NkM2ODcuMjQ3LDY2LjY2NyA2NDAuMTA3LDY2LjY2NyA1NDUuODI3LDY2LjY2N0w1MTIuNDkzLDY2LjY2N0M0MTguMjEzLDY2LjY2NyAzNzEuMDgsNjYuNjY3IDM0MS43OSw5NS45NTZDMzEyLjUsMTI1LjI0NSAzMTIuNSwxNzIuMzg2IDMxMi41LDI2Ni42NjdaIiBzdHlsZT0iZmlsbDp3aGl0ZTtmaWxsLXJ1bGU6bm9uemVybzsiLz4KICAgIDwvZz4KPC9zdmc+Cg==`;
        let svg_background_image = `base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj48c3ZnIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIHZpZXdCb3g9IjAgMCAzOSAxMCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4bWw6c3BhY2U9InByZXNlcnZlIiB4bWxuczpzZXJpZj0iaHR0cDovL3d3dy5zZXJpZi5jb20vIiBzdHlsZT0iZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEuNTsiPjxwYXRoIGQ9Ik03LjQ0NywwLjM0OWMtMCwwIC01LjgyNiwtMC4wODkgLTYuODc0LDAuMDA3Yy0wLjA5NCwwLjAwOSAtMC4xNTgsMC4wMzYgLTAuMTkyLDAuMTUxYy0wLjAzNSwwLjEyIC0wLjA2OCwxLjI4NiAtMC4wNjgsMi4wM2MtMCwxLjU3MSAtMCw1LjIzNSAtMCw2LjAxMWMtMCwwLjQ5OSAwLjA0NSwwLjg5OCAwLjA2NCwxLjAxM2MwLjAxMywwLjA3NCAwLjEsMC4wNjYgMC4xLDAuMDY2bDIuMDcxLDBjMS43NjQsMCA0LjI1NiwwLjA4NCA0Ljc2MywtMC4wMzZjMC4xNTUsLTAuMDM3IDAuMTg3LC0wLjExNSAwLjIwOSwtMC4yNTljMC4wNzgsLTAuNTA5IDAuMTExLC00LjE5MiAwLjExMSwtNS45NDNsLTAsLTIuODY4bC0wLjA5MiwtMC4wODRsLTAuMDkyLC0wLjA4OFoiIHN0eWxlPSJmaWxsOiMwMGE4MWM7ZmlsbC1vcGFjaXR5OjAuMTtmaWxsLXJ1bGU6bm9uemVybzsiLz48cGF0aCBkPSJNMC4zNTEsOS4zNDRjLTAuMTk2LDAuMTIzIDAuMTIxLDAuNTg5IDAuMTI2LDAuNTk2Yy0wLjA4MSwtMC4wMDEgLTAuMzYsLTAuMDQgLTAuNDA4LC0wLjMyOGMtMC4wMiwtMC4xMjEgLTAuMDY5LC0wLjU0IC0wLjA2OSwtMS4wNjRjMCwtMC43NzYgLTAsLTQuNDQgLTAsLTYuMDExYy0wLC0wLjc3NiAwLjA0NCwtMS45OTIgMC4wODEsLTIuMTE4YzAuMDQzLC0wLjE0NSAwLjExNSwtMC4yMjggMC4xOTYsLTAuMjg0YzAuMDczLC0wLjA1MSAwLjE2LC0wLjA4IDAuMjY4LC0wLjA5YzEuMDUyLC0wLjA5NiA2LjkwNywtMC4wMDggNi45MDcsLTAuMDA4YzAuMDgyLDAuMDAxIDAuMTU2LDAuMDM0IDAuMjExLDAuMDg3bDAuMDg5LDAuMDg1bDAuMDg4LDAuMDc5YzAuMTAxLDAuMDkzIDAuMTAxLDAuMTkxIDAuMDU5LDAuMjc3bDAuMDQ0LC0wLjA0NGwwLDIuODY4YzAsMS43NjUgLTAuMDM2LDUuNDc3IC0wLjExNCw1Ljk5MWMtMC4wMjYsMC4xNjggLTAuMDc3LDAuMjgyIC0wLjE2NSwwLjM2OGMtMC4wNjMsMC4wNjIgLTAuMTQ5LDAuMTE1IC0wLjI4MSwwLjE0N2MtMC41MTQsMC4xMjIgLTMuMDQ0LDAuMDQ1IC00LjgzNSwwLjA0NWwtMi4wNzEsLTBsMC4wMDIsLTBsLTAuMDAyLC0wbC0wLC0wLjMxM2wyLjA3MSwwYzEuNzY0LDAgNC4yNTYsMC4wODQgNC43NjMsLTAuMDM2YzAuMTU1LC0wLjAzNyAwLjE4NywtMC4xMTUgMC4yMDksLTAuMjU5YzAuMDc4LC0wLjUwOSAwLjExMSwtNC4xOTIgMC4xMTEsLTUuOTQzbC0wLC0yLjg2OGwtMC4wOTIsLTAuMDg0bC0wLjA5MiwtMC4wODhjLTAsMCAtNS44MjYsLTAuMDg5IC02Ljg3NCwwLjAwN2MtMC4wOTQsMC4wMDkgLTAuMTU4LDAuMDM2IC0wLjE5MiwwLjE1MWMtMC4wMzUsMC4xMiAtMC4wNjgsMS4yODYgLTAuMDY4LDIuMDNjLTAsMS41NzEgLTAsNS4yMzUgLTAsNi4wMTFjLTAsMC4zMjQgMC4wMTksMC42MDcgMC4wMzgsMC43OTZaIiBzdHlsZT0iZmlsbDojZmZmO2ZpbGwtb3BhY2l0eTowLjE7Ii8+PHBhdGggZD0iTTYuMzIsNC4xNTZjMS4zNjIsLTAuODUgMi42NDQsLTEuNDY1IDIuNjU2LC0xLjQ3N2MwLjAxMSwtMC4wMDcgMC4wMTksLTAuMTQxIDAuMDE1LC0wLjI5NWMtMC4wMDQsLTAuNDg4IC0wLjI0MiwtMC44NjIgLTAuNjg2LC0xLjA4NmwtMC4yMzQsLTAuMTE4Yy0wLC0wIC0xLjM0NiwxLjA0MSAtMi41NTcsMS43OTJsLTIuNDI5LDEuMzIxYy0wLDAgLTAuNzM1LDEuMzIxIC0wLjYxMSwxLjQzM2MwLjEwNCwwLjA5NSAxLjIzNSwtMC4wMDMgMS41MTYsLTAuMDMyYzAuMDA3LC0wIDAuNzc1LC0wLjU2NyAyLjMzLC0xLjUzOFoiIHN0eWxlPSJmaWxsOiMwMDM2MWM7ZmlsbC1vcGFjaXR5OjAuMTtmaWxsLXJ1bGU6bm9uemVybzsiLz48cGF0aCBkPSJNOS4xNywyLjkyM2wtMC4wMTksMC4wMTVjMC4wMzUsLTAuMDIzIDAuMDkyLC0wLjA2OSAwLjEyNCwtMC4xNjVjMC4wMTQsLTAuMDM5IDAuMDMzLC0wLjIwNSAwLjAyOSwtMC4zOTRjLTAuMDA2LC0wLjYxIC0wLjMwMiwtMS4wNzkgLTAuODU5LC0xLjM2bC0wLjIzNCwtMC4xMThjLTAuMTA3LC0wLjA1NCAtMC4yMzYsLTAuMDQyIC0wLjMzMiwwLjAzMmMwLC0wIC0xLjMyNCwxLjAyNSAtMi41MjEsMS43NjdjLTAuMDAyLDAuMDAxIC0yLjQyMywxLjMxOSAtMi40MjMsMS4zMTljLTAuMDUyLDAuMDI4IC0wLjA5NCwwLjA3MSAtMC4xMjMsMC4xMjJjLTAsMCAtMC40OSwwLjg4NSAtMC42MiwxLjI5OWMtMC4wMzksMC4xMjQgLTAuMDQ5LDAuMjI0IC0wLjA0MiwwLjI4NmMwLjAxMSwwLjEwOSAwLjA2LDAuMTgyIDAuMTE0LDAuMjMxYzAuMDQ1LDAuMDQyIDAuMTY5LDAuMTA1IDAuMzYxLDAuMTE3YzAuMzY1LDAuMDIzIDEuMTY0LC0wLjA0NSAxLjM5NywtMC4wNjljMC4wMDQsLTAgMC4wNzQsLTAuMDAyIDAuMTczLC0wLjA3MmMwLjE0MiwtMC4xMDEgMC45LC0wLjY0NCAyLjI5MSwtMS41MTJsLTAsLTBjMS4yMTQsLTAuNzU4IDIuMzY0LC0xLjMyNyAyLjU5LC0xLjQ0MWMwLjA0NCwtMC4wMjIgMC4wNzQsLTAuMDQzIDAuMDk0LC0wLjA1N1ptLTIuODUsMS4yMzNjMS4zNjIsLTAuODUgMi42NDQsLTEuNDY1IDIuNjU2LC0xLjQ3N2MwLjAxMSwtMC4wMDcgMC4wMTksLTAuMTQxIDAuMDE1LC0wLjI5NWMtMC4wMDQsLTAuNDg4IC0wLjI0MiwtMC44NjIgLTAuNjg2LC0xLjA4NmwtMC4yMzQsLTAuMTE4Yy0wLC0wIC0xLjM0NiwxLjA0MSAtMi41NTcsMS43OTJsLTIuNDI5LDEuMzIxYy0wLDAgLTAuNzM1LDEuMzIxIC0wLjYxMSwxLjQzM2MwLjEwNCwwLjA5NSAxLjIzNSwtMC4wMDMgMS41MTYsLTAuMDMyYzAuMDA3LC0wIDAuNzc1LC0wLjU2NyAyLjMzLC0xLjUzOFoiIHN0eWxlPSJmaWxsOiNmZmY7ZmlsbC1vcGFjaXR5OjAuMTsiLz48cGF0aCBkPSJNMS4yMjMsMS44NTFjLTAsLTAuMDMxIDAuMDI5LC0wLjA1NiAwLjA0NCwtMC4wODRjMC4wNSwtMC4wOTcgMC4xMTUsLTAuMTkgMC4xNzIsLTAuMjgzYzAuMDA2LC0wLjAwOSAwLjAzMSwtMC4wNjQgMC4wNDksLTAuMDc5YzAuMDEsLTAuMDA5IDAuMDMxLC0wLjA0IDAuMDMxLC0wLjAyN2MtMCwwLjIwOCAwLjAyNiwwLjQxMiAwLjAyNiwwLjYxOWwwLDAuMjY1YzAsMC4wMTEgMC4wMDMsMC4wNzYgMC4wMjcsMC4wNThjMC4xNTUsLTAuMTIxIDAuMjkyLC0wLjMzNCAwLjQ2NCwtMC40MmMwLjAxNiwtMC4wMDggMC4wMzEsMC4wMTggMC4wNDQsMC4wMzFjMC4wNDEsMC4wNDEgMC4wODUsMC4wODQgMC4xNDYsMC4wOTdjMC4wNjksMC4wMTUgMC4wNzEsLTAuMDY5IDAuMTE1LC0wLjA4YzAuMDg2LC0wLjAyMSAwLjE3NSwwLjA1OCAwLjI3LDAuMDI3YzAuMTQ2LC0wLjA0NyAwLjIzOCwtMC4xNDMgMC4zNTcsLTAuMjM0YzAuMDIsLTAuMDE1IDAuMDgxLC0wLjA3OCAwLjEyLC0wLjA2MmMwLjAzOSwwLjAxNiAwLjA0OCwwLjA0OSAwLjA3NSwwLjA3NWMwLjA3NCwwLjA3IDAuMTYzLDAuMTUgMC4yNDMsMC4yMDNjMC4wMzMsMC4wMjIgMC4xMTEsLTAuMDkxIDAuMTI4LC0wLjEwNmMwLjEwMiwtMC4wODcgMC4yMjMsLTAuMTU5IDAuMzU0LC0wLjE5YzAuMDI5LC0wLjAwNyAwLjA2NCwtMC4wMjkgMC4wODgsLTAuMDEzYzAuMDM2LDAuMDI0IDAuMDM1LDAuMDkgMC4wNDQsMC4xMjhjMC4wMTgsMC4wNzIgMC4wODksMC4yNzMgMC4xOTQsMC4yODdjMC4yMTksMC4wMjcgMC4zNTQsLTAuMjgyIDAuNTUzLC0wLjMyN2MwLjA5OSwtMC4wMjIgMC4xNDcsMC4xODIgMC4yNDQsMC4yMDRjMC4wNTgsMC4wMTMgMC4xMSwtMC4wMjkgMC4xNjcsLTAuMDM2YzAuMDk5LC0wLjAxMSAwLjIwMiwwLjAwNSAwLjMwMSwwLjAwNSIgc3R5bGU9ImZpbGw6bm9uZTtzdHJva2U6I2ZmZjtzdHJva2Utb3BhY2l0eTowLjI7c3Ryb2tlLXdpZHRoOjAuMzFweDsiLz48cGF0aCBkPSJNMS4xMzksNC4wMjJjMC4wMjQsLTAuMDE3IDAuMDEyLC0wLjA0OCAwLjAxMywtMC4wNzZjMC4wMDQsLTAuMDY4IDAuMDA3LC0wLjEzNSAwLjAxMywtMC4yMDNjMC4wMTIsLTAuMTI1IDAuMDQxLC0wLjI1OCAwLjExMiwtMC4zNjRjMC4wMTcsLTAuMDI3IDAuMDYzLC0wLjA4NyAwLjEsLTAuMDkxYzAuMjM3LC0wLjAyOCAwLjIzNywwLjI0MSAwLjM2MywwLjM2N2MwLjAzMSwwLjAzMSAwLjIsLTAuMDUgMC4yMjEsLTAuMDUzYzAuMDYsLTAuMDExIDAuMDc4LDAuMDggMC4xNDEsMC4xMDFjMC4xMDEsMC4wMzQgMC4zMTUsMC4wMTkgMC40MDIsLTAuMDA0YzAuMDY4LC0wLjAxOCAwLjE2NSwtMC4wNTIgMC4yMTMsLTAuMTA2YzAuMDIsLTAuMDI0IDAuMDIyLC0wLjA5NyAwLjA0OCwtMC4wOGMwLjEyMywwLjA4MiAwLjM4NSwwLjE2OCAwLjUwOCwwLjEwNiIgc3R5bGU9ImZpbGw6bm9uZTtzdHJva2U6I2ZmZjtzdHJva2Utb3BhY2l0eTowLjI7c3Ryb2tlLXdpZHRoOjAuMzFweDsiLz48ZyB0cmFuc2Zvcm09Im1hdHJpeCg4LjA1NDIsMCwwLDguMDU0MiwzOC4yNTM2LDcuODY2NTgpIj48L2c+PHRleHQgeD0iMTAuNDk3cHgiIHk9IjcuODY3cHgiIHN0eWxlPSJmb250LWZhbWlseTonQXJpYWxNVCcsICdBcmlhbCcsIHNhbnMtc2VyaWY7Zm9udC1zaXplOjguMDU0cHg7ZmlsbDojMDBhODFjO2ZpbGwtb3BhY2l0eTowLjE7Ij5Ob3RlZm94PC90ZXh0Pjwvc3ZnPg==`;

        if (settings_json["font-family"] === undefined || (settings_json["font-family"] !== "Shantell Sans" && settings_json["font-family"] !== "Open Sans")) settings_json["font-family"] = "Shantell Sans";

        return `
            @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Source+Code+Pro:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap&family=Shantell+Sans:ital,wght@0,300..800;1,300..800&display=swap');

            #sticky-notes-notefox--website-test {
                position: fixed;
                top: 20px;
                left: 20px;
                width: 300px;
                height: 300px;
                background-color: #fffd7d;
                opacity: 0.8;
                z-index: 99999999999;
                padding: 15px !important;
                margin: 0px !important;
                box-sizing: border-box !important;
                border-radius: 10px;
                border-bottom-right-radius: 0px;
                cursor: default;
                box-shadow: 0px 0px 5px rgba(255,98,0,0.27);
                font-family: inherit;
                color: #111111;
                font-size: 17px;
                background-image: url('data:image/svg+xml;${svg_background_image}');
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
                background-color: #ff6200;
                opacity: 1;
                cursor: grab;
                border-radius: 0px 0px 10px 10px;
                z-index: 4;
                transition: 0.5s;
                font-weight: bold !important;
                font-family: 'Open Sans', sans-serif;
                padding: 2px 5px !important;
                font-size: 10px !important;
                border: 0px solid transparent;
                color: #ffffff;
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
                border-right: 10px solid #ff6200;
                width: 0;
            }
            #text--sticky-notes-notefox--website-test {
                scrollbar-color: #ff6200 transparent;
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
                color: #111111 !important;
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
                bottom: 35px;
                width: auto;
                height: auto;
                padding: 0px !important;
                overflow: visible;
            }
            #text--sticky-notes-notefox--website-test:focus {
                outline: 2px solid #ff6200;
            }

            #text--sticky-notes-notefox--website-test, #text--sticky-notes-notefox--website-test * {
                font-family: '${settings_json["font-family"]}', sans-serif;
            }

            #close--sticky-notes-notefox--website-test, #minimize--sticky-notes-notefox--website-test {
                position: absolute;
                top: 0px ;
                right: 0px;
                width: 30px;
                height: 30px;
                background-image: url('data:image/svg+xml;${svg_image_close}');
                background-size: auto 70%;
                background-repeat: no-repeat;
                background-position: center center;
                background-color: #ff6200;
                border: 0px solid transparent;
                color: #ffffff;
                z-index: 5;
                border-radius: 10px;
                cursor: pointer;
                margin: 0px !important;
                padding: 0px !important;
                box-sizing: border-box !important;
                font-size: 8px;
            }
            #close--sticky-notes-notefox--website-test:active, #close--sticky-notes-notefox--website-test:focus, #minimize--sticky-notes-notefox--website-test:active, #minimize--sticky-notes-notefox--website-test:focus {
                box-shadow: 0px 0px 0px 2px #ff6200, 0px 0px 0px 5px #ffb788;
                z-index: 6;
                transition: 0.5s;
            }
            #minimize--sticky-notes-notefox--website-test {
                left: 0px;
                right: auto;
                background-image: url('data:image/svg+xml;${svg_image_minimize}');
            }

            #slider-container--sticky-notes-notefox--website-test {
                position: absolute;
                z-index: 2;
                width: auto !important;
                left: 10px !important;
                right: 10px !important;
                bottom: 10px !important;
                margin: 0px !important;
                padding: 0px 10px !important;
                box-sizing: border-box !important;
            }

            #slider--sticky-notes-notefox--website-test {
                width: 100%;
                height: 5px;
                background: linear-gradient(to right, #ff6200 0%, #ff6200 ${opacity * 100}%, #eeeeee ${opacity * 100}%, #eeeeee 100%);
                border: 1px solid #ffb788;
                outline: none;
                opacity: 0.7;
                transition: opacity .2s;
                cursor: pointer;
                border-radius: 10px;
                margin: 0px !important;
                padding: 0px !important;
                box-sizing: border-box !important;
            }

            #slider--sticky-notes-notefox--website-test:hover {
                opacity: 1;
            }

            #slider--sticky-notes-notefox--website-test::-moz-range-thumb {
                width: 15px;
                height: 15px;
                background: #ff6200;
                cursor: grab;
                border: 1px solid #eeeeee;
                border-radius: 100%;
                margin: 0px;
            }
            #slider--sticky-notes-notefox--website-test::-moz-range-thumb:active {
                cursor: grabbing;
                box-shadow: 0px 0px 0px 1px #ffb788, 0px 0px 0px 4px #ff6200;
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
            }`;
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