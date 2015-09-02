$(function(){
	(function($){
		$.widget('lbs.lbs_set',{
			options : {
				hasTmpl: '',
				noTmpl : '',
			},
			
			_create : function(){
				this.defaultDom = ['default-option', 'default-value', 'default-reduce', 'default-fill', 'default-batch', 'type-custom', 'type-date','type-price'];
				this.val =  this.element.find('.data-type').filter(function(){
					return $(this).prop('checked');
				}).closest('li').find('span').text();
				var type =  this.element.find('.data-type').filter(function(){
					return $(this).prop('checked');
				}).attr('_type');
				this.extraTypes = ['classify', 'price', 'date', 'label', 'custom'];	//叮当里新增的五个类型
				this._toggle( type, this.val, true );
			},
			
			_init : function(){
				this._on({
					'click .data-type' : '_type',
					'click .add-option' : '_addoption',
					'click .default-batch .batch' : '_batch'
				});
				this._submit();
			},
			
			_type : function( event ){
				var self = $(event.currentTarget),
					txt = $.trim( self.attr('_val') ),
					type = $.trim( self.attr('_type') ); 
				this._toggle( type, txt );
			},
			
			_toggle : function( type, txt, init ){
				//为叮当新增的类型扩展
//				$('.my-tpl-wrap').empty()
//				if( $.inArray( type, this.extraTypes ) > -1 ){
//					this._tabTpls( type , init);
//					return;
//				}
				//end
				var str = this.getStr( type ),
					_this = this;
				var info={};
				if( $.isArray( str ) ){
					$.each(this.defaultDom, function(k, v){
						if( $.inArray( k, str ) > -1 ){
							var isshow = true;
							info.txt = txt;
							if( !k && txt == _this.val && !init){
								var box = $('.' + v).find('.option-contain').empty();
								$.each($.globaldefault,function(k , v){
									info.value = v;
									$( _this.options.hasTmpl ).tmpl( info ).appendTo( box );
								});
							}else if( !k && !init){
								var box = $('.' + v).find('.option-contain').empty();
								info.value = '';
								$( _this.options.hasTmpl ).tmpl( info ).appendTo( box );
							}
							if( k == 2 && type == 'img'){
								if( $('.default-batch').find('input:checked').val() == 0 ){
									$('.' + v).show();
								}else{
									$('.' + v).hide();
								}
							}else{
								$('.' + v).show();
							}
						}else{
							$('.' + v).hide();
						}
					});
				}
			},
			_tabTpls : function( type, isTab ){
				var data = {
						type : type,
						formdata : !isTab ? {} : $.formdata
				};
				$('#my-tpls').tmpl(data).appendTo( $('.my-tpl-wrap') );
			},
			_batch : function( event ){
				var self = $(event.currentTarget);
				var isbatch = self.val(); 
				$('.default-reduce')[(isbatch == 0) ? 'show' : 'hide']();
			},
			
			getStr : function( type ){
				switch( type ){
					case 'checkbox' :
					case 'radio' : 
					case 'option' :{
						return [0,1,2,3];
					}
					case 'text' : {
						return [1,2,3];
					}
					case 'textarea' : {
						return [1,3];
					}
					case 'img' : {
						return [2,3,4];
					}
					case 'classify' : {		//分类
						return [0, 1];
					}
					case 'price' : {		//价格
						return [0, 7];
					}
					case 'date' : {			//日期
						return [6];
					}
					case 'label' : {		//特殊标签
						return [0];
					}
					case 'custom' : {		//自定义描述
						return [5];
					}
					
					
					default : {
						return false;
					}
				}
			},
			
			_addoption : function(event){
				var self = $(event.currentTarget),
					obj =self.closest('.default-option').find('.option-contain'),
					op = this.options;
				obj.append( $(op.noTmpl).html() );
			},
			
			_submit : function(){
				var	sform = this.element,
					_this = this;
				sform.submit(function(){
					var val = $.trim(sform.find('input[name="zh_name"]').val()),
						txt = $.trim(sform.find('input[name="field"]').val()),
						num = /^[0-9]*$/;
					if(!val){
						alert("请填写信息名称");
						return false;
					}
					if(txt && num.test(txt)){
						alert("标识不能全部是数字");
						return false;
					}
				});
			},
		});
	})($);
	$('.ad_form').lbs_set({
		hasTmpl : $('#option-tpl'),
		noTmpl : $('#add-option-tpl')
	});
});
