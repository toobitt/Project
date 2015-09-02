//$(function(){ $('#equalize').equalHeights(); });
$(document).ready(function (){

	//添加关注
	addFriends=function(id , relation){
		var target = '#add_' + id;		
		$.ajax({
			url: "follow.php",
			type: 'POST',
			dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
			data: {a: "create",
				  id: id
			},
			error: function(){
				alert('Ajax request error!');
			},
			success: function(response){
				var attention_count =  parseInt($('#liv_head_attention_count').text()) + 1;
				if(relation == 4)      	   //未知这批人是否关注了我
				{
					$(target).html('<a class="been-concern"></a>');
					$('#liv_head_attention_count').text(attention_count);	
				}

				if(relation == 3)		   //已知这批人是否关注了我
				{
					$(target).html('<p>相互关注</p>');			
				}
					
			}
			});			
	}

    //弹出取消关注提示框
	moveFollow  = function (id){

		if($('#showId').val() == 0)
		{
			$('#showId').val(id);	
		}
		else
		{
			var closeId = $('#showId').val();
			var close = '#deleteFollow_' + closeId;
			$(close).empty();
			$('#showId').val(id);			
		}		

		var target = '#deleteFollow_' + id;
		var content = '<div class="follow_div">'+	      			  
				      '<p>确定取消对该用户的关注?</p>'+
				      '<a onclick="deleteRealtion('+ id +');" class="text">确定</a>'+
				      '<a onclick="closeDelete('+ id +');" class="text">取消</a></div>';
		$(target).html(content);
		
					
	}

    //关闭弹出框
	closeDelete = function(id)
	{
		var target = '#deleteFollow_' + id;
		$(target).empty();
		$('#showId').val(0);			
	}
	
	//取消关注
	deleteRealtion = function(id){

		var target = '#delete_' + id;
		$.ajax({
			url: "follow.php",
			type: 'POST',
			dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
			data: {a: "delete",    	
				  id: id
			},
			error: function(){
				alert('Ajax request error!');
			},
			success: function(response){
				
				$(target).remove();
				
				var attention_count =  parseInt($('#liv_info_attention_count').text()) - 1;

				//$('#liv_head_attention_count').text(attention_count);
				$('#liv_info_attention_count').text(attention_count);
				//$('#liv_list_attention_count').text(attention_count);
				$('#liv_title_attention_count').text(attention_count);
			}
			});				
	}
	
	clearText = function (obj){
		
		if(obj.value == '请输入昵称')
		{
			obj.value = '';
		}
		else
		{
			//不清除
		}	
		
	}
	
	showText  = function (obj){
		
		if(obj.value != '')
		{
			
		}
		else
		{
			obj.value = '请输入昵称';
		}					
	}
});