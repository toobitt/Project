define(function(require, exports, module) {
	var ActionResult = require('./action_result_create');
	var $ = require('$');
	require( 'uploadify/uploadify' );
	var Backbone = require( 'Backbone' );
	var PCAS = require( 'PCAS' );
	require( 'jquery-ui' );
	require( 'jquery.validate' );
	require( 'jquery.form' );
	
	
	
	var map = null;
	options = {
		onSelect: function (point, zoom, address) {
			$('#pub-address').val(address);
			$('#location').val( point.lng + 'X' + point.lat + 'X' + zoom);
		},
		onCancel: function () {
			$('#location').val('');
		}
	};
	mapMethods = {
		initMap: function (el ,point, zoomsize) {
			map = new BMap.Map(el);
			map.centerAndZoom(point, zoomsize);//设定地图的中心点和坐标并将地图显示在地图容器中
			map.enableScrollWheelZoom();//启用地图滚轮放大缩小
			//向地图中添加缩放控件
			var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
			map.addControl(ctrl_nav);
			//向地图中添加比例尺控件
			var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
			map.addControl(ctrl_sca);
			
			map.addEventListener('click', function(e) {
				mapMethods.createMark( e.point );
			});
			if (point instanceof BMap.Point) {
				map.addOverlay( new BMap.Marker(point) );
			}
		},
		createMark: function (point) {
			(new BMap.Geocoder()).getLocation(point, function (result) {
				if (result) {
					map.clearOverlays();
					var marker = new BMap.Marker(point);
					marker.enableDragging();
					marker.addEventListener( 'dragend', function (e){
						mapMethods.createMark( e.point );
					});
					map.addOverlay(marker);
					options.onSelect(point, map.getZoom(), result.address);
		        	marker.openInfoWindow(
		        		new BMap.InfoWindow( mapMethods.templateMark(result)[0] , { width:222 } ),
				    	result.point
				    );
				}
			});
		},
		templateMark: function (result) {
			return $(
				'<div style="width:220px"><span style="width:120px">' + result.address + '</span>' + 
				'<hr style="border:solid 1px #cccccc;" />' + 
				'<a style="display:block;corlor:#0000cc;text-decoration:underline;text-align:right;cursor:pointer;">取消标注</a>' + 
				'</div>'
           	).find('a').click( function () {
				map.clearOverlays();
				options.onCancel();
			}).end();
		}
	};
	
	
	return Backbone.View.extend({
		events: {
			'click .information-list li': 'toggle',
			'change #address-select-box': 'setMap',
			'click #addressToggle1': 'hideAddress',
			'click #addressToggle2': 'showAddress',
			'click #need_infoToggle1': 'hideNeed',
			'click #need_infoToggle2': 'showNeed',
			'click #peapleNumTog2': 'hidePeaple',
			'click #peapleNumTog1': 'showPeaple',
			'click #payTog2': 'showPeaple',
			'click #payTog1': 'hidePeaple',
			'click #timeTog2': 'showPeaple',
			'click #timeTog1': 'hidePeaple'
		},
		initialize: function (options) {
			optins = $.extend({}, this.options);
			this.el = $(this.el);
			new PCAS("q_province", "q_city", "q_area", this.options.province, this.options.city, this.options.area);
			this.img = this.$('.poster-view');
			this.action_img = this.$('input[name=q_action_img]');
			this.province = this.$('[name=q_province]');
			this.city = this.$('[name=q_city]');
			this.area = this.$('[name=q_area]');
			this.addressBox = this.$('#address-select-box').parent();
			this.needInfo = this.$('.information-detail');
			$('#file-upload1').uploadify({
				buttonText: '+ 添加海报',
				height: '30',
				swf: JS_PATH + 'modules/uploadify/uploadify.swf',
				uploader: 'run.php?mid=305&a=upload&ajax=1',
				queueID: 'pic-list1',
				removeCompleted: true,
				fileTypeExts: '*.jpg; *.jpeg; *.gif; *.png; *.bmp',
				fileTypeDesc: '请选择图片',
				multi: false,
				fileSizeLimit: '20MB',
				onUploadSuccess: $.proxy(this.createPic, this)
			});
			this.start = $('#pub-startime');
			this.end = $('#pub-endtime');
			this.start.datetimepicker({
				//defaultDate: "+1w",
				changeMonth: true,
				onClose: $.proxy(function( selectedDate, input, ins ) {
					//this.end.datetimepicker( "option", "minDate", selectedDate );
					this.end.focus();
				}, this)
			});
			this.end.datetimepicker({
				//defaultDate: "+1w",
				changeMonth: true,
				onSelect: $.proxy(function( selectedDate ) {
					//this.start.datetimepicker( "option", "maxDate", selectedDate );
				}, this)
			});
			
			/*地图在display：none的时候渲染有问题，改下*/
			this.location = $('#location').val();
			if ( $('#addressToggle1').find('input').prop('checked') ) {
				$('#addressToggle2').one('click', $.proxy(function () {
					this.initMap();
				}, this));
			} else {
				this.initMap();
			}
			this.validate();
			
			/*新加了功能，把详情换成编辑器*/
			this.actionResult = new ActionResult({
				url: 'run.php?mid=305&a=uploadReview&ajax=1',
				el: this.$('.topic-form-box').eq(0),
				index: 1,
				data: options.data,
				slideBar: [["Bold","Underline","StrikeThrough","JustifyLeft","JustifyCenter","JustifyRight","InsertOrderedList","InsertUnorderedList","BlockQuote","Link","Unlink","Source"]]
			});
			
			/*被内嵌*/
			if (top != window) {
				var me = this;
				this.$el.submit(function (e) {
					e.preventDefault();
					me.actionResult.ueditor.sync();
					me.$el.ajaxSubmit({
						success: function () {
							parent.$('#add_auth').hide();
							parent.jAlert('编辑成功!');
						},
						error: function () {
						}
					})
				});
			}
		},
		toggle: function (event) {
			var li = $(event.currentTarget);
			li.toggleClass('info-selected');
			if ( $(event.target).is(':checkbox') ) return;
			(function () {
				this.prop( 'checked', !this.prop('checked') );
			}).call( li.find('input:checkbox') );
		},
		initMap: function () {
			var location;
			if (this.location) {
				location = this.location.split('X');
				
				mapMethods.initMap('baidu-map', new BMap.Point( location[0], location[1] ), location[2]);
			} else {
				mapMethods.initMap('baidu-map', '南京');
			}
		},
		hideAddress: function (e) {
			$(e.currentTarget).find('input').prop('checked', true);
			this.addressBox.slideUp();
		},
		showAddress: function (e) {
			$(e.currentTarget).find('input').prop('checked', true);
			this.addressBox.slideDown();
		},
		hideNeed: function (e) {
			$(e.currentTarget).find('input').prop('checked', true);
			this.needInfo.slideUp();
		},
		showNeed: function (e) {
			$(e.currentTarget).find('input').prop('checked', true);
			this.needInfo.slideDown();
		},
		hidePeaple: function (e) {
			$(e.currentTarget).find('input').prop('checked', true).end().siblings('.radio-detail').slideUp().find('input').val('');
		},
		showPeaple: function (e) {
			$(e.currentTarget).find('input').prop('checked', true).end().siblings('.radio-detail').slideDown();
		},
		validate: function () {
			$.validator.addMethod("notDefault", function(value, element, param) { //输入只能是mao
				return !(value == param);
			}, '必填字段!');
			var start = this.$('[name=q_start_time]')[0],
				end = this.$('[name=q_end_time]')[0];
			this.el.validate({
				rules: {
					q_action_name: 'required',
					q_slogan: {
						'required': true,
						maxlength: 70
					},
					q_start_time: {
						required: true,
						notDefault: '报名开始时间'
					},
					q_end_time: {
						required: true,
						notDefault: '报名结束时间'
					},
					q_summary: 'required',
					q_action_img: 'required'
				},
				onfocus: true,
				errorPlacement: function (errorElement, input) {	
					if ( input[0] == start || input[0] == end ) {
						return;
					}
					errorElement.appendTo( input.parent() );
				}
			});
		},
		setMap: function () {
			var address = this.province.val() + this.city.val() + this.area.val();
			if ( !address ) return;
			map && map.setCenter( address );
		},
		createPic: function (file, data, response) {
			try {
				data = $.parseJSON(data)[0];
				this.img.empty().append('<img src="' + data.url + '" />');
				this.action_img.val(data.data);
				$('#pic-list1').empty();
			} catch (e) {
				
			}
		}
	})
})