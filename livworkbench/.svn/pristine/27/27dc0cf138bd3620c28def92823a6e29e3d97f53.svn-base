;(function($){
	var defaultOptions = {
			baseUrl : 'http://app.wifiwx.com/bus/api.php',
	};
	function Bus( el, options ){
		var _this = this;
		this.op = $.extend( {}, options, defaultOptions );
		this.el = el;
		this.init();
	}
	$.extend( Bus.prototype, {
		init : function(){
			//this.showLoading();
			this.nearbyRoute();
		},
		nearbyRoute : function(){
			this.getCoord();
		},
		/** 获取当前坐标 */
		getCoord : function(){
			console.log("获取当前坐标...");
//			this.map = new Widget.CMap.Map("mapDiv","BAIDU");
//			Widget.CMap.Location.requestMyLocation(this.map);
//			Widget.CMap.Location.onMyLocationComplete = function (point) {
//				if(point==null || point==undefined) {
//	                alert("定位失败");
//	                return;
//				}
//	            alert("point-lat:" + point.lat + "  lng:" + point.lng);
//	        }
			var data = {
					adow : '3',
					a : 'get_segment',
					rad : '1000.000000',
					lng : '120.277359',
					lat : '31.561094',
					type : '1'
			};
			var url = 'http://app.wifiwx.com/bus/api.php?key=&type=0&version=0.1&adow=3&rad=1000.000000&lng=120.277359&lat=31.561094&signature=5147a85bc45f20fd42e2836dee9d3e973e95f11a&nonce=D3FCA8C4-107B-4A27-94EE-C55794AA9085&a=get_segment';
			this.ajax(url, {}, function(json){
				alert(1);
				console.log(data);
			});
			
		},
		nearbyStation : function(){
			var url = this.interface_tool( 'nearbyStation' );
			var data = {a:'station'};
			this.ajax(url,data);
		},
		ajax : function( url, param, callback ){		//ajax工具函数
			var _this = this;
//					$.getJSON( url, param, function( data ){
//						if( $.isFunction( callback ) ){
//							callback( data );
//						}
//					});
					$.ajax({
						type: "get",
			            url: url,
			            data : param,
			            dataType: "jsonp",
			            jsonp: "callback",
			            success: function(json){
			            	$('body').append('aaaaaaaaaaaaa');
			            }
			        });
		},
		showLoading : function( str ){
			if( this.loading ){
				$('#bae_progress_box').show();
				return;
			}
			var str = str || '加载数据中...';
			this.loading = $.bae_progressbar({
				message:"<p>"+ str +"</p>",
				modal:true,
				canCancel : true
			});
		},
		closeLoading : function(){
			this.loading.close();
		},
	});
	
	window.Bus = Bus;
	
})($);
$(function(){
	var busObj = new Bus( $('body') );
});