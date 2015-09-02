(function ( $ ) {
	$.fn.numBox = function( option ){
		var op = $.extend({
			min : 1,
			max : 1000
		}, option);
		return this.each(function(){
			var tpl = 	'<div class="num-select-wrap m2o-flex item-input">'+
							'<span class="num-btn minus disable"></span>'+
							'<input class="num" name="goods_number"/>'+
							'<span class="num-btn plus"></span>'+
						'</div>';
			var el = $(this).html( tpl ),
				valInp = el.find('.num'),
				plusBtn = el.find('.plus'),
				minusBtn = el.find('.minus');
			var numCache = op.min;
			valInp.val( op.min );
			el
			.on('click', '.num-btn', function( event ){
				event.stopPropagation();
				var target = $(this);
				if( target.hasClass('disable') ){
					return;
				}else{
					if( target.hasClass('plus') ){	//加 
						valInp.val( parseInt(valInp.val())+1 );
					}else{		//减 
						valInp.val( parseInt(valInp.val())-1 );
					}
					numCache = valInp.val();
					$(this).trigger('update', numCache);
					target.siblings('.num-btn').removeClass('disable');
					if( valInp.val() <= op.min || valInp.val() >= op.max ){
						target.addClass('disable');
					}
				}
			})
			.on('blur', '.num', function(){
				var target = $(this),
					num = target.val();
				if( num.search(/\D/) != -1 || num<op.min || num>op.max ){
					target.val( numCache );
				}else{
					numCache = num;
					if( num == op.min ){
						minusBtn.addClass('disable');
						plusBtn.removeClass('disable');
					}else if( num == op.max ){
						plusBtn.addClass('disable');
						minusBtn.removeClass('disable');
					}else{
						plusBtn.removeClass('disable');
						minusBtn.removeClass('disable');
					}
				}
				$(this).trigger('update', target.val());
			});
		});
	};
})( window.jQuery || window.Zepto );
