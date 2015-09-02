var share = (function(){
	var me = {};
	var platform = navigator.userAgent.toLowerCase(),
		isWechat = (/micromessenger/gi).test( platform ),
		isIOS = (/iphone|ipod|ipad/gi).test(platform),
		isAndroid = (/android/gi).test( platform );
	var box = $('.common-order-info');
	var init = function(){
		me.bind();
	}
	
	me.bind = function(){
		box.on('click', '.share-btn', function(){
			var title = box.find('.name').html(),
				website = location.href,
				img = box.find('.pic')[0].src;
			me.share(title, website, img);
		});
	};
	
	me.share = function(i, o, n){
		if( device.desktop() ){
			console.log( '请到移动设备上测试' );
			return; 
		}
		if( isWechat ){
			var t = $("#tip-share");
			if (t.length > 0) {
				t.show()
			} else {
				t = $('<div id="tip-share" class="tip-share-wechat"><img src="images/tip-share-wechat.png" width="100%" alt="分享提示" /></div>');
				t.appendTo( box.closest('.page') );
				t.click(function() {
					$(this).hide()
				});
			}
		}else{
			if ( isIOS ) {
				var a = "#func=sharePlatsAction&content=" + i + "&content_url=" + o;
				if ( n )
					a += "&pic=" + n;
				window.location.hash = "";
				window.location.href = a;
				a = '';
			} else if (isAndroid) {
				window.android.sharePlatsAction(i, o, n);
			}
		}
	};
	return {
		init : init
	}
})();
share.init();