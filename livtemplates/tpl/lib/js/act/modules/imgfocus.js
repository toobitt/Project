define(function(require, exports, module) {
	var $, jQuery;
	$ = jQuery = require('$');
	window.$ = $;
	window.jQuery = $;
	//焦点图控件
	(function($) {
	    $.extend(jQuery.easing, {
	        easeOutCubic: function (x, t, b, c, d) {
	            return c * ((t = t / d - 1) * t * t + 1) + b;
	        }
	    });
	    $.fn.imgfocus = function(options) {
	        var opts = $.extend({}, $.fn.imgfocus.defaults, options);
	        return this.each(function() {
	            var _this = $(this);
	            var index = 0;
	            var timer = null;
	            var oNumList = _this.find(opts.numbox).children();
	            var imgListBox = _this.find(opts.imgbox);
	            var imgListBoxChid = imgListBox.children();
	            if (opts.drection == "filter")imgListBoxChid.eq(0).css({"opacity":1,"display":"block"}).siblings().css({"opacity": 0,"display":"none"});
	            if (opts.auto)auto();
	            function auto() {
	                timer = setInterval(function() {
	                    index++;
	                    if (opts.contine) {
	                        if (index == opts.imgLen) {
	                            if (opts.drection == "up") {
	                                imgListBoxChid.eq(0).css({"position":"relative","top":opts.imgLen * opts.imgboxHeight});
	                            }
	                            else if (opts.drection == "left") {
	                                imgListBoxChid.eq(0).css({"position":"relative","left":opts.imgLen * opts.imgboxWidth});
	                            }
	                            setTimeout(function() {
	                                index = 0
	                            }, 500)
	                        }
	                    }
	                    else {
	                        index == opts.imgLen ? index = 0 : index = index;
	                    }
	                    $.fn.imgfocus.action(opts, index, oNumList, imgListBox, imgListBoxChid, _this);
	                }, opts.speed)
	            }
	
	            _this.hover(function() {
	                clearInterval(timer);
	            }, function() {
	                if (opts.auto)auto();
	            })
	            oNumList[opts.usevent](function() {
	                index = oNumList.index(this);
	                setTimeout(function() {
	                    $.fn.imgfocus.action(opts, index, oNumList, imgListBox, imgListBoxChid, _this)
	                }, 300);
	            })
	        })
	    }
	    $.fn.imgfocus.action = function(opts, index, oNumList, imgListBox, imgListBoxChid, _this) {
	        switch (opts.drection) {
	            case "left":
	                imgListBoxChid.css({float:"left"})
	                imgListBox.width(opts.imgLen * opts.imgboxWidth);
	                imgListBox.stop().animate({
	                    left:[-index * opts.imgboxWidth,'easeOutCubic']
	                }, 500, function() {
	                    if (opts.contine && index == 0) {
	                        imgListBoxChid.eq(0).css("position", "");
	                        imgListBox.css("left", 0);
	                    }
	                })
	                if (opts.contine && index == opts.imgLen)index = 0;
	                break;
	            case "up":
	                _this.height(opts.imgboxHeight);
	                imgListBox.stop().animate({
	                    top:[-index * opts.imgboxHeight,'easeOutCubic']
	                }, 500, function() {
	                    if (opts.contine && index == 0) {
	                        imgListBoxChid.eq(0).css("position", "");
	                        imgListBox.css("top", 0);
	                    }
	                })
	                if (opts.contine) {
	                    if (index == opts.imgLen)index = 0;
	                }
	                break;
	            case "filter":
	                imgListBoxChid.eq(index).css({"position":"absolute","left":"0px","top":"0px","z-index":"1","display":"block"}).siblings().css({"z-index":"0"})
	                imgListBoxChid.eq(index)
	                    .stop(true,true).animate({opacity:1}, 800)
	                    .siblings().stop(true,true).animate({opacity:0}, 800)
	                break;
	            default:
	                break;
	        }
	        oNumList.eq(index).addClass(opts.addClass).siblings().removeClass(opts.addClass);
	    }
	
	    $.fn.imgfocus.defaults = {
	        drection: "up",
	        numbox: "#num",
	        imgbox: "#show_img",
	        speed: 3000,
	        addClass: "on",
	        imgboxWidth:700,
	        imgboxHeight:300,
	        imgLen:5,
	        auto:true,
	        contine:true,
	        usevent:"mouseover"
	    }
	})(jQuery);
	
	$(function(){
		//小组页焦点图
		var flash = $("#youDoing_flash").imgfocus({
			drection:"filter",contine:false,speed:5000,imgLen:5,imgboxWidth:700,imgboxHeight:300,
			addClass:'current',
			numbox: '#num2',
			imgbox: '#show_img2'
		});
        if(flash[0]){
            flash.find('.banner-nav1').width(function(){
                var lis = $(this).find('li');
                lis.last().css('margin-right', 0);
                var ww = 0;
                lis.each(function(){
                    ww += $(this).outerWidth();
                });
                return lis.eq(0).css('margin-left', ($('#youDoing_flash').width() - ww) / 2 + 'px');
            });
        }
		//首页焦点图
		$("#index_flash").imgfocus({drection:"filter",contine:false,speed:5000,imgLen:3,imgboxWidth:240,imgboxHeight:165});
	})
})