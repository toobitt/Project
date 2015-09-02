$(function(){
	(function($){
		$.widget('hoge.ah_crtl',{
			options : {
				'channel' : true,
				'date' : true,
				'program' : true
			},
			_init : function(){
				var op = this.options;
				if( op.channel ){
					this.element.channel( op );
				}if( op.date ){
					this.element.date( op );
				}if( op.program ){
					this.element.program( op );
				}
			}
		});
	})(jQuery);
})