$(function(){
	$.doajax = function( target , url , param ,  cb ){
		if( target ){
			utils.spinner.show( target );
		}else{
			utils.spinner.show();
		}
		$.ajax({
			type: "post",
            url: url,
            data : param ,
            success: function( data ){
            	utils.spinner.close();
				if( data ){
					if( data.error ){
						utils.spinner.close();
						$.tip('暂无数据!');
						// $.goBack();
		    			return;
					}
					cb && cb( data );
				}else{
					utils.spinner.close();
					$.tip('暂无数据!');
					// $.goBack();
	    			return;
				}
            },
        	error : function(){
        		utils.spinner.close();
        		$.tip('暂无数据!');
        		// $.goBack();
    			return;
        	}

		})
	}

	// $.goBack = function(){
		// setTimeout(function(){
        	// if(isAndroid ){
				// window.android.goBack();
			// }else if( isIOS ){
				// window.location.hash = "";
				// window.location.hash = "#func=goBack"; 
			// }
        // },'1000');
	// }
	
	$.tip = function( target , tip ){
		$.hg_toast({
			appendTo : (target ? target : 'body'),
			delay : 1500
		}).show( tip );
	}
	
	$.getHrefinfo = function(){
		var location = document.location.search,
			beginIndex = location.indexOf('?') + 1,
			arr = location.substring( beginIndex ).split('&');
		var config = {};
		for(var i=0;i<arr.length;i++){
			var ar = arr[i].split('=');

			if(ar[0] != ''){
				config[ar[0]] = ar[1];
			}
		}
		return config;
	}
	
	$.getTitle = function( sName ){
		var listTitle;
		$.each( $.datalist , function(k , v){
			if( v.serviceName == sName ){
				listTitle = v.title;
			}
		});
		return listTitle;
   	}
});
