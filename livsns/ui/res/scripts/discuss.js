$(document).ready(function (){
			
	disreplyStatus = function(id, uname){
		$("#status_id").val(id);
		$("#status").val('回复@' + uname + ' ');
		cursor("status",$("#status").val().length,$("#status").val().length);
		$("#status").focus();
	};

	dispubUserStatus = function(){
		
	    if ($("#status").val() != "") 
	    {
			var prefix = $("#keywords").val();
									
	        $.ajax({
	            url: "dispose.php",
	            type: 'POST',
	            dataType: 'html',
	   			timeout: TIME_OUT,
	   			cache: false,
	            data: {status: prefix + $("#status").val(),
		        	a: "update",
		        	status_id:$("#status_id").val(),
		        	source:$("#source").val()
		        	},
	            error: function() {
	                //alert('Ajax request error');
						$("#status").val('');
						$("#status_id").val(0);
	            },
	            success: function(json) {
	            		            		            	
	            	var obj = new Function("return" + json)();

					if(obj=='false')
					{
						$('#login_area').css('display' , 'inline-block');
					}
					else
					{
						//加载数据
						$("#status").val('');
						$("#status_id").val(0);
						get_newest_speak();
					}
	            }
	        });	
		 }
	    else
		{	
	    	$("#status").css('background-color','rgb(255, 200, 200)')
	    	setTimeout("$('#status').css('background-color','rgb(255, 255, 255)')",800);    
			
		}
	}
});