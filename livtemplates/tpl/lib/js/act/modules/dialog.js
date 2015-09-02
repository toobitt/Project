define(function (require, exports, module) {
	var $ = require('$');
	var _ = require( 'underscore');
	require('jquery-ui');
	require('jquery.form');
	function template () {
		return '<div style="overflow:visible;margin-left:-5px;" id="act-dialog" class="del-dialog-box"><div class="del-dialog-top"><div class="close-btn hide-text">关闭</div></div><div class="del-dialog-con"><div class="pub-head wd130 ml5"><em></em><span class="title-icon del-icon">提示 </span></div><p class="del-tip mt25"></p><p class="dialog-controll wd180 clearfix"><a class="success-btn mr15 fz16">确定</a><a class="write-btn-cancel fz16">取消</a></p></div><div class="del-dialog-bottom"></div></div>';
	}
	var noTitleOptions = {
		modal: true,
		width: 750,
		resizable: false,
		dialogClass: 'dialog-alert-wrap dialog-confirm-wrap',
		create: function (event, ui) {
			$('.ui-dialog-titlebar').hide();
		}
	};
	var alertTpl = _.template($('#template_alert').html());
	window.hAlert = function (msg, fn) {
		var dialog = $(alertTpl({msg: msg}));
		dialog.dialog($.extend({}, noTitleOptions, {width: 500})).draggable().css('overflow', 'visible')
			.on('click', '.success-btn', function () {
				dialog.dialog('destroy');
				dialog.closest('.login-dialog-box').remove();
				fn && fn();
			});
	};
	window.hConfirm = function (msg, fn) {
		var dialog = $(template()).find('.del-tip').html(msg).end();
		dialog.dialog($.extend({}, noTitleOptions, {width: 500})).draggable().on('click', '.success-btn,.write-btn-cancel,.close-btn', function () {
			dialog.dialog( 'destroy' );
			dialog.closest('.login-dialog-box').remove();
			if ( !$.isFunction(fn) ) return;
			$(this).is('.success-btn') ? fn(true) : fn(false);
		});
	};
	window.msgDialog = function (options, fn) {
		if ( !usrIsLogin() ) {
			return false;
		}
		var dialog = $( $('#template_msg').html() );
		dialog.dialog($.extend({}, noTitleOptions, {width: 650})).draggable()
			.on('click', '.close-btn', function () {
				dialog.dialog( 'destroy' );
				dialog.closest('.login-dialog-box').remove();
			});
		options = install(options);
		dialog.find('form').find('[name=uid]').val(options.id).end().find('#pub-sub').val(options.name).end()
			.submit(function () {
				var form = $(this);
				var val = form.find('textarea').val();
				if ( !$.trim(val) ) {
					blinkCss(form.find('textarea'), {oldValue: '', time: 2});
					return false;
				}
				if ( dialog.data('notSubmit') ) {
					blinkCss(form.find('.tip-location'), {oldValue: '', value: 'none', time: 2, prop: 'display'});
					return false;
				}
				form.find(':submit').prop('disabled', true).val('发送中，请等待...');
				form.ajaxSubmit({
					success: options.success || function (data) {
						dialog.dialog('destroy');
						dialog.closest('.login-dialog-box').remove();
					}
				})
				
				return false;
			}).on('keyup', 'textarea', function () {
				var me = $(this);
				var num = countStr(me.val());
				var juli = 300 - num;
				if (juli >= 0) {
					dialog.data('notSubmit', false);
					me.siblings('.tip-location').removeClass('invalid').text('(还可输入' + juli + '字)');
				} else {
					dialog.data('notSubmit', true);
					me.siblings('.tip-location').addClass('invalid').text('(超出' + (-juli) + '字)');
				}
			})
	};

	window.reasonDialog = function (options, fn) {
		if ( !usrIsLogin() ) {
			return false;
		}
		var triggerBtn = $(this);
		var dialog = $( $('#template_reason').html() );
		dialog.dialog($.extend({}, noTitleOptions, {width: 650})).draggable()
			.on('click', '.close-btn,.write-btn-cancel', function () {
				dialog.dialog( 'destroy' );
				dialog.closest('.login-dialog-box').remove();
			});
		options = install(options);
		dialog.find('form').submit(function () {
			var form = $(this);
			var val = form.find('textarea').val();
			val = $.trim(val);
			if ( !val ) {
				blinkCss(form.find('textarea'), {oldValue: '', time: 2});
				return false;
			}
			if ( val == form.find('textarea').data('default') ) {
				form.find('textarea').select();
				return false
			}
			form.find(':submit').prop('disabled', true).val('处理中...');
			form.ajaxSubmit({
				success: (options && options.success) || function (data) {
					
					dialog.dialog('destroy');
					dialog.closest('.login-dialog-box').remove();
					triggerBtn.attr('onclick', "hAlert.call(this, '你已提交申请，请等待审核。')");
				}
			})
			
			return false;
		})
		.on('focus', 'textarea', function () {
			dialog.find('.show-warn').empty();
		}).find('textarea').select();
	}
	window.loginDialog = function () {
		var dialog = $( $('#template_login').html() );
		dialog.dialog($.extend({}, noTitleOptions, {
			width: 680,
			open: function () {
				var Login = require('../act/login').extend({
					showTip: function (tip) {
						tip.show();
					},
					hideTip: function (tip) {
						tip.hide();
					},
					loginSuccess: function () {
						window.location.reload(true);
					}
				});
				new Login({el: $('#loginform')})
				dialog.find('input').blur();
			}
		})).draggable().on('click', '.close-btn', function () {
			dialog.dialog('destroy');
			dialog.closest('.login-dialog-box').remove();
		});
	}
	function install (options) {
		if ( !options ) return null;
		if ( (options instanceof $) || options.nodeType  ) {
			return {
				id: $(options).data('id'),
				name: $(options).data('name')
			}
		}
		return options;
	}
	function blinkCss (element, options) {
		var prop, v1, v2, time, hz;
		options = options || {}
		time = options.time || 3;
		time *= 2;
		hz = options.hz || 150;
		prop = options.prop || 'background-color',
		v1 = options.value || '#faffbd';
		v2 = options.oldValue === undefined ? element.css(prop) : options.oldValue;
		var fn, fn1, fn2;
		fn = fn1 = function () { element.css(prop, v1); };
		fn2 = function () { element.css(prop, v2); };
		for (var i = 0; i < time; i++) {
			setTimeout(fn, 150 * i)
			fn = fn == fn1 ? fn2 : fn1;
		}
	}
	function countStr(text) {
		var i, sum = 0, halfExist = false;
		for (i = 0; i < text.length; i++) {
			if ( text.charCodeAt(i) < 128 ) {
				halfExist || sum++;
				halfExist = !halfExist;
			} else {
				sum++;
			}
		}
		return sum;
	}
	window.usrIsLogin = function () {
		if ( !gUserId ) {
			loginDialog();
			return false;
		}
		return true;
	}
	return noTitleOptions;
}) 