$(function(){
	(function($){
		$.widget('code.code_form',{
			options : {
				preUrl : ''
			},
			
			_create : function(){
			},
			
			_init : function(){
				this._on({
					'click .use-pic' : '_usepic',
					'click input[name="type_id"]' : '_type',
					'click .store-upload' : '_bgpic',
					'click .bg-box li' : '_choose',
					'click input[name="is_bgcolor"]' : '_show',
					'click .pop-close-button2' : '_close',
					'click .pop-save-button' : '_save',
					'click .preview' : '_preview',
					'click .code-type li' : '_name',
					'blur .blur' : '_blur'
				})
				this._color();
//				this._submit();
			},
			
			_color : function(){
				this.element.find('.color-picker').hg_colorpicker();
			},
			
			_type : function(event){
				var self = $(event.currentTarget),
					item = this.element.find('.operate－mode'),
					aitem = this.element.find('.count-num'),
					obj = this.element.find('.dipartite-cover'),
				 	no = self.val();
				switch( no ){
					case '1':
						obj.show();
						this._prevent(item ,aitem);
						break;
					case '2':
						obj.hide();
						this._prevent(item ,aitem);
						break;
					case '3':
						obj.hide();
						this._prevent(item ,aitem);
						break;
					case '4':
						obj.show();
						this._prevent(item ,aitem);
						break;
					case '5':
						obj.show();
						item.hide();
						aitem.show();
						break;
				};
//				if(no == 5){
//					this.element.find('input[name="length"]').val(0);
//					this.element.find('input[name="operation"]').val(5);
//				}else{
//					this.element.find('input[name="length"]').val(4);
//					this.element.find('input[name="operation"]').val(0);
//				}
			},
			
			_prevent : function(item , aitem){
				item.show();
				aitem.hide();
			},
			
			_usepic : function(event){
				var self = $(event.currentTarget),
					isSelected = self.prop('checked'),
					item = this.element.find('.img-info');
				isSelected ? item.show() : item.hide();
			},
			
			_bgpic : function(event){
				var self = $(event.currentTarget),
					top = self.offset().top;
				this.element.find('.bg-box').css('top' , top-250);
			},
			
			_choose : function(event){
				var self = $(event.currentTarget);
				self.addClass('select').siblings().removeClass('select');
				self.find('.select-pic').show();
				self.siblings().find('.select-pic').hide()
			},
			
			_show : function(event){
				var self = $(event.currentTarget),
					num = self.val(),
					obj = this.element.find('.bg-display');
				num == 1 ? obj.show() : obj.hide();
			},
			
			_close : function(){
				this.element.find('.bg-box').css('top' , -400)
			},
			
			_save : function(){
				var item = this.element.find('.bg-box .bg-list.select'),
					id =  item.data('id'),
					name =item.attr('_name'),
					type =item.attr('_type'),
					url = item.data('url'),
					obj = this.element.find('.img-info');
				if(item[0]){
					obj.find('p').hide();
					obj.find('img').attr('src' , url);
					obj.find('.pic-hidden').val(id);
					obj.find('.name-hidden').val(name);
					obj.find('.type-hidden').val(type);
				}
				this._close();
			},
			
			_preview : function(event){
				var self = $(event.currentTarget),
					font = this.element.find('input[name="fontface_id"]').val(),
					method = self.data('publish'),
					a = this.element.find('input[name="a"]').val(),
					_this = this,
					form = this.element;
				var tip = '';
				this.element.find('input[name="a"]').val(method);
				form.ajaxSubmit({
					beforeSubmit:function(){
						if(font == -1){
							var tip = '请先选择字体',
								wid = 120;
							_this._tip(self ,tip , wid);
							form.find('input[name="a"]').val(a);
							return false;
						}
						form.find('#top-loading').show();
					},
					type : 'POST',
					dataType : 'json',
					success : function( data ){
						form.find('#top-loading').hide();
						var src =data[0].img;
						form.find('.item-show img').attr('src',src );
					}
				});
				this.element.find('input[name="a"]').val(a);
			},
			
			_tip : function(self , tip , wid ){
				self.myTip({
					string : tip,
					width : wid,
					dleft : 110,
					color : '#6ba4eb'
				});
			},
			
			_name : function(event){
				var self = $(event.currentTarget);
					val = self.find('a').text();
				this.element.find('input[name="character"]').val(val);
			},
			
			_blur : function(event){
				var self = $(event.currentTarget),
					val = $.trim(self.val()),
					min_num = self.data('min'),
					max_num = self.data('max'),
					wid = 150;
				if(val){
					if(val < min_num || val > max_num){
						var tip = '值必须在'+'[' + min_num + ',' + max_num +']'+'之间';
						this._tip(self , tip ,wid);
						self.val('');
					}
					if(!$.isNumeric(val )){
						var tip = '值必须为数字';
						this._tip(self , tip ,wid);
						self.val('');
					}
					
				}
			},
		});
	})($);
	$('#verifycode_form').code_form({
		preUrl : 'run.php?mid='+gMid+'&a=preview'
	});
});