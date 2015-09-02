/*调用接口方式
 * getUrl( 'detail' )
 * */

(function( w ) {
	var base = './php/';
	var baseOptions = {
		'index' : 'index.php',
		'detail' : 'shopping-detail.html'
	}
	w.getUrl = function( tpl ){
		return base + baseOptions[ tpl ];
	};
	w.getAjax = function( url, param, callback ){
		$.ajax({
			type: "get",
            url: url,
            dataType: "json",
            data : param,
            timeout : 60000,
            success: function(json){
            	callback( json );
            },
        	error : function(){
        		alert('接口访问错误，请稍候再试');
        	}
        });
	}
})(window); 
