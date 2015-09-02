function loadTxt() {
    document.getElementById("tab0").innerHTML = "\u6211\u7684\u6587\u4EF6";
    document.getElementById("tab1").innerHTML = "\u6837\u5F0F";
    document.getElementById("lblUrl").innerHTML = "\u94FE\u63A5:";
    document.getElementById("lblTitle").innerHTML = "\u6807\u9898:";
    document.getElementById("lblTarget1").innerHTML = "\u5728\u5F53\u524D\u9875\u9762\u6253\u5F00";
    document.getElementById("lblTarget2").innerHTML = "\u5728\u65B0\u7A97\u53E3\u6253\u5F00";
    document.getElementById("lblTarget3").innerHTML = "\u5728\u5F39\u51FA\u5C42\u6253\u5F00";
    document.getElementById("lnkNormalLink").innerHTML = "\u6B63\u5E38\u94FE\u63A5 &raquo;";
    document.getElementById("btnCancel").value = "\u5173\u95ED";
}
function writeTitle() {
    document.write("<title>" + "\u94FE\u63A5" + "</title>")
}
function getTxt(s) {
    switch (s) {
        case "insert": return "\u63D2\u5165";
        case "change": return "\u786E\u5B9A";
    }
}