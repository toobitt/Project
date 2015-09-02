$(function() {
	function addAddress() {
		var _this = this, is_prevent = false;
		this.listblock = $('.list-block');
		this.Hempty = {
			"buyer-empty" : "请输入收货人姓名",
			"buyer-wrong" : "请正确输入收货人姓名",
			"phone-empty" : "请输入手机号码",
			"phone-wrong" : "手机号格式不正确",
			"province-empty" : "请选择省份",
			"city-empty" : "请选择城市",
			"region-empty" : "请选择地区",
			"dtladdr-empty" : "请填写详细地址",
			"dtladdr-wrong" : "请正确填写详细地址",
			"zipcode-empty" : "请填写邮编",
			"zipcode-wrong" : "请正确填写邮编"
		};
		this.tips = this.append();
		this.init();
		$('.button-submit').click(function(event) {
			var self = $(event.target);
			var childbox = _this.listblock.find('.item-input').children();
			var data = [];
			childbox.each(function(index) {
				event.preventDefault();
				var $this = $(this);
				$this.removeClass('error');
				var tips = _this.limitSwitch($this);
				if (tips) {
					$this.addClass('error');
					_this.showtips(tips);
					is_prevent = true;
					return false;
				}
				is_prevent = false;
				var label = $this.attr("name"), info = {};
				info[label] = $this.val();
				data.push(info)
			});
			if (is_prevent) {
				return false;
			}
			if (data.length && self.is('a')) {
				_this.ajaxUrl(data);
			} else if (self.is('input[type="submit"]')) {
				$('#userAddress').submit();
			}
		});
	}


	$.extend(addAddress.prototype, {
		init : function() {
			$('.list-block').address();
		},

		limitSwitch : function(dom, method) {
			var label = dom.attr("id").substr(2), method = method || 'val'
			val = dom[method]();
			if (!val) {
				var tips_str = this.Hempty[label + '-empty'];
				return tips_str;
			}

			switch( label ) {
				case 'buyer' : {
					var code = this.codelength(val);
					if (code < 4 || code > 30 || /^[0-9]+$/.test(val)) {
						tips_str = this.Hempty["buyer-wrong"];
					}
					break;
				}

				case 'dtladdr' : {
					var code = this.codelength(val);
					if (code < 10 || code > 120 || /^[0-9]+$/.test(val) || /^[a-zA-Z]+$/.test(val)) {
						tips_str = this.Hempty["dtladdr-wrong"];
					}
					break;
				}

				case 'zipcode' : {
					if (!this.zipVerify(val)) {
						tips_str = this.Hempty["zipcode-wrong"];
					}
					break;
				}

				case 'phone' : {
					if (!this.phoneVerify(val)) {
						tips_str = this.Hempty["phone-wrong"];
					}
					break;
				}

			}
			return tips_str;
		},

		showtips : function(msg) {
			var tipDom = this.tips;
			tipDom.removeClass('fadeOut').addClass('fadeIn').html(msg);
			var setTime = setTimeout(function() {
				tipDom.removeClass('fadeIn').addClass('fadeOut');
			}, 800);
		},

		append : function() {
			return $('<div class="popDiv fadeOut"/>').appendTo(this.listblock).css({
				position : 'absolute',
				left : '50%',
				top : '50%',
				height : '46px',
				color : '#fff',
				padding : '0 20px',
				'border-radius' : '3px',
				'z-index' : 999,
				'line-height' : '46px',
				'background-color' : 'rgba(51, 51, 51, 0.8)',
				'transition' : 'opacity 0.3s'
			});
		},

		ajaxUrl : function(data) {
			data = this.handle(data);
			$.ajax({
				url : '',
				data : data,
				dataType : 'json',
				type : 'post',
				success : function(strValue) {
					location.pathname = "/livworkbench/doc/creditshop/index.html";
				}
			});
			//location.pathname="/livworkbench/doc/creditshop/index.html";
		},

		handle : function(data) {
			var param = {};
			$.map(data, function(obj) {
				$.extend(param, obj);
			});
			return param;
		},

		codelength : function(str) {
			var sum = 0;
			for (var i = 0, len = str.length; i < len; i++) {
				sum += 1;
				if (str.charCodeAt(i) > 255) {
					sum += 2;
				}
			}
			return sum
		},

		zipVerify : function(zip) {
			return /^\d{6}|0{6}$/.test(zip) || /^[a-zA-Z0-9\s\-]{6,10}$/.test(zip) || /^\d{3}|0{3}$/.test(zip) ? !0 : !1;
		},

		phoneVerify : function(phone) {
			var telreg = [/^(86){0,1}1\d{10}$/, /^(852){1}0{0,1}[1,5,6,9](?:\d{7}|\d{8}|\d{12})$/, /^(853){1}0{0,1}[6]\d{7}$/, /^(886){1}0{0,1}[6,7,9](?:\d{7}|\d{8}|\d{10})$/, /^(81){1}0{0,1}[7,8,9](?:\d{8}|\d{9})$/, /^(82){1}0{0,1}[7,1](?:\d{8}|\d{9})$/];
			var ph = /^1\d{10}$/.test(phone), rel = !0, is_verity = false;
			for (var i = 0, len = telreg.length; i < len; i++) {
				telreg[i].test(phone) || (rel != 1 );
				if (rel && ph) {
					is_verity = true;
				}
			}
			return is_verity;
		}
	})
	window.addAddress = addAddress;
	var addAddress = new addAddress();
});
