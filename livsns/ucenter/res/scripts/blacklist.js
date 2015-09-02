$(function(){ $('#equalize').equalHeights(); });
$(document).ready(function (){
	moveBlocks = function (id)
	{
		if($('#showId').val() == 0)
		{
			$('#showId').val(id);	
		}
		else
		{
			var closeId = $('#showId').val();
			var close = '#showMove_' + closeId;
			$(close).empty();
			$('#showId').val(id);			
		}
		
		var target = '#showMove_' + id;
		var content = '<div class="follow_div">'+	      			  
                      '<p>确定移除黑名单?</p>'+
                      '<a class="text" onclick="deleteBlocks('+ id +');">确定</a>'+
                      '<a class="text" onclick="closeDelete('+ id +');">取消</a>';

		$(target).html(content);
	}

	deleteBlocks = function (id)
	{		
		var target = "#deleteBlock_" + id;
		$.ajax({
			url: "blacklist.php",
			type: 'POST',
			dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
			data: {a: "destroy", 	
				  id: id
			},
			error: function(){
				alert('Ajax request error');
			},
			success: function(response){

				$(target).remove();
			}
			});
	}

	closeDelete = function(id)
	{
		var target = '#showMove_' + id;
		$(target).empty();
		$('#showId').val(0);	
	}
	
});