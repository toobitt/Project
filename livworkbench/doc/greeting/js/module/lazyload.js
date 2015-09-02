(function( $ ){
	$.fn.lazyload = function( options ){
		var defaults = {
			errorImgUrl : 'images/load.gif',
			scrollTarget : $( window ),
			classname : 'lazy',
			sensitivity : 50
		}
		
		var op = $.extend(defaults, options);
		
		return this.each(function(){
			var $this = $(this);
			 function imgEach(){
			 	$this.find('img.' + op.classname).filter(function(){
			 		return $(this).data('original')
			 	}).each(function( ii, vv ){
			 		if( !vv ){
			 			return;
			 		}
			 		var self = $( vv );
			 		if( !compare( self ) ){
			 			return;
			 		}
			 		changeImg( self, ii );
			 	});
			 }
			 
			 function compare( dom ){
			 	var pageY = window.pageYOffset,
			 		innerHeight = window.innerHeight,
			 		offsetTop = dom.offset().top;
			 	var scrollTop = op.scrollTarget.scrollTop();
			 	if( offsetTop >= pageY && pageY + innerHeight + scrollTop> offsetTop + op.sensitivity ){
			 		return true;
			 	}
			 	return false;
			 }
			 
			 function changeImg( self, ii ){
			 	var src = self.data('original');
			 	if( !src ){
			 		return;
			 	}
			 	self.attr('src', src);
			 	self.on({
			 		'load' : function(){
			 			self.removeClass( op.classname ).removeAttr('data-original');	
			 		},
			 		'error' : function(){
			 			if ( !op.errorImgUrl ) {
							return
						}
						this.src = op.errorImgUrl;
						self.removeClass( op.classname ).removeAttr('data-original');
			 		}
			 	});
			 }
			 imgEach();
			 op.scrollTarget.on('scroll', function(){
			 	imgEach();
			});
		});
	};
})( window.jQuery || window.Zepto );
