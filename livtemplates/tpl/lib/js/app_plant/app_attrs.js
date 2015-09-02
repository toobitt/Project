//新属性管理
$.imgSrc = function( pic ){
	if( pic.dir ){
		return [pic.host, pic.dir, pic.filepath, pic.filename].join('');
	}else{		//upaiyun
		return [pic.host, pic.filepath, pic.filename ].join('');
	}
//	console.log(pic);
};
$(function(){
(function(){
	$.widget('appPlant.appAttrs', {
		options : {},
		_init : function(){
			var _this = this;
			this._on({
				'click li' : '_selectType'
			});
			this.el = $('.set-attr-default');
			this._handleData();
			this.element.on('change', 'select', function(){
				_this._switch( $(this).val() );
			});
		},
		_handleData : function(){
			this.config = {};
			for(var i=0,len=attrConfig.length; i<len; i++){
				if( attrConfig[i]['uniqueid'] ){
					this.config[ attrConfig[i]['uniqueid'] ] = attrConfig[i];
				}
			}
//			console.log( this.config );
			if( currentData.attr_type_id ){
				var info = {
						typeFlag : attrConfig[currentData.attr_type_id]['uniqueid'],
						style_value : currentData.style_value,
						default_value : currentData.default_value
				};
//				console.log( info );
				$('#attrs-tpl').tmpl( info ).appendTo( this.el.empty().attr('_type', info.typeFlag) );
				this._initWidgets();
			}
		},
		initWidgets : function(){
			this._initWidgets();
		},
		_initWidgets : function(){
			this.el.find('.only-spectrum .spectrum-colorpicker').each(function(){
				var me = $(this);
				me.flatuiSpectrum({
					color : me.attr('_color'),
					alpha : me.attr('_alpha'),
					showAlpha : me.hasClass('alpha'),
					preferredFormat: "hex",
					change: function(cobj) {
						var color = cobj.toHexString(),
							alpha = cobj._a,
							$el = me.siblings('.flatui-spectrum-box'),
							hasAlpha = me.hasClass('alpha');
						$el.find('.color-prev-tile').css({
							background : color,
							opacity : alpha
						});
						$el.find('.color-input').val( color );
						$el.find('.alpha-input').val( alpha );
						me.siblings('[type="hidden"]').val( me.hasClass('alpha') ? color+'|'+alpha : color );
					},
				});
			});
			this.el.find('.advance-setting .spectrum-colorpicker').each(function(){
				var me = $(this);
				me.flatuiSpectrum({
					color : me.attr('_color'),
					alpha : me.attr('_alpha'),
					showAlpha : me.hasClass('alpha'),
					preferredFormat: "hex",
					change: function(cobj) {
						var color = cobj.toHexString(),
							alpha = cobj._a,
							$el = me.siblings('.flatui-spectrum-box'),
							hasAlpha = me.hasClass('alpha');
						$el.find('.color-prev-tile').css({
							background : color,
							opacity : alpha
						});
						$el.find('.color-input').val( color );
						$el.find('.alpha-input').val( alpha );
						me.siblings('[type="hidden"]').val( ['color', color, alpha].join('|')  );
					},
				});
			});
			this.el.find('.advance-setting').on('blur', '.text-input', function(){
				$(this).siblings('[type="hidden"]').val( 'text|'+$(this).val().trim() ).removeAttr('disable');
			});
			this.el.find('.is-tile').click(function(){
				$(this).siblings('[type="hidden"]').val( $(this).prop('checked') - 0 );
			});
		},
		_selectType : function(e){
			var self = $(e.currentTarget),
				target = self.find('a'),
				id = target.attr('attrid');
//				data = this.config['a'+id];
			this._switch(id);
		},
		_switch : function( id ){
			var info = {
					typeFlag : attrConfig[id]['uniqueid'],
					style_value : {
						datasource : [{}]
					},
					isNew : true
			};
			$('#attrs-tpl').tmpl( info ).appendTo( this.el.empty().attr('_type', info.typeFlag) );
			this._initWidgets();
		},
	});
	$.widget('appPlant.setting',{
		_init : function(){
			this._on({
				'click .add-btn' : '_add',
				'click .del-btn' : '_del',
				'click .pic-prev' : '_triggerFile',
				'change input[type="file"]' : '_prev',
				'click .common-tab-wrap .tab-btn' : '_tab'
			});
		},
		_add : function( e ){
			var type = this.element.attr('_type');
			var info = {
					typeFlag : type,
					data : [{}],
					index : this.element.find('.items-box .setting-item').length || this.element.find('.items-box .form-group').length
			};
			$('#more-setting-item-tpl').tmpl( {}, info ).appendTo( this.element.find('.items-box') );
		},
		_del : function(e){
			var target = $(e.currentTarget),
				parent = target.closest('.items-box');
			target.closest('.setting-item').remove();
			target.closest('.form-group').remove();
			parent.find('input[type="radio"]').each(function(k,v){
				$(this).val( k );
			});
			parent.find('input[type="checkbox"]').each(function(k,v){
				$(this).val( k );
			});
		},
		_triggerFile : function(e){
			$(e.currentTarget).siblings('[type="hidden"]').attr('disabled', true);
			$(e.currentTarget).siblings('[type="file"]').removeAttr('disabled').click();
		},
		_prev : function(e){
			var self = e.currentTarget,
				files = self.files,
				reader = new FileReader();
				reader.onloadend = function(event){
					var src = event.target.result
					$(self).siblings('.pic-prev').find('img').attr('src', src);
	            };
	            reader.readAsDataURL(files[0]);
		},
		_tab : function(e){
			var self = $(e.currentTarget),
				wrap = self.closest('.common-tab-wrap'),
				i = self.index();
			self.addClass('selected').siblings().removeClass('selected');
			var currentItem = wrap.find('.tab-item').eq(i).removeClass('hide');
			currentItem.find('input').removeAttr('disabled');
			currentItem.siblings('.tab-item').addClass('hide').find('input').attr('disabled', true);
		},
	});
	var setting = $('.set-attr-default');
	setting.setting();
})();
});