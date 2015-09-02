var to_name = "";
var ll = '';
var salt_str = "";
var showtype = "";
var ul_li = "";
var msg_time = 0;
var childs = "";
$(document).ready(function (){ 
	 
	chat_insert_face = function(id,face){
		obj = $("#"+id);
		obj.val(obj.val()+face+' ');
		$('div.facelist').hide();
		/*cursor(id,obj.val().length,obj.val().length);*/
	}; 
	show_face = function(obj_id)
	{
		$("#"+obj_id).toggle();
	};
	
	
	showMsgBox = function(to_names,salt_strs,showtypes,ul_lis)
	{  
		to_name = to_names;
		salt_str = salt_strs;
		showtype = showtypes;
		ul_li = ul_lis;
		var childs = $("#desktop").children();
		ll = childs.length;
		var flag = 0; 
		/*alert(sns_uui_url+"messages.php");*/
		$.ajax({
			url:sns_uui_url+"messages.php",
			type:"post",
			dataType:"html",
			cache:false,
			timeout: TIME_OUT,
			data:{
				a:"show",
				salt_str:salt_str,
				to_name:to_name,
				ajax:1,
				showtype:showtype,
				ul_li:ul_li
			},

			success:function(json){
				var obj = new Function("return" + json)();
				eval(obj.callback);
			},
			error:function(){
				/*alert('请求出错了！');*/
			}
		});	
	};
	
	
	hg_html_dialog = function(html,salt_str)
	{
		json = html+'[[------]]' + salt_str;

		var flag = 0; 
		sid = json.split('[[------]]')[1];
				json = json.split('[[------]]')[0];
				
				if(ll != 0)
				{
					$.each(childs,function(k,v){
						if($(childs[k]).attr("id") != "dialog_" + sid)
						{
							flag = 0;
						}
						else
						{
							flag += 1;
						}
					});
				}
				if(ll == 0 || flag == 0)
				{
					 
					
					$("#desktop").append(json);//如果原页面不存在任何对话div或者不存在新的div就将其附加进去
					//对话框页面绝对居中
					var top_num = $(document).scrollTop() + ($(window).height() / 2 -parseInt($("#dialog_"+sid).outerHeight() / 2)) ;
					$("#dialog_"+sid).css("top",top_num);
					if(!showtype){
						$("#dialog_"+sid).css("display","block");
						
					} 
				}
				
				
				
					$.each(childs,function(k,v){
						
						
						
						if($(childs[k]).attr("id") == 'dialog_'+sid)
						{
							$(childs[k]).show();
						}
						else
						{
							$(childs[k]).hide();
						}
					});
					
					
					var li_childs = $("#dialogMinimizeContainer").children();
					var n_flag = 0;
					if(li_childs.length != 0)
					{
						$.each(li_childs,function(k){
							if($(li_childs[k]).attr("id") == "dm" + sid)
							{
								n_flag += 1; 
							}
							else
							{
								n_flag = 0;
							}
						});
					}
					
					
					if(li_childs.length == 0 || n_flag == 0)
					{
						var lli = $("<li></li>");
						lli.attr("id","dm" + sid);
						lli.attr("style","width:105px;position:relative;font-size:12px;overflow-x:hidden;float:right;");
						var div_html = '<div class="MUserLiBox"><a onclick="return dialogDestroy(\''+sid+'\');" href="javascript:void(0);" class="MClose"></a><span class="user_name"><a href="javascript:void(0);" onclick="showHideDialog(\''+sid+'\');">'+to_name+'</a></span></div>';
						lli.html(div_html);
						lli.attr("className","hover");
						$("#dialogMinimizeContainer").append(lli);
					}
					
	       }

	
	
	
	
	
	
	
	/*最小化窗口*/
	dialogMinimize = function(salt_str)
	{
		$("#dialog_"+salt_str).toggle(); 
	};
	
	/*关闭窗口*/
	dialogDestroy = function(salt_str)
	{
		$("#dm"+salt_str).remove();
		$("#dialog_"+salt_str).remove(); 
		update_ltime(salt_str);
	};
	
	/*还原对话窗口*/
	showHideDialog = function(salt_str)
	{
		 
		
		if($("#dm"+salt_str).hasClass("new"))
		{ 
			$("#dm"+salt_str).removeClass("new");
			update_ltime(salt_str);
		}
		
		if(!$("#dm"+salt_str).hasClass("hover"))
		{
			$("#dm"+salt_str).addClass("hover");
		}
		
		var ch = $("#contentList_"+salt_str).children();
		 
		if(ch.length != 0)
		{
			$("#first"+salt_str).hide(); 
			$("#contentList_"+salt_str).show();
		}
			
		var childs = $("#desktop").children();
		var ll = childs.length;
		for(var n=0;n<ll;n++)
		{
			var n_id = $(childs[n]).attr("id");
			if(('dialog_'+salt_str) == n_id){
				$("#dialog_"+salt_str).show();
			}else
			{
				$("#"+n_id).hide();
			}
		}
	};
	
	/*两人对话发送信息*/
	sendMessage = function(salt_str){ 
		check_new_msg();
		var send_con = $("#content_"+salt_str).val();
		if(!send_con)
		{
			alert("消息内容不可为空！");
			$("#content_"+salt_str).focus();
		}
		else if(send_con.length > 300)
		{
			alert("您当前输入" + send_con.length + "个字符，超出300个字符的限制！");
			$("#content_"+salt_str).focus();
		}
		else
		{
			$("#s"+salt_str).hide();
			$("#dis_submit"+salt_str).show(); 
			
			var pp = $("#contentList_"+salt_str+">li:last").attr("id");
			var pid;
			if(pp != "undefined" && pp != null)
			{
				pid = pp.split('_')[1];
			}
			else
			{
				pid = 0;
			} 
			$.ajax({
				url:sns_uui_url+"messages.php",
				type:"POST",
				dataType:"html",
				cache:false,
				timeout: TIME_OUT,
				data:{
					a:"send_msg",
					sid:salt_str,
					content:$("#content_"+salt_str).val(),
					to_name:$("#to"+salt_str).val(),
					pid:pid
				},
				success:function(json){ 
					$("#s"+salt_str).show();
					$("#dis_submit"+salt_str).hide();
					if(json != "null")
					{
						 
						if($("#dm"+salt_str).hasClass("new"))
						{
							$("#dm"+salt_str).attr("className","hover");
						}
						json =  new Function("return" + json)();
						var c_name;
						if(json.fromwho != $("#caption_"+salt_str).html())
						{
							c_name = 'liBgDif';
						}
						else
						{
							c_name = '';
						}
						
						var ul_content = '<li id="pm_'+json.pid+'" class="'+c_name+'"><div class="eachMessage clearfix"><div class="left"><span  class="user_name">'+json.fromwho+'</span></div><div class="right">'+json.stime+'</div><div style="color: rgb(125, 125, 125);" class="eachMessageCon">'+json.content+'</div></div></li>';
						$("#contentList_"+salt_str).append(ul_content);
						document.getElementById("contentList_"+salt_str).scrollTop = document.getElementById("contentList_"+salt_str).scrollHeight;/*让滚动条保持在对话的最后一条*/
						$("#content_"+salt_str).val("");
						$("#contentList_"+salt_str).show(); 
					}  
				},
				error:function(){
					alert('网络延时，信息发送失败!');
					$("#s"+salt_str).show();
					$("#dis_submit"+salt_str).hide();
				} 
			});
			 
		}
 
	};
	
	check_new_msg = function(){ 
	 
		$.ajax({
			url:sns_uui_url+"messages.php",
			type:"POST",
			dataType:"html",
			cache:false,
			timeout: TIME_OUT,
			data:{
				a:"check_new"  
			},
			success:function(obj){  
				if(obj.length > 0) 
				{   
					msg_time = 0;
					var json =  new Function("return" + obj)();  
					$.each(json,function(k){
						var c_name,nn; 
						var cc = json[k].content;
						var ul_content = new Array();
						ul_content[k] = '';
						$.each(cc,function(kk){ 
							if(cc[kk].cfromwho == now_user)
							{
								c_name = 'liBgDif';
							}
							else
							{
								c_name = '';
								nn = cc[kk].cfromwho;
							} 
							if(in_array("pm_"+cc[kk].pid,1,k) == false){
								ul_content[k] += '<li id="pm_'+cc[kk].pid+'" class="'+c_name+'"><div class="eachMessage clearfix"><div class="left"><span  class="user_name">'+cc[kk].cfromwho+'</span></div><div class="right">'+cc[kk].stime+'</div><div style="color: rgb(125, 125, 125);" class="eachMessageCon">'+cc[kk].content+'</div></div></li>';
							}
							
 						});  
 					
						/*判断当前是否有对话窗口，如果有就把内容append到原来的对话内容中，如果没有对话窗口，就给desktop添加一个新节点*/
							
						var salt_str = k;  
						if(in_array(k,2) == false){ 
							/*$("#desktop").append(n_div);*/
							showMsgBox(nn,k,1,ul_content[k]);
						}else{
							$("#contentList_"+k).append(ul_content[k]);
							document.getElementById("contentList_"+k).scrollTop = document.getElementById("contentList_"+k).scrollHeight;/*让滚动条保持在对话的最后一条 */
						}
						/*新消息提醒*/
						var nn_id = 'dm'+k;
						if(in_array(nn_id,0) == false){
							var lli = $("<li></li>");
							lli.attr("id","dm" + salt_str);
							lli.attr("style","width:105px;position:relative;font-size:12px;overflow-x:hidden;float:right;");
							var div_html = '<div class="MUserLiBox"><a onclick="return dialogDestroy(\''+salt_str+'\');" href="javascript:void(0);" class="MClose"></a><span class="user_name"><a href="javascript:void(0);" onclick="showHideDialog(\''+salt_str+'\');">'+json[k].fromwhotitle+'</a></span></div>';
							lli.html(div_html);
							lli.attr("className","new");
							lli.click=function(){showHideDialog(salt_str);};
							$("#dialogMinimizeContainer").append(lli);
						}else{
							$("#"+nn_id).attr("className","new");
						}   
					});
					setTimeout("check_new_msg()",5000);
				}
				else
				{
					 
					msg_time = msg_time +5; 
					if(msg_time < 30)
					{
						setTimeout("check_new_msg()",msg_time*1000);
					}
					else
					{
						setTimeout("check_new_msg()",30000);
					}
				}
				  
			},
			error:function(){}
		});
		/*setTimeout("check_new_msg()",5000);*/
	}; 
	
	
	/*更新未读信息的最后读取时间*/
	update_ltime = function(salt_str)
	{
		var ch = $("#contentList_"+salt_str+" li");
		var ids = new Array(ch.length);
		$.each(ch,function(k){
			ids[k] = $(ch[k]).attr("id").split("_")[1];
		});
		var id_str = ids.join(','); 
		if(ids)
		{
			$.ajax({
				url:sns_uui_url+"messages.php",
				type:"POST",
				dataType:"html",
				cache:false,
				timeout: TIME_OUT,
				data:{
					a:"update_ltime",
					ids:id_str,
					sid:salt_str
				},
				success:function(obj){ 
				},
				error:function(){
					/*alert("REQUEST ERROR!");*/
				}
			});
		}
	};
	
	/*Ctrl + Enter发送信息*/
	quick_submit = function(salt_str,e)
	{
		  
		if(e.ctrlKey && e.keyCode == 13  || e.keyCode == 10) 
		{ 
		 
			setTimeout("sendMessage('" + salt_str + "');",100);
		}
		 
	};
	/*更换新信息的提示色*/
	change_remind_css = function(salt_str)
	{
		 
		if($("#dm"+salt_str).hasClass("new"))
		{
			$("#dm"+salt_str).attr("className","hover");
			update_ltime(salt_str);
		}
	};
});

in_array = function(neddle,type,sid)
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
	}else if(type == 1){
		var ch = $("#contentList_"+sid).children();
		var ch_ll = ch.length;
		for(var j=0;j<ch_ll;j++){
			if(neddle == $(ch[j]).attr("id"))
			{
				return true;
			}
		}
		return false;	
	}else if(type == 2){
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
}
 
 