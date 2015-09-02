$(function(){
	(function(){
		$('.char-count').charCount({maxLen : 50});
	})();
	
	var control = {
			init : function( el ){
				this.el = el;
				this.initPoP();
				this.el
				.on('click' , '.select-app li' , $.proxy(this.toggle , this))
				.on('click' , '.sel-link-module .overflow' , $.proxy(this.showadd , this))
				.on('click' , '.sel-con' , $.proxy(this.addPop , this))
				this.initinfo();
			},
			
			initPoP : function(){
				var _this = this;
				$.pop({
					title : '添加内容',
					className : 'pubLib-pop-box',
					widget : 'pubLib',
					clickCall : function(event , info ,widget){
						_this._clickCall( info, widget );
					}
				});
	            this.datasource = $('.pubLib-pop-box');
	            this.datasource.pubLib('hide');
			},
			
			initinfo : function(){
				var id = this.$('input[name="app_push_id"]').val();
				this.toggleinfo( id );
			},
			
			toggle : function( event ){
				var self = $( event.currentTarget ),
					target = self.find('.overflow'),
					id = target.attr('attrid'),
					item = self.closest('.down_list'),
					url = 'run.php?mid='+ gMid +'&a=get_platform_type';
				var _this = this;
				if( id == '-1'){
					this.toggleinfo( id );
				}else{
					$.globalAjax(item, function(){
				        return $.getJSON(url,{app_id : id},function(json){
				        	_this.toggleinfo( json[0].platform_type  );
				        });
				    });
				}
			},
			
			toggleinfo : function( id ){
				switch( id ){
					case '-1' : {
						this.$('.display').hide();
						this.$('input[name="title"]').attr('required' , true);
						break;
					}
					case '1' : {
						this.$('.platform').show()
										   .find('.ios').show()
						   				   .end().find('.android').show()
						   				   .end().find('.winphone').show();
						this.$('.send_time').show();
						this.$('.expire_time').show();
						this.$('.link-module').show();
						this.$('input[name="title"]').attr('required' , false);
						break;
					}
					case '2' : {
						this.$('.platform').show()
										   .find('.ios').show()
										   .end().find('.android').show()
										   .end().find('.winphone').show();
						this.$('.send_time').hide();
						this.$('.expire_time').show();
						this.$('.link-module').show();
						this.$('input[name="title"]').attr('required' , false);
						break;
					}
					case '3' : {
						this.$('.platform').show()
										   .find('.ios').show()
										   .end().find('.android').show()
										   .end().find('.winphone').hide();
						this.$('.send_time').show();
						this.$('.expire_time').show();
						this.$('.link-module').show();
						this.$('input[name="title"]').attr('required' , true);
						break;
					}
				}
			},
			
			showadd : function( event ){
				var self = $( event.currentTarget ),
					id = self.attr('attrid');
				console.log( id );
			},
			
			addPop : function( event ){
				this.self = $( event.currentTarget );
				this.showPop();
			},
			
			showPop : function(){
				this.datasource.pubLib('show', {
					top : 0 + 'px',
					left : 440 + 'px',
					'margin-top' : 0,
				});
			},
			
			_clickCall : function( info ,widget ){
				this.self.closest('.link-modules').find('input[name="content_id"]').val( info[0].id );
				widget.element.pubLib('hide');
			},
			
			$: function(s) {
				return this.el.find(s);
			},
			
	}
	control.init( $('.ad_form') );
});