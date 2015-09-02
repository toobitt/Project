function show(obj)
{
	var _v = obj.replyContent.defaultValue;
	var thread_id = obj.thread_id.value;
	var post_id = obj.post_id.value;
	var reply_user_id = obj.reply_user_id.value;
	var reply_user_name = obj.reply_user_name.value;
	var replyContent = obj.replyContent.value;
	var reply_des = obj.reply_des.value;
	if (replyContent == _v)
	{
		alert('请正确输入回复信息');
		return false;
	}else {
		obj.replyContent.value = _v;
	}
	$.post('thread.php', {
		'a' : 'reply',
		'thread_id' : thread_id,
		'post_id' : post_id,
		'content' : replyContent,
		'user_id' : reply_user_id,
		'reply_user_name' : reply_user_name,
		'reply_des' : reply_des,
		'mt' : (+new Date())
	}, function(data) {
		if (data.err == 0)
		{
			alert(data.msg);
			var reply_num = $('#reply_'+thread_id).text();
			$('#reply_'+thread_id).text(parseInt(reply_num)+1);
			//location.href = 'thread.php?thread_id=' + thread_id + '#reply' + data.post_id;
		}else {
			alert(data.msg);
		}
	}, 'json');
}

$(function() {
	$('.gtalk_reply_content').each(function() {
		var _v = $(this).val();
		$(this).focus(function() {
			if (_v == $(this).val())
			{
				$(this).val('');
			}
		}).blur(function() {
			if ($(this).val() == '')
			{
				$(this).val(_v);
			}
		});
	});
	
	$('.joinGroup').click(function() {
		$.getJSON('group.php?a=attention_op&group_id='+gid+'&type_op=1', function(data) {
			if (data.err == 1) {
				alert(data.msg);
			}else if (data.err == 0) {
				alert(data.msg);
				location.reload();
			}
		});
	});
	$('.ourGroup').click(function() {
		$.getJSON('group.php?a=attention_op&group_id='+gid+'&type_op=0', function(data) {
			if (data.err == 1) {
				alert(data.msg);
			}else if (data.err == 0) {
				alert(data.msg);
				location.reload();
			}
		});
	});
	
	//赞操作
	$('.gtalk_digg').click(function() {
		var cid = $(this).attr('cid');
		var m = $(this).attr('m');
		var _self = $(this);
		$.getJSON('group.php?a=zan_op&cid='+cid+'&group_id='+gid+'&m='+m, function(data) {
			if (data.error)
			{
				alert(data.error);
			}
			if (data.msg == 1) {
				_self.addClass('gtalk_digg_v');
				_self.attr('m', 'drop');
				$('#digg_'+cid).html(data.count);
			}else {
				_self.removeClass('gtalk_digg_v');
				_self.attr('m', 'add');
				$('#digg_'+cid).html(data.count);
			}
		});
	});

	$(".awesay").click(function(){
		$.ajax({
			type : 'post',
			url : 'activity.php',
			dataType : 'html',
   			cache: false,
   			timeout: TIME_OUT,
			data : {
					ajax:1,
					content:$("#topic_name").html(),
		        	a: "topic_weibo"
		        },
			success : function(json) {
				var obj = new Function("return" + json)();
				eval(obj.callback);
			}
		});
		return false;
/**/
//alert('功能暂未开放！');
	//	$(".plugin_weibo").show(500);
	});
	
	$("#say_form").submit(function(){
		$.ajax({
			type : 'post',
			url : 'activity.php',
			dataType : 'html',
   			cache: false,
   			timeout: TIME_OUT,
			data : {
					ajax:1,
					content: this.content.value,
		        	a: "update_weibo"
		        },
			success : function(json) {
			//return false;
				var obj = new Function("return" + json)();
				eval(obj.callback);
			}
		});
		return false;
	});
	
	$(".plugin_weibo_item_more span").click(function(){
		$(".plugin_weibo_list").scrollTop(-1);
	});
	
	hg_say_add = function(html){
		$(".plugin_weibo_list").html(html);
		$(".plugin_weibo").show(500);
	};
	
	hg_say_show = function(html){
	//	alert(html);
	
		$(".plugin_weibo_list").append(html);
	};
});