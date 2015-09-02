$(document).ready(function (){
	
	addFriends=function(id , relation){
				
		var target = '#add_' + id;
		$.ajax({
			url: "n.php",
			type: 'POST',
			dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
			data: {a: "create",
				  id: id
			},
			error: function(){
				alert('Ajax request error');
			},
			success: function(response){
				
				if(relation == 4)          //未知这批人是否关注了我
				{
					$(target).html('<a class="been-concern"></a>');						
				}				
			}
		});			
	}	
});