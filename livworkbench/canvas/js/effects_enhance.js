(AV.filterPacks || (AV.filterPacks = [])).push(function(g, m, n, t, a, e) {
    g.la = function(b) {
        var d = (b[0] / 2.55 + 16) / 116,
            c = (b[1] - 127) / 500 + d,
            b = d - (b[2] - 127) / 200,
            f = d * d * d,
            h = c * c * c,
            a = b * b * b,
            c = 0.95047 * (0.008856 < h ? h : (c - 16 / 116) / 7.787),
            d = 1 * (0.008856 < f ? f : (d - 16 / 116) / 7.787),
            b = 1.08883 * (0.008856 < a ? a : (b - 16 / 116) / 7.787),
            f = 3.2406 * c + -1.5372 * d + -0.4986 * b,
            h = -0.9689 * c + 1.8758 * d + 0.0415 * b,
            d = 0.0557 * c + -0.204 * d + 1.057 * b,
            f = 0.0031308 < f ? 1.055 * Math.pow(f, 0.41666666666667) - 0.055 : 12.92 * f,
            h = 0.0031308 < h ? 1.055 * Math.pow(h, 0.41666666666667) - 0.055 : 12.92 * h,
            d = 0.0031308 < d ? 1.055 * Math.pow(d, 0.41666666666667) - 0.055 : 12.92 * d;
        return [255 * f + 0.5 | 0, 255 * h + 0.5 | 0, 255 * d + 0.5 | 0]
    };
    g.ga = function(b, d, c, f) {
        var h, b = f[b],
            d = f[d],
            c = f[c],
            f = 0.00433891 * b + 0.00376234915 * d + 0.0018990604648 * c;
        h = 0.002126 * b + 0.007152 * d + 7.22E-4 * c;
        b = 1.77255E-4 * b + 0.00109475308 * d + 0.0087295537 * c;
        f = 0.008856 < f ? Math.pow(f, 1 / 3) : 7.787 * f + 16 / 116;
        h = 0.008856 < h ? Math.pow(h, 1 / 3) : 7.787 * h + 16 / 116;
        b = 0.008856 < b ? Math.pow(b, 1 / 3) : 7.787 * b + 16 / 116;
        return [2.55 * (116 * h - 16), 500 * (f - h) + 127, 200 * (h - b) + 127]
    };
    t.Ia = function(b, d, c, f) {
        g.ia(b, d, c, f);
        g.xa(b, b, c, f)
    };
    t.Ja = function(b, d, c, f) {
        g.ia(b, d, c, f);
        g.ca(b, b, 0.5);
        g.xa(b, b, c, f)
    };
    t.Qb = function(b, d, c, f) {
        g.ia(b, d, c, f);
        g.xb(b, b, c, f)
    };
    t.Jb = function(b, d, c, f) {
        for (var h = 0, h = 0, a = Array(256), i = Array(256), k = Array(256), j = [], h = 0; 256 > h; h++) a[h] = 0, i[h] = 0, k[h] = 0, j[h] = 0.04045 >= h / 255 ? 100 * h / 255 / 12.92 : 100 * Math.pow((h / 255 + 0.055) / 1.055, 2.4);
        for (var e, n = 0; n < f; n++) for (var o = 0; o < c; o++) h = 4 * o + 4 * n * c, e = g.ga(d[h], d[h + 1], d[h + 2], j), a[e[0] + 0.5 | 0]++, i[e[1] + 0.5 | 0]++, k[e[2] + 0.5 | 0]++;
        h = Array(3);
        h[0] = a;
        h[1] = i;
        h[2] = k;
        g.w(h[0]);
        n = g.w(h[1]);
        h = g.w(h[2]);
        n = (g.p(n, 0.1) + g.p(n, 0.9)) / 2 - 127;
        o = (g.p(h, 0.1) + g.p(h, 0.9)) / 2 - 127;
        h = 0;
        n = 0 > n ? -Math.pow(-0.5 * n, 1) : Math.pow(0.5 * n, 1);
        h = 0 > o ? -Math.pow(-0.5 * o, 1) : Math.pow(0.5 * o, 1);
        a = 127 / (127 + n);
        i = 127 / (127 + h);
        for (n = 0; n < f; n++) for (o = 0; o < c; o++) {
            h = 4 * o + 4 * n * c;
            e = g.ga(d[h], d[h + 1], d[h + 2], j);
            e[1] *= a;
            e[2] *= i;
            rgb = g.la(e);
            var m, k = rgb[0] + 0.5 | 0;
            e = rgb[1] + 0.5 | 0;
            m = rgb[2] + 0.5 | 0;
            255 < k ? k = 255 : 0 > k && (k = 0);
            255 < e ? e = 255 : 0 > e && (e = 0);
            255 < m ? m = 255 : 0 > m && (m = 0);
            b[h] = k;
            b[h + 1] = e;
            b[h + 2] = m;
            b[h + 3] = d[h + 3]
        }
    };
    e.push(["Auto", "autoenhance", t.Ia]);
    e.push(["Night", "nightenhance", t.Qb]);
    e.push(["Backlit", "backlightenhance", t.Ja]);
    e.push(["Balance", "labcorrect", t.Jb])
});