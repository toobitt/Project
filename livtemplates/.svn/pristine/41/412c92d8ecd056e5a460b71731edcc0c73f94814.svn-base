(function() {
	parent.$.fn.topOffset = (function () {
		function getOwnerWindow(el) {
			return el.ownerDocument.defaultView;
		}
		function getOwnerIframe(win) {
			if (win === top) return null;
			var i = 0;
			while (true) {
				if ( win.parent.frames[i] == win ) {
					break;
				}
				i++;
			}
			return win.parent.$('iframe')[i];
		}
		function topOffset() {
			var el = this[0],
				tmpOffset,
				offset = { left: 0, top: 0 },
				curWindow;
			do {
				tmpOffset = $(el).offset();
				offset.left += tmpOffset.left;
				offset.top += tmpOffset.top;
				curWindow = getOwnerWindow(el);
				el = getOwnerIframe(curWindow);
			} while (curWindow !== top)
			return offset;
		}
		return topOffset;
	})();
	
	$(function () {
		var uploader = top.livUpload;
		var options = livUploder_params;
		var el = parent.$('.nav-box');
		var offset = el.topOffset();
		offset.left += 1000;
		offset.top += 5;
		var doReInit = function() {
			top.$.globalData.set('vod_mid', gMid);
			uploader
				.position(offset)
				.dimensions(107, 34)
				.wraperCss({ 'opacity': 0, 'display': 'block', 'z-index': 1 });
		}
		if ( uploader.isInit() ) {
			doReInit();
		} else {
			uploader.createFlash(options, doReInit);
		}


        top.$.uploaderTimer = function(top){
            top.$.uploaderTimer && top.clearInterval(top.$.uploaderTimer);
            return top.setInterval.call(top, function(){
                var main = this.$('#mainwin');
                var btn = main.contents().find('#hg_parent_page_menu .flash-position');
                if(!btn.length){
                    this.clearInterval.call(this, this.$.uploaderTimer);
                    return;
                }
                var mainOffset = main.offset();
                var offset = btn.offset();
                offset.left += mainOffset.left;
                offset.top += mainOffset.top;
                this.livUpload.position(offset);
            }, 1000);
        }(top);
        
        $(window).on( 'unload', function(){
        	uploader.initPosition();
        } );
	});
	
})();