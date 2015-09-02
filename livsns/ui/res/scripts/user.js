$(document).ready(function (){
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
				success: function(response){							
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
		}
		

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
				success: function(response){
				$(target).html('<a class="concern" href="javascript:void(0);" onclick="addFriends('+ id +' , '+ response +')"></a>');
				$('#deleteFriend').empty(); 
				}
		});
		}

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
				
				var father_obj =  $(target).parent();

				father_obj.attr('class' , 'follow-all');

				$(target).html('<a class="concern" href="javascript:void(0);" onclick="addFriends('+ id +' , 4)"></a>');

				$('#deleteFriend').empty(); 

				}
			});		
		}
		


//删除一条博客信息
destroy_blog= function(status_id)
{	
	$.ajax({
		url: "user.php",
		type: 'POST',
		dataType: 'html',
		timeout: TIME_OUT,
		cache: false,
		data:
		{
			//传递用户参数	
			a:"destroy_blog",
			status_id:status_id
		},
		error: function()
		{
			alert('Ajax request error');
		},
		success: function(json)
		{	
			
			//location.href = 'user.php';
			var nodeAncestor=document.getElementById("fa"+status_id).parentNode.parentNode.parentNode;			
			var ancestorParent = nodeAncestor.parentNode;
			ancestorParent.removeChild(nodeAncestor);
		}
	});
};
unfshowd = function(status_id)
{
	$("#idBox2"+status_id).remove();  
	var html = " <div id=idBox2"+status_id+" style='width:208px; height:85px; line-height:85px;  position:absolute; right:0px; top:-48px;border:1px solid #CACACA; background-image:url(res/img/unblog.jpg); text-align:center'><div style='padding-top:20px;' ><a href='javascript:void(0);' onclick='destroy_blog("+status_id+")'><img src='res/img/true.jpg'></a>&nbsp;&nbsp;<a href='javascript:void(0);' onclick='unclose("+status_id+")'><img src='res/img/false.jpg'></a></div></div>";
	$("#fa"+status_id).append(html);
	$("#idBox2"+status_id).fadeIn(5000);	
	//unfavorites(status_id);
};

});