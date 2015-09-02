/**
 * rotate plugin
 * ok!: MSIE 6, 7, 8, Firefox 3.6, chrome 4, Safari 4, Opera 10
 * @gbook: http://byzuo.com/blog/html5-canvas-rotate
 * @demo:  http://byzuo.com/demo/jq/rotate
 *
 * @example $imgID.rotate('cw')、$imgID.rotate('ccw')
 * @p = rotate direction clockwise(cw)、anticlockwise(ccw)
 */
 
;(function($){
	$.fn.extend({
		"rotate":function(p){
			var $img = $(this);
			var n = $img.attr('step');
			if(n== null) n=0;
			if(p== 'cw'){
				(n==3)? n=0:n++;
			}else if(p== 'ccw'){
				(n==0)? n=3:n--;
			}
			$img.attr('step',n);
			//MSIE
			if($.browser.msie) {
				$img.css('filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ n +')');
				//MSIE 8.0
				if($.browser.version == 8.0){
					if(!$img.parent('div').hasClass('wrapImg')){
						$img.wrap('<div class="wrapImg"></div>');	
					}
					$img.parent('div.wrapImg').height($img.height());
				}
			//DOM
			}else{
				if(!$img.siblings('canvas').hasClass('imgCanvas')){
					$img.css({'position':'absolute','visibility':'hidden'})
						.after('<canvas class="imgCanvas"></canvas>');
				}
				var c = $img.siblings('canvas.imgCanvas')[0], img = $img[0];
				var canvasContext = c.getContext('2d');
				switch(n) {
					default :
					case 0 :
						c.setAttribute('width', img.width);
						c.setAttribute('height', img.height);
						canvasContext.rotate(0 * Math.PI / 180);
						canvasContext.drawImage(img, 0, 0);
						break;
					case 1 :
						c.setAttribute('width', img.height);
						c.setAttribute('height', img.width);
						canvasContext.rotate(90 * Math.PI / 180);
						canvasContext.drawImage(img, 0, -img.height);
						break;
					case 2 :
						c.setAttribute('width', img.width);
						c.setAttribute('height', img.height);
						canvasContext.rotate(180 * Math.PI / 180);
						canvasContext.drawImage(img, -img.width, -img.height);
						break;
					case 3 :
						c.setAttribute('width', img.height);
						c.setAttribute('height', img.width);
						canvasContext.rotate(270 * Math.PI / 180);
						canvasContext.drawImage(img, -img.width, 0);
						break;
				};
			}
		}
	});
})(jQuery);

