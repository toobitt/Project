$(document).ready(function (){

	setBindInfo = function ()
	{
		var showNotice = '<span style="background:white; width:100px;height:50px;padding:10px;border:5px solid silver;text-align:center;color:green;">设置成功！</span>';				
		$.ajax({
			url: "bind.php",
			type: 'POST',
			dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
			data: {
			a:'bind',
			set_bindinfo: 1,
		web_type: $("#web_type").val(),
 		   state: $("input[name='state']:checked").val()	  				 	
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