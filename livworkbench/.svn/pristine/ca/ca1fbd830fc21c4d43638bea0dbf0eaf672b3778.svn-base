$(function(){
	var orderForm = (function(){
		var form = $('.order-form'),
			subBtn = $('.order-submit-btn');
		var init = function(){
			$('.num-select-box').numBox();
			bind();
		};
		var bind = function(){
			form.on('blur', '.item-input input', function(){
				var inputs = form.find('.item-input input'),
					inputLen = inputs.length;
				var canSubmit = false,
					hasValInputLen = 0;
				for(var i=0;i<inputLen;i++){
					if( inputs.eq(i).val().trim() ){
						hasValInputLen++;
					}
				}
				if( inputLen == hasValInputLen ){
					canSubmit = true;
					subBtn.removeClass('btn-gray').addClass('btn-blue').removeAttr('disabled');
				}else{
					canSubmit = false;
					subBtn.removeClass('btn-blue').addClass('btn-gray').attr('disabled','disabled');
				}
			});
			subBtn.on('click', function(){
				var mobileInp = form.find('#M_phone');
				var reg = /^1\d{10}$/;
				if( !reg.test(mobileInp.val().trim()) ){
					showtips('手机号格式不正确，请重新填写', 2500);
					return false;
				}
			});
		};
		var showtips = function( msg, delay ){
	 		var tipDom = append();
	 		tipDom.removeClass('fadeOut').addClass('fadeIn').html( msg );
	 		tipDom.css('margin-left', '-'+(tipDom.width()/2)+'px');
	 		var setTime = setTimeout(function(){
	 			tipDom.removeClass('fadeIn').addClass('fadeOut');
	 		}, delay||800);
	 	};
		var append = function(){
	 		$('.popDiv').remove();
	 		return $('<div class="popDiv fadeOut"/>').appendTo( form.closest('.pages') ).css({
	 			position : 'absolute',
	 			left : '50%',
	 			top : '50%',
	 			height : '46px',
	 			color : '#fff',
	 			padding : '0 20px',
	 			'border-radius' : '3px',
	 			'z-index' : 999,
	 			'line-height' : '46px',
	 			'background-color':'rgba(51, 51, 51, 0.8)',
	 			'transition' : 'opacity 0.3s'
	 		});
	 	};
		return {init : init}
	})();
	orderForm.init();
});