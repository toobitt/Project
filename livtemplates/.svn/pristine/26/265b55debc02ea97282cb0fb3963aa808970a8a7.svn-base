jQuery(function($){
		
		var MC = $('.m2o-list'),
			_this = this;
		var control = {
				init : function(){
					MC
					.on('click' , '.m2o-link' , $.proxy(this.showLink , this))
					.on('mouseleave' , '.m2o-each' , $.proxy(this.hideLink , this))
					.on('click' , '.generate-form' , $.proxy(this.generateForm , this))
					.on('click' , '.generate-greet' , $.proxy(this.generateForm2 , this))
				},
				
				showLink : function( event ){
					var self = $( event.currentTarget );
					self.find('.link-box').show();
				},
				
				hideLink : function( event ){
					var self = $( event.currentTarget );
					self.find('.link-box').hide();
				},
				
				generateForm : function( event ){
					var self = $( event.currentTarget ),
						_this = this,
						id = self.closest('.m2o-each').data('id'),
						url = './run.php?mid=' + gMid + '&a=create_form&id='+ id;
					$.globalAjax( self, function(){
						return $.getJSON( url,function( data ){
							if( data['callback'] ){
								eval( data['callback'] );
								return;
							}
							else{
								_this.myTip( self , '生成表单成功!');
							}
						});
					} );
					
				},
				//临时使用 generateForm2生成贺卡
				generateForm2 : function( event ){
					var self = $( event.currentTarget ),
						_this = this,
						id = self.closest('.m2o-each').data('id'),
						url = './run.php?mid=' + gMid + '&a=create_form2&id='+ id;
					$.globalAjax( self, function(){
						return $.getJSON( url,function( data ){
							if( data['callback'] ){
								eval( data['callback'] );
								return;
							}
							else{
								_this.myTip( self , '生成贺卡成功!');
							}
						});
					} );
					
				},
				
				myTip : function( self , tip ){
					self.myTip({
						string : tip,
						delay: 1000,
						dtop : 0,
						dleft : 40,
						color : '#1bbc9b'
					});
				},
				
		};
		control.init();
});