define(function() {
	var a = function() {
		var b = this;
		this.init = function(e) {
			b.onerrorImgUrl = e.onerrorImgUrl;
			b.srcStore = e.srcStore;
			b.cla = e.cla;
			b.sensitivity = 50;
			b.scrollTarget = e.scrollTarget || $(window);
			minScroll = 5, slowScrollTime = 200, ios = navigator.appVersion.match(/(iPhone\sOS)\s([\d_]+)/), isIos = ios && !0 || !1, isoVersion = isIos && ios[2].split("_");
			isoVersion = isoVersion && parseFloat(isoVersion.length > 1 ? isoVersion.splice(0, 2).join(".") : isoVersion[0], 10), isIos = b.isPhone = isIos && isoVersion < 6;
			if (isIos) {
				var c, d;
				$(window).on("touchstart", function() {
					c = {
						sy : window.scrollY,
						time : Date.now()
					}, d && clearTimeout(d)
				}).on("touchend", function(f) {
					if (f && f.changedTouches) {
						var g = Math.abs(window.scrollY - c.sy);
						if (g > minScroll) {
							var h = Date.now() - c.time;
							d = setTimeout(function() {
								b.changeimg(), c = {}, clearTimeout(d), d = null
							}, h > slowScrollTime ? 0 : 200)
						}
					} else {
						b.changeimg()
					}
				}).on("touchcancel", function() {
					d && clearTimeout(d), c = {}
				})
			} else {
				b.scrollTarget.on("scroll", function() {
					b.changeimg()
				})
			}
			setTimeout(function() {
				b.trigger();
				b.changeimg();
			}, 90)
		};
		b.trigger = function() {
			var c = b.isPhone && "touchend" || "scroll";
			b.imglist = $("img." + b.cla + "");
			if (b.imglist && b.imglist.length > 0) {
				$(window).trigger(c)
			}
		};
		b.changeimg = function() {
			function d(f) {
				var h = window.pageYOffset, e = h + window.innerHeight, g = f.offset().top;
				return g >= h && g - b.sensitivity <= e
			}

			function c(j, k) {
				var n = j.attr(b.srcStore);
				if (!n) {
					return;
				}
				if (j.attr("isCut")) {
					var h = /^(http|https):\/\/(d\d{1,2})/;
					var g = /_\d{1,}x\d{1,}\./;
					if (n.search(g) != -1) {
						var f = j.attr("width"), m = j.attr("height");
						if (h.test(n) && !isNaN(f) && !isNaN(m)) {
							var i = n.split(".");
							if (i.length < 2) {
								return
							}
							var l = i.pop();
							var e = "_" + f + "x" + m;
							n = i.join(".") + e + "." + l
						}
					}
				}
				
				j.attr("src", n);
				j[0].onload || (j[0].onload = function() {
					$(this).removeClass(b.cla).removeAttr(b.srcStore), b.imglist[k] = null, this.onerror = this.onload = null
				}, j[0].onerror = function() {
					if (!b.onerrorImgUrl) {
						return
					}
					this.src = b.onerrorImgUrl;
					$(this).removeClass(b.cla).removeAttr(b.srcStore);
					b.imglist[k] = null;
					this.onerror = this.onload = null
				})
			}

			if (b.imglist && b.imglist.length > 0) {
				b.imglist.each(function(f, g) {
					if (!g) {
						return
					}
					var e = $(g);
					if (!d(e)) {
						return
					}
					if (!e.attr(b.srcStore) || !e.hasClass(b.cla)) {
						return
					}
					c(e, f)
				})
			}
		}
	};
	return new a()
}); 