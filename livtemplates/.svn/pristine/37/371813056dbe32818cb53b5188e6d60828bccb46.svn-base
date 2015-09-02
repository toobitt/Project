/* 
 * f触发事件的按钮ID
 * id输入框对象
 * */
function isFocus(id,f){
	if(document.activeElement.id== id)
	{		
        $("#"+f).click();
	}
}
$(document).keypress(function(e){
	if(e.ctrlKey && e.which == 13 || e.which == 10) 
	{
		isFocus('com_content','insert_comment');
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
	//添加评论（用户叫做留言）
	add_comment = function (){
		cid = $("#com_cid").val();
		content = $("#com_content").val();
		reply_id = $("#com_reply_id").val();
		reply_user_id = $("#com_reply_user_id").val();
		type = parseInt($("#com_type").val());	
		if(content == "")
		{
			tipsport('内容不能为空！');
		}
		else
		{
			var names = '';
			if($("#syn").attr("checked"))
			{
    			var check = 1;    
			}
    		else
    		{
    			var check = 0;  
    		}
			if(type)
			{
				names = $("#station_name").html()? $("#station_name").html():'';
			}
			else
			{
				names = $("#vedio_name_container").html()? $("#vedio_name_container").html():'';
			}	
			
			$.ajax({
		        url: "comment.php",
		        type: 'POST',
		        dataType: 'html',
				timeout: TIME_OUT,
				cache: false,
		        data: {
					a: "create_comment",
					cid: cid,
					content: content,
					reply_id: reply_id,
					reply_user_id: reply_user_id,
					checked:check,
					title:names,
					type: type
		        	},
		        error: function() {
		        		tipsport('网络延迟！');
		        },
		        success: function(json) {
		        	var obj = new Function("return" + json)();
		        	if(obj=="")
		        	{
		        		location.href = SNS_UCENTER + "login.php";
		        	}
		        	else
		        	{
		        		$("#count_num").html(parseInt($("#count_num").html())+1);
		        		if(reply_id==0)
	        			{
		        			$.each(obj,function(k,v){
		        				var types = parseInt($("#types").html());
		        				var urls = $("#urls").html() + cid +'#c'+v.id;
		        				li = '<li id="com_'+v.id+'" class="clear" onmouseover="report_show('+v.id+','+v.user.id+');" onmouseout="report_hide('+v.id+','+v.user.id+');">'+
		        				'<a name="c'+v.id+'" id="c'+v.id+'"></a>'+
			        			'<div class="comment-img"><a target="_blank" href="'+SNS_UCENTER+'user.php?user_id='+v.user.id+'"><img src="'+v.user.middle_avatar+'"/></a></div>'+
			        			'<div class="comment-bar">'+
			        			'<a target="_blank" class="bar-left" href="'+SNS_UCENTER+'user.php?user_id='+v.user.id+'">'+v.user.username+'</a>'+
			        			'<div style="display:none;" id="cons_'+v.id+'_'+v.user.id+'">'+v.content+'</div>'+
			        			'<div style="display:none;" id="ava_'+v.id+'_'+v.user.id+'">'+v.user.middle_avatar+'</div>'+
			        			'<div style="display:none;" id="user_'+v.id+'_'+v.user.id+'">'+v.user.username+'</div>'+
			        			'<div style="display:none;" id="url_'+v.id+'_'+v.user.id+'">'+ urls +'</div>'+
			        			'<div style="display:none;" id="type_'+v.id+'_'+v.user.id+'">'+ types +'</div>'+
			        			'<div class="bar-right">'+
			        			'<span>'+v.create_time+'</span> ';
			        			if(report)
								{
									li += '<a id="re_'+v.id+'_'+v.user.id+'" onclick="report_play('+v.id+','+v.user.id+');" href="javascript:void(0);" style="display:none;">举报</a> '+
			        			'<a href="javascript:void(0);" onclick="reply_comment('+v.cid+','+v.id+','+v.user.id+');">回复</a> '+
			        			'<a href="javascript:void(0);" onclick="del_comment('+v.id+');">删除</a>'+
			        			'</div></div><div class="comment-con">'+(v.content)+'</div></li>';
								}
								else
								{
									li += '<a href="javascript:void(0);" onclick="reply_comment('+v.cid+','+v.id+','+v.user.id+');">回复</a> '+
			        			'<a href="javascript:void(0);" onclick="del_comment('+v.id+');">删除</a>'+
			        			'</div></div><div class="comment-con">'+(v.content)+'</div></li>';
								}
		        				var content = v.content;
		        			});
		        			if($('#comment_list').html())
	        				{
		        				$("#com_content").val('');
		        				$("#com_content").focus();
		        				$(li).prependTo("#comment_list");
	        				}
	        			else
	        				{
		        				$("#com_content").val('');
		        				$("#com_content").focus();
	        					ul = '<ul class="comment_list" id="comment_list">'+li+'</ul>';
	        					$('#comment').html(ul);
	        				}
		        			
	        			}
		        		else
	        			{
		        			$.each(obj,function(k,v){
		        				var types = parseInt($("#types").html());
		        				var urls = $("#urls").html() + cid +'#c'+v.id;
		        				li = '<li id="com_'+v.id+'" class="clear" onmouseover="report_show('+v.id+','+v.user.id+');" onmouseout="report_hide('+v.id+','+v.user.id+');">'+
		        				'<a name="c'+v.id+'" id="c'+v.id+'"></a>'+
		        				'<div class="comment-img"><a target="_blank" href="'+SNS_UCENTER+'user.php?user_id='+v.user.id+'"><img src="'+v.user.middle_avatar+'"/></a></div>'+
			        			'<div class="comment-bar">'+
			        			'<a target="_blank" class="bar-left" href="'+SNS_UCENTER+'user.php?user_id='+v.user.id+'">'+v.user.username+'</a>'+
			        			'<div style="display:none;" id="cons_'+v.id+'_'+v.user.id+'">'+v.content+'</div>'+
			        			'<div style="display:none;" id="ava_'+v.id+'_'+v.user.id+'">'+v.user.middle_avatar+'</div>'+
			        			'<div style="display:none;" id="user_'+v.id+'_'+v.user.id+'">'+v.user.username+'</div>'+
			        			'<div style="display:none;" id="url_'+v.id+'_'+v.user.id+'">'+ urls +'</div>'+
			        			'<div style="display:none;" id="type_'+v.id+'_'+v.user.id+'">'+ types +'</div>'+
			        			'<div class="bar-right">'+
			        			'<span>'+v.create_time+'</span> ';
								if(!report)
								{
									li += '<a href="javascript:void(0);" onclick="reply_comment('+v.cid+','+v.reply_id+','+reply_user_id+');">回复</a> '+
			        				'<a href="javascript:void(0);" onclick="del_comment('+v.id+');">删除</a>'+
			        				'</div></div><div class="comment-con">'+(v.content)+'</div></li>';
								}
								else
								{
									li += '<a id="re_'+v.id+'_'+v.user.id+'" onclick="report_play('+v.id+','+v.user.id+');" href="javascript:void(0);" style="display:none;">举报</a> '+
									'<a href="javascript:void(0);" onclick="reply_comment('+v.cid+','+v.reply_id+','+reply_user_id+');">回复</a> '+
									'<a href="javascript:void(0);" onclick="del_comment('+v.id+');">删除</a>'+
									'</div></div><div class="comment-con">'+(v.content)+'</div></li>';
								}
			        			//
			        			
		        				var content = v.content;
		        			});
		        			if($('#rep_'+reply_id).html())
	        				{
		        				back_comment();
	        					$(li).appendTo('#rep_'+reply_id);
	        				}
		        			else
	        				{
	        					back_comment();
	        					ul = '<ul class="reply_list" id="rep_'+reply_id+'">'+li+'</ul>';
	        					$(ul).appendTo('#com_'+reply_id);
	        				}
	        			}
		        	}
		        	$("#counter").html('500');
		        }
		    });
		}	
	}
	reply_comment = function(cid,reply_id,reply_user_id){
		$("#com_cid").val(cid);
		$("#com_reply_id").val(reply_id);
		$("#com_reply_user_id").val(reply_user_id);
		$("#comment_note").attr('style','margin-left:10%;');
		$("#com_"+reply_id).append($("#comment_note"));
		$("#com_content").val('');
		$("#com_content").focus();
		$("#comment_back").show();	
	}
	back_comment = function (){
		$("#comment_note").attr('style','margin-left:0;');
		$("#com_reply_id").val(0);
		$("#com_reply_user_id").val(0);
		$("#comment").prepend($("#comment_note"));
		$("#com_content").val('');
		$("#com_content").focus();
		$("#comment_back").hide();
	}
	
	
	
	del_comment = function (id,cid,type){
		$.ajax({
	        url: "comment.php",
	        type: 'POST',
	        dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
	        data: {
				a: "del_comment",
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
		        	$("#com_"+id).remove();
		        	$("#count_num").html(parseInt($("#count_num").html())-1);
	        	}
	        }
	    });
	}
	
	recover_comment = function (id,cid,type){
		$.ajax({
	        url: "comment.php",
	        type: 'POST',
	        dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
	        data: {
				a: "recover_comment",
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
		        	$("#com_"+id).remove();
	        	}
	        }
	    });
	}
	
	comment_page = function(e,cid,user_id,type,count){
		str = e.id;
		strs=str.split("_"); //字符分割 
		pp = strs[strs.length-1];
		$.ajax({
	        url: "comment.php",
	        type: 'POST',
	        dataType: 'html',
			timeout: TIME_OUT,
			cache: false,
	        data: {
				a: "comment_list",
				pp: pp,
				cid: cid,
				user_id: user_id,
				type:type,
				count: count
	        	},
	        error: function() {
	        		tipsport('网络延迟！');
	        },
	        success: function(json) {
	        	if(json)
	        	{
	        		$("#comment").html(json);
	        	}
	        	else
	        	{
	        		tipsport('网络延迟！');
	        	}
	        }
	    });
	}
	
	get_comment_plot = function(type){
		if(type)
		{
			$("#com_content").focus();
		}
		else
		{
			location.href = SNS_UCENTER + "login.php";
		}	
	}

	countChar = function(counts,id,num){  
		counter = $("#"+counts);
		obj = $("#"+id);
		counter.html(parseInt(num) - obj.val().length);
		if(counter.html()<1)
		{
			counter.html("<b style='color:red'>0</b>");
			obj.val(obj.val().substring(0,parseInt(num)));
		}
	}
		
	
	pubStatus = function(content){		
	    if (content != "") 
	    {
    		$.ajax({
	            url: "comment.php",
	            type: 'POST',
	            dataType: 'html',
	   			timeout: TIME_OUT,
	   			cache: false,
	            data: {
        			status: content,
		        	a: "update",
		        	source:"点滴",
	        		type:0
		        	},
	            error: function() {
		        		tipsport('网络有误！');
	            },
	            success: function(json) { 
		            var obj = new Function("return" + json)();
					if(obj=='false')
					{
						location.href = SNS_UCENTER + "login.php";
					}
					else
					{
						if(!obj.total)
						{
							$("#syn").attr("checked","false");
						}
						else
						{	
							tipsport('不要太贪心,发一次就够了!');							
						}
					}        	
	            }
	        });
	    }
	}

});
