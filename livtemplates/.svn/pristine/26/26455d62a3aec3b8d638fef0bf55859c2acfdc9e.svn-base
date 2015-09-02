$(function(){
	var control = {
			init : function( el ){
				this.el = el;
				this.initinfo();
				this.el
				.on('click' , '.overflow' , $.proxy(this.toggle , this))
			},
			
			$: function(s) {
				return this.el.find(s);
			},
			
			initinfo : function(){
				var id = this.$('input[name="platfrom_type"]').val();
				this.toggleinfo( id );
			},
			
			toggle : function( event ){
				var self = $( event.currentTarget ),
					id = self.attr('attrid');
				this.toggleinfo( id );
				
			},
			
			toggleinfo : function( id ){
				switch( id ){
					case '-1' : {
						this.$('.list-info').hide();
						break;
					}
					case '1' : {
						this.$('.appid').show();
						this.$('.appkey').show();
						this.$('.secretkey').show();
						this.$('.channel').hide();
						break;
					}
					case '2' : {
						this.$('.appid').hide();
						this.$('.appkey').show();
						this.$('.secretkey').show();
						this.$('.channel').hide();
						break;
					}
					case '3' : {
						this.$('.list-info').show();
						break;
					}
				}
			},
	}
	control.init( $('.ad_form') );
});