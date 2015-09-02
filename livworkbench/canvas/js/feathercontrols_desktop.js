/*! jQuery v1.7.1 jquery.com | jquery.org/license */
(function(a, b) {
    function cy(a) {
        return f.isWindow(a) ? a : a.nodeType === 9 ? a.defaultView || a.parentWindow : !1
    }
    function cv(a) {
        if (!ck[a]) {
            var b = c.body,
                d = f("<" + a + ">").appendTo(b),
                e = d.css("display");
            d.remove();
            if (e === "none" || e === "") {
                cl || (cl = c.createElement("iframe"), cl.frameBorder = cl.width = cl.height = 0), b.appendChild(cl);
                if (!cm || !cl.createElement) cm = (cl.contentWindow || cl.contentDocument).document, cm.write((c.compatMode === "CSS1Compat" ? "<!doctype html>" : "") + "<html><body>"), cm.close();
                d = cm.createElement(a), cm.body.appendChild(d), e = f.css(d, "display"), b.removeChild(cl)
            }
            ck[a] = e
        }
        return ck[a]
    }
    function cu(a, b) {
        var c = {};
        f.each(cq.concat.apply([], cq.slice(0, b)), function() {
            c[this] = a
        });
        return c
    }
    function ct() {
        cr = b
    }
    function cs() {
        setTimeout(ct, 0);
        return cr = f.now()
    }
    function cj() {
        try {
            return new a.ActiveXObject("Microsoft.XMLHTTP")
        } catch (b) {}
    }
    function ci() {
        try {
            return new a.XMLHttpRequest
        } catch (b) {}
    }
    function cc(a, c) {
        a.dataFilter && (c = a.dataFilter(c, a.dataType));
        var d = a.dataTypes,
            e = {},
            g, h, i = d.length,
            j, k = d[0],
            l, m, n, o, p;
        for (g = 1; g < i; g++) {
            if (g === 1) for (h in a.converters) typeof h == "string" && (e[h.toLowerCase()] = a.converters[h]);
            l = k, k = d[g];
            if (k === "*") k = l;
            else if (l !== "*" && l !== k) {
                m = l + " " + k, n = e[m] || e["* " + k];
                if (!n) {
                    p = b;
                    for (o in e) {
                        j = o.split(" ");
                        if (j[0] === l || j[0] === "*") {
                            p = e[j[1] + " " + k];
                            if (p) {
                                o = e[o], o === !0 ? n = p : p === !0 && (n = o);
                                break
                            }
                        }
                    }
                }!n && !p && f.error("No conversion from " + m.replace(" ", " to ")), n !== !0 && (c = n ? n(c) : p(o(c)))
            }
        }
        return c
    }
    function cb(a, c, d) {
        var e = a.contents,
            f = a.dataTypes,
            g = a.responseFields,
            h, i, j, k;
        for (i in g) i in d && (c[g[i]] = d[i]);
        while (f[0] === "*") f.shift(), h === b && (h = a.mimeType || c.getResponseHeader("content-type"));
        if (h) for (i in e) if (e[i] && e[i].test(h)) {
            f.unshift(i);
            break
        }
        if (f[0] in d) j = f[0];
        else {
            for (i in d) {
                if (!f[0] || a.converters[i + " " + f[0]]) {
                    j = i;
                    break
                }
                k || (k = i)
            }
            j = j || k
        }
        if (j) {
            j !== f[0] && f.unshift(j);
            return d[j]
        }
    }
    function ca(a, b, c, d) {
        if (f.isArray(b)) f.each(b, function(b, e) {
            c || bE.test(a) ? d(a, e) : ca(a + "[" + (typeof e == "object" || f.isArray(e) ? b : "") + "]", e, c, d)
        });
        else if (!c && b != null && typeof b == "object") for (var e in b) ca(a + "[" + e + "]", b[e], c, d);
        else d(a, b)
    }
    function b_(a, c) {
        var d, e, g = f.ajaxSettings.flatOptions || {};
        for (d in c) c[d] !== b && ((g[d] ? a : e || (e = {}))[d] = c[d]);
        e && f.extend(!0, a, e)
    }
    function b$(a, c, d, e, f, g) {
        f = f || c.dataTypes[0], g = g || {}, g[f] = !0;
        var h = a[f],
            i = 0,
            j = h ? h.length : 0,
            k = a === bT,
            l;
        for (; i < j && (k || !l); i++) l = h[i](c, d, e), typeof l == "string" && (!k || g[l] ? l = b : (c.dataTypes.unshift(l), l = b$(a, c, d, e, l, g)));
        (k || !l) && !g["*"] && (l = b$(a, c, d, e, "*", g));
        return l
    }
    function bZ(a) {
        return function(b, c) {
            typeof b != "string" && (c = b, b = "*");
            if (f.isFunction(c)) {
                var d = b.toLowerCase().split(bP),
                    e = 0,
                    g = d.length,
                    h, i, j;
                for (; e < g; e++) h = d[e], j = /^\+/.test(h), j && (h = h.substr(1) || "*"), i = a[h] = a[h] || [], i[j ? "unshift" : "push"](c)
            }
        }
    }
    function bC(a, b, c) {
        var d = b === "width" ? a.offsetWidth : a.offsetHeight,
            e = b === "width" ? bx : by,
            g = 0,
            h = e.length;
        if (d > 0) {
            if (c !== "border") for (; g < h; g++) c || (d -= parseFloat(f.css(a, "padding" + e[g])) || 0), c === "margin" ? d += parseFloat(f.css(a, c + e[g])) || 0 : d -= parseFloat(f.css(a, "border" + e[g] + "Width")) || 0;
            return d + "px"
        }
        d = bz(a, b, b);
        if (d < 0 || d == null) d = a.style[b] || 0;
        d = parseFloat(d) || 0;
        if (c) for (; g < h; g++) d += parseFloat(f.css(a, "padding" + e[g])) || 0, c !== "padding" && (d += parseFloat(f.css(a, "border" + e[g] + "Width")) || 0), c === "margin" && (d += parseFloat(f.css(a, c + e[g])) || 0);
        return d + "px"
    }
    function bp(a, b) {
        b.src ? f.ajax({
            url: b.src,
            async: !1,
            dataType: "script"
        }) : f.globalEval((b.text || b.textContent || b.innerHTML || "").replace(bf, "/*$0*/")), b.parentNode && b.parentNode.removeChild(b)
    }
    function bo(a) {
        var b = c.createElement("div");
        bh.appendChild(b), b.innerHTML = a.outerHTML;
        return b.firstChild
    }
    function bn(a) {
        var b = (a.nodeName || "").toLowerCase();
        b === "input" ? bm(a) : b !== "script" && typeof a.getElementsByTagName != "undefined" && f.grep(a.getElementsByTagName("input"), bm)
    }
    function bm(a) {
        if (a.type === "checkbox" || a.type === "radio") a.defaultChecked = a.checked
    }
    function bl(a) {
        return typeof a.getElementsByTagName != "undefined" ? a.getElementsByTagName("*") : typeof a.querySelectorAll != "undefined" ? a.querySelectorAll("*") : []
    }
    function bk(a, b) {
        var c;
        if (b.nodeType === 1) {
            b.clearAttributes && b.clearAttributes(), b.mergeAttributes && b.mergeAttributes(a), c = b.nodeName.toLowerCase();
            if (c === "object") b.outerHTML = a.outerHTML;
            else if (c !== "input" || a.type !== "checkbox" && a.type !== "radio") {
                if (c === "option") b.selected = a.defaultSelected;
                else if (c === "input" || c === "textarea") b.defaultValue = a.defaultValue
            } else a.checked && (b.defaultChecked = b.checked = a.checked), b.value !== a.value && (b.value = a.value);
            b.removeAttribute(f.expando)
        }
    }
    function bj(a, b) {
        if (b.nodeType === 1 && !! f.hasData(a)) {
            var c, d, e, g = f._data(a),
                h = f._data(b, g),
                i = g.events;
            if (i) {
                delete h.handle, h.events = {};
                for (c in i) for (d = 0, e = i[c].length; d < e; d++) f.event.add(b, c + (i[c][d].namespace ? "." : "") + i[c][d].namespace, i[c][d], i[c][d].data)
            }
            h.data && (h.data = f.extend({}, h.data))
        }
    }
    function bi(a, b) {
        return f.nodeName(a, "table") ? a.getElementsByTagName("tbody")[0] || a.appendChild(a.ownerDocument.createElement("tbody")) : a
    }
    function U(a) {
        var b = V.split("|"),
            c = a.createDocumentFragment();
        if (c.createElement) while (b.length) c.createElement(b.pop());
        return c
    }
    function T(a, b, c) {
        b = b || 0;
        if (f.isFunction(b)) return f.grep(a, function(a, d) {
            var e = !! b.call(a, d, a);
            return e === c
        });
        if (b.nodeType) return f.grep(a, function(a, d) {
            return a === b === c
        });
        if (typeof b == "string") {
            var d = f.grep(a, function(a) {
                return a.nodeType === 1
            });
            if (O.test(b)) return f.filter(b, d, !c);
            b = f.filter(b, d)
        }
        return f.grep(a, function(a, d) {
            return f.inArray(a, b) >= 0 === c
        })
    }
    function S(a) {
        return !a || !a.parentNode || a.parentNode.nodeType === 11
    }
    function K() {
        return !0
    }
    function J() {
        return !1
    }
    function n(a, b, c) {
        var d = b + "defer",
            e = b + "queue",
            g = b + "mark",
            h = f._data(a, d);
        h && (c === "queue" || !f._data(a, e)) && (c === "mark" || !f._data(a, g)) && setTimeout(function() {
            !f._data(a, e) && !f._data(a, g) && (f.removeData(a, d, !0), h.fire())
        }, 0)
    }
    function m(a) {
        for (var b in a) {
            if (b === "data" && f.isEmptyObject(a[b])) continue;
            if (b !== "toJSON") return !1
        }
        return !0
    }
    function l(a, c, d) {
        if (d === b && a.nodeType === 1) {
            var e = "data-" + c.replace(k, "-$1").toLowerCase();
            d = a.getAttribute(e);
            if (typeof d == "string") {
                try {
                    d = d === "true" ? !0 : d === "false" ? !1 : d === "null" ? null : f.isNumeric(d) ? parseFloat(d) : j.test(d) ? f.parseJSON(d) : d
                } catch (g) {}
                f.data(a, c, d)
            } else d = b
        }
        return d
    }
    function h(a) {
        var b = g[a] = {},
            c, d;
        a = a.split(/\s+/);
        for (c = 0, d = a.length; c < d; c++) b[a[c]] = !0;
        return b
    }
    var c = a.document,
        d = a.navigator,
        e = a.location,
        f = function() {
            function J() {
                if (!e.isReady) {
                    try {
                        c.documentElement.doScroll("left")
                    } catch (a) {
                        setTimeout(J, 1);
                        return
                    }
                    e.ready()
                }
            }
            var e = function(a, b) {
                    return new e.fn.init(a, b, h)
                },
                f = a.jQuery,
                g = a.$,
                h, i = /^(?:[^#<]*(<[\w\W]+>)[^>]*$|#([\w\-]*)$)/,
                j = /\S/,
                k = /^\s+/,
                l = /\s+$/,
                m = /^<(\w+)\s*\/?>(?:<\/\1>)?$/,
                n = /^[\],:{}\s]*$/,
                o = /\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,
                p = /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
                q = /(?:^|:|,)(?:\s*\[)+/g,
                r = /(webkit)[ \/]([\w.]+)/,
                s = /(opera)(?:.*version)?[ \/]([\w.]+)/,
                t = /(msie) ([\w.]+)/,
                u = /(mozilla)(?:.*? rv:([\w.]+))?/,
                v = /-([a-z]|[0-9])/ig,
                w = /^-ms-/,
                x = function(a, b) {
                    return (b + "").toUpperCase()
                },
                y = d.userAgent,
                z, A, B, C = Object.prototype.toString,
                D = Object.prototype.hasOwnProperty,
                E = Array.prototype.push,
                F = Array.prototype.slice,
                G = String.prototype.trim,
                H = Array.prototype.indexOf,
                I = {};
            e.fn = e.prototype = {
                constructor: e,
                init: function(a, d, f) {
                    var g, h, j, k;
                    if (!a) return this;
                    if (a.nodeType) {
                        this.context = this[0] = a, this.length = 1;
                        return this
                    }
                    if (a === "body" && !d && c.body) {
                        this.context = c, this[0] = c.body, this.selector = a, this.length = 1;
                        return this
                    }
                    if (typeof a == "string") {
                        a.charAt(0) !== "<" || a.charAt(a.length - 1) !== ">" || a.length < 3 ? g = i.exec(a) : g = [null, a, null];
                        if (g && (g[1] || !d)) {
                            if (g[1]) {
                                d = d instanceof e ? d[0] : d, k = d ? d.ownerDocument || d : c, j = m.exec(a), j ? e.isPlainObject(d) ? (a = [c.createElement(j[1])], e.fn.attr.call(a, d, !0)) : a = [k.createElement(j[1])] : (j = e.buildFragment([g[1]], [k]), a = (j.cacheable ? e.clone(j.fragment) : j.fragment).childNodes);
                                return e.merge(this, a)
                            }
                            h = c.getElementById(g[2]);
                            if (h && h.parentNode) {
                                if (h.id !== g[2]) return f.find(a);
                                this.length = 1, this[0] = h
                            }
                            this.context = c, this.selector = a;
                            return this
                        }
                        return !d || d.jquery ? (d || f).find(a) : this.constructor(d).find(a)
                    }
                    if (e.isFunction(a)) return f.ready(a);
                    a.selector !== b && (this.selector = a.selector, this.context = a.context);
                    return e.makeArray(a, this)
                },
                selector: "",
                jquery: "1.7.1",
                length: 0,
                size: function() {
                    return this.length
                },
                toArray: function() {
                    return F.call(this, 0)
                },
                get: function(a) {
                    return a == null ? this.toArray() : a < 0 ? this[this.length + a] : this[a]
                },
                pushStack: function(a, b, c) {
                    var d = this.constructor();
                    e.isArray(a) ? E.apply(d, a) : e.merge(d, a), d.prevObject = this, d.context = this.context, b === "find" ? d.selector = this.selector + (this.selector ? " " : "") + c : b && (d.selector = this.selector + "." + b + "(" + c + ")");
                    return d
                },
                each: function(a, b) {
                    return e.each(this, a, b)
                },
                ready: function(a) {
                    e.bindReady(), A.add(a);
                    return this
                },
                eq: function(a) {
                    a = +a;
                    return a === -1 ? this.slice(a) : this.slice(a, a + 1)
                },
                first: function() {
                    return this.eq(0)
                },
                last: function() {
                    return this.eq(-1)
                },
                slice: function() {
                    return this.pushStack(F.apply(this, arguments), "slice", F.call(arguments).join(","))
                },
                map: function(a) {
                    return this.pushStack(e.map(this, function(b, c) {
                        return a.call(b, c, b)
                    }))
                },
                end: function() {
                    return this.prevObject || this.constructor(null)
                },
                push: E,
                sort: [].sort,
                splice: [].splice
            }, e.fn.init.prototype = e.fn, e.extend = e.fn.extend = function() {
                var a, c, d, f, g, h, i = arguments[0] || {},
                    j = 1,
                    k = arguments.length,
                    l = !1;
                typeof i == "boolean" && (l = i, i = arguments[1] || {}, j = 2), typeof i != "object" && !e.isFunction(i) && (i = {}), k === j && (i = this, --j);
                for (; j < k; j++) if ((a = arguments[j]) != null) for (c in a) {
                    d = i[c], f = a[c];
                    if (i === f) continue;
                    l && f && (e.isPlainObject(f) || (g = e.isArray(f))) ? (g ? (g = !1, h = d && e.isArray(d) ? d : []) : h = d && e.isPlainObject(d) ? d : {}, i[c] = e.extend(l, h, f)) : f !== b && (i[c] = f)
                }
                return i
            }, e.extend({
                noConflict: function(b) {
                    a.$ === e && (a.$ = g), b && a.jQuery === e && (a.jQuery = f);
                    return e
                },
                isReady: !1,
                readyWait: 1,
                holdReady: function(a) {
                    a ? e.readyWait++ : e.ready(!0)
                },
                ready: function(a) {
                    if (a === !0 && !--e.readyWait || a !== !0 && !e.isReady) {
                        if (!c.body) return setTimeout(e.ready, 1);
                        e.isReady = !0;
                        if (a !== !0 && --e.readyWait > 0) return;
                        A.fireWith(c, [e]), e.fn.trigger && e(c).trigger("ready").off("ready")
                    }
                },
                bindReady: function() {
                    if (!A) {
                        A = e.Callbacks("once memory");
                        if (c.readyState === "complete") return setTimeout(e.ready, 1);
                        if (c.addEventListener) c.addEventListener("DOMContentLoaded", B, !1), a.addEventListener("load", e.ready, !1);
                        else if (c.attachEvent) {
                            c.attachEvent("onreadystatechange", B), a.attachEvent("onload", e.ready);
                            var b = !1;
                            try {
                                b = a.frameElement == null
                            } catch (d) {}
                            c.documentElement.doScroll && b && J()
                        }
                    }
                },
                isFunction: function(a) {
                    return e.type(a) === "function"
                },
                isArray: Array.isArray ||
                    function(a) {
                        return e.type(a) === "array"
                    },
                isWindow: function(a) {
                    return a && typeof a == "object" && "setInterval" in a
                },
                isNumeric: function(a) {
                    return !isNaN(parseFloat(a)) && isFinite(a)
                },
                type: function(a) {
                    return a == null ? String(a) : I[C.call(a)] || "object"
                },
                isPlainObject: function(a) {
                    if (!a || e.type(a) !== "object" || a.nodeType || e.isWindow(a)) return !1;
                    try {
                        if (a.constructor && !D.call(a, "constructor") && !D.call(a.constructor.prototype, "isPrototypeOf")) return !1
                    } catch (c) {
                        return !1
                    }
                    var d;
                    for (d in a);
                    return d === b || D.call(a, d)
                },
                isEmptyObject: function(a) {
                    for (var b in a) return !1;
                    return !0
                },
                error: function(a) {
                    throw new Error(a)
                },
                parseJSON: function(b) {
                    if (typeof b != "string" || !b) return null;
                    b = e.trim(b);
                    if (a.JSON && a.JSON.parse) return a.JSON.parse(b);
                    if (n.test(b.replace(o, "@").replace(p, "]").replace(q, ""))) return (new Function("return " + b))();
                    e.error("Invalid JSON: " + b)
                },
                parseXML: function(c) {
                    var d, f;
                    try {
                        a.DOMParser ? (f = new DOMParser, d = f.parseFromString(c, "text/xml")) : (d = new ActiveXObject("Microsoft.XMLDOM"), d.async = "false", d.loadXML(c))
                    } catch (g) {
                        d = b
                    }(!d || !d.documentElement || d.getElementsByTagName("parsererror").length) && e.error("Invalid XML: " + c);
                    return d
                },
                noop: function() {},
                globalEval: function(b) {
                    b && j.test(b) && (a.execScript ||
                        function(b) {
                            a.eval.call(a, b)
                        })(b)
                },
                camelCase: function(a) {
                    return a.replace(w, "ms-").replace(v, x)
                },
                nodeName: function(a, b) {
                    return a.nodeName && a.nodeName.toUpperCase() === b.toUpperCase()
                },
                each: function(a, c, d) {
                    var f, g = 0,
                        h = a.length,
                        i = h === b || e.isFunction(a);
                    if (d) {
                        if (i) {
                            for (f in a) if (c.apply(a[f], d) === !1) break
                        } else
                            for (; g < h;) if (c.apply(a[g++], d) === !1) break
                    } else if (i) {
                        for (f in a) if (c.call(a[f], f, a[f]) === !1) break
                    } else
                        for (; g < h;) if (c.call(a[g], g, a[g++]) === !1) break;
                    return a
                },
                trim: G ?
                    function(a) {
                        return a == null ? "" : G.call(a)
                    } : function(a) {
                    return a == null ? "" : (a + "").replace(k, "").replace(l, "")
                },
                makeArray: function(a, b) {
                    var c = b || [];
                    if (a != null) {
                        var d = e.type(a);
                        a.length == null || d === "string" || d === "function" || d === "regexp" || e.isWindow(a) ? E.call(c, a) : e.merge(c, a)
                    }
                    return c
                },
                inArray: function(a, b, c) {
                    var d;
                    if (b) {
                        if (H) return H.call(b, a, c);
                        d = b.length, c = c ? c < 0 ? Math.max(0, d + c) : c : 0;
                        for (; c < d; c++) if (c in b && b[c] === a) return c
                    }
                    return -1
                },
                merge: function(a, c) {
                    var d = a.length,
                        e = 0;
                    if (typeof c.length == "number") for (var f = c.length; e < f; e++) a[d++] = c[e];
                    else
                        while (c[e] !== b) a[d++] = c[e++];
                    a.length = d;
                    return a
                },
                grep: function(a, b, c) {
                    var d = [],
                        e;
                    c = !! c;
                    for (var f = 0, g = a.length; f < g; f++) e = !! b(a[f], f), c !== e && d.push(a[f]);
                    return d
                },
                map: function(a, c, d) {
                    var f, g, h = [],
                        i = 0,
                        j = a.length,
                        k = a instanceof e || j !== b && typeof j == "number" && (j > 0 && a[0] && a[j - 1] || j === 0 || e.isArray(a));
                    if (k) for (; i < j; i++) f = c(a[i], i, d), f != null && (h[h.length] = f);
                    else
                        for (g in a) f = c(a[g], g, d), f != null && (h[h.length] = f);
                    return h.concat.apply([], h)
                },
                guid: 1,
                proxy: function(a, c) {
                    if (typeof c == "string") {
                        var d = a[c];
                        c = a, a = d
                    }
                    if (!e.isFunction(a)) return b;
                    var f = F.call(arguments, 2),
                        g = function() {
                            return a.apply(c, f.concat(F.call(arguments)))
                        };
                    g.guid = a.guid = a.guid || g.guid || e.guid++;
                    return g
                },
                access: function(a, c, d, f, g, h) {
                    var i = a.length;
                    if (typeof c == "object") {
                        for (var j in c) e.access(a, j, c[j], f, g, d);
                        return a
                    }
                    if (d !== b) {
                        f = !h && f && e.isFunction(d);
                        for (var k = 0; k < i; k++) g(a[k], c, f ? d.call(a[k], k, g(a[k], c)) : d, h);
                        return a
                    }
                    return i ? g(a[0], c) : b
                },
                now: function() {
                    return (new Date).getTime()
                },
                uaMatch: function(a) {
                    a = a.toLowerCase();
                    var b = r.exec(a) || s.exec(a) || t.exec(a) || a.indexOf("compatible") < 0 && u.exec(a) || [];
                    return {
                        browser: b[1] || "",
                        version: b[2] || "0"
                    }
                },
                sub: function() {
                    function a(b, c) {
                        return new a.fn.init(b, c)
                    }
                    e.extend(!0, a, this), a.superclass = this, a.fn = a.prototype = this(), a.fn.constructor = a, a.sub = this.sub, a.fn.init = function(d, f) {
                        f && f instanceof e && !(f instanceof a) && (f = a(f));
                        return e.fn.init.call(this, d, f, b)
                    }, a.fn.init.prototype = a.fn;
                    var b = a(c);
                    return a
                },
                browser: {}
            }), e.each("Boolean Number String Function Array Date RegExp Object".split(" "), function(a, b) {
                I["[object " + b + "]"] = b.toLowerCase()
            }), z = e.uaMatch(y), z.browser && (e.browser[z.browser] = !0, e.browser.version = z.version), e.browser.webkit && (e.browser.safari = !0), j.test("聽") && (k = /^[\s\xA0]+/, l = /[\s\xA0]+$/), h = e(c), c.addEventListener ? B = function() {
                c.removeEventListener("DOMContentLoaded", B, !1), e.ready()
            } : c.attachEvent && (B = function() {
                c.readyState === "complete" && (c.detachEvent("onreadystatechange", B), e.ready())
            });
            return e
        }(),
        g = {};
    f.Callbacks = function(a) {
        a = a ? g[a] || h(a) : {};
        var c = [],
            d = [],
            e, i, j, k, l, m = function(b) {
                var d, e, g, h, i;
                for (d = 0, e = b.length; d < e; d++) g = b[d], h = f.type(g), h === "array" ? m(g) : h === "function" && (!a.unique || !o.has(g)) && c.push(g)
            },
            n = function(b, f) {
                f = f || [], e = !a.memory || [b, f], i = !0, l = j || 0, j = 0, k = c.length;
                for (; c && l < k; l++) if (c[l].apply(b, f) === !1 && a.stopOnFalse) {
                    e = !0;
                    break
                }
                i = !1, c && (a.once ? e === !0 ? o.disable() : c = [] : d && d.length && (e = d.shift(), o.fireWith(e[0], e[1])))
            },
            o = {
                add: function() {
                    if (c) {
                        var a = c.length;
                        m(arguments), i ? k = c.length : e && e !== !0 && (j = a, n(e[0], e[1]))
                    }
                    return this
                },
                remove: function() {
                    if (c) {
                        var b = arguments,
                            d = 0,
                            e = b.length;
                        for (; d < e; d++) for (var f = 0; f < c.length; f++) if (b[d] === c[f]) {
                            i && f <= k && (k--, f <= l && l--), c.splice(f--, 1);
                            if (a.unique) break
                        }
                    }
                    return this
                },
                has: function(a) {
                    if (c) {
                        var b = 0,
                            d = c.length;
                        for (; b < d; b++) if (a === c[b]) return !0
                    }
                    return !1
                },
                empty: function() {
                    c = [];
                    return this
                },
                disable: function() {
                    c = d = e = b;
                    return this
                },
                disabled: function() {
                    return !c
                },
                lock: function() {
                    d = b, (!e || e === !0) && o.disable();
                    return this
                },
                locked: function() {
                    return !d
                },
                fireWith: function(b, c) {
                    d && (i ? a.once || d.push([b, c]) : (!a.once || !e) && n(b, c));
                    return this
                },
                fire: function() {
                    o.fireWith(this, arguments);
                    return this
                },
                fired: function() {
                    return !!e
                }
            };
        return o
    };
    var i = [].slice;
    f.extend({
        Deferred: function(a) {
            var b = f.Callbacks("once memory"),
                c = f.Callbacks("once memory"),
                d = f.Callbacks("memory"),
                e = "pending",
                g = {
                    resolve: b,
                    reject: c,
                    notify: d
                },
                h = {
                    done: b.add,
                    fail: c.add,
                    progress: d.add,
                    state: function() {
                        return e
                    },
                    isResolved: b.fired,
                    isRejected: c.fired,
                    then: function(a, b, c) {
                        i.done(a).fail(b).progress(c);
                        return this
                    },
                    always: function() {
                        i.done.apply(i, arguments).fail.apply(i, arguments);
                        return this
                    },
                    pipe: function(a, b, c) {
                        return f.Deferred(function(d) {
                            f.each({
                                done: [a, "resolve"],
                                fail: [b, "reject"],
                                progress: [c, "notify"]
                            }, function(a, b) {
                                var c = b[0],
                                    e = b[1],
                                    g;
                                f.isFunction(c) ? i[a](function() {
                                    g = c.apply(this, arguments), g && f.isFunction(g.promise) ? g.promise().then(d.resolve, d.reject, d.notify) : d[e + "With"](this === i ? d : this, [g])
                                }) : i[a](d[e])
                            })
                        }).promise()
                    },
                    promise: function(a) {
                        if (a == null) a = h;
                        else
                            for (var b in h) a[b] = h[b];
                        return a
                    }
                },
                i = h.promise({}),
                j;
            for (j in g) i[j] = g[j].fire, i[j + "With"] = g[j].fireWith;
            i.done(function() {
                e = "resolved"
            }, c.disable, d.lock).fail(function() {
                    e = "rejected"
                }, b.disable, d.lock), a && a.call(i, i);
            return i
        },
        when: function(a) {
            function m(a) {
                return function(b) {
                    e[a] = arguments.length > 1 ? i.call(arguments, 0) : b, j.notifyWith(k, e)
                }
            }
            function l(a) {
                return function(c) {
                    b[a] = arguments.length > 1 ? i.call(arguments, 0) : c, --g || j.resolveWith(j, b)
                }
            }
            var b = i.call(arguments, 0),
                c = 0,
                d = b.length,
                e = Array(d),
                g = d,
                h = d,
                j = d <= 1 && a && f.isFunction(a.promise) ? a : f.Deferred(),
                k = j.promise();
            if (d > 1) {
                for (; c < d; c++) b[c] && b[c].promise && f.isFunction(b[c].promise) ? b[c].promise().then(l(c), j.reject, m(c)) : --g;
                g || j.resolveWith(j, b)
            } else j !== a && j.resolveWith(j, d ? [a] : []);
            return k
        }
    }), f.support = function() {
        var b, d, e, g, h, i, j, k, l, m, n, o, p, q = c.createElement("div"),
            r = c.documentElement;
        q.setAttribute("className", "t"), q.innerHTML = "   <link/><table></table><a href='/a' style='top:1px;float:left;opacity:.55;'>a</a><input type='checkbox'/>", d = q.getElementsByTagName("*"), e = q.getElementsByTagName("a")[0];
        if (!d || !d.length || !e) return {};
        g = c.createElement("select"), h = g.appendChild(c.createElement("option")), i = q.getElementsByTagName("input")[0], b = {
            leadingWhitespace: q.firstChild.nodeType === 3,
            tbody: !q.getElementsByTagName("tbody").length,
            htmlSerialize: !! q.getElementsByTagName("link").length,
            style: /top/.test(e.getAttribute("style")),
            hrefNormalized: e.getAttribute("href") === "/a",
            opacity: /^0.55/.test(e.style.opacity),
            cssFloat: !! e.style.cssFloat,
            checkOn: i.value === "on",
            optSelected: h.selected,
            getSetAttribute: q.className !== "t",
            enctype: !! c.createElement("form").enctype,
            html5Clone: c.createElement("nav").cloneNode(!0).outerHTML !== "<:nav></:nav>",
            submitBubbles: !0,
            changeBubbles: !0,
            focusinBubbles: !1,
            deleteExpando: !0,
            noCloneEvent: !0,
            inlineBlockNeedsLayout: !1,
            shrinkWrapBlocks: !1,
            reliableMarginRight: !0
        }, i.checked = !0, b.noCloneChecked = i.cloneNode(!0).checked, g.disabled = !0, b.optDisabled = !h.disabled;
        try {
            delete q.test
        } catch (s) {
            b.deleteExpando = !1
        }!q.addEventListener && q.attachEvent && q.fireEvent && (q.attachEvent("onclick", function() {
            b.noCloneEvent = !1
        }), q.cloneNode(!0).fireEvent("onclick")), i = c.createElement("input"), i.value = "t", i.setAttribute("type", "radio"), b.radioValue = i.value === "t", i.setAttribute("checked", "checked"), q.appendChild(i), k = c.createDocumentFragment(), k.appendChild(q.lastChild), b.checkClone = k.cloneNode(!0).cloneNode(!0).lastChild.checked, b.appendChecked = i.checked, k.removeChild(i), k.appendChild(q), q.innerHTML = "", a.getComputedStyle && (j = c.createElement("div"), j.style.width = "0", j.style.marginRight = "0", q.style.width = "2px", q.appendChild(j), b.reliableMarginRight = (parseInt((a.getComputedStyle(j, null) || {
            marginRight: 0
        }).marginRight, 10) || 0) === 0);
        if (q.attachEvent) for (o in {
            submit: 1,
            change: 1,
            focusin: 1
        }) n = "on" + o, p = n in q, p || (q.setAttribute(n, "return;"), p = typeof q[n] == "function"), b[o + "Bubbles"] = p;
        k.removeChild(q), k = g = h = j = q = i = null, f(function() {
            var a, d, e, g, h, i, j, k, m, n, o, r = c.getElementsByTagName("body")[0];
            !r || (j = 1, k = "position:absolute;top:0;left:0;width:1px;height:1px;margin:0;", m = "visibility:hidden;border:0;", n = "style='" + k + "border:5px solid #000;padding:0;'", o = "<div " + n + "><div></div></div>" + "<table " + n + " cellpadding='0' cellspacing='0'>" + "<tr><td></td></tr></table>", a = c.createElement("div"), a.style.cssText = m + "width:0;height:0;position:static;top:0;margin-top:" + j + "px", r.insertBefore(a, r.firstChild), q = c.createElement("div"), a.appendChild(q), q.innerHTML = "<table><tr><td style='padding:0;border:0;display:none'></td><td>t</td></tr></table>", l = q.getElementsByTagName("td"), p = l[0].offsetHeight === 0, l[0].style.display = "", l[1].style.display = "none", b.reliableHiddenOffsets = p && l[0].offsetHeight === 0, q.innerHTML = "", q.style.width = q.style.paddingLeft = "1px", f.boxModel = b.boxModel = q.offsetWidth === 2, typeof q.style.zoom != "undefined" && (q.style.display = "inline", q.style.zoom = 1, b.inlineBlockNeedsLayout = q.offsetWidth === 2, q.style.display = "", q.innerHTML = "<div style='width:4px;'></div>", b.shrinkWrapBlocks = q.offsetWidth !== 2), q.style.cssText = k + m, q.innerHTML = o, d = q.firstChild, e = d.firstChild, h = d.nextSibling.firstChild.firstChild, i = {
                doesNotAddBorder: e.offsetTop !== 5,
                doesAddBorderForTableAndCells: h.offsetTop === 5
            }, e.style.position = "fixed", e.style.top = "20px", i.fixedPosition = e.offsetTop === 20 || e.offsetTop === 15, e.style.position = e.style.top = "", d.style.overflow = "hidden", d.style.position = "relative", i.subtractsBorderForOverflowNotVisible = e.offsetTop === -5, i.doesNotIncludeMarginInBodyOffset = r.offsetTop !== j, r.removeChild(a), q = a = null, f.extend(b, i))
        });
        return b
    }();
    var j = /^(?:\{.*\}|\[.*\])$/,
        k = /([A-Z])/g;
    f.extend({
        cache: {},
        uuid: 0,
        expando: "jQuery" + (f.fn.jquery + Math.random()).replace(/\D/g, ""),
        noData: {
            embed: !0,
            object: "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",
            applet: !0
        },
        hasData: function(a) {
            a = a.nodeType ? f.cache[a[f.expando]] : a[f.expando];
            return !!a && !m(a)
        },
        data: function(a, c, d, e) {
            if ( !! f.acceptData(a)) {
                var g, h, i, j = f.expando,
                    k = typeof c == "string",
                    l = a.nodeType,
                    m = l ? f.cache : a,
                    n = l ? a[j] : a[j] && j,
                    o = c === "events";
                if ((!n || !m[n] || !o && !e && !m[n].data) && k && d === b) return;
                n || (l ? a[j] = n = ++f.uuid : n = j), m[n] || (m[n] = {}, l || (m[n].toJSON = f.noop));
                if (typeof c == "object" || typeof c == "function") e ? m[n] = f.extend(m[n], c) : m[n].data = f.extend(m[n].data, c);
                g = h = m[n], e || (h.data || (h.data = {}), h = h.data), d !== b && (h[f.camelCase(c)] = d);
                if (o && !h[c]) return g.events;
                k ? (i = h[c], i == null && (i = h[f.camelCase(c)])) : i = h;
                return i
            }
        },
        removeData: function(a, b, c) {
            if ( !! f.acceptData(a)) {
                var d, e, g, h = f.expando,
                    i = a.nodeType,
                    j = i ? f.cache : a,
                    k = i ? a[h] : h;
                if (!j[k]) return;
                if (b) {
                    d = c ? j[k] : j[k].data;
                    if (d) {
                        f.isArray(b) || (b in d ? b = [b] : (b = f.camelCase(b), b in d ? b = [b] : b = b.split(" ")));
                        for (e = 0, g = b.length; e < g; e++) delete d[b[e]];
                        if (!(c ? m : f.isEmptyObject)(d)) return
                    }
                }
                if (!c) {
                    delete j[k].data;
                    if (!m(j[k])) return
                }
                f.support.deleteExpando || !j.setInterval ? delete j[k] : j[k] = null, i && (f.support.deleteExpando ? delete a[h] : a.removeAttribute ? a.removeAttribute(h) : a[h] = null)
            }
        },
        _data: function(a, b, c) {
            return f.data(a, b, c, !0)
        },
        acceptData: function(a) {
            if (a.nodeName) {
                var b = f.noData[a.nodeName.toLowerCase()];
                if (b) return b !== !0 && a.getAttribute("classid") === b
            }
            return !0
        }
    }), f.fn.extend({
        data: function(a, c) {
            var d, e, g, h = null;
            if (typeof a == "undefined") {
                if (this.length) {
                    h = f.data(this[0]);
                    if (this[0].nodeType === 1 && !f._data(this[0], "parsedAttrs")) {
                        e = this[0].attributes;
                        for (var i = 0, j = e.length; i < j; i++) g = e[i].name, g.indexOf("data-") === 0 && (g = f.camelCase(g.substring(5)), l(this[0], g, h[g]));
                        f._data(this[0], "parsedAttrs", !0)
                    }
                }
                return h
            }
            if (typeof a == "object") return this.each(function() {
                f.data(this, a)
            });
            d = a.split("."), d[1] = d[1] ? "." + d[1] : "";
            if (c === b) {
                h = this.triggerHandler("getData" + d[1] + "!", [d[0]]), h === b && this.length && (h = f.data(this[0], a), h = l(this[0], a, h));
                return h === b && d[1] ? this.data(d[0]) : h
            }
            return this.each(function() {
                var b = f(this),
                    e = [d[0], c];
                b.triggerHandler("setData" + d[1] + "!", e), f.data(this, a, c), b.triggerHandler("changeData" + d[1] + "!", e)
            })
        },
        removeData: function(a) {
            return this.each(function() {
                f.removeData(this, a)
            })
        }
    }), f.extend({
        _mark: function(a, b) {
            a && (b = (b || "fx") + "mark", f._data(a, b, (f._data(a, b) || 0) + 1))
        },
        _unmark: function(a, b, c) {
            a !== !0 && (c = b, b = a, a = !1);
            if (b) {
                c = c || "fx";
                var d = c + "mark",
                    e = a ? 0 : (f._data(b, d) || 1) - 1;
                e ? f._data(b, d, e) : (f.removeData(b, d, !0), n(b, c, "mark"))
            }
        },
        queue: function(a, b, c) {
            var d;
            if (a) {
                b = (b || "fx") + "queue", d = f._data(a, b), c && (!d || f.isArray(c) ? d = f._data(a, b, f.makeArray(c)) : d.push(c));
                return d || []
            }
        },
        dequeue: function(a, b) {
            b = b || "fx";
            var c = f.queue(a, b),
                d = c.shift(),
                e = {};
            d === "inprogress" && (d = c.shift()), d && (b === "fx" && c.unshift("inprogress"), f._data(a, b + ".run", e), d.call(a, function() {
                f.dequeue(a, b)
            }, e)), c.length || (f.removeData(a, b + "queue " + b + ".run", !0), n(a, b, "queue"))
        }
    }), f.fn.extend({
        queue: function(a, c) {
            typeof a != "string" && (c = a, a = "fx");
            if (c === b) return f.queue(this[0], a);
            return this.each(function() {
                var b = f.queue(this, a, c);
                a === "fx" && b[0] !== "inprogress" && f.dequeue(this, a)
            })
        },
        dequeue: function(a) {
            return this.each(function() {
                f.dequeue(this, a)
            })
        },
        delay: function(a, b) {
            a = f.fx ? f.fx.speeds[a] || a : a, b = b || "fx";
            return this.queue(b, function(b, c) {
                var d = setTimeout(b, a);
                c.stop = function() {
                    clearTimeout(d)
                }
            })
        },
        clearQueue: function(a) {
            return this.queue(a || "fx", [])
        },
        promise: function(a, c) {
            function m() {
                --h || d.resolveWith(e, [e])
            }
            typeof a != "string" && (c = a, a = b), a = a || "fx";
            var d = f.Deferred(),
                e = this,
                g = e.length,
                h = 1,
                i = a + "defer",
                j = a + "queue",
                k = a + "mark",
                l;
            while (g--) if (l = f.data(e[g], i, b, !0) || (f.data(e[g], j, b, !0) || f.data(e[g], k, b, !0)) && f.data(e[g], i, f.Callbacks("once memory"), !0)) h++, l.add(m);
            m();
            return d.promise()
        }
    });
    var o = /[\n\t\r]/g,
        p = /\s+/,
        q = /\r/g,
        r = /^(?:button|input)$/i,
        s = /^(?:button|input|object|select|textarea)$/i,
        t = /^a(?:rea)?$/i,
        u = /^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i,
        v = f.support.getSetAttribute,
        w, x, y;
    f.fn.extend({
        attr: function(a, b) {
            return f.access(this, a, b, !0, f.attr)
        },
        removeAttr: function(a) {
            return this.each(function() {
                f.removeAttr(this, a)
            })
        },
        prop: function(a, b) {
            return f.access(this, a, b, !0, f.prop)
        },
        removeProp: function(a) {
            a = f.propFix[a] || a;
            return this.each(function() {
                try {
                    this[a] = b, delete this[a]
                } catch (c) {}
            })
        },
        addClass: function(a) {
            var b, c, d, e, g, h, i;
            if (f.isFunction(a)) return this.each(function(b) {
                f(this).addClass(a.call(this, b, this.className))
            });
            if (a && typeof a == "string") {
                b = a.split(p);
                for (c = 0, d = this.length; c < d; c++) {
                    e = this[c];
                    if (e.nodeType === 1) if (!e.className && b.length === 1) e.className = a;
                    else {
                        g = " " + e.className + " ";
                        for (h = 0, i = b.length; h < i; h++)~g.indexOf(" " + b[h] + " ") || (g += b[h] + " ");
                        e.className = f.trim(g)
                    }
                }
            }
            return this
        },
        removeClass: function(a) {
            var c, d, e, g, h, i, j;
            if (f.isFunction(a)) return this.each(function(b) {
                f(this).removeClass(a.call(this, b, this.className))
            });
            if (a && typeof a == "string" || a === b) {
                c = (a || "").split(p);
                for (d = 0, e = this.length; d < e; d++) {
                    g = this[d];
                    if (g.nodeType === 1 && g.className) if (a) {
                        h = (" " + g.className + " ").replace(o, " ");
                        for (i = 0, j = c.length; i < j; i++) h = h.replace(" " + c[i] + " ", " ");
                        g.className = f.trim(h)
                    } else g.className = ""
                }
            }
            return this
        },
        toggleClass: function(a, b) {
            var c = typeof a,
                d = typeof b == "boolean";
            if (f.isFunction(a)) return this.each(function(c) {
                f(this).toggleClass(a.call(this, c, this.className, b), b)
            });
            return this.each(function() {
                if (c === "string") {
                    var e, g = 0,
                        h = f(this),
                        i = b,
                        j = a.split(p);
                    while (e = j[g++]) i = d ? i : !h.hasClass(e), h[i ? "addClass" : "removeClass"](e)
                } else if (c === "undefined" || c === "boolean") this.className && f._data(this, "__className__", this.className), this.className = this.className || a === !1 ? "" : f._data(this, "__className__") || ""
            })
        },
        hasClass: function(a) {
            var b = " " + a + " ",
                c = 0,
                d = this.length;
            for (; c < d; c++) if (this[c].nodeType === 1 && (" " + this[c].className + " ").replace(o, " ").indexOf(b) > -1) return !0;
            return !1
        },
        val: function(a) {
            var c, d, e, g = this[0]; {
                if ( !! arguments.length) {
                    e = f.isFunction(a);
                    return this.each(function(d) {
                        var g = f(this),
                            h;
                        if (this.nodeType === 1) {
                            e ? h = a.call(this, d, g.val()) : h = a, h == null ? h = "" : typeof h == "number" ? h += "" : f.isArray(h) && (h = f.map(h, function(a) {
                                return a == null ? "" : a + ""
                            })), c = f.valHooks[this.nodeName.toLowerCase()] || f.valHooks[this.type];
                            if (!c || !("set" in c) || c.set(this, h, "value") === b) this.value = h
                        }
                    })
                }
                if (g) {
                    c = f.valHooks[g.nodeName.toLowerCase()] || f.valHooks[g.type];
                    if (c && "get" in c && (d = c.get(g, "value")) !== b) return d;
                    d = g.value;
                    return typeof d == "string" ? d.replace(q, "") : d == null ? "" : d
                }
            }
        }
    }), f.extend({
        valHooks: {
            option: {
                get: function(a) {
                    var b = a.attributes.value;
                    return !b || b.specified ? a.value : a.text
                }
            },
            select: {
                get: function(a) {
                    var b, c, d, e, g = a.selectedIndex,
                        h = [],
                        i = a.options,
                        j = a.type === "select-one";
                    if (g < 0) return null;
                    c = j ? g : 0, d = j ? g + 1 : i.length;
                    for (; c < d; c++) {
                        e = i[c];
                        if (e.selected && (f.support.optDisabled ? !e.disabled : e.getAttribute("disabled") === null) && (!e.parentNode.disabled || !f.nodeName(e.parentNode, "optgroup"))) {
                            b = f(e).val();
                            if (j) return b;
                            h.push(b)
                        }
                    }
                    if (j && !h.length && i.length) return f(i[g]).val();
                    return h
                },
                set: function(a, b) {
                    var c = f.makeArray(b);
                    f(a).find("option").each(function() {
                        this.selected = f.inArray(f(this).val(), c) >= 0
                    }), c.length || (a.selectedIndex = -1);
                    return c
                }
            }
        },
        attrFn: {
            val: !0,
            css: !0,
            html: !0,
            text: !0,
            data: !0,
            width: !0,
            height: !0,
            offset: !0
        },
        attr: function(a, c, d, e) {
            var g, h, i, j = a.nodeType;
            if ( !! a && j !== 3 && j !== 8 && j !== 2) {
                if (e && c in f.attrFn) return f(a)[c](d);
                if (typeof a.getAttribute == "undefined") return f.prop(a, c, d);
                i = j !== 1 || !f.isXMLDoc(a), i && (c = c.toLowerCase(), h = f.attrHooks[c] || (u.test(c) ? x : w));
                if (d !== b) {
                    if (d === null) {
                        f.removeAttr(a, c);
                        return
                    }
                    if (h && "set" in h && i && (g = h.set(a, d, c)) !== b) return g;
                    a.setAttribute(c, "" + d);
                    return d
                }
                if (h && "get" in h && i && (g = h.get(a, c)) !== null) return g;
                g = a.getAttribute(c);
                return g === null ? b : g
            }
        },
        removeAttr: function(a, b) {
            var c, d, e, g, h = 0;
            if (b && a.nodeType === 1) {
                d = b.toLowerCase().split(p), g = d.length;
                for (; h < g; h++) e = d[h], e && (c = f.propFix[e] || e, f.attr(a, e, ""), a.removeAttribute(v ? e : c), u.test(e) && c in a && (a[c] = !1))
            }
        },
        attrHooks: {
            type: {
                set: function(a, b) {
                    if (r.test(a.nodeName) && a.parentNode) f.error("type property can't be changed");
                    else if (!f.support.radioValue && b === "radio" && f.nodeName(a, "input")) {
                        var c = a.value;
                        a.setAttribute("type", b), c && (a.value = c);
                        return b
                    }
                }
            },
            value: {
                get: function(a, b) {
                    if (w && f.nodeName(a, "button")) return w.get(a, b);
                    return b in a ? a.value : null
                },
                set: function(a, b, c) {
                    if (w && f.nodeName(a, "button")) return w.set(a, b, c);
                    a.value = b
                }
            }
        },
        propFix: {
            tabindex: "tabIndex",
            readonly: "readOnly",
            "for": "htmlFor",
            "class": "className",
            maxlength: "maxLength",
            cellspacing: "cellSpacing",
            cellpadding: "cellPadding",
            rowspan: "rowSpan",
            colspan: "colSpan",
            usemap: "useMap",
            frameborder: "frameBorder",
            contenteditable: "contentEditable"
        },
        prop: function(a, c, d) {
            var e, g, h, i = a.nodeType;
            if ( !! a && i !== 3 && i !== 8 && i !== 2) {
                h = i !== 1 || !f.isXMLDoc(a), h && (c = f.propFix[c] || c, g = f.propHooks[c]);
                return d !== b ? g && "set" in g && (e = g.set(a, d, c)) !== b ? e : a[c] = d : g && "get" in g && (e = g.get(a, c)) !== null ? e : a[c]
            }
        },
        propHooks: {
            tabIndex: {
                get: function(a) {
                    var c = a.getAttributeNode("tabindex");
                    return c && c.specified ? parseInt(c.value, 10) : s.test(a.nodeName) || t.test(a.nodeName) && a.href ? 0 : b
                }
            }
        }
    }), f.attrHooks.tabindex = f.propHooks.tabIndex, x = {
        get: function(a, c) {
            var d, e = f.prop(a, c);
            return e === !0 || typeof e != "boolean" && (d = a.getAttributeNode(c)) && d.nodeValue !== !1 ? c.toLowerCase() : b
        },
        set: function(a, b, c) {
            var d;
            b === !1 ? f.removeAttr(a, c) : (d = f.propFix[c] || c, d in a && (a[d] = !0), a.setAttribute(c, c.toLowerCase()));
            return c
        }
    }, v || (y = {
        name: !0,
        id: !0
    }, w = f.valHooks.button = {
        get: function(a, c) {
            var d;
            d = a.getAttributeNode(c);
            return d && (y[c] ? d.nodeValue !== "" : d.specified) ? d.nodeValue : b
        },
        set: function(a, b, d) {
            var e = a.getAttributeNode(d);
            e || (e = c.createAttribute(d), a.setAttributeNode(e));
            return e.nodeValue = b + ""
        }
    }, f.attrHooks.tabindex.set = w.set, f.each(["width", "height"], function(a, b) {
        f.attrHooks[b] = f.extend(f.attrHooks[b], {
            set: function(a, c) {
                if (c === "") {
                    a.setAttribute(b, "auto");
                    return c
                }
            }
        })
    }), f.attrHooks.contenteditable = {
        get: w.get,
        set: function(a, b, c) {
            b === "" && (b = "false"), w.set(a, b, c)
        }
    }), f.support.hrefNormalized || f.each(["href", "src", "width", "height"], function(a, c) {
        f.attrHooks[c] = f.extend(f.attrHooks[c], {
            get: function(a) {
                var d = a.getAttribute(c, 2);
                return d === null ? b : d
            }
        })
    }), f.support.style || (f.attrHooks.style = {
        get: function(a) {
            return a.style.cssText.toLowerCase() || b
        },
        set: function(a, b) {
            return a.style.cssText = "" + b
        }
    }), f.support.optSelected || (f.propHooks.selected = f.extend(f.propHooks.selected, {
        get: function(a) {
            var b = a.parentNode;
            b && (b.selectedIndex, b.parentNode && b.parentNode.selectedIndex);
            return null
        }
    })), f.support.enctype || (f.propFix.enctype = "encoding"), f.support.checkOn || f.each(["radio", "checkbox"], function() {
        f.valHooks[this] = {
            get: function(a) {
                return a.getAttribute("value") === null ? "on" : a.value
            }
        }
    }), f.each(["radio", "checkbox"], function() {
        f.valHooks[this] = f.extend(f.valHooks[this], {
            set: function(a, b) {
                if (f.isArray(b)) return a.checked = f.inArray(f(a).val(), b) >= 0
            }
        })
    });
    var z = /^(?:textarea|input|select)$/i,
        A = /^([^\.]*)?(?:\.(.+))?$/,
        B = /\bhover(\.\S+)?\b/,
        C = /^key/,
        D = /^(?:mouse|contextmenu)|click/,
        E = /^(?:focusinfocus|focusoutblur)$/,
        F = /^(\w*)(?:#([\w\-]+))?(?:\.([\w\-]+))?$/,
        G = function(a) {
            var b = F.exec(a);
            b && (b[1] = (b[1] || "").toLowerCase(), b[3] = b[3] && new RegExp("(?:^|\\s)" + b[3] + "(?:\\s|$)"));
            return b
        },
        H = function(a, b) {
            var c = a.attributes || {};
            return (!b[1] || a.nodeName.toLowerCase() === b[1]) && (!b[2] || (c.id || {}).value === b[2]) && (!b[3] || b[3].test((c["class"] || {}).value))
        },
        I = function(a) {
            return f.event.special.hover ? a : a.replace(B, "mouseenter$1 mouseleave$1")
        };
    f.event = {
        add: function(a, c, d, e, g) {
            var h, i, j, k, l, m, n, o, p, q, r, s;
            if (!(a.nodeType === 3 || a.nodeType === 8 || !c || !d || !(h = f._data(a)))) {
                d.handler && (p = d, d = p.handler), d.guid || (d.guid = f.guid++), j = h.events, j || (h.events = j = {}), i = h.handle, i || (h.handle = i = function(a) {
                    return typeof f != "undefined" && (!a || f.event.triggered !== a.type) ? f.event.dispatch.apply(i.elem, arguments) : b
                }, i.elem = a), c = f.trim(I(c)).split(" ");
                for (k = 0; k < c.length; k++) {
                    l = A.exec(c[k]) || [], m = l[1], n = (l[2] || "").split(".").sort(), s = f.event.special[m] || {}, m = (g ? s.delegateType : s.bindType) || m, s = f.event.special[m] || {}, o = f.extend({
                        type: m,
                        origType: l[1],
                        data: e,
                        handler: d,
                        guid: d.guid,
                        selector: g,
                        quick: G(g),
                        namespace: n.join(".")
                    }, p), r = j[m];
                    if (!r) {
                        r = j[m] = [], r.delegateCount = 0;
                        if (!s.setup || s.setup.call(a, e, n, i) === !1) a.addEventListener ? a.addEventListener(m, i, !1) : a.attachEvent && a.attachEvent("on" + m, i)
                    }
                    s.add && (s.add.call(a, o), o.handler.guid || (o.handler.guid = d.guid)), g ? r.splice(r.delegateCount++, 0, o) : r.push(o), f.event.global[m] = !0
                }
                a = null
            }
        },
        global: {},
        remove: function(a, b, c, d, e) {
            var g = f.hasData(a) && f._data(a),
                h, i, j, k, l, m, n, o, p, q, r, s;
            if ( !! g && !! (o = g.events)) {
                b = f.trim(I(b || "")).split(" ");
                for (h = 0; h < b.length; h++) {
                    i = A.exec(b[h]) || [], j = k = i[1], l = i[2];
                    if (!j) {
                        for (j in o) f.event.remove(a, j + b[h], c, d, !0);
                        continue
                    }
                    p = f.event.special[j] || {}, j = (d ? p.delegateType : p.bindType) || j, r = o[j] || [], m = r.length, l = l ? new RegExp("(^|\\.)" + l.split(".").sort().join("\\.(?:.*\\.)?") + "(\\.|$)") : null;
                    for (n = 0; n < r.length; n++) s = r[n], (e || k === s.origType) && (!c || c.guid === s.guid) && (!l || l.test(s.namespace)) && (!d || d === s.selector || d === "**" && s.selector) && (r.splice(n--, 1), s.selector && r.delegateCount--, p.remove && p.remove.call(a, s));
                    r.length === 0 && m !== r.length && ((!p.teardown || p.teardown.call(a, l) === !1) && f.removeEvent(a, j, g.handle), delete o[j])
                }
                f.isEmptyObject(o) && (q = g.handle, q && (q.elem = null), f.removeData(a, ["events", "handle"], !0))
            }
        },
        customEvent: {
            getData: !0,
            setData: !0,
            changeData: !0
        },
        trigger: function(c, d, e, g) {
            if (!e || e.nodeType !== 3 && e.nodeType !== 8) {
                var h = c.type || c,
                    i = [],
                    j, k, l, m, n, o, p, q, r, s;
                if (E.test(h + f.event.triggered)) return;
                h.indexOf("!") >= 0 && (h = h.slice(0, -1), k = !0), h.indexOf(".") >= 0 && (i = h.split("."), h = i.shift(), i.sort());
                if ((!e || f.event.customEvent[h]) && !f.event.global[h]) return;
                c = typeof c == "object" ? c[f.expando] ? c : new f.Event(h, c) : new f.Event(h), c.type = h, c.isTrigger = !0, c.exclusive = k, c.namespace = i.join("."), c.namespace_re = c.namespace ? new RegExp("(^|\\.)" + i.join("\\.(?:.*\\.)?") + "(\\.|$)") : null, o = h.indexOf(":") < 0 ? "on" + h : "";
                if (!e) {
                    j = f.cache;
                    for (l in j) j[l].events && j[l].events[h] && f.event.trigger(c, d, j[l].handle.elem, !0);
                    return
                }
                c.result = b, c.target || (c.target = e), d = d != null ? f.makeArray(d) : [], d.unshift(c), p = f.event.special[h] || {};
                if (p.trigger && p.trigger.apply(e, d) === !1) return;
                r = [
                    [e, p.bindType || h]
                ];
                if (!g && !p.noBubble && !f.isWindow(e)) {
                    s = p.delegateType || h, m = E.test(s + h) ? e : e.parentNode, n = null;
                    for (; m; m = m.parentNode) r.push([m, s]), n = m;
                    n && n === e.ownerDocument && r.push([n.defaultView || n.parentWindow || a, s])
                }
                for (l = 0; l < r.length && !c.isPropagationStopped(); l++) m = r[l][0], c.type = r[l][1], q = (f._data(m, "events") || {})[c.type] && f._data(m, "handle"), q && q.apply(m, d), q = o && m[o], q && f.acceptData(m) && q.apply(m, d) === !1 && c.preventDefault();
                c.type = h, !g && !c.isDefaultPrevented() && (!p._default || p._default.apply(e.ownerDocument, d) === !1) && (h !== "click" || !f.nodeName(e, "a")) && f.acceptData(e) && o && e[h] && (h !== "focus" && h !== "blur" || c.target.offsetWidth !== 0) && !f.isWindow(e) && (n = e[o], n && (e[o] = null), f.event.triggered = h, e[h](), f.event.triggered = b, n && (e[o] = n));
                return c.result
            }
        },
        dispatch: function(c) {
            c = f.event.fix(c || a.event);
            var d = (f._data(this, "events") || {})[c.type] || [],
                e = d.delegateCount,
                g = [].slice.call(arguments, 0),
                h = !c.exclusive && !c.namespace,
                i = [],
                j, k, l, m, n, o, p, q, r, s, t;
            g[0] = c, c.delegateTarget = this;
            if (e && !c.target.disabled && (!c.button || c.type !== "click")) {
                m = f(this), m.context = this.ownerDocument || this;
                for (l = c.target; l != this; l = l.parentNode || this) {
                    o = {}, q = [], m[0] = l;
                    for (j = 0; j < e; j++) r = d[j], s = r.selector, o[s] === b && (o[s] = r.quick ? H(l, r.quick) : m.is(s)), o[s] && q.push(r);
                    q.length && i.push({
                        elem: l,
                        matches: q
                    })
                }
            }
            d.length > e && i.push({
                elem: this,
                matches: d.slice(e)
            });
            for (j = 0; j < i.length && !c.isPropagationStopped(); j++) {
                p = i[j], c.currentTarget = p.elem;
                for (k = 0; k < p.matches.length && !c.isImmediatePropagationStopped(); k++) {
                    r = p.matches[k];
                    if (h || !c.namespace && !r.namespace || c.namespace_re && c.namespace_re.test(r.namespace)) c.data = r.data, c.handleObj = r, n = ((f.event.special[r.origType] || {}).handle || r.handler).apply(p.elem, g), n !== b && (c.result = n, n === !1 && (c.preventDefault(), c.stopPropagation()))
                }
            }
            return c.result
        },
        props: "attrChange attrName relatedNode srcElement altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "),
            filter: function(a, b) {
                a.which == null && (a.which = b.charCode != null ? b.charCode : b.keyCode);
                return a
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            filter: function(a, d) {
                var e, f, g, h = d.button,
                    i = d.fromElement;
                a.pageX == null && d.clientX != null && (e = a.target.ownerDocument || c, f = e.documentElement, g = e.body, a.pageX = d.clientX + (f && f.scrollLeft || g && g.scrollLeft || 0) - (f && f.clientLeft || g && g.clientLeft || 0), a.pageY = d.clientY + (f && f.scrollTop || g && g.scrollTop || 0) - (f && f.clientTop || g && g.clientTop || 0)), !a.relatedTarget && i && (a.relatedTarget = i === a.target ? d.toElement : i), !a.which && h !== b && (a.which = h & 1 ? 1 : h & 2 ? 3 : h & 4 ? 2 : 0);
                return a
            }
        },
        fix: function(a) {
            if (a[f.expando]) return a;
            var d, e, g = a,
                h = f.event.fixHooks[a.type] || {},
                i = h.props ? this.props.concat(h.props) : this.props;
            a = f.Event(g);
            for (d = i.length; d;) e = i[--d], a[e] = g[e];
            a.target || (a.target = g.srcElement || c), a.target.nodeType === 3 && (a.target = a.target.parentNode), a.metaKey === b && (a.metaKey = a.ctrlKey);
            return h.filter ? h.filter(a, g) : a
        },
        special: {
            ready: {
                setup: f.bindReady
            },
            load: {
                noBubble: !0
            },
            focus: {
                delegateType: "focusin"
            },
            blur: {
                delegateType: "focusout"
            },
            beforeunload: {
                setup: function(a, b, c) {
                    f.isWindow(this) && (this.onbeforeunload = c)
                },
                teardown: function(a, b) {
                    this.onbeforeunload === b && (this.onbeforeunload = null)
                }
            }
        },
        simulate: function(a, b, c, d) {
            var e = f.extend(new f.Event, c, {
                type: a,
                isSimulated: !0,
                originalEvent: {}
            });
            d ? f.event.trigger(e, null, b) : f.event.dispatch.call(b, e), e.isDefaultPrevented() && c.preventDefault()
        }
    }, f.event.handle = f.event.dispatch, f.removeEvent = c.removeEventListener ?
        function(a, b, c) {
            a.removeEventListener && a.removeEventListener(b, c, !1)
        } : function(a, b, c) {
        a.detachEvent && a.detachEvent("on" + b, c)
    }, f.Event = function(a, b) {
        if (!(this instanceof f.Event)) return new f.Event(a, b);
        a && a.type ? (this.originalEvent = a, this.type = a.type, this.isDefaultPrevented = a.defaultPrevented || a.returnValue === !1 || a.getPreventDefault && a.getPreventDefault() ? K : J) : this.type = a, b && f.extend(this, b), this.timeStamp = a && a.timeStamp || f.now(), this[f.expando] = !0
    }, f.Event.prototype = {
        preventDefault: function() {
            this.isDefaultPrevented = K;
            var a = this.originalEvent;
            !a || (a.preventDefault ? a.preventDefault() : a.returnValue = !1)
        },
        stopPropagation: function() {
            this.isPropagationStopped = K;
            var a = this.originalEvent;
            !a || (a.stopPropagation && a.stopPropagation(), a.cancelBubble = !0)
        },
        stopImmediatePropagation: function() {
            this.isImmediatePropagationStopped = K, this.stopPropagation()
        },
        isDefaultPrevented: J,
        isPropagationStopped: J,
        isImmediatePropagationStopped: J
    }, f.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout"
    }, function(a, b) {
        f.event.special[a] = {
            delegateType: b,
            bindType: b,
            handle: function(a) {
                var c = this,
                    d = a.relatedTarget,
                    e = a.handleObj,
                    g = e.selector,
                    h;
                if (!d || d !== c && !f.contains(c, d)) a.type = e.origType, h = e.handler.apply(this, arguments), a.type = b;
                return h
            }
        }
    }), f.support.submitBubbles || (f.event.special.submit = {
        setup: function() {
            if (f.nodeName(this, "form")) return !1;
            f.event.add(this, "click._submit keypress._submit", function(a) {
                var c = a.target,
                    d = f.nodeName(c, "input") || f.nodeName(c, "button") ? c.form : b;
                d && !d._submit_attached && (f.event.add(d, "submit._submit", function(a) {
                    this.parentNode && !a.isTrigger && f.event.simulate("submit", this.parentNode, a, !0)
                }), d._submit_attached = !0)
            })
        },
        teardown: function() {
            if (f.nodeName(this, "form")) return !1;
            f.event.remove(this, "._submit")
        }
    }), f.support.changeBubbles || (f.event.special.change = {
        setup: function() {
            if (z.test(this.nodeName)) {
                if (this.type === "checkbox" || this.type === "radio") f.event.add(this, "propertychange._change", function(a) {
                    a.originalEvent.propertyName === "checked" && (this._just_changed = !0)
                }), f.event.add(this, "click._change", function(a) {
                    this._just_changed && !a.isTrigger && (this._just_changed = !1, f.event.simulate("change", this, a, !0))
                });
                return !1
            }
            f.event.add(this, "beforeactivate._change", function(a) {
                var b = a.target;
                z.test(b.nodeName) && !b._change_attached && (f.event.add(b, "change._change", function(a) {
                    this.parentNode && !a.isSimulated && !a.isTrigger && f.event.simulate("change", this.parentNode, a, !0)
                }), b._change_attached = !0)
            })
        },
        handle: function(a) {
            var b = a.target;
            if (this !== b || a.isSimulated || a.isTrigger || b.type !== "radio" && b.type !== "checkbox") return a.handleObj.handler.apply(this, arguments)
        },
        teardown: function() {
            f.event.remove(this, "._change");
            return z.test(this.nodeName)
        }
    }), f.support.focusinBubbles || f.each({
        focus: "focusin",
        blur: "focusout"
    }, function(a, b) {
        var d = 0,
            e = function(a) {
                f.event.simulate(b, a.target, f.event.fix(a), !0)
            };
        f.event.special[b] = {
            setup: function() {
                d++ === 0 && c.addEventListener(a, e, !0)
            },
            teardown: function() {
                --d === 0 && c.removeEventListener(a, e, !0)
            }
        }
    }), f.fn.extend({
        on: function(a, c, d, e, g) {
            var h, i;
            if (typeof a == "object") {
                typeof c != "string" && (d = c, c = b);
                for (i in a) this.on(i, c, d, a[i], g);
                return this
            }
            d == null && e == null ? (e = c, d = c = b) : e == null && (typeof c == "string" ? (e = d, d = b) : (e = d, d = c, c = b));
            if (e === !1) e = J;
            else if (!e) return this;
            g === 1 && (h = e, e = function(a) {
                f().off(a);
                return h.apply(this, arguments)
            }, e.guid = h.guid || (h.guid = f.guid++));
            return this.each(function() {
                f.event.add(this, a, e, d, c)
            })
        },
        one: function(a, b, c, d) {
            return this.on.call(this, a, b, c, d, 1)
        },
        off: function(a, c, d) {
            if (a && a.preventDefault && a.handleObj) {
                var e = a.handleObj;
                f(a.delegateTarget).off(e.namespace ? e.type + "." + e.namespace : e.type, e.selector, e.handler);
                return this
            }
            if (typeof a == "object") {
                for (var g in a) this.off(g, c, a[g]);
                return this
            }
            if (c === !1 || typeof c == "function") d = c, c = b;
            d === !1 && (d = J);
            return this.each(function() {
                f.event.remove(this, a, d, c)
            })
        },
        bind: function(a, b, c) {
            return this.on(a, null, b, c)
        },
        unbind: function(a, b) {
            return this.off(a, null, b)
        },
        live: function(a, b, c) {
            f(this.context).on(a, this.selector, b, c);
            return this
        },
        die: function(a, b) {
            f(this.context).off(a, this.selector || "**", b);
            return this
        },
        delegate: function(a, b, c, d) {
            return this.on(b, a, c, d)
        },
        undelegate: function(a, b, c) {
            return arguments.length == 1 ? this.off(a, "**") : this.off(b, a, c)
        },
        trigger: function(a, b) {
            return this.each(function() {
                f.event.trigger(a, b, this)
            })
        },
        triggerHandler: function(a, b) {
            if (this[0]) return f.event.trigger(a, b, this[0], !0)
        },
        toggle: function(a) {
            var b = arguments,
                c = a.guid || f.guid++,
                d = 0,
                e = function(c) {
                    var e = (f._data(this, "lastToggle" + a.guid) || 0) % d;
                    f._data(this, "lastToggle" + a.guid, e + 1), c.preventDefault();
                    return b[e].apply(this, arguments) || !1
                };
            e.guid = c;
            while (d < b.length) b[d++].guid = c;
            return this.click(e)
        },
        hover: function(a, b) {
            return this.mouseenter(a).mouseleave(b || a)
        }
    }), f.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function(a, b) {
        f.fn[b] = function(a, c) {
            c == null && (c = a, a = null);
            return arguments.length > 0 ? this.on(b, null, a, c) : this.trigger(b)
        }, f.attrFn && (f.attrFn[b] = !0), C.test(b) && (f.event.fixHooks[b] = f.event.keyHooks), D.test(b) && (f.event.fixHooks[b] = f.event.mouseHooks)
    }), function() {
        function x(a, b, c, e, f, g) {
            for (var h = 0, i = e.length; h < i; h++) {
                var j = e[h];
                if (j) {
                    var k = !1;
                    j = j[a];
                    while (j) {
                        if (j[d] === c) {
                            k = e[j.sizset];
                            break
                        }
                        if (j.nodeType === 1) {
                            g || (j[d] = c, j.sizset = h);
                            if (typeof b != "string") {
                                if (j === b) {
                                    k = !0;
                                    break
                                }
                            } else if (m.filter(b, [j]).length > 0) {
                                k = j;
                                break
                            }
                        }
                        j = j[a]
                    }
                    e[h] = k
                }
            }
        }
        function w(a, b, c, e, f, g) {
            for (var h = 0, i = e.length; h < i; h++) {
                var j = e[h];
                if (j) {
                    var k = !1;
                    j = j[a];
                    while (j) {
                        if (j[d] === c) {
                            k = e[j.sizset];
                            break
                        }
                        j.nodeType === 1 && !g && (j[d] = c, j.sizset = h);
                        if (j.nodeName.toLowerCase() === b) {
                            k = j;
                            break
                        }
                        j = j[a]
                    }
                    e[h] = k
                }
            }
        }
        var a = /((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^\[\]]*\]|['"][^'"]*['"]|[^\[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?((?:.|\r|\n)*)/g,
            d = "sizcache" + (Math.random() + "").replace(".", ""),
            e = 0,
            g = Object.prototype.toString,
            h = !1,
            i = !0,
            j = /\\/g,
            k = /\r\n/g,
            l = /\W/;
        [0, 0].sort(function() {
            i = !1;
            return 0
        });
        var m = function(b, d, e, f) {
            e = e || [], d = d || c;
            var h = d;
            if (d.nodeType !== 1 && d.nodeType !== 9) return [];
            if (!b || typeof b != "string") return e;
            var i, j, k, l, n, q, r, t, u = !0,
                v = m.isXML(d),
                w = [],
                x = b;
            do {
                a.exec(""), i = a.exec(x);
                if (i) {
                    x = i[3], w.push(i[1]);
                    if (i[2]) {
                        l = i[3];
                        break
                    }
                }
            } while (i);
            if (w.length > 1 && p.exec(b)) if (w.length === 2 && o.relative[w[0]]) j = y(w[0] + w[1], d, f);
            else {
                j = o.relative[w[0]] ? [d] : m(w.shift(), d);
                while (w.length) b = w.shift(), o.relative[b] && (b += w.shift()), j = y(b, j, f)
            } else {
                !f && w.length > 1 && d.nodeType === 9 && !v && o.match.ID.test(w[0]) && !o.match.ID.test(w[w.length - 1]) && (n = m.find(w.shift(), d, v), d = n.expr ? m.filter(n.expr, n.set)[0] : n.set[0]);
                if (d) {
                    n = f ? {
                        expr: w.pop(),
                        set: s(f)
                    } : m.find(w.pop(), w.length === 1 && (w[0] === "~" || w[0] === "+") && d.parentNode ? d.parentNode : d, v), j = n.expr ? m.filter(n.expr, n.set) : n.set, w.length > 0 ? k = s(j) : u = !1;
                    while (w.length) q = w.pop(), r = q, o.relative[q] ? r = w.pop() : q = "", r == null && (r = d), o.relative[q](k, r, v)
                } else k = w = []
            }
            k || (k = j), k || m.error(q || b);
            if (g.call(k) === "[object Array]") if (!u) e.push.apply(e, k);
            else if (d && d.nodeType === 1) for (t = 0; k[t] != null; t++) k[t] && (k[t] === !0 || k[t].nodeType === 1 && m.contains(d, k[t])) && e.push(j[t]);
            else
                for (t = 0; k[t] != null; t++) k[t] && k[t].nodeType === 1 && e.push(j[t]);
            else s(k, e);
            l && (m(l, h, e, f), m.uniqueSort(e));
            return e
        };
        m.uniqueSort = function(a) {
            if (u) {
                h = i, a.sort(u);
                if (h) for (var b = 1; b < a.length; b++) a[b] === a[b - 1] && a.splice(b--, 1)
            }
            return a
        }, m.matches = function(a, b) {
            return m(a, null, null, b)
        }, m.matchesSelector = function(a, b) {
            return m(b, null, null, [a]).length > 0
        }, m.find = function(a, b, c) {
            var d, e, f, g, h, i;
            if (!a) return [];
            for (e = 0, f = o.order.length; e < f; e++) {
                h = o.order[e];
                if (g = o.leftMatch[h].exec(a)) {
                    i = g[1], g.splice(1, 1);
                    if (i.substr(i.length - 1) !== "\\") {
                        g[1] = (g[1] || "").replace(j, ""), d = o.find[h](g, b, c);
                        if (d != null) {
                            a = a.replace(o.match[h], "");
                            break
                        }
                    }
                }
            }
            d || (d = typeof b.getElementsByTagName != "undefined" ? b.getElementsByTagName("*") : []);
            return {
                set: d,
                expr: a
            }
        }, m.filter = function(a, c, d, e) {
            var f, g, h, i, j, k, l, n, p, q = a,
                r = [],
                s = c,
                t = c && c[0] && m.isXML(c[0]);
            while (a && c.length) {
                for (h in o.filter) if ((f = o.leftMatch[h].exec(a)) != null && f[2]) {
                    k = o.filter[h], l = f[1], g = !1, f.splice(1, 1);
                    if (l.substr(l.length - 1) === "\\") continue;
                    s === r && (r = []);
                    if (o.preFilter[h]) {
                        f = o.preFilter[h](f, s, d, r, e, t);
                        if (!f) g = i = !0;
                        else if (f === !0) continue
                    }
                    if (f) for (n = 0;
                                (j = s[n]) != null; n++) j && (i = k(j, f, n, s), p = e ^ i, d && i != null ? p ? g = !0 : s[n] = !1 : p && (r.push(j), g = !0));
                    if (i !== b) {
                        d || (s = r), a = a.replace(o.match[h], "");
                        if (!g) return [];
                        break
                    }
                }
                if (a === q) if (g == null) m.error(a);
                else
                    break;
                q = a
            }
            return s
        }, m.error = function(a) {
            throw new Error("Syntax error, unrecognized expression: " + a)
        };
        var n = m.getText = function(a) {
                var b, c, d = a.nodeType,
                    e = "";
                if (d) {
                    if (d === 1 || d === 9) {
                        if (typeof a.textContent == "string") return a.textContent;
                        if (typeof a.innerText == "string") return a.innerText.replace(k, "");
                        for (a = a.firstChild; a; a = a.nextSibling) e += n(a)
                    } else if (d === 3 || d === 4) return a.nodeValue
                } else
                    for (b = 0; c = a[b]; b++) c.nodeType !== 8 && (e += n(c));
                return e
            },
            o = m.selectors = {
                order: ["ID", "NAME", "TAG"],
                match: {
                    ID: /#((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,
                    CLASS: /\.((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,
                    NAME: /\[name=['"]*((?:[\w\u00c0-\uFFFF\-]|\\.)+)['"]*\]/,
                    ATTR: /\[\s*((?:[\w\u00c0-\uFFFF\-]|\\.)+)\s*(?:(\S?=)\s*(?:(['"])(.*?)\3|(#?(?:[\w\u00c0-\uFFFF\-]|\\.)*)|)|)\s*\]/,
                    TAG: /^((?:[\w\u00c0-\uFFFF\*\-]|\\.)+)/,
                    CHILD: /:(only|nth|last|first)-child(?:\(\s*(even|odd|(?:[+\-]?\d+|(?:[+\-]?\d*)?n\s*(?:[+\-]\s*\d+)?))\s*\))?/,
                    POS: /:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^\-]|$)/,
                    PSEUDO: /:((?:[\w\u00c0-\uFFFF\-]|\\.)+)(?:\((['"]?)((?:\([^\)]+\)|[^\(\)]*)+)\2\))?/
                },
                leftMatch: {},
                attrMap: {
                    "class": "className",
                    "for": "htmlFor"
                },
                attrHandle: {
                    href: function(a) {
                        return a.getAttribute("href")
                    },
                    type: function(a) {
                        return a.getAttribute("type")
                    }
                },
                relative: {
                    "+": function(a, b) {
                        var c = typeof b == "string",
                            d = c && !l.test(b),
                            e = c && !d;
                        d && (b = b.toLowerCase());
                        for (var f = 0, g = a.length, h; f < g; f++) if (h = a[f]) {
                            while ((h = h.previousSibling) && h.nodeType !== 1);
                            a[f] = e || h && h.nodeName.toLowerCase() === b ? h || !1 : h === b
                        }
                        e && m.filter(b, a, !0)
                    },
                    ">": function(a, b) {
                        var c, d = typeof b == "string",
                            e = 0,
                            f = a.length;
                        if (d && !l.test(b)) {
                            b = b.toLowerCase();
                            for (; e < f; e++) {
                                c = a[e];
                                if (c) {
                                    var g = c.parentNode;
                                    a[e] = g.nodeName.toLowerCase() === b ? g : !1
                                }
                            }
                        } else {
                            for (; e < f; e++) c = a[e], c && (a[e] = d ? c.parentNode : c.parentNode === b);
                            d && m.filter(b, a, !0)
                        }
                    },
                    "": function(a, b, c) {
                        var d, f = e++,
                            g = x;
                        typeof b == "string" && !l.test(b) && (b = b.toLowerCase(), d = b, g = w), g("parentNode", b, f, a, d, c)
                    },
                    "~": function(a, b, c) {
                        var d, f = e++,
                            g = x;
                        typeof b == "string" && !l.test(b) && (b = b.toLowerCase(), d = b, g = w), g("previousSibling", b, f, a, d, c)
                    }
                },
                find: {
                    ID: function(a, b, c) {
                        if (typeof b.getElementById != "undefined" && !c) {
                            var d = b.getElementById(a[1]);
                            return d && d.parentNode ? [d] : []
                        }
                    },
                    NAME: function(a, b) {
                        if (typeof b.getElementsByName != "undefined") {
                            var c = [],
                                d = b.getElementsByName(a[1]);
                            for (var e = 0, f = d.length; e < f; e++) d[e].getAttribute("name") === a[1] && c.push(d[e]);
                            return c.length === 0 ? null : c
                        }
                    },
                    TAG: function(a, b) {
                        if (typeof b.getElementsByTagName != "undefined") return b.getElementsByTagName(a[1])
                    }
                },
                preFilter: {
                    CLASS: function(a, b, c, d, e, f) {
                        a = " " + a[1].replace(j, "") + " ";
                        if (f) return a;
                        for (var g = 0, h;
                             (h = b[g]) != null; g++) h && (e ^ (h.className && (" " + h.className + " ").replace(/[\t\n\r]/g, " ").indexOf(a) >= 0) ? c || d.push(h) : c && (b[g] = !1));
                        return !1
                    },
                    ID: function(a) {
                        return a[1].replace(j, "")
                    },
                    TAG: function(a, b) {
                        return a[1].replace(j, "").toLowerCase()
                    },
                    CHILD: function(a) {
                        if (a[1] === "nth") {
                            a[2] || m.error(a[0]), a[2] = a[2].replace(/^\+|\s*/g, "");
                            var b = /(-?)(\d*)(?:n([+\-]?\d*))?/.exec(a[2] === "even" && "2n" || a[2] === "odd" && "2n+1" || !/\D/.test(a[2]) && "0n+" + a[2] || a[2]);
                            a[2] = b[1] + (b[2] || 1) - 0, a[3] = b[3] - 0
                        } else a[2] && m.error(a[0]);
                        a[0] = e++;
                        return a
                    },
                    ATTR: function(a, b, c, d, e, f) {
                        var g = a[1] = a[1].replace(j, "");
                        !f && o.attrMap[g] && (a[1] = o.attrMap[g]), a[4] = (a[4] || a[5] || "").replace(j, ""), a[2] === "~=" && (a[4] = " " + a[4] + " ");
                        return a
                    },
                    PSEUDO: function(b, c, d, e, f) {
                        if (b[1] === "not") if ((a.exec(b[3]) || "").length > 1 || /^\w/.test(b[3])) b[3] = m(b[3], null, null, c);
                        else {
                            var g = m.filter(b[3], c, d, !0 ^ f);
                            d || e.push.apply(e, g);
                            return !1
                        } else if (o.match.POS.test(b[0]) || o.match.CHILD.test(b[0])) return !0;
                        return b
                    },
                    POS: function(a) {
                        a.unshift(!0);
                        return a
                    }
                },
                filters: {
                    enabled: function(a) {
                        return a.disabled === !1 && a.type !== "hidden"
                    },
                    disabled: function(a) {
                        return a.disabled === !0
                    },
                    checked: function(a) {
                        return a.checked === !0
                    },
                    selected: function(a) {
                        a.parentNode && a.parentNode.selectedIndex;
                        return a.selected === !0
                    },
                    parent: function(a) {
                        return !!a.firstChild
                    },
                    empty: function(a) {
                        return !a.firstChild
                    },
                    has: function(a, b, c) {
                        return !!m(c[3], a).length
                    },
                    header: function(a) {
                        return /h\d/i.test(a.nodeName)
                    },
                    text: function(a) {
                        var b = a.getAttribute("type"),
                            c = a.type;
                        return a.nodeName.toLowerCase() === "input" && "text" === c && (b === c || b === null)
                    },
                    radio: function(a) {
                        return a.nodeName.toLowerCase() === "input" && "radio" === a.type
                    },
                    checkbox: function(a) {
                        return a.nodeName.toLowerCase() === "input" && "checkbox" === a.type
                    },
                    file: function(a) {
                        return a.nodeName.toLowerCase() === "input" && "file" === a.type
                    },
                    password: function(a) {
                        return a.nodeName.toLowerCase() === "input" && "password" === a.type
                    },
                    submit: function(a) {
                        var b = a.nodeName.toLowerCase();
                        return (b === "input" || b === "button") && "submit" === a.type
                    },
                    image: function(a) {
                        return a.nodeName.toLowerCase() === "input" && "image" === a.type
                    },
                    reset: function(a) {
                        var b = a.nodeName.toLowerCase();
                        return (b === "input" || b === "button") && "reset" === a.type
                    },
                    button: function(a) {
                        var b = a.nodeName.toLowerCase();
                        return b === "input" && "button" === a.type || b === "button"
                    },
                    input: function(a) {
                        return /input|select|textarea|button/i.test(a.nodeName)
                    },
                    focus: function(a) {
                        return a === a.ownerDocument.activeElement
                    }
                },
                setFilters: {
                    first: function(a, b) {
                        return b === 0
                    },
                    last: function(a, b, c, d) {
                        return b === d.length - 1
                    },
                    even: function(a, b) {
                        return b % 2 === 0
                    },
                    odd: function(a, b) {
                        return b % 2 === 1
                    },
                    lt: function(a, b, c) {
                        return b < c[3] - 0
                    },
                    gt: function(a, b, c) {
                        return b > c[3] - 0
                    },
                    nth: function(a, b, c) {
                        return c[3] - 0 === b
                    },
                    eq: function(a, b, c) {
                        return c[3] - 0 === b
                    }
                },
                filter: {
                    PSEUDO: function(a, b, c, d) {
                        var e = b[1],
                            f = o.filters[e];
                        if (f) return f(a, c, b, d);
                        if (e === "contains") return (a.textContent || a.innerText || n([a]) || "").indexOf(b[3]) >= 0;
                        if (e === "not") {
                            var g = b[3];
                            for (var h = 0, i = g.length; h < i; h++) if (g[h] === a) return !1;
                            return !0
                        }
                        m.error(e)
                    },
                    CHILD: function(a, b) {
                        var c, e, f, g, h, i, j, k = b[1],
                            l = a;
                        switch (k) {
                            case "only":
                            case "first":
                                while (l = l.previousSibling) if (l.nodeType === 1) return !1;
                                if (k === "first") return !0;
                                l = a;
                            case "last":
                                while (l = l.nextSibling) if (l.nodeType === 1) return !1;
                                return !0;
                            case "nth":
                                c = b[2], e = b[3];
                                if (c === 1 && e === 0) return !0;
                                f = b[0], g = a.parentNode;
                                if (g && (g[d] !== f || !a.nodeIndex)) {
                                    i = 0;
                                    for (l = g.firstChild; l; l = l.nextSibling) l.nodeType === 1 && (l.nodeIndex = ++i);
                                    g[d] = f
                                }
                                j = a.nodeIndex - e;
                                return c === 0 ? j === 0 : j % c === 0 && j / c >= 0
                        }
                    },
                    ID: function(a, b) {
                        return a.nodeType === 1 && a.getAttribute("id") === b
                    },
                    TAG: function(a, b) {
                        return b === "*" && a.nodeType === 1 || !! a.nodeName && a.nodeName.toLowerCase() === b
                    },
                    CLASS: function(a, b) {
                        return (" " + (a.className || a.getAttribute("class")) + " ").indexOf(b) > -1
                    },
                    ATTR: function(a, b) {
                        var c = b[1],
                            d = m.attr ? m.attr(a, c) : o.attrHandle[c] ? o.attrHandle[c](a) : a[c] != null ? a[c] : a.getAttribute(c),
                            e = d + "",
                            f = b[2],
                            g = b[4];
                        return d == null ? f === "!=" : !f && m.attr ? d != null : f === "=" ? e === g : f === "*=" ? e.indexOf(g) >= 0 : f === "~=" ? (" " + e + " ").indexOf(g) >= 0 : g ? f === "!=" ? e !== g : f === "^=" ? e.indexOf(g) === 0 : f === "$=" ? e.substr(e.length - g.length) === g : f === "|=" ? e === g || e.substr(0, g.length + 1) === g + "-" : !1 : e && d !== !1
                    },
                    POS: function(a, b, c, d) {
                        var e = b[2],
                            f = o.setFilters[e];
                        if (f) return f(a, c, b, d)
                    }
                }
            },
            p = o.match.POS,
            q = function(a, b) {
                return "\\" + (b - 0 + 1)
            };
        for (var r in o.match) o.match[r] = new RegExp(o.match[r].source + /(?![^\[]*\])(?![^\(]*\))/.source), o.leftMatch[r] = new RegExp(/(^(?:.|\r|\n)*?)/.source + o.match[r].source.replace(/\\(\d+)/g, q));
        var s = function(a, b) {
            a = Array.prototype.slice.call(a, 0);
            if (b) {
                b.push.apply(b, a);
                return b
            }
            return a
        };
        try {
            Array.prototype.slice.call(c.documentElement.childNodes, 0)[0].nodeType
        } catch (t) {
            s = function(a, b) {
                var c = 0,
                    d = b || [];
                if (g.call(a) === "[object Array]") Array.prototype.push.apply(d, a);
                else if (typeof a.length == "number") for (var e = a.length; c < e; c++) d.push(a[c]);
                else
                    for (; a[c]; c++) d.push(a[c]);
                return d
            }
        }
        var u, v;
        c.documentElement.compareDocumentPosition ? u = function(a, b) {
            if (a === b) {
                h = !0;
                return 0
            }
            if (!a.compareDocumentPosition || !b.compareDocumentPosition) return a.compareDocumentPosition ? -1 : 1;
            return a.compareDocumentPosition(b) & 4 ? -1 : 1
        } : (u = function(a, b) {
            if (a === b) {
                h = !0;
                return 0
            }
            if (a.sourceIndex && b.sourceIndex) return a.sourceIndex - b.sourceIndex;
            var c, d, e = [],
                f = [],
                g = a.parentNode,
                i = b.parentNode,
                j = g;
            if (g === i) return v(a, b);
            if (!g) return -1;
            if (!i) return 1;
            while (j) e.unshift(j), j = j.parentNode;
            j = i;
            while (j) f.unshift(j), j = j.parentNode;
            c = e.length, d = f.length;
            for (var k = 0; k < c && k < d; k++) if (e[k] !== f[k]) return v(e[k], f[k]);
            return k === c ? v(a, f[k], -1) : v(e[k], b, 1)
        }, v = function(a, b, c) {
            if (a === b) return c;
            var d = a.nextSibling;
            while (d) {
                if (d === b) return -1;
                d = d.nextSibling
            }
            return 1
        }), function() {
            var a = c.createElement("div"),
                d = "script" + (new Date).getTime(),
                e = c.documentElement;
            a.innerHTML = "<a name='" + d + "'/>", e.insertBefore(a, e.firstChild), c.getElementById(d) && (o.find.ID = function(a, c, d) {
                if (typeof c.getElementById != "undefined" && !d) {
                    var e = c.getElementById(a[1]);
                    return e ? e.id === a[1] || typeof e.getAttributeNode != "undefined" && e.getAttributeNode("id").nodeValue === a[1] ? [e] : b : []
                }
            }, o.filter.ID = function(a, b) {
                var c = typeof a.getAttributeNode != "undefined" && a.getAttributeNode("id");
                return a.nodeType === 1 && c && c.nodeValue === b
            }), e.removeChild(a), e = a = null
        }(), function() {
            var a = c.createElement("div");
            a.appendChild(c.createComment("")), a.getElementsByTagName("*").length > 0 && (o.find.TAG = function(a, b) {
                var c = b.getElementsByTagName(a[1]);
                if (a[1] === "*") {
                    var d = [];
                    for (var e = 0; c[e]; e++) c[e].nodeType === 1 && d.push(c[e]);
                    c = d
                }
                return c
            }), a.innerHTML = "<a href='#'></a>", a.firstChild && typeof a.firstChild.getAttribute != "undefined" && a.firstChild.getAttribute("href") !== "#" && (o.attrHandle.href = function(a) {
                return a.getAttribute("href", 2)
            }), a = null
        }(), c.querySelectorAll &&
            function() {
                var a = m,
                    b = c.createElement("div"),
                    d = "__sizzle__";
                b.innerHTML = "<p class='TEST'></p>";
                if (!b.querySelectorAll || b.querySelectorAll(".TEST").length !== 0) {
                    m = function(b, e, f, g) {
                        e = e || c;
                        if (!g && !m.isXML(e)) {
                            var h = /^(\w+$)|^\.([\w\-]+$)|^#([\w\-]+$)/.exec(b);
                            if (h && (e.nodeType === 1 || e.nodeType === 9)) {
                                if (h[1]) return s(e.getElementsByTagName(b), f);
                                if (h[2] && o.find.CLASS && e.getElementsByClassName) return s(e.getElementsByClassName(h[2]), f)
                            }
                            if (e.nodeType === 9) {
                                if (b === "body" && e.body) return s([e.body], f);
                                if (h && h[3]) {
                                    var i = e.getElementById(h[3]);
                                    if (!i || !i.parentNode) return s([], f);
                                    if (i.id === h[3]) return s([i], f)
                                }
                                try {
                                    return s(e.querySelectorAll(b), f)
                                } catch (j) {}
                            } else if (e.nodeType === 1 && e.nodeName.toLowerCase() !== "object") {
                                var k = e,
                                    l = e.getAttribute("id"),
                                    n = l || d,
                                    p = e.parentNode,
                                    q = /^\s*[+~]/.test(b);
                                l ? n = n.replace(/'/g, "\\$&") : e.setAttribute("id", n), q && p && (e = e.parentNode);
                                try {
                                    if (!q || p) return s(e.querySelectorAll("[id='" + n + "'] " + b), f)
                                } catch (r) {} finally {
                                    l || k.removeAttribute("id")
                                }
                            }
                        }
                        return a(b, e, f, g)
                    };
                    for (var e in a) m[e] = a[e];
                    b = null
                }
            }(), function() {
            var a = c.documentElement,
                b = a.matchesSelector || a.mozMatchesSelector || a.webkitMatchesSelector || a.msMatchesSelector;
            if (b) {
                var d = !b.call(c.createElement("div"), "div"),
                    e = !1;
                try {
                    b.call(c.documentElement, "[test!='']:sizzle")
                } catch (f) {
                    e = !0
                }
                m.matchesSelector = function(a, c) {
                    c = c.replace(/\=\s*([^'"\]]*)\s*\]/g, "='$1']");
                    if (!m.isXML(a)) try {
                        if (e || !o.match.PSEUDO.test(c) && !/!=/.test(c)) {
                            var f = b.call(a, c);
                            if (f || !d || a.document && a.document.nodeType !== 11) return f
                        }
                    } catch (g) {}
                    return m(c, null, null, [a]).length > 0
                }
            }
        }(), function() {
            var a = c.createElement("div");
            a.innerHTML = "<div class='test e'></div><div class='test'></div>";
            if ( !! a.getElementsByClassName && a.getElementsByClassName("e").length !== 0) {
                a.lastChild.className = "e";
                if (a.getElementsByClassName("e").length === 1) return;
                o.order.splice(1, 0, "CLASS"), o.find.CLASS = function(a, b, c) {
                    if (typeof b.getElementsByClassName != "undefined" && !c) return b.getElementsByClassName(a[1])
                }, a = null
            }
        }(), c.documentElement.contains ? m.contains = function(a, b) {
            return a !== b && (a.contains ? a.contains(b) : !0)
        } : c.documentElement.compareDocumentPosition ? m.contains = function(a, b) {
            return !!(a.compareDocumentPosition(b) & 16)
        } : m.contains = function() {
            return !1
        }, m.isXML = function(a) {
            var b = (a ? a.ownerDocument || a : 0).documentElement;
            return b ? b.nodeName !== "HTML" : !1
        };
        var y = function(a, b, c) {
            var d, e = [],
                f = "",
                g = b.nodeType ? [b] : b;
            while (d = o.match.PSEUDO.exec(a)) f += d[0], a = a.replace(o.match.PSEUDO, "");
            a = o.relative[a] ? a + "*" : a;
            for (var h = 0, i = g.length; h < i; h++) m(a, g[h], e, c);
            return m.filter(f, e)
        };
        m.attr = f.attr, m.selectors.attrMap = {}, f.find = m, f.expr = m.selectors, f.expr[":"] = f.expr.filters, f.unique = m.uniqueSort, f.text = m.getText, f.isXMLDoc = m.isXML, f.contains = m.contains
    }();
    var L = /Until$/,
        M = /^(?:parents|prevUntil|prevAll)/,
        N = /,/,
        O = /^.[^:#\[\.,]*$/,
        P = Array.prototype.slice,
        Q = f.expr.match.POS,
        R = {
            children: !0,
            contents: !0,
            next: !0,
            prev: !0
        };
    f.fn.extend({
        find: function(a) {
            var b = this,
                c, d;
            if (typeof a != "string") return f(a).filter(function() {
                for (c = 0, d = b.length; c < d; c++) if (f.contains(b[c], this)) return !0
            });
            var e = this.pushStack("", "find", a),
                g, h, i;
            for (c = 0, d = this.length; c < d; c++) {
                g = e.length, f.find(a, this[c], e);
                if (c > 0) for (h = g; h < e.length; h++) for (i = 0; i < g; i++) if (e[i] === e[h]) {
                    e.splice(h--, 1);
                    break
                }
            }
            return e
        },
        has: function(a) {
            var b = f(a);
            return this.filter(function() {
                for (var a = 0, c = b.length; a < c; a++) if (f.contains(this, b[a])) return !0
            })
        },
        not: function(a) {
            return this.pushStack(T(this, a, !1), "not", a)
        },
        filter: function(a) {
            return this.pushStack(T(this, a, !0), "filter", a)
        },
        is: function(a) {
            return !!a && (typeof a == "string" ? Q.test(a) ? f(a, this.context).index(this[0]) >= 0 : f.filter(a, this).length > 0 : this.filter(a).length > 0)
        },
        closest: function(a, b) {
            var c = [],
                d, e, g = this[0];
            if (f.isArray(a)) {
                var h = 1;
                while (g && g.ownerDocument && g !== b) {
                    for (d = 0; d < a.length; d++) f(g).is(a[d]) && c.push({
                        selector: a[d],
                        elem: g,
                        level: h
                    });
                    g = g.parentNode, h++
                }
                return c
            }
            var i = Q.test(a) || typeof a != "string" ? f(a, b || this.context) : 0;
            for (d = 0, e = this.length; d < e; d++) {
                g = this[d];
                while (g) {
                    if (i ? i.index(g) > -1 : f.find.matchesSelector(g, a)) {
                        c.push(g);
                        break
                    }
                    g = g.parentNode;
                    if (!g || !g.ownerDocument || g === b || g.nodeType === 11) break
                }
            }
            c = c.length > 1 ? f.unique(c) : c;
            return this.pushStack(c, "closest", a)
        },
        index: function(a) {
            if (!a) return this[0] && this[0].parentNode ? this.prevAll().length : -1;
            if (typeof a == "string") return f.inArray(this[0], f(a));
            return f.inArray(a.jquery ? a[0] : a, this)
        },
        add: function(a, b) {
            var c = typeof a == "string" ? f(a, b) : f.makeArray(a && a.nodeType ? [a] : a),
                d = f.merge(this.get(), c);
            return this.pushStack(S(c[0]) || S(d[0]) ? d : f.unique(d))
        },
        andSelf: function() {
            return this.add(this.prevObject)
        }
    }), f.each({
        parent: function(a) {
            var b = a.parentNode;
            return b && b.nodeType !== 11 ? b : null
        },
        parents: function(a) {
            return f.dir(a, "parentNode")
        },
        parentsUntil: function(a, b, c) {
            return f.dir(a, "parentNode", c)
        },
        next: function(a) {
            return f.nth(a, 2, "nextSibling")
        },
        prev: function(a) {
            return f.nth(a, 2, "previousSibling")
        },
        nextAll: function(a) {
            return f.dir(a, "nextSibling")
        },
        prevAll: function(a) {
            return f.dir(a, "previousSibling")
        },
        nextUntil: function(a, b, c) {
            return f.dir(a, "nextSibling", c)
        },
        prevUntil: function(a, b, c) {
            return f.dir(a, "previousSibling", c)
        },
        siblings: function(a) {
            return f.sibling(a.parentNode.firstChild, a)
        },
        children: function(a) {
            return f.sibling(a.firstChild)
        },
        contents: function(a) {
            return f.nodeName(a, "iframe") ? a.contentDocument || a.contentWindow.document : f.makeArray(a.childNodes)
        }
    }, function(a, b) {
        f.fn[a] = function(c, d) {
            var e = f.map(this, b, c);
            L.test(a) || (d = c), d && typeof d == "string" && (e = f.filter(d, e)), e = this.length > 1 && !R[a] ? f.unique(e) : e, (this.length > 1 || N.test(d)) && M.test(a) && (e = e.reverse());
            return this.pushStack(e, a, P.call(arguments).join(","))
        }
    }), f.extend({
        filter: function(a, b, c) {
            c && (a = ":not(" + a + ")");
            return b.length === 1 ? f.find.matchesSelector(b[0], a) ? [b[0]] : [] : f.find.matches(a, b)
        },
        dir: function(a, c, d) {
            var e = [],
                g = a[c];
            while (g && g.nodeType !== 9 && (d === b || g.nodeType !== 1 || !f(g).is(d))) g.nodeType === 1 && e.push(g), g = g[c];
            return e
        },
        nth: function(a, b, c, d) {
            b = b || 1;
            var e = 0;
            for (; a; a = a[c]) if (a.nodeType === 1 && ++e === b) break;
            return a
        },
        sibling: function(a, b) {
            var c = [];
            for (; a; a = a.nextSibling) a.nodeType === 1 && a !== b && c.push(a);
            return c
        }
    });
    var V = "abbr|article|aside|audio|canvas|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",
        W = / jQuery\d+="(?:\d+|null)"/g,
        X = /^\s+/,
        Y = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/ig,
        Z = /<([\w:]+)/,
        $ = /<tbody/i,
        _ = /<|&#?\w+;/,
        ba = /<(?:script|style)/i,
        bb = /<(?:script|object|embed|option|style)/i,
        bc = new RegExp("<(?:" + V + ")", "i"),
        bd = /checked\s*(?:[^=]|=\s*.checked.)/i,
        be = /\/(java|ecma)script/i,
        bf = /^\s*<!(?:\[CDATA\[|\-\-)/,
        bg = {
            option: [1, "<select multiple='multiple'>", "</select>"],
            legend: [1, "<fieldset>", "</fieldset>"],
            thead: [1, "<table>", "</table>"],
            tr: [2, "<table><tbody>", "</tbody></table>"],
            td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
            col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
            area: [1, "<map>", "</map>"],
            _default: [0, "", ""]
        },
        bh = U(c);
    bg.optgroup = bg.option, bg.tbody = bg.tfoot = bg.colgroup = bg.caption = bg.thead, bg.th = bg.td, f.support.htmlSerialize || (bg._default = [1, "div<div>", "</div>"]), f.fn.extend({
        text: function(a) {
            if (f.isFunction(a)) return this.each(function(b) {
                var c = f(this);
                c.text(a.call(this, b, c.text()))
            });
            if (typeof a != "object" && a !== b) return this.empty().append((this[0] && this[0].ownerDocument || c).createTextNode(a));
            return f.text(this)
        },
        wrapAll: function(a) {
            if (f.isFunction(a)) return this.each(function(b) {
                f(this).wrapAll(a.call(this, b))
            });
            if (this[0]) {
                var b = f(a, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && b.insertBefore(this[0]), b.map(function() {
                    var a = this;
                    while (a.firstChild && a.firstChild.nodeType === 1) a = a.firstChild;
                    return a
                }).append(this)
            }
            return this
        },
        wrapInner: function(a) {
            if (f.isFunction(a)) return this.each(function(b) {
                f(this).wrapInner(a.call(this, b))
            });
            return this.each(function() {
                var b = f(this),
                    c = b.contents();
                c.length ? c.wrapAll(a) : b.append(a)
            })
        },
        wrap: function(a) {
            var b = f.isFunction(a);
            return this.each(function(c) {
                f(this).wrapAll(b ? a.call(this, c) : a)
            })
        },
        unwrap: function() {
            return this.parent().each(function() {
                f.nodeName(this, "body") || f(this).replaceWith(this.childNodes)
            }).end()
        },
        append: function() {
            return this.domManip(arguments, !0, function(a) {
                this.nodeType === 1 && this.appendChild(a)
            })
        },
        prepend: function() {
            return this.domManip(arguments, !0, function(a) {
                this.nodeType === 1 && this.insertBefore(a, this.firstChild)
            })
        },
        before: function() {
            if (this[0] && this[0].parentNode) return this.domManip(arguments, !1, function(a) {
                this.parentNode.insertBefore(a, this)
            });
            if (arguments.length) {
                var a = f.clean(arguments);
                a.push.apply(a, this.toArray());
                return this.pushStack(a, "before", arguments)
            }
        },
        after: function() {
            if (this[0] && this[0].parentNode) return this.domManip(arguments, !1, function(a) {
                this.parentNode.insertBefore(a, this.nextSibling)
            });
            if (arguments.length) {
                var a = this.pushStack(this, "after", arguments);
                a.push.apply(a, f.clean(arguments));
                return a
            }
        },
        remove: function(a, b) {
            for (var c = 0, d;
                 (d = this[c]) != null; c++) if (!a || f.filter(a, [d]).length)!b && d.nodeType === 1 && (f.cleanData(d.getElementsByTagName("*")), f.cleanData([d])), d.parentNode && d.parentNode.removeChild(d);
            return this
        },
        empty: function() {
            for (var a = 0, b;
                 (b = this[a]) != null; a++) {
                b.nodeType === 1 && f.cleanData(b.getElementsByTagName("*"));
                while (b.firstChild) b.removeChild(b.firstChild)
            }
            return this
        },
        clone: function(a, b) {
            a = a == null ? !1 : a, b = b == null ? a : b;
            return this.map(function() {
                return f.clone(this, a, b)
            })
        },
        html: function(a) {
            if (a === b) return this[0] && this[0].nodeType === 1 ? this[0].innerHTML.replace(W, "") : null;
            if (typeof a == "string" && !ba.test(a) && (f.support.leadingWhitespace || !X.test(a)) && !bg[(Z.exec(a) || ["", ""])[1].toLowerCase()]) {
                a = a.replace(Y, "<$1></$2>");
                try {
                    for (var c = 0, d = this.length; c < d; c++) this[c].nodeType === 1 && (f.cleanData(this[c].getElementsByTagName("*")), this[c].innerHTML = a)
                } catch (e) {
                    this.empty().append(a)
                }
            } else f.isFunction(a) ? this.each(function(b) {
                var c = f(this);
                c.html(a.call(this, b, c.html()))
            }) : this.empty().append(a);
            return this
        },
        replaceWith: function(a) {
            if (this[0] && this[0].parentNode) {
                if (f.isFunction(a)) return this.each(function(b) {
                    var c = f(this),
                        d = c.html();
                    c.replaceWith(a.call(this, b, d))
                });
                typeof a != "string" && (a = f(a).detach());
                return this.each(function() {
                    var b = this.nextSibling,
                        c = this.parentNode;
                    f(this).remove(), b ? f(b).before(a) : f(c).append(a)
                })
            }
            return this.length ? this.pushStack(f(f.isFunction(a) ? a() : a), "replaceWith", a) : this
        },
        detach: function(a) {
            return this.remove(a, !0)
        },
        domManip: function(a, c, d) {
            var e, g, h, i, j = a[0],
                k = [];
            if (!f.support.checkClone && arguments.length === 3 && typeof j == "string" && bd.test(j)) return this.each(function() {
                f(this).domManip(a, c, d, !0)
            });
            if (f.isFunction(j)) return this.each(function(e) {
                var g = f(this);
                a[0] = j.call(this, e, c ? g.html() : b), g.domManip(a, c, d)
            });
            if (this[0]) {
                i = j && j.parentNode, f.support.parentNode && i && i.nodeType === 11 && i.childNodes.length === this.length ? e = {
                    fragment: i
                } : e = f.buildFragment(a, this, k), h = e.fragment, h.childNodes.length === 1 ? g = h = h.firstChild : g = h.firstChild;
                if (g) {
                    c = c && f.nodeName(g, "tr");
                    for (var l = 0, m = this.length, n = m - 1; l < m; l++) d.call(c ? bi(this[l], g) : this[l], e.cacheable || m > 1 && l < n ? f.clone(h, !0, !0) : h)
                }
                k.length && f.each(k, bp)
            }
            return this
        }
    }), f.buildFragment = function(a, b, d) {
        var e, g, h, i, j = a[0];
        b && b[0] && (i = b[0].ownerDocument || b[0]), i.createDocumentFragment || (i = c), a.length === 1 && typeof j == "string" && j.length < 512 && i === c && j.charAt(0) === "<" && !bb.test(j) && (f.support.checkClone || !bd.test(j)) && (f.support.html5Clone || !bc.test(j)) && (g = !0, h = f.fragments[j], h && h !== 1 && (e = h)), e || (e = i.createDocumentFragment(), f.clean(a, i, e, d)), g && (f.fragments[j] = h ? e : 1);
        return {
            fragment: e,
            cacheable: g
        }
    }, f.fragments = {}, f.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function(a, b) {
        f.fn[a] = function(c) {
            var d = [],
                e = f(c),
                g = this.length === 1 && this[0].parentNode;
            if (g && g.nodeType === 11 && g.childNodes.length === 1 && e.length === 1) {
                e[b](this[0]);
                return this
            }
            for (var h = 0, i = e.length; h < i; h++) {
                var j = (h > 0 ? this.clone(!0) : this).get();
                f(e[h])[b](j), d = d.concat(j)
            }
            return this.pushStack(d, a, e.selector)
        }
    }), f.extend({
        clone: function(a, b, c) {
            var d, e, g, h = f.support.html5Clone || !bc.test("<" + a.nodeName) ? a.cloneNode(!0) : bo(a);
            if ((!f.support.noCloneEvent || !f.support.noCloneChecked) && (a.nodeType === 1 || a.nodeType === 11) && !f.isXMLDoc(a)) {
                bk(a, h), d = bl(a), e = bl(h);
                for (g = 0; d[g]; ++g) e[g] && bk(d[g], e[g])
            }
            if (b) {
                bj(a, h);
                if (c) {
                    d = bl(a), e = bl(h);
                    for (g = 0; d[g]; ++g) bj(d[g], e[g])
                }
            }
            d = e = null;
            return h
        },
        clean: function(a, b, d, e) {
            var g;
            b = b || c, typeof b.createElement == "undefined" && (b = b.ownerDocument || b[0] && b[0].ownerDocument || c);
            var h = [],
                i;
            for (var j = 0, k;
                 (k = a[j]) != null; j++) {
                typeof k == "number" && (k += "");
                if (!k) continue;
                if (typeof k == "string") if (!_.test(k)) k = b.createTextNode(k);
                else {
                    k = k.replace(Y, "<$1></$2>");
                    var l = (Z.exec(k) || ["", ""])[1].toLowerCase(),
                        m = bg[l] || bg._default,
                        n = m[0],
                        o = b.createElement("div");
                    b === c ? bh.appendChild(o) : U(b).appendChild(o), o.innerHTML = m[1] + k + m[2];
                    while (n--) o = o.lastChild;
                    if (!f.support.tbody) {
                        var p = $.test(k),
                            q = l === "table" && !p ? o.firstChild && o.firstChild.childNodes : m[1] === "<table>" && !p ? o.childNodes : [];
                        for (i = q.length - 1; i >= 0; --i) f.nodeName(q[i], "tbody") && !q[i].childNodes.length && q[i].parentNode.removeChild(q[i])
                    }!f.support.leadingWhitespace && X.test(k) && o.insertBefore(b.createTextNode(X.exec(k)[0]), o.firstChild), k = o.childNodes
                }
                var r;
                if (!f.support.appendChecked) if (k[0] && typeof(r = k.length) == "number") for (i = 0; i < r; i++) bn(k[i]);
                else bn(k);
                k.nodeType ? h.push(k) : h = f.merge(h, k)
            }
            if (d) {
                g = function(a) {
                    return !a.type || be.test(a.type)
                };
                for (j = 0; h[j]; j++) if (e && f.nodeName(h[j], "script") && (!h[j].type || h[j].type.toLowerCase() === "text/javascript")) e.push(h[j].parentNode ? h[j].parentNode.removeChild(h[j]) : h[j]);
                else {
                    if (h[j].nodeType === 1) {
                        var s = f.grep(h[j].getElementsByTagName("script"), g);
                        h.splice.apply(h, [j + 1, 0].concat(s))
                    }
                    d.appendChild(h[j])
                }
            }
            return h
        },
        cleanData: function(a) {
            var b, c, d = f.cache,
                e = f.event.special,
                g = f.support.deleteExpando;
            for (var h = 0, i;
                 (i = a[h]) != null; h++) {
                if (i.nodeName && f.noData[i.nodeName.toLowerCase()]) continue;
                c = i[f.expando];
                if (c) {
                    b = d[c];
                    if (b && b.events) {
                        for (var j in b.events) e[j] ? f.event.remove(i, j) : f.removeEvent(i, j, b.handle);
                        b.handle && (b.handle.elem = null)
                    }
                    g ? delete i[f.expando] : i.removeAttribute && i.removeAttribute(f.expando), delete d[c]
                }
            }
        }
    });
    var bq = /alpha\([^)]*\)/i,
        br = /opacity=([^)]*)/,
        bs = /([A-Z]|^ms)/g,
        bt = /^-?\d+(?:px)?$/i,
        bu = /^-?\d/,
        bv = /^([\-+])=([\-+.\de]+)/,
        bw = {
            position: "absolute",
            visibility: "hidden",
            display: "block"
        },
        bx = ["Left", "Right"],
        by = ["Top", "Bottom"],
        bz, bA, bB;
    f.fn.css = function(a, c) {
        if (arguments.length === 2 && c === b) return this;
        return f.access(this, a, c, !0, function(a, c, d) {
            return d !== b ? f.style(a, c, d) : f.css(a, c)
        })
    }, f.extend({
        cssHooks: {
            opacity: {
                get: function(a, b) {
                    if (b) {
                        var c = bz(a, "opacity", "opacity");
                        return c === "" ? "1" : c
                    }
                    return a.style.opacity
                }
            }
        },
        cssNumber: {
            fillOpacity: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {
            "float": f.support.cssFloat ? "cssFloat" : "styleFloat"
        },
        style: function(a, c, d, e) {
            if ( !! a && a.nodeType !== 3 && a.nodeType !== 8 && !! a.style) {
                var g, h, i = f.camelCase(c),
                    j = a.style,
                    k = f.cssHooks[i];
                c = f.cssProps[i] || i;
                if (d === b) {
                    if (k && "get" in k && (g = k.get(a, !1, e)) !== b) return g;
                    return j[c]
                }
                h = typeof d, h === "string" && (g = bv.exec(d)) && (d = +(g[1] + 1) * +g[2] + parseFloat(f.css(a, c)), h = "number");
                if (d == null || h === "number" && isNaN(d)) return;
                h === "number" && !f.cssNumber[i] && (d += "px");
                if (!k || !("set" in k) || (d = k.set(a, d)) !== b) try {
                    j[c] = d
                } catch (l) {}
            }
        },
        css: function(a, c, d) {
            var e, g;
            c = f.camelCase(c), g = f.cssHooks[c], c = f.cssProps[c] || c, c === "cssFloat" && (c = "float");
            if (g && "get" in g && (e = g.get(a, !0, d)) !== b) return e;
            if (bz) return bz(a, c)
        },
        swap: function(a, b, c) {
            var d = {};
            for (var e in b) d[e] = a.style[e], a.style[e] = b[e];
            c.call(a);
            for (e in b) a.style[e] = d[e]
        }
    }), f.curCSS = f.css, f.each(["height", "width"], function(a, b) {
        f.cssHooks[b] = {
            get: function(a, c, d) {
                var e;
                if (c) {
                    if (a.offsetWidth !== 0) return bC(a, b, d);
                    f.swap(a, bw, function() {
                        e = bC(a, b, d)
                    });
                    return e
                }
            },
            set: function(a, b) {
                if (!bt.test(b)) return b;
                b = parseFloat(b);
                if (b >= 0) return b + "px"
            }
        }
    }), f.support.opacity || (f.cssHooks.opacity = {
        get: function(a, b) {
            return br.test((b && a.currentStyle ? a.currentStyle.filter : a.style.filter) || "") ? parseFloat(RegExp.$1) / 100 + "" : b ? "1" : ""
        },
        set: function(a, b) {
            var c = a.style,
                d = a.currentStyle,
                e = f.isNumeric(b) ? "alpha(opacity=" + b * 100 + ")" : "",
                g = d && d.filter || c.filter || "";
            c.zoom = 1;
            if (b >= 1 && f.trim(g.replace(bq, "")) === "") {
                c.removeAttribute("filter");
                if (d && !d.filter) return
            }
            c.filter = bq.test(g) ? g.replace(bq, e) : g + " " + e
        }
    }), f(function() {
        f.support.reliableMarginRight || (f.cssHooks.marginRight = {
            get: function(a, b) {
                var c;
                f.swap(a, {
                    display: "inline-block"
                }, function() {
                    b ? c = bz(a, "margin-right", "marginRight") : c = a.style.marginRight
                });
                return c
            }
        })
    }), c.defaultView && c.defaultView.getComputedStyle && (bA = function(a, b) {
        var c, d, e;
        b = b.replace(bs, "-$1").toLowerCase(), (d = a.ownerDocument.defaultView) && (e = d.getComputedStyle(a, null)) && (c = e.getPropertyValue(b), c === "" && !f.contains(a.ownerDocument.documentElement, a) && (c = f.style(a, b)));
        return c
    }), c.documentElement.currentStyle && (bB = function(a, b) {
        var c, d, e, f = a.currentStyle && a.currentStyle[b],
            g = a.style;
        f === null && g && (e = g[b]) && (f = e), !bt.test(f) && bu.test(f) && (c = g.left, d = a.runtimeStyle && a.runtimeStyle.left, d && (a.runtimeStyle.left = a.currentStyle.left), g.left = b === "fontSize" ? "1em" : f || 0, f = g.pixelLeft + "px", g.left = c, d && (a.runtimeStyle.left = d));
        return f === "" ? "auto" : f
    }), bz = bA || bB, f.expr && f.expr.filters && (f.expr.filters.hidden = function(a) {
        var b = a.offsetWidth,
            c = a.offsetHeight;
        return b === 0 && c === 0 || !f.support.reliableHiddenOffsets && (a.style && a.style.display || f.css(a, "display")) === "none"
    }, f.expr.filters.visible = function(a) {
        return !f.expr.filters.hidden(a)
    });
    var bD = /%20/g,
        bE = /\[\]$/,
        bF = /\r?\n/g,
        bG = /#.*$/,
        bH = /^(.*?):[ \t]*([^\r\n]*)\r?$/mg,
        bI = /^(?:color|date|datetime|datetime-local|email|hidden|month|number|password|range|search|tel|text|time|url|week)$/i,
        bJ = /^(?:about|app|app\-storage|.+\-extension|file|res|widget):$/,
        bK = /^(?:GET|HEAD)$/,
        bL = /^\/\//,
        bM = /\?/,
        bN = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
        bO = /^(?:select|textarea)/i,
        bP = /\s+/,
        bQ = /([?&])_=[^&]*/,
        bR = /^([\w\+\.\-]+:)(?:\/\/([^\/?#:]*)(?::(\d+))?)?/,
        bS = f.fn.load,
        bT = {},
        bU = {},
        bV, bW, bX = ["*/"] + ["*"];
    try {
        bV = e.href
    } catch (bY) {
        bV = c.createElement("a"), bV.href = "", bV = bV.href
    }
    bW = bR.exec(bV.toLowerCase()) || [], f.fn.extend({
        load: function(a, c, d) {
            if (typeof a != "string" && bS) return bS.apply(this, arguments);
            if (!this.length) return this;
            var e = a.indexOf(" ");
            if (e >= 0) {
                var g = a.slice(e, a.length);
                a = a.slice(0, e)
            }
            var h = "GET";
            c && (f.isFunction(c) ? (d = c, c = b) : typeof c == "object" && (c = f.param(c, f.ajaxSettings.traditional), h = "POST"));
            var i = this;
            f.ajax({
                url: a,
                type: h,
                dataType: "html",
                data: c,
                complete: function(a, b, c) {
                    c = a.responseText, a.isResolved() && (a.done(function(a) {
                        c = a
                    }), i.html(g ? f("<div>").append(c.replace(bN, "")).find(g) : c)), d && i.each(d, [c, b, a])
                }
            });
            return this
        },
        serialize: function() {
            return f.param(this.serializeArray())
        },
        serializeArray: function() {
            return this.map(function() {
                return this.elements ? f.makeArray(this.elements) : this
            }).filter(function() {
                    return this.name && !this.disabled && (this.checked || bO.test(this.nodeName) || bI.test(this.type))
                }).map(function(a, b) {
                    var c = f(this).val();
                    return c == null ? null : f.isArray(c) ? f.map(c, function(a, c) {
                        return {
                            name: b.name,
                            value: a.replace(bF, "\r\n")
                        }
                    }) : {
                        name: b.name,
                        value: c.replace(bF, "\r\n")
                    }
                }).get()
        }
    }), f.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "), function(a, b) {
        f.fn[b] = function(a) {
            return this.on(b, a)
        }
    }), f.each(["get", "post"], function(a, c) {
        f[c] = function(a, d, e, g) {
            f.isFunction(d) && (g = g || e, e = d, d = b);
            return f.ajax({
                type: c,
                url: a,
                data: d,
                success: e,
                dataType: g
            })
        }
    }), f.extend({
        getScript: function(a, c) {
            return f.get(a, b, c, "script")
        },
        getJSON: function(a, b, c) {
            return f.get(a, b, c, "json")
        },
        ajaxSetup: function(a, b) {
            b ? b_(a, f.ajaxSettings) : (b = a, a = f.ajaxSettings), b_(a, b);
            return a
        },
        ajaxSettings: {
            url: bV,
            isLocal: bJ.test(bW[1]),
            global: !0,
            type: "GET",
            contentType: "application/x-www-form-urlencoded",
            processData: !0,
            async: !0,
            accepts: {
                xml: "application/xml, text/xml",
                html: "text/html",
                text: "text/plain",
                json: "application/json, text/javascript",
                "*": bX
            },
            contents: {
                xml: /xml/,
                html: /html/,
                json: /json/
            },
            responseFields: {
                xml: "responseXML",
                text: "responseText"
            },
            converters: {
                "* text": a.String,
                "text html": !0,
                "text json": f.parseJSON,
                "text xml": f.parseXML
            },
            flatOptions: {
                context: !0,
                url: !0
            }
        },
        ajaxPrefilter: bZ(bT),
        ajaxTransport: bZ(bU),
        ajax: function(a, c) {
            function w(a, c, l, m) {
                if (s !== 2) {
                    s = 2, q && clearTimeout(q), p = b, n = m || "", v.readyState = a > 0 ? 4 : 0;
                    var o, r, u, w = c,
                        x = l ? cb(d, v, l) : b,
                        y, z;
                    if (a >= 200 && a < 300 || a === 304) {
                        if (d.ifModified) {
                            if (y = v.getResponseHeader("Last-Modified")) f.lastModified[k] = y;
                            if (z = v.getResponseHeader("Etag")) f.etag[k] = z
                        }
                        if (a === 304) w = "notmodified", o = !0;
                        else
                            try {
                                r = cc(d, x), w = "success", o = !0
                            } catch (A) {
                                w = "parsererror", u = A
                            }
                    } else {
                        u = w;
                        if (!w || a) w = "error", a < 0 && (a = 0)
                    }
                    v.status = a, v.statusText = "" + (c || w), o ? h.resolveWith(e, [r, w, v]) : h.rejectWith(e, [v, w, u]), v.statusCode(j), j = b, t && g.trigger("ajax" + (o ? "Success" : "Error"), [v, d, o ? r : u]), i.fireWith(e, [v, w]), t && (g.trigger("ajaxComplete", [v, d]), --f.active || f.event.trigger("ajaxStop"))
                }
            }
            typeof a == "object" && (c = a, a = b), c = c || {};
            var d = f.ajaxSetup({}, c),
                e = d.context || d,
                g = e !== d && (e.nodeType || e instanceof f) ? f(e) : f.event,
                h = f.Deferred(),
                i = f.Callbacks("once memory"),
                j = d.statusCode || {},
                k, l = {},
                m = {},
                n, o, p, q, r, s = 0,
                t, u, v = {
                    readyState: 0,
                    setRequestHeader: function(a, b) {
                        if (!s) {
                            var c = a.toLowerCase();
                            a = m[c] = m[c] || a, l[a] = b
                        }
                        return this
                    },
                    getAllResponseHeaders: function() {
                        return s === 2 ? n : null
                    },
                    getResponseHeader: function(a) {
                        var c;
                        if (s === 2) {
                            if (!o) {
                                o = {};
                                while (c = bH.exec(n)) o[c[1].toLowerCase()] = c[2]
                            }
                            c = o[a.toLowerCase()]
                        }
                        return c === b ? null : c
                    },
                    overrideMimeType: function(a) {
                        s || (d.mimeType = a);
                        return this
                    },
                    abort: function(a) {
                        a = a || "abort", p && p.abort(a), w(0, a);
                        return this
                    }
                };
            h.promise(v), v.success = v.done, v.error = v.fail, v.complete = i.add, v.statusCode = function(a) {
                if (a) {
                    var b;
                    if (s < 2) for (b in a) j[b] = [j[b], a[b]];
                    else b = a[v.status], v.then(b, b)
                }
                return this
            }, d.url = ((a || d.url) + "").replace(bG, "").replace(bL, bW[1] + "//"), d.dataTypes = f.trim(d.dataType || "*").toLowerCase().split(bP), d.crossDomain == null && (r = bR.exec(d.url.toLowerCase()), d.crossDomain = !(!r || r[1] == bW[1] && r[2] == bW[2] && (r[3] || (r[1] === "http:" ? 80 : 443)) == (bW[3] || (bW[1] === "http:" ? 80 : 443)))), d.data && d.processData && typeof d.data != "string" && (d.data = f.param(d.data, d.traditional)), b$(bT, d, c, v);
            if (s === 2) return !1;
            t = d.global, d.type = d.type.toUpperCase(), d.hasContent = !bK.test(d.type), t && f.active++ === 0 && f.event.trigger("ajaxStart");
            if (!d.hasContent) {
                d.data && (d.url += (bM.test(d.url) ? "&" : "?") + d.data, delete d.data), k = d.url;
                if (d.cache === !1) {
                    var x = f.now(),
                        y = d.url.replace(bQ, "$1_=" + x);
                    d.url = y + (y === d.url ? (bM.test(d.url) ? "&" : "?") + "_=" + x : "")
                }
            }(d.data && d.hasContent && d.contentType !== !1 || c.contentType) && v.setRequestHeader("Content-Type", d.contentType), d.ifModified && (k = k || d.url, f.lastModified[k] && v.setRequestHeader("If-Modified-Since", f.lastModified[k]), f.etag[k] && v.setRequestHeader("If-None-Match", f.etag[k])), v.setRequestHeader("Accept", d.dataTypes[0] && d.accepts[d.dataTypes[0]] ? d.accepts[d.dataTypes[0]] + (d.dataTypes[0] !== "*" ? ", " + bX + "; q=0.01" : "") : d.accepts["*"]);
            for (u in d.headers) v.setRequestHeader(u, d.headers[u]);
            if (d.beforeSend && (d.beforeSend.call(e, v, d) === !1 || s === 2)) {
                v.abort();
                return !1
            }
            for (u in {
                success: 1,
                error: 1,
                complete: 1
            }) v[u](d[u]);
            p = b$(bU, d, c, v);
            if (!p) w(-1, "No Transport");
            else {
                v.readyState = 1, t && g.trigger("ajaxSend", [v, d]), d.async && d.timeout > 0 && (q = setTimeout(function() {
                    v.abort("timeout")
                }, d.timeout));
                try {
                    s = 1, p.send(l, w)
                } catch (z) {
                    if (s < 2) w(-1, z);
                    else
                        throw z
                }
            }
            return v
        },
        param: function(a, c) {
            var d = [],
                e = function(a, b) {
                    b = f.isFunction(b) ? b() : b, d[d.length] = encodeURIComponent(a) + "=" + encodeURIComponent(b)
                };
            c === b && (c = f.ajaxSettings.traditional);
            if (f.isArray(a) || a.jquery && !f.isPlainObject(a)) f.each(a, function() {
                e(this.name, this.value)
            });
            else
                for (var g in a) ca(g, a[g], c, e);
            return d.join("&").replace(bD, "+")
        }
    }), f.extend({
        active: 0,
        lastModified: {},
        etag: {}
    });
    var cd = f.now(),
        ce = /(\=)\?(&|$)|\?\?/i;
    f.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function() {
            return f.expando + "_" + cd++
        }
    }), f.ajaxPrefilter("json jsonp", function(b, c, d) {
        var e = b.contentType === "application/x-www-form-urlencoded" && typeof b.data == "string";
        if (b.dataTypes[0] === "jsonp" || b.jsonp !== !1 && (ce.test(b.url) || e && ce.test(b.data))) {
            var g, h = b.jsonpCallback = f.isFunction(b.jsonpCallback) ? b.jsonpCallback() : b.jsonpCallback,
                i = a[h],
                j = b.url,
                k = b.data,
                l = "$1" + h + "$2";
            b.jsonp !== !1 && (j = j.replace(ce, l), b.url === j && (e && (k = k.replace(ce, l)), b.data === k && (j += (/\?/.test(j) ? "&" : "?") + b.jsonp + "=" + h))), b.url = j, b.data = k, a[h] = function(a) {
                g = [a]
            }, d.always(function() {
                a[h] = i, g && f.isFunction(i) && a[h](g[0])
            }), b.converters["script json"] = function() {
                g || f.error(h + " was not called");
                return g[0]
            }, b.dataTypes[0] = "json";
            return "script"
        }
    }), f.ajaxSetup({
        accepts: {
            script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        contents: {
            script: /javascript|ecmascript/
        },
        converters: {
            "text script": function(a) {
                f.globalEval(a);
                return a
            }
        }
    }), f.ajaxPrefilter("script", function(a) {
        a.cache === b && (a.cache = !1), a.crossDomain && (a.type = "GET", a.global = !1)
    }), f.ajaxTransport("script", function(a) {
        if (a.crossDomain) {
            var d, e = c.head || c.getElementsByTagName("head")[0] || c.documentElement;
            return {
                send: function(f, g) {
                    d = c.createElement("script"), d.async = "async", a.scriptCharset && (d.charset = a.scriptCharset), d.src = a.url, d.onload = d.onreadystatechange = function(a, c) {
                        if (c || !d.readyState || /loaded|complete/.test(d.readyState)) d.onload = d.onreadystatechange = null, e && d.parentNode && e.removeChild(d), d = b, c || g(200, "success")
                    }, e.insertBefore(d, e.firstChild)
                },
                abort: function() {
                    d && d.onload(0, 1)
                }
            }
        }
    });
    var cf = a.ActiveXObject ?
            function() {
                for (var a in ch) ch[a](0, 1)
            } : !1,
        cg = 0,
        ch;
    f.ajaxSettings.xhr = a.ActiveXObject ?
        function() {
            return !this.isLocal && ci() || cj()
        } : ci, function(a) {
        f.extend(f.support, {
            ajax: !! a,
            cors: !! a && "withCredentials" in a
        })
    }(f.ajaxSettings.xhr()), f.support.ajax && f.ajaxTransport(function(c) {
        if (!c.crossDomain || f.support.cors) {
            var d;
            return {
                send: function(e, g) {
                    var h = c.xhr(),
                        i, j;
                    c.username ? h.open(c.type, c.url, c.async, c.username, c.password) : h.open(c.type, c.url, c.async);
                    if (c.xhrFields) for (j in c.xhrFields) h[j] = c.xhrFields[j];
                    c.mimeType && h.overrideMimeType && h.overrideMimeType(c.mimeType), !c.crossDomain && !e["X-Requested-With"] && (e["X-Requested-With"] = "XMLHttpRequest");
                    try {
                        for (j in e) h.setRequestHeader(j, e[j])
                    } catch (k) {}
                    h.send(c.hasContent && c.data || null), d = function(a, e) {
                        var j, k, l, m, n;
                        try {
                            if (d && (e || h.readyState === 4)) {
                                d = b, i && (h.onreadystatechange = f.noop, cf && delete ch[i]);
                                if (e) h.readyState !== 4 && h.abort();
                                else {
                                    j = h.status, l = h.getAllResponseHeaders(), m = {}, n = h.responseXML, n && n.documentElement && (m.xml = n), m.text = h.responseText;
                                    try {
                                        k = h.statusText
                                    } catch (o) {
                                        k = ""
                                    }!j && c.isLocal && !c.crossDomain ? j = m.text ? 200 : 404 : j === 1223 && (j = 204)
                                }
                            }
                        } catch (p) {
                            e || g(-1, p)
                        }
                        m && g(j, k, m, l)
                    }, !c.async || h.readyState === 4 ? d() : (i = ++cg, cf && (ch || (ch = {}, f(a).unload(cf)), ch[i] = d), h.onreadystatechange = d)
                },
                abort: function() {
                    d && d(0, 1)
                }
            }
        }
    });
    var ck = {},
        cl, cm, cn = /^(?:toggle|show|hide)$/,
        co = /^([+\-]=)?([\d+.\-]+)([a-z%]*)$/i,
        cp, cq = [
            ["height", "marginTop", "marginBottom", "paddingTop", "paddingBottom"],
            ["width", "marginLeft", "marginRight", "paddingLeft", "paddingRight"],
            ["opacity"]
        ],
        cr;
    f.fn.extend({
        show: function(a, b, c) {
            var d, e;
            if (a || a === 0) return this.animate(cu("show", 3), a, b, c);
            for (var g = 0, h = this.length; g < h; g++) d = this[g], d.style && (e = d.style.display, !f._data(d, "olddisplay") && e === "none" && (e = d.style.display = ""), e === "" && f.css(d, "display") === "none" && f._data(d, "olddisplay", cv(d.nodeName)));
            for (g = 0; g < h; g++) {
                d = this[g];
                if (d.style) {
                    e = d.style.display;
                    if (e === "" || e === "none") d.style.display = f._data(d, "olddisplay") || ""
                }
            }
            return this
        },
        hide: function(a, b, c) {
            if (a || a === 0) return this.animate(cu("hide", 3), a, b, c);
            var d, e, g = 0,
                h = this.length;
            for (; g < h; g++) d = this[g], d.style && (e = f.css(d, "display"), e !== "none" && !f._data(d, "olddisplay") && f._data(d, "olddisplay", e));
            for (g = 0; g < h; g++) this[g].style && (this[g].style.display = "none");
            return this
        },
        _toggle: f.fn.toggle,
        toggle: function(a, b, c) {
            var d = typeof a == "boolean";
            f.isFunction(a) && f.isFunction(b) ? this._toggle.apply(this, arguments) : a == null || d ? this.each(function() {
                var b = d ? a : f(this).is(":hidden");
                f(this)[b ? "show" : "hide"]()
            }) : this.animate(cu("toggle", 3), a, b, c);
            return this
        },
        fadeTo: function(a, b, c, d) {
            return this.filter(":hidden").css("opacity", 0).show().end().animate({
                opacity: b
            }, a, c, d)
        },
        animate: function(a, b, c, d) {
            function g() {
                e.queue === !1 && f._mark(this);
                var b = f.extend({}, e),
                    c = this.nodeType === 1,
                    d = c && f(this).is(":hidden"),
                    g, h, i, j, k, l, m, n, o;
                b.animatedProperties = {};
                for (i in a) {
                    g = f.camelCase(i), i !== g && (a[g] = a[i], delete a[i]), h = a[g], f.isArray(h) ? (b.animatedProperties[g] = h[1], h = a[g] = h[0]) : b.animatedProperties[g] = b.specialEasing && b.specialEasing[g] || b.easing || "swing";
                    if (h === "hide" && d || h === "show" && !d) return b.complete.call(this);
                    c && (g === "height" || g === "width") && (b.overflow = [this.style.overflow, this.style.overflowX, this.style.overflowY], f.css(this, "display") === "inline" && f.css(this, "float") === "none" && (!f.support.inlineBlockNeedsLayout || cv(this.nodeName) === "inline" ? this.style.display = "inline-block" : this.style.zoom = 1))
                }
                b.overflow != null && (this.style.overflow = "hidden");
                for (i in a) j = new f.fx(this, b, i), h = a[i], cn.test(h) ? (o = f._data(this, "toggle" + i) || (h === "toggle" ? d ? "show" : "hide" : 0), o ? (f._data(this, "toggle" + i, o === "show" ? "hide" : "show"), j[o]()) : j[h]()) : (k = co.exec(h), l = j.cur(), k ? (m = parseFloat(k[2]), n = k[3] || (f.cssNumber[i] ? "" : "px"), n !== "px" && (f.style(this, i, (m || 1) + n), l = (m || 1) / j.cur() * l, f.style(this, i, l + n)), k[1] && (m = (k[1] === "-=" ? -1 : 1) * m + l), j.custom(l, m, n)) : j.custom(l, h, ""));
                return !0
            }
            var e = f.speed(b, c, d);
            if (f.isEmptyObject(a)) return this.each(e.complete, [!1]);
            a = f.extend({}, a);
            return e.queue === !1 ? this.each(g) : this.queue(e.queue, g)
        },
        stop: function(a, c, d) {
            typeof a != "string" && (d = c, c = a, a = b), c && a !== !1 && this.queue(a || "fx", []);
            return this.each(function() {
                function h(a, b, c) {
                    var e = b[c];
                    f.removeData(a, c, !0), e.stop(d)
                }
                var b, c = !1,
                    e = f.timers,
                    g = f._data(this);
                d || f._unmark(!0, this);
                if (a == null) for (b in g) g[b] && g[b].stop && b.indexOf(".run") === b.length - 4 && h(this, g, b);
                else g[b = a + ".run"] && g[b].stop && h(this, g, b);
                for (b = e.length; b--;) e[b].elem === this && (a == null || e[b].queue === a) && (d ? e[b](!0) : e[b].saveState(), c = !0, e.splice(b, 1));
                (!d || !c) && f.dequeue(this, a)
            })
        }
    }), f.each({
        slideDown: cu("show", 1),
        slideUp: cu("hide", 1),
        slideToggle: cu("toggle", 1),
        fadeIn: {
            opacity: "show"
        },
        fadeOut: {
            opacity: "hide"
        },
        fadeToggle: {
            opacity: "toggle"
        }
    }, function(a, b) {
        f.fn[a] = function(a, c, d) {
            return this.animate(b, a, c, d)
        }
    }), f.extend({
        speed: function(a, b, c) {
            var d = a && typeof a == "object" ? f.extend({}, a) : {
                complete: c || !c && b || f.isFunction(a) && a,
                duration: a,
                easing: c && b || b && !f.isFunction(b) && b
            };
            d.duration = f.fx.off ? 0 : typeof d.duration == "number" ? d.duration : d.duration in f.fx.speeds ? f.fx.speeds[d.duration] : f.fx.speeds._default;
            if (d.queue == null || d.queue === !0) d.queue = "fx";
            d.old = d.complete, d.complete = function(a) {
                f.isFunction(d.old) && d.old.call(this), d.queue ? f.dequeue(this, d.queue) : a !== !1 && f._unmark(this)
            };
            return d
        },
        easing: {
            linear: function(a, b, c, d) {
                return c + d * a
            },
            swing: function(a, b, c, d) {
                return (-Math.cos(a * Math.PI) / 2 + .5) * d + c
            }
        },
        timers: [],
        fx: function(a, b, c) {
            this.options = b, this.elem = a, this.prop = c, b.orig = b.orig || {}
        }
    }), f.fx.prototype = {
        update: function() {
            this.options.step && this.options.step.call(this.elem, this.now, this), (f.fx.step[this.prop] || f.fx.step._default)(this)
        },
        cur: function() {
            if (this.elem[this.prop] != null && (!this.elem.style || this.elem.style[this.prop] == null)) return this.elem[this.prop];
            var a, b = f.css(this.elem, this.prop);
            return isNaN(a = parseFloat(b)) ? !b || b === "auto" ? 0 : b : a
        },
        custom: function(a, c, d) {
            function h(a) {
                return e.step(a)
            }
            var e = this,
                g = f.fx;
            this.startTime = cr || cs(), this.end = c, this.now = this.start = a, this.pos = this.state = 0, this.unit = d || this.unit || (f.cssNumber[this.prop] ? "" : "px"), h.queue = this.options.queue, h.elem = this.elem, h.saveState = function() {
                e.options.hide && f._data(e.elem, "fxshow" + e.prop) === b && f._data(e.elem, "fxshow" + e.prop, e.start)
            }, h() && f.timers.push(h) && !cp && (cp = setInterval(g.tick, g.interval))
        },
        show: function() {
            var a = f._data(this.elem, "fxshow" + this.prop);
            this.options.orig[this.prop] = a || f.style(this.elem, this.prop), this.options.show = !0, a !== b ? this.custom(this.cur(), a) : this.custom(this.prop === "width" || this.prop === "height" ? 1 : 0, this.cur()), f(this.elem).show()
        },
        hide: function() {
            this.options.orig[this.prop] = f._data(this.elem, "fxshow" + this.prop) || f.style(this.elem, this.prop), this.options.hide = !0, this.custom(this.cur(), 0)
        },
        step: function(a) {
            var b, c, d, e = cr || cs(),
                g = !0,
                h = this.elem,
                i = this.options;
            if (a || e >= i.duration + this.startTime) {
                this.now = this.end, this.pos = this.state = 1, this.update(), i.animatedProperties[this.prop] = !0;
                for (b in i.animatedProperties) i.animatedProperties[b] !== !0 && (g = !1);
                if (g) {
                    i.overflow != null && !f.support.shrinkWrapBlocks && f.each(["", "X", "Y"], function(a, b) {
                        h.style["overflow" + b] = i.overflow[a]
                    }), i.hide && f(h).hide();
                    if (i.hide || i.show) for (b in i.animatedProperties) f.style(h, b, i.orig[b]), f.removeData(h, "fxshow" + b, !0), f.removeData(h, "toggle" + b, !0);
                    d = i.complete, d && (i.complete = !1, d.call(h))
                }
                return !1
            }
            i.duration == Infinity ? this.now = e : (c = e - this.startTime, this.state = c / i.duration, this.pos = f.easing[i.animatedProperties[this.prop]](this.state, c, 0, 1, i.duration), this.now = this.start + (this.end - this.start) * this.pos), this.update();
            return !0
        }
    }, f.extend(f.fx, {
        tick: function() {
            var a, b = f.timers,
                c = 0;
            for (; c < b.length; c++) a = b[c], !a() && b[c] === a && b.splice(c--, 1);
            b.length || f.fx.stop()
        },
        interval: 13,
        stop: function() {
            clearInterval(cp), cp = null
        },
        speeds: {
            slow: 600,
            fast: 200,
            _default: 400
        },
        step: {
            opacity: function(a) {
                f.style(a.elem, "opacity", a.now)
            },
            _default: function(a) {
                a.elem.style && a.elem.style[a.prop] != null ? a.elem.style[a.prop] = a.now + a.unit : a.elem[a.prop] = a.now
            }
        }
    }), f.each(["width", "height"], function(a, b) {
        f.fx.step[b] = function(a) {
            f.style(a.elem, b, Math.max(0, a.now) + a.unit)
        }
    }), f.expr && f.expr.filters && (f.expr.filters.animated = function(a) {
        return f.grep(f.timers, function(b) {
            return a === b.elem
        }).length
    });
    var cw = /^t(?:able|d|h)$/i,
        cx = /^(?:body|html)$/i;
    "getBoundingClientRect" in c.documentElement ? f.fn.offset = function(a) {
        var b = this[0],
            c;
        if (a) return this.each(function(b) {
            f.offset.setOffset(this, a, b)
        });
        if (!b || !b.ownerDocument) return null;
        if (b === b.ownerDocument.body) return f.offset.bodyOffset(b);
        try {
            c = b.getBoundingClientRect()
        } catch (d) {}
        var e = b.ownerDocument,
            g = e.documentElement;
        if (!c || !f.contains(g, b)) return c ? {
            top: c.top,
            left: c.left
        } : {
            top: 0,
            left: 0
        };
        var h = e.body,
            i = cy(e),
            j = g.clientTop || h.clientTop || 0,
            k = g.clientLeft || h.clientLeft || 0,
            l = i.pageYOffset || f.support.boxModel && g.scrollTop || h.scrollTop,
            m = i.pageXOffset || f.support.boxModel && g.scrollLeft || h.scrollLeft,
            n = c.top + l - j,
            o = c.left + m - k;
        return {
            top: n,
            left: o
        }
    } : f.fn.offset = function(a) {
        var b = this[0];
        if (a) return this.each(function(b) {
            f.offset.setOffset(this, a, b)
        });
        if (!b || !b.ownerDocument) return null;
        if (b === b.ownerDocument.body) return f.offset.bodyOffset(b);
        var c, d = b.offsetParent,
            e = b,
            g = b.ownerDocument,
            h = g.documentElement,
            i = g.body,
            j = g.defaultView,
            k = j ? j.getComputedStyle(b, null) : b.currentStyle,
            l = b.offsetTop,
            m = b.offsetLeft;
        while ((b = b.parentNode) && b !== i && b !== h) {
            if (f.support.fixedPosition && k.position === "fixed") break;
            c = j ? j.getComputedStyle(b, null) : b.currentStyle, l -= b.scrollTop, m -= b.scrollLeft, b === d && (l += b.offsetTop, m += b.offsetLeft, f.support.doesNotAddBorder && (!f.support.doesAddBorderForTableAndCells || !cw.test(b.nodeName)) && (l += parseFloat(c.borderTopWidth) || 0, m += parseFloat(c.borderLeftWidth) || 0), e = d, d = b.offsetParent), f.support.subtractsBorderForOverflowNotVisible && c.overflow !== "visible" && (l += parseFloat(c.borderTopWidth) || 0, m += parseFloat(c.borderLeftWidth) || 0), k = c
        }
        if (k.position === "relative" || k.position === "static") l += i.offsetTop, m += i.offsetLeft;
        f.support.fixedPosition && k.position === "fixed" && (l += Math.max(h.scrollTop, i.scrollTop), m += Math.max(h.scrollLeft, i.scrollLeft));
        return {
            top: l,
            left: m
        }
    }, f.offset = {
        bodyOffset: function(a) {
            var b = a.offsetTop,
                c = a.offsetLeft;
            f.support.doesNotIncludeMarginInBodyOffset && (b += parseFloat(f.css(a, "marginTop")) || 0, c += parseFloat(f.css(a, "marginLeft")) || 0);
            return {
                top: b,
                left: c
            }
        },
        setOffset: function(a, b, c) {
            var d = f.css(a, "position");
            d === "static" && (a.style.position = "relative");
            var e = f(a),
                g = e.offset(),
                h = f.css(a, "top"),
                i = f.css(a, "left"),
                j = (d === "absolute" || d === "fixed") && f.inArray("auto", [h, i]) > -1,
                k = {},
                l = {},
                m, n;
            j ? (l = e.position(), m = l.top, n = l.left) : (m = parseFloat(h) || 0, n = parseFloat(i) || 0), f.isFunction(b) && (b = b.call(a, c, g)), b.top != null && (k.top = b.top - g.top + m), b.left != null && (k.left = b.left - g.left + n), "using" in b ? b.using.call(a, k) : e.css(k)
        }
    }, f.fn.extend({
        position: function() {
            if (!this[0]) return null;
            var a = this[0],
                b = this.offsetParent(),
                c = this.offset(),
                d = cx.test(b[0].nodeName) ? {
                    top: 0,
                    left: 0
                } : b.offset();
            c.top -= parseFloat(f.css(a, "marginTop")) || 0, c.left -= parseFloat(f.css(a, "marginLeft")) || 0, d.top += parseFloat(f.css(b[0], "borderTopWidth")) || 0, d.left += parseFloat(f.css(b[0], "borderLeftWidth")) || 0;
            return {
                top: c.top - d.top,
                left: c.left - d.left
            }
        },
        offsetParent: function() {
            return this.map(function() {
                var a = this.offsetParent || c.body;
                while (a && !cx.test(a.nodeName) && f.css(a, "position") === "static") a = a.offsetParent;
                return a
            })
        }
    }), f.each(["Left", "Top"], function(a, c) {
        var d = "scroll" + c;
        f.fn[d] = function(c) {
            var e, g;
            if (c === b) {
                e = this[0];
                if (!e) return null;
                g = cy(e);
                return g ? "pageXOffset" in g ? g[a ? "pageYOffset" : "pageXOffset"] : f.support.boxModel && g.document.documentElement[d] || g.document.body[d] : e[d]
            }
            return this.each(function() {
                g = cy(this), g ? g.scrollTo(a ? f(g).scrollLeft() : c, a ? c : f(g).scrollTop()) : this[d] = c
            })
        }
    }), f.each(["Height", "Width"], function(a, c) {
        var d = c.toLowerCase();
        f.fn["inner" + c] = function() {
            var a = this[0];
            return a ? a.style ? parseFloat(f.css(a, d, "padding")) : this[d]() : null
        }, f.fn["outer" + c] = function(a) {
            var b = this[0];
            return b ? b.style ? parseFloat(f.css(b, d, a ? "margin" : "border")) : this[d]() : null
        }, f.fn[d] = function(a) {
            var e = this[0];
            if (!e) return a == null ? null : this;
            if (f.isFunction(a)) return this.each(function(b) {
                var c = f(this);
                c[d](a.call(this, b, c[d]()))
            });
            if (f.isWindow(e)) {
                var g = e.document.documentElement["client" + c],
                    h = e.document.body;
                return e.document.compatMode === "CSS1Compat" && g || h && h["client" + c] || g
            }
            if (e.nodeType === 9) return Math.max(e.documentElement["client" + c], e.body["scroll" + c], e.documentElement["scroll" + c], e.body["offset" + c], e.documentElement["offset" + c]);
            if (a === b) {
                var i = f.css(e, d),
                    j = parseFloat(i);
                return f.isNumeric(j) ? j : i
            }
            return this.css(d, typeof a == "string" ? a : a + "px")
        }
    }), a.avpw_jQuery = a.avpw$ = f, typeof define == "function" && define.amd && define.amd.jQuery && define("jquery", [], function() {
        return f
    })
})(window);
/*
 * jQuery miniColors: A small color selector
 *
 * Copyright 2011 Cory LaViska for A Beautiful Site, LLC. (http://abeautifulsite.net/)
 *
 * Dual licensed under the MIT or GPL Version 2 licenses
 *
 */
(function(e) {
    e.miniColors = function(t) {
        var n = this,
            r = function(t) {
                var n = e('<div style="display: none;" class="miniColors-selector"></div>');
                return n.append('<div class="miniColors-colors" style="background-color: #FFF;"><div class="miniColors-colorPicker"><div class="miniColors-colorPicker-inner"></div></div></div>').append('<div class="miniColors-hues"><div class="miniColors-huePicker"></div></div>'), n.find(".miniColors-colors").css("backgroundColor", "#" + m({
                    h: t.h,
                    s: 100,
                    b: 100
                })), f(n, t), n.bind("selectstart", function() {
                    return !1
                }), n.data("hsb", t), n
            },
            i = function(e, t) {},
            s = function(t) {
                var n = !1,
                    r;
                e(document).bind("mousedown.miniColors touchstart.miniColors", function(i) {
                    n = !0;
                    var s = e(i.target).parents().andSelf();
                    s.hasClass("miniColors-colors") && (i.preventDefault(), r = "colors", u(t, i)), s.hasClass("miniColors-hues") && (i.preventDefault(), r = "hues", a(t, i));
                    if (s.hasClass("miniColors-selector")) {
                        i.preventDefault();
                        return
                    }
                    if (s.hasClass("miniColors")) return;
                    t.trigger("clickOutsideBounds")
                }), e(document).bind("mouseup.miniColors touchend.miniColors", function(e) {
                    e.preventDefault(), n = !1, r = undefined
                }).bind("mousemove.miniColors touchmove.miniColors", function(e) {
                        e.preventDefault(), n && (r === "colors" && u(t, e), r === "hues" && a(t, e))
                    })
            },
            o = function() {
                e(document).unbind(".miniColors")
            },
            u = function(e, t) {
                var n = {
                        x: t.pageX,
                        y: t.pageY
                    },
                    r = e.find(".miniColors-colors").offset();
                t.originalEvent.changedTouches && (n.x = t.originalEvent.changedTouches[0].pageX, n.y = t.originalEvent.changedTouches[0].pageY), n.x = n.x - r.left - 5, n.y = n.y - r.top - 5, n.x <= -5 && (n.x = -5), n.x >= 144 && (n.x = 144), n.y <= -5 && (n.y = -5), n.y >= 144 && (n.y = 144);
                var i = Math.round((n.x + 5) * .67);
                i < 0 && (i = 0), i > 100 && (i = 100);
                var s = 100 - Math.round((n.y + 5) * .67);
                s < 0 && (s = 0), s > 100 && (s = 100);
                var o = e.data("hsb");
                o.s = i, o.b = s, l(e, o, !0)
            },
            a = function(e, t) {
                var n = {
                        y: t.pageY
                    },
                    r = e.find(".miniColors-colors").offset();
                t.originalEvent.changedTouches && (n.y = t.originalEvent.changedTouches[0].pageY), n.y = n.y - r.top - 1, n.y <= -1 && (n.y = -1), n.y >= 149 && (n.y = 149);
                var i = Math.round((150 - n.y - 1) * 2.4);
                i < 0 && (i = 0), i > 360 && (i = 360);
                var s = e.data("hsb");
                s.h = i, l(e, s, !0)
            },
            f = function(e, t) {
                var n = c(t),
                    r = e.find(".miniColors-colorPicker");
                r.css("top", n.y + "px").css("left", n.x + "px").show();
                var i = p(t),
                    s = e.find(".miniColors-huePicker");
                s.css("top", i.y + "px").show()
            },
            l = function(e, t, n) {
                f(e, t), e.data("hsb", t);
                var r = m(t);
                n && e.trigger("updateInput", {
                    hex: r
                }), e.find(".miniColors-colors").css("backgroundColor", "#" + m({
                    h: t.h,
                    s: 100,
                    b: 100
                })), e.trigger("setColor", {
                    hex: r
                })
            },
            c = function(e) {
                var t = Math.ceil(e.s / .67);
                t < 0 && (t = 0), t > 150 && (t = 150);
                var n = 150 - Math.ceil(e.b / .67);
                return n < 0 && (n = 0), n > 150 && (n = 150), {
                    x: t - 5,
                    y: n - 5
                }
            },
            p = function(e) {
                var t = 150 - e.h / 2.4;
                return t < 0 && (h = 0), t > 150 && (h = 150), {
                    y: t - 1
                }
            },
            d = function(e) {
                var t = {},
                    n = Math.round(e.h),
                    r = Math.round(e.s * 255 / 100),
                    i = Math.round(e.b * 255 / 100);
                if (r === 0) t.r = t.g = t.b = i;
                else {
                    var s = i,
                        o = (255 - r) * i / 255,
                        u = (s - o) * (n % 60) / 60;
                    n === 360 && (n = 0), n < 60 ? (t.r = s, t.b = o, t.g = o + u) : n < 120 ? (t.g = s, t.b = o, t.r = s - u) : n < 180 ? (t.g = s, t.r = o, t.b = o + u) : n < 240 ? (t.b = s, t.r = o, t.g = s - u) : n < 300 ? (t.b = s, t.g = o, t.r = o + u) : n < 360 ? (t.r = s, t.g = o, t.b = s - u) : (t.r = 0, t.g = 0, t.b = 0)
                }
                return {
                    r: Math.round(t.r),
                    g: Math.round(t.g),
                    b: Math.round(t.b)
                }
            },
            v = function(e) {
                var t = [e.r.toString(16), e.g.toString(16), e.b.toString(16)],
                    n, r = t.length,
                    i;
                for (n = 0; n < r; n++) i = t[n], i.length === 1 && (t[n] = "0" + i);
                return t.join("")
            },
            m = function(e) {
                return v(d(e))
            };
        return n.setSelectorColor = l, n.buildSelector = r, n.bindSelectorEvents = s, n.unBindSelectorEvents = o, n
    }
})(avpw_jQuery);
//fgnass.github.com/spin.js#v1.2.2
/**
 * Modified spin.js: some utility methods replaced
 * with Feather analogs, and adding id param for styling
 * (no positioning via style)
 *
 * Use as new AV.Spinner()
 */
(function(e, t, n) {
    function s(e, n) {
        var r = t.createElement(e || "div"),
            i;
        for (i in n) r[i] = n[i];
        return r
    }
    function o(e, t, n) {
        return n && !n.parentNode && o(e, n), e.insertBefore(t, n || null), e
    }
    function a(e, t, n, s) {
        var o = ["opacity", t, ~~ (e * 100), n, s].join("-"),
            a = .01 + n / s * 100,
            f = Math.max(1 - (1 - e) / t * (100 - a), e),
            l = i.substring(0, i.indexOf("Animation")).toLowerCase(),
            c = l && "-" + l + "-" || "";
        return r[o] || (u.insertRule("@" + c + "keyframes " + o + "{" + "0%{opacity:" + f + "}" + a + "%{opacity:" + e + "}" + (a + .01) + "%{opacity:1}" + (a + t) % 100 + "%{opacity:" + e + "}" + "100%{opacity:" + f + "}" + "}", 0), r[o] = 1), o
    }
    function f(e, t) {
        for (var n in t) e.style[AV.support.getVendorProperty(n) || n] = t[n];
        return e
    }
    function l(e) {
        for (var t = 1; t < arguments.length; t++) {
            var r = arguments[t];
            for (var i in r) e[i] === n && (e[i] = r[i])
        }
        return e
    }
    var r = {},
        i, u = function() {
            var e = s("style");
            return o(t.getElementsByTagName("head")[0], e), e.sheet || e.styleSheet
        }(),
        c = function d(e) {
            if (!this.spin) return new d(e);
            this.opts = l(e || {}, d.defaults, h)
        },
        h = c.defaults = {
            lines: 12,
            length: 7,
            width: 5,
            radius: 10,
            color: "#000",
            speed: 1,
            trail: 100,
            opacity: .25,
            fps: 20
        },
        p = c.prototype = {
            spin: function(e) {
                this.stop();
                var t = this,
                    n = t.el = s(),
                    r, u;
                t.opts.id && (t.el.id = t.opts.id), e && o(e, n, e.firstChild), n.setAttribute("aria-role", "progressbar"), t.lines(n, t.opts);
                if (!i) {
                    var a = t.opts,
                        f = 0,
                        l = a.fps,
                        c = l / a.speed,
                        h = (1 - a.opacity) / (c * a.trail / 100),
                        p = c / a.lines;
                    (function d() {
                        f++;
                        for (var e = a.lines; e; e--) {
                            var r = Math.max(1 - (f + e * p) % c * h, a.opacity);
                            t.opacity(n, a.lines - e, r, a)
                        }
                        t.timeout = t.el && setTimeout(d, ~~ (1e3 / l))
                    })()
                }
                return t
            },
            stop: function() {
                var e = this.el;
                return e && (clearTimeout(this.timeout), e.parentNode && e.parentNode.removeChild(e), this.el = n), this
            }
        };
    p.lines = function(e, t) {
        function u(e, r) {
            return f(s(), {
                position: "absolute",
                width: t.length + t.width + "px",
                height: t.width + "px",
                background: e,
                boxShadow: r,
                transformOrigin: "left",
                transform: "rotate(" + ~~ (360 / t.lines * n) + "deg) translate(" + t.radius + "px" + ",0)",
                borderRadius: (t.width >> 1) + "px"
            })
        }
        var n = 0,
            r;
        for (; n < t.lines; n++) r = f(s(), {
            position: "absolute",
            top: 1 + ~ (t.width / 2) + "px",
            opacity: t.opacity,
            animation: i && a(t.opacity, t.trail, n, t.lines) + " " + 1 / t.speed + "s linear infinite"
        }), t.shadow && o(r, f(u("#000", "0 0 4px #000"), {
            top: "2px"
        })), o(e, o(r, u(t.color, "0 0 1px rgba(0,0,0,.1)")));
        return e
    }, p.opacity = function(e, t, n) {
        t < e.childNodes.length && (e.childNodes[t].style.opacity = n)
    }, function() {
        var e = f(s("group"), {
                behavior: "url(#default#VML)"
            }),
            t;
        if (!AV.support.getVendorProperty("transform") && e.adj) {
            for (t = 4; t--;) u.addRule(["group", "roundrect", "fill", "stroke"][t], "behavior:url(#default#VML)");
            p.lines = function(e, t) {
                function i() {
                    return f(s("group", {
                        coordsize: r + " " + r,
                        coordorigin: -n + " " + -n
                    }), {
                        width: r,
                        height: r
                    })
                }
                function c(e, r, a) {
                    o(u, o(f(i(), {
                        rotation: 360 / t.lines * e + "deg",
                        left: ~~r
                    }), o(f(s("roundrect", {
                        arcsize: 1
                    }), {
                        width: n,
                        height: t.width,
                        left: t.radius,
                        top: -t.width >> 1,
                        filter: a
                    }), s("fill", {
                        color: t.color,
                        opacity: t.opacity
                    }), s("stroke", {
                        opacity: 0
                    }))))
                }
                var n = t.length + t.width,
                    r = 2 * n,
                    u = i(),
                    a = ~ (t.length + t.radius + t.width) + "px",
                    l;
                if (t.shadow) for (l = 1; l <= t.lines; l++) c(l, -2, "progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)");
                for (l = 1; l <= t.lines; l++) c(l);
                return o(f(e, {
                    zoom: 1
                }), u)
            }, p.opacity = function(e, t, n, r) {
                var i = e.firstChild;
                r = r.shadow && r.lines || 0, i && t + r < i.childNodes.length && (i = i.childNodes[t + r], i = i && i.firstChild, i = i && i.firstChild, i && (i.opacity = n))
            }
        } else i = AV.support.getVendorProperty("animation")
    }(), AV.Spinner = c
})(window, document);
"undefined" == typeof AV && (AV = {});
(function(a) {
    var b = a.desktop = {};
    b.saveBlock = function() {
        return ['<div class="avpw_mode_action avpw_mode_action_right">', '<a id="avpw_save_button" href="#Save" class="avpw_button avpw_primary_button">' + AV.getLocalizedString("Save") + "</a>", '<form id="avpw_save_form" enctype="multipart/form-data" action="' + AV.build.imgrecvServer + '" method="POST" target="avpw_img_submit_target">', '<input id="avpw_save_form_posturl" type="hidden" name="posturl" value=""></input><input id="avpw_save_form_postdata" type="hidden" name="postdata" value=""></input><input id="avpw_save_form_apikey" type="hidden" name="apikey" value=""></input><input id="avpw_save_form_data" type="hidden" name="file" value=""></input><input id="avpw_save_form_sessionid" type="hidden" name="sessionid" value=""></input><input id="avpw_save_form_actionlist" type="hidden" name="actionlist" value=""></input><input id="avpw_save_form_origurl" type="hidden" name="origurl" value=""></input><input id="avpw_save_form_hiresurl" type="hidden" name="hiresurl" value=""></input><input type="hidden" name="encodedas" value="base64text"></input><input id="avpw_save_form_fileformat" type="hidden" name="fileformat" value=""></input><input id="avpw_save_form_jpgquality" type="hidden" name="jpgquality" value=""></input><input id="avpw_save_form_debug" type="hidden" name="debug" value=""></input><input id="avpw_save_form_asyncsave" type="hidden" name="asyncsave" value=""></input><input id="avpw_save_form_signature" type="hidden" name="signature" value=""></input><input id="avpw_save_form_timestamp" type="hidden" name="timestamp" value=""></input><input id="avpw_save_form_usecustomfileexpiration" type="hidden" name="usecustomfileexpiration" value=""></input></form></div>'].join("")
    };
    b.closeButton =

        function(a) {
            return ['<div id="' + a.id + '" title="' + a.text + '">', '<div id="' + a.id + '_inner" class="avpw_close_button">', '<div class="avpw_close_inner">&times;</div></div></div>'].join("")
        };
    b.cancelButton = function(a) {
        return ['<a href="#Back" id="' + a.id + '" class="avpw_button avpw_back_button">', AV.getLocalizedString(a.label), "</a>"].join("")
    };
    b.inAppPurchaseFrame = function(a) {
        return "" + ('<iframe width="100%" height="170" class="avpw_popup_frame" id="avpw_purchase_frame" name="avpw_purchase_frame" src="' + a.src + '" style="display: none;"></iframe>')
    };
    b.controls = function(a) {
        var html = '<div id="my-controls"></div>';

        return ['<div id="avpw_fullscreen_bg" style="display: none;"></div><div style="display:none;" id="avpw_controls" class="avpw avpw_main_mode">'+ html +'<div id="avpw_tool_content_wrapper"><div id="avpw_tool_main_container" class="avpw_tool_pager"><a id="avpw_lftArrow" class="avpw_prev avpw_bookend"><span class="avpw_arrow_icon"></span><div class="avpw_scroll_rule"></div></a><div id="avpw_control_main_scroll_panel" class="avpw_clip"><div id="avpw_control_main_scrolling_region" class="avpw_scroll_strip"></div><div id="avpw_tools_pager"><ul></ul></div></div><a id="avpw_rghtArrow" class="avpw_next avpw_bookend"><div class="avpw_scroll_rule"></div><span class="avpw_arrow_icon"></span></a>', a.internalSaveBlock, '</div><div id="avpw_tool_options_container" style="display:none;"><div id="avpw_tool_container"><div id="avpw_controlpanel_brightness" class="avpw_controlpanel avpw_tool_fixed avpw_tool_slider"><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Brightness"), "</h2>", b.slider({
            id: "avpw_brightness_slider",
            divider: !0
        }), '</div><div id="avpw_controlpanel_contrast" class="avpw_controlpanel avpw_tool_fixed avpw_tool_slider"><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Brightness"), "</h2>", b.slider({
            id: "avpw_contrast_slider",
            divider: !0
        }), '</div><div id="avpw_controlpanel_saturation" class="avpw_controlpanel avpw_tool_fixed avpw_tool_slider"><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Saturation"), "</h2>", b.slider({
            id: "avpw_saturation_slider",
            divider: !0
        }), '</div><div id="avpw_controlpanel_warmth" class="avpw_controlpanel avpw_tool_fixed avpw_tool_slider"><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Warmth"), "</h2>", b.slider({
            id: "avpw_warmth_slider",
            divider: !0
        }), '</div><div id="avpw_controlpanel_sharpness" class="avpw_controlpanel avpw_tool_fixed avpw_tool_slider"><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Sharpness"), "</h2>", b.slider({
            id: "avpw_sharpness_slider",
            divider: !0
        }), '</div><div id="avpw_controlpanel_orientation" class="avpw_controlpanel avpw_tool_cutout"><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Orientation"), '</h2><div class="avpw_tool_cutout_inner avpw_inset_background"><div class="avpw_tool_cutout_centered_orientation"><div class="avpw_inset_button_group avpw_inset_group avpw_span_2_buttons avpw_inset_group_first"><div id="avpw_rotate_left" class="avpw_inset_button avpw_inset_button_first"><div class="avpw_orientation_button_inner"></div></div><div id="avpw_rotate_right" class="avpw_inset_button avpw_inset_button_last"><div class="avpw_orientation_button_inner"></div></div></div><div class="avpw_inset_button_group avpw_inset_group avpw_inset_group_last avpw_span_2_buttons"><div id="avpw_flip_h" class="avpw_inset_button avpw_inset_button_first"><div class="avpw_orientation_button_inner"></div></div><div id="avpw_flip_v" class="avpw_inset_button avpw_inset_button_last"><div class="avpw_orientation_button_inner"></div></div></div><div class="avpw_inset_button_label avpw_label avpw_inset_group avpw_span_2_buttons avpw_inset_group_first">', AV.getLocalizedString("Rotate"), '</div><div class="avpw_inset_button_label avpw_label avpw_inset_group avpw_span_2_buttons avpw_inset_group_last">', AV.getLocalizedString("Mirror"), '</div></div></div></div><div id="avpw_controlpanel_crop" class="avpw_controlpanel avpw_tool_stretch avpw_tool_cutout"><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Crop"), '</h2><div class="avpw_tool_scroll"><a id="avpw_crop_presets_lftArrow" class="avpw_prev avpw_bookend"><span class="avpw_arrow_icon"></span><div class="avpw_scroll_rule"></div></a><div id="avpw_crop_presets_scroll_window" class="avpw_clip avpw_inset_background"><div id="avpw_crop_presets_scroll_region" class="avpw_scroll_strip"></div><div class="avpw_shadowbar avpw_shadowbar_n"></div></div><a id="avpw_crop_presets_rghtArrow" class="avpw_next avpw_bookend"><div class="avpw_scroll_rule"></div><span class="avpw_arrow_icon"></span></a></div></div><div id="avpw_controlpanel_resize" class="avpw_controlpanel avpw_tool_cutout"><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Resize"), '</h2><div class="avpw_tool_cutout_inner avpw_inset_background"><div class="avpw_tool_cutout_centered_resize"><div class="avpw_resize_form_block avpw_resize_width"><input class="avpw_text_input avpw_number_input" id="avpw_resize_width" type="text" value="640" maxlength="5"/><div class="avpw_label">', AV.getLocalizedString("Width"), '</div></div><div class="avpw_resize_form_block avpw_resize_constrain"><div  class="avpw_inset_group avpw_inset_button_group avpw_inset_group_first avpw_inset_group_last avpw_checkmark_button">', '<div id="avpw_constrain_prop" class="avpw_inset_button avpw_inset_button_first avpw_inset_button_last" title="' + AV.getLocalizedString("Maintain proportions") + '">', '<div class="avpw_lock_icon"><div class="avpw_lock_icon_top"><div class="avpw_lock_icon_top_inner"></div></div><div class="avpw_lock_icon_sep"></div><div class="avpw_lock_icon_bottom"></div></div></div></div></div><div class="avpw_resize_form_block avpw_resize_height"><input class="avpw_text_input avpw_number_input" id="avpw_resize_height" type="text" value="480" maxlength="5"/><div class="avpw_label">', AV.getLocalizedString("Height"), '</div></div></div></div></div><div id="avpw_controlpanel_redeye" class="avpw_controlpanel avpw_tool_stretch avpw_tool_cutout"><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Redeye"), '</h2><div class="avpw_tool_scroll"><a id="avpw_redeye_brushes_lftArrow" class="avpw_prev avpw_bookend"><span class="avpw_arrow_icon"></span><div class="avpw_scroll_rule"></div></a><div id="avpw_redeye_brushes_scroll_window" class="avpw_clip avpw_inset_background"><div id="avpw_redeye_brushes_scroll_region" class="avpw_scroll_strip"></div><div class="avpw_shadowbar avpw_shadowbar_n"></div></div><a id="avpw_redeye_brushes_rghtArrow" class="avpw_next avpw_bookend"><div class="avpw_scroll_rule"></div><span class="avpw_arrow_icon"></span></a></div></div><div id="avpw_controlpanel_whiten" class="avpw_controlpanel avpw_tool_stretch avpw_tool_cutout"><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Whiten"), '</h2><div class="avpw_tool_scroll"><a id="avpw_whiten_brushes_lftArrow" class="avpw_prev avpw_bookend"><span class="avpw_arrow_icon"></span><div class="avpw_scroll_rule"></div></a><div id="avpw_whiten_brushes_scroll_window" class="avpw_clip avpw_inset_background"><div id="avpw_whiten_brushes_scroll_region" class="avpw_scroll_strip"></div><div class="avpw_shadowbar avpw_shadowbar_n"></div></div><a id="avpw_whiten_brushes_rghtArrow" class="avpw_next avpw_bookend"><div class="avpw_scroll_rule"></div><span class="avpw_arrow_icon"></span></a></div></div><div id="avpw_controlpanel_blemish" class="avpw_controlpanel avpw_tool_stretch avpw_tool_cutout"><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Blemish"), '</h2><div class="avpw_tool_scroll"><a id="avpw_blemish_brushes_lftArrow" class="avpw_prev avpw_bookend"><span class="avpw_arrow_icon"></span><div class="avpw_scroll_rule"></div></a><div id="avpw_blemish_brushes_scroll_window" class="avpw_clip avpw_inset_background"><div id="avpw_blemish_brushes_scroll_region" class="avpw_scroll_strip"></div><div class="avpw_shadowbar avpw_shadowbar_n"></div></div><a id="avpw_blemish_brushes_rghtArrow" class="avpw_next avpw_bookend"><div class="avpw_scroll_rule"></div><span class="avpw_arrow_icon"></span></a></div></div><div id="avpw_controlpanel_effects" class="avpw_controlpanel avpw_tool_stretch avpw_tool_cutout"><div class="avpw_current_tool_icon_shadow avpw_advanced_chrome avpw_isa_basic_chrome"></div><h2 class="avpw_current_tool_icon avpw_advanced_chrome avpw_isa_basic_chrome"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Effects"), '</h2><div class="avpw_advanced_window"><div class="avpw_advanced_region"><div id="avpw_effects_effects" class="avpw_tool_scroll"><a id="avpw_filter_lftArrow" class="avpw_prev avpw_bookend"><span class="avpw_arrow_icon"></span><div class="avpw_scroll_rule"></div></a><div id="avpw_filter_body_scroll_window" class="avpw_clip avpw_inset_background"><div id="avpw_filter_pack_scroll_region" class="avpw_scroll_strip"></div><div id="avpw_filter_body_scroll_region" class="avpw_scroll_strip" style="display: none;"></div><div class="avpw_shadowbar avpw_shadowbar_n"></div></div><a id="avpw_filter_rghtArrow" class="avpw_next avpw_bookend"><div class="avpw_scroll_rule"></div><span class="avpw_arrow_icon"></span></a></div><div id="avpw_effects_options" class="avpw_tool_cutout_inner avpw_inset_background"><div class="avpw_tool_cutout_centered_fixed_size"><div class="avpw_effects_frame_container"><div  class="avpw_inset_group avpw_inset_button_group avpw_inset_button_group_first avpw_inset_button_group_last avpw_checkmark_button"><div id="avpw_effects_frame_toggle" class="avpw_inset_button avpw_inset_button_first avpw_inset_button_last"><div class="avpw_frame_toggle_icon"></div></div></div><div class="avpw_label">', AV.getLocalizedString("Frame"), '</div></div><div class="avpw_advanced_slider">', b.slider({
            id: "avpw_effects_slider"
        }), '<div class="avpw_label">', AV.getLocalizedString("Intensity"), "</div></div></div></div></div></div>", b.advancedToolsSplitter(), '</div><div id="avpw_controlpanel_enhance" class="avpw_controlpanel avpw_tool_cutout"><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Enhance"), '</h2><div class="avpw_tool_cutout_inner avpw_inset_background"><div class="avpw_tool_cutout_centered_enhance"><div class="avpw_inset_button_group avpw_inset_group avpw_inset_group_first avpw_inset_group_last avpw_span_4_buttons"><div class="avpw_inset_button avpw_inset_button_first"><div id="avpw_enhance_icon_one" class="avpw_enhance_button_inner">', AV.getLocalizedString("Enhance"), '</div></div><div class="avpw_inset_button"><div id="avpw_enhance_icon_two" class="avpw_enhance_button_inner">', AV.getLocalizedString("Night"), '</div></div><div class="avpw_inset_button"><div id="avpw_enhance_icon_three" class="avpw_enhance_button_inner">', AV.getLocalizedString("Backlit"), '</div></div><div class="avpw_inset_button avpw_inset_button_last"><div id="avpw_enhance_icon_four" class="avpw_enhance_button_inner">', AV.getLocalizedString("Balance"), '</div></div></div><div class="avpw_inset_group avpw_inset_group_first avpw_inset_group_last avpw_span_4_buttons"><div class="avpw_inset_button_label avpw_label">', AV.getLocalizedString("Enhance"), '</div><div class="avpw_inset_button_label avpw_label">', AV.getLocalizedString("Night"), '</div><div class="avpw_inset_button_label avpw_label">', AV.getLocalizedString("Backlit"), '</div><div class="avpw_inset_button_label avpw_label">', AV.getLocalizedString("Balance"), '</div></div></div></div></div><div id="avpw_controlpanel_drawing" class="avpw_controlpanel avpw_tool_stretch avpw_tool_cutout"><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Draw"), '</h2><div id="avpw_drawing_brushes" class="avpw_tool_half_height avpw_tool_double_scroll avpw_tool_scroll"><a id="avpw_drawing_brushes_lftArrow" class="avpw_prev avpw_bookend"><span class="avpw_arrow_icon"></span><div class="avpw_scroll_rule"></div></a><div id="avpw_drawing_brushes_scroll_window" class="avpw_clip avpw_inset_background"><div id="avpw_drawing_brushes_scroll_region" class="avpw_scroll_strip"></div><div class="avpw_shadowbar avpw_shadowbar_n"></div></div><a id="avpw_drawing_brushes_rghtArrow" class="avpw_next avpw_bookend"><div class="avpw_scroll_rule"></div><span class="avpw_arrow_icon"></span></a></div><div class="avpw_clip_splitter"></div><div id="avpw_drawing_colors" class="avpw_tool_half_height avpw_tool_scroll"><a id="avpw_drawing_colors_lftArrow" class="avpw_prev avpw_bookend"><span class="avpw_arrow_icon"></span></a><div id="avpw_drawing_colors_scroll_window" class="avpw_clip avpw_inset_background"><div id="avpw_drawing_colors_scroll_region" class="avpw_scroll_strip"></div><div class="avpw_shadowbar avpw_shadowbar_n"></div></div><a id="avpw_drawing_colors_rghtArrow" class="avpw_next avpw_bookend"><span class="avpw_arrow_icon"></span></a></div></div><div id="avpw_controlpanel_overlay" class="avpw_controlpanel avpw_tool_stretch avpw_tool_cutout"><div class="avpw_current_tool_icon_shadow"></div><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Stickers"), '</h2><div class="avpw_tool_scroll"><a id="avpw_overlay_lftArrow" class="avpw_prev avpw_bookend"><span class="avpw_arrow_icon"></span><div class="avpw_scroll_rule"><span class="fader"></span></div></a><div id="avpw_overlay_images_scroll_window" class="avpw_clip avpw_inset_background"><div id="avpw_overlay_pack_scroll_region" class="avpw_scroll_strip"></div><div class="avpw_overlay_roll"></div><div id="avpw_overlay_images_scroll_region" class="avpw_overlay_strip avpw_scroll_strip" style="display: none;"></div><div class="avpw_shadowbar avpw_shadowbar_n"></div></div><a id="avpw_overlay_rghtArrow" class="avpw_next avpw_bookend"><div class="avpw_scroll_rule"></div><span class="avpw_arrow_icon"></span></a></div></div><div id="avpw_controlpanel_text" class="avpw_controlpanel avpw_tool_stretch avpw_tool_cutout"><h2 class="avpw_current_tool_icon"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Text"), '</h2><div id="avpw_text_colors" class="avpw_tool_scroll avpw_tool_half_height"><a id="avpw_text_colors_lftArrow" class="avpw_prev avpw_bookend"><span class="avpw_arrow_icon"></span></a><div id="avpw_text_colors_scroll_window" class="avpw_clip avpw_inset_background"><div id="avpw_text_colors_scroll_region" class="avpw_scroll_strip"></div><div class="avpw_shadowbar avpw_shadowbar_n"></div></div><a id="avpw_text_colors_rghtArrow" class="avpw_next avpw_bookend"><span class="avpw_arrow_icon"></span></a></div><div class="avpw_tool_half_height"><button id="avpw_add_text" class="avpw_button">', AV.getLocalizedString("Add Text"), '</button><input type="text" id="avpw_text_area" name="avpw_text_area" class="avpw_text_input" /><input type="hidden" id="avpw_text_font" value="sans-serif" /><input type="hidden" id="avpw_text_font_size" value="60" /></div></div><div id="avpw_controlpanel_frames" class="avpw_controlpanel avpw_tool_stretch avpw_tool_cutout"><div class="avpw_current_tool_icon_shadow avpw_advanced_chrome avpw_isa_basic_chrome"></div><h2 class="avpw_current_tool_icon avpw_advanced_chrome avpw_isa_basic_chrome"><div class="avpw_icon_image"></div>', AV.getLocalizedString("Frames"), '</h2><div class="avpw_advanced_window"><div class="avpw_advanced_region"><div id="avpw_frames_frames" class="avpw_tool_scroll"><a id="avpw_frames_lftArrow" class="avpw_prev avpw_bookend avpw_advanced_chrome avpw_isa_basic_chrome"><span class="avpw_arrow_icon"></span><div class="avpw_scroll_rule"></div></a><div id="avpw_frames_body_scroll_window" class="avpw_clip avpw_inset_background"><div id="avpw_frames_body_scroll_region" class="avpw_scroll_strip avpw_scroll_frames_strip"></div><div class="avpw_top_rule"></div></div><a id="avpw_frames_rghtArrow" class="avpw_next avpw_bookend avpw_advanced_chrome avpw_isa_basic_chrome"><div class="avpw_scroll_rule"></div><span class="avpw_arrow_icon"></span></a></div><div id="avpw_frames_thickness" class="avpw_tool_half_height avpw_tool_advanced_half_height avpw_tool_double_scroll avpw_tool_scroll"><a id="avpw_frames_thickness_lftArrow" class="avpw_prev avpw_bookend avpw_advanced_chrome avpw_isa_advanced_chrome"><span class="avpw_arrow_icon"></span><div class="avpw_scroll_rule"></div></a><div id="avpw_frames_thickness_scroll_window" class="avpw_clip avpw_inset_background"><div id="avpw_frames_thickness_scroll_region" class="avpw_scroll_strip"></div><div class="avpw_top_rule"></div></div><a id="avpw_frames_thickness_rghtArrow" class="avpw_next avpw_bookend avpw_advanced_chrome avpw_isa_advanced_chrome"><div class="avpw_scroll_rule"></div><span class="avpw_arrow_icon"></span></a></div><div class="avpw_clip_splitter"></div><div id="avpw_frames_colors" class="avpw_tool_half_height avpw_tool_advanced_half_height avpw_tool_scroll"><a id="avpw_frames_colors_lftArrow" class="avpw_prev avpw_bookend avpw_advanced_chrome avpw_isa_advanced_chrome"><span class="avpw_arrow_icon"></span></a><div id="avpw_frames_colors_scroll_window" class="avpw_clip avpw_inset_background"><div id="avpw_frames_colors_scroll_region" class="avpw_scroll_strip"></div><div class="avpw_top_rule"></div></div><a id="avpw_frames_colors_rghtArrow" class="avpw_next avpw_bookend avpw_advanced_chrome avpw_isa_advanced_chrome"><span class="avpw_arrow_icon"></span></a></div></div></div>', b.advancedToolsSplitter(), '</div></div><div class="avpw_mode_action avpw_mode_action_left">', b.cancelButton({
            id: "avpw_all_effects",
            label: "Cancel"
        }), b.cancelButton({
            id: "avpw_up_one_level",
            label: "Cancel"
        }), '</div><div class="avpw_mode_action avpw_mode_action_right"><a href="#Apply" id="avpw_apply_container" class="avpw_button avpw_primary_button">', AV.getLocalizedString("Apply"), '</a></div></div></div><div id="avpw_canvas_embed" class="avpw_canvas_background"></div><div id="avpw_footer"><div id="avpw_history"><a id="avpw_history_undo" class="avpw_button avpw_history_button avpw_history_button_first avpw_history_disabled"><span class="avpw_history_icon">Undo</span></a><a id="avpw_history_redo" class="avpw_button avpw_history_button avpw_history_button_last avpw_history_disabled"><span class="avpw_history_icon">Redo</span></a><div id="avpw_history_undo_blocker" class="avpw_history_blocker avpw_history_blocker_first"></div><div id="avpw_history_redo_blocker" class="avpw_history_blocker avpw_history_blocker_last"></div></div><p class="avpw_footer_text" id="avpw_size_indicator"></p><p class="avpw_footer_text avpw_powered_text" style="display:none;">', '<a href="' + AV.launchData.getWidgetURL + '" target="_blank">', AV.getLocalizedString("Powered by") + " ", '<span id="avpw_logo">&nbsp;</span></a></p></div><div id="avpw_messaging" style="display: none;"><div id="avpw_messaging_inner"></div><div id="avpw_messages" style="display: none;">', b.aviaryAppPopups(), "</div></div>", '<form style="display:none;" id="avpw_track_form" action="' + AV.build.imgtrackServer + '" method="GET" target="avpw_img_track_target">', '<input id="avpw_track_form_action" type="hidden" name="action" value=""></input><input id="avpw_track_form_sessionid" type="hidden" name="sessionid" value=""></input><input id="avpw_track_form_apikey" type="hidden" name="apikey" value=""></input>', '<input id="avpw_track_form_featherversion" type="hidden" name="featherversion" value="' + AV.build.version + '"></input>', '<input id="avpw_track_form_data" type="hidden" name="toolusagedata" value=""></input></form></div>', b.closeButton({
            id: "avpw_control_cancel_pane",
            text: AV.getLocalizedString("Cancel")
        }), '<div id="avpw_img_submit_target_holder" style="position:absolute;top:0;left:0"></div><div id="avpw_img_track_target_holder" style="position:absolute;top:0;left:0"></div>', AV.buildHiddenFrame("avpw_img_submit_target_announcer", "avpw_img_submit_target_announcer", AV.build.featherTargetAnnounce)].join("")
    };
    b.eggIcon = function(a) {
        return ['<div class="avpw_icon avpw_tool_icon" id="avpw_main_' + a.optionName + '" data-header="' + a.capOptionName + '" data-toolname="' + a.optionName + '">', '<div class="avpw_icon_image avpw_tool_icon_image"><div class="avpw_tool_icon_inner"></div></div><div class="avpw_icon_waiter avpw_center_contents"></div>', '<span class="avpw_icon_label avpw_label">' + a.capOptionName + "</span>", "</div>"].join("")
    };
    b.emptyEggIcon =

        function() {
            return ""
        };
    b.genericScrollPanel = function(a) {
        return ['<div style="width: ' + a.panelWidth + 'px;" class="avpw_scroll_page ' + a.panelClass + '">\n', '<span class="avpw_scroll_page_inner">\n', a.panelHTML, "</span>\n</div>\n"].join("")
    };
    b.aviaryScrollPanel = function(a) {
        return '';
        return ['<div style="width: ' + a.panelWidth + 'px;display:none;" class="avpw_scroll_page ' + a.panelClass + '">\n', '<span class="avpw_scroll_page_inner">\n<div class="avpw_info">', '<a href="' + AV.launchData.getWidgetURL + '" target="_blank" class="avpw_button avpw_info_right">' + AV.getLocalizedString("Get this editor") + "</a>\n", '<a href="' + AV.launchData.giveFeedbackURL + '" target="_blank" class="avpw_button avpw_info_left">' + AV.getLocalizedString("Give feedback") + "</a>\n", '<div class="avpw_info_inner"><a id="avpw_logo_large" href="http://www.aviary.com/" target="_blank">Aviary</a><p class="avpw_version_text">', "v" + AV.build.version, "</p></div></div></span></div>"].join("")
    };
    b.aviaryAppPopups = function() {
        return ['<div id="avpw_aviary_beensaved" class="avpw_app_popup">\n', '<p class="avpw_message_text">' + AV.getLocalizedString("Your work was saved!") + "</p>", '<div class="avpw_message_buttons">\n', '<a id="avpw_resume_aftersave" class="avpw_button" href="#Resume">' + AV.getLocalizedString("Resume") + "</a>\n", '<a id="avpw_close_aftersave" class="avpw_button avpw_primary_button" href="#Close">' + AV.getLocalizedString("Close") + "</a>", '</div>\n<br /><div class="avpw_center_contents">\n', '<a class="avpw_button" style="display:none;" href="' + AV.launchData.getWidgetURL + '" target="_blank">' + AV.getLocalizedString("Get this editor") + "</a>\n", '</div>\n</div>\n<div id="avpw_resize_invalid" class="avpw_app_popup">\n<p class="avpw_message_text">', AV.getLocalizedString("Width and height must be greater than zero and less than the maximum({max}px)").replace("{max}", '<span id="avpw_resize_invalid_max_size"></span>'), '</p><div class="avpw_message_buttons">\n', '<a id="avpw_resize_invalid_confirm" class="avpw_button" href="#Confirm">' + AV.getLocalizedString("OK") + "</a>\n", '</div>\n</div>\n<div id="avpw_purchase_pack" class="avpw_app_popup">\n', b.closeButton({
            id: "avpw_purchase_pack_close",
            text: AV.getLocalizedString("Cancel")
        }), '<div id="avpw_purchase_pack_contents">\n</div>\n</div>\n<div id="avpw_resize_unlocked" class="avpw_app_popup">\n<p class="avpw_message_text">', AV.getLocalizedString("Are you sure? This can distort your image"), '</p><div class="avpw_message_buttons">\n', '<a id="avpw_resize_unlocked_confirm" class="avpw_button" href="#Confirm">' + AV.getLocalizedString("OK") + "</a>\n", '<a id="avpw_resize_unlocked_cancel" class="avpw_button" href="#Confirm">' + AV.getLocalizedString("Cancel") + "</a>\n", '</div>\n</div>\n<div id="avpw_aviary_quitareyousure" class="avpw_app_popup">', '<p class="avpw_message_text">' + AV.getLocalizedString("Wait! You didn't save your work. Are you certain that you want to close this editor?") + "</p>", '<div class="avpw_message_buttons avpw_center_contents">\n', '<a id="avpw_resume_editing" class="avpw_button" href="#Resume">' + AV.getLocalizedString("Resume") + "</a>\n", '<a id="avpw_close_nosave" class="avpw_button" href="#Close">' + AV.getLocalizedString("Close") + "</a>\n", '<a id="avpw_close_save" class="avpw_button avpw_primary_button" href="#Save">' + AV.getLocalizedString("Save") + "</a>", '</div></div><div id="avpw_aviary_unsupported" class="avpw_app_popup"><p class="avpw_message_text">', AV.getLocalizedString("Please install {Adobe Flash Player} (version {min} or higher), or use a supported browser: {Chrome}, {Firefox}, {Safari}, {Opera}, or {Internet Explorer} (version 9 or higher).").replace("{Adobe Flash Player}", '<a href="http://get.adobe.com/flashplayer/" target="_blank">Adobe Flash Player</a>').replace("{min}", AV.build.MINIMUM_FLASH_PLAYER_VERSION).replace("{Chrome}", '<a href="http://www.google.com/chrome/" target="_blank">Chrome</a>').replace("{Firefox}", '<a href="http://www.mozilla.org/firefox/" target="_blank">Firefox</a>').replace("{Safari}", '<a href="http://www.apple.com/safari/" target="_blank">Safari</a>').replace("{Opera}", '<a href="http://www.opera.com/" target="_blank">Opera</a>').replace("{Internet Explorer}", '<a href="http://www.beautyoftheweb.com/" target="_blank">Internet Explorer</a>'), '</p><div class="avpw_message_buttons avpw_center_contents">\n', '<a id="avpw_close_unsupported" class="avpw_button" href="#Close">' + AV.getLocalizedString("Close") + "</a>\n", "</div></div>"].join("")
    };
    b.scrollPanelPip = function(a) {
        return '<li class="avpw_page avpw_is_navpip" pagenum="' + a.i + '" id="avpw_navpip_' + a.i + '">&bull;</li>\n'
    };
    b.slider = function(a) {
        return ['<div class="avpw_slider_container avpw_isa_slider_container"><div class="avpw_slider_bookend avpw_slider_bookend_left avpw_slider_label">-</div>', '<div class="avpw_slider_positioned" id="' + a.id + '">', '<div class="avpw_slider_positioned_inner"><div class="avpw_slider_bounds" ><div class="avpw_slider_goo"></div>', a.divider ? '<div class="avpw_slider_divider"></div>' : "", '<a class="avpw_slider_handle"></a></div></div></div><div class="avpw_slider_bookend avpw_slider_bookend_right avpw_slider_label">+</div></div>'].join("")
    };
    b.zoomModeOverlay = function() {
        return ['<div id="avpw_zoom_mode_overlay"><div id="avpw_zoom_mode_text"><h2 class="avpw_mode_warning">', AV.getLocalizedString("Zoom Mode"), "</h2>", AV.getLocalizedString("Click to release"), "</div></div>"].join("")
    };
    b.zoomControls = function() {
        return '<div id="avpw_zoom_container"><div id="avpw_zoom_button"><a href="#zoom" id="avpw_zoom_icon"></a></div><div class="avpw_zoom_slider_container avpw_isa_slider_container"><div id="avpw_zoom_slider"><a id="avpw_zoom_handle"></a></div></div></div><div class="avpw_shadowbar avpw_shadowbar_n"></div><div class="avpw_shadowbar avpw_shadowbar_s"></div><div class="avpw_shadowbar avpw_shadowbar_e"></div><div class="avpw_shadowbar avpw_shadowbar_w"></div>'
    };
    b.presetIndicator = function() {
        return '<div class="avpw_preset_indicator_outer"><div class="avpw_preset_indicator"></div></div>'
    };
    b.cropPreset = function(a) {
        return ['<div class="avpw_preset_crop_icon avpw_preset_icon avpw_icon avpw_with_indicator avpw_isa_preset_crop" data-crop="' + a.label + '">', a.strict || a.labeled ? "" : b.cropPresetFlippedIndicator(), '<div class="avpw_preset_label avpw_label">', a.label, "</div>", b.presetIndicator(), "</div>"].join("")
    };
    b.cropPresetFlippedIndicator = function() {
        return '<div class="avpw_crop_preset_flipped_indicator_shadow"><div class="avpw_crop_preset_flipped_indicator_left"></div><div class="avpw_crop_preset_flipped_indicator_right"></div></div><div class="avpw_crop_preset_flipped_indicator"><div class="avpw_crop_preset_flipped_indicator_left"></div><div class="avpw_crop_preset_flipped_indicator_right"></div></div>'
    };
    b.brushIconLarge = function(a) {
        return ['<div class="avpw_preset_icon avpw_icon avpw_with_indicator avpw_isa_preset_brush" data-size="' + a.size + '">', '<div class="avpw_icon_image avpw_brush_size avpw_brush_size_' + a.size + '">', "</div>", b.presetIndicator(), "</div>"].join("")
    };
    b.brushIconSmall = function(a) {
        return ['<div class="avpw_preset_icon avpw_icon avpw_isa_preset_brush" data-size="' + a.size + '">', '<div class="avpw_icon_image avpw_brush_size avpw_brush_size_' + a.size + '">', "</div></div>"].join("")
    };
    b.brushColorIcon =

        function(a) {
            return ['<div class="avpw_preset_icon avpw_icon avpw_isa_preset_color" data-color="' + a.color + '">', '<div class="avpw_preset_color_image" style="background: ' + a.color + ';">', "</div>", a.extra ? a.extra : "", "</div>"].join("")
        };
    b.magicColorIcon = function() {
        return '<div class="avpw_glow_accent"></div><div class="avpw_star_accent"></div>'
    };
    b.colorPickerIcon = function() {
        return '<div class="avpw_preset_icon avpw_icon avpw_isa_color_picker" data-color=""><div class="avpw_preset_color_image avpw_custom_color_image avpw_isa_color_feedback"></div></div>'
    };
    b.colorPickerContainer = function() {
        return ['<div class="avpw_color_picker_container"><div class="avpw_color_picker_tail"></div><div class="avpw_color_picker_background"></div><div class="avpw_color_picker_preview avpw_isa_color_feedback"></div><a class="avpw_button avpw_color_picker_confirm">', AV.getLocalizedString("Set Color"), "</a></div>"].join("")
    };
    b.eraserIcon = function() {
        return ['<div class="avpw_eraser_icon avpw_preset_icon avpw_icon"><div class="avpw_icon_image">', AV.getLocalizedString("Eraser"), "</div></div>"].join("")
    };
    b.brushPreviewOverlay = function() {
        return '<div id="avpw_brush_preview_container" class="avpw_brush_preview"><canvas height="100" width="100" class="avpw_brush_preview_display"></canvas></div>'
    };
    b.frameThicknessIcon = function(a) {
        var b = 24 - 2 * a.thickness + "px";
        return ['<div class="avpw_preset_icon avpw_icon avpw_isa_preset_thickness" data-thickness="' + a.thickness + '">', '<div class="avpw_preset_thickness_image" ', 'style="border-width: ' + (a.thickness + "px") + ";", "width: " + b + ";", "height: " + b + ';"', "></div></div>"].join("")
    };
    b.blankPreset = function() {
        return '<div class="avpw_preset_icon avpw_icon avpw_preset_icon_disabled"></div>'
    };
    b.blankCropPreset = function() {
        return '<div class="avpw_preset_crop_icon avpw_preset_icon avpw_icon avpw_preset_icon_disabled"></div>'
    };
    b.stickerThumbnail = function(a) {
        return ['<div class="avpw_icon avpw_overlay_icon avpw_isa_control_selector_overlay" thumburl="' + a.thumburl + '" fullimageurl="' + a.url + '">', '<div class="avpw_icon_image avpw_overlay_image avpw_header_inline_center">', '<img draggable="false" src="' + a.thumburl + '" fullimageurl="' + a.url + '"></img>', '</div><div class="avpw_icon_waiter avpw_center_contents avpw_isa_overlay_waiter"></div></div>'].join("")
    };
    b.stickerRollInner = function(a) {
        return ['<img draggable="false" height="60" width="60" class="avpw_stickerpack_image" ', 'src="' + a.thumburl + '"></img>', '<span class="avpw_icon_label avpw_label">' + a.label + "</span>"].join("")
    };
    b.stickerRoll = function(a) {
        return ['<div class="avpw_icon avpw_pack_icon avpw_pack_icon_selected avpw_stickerpack_icon avpw_isa_control_selector_stickerpack" data-pack="' + a.id + '">', b.stickerRollInner(a), "</div>"].join("")
    };
    b.stickerRollDisabled = function(a) {
        return ['<div class="avpw_icon avpw_pack_icon avpw_pack_icon_selected avpw_stickerpack_icon avpw_isa_control_selector_stickerinfo" data-pack="' + a.id + '">', b.stickerRollInner(a), '<div class="avpw_icon_waiter avpw_center_contents"></div></div>'].join("")
    };
    b.stickerLeadIn = function() {
        return '<div class="avpw_overlay_lead"><div class="avpw_overlay_lead_inner"></div></div>'
    };
    b.filterThumbnail = function(a) {
        return ['<div class="avpw_icon avpw_filter_icon avpw_isa_control_selector_filter" data-frame="' + a.frame + '" data-filtername="' + a.id + '">', b.presetIndicator(), '<img draggable="false" height="55" width="55" class="avpw_filter_icon_image" ', 'src="' + a.thumburl + '"></img>', '<span class="avpw_icon_label avpw_label">' + a.label + "</span>", '<div class="avpw_icon_waiter avpw_center_contents"></div></div>'].join("")
    };
    b.filterCanisterInner = function(a) {
        return ['<img draggable="false" height="75" width="54" class="avpw_filterpack_image" ', 'src="' + a.thumburl + '"></img>', '<span class="avpw_icon_label avpw_label">' + a.label + "</span>"].join("")
    };
    b.filterCanister = function(a) {
        return ['<div class="avpw_icon avpw_pack_icon avpw_pack_icon_selected avpw_filterpack_icon avpw_isa_control_selector_filterpack" data-pack="' + a.id + '">', b.filterCanisterInner(a), "</div>"].join("")
    };
    b.filterCanisterDisabled = function(a) {
        return ['<div class="avpw_icon avpw_pack_icon avpw_pack_icon_selected avpw_filterpack_icon avpw_isa_control_selector_filterinfo" data-pack="' + a.id + '">', b.filterCanisterInner(a), '<img draggable="false" height="75" width="54" class="avpw_filterpack_tag_image" src="' + a.tagurl + '"></img>', '<div class="avpw_icon_waiter avpw_center_contents"></div></div>'].join("")
    };
    b.frameThumbnail = function(a) {
        return ['<div class="avpw_filter_icon avpw_icon avpw_isa_control_selector_frame" data-framename="' + (a.id ? a.id : "") + '">', b.presetIndicator(), '<canvas height="55" width="55" class="avpw_filter_icon_image" style="background-image: url(\'' + a.thumburl + "')\"></canvas>", '<span class="avpw_icon_label avpw_label">' + a.label + "</span>", "</div>"].join("")
    };
    b.straightenSlider = function() {
        return '<div class="avpw_straighten_ui avpw_straighten_ui_animate" id="avpw_straighten_container"><div class="avpw_straighten_ui_grid avpw_straighten_ui_grid_animate" id="avpw_straighten_grid"></div><a id="avpw_straighten_handle"></a></div>'
    };
    b.flashCanvasBox = function(a) {
        return ['<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td width="100%" height="100%" valign="middle" align="center">', '<div id="' + a.id + '"></div>', "</td></tr></table>"].join("")
    };
    b.advancedToolsSplitter = function() {
        return '<div class="avpw_advanced_splitter"><div class="avpw_advanced_splitter_inner"><div class="avpw_advanced_splitter_control"><div class="avpw_advanced_splitter_bottom_shadow"></div><div class="avpw_advanced_splitter_bottom"></div><div class="avpw_advanced_splitter_middle"><a class="avpw_advanced_splitter_up_arrow"></a><a class="avpw_advanced_splitter_down_arrow"></a></div></div></div></div>'
    }
})(AV.template =
    AV.template || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.android = {
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "sticker_pack_updated_3",
        Store: "menu_premium",
        Loading: "effet_loading_title",
        "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!": "feather_crash_toast_text",
        "Saving...": "feather_save_progress",
        Confirm: "confirm",
        Singe: "feather_plugin_filter_singe_name",
        "San Carmen": "feather_plugin_filter_sancarmen_name",
        Error: "generic_error_title",
        Stickers: "stickers",
        Aviary: "tool_name",
        Custom: "custom",
        "Sorry, there was an error loading the effect pack": "feather_effects_error_loading_pack",
        "Get More": "get_more",
        Rotate: "rotate",
        "Loading Image...": "loading_image",
        "Soft Focus": "feather_plugin_filter_softfocus_name",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "revert_dialog_message",
        "Oops, there was an error while saving the image.": "feather_error_saving_image",
        Contrast: "contrast",
        Vivid: "feather_plugin_filter_vivid_name",
        Meme: "meme",
        "Learn more!": "infoscreen_submit",
        Draw: "draw",
        Indiglow: "feather_plugin_filter_indiglow_name",
        "Applying effects": "effect_loading_message",
        "Aviary Editor": "app_name",
        Auto: "auto_enhance_label",
        "A filter pack has been updated. Click ok to reload the packs list.": "filter_pack_updated",
        Attention: "attention",
        Update: "feather_update",
        Redeye: "red_eye",
        "There was an error downloading the image, please try again later.": "error_download_image_message",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "confirm_quit_message",
        "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?": "feather_error_saving_aviary_folder",
        "Zoom Mode": "zoom_mode",
        "A sticker pack has been updated. We need to reload the current panel.": "sticker_pack_updated_2",
        Backlit: "back_enhance_label",
        Laguna: "feather_plugin_filter_edgewood_name",
        "Image saved in %1$s. Do you want to see the saved image?": "feather_image_saved_in",
        Effects: "effects",
        Reset: "menu_reset",
        Blemish: "blemish",
        "Are you sure you want to discard changes from this tool?": "tool_leave_question",
        Brightness: "brightness",
        "Enter text here": "enter_text_here",
        Aqua: "feather_plugin_filter_aqua_name",
        "Keep editing": "keep_editing",
        Saturation: "saturation",
        Remove: "remove",
        Concorde: "feather_plugin_filter_thresh_name",
        "Sorry, you must update the effect pack to continue.": "feather_effects_error_update_pack",
        "About this editor": "infoscreen_bottom_button",
        "View Image": "feather_view_file",
        "Are you sure you want to remove this sticker?": "sticker_delete_message",
        "A sticker pack has been updated. Click ok to reload the packs list.": "sticker_pack_updated_1",
        "Revert to original?": "revert_dialog_title",
        Apply: "apply",
        Enhance: "enhance",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "infoscreen_text",
        Balance: "balance_enhance_label",
        Original: "feather_plugin_filter_undefined_name",
        Text: "text",
        Strato: "feather_plugin_filter_andy_name",
        Night: "night_enhance_label",
        Square: "square",
        Orientation: "adjust",
        "Unknown error": "feather_effects_unknown_error",
        "Sorry, you must update the Aviary editor to use these effects.": "feather_effects_error_update_editor",
        Demo: "sticker_name",
        "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?": "feather_activity_not_found",
        Sharpness: "sharpen",
        Ventura: "feather_plugin_filter_joecool_name",
        Crop: "crop",
        "Edit Top Text": "edit_top_text",
        Done: "save",
        Whiten: "whiten",
        Mirror: "mirror",
        Cancel: "menu_cancel",
        Close: "feather_close",
        "Edit Bottom Text": "edit_bottom_text",
        "Photo Editor": "edit_your_photo",
        "Leave editor": "yes_leave",
        Tool: "tool"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.ca = {
        Nostalgia: "Nost\u00e0lgia",
        Width: "Amplada",
        Blur: "Difuminar",
        "Your image was cropped.": "La seva imatge ha sigut retallada",
        Sharpen: "Afinar",
        "There is another image editing window open.  Close it without saving and continue?": "Hi ha una altra imatge amb l'editor obert. Vol tancarla sense guardar els canvis i continuar?",
        Resume: "Resum",
        Heatwave: "Ona de calor",
        "Maintain proportions": "Mantenir proporcions",
        Update: "Actualitzar",
        Free: "Lliure",
        Reset: "Reajustar",
        Blemish: "Taca",
        Bulge: "Eixamplar",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary \u00e9s un SDK gratu\u00eft disponible per iOS i Android que et permet afegir capacitats d'edici\u00f3 de fotos i efectes a la teva aplicaci\u00f3 amb nom\u00e9s dues l\u00ednies de codi.",
        Greeneye: "Ulls verds",
        Shadow: "Ombra",
        OK: "D'acord",
        Intensity: "Intensitat",
        Whiten: "Blanquejar",
        Frames: "Marc",
        "Delete selected": "Eliminar seleccionats",
        "Always Sunny": "Solejat",
        Negative: "Negatiu",
        Send: "Enviar",
        "Keep editing": "Continuar editant",
        "Powered by Aviary.com": "Desenvolupat per Aviary.com",
        Zoom: "Zoom",
        Retro: "Retro",
        Save: "Guardar",
        "Are you sure?": "Est\u00e0 segur?",
        Warmth: "Calor",
        More: "M\u00e9s",
        Meme: "Meme",
        Grunge: "Grunge",
        "Applying effects": "Aplicant efectes",
        Auto: "Autom\u00e0tic",
        Tool: "Eina",
        Daydream: "Somni",
        Cinematic: "Cinem\u00e0tica",
        Brightness: "Lluentor",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "Esperi! No ha guardat els canvis. Est\u00e0 segur que desitja tancar aquest navegador?",
        Smooth: "Alise",
        Draw: "Dibuix",
        Flip: "Voltejar",
        Cancel: "Cancellar",
        "Your work was saved!": "El seu treball s'ha guardat!",
        Delete: "Esborrar",
        "Preset Sizes": "Mida predefinida",
        Back: "Tornar",
        "Brush softness": "Suavitat del pinzell",
        Brush: "Raspall",
        "Edit Bottom Text": "Editar text inferior",
        "Toy Camera": "C\u00e0mera Toy",
        Exit: "Sortida",
        Undo: "Desfer",
        Borders: "Fronteres",
        Contrast: "Contrast",
        "Instant!": "Instant\u00e0nia",
        "Choose Color": "Triar color",
        "Hard Brushes": "Pinzellada forta",
        "Brush size": "Mida del pinzell",
        "Color Matrix": "Matriu de colors",
        Pinch: "Contreure",
        Indiglow: "Indiglow",
        Original: "Original",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "La seva imatge ha sigut redu\u00efda temporalment perqu\u00e8 sigui m\u00e9s f\u00e0cil d'editar. Quan pitji Guardar, es guardar\u00e0 en el tamany complet.",
        "Add Text": "Afegir text",
        Classic: "Cl\u00e0ssic",
        Text: "Text",
        "No stickers defined in Feather_Stickers.": "No hi ha etiquetes definides en Feather_Stickers",
        "Drag corners to resize crop area": "Arrossegui els cantons per redimensionar l'\u00e0rea del rectangle",
        "Give feedback": "Observacions",
        Height: "Al\u00e7ada",
        Colors: "Colors",
        Done: "Fet",
        "Soft Brushes": "Pinzellada suau",
        Close: "Tancar",
        Redo: "Refer",
        Size: "Mida",
        "e-mail address": "Correu electr\u00f2nic",
        Eraser: "Goma",
        Min: "Min",
        Fade: "Fos",
        Saturation: "Saturaci\u00f3",
        "Crop again": "Torni a provar",
        Power: "Pot\u00e8ncia",
        Max: "Max",
        Redeye: "Ulls vermells",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "Est\u00e0 a punt de perdre els canvis que ha realitzat amb aquesta eina. Est\u00e0 segur que desitja sortir?",
        Corners: "Cantonades",
        "Old Photo": "Foto antiga",
        Resize: "Reduir",
        "Color Grading": "Correcci\u00f3 de color",
        Rotate: "Girar",
        "Tool Selection": "Eina de selecci\u00f3",
        "Enter text here": "Introdueixi text aqu\u00ed",
        "Code Red": "Codi vermell",
        "Interested? We'll send you some info.": "Interessat? T'enviarem informaci\u00f3.",
        "Vignette Blur": "Vinyeta amb desenfoc",
        "Film Grain": "Granulat",
        Color: "Color",
        Stickers: "Adhesius",
        Crop: "Retallar",
        "Edit Top Text": "Editar text superior",
        Apply: "Aplicar",
        Tools: "Utilitats"
    }
})(AV.lang =
    AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.cs = {
        Loading: "Nahr\u00e1v\u00e1m",
        "Sorry, you must update the Aviary editor to use these effects.": "Omlouv\u00e1m se, ale mus\u00edte aktualizovat editor Aviary, abyste mohl(a) pou\u017e\u00edt tyto efekty.",
        "Toy Camera": "Plastov\u00e1 kamera",
        Night: "Noc",
        Nostalgia: "Nostalgie",
        Aviary: "Aviary",
        "Mark II": "Verze dva",
        Width: "\u0160\u00ed\u0159ka",
        "No effects found for this pack.": "V tomto bal\u00ed\u010dku nebyly nalezeny \u017e\u00e1dn\u00e9 efekty.",
        Blur: "Rozmaz\u00e1n\u00ed",
        "Your image was cropped.": "V\u00e1\u0161 obr\u00e1zek byl o\u0159\u00edznut.",
        Sharpen: "Ost\u0159en\u00ed",
        "Learn more!": "Zjistit v\u00edce!",
        Indiglow: "Sv\u00edt\u00edc\u00ed tachometr",
        "There is another image editing window open.  Close it without saving and continue?": "M\u00e1te otev\u0159eno jin\u00e9 okno s editac\u00ed obr\u00e1zku. Chcete ho bez ulo\u017een\u00ed zav\u0159\u00edt a pokra\u010dovat?",
        Resume: "Pokra\u010dovat",
        Heatwave: "Horko",
        "A filter pack has been updated. Click ok to reload the packs list.": "Bal\u00ed\u010dek filtr\u016f byl aktualizov\u00e1n. Klikn\u011bte na OK pro znovuna\u010dten\u00ed bal\u00ed\u010dku.",
        Update: "Aktualizovat",
        Free: "Voln\u00fd",
        "There was an error downloading the image, please try again later.": "Do\u0161lo k chyb\u011b p\u0159i stahov\u00e1n\u00ed obr\u00e1zku. Zkuste to pros\u00edm znovu.",
        Effects: "Efekty",
        "Sorry, there's no application on your phone to handle this action.": "Omlouv\u00e1m se, ale na Va\u0161em za\u0159\u00edzen\u00ed nen\u00ed aplikace, kter\u00e1 toto um\u00ed.",
        Vogue: "M\u00f3dn\u00ed",
        Tools: "N\u00e1stroje",
        "Don't ask me again": "Neptat se znovu",
        Reset: "Resetovat",
        "File saved": "Soubor ulo\u017een",
        Blemish: "Vady a kazy",
        Chrono: "Chrono",
        Bulge: "Vyboulit",
        Alice: "Alice",
        "Destination folder": "C\u00edlov\u00e1 slo\u017eka",
        "Sorry, you must update the effect pack to continue.": "Omlouv\u00e1m se, ale pro pokra\u010dov\u00e1n\u00ed mus\u00edte aktualizovat bal\u00ed\u010dek efekt\u016f.",
        "Original size": "P\u016fvodn\u00ed velikost",
        "Are you sure you want to remove this sticker?": "Opravdu chcete odstranit tuto samolepku?",
        "Revert to original?": "Vr\u00e1tit do p\u016fvodn\u00edho stavu?",
        Mohawk: "Mohawk",
        Enhance: "Vylep\u0161it",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary je voln\u011b dostupn\u00fd SDK pro iOS a Android, kter\u00fd v\u00e1m umo\u017en\u00ed d\u00edky n\u011bkolika \u0159\u00e1dk\u016fm k\u00f3du p\u0159idat do sv\u00fdch aplikac\u00ed mo\u017enost editace fotografi\u00ed a p\u0159id\u00e1v\u00e1n\u00ed efekt\u016f.",
        Greeneye: "Zelen\u00e9 o\u010di",
        Shadow: "St\u00edn",
        "Unknown error": "Nezn\u00e1m\u00e1 chyba!",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "T\u00edmto ztrat\u00edte v\u0161echny zm\u011bny, kter\u00e9 jste provedli. V\u00e1\u017en\u011b se chcete vr\u00e1tit k p\u016fvodn\u00edmu obr\u00e1zku?",
        OK: "OK",
        "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?": "Omlouv\u00e1m se, ale na Va\u0161em za\u0159\u00edzen\u00ed nen\u00ed aplikace, kter\u00e1 toto um\u00ed. Chcete j\u00ed nyn\u00ed st\u00e1hnout z marketu?",
        Intensity: "Intenzita",
        Whiten: "Vyb\u011blit",
        Frames: "R\u00e1my",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "P\u0159idejte sv\u00fdm fotografi\u00edm zrno a st\u00e1\u0159\u00ed d\u00edky t\u011bmto \u0161esti efekt\u016fm.",
        "Delete selected": "Smazat vybran\u00e9",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "Bal\u00ed\u010dek samolepek byl aktualizov\u00e1n. Je pot\u0159eba znovu na\u010d\u00edst aktu\u00e1ln\u00ed panel. Chcete aktu\u00e1ln\u00ed samolepku ulo\u017eit?",
        "Always Sunny": "Slun\u00ed\u010dko",
        Confirm: "Potvrdit",
        Siesta: "Odpo\u010dinek",
        Reflex: "Odraz",
        Negative: "Negativ",
        Send: "Odeslat",
        "Keep editing": "Pokra\u010dovat v \u00faprav\u00e1ch",
        "Powered by Aviary.com": "Powered by Aviary.com",
        Zoom: "Zoom",
        "Sorry, there's no file manager installed on your phone to handle this action. Do you want to download one now from the market?": "Omlouv\u00e1m se, ale na va\u0161em za\u0159\u00edzen\u00ed nen\u00ed \u017e\u00e1dn\u00fd mana\u017eer soubor\u016f. Chcete ho nyn\u00ed st\u00e1hnout z marketu?",
        Editor: "Editor",
        "Biggest size": "Nejv\u011bt\u0161\u00ed velikost",
        "Soft Focus": "Lehce zaost\u0159it",
        Save: "Ulo\u017eit",
        "Are you sure?": "Jste si jisti?",
        Warmth: "Teplo",
        More: "V\u00edce",
        Meme: "Meme",
        Malibu: "Malibu",
        Grunge: "\u0160p\u00edna",
        "Tool Selection": "V\u00fdb\u011br n\u00e1stroje",
        Auto: "Auto",
        Tool: "N\u00e1stroj",
        Settings: "Nastaven\u00ed",
        Eddie: "Eddie",
        Cinematic: "Kino",
        "Medium size": "St\u0159edn\u00ed velikost",
        Store: "Obchod",
        Backlit: "Podsv\u00edcen\u00ed",
        "Are you sure you want to discard changes from this tool?": "Opravdu chcete zru\u0161it zm\u011bny proveden\u00e9 t\u00edmto n\u00e1strojem?",
        Brightness: "Jas",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "Po\u010dkat! Neulo\u017eili jste svou pr\u00e1ci. Opravdu chcete editor zav\u0159\u00edt?",
        Smooth: "Hladk\u00e9",
        "Get this editor": "St\u00e1hnout aplikaci",
        Draw: "Kreslit",
        Flip: "Obr\u00e1tit",
        "Soft Brushes": "Jemn\u00e9 \u0161t\u011btce",
        "View Image": "Uk\u00e1zat obr\u00e1zek",
        Viewfinder: "Hled\u00e1\u010dek",
        "Your work was saved!": "Va\u0161e pr\u00e1ce byla ulo\u017eena!",
        "Small size": "Mal\u00e1 velikost",
        Delete: "Smazat",
        Square: "\u010ctverec",
        Redo: "Znovu prov\u00e9st",
        "Preset Sizes": "P\u0159ednastaven\u00e9 velikosti",
        Sharpness: "Ostrost",
        Back: "Zp\u011bt",
        "Brush softness": "M\u011bkkost \u0161t\u011btce",
        Periscope: "Periskop",
        Brush: "\u0160t\u011btec",
        Mirror: "Zrcadlo",
        "Edit Bottom Text": "Upravit spodn\u00ed text",
        "Photo Editor": "Editor fotografi\u00ed",
        "Maintain proportions": "Zachovat proporce",
        Vivid: "Barvi\u010dky",
        "San Carmen": "San Carmen",
        Retro: "Retro",
        "Sorry, there was an error loading the effect pack": "Omlouv\u00e1m se, ale do\u0161lo k chyb\u011b p\u0159i na\u010d\u00edt\u00e1n\u00ed bal\u00ed\u010dku efekt\u016f",
        Exit: "V\u00fdstup",
        Undo: "Zp\u011bt",
        "Loading Image...": "Nahr\u00e1v\u00e1m obr\u00e1zek",
        Borders: "Hranice",
        Contrast: "Kontrast",
        "Saving...": "Ukl\u00e1d\u00e1m",
        "Instant!": "Hned1",
        "Choose Color": "Vybrat barvu",
        Strato: "Strato",
        "Zoom Mode": "M\u00f3d zoomu",
        "A sticker pack has been updated. We need to reload the current panel.": "Bal\u00ed\u010dek samolepek byl aktualizov\u00e1n. Je pot\u0159eba znovu na\u010d\u00edst aktu\u00e1ln\u00ed panel.",
        Vigilante: "Vigilante",
        "Image saved in %1$s. Do you want to see the saved image?": "Obr\u00e1zek ulo\u017een do %1$s. P\u0159ejete si vid\u011bt v\u00fdsledn\u00fd obr\u00e1zek?",
        "Hard Brushes": "Hrub\u00e9 \u0161t\u011btce",
        "Brush size": "Velikost \u0161t\u011btce",
        "Get More": "V\u00edce",
        "Color Matrix": "Barevn\u00e1 matice",
        Corners: "Rohy",
        Aqua: "Voda",
        "Output Image Size": "V\u00fdstupn\u00ed velikost",
        Ventura: "Ventura",
        Error: "Chyba",
        "You can change this property in the Settings panel.": "Toto lze zm\u011bnit v menu Nastaven\u00ed.",
        Kurt: "Kurt",
        Balance: "Vyv\u00e1\u017een\u00ed",
        Original: "P\u016fvodn\u00ed",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "V\u00e1\u0161 obr\u00e1zek byl do\u010dasn\u011b zm\u011bn\u0161en, aby se v\u00e1m l\u00e9pe upravoval. A\u017e ho ulo\u017e\u00edte, bude zp\u011bt v pln\u00e9 velikosti.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "Zavzpom\u00ednejte na kr\u00e1sn\u00e9 z\u00e1\u017eitky d\u00edky na\u0161im \u0161esti nostalgick\u00fdm efekt\u016fm.",
        "Oops, there was an error while saving the image.": "Jejda, do\u0161lo k chyb\u011b p\u0159i ukl\u00e1d\u00e1n\u00ed obr\u00e1zku!",
        Orientation: "Orientace",
        "Add Text": "P\u0159idat text",
        Classic: "Klasick\u00fd",
        "24ZX": "24ZX",
        Text: "Text",
        "No stickers defined in Feather_Stickers.": "Pro Feather_Stickers nebyly definov\u00e1ny \u017e\u00e1dn\u00e9 samolepky.",
        "Drag corners to resize crop area": "Pro zm\u011bnu o\u0159ezu pros\u00edm posu\u0148te okraje",
        "Give feedback": "Ohodnotit aplikaci",
        "Get this pack!": "St\u00e1hnout bal\u00ed\u010dek!",
        Height: "V\u00fd\u0161ka",
        Colors: "Barvy",
        Done: "Hotovo",
        Fixie: "Fixie",
        Covert: "Tajn\u00fd",
        Cancel: "Zru\u0161it",
        Close: "Zav\u0159\u00edt",
        "Width and height must be greater than zero and less than the maximum({max}px)": "\u0160\u00ed\u0159ka a v\u00fd\u0161ka mus\u00ed b\u00fdt vy\u0161\u0161\u00ed ne\u017e nula a ni\u017e\u0161\u00ed ne\u017e {max} px.",
        "Leave editor": "Opustit editor",
        Size: "Velikost",
        "e-mail address": "Emailov\u00e1 adresa",
        "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!": "Jejda! Spadl jsem, ale informace o tom, co je \u0161patn\u011b, jsem poslal autor\u016fm, kte\u0159\u00ed m\u011b oprav\u00ed!",
        Fade: "Sl\u00e1bnout",
        Min: "Min",
        Cherry: "T\u0159e\u0161e\u0148",
        "Are you sure? This can distort your image": "Skute\u010dn\u011b chcete prov\u00e9st po\u017eadovanou akci? M\u016f\u017ee to v\u00e9st ke zkreslen\u00ed obr\u00e1zku.",
        "A sticker pack has been updated. Click ok to reload the packs list.": "Bal\u00ed\u010dek samolepek byl aktualizov\u00e1n. Klikn\u011bte na OK pro znovuna\u010dten\u00ed bal\u00ed\u010dku.",
        Custom: "Vlastn\u00ed",
        Eraser: "Guma",
        Singe: "O\u017eehnout",
        Drifter: "Na vandru",
        Saturation: "Saturace",
        "Crop again": "O\u0159\u00edznout znovu",
        "Aviary Editor": "Editor Aviary",
        "Applying action %2$i of %2$i": "Prov\u00e1d\u00edm akci  %2$i z %2$i",
        Max: "Max",
        Attention: "Pozor!",
        Redeye: "\u010cerven\u00e9 o\u010di",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "Zru\u0161en\u00edm ztrat\u00edte v\u0161echny zm\u011bny proveden\u00e9 t\u00edmto n\u00e1strojem. Skute\u010dn\u011b chcete odej\u00edt?",
        "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?": "Jejda! Do\u0161lo k chyb\u011b p\u0159i pokusu o ulo\u017een\u00ed obr\u00e1zku do slo\u017eky Aviary. Chcete se pokusit obr\u00e1zek ulo\u017eit do norm\u00e1ln\u00ed slo\u017eky s fotkami?",
        Pinch: "\u0160t\u00edpnout",
        "Old Photo": "Star\u00e1 fotka",
        Laguna: "Laguna",
        Resize: "Zm\u011bnit velikost",
        "Powered by": "Powered by",
        "Color Grading": "Barevn\u00e9 schody",
        Firefly: "Sv\u011btlu\u0161ka",
        Rotate: "Ot\u00e1\u010den\u00ed (rotace)",
        "Applying effects": "P\u0159id\u00e1v\u00e1m efekty",
        Daydream: "Denn\u00ed sny",
        "Enter text here": "Vlo\u017ete text",
        "Code Red": "Rud\u00fd poplach",
        "Interested? We'll send you some info.": "Zaj\u00edm\u00e1 v\u00e1s to? Po\u0161leme v\u00e1m n\u011bjak\u00e9 informace.",
        Remove: "Odstranit",
        Concorde: "Konkord",
        "Vignette Blur": "Um\u011bleck\u00e9 rozmaz\u00e1n\u00ed",
        "About this editor": "O tomto editoru",
        Discard: "Zahodit",
        "Film Grain": "Filmov\u00fd \u0161um",
        Power: "S\u00edla",
        Color: "Barva",
        Demo: "Demo",
        Crop: "O\u0159ez",
        "Edit Top Text": "Upravit vrchn\u00ed text",
        Apply: "Pou\u017e\u00edt",
        Stickers: "Samolepky"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.de = {
        Loading: "Wird geladen\u2026",
        "Toy Camera": "Spielzeugkamera",
        Night: "Nacht",
        Nostalgia: "Nostalgisch",
        Aviary: "Aviary",
        Width: "Breite",
        "No effects found for this pack.": "Keine Effekte f\u00fcr dieses Pack gefunden",
        Blur: "Weichzeichnen",
        "Your image was cropped.": "Ihr Bild wurde zugeschnitten.",
        Sharpen: "Sch\u00e4rfen",
        "Learn more!": "Weitere Informationen",
        Ripped: "Zerissen",
        Indiglow: "Indiglow",
        "There is another image editing window open.  Close it without saving and continue?": "Ein weiteres Editorfenster ist ge\u00f6ffnet. M\u00f6chten Sie dieses ohne speichern der \u00c4nderungen schlie\u00dfen und fortfahren?",
        Resume: "Fortfahren",
        Heatwave: "Hitzewelle",
        "A filter pack has been updated. Click ok to reload the packs list.": "Ein Filterpack wurde aktualisiert. Klicken Sie, um die Pack-Liste erneut zu laden.",
        Update: "Aktualisieren",
        Free: "Frei",
        "There was an error downloading the image, please try again later.": "Beim Herunterladen des Bildes ist ein Fehler aufgetreten. Bitte versuchen Sie es sp\u00e4ter erneut.",
        Effects: "Effekte",
        Tools: "Werkzeuge",
        Reset: "Zur\u00fccksetzen",
        Blemish: "Heilen",
        Bulge: "W\u00f6lben",
        Alice: "Alice",
        "Are you sure you want to remove this sticker?": "M\u00f6chten Sie diesen Sticker wirklich entfernen?",
        "Revert to original?": "Original wiederherstellen?",
        Mohawk: "Mohawk",
        Enhance: "Verbessern",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary ist ein kostenloses SDK erh\u00e4ltlich f\u00fcr iOS und Android mit dem Sie Ihrer App mit wenigen Zeilen Code Bildbearbeitungsf\u00e4higkeiten und Effekte hinzuf\u00fcgen k\u00f6nnen.",
        Greeneye: "Gr\u00fcne Augen",
        Shadow: "Schatten",
        Vogue: "Vogue",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "Wenn Sie fortfahren gehen alle Ihre \u00c4nderungen verloren. M\u00f6chten Sie das Originalbild wirklich wiederherstellen?",
        OK: "OK",
        Intensity: "Intensit\u00e4t",
        Whiten: "Aufhellen",
        Frames: "Frames",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "F\u00fcgen Sie Ihren Fotos mit diesen sechs Effekten Bildrauschen und visuellen Verschlei\u00df hinzu.",
        "Delete selected": "Auswahl l\u00f6schen",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "Ein Stickerpack wurde aktualisiert. Der aktuelle Bereich muss neu geladen werden. M\u00f6chten Sie den aktuellen Sticker hinzuf\u00fcgen?",
        "Set color": "Farbe einstellen",
        "Always Sunny": "Always Sunny",
        Confirm: "Best\u00e4tigen",
        Siesta: "Siesta",
        Negative: "Negativ",
        Send: "Senden",
        "Keep editing": "Weiterbearbeiten",
        "Powered by Aviary.com": "Powered by Aviary.com",
        Zoom: "Zoom",
        Editor: "Editor",
        "Soft Focus": "Weicher Fokus",
        Save: "Sichern",
        "Are you sure?": "Sind Sie sicher?",
        Warmth: "W\u00e4rme",
        More: "Mehr",
        Meme: "Mem",
        Charcoal: "Kohle",
        Malibu: "Malibu",
        Grunge: "Grunge",
        "Tool Selection": "Werkzeugauswahl",
        Auto: "Auto",
        Tool: "Werkzeug",
        Daydream: "Tagtraum",
        Eddie: "Eddie",
        Cinematic: "Cinematisch",
        Store: "Shop",
        Backlit: "Gegenlicht",
        Fixie: "Fixie",
        "Are you sure you want to discard changes from this tool?": "Sind Sie sicher, dass Sie die \u00c4nderungen verwerfen m\u00f6chten?",
        Brightness: "Helligkeit",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "Moment! Wollen Sie den Editor wirklich ohne speichern der \u00c4nderungen schlie\u00dfen?",
        Smooth: "Gl\u00e4tten",
        "Get this editor": "Mehr \u00fcber Aviary",
        Draw: "Zeichnen",
        Flip: "Umkehren",
        "Soft Brushes": "Weiche Pinsel",
        Viewfinder: "Kamerasucher",
        "Your work was saved!": "\u00c4nderungen gespeichert!",
        Delete: "L\u00f6schen",
        Square: "Quadrat",
        Rounded: "Abgerundet",
        Redo: "Wiederholen",
        "Preset Sizes": "Voreingestellte Formate",
        Sharpness: "Sch\u00e4rfe",
        Back: "Zur\u00fcck",
        "Brush softness": "Pinselst\u00e4rke",
        Brush: "Pinsel",
        Mirror: "Spiegel",
        "Edit Bottom Text": "Text unten bearbeiten",
        "Photo Editor": "Foto-Editor",
        "Maintain proportions": "Proportionen beibehalten",
        Vivid: "Lebhaft",
        "San Carmen": "San Carmen",
        Retro: "Retro",
        Exit: "Ausgang",
        Undo: "R\u00fcckg\u00e4ngig",
        "Loading Image...": "Bild wird geladen\u2026",
        Borders: "Borders",
        Contrast: "Kontrast",
        "Instant!": "Sofortbild!",
        "Choose Color": "Farbe w\u00e4hlen",
        Strato: "Strato",
        Vignette: "Vignette",
        "Zoom Mode": "Zoom-Modus",
        "A sticker pack has been updated. We need to reload the current panel.": "Ein Stickerpack wurde aktualisiert. Der aktuelle Bereich muss neu geladen werden.",
        Vigilante: "Vigilante",
        "Hard Brushes": "Harte Pinsel",
        "Brush size": "Pinselgr\u00f6\u00dfe",
        "Get More": "Holen Sie sich mehr",
        "Color Matrix": "Farbmatrix",
        Corners: "Corners",
        Aqua: "Aqua",
        Ragged: "Ausgefranst",
        Ventura: "Ventura",
        Error: "Fehler",
        Kurt: "Kurt",
        Balance: "Abgleich",
        Original: "Original",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "Ihr Bild wurde vor\u00fcbergehend verkleinert, um das Bearbeiten einfacher zu machen. Beim Speichern wird jedoch die Originalgr\u00f6\u00dfe gespeichert.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "Schwelgen Sie in Erinnerungen, und denken Sie mit unseren sechs traumhaften Nostalgieeffekten an die gute alte Zeit.",
        Orientation: "Ausrichten",
        "Add Text": "Text",
        Classic: "Klassiker",
        Text: "Text",
        "No stickers defined in Feather_Stickers.": "Keine Sticker in Feather_Stickers vorhanden",
        "Drag corners to resize crop area": "Verschieben Sie die Bildr\u00e4nder um den Schnittbereich zu ver\u00e4ndern",
        "Give feedback": "Feedback geben",
        "Get this pack!": "Jetzt kaufen!",
        Height: "H\u00f6he",
        Colors: "Farben",
        Done: "Fertig",
        "See your world a little differently with these six high-tech camera effects.": "Ein anderer Blick auf die Welt mit diesen 6 Bildeffekten.",
        Cancel: "Abbr.",
        Close: "Schlie\u00dfen",
        "Width and height must be greater than zero and less than the maximum({max}px)": "Breite und H\u00f6he m\u00fcssen gr\u00f6\u00dfer als Null und kleiner als der Maximalwert ({max} Pixel) sein",
        "Leave editor": "Editor verlassen",
        Size: "Gr\u00f6\u00dfe",
        "e-mail address": "E-Mail-Adresse",
        Eraser: "Radierer",
        Min: "Min",
        Cherry: "Kirsche",
        "Are you sure? This can distort your image": "Sind Sie sicher? Dies kann Ihr Foto verzerren",
        "A sticker pack has been updated. Click ok to reload the packs list.": "Ein Stickerpack wurde aktualisiert. Klicken Sie OK, um die Pack-Liste erneut zu laden.",
        Custom: "Anpassen",
        Fade: "Verblassen",
        Singe: "Abgesengt",
        Drifter: "Drifter",
        Saturation: "S\u00e4ttigung",
        "Crop again": "Erneut zuschneiden",
        "Aviary Editor": "Aviary Editor",
        Max: "Max",
        Attention: "Achtung",
        Redeye: "Rote Augen",
        Halftone: "Halbton",
        Pinch: "Stauchen",
        "Old Photo": "Foto altern",
        Laguna: "Laguna",
        Resize: "Bildgr\u00f6\u00dfe",
        "Powered by": "Powered by",
        "Color Grading": "Farbabstufung",
        Firefly: "Libelle",
        Rotate: "Drehen",
        "Applying effects": "Wende Effekte an",
        "Enter text here": "Text hier eingeben",
        "Code Red": "Roter Alarm",
        "Interested? We'll send you some info.": "Interessiert? Wir werden Ihnen Informationen zuschicken.",
        Remove: "Entfernen",
        Concorde: "Concorde",
        "Vignette Blur": "Vignettierung",
        "About this editor": "\u00dcber diesen Editor",
        Discard: "Verwerfen",
        "Film Grain": "Filmk\u00f6rnung",
        Power: "An/Aus",
        Color: "Farbe",
        Demo: "Demo",
        Crop: "Ausschnitt",
        "Edit Top Text": "Text oben bearbeiten",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "Sie verlieren Ihre \u00c4nderungen. M\u00f6chten Sie das den Editor wirklich verlassen?",
        Apply: "Anwenden",
        Stickers: "Sticker"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.en = {
        Loading: "Loading",
        "Sorry, you must update the Aviary editor to use these effects.": "Sorry, you must update the Aviary editor to use these effects.",
        "Toy Camera": "Toy Camera",
        Night: "Night",
        Nostalgia: "Nostalgia",
        Aviary: "Aviary",
        "Mark II": "Mark II",
        Width: "Width",
        "No effects found for this pack.": "No effects found for this pack.",
        Blur: "Blur",
        "Your image was cropped.": "Your image was cropped.",
        Sharpen: "Sharpen",
        "Learn more!": "Learn more!",
        Ripped: "Ripped",
        Indiglow: "Indiglow",
        "There is another image editing window open.  Close it without saving and continue?": "There is another image editing window open.  Close it without saving and continue?",
        Resume: "Resume",
        Heatwave: "Heatwave",
        "A filter pack has been updated. Click ok to reload the packs list.": "A filter pack has been updated. Click ok to reload the packs list.",
        Update: "Update",
        Free: "Free",
        "There was an error downloading the image, please try again later.": "There was an error downloading the image, please try again later.",
        Effects: "Effects",
        "Sorry, there's no application on your phone to handle this action.": "Sorry, there's no application on your phone to handle this action.",
        Vogue: "Vogue",
        Tools: "Tools",
        "Don't ask me again": "Don't ask me again",
        Reset: "Reset",
        "File saved": "File saved",
        Blemish: "Blemish",
        Chrono: "Chrono",
        Bulge: "Bulge",
        Alice: "Alice",
        "Destination folder": "Destination folder",
        "Sorry, you must update the effect pack to continue.": "Sorry, you must update the effect pack to continue.",
        "Original size": "Original size",
        "Are you sure you want to remove this sticker?": "Are you sure you want to remove this sticker?",
        "Revert to original?": "Revert to original?",
        Mohawk: "Mohawk",
        Enhance: "Enhance",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.",
        Greeneye: "Greeneye",
        Shadow: "Shadow",
        "Unknown error": "Unknown error",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?",
        OK: "OK",
        "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?": "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?",
        Intensity: "Intensity",
        Whiten: "Whiten",
        Frames: "Frames",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "Add some grit and visual wear-and-tear to your photos with these six grungy effects.",
        "Delete selected": "Delete selected",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?",
        Sepia: "Sepia",
        "Set color": "Set color",
        "Always Sunny": "Always Sunny",
        Confirm: "Confirm",
        Siesta: "Siesta",
        Reflex: "Reflex",
        Negative: "Negative",
        Send: "Send",
        "Keep editing": "Keep editing",
        "Powered by Aviary.com": "Powered by Aviary.com",
        Zoom: "Zoom",
        "Sorry, there's no file manager installed on your phone to handle this action. Do you want to download one now from the market?": "Sorry, there's no file manager installed on your phone to handle this action. Do you want to download one now from the market?",
        Editor: "Editor",
        "Biggest size": "Biggest size",
        "Soft Focus": "Soft Focus",
        Save: "Save",
        "Are you sure?": "Are you sure?",
        Warmth: "Warmth",
        More: "More",
        Meme: "Meme",
        Charcoal: "Charcoal",
        Malibu: "Malibu",
        Grunge: "Grunge",
        "Tool Selection": "Tool Selection",
        Auto: "Auto",
        Tool: "Tool",
        Settings: "Settings",
        Eddie: "Eddie",
        Cinematic: "Cinematic",
        "Medium size": "Medium size",
        Store: "Store",
        Backlit: "Backlit",
        "B&W": "B&W",
        Fixie: "Fixie",
        "Are you sure you want to discard changes from this tool?": "Are you sure you want to discard changes from this tool?",
        Brightness: "Brightness",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "Wait! You didn't save your work. Are you certain that you want to close this editor?",
        Smooth: "Smooth",
        "Get this editor": "Get this editor",
        Draw: "Draw",
        Flip: "Flip",
        "Soft Brushes": "Soft Brushes",
        "View Image": "View Image",
        Viewfinder: "Viewfinder",
        "Your work was saved!": "Your work was saved!",
        "Small size": "Small size",
        Delete: "Delete",
        Square: "Square",
        Rounded: "Rounded",
        Redo: "Redo",
        "Preset Sizes": "Preset Sizes",
        Sharpness: "Sharpness",
        Back: "Back",
        "Brush softness": "Brush softness",
        Periscope: "Periscope",
        Brush: "Brush",
        Mirror: "Mirror",
        "Edit Bottom Text": "Edit Bottom Text",
        "Photo Editor": "Photo Editor",
        "Maintain proportions": "Maintain proportions",
        Vivid: "Vivid",
        "San Carmen": "San Carmen",
        Retro: "Retro",
        "Sorry, there was an error loading the effect pack": "Sorry, there was an error loading the effect pack",
        Exit: "Exit",
        Undo: "Undo",
        "Loading Image...": "Loading Image...",
        "Please install {Adobe Flash Player} (version {min} or higher), or use a supported browser: {Chrome}, {Firefox}, {Safari}, {Opera}, or {Internet Explorer} (version 9 or higher).": "Please install {Adobe Flash Player} (version {min} or higher), or use a supported browser: {Chrome}, {Firefox}, {Safari}, {Opera}, or {Internet Explorer} (version 9 or higher).",
        Borders: "Borders",
        Contrast: "Contrast",
        "Saving...": "Saving...",
        "Instant!": "Instant!",
        "Choose Color": "Choose Color",
        Strato: "Strato",
        Vignette: "Vignette",
        "Zoom Mode": "Zoom Mode",
        "A sticker pack has been updated. We need to reload the current panel.": "A sticker pack has been updated. We need to reload the current panel.",
        Vigilante: "Vigilante",
        "Image saved in %1$s. Do you want to see the saved image?": "Image saved in %1$s. Do you want to see the saved image?",
        "Hard Brushes": "Hard Brushes",
        "Brush size": "Brush size",
        "Get More": "Get More",
        "Color Matrix": "Color Matrix",
        Corners: "Corners",
        Aqua: "Aqua",
        "Output Image Size": "Output Image Size",
        Ragged: "Ragged",
        Ventura: "Ventura",
        Error: "Error",
        "You can change this property in the Settings panel.": "You can change this property in the Settings panel.",
        Kurt: "Kurt",
        Balance: "Balance",
        Original: "Original",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "Reminisce over fond memories and good times with our six dreamy nostalgia effects.",
        "Oops, there was an error while saving the image.": "Oops, there was an error while saving the image.",
        Orientation: "Orientation",
        "Add Text": "Add Text",
        Classic: "Classic",
        "24ZX": "24ZX",
        Text: "Text",
        "No stickers defined in Feather_Stickers.": "No stickers defined in Feather_Stickers.",
        "Drag corners to resize crop area": "Drag corners to resize crop area",
        "Give feedback": "Give feedback",
        "Get this pack!": "Get this pack!",
        Height: "Height",
        Colors: "Colors",
        Done: "Done",
        "See your world a little differently with these six high-tech camera effects.": "See your world a little differently with these six high-tech camera effects.",
        Covert: "Covert",
        Cancel: "Cancel",
        Close: "Close",
        "Width and height must be greater than zero and less than the maximum({max}px)": "Width and height must be greater than zero and less than the maximum({max}px)",
        "Leave editor": "Leave editor",
        Size: "Size",
        "e-mail address": "e-mail address",
        "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!": "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!",
        Fade: "Fade",
        Min: "Min",
        Cherry: "Cherry",
        "Are you sure? This can distort your image": "Are you sure? This can distort your image",
        "A sticker pack has been updated. Click ok to reload the packs list.": "A sticker pack has been updated. Click ok to reload the packs list.",
        Custom: "Custom",
        Eraser: "Eraser",
        Singe: "Singe",
        Drifter: "Drifter",
        Saturation: "Saturation",
        "Crop again": "Crop again",
        "Aviary Editor": "Aviary Editor",
        "Applying action %2$i of %2$i": "Applying action %2$i of %2$i",
        Max: "Max",
        Attention: "Attention",
        Redeye: "Redeye",
        Halftone: "Halftone",
        "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?": "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?",
        Pinch: "Pinch",
        "Old Photo": "Old Photo",
        Laguna: "Laguna",
        Resize: "Resize",
        "Powered by": "Powered by",
        "Color Grading": "Color Grading",
        Firefly: "Firefly",
        Rotate: "Rotate",
        "Applying effects": "Applying effects",
        Daydream: "Daydream",
        "Enter text here": "Enter text here",
        "Code Red": "Code Red",
        "Interested? We'll send you some info.": "Interested? We'll send you some info.",
        Remove: "Remove",
        Concorde: "Concorde",
        "Vignette Blur": "Vignette Blur",
        "About this editor": "About this editor",
        Discard: "Discard",
        "Film Grain": "Film Grain",
        Power: "Power",
        Color: "Color",
        Demo: "Demo",
        Crop: "Crop",
        "Edit Top Text": "Edit Top Text",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "You're about to lose the changes you've made in this tool. Are you sure you want to leave?",
        Apply: "Apply",
        Stickers: "Stickers"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.es = {
        Loading: "Cargando",
        "Toy Camera": "Juguete",
        Night: "Noche",
        Nostalgia: "Nostalgia",
        Aviary: "Aviary",
        Width: "Ancho",
        "No effects found for this pack.": "No se encontraron efectos para este paquete",
        Blur: "Difuminar",
        "Your image was cropped.": "Tu imagen fue recortada.",
        Sharpen: "Afinar",
        "Learn more!": "\u00a1M\u00e1s informaci\u00f3n!",
        Ripped: "Rasgado",
        Indiglow: "Indibrillo",
        "There is another image editing window open.  Close it without saving and continue?": "Hay otra imagen con el editor abierto. \u00bfQuiere cerrarla sin guardar los cambios y continuar?",
        Resume: "Resumen",
        Heatwave: "Ola de calor",
        "A filter pack has been updated. Click ok to reload the packs list.": "Se ha actualizado un paquete de filtro. Haz clic en \u201caceptar\u201d para volver a cargar la lista de paquetes",
        Update: "Actualizar",
        Free: "Libre",
        "There was an error downloading the image, please try again later.": "Ocurri\u00f3 un error al descargar la imagen, prueba otra vez m\u00e1s tarde",
        Effects: "Efectos",
        "Sorry, there's no application on your phone to handle this action.": "Lo sentimos, no hay ninguna aplicaci\u00f3n en tu tel\u00e9fono para ejecutar esta acci\u00f3n",
        Tools: "Utilidades",
        "Don't ask me again": "No volver a preguntarme",
        Reset: "Restablecer",
        "File saved": "Archivo guardado",
        Blemish: "Mancha",
        Bulge: "Ensanchar",
        Alice: "Alice",
        "Destination folder": "Carpeta de destino",
        "Original size": "Tama\u00f1o original",
        "Are you sure you want to remove this sticker?": "\u00bfEst\u00e1s seguro de que quieres quitar esta pegatina?",
        "Revert to original?": "\u00bfVolver al original?",
        Mohawk: "Mohawk",
        Enhance: "Realzar",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary es un SDK disponible para iOS y Android que te permite agregar capacidades de edici\u00f3n de fotos y efectos a tu aplicaci\u00f3n con s\u00f3lo un par de l\u00edneas de c\u00f3digo.",
        Greeneye: "Ojo verde",
        Shadow: "Sombra",
        Vogue: "Vogue",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "Est\u00e1s a punto de perder todos los cambios que realizaste. \u00bfEst\u00e1s seguro de que quieres volver a la imagen original?",
        OK: "Ok",
        "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?": "Lo sentimos, no hay ninguna aplicaci\u00f3n en tu tel\u00e9fono para ejecutar esta acci\u00f3n. \u00bfQuieres descargarla ahora en el mercado?",
        Intensity: "Intensidad",
        Whiten: "Blanquear",
        Frames: "Marcos",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "A\u00f1ade un poco de polvo y desgaste a tus fotos con estos seis efectos mugrientos.",
        "Delete selected": "Eliminar seleccionados",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "Se ha actualizado un paquete de pegatina. Necesitamos volver a cargar el panel actual. \u00bfQuieres aplicar la pegatina actual?",
        "Set color": "Selecciona color",
        "Always Sunny": "Soleado",
        Confirm: "Confirmar",
        Siesta: "Siesta",
        Negative: "Negativo",
        Send: "Enviar",
        "Keep editing": "Seguir editando",
        "Powered by Aviary.com": "Desarrollado por Aviary.com",
        Zoom: "Zoom",
        Editor: "Editor",
        "Biggest size": "Tama\u00f1o grande",
        "Soft Focus": "Soft focus",
        Save: "Guardar",
        "Are you sure?": "\u00bfEst\u00e1 seguro?",
        Warmth: "Calor",
        More: "M\u00e1s",
        Meme: "Meme",
        Charcoal: "Carboncillo",
        Malibu: "Malib\u00fa",
        Grunge: "Grunge",
        "Tool Selection": "Herramienta de Selecci\u00f3n",
        Auto: "Auto",
        Tool: "Herramienta",
        Settings: "Configuraci\u00f3n",
        Eddie: "Eddie",
        Cinematic: "Cinem\u00e1tica",
        "Medium size": "Tama\u00f1o medio",
        Store: "Tienda",
        Backlit: "Contraluz",
        Fixie: "Fixie",
        "Are you sure you want to discard changes from this tool?": "\u00bfEst\u00e1s seguro de que quieres desechar los cambios de esta herramienta?",
        Brightness: "Brillo",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "\u00a1Espera! No has guardado los cambios. \u00bfEst\u00e1s seguro que deseas cerrar este editor?",
        Smooth: "Alise",
        "Get this editor": "Obtener este editor",
        Draw: "Dibujo",
        Flip: "Voltear",
        "Soft Brushes": "Pincelada suave",
        "View Image": "Ver imagen",
        Viewfinder: "Visor",
        "Your work was saved!": "\u00a1Tu trabajo se ha guardado!",
        "Small size": "Tama\u00f1o peque\u00f1o",
        Delete: "Borrar",
        Square: "Cuadrado",
        Rounded: "Redondeado",
        Redo: "Rehacer",
        "Preset Sizes": "Tama\u00f1o",
        Sharpness: "Nitidez",
        Back: "Volver",
        "Brush softness": "Pincel suave",
        Brush: "Cepillo",
        Mirror: "Espejo",
        "Edit Bottom Text": "Editar texto inferior",
        "Photo Editor": "Editor de fotos",
        "Maintain proportions": "Mantener proporciones",
        Vivid: "V\u00edvido",
        "San Carmen": "San Carmen",
        Retro: "Retro",
        Exit: "Salida",
        Undo: "Deshacer",
        "Loading Image...": "Cargando imagen...",
        Borders: "Fronteras",
        Contrast: "Contraste",
        "Saving...": "Guardando...",
        "Instant!": "Al instante",
        "Choose Color": "Elegir color",
        Strato: "Strato",
        Vignette: "Vi\u00f1eta",
        "Zoom Mode": "Modo Zoom",
        "A sticker pack has been updated. We need to reload the current panel.": "Se ha actualizado un paquete de pegatina. Necesitamos volver a cargar el panel actual",
        Vigilante: "Vigilante",
        "Image saved in %1$s. Do you want to see the saved image?": "Imagen guardada en %1$s. \u00bfQuieres ver la imagen guardada?",
        "Hard Brushes": "Pincelada fuerte",
        "Brush size": "Punto",
        "Get More": "Obtener m\u00e1s",
        "Color Matrix": "Matriz de colores",
        Corners: "Esquinas",
        Aqua: "Aqua",
        "Output Image Size": "Tama\u00f1o de la imagen",
        Ragged: "Irregular",
        Ventura: "Ventura",
        Error: "Error",
        "You can change this property in the Settings panel.": "Puedes cambiar esta propiedad en el panel de Configuraci\u00f3n",
        Kurt: "Kurt",
        Balance: "Equilibrio",
        Original: "Original",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "Tu imagen ha sido reducida temporalmente para que sea m\u00e1s f\u00e1cil de editar. Cuando grabes, se guardar\u00e1 el tama\u00f1o original.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "Rememora preciados momentos y buenos tiempos con nuestros seis efectos de nostalgia de ensue\u00f1o.",
        "Oops, there was an error while saving the image.": "\u00a1Vaya!, ha habido un error al guardar la imagen.",
        Orientation: "Orientado",
        "Add Text": "+ texto",
        Classic: "Cl\u00e1sico",
        Text: "Texto",
        "No stickers defined in Feather_Stickers.": "No hay etiquetas definidas en Feather_Stickers",
        "Drag corners to resize crop area": "Arrastre las esquinas para cambiar el tama\u00f1o",
        "Give feedback": "Observaciones",
        "Get this pack!": "\u00a1Obt\u00e9n este paquete!",
        Height: "Alto",
        Colors: "Colores",
        Done: "Listo",
        "See your world a little differently with these six high-tech camera effects.": "Ve tu mundo un poco diferente con estos seis efectos de c\u00e1mara de alta tecnolog\u00eda.",
        Cancel: "Cancelar",
        Close: "Cerrar",
        "Width and height must be greater than zero and less than the maximum({max}px)": "El ancho y el alto deben ser superiores a cero e inferiores al m\u00e1ximo ({max} px)",
        "Leave editor": "Salir del editor",
        Size: "Tama\u00f1o",
        "e-mail address": "Correo electr\u00f3nico",
        "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!": "\u00a1Vaya! \u00a1He tenido un fallo, pero he enviado un informe a mi desarrollador para ayudarle a solucionar el problema!",
        Fade: "Fundido",
        Min: "M\u00edn",
        Cherry: "Cereza",
        "Are you sure? This can distort your image": "\u00bfEst\u00e1s seguro? Esto puede distorsionar la imagen",
        "A sticker pack has been updated. Click ok to reload the packs list.": "Se ha actualizado un paquete de pegatina. Haz clic en \u201caceptar\u201d para volver a cargar la lista de paquetes",
        Custom: "Personal",
        Eraser: "Borrador",
        Singe: "Chamuscado",
        Drifter: "Vagabundo",
        Saturation: "Saturaci\u00f3n",
        "Crop again": "Intentar de nuevo",
        "Aviary Editor": "Aviary Editor",
        "Applying action %2$i of %2$i": "Aplicando acci\u00f3n %2$i de %2$i",
        Max: "M\u00e1x",
        Attention: "Atenci\u00f3n",
        Redeye: "Ojos rojos",
        Halftone: "Medios tonos",
        "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?": "\u00a1Vaya!, ha habido un error al intentar guardar la imagen en la carpeta de Aviary. \u00bfQuieres intentar guardarla en la carpeta predeterminada de la c\u00e1mara?",
        Pinch: "Contraer",
        "Old Photo": "Foto antigua",
        Laguna: "Laguna",
        Resize: "Reducir",
        "Powered by": "Desarrollado por",
        "Color Grading": "Correcci\u00f3n de color",
        Firefly: "Luci\u00e9rnaga",
        Rotate: "Girar",
        "Applying effects": "Aplicando efectos",
        Daydream: "Ensue\u00f1o",
        "Enter text here": "Escribir texto aqu\u00ed",
        "Code Red": "C\u00f3digo rojo",
        "Interested? We'll send you some info.": "\u00bfInteresado? Te enviaremos informaci\u00f3n.",
        Remove: "Suspender",
        Concorde: "Concorde",
        "Vignette Blur": "Vi\u00f1eta con desenfoque",
        "About this editor": "Acerca de este editor",
        Discard: "Desechar",
        "Film Grain": "Granulado",
        Power: "Poder",
        Color: "Color",
        Demo: "Demo",
        Crop: "Recortar",
        "Edit Top Text": "Editar texto superior",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "Est\u00e1s a punto de perder los cambios que has efectuado con esta herramienta. \u00bfEst\u00e1s seguro que deseas salir?",
        Apply: "Aplicar",
        Stickers: "Pegatinas"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.fi = {
        Loading: "Lataa",
        "Sorry, you must update the Aviary editor to use these effects.": "Valitettavasti sinun t\u00e4ytyy p\u00e4ivitt\u00e4\u00e4 Aviary muokkausty\u00f6kalu k\u00e4ytt\u00e4\u00e4ksesi n\u00e4it\u00e4 toimintoja.",
        "Toy Camera": "Leikkikamera",
        Night: "Y\u00f6",
        Nostalgia: "Nostalgia",
        Aviary: "Aviary",
        "Mark II": "Mark II",
        Width: "Leveys",
        "No effects found for this pack.": "Tehosteita ei l\u00f6ydetty t\u00e4h\u00e4n pakettiin.",
        Blur: "H\u00e4m\u00e4rr\u00e4",
        Sharpen: "Tarkenna",
        "Learn more!": "Lue lis\u00e4\u00e4",
        Indiglow: "Indiglow",
        "There is another image editing window open.  Close it without saving and continue?": "Toinen kuvanmuokkaussivu on auki samanaikaisesti. Sulje sivu tallentamatta ja jatka?",
        Resume: "Jatka",
        Heatwave: "Helleaalto",
        "A filter pack has been updated. Click ok to reload the packs list.": "Filter-paketti on p\u00e4ivitetty. Klikkaa OK ladataksesi uudelleen pakettilistan.",
        Update: "P\u00e4ivit\u00e4",
        Undo: "Kumoa",
        "There was an error downloading the image, please try again later.": "Kuvan latauksessa tapahtui virhe, kokeile my\u00f6hemmin uudestaan.",
        Effects: "Tehosteet",
        "Sorry, there's no application on your phone to handle this action.": "Valitettavasti puhelimessasi ei ole t\u00e4h\u00e4n sopivaa sovellusta.",
        Vogue: "Vogue",
        Tools: "Ty\u00f6kalut",
        "Don't ask me again": "\u00c4l\u00e4 kysy uudelleen",
        Reset: "Palauta",
        "File saved": "Tiedosto tallennettu",
        Blemish: "Tahraa",
        Chrono: "Chrono",
        Bulge: "Pullista",
        Alice: "Alice",
        "Destination folder": "Kohdekansio",
        "Sorry, you must update the effect pack to continue.": "Valitettavasti sinun t\u00e4ytyy p\u00e4ivitt\u00e4\u00e4 paketti jatkaaksesi.",
        "Original size": "Alkuper\u00e4inen koko",
        "Are you sure you want to remove this sticker?": "Oletko varma, ett\u00e4 haluat poistaa stickersin?",
        "Revert to original?": "Palauta alkuper\u00e4inen",
        Mohawk: "Mohawk",
        Enhance: "Paranna",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary on ilmainen SDK saatavilla IOS ja Android j\u00e4rjestelmille, ja jonka avulla voit lis\u00e4t\u00e4 valokuva-editointi ominaisuuksia ja tehosteita sovellukseesi vain muutaman koodirivin avulla.",
        Greeneye: "Vihre\u00e4silm\u00e4isyys",
        "Unknown error": "Tuntematon virhe",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "Kaikki muutokset menetet\u00e4\u00e4n. Oletko varma, ett\u00e4 haluat palauttaa alkuper\u00e4isen kuvan?",
        OK: "OK",
        "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?": "Valitettavasti puhelimessasi ei ole t\u00e4h\u00e4n sopivaa sovellusta. Haluatko ladata sopivan sovelluksen?",
        Reflex: "Heijastus",
        Whiten: "Valkaise",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "Lis\u00e4\u00e4 rosoisuutta ja grudge tunnelmaa kuviisi n\u00e4iden kuuden Grunge tehosteen avulla.",
        "Delete selected": "Poista valittu",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "Stickers-paketti on p\u00e4ivitetty. Nykyinen paneeli t\u00e4ytyy ladata uudelleen. Haluatko hyv\u00e4ksy\u00e4 valitsemasi stickersin?",
        Sepia: "Seepia",
        "Always Sunny": "Aina aurinkoinen",
        Confirm: "Vahvista",
        Siesta: "Siesta",
        Negative: "Negatiivi",
        Send: "L\u00e4het\u00e4",
        "Keep editing": "Jatka muokkausta",
        "Powered by Aviary.com": "Powered by Aviary.com",
        Zoom: "Suurenna",
        "Sorry, there's no file manager installed on your phone to handle this action. Do you want to download one now from the market?": "Valitettavasti puhelimessasi ei ole tiedostonhallintasovellusta t\u00e4m\u00e4 toimintoa varten. Haluatko ladata sovelluksen?",
        Editor: "Muokkaja",
        "Biggest size": "Suurin koko",
        "Soft Focus": "Pehme\u00e4 tarkennus",
        Save: "Tallenna",
        "Are you sure?": "Oletko varma?",
        Warmth: "L\u00e4mp\u00f6isyys",
        More: "Lis\u00e4\u00e4",
        Meme: "Meme",
        Malibu: "Malibu",
        Grunge: "Grunge",
        "Tool Selection": "Tykalun valinta",
        Auto: "Auto",
        Tool: "Ty\u00f6kalu",
        Settings: "Asetukset",
        Eddie: "Eddie",
        Cinematic: "Elokuva",
        "Medium size": "Keskikoko",
        Store: "S\u00e4ilyt\u00e4",
        Backlit: "Taustavalaistus",
        "B&W": "Mustavalkoinen",
        Fixie: "Fixie",
        "Are you sure you want to discard changes from this tool?": "Oletko varma, ett\u00e4 haluat hyl\u00e4t\u00e4 t\u00e4m\u00e4n ty\u00f6kalun muutokset?",
        Brightness: "Kirkkaus",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "Odota! Et tallentanut ty\u00f6t\u00e4si. Oletko varma, ett\u00e4 haluat sulkea t\u00e4m\u00e4n toiminnon?",
        "Get this editor": "Hanki t\u00e4m\u00e4 muokkausty\u00f6kalu",
        Draw: "Ved\u00e4",
        Flip: "K\u00e4\u00e4nn\u00e4",
        "View Image": "N\u00e4yt\u00e4 kuva",
        Viewfinder: "Etsin",
        "Your work was saved!": "Ty\u00f6si on tallennettu!",
        "Small size": "Pieni koko",
        Delete: "Poista",
        Square: "Neli\u00f6",
        Redo: "Tee uudelleen",
        "Preset Sizes": "Esiasetetut koot",
        Sharpness: "Tarkkuus",
        Back: "Takaisin",
        "Brush softness": "Harjan pehmeys",
        Periscope: "Periskooppi",
        Brush: "Harja",
        Mirror: "Peili",
        "Edit Bottom Text": "Muokkaa alhaalla olevaa teksti\u00e4",
        "Photo Editor": "Kuvan muokkaaja",
        "Maintain proportions": "S\u00e4ilyt\u00e4 mittasuhteet",
        Vivid: "Kirkas",
        "San Carmen": "San Carmen",
        Retro: "Retro",
        "Sorry, there was an error loading the effect pack": "Valitettavasti tehosteen lataaamisessa k\u00e4vi virhe.",
        "Loading Image...": "Kuvaa ladataan...",
        Contrast: "Kontrasti",
        "Instant!": "Instant!",
        "Choose Color": "Valitse v\u00e4ri",
        Strato: "Strato",
        "Zoom Mode": "Suurennusn\u00e4kym\u00e4",
        "A sticker pack has been updated. We need to reload the current panel.": "Stickers-paketti on p\u00e4ivitetty. Nykyinen paneeli t\u00e4ytyy ladata uudelleen.",
        Vigilante: "Valpas",
        "Image saved in %1$s. Do you want to see the saved image?": "Kuva tallennettu kansioon %1$s. Haluatko n\u00e4hd\u00e4 tallennetun kuvan?",
        "Hard Brushes": "Kovat harjat",
        "Brush size": "Harjan koko",
        "Get More": "Hanki lis\u00e4\u00e4",
        "Color Matrix": "V\u00e4rimatriisi",
        Pinch: "Nipist\u00e4",
        Aqua: "Aqua",
        "Output Image Size": "Output kuvan koko",
        Ventura: "Ventura",
        Error: "Nuoli",
        "You can change this property in the Settings panel.": "Voit muuttaa t\u00e4m\u00e4n ominaisuuden Asetuksissa.",
        Kurt: "Kurt",
        Balance: "Tasapaino",
        Original: "Alkuper\u00e4inen",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "Kuvasi pienennettiin tilap\u00e4isesti paremman muokkauksen toteuttamiseksi. Kun painat Tallenna, tallennat oikean koon.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "Muistele vanhoja hyvi\u00e4 aikoja kuuden unenomaisen nostalgia tehosteen ansiosta.",
        "Oops, there was an error while saving the image.": "Hups, kuvan tallentamisessa tapahtui virhe.",
        Orientation: "Suuntaus",
        "Add Text": "Lis\u00e4\u00e4 teksti\u00e4",
        "Your image was cropped.": "Kuvasi on rajattu.",
        "24ZX": "24ZX",
        Text: "Teksti",
        "No stickers defined in Feather_Stickers.": "Stickerseja ei m\u00e4\u00e4ritelty Sulka_Stickers paketissa",
        "Drag corners to resize crop area": "Laahaa kulmat muuttaaksesi rajattua aluetta",
        "Give feedback": "Anna palautetta",
        "Get this pack!": "Hanki t\u00e4m\u00e4 paketti!",
        Height: "Pituus",
        Colors: "V\u00e4rit",
        Done: "Valmis",
        "See your world a little differently with these six high-tech camera effects.": "N\u00e4e maailma hieman erilailla n\u00e4iden kuuden high tech kameratehosteen avulla.",
        Covert: "Salamyhk\u00e4inen",
        "Soft Brushes": "Pehme\u00e4 harja",
        Close: "Sulje",
        "Width and height must be greater than zero and less than the maximum({max}px)": "Leveyden ja korkeuden on oltava nollaa suurempia ja pienempi\u00e4 kuin maximum({max}px)",
        "Leave editor": "Poistu editorista",
        Size: "Koko",
        "e-mail address": "S\u00e4hk\u00f6postiosoite",
        "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!": "Hups! Jotain meni pieleen, virheraportti on l\u00e4hetetty eteenp\u00e4in, jotta asia saadaan korjattua.",
        "Saving...": "Tallentaa..",
        Min: "Minimi",
        Cherry: "Kirsikka",
        "Are you sure? This can distort your image": "Oletko varma? T\u00e4m\u00e4 saattaa v\u00e4\u00e4rist\u00e4\u00e4 kuvasi.",
        "A sticker pack has been updated. Click ok to reload the packs list.": "Stickers-paketti on p\u00e4ivitetty. Klikkaa OK ladataksesi uudestaan pakettilistan.",
        Custom: "Mukauta",
        Eraser: "Kumi",
        Singe: "Singe",
        Drifter: "Drifter",
        Saturation: "Kyll\u00e4stys",
        "Crop again": "Rajaa uudelleen",
        "Aviary Editor": "Aviary Editor",
        "Applying action %2$i of %2$i": "Toimintoa %2$i / %2$i sovelletaan",
        Max: "Maksimi",
        Attention: "Huomio",
        Redeye: "Punasilm\u00e4isyys",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "Kaikki ty\u00f6kalun muutokset menetet\u00e4\u00e4n. Oletko varma, ett\u00e4 haluat poistua?",
        "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?": "Hups, kuvan tallentamisessa Aviary-kansioon tapahtui virhe. Haluatko tallentaa kuvan oletusarvokansioon?",
        "Old Photo": "Vanha kuva",
        Laguna: "Laguna",
        Resize: "Muunna kokoa",
        "Powered by": "Powered by",
        "Color Grading": "V\u00e4riskaala",
        Firefly: "Firefly",
        Rotate: "K\u00e4\u00e4nn\u00e4",
        "Applying effects": "Tehosteita sovelletaan",
        Daydream: "P\u00e4iv\u00e4uni",
        "Enter text here": "Lis\u00e4\u00e4 teksti t\u00e4h\u00e4n",
        "Code Red": "Code Red",
        "Interested? We'll send you some info.": "Oletko kiinnostunut? L\u00e4het\u00e4mme sinulle lis\u00e4tietoja.",
        Remove: "Poista",
        Concorde: "Concorde",
        "Vignette Blur": "Vignette Blur",
        "About this editor": "Tietoja t\u00e4st\u00e4 editorista",
        Discard: "Hylk\u00e4\u00e4",
        "Film Grain": "Filmin rakeisuus",
        Power: "Virta",
        Color: "V\u00e4ri",
        Demo: "Demo",
        Crop: "Rajaa",
        "Edit Top Text": "Muokkaa ylh\u00e4\u00e4ll\u00e4 olevaa teksti\u00e4",
        Apply: "Sovella",
        Stickers: "Stickers"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.fr = {
        Loading: "Chargement",
        "Toy Camera": "Vintage",
        Night: "Nuit",
        Nostalgia: "Nostalgie",
        Aviary: "Aviary",
        Width: "Largeur",
        "No effects found for this pack.": "Pas d'effets trouv\u00e9s pour ce pack",
        Blur: "Flou",
        "Your image was cropped.": "Votre image a \u00e9t\u00e9 recadr\u00e9e.",
        Sharpen: "Nettet\u00e9",
        "Learn more!": "En savoir plus\u00a0!",
        Ripped: "D\u00e9chir\u00e9",
        Indiglow: "Brillance",
        "There is another image editing window open.  Close it without saving and continue?": "Il ya une autre fen\u00eatre d'\u00e9dition d'image ouverte. La fermer sans enregistrer et continuer?",
        Resume: "Reprendre",
        Heatwave: "Canicule",
        "A filter pack has been updated. Click ok to reload the packs list.": "Un pack de filtres a \u00e9t\u00e9 mis \u00e0 jour. Cliquez sur OK pour recharger la liste des packs",
        Update: "Mettre \u00e0 jour",
        Free: "Libre",
        "There was an error downloading the image, please try again later.": "Une erreur s'est produite pendant le t\u00e9l\u00e9chargement de l'image, veuillez r\u00e9essayer plus tard.",
        Effects: "Effets",
        "Sorry, there's no application on your phone to handle this action.": "D\u00e9sol\u00e9, il n'y a pas d'application sur votre t\u00e9l\u00e9phone pour g\u00e9rer cette action",
        Tools: "Outils",
        "Don't ask me again": "Ne pas me le redemander",
        Reset: "R\u00e9initialiser",
        "File saved": "Fichier sauvegard\u00e9",
        Blemish: "Fondu",
        Bulge: "Gonfler",
        Alice: "Alice",
        "Destination folder": "Dossier de destination",
        "Original size": "Taille originale",
        "Are you sure you want to remove this sticker?": "\u00cates-vous s\u00fbr de vouloir supprimer cet autocollant\u00a0?",
        "Revert to original?": "Revenir \u00e0 l'original\u00a0?",
        Mohawk: "Mohawk",
        Enhance: "Renforcer",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary est un SDK gratuit disponible pour iOS et Andro\u00efde qui vous permet d\u2019ajouter des capacit\u00e9s de modification de photos  et des effets \u00e0 votre App avec quelques lignes de code seulement.",
        Greeneye: "Yeux verts",
        Shadow: "Ombre",
        Vogue: "Vogue",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "Vous \u00eates sur le point de perdre toutes les modifications que vous avez faites. \u00cates-vous s\u00fbr de vouloir revenir \u00e0 l'image d'origine\u00a0?",
        OK: "Ok",
        "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?": "D\u00e9sol\u00e9, il n'y a pas d'application sur votre t\u00e9l\u00e9phone pour g\u00e9rer cette action. Voulez-vous en t\u00e9l\u00e9charger une tout de suite sur le march\u00e9\u00a0?",
        Intensity: "Intensit\u00e9",
        Whiten: "Blanchir",
        Frames: "Cadres",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "Ajoutez du grain et une impression d'usure \u00e0 vos photos gr\u00e2ce \u00e0 ces six effets de vieillissement.",
        "Delete selected": "Supprimer la s\u00e9lection",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "Un pack d'autocollants a \u00e9t\u00e9 mis \u00e0 jour. Nous devons actualiser le panneau actuel. Voulez-vous appliquer l'autocollant s\u00e9lectionn\u00e9\u00a0?",
        "Set color": "R\u00e9gler la couleur",
        "Always Sunny": "Plein soleil",
        Confirm: "Confirmer",
        Siesta: "Sieste",
        Negative: "N\u00e9gatif",
        Send: "Envoyer",
        "Keep editing": "Continuer l\u2019\u00e9dition",
        "Powered by Aviary.com": "Propuls\u00e9 par Aviary.com",
        Zoom: "Zoom",
        Editor: "Editeur",
        "Biggest size": "Taille la plus grande",
        "Soft Focus": "Soft focus",
        Save: "Sauver",
        "Are you sure?": "Etes-vous s\u00fbr\u00a0?",
        Warmth: "Chaleur",
        More: "Autre",
        Meme: "Meme",
        Charcoal: "Anthracite",
        Malibu: "Malibu",
        Grunge: "Grunge",
        "Tool Selection": "S\u00e9lection d\u2019outil",
        Auto: "Auto",
        Tool: "Outil",
        Settings: "Param\u00e8tres",
        Eddie: "Eddie",
        Cinematic: "Cinematic",
        "Medium size": "Taille moyenne",
        Store: "Boutique",
        Backlit: "R\u00e9tro\u00e9clair\u00e9",
        Fixie: "Fixie",
        "Are you sure you want to discard changes from this tool?": "\u00cates-vous s\u00fbr de vouloir supprimer les modifications effectu\u00e9es avec cet outil\u00a0?",
        Brightness: "Luminosit\u00e9",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "Attendez! Vous n'avez pas enregistr\u00e9 votre travail. \u00cates-vous certain de vouloir fermer cet \u00e9diteur ?",
        Smooth: "Lisser",
        "Get this editor": "Obtenir cet \u00e9diteur",
        Draw: "Dessin",
        Flip: "Sym\u00e9trie",
        "Soft Brushes": "Pinceau souple",
        "View Image": "Afficher l'image",
        Viewfinder: "Viseur",
        "Your work was saved!": "Sauvegarde effectu\u00e9e!",
        "Small size": "Petite taille",
        Delete: "Supprimer",
        Square: "Carr\u00e9",
        Rounded: "Arrondi",
        Redo: "R\u00e9tablir",
        "Preset Sizes": "Format",
        Sharpness: "Nettet\u00e9",
        Back: "Retour",
        "Brush softness": "Duret\u00e9 du pinceau",
        Brush: "Pinceau",
        Mirror: "Mirroir",
        "Edit Bottom Text": "Modifier texte du bas",
        "Photo Editor": "Editeur photo",
        "Maintain proportions": "Conserver les proportions",
        Vivid: "Vif",
        "San Carmen": "San Carmen",
        Retro: "R\u00e9tro",
        Exit: "Sortie",
        Undo: "Annuler",
        "Loading Image...": "Chargement de l'image\u2026",
        Borders: "Limites",
        Contrast: "Contraste",
        "Saving...": "Enregistrement\u2026",
        "Instant!": "Instantan\u00e9!",
        "Choose Color": "Choisir couleur",
        Strato: "Strato",
        Vignette: "Vignette",
        "Zoom Mode": "Mode Zoom",
        "A sticker pack has been updated. We need to reload the current panel.": "Un pack d'autocollants a \u00e9t\u00e9 mis \u00e0 jour. Nous devons actualiser le panneau actuel",
        Vigilante: "Vigilante",
        "Image saved in %1$s. Do you want to see the saved image?": "Image enregistr\u00e9e en %1$s. Voulez-vous voir l'image enregistr\u00e9e\u00a0?",
        "Hard Brushes": "Pinceau dur",
        "Brush size": "Taille du pinceau",
        "Get More": "Obtenir davantage",
        "Color Matrix": "Matrice de couleurs",
        Corners: "Corners",
        Aqua: "Aqua",
        "Output Image Size": "Taille de l'image de sortie",
        Ragged: "Irr\u00e9gulier",
        Ventura: "Ventura",
        Error: "Erreur",
        "You can change this property in the Settings panel.": "Vous pouvez modifier cette propri\u00e9t\u00e9 dans le panneau Param\u00e8tres",
        Kurt: "Kurt",
        Balance: "Equilibre",
        Original: "Original",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "Votre image a \u00e9t\u00e9 temporairement r\u00e9duite. Vous conserverez cependant sa taille originale lors de la sauvegarde.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "\u00c9voquez les souvenirs imp\u00e9rissables et les bons moments gr\u00e2ce \u00e0 nos six effets de nostalgie r\u00eaveuse.",
        "Oops, there was an error while saving the image.": "Oups, erreur dans l'enregistrement de l'image.",
        Orientation: "Orientation",
        "Add Text": "+ texte",
        Classic: "Classique",
        Text: "Texte",
        "No stickers defined in Feather_Stickers.": "Aucun autocollants d\u00e9fini dans Feather_Stickers",
        "Drag corners to resize crop area": "Faites glisser les coins pour redimensionner la zone de recadrage",
        "Give feedback": "Donner son avis",
        "Get this pack!": "Obtenir ce pack\u00a0!",
        Height: "Hauteur",
        Colors: "Couleurs",
        Done: "Termin\u00e9",
        "See your world a little differently with these six high-tech camera effects.": "Regardez le monde un peu diff\u00e9remment avec ces six effets hi-tech d'appareil photo.",
        Cancel: "Annuler",
        Close: "Fermer",
        "Width and height must be greater than zero and less than the maximum({max}px)": "La largeur et la hauteur doivent \u00eatre sup\u00e9rieures \u00e0 z\u00e9ro et inf\u00e9rieures \u00e0 la valeur maximale ({max}\u00a0px)",
        "Leave editor": "Quitter l'\u00e9diteur",
        Size: "Dimension",
        "e-mail address": "Adresse courriel",
        "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!": "Ooooups\u00a0! J'ai plant\u00e9, mais un rapport a \u00e9t\u00e9 envoy\u00e9 \u00e0 mon d\u00e9veloppeur pour l'aider \u00e0 r\u00e9soudre le probl\u00e8me\u00a0!",
        Fade: "S'effacer",
        Min: "Min",
        Cherry: "Cerise",
        "Are you sure? This can distort your image": "Confirmez-vous ce changement\u00a0? L'image risque d'\u00eatre d\u00e9form\u00e9e",
        "A sticker pack has been updated. Click ok to reload the packs list.": "Un pack d'autocollants a \u00e9t\u00e9 mis \u00e0 jour. Cliquez sur ok pour actualiser la liste du pack",
        Custom: "Perso",
        Eraser: "Gomme",
        Singe: "Singe",
        Drifter: "D\u00e9riveur",
        Saturation: "Saturation",
        "Crop again": "Recadrer \u00e0 nouveau",
        "Aviary Editor": "Aviary Editor",
        "Applying action %2$i of %2$i": "Action %2$i de %2$i en cours d'ex\u00e9cution",
        Max: "Max",
        Attention: "Attention",
        Redeye: "Suppr YR",
        Halftone: "Demi-teinte",
        "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?": "Oups, erreur en essayant d'enregistrer l'image dans le dossier Aviary. Voulez-vous essayer de l'enregistrer dans le dossier d'appareil photo par d\u00e9faut\u00a0?",
        Pinch: "Contraction",
        "Old Photo": "Vieillissement",
        Laguna: "Laguna",
        Resize: "Taille",
        "Powered by": "Aliment\u00e9 par",
        "Color Grading": "\u00c9talonnage couleur",
        Firefly: "Luciole",
        Rotate: "Pivoter",
        "Applying effects": "Application des effets en cours",
        Daydream: "R\u00eave du jour",
        "Enter text here": "Entrez le texte ici",
        "Code Red": "Code Rouge",
        "Interested? We'll send you some info.": "Int\u00e9ress\u00e9s\u00a0? Nous pouvons vous envoyer des informations.",
        Remove: "Supprimer",
        Concorde: "Concorde",
        "Vignette Blur": "Vignettage flou",
        "About this editor": "\u00c0 propos de cet \u00e9diteur",
        Discard: "Supprimer",
        "Film Grain": "Grain photo",
        Power: "Puissance",
        Color: "Couleur",
        Demo: "D\u00e9mo",
        Crop: "Recadrage",
        "Edit Top Text": "Modifier texte de t\u00eate",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "Vous allez perdre les changements faits \u00e0 cet outil. Voulez-vous quitter\u00a0?",
        Apply: "Valider",
        Stickers: "Stickers"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.he = {
        Loading: "\u05d8\u05d5\u05e2\u05df",
        "Sorry, you must update the Aviary editor to use these effects.": "\u05e2\u05dc\u05d9\u05da \u05dc\u05e2\u05d3\u05db\u05df \u05d0\u05ea \u05e2\u05d5\u05e8\u05da Aviary \u05db\u05d3\u05d9 \u05dc\u05d4\u05e9\u05ea\u05de\u05e9 \u05d1\u05d0\u05e4\u05e7\u05d8\u05d9\u05dd \u05d0\u05dc\u05d5.",
        "Toy Camera": "Toy Camera",
        Night: "\u05dc\u05d9\u05dc\u05d4",
        Nostalgia: "Nostalgia",
        Aviary: "Aviary",
        "Mark II": "Mark II",
        Width: "\u05e8\u05d5\u05d7\u05d1",
        "No effects found for this pack.": "\u05dc\u05d0 \u05e0\u05de\u05e6\u05d0\u05d5 \u05d0\u05e4\u05e7\u05d8\u05d9\u05dd \u05d1\u05d7\u05d1\u05d9\u05dc\u05d4 \u05d6\u05d5",
        Blur: "\u05d8\u05e9\u05d8\u05d5\u05e9",
        "Your image was cropped.": "\u05ea\u05de\u05d5\u05e0\u05ea\u05da \u05e0\u05d7\u05ea\u05db\u05d4",
        Sharpen: "\u05d7\u05d3 \u05d9\u05d5\u05ea\u05e8",
        "Learn more!": "\u05d0\u05e0\u05d9 \u05e8\u05d5\u05e6\u05d4 \u05dc\u05dc\u05de\u05d5\u05d3 \u05e2\u05d5\u05d3",
        Indiglow: "Indiglow",
        "There is another image editing window open.  Close it without saving and continue?": "\u05d9\u05e9\u05e0\u05d5 \u05d7\u05dc\u05d5\u05df \u05e2\u05e8\u05d9\u05db\u05ea \u05ea\u05de\u05d5\u05e0\u05d4 \u05e0\u05d5\u05e1\u05e3. \u05d4\u05d0\u05dd \u05dc\u05e1\u05d2\u05d5\u05e8 \u05d0\u05d5\u05ea\u05d5 \u05de\u05d1\u05dc\u05d9 \u05dc\u05e9\u05de\u05d5\u05e8 \u05d5\u05dc\u05d4\u05de\u05e9\u05d9\u05da \u05db\u05d0\u05df?",
        Resume: "\u05d4\u05de\u05e9\u05da",
        Heatwave: "Heatwave",
        "A filter pack has been updated. Click ok to reload the packs list.": "\u05d9\u05e9\u05e0\u05d5 \u05e2\u05d3\u05db\u05d5\u05df \u05dc\u05d7\u05d1\u05d9\u05dc\u05ea \u05e4\u05d9\u05dc\u05d8\u05e8\u05d9\u05dd. \u05dc\u05d7\u05e6/\u05d9 \u05e2\u05dc \u05d0\u05d9\u05e9\u05d5\u05e8 \u05db\u05d3\u05d9 \u05dc\u05e8\u05e2\u05e0\u05df \u05d0\u05ea \u05e8\u05e9\u05d9\u05de\u05ea \u05d4\u05d7\u05d1\u05d9\u05dc\u05d5\u05ea.",
        Update: "\u05e2\u05d9\u05d3\u05db\u05d5\u05df",
        Free: "\u05dc\u05dc\u05d0 \u05ea\u05e9\u05dc\u05d5\u05dd",
        "There was an error downloading the image, please try again later.": "\u05dc\u05d4 \u05ea\u05e7\u05dc\u05d4 \u05d1\u05de\u05d4\u05dc\u05da \u05d4\u05d5\u05e8\u05d3\u05ea \u05d4\u05ea\u05de\u05d5\u05e0\u05d4. \u05d0\u05e0\u05d0 \u05e0\u05e1\u05d4/\u05d9 \u05e9\u05e0\u05d9\u05ea \u05de\u05d0\u05d5\u05d7\u05e8 \u05d9\u05d5\u05ea\u05e8.",
        Effects: "\u05d0\u05e4\u05e7\u05d8\u05d9\u05dd",
        "Sorry, there's no application on your phone to handle this action.": "\u05d0\u05d9\u05e0\u05e0\u05d5 \u05de\u05d5\u05e6\u05d0\u05d9\u05dd \u05d0\u05e4\u05dc\u05d9\u05e7\u05e6\u05d9\u05d4 \u05e2\u05dc \u05d4\u05de\u05db\u05e9\u05d9\u05e8 \u05e9\u05dc\u05da \u05e9\u05d9\u05db\u05d5\u05dc\u05d4 \u05dc\u05d1\u05e6\u05e2 \u05d0\u05ea \u05d4\u05e4\u05e2\u05d5\u05dc\u05d4 \u05d4\u05e0\u05d3\u05e8\u05e9\u05ea.",
        Vogue: "Vogue",
        Tools: "\u05db\u05dc\u05d9\u05dd",
        "Don't ask me again": "\u05d0\u05dc \u05ea\u05e9\u05d0\u05dc \u05d0\u05d5\u05ea\u05d9 \u05e9\u05d5\u05d1",
        Reset: "\u05d0\u05d9\u05e4\u05d5\u05e1",
        "File saved": "\u05d4\u05e7\u05d5\u05d1\u05e5 \u05e0\u05e9\u05de\u05e8",
        Blemish: "\u05db\u05ea\u05dd",
        Chrono: "Chrono",
        Bulge: "\u05d1\u05dc\u05d9\u05d8\u05d4",
        Alice: "Alice",
        "Destination folder": "\u05ea\u05d9\u05e7\u05d9\u05d9\u05ea \u05d9\u05e2\u05d3",
        "Sorry, you must update the effect pack to continue.": "\u05e2\u05dc\u05d9\u05da \u05dc\u05e2\u05d3\u05db\u05df \u05d0\u05ea \u05d7\u05d1\u05d9\u05dc\u05ea \u05d4\u05d0\u05e4\u05e7\u05d8\u05d9\u05dd \u05e2\u05dc \u05de\u05e0\u05ea \u05dc\u05d4\u05de\u05e9\u05d9\u05da.",
        "Original size": "\u05d2\u05d5\u05d3\u05dc \u05de\u05e7\u05d5\u05e8\u05d9",
        "Are you sure you want to remove this sticker?": "\u05d4\u05d0\u05dd \u05d0\u05ea/\u05d4 \u05d1\u05d8\u05d5\u05d7/\u05d4 \u05e9\u05d1\u05e8\u05e6\u05d5\u05e0\u05da \u05dc\u05d4\u05e1\u05d9\u05e8 \u05d0\u05ea \u05d4\u05de\u05d3\u05d1\u05e7\u05d4 \u05d4\u05d6\u05d5?",
        "Revert to original?": "\u05d4\u05d7\u05d6\u05e8 \u05dc\u05de\u05e7\u05d5\u05e8?",
        Mohawk: "Mohawk",
        Enhance: "\u05e9\u05d9\u05e4\u05d5\u05e8",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary \u05d4\u05d5\u05d0 SDK \u05d7\u05d9\u05e0\u05de\u05d9 \u05e9\u05d6\u05de\u05d9\u05df \u05dc iOS \u05d5\u05dc\u05d0\u05e0\u05d3\u05e8\u05d5\u05d0\u05d9\u05d3, \u05d5\u05de\u05d0\u05e4\u05e9\u05e8 \u05dc\u05d4\u05d5\u05e1\u05d9\u05e3 \u05d9\u05db\u05d5\u05dc\u05d5\u05ea \u05e2\u05e8\u05d9\u05db\u05ea \u05ea\u05de\u05d5\u05e0\u05d4 \u05d5\u05d0\u05e4\u05e7\u05d8\u05d9\u05dd \u05d1\u05d0\u05de\u05e6\u05e2\u05d5\u05ea \u05e9\u05d5\u05e8\u05d5\u05ea \u05e7\u05d5\u05d3 \u05de\u05e2\u05d8\u05d5\u05ea.",
        Greeneye: "\u05e2\u05d9\u05df \u05d9\u05e8\u05d5\u05e7\u05d4",
        Shadow: "\u05e6\u05dc",
        "Unknown error": "\u05e9\u05d2\u05d9\u05d0\u05d4 \u05dc\u05d0 \u05d9\u05d3\u05d5\u05e2\u05d4",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "\u05d0\u05ea/\u05d4 \u05e2\u05d5\u05de\u05d3/\u05ea \u05dc\u05d0\u05d1\u05d3 \u05d0\u05ea \u05db\u05dc \u05d4\u05e9\u05d9\u05e0\u05d5\u05d9\u05d9\u05dd \u05e9\u05d1\u05d9\u05e6\u05e2\u05ea. \u05d4\u05d0\u05dd \u05d0\u05ea/\u05d4 \u05d1\u05d8\u05d5\u05d7/\u05d4 \u05e9\u05d1\u05e8\u05e6\u05d5\u05e0\u05da \u05dc\u05d7\u05d6\u05d5\u05e8 \u05dc\u05ea\u05de\u05d5\u05e0\u05d4 \u05d4\u05de\u05e7\u05d5\u05e8\u05d9\u05ea?",
        OK: "\u05d0\u05d5\u05e7\u05d9\u05d9",
        "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?": "\u05d0\u05d9\u05e0\u05e0\u05d5 \u05de\u05d5\u05e6\u05d0\u05d9\u05dd \u05d0\u05e4\u05dc\u05d9\u05e7\u05e6\u05d9\u05d4 \u05e2\u05dc \u05d4\u05de\u05db\u05e9\u05d9\u05e8 \u05e9\u05dc\u05da \u05e9\u05d9\u05db\u05d5\u05dc\u05d4 \u05dc\u05d1\u05e6\u05e2 \u05d0\u05ea \u05d4\u05e4\u05e2\u05d5\u05dc\u05d4 \u05d4\u05e0\u05d3\u05e8\u05e9\u05ea. \u05d4\u05d0\u05dd \u05d1\u05e8\u05e6\u05d5\u05e0\u05da \u05dc\u05d4\u05d5\u05e8\u05d9\u05d3 \u05d0\u05d5\u05ea\u05d4 \u05de\u05d7\u05e0\u05d5\u05ea \u05d4\u05d0\u05e4\u05dc\u05d9\u05e7\u05e6\u05d9\u05d5\u05ea?",
        Intensity: "\u05d0\u05d9\u05e0\u05d8\u05e0\u05e1\u05d9\u05d1\u05d9\u05d5\u05ea",
        Whiten: "\u05d4\u05dc\u05d1\u05df",
        Frames: "\u05de\u05e1\u05d2\u05e8\u05d5\u05ea",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "\u05d4\u05d5\u05e1\u05e4\u05ea \u05d7\u05d9\u05e1\u05e4\u05d5\u05e1 \u05d5\u05de\u05e8\u05d0\u05d4 \u05d1\u05dc\u05d5\u05d9 \u05dc\u05ea\u05de\u05d5\u05e0\u05d5\u05ea \u05e2\u05dd \u05e9\u05e9\u05ea \u05d0\u05e4\u05e7\u05d8\u05d9 \u05d4\u05d2\u05e8\u05d0\u05e0\u05d2' \u05d4\u05d0\u05dc\u05d4.",
        "Delete selected": "\u05de\u05d7\u05e7 \u05d1\u05d7\u05d9\u05e8\u05d4",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "\u05d7\u05d1\u05d9\u05dc\u05ea \u05de\u05d3\u05d1\u05e7\u05d5\u05ea \u05e2\u05d5\u05d3\u05db\u05e0\u05d4, \u05d5\u05e6\u05e8\u05d9\u05da \u05dc\u05d8\u05e2\u05d5\u05df \u05e9\u05d5\u05d1 \u05d0\u05ea \u05d4\u05dc\u05d5\u05d7 \u05d4\u05e0\u05d5\u05db\u05d7\u05d9. \u05d4\u05d0\u05dd \u05d1\u05e8\u05e6\u05d5\u05e0\u05da \u05dc\u05d4\u05d3\u05d1\u05d9\u05e7 \u05d0\u05ea \u05d4\u05de\u05d3\u05d1\u05e7\u05d4 \u05d4\u05d6\u05d5?",
        "Always Sunny": "Always Sunny",
        Confirm: "\u05d0\u05d9\u05e9\u05d5\u05e8",
        Siesta: "Siesta",
        Reflex: "Reflex",
        Negative: "Negative",
        Send: "\u05e9\u05dc\u05d7",
        "Keep editing": "\u05d4\u05de\u05e9\u05da \u05d1\u05e2\u05e8\u05d9\u05db\u05d4",
        "Powered by Aviary.com": "\u05e8\u05e5 \u05e2\u05dc Aviary.com",
        Zoom: "\u05d6\u05d5\u05dd",
        "Sorry, there's no file manager installed on your phone to handle this action. Do you want to download one now from the market?": "\u05d0\u05d9\u05e0\u05e0\u05d5 \u05de\u05d5\u05e6\u05d0\u05d9\u05dd \u05de\u05e0\u05d4\u05dc \u05e7\u05d1\u05e6\u05d9\u05dd \u05e2\u05dc \u05d4\u05de\u05db\u05e9\u05d9\u05e8 \u05e9\u05dc\u05da \u05e9\u05d9\u05db\u05d5\u05dc \u05dc\u05d1\u05e6\u05e2 \u05d0\u05ea \u05d4\u05e4\u05e2\u05d5\u05dc\u05d4 \u05d4\u05e0\u05d3\u05e8\u05e9\u05ea. \u05d4\u05d0\u05dd \u05d1\u05e8\u05e6\u05d5\u05e0\u05da \u05dc\u05d4\u05d5\u05e8\u05d9\u05d3 \u05d0\u05d5\u05ea\u05d5 \u05de\u05d7\u05e0\u05d5\u05ea \u05d4\u05d0\u05e4\u05dc\u05d9\u05e7\u05e6\u05d9\u05d5\u05ea?",
        Editor: "\u05e2\u05d5\u05e8\u05da",
        "Biggest size": "\u05d4\u05d2\u05d5\u05d3\u05dc \u05d4\u05de\u05e7\u05e1\u05d9\u05de\u05dc\u05d9",
        "Soft Focus": "Soft Focus",
        Save: "\u05e9\u05de\u05d9\u05e8\u05d4",
        "Are you sure?": "\u05d4\u05d0\u05dd \u05d0\u05ea/\u05d4 \u05d1\u05d8\u05d5\u05d7/\u05d4?",
        Warmth: "\u05d7\u05d5\u05dd",
        More: "\u05e2\u05d5\u05d3",
        Meme: "\u05de\u05dd",
        Malibu: "Malibu",
        Grunge: "Grunge",
        "Tool Selection": "\u05d1\u05d7\u05d9\u05e8\u05ea \u05db\u05dc\u05d9",
        Auto: "\u05d0\u05d5\u05d8\u05d5\u05de\u05d8\u05d9",
        Tool: "\u05db\u05dc\u05d9",
        Settings: "\u05d4\u05d2\u05d3\u05e8\u05d5\u05ea",
        Eddie: "Eddie",
        Cinematic: "Cinematic",
        "Medium size": "\u05d2\u05d5\u05d3\u05dc \u05de\u05d3\u05d9\u05d5\u05dd",
        Store: "\u05d7\u05e0\u05d5\u05ea",
        Backlit: "\u05ea\u05d0\u05d5\u05e8\u05d4 \u05d0\u05d7\u05d5\u05e8\u05d9\u05ea",
        "Are you sure you want to discard changes from this tool?": "it's a name, sticking to it is ok in hebrew",
        Brightness: "\u05d1\u05d4\u05d9\u05e8\u05d5\u05ea",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "\u05e8\u05d2\u05e2! \u05dc\u05d0 \u05e9\u05de\u05e8\u05ea \u05d0\u05ea \u05d4\u05e7\u05d5\u05d1\u05e5! \u05d4\u05d0\u05dd \u05d0\u05ea/\u05d4 \u05d1\u05d8\u05d5\u05d7/\u05d4 \u05e9\u05d1\u05e8\u05e6\u05d5\u05e0\u05da \u05dc\u05e1\u05d2\u05d5\u05e8 \u05d0\u05ea \u05d4\u05e2\u05d5\u05e8\u05da?",
        Smooth: "\u05dc\u05d4\u05d7\u05dc\u05d9\u05e7",
        "Get this editor": "\u05d0\u05e0\u05d9 \u05e8\u05d5\u05e6\u05d4 \u05d0\u05ea \u05d4\u05e2\u05d5\u05e8\u05da \u05d4\u05d6\u05d4!",
        Draw: "\u05e6\u05d9\u05d9\u05e8",
        Flip: "\u05d4\u05e4\u05d5\u05da",
        "Soft Brushes": "\u05de\u05d1\u05e8\u05e9\u05d5\u05ea \u05e8\u05db\u05d5\u05ea",
        "View Image": "\u05e6\u05e4\u05d9\u05d9\u05d4 \u05d1\u05ea\u05de\u05d5\u05e0\u05d4",
        Viewfinder: "\u05de\u05e6\u05dc\u05de\u05d4 \u05e2\u05ea\u05d9\u05e7\u05d4",
        "Your work was saved!": "\u05d4\u05e9\u05d9\u05e0\u05d5\u05d9\u05d9\u05dd \u05e0\u05e9\u05de\u05e8\u05d5!",
        "Small size": "\u05d2\u05d5\u05d3\u05dc \u05e7\u05d8\u05df",
        Delete: "\u05de\u05d7\u05e7",
        Square: "\u05e8\u05d9\u05d1\u05d5\u05e2",
        Redo: "\u05d1\u05e6\u05e2 \u05e9\u05d5\u05d1",
        "Preset Sizes": "\u05d2\u05d3\u05dc\u05d9\u05dd \u05e7\u05d1\u05d5\u05e2\u05d9\u05dd \u05de\u05e8\u05d0\u05e9",
        Sharpness: "\u05d7\u05d3\u05d5\u05ea",
        Back: "\u05d7\u05d6\u05d5\u05e8",
        "Brush softness": "\u05e8\u05db\u05d5\u05ea \u05d4\u05de\u05d1\u05e8\u05e9\u05ea",
        Periscope: "Periscope",
        Brush: "\u05de\u05d1\u05e8\u05e9\u05ea",
        Mirror: "\u05de\u05e8\u05d0\u05d4",
        "Edit Bottom Text": "\u05e2\u05e8\u05d9\u05db\u05ea \u05d8\u05e7\u05e1\u05d8 \u05ea\u05d7\u05ea\u05d5\u05df",
        "Photo Editor": "\u05e2\u05d5\u05e8\u05da \u05ea\u05de\u05d5\u05e0\u05d5\u05ea",
        "Maintain proportions": "\u05e9\u05de\u05d5\u05e8 \u05e2\u05dc \u05e4\u05e8\u05d5\u05e4\u05d5\u05e8\u05e6\u05d9\u05d5\u05ea",
        Vivid: "Vivid",
        "San Carmen": "San Carmen",
        Retro: "Retro",
        "Sorry, there was an error loading the effect pack": "\u05d7\u05dc\u05d4 \u05e9\u05d2\u05d9\u05d0\u05d4 \u05d1\u05d8\u05e2\u05d9\u05e0\u05ea \u05d7\u05d1\u05d9\u05dc\u05ea \u05d4\u05d0\u05e4\u05e7\u05d8\u05d9\u05dd, \u05e2\u05de\u05db\u05dd \u05d4\u05e1\u05dc\u05d9\u05d7\u05d4.",
        Exit: "\u05d9\u05e6\u05d9\u05d0\u05d4",
        Undo: "\u05d1\u05d8\u05dc",
        "Loading Image...": "\u05d8\u05d5\u05e2\u05df \u05ea\u05de\u05d5\u05e0\u05d4",
        Borders: "\u05d2\u05d1\u05d5\u05dc\u05d5\u05ea",
        Contrast: "\u05e0\u05d9\u05d2\u05d5\u05d3",
        "Saving...": "\u05e9\u05d5\u05de\u05e8",
        "Instant!": "Instant!",
        "Choose Color": "\u05d1\u05d7\u05d9\u05e8\u05ea \u05e6\u05d1\u05e2",
        Strato: "Strato",
        "Zoom Mode": "\u05de\u05e6\u05d1 \u05d6\u05d5\u05dd",
        "A sticker pack has been updated. We need to reload the current panel.": "\u05d9\u05e9\u05e0\u05d5 \u05e2\u05d3\u05db\u05d5\u05df \u05dc\u05d7\u05d1\u05d9\u05dc\u05ea \u05de\u05d3\u05d1\u05e7\u05d5\u05ea. \u05d4\u05de\u05e2\u05e8\u05db\u05ea \u05e6\u05e8\u05d9\u05db\u05d4 \u05dc\u05d8\u05e2\u05d5\u05df \u05de\u05d7\u05d3\u05e9 \u05d0\u05ea \u05d4\u05dc\u05d5\u05d7 \u05d4\u05e0\u05d5\u05db\u05d7\u05d9.",
        Vigilante: "Vigilante",
        "Image saved in %1$s. Do you want to see the saved image?": "\u05d4\u05ea\u05de\u05d5\u05e0\u05d4 \u05e0\u05e9\u05de\u05e8\u05d4 \u05d1 %1$s. \u05d4\u05d0\u05dd \u05d1\u05e8\u05e6\u05d5\u05e0\u05da \u05dc\u05e8\u05d0\u05d5\u05ea \u05d0\u05ea \u05d4\u05ea\u05de\u05d5\u05e0\u05d4 \u05d4\u05e9\u05de\u05d5\u05e8\u05d4?",
        "Hard Brushes": "\u05de\u05d1\u05e8\u05e9\u05d5\u05ea \u05e7\u05e9\u05d5\u05ea",
        "Brush size": "\u05d2\u05d5\u05d3\u05dc \u05de\u05d1\u05e8\u05e9\u05ea",
        "Get More": "\u05e2\u05d5\u05d3",
        "Color Matrix": "Color Matrix",
        Corners: "\u05e4\u05d9\u05e0\u05d5\u05ea",
        Aqua: "Aqua",
        "Output Image Size": "\u05d2\u05d5\u05d3\u05dc \u05ea\u05de\u05d5\u05e0\u05ea \u05d4\u05e4\u05dc\u05d8",
        Ventura: "Ventura",
        Error: "\u05e9\u05d2\u05d9\u05d0\u05d4",
        "You can change this property in the Settings panel.": "\u05e0\u05d9\u05ea\u05df \u05dc\u05e9\u05e0\u05d5\u05ea \u05de\u05d0\u05e4\u05d9\u05d9\u05df \u05d6\u05d4 \u05d1\u05dc\u05d5\u05d7 \u05d4\u05d4\u05d2\u05d3\u05e8\u05d5\u05ea",
        Kurt: "Kurt",
        Balance: "\u05d0\u05d9\u05d6\u05d5\u05df \u05e6\u05d1\u05e2",
        Original: "Original",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "\u05db\u05d9\u05d5\u05d5\u05e6\u05e0\u05d5 \u05d0\u05ea \u05ea\u05de\u05d5\u05e0\u05ea\u05da \u05db\u05d3\u05d9 \u05dc\u05d4\u05e7\u05dc \u05e2\u05dc \u05e2\u05d1\u05d5\u05d3\u05ea \u05d4\u05e2\u05e8\u05d9\u05db\u05d4. \u05dc\u05db\u05e9\u05ea\u05dc\u05d7\u05e5/\u05d9 \u05e2\u05dc \u05e9\u05de\u05d5\u05e8, \u05d9\u05d9\u05e9\u05de\u05e8 \u05d4\u05d2\u05d5\u05d3\u05dc \u05d4\u05de\u05dc\u05d0.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "\u05e9\u05d9\u05e9\u05d4 \u05d0\u05e4\u05e7\u05d8\u05d9\u05d9\u05dd \u05e0\u05d5\u05e1\u05d8\u05dc\u05d2\u05d9\u05d9\u05dd \u05d5\u05d7\u05d5\u05dc\u05de\u05e0\u05d9\u05d9\u05dd \u05e9\u05e2\u05d5\u05d8\u05e4\u05d9\u05dd \u05d0\u05ea \u05d4\u05ea\u05de\u05d5\u05e0\u05d5\u05ea \u05d5\u05d9\u05d2\u05e8\u05de\u05d5 \u05dc\u05e2\u05d9\u05e0\u05d9\u05db\u05dd \u05dc\u05d4\u05e6\u05d8\u05e2\u05e3.",
        "Oops, there was an error while saving the image.": "\u05d0\u05d5\u05e4\u05e1, \u05e7\u05e8\u05ea\u05d4 \u05ea\u05e7\u05dc\u05d4 \u05d1\u05d6\u05de\u05df \u05e9\u05de\u05d9\u05e8\u05ea \u05d4\u05ea\u05de\u05d5\u05e0\u05d4",
        Orientation: "\u05d0\u05d5\u05e8\u05d9\u05d9\u05e0\u05d8\u05e6\u05d9\u05d4",
        "Add Text": "\u05d4\u05d5\u05e1\u05e4\u05ea \u05d8\u05e7\u05e1\u05d8.",
        Classic: "\u05e7\u05dc\u05d0\u05e1\u05d9",
        Text: "\u05d8\u05e7\u05e1\u05d8",
        "No stickers defined in Feather_Stickers.": "\u05d0\u05d9\u05df \u05de\u05d3\u05d1\u05e7\u05d5\u05ea \u05de\u05d5\u05d2\u05d3\u05e8\u05d5\u05ea \u05d1Feather_Stickers",
        "Drag corners to resize crop area": "\u05d2\u05e8\u05d5\u05e8/\u05d9 \u05d0\u05ea \u05d4\u05e4\u05d9\u05e0\u05d5\u05ea \u05db\u05d3\u05d9 \u05dc\u05e9\u05e0\u05d5\u05ea \u05d0\u05ea \u05d2\u05d5\u05d3\u05dc \u05d0\u05d6\u05d5\u05e8 \u05d4\u05d7\u05d9\u05ea\u05d5\u05da",
        "Give feedback": "\u05ea\u05e0\u05d5 \u05de\u05e9\u05d5\u05d1",
        "Get this pack!": "\u05d0\u05e0\u05d9 \u05e8\u05d5\u05e6\u05d4 \u05d0\u05ea \u05d4\u05d7\u05d1\u05d9\u05dc\u05d4 \u05d4\u05d6\u05d5!",
        Height: "\u05d2\u05d5\u05d1\u05d4",
        Colors: "\u05e6\u05d1\u05e2\u05d9\u05dd",
        Done: "\u05d1\u05d5\u05e6\u05e2",
        Fixie: "Fixie",
        Covert: "Covert",
        Cancel: "\u05d1\u05d9\u05d8\u05d5\u05dc",
        Close: "\u05e1\u05d2\u05d9\u05e8\u05d4",
        "Width and height must be greater than zero and less than the maximum({max}px)": "\u05d4\u05d2\u05d5\u05d1\u05d4 \u05d5\u05d4\u05e8\u05d5\u05d7\u05d1 \u05d7\u05d9\u05d9\u05d1\u05d9\u05dd \u05dc\u05d4\u05d9\u05d5\u05ea \u05d2\u05d3\u05d5\u05dc\u05d9\u05dd \u05de\u05d0\u05e4\u05e1, \u05d5\u05e4\u05d7\u05d5\u05ea \u05de({max} \u05e4\u05d9\u05e7\u05e1\u05dc\u05d9\u05dd)",
        "Leave editor": "\u05e6\u05d0 \u05de\u05d4\u05e2\u05d5\u05e8\u05da",
        Size: "\u05d2\u05d5\u05d3\u05dc",
        "e-mail address": "\u05db\u05ea\u05d5\u05d1\u05ea email",
        "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!": "\u05d0\u05d5\u05e4\u05e1! \u05e7\u05e8\u05e1\u05ea\u05d9, \u05d0\u05d1\u05dc \u05e9\u05dc\u05d7\u05ea\u05d9 \u05de\u05d9\u05d3\u05e2 \u05dc\u05de\u05e4\u05ea\u05d7 \u05e9\u05d0\u05d7\u05e8\u05d0\u05d9 \u05e2\u05dc\u05d9 \u05db\u05d3\u05d9 \u05dc\u05e2\u05d6\u05d5\u05e8 \u05dc\u05d5 \u05dc\u05e4\u05ea\u05d5\u05e8 \u05d0\u05ea \u05d4\u05d1\u05e2\u05d9\u05d4!",
        Fade: "\u05dc\u05d3\u05e2\u05d5\u05da",
        Min: "\u05de\u05d9\u05e0\u05d9\u05de\u05d5\u05dd",
        Cherry: "Cherry",
        "Are you sure? This can distort your image": "\u05d4\u05d0\u05dd \u05d0\u05ea/\u05d4 \u05d1\u05d8\u05d5\u05d7/\u05d4? \u05d6\u05d4 \u05e2\u05dc\u05d5\u05dc \u05dc\u05e2\u05d5\u05d5\u05ea \u05d0\u05ea \u05ea\u05de\u05d5\u05e0\u05ea\u05da",
        "A sticker pack has been updated. Click ok to reload the packs list.": "\u05d9\u05e9\u05e0\u05d5 \u05e2\u05d3\u05db\u05d5\u05df \u05dc\u05d7\u05d1\u05d9\u05dc\u05ea \u05de\u05d3\u05d1\u05e7\u05d5\u05ea. \u05dc\u05d7\u05e6/\u05d9 \u05e2\u05dc \u05d0\u05d9\u05e9\u05d5\u05e8 \u05db\u05d3\u05d9 \u05dc\u05e8\u05e2\u05e0\u05df \u05d0\u05ea \u05e8\u05e9\u05d9\u05de\u05ea \u05d4\u05de\u05d3\u05d1\u05e7\u05d5\u05ea.",
        Custom: "\u05d4\u05ea\u05d0\u05dd",
        Eraser: "\u05de\u05d7\u05e7",
        Singe: "Singe",
        Drifter: "Drifter",
        Saturation: "\u05e8\u05d5\u05d5\u05d9\u05d4",
        "Crop again": "\u05d7\u05ea\u05d5\u05da \u05e9\u05d5\u05d1",
        "Aviary Editor": "\u05e2\u05d5\u05e8\u05da Aviary",
        "Applying action %2$i of %2$i": "\u05de\u05d1\u05e6\u05e2 \u05e4\u05e2\u05d5\u05dc\u05d4 %2$i \u05de\u05ea\u05d5\u05da %2$i",
        Max: "\u05de\u05e7\u05e1",
        Attention: "\u05e9\u05d9\u05de/\u05d9 \u05dc\u05d1",
        Redeye: "\u05e2\u05d9\u05df \u05d0\u05d3\u05d5\u05de\u05d4",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "\u05d0\u05ea/\u05d4 \u05e2\u05d5\u05de\u05d3/\u05ea \u05dc\u05d0\u05d1\u05d3 \u05d0\u05ea \u05db\u05dc \u05d4\u05e9\u05d9\u05e0\u05d5\u05d9\u05d9\u05dd \u05e9\u05d1\u05d9\u05e6\u05e2\u05ea \u05d1\u05db\u05dc\u05d9 \u05d6\u05d4. \u05d4\u05d0\u05dd \u05d0\u05ea/\u05d4 \u05d1\u05d8\u05d5\u05d7/\u05d4 \u05e9\u05d1\u05e8\u05e6\u05d5\u05e0\u05da \u05dc\u05e2\u05d6\u05d5\u05d1?",
        "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?": "\u05d0\u05d5\u05e4\u05e1! \u05e7\u05e8\u05ea\u05d4 \u05ea\u05e7\u05dc\u05d4 \u05db\u05e9\u05e0\u05d9\u05e1\u05d9\u05ea\u05d9 \u05dc\u05e9\u05de\u05d5\u05e8 \u05d0\u05ea \u05d4\u05ea\u05de\u05d5\u05e0\u05d4 \u05dc\u05ea\u05d9\u05e7\u05d9\u05ea Aviary. \u05d4\u05d0\u05dd \u05d1\u05e8\u05e6\u05d5\u05e0\u05da \u05dc\u05e0\u05e1\u05d5\u05ea \u05dc\u05e9\u05de\u05d5\u05e8 \u05d6\u05d0\u05ea \u05dc\u05ea\u05d9\u05e7\u05d9\u05ea \u05d4\u05de\u05e6\u05dc\u05de\u05d4?",
        Pinch: "\u05e6\u05de\u05e6\u05dd",
        "Old Photo": "\u05ea\u05de\u05d5\u05e0\u05d4 \u05d9\u05e9\u05e0\u05d4",
        Laguna: "Laguna",
        Resize: "\u05e9\u05d9\u05e0\u05d5\u05d9 \u05d2\u05d5\u05d3\u05dc",
        "Powered by": "\u05e8\u05e5 \u05e2\u05dc \u05d2\u05d1\u05d9",
        "Color Grading": "Color Grading",
        Firefly: "Firefly",
        Rotate: "\u05e1\u05d9\u05d1\u05d5\u05d1",
        "Applying effects": "\u05de\u05d5\u05e1\u05d9\u05e3 \u05d0\u05e4\u05e7\u05d8\u05d9\u05dd",
        Daydream: "Daydream",
        "Enter text here": "\u05d4\u05d6\u05e0/\u05d9 \u05d8\u05e7\u05e1\u05d8 \u05db\u05d0\u05df",
        "Code Red": "Code Red",
        "Interested? We'll send you some info.": "\u05de\u05e2\u05d5\u05e0\u05d9\u05d9\u05e0/\u05ea? \u05e0\u05e9\u05dc\u05d7 \u05dc\u05da \u05e2\u05d5\u05d3 \u05de\u05d9\u05d3\u05e2.",
        Remove: "\u05de\u05d7\u05d9\u05e7\u05d4",
        Concorde: "Concorde",
        "Vignette Blur": "\u05e9\u05d5\u05dc\u05d9\u05d9\u05dd \u05de\u05e2\u05d5\u05de\u05e2\u05de\u05d9\u05dd",
        "About this editor": "\u05d0\u05d5\u05d3\u05d5\u05ea \u05e2\u05d5\u05e8\u05da \u05d6\u05d4",
        Discard: "\u05d4\u05ea\u05e2\u05dc\u05dd",
        "Film Grain": "\u05d2\u05e8\u05d2\u05e8\u05d9\u05dd",
        Color: "\u05e6\u05d1\u05e2",
        Demo: "\u05d3\u05de\u05d5",
        Crop: "\u05d7\u05ea\u05d5\u05da",
        "Edit Top Text": "\u05e2\u05e8\u05d9\u05db\u05ea \u05d8\u05e7\u05e1\u05d8 \u05e2\u05dc\u05d9\u05d5\u05df",
        Apply: "\u05d1\u05e6\u05e2",
        Stickers: "\u05de\u05d3\u05d1\u05e7\u05d5\u05ea"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.id = {
        Loading: "Memuat",
        "Toy Camera": "Kamera Mainan",
        Night: "Malam",
        Nostalgia: "Nostalgia",
        Aviary: "Aviary",
        Width: "Lebar",
        "No effects found for this pack.": "Tidak ada efek yang ditemukan untuk paket ini",
        Blur: "Buram",
        "Your image was cropped.": "Gambar Anda telah di-crop.",
        Sharpen: "Tajam",
        "Learn more!": "Pelajari lebih lanjut!",
        Ripped: "Robek",
        Indiglow: "Brilian",
        "There is another image editing window open.  Close it without saving and continue?": "Ada jendela pengeditan gambar lainnya yang terbuka. Tutup jendela tersebut tanpa menyimpan dan lanjutkan?",
        Resume: "Lanjutkan",
        Heatwave: "Gelombang panas",
        "A filter pack has been updated. Click ok to reload the packs list.": "Paket filter telah diperbarui Klik ok untuk memuat kembali daftar paket",
        Update: "Memperbarui",
        Free: "Gratis",
        "There was an error downloading the image, please try again later.": "Ada kesalahan saat men-download gambar, silakan coba lagi nanti",
        Effects: "Efek",
        Tools: "Alat",
        Reset: "Reset",
        Blemish: "Belang",
        Bulge: "Berkerut",
        Alice: "Alice",
        "Are you sure you want to remove this sticker?": "Anda yakin ingin menghapus stiker ini?",
        "Revert to original?": "Kembali ke asli?",
        Mohawk: "Mohawk",
        Enhance: "Tingkatkan",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary adalah SDK gratis yang tersedia untuk iOS dan Android yang memungkinkan Anda untuk menambahkan kemampuan pengeditan foto dan efek-efek pada aplikasi Anda hanya dengan beberapa baris kode.",
        Greeneye: "Mata hijau",
        Shadow: "Bayangan",
        Vogue: "Mode",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "Anda akan kehilangan perubahan yang telah Anda buat. Anda yakin ingin mengembalikan ke gambar asli?",
        OK: "OK",
        Intensity: "Intensitas",
        Whiten: "Putihkan",
        Frames: "Frames",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "Tambahkan kesan berdebu dan lusuh pada foto Anda dengan enam efek jadul ini.",
        "Delete selected": "Hapus yang dipilih",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "Paket stiker telah diperbarui. Kami harus memuat ulang panel yang sekarang. Apakah Anda ingin menerapkan stiker yang sekarang?",
        "Always Sunny": "Selalu Cerah",
        Confirm: "Konfirmasi",
        Siesta: "Siesta",
        Negative: "Negatif",
        Send: "Kirim",
        "Keep editing": "Teruslah mengedit",
        "Powered by Aviary.com": "Didukung oleh Aviary.com",
        Zoom: "Perbesaran",
        Editor: "Editor",
        "Soft Focus": "Fokus Lunak",
        Save: "Simpan",
        "Are you sure?": "Anda yakin?",
        Warmth: "Kehangatan",
        More: "Lebih lanjut",
        Meme: "Tanda mata",
        Charcoal: "Arang",
        Malibu: "Malibu",
        Grunge: "Suram",
        "Tool Selection": "Pemilihan Alat",
        Auto: "Otomatis",
        Tool: "Alat",
        Daydream: "Khayalan",
        Eddie: "Eddie",
        Cinematic: "Sinema",
        Store: "Simpan",
        Backlit: "Cahaya latar",
        "Are you sure you want to discard changes from this tool?": "Anda yakin ingin membuang perubahan dari alat ini?",
        Brightness: "Kecerahan",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "Tunggu! Anda tidak menyimpan pekerjaan Anda. Anda yakin ingin menutup edito inir?",
        Smooth: "Halus",
        "Get this editor": "Dapatkan editor ini",
        Draw: "Tarik",
        Flip: "Jentik",
        "Soft Brushes": "Kuas Lembut",
        "Your work was saved!": "Pekerjaan Anda telah disimpan!",
        Delete: "Hapus",
        Square: "Persegi",
        Rounded: "Bulat",
        "Preset Sizes": "Ukuran Preset",
        Sharpness: "Ketajaman",
        Back: "Kembali",
        "Brush softness": "Kelunakan kuas",
        Brush: "Kuas",
        Mirror: "Cermin",
        "Edit Bottom Text": "Edit Teks di Bagian Bawah",
        "Photo Editor": "Editor Foto",
        "Maintain proportions": "Pertahankan proporsi",
        Vivid: "Vivid",
        "San Carmen": "San Carmen",
        Retro: "Retro",
        Exit: "Keluar",
        Undo: "Urungkan",
        "Loading Image...": "Memuat Gambar...",
        Borders: "Batas",
        Contrast: "Kontras",
        "Instant!": "Instan!",
        "Choose Color": "Pilih Warna",
        Strato: "Strato",
        Vignette: "Vinyet",
        "Zoom Mode": "Mode Perbesaran",
        "A sticker pack has been updated. We need to reload the current panel.": "Paket stiker telah diperbarui. Kami harus memuat ulang panel yang sekarang",
        Vigilante: "Vigilante",
        "Hard Brushes": "Kuas Keras",
        "Brush size": "Ukuran kuas",
        "Get More": "Dapatkan Lainnya",
        "Color Matrix": "Matriks Warna",
        Corners: "Corners",
        Aqua: "Aqua",
        Ragged: "Rombeng",
        Ventura: "Ventura",
        Error: "Kesalahan",
        Kurt: "Kurt",
        Balance: "Keseimbangan",
        Original: "Asli",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "Gambar Anda untuk sementara disusutkan agar gambar tersebut menjadi lebih mudah diedit. Bila Anda menekan Simpan, Anda akan menyimpan tampilan dalam ukuran penuh.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "Bernostalgia dengan kenangan indah dan masa-masa yang menyenangkan dengan enam efek nostalgia yang luar biasa.",
        Orientation: "Orientasi",
        "Add Text": "Tambah Teks",
        Classic: "Klasik",
        Text: "Teks",
        "No stickers defined in Feather_Stickers.": "Tidak ada stiker yang didefinisikan di Feather_Stickers",
        "Drag corners to resize crop area": "Tarik bagian sudut untuk mengubah ukuran area crop",
        "Give feedback": "Beri umpan balik",
        "Get this pack!": "Dapatkan paket ini!",
        Height: "Tinggi",
        Colors: "Warna",
        Done: "Selesai",
        Fixie: "Fixie",
        Cancel: "Batal",
        Close: "Tutup",
        "Width and height must be greater than zero and less than the maximum({max}px)": "Lebar dan tinggi harus lebih besar dari nol dan lebih kecil dari maksimum ({max}px)",
        "Leave editor": "Keluar dari editor",
        Size: "Ukuran",
        "e-mail address": "Alamat e-mail",
        Eraser: "Penghapus",
        Min: "Min",
        Cherry: "Ceri",
        "Are you sure? This can distort your image": "Anda yakin? Ini dapat mendistorsi foto Anda",
        "A sticker pack has been updated. Click ok to reload the packs list.": "Paket stiker telah diperbarui. Klik ok untuk memuat kembali daftar paket",
        Custom: "Sesuaikan",
        Fade: "Luntur",
        Singe: "Gosong",
        Drifter: "Pengembara",
        Saturation: "Saturasi",
        "Crop again": "Crop lagi",
        "Aviary Editor": "Aviary Editor",
        Max: "Maks",
        Attention: "Perhatian",
        Redeye: "Mata merah",
        Halftone: "Setengah nada",
        Pinch: "Cubit",
        "Old Photo": "Foto Lama",
        Laguna: "Laguna",
        Resize: "Ubah ukuran",
        "Powered by": "Didukung oleh",
        "Color Grading": "Skala Warna",
        Firefly: "Kunang-kunang",
        Rotate: "Putar",
        "Applying effects": "Mengaplikasikan Efek",
        "Enter text here": "Masukkan teks di sini",
        "Code Red": "Code Red",
        "Interested? We'll send you some info.": "Tertarik? Kami akan mengirimkan informasinya kepada Anda",
        Remove: "Hapus",
        Concorde: "Concorde",
        "Vignette Blur": "Vignette Blur",
        "About this editor": "Tentang editor ini",
        Discard: "Buang",
        "Film Grain": "Tekstur Film",
        Power: "Daya",
        Color: "Warna",
        Demo: "Demo",
        Crop: "Crop",
        "Edit Top Text": "Edit Teks di Bagian Atas",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "Anda akan kehilangan perubahan yang telah Anda buat di alat ini. Anda yakin ingin keluar?",
        Apply: "Aplikasikan",
        Stickers: "Stiker"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.it = {
        Loading: "Caricamento",
        "Toy Camera": "Toy camera",
        Night: "Notte",
        Nostalgia: "Nostalgia",
        Aviary: "Aviary",
        Width: "Larghezza",
        "No effects found for this pack.": "Non ci sono effetti in questo pack",
        Blur: "Disturbo",
        "Your image was cropped.": "La tua immagine \u00e8 stata ritagliata",
        Sharpen: "Migliora",
        "Learn more!": "Maggior informazioni",
        Ripped: "Strappato",
        Indiglow: "Indiglow",
        "There is another image editing window open.  Close it without saving and continue?": "C'\u00e8 un'altra finestra aperta. Vuoi chiuderla senza salvare e continuare?",
        Resume: "Reset",
        Heatwave: "Onda di calore",
        "A filter pack has been updated. Click ok to reload the packs list.": "Un pacchetto di filtri \u00e8 stato aggiornato. Fai clic su OK per ricaricare l\u2019elenco dei pacchetti",
        Update: "Aggiornare",
        Free: "Gratis",
        "There was an error downloading the image, please try again later.": "Si \u00e8 verificato un errore di caricamento dell\u2019immagine. Riprova pi\u00f9 tardi",
        Effects: "Effetti",
        Tools: "Strumenti",
        "Don't ask me again": "Non chiedermelo pi\u00f9",
        Reset: "Ripristina",
        "File saved": "File salvato",
        Blemish: "Difetto",
        Bulge: "Aumenta",
        Alice: "Alice",
        "Destination folder": "Cartella di destinazione",
        "Original size": "Originale",
        "Are you sure you want to remove this sticker?": "Sei sicuro di voler rimuovere l'adesivo?",
        "Revert to original?": "Tornare alla versione originale?",
        Mohawk: "Mohawk",
        Enhance: "Migliora",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary \u00e8 un SDK gratuita disponibile per iOS e Android che consente di aggiungere alla tua applicazione funzionalit\u00e0 di foto editing, facilmente e in poche righe di codice.",
        Greeneye: "Occhi Verdi",
        Shadow: "Ombra",
        Vogue: "Vogue",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "In questo modo perderai tutte le modifiche apportate. Sei sicuro di voler tornare all\u2019immagine originale?",
        OK: "OK",
        Intensity: "Intensit\u00e0",
        Whiten: "Schiarisci",
        Frames: "Frames",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "Aggiungi un po' di polvere alle tue foto; invecchiala con i nostri sei effetti grunge.",
        "Delete selected": "Elimina selezionati",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "Un pacchetto di adesivi \u00e8 stato aggiornato. Dobbiamo ricaricare il riquadro corrente. Vuoi applicare l\u2019adesivo corrente?",
        "Set color": "Imposta colore",
        "Always Sunny": "Soleggiato",
        Confirm: "Conferma",
        Siesta: "Siesta",
        Negative: "Negativo",
        Send: "Invia",
        "Keep editing": "Continua a modificare",
        "Powered by Aviary.com": "Fornito da Aviary.com",
        Zoom: "Zoom",
        Editor: "Editor",
        "Biggest size": "Grande",
        "Soft Focus": "Soft Focus",
        Save: "Salva",
        "Are you sure?": "Sei sicuro?",
        Warmth: "Calore",
        More: "Altri",
        Meme: "Meme",
        Charcoal: "Antracite",
        Malibu: "Malibu",
        Grunge: "Grunge",
        "Tool Selection": "Scegli strumento",
        Auto: "Auto",
        Tool: "Strumento",
        Settings: "Impostazioni",
        Eddie: "Eddie",
        Cinematic: "Cinematico",
        "Medium size": "Media",
        Store: "Store",
        Backlit: "Luce posteriore",
        "Are you sure you want to discard changes from this tool?": "Sei sicuro di voler annullare le modifiche effettuate con questo strumento?",
        Brightness: "Luminosit\u00e0",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "Aspetta! Non hai salvato il tuo lavoro. Sei sicuro di voler chiudere questo editor?",
        Smooth: "Smooth",
        "Get this editor": "Vai al sito",
        Draw: "Disegno",
        Flip: "Capovolgi",
        "Soft Brushes": "Contorni morbidi",
        "View Image": "Vedi Immagine",
        "Your work was saved!": "Il tuo lavoro \u00e8 stato salvato",
        "Small size": "Piccola",
        Delete: "Elimina",
        Square: "Quadrato",
        Rounded: "Rotondeggiante",
        Redo: "Ripristina",
        "Preset Sizes": "Dimensioni predefinite",
        Sharpness: "Nitidezza",
        Back: "Indietro",
        "Brush softness": "Intensit\u00e0 del pennello",
        Brush: "Pennello",
        Mirror: "Capovolgi",
        "Edit Bottom Text": "Modifica il testo sotto",
        "Photo Editor": "Editor di Foto",
        "Maintain proportions": "Mantieni le proporzioni",
        Vivid: "Vivid",
        "San Carmen": "San Carmen",
        Retro: "Retro",
        Exit: "Uscita",
        Undo: "Annulla",
        "Loading Image...": "Caricamento immagine...",
        Borders: "Borders",
        Contrast: "Contrasto",
        "Saving...": "Salvataggio in corso...",
        "Instant!": "Istantanea",
        "Choose Color": "Scegli colore",
        Strato: "Strato",
        Vignette: "Vignettoso",
        "Zoom Mode": "Modalit\u00e0 zoom",
        "A sticker pack has been updated. We need to reload the current panel.": "Un pacchetto di adesivi \u00e8 stato aggiornato. Dobbiamo ricaricare il riquadro corrente",
        Vigilante: "Vigilante",
        "Image saved in %1$s. Do you want to see the saved image?": "File salvato in %1$s. Vuoi vedere l'immagine appena salvata?",
        "Hard Brushes": "Contorni netti",
        "Brush size": "Dimensione pennello",
        "Get More": "Altri",
        "Color Matrix": "Matrice di colore",
        Corners: "Corners",
        Aqua: "Aqua",
        "Output Image Size": "Dimensione immagine finale",
        Ragged: "Logoro",
        Ventura: "Ventura",
        Error: "Errore",
        "You can change this property in the Settings panel.": "Puoi cambiare quest\u00e0 propriet\u00e0 nelle impostazioni",
        Kurt: "Kurt",
        Balance: "Bilanciamento",
        Original: "Originale",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "E' stata creata una miniatura della tua immagine perch\u00e9 la possa modificare pi\u00f9 facilmente. Quando farai click su Salva la vedrai in dimensione intera.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "Racconta i tuoi ricordi pi\u00f9 belli con i nostri sei effetti nostalgia.",
        Orientation: "Ruota",
        "Add Text": "+ Testo",
        Classic: "Classico",
        Text: "Testo",
        "No stickers defined in Feather_Stickers.": "Non ci sono adesivi in Feather_Stickers",
        "Drag corners to resize crop area": "Trascina gli angoli per ridimensionare l'area da ritagliare",
        "Give feedback": "Suggerimenti",
        "Get this pack!": "Ottieni questo pacchetto!",
        Height: "Altezza",
        Colors: "Colori",
        Done: "Fatto",
        Fixie: "Fixie",
        Cancel: "Annulla",
        Close: "Chiudi",
        "Width and height must be greater than zero and less than the maximum({max}px)": "Larghezza e altezza devono essere superiori a zero e inferiori alle dimensioni massime ({max} px)",
        "Leave editor": "Esci",
        Size: "Dimensioni",
        "e-mail address": "Indirizzo email",
        Fade: "Dissolvenza",
        Min: "Min",
        Cherry: "Ciliegia",
        "Are you sure? This can distort your image": "Sei sicuro? Questo pu\u00f2 creare distorsioni nell'immagine",
        "A sticker pack has been updated. Click ok to reload the packs list.": "Un pacchetto di adesivi \u00e8 stato aggiornato. Fai clic su OK per ricaricare l\u2019elenco dei pacchetti",
        Custom: "Personale",
        Eraser: "Gomma",
        Singe: "Singe",
        Drifter: "Giramondo",
        Saturation: "Satura",
        "Crop again": "Ritaglia ancora",
        "Aviary Editor": "Editor Aviary",
        "Applying action %2$i of %2$i": "Salvataggio in corso. %1$i di %2$i",
        Max: "Max",
        Attention: "Attenzione",
        Redeye: "Red eye",
        Halftone: "Mezzotono",
        Pinch: "Contrai",
        "Old Photo": "Vecchia foto",
        Laguna: "Laguna",
        Resize: "Ridimensiona",
        "Powered by": "Powered by",
        "Color Grading": "Gradazione di colore",
        Firefly: "Lucciola",
        Rotate: "Ruota",
        "Applying effects": "Sto applicando l'effetto",
        Daydream: "Sogni",
        "Enter text here": "Inserisci il testo qui",
        "Code Red": "Codice Rosso",
        "Interested? We'll send you some info.": "Sei interessato? Ti mandiamo alcune informazioni.",
        Remove: "Rimuovi",
        Concorde: "Concorde",
        "Vignette Blur": "Vignetta",
        "About this editor": "Informazioni su questo editor",
        Discard: "S\u00ec, elimina",
        "Film Grain": "Pellicola",
        Power: "Potenza",
        Color: "Colore",
        Demo: "Demo",
        Crop: "Ritaglia",
        "Edit Top Text": "Modifica il testo sopra",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "Stai per perdere le modifiche effettuate con questo strumento. Sei sicuro di voler uscire?",
        Apply: "Applica",
        Stickers: "Adesivi"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.ja = {
        Loading: "\u8aad\u307f\u8fbc\u307f\u4e2d",
        "Toy Camera": "\u30c8\u30a4\u30ab\u30e1\u30e9",
        Night: "\u591c",
        Nostalgia: "\u30ce\u30b9\u30bf\u30eb\u30b8\u30a2",
        Aviary: "\u30a2\u30d3\u30a2\u30ea\u30fc",
        Width: "\u6a2a\u5e45",
        "No effects found for this pack.": "\u3053\u306e\u30d1\u30c3\u30af\u306e\u52b9\u679c\u306f\u898b\u3064\u304b\u308a\u307e\u305b\u3093\u3067\u3057\u305f\u3002",
        Blur: "\u307c\u304b\u3057",
        "Your image was cropped.": "\u753b\u50cf\u304c\u5207\u308a\u629c\u304b\u308c\u307e\u3057\u305f\u3002",
        Sharpen: "\u30b7\u30e3\u30fc\u30d7",
        Ripped: "\u7834\u308c\u305f",
        Indiglow: "\u30a4\u30f3\u30c7\u30a3\u30b4",
        "There is another image editing window open.  Close it without saving and continue?": "\u5225\u306e\u30a6\u30a3\u30f3\u30c9\u30a6\u3067\u753b\u50cf\u7de8\u96c6\u3092\u958b\u3044\u3066\u3044\u307e\u3059\u3002\u4fdd\u5b58\u305b\u305a\u306b\u305d\u308c\u3092\u9589\u3058\u307e\u3059\u304b\uff1f",
        Resume: "\u518d\u958b\u3059\u308b",
        Heatwave: "\u71b1\u6ce2",
        "A filter pack has been updated. Click ok to reload the packs list.": "\u30d5\u30a3\u30eb\u30bf\u30fc\u30d1\u30c3\u30af\u306e\u66f4\u65b0\u304c\u5b8c\u4e86\u3057\u307e\u3057\u305f\u3002OK\u3092\u30af\u30ea\u30c3\u30af\u3057\u3066\u30d1\u30c3\u30af\u30ea\u30b9\u30c8\u3092\u30ea\u30ed\u30fc\u30c9\u3057\u3066\u304f\u3060\u3055\u3044\u3002",
        Update: "\u66f4\u65b0",
        Free: "\u30d5\u30ea\u30fc",
        "There was an error downloading the image, please try again later.": "\u30a4\u30e1\u30fc\u30b8\u306e\u30c0\u30a6\u30f3\u30ed\u30fc\u30c9\u4e2d\u306b\u30a8\u30e9\u30fc\u304c\u767a\u751f\u3057\u307e\u3057\u305f\u3002\u3082\u3046\u4e00\u5ea6\u8a66\u3057\u3066\u304f\u3060\u3055\u3044\u3002",
        Effects: "\u52b9\u679c",
        "Sorry, there's no application on your phone to handle this action.": "\u3054\u5229\u7528\u306e\u643a\u5e2f\u6a5f\u5668\u306b\u3053\u306e\u52d5\u4f5c\u3092\u30b5\u30dd\u30fc\u30c8\u3059\u308b\u30a2\u30d7\u30ea\u30b1\u30fc\u30b7\u30e7\u30f3\u304c\u3042\u308a\u307e\u305b\u3093\u3002",
        Tools: "\u30c4\u30fc\u30eb",
        "Don't ask me again": "\u3053\u306e\u8cea\u554f\u306f\u8868\u793a\u3057\u306a\u3044",
        Reset: "\u30ea\u30bb\u30c3\u30c8",
        "File saved": "\u30d5\u30a1\u30a4\u30eb\u306e\u4fdd\u5b58\u304c\u5b8c\u4e86\u3057\u307e\u3057\u305f",
        Blemish: "\u50b7\u88dc\u6b63",
        Bulge: "\u30d0\u30eb\u30b8",
        Alice: "\u30a2\u30ea\u30b9",
        "Destination folder": "\u76ee\u7684\u30d5\u30a1\u30eb\u30c0\u30fc",
        "Original size": "\u5143\u306e\u30b5\u30a4\u30ba",
        "Are you sure you want to remove this sticker?": "\u30b9\u30c6\u30c3\u30ab\u30fc\u3092\u524a\u9664\u3057\u307e\u3059\u304b?",
        "Revert to original?": "\u5143\u306e\u30a4\u30e1\u30fc\u30b8\u306b\u623b\u3059",
        Mohawk: "\u30e2\u30d2\u30ab\u30f3",
        Enhance: "\u5f37\u8abf",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary \u306fiOS\u3068Android\u7528\u306e\u7121\u6599SDK\u3067\u3059\u3002\u305f\u3063\u305f\u6570\u884c\u306e\u30b3\u30fc\u30c9\u3067\u3001\u30a2\u30d7\u30ea\u306b\u5199\u771f\u7de8\u96c6\u6a5f\u80fd\u3084\u30a8\u30d5\u30a7\u30af\u30c8\u3092\u8ffd\u52a0\u3067\u304d\u307e\u3059\u3002",
        Greeneye: "\u7dd1\u76ee\u88dc\u6b63",
        Shadow: "\u5f71",
        Vogue: "\u30f4\u30a9\u30fc\u30b0",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "\u3059\u3079\u3066\u306e\u5909\u66f4\u5185\u5bb9\u304c\u5931\u308f\u308c\u307e\u3059\u3002\u5143\u306e\u30a4\u30e1\u30fc\u30b8\u306b\u623b\u3057\u307e\u3059\u304b?",
        OK: "OK",
        "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?": "\u3054\u5229\u7528\u306e\u643a\u5e2f\u6a5f\u5668\u306b\u3053\u306e\u52d5\u4f5c\u3092\u30b5\u30dd\u30fc\u30c8\u3059\u308b\u30a2\u30d7\u30ea\u30b1\u30fc\u30b7\u30e7\u30f3\u304c\u3042\u308a\u307e\u305b\u3093\u3002\u30de\u30fc\u30b1\u30c3\u30c8\u304b\u3089\u30c0\u30a6\u30f3\u30ed\u30fc\u30c9\u3057\u307e\u3059\u304b?",
        Intensity: "\u5f37\u5ea6",
        Whiten: "\u7f8e\u767d",
        Frames: "\u30d5\u30ec\u30fc\u30e0",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "\u3053\u308c\u3089\uff16\u3064\u306e\u300c\u6c5a\u308c\u300d\u52b9\u679c\u3092\u4f7f\u3063\u3066\u3001\u3042\u306a\u305f\u306e\u5199\u771f\u306b\u7802\u307c\u3053\u308a\u3084\u64e6\u308a\u5207\u308c\u305f\u611f\u3058\u3092\u52a0\u3048\u307e\u3057\u3087\u3046\u3002",
        "Delete selected": "\u9078\u629e\u3092\u524a\u9664\u3059\u308b",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "\u30b9\u30c6\u30c3\u30ab\u30fc\u30d1\u30c3\u30af\u306e\u66f4\u65b0\u304c\u5b8c\u4e86\u3057\u307e\u3057\u305f\u3002\u66f4\u65b0\u3057\u305f\u30d1\u30cd\u30eb\u306e\u30ea\u30ed\u30fc\u30c9\u304c\u5fc5\u8981\u3067\u3059\u3002\u66f4\u65b0\u30b9\u30c6\u30c3\u30ab\u30fc\u3092\u9069\u7528\u3057\u307e\u3059\u304b?",
        "Set color": "\u8272\u3092\u8a2d\u5b9a\u3059\u308b",
        "Always Sunny": "\u6674\u5929",
        Confirm: "\u78ba\u5b9a",
        Siesta: "\u30b7\u30a8\u30b9\u30bf",
        Negative: "\u53cd\u8ee2",
        Send: "\u9001\u4fe1",
        "Keep editing": "\u7de8\u96c6\u3092\u7d9a\u3051\u308b",
        "Powered by Aviary.com": "Powered by Aviary.com",
        Zoom: "\u30ba\u30fc\u30e0",
        Retro: "\u30ec\u30c8\u30ed",
        "Biggest size": "\u30b5\u30a4\u30ba\u6700\u5927",
        "Soft Focus": "\u8edf\u7126\u70b9",
        Save: "\u4fdd\u5b58",
        "Are you sure?": "\u672c\u5f53\u306b\uff1f",
        Warmth: "\u6696\u304b\u3055",
        More: "\u3055\u3089\u306b",
        Meme: "\u30df\u30fc\u30e0",
        Charcoal: "\u6728\u70ad\u753b",
        Malibu: "\u30de\u30ea\u30d6",
        Grunge: "\u30b0\u30e9\u30f3\u30b8",
        "Tool Selection": "\u30c4\u30fc\u30eb\u9078\u629e",
        Auto: "\u81ea\u52d5",
        Tool: "\u30c4\u30fc\u30eb",
        Settings: "\u8a2d\u5b9a",
        Eddie: "\u30a8\u30c7\u30a3\u30fc",
        Cinematic: "\u30b7\u30cd\u30de",
        "Medium size": "\u30b5\u30a4\u30ba\u4e2d",
        Store: "\u30b9\u30c8\u30a2",
        Backlit: "\u30d0\u30c3\u30af\u30e9\u30a4\u30c8",
        Fixie: "\u30d5\u30a3\u30af\u30b7\u30fc",
        Brightness: "\u660e\u308b\u3055",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "\u3061\u3087\u3063\u3068\u307e\u3063\u3066\uff01\u307e\u3060\u5909\u66f4\u306f\u4fdd\u5b58\u3055\u308c\u3066\u3044\u307e\u305b\u3093\u3002\u672c\u5f53\u306b\u3053\u306e\u30a8\u30c7\u30a3\u30bf\u3092\u9589\u3058\u307e\u3059\u304b\uff1f",
        Smooth: "\u6ed1\u3089\u304b\u306a",
        "Get this editor": "Editor\u3092\u4f7f\u3046",
        Draw: "\u30c9\u30ed\u30fc",
        Flip: "\u53cd\u8ee2",
        "Soft Brushes": "\u30bd\u30d5\u30c8\u30d6\u30e9\u30b7",
        "View Image": "\u30a4\u30e1\u30fc\u30b8\u3092\u8868\u793a",
        Viewfinder: "\u30d5\u30a1\u30a4\u30f3\u30c0\u30fc",
        "Your work was saved!": "\u4fdd\u5b58\u3055\u308c\u307e\u3057\u305f",
        "Small size": "\u30b5\u30a4\u30ba\u5c0f",
        Delete: "\u524a\u9664",
        Square: "\u30b9\u30af\u30a8\u30a2",
        Rounded: "\u4e38\u307f\u51e6\u7406",
        Redo: "\u3084\u308a\u76f4\u3059",
        "Preset Sizes": "\u30d7\u30ea\u30bb\u30c3\u30c8",
        Sharpness: "\u9bae\u660e\u5ea6",
        Back: "\u623b\u308b",
        "Brush softness": "\u786c\u3055",
        Brush: "\u30d6\u30e9\u30b7",
        Mirror: "\u30df\u30e9\u30fc",
        "Edit Bottom Text": "\u4e0b\u306e\u30c6\u30ad\u30b9\u30c8\u3092\u7de8\u96c6",
        "Photo Editor": "\u30a8\u30c7\u30a3\u30bf\u30fc",
        "Maintain proportions": "\u30d7\u30ed\u30d1\u30c6\u30a3\u3092\u4fdd\u6301",
        Vivid: "\u9bae\u660e",
        "San Carmen": "\u30bb\u30a4\u30f3\u30c8",
        Exit: "\u7d42\u4e86\u3059\u308b",
        Undo: "\u53d6\u308a\u6d88\u3059",
        "Loading Image...": "\u30a4\u30e1\u30fc\u30b8\u3092\u8aad\u307f\u8fbc\u307f\u4e2d\u2026",
        Borders: "\u56fd\u5883",
        Contrast: "\u5f37\u5f31",
        "Saving...": "\u4fdd\u5b58\u4e2d\u2026",
        "Instant!": "\u30a4\u30f3\u30b9\u30bf\u30f3\u30c8",
        "Choose Color": "\u8272\u3092\u9078\u629e",
        Strato: "\u96f2",
        Vignette: "\u98fe\u308a\u6a21\u69d8",
        "Zoom Mode": "\u30ba\u30fc\u30e0 \u30e2\u30fc\u30c9",
        "A sticker pack has been updated. We need to reload the current panel.": "\u30b9\u30c6\u30c3\u30ab\u30fc\u30d1\u30c3\u30af\u306e\u66f4\u65b0\u304c\u5b8c\u4e86\u3057\u307e\u3057\u305f\u3002\u66f4\u65b0\u3057\u305f\u30d1\u30cd\u30eb\u306e\u30ea\u30ed\u30fc\u30c9\u304c\u5fc5\u8981\u3067\u3059\u3002",
        Vigilante: "\u30f4\u30a3\u30b8\u30e9\u30f3\u30c8",
        "Image saved in %1$s. Do you want to see the saved image?": "\u30a4\u30e1\u30fc\u30b8\u304c%1$s\u306b\u4fdd\u5b58\u3055\u308c\u307e\u3057\u305f\u3002\u30a4\u30e1\u30fc\u30b8\u3092\u898b\u307e\u3059\u304b?",
        "Hard Brushes": "\u30cf\u30fc\u30c9\u30d6\u30e9\u30b7",
        "Brush size": "\u30b5\u30a4\u30ba",
        "Get More": "\u3082\u3063\u3068\u30b2\u30c3\u30c8\u3059\u308b",
        "Color Matrix": "\u30ab\u30e9\u30fc\u30de\u30c8\u30ea\u30af\u30b9",
        Corners: "\u30b3\u30fc\u200b\u200b\u30ca\u30fc",
        Aqua: "\u6c34",
        "Output Image Size": "\u30a2\u30a6\u30c8\u30d7\u30c3\u30c8 \u30a4\u30e1\u30fc\u30b8 \u30b5\u30a4\u30ba",
        Ragged: "\u3067\u3053\u307c\u3053",
        Ventura: "\u30d9\u30f3\u30c1\u30e5\u30e9",
        Error: "\u30a8\u30e9\u30fc",
        "You can change this property in the Settings panel.": "\u30d7\u30ed\u30d1\u30c6\u30a3\u30fc\u306f\u8a2d\u5b9a\u30d1\u30cd\u30eb\u3067\u5909\u66f4\u3067\u304d\u307e\u3059",
        Kurt: "\u30ab\u30fc\u30c8",
        Balance: "\u30d0\u30e9\u30f3\u30b9",
        Original: "\u30aa\u30ea\u30b8\u30ca\u30eb",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "\u753b\u50cf\u306f\u7de8\u96c6\u3057\u3084\u3059\u3044\u3088\u3046\u306b\u4e00\u6642\u7684\u306b\u7e2e\u5c0f\u3055\u308c\u3066\u3044\u307e\u3059\u3002[\u4fdd\u5b58]\u3092\u30af\u30ea\u30c3\u30af\u3059\u308b\u3068\u30d5\u30eb\u30b5\u30a4\u30ba\u306e\u753b\u50cf\u304c\u4fdd\u5b58\u3055\u308c\u307e\u3059\u3002",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "\u305d\u306e\u5e7b\u60f3\u7684\u3067\u30ce\u30b9\u30bf\u30eb\u30b8\u30c3\u30af\u306a\u52b9\u679c\u3067\u3001\u697d\u3057\u304b\u3063\u305f\u601d\u3044\u51fa\u3084\u53e4\u304d\u826f\u304d\u6642\u4ee3\u3092\u632f\u308a\u8fd4\u308a\u307e\u3057\u3087\u3046\u3002",
        "Oops, there was an error while saving the image.": "\u30a4\u30e1\u30fc\u30b8\u306e\u4fdd\u5b58\u4e2d\u306b\u30a8\u30e9\u30fc\u304c\u767a\u751f\u3057\u307e\u3057\u305f\u3002",
        Orientation: "\u4f4d\u7f6e",
        "Add Text": "\u6587\u5b57\u8ffd\u52a0",
        Classic: "\u30af\u30e9\u30b7\u30c3\u30af",
        Text: "\u30c6\u30ad\u30b9\u30c8",
        "No stickers defined in Feather_Stickers.": "Feather_Stickers \u306b\u306f\u4f55\u3082\u30b9\u30c6\u30c3\u30ab\u30fc\u304c\u3042\u308a\u307e\u305b\u3093\u3002",
        "Drag corners to resize crop area": "\u5207\u308a\u629c\u304d\u9818\u57df\u3092\u30ea\u30b5\u30a4\u30ba\u3059\u308b\u306b\u306f\u89d2\u3092\u30c9\u30e9\u30c3\u30b0\u3057\u3066\u304f\u3060\u3055\u3044",
        "Give feedback": "\u30d5\u30a3\u30fc\u30c9\u30d0\u30c3\u30af\u3092\u9001\u4fe1",
        "Get this pack!": "\u3053\u306e\u30d1\u30c3\u30af\u3092\u624b\u306b\u5165\u308c\u3088\u3046!",
        Height: "\u9ad8\u3055",
        Colors: "\u8272\u8abf\u88dc\u6b63",
        Done: "\u5b8c\u4e86",
        "See your world a little differently with these six high-tech camera effects.": "6\u3064\u306e\u30cf\u30a4\u30c6\u30af\u306a\u30ab\u30e1\u30e9\u52b9\u679c\u3092\u4f7f\u3063\u3066\u3001\u5c11\u3057\u9055\u3046\u76ee\u3067\u4e16\u754c\u3092\u898b\u3066\u307f\u3088\u3046\u3002",
        Cancel: "\u53d6\u6d88",
        Close: "\u9589\u3058\u308b",
        "Width and height must be greater than zero and less than the maximum({max}px)": "\u5e45\u3068\u9ad8\u3055\u306f\u30bc\u30ed\u3088\u308a\u3082\u5927\u304d\u304f\u6700\u5927\u9650\u3088\u308a\u3082\u5c0f\u3055\u304f\u306a\u304f\u3066\u306f\u3044\u3051\u306a\u3044",
        "Leave editor": "\u30a8\u30c7\u30a3\u30bf\u30fc\u3092\u9589\u3058\u308b",
        Size: "\u30b5\u30a4\u30ba",
        "e-mail address": "\u30e1\u30fc\u30eb\u30a2\u30c9\u30ec\u30b9",
        "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!": "\u30af\u30e9\u30c3\u30b7\u30e5\u3057\u307e\u3057\u305f! \u30c7\u30d9\u30ed\u30c3\u30d1\u30fc\u306b\u30a8\u30e9\u30fc\u5831\u544a\u304c\u9001\u4fe1\u3055\u308c\u307e\u3057\u305f\u3002",
        Fade: "\u30d5\u30a7\u30fc\u30c9",
        Min: "\u6700\u5c0f",
        Cherry: "\u30c1\u30a7\u30ea\u30fc",
        "Are you sure? This can distort your image": "\u4fe1\u3058\u3089\u308c\u307e\u3059\u304b\uff1f\u4eca\u307e\u3067\u306e\u5e38\u8b58\u304c\u3072\u3063\u304f\u308a\u8fd4\u308a\u307e\u3059\u3002",
        "A sticker pack has been updated. Click ok to reload the packs list.": "\u30b9\u30c6\u30c3\u30ab\u30fc\u30d1\u30c3\u30af\u306e\u66f4\u65b0\u5b8c\u304c\u5b8c\u4e86\u3057\u307e\u3057\u305f\u3002OK\u3092\u30af\u30ea\u30c3\u30af\u3057\u3066\u30d1\u30c3\u30af\u30ea\u30b9\u30c8\u3092\u30ea\u30ed\u30fc\u30c9\u3057\u3066\u304f\u3060\u3055\u3044\u3002",
        Custom: "\u30ab\u30b9\u30bf\u30e0",
        Eraser: "\u6d88\u3057\u30b4\u30e0",
        Singe: "\u7126\u304c\u3057",
        Drifter: "\u30c9\u30ea\u30d5\u30bf\u30fc",
        Saturation: "\u5f69\u5ea6",
        "Crop again": "\u518d\u5ea6\u5207\u308a\u629c\u304f",
        "Aviary Editor": "Aviary Editor",
        "Applying action %2$i of %2$i": "%2$i of %2$i\u3092\u9069\u7528\u4e2d",
        Max: "\u6700\u5927",
        Attention: "\u6ce8\u610f",
        Redeye: "\u8d64\u76ee\u88dc\u6b63",
        Halftone: "\u4e2d\u9593\u8272",
        Pinch: "\u3064\u307e\u3080",
        "Old Photo": "\u30aa\u30fc\u30eb\u30c9\u30d5\u30a9\u30c8",
        Laguna: "\u73ca\u745a\u7901",
        Resize: "\u30ea\u30b5\u30a4\u30ba",
        "Powered by": "\u63d0\u4f9b",
        "Color Grading": "\u30ab\u30e9\u30fc\u30b0\u30ec\u30fc\u30c7\u30a3\u30f3\u30b0",
        Firefly: "\u30db\u30bf\u30eb",
        Rotate: "\u56de\u8ee2",
        "Applying effects": "\u9069\u7528\u4e2d",
        Daydream: "\u30c7\u30a4\u30c9\u30ea\u30fc\u30e0",
        "Enter text here": "\u30c6\u30ad\u30b9\u30c8\u3092\u5165\u529b",
        "Code Red": "\u30b3\u30fc\u30c9\u30ec\u30c3\u30c9",
        "Interested? We'll send you some info.": "\u8208\u5473\u304c\u3042\u308a\u307e\u3057\u305f\u3089\u3001\u8a73\u3057\u3044\u60c5\u5831\u3092\u304a\u9001\u308a\u3057\u307e\u3059\u3002",
        Remove: "\u524a\u9664",
        Concorde: "\u30b3\u30f3\u30b3\u30eb\u30c9",
        "Vignette Blur": "\u30d3\u30cd\u30c3\u30c8",
        "About this editor": "\u3053\u306e\u30a8\u30c7\u30a3\u30bf\u30fc\u306b\u3064\u3044\u3066",
        "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?": "Aviary\u30d5\u30a9\u30eb\u30c0\u30fc\u306b\u30a4\u30e1\u30fc\u30b8\u3092\u4fdd\u5b58\u4e2d\u306b\u30a8\u30e9\u30fc\u304c\u767a\u751f\u3057\u307e\u3057\u305f\u3002\u30ab\u30e1\u30e9\u306e\u30c7\u30d5\u30a9\u30eb\u30c8\u30d5\u30a9\u30eb\u30c0\u30fc\u306b\u4fdd\u5b58\u3057\u76f4\u3057\u307e\u3059\u304b?",
        "Film Grain": "\u30d5\u30a3\u30eb\u30e0\u30b0\u30ec\u30a4\u30f3",
        Power: "\u96fb\u6e90",
        Color: "\u8272",
        Demo: "\u30c7\u30e2",
        Crop: "\u5207\u308a\u629c\u304d",
        "Edit Top Text": "\u30c8\u30c3\u30d7\u30c6\u30ad\u30b9\u30c8\u3092\u7de8\u96c6",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "\u3053\u306e\u30c4\u30fc\u30eb\u3067\u884c\u3063\u305f\u5909\u66f4\u304c\u5931\u308f\u308c\u307e\u3059\u3002\u672c\u5f53\u306b\u9589\u3058\u3066\u826f\u3044\u3067\u3059\u304b\uff1f",
        Apply: "\u9069\u7528",
        Stickers: "\u30b9\u30c6\u30c3\u30ab\u30fc"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.ko = {
        Loading: "\ub85c\ub529 \uc911",
        "Toy Camera": "\uc7a5\ub09c\uac10 \uce74\uba54\ub77c",
        Night: "\ubc24",
        Nostalgia: "\ud5a5\uc218",
        Aviary: "Aviary",
        Width: "\ub108\ube44",
        "No effects found for this pack.": "\uc774 \ud329\uc5d0\ub294 \ud6a8\uacfc\uac00 \uc5c6\uc74c",
        Blur: "\ud750\ub9bc",
        "Your image was cropped.": "\uc774\ubbf8\uc9c0\uac00 \uc798\ub838\uc2b5\ub2c8\ub2e4.",
        Sharpen: "\uc120\uba85\ud558\uac8c",
        "Learn more!": "\uc790\uc138\ud788 \uc54c\uc544\ubcf4\uae30!",
        Ripped: "\ucc22\uc5b4\uc9c4",
        Indiglow: "\uc778\ub514\uae00\ub85c\uc6b0",
        "There is another image editing window open.  Close it without saving and continue?": "\ub2e4\ub978 \ud3b8\uc9d1 \ucc3d\uc774 \uc5f4\ub824 \uc788\uc2b5\ub2c8\ub2e4. \uc800\uc7a5\ud558\uc9c0 \uc54a\uace0 \ub2eb\uc740 \ud6c4 \uacc4\uc18d\ud560\uae4c\uc694?",
        Resume: "\uc7ac\uc2dc\uc791",
        Heatwave: "\ubb34\ub354\uc704",
        "A filter pack has been updated. Click ok to reload the packs list.": "\ud544\ud130 \ud329\uc774 \uc5c5\ub370\uc774\ud2b8\ub418\uc5c8\uc2b5\ub2c8\ub2e4. \ud329 \ubaa9\ub85d\uc744 \ub2e4\uc2dc \ub85c\ub529\ud558\ub824\uba74 \ud655\uc778\uc744 \ud074\ub9ad\ud558\uc138\uc694.",
        Update: "\uc5c5\ub370\uc774\ud2b8",
        Free: "\ubb34\ub8cc",
        "There was an error downloading the image, please try again later.": "\uc774\ubbf8\uc9c0\ub97c \ub2e4\uc6b4\ub85c\ub4dc\ud558\ub294 \uc911 \uc624\ub958\uac00 \ubc1c\uc0dd\ud588\uc2b5\ub2c8\ub2e4. \ub098\uc911\uc5d0 \ub2e4\uc2dc \uc2dc\ub3c4\ud558\uc138\uc694.",
        Effects: "\ud6a8\uacfc",
        Tools: "\ub3c4\uad6c",
        Reset: "\uc7ac\uc124\uc815",
        Blemish: "\uc7a1\ud2f0",
        Bulge: "\ubd88\ub8e9\ud558\uac8c",
        Alice: "\uc568\ub9ac\uc2a4",
        "Are you sure you want to remove this sticker?": "\uc774 \uc2a4\ud2f0\ucee4\ub97c \uc81c\uac70\ud560\uae4c\uc694?",
        "Revert to original?": "\uc6d0\ubcf8\uc73c\ub85c \ub418\ub3cc\ub9b4\uae4c\uc694?",
        Mohawk: "\ubaa8\ud638\ud06c",
        Enhance: "\uac1c\uc120",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary\ub294 iOS \ubc0f Android\uc5d0 \uc0ac\uc6a9 \uac00\ub2a5\ud55c \ubb34\ub8cc SDK\ub85c\uc11c \ub2e8 \uba87 \uc904\uc758 \ucf54\ub4dc\ub85c \uc560\ud50c\ub9ac\ucf00\uc774\uc158\uc5d0 \uc0ac\uc9c4 \ud3b8\uc9d1 \uae30\ub2a5 \ubc0f \ud6a8\uacfc\ub97c \ucd94\uac00\ud560 \uc218 \uc788\uc2b5\ub2c8\ub2e4.",
        Greeneye: "\uccad\ubaa9",
        Shadow: "\uadf8\ub9bc\uc790",
        Vogue: "\uc720\ud589",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "\uc801\uc6a9\ud55c \ubcc0\uacbd \ub0b4\uc6a9\uc744 \ubaa8\ub450 \uc783\uac8c \ub429\ub2c8\ub2e4. \uc6d0\ubcf8 \uc774\ubbf8\uc9c0\ub85c \ub418\ub3cc\ub9b4\uae4c\uc694?",
        OK: "\ud655\uc778",
        Intensity: "\uac15\ub82c",
        Whiten: "\ud76c\uac8c",
        Frames: "\ud504\ub808\uc784",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "\uc774 6\uac00\uc9c0\uc758 \uadf8\ub7f0\uc9c0 \ud6a8\uacfc\ub85c \uc0ac\uc9c4\uc5d0 \uae4c\uc2ac\uae4c\uc2ac\ud55c \ub290\ub08c\uacfc \uc2dc\uac01\uc801\uc778 \ub9c8\ubaa8 \ud6a8\uacfc\ub97c \ucd94\uac00\ud558\uc138\uc694.",
        "Delete selected": "\uc120\ud0dd \ud56d\ubaa9 \uc0ad\uc81c",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "\uc2a4\ud2f0\ucee4 \ud329\uc774 \uc5c5\ub370\uc774\ud2b8\ub418\uc5c8\uc2b5\ub2c8\ub2e4. \ud604\uc7ac \ud328\ub110\uc744 \ub2e4\uc2dc \ub85c\ub529\ud574\uc57c \ud569\ub2c8\ub2e4. \ud604\uc7ac \uc2a4\ud2f0\ucee4\ub97c \uc801\uc6a9\ud560\uae4c\uc694?",
        "Set color": "\uc0c9\uc0c1 \uc124\uc815",
        "Always Sunny": "\ud56d\uc0c1 \ubc1d\uac8c",
        Confirm: "\ud655\uc778",
        Siesta: "\uc2dc\uc5d0\uc2a4\ud0c0",
        Negative: "\uc74c\ud654",
        Send: "\ubcf4\ub0b4\uae30",
        "Keep editing": "\ud3b8\uc9d1 \uacc4\uc18d",
        "Powered by Aviary.com": "\uc81c\uacf5: Aviary.com",
        Zoom: "\ud655\ub300/\ucd95\uc18c",
        Editor: "\ud3b8\uc9d1\uae30",
        "Soft Focus": "\ubd80\ub4dc\ub7ec\uc6b4 \ucd08\uc810",
        Save: "\uc800\uc7a5",
        "Are you sure?": "\uacc4\uc18d\ud560\uae4c\uc694?",
        Warmth: "\ub530\ub73b\ud568",
        More: "\ub354 \ubcf4\uae30",
        Meme: "\ubc08",
        Charcoal: "\ucc28\ucf5c",
        Malibu: "\ub9d0\ub9ac\ubd80",
        Grunge: "\uadf8\ub7f0\uc9c0",
        "Tool Selection": "\ub3c4\uad6c \uc120\ud0dd",
        Auto: "\uc790\ub3d9",
        Tool: "\ub3c4\uad6c",
        Daydream: "\ub0ae\uc7a0",
        Eddie: "\uc5d0\ub514",
        Cinematic: "\uc2dc\ub124\ub9c8",
        Store: "\ubcf4\uad00",
        Backlit: "\ubc30\uacbd \uc870\uba85",
        Fixie: "\ud53d\uc2dc",
        "Are you sure you want to discard changes from this tool?": "\uc774 \ub3c4\uad6c\ub85c \uc801\uc6a9\ud55c \ubcc0\uacbd \uc0ac\ud56d\uc744 \ucde8\uc18c\ud560\uae4c\uc694?",
        Brightness: "\ubc1d\uae30",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "\uc7a0\uc2dc\ub9cc\uc694! \uc791\uc5c5\uc744 \uc800\uc7a5\ud558\uc9c0 \uc54a\uc2b5\ub2c8\ub2e4. \uc774 \ud3b8\uc9d1\uae30\ub97c \uc885\ub8cc\ud560\uae4c\uc694?",
        Smooth: "\ubd80\ub4dc\ub7fd\uac8c",
        "Get this editor": "\uc774 \ud3b8\uc9d1\uae30 \uac00\uc838\uc624\uae30",
        Draw: "\uadf8\ub9ac\uae30",
        Flip: "\ub4a4\uc9d1\uae30",
        "Soft Brushes": "\ubd80\ub4dc\ub7ec\uc6b4 \ube0c\ub7ec\uc2dc",
        Viewfinder: "\ubdf0\ud30c\uc778\ub354",
        "Your work was saved!": "\uc791\uc5c5\uc774 \uc800\uc7a5\ub418\uc5c8\uc2b5\ub2c8\ub2e4!",
        Delete: "\uc0ad\uc81c",
        Square: "\uc0ac\uac01\ud615",
        Rounded: "\ub465\uadfc",
        "Preset Sizes": "\uc0ac\uc804 \uc124\uc815\ub41c \ud06c\uae30",
        Sharpness: "\uba85\ub3c4",
        Back: "\ub4a4\ub85c",
        "Brush softness": "\ube0c\ub7ec\uc2dc \uac15\ub3c4",
        Brush: "\ube0c\ub7ec\uc2dc",
        Mirror: "\uac70\uc6b8",
        "Edit Bottom Text": "\ub9e8 \uc544\ub798 \ud14d\uc2a4\ud2b8 \ud3b8\uc9d1",
        "Photo Editor": "\uc0ac\uc9c4 \ud3b8\uc9d1\uae30",
        "Maintain proportions": "\ube44\uc728 \uc720\uc9c0",
        Vivid: "\uc0dd\uc0dd\ud55c",
        "San Carmen": "\uc0b0 \uce74\ub974\uba58",
        Retro: "\ubcf5\uace0\ud48d",
        Exit: "\ucd9c\uad6c",
        Undo: "\uc2e4\ud589 \ucde8\uc18c",
        "Loading Image...": "\uc774\ubbf8\uc9c0 \ub85c\ub529 \uc911...",
        Borders: "\ud14c\ub450\ub9ac",
        Contrast: "\ub300\ube44",
        "Instant!": "\uc989\uc2dc!",
        "Choose Color": "\uc0c9\uc0c1 \uc120\ud0dd",
        Strato: "\uce35\uc6b4",
        Vignette: "\ube44\ub124\ud2b8",
        "Zoom Mode": "\ud655\ub300/\ucd95\uc18c \ubaa8\ub4dc",
        "A sticker pack has been updated. We need to reload the current panel.": "\uc2a4\ud2f0\ucee4 \ud329\uc774 \uc5c5\ub370\uc774\ud2b8\ub418\uc5c8\uc2b5\ub2c8\ub2e4. \ud604\uc7ac \ud328\ub110\uc744 \ub2e4\uc2dc \ub85c\ub529\ud574\uc57c \ud569\ub2c8\ub2e4.",
        Vigilante: "\uc790\uacbd\ub2e8",
        "Hard Brushes": "\ub2e8\ub2e8\ud55c \ube0c\ub7ec\uc2dc",
        "Brush size": "\ube0c\ub7ec\uc2dc \ud06c\uae30",
        "Get More": "\ub354 \uac00\uc838\uc624\uae30",
        "Color Matrix": "\uc0c9\uc0c1 \ub9e4\ud2b8\ub9ad\uc2a4",
        Corners: "\ucf54\ub108",
        Aqua: "\uc544\ucfe0\uc544",
        Ragged: "\ub204\ub354\uae30\uac00 \ub41c",
        Ventura: "\ubca4\ud22c\ub77c",
        Error: "\uc624\ub958",
        Kurt: "\ucee4\ud2b8",
        Balance: "\ubc38\ub7f0\uc2a4",
        Original: "\uc6d0\ubcf8",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "\ud3b8\uc9d1\ud558\uae30 \uc27d\ub3c4\ub85d \uc774\ubbf8\uc9c0\uac00 \uc77c\uc2dc\uc801\uc73c\ub85c \ucd95\uc18c\ub418\uc5c8\uc2b5\ub2c8\ub2e4. \uc800\uc7a5\uc744 \ub204\ub974\uba74 \uc804\uccb4 \ud45c\uc2dc \ud06c\uae30\uac00 \uc800\uc7a5\ub429\ub2c8\ub2e4.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "6\uac00\uc9c0\uc758 \uafc8\uc744 \uafb8\ub294 \ub4ef\ud55c \ud5a5\uc218 \ud6a8\uacfc\ub85c \uc990\uac70\uc6b4 \ucd94\uc5b5\uacfc \uc88b\uc740 \uc2dc\uc808\uc744 \ud68c\uc0c1\ud558\uc138\uc694.",
        Orientation: "\ubc29\ud5a5",
        "Add Text": "\ud14d\uc2a4\ud2b8 \ucd94\uac00",
        Classic: "\uace0\uc804\uc801\uc778",
        Text: "\ud14d\uc2a4\ud2b8",
        "No stickers defined in Feather_Stickers.": "\uae43\ud138_\uc2a4\ud2f0\ucee4\uc5d0 \uc815\uc758\ub41c \uc2a4\ud2f0\ucee4\uac00 \uc5c6\uc74c",
        "Drag corners to resize crop area": "\uc790\ub974\uae30 \uc601\uc5ed\uc758 \ud06c\uae30\ub97c \uc870\uc808\ud558\ub824\uba74 \ubaa8\uc11c\ub9ac\ub97c \ub044\uc138\uc694.",
        "Give feedback": "\ud53c\ub4dc\ubc31 \ubcf4\ub0b4\uae30",
        "Get this pack!": "\uc774 \ud329 \uac00\uc838\uc624\uae30!",
        Height: "\ub192\uc774",
        Colors: "\uc0c9\uc0c1",
        Done: "\uc644\ub8cc",
        "See your world a little differently with these six high-tech camera effects.": "6\uac00\uc9c0 \ucca8\ub2e8 \uce74\uba54\ub77c \ud6a8\uacfc\ub85c \uc138\uc0c1\uc744 \uc880 \ub2e4\ub974\uac8c \ubc14\ub77c\ubcf4\uc138\uc694.",
        Cancel: "\ucde8\uc18c",
        Close: "\ub2eb\uae30",
        "Width and height must be greater than zero and less than the maximum({max}px)": "\uc774\ubbf8\uc9c0\uc758 \ub108\ube44\uc640 \ub192\uc774\ub294 0\ubcf4\ub2e4 \ud06c\uace0 {max}\ud53d\uc140(\ucd5c\ub300\uac12)\ubcf4\ub2e4 \uc791\uc544\uc57c \ud569\ub2c8\ub2e4.",
        "Leave editor": "\ud3b8\uc9d1\uae30 \uc885\ub8cc",
        Size: "\ud06c\uae30",
        "e-mail address": "\uc774\uba54\uc77c \uc8fc\uc18c",
        Eraser: "\uc9c0\uc6b0\uac1c",
        Min: "\ucd5c\uc18c",
        Cherry: "\uccb4\ub9ac",
        "Are you sure? This can distort your image": "\uacc4\uc18d\ud558\uc2dc\uaca0\uc2b5\ub2c8\uae4c? \uc774 \uc791\uc5c5\uc73c\ub85c \uc774\ubbf8\uc9c0\uac00 \uc65c\uace1\ub420 \uc218 \uc788\uc2b5\ub2c8\ub2e4.",
        "A sticker pack has been updated. Click ok to reload the packs list.": "\uc2a4\ud2f0\ucee4 \ud329\uc774 \uc5c5\ub370\uc774\ud2b8\ub418\uc5c8\uc2b5\ub2c8\ub2e4. \ud329 \ubaa9\ub85d\uc744 \ub2e4\uc2dc \ub85c\ub529\ud558\ub824\uba74 \ud655\uc778\uc744 \ud074\ub9ad\ud558\uc138\uc694.",
        Custom: "\uc0ac\uc6a9\uc790 \uc815\uc758",
        Fade: "\ud398\uc774\ub4dc",
        Singe: "\uadf8\uc744\ub9bc",
        Drifter: "\ub098\uadf8\ub124",
        Saturation: "\ucc44\ub3c4",
        "Crop again": "\ub2e4\uc2dc \uc790\ub974\uae30",
        "Aviary Editor": "Aviary \ud3b8\uc9d1\uae30",
        Max: "\ucd5c\ub300",
        Attention: "\uc8fc\uc758",
        Redeye: "\uc801\ubaa9",
        Halftone: "\ud558\ud504\ud1a4",
        Pinch: "\ud540\uce58",
        "Old Photo": "\uc624\ub798\ub41c \uc0ac\uc9c4",
        Laguna: "\ub77c\uad6c\ub108",
        Resize: "\ud06c\uae30 \uc870\uc815",
        "Powered by": "\uc81c\uacf5",
        "Color Grading": "\uc0c9\ubcf4\uc815",
        Firefly: "\ubc18\ub514\ubd88\uc774",
        Rotate: "\ud68c\uc804",
        "Applying effects": "\ud6a8\uacfc \uc801\uc6a9 \uc911",
        "Enter text here": "\uc5ec\uae30\uc5d0 \ud14d\uc2a4\ud2b8 \uc785\ub825",
        "Code Red": "\ucf54\ub4dc \ub808\ub4dc",
        "Interested? We'll send you some info.": "\uad00\uc2ec\uc774 \uc788\uc73c\uc138\uc694? \uba87 \uac00\uc9c0 \uc815\ubcf4\ub97c \ubcf4\ub0b4 \ub4dc\ub9ac\uaca0\uc2b5\ub2c8\ub2e4.",
        Remove: "\uc81c\uac70",
        Concorde: "\ucf69\ucf54\ub4dc",
        "Vignette Blur": "\ube44\ub124\ud2b8 \ud750\ub9bc",
        "About this editor": "\uc774 \ud3b8\uc9d1\uae30 \uc18c\uac1c",
        Discard: "\ucde8\uc18c",
        "Film Grain": "\ud544\ub984 \uadf8\ub808\uc778",
        Power: "\ud30c\uc6cc",
        Color: "\uc0c9\uc0c1",
        Demo: "\ub370\ubaa8",
        Crop: "\uc790\ub974\uae30",
        "Edit Top Text": "\ub9e8 \uc704 \ud14d\uc2a4\ud2b8 \ud3b8\uc9d1",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "\uc774 \ub3c4\uad6c\uc5d0\uc11c \uc801\uc6a9\ud55c \ubcc0\uacbd \uc0ac\ud56d\uc744 \uc783\uac8c \ub429\ub2c8\ub2e4. \uadf8\ub798\ub3c4 \uc885\ub8cc\ud560\uae4c\uc694?",
        Apply: "\uc801\uc6a9",
        Stickers: "\uc2a4\ud2f0\ucee4"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.lt = {
        "Toy Camera": "Muilin\u0117",
        Nostalgia: "Nostalgija",
        Width: "Plotis",
        "No effects found for this pack.": "\u0160iam paketui efekt\u0173 nerasta",
        Blur: "Miglos efkt.",
        "Your image was cropped.": "J\u016bs\u0173 nuotrauka apkirpta.",
        Sharpen: "Grie\u017etinti",
        "Learn more!": "Su\u017einokite daugiau!",
        Indiglow: "Raudoni\u0161ka",
        "There is another image editing window open.  Close it without saving and continue?": "Atvertas kitas langas. U\u017edaryti j\u012f nei\u0161saugojus ir t\u0119sti?",
        Resume: "T\u0119sti",
        Heatwave: "Kar\u0161tis",
        "Maintain proportions": "I\u0161laikyti proporcijas",
        Update: "Atnaujinti",
        Free: "Nemokamai",
        Effects: "Efektai",
        Reset: "Nunulinti",
        Blemish: "Defektas",
        Bulge: "I\u0161p\u016btimas",
        Alice: "Alisa",
        Enhance: "Sustiprinti",
        Greeneye: "\u017dalios akys",
        Shadow: "\u0160e\u0161\u0117lis",
        Vogue: "Madinga",
        OK: "OK",
        Intensity: "Intensyvumas",
        Whiten: "\u0160viesinti",
        Frames: "R\u0117meliai",
        "Delete selected": "I\u0161trinti pa\u017eym\u0117tus",
        "Always Sunny": "Saul\u0117ta",
        Negative: "Negatyvas",
        Send: "Si\u0173sti",
        "Keep editing": "T\u0119skite redagavim\u0105",
        "Powered by Aviary.com": "Padaryta Aviary.com",
        Zoom: "Pritraukti",
        Editor: "Redaktorius",
        "Soft Focus": "Nery\u0161ki",
        Save: "Saugoti",
        "Are you sure?": "Ar esate \u012fsitikin\u0119s?",
        Warmth: "\u0160iluma",
        More: "Daugiau",
        Meme: "Meme",
        Malibu: "Malibu",
        Grunge: "Purvina",
        "Tool Selection": "\u012erankio pasirinkimas",
        Auto: "Auto",
        Tool: "\u012erankis",
        Daydream: "Sapnas",
        Eddie: "Edis",
        Cinematic: "Kino",
        Backlit: "Pa\u0161vitinimas",
        "Are you sure you want to discard changes from this tool?": "Ar tikrai norite panaikinti visus pakeitimus?",
        Brightness: "Ry\u0161kumas",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "Palaukite! J\u016bs nei\u0161saugojote savo darbo. Ar tikrai norite u\u017edaryti redaktori\u0173?",
        Smooth: "I\u0161lyginti",
        "Get this editor": "Gauti \u0161\u012f redaktori\u0173",
        Draw: "Pie\u0161ti",
        Flip: "Apversti",
        "Soft Brushes": "Mink\u0161ti \u0161epet\u0117liai",
        "Your work was saved!": "J\u016bs\u0173 darbas buvo i\u0161saugotas!",
        Delete: "I\u0161trinti",
        Square: "Kvadratas",
        "Preset Sizes": "Standart. Dyd\u017eiai",
        Sharpness: "Grie\u017etumas",
        Back: "Atgal",
        "Brush softness": "\u0160epet\u0117lio mink\u0161tumas",
        "Powered by": "Padaryta",
        Mirror: "Veidrodis",
        "Edit Bottom Text": "Red. apatin\u012f tekst\u0105.",
        "Photo Editor": "Foto redaktorius",
        Vivid: "Ry\u0161kus",
        "San Carmen": "Praeitis",
        Retro: "Retro",
        Exit: "I\u0161eiti",
        Undo: "Panaikinti",
        Siesta: "Siesta",
        Borders: "Sien\u0173",
        Contrast: "Kontrastas",
        "Instant!": "Poloroidas",
        "Choose Color": "Pasirinkite spalv\u0105",
        Strato: "Kosmosas",
        Vigilante: "Budrumas",
        "Hard Brushes": "Kieti \u0161epet\u0117liai",
        "Brush size": "\u0160epet\u0117lio dydis",
        "Get More": "Gauti daugiau",
        "Color Matrix": "Spalvos matrica",
        Pinch: "Suimti",
        Aqua: "Akva",
        Ventura: "Prisiminimas",
        Night: "Naktis",
        Kurt: "Kurtas",
        Balance: "Balansas",
        Original: "Originali",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "J\u016bs\u0173 nuotrauka buvo laikinai suma\u017einti, kad b\u016bt\u0173 lengviau redaguoti. Kai paspausite \u201cSaugoti\u201d ji bus i\u0161saugota originaliu dyd\u017eiu.",
        Orientation: "Orientacija",
        "Add Text": "+ Tekst\u0105",
        Classic: "Klasikinis",
        Text: "Tekstas",
        "No stickers defined in Feather_Stickers.": "Nepasirinkti lipdukai",
        "Drag corners to resize crop area": "Tempkite u\u017e kamp\u0173, norint pakeisti kirpimo plot\u0105",
        "Give feedback": "Palikti atsiliepim\u0105",
        Height: "Auk\u0161tis",
        Colors: "Spalvos",
        Done: "Atlikta",
        Fixie: "Fiksis",
        Cancel: "At\u0161aukti",
        Close: "U\u017edaryti",
        Redo: "Pakartoti",
        Size: "Dydis",
        "e-mail address": "el.pa\u0161to adresas",
        Eraser: "Trintukas",
        Min: "Min",
        Cherry: "Vy\u0161n\u0117",
        Custom: "Individualus",
        Fade: "I\u0161blukti",
        Singe: "Apdegus",
        Drifter: "Valkata",
        Saturation: "Saturacija",
        "Crop again": "I\u0161kirpti dar kart\u0105",
        Power: "Galia",
        Max: "Max",
        Redeye: "Aki\u0173 raudonumas",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "J\u016bs prarasite visus padarytus pakeitimus. Ar tikrai norite i\u0161eiti?",
        Corners: "Kampai",
        "Old Photo": "Senovin\u0117 foto",
        Laguna: "Laguna",
        Resize: "Keisti dyd\u012f",
        Brush: "\u0160epet\u0117lis",
        "Color Grading": "Spalvos gradacija",
        Firefly: "Jonvabalis",
        Rotate: "Pasukti",
        "Applying effects": "Pritaikyti Efektus",
        "Enter text here": "\u012eveskite tekst\u0105 \u010dia",
        "Code Red": "Raudonas kodas",
        "Interested? We'll send you some info.": "Domina? Mes atsi\u016bsim Jums daugiau informacijos",
        Mohawk: "Irok\u0117zas",
        Concorde: "Nespalvota",
        "Vignette Blur": "Vinjet\u0117",
        "About this editor": "Apie \u0161\u012f redaktori\u0173",
        Discard: "Atmesti",
        "Film Grain": "Kino juosta",
        Color: "Spalva",
        Tools: "\u012erankiai",
        Crop: "I\u0161kirpti",
        "Edit Top Text": "Red. vir\u0161utin\u012f tekst\u0105.",
        Apply: "Pritaikyti",
        Stickers: "Lipdukai"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.lv = {
        "Toy Camera": "Sp\u0113\u013cu kamera",
        Nostalgia: "Nosta\u013c\u0123ija",
        Width: "Plat\u0101k",
        "No effects found for this pack.": "\u0160ai pakai efekti nav atrasti",
        Blur: "Aizmiglot",
        "Your image was cropped.": "J\u016bsu bilde ir apgriezta",
        Sharpen: "Saasin\u0101t",
        "Learn more!": "Uzzin\u0101t vair\u0101k!",
        Indiglow: "Indiglow",
        "There is another image editing window open.  Close it without saving and continue?": "Ir atv\u0113rts cits redaktora logs. Aizv\u0113rt to bez saglab\u0101\u0161anas un turpin\u0101t?",
        Resume: "Ats\u0101kt",
        Heatwave: "Karsts",
        "Maintain proportions": "Saglab\u0101t proporcijas",
        Update: "Atjaunin\u0101t",
        Free: "Bezmaksas",
        Effects: "Efekti",
        Reset: "Atjaunot",
        Blemish: "Defekts",
        Bulge: "Izspiedums",
        Alice: "Alise",
        Enhance: "Uzlabot",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Bezmaksas SDK ir pieejama iOS un Android, \u013cauj jums pievienot fotoatt\u0113lu redi\u0123\u0113\u0161anas iesp\u0113jas un ietekmi uz j\u016bsu app, tikai da\u017eas kodu rindi\u0146as.",
        Greeneye: "Za\u013c\u0101s acis",
        Shadow: "\u0112na",
        Vogue: "Mode",
        OK: "OK",
        Intensity: "Intensit\u0101te",
        Whiten: "Gai\u0161\u0101k",
        Frames: "R\u0101mji",
        "Delete selected": "Dz\u0113st izv\u0113l\u0113to",
        "Always Sunny": "Saulains",
        Negative: "Negat\u012bvs",
        Send: "S\u016bt\u012bt",
        "Keep editing": "Patur\u0113t redi\u0123\u0113\u0161anu",
        "Powered by Aviary.com": "Paveica Aviary.com",
        Zoom: "Palielin\u0101t",
        Editor: "Redaktors",
        "Soft Focus": "Maigs fokuss",
        Save: "Saglab\u0101t",
        "Are you sure?": "Vai esat p\u0101rliecin\u0101ti?",
        Warmth: "Siltums",
        More: "Vair\u0101k",
        Meme: "Meme",
        Malibu: "Malibu",
        Grunge: "Net\u012brs",
        "Tool Selection": "Instrumentu izv\u0113le",
        Auto: "Auto",
        Tool: "Instrumenti",
        Daydream: "Sapnis",
        Eddie: "Edijs",
        Cinematic: "Kino",
        Backlit: "Izgaismot",
        "Are you sure you want to discard changes from this tool?": "Vai tie\u0161\u0101m v\u0113laties atcelt \u0161\u012b r\u012bka izmai\u0146as?",
        Brightness: "Spilgtums",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "J\u016bsu darbs nav saglab\u0101ts, esat p\u0101rliecin\u0101ti, ka v\u0113laties aizv\u0113rt redaktoru?",
        Smooth: "Izl\u012bdzin\u0101t",
        "Get this editor": "Ieg\u016bt redaktoru",
        Draw: "Z\u012bm\u0113t",
        Flip: "Uzsist",
        "Soft Brushes": "M\u012bkst\u0101s otas",
        "Your work was saved!": "J\u016bsu darbs ir saglab\u0101ts!",
        Delete: "Dz\u0113st",
        Square: "Kvadr\u0101ts",
        "Preset Sizes": "Preset Izm\u0113ru",
        Sharpness: "Asums",
        Back: "Atpaka\u013c",
        "Brush softness": "Otas m\u012bkstums",
        "Powered by": "Paveica",
        Mirror: "Spogulis",
        "Edit Bottom Text": "Redi\u0123\u0113t apak\u0161\u0113jo tekstu",
        "Photo Editor": "Foto redaktors",
        Vivid: "Jarik",
        "San Carmen": "San Karmen",
        Retro: "Retro",
        Exit: "Iziet",
        Undo: "Atsaukt",
        Siesta: "Siesta",
        Borders: "Robe\u017eas",
        Contrast: "Kontrasts",
        "Instant!": "Poloroids",
        "Choose Color": "Kr\u0101sas izv\u0113le",
        Strato: "Strato",
        Vigilante: "Kust\u012bba",
        "Hard Brushes": "Ciet\u0101s otas",
        "Brush size": "Otas izm\u0113rs",
        "Get More": "Ieg\u016bt vair\u0101k",
        "Color Matrix": "Kr\u0101su matrica",
        Pinch: "Sa\u0161aurin\u0101t",
        Aqua: "Akva",
        Ventura: "Liktenis",
        Night: "Nakts",
        Kurt: "Kurts",
        Balance: "Balanss",
        Original: "Origin\u0101ls",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": 'Lai jums b\u016btu viegl\u0101t redakt\u0113t, uz laiku esam samazin\u0101ju\u0161i bildi. Kad j\u016bs spied\u012bsiet \\"Saglab\u0101t\\", t\u0101 saglab\u0101sies pirmatn\u0113j\u0101 izm\u0113r\u0101.',
        Orientation: "Orient\u0101cija",
        "Add Text": "Pievienot tekstu",
        Classic: "Klasisks",
        Text: "Teksts",
        "No stickers defined in Feather_Stickers.": "Stikeri nav izv\u0113l\u0113ti",
        "Drag corners to resize crop area": "Velciet aiz st\u016bra, lai main\u012btu grie\u017e\u0161anas plat\u012bbu",
        "Give feedback": "Dot atsauksmes",
        Height: "Augstums",
        Colors: "Kr\u0101sas",
        Done: "Dar\u012bts",
        Fixie: "Fiksis",
        Cancel: "Atcelt",
        Close: "Aizv\u0113rt",
        Redo: "Atk\u0101rtot",
        Size: "Izm\u0113rs",
        "e-mail address": "e-pasta adrese",
        Eraser: "Dz\u0113\u0161gumija",
        Min: "Min",
        Cherry: "\u0136irsis",
        Custom: "Individualiz\u0113t",
        Fade: "Izbalin\u0101t",
        Singe: "Parasts",
        Drifter: "Klaidonis",
        Saturation: "Pies\u0101tin\u0101jums",
        "Crop again": "Izgriezt v\u0113lreiz",
        Power: "Sp\u0113ks",
        Max: "Max",
        Redeye: "Sark. acis",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "J\u016bs varat zaud\u0113t izmai\u0146as, ko veic\u0101t ar \u0161o r\u012bku. Vai tie\u0161\u0101m v\u0113laties iziet?",
        Corners: "St\u016bri",
        "Old Photo": "Veca bilde",
        Laguna: "Lag\u016bna",
        Resize: "Izm\u0113rs",
        Brush: "Ota",
        "Color Grading": "Kr\u0101su grad\u0101cija",
        Firefly: "J\u0101\u0146t\u0101rpi\u0146\u0161",
        Rotate: "Atk\u0101rtot",
        "Applying effects": "Piem\u0113rot Efektu",
        "Enter text here": "Ievadiet tekstu \u0161eit",
        "Code Red": "Sarkainais kods",
        "Interested? We'll send you some info.": "Interes\u0113? M\u0113s nos\u016bt\u012bsim vair\u0101k inform\u0101cijas.",
        Mohawk: "Irokeza",
        Concorde: "Konkords",
        "Vignette Blur": "Vinjete",
        "About this editor": "Par \u0161o redaktoru",
        Discard: "Izmest",
        "Film Grain": "Kinolente",
        Color: "Kr\u0101sa",
        Tools: "Instruments",
        Crop: "Izgriezt",
        "Edit Top Text": "Redi\u0123\u0113t aug\u0161\u0113jo tekstu",
        Apply: "Piem\u0113rot",
        Stickers: "Uzl\u012bmes"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.nl = {
        Loading: "Bezig met laden",
        "Toy Camera": "Speelgoed",
        Night: "Nacht",
        Nostalgia: "Nostalgie",
        Aviary: "Aviary",
        Width: "Breedte",
        "No effects found for this pack.": "Geen effecten\u00a0gevonden voor\u00a0dit pakket",
        Blur: "Waas",
        "Your image was cropped.": "Uw afbeelding werd bijgesneden",
        Sharpen: "Scherper",
        Ripped: "Gescheurde",
        Indiglow: "Indiglow",
        "There is another image editing window open.  Close it without saving and continue?": "Er staat nog een ander venster open om een afbeelding te bewerken.  Afsluiten zonder op te slaan en verder gaan?",
        Resume: "Verder",
        "A filter pack has been updated. Click ok to reload the packs list.": "Een filterpakket is bijgewerkt. Klik op ok om de lijst met pakketen te herladen",
        Update: "Bijwerken",
        Free: "Gratis",
        "There was an error downloading the image, please try again later.": "Er is een fout opgetreden tijdens het downloaden van de afbeelding, probeer het later opnieuw.",
        Effects: "Effecten",
        "Sorry, there's no application on your phone to handle this action.": "Sorry, er bevindt zich geen toepassing op uw telefoon om deze actie mee uit te voeren",
        Tools: "Werktuig",
        "Don't ask me again": "Vraag mij dit niet nogmaals",
        Reset: "Herinitialiseren",
        "File saved": "Bestand opgeslagen",
        Blemish: "Vlek",
        Bulge: "Opblazen",
        Alice: "Alice",
        "Destination folder": "Bestemmingsmap",
        "Original size": "Originele formaat",
        "Are you sure you want to remove this sticker?": "Weet u zeker dat u deze sticker wilt verwijderen?",
        "Revert to original?": "Terug naar origineel",
        Enhance: "Verbeteren",
        Greeneye: "Groenoog",
        Shadow: "Schaduw",
        Vogue: "Mode",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "U staat op het punt om al de door u gemaakte veranderingen te verliezen. Weet u zuker dat u terug wilt gaan naar het origineel?",
        OK: "OK",
        "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?": "Sorry, er bevindt zich geen toepassing op uw telefoon om deze actie mee uit te voeren. Wilt u de toepassing van de markt downloaden?",
        Intensity: "Intensiteit",
        Whiten: "Witter",
        Frames: "Frames",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "Voeg wat ruis en andere underground stijlen toe aan je foto's met deze 6 grunge effecten.",
        "Delete selected": "Selectie verwijderen",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "Een stickerpakket is bijgewerkt. We moeten het huidige paneel herladen. Wilt u de huidige sticker toepassen?",
        "Set color": "Ingestelde kleur",
        Confirm: "Bevestigen",
        Siesta: "Si\u00ebsta",
        "Keep editing": "Verder werken",
        "Powered by Aviary.com": "Aangedreven door Aviary.com",
        Editor: "Editor",
        "Biggest size": "Grootste formaat",
        "Soft Focus": "Zachte Focus",
        Save: "Opslaan",
        "Are you sure?": "Bent u zeker?",
        Warmth: "Warmte",
        Firefly: "Vuurvliegje",
        Charcoal: "Houtskool",
        Malibu: "Malibu",
        Grunge: "Grunge",
        Auto: "Auto",
        Tool: "Gereedschap",
        Settings: "Instellingen",
        Eddie: "Eddie",
        "Medium size": "Middel formaat",
        Store: "Store",
        Backlit: "Achtergrondverlichting",
        Fixie: "Fixie",
        Brightness: "Helderheid",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "Wacht!  U heeft uw werk niet opgeslagen.  Bent u zeker dat u deze editor wil sluiten?",
        Smooth: "Smooth",
        "Get this editor": "Krijg deze\u00a0editor",
        Draw: "Tekening",
        Flip: "Omdraaien",
        "Soft Brushes": "Zachte penselen",
        "View Image": "Afbeelding bekijken",
        Viewfinder: "Beeldzoeker",
        "Your work was saved!": "Uw werk werd opgeslagen!",
        "Small size": "Klein formaat",
        Delete: "Verwijderen",
        Square: "Vierkant",
        Rounded: "Afgerond",
        Redo: "Opnieuw",
        "Preset Sizes": "Formaten",
        Sharpness: "Scherpte",
        Back: "Terug",
        "Brush softness": "Penseelzachtheid",
        Brush: "Penseel",
        Mirror: "Spiegel",
        "Photo Editor": "Photo Editor",
        "Maintain proportions": "Proporties houden",
        Vivid: "Levendig",
        "San Carmen": "San\u00a0Carmen",
        Retro: "Retro",
        Exit: "Uitgang",
        Undo: "Annuleer",
        "Loading Image...": "Bezig met het laden van de afbeelding\u2026",
        Borders: "Borders",
        Contrast: "Contrast",
        "Saving...": "Bezig met opslaan\u2026",
        "Instant!": "Instant",
        "Choose Color": "Selectie kleur",
        Strato: "Strato",
        Vignette: "Vignet",
        "Zoom Mode": "Zoom-stand",
        "A sticker pack has been updated. We need to reload the current panel.": "Een stickerpakket is bijgewerkt. We moeten het huidige paneel herladen",
        Vigilante: "Vigilante",
        "Image saved in %1$s. Do you want to see the saved image?": "Afbeelding opgeslagen in %1$s. Wilt u de opgeslagen afbeelding bekijken?",
        "Hard Brushes": "Harde penselen",
        "Brush size": "Penseelformaat",
        "Get More": "Krijg meer",
        "Color Matrix": "Kleurenmatrix",
        Corners: "Corners",
        Aqua: "Aqua",
        "Output Image Size": "Bestandsgrootte van uitvoer",
        Ragged: "Gerafelde",
        Ventura: "Ventura",
        Error: "Fout",
        "You can change this property in the Settings panel.": "U kunt deze eigenschap in het instellingenpaneel wijzigen",
        Kurt: "Kurt",
        Balance: "Balans",
        Original: "Origineel",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "Uw afbeelding werd tijdelijk kleiner gemaakt om ze makkelijker te kunnen bewerken.  Zodra u op Opslaan klikt wordt de volledige grootte opgeslagen.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "Denk terug aan mooie herinneringen met onze 6 dromerige nostalgische effecten.",
        Orientation: "Ori\u00ebntatie",
        "Add Text": "Tekst Toevoegen",
        Classic: "Klassiek",
        Text: "Tekst",
        "No stickers defined in Feather_Stickers.": "Geen stickers ingesteld in Feather_Stickers",
        "Drag corners to resize crop area": "Versleep hoeken om bij te snijden",
        "Give feedback": "Feedback geven",
        "Get this pack!": "Neem dit pakket!",
        Height: "Hoogte",
        Colors: "Kleuren",
        Done: "Klaar",
        "See your world a little differently with these six high-tech camera effects.": "Bekijk de wereld eens anders met deze zes high-tech camera-effecten.",
        Cancel: "Afzeggen",
        Close: "Sluiten",
        "Width and height must be greater than zero and less than the maximum({max}px)": "Breedte en hoogte moeten groter zijn dan nul en kleiner dan het maximum ({max}px)",
        "Leave editor": "Editor afsluiten",
        Size: "Formaat",
        "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!": "Oooooeps! Ik ben gecrashd, maar er is een rapport naar mijn ontwikkelaar verstuurd om hem te helpen het probleem op te lossen.",
        Fade: "Vervagen",
        Mohawk: "Mohawk",
        Cherry: "Kers",
        "Are you sure? This can distort your image": "Weet u het zeker? Dit kan uw afbeelding vervormen",
        "A sticker pack has been updated. Click ok to reload the packs list.": "Een stickerpakket is bijgewerkt. Klik op ok om de lijst met pakketen te herladen",
        Custom: "Eigen",
        Eraser: "Gom",
        Singe: "Verschroeien",
        Saturation: "Saturatie",
        "Crop again": "Opnieuw bijsnijden",
        "Aviary Editor": "Aviary Editor",
        "Applying action %2$i of %2$i": "Actie %2$i van %2$i wordt uitgevoerd",
        "Oops, there was an error while saving the image.": "Oeps, er is een fout opgetreden tijdens het opslaan van de afbeelding.",
        Attention: "Aandacht",
        Redeye: "Roodoog",
        Halftone: "Halftoon",
        Pinch: "Samenknijpen",
        "Old Photo": "Oude foto",
        Laguna: "Laguna",
        Resize: "Formaat",
        "Powered by": "Aangedreven door",
        "Color Grading": "Kleurgradi\u00ebnt",
        Rotate: "Roteren",
        "Tool Selection": "Selectie gereedschap",
        "Enter text here": "Vul hier uw tekst in",
        Remove: "Verwijderen",
        Concorde: "Concorde",
        "Vignette Blur": "Wazig vignet",
        "About this editor": "Over deze editor",
        "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?": "Oeps, er is een fout opgetreden tijdens het opslaan van de afbeelding naar de Aviary-map. Wilt u proberen de afbeelding op te slaan naar de standaard cameramap.",
        "Film Grain": "Korrelig",
        Color: "Kleur",
        Demo: "Demo",
        Crop: "Bijsnijden",
        Drifter: "Drifter",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "U staat op het punt de wijzigingen te verliezen die u met dit gereedschap aanbracht.  Bent u zeker dat u wenst weg te gaan?",
        Apply: "Hanteren",
        Stickers: "Stickers"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.pl = {
        "Toy Camera": "Aparat zabawka",
        Nostalgia: "Nostalgia",
        Width: "Szeroko\u015b\u0107",
        "No effects found for this pack.": "Brak efekt\u00f3w dla tego pakietu",
        Blur: "Zamgli\u0107",
        "Your image was cropped.": "Twoje zdj\u0119cie jest obci\u0119te.",
        Sharpen: "Wyostrz",
        "Learn more!": "Dowiedz si\u0119 wi\u0119cej!",
        Ripped: "Rozpruty",
        Indiglow: "Czerwony",
        "There is another image editing window open.  Close it without saving and continue?": "Jeszcze jedno okno edycji obrazu jest otwarte. Zamkn\u0105\u0107 je bez zapisywania i kontynuowa\u0107?",
        Resume: "Powr\u00f3\u0107 do edycji",
        Heatwave: "Upalny",
        "Maintain proportions": "Zachowaj proporcje",
        Update: "Aktualizacja",
        Free: "Wolny",
        Effects: "Efekty",
        Reset: "Resetuj",
        Blemish: "Rozmywanie",
        Bulge: "Uwypuklij",
        Alice: "Alisa",
        Enhance: "Popraw",
        Greeneye: "Zielone oczy",
        Shadow: "Cie\u0144",
        Vogue: "Modny",
        OK: "OK",
        Intensity: "Intensywno\u015b\u0107",
        Whiten: "Wybielanie",
        Frames: "Ramki",
        "Delete selected": "Skasowa\u0107 wybrane",
        "Always Sunny": "S\u0142onecznie",
        Negative: "Negatyw",
        Send: "Wy\u015blij",
        "Keep editing": "Kontynuuj edycj\u0119",
        "Powered by Aviary.com": "Zrobione przez Aviary.com",
        Zoom: "Powi\u0119kszy\u0107",
        Editor: "Edytor",
        "Soft Focus": "Rozmycie",
        Save: "Zapisz",
        "Are you sure?": "Czy jeste\u015b pewny?",
        Warmth: "Ciep\u0142o",
        More: "Wi\u0119cej",
        Meme: "Meme",
        Charcoal: "Ciemnoszary",
        Malibu: "Malibu",
        Grunge: "Brudny",
        "Tool Selection": "Wyb\u00f3r narz\u0119dzia",
        Auto: "Auto",
        Tool: "Narz\u0119dzie",
        Daydream: "Sen",
        Eddie: "Eddie",
        Cinematic: "Kino",
        Backlit: "Pod \u015bwiat\u0142o",
        "Are you sure you want to discard changes from this tool?": "Czy na pewno chcesz anulowa\u0107 wszystkie zmiany?",
        Brightness: "Jasno\u015b\u0107",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "Zaczekaj! Nie zachowa\u0142e\u015b zmian. Czy jeste\u015b pewien, \u017ce chcesz przerwa\u0107 edycj\u0119?",
        Smooth: "Smooth",
        "Get this editor": "Pobierz edytor",
        Draw: "Rysowanie",
        Flip: "Odwr\u00f3ci\u0107",
        "Soft Brushes": "Mi\u0119kkie p\u0119dzle",
        "Your work was saved!": "Twoja praca zosta\u0142a zachowana.",
        Delete: "Skasowa\u0107",
        Square: "Kwadrat",
        Rounded: "Zaokr\u0105glony",
        "Preset Sizes": "Standard. rozmiary",
        Sharpness: "Wyostrzanie",
        Back: "Powr\u00f3t",
        "Brush softness": "Mi\u0119kko\u015b\u0107 p\u0119dzla",
        "Powered by": "Zrobione",
        Mirror: "Odbij",
        "Edit Bottom Text": "Edytuj dolny tekst",
        "Photo Editor": "Edytor zdj\u0119\u0107",
        Vivid: "Nasycenie",
        "San Carmen": "Przesz\u0142o\u015b\u0107",
        Retro: "Retro",
        Exit: "Wyj\u015bcie",
        Undo: "Cofnij",
        Siesta: "Siesta",
        Borders: "Granice",
        Contrast: "Kontrast",
        "Saving...": "Zapisywanie...",
        "Instant!": "Polaroid",
        "Choose Color": "Wybierz kolor",
        Strato: "Kosmos",
        Vignette: "Winieta",
        Vigilante: "Czujno\u015b\u0107",
        "Hard Brushes": "Twarde p\u0119dzle",
        "Brush size": "Rozmiar p\u0119dzla",
        "Get More": "Pobierz wi\u0119cej",
        "Color Matrix": "Matryca koloru",
        Pinch: "Przyci\u0105\u0107",
        Aqua: "Akwa",
        Ragged: "Postrz\u0119piony",
        Ventura: "Wspomnienie",
        Night: "Zdj\u0119cia nocne",
        Kurt: "Kurt",
        Balance: "Korekcja bieli",
        Original: "Bez zmian",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "Twoje zdj\u0119cie zosta\u0142o czasowo zmniejszone, aby \u0142atwiej by\u0142o je edytowa\u0107. Je\u015bli naci\u015bniesz \u201ezachowaj\u201c, zdj\u0119cie zostanie zachowane w pe\u0142nym rozmiarze.",
        Orientation: "Orientacja",
        "Add Text": "+ Tekst",
        Classic: "Klasyczny",
        Text: "Tekst",
        "No stickers defined in Feather_Stickers.": "Nie wybrano naklejki",
        "Drag corners to resize crop area": "Ci\u0105gnij za r\u00f3g zdj\u0119cia, aby zmieni\u0107 obszar ci\u0119cia",
        "Give feedback": "Zg\u0142o\u015b uwagi",
        Height: "Wysoko\u015b\u0107",
        Colors: "Kolory",
        Done: "Zrobione",
        Fixie: "Fiksi",
        Cancel: "Anuluj",
        Close: "Zamknij",
        "Width and height must be greater than zero and less than the maximum({max}px)": "Szeroko\u015b\u0107 i wysoko\u015b\u0107 musz\u0105 wynosi\u0107 wi\u0119cej ni\u017c zero i mniej ni\u017c maksimum ({max}px)",
        Redo: "Powt\u00f3rz",
        Size: "Rozmiar",
        "e-mail address": "Adres e-mail",
        Eraser: "Gumka",
        Min: "Min",
        Cherry: "Wi\u015bniowo",
        "Are you sure? This can distort your image": "Na pewno? To mo\u017ce zniekszta\u0142ci\u0107 obraz",
        Custom: "Kadr dowolny",
        Fade: "Blakn\u0105\u0107",
        Singe: "Wypalenie",
        Drifter: "W\u0142\u00f3cz\u0119ga",
        Saturation: "Nasycenie",
        "Crop again": "Kadruj jeszcze raz",
        Power: "Si\u0142a",
        Max: "Maks",
        Redeye: "Oczy",
        Halftone: "P\u00f3\u0142ton",
        Corners: "Naro\u017cniki",
        "Old Photo": "Stare zdj\u0119cie",
        Laguna: "Laguna",
        Resize: "Rozmiar",
        Brush: "P\u0119dzel",
        "Color Grading": "Gradacja koloru",
        Firefly: "\u015awi\u0119toja\u0144ski",
        Rotate: "Obr\u00f3\u0107",
        "Applying effects": "Efekty s\u0105 wprowadzane",
        "Enter text here": "Wpisz tekst",
        "Code Red": "Czerwony kod",
        "Interested? We'll send you some info.": "Jeste\u015b zainteresowany? Wy\u015blemy Ci wi\u0119cej informacji",
        Mohawk: "Irokez",
        Concorde: "Szaro\u015bci",
        "Vignette Blur": "Winieta",
        "About this editor": "O tym edytorze",
        Discard: "Odrzuci\u0107",
        "Film Grain": "Film",
        Color: "Kolor",
        Tools: "Narz\u0119dzia",
        Crop: "Kadruj",
        "Edit Top Text": "Edytuj g\u00f3rny tekst",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "Mo\u017cesz utraci\u0107 wszystkie zmiany wprowadzone w tym edytorze. Czy na pewno chcesz wyj\u015b\u0107?",
        Apply: "Zastosuj",
        Stickers: "Naklejki"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.pt = {
        Loading: "Carregando",
        Night: "Noite",
        Nostalgia: "Nostalgia",
        Aviary: "Aviary",
        Width: "Largura",
        "No effects found for this pack.": "Nenhum efeito encontrado para este pacote",
        Blur: "Desfoque",
        "Your image was cropped.": "Sua imagem foi recortada.",
        Sharpen: "Tornar N\u00edtido",
        "Learn more!": "Saiba mais!",
        Ripped: "Rasgado",
        Indiglow: "Indiglow",
        "There is another image editing window open.  Close it without saving and continue?": "H\u00e1 outra imagem com o editor aberto. Quer fechar sem salvar as mudan\u00e7as e continuar?",
        Resume: "Retomar",
        "A filter pack has been updated. Click ok to reload the packs list.": "Um pacote de filtro foi atualizado. Clique em OK para recarregar a lista de pacotes",
        Update: "Atualizar",
        Free: "Livre",
        "There was an error downloading the image, please try again later.": "Houve um erro ao baixar a imagem. Tente novamente mais tarde",
        Effects: "Efeitos",
        "Sorry, there's no application on your phone to handle this action.": "Desculpe, n\u00e3o h\u00e1 aplicativo no seu telefone para realizar esta a\u00e7\u00e3o",
        Tools: "Ferramentas",
        "Don't ask me again": "N\u00e3o perguntar de novo",
        Reset: "Restabelecer",
        "File saved": "Arquivo salvo",
        Blemish: "Mancha",
        Bulge: "Aumentar",
        Alice: "Alice",
        "Destination folder": "Pasta de destino",
        "Original size": "Original",
        "Are you sure you want to remove this sticker?": "Tem certeza de que deseja remover este sticker?",
        "Revert to original?": "Reverter para original?",
        Mohawk: "Moicano",
        Enhance: "Real\u00e7ar",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary \u00e9 um SDK dispon\u00edvel para iOS e Android que permite que voc\u00ea adicione fun\u00e7\u00f5es para editar fotos e efeitos ao seu aplicativo com apenas poucas linhas de c\u00f3digo.",
        Greeneye: "Olho verde",
        Shadow: "Sombra",
        Vogue: "Moda",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "Voc\u00ea est\u00e1 prestes a perder todas as mudan\u00e7as que voc\u00ea fez. Tem certeza de que deseja reverter para a imagem original?",
        OK: "OK",
        "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?": "Desculpe, n\u00e3o h\u00e1 aplicativo no seu telefone para realizar esta a\u00e7\u00e3o. Voc\u00ea quer baix\u00e1-lo do mercado?",
        Intensity: "Intensidade",
        Whiten: "Iluminar",
        Frames: "Frames",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "Adicione um pouco de gr\u00e3o e d\u00ea um ar envelhecido \u00e0s suas fotos com estes seis efeitos de sujidade.",
        "Delete selected": "Excluir selecionados",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "Um pacote de sticker foi atualizado. Precisamos recarregar o painel atual. Deseja aplicar o sticker atual?",
        "Set color": "Definir cor",
        Confirm: "Confirmar",
        Siesta: "Sesta",
        Send: "Enviar",
        "Keep editing": "Seguir editando",
        "Powered by Aviary.com": "Desenvolvido por Aviary.com",
        Zoom: "Zoom",
        Editor: "Editor",
        "Biggest size": "Maior",
        "Soft Focus": "Foco Suave",
        Save: "Salvar",
        "Are you sure?": "Tem certeza?",
        Warmth: "Calor",
        Meme: "Meme",
        Charcoal: "Carv\u00e3o",
        Malibu: "Malibu",
        Grunge: "Grunge",
        "Tool Selection": "Selecionar ferramenta",
        Auto: "Auto",
        Tool: "Ferramenta",
        Settings: "Configura\u00e7\u00f5es",
        Eddie: "Eddie",
        "Medium size": "M\u00e9dio",
        Store: "Armazenar",
        Backlit: "Luz de fundo",
        Fixie: "Fixie",
        "Are you sure you want to discard changes from this tool?": "Tem certeza que quer eliminar as mudan\u00e7as feitas com esta ferramenta?",
        Brightness: "Brilho",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "Espere! Voc\u00ea n\u00e3o salvou as mudan\u00e7as. Tem certeza que quer fechar este editor?",
        Smooth: "Alise",
        "Get this editor": "Obter este editor",
        Draw: "Desenhar",
        Flip: "Virar",
        "Soft Brushes": "Pinc\u00e9is suaves",
        "View Image": "Visualizar Imagem",
        Viewfinder: "Acha-vistas",
        "Your work was saved!": "Seu trabalho foi salvo!",
        "Small size": "Pequeno",
        Delete: "Excluir",
        Square: "Quadrado",
        Rounded: "Redondo",
        Redo: "Refazer",
        "Preset Sizes": "Tamanhos",
        Sharpness: "Nitidez",
        Back: "Voltar",
        "Brush softness": "Suavidade do pincel",
        Brush: "Pincel",
        Mirror: "Espelho",
        "Edit Bottom Text": "Editar subescrito",
        "Photo Editor": "Editor de Fotos",
        "Maintain proportions": "Manter propor\u00e7\u00f5es",
        Vivid: "V\u00edvido",
        "San Carmen": "San Carmen",
        Exit: "Sair",
        Undo: "Desfazer",
        "Loading Image...": "Carregando imagem...",
        Borders: "Fronteiras",
        Contrast: "Contraste",
        "Saving...": "Salvando...",
        "Choose Color": "Escolher cor",
        Strato: "Strato",
        Vignette: "Vinheta",
        "Zoom Mode": "Modo de zoom",
        "A sticker pack has been updated. We need to reload the current panel.": "Um pacote de adesivos foi atualizado. Precisamos recarregar o painel atual",
        Vigilante: "Justiceiro",
        "Image saved in %1$s. Do you want to see the saved image?": "Imagem salva em %1$s. Voc\u00ea quer visualizar a imagem salva?",
        "Hard Brushes": "Pinc\u00e9is duros",
        "Brush size": "Tamanho do pincel",
        "Get More": "Obter Mais",
        "Color Matrix": "Matriz de cores",
        Corners: "Cantos",
        Aqua: "Aqua",
        "Output Image Size": "Tamanho da Imagem de Sa\u00edda",
        Ragged: "Esfarrapado",
        Ventura: "Ventura",
        Error: "Erro",
        "You can change this property in the Settings panel.": "Voc\u00ea pode modificar esta propriedade no painel de Configura\u00e7\u00f5es",
        Kurt: "Kurt",
        Balance: "Balan\u00e7o",
        Original: "Original",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "Sua imagem foi reduzida temporariamente para que seja mais f\u00e1cil de editar. Quando clicar Salvar, ela ser\u00e1 salva no tamanho normal.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "Recorde mem\u00f3rias queridas e bons tempos com os nossos seis efeitos nost\u00e1lgicos de sonho.",
        "Oops, there was an error while saving the image.": "\u00d4opa, ocorreu um erro ao tentar salvar a imagem.",
        Orientation: "Orienta\u00e7\u00e3o",
        "Add Text": "Adicionar Texto",
        Classic: "Cl\u00e1ssico",
        Text: "Texto",
        "No stickers defined in Feather_Stickers.": "N\u00e3o h\u00e1 adesivos definidos em Suavizar_Stickers",
        "Drag corners to resize crop area": "Arraste as arestas para modificar o tamanho do corte",
        "Give feedback": "Observa\u00e7\u00f5es",
        "Get this pack!": "Obtenha este pacote!",
        Height: "Altura",
        Colors: "Cores",
        Done: "Feito",
        "See your world a little differently with these six high-tech camera effects.": "Veja seu mundo um pouco diferente com esses 6 efeitos de c\u00e2mera superavan\u00e7ados.",
        Cancel: "Cancelar",
        Close: "Fechar",
        "Width and height must be greater than zero and less than the maximum({max}px)": "A largura e a altura devem ser superiores a zero e inferiores ao limite m\u00e1ximo ({max} pixels)",
        "Leave editor": "Sair do editor",
        Size: "Tamanho",
        "e-mail address": "Endere\u00e7o de email",
        "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!": "\u00d4oooopa! Eu falhei, mas um relat\u00f3rio foi enviado ao meu desenvolvedor para ajud\u00e1-lo a consertar esse problema!",
        Fade: "Desvanecer",
        Min: "M\u00ednimo",
        Cherry: "Cereja",
        "Are you sure? This can distort your image": "Tem certeza? Esta a\u00e7\u00e3o pode deformar a imagem",
        "A sticker pack has been updated. Click ok to reload the packs list.": "Um pacote de adesivos foi atualizado.  Clique em OK para recarregar a lista de pacotes",
        Custom: "Seu jeito",
        Eraser: "Borracha",
        Singe: "Chamuscado",
        Drifter: "Andarilho",
        Saturation: "Satura\u00e7\u00e3o",
        "Crop again": "Cortar novamente",
        "Aviary Editor": "Editor Aviary",
        "Applying action %2$i of %2$i": "Aplicando a\u00e7\u00e3o %2$i de %2$i",
        Max: "M\u00e1ximo",
        Attention: "Aten\u00e7\u00e3o",
        Redeye: "Olhos",
        Halftone: "Meio-Tom",
        "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?": "\u00d4opa, ocorreu um erro ao tentar salvar a imagem na pasta Aviary. Voc\u00ea quer tentar salv\u00e1-la na pasta padr\u00e3o da c\u00e2mera?",
        Pinch: "Suc\u00e7\u00e3o",
        "Old Photo": "Foto antiga",
        Laguna: "Laguna",
        Resize: "Mudar o Tamanho",
        "Powered by": "Desenvolvido por",
        "Color Grading": "Classifica\u00e7\u00e3o de cores",
        Firefly: "Vaga-Lume",
        Rotate: "Girar",
        "Applying effects": "Aplicando Efeitos",
        "Enter text here": "Insira texto aqui",
        "Interested? We'll send you some info.": "Interessado? Lhe enviaremos  informa\u00e7\u00f5es.",
        Remove: "Remover",
        Concorde: "Concorde",
        "Vignette Blur": "Desfoque do esbo\u00e7o",
        "About this editor": "Sobre este editor",
        Discard: "Eliminar",
        "Film Grain": "Foto Granulada",
        Power: "Comando",
        Color: "Cor",
        Demo: "Demonstra\u00e7\u00e3o",
        Crop: "Cortar",
        "Edit Top Text": "Editar sobrescrito",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "Voc\u00ea vai perder as mudan\u00e7as que fez com esta ferramenta. Tem certeza que quer sair?",
        Apply: "Aplicar",
        Stickers: "Adesivos"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.pt_br = {
        Loading: "Carregando",
        "Toy Camera": "Toy Camera",
        Night: "Noite",
        Nostalgia: "Nostalgia",
        Aviary: "Aviary",
        Width: "Largura",
        "No effects found for this pack.": "N\u00e3o se encontraram efeitos para este pacote",
        Blur: "Desfoque",
        "Your image was cropped.": "Sua imagem foi recortada.",
        Sharpen: "Tornar N\u00edtido",
        "Learn more!": "Saiba mais!",
        Ripped: "Rasgado",
        Indiglow: "Indiglow",
        "There is another image editing window open.  Close it without saving and continue?": "H\u00e1 outra imagem com o editor aberto. Quer fechar sem salvar as mudan\u00e7as e continuar?",
        Resume: "Retomar",
        Heatwave: "Heatwave",
        "A filter pack has been updated. Click ok to reload the packs list.": "Um pacote de filtro foi atualizado. Clique em OK para recarregar a lista de pacotes",
        Update: "Atualizar",
        Free: "Livre",
        "There was an error downloading the image, please try again later.": "Houve um erro ao baixar a imagem. Tente novamente mais tarde",
        Effects: "Efeitos",
        "Sorry, there's no application on your phone to handle this action.": "Desculpe, n\u00e3o h\u00e1 aplicativo no seu telefone para realizar esta a\u00e7\u00e3o",
        Tools: "Ferramentas",
        "Don't ask me again": "N\u00e3o perguntar de novo",
        Reset: "Restabelecer",
        "File saved": "Arquivo salvo",
        Blemish: "Mancha",
        Bulge: "Aumentar",
        Alice: "Alice",
        "Destination folder": "Pasta de destino",
        "Original size": "Original",
        "Are you sure you want to remove this sticker?": "Tem certeza de que deseja remover este sticker?",
        "Revert to original?": "Reverter para original?",
        Mohawk: "Mohawk",
        Enhance: "Real\u00e7ar",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary \u00e9 um SDK dispon\u00edvel para iOS e Android que permite que voc\u00ea adicione fun\u00e7\u00f5es para editar fotos e efeitos ao seu aplicativo com apenas poucas linhas de c\u00f3digo.",
        Greeneye: "Olho verde",
        Shadow: "Sombra",
        Vogue: "Vogue",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "Voc\u00ea est\u00e1 prestes a perder todas as mudan\u00e7as que voc\u00ea fez. Tem certeza de que deseja reverter para a imagem original?",
        OK: "OK",
        "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?": "Desculpe, n\u00e3o h\u00e1 aplicativo no seu telefone para realizar esta a\u00e7\u00e3o. Voc\u00ea quer baix\u00e1-lo do mercado?",
        Intensity: "Intensidade",
        Whiten: "Iluminar",
        Frames: "Frames",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "D\u00ea um efeito granulado e um desgaste visual natural \u00e0s suas fotos com estes seis efeitos.",
        "Delete selected": "Excluir selecionados",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "Um pacote de sticker foi atualizado. Precisamos recarregar o painel atual. Deseja aplicar o sticker atual?",
        "Set color": "Definir cor",
        "Always Sunny": "Always Sunny",
        Confirm: "Confirmar",
        Siesta: "Siesta",
        Negative: "Negative",
        Send: "Enviar",
        "Keep editing": "Seguir editando",
        "Powered by Aviary.com": "Desenvolvido por Aviary.com",
        Zoom: "Zoom",
        Editor: "Editor",
        "Biggest size": "Maior",
        "Soft Focus": "Soft Focus",
        Save: "Salvar",
        "Are you sure?": "Tem certeza?",
        Warmth: "Calor",
        More: "Mais",
        Meme: "Meme",
        Charcoal: "Carv\u00e3o",
        Malibu: "Malibu",
        Grunge: "Grunge",
        "Tool Selection": "Selecionar ferramenta",
        Auto: "Auto",
        Tool: "Ferramenta",
        Settings: "Configura\u00e7\u00f5es",
        Eddie: "Eddie",
        Cinematic: "Cinematic",
        "Medium size": "M\u00e9dio",
        Store: "Armazenar",
        Backlit: "Luz de fundo",
        Fixie: "Fixie",
        "Are you sure you want to discard changes from this tool?": "Tem certeza que quer eliminar as mudan\u00e7as feitas com esta ferramenta?",
        Brightness: "Brilho",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "Espere! Voc\u00ea n\u00e3o salvou as mudan\u00e7as. Tem certeza que quer fechar este editor?",
        Smooth: "Alise",
        "Get this editor": "Obter este editor",
        Draw: "Desenhar",
        Flip: "Virar",
        "Soft Brushes": "Pinc\u00e9is suaves",
        "View Image": "Visualizar Imagem",
        Viewfinder: "Acha-vistas",
        "Your work was saved!": "Seu trabalho foi salvo!",
        "Small size": "Pequeno",
        Delete: "Excluir",
        Square: "Quadrado",
        Rounded: "Arredondado",
        Redo: "Refazer",
        "Preset Sizes": "Tamanhos",
        Sharpness: "Nitidez",
        Back: "Voltar",
        "Brush softness": "Suavidade do pincel",
        Brush: "Pincel",
        Mirror: "Espelho",
        "Edit Bottom Text": "Editar subescrito",
        "Photo Editor": "Editor de fotos",
        "Maintain proportions": "Manter propor\u00e7\u00f5es",
        Vivid: "Vivid",
        "San Carmen": "San Carmen",
        Retro: "Retro",
        Exit: "Sair",
        Undo: "Desfazer",
        "Loading Image...": "Carregando imagem...",
        Borders: "Fronteiras",
        Contrast: "Contraste",
        "Saving...": "Salvando...",
        "Instant!": "Instant!",
        "Choose Color": "Escolher cor",
        Strato: "Strato",
        Vignette: "Vinheta",
        "Zoom Mode": "Modo de zoom",
        "A sticker pack has been updated. We need to reload the current panel.": "Um pacote de adesivos foi atualizado. Precisamos recarregar o painel atual",
        Vigilante: "Vigilante",
        "Image saved in %1$s. Do you want to see the saved image?": "Imagem salva em %1$s. Voc\u00ea quer visualizar a imagem salva?",
        "Hard Brushes": "Pinc\u00e9is duros",
        "Brush size": "Tamanho do pincel",
        "Get More": "Obter mais",
        "Color Matrix": "Matriz de cores",
        Corners: "Cantos",
        Aqua: "Aqua",
        "Output Image Size": "Tamanho da Imagem de Sa\u00edda",
        Ragged: "Esfarrapado",
        Ventura: "Ventura",
        Error: "Erro",
        "You can change this property in the Settings panel.": "Voc\u00ea pode modificar esta propriedade no painel de Configura\u00e7\u00f5es",
        Kurt: "Kurt",
        Balance: "Equil\u00edbrio",
        Original: "Original",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "Sua imagem foi reduzida temporariamente para que seja mais f\u00e1cil de editar. Quando clicar Salvar, ela ser\u00e1 salva no tamanho normal.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "Reviva lembran\u00e7as ternas e os bons tempos com os nossos seis sonhadores efeitos de nostalgia.",
        "Oops, there was an error while saving the image.": "\u00d4opa, ocorreu um erro ao tentar salvar a imagem.",
        Orientation: "Orienta\u00e7\u00e3o",
        "Add Text": "Texto",
        Classic: "Cl\u00e1ssico",
        Text: "Texto",
        "No stickers defined in Feather_Stickers.": "N\u00e3o h\u00e1 adesivos definidos em Suavizar_Stickers",
        "Drag corners to resize crop area": "Arraste as arestas para modificar o tamanho do corte",
        "Give feedback": "Observa\u00e7\u00f5es",
        "Get this pack!": "Obtenha este pacote!",
        Height: "Altura",
        Colors: "Cores",
        Done: "Feito",
        "See your world a little differently with these six high-tech camera effects.": "Veja seu mundo um pouco diferente com esses 6 efeitos de c\u00e2mera superavan\u00e7ados.",
        Cancel: "Cancelar",
        Close: "Fechar",
        "Width and height must be greater than zero and less than the maximum({max}px)": "A largura e a altura devem ser superiores a zero e inferiores ao limite m\u00e1ximo ({max} pixels)",
        "Leave editor": "Sair do editor",
        Size: "Tamanho",
        "e-mail address": "Endere\u00e7o de email",
        "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!": "\u00d4oooopa! Eu falhei, mas um relat\u00f3rio foi enviado ao meu desenvolvedor para ajud\u00e1-lo a consertar esse problema!",
        Fade: "Desvanecer",
        Min: "M\u00ednimo",
        Cherry: "Cherry",
        "Are you sure? This can distort your image": "Tem certeza? Esta a\u00e7\u00e3o pode deformar a imagem",
        "A sticker pack has been updated. Click ok to reload the packs list.": "Um pacote de adesivos foi atualizado.  Clique em OK para recarregar a lista de pacotes",
        Custom: "Seu jeito",
        Eraser: "Borracha",
        Singe: "Singe",
        Drifter: "Drifter",
        Saturation: "Satura\u00e7\u00e3o",
        "Crop again": "Cortar novamente",
        "Aviary Editor": "Editor Aviary",
        "Applying action %2$i of %2$i": "Aplicando a\u00e7\u00e3o %2$i de %2$i",
        Max: "M\u00e1ximo",
        Attention: "Aten\u00e7\u00e3o",
        Redeye: "Olhos",
        Halftone: "Meio-tom",
        "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?": "\u00d4opa, ocorreu um erro ao tentar salvar a imagem na pasta Aviary. Voc\u00ea quer tentar salv\u00e1-la na pasta padr\u00e3o da c\u00e2mera?",
        Pinch: "Suc\u00e7\u00e3o",
        "Old Photo": "Foto antiga",
        Laguna: "Laguna",
        Resize: "Mudar o Tamanho",
        "Powered by": "Desenvolvido por",
        "Color Grading": "Classifica\u00e7\u00e3o de cores",
        Firefly: "Firefly",
        Rotate: "Girar",
        "Applying effects": "Aplicando Efeitos",
        Daydream: "Daydream",
        "Enter text here": "Insira texto aqui",
        "Code Red": "Code Red",
        "Interested? We'll send you some info.": "Interessado? Lhe enviaremos  informa\u00e7\u00f5es.",
        Remove: "Remover",
        Concorde: "Concorde",
        "Vignette Blur": "Desfoque do esbo\u00e7o",
        "About this editor": "Sobre este editor",
        Discard: "Eliminar",
        "Film Grain": "Foto Granulada",
        Power: "Comando",
        Color: "Cor",
        Demo: "Demonstra\u00e7\u00e3o",
        Crop: "Cortar",
        "Edit Top Text": "Editar sobrescrito",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "Voc\u00ea vai perder as mudan\u00e7as que fez com esta ferramenta. Tem certeza que quer sair?",
        Apply: "Aplicar",
        Stickers: "Adesivos"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.ru = {
        Loading: "\u0417\u0430\u0433\u0440\u0443\u0437\u043a\u0430...",
        "Toy Camera": "\u041c\u044b\u043b\u044c\u043d\u0438\u0446\u0430",
        Night: "\u041d\u043e\u0447\u044c",
        Nostalgia: "\u041d\u043e\u0441\u0442\u0430\u043b\u044c\u0433\u0438\u044f",
        Aviary: "\u0410vi\u0430ry",
        Width: "\u0428\u0438\u0440\u0438\u043d\u0430",
        "No effects found for this pack.": "\u0414\u043b\u044f \u0434\u0430\u043d\u043d\u043e\u0433\u043e \u043f\u0430\u043a\u0435\u0442\u0430 \u044d\u0444\u0444\u0435\u043a\u0442\u043e\u0432 \u043d\u0435 \u043d\u0430\u0439\u0434\u0435\u043d\u043e",
        Blur: "\u0420\u0430\u0437\u043c\u044b\u0442\u0438\u0435",
        "Your image was cropped.": "\u0412\u0430\u0448\u0435 \u0444\u043e\u0442\u043e \u043e\u0431\u0440\u0435\u0437\u0430\u043d\u043e",
        Sharpen: "\u0420\u0435\u0437\u0447\u0435",
        "Learn more!": "\u0423\u0437\u043d\u0430\u0439\u0442\u0435 \u0431\u043e\u043b\u044c\u0448\u0435!",
        Ripped: "\u0420\u0432\u0430\u043d\u043e\u0435",
        Indiglow: "\u0418\u043d\u0434\u0438\u0433\u043b\u043e\u0443",
        "There is another image editing window open.  Close it without saving and continue?": "\u041e\u0442\u043a\u0440\u044b\u0442\u043e \u0434\u0440\u0443\u0433\u043e\u0435 \u043e\u043a\u043d\u043e \u0440\u0435\u0434\u0430\u043a\u0442\u0438\u0440\u043e\u0432\u0430\u043d\u0438\u044f. \u0417\u0430\u043a\u0440\u044b\u0442\u044c \u0435\u0433\u043e \u0431\u0435\u0437 \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u0438\u044f \u0438 \u043f\u0440\u043e\u0434\u043e\u043b\u0436\u0438\u0442\u044c?",
        Resume: "\u041e\u0442\u043c\u0435\u043d\u0430",
        Heatwave: "\u0412\u043e\u043b\u043d\u0430",
        "A filter pack has been updated. Click ok to reload the packs list.": "\u041f\u0430\u043a\u0435\u0442 \u0444\u0438\u043b\u044c\u0442\u0440\u043e\u0432 \u043e\u0431\u043d\u043e\u0432\u043b\u0435\u043d. \u041d\u0430\u0436\u043c\u0438\u0442\u0435 \u0434\u043b\u044f \u043f\u0435\u0440\u0435\u0437\u0430\u0433\u0440\u0443\u0437\u043a\u0438 \u0441\u043f\u0438\u0441\u043a\u0430 \u043f\u0430\u043a\u0435\u0442\u043e\u0432",
        Update: "\u041e\u0431\u043d\u043e\u0432\u043b\u0435\u043d\u0438\u0435",
        Free: "\u0411\u0435\u0441\u043f\u043b\u0430\u0442\u043d\u043e",
        "There was an error downloading the image, please try again later.": "\u041f\u0440\u043e\u0438\u0437\u043e\u0448\u043b\u0430 \u043e\u0448\u0438\u0431\u043a\u0430 \u043f\u0440\u0438 \u0437\u0430\u0433\u0440\u0443\u0437\u043a\u0435 \u0438\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u044f. \u041f\u043e\u0432\u0442\u043e\u0440\u0438\u0442\u0435 \u043f\u043e\u043f\u044b\u0442\u043a\u0443 \u043f\u043e\u0437\u0434\u043d\u0435\u0435",
        Effects: "\u042d\u0444\u0444\u0435\u043a\u0442\u044b",
        "Sorry, there's no application on your phone to handle this action.": "\u041a \u0441\u043e\u0436\u0430\u043b\u0435\u043d\u0438\u044e, \u043d\u0430 \u0432\u0430\u0448\u0435\u043c \u0442\u0435\u043b\u0435\u0444\u043e\u043d\u0435 \u043d\u0435\u0442 \u043f\u0440\u0438\u043b\u043e\u0436\u0435\u043d\u0438\u044f, \u043a\u043e\u0442\u043e\u0440\u043e\u0435 \u043c\u043e\u0433\u043b\u043e \u0431\u044b \u0432\u044b\u043f\u043e\u043b\u043d\u0438\u0442\u044c \u044d\u0442\u043e \u0434\u0435\u0439\u0441\u0442\u0432\u0438\u0435",
        Tools: "\u041d\u0430\u0437\u0430\u0434",
        "Don't ask me again": "\u0411\u043e\u043b\u044c\u0448\u0435 \u043d\u0435 \u0441\u043f\u0440\u0430\u0448\u0438\u0432\u0430\u0442\u044c",
        Reset: "\u0421\u0431\u0440\u043e\u0441",
        "File saved": "\u0424\u0430\u0439\u043b \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d",
        Blemish: "\u0414\u0435\u0444\u0435\u043a\u0442",
        Bulge: "\u0412\u044b\u043f\u0443\u043a\u043b\u043e",
        Alice: "\u0410\u043b\u0438\u0441\u0430",
        "Destination folder": "\u041f\u0430\u043f\u043a\u0430 \u043d\u0430\u0437\u043d\u0430\u0447\u0435\u043d\u0438\u044f",
        "Original size": "\u0418\u0441\u0445\u043e\u0434\u043d\u044b\u0439 \u0440\u0430\u0437\u043c\u0435\u0440",
        "Are you sure you want to remove this sticker?": "\u0412\u044b \u0443\u0432\u0435\u0440\u0435\u043d\u044b, \u0447\u0442\u043e \u0445\u043e\u0442\u0438\u0442\u0435 \u0443\u0434\u0430\u043b\u0438\u0442\u044c \u044d\u0442\u0443 \u043d\u0430\u043a\u043b\u0435\u0439\u043a\u0443?",
        "Revert to original?": "\u041e\u0442\u043c\u0435\u043d\u0438\u0442\u044c \u0432\u0441\u0435 \u0438\u0437\u043c\u0435\u043d\u0435\u043d\u0438\u044f?",
        Mohawk: "\u0418\u0440\u043e\u043a\u0435\u0437",
        Enhance: "\u0423\u043b\u0443\u0447\u0448\u0438\u0442\u044c",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Avairy \u044d\u0442\u043e \u0431\u0435\u0441\u043f\u043b\u0430\u0442\u043d\u0430\u044f SDK, \u0434\u043e\u0441\u0442\u0443\u043f\u043d\u0430\u044f \u0434\u043b\u044f IOS \u0438 Android, \u043a\u043e\u0442\u043e\u0440\u0430\u044f \u043f\u043e\u0437\u0432\u043e\u043b\u044f\u0435\u0442 \u0412\u0430\u043c \u0432\u043e\u0437\u043c\u043e\u0436\u043d\u043e\u0441\u0442\u044c \u0440\u0435\u0434\u0430\u043a\u0442\u0438\u0440\u043e\u0432\u0430\u043d\u0438\u044f \u0438 \u043d\u0430\u043b\u043e\u0436\u0435\u043d\u0438\u0435 \u044d\u0444\u0444\u0435\u043a\u0442\u043e\u0432 \u043d\u0430 \u0432\u0430\u0448\u0438 \u0444\u043e\u0442\u043e \u0432 \u0432\u0430\u0448\u0435\u043c \u043f\u0440\u0438\u043b\u043e\u0436\u0435\u043d\u0438\u0438 \u0441 \u043f\u043e\u043c\u043e\u0449\u044c\u044e \u043d\u0435\u0441\u043a\u043e\u043b\u044c\u043a\u0438\u0445 \u0441\u0442\u0440\u043e\u043a \u043a\u043e\u0434\u0430.",
        Greeneye: "\u0417\u0435\u043b. \u0433\u043b\u0430\u0437\u0430",
        Shadow: "\u0422\u0435\u043d\u044c",
        Vogue: "\u041c\u043e\u0434\u0430",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "\u0412\u044b \u043c\u043e\u0436\u0435\u0442\u0435 \u043f\u043e\u0442\u0435\u0440\u044f\u0442\u044c \u0432\u0441\u0435 \u0441\u0434\u0435\u043b\u0430\u043d\u043d\u044b\u0435 \u0432\u0430\u043c\u0438 \u0438\u0437\u043c\u0435\u043d\u0435\u043d\u0438\u044f. \u0412\u044b \u0443\u0432\u0435\u0440\u0435\u043d\u044b, \u0447\u0442\u043e \u0445\u043e\u0442\u0438\u0442\u0435 \u043e\u0442\u043c\u0435\u043d\u0438\u0442\u044c \u0438\u0437\u043c\u0435\u043d\u0435\u043d\u0438\u044f \u0438 \u0432\u0435\u0440\u043d\u0443\u0442\u044c\u0441\u044f \u043a \u0438\u0441\u0445\u043e\u0434\u043d\u043e\u043c\u0443 \u0438\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u044e?",
        OK: "\u041e\u043a",
        "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?": "\u041a \u0441\u043e\u0436\u0430\u043b\u0435\u043d\u0438\u044e, \u043d\u0430 \u0432\u0430\u0448\u0435\u043c \u0442\u0435\u043b\u0435\u0444\u043e\u043d\u0435 \u043d\u0435\u0442 \u043f\u0440\u0438\u043b\u043e\u0436\u0435\u043d\u0438\u044f, \u043a\u043e\u0442\u043e\u0440\u043e\u0435 \u043c\u043e\u0433\u043b\u043e \u0431\u044b \u0432\u044b\u043f\u043e\u043b\u043d\u0438\u0442\u044c \u044d\u0442\u0443 \u043e\u043f\u0435\u0440\u0430\u0446\u0438\u044e. \u0421\u043a\u0430\u0447\u0430\u0442\u044c \u0441\u0435\u0439\u0447\u0430\u0441 \u0438\u0437 \u043c\u0430\u0433\u0430\u0437\u0438\u043d\u0430 Market?",
        Intensity: "\u0418\u043d\u0442\u0435\u043d\u0441\u0438\u0432\u043d\u043e\u0441\u0442\u044c",
        Whiten: "\u0421\u0432\u0435\u0442\u043b\u0435\u0439",
        Frames: "\u041a\u0430\u0434\u0440\u044b",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": '\u0414\u043e\u0431\u0430\u0432\u044c\u0442\u0435 \u043d\u0430 \u0441\u0432\u043e\u0438 \u0444\u043e\u0442\u043e\u0433\u0440\u0430\u0444\u0438\u0438 \u0437\u0435\u0440\u043d\u0438\u0441\u0442\u043e\u0441\u0442\u044c \u0438 \u043f\u043e\u0442\u0435\u0440\u0442\u043e\u0441\u0442\u044c \u043f\u0440\u0438 \u043f\u043e\u043c\u043e\u0449\u0438 \u044d\u0442\u0438\u0445 \u0448\u0435\u0441\u0442\u0438 \\"\u0433\u0440\u044f\u0437\u043d\u044b\u0445\\" \u044d\u0444\u0444\u0435\u043a\u0442\u043e\u0432.',
        "Delete selected": "\u0423\u0434\u0430\u043b\u0438\u0442\u044c \u0432\u044b\u0431\u0440\u0430\u043d\u043d\u043e\u0435",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "\u041f\u0430\u043a\u0435\u0442 \u043d\u0430\u043a\u043b\u0435\u0435\u043a \u043e\u0431\u043d\u043e\u0432\u043b\u0435\u043d. \u041d\u0430\u043c \u043d\u0443\u0436\u043d\u043e \u043f\u0435\u0440\u0435\u0437\u0430\u0433\u0440\u0443\u0437\u0438\u0442\u044c \u0442\u0435\u043a\u0443\u0449\u0443\u044e \u043f\u0430\u043d\u0435\u043b\u044c. \u041f\u0440\u0438\u043c\u0435\u043d\u0438\u0442\u044c \u0442\u0435\u043a\u0443\u0449\u0443\u044e \u043d\u0430\u043a\u043b\u0435\u0439\u043a\u0443?",
        "Set color": "\u0423\u0441\u0442\u0430\u043d\u043e\u0432\u0438\u0442\u044c \u0446\u0432\u0435\u0442",
        "Always Sunny": "\u0421\u043e\u043b\u043d\u0435\u0447\u043d\u043e",
        Confirm: "\u041f\u043e\u0434\u0442\u0432\u0435\u0440\u0434\u0438\u0442\u044c",
        Siesta: "\u0421\u0438\u0435\u0441\u0442\u0430",
        Negative: "\u041d\u0435\u0433\u0430\u0442\u0438\u0432",
        Send: "\u041f\u043e\u0441\u043b\u0430\u0442\u044c",
        "Keep editing": "\u041f\u0440\u043e\u0434\u043e\u043b\u0436\u0430\u0439\u0442\u0435 \u0440\u0435\u0434\u0430\u043a\u0442\u0438\u0440\u043e\u0432\u0430\u0442\u044c",
        "Powered by Aviary.com": "\u0420\u0430\u0431\u043e\u0442\u0430\u0435\u0442 \u043d\u0430",
        Zoom: "\u0423\u0432\u0435\u043b\u0438\u0447\u0435\u043d\u0438\u0435",
        Editor: "\u0420\u0435\u0434\u0430\u043a\u0442\u043e\u0440",
        "Biggest size": "\u0421\u0430\u043c\u044b\u0439 \u0431\u043e\u043b\u044c\u0448\u043e\u0439 \u0440\u0430\u0437\u043c\u0435\u0440",
        "Soft Focus": "\u0420\u0430\u0441\u0444\u043e\u043a\u0443\u0441",
        Save: "\u0421\u043e\u0445\u0440",
        "Are you sure?": "\u0412\u044b \u0443\u0432\u0435\u0440\u0435\u043d\u044b?",
        Warmth: "\u0442\u0435\u043f\u043b\u043e",
        More: "\u0411\u043e\u043b\u044c\u0448\u0435",
        Meme: "Meme",
        Charcoal: "\u0422\u0435\u043c\u043d\u043e-\u0441\u0435\u0440\u043e\u0435",
        Malibu: "\u041c\u0430\u043b\u0438\u0431\u0443",
        Grunge: "\u0413\u0440\u044f\u0437\u044c",
        "Tool Selection": "\u0412\u044b\u0431\u043e\u0440 \u0438\u043d\u0441\u0442\u0440\u0443\u043c\u0435\u043d\u0442\u0430",
        Auto: "\u0410\u0432\u0442\u043e",
        Tool: "\u0418\u043d\u0441\u0442\u0440\u0443\u043c\u0435\u043d\u0442",
        Settings: "\u041d\u0430\u0441\u0442\u0440\u043e\u0439\u043a\u0438",
        Eddie: "\u042d\u0434\u0434\u0438",
        Cinematic: "\u041a\u0438\u043d\u043e",
        "Medium size": "\u0421\u0440\u0435\u0434\u043d\u0438\u0439 \u0440\u0430\u0437\u043c\u0435\u0440",
        Store: "\u041c\u0430\u0433\u0430\u0437\u0438\u043d",
        Backlit: "\u041f\u043e\u0434\u0441\u0432\u0435\u0442\u043a\u0430",
        Fixie: "\u0424\u0438\u043a\u0441\u0438",
        "Are you sure you want to discard changes from this tool?": "\u0412\u044b \u0434\u0435\u0439\u0441\u0442\u0432\u0438\u0442\u0435\u043b\u044c\u043d\u043e \u0445\u043e\u0442\u0438\u0442\u0435 \u043e\u0442\u043c\u0435\u043d\u0438\u0442\u044c \u0438\u0437\u043c\u0435\u043d\u0435\u043d\u0438\u044f?",
        Brightness: "\u042f\u0440\u043a\u043e\u0441\u0442\u044c",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "\u0412\u0430\u0448\u0430 \u0440\u0430\u0431\u043e\u0442\u0430 \u043d\u0435 \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u0430, \u0432\u044b \u0443\u0432\u0435\u0440\u0435\u043d\u044b \u0447\u0442\u043e \u0445\u043e\u0442\u0438\u0442\u0435 \u0437\u0430\u043a\u0440\u044b\u0442\u044c \u0440\u0435\u0434\u0430\u043a\u0442\u043e\u0440?",
        Smooth: "\u0413\u043b\u0430\u0434\u043a\u0430\u044f",
        "Get this editor": "\u0421\u043a\u0430\u0447\u0430\u0442\u044c \u0440\u0435\u0434\u0430\u043a\u0442\u043e\u0440",
        Draw: "\u0420\u0438\u0441\u0443\u043d\u043e\u043a",
        Flip: "\u041e\u0442\u0440\u0430\u0437\u0438\u0442\u044c",
        "Soft Brushes": "\u041c\u044f\u0433\u043a\u0438\u0435 \u043a\u0438\u0441\u0442\u0438",
        "View Image": "\u041f\u0440\u043e\u0441\u043c\u043e\u0442\u0440 \u0438\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u044f",
        Viewfinder: "\u0412\u0438\u0434\u043e\u0438\u0441\u043a\u0430\u0442\u0435\u043b\u044c",
        "Your work was saved!": "\u0412\u0430\u0448\u0430 \u0440\u0430\u0431\u043e\u0442\u0430 \u0431\u044b\u043b\u0430 \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u0430.",
        "Small size": "\u041c\u0430\u043b\u044b\u0439 \u0440\u0430\u0437\u043c\u0435\u0440",
        Delete: "\u0423\u0434\u0430\u043b\u0438\u0442\u044c",
        Square: "\u041a\u0432\u0430\u0434\u0440\u0430\u0442",
        Rounded: "\u0417\u0430\u043a\u0440\u0443\u0433\u043b\u0435\u043d\u043d\u044b\u0435",
        Redo: "\u041f\u043e\u0432\u0442\u043e\u0440",
        "Preset Sizes": "\u0421\u0442\u0430\u043d\u0434\u0430\u0440\u0442\u044b",
        Sharpness: "\u0420\u0435\u0437\u043a\u043e\u0441\u0442\u044c",
        Back: "\u041d\u0430\u0437\u0430\u0434",
        "Brush softness": "\u041c\u044f\u0433\u043a\u043e\u0441\u0442\u044c \u043a\u0438\u0441\u0442\u0438",
        Brush: "\u041a\u0438\u0441\u0442\u043e\u0447\u043a\u0430",
        Mirror: "\u0417\u0435\u0440\u043a\u0430\u043b\u043e",
        "Edit Bottom Text": "\u0418\u0437\u043c\u0435\u043d\u0438\u0442\u044c \u043d\u0438\u0436\u043d\u0438\u0439 \u0442\u0435\u043a\u0441\u0442",
        "Photo Editor": "\u0424\u043e\u0442\u043e\u0440\u0435\u0434\u0430\u043a\u0442\u043e\u0440",
        "Maintain proportions": "\u0421\u043e\u0445\u0440\u0430\u043d\u0438\u0442\u044c \u043f\u0440\u043e\u043f\u043e\u0440\u0446\u0438\u0438",
        Vivid: "\u042f\u0440\u043a\u0438\u0439",
        "San Carmen": "\u0421\u0430\u043d-\u041a\u0430\u0440\u043c\u0435\u043d",
        Retro: "\u0420\u0435\u0442\u0440\u043e",
        Exit: "\u0412\u044b\u0445\u043e\u0434",
        Undo: "\u041e\u0442\u043c\u0435\u043d\u0430",
        "Loading Image...": "\u0417\u0430\u0433\u0440\u0443\u0437\u043a\u0430 \u0438\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u044f...",
        Borders: "\u0413\u0440\u0430\u043d\u0438\u0446\u044b",
        Contrast: "\u041a\u043e\u043d\u0442\u0440\u0430\u0441\u0442",
        "Saving...": "\u0421\u043e\u0445\u0440\u0430\u043d\u044f\u0435\u0442\u0441\u044f...",
        "Instant!": "\u041f\u043e\u043b\u0430\u0440\u043e\u0438\u0434",
        "Choose Color": "\u0412\u044b\u0431\u0440\u0430\u0442\u044c \u0446\u0432\u0435\u0442",
        Strato: "\u0421\u0442\u0440\u0430\u0442\u043e",
        Vignette: "\u0412\u0438\u043d\u044c\u0435\u0442\u043a\u0430",
        "Zoom Mode": "\u0420\u0435\u0436\u0438\u043c \u0443\u0432\u0435\u043b\u0438\u0447\u0435\u043d\u0438\u044f",
        "A sticker pack has been updated. We need to reload the current panel.": "\u041f\u0430\u043a\u0435\u0442 \u043d\u0430\u043a\u043b\u0435\u0435\u043a \u043e\u0431\u043d\u043e\u0432\u043b\u0435\u043d. \u041d\u0430\u043c \u043d\u0443\u0436\u043d\u043e \u043f\u0435\u0440\u0435\u0437\u0430\u0433\u0440\u0443\u0437\u0438\u0442\u044c \u0442\u0435\u043a\u0443\u0449\u0443\u044e \u043f\u0430\u043d\u0435\u043b\u044c",
        Vigilante: "\u0412\u0438\u0434\u0436\u0438\u043b\u0430\u043d\u0442\u0435",
        "Image saved in %1$s. Do you want to see the saved image?": "\u0418\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u0435 \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u043e \u0432 %1$s. \u0425\u043e\u0442\u0438\u0442\u0435 \u0443\u0432\u0438\u0434\u0435\u0442\u044c \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u043d\u043e\u0435 \u0438\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u0435?",
        "Hard Brushes": "\u0422\u0432\u0435\u0440\u0434\u0430\u044f \u043a\u0438\u0441\u0442\u043e\u0447\u043a\u0430",
        "Brush size": "\u0420\u0430\u0437\u043c\u0435\u0440 \u043a\u0438\u0441\u0442\u0438",
        "Get More": "\u0415\u0449\u0435",
        "Color Matrix": "\u0426\u0432\u0435\u0442\u043e\u0432\u0430\u044f \u043c\u0430\u0442\u0440\u0438\u0446\u0430",
        Corners: "\u0423\u0433\u043e\u043b\u043a\u0438",
        Aqua: "\u0410\u043a\u0432\u0430",
        "Output Image Size": "\u0420\u0430\u0437\u043c\u0435\u0440 \u0438\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u044f \u043d\u0430 \u0432\u044b\u0445\u043e\u0434\u0435",
        Ragged: "\u0428\u0435\u0440\u043e\u0445\u043e\u0432\u0430\u0442\u043e\u0435",
        Ventura: "\u0421\u0443\u0434\u044c\u0431\u0430",
        Error: "\u041e\u0448\u0438\u0431\u043a\u0430",
        "You can change this property in the Settings panel.": '\u0412\u044b \u043c\u043e\u0436\u0435\u0442\u0435 \u0438\u0437\u043c\u0435\u043d\u0438\u0442\u044c \u044d\u0442\u043e \u0441\u0432\u043e\u0439\u0441\u0442\u0432\u043e \u043d\u0430 \u043f\u0430\u043d\u0435\u043b\u0438 \\"\u041f\u0430\u0440\u0430\u043c\u0435\u0442\u0440\u044b\\"',
        Kurt: "\u041a\u0443\u0440\u0442",
        Balance: "\u0411\u0430\u043b\u0430\u043d\u0441",
        Original: "\u041e\u0440\u0438\u0433",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": '\u0427\u0442\u043e\u0431\u044b \u0432\u0430\u043c \u0431\u044b\u043b\u043e \u043b\u0435\u0433\u0447\u0435 \u0440\u0435\u0434\u0430\u043a\u0442\u0438\u0440\u043e\u0432\u0430\u0442\u044c, \u043c\u044b \u0432\u0440\u0435\u043c\u0435\u043d\u043d\u043e \u0443\u043c\u0435\u043d\u044c\u0448\u0438\u043b\u0438 \u0444\u043e\u0442\u043e\u0433\u0440\u0430\u0444\u0438\u044e. \u041a\u043e\u0433\u0434\u0430 \u0432\u044b \u043d\u0430\u0436\u043c\u0435\u0442\u0435 \\"\u0421\u043e\u0445\u0440\u0430\u043d\u0438\u0442\u044c\\", \u0441\u043e\u0445\u0440\u0430\u043d\u0438\u0442\u0441\u044f \u0438\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u0435 \u0438\u0441\u0445\u043e\u0434\u043d\u043e\u0433\u043e \u0440\u0430\u0437\u043c\u0435\u0440\u0430.',
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "\u041e\u043a\u0443\u043d\u0438\u0442\u0435\u0441\u044c \u0432 \u0432\u043e\u0441\u043f\u043e\u043c\u0438\u043d\u0430\u043d\u0438\u044f \u043e \u043f\u0440\u043e\u0448\u043b\u043e\u043c \u043f\u0440\u0438 \u043f\u043e\u043c\u043e\u0449\u0438 \u044d\u0442\u0438\u0445 \u0448\u0435\u0441\u0442\u0438 \u043d\u043e\u0441\u0442\u0430\u043b\u044c\u0433\u0438\u0447\u0435\u0441\u043a\u0438\u0445 \u044d\u0444\u0444\u0435\u043a\u0442\u043e\u0432, \u0438\u043c\u0438\u0442\u0438\u0440\u0443\u044e\u0449\u0438\u0445 \u0441\u0442\u0430\u0440\u0438\u043d\u0443.",
        "Oops, there was an error while saving the image.": "\u041f\u0440\u043e\u0438\u0437\u043e\u0448\u043b\u0430 \u043e\u0448\u0438\u0431\u043a\u0430 \u043f\u0440\u0438 \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u0438\u0438 \u0438\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u044f.",
        Orientation: "\u041e\u0440\u0438\u0435\u043d\u0442",
        "Add Text": "\u0414\u043e\u0431\u0430\u0432\u0438\u0442\u044c",
        Classic: "\u041a\u043b\u0430\u0441\u0441\u0438\u0447\u0435\u0441\u043a\u0438\u0439",
        Text: "\u0422\u0435\u043a\u0441\u0442",
        "No stickers defined in Feather_Stickers.": "\u041d\u0435 \u0432\u044b\u0431\u0440\u0430\u043d\u044b \u0441\u0442\u0438\u043a\u0435\u0440\u044b",
        "Drag corners to resize crop area": "\u0422\u044f\u043d\u0438\u0442\u0435 \u0437\u0430 \u0443\u0433\u043b\u044b \u0434\u043b\u044f \u0438\u0437\u043c\u0435\u043d\u0435\u043d\u0438\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u0438 \u043e\u0431\u0440\u0435\u0437\u043a\u0438",
        "Give feedback": "\u041a\u043e\u043d\u0442\u0430\u043a\u0442",
        "Get this pack!": "\u041f\u043e\u043b\u0443\u0447\u0438\u0442\u0435 \u044d\u0442\u043e\u0442 \u043d\u0430\u0431\u043e\u0440!",
        Height: "\u0412\u044b\u0441\u043e\u0442\u0430",
        Colors: "\u0426\u0432\u0435\u0442\u0430",
        Done: "\u0421\u0434\u0435\u043b\u0430\u043d\u043e",
        "See your world a little differently with these six high-tech camera effects.": "\u0411\u043b\u0430\u0433\u043e\u0434\u0430\u0440\u044f \u044d\u0442\u0438\u043c \u0448\u0435\u0441\u0442\u0438 \u0432\u044b\u0441\u043e\u043a\u043e\u0442\u0435\u0445\u043d\u043e\u043b\u043e\u0433\u0438\u0447\u043d\u044b\u043c \u044d\u0444\u0444\u0435\u043a\u0442\u0430\u043c \u043a\u0430\u043c\u0435\u0440\u044b \u0432\u044b \u0443\u0432\u0438\u0434\u0438\u0442\u0435 \u043c\u0438\u0440 \u043d\u0435\u043c\u043d\u043e\u0433\u043e \u0434\u0440\u0443\u0433\u0438\u043c.",
        Cancel: "\u041e\u0442\u043c\u0435\u043d\u0430",
        Close: "\u0417\u0430\u043a\u0440\u044b\u0442\u044c",
        "Width and height must be greater than zero and less than the maximum({max}px)": "\u0428\u0438\u0440\u0438\u043d\u0430 \u0438 \u0432\u044b\u0441\u043e\u0442\u0430 \u0434\u043e\u043b\u0436\u043d\u044b \u0431\u044b\u0442\u044c \u0431\u043e\u043b\u044c\u0448\u0435 0 \u0438 \u043c\u0435\u043d\u044c\u0448\u0435 \u043c\u0430\u043a\u0441\u0438\u043c\u0443\u043c\u0430 ({max}px)",
        "Leave editor": "\u0412\u044b\u0439\u0442\u0438 \u0438\u0437 \u0440\u0435\u0434\u0430\u043a\u0442\u043e\u0440\u0430",
        Size: "\u0420\u0430\u0437\u043c\u0435\u0440",
        "e-mail address": "e-mail \u0430\u0434\u0440\u0435\u0441",
        "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!": "\u041e\u0439! \u0423 \u043c\u0435\u043d\u044f \u043f\u0440\u043e\u0438\u0437\u043e\u0448\u0435\u043b \u0441\u0431\u043e\u0439 \u0432 \u0440\u0430\u0431\u043e\u0442\u0435, \u043d\u043e \u0440\u0430\u0437\u0440\u0430\u0431\u043e\u0442\u0447\u0438\u043a\u0443 \u0431\u044b\u043b \u043d\u0430\u043f\u0440\u0430\u0432\u043b\u0435\u043d \u043e\u0442\u0447\u0435\u0442, \u0447\u0442\u043e\u0431\u044b \u043f\u0440\u043e\u0431\u043b\u0435\u043c\u0430 \u0431\u044b\u043b\u0430 \u0438\u0441\u043f\u0440\u0430\u0432\u043b\u0435\u043d\u0430!",
        Fade: "\u0412\u044b\u0446\u0432\u0435\u0442\u0430\u0442\u044c",
        Min: "Min",
        Cherry: "\u0412\u0438\u0448\u043d\u044f",
        "Are you sure? This can distort your image": "\u0412\u044b \u0443\u0432\u0435\u0440\u0435\u043d\u044b? \u0418\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u0435 \u043c\u043e\u0436\u0435\u0442 \u0438\u0441\u043a\u0430\u0437\u0438\u0442\u044c\u0441\u044f",
        "A sticker pack has been updated. Click ok to reload the packs list.": "\u041f\u0430\u043a\u0435\u0442 \u043d\u0430\u043a\u043b\u0435\u0435\u043a \u043e\u0431\u043d\u043e\u0432\u043b\u0435\u043d. \u041d\u0430\u0436\u043c\u0438\u0442\u0435 \u0434\u043b\u044f \u043f\u0435\u0440\u0435\u0437\u0430\u0433\u0440\u0443\u0437\u043a\u0438 \u0441\u043f\u0438\u0441\u043a\u0430 \u043f\u0430\u043a\u0435\u0442\u043e\u0432",
        Custom: "\u041f\u043e\u043b\u044c\u0437\u043e\u0432",
        Eraser: "\u041b\u0430\u0441\u0442\u0438\u043a",
        Singe: "\u041f\u0440\u043e\u0441\u0442\u043e\u0439",
        Drifter: "\u0411\u0440\u043e\u0434\u044f\u0433\u0430",
        Saturation: "\u041d\u0430\u0441\u044b\u0449.",
        "Crop again": "\u041e\u0431\u0440\u0435\u0437\u0430\u0442\u044c \u0435\u0449\u0435 \u0440\u0430\u0437",
        "Aviary Editor": "\u0420\u0435\u0434\u0430\u043a\u0442\u043e\u0440 \u0410vi\u0430ry",
        "Applying action %2$i of %2$i": "\u041f\u0440\u0438\u043c\u0435\u043d\u044f\u0435\u0442\u0441\u044f \u0434\u0435\u0439\u0441\u0442\u0432\u0438\u0435 %2$i of %2$i",
        Max: "Max",
        Attention: "\u0412\u043d\u0438\u043c\u0430\u043d\u0438\u0435",
        Redeye: "\u041a\u0440. \u0433\u043b\u0430\u0437\u0430",
        Halftone: "\u041f\u043e\u043b\u0443\u0442\u043e\u043d\u043e\u0432\u043e\u0435",
        "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?": "\u041f\u0440\u043e\u0438\u0437\u043e\u0448\u043b\u0430 \u043e\u0448\u0438\u0431\u043a\u0430 \u043f\u0440\u0438 \u0441\u043e\u0445\u0440\u0430\u043d\u0435\u043d\u0438\u0438 \u0438\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u044f \u0432 \u043f\u0430\u043f\u043a\u0443 Aviary \u0425\u043e\u0442\u0438\u0442\u0435 \u043f\u043e\u043f\u0440\u043e\u0431\u043e\u0432\u0430\u0442\u044c \u0441\u043e\u0445\u0440\u0430\u043d\u0438\u0442\u044c \u0435\u0433\u043e \u0432 \u043f\u0430\u043f\u043a\u0443 \u043a\u0430\u043c\u0435\u0440\u044b \u043f\u043e \u0443\u043c\u043e\u043b\u0447\u0430\u043d\u0438\u044e?",
        Pinch: "\u0421\u0443\u0436\u0435\u043d\u0438\u0435",
        "Old Photo": "\u0421\u0442\u0430\u0440\u0435\u043d\u0438\u0435",
        Laguna: "\u041b\u0430\u0433\u0443\u043d\u0430",
        Resize: "\u0420\u0430\u0437\u043c\u0435\u0440",
        "Powered by": "\u0421\u0434\u0435\u043b\u0430\u043d\u043e",
        "Color Grading": "\u0413\u0440\u0430\u0434\u0430\u0446\u0438\u044f \u0446\u0432\u0435\u0442\u0430",
        Firefly: "\u0421\u0432\u0435\u0442\u043b\u044f\u0447\u043e\u043a",
        Rotate: "\u041f\u043e\u0432\u043e\u0440\u043e\u0442",
        "Applying effects": "\u041f\u0440\u0438\u043c\u0435\u043d\u0438\u0442\u044c \u042d\u0444\u0444\u0435\u043a\u0442\u044b",
        Daydream: "\u041c\u0435\u0447\u0442\u0430",
        "Enter text here": "\u0412\u0432\u0435\u0434\u0438\u0442\u0435 \u0442\u0435\u043a\u0441\u0442 \u0437\u0434\u0435\u0441\u044c",
        "Code Red": "\u041a\u0440\u0430\u0441\u043d\u043e\u0435",
        "Interested? We'll send you some info.": "\u0418\u043d\u0442\u0435\u0440\u0435\u0441\u043d\u043e? \u041c\u044b \u0432\u044b\u0448\u043b\u0435\u043c \u0432\u0430\u043c \u043d\u0435\u043a\u043e\u0442\u043e\u0440\u0443\u044e \u0438\u043d\u0444\u043e\u0440\u043c\u0430\u0446\u0438\u044e.",
        Remove: "\u0423\u0434\u0430\u043b\u0438\u0442\u044c",
        Concorde: "\u041a\u043e\u043d\u043a\u043e\u0440\u0434",
        "Vignette Blur": "\u0412\u0438\u043d\u044c\u0435\u0442\u043a\u0430",
        "About this editor": "\u041e\u0431 \u044d\u0442\u043e\u043c \u0440\u0435\u0434\u0430\u043a\u0442\u043e\u0440\u0435",
        Discard: "\u041e\u0442\u043c\u0435\u043d\u0438\u0442\u044c",
        "Film Grain": "\u041a\u0438\u043d\u043e\u043f\u043b\u0435\u043d\u043a\u0430",
        Power: "\u0421\u0438\u043b\u0430",
        Color: "\u0426\u0432\u0435\u0442",
        Demo: "\u0414\u0435\u043c\u043e",
        Crop: "\u041e\u0431\u0440\u0435\u0437\u043a\u0430",
        "Edit Top Text": "\u0418\u0437\u043c\u0435\u043d\u0438\u0442\u044c \u0432\u0435\u0440\u0445\u043d\u0438\u0439 \u0442\u0435\u043a\u0441\u0442",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "\u0412\u044b \u043f\u043e\u0442\u0435\u0440\u044f\u0435\u0442\u0435 \u0441\u0434\u0435\u043b\u0430\u043d\u043d\u044b\u0435 \u0432\u0441\u0435 \u0438\u0437\u043c\u0435\u043d\u0435\u043d\u0438\u044f. \u0412\u044b \u0443\u0432\u0435\u0440\u0435\u043d\u044b, \u0447\u0442\u043e \u0445\u043e\u0442\u0438\u0442\u0435 \u0432\u044b\u0439\u0442\u0438?",
        Apply: "\u0412\u0437\u044f\u0442\u044c",
        Stickers: "\u0421\u0442\u0438\u043a\u0435\u0440\u044b"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.sv = {
        Loading: "Laddar",
        "Sorry, you must update the Aviary editor to use these effects.": "Tyv\u00e4rr, du m\u00e5ste uppdatera Aviary Fotoredigering f\u00f6r att kunna anv\u00e4nda effekterna.",
        "Toy Camera": "Leksakskamera",
        Night: "Natt",
        Nostalgia: "Nostalgi",
        Aviary: "Aviary",
        "Mark II": "Mark II",
        Width: "Bredd",
        "No effects found for this pack.": "Inga effekter hittades f\u00f6r paketet.",
        Blur: "Suddig",
        Sharpen: "Sk\u00e4rp",
        "Learn more!": "L\u00e4s mer!",
        Indiglow: "Indiglow",
        "There is another image editing window open.  Close it without saving and continue?": "Det finns en annan fotoredigeringssession \u00f6ppen. Vill du forts\u00e4tta och st\u00e4nga den utan att spara ?",
        Resume: "Sammanfatta",
        Heatwave: "V\u00e4rmev\u00e5g",
        "A filter pack has been updated. Click ok to reload the packs list.": "Ett filterpaket har uppdaterats. Klicka p\u00e5 Ok f\u00f6r att uppdatera paketet.",
        Update: "Uppdatera",
        Undo: "\u00c5ngra",
        "There was an error downloading the image, please try again later.": "Ett fel uppstod vid nedladdningen av bilden, v\u00e4nligen f\u00f6rs\u00f6k igen senare.",
        Effects: "Effekter",
        "Sorry, there's no application on your phone to handle this action.": "Tyv\u00e4rr, det finns ingen applikation p\u00e5 din enhet som kan hantera ditt val.",
        Vogue: "Vogue",
        Tools: "Verktyg",
        "Don't ask me again": "Fr\u00e5ga mig inte igen",
        Reset: "\u00c5terst\u00e4ll",
        "File saved": "Fil sparad",
        Blemish: "Fl\u00e4ckig",
        Bulge: "Utbuktning",
        Alice: "Alice",
        "Destination folder": "L\u00e4gg till i mapp",
        "Sorry, you must update the effect pack to continue.": "Tyv\u00e4rr, du m\u00e5ste uppdatera effektpaktetet f\u00f6r att kunna forts\u00e4tta.",
        "Original size": "Originalstorlek",
        "Are you sure you want to remove this sticker?": "\u00c4r du s\u00e4ker p\u00e5 att du vill ta bort accessoaren?",
        "Revert to original?": "\u00c5terg\u00e5 till original?",
        Mohawk: "Mohawk",
        Enhance: "F\u00f6rb\u00e4ttra",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary \u00e4r en gratis SDK tillg\u00e4nglig f\u00f6r iOS och Android som till\u00e5ter dig att redigera foton och till\u00e4mpa effekter till din applikation genom endast ett par koder.",
        Greeneye: "Gr\u00f6na \u00f6gat",
        "Unknown error": "Ok\u00e4nt fel",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "Du kommer att f\u00f6rlora alla \u00e4ndringar du gjort. \u00c4r du s\u00e4ker p\u00e5 att du vill \u00e5terg\u00e5 till din originalbild?",
        OK: "OK",
        "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?": "Tyv\u00e4rr, det finns ingen applikation p\u00e5 din enhet som kan hantera ditt val. Vill du ladda ned en nu?",
        Reflex: "Reflex",
        Whiten: "G\u00f6r vitare",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "L\u00e4gg till ljus-och m\u00f6rkereffekter i grunge-stil. Sex olika effekter finns tillg\u00e4ngliga.",
        "Delete selected": "Ta bort vald",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "Ett accessoarpaket har uppdaterats. Vi m\u00e5ste ladda om den nuvarande sessionen. Vill du anv\u00e4nda den nuvarande accessoaren?",
        Sepia: "Sepia",
        "Always Sunny": "Alltid soligt",
        Confirm: "Bekr\u00e4fta",
        Siesta: "Siesta",
        Negative: "Negativ",
        Send: "Skicka",
        "Keep editing": "Forts\u00e4tt redigera",
        "Powered by Aviary.com": "Med st\u00f6d av Aviary.com",
        Zoom: "Zoom",
        "Sorry, there's no file manager installed on your phone to handle this action. Do you want to download one now from the market?": "Tyv\u00e4rr, det finns inget filhanterare installerad p\u00e5 din enhet som kan hantera ditt val. Vill du ladda ned en nu?",
        Editor: "Redigerare",
        "Biggest size": "St\u00f6rsta formatet",
        "Soft Focus": "Mjukt fokus",
        Save: "Spara",
        "Are you sure?": "\u00c4r du s\u00e4ker?",
        Warmth: "V\u00e4rme",
        More: "Mer",
        Meme: "Meme",
        Malibu: "Malibu",
        Grunge: "Grunge",
        "Tool Selection": "Val av verktyg",
        Auto: "Auto",
        Tool: "Verktyg",
        Settings: "Inst\u00e4llningar",
        Eddie: "Eddie",
        Cinematic: "Cinematic",
        "Medium size": "Mediumstorlek",
        Store: "Butik",
        Backlit: "Lys upp bakgrund",
        "B&W": "B&W",
        Fixie: "Fixie",
        "Are you sure you want to discard changes from this tool?": "\u00c4r du s\u00e4ker p\u00e5 att du vill bortse fr\u00e5n \u00e4ndringarna i verktyget?",
        Brightness: "Ljushet",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "V\u00e4nta! Du har inte sparat ditt verk. \u00c4r du s\u00e4ker p\u00e5 att du vill st\u00e4nga fotoredigeraren?",
        "Get this editor": "H\u00e4mta redigeraren",
        Draw: "Dra",
        Flip: "Vrid",
        "View Image": "Se bild",
        Viewfinder: "S\u00f6k",
        "Your work was saved!": "Ditt verk har sparats!",
        "Small size": "Liten storlek",
        Delete: "Ta bort",
        Square: "Kvadrat",
        Redo: "\u00c5ngra",
        "Preset Sizes": "F\u00f6rbest\u00e4mda storlekar",
        Sharpness: "Sk\u00e4rpa",
        Back: "Tillbaka",
        "Brush softness": "Borstmjukhet",
        Periscope: "Periskop",
        Brush: "Borste",
        Mirror: "Spegel",
        "Edit Bottom Text": "Redigera text nedan",
        "Photo Editor": "Fotoredigerare",
        "Maintain proportions": "Beh\u00e5ll proportioner",
        Vivid: "Levande",
        "San Carmen": "San Carmen",
        Retro: "Retro",
        "Sorry, there was an error loading the effect pack": "Tyv\u00e4rr, ett fel uppstod n\u00e4r paketet skulle laddas",
        "Loading Image...": "Laddar bild...",
        Contrast: "Kontrast",
        "Instant!": "Instant!",
        "Choose Color": "V\u00e4lj f\u00e4rg",
        Strato: "Strato",
        "Zoom Mode": "Zoomat",
        "A sticker pack has been updated. We need to reload the current panel.": "Ett accessoarpaket har uppdaterats. Vi m\u00e5ste ladda om den nuvarande sessionen",
        Vigilante: "Vigilante",
        "Image saved in %1$s. Do you want to see the saved image?": "Bilden sparad i %1$s. Vill du se den sparade bilden?",
        "Hard Brushes": "H\u00e5rda borstar",
        "Brush size": "Borststorlek",
        "Get More": "H\u00e4mta mer",
        "Color Matrix": "F\u00e4rgmatris",
        Pinch: "Pinna",
        Aqua: "Aqua",
        "Output Image Size": "Slutlig bildstorlek",
        Ventura: "Ventura",
        Error: "Fel",
        "You can change this property in the Settings panel.": "Du kan \u00e4ndra inst\u00e4llningen under Inst\u00e4llningar",
        Kurt: "Kurt",
        Balance: "Balans",
        Original: "Original",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "Din bild har tillf\u00e4lligt krympt f\u00f6r att underl\u00e4tta redigeringen. N\u00e4r du klickar p\u00e5 Spara sparar du hela bilden i samma storlek som din sk\u00e4rm.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "Dr\u00f6m tillbaka till den gamla goda tiden och dina finaste minnen med v\u00e5ra sex nostalgiska effekter.",
        "Oops, there was an error while saving the image.": "Hoppsan, ett fel uppstod n\u00e4r bilden skulle sparas.",
        Orientation: "Visningsl\u00e4ge",
        "Add Text": "L\u00e4gg till text",
        "Your image was cropped.": "Din bild har blivit beskuren.",
        "24ZX": "24ZX",
        Text: "Text",
        "No stickers defined in Feather_Stickers.": "Inga accessoarer hittades i Fj\u00e4der_Accessoarer",
        "Drag corners to resize crop area": "Dra i h\u00f6rnen f\u00f6r att \u00e4ndra det besk\u00e4rda omr\u00e5det",
        "Give feedback": "Ge ditt omd\u00f6me",
        "Get this pack!": "H\u00e4mta paketet!",
        Height: "H\u00f6jd",
        Colors: "F\u00e4rger",
        Done: "Klar",
        "See your world a little differently with these six high-tech camera effects.": "Se v\u00e4rlden ur en annorlunda synvinkel med v\u00e5ra sex high-tech kameraeffekter.",
        Covert: "Hemlig",
        "Soft Brushes": "Mjuka borstar",
        Close: "St\u00e4ng",
        "Leave editor": "L\u00e4mna redigeraren",
        Size: "Storlek",
        "e-mail address": "e-mail adress",
        "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!": "Hoppsan! Jag kraschade, men en rapport har skickats till min utvecklare f\u00f6r att r\u00e4tta till problemet!",
        "Saving...": "Sparar...",
        Min: "Min",
        Cherry: "Chrono",
        "Are you sure? This can distort your image": "\u00c4r du s\u00e4ker? Detta kan f\u00f6rvr\u00e4nga bilden.",
        "A sticker pack has been updated. Click ok to reload the packs list.": "Ett accessoarpaket har uppdaterats. Klicka p\u00e5 Ok f\u00f6r att uppdatera paketet.",
        Custom: "Anpassa",
        Eraser: "Raderare",
        Singe: "Singe",
        Drifter: "Drivare",
        Saturation: "F\u00e4rgm\u00e4ttnad",
        "Crop again": "Besk\u00e4r igen",
        "Aviary Editor": "Aviary Fotoredigering",
        "Applying action %2$i of %2$i": "Till\u00e4mpar val %2$i of %2$i",
        Max: "Max",
        Attention: "Varning",
        Redeye: "R\u00f6da \u00f6gon",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "Du kommer att f\u00f6rlora alla \u00e4ndringar du gjort i verktyget. \u00c4r du s\u00e4ker p\u00e5 att du vill l\u00e4mna?",
        "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?": "Hoppsan, ett fel uppstod n\u00e4r bilden skulle sparas till Aviary-mappen. Vill du spara bilden i den automatiska bildmappen?",
        "Old Photo": "Gammalt foto",
        Laguna: "Laguna",
        Resize: "\u00c4ndra storlek",
        "Powered by": "Med st\u00f6d av",
        "Color Grading": "F\u00e4rggradering",
        Firefly: "Firefly",
        Rotate: "Rotera",
        "Applying effects": "Till\u00e4mpar effekter",
        Daydream: "Dagdr\u00f6mma",
        "Enter text here": "Skriv in text h\u00e4r",
        "Code Red": "Code Red",
        "Interested? We'll send you some info.": "Intresserad? Vi kommer att skicka dig mer information.",
        Remove: "Ta bort",
        Concorde: "Concorde",
        "Vignette Blur": "Vignette Blur",
        "About this editor": "Om redigeringsverktyget",
        Discard: "Ta bort",
        "Film Grain": "Filmkorn",
        Power: "St\u00f6d",
        Color: "F\u00e4rg",
        Demo: "Demo",
        Crop: "Besk\u00e4r",
        "Edit Top Text": "Redigera text ovan",
        Apply: "Anv\u00e4nd",
        Stickers: "Accessoarer"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.vi = {
        Loading: "\u0110ang ta\u0309i",
        "Toy Camera": "Ma\u0301y a\u0309nh \u0111\u00f4\u0300 ch\u01a1i",
        Night: "Ban \u0111\u00eam",
        Nostalgia: "Qua\u0301 kh\u01b0\u0301",
        Aviary: "Aviary",
        Width: "Chi\u00ea\u0300u r\u00f4\u0323ng",
        "No effects found for this pack.": "Kh\u00f4ng ti\u0300m th\u00e2\u0301y hi\u00ea\u0323u \u01b0\u0301ng na\u0300o cho go\u0301i na\u0300y",
        Blur: "M\u01a1\u0300",
        "Your image was cropped.": "A\u0309nh cu\u0309a ba\u0323n \u0111a\u0303 \u0111\u01b0\u01a1\u0323c c\u0103\u0301t.",
        Sharpen: "La\u0300m s\u0103\u0301c ne\u0301t",
        "Learn more!": "Ti\u0300m hi\u00ea\u0309u th\u00eam!",
        Ripped: "T\u1ea1o \u0110\u01b0\u1eddng \u1ea2nh B\u1ecb X\u00e9",
        Indiglow: "A\u0301nh sa\u0301ng xanh",
        "There is another image editing window open.  Close it without saving and continue?": "M\u00f4\u0323t c\u01b0\u0309a s\u00f4\u0309 chi\u0309nh s\u01b0\u0309a hi\u0300nh a\u0309nh kha\u0301c \u0111ang m\u01a1\u0309. \u0110o\u0301ng c\u01b0\u0309a s\u00f4\u0309 na\u0300y ma\u0300 kh\u00f4ng l\u01b0u va\u0300 ti\u00ea\u0301p tu\u0323c?",
        Resume: "Ti\u00ea\u0301p tu\u0323c",
        Heatwave: "So\u0301ng nhi\u00ea\u0323t",
        "A filter pack has been updated. Click ok to reload the packs list.": "Go\u0301i b\u00f4\u0323 lo\u0323c \u0111a\u0303 \u0111\u01b0\u01a1\u0323c c\u00e2\u0323p nh\u00e2\u0323t. Nh\u00e2\u0301p ok \u0111\u00ea\u0309 ta\u0309i la\u0323i danh sa\u0301ch go\u0301i",
        Update: "C\u1eadp nh\u1eadt",
        Free: "Mi\u1ec5n ph\u00ed",
        "There was an error downloading the image, please try again later.": "\u0110a\u0303 xa\u0309y ra l\u00f4\u0303i khi ta\u0309i xu\u00f4\u0301ng hi\u0300nh a\u0309nh, vui lo\u0300ng th\u01b0\u0309 la\u0323i sau",
        Effects: "Hi\u00ea\u0323u \u01b0\u0301ng",
        Tools: "C\u00f4ng cu\u0323",
        Reset: "Thi\u1ebft l\u1eadp la\u0323i",
        Blemish: "La\u0300m tr\u00e2\u0300y",
        Bulge: "\u0110\u00f4\u0323 phi\u0300nh",
        Alice: "Alice",
        "Are you sure you want to remove this sticker?": "Ba\u0323n co\u0301 ch\u0103\u0301c mu\u00f4\u0301n xo\u0301a nha\u0303n da\u0301n na\u0300y kh\u00f4ng?",
        "Revert to original?": "Tr\u01a1\u0309 v\u00ea\u0300 nguy\u00ean ba\u0309n?",
        Mohawk: "Mohawk",
        Enhance: "T\u0103ng c\u01b0\u01a1\u0300ng",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary la\u0300 m\u00f4\u0323t SDK mi\u00ea\u0303n phi\u0301 da\u0300nh cho iOS va\u0300 Android, cho phe\u0301p ba\u0323n th\u00eam kha\u0309 n\u0103ng chi\u0309nh s\u01b0\u0309a a\u0309nh va\u0300 ca\u0301c hi\u00ea\u0323u \u01b0\u0301ng va\u0300o \u01b0\u0301ng du\u0323ng cu\u0309a mi\u0300nh chi\u0309 v\u01a1\u0301i va\u0300i do\u0300ng ma\u0303.",
        Greeneye: "M\u0103\u0301t xanh",
        Shadow: "B\u00f3ng t\u1ed1i",
        Vogue: "Thi\u0323nh ha\u0300nh",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "Ba\u0323n s\u0103\u0301p m\u00e2\u0301t t\u00e2\u0301t ca\u0309 nh\u01b0\u0303ng thay \u0111\u00f4\u0309i ba\u0323n \u0111a\u0303 th\u01b0\u0323c hi\u00ea\u0323n. Ba\u0323n co\u0301 ch\u0103\u0301c mu\u00f4\u0301n tr\u01a1\u0309 v\u00ea\u0300 hi\u0300nh a\u0309nh nguy\u00ean ba\u0309n kh\u00f4ng?",
        OK: "OK",
        Intensity: "C\u01b0\u1eddng \u0111\u1ed9",
        Whiten: "La\u0300m tr\u0103\u0301ng",
        Frames: "Khung",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "Th\u00eam m\u00f4\u0323t s\u00f4\u0301 ha\u0323t s\u00e2\u0300n va\u0300 v\u00ea\u0323t mo\u0300n va\u0300o a\u0309nh cu\u0309a ba\u0323n v\u01a1\u0301i sa\u0301u hi\u00ea\u0323u \u01b0\u0301ng la\u0300m nho\u0300e na\u0300y.",
        "Delete selected": "Xo\u0301a mu\u0323c \u0111\u01b0\u01a1\u0323c cho\u0323n",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "Go\u0301i nha\u0303n da\u0301n \u0111a\u0303 \u0111\u01b0\u01a1\u0323c c\u00e2\u0323p nh\u00e2\u0323t. Chu\u0301ng t\u00f4i c\u00e2\u0300n ta\u0309i la\u0323i ba\u0309ng \u0111i\u00ea\u0300u khi\u00ea\u0309n hi\u00ea\u0323n ta\u0323i. Ba\u0323n co\u0301 mu\u00f4\u0301n a\u0301p du\u0323ng nha\u0303n da\u0301n hi\u00ea\u0323n ta\u0323i kh\u00f4ng?",
        "Always Sunny": "Lu\u00f4n co\u0301 a\u0301nh m\u0103\u0323t tr\u01a1\u0300i",
        Confirm: "Xa\u0301c nh\u00e2\u0323n",
        Siesta: "Gi\u00e2\u0301c ngu\u0309 tr\u01b0a",
        Negative: "\u00c2m ba\u0309n",
        Send: "G\u01b0\u0309i",
        "Keep editing": "Ti\u00ea\u0301p tu\u0323c chi\u0309nh s\u01b0\u0309a",
        "Powered by Aviary.com": "Cung c\u00e2\u0301p b\u01a1\u0309i Aviary.com",
        Zoom: "Thu pho\u0301ng",
        Editor: "Tri\u0300nh s\u01b0\u0309a a\u0309nh",
        "Soft Focus": "La\u0300m m\u01a1\u0300 ph\u00e2\u0300n gi\u01b0\u0303a",
        Save: "L\u01b0u",
        "Are you sure?": "Ba\u0323n ch\u0103\u0301c kh\u00f4ng?",
        Warmth: "\u1ea4m \u00e1p",
        More: "Kha\u0301c",
        Meme: "Tra\u0300o l\u01b0u",
        Charcoal: "V\u1ebd Than Ch\u00ec",
        Malibu: "Malibu",
        Grunge: "La\u0300m nho\u0300e",
        "Tool Selection": "Cho\u0323n c\u00f4ng cu\u0323",
        Auto: "T\u01b0\u0323 \u0111\u00f4\u0323ng",
        Tool: "C\u00f4ng cu\u0323",
        Daydream: "M\u01a1 m\u00f4\u0323ng",
        Eddie: "Xoa\u0301y n\u01b0\u01a1\u0301c",
        Cinematic: "Hi\u00ea\u0323u \u01b0\u0301ng ra\u0323p chi\u00ea\u0301u phim",
        Store: "C\u01b0\u0309a ha\u0300ng",
        Backlit: "Ng\u01b0\u01a1\u0323c sa\u0301ng",
        "Are you sure you want to discard changes from this tool?": "Ba\u0323n co\u0301 ch\u0103\u0301c mu\u00f4\u0301n hu\u0309y bo\u0309 nh\u01b0\u0303ng thay \u0111\u00f4\u0309i kho\u0309i c\u00f4ng cu\u0323 na\u0300y kh\u00f4ng?",
        Brightness: "\u0110\u00f4\u0323 sa\u0301ng",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "Xin \u0111\u01a1\u0323i! Ba\u0323n ch\u01b0a l\u01b0u ta\u0301c ph\u00e2\u0309m cu\u0309a mi\u0300nh. Ba\u0323n co\u0301 ch\u0103\u0301c mu\u00f4\u0301n \u0111o\u0301ng tri\u0300nh s\u01b0\u0309a a\u0309nh na\u0300y kh\u00f4ng?",
        Smooth: "M\u1ecbn",
        "Get this editor": "Download",
        Draw: "Ve\u0303",
        Flip: "L\u00e2\u0323t",
        "Soft Brushes": "Ch\u00f4\u0309i m\u00ea\u0300m",
        "Your work was saved!": "Ta\u0301c ph\u00e2\u0309m cu\u0309a ba\u0323n \u0111a\u0303 \u0111\u01b0\u01a1\u0323c l\u01b0u!",
        Delete: "X\u00f3a",
        Square: "Vu\u00f4ng",
        Rounded: "Bo Tr\u00f2n",
        "Preset Sizes": "Ki\u0301ch th\u01b0\u01a1\u0301c co\u0301 s\u0103\u0303n",
        Sharpness: "\u0110\u00f4\u0323 s\u0103\u0301c ne\u0301t",
        Back: "Tr\u1edf la\u0323i",
        "Brush softness": "\u0110\u00f4\u0323 m\u00ea\u0300m cu\u0309a ch\u00f4\u0309i",
        Brush: "Ch\u00f4\u0309i",
        Mirror: "Pha\u0309n chi\u00ea\u0301u",
        "Edit Bottom Text": "Chi\u0309nh s\u01b0\u0309a v\u0103n ba\u0309n d\u01b0\u01a1\u0301i cu\u0300ng",
        "Photo Editor": "Tri\u0300nh s\u01b0\u0309a a\u0309nh",
        "Maintain proportions": "Duy tri\u0300 ty\u0309 l\u00ea\u0323",
        Vivid: "R\u01b0\u0323c r\u01a1\u0303",
        "San Carmen": "San Carmen",
        Retro: "Hoa\u0300i c\u00f4\u0309",
        Exit: "Ra",
        Undo: "Hoa\u0300n ta\u0301c",
        "Loading Image...": "\u0110ang ta\u0309i hi\u0300nh a\u0309nh...",
        Borders: "Bi\u00ean gi\u1edbi",
        Contrast: "T\u01b0\u01a1ng pha\u0309n",
        "Instant!": "T\u01b0\u0301c thi\u0300!",
        "Choose Color": "Cho\u0323n ma\u0300u",
        Strato: "Ph\u00e2n t\u00e2\u0300ng",
        Vignette: "L\u00e0m M\u1edd N\u1ec1n \u1ea2nh Quanh Trung T\u00e2m",
        "Zoom Mode": "Ch\u00ea\u0301 \u0111\u00f4\u0323 thu pho\u0301ng",
        "A sticker pack has been updated. We need to reload the current panel.": "Go\u0301i nha\u0303n da\u0301n \u0111a\u0303 \u0111\u01b0\u01a1\u0323c c\u00e2\u0323p nh\u00e2\u0323t. Chu\u0301ng t\u00f4i c\u00e2\u0300n ta\u0309i la\u0323i ba\u0309ng \u0111i\u00ea\u0300u khi\u00ea\u0309n hi\u00ea\u0323n ta\u0323i",
        Vigilante: "Vigilante",
        "Hard Brushes": "Ch\u00f4\u0309i c\u01b0\u0301ng",
        "Brush size": "C\u01a1\u0303 ch\u00f4\u0309i",
        "Get More": "Ta\u0309i th\u00eam",
        "Color Matrix": "Ma tr\u00e2\u0323n ma\u0300u",
        Corners: "Corners",
        Aqua: "N\u01b0\u01a1\u0301c",
        Ragged: "L\u00e0m G\u1ed3 Gh\u1ec1",
        Ventura: "Khu\u00ea\u0301ch ta\u0301n",
        Error: "L\u00f4\u0303i",
        Kurt: "Kurt",
        Balance: "C\u00e2n b\u0103\u0300ng",
        Original: "G\u00f4\u0301c",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "A\u0309nh cu\u0309a ba\u0323n ta\u0323m th\u01a1\u0300i bi\u0323 co la\u0323i \u0111\u00ea\u0309 d\u00ea\u0303 chi\u0309nh s\u01b0\u0309a h\u01a1n. Khi ba\u0323n nh\u00e2\u0301n L\u01b0u, ba\u0323n se\u0303 l\u01b0u ki\u0301ch th\u01b0\u01a1\u0301c hi\u00ea\u0309n thi\u0323 \u0111\u00e2\u0300y \u0111u\u0309.",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "H\u00f4\u0300i t\u01b0\u01a1\u0309ng nh\u01b0\u0303ng ky\u0301 \u01b0\u0301c y\u00eau d\u00e2\u0301u ho\u0103\u0323c nh\u01b0\u0303ng khoa\u0309ng th\u01a1\u0300i gian \u0111e\u0323p v\u01a1\u0301i sa\u0301u hi\u00ea\u0323u \u01b0\u0301ng qua\u0301 kh\u01b0\u0301 \u0111e\u0323p nh\u01b0 m\u01a1 cu\u0309a chu\u0301ng t\u00f4i.",
        Orientation: "H\u01b0\u01a1\u0301ng",
        "Add Text": "Th\u00eam v\u0103n ba\u0309n",
        Classic: "C\u1ed5 \u0111i\u1ec3n",
        Text: "N\u1ed9i dung",
        "No stickers defined in Feather_Stickers.": "Kh\u00f4ng co\u0301 nha\u0303n da\u0301n na\u0300o \u0111\u01b0\u01a1\u0323c \u0111i\u0323nh nghi\u0303a trong Feather_Stickers",
        "Drag corners to resize crop area": "Ke\u0301o ca\u0301c go\u0301c \u0111\u00ea\u0309 \u0111i\u0323nh c\u01a1\u0303 la\u0323i vu\u0300ng c\u0103\u0301t",
        "Give feedback": "\u0110\u01b0a ra pha\u0309n h\u00f4\u0300i",
        "Get this pack!": "Ta\u0309i go\u0301i na\u0300y!",
        Height: "Chi\u00ea\u0300u cao",
        Colors: "Ma\u0300u",
        Done: "Xong",
        Fixie: "Fixie",
        Cancel: "H\u1ee7y",
        Close: "\u0110o\u0301ng",
        "Width and height must be greater than zero and less than the maximum({max}px)": "Chi\u1ec1u r\u1ed9ng v\u00e0 chi\u1ec1u cao ph\u1ea3i l\u1edbn h\u01a1n 0 v\u00e0 nh\u1ecf h\u01a1n k\u00edch th\u01b0\u1edbc t\u1ed1i \u0111a ({max}px)",
        "Leave editor": "Thoa\u0301t tri\u0300nh s\u01b0\u0309a a\u0309nh",
        Size: "Ki\u0301ch c\u1ee1",
        "e-mail address": "\u0111i\u0323a chi\u0309 email",
        Eraser: "T\u00e2\u0309y",
        Min: "T\u00f4\u0301i thi\u00ea\u0309u",
        Cherry: "\u0110o\u0309 anh \u0111a\u0300o",
        "Are you sure? This can distort your image": "B\u1ea1n ch\u1eafc kh\u00f4ng? Thao t\u00e1c n\u00e0y c\u00f3 th\u1ec3 l\u00e0m m\u00e9o \u1ea3nh c\u1ee7a b\u1ea1n",
        "A sticker pack has been updated. Click ok to reload the packs list.": "Go\u0301i nha\u0303n da\u0301n \u0111a\u0303 \u0111\u01b0\u01a1\u0323c c\u00e2\u0323p nh\u00e2\u0323t. Nh\u00e2\u0301p ok \u0111\u00ea\u0309 ta\u0309i la\u0323i danh sa\u0301ch go\u0301i",
        Custom: "Tu\u0300y chi\u0309nh",
        Fade: "Phai",
        Singe: "Cha\u0301y xe\u0301m",
        Drifter: "Tr\u00f4i gia\u0323t",
        Saturation: "Ba\u0303o ho\u0300a",
        "Crop again": "C\u0103\u0301t la\u0323i",
        "Aviary Editor": "Tri\u0300nh s\u01b0\u0309a a\u0309nh Aviary",
        Max: "T\u00f4\u0301i \u0111a",
        Attention: "L\u01b0u y\u0301",
        Redeye: "M\u0103\u0301t \u0111o\u0309",
        Halftone: "T\u1ea1o \u0110i\u1ec3m M\u00e0u",
        Pinch: "Cu\u0301 \u0111\u00e2\u0301m",
        "Old Photo": "A\u0309nh cu\u0303",
        Laguna: "H\u00f4\u0300 nho\u0309",
        Resize: "Resize",
        "Powered by": "Cung c\u00e2\u0301p b\u01a1\u0309i",
        "Color Grading": "Ph\u00e2n loa\u0323i ma\u0300u",
        Firefly: "\u0110om \u0111o\u0301m",
        Rotate: "Xoay",
        "Applying effects": "A\u0301p du\u0323ng hi\u00ea\u0323u \u01b0\u0301ng",
        "Enter text here": "Nh\u00e2\u0323p v\u0103n ba\u0309n va\u0300o \u0111\u00e2y",
        "Code Red": "Ma\u0303 \u0111o\u0309",
        "Interested? We'll send you some info.": "Ba\u0323n quan t\u00e2m? Chu\u0301ng t\u00f4i se\u0303 g\u01b0\u0309i cho ba\u0323n m\u00f4\u0323t s\u00f4\u0301 th\u00f4ng tin.",
        Remove: "X\u00f3a",
        Concorde: "Concorde",
        "Vignette Blur": "M\u01a1\u0300 ho\u0323a ti\u00ea\u0301t vi\u00ea\u0300n",
        "About this editor": "Gi\u01a1\u0301i thi\u00ea\u0323u v\u00ea\u0300 tri\u0300nh s\u01b0\u0309a a\u0309nh",
        Discard: "Loa\u0323i bo\u0309",
        "Film Grain": "Ha\u0323t phim",
        Power: "S\u01b0\u0301c ma\u0323nh",
        Color: "Ma\u0300u",
        Demo: "Ba\u0309n gi\u01a1\u0301i thi\u00ea\u0323u",
        Crop: "C\u0103\u0301t",
        "Edit Top Text": "Chi\u0309nh s\u01b0\u0309a v\u0103n ba\u0309n tr\u00ean cu\u0300ng",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "Ba\u0323n s\u0103\u0301p m\u00e2\u0301t nh\u01b0\u0303ng thay \u0111\u00f4\u0309i ba\u0323n \u0111a\u0303 th\u01b0\u0323c hi\u00ea\u0323n trong c\u00f4ng cu\u0323 na\u0300y. Ba\u0323n co\u0301 ch\u0103\u0301c mu\u00f4\u0301n thoa\u0301t kh\u00f4ng?",
        Apply: "A\u0301p du\u0323ng",
        Stickers: "Nha\u0303n da\u0301n"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.yr = {}
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.zh_hans = {
        Loading: "\u6b63\u5728\u8f7d\u5165",
        "Toy Camera": "\u73a9\u5177\u76f8\u673a",
        Night: "\u591c\u665a",
        Nostalgia: "\u6000\u65e7",
        Aviary: "Aviary",
        Width: "\u5bbd\u5ea6",
        "No effects found for this pack.": "\u6b64\u6587\u4ef6\u5305\u4e2d\u6ca1\u6709\u6548\u679c",
        Blur: "\u6a21\u7cca",
        "Your image was cropped.": "\u60a8\u7684\u56fe\u50cf\u5df2\u7ecf\u88c1\u5207\u3002",
        Sharpen: "\u9510\u5316",
        Ripped: "\u6495\u5f00",
        Indiglow: "\u8367\u5149",
        "There is another image editing window open.  Close it without saving and continue?": "\u53e6\u4e00\u4e2a\u56fe\u50cf\u7f16\u8f91\u7a97\u53e3\u5df2\u7ecf\u6253\u5f00\u3002\u5173\u95ed\u800c\u4e0d\u4fdd\u5b58\u8be5\u7a97\u53e3\uff0c\u5e76\u7ee7\u7eed\u5417\uff1f",
        Resume: "\u6062\u590d",
        Heatwave: "\u70ed\u6d6a",
        "A filter pack has been updated. Click ok to reload the packs list.": "\u6ee4\u955c\u5305\u5df2\u66f4\u65b0\u3002\u5355\u51fb\u201c\u786e\u5b9a\u201d\u6765\u91cd\u65b0\u52a0\u8f7d\u8d44\u6e90\u5305\u5217\u8868",
        Update: "\u66f4\u65b0",
        Free: "\u514d\u8d39",
        "There was an error downloading the image, please try again later.": "\u4e0b\u8f7d\u56fe\u7247\u65f6\u53d1\u751f\u9519\u8bef\uff0c\u8bf7\u7a0d\u540e\u518d\u8bd5",
        Effects: "\u6548\u679c",
        "Sorry, there's no application on your phone to handle this action.": "\u62b1\u6b49\uff0c\u60a8\u7684\u624b\u673a\u4e0a\u6ca1\u6709\u5904\u7406\u6b64\u64cd\u4f5c\u7684\u5e94\u7528\u7a0b\u5e8f",
        Tools: "\u5de5\u5177",
        "Don't ask me again": "\u4e0d\u8981\u518d\u95ee\u6211",
        Reset: "\u91cd\u7f6e",
        "File saved": "\u6587\u4ef6\u5df2\u4fdd\u5b58",
        Blemish: "\u6e05\u9664\u6c61\u70b9",
        Bulge: "\u51f8\u5ea6",
        Alice: "\u7231\u4e3d\u4e1d",
        "Destination folder": "\u76ee\u6807\u6587\u4ef6\u5939",
        "Original size": "\u539f\u59cb\u5c3a\u5bf8",
        "Are you sure you want to remove this sticker?": "\u60a8\u786e\u5b9a\u8981\u5220\u9664\u6b64\u8d34\u7eb8\u5417\uff1f",
        "Revert to original?": "\u6062\u590d\u5230\u521d\u59cb\u72b6\u6001\uff1f",
        Mohawk: "\u83ab\u970d\u514b",
        Enhance: "\u5f3a\u5316",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary\u662f\u4e00\u4e2a\u514d\u8d39\u7684\u8f6f\u4ef6\u5f00\u53d1\u5de5\u5177\u5305\uff0c\u53ef\u5e94\u7528\u4e8eiOS\u548cAndroid\uff0c\u8ba9\u60a8\u53ea\u9700\u51e0\u884c\u4ee3\u7801\u5c31\u5c06\u7167\u7247\u7f16\u8f91\u529f\u80fd\u548c\u6548\u679c\u6dfb\u52a0\u5230\u60a8\u7684\u5e94\u7528\u7a0b\u5e8f\u3002",
        Greeneye: "\u6d88\u9664\u7eff\u773c",
        Shadow: "\u9634\u5f71",
        Vogue: "\u65f6\u5c1a",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "\u60a8\u5c06\u4e22\u5931\u60a8\u6240\u505a\u7684\u6240\u6709\u66f4\u6539\u3002\u60a8\u786e\u5b9a\u8981\u6062\u590d\u5230\u539f\u59cb\u56fe\u7247\u5417\uff1f",
        OK: "\u786e\u5b9a",
        "Sorry, there's no application on your device to handle this action. Do you want to download it now from the market?": "\u62b1\u6b49\uff0c\u60a8\u7684\u624b\u673a\u4e0a\u6ca1\u6709\u5904\u7406\u6b64\u64cd\u4f5c\u7684\u5e94\u7528\u7a0b\u5e8f\u3002\u60a8\u8981\u73b0\u5728\u4ece\u7535\u5b50\u5e02\u573a\u4e0b\u8f7d\u5417\uff1f",
        Intensity: "\u5f3a\u5ea6",
        Whiten: "\u6f02\u767d",
        Frames: "\u5e27",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "\u901a\u8fc7\u8fd9\u516d\u79cd\u505a\u65e7\u98ce\u683c\u7ed9\u60a8\u7684\u7167\u7247\u6dfb\u52a0\u566a\u70b9\u548c\u78e8\u635f\u6548\u679c\u3002",
        "Delete selected": "\u5220\u9664\u9009\u5b9a\u5185\u5bb9",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "\u8d34\u7eb8\u5305\u5df2\u66f4\u65b0\u3002\u6211\u4eec\u9700\u8981\u91cd\u65b0\u8f7d\u5165\u5f53\u524d\u9762\u677f\u3002\u60a8\u8981\u5e94\u7528\u5f53\u524d\u8d34\u7eb8\u5417\uff1f",
        "Set color": "\u8bbe\u7f6e\u989c\u8272",
        "Always Sunny": "\u9633\u5149\u660e\u5a9a",
        Confirm: "\u786e\u8ba4",
        Siesta: "\u5348\u4f11",
        Negative: "\u8d1f\u7247",
        Send: "\u53d1\u9001",
        "Keep editing": "\u7ee7\u7eed\u7f16\u8f91",
        "Powered by Aviary.com": "\u7531Aviary.com \u63d0\u4f9b\u6280\u672f\u652f\u6301",
        Zoom: "\u7f29\u653e",
        Editor: "\u7f16\u8f91\u5668",
        "Biggest size": "\u6700\u5927\u5c3a\u5bf8",
        "Soft Focus": "\u67d4\u7126",
        Save: "\u4fdd\u5b58",
        "Are you sure?": "\u60a8\u786e\u5b9a\u5417\uff1f",
        Warmth: "\u70ed\u60c5",
        More: "\u66f4\u591a",
        Meme: "Meme",
        Charcoal: "\u70ad",
        Malibu: "\u9a6c\u91cc\u5e03",
        Grunge: "\u7834\u635f\u88c5\u626e",
        "Tool Selection": "\u5de5\u5177\u9009\u62e9",
        Auto: "\u81ea\u52a8",
        Tool: "\u5de5\u5177",
        Settings: "\u8bbe\u7f6e",
        Eddie: "\u57c3\u8fea",
        Cinematic: "\u7535\u5f71",
        "Medium size": "\u4e2d\u7b49\u5c3a\u5bf8",
        Store: "\u5b58\u50a8",
        Backlit: "\u80cc\u5149",
        Fixie: "\u5b9a\u901f",
        Brightness: "\u4eae\u5ea6",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "\u8bf7\u7a0d\u7b49\uff01\u60a8\u8fd8\u6ca1\u6709\u4fdd\u5b58\u60a8\u7684\u5de5\u4f5c\u3002\u60a8\u786e\u5b9a\u8981\u5173\u95ed\u6b64\u7f16\u8f91\u5668\u5417\uff1f",
        Smooth: "\u987a\u5229",
        "Get this editor": "\u83b7\u53d6\u8be5\u7f16\u8f91\u5668",
        Draw: "\u7ed8\u56fe",
        Flip: "\u7ffb\u8f6c",
        "Soft Brushes": "\u8f6f\u5237",
        "View Image": "\u67e5\u770b\u56fe\u7247",
        Viewfinder: "\u53d6\u666f\u5668",
        "Your work was saved!": "\u60a8\u7684\u5de5\u4f5c\u5df2\u7ecf\u4fdd\u5b58\uff01",
        "Small size": "\u5c0f\u5c3a\u5bf8",
        Delete: "\u5220\u9664",
        Square: "\u6b63\u65b9\u5f62",
        Rounded: "\u5706\u6ed1",
        Redo: "\u518d\u6b21\u6267\u884c",
        "Preset Sizes": "\u9884\u8bbe\u5c3a\u5bf8",
        Sharpness: "\u9510\u5ea6",
        Back: "\u8fd4\u56de",
        "Brush softness": "\u7b14\u5237\u786c\u5ea6",
        Brush: "\u7b14\u5237",
        Mirror: "\u955c\u8c61",
        "Edit Bottom Text": "\u7f16\u8f91\u5e95\u90e8\u6587\u672c",
        "Photo Editor": "\u7167\u7247\u7f16\u8f91\u5668",
        "Maintain proportions": "\u7ef4\u6301\u6bd4\u4f8b",
        Vivid: "\u751f\u52a8",
        "San Carmen": "\u5723\u5361\u95e8",
        Retro: "\u590d\u53e4",
        Exit: "\u51fa\u53e3",
        Undo: "\u53d6\u6d88",
        "Loading Image...": "\u6b63\u5728\u8f7d\u5165\u56fe\u7247\u2026",
        Borders: "\u8fb9\u754c",
        Contrast: "\u5bf9\u6bd4\u5ea6",
        "Saving...": "\u6b63\u5728\u4fdd\u5b58\u2026",
        "Instant!": "\u5373\u523b\uff01",
        "Choose Color": "\u9009\u62e9\u989c\u8272",
        Strato: "\u4e91\u5f69",
        Vignette: "\u5c0f\u63d2\u56fe",
        "Zoom Mode": "\u7f29\u653e\u6a21\u5f0f",
        "A sticker pack has been updated. We need to reload the current panel.": "\u8d34\u7eb8\u5305\u5df2\u66f4\u65b0\u3002\u6211\u4eec\u9700\u8981\u91cd\u65b0\u8f7d\u5165\u5f53\u524d\u9762\u677f\u3002",
        Vigilante: "\u6c11\u56e2\u56e2\u5458",
        "Image saved in %1$s. Do you want to see the saved image?": "\u56fe\u7247\u5df2\u4fdd\u5b58\u5230 %1$s\u3002\u60a8\u8981\u67e5\u770b\u5df2\u4fdd\u5b58\u7684\u56fe\u7247\u5417\uff1f",
        "Hard Brushes": "\u786c\u5237",
        "Brush size": "\u7b14\u5237\u5c3a\u5bf8",
        "Get More": "\u83b7\u53d6\u66f4\u591a",
        "Color Matrix": "\u989c\u8272\u77e9\u9635",
        Corners: "\u89d2\u843d",
        Aqua: "\u6c34\u5e55",
        "Output Image Size": "\u8f93\u51fa\u56fe\u7247\u5c3a\u5bf8",
        Ragged: "\u7c97\u7cd9",
        Ventura: "\u6e29\u56fe\u62c9",
        Error: "\u9519\u8bef",
        "You can change this property in the Settings panel.": "\u60a8\u53ef\u4ee5\u5728\u201c\u8bbe\u7f6e\u201d\u9762\u677f\u4e2d\u66f4\u6539\u6b64\u5c5e\u6027",
        Kurt: "\u5e93\u5c14\u7279",
        Balance: "\u5e73\u8861",
        Original: "\u539f\u59cb",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "\u60a8\u7684\u56fe\u50cf\u5df2\u6682\u65f6\u7f29\u5c0f\uff0c\u4f7f\u5176\u66f4\u5bb9\u6613\u8fdb\u884c\u7f16\u8f91\u3002\u5f53\u60a8\u70b9\u51fb\u4fdd\u5b58\uff0c\u5c06\u4f1a\u4fdd\u5b58\u5b8c\u6574\u7684\u663e\u793a\u5927\u5c0f\u3002",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "\u7528\u6211\u4eec\u8fd9\u516d\u79cd\u68a6\u5e7b\u822c\u7684\u6000\u65e7\u98ce\u683c\u56de\u5fc6\u5f80\u4e8b\u548c\u7f8e\u597d\u65f6\u5149\u3002",
        "Oops, there was an error while saving the image.": "\u554a\u54e6\uff0c\u4fdd\u5b58\u56fe\u7247\u65f6\u53d1\u751f\u9519\u8bef\u3002",
        Orientation: "\u65b9\u5411",
        "Add Text": "\u6dfb\u52a0\u6587\u672c",
        Classic: "\u7ecf\u5178",
        Text: "\u6587\u672c",
        "No stickers defined in Feather_Stickers.": "\u5728Feather_Stickers\u4e2d\u6ca1\u6709\u5b9a\u4e49\u8d34\u7eb8",
        "Drag corners to resize crop area": "\u62d6\u52a8\u8fb9\u89d2\u4ee5\u8c03\u6574\u88c1\u5207\u8303\u56f4",
        "Give feedback": "\u63d0\u4f9b\u53cd\u9988\u610f\u89c1",
        "Get this pack!": "\u83b7\u53d6\u672c\u8f6f\u4ef6\u5305\uff01",
        Height: "\u9ad8\u5ea6",
        Colors: "\u989c\u8272",
        Done: "\u5b8c\u6210",
        "See your world a little differently with these six high-tech camera effects.": "\u6709\u4e86\u8fd9\u516d\u4e2a\u9ad8\u79d1\u6280\u7684\u6444\u5f71\u6548\u679c\uff0c\u4f60\u4f1a\u53d1\u73b0\u81ea\u5df1\u770b\u5230\u7684\u4e16\u754c\u7115\u7136\u4e00\u65b0\u3002",
        Cancel: "\u53d6\u6d88",
        Close: "\u5173\u95ed",
        "Width and height must be greater than zero and less than the maximum({max}px)": "\u5bbd\u5ea6\u548c\u9ad8\u5ea6\u5fc5\u987b\u5927\u4e8e\u96f6\u5e76\u5c0f\u4e8e\u6700\u5927\u503c ({max}px)",
        "Leave editor": "\u79bb\u5f00\u7f16\u8f91\u5668",
        Size: "\u5c3a\u5bf8",
        "e-mail address": "\u7535\u5b50\u90ae\u4ef6\u5730\u5740",
        "Oops! I crashed, but a report has been sent to my developer to help him fix the issue!": "\u7cdf\u7cd5\uff01\u6211\u5d29\u6e83\u4e86\uff0c\u4e0d\u8fc7\u6211\u53d1\u9001\u4e86\u4e00\u4efd\u62a5\u544a\u7ed9\u6211\u7684\u5f00\u53d1\u8005\uff0c\u53ef\u4ee5\u5e2e\u52a9\u4ed6\u4fee\u590d\u8fd9\u4e2a\u95ee\u9898\uff01",
        Fade: "\u892a\u8272",
        Min: "\u6700\u5c0f",
        Cherry: "\u6a31\u6843",
        "Are you sure? This can distort your image": "\u60a8\u786e\u8ba4\u5417\uff1f\u6b64\u64cd\u4f5c\u4f1a\u626d\u66f2\u60a8\u7684\u56fe\u7247",
        "A sticker pack has been updated. Click ok to reload the packs list.": "\u8d34\u7eb8\u5305\u5df2\u66f4\u65b0\u3002\u5355\u51fb\u201c\u786e\u5b9a\u201d\u6765\u91cd\u65b0\u52a0\u8f7d\u8d44\u6e90\u5305\u5217\u8868",
        Custom: "\u81ea\u5b9a\u4e49",
        Eraser: "\u6a61\u76ae",
        Singe: "\u706b\u70e7",
        Drifter: "\u6d41\u6d6a\u6c49",
        Saturation: "\u9971\u548c\u5ea6",
        "Crop again": "\u518d\u6b21\u88c1\u5207",
        "Aviary Editor": "Aviary \u7f16\u8f91\u5668",
        "Applying action %2$i of %2$i": "\u6b63\u5728\u5e94\u7528\u64cd\u4f5c\uff08\u7b2c %2$i \u4e2a\uff0c\u4f9b %2$i \u4e2a\uff09",
        Max: "\u6700\u5927",
        Attention: "\u6ce8\u610f",
        Redeye: "\u6d88\u9664\u7ea2\u773c",
        Halftone: "\u534a\u8272\u8c03",
        Pinch: "\u6536\u7f29",
        "Old Photo": "\u8001\u7167\u7247",
        Laguna: "\u62c9\u53e4\u7eb3",
        Resize: "\u8c03\u6574\u5c3a\u5bf8",
        "Powered by": "\u7531.......\u63d0\u4f9b\u6280\u672f\u652f\u6301",
        "Color Grading": "\u989c\u8272\u5206\u5c42",
        Firefly: "\u8424\u706b\u866b",
        Rotate: "\u65cb\u8f6c",
        "Applying effects": "\u5e94\u7528\u6548\u679c",
        Daydream: "\u767d\u65e5\u68a6",
        "Enter text here": "\u5728\u6b64\u8f93\u5165\u6587\u672c",
        "Code Red": "\u7ea2\u8272\u4ee3\u7801",
        "Interested? We'll send you some info.": "\u611f\u5174\u8da3\u5417\uff1f\u6211\u4eec\u4f1a\u5411\u60a8\u53d1\u9001\u4e00\u4e9b\u4fe1\u606f\u3002",
        Remove: "\u5220\u9664",
        Concorde: "\u6df7\u5408",
        "Vignette Blur": "\u6a21\u7cca\u6655\u5316",
        "About this editor": "\u5173\u4e8e\u6b64\u7f16\u8f91\u5668",
        "Oops, there was an error trying to save the image to the Aviary folder. Do you want to try to save it to the default camera folder?": "\u554a\u54e6\uff0c\u5c1d\u8bd5\u4fdd\u5b58\u56fe\u7247\u5230 Aviary \u6587\u4ef6\u5939\u65f6\u53d1\u751f\u9519\u8bef\u3002\u60a8\u8981\u5c1d\u8bd5\u4fdd\u5b58\u5230\u9ed8\u8ba4\u76f8\u673a\u6587\u4ef6\u5939\u4e2d\u5417\uff1f",
        "Film Grain": "\u80f6\u7247\u9897\u7c92",
        Power: "\u7535\u6e90",
        Color: "\u989c\u8272",
        Demo: "\u6f14\u793a",
        Crop: "\u88c1\u5207",
        "Edit Top Text": "\u7f16\u8f91\u9876\u90e8\u6587\u672c",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "\u60a8\u5c06\u8981\u5931\u53bb\u60a8\u5728\u672c\u5de5\u5177\u4e2d\u6240\u505a\u7684\u6240\u6709\u4fee\u6539\u3002\u60a8\u786e\u5b9a\u8981\u79bb\u5f00\u5417\uff1f",
        Apply: "\u5e94\u7528",
        Stickers: "\u8d34\u7eb8"
    }
})(AV.lang = AV.lang || {});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.zh_hant = {
        Loading: "\u6b63\u5728\u8f09\u5165",
        "Toy Camera": "\u73a9\u5177\u76f8\u6a5f",
        Night: "\u591c\u665a",
        Nostalgia: "\u61f7\u820a",
        Aviary: "\u9ce5\u7c60",
        Width: "\u5bdb\u5ea6",
        "No effects found for this pack.": "\u5728\u6b64\u5305\u88dd\u4e2d\u6c92\u6709\u4efb\u4f55\u6548\u679c",
        Blur: "\u6a21\u7cca",
        "Your image was cropped.": "\u88c1\u5207\u5b8c\u6210\u3002",
        Sharpen: "\u92b3\u5316",
        Ripped: "\u6495\u88c2",
        Indiglow: "\u7d62\u5f69",
        "There is another image editing window open.  Close it without saving and continue?": "\u53e6\u4e00\u500b\u5716\u50cf\u7de8\u8f2f\u8996\u7a97\u5df2\u7d93\u6253\u958b\u3002\u95dc\u9589\u800c\u4e0d\u5132\u5b58\u8a72\u8996\u7a97\uff0c\u7e7c\u7e8c\u55ce\uff1f",
        Resume: "\u6062\u5fa9",
        Heatwave: "\u71b1\u6d6a",
        "A filter pack has been updated. Click ok to reload the packs list.": "\u6ffe\u93e1\u5305\u5df2\u7d93\u66f4\u65b0\u3002 \u6309\u4e00\u4e0b\u300c\u78ba\u5b9a\u300d\u91cd\u65b0\u8f09\u5165\u5305\u6e05\u55ae",
        Update: "\u66f4\u65b0",
        Free: "\u514d\u8cbb",
        "There was an error downloading the image, please try again later.": "\u4e0b\u8f09\u5716\u7247\u6642\u51fa\u73fe\u932f\u8aa4\uff0c\u8acb\u65bc\u7a0d\u5f8c\u91cd\u8a66",
        Effects: "\u6548\u679c",
        Tools: "\u5de5\u5177",
        Reset: "\u91cd\u7f6e",
        Blemish: "\u6e05\u9664\u6c61\u9ede",
        Bulge: "\u51f8\u5ea6",
        Alice: "\u611b\u9e97\u7d72",
        "Are you sure you want to remove this sticker?": "\u662f\u5426\u78ba\u5b9a\u8981\u79fb\u9664\u9019\u500b\u8cbc\u7d19\uff1f",
        "Revert to original?": "\u6062\u5fa9\u5230\u539f\u59cb\u72c0\u614b\uff1f",
        Mohawk: "\u83ab\u970d\u514b",
        Enhance: "\u589e\u5f37",
        "Aviary is a free SDK available for iOS and Android that allows you to add photo-editing capabilities and effects to your app with just a few lines of code.": "Aviary\u662f\u4e00\u500b\u514d\u8cbb\u7684\u8edf\u9ad4\u958b\u767c\u5de5\u5177\u7bb1\uff0c\u53ef\u61c9\u7528\u65bciOS\u548cAndroid\uff0c\u8b93\u60a8\u53ea\u9700\u5e7e\u884c\u7a0b\u5f0f\u78bc\u5c31\u5c07\u7167\u7247\u7de8\u8f2f\u529f\u80fd\u548c\u6548\u679c\u52a0\u5165\u5230\u60a8\u7684\u61c9\u7528\u7a0b\u5f0f\u3002",
        Greeneye: "\u6d88\u9664\u7da0\u773c",
        Shadow: "\u9670\u5f71",
        Vogue: "\u6642\u5c1a",
        "You're about to lose all the changes you've made. Are you sure you want to revert to the original image?": "\u4f60\u7684\u6240\u6709\u6539\u52d5\u5c07\u6703\u4e1f\u5931\u3002 \u78ba\u5b9a\u8981\u6062\u5fa9\u5230\u539f\u59cb\u5716\u7247\u55ce\uff1f",
        OK: "\u78ba\u5b9a",
        Intensity: "\u5f37\u5ea6",
        Whiten: "\u6f02\u767d",
        Frames: "\u5e40",
        "Add some grit and visual wear-and-tear to your photos with these six grungy effects.": "\u4f7f\u7528\u9019 6 \u7a2e\u7834\u820a\u6548\u679c\uff0c\u70ba\u4f60\u7684\u76f8\u7247\u589e\u52a0\u4e00\u4e9b\u9846\u7c92\u611f\u548c\u8996\u89ba\u4e0a\u65e2\u7834\u4e14\u820a\u7684\u611f\u89ba\u3002",
        "Delete selected": "\u522a\u9664\u9078\u5b9a\u5167\u5bb9",
        "A sticker pack has been updated. We need to reload the current panel. Do you want to apply the current sticker?": "\u8cbc\u7d19\u5305\u5df2\u7d93\u66f4\u65b0\u3002 \u6211\u5011\u9700\u8981\u91cd\u65b0\u8f09\u5165\u7576\u524d\u8996\u7a97\u3002 \u662f\u5426\u8981\u5957\u7528\u7576\u524d\u8cbc\u7d19\uff1f",
        "Set color": "\u8a2d\u5b9a\u984f\u8272",
        "Always Sunny": "\u967d\u5149\u660e\u5a9a",
        Confirm: "\u78ba\u8a8d",
        Siesta: "\u897f\u8036\u65af",
        Negative: "\u8ca0\u7247",
        Send: "\u767c\u9001",
        "Keep editing": "\u7e7c\u7e8c\u7de8\u8f2f",
        "Powered by Aviary.com": "\u7531Aviary.com \u63d0\u4f9b\u6280\u8853\u652f\u63f4",
        Zoom: "\u7e2e\u653e",
        Editor: "\u7de8\u8f2f\u5668",
        "Soft Focus": "\u67d4\u7126",
        Save: "\u5132\u5b58",
        "Are you sure?": "\u60a8\u78ba\u5b9a\u55ce\uff1f",
        Warmth: "\u71b1\u60c5",
        More: "\u66f4\u591a",
        Meme: "\u7030\u56e0",
        Charcoal: "\u70ad\u7b46",
        Malibu: "\u99ac\u91cc\u5e03",
        Grunge: "\u9839\u6f2c",
        "Tool Selection": "\u5de5\u5177\u9078\u64c7",
        Auto: "\u81ea\u52d5",
        Tool: "\u5de5\u5177",
        Daydream: "\u767d\u65e5\u5922",
        Eddie: "\u827e\u8fea",
        Cinematic: "\u96fb\u5f71",
        Store: "\u5132\u5b58",
        Backlit: "\u80cc\u5149",
        Fixie: "\u5b9a\u901f",
        "Are you sure you want to discard changes from this tool?": "\u4f60\u78ba\u5b9a\u8981\u653e\u68c4\u5728\u6b64\u5de5\u5177\u4e2d\u6240\u505a\u7684\u4fee\u6539\uff1f",
        Brightness: "\u4eae\u5ea6",
        "Wait! You didn't save your work. Are you certain that you want to close this editor?": "\u8acb\u7a0d\u7b49\uff01\u60a8\u9084\u6c92\u6709\u5132\u5b58\u60a8\u7684\u4fee\u6539\u3002\u60a8\u78ba\u5b9a\u8981\u95dc\u9589\u6b64\u7de8\u8f2f\u5668\u55ce\uff1f",
        Smooth: "\u9806\u5229",
        "Get this editor": "\u53d6\u5f97\u6b64\u7de8\u8f2f\u5668",
        Draw: "\u7e6a\u5716",
        Flip: "\u7ffb\u8f49",
        "Soft Brushes": "\u8edf\u5237",
        Viewfinder: "\u89c0\u666f\u5668",
        "Your work was saved!": "\u5df2\u5132\u5b58",
        Delete: "\u522a\u9664",
        Square: "\u548c\u8ae7",
        Rounded: "\u5713\u89d2",
        Redo: "\u91cd\u505a",
        "Preset Sizes": "\u9810\u8a2d\u5c3a\u5bf8",
        Sharpness: "\u92b3\u5229\u5ea6",
        Back: "\u8fd4\u56de",
        "Brush softness": "\u7b46\u5237\u786c\u5ea6",
        Brush: "\u7b46\u5237",
        Mirror: "\u93e1\u50cf",
        "Edit Bottom Text": "\u7de8\u8f2f\u5e95\u90e8\u6587\u5b57",
        "Photo Editor": "\u7167\u7247\u7de8\u8f2f\u5668",
        "Maintain proportions": "\u7dad\u6301\u6bd4\u4f8b",
        Vivid: "\u9bae\u660e",
        "San Carmen": "\u8056\u5361\u9580",
        Retro: "\u5fa9\u53e4",
        Exit: "\u51fa\u53e3",
        Undo: "\u9084\u539f",
        "Loading Image...": "\u6b63\u5728\u8f09\u5165\u5716\u7247...",
        Borders: "\u908a\u754c",
        Contrast: "\u5c0d\u6bd4\u5ea6",
        "Instant!": "\u62cd\u7acb\u5f97",
        "Choose Color": "\u9078\u64c7\u984f\u8272",
        Strato: "\u5c64\u96f2",
        Vignette: "\u63d2\u5716",
        "Zoom Mode": "\u7e2e\u653e\u6a21\u5f0f",
        "A sticker pack has been updated. We need to reload the current panel.": "\u8cbc\u7d19\u5305\u5df2\u7d93\u66f4\u65b0\u3002 \u6211\u5011\u9700\u8981\u91cd\u65b0\u8f09\u5165\u7576\u524d\u8996\u7a97",
        Vigilante: "\u62ff\u6492\u52d2",
        "Hard Brushes": "\u786c\u5237",
        "Brush size": "\u7b46\u5237\u5c3a\u5bf8",
        "Get More": "\u53d6\u5f97\u66f4\u591a",
        "Color Matrix": "\u984f\u8272\u77e9\u9663",
        Corners: "\u89d2\u843d",
        Aqua: "\u6c34\u5411",
        Ragged: "\u4e0d\u5c0d\u9f4a",
        Ventura: "\u51e1\u675c\u62c9",
        Error: "\u932f\u8aa4",
        Kurt: "\u5eab\u723e\u7279",
        Balance: "\u5e73\u8861",
        Original: "\u539f\u4ef6",
        "Your image was temporarily shrunk to make it easier to edit it. When you hit Save, you will save the full display size.": "\u60a8\u7684\u5716\u50cf\u5df2\u66ab\u6642\u7e2e\u5c0f\uff0c\u4f7f\u5176\u66f4\u5bb9\u6613\u9032\u884c\u7de8\u8f2f\u3002\u7576\u60a8\u9ede\u9078\u5132\u5b58\uff0c\u5c07\u6703\u5132\u5b58\u5b8c\u6574\u7684\u986f\u793a\u5927\u5c0f\u3002",
        "Reminisce over fond memories and good times with our six dreamy nostalgia effects.": "\u4f7f\u7528\u9019 6 \u7a2e\u5922\u5e7b\u7684\u61f7\u820a\u6548\u679c\uff0c\u8ffd\u61b6\u6709\u8da3\u7684\u5f80\u4e8b\u548c\u7f8e\u597d\u7684\u6642\u5149\u3002",
        Orientation: "\u65b9\u5411",
        "Add Text": "\u52a0\u5165\u6587\u5b57",
        Classic: "\u7d93\u5178",
        Text: "\u6587\u5b57",
        "No stickers defined in Feather_Stickers.": "\u5728Feather_Stickers\u4e2d\u6c92\u6709\u5b9a\u7fa9\u8cbc\u7d19",
        "Drag corners to resize crop area": "\u62d6\u52d5\u908a\u89d2\u4ee5\u8abf\u6574\u88c1\u5207\u7bc4\u570d",
        "Give feedback": "\u63d0\u4f9b\u610f\u898b",
        "Get this pack!": "\u5165\u624b\u65b0\u5957\u4ef6\uff01",
        Height: "\u9ad8\u5ea6",
        Colors: "\u984f\u8272",
        Done: "\u5b8c\u6210",
        "See your world a little differently with these six high-tech camera effects.": "\u5229\u7528\u516d\u500b\u9ad8\u79d1\u6280\u76f8\u6a5f\u6548\u679c\uff0c\u8b93\u60a8\u770b\u4e16\u754c\u6709\u90a3\u9ebd\u9ede\u4e0d\u540c\u3002",
        Cancel: "\u53d6\u6d88",
        Close: "\u95dc\u9589",
        "Width and height must be greater than zero and less than the maximum({max}px)": "\u5bec\u5ea6\u548c\u9ad8\u5ea6\u5fc5\u9808\u5927\u65bc\u96f6\uff0c\u4e14\u5c0f\u65bc\u6700\u5927\u503c ({max}px)",
        "Leave editor": "\u96e2\u958b\u7de8\u8f2f\u5668",
        Size: "\u5c3a\u5bf8",
        "e-mail address": "\u96fb\u5b50\u90f5\u4ef6\u5730\u5740",
        Eraser: "\u6a61\u76ae",
        Min: "\u6700\u5c0f",
        Cherry: "\u6afb\u6843",
        "Are you sure? This can distort your image": "\u662f\u5426\u78ba\u5b9a\u8981\u57f7\u884c\uff1f \u9019\u53ef\u80fd\u6703\u5c0e\u81f4\u5716\u7247\u8b8a\u5f62",
        "A sticker pack has been updated. Click ok to reload the packs list.": "\u8cbc\u7d19\u5305\u5df2\u7d93\u66f4\u65b0\u3002 \u6309\u4e00\u4e0b\u300c\u78ba\u5b9a\u300d\u91cd\u65b0\u8f09\u5165\u5305\u6e05\u55ae",
        Custom: "\u81ea\u8a02",
        Fade: "\u892a\u8272",
        Singe: "\u71d2\u707c",
        Drifter: "\u6f02\u6cca",
        Saturation: "\u98fd\u548c\u5ea6",
        "Crop again": "\u518d\u6b21\u88c1\u5207",
        "Aviary Editor": "\u9ce5\u7c60\u7de8\u8f2f\u5668",
        Max: "\u6700\u5927",
        Attention: "\u6ce8\u610f",
        Redeye: "\u6d88\u9664\u7d05\u773c",
        Halftone: "\u534a\u8272\u8abf",
        Pinch: "\u6536\u7e2e",
        "Old Photo": "\u8001\u7167\u7247",
        Laguna: "\u62c9\u53e4\u5a1c",
        Resize: "\u8abf\u6574\u5c3a\u5bf8",
        "Powered by": "Powered by",
        "Color Grading": "\u984f\u8272\u5206\u5c64",
        Firefly: "\u87a2\u706b\u87f2",
        Rotate: "\u65cb\u8f49",
        "Applying effects": "\u5957\u7528\u6548\u679c",
        "Enter text here": "\u5728\u6b64\u8f38\u5165\u6587\u5b57",
        "Code Red": "\u7d05\u8272\u4ee3\u78bc",
        "Interested? We'll send you some info.": "\u611f\u8208\u8da3\u55ce\uff1f\u6211\u5011\u6703\u5411\u60a8\u63d0\u4f9b\u66f4\u591a\u8cc7\u8a0a\u3002",
        Remove: "\u79fb\u9664",
        Concorde: "\u5354\u548c",
        "Vignette Blur": "\u6a21\u7cca\u6688\u5316",
        "About this editor": "\u95dc\u65bc\u6b64\u7de8\u8f2f\u5668",
        Discard: "\u653e\u68c4",
        "Film Grain": "\u81a0\u7247\u9846\u7c92",
        Power: "\u96fb\u6e90",
        Color: "\u984f\u8272",
        Demo: "\u6f14\u793a",
        Crop: "\u88c1\u5207",
        "Edit Top Text": "\u7de8\u8f2f\u9802\u90e8\u6587\u5b57",
        "You're about to lose the changes you've made in this tool. Are you sure you want to leave?": "\u4e0d\u5132\u5b58\u4fee\u6539\u4e26\u96e2\u958b\uff1f",
        Apply: "\u5957\u7528",
        Stickers: "\u8cbc\u7d19"
    }
})(AV.lang = AV.lang || {});
(function(a) {
    a.AV = a.AV || {};
    var b = a.AV;
    b.Pager = function(a) {
        var f, c = 0,
            e = 0,
            h = parseInt((a.itemCount - 1) / a.itemsPerPage, 10) + 1,
            k = 0,
            g = function() {
                for (var b = "", c = 0; c < h - 1; c++) b += a.pipTemplate({
                    i: c
                });
                return b
            },
            j = function() {
                f.removeClass("avpw_page_selected");
                f.filter('[pagenum="' + c + '"]').addClass("avpw_page_selected")
            },
            l = function() {
                0 == c ? avpw$(a.leftArrow).removeClass("avpw_prev_enabled").addClass("avpw_prev_disabled") : avpw$(a.leftArrow).removeClass("avpw_prev_disabled").addClass("avpw_prev_enabled");
                c == h - 1 ? avpw$(a.rightArrow).removeClass("avpw_next_enabled").addClass("avpw_next_disabled") : avpw$(a.rightArrow).removeClass("avpw_next_disabled").addClass("avpw_next_enabled")
            },
            n = function(a, b) {
                var c = avpw$(".avpw_arrow"),
                    d = function() {
                        c.addClass("avpw_arrow_visible")
                    };
                c.removeClass("avpw_arrow_visible");
                window.setTimeout(function() {
                    a.apply(this, b);
                    window.setTimeout(d, 600)
                }, 200)
            },
            i = function(b) {
                var b = b || 0,
                    c = b * a.pageWidth;
                0 !== b && (c -= k);
                return c
            },
            p = function(g, e) {
                var m = a.pageContainer,
                    r;
                if (0 > c) {
                    if (c = 0, "mobile" == b.launchData.openType || "aviary" == b.launchData.openType) return
                } else if (c > h - 1 && (c = h - 1, "mobile" == b.launchData.openType || "aviary" == b.launchData.openType)) return;
                r = i(c);
                if ("mobile" == b.launchData.openType) {
                    var k = b.support.getVendorProperty("transform");
                    n(function() {
                        m[0].style[k] = 0 < c ? "translateX(-" + r + "px)" : "translateX(0)";
                        l()
                    })
                } else {
                    if ("aviary" == b.launchData.openType)(k = b.support.getVendorProperty("transform")) ? m[0].style[k] = 0 < c ? "translateX(-" + r + "px)" : "translateX(0)" : m[0].style.left = 0 < c ? "-" + r + "px" : "0";
                    else {
                        var o = avpw$(m).parent();
                        g ? o.animate({
                            scrollLeft: r
                        }, 1, "swing", e) : o.animate({
                            scrollLeft: r
                        }, 300, "swing", e)
                    }
                    l()
                }
                a.onPageChange && a.onPageChange.apply(this, [c]);
                f && j()
            },
            m = function() {
                e = c;
                c += -1;
                p.call(this);
                return !1
            },
            q = function() {
                e = c;
                c += 1;
                p.call(this);
                return !1
            },
            s = function() {
                var b = a.longPressDuration || 200,
                    g = !1,
                    h = null,
                    f = this,
                    r = function(a) {
                        h = window.setInterval(function() {
                            g && a ? (e = c, c += a, p.call(f)) : (window.clearInterval(h), h = null)
                        }, b)
                    },
                    i = function() {
                        window.clearInterval(h);
                        h = null;
                        m.call(this);
                        g = !0;
                        r.call(this, -1)
                    },
                    k = function() {
                        window.clearInterval(h);
                        h = null;
                        q.call(this);
                        g = !0;
                        r.call(this, 1)
                    },
                    j = function() {
                        g = !1
                    };
                return {
                    bindEvents: function() {
                        avpw$(a.leftArrow).bind("mousedown", i).bind("mouseup", j);
                        avpw$(a.rightArrow).bind("mousedown", k).bind("mouseup", j)
                    },
                    unbindEvents: function() {
                        avpw$(a.leftArrow).unbind("mousedown", i).unbind("mouseup", j);
                        avpw$(a.rightArrow).unbind("mousedown", k).unbind("mouseup", j)
                    }
                }
            }(),
            o = function(a, c, d) {
                var g, h, e, f;
                a.length && (a = a[0]);
                a.ontouchstart = function(a) {
                    if (a = b.util.getTouch(a)) g = a.pageX, e = a.pageY
                };
                a.ontouchmove = function(a) {
                    if (a = b.util.getTouch(a)) h = a.pageX, f = a.pageY;
                    return !1
                };
                a.ontouchend = function() {
                    var a = h - g,
                        b = f - e;
                    30 < Math.abs(a) && 60 > Math.abs(b) && (30 < a ? c() : d());
                    h = g = void 0
                }
            },
            r = function(b) {
                if (a.itemsPerPage && !a.pageTemplate) {
                    var b = b.currentTarget,
                        g = a.pageContainer.offset().left,
                        b = avpw$(b).offset().left - g;
                    b >= i(c + 1) ? (c++, p()) : b < i(c) && (c--, p())
                }
            };
        this.shutdown = function() {
            if (1 !== h) {
                a.bindLongPress ? s.unbindEvents() : (avpw$(a.leftArrow).unbind("click"), avpw$(a.rightArrow).unbind("click"));
                var b = a.pageContainer;
                b.length && (b = b[0]);
                b.ontouchstart = b.ontouchmove = b.ontouchend = null;
                f && f.unbind("click");
                a.pageContainer.undelegate(".avpw_icon", "click")
            }
            a.pageContainer.html("");
            f && a.pipContainer.html("")
        };
        this.changePage = p;
        this.pageLeft = m;
        this.pageRight = q;
        this.setCurrentPage = function(a) {
            e = c;
            c = a
        };
        this.getCurrentPage = function() {
            return c
        };
        this.getPreviousPage = function() {
            return e
        };
        this.getLastPage = function() {
            return h - 1
        };
        this.getPageCount = function() {
            return h
        };
        this.setPageCount = function(a) {
            h = a
        };
        a.pageContainer.html(function() {
            var b, c, g, e = "";
            a.firstPageTemplate && (e += a.firstPageTemplate.apply(this, [a.firstPageContents]));
            for (c = b = 0; c < h; c++) {
                var f = "";
                for (g = 0; g < a.itemsPerPage && b < a.itemCount; g++) f += a.itemBuilder.apply(this, [b]), b++;
                e = a.pageTemplate ? e + a.pageTemplate({
                    panelHTML: f,
                    panelWidth: a.pageWidth,
                    panelClass: g === a.itemsPerPage ? "avpw_scroll_page_complete" : "avpw_scroll_page_incomplete"
                }) : e + f
            }
            a.lastPageTemplate && (e += a.lastPageTemplate.apply(this, [a.lastPageContents]));
            g = c = b = 0;
            if (a.firstPageTemplate || a.lastPageTemplate) a.firstPageTemplate && (a.firstPageWidth ? b += a.firstPageWidth * (a.itemsPerPage / a.pageWidth) : h++), a.lastPageTemplate && (a.lastPageWidth ? b += a.lastPageWidth * (a.itemsPerPage / a.pageWidth) : h++), c = a.itemCount % a.itemsPerPage, b && (k = a.pageWidth / a.itemsPerPage - a.firstPageWidth % (a.pageWidth / a.itemsPerPage) | 0, g = k * (a.itemsPerPage / a.pageWidth), 0 < c ? c + b + g > a.itemsPerPage && h++ : h++);
            if (!a.pageTemplate && a.fillRemainingSpace && (b = (a.itemCount + b + g) % a.itemsPerPage)) {
                c = a.itemsPerPage - b;
                if (1 === h && a.centerContents) {
                    c = c / 2 | 0;
                    g = "";
                    for (b = 0; b < c; b++) g += a.fillRemainingSpace.apply(this);
                    e = g + e
                }
                for (b = 0; b < c; b++) e += a.fillRemainingSpace.apply(this)
            }
            return e
        }());
        1 === h ? (avpw$(a.leftArrow).hide(), avpw$(a.rightArrow).hide(), a.centerContents && (a.pageContainer.css("width", "auto"), a.pageContainer.addClass("avpw_scroll_strip_centered"))) : (avpw$(a.leftArrow).show(), avpw$(a.rightArrow).show(), a.pageContainer.removeClass("avpw_scroll_strip_centered"), a.pipContainer && a.pipTemplate && (a.pipContainer.html(g()), f = a.pipContainer.find(".avpw_is_navpip"), j()));
        (function() {
            if (h !== 1) {
                if (a.bindLongPress) s.bindEvents();
                else {
                    avpw$(a.leftArrow).bind("click", function() {
                        return a.onPageLeft ? a.onPageLeft.call(this) : m.call(this)
                    });
                    avpw$(a.rightArrow).bind("click", function() {
                        return a.onPageRight ? a.onPageRight.call(this) : q.call(this)
                    })
                }
                o(a.pageContainer, m, q);
                f && f.bind("click", function(a) {
                    a = avpw$(a.currentTarget).attr("pagenum");
                    e = c;
                    c = parseInt(a);
                    p();
                    return false
                });
                a.pageContainer.delegate(".avpw_icon", "click", r)
            }
        })();
        return this
    };
    return a
})(this);
"undefined" == typeof AV && (AV = {});
//AV.colorChoices = "#000000 #ffffff #f6311d #f7663c #f89a3c #f9ce3c #fcff3d #41ff34 #249b33 #2b61cb #3e00cb #380098 #650098 #9700cb #f8639b #f81ecd #f7289b #f62e6a #000000 #444444 #777777 #878787 #aaaaaa #bababa #dddddd #ffffff".split(" ");
AV.colorChoices = "#ffffff #ffcbcd #cdffcc #cdffff #feccff #d9d9d9 #ff6666 #66ff66 #99cdff #cd9aff #a4a4a4 #ff3334 #33cc00 #6599ff #9932cb #5e5e5e #cc0001 #019700 #0066cb #650199 #000000 #670001 #013300 #003399 #330065".split(" ");
//AV.brushWidths = [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 60, 70];
AV.brushWidths = [5, 15, 25, 35, 45, 60];
AV.buildBlankPreset = function() {
    return AV.template[AV.launchData.layout].blankPreset()
};
AV.buildBlankCropPreset = function() {
    return AV.template[AV.launchData.layout].blankCropPreset()
};
AV.toolDefaults = {
    redeye: {
        presets: AV.brushWidths,
        files: [AV.build.feather_baseURL + "redeye.swf"]
    },
    greeneye: {
        initialvalue: 10,
        presets: [3, 7, 12, 20],
        presetsScaled: [2, 5, 9, 15]
    },
    whiten: {
        initialvalue: 10,
        presets: AV.brushWidths,
        files: [AV.build.feather_baseURL + "redeye.swf"]
    },
    blemish: {
        initialvalue: 5,
        presets: AV.brushWidths,
        files: [AV.build.feather_baseURL + "blemish.swf"]
    },
    bulge: {
        radius: 100,
        maxradius: 200,
        power: 50
    },
    pinch: {
        radius: 100,
        maxradius: 200,
        power: 50
    },
    drawing: {
        initialColor: AV.colorChoices[7],
        presetsWidth: AV.brushWidths,
        presetsColor: AV.colorChoices,
        files: [AV.build.feather_baseURL + "drawing.swf"]
    },
    text: {
        presetsColor: AV.colorChoices,
        files: [AV.build.feather_baseURL + "text.swf"]
    },
    sharpness: {
        presets: [-100, -33, 33, 100],
        files: [AV.build.feather_baseURL + "blur.swf", AV.build.feather_baseURL + "sharpen.swf"]
    },
    saturation: {
        presets: [0, 0.66, 1.33, 2],
        presetsFlash: [{
            value: 0
        }, {
            value: 0.66
        }, {
            value: 1.33
        }, {
            value: 2
        }],
        files: [AV.build.feather_baseURL + "saturation.swf"]
    },
    warmth: {
        presets: [-100, -33, 33, 100]
    },
    brightness: {
        presets: [-100, -33, 33, 100],
        presetsFlash: [{
            brightnessvalue: 0.33,
            contrastvalue: 1
        }, {
            brightnessvalue: 0.77,
            contrastvalue: 1
        }, {
            brightnessvalue: 1.22,
            contrastvalue: 1
        }, {
            brightnessvalue: 1.66,
            contrastvalue: 1
        }],
        files: [AV.build.feather_baseURL + "brightness.swf"]
    },
    contrast: {
        presets: [-100, -33, 33, 100],
        presetsFlash: [{
            contrastvalue: 0.33,
            brightnessvalue: 1
        }, {
            contrastvalue: 0.77,
            brightnessvalue: 1
        }, {
            contrastvalue: 1.22,
            brightnessvalue: 1
        }, {
            contrastvalue: 1.66,
            brightnessvalue: 1
        }],
        files: [AV.build.feather_baseURL + "contrast.swf"]
    },
    colors: {
        min: -133,
        max: 67,
        initialValue: -33,
        files: [AV.build.feather_baseURL + "colors.swf"]
    },
    orientation: {
        presetsFlash: [{
            vertical: !1,
            horizontal: !1
        }, {
            vertical: !1,
            horizontal: !0
        }, {
            vertical: !0,
            horizontal: !1
        }, {
            vertical: !0,
            horizontal: !0
        }],
        files: [AV.build.feather_baseURL + "flip.swf", AV.build.feather_baseURL + "rotate90.swf"]
    },
    crop: {
        files: [AV.build.feather_baseURL + "crop.swf", AV.build.feather_baseURL + "resize.swf"]
    },
    resize: {
        files: [AV.build.feather_baseURL + "resize.swf"]
    },
    overlay: {
        files: [AV.build.feather_baseURL + "overlay.swf"]
    },
    effects: {
        files: [AV.build.feather_baseURL + "effects.swf"],
        optionalFrame: "singe sancarmen purple thresh aqua andy edgewood joecool".split(" ")
    },
    enhance: {
        presets: ["autoenhance", "nightenhance", "backlightenhance", "labcorrect"],
        files: [AV.build.feather_baseURL + "effects.swf"]
    },
    frames: {
        presetsThickness: [1, 2, 3, 4, 5, 6]
    }
};
AV.buildColorPicker = function(a, b, d, f) {
    var c = avpw$(b).data("color"),
        e = null,
        h = null,
        k = function(a) {
            var b = a,
                b = parseInt(-1 < b.indexOf("#") ? b.substring(1) : b, 16),
                a = b >> 16,
                c = (b & 65280) >> 8,
                b = b & 255,
                d = {
                    h: 0,
                    s: 0,
                    b: 0
                },
                g = Math.min(a, c, b),
                e = Math.max(a, c, b),
                g = e - g;
            d.b = e;
            d.s = 0 !== e ? 255 * g / e : 0;
            d.h = 0 !== d.s ? a === e ? (c - b) / g : c === e ? 2 + (b - a) / g : 4 + (a - c) / g : -1;
            d.h *= 60;
            0 > d.h && (d.h += 360);
            d.s *= 100 / 255;
            d.b *= 100 / 255;
            0 === d.s && (d.h = 360);
            return d
        },
        g = function(a) {
            var a = a || avpw$(b).data("color"),
                c = avpw$(b).find(".avpw_isa_color_feedback");
            a ? c.css("background-color", a).addClass("avpw_custom_color_image_with_preview") : c.removeClass("avpw_custom_color_image_with_preview")
        },
        j = function(a) {
            a = a || avpw$(b).data("color");
            h.find(".avpw_isa_color_feedback").css("background-color", a)
        },
        l = function() {
            var a = avpw$(b).offset(),
                c = avpw$("#avpw_controls").offset();
            a.left += avpw$(b).width() / 2;
            a.left -= h.width() / 2;
            a.left -= c.left;
            a.top -= c.top;
            h.css({
                left: a.left + "px",
                top: a.top + 44 + "px"
            })
        },
        n = function() {
            AV.miniColors.unBindSelectorEvents();
            h.undelegate(".avpw_color_picker_confirm", "mousedown");
            e.unbind();
            h.hide().remove();
            h = e = null;
            AV.featherUseFlash && AV.FlashAPI.showCanvas()
        };
    AV.miniColors && (c = c || a || "#ffffff", a = k(c), e = AV.miniColors.buildSelector(a), (h = avpw$(".avpw_color_picker_container")) && h.length ? (h.unbind(), h.detach()) : h = avpw$(AV.template[AV.launchData.layout].colorPickerContainer()), AV.miniColors.bindSelectorEvents(e), avpw$("#avpw_controls").append(h.append(e)), h.delegate(".avpw_color_picker_confirm", "mousedown", function(a) {
        a.stopPropagation();
        a.preventDefault();
        d && d.apply(this, [c]);
        a = c;
        avpw$(b).data("color", a);
        g(c);
        n()
    }), e.bind("clickOutsideBounds", function() {
        g("");
        n();
        f && f.call(this)
    }).bind("setColor", function(a, b) {
            c = "#" + b.hex;
            j(c)
        }).show(), j(c), l(), AV.featherUseFlash && AV.FlashAPI.hideCanvas())
};
AV.ControlsWidget.prototype.populateCropPresets = function(a) {
    var b, d, f, c, e, h, k = AV.launchData.cropPresetsStrict;
    e = this.imageSizeTracker.isUsingHiResDimensions(AV.launchData);
    AV.featherUseFlash || e ? (b = this.paintWidget.getScaledSize(), e = b.width, h = b.height) : (e = this.paintWidget.width, h = this.paintWidget.height);
    b = 1 < e / h;
    for (var g = [], j = 0; j < a.length; j++) {
        var l, n = a[j] instanceof Array;
        c = n ? a[j][1] : a[j];
        l = n ? a[j][0] : a[j];
        d = c.indexOf("x");
        if ("custom" === l.toLowerCase()) n = !0, l = {
            label: l,
            width: 0.85 * e,
            height: 0.75 * h,
            constrain: !1,
            resize: !1
        };
        else if ("original" === l.toLowerCase()) n = !0, l = {
            label: l,
            width: e,
            height: h,
            constrain: !0,
            resize: !1
        };
        else if (-1 != d) {
            f = c.substr(0, d);
            d = c.substr(d + 1);
            f = parseInt(f, 10);
            d = parseInt(d, 10);
            if (f >= e || d >= h) continue;
            if (d >= e || f >= h) continue;
            if (!n && !k && (b && 1 > f / d || !b && 1 < f / d)) l = d + "x" + f, c = f, f = d, d = c;
            l = {
                label: l,
                width: f,
                height: d,
                constrain: !0,
                resize: !0
            }
        } else if (d = c.indexOf(":"), -1 == d && "" !== avpw$.trim(c)) continue;
        else {
            f = c.substr(0, d);
            d = c.substr(d + 1);
            f = parseInt(f, 10);
            d = parseInt(d, 10);
            if (!n && !k && (b && 1 > f / d || !b && 1 < f / d)) l =
                d + ":" + f, c = f, f = d, d = c;
            l = {
                label: l,
                width: f,
                height: d,
                constrain: !0,
                resize: !1
            }
        }
        l && (l.strict = k, l.labeled = n, g.push(l))
    }
    return g
};
AV.PacksAndItems = function(a) {
    var b = a.browser,
        d = a.packBrowser,
        f = a.pager,
        c = a.packPager,
        e = a.buildPacksHTML,
        h = a.buildPackItemsHTML,
        k = a.currentPack,
        g = AV.support.getVendorProperty("transition"),
        j = this,
        l = function(a, b) {
            var c = avpw$("#avpw_all_effects"),
                d = avpw$("#avpw_up_one_level"),
                g = function(g) {
                    a ? (d.text(a), b && d.bind("click", b), c.hide(), g ? d.show() : (d.addClass("avpw_back_button_hidden"), d.show(), window.setTimeout(function() {
                        d.removeClass("avpw_back_button_hidden")
                    }, 100))) : (d.unbind("click"), d.hide(), g ? c.show() : (c.addClass("avpw_back_button_hidden"), c.show(), window.setTimeout(function() {
                        c.removeClass("avpw_back_button_hidden")
                    }, 100)))
                };
            c.is(":visible") ? (c.addClass("avpw_back_button_hidden"), window.setTimeout(function() {
                g();
                c.removeClass("hidden")
            }, 100)) : d.is(":visible") ? (d.addClass("avpw_back_button_hidden"), window.setTimeout(function() {
                g();
                d.removeClass("avpw_back_button_hidden")
            }, 100)) : g(!0)
        },
        n = function(a) {
            d.find(".avpw_pack_icon_selected").removeClass("avpw_pack_icon_selected");
            avpw$(a).addClass("avpw_pack_icon_selected");
            if (g) {
                var e = AV.support.getVendorProperty("transform"),
                    f = AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "getDims").INNER_BROWSER_WIDTH + "px";
                b[0].style[e] = "translateX(" + f + ")";
                b.addClass("avpw_transition_to_pack_contents");
                b.show();
                window.setTimeout(function() {
                    var g = AV.support.getVendorProperty("transform");
                    a.style[g] = "translateX(" + f + ")";
                    avpw$(a).removeClass("avpw_pack_icon_selected");
                    window.setTimeout(function() {
                        d.hide();
                        c && (c.shutdown(), c = null);
                        h && h();
                        window.setTimeout(function() {
                            b.removeClass("avpw_transition_to_pack_contents")
                        }, 200)
                    }, 600)
                }, 100)
            } else d.hide(), c && (c.shutdown(), c = null), h && h(), b.show()
        },
        i = function() {
            if (g) {
                var a = AV.support.getVendorProperty("transform"),
                    c = AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "getDims").INNER_BROWSER_WIDTH + "px";
                b[0].style[a] = "translateX(" + c + ")";
                window.setTimeout(function() {
                    f && (f.shutdown(), f = null);
                    l();
                    e && e();
                    d.show()
                }, 400)
            } else f && (f.shutdown(), f = null), l(), e && e(), d.show()
        },
        p = function() {
            j.onBackButtonClicked();
            i();
            return !1
        };
    j.onPackClicked = function(a) {
        var a = a.currentTarget,
            b = avpw$(a).data("pack");
        k !== b && (k = b || k);
        l(AV.getLocalizedString("Back"), p);
        n(a);
        return !1
    };
    j.onPackInfoClicked = function(a) {
        _packOfInterest = a = a.currentTarget;
        a = avpw$(a).data("pack");
        AV.controlsWidgetInstance.purchaseManager.showAssetPurchaseView(a, j.onPurchaseButtonClicked);
        return !1
    };
    j.onBackButtonClicked = function() {};
    j.onPurchaseButtonClicked = function() {};
    j.getCurrentPack = function() {
        return k
    };
    j.setCurrentPack = function(a) {
        k !== a && (k = a || k)
    };
    j.getPackOfInterest = function() {
        return _packOfInterest
    };
    j.updateCancelButton =
        l;
    return j
};
(function(a) {
    a.fn.slider = function(b) {
        var b = AV.util.extend({
                max: 100,
                min: 0,
                value: 50
            }, b),
            d = 0,
            f = d,
            c = 0,
            e = null,
            h = null,
            k = !1,
            g = null,
            j = this,
            l, n = null,
            i = 0,
            p = null,
            m = function(a) {
                a ? (l = l || j.outerWidth() || 1, a -= j.offset().left, a /= l, a = 1 < a ? 1 : a, a = 0 > a ? 0 : a, f = parseFloat((d + a * (c - d)).toFixed(5))) : a = c !== d ? (f - d) / (c - d) : 0;
                h.length && (k ? 0.5 < a ? h.css({
                    left: "50%",
                    width: 100 * (a - 0.5) + "%"
                }) : h.css({
                    left: 100 * a + "%",
                    width: 100 * (0.5 - a) + "%"
                }) : h.css({
                    left: "0",
                    width: 100 * a + "%"
                }));
                e.css("left", 100 * a + "%")
            },
            q = function(a) {
                j.trigger("slidestart", [a]);
                i && (p = null, n = window.setInterval(function() {
                    p && (j.trigger("slide", [p]), p = null)
                }, i))
            },
            s = function(a) {
                j.trigger("slidechange", [a]);
                i && window.clearInterval(n)
            },
            o = function() {
                a(document).bind("mousemove", r).bind("mouseup blur", u);
                q({
                    value: f
                });
                return !1
            },
            r = function(a) {
                var b = AV.util.getTouch(a);
                m(b ? b.pageX : a.pageX);
                a = {
                    value: f
                };
                i ? p = a : j.trigger("slide", [a])
            },
            u = function() {
                a(document).unbind("mousemove", r).unbind("mouseup blur", u);
                s({
                    value: f
                });
                return !1
            },
            t = function() {
                e.parent().bind("touchmove", r).bind("touchend", v);
                q({
                    value: f
                });
                return !1
            },
            v = function() {
                e.parent().unbind("touchmove", r).unbind("touchend", v);
                s({
                    value: f
                });
                return !1
            },
            z = function(a) {
                var b = AV.util.getTouch(a);
                m(b ? b.pageX : a.pageX);
                j.trigger("slidechange", [{
                    value: f
                }])
            },
            f = b.value,
            d = b.min,
            c = b.max,
            h = j.find(".avpw_slider_goo").eq(0),
            k = !! j.find(".avpw_slider_divider").length,
            e = j.find("a").eq(0),
            g = j.parents(".avpw_isa_slider_container");
        b.delay && (i = b.delay);
        g.length && (g.bind("mousedown", o), i ? g.bind("mousedown", z) : g.bind("click", z), a.support.touch && (g.bind("touchstart", t), i && g.bind("touchstart", z)));
        a.support.touch && e.bind("touchstart", t);
        e.bind("mousedown", o);
        j.slider = function(a, b) {
            switch (a) {
                case "value":
                    if (arguments.length < 1) return f;
                    f = b;
                    m();
                    break;
                case "destroy":
                    e.unbind("mousedown mouseup blur touchstart");
                    if (g.length) {
                        g.unbind("mousedown", z).unbind("mousedown", o).unbind("touchstart", z).unbind("touchstart", t).unbind("click", z);
                        g = null
                    }
                    e = h = null;
                    window.clearInterval(n);
                    n = null;
                    i = 0;
                    p = null
            }
        };
        return j
    }
})(avpw_jQuery);
(function(a, b) {
    a.fn.pressed = function(d) {
        this.bind("mousedown", function() {
            var f = a(this);
            f.addClass(d);
            b.setTimeout(function() {
                f.removeClass(d)
            }, 100)
        });
        return this
    }
})(avpw_jQuery, window);
AV.ControlsWidget.prototype.tool.brightness = function() {
    var a, b = AV.toolDefaults.brightness.presets,
        d, f, c, e = {},
        h = function(b, d) {
            c = d.value;
            var e = c / 100;
            AV.featherUseFlash && (e = 0.6 * e / 100 + 1);
            a.paintWidget.module.brightness.applyPreview(e, 0, !0)
        };
    e.init = function(c) {
        f = 100;
        var g = {
            element: document.getElementById("avpw_brightness_slider"),
            min: 100 * b[0],
            max: 100 * b[3],
            defaultValue: f,
            onchange: h
        };
        AV.featherUseFlash || AV.util.extend(g, {
            onslide: h,
            delay: 100
        });
        a = c;
        d = new a.Slider(g)
    };
    e.cancel = function() {
        e.resetUI()
    };
    e.panelWillOpen =

        function() {
            c = f
        };
    e.panelDidOpen = function() {
        d.addListeners()
    };
    e.panelWillClose = function() {
        d.removeListeners()
    };
    e.onUndo = function(a) {
        a || e.resetUI()
    };
    e.onRedo = function(a) {
        !a && d && d.setValue(c)
    };
    e.resetUI = function() {
        d.reset()
    };
    e.shutdown = function() {
        d && (d.shutdown(), d = void 0);
        c = f = void 0;
        a = null
    };
    return e
}();
AV.ControlsWidget.prototype.tool.contrast = function() {
    var a, b = AV.toolDefaults.contrast.presets,
        d, f, c, e = {},
        h = function(b, d) {
            c = d.value;
            var e = c / 100;
            AV.featherUseFlash && (e = 0.6 * e / 100 + 1);
            a.paintWidget.module.contrast.applyPreview(0, e, !0)
        };
    e.init = function(c) {
        f = 100;
        var g = {
            element: document.getElementById("avpw_contrast_slider"),
            min: 100 * b[0],
            max: 100 * b[3],
            defaultValue: f,
            onchange: h
        };
        AV.featherUseFlash || AV.util.extend(g, {
            onslide: h,
            delay: 100
        });
        a = c;
        d = new a.Slider(g)
    };
    e.cancel = function() {
        e.resetUI()
    };
    e.panelWillOpen =

        function() {
            c = f
        };
    e.panelDidOpen = function() {
        d.addListeners()
    };
    e.panelWillClose = function() {
        d.removeListeners()
    };
    e.onUndo = function(a) {
        a || e.resetUI()
    };
    e.onRedo = function(a) {
        !a && d && d.setValue(c)
    };
    e.resetUI = function() {
        d.reset()
    };
    e.shutdown = function() {
        d && (d.shutdown(), d = void 0);
        a = null
    };
    return e
}();
AV.ControlsWidget.prototype.tool.saturation = function() {
    var a, b = AV.toolDefaults.saturation.presets,
        d, f, c, e = {},
        h = function(b, d) {
            c = d.value;
            a.paintWidget.module.saturation.applyPreview(c / 100, !0)
        };
    e.init = function(c) {
        f = 100;
        var g = {
            element: document.getElementById("avpw_saturation_slider"),
            min: 100 * b[0],
            max: 100 * b[3],
            defaultValue: f,
            onchange: h
        };
        AV.featherUseFlash || AV.util.extend(g, {
            onslide: h,
            delay: 400
        });
        a = c;
        d = new a.Slider(g)
    };
    e.cancel = function() {
        e.resetUI()
    };
    e.panelWillOpen = function() {
        c = f
    };
    e.panelDidOpen = function() {
        d.addListeners()
    };
    e.panelWillClose = function() {
        d.removeListeners()
    };
    e.onUndo = function(a) {
        a || e.resetUI()
    };
    e.onRedo = function(a) {
        !a && d && d.setValue(c)
    };
    e.resetUI = function() {
        d.reset()
    };
    e.shutdown = function() {
        d && (d.shutdown(), d = void 0);
        a = null
    };
    return e
}();
AV.ControlsWidget.prototype.tool.sharpness = function() {
    var a, b = AV.toolDefaults.sharpness.presets,
        d, f, c, e = {},
        h = function(b, d) {
            c = d.value;
            a.paintWidget.module.blur.reset();
            a.paintWidget.module.sharpen.reset();
            if (!AV.featherUseFlash || 0 < c) a.paintWidget.setMode("sharpen"), a.paintWidget.module.sharpen.applyPreview(c, !0);
            else if (0 > c) {
                var e = c,
                    e = 0.15 * -e | 0;
                a.paintWidget.setMode("blur");
                a.paintWidget.module.blur.applyPreview(e, !0)
            }
        };
    e.init = function(c) {
        f = 0;
        a = c;
        d = new a.Slider({
            element: document.getElementById("avpw_sharpness_slider"),
            min: b[0],
            max: b[3],
            defaultValue: f,
            onchange: h
        })
    };
    e.cancel = function() {
        e.resetUI()
    };
    e.panelWillOpen = function() {
        c = f
    };
    e.panelDidOpen = function() {
        d.addListeners()
    };
    e.panelWillClose = function() {
        d.removeListeners()
    };
    e.onUndo = function(a) {
        a || e.resetUI()
    };
    e.onRedo = function(a) {
        !a && d && d.setValue(c)
    };
    e.resetUI = function() {
        d.reset()
    };
    e.shutdown = function() {
        d && (d.shutdown(), d = void 0);
        c = f = void 0;
        a = null
    };
    return e
}();
AV.ControlsWidget.prototype.tool.warmth = function() {
    var a, b = AV.toolDefaults.warmth.presets,
        d, f, c, e = {},
        h = function(b, d) {
            c = d.value;
            a.paintWidget.module.warmth.applyPreview(c, !0)
        };
    e.init = function(c) {
        f = 0;
        a = c;
        d = new a.Slider({
            element: document.getElementById("avpw_warmth_slider"),
            min: b[0],
            max: b[3],
            defaultValue: 0,
            delay: 100,
            onchange: h,
            onslide: h
        })
    };
    e.cancel = function() {
        e.resetUI()
    };
    e.panelWillOpen = function() {
        c = f
    };
    e.panelDidOpen = function() {
        d.addListeners()
    };
    e.panelWillClose = function() {
        d.removeListeners()
    };
    e.onUndo =

        function(a) {
            a || e.resetUI()
        };
    e.onRedo = function(a) {
        !a && d && d.setValue(c)
    };
    e.resetUI = function() {
        d.reset()
    };
    e.shutdown = function() {
        d && (d.shutdown(), d = void 0);
        a = null
    };
    return e
}();
AV.ControlsWidget.prototype.tool.orientation = function() {
    var a, b, d, f, c = null,
        e = null,
        h = null,
        k = null,
        g, j = !1,
        l = !1,
        n = 0,
        i = !1,
        p = !1,
        m = 0,
        q, s, o = {},
        r = function() {
            var b = d.layoutNotify(AV.launchData.openType, "getMaxDims"),
                b = b.height < b.width ? b.height : b.width,
                b = b - 10,
                b = b < AV.launchData.maxSize ? b : AV.launchData.maxSize;
            d.canvasUI && (d.canvasUI.viewport.resize(b, b), a ? window.setTimeout(d.canvasUI.resetOffset, 200) : d.canvasUI.resetOffset())
        },
        u = function() {
            r();
            if (d.canvasUI && b) {
                var a = d.canvasUI.viewport.getRatio(),
                    g = d.paintWidget.width / a,
                    a = d.paintWidget.height / a;
                d.canvasUI.viewport.resize(g, a);
                if (n % 180) var f = g,
                    g = a,
                    a = f;
                q.css({
                    width: g | NaN,
                    height: a | NaN,
                    "margin-left": "-" + (g / 2 | 0) + "px",
                    "margin-top": "-" + (a / 2 | 0) + "px"
                }).addClass("avpw_straighten_ui_ready");
                s && (s.shutdown(), s = void 0);
                s = new d.Slider({
                    element: q,
                    min: -0.5,
                    max: 0.5,
                    defaultValue: 0,
                    onstart: v,
                    onchange: z,
                    onslide: A
                });
                s.addListeners();
                avpw$("#avpw_straighten_handle").css({
                    left: "50%"
                });
                d.paintWidget.setMode("straighten");
                d.paintWidget.module.straighten.clearSelection();
                k = h = null;
                c = g;
                e =
                    a;
                var f = 0,
                    m = !1;
                avpw$("#container");
                for (var j, i = document.createDocumentFragment(), o = 0; 8 >= o; o++) {
                    var l = o * (a - 1) / 8,
                        l = l + 0.5 | 0;
                    j = avpw$('<div class="avpw_straighten_ui_grid_line"></div>');
                    j.css({
                        top: l + "px",
                        height: "1px",
                        width: g
                    });
                    4 === o && j.addClass("avpw_straighten_ui_center_grid_line");
                    i.appendChild(j[0]);
                    if (0 < o) for (var p = 0; 8 >= p; p++) {
                        var u = f + 1,
                            t = p * (g - 1) / 8 + 0.5 | 0,
                            w = l - f - 1;
                        j = avpw$('<div class="avpw_straighten_ui_grid_line"></div>');
                        j.css({
                            top: u + "px",
                            left: t + "px",
                            height: w + "px",
                            width: "1px"
                        });
                        4 === p && !m && (j.css({
                            top: 0,
                            height: a + "px"
                        }).addClass("avpw_straighten_ui_center_grid_line"), m = !0);
                        i.appendChild(j[0])
                    }
                    f = l
                }
                avpw$("#avpw_straighten_grid").html(i)
            }
        },
        t = function(c, d, g) {
            var c = c ? c + "deg" : "0",
                d = d ? d + "deg" : "0",
                g = g ? g + "deg" : "0",
                e = new AV.TransformStyle(f[0].style[b]);
            a ? e.set({
                rotateX: c,
                rotateY: d,
                rotateZ: g
            }) : e.set({
                rotate: c
            });
            f[0].style[b] = e.serialize()
        },
        v = function() {
            (n % 360 || j || l) && E();
            f.addClass("avpw_ready_for_straighten");
            avpw$("#avpw_straighten_grid").addClass("avpw_straighten_ui_grid_ready");
            d.paintWidget.setMode("straighten");
            d.paintWidget.module.straighten.setSelectionByRotation(h)
        },
        z = function() {
            f.removeClass("avpw_ready_for_straighten");
            avpw$("#avpw_straighten_grid").removeClass("avpw_straighten_ui_grid_ready");
            d.layoutNotify(AV.launchData.openType, "updateUndoRedo", [!0, !1])
        },
        A = function(g, r) {
            var J = r.value;
            if (b) {
                var m = AV.math.sign(J);
                k = 45 * (1 - Math.cos(J * Math.PI)) * m;
                h = k * Math.PI / 180;
                if (a) {
                    var J = l ? 180 : 0,
                        m = j ? 180 : 0,
                        i = new AV.TransformStyle(f[0].style[b]);
                    i.set({
                        rotateX: J + "deg",
                        rotateY: m + "deg",
                        rotateZ: k + "deg"
                    })
                } else i = new AV.TransformStyle(f[0].style[b]), i.set({
                    rotate: k + "deg"
                });
                f[0].style[b] = i.serialize();
                J = d.paintWidget.module.straighten.setSelectionByRotation(h);
                d.canvasUI && (c && e) && d.canvasUI.viewport.resize(c * J, e * J, !0)
            }
        },
        w = function(c, d) {
            if (a && !AV.featherUseFlash) {
                c && c.call(this);
                q.removeClass("avpw_straighten_ui_animate");
                q.removeClass("avpw_straighten_ui_ready");
                window.setTimeout(function() {
                    q.addClass("avpw_straighten_ui_animate");
                    u()
                }, 200);
                var g = l ? 180 : 0,
                    e = j ? 180 : 0,
                    h = new AV.TransformStyle(f[0].style[b]);
                h.set({
                    rotateX: g + "deg",
                    rotateY: e + "deg",
                    rotateZ: n + "deg"
                });
                f[0].style[b] = h.serialize()
            } else b && !AV.featherUseFlash ? (q.removeClass("avpw_straighten_ui_ready"), t(0, 0, 0), d && d.call(this), u()) : d && d.call(this)
        },
        H = function() {
            l = j = !1;
            n = 0;
            k = h = null;
            p = i = !1;
            m = 0
        },
        F = function() {
            a && !AV.featherUseFlash ? (f.removeClass("avpw_ready_for_orient"), q.removeClass("avpw_straighten_ui_animate"), q.removeClass("avpw_straighten_ui_ready"), window.setTimeout(function() {
                f.addClass("avpw_ready_for_orient");
                q.addClass("avpw_straighten_ui_animate");
                u()
            }, 200)) : (q.removeClass("avpw_straighten_ui_ready"), u())
        },
        E = function() {
            var b = n % 360;
            0 !== b && a && (d.paintWidget.setMode("rotate90"), d.paintWidget.module.rotate90.rotate90(b, g));
            if (j || l) d.paintWidget.setMode("flip"), d.paintWidget.module.flip.flip(j, l, g);
            p = i = !1;
            m = 0;
            i = j ? !i : i;
            p = l ? !p : p;
            m += -(n % 360);
            l = j = !1;
            n = 0;
            t(0, 0, 0);
            r()
        },
        G = function(c) {
            var d = 0,
                g = 0,
                e = 0;
            if (a && !AV.featherUseFlash) {
                if (m || p || i) d = p ? 180 : 0, g = i ? 180 : 0, e = m;
                t(d, g, e);
                window.setTimeout(function() {
                    t(0, 0, 0);
                    c && c.call(this)
                }, 200)
            } else b && !AV.featherUseFlash && t(0, 0, 0), c && c.call(this)
        },
        C = function(a) {
            var b = avpw$(a.currentTarget);
            a.stopPropagation();
            a.preventDefault();
            G(function() {
                b.unbind("click").trigger("click")
            })
        },
        D = function() {
            avpw$("#avpw_flip_h").bind("click", function() {
                AV.featherUseFlash ? (d.paintWidget.setMode("flip"), d.paintWidget.module.flip.flashFlip(!0, !1)) : w(function() {
                    j = !j
                }, function() {
                    d.paintWidget.setMode("flip");
                    d.paintWidget.module.flip.hflip(!0)
                });
                d.layoutNotify(AV.launchData.openType, "updateUndoRedo", [!0, !1])
            });
            avpw$("#avpw_flip_v").bind("click", function() {
                AV.featherUseFlash ? (d.paintWidget.setMode("flip"), d.paintWidget.module.flip.flashFlip(!1, !0)) : w(function() {
                    l = !l
                }, function() {
                    d.paintWidget.setMode("flip");
                    d.paintWidget.module.flip.vflip(!0)
                });
                d.layoutNotify(AV.launchData.openType, "updateUndoRedo", [!0, !1])
            });
            avpw$("#avpw_rotate_left").bind("click", function() {
                var b = !a || !j && !l || j && l ? -90 : 90;
                w(function() {
                    n += b
                }, function() {
                    d.paintWidget.setMode("rotate90");
                    d.paintWidget.module.rotate90.rotate90(b, g)
                });
                d.layoutNotify(AV.launchData.openType, "updateUndoRedo", [!0, !1])
            });
            avpw$("#avpw_rotate_right").bind("click", function() {
                var b = !a || !j && !l || j && l ? 90 : -90;
                w(function() {
                    n += b
                }, function() {
                    d.paintWidget.setMode("rotate90");
                    d.paintWidget.module.rotate90.rotate90(b, g)
                });
                d.layoutNotify(AV.launchData.openType, "updateUndoRedo", [!0, !1])
            });
            avpw$("#avpw_controlpanel_orientation").find(".avpw_inset_button").pressed("avpw_inset_button_down");
            avpw$("#avpw_all_effects").bind("click", C)
        },
        B = function() {
            avpw$("#avpw_controlpanel_orientation").find(".avpw_inset_button").unbind();
            avpw$("#avpw_all_effects").unbind("click", C)
        };
    o.init =

        function(c) {
            d = c;
            f = avpw$(d.paintWidget.canvas);
            a = !AV.featherUseFlash && AV.support.getVendorProperty("transformStyle");
            b = AV.support.getVendorProperty("transform");
            q = avpw$(AV.template[AV.launchData.layout].straightenSlider());
            g = !0
        };
    o.panelWillOpen = function() {
        H();
        D();
        d.layoutNotify(AV.launchData.openType, "subscribeToResize", ["orientationZoom", u]);
        d.layoutNotify(AV.launchData.openType, "getEmbedElement").append(q);
        d.layoutNotify(AV.launchData.openType, "hideZoomButton")
    };
    o.panelDidOpen = function() {
        a && (f.addClass("avpw_ready_for_orient"), q.addClass("avpw_straighten_ui_animate"));
        u()
    };
    o.panelWillClose = function() {
        d.canvasUI && d.canvasUI.unsubscribe(o);
        B();
        s && (s.shutdown(), s = void 0);
        d.layoutNotify(AV.launchData.openType, "scaleCanvas");
        q.detach();
        d.layoutNotify(AV.launchData.openType, "unsubscribeToResize", ["orientationZoom"]);
        a ? (f.removeClass("avpw_ready_for_orient"), q.removeClass("avpw_straighten_ui_animate"), q.removeClass("avpw_straighten_ui_ready")) : b && q.removeClass("avpw_straighten_ui_ready");
        d.layoutNotify(AV.launchData.openType, "showZoomButton")
    };
    o.commit = function() {
        b && !AV.featherUseFlash && (E(), h && 0 !== h && (d.paintWidget.setMode("straighten"), d.paintWidget.module.straighten.straighten(h, k, g)))
    };
    o.shutdown = function() {
        B();
        f = d = null;
        s = void 0;
        q = null
    };
    o.onUndo = function() {
        if (n % 360 || j || l || h && 0 !== h) o.commit(), H();
        F()
    };
    o.onRedo = function() {
        F()
    };
    o.onEnableZoomMode = function() {
        d.canvasUI && d.canvasUI.unsubscribe(o);
        return !1
    };
    o.onDisableZoomMode = function() {
        d.canvasUI && d.canvasUI.subscribe(o)
    };
    return o
}();
AV.ControlsWidget.prototype.tool.crop = function() {
    var a, b = null,
        d, f, c, e = !1,
        h = {},
        k = function(a) {
            a = d[a];
            a.label = AV.getLocalizedString(a.label);
            return AV.template[AV.launchData.layout].cropPreset(a)
        },
        g = function(a) {
            a.parent().children().each(function(a, b) {
                var c = d[a];
                if (c) {
                    var g = c.label,
                        e = c.labeled,
                        h = avpw$(b).find(".avpw_label"),
                        f = h.html(),
                        j = c.resize ? "x" : ":";
                    e || (f === g ? (e = g.indexOf(j), -1 != j && (f = g.substr(0, e), g = g.substr(e + 1), f = parseInt(f, 10), g = parseInt(g, 10), f = g + j + f)) : f = g, h.html(f), c.flipped = !c.flipped)
                }
            })
        },
        j = function() {
            var c = avpw$(this),
                e = AV.util.findItemByKeyValueFromArray("label", c.data("crop"), d),
                f, h;
            b = null;
            e && (!e.labeled && (!e.strict && c.hasClass("avpw_preset_icon_active")) && g(c), e.flipped ? (c.addClass("avpw_crop_preset_flipped"), f = e.height, h = e.width) : (c.removeClass("avpw_crop_preset_flipped"), f = e.width, h = e.height), e.resize ? (b = e, f = a.paintWidget.a2c(f, h, !0), a.paintWidget.module.crop.setInitialSelectionTo(f.width, f.height)) : f && h && a.paintWidget.module.crop.setInitialSelectionByRatio(f / h), a.paintWidget.module.crop.forceAspect(e.constrain));
            c.siblings().removeClass("avpw_preset_icon_active");
            c.siblings().removeClass("avpw_crop_preset_flipped");
            c.addClass("avpw_preset_icon_active");
            return !1
        },
        l = function() {
            c && c.shutdown();
            d = a.populateCropPresets(f);
            var b = a.layoutNotify(AV.launchData.openType, "getDims").PRESET_CROP_ICON_WIDTH,
                g = a.layoutNotify(AV.launchData.openType, "getCropPresetsPerPage"),
                e = b * g,
                h = d.length,
                j = avpw$("#avpw_crop_presets_scroll_region");
            j.css({
                width: (h + g) * b + "px"
            });
            c = new AV.Pager({
                leftArrow: avpw$("#avpw_crop_presets_lftArrow"),
                rightArrow: avpw$("#avpw_crop_presets_rghtArrow"),
                itemCount: h,
                itemsPerPage: g,
                pageWidth: e,
                itemBuilder: k,
                pageContainer: j,
                fillRemainingSpace: AV.buildBlankCropPreset,
                centerContents: !0
            });
            c.changePage()
        };
    h.resetUI = function() {
        a.layoutNotify(AV.launchData.openType, "scaleCanvas");
        AV.util.nextFrame(function() {
            var a = avpw$("#avpw_crop_presets_scroll_region").find('.avpw_isa_preset_crop[data-crop="Custom"]');
            a.length ? a.trigger("click") : avpw$("#avpw_crop_presets_scroll_region").find(".avpw_isa_preset_crop").eq(0).trigger("click")
        })
    };
    h.init = function(b) {
        a = b;
        f = AV.launchData.cropPresets;
        "string" === typeof presetsFromConfig && (f = f.split(","));
        a.layoutNotify(AV.launchData.openType, "subscribeToResize", ["buildCropPresets", l])
    };
    h.shutdown = function() {
        c && (c.shutdown(), c = null);
        a && (a.paintWidget.module.crop.setMouseDownCallback(null), a.layoutNotify(AV.launchData.openType, "unsubscribeToResize", ["buildCropPresets"]));
        a = b = null
    };
    h.onRedoComplete = function() {
        h.resetUI()
    };
    h.onUndoComplete = function() {
        h.resetUI()
    };
    h.panelWillOpen = function() {
        b = null;
        e = !1;
        a.canvasUI && a.canvasUI.subscribe(h);
        avpw$("#avpw_crop_presets_scroll_region").delegate(".avpw_isa_preset_crop", "click", j);
        a.layoutNotify(AV.launchData.openType, "hideZoomButton")
    };
    h.panelDidOpen = function() {
        l();
        c && (c.setCurrentPage(0), c.changePage());
        h.resetUI()
    };
    h.panelWillClose = function() {
        a.canvasUI && a.canvasUI.unsubscribe(h);
        avpw$("#avpw_crop_presets_scroll_region").undelegate(".avpw_isa_preset_crop", "click");
        c && (c.shutdown(), c = null);
        a.layoutNotify(AV.launchData.openType, "showZoomButton")
    };
    h.mouseDownEvent = function(b) {
        a.paintWidget.module.crop.updateUIDown(b.canvasX, b.canvasY) && (a.canvasUI.setMouseCursor("move"), e = !0);
        return !1
    };
    h.mouseMoveEvent = function(b) {
        if (e) return a.paintWidget.module.crop.updateUIMove(b.canvasX, b.canvasY), !1
    };
    h.mouseUpEvent = function(b) {
        if (e) return a.canvasUI.setMouseCursor(), a.paintWidget.module.crop.apply(b.canvasX, b.canvasY), e = !1
    };
    h.commit = function() {
        a.paintWidget.module.crop.crop();
        a.paintWidget.module.crop.hideSelection();
        b && (a.paintWidget.setMode("resize"), a.paintWidget.module.resize.resize(b.width, b.height, !0), a.paintWidget.setMode("crop"))
    };
    h.onEnableZoomMode = function() {
        a.canvasUI && a.canvasUI.unsubscribe(h);
        return !1
    };
    h.onDisableZoomMode = function() {
        a.canvasUI && a.canvasUI.subscribe(h)
    };
    return h
}();
AV.ControlsWidget.prototype.tool.resize = function() {
    var a, b, d, f, c, e, h, k, g, j = !1,
        l = !1,
        n = {},
        i = function(b) {
            if (!0 === b || !1 === b) e = b;
            else {
                if (e && !l) return a.messager.show("avpw_resize_unlocked", !0), !1;
                e = !e
            }
            e ? (f.addClass("avpw_inset_button_active"), m()) : f.removeClass("avpw_inset_button_active")
        },
        p = function() {
            var c, g;
            AV.featherUseFlash || j ? (g = a.paintWidget.getScaledSize(), c = g.width, g = g.height) : (c = a.paintWidget.width, g = a.paintWidget.height);
            b.val(c);
            d.val(g);
            h = c;
            k = g
        },
        m = function() {
            var f, j, m;
            AV.util.nextFrame(function() {
                if (e) if ("width" === c) {
                    j = parseInt(b.val());
                    if (!j || isNaN(j)) j = a.paintWidget.width;
                    f = j / a.paintWidget.width;
                    m = a.paintWidget.height * f | 0;
                    1 > m && (m = 1);
                    d.val(m)
                } else {
                    m = parseInt(d.val());
                    if (!m || isNaN(m)) m = a.paintWidget.height;
                    f = m / a.paintWidget.height;
                    j = a.paintWidget.width * f | 0;
                    1 > j && (j = 1);
                    b.val(j)
                }
                var i = parseInt(b.val()),
                    o = parseInt(d.val());
                !isNaN(i) && (0 < i && i <= g) && (!isNaN(o) && 0 < o && o <= g) && (h = i, k = o)
            })
        },
        q = function() {
            avpw$(this).addClass("avpw_text_input_focused").select()
        },
        s = function() {
            avpw$(this).removeClass("avpw_text_input_focused")
        },
        o = function(a) {
            9 == a.keyCode || (27 == a.keyCode || 65 == a.keyCode && (!0 === a.ctrlKey || !0 === a.metaKey) || 35 <= a.keyCode && 39 >= a.keyCode) || ((48 > a.keyCode || 57 < a.keyCode) && (96 > a.keyCode || 105 < a.keyCode) && 46 !== a.keyCode && 8 !== a.keyCode ? a.preventDefault() : (c = a.data.currentProp, m()))
        };
    n.init = function(c) {
        a = c;
        j = a.imageSizeTracker.isUsingHiResDimensions(AV.launchData);
        g = AV.launchData.hiresMaxSize || AV.launchData.maxSize;
        b = b || avpw$("#avpw_resize_width");
        b.bind("keydown", {
            currentProp: "width"
        }, o).bind("focus", q).bind("blur", s);
        d = d || avpw$("#avpw_resize_height");
        d.bind("keydown", {
            currentProp: "height"
        }, o).bind("focus", q).bind("blur", s);
        f = f || avpw$("#avpw_constrain_prop");
        f.click(i);
        avpw$("#avpw_constrain_prop_label").click(function() {
            f.trigger("click")
        });
        avpw$("#avpw_resize_invalid_confirm").click(function() {
            a.messager.hide("avpw_resize_invalid");
            b.val(h);
            d.val(k);
            return !1
        });
        avpw$("#avpw_resize_unlocked_confirm").click(function() {
            l = !0;
            a.messager.hide("avpw_resize_unlocked");
            i();
            return !1
        });
        avpw$("#avpw_resize_unlocked_cancel").click(function() {
            l = !0;
            a.messager.hide("avpw_resize_unlocked");
            return !1
        });
        avpw$("#avpw_resize_invalid_max_size").html(g)
    };
    n.panelWillOpen = function() {
        p();
        i(!0);
        l = !1;
        a.layoutNotify(AV.launchData.openType, "hideZoomButton")
    };
    n.panelDidOpen = function() {
        b.trigger("focus");
        a.layoutNotify(AV.launchData.openType, "scaleCanvas")
    };
    n.panelWillClose = function() {
        a.layoutNotify(AV.launchData.openType, "showZoomButton")
    };
    n.onRedoComplete = function() {
        p()
    };
    n.onUndoComplete = function() {
        p()
    };
    n.commit = function() {
        var c = b.val(),
            g = d.val(),
            e = a.paintWidget.height;
        if (c !== a.paintWidget.width || g !== e) return c = parseInt(b.val()), g = parseInt(d.val()), c === h && g === k ? (!isNaN(c) && !isNaN(g) && a.paintWidget.module.resize.resize(c, g, !0), c = !0) : (a.messager.show("avpw_resize_invalid", !0), c = !1), c
    };
    n.shutdown = function() {
        b && b.unbind();
        d && d.unbind();
        f && f.unbind();
        avpw$("avpw_constrain_prop_label").unbind("click");
        avpw$("#avpw_resize_invalid_confirm").unbind("click");
        avpw$("#avpw_resize_unlocked_confirm").unbind("click");
        avpw$("#avpw_resize_unlocked_cancel").unbind("click");
        a = null
    };
    return n
}();
AV.ControlsWidget.prototype.tool.overlay = function() {
    var a, b, d, f, c, e, h = null,
        k = null,
        g, j, l, n = !1,
        i, p, m = {},
        q = function(a, b, c) {
            AV.util.loadFile(b, "js", function() {
                l[a] = AV.stickerPacks;
                AV.stickerPacks = null;
                c && "function" === typeof c && c.call(this)
            })
        },
        s = function() {
            e.hide();
            c.show();
            B();
            h && h.changePage(!0)
        },
        o = function(b) {
            var c = avpw$(g.getPackOfInterest()).children(".avpw_icon_waiter");
            a.waitThrobber.spin(c[0]);
            a.purchaseManager.hideAssetPurchasePopup();
            q(b.assetId, b.resourceUrl, function() {
                a.waitThrobber.stop();
                a.setPanelMode("overlay")
            })
        },
        r = function(b, c) {
            if (a.paintWidget.overlayRegistry.getElement(b, function() {
                f = !1;
                c && c.call(this)
            })) return !0;
            f = !0;
            return !1
        },
        u = function(b, c, d, g) {
            if (AV.featherUseFlash) a.paintWidget.module.overlay.newOverlay(b);
            else {
                var e = r(b, function() {
                    a.paintWidget.module.overlay.newOverlay(b, d, g, 0.375, 0.375);
                    e || a.onEggWaitThrobber.stop()
                });
                !e && c && (c = avpw$(c).children(".avpw_isa_overlay_waiter"), a.onEggWaitThrobber.spin(c[0]))
            }
        },
        t = function() {
            _dragOrigin = i = null;
            f = !1;
            p && p.length && p.detach().remove()
        },
        v = function(a) {
            if (p && p.length) {
                var b = AV.util.getTouch(a);
                b ? (x = b.pageX, y = b.pageY) : (x = a.originalEvent.pageX, y = a.originalEvent.pageY);
                p.css({
                    left: x + "px",
                    top: y + "px"
                })
            }
        },
        z = function(a) {
            var b, c, d;
            t();
            d = avpw$(a.currentTarget);
            i = d.attr("fullimageurl");
            AV.featherUseFlash || ((c = AV.util.getTouch(a)) ? (b = c.pageX, a = c.pageY) : (b = a.originalEvent.pageX, a = a.originalEvent.pageY), _dragOrigin = {
                x: b,
                y: a
            }, avpw$(window).bind("mousemove", A).bind("touchmove", A).bind("mouseup", w).bind("touchend", w), p = d.clone())
        },
        A = function(a) {
            var b, c, d = 0,
                g = 0;
            _dragOrigin && (d = _dragOrigin.x, g = _dragOrigin.y);
            (c = AV.util.getTouch(a)) ? (b = c.pageX, c = c.pageY, avpw$(AV.paintWidgetInstance.canvas).trigger("touchstart")) : (b = a.originalEvent.pageX, c = a.originalEvent.pageY);
            if (5 < d - b || 5 < b - d || 5 < g - c || 5 < c - g) avpw$(window).unbind("mousemove", A).unbind("touchmove", A).bind("mousemove", v).bind("touchmove", v), v(a), p.addClass("avpw_overlay_icon_dropped"), avpw$("body").append(p), r(i)
        },
        w = function(b) {
            avpw$(window).unbind("mousemove", A).unbind("touchmove", A).unbind("mousemove", v).unbind("touchmove", v).unbind("mouseup", w).unbind("touchend", w);
            var c = a.canvasUI.getCoordinatesFromEventWithinCanvasBounds(b);
            if (c) if (f) var d = window.setInterval(function() {
                f || (c && u(i, b.currentTarget, c.canvasX, c.canvasY), window.clearInterval(d), d = null, t())
            }, 100);
            else u(i, b.currentTarget, c.canvasX, c.canvasY), t();
            else p && p.length && (p.addClass("avpw_overlay_icon_return_ready"), AV.util.nextFrame(function() {
                p.addClass("avpw_overlay_icon_returned");
                _dragOrigin && p.css({
                    top: _dragOrigin.y + "px",
                    left: _dragOrigin.x + "px"
                });
                window.setTimeout(function() {
                    t()
                }, 400)
            }))
        },
        H = function(b) {
            var c = b.currentTarget,
                d = avpw$(c).attr("fullimageurl"),
                g, e;
            if (d && d === i) {
                if (a.canvasUI && (b = a.canvasUI.getViewportCenter())) g = b.canvasX, e = b.canvasY;
                if (f) var h = window.setInterval(function() {
                    f || (u(d, c, g, e), window.clearInterval(h), h = null)
                }, 100);
                else u(d, c, g, e)
            }
            i = _dragOrigin = null;
            avpw$(window).unbind("mousemove", A).unbind("touchmove", A).unbind("mousemove", v).unbind("touchmove", v)
        },
        F = function(c) {
            if (b) {
                var d = b[c][0],
                    g = b[c][1];
                a.paintWidget.overlayRegistry.add(d, b[c][2]);
                return AV.template[AV.launchData.layout].stickerThumbnail({
                    url: d,
                    thumburl: g
                })
            }
        },
        E = function(a) {
            var a = j[a],
                b = a.assetId;
            return (0, AV.template[AV.launchData.layout][a.purchased || !a.needsPurchase ? "stickerRoll" : "stickerRollDisabled"])({
                label: AV.getLocalizedString(a.displayName),
                id: b,
                thumburl: AV.build.feather_baseURL + "images/stickerpacks/" + b + ".png",
                tagurl: AV.build.feather_baseURL + "images/stickerpacks/purchaseable.png"
            })
        },
        G = function() {
            if (b) {
                h && h.shutdown();
                var c = b.length,
                    d = a.layoutNotify(AV.launchData.openType, "getDims"),
                    g = d.STICKER_ICON_WIDTH,
                    e = a.layoutNotify(AV.launchData.openType, "getStickersPerPage"),
                    f = g * e;
                avpw$("#avpw_overlay_images_scroll_region").css({
                    width: c * g + "px",
                    paddingRight: f + "px"
                });
                h = new AV.Pager({
                    leftArrow: avpw$("#avpw_overlay_lftArrow"),
                    rightArrow: avpw$("#avpw_overlay_rghtArrow"),
                    itemCount: c,
                    itemsPerPage: e,
                    pageWidth: f,
                    itemBuilder: F,
                    pageContainer: avpw$("#avpw_overlay_images_scroll_region"),
                    firstPageTemplate: AV.template[AV.launchData.layout].stickerLeadIn,
                    firstPageWidth: d.STICKER_LEAD_IN_WIDTH
                });
                h.changePage()
            }
        },
        C = function() {
            h && (h.shutdown(), h = null);
            k && (k.shutdown(), k = null);
            g && g.updateCancelButton();
            c && c.length && c.hide();
            e && e.length && e.show();
            p = i = null
        },
        D = function() {
            if (!d) {
                d = !0;
                if (0 < j.length) {
                    k && k.shutdown();
                    var b = a.layoutNotify(AV.launchData.openType, "getDims").STICKER_ROLL_WIDTH,
                        c = a.layoutNotify(AV.launchData.openType, "getStickerPacksPerPage"),
                        g = b * c;
                    e.css({
                        width: j.length * b + "px",
                        paddingRight: g + "px"
                    });
                    k = new AV.Pager({
                        leftArrow: avpw$("#avpw_overlay_lftArrow"),
                        rightArrow: avpw$("#avpw_overlay_rghtArrow"),
                        itemCount: j.length,
                        itemsPerPage: c,
                        pageWidth: g,
                        itemBuilder: E,
                        pageContainer: e
                    });
                    k.changePage()
                }
                d = !1
            }
        },
        B = function() {
            b = l[g.getCurrentPack()];
            d || (d = !0, 0 < b.length ? G() : c.html(AV.getLocalizedString("No stickers defined in Feather_Stickers")), d = !1)
        };
    m.init = function(b) {
        a = b;
        l = {};
        e = avpw$("#avpw_overlay_pack_scroll_region");
        c = avpw$("#avpw_overlay_images_scroll_region");
        g = new AV.PacksAndItems({
            currentPack: "original_stickers",
            browser: c,
            packBrowser: e,
            pager: h,
            packPager: k,
            buildPacksHTML: D,
            buildPackItemsHTML: B
        });
        g.onPurchaseButtonClicked = o;
        e.delegate(".avpw_isa_control_selector_stickerpack", "click", g.onPackClicked).delegate(".avpw_isa_control_selector_stickerinfo", "click", g.onPackInfoClicked);
        c.delegate(".avpw_isa_control_selector_overlay", "mousedown touchstart", z).delegate(".avpw_isa_control_selector_overlay", "mouseup", H);
        a.layoutNotify(AV.launchData.openType, "subscribeToResize", ["buildStickerPresets", G])
    };
    m.panelWillOpen = function() {
        n = !1;
        a.canvasUI && a.canvasUI.subscribe(m);
        C();
        g.setCurrentPack();
        a.purchaseManager.getPurchasedAssets("STICKER", function(a) {
            j = a;
            var b, c = !1;
            1 === j.length ? l[j[0].assetId] ? s() : b = function() {
                c || (s(), c = !0)
            } : (D(), k && k.changePage(!0));
            for (a = 0; a < j.length; a++) {
                var d = j[a].assetId,
                    g = j[a].resourceUrl;
                !l[d] && g && q(d, g, b)
            }
        })
    };
    m.panelWillClose = function() {
        a.canvasUI && a.canvasUI.unsubscribe(m);
        g.updateCancelButton();
        t()
    };
    m.shutdown = function() {
        C();
        t();
        e && e.length && e.undelegate();
        c && c.length && c.undelegate();
        a && a.layoutNotify(AV.launchData.openType, "unsubscribeToResize", ["buildStickerPresets"]);
        g = l = c = e = a = null
    };
    m.mouseDownEvent = function(b) {
        if (!n) {
            var c = a.paintWidget.module.overlay.updateUIDown(b.canvasX, b.canvasY);
            !1 !== c && (n = !0, "rotate" === c ? a.canvasUI.setMouseCursor("pointer") : "translate" === c && a.canvasUI.setMouseCursor("move"), m.mouseMoveEvent(b))
        }
    };
    m.mouseMoveEvent = function(b) {
        if (n) return a.paintWidget.module.overlay.updateUIMove(b.canvasX, b.canvasY), !1
    };
    m.mouseUpEvent = function(b) {
        n && (a.canvasUI.setMouseCursor(), a.paintWidget.module.overlay.apply(b.canvasX, b.canvasY), n = !1)
    };
    m.onEnableZoomMode = function() {
        a.canvasUI && a.canvasUI.unsubscribe(m);
        return !1
    };
    m.onDisableZoomMode = function() {
        a.canvasUI && a.canvasUI.subscribe(m)
    };
    return m
}();
AV.ControlsWidget.prototype.tool.text = function() {
    var a, b, d = AV.toolDefaults.text.presetsColor,
        f, c, e = !1,
        h, k = !1,
        g = {},
        j = function(a) {
            return AV.template[AV.launchData.layout].brushColorIcon({
                color: d[a]
            })
        },
        l = function(a) {
            a.siblings().removeClass("avpw_preset_icon_active");
            a.addClass("avpw_preset_icon_active")
        },
        n = function(a) {
            var c;
            c = a && AV.util.color_is_light(a) ? "#000" : "#fff";
            AV.util.color_is_light(a);
            b = a;
            f = c
        },
        i = function(a) {
            a.preventDefault();
            a = avpw$(this);
            n(a.data("color"));
            l(a);
            e = !1
        },
        p = function(b) {
            b.preventDefault();
            b.stopPropagation();
            var c = b.currentTarget;
            AV.buildColorPicker(a.paintWidget.module.drawing.color(), c, function(b) {
                n(b);
                a.canvasUI && a.canvasUI.subscribe(g);
                l(avpw$(c))
            }, function() {
                a.canvasUI && a.canvasUI.subscribe(g)
            });
            a.canvasUI && a.canvasUI.unsubscribe(g)
        },
        m = function() {
            if (!e) {
                var c, d, g, h = avpw$("#avpw_text_font_size").val(),
                    j = b,
                    m = f,
                    i = avpw$("#avpw_text_font").val(),
                    o = avpw$("#avpw_text_area").val();
                if (a.canvasUI && (c = a.canvasUI.getViewportCenter())) d = c.canvasX, g = c.canvasY;
                "" == h && (h = avpw$("#avpw_text_font_size").attr("value"));
                "" == h && (h = "60");
                "" == i && (i = avpw$("#avpw_text_font").attr("value"));
                "" == i && (i = "sans-serif");
                "sans-serif" == i && (i = '"Arial", sans-serif');
                c = parseFloat(h) / 60;
                a.paintWidget.module.text.newText(o, i, 60, j, m, d, g, c, c, 0);
                e = !0;
                avpw$("#avpw_text_area").trigger("blur")
            }
            return !1
        },
        q = function() {
            if (c) {
                var a = avpw$(this);
                a[0].value = "";
                c = !1;
                a.addClass("avpw_text_input_focused")
            }
            e = !1
        },
        s = function(a) {
            if (13 == a.which) return avpw$("#avpw_add_text").trigger("click"), !1
        },
        o = function() {
            h && h.shutdown();
            var b = a.layoutNotify(AV.launchData.openType, "getDims"),
                c = b.PRESET_ICON_WIDTH,
                g = a.layoutNotify(AV.launchData.openType, "getPresetsPerPage"),
                e = c * g,
                f = d.length,
                m = avpw$("#avpw_text_colors_scroll_region");
            m.css({
                width: (f + g + 1) * c + "px"
            });
            h = new AV.Pager({
                leftArrow: avpw$("#avpw_text_colors_lftArrow"),
                rightArrow: avpw$("#avpw_text_colors_rghtArrow"),
                itemCount: f,
                itemsPerPage: g,
                pageWidth: e,
                itemBuilder: j,
                pageContainer: m,
                firstPageTemplate: AV.template[AV.launchData.layout].colorPickerIcon,
                fillRemainingSpace: AV.buildBlankPreset,
                centerContents: !0
            });
            h.changePage();
            avpw$("#avpw_text_area").css("width", b.INNER_BROWSER_WIDTH - b.TEXT_ADD_TEXT_BUTTON_WIDTH + "px")
        };
    g.init = function(b) {
        a = b;
        o();
        a.layoutNotify(AV.launchData.openType, "subscribeToResize", ["buildTextPresets", o])
    };
    g.panelWillOpen = function() {
        e = k = !1;
        var b = avpw$("#avpw_text_area");
        b[0].value = AV.getLocalizedString("Enter text here");
        c = !0;
        b.removeClass("avpw_text_input_focused");
        h && (h.setCurrentPage(0), h.changePage());
        avpw$("#avpw_text_colors_scroll_region").delegate(".avpw_isa_preset_color", "click", i).delegate(".avpw_isa_color_picker", "click", p);
        avpw$("#avpw_text_area").bind("focus", q).bind("keydown", s);
        avpw$("#avpw_add_text").click(m);
        a.canvasUI && a.canvasUI.subscribe(g);
        avpw$.miniColors && (AV.miniColors = new avpw$.miniColors)
    };
    g.panelDidOpen = function() {
        avpw$("#avpw_text_colors_scroll_region").find(".avpw_isa_preset_color").eq(0).trigger("click")
    };
    g.panelWillClose = function() {
        avpw$("#avpw_text_colors_scroll_region").undelegate(".avpw_isa_preset_color", "click").undelegate(".avpw_isa_color_picker", "click");
        avpw$("#avpw_text_area").unbind();
        avpw$("#avpw_add_text").unbind("click");
        a.canvasUI && a.canvasUI.unsubscribe(g);
        avpw$("#avpw_text_area").trigger("blur");
        AV.miniColors = null
    };
    g.shutdown = function() {
        h && (h.shutdown(), h = null);
        a && a.layoutNotify(AV.launchData.openType, "unsubscribeToResize", ["buildTextPresets"]);
        a = null
    };
    g.mouseDownEvent = function(b) {
        if (!k) return b = a.paintWidget.module.text.updateUIDown(b.canvasX, b.canvasY), !1 !== b && (k = !0, "rotate" === b ? a.canvasUI.setMouseCursor("pointer") : "translate" === b && a.canvasUI.setMouseCursor("move")), !1
    };
    g.mouseMoveEvent = function(b) {
        if (k) return a.paintWidget.module.text.updateUIMove(b.canvasX, b.canvasY), !1
    };
    g.mouseUpEvent = function(b) {
        k && (e = !1, a.canvasUI.setMouseCursor(), a.paintWidget.module.text.apply(b.canvasX, b.canvasY), k = !1)
    };
    g.onEnableZoomMode = function() {
        a.canvasUI && a.canvasUI.unsubscribe(g);
        return !1
    };
    g.onDisableZoomMode = function() {
        a.canvasUI && a.canvasUI.subscribe(g)
    };
    return g
}();
AV.ControlsWidget.prototype.tool.effects = function() {
    var a = AV.toolDefaults.effects.optionalFrame,
        b, d, f, c, e, h, k, g, j, l, n, i, p = null,
        m = null,
        q, s, o, r = {},
        u = function(a) {
            avpw$(".avpw_isa_control_selector_filter").removeClass("avpw_preset_icon_active");
            a.addClass("avpw_preset_icon_active")
        },
        t = function() {
            var a, b;
            o && (a = o.data("filtername"), b = o.children(".avpw_icon_waiter"), k.waitThrobber.spin(b[0]), AV.util.nextFrame(function() {
                AV.onRunFilter ? AV.onRunFilter.apply(this, [a, f, c]) : k.paintWidget.module.effects.applyPreview(a, {
                    seed: h,
                    intensity: f,
                    border: c
                }, !0);
                k.waitThrobber.stop()
            }))
        },
        v = function(a, b) {
            f = b.value;
            t()
        },
        z = function() {
            var a = avpw$("#avpw_effects_frame_toggle");
            (c = !c) ? a.addClass("avpw_inset_button_active") : a.removeClass("avpw_inset_button_active");
            t()
        },
        A = function(a) {
            a = avpw$(a.currentTarget);
            id = a.data("filtername");
            "original" === id ? (r.cancel(), I()) : (h = Math.floor(4294967295 * Math.random()), o = a, t(), e = a.data("frame"), AV.featherUseFlash || avpw$("#avpw_controlpanel_effects").find(".avpw_advanced_splitter_control").fadeIn(100));
            u(a);
            return !1
        },
        w = function(a) {
            var b = avpw$(q.getPackOfInterest()).children(".avpw_icon_waiter");
            k.waitThrobber.spin(b[0]);
            k.purchaseManager.hideAssetPurchasePopup();
            k.paintWidget.filterManager.loadPack(a.assetId, a.resourceUrl, function() {
                k.waitThrobber.stop();
                k.setPanelMode("effects")
            })
        },
        H = function(b) {
            if (g) {
                for (var c = g[b][0], d = g[b][1], e = a.length, f = !1, b = 0; b < e; b++) if (d === a[b]) {
                    f = !0;
                    break
                }
                return AV.template[AV.launchData.layout].filterThumbnail({
                    label: AV.getLocalizedString(c),
                    id: d,
                    thumburl: AV.build.feather_baseURL + "images/filters/" + d + ".jpg",
                    frame: f
                })
            }
        },
        F = function(a) {
            var a = s[a],
                b = a.assetId;
            return (0, AV.template[AV.launchData.layout][a.purchased || !a.needsPurchase ? "filterCanister" : "filterCanisterDisabled"])({
                label: AV.getLocalizedString(a.displayName),
                id: b,
                thumburl: AV.build.feather_baseURL + "images/filterpacks/" + b + ".png",
                tagurl: AV.build.feather_baseURL + "images/filterpacks/purchaseable.png"
            })
        },
        E = function() {
            if (g) {
                p && p.shutdown();
                var a = g.length,
                    b = k.layoutNotify(AV.launchData.openType, "getDims").FILTER_IMAGE_WIDTH,
                    c =
                        k.layoutNotify(AV.launchData.openType, "getFiltersPerPage"),
                    d = b * c;
                n.css({
                    width: a * b + "px",
                    paddingRight: d + "px"
                });
                p = new AV.Pager({
                    leftArrow: avpw$("#avpw_filter_lftArrow"),
                    rightArrow: avpw$("#avpw_filter_rghtArrow"),
                    itemCount: a,
                    itemsPerPage: c,
                    pageWidth: d,
                    itemBuilder: H,
                    pageContainer: n
                });
                p.changePage();
                r.resetUI();
                avpw$(".avpw_isa_control_selector_filter").eq(0).trigger("click")
            }
        },
        G = function() {
            p && (p.shutdown(), p = null);
            m && (m.shutdown(), m = null);
            q && q.updateCancelButton();
            n && n.length && n.hide();
            i && i.length && i.show()
        },
        C = function() {
            if (!j) {
                j = !0;
                if (0 < s.length) {
                    m && m.shutdown();
                    var a = k.layoutNotify(AV.launchData.openType, "getDims").FILTER_CANISTER_WIDTH,
                        b = k.layoutNotify(AV.launchData.openType, "getFilterPacksPerPage"),
                        c = a * b;
                    i.css({
                        width: s.length * a + "px",
                        paddingRight: c + "px"
                    });
                    m = new AV.Pager({
                        leftArrow: avpw$("#avpw_filter_lftArrow"),
                        rightArrow: avpw$("#avpw_filter_rghtArrow"),
                        itemCount: s.length,
                        itemsPerPage: b,
                        pageWidth: c,
                        itemBuilder: F,
                        pageContainer: i
                    });
                    m.changePage()
                }
                j = !1
            }
        },
        D = function() {
            var a;
            a = (a = AV.filtersInOrder ? AV.filtersInOrder : k.paintWidget.filterManager.getClickableFiltersForPack(q.getCurrentPack())) ? a.slice(0) : [];
            a.unshift(["Original", "original"]);
            g = a;
            j || (j = !0, 0 < g.length ? E() : n.html(AV.getLocalizedString("No effects found for this pack")), j = !1)
        },
        B = function() {
            l = !1;
            avpw$("#avpw_controlpanel_effects").removeClass("avpw_advanced_mode")
        },
        K = function() {
            if (l) AV.usageTracker.submit("closeadvancedtools", {
                toolName: "effects"
            }), B();
            else {
                AV.usageTracker.submit("openadvancedtools", {
                    toolName: "effects"
                });
                var a, b = avpw$("#avpw_controlpanel_effects");
                e ? (b.addClass("avpw_effect_with_frame"), a = avpw$("#avpw_effects_frame_toggle"), c ? a.addClass("avpw_inset_button_active") : a.removeClass("avpw_inset_button_active")) : b.removeClass("avpw_effect_with_frame");
                b.addClass("avpw_advanced_mode");
                l = !0;
                avpw$("#avpw_controlpanel_effects").addClass("avpw_advanced_mode")
            }
            return !1
        },
        I = function() {
            var a = avpw$("#avpw_controlpanel_effects").find(".avpw_advanced_splitter_control");
            a.is(":visible") ? a.fadeOut(100) : a.css("display", "none")
        };
    r.resetUI = function() {
        avpw$(".avpw_isa_control_selector_filter").removeClass("avpw_preset_icon_active");
        avpw$(".avpw_isa_control_selector_filter").eq(0).addClass("avpw_preset_icon_active");
        b.reset();
        B();
        I()
    };
    r.init = function(a) {
        k = a;
        f = 1;
        c = !0;
        i = avpw$("#avpw_filter_pack_scroll_region");
        n = avpw$("#avpw_filter_body_scroll_region");
        q = new AV.PacksAndItems({
            currentPack: "original_effects",
            browser: n,
            packBrowser: i,
            pager: p,
            packPager: m,
            buildPacksHTML: C,
            buildPackItemsHTML: D
        });
        q.onBackButtonClicked = r.cancel;
        q.onPurchaseButtonClicked = w;
        i.delegate(".avpw_isa_control_selector_filterpack", "click", q.onPackClicked).delegate(".avpw_isa_control_selector_filterinfo", "click", q.onPackInfoClicked);
        n.delegate(".avpw_isa_control_selector_filter", "click", A);
        avpw$("#avpw_controlpanel_effects").delegate(".avpw_effects_frame_container", "click", z);
        avpw$("#avpw_controlpanel_effects").delegate(".avpw_advanced_splitter_control", "click", K);
        d = 1;
        b = new k.Slider({
            element: document.getElementById("avpw_effects_slider"),
            min: 0,
            max: d,
            defaultValue: d,
            delay: 400,
            onchange: v,
            onslide: v
        })
    };
    r.panelWillOpen = function() {
        G();
        q.setCurrentPack();
        k.purchaseManager.getPurchasedAssets("EFFECT", function(a) {
            s =
                a;
            for (a = 0; a < s.length; a++) if ("enhance" === s[a].assetId) {
                s.splice(a, 1);
                break
            }
            1 === s.length ? (i.hide(), n.show(), D()) : C();
            m && m.changePage(!0);
            p && p.changePage(!0)
        });
        o = null;
        _lastValue = d;
        e = void 0;
        k.layoutNotify(AV.launchData.openType, "subscribeToResize", ["buildEffectsPresets", E]);
        I()
    };
    r.panelDidOpen = function() {
        b.addListeners()
    };
    r.panelWillClose = function() {
        q.updateCancelButton();
        b.removeListeners();
        k.layoutNotify(AV.launchData.openType, "unsubscribeToResize", ["buildEffectsPresets"])
    };
    r.panelDidClose = function() {
        B()
    };
    r.cancel = function() {
        r.resetUI();
        k.paintWidget.actions.isACheckpoint && !k.paintWidget.actions.isACheckpoint() && k.paintWidget.actions.undoToCheckpoint()
    };
    r.onUndo = function(a) {
        a || r.resetUI()
    };
    r.onRedo = function(a) {
        a || (o && o.length && u(o), b && b.setValue(_lastValue))
    };
    r.shutdown = function() {
        G();
        b && (b.shutdown(), b = void 0);
        i && i.length && i.undelegate(".avpw_isa_control_selector_filterpack", "click").undelegate(".avpw_isa_control_selector_filterinfo", "click");
        n && n.length && n.undelegate(".avpw_isa_control_selector_filter", "click");
        avpw$("#avpw_controlpanel_effects").undelegate(".avpw_advanced_checkbox", "click");
        avpw$("#avpw_controlpanel_effects").undelegate(".avpw_advanced_splitter_control", "click");
        k && k.layoutNotify(AV.launchData.openType, "unsubscribeToResize", ["buildEffectsPresets"]);
        q = n = i = k = null
    };
    return r
}();
AV.ControlsWidget.prototype.tool.enhance = function() {
    var a, b, d = AV.toolDefaults.enhance.presets,
        f = {},
        c = function(a) {
            a.siblings().removeClass("avpw_inset_button_down");
            a.addClass("avpw_inset_button_down")
        },
        e = function() {
            var e, k;
            k = avpw$(this);
            k.hasClass("avpw_inset_button_down") ? (k.removeClass("avpw_inset_button_down"), f.cancel()) : (c(k), e = d[k.index()], a.showWaitThrobber(!0, function() {
                a.paintWidget.module.effects.applyPreview(e, [], true);
                a.showWaitThrobber(false)
            }), b = k);
            return !1
        };
    f.resetUI = function() {
        avpw$("#avpw_controlpanel_enhance").find(".avpw_inset_button").removeClass("avpw_inset_button_down")
    };
    f.init = function(b) {
        a = b
    };
    f.panelWillOpen = function() {
        a.paintWidget.module.effects.activate(a.paintWidget);
        b = null
    };
    f.panelDidOpen = function() {
        avpw$("#avpw_controlpanel_enhance").find(".avpw_inset_button").bind("click", e)
    };
    f.panelWillClose = function() {
        avpw$("#avpw_controlpanel_enhance").find(".avpw_inset_button").unbind("click").removeClass("avpw_inset_button_down");
        a.paintWidget.module.effects.deactivate()
    };
    f.cancel = function() {
        f.resetUI();
        a.paintWidget.actions.isACheckpoint() || a.paintWidget.actions.undoToCheckpoint()
    };
    f.onUndo = function(a) {
        a || f.resetUI()
    };
    f.onRedo = function(a) {
        !a && (b && b.length) && c(b)
    };
    f.shutdown = function() {
        a = null
    };
    return f
}();
AV.ControlsWidget.prototype.tool.drawing = function() {
    var a, b, d, f = AV.toolDefaults.drawing.presetsWidth,
        c = AV.toolDefaults.drawing.presetsColor,
        e, h = {},
        k = function() {
            var b = 1;
            a.canvasUI && (b = a.canvasUI.viewport.getRatio());
            var c = a.paintWidget.module.drawing.erase(),
                d = a.paintWidget.module.drawing.width();
            a.brushPreview && a.brushPreview.show(d / b / 2 + 0.5 | 0, !c ? a.paintWidget.module.drawing.color() : null)
        },
        g = function(a) {
            a.siblings().removeClass("avpw_preset_icon_active");
            a.addClass("avpw_preset_icon_active")
        },
        j = function(b) {
            b.preventDefault();
            var b = avpw$(this),
                c = 1;
            a.canvasUI && (c = a.canvasUI.viewport.getRatio());
            c = b.data("size") * c | 0;
            a.paintWidget.module.drawing.setWidth(c);
            g(b);
            k()
        },
        l = function(b) {
            a.paintWidget.module.drawing.setErase(!1);
            a.paintWidget.module.drawing.setColor(b)
        },
        n = function(a) {
            a.preventDefault();
            a = avpw$(this);
            l(a.data("color"));
            g(a);
            k()
        },
        i = function(b) {
            b.preventDefault();
            b.stopPropagation();
            var c = b.currentTarget;
            AV.buildColorPicker(a.paintWidget.module.drawing.color(), c, function(b) {
                l(b);
                k();
                a.canvasUI && a.canvasUI.subscribe(h);
                g(avpw$(c))
            }, function() {
                a.canvasUI && a.canvasUI.subscribe(h)
            });
            a.canvasUI && a.canvasUI.unsubscribe(h)
        },
        p = function(b) {
            b.preventDefault();
            a.paintWidget.module.drawing.setErase(!0);
            g(avpw$(this));
            k()
        },
        m = function(a) {
            return AV.template[AV.launchData.layout].brushIconSmall({
                size: f[a]
            })
        },
        q = function(a) {
            return AV.template[AV.launchData.layout].brushColorIcon({
                color: c[a]
            })
        },
        s = function() {
            b && b.shutdown();
            d && d.shutdown();
            var g = a.layoutNotify(AV.launchData.openType, "getDims").PRESET_ICON_WIDTH,
                e = a.layoutNotify(AV.launchData.openType, "getPresetsPerPage"),
                h = g * e,
                j = f.length,
                i = avpw$("#avpw_drawing_brushes_scroll_region"),
                k = c.length,
                l = avpw$("#avpw_drawing_colors_scroll_region");
            i.css({
                width: (j + e) * g + "px"
            });
            b = new AV.Pager({
                leftArrow: avpw$("#avpw_drawing_brushes_lftArrow"),
                rightArrow: avpw$("#avpw_drawing_brushes_rghtArrow"),
                itemCount: j,
                itemsPerPage: e,
                pageWidth: h,
                itemBuilder: m,
                pageContainer: i,
                fillRemainingSpace: AV.buildBlankPreset,
                centerContents: !0
            });
            b.changePage();
            l.css({
                width: (k + e + 3) * g + "px"
            });
            d = new AV.Pager({
                leftArrow: avpw$("#avpw_drawing_colors_lftArrow"),
                rightArrow: avpw$("#avpw_drawing_colors_rghtArrow"),
                itemCount: k,
                itemsPerPage: e,
                pageWidth: h,
                itemBuilder: q,
                pageContainer: l,
                firstPageTemplate: function(a) {
                    var b;
                    b = "" + AV.template[AV.launchData.layout].eraserIcon(a);
                    return b += AV.template[AV.launchData.layout].colorPickerIcon(a)
                },
                firstPageWidth: g,
                lastPageTemplate: AV.template[AV.launchData.layout].eraserIcon,
                lastPageWidth: g,
                fillRemainingSpace: AV.buildBlankPreset,
                centerContents: !0
            });
            d.changePage()
        };
    h.init = function(b) {
        a = b;
        s();
        a.layoutNotify(AV.launchData.openType, "subscribeToResize", ["buildDrawingPresets", s])
    };
    h.panelWillOpen = function() {
        AV.featherUseFlash || (a.paintWidget.setMode("flatten"), a.paintWidget.module.flatten.flatten());
        e = !1;
        b && (b.setCurrentPage(0), b.changePage());
        d && (d.setCurrentPage(0), d.changePage());
        avpw$("#avpw_drawing_brushes_scroll_region").delegate(".avpw_isa_preset_brush", "click", j);
        avpw$("#avpw_drawing_colors_scroll_region").delegate(".avpw_isa_preset_color", "click", n).delegate(".avpw_isa_color_picker", "click", i).delegate(".avpw_eraser_icon", "click", p);
        a.canvasUI && (a.canvasUI.subscribe(h), a.canvasUI.setMouseCursor("none"));
        avpw$.miniColors && (AV.miniColors = new avpw$.miniColors)
    };
    h.panelDidOpen = function() {
        avpw$("#avpw_drawing_brushes_scroll_region").find(".avpw_isa_preset_brush").eq(3).trigger("click");
        avpw$("#avpw_drawing_colors_scroll_region").find(".avpw_isa_preset_color").eq(0).trigger("click");
        a.paintWidget.setCurrentLayerByName("drawing")
    };
    h.panelWillClose = function() {
        avpw$("#avpw_drawing_brushes_scroll_region").undelegate(".avpw_isa_preset_brush", "click");
        avpw$("#avpw_drawing_colors_scroll_region").undelegate(".avpw_isa_preset_color", "click").undelegate(".avpw_isa_color_picker", "click").undelegate(".avpw_eraser_icon", "click");
        a.canvasUI && a.canvasUI.unsubscribe(h);
        a.paintWidget.uiLayerShow(!1);
        AV.miniColors = null
    };
    h.shutdown = function() {
        b && (b.shutdown(), b = null);
        d && (d.shutdown(), d = null);
        a && a.layoutNotify(AV.launchData.openType, "unsubscribeToResize", ["buildDrawingPresets"]);
        a = null
    };
    h.mouseDownEvent = function(b) {
        if (!e) return e = !0, a.paintWidget.module.drawing.updateUIDown(b.canvasX, b.canvasY), a.layoutNotify(AV.launchData.openType, "hideZoomButton"), !1
    };
    h.mouseMoveEvent = function(b) {
        if (e) return a.paintWidget.module.drawing.updateUIDraw(b.canvasX, b.canvasY), !1;
        a.paintWidget.module.drawing.updateUIMove(b.canvasX, b.canvasY)
    };
    h.mouseUpEvent = function(b) {
        e && (e = !1, a.paintWidget.module.drawing.apply(b.canvasX, b.canvasY), a.layoutNotify(AV.launchData.openType, "showZoomButton"))
    };
    h.mouseOutEvent = function() {
        a.paintWidget.uiLayerShow(!1)
    };
    h.onEnableZoomMode = function() {
        a.canvasUI && (a.canvasUI.unsubscribe(h), a.canvasUI.setMouseCursor());
        a.paintWidget.uiLayerShow(!1);
        return !1
    };
    h.onDisableZoomMode = function() {
        a.canvasUI && (a.canvasUI.subscribe(h), a.canvasUI.setMouseCursor("none"))
    };
    return h
}();
AV.ControlsWidget.prototype.tool.redeye = function() {
    var a, b, d = AV.toolDefaults.redeye.presets,
        f, c = {},
        e = function(b) {
            b.preventDefault();
            var b = avpw$(this),
                c = 1;
            a.canvasUI && (c = a.canvasUI.viewport.getRatio());
            c = b.data("size") * c / 2 | 0;
            a.paintWidget.module.redeye.setRadius(c);
            b.siblings().removeClass("avpw_preset_icon_active");
            b.addClass("avpw_preset_icon_active");
            b = 1;
            a.canvasUI && (b = a.canvasUI.viewport.getRatio());
            c = a.paintWidget.module.redeye.radius();
            a.brushPreview && a.brushPreview.show(c / b + 0.5 | 0)
        },
        h = function(a) {
            return AV.template[AV.launchData.layout].brushIconLarge({
                size: d[a]
            })
        },
        k = function() {
            b && b.shutdown();
            var c = a.layoutNotify(AV.launchData.openType, "getDims").PRESET_ICON_WIDTH,
                e = a.layoutNotify(AV.launchData.openType, "getPresetsPerPage"),
                f = c * e,
                k = d.length,
                i = avpw$("#avpw_redeye_brushes_scroll_region");
            i.css({
                width: (k + e) * c + "px"
            });
            b = new AV.Pager({
                leftArrow: avpw$("#avpw_redeye_brushes_lftArrow"),
                rightArrow: avpw$("#avpw_redeye_brushes_rghtArrow"),
                itemCount: k,
                itemsPerPage: e,
                pageWidth: f,
                itemBuilder: h,
                pageContainer: i,
                fillRemainingSpace: AV.buildBlankPreset,
                centerContents: !0
            });
            b.changePage()
        };
    c.init = function(b) {
        a = b;
        k();
        a.layoutNotify(AV.launchData.openType, "subscribeToResize", ["buildRedeyePresets", k])
    };
    c.panelWillOpen = function() {
        AV.featherUseFlash || (a.paintWidget.setMode("flatten"), a.paintWidget.module.flatten.flatten());
        b && (b.setCurrentPage(0), b.changePage());
        avpw$("#avpw_redeye_brushes_scroll_region").delegate(".avpw_isa_preset_brush", "click", e);
        a.canvasUI && a.canvasUI.subscribe(c);
        a.canvasUI && a.canvasUI.setMouseCursor("none")
    };
    c.panelDidOpen = function() {
        a.paintWidget.module.redeye.setMode("redeye");
        avpw$("#avpw_redeye_brushes_scroll_region").find(".avpw_isa_preset_brush").eq(3).trigger("click")
    };
    c.panelWillClose = function() {
        avpw$("#avpw_redeye_brushes_scroll_region").undelegate(".avpw_isa_preset_brush", "click");
        a.canvasUI && a.canvasUI.unsubscribe(c);
        a.paintWidget.uiLayerShow(!1)
    };
    c.shutdown = function() {
        a && a.layoutNotify(AV.launchData.openType, "unsubscribeToResize", ["buildRedeyePresets"]);
        a = null;
        b && (b.shutdown(), b = null)
    };
    c.mouseDownEvent = function(b) {
        if (!f) return f = !0, a.paintWidget.module.redeye.updateUIDown(b.canvasX, b.canvasY), !1
    };
    c.mouseMoveEvent = function(b) {
        if (f) return a.paintWidget.module.redeye.updateUIDraw(b.canvasX, b.canvasY), !1;
        a.paintWidget.module.redeye.updateUIMove(b.canvasX, b.canvasY)
    };
    c.mouseUpEvent = function(b) {
        f && (f = !1, a.paintWidget.module.redeye.apply(b.canvasX, b.canvasY))
    };
    c.mouseOutEvent = function() {
        a.paintWidget.uiLayerShow(!1)
    };
    c.onEnableZoomMode = function() {
        a.canvasUI && (a.canvasUI.unsubscribe(c), a.canvasUI.setMouseCursor());
        a.paintWidget.uiLayerShow(!1);
        return !1
    };
    c.onDisableZoomMode = function() {
        a.canvasUI && (a.canvasUI.subscribe(c), a.canvasUI.setMouseCursor("none"))
    };
    return c
}();
AV.ControlsWidget.prototype.tool.whiten = function() {
    var a, b, d = AV.toolDefaults.whiten.presets,
        f, c = {},
        e = function(b) {
            b.preventDefault();
            var b = avpw$(this),
                c = 1;
            a.canvasUI && (c = a.canvasUI.viewport.getRatio());
            c = b.data("size") * c / 2 | 0;
            a.paintWidget.module.whiten.setRadius(c);
            b.siblings().removeClass("avpw_preset_icon_active");
            b.addClass("avpw_preset_icon_active");
            b = 1;
            a.canvasUI && (b = a.canvasUI.viewport.getRatio());
            c = a.paintWidget.module.whiten.radius();
            a.brushPreview && a.brushPreview.show(c / b + 0.5 | 0)
        },
        h = function(a) {
            return AV.template[AV.launchData.layout].brushIconLarge({
                size: d[a]
            })
        },
        k = function() {
            b && b.shutdown();
            var c = a.layoutNotify(AV.launchData.openType, "getDims").PRESET_ICON_WIDTH,
                e = a.layoutNotify(AV.launchData.openType, "getPresetsPerPage"),
                f = c * e,
                k = d.length,
                i = avpw$("#avpw_whiten_brushes_scroll_region");
            i.css({
                width: (k + e) * c + "px"
            });
            b = new AV.Pager({
                leftArrow: avpw$("#avpw_whiten_brushes_lftArrow"),
                rightArrow: avpw$("#avpw_whiten_brushes_rghtArrow"),
                itemCount: k,
                itemsPerPage: e,
                pageWidth: f,
                itemBuilder: h,
                pageContainer: i,
                fillRemainingSpace: AV.buildBlankPreset,
                centerContents: !0
            });
            b.changePage()
        };
    c.init = function(b) {
        a = b;
        k();
        a.layoutNotify(AV.launchData.openType, "subscribeToResize", ["buildWhitenPresets", k])
    };
    c.panelWillOpen = function() {
        AV.featherUseFlash || (a.paintWidget.setMode("flatten"), a.paintWidget.module.flatten.flatten());
        b && (b.setCurrentPage(0), b.changePage());
        avpw$("#avpw_whiten_brushes_scroll_region").delegate(".avpw_isa_preset_brush", "click", e);
        a.canvasUI && a.canvasUI.subscribe(c);
        a.canvasUI && a.canvasUI.setMouseCursor("none")
    };
    c.panelDidOpen = function() {
        avpw$("#avpw_whiten_brushes_scroll_region").find(".avpw_isa_preset_brush").eq(3).trigger("click")
    };
    c.panelWillClose = function() {
        avpw$("#avpw_whiten_brushes_scroll_region").undelegate(".avpw_isa_preset_brush", "click");
        a.canvasUI && a.canvasUI.unsubscribe(c);
        a.paintWidget.uiLayerShow(!1)
    };
    c.shutdown = function() {
        a && a.layoutNotify(AV.launchData.openType, "unsubscribeToResize", ["buildWhitenPresets"]);
        a = null;
        b && (b.shutdown(), b = null)
    };
    c.mouseDownEvent = function(b) {
        if (!f) return f = !0, a.paintWidget.module.whiten.updateUIDown(b.canvasX, b.canvasY), !1
    };
    c.mouseMoveEvent = function(b) {
        if (f) return a.paintWidget.module.whiten.updateUIDraw(b.canvasX, b.canvasY), !1;
        a.paintWidget.module.whiten.updateUIMove(b.canvasX, b.canvasY)
    };
    c.mouseUpEvent = function(b) {
        f && (f = !1, a.paintWidget.module.whiten.apply(b.canvasX, b.canvasY))
    };
    c.mouseOutEvent = function() {
        a.paintWidget.uiLayerShow(!1)
    };
    c.onEnableZoomMode = function() {
        a.canvasUI && (a.canvasUI.unsubscribe(c), a.canvasUI.setMouseCursor());
        a.paintWidget.uiLayerShow(!1);
        return !1
    };
    c.onDisableZoomMode = function() {
        a.canvasUI && (a.canvasUI.subscribe(c), a.canvasUI.setMouseCursor("none"))
    };
    return c
}();
AV.ControlsWidget.prototype.tool.blemish = function() {
    var a, b, d = AV.toolDefaults.blemish.presets,
        f, c = {},
        e = function(b) {
            b.preventDefault();
            var b = avpw$(this),
                c = 1;
            a.canvasUI && (c = a.canvasUI.viewport.getRatio());
            c = b.data("size") * c / 2 | 0;
            a.paintWidget.module.blemish.setRadius(c);
            b.siblings().removeClass("avpw_preset_icon_active");
            b.addClass("avpw_preset_icon_active");
            b = 1;
            a.canvasUI && (b = a.canvasUI.viewport.getRatio());
            c = a.paintWidget.module.blemish.radius();
            a.brushPreview && a.brushPreview.show(c / b + 0.5 | 0)
        },
        h = function(a) {
            return AV.template[AV.launchData.layout].brushIconLarge({
                size: d[a]
            })
        },
        k = function() {
            b && b.shutdown();
            var c = a.layoutNotify(AV.launchData.openType, "getDims").PRESET_ICON_WIDTH,
                e = a.layoutNotify(AV.launchData.openType, "getPresetsPerPage"),
                f = c * e,
                k = d.length,
                i = avpw$("#avpw_blemish_brushes_scroll_region");
            i.css({
                width: (k + e) * c + "px"
            });
            b = new AV.Pager({
                leftArrow: avpw$("#avpw_blemish_brushes_lftArrow"),
                rightArrow: avpw$("#avpw_blemish_brushes_rghtArrow"),
                itemCount: k,
                itemsPerPage: e,
                pageWidth: f,
                itemBuilder: h,
                pageContainer: i,
                fillRemainingSpace: AV.buildBlankPreset,
                centerContents: !0
            });
            b.changePage()
        };
    c.init = function(b) {
        a = b;
        k();
        a.layoutNotify(AV.launchData.openType, "subscribeToResize", ["buildBlemishPresets", k])
    };
    c.panelWillOpen = function() {
        AV.featherUseFlash || (a.paintWidget.setMode("flatten"), a.paintWidget.module.flatten.flatten());
        b && (b.setCurrentPage(0), b.changePage());
        avpw$("#avpw_blemish_brushes_scroll_region").delegate(".avpw_isa_preset_brush", "click", e);
        a.canvasUI && a.canvasUI.subscribe(c);
        a.canvasUI && a.canvasUI.setMouseCursor("none")
    };
    c.panelDidOpen = function() {
        avpw$("#avpw_blemish_brushes_scroll_region").find(".avpw_isa_preset_brush").eq(3).trigger("click")
    };
    c.panelWillClose = function() {
        avpw$("#avpw_blemish_brushes_scroll_region").undelegate(".avpw_isa_preset_brush", "click");
        a.canvasUI && a.canvasUI.unsubscribe(c);
        a.paintWidget.uiLayerShow(!1)
    };
    c.shutdown = function() {
        a && a.layoutNotify(AV.launchData.openType, "unsubscribeToResize", ["buildBlemishPresets"]);
        a = null;
        b && (b.shutdown(), b = null)
    };
    c.mouseDownEvent = function(b) {
        if (!f) return f = !0, a.paintWidget.module.blemish.updateUIDown(b.canvasX, b.canvasY), !1
    };
    c.mouseMoveEvent = function(b) {
        if (f) return a.paintWidget.module.blemish.updateUIDraw(b.canvasX, b.canvasY), !1;
        a.paintWidget.module.blemish.updateUIMove(b.canvasX, b.canvasY)
    };
    c.mouseUpEvent = function(b) {
        f && (f = !1, a.paintWidget.module.blemish.apply(b.canvasX, b.canvasY))
    };
    c.mouseOutEvent = function() {
        a.paintWidget.uiLayerShow(!1)
    };
    c.onEnableZoomMode = function() {
        a.canvasUI && (a.canvasUI.unsubscribe(c), a.canvasUI.setMouseCursor());
        a.paintWidget.uiLayerShow(!1);
        return !1
    };
    c.onDisableZoomMode = function() {
        a.canvasUI && (a.canvasUI.subscribe(c), a.canvasUI.setMouseCursor("none"))
    };
    return c
}();
AV.ControlsWidget.prototype.tool.frames = function() {
    var a, b, d, f, c, e, h, k, g, j, l, n, i, p, m, q, s, o = {},
        r = function(a) {
            a.siblings().removeClass("avpw_preset_icon_active");
            a.addClass("avpw_preset_icon_active")
        },
        u = function(a) {
            a.siblings().removeClass("avpw_preset_icon_active");
            a.addClass("avpw_preset_icon_active")
        },
        t = function() {
            i && a.paintWidget.module.frames.applyPreview(i, {
                seed: q,
                color: m,
                size: p
            }, !0)
        },
        v = function(a) {
            var a = a.currentTarget,
                b = avpw$(a).data("framename");
            b ? (i = b, q = Math.floor(4294967295 * Math.random()), t(), s = b, AV.featherUseFlash || avpw$("#avpw_controlpanel_frames").find(".avpw_advanced_splitter_control").fadeIn(100)) : (o.cancel(), I());
            u(avpw$(a));
            return !1
        },
        z = function(a) {
            a = a.currentTarget;
            p = avpw$(a).data("thickness");
            t();
            r(avpw$(a))
        },
        A = function(a) {
            a = a.currentTarget;
            m = avpw$(a).data("color");
            AV.util.nextFrame(F);
            t();
            r(avpw$(a))
        },
        w = function(a) {
            a.preventDefault();
            var b = a.currentTarget;
            AV.buildColorPicker(m, b, function(a) {
                m = a;
                AV.util.nextFrame(F);
                t();
                r(avpw$(b))
            })
        },
        H = function(a) {
            if (j) {
                var b = j[a][1];
                return AV.template[AV.launchData.layout].frameThumbnail({
                    label: AV.getLocalizedString(j[a][0]),
                    id: b,
                    thumburl: AV.build.feather_baseURL + "images/filters/original.jpg"
                })
            }
        },
        F = function() {
            j && c.find(".avpw_isa_control_selector_frame canvas").each(function(b, c) {
                var d = j[b][1];
                d && a.paintWidget.module.frames.makeThumb(c, d, m)
            })
        },
        E = function(a) {
            return AV.template[AV.launchData.layout].frameThicknessIcon({
                thickness: k[a]
            })
        },
        G = function(a) {
            var b = g[a],
                c;
            n && a < n && (c = AV.template[AV.launchData.layout].magicColorIcon());
            return AV.template[AV.launchData.layout].brushColorIcon({
                color: b,
                extra: c
            })
        },
        C = function() {
            var m =
                    a.layoutNotify(AV.launchData.openType, "getDims"),
                i, l, q;
            j = i = (i = a.paintWidget.filterManager.getClickableFiltersForPack("borders")) ? i.slice(0) : [];
            j.unshift(["None"]);
            var o = j.length;
            i = m.FILTER_IMAGE_WIDTH;
            l = a.layoutNotify(AV.launchData.openType, "getFiltersPerPage");
            q = i * l;
            c.css({
                width: o * i + "px",
                paddingRight: q + "px"
            });
            b = new AV.Pager({
                leftArrow: avpw$("#avpw_frames_lftArrow"),
                rightArrow: avpw$("#avpw_frames_rghtArrow"),
                itemCount: o,
                itemsPerPage: l,
                pageWidth: q,
                itemBuilder: H,
                pageContainer: c
            });
            b.changePage();
            o = k.length;
            i = m.PRESET_ICON_WIDTH;
            l = a.layoutNotify(AV.launchData.openType, "getPresetsPerPage");
            q = i * l;
            e.css({
                width: o * i + "px"
            });
            d = new AV.Pager({
                leftArrow: avpw$("#avpw_frames_thickness_lftArrow"),
                rightArrow: avpw$("#avpw_frames_thickness_rghtArrow"),
                itemCount: o,
                itemsPerPage: l,
                pageWidth: q,
                itemBuilder: E,
                pageContainer: e,
                fillRemainingSpace: AV.buildBlankPreset,
                centerContents: !0
            });
            d.changePage();
            e.find(".avpw_isa_preset_thickness").eq(3).trigger("click");
            if ((i = a.paintWidget.getImageColors()) && i.length) n = i.length, g = AV.colorChoices.slice(0), g.unshift.apply(g, i);
            o = g.length;
            i = m.PRESET_ICON_WIDTH;
            l = a.layoutNotify(AV.launchData.openType, "getPresetsPerPage");
            q = i * l;
            h.css({
                width: (o + 1) * i + "px"
            });
            f = new AV.Pager({
                leftArrow: avpw$("#avpw_frames_colors_lftArrow"),
                rightArrow: avpw$("#avpw_frames_colors_rghtArrow"),
                itemCount: o,
                itemsPerPage: l,
                pageWidth: q,
                itemBuilder: G,
                firstPageTemplate: AV.template[AV.launchData.layout].colorPickerIcon,
                pageContainer: h,
                fillRemainingSpace: AV.buildBlankPreset,
                centerContents: !0
            });
            f.changePage();
            h.find("[data-color=#ffffff]").eq(0).trigger("click");
            s ? c.find('[data-framename="' + s + '"]').trigger("click") : avpw$(".avpw_isa_control_selector_frame").eq(0).trigger("click")
        },
        D = function() {
            b && (b.shutdown(), b = null);
            d && (d.shutdown(), d = null);
            f && (f.shutdown(), f = null);
            i = m = p = null
        },
        B = function() {
            l = !1;
            avpw$("#avpw_controlpanel_frames").removeClass("avpw_advanced_mode")
        },
        K = function() {
            l ? (AV.usageTracker.submit("closeadvancedtools", {
                toolName: "frames"
            }), B()) : (AV.usageTracker.submit("openadvancedtools", {
                toolName: "frames"
            }), l = !0, avpw$("#avpw_controlpanel_frames").addClass("avpw_advanced_mode"));
            return !1
        };
    o.init = function(b) {
        a = b;
        k = AV.toolDefaults.frames.presetsThickness;
        _presetsSize = AV.toolDefaults.frames.presetsSize;
        g = AV.colorChoices.slice(0);
        c = avpw$("#avpw_frames_body_scroll_region");
        e = avpw$("#avpw_frames_thickness_scroll_region");
        h = avpw$("#avpw_frames_colors_scroll_region");
        c.delegate(".avpw_isa_control_selector_frame", "click", v);
        e.delegate(".avpw_isa_preset_thickness", "click", z);
        h.delegate(".avpw_isa_preset_color", "click", A).delegate(".avpw_isa_color_picker", "click", w);
        avpw$("#avpw_controlpanel_frames").delegate(".avpw_advanced_splitter_control", "click", K)
    };
    var I = function() {
        var a = avpw$("#avpw_controlpanel_frames").find(".avpw_advanced_splitter_control");
        a.is(":visible") ? a.fadeOut(100) : a.css("display", "none")
    };
    o.resetUI = function() {
        u(avpw$(".avpw_isa_control_selector_frame").eq(0));
        I()
    };
    o.panelWillOpen = function() {
        n = s = void 0;
        D();
        avpw$.miniColors && (AV.miniColors = new avpw$.miniColors);
        I()
    };
    o.panelDidOpen = function() {
        C();
        a.layoutNotify(AV.launchData.openType, "subscribeToResize", ["buildframesPresets", C]);
        b && b.changePage(!0)
    };
    o.panelWillClose = function() {
        a && a.layoutNotify(AV.launchData.openType, "unsubscribeToResize", ["buildframesPresets"]);
        AV.miniColors = null
    };
    o.panelDidClose = function() {
        B()
    };
    o.cancel = function() {
        o.resetUI();
        a.paintWidget.actions.isACheckpoint() || a.paintWidget.actions.undoToCheckpoint()
    };
    o.onUndo = function(a) {
        a || o.resetUI()
    };
    o.onRedo = function(a) {
        !a && (c && s) && u(c.find('[data-framename="' + s + '"]'))
    };
    o.shutdown = function() {
        D();
        c && c.length && c.undelegate(".avpw_isa_control_selector_frame", "click");
        e && e.length && e.undelegate(".avpw_isa_preset_thickness", "click");
        h && h.length && h.undelegate(".avpw_isa_preset_color", "click").undelegate(".avpw_isa_color_picker", "click");
        avpw$("#avpw_controlpanel_frames").undelegate(".avpw_advanced_splitter_control", "click");
        a && a.layoutNotify(AV.launchData.openType, "unsubscribeToResize", ["buildframesPresets"]);
        k = g = _presetsSize = h = e = c = a = null
    };
    return o
}();
AV.ControlsWidget.prototype.layout.aviary = function() {
    var a = "fullscreen",
        b = {
            HEADER_HEIGHT: 108,
            FOOTER_HEIGHT: 35,
            MINIMUM_WIDGET_HEIGHT: 396,
            MODE_ACTION_WIDTH: 118,
            BOOKEND_WIDTH: 48,
            INNER_BOOKEND_WIDTH: 40,
            CANVAS_MARGIN: 12,
            CANVAS_PADDING: 15,
            MINIMUM_WIDGET_WIDTH: 712,
            TOOL_ICON_WIDTH: 68,
            PRESET_ICON_WIDTH: 54,
            PRESET_CROP_ICON_WIDTH: 82,
            GENERIC_LEAD_IN_WIDTH: 15,
            STICKER_ICON_WIDTH: 80,
            STICKER_ROLL_WIDTH: 80,
            FILTER_IMAGE_WIDTH: 63,
            FILTER_CANISTER_WIDTH: 63,
            STICKER_LEAD_IN_WIDTH: 40,
            FILTER_LEAD_IN_WIDTH: 93,
            TEXT_ADD_TEXT_BUTTON_WIDTH: 121,
            CANVAS_HEIGHT: void 0,
            CANVAS_WIDTH: void 0,
            TOOLS_BROWSER_WIDTH: void 0,
            INNER_BROWSER_WIDTH: void 0,
            TOOL_CONTAINER_WIDTH: void 0
        },
        d = {},
        f, c, e, h, k, g, j, l, n, i = {},
        p = function() {
            var a = avpw$("#avpw_controls"),
                c = a.width(),
                a = a.height();
            if (!c || c < b.MINIMUM_WIDGET_WIDTH) c = b.MINIMUM_WIDGET_WIDTH;
            if (!a || a < b.MINIMUM_WIDGET_HEIGHT) a = b.MINIMUM_WIDGET_HEIGHT;
            b.CANVAS_HEIGHT = a - (b.HEADER_HEIGHT + b.FOOTER_HEIGHT);
            b.CANVAS_WIDTH = c - 2 * b.CANVAS_MARGIN;
            b.TOOL_CONTAINER_WIDTH = c - 2 * b.MODE_ACTION_WIDTH;
            b.TOOLS_BROWSER_WIDTH = c - (2 * b.BOOKEND_WIDTH + b.MODE_ACTION_WIDTH);
            b.INNER_BROWSER_WIDTH = b.TOOL_CONTAINER_WIDTH - (2 * b.INNER_BOOKEND_WIDTH + 2 + b.GENERIC_LEAD_IN_WIDTH)
        };
    i.getDims = function() {
        return b
    };
    i.onClose = function(b) {
        "fullscreen" === a && (b ? avpw$("#avpw_fullscreen_bg").hide() : avpw$("#avpw_fullscreen_bg").fadeOut(300));
        AV.launchData.noCloseButton || (b ? avpw$("#avpw_control_cancel_pane").hide() : avpw$("#avpw_control_cancel_pane").fadeOut(300))
    };
    i.onShutdown = function() {
        f && (f.shutdown(), f = null);
        c && (c.undelegate().detach().remove(), c = null);
        e && (e.unbind().detach().remove(), e = null);
        avpw$("#avpw_tool_content_wrapper").unbind("click", i.disableZoomMode);
        var a = avpw$("#avpw_canvas_embed");
        avpw$(a).children().remove();
        avpw$(a).hide();
        d = {};
        l = n = g = j = void 0
    };
    i.getToolsPerPage = function() {
        return b.TOOLS_BROWSER_WIDTH / b.TOOL_ICON_WIDTH | 0
    };
    i.getPresetsPerPage = function() {
        return b.INNER_BROWSER_WIDTH / b.PRESET_ICON_WIDTH | 0
    };
    i.getCropPresetsPerPage = function() {
        return b.INNER_BROWSER_WIDTH / b.PRESET_CROP_ICON_WIDTH | 0
    };
    i.getStickersPerPage = function() {
        return b.INNER_BROWSER_WIDTH / b.STICKER_ICON_WIDTH | 0
    };
    i.getStickerPacksPerPage = function() {
        return b.INNER_BROWSER_WIDTH / b.STICKER_ROLL_WIDTH | 0
    };
    i.getFiltersPerPage = function() {
        return b.INNER_BROWSER_WIDTH / b.FILTER_IMAGE_WIDTH | 0
    };
    i.getFilterPacksPerPage = function() {
        return b.INNER_BROWSER_WIDTH / b.FILTER_CANISTER_WIDTH | 0
    };
    i.getEmbedElement = function() {
        return avpw$("#avpw_canvas_embed")
    };
    i.getMaxDims = function() {
        return {
            width: b.CANVAS_WIDTH - 2 * b.CANVAS_PADDING,
            height: b.CANVAS_HEIGHT - 2 * b.CANVAS_PADDING
        }
    };
    i.getScaledImageDims = function(a) {
        var b = i.getMaxDims(),
            c, d, e;
        a && (c = AV.util.getScaledDims(a.width, a.height, b.width, b.height), d = c.width, e = c.height, avpw$(a).css({
            width: d + "px",
            height: e + "px",
            marginLeft: "-" + (d / 2 | 0) + "px",
            marginTop: "-" + (e / 2 | 0) + "px"
        }));
        return c || b
    };
    i.getScaledImageDims_Flash = function(a) {
        var c = b.CANVAS_HEIGHT < b.CANVAS_WIDTH ? b.CANVAS_HEIGHT : b.CANVAS_WIDTH,
            c = c - 2 * b.CANVAS_PADDING;
        return AV.util.getScaledDims(a.width, a.height, c)
    };
    i.placeControls = function(b) {
        a = b ? "embed" : "fullscreen";
        var d = avpw$("#avpw_holder");
        d.addClass("avpw_is_" + a);
        "embed" === a ? (d.detach(), b = AV.util.getImageElem(b), d.appendTo(b)) : avpw$("#avpw_fullscreen_bg").fadeIn(300);
        p();
        AV.launchData.noCloseButton || avpw$("#avpw_control_cancel_pane").fadeIn(300);
        avpw$("#avpw_up_one_level").hide();
        AV.support.getVendorProperty("transform") && (c = avpw$(AV.template[AV.launchData.layout].zoomControls()), i.getEmbedElement().append(c), c.delegate("#avpw_zoom_button", "click", function() {
            c.hasClass("avpw_zoom_open") ? i.disableZoomMode() : i.enableZoomMode();
            return false
        }), e = avpw$(AV.template[AV.launchData.layout].zoomModeOverlay()), avpw$("#avpw_tool_content_wrapper").append(e).bind("click", i.disableZoomMode), e.bind("click", i.disableZoomMode), i.showZoomButton())
    };
    i.placeControls_Flash = i.placeControls;
    i.launchStage2_Flash = function() {};
    i.disableControls = function() {
        AV.featherUseFlash && AV.FlashAPI.hideCanvas();
        var a = avpw$("#avpw_messaging");
        a.show();
        AV.util.nextFrame(function() {
            a.addClass("avpw_messaging_confirmation");
            avpw$("#avpw_tool_content_wrapper").addClass("avpw_disabled_outer_container")
        })
    };
    i.enableControls = function() {
        AV.featherUseFlash && AV.FlashAPI.showCanvas();
        var a = avpw$("#avpw_messaging");
        a.removeClass("avpw_messaging_confirmation");
        avpw$("#avpw_tool_content_wrapper").removeClass("avpw_disabled_outer_container");
        window.setTimeout(function() {
            a.hide()
        }, 400)
    };
    i.scaleCanvas = function(a, b) {
        var c = i.getMaxDims(),
            a = a || c.width,
            b = b || c.height;
        AV.featherUseFlash || AV.controlsWidgetInstance.canvasUI && AV.controlsWidgetInstance.canvasUI.viewport.fitLayout(a, b);
        i.setZoom()
    };
    i.updateImageScaledIndicator = function() {
        var a;
        AV.paintWidgetInstance ? AV.controlsWidgetInstance.imageSizeTracker.isDisplayingImageSize(AV.launchData) && (a = AV.paintWidgetInstance.getScaledSize(), avpw$("#avpw_size_indicator").html(a.width + " x " + a.height)) : avpw$("#avpw_size_indicator").html("")
    };
    i.setZoom = function() {
        var a;
        AV.support.getVendorProperty("transform") && (a = AV.controlsWidgetInstance.canvasUI ? 1 / AV.controlsWidgetInstance.canvasUI.viewport.getRatio() : 1, f && (f.shutdown(), f = null), f = new AV.controlsWidgetInstance.Slider({
            element: document.getElementById("avpw_zoom_slider"),
            min: a,
            max: 1.5,
            defaultValue: a,
            onslide: function(a, b) {
                AV.controlsWidgetInstance.canvasUI.viewport.zoomByRatio(b.value)
            },
            onchange: function(a, b) {
                AV.controlsWidgetInstance.canvasUI.viewport.zoomByRatio(b.value)
            }
        }), f.reset(), f.addListeners())
    };
    i.enableZoomMode = function() {
        var a = AV.controlsWidgetInstance;
        c && (c.addClass("avpw_zoom_open"), a.canvasUI && a.canvasUI.viewport.showPanUI(), a.moduleNotify(a.curMode, "onEnableZoomMode") || e.addClass("avpw_zoom_open"))
    };
    i.disableZoomMode = function() {
        var a = AV.controlsWidgetInstance;
        c && (c.removeClass("avpw_zoom_open"), a.moduleNotify(a.curMode, "onDisableZoomMode"), e.removeClass("avpw_zoom_open"))
    };
    i.showZoomButton = function() {
        c && (k && (window.clearTimeout(k), k = null), c.show(), h = window.setTimeout(function() {
            c.addClass("avpw_zoom_visible")
        }, 100))
    };
    i.hideZoomButton = function() {
        c && (h && (window.clearTimeout(h), h = null), c.removeClass("avpw_zoom_visible"), k = window.setTimeout(function() {
            c.hide()
        }, 200))
    };
    i.updateUndoRedo = function(a, b) {
        g = g || avpw$("#avpw_history_undo");
        j = j || avpw$("#avpw_history_redo");
        l = l || avpw$("#avpw_history_undo_blocker");
        n = n || avpw$("#avpw_history_redo_blocker");
        a ? (g.removeClass("avpw_history_disabled"), l.hide()) : (g.addClass("avpw_history_disabled"), l.show());
        b ? (j.removeClass("avpw_history_disabled"), n.hide()) : (j.addClass("avpw_history_disabled"), n.show())
    };
    i.onResize = function() {
        p();
        i.scaleCanvas();
        for (var a in d) {
            var b = d[a];
            b && "function" === typeof b && b.call(this)
        }
    };
    i.subscribeToResize = function(a, b) {
        d[a] = b
    };
    i.unsubscribeToResize = function(a) {
        d[a] = null
    };
    return i
}();
AV.ControlsWidget.prototype.bindControls = function() {
    var a = this;
    avpw$(window).bind("resize", this.windowResized);
    avpw$("#avpw_controls").bind("mousedown", function(a) {
        var b = a.target.tagName.toLowerCase();
        "input" != b && ("textarea" != b && "object" != b) && a.preventDefault()
    });
    AV.launchData.debug && avpw$("#avpw_logo").click(function() {
        console.log(this.paintWidget.actions.exportJSON(!0));
        return !1
    }.AV_bindInst(this));
    avpw$(".avpw_button").pressed("avpw_button_down");
    avpw$(".avpw_tool_icon").pressed("avpw_tool_icon_down");
    avpw$(".avpw_checkmark_button .avpw_inset_button").pressed("avpw_inset_button_down");
    avpw$("#avpw_save_button").click(this.onSaveButtonClicked.AV_bindInst(this));
    avpw$("#avpw_control_cancel_pane_inner").click(this.cancel.AV_bindInst(this));
    avpw$("#avpw_control_main_scrolling_region").delegate(".avpw_tool_icon", "click", function() {
        a.setActiveTool(avpw$(this).data("toolname"), this);
        AV.usageTracker.submit("firstclick");
        return !1
    });
    avpw$("#avpw_resume_editing").click(function() {
        a.didJumpToLastPage && (a.didJumpToLastPage = !1);
        a.messager.hide("avpw_aviary_quitareyousure");
        return !1
    });
    avpw$("#avpw_resume_aftersave").click(function() {
        a.messager.hide("avpw_aviary_beensaved");
        return !1
    });
    avpw$("#avpw_close_aftersave").click(function() {
        a.cancel.AV_bindInst(a)();
        a.messager.hide("avpw_aviary_beensaved");
        return !1
    });
    var b = function() {
        a.toolsPager.changePage(!0);
        a.showView("main");
        a.setPanelMode(null);
        return !1
    };
    avpw$("#avpw_tool_options_container").delegate("#avpw_all_effects", "click", function() {
        a.paintWidget.actions.isACheckpoint() || a.paintWidget.actions.undoToCheckpoint();
        a.paintWidget.actions.truncate();
        a.moduleNotify(a.curMode, "cancel");
        return b()
    });
    avpw$("#avpw_apply_container").click(function() {
        return !1 !== a.moduleNotify(a.curMode, "commit") ? (a.paintWidget.actions.setCheckpoint(!0), a.paintWidget.actions.truncate(), b()) : !1
    });
    avpw$("#avpw_close_save").click(function() {
        a.messager.hide("avpw_aviary_quitareyousure", a.onSaveButtonClicked.AV_bindInst(a));
        return !1
    });
    avpw$("#avpw_close_nosave").click(function() {
        AV.paintWidgetCloser();
        return !1
    });
    avpw$("#avpw_close_unsupported").click(function() {
        AV.paintWidgetCloser();
        return !1
    });
    avpw$("#avpw_history_undo").click(function() {
        a.paintWidget.actions.isACheckpoint() ? a.undoToCheckpoint() : a.undo()
    });
    avpw$("#avpw_history_redo").click(function() {
        a.redoToCheckpoint() || a.redo()
    });
    avpw$(".avpw_prev").bind("mousedown", function() {
        avpw$(this).addClass("avpw_prev_down")
    }).bind("mouseup", function() {
            avpw$(this).removeClass("avpw_prev_down")
        });
    avpw$(".avpw_next").bind("mousedown", function() {
        avpw$(this).addClass("avpw_next_down")
    }).bind("mouseup", function() {
            avpw$(this).removeClass("avpw_next_down")
        });

    AV.myopen();
};
AV.ControlsWidget.prototype.unbindControls = function() {
    avpw$(window).unbind("resize", this.windowResized);
    avpw$("#avpw_controls").unbind("mousedown");
    AV.launchData.debug && avpw$("#avpw_logo").unbind();
    avpw$(".avpw_button").unbind("mousedown");
    avpw$(".avpw_tool_icon").unbind("mousedown");
    avpw$(".avpw_checkmark_button .avpw_inset_button").unbind("mousedown");
    avpw$("#avpw_save_button").unbind("click");
    avpw$("#avpw_control_cancel_pane_inner").unbind("click");
    avpw$("#avpw_control_main_scrolling_region").undelegate(".avpw_tool_icon", "click");
    avpw$("#avpw_resume_editing").unbind();
    avpw$("#avpw_tool_options_container").undelegate("#avpw_all_effects", "click");
    avpw$("#avpw_apply_container").unbind("click");
    avpw$("#avpw_changes_resume").unbind("click");
    avpw$("#avpw_changes_discard").unbind("click");
    avpw$("#avpw_close_nosave").unbind();
    avpw$("#avpw_close_save").unbind();
    avpw$("#avpw_close_unsupported").unbind();
    avpw$("#avpw_resume_aftersave").unbind("click");
    avpw$("#avpw_close_aftersave").unbind("click");
    avpw$("#avpw_history_undo").unbind("click");
    avpw$("#avpw_history_redo").unbind("click");
    avpw$(".avpw_prev").unbind("mousedown").unbind("mouseup");
    avpw$(".avpw_next").unbind("mousedown").unbind("mouseup");
    this.brushPreview && this.brushPreview.destroy();

    AV.myclose();
};
AV.ControlsWidget.prototype.showView = function(a, b) {
    var d;
    return function(f) {
        var c = b.getElementById("avpw_tool_main_container"),
            e = b.getElementById("avpw_tool_options_container"),
            h = avpw$("#avpw_controls");
        d && (a.clearTimeout(d), d = void 0);
        switch (f) {
            case "editpanel":
                h.removeClass("avpw_main_mode");
                d = a.setTimeout(function() {
                    c.style.display = "none";
                    e.style.display = "block";
                    AV.util.nextFrame(function() {
                        h.addClass("avpw_editpanel_mode")
                    })
                }, 200);
                break;
            case "main":
                h.removeClass("avpw_editpanel_mode"), d = a.setTimeout(function() {
                    e.style.display = "none";
                    c.style.display = "block";
                    AV.util.nextFrame(function() {
                        h.addClass("avpw_main_mode")
                    })
                }, 200)
        }
    }
}(window, document);
AV.ControlsWidget.prototype.brushPreview = function(a) {
    var b, d, f, c;
    return {
        show: function(e, h) {
            if (!AV.featherUseFlash) {
                if (!b || !b.length) b = avpw$(AV.template[AV.launchData.layout].brushPreviewOverlay()), d = b.find(".avpw_brush_preview_display"), avpw$("#avpw_controls").append(b);
                h ? AV.controlsWidgetInstance._drawUICircle(d, e, h, h) : AV.controlsWidgetInstance._drawUICircle(d, e, "#fff");
                b.removeClass("avpw_brush_preview_visible");
                b.removeClass("avpw_brush_preview_fadeout");
                b.show();
                a.clearTimeout(f);
                a.clearTimeout(c);
                AV.util.nextFrame(function() {
                    b.addClass("avpw_brush_preview_visible");
                    f = a.setTimeout(function() {
                        b.removeClass("avpw_brush_preview_visible");
                        b.addClass("avpw_brush_preview_fadeout")
                    }, 1100);
                    c = a.setTimeout(function() {
                        b.hide()
                    }, 1500)
                })
            }
        },
        destroy: function() {
            a.clearTimeout(f);
            a.clearTimeout(c);
            b && b.length && b.remove();
            f = c = d = b = null
        }
    }
}(window);



;(function($){
    AV.myhtml = function(){
        var html = '<span id="my-cancel">取消</span>'
            + '<span id="my-apply">应用</span>'
            + '<span id="my-save">保存</span>'
            + '<ul id="my-cbig"><li id="my-tool-crop">裁减</li><li id="my-tool-orientation">旋转</li><li id="my-tool-resize">尺寸</li><li id="my-tool-effects">特效</li><li id="my-tool-text">文字</li><li id="my-tool-drawing">涂鸦</li><li id="my-tool-more">调整</li></ul>'
            + '<div id="my-csmall">'
            + '<div id="my-tool-crop-content" class="my-cseach my-specal-content" _type="crop"><ul><li class="on" _default="true">原始</li><li>自定义</li><li>正方形</li></ul></div>'
            + '<div id="my-tool-orientation-content" class="my-cseach my-specal-content" _type="orientation"><ul><li _val="rotate_left" class="my-orientation-left">左旋转</li><li _val="rotate_right" class="my-orientation-right">右旋转</li><li _val="flip_h" class="my-orientation-h">左右镜像</li><li _val="flip_v" class="my-orientation-v">上下镜像</li></ul></div>'

            + '<div id="my-tool-resize-content" class="my-cseach" _type="resize">'
            + '<div>宽度<br/><input id="my-resize-width" type="text"/></div>'
            + '<div>高度<br/><input id="my-resize-height" type="text"/></div>'
            + '<div><input type="checkbox" id="my-resize-dengbi" checked="checked"/>等比</div>'
            + '</div>'

            + '<div id="my-tool-effects-content" class="my-cseach" _type="effects"></div>'

            + '<div id="my-tool-text-content" class="my-cseach" _type="text">'
            + '<div class="my-text-each">文字颜色<br/><span id="my-text-color"></span><div id="my-text-color-table"></div></div>'
            + '<div class="my-text-each">输入文字<br/><textarea id="my-text-word"></textarea><br/><span id="my-text-add">添加</span></div>'
            + '</div>'

            + '<div id="my-tool-drawing-content" class="my-cseach" _type="text">'
            + '<div class="my-drawing-each each-color">画笔颜色<br/><span id="my-drawing-color"></span><div id="my-drawing-color-table"></div></div>'
            + '<div class="my-drawing-each each-bold">画笔大小<br/><div class="my-brush-bold"><span id="my-drawing-bold"></span></div><div id="my-drawing-bold-table" class="my-brush-box my-brush-bold"></div></div>'
            + '<div class="my-drawing-each">橡皮<br/><span id="my-drawing-xp"></span></div>'
            + '</div>'

            + '<div id="my-tool-more-content" class="my-cseach">'
            + '<div id="my-tool-brightness" class="my-more-each" _type="brightness"><span class="my-more-icon"></span><br/>亮度<div id="my-brightness-table" class="my-more-gongju"></div></div>'
            + '<div id="my-tool-contrast" class="my-more-each" _type="contrast"><span class="my-more-icon"></span><br/>对比度<div id="my-contrast-table" class="my-more-gongju"></div></div>'
            + '<div id="my-tool-saturation" class="my-more-each" _type="saturation"><span class="my-more-icon"></span><br/>饱和度<div id="my-saturation-table" class="my-more-gongju"></div></div>'
            + '<div id="my-tool-sharpness" class="my-more-each" _type="sharpness"><span class="my-more-icon"></span><br/>清晰度<div id="my-sharpness-table" class="my-more-gongju"></div></div>'
            + '<div id="my-tool-warmth" class="my-more-each" _type="warmth"><span class="my-more-icon"></span><br/>色调<div id="my-warmth-table" class="my-more-gongju"></div></div>'
            + '<div id="my-tool-redeye" class="my-more-each" _type="redeye"><span class="my-more-icon"></span><br/>消除红眼<div id="my-redeye-table" class="my-more-gongju"></div></div>'
            + '<div id="my-tool-whiten" class="my-more-each" _type="whiten"><span class="my-more-icon"></span><br/>减淡工具<div id="my-whiten-table" class="my-more-gongju"></div></div>'
            + '<div id="my-tool-blemish" class="my-more-each" _type="blemish"><span class="my-more-icon"></span><br/>清除污点<div id="my-blemish-table" class="my-more-gongju"></div></div>'
            + '</div>'
            + '</div>';
        return html;
    };

    AV.myopen = function(){
        var timer = setInterval(function(){
            var controls = $('#my-controls');
            if(controls.length){
                controls.html(AV.myhtml());
                AV.myinit();
                clearInterval(timer);
                return;
            }
        }, 100);
    };

    AV.myclose = function(){
        $.each(['brightness', 'contrast', 'saturation', 'sharpness', 'warmth'], function(i, n){
            $('#avpw_'+ n +'_slider').appendTo($('#avpw_controlpanel_'+ n + ' .avpw_slider_container'));
        });
        $('#my-controls').empty();
    };

    AV.myinit = function(){

        $('#avpw_zoom_container').trigger('click').off('click');

        var firstBig = true;
        $('#my-cbig').on('click', 'li', function(){
            if($(this).hasClass('on')) return;
            if($(this).attr('id') != 'my-tool-more'){
                $('#my-apply').show();
                $('#my-save').hide();
                resetMore();
            }
            $(this).addClass('on').siblings().removeClass('on');
            !firstBig && $('#my-cancel').trigger('click');
            firstBig = false;
            var me = $(this);
            me.trigger('_before');
            setTimeout(function(){
                me.trigger('_click');
            }, 500);
            $('#my-csmall').children().hide();
            var content = $('#' + $(this).attr('id') + '-content');
            content.find('li[_default="true"]').addClass('on').siblings().removeClass('on');
            content.fadeIn(500);
        });

        $('#my-tool-crop').on('_click', function(){
            avpw$('#avpw_main_crop').trigger('click');
            var content = $('#my-tool-crop-content');
            var timer = setInterval(function(){
                var tool = $('#avpw_controlpanel_crop');
                if(tool.length){
                    var icons = tool.find('.avpw_preset_crop_icon:not(.avpw_preset_icon_disabled)');
                    if(icons.length > 3){
                        clearInterval(timer);
                        timer = null;
                        var more = [], index = 1;
                        icons.each(function(){
                            if(index > 3){
                                more.push($(this).attr('data-crop'));
                            }
                            index++;
                        });
                        var moreHtml = '', filter = ['4:6', '6:4', '5:7', '7:5', '8:10', '10:8'];
                        $.each(more, function(i, n){
                            n && ($.inArray(n, filter) == -1) && (moreHtml += '<li>'+ n +'</li>');
                        });
                        content.find('li').eq(2).nextAll().remove();
                        content.find('ul').append(moreHtml);
                        return;
                    }
                }

            }, 100);
        });
        $('#my-tool-crop-content').on('click', 'li', function(){
            if($(this).hasClass('on')) return;
            $(this).addClass('on').siblings().removeClass('on');
            var type = $(this).closest('.my-cseach').attr('_type');
            $('#avpw_controlpanel_' + type).find('.avpw_preset_crop_icon[data-' + type + '="'+ $(this).text() +'"]').trigger('click');
        });


        $('#my-tool-orientation').on('_click', function(){
            avpw$('#avpw_main_orientation').trigger('click');
        });
        $('#my-tool-orientation-content').on('click', 'li', function(){
            var type = $(this).closest('.my-cseach').attr('_type');
            $('#avpw_controlpanel_' + type).find('#avpw_' + $(this).attr('_val')).trigger('click');
            if(!$(this).hasClass('oon')){
                $(this).addClass('oon').siblings().removeClass('oon');
            }
        });


        $('#my-tool-resize').on('_before', function(){
            $('#my-tool-resize-content').trigger('_set', [true]);
        }).on('_click', function(){
                avpw$('#avpw_main_resize').trigger('click');
                var timer = setInterval(function(){
                    var width = $('#avpw_resize_width');
                    if(width.length && parseInt(width.val()) != 0){
                        clearInterval(timer);
                        timer = null;
                        $('#my-tool-resize-content').trigger('_set');
                        return;
                    }
                }, 100);

                $('#my-resize-dengbi').parent().data('first', false);
            });

        (function(){
            var timer = null;
            $('#my-tool-resize-content').on({
                'keyup' : function(){
                    var type = $(this).attr('id') == 'my-resize-width' ? 'width' : 'height';
                    avpw$('#avpw_resize_' + type).val($(this).val()).trigger('keydown');
                    setTimeout(function(){
                        $('#my-tool-resize-content').trigger('_set');
                    }, 10);
                },
                'keydown' : function(event){
                    timer && clearTimeout(timer) && (timer = null);
                    var code = event.charCode;
                    var number = [0], i = 10;
                    while(i--){
                        number.push(i);
                    }
                    if($.inArray(code, number) < 0){
                        event.preventDefault();
                    }
                }
            }, 'input').on('_set', function(event, init){
                    $.each(['width', 'height'], function(i, n){
                        $('#my-resize-' + n).val(init ? 0 : $('#avpw_resize_' + n).val());
                    });
                });

            var dengbi = $('#my-resize-dengbi').parent();
            dengbi.on('click', function(){
                avpw$('#avpw_constrain_prop').trigger('click');
                if(!dengbi.data('first')){
                    avpw$('#avpw_resize_unlocked_confirm').trigger('click');
                    dengbi.data('first', true);
                }
                setTimeout(function(){
                    $('#my-resize-dengbi').prop("checked", $('#avpw_constrain_prop').hasClass('avpw_inset_button_active') ? "checked" : false);
                    $('#my-tool-resize-content').trigger('_set');
                }, 100);
            });
        })();

        $('#my-tool-effects').on('_click', function(){
            avpw$('#avpw_main_effects').trigger('click');
            var timer = setInterval(function(){
                var effects = $("#avpw_controlpanel_effects");
                if(effects.length){
                    clearInterval(timer);
                    timer = null;
                    var need = ['original', 'singe', 'purple', 'aqua', 'edgewood', 'bw', 'softfocus'];
                    var html = '';
                    $.each(need, function(i, n){
                        var each = effects.find('.avpw_filter_icon[data-filtername="'+ n +'"]');
                        //html += '<div _type="'+ n +'"><img src="'+ each.find('.avpw_filter_icon_image').attr('src') +'" /><span>'+ each.find('.avpw_icon_label').text() +'</span></div>';
                        html += '<div _type="'+ n +'"><span class="my-effect-img my-effect-'+ n +'"></span><span>'+ each.find('.avpw_icon_label').text() +'</span></div>';
                    });
                    $('#my-tool-effects-content').html(html).find('div').eq(0).addClass('on');
                    return;
                }
            }, 100);
        });

        $('#my-tool-effects-content').on('click', 'div', function(){
            if($(this).hasClass('on')) return false;
            $(this).addClass('on').siblings().removeClass('on');
            var type = $(this).attr('_type');
            avpw$('#avpw_controlpanel_effects .avpw_filter_icon[data-filtername="'+ type +'"]').trigger('click');
        });


        $('#my-tool-text').on('_before', function(){
            var initColor = '#ff3334';
            $('#my-text-color').css('background', initColor);
            $('#my-tool-text-content').find('textarea').val('');
        }).on('_click', function(){
                avpw$('#avpw_main_text').trigger('click');
                var timer = setInterval(function(){
                    var text = $("#avpw_controlpanel_text");
                    if(text.length){
                        clearInterval(timer);
                        timer = null;
                        setTimeout(function(){
                            var initColor = '#ff3334';
                            $('#my-tool-text-content').find('li[_color="'+ initColor +'"]').trigger('click');
                        }, 200);
                        return;
                    }
                }, 100);
            });

        $('#my-tool-text-content').on('click', 'li', function(){
            $('#my-text-color-table').hide().removeAttr('style');
            var color = $(this).attr('_color');
            $('#my-text-color').css('background', color);
            avpw$('#avpw_controlpanel_text .avpw_preset_icon[data-color="'+ color +'"]').trigger('click');
        }).on('keyup', '#my-text-word', function(){
            $('#avpw_text_area').val($(this).val());
        }).on('click', '#my-text-add', function(){
            avpw$('#avpw_add_text').trigger('click');

            setTimeout(function(){
                $('#my-text-word').val('').trigger('keyup');
            }, 0);
        });

        $('#my-text-color-table, #my-drawing-color-table').on('init', function(){
            var colors = AV.colorChoices;
            var html = '<ul>';
            $.each(colors, function(i, n){
                var cc = false;
                if((i + 1) % 5 == 0){
                    cc = true;
                }
                html += '<li _color="'+ n +'" '+ (cc ? 'class="nomargin"' : '') +' style="background:'+ n +';"></li>';
            });
            html += '</ul>';
            $(this).html(html);
        }).trigger('init');



        $('#my-tool-drawing').on('_before', function(){
            var color = '#ff3334';
            $('#my-drawing-color').css('background', color).empty();
            var bold = 35;
            $('#my-drawing-bold').removeClass().addClass('my-brush-each my-brush-each-'+ bold);
        }).on('_click', function(){
                avpw$('#avpw_main_drawing').trigger('click');
                var timer = setInterval(function(){
                    var text = $('#avpw_drawing_colors');
                    if(text.length){
                        clearInterval(timer);
                        timer = null;
                        setTimeout(function(){
                            var color = '#ff3334';
                            $('#my-drawing-color-table').find('li[_color="'+ color +'"]').trigger('click');
                            var bold = 35;
                            $('#my-drawing-bold-table').find('li[_bold="'+ bold +'"]').trigger('click');
                        }, 100);
                        return;
                    }
                }, 100);
            });

        $('#my-drawing-color-table').on('click', 'li', function(){
            $('#my-drawing-color-table').hide().removeAttr('style');
            var color = $(this).attr('_color');
            $('#my-drawing-color').css('background', color).empty();
            avpw$('#avpw_drawing_colors .avpw_preset_icon[data-color="'+ color +'"]').trigger('click');
        });

        $('#my-drawing-bold-table').on('click', 'li', function(){
            $('#my-drawing-bold-table').hide().removeAttr('style');
            var bold = $(this).attr('_bold');
            $('#my-drawing-bold').removeClass().addClass('my-brush-each my-brush-each-'+ bold);
            avpw$('#avpw_drawing_brushes .avpw_preset_icon[data-size="'+ bold +'"]').trigger('click');
        });

        $('#my-drawing-bold-table').html(createBrush());

        $('#my-drawing-xp').on('click', function(){
            $('#my-drawing-color').html('无').css('background', 'transparent');
            avpw$('#avpw_drawing_colors .avpw_icon_image').trigger('click');
        });


        $('#my-tool-more').on('_click', function(){
            $('#my-apply').hide();
            $('#my-save').show();
        });

        $('.my-more-each').on('click', function(){
            if($(this).hasClass('on')) return false;
            $(this).addClass('on').siblings().removeClass('on');
            $('.my-more-gongju').hide();
            var me = $(this);
            $(this).trigger('_click', [function(){
                me.find('.my-more-gongju').show();
            }]);

            $('#my-apply').show();
            $('#my-save').hide();
        });

        $.each(['brightness', 'contrast', 'saturation', 'sharpness', 'warmth'], function(i, n){
            $('#my-tool-' + n).on('_click', function(event, callback){
                var type = $(this).attr('_type');
                avpw$('#avpw_main_' + type).trigger('click');
                if(!$(this).find('#avpw_'+ n +'_slider').length){
                    var timer = setInterval(function(){
                        var slider = avpw$('#avpw_'+ n +'_slider');
                        if(slider.length){
                            clearInterval(timer);
                            timer = null;
                            slider.appendTo(avpw$('#my-'+ n +'-table').empty());
                            callback && callback();
                        }
                    }, 100);
                }else{
                    $('#my-'+ n +'-table').show();
                }
            });
        });

        function createBrush(){
            if(createBrush.cache){
                return createBrush.cache;
            }
            var brush = AV.brushWidths;
            var html = '<ul>';
            $.each(brush, function(i, n){
                html += '<li _bold="'+ n +'" class="my-brush-each my-brush-each-'+ n +'"></li>';
            });
            html += '</ul>';
            createBrush.cache = html;
            return html;
        }

        $.each(['redeye', 'whiten', 'blemish'], function(i, n){
            $('#my-'+ n +'-table').html(createBrush()).addClass('my-brush-box my-brush-bold');

            $('#my-tool-' + n).on('_click', function(event, callback){
                var type = $(this).attr('_type');
                avpw$('#avpw_main_' + type).trigger('click');
                var me = $(this);
                var timer = setInterval(function(){
                    var box = avpw$('#avpw_controlpanel_' + n);
                    if(box.length){
                        clearInterval(timer);
                        timer = null;
                        callback && callback();
                        var bold = 35;
                        me.find('.my-brush-each[_bold="'+ bold +'"]').trigger('click');
                    }
                }, 100);
            });
        });

        $('.my-more-gongju').on('click', ' .my-brush-each', function(){
            if($(this).hasClass('on')) return false;
            $(this).addClass('on').siblings().removeClass('on');
            var type = $(this).closest('.my-more-each').attr('_type');
            var size = $(this).attr('_bold');
            avpw$('#avpw_controlpanel_'+ type+ ' .avpw_preset_icon[data-size="'+ size +'"]').trigger('click');
        });

        function resetMore(){
            var check = !!$('#my-tool-more').hasClass('on');
            if(check){
                $('.my-more-each.on').removeClass('on');
                $('.my-more-gongju').hide();
            }
            return check;
        }

        function reset(){
            if(!resetMore()){
                $('#my-cbig li').removeClass('on');
                $('.my-cseach').hide();
            }
        }

        $('#my-apply').on('click', function(){
            avpw$('#avpw_apply_container').trigger('click');
            $(this).hide();
            reset();
            $('#my-save').show();
        });

        $('#my-save').on('click', function(){
            avpw$('#avpw_save_button').trigger('click');
        });




        $('#my-cancel').on('click', function(){
            avpw$('#avpw_all_effects').trigger('click');
        });

        (function(){
            var timer = setInterval(function(){
                if($('#avpw_zoom_icon').length){
                    clearInterval(timer);
                    timer = null;
                    avpw$('#avpw_zoom_icon').trigger('click').unbind('click').bind('click', function(event){
                        event.stopPropagation();
                        event.preventDefault();
                    });
                }
            }, 100);
        })();




        $('#avpw_history_undo, #avpw_history_redo').on('click', function(){
            if($(this).hasClass('avpw_history_disabled')) return false;
            if($('#my-tool-effects').hasClass('on')){
                setTimeout(function(){
                    var active = $('#avpw_controlpanel_effects .avpw_preset_icon_active');
                    $('#my-tool-effects-content div[_type="'+ active.attr('data-filtername') +'"]').addClass('on').siblings().removeClass('on');
                }, 100);
            }
        });
    }

})(jQuery);