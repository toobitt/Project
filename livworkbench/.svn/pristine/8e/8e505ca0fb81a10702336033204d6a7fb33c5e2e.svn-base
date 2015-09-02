/*
 * 调用方法	$.log( value );
 * 查看方法	http://localhost/livsns/print/print.php?a=get
 * */


(function(){
	var log = {};
	$.extend(log, {
		baseurl : "http://" + location.hostname + "/livsns/print/print.php",		//location.hostname或'localhost'
		init : function( option ){
			if( !option ){
				log.img();
			}
			try{
				if( option instanceof Object ){
					log.form( option );
				}else if( typeof option == 'string' ){
					log.img( option );
				}
			}catch( e ){
				console.log( '数据类型错误' );
			}
		},
		
		form : function( option ){
			var formdata = new FormData(),
    			url = log.baseurl + '?a=log';
    			option = JSON.stringify( option );
    		formdata.append( 'val', option );
    		console.log( url );
    		$.ajax({
    			url : url,
				data : formdata,
				cache : true,
	        	timeout : 60000,
	        	processData : false,
                contentType : false,
	        	type : 'post',
				dataType : 'json',
				success : function( data ){
					console.log( 'Done' );
				},
				error : function(){
	        		console.warn( 'Error' );
	        	}
    		});
		},
		
		img : function( option ){
			var img = new Image();
    		img.onload = img.onerror = function(){
    			console.log( 'Done' );
    		}
    		img.src = log.baseurl + '?a=log' + ( option ? '&val=' + option : '');
		}
	})
	$.log = function( option ){
		return log['init'].apply( this, arguments );
	}

})( window.jQuery || window.Zepto );
