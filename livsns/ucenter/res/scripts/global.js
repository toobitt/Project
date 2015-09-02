/*$(function(){ $('#equalize').equalHeights(); });*/


function isFocus(id,f){
	if(document.activeElement.id== id)
	{		
        $("#"+f).click();
	}
}
$(document).keypress(function(e){
	if(e.ctrlKey && (e.which == 13 || e.which == 10)) 
	{
		isFocus('status','themeBut');
		isFocus('status','Released');
		isFocus('statusF','ModForward');
		isFocus('search_people','search_people_bt');	
	}
});



var last_choice;
$(document).ready(function(){
	getCommentList = function(status_id,bloggerid)
	{
		if(!parseInt(bloggerid))
		{
			location.href = "login.php";
		}
		var html = $("#comment_list_" + status_id).html();
		if(!html)
		{  
			$.ajax({
				url:"show.php",
				type:"POST",
				dataType:"html",
				cache:false,
				data:{
					a:'show', 
					id:status_id,
					ajax:1
				},
				success:function(json){ 
					var obj = new Function("return" + json)();
					eval(obj.callback);	
				},
				error:function(){
					alert("Ajax Request Error!");
				}
			});
		}
		else
		{
			$("#comment_list_" + status_id).html(''); 
		}
	};
	
	hg_getCommentList = function(json,status_id)
	{
		if(!json)
		{ 
			location.href = "login.php";
		}
		else
		{ 
			if($("#status_item_"+status_id).length <= 0)
			{
				$("#comment_list_" + status_id).append(json);
			}
			var total = $("#num_status_" + status_id).val();
		}						
	}
	

	pushAction = function(sid)
	{
		var flag = $("#push_flag").val();
		if(flag == 0 || flag == "undefined")
		{ 
			if($("#comm_text_"+sid).val().length == 0)
			{
				alert("评论内容不可为空！");
				$("#comm_text_"+sid).focus();
			}
			else
			{
				
				var chk_val = $("#transmit_to_mt" + sid).attr("checked");
				if(chk_val == true){
					chk_val = 1;
				}else{
					chk_val = 0;
				} 
				
				$.ajax({
					url:"show.php",
					type:"POST",
					dataType:"html",
					timeout: TIME_OUT,
					cache:false,
					data:{
						a:"comment",
						status_id:sid,
						text:$("#comm_text_"+sid).val(),
						ajax:1,
						transmit_type:chk_val
					},
					success: function(json)
					{ 
						json = new Function("return" + json)();
						if(json.ErrorCode == "USENAME_NOLOGIN") 
						{
							location.href = "login.php";
						}
						else
						{
							
							$("#transmit_to_mt" + sid).removeAttr("checked");
							var bloggerid = $("input[name='blogger']").val();/*当前博主id*/  
							var url = SNS_MBLOG + 'show.php?id='+ sid +'#'+json.id;
							
							var li_new = '<dd id="co_' + json.id + '_' + json.user.id + '" onmouseover="report_show(' + json.id + ',' + json.user.id + ');" onmouseout="report_hide(' + json.id + ',' + json.user.id + ');">';
							li_new += '<a id="' + json.id + '_' + json.user.id + '" name="' + json.id + '_' + json.user.id + '"></a>'+
							'<div style="display:none;" id="cons_' + json.id + '_' + json.user.id + '">'+json.text+'</div>'+
							'<div style="display:none;" id="ava_' + json.id + '_' + json.user.id + '">'+json.user.small_avatar+'</div>'+
							'<div style="display:none;" id="user_' + json.id + '_' + json.user.id + '">'+json.user.username +'</div>'+
							'<div style="display:none;" id="url_' + json.id + '_' + json.user.id + '">'+ url +'</div>'+
							'<div style="display:none;" id="type_' + json.id + '_' + json.user.id + '">8</div>';
							
							li_new += '<div id="tips_'+json.id+'" ><input type="hidden" name="hid" value="" /></div><a href="user.php?user_id=' + json.user.id +'"><img src="'+json.user.small_avatar+'" /></a>';
							li_new += '<span style="float:right;"><a id="re_' + json.id + '_' + json.user.id + '" onclick="report_play(' + json.id + ',' + json.user.id + ');" href="javascript:void(0);" style="display:none;">举报</a>&nbsp;<a href="javascript:void(0);" onClick = "replyC('+json.id+','+json.user.id+','+sid+')">回复</a>&nbsp;';
							if((bloggerid == json.user.id) || (bloggerid == json.status.member_id))
							{ 
								li_new += '<a href="javascript:void(0)" onClick="deleteC('+json.id+','+sid+')" >删除</a>&nbsp;';
							}
	
							li_new += '</span>'; 
							li_new += '<a href="user.php?user_id=' + json.user.id +'">'+json.user.username +'</a> &nbsp;&nbsp;'+json.text;
						 	 
							li_new += '<span>&nbsp;&nbsp;'+json.create_at;
							li_new += '<input type="hidden" name="user_'+json.user.id+'" id="user_'+json.user.id+'_'+json.id+'" value="'+json.user.username+'" />';
							li_new += '</span></dd>';
							var s_id = json.status.id;
							$("#text_"+ sid).append(li_new);
							$("#comm_text_"+s_id).val('');
							
							var ss = $("#num_status_"+json.status.id).val();
							ss = parseInt(ss) + 1;
							$("#num_status_"+json.status.id).val(ss);
							var lang = $("#numTips_"+json.status.id).html();
							lang = parseInt(lang) + 1;
							$("#numTips_"+json.status.id).html(lang);
							/*更新评论数量*/
							var total_num = $("#comm_"+json.status.id).html();
							total_num = parseInt(total_num) + 1;
							$("#comm_"+json.status.id).html(total_num);

							var total_num_s = $("#coms_"+json.status.id).html();
							total_num_s = parseInt(total_num_s) + 1;
							$("#coms_"+json.status.id).html(total_num_s);
						}
					},
					error:function()
					{
						alert("Ajax Request Error!");
					}
	
				});
			}
		}
		else
		{ 
			if(!$("#comm_text_"+sid).val())
			{
				alert("回复内容不可为空！");
				$("#comm_text_"+sid).focus();
			}
			else
			{
				var chk_val = $("#transmit_to_mt" + sid).attr("checked");
				if(chk_val == true){
					chk_val = 1;
				}else{
					chk_val = 0;
				}
				
				$.ajax({
					url:"show.php",
					type:"POST",
					dataType:"html",
					timeout: TIME_OUT,
					cache:false,
					data:{
						a:"reply_comment",
						status_id:sid,
						reply_id:flag,
						text:$("#comm_text_"+sid).val(),
						transmit_type:chk_val
					},
					success:function(json)
					{ 
						$("#transmit_to_mt" + sid).removeAttr("checked");
						json = new Function("return" + json)(); 
						var bloggerid = $("input[name='blogger']").val();/*当前博主id*/  
						
						var url = SNS_MBLOG + 'show.php?id='+ sid +'#'+json.id;
						var li_new = '<dd id="co_' + json.id + '_' + json.user.id + '" onmouseover="report_show(' + json.id + ',' + json.user.id + ');" onmouseout="report_hide(' + json.id + ',' + json.user.id + ');">';
						li_new += '<a id="' + json.id + '_' + json.user.id + '" name="' + json.id + '_' + json.user.id + '"></a>'+
						'<div style="display:none;" id="cons_' + json.id + '_' + json.user.id + '">'+json.text+'</div>'+
						'<div style="display:none;" id="ava_' + json.id + '_' + json.user.id + '">'+json.user.small_avatar+'</div>'+
						'<div style="display:none;" id="user_' + json.id + '_' + json.user.id + '">'+json.user.username +'</div>'+
						'<div style="display:none;" id="url_' + json.id + '_' + json.user.id + '">'+ url +'</div>'+
						'<div style="display:none;" id="type_' + json.id + '_' + json.user.id + '">8</div>';
						
						li_new += '<span style="float:right;">';
						li_new += '<a id="re_' + json.id + '_' + json.user.id + '" onclick="report_play(' + json.id + ',' + json.user.id + ');" href="javascript:void(0);" style="display:none;">举报</a>&nbsp;<a href="javascript:void(0);" onClick = "replyC('+json.id+','+json.user.id+','+sid+')">回复</a>&nbsp;';
						if((bloggerid == json.user.id) || (bloggerid == json.status.member_id))
						{ 
							li_new += '<a href="javascript:void(0)" onClick="deleteC('+json.id+','+sid+')" >删除</a>&nbsp;';
						} 
						li_new += '</span>';
						li_new += '<div id="tips_'+json.id+'" ><input type="hidden" name="hid" value="" /></div><a href="user.php?user_id=' + json.user.id +'"><img src="'+json.user.small_avatar+'" /></a>';
						li_new += '<a href="user.php?user_id=' + json.user.id +'">'+json.user.username +'</a> &nbsp;&nbsp;'+json.text;
						li_new += '<input type="hidden" name="user_'+json.user.id+'" id="user_'+json.user.id+'_'+json.id+'" value="'+json.user.username+'" />';
						
						li_new += '<span>&nbsp;&nbsp;'+json.create_at+'</span></dd>';
						$("#text_"+ sid).append(li_new);
						$("#comm_text_"+sid).val('');  
						document.getElementById("push_flag").value = 0; 
						var lang = $("#numTips_"+json.status.id).html();
						lang = parseInt(lang) + 1; 
						$("#numTips_"+json.status.id).html(lang);
						
						
						var total_num = $("#comm_"+json.status.id).html();
						total_num = parseInt(total_num) + 1;
						$("#comm_"+json.status.id).html(total_num);

						var total_num_s = $("#coms_"+json.status.id).html();
						total_num_s = parseInt(total_num_s) + 1;
						$("#coms_"+json.status.id).html(total_num_s);
					},
					error:function()
					{
						alert("Ajax Request Error!");
					}
				});
			}  
		}
	};
	  
	/*举报*/
	if($("#report").html()!=null)
	{
		var param_re = ({
			cid:0,
			uid:0,
			type:0,
			url:0
		});
		var report = new AlertBox("report"),locks = false;
		function lockup(e){ e.preventDefault(); };
		function lockout(e){ e.stopPropagation(); };
		report.onShow = function(){
		/*$("#status").focus();*/
			if ( locks ) {
				$$E.addEvent( document, "keydown", lockup );
				$$E.addEvent( this.box, "keydown", lockout );
				OverLay.show();
			}
		}
		report.onClose = function(){	
			$$E.removeEvent( document, "keydown", lockup );
			$$E.removeEvent( this.box, "keydown", lockout );
			OverLay.close();
		}
		$$("reportClose").onclick = function(){ report.close(); }
		$$("reportClose").onclick = function(){ report.close(); }	
		report_play = function(id1,id2){
			$("#report_text").val('');
			$("#report_text").attr('class','report_text');
			param_re.cid = id1;
			param_re.uid = id2;
			var id = id1+'_'+id2;
			var username = $("#user_"+id).html();
			var avatar = $("#ava_"+id).html();
			var content = $("#cons_"+id).html();
			param_re.type = parseInt($("#type_"+id).html());
			param_re.url = $("#url_"+id).html();
			
			switch(param_re.type)
			{
				case 3:
					var prefix = '发布的点滴';
					break;
				case 8:
					var prefix = '点滴的评论';
					break;
				case 10:
					var prefix = '用户';
					break;
				default:
					var prefix = '的评论';
					break;
			}
			
			$("#users").html("你要举报的是“" + username + "”" + prefix + "：");
			$("#avatars").attr("src",avatar);
			$("#contents").html(username+"："+content);
			if(report.center){
				report.center = true;
				locks = true;
			} else {
				report.center = true;
				locks = true;
			}
			report.show();	
		}
	}
	
	report_clear = function()
	{
		report.close();
		$("#report_text").val('');
	}
	
	report_add = function(){
		var contents = $("#report_text").val();
		$.ajax({
			url:"dispose.php",
			type:"POST",
			dataType:"html",
			timeout: TIME_OUT,
			cache:false,
			data:{
				a:"add_report",
				cid:param_re.cid,
				uid:param_re.uid,
				type:param_re.type,/*该举报的对象类型*/
				url:param_re.url,/*该举报的对象进入地址*/
				content:contents
			},
			success:function(json)
			{ 
				var obj = new Function("return" + json)();
				if(obj == 'login')
				{
					location.href = "login.php";
				}
				if(obj)
				{
					if(obj.id)
					{
						report.close();
						$("#report_text").val('');
						param_re.cid = 0;
						param_re.uid = 0;
						param_re.type = 0;
						param_re.url = 0;
					}
				}
				else
				{
					report.close();
					tipsport("举报有误！");
				}	
			},
			error:function()
			{
				tipsport("网络延迟！");
			}
		});
			
	}
	
	clearReport = function(obj){
		if(obj.className == "report_text")
		{
			obj.className = '';
		}
		else
		{

		}
	}

	showReport = function(obj){
		if(obj.value != '')
		{
			obj.className = '';
		}
		else
		{
			obj.className = 'report_text';
		}	
	}
	
	report_show = function(id1,id2){
		var id = 're_'+id1+'_'+id2;
		$("#"+id).show();
		return false;
	}
	report_hide = function(id1,id2){
		var id = 're_'+id1+'_'+id2;
		$("#"+id).hide();
		return false;
	}
	
	
	replyC = function(commid,userid,statusid)
	{ 
		document.getElementById("push_flag").value = commid;
		document.getElementById("comm_text_"+statusid).value = '回复 @' + $("#user_"+userid+'_'+commid).val() + ': ';
		document.getElementById("comm_text_"+statusid).focus(); 
		$("#commBtn_"+statusid).click=(function(){
			pushAction(statusid);
			});
		
	}; 

	deleteC = function(commentid,sid)
	{
		/*删除评论时添加下面的div*/
		var dd = '<div id="box_'+commentid+'" style="width: 200px;height: 80px;text-align: center;background-color: white;padding: 5px;border: 5px solid #DFDFDF;position: absolute;z-index: 9999;font-size: 12px;left: 40%;"><p style="color: rgb(102, 102, 102);">确定删除该条评论吗?</p><br><input type="button" value="确定" name="confirm" onclick="deleteComment('+commentid+');" style="margin: 0pt 5px; background-color: rgb(108, 187, 74); padding: 3px 10px; border: 0px none; color: rgb(255, 255, 255);"><input type="button" value="取消" name="canel" onclick="closeBox('+commentid+');" style="margin: 0pt 5px; background-color: rgb(108, 187, 74); padding: 3px 10px; border: 0px none; color: rgb(255, 255, 255);"></div>';
		$("#tips_"+commentid).before(dd).fadeIn(5000);		
	};
	check_opt = function(sid)
	{
		var obj = $("#comm_text_"+sid);
		$(document).keypress(function(e){
			if(e.ctrlKey && (e.which == 13 || e.which == 10)) 
			{  
				if(last_choice)
				{
					$("#comm_text_"+last_choice).blur();
				} 
				var id = $(obj).attr("id").split("_")[2];
				if(last_choice != id)
				{ 
					$("#comm_text_"+last_choice).blur();
					last_choice = id;
				}
				pushAction(id);
				e.ctrlKey = false;
			}
		});
	}
	deleteComment = function(commentid)
	{
		$.ajax({
			url:"show.php",
			type:"POST",
			dataType:"html",
			timeout: TIME_OUT,
			data:{
				a:"del_comment",
				cid:commentid
			},
			success:function(json)
			{
				/*还没有提示*/
				closeBox(commentid);
				json = new Function("return" + json)();
				$('#co_' + json.id + '_' + json.user.id).slideUp("slow");
			//	$('#co_' + json.id + '_' + json.user.id).remove();
				
				var total = $("#num_status_" + json.status.id).val();
				total = parseInt(total) - 1;
				$("#num_status_" + status_id).val(total);
				
				/*更改显示评论个数*/
				var total_num = $("#comm_"+json.status.id).html();
				total_num = parseInt(total_num) - 1; 
				$("#comm_"+json.status.id).html(total_num);
				
				var total_num_s = $("#coms_"+json.status.id).html();
				total_num_s = parseInt(total_num_s) - 1; 
				$("#coms_"+json.status.id).html(total_num_s);
				 
				var lang = $("#numTips_"+json.status.id).html();
				lang = parseInt(lang) - 1; 
				$("#numTips_"+json.status.id).html(lang);
			},
			error:function()
			{
				alert("Ajax Request Error");
			}
		});
	};
	
	closeBox = function(commentid)
	{
		$("#box_"+commentid).remove().fadeOut(5000);
	};
	closeComm = function(statusid)
	{
		$("#comment_list_"+statusid).html('');
	};
	
	
	
	/**
	 * 首页找人js
	 */
	$('#search_people').val('');
	searchFunc = function()
	{
		if($('#search_people').val() == '请输入用户昵称')
		{			
		}
		else
		{
			$('#find_form').submit();
			$('#search_people').attr('class','text_none');
		}	
	}
	
	clearNotice = function (obj){
		
		if(obj.className == "text")
		{
			obj.className = 'text_none';
		}
		else
		{

		}
	}
	
	showNotice  = function (obj){
		if(obj.value != '')
		{
			obj.className = 'text_none';
		}
		else
		{
			obj.className = 'text';
		}			
	}
	clearUser = function (obj){
		
		if(obj.className == "username_bg")
		{
			obj.className = 'username_bg_none';
		}
		else
		{

		}
	}
	/*$('#username').val('');*/
	$('#password').val('');
	showUser  = function (obj){
		if(obj.value != '')
		{
			obj.className = 'username_bg_none';
		}
		else
		{
			obj.className = 'username_bg';
		}			
	}
	
	/*tipsmessage*/
	if($("#tips").html()!=null)	
	{
		var tips = new AlertBox("tips"),locks = false;
		function lockup(e){ e.preventDefault(); };
		function lockout(e){ e.stopPropagation(); };
		tips.onShow = function(){
			$("#status").focus();
			if ( locks ) {
				$$E.addEvent( document, "keydown", lockup );
				$$E.addEvent( this.box, "keydown", lockout );
				OverLay.show();
			}
		}
		tips.onClose = function(){	
			$$E.removeEvent( document, "keydown", lockup );
			$$E.removeEvent( this.box, "keydown", lockout );
			OverLay.close();
		}
		$$("tipsCloses").onclick = function(){ tips.close(); }
		$$("tipsClose").onclick = function(){ tips.close(); }	
		tipsport = function(text){
			$("#tipscon").html(text);
			if(tips.center){
				tips.center = true;
				locks = true;
			} else {
				tips.center = true;
				locks = true;
			}
			tips.show();	
		}
	}
	
	if($("#login").html() != null)
	{
	var $inp = $('#login input');
	$inp.bind('keydown', function (e){
		var key = e.which;		
		if (key == 13)
		{
	        var nxtIdx = $inp.index(this) + 1;
	        if(nxtIdx != 2)
	        {
	        	e.preventDefault();
	        }
	        $("#login input:eq(" + $inp.index(this) + ")").blur();
	        $("#login input:eq(" + nxtIdx + ")").focus();
	        isFocus("login_bt","login_bt");
		}
	});
	}
	
	/*表情开始*/	
	face_tab = function(n,t,id){
		n = parseInt(n);
		t = parseInt(t);
		for(i = 1;i <= t;i++)
		{
			if(i !=n )
			{
				$("#"+id+i).hide();
			}
			else
			{
				$("#"+id+i).show();
			}	
		}
	}
	
	insert_face = function(id,face,fid){
		obj = $("#"+id);
		obj.val(obj.val()+face+' ');
		$('#'+fid).hide();
		cursor(id,obj.val().length,obj.val().length);
	}
	
	global_face = function(cons,tabs){
		if(!$('#'+tabs).html())
		{
			var loading = '<img src="res/img/loading_page.gif"/>加载中...';
			$('#'+tabs).html(loading);
			$('#'+tabs).toggle();
			$.ajax({
				url:"user.php",
				type:"POST",
				dataType:"html",
				timeout: TIME_OUT,
				data:{
					a:"get_face",
					con:cons,
					tab:tabs,
					ajax:1
				},
				success:function(json)
				{
					var obj = new Function("return" + json)();
					eval(obj.callback);	
				}
			});	
		}
		else
		{
			$('#'+tabs).toggle();
		}
	}
	
	$("#facelist").bind("click",function(){
		global_face('statusF','faceF');
	});
	
});

hg_html_face = function(html,tabs)
{
	$("#"+tabs).html(html);
}



/*光标定位-以及选中*/
function cursor(id,numS,numE)
{
	var ctrl = document.getElementById(id);
	if(ctrl.setSelectionRange){
		ctrl.focus();
		ctrl.setSelectionRange(numS,numE);
	}
	else if (ctrl.createTextRange) {
		var range = ctrl.createTextRange();
		range.collapse(true);
		range.moveEnd('character', numE);
		range.moveStart('character', numS);
		range.select();
	}
}

function closeWindow() 
{
 　window.opener = null;
 　window.open(' ', '_self', ' '); 
 　window.close();
}

function changevalue(status_id)
{ 
	var vv = document.getElementById("transmit_to_mt"+status_id);
	if(vv.checked == true)
	{
		document.getElementById("transmit_to_mt"+status_id).checked = false;
	}
	else
	{
		document.getElementById("transmit_to_mt"+status_id).checked = true;
	}
}

