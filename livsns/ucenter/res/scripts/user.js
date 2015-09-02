$(document).ready(function (){
		
	changeContent = function(type , user_id){
						
			switch(type)
			{
				case 1:/*视频*/
					
					$('#content').empty();
					loadingPage('加载中，请稍后...');
										
					$("#t_2").attr('class','bt_d');
					$("#t_3").attr('class','bt_d');
					$("#t_4").attr('class','bt_d');
					$("#t_1").attr('class','bt');
					
					$.ajax({
						url: "user.php",
						type: 'POST',
						dataType: 'html',
						timeout: TIME_OUT,
						cache: false,
						data: {a: "videos",
							  user_id: user_id,
							  ajax:1,
							  content:"content"
						},
						error: function(){
							alert('Ajax request error');
						},
						success: function(json){
							var obj = new Function("return" + json)();
							eval(obj.callback);	
						}
					});	
					
					break;
				case 2:/*微博*/
					
					$('#content').empty();
					loadingPage('加载中，请稍后...');
										
					$("#t_1").attr('class','bt_d');
					$("#t_3").attr('class','bt_d');
					$("#t_4").attr('class','bt_d');
					$("#t_2").attr('class','bt');
					
					
					$.ajax({
						url: "user.php",
						type: 'POST',
						dataType: 'html',
						timeout: TIME_OUT,
						cache: false,
						data: {a: "status",
							  user_id: user_id,
							  ajax:1,
							  content:"content"
						},
						error: function(){
							alert('Ajax request error');
						},
						success: function(json){
							var obj = new Function("return" + json)();
							eval(obj.callback);	
						}
					});	
					
					
					break;				
				case 3:/*相册*/
					$('#content').empty();
					loadingPage('加载中，请稍后...');
					
					$("#t_1").attr('class','bt_d');
					$("#t_2").attr('class','bt_d');
					$("#t_4").attr('class','bt_d');
					$("#t_3").attr('class','bt');
					
					$.ajax({
						url: "user.php",
						type: 'POST',
						dataType: 'html',
						timeout: TIME_OUT,
						cache: false,
						data: {a: "albums",
							  user_id: user_id,
							  ajax:1,
							  content:"content"
						},
						error: function(){
							alert('Ajax request error');
						},
						success: function(json){
							var obj = new Function("return" + json)();
							eval(obj.callback);	
						}
					});			
					
					break;
				case 4:/*帖子*/
					
					$('#content').empty();
					loadingPage('加载中，请稍后...');
					
					$("#t_1").attr('class','bt_d');
					$("#t_2").attr('class','bt_d');
					$("#t_3").attr('class','bt_d');
					$("#t_4").attr('class','bt');
					
					$.ajax({
						url: "user.php",
						type: 'POST',
						dataType: 'html',
						timeout: TIME_OUT,
						cache: false,
						data: {a: "topic",
							  user_id: user_id,
							  ajax:1,
							  content:"content"
						},
						error: function(){
							alert('Ajax request error');
						},
						success: function(json){
							var obj = new Function("return" + json)();
							eval(obj.callback);						
						}
					});
					break; 
				default :
					break;
			}
			
		};
		
		
		hg_getlist = function(json,content)
		{
			$('#content').html(json);
		}
		
		
		page_user_show = function(e,type,user_id){
			str = e.id;
			strs=str.split("_"); /*字符分割*/ 
			pp = strs[strs.length-1];
			$.ajax({
		        url: "user.php",
		        type: 'POST',
		        dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
		        data: {
					a: "page_show",
					type:type,
					user_id:user_id,
					pp: pp
		        	},
		        error: function() {
		        		tipsport('网络延迟！');
		        },
		        success: function(json) {
		        	if(json)
		        	{
		        		$("#content").html(json);
		        		var scrollObj = document.documentElement || document.body; /*浏览器兼容*/
		        		if(scrollObj)
		        		{
		        			scrollObj.scrollTop=200;
		        		}
		        	}
		        	else
		        	{
		        		tipsport('网络延迟！');
		        	}
		        }
		    });
			
		};
	
	
		/*添加关注*/
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
		        			li = '<a class="plays" href="'+SNS_VIDEO+'station_play.php?sta_id='+obj.sid+'"></a><a class="gz_get" href="javascript:void(0);" onclick="del_concern('+ obj.cid +','+ obj.sid +','+ id +',1);">取消关注</a>';
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
		

		/*取消关注*/
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
		        		li = '<a class="plays" href="'+SNS_VIDEO+'station_play.php?sta_id='+ obj.cid +'"></a><a class="gz_get" href="javascript:void(0);" onclick="add_concern('+ obj.cid +',1,'+ id +');">关注该频道</a>';
		        		$("#collect_" + obj.cid).html(li);
	        		}
				}
		});
		};

		/*解除黑名单*/
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
		


		/*删除一条博客信息*/
		destroy_blog= function(status_id)
		{	
			$("#idBox2"+status_id).html('<img style="padding-top:30px;" src="res/img/loading_page.gif"/>删除中...');
			$.ajax({
				url: "user.php",
				type: 'POST',
				dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
				data:
				{
					/*传递用户参数*/	
					a:"destroy_blog",
					status_id:status_id
				},
				error: function()
				{
					tipsport('网络延迟！');
				},
				success: function(json)
				{	
					
					//location.href = 'user.php';
					var nodeAncestor=document.getElementById("fa"+status_id).parentNode.parentNode.parentNode;			
					var ancestorParent = nodeAncestor.parentNode;
					ancestorParent.removeChild(nodeAncestor);
					$("#status_count").html(parseInt($("#status_count").html())-1);
				}
			});
			};
		
		
			unfshowd = function(status_id)
			{
				$("#idBox2"+status_id).remove();  
				var html = ' <div id="idBox2'+status_id+'" class="del_box"><div class="del_tip">确定删除这条点滴吗？</div>' +
							'<div style="padding-top:20px;" >' +
							'	<a href="javascript:void(0);" onclick="destroy_blog('+status_id+')"><img src="res/img/true.jpg"></a>' +
									'&nbsp;&nbsp;<a href="javascript:void(0);" onclick="unclose('+status_id+')"><img src="res/img/false.jpg"></a>' +
											'</div></div>';
				$("#fa"+status_id).append(html);
				$("#idBox2"+status_id).fadeIn(5000);
				//unfavorites(status_id);
			};
						
			//通知页面切换css
			changeCss = function(current_id)
			{
				switch(current_id)
				{
					case 1 : $("#_2").removeClass("bt");$("#_1").addClass("bt");location.href=re_back+'?a=show_notice';break;
					case 2 : $("#_2").addClass("bt");$("#_1").removeClass("bt");location.href=re_back+'?a=show_notice&n=1';break;
				}
				
			};

			//显示对话框
			show_msg_box = function(u_id)
			{
				$.ajax({
					url:"user.php",
					type:"POST",
					dataType:"html",
					cache:false,
					data:{
						a:"do_showMessages", 
						u_id:u_id,
						id:$("#this_uid"+u_id).val()
					},
					success:function(obj){
						obj = obj.split('[[[------------]]]');
						if(in_array(obj[1],1) == false){
							$("#desktop").append(obj[0]);
							var top_num = $(document).scrollTop() + ($(window).height() / 2 -parseInt($("#dialog_"+obj[1]).outerHeight() / 2)) ;
							document.getElementById("contentList_"+obj[1]).scrollTop = document.getElementById("contentList_"+obj[1]).scrollHeight;//让滚动条保持在对话的最后一条
							$("#dialog_"+obj[1]).css("top",top_num); 
							$("#dialog_"+obj[1]).show();
							if(in_array('dm'+obj[1],0) == false){
								var nn_id = 'dm'+obj[1]; 
								var lli = $("<li></li>");
								lli.attr("id","dm" + obj[1]);
								lli.attr("style","width:105px;position:relative;font-size:12px;overflow-x:hidden;float:right;");
								var div_html = '<div class="MUserLiBox"><a onclick="return dialogDestroy(\''+obj[1]+'\');" href="javascript:void(0);" class="MClose"></a><span class="user_name"><a href="javascript:void(0);" onclick="showHideDialog(\''+obj[1]+'\');">'+obj[2]+'</a></span></div>';
								lli.html(div_html);
								lli.attr("className","hover");
								lli.click=function(){showHideDialog(obj[1]);};
								$("#dialogMinimizeContainer").append(lli);
							}
						}else{
							$("#dialog_"+obj[1]).show();
						}
					},
					error:function(){}
				});
			};	
			
			//type:0 判断最底下的对话层，type：1判断desktop
			in_array = function(neddle,type)
			{
				if(type == 0){
					var dm_ch = $("#dialogMinimizeContainer").children();
					var dm_ch_ll = dm_ch.length;
				 	for(var i=0;i<dm_ch_ll;i++)
				 	{ 
				 		if(neddle == $(dm_ch[i]).attr("id"))
				 		{
				 			return true;
				 		}
				 	}
				 	return false;
				}else{
					var cc = $("#desktop").children();
					var cc_l = cc.length;
					var dd = 'dialog_'+neddle;
					for(var k=0;k<cc_l;k++)
					{
						if(dd == $(cc[k]).attr('id'))
						{
							return true;
						}
					}
					return false;
				}
			};			
});

function loadingPage(message)
{
	$('#content').html('<div style="text-align:center;"><img src="./res/img/loading_page.gif" /><span style="color:gray;">' + message + '</span></div>');
}

function delay(){return true;}