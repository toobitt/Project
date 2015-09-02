function loadTxt() {
    document.getElementById("tab0").innerHTML = "FLICKR\u76F8\u518C";
    document.getElementById("tab1").innerHTML = "\u6211\u7684\u6587\u4EF6";
    document.getElementById("tab2").innerHTML = "\u6837\u5F0F";
    document.getElementById("tab3").innerHTML = "\u6548\u679C";
    document.getElementById("lblTag").innerHTML = "\u6807\u7B7E:";
    document.getElementById("lblFlickrUserName").innerHTML = "Flickr \u7528\u6237\u540D:";
    document.getElementById("lnkLoadMore").innerHTML = "\u52A0\u8F7D\u66F4\u591A";
    document.getElementById("lblImgSrc").innerHTML = "\u56FE\u7247\u5730\u5740:";
    document.getElementById("lblWidthHeight").innerHTML = "\u5BBD x \u9AD8:";
    
    var optAlign = document.getElementsByName("optAlign");
    optAlign[0].text = ""
    optAlign[1].text = "\u5DE6"
    optAlign[2].text = "\u53F3"

    document.getElementById("lblTitle").innerHTML = "\u6807\u9898:";
    document.getElementById("lblAlign").innerHTML = "\u4F4D\u7F6E:";
    document.getElementById("lblSpacing").innerHTML = "\u6C34\u5E73\u8DDD\u79BB:";
    document.getElementById("lblSpacingH").innerHTML = "\u5782\u76F4\u8DDD\u79BB:";
    document.getElementById("lblSize1").innerHTML = "\u5C0F\u6B63\u65B9\u5F62";
    document.getElementById("lblSize2").innerHTML = "\u7F29\u7565\u56FE";
    document.getElementById("lblSize3").innerHTML = "\u5C0F";
    document.getElementById("lblSize5").innerHTML = "\u4E2D";
    document.getElementById("lblSize6").innerHTML = "\u5927";

    document.getElementById("lblOpenLarger").innerHTML = "\u5728\u5F39\u51FA\u5C42\u4E2D\u6253\u5F00\u5927\u56FE, \u6216\u8005";
    document.getElementById("lblLinkToUrl").innerHTML = "URL\u94FE\u63A5:";
    document.getElementById("lblNewWindow").innerHTML = "\u5728\u65B0\u7A97\u53E3\u4E2D\u6253\u5F00.";
    document.getElementById("btnCancel").value = "\u5173\u95ED";
    document.getElementById("btnSearch").value = " \u67E5\u627E ";

    document.getElementById("btnRestore").value = "\u539F\u59CB\u56FE\u7247";
    document.getElementById("btnSaveAsNew").value = "\u91CD\u65B0\u4FDD\u5B58"; 
}
function writeTitle() {
    document.write("<title>" + "\u56FE\u7247" + "</title>")
}
function getTxt(s) {
    switch (s) {
        case "insert": return "\u63D2\u5165";
        case "change": return "\u786E\u5B9A";
        case "notsupported": return "\u5916\u90E8\u56FE\u7247\u4E0D\u652F\u6301.";
    }
}