;(function () {
	var root = this;
	var optionsMap = {};
	
	function isProcessing (ui) {
		return ui.hasClass('processing');
	}
	var ajaxSetting = {
		dataType: 'json',
		timeout: 10 * 1000
	};
	var neddProxyMethods = ['beforeSend', 'success', 'error'];
	function sendAjax (ui, options) {
		ui.addClass('processing');
		var url = options.baseUrl + '&' + (ui.data('suburl') || '');
		delete options.baseUrl;
		
		var setting = $.extend({
			complete: function () {
				ui.removeClass('processing');
			},
			url: url
		}, ajaxSetting, options);
		
		/**/
		if (setting.data && $.isFunction(options.data)) {
			setting.data = setting.data.call(ui);
		}
		/*从原callback函数中，生成为此次ajax请求所使用的新函数，curry化了数据*/
		for (var i in neddProxyMethods) {
			setting[ neddProxyMethods[i] ] = $.proxy( setting[ neddProxyMethods[i] ], ui, setting.data);
		}
		
		if( setting.beforeSend() === false ) {
			ui.removeClass('processing');
			return;
		}
		delete setting.beforeSend;
		
		$.ajax( setting );
	}

	function processAjaxRequest (constOptions, e) {
		var ui = $(e.target);
		var a = ui.data('a');
		if ( ui.is('a') ) e.preventDefault();
		/*正在处理中则返回*/
		if ( isProcessing(ui) ) return;
		/*禁止了则返回*/
		if ( ui.data('disabled') ) return;
		
		var options = $.extend({}, constOptions);/*一个副本*/
		
		/**/
		if ( options.onStart.call(ui) === false ) return;
		
		delete options.onStart;
		if (options.needConfirm) {
			delete options.needConfirm;
			jConfirm(options.confirmMsg, '提醒', function (yes) {
				if (yes) {
					sendAjax( ui, options );
				}
			}).position(ui);
			delete options.confirmMsg;
		} else {
			sendAjax( ui, options );
		}
	}
	
	
	function normalize(options, a) {
		var baseUrl = 'run.php?mid=' + gMid + '&a=' + a;
		return $.extend({
			beforeSend: $.noop,
			success: $.noop,
			error: $.noop,
			onStart: $.noop,
			baseUrl: baseUrl,
			needConfirm: false
		}, options);
		
	}
	
	$.fn.clickAjax = function (options) {
		var a = this.data('a');
		
		options = normalize(options, a);
		/*curry函数processAjaxRequest，让其每次调用访问自己独有的options*/
		this.on( 'click', $.proxy(processAjaxRequest, this, options) );
		return;
	};
})();


