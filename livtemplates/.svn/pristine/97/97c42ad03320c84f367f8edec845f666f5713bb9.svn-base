;(function() {

	function AjaxCache() {
		this.cache = {};
		this.waitQueue = {};
	}
	AjaxCache.prototype.get = function(url, options) {
		var data = this.cache[url];
		options = options || {};
		
		options.before && options.before();
		
		if (data && !options.refresh) {
			if ( options.after ) { 
				// 异步执行
				setTimeout(function() { options.after(data); });
			}
			return;
		}
		var _this = this;
		this.cache[url] = null;
		if (this.waitQueue[url]) {
			this.waitQueue[url].push(options.after);
			return;
		} else {
			this.waitQueue[url] = [options.after];
		}
		
		if( $.globalAjax && options.target ){
			$.globalAjax( options.target, function(){
				return $.get(url, function(data) {
					_this.cache[url] = data;
					var i, queue = _this.waitQueue[url];
					for (i in queue) {
						queue[i] && queue[i](data);
					}
					_this.waitQueue[url] = null;
				}, options.type);
			} );
		}else{
			$.get(url, function(data) {
				_this.cache[url] = data;
				var i, queue = _this.waitQueue[url];
				for (i in queue) {
					queue[i] && queue[i](data);
				}
				_this.waitQueue[url] = null;
			}, options.type);
		}
		
	}
	
	window.AjaxCache = AjaxCache;
	
})();