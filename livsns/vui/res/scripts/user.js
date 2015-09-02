$(document).ready(function(){	

	//添加关注
	addFriends = function(id , relation){
		$.ajax({
			url: "user.php",
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
			success: function(json){	
				var obj = new Function("return" + json)();
				if(parseInt(obj.id))
				{
					if($("#collect_user").html())
        			{
	        			//li = '<a class="gz_back" href="' + SNS_UCENTER + 'user.php?user_id='+ id +'"></a><a class="gz_del" href="javascript:void(0);" onclick="delFriend(' + id + ');"></a>';
	        			li = '<a class="gz_back1" href="' + SNS_UCENTER + 'user.php?user_id='+ id +'"></a>  <a class="gz_del" href="javascript:void(0);" onclick="delFriend(' + id + ');"></a>';
	        			$("#collect_user").html(li);
        			}
				}
				
		//		$('#deleteFriend').html('<a class="cancel-concern" href="javascript:void(0);" onclick="delFriend('+ id +')"></a>');														
			}
			});
	};
	

	//取消关注
	delFriend = function (id){
		var target = '#add_' + id;
		$.ajax({
			url: "user.php",
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
			success: function(json){
				var obj = new Function("return" + json)();
				if($("#collect_user").html())
        		{
	        		//li = '<a class="gz_back" href="' + SNS_UCENTER + 'user.php?user_id='+ id +'"></a><a class="gz_get" href="javascript:void(0);" onclick="addFriends('+ id +' , '+ obj.relation +');"></a>';
	        		li = '<a class="gz_back1" href="' + SNS_UCENTER + 'user.php?user_id='+ id +'"></a>  <a class="gz_get" href="javascript:void(0);" onclick="addFriends('+ id +' , '+ obj.relation +');"></a>';
					$("#collect_user").html(li);
        		}
			}
	});
	};

	//解除黑名单
	deleteBlock = function (id)
	{
		var target = '#add_' + id;

		$.ajax({
			url: "user.php",
			type: 'POST',
			dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
			data: {a: "remove",
				  id: id
			},
			error: function(){
				alert('Ajax request error');
			},
			success: function(response){
			alert(response);
			var father_obj =  $(target).parent();

			father_obj.attr('class' , 'follow-all');

			$(target).html('<a class="concern" href="javascript:void(0);" onclick="addFriends('+ id +' , 4)"></a>');

			$('#deleteFriend').empty(); 

			}
		});		
	};
	
	chang_img = function(id,sta_id,user_id){
		$("#pic_href").attr("href",$("#vs_"+id).attr("href"));
		$("#pic_ct").attr("src",$("#bs_"+id).html());
	}
});