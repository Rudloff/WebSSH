var scroll = function () {
    "use strict";
    var shell = document.getElementById("shell");
    window.scrollTo(0, shell.scrollHeight);
};
window.addEventListener("load", scroll, true);
