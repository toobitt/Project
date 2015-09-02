$(function(){
	(function($){
	 	$.fn.hg_preview = function( option ){
	 		var defaultOption = {
	 				box: '',
	 				width: '',
	 				height: '',
	 				file: '',
	 				imageType : '',
	 				flag : false,
	 				type : false,
	 		};
	 		var options = $.extend(defaultOption, option );
	 		var hasBox = options['box'];
	 		if(!hasBox){
	 			return false;
	 		}else{
	 			if(options['type'] == true){
					var imageType= options['imageType'];
					if(!options['file'].type.match(imageType)){
						alert("图片格式不对");
						return false;
					}else{
						preview(options);
					}
	 			}else{
	 				preview(options);
	 			};
	 		};
 			function preview( options ){
 	    		reader=new FileReader();
 	    		reader.onload=function(e){
 	    			var imgData=e.target.result,
 	    				img = options['box'].find('img');
 	    			!img[0] && (img = $('<img style="width:'+ options['width']+'px' +';height:'+options['height']+'px' +';"/>').appendTo( options['box'] ));
 	    			img.attr('src', imgData);
 	    			if(options['flag']){
 	    				options['box'].find('.indexpic-suoyin').addClass('indexpic-suoyin-current');
					}
 	    		}; 
 	    		reader.readAsDataURL(options['file']); 
	 	    };
	 	    return this;
	 	};
	 	
	})($);
});