;$(function(){
	(function($){
		$.hg_scroll = function(option){
			var defaultOption = {
	 				el : 'wrapper',		//滚动面板 id
	 				loadmore : false,	//是否有加载更多
	 				loadCallback : $.noop
	 		};
	 		var op = $.extend( defaultOption,option );
	 		var scrollOptions = {
	 				useTransform: false,
	 	            scrollbarClass: 'myScrollbar',
	 	            onBeforeScrollStart: function (e) {
	 	                var target = e.target;
	 	                while (target.nodeType != 1) target = target.parentNode;

	 	                if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA')
	 	                    e.preventDefault();
	 	            }
	 		};
	 		if( op.loadmore ){
	 			var pullUpEl = document.getElementById('pullUp');
	 			$("#pullUp").bind("click", function () {
	 		        pullUpEl.className = 'loading';
	 		        pullUpEl.querySelector('.pullUpIcon').style.display = "inline-block";
	 		        pullUpEl.querySelector('.pullUpLabel').innerHTML = '正在加载数据，请稍候...';
	 		        op.loadCallback();
	 		    });
	 			scrollOptions.onRefresh = function(){
 	            	if (pullUpEl.className.match('loading')) {
 	            		pullUpEl.className = '';
 	            		pullUpEl.querySelector('.pullUpIcon').style.display = "none";
 	            		pullUpEl.querySelector('.pullUpLabel').innerHTML = '点击加载更多';
 	            	}
 	            }
	 		}
	 		$.myScroll = new iScroll('wrapper',scrollOptions);
	 		//tip:
	 		//发起请求获取数据，插入dom之后，记得 $.myScroll.refresh();
		};
		
 		//禁用系统的触摸拖动事件
 		document.addEventListener('touchmove', function (e) {
 			e.preventDefault();
 		}, false);
	})($)
});