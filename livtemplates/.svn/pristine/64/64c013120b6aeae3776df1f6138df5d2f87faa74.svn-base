//ajax返回也不判断是否成功
$(function() {
	var _v = $('textarea[name="replyContent"]').val();
	
	$('#reply_submit').click(function() {
		var thread_id = $('input[name="thread_id"]').val();
		var post_id = $('input[name="post_id"]').val();
		var fnum = $('input[name="fnum"]').val();
		var reply_user_id = $('input[name="reply_user_id"]').val();
		var reply_user_name = $('input[name="reply_user_name"]').val();
		var reply_des = $('input[name="reply_des"]').val();
		var replyContent = $('textarea[name="replyContent"]').val();
		if (replyContent == _v)
		{
			jAlert('请正确输入回复信息', '提示');
			return false;
		}else {
			$('textarea[name="replyContent"]').val(_v);
		}
		$.post('thread.php', {
			'a' : 'reply',
			'ajax' : '1',
			'thread_id' : thread_id,
			'post_id' : post_id,
			'fnum' : fnum,
			'content' : replyContent,
			'user_id' : reply_user_id,
			'reply_user_name' : reply_user_name,
			'reply_des' : reply_des,
			'mt' : (+new Date())
		}, function(data) {
			jAlert(data.msg, '提示', function () {
				location.reload();
			});
		}, 'json');
	});
	
	$('#replyContent').focus(function() {
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

function show(obj)
{
	var args = obj.getAttribute('args');
	argsArr = args.split('|');
	var fnum = argsArr[0];
	var post_id = argsArr[1];
	var user_name = argsArr[2];
	var user_id = argsArr[3];
	var pagetext = argsArr[4];
	$('input[name="fnum"]').val(fnum);
	$('input[name="post_id"]').val(post_id);
	$('input[name="reply_user_id"]').val(user_id);
	$('input[name="reply_user_name"]').val(user_name);
	$('input[name="reply_des"]').val(pagetext);
	var html = '<cite class="reply_cite">回复'+fnum+'楼'+user_name+':</cite> '+pagetext;
	$('#quoteCon').html(html);
}
