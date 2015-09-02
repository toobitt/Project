$(function(){
	$('.button-modify').on('click', function( event ){
		var $this = $(this);
		if( $this.hasClass('button-disable') ){
			return; 
		}
 		var formBtn = $('.form-btn');
 		formBtn.removeClass('hide');
 		$this.addClass('button-disabled');
 		$('.content-address').find('span').attr('contenteditable', true);
 	});
 	function editAddress(){
 		var _this = this, is_prevent = false;
 		this.listblock = $('.content-address');
 		this.tips = this.append();
 		this.init();
 		$('.button-submit').click(function( event ){
	 		var childbox = _this.listblock.find('p:not(.content-btn)').children();
	 		var data = [];
	 		childbox.each(function(index){
	 			event.preventDefault();
	 			var $this = $(this);
	 			var tips = _this.limitSwitch( $this, 'html' );
	 			if( tips ){
	 				_this.showtips( tips );
	 				is_prevent = true;
	 				return false;
	 			}
	 			is_prevent = false
	 			var label=$this.attr("id").substr(2), info = {};
	 			if( label == 'province' || label == 'city' || label == 'region'){
	 				info[label] = $this.attr('_val');
	 			}else{
	 				info[label] = $this.html();
	 			}
	 			data.push(info)
	 		});
	 		if( is_prevent ){
	 			return false;
	 		}
	 		if( data.length ){
	 			_this.ajaxUrl( data );
	 		}
	 	});
 	}
 	editAddress.prototype = new addAddress();
 	editAddress.prototype.init = function(){
 		$('.content-address').address({
 			pop : true
 		});
 	};
 	new editAddress();
});
