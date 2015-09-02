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
				if(parseInt(obj.is))
				{
					if($("#collect_"+obj.sid).html())
        			{
	        			li = '<a class="plays" href="'+SNS_VIDEO+'station_play.php?sta_id='+obj.sid+'"></a><a class="gz_del" href="javascript:void(0);" onclick="del_concern('+ obj.cid +','+ obj.sid +','+ id +',1);"></a>';
	        			$("#collect_"+obj.sid).html(li);
        			}
				}
				var target = '#add_' + id;
				if(relation == 3)
				{
					$(target).html('<a class="mul-concern"></a>');				
				}

				if(relation == 4)
				{
					$(target).html('<a class="been-concern"></a>');	
				}
				
				$('#deleteFriend').html('<a class="cancel-concern" href="javascript:void(0);" onclick="delFriend('+ id +')"></a>');														
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
				$(target).html('<a class="concern" href="javascript:void(0);" onclick="addFriends('+ id +' , '+ obj.relation +')"></a>');
				$('#deleteFriend').empty(); 
				if($("#collect_" + obj.cid).html())
        		{
	        		li = '<a class="plays" href="'+SNS_VIDEO+'station_play.php?sta_id='+ obj.cid +'"></a><a class="gz_get" href="javascript:void(0);" onclick="add_concern('+ obj.cid +',1,'+ id +');"></a>';
	        		$("#collect_" + obj.cid).html(li);
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
		$("#pic_ct").attr('src',$("#bs_"+id).html());
	}
});