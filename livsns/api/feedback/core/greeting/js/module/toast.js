(function(){
	var pluginName = 'h5_toast';
	
	function Toast( options ){
		this.op = $.extend({
			content : '接口访问错误',
			target : '#popToast',
			appendTo : 'body',
			insertBefore : '',
			delay : 1300,
			cssInited : false
		}, options);
		this.cssInit();
	}
	$.extend(Toast.prototype , {
		show : function( msg ){
			var self = this;
			if( !self.$toast || !self.$toast.length ){
				self.$toast = $('<div class="popBox fadeOut" id="' + self.op.target.substr(1) + '"><span class="inner"></span></div>');
				if( self.op.insertBefore ){
					self.$toast.insertBefore( self.op.insertBefore );
				}else if( self.op.appendTo ){
					self.$toast.appendTo( self.op.appendTo );
				}

			}
			self.$toast.find('.inner').html( msg || self.op.content );
			self.$toast.removeClass('fadeOut').addClass('fadeIn');
			self.close();
			return self;
		},
		
		close : function(){
			var self = this;
			setTimeout(function(){
	 			!!self.$toast && self.$toast.removeClass('fadeIn').addClass('fadeOut');
	 			setTimeout(function(){
	 				!!self.$toast && self.$toast.remove();
	 				self.$toast = null;
	 			}, 300);
	 		}, self.op.delay );
			return this;
		},
		
		cssInit : function(){
			if( this.op.cssInited ){
				return;
			}
			this.op.cssInited = true;
			$('<style />').attr('style', 'text/css').appendTo('head').html( 
				'.popBox{position:absolute; top:30%;left:0; height : 46px; width : 100%; z-index: 11001; text-align : center; transition:opacity 0.3s; }' +
				'.inner{display:inline-block; height:46px; line-height:46px; margin:0 10px; padding:0 10px; background-color: rgba(0, 0, 0, 0.8);border-radius:8px; overflow:hidden; color : #fff;}' +
				'.fadeOut{opacity:0!important; z-index:-1!important;}' +
				'.fadeIn{opacity:1!important; }'
			);
		}
	});

	$[pluginName] = function( option ) {
		return new Toast( option );
	};
})();