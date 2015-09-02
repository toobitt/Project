$(document).ready(function (){

	setPrivacy = function ()
	{
		var showNotice = '<span style="background:white; width:100px;height:50px;padding:10px;border:5px solid silver;text-align:center;color:green;">设置成功！</span>';				
		$.ajax({
			url: "userprivacy.php",
			type: 'POST',
			dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
			data: {a: "set",
 visit_user_info: $("input[name='visit_user_info']:checked").val(),
		  follow: $("input[name='follow']:checked").val(),
		 comment: $("input[name='comment']:checked").val(),
search_true_name: $("input[name='search_true_name']:checked").val()	  				 	
			},
			error: function(){
				alert('Ajax request error');
			},
			success: function(response){

				$('#show_notice').html(showNotice);
			
				$('#show_notice').animate({opacity:'show'},2000)
				 				 .animate({opacity:'toggle'},2000);				
			}
			});
	};		
});