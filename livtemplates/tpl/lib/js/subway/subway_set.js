jQuery(function($){
	$.roadInfo = {
		colorPicker : function(){
			this.$('.color-picker').hg_colorpicker()
		},
		
		switchAble : function(){
			var _this = this;
			this.$('.common-switch').each(function(){
				var $this = $(this);
				$this.hasClass('common-switch-on') ? val = 100 : val = 0;
				$this.hg_switch({
					'value' : val,
					'callback' : function( event, value ){
						var is_on = 0;
						( value > 50 ) ? is_on = 1 : is_on = 0;
						_this.el.find('input[name="is_operate"]').val( is_on );
					}
				});
			});
		},
		
		saveAside : function(){
			this.initSubmit();
		},
		
		initSubmit : function(){
			var _this = this, type = true, str;
			var form = this.$('#roadForm');
			var dom = $('.m2o-save');
			form.ajaxSubmit({
				beforeSubmit:function(){
					var title = form.find('input[name="title"]').val();
					if( !title ){
						type = false;
						str = '请先填写线路名称';
					}else{
						var bool =  $('.way-content').subwayInfo('vertifySpace', title);
						if( bool ){
							str = '线路名称' + bool;
							type = false;
						}
					};
					if( !type ){
						$('.way-content').subwayInfo('myTip', dom, str);
						$('.way-map').find('.ui-slider-handle').hide();
						return false;
					}
				},
				dataType : 'json',
				success:function( data ){
					if(data['callback']){
						eval( data['callback'] );
						return;
					}else{
						if( data[0] && data[0].id ){
							_this.getRoadid( data[0].id );
						}
						$('.way-content').subwayInfo('myTip', dom, '保存线路成功');
					}
				},
				error:function(){
					$('.way-content').subwayInfo('myTip', dom, '保存线路失败');
				}
			});
		},
		
		closeMap : function(){
			this.$('.map-box').addClass('map-pop').find('.map-lude input').val('');
		},
		
		saveMap : function(){
			var map = this.$('.map-box'),
				longitude = map.find('input[name=site_longitude]').val(),
				latitude = map.find('input[name="site_latitude"]').val();
			var type = map.data('type');
			if( type.indexOf('_') > 0 ){
				var typearr = type.split('_');
				box = this.$('.' + typearr[0]).find('.let-list:nth-child(' + (parseInt(typearr[1]) + 1) + ')');
			}else{
				var box = this.$('.' + type).find('.m2o-item');
			}
			box.find('.site-titude').first().find('input').val( longitude ); 
			box.find('.site-titude').last().find('input').val( latitude );   
			this.closeMap();
		},
		
		saveBus : function(){
			var box = this.$('.bus-box'),
				busArr = [];
			box.find('li').each(function(){
				if($(this).find('input').prop('checked')){
					busArr.push($(this).attr('_id'));
				}
			});
			var type = box.data('type');
			box = this.$('.operateform').find('.let-list:nth-child(' + (type + 1) + ')');
			box.find('.station_id').val( busArr.join(',') );
			this.closeBus();
		},
		
		closeBus : function(){
			this.$('.bus-box').addClass('pop-hide');
		},
		
		getRoadid : function( id ){
			if( !this.$('#roadForm').data('id') ){
				if( $('.way-content').find('.way-road').length ){
					var dom = $('.way-content').find('.way-road').first()
					$('.way-content').subwayInfo('getSitePull', dom, id);
				}
			}
			this.$('#roadForm').data('id', id);
			this.$('.m2o-title').find('.m2o-l').html('更新地铁线路');
			this.$('#roadForm').find('input[name="id"]').val( id );
			this.$('#roadForm').find('input[name="a"]').val('update');
		},
		
		blurTitle : function( event ){
			var val = $(event.currentTarget).val();
			this.$('#roadForm').find('input[name="title"]').val( val );
		},
		
		$:function( s ){
			return this.el.find(s);
		},
		
		init : function( el ){
			this.el = el;
			this.colorPicker();
			this.switchAble();
			this.el
			.on('click', '.m2o-save', $.proxy(this.saveAside, this))
			.on('blur', '.m2o-m-title', $.proxy(this.blurTitle, this))
			.on('click', '.map-close', $.proxy(this.closeMap, this))
			.on('click', '.map-save', $.proxy(this.saveMap, this))
			.on('click', '.bus-save', $.proxy(this.saveBus, this))
			.on('click', '.bus-close', $.proxy(this.closeBus, this))
		},
	}
	$.roadInfo.init( $('.main-wrap') );
});
