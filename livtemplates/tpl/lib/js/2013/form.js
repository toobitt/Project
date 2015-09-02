$(function($){
	/*switch radio*/
	function setStatus( obj, status ){
		obj.find('input').attr('checked',false);
		if( status ){
			obj.find('input:first').attr('checked',true);
		}else{
			obj.find('input:last').attr('checked',true);
		}
	}
	(function($){
		$('.common-switch').each(function(){
			var val = 0,
			    status = false;
			$(this).hasClass( 'common-switch-on' ) ? val = 100 : val = 0;
			var obj = $(this).closest( '.m2o-item' );
			$(this).hg_switch({
				'value' : val,
				'callback':function( event,val ){
					val >= 50 ? status = true : status = false;
					setStatus( obj, status );
				}
			});
		});
	})($);
});