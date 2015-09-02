$(document).ready(function (){
//收藏点滴信息
favorites = function(status_id,uid)
{	
	if(!parseInt(uid))
	{
		location.href = "login.php";
	}
	$.ajax({
		url: "favoritesajax.php",
		type: 'POST',
		dataType: 'html',
		timeout: TIME_OUT,
		cache: false,
		data:
		{
			//传递用户参数
			a:"updatefavorites",
			statusid:status_id
		},
		error: function()
		{
			alert('Ajax request error');
		},
		success: function(json)
		{	

			if(json)
			{
				var obj = new Function("return" + json)();
				//判断用户是否登录
				if(obj.ErrorCode =='USENAME_NOLOGIN')
				{
					location.href = 'login.php';
				}
				else
				{
					fshow(status_id);
				}	
			}
			else
			{
				alert("没有收藏成功！");
			}
			
		}
	});
}
fshow = function(status_id)
{
	$("#idBox2"+status_id).remove();  
	var html = " <div id=idBox2"+status_id+" style='width:203px; height:46px; line-height:50px;  position:absolute; left:0px; top:-50px;border:1px solid #CACACA; background-image:url(res/img/fa.jpg); text-align:center'></div>";
	$("#fa"+status_id).append(html);
	$("#idBox2"+status_id).fadeIn(5000);	
	$("#idBox2"+status_id).fadeOut(3000); 
	$("#fal"+status_id).replaceWith("已收藏");
}

//取消点滴收藏
unfavorites = function(status_id)
{	

	$.ajax({
		url: "favoritesajax.php",
		type: 'POST',
		dataType: 'html',
		timeout: TIME_OUT,
		cache: false,
		data:
		{
			//传递用户参数
			a:"deletefavorites",
			id:status_id
		},
		error: function()
		{
			alert('Ajax request error');
		},
		success: function(json)
		{	
			//成功提示
			//location.href = 'favorites.php'; 
			var nodeAncestor=document.getElementById("fa"+status_id).parentNode.parentNode.parentNode;
			var ancestorParent = nodeAncestor.parentNode;
			ancestorParent.removeChild(nodeAncestor);
		}
	});
}
unfshow = function(status_id)
{
	//$("#idBox2"+status_id).remove();
	var html = " <div id=idBox2"+status_id+" style='width:208px; height:85px; line-height:85px;  position:absolute; left:0px; top:-90px;border:1px solid #CACACA; background-image:url(res/img/unfa.jpg); text-align:center'><div style='padding-top:10px;' ><a href='javascript:void(0);' onclick='unfavorites("+status_id+")'><img src='res/img/true.jpg'></a>&nbsp;&nbsp;<a href='javascript:void(0);' onclick='unclose("+status_id+")'><img src='res/img/false.jpg'></a></div></div>";
	$("#fa"+status_id).append(html);
	$("#idBox2"+status_id).fadeIn(5000);	
	//unfavorites(status_id);
}

unclose = function(status_id)
{
	$("#idBox2"+status_id).fadeOut(500); 
}
});