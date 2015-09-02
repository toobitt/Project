jQuery(function($){
		var MC = $('.m2o-form'),
			_this = this;
		var control = {
				init : function(){
					MC
					.on('click' , '.indexpic' , $.proxy(this.indexpic , this))
					.on('change' , 'input[name="indexpic"]' , $.proxy(this.showIndexpic , this))
					.on('click' , '.tel-item em' , $.proxy(this.addTel , this))
				},
				
				indexpic : function(){
					MC.find('input[name="indexpic"]').trigger('click');
				},
				
				showIndexpic : function( event ){
					var file = event.currentTarget.files[0],
	 					type = file.type,
	 					reader = new FileReader();
	 				var self = $(event.currentTarget),
	 					item = MC.find('.indexpic'),
	 					img = item.find('img');
	 				var matchType = /image.*/;
	 				if(!type.match(matchType)){
	 					this.myTip( item , '请选择图片');
	 					return false;
	 				}
	 				reader.readAsDataURL(file);
	 				reader.onload =  function(e){
	 					var result = e.target.result;
	 					!img[0] && (img = $('<img style="width: 176px; height: 176px;"/>').appendTo( item ));
	 					img.attr('src',result);
	 					item.find('.indexpic-suoyin').addClass('indexpic-suoyin-current');
	 				};
				},
				
				addTel : function( event ){
					var self = $(event.currentTarget),
						type = self.data('type'),
						obj = self.closest('li'),
						parent = self.closest('ul');
					if(type == 'add'){
						$('#add-tel-tpl').tmpl(null).appendTo( parent );
						self.data('type', 'del').addClass('del').removeClass('add')
							.attr('title', '删除电话');
					}else{
						var val = obj.find('input').val();
						if(val){
							jConfirm('你确定要删除该条记录', '删除提醒', function( result ){
								if(result){
									obj.remove();
								}
							}).position(self);
						}else{
							obj.remove();
						}
					}
				},
				
				myTip : function(self , tip ){
	                self.myTip({
	                    string : tip,
	                    delay: 1000,
	                    width : 120,
	                    dleft : 140,
	                });
	            },
		};
		control.init();
});