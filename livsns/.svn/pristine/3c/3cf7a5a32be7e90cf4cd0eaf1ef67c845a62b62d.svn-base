//$(function(){ $('#equalize').equalHeights(); });
$(document).ready(function (){
	addFriends=function(id , relation){
		var target = '#add_' + id;		
		$.ajax({
			url: "fans.php",
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
				var attention_count =  parseInt($('#liv_info_attention_count').text()) + 1;
				if(relation == 4)          //未知这批人是否关注了我
				{
					$(target).html('<a class="been-concern"></a>');						
				}

				if(relation == 3)		   //已知这批人是否关注了我
				{
					$(target).html('<a class="relation"></a>');
					$('#liv_info_attention_count').text(attention_count);
					//$('#liv_list_attention_count').text(attention_count);								
				}
				$('#liv_head_attention_count').text(attention_count);					
			}
			});			
	}

	deleteRealtion = function(id){

		var target = '#delete_' + id;
		var hidden = '#deleteMove_' + id;
		var is_block = '#addBlock_' + id;

		if($(is_block).attr('checked') == true)
		{
			var add_block = 1;
		}
		else
		{
			var add_block = 0;
		}		
		
		$.ajax({
			url: "fans.php",
			type: 'POST',
			dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
			data: {a: "move", 	
				  id: id,
			is_block: add_block
			},
			error: function(){
				alert('Ajax request error');
			},
			success: function(response){
				$(target).remove();
				var followers_count =  parseInt($('#liv_info_followers_count').text()) - 1;
								
				//$('#liv_head_followers_count').text(followers_count);
				$('#liv_info_followers_count').text(followers_count);
				//$('#liv_list_followers_count').text(followers_count);
				$('#liv_title_followers_count').text(followers_count);			
			}
			});			
	}

	moveFans = function (id){

		if($('#showId').val() == 0)
		{
			$('#showId').val(id);	
		}
		else
		{
			var closeId = $('#showId').val();
			var close = '#deleteMove_' + closeId;
			$(close).empty();
			$('#showId').val(id);			
		}
		
		var target = '#deleteMove_' + id;
		var content = '<div class="follow_div">'+
					  '<p>移除之后将取消对你的关注</p>'+
	      			  '<span><input id="addBlock_'+ id +'" type="checkbox" name="addBlock" />同时将此用户加入黑名单</span>'+
                      '<p>确定移除该用户?</p>'+
                      '<a onclick="deleteRealtion('+ id +');" class="text">确定</a>'+
                      '<a onclick="closeDelete('+ id +');" class="text">取消</a></div>';

		$(target).html(content);		
	}

	closeDelete = function (id){
		var target = '#deleteMove_' + id;
		$(target).empty();
		$('#showId').val(0);		
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
