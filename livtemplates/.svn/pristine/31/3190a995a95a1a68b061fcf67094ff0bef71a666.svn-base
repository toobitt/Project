/* 
 * f触发事件的按钮ID
 * id输入框对象
 * */
function isFocus(id,f){
	if(document.activeElement.id== id)
	{		
//        $("#"+f).click();
	}
}
$(document).keypress(function(e){
	if(e.ctrlKey && e.which == 13 || e.which == 10) 
	{
//		isFocus('com_content','insert_comment');
	}
});

//光标定位-以及选中
function cursor(id,numS,numE)
{
	var obj = document.getElementById(id);
	if(obj.createTextRange)
	{//IE浏览器
	    var range = obj.createTextRange();
	    range.moveEnd("character",numE);
	    range.moveStart("character",numS);
	    range.select();
	}else{//非IE浏览器
		obj.setSelectionRange(numS,numE);
		obj.focus();
	}
}

function closeWindow() 
{
 　window.opener = null;
 　window.open(' ', '_self', ' '); 
 　window.close();
}

$(document).ready(function(){	
	//添加收藏（用户叫做关注）
	add_collect = function (id,type,uid){
		if(!id)
		{
			tipsport('对象已被删除！');
		}
		else
		{
			if(!uid)
			{
				location.href = SNS_UCENTER + "login.php";
			}
			else
			{
				$.ajax({
			        url: "index.php",
			        type: 'POST',
			        dataType: 'html',
					timeout: TIME_OUT,
					cache: false,
			        data: {
						a: "create_collect",
						id: id,
						type: type,
						uid:uid
			        	},
			        error: function() {
			        		tipsport('网络延迟！');
			        },
			        success: function(json) {
			        	if(json == 'null')
			        	{
			        		location.href = SNS_UCENTER + "login.php";
			        	}
			        	else
			        	{
				        	var obj = new Function("return" + json)();
				        	switch(type)
				    		{
				        		case 0:
				        			if($("#collect_"+id).html())
			        				{
				        				$("#collect_count_"+id).html(parseInt($("#collect_count_"+id).html())+1);
						        		$("#collect_"+id).html('<img src="'+RESOURCE_DIR+'img/sy_button.jpg" width="58" height="18" />');
			        				}
				        			
					        		if($("#video_collect").html())
					        		{
					        			$("#video_collect").html('<a href="javascript:void(0);">已收藏</a>');
					        		}
				        			break;
				        		case 1:
				        			if(id)
			        				{
					        			if(obj.self)
							        	{
							        		tipsport('不能关注自己！');
							        	}
							        	else
						        		{
							        		if($("#collect_"+id).html())
						        			{
							        			$("#c_"+id).html(parseInt($("#c_"+id).html())+1);
							        			$("#collect_"+id).html('已关注');
						        			}
							        		
							        		if($("#con_"+id).html())
							        		{
							        			$("#co_"+id).html(parseInt($("#co_"+id).html())+1);
							        			li = '<a class="gz_get" href="javascript:void(0);" onclick="del_concern('+ obj.id +','+ id +','+ uid +',1);">取消关注</a>';
							        			$("#con_"+id).html(li);
							        		}
						        		}			        			
			        				}
				        			else
				        			{
				        				tipsport('该用户未创建频道！');
				        			}
				        			break;
				        		case 2:
				        			if(obj.self)
						        	{
						        		tipsport('不能关注自己！');
						        	}
						        	else
					        		{
						        		$("#c_"+id).html(parseInt($("#c_"+id).html())+1);
						        		$("#collect_"+id).html('已关注');
					        		}
				        			break;
				        		default:
				        			break;
				    		}
			        	}
			        }
			    });		
			}
		}
	}
	
	del_collect = function (id,cid,type){
		if(confirm('确定删除此收藏?'))
		{
			$.ajax({
		        url: "index.php",
		        type: 'POST',
		        dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
		        data: {
					a: "del_collect",
					id: id,
					cid:cid,
					type: type
		        	},
		        error: function() {
		        		tipsport('网络延迟！');
		        },
		        success: function(json) {
		        	if(json == 'null')
		        	{
		        		tipsport('删除失败');
		        	}
		        	else
		        	{
			        	var obj = new Function("return" + json)();
			        	
			        	if($("#collect_"+id).html())
		        		{
		        			$("#collect_"+id).remove();
		        		}
		        	}
		        }
		    });
		}
	}
	
	del_concern = function (id,cid,uid,type){
		if(!uid)
		{
			location.href = SNS_UCENTER + "login.php";
		}
		else
		{
			if(confirm('确定删除此收藏?'))
			{
				$.ajax({
			        url: "index.php",
			        type: 'POST',
			        dataType: 'html',
					timeout: TIME_OUT,
					cache: false,
			        data: {
						a: "del_collect",
						id: id,
						cid:cid,
						type: type
			        	},
			        error: function() {
			        		tipsport('网络延迟！');
			        },
			        success: function(json) {
			        	if(json == 'null')
			        	{
			        		tipsport('删除失败');
			        	}
			        	else
			        	{
				        	var obj = new Function("return" + json)();
				        	
				        	if($("#con_" + cid).html())
			        		{
				        		$("#co_"+cid).html(parseInt($("#co_"+cid).html())-1);
				        		li = '<a class="gz_get" href="javascript:void(0);" onclick="add_collect('+ cid +',1,'+ uid +');">关注该频道</a>';
				        		$("#con_" + cid).html(li);
			        		}
			        	}
			        }
			    });
			}
		}
	}
	
	
	$("#sup").click(function(){
		
	});
	$("#sdown").click(function(){
		
	});
	$("#share").click(function(){
		
	});
	
	/*tipsmessage*/
	if($("#tips").html()!=null)	
	{
		var tips = new AlertBox("tips"),locks = false;
		function lockup(e){ e.preventDefault(); };
		function lockout(e){ e.stopPropagation(); };
		tips.onShow = function(){
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
	
	// 表情开始	
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
		countChar('counter',id,500);
		cursor(id,obj.val().length,obj.val().length);
	}
	
	global_face = function(cons,tabs){
		if(!$('#'+tabs).html())
		{
			var loading = '<img src="res/img/loading_page.gif"/>加载中...';
			$('#'+tabs).html(loading);
			$('#'+tabs).toggle();
			$.ajax({
				url:"comment.php",
				type:"POST",
				dataType:"html",
				timeout: TIME_OUT,
				data:{
					a:"get_face",
					con:cons,
					ajax:1,
					tab:tabs
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
	
	hg_html_face = function(html,tabs)
	{
		$("#"+tabs).html(html);
	}
	
	$("#choiceface").bind("click",function(){
		global_face('com_content','face');
	});

	$("#facelist").bind("click",function(){
		global_face('status','faceF');
	});
	
	//举报
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
		//	$("#status").focus();
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
				case 2:
					var prefix = '上传的视频';
					break;
				case 5:
					var prefix = '视频的评论';
					break;
				case 9:
					var prefix = '创建的频道';
					break;
				case 11:
					var prefix = '频道的评论';
					break;
				default:
					var prefix = '的评论';
					break;
			}
			
			$("#users").html("你要举报的是“"+username+"”"+prefix+"：");
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
			url:"index.php",
			type:"POST",
			dataType:"html",
			timeout: TIME_OUT,
			cache:false,
			data:{
				a:"add_report",
				cid:param_re.cid,
				uid:param_re.uid,
				type:param_re.type,//该举报的对象类型
				url:param_re.url,//该举报的对象进入地址
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
});




