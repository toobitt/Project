function loadTxt() {
    document.getElementById("tab0").innerHTML = "\u7ED8\u753B";
    document.getElementById("tab1").innerHTML = "\u8BBE\u7F6E";
    document.getElementById("tab3").innerHTML = "\u4FDD\u5B58";

    document.getElementById("lblWidthHeight").innerHTML = "\u753B\u5E03\u5927\u5C0F:";
    
    var optAlign = document.getElementsByName("optAlign");
    optAlign[0].text = ""
    optAlign[1].text = "\u5DE6"
    optAlign[2].text = "\u53F3"

    document.getElementById("lblTitle").innerHTML = "\u6807\u9898:";
    document.getElementById("lblAlign").innerHTML = "\u4F4D\u7F6E:";
    document.getElementById("lblSpacing").innerHTML = "\u5782\u76F4\u8DDD\u79BB:";
    document.getElementById("lblSpacingH").innerHTML = "\u6C34\u5E73\u8DDD\u79BB:";

    document.getElementById("btnCancel").value = "\u5173\u95ED";
}
function writeTitle() {
    document.write("<title>" + "\u7ED8\u753B" + "</title>")
}
function getTxt(s) {
    switch (s) {
        case "insert": return "\u63D2\u5165";
        case "change": return "\u786E\u5B9A";
        case "DELETE": return "\u5220\u9664";
    }
}