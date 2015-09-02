//用于全局命名空间，和事件发布订阅
window.App = {};
//事件功能minxin App
_.extend(App, Backbone.Events);

//增强dom，给$加小插件
$.fn.topOffset = (function () {
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
