// this has been modded to put JSON in the AV namespace
typeof AV == "undefined" && (AV = {}), AV.JSON = {}, function() {
    "use strict";

    function f(e) {
        return e < 10 ? "0" + e : e
    }
    function quote(e) {
        return escapable.lastIndex = 0, escapable.test(e) ? '"' + e.replace(escapable, function(e) {
            var t = meta[e];
            return typeof t == "string" ? t : "\\u" + ("0000" + e.charCodeAt(0).toString(16)).slice(-4)
        }) + '"' : '"' + e + '"'
    }
    function str(e, t) {
        var n, r, i, s, o = gap,
            u, a = t[e];
        a && typeof a == "object" && typeof a.toJSON == "function" && (a = a.toJSON(e)), typeof rep == "function" && (a = rep.call(t, e, a));
        switch (typeof a) {
            case "string":
                return quote(a);
            case "number":
                return isFinite(a) ? String(a) : "null";
            case "boolean":
            case "null":
                return String(a);
            case "object":
                if (!a) return "null";
                gap += indent, u = [];
                if (Object.prototype.toString.apply(a) === "[object Array]") {
                    s = a.length;
                    for (n = 0; n < s; n += 1) u[n] = str(n, a) || "null";
                    return i = u.length === 0 ? "[]" : gap ? "[\n" + gap + u.join(",\n" + gap) + "\n" + o + "]" : "[" + u.join(",") + "]", gap = o, i
                }
                if (rep && typeof rep == "object") {
                    s = rep.length;
                    for (n = 0; n < s; n += 1) r = rep[n], typeof r == "string" && (i = str(r, a), i && u.push(quote(r) + (gap ? ": " : ":") + i))
                } else
                    for (r in a) Object.hasOwnProperty.call(a, r) && (i = str(r, a), i && u.push(quote(r) + (gap ? ": " : ":") + i));
                return i = u.length === 0 ? "{}" : gap ? "{\n" + gap + u.join(",\n" + gap) + "\n" + o + "}" : "{" + u.join(",") + "}", gap = o, i
        }
    }
    typeof Date.prototype.toJSON != "function" && (Date.prototype.toJSON = function(e) {
        return isFinite(this.valueOf()) ? this.getUTCFullYear() + "-" + f(this.getUTCMonth() + 1) + "-" + f(this.getUTCDate()) + "T" + f(this.getUTCHours()) + ":" + f(this.getUTCMinutes()) + ":" + f(this.getUTCSeconds()) + "Z" : null
    }, String.prototype.toJSON = Number.prototype.toJSON = Boolean.prototype.toJSON = function(e) {
        return this.valueOf()
    });
    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap, indent, meta = {
            "\b": "\\b",
            "	": "\\t",
            "\n": "\\n",
            "\f": "\\f",
            "\r": "\\r",
            '"': '\\"',
            "\\": "\\\\"
        },
        rep;
    typeof AV.JSON.stringify != "function" && (AV.JSON.stringify = function(e, t, n) {
        var r;
        gap = "", indent = "";
        if (typeof n == "number") for (r = 0; r < n; r += 1) indent += " ";
        else typeof n == "string" && (indent = n);
        rep = t;
        if (!t || typeof t == "function" || typeof t == "object" && typeof t.length == "number") return str("", {
            "": e
        });
        throw new Error("AV.JSON.stringify")
    }), typeof AV.JSON.parse != "function" && (AV.JSON.parse = function(text, reviver) {
        function walk(e, t) {
            var n, r, i = e[t];
            if (i && typeof i == "object") for (n in i) Object.hasOwnProperty.call(i, n) && (r = walk(i, n), r !== undefined ? i[n] = r : delete i[n]);
            return reviver.call(e, t, i)
        }
        var j;
        text = String(text), cx.lastIndex = 0, cx.test(text) && (text = text.replace(cx, function(e) {
            return "\\u" + ("0000" + e.charCodeAt(0).toString(16)).slice(-4)
        }));
        if (/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""))) return j = eval("(" + text + ")"), typeof reviver == "function" ? walk({
            "": j
        }, "") : j;
        throw new SyntaxError("AV.JSON.parse")
    })
}();
/*
 * Canvas2Image v0.1
 * Copyright (c) 2008 Jacob Seidelin, jseidelin@nihilogic.dk
 * MIT License [http://www.opensource.org/licenses/mit-license.php]
 *
 * Modified by Aviary to AV namespace; no public vars 
 */
typeof AV == "undefined" && (AV = {}), AV.toBitmapURL = function(e) {
    var t = function(t) {
            var n = "";
            if (typeof t == "string") n = t;
            else {
                var r = t;
                for (var i = 0; i < r.length; i++) n += String.fromCharCode(r[i])
            }
            return e.btoa(n)
        },
        n = function(e) {
            var n = [],
                r = e.width,
                i = e.height;
            n.push(66), n.push(77);
            var s = r * i * 3 + 54;
            n.push(s % 256), s = Math.floor(s / 256), n.push(s % 256), s = Math.floor(s / 256), n.push(s % 256), s = Math.floor(s / 256), n.push(s % 256), n.push(0), n.push(0), n.push(0), n.push(0), n.push(54), n.push(0), n.push(0), n.push(0);
            var o = [];
            o.push(40), o.push(0), o.push(0), o.push(0);
            var u = r;
            o.push(u % 256), u = Math.floor(u / 256), o.push(u % 256), u = Math.floor(u / 256), o.push(u % 256), u = Math.floor(u / 256), o.push(u % 256);
            var a = i;
            o.push(a % 256), a = Math.floor(a / 256), o.push(a % 256), a = Math.floor(a / 256), o.push(a % 256), a = Math.floor(a / 256), o.push(a % 256), o.push(1), o.push(0), o.push(24), o.push(0), o.push(0), o.push(0), o.push(0), o.push(0);
            var f = r * i * 3;
            o.push(f % 256), f = Math.floor(f / 256), o.push(f % 256), f = Math.floor(f / 256), o.push(f % 256), f = Math.floor(f / 256), o.push(f % 256);
            for (var l = 0; l < 16; l++) o.push(0);
            var c = (4 - r * 3 % 4) % 4,
                h = e.data,
                p = "",
                d = i;
            do {
                var v = r * (d - 1) * 4,
                    m = "";
                for (var g = 0; g < r; g++) {
                    var y = 4 * g;
                    m += String.fromCharCode(h[v + y + 2]), m += String.fromCharCode(h[v + y + 1]), m += String.fromCharCode(h[v + y])
                }
                for (var b = 0; b < c; b++) m += String.fromCharCode(0);
                p += m
            } while (--d);
            var w = t(n.concat(o)) + t(p);
            return w
        };
    return function(e) {
        var t = e.width,
            r = e.height;
        return n(e.getContext("2d").getImageData(0, 0, t, r))
    }
}(window);
"undefined" == typeof AV && (AV = {});
AV.Actions = function() {
    this.actionList = [];
    this.index = -1;
    return this
};
AV.Actions.prototype.undoImplicitActions = function() {
    for (; 0 <= this.index && !0 === this.actionList[this.index][2].implicit && !this.isACheckpoint();) {
        var a = this.actionList[this.index][0];
        a[0].apply(a[1], a[2]);
        this.index--
    }
};
AV.Actions.prototype.canUndo = function() {
    var a = 0 <= this.index;
    0 === this.index && !0 === this.actionList[0][2].implicit && (a = !1);
    return a
};
AV.Actions.prototype.undo = function() {
    var a, b, f;
    if (!(0 > this.index || this.isACheckpoint())) {
        do {
            AV.paintWidgetInstance && (AV.paintWidgetInstance.dirty = !0);
            if (a = this.actionList[this.index][0]) b = a[0], f = a[1], a = a[2], b.apply(f, a);
            0 < this.index ? (b = this.actionList[this.index - 1][3], this.setDims(b.width, b.height)) : this.setDims(this._origHiresWidth, this._origHiresHeight);
            this.index--
        } while (0 <= this.index && !this.isACheckpoint() && !0 === this.actionList[this.index][2].implicit);
        AV.controlsWidgetInstance && AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "updateUndoRedo", [this.canUndo(), this.canRedo()])
    }
};
AV.Actions.prototype.undoFake = function() {
    var a;
    0 > this.index || this.isACheckpoint() || (AV.paintWidgetInstance && (AV.paintWidgetInstance.dirty = !0), 0 < this.index ? (a = this.actionList[this.index - 1][3], this.setDims(a.width, a.height)) : this.setDims(this._origHiresWidth, this._origHiresHeight), this.index--, AV.controlsWidgetInstance && AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "updateUndoRedo", [this.canUndo(), this.canRedo()]))
};
AV.Actions.prototype.undoToCheckpoint = function() {
    var a, b, f;
    if (!(0 > this.index)) {
        AV.paintWidgetInstance && (AV.paintWidgetInstance.dirty = !0);
        do a = this.actionList[this.index][0], b = a[0], f = a[1], a = a[2], b.apply(f, a), 0 < this.index ? (b = this.actionList[this.index - 1][3], this.setDims(b.width, b.height)) : this.setDims(this._origHiresWidth, this._origHiresHeight), this.index--;
        while (0 <= this.index && !this.isACheckpoint());
        AV.controlsWidgetInstance && AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "updateUndoRedo", [this.canUndo(), this.canRedo()])
    }
};
AV.Actions.prototype.canRedo = function() {
    return this.index < this.actionList.length - 1
};
AV.Actions.prototype.redo = function() {
    var a, b, f;
    if (!(this.index >= this.actionList.length - 1)) {
        do
            if (AV.paintWidgetInstance && (AV.paintWidgetInstance.dirty = !0), this.index++, a = this.actionList[this.index][1]) b = a[0], f = a[1], a = a[2], b.apply(f, a), b = this.actionList[this.index][3], this.setDims(b.width, b.height);
        while (this.index < this.actionList.length - 1 && !0 === this.actionList[this.index][2].implicit);
        AV.controlsWidgetInstance && AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "updateUndoRedo", [this.canUndo(), this.canRedo()])
    }
};
AV.Actions.prototype.redoFake = function() {
    var a;
    if (!(this.index >= this.actionList.length - 1)) {
        AV.paintWidgetInstance && (AV.paintWidgetInstance.dirty = !0);
        if (a = this.actionList[this.index + 1]) a = a[3], this.setDims(a.width, a.height);
        this.index++;
        AV.controlsWidgetInstance && AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "updateUndoRedo", [this.canUndo(), this.canRedo()])
    }
};
AV.Actions.prototype.redoToCheckpoint = function() {
    var a, b, f;
    f = !1;
    if (this.index >= this.actionList.length - 1) return !0;
    b = this.index;
    do b++, f = this.isACheckpoint(b);
    while (!f && b < this.actionList.length - 1);
    if (!f) return !1;
    AV.paintWidgetInstance && (AV.paintWidgetInstance.dirty = !0);
    do
        if (this.index++, a = this.actionList[this.index][1]) b = a[0], f = a[1], a = a[2], b.apply(f, a), b = this.actionList[this.index][3], this.setDims(b.width, b.height);
    while (this.index < this.actionList.length - 1 && !this.isACheckpoint());
    AV.controlsWidgetInstance && AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "updateUndoRedo", [this.canUndo(), this.canRedo()]);
    return !0
};
AV.Actions.prototype.push = function(a, b, f, j, l) {
    l = l || {};
    this.truncate(!0);
    j = j || this.getDims();
    this.actionList.push([a, b, l, j, f])
};
AV.Actions.prototype.setCheckpoint = function(a) {
    0 > this.index || (this.actionList[this.index][2].checkpoint = a)
};
AV.Actions.prototype.isACheckpoint = function(a) {
    a = void 0 !== a ? a : this.index;
    return this.actionList[a] ? !! this.actionList[a][2].checkpoint : !1
};
AV.Actions.prototype.isImplicit = function(a) {
    0 > this.index || (this.actionList[this.index][2].implicit = a)
};
AV.Actions.prototype.truncate = function(a) {
    this.actionList.length = this.index + 1;
    !a && AV.controlsWidgetInstance && AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "updateUndoRedo", [this.canUndo(), this.canRedo()])
};
AV.Actions.prototype.getDims = function() {
    return this.hiresWidth && this.hiresHeight ? {
        width: this.hiresWidth,
        height: this.hiresHeight
    } : AV.paintWidgetInstance ? {
        width: AV.paintWidgetInstance.width,
        height: AV.paintWidgetInstance.height
    } : null
};
AV.Actions.prototype.setDims = function(a, b) {
    if (this.hiresWidth != a || this.hiresHeight != b) this.hiresWidth = a, this.hiresHeight = b, AV.controlsWidgetInstance && AV.controlsWidgetInstance.imageSizeTracker.setImageScaledIndicator()
};
AV.Actions.prototype.setOrigSize = function(a, b) {
    this._origHiresWidth = a;
    this._origHiresHeight = b;
    this.setDims(a, b)
};
AV.Actions.prototype.exportJSON = function(a) {
    var b, f;
    if (AV.paintWidgetInstance) {
        var j = AV.paintWidgetInstance.getOrigSize();
        b = j.width;
        f = j.height
    }
    var j = {
            metadata: {
                imageorigsize: [b, f]
            }
        },
        l = [],
        d, e, h;
    AV.isRelaunched && AV.prevActionList ? (d = JSON.parse(AV.prevActionList)) && (l = d.actionlist.slice()) : actionList = [];
    l.push({
        action: "setfeathereditsize",
        width: b,
        height: f
    });
    for (b = 0; b < this.index + 1; b++) if (f = this.actionList[b], f = f[4]) if (void 0 == f.length) l.push(f);
    else
        for (d = 0; d < f.length; d++) l.push(f[d]);
    if (a) {
        d = {};
        a = [];
        a.length = l.length;
        for (b = 0; b < l.length; b++) switch (f = l[b], e = f.action, e) {
            case "addsticker":
            case "addtext":
                actionLayerId = f.id;
                d[actionLayerId] = b;
                a[b] = l[b];
                break;
            case "setsticker":
                actionLayerId = f.id;
                e = l[d[actionLayerId]];
                h = {};
                h.action = e.action;
                h.url = e.url;
                h.urls = e.urls;
                h.size = e.size;
                h.external = e.external;
                h.id = e.id;
                h.center = f.center;
                h.scale = f.scale;
                h.rotation = f.rotation;
                a[b] = h;
                break;
            case "settext":
                actionLayerId = f.id;
                e = l[d[actionLayerId]];
                h = {};
                h.action = e.action;
                h.id = e.id;
                h.text = e.text;
                h.font = e.font;
                h.size =
                    e.size;
                h.color = e.color;
                h.shadowcolor = e.shadowcolor;
                h.center = f.center;
                h.scale = f.scale;
                h.rotation = f.rotation;
                a[b] = h;
                break;
            default:
                a[b] = l[b]
        }
        l = {};
        for (b = a.length - 1; 0 <= b; b--) switch (f = a[b], e = f.action, e) {
            case "deletetext":
            case "deletesticker":
                actionLayerId = f.id;
                l[actionLayerId] = !0;
                a.splice(b, 1);
                break;
            case "addtext":
            case "addsticker":
                actionLayerId = f.id, l[actionLayerId] ? a.splice(b, 1) : l[actionLayerId] = !0
        }
        j.actionlist = a
    } else j.actionlist = l;
    return AV.JSON.stringify(j)
};
AV.Actions.prototype.importJSON = function(a) {
    if (!a || !AV.paintWidgetInstance) return !1;
    var b = [],
        f = this,
        j, l;
    if (a = AV.JSON.parse(a)) b = a.actionlist;
    l = b.length;
    var d = {},
        e;
    if (AV.paintWidgetInstance.filterManager && (a = AV.paintWidgetInstance.filterManager.getClickableFiltersForPack("original_effects"))) {
        e = a.length;
        for (j = 0; j < e; j++) 2 < a[j].length && (d[a[j][1]] = !0)
    }
    if (AV.toolDefaults && AV.toolDefaults.enhance) {
        a = AV.toolDefaults.enhance.presets;
        e = a.length;
        for (j = 0; j < e; j++) d[a[j]] = !0
    }
    var h = function(a) {
            a: {
                var l = a.action,
                    e;
                for (e in d) if (e === l) {
                    a.name = l;
                    a.action = "effects";
                    break a
                }
            }
            l = a.action;
            switch (l) {
                case "addsticker":
                    l = "overlay";
                    break;
                case "addtext":
                    l = "text";
                    break;
                case "rotate":
                    l = "straighten";
                    break;
                case "colortemp":
                    l = "warmth";
                    break;
                case "sharpness":
                    l = "sharpen";
                    break;
                case "redeye2":
                    l = "redeye";
                    break;
                case "whiten2":
                    l = "whiten";
                    break;
                case "selectiveblur":
                    l = "blemish"
            }
            AV.paintWidgetInstance.module[l] && AV.paintWidgetInstance.module[l].readAction ? (AV.paintWidgetInstance.setMode(l), AV.paintWidgetInstance.module[l].readAction.apply(this, [a, t])) : t()
        },
        t = function() {
            f.setCheckpoint(!0);
            j++;
            j < l ? h(b[j]) : AV.paintWidgetInstance.setMode(null)
        };
    j = 0;
    t();
    return !0
};
(function(a) {
    a.AV = a.AV || {};
    var b = a.AV;
    b.FilterManager = function() {
        var a = [],
            j = {},
            l = {},
            d = {},
            e = {},
            h = {},
            t = {},
            m = {},
            o = function(l) {
                var d, e = a.length;
                for (d = 0; d < e; d++) if (a[d] === l) return !1;
                a.push(l);
                return !0
            },
            g = function(a, b) {
                var k, g;
                if (b) {
                    for (k = 0; k < b.length; k++) if (g = [], b[k].apply(this, [j, l, d, h, e, g]), g.length) {
                        m[a] = g;
                        for (var o = 0; o < g.length; o++) t[g[o][1]] = g[o][2];
                        break
                    }
                    b.splice(0, k + 1)
                }
            };
        this.getEffectById = function(a) {
            return t[a]
        };
        this.getClickableFiltersForPack = function(a) {
            return m[a]
        };
        this.loadPack = function(a, l, d) {
            l && o(l) ? b.util.loadFile(l, "js", function() {
                g(a, b.filterPacks);
                d && d.call(this)
            }) : (g(a, b.filterPacks), d && d.call(this))
        };
        return this
    };
    return a
})(this);
(function(a) {
    a.AV = a.AV || {};
    var b = a.AV;
    b.OverlayRegistry = function() {
        var a = {},
            j = function(a, e) {
                var h, t;
                b.util.nextFrame(function() {
                    avpw$.ajax({
                        type: "GET",
                        dataType: "json",
                        url: b.build.jsonp_imgserver + "?callback=?",
                        data: {
                            url: escape(a)
                        },
                        success: function(b) {
                            t = document.createElement("img");
                            t.fullimageurl = a;
                            avpw$(t).attr("src", b.data);
                            h = function(b) {
                                avpw$(t).unbind("load", h);
                                l.addElement(a, t);
                                e && e.apply(this, [b])
                            };
                            avpw$(t).load(h)
                        }
                    })
                })
            },
            l = this;
        l.add = function(l, e) {
            a[l] || (a[l] = {});
            e && (a[l].hiresurl = e)
        };
        l.addElement =

            function(d, e) {
                a[d] || l.add(d);
                a[d].element = e
            };
        l.getElement = function(l, e) {
            var h = null;
            !a[l] || !a[l].element ? e && j(l, e) : (h = a[l].element, e && e.apply(this, [h]));
            return h
        };
        l.getHiRes = function(l) {
            return a[l].hiresurl
        };
        return l
    };
    return a
})(this);
var M = !0,
    P = !1;
(AV.filterPacks || (AV.filterPacks = [])).push(function(a, b, f) {
    function j(a) {
        this.state = "number" === typeof a && 0 <= a ? a : Math.floor(4294967295 * Math.random())
    }
    a.xc = function() {
        return [1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0]
    };
    a.wc = function() {
        for (var a = Math.random(), d = Math.random(); 0 === a;) a = Math.random();
        for (; 0 === d;) d = Math.random();
        return Math.sqrt(-2 * Math.log(a)) * Math.cos(2 * Math.PI * d)
    };
    a.vc = function() {
        var a = 0.5 - Math.random();
        return 0 > a ? Math.log(1 + 2 * a) : -Math.log(1 - 2 * a)
    };
    a.$ = function(a, d) {
        var e = Array(12);
        e[0] = a[0] * d[0] + a[1] * d[4] + a[2] * d[8];
        e[1] = a[0] * d[1] + a[1] * d[5] + a[2] * d[9];
        e[2] = a[0] * d[2] + a[1] * d[6] + a[2] * d[10];
        e[4] = a[4] * d[0] + a[5] * d[4] + a[6] * d[8];
        e[5] = a[4] * d[1] + a[5] * d[5] + a[6] * d[9];
        e[6] = a[4] * d[2] + a[5] * d[6] + a[6] * d[10];
        e[8] = a[8] * d[0] + a[9] * d[4] + a[10] * d[8];
        e[9] = a[8] * d[1] + a[9] * d[5] + a[10] * d[9];
        e[10] = a[8] * d[2] + a[9] * d[6] + a[10] * d[10];
        e[3] = a[0] * d[3] + a[1] * d[7] + a[2] * d[11] + a[3];
        e[7] = a[4] * d[3] + a[5] * d[7] + a[6] * d[11] + a[7];
        e[11] = a[8] * d[3] + a[9] * d[7] + a[10] * d[11] + a[11];
        for (var h = 0; 12 > h; h++) a[h] = e[h]
    };
    a.c = function(l, d, e) {
        var h = d.length,
            b, m, o, g = 0;
        if (0 === e[1] && 0 === e[2] && 0 === e[4] && 0 === e[6] && 0 === e[8] && 0 === e[9]) {
            h = [];
            b = [];
            m = [];
            for (o = 0; 256 > o; o++) h[o] = Math.max(0, Math.min(255, e[0] * o + e[3] + 0.5 | 0)), b[o] = Math.max(0, Math.min(255, e[5] * o + e[7] + 0.5 | 0)), m[o] = Math.max(0, Math.min(255, e[10] * o + e[11] + 0.5 | 0));
            a.b(l, d, [h, b, m])
        } else
            for (; g < h;) b = e[0] * d[g] + e[1] * d[g + 1] + e[2] * d[g + 2] + e[3] + 0.5 | 0, m = e[4] * d[g] + e[5] * d[g + 1] + e[6] * d[g + 2] + e[7] + 0.5 | 0, o = e[8] * d[g] + e[9] * d[g + 1] + e[10] * d[g + 2] + e[11] + 0.5 | 0, l[g] = 0 > b ? 0 : 255 < b ? 255 : b, l[g + 1] = 0 > m ? 0 : 255 < m ? 255 : m, l[g + 2] = 0 > o ? 0 : 255 < o ? 255 : o, l[g + 3] = d[g + 3], g += 4
    };
    a.ta = function(a) {
        var d = Array(3),
            e = Array(256),
            h = Array(256),
            b = Array(256),
            m, o;
        for (m = 0; 256 > m; m++) e[m] = 0, h[m] = 0, b[m] = 0;
        m = 0;
        for (o = a.length; m < o; m += 4) e[a[m]]++, h[a[m + 1]]++, b[a[m + 2]]++;
        d[0] = e;
        d[1] = h;
        d[2] = b;
        return d
    };
    a.ua = function(a) {
        var d = Array(256),
            e, h;
        for (e = 0; 256 > e; e++) d[e] = 0;
        e = 0;
        for (h = a.length; e < h; e++) d[a[e]]++;
        return d
    };
    a.w = function(a) {
        for (var d = 0, e = 0; 256 > e; e++) d += a[e];
        e = Array(256);
        e[0] = a[0] / d;
        for (var h = 1; 256 > h; h++) e[h] = a[h] / d + e[h - 1];
        return e
    };
    a.p = function(a, d) {
        if (0 >= d) return 0;
        if (1 <= d) return 255;
        for (var e =
            0; d > a[e];) e++;
        return 0 === e ? d / a[e] : e - 1 + (d - a[e - 1]) / (a[e] - a[e - 1])
    };
    a.Ba = function(a) {
        var d = 0.213 * (1 - a),
            e = 0.715 * (1 - a),
            h = 0.072 * (1 - a);
        return [d + a, e, h, 0, d, e + a, h, 0, d, e, h + a, 0]
    };
    a.ba = function(l, d, e, h, b) {
        a.$(l, [1 - b, 0, 0, d * b, 0, 1 - b, 0, e * b, 0, 0, 1 - b, h * b])
    };
    a.ca = function(l, d, e) {
        for (var h = [], b = [], m = 0; 128 > m; m++) h[m] = Math.round(127 * Math.pow(m / 127, e));
        for (m = 128; 256 > m; m++) h[m] = Math.round(127 + 127 * Math.pow((m - 128) / 127, 1 / e));
        b[0] = h;
        b[1] = h;
        b[2] = h;
        a.b(l, d, b)
    };
    a.rb = function(a, d, e, h, b) {
        for (var m = d - 0, o = (d - 0) / 2, g; 2 <= m;) {
            for (var n =
                0; n < d; n += m) g = n + o, a[g] = (a[n] + a[n + m]) / 2 + e * (b.h() - 0.5);
            e *= Math.pow(2, -h);
            m = o;
            o /= 2
        }
    };
    a.pb = function(a, d, e, h, b) {
        for (var m = d - 0, o = (d - 0) / 2, g; 2 <= m;) {
            for (var n = 0; n < d; n += m) g = n + o, a[g] = (a[n] + a[n + m]) / 2 + e * b.aa();
            e *= Math.pow(2, -h);
            m = o;
            o /= 2
        }
    };
    a.ob = function(a, d, e, h, b) {
        for (var m = d - 0, o = (d - 0) / 2, g; 2 <= m;) {
            for (var n = 0; n < d; n += m) g = n + o, a[g] = (a[n] + a[n + m]) / 2 + e * b.Pb();
            e *= Math.pow(2, -h);
            m = o;
            o /= 2
        }
    };
    a.l = function(l, d, e, h, b, m) {
        m = new a.m(m);
        a.qb(l, d, e, h, b, m)
    };
    a.Mb = function(a) {
        for (var d = 1; 2 * d <= a;) d *= 2;
        return d
    };
    a.Xb = function(a, d, e) {
        if (!(e <= d)) for (var d = e / d, h, b; 0 <= e; e--) h = e / d, b = h - (h | 0), a[e] = (1 - b) * a[h | 0] + b * a[(h | 0) + 1]
    };
    a.qb = function(l, d, e, h, b, m) {
        "undefined" === typeof b && (b = "uniform");
        var o = 0 + a.Mb(d - 0);
        l[o] = l[d];
        "uniform" === b ? a.rb(l, o, e, h, m) : "normal" === b ? a.pb(l, d, e, h, m) : "laplacian" === b && a.ob(l, d, e, h, m);
        a.Xb(l, o, d)
    };
    a.b = function(a, d, e) {
        for (var h = d.length, b = 0; b < h;) a[b] = e[0][d[b]], a[b + 1] = e[1][d[b + 1]], a[b + 2] = e[2][d[b + 2]], a[b + 3] = d[b + 3], b += 4
    };
    a.sa = function(a, d) {
        for (var e = d.length, h = 0, b = 0; h < e;) a[b] = 0.212671 * d[h] + 0.71516 * d[h + 1] + 0.072169 * d[h + 2] + 0.5 | 0, h += 4, b++
    };
    a.copy = function(a, d) {
        for (var e = d.length, h = 0; h < e; h += 4) a[h] = d[h], a[h + 1] = d[h + 1], a[h + 2] = d[h + 2], a[h + 3] = d[h + 3]
    };
    f.Rb = function(a, d, e, h) {
        for (var b, m, o, g, n = 0; n < e * h; n++) b = 4 * n, g = 12.75 * (2 * Math.random() - 1), m = d[b] + g | 0, o = d[b + 1] + g | 0, g = d[b + 2] + g | 0, m = 0 > m ? 0 : 255 < m ? 255 : m, o = 0 > o ? 0 : 255 < o ? 255 : o, g = 0 > g ? 0 : 255 < g ? 255 : g, a[b] = m, a[b + 1] = o, a[b + 2] = g, a[b + 3] = d[b + 3]
    };
    a.o = function(l, d) {
        var e = 0.213 * (1 - d),
            h = 0.715 * (1 - d),
            b = 0.072 * (1 - d);
        a.$(l, [e + d, h, b, 0, e, h + d, b, 0, e, h, b + d, 0])
    };
    a.ra = function(l, d, e) {
        "undefined" === typeof e && (e = 1);
        for (var h = [], b = [], m = 0; 256 > m; m++) h[m] = Math.round(255 * Math.pow(m / 255, e));
        h[0] = h[1];
        h[255] = h[254];
        b[0] = h;
        b[1] = h;
        b[2] = h;
        a.b(l, d, b)
    };
    a.Ca = function(a, d, e, h, b) {
        if (0 !== h) {
            var m = "undefined" !== typeof b,
                o, g = M;
            0 > h && (h = -h, g = P);
            for (var n = 4 * (h / 100), h = [], p = Math.ceil(2 * n), k = 2 * p + 1, q = 0, s = 0; s < k; s++) h[s] = Math.exp(-(p - s) * (p - s) / (2 * n * n)), q += h[s];
            var n = document.createElement("canvas").getContext("2d").createImageData(d, 1).data,
                r, f, u, w, v, j;
            for (r = 0; r < e; r++) {
                for (s = 0; s < d; s++) v = 4 * (r * d + s), n[4 * s] = a[v], n[4 * s + 1] = a[v + 1], n[4 * s + 2] = a[v + 2], n[4 * s + 3] = a[v + 3];
                for (s = 0; s < d; s++) if (!m || b[s + d * r]) {
                    v = 4 * (r * d + s);
                    j = 4 * (s - p);
                    w = u = f = 0;
                    if (s < p || s > d - 1 - p) for (o = 0; o < k; o++) j = 4 * Math.max(0, Math.min(d - 1, s + o - p)), f += h[o] * n[j], u += h[o] * n[j + 1], w += h[o] * n[j + 2];
                    else {
                        f += h[0] * n[j];
                        u += h[0] * n[j + 1];
                        w += h[0] * n[j + 2];
                        f += h[1] * n[j + 4];
                        u += h[1] * n[j + 5];
                        w += h[1] * n[j + 6];
                        f += h[2] * n[j + 8];
                        u += h[2] * n[j + 9];
                        w += h[2] * n[j + 10];
                        for (o = 3; o < k; o++) f += h[o] * n[j + 4 * o], u += h[o] * n[j + 4 * o + 1], w += h[o] * n[j + 4 * o + 2]
                    }
                    g ? m ? (o = b[s + d * r], f = (1 + 0.5 * o) * a[v] - o * f / q / 2 + 0.5 | 0, u = (1 + 0.5 * o) * a[v + 1] - o * u / q / 2 + 0.5 | 0, w = (1 + 0.5 * o) * a[v + 2] - o * w / q / 2 + 0.5 | 0) : (f = 1.5 * a[v] - f / q / 2 + 0.5 | 0, u = 1.5 * a[v + 1] - u / q / 2 + 0.5 | 0, w = 1.5 * a[v + 2] - w / q / 2 + 0.5 | 0) : m ? (o = b[s + d * r], f = (1 - o) * a[v] + o * f / q + 0.5 | 0, u = (1 - o) * a[v + 1] + o * u / q + 0.5 | 0, w = (1 - o) * a[v + 2] + o * w / q + 0.5 | 0) : (f = f / q + 0.5 | 0, u = u / q + 0.5 | 0, w = w / q + 0.5 | 0);
                    a[v] = 0 > f ? 0 : 255 < f ? 255 : f;
                    a[v + 1] = 0 > u ? 0 : 255 < u ? 255 : u;
                    a[v + 2] = 0 > w ? 0 : 255 < w ? 255 : w;
                    a[v + 3] = n[4 * s + 3]
                }
            }
        }
    };
    a.Da = function(a, d, e, h, b) {
        if (0 !== h) {
            var m = M;
            0 > h && (h = -h, m = P);
            for (var o = "undefined" !== typeof b, g, n = 4 * (h / 100), h = [], p = Math.ceil(2 * n), k = 2 * p + 1, q = 0, s = 0; s < k; s++) h[s] = Math.exp(-(p - s) * (p - s) / (2 * n * n)), q += h[s];
            for (var n = document.createElement("canvas").getContext("2d").createImageData(1, e).data, r, f, u, w, v, j, s = 0; s < d; s++) {
                for (r = 0; r < e; r++) v = 4 * (r * d + s), n[4 * r] = a[v], n[4 * r + 1] = a[v + 1], n[4 * r + 2] = a[v + 2], n[4 * r + 3] = a[v + 3];
                for (r = 0; r < e; r++) if (!o || b[s + d * r]) {
                    v = 4 * (r * d + s);
                    j = 4 * (r - p);
                    w = u = f = 0;
                    if (r < p || r > e - 1 - p) for (g = 0; g < k; g++) j = 4 * Math.max(0, Math.min(e - 1, r + g - p)), f += h[g] * n[j], u += h[g] * n[j + 1], w += h[g] * n[j + 2];
                    else {
                        f += h[0] * n[j];
                        u += h[0] * n[j + 1];
                        w += h[0] * n[j + 2];
                        f += h[1] * n[j + 4];
                        u += h[1] * n[j + 4 + 1];
                        w += h[1] * n[j + 4 + 2];
                        f += h[2] * n[j + 8];
                        u += h[2] * n[j + 8 + 1];
                        w += h[2] * n[j + 8 + 2];
                        for (g = 3; g < k; g++) f += h[g] * n[j + 4 * g], u += h[g] * n[j + 4 * g + 1], w += h[g] * n[j + 4 * g + 2]
                    }
                    m ? o ? (g = b[s + d * r], f = (1 + 0.5 * g) * a[v] - g * f / q / 2 + 0.5 | 0, u = (1 + 0.5 * g) * a[v + 1] - g * u / q / 2 + 0.5 | 0, w = (1 + 0.5 * g) * a[v + 2] - g * w / q / 2 + 0.5 | 0) : (f = 1.5 * a[v] - f / q / 2 + 0.5 | 0, u = 1.5 * a[v + 1] - u / q / 2 + 0.5 | 0, w = 1.5 * a[v + 2] - w / q / 2 + 0.5 | 0) : o ? (g = b[s + d * r], f = (1 - g) * a[v] + g * f / q + 0.5 | 0, u = (1 - g) * a[v + 1] + g * u / q + 0.5 | 0, w = (1 - g) * a[v + 2] + g * w / q + 0.5 | 0) : (f = f / q + 0.5 | 0, u = u / q + 0.5 | 0, w = w / q + 0.5 | 0);
                    a[v] = 0 > f ? 0 : 255 < f ? 255 : f;
                    a[v + 1] = 0 > u ? 0 : 255 < u ? 255 : u;
                    a[v + 2] = 0 > w ? 0 : 255 < w ? 255 : w;
                    a[v + 3] = n[4 * r + 3]
                }
            }
        }
    };
    a.Hb = function(l, d, e, h, b) {
        a.copy(l, d);
        a.Ca(l, e, h, b);
        a.Da(l, e, h, b)
    };
    a.fa = function(a, d, e, h) {
        for (var b, m = "left" === h, o = "right" === h, g = "bottom" === h, n = "top" === h, p = 0; p < e; p++) {
            g ? (b = 765 * (2 * p - e) / e + 0.5 | 0, b = 0 > b ? 0 : 255 < b ? 255 : b) : n && (b = 765 * (e - 2 * p) / e + 0.5 | 0, b = 0 > b ? 0 : 255 < b ? 255 : b);
            for (var k = 0; k < d; k++) h = 4 * (p * d + k), m ? (b = 765 * (d - 2 * k) / d + 0.5 | 0, b = 0 > b ? 0 : 255 < b ? 255 : b) : o && (b = 765 * (2 * k - d) / d + 0.5 | 0, b = 0 > b ? 0 : 255 < b ? 255 : b), a[h + 3] = b
        }
    };
    a.v = function(a, d, e, b, t) {
        for (var m, o, g, n, p = 0; p < b; p++) for (var k = 0; k < e; k++) n = 4 * (p * e + k), g = t * a[n + 3] / 255, m = (1 - g) * d[n] + g * a[n] + 0.5 | 0, o = (1 - g) * d[n + 1] + g * a[n + 1] + 0.5 | 0, g = (1 - g) * d[n + 2] + g * a[n + 2] + 0.5 | 0, m = 0 > m ? 0 : 255 < m ? 255 : m, o = 0 > o ? 0 : 255 < o ? 255 : o, g = 0 > g ? 0 : 255 < g ? 255 : g, a[n] = m, a[n + 1] = o, a[n + 2] = g, a[n + 3] = d[n + 3]
    };
    a.z = function(a, d, e, b, t, m, o, g, n) {
        "undefined" === typeof n && (n = 1);
        for (var p, k, q = 1 - n, s, r, f, j, w = 0; w < b; w++) {
            s = g ? b - 1 - (w - m) : w - m;
            s >= b ? s = b - 1 - (s - (b - 1)) : 0 > s && (s = -s);
            for (var v = 0; v < e; v++) p = 4 * (w * e + v), k = o ? e - 1 - (v - t) : v - t, k >= e ? k = e - 1 - (k - (e - 1)) : 0 > k && (k = -k), k = 4 * (s * e + k), r = n * d[k] + q * a[p] + 0.5 | 0, f = n * d[k + 1] + q * a[p + 1] + 0.5 | 0, j = n * d[k + 2] + q * a[p + 2] + 0.5 | 0, r = 0 > r ? 0 : 255 < r ? 255 : r, f = 0 > f ? 0 : 255 < f ? 255 : f, j = 0 > j ? 0 : 255 < j ? 255 : j, a[p] = r, a[p + 1] = f, a[p + 2] = j, a[p + 3] = d[k + 3]
        }
    };
    a.t = function(a, d, e, b, t, m, o) {
        var g;
        "undefined" === typeof g && (g = "all");
        for (var n, p, k, q, s = d / 2 | 0, r = e / 2 | 0, f = o, j = 1 - o, w = 0; w < e; w++) for (var v = 0; v < d; v++) q = 4 * (w * d + v), o = "left" === g && v > s ? 0 : "right" === g && v < s ? 0 : "bottom" === g && w < r ? 0 : "top" === g && v < r ? 0 : f, j = 1 - o, n = a[q], p = a[q + 1], k = a[q + 2], n = 128 >= n ? j * n + 2 * o * n * b / 256 + 0.5 | 0 : j * n + o * (255 - (255 - (2 * n - 256)) * (255 - b) / 256) + 0.5 | 0, p = 128 >= p ? j * p + 2 * o * p * t / 256 + 0.5 | 0 : j * p + o * (255 - (255 - (2 * p - 256)) * (255 - t) / 256) + 0.5 | 0, k = 128 >= k ? j * k + 2 * o * k * m / 256 + 0.5 | 0 : j * k + o * (255 - (255 - (2 * k - 256)) * (255 - m) / 256) + 0.5 | 0, n = 255 < n ? 255 : 0 > n ? 0 : n, p = 255 < p ? 255 : 0 > p ? 0 : p, k = 255 < k ? 255 : 0 > k ? 0 : k, a[q] = n, a[q + 1] = p, a[q + 2] = k
    };
    a.bb = function(l) {
        var d = Array(l || 0);
        if (1 < arguments.length) for (var e = Array.prototype.slice.call(arguments, 1), b = 0; b < l; b++) d[b] = a.bb(this, e);
        return d
    };
    a.Ea = function(l, d) {
        a.$(l, [d, 0, 0, 0, 0, d, 0, 0, 0, 0, d, 0])
    };
    a.Fa = function(l, d) {
        var e = d + 1,
            b = 255 * (-0.5 * e + 0.5);
        a.$(l, [e, 0, 0, b, 0, e, 0, b, 0, 0, e, b])
    };
    a.ja = function(l, d, e) {
        var b = [1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0],
            t = 128 * (1 - e);
        b[0] = e * d;
        b[5] = e * d;
        b[10] = e * d;
        b[3] = t * d;
        b[7] = t * d;
        b[11] = t * d;
        a.$(l, b)
    };
    a.ia = function(l, d, e, b) {
        for (var t = e * b, e = a.ta(d), b = 0.0050 * t, m = 0.995 * t, o, g, n, p, k, t = o = g = n = p = k = -1, q, s, r, f = q = s = r = 0; 256 > f; f++) q += e[0][f], s += e[1][f], r += e[2][f], 0 > t && q > b && (t = f), 0 > g && s > b && (g = f), 0 > p && r > b && (p = f), 0 > o && q > m && (o = f), 0 > n && s > m && (n = f), 0 > k && r > m && (k = f);
        e = [255 / (o - t), 0, 0, 0, 0, 255 / (n - g), 0, 0, 0, 0, 255 / (k - p), 0];
        a.c(l, d, [1, 0, 0, -t, 0, 1, 0, -g, 0, 0, 1, -p]);
        a.c(l, l, e)
    };
    a.xa = function(l, d, e, b) {
        var t = e * b,
            m = Array(t);
        a.sa(m, d);
        var o =
                a.ua(m),
            g = 0,
            n = Array(256),
            p;
        for (p = 0; 256 > p; p++) g += o[p], n[p] = g / t, 0 === p ? n[0] = 0 : n[p] = 255 * (n[p] / p), n[p] = Math.pow(n[p], 0.5 * Math.pow(1 - p / 255, 2.2));
        var k, q, s;
        for (p = 0; p < e * b; p++) {
            o = d[4 * p];
            g = d[4 * p + 1];
            k = d[4 * p + 2];
            t = m[p];
            s = n[t];
            var r = a.Ba(1 / s);
            q = o * r[0] + g * r[1] + k * r[2];
            t = o * r[4] + g * r[5] + k * r[6];
            o = o * r[8] + g * r[9] + k * r[10];
            q *= s;
            t *= s;
            o *= s;
            g = q + 0.5 | 0;
            t = t + 0.5 | 0;
            o = o + 0.5 | 0;
            255 < g ? g = 255 : 0 > g && (g = 0);
            255 < t ? t = 255 : 0 > t && (t = 0);
            255 < o ? o = 255 : 0 > o && (o = 0);
            l[4 * p] = g;
            l[4 * p + 1] = t;
            l[4 * p + 2] = o;
            l[4 * p + 3] = d[4 * p + 3]
        }
    };
    a.xb = function(l, d, e, b) {
        var t = e * b,
            m = Array(t);
        a.sa(m, d);
        var o = a.ua(m),
            g = 0,
            n = Array(256),
            p;
        for (p = 0; 256 > p; p++) g += o[p], n[p] = g / t, 0 === p ? n[0] = 0 : n[p] = 255 * (n[p] / p), n[p] = Math.pow(n[p], 0.65);
        var k, q, s;
        for (p = 0; p < e * b; p++) {
            o = d[4 * p];
            g = d[4 * p + 1];
            k = d[4 * p + 2];
            t = m[p];
            s = n[t];
            var r = a.Ba(1 / s);
            q = o * r[0] + g * r[1] + k * r[2];
            t = o * r[4] + g * r[5] + k * r[6];
            o = o * r[8] + g * r[9] + k * r[10];
            q *= s;
            t *= s;
            o *= s;
            g = q + 0.5 | 0;
            t = t + 0.5 | 0;
            o = o + 0.5 | 0;
            255 < g ? g = 255 : 0 > g && (g = 0);
            255 < t ? t = 255 : 0 > t && (t = 0);
            255 < o ? o = 255 : 0 > o && (o = 0);
            l[4 * p] = g;
            l[4 * p + 1] = t;
            l[4 * p + 2] = o;
            l[4 * p + 3] = d[4 * p + 3]
        }
    };
    a.nb = function(l, d, b) {
        var h = new a.m(0),
            t, m, o = [];
        for (m = 0; 256 > m; m++) o[m] = 0.04045 >= m / 255 ? 100 * m / 255 / 12.92 : 100 * Math.pow((m / 255 + 0.055) / 1.055, 2.4);
        var g = [],
            n;
        n = [];
        for (t = 0; 500 > t; t++) m = 4 * (Math.floor(1 * h.h() * d / 2 + 1 * d / 4) + d * Math.floor(1 * h.h() * b / 2 + 1 * b / 4)), n[0] = l[m], n[1] = l[m + 1], n[2] = l[m + 2], m = a.ga(n[0], n[1], n[2], o), g[t] = m;
        n = [];
        for (t = 0; 30 > t;) n[t] = g[t], t++;
        for (var d = M, b = 1, h = [], p; d && 40 > b;) {
            b++;
            d = P;
            for (t = 0; 500 > t; t++) {
                p = 195075E6;
                for (l = m = 0; 30 > l; l++) o = Math.pow(g[t][0] - n[l][0], 2) / 100 + Math.pow(g[t][1] - n[l][1], 2) + Math.pow(g[t][2] - n[l][2], 2), o < p && (p = o, m = l);
                "undefined" === typeof h[m] && (h[m] = []);
                h[m].push(t);
                n[m][3] = h[m].length
            }
            for (l = 0; 30 > l; l++) if (t = [0, 0, 0, n[l][3]], "undefined" === typeof h[l] || 0 == h[l].length) h[l] = [];
            else {
                for (m = 0; m < h[l].length; m++) o = g[h[l][m]], t[0] += o[0], t[1] += o[1], t[2] += o[2];
                h[l].length && (t[0] /= h[l].length, t[1] /= h[l].length, t[2] /= h[l].length);
                m = t;
                1 < Math.pow(n[l][0] - m[0], 2) && 1 < Math.pow(n[l][1] - m[1], 2) && 1 < Math.pow(n[l][2] - m[2], 2) && (d = M);
                n[l] = t
            }
        }
        var g = [],
            k, q, s;
        for (m = 0; 30 > m; m++) {
            for (t = b = d = 0; 30 > t; t++) {
                k = n[t][0];
                h = n[t][1];
                o = n[t][2];
                p = n[t][3];
                l = (1 - Math.exp(-Math.pow(h - 132, 2) / 100)) * (1 - Math.exp(-Math.pow(o - 147, 2) / 200));
                k *= l * (Math.pow(h - 127, 2) + Math.pow(o - 127, 2));
                s = 65025;
                for (l = 0; l < m; l++) q = Math.sqrt(Math.pow(g[l][1] - h, 2) + Math.pow(g[l][2] - o, 2)), q < s && (s = q);
                0 == m && (s = 1);
                l = s * k * Math.pow(p, -0.5);
                l > d && (d = l, b = t)
            }
            g[m] = n[b];
            n[b] = [0, 127, 127, 1]
        }
        t = [];
        for (m = 0; 30 > m; m++) n = a.la(g[m]), t[m] = n;
        return t
    };
    j.prototype.g = function() {
        return this.state = (69069 * this.state + 1) % 4294967296
    };
    j.prototype.h = function() {
        this.state = (69069 * this.state + 1) % 4294967296;
        return this.state / 4294967296
    };
    j.prototype.aa =

        function() {
            for (var a = this.h(), d = this.h(); 0 === a;) a = this.h();
            for (; 0 === d;) d = this.h();
            return Math.sqrt(-2 * Math.log(a)) * Math.cos(2 * Math.PI * d)
        };
    j.prototype.Pb = function() {
        var a = 0.5 - this.h();
        return 0 > a ? Math.log(1 + 2 * a) : -Math.log(1 - 2 * a)
    };
    a.m = j;
    a.j = function(a, d) {
        for (var b = 0; 256 > b; b++) a[0][b] = b * (1 - d) + a[0][b] * d + 0.5 | 0, a[1][b] = b * (1 - d) + a[1][b] * d + 0.5 | 0, a[2][b] = b * (1 - d) + a[2][b] * d + 0.5 | 0, a[0][b] = 0 > a[0][b] ? 0 : 255 < a[0][b] ? 255 : a[0][b], a[1][b] = 0 > a[1][b] ? 0 : 255 < a[1][b] ? 255 : a[1][b], a[2][b] = 0 > a[2][b] ? 0 : 255 < a[2][b] ? 255 : a[2][b];
        return a
    };
    a.pa = function(b, d, e, h, t, m) {
        var o = a.ta(d),
            g = a.w(o[0]),
            n = a.w(o[1]),
            p = a.w(o[2]),
            o = e * a.p(g, 0.0050),
            k = 255 * (1 - e) + e * a.p(g, 0.995),
            g = e * a.p(n, 0.0050),
            q = 255 * (1 - e) + e * a.p(n, 0.995),
            n = e * a.p(p, 0.0050),
            p = 255 * (1 - e) + e * a.p(p, 0.995);
        "undefined" !== typeof h && (k = Math.max(h, k) * e + k * (1 - e));
        "undefined" !== typeof t && (q = Math.max(t, q) * e + q * (1 - e));
        "undefined" !== typeof m && (p = Math.max(m, p) * e + p * (1 - e));
        e = [255 / (k - o), 0, 0, 0, 0, 255 / (q - g), 0, 0, 0, 0, 255 / (p - n), 0];
        a.c(b, d, [1, 0, 0, -o, 0, 1, 0, -g, 0, 0, 1, -n]);
        a.c(b, b, e)
    };
    a.i = function(a) {
        "undefined" === typeof a && (a = {});
        "undefined" === typeof a.border && (a.border = M);
        "undefined" === typeof a.intensity && (a.intensity = 1);
        "undefined" === typeof a.seed && (a.seed = 0);
        return a
    };
    a.qc = function(a) {
        return 1.9841269E-4 * (5040 + a * (5040 + a * (2520 + a * (840 + a * (210 + a * (42 + a * (7 + a)))))))
    };
    b.oa = function(a, b, e) {
        var h = 0.025,
            t = Math.max(b, e),
            m = Array(t),
            h = h * Math.min(b, e),
            o, g, n, p;
        o = 1 * h;
        g = 1 * h;
        p = 1 * h;
        n = 1 * h;
        var h = 1.5 * h,
            k, q, s, r, f;
        for (k = 0; k < t; k++) r = e * k / t, m[k] = r < n ? o + h : r < n + h ? o + h - Math.sqrt(h * h - (r - h - n) * (r - h - n)) : r < e - p - h ? o : r < e - p ? o + h - Math.sqrt(h * h - (r - e + p + h) * (r - e + p + h)) : o + h;
        for (r = 0; r < e; r++) if (q = t * r / e, k = m[Math.max(0, Math.min(t - 1, Math.floor(q)))], q = m[Math.max(0, Math.min(t - 1, Math.ceil(q)))], f = (k + q) / 2, 0 <= f && f < b) for (s = 0; s < f + 1.1; s++) k = 4 * (r * b + s), s < f - 1.1 ? (a[k] = 0, a[k + 1] = 0, a[k + 2] = 0) : (q = 1 - (s - f + 1.1) / 2.2, a[k] = 0 * q + (1 - q) * a[k] | 0, a[k + 1] = 0 * q + (1 - q) * a[k + 1] | 0, a[k + 2] = 0 * q + (1 - q) * a[k + 2] | 0);
        for (k = 0; k < t; k++) s = b * k / t, m[k] = s < b - g - h ? n : s < b - g ? n + h - Math.sqrt(h * h - (s - b + g + h) * (s - b + g + h)) : n + h;
        for (s = 0; s < b; s++) if (q = t * s / b, k = m[Math.max(0, Math.min(t - 1, Math.floor(q)))], q = m[Math.max(0, Math.min(t - 1, Math.ceil(q)))], f = (k + q) / 2, 0 <= f && f < e) for (r = 0; r < f + 1.1; r++) k = 4 * (r * b + s), r < f - 1.1 ? (a[k] = 0, a[k + 1] = 0, a[k + 2] = 0) : (q = 1 - (r - f + 1.1) / 2.2, a[k] = 0 * q + (1 - q) * a[k] | 0, a[k + 1] = 0 * q + (1 - q) * a[k + 1] | 0, a[k + 2] = 0 * q + (1 - q) * a[k + 2] | 0);
        for (k = 0; k < t; k++) s = b * k / t, m[k] = s < o ? e - p - h : s < o + h ? e - (p + h - Math.sqrt(h * h - (s - h - o) * (s - h - o))) : e - p;
        for (s = 0; s < b; s++) if (q = t * s / b, k = m[Math.max(0, Math.min(t - 1, Math.floor(q)))], q = m[Math.max(0, Math.min(t - 1, Math.ceil(q)))], f = (k + q) / 2, 0 <= f && f < e) for (r = e - 1; r > f - 1.1; r--) k = 4 * (r * b + s), r > f + 1.1 ? (a[k] = 0, a[k + 1] = 0, a[k + 2] = 0) : (q = 1 - (f - r + 1.1) / 2.2, a[k] = 0 * q + (1 - q) * a[k] | 0, a[k + 1] = 0 * q + (1 - q) * a[k + 1] | 0, a[k + 2] = 0 * q + (1 - q) * a[k + 2] | 0);
        for (k = 0; k < t; k++) r = e * k / t, m[k] = r < e - p - h ? b - g : r < e - p ? b - (g + h - Math.sqrt(h * h - (r - e + h + p) * (r - e + h + p))) : b - g - h;
        for (r = 0; r < e; r++) if (q = t * r / e, k = m[Math.max(0, Math.min(t - 1, Math.floor(q)))], q = m[Math.max(0, Math.min(t - 1, Math.ceil(q)))], f = (k + q) / 2, 0 <= f && f < b) for (s = b - 1; s > f - 1.1; s--) k = 4 * (r * b + s), s > f + 1.1 ? (a[k] = 0, a[k + 1] = 0, a[k + 2] = 0) : (q = 1 - (f - s + 1.1) / 2.2, a[k] = 0 * q + (1 - q) * a[k] | 0, a[k + 1] = 0 * q + (1 - q) * a[k + 1] | 0, a[k + 2] = 0 * q + (1 - q) * a[k + 2] | 0)
    };
    b.k = function(a, b, e, h, t, m, o, g) {
        var n;
        "number" !== typeof n && (n = 1);
        for (var t = 1 / (Math.pow(t, 2) / 1.15), p, k, q, f, r, j, u, w = e / 2 | 0, v = h / 2 | 0, y = 5 / 3, C = 34 / 45, z = 13 / 63, D = 514 / 14175, A = 0; A < h; A++) {
            k = 4 * A * e;
            q = (A - v) * (A - v);
            for (var G = 0; G < e; G++) p = k + 4 * G, f = ((G - w) * (G - w) + q) * t, 1.15 < f ? f = 0 : 0.04 > f ? f = 1 : (f *= f, r = f * f, j = r * f, u = j * f, f = 1 - 2 * f + y * r - C * j + z * u - D * u * f), f = 0 > f ? 0 : 1 < f ? 1 : f, a[p] = b[p] * f + m * (1 - f) + 0.5 | 0, a[p + 1] = b[p + 1] * f + o * (1 - f) + 0.5 | 0, a[p + 2] = b[p + 2] * f + g * (1 - f) + 0.5 | 0, a[p + 3] = b[p + 3] * f + 255 * n * (1 - f) + 0.5 | 0
        }
    };
    b.wa = function(a, b, e, h, f, m, o) {
        "undefined" === typeof m && (m = 1);
        "undefined" === typeof o && (o = 0);
        for (var g = "smooth" === f, n = "cosine" === f, p = "stamp" === f, k = "halftone" === f, f = "lines" === f, q, s, r, j, u, w = m * (e + h) / 30, v = h / 2 | 0, y = e / 2 | 0, C = 0; C < h; C++) {
            m = 4 * C * e;
            j = C < v ? C - o : h - C - o;
            for (var z = 0; z < e; z++) q = m + 4 * z, r = z < y ? z - o : e - z - o, 0 > j || 0 > r ? (a[q] = 0, a[q + 1] = 0, a[q + 2] = 0) : (u = j < Math.SQRT2 * w && r < Math.SQRT2 * w ? Math.SQRT2 * w - Math.sqrt((j - Math.SQRT2 * w) * (j - Math.SQRT2 * w) + (r - Math.SQRT2 * w) * (r - Math.SQRT2 * w)) : j < r ? j : r, u < w ? (s = g || n || p ? 1 : 0.4 * Math.random() + 0.6, k ? s *= Math.cos(200 * Math.PI * (C / h)) * Math.cos(200 * Math.PI * (z / e)) + 1 : f && (s *= Math.cos(140 * Math.PI * (C / h)) + 1), j < r ? n ? s *= 0.5 * Math.cos(60 * Math.PI * z / (e + h)) + 1.5 : p && (s *= 0.5 * Math.sin(60 * Math.PI * z / (e + h)) + 0.5) : n ? s *= 0.5 * Math.cos(60 * Math.PI * C / (e + h)) + 1.5 : p && (s *= 0.5 * Math.sin(60 * Math.PI * C / (e + h)) + 0.5), r = 1 - 2 * s * Math.pow(1 - u / w, 4), p && (r = 1 - 2 * s * Math.pow(1 - 0.5 * u / w, 4)), r = 0 > r ? 0 : 1 < r ? 1 : r, a[q] = b[q] * r + 0 * (1 - r) + 0.5 | 0, a[q + 1] = b[q + 1] * r + 0 * (1 - r) + 0.5 | 0, a[q + 2] = b[q + 2] * r + 0 * (1 - r) + 0.5 | 0) : (a[q] = b[q], a[q + 1] = b[q + 1], a[q + 2] = b[q + 2])), a[q + 3] = b[q + 3]
        }
    };
    b.sc = function(a, b, e, h, f, m, o, g) {
        for (var n = "fade" === f, f = "shadow" === f, p, k, q, s, r, j = (e + h) / 80, u = h / 2 | 0, w = e / 2 | 0, v = 0; v < h; v++) {
            p = 4 * v * e;
            s = v < u ? v : h - v;
            for (var y = 0; y < e; y++) k = p + 4 * y, q = y < w ? y : e - y, q = s < Math.PI * j && q < Math.PI * j ? Math.max(0, Math.PI * j - Math.sqrt((s - Math.PI * j) * (s - Math.PI * j) + (q - Math.PI * j) * (q - Math.PI * j))) : q < s ? q : s, q < j ? (n ? r = q * q / j / j : f && (b[k] = 0, b[k + 1] = 0, b[k + 2] = 0, r = Math.pow(q / j - 0.2, 2)), r = 0 > r ? 0 : 1 < r ? 1 : r, a[k] = b[k] * r + m * (1 - r) + 0.5 | 0, a[k + 1] = b[k + 1] * r + o * (1 - r) + 0.5 | 0, a[k + 2] = b[k + 2] * r + g * (1 - r) + 0.5 | 0) : (a[k] = b[k], a[k + 1] = b[k + 1], a[k + 2] = b[k + 2]), a[k + 3] = b[k + 3]
        }
    };
    b.kc = function(a, b, e, h, f, m, o, g) {
        var n = [0, 0, 0, 1];
        "undefined" === typeof f && (f = 0);
        "undefined" === typeof m && (m = 0);
        "undefined" === typeof o && (o = 0);
        "undefined" === typeof g && (g = 0);
        for (var p = n[0], k = n[1], q = n[2], n = n[3], s, r, j, u = Math.sqrt(2), w = h / 2 | 0, v = e / 2 | 0, y = (e + h) / 60, C = 0; C < h; C++) for (var z = 0; z < e; z++) j = 4 * C * e + 4 * z, r = C < w ? C - f : h - C - m, s = z < v ? z - o : e - z - g, 0 > r || 0 > s ? (s = 1 - n, a[j] = b[j] * s + p * (1 - s) + 0.5 | 0, a[j + 1] = b[j + 1] * s + k * (1 - s) + 0.5 | 0, a[j + 2] = b[j + 2] * s + q * (1 - s) + 0.5 | 0, a[j + 3] = b[j + 3]) : (s = r < u * y && s < u * y ? u * y - Math.sqrt((r - u * y) * (r - u * y) + (s - u * y) * (s - u * y)) : s < r ? s : r, s < y && (r = 0.4 * Math.random() + 0.6, s = 1 - 2 * r * Math.pow(1 - s / y, 4), 1 < s ? s = 1 : 0 > s && (s = 0), s = 1 - n + n * s, a[j] = b[j] * s + p * (1 - s) + 0.5 | 0, a[j + 1] = b[j + 1] * s + k * (1 - s) + 0.5 | 0, a[j + 2] = b[j + 2] * s + q * (1 - s) + 0.5 | 0, a[j + 3] = b[j + 3]))
    };
    b.ub = function(a, d, e) {
        b.u(a, a, d, e, [0, 0, 0, 1]);
        b.k(a, a, d, e, 1.1 * ((d + e) / 2), 0, 0, 150);
        b.qa(a, d, e, 0.05)
    };
    b.Cc = function(b, d, e, h, f, m, o, g, n) {
        n || (n = "uniform");
        var d = g[0],
            p = g[1],
            k = g[2],
            g = g[3],
            q = Array(512),
            m = m * Math.min(e, h),
            f = f * Math.min(e, h),
            s = Math.max(1, 0.0030 * Math.min(e, h));
        q[511] = m;
        q[0] = m;
        a.l(q, 511, f, o, n);
        var r, j, u, w, v;
        for (j = 0; j < h; j++) if (u = 512 * j / h, w = q[Math.max(0, Math.min(511, Math.floor(u)))], u = q[Math.max(0, Math.min(511, Math.ceil(u)))], w = (w + u) / 2, 0 <= w && w < e) for (r = 0; r < w + s; r++) u = 4 * (j * e + r), r < w - s ? (b[u] = g * d + (1 - g) * b[u] | 0, b[u + 1] = g * p + (1 - g) * b[u + 1] | 0, b[u + 2] = g * k + (1 - g) * b[u + 2] | 0) : (v = g * (1 - (r - w + s) / (2 * s)), b[u] = v * d + (1 - v) * b[u] | 0, b[u + 1] = v * p + (1 - v) * b[u + 1] | 0, b[u + 2] = v * k + (1 - v) * b[u + 2] | 0);
        q[511] = m;
        q[0] = m;
        a.l(q, 511, f, o, n);
        for (r = 0; r < e; r++) if (u = 512 * r / e, w = q[Math.max(0, Math.min(511, Math.floor(u)))], u = q[Math.max(0, Math.min(511, Math.ceil(u)))], w = (w + u) / 2, 0 <= w && w < h) for (j = 0; j < w + s; j++) u = 4 * (j * e + r), j < w - s ? (b[u] =
            g * d + (1 - g) * b[u] | 0, b[u + 1] = g * p + (1 - g) * b[u + 1] | 0, b[u + 2] = g * k + (1 - g) * b[u + 2] | 0) : (v = g * (1 - (j - w + s) / (2 * s)), b[u] = v * d + (1 - v) * b[u] | 0, b[u + 1] = v * p + (1 - v) * b[u + 1] | 0, b[u + 2] = v * k + (1 - v) * b[u + 2] | 0);
        q[511] = h - m - 1;
        q[0] = h - m - 1;
        a.l(q, 511, f, o, n);
        for (r = 0; r < e; r++) if (u = 512 * r / e, w = q[Math.max(0, Math.min(511, Math.floor(u)))], u = q[Math.max(0, Math.min(511, Math.ceil(u)))], w = (w + u) / 2, 0 <= w && w < h) for (j = h - 1; j > w - s; j--) u = 4 * (j * e + r), j > w + s ? (b[u] = g * d + (1 - g) * b[u] | 0, b[u + 1] = g * p + (1 - g) * b[u + 1] | 0, b[u + 2] = g * k + (1 - g) * b[u + 2] | 0) : (v = g * (1 - (w - j + s) / (2 * s)), b[u] = v * d + (1 - v) * b[u] | 0, b[u + 1] = v * p + (1 - v) * b[u + 1] | 0, b[u + 2] = v * k + (1 - v) * b[u + 2] | 0);
        q[511] = e - m - 1;
        q[0] = e - m - 1;
        a.l(q, 511, f, o, n);
        for (j = 0; j < h; j++) if (u = 512 * j / h, w = q[Math.max(0, Math.min(511, Math.floor(u)))], u = q[Math.max(0, Math.min(511, Math.ceil(u)))], w = (w + u) / 2, 0 <= w && w < e) for (r = e - 1; r > w - s; r--) u = 4 * (j * e + r), r > w + s ? (b[u] = g * d + (1 - g) * b[u] | 0, b[u + 1] = g * p + (1 - g) * b[u + 1] | 0, b[u + 2] = g * k + (1 - g) * b[u + 2] | 0) : (v = g * (1 - (w - r + s) / (2 * s)), b[u] = v * d + (1 - v) * b[u] | 0, b[u + 1] = v * p + (1 - v) * b[u + 1] | 0, b[u + 2] = v * k + (1 - v) * b[u + 2] | 0)
    };
    b.jc = function(b, d, e, h, f) {
        var m = Array(Math.max(d, e)),
            o =
                Array(Math.max(d, e)),
            f = f * Math.min(d, e),
            h = h * Math.min(d, e),
            g = Math.max(1, 0.0030 * Math.min(d, e));
        m[e - 1] = f + 0.5 * h * (Math.random() - 0.5);
        m[0] = f + 0.5 * h * (Math.random() - 0.5);
        o[e - 1] = f + 0.5 * h * (Math.random() - 0.5);
        o[0] = f + 0.5 * h * (Math.random() - 0.5);
        a.l(m, e - 1, h, 0.5);
        a.l(o, e - 1, h, 0.5);
        var n, p, k, q, s, r;
        for (p = 0; p < e; p++) {
            s = m[p];
            r = o[p];
            for (n = 0; n < s + g; n++) k = 4 * (p * d + n), n < s - g && n > r + g ? (b[k] = 0 + 0 * b[k] | 0, b[k + 1] = 0 + 0 * b[k + 1] | 0, b[k + 2] = 0 + 0 * b[k + 2] | 0) : (q = 1 * Math.min((s - n) / g, (n - r) / g), 1 < q ? q = 1 : 0 > q && (q = 0), b[k] = 0 * q + (1 - q) * b[k] | 0, b[k + 1] = 0 * q + (1 - q) * b[k + 1] | 0, b[k + 2] = 0 * q + (1 - q) * b[k + 2] | 0)
        }
        m[d - 1] = f + 0.5 * h * (Math.random() - 0.5);
        m[0] = f + 0.5 * h * (Math.random() - 0.5);
        o[d - 1] = f + 0.5 * h * (Math.random() - 0.5);
        o[0] = f + 0.5 * h * (Math.random() - 0.5);
        a.l(m, d - 1, h, 0.5);
        a.l(o, d - 1, h, 0.5);
        for (n = 0; n < d; n++) {
            s = m[n];
            r = o[n];
            for (p = 0; p < s + g || p < r + g; p++) k = 4 * (p * d + n), p < s - g && p > r + g ? (b[k] = 0 + 0 * b[k] | 0, b[k + 1] = 0 + 0 * b[k + 1] | 0, b[k + 2] = 0 + 0 * b[k + 2] | 0) : (q = 1 * Math.min((s - p) / g, (p - r) / g), 1 < q ? q = 1 : 0 > q && (q = 0), b[k] = 0 * q + (1 - q) * b[k] | 0, b[k + 1] = 0 * q + (1 - q) * b[k + 1] | 0, b[k + 2] = 0 * q + (1 - q) * b[k + 2] | 0)
        }
        m[d - 1] = e - f + 0.5 * h * (Math.random() - 0.5);
        m[0] = e - f + 0.5 * h * (Math.random() - 0.5);
        o[d - 1] = e - f + 0.5 * h * (Math.random() - 0.5);
        o[0] = e - f + 0.5 * h * (Math.random() - 0.5);
        a.l(m, d - 1, h, 0.5);
        a.l(o, d - 1, h, 0.5);
        for (n = 0; n < d; n++) {
            s = m[n];
            r = o[n];
            for (p = e - 1; p > r - g; p--) k = 4 * (p * d + n), p > r + g && p < s - g ? (b[k] = 0 + 0 * b[k] | 0, b[k + 1] = 0 + 0 * b[k + 1] | 0, b[k + 2] = 0 + 0 * b[k + 2] | 0) : (q = 1 * Math.min((s - p) / g, (p - r) / g), 1 < q ? q = 1 : 0 > q && (q = 0), b[k] = 0 * q + (1 - q) * b[k] | 0, b[k + 1] = 0 * q + (1 - q) * b[k + 1] | 0, b[k + 2] = 0 * q + (1 - q) * b[k + 2] | 0)
        }
        m[e - 1] = d - f + 0.5 * h * (Math.random() - 0.5);
        m[0] = d - f + 0.5 * h * (Math.random() - 0.5);
        o[e - 1] = d - f + 0.5 * h * (Math.random() - 0.5);
        o[0] = d - f + 0.5 * h * (Math.random() - 0.5);
        a.l(m, e - 1, h, 0.5);
        a.l(o, e - 1, h, 0.5);
        for (p = 0; p < e; p++) {
            s = m[p];
            r = o[p];
            for (n = d - 1; n > r - g; n--) k = 4 * (p * d + n), n > r + g && n < s - g ? (b[k] = 0 + 0 * b[k] | 0, b[k + 1] = 0 + 0 * b[k + 1] | 0, b[k + 2] = 0 + 0 * b[k + 2] | 0) : (q = 1 * Math.min((s - n) / g, (n - r) / g), 1 < q ? q = 1 : 0 > q && (q = 0), b[k] = 0 * q + (1 - q) * b[k] | 0, b[k + 1] = 0 * q + (1 - q) * b[k + 1] | 0, b[k + 2] = 0 * q + (1 - q) * b[k + 2] | 0)
        }
    };
    b.za = function(a, b, e, h, f) {
        for (var m = h / 2 | 0, o = e / 2 | 0, g, n, p, k, q = 0; q < h; q++) {
            n = 4 * q * e;
            k = q < m ? q : h - 1 - q;
            for (var s = 0; s < e; s++) g = n + 4 * s, p = s < o ? s : e - 1 - s, p = p < f || k < f ? 0 : 1, a[g] = b[g] * p + 0 * (1 - p), a[g + 1] = b[g + 1] * p + 0 * (1 - p), a[g + 2] = b[g + 2] * p + 0 * (1 - p), a[g + 3] = b[g + 3]
        }
    };
    b.tb = function(a, d, e, h) {
        b.u(a, d, e, h, [0, 0, 0, 1]);
        b.k(a, a, e, h, 1.1 * ((e + h) / 2), 0, 0, 100)
    };
    b.va = function(l, d, e, h, f) {
        h = new a.m(h);
        b.d(l, d, e, [0, 0, 0, 0.2], "torn", [0.03 * f, 0.02, 0.2, "uniform", h.g()]);
        b.d(l, d, e, [0, 0, 0, 0.8], "torn", [0.02 * f, 0.01, 0.5, "uniform", h.g()]);
        b.u(l, l, d, e, {
            color: [0, 0, 0, 1],
            na: -(d + e) / 150,
            ka: 0,
            scale: 0.5,
            ha: h.g(),
            alpha: f
        })
    };
    b.u = function(b, d, e, h, f) {
        "undefined" === typeof f.offset && (f.offset = 0);
        "undefined" === typeof f.corner && (f.corner =
            0);
        "undefined" === typeof f.scale && (f.scale = 1);
        "undefined" === typeof f.seed && (f.seed = 0);
        "undefined" === typeof f.alpha && (f.alpha = 1);
        "undefined" === typeof f.color && (f.color = [0, 0, 0, 1]);
        var m = f.offset,
            o = f.corner,
            g = f.scale,
            n = f.color,
            p = f.alpha,
            f = new a.m(f.seed),
            k = n[0],
            q = n[1],
            s = n[2],
            n = n[3] * p,
            r = Array(h),
            j = Array(h),
            u = Array(e),
            w = Array(e);
        r[h - 1] = o;
        r[0] = 0;
        j[h - 1] = 0;
        j[0] = 0;
        u[e - 1] = 0;
        u[0] = 0;
        w[e - 1] = 0;
        w[0] = o;
        a.l(r, h - 1, 0.03 * e * p, 0.7, "uniform", f.g());
        a.l(j, h - 1, 0.03 * e * p, 0.7, "uniform", f.g());
        a.l(u, e - 1, 0.03 * h * p, 0.7, "uniform", f.g());
        a.l(w, e - 1, 0.03 * h * p, 0.7, "uniform", f.g());
        var v, y, p = h / 2 | 0,
            C = e / 2 | 0,
            z = (e + h) / 30,
            D, A;
        for (A = 0; A < h; A++) for (D = 0; D < e; D++) o = 4 * A * e + 4 * D, y = A < p ? A - u[D] - m : h - A - w[D] - m, v = D < C ? D - r[A] - m : e - D - j[A] - m, 0 > y || 0 > v ? (v = 1 - n, b[o] = d[o] * v + k * (1 - v) | 0, b[o + 1] = d[o + 1] * v + q * (1 - v) | 0, b[o + 2] = d[o + 2] * v + s * (1 - v) | 0, b[o + 3] = d[o + 3]) : (v = y < Math.SQRT2 * z && v < Math.SQRT2 * z ? Math.SQRT2 * z - Math.sqrt((y - Math.SQRT2 * z) * (y - Math.SQRT2 * z) + (v - Math.SQRT2 * z) * (v - Math.SQRT2 * z)) : v < y ? v : y, v < z && (y = 2 * Math.pow(1 - v / z, 4 * g), y > 1 / 255 && (v = 0.4 * f.h() + 0.6, v = 1 - v * y, 1 < v ? v = 1 : 0 > v && (v =
            0), v = 1 - n + n * v, b[o] = d[o] * v + k * (1 - v) | 0, b[o + 1] = d[o + 1] * v + q * (1 - v) | 0, b[o + 2] = d[o + 2] * v + s * (1 - v) | 0, b[o + 3] = d[o + 3])))
    };
    b.qa = function(a, b, e, h) {
        var h = h * ((b + e) / 2),
            f = Array(b),
            m = Array(e),
            o = 0.5 * h,
            g, n, p, k, q;
        for (g = 0; g < e; g++) m[g] = 0.28 * -h * Math.sin(g * Math.PI / e) + o;
        for (p = 0; p < e; p++) if (q = m[p], 0 <= q && q < b) for (n = 0; n < q + 1; n++) g = 4 * (p * b + n), n < q - 1 ? (a[g] = 0, a[g + 1] = 0, a[g + 2] = 0) : (k = 1 - (n - q + 1) / 2, a[g] = 0 * k + (1 - k) * a[g] | 0, a[g + 1] = 0 * k + (1 - k) * a[g + 1] | 0, a[g + 2] = 0 * k + (1 - k) * a[g + 2] | 0);
        for (g = 0; g < b; g++) f[g] = 0.28 * -h * Math.sin(g * Math.PI / b) + o;
        for (n = 0; n < b; n++) if (q =
            f[n], 0 <= q && q < e) for (p = 0; p < q + 1; p++) g = 4 * (p * b + n), p < q - 1 ? (a[g] = 0, a[g + 1] = 0, a[g + 2] = 0) : (k = 1 - (p - q + 1) / 2, a[g] = 0 * k + (1 - k) * a[g] | 0, a[g + 1] = 0 * k + (1 - k) * a[g + 1] | 0, a[g + 2] = 0 * k + (1 - k) * a[g + 2] | 0);
        for (g = 0; g < b; g++) f[g] = e - 1 + 0.28 * h * Math.sin(g * Math.PI / b) - o;
        for (n = 0; n < b; n++) if (q = f[n], 0 <= q && q < e) for (p = e - 1; p > q - 1; p--) g = 4 * (p * b + n), p > q + 1 ? (a[g] = 0, a[g + 1] = 0, a[g + 2] = 0) : (k = 1 - (q - p + 1) / 2, a[g] = 0 * k + (1 - k) * a[g] | 0, a[g + 1] = 0 * k + (1 - k) * a[g + 1] | 0, a[g + 2] = 0 * k + (1 - k) * a[g + 2] | 0);
        for (g = 0; g < e; g++) m[g] = b - 1 + 0.28 * h * Math.sin(g * Math.PI / e) - o;
        for (p = 0; p < e; p++) if (q = m[p], 0 <= q && q < b) for (n = b - 1; n > q - 1; n--) g = 4 * (p * b + n), n > q + 1 ? (a[g] = 0, a[g + 1] = 0, a[g + 2] = 0) : (k = 1 - (q - n + 1) / 2, a[g] = 0 * k + (1 - k) * a[g] | 0, a[g + 1] = 0 * k + (1 - k) * a[g + 1] | 0, a[g + 2] = 0 * k + (1 - k) * a[g + 2] | 0)
    };
    b.r = function(a, b, e, h, f, m, o) {
        var g, n, p, k, q, j, r, x, u, w, v = 1;
        g = (e + h) / 30;
        n = 0;
        var y = h / 2 | 0,
            C = e / 2 | 0,
            z = f[0],
            D = f[1],
            A = f[2],
            f = 255 * f[3],
            m = m || "",
            G, J, K, N, O;
        G = J = K = N = O = P;
        "halftone" === m ? (G = M, g = (e + h) / 30, n = o[0], j = 1E3 / (e + h), "number" == typeof o[1] && (j = 0 === o[1] ? 0 : j / o[1])) : "smooth" == m ? (K = M, g = o[0] * (e + h) / 30) : "lines" == m ? (J = M, g = o[0] * (e + h) / 30, w = o[1]) : "shadow" == m ? (N = M, g =
            o[0] * (e + h) / 80, n = 0.3, r = Math.PI / 180 * o[1], x = Math.sin(r), r = 0.5 * Math.cos(r) * g, x = 0.5 * x * g) : "fade" == m && (O = M, g = (e + h) / 80, "number" == typeof o[0] && (g *= o[0]), n = "number" == typeof o[1] ? o[1] : 1, "number" == typeof o[2] && (v = o[2]));
        n *= g;
        for (var L = 0; L < h; L++) for (var o = L < y ? L - n : h - 1 - L - n, I = 0; I < e; I++) if (m = 4 * (e * L + I), p = I < C ? I - n : e - 1 - I - n, q = o < Math.SQRT2 * v * g && p < Math.SQRT2 * v * g ? Math.SQRT2 * v * g - Math.sqrt((o - Math.SQRT2 * v * g) * (o - Math.SQRT2 * v * g) + (p - Math.SQRT2 * v * g) * (p - Math.SQRT2 * v * g)) : o < p ? o : p, 0 > o || 0 > p) G ? (k = Math.cos((L - y) * j) * Math.cos((I - C) * j) + 1, k = 1 - 2 * k, k = 0 > k ? 0 : 1 < k ? 1 : k, a[m] = b[m] * k + z * (1 - k) + 0.5 | 0, a[m + 1] = b[m + 1] * k + D * (1 - k) + 0.5 | 0, a[m + 2] = b[m + 2] * k + A * (1 - k) + 0.5 | 0, a[m + 3] = b[m + 3] * k + f * (1 - k) + 0.5 | 0) : N ? (q = L < y ? L - n - x : h - 1 - L - n + x, u = I < C ? I - n - r : e - 1 - I - n + r, q = q < Math.SQRT2 * v * g && u < Math.SQRT2 * v * g ? Math.SQRT2 * v * g - Math.pow(Math.pow(q - Math.SQRT2 * v * g, 4) + Math.pow(u - Math.SQRT2 * v * g, 4), 0.25) : q < u ? q : u, q < -g && (q = -g), k = 0.6 * Math.pow((q / g + 1) / 2, 4), k = 0 > k ? 0 : 1 < k ? 1 : k, a[m] = z * (1 - k) + 0.5 | 0, a[m + 1] = D * (1 - k) + 0.5 | 0, a[m + 2] = A * (1 - k) + 0.5 | 0, a[m + 3] = f * (1 - k) + k * b[m + 3] + 0.5 | 0) : (a[m] = z, a[m + 1] = D, a[m + 2] = A, a[m + 3] = f);
        else if (q < g) {
            if (G) k = Math.cos((L - y) * j) * Math.cos((I - C) * j) + 1, k = 0 >= q ? 1 - 2 * k : 1 - 2 * k * Math.pow(1 - q / g, 4);
            else if (N) {
                q = L < y ? L - n - x : h - 1 - L - n + x;
                u = I < C ? I - n - r : e - 1 - I - n + r;
                q = q < Math.SQRT2 * v * g && u < Math.SQRT2 * v * g ? Math.SQRT2 * v * g - Math.pow(Math.pow(q - Math.SQRT2 * v * g, 4) + Math.pow(u - Math.SQRT2 * v * g, 4), 0.25) : q < u ? q : u;
                if (p >= g && o >= g) continue;
                q < -g && (q = -g);
                q > g && (q = Math.sqrt(q / g) * g);
                k = 0.6 * Math.pow((q / g + 1) / 2, 4);
                k = 0 > k ? 0 : 1 < k ? 1 : k;
                a[m] = z * (1 - k) + 0.5 | 0;
                a[m + 1] = D * (1 - k) + 0.5 | 0;
                a[m + 2] = A * (1 - k) + 0.5 | 0;
                a[m + 3] = 255;
                continue
            } else O ? (q = 0 > q ? 0 : q, k = Math.pow(q / g, 2)) : K ? k = 1 - 2 * Math.pow(1 - q / g, 4) : J && (k = 1 - 2 * ((0.4 * Math.random() + 0.6) * (Math.cos(140 * Math.PI * ((L - y) / h) / w) + 1)) * Math.pow(1 - q / g, 4));
            k = 0 > k ? 0 : 1 < k ? 1 : k;
            a[m] = b[m] * k + z * (1 - k) + 0.5 | 0;
            a[m + 1] = b[m + 1] * k + D * (1 - k) + 0.5 | 0;
            a[m + 2] = b[m + 2] * k + A * (1 - k) + 0.5 | 0;
            a[m + 3] = b[m + 3] * k + f * (1 - k) + 0.5 | 0
        }
    };
    b.d = function(b, d, e, h, f, m) {
        "undefined" == typeof f && (f = "");
        var o, g, n, p;
        o = g = n = p = P;
        var k, q, j, r, x, u, w, v, y, C, z, D, A, G, J, K, N, O = h[0],
            L = h[1],
            I = h[2],
            h = h[3],
            S = 1 !== h,
            U = 1 + (d + e) / 2 / 1024;
        j = new a.m;
        "torn" === f ? (g = M, q = m[0] * (d + e) / 2, k = m[1] * (d + e) / 2, x = m[2], r = "uniform", "string" === typeof m[3] && (r = m[3]), j = m[4], u = "onlyout" === m[5], j = new a.m(j)) : "bulge" === f ? (o = M, k = m[0] * (d + e) / 2, w = m[1] * k, v = m[2] * k) : "rect" === f ? (n = M, k = m[0] * (d + e) / 2, 1 === m[1] && (U = 0)) : "round" === f && (p = M, k = m[0] * Math.min(d, e), C = m[1] * k, z = m[2] * k, D = m[3] * k, A = m[4] * k, y = k, "number" === typeof m[5] && (y *= m[5]));
        for (var H = Math.max(d, e), m = [], E, R, T, B, F, f = [e, d, d, e], V = [1, 1, -1, -1], W = [0, 0, e - 1, d - 1], Q = 0; 4 > Q; Q++) {
            T = f[Q];
            B = V[Q];
            F = W[Q];
            H = T;
            if (g) {
                if (m[H - 1] = F + B * k, m[0] = F + B * k, a.l(m, H - 1, q, x, r, j.g()), u) for (e = 0; e < H; e++) m[e] = B * Math.min(B * (F + B * k), B * m[e])
            } else if (o) for (e = 0; e < H; e++) m[e] = F - B * (w * Math.sin(e * Math.PI / H) - v);
            else if (n) for (e = 0; e < H; e++) m[e] = F + B * k;
            else if (p) {
                E = y * (1 - 1 / Math.SQRT2);
                0 === Q && (K = z, J = C, N = D);
                1 === Q && (K = C, J = z, N = A);
                2 === Q && (K = D, J = z, N = A);
                3 === Q && (K = A, J = C, N = D);
                K -= U / 2;
                for (e = 0; e < H; e++) G = e, m[e] = G < J + E ? F + B * (K + E) : G < J + y ? F + B * (K + y - Math.sqrt(y * y - Math.pow(G - J - y, 2))) : G < T - 1 - N - y ? F + B * K : G <= T - 1 - N - E ? F + B * (K + y - Math.sqrt(y * y - Math.pow(T - 1 - G - N - y, 2))) : F + B * (K + E)
            }
            for (e = 0; e < T; e++) {
                G = m[e];
                for (H = F; B * H < B * (G + B * U); H += B) E = 0 === Q || 3 === Q ? 4 * (e * d + H) : 4 * (H * d + e), B * H < B * (G - B * U) ? S ? (b[E] = h * O + (1 - h) * b[E] + 0.5 | 0, b[E + 1] = h * L + (1 - h) * b[E + 1] + 0.5 | 0, b[E + 2] = h * I + (1 - h) * b[E + 2] + 0.5 | 0, b[E + 3] = 255 * h + (1 - h) * b[E + 3] + 0.5 | 0) : (b[E] = O, b[E + 1] = L, b[E + 2] = I, b[E + 3] = 255) : (R = h * (1 - (B * (H - G) + U) / (2 * U)), b[E] = R * O + (1 - R) * b[E] + 0.5 | 0, b[E + 1] = R * L + (1 - R) * b[E + 1] + 0.5 | 0, b[E + 2] = R * I + (1 - R) * b[E + 2] + 0.5 | 0, b[E + 3] = 255 * R + (1 - R) * b[E + 3] + 0.5 | 0)
            }
        }
    };
    b.ea = function(b, d, e, h, f) {
        var m = "torn";
        "undefined" == typeof m && (m = "");
        var o, g, n, p, k;
        "torn" === m && (o = f[0] * (d + e) / 2, g = f[1] * (d + e) / 2, n = f[2], p = "uniform", "string" === typeof f[3] && (p = f[3]), k = f[4]);
        for (var f =
            h[0], m = h[1], q = h[2], h = h[3], j = 1 !== h, r = Math.max(d, e), x = [], u = [], w = Math.max(1, 0.0010 * (d + e)), v, y, C, z, D, A, G, J = [e, d, d, e], K = [1, 1, -1, -1], e = [0, 0, e - 1, d - 1], N = new a.m(k), O = 0; 4 > O; O++) {
            k = J[O];
            A = K[O];
            G = e[O];
            r = k;
            x[r - 1] = G + A * g;
            x[0] = G + A * g;
            a.l(x, r - 1, o, n, p, N.g());
            u[r - 1] = G + A * g;
            u[0] = G + A * g;
            a.l(u, r - 1, o, n, p, N.g());
            for (r = 0; r < k; r++) {
                y = x[r];
                C = u[r];
                for (v = G; A * v < A * (y + A * w); v += A) z = 0 === O || 3 === O ? 4 * (r * d + v) : 4 * (v * d + r), A * v < A * (y - A * w) && A * v > A * (C + A * w) ? j ? (b[z] = h * f + (1 - h) * b[z] + 0.5 | 0, b[z + 1] = h * m + (1 - h) * b[z + 1] + 0.5 | 0, b[z + 2] = h * q + (1 - h) * b[z + 2] + 0.5 | 0) : (b[z] =
                    f, b[z + 1] = m, b[z + 2] = q) : A * v < A * (y + A * w) && A * v > A * (C - A * w) && (D = h * Math.min(A * (y - v) / w, A * (v - C) / w), 1 < D ? D = 1 : 0 > D && (D = 0), b[z] = D * f + (1 - D) * b[z] + 0.5 | 0, b[z + 1] = D * m + (1 - D) * b[z + 1] + 0.5 | 0, b[z + 2] = D * q + (1 - D) * b[z + 2] + 0.5 | 0)
            }
        }
    };
    f.q = function(a, b, e, h, f, m, o, g, n, p, k, q, j, r) {
        "undefined" === typeof k && (k = 0);
        "undefined" === typeof q && (q = 0);
        "undefined" === typeof j && (j = 1);
        "undefined" === typeof r && (r = 1);
        "undefined" === typeof m ? m = 0 : 0 > m ? m = 0 : 255 < m && (m = 255);
        "undefined" === typeof o ? o = 0 : 0 > o ? o = 0 : 255 < o && (o = 255);
        "undefined" === typeof g ? g = 0 : 0 > g ? g = 0 : 255 < g && (g =
            255);
        "undefined" === typeof n && (n = 1);
        var x = "sinc" === p,
            u = "gaussianthing" === p,
            w = "gaussianthing2" === p,
            v = "gaussianthing3" === p,
            y = "gaussianthing4" === p,
            C = "anglegaussian" === p,
            z = "stardom" === p,
            D = "lobes" === p,
            A = "stripe" === p,
            G = "stripe2" === p,
            J = "dimondflare" === p,
            K = "crossflare" === p,
            N = "cornerflares" === p,
            O = "fingerflare" === p,
            L = "blobflare" === p,
            I = "blah" === p,
            S = "blob" === p,
            p = "blob2" === p,
            U, H, E, R, T, B, F, V = e / 2 | 0,
            W = h / 2 | 0,
            Q = Math.log(1 / 255 / n),
            k = k * V,
            q = q * W,
            j = 1 / f / V / j,
            r = 1 / f / W / r;
        x && (r = j = 2 / f / (V + W));
        for (T = 0; T < h; T++) {
            U = 4 * T * e;
            F = r * (T - W - q);
            for (R = 0; R < e; R++) f = U + 4 * R, B = j * (R - V - k), E = 1, x ? (E = 80 * (B * B + F * F), E = 0 === E ? 1 : 1 - 0.4 * n * (Math.sin(E) / Math.sqrt(E))) : u ? (H = 2 * -(B * B + F * F) - 16 * (F + 0.5) * (F + 0.5) * (B + 0.5) * (B + 0.5), E = H < Q ? 1 : 0 < H ? 1 - n : 1 - n * Math.exp(H)) : w ? (H = 2 * -(B * B + F * F) - 16 * (F + 1) * (F + 1) * (B - 0.5) * (B - 0.5), E = H < Q ? 1 : 0 < H ? 1 - n : 1 - n * Math.exp(H)) : v ? (E = B * B + F * F, H = 2 * -E - 1 * (F + 1.7) * (F + 1.7) * (B - 0.6) * (B - 0.6), E = H < Q ? 1 : 0 < H ? 1 - n : 1 - n * Math.exp(H)) : y ? (H = -0.5 * (F - 1.7) * (F - 1.7) * (B + 0.6) * (B + 0.6) - 0.5 * (F + 1.7) * (F + 1.7) * (B - 0.6) * (B - 0.6), E = H < Q ? 1 : 0 < H ? 1 - n : 1 - n * Math.exp(H)) : C ? (H = 2 * -(B * B + F * F) - 5 * (B + 0.2) * (F + 0.2) * B * B, E = H < Q ? 1 : 0 < H ? 1 - 0.5 * n : 1 - 0.5 * n * Math.exp(H)) : z ? (E = B * B + F * F, B = Math.atan2(F, B), E = 1 - 0.2 * n * (1 - E * E * Math.sin(10 * B))) : D ? (E = B * B + F * F, B = Math.atan2(F, B), E = 1 - 0.5 * n * Math.exp(2 * -E) * (0.5 + 0.5 * Math.sin(4 * B + 1))) : A ? (H = -(B * B) - F * F / 64, E = H < Q ? 1 : 0 < H ? 1 - n : 1 - n * Math.exp(H)) : G ? (H = -(F * F) - B * B / 64, E = H < Q ? 1 : 0 < H ? 1 - n : 1 - n * Math.exp(H)) : J ? (E = B * B + F * F, E = 1 - n * Math.exp(2 * -(E * E) + -8 * (F + 0.5) * (F - 0.5) * (B + 0.5) * (B - 0.5))) : K ? (E = B * B + F * F, H = 2 * -(E / 180) - 16 * (F + 0.5) * (F + 0.5) * (B + 0.5) * (B + 0.5), E = H < Q ? 1 : 0 < H ? 1 - n : 1 - n * Math.exp(H)) : N ? (E = B * B + F * F, E = 1 - n * Math.exp(2 * -(E / 180)) * Math.exp(-8 * (F + 1) * (F - 1) * (B + 1) * (B - 1))) : O ? (F = r * (T - W - q) - 1, B = j * (R - V - k) - 1, E = (B + 1) * B + F * F, E = n * Math.exp(F / B + -E * Math.exp(Math.exp(F) * B))) : L ? (E = B * B + F * F, E = 1 - n * Math.exp(-Math.pow(E, 2) + -0.9 * (F - 1.7) * (F + 0.5) * (B - 1.5) * (B + 0.5))) : I ? (E = B * B + F * F, B = Math.atan2(F, B), E = H < Q ? 1 : 0 < H ? 1 - n : 1 - 0.2 * n * (1 - Math.sin(E) * Math.sin(10 * E) * Math.sin(10 * B))) : p ? (H = 10 * -Math.pow(F * F + B * B, 2), E = H < Q ? 1 : 0 < H ? 1 - n : 1 - n * Math.exp(H)) : S && (H = 100 * -Math.pow(F * F + B * B, 4), E = H < Q ? 1 : 0 < H ? 1 - n : 1 - n * Math.exp(H)), E = 0 > E ? 0 : 1 < E ? 1 : E, a[f] = b[f] * E + m * (1 - E) + 0.5 | 0, a[f + 1] = b[f + 1] * E + o * (1 - E) + 0.5 | 0, a[f + 2] = b[f + 2] * E + g * (1 - E) + 0.5 | 0, a[f + 3] = b[f + 3]
        }
    };
    f.jb = function(a, b, e, h, f, m, o, g, n, p, k, q) {
        var j = 1,
            r = b / 2 | 0,
            x = e / 2 | 0,
            g = g * r + k * r,
            n = n * r + q * x,
            j = 1,
            k = 1 / j / r / 1,
            j = 1 / j / r / 1,
            q = p * p,
            u = Math.PI / (2 * q),
            w, v, y, C;
        w = Math.round(Math.min(e - 1, Math.max(0, x + n - 5 * p / j)));
        for (var e = Math.round(Math.max(0, Math.min(e - 1, x + n + 5 * p / j))), z = Math.round(Math.min(b - 1, Math.max(0, r + g - 5 * p / k))), p = Math.round(Math.max(0, Math.min(b - 1, r + g + 5 * p / k))), D = w; D <= e; D++) {
            w = 4 * D * b;
            v = j * (D - x - n);
            for (var A = z; A <= p; A++) y = w + 4 * A, C = k * (A - r - g), C = C * C + v * v, C > 5 * q || (C =
                2.25 > C * u ? 1 - o * Math.max(Math.cos(C * u), Math.cos(Math.sqrt(C * u) / 1.8) / 2) : 1 - o * Math.cos(Math.sqrt(C * u) / 1.8) / 2, 1 < C && (C = 1), a[y] = a[y] * C + h * (1 - C) | 0, a[y + 1] = a[y + 1] * C + f * (1 - C) | 0, a[y + 2] = a[y + 2] * C + m * (1 - C) | 0)
        }
    };
    f.e = function(a, b, e, h, j, m, o, g, n, p, k, q, s, r) {
        r || (r = 0);
        q || (q = 0);
        s || (s = 1.3);
        var s = s * j,
            x = [];
        x[" "] = [
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0]
        ];
        x["%"] = [
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 0, 0, 0, 0, 0]
        ];
        x["&"] = [
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0]
        ];
        x["~"] = [
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 0, 1, 0, 1, 0],
            [0, 1, 0, 1, 0, 1, 0],
            [0, 1, 0, 1, 0, 1, 0],
            [0, 1, 0, 1, 0, 1, 0]
        ];
        x["{"] = [
            [1, 0, 1, 0, 1, 0, 1],
            [1, 0, 1, 0, 1, 0, 1],
            [1, 0, 1, 0, 1, 0, 1],
            [1, 0, 1, 0, 1, 0, 1],
            [0, 1, 1, 1, 1, 1, 1],
            [0, 1, 1, 1, 1, 1, 1],
            [0, 1, 1, 1, 1, 1, 1]
        ];
        x["}"] = [
            [0, 1, 1, 1, 0, 1, 0],
            [0, 1, 1, 1, 0, 1, 0],
            [0, 1, 1, 1, 0, 1, 0],
            [0, 1, 0, 1, 0, 0, 0],
            [0, 1, 0, 1, 0, 0, 0],
            [0, 1, 0, 1, 0, 0, 0],
            [0, 1, 0, 1, 0, 0, 0]
        ];
        x["^"] = [
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [1, 1, 1, 1, 1, 1, 1],
            [0, 0, 0, 0, 0, 0, 0]
        ];
        x[">"] = [
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 1, 0, 0, 0, 0],
            [0, 1, 1, 1, 0, 0, 0],
            [0, 1, 1, 1, 1, 0, 0],
            [0, 1, 1, 1, 0, 0, 0],
            [0, 1, 1, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0]
        ];
        x["<"] = [
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 1, 1, 0],
            [0, 0, 0, 1, 1, 1, 0],
            [0, 0, 1, 1, 1, 1, 0],
            [0, 0, 0, 1, 1, 1, 0],
            [0, 0, 0, 0, 1, 1, 0],
            [0, 0, 0, 0, 0, 1, 0]
        ];
        x[":"] = [
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0]
        ];
        x["-"] = [
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0]
        ];
        x["+"] = [
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0]
        ];
        x["."] = [
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 1, 1, 0, 0, 0],
            [0, 0, 1, 1, 0, 0, 0]
        ];
        x["/"] = [
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 1, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 1, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0]
        ];
        x["?"] = [
            [0, 0, 1, 1, 0, 0, 0],
            [0, 1, 0, 0, 1, 0, 0],
            [0, 1, 0, 0, 1, 0, 0],
            [0, 0, 1, 1, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0]
        ];
        x["*"] = [
            [0, 0, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 0, 1, 0, 1, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 1, 0, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 0, 0, 0]
        ];
        x["|"] = [
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0]
        ];
        x["@"] = [
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 1, 1, 1, 0, 0]
        ];
        x.A = [
            [0, 0, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0]
        ];
        x.B = [
            [0, 1, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 0, 0]
        ];
        x.C = [
            [0, 0, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 0, 1, 1, 1, 0, 0]
        ];
        x.D = [
            [0, 1, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 0, 0]
        ];
        x.E = [
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 1, 1, 1, 1, 0]
        ];
        x.F = [
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0]
        ];
        x.G = [
            [0, 0, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 1, 1, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 0, 1, 1, 1, 0, 0]
        ];
        x.H = [
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0]
        ];
        x.I = [
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 1, 1, 1, 0, 0]
        ];
        x.J = [
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 0, 1, 1, 1, 0, 0]
        ];
        x.K = [
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 1, 0, 0],
            [0, 1, 0, 1, 0, 0, 0],
            [0, 1, 1, 0, 0, 0, 0],
            [0, 1, 0, 1, 0, 0, 0],
            [0, 1, 0, 0, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0]
        ];
        x.L = [
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 1, 1, 1, 1, 0]
        ];
        x.M = [
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 1, 0, 1, 1, 0],
            [0, 1, 0, 1, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0]
        ];
        x.N = [
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 1, 0, 0, 1, 0],
            [0, 1, 0, 1, 0, 1, 0],
            [0, 1, 0, 0, 1, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0]
        ];
        x.O = [
            [0, 0, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 0, 1, 1, 1, 0, 0]
        ];
        x.P = [
            [0, 1, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0]
        ];
        x.Q = [
            [0, 0, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 1, 0, 1, 0],
            [0, 1, 0, 0, 1, 1, 0],
            [0, 0, 1, 1, 1, 1, 0]
        ];
        x.R = [
            [0, 1, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0]
        ];
        x.S = [
            [0, 0, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 0, 1, 1, 1, 0, 0]
        ];
        x.T = [
            [0, 1, 1, 1, 1, 1, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0]
        ];
        x.U = [
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 0, 1, 1, 1, 0, 0]
        ];
        x.V = [
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 0, 1, 0, 1, 0, 0],
            [0, 0, 0, 1, 0, 0, 0]
        ];
        x.W = [
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 1, 0, 1, 0],
            [0, 1, 1, 0, 1, 1, 0],
            [0, 1, 0, 0, 0, 1, 0]
        ];
        x.X = [
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 0, 1, 0, 1, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 1, 0, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0]
        ];
        x.Y = [
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 0, 1, 0, 1, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0]
        ];
        x.Z = [
            [0, 1, 1, 1, 1, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 1, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 1, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 1, 1, 1, 1, 0]
        ];
        x.f = [
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 1, 1, 1, 0],
            [0, 0, 1, 0, 0, 0, 0],
            [0, 0, 1, 0, 0, 0, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 1, 0, 0, 0, 0],
            [0, 0, 1, 0, 0, 0, 0]
        ];
        x.s = [
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 1, 1, 1, 0, 0]
        ];
        x["1"] = [
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 1, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0]
        ];
        x["2"] = [
            [0, 1, 1, 1, 1, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 1, 1, 1, 1, 0]
        ];
        x["3"] = [
            [0, 1, 1, 1, 1, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 1, 1, 1, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 1, 0]
        ];
        x["4"] = [
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 0, 1, 0]
        ];
        x["5"] = [
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 1, 0]
        ];
        x["6"] = [
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 0, 0, 0, 0, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 1, 0]
        ];
        x["7"] = [
            [0, 1, 1, 1, 1, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 1, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0]
        ];
        x["8"] = [
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 1, 0]
        ];
        x["9"] = [
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 0, 0, 0, 0, 1, 0],
            [0, 1, 1, 1, 1, 1, 0]
        ];
        x["0"] = [
            [0, 0, 1, 1, 1, 0, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 1, 0, 0, 1, 1, 0],
            [0, 1, 0, 1, 0, 1, 0],
            [0, 1, 1, 0, 0, 1, 0],
            [0, 1, 0, 0, 0, 1, 0],
            [0, 0, 1, 1, 1, 0, 0]
        ];
        for (var u, w, v = 0; v < h.length; v++) for (var y = 0; 7 > y; y++) for (var C =
            0; 7 > C; C++) x[h[v]] && x[h[v]][C][y] && (w = (7 * (v + q) + y) * s * Math.cos(r) - C * s * Math.sin(r), u = (7 * (v + q) + y) * s * Math.sin(r) + C * s * Math.cos(r), f.jb(a, b, e, g, n, p, k, w, u, j, m, o))
    };
    f.a = function(a, b, e, h, f, m, o, g, n, p) {
        for (var k = 0; k < n.length - 1; k++) {
            var q = n[k][0],
                j = n[k][1],
                r = n[k + 1][0],
                x = n[k + 1][1],
                u, w, v, y, C, z, D, A = [j - x, r - q],
                G = Math.sqrt(A[0] * A[0] + A[1] * A[1]);
            A[0] /= G;
            A[1] /= G;
            var G = [r - q, x - j],
                G = G[0] * G[0] + G[1] * G[1],
                J = p * p,
                K = (p + 2) * (p + 2);
            z = Math.round(Math.min(h - 1, Math.max(0, Math.min(j, x) - p - 2)));
            for (var N = Math.round(Math.max(0, Math.min(h - 1, Math.max(j, x) + p + 2))), O = Math.round(Math.min(e - 1, Math.max(0, Math.min(q, r) - p - 2))), L = Math.round(Math.max(0, Math.min(e - 1, Math.max(q, r) + p + 2))), I = z; I <= N; I++) {
                z = 4 * I * e;
                for (var S = O; S <= L; S++) if (D = z + 4 * S, u = Math.abs(A[0] * (S - q) + A[1] * (I - j)), w = (S - q) * (S - q) + (I - j) * (I - j), v = (S - r) * (S - r) + (I - x) * (I - x), w < v ? (y = v, C = w) : (y = w, C = v), v = u > p + 2 ? 1 : C + G < y ? w < J || v < J ? 1 - g : w < K || v < K ? 1 - g + g * (Math.sqrt(C) - p) / 2 : 1 : u > p ? 1 - g + g * (u - p) / 2 : 1 - g, 0.995 > v) u = b[D] * v + f * (1 - v) + 0.5 | 0, w = b[D + 1] * v + m * (1 - v) + 0.5 | 0, v = b[D + 2] * v + o * (1 - v) + 0.5 | 0, u = 0 > u ? 0 : 255 < u ? 255 : u, w = 0 > w ? 0 : 255 < w ? 255 : w, v = 0 > v ? 0 : 255 < v ? 255 : v, a[D] = u, a[D + 1] = w, a[D + 2] = v, a[D + 3] = b[D + 3]
            }
        }
    };
    f.Aa = function(a, b, e, h, f, m, o, g) {
        var n = (b + e) / 2,
            p, k, q = 0.01 * o,
            j = o * o,
            m = m * m;
        p = Math.min(e, Math.max(0, Math.round(f - o * n - 4)));
        for (var e = Math.min(e, Math.max(0, Math.round(f + o * n + 4))), r = Math.min(b, Math.max(0, Math.round(h - o * n - 4))), o = Math.min(b, Math.max(0, Math.round(h + o * n + 4))), x, u, w, v, y = p; y < e; y++) {
            x = 4 * y * b;
            p = (y - f) / n;
            for (var C = r; C < o; C++) u = x + 4 * C, k = (C - h) / n, k = k * k + p * p, w = k > j + q ? 0 : k > j ? (j + q - k) / q : k > m ? 1 : k > m - q ? 1 - (m - k) / q : 0, w = 1 - w * w * g, 0.995 > w && (k = a[u] * w + 255 * (1 - w) + 0.5 | 0, v = a[u + 1] * w + 255 * (1 - w) + 0.5 | 0, w = a[u + 2] * w + 255 * (1 - w) + 0.5 | 0, k = 0 > k ? 0 : 255 < k ? 255 : k, v = 0 > v ? 0 : 255 < v ? 255 : v, w = 0 > w ? 0 : 255 < w ? 255 : w, a[u] = k, a[u + 1] = v, a[u + 2] = w, a[u + 3] = a[u + 3])
        }
    };
    f.Qa = function(a, d, e, h, t) {
        for (var h = new j(h), m = "1.4 2 2.8 4 5.6 8 11 16 22 32".split(" "), m = m[h.h() * m.length | 0]; 3 > m.length;) m += " ";
        for (var o = ["100", "200", "400", "800", "1600"], o = o[h.h() * o.length | 0]; 4 > o.length;) o += " ";
        for (var g = "1 2 4 8 15 30 60 125 250 500 1000".split(" "), n = g[0], p = 0.5 * parseFloat(o) / (0.015625 * parseFloat(m) * parseFloat(m)), k = 1; k < g.length; k++) Math.abs(parseInt(g[k], 10) - p) < Math.abs(parseInt(n, 10) - p) && (n = g[k]);
        for (; 4 > n.length;) n = " " + n;
        g = "               ";
        h = g.length * h.h();
        h = Math.min(g.length - 1, Math.max(0, h));
        g = g.substr(0, h) + "@" + g.substr(h + 1);
        h = n + "/s f" + m + "  > |";
        m = "  | <     ISO:" + o;
        b.d(a, d, e, [0, 0, 0, 1], "round", [0.0080, 6, 4, 4, 4, 1.5]);
        o = 0.0043 * Math.min(d, e) / d;
        f.e(a, d, e, h, o, -0.9, -0.978, 180, 120, 0, 1);
        f.e(a, d, e, m, o, 0.9, -0.978, 180, 120, 0, 1, -m.length);
        f.e(a, d, e, "+2..1..0..1..2-", o / 1.7, 0, -0.985, 180, 120, 0, 1, -7.5);
        f.e(a, d, e, g, o / 1.7, 0, -0.988 + 9.1 * o * d / e, 180, 120, 0, 1, -7.5);
        f.e(a, d, e, t, o, 0.85, 0.86, 230, 50, 40, 1, -t.length)
    }
});
(AV.filterPacks || (AV.filterPacks = [])).push(function(a, b, f, j, l, d) {
    l.ma = function(a, b, d, f, o, g, n, p) {
        "undefined" === typeof p && (p = 1);
        for (var k = g * g, q = n * n, j = Math.max(0, Math.min(a - 1, Math.round(f - g))), l = Math.max(0, Math.min(b - 1, Math.round(o - g))), x = Math.max(0, Math.min(a - 1, Math.round(f + g))), b = Math.max(0, Math.min(b - 1, Math.round(o + g))), u, g = l; g <= b; g++) for (var w = j; w <= x; w++) l = w + a * g, u = (w - f) * (w - f) + (g - o) * (g - o), u < k && (u = 0 > n ? p : p * Math.exp(-u / (2 * q)), u > d[l] && (d[l] = u))
    };
    l.La = function(b, d, f) {
        if (0 > f) f = Math.log(0.5 + f / 400) / Math.log(0.5), a.ra(b, d, f);
        else {
            var m = [1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0];
            a.ja(m, 0.6 * f / 100 + 1, 1);
            a.c(b, d, m)
        }
    };
    l.$a = function(b, d, f) {
        f = Math.log(0.5 + 0.75 * f / 400) / Math.log(0.5);
        a.ca(b, d, 1 / f)
    };
    l.Za = function(b, d, f) {
        var m = f;
        0 !== m && (m = 100 * m / Math.abs(m) * (1 - Math.cos(m * Math.PI / 200)));
        var f = [],
            o = [],
            g = [],
            n, p;
        0 < m ? (n = 255 + 90.78 * m / 100, p = 255 - 24 * m / 100, m = 255 - 140 * m / 100) : (n = 255 + 155 * m / 100, p = 255 - 44.625 * m / 100, m = 255 - 245.31 * m / 100);
        for (var k = 0; 255 >= k; k++) f[k] = k * n / 255 + 0.5 | 0, o[k] = k * p / 255 + 0.5 | 0, g[k] = k * m / 255 + 0.5 | 0, f[k] = 255 < f[k] ? 255 : 0 > f[k] ? 0 : f[k], o[k] = 255 < o[k] ? 255 : 0 > o[k] ? 0 : o[k], g[k] = 255 < g[k] ? 255 : 0 > g[k] ? 0 : g[k];
        a.b(b, d, [f, o, g])
    };
    l.ac = function(b, d, f, m, o) {
        a.Hb(b, d, f, m, o)
    };
    l.Vb = function(a, b, d, f, o, g) {
        for (var n = Array(d * f), p = 0; p < d * f; p++) n[p] = 0;
        for (var k = 0.7 * g, p = 0; p < o.length; p++) l.ma(d, f, n, o[p][0], o[p][1], g, k);
        for (var q, j, r, p = 0; p <= f - 1; p++) for (k = 0; k <= d - 1; k++) o = k + d * p, n[o] ? (g = b[4 * o], q = b[4 * o + 1], j = b[4 * o + 2], r = g > q + j ? 1 : 0, 0 > r && (r = 0), r *= n[o], 0.1 < r ? (g = b[4 * o], q = b[4 * o + 1], j = b[4 * o + 2], r *= 3, 1 < r && (r = 1), g = (1 - r) * g + r * (q + j) / 2 + 0.5 | 0, a[4 * o] = g) : a[4 * o] = b[4 * o]) : a[4 * o] = b[4 * o], a[4 * o + 1] =
            b[4 * o + 1], a[4 * o + 2] = b[4 * o + 2], a[4 * o + 3] = b[4 * o + 3]
    };
    l.nc = function(b, d, f, m, o, g) {
        for (var n = [], p = 0; 256 > p; p++) n[p] = 0.04045 >= p / 255 ? 100 * p / 255 / 12.92 : 100 * Math.pow((p / 255 + 0.055) / 1.055, 2.4);
        for (var k = Array(f * m), p = 0; p < f * m; p++) k[p] = 0;
        for (var q = 0.7 * g, p = 0; p < o.length; p++) l.ma(f, m, k, o[p][0], o[p][1], g, q);
        for (var j, g = 0; g < m; g++) for (p = 0; p < f; p++) o = 4 * (p + f * g), k[p + f * g] ? (q = a.ga(d[o], d[o + 1], d[o + 2], n), j = Math.pow(q[1] - 127, 2) + Math.min(Math.pow(q[2] - 154, 2), Math.pow(q[2] - 170, 2)), j = Math.exp(-j / 100) * k[p + f * g], q = a.la([255 * Math.pow(q[0] / 255, 1 - 0.4 * j), q[1], (1 - j) * (q[2] - 127) + 127]), b[o] = q[0], b[o + 1] = q[1], b[o + 2] = q[2]) : (b[o] = d[o], b[o + 1] = d[o + 1], b[o + 2] = d[o + 2]), b[o + 3] = d[o + 3]
    };
    l.Yb = function(b, d, f, m, o, g) {
        for (var n = Array(f * m), p = 0; p < f * m; p++) n[p] = 0;
        for (var k = 0.5 * g, p = 0; p < o.length; p++) l.ma(f, m, n, o[p][0], o[p][1], g, k, 0.65);
        a.copy(b, d);
        a.Ca(b, f, m, -100, n);
        a.Da(b, f, m, -100, n)
    };
    d.push(["", "selectiveblur", l.Yb]);
    d.push(["", "redeye2", l.Vb]);
    d.push(["", "whiten2", l.nc]);
    d.push(["", "brightness", l.La]);
    d.push(["", "contrast", l.$a]);
    d.push(["", "colortemp", l.Za]);
    d.push(["", "sharpness", l.ac]);
    d.push(["", "getImageColors", a.nb])
});
"undefined" == typeof AV && (AV = {});
(function(a) {
    a.support.touch = a.support.touch || "ontouchend" in document
})(avpw_jQuery);
AV.PaintUI = function(a) {
    var b = avpw$(a),
        f = {},
        j = ["mouseOutEvent", "mouseMoveEvent", "mouseDownEvent", "mouseUpEvent"],
        l, d = !0,
        e = !1,
        h = this,
        t = function() {},
        m = function(b) {
            l = l || avpw$(a).offset();
            var d, e, f;
            "internal" == b.type ? (d = b.x, b = b.y) : (e = AV.util.getTouch(b)) ? (d = e.pageX, b = e.pageY) : (d = b.pageX, b = b.pageY);
            e = d - l.left;
            f = b - l.top;
            if (h.viewport.isZoomed()) {
                var k = h.viewport.getRatio();
                e *= k;
                f *= k
            }
            return {
                x: d | 0,
                y: b | 0,
                canvasX: e | 0,
                canvasY: f | 0
            }
        };
    this.PAN_RATE = 20;
    var o = null,
        g = !1,
        n, p = function(a, b) {
            g = !0;
            h.viewport.pan(a - n.x, b - n.y);
            var d = {
                x: a,
                y: b
            };
            window.clearTimeout(o);
            o = window.setTimeout(function() {
                g = !1;
                n = d
            }, h.PAN_RATE)
        };
    this.ZOOM_RATE = 20;
    var k = null,
        q = !1,
        s = function(a) {
            q = !0;
            h.viewport.gestureZoomUpdate(a);
            window.clearTimeout(k);
            k = window.setTimeout(function() {
                q = !1
            }, h.ZOOM_RATE)
        },
        r = function(a) {
            a.scale && !q && s(a.scale);
            return !1
        },
        x = function(a) {
            var b;
            if (d) {
                d = !1;
                window.setTimeout(function() {
                    d = !0
                }, 30);
                if (f.mouseMoveEvent) return b = m(a), f.mouseMoveEvent.apply(h, [b]);
                if (("mobile" == AV.launchData.openType || "aviary" == AV.launchData.openType) && e) b = m(a), a.preventDefault(), n = n || b, g || p(b.x, b.y)
            }
        },
        u = function(a) {
            if (f.mouseDownEvent) return a = m(a), f.mouseDownEvent.apply(h, [a]);
            if ("mobile" == AV.launchData.openType || "aviary" == AV.launchData.openType) n = void 0, e = !0, b.hasClass("avpw_can_pan") && b.addClass("avpw_is_panning")
        },
        w = function(a) {
            if (f.mouseUpEvent) a = m(a), f.mouseUpEvent.apply(h, [a]);
            else if ("mobile" == AV.launchData.openType || "aviary" == AV.launchData.openType) e = !1, h.viewport.isZoomed() && h.viewport.bounceBack(), b.removeClass("avpw_is_panning")
        };
    this.resetOffset =

        function() {
            l = void 0
        };
    this.getCoordinatesFromEventWithinCanvasBounds = function(a) {
        var a = m(a),
            d = a.x,
            e = a.y,
            h = b.offset(),
            f = h.left,
            k = f + b.width(),
            h = h.top,
            g = h + b.height();
        return d > f && d < k && e > h && e < g ? a : null
    };
    this.getCoordinatesFromEvent = function(a) {
        return m(a)
    };
    this.getViewportCenter = function() {
        var a, b, d;
        return (a = AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "getEmbedElement")) ? (a = avpw$(a), b = a.width(), d = a.height(), a = a.offset(), m({
            type: "internal",
            x: b / 2 + a.left,
            y: d / 2 + a.top
        })) : null
    };
    this.subscribe =

        function(a) {
            for (var b = 0; b < j.length; b++) a[j[b]] && (f[j[b]] = a[j[b]].AV_bindInst(a));
            ("mobile" == AV.launchData.openType || "aviary" == AV.launchData.openType) && this.viewport.hidePanUI()
        };
    this.unsubscribe = function() {
        f = {};
        ("mobile" == AV.launchData.openType || "aviary" == AV.launchData.openType) && this.viewport.showPanUI()
    };
    this.shutdown = function() {
        avpw$(a).unbind("mouseleave").unbind("mousedown");
        avpw$(window).unbind("mousemove", x).unbind("mouseup", w).unbind("scroll", h.resetOffset);
        avpw$.support.touch && (a.ontouchmove =
            a.ontouchstart = a.ontouchend = null, a.ongesturechange = a.ongestureend = null, avpw$(window).unbind("touchmove", t));
        this.resetOffset();
        this.viewport.shutdown();
        f = {};
        e = !1
    };
    this.onModeChange = function(b) {
        h.setMouseCursor();
        avpw$.support.touch && (null == b ? (a.ongesturechange = r, a.ongestureend = h.viewport.gestureZoomFinish) : a.ongesturechange = a.ongestureend = null)
    };
    this.setMouseCursor = function(b) {
        void 0 === b && (b = "mobile" == AV.launchData.openType || "aviary" == AV.launchData.openType ? "" : "default");
        a.style.cursor = b
    };
    b.bind("mouseleave", function() {
        f.mouseOutEvent && f.mouseOutEvent.apply(h)
    }).bind("mousedown", u);
    avpw$(window).bind("mouseup", w).bind("mousemove", x).bind("scroll", h.resetOffset);
    avpw$.support.touch && (avpw$(window).bind("touchmove", t), a.ontouchstart = function(b) {
        avpw$(window).unbind("mousemove", x);
        a.ontouchmove = x;
        return u(b)
    }, a.ontouchend = function(b) {
        a.removeEventListener("touchmove", x);
        avpw$(window).bind("mousemove", x);
        w(b);
        return !1
    }, a.ongesturechange = r, a.ongestureend = h.viewport.gestureZoomFinish);
    this.viewport.setCanvas.AV_bindInst(this)(a);
    return this
};
AV.PaintUI.prototype.viewport = function() {
    var a, b, f = {},
        j, l, d = null,
        e = null,
        h, t = function() {
            var a = e.y0 + e.h,
                h = !1,
                f = !1;
            if (e.x0 + e.w > d.w || e.x0 < d.x0) h = !0;
            if (e.y0 < d.y0 || a > d.h) f = !0;
            h || f ? avpw$(b).addClass("avpw_can_pan") : avpw$(b).removeClass("avpw_can_pan");
            return {
                x: h,
                y: f
            }
        },
        m = AV.support.getVendorProperty("transform"),
        o = function(a) {
            if (m && ("mobile" == AV.launchData.openType || "aviary" == AV.launchData.openType)) {
                /*var a = a && a.scale ? a.scale : 1 / f.getRatio(),
                    d = e.x0 - (0.5 * (1 * e.w / a - e.w) + 0.5 | 0),
                    h = e.y0 - (0.5 * (1 * e.h / a - e.h) + 0.5 | 0),
                    g = new AV.TransformStyle(b.style[m]);
                g.set({
                    translate: d + "px, " + h + "px",
                    scale: a
                });
                b.style[m] = g.serialize()*/
                var aa = a && a.scale ? a.scale : 1 / f.getRatio();
                var d = e.x0 - (0.5 * (1 * e.w / aa - e.w) + 0.5 | 0),
                    h = e.y0 - (0.5 * (1 * e.h / aa - e.h) + 0.5 | 0),
                    g = new AV.TransformStyle(b.style[m]);
                //aa *= 0.7;
                //d /= .5;
                g.set({
                    translate: d + "px, " + h + "px",
                    scale: aa
                });
                b.style[m] = g.serialize()
            } else "aviary" == AV.launchData.openType ? (b.style.width = e.w + "px", b.style.height = e.h + "px", b.style.marginLeft = "-" + (e.w / 2 + 0.5 | 0) + "px", b.style.marginTop = "-" + (e.h / 2 + 0.5 | 0) + "px") : (b.style.width = e.w + "px", b.style.height = e.h + "px")
        };
    f.setCanvas = function(d) {
        b = d;
        m && avpw$(b).addClass("avpw_position_by_transform");
        a = this
    };
    f.shutdown = function() {
        j = l = void 0;
        d = e = null
    };
    var g = function(a) {
            if (a) {
                var a = e.w + l * a,
                    d = a * b.height / b.width + 0.5 | 0;
                if (0 > d || 0 > a) return !1;
                e.w = a;
                e.h = d;
                f.centerCanvas();
                h = void 0
            }
        },
        n = 1;
    f.gestureZoomUpdate = function(a) {
        n = a;
        a = 1 * a / this.getRatio();
        o({
            scale: a
        })
    };
    f.gestureZoomFinish = function() {
        if (n) {
            var a = e.w * n + 0.5 | 0,
                b = e.h * n + 0.5 | 0,
                h = a / b;
            a < d.w && b < d.h ? a > h * b ? (e.w = d.w, e.h = d.w * b / a + 0.5 | 0) : (e.h = d.h, e.w = d.h * h + 0.5 | 0) : (e.w = a, e.h = b);
            o()
        }
    };
    var p = function(a, d, f) {
        var g = b.width,
            p = b.height,
            a = a || g,
            d = d || p;
        j = g;
        f || (d = AV.util.getScaledDims(g, p, a, d), a = d.width, d = d.height);
        e = new AV.Bbox(0, 0, a, d);
        o();
        l = Math.abs((j - e.w) / 2) + 0.5 | 0;
        h = void 0
    };
    f.isZoomed = function() {
        return Boolean(e)
    };
    f.getRatio = function() {
        return !this.isZoomed() ? 1 : j / e.w
    };
    f.zoomIn = function() {
        f.isZoomed() || p();
        g(1);
        a.resetOffset();
        return !1
    };
    f.zoomOut = function() {
        f.isZoomed() || p();
        g(-1);
        a.resetOffset();
        return !1
    };
    f.zoomByRatio = function(k) {
        var g = b.width * k,
            k = b.height * k,
            p = e.w,
            m = e.h,
            o = 0,
            n = 0;
        e.w = g + 0.5 | 0;
        e.h = k + 0.5 | 0;
        h = t();
        h.x ? o = -((g - p) / 2 + 0.5 | 0) : (o = (d.w - e.w) / 2 + 0.5 | 0, e.x0 = 0);
        h.y ? n = -((k - m) / 2 + 0.5 | 0) : (n = (d.h - e.h) / 2 + 0.5 | 0, e.y0 = 0);
        f.pan(o, n, !0);
        f.bounceBack();
        a.resetOffset();
        h = void 0;
        return !1
    };
    f.fitLayout = function(b, e) {
        d = new AV.Bbox(0, 0, b, e);
        p(b, e);
        this.centerCanvas();
        a.resetOffset()
    };
    f.resize = function(b, d, e) {
        p(b, d, e);
        this.centerCanvas();
        a.resetOffset();
        return !1
    };
    f.centerCanvas = function() {
        if (d) {
            var a = (d.w - e.w) / 2 + 0.5 | 0,
                b = (d.h - e.h) / 2 + 0.5 | 0;
            e.x0 = e.y0 = 0;
            this.pan(a, b, !0)
        }
    };
    f.pan = function(b, d, f) {
        h = h || t();
        var g = !1;
        if (f || h.x) e.x0 += b, g = !0;
        if (f || h.y) e.y0 += d, g = !0;
        g && (o(), a.resetOffset())
    };
    f.showPanUI = function() {
        h = t()
    };
    f.hidePanUI = function() {
        avpw$(b).removeClass("avpw_can_pan")
    };
    f.bounceBack = function() {
        var a =
                e.x0 + e.w,
            b = e.y0 + e.h;
        a > d.w && e.x0 > d.x0 ? (a = d.w - e.w, e.x0 = 0 < a ? a : 0) : e.x0 < d.x0 && a < d.w && (a = d.w - e.w, e.x0 = 0 > a ? a : 0);
        b > d.h && e.y0 > d.y0 ? (a = d.h - e.h, e.y0 = 0 < a ? a : 0) : e.y0 < d.x0 && b < d.h && (a = d.h - e.h, e.y0 = 0 > a ? a : 0);
        this.pan(0, 0)
    };
    return f
}();
"undefined" == typeof AV && (AV = {});
AV.cnvs = {
    clearCanvas: function(a) {
        a.width = a.width
    },
    copyCanvas: function(a, b, f, j, l) {
        var d = document.createElement("canvas");
        void 0 === l ? (d.width = a.width, d.height = a.height, j = d.getContext("2d"), j.drawImage(a, 0, 0)) : (d.width = j, d.height = l, j = d.getContext("2d"), j.drawImage(a, -b, -f));
        return d
    },
    drawImageCopy: function(a, b, f, j) {
        var l = a.globalCompositeOperation;
        a.save();
        a.beginPath();
        a.rect(f, j, b.width, b.height);
        a.clip();
        a.closePath();
        a.globalCompositeOperation = "copy";
        a.drawImage(b, f, j);
        a.globalCompositeOperation =
            l;
        a.restore()
    },
    getCanvasPixelData: function(a) {
        return a.getContext("2d").getImageData(0, 0, a.width, a.height)
    },
    getImageDataRegion: function(a, b, f, j, l, d) {
        var e, h = a.width,
            t = a.height;
        if (0 > b || 0 > f || b + j > h || f + l > t) {
            if (e = document.createElement("canvas"), e.width = j, e.height = l, e = e.getContext("2d"), e.drawImage(a, -b, -f), e = e.getImageData(0, 0, j, l), d) for (var m = a.getContext("2d"), a = m.getImageData(0, 0, h, 1).data, d = m.getImageData(0, t - 1, h, 1).data, o = m.getImageData(0, 0, 1, t).data, m = m.getImageData(h - 1, 0, 1, t).data, g = e.data, n = 0; n < l; n++) {
                var p = null,
                    k = n + f;
                0 > k && (k = 0, p = a);
                k >= t && (k = t - 1, p = d);
                for (var q = 0; q < j; q++) {
                    var s = null,
                        r = q + b;
                    0 > r && (r = 0, s = o);
                    r >= h && (r = h - 1, s = m);
                    var x = 4 * (q + n * j);
                    s ? (r = 4 * k, g[x++] = s[r++], g[x++] = s[r++], g[x++] = s[r++], g[x] = s[r]) : p ? (s = 4 * r, g[x++] = p[s++], g[x++] = p[s++], g[x++] = p[s++], g[x] = p[s]) : q = h - b - 1
                }
            }
        } else e = a.getContext("2d"), e = e.getImageData(b, f, j, l);
        return e
    },
    putImageDataRegion: function(a, b, f, j) {
        var l = a.getContext("2d"),
            d;
        d = a.width;
        var e = a.height,
            a = b.width,
            h = b.height;
        0 > f || 0 > j || f + a >= d || j + h >= e ? (d = document.createElement("canvas"), d.width = a, d.height = h, a = d.getContext("2d"), a.putImageData(b, 0, 0), l.drawImage(d, f, j)) : l.putImageData(b, f, j)
    },
    convertRgbToHsv: function(a, b, f) {
        var j, l, d, e, h, t;
        for (j = 0; j < f; j += 4) {
            l = b[j];
            d = b[j + 1];
            e = b[j + 2];
            var m = Math.max(l, Math.max(d, e));
            h = Math.min(l, Math.min(d, e));
            t = m;
            if (0 == m) h = l = 0;
            else {
                var o = m - h;
                h = o / m;
                l = l == m ? (d - e) / o : d == m ? 2 + (e - l) / o : 4 + (l - d) / o;
                0 > l && (l += 6);
                l *= 60
            }
            a[j] = l;
            a[j + 1] = h;
            a[j + 2] = t;
            a[j + 3] = b[j + 3]
        }
    },
    convertHsvToRgb: function(a, b, f) {
        var j, l, d, e, h, t;
        for (j = 0; j < f; j += 4) {
            h = b[j];
            t = b[j + 1];
            l = d = e = l = b[j + 2];
            if (0 != t) {
                h /= 60;
                var m = h | 0,
                    o = h - m;
                h = 1 - t;
                var g = 1 - t * o;
                t = 1 - t * (1 - o);
                switch (m) {
                    case 0:
                        e *= h;
                        d *= t;
                        break;
                    case 1:
                        e *= h;
                        l *= g;
                        break;
                    case 2:
                        l *= h;
                        e *= t;
                        break;
                    case 3:
                        l *= h;
                        d *= g;
                        break;
                    case 4:
                        d *= h;
                        l *= t;
                        break;
                    default:
                        d *= h, e *= g
                }
            }
            0 > l && (l = 0);
            255 < l && (l = 255);
            0 > d && (d = 0);
            255 < d && (d = 255);
            0 > e && (e = 0);
            255 < e && (e = 255);
            a[j] = l;
            a[j + 1] = d;
            a[j + 2] = e;
            a[j + 3] = b[j + 3]
        }
    },
    applyKernel1D: function(a, b, f, j, l, d, e, h, t) {
        var m = a.length,
            o, g, n = t - 1 - (m - b);
        for (o = 0; o < t; o++) {
            var p = 0,
                k = 0,
                q = 0;
            if (o >= b && o <= n) {
                var s = e + (o + (m - b) - 1) * h,
                    r;
                for (g = m - 1; 3 <= g;) r = a[g--], p += r * d[s], k += r * d[s + 1], q += r * d[s + 2], s -= h, r = a[g--], p += r * d[s], k += r * d[s + 1], q += r * d[s + 2], s -= h, r = a[g--], p += r * d[s], k += r * d[s + 1], q += r * d[s + 2], s -= h, r = a[g--], p += r * d[s], k += r * d[s + 1], q += r * d[s + 2], s -= h;
                for (; 0 <= g;) r = a[g--], p += r * d[s], k += r * d[s + 1], q += r * d[s + 2], s -= h
            } else
                for (g = 0; g < m; g++) s = o + (g - b), 0 > s && (s = 0), s >= t && (s = t - 1), s = e + s * h, r = a[g], p += r * d[s], k += r * d[s + 1], q += r * d[s + 2];
            g = j + o * l;
            f[g] = p;
            f[g + 1] = k;
            f[g + 2] = q
        }
    },
    shadowTransform: function() {
        var a = document.createElement("canvas");
        a.height = a.width = 3;
        a = a.getContext("2d");
        a.shadowOffsetX = -1;
        a.shadowOffsetY = -1;
        a.shadowBlur = 1;
        a.shadowColor = "red";
        a.fillRect(1, 1, 1, 1);
        var a = a.getImageData(0, 0, 3, 3).data,
            b = {
                x: 1,
                y: 1
            };
        switch (255) {
            case a[32]:
                b.x = b.y = -1;
                break;
            case a[24]:
                b.y = -1;
                break;
            case a[8]:
                b.x = -1
        }
        return b
    }(),
    isOriginClean: function(a) {
        var b = !0;
        try {
            a.getImageData(0, 0, 1, 1)
        } catch (f) {
            f && f.message && -1 !== f.message.toLowerCase().indexOf("security") && (b = !1), f && f.name && -1 !== f.name.toLowerCase().indexOf("security") && (b = !1)
        }
        return b
    }
};
AV.math = {
    sign: function(a) {
        return 0 > a ? -1 : 1
    },
    sqrDist: function(a, b, f, j) {
        a = f - a;
        b = j - b;
        return a * a + b * b
    },
    solveQuadratic: function(a, b, f) {
        var j = -b,
            b = b * b - 4 * a * f,
            a = 2 * a;
        return 0 > b ? [0, 0] : [(j - Math.sqrt(b)) / a, (j + Math.sqrt(b)) / a]
    },
    lowestPositiveQuadratic: function(a, b, f) {
        b = AV.math.solveQuadratic(a, b, f);
        a = b[0];
        b = b[1];
        return 0 >= b && 0 < a ? a : 0 >= a && 0 < b ? b : Math.max(Math.min(a, b), 0)
    },
    lineSegmentIntersection: function(a, b, f, j, l, d, e, h, t) {
        var m, o;
        if (a == f && b == j || l == e && d == h || a == l && b == d || f == l && j == d || a == e && b == h || f == e && j == h) return null;
        f -= a;
        j -= b;
        l -= a;
        d -= b;
        e -= a;
        h -= b;
        m = Math.sqrt(f * f + j * j);
        f /= m;
        j /= m;
        o = l * f + d * j;
        d = d * f - l * j;
        l = o;
        o = e * f + h * j;
        h = h * f - e * j;
        e = o;
        if (0 > d && 0 > h || 0 <= d && 0 <= h) return null;
        l = e + (l - e) * h / (h - d);
        if (0 > l || l > m) return null;
        t.x = a + l * f;
        t.y = b + l * j;
        return !0
    }
};
AV.Bbox = function(a, b, f, j, l) {
    void 0 !== j ? (this.x0 = a, this.y0 = b, this.x1 = f, this.y1 = j, this.w = this.x1 - this.x0, this.h = this.y1 - this.y0, this.margin = void 0 !== l ? l : 0) : this.reset()
};
AV.Bbox.prototype.include = function(a, b) {
    var f = a - this.margin,
        j = b - this.margin,
        l = a + this.margin,
        d = b + this.margin;
    this.x0 > f && (this.x0 = f);
    this.y0 > j && (this.y0 = j);
    this.x1 < l && (this.x1 = l);
    this.y1 < d && (this.y1 = d);
    this.w = this.x1 - this.x0;
    this.h = this.y1 - this.y0
};
AV.Bbox.prototype.reset = function() {
    this.y0 = this.x0 = 999999999;
    this.y1 = this.x1 = -999999999;
    this.margin = this.h = this.w = 0
};
AV.Bbox.prototype.contains = function(a, b) {
    var f = b >= this.y0 && b <= this.y1;
    return a >= this.x0 && a <= this.x1 && f
};
AV.Bbox.prototype.constrain = function(a, b) {
    var f = !1;
    a < this.x0 && (a = this.x0, f = !0);
    a > this.x1 && (a = this.x1, f = !0);
    b < this.y0 && (b = this.y0, f = !0);
    b > this.y1 && (b = this.y1, f = !0);
    return {
        x: a,
        y: b,
        dirty: f
    }
};
AV.Layer = function(a) {
    this.image = this.canvas = this.drawable = null;
    void 0 !== a.image ? (this.image = this.drawable = a.image, this.type = "image") : void 0 !== a.canvas && (this.canvas = this.drawable = a.canvas, this.type = "canvas");
    this.name = a.name;
    this.translateX = this.translateY = this.rotate = 0;
    this.scaleX = this.scaleY = 1
};
AV.Layer.prototype.worldToLocal = function(a) {
    a.x -= this.translateX;
    a.y -= this.translateY;
    var b = Math.cos(-this.rotate),
        f = Math.sin(-this.rotate),
        j = a.x;
    a.x = j * b - a.y * f;
    a.y = j * f + a.y * b;
    a.x /= this.scaleX;
    a.y /= this.scaleY
};
AV.Layer.prototype.localToWorld = function(a) {
    this.centerX && (a.x -= this.centerX);
    this.centerY && (a.y -= this.centerY);
    a.x *= this.scaleX;
    a.y *= this.scaleY;
    var b = Math.cos(this.rotate),
        f = Math.sin(this.rotate),
        j = a.x;
    a.x = j * b - a.y * f;
    a.y = j * f + a.y * b;
    a.x += this.translateX;
    a.y += this.translateY
};
AV.Layer.prototype.localPointIn = function(a) {
    var b = 0,
        f = 0;
    this.centerX && (b -= this.centerX);
    this.centerY && (f -= this.centerY);
    return a.x >= b && a.y >= f && a.x < b + this.drawable.width && a.y < f + this.drawable.height
};
AV.BrushModule = function(a) {
    var b = a.overflow || 0,
        f = a.layerName || "drawing",
        j = a.preserveBacking || !1,
        l, d, e = 5,
        h = "rgba(255,255,204,0.5)",
        t = !1,
        m, o, g, n, p, k, q = function() {
            var a = l.getLayerByName(f);
            a || (a = document.createElement("canvas"), a.width = l.width, a.height = l.height, a = new AV.Layer({
                canvas: a,
                name: f
            }), l.addLayer(a), l.uiLayerReset());
            a.canvas.width != l.width && (a.canvas.width = l.width);
            a.canvas.height != l.height && (a.canvas.height = l.height);
            return a
        },
        s = function(a, b, d, f, m) {
            a.lineCap = "round";
            a.lineJoin = "round";
            a.lineWidth =
                2 * e;
            t ? (a.strokeStyle = "#fff", a.globalCompositeOperation = "destination-out") : (a.strokeStyle = h, a.globalCompositeOperation = "source-over");
            a.beginPath();
            f ? (g = b, n = d, a.moveTo(g, n), a.lineTo(b + 0.02, d), p = b, k = d) : m ? (a.moveTo(p, k), a.lineTo(g, n), g = n = p = k = null) : (f = (g + b) / 2 | 0, m = (n + d) / 2 | 0, a.moveTo(p, k), a.quadraticCurveTo(g, n, f, m), g = b, n = d, p = f, k = m);
            a.stroke();
            a.closePath()
        },
        r = function(a) {
            for (var b = e, d, h = 0, f = [], g = function(a, d, e, h, f, k, p) {
                if ((h - d) * (h - d) + (f - e) * (f - e) < b * b) return null;
                var m, o, n, j;
                void 0 !== k ? (m = (k + d) / 2, o = (k + h) / 2, n = (p + e) / 2, j = (p + f) / 2, k = (m + o) / 2, p = (n + j) / 2) : (k = (d + h) / 2, p = (e + f) / 2);
                g(a, d, e, k, p, m, n);
                a.push([k | 0, p | 0]);
                g(a, k, p, h, f, o, j)
            }, h = 0; h < a.length - 1; ++h) {
                var k = a[h],
                    p = a[h + 1],
                    m = [(k[0] + p[0]) / 2 | 0, (k[1] + p[1]) / 2 | 0];
                0 === h ? g(f, k[0], k[1], m[0], m[1]) : g(f, d[0], d[1], m[0], m[1], k[0], k[1]);
                d = m;
                f.push(m);
                h === a.length - 2 && (g(f, m[0], m[1], p[0], p[1]), f.push(p))
            }
            return f
        },
        x = function(a, b, d, e) {
            a = l.getLayerByName(a).canvas.getContext("2d");
            AV.cnvs.drawImageCopy(a, b, d, e);
            l.recomposite()
        },
        u = function(a, b, d, e) {
            a = q().canvas.getContext("2d");
            AV.cnvs.drawImageCopy(a, b, d, e);
            l.recomposite()
        };
    this.activate = function(a) {
        l = a;
        o = new AV.Bbox;
        g = n = 0;
        l.uiLayerReset();
        l.uiLayerShow(!0)
    };
    this.deactivate = function() {
        l.uiLayerShow(!1)
    };
    this.userUndo = function() {
        l.uiLayerShow(!1)
    };
    this.userRedo = function() {
        l.uiLayerShow(!1)
    };
    this.updateUIDown = function(a, h) {
        var f = q();
        l.currentLayer = f;
        j && (d = AV.cnvs.copyCanvas(f.canvas));
        l.uiLayerShow(!0);
        m = [
            [a | 0, h | 0]
        ];
        o.reset();
        o.margin = (e | 0) + b;
        o.include(a, h);
        f = f.canvas.getContext("2d");
        s(f, a, h, !0);
        l.recomposite()
    };
    this.updateUIMove =

        function(a, b) {
            l.uiLayerShow(!0);
            l.uiLayerDrawCircleSelection(a, b, e);
            l.recomposite()
        };
    this.updateUIDraw = function(a, b) {
        var d = l.currentLayer.canvas.getContext("2d");
        m.push([a | 0, b | 0]);
        s(d, a, b, !1);
        o.include(a, b);
        l.uiLayerShow(!0);
        l.uiLayerDrawCircleSelection(a, b, e);
        l.recomposite()
    };
    this.commit = function(a, b, f) {
        var g, k;
        if (b && f) {
            var p = 1 < m.length ? r(m) : m;
            g = l.findLayerIndexByName("spot_tool"); - 1 !== g && (l.layers[g].canvas.width = l.canvas.width, l.layers[g].canvas.height = l.canvas.height);
            g = l.layers[l.currentLayerIndex];
            if (!g.canvas) return;
            k = AV.cnvs.copyCanvas(g.canvas, o.x0, o.y0, o.w, o.h);
            l.actions.push([b, this, [g.name, k, o.x0, o.y0]], [f, this, [g.name, p, e]], {
                action: a,
                radius: e | 0,
                pointlist: p
            });
            l.actions.redo()
        } else g = l.currentLayer, b = g.canvas.getContext("2d"), s(b, 0, 0, !1, !0), l.recomposite(), k = AV.cnvs.copyCanvas(g.canvas, o.x0, o.y0, o.w, o.h), b = AV.cnvs.copyCanvas(d, o.x0, o.y0, o.w, o.h), l.actions.push([x, this, [g.name, b, o.x0, o.y0]], [u, this, [g.name, k, o.x0, o.y0]], {
            action: a,
            erase: t,
            radius: e | 0,
            softness: 0,
            color: AV.util.color_to_rgb(h),
            pointlist: m
        }), l.actions.redoFake();
        d = m = null
    };
    this.radius = function() {
        return e
    };
    this.setRadius = function(a) {
        e = a
    };
    this.setColor = function(a) {
        h = a
    };
    this.setErase = function(a) {
        t = a
    };
    return this
};
AV.PaintWidget = function(a, b) {
    this.imgElementClean = null;
    this.busy = !1;
    this.moduleLoadedCallback = {};
    this.canvasReadyCallback = new avpw$.Deferred;
    this.active = null;
    this.layers = [];
    this.dirtyRect = {
        bbox: new AV.Bbox,
        dirty: !1
    };
    this.canvas = document.createElement("canvas");
    this.setDimensions(a, b);
    this.canvas.style.display = "block";
    this.dirty = !1;
    this.actions = new AV.Actions;
    this.closeXImg = document.createElement("img");
    this.closeXImg.src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACYAAAAmCAYAAACoPemuAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAAJcEhZcwAACxMAAAsTAQCanBgAAAe/SURBVFjDtVhJUBRXGBZkXxT3DeKCC8uwM+zDwAxLULJ4SNSKCW5JLBFIlaYqWnE5KSC5aRlFytLSlF5Ewm6BilUeFGIsLyhysVTUgx7koFUeXv7v8b+enp4eBDVd9cs487r76+/7/qXflCmTO3w4fCmmUvhR+HsJP17jqzvvkx96MLhpIEUwRShFGMU0Q4Txb8G81t8A8pOBUoCC+KYRFLMp5lEspIikiOKI5O/m85oIPidIB/CjGFQsKUDhFLP4pkspVlFYKJLPnTu39ezZs9sQ+Ox0Om30fQJFDMUyikUMchqzqAf4QSwF8NPO4osvp0hsamra/ujRo7Y3b948E14O/EZr2pubm3cDPMUKZnM2XzNwsuAUqEBmaS7FEor4lpaWXXTDET2A/v5+ceLPE+LkiZOi8WSjONV4StzouyGejTzTgxy5evXqQWZxKVtgGisxIXBKvgA+ET6JhjTPnz+/rm50+/ZtUVNdI5ITk0RqcopIT00T1nSryMzIFNlZ2SI3J0/Y8vLF1i3bRFdnlxgdHZXnvXr16p+qqqrVzN4CiukTZU55KpSfanl9ff16xdKTJ0/EpooKkRBvEUkJiSIlKVmkpaQKa1q6yLQSqEwClZ0r8nJtIt9mFwX2QuEodIqN320UN2/elODevXs3WldXt4GuvZLBhTMRXjPWh2tPCHsqGqBwIVyw+dIlkUWMWOLiRaIlgdlKJbbSRUZ6Bv2WJXKyckQesZVPbBXkFwhHgUMUOYpEcVGJjLq6eskerllbW7uBmZvDRPgzOFNfQfMZSHuHw2FTTAFUXEysiI+NJ7YSiK0kYiuF2CIJ06xubNnybMKebxeFBYXC6XBqoAAQQCGvArdjx45y9u9MzlYPSZWvwpneOPJUnwIVuyqGQMVJCRNJwmSSMJUkTCcJM6zEViaxlU1s5RJbtnxNwiJnsSgpLpF/8X98D4n3/LZHyvr69eshTohF7OkAPWuKrWCWcNmZM2d+VJ7KsFolW+4SElsehs+VhreThIXEjFMnIT7jO/yGNVh78cJFCe7atWsHuQzNZhv5KdZ8WN8wZise2YOTKr7/wY0tT8NnSAlzTAw/xlapga18ySrYLXIWgTFZSnSsheu95sspi9axuKGhYZ0sCbduSVBmbMHwra2tYujBkJTKaHgw1N19haKb2SqUvoP/4EM8DHyJ2oejr69vP9e3mexzCUzJCCpXDA8P/4XF8EGsNHych+Fb/27ViufQ0JAo+7xM2G0uw3d3dWu/o44BMIDjAZC5yGBkcoG9QK558eLFdW5xc1lOYJKahnIxjaOMeYDFMDVAWUwM39ba5tZ+Hj58KMrXlEvJ9KBwdBIwSAypXWxlSCvAEgPUOZChLOdCthTklP+Es8aJuNjg4KCHhCkGw7e3tRvADYsuAyiw5TJ8nkwSnItr4Fq45vHjx+Va7ghR3A0CFDCkaiRaDxb19PR4NbyrPOSKjvYObz18TEIuD8rwOA/ng3Wwn5yYLH6h1oYDlYBrWoQRWNT58+e3YtGxo0dNDJ9mWuE7Ozs9QXV1uRs+x2V4FGT4FH7FQ2/ZtFmec/r06Z84AWZwMpoBO6Zjy0uFJ8/A8PCQ8YCs5avLTQ2PjMaD4oHx4JsrNr0XmCZlb28vnZQoqdb6oUmFN2NLnxBlpWWmhoc18NB4+KqdVXopF+ul9ONMQEYkYNH9wfsiycTwrgpvE50d7qBQs4wZiVJSQpXfaHjFFuwC2+DgqTeKSfLXlwuMObGq6uPmaab90CY6PEBd0Sq8UVoUYTfDE1tQwxJnkZmPROPqb+HOE8aY3ArschoC/8AFD+w/wIa3uhkeqX/4UK124ysEyr0f2gm4K1tR84yGh4TIesiL4/Hjx61mBVZNFqgfn1VXV6/G4oGBAa+GL6QyUFdbL5mSI41kyyEruTJ8e3u7aGtrMzU8JARbhw8d0vtLGV9rSfomPl8v5/aft7vY0kaaAgkCYOT0QH+dJCHAArQt170fmhlesYXp5e3bt0rGhcYmrh8S0USXHDlyZD2AjYyM0M2LNcOPDYDvG2lQ4XM0w8MOZmwRS5ItvOBgWmYreQyLetaQBCtVM4ckExtpxvqhaYWH4Ykt9F2A2llZKUFhGIVCrFS4cVA0vrJNV32TGvoQLoAsHH8A1FV4fT/UDJ+kSbj267XaHIbxHb7m2uX1VU69jASzpEvpxHwFDr3vyy++0rzlZng1AHpU+FQ3Cffu2au9KeFFx9vk+r7XN6RutB4cySt27/pV+m58w1vdDF9aUip6e3q1OZ9BrWAJw8wkHG+/Qr2FI42T7t2716hq0927d8W+3/dphnf1w0xZ95Th133zrWi5fFlKx53gAlf4aAY1je3jO9m38TCmGj6IraysLMd+hAKI17A7d/4VTaea5NYAtggwLtMLhnj69KlWZF++fHmHm3Q8jzZz+ME/aP9CMRfMCTGPm2xMTU1NGRXgBozE6oVYf+A71ML+/v4GGgDXYDJmQPPZ6CH84FM/dDvKl00ZyL6LYHmjeHtJbUUl8Y5OMn+28BZUNLM9j6u62uXx+xSbeD4GgCHM4EwGuYDLSyTHIv5uLr+nTueHCvqYfbGJ7L36sQxBDDKU/aK2OcOZmRBeE6Dbj/3f9mL1DI63Qexnsjk8qeM/jyU1SeRiYmkAAAAASUVORK5CYII=";
    this.handleImg = document.createElement("img");
    this.handleImg.src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAAJcEhZcwAACxMAAAsTAQCanBgAAAYKSURBVFjDpZj7axRXFMe/d3Z3NvvIpnFbd8UkapL6gCTGH2wbrNKKogWFQH8p+EP6g6W0/U0KQaUFCxVK/wCR1l9asTRqSrEPbYNbN9XGEGtIjDGs2phE8tydfWVnn3P7w95Z70xmH9HA4e5Ohp3PnPM9555zCVb/R5jx3wGA6u6jBtfK/vBqAARmhFuJDkDhVoWDoi8Kwz/UzMzCzMRM/b/68ByALIAMW1UrC0Uq8ISJPdwKwMasCoDIQakwKkRSZyl2nfdWRTAqCA9hB+AE4GLmOHz4cF17e7u3ubl5bX19vXtkZGRKkqTYqVOnRgEkAMQBxDiTAaQZsFKpngQG4QDwCoBNAHYA2Hvo0KGP/H7/1WAwuJRMpmgiIVNZlmkymaSpVIqm02mayWTo2NjYv+fOnTsDoBPAbgDbAKwH8BLzqrkSvQrsRieAtQBeBdDR0tLyns/n+z0Wi1EpHKahkEQlKUzDkSiNxmI0Hl+my4kEleVnUMlkks7Ozi52d3d/AWAfgO0AGgDUMiCTHkifoiamBReANQA8XV3v7zx58sQna9zuBkoBQggEgUAgAghbBYH/TEDYCgDxeBwDAwNXDxw4cAbAHIBFABEWygwTvAaGGITHe+TIkTe+PH36M1uVzU4E7YO0MAREECAQkgfjYBRKEY1GcWfozs39+/d9DeApgAUOKMc0BEEHIzKYNc3NzZuOHz/xqdVaZSeEYHWW9yClFFShcDgcaGtr23W5t/dj9qI1LCksfK0y6URrZ+Gpu3Dhh+76+vpGs8UCgTx7c9VDpT2T9wqlFJQqoAqFaBXhqq5uSqXSk0NDQ7Nc2hdqEF9NzayG1Bw9+sHrGzdu3GERxfwPV+INaL/nQbTmqqlBV1fXh+yFq1nZKGSWoAuRDUB1Z2fnuza7XeNyzYriUIAxCFUoLBYL3G73y8eOHdvNYPisIoIuixy7dr3Z6PV6t1RZrRXpQr8CFIoRjOodlws7d772NtONjclD0MNYAFTt2bOn1e5wFHV/OVO9oFBjKNFqhXeddwvTpxomQR8mMwDR4/FsslrFynRCSuhEya9GQDab3Xbw4DtNur2N6FPb7HK5Ntht9rICBXcNK2AUzcP1QKIowul0OjmvaARc6FXSmQwxW8wldUEI8mleAEVRjRhZNptFNpdd0RMJuj6DptNpms3myoYEGtGiaAZpr+Xvy2WzUHK5Fb2NwHVoOQCZ+fn5qWg0WhZG0GlFKSFavYVCIYQjkTgreJrtgHIdWiYUCs5JoVDZ4oYSxa0UEECxFAzCf+PGI9bf5PgKXPAKgGS/v39wcnLSsLihZAYpBmHhDKpXJExMTAyzTZJvtjSeSQOQ790bnbo/Pj4Tj8dLi7hYpS3qnbxe7o/dx8NAYIh1gkm+81M1o3pGBhAdHR256r/hL7kvAXRVoqWUIhaL4e7w3WBPz49/sFY0yYVJ45kMgGUA4Z96e3+7fXtwIhAI5IUK4/BUKliwta+vD4ODg98DWAIQZY16Ydc2cUWP8s34UnBpWjCZ39q8ebPF5XJp2oRCKnNVVimhEwoKn+8v+P39/Zcv9XwHYB6AxF4+o3rGVGxWkkIhOSxJj8PhSMe2bVstNTWuQgdXOiwrze/vx/XrvifffnP2c9Z6BhlIkp8UjGAKBXBhYT4ihcOPFpeWOkRRtDQ2Nuq0ohQByHtOlmVcvHgJ165d+/vXX658JcuJaQYSZfos1JhiMHwRzC0uLoQmJh7ckqTwhsknk+tsVTZ4PJ6SGZRIJODz+XD+/AV54J9bPVeu/HxWlhNTnFZkbqgrOsSpmlGHNwebFGoBuFvbtnc0NDTsbWpqat+6Zautrq4O6+vWg1KK5eVlzEzPIPAwgPHxB8G52dk/h4fv9kUi4SnWgIdZOqvDnFIORj9RihyUkzVELgDOlta21tra2kazyexAvj7SSCTy3/zc3OOnT2emuYkyynmDL3LK887aosGsbWXX+Vk7wx6oNtvyi87aRqcQ/AGAambuJILXGX8CkdFths91CmE09vLh05/T8FnIH43QSiBWC6MPHzGYSKnBidWqTq/+Bz5vhIk6njv4AAAAAElFTkSuQmCC"
};
AV.PaintWidget.prototype.filterManager = new AV.FilterManager;
AV.PaintWidget.prototype.overlayRegistry = new AV.OverlayRegistry;
AV.PaintWidget.prototype.setOrigSize = function(a, b) {
    this._origWidth = a + 0.5 | 0;
    this._origHeight = b + 0.5 | 0
};
AV.PaintWidget.prototype.getOrigSize = function() {
    return {
        width: this._origWidth,
        height: this._origHeight
    }
};
AV.PaintWidget.prototype.c2a = function(a, b, f) {
    var j, l;
    l = this.canvas.width;
    var d = this.canvas.height;
    j = this.getScaledSize();
    if (l != j.width || d != j.height) l = j.width / l, a *= l, f && (a = a + 0.5 | 0), l = j.height / d, b *= l, f && (b = b + 0.5 | 0);
    return {
        width: a,
        height: b
    }
};
AV.PaintWidget.prototype.a2c = function(a, b, f) {
    var j, l;
    l = this.canvas.width;
    var d = this.canvas.height;
    j = this.getScaledSize();
    if (l != j.width || d != j.height) l /= j.width, a *= l, f && (a = a + 0.5 | 0), l = d / j.height, b *= l, f && (b = b + 0.5 | 0);
    return {
        width: a,
        height: b
    }
};
AV.PaintWidget.prototype.setBackground = function(a) {
    var b;
    this.imgElementClean = a;
    var f = document.createElement("canvas");
    f.width = this.width;
    f.height = this.height;
    b = f.getContext("2d");
    b.drawImage(a, 0, 0, this.width, this.height);
    a = AV.cnvs.isOriginClean(b);
    this.addLayer(new AV.Layer({
        canvas: f,
        name: "background"
    }));
    this.recomposite();
    this.currentLayerIndex = 0;
    this.currentLayer = this.layers[this.currentLayerIndex];
    this.canvasReadyCallback && this.canvasReadyCallback.resolve();
    return a
};
AV.PaintWidget.prototype.shutdown = function() {
    this.setMode(null);
    this.waitThrobberShow = this.canvasReadyCallback = this.moduleLoadedCallback = this.canvas = this.currentLayer = this.layers = this.actions = null
};
AV.PaintWidget.prototype.moduleLoaded = function(a, b) {
    var f = this,
        j, l = [],
        d = function(a) {
            var b, d, e;
            for (b = 0; b < a.length; b++) if (d = a[b].resourceUrl) e = new avpw$.Deferred, f.filterManager.loadPack(a[b].assetId, d, e.resolve), l.push(e)
        },
        e = function() {
            b && b.apply(this, [a])
        };
    f.moduleLoadedCallback && (j = f.moduleLoadedCallback[a]);
    if (j && j.isResolved()) return j.done(e), !0;
    j || ("effects" === a || "enhance" === a ? AV.controlsWidgetInstance.purchaseManager && AV.controlsWidgetInstance.purchaseManager.getPurchasedAssets("EFFECT", d) : "frames" === a && AV.controlsWidgetInstance.purchaseManager && AV.controlsWidgetInstance.purchaseManager.getPurchasedAssets("FRAME", d), j = avpw$.when(f.canvasReadyCallback, l));
    j.done(e);
    f.moduleLoadedCallback[a] = j;
    return j.isResolved()
};
AV.PaintWidget.prototype.setDimensions = function(a, b) {
    this.canvas.width = this.width = a;
    this.canvas.height = this.height = b;
    AV.controlsWidgetInstance && (AV.controlsWidgetInstance.canvasUI && AV.controlsWidgetInstance.canvasUI.resetOffset(), AV.controlsWidgetInstance.layoutNotify(AV.launchData.openType, "scaleCanvas"))
};
AV.PaintWidget.prototype.getScaledSize = function() {
    return this.actions ? this.actions.getDims() : {
        width: this.width,
        height: this.height
    }
};
AV.PaintWidget.prototype.showWaitThrobber = function(a, b) {
    b && setTimeout(b, 5)
};
AV.PaintWidget.prototype.getBackgroundLayerIndex = function() {
    return 0
};
AV.PaintWidget.prototype.findLayerIndexByName = function(a) {
    var b;
    for (b = 0; b < this.layers.length; b++) if (this.layers[b].name === a) return b;
    return -1
};
AV.PaintWidget.prototype.getLayerByName = function(a) {
    a = this.findLayerIndexByName(a);
    return -1 === a ? null : this.layers[a]
};
AV.PaintWidget.prototype.moveLayerByName = function(a, b, f) {
    a = this.getLayerByName(a);
    null !== a && (a.translateX = b, a.translateY = f)
};
AV.PaintWidget.prototype.setCurrentLayerByName = function(a) {
    a = this.findLayerIndexByName(a);
    if (-1 === a) return !1;
    this.currentLayerIndex = a;
    this.currentLayer = this.layers[a];
    return !0
};
AV.PaintWidget.prototype.layerToTop = function(a) {
    for (var b = this.layers[a]; a < this.layers.length - 1;) {
        var f = parseInt(a, 10) + 1;
        this.layers[a] = this.layers[f];
        a++
    }
    this.layers[a] = b
};
AV.PaintWidget.prototype.layerDelete = function(a) {
    for (; a < this.layers.length - 1;) {
        var b = parseInt(a, 10) + 1;
        this.layers[a] = this.layers[b];
        a++
    }
    this.layers.length -= 1
};
AV.PaintWidget.prototype.getLayerByMouseClickWithTag = function(a, b, f, j, l) {
    var d, e, h, t, m, o, g;
    for (d = this.layers.length - 1; 0 <= d; d--) {
        var n = this.layers[d];
        if (!n.hidden && !(void 0 !== f && n[f] !== j)) {
            var p = {
                x: a,
                y: b
            };
            n.worldToLocal(p);
            null != l && (l.x = p.x, l.y = p.y, l.dx = l.dy = 0);
            if (l.handleWidth && 0 < l.handleWidth) {
                g = l.handleWidth / n.scaleX;
                l.cornerHit = -1;
                h = 0;
                e = h - g / 2;
                t = h + g / 2;
                o = n.drawable.height;
                m = o - g / 2;
                g = o + g / 2;
                n.centerX && (h -= n.centerX, e -= n.centerX, t -= n.centerX);
                n.centerY && (o -= n.centerY, m -= n.centerY, g -= n.centerY);
                if (p.x >= e && p.x <= t && p.y >= m && p.y <= g) return l.cornerHit = 2, a = (h - p.x) * n.scaleX, o = (o - p.y) * n.scaleY, l.dx = (0 < a ? a + 0.5 : a - 0.5) | 0, l.dy = (0 < o ? o + 0.5 : o - 0.5) | 0, n;
                e = n.drawable.width - this.closeXImg.width / 2;
                m = 0 - this.closeXImg.height / 2;
                t = n.drawable.width + this.closeXImg.width / 2;
                g = this.closeXImg.height / 2;
                n.centerX && (e -= n.centerX, t -= n.centerX);
                n.centerY && (m -= n.centerY, g -= n.centerY);
                if (p.x >= e && p.x <= t && p.y >= m && p.y <= g) return l.cornerHit = 1, n
            }
            if (n.localPointIn(p)) return n
        }
    }
    return null
};
AV.PaintWidget.prototype.getImageColors = function() {
    var a, b;
    a = [];
    if (b = this.filterManager.getEffectById("getImageColors")) {
        a = this.getBackingAll();
        a = b(a.data, this.width, this.height);
        a = a.slice(0, 3);
        for (b = 0; 3 > b; b++) a[b] = AV.util.array_to_color(a[b])
    }
    return a
};
AV.PaintWidget.prototype.uiLayerReset = function() {
    var a = this.findLayerIndexByName("_ui"); - 1 == a ? (a = document.createElement("canvas"), a.width = this.canvas.width, a.height = this.canvas.height, a = new AV.Layer({
        canvas: a,
        name: "_ui"
    }), a.hidden = !0, this.layers.push(a)) : (this.layers[a].hidden = !0, this.layers[a].canvas.width = this.canvas.width, this.layers[a].canvas.height = this.canvas.height, this.layerToTop(a))
};
AV.PaintWidget.prototype.uiLayerShow = function(a) {
    var b = this.findLayerIndexByName("_ui"); - 1 != b && (this.layers[b].hidden = !a, a || this.recomposite())
};
AV.PaintWidget.prototype.uiLayerDrawRectSelection = function(a) {
    var b, f, j = 1;
    AV.controlsWidgetInstance && AV.controlsWidgetInstance.canvasUI && (j = AV.controlsWidgetInstance.canvasUI.viewport.getRatio());
    b = a.drawable.width;
    f = a.drawable.height;
    var l = this.getLayerByName("_ui"),
        d = l.canvas.getContext("2d");
    d.globalCompositeOperation = "copy";
    d.clearRect(0, 0, l.canvas.width, l.canvas.height);
    d.globalCompositeOperation = "source-over";
    d.setTransform(1, 0, 0, 1, 0, 0);
    d.translate(a.translateX, a.translateY);
    d.rotate(a.rotate);
    d.scale(a.scaleX, a.scaleY);
    null != a.centerX && null != a.centerY && d.translate(-a.centerX, -a.centerY);
    d.globalAlpha = 0.5;
    d.strokeStyle = "#000000";
    d.lineWidth = 3 * j / a.scaleX;
    d.lineCap = "round";
    d.lineJoin = "miter";
    d.beginPath();
    d.moveTo(b, 0);
    d.lineTo(8, 0);
    d.arcTo(0, 0, 0, 8, 8);
    d.lineTo(0, f);
    d.lineTo(b - 8, f);
    d.arcTo(b, f, b, f - 8, 8);
    d.lineTo(b, 0);
    d.stroke();
    d.closePath();
    d.globalAlpha = 1;
    d.setTransform(1, 0, 0, 1, 0, 0);
    b = {
        x: b,
        y: 0
    };
    a.localToWorld(b);
    d.translate(b.x, b.y);
    d.rotate(a.rotate);
    d.translate(-1 * (this.closeXImg.width * j / 2 + 0.5 | 0), -1 * (this.closeXImg.height * j / 2 + 0.5 | 0));
    d.scale(j, j);
    d.drawImage(this.closeXImg, 0, 0);
    d.scale(1, 1);
    d.setTransform(1, 0, 0, 1, 0, 0);
    f = {
        x: 0,
        y: f
    };
    a.localToWorld(f);
    d.translate(f.x, f.y);
    d.rotate(a.rotate);
    d.translate(-1 * (this.handleImg.width * j / 2 + 0.5 | 0), -1 * (this.handleImg.height * j / 2 + 0.5 | 0));
    d.scale(j, j);
    d.drawImage(this.handleImg, 0, 0);
    d.scale(1, 1);
    d.setTransform(1, 0, 0, 1, 0, 0)
};
AV.PaintWidget.prototype.uiLayerClear = function() {
    var a = this.getLayerByName("_ui"),
        b = a.canvas.getContext("2d");
    b.globalCompositeOperation = "copy";
    b.clearRect(0, 0, a.canvas.width, a.canvas.height);
    b.globalCompositeOperation = "source-over"
};
AV.PaintWidget.prototype.uiLayerDrawCircleSelection = function(a, b, f, j) {
    var l = this.getLayerByName("_ui"),
        d = l.canvas.getContext("2d"),
        e = 1;
    j || (d.globalCompositeOperation = "copy", d.clearRect(0, 0, l.canvas.width, l.canvas.height));
    j = 1;
    AV.controlsWidgetInstance.canvasUI && (j = AV.controlsWidgetInstance.canvasUI.viewport.getRatio());
    e = e * j + 0.5 | 0;
    d.globalCompositeOperation = "source-over";
    d.lineWidth = e;
    d.globalAlpha = 1;
    d.beginPath();
    d.strokeStyle = "#ffffff";
    d.arc(a, b, f, 0, 2 * Math.PI, !1);
    d.closePath();
    d.stroke();
    f -= e;
    0 < f && (d.beginPath(), d.strokeStyle = "#000000", d.arc(a, b, f, 0, 2 * Math.PI, !1), d.closePath(), d.stroke())
};
AV.PaintWidget.prototype.dirtyRectEnable = function(a) {
    this.dirtyRect.dirty = a
};
AV.PaintWidget.prototype.recomposite = function(a) {
    var b;
    AV.cnvs.clearCanvas(this.canvas);
    var f = this.canvas.getContext("2d");
    null == a ? a = 0 : (a--, 0 > a && (a = 0));
    for (f.setTransform(1, 0, 0, 1, 0, 0); a < this.layers.length; a++) b = this.layers[a], b.hidden || (f.save(), this.dirtyRect.dirty && (f.beginPath(), f.rect(this.dirtyRect.bbox.x0, this.dirtyRect.bbox.y0, this.dirtyRect.bbox.w, this.dirtyRect.bbox.h), f.clip(), f.closePath()), f.translate(b.translateX, b.translateY), f.rotate(b.rotate), f.scale(b.scaleX, b.scaleY), null != b.centerX && null != b.centerY && f.translate(-b.centerX, -b.centerY), f.globalAlpha = null != b.alpha ? b.alpha : 1, f.drawImage(b.drawable, 0, 0), f.restore());
    null != this.debugPoint && (f.setTransform(1, 0, 0, 1, 0, 0), f.strokeStyle = "#00ffff", f.lineWidth = 1, f.lineCap = "round", f.lineJoin = "round", f.strokeRect(this.debugPoint.x - 2, this.debugPoint.y - 2, 4, 4))
};
AV.PaintWidget.prototype.makeThumbFlat = function(a, b) {
    this.recomposite();
    var f = a.width,
        j = a.height,
        l = a.getContext("2d");
    l.globalCompositeOperation = "copy";
    l.clearRect(0, 0, a.width, a.height);
    l.globalCompositeOperation = "source-over";
    var d = f / this.canvas.width,
        e = j / this.canvas.height;
    b ? d > e ? (d = this.canvas.width * e, l.drawImage(this.canvas, 0, 0, this.canvas.width, this.canvas.height, (f - d) / 2, 0, d, j)) : (d *= this.canvas.height, l.drawImage(this.canvas, 0, 0, this.canvas.width, this.canvas.height, 0, (j - d) / 2, f, d)) : d < e ? (d = this.canvas.width * e, l.drawImage(this.canvas, 0, 0, this.canvas.width, this.canvas.height, -(d - f) / 2, 0, d, j)) : (d *= this.canvas.height, l.drawImage(this.canvas, 0, 0, this.canvas.width, this.canvas.height, 0, -(d - j) / 2, f, d));
    return a
};
AV.PaintWidget.prototype.exportImage = function(a, b) {
    a = a || "image/png";
    this.uiLayerShow(!1);
    this.recomposite();
    var f;
    f = 0 == document.createElement("canvas").toDataURL("image/png").indexOf("data:image/png") ? this.canvas.toDataURL(a) : AV.toBitmapURL(this.canvas);
    var j = f.indexOf(";", 0),
        l = f.indexOf(",", j);
    if (b && "function" === typeof b) {
        var d = this;
        this.showWaitThrobber(!0, function() {
            b.apply(this, [f]);
            d.showWaitThrobber(false)
        })
    }
    return {
        mimeType: f.slice(5, j),
        base64data: f.slice(l + 1)
    }
};
AV.PaintWidget.prototype.addLayer = function(a) {
    this.layers.push(a);
    return this.layers.length - 1
};
AV.PaintWidget.prototype.setMode = function(a) {
    null !== this.active && void 0 !== this.active.deactivate && this.active.deactivate();
    null !== a && void 0 !== a && "" !== a ? (a = this.module[a], void 0 !== a && (this.active = a, void 0 !== this.active.activate && this.active.activate(this))) : this.active = null
};
AV.PaintWidget.prototype.undo = function() {
    this.active && void 0 !== this.active.userUndo && this.active.userUndo();
    this.actions.undo();
    this.active && void 0 !== this.active.userPostUndo && this.active.userPostUndo()
};
AV.PaintWidget.prototype.redo = function() {
    this.active && void 0 !== this.active.userRedo && this.active.userRedo();
    this.actions.redo();
    this.active && void 0 !== this.active.userPostRedo && this.active.userPostRedo()
};
AV.PaintWidget.prototype.getBacking = function(a, b, f, j) {
    return this.canvas.getContext("2d").getImageData(a, b, f, j)
};
AV.PaintWidget.prototype.getBackingAll = function() {
    return this.canvas.getContext("2d").getImageData(0, 0, this.canvas.width, this.canvas.height)
};
AV.PaintWidget.prototype.putBacking = function(a, b, f, j) {
    a.canvas.getContext("2d").putImageData(b, f, j)
};
AV.PaintWidget.prototype.rotate90 = function() {
    var a, b, f = function(a, b) {
        var d;
        a.rotate += b * Math.PI / 180;
        90 == b ? (d = a.translateX, a.translateX = this.canvas.width - a.translateY, a.translateY = d) : 270 == b ? (d = a.translateX, a.translateX = a.translateY, a.translateY = this.canvas.height - d) : 180 == b && (a.translateX = this.canvas.width - a.translateX, a.translateY = this.canvas.height - a.translateY)
    };
    return function(j) {
        j %= 360;
        0 > j && (j += 360);
        j = 90 * parseInt(j / 90, 10);
        if (0 != j) {
            0 != j % 180 && this.setDimensions(this.canvas.height, this.canvas.width);
            for (a = 0; a < this.layers.length; a++) {
                var l = this.layers[a];
                if (null != l.canvas) if ("module_text" == l.tag) f(l, j);
                else {
                    var d = document.createElement("canvas");
                    d.width = this.canvas.width;
                    d.height = this.canvas.height;
                    b = d.getContext("2d");
                    b.setTransform(1, 0, 0, 1, 0, 0);
                    b.translate(d.width / 2, d.height / 2);
                    b.rotate(3.14159265358979 * j / 180);
                    b.translate(-l.canvas.width / 2, -l.canvas.height / 2);
                    b.globalCompositeOperation = "copy";
                    b.drawImage(l.canvas, 0, 0);
                    b.globalCompositeOperation = "source-over";
                    b.setTransform(1, 0, 0, 1, 0, 0);
                    l.canvas =
                        l.drawable = d
                } else null != l.image && f(l, j)
            }
            this.recomposite()
        }
    }
}();
AV.PaintWidget.prototype._dupLayerAttrs = function(a, b) {
    !0 == b.hidden && (a.hidden = !0);
    void 0 != b.alpha && (a.alpha = b.alpha);
    a.rotate = b.rotate;
    a.scaleX = b.scaleX;
    a.scaleY = b.scaleY;
    a.translateX = b.translateX;
    a.translateY = b.translateY;
    null != b.centerX && (a.centerX = b.centerX);
    null != b.centerY && (a.centerY = b.centerY);
    null != b.tag && (a.tag = b.tag);
    null != b.module_data && (a.module_data = b.module_data)
};
AV.PaintWidget.prototype.duplicateAllLayers = function() {
    var a = [],
        b, f;
    for (b = 0; b < this.layers.length; b++) if ("_ui" != this.layers[b].name) if (null != this.layers[b].canvas) {
        var j = document.createElement("canvas");
        j.width = this.layers[b].canvas.width;
        j.height = this.layers[b].canvas.height;
        f = j.getContext("2d");
        f.drawImage(this.layers[b].canvas, 0, 0, j.width, j.height);
        f = new AV.Layer({
            canvas: j,
            name: this.layers[b].name
        });
        this._dupLayerAttrs(f, this.layers[b]);
        a.push(f)
    } else null != this.layers[b].image && (f = new AV.Layer({
        image: this.layers[b].image,
        name: this.layers[b].name
    }), this._dupLayerAttrs(f, this.layers[b]), a.push(f));
    return a
};
AV.PaintWidget.prototype.duplicateAllLayersFrom = function(a) {
    var b, f;
    for (b = 0; b < a.length; b++) if ("_ui" != a[b].name) {
        var j = this.getLayerByName(a[b].name);
        null === j && (j = new AV.Layer({
            name: a[b].name
        }), this.layers.push(j));
        null != a[b].canvas ? (null == j.canvas && (j.canvas = document.createElement("canvas")), j.drawable = j.canvas, j.canvas.width = a[b].canvas.width, j.canvas.height = a[b].canvas.height, f = j.canvas.getContext("2d"), f.drawImage(a[b].canvas, 0, 0, a[b].canvas.width, a[b].canvas.height), this._dupLayerAttrs(j, a[b])) : null != a[b].image && (j.image = a[b].image, j.drawable = j.image, this._dupLayerAttrs(j, a[b]))
    }
};
AV.PaintWidget.prototype.resizeAllLayers = function(a, b, f) {
    var j, l, d = [],
        e = this.width,
        h;
    this.setDimensions(a, b);
    for (j = 0; j < this.layers.length; j++) null != this.layers[j].canvas ? (h = document.createElement("canvas"), h.width = a, h.height = b, l = h.getContext("2d"), l.drawImage(this.layers[j].canvas, 0, 0, a, b), l = new AV.Layer({
        canvas: h,
        name: this.layers[j].name
    }), this._dupLayerAttrs(l, this.layers[j]), d.push(l)) : null != this.layers[j].image && (f ? (h = a / e, l = new AV.Layer({
        image: this.layers[j].image,
        name: this.layers[j].name
    }), this._dupLayerAttrs(l, this.layers[j]), l.translateX *= h, l.translateY *= h, l.scaleX *= h, l.scaleY *= h) : (l = new AV.Layer({
        image: this.layers[j].image,
        name: this.layers[j].name
    }), this._dupLayerAttrs(l, this.layers[j])), d.push(l));
    this.layers = d;
    this.recomposite()
};
AV.PaintWidget.prototype.resizeAllLayersQnD = function(a, b) {
    var f;
    this.setDimensions(a, b);
    for (f = 0; f < this.layers.length; f++) null != this.layers[f].canvas && (this.layers[f].canvas.width = a, this.layers[f].canvas.height = b)
};
AV.PaintWidget.prototype.flattenAllLayers = function() {
    var a = this.getLayerByName("_ui");
    this.uiLayerShow(!1);
    this.recomposite();
    this.layers.length = 0;
    var b = document.createElement("canvas");
    b.width = this.canvas.width;
    b.height = this.canvas.height;
    this.layers.push(new AV.Layer({
        canvas: b,
        name: "background"
    }));
    a && this.layers.push(a);
    b.getContext("2d").drawImage(this.canvas, 0, 0)
};
AV.PaintWidget.prototype.cropAllLayers = function(a, b, f, j) {
    var l, d, e, h = [];
    this.setDimensions(f, j);
    for (l = 0; l < this.layers.length; l++) {
        e = this.layers[l];
        if (null != e.canvas) d = "module_text" == e.tag ? "textcanvas" : "canvas";
        else if (null != e.image) d = "image";
        else
            continue;
        switch (d) {
            case "canvas":
                var t = document.createElement("canvas");
                t.width = f;
                t.height = j;
                d = t.getContext("2d");
                d.drawImage(this.layers[l].canvas, -a, -b, this.layers[l].canvas.width, this.layers[l].canvas.height);
                e = new AV.Layer({
                    canvas: t,
                    name: this.layers[l].name
                });
                this._dupLayerAttrs(e, this.layers[l]);
                h.push(e);
                break;
            case "image":
                e = new AV.Layer({
                    image: this.layers[l].image,
                    name: this.layers[l].name
                });
                this._dupLayerAttrs(e, this.layers[l]);
                e.translateX -= a;
                e.translateY -= b;
                h.push(e);
                break;
            case "textcanvas":
                t = document.createElement("canvas"), d = t.getContext("2d"), t.width = e.canvas.width, t.height = e.canvas.height, d.drawImage(this.layers[l].canvas, 0, 0), e = new AV.Layer({
                    canvas: t,
                    name: this.layers[l].name
                }), this._dupLayerAttrs(e, this.layers[l]), e.translateX -= a, e.translateY -= b, h.push(e)
        }
    }
    this.layers = h;
    this.recomposite()
};
AV.PaintWidget.prototype.module = {};
AV.PaintWidget.prototype.module.bulge = function() {
    var a, b = 100,
        f = 0,
        j = {},
        l = function(a, b, d, e, f) {
            var n = 2 * e + 1,
                p = 2 * e + 1,
                d = AV.cnvs.getImageDataRegion(a, b - e, d - e, n, p, !0),
                a = a.getContext("2d").createImageData(n, p),
                b = a.data,
                d = d.data,
                k = e * e,
                j;
            j = 0 <= f ? -f / 120 : -f / 200;
            f = 1 - j;
            j /= e;
            for (var l = 0, r = 0; r < p; r++) for (var x = r - e, u = x * x, w = 0; w < n; w++) {
                var v = w - e,
                    y = v * v + u;
                if (y < k) {
                    var y = Math.sqrt(y),
                        y = 0.999999 / (f + j * y),
                        v = v * y + e,
                        y = x * y + e,
                        C = v | 0,
                        z = y | 0,
                        v = v - C,
                        y = y - z,
                        D = 1 - v,
                        A = 1 - y,
                        G = 4 * (C + z * n),
                        C = d[G] * D + d[G + 4] * v,
                        z = d[G + 1] * D + d[G + 5] * v,
                        J = d[G + 2] * D + d[G + 6] * v,
                        K = d[G + 3] * D + d[G + 7] * v,
                        G = G + 4 * n,
                        N = d[G] * D + d[G + 4] * v,
                        O = d[G + 1] * D + d[G + 5] * v,
                        L = d[G + 2] * D + d[G + 6] * v,
                        v = d[G + 3] * D + d[G + 7] * v;
                    b[l++] = C * A + N * y;
                    b[l++] = z * A + O * y;
                    b[l++] = J * A + L * y;
                    b[l++] = K * A + v * y
                } else b[l] = d[l], l++, b[l] = d[l], l++, b[l] = d[l], l++, b[l] = d[l], l++
            }
            return a
        },
        d = function(b, d, e, f) {
            b = a.getLayerByName(b).canvas.getContext("2d");
            b.globalCompositeOperation = "copy";
            b.drawImage(d, e, f);
            b.globalCompositeOperation = "source-over";
            a.recomposite()
        },
        e = function(b, d, e, f, g) {
            b = a.getLayerByName(b).canvas;
            g = l(b, d, e, f, g);
            AV.cnvs.putImageDataRegion(b, g, d - f, e - f);
            a.recomposite()
        };
    j.activate = function(b) {
        a = b;
        a.uiLayerReset();
        a.uiLayerShow(!0)
    };
    j.deactivate = function() {
        a.uiLayerShow(!1)
    };
    j.userUndo = function() {
        a.uiLayerShow(!1)
    };
    j.userRedo = function() {
        a.uiLayerShow(!1)
    };
    j.setRadius = function(a) {
        b = a
    };
    j.setPower = function(a) {
        f = a
    };
    j.updateUI = function(d, e) {
        var m = a.layers[a.currentLayerIndex].canvas;
        if (null != m && !(0 > d || d >= m.width || 0 > e || e >= m.height)) {
            var o = b,
                m = l(m, d, e, o, f);
            a.uiLayerShow(!0);
            a.uiLayerClear();
            AV.cnvs.putImageDataRegion(a.getLayerByName("_ui").canvas, m, d - o, e - o);
            a.uiLayerDrawCircleSelection(d, e, o, !0);
            a.recomposite()
        }
    };
    j.apply = function(h, l) {
        var m = a.layers[a.currentLayerIndex],
            o = m.canvas;
        if (null != o && !(0 > h || h >= o.width || 0 > l || l >= o.height)) {
            var g = h - b,
                n = l - b,
                p = 2 * b + 1,
                o = AV.cnvs.copyCanvas(o, g, n, p, p);
            a.actions.push([d, this, [m.name, o, g, n]], [e, this, [m.name, h, l, b, f]], {
                action: "bulge",
                center: [h, l],
                radius: b,
                power: f
            });
            a.actions.redo();
            return b
        }
    };
    return j
}();
AV.PaintWidget.prototype.module.whiten = function() {
    var a, b = new AV.BrushModule({
            layerName: "spot_tool"
        }),
        f = {},
        j = function(b, e, f, l) {
            a.getLayerByName(b).canvas.getContext("2d").drawImage(e, f, l);
            a.recomposite()
        },
        l = function(b, e, f) {
            var b = a.getLayerByName(b),
                l = b.canvas.getContext("2d");
            _origBackingPixels = AV.cnvs.getCanvasPixelData(b.canvas);
            _origBacking = AV.cnvs.copyCanvas(b.canvas);
            _newCanvasData = l.createImageData(b.canvas.width, b.canvas.height);
            a.filterManager.getEffectById("whiten2")(_newCanvasData.data, _origBackingPixels.data, b.canvas.width, b.canvas.height, e, f);
            l.putImageData(_newCanvasData, 0, 0);
            a.recomposite()
        };
    f.activate = function(d) {
        a = d;
        b.activate(a)
    };
    f.deactivate = b.deactivate;
    f.userUndo = b.userUndo;
    f.userRedo = b.userRedo;
    f.updateUIDown = b.updateUIDown;
    f.updateUIMove = b.updateUIMove;
    f.updateUIDraw = b.updateUIDraw;
    f.apply = function() {
        b.commit("whiten2", j, l)
    };
    f.radius = b.radius;
    f.setRadius = b.setRadius;
    f.readAction = function(a, b) {
        var h, l, m;
        if (a && a.radius && a.pointlist) {
            l = a.pointlist.length;
            f.setRadius(a.radius);
            for (h = 0; h < l; h++) m = a.pointlist[h], 0 === h ? f.updateUIDown(m[0], m[1]) : f.updateUIDraw(m[0], m[1]);
            f.apply()
        }
        b && b.call(this)
    };
    return f
}();
AV.PaintWidget.prototype.module.redeye = function() {
    var a, b = "redeye",
        f = new AV.BrushModule({
            layerName: "spot_tool"
        }),
        j = {},
        l = function(b, d, e, f) {
            a.getLayerByName(b).canvas.getContext("2d").drawImage(d, e, f);
            a.recomposite()
        },
        d = function(b, d, e) {
            var b = a.getLayerByName(b),
                f = b.canvas.getContext("2d");
            _origBackingPixels = AV.cnvs.getCanvasPixelData(b.canvas);
            _origBacking = AV.cnvs.copyCanvas(b.canvas);
            _newCanvasData = f.createImageData(b.canvas.width, b.canvas.height);
            a.filterManager.getEffectById("redeye2")(_newCanvasData.data, _origBackingPixels.data, b.canvas.width, b.canvas.height, d, e);
            f.putImageData(_newCanvasData, 0, 0);
            a.recomposite()
        },
        e = function(b, d, e) {
            var b = a.getLayerByName(b),
                f = e * e,
                g = 2 * e,
                n, p, k, l, j, r, x, u, w, v, y;
            for (l = 0; l < d.length; l++) {
                var C = d[l][0],
                    z = d[l][1],
                    D = AV.cnvs.getImageDataRegion(b.canvas, C - e, z - e, g, g);
                j = 0;
                for (p = -e; p < e; p++) for (n = -e; n < e; n++, j += 4) k = n * n + p * p, k > f || (k = Math.sqrt(k), y = k / e, y = 2 * y - 1, 0 > y && (y = 0), k = D.data[j], r = D.data[j + 1], x = D.data[j + 2], 128 < k || 128 < r || 128 < x ? (u = 0.8 * k, w = 0.8 * r, v = 0.8 * x) : (u = k, w = r, v = x), D.data[j] = u * (1 - y) + k * y, D.data[j + 1] = w * (1 - y) + r * y, D.data[j + 2] = v * (1 - y) + x * y);
                AV.cnvs.putImageDataRegion(b.canvas, D, C - e, z - e)
            }
            a.recomposite()
        };
    j.activate = function(b) {
        a = b;
        f.activate(a)
    };
    j.deactivate = f.deactivate;
    j.userUndo = f.userUndo;
    j.userRedo = f.userRedo;
    j.updateUIDown = f.updateUIDown;
    j.updateUIMove = f.updateUIMove;
    j.updateUIDraw = f.updateUIDraw;
    j.apply = function() {
        "greeneye" == b ? f.commit("greeneye", l, e) : f.commit("redeye2", l, d)
    };
    j.radius = f.radius;
    j.setRadius = f.setRadius;
    j.setMode = function(a) {
        b = a
    };
    j.readAction = function(a, b) {
        var d, e, f;
        if (a && a.radius && a.pointlist) {
            e = a.pointlist.length;
            j.setRadius(a.radius);
            for (d = 0; d < e; d++) f = a.pointlist[d], 0 === d ? j.updateUIDown(f[0], f[1]) : j.updateUIDraw(f[0], f[1]);
            j.apply()
        }
        b && b.call(this)
    };
    return j
}();
AV.PaintWidget.prototype.module.blemish = function() {
    var a, b = new AV.BrushModule({
            layerName: "spot_tool"
        }),
        f = {},
        j = function(b, e, f, l) {
            a.getLayerByName(b).canvas.getContext("2d").drawImage(e, f, l);
            a.recomposite()
        },
        l = function(b, e, f) {
            var b = a.getLayerByName(b),
                l = b.canvas.getContext("2d");
            _origBackingPixels = AV.cnvs.getCanvasPixelData(b.canvas);
            _origBacking = AV.cnvs.copyCanvas(b.canvas);
            _newCanvasData = l.createImageData(b.canvas.width, b.canvas.height);
            a.filterManager.getEffectById("selectiveblur")(_newCanvasData.data, _origBackingPixels.data, b.canvas.width, b.canvas.height, e, f);
            l.putImageData(_newCanvasData, 0, 0);
            a.recomposite()
        };
    f.activate = function(d) {
        a = d;
        b.activate(a)
    };
    f.deactivate = b.deactivate;
    f.userUndo = b.userUndo;
    f.userRedo = b.userRedo;
    f.updateUIDown = b.updateUIDown;
    f.updateUIMove = b.updateUIMove;
    f.updateUIDraw = b.updateUIDraw;
    f.apply = function() {
        b.commit("selectiveblur", j, l)
    };
    f.radius = b.radius;
    f.setRadius = b.setRadius;
    f.readAction = function(a, b) {
        var h, l, m;
        if (a && a.radius && a.pointlist) {
            l = a.pointlist.length;
            f.setRadius(a.radius);
            for (h = 0; h < l; h++) m = a.pointlist[h], 0 === h ? f.updateUIDown(m[0], m[1]) : f.updateUIDraw(m[0], m[1]);
            f.apply()
        }
        b && b.call(this)
    };
    return f
}();
AV.PaintWidget.prototype.module.flip = function() {
    var a, b = {},
        f = function(a, b, d) {
            var f = a.getContext("2d");
            b && (b = AV.cnvs.copyCanvas(a), f.setTransform(-1, 0, 0, 1, a.width, 0), f.drawImage(b, 0, 0));
            d && (b = AV.cnvs.copyCanvas(a), f.setTransform(1, 0, 0, -1, 0, a.height), f.drawImage(b, 0, 0));
            f.setTransform(1, 0, 0, 1, 0, 0)
        },
        j = function(b, f, j) {
            var m = a.layers[a.currentLayerIndex],
                o;
            null != m.canvas && (j ? (o = a.duplicateAllLayers(), a.actions.push([l, this, [o]], [d, this, [m.name, b, f, j]], {
                action: "flip",
                horizontal: b,
                vertical: f,
                flatten: j
            })) : a.actions.push([d, this, [m.name, b, f, j]], [d, this, [m.name, b, f, j]], {
                action: "flip",
                horizontal: b,
                vertical: f,
                flatten: j
            }), a.actions.redo())
        },
        l = function(b) {
            a.duplicateAllLayersFrom(b);
            a.recomposite()
        },
        d = function(b, d, l, m) {
            m && a.flattenAllLayers();
            b = a.getLayerByName(b);
            f(b.canvas, d, l);
            a.recomposite()
        };
    b.activate = function(b) {
        a = b
    };
    b.makeThumb = function(b, d, l) {
        a.makeThumbFlat(b, !0);
        d && f(b, !0, !1);
        l && f(b, !1, !0)
    };
    b.flip = function(a, b, d) {
        j(a, b, d)
    };
    b.hflip = function(a) {
        j(!0, !1, a)
    };
    b.vflip = function(a) {
        j(!1, !0, a)
    };
    b.hvflip =

        function(a) {
            j(!0, !0, a)
        };
    return b
}();
AV.PaintWidget.prototype.module.desaturate = function() {
    var a, b = {},
        f = function(a) {
            for (var b = 0; b < a.length; b += 4) a[b] = a[b + 1] = a[b + 2] = (a[b] + a[b + 1] + a[b + 2]) / 3 | 0
        },
        j = function(b, e, f) {
            f ? a.duplicateAllLayersFrom(f) : (b = a.getLayerByName(b).canvas.getContext("2d"), b.globalCompositeOperation = "copy", b.drawImage(e, 0, 0), b.globalCompositeOperation = "source-over");
            a.recomposite()
        },
        l = function(b, e) {
            e && a.flattenAllLayers();
            var h = a.getLayerByName(b),
                l = h.canvas.getContext("2d"),
                m = l.getImageData(0, 0, h.canvas.width, h.canvas.height);
            a.showWaitThrobber(!0, function() {
                f(m.data);
                l.putImageData(m, 0, 0);
                a.recomposite();
                a.showWaitThrobber(false)
            })
        };
    b.activate = function(b) {
        a = b
    };
    b.makeThumb = function(b) {
        a.makeThumbFlat(b);
        var e = b.getContext("2d"),
            b = e.getImageData(0, 0, b.width, b.height);
        f(b.data);
        e.putImageData(b, 0, 0)
    };
    b.desaturate = function(b) {
        var e = a.layers[a.currentLayerIndex];
        if (null != e.canvas) {
            var f = null,
                t = null;
            b ? t = a.duplicateAllLayers() : f = AV.cnvs.copyCanvas(e.canvas);
            a.actions.push([j, this, [e.name, f, t]], [l, this, [e.name, b]], {
                action: "desaturate",
                flatten: b
            });
            a.actions.redo()
        }
    };
    return b
}();
AV.PaintWidget.prototype.module.saturation = function() {
    var a, b = 0,
        f, j, l, d, e, h, t = {},
        m = function(a, b, d) {
            var e, f, g, h, p, l, m = 0.213 * (1 - d),
                n = 0.715 * (1 - d),
                j = 0.072 * (1 - d);
            for (e = 0; 4 * e < b.length; e++) f = b[4 * e], g = b[4 * e + 1], h = b[4 * e + 2], p = (m + d) * f + n * g + j * h + 0.5 | 0, l = m * f + (n + d) * g + j * h + 0.5 | 0, h = m * f + n * g + (j + d) * h + 0.5 | 0, f = 255 < p ? 255 : 0 > p ? 0 : p, g = 255 < l ? 255 : 0 > l ? 0 : l, h = 255 < h ? 255 : 0 > h ? 0 : h, a[4 * e] = f, a[4 * e + 1] = g, a[4 * e + 2] = h, a[4 * e + 3] = b[4 * e + 3]
        },
        o = function(h, g, p) {
            var n = a.getLayerByName(h);
            if (null != n.canvas) {
                var o;
                o = n.canvas.getContext("2d");
                null == f && (p && (d = a.duplicateAllLayers(), a.flattenAllLayers(), n = a.getLayerByName(h), o = n.canvas.getContext("2d")), j = AV.cnvs.getCanvasPixelData(n.canvas), f = AV.cnvs.copyCanvas(n.canvas), l = o.createImageData(n.canvas.width, n.canvas.height));
                b = g;
                e = p;
                m(l.data, j.data, g);
                o.putImageData(l, 0, 0);
                a.recomposite()
            }
        },
        g = function() {
            var h = a.layers[a.currentLayerIndex];
            a.actions.push([n, this, [h.name, f, d]], [p, this, [h.name, b, e]], {
                action: "saturation",
                value: b,
                flatten: e
            });
            a.actions.redoFake()
        },
        n = function(b, d, e) {
            e ? a.duplicateAllLayersFrom(e) : (b = a.getLayerByName(b).canvas.getContext("2d"), b.globalCompositeOperation = "copy", b.drawImage(d, 0, 0), b.globalCompositeOperation = "source-over");
            a.recomposite()
        },
        p = function(a, b, e) {
            o(a, b, e);
            d = f = null;
            h = !0
        };
    t.set = function(b, d) {
        a.dirty = !0;
        var e = a.layers[a.currentLayerIndex];
        AV.util.nextFrame(function() {
            o(e.name, b, d)
        })
    };
    t.applyPreview = function(b, d) {
        var e = a.layers[a.currentLayerIndex];
        AV.util.nextFrame(function() {
            h ? (a.actions.undo(), h = !1) : a.actions.undoFake();
            o(e.name, b, d);
            g()
        })
    };
    t.activate = function(g) {
        a =
            g;
        d = l = f = j = null;
        b = 0;
        h = e = !1
    };
    t.deactivate = function() {
        t.reset()
    };
    t.makeThumb = function(b, d) {
        a.makeThumbFlat(b);
        var e = b.getContext("2d"),
            f = e.getImageData(0, 0, b.width, b.height);
        m(f.data, f.data, d);
        e.putImageData(f, 0, 0)
    };
    t.reset = function() {
        d = l = j = f = null
    };
    t.commit = function() {
        g();
        d = l = j = f = null
    };
    t.readAction = function(b, d) {
        b && void 0 !== b.value && (o(a.layers[a.currentLayerIndex].name, b.value, b.flatten), g());
        d && d.call(this)
    };
    return t
}();
AV.PaintWidget.prototype.module.brightness = function() {
    var a, b, f, j, l, d, e, h, t, m = {},
        o = function(b, d, e, f) {
            0 !== e ? a.filterManager.getEffectById("brightness")(b, d, e) : 0 !== f && a.filterManager.getEffectById("contrast")(b, d, f)
        },
        g = function(g, k, p, n) {
            var m = a.getLayerByName(g);
            if (null != m.canvas) {
                var w;
                w = m.canvas.getContext("2d");
                null == b && (n && (j = a.duplicateAllLayers(), a.flattenAllLayers(), m = a.getLayerByName(g), w = m.canvas.getContext("2d")), f = AV.cnvs.getCanvasPixelData(m.canvas), b = AV.cnvs.copyCanvas(m.canvas), d = w.createImageData(m.canvas.width, m.canvas.height));
                e = k;
                h = p;
                l = n;
                o(d.data, f.data, k, p);
                w.putImageData(d, 0, 0);
                a.recomposite()
            }
        },
        n = function(b, d, e) {
            e ? a.duplicateAllLayersFrom(e) : (b = a.getLayerByName(b).canvas.getContext("2d"), b.globalCompositeOperation = "copy", b.drawImage(d, 0, 0), b.globalCompositeOperation = "source-over");
            a.recomposite()
        },
        p = function(a, d, e, f) {
            g(a, d, e, f);
            j = b = null;
            t = !0
        },
        k = function() {
            var d = a.layers[a.currentLayerIndex];
            a.actions.push([n, this, [d.name, b, j]], [p, this, [d.name, e, h, l]], {
                action: 0 !== e ? "brightness" : "contrast",
                value: 0 !== e ? e : h,
                flatten: l
            });
            a.actions.redoFake()
        };
    m.activate = function(f) {
        a = f;
        j = d = b = null;
        h = e = 0;
        t = l = !1
    };
    m.deactivate = function() {
        m.reset()
    };
    m.set = function(b, d, e) {
        var f = a.layers[a.currentLayerIndex];
        AV.util.nextFrame(function() {
            g(f.name, b, d, e)
        });
        a.dirty = !0
    };
    m.applyPreview = function(b, d, e) {
        var f = a.layers[a.currentLayerIndex];
        AV.util.nextFrame(function() {
            t ? (a.actions.undo(), t = !1) : a.actions.undoFake();
            g(f.name, b, d, e);
            k()
        })
    };
    m.makeThumb = function(b, d, e) {
        a.makeThumbFlat(b);
        var f = b.getContext("2d"),
            b = f.getImageData(0, 0, b.width, b.height);
        o(b.data, b.data, d, e);
        f.putImageData(b, 0, 0)
    };
    m.reset = function() {
        j = d = f = b = null
    };
    m.commit = function() {
        k();
        j = d = f = b = null
    };
    m.readAction = function(b, d) {
        if (b && b.action && void 0 !== b.value) {
            var e = 0,
                f = 0,
                h = a.layers[a.currentLayerIndex];
            "brightness" == b.action ? e = b.value : "contrast" == b.action && (f = b.value);
            g(h.name, e, f, b.flatten);
            k()
        }
        d && d.call(this)
    };
    return m
}();
AV.PaintWidget.prototype.module.contrast = AV.PaintWidget.prototype.module.brightness;
AV.PaintWidget.prototype.module.colors = function() {
    var a, b = 0,
        f = 0,
        j = 0,
        l, d, e, h, t, m = {},
        o = function(a) {
            return (0.75 * (a + 100) + 50) / 100
        },
        g = function(b, d, e) {
            e ? a.duplicateAllLayersFrom(e) : (b = a.getLayerByName(b).canvas.getContext("2d"), b.globalCompositeOperation = "copy", b.drawImage(d, 0, 0), b.globalCompositeOperation = "source-over");
            a.recomposite()
        },
        n = function(a, b, e, f, h) {
            p(a, b, e, f, h);
            d = l = null
        },
        p = function(g, p, m, n, x) {
            var u = a.getLayerByName(g);
            if (null != u.canvas) {
                var w, v, y, C, z;
                v = u.canvas.getContext("2d");
                null == l && (x && (d =
                    a.duplicateAllLayers(), a.flattenAllLayers(), u = a.getLayerByName(g), v = u.canvas.getContext("2d")), e = AV.cnvs.getCanvasPixelData(u.canvas), l = AV.cnvs.copyCanvas(u.canvas), h = v.createImageData(u.canvas.width, u.canvas.height));
                b = p;
                j = n;
                f = m;
                t = x;
                y = o(p);
                C = o(m);
                z = o(n);
                a.showWaitThrobber(!0, function() {
                    for (w = 0; w < e.data.length;) {
                        h.data[w] = e.data[w] * y | 0;
                        w++;
                        h.data[w] = e.data[w] * C | 0;
                        w++;
                        h.data[w] = e.data[w] * z | 0;
                        w++;
                        h.data[w] = e.data[w];
                        w++
                    }
                    v.putImageData(h, 0, 0);
                    a.recomposite();
                    a.showWaitThrobber(false)
                })
            }
        };
    m.activate =

        function(e) {
            a = e;
            d = h = l = null;
            b = f = j = 0;
            t = !1
        };
    m.deactivate = function() {
        m.reset()
    };
    m.set = function(b, d, e, f) {
        a.dirty = !0;
        p(a.layers[a.currentLayerIndex].name, b, d, e, f)
    };
    m.reset = function() {
        if (d) a.duplicateAllLayersFrom(d), a.recomposite();
        else if (l) {
            var b = a.layers[a.currentLayerIndex].canvas.getContext("2d");
            b.globalCompositeOperation = "copy";
            b.drawImage(l, 0, 0);
            b.globalCompositeOperation = "source-over";
            a.recomposite()
        }
        d = h = e = l = null
    };
    m.commit = function() {
        if (null != l) {
            var p = a.layers[a.currentLayerIndex];
            a.actions.push([g, this, [p.name, l, d]], [n, this, [p.name, b, f, j, t]], {
                action: "colors",
                red: b,
                green: f,
                blue: j,
                flatten: t
            });
            a.actions.redoFake();
            d = h = e = l = null
        }
    };
    return m
}();
AV.PaintWidget.prototype.module.flashfilter = function() {
    var a, b = {
            toycamera: {
                url: AV.build.feather_baseURL + "ostrich/filters/ToyCamera_v02.swf",
                params: {
                    stampmode: "resize"
                }
            },
            polaroid: {
                url: AV.build.feather_baseURL + "ostrich/filters/Polaroid_v02.swf",
                params: {
                    stampmode: "resize"
                }
            },
            autocorrection: {
                url: AV.build.feather_baseURL + "ostrich/filters/AutoCorrection_v01.swf",
                params: {
                    stampmode: "normal"
                }
            },
            oldphoto: {
                url: AV.build.feather_baseURL + "ostrich/filters/OldPhoto_v02.swf",
                params: {
                    stampmode: "normal"
                }
            },
            colorgrading: {
                url: AV.build.feather_baseURL + "ostrich/filters/ColorGrading_v02.swf",
                params: {
                    stampmode: "normal"
                }
            },
            vignetteblur: {
                url: AV.build.feather_baseURL + "ostrich/filters/VignetteBlur_v02.swf",
                params: {
                    stampmode: "normal"
                }
            },
            colormatrix: {
                url: AV.build.feather_baseURL + "ostrich/filters/ColorMatrix_v02.swf",
                params: {
                    stampmode: "normal"
                }
            },
            filmgrain: {
                url: AV.build.feather_baseURL + "ostrich/filters/FilmGrain_v02.swf",
                params: {
                    stampmode: "normal"
                }
            },
            retro: {
                url: AV.build.feather_baseURL + "ostrich/filters/Retro_v01.swf",
                params: {
                    stampmode: "normal"
                }
            }
        },
        f = !1,
        j = null,
        l = null,
        d = null,
        e = null,
        h = null,
        t = {},
        m = function(b, d, e, f, h) {
            var g, l = 0,
                m = 0,
                n = [0, 0, 1],
                j = b.getContext("2d"),
                o, t;
            t = h ? h.stampmode : "normal";
            o = j.createImageData(e, f);
            for (j = o.data; m < d.length;) j[l++] = 1 != (g = d.charCodeAt(m++)) ? g : n[d.charCodeAt(m++)];
            switch (t) {
                case "resize":
                    a.resizeAllLayersQnD(e, f);
                    j = b.getContext("2d");
                    j.putImageData(o, 0, 0);
                    break;
                case "maxspect":
                    d = document.createElement("canvas");
                    d.width = e;
                    d.height = f;
                    j = d.getContext("2d");
                    j.putImageData(o, 0, 0);
                    j = b.getContext("2d");
                    j.fillStyle = h.fill ? h.fill : "#000";
                    j.fillRect(0, 0, b.width, b.height);
                    e > f ? (swidth = b.width, sheight = f * (b.width / e)) : (swidth = e * (b.height / f), sheight = b.height);
                    j.drawImage(d, (b.width - swidth) / 2, (b.height - sheight) / 2, swidth, sheight);
                    break;
                case "max":
                    d = document.createElement("canvas");
                    d.width = e;
                    d.height = f;
                    j = d.getContext("2d");
                    j.putImageData(o, 0, 0);
                    j = b.getContext("2d");
                    j.drawImage(d, 0, 0, b.width, b.height);
                    break;
                default:
                    j = b.getContext("2d"), j.putImageData(o, 0, 0)
            }
            return b
        },
        o = function(a, b, d, f, g, m, l, j) {
            j || (j = "");
            if (null == e) {
                e = document.getElementById("avpw_OstrichFeather");
                h = [];
                for (var n = 0; 256 > n; n++) h[n] = String.fromCharCode(n);
                h[0] = String.fromCharCode(1) + String.fromCharCode(1);
                h[1] = String.fromCharCode(1) + String.fromCharCode(2)
            }
            try {
                var o = b.toDataURL("image/png");
                e.setPNG(a, o.substr(o.indexOf(",") + 1))
            } catch (t) {
                var n = b.getContext("2d"),
                    z;
                try {
                    try {
                        z = n.getImageData(d, f, g, m)
                    } catch (D) {
                        netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserRead"), z = n.getImageData(d, f, g, m)
                    }
                } catch (A) {
                    throw Error("unable to access image data: " + A);
                }
                z = z.data;
                n = "";
                for (o = 0; o < z.length; o += 4) n += h[z[o + 3]], n += h[z[o]], n += h[z[o + 1]], n += h[z[o + 2]];
                e.setBitmap(a, g, m, n)
            }
            e.runFilter(a, b.id, d, f, l, j)
        },
        g = function(b, d, e, f, h) {
            a.setDimensions(f, h);
            e ? a.duplicateAllLayersFrom(e) : (b = a.getLayerByName(b).canvas.getContext("2d"), b.globalCompositeOperation = "copy", b.drawImage(d, 0, 0), b.globalCompositeOperation = "source-over");
            a.recomposite()
        },
        n = function(e, f, h, g, n) {
            a.busy = !0;
            g && a.flattenAllLayers();
            var m = a.getLayerByName(e);
            j = m;
            l = f;
            d = n;
            a.showWaitThrobber(!0, function() {
                o("ostrich_" + m.name, m.drawable, 0, 0, m.drawable.width, m.drawable.height, b[f].url, h)
            })
        };
    t.activate = function(b) {
        f = !1;
        a = b
    };
    t.deactivate = function() {
        a.busy && (f = !0, a.actions.undoFake(), "function" == typeof d && d(), d = null, a.busy = !1)
    };
    t.makeThumb = function(d, e, f) {
        a.makeThumbFlat(d);
        o("ostrich_" + d.id, d, 0, 0, d.width, d.height, b[e].url, f)
    };
    t.applyFilter = function(b, d, e, f) {
        var h = a.layers[a.currentLayerIndex];
        if (null != h.canvas) {
            var m = null,
                l = null,
                j = a.canvas.width,
                o = a.canvas.height;
            e ? l = a.duplicateAllLayers() : m = AV.cnvs.copyCanvas(h.canvas);
            a.actions.push([g, this, [h.name, m, l, j, o]], [n, this, [h.name, b, d.flash, e, f]], {
                action: b,
                params: d.server,
                flatten: e
            });
            a.actions.redo()
        }
    };
    t.makeThumbCallback = function(a, b, d, e) {
        a = document.getElementById(a);
        m(a, b, d, e, {
            stampmode: "maxspect",
            fill: "#fff"
        })
    };
    t.flashFilterResult = function(e, h, g, n) {
        f ? f = !1 : (m(j.canvas, h, g, n, b[l].params), a.recomposite(), a.showWaitThrobber(!1), a.busy = !1, "function" == typeof d && d())
    };
    return t
}();
AV.module_flashfilter_onFilterComplete = function(a, b, f, j, l, d, e) {
    "_thumb" == a.substr(a.lastIndexOf("_")) ? AV.paintWidgetInstance.module.flashfilter.makeThumbCallback(a, e, j, l) : AV.paintWidgetInstance.module.flashfilter.flashFilterResult(a, e, j, l)
};
AV.PaintWidget.prototype.module.drawing = function() {
    var a, b = 10,
        f = "#ffffff",
        j = !1,
        l = new AV.BrushModule({
            overflow: 10,
            preserveBacking: !0
        }),
        d = {
            activate: function(b) {
                a = b;
                l.activate(a)
            }
        };
    d.deactivate = l.deactivate;
    d.userUndo = l.userUndo;
    d.userRedo = l.userRedo;
    d.updateUIDown = l.updateUIDown;
    d.updateUIMove = l.updateUIMove;
    d.updateUIDraw = l.updateUIDraw;
    d.apply = function() {
        l.commit("drawing")
    };
    d.width = function() {
        return b
    };
    d.setWidth = function(a) {
        b = a;
        l.setRadius(b / 2)
    };
    d.color = function() {
        return f
    };
    d.setColor = function(a) {
        f =
            a;
        l.setColor(f)
    };
    d.erase = function() {
        return j
    };
    d.setErase = function(a) {
        j = a;
        l.setErase(j)
    };
    d.readAction = function(a, b) {
        var f, m, l;
        if (a && a.color && a.radius && a.pointlist) {
            m = a.pointlist.length;
            d.setColor(a.color);
            d.setWidth(2 * a.radius);
            for (f = 0; f < m; f++) l = a.pointlist[f], 0 === f ? d.updateUIDown(l[0], l[1]) : d.updateUIDraw(l[0], l[1]);
            d.apply()
        }
        b && b.call(this)
    };
    return d
}();
AV.PaintWidget.prototype.module.straighten = function() {
    var a, b, f, j = {},
        l = function(d) {
            var f, e, l, g;
            AV.controlsWidgetInstance && AV.controlsWidgetInstance.canvasUI && AV.controlsWidgetInstance.canvasUI.viewport.getRatio();
            var n = a.getLayerByName("_ui");
            b.x0 < b.x1 ? (f = b.x0, l = b.x1) : (f = b.x1, l = b.x0);
            b.y0 < b.y1 ? (e = b.y0, g = b.y1) : (e = b.y1, g = b.y0);
            var p = n.canvas.getContext("2d");
            p.globalCompositeOperation = "copy";
            p.clearRect(0, 0, n.canvas.width, n.canvas.height);
            p.globalCompositeOperation = "source-over";
            if (d) {
                var k = a.width / 2,
                    j = a.height / 2;
                p.translate(k, j);
                p.rotate(-d);
                p.translate(-k, -j)
            }
            p.fillStyle = "#000000";
            p.globalAlpha = 0.6;
            d = n.canvas.width > n.canvas.height ? n.canvas.width : n.canvas.height;
            d = 0.5 * d | 0;
            p.fillRect(-d, -d, f + d, n.canvas.height + 2 * d);
            p.fillRect(l, -d, n.canvas.width - l + d, n.canvas.height + 2 * d);
            p.fillRect(f, -d, l - f, e + d);
            p.fillRect(f, g, l - f, n.canvas.height - g + 2 * d)
        },
        d = function(b, d, f) {
            a.setDimensions(d, f);
            a.duplicateAllLayersFrom(b);
            a.recomposite()
        },
        e = function(b, d, f, e) {
            e && a.flattenAllLayers();
            if (b) {
                for (i = 0; i < a.layers.length; i++) {
                    var g =
                        a.layers[i];
                    null != g.canvas && (e = document.createElement("canvas"), e.width = d, e.height = f, c = e.getContext("2d"), c.setTransform(1, 0, 0, 1, 0, 0), c.translate(d / 2, f / 2), c.rotate(b), c.translate(-g.canvas.width / 2, -g.canvas.height / 2), c.globalCompositeOperation = "copy", c.drawImage(g.canvas, 0, 0), c.globalCompositeOperation = "source-over", g.canvas = g.drawable = e)
                }
                a.cropAllLayers(0, 0, d, f);
                a.recomposite()
            }
        };
    j.activate = function(d) {
        a = d;
        b = new AV.Bbox;
        a.uiLayerReset();
        a.uiLayerShow(!0)
    };
    j.deactivate = function() {
        a.uiLayerShow(!1);
        b = null
    };
    j.clearSelection = function() {
        a.uiLayerReset();
        a.uiLayerShow(!0);
        a.recomposite()
    };
    j.setSelectionByRotation = function(d) {
        var e = a.width,
            j = a.height,
            o = e / 2,
            g = j / 2;
        b.x0 = 0;
        b.x1 = e;
        b.y0 = 0;
        b.y1 = j;
        if (d) {
            for (var n = Math.cos(d), p = Math.sin(d), k = Math.cos(-d), q = Math.sin(-d), s = o + n * (0 - o) + p * (0 - g), r = o + n * (0 - o) + p * (j - g), x = o + n * (e - o) + p * (0 - g), u = o + n * (e - o) + p * (j - g), w = g + p * -(0 - o) + n * (0 - g), v = g + p * -(0 - o) + n * (j - g), y = g + p * -(e - o) + n * (0 - g), n = g + p * -(e - o) + n * (j - g), s = [
                [s, w, x, y],
                [x, y, u, n],
                [u, n, r, v],
                [r, v, s, w]
            ], r = [0, 0, e, j], j = [e, 0, 0, j], C = [], x =

                function(a) {
                    var d = o + k * (a.x - o) + q * (a.y - g),
                        e = g + q * -(a.x - o) + k * (a.y - g);
                    b.contains(d | 0, e | 0) && C.push({
                        x: a.x,
                        y: a.y
                    })
                }, e = 0; e < s.length; e++) u = s[e], w = {}, AV.math.lineSegmentIntersection(u[0], u[1], u[2], u[3], r[0], r[1], r[2], r[3], w) && x(w), AV.math.lineSegmentIntersection(u[0], u[1], u[2], u[3], j[0], j[1], j[2], j[3], w) && x(w);
            for (e = 0; e < C.length; e++)(w = C[e], w.y > g ? w.y < b.y1 && (b.y1 = w.y) : w.y > b.y0 && (b.y0 = w.y), w.x > o) ? w.x < b.x1 && (b.x1 = w.x) : w.x > b.x0 && (b.x0 = w.x)
        }
        a.uiLayerReset();
        a.uiLayerShow(!0);
        l(d);
        a.recomposite();
        f = b;
        return (d = b.x1 - b.x0) ? a.width / d : 1
    };
    j.straighten = function(d, e, l) {
        f = f || b;
        j.straightenRect(d, e, (f.x1 + 0.5 | 0) - (f.x0 + 0.5 | 0), (f.y1 + 0.5 | 0) - (f.y0 + 0.5 | 0), l);
        a.uiLayerShow(!1)
    };
    j.straightenRect = function(b, f, l, j, g) {
        var n = a.duplicateAllLayers();
        a.actions.push([d, this, [n, a.width, a.height]], [e, this, [b, l, j, g]], [{
            action: "rotate",
            angle: f,
            width: l,
            height: j,
            flatten: g
        }, {
            action: "setfeathereditsize",
            width: l,
            height: j
        }], a.c2a(l, j, !0));
        a.actions.redo()
    };
    j.readAction = function(a, b) {
        var d, e;
        a && (a.angle && a.width && a.height) && (e = a.angle, d = e * Math.PI / 180, j.straightenRect(d, e, a.width, a.height, a.flatten));
        b && b.call(this)
    };
    return j
}();
AV.PaintWidget.prototype.module.rotate90 = function() {
    var a, b = {},
        f = function(b, d, e) {
            a.setDimensions(d, e);
            a.duplicateAllLayersFrom(b);
            a.recomposite()
        },
        j = function(b, d) {
            d && a.flattenAllLayers();
            a.rotate90(b)
        };
    b.activate = function(b) {
        a = b
    };
    b.makeThumb = function(b, d) {
        var e, f, d = d % 360;
        0 > d && (d += 360);
        d = 90 * parseInt(d / 90, 10);
        0 != d % 180 ? (e = b.height, f = b.width) : (e = b.width, f = b.height);
        var j = document.createElement("canvas");
        j.width = b.width;
        j.height = b.height;
        a.makeThumbFlat(j, !0);
        b.width = e;
        b.height = f;
        e = b.getContext("2d");
        e.setTransform(1, 0, 0, 1, 0, 0);
        e.translate(b.width / 2, b.height / 2);
        e.rotate(d * Math.PI / 180);
        e.translate(-j.width / 2, -j.height / 2);
        e.globalCompositeOperation = "copy";
        e.drawImage(j, 0, 0);
        e.globalCompositeOperation = "source-over";
        e.setTransform(1, 0, 0, 1, 0, 0)
    };
    b.rotate90 = function(b, d) {
        var e, h, t, m = a.getScaledSize();
        d ? (e = a.duplicateAllLayers(), h = a.width, t = a.height, a.actions.push([f, this, [e, h, t]], [j, this, [b, d]], {
            action: "rotate90",
            angle: b,
            flatten: d
        }, m ? {
            width: m.height,
            height: m.width
        } : null)) : a.actions.push([j, this, [-b, d]], [j, this, [b, d]], {
            action: "rotate90",
            angle: b,
            flatten: d
        }, m ? {
            width: m.height,
            height: m.width
        } : null);
        a.actions.redo()
    };
    return b
}();
AV.PaintWidget.prototype.module.resize = function() {
    var a, b = {},
        f = function(b, d, e) {
            a.setDimensions(d, e);
            a.duplicateAllLayersFrom(b);
            a.recomposite()
        },
        j = function(b, d) {
            a.resizeAllLayers(b, d);
            a.recomposite()
        };
    b.activate = function(b) {
        a = b
    };
    b.resize = function(b, d, e) {
        var h = a.duplicateAllLayers();
        e && a.flattenAllLayers();
        AV.controlsWidgetInstance ? (e = AV.controlsWidgetInstance.getScaledDims(b, d), b = b + 0.5 | 0, d = d + 0.5 | 0) : (b = b + 0.5 | 0, d = d + 0.5 | 0, e = {
            width: b,
            height: d
        });
        a.actions.push([f, this, [h, a.canvas.width, a.canvas.height]], [j, this, [e.width, e.height]], [{
            action: "resize",
            size: [b, d]
        }, {
            action: "setfeathereditsize",
            width: e.width,
            height: e.height
        }], {
            width: b,
            height: d
        });
        a.actions.redo()
    };
    b.readAction = function(a, d) {
        a && (a.size && a.size.length) && b.resize(a.size[0], a.size[1], a.flatten);
        d && d.call(this)
    };
    return b
}();
AV.PaintWidget.prototype.module.crop = function() {
    var a, b, f, j = 1,
        l = !1,
        d = -1,
        e = null,
        h = null,
        t, m, o = {},
        g = function() {
            f = new AV.Bbox(0, 0, a.width, a.height)
        },
        n = function() {
            j = b ? Math.abs((b.x1 - b.x0) / (b.y1 - b.y0)) : 0
        },
        p = function(a, d) {
            var e;
            if (0 != j) {
                var g = 1 / j;
                l && (Math.abs((b.x1 - b.x0) / (b.y1 - b.y0)) > j ? (e = AV.math.sign(b.y1 - b.y0) * Math.abs(b.x1 - b.x0) * g + 0.5 | 0, b[d] = "y1" === d ? b.y0 + e : b.y1 - e) : (e = AV.math.sign(b.x1 - b.x0) * Math.abs(b.y1 - b.y0) * j + 0.5 | 0, b[a] = "x1" === a ? b.x0 + e : b.x1 - e));
                e = f.constrain(b[a], b[d]);
                b[a] = e.x;
                b[d] = e.y;
                l && e.dirty && (Math.abs((b.x1 - b.x0) / (b.y1 - b.y0)) > j ? (e = AV.math.sign(b.x1 - b.x0) * Math.abs(b.y1 - b.y0) * j + 0.5 | 0, b[a] = "x1" === a ? b.x0 + e : b.x1 - e) : (e = AV.math.sign(b.y1 - b.y0) * Math.abs(b.x1 - b.x0) * g + 0.5 | 0, b[d] = "y1" === d ? b.y0 + e : b.y1 - e))
            }
        },
        k = function(b, d, e) {
            a.setDimensions(d, e);
            a.duplicateAllLayersFrom(b);
            a.recomposite();
            g()
        },
        q = function(b, d, e, f) {
            a.cropAllLayers(b, d, e, f);
            a.recomposite();
            n();
            g()
        },
        s = function() {
            var d, e, f, g, h, j, p = 1;
            AV.controlsWidgetInstance && AV.controlsWidgetInstance.canvasUI && (p = AV.controlsWidgetInstance.canvasUI.viewport.getRatio());
            var n = a.getLayerByName("_ui");
            b.x0 < b.x1 ? (d = b.x0, f = b.x1) : (d = b.x1, f = b.x0);
            b.y0 < b.y1 ? (e = b.y0, g = b.y1) : (e = b.y1, g = b.y0);
            h = f - d;
            j = g - e;
            var k = n.canvas.getContext("2d");
            k.globalCompositeOperation = "copy";
            k.clearRect(0, 0, n.canvas.width, n.canvas.height);
            k.globalCompositeOperation = "source-over";
            k.fillStyle = "#000000";
            k.globalAlpha = 0.6;
            k.fillRect(0, 0, d, n.canvas.height);
            k.fillRect(f, 0, n.canvas.width, n.canvas.height);
            k.fillRect(d, 0, f - d, e - 0);
            k.fillRect(d, g, f - d, n.canvas.height - g);
            k.strokeStyle = "#f4f4f4";
            k.globalAlpha =
                1;
            k.lineWidth = p;
            k.lineCap = "square";
            k.strokeRect(d, e, h, j);
            k.beginPath();
            k.moveTo(d, e + 0.33 * j + 0.5 | 0);
            k.lineTo(f, e + 0.33 * j + 0.5 | 0);
            k.moveTo(d, e + 0.67 * j + 0.5 | 0);
            k.lineTo(f, e + 0.67 * j + 0.5 | 0);
            k.moveTo(d + 0.33 * h + 0.5 | 0, e);
            k.lineTo(d + 0.33 * h + 0.5 | 0, g);
            k.moveTo(d + 0.67 * h + 0.5 | 0, e);
            k.lineTo(d + 0.67 * h + 0.5 | 0, g);
            k.stroke();
            k.closePath();
            k.globalAlpha = 1;
            d = [{
                x: d,
                y: e
            }, {
                x: f,
                y: e
            }, {
                x: d,
                y: g
            }, {
                x: f,
                y: g
            }];
            for (e = 0; e < d.length; e++) f = d[e], k.setTransform(1, 0, 0, 1, 0, 0), n.localToWorld(f), k.translate(f.x, f.y), k.translate(-1 * (p * a.handleImg.width / 2 + 0.5 | 0), -1 * (p * a.handleImg.height / 2 + 0.5 | 0)), k.scale(p, p), k.drawImage(a.handleImg, 0, 0), k.scale(1, 1), k.setTransform(1, 0, 0, 1, 0, 0)
        };
    o.activate = function(d) {
        a = d;
        b = new AV.Bbox;
        g();
        l = !1;
        a.uiLayerReset();
        a.uiLayerShow(!0)
    };
    o.deactivate = function() {
        a.uiLayerShow(!1);
        b = null
    };
    o.userPostUndo = function() {
        o.setInitialSelection()
    };
    o.userPostRedo = function() {
        o.setInitialSelection()
    };
    o.forceAspect = function(a) {
        a && n();
        l = a
    };
    o.setInitialSelectionTo = function(d, e) {
        b.x0 = (a.canvas.width - d) / 2 + 0.5 | 0;
        b.x1 = b.x0 + d + 0.5 | 0;
        b.y0 = (a.canvas.height - e) / 2 + 0.5 | 0;
        b.y1 = b.y0 + e + 0.5 | 0;
        a.uiLayerReset();
        a.uiLayerShow(!0);
        s();
        a.recomposite()
    };
    o.setInitialSelectionByRatio = function(b) {
        var d = a.handleImg.width,
            e = a.canvas.width - 2 * d,
            f = a.canvas.height - 2 * d,
            d = f * b;
        d > e && (d = e, f = e / b);
        return o.setInitialSelectionTo(d, f)
    };
    o.setInitialSelection = function() {
        var d, e;
        l ? d = e = 0.12 : (d = 0.09, e = 0.18);
        b.x0 = a.canvas.width * d + 0.5 | 0;
        b.x1 = a.canvas.width * (1 - d) + 0.5 | 0;
        b.y0 = a.canvas.height * e + 0.5 | 0;
        b.y1 = a.canvas.height * (1 - e) + 0.5 | 0;
        a.uiLayerReset();
        a.uiLayerShow(!0);
        s();
        a.recomposite()
    };
    o.hideSelection =

        function() {
            a.uiLayerShow(!1)
        };
    o.setMouseDownCallback = function(a) {
        e = a
    };
    o.crop = function() {
        a.uiLayerShow(!1);
        var d, e, f, g;
        b.x0 < b.x1 ? (d = b.x0, f = b.x1) : (d = b.x1, f = b.x0);
        b.y0 < b.y1 ? (e = b.y0, g = b.y1) : (e = b.y1, g = b.y0);
        var h = a.getLayerByName("_ui");
        0 > d && (d = 0);
        0 > e && (e = 0);
        f > h.canvas.width && (f = h.canvas.width);
        g > h.canvas.height && (g = h.canvas.height);
        o.cropRect(d, e, f - d, g - e);
        o.setInitialSelection()
    };
    o.cropRect = function(b, d, e, f) {
        var g = a.duplicateAllLayers();
        a.actions.push([k, this, [g, a.canvas.width, a.canvas.height]], [q, this, [b, d, e, f]], [{
            action: "crop",
            upperleftpoint: [b, d],
            size: [e, f]
        }, {
            action: "setfeathereditsize",
            width: e,
            height: f
        }], a.c2a(e, f, !0));
        a.actions.redo()
    };
    o.updateUIDown = function(f, g) {
        var k = 99999999,
            j;
        j = 1;
        AV.controlsWidgetInstance && AV.controlsWidgetInstance.canvasUI && (j = AV.controlsWidgetInstance.canvasUI.viewport.getRatio());
        var p = a.handleImg.width,
            p = j * (p / 2) * (p / 2) | 0;
        h = null;
        a.uiLayerReset();
        a.uiLayerShow(!0);
        d = -1;
        j = AV.math.sqrDist(b.x0, b.y0, f, g);
        j < k && j < p && (d = 0, k = j);
        j = AV.math.sqrDist(b.x1, b.y0, f, g);
        j < k && j < p && (d =
            1, k = j);
        j = AV.math.sqrDist(b.x0, b.y1, f, g);
        j < k && j < p && (d = 2, k = j);
        j = AV.math.sqrDist(b.x1, b.y1, f, g);
        j < k && j < p && (d = 3);
        e && e(f, g, -1 < d);
        switch (d) {
            case 0:
                t = f - b.x0;
                m = g - b.y0;
                break;
            case 3:
                t = b.x1 - f;
                m = b.y1 - g;
                break;
            case 1:
                t = b.x1 - f;
                m = g - b.y0;
                break;
            case 2:
                t = f - b.x0;
                m = b.y1 - g;
                break;
            default:
                if (b.contains(f, g)) h = {
                    x: f,
                    y: g
                };
                else
                    return !1
        }
        s();
        a.recomposite();
        return !0
    };
    o.updateUIMove = function(e, g) {
        var k, j;
        if (null != h) k = e - h.x, j = g - h.y, b.x0 += k, b.x1 += k, b.y0 += j, b.y1 += j, b.w = b.x1 - b.x0, b.h = b.y1 - b.y0, k = f.constrain(b.x0, b.y0), k.dirty && (b.x0 =
            k.x, b.y0 = k.y, b.x1 = b.x0 + b.w, b.y1 = b.y0 + b.h), k = f.constrain(b.x1, b.y1), k.dirty && (b.x1 = k.x, b.y1 = k.y, b.x0 = b.x1 - b.w, b.y0 = b.y1 - b.h), h.x = e, h.y = g;
        else
            switch (d) {
                case 1:
                    b.x1 = e + t;
                    b.y0 = g - m;
                    p("x1", "y0");
                    break;
                case 2:
                    b.x0 = e - t;
                    b.y1 = g + m;
                    p("x0", "y1");
                    break;
                case 3:
                    b.x1 = e + t;
                    b.y1 = g + m;
                    p("x1", "y1");
                    break;
                default:
                    b.x0 = e - t, b.y0 = g - m, p("x0", "y0")
            }
        s();
        a.recomposite(0)
    };
    o.apply = function() {
        var a;
        b.x0 > b.x1 && (a = b.x0, b.x0 = b.x1, b.x1 = a);
        b.y0 > b.y1 && (a = b.y0, b.y0 = b.y1, b.y1 = a)
    };
    o.readAction = function(a, b) {
        a && (a.size && a.upperleftpoint) && o.cropRect(a.upperleftpoint[0], a.upperleftpoint[1], a.size[0], a.size[1]);
        b && b.call(this)
    };
    return o
}();
AV.PaintWidget.prototype.module.overlay = function() {
    var a, b = null,
        f = null,
        j = null,
        l = null,
        d = 0,
        e = 0,
        h = 0,
        t = 1,
        m = 1,
        o = 0,
        g = 0,
        n = 0,
        p = 0,
        k = {},
        q = function(d) {
            d = a.findLayerIndexByName(d); - 1 != d && (a.layers[d] === b && (b = null, a.uiLayerShow(!1)), a.layerDelete(d));
            a.recomposite()
        },
        s = function(b, d, e, f, g, h, k) {
            b = new AV.Layer({
                name: b,
                image: d
            });
            b.tag = "module_overlay";
            b.centerX = b.drawable.width / 2 + 0.5 | 0;
            b.centerY = b.drawable.height / 2 + 0.5 | 0;
            null != k ? (b.translateX = e, b.translateY = f, b.rotate = k, b.scaleX = g, b.scaleY = h) : (b.translateX = a.width / 2 + 0.5 | 0, b.translateY = a.height / 2 + 0.5 | 0);
            a.layers.push(b);
            a.recomposite()
        },
        r = function(b, d, e) {
            a.moveLayerByName(b, d, e);
            a.recomposite()
        },
        x = function(b, d, e, f) {
            b = a.getLayerByName(b);
            null != b && (b.rotate = d, b.scaleX = e, b.scaleY = f);
            a.recomposite()
        };
    k.activate = function(d) {
        a = d;
        b = f = null;
        j = {
            x: 0,
            y: 0
        }
    };
    k.deactivate = function() {
        b = f = null;
        a.uiLayerShow(!1)
    };
    k.userUndo = function() {
        b = null;
        a.uiLayerShow(!1)
    };
    k.userRedo = function() {
        b = null;
        a.uiLayerShow(!1)
    };
    k.newOverlay = function(d, e, g, h, k, j) {
        var p = a.overlayRegistry.getElement(d),
            n = a.overlayRegistry.getHiRes(d),
            m = "_module_overlay-" + Math.floor(4294967295 * Math.random()).toString(16);
        h || (h = k = 1);
        j || (j = 0);
        e || (e = a.width / 2 + 0.5 | 0, g = a.height / 2 + 0.5 | 0);
        var l = a.width < a.height ? a.width : a.height,
            l = l - 2 * a.handleImg.width,
            o = p.width > p.height ? p.width : p.height;
        h * o > l && (h = k = l / o);
        a.actions.push([q, this, [m]], [s, this, [m, p, e, g, h, k, j]], {
            action: "addsticker",
            url: n || d,
            id: m,
            center: [e, g],
            scale: [h, k],
            rotation: j,
            external: n ? 1 : 0,
            size: [p.width, p.height],
            urls: [d, n]
        });
        a.actions.redo();
        a.uiLayerReset();
        a.uiLayerShow(!0);
        f = b = a.getLayerByName(m);
        a.uiLayerDrawRectSelection(f, 1);
        a.recomposite();
        return m
    };
    k.deleteSelectedOverlay = function() {
        if (null != b) {
            var d = b.name;
            a.actions.push([s, this, [d, b.image, b.translateX, b.translateY, b.scaleX, b.scaleY, b.rotate]], [q, this, [d]], {
                action: "deletesticker",
                id: d
            });
            a.actions.redo()
        }
    };
    k.updateUIDown = function(q, r) {
        j = {
            x: 0,
            y: 0
        };
        null != b && (j.handleWidth = a.handleImg.width);
        var s = a.getLayerByMouseClickWithTag(q, r, "tag", "module_overlay", j);
        if (null == s) return a.uiLayerShow(!1), b = null, !1;
        var y = a.findLayerIndexByName(s.name);
        a.layerToTop(y);
        l = 1 == j.cornerHit || 2 == j.cornerHit ? "rotate" : "translate";
        a.uiLayerReset();
        a.uiLayerShow(!0);
        switch (l) {
            case "rotate":
                d = s.rotate;
                switch (j.cornerHit) {
                    case 1:
                        return b = s, k.deleteSelectedOverlay(), !1;
                    case 2:
                        q += j.dx, r += j.dy, e = Math.atan2(s.translateX - q, s.translateY - r)
                }
                h = s.rotate;
                t = s.scaleX;
                m = s.scaleY;
                break;
            case "translate":
                o = s.translateX, g = s.translateY, n = s.translateX - q, p = s.translateY - r
        }
        f = b = s;
        return l
    };
    k.updateUIMove = function(b, d) {
        var g;
        switch (l) {
            case "rotate":
                b += j.dx;
                d += j.dy;
                switch (j.cornerHit) {
                    case 1:
                        return !1;
                    case 2:
                        g = Math.atan2(f.translateX - b, f.translateY - d), g -= e
                }
                f.rotate = h - g;
                var k = b - f.translateX,
                    m = d - f.translateY;
                g = Math.sqrt(k * k + m * m);
                k = null != f.centerX ? f.drawable.width - f.centerX : f.drawable.width;
                m = null != f.centerY ? f.drawable.height - f.centerY : f.drawable.height;
                k = Math.sqrt(k * k + m * m);
                g /= k;
                0.1 > g && (g = 0.1);
                f.scaleX = g;
                f.scaleY = g;
                break;
            case "translate":
                a.moveLayerByName(f.name, b + n, d + p)
        }
        a.uiLayerDrawRectSelection(f, 1);
        a.recomposite()
    };
    k.apply = function() {
        switch (l) {
            case "rotate":
                a.actions.push([x, this, [f.name, d, t, m]], [x, this, [f.name, f.rotate, f.scaleX, f.scaleY]], {
                    action: "setsticker",
                    id: f.name,
                    center: [f.translateX, f.translateY],
                    scale: [f.scaleX, f.scaleY],
                    rotation: f.rotate
                });
                break;
            case "translate":
                a.actions.push([r, this, [f.name, o, g]], [r, this, [f.name, f.translateX, f.translateY]], {
                    action: "setsticker",
                    id: f.name,
                    center: [f.translateX, f.translateY],
                    scale: [f.scaleX, f.scaleY],
                    rotation: f.rotate
                })
        }
        a.actions.redoFake();
        f = null
    };
    k.readAction = function(b, d) {
        var e, f, g, h, j, p;
        b && b.url ? (b.center && b.center.length && (g = b.center[0], h = b.center[1]), b.scale && b.scale.length && (j = b.scale[0], p = b.scale[1]), b.urls && b.urls.length ? (e = b.urls[0], f = b.urls[1]) : f = e = b.url, a.overlayRegistry.add(e, f), a.overlayRegistry.getElement(e, function() {
            k.newOverlay(e, g, h, j, p, b.rotation);
            d && d.call(this)
        })) : d && d.call(this)
    };
    return k
}();
AV.PaintWidget.prototype.module.effects = function() {
    var a, b, f, j, l = {},
        d = function(d, e, g, h) {
            var j = a.getLayerByName(d);
            if (null != j.canvas) {
                b || (h && (f = a.duplicateAllLayers(), a.flattenAllLayers(), j = a.getLayerByName(d)), b = AV.cnvs.copyCanvas(j.canvas));
                var d = j.canvas,
                    h = d.getContext("2d"),
                    j = AV.cnvs.getCanvasPixelData(b),
                    k = h.createImageData(d.width, d.height);
                a.filterManager.getEffectById(e).apply(this, [k.data, j.data, d.width, d.height, g]);
                h.putImageData(k, 0, 0);
                a.recomposite()
            }
        },
        e = function(d) {
            var e;
            if (d) {
                e = {
                    action: "effects",
                    name: d.filterName,
                    flatten: d.flatten
                };
                AV.util.extend(e, d.filterParams);
                var g = a.layers[a.currentLayerIndex];
                a.actions.push([h, this, [g.name, b, f]], [t, this, [g.name, d.filterName, d.filterParams, d.flatten]], e);
                a.actions.redoFake()
            }
        },
        h = function(b, d, e) {
            e ? a.duplicateAllLayersFrom(e) : (b = a.getLayerByName(b).canvas.getContext("2d"), b.globalCompositeOperation = "copy", b.drawImage(d, 0, 0), b.globalCompositeOperation = "source-over");
            a.recomposite()
        },
        t = function(a, e, g, h) {
            d(a, e, g, h);
            f = b = null;
            j = !0
        };
    l.activate = function(b) {
        a =
            b;
        j = !1
    };
    l.deactivate = function() {
        a.busy && (a.actions.undoFake(), a.busy = !1);
        f = b = null
    };
    l.makeThumb = function(b, d) {
        a.makeThumbFlat(d);
        var e = d.width,
            f = d.height,
            h = d.getContext("2d"),
            k = h.getImageData(0, 0, e, f),
            j = h.createImageData(e, f),
            l = a.filterManager.getEffectById(b);
        l ? l.apply(this, [j.data, k.data, e, f]) : j = k;
        h.putImageData(j, 0, 0)
    };
    l.applyPreview = function(b, f, g) {
        j ? (a.actions.undo(), j = !1) : a.actions.undoFake();
        a.dirty = !0;
        var h = a.layers[a.currentLayerIndex];
        null != h.canvas && (d(h.name, b, f, g), e({
            filterName: b,
            filterParams: f,
            flatten: g
        }))
    };
    l.readAction = function(a, b) {
        a && a.name && l.applyPreview(a.name, a.params, a.flatten);
        b && b.call(this)
    };
    return l
}();
AV.PaintWidget.prototype.module.flatten = function() {
    var a, b = {},
        f = function(b) {
            a.duplicateAllLayersFrom(b);
            a.recomposite()
        },
        j = function() {
            a.flattenAllLayers();
            a.recomposite()
        };
    b.activate = function(b) {
        a = b
    };
    b.flatten = function() {
        var b = a.duplicateAllLayers();
        a.actions.push([f, this, [b]], [j, this, []], {
            action: "flatten"
        }, null, {
            implicit: !0
        });
        a.actions.redo()
    };
    return b
}();
AV.PaintWidget.prototype.module.text = function() {
    var a, b, f, j, l, d, e, h, t, m, o, g, n, p, k = {},
        q = function(b, d) {
            var e = b.split("\n"),
                f = a.canvas.getContext("2d"),
                g = f.font;
            f.font = d;
            var h = 0,
                k, j;
            for (j = 0; j < e.length; j++) k = f.measureText(e[j]), k = k.width, k > h && (h = k);
            f.font = g;
            return h
        },
        s = function(b, d, e) {
            a.moveLayerByName(b, d, e);
            a.recomposite()
        },
        r = function(b, d, e, f) {
            b = a.getLayerByName(b);
            null != b && (b.rotate = d, b.scaleX = e, b.scaleY = f);
            a.recomposite()
        },
        x = function(b) {
            b = a.findLayerIndexByName(b);
            0 <= b && (a.layerDelete(b), a.recomposite())
        },
        u = function(b, d, e, f, g, h, k, j, p, n, l) {
            var m = "" + f + "px " + e,
                o = q(d, m),
                r, s = d.split("\n");
            r = f * s.length + f / 2;
            s = document.createElement("canvas");
            s.width = o;
            s.height = r;
            b = new AV.Layer({
                canvas: s,
                name: b
            });
            b.tag = "module_text";
            b.centerX = b.drawable.width / 2 + 0.5 | 0;
            b.centerY = b.drawable.height / 2 + 0.5 | 0;
            b.module_data = {
                str: d,
                fontName: e,
                fontSizePx: f,
                fontColor: g,
                shadowColor: h
            };
            b.rotate = null == l ? 0 : l;
            null == p ? (b.scaleX = 1, b.scaleY = 1) : (b.scaleX = p, b.scaleY = n);
            null == k ? (b.translateX = a.width / 2 + 0.5 | 0, b.translateY = a.height / 2 + 0.5 | 0) : (b.translateX =
                k, b.translateY = j);
            a.layers.push(b);
            e = s.getContext("2d");
            e.font = m;
            d = d.split("\n");
            h = f;
            for (m = 0; m < d.length; m++) e.fillStyle = g, e.fillText(d[m], 0, h), h += f;
            a.recomposite()
        };
    k.activate = function(b) {
        n = g = null;
        a = b;
        p = {
            x: 0,
            y: 0
        }
    };
    k.deactivate = function() {
        n = g = null;
        a.uiLayerShow(!1)
    };
    k.userUndo = function() {
        n = null;
        a.uiLayerShow(!1)
    };
    k.userRedo = function() {
        n = null;
        a.uiLayerShow(!1)
    };
    k.newText = function(b, d, e, f, h, k, j, p, l, m) {
        if ("" != b) {
            var o = "_module_text-" + Math.floor(4294967295 * Math.random()).toString(16);
            p || (p = l = 1);
            m || (m =
                0);
            k || (k = a.width / 2 + 0.5 | 0, j = a.height / 2 + 0.5 | 0);
            var r = q(b, "" + e + "px " + d);
            r > a.width && (p = l = (a.width - 2 * a.closeXImg.width) / r);
            a.actions.push([x, this, [o]], [u, this, null != m ? [o, b, d, e, f, h, k, j, p, l, m] : null != j ? [o, b, d, e, f, h, k, j, 1, 1, 0] : [o, b, d, e, f, h, b]], {
                action: "addtext",
                id: o,
                text: b,
                font: d,
                size: e,
                color: AV.util.color_to_rgb(f),
                center: [k, j],
                scale: [p, l],
                rotation: m
            });
            a.actions.redo();
            a.uiLayerReset();
            a.uiLayerShow(!0);
            g = n = a.getLayerByName(o);
            a.uiLayerDrawRectSelection(g, 1);
            a.recomposite()
        }
    };
    k.deleteSelectedText = function() {
        if (null != n) {
            var b = n.name;
            a.actions.push([u, this, [b, n.module_data.str, n.module_data.fontName, n.module_data.fontSizePx, n.module_data.fontColor, n.module_data.shadowColor, n.translateX, n.translateY, n.scaleX, n.scaleY, n.rotate]], [x, this, [b]], {
                action: "deletetext",
                id: b
            });
            a.uiLayerShow(!1);
            a.actions.redo()
        }
    };
    k.updateUIDown = function(q, r) {
        p = {
            x: 0,
            y: 0
        };
        null != n && (p.handleWidth = a.handleImg.width);
        var s = a.getLayerByMouseClickWithTag(q, r, "tag", "module_text", p);
        if (null == s) return a.uiLayerShow(!1), n = null, !1;
        var u = a.findLayerIndexByName(s.name);
        a.layerToTop(u);
        b = 1 == p.cornerHit || 2 == p.cornerHit ? "rotate" : "translate";
        a.uiLayerReset();
        a.uiLayerShow(!0);
        switch (b) {
            case "rotate":
                o = s.rotate;
                switch (p.cornerHit) {
                    case 1:
                        return n = s, k.deleteSelectedText(), !1;
                    case 2:
                        q += p.dx, r += p.dy, m = Math.atan2(s.translateX - q, s.translateY - r)
                }
                f = s.rotate;
                j = s.scaleX;
                l = s.scaleY;
                break;
            case "translate":
                d = s.translateX, e = s.translateY, h = s.translateX - q, t = s.translateY - r
        }
        g = n = s;
        a.uiLayerDrawRectSelection(g, 1);
        a.recomposite();
        return b
    };
    k.updateUIMove = function(d, e) {
        var k;
        switch (b) {
            case "rotate":
                d += p.dx;
                e += p.dy;
                switch (p.cornerHit) {
                    case 1:
                        return !1;
                    case 2:
                        k = Math.atan2(g.translateX - d, g.translateY - e), k -= m
                }
                g.rotate = f - k;
                var j = d - g.translateX,
                    n = e - g.translateY;
                k = Math.sqrt(j * j + n * n);
                j = null != g.centerX ? g.drawable.width - g.centerX : g.drawable.width;
                n = null != g.centerY ? g.drawable.height - g.centerY : g.drawable.height;
                j = Math.sqrt(j * j + n * n);
                k /= j;
                0.1 > k && (k = 0.1);
                g.scaleX = k;
                g.scaleY = k;
                break;
            case "translate":
                a.moveLayerByName(g.name, d + h, e + t)
        }
        a.uiLayerDrawRectSelection(g, 1);
        a.recomposite()
    };
    k.apply = function() {
        switch (b) {
            case "rotate":
                a.actions.push([r, this, [g.name, o, j, l]], [r, this, [g.name, g.rotate, g.scaleX, g.scaleY]], {
                    action: "settext",
                    id: g.name,
                    center: [g.translateX, g.translateY],
                    scale: [g.scaleX, g.scaleY],
                    rotation: g.rotate
                });
                break;
            case "translate":
                a.actions.push([s, this, [g.name, d, e]], [s, this, [g.name, g.translateX, g.translateY]], {
                    action: "settext",
                    id: g.name,
                    center: [g.translateX, g.translateY],
                    scale: [g.scaleX, g.scaleY],
                    rotation: g.rotate
                })
        }
        a.actions.redoFake();
        g = null
    };
    k.readAction = function(a, b) {
        var d, e, f, g;
        a && a.text && (a.center && a.center.length && (d = a.center[0], e = a.center[1]), a.scale && a.scale.length && (f = a.scale[0], g = a.scale[1]), k.newText(a.text, a.font, a.size, a.color, null, d, e, f, g, a.rotation));
        b && b.call(this)
    };
    return k
}();
AV.PaintWidget.prototype.module.blur = function() {
    var a, b = 0,
        f, j, l, d, e, h = {},
        t = function(g, h, p) {
            var k = a.getLayerByName(g);
            if (null != k.canvas) {
                var m = k.canvas.getContext("2d");
                null == f && (p && (l = a.duplicateAllLayers(), a.flattenAllLayers(), k = a.getLayerByName(g), m = k.canvas.getContext("2d")), j = AV.cnvs.getCanvasPixelData(k.canvas), f = AV.cnvs.copyCanvas(k.canvas), e = m.createImageData(k.canvas.width, k.canvas.height));
                b = h;
                d = p;
                a.showWaitThrobber(!0, function() {
                    var b = e.data,
                        d = j.data,
                        f = k.canvas.width,
                        g = k.canvas.height,
                        p =
                            h;
                    a.filterManager.getEffectById("sharpness")(b, d, f, g, p * -1);
                    m.putImageData(e, 0, 0);
                    a.recomposite();
                    a.showWaitThrobber(false)
                })
            }
        },
        m = function(b, d, e) {
            e ? a.duplicateAllLayersFrom(e) : (b = a.getLayerByName(b).canvas.getContext("2d"), b.globalCompositeOperation = "copy", b.drawImage(d, 0, 0), b.globalCompositeOperation = "source-over");
            a.recomposite()
        },
        o = function(a, b, d) {
            t(a, b, d);
            l = f = null
        };
    h.activate = function(b) {
        a = b;
        l = e = f = null;
        d = !1
    };
    h.deactivate = function() {
        h.reset()
    };
    h.makeThumb = function(b, d) {
        a.makeThumbFlat(b);
        var e =
                b.width,
            f = b.height,
            h = b.getContext("2d"),
            j = h.getImageData(0, 0, e, f),
            l = h.createImageData(e, f),
            m = l.data,
            j = j.data;
        a.filterManager.getEffectById("sharpness")(m, j, e, f, -1 * d);
        h.putImageData(l, 0, 0)
    };
    h.set = function(b, d) {
        a.dirty = !0;
        t(a.layers[a.currentLayerIndex].name, b, d)
    };
    h.reset = function() {
        if (l) a.duplicateAllLayersFrom(l), a.recomposite();
        else if (f) {
            var b = a.layers[a.currentLayerIndex].canvas.getContext("2d");
            b.globalCompositeOperation = "copy";
            b.drawImage(f, 0, 0);
            b.globalCompositeOperation = "source-over";
            a.recomposite()
        }
        l =
            e = j = f = null
    };
    h.commit = function() {
        if (null != f) {
            var g = a.layers[a.currentLayerIndex];
            a.actions.push([m, this, [g.name, f, l]], [o, this, [g.name, b, d]], {
                action: "sharpness",
                value: -b,
                flatten: d
            });
            a.actions.redoFake();
            l = e = j = f = null
        }
    };
    return h
}();
AV.PaintWidget.prototype.module.barrel = function() {
    var a, b, f, j, l, d, e = 0,
        h = {},
        t = function(a, b, d, e, f) {
            if (0 == f) for (d = 0; d < b.length; d++) a[d] = b[d];
            else {
                var g = (d - 1) / 2,
                    h = (e - 1) / 2,
                    j = Math.sqrt(g * g + h * h),
                    l;
                0 < f ? (f = 8 * Math.pow(f / 100, 2), l = j) : (f = 0.34 * -Math.sqrt(-f / 100), l = Math.min(h, g));
                l = 0.999999 * (AV.math.lowestPositiveQuadratic(f / j, 1, -l) / l);
                var m, o;
                for (o = 0; o < e; o++) {
                    var t = l * (o - h);
                    for (m = 0; m < d; m++) {
                        var z = l * (m - g),
                            D = 1 + f * Math.sqrt(z * z + t * t) / j,
                            z = z * D + g,
                            D = t * D + h,
                            A = z | 0,
                            G = D | 0,
                            z = z - A,
                            D = D - G,
                            J = 1 - z,
                            K = 1 - D,
                            A = 4 * (A + G * d),
                            G = b[A] * J + b[A + 4] * z,
                            N = b[A + 1] * J + b[A + 5] * z,
                            O = b[A + 2] * J + b[A + 6] * z,
                            L = b[A + 3] * J + b[A + 7] * z,
                            A = A + 4 * d,
                            I = b[A + 1] * J + b[A + 5] * z,
                            S = b[A + 2] * J + b[A + 6] * z,
                            U = b[A + 3] * J + b[A + 7] * z,
                            H = 4 * (m + o * d);
                        a[H] = G * K + (b[A] * J + b[A + 4] * z) * D;
                        a[H + 1] = N * K + I * D;
                        a[H + 2] = O * K + S * D;
                        a[H + 3] = L * K + U * D
                    }
                }
            }
        },
        m = function(g, h, k) {
            var m = a.getLayerByName(g);
            if (null != m.canvas) {
                var o;
                o = m.canvas.getContext("2d");
                null == b && (k && (j = a.duplicateAllLayers(), a.flattenAllLayers(), m = a.getLayerByName(g), o = m.canvas.getContext("2d")), f = AV.cnvs.getCanvasPixelData(m.canvas), b = AV.cnvs.copyCanvas(m.canvas), d = o.createImageData(m.canvas.width, m.canvas.height));
                e = h;
                l = k;
                a.showWaitThrobber(!0, function() {
                    t(d.data, f.data, f.width, f.height, h);
                    o.putImageData(d, 0, 0);
                    a.recomposite();
                    a.showWaitThrobber(false)
                })
            }
        };
    h.activate = function(f) {
        a = f;
        j = d = b = null;
        e = 0;
        l = !1
    };
    var o = function(b, d, e) {
            e ? a.duplicateAllLayersFrom(e) : (b = a.getLayerByName(b).canvas.getContext("2d"), b.globalCompositeOperation = "copy", b.drawImage(d, 0, 0), b.globalCompositeOperation = "source-over");
            a.recomposite()
        },
        g = function(a, d, e) {
            m(a, d, e);
            j = b = null
        };
    h.deactivate = function() {
        h.reset()
    };
    h.set = function(b, d) {
        a.dirty = !0;
        m(a.layers[a.currentLayerIndex].name, b, d)
    };
    h.makeThumb = function(b, d) {
        a.makeThumbFlat(b);
        var e = b.getContext("2d"),
            f = e.getImageData(0, 0, b.width, b.height),
            g = e.createImageData(b.width, b.height);
        t(g.data, f.data, f.width, f.height, d);
        e.putImageData(g, 0, 0)
    };
    h.reset = function() {
        if (j) a.duplicateAllLayersFrom(j), a.recomposite();
        else if (b) {
            var e = a.layers[a.currentLayerIndex].canvas.getContext("2d");
            e.globalCompositeOperation = "copy";
            e.drawImage(b, 0, 0);
            e.globalCompositeOperation = "source-over";
            a.recomposite()
        }
        j =
            d = f = b = null
    };
    h.commit = function() {
        var h = a.layers[a.currentLayerIndex];
        a.actions.push([o, this, [h.name, b, j]], [g, this, [h.name, e, l]], {
            action: "barrel",
            value: e,
            flatten: l
        });
        a.actions.redoFake();
        j = d = f = b = null
    };
    return h
}();
AV.PaintWidget.prototype.module.sharpen = function() {
    var a, b, f, j, l, d, e = 0,
        h = {},
        t = function(b, d, e, f, g) {
            a.filterManager.getEffectById("sharpness").apply(this, [b, d, e, f, g])
        },
        m = function(g, h, m) {
            var n = a.getLayerByName(g);
            if (null != n.canvas) {
                var o;
                o = n.canvas.getContext("2d");
                null == b && (m && (j = a.duplicateAllLayers(), a.flattenAllLayers(), n = a.getLayerByName(g), o = n.canvas.getContext("2d")), f = AV.cnvs.getCanvasPixelData(n.canvas), b = AV.cnvs.copyCanvas(n.canvas), d = o.createImageData(n.canvas.width, n.canvas.height));
                e = h;
                l = m;
                t(d.data, f.data, n.canvas.width, n.canvas.height, h);
                o.putImageData(d, 0, 0);
                a.recomposite()
            }
        },
        o = function() {
            var d = a.layers[a.currentLayerIndex];
            a.actions.push([g, this, [d.name, b, j]], [n, this, [d.name, e, l]], {
                action: "sharpness",
                value: e,
                flatten: l
            });
            a.actions.redoFake()
        },
        g = function(b, d, e) {
            e ? a.duplicateAllLayersFrom(e) : (b = a.getLayerByName(b).canvas.getContext("2d"), b.globalCompositeOperation = "copy", b.drawImage(d, 0, 0), b.globalCompositeOperation = "source-over");
            a.recomposite()
        },
        n = function(a, d, e) {
            m(a, d, e);
            j = b =
                null
        };
    h.activate = function(f) {
        a = f;
        j = d = b = null;
        e = 0;
        l = !1
    };
    h.deactivate = function() {
        j = d = f = b = null
    };
    h.set = function(b, d) {
        a.dirty = !0;
        var e = a.layers[a.currentLayerIndex];
        a.showWaitThrobber(!0, function() {
            m(e.name, b, d);
            a.showWaitThrobber(false)
        })
    };
    h.applyPreview = function(b, d) {
        var e = a.layers[a.currentLayerIndex];
        a.showWaitThrobber(!0, function() {
            a.actions.undo();
            m(e.name, b, d);
            a.showWaitThrobber(false);
            o()
        })
    };
    h.makeThumb = function(b, d) {
        a.makeThumbFlat(b);
        var e = b.getContext("2d"),
            f = e.getImageData(0, 0, b.width, b.height),
            g = e.createImageData(b.width, b.height);
        t(g.data, f.data, f.width, f.height, d);
        e.putImageData(g, 0, 0)
    };
    h.reset = function() {
        if (j) a.duplicateAllLayersFrom(j), a.recomposite();
        else if (b) {
            var e = a.layers[a.currentLayerIndex].canvas.getContext("2d");
            e.globalCompositeOperation = "copy";
            e.drawImage(b, 0, 0);
            e.globalCompositeOperation = "source-over";
            a.recomposite()
        }
        j = d = f = b = null
    };
    h.commit = function() {
        o();
        j = d = f = b = null
    };
    h.readAction = function(b, d) {
        b && void 0 !== b.value && (m(a.layers[a.currentLayerIndex].name, b.value, b.flatten), a.showWaitThrobber(!1), o());
        d && d.call(this)
    };
    return h
}();
AV.PaintWidget.prototype.module.warmth = function() {
    var a, b, f, j, l, d, e = 0,
        h, t = {},
        m = function(g, h, m) {
            var n = a.getLayerByName(g);
            if (null != n.canvas) {
                var o;
                o = n.canvas.getContext("2d");
                null == b && (m && (j = a.duplicateAllLayers(), a.flattenAllLayers(), n = a.getLayerByName(g), o = n.canvas.getContext("2d")), f = AV.cnvs.getCanvasPixelData(n.canvas), b = AV.cnvs.copyCanvas(n.canvas), d = o.createImageData(n.canvas.width, n.canvas.height));
                e = h;
                l = m;
                a.filterManager.getEffectById("colortemp").apply(this, [d.data, f.data, e]);
                o.putImageData(d, 0, 0);
                a.recomposite()
            }
        },
        o = function(b, d, e) {
            e ? a.duplicateAllLayersFrom(e) : (b = a.getLayerByName(b).canvas.getContext("2d"), b.globalCompositeOperation = "copy", b.drawImage(d, 0, 0), b.globalCompositeOperation = "source-over");
            a.recomposite()
        },
        g = function(a, d, e) {
            m(a, d, e);
            j = b = null;
            h = !0
        },
        n = function() {
            var d = a.layers[a.currentLayerIndex];
            a.actions.push([o, this, [d.name, b, j]], [g, this, [d.name, e, l]], {
                action: "colortemp",
                value: e,
                flatten: l
            });
            a.actions.redoFake()
        };
    t.activate = function(f) {
        a = f;
        j = d = b = null;
        e = 0;
        h = l = !1
    };
    t.deactivate =

        function() {
            t.reset()
        };
    t.set = function(b, d) {
        a.dirty = !0;
        var e = a.layers[a.currentLayerIndex];
        AV.util.nextFrame(function() {
            m(e.name, b, d)
        })
    };
    t.applyPreview = function(b, d) {
        var e = a.layers[a.currentLayerIndex];
        AV.util.nextFrame(function() {
            h ? (a.actions.undo(), h = !1) : a.actions.undoFake();
            m(e.name, b, d);
            n()
        })
    };
    t.reset = function() {
        j = d = f = b = null
    };
    t.readAction = function(b, d) {
        b && void 0 !== b.value && (m(a.layers[a.currentLayerIndex].name, b.value, b.flatten), n());
        d && d.call(this)
    };
    return t
}();
AV.PaintWidget.prototype.module.frames = function() {
    var a, b, f, j, l, d, e = {},
        h = function(a, b) {
            var d = b.color,
                e = b.size,
                d = "string" === typeof d ? AV.util.color_to_array(d) : d;
            3 === d.length && d.push(0.4);
            b.color = d;
            e = (d = {
                original: [1, 1, 1, 1, 1, 1],
                justround: [0.09, 0.18, 0.3, 0.5, 0.75, 1],
                smooth: [0.09, 0.2, 0.45, 0.65, 0.8, 1],
                halftone: [0.32, 0.44, 0.55, 0.7, 0.8, 0.9],
                instant: [0.2, 0.4, 0.5, 0.65, 0.85, 1],
                charcoal: [0, 0.25, 0.45, 0.65, 0.85, 1],
                fade: [0.06, 0.18, 0.3, 0.45, 0.75, 1],
                shadow: [0.25, 0.4, 0.55, 0.7, 0.85, 1],
                round: [0.18, 0.35, 0.5, 0.7, 0.85, 1],
                vignette: [0.3, 0.45, 0.55, 0.7, 0.85, 1],
                rect: [0.15, 0.25, 0.4, 0.6, 0.8, 1],
                torn: [0.13, 0.3, 0.5, 0.7, 0.85, 1],
                torn3: [0.13, 0.3, 0.5, 0.7, 0.85, 1],
                bulge: [0.25, 0.45, 0.6, 0.75, 0.85, 1]
            }[a]) && d.length ? d[e - 1] : e;
            b.size = e
        },
        t = function(d, e, h, k) {
            var m = a.getLayerByName(d);
            if (null != m.canvas) {
                var o;
                o = m.canvas.getContext("2d");
                null == b && (k && (j = a.duplicateAllLayers(), a.flattenAllLayers(), m = a.getLayerByName(d), o = m.canvas.getContext("2d")), f = AV.cnvs.getCanvasPixelData(m.canvas), b = AV.cnvs.copyCanvas(m.canvas), l = o.createImageData(m.canvas.width, m.canvas.height));
                AV.util.nextFrame(function() {
                    a.filterManager.getEffectById(e).apply(this, [l.data, f.data, m.canvas.width, m.canvas.height, h]);
                    o.putImageData(l, 0, 0);
                    a.recomposite()
                })
            }
        },
        m = function(b, d, e) {
            e ? a.duplicateAllLayersFrom(e) : (b = a.getLayerByName(b).canvas.getContext("2d"), b.globalCompositeOperation = "copy", b.drawImage(d, 0, 0), b.globalCompositeOperation = "source-over");
            a.recomposite()
        },
        o = function(a, e, f, h) {
            t(a, e, f, h);
            j = b = null;
            d = !0
        };
    e.activate = function(e) {
        a = e;
        j = l = b = null;
        d = !1
    };
    e.deactivate = function() {
        j =
            l = f = b = null;
        d = !1
    };
    e.applyPreview = function(e, f, l) {
        d ? (a.actions.undo(), d = !1) : a.actions.undoFake();
        var k = a.currentLayerIndex;
        if (k = a.layers[k]) hiResData = {
            action: "borders",
            name: e,
            flatten: l
        }, AV.util.extend(hiResData, f), h(e, f), t(k.name, e, f, l), a.dirty = !0, k = a.currentLayerIndex, k = a.layers[k], a.actions.push([m, this, [k.name, b, j]], [o, this, [k.name, e, f, l]], hiResData), a.actions.redoFake()
    };
    e.makeThumb = function(b, d, e) {
        var f, j, m, l;
        f = b.width;
        j = b.height;
        b = b.getContext("2d");
        m = b.createImageData(f, j);
        l = b.createImageData(f, j);
        e = {
            color: e,
            size: 6
        };
        h(d, e);
        a.filterManager.getEffectById(d).apply(this, [l.data, m.data, f, j, e]);
        b.putImageData(l, 0, 0)
    };
    return e
}();