/*!	SWFObject v2.2 <http://code.google.com/p/swfobject/> 
 is released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
 */
var avpw_swfobject = function() {
    function C() {
        if (b) return;
        try {
            var e = a.getElementsByTagName("body")[0].appendChild(U("span"));
            e.parentNode.removeChild(e)
        } catch (t) {
            return
        }
        b = !0;
        var n = c.length;
        for (var r = 0; r < n; r++) c[r]()
    }
    function k(e) {
        b ? e() : c[c.length] = e
    }
    function L(t) {
        if (typeof u.addEventListener != e) u.addEventListener("load", t, !1);
        else if (typeof a.addEventListener != e) a.addEventListener("load", t, !1);
        else if (typeof u.attachEvent != e) z(u, "onload", t);
        else if (typeof u.onload == "function") {
            var n = u.onload;
            u.onload = function() {
                n(), t()
            }
        } else u.onload = t
    }
    function A() {
        l ? O() : M()
    }
    function O() {
        var n = a.getElementsByTagName("body")[0],
            r = U(t);
        r.setAttribute("type", i);
        var s = n.appendChild(r);
        if (s) {
            var o = 0;
            (function() {
                if (typeof s.GetVariable != e) {
                    var t = s.GetVariable("$version");
                    t && (t = t.split(" ")[1].split(","), T.pv = [parseInt(t[0], 10), parseInt(t[1], 10), parseInt(t[2], 10)])
                } else if (o < 10) {
                    o++, setTimeout(arguments.callee, 10);
                    return
                }
                n.removeChild(r), s = null, M()
            })()
        } else M()
    }
    function M() {
        var t = h.length;
        if (t > 0) for (var n = 0; n < t; n++) {
            var r = h[n].id,
                i = h[n].callbackFn,
                s = {
                    success: !1,
                    id: r
                };
            if (T.pv[0] > 0) {
                var o = R(r);
                if (o) if (W(h[n].swfVersion) && !(T.wk && T.wk < 312)) V(r, !0), i && (s.success = !0, s.ref = _(r), i(s));
                else if (h[n].expressInstall && D()) {
                    var u = {};
                    u.data = h[n].expressInstall, u.width = o.getAttribute("width") || "0", u.height = o.getAttribute("height") || "0", o.getAttribute("class") && (u.styleclass = o.getAttribute("class")), o.getAttribute("align") && (u.align = o.getAttribute("align"));
                    var a = {},
                        f = o.getElementsByTagName("param"),
                        l = f.length;
                    for (var c = 0; c < l; c++) f[c].getAttribute("name").toLowerCase() != "movie" && (a[f[c].getAttribute("name")] = f[c].getAttribute("value"));
                    P(u, a, r, i)
                } else H(o), i && i(s)
            } else {
                V(r, !0);
                if (i) {
                    var p = _(r);
                    p && typeof p.SetVariable != e && (s.success = !0, s.ref = p), i(s)
                }
            }
        }
    }
    function _(n) {
        var r = null,
            i = R(n);
        if (i && i.nodeName == "OBJECT") if (typeof i.SetVariable != e) r = i;
        else {
            var s = i.getElementsByTagName(t)[0];
            s && (r = s)
        }
        return r
    }
    function D() {
        return !w && W("6.0.65") && (T.win || T.mac) && !(T.wk && T.wk < 312)
    }
    function P(t, n, r, i) {
        w = !0, g = i || null, y = {
            success: !1,
            id: r
        };
        var o = R(r);
        if (o) {
            o.nodeName == "OBJECT" ? (v = B(o), m = null) : (v = o, m = r), t.id = s;
            if (typeof t.width == e || !/%$/.test(t.width) && parseInt(t.width, 10) < 310) t.width = "310";
            if (typeof t.height == e || !/%$/.test(t.height) && parseInt(t.height, 10) < 137) t.height = "137";
            a.title = a.title.slice(0, 47) + " - Flash Player Installation";
            var u = T.ie && T.win ? "ActiveX" : "PlugIn",
                f = "MMredirectURL=" + encodeURI(window.location).toString().replace(/&/g, "%26") + "&MMplayerType=" + u + "&MMdoctitle=" + a.title;
            typeof n.flashvars != e ? n.flashvars += "&" + f : n.flashvars = f;
            if (T.ie && T.win && o.readyState != 4) {
                var l = U("div");
                r += "SWFObjectNew", l.setAttribute("id", r), o.parentNode.insertBefore(l, o), o.style.display = "none", function() {
                    o.readyState == 4 ? o.parentNode.removeChild(o) : setTimeout(arguments.callee, 10)
                }()
            }
            j(t, n, r)
        }
    }
    function H(e) {
        if (T.ie && T.win && e.readyState != 4) {
            var t = U("div");
            e.parentNode.insertBefore(t, e), t.parentNode.replaceChild(B(e), t), e.style.display = "none", function() {
                e.readyState == 4 ? e.parentNode.removeChild(e) : setTimeout(arguments.callee, 10)
            }()
        } else e.parentNode.replaceChild(B(e), e)
    }
    function B(e) {
        var n = U("div");
        if (T.win && T.ie) n.innerHTML = e.innerHTML;
        else {
            var r = e.getElementsByTagName(t)[0];
            if (r) {
                var i = r.childNodes;
                if (i) {
                    var s = i.length;
                    for (var o = 0; o < s; o++)(i[o].nodeType != 1 || i[o].nodeName != "PARAM") && i[o].nodeType != 8 && n.appendChild(i[o].cloneNode(!0))
                }
            }
        }
        return n
    }
    function j(n, r, s) {
        var o, u = R(s);
        if (T.wk && T.wk < 312) return o;
        if (u) {
            typeof n.id == e && (n.id = s);
            if (T.ie && T.win) {
                var a = "";
                for (var f in n) n[f] != Object.prototype[f] && (f.toLowerCase() == "data" ? r.movie = n[f] : f.toLowerCase() == "styleclass" ? a += ' class="' + n[f] + '"' : f.toLowerCase() != "classid" && (a += " " + f + '="' + n[f] + '"'));
                var l = "";
                for (var c in r) r[c] != Object.prototype[c] && (l += '<param name="' + c + '" value="' + r[c] + '" />');
                u.outerHTML = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"' + a + ">" + l + "</object>", p[p.length] = n.id, o = R(n.id)
            } else {
                var h = U(t);
                h.setAttribute("type", i);
                for (var d in n) n[d] != Object.prototype[d] && (d.toLowerCase() == "styleclass" ? h.setAttribute("class", n[d]) : d.toLowerCase() != "classid" && h.setAttribute(d, n[d]));
                for (var v in r) r[v] != Object.prototype[v] && v.toLowerCase() != "movie" && F(h, v, r[v]);
                u.parentNode.replaceChild(h, u), o = h
            }
        }
        return o
    }
    function F(e, t, n) {
        var r = U("param");
        r.setAttribute("name", t), r.setAttribute("value", n), e.appendChild(r)
    }
    function I(e) {
        var t = R(e);
        t && t.nodeName == "OBJECT" && (T.ie && T.win ? (t.style.display = "none", function() {
            t.readyState == 4 ? q(e) : setTimeout(arguments.callee, 10)
        }()) : t.parentNode.removeChild(t))
    }
    function q(e) {
        var t = R(e);
        if (t) {
            for (var n in t) typeof t[n] == "function" && (t[n] = null);
            t.parentNode.removeChild(t)
        }
    }
    function R(e) {
        var t = null;
        try {
            t = a.getElementById(e)
        } catch (n) {}
        return t
    }
    function U(e) {
        return a.createElement(e)
    }
    function z(e, t, n) {
        e.attachEvent(t, n), d[d.length] = [e, t, n]
    }
    function W(e) {
        var t = T.pv,
            n = e.split(".");
        return n[0] = parseInt(n[0], 10), n[1] = parseInt(n[1], 10) || 0, n[2] = parseInt(n[2], 10) || 0, t[0] > n[0] || t[0] == n[0] && t[1] > n[1] || t[0] == n[0] && t[1] == n[1] && t[2] >= n[2] ? !0 : !1
    }
    function X(n, r, i, s) {
        if (T.ie && T.mac) return;
        var o = a.getElementsByTagName("head")[0];
        if (!o) return;
        var u = i && typeof i == "string" ? i : "screen";
        s && (E = null, S = null);
        if (!E || S != u) {
            var f = U("style");
            f.setAttribute("type", "text/css"), f.setAttribute("media", u), E = o.appendChild(f), T.ie && T.win && typeof a.styleSheets != e && a.styleSheets.length > 0 && (E = a.styleSheets[a.styleSheets.length - 1]), S = u
        }
        T.ie && T.win ? E && typeof E.addRule == t && E.addRule(n, r) : E && typeof a.createTextNode != e && E.appendChild(a.createTextNode(n + " {" + r + "}"))
    }
    function V(e, t) {
        if (!x) return;
        var n = t ? "visible" : "hidden";
        b && R(e) ? R(e).style.visibility = n : X("#" + e, "visibility:" + n)
    }
    function $(t) {
        var n = /[\\\"<>\.;]/,
            r = n.exec(t) != null;
        return r && typeof encodeURIComponent != e ? encodeURIComponent(t) : t
    }
    var e = "undefined",
        t = "object",
        n = "Shockwave Flash",
        r = "ShockwaveFlash.ShockwaveFlash",
        i = "application/x-shockwave-flash",
        s = "SWFObjectExprInst",
        o = "onreadystatechange",
        u = window,
        a = document,
        f = navigator,
        l = !1,
        c = [A],
        h = [],
        p = [],
        d = [],
        v, m, g, y, b = !1,
        w = !1,
        E, S, x = !0,
        T = function() {
            var s = typeof a.getElementById != e && typeof a.getElementsByTagName != e && typeof a.createElement != e,
                o = f.userAgent.toLowerCase(),
                c = f.platform.toLowerCase(),
                h = c ? /win/.test(c) : /win/.test(o),
                p = c ? /mac/.test(c) : /mac/.test(o),
                d = /webkit/.test(o) ? parseFloat(o.replace(/^.*webkit\/(\d+(\.\d+)?).*$/, "$1")) : !1,
                v = !1,
                m = [0, 0, 0],
                g = null;
            if (typeof f.plugins != e && typeof f.plugins[n] == t) g = f.plugins[n].description, g && (typeof f.mimeTypes == e || !f.mimeTypes[i] || !! f.mimeTypes[i].enabledPlugin) && (l = !0, v = !1, g = g.replace(/^.*\s+(\S+\s+\S+$)/, "$1"), m[0] = parseInt(g.replace(/^(.*)\..*$/, "$1"), 10), m[1] = parseInt(g.replace(/^.*\.(.*)\s.*$/, "$1"), 10), m[2] = /[a-zA-Z]/.test(g) ? parseInt(g.replace(/^.*[a-zA-Z]+(.*)$/, "$1"), 10) : 0);
            else if (typeof u.ActiveXObject != e) try {
                var y = new ActiveXObject(r);
                y && (g = y.GetVariable("$version"), g && (v = !0, g = g.split(" ")[1].split(","), m = [parseInt(g[0], 10), parseInt(g[1], 10), parseInt(g[2], 10)]))
            } catch (b) {}
            return {
                w3: s,
                pv: m,
                wk: d,
                ie: v,
                win: h,
                mac: p
            }
        }(),
        N = function() {
            if (!T.w3) return;
            (typeof a.readyState != e && a.readyState == "complete" || typeof a.readyState == e && (a.getElementsByTagName("body")[0] || a.body)) && C(), b || (typeof a.addEventListener != e && a.addEventListener("DOMContentLoaded", C, !1), T.ie && T.win && (a.attachEvent(o, function() {
                a.readyState == "complete" && (a.detachEvent(o, arguments.callee), C())
            }), u == top &&
                function() {
                    if (b) return;
                    try {
                        a.documentElement.doScroll("left")
                    } catch (e) {
                        setTimeout(arguments.callee, 0);
                        return
                    }
                    C()
                }()), T.wk &&
                function() {
                    if (b) return;
                    if (!/loaded|complete/.test(a.readyState)) {
                        setTimeout(arguments.callee, 0);
                        return
                    }
                    C()
                }(), L(C))
        }(),
        J = function() {
            T.ie && T.win && window.attachEvent("onunload", function() {
                var e = d.length;
                for (var t = 0; t < e; t++) d[t][0].detachEvent(d[t][1], d[t][2]);
                var n = p.length;
                for (var r = 0; r < n; r++) I(p[r]);
                for (var i in T) T[i] = null;
                T = null;
                for (var s in avpw_swfobject) avpw_swfobject[s] = null;
                avpw_swfobject = null
            })
        }();
    return {
        registerObject: function(e, t, n, r) {
            if (T.w3 && e && t) {
                var i = {};
                i.id = e, i.swfVersion = t, i.expressInstall = n, i.callbackFn = r, h[h.length] = i, V(e, !1)
            } else r && r({
                success: !1,
                id: e
            })
        },
        getObjectById: function(e) {
            if (T.w3) return _(e)
        },
        embedSWF: function(n, r, i, s, o, u, a, f, l, c) {
            var h = {
                success: !1,
                id: r
            };
            T.w3 && !(T.wk && T.wk < 312) && n && r && i && s && o ? (V(r, !1), k(function() {
                i += "", s += "";
                var p = {};
                if (l && typeof l === t) for (var d in l) p[d] = l[d];
                p.data = n, p.width = i, p.height = s;
                var v = {};
                if (f && typeof f === t) for (var m in f) v[m] = f[m];
                if (a && typeof a === t) for (var g in a) typeof v.flashvars != e ? v.flashvars += "&" + g + "=" + a[g] : v.flashvars = g + "=" + a[g];
                if (W(o)) {
                    var y = j(p, v, r);
                    p.id == r && V(r, !0), h.success = !0, h.ref = y
                } else {
                    if (u && D()) {
                        p.data = u, P(p, v, r, c);
                        return
                    }
                    V(r, !0)
                }
                c && c(h)
            })) : c && c(h)
        },
        switchOffAutoHideShow: function() {
            x = !1
        },
        ua: T,
        getFlashPlayerVersion: function() {
            return {
                major: T.pv[0],
                minor: T.pv[1],
                release: T.pv[2]
            }
        },
        hasFlashPlayerVersion: W,
        createSWF: function(e, t, n) {
            return T.w3 ? j(e, t, n) : undefined
        },
        showExpressInstall: function(e, t, n, r) {
            T.w3 && D() && P(e, t, n, r)
        },
        removeSWF: function(e) {
            T.w3 && I(e)
        },
        createCSS: function(e, t, n, r) {
            T.w3 && X(e, t, n, r)
        },
        addDomLoadEvent: k,
        addLoadEvent: L,
        getQueryParamValue: function(e) {
            var t = a.location.search || a.location.hash;
            if (t) {
                /\?/.test(t) && (t = t.split("?")[1]);
                if (e == null) return $(t);
                var n = t.split("&");
                for (var r = 0; r < n.length; r++) if (n[r].substring(0, n[r].indexOf("=")) == e) return $(n[r].substring(n[r].indexOf("=") + 1))
            }
            return ""
        },
        expressInstallCallback: function() {
            if (w) {
                var e = R(s);
                e && v && (e.parentNode.replaceChild(v, e), m && (V(m, !0), T.ie && T.win && (v.style.display = "block")), g && g(y)), w = !1
            }
        }
    }
}();
(function(b) {
    /*b.build = {
        version: "2.2.1.70",
        closureCompiled: !0,
        bundled: !1,
        imgrecvServer: "http://featherservices.aviary.com/FeatherReceiver.aspx",
        imgrecvServer_SSL: "https://featherservices.aviary.com/FeatherReceiver.aspx",
        inAppPurchaseFrameURL: "http://purchases.viary.com/gateway.aspx?p=flickr",
        imgrecvBase: "http://featherservices.aviary.com/",
        imgrecvBase_SSL: "https://featherservices.aviary.com/",
        imgtrackServer: "http://featherservices.aviary.com/featherlog.aspx",
        imgtrackServer_SSL: "https://featherservices.aviary.com/featherlog.aspx",
        filterServer: "http://featherservices.aviary.com/moa.ashx",
        filterServer_SSL: "https://featherservices.aviary.com/moa.ashx",
        flashGatewayServer: "http://featherservices.aviary.com/gateway.aspx",
        flashGatewayServer_SSL: "https://featherservices.aviary.com/gateway.aspx",
        jsonp_imgserver: "http://featherservices.aviary.com/imgjsonpserver.aspx",
        jsonp_imgserver_SSL: "https://featherservices.aviary.com/imgjsonpserver.aspx",
        featherTargetAnnounce: "http://featherservices.aviary.com/feather_target_announce.html",
        featherTargetAnnounce_SSL: "https://featherservices.aviary.com/feather_target_announce.html",
        featherFilterAnnounce: "http://featherservices.aviary.com/feather_filter_announce.html",
        featherFilterAnnounce_SSL: "https://featherservices.aviary.com/feather_filter_announce.html",
        proxyServer: "http://featherservices.aviary.com/proxy.aspx",
        proxyServer_SSL: "https://featherservices.aviary.com/proxy.aspx",
        feather_baseURL: "http://feather.aviary.com/2.2.1.70/",
        feather_baseURL_SSL: "https://dme0ih8comzn4.cloudfront.net/2.2.1.70/",
        feather_stickerURL: "http://feather.aviary.com/stickers/",
        feather_stickerURL_SSL: "https://dme0ih8comzn4.cloudfront.net/stickers/",
        googleTracker: "UA-84575-16",
        MINIMUM_FLASH_PLAYER_VERSION: "10.2.0"
    }*/
    b.build = {
        version: "2.2.1.70",
        closureCompiled: !0,
        bundled: !1,
        imgrecvServer: featherEditorConfig['imgrecvServer'],

        imgrecvBase: featherEditorConfig['imgrecvBase'],

        imgtrackServer: featherEditorConfig['imgtrackServer'],


        jsonp_imgserver: featherEditorConfig['jsonp_imgserver'],

        featherTargetAnnounce: featherEditorConfig['featherTargetAnnounce'],

        featherFilterAnnounce: featherEditorConfig['featherFilterAnnounce'],

        feather_baseURL: featherEditorConfig['feather_baseURL']
    }
})(AV = window.AV || {});
(function(b, c, d) {
    b.AV = b.AV || {};
    var a = b.AV;
    a.util = {
        getX: function(a) {
            for (var b = 0; null != a;) b += a.offsetLeft, a = a.offsetParent;
            return b
        },
        getY: function(a) {
            for (var b = 0; null != a;) b += a.offsetTop, a = a.offsetParent;
            return b
        },
        getTouch: function(a) {
            a.originalEvent && (a = a.originalEvent);
            return a.changedTouches && 1 == a.changedTouches.length ? a.changedTouches[0] : a.touches && 1 == a.touches.length ? a.touches[0] : !1
        },
        getScaledDims: function(a, b, e, c) {
            var c = c || e,
                d = a,
                h = b,
                i = a / b;
            if (a > e || b > c) a - e > i * (b - c) ? (d = e, h = e * b / a + 0.5 | 0) : (d = c * i + 0.5 | 0, h = c);
            return {
                width: d,
                height: h
            }
        },
        nextFrame: function(a) {
            setTimeout(a, 1)
        },
        getDomain: function(a, b) {
            var e, c, d;
            e = "http://" == a.substr(0, 7) ? 7 : "https://" == a.substr(0, 8) ? 8 : "ftp://" == a.substr(0, 6) ? 6 : 0;
            c = a.indexOf("/", e); - 1 == c && (c = a.length);
            b || (d = a.lastIndexOf(".", c), d = a.lastIndexOf(".", d - 1), e = -1 == d ? e : d + 1);
            return a.substring(e, c)
        },
        extend: function() {
            var a, b, e, c, d, h = arguments[0] || {},
                i = 1,
                j = arguments.length,
                m = !1;
            "boolean" === typeof h && (m = h, h = arguments[1] || {}, i = 2);
            "object" !== typeof h && !jQuery.isFunction(h) && (h = {});
            j === i && (h = this, --i);
            for (; i < j; i++) if (null != (a = arguments[i])) for (b in a) e = h[b], c = a[b], h !== c && (m && c && (jQuery.isPlainObject(c) || (d = jQuery.isArray(c))) ? (d ? (d = !1, e = e && jQuery.isArray(e) ? e : []) : e = e && jQuery.isPlainObject(e) ? e : {}, h[b] = jQuery.extend(m, e, c)) : void 0 !== c && (h[b] = c));
            return h
        },
        findItemByKeyValueFromArray: function(a, b, e) {
            var c, d;
            for (c = 0; c < e.length; c++) if (e[c] && e[c][a] && e[c][a] === b) {
                d = e[c];
                break
            }
            return d
        },
        loadFile: function() {
            var a, b, e = 0,
                c;
            a = function(a, f) {
                if (f) {
                    var e = function(a) {
                        (4 == this.readyState || "complete" == this.readyState || "loaded" == this.readyState) && f(a)
                    };
                    "Microsoft Internet Explorer" == navigator.appName ? a.onreadystatechange = e : a.onload = f
                }
            };
            return function(l, h, i) {
                var j;
                "js" == h ? (j = d.createElement("script"), j.setAttribute("type", "text/javascript"), a(j, i), j.setAttribute("src", l)) : "css" == h ? d.createStyleSheet ? d.createStyleSheet(l, e++) : (j = d.createElement("link"), j.setAttribute("rel", "stylesheet"), j.setAttribute("type", "text/css"), j.setAttribute("href", l)) : "img" == h && (j = d.createElement("img"), a(j, i), j.setAttribute("src", l));
                j && (b = b || d.getElementsByTagName("head")[0], "js" == h ? b.appendChild(j) : "css" == h && (c = c || d.createDocumentFragment(), c.appendChild(j), b.insertBefore(j, void 0)));
                return j
            }
        }(),
        getImageElem: function(a) {
            return "string" == typeof a ? d.getElementById(a) : a.length ? a[0] : a
        },
        getImageId: function(a) {
            return "string" == typeof a ? a : a.id
        },
        imgOnLoad: function(a, b) {
            var e = avpw$(a);
            e.load(b);
            (!0 == e[0].complete || 4 == e[0].readyState || "complete" == e[0].readyState || "loaded" == e[0].readyState) && e.trigger("load")
        },
        color_is_white: function(a) {
            a = a.toLowerCase();
            return "#fff" == a || "#ffffff" == a || "white" == a || "rgb(255,255,255)" == a || "rgb(255, 255, 255)" == a
        },
        color_is_light: function(a) {
            return "#fff" == a || "#ffffff" == a || "rgb(255, 255, 255)" == a
        },
        color_expand: function(a) {
            var b, e;
            4 == a.length && (b = a.charAt(1), e = a.charAt(2), a = a.charAt(3), a = "#" + b + b + e + e + a + a);
            return a
        },
        color_to_array: function(f) {
            var b, e, c;
            "#" == f.charAt(0) ? (f = a.util.color_expand(f), b = parseInt(f.substr(1, 2), 16), e = parseInt(f.substr(3, 2), 16), c = parseInt(f.substr(5, 2), 16)) : "r" == f.charAt(0).toLowerCase() && (f = a.util.rgb_to_color(f), b = parseInt(f.substr(1, 2), 16), e = parseInt(f.substr(3, 2), 16), c = parseInt(f.substr(5, 2), 16));
            return f = [b, e, c, 1]
        },
        array_to_color: function(f) {
            f = a.util.array_to_rgb(f);
            return f = a.util.rgb_to_color(f)
        },
        array_to_rgb: function(a) {
            var b = "rgb(0,0,0)";
            a.join && (3 < a.length && (a = a.slice(0, 3)), b = "rgb(" + a.join(",") + ")");
            return b
        },
        color_to_rgb: function(f) {
            f = a.util.color_to_array(f);
            return f = a.util.array_to_rgb(f)
        },
        rgb_to_color: function(a) {
            var b, e;
            return (e = a.match(/\s*rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/)) ? (a = parseInt(e[1]).toString(16), 1 == a.length && (a = "0" + a), b = parseInt(e[2]).toString(16), 1 == b.length && (b = "0" + b), e = parseInt(e[3]).toString(16), 1 == e.length && (e = "0" + e), "#" + a + b + e) : a
        },
        color_to_int: function(b) {
            b = a.util.color_expand(b);
            b = a.util.rgb_to_color(b);
            return parseInt(b.substr(1), 16)
        }
    };
    return b
})(this, "undefined" !== typeof window ? window : {}, "undefined" !== typeof document ? document : {});
"undefined" === typeof AV && (AV = {});
AV.FlashAPI = function() {
    var b = null,
        c = function() {
            return function(a, b, e, c, d) {
                var d = d || {},
                    h = {
                        quality: "high",
                        bgcolor: "#808080",
                        allowscriptaccess: "always",
                        allowfullscreen: "true"
                    },
                    i = {};
                i.id = a;
                i.name = a;
                AV.msie && 9 > AV.msie && (i.align = "center");
                var j = a + "Content",
                    m = d,
                    d = {};
                if (i && "object" === typeof i) for (var o in i) d[o] = i[o];
                d.data = b || a + ".swf";
                d.width = (e || "100%") + "";
                d.height = (c || "100%") + "";
                a = {};
                if (h && "object" === typeof h) for (var u in h) a[u] = h[u];
                if (m && "object" === typeof m) for (var s in m) a.flashvars = "undefined" != typeof a.flashvars ? a.flashvars + ("&" + s + "=" + m[s]) : s + "=" + m[s];
                if (h = document.getElementById(j)) if ("undefined" == typeof d.id && (d.id = j), AV.msie) {
                    var p = "",
                        n;
                    for (n in d) d[n] != Object.prototype[n] && ("data" == n.toLowerCase() ? a.movie = d[n] : "styleclass" == n.toLowerCase() ? p += ' class="' + d[n] + '"' : "classid" != n.toLowerCase() && (p += " " + n + '="' + d[n] + '"'));
                    n = "";
                    for (var q in a) a[q] != Object.prototype[q] && (n += '<param name="' + q + '" value="' + a[q] + '" />');
                    h.outerHTML = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"' + p + ">" + n + "</object>";
                    "undefined" == typeof objIdArr && (objIdArr = []);
                    objIdArr[objIdArr.length] = d.id
                } else {
                    q = document.createElement("object");
                    q.setAttribute("type", "application/x-shockwave-flash");
                    for (var r in d) d[r] != Object.prototype[r] && ("styleclass" == r.toLowerCase() ? q.setAttribute("class", d[r]) : "classid" != r.toLowerCase() && q.setAttribute(r, d[r]));
                    for (p in a) a[p] != Object.prototype[p] && "movie" != p.toLowerCase() && (d = q, n = p, r = a[p], j = document.createElement("param"), j.setAttribute("name", n), j.setAttribute("value", r), d.appendChild(j));
                    h.parentNode.replaceChild(q, h)
                }
            }
        }(),
        d = function() {};
    d.prototype = function() {
        return {
            getPlugins: function() {
                return []
            },
            createCanvas: function() {
                this.initsize ? c("canvas", AV.build.feather_baseURL + "canvas.swf", this.initsize.width, this.initsize.height) : c("canvas", AV.build.feather_baseURL + "canvas.swf");
                c("avpw_flashThumb1", AV.build.feather_baseURL + "Thumb.swf", "64px", "64px", {
                    callback: "AV.FlashAPI.onThumbClick",
                    uid: "avpw_flashThumb1"
                });
                c("avpw_flashThumb2", AV.build.feather_baseURL + "Thumb.swf", "64px", "64px", {
                    callback: "AV.FlashAPI.onThumbClick",
                    uid: "avpw_flashThumb2"
                });
                c("avpw_flashThumb3", AV.build.feather_baseURL + "Thumb.swf", "64px", "64px", {
                    callback: "AV.FlashAPI.onThumbClick",
                    uid: "avpw_flashThumb3"
                });
                c("avpw_flashThumb4", AV.build.feather_baseURL + "Thumb.swf", "64px", "64px", {
                    callback: "AV.FlashAPI.onThumbClick",
                    uid: "avpw_flashThumb4"
                })
            },
            onSave: function(a) {
                return this.onEndEditing(a)
            },
            onEndEditing: function(a) {
                a.commit(AV.FlashAPI.constants.UPLOAD_TO_AVIARY, {
                    url: "http://test.viary.com/apps/xmlapi/receiver.aspx?auid=aviaryframework",
                    name: "image2"
                })
            },
            onCommitComplete: function(a) {
                AV.controlsWidget_onImageSaved(a.url, a.hiresurl)
            },
            _onComponentLoaded: function(a) {
                switch (a) {
                    case "canvas":
                        a = document.getElementById(a), AV.FlashAPI._onCanvasLoaded(a)
                }
            },
            _onComponentReady: function(a) {
                var b = AV.paintWidgetInstance;
                "canvas" === a && b.canvasReadyCallback && b.canvasReadyCallback.resolve()
            }
        }
    }();
    var a = {
        listenerList: {},
        addListener: function(a, b) {
            this.listenerList.hasOwnProperty(a) || (this.listenerList[a] = []);
            this.listenerList[a].push(b)
        },
        removeAllListeners: function() {
            this.listenerList = {}
        },
        doCallbacks: function(a) {
            var b, e;
            if (this.listenerList.hasOwnProperty(a)) {
                e = this.listenerList[a];
                for (b = 0; b < e.length; b++) e[b](a)
            }
        },
        shutdown: function() {
            this.removeAllListeners()
        }
    };
    return function() {
        var c = !1,
            g = !1;
        return {
            constants: {
                UPLOAD_TO_SERVER: "UPLOAD_TO_SERVER",
                UPLOAD_TO_AVIARY: "UPLOAD_TO_AVIARY",
                GET_IMAGE_DATA: "GET_IMAGE_DATA",
                REPLACE_IMAGE: "REPLACE_IMAGE"
            },
            mapToFlashToolName: function(a) {
                switch (a) {
                    case "rotate":
                        return "rotate90"
                }
                return a
            },
            mapFromFlashToolName: function(a) {
                switch (a) {
                    case "rotate90":
                        return "rotate"
                }
                return a
            },
            customBridge: function(a) {
                var b = function() {};
                b.prototype = new d;
                b.prototype.constructor = d;
                for (var c in a) b.prototype[c] = a[c];
                return b
            },
            activate: function(a) {
                this.bridge = a || new d;
                this.goldenEggCallback = null
            },
            setHiresSize: function(a, b) {
                this.canvas && this.canvas.setHiresSize(a, b)
            },
            hiresSizeChanged: function(a, b) {
                AV.paintWidgetInstance && AV.paintWidgetInstance.actions && AV.paintWidgetInstance.actions.setDims(a, b)
            },
            startEditing: function(a) {
                this.canvas = null;
                this.active_target = a;
                this.bridge.createCanvas()
            },
            restartEditing: function(a) {
                this.active_target =
                    a;
                this._setupEditing()
            },
            save: function() {
                this.bridge.onSave(this.canvas)
            },
            close: function() {
                a.shutdown()
            },
            runGoldenEgg: function(a, b, c, f) {
                this.goldenEggCallback = f;
                this.canvas.renderGoldenEgg(a, b, c)
            },
            doCrop: function() {
                this.canvas.executeCrop()
            },
            endEditing: function() {
                this.bridge.onEndEditing(this.canvas);
                this.goldenEggCallback = null
            },
            activatePlugin: function(a) {
                this.canvas.activatePlugin(a)
            },
            deactivatePlugin: function(a) {
                this.canvas.deactivatePlugin(a)
            },
            changeProperty: function(a, b) {
                this.canvas.changeProperty(a, b)
            },
            applyPreviewFromPlugin: function(a) {
                this.canvas.applyPreviewFromPlugin(a)
            },
            commitChangesFromPlugin: function(a) {
                this.canvas.commitChangesFromPlugin(a)
            },
            discardChangesFromPlugin: function(a) {
                this.canvas.discardChangesFromPlugin(a)
            },
            resizeCanvas: function(a, b) {
                var c = this.canvas;
                c.width = a + "px";
                c.height = b + "px";
                AV.paintWidgetInstance && AV.paintWidgetInstance.setDimensions(a, b)
            },
            hideCanvas: function() {
                this.canvas && (this.canvas.style.visibility = "hidden")
            },
            showCanvas: function() {
                this.canvas && (this.canvas.style.visibility = "visible")
            },
            executePlugin: function() {
                this.canvas.executePlugin()
            },
            renderPreview: function(a, b) {
                this.canvas.renderPreview(a, b)
            },
            getDynamicPropertyDefaultValue: function(a) {
                return this.canvas.getDynamicPropertyDefaultValue(a)
            },
            syncProperty: function() {},
            syncPreview: function() {},
            setCanvasDataArray: function(a, b, c) {
                AV.canvasDataReceiver && AV.canvasDataReceiver.apply(this, [a, b, c])
            },
            onThumbClick: function(b) {
                a.doCallbacks(b)
            },
            addThumbClickListener: function(b, c) {
                a.addListener(b, c)
            },
            removeAllThumbClickListeners: function() {
                a.removeAllListeners()
            },
            setThumb: function(a, b) {
                var c = document.getElementById("avpw_flashThumb" + a);
                c && c.refreshThumb(b)
            },
            getHistory: function() {
                return this.canvas.getHistory()
            },
            getFlashGatewayServer: function() {
                return AV.build.flashGatewayServer
            },
            getHiResStickerUrl: function(a) {
                return AV.paintWidgetInstance && AV.paintWidgetInstance.overlayRegistry ? AV.paintWidgetInstance.overlayRegistry.getHiRes(a) : null
            },
            getImageData: function(a, c) {
                c && "function" === typeof c && (b = c);
                this.canvas.commit(AV.FlashAPI.constants.GET_IMAGE_DATA, {})
            },
            getMaxSize: function() {
                return AV.launchData.maxEditSize || AV.launchData.maxSize
            },
            getMaxBitmapSize: function() {
                var a = 0;
                "aviary" == AV.launchData.openType && (a = AV.launchData.maxSize);
                return a
            },
            _cropSelectionStarted: function() {},
            _onPreviewRendered: function() {},
            _onPluginLoaded: function(a) {
                var b = AV.paintWidgetInstance;
                b.moduleLoadedCallback && b.moduleLoadedCallback[a] && b.moduleLoadedCallback[a].resolve()
            },
            _onImageLoaded: function(a, b) {
                AV.paintWidgetLauncher_Flash_stage2 && AV.paintWidgetLauncher_Flash_stage2(a, b)
            },
            _onGoldenEggComplete: function() {
                this.goldenEggCallback && this.goldenEggCallback()
            },
            _onCanvasLoaded: function(a) {
                this.canvas = a;
                this._setupEditing(AV.launchData.url || null)
            },
            _onCommitComplete: function(a) {
                this.bridge.onCommitComplete(a)
            },
            _onGetImageDataComplete: function(a) {
                b && (b.apply(this, [a]), b = null)
            },
            _setupEditing: function(a) {
                var b = this.bridge.getPlugins(),
                    a = a || this.active_target.src;
                this.canvas.setup(a, AV.build.proxyServer, b)
            },
            _canUndo: function() {
                return c
            },
            _onUndo: function() {
                this.canvas.undo()
            },
            _canRedo: function() {
                return g
            },
            _onRedo: function() {
                this.canvas.redo()
            },
            _onHistoryChange: function(a, b) {
                c = a;
                g = b;
                AV.controlsWidgetInstance && AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "updateUndoRedo", [a, b])
            },
            setCheckpoint: function(a) {
                this.canvas.setCheckpoint(a)
            },
            isACheckpoint: function(a) {
                return this.canvas.isACheckpoint(a)
            },
            undoToCheckpoint: function() {
                this.canvas.undoToCheckpoint()
            },
            redoToCheckpoint: function() {
                return this.canvas.redoToCheckpoint()
            },
            truncateActionList: function() {
                this.canvas.truncateActionList()
            },
            _onError: function(a, b) {
                "BAD_IMAGE" === a && (AV.paintWidgetCloser(!0), AV.launchData.url && (a = "BAD_URL"));
                AV.errorNotify(a, b)
            },
            init: function(a) {
                AV.FlashAPI.activate(function() {
                    if (!a) return new d;
                    if (a.plugins) {
                        for (var b = [], c = a.plugins, f = 0; f < c.length; ++f) {
                            var g = AV.FlashAPI.mapToFlashToolName(c[f]),
                                j = AV.toolDefaults[c[f]];
                            j && j.files && b.push({
                                id: g,
                                presets: j.presetsFlash,
                                files: j.files
                            })
                        }
                        0 < b.length && (a.getPlugins = function() {
                            return b
                        });
                        delete a.plugins
                    }
                    if (a.action) {
                        var m = a.action;
                        a.onSave = function(a) {
                            var b = m.type;
                            m = AV.util.extend(m, {
                                posturl_: AV.launchData.postUrl,
                                hiresUrl_: AV.launchData.hiresUrl,
                                postdata_: AV.launchData.postData,
                                partnerHash_: AV.launchData.apiKey,
                                contenttype_: AV.launchData.fileFormat,
                                jpgQuality_: AV.launchData.jpgQuality,
                                debug_: AV.launchData.debug,
                                asyncSave_: AV.launchData.asyncSave,
                                signature_: AV.launchData.signature,
                                timestamp_: AV.launchData.timestamp,
                                useCustomFileExpiration_: AV.launchData.useCustomFileExpiration
                            });
                            a.commit(b, m)
                        };
                        delete a.action
                    }
                    c = function() {};
                    c.prototype = new d;
                    c.prototype.constructor = d;
                    for (var o in a) c.prototype[o] = a[o];
                    return new c
                }())
            }
        }
    }()
}();
(function(b) {
    b.AV = b.AV || {};
    var c = b.AV;
    c.ImageSizeTracker = function() {
        var b = this;
        b.setImageScaledIndicator = function() {
            c.controlsWidgetInstance.layoutNotify(c.launchData.openType, "updateImageScaledIndicator")
        };
        b.setOrigSize = function(a, f, g) {
            var e;
            if (a.hiresWidth && a.hiresHeight) e = parseInt(a.hiresWidth, 10), a = parseInt(a.hiresHeight, 10);
            else if (a.hiresUrl) e = f.width, a = f.height;
            else if (a.displayImageSize) e = g.width, a = g.height;
            else
                return null;
            c.paintWidgetInstance.actions.setOrigSize(e, a);
            b.setImageScaledIndicator();
            return {
                width: e,
                height: a
            }
        };
        b.isDisplayingImageSize = function(a) {
            return a.hiresWidth || a.hiresHeight || a.displayImageSize
        };
        b.isUsingHiResDimensions = function(a) {
            return a.hiresWidth || a.hiresHeight || a.displayImageSize && a.hiresUrl
        }
    };
    return b
})(this, "undefined" !== typeof window ? window : {}, "undefined" !== typeof document ? document : {});
"undefined" == typeof AV && (AV = {});
AV.usageTracker = function() {
    var b = null,
        c = {},
        d = 0,
        a = [],
        f, g = !0,
        e = !1,
        k = {},
        l, h, i, j, m, o, u = function() {
            AV.controlsWidgetInstance && AV.usageTracker.submit("close")
        },
        s = function() {
            window._gaq = window._gaq || [];
            if ("undefined" === typeof _gat) {
                var a = document.createElement("script");
                a.type = "text/javascript";
                a.async = !0;
                a.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
                var b = document.getElementsByTagName("script")[0];
                b.parentNode.insertBefore(a, b)
            }
            e || (_gaq.push(["Feather._setAccount", AV.build.googleTracker]), _gaq.push(["Feather._setCustomVar", 1, "apikey", AV.launchData.apiKey]), _gaq.push(["Feather._setCustomVar", 2, "featherversion", AV.build.version]), _gaq.push(["Feather._setCustomVar", 3, "sessionid", this.getUUID()]), _gaq.push(["Feather._setCustomVar", 4, "language", AV.launchData.language]), e = !0)
        },
        p = function() {
            var a;
            avpw$.each(c, function(b, c) {
                c = c || 0;
                for (a = 0; a < c; a++) _gaq.push(["Feather._trackEvent", "toolusage", b, "count", 1])
            })
        };
    k.setup = function() {
        avpw$(window).bind("unload", u)
    };
    k.shutdown =

        function() {
            avpw$(window).unbind("unload", u)
        };
    k.clear = function() {
        b = null;
        c = {};
        d = 0;
        a = []
    };
    k.getUUID = function() {
        return b ? b : b = Math.floor(4294967295 * Math.random()).toString(16) + Math.floor(4294967295 * Math.random()).toString(16)
    };
    k.addUsage = function(a, b) {
        b || (b = 1);
        c[a] = void 0 === c[a] ? b : c[a] + b;
        d += b
    };
    k.setPageCount = function(b) {
        var c;
        a = Array(b);
        for (c = 0; c < b; c++) a[c] = 0
    };
    k.addPageHit = function(b) {
        b !== f && a[b]++;
        f = b
    };
    k.submit = function(b, f) {return;
        var e = null;
        if ("firstclick" === b) if (g) g = !1;
        else
            return;
        s.call(this);
        "launch" === b ? _gaq.push(["Feather._trackPageview"]) : _gaq.push(["Feather._trackEvent", "submit", b, "totalcount", d]);
        l || (l = avpw$("#avpw_track_form"), h = avpw$("#avpw_track_form_action"), i = avpw$("#avpw_track_form_sessionid"), j = avpw$("#avpw_track_form_apikey"), m = avpw$("#avpw_track_form_data"), o = avpw$("#avpw_img_track_target_holder"));
        if (AV.build.imgtrackServer && AV.JSON) {
            h.val(b);
            i.val(this.getUUID());
            j.val(AV.launchData.apiKey);
            switch (b) {
                case "close":
                    e = {
                        dataver: 1,
                        opentype: AV.launchData.openType,
                        pagehits: a,
                        toolusage: c
                    };
                    m.val(AV.JSON.stringify(e));
                    p();
                    break;
                case "firstclick":
                    e = {
                        toolusage: c
                    };
                    m.val(AV.JSON.stringify(e));
                    p();
                    break;
                case "openadvancedtools":
                case "closeadvancedtools":
                    e = {
                        toolusage: c
                    };
                    f && f.toolName && (e = {
                        toolname: f.toolName
                    }, m.val(AV.JSON.stringify(e)), _gaq.push(["Feather._trackEvent", "toolusage", f.toolName, b]));
                    break;
                default:
                    m.val("")
            }
            o.html(AV.buildHiddenFrame("avpw_img_track_target"));
            avpw$("#avpw_img_track_target").load(function() {
                AV.util.nextFrame(function() {
                    o && o.length && o.empty()
                })
            });
            AV.util.nextFrame(function() {
                l && (l.length && o && o.length && o.children().length > 0) && l.submit()
            })
        }
    };
    return k
}();
AV.PurchaseManager = function(b) {
    var c = {
            EFFECT: "effect",
            STICKER: "sticker",
            FRAME: "frame"
        },
        d = {},
        a, f = function(a) {
            var b = a.resourceUrl;
            b && (-1 === b.indexOf("http") && (b = AV.build.feather_baseURL + b), a.resourceUrl = b)
        },
        g = function(b) {
            for (var c = 0; c < a.length; c++) for (var d = 0; d < b.length; d++) b[d].assetId === a[c].assetId && (a[c].purchased = !0, AV.util.extend(a[c], b[d]), f(a[c]))
        },
        e = function(b) {
            var f = [];
            if (b) for (var b = c[b], d = 0; d < a.length; d++) a[d].assetType === b && f.push(a[d]);
            else f = a.slice(0);
            return f
        },
        k = function(b, c) {
            if (!a) return h.getAssets(b, function(d) {
                a = d;
                for (d = 0; d < a.length; d++) f(a[d]);
                h.getPurchasedAssets(b, c)
            }), !1;
            try {
                return window.avpw_purchase_frame.postMessage("getPurchasedAssets", "*"), d.getPurchasedAssets = function(a) {
                    g(a);
                    c && c.apply(this, [e(b)])
                }, !0
            } catch (l) {
                return !1
            }
        },
        l = function(b, c) {
            a = [{
                needsPurchase: !1,
                assetId: "original_effects",
                assetType: "effect",
                displayName: "Original",
                resourceUrl: "js/effects_original_effects.js"
            }, {
                needsPurchase: !1,
                assetId: "enhance",
                assetType: "effect",
                displayName: "Enhance",
                resourceUrl: "js/effects_enhance.js"
            }, {
                needsPurchase: !1,
                assetId: "original_stickers",
                assetType: "sticker",
                displayName: "Original",
                resourceUrl: "js/stickers_original_stickers.js"
            }, {
                needsPurchase: !1,
                assetId: "borders",
                assetType: "frame",
                displayName: "",
                resourceUrl: "js/borders.js"
            }];
            c && AV.util.nextFrame(function() {
                for (var d = 0; d < a.length; d++) f(a[d]);
                c.apply(this, [e(b)])
            });
            return !0
        },
        h = this;
    h.getAssets = function(a, b) {
        window.setTimeout(function() {
            try {
                return window.avpw_purchase_frame.postMessage("getAssets", "*"), d.getAssets = b, !0
            } catch (a) {
                return !1
            }
        }, 2E3)
    };
    h.getPurchasedAssets = b ? k : l;
    h.getById = function(b) {
        for (var c = 0; c < a.length; c++) if (a[c].assetId === b) return a[c]
    };
    h.showAssetPurchaseView = function(a, b) {
        var c = {
                messageName: "showAssetPurchaseView",
                assetId: a
            },
            c = AV.JSON.stringify(c);
        try {
            return window.avpw_purchase_frame.postMessage(c, "*"), d.showAssetPurchaseView = function() {
                h.showAssetPurchasePopup(a, b)
            }, !0
        } catch (f) {
            return !1
        }
    };
    h.showAssetPurchasePopup = function(a, b) {
        avpw$("#avpw_purchase_frame").show();
        AV.controlsWidgetInstance.messager.show("avpw_purchase_pack", !0);
        avpw$("#avpw_purchase_pack_close").bind("click", h.hideAssetPurchasePopup);
        d.getPurchasedAssets = function(c) {
            g(c);
            c = h.getById(a);
            b && ("function" === typeof b && c) && b.apply(this, [c]);
            h.hideAssetPurchasePopup()
        }
    };
    h.hideAssetPurchasePopup = function() {
        d.getPurchasedAssets = null;
        avpw$("#avpw_purchase_frame").hide();
        AV.controlsWidgetInstance.messager.hide("avpw_purchase_pack");
        avpw$("#avpw_purchase_pack_close").unbind("click", h.hideAssetPurchasePopup)
    };
    h.messageHandler = function(a) {
        a.messageName && d[a.messageName] && d[a.messageName].apply(this, [a.data])
    };
    b && (window.avpw_purchase_frame || avpw$("#avpw_messaging").append(AV.template[AV.launchData.layout].inAppPurchaseFrame({
        src: AV.build.inAppPurchaseFrameURL
    })));
    return h
};
AV.getActiveTools = function(b, c) {
    var d = b;
    "string" === typeof b && (d = b.split(","));
    var a = [],
        f, g = {},
        e;
    if (c) for (e = 0; e < c.length; e++) f = c[e], g[f] = !0;
    for (e = 0; e < d.length; e++) if (f = d[e], !(c && f in g) && ("stickers" === f && (f = "overlay"), "draw" === f && (f = "drawing"), a.push(f), "aviary" !== AV.launchData.openType && "mobile" !== AV.launchData.openType && "overlay" === f && (f = AV.launchData.stickers, f[0] && f[0].contents))) for (var k = 1; k < f.length; k++) a.push("overlay");
    return a
};
AV.paintWidgetGetPopupEmbedDiv = function(b) {
    var c = avpw$("#avpw_canvas_embed_popup");
    if (b) {
        var d = avpw$(b),
            a, f, g = "top left bottom right margin-top margin-right margin-bottom margin-left border-top border-right border-bottom border-left padding-top padding-right padding-bottom padding-left".split(" "),
            e = {
                position: "relative"
            };
        for (a = 0; a < g.length; a++) f = g[a], e[f] = d.css(f);
        d = avpw$(b).css("display");
        if ("" == d || "inline" == d) d = "inline-block";
        e.display = d;
        0 == c.length && (c = document.createElement("div"), c.id = "avpw_canvas_embed_popup");
        avpw$(c).hide().css(e).insertBefore(b)
    }
    return c
};
AV.paintWidgetLauncher = function(b, c) {
    if (!AV.paintWidgetInstance) return AV.usageTracker.clear(), AV.featherUseFlash ? AV.paintWidgetLauncher_Flash(b, c) : AV.paintWidgetLauncher_HTML(b, c)
};
AV.paintWidgetLauncher_Flash = function(b, c) {
    function d() {
        avpw$(e).unbind("load", d);
        AV.msie && 7 == AV.msie && (avpw$("#avpw_controls").css("visibility", "hidden"), avpw$("#avpw_controls").show());
        AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "placeControls_Flash", [AV.launchData.appendTo]);
        AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "enableControls");
        l = AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "getScaledImageDims_Flash", [e]);
        h = l.width;
        i = l.height;
        "aviary" == AV.launchData.openType && (j = h > i ? h : i, AV.launchData.maxEditSize = j);
        "mobile" != AV.launchData.openType && "aviary" != AV.launchData.openType && AV.controlsWidgetInstance.setMinWidth(h);
        AV.paintWidgetInstance = new AV.PaintWidget(h, i);
        AV.paintWidgetInstance.setOrigSize(h, i);
        if ("aviary" == AV.launchData.openType) var a = AV.template[AV.launchData.layout].flashCanvasBox({
            id: "canvasContent"
        });
        else a = document.createElement("div"), a.id = "canvasContent";
        avpw$(k).append(a);
        AV.controlsWidgetInstance.initAllTools.call(AV.controlsWidgetInstance);
        var g = {
            initsize: l,
            plugins: m,
            action: {
                origUrl_: c ? c : f.src,
                sessionID_: AV.usageTracker.getUUID(),
                referrerUrl_: window.location.href,
                type: AV.FlashAPI.constants.UPLOAD_TO_AVIARY,
                url: AV.build.imgrecvServer,
                name: "file"
            }
        };
        AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "hideOriginalImage", [f]);
        "aviary" == AV.launchData.openType && AV.util.nextFrame(function() {
            AV.controlsWidgetInstance.setupScrollPanels()
        });
        "mobile" != AV.launchData.openType && "aviary" != AV.launchData.openType && (AV.controlsWidgetInstance.filterEggs && AV.controlsWidgetInstance.filterEggs.setup.call(AV.controlsWidgetInstance), AV.controlsWidgetInstance.populatePulldown("crop"), AV.controlsWidgetInstance.populatePulldown("resize"), AV.formFields && (AV.formFields.checkbox_style_startup(), AV.formFields.dropdown_style_startup()), AV.controlsWidgetInstance.setupScrollPanels(), AV.controlsWidgetInstance.setLastToolsPage("avpw_aviary_about"));
        AV.setUpOstrich();
        AV.msie && 7 == AV.msie && (avpw$("#avpw_controls").hide(), avpw$("#avpw_controls").css("visibility", "visible"));
        avpw$("#avpw_controls").fadeIn(300);
        AV.launchData.noCloseButton && avpw$("#avpw_control_cancel_pane").css("display", "none");
        setTimeout(function() {
            AV.FlashAPI.init(g);
            AV.FlashAPI.startEditing(b);
            AV.controlsWidgetInstance.initWithPaintWidget(AV.paintWidgetInstance);
            AV.controlsWidgetInstance.setPanelMode(null);
            AV.controlsWidgetInstance.loaderPhase = 1
        }, 300)
    }
    function a() {
        AV.util.imgOnLoad(e, d);
        avpw$(e).attr("src", c ? c : g)
    }
    var f = AV.util.getImageElem(b),
        g = f.src,
        e, k, l, h, i, j, m = AV.getActiveTools(AV.launchData.tools, "whiten barrel bulge pinch warmth frames".split(" "));
    AV.controlsWidgetInstance = new AV.ControlsWidget(null, b, m);
    avpw$(".avpw_isa_previewcanvas").hide();
    k = AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "getEmbedElement", [f]);
    avpw$(k).show();
    window.addEventListener && window.addEventListener("message", AV.controlsWidget_MessageHandler, !1);
    e = document.createElement("img");
    avpw$(e).css({
        display: "block",
        visibility: "hidden",
        position: "absolute"
    }).attr("src", c ? c : f.src);
    AV.build.bundled ? a() : AV.util.loadFile(AV.build.feather_baseURL + "js/featherpaint_flash.js", "js", a)
};
AV.paintWidgetLauncher_Flash_stage2 = function(b, c) {
    AV.controlsWidgetInstance.loaderPhase = 2;
    AV.controlsWidgetInstance.imageSizeTracker.setOrigSize(AV.launchData, {
        width: b,
        height: c
    }, {
        width: AV.paintWidgetInstance.width,
        height: AV.paintWidgetInstance.height
    });
    AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "launchStage2_Flash");
    AV.controlsWidgetInstance.showWaitThrobber(!1);
    avpw$(AV.controlsWidgetInstance.onEggWaitThrobber).hide();
    AV.controlsWidgetInstance.showFlashThumbs(null, !1);
    AV.usageTracker.submit("launch");
    AV.fireLaunchComplete()
};
AV.paintWidgetLauncher_HTML = function(b, c) {
    var d = AV.util.getImageElem(b),
        a, f, g, e;
    e = "mobile" == AV.launchData.openType ? AV.getActiveTools(AV.launchData.tools, ["resize"]) : AV.getActiveTools(AV.launchData.tools);
    AV.isRelaunched && "undefined" != typeof d.avpw_prevURL && (c = d.avpw_prevURL);
    AV.controlsWidgetInstance = new AV.ControlsWidget(null, b, e);
    AV.controlsWidgetInstance.origURL = c ? c : avpw$(d).attr("src");
    AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "placeControls", [AV.launchData.appendTo]);
    AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "enableControls");
    "mobile" != AV.launchData.openType && "aviary" != AV.launchData.openType && AV.setUpOstrich();
    avpw$("#avpw_controls").fadeIn(300);
    ("mobile" == AV.launchData.openType || "aviary" == AV.launchData.openType) && AV.util.nextFrame(function() {
        "mobile" == AV.launchData.openType && AV.setPageWidth(avpw$("#avpw_controls").width());
        AV.controlsWidgetInstance.setupScrollPanels()
    });
    "mobile" != AV.launchData.openType && "aviary" != AV.launchData.openType && (AV.controlsWidgetInstance.filterEggs && AV.controlsWidgetInstance.filterEggs.setup.call(AV.controlsWidgetInstance), AV.controlsWidgetInstance.populatePulldown("crop"), AV.controlsWidgetInstance.populatePulldown("resize"), AV.formFields && (AV.formFields.checkbox_style_startup(), AV.formFields.dropdown_style_startup()), AV.controlsWidgetInstance.setupScrollPanels(), AV.controlsWidgetInstance.setLastToolsPage("avpw_aviary_about"));
    AV.launchData.noCloseButton && avpw$("#avpw_control_cancel_pane").css("display", "none");
    if (d && "canvas" === d.nodeName.toLowerCase()) AV.mockLauncher(d);
    else
        return window.addEventListener("message", AV.controlsWidget_MessageHandler, !1), f = AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "getEmbedElement", [d]), a = document.createElement("img"), a.id = "avpw_temp_loading_image", AV.tempLoadingImageSrc = a.src, avpw$(a).load(function() {
            g = AV.controlsWidgetInstance.getScaledDims(avpw$(d).width(), avpw$(d).height());
            a.width = g.width;
            a.height = g.height;
            AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "getScaledImageDims", [a]);
            avpw$(a).unbind();
            a.style.display = "block";
            avpw$(f).append(a);
            AV.controlsWidgetInstance.showWaitThrobber(true);
            AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "hideOriginalImage", [d]);
            avpw$(f).show();
            AV.util.nextFrame(function() {
                AV.build.bundled ? AV.paintWidgetLauncher_stage2(b, c) : AV.util.loadFile(AV.build.feather_baseURL + "js/featherpaint.js", "js", function() {
                    AV.paintWidgetLauncher_stage2(b, c)
                })
            })
        }).error(function() {
                AV.paintWidgetCloser(true);
                AV.errorNotify("BAD_IMAGE", [c])
            }), a.src = d.src, !1
};
AV.mockLauncher = function(b) {
    var c = AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "getEmbedElement", [b]);
    AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "getScaledImageDims", [b]);
    avpw$(b).detach();
    avpw$(c).append(b);
    avpw$(c).show();
    AV.controlsWidgetInstance.showWaitThrobber(!0);
    AV.util.nextFrame(function() {
        var b = function() {};
        AV.controlsWidgetInstance.initAllTools = function() {
            for (var a in this.activeTools) {
                var b = this.activeTools[a];
                this.tool.hasOwnProperty(b) && this.moduleNotify(b, "init", [this])
            }
        };
        AV.controlsWidgetInstance.initWithPaintWidget = function(a) {
            this.paintWidget = a;
            this.initAllTools();
            this.bindControls();
            this.paintWidget.showWaitThrobber = this.showWaitThrobber.AV_bindInst(this)
        };
        AV.controlsWidgetInstance.loaderPhase = 1;
        AV.paintWidgetInstance = {
            moduleLoaded: function(a, b) {
                return b.call(this)
            },
            setMode: b,
            setCurrentLayerByName: b,
            shutdown: b,
            actions: {
                setCheckpoint: b
            }
        };
        AV.controlsWidgetInstance.initWithPaintWidget(AV.paintWidgetInstance);
        AV.controlsWidgetInstance.showWaitThrobber(!1);
        AV.controlsWidgetInstance.loaderPhase = 2;
        AV.fireLaunchComplete()
    })
};
AV.paintWidgetLauncher_stage2 = function(b, c) {
    var d = AV.util.getImageElem(b),
        a, f, g = function(b) {
            AV.controlsWidgetInstance && AV.paintWidgetInstance && (a = new Image, avpw$(a).load(function() {
                if (AV.controlsWidgetInstance && AV.paintWidgetInstance) {
                    f = AV.controlsWidgetInstance.getScaledDims(a.width, a.height);
                    AV.controlsWidgetInstance.imageSizeTracker.setOrigSize(AV.launchData, a, f);
                    a.width = f.width;
                    a.height = f.height;
                    AV.paintWidgetInstance.setDimensions(f.width, f.height);
                    if (!AV.paintWidgetInstance.setBackground(a)) return AV.paintWidgetCloser(!0), AV.errorNotify("IMAGE_NOT_CLEAN", [c]), !1;
                    AV.paintWidgetInstance.setOrigSize(f.width, f.height);
                    d.src !== c && AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "scaleCanvas");
                    avpw$(AV.paintWidgetInstance.canvas).insertBefore("#avpw_temp_loading_image");
                    e.remove();
                    AV.tempLoadingImageSrc = c;
                    AV.controlsWidgetInstance.showWaitThrobber(!1);
                    AV.controlsWidgetInstance.loaderPhase = 2;
                    AV.launchData.actionListJSON && AV.paintWidgetInstance.actions.importJSON(AV.launchData.actionListJSON)
                }
            }).attr("src", b))
        };
    f = AV.controlsWidgetInstance.getScaledDims(avpw$(d).width(), avpw$(d).height());
    AV.controlsWidgetInstance.loaderPhase = 1;
    "mobile" != AV.launchData.openType && "aviary" != AV.launchData.openType && AV.controlsWidgetInstance.setMinWidth(f.width);
    AV.paintWidgetInstance = new AV.PaintWidget(f.width, f.height);
    AV.controlsWidgetInstance.canvasUI = new AV.PaintUI(AV.paintWidgetInstance.canvas);
    AV.controlsWidgetInstance.initWithPaintWidget(AV.paintWidgetInstance);
    AV.paintWidgetInstance.setOrigSize(f.width, f.height);
    AV.controlsWidgetInstance.imageSizeTracker.setOrigSize(AV.launchData, d, f);
    var e = avpw$("#avpw_temp_loading_image");
    AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "scaleCanvas");
    if (null != c) - 1 === c.indexOf("data:") ? avpw$.ajax({
        type: "GET",
        dataType: "json",
        url: AV.build.jsonp_imgserver + "?callback=?",
        data: {
            url: escape(c)
        },
        success: function(a) {
            g(a.data)
        },
        error: function(a, b) {
            200 === a.status && "parsererror" === b && AV.controlsWidgetInstance && (AV.controlsWidgetInstance.showWaitThrobber(!1), AV.util.nextFrame(function() {
                AV.paintWidgetCloser(!0);
                AV.errorNotify("BAD_URL", [c])
            }))
        }
    }) : g(c);
    else {
        if (!AV.paintWidgetInstance.setBackground(d)) return AV.paintWidgetCloser(!0), AV.errorNotify("IMAGE_NOT_CLEAN", [c]), !1;
        avpw$("#avpw_controls").insertAfter(AV.paintWidgetInstance.canvas);
        avpw$(AV.paintWidgetInstance.canvas).insertBefore(e);
        e.remove();
        AV.tempLoadingImageSrc = d.src;
        AV.controlsWidgetInstance.showWaitThrobber(!1);
        AV.controlsWidgetInstance.loaderPhase = 2;
        AV.launchData.actionListJSON && AV.paintWidgetInstance.actions.importJSON(AV.launchData.actionListJSON)
    }
    AV.usageTracker.submit("launch");
    AV.fireLaunchComplete();
    return !1
};
AV.fireLaunchComplete = function() {
    var b, c, d = !1,
        a = AV.launchData.initTool;
    if (a) {
        c = AV.controlsWidgetInstance.activeTools.length;
        for (b = 0; b < c; b++) if (AV.controlsWidgetInstance.activeTools[b] === a) {
            d = !0;
            break
        }
        d ? AV.controlsWidgetInstance.setActiveTool(a) : AV.errorNotify("UNSUPPORTED_TOOL", [a])
    }
    if ("function" === typeof AV.launchData.onReady) AV.launchData.onReady()
};
AV.paintWidgetShutdown = function() {
    window.removeEventListener && window.removeEventListener("message", AV.controlsWidget_MessageHandler, !1);
    avpw$("#avpw_controls").hide();
    AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "onShutdown");
    if ("function" === typeof AV.launchData.onClose) AV.launchData.onClose(AV.paintWidgetInstance.dirty);
    AV.paintWidgetInstance && AV.paintWidgetInstance.shutdown();
    AV.controlsWidgetInstance && AV.controlsWidgetInstance.shutdown();
    AV.featherUseFlash && AV.FlashAPI.close();
    AV.usageTracker.submit("close");
    AV.paintWidgetInstance = null;
    AV.controlsWidgetInstance = null;
    AV.tempLoadingImageSrc = null
};
AV.paintWidgetCloser = function(b) {
    AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "onClose", [b]);
    b ? (avpw$("#avpw_controls").hide(), AV.paintWidgetShutdown()) : avpw$("#avpw_controls").fadeOut(300, function() {
        AV.paintWidgetInstance && AV.paintWidgetShutdown()
    })
};
AV.controlsWidget_onImageSaved = function(b, c) {
    var d;
    "function" === typeof AV.launchData.onSave && (d = AV.launchData.onSave.apply(AV.launchData, [AV.util.getImageId(AV.controlsWidgetInstance.paintImgIdElem), b, c]));
    AV.controlsWidgetInstance && (AV.util.getImageElem(AV.controlsWidgetInstance.paintImgIdElem).avpw_prevURL = b, AV.controlsWidgetInstance.showView("main"), AV.controlsWidgetInstance.setPanelMode(null), !1 !== d && ("mobile" == AV.launchData.openType || "aviary" == AV.launchData.openType ? AV.controlsWidgetInstance.messager.show("avpw_aviary_beensaved", !0) : (AV.controlsWidgetInstance.setLastToolsPage("avpw_aviary_beensaved"), setTimeout(function() {
        AV.controlsWidgetInstance.didJumpToLastPage = !0;
        AV.controlsWidgetInstance.goToToolsPage(AV.controlsWidgetInstance.toolsPager.getLastPage())
    }, 5))), AV.controlsWidgetInstance.paintWidget.dirty = !1, AV.controlsWidgetInstance.saving = !1)
};
AV.controlsWidget_MessageHandler = function(b) {
    var c = b.data,
        d;
    if ("" != AV.build.feather_baseURL && (d = AV.util.getDomain(b.origin), "aviary.com" != d && ("aviary.local" != d && "viary.com" != d && "amazonaws.com" != d) && (b = AV.util.getDomain(AV.build.feather_baseURL), "" == b || "" == d || b != d))) return;
    if (c.substr && "avpw:" == c.substr(0, 5)) b = c.substr(5).split("---FEATHER-POSTMESSAGE---"), c = b[0], b = b[1], avpw$("#avpw_img_submit_target").unbind("load"), avpw$("#avpw_img_submit_target_holder").empty(), AV.controlsWidget_onImageSaved(c, b);
    else
        try {
            (c = AV.JSON.parse(c)) && c.messageName && AV.controlsWidgetInstance.purchaseManager.messageHandler(c)
        } catch (a) {}
};
AV.ControlsWidget = function(b, c, d) {
    this.maxHeight = this.maxWidth = parseInt(AV.launchData.maxSize);
    this.saving = !1;
    this.origURL = null;
    this.activeTools = d;
    this.didJumpToLastPage = !1;
    this.quitCount = 0;
    AV.usageTracker.setup();
    this.paintImgIdElem = c;
    this.curMode = null;
    if ("aviary" == AV.launchData.openType || "mobile" == AV.launchData.openType) this.purchaseManager = new AV.PurchaseManager(AV.launchData.allowInAppPurchase);
    this.layoutNotify(AV.launchData.openType, "subscribeToResize", ["setupScrollPanels", this.setupScrollPanels.AV_bindInst(this)]);
    this.showView("main", 1);
    b && this.initWithPaintWidget(b);
    b = {
        id: "avpw_tool_spinner",
        lines: 12,
        length: 6,
        width: 2,
        radius: 6,
        color: "#fff",
        speed: 0.5,
        trail: 70
    };
    "mobile" != AV.launchData.openType && (b.color = "#555", b.length = 4);
    this.waitThrobber = new AV.Spinner({
        id: "avpw_canvas_spinner",
        lines: 12,
        length: 6,
        width: 2,
        radius: 6,
        color: "#fff",
        speed: 0.5,
        trail: 70
    });
    this.onEggWaitThrobber = new AV.Spinner(b)
};
AV.ControlsWidget.prototype.tool = {};
AV.ControlsWidget.prototype.layout = {};
AV.ControlsWidget.prototype.moduleNotify = function(b, c, d) {
    return this.objectNotify("tool", b, c, d)
};
AV.ControlsWidget.prototype.layoutNotify = function(b, c, d) {
    return this.objectNotify("layout", b, c, d)
};
AV.ControlsWidget.prototype.objectNotify = function(b, c, d, a) {
    return "object" == typeof this[b][c] && (b = this[b][c], "function" == typeof b[d]) ? (d = b[d], a || (a = []), d.apply(b, a)) : !0
};
AV.ControlsWidget.prototype.shutdown = function() {
    "mobile" != AV.launchData.openType && "aviary" != AV.launchData.openType && AV.formFields && (AV.formFields.checkbox_style_shutdown(), AV.formFields.dropdown_style_shutdown());
    this.canvasUI && this.canvasUI.shutdown();
    this.messager && this.messager.hide();
    this.layoutNotify(AV.launchData.openType, "unsubscribeToResize", ["setupScrollPanels"]);
    this.shutdownAllTools();
    this.unbindControls();
    this.toolsPager && (this.toolsPager.shutdown(), this.toolsPager = null);
    this.paintWidget && (this.paintWidget.showWaitThrobber = null);
    AV.usageTracker.shutdown();
    this.waitThrobber.stop();
    this.onEggWaitThrobber.stop();
    this.onEggWaitThrobber = this.waitThrobber = null;
    this.showPanel(null)
};
AV.ControlsWidget.prototype.initAllTools = function() {
    AV.paintWidgetInstance.filterManager.loadPack("tools");
    for (var b in this.activeTools) {
        var c = this.activeTools[b];
        this.tool.hasOwnProperty(c) && AV.paintWidgetInstance.moduleLoaded(c, function(b) {
            this.moduleNotify(b, "init", [this])
        }.AV_bindInst(this))
    }
};
AV.ControlsWidget.prototype.shutdownAllTools = function() {
    for (var b in this.activeTools) this.moduleNotify(this.activeTools[b], "shutdown")
};
AV.ControlsWidget.prototype.bindControls = function() {};
AV.ControlsWidget.prototype.unbindControls = function() {};
AV.ControlsWidget.prototype.initWithPaintWidget = function(b) {
    this.paintWidget = b;
    this.imageSizeTracker = new AV.ImageSizeTracker(b.actions);
    AV.featherUseFlash || this.initAllTools();
    this.bindControls();
    this.paintWidget.showWaitThrobber = this.showWaitThrobber.AV_bindInst(this)
};
AV.ControlsWidget.prototype.setActiveTool = function(b, c) {
    var d = function() {
        if (!this.paintWidget.busy) {
            this.setPanelModeFromMain(b, c);
            AV.controlsWidgetInstance.onEggWaitThrobber.stop();
            if ("mobile" == AV.launchData.openType) {
                var a;
                if (c = c || document.getElementById("avpw_main_" + b)) if (a = c.getAttribute("data-header")) document.getElementById("avpw_control_toolname").innerHTML = a
            }
            AV.usageTracker.addUsage(b)
        }
    }.AV_bindInst(this);
    if (-1 < b.indexOf("overlay")) {
        if ("aviary" !== AV.launchData.openType && "mobile" !== AV.launchData.openType) {
            var a =
                b.split("overlay_")[1];
            a && this.tool.overlay.setStickerGroupIndex(a)
        }
        b = "overlay"
    } - 1 < b.indexOf("effects") && ((a = b.split("effects_")[1]) && this.tool.effects.setFilterPack(a), b = "effects");
    !this.paintWidget.moduleLoaded(b, d) && c && (this.onEggWaitThrobber.stop(), this.onEggWaitThrobber.spin(avpw$(c).children(".avpw_icon_waiter")[0]))
};
AV.ControlsWidget.prototype.setMinWidth = function(b) {
    AV.msie && !(8 <= AV.msie) && (b = parseInt(b) + 330, avpw$("#avpw_wrapper_1").css("min-width", b))
};
AV.ControlsWidget.prototype.showWaitThrobber = function(b, c) {
    if (b) {
        var d = this.layoutNotify(AV.launchData.openType, "getEmbedElement");
        d.is(":visible") && (this.waitThrobber.spin(d[0]), avpw$(this.waitThrobber).fadeIn(300))
    } else avpw$(this.waitThrobber.el).fadeOut(300, this.waitThrobber.stop);
    c && window.setTimeout(c, 5)
};
AV.ControlsWidget.prototype.panelMode2WidgetMode = function(b) {
    switch (b) {
        case "rotate":
            return "rotate90";
        case "greeneye":
            return "redeye";
        case "pinch":
            return "bulge"
    }
    return b
};
AV.ControlsWidget.prototype.setPanelMode = function() {
    var b, c, d = function(a) {
        if (c.canvasUI) c.canvasUI.onModeChange(a);
        c.moduleNotify(c.curMode, "panelWillClose");
        c.moduleNotify(a, "panelWillOpen");
        c.resizeFlashThumbs(a);
        c.paintWidget.setMode(c.panelMode2WidgetMode(a));
        c.showPanel(a);
        c._resetUI(a);
        c.paintWidget.setCurrentLayerByName("background");
        window.setTimeout(function(f) {
            return function() {
                c.moduleNotify(f, "panelDidClose");
                c.curMode = a;
                c.moduleNotify(a, "panelDidOpen");
                b = !1
            }
        }(c.curMode), 200);
        c.curMode =
            a;
        c.layoutNotify(AV.launchData.openType, "disableZoomMode")
    };
    return function(a) {
        b || (b = !0, c = this, d(a))
    }
}();
AV.ControlsWidget.prototype.setPanelModeFromMain = function(b) {
    this.showView("editpanel");
    this.setPanelMode(b)
};
AV.ControlsWidget.prototype.showView = function() {};
AV.ControlsWidget.prototype.setupScrollPanels = function() {
    if (this.activeTools && this.activeTools.length) {
        var b, c, d = this,
            a = 0,
            f, g = {},
            e;
        "mobile" == AV.launchData.openType ? (f = null, e = AV.PAGE_WIDTH) : "aviary" == AV.launchData.openType ? (e = this.layoutNotify(AV.launchData.openType, "getDims").TOOLS_BROWSER_WIDTH, f = AV.template[AV.launchData.layout].aviaryScrollPanel, g = {
            panelWidth: e,
            panelClass: "avpw_scroll_page_complete"
        }) : (f = AV.template[AV.launchData.layout].aviaryScrollPanel, e = 248, avpw$("#avpw_control_main_scrolling_region").width(99999));
        f = {
            itemCount: this.activeTools.length,
            itemsPerPage: this.layoutNotify(AV.launchData.openType, "getToolsPerPage"),
            pageWidth: e,
            leftArrow: avpw$("#avpw_lftArrow"),
            rightArrow: avpw$("#avpw_rghtArrow"),
            itemBuilder: function(f) {
                b = d.activeTools[f];
                if (b == "overlay") if (AV.launchData.openType !== "aviary" && AV.launchData.openType !== "mobile") {
                    f = AV.launchData.stickers;
                    if (f[a] && f[a].label) {
                        c = f[a].label;
                        b = "overlay_" + a;
                        a++
                    } else c = AV.getLocalizedString("Stickers")
                } else c = AV.getLocalizedString("Stickers");
                else c = b == "barrel" ? AV.getLocalizedString("Bulge") : b == "drawing" ? AV.getLocalizedString("Draw") : b.substr(0, 1).toUpperCase() + b.substr(1);
                c = AV.getLocalizedString(c);
                return AV.template[AV.launchData.layout].eggIcon({
                    optionName: b,
                    capOptionName: c
                })
            },
            pageTemplate: AV.template[AV.launchData.layout].genericScrollPanel,
            pipTemplate: AV.template[AV.launchData.layout].scrollPanelPip,
            lastPageTemplate: f,
            lastPageContents: g,
            pageContainer: avpw$("#avpw_control_main_scrolling_region"),
            pipContainer: avpw$("#avpw_tools_pager ul"),
            onPageLeft: function() {
                d.didJumpToLastPage ? d.goToPreviousToolsPage.call(d) : d.toolsPager.pageLeft.call(this);
                return false
            },
            onPageChange: function(a) {
                AV.usageTracker.addPageHit(a)
            }
        };
        this.toolsPager = new AV.Pager(f);
        AV.usageTracker.setPageCount(this.toolsPager.getPageCount());
        "aviary" == AV.launchData.openType && avpw$("#avpw_control_main_scrolling_region").css("width", this.toolsPager.getPageCount() * e + "px");
        this.toolsPager.changePage()
    }
};
AV.ControlsWidget.prototype.setLastToolsPage = function(b) {
    avpw$(".avpw_isa_last_panel").hide();
    avpw$("#" + b).show()
};
AV.ControlsWidget.prototype.goToToolsPage = function(b, c, d) {
    this.toolsPager.setCurrentPage(parseInt(b));
    this.toolsPager.changePage(c, d)
};
AV.ControlsWidget.prototype.goToPreviousToolsPage = function() {
    var b = this;
    this.didJumpToLastPage = !1;
    this.goToToolsPage(this.toolsPager.getPreviousPage(), !1, function() {
        b.setLastToolsPage("avpw_aviary_about")
    })
};
AV.ControlsWidget.prototype.messager = function() {
    var b = {},
        c, d, a;
    return {
        show: function(a, g, e) {
            c = c || avpw$("#avpw_messaging");
            d = d || avpw$("#avpw_messaging_inner");
            a = b[a] || (b[a] = avpw$("#" + a));
            d.append(a);
            c.show();
            g ? (c.data("needsConfirmation", !0), AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "disableControls")) : (c.data("needsConfirmation", !1), e || window.setTimeout(this.hide, 1E3))
        },
        hide: function(f, d) {
            c = c || avpw$("#avpw_messaging");
            a = a || avpw$("#avpw_messages");
            if (f) {
                var e = b[f];
                e && a.append(e)
            } else avpw$.each(b, function(b, c) {
                a.append(c)
            });
            c.data("needsConfirmation") ? (window.setTimeout(function() {
                d && d()
            }, 400), AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "enableControls")) : (c.hide(), d && d())
        },
        addMessage: function(b) {
            a = a || avpw$("#avpw_messages");
            b && (a[0].innerHTML += b)
        }
    }
}();
AV.ControlsWidget.prototype.orientationChanged = function() {};
AV.ControlsWidget.prototype.windowResized = function() {
    var b = null;
    return function() {
        window.clearTimeout(b);
        b = window.setTimeout(function() {
            AV.controlsWidgetInstance.canvasUI && AV.controlsWidgetInstance.canvasUI.resetOffset();
            AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "onResize");
            b = null
        }, 500)
    }
}();
AV.ControlsWidget.prototype.Slider = function(b) {
    var c = !1,
        d = function(a, d) {
            !c && b.onstart && b.onstart.apply(this, [a, d])
        },
        a = function(a, d) {
            !c && b.onchange && b.onchange.apply(this, [a, d])
        },
        f = function(a, d) {
            !c && b.onslide && b.onslide.apply(this, [a, d])
        },
        g = avpw$(b.element).slider({
            range: "min",
            max: b.max,
            min: b.min,
            value: b.defaultValue || b.max / 2,
            delay: b.delay
        });
    this.getValue = function() {
        return g.slider("value")
    };
    this.setValue = function(a) {
        return g.slider("value", a)
    };
    this.reset = function() {
        c = !0;
        this.setValue(b.defaultValue);
        c = !1
    };
    this.addListeners = function() {
        g.bind("slidestart", d).bind("slidechange", a).bind("slide", f)
    };
    this.removeListeners = function() {
        g.unbind("slidestart").unbind("slide").unbind("slidechange")
    };
    this.shutdown = function() {
        g.slider("destroy")
    };
    return this
};
AV.ControlsWidget.prototype.Thumbs = function(b) {
    var c = b.elements,
        d = function(a, b) {
            var d = this;
            return function() {
                a && a.apply(d, [c[b], b])
            }
        };
    this.addListeners = function() {
        for (var a = 0; a < c.length; a++) AV.featherUseFlash ? AV.FlashAPI.addThumbClickListener("avpw_flashThumb" + (a + 1), d(b.onclick, a)) : avpw$(c[a]).bind("click", d(b.onclick, a))
    };
    this.removeListeners = function() {
        AV.featherUseFlash ? AV.FlashAPI.removeAllThumbClickListeners() : avpw$(c).unbind("click")
    };
    this.reset = function() {
        for (var a = 0; a < c.length; a++) b.onreset && b.onreset.apply(this, [c[a], a])
    };
    return this
};
AV.ControlsWidget.prototype._drawUICircle = function(b, c, d, a, f) {
    AV.featherUseFlash ? this._drawUICircle_Flash(b, c, d, a, f) : this._drawUICircle_HTML(b, c, d, a, f)
};
AV.ControlsWidget.prototype._drawUICircle_Flash = function(b, c, d, a) {
    c = parseInt(c);
    d = AV.util.color_to_int(d);
    "transparent" == a ? (b = !0, a = 0) : (b = !1, a = a ? parseInt(a.slice(1), 16) : 16777215);
    AV.getFlashMovie("avpw_flashThumb1").setBrushThumb(c, d, a, b)
};
AV.ControlsWidget.prototype._drawUICircle_HTML = function(b, c, d, a, f) {
    var b = avpw$(b)[0],
        g = b.getContext("2d");
    g.clearRect(0, 0, b.width, b.height);
    f && "transparent" !== a && this._drawUIGrid(g, b.width, b.height);
    g.globalCompositeOperation = "source-over";
    null != d ? (g.strokeStyle = f && ("transparent" == d || AV.util.color_is_white(d) || null == a) ? "#444" : d, d = 3) : (g.strokeStyle = "rgba(0,0,0,0)", d = 1);
    g.lineWidth = d;
    g.beginPath();
    g.arc(b.width / 2, b.height / 2, c, 0, 2 * Math.PI, !0);
    g.stroke();
    g.closePath();
    null != a && (g.save(), g.clip(), f && "transparent" == a ? this._drawUIGrid(g, b.width, b.height) : (g.fillStyle = a, g.fillRect(0, 0, b.width, b.height)), g.restore())
};
AV.ControlsWidget.prototype._drawUIGrid = function(b, c, d, a) {
    var f;
    a || (a = 5);
    for (f = 0; f < d + a; f += a) for (c = 0; c < d + a; c += a) b.fillStyle = 1 == (c + f & 1) ? "#fff" : "#ddd", b.fillRect(c, f, a, a)
};
AV.ControlsWidget.prototype.showPanel = function(b) {
    null != b && (avpw$(".avpw_controlpanel").each(function() {
        avpw$(this).hide()
    }), avpw$("#avpw_controlpanel_" + b).show())
};
AV.ControlsWidget.prototype.showFlashThumbs = function(b, c) {
    if (AV.featherUseFlash) {
        var d, a, f, g;
        d = b ? avpw$("#avpw_controlpanel_" + b + " .avpw_flashthumb_holder") : avpw$(".avpw_flashthumb_holder");
        c ? avpw$(d).each(function(b, c) {
            a = "avpw_flashThumb" + (b + 1);
            g = AV.getFlashMovie(a);
            g.width = avpw$(c).width();
            g.height = avpw$(c).height();
            f = avpw$(c).offset();
            f.top += 1;
            f.left += 1;
            avpw$("#" + a).offset(f)
        }) : avpw$(d).each(function(b) {
            a = "avpw_flashThumb" + (b + 1);
            avpw$("#" + a).offset({
                top: -9999,
                left: -9999
            })
        })
    }
};
AV.ControlsWidget.prototype.resizeFlashThumbs = function(b) {
    if (AV.featherUseFlash) {
        var c, d, b = avpw$("#avpw_controlpanel_" + b + " .avpw_flashthumb_holder");
        avpw$(b).each(function(a, b) {
            c = "avpw_flashThumb" + (a + 1);
            d = AV.getFlashMovie(c);
            var g = avpw$(b).prop("width"),
                e = avpw$(b).prop("height");
            g || (g = avpw$(b).width());
            e || (e = avpw$(b).height());
            g && e && (d.width = g, d.height = e)
        })
    }
};
AV.ControlsWidget.prototype.save = function() {
    var b = function() {
        var b = this.paintWidget.exportImage().base64data,
            d = function() {
                AV.simpleXHRPost(AV.build.imgrecvServer, {
                    postdata: AV.launchData.postData,
                    posturl: AV.launchData.postUrl,
                    apikey: AV.launchData.apiKey,
                    sessionid: AV.usageTracker.getUUID(),
                    actionlist: this.paintWidget.actions.exportJSON(!0),
                    origurl: this.origURL,
                    encodedas: "base64text",
                    hiresurl: AV.launchData.hiresUrl,
                    contenttype: AV.launchData.fileFormat,
                    jpgquality: AV.launchData.jpgQuality,
                    debug: AV.launchData.debug,
                    asyncsave: AV.launchData.asyncSave,
                    signature: AV.launchData.signature,
                    timestamp: AV.launchData.timestamp,
                    usecustomfileexpiration: AV.launchData.useCustomFileExpiration,
                    file: b
                }, this.saveCallback_XHR.AV_bindInst(this)) || a.call(this)
            },
            a = function() {
                var a = "avpw_img_submit_target_" + Math.floor(4294967295 * Math.random()).toString(16);
                avpw$("#avpw_img_submit_target_holder").html(AV.buildHiddenFrame("avpw_img_submit_target", a));
                avpw$("#avpw_save_form").attr("target", a);
                avpw$("#avpw_img_submit_target").load(function() {
                    this.saveCallback_form(a)
                }.AV_bindInst(this));
                avpw$("#avpw_save_form_data").val(b);
                avpw$("#avpw_save_form_postdata").val(AV.launchData.postData);
                avpw$("#avpw_save_form_posturl").val(AV.launchData.postUrl);
                avpw$("#avpw_save_form_apikey").val(AV.launchData.apiKey);
                avpw$("#avpw_save_form_sessionid").val(AV.usageTracker.getUUID());
                avpw$("#avpw_save_form_actionlist").val(this.paintWidget.actions.exportJSON(!0));
                avpw$("#avpw_save_form_origurl").val(this.origURL);
                avpw$("#avpw_save_form_hiresurl").val(AV.launchData.hiresUrl);
                avpw$("#avpw_save_form_contenttype").val(AV.launchData.fileFormat);
                avpw$("#avpw_save_form_jpgquality").val(AV.launchData.jpgQuality);
                avpw$("#avpw_save_form_debug").val(AV.launchData.debug);
                avpw$("#avpw_save_form_asyncsave").val(AV.launchData.asyncSave);
                avpw$("#avpw_save_form_signature").val(AV.launchData.signature);
                avpw$("#avpw_save_form_timestamp").val(AV.launchData.timestamp);
                avpw$("#avpw_save_form_usecustomfileexpiration").val(AV.launchData.useCustomFileExpiration);
                avpw$("#avpw_save_form").submit()
            },
            f = this;
        this.paintWidget.showWaitThrobber(!0, function() {
            avpw$.support.cors && (!AV.firefox || AV.firefox >= 4) ? d.call(f) : a.call(f)
        });
        return !1
    };
    return function() {
        if (2 > AV.controlsWidgetInstance.loaderPhase || this.saving) return !1;
        this.moduleNotify(this.curMode, "commit");
        this.showView("main");
        this.setPanelMode(null);
        this.saving = !0;
        AV.prevActionList = this.paintWidget.actions.exportJSON(!0);
        AV.launchData.postData && "string" !== typeof AV.launchData.postData && (AV.launchData.postData = AV.JSON.stringify(AV.launchData.postData));
        return AV.featherUseFlash ? (AV.FlashAPI.save(), !1) : b.call(this)
    }
}();
AV.ControlsWidget.prototype.onSaveButtonClicked = function() {
    //AV.usageTracker.submit("saveclicked");
    return "function" === typeof AV.launchData.onSaveButtonClicked && !1 === AV.launchData.onSaveButtonClicked.apply(AV.launchData, [AV.util.getImageId(AV.controlsWidgetInstance.paintImgIdElem)]) ? !1 : AV.controlsWidgetInstance.save()
};
AV.ControlsWidget.prototype.saveCallback_form = function(b) {
    this.paintWidget && (this.paintWidget.showWaitThrobber(!1), window.avpw_img_submit_target_announcer.postMessage("avpw_load:" + b, "*"))
};
AV.ControlsWidget.prototype.saveCallback_XHR = function(b) {
    this.paintWidget.showWaitThrobber(!1);
    /*var c = avpw$(b).find("error");
    if (0 < c.length) alert("file submission error: " + c.text());
    else {
        var d, a;
        if (c = avpw$(b).find("url")) d = c.text(), d = d.replace(/^\s+|\s+$/g, ""), avpw$(AV.util.getImageElem(this.paintImgIdElem)).avpw_prevURL = d;
        if (b = avpw$(b).find("hiresurl")) a = b.text(), a = a.replace(/^\s+|\s+$/g, "");
        AV.controlsWidget_onImageSaved(d, a)
    }*/
    b = avpw$.parseJSON(b);
    var c = b.error;
    if(c){
        alert("file submission error: " + c.text());
    }else{
        /*var d, a;
        if (c = b.url) d = c, d = d.replace(/^\s+|\s+$/g, ""), avpw$(AV.util.getImageElem(this.paintImgIdElem)).avpw_prevURL = d;
        if (b = b.hiresurl) a = b, a = a.replace(/^\s+|\s+$/g, "");
        AV.controlsWidget_onImageSaved(d, a)*/
        AV.controlsWidget_onImageSaved(b.url);
    }
};
AV.ControlsWidget.prototype.showAreYouSure = function() {
    "mobile" == AV.launchData.openType || "aviary" == AV.launchData.openType ? this.messager.show("avpw_aviary_quitareyousure", !0) : (this.showView("main"), this.setPanelMode(null), this.setLastToolsPage("avpw_aviary_quitareyousure"), AV.util.nextFrame(function() {
        this.didJumpToLastPage = !0;
        this.goToToolsPage(this.toolsPager.getLastPage())
    }.AV_bindInst(this)))
};
AV.ControlsWidget.prototype.cancel = function() {
    this.quitCount++;
    var b = 0 < this.quitCount && this.paintWidget && this.paintWidget.dirty;
    if ("function" === typeof AV.launchData.onCloseButtonClicked && !1 === AV.launchData.onCloseButtonClicked.apply(AV.launchData, [b])) return !1;
    b ? this.showAreYouSure() : AV.paintWidgetCloser();
    return !1
};
AV.ControlsWidget.prototype.undo = function() {
    if (this.paintWidget.busy || !1 === this.moduleNotify(this.curMode, "onUndo")) return !1;
    this.paintWidget.undo();
    "mobile" != AV.launchData.openType && "aviary" != AV.launchData.openType && this.setMinWidth(this.paintWidget.width);
    this.moduleNotify(this.curMode, "onUndoComplete");
    return !1
};
AV.ControlsWidget.prototype.undoToCheckpoint = function() {
    if (this.paintWidget.busy || !1 === this.moduleNotify(this.curMode, "onUndo", [{
        global: !0
    }])) return !1;
    this.paintWidget.actions.undoToCheckpoint();
    this.moduleNotify(this.curMode, "onUndoComplete", [{
        global: !0
    }]);
    return !1
};
AV.ControlsWidget.prototype.redo = function() {
    if (this.paintWidget.busy || !1 === this.moduleNotify(this.curMode, "onRedo")) return !1;
    this.paintWidget.redo();
    "mobile" != AV.launchData.openType && "aviary" != AV.launchData.openType && this.setMinWidth(this.paintWidget.width);
    this.moduleNotify(this.curMode, "onRedoComplete");
    return !1
};
AV.ControlsWidget.prototype.redoToCheckpoint = function() {
    if (this.paintWidget.busy || !1 === this.moduleNotify(this.curMode, "onRedo", [{
        global: !0
    }])) return !1;
    var b = this.paintWidget.actions.redoToCheckpoint();
    b && this.moduleNotify(this.curMode, "onRedoComplete", [{
        global: !0
    }]);
    return b
};
AV.ControlsWidget.prototype._resetUI = function(b) {
    this.moduleNotify(b, "resetUI")
};
AV.ControlsWidget.prototype.getScaledDims = function(b, c) {
    return AV.util.getScaledDims(b, c, this.maxWidth, this.maxHeight)
};
AV.TransformStyle = function(b) {
    var c = b || "";
    this.set = function(b) {
        if (c) for (var a in b) {
            var f = !1;
            c = c.replace(RegExp(a + "\\([^\\)]*"), function() {
                f = !0;
                return a + "(" + b[a]
            });
            f || (c += " " + a + "(" + b[a] + ")")
        } else
            for (a in b) c += " " + a + "(" + b[a] + ")"
    };
    this.serialize = function() {
        return c
    };
    return this
};
(function(b, c, d) {
    b.AV = b.AV || {};
    var a = b.AV;
    b.Aviary = a;
    a.feather_loaded = !1;
    a.feather_loading = !1;
    a.build = a.build || {
        version: "",
        imgrecvServer: "imgrecvserver.cgi",
        flashGatewayServer: "",
        imgrecvBase: "",
        inAppPurchaseFrameURL: "",
        imgtrackServer: "imgtrackserver.cgi",
        filterServer: "",
        jsonp_imgserver: "",
        featherTargetAnnounce: "feather_target_announce.html",
        proxyServer: "",
        feather_baseURL: "",
        feather_stickerURL: "",
        googleTracker: "",
        MINIMUM_FLASH_PLAYER_VERSION: "10.2.0"
    };
    //a.defaultTools_legacy = "sharpen whiten rotate flip resize crop redeye blemish colors saturation blur brightness contrast drawing text overlay".split(" ");
    //a.defaultTools = "enhance effects overlay orientation resize crop warmth brightness contrast saturation sharpness drawing text redeye whiten blemish".split(" ");
    a.defaultTools_legacy = "sharpen whiten rotate flip resize crop redeye blemish colors saturation blur brightness contrast drawing text".split(" ");
    a.defaultTools = "enhance effects orientation resize crop warmth brightness contrast saturation sharpness drawing text redeye whiten blemish".split(" ");
    a.defaultStickers = [
        [a.build.feather_stickerURL + "400x400/sombrero.png", a.build.feather_stickerURL + "100x100/sombrero.png", a.build.feather_stickerURL + "1000x1000/sombrero.png"],
        [a.build.feather_stickerURL + "400x400/helicopter.png", a.build.feather_stickerURL + "100x100/helicopter.png", a.build.feather_stickerURL + "1000x1000/helicopter.png"],
        [a.build.feather_stickerURL + "400x400/crown.png", a.build.feather_stickerURL + "100x100/crown.png", a.build.feather_stickerURL + "1000x1000/crown.png"],
        [a.build.feather_stickerURL + "400x400/fez.png", a.build.feather_stickerURL + "100x100/fez.png", a.build.feather_stickerURL + "1000x1000/fez.png"],
        [a.build.feather_stickerURL + "400x400/3d_glasses.png", a.build.feather_stickerURL + "100x100/3d_glasses.png", a.build.feather_stickerURL + "1000x1000/3d_glasses.png"],
        [a.build.feather_stickerURL + "400x400/hipster_glasses.png", a.build.feather_stickerURL + "100x100/hipster_glasses.png", a.build.feather_stickerURL + "1000x1000/hipster_glasses.png"],
        [a.build.feather_stickerURL + "400x400/disguise.png", a.build.feather_stickerURL + "100x100/disguise.png", a.build.feather_stickerURL + "1000x1000/disguise.png"],
        [a.build.feather_stickerURL + "400x400/aviators.png", a.build.feather_stickerURL + "100x100/aviators.png", a.build.feather_stickerURL + "1000x1000/aviators.png"],
        [a.build.feather_stickerURL + "400x400/eyepatch.png", a.build.feather_stickerURL + "100x100/eyepatch.png", a.build.feather_stickerURL + "1000x1000/eyepatch.png"],
        [a.build.feather_stickerURL + "400x400/bow_tie.png", a.build.feather_stickerURL + "100x100/bow_tie.png", a.build.feather_stickerURL + "1000x1000/bow_tie.png"],
        [a.build.feather_stickerURL + "400x400/tie.png", a.build.feather_stickerURL + "100x100/tie.png", a.build.feather_stickerURL + "1000x1000/tie.png"],
        [a.build.feather_stickerURL + "400x400/pipe.png", a.build.feather_stickerURL + "100x100/pipe.png", a.build.feather_stickerURL + "1000x1000/pipe.png"],
        [a.build.feather_stickerURL + "400x400/cigar.png", a.build.feather_stickerURL + "100x100/cigar.png", a.build.feather_stickerURL + "1000x1000/cigar.png"],
        [a.build.feather_stickerURL + "400x400/arrow.png", a.build.feather_stickerURL + "100x100/arrow.png", a.build.feather_stickerURL + "1000x1000/arrow.png"],
        [a.build.feather_stickerURL + "400x400/green_bubble.png", a.build.feather_stickerURL + "100x100/green_bubble.png", a.build.feather_stickerURL + "1000x1000/green_bubble.png"],
        [a.build.feather_stickerURL + "400x400/orange_bubble.png", a.build.feather_stickerURL + "100x100/orange_bubble.png", a.build.feather_stickerURL + "1000x1000/orange_bubble.png"],
        [a.build.feather_stickerURL + "400x400/blue_bubble.png", a.build.feather_stickerURL + "100x100/blue_bubble.png", a.build.feather_stickerURL + "1000x1000/blue_bubble.png"],
        [a.build.feather_stickerURL + "400x400/pink_bubble.png", a.build.feather_stickerURL + "100x100/pink_bubble.png", a.build.feather_stickerURL + "1000x1000/pink_bubble.png"],
        [a.build.feather_stickerURL + "400x400/star.png", a.build.feather_stickerURL + "100x100/star.png", a.build.feather_stickerURL + "1000x1000/star.png"],
        [a.build.feather_stickerURL + "400x400/heart.png", a.build.feather_stickerURL + "100x100/heart.png", a.build.feather_stickerURL + "1000x1000/heart.png"],
        [a.build.feather_stickerURL + "400x400/red_arrow.png", a.build.feather_stickerURL + "100x100/red_arrow.png", a.build.feather_stickerURL + "1000x1000/red_arrow.png"],
        [a.build.feather_stickerURL + "400x400/blue_arrow.png", a.build.feather_stickerURL + "100x100/blue_arrow.png", a.build.feather_stickerURL + "1000x1000/blue_arrow.png"],
        [a.build.feather_stickerURL + "400x400/green_circle.png", a.build.feather_stickerURL + "100x100/green_circle.png", a.build.feather_stickerURL + "1000x1000/green_circle.png"],
        [a.build.feather_stickerURL + "400x400/orange_square.png", a.build.feather_stickerURL + "100x100/orange_square.png", a.build.feather_stickerURL + "1000x1000/orange_square.png"]
    ];
    (function(b) {
        var c = function(b, c) {
            c = c || {};
            return {
                theme: c.theme || b.Feather_Theme || "bluesky",
                minimumStyling: c.minimumStyling || !1,
                openType: c.openType || b.Feather_OpenType || "popup",
                layout: c.layout || "desktop_bluesky",
                language: c.language || b.Feather_Language || "en",
                forceFlash: c.forceFlash || b.Feather_ForceFlash,
                forceSupport: c.forceSupport || b.AV_Feather_ForceSupport,
                poweredByURL: c.poweredByURL || "http://www.aviary.com",
                giveFeedbackURL: c.giveFeedbackURL || "http://www.aviary.com/feature-requests",
                getWidgetURL: c.getWidgetURL || "http://www.aviary.com",
                onLoad: c.onLoad || b.Feather_OnLoad,
                onReady: b.Feather_OnLaunchComplete,
                onClose: b.Feather_OnClose,
                onSave: b.Feather_OnSave,
                onSaveButtonClicked: b.Feather_OnSaveButtonClicked,
                onError: b.Feather_OnError,
                image: null,
                url: null,
                appendTo: null,
                noCloseButton: b.Feather_NoCloseButton,
                maxSize: b.Feather_MaxSize || b.Feather_MaxDisplaySize || 800,
                maxEditSize: null,
                hiresMaxSize: 1E4,
                hiresWidth: null,
                hiresHeight: null,
                tools: b.Feather_EditOptions && "all" !== b.Feather_EditOptions && "All" !== b.Feather_EditOptions && "" !== b.Feather_EditOptions ? b.Feather_EditOptions : a.defaultTools_legacy,
                initTool: "",
                cropPresets: b.Feather_CropSizes || ["Original", "Custom", ["Square", "1:1"], "3:2", "3:5", "4:3", "4:5", "4:6", "5:7", "8:10", "16:9"],
                resizePresets: b.Feather_ResizeSizes || "320x240 640x480 800x600 1280x1024 1600x1200 240x320 480x640 600x800 1024x1280 1200x1600".split(" "),
                stickers: b.Feather_Stickers || a.defaultStickers,
                apiKey: b.Feather_APIKey,
                hiresUrl: b.Feather_HiResURL,
                postUrl: b.Feather_PostURL,
                postData: null,
                fileFormat: b.Feather_FileFormat || b.Feather_ContentType || "",
                jpgQuality: b.Feather_FileQuality || 100,
                debug: !1,
                asyncSave: !0,
                signature: b.Feather_Signature || null,
                timestamp: b.Feather_Timestamp || null,
                useCustomFileExpiration: !1,
                allowInAppPurchase: !1
            }
        };
        a.baseConfig = c(b);
        if ("https:" == b.location.protocol || "chrome-extension:" == b.location.protocol) {
            var d, k;
            for (k in a.build) d = k.split("_SSL"), 2 === d.length && a.build[k] && (d = d[0], a.build[d] = a.build[k])
        }(b.Feather_Theme || b.Feather_OpenType || b.Feather_APIKey) && b.setTimeout(function() {
            var d = new a.Feather;
            b.aviaryeditor = function(e, i, j, k) {
                a.launchData = c(b, a.baseConfig);
                e = a.util.extend(a.launchData, {
                    image: e,
                    url: i,
                    postData: j,
                    appendTo: k
                });
                d.launch(e)
            };
            b.aviaryeditor_close = d.close;
            b.aviaryeditor_relaunch = d.relaunch;
            b.aviaryeditor_activatetool =
                d.activateTool;
            b.aviarynewimage = d.replaceImage
        }, 0)
    })(c);
    a.getLocalizedString = function(b) {
        try {
            var c = a.lang[a.launchData.language][b];
            void 0 === c && (c = b);
            return c
        } catch (d) {}
        return b
    };
    Function.prototype.AV_bindInst = function(a) {
        var b = this;
        return function() {
            return b.apply(a, arguments)
        }
    };
    a.errorNotify = function(b, d) {
        var e = {
                BAD_IMAGE: {
                    code: 1,
                    message: "Unable to load image from `image` param provided."
                },
                UNSUPPORTED: {
                    code: 2,
                    message: "Unable to detect Flash plugin or HTML canvas support. Cannot launch editor."
                },
                BAD_URL: {
                    code: 3,
                    message: "Image at `url` param could not be accessed by Aviary service at " + (a.featherUseFlash ? a.build.proxyServer : a.build.imgrecvBase) + "."
                },
                UNSUPPORTED_TOOL: {
                    code: 4,
                    message: "Tool requested is not part of your chosen `tools` (" + a.launchData.tools + ") or not supported by this browser."
                },
                IMAGE_NOT_CLEAN: {
                    code: 5,
                    message: "Image cannot be edited due to browser security restrictions. Image must come from the same domain as this page (" + c.location.href + "), or its location must be provided to the `url` param so Aviary server can generate generic image data with no origin."
                }
            }[b],
            k = e.message;
        "function" === typeof a.launchData.onError && (e.args = d, k = a.launchData.onError(e) || k);
        return k
    };
    a.injectControls = function() {
        var b, c;
        "popup" == a.launchData.openType ? (b = "", c = a.template[a.launchData.layout].saveBlock()) : (b = a.template[a.launchData.layout].saveBlock(), c = "");
        if (a.criticalLayoutStyles && !a.feather_loaded) {
            var e = d.createElement("style");
            e.type = "text/css";
            var k = d.createTextNode(a.criticalLayoutStyles);
            e.styleSheet ? e.styleSheet.cssText = k.nodeValue : e.appendChild(k);
            d.getElementsByTagName("head")[0].appendChild(e)
        }
        b =
            a.template[a.launchData.layout].controls({
                internalSaveBlock: b,
                externalSaveBlock: c
            });
        c = d.createElement("div");
        c.id = "avpw_holder";
        e = "";
        a.featherUseFlash && (e = "avpw_flash ");
        a.msie && (e += "avpw_ie" + a.msie);
        e && (c.className = e);
        (e = d.getElementsByTagName("body")) && (e = e[0]);
        e || (e = d.documentElement);
        e.appendChild(c);
        c.innerHTML = b
    };
    a.setPopupPos = function(b) {
        var c = a.util.getX(b),
            e = a.util.getY(b),
            b = c + (b.width < a.launchData.maxSize ? b.width : a.launchData.maxSize) + 15,
            c = d.getElementById("avpw_controls");
        c.style.position = "absolute";
        c.style.left = b + "px";
        c.style.top = e + "px"
    };
    a.setLightboxPos = function() {
        var a;
        a = "pageYOffset" in c ? c.pageYOffset : (((t = d.documentElement) || (t = d.body.parentNode)) && "number" == typeof t.ScrollTop ? t : d.body).scrollTop;
        d.getElementById("avpw_controls").style.top = a + 30 + "px"
    };
    a.buildHiddenFrame = function(b, c, d) {
        d || (d = a.build.feather_baseURL + "blank.html");
        c || (c = b);
        return ['<iframe width="1" height="1" style="position:absolute;left:-9999px;" ', 'id="' + b + '" name="' + c + '" src="' + d + '">', "</iframe>"].join("")
    };
    a.simpleXHRPost = function(a, b, c) {
        function d() {
            //c && 4 === i.readyState && c(i.responseXML)
            c && 4 === i.readyState && c(i.responseText)
        }
        function l(a) {
            var b = [];
            avpw$.each(a, function(a, c) {
                c && b.push("Content-Disposition: form-data; " + ('name="' + a + '"\r\n\r\n') + (c + "\r\n"))
            });
            a = "--" + h + "\r\n";
            a += b.join("--" + h + "\r\n");
            return a += "--" + h + "--\r\n"
        }
        var h = "FEATHER-AJAX---" + (new Date).getTime(),
            i = new XMLHttpRequest;
        try {
            var j = "multipart/form-data; charset=UTF-8; boundary=" + h;
            i.open("POST", a, !0);
            i.setRequestHeader("Content-Type", j);
            i.onreadystatechange = d;
            var m = l(b);
            i.send(m)
        } catch (o) {
            return !1
        }
        return !0
    };
    a.Feather = function(b) {
        var c = function() {
                a.injectControls();
                a.util.nextFrame(a.loadStageFinal)
            },
            e = function() {
                "undefined" !== typeof avpw$ ? avpw$(d).ready(c) : c()
            },
            k = !1;
        if (b && (b.apiVersion && 2 === parseInt(b.apiVersion) && (b.openType = "aviary"), !b.tools || "all" === b.tools || "All" === b.tools || "" === b.tools)) k = !0;
        b = b || {};
        a.launchData = a.util.extend(a.baseConfig, b);
        (function() {
            function b(c) {
                var c = a.build.feather_baseURL + "css/" + c,
                    d = a.launchData.theme;
                "mobile" == a.launchData.openType || "aviary" == a.launchData.openType ? a.util.loadFile(c + ".css", "css") : (a.msie && (8 === a.msie || 7 === a.msie) ? a.util.loadFile(c + "_ie" + a.msie + ".css", "css") : a.featherUseFlash ? a.util.loadFile(c + "_flash.css", "css") : a.util.loadFile(c + ".css", "css"), a.util.loadFile(c + "-" + d + ".css", "css"))
            }
            a.featherUseFlash = !a.featherHtmlOk() && a.featherFlashOk();
            a.launchData.language = a.launchData.language.toLowerCase();
            "float" == a.launchData.openType && (a.launchData.openType = "popup");
            "integrated" == a.launchData.openType && (a.launchData.openType = "lightbox");
            "inline" == a.launchData.openType && (a.launchData.openType = "inject");
            a.featherUseFlash && "popup" == a.launchData.openType && (a.launchData.openType = "lightbox");
            k && (a.launchData.tools = "aviary" === a.launchData.openType ? a.defaultTools : a.defaultTools_legacy, "mobile" == a.launchData.openType && a.launchData.tools.unshift("effects"));
            if (!a.feather_loaded && !a.feather_loading) {
                a.feather_loading = !0;
                var c = "js/feathercontrols_desktop_bluesky.js";
                "mobile" == a.launchData.openType ? (c = "js/feathercontrols_mobile.js", a.launchData.layout = "mobile_default") : "aviary" == a.launchData.openType && (c = "js/feathercontrols_desktop.js", a.launchData.layout = "desktop");
                "mobile" == a.launchData.openType ? b("feather_mobile") : "aviary" == a.launchData.openType ? a.launchData.minimumStyling ? b("feather_core") : b("feather_theme_aviary") : "popup" == a.launchData.openType ? b("feather") : b("feather_hd");
                "aviary" == a.launchData.openType ? a.util.loadFile(a.build.feather_baseURL + "images/aviary_atlas.png", "img") : a.util.loadFile(a.build.feather_baseURL + "images/atlas-" + a.launchData.theme + ".png", "img");
                a.build.bundled ? e() : a.util.loadFile(a.build.feather_baseURL + c, "js", e)
            }
        })();
        var l = function() {
            if (a.paintWidgetInstance) return !1;
            var b = a.util.getImageElem(a.launchData.image);
            "popup" == a.launchData.openType ? a.setPopupPos(b) : "lightbox" == a.launchData.openType && a.setLightboxPos(b);
            a.paintWidgetLauncher(b, a.launchData.url)
        };
        this.launch = function(b) {
            if (!a.feather_loaded) return !1;
            var c = d.getElementById("avpw_holder");
            c || a.injectControls();
            if (a.paintWidgetInstance) {
                if (c) return !1;
                this.close(!0)
            }
            a.launchData = b ? a.util.extend(a.launchData, b) : a.launchData;
            if (!a.launchData.image) return !1;
            a.launchData.image = a.util.getImageElem(a.launchData.image);
            if (a.featherUseFlash) l();
            else {
                if (!a.featherSupported()) return a.errorNotify("UNSUPPORTED") && "aviary" == a.launchData.openType && (a.controlsWidgetInstance = new a.ControlsWidget, a.controlsWidgetInstance.layoutNotify(a.launchData.openType, "placeControls", [a.launchData.appendTo]), a.controlsWidgetInstance.bindControls(), d.getElementById("avpw_controls").style.display = "block", a.controlsWidgetInstance.messager.show("avpw_aviary_unsupported", !0)), !0;
                a.util.nextFrame(l)
            }
            return !0
        };
        this.save = function() {
            return !a.paintWidgetInstance ? !1 : a.controlsWidgetInstance.save()
        };
        this.close = function(b) {
            if (!a.paintWidgetInstance) return !1;
            a.paintWidgetCloser(b)
        };
        this.relaunch = function() {
            a.isRelaunched = !0;
            if (a.launchData) this.launch(a.launchData);
            else
                return !1
        };
        this.activateTool = function(b) {
            if (!a.paintWidgetInstance) return !1;
            a.controlsWidgetInstance.setActiveTool(b)
        };
        this.replaceImage =

            function(b) {
                if (a.launchData) this.close(!0), a.util.nextFrame(function() {
                    a.launchData.url = b;
                    this.launch(a.launchData)
                }.AV_bindInst(this));
                else
                    return !1
            };
        this.updateConfig = function(b, c) {
            if (a.launchData && b && "string" === typeof b) a.launchData[b] = c;
            else
                return !1
        };
        this.getIsDirty = function() {
            return a.paintWidgetInstance ? a.paintWidgetInstance.dirty : !1
        };
        this.getImageData = function(b, c, d) {
            var f = a.controlsWidgetInstance;
            return a.paintWidgetInstance && 2 === f.loaderPhase ? (f.moduleNotify(f.curMode, "commit"), a.paintWidgetInstance.exportImage(d, function(d) {
                c && a.util.nextFrame(function() {
                    a.controlsWidget_onImageSaved(f.origURL)
                });
                b && "function" === typeof b && b.apply(this, [d])
            }), !0) : !1
        };
        this.getActionList = function() {
            if (a.paintWidgetInstance) {
                var b = a.controlsWidgetInstance;
                b.moduleNotify(b.curMode, "commit");
                return a.paintWidgetInstance.actions.exportJSON(!0)
            }
        };
        this.disableControls = function() {
            a.controlsWidgetInstance.layoutNotify(a.launchData.openType, "disableControls")
        };
        this.enableControls = function() {
            a.controlsWidgetInstance.layoutNotify(a.launchData.openType, "enableControls")
        };
        return this
    };
    a.setUpOstrich = function() {
        avpw_swfobject.embedSWF(a.build.feather_baseURL + "ostrich/OstrichFeather.swf", "avpw_OstrichFeather", "1", "1", a.build.MINIMUM_FLASH_PLAYER_VERSION, "ostrich/playerProductInstall.swf", {
            initializedCallback: "engineReady",
            allowDomains: "*",
            completeCallback: "AV.module_flashfilter_onFilterComplete"
        }, {
            quality: "high",
            bgcolor: "#ffffff",
            allowscriptaccess: "always",
            allowfullscreen: "true",
            hasPriority: "true"
        }, {
            id: "avpw_OstrichFeather",
            name: "avpw_OstrichFeather",
            align: "middle"
        })
    };
    a.loadStageFinal = function() {
        a.feather_loaded = !0;
        if ("function" === typeof a.launchData.onLoad) a.launchData.onLoad()
    };
    a.featherSupported = function() {
        return a.featherHtmlOk() || a.featherFlashOk() || a.launchData.forceSupport
    };
    a.featherFlashOk = function() {
        return a.launchData.forceFlash ? !0 : avpw_swfobject && avpw_swfobject.hasFlashPlayerVersion(a.build.MINIMUM_FLASH_PLAYER_VERSION)
    };
    a.featherHtmlOk = function() {
        if (a.launchData.forceFlash) return !1;
        var b = !! d.createElement("canvas").getContext,
            g = "function" === typeof c.postMessage;
        return b && g
    };
    a.getFlashMovie = function(a) {
        return c[a] || d[a]
    };
    a.msie = function() {
        for (var a = 3, b = d.createElement("div"), c = b.getElementsByTagName("i"); b.innerHTML = "<\!--[if gt IE " + ++a + "]><i></i><![endif]--\>", c[0];);
        return 4 < a ? a : void 0
    }();
    a.firefox = function() {
        var a;
        "Gecko" === c.navigator.product && (a = navigator.userAgent.split("Firefox/")[1], a = parseInt(a, 10));
        return a
    }();
    a.PAGE_WIDTH = 360;
    a.setPageWidth = function(b) {
        a.PAGE_WIDTH = b
    };
    return b
})(this, "undefined" !== typeof window ? window : {}, "undefined" !== typeof document ? document : {});
(function(b, c, d) {
    b.AV = b.AV || {};
    b.AV.support = function(a) {
        var b = a.navigator.userAgent,
            c = a.screen.width,
            a = {
                "0": /Android/i,
                1: /webOS/i,
                2: /iPhone/i,
                3: /iPod/i,
                4: /BlackBerry/i,
                5: /iPad/i
            },
            e, k = 0,
            l = 0,
            h = 0,
            i;
        for (i in a) b.match(a[i]) && (e = parseInt(i));
        b.match(/AppleWebKit/i) && (k = 1);
        0 === e && (l = 1);
        1 === l && (b = b.match(/Android [0-9].[0-9]/).toString()) && (h = parseFloat(b.split("Android ")[1]));
        b = {
            isAppleWebkit: function() {
                return k === 1
            },
            isMobileWebkit: function() {
                return k === 1 && c && (c <= 480 || h > 0 && h <= 2.3)
            },
            isIPhoneOrIPod: function() {
                return e === 2 || e === 3
            },
            isAndroid: function() {
                return l === 1
            },
            getAndroidVersion: function() {
                return h
            }
        };
        b.getVendorProperty = function() {
            var a = {};
            return function(b) {
                var c;
                if (!(c = a[b])) {
                    var e;
                    a: {
                        var f = d.createElement("div");
                        c = b;
                        var g = ["webkit", "ms", "Moz", "O"],
                            h, i = f.style;
                        if (i[c] !== void 0) e = c;
                        else {
                            c = c.charAt(0).toUpperCase() + c.slice(1);
                            for (h = 0; h < g.length; h++) {
                                f = g[h] + c;
                                if (i[f] !== void 0) {
                                    e = f;
                                    break a
                                }
                            }
                        }
                    }
                    c = a[b] = e
                }
                return c
            }
        }();
        return b
    }(c);
    return b
})(this, "undefined" !== typeof window ? window : {}, "undefined" !== typeof document ? document : {});