var platform = navigator.userAgent.toLowerCase();
window.isWifiwx = true;
window.isIOS = /iphone|ipod|ipad/gi.test(platform);
window.isIOS7 = /iphone os 7/gi.test(platform);
window.isIPad = /ipad/gi.test(platform);
window.isAndroid = /android/gi.test(platform);
window.isAndroidOld = /android 2.3/gi.test(platform) || /android 2.2/gi.test(platform);
window.isSafari = /safari/gi.test(platform) && !/chrome/gi.test(platform);
window.isWechat = /micromessenger/gi.test(platform);
window.localhost = "app.wifiwx.com";
function object2str(i, o) {
    if (typeof i === "number" || !isNaN(+i) && i !== "")
        return i;
    else if (typeof i === "string")
        return '"' + i + '"';
    else if (typeof i === "function")
        return "一个函数";
    else if (typeof i !== "object")
        return String(i);
    else if (Object.prototype.toString.call(i) == "[object RegExp]")
        return i.toSource();
    var n, a, t, e, s = "    ";
    o = o || 1;
    for (e = "", a = 0; a < o; a++)
        e += s;
    n = "Array(\n";
    for (t in i) {
        n += e + object2str(t) + " => " + object2str(i[t], o + 1) + ",\n"
    }
    n += e.replace(s, "") + ")";
    return n
}
function print_r(i, o) {
    var n = object2str(i);
    return o ? n : alert(n)
}
var appUserInfo, appSystemInfo, appDeviceToken,appLocationInfo;
var appDLButton = isIOS || isAndroid ? '<div style="margin:-15px -15px 0;"><a href="javascript:;" onclick="appLink()"><img width="100%" src="//app.wifiwx.com/img/tip-download.png" alt="立刻下载智慧无锡参加活动" /></a></div><div style="margin:0 0 5px;" class="button"><a href="javascript:;" onclick="window.location.reload()" class="link-button green">刷新重试</a></div>' : '请在智慧无锡2.0以上版本参与活动<div style="margin:10px 0 5px;"><a href="http://www.wifiwx.com/getapp.html"><img src="http://www.wifiwx.com/statics/img/qrcode.png" width="200" /></a></div>扫描二维码立即下载', appNotificationTip = isIOS ? isIOS7 ? '<img src="//app.wifiwx.com/img/notification_ios7.png" width="258" alt="" />' : '<img src="/img/notification_ios6.png" width="258" alt="" />' : "", appErrorMsg = "请求超时，请返回重试。请在智慧无锡2.0以上版本参与活动，iPhone用户请打开推送通知。" + appDLButton + "<br>" + appNotificationTip, appErrorMsg1 = appDLButton, appErrorMsgBox = $("#page"), loadingPage = $("#page");
var appLoginButton = '<div style="margin:10px 0 5px;" class="button"><a href="javascript:;" onclick="goUcenter()" class="link-button red">去登录</a></div><div style="margin:10px 0 5px;" class="button"><a href="javascript:;" onclick="window.location.reload()" class="link-button green">刷新重试</a></div>', appLoginMsg = '<div class="tip-login">请登录后进行操作</div>' + appLoginButton, appLoginMsg1 = '<div class="tip-login">请在智慧无锡内登录后进行操作</div>' + appLoginButton;
var appAuthTimeout = 3e3;
var authDeviceSuccess, authUserSuccess,authLocationSuccess;
function showErrorMsg(i) {
    appErrorMsgBox.html(i).show();
    loadingPage.removeClass("loading")
}
function appLink(i) {
    if (!i) {
        i = $('meta[name="apple-itunes-app"]').attr("content") || "";
        if (i.indexOf("app-argument") > -1) {
            i = i.substring(i.indexOf("app-argument") + "app-argument=".length)
        }
    }
    if (-1 === i.indexOf("wifiwx://"))
        i = "wifiwx://" + i;
    window.location.href = i;
    window.setTimeout(function() {
        window.location.href = "http://www.wifiwx.com/getapp.html"
    }, 250)
}
function getSystemInfo(i) {
    appSystemInfo = i.device_token ? i : i.deviceInfo;
    appDeviceToken = appSystemInfo.device_token;
    if (appDeviceToken) {
        authDevice(authDeviceSuccess)
    } else {
    }
    loadingPage.removeClass("loading")
}
function authDevice(i) {
    if (typeof i == "function")
        i()
}
function callSystemInfo(i, o) {
    try {
        var n = 0;
        var a = setInterval(function() {
            if (appSystemInfo) {
                clearInterval(a);
                if (typeof i == "function")
                    i()
            } else {
                n += 500;
                if (n >= appAuthTimeout) {
                    clearInterval(a);
                    if (!appSystemInfo) {
                        window.isWifiwx = false;
                        showErrorMsg(appErrorMsg1);
                        if (typeof o == "function")
                            o()
                    }
                }
            }
        }, 500);
        if (isIOS) {
            window.location.hash = "";
            window.location.hash = '#func=getSystemInfo&parameter={"action" : "haha"}';
            window.location.hash = "";
            window.location.hash = '#func=getSystemInfo&parameter={"action" : "haha"}'
        } else if (isAndroid) {
            window.android.callSystemInfo()
        }
    } catch (o) {
        window.isWifiwx = false;
        showErrorMsg(appErrorMsg1)
    }
}
function getUserInfo(i) {
    appUserInfo = i.userInfo || i.userinfo;
    if (appUserInfo) {
        if (appUserInfo.userid && appUserInfo.userTokenKey) {
            authUser(authUserSuccess)
        } else {
            showErrorMsg(appLoginMsg)
        }
    } else {
        showErrorMsg(appLoginMsg)
    }
    loadingPage.removeClass("loading")
}
function authUser(i) {
    var o = "http://" + window.localhost + "/m2omobile/ck_member.php", n = "json";
    if (window.location.hostname != window.localhost) {
        o += "?type=jsonp&callback=?";
        n = "jsonp"
    }
    $.ajax({url: o,data: {userid: appUserInfo.userid,usertokenkey: appUserInfo.userTokenKey},dataType: n,cache: false,success: function(o) {
            if (o.error > 0) {
                if (typeof i == "function")
                    i()
            } else {
                showErrorMsg(appLoginMsg)
            }
        },error: function() {
            showErrorMsg(appLoginMsg)
        }})
}
function callUserInfo() {
    try {
        var i = 0;
        var o = setInterval(function() {
            i += 500;
            if (i >= appAuthTimeout) {
                clearInterval(o);
                if (!appUserInfo)
                    showErrorMsg(appLoginMsg)
            }
        }, 500);
        if (isIOS) {
            window.location.hash = "";
            window.location.hash = '#func=getUserInfo&parameter={"action" : "haha"}';
            window.location.hash = "";
            window.location.hash = '#func=getUserInfo&parameter={"action" : "haha"}'
        } else if (isAndroid) {
            window.android.callUserInfo()
        }
    } catch (n) {
        showErrorMsg(appLoginMsg)
    }
}
function userLoginSuccess(i) {
    print_r(i)
}
function goHome() {
    if (isIOS) {
        window.location.hash = "";
        window.location.hash = "#func=goHome"
    } else if (isAndroid) {
        window.android.goHome()
    }
}
function goBack() {
    if (isIOS) {
        window.location.hash = "";
        window.location.hash = "#func=goBack"
    } else if (isAndroid) {
        window.android.goBack()
    }
}
function goUcenter() {
    if (isIOS) {
        window.location.hash = "";
        window.location.hash = "#func=goUcenter"
    } else if (isAndroid) {
        window.android.goUcenter()
    }
}
function goOutLink(i) {
    if (isIOS) {
        i = i.replace("#", "&");
        window.location.hash = "";
        window.location.hash = "#" + i
    } else if (isAndroid) {
        window.android.goOutlink(i)
    }
}
function share(i, o, n) {
    if (window.isWifiwx) {
        if (isIOS) {
            var a = "#func=sharePlatsAction&content=" + i + "&content_url=" + o;
            if (n)
                a += "&pic=" + n;
            window.location.hash = "";
            window.location.href = a
        } else if (isAndroid) {
            window.android.sharePlatsAction(i, o, n)
        }
    } else {
        var t = $("#tip-share");
        if (t.length > 0) {
            t.show()
        } else {
            t = $('<div id="tip-share" class="tip-share-wechat"><img src="//app.wifiwx.com/img/tip-share-wechat.png" width="100%" alt="分享提示" /></div>');
            $("body").append(t);
            t.click(function() {
                $(this).hide()
            })
        }
    }
}

function callLocation(i,o){
	try {
        var n = 0;
        var a = setInterval(function() {
            if (appLocationInfo) {
                clearInterval(a);
                if (typeof i == "function")
                    i()
            } else {
                n += 500;
                if (n >= appAuthTimeout) {
                    clearInterval(a);
                    if (!appLocationInfo) {
                        window.isWifiwx = false;
                        showErrorMsg(appErrorMsg1);
                        if (typeof o == "function")
                            o()
                    }
                }
            }
        }, 500);
        if (isIOS) {
            window.location.hash = "";
            window.location.hash = '#func=getLocation&parameter={"action" : "haha"}';
            window.location.hash = "";
            window.location.hash = '#func=getLocation&parameter={"action" : "haha"}'
        } else if (isAndroid) {
        	try {
	            window.android.callLocation();
        	} catch ( error) {
        		showErrorMsg(appErrorMsg1)
        	}
        }
    } catch (o) {
        window.isWifiwx = false;
        showErrorMsg(appErrorMsg1)
    }
}
function getLocation(i){
    if (i) {
		appLocationInfo = i;
        authDevice(authLocationSuccess)
    } else {
    }
    loadingPage.removeClass("loading")
}

