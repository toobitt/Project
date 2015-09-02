//报名
$(function() {
	$('#join_action').click(function() {
		var self = $(this);
		var action_id = self.attr('aid');
		var url = 'activity.php?a=apply&ajax=1&action_id='+action_id+'&mt='+(+new Date());
		$.getJSON(url, function(data) {
			if (data.msg) {
				if(data.status==0 || data.status==2)
				{
					self.html('已参加');
					self.removeAttr('id');
					self.removeAttr('aid');
					var yet = parseInt($('.yet_join').html());
					yet++;
					$('.yet_join').html(yet+'人参加');
					
				}
				else if(data.status==1)
				{
					self.html('审核中');
					self.removeAttr('id');
					self.removeAttr('aid');
				}
				self.attr('class','atopic_btn_join  gray_btn');
				var counter = parseInt($('.atopic_count span:first-child').html());
				counter++;
				$('.atopic_count span:first-child').html(counter+'人报名');	
			}
		});
	});
});
//九宫图
$(function ($) {
	var timer = null,
	    action_pic = $('.action_pic'),
	    bigPic = action_pic.find('.action_big_pic img'),
	    beginSrc = bigPic.attr('src');
	action_pic
		.on( 'mouseover', '.action_small_pic li', function (e) {
			if ( timer ) {
				clearTimeout(timer);
				timer = null;
			}
			bigPic.attr( 'src', $(e.target).data('src') );
		}).on( 'mouseout', '.action_small_pic li', function () {
			timer = setTimeout(function () { bigPic.attr( 'src', beginSrc ); }, 300);
		});
});
//关注的js
$(function($) {
	var view = $('#join_love'),
		joinView = $('.atopic_count span:last-child');
	view.click(function () {
		if ( view.hasClass('active') ) {
			return;
		}
		var type = view.attr('bid');
		view.addClass('active');
		$.getJSON(
			'activity.php?a=collect&ajax=1&action_id=' + view.attr('aid') + '&type=' + type + '&mt=' + (+new Date()),
			function (data) {
				if (type > 0) {
					view
					    .html('关注')
					    .attr('bid', data.have_apply)
					    .attr('class','atopic_btn_love');
					joinView.html(
						parseInt(joinView.text()) - 1 + '人关注'
					);
				} else {
					if (data.id > 0) {
						view
							.html('取消关注')
							.attr('bid',data.id)
							.attr('class','atopic_btn_love gray_btn');
						joinView.html(
							parseInt(joinView.text()) + 1 + '人关注'
						);
					} else {
						view.removeClass('active');
					}
				}
			}
		)
	});
});

//赞的js
$(function($) {
	var counter = $('#praisecount');
	$('.atopic_aside').on('click', '.atopic_digg_btn:not(.active)', function(e) {
		var me = $(this), url, callback;
		me.addClass('active');
		if ( me.hasClass('atopic_btn_digg') ) {
			url = 'activity.php?a=memberPraiseAdd&ajax=1&action_id=' + me.attr('aid') + '&mt=' + (+new Date());
			callback = function (data) {
				if (data > 0) {
					me
						.removeClass('active atopic_btn_digg')
						.addClass('atopic_btn_already_digg');
					counter.html(
						parseInt(counter.text()) + 1 + '赞'
					);
				}
				
			}
		} else {
			url = 'activity.php?a=memberPraiseDelete&ajax=1&action_id=' + me.attr('aid') + '&mt=' + (+new Date());
			callback = function (data) {
				if (data) {
					me
						.removeClass('active atopic_btn_already_digg')
						.addClass('atopic_btn_digg');
					counter.html(
						parseInt(counter.text()) - 1 + '赞'
					);
				}
			}
		}
		$.getJSON(url, callback);
	});
});

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
		jAlert('请正确输入回复信息');
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
			jAlert(data.msg);
			var reply_num = $('#reply_'+thread_id).text();
			$('#reply_'+thread_id).text(parseInt(reply_num)+1);
			//location.href = 'thread.php?thread_id=' + thread_id + '#reply' + data.post_id;
		}else {
			jAlert(data.msg);
		}
	}, 'json');
}
$(function() {
	var allReplyBtn = $('.gtalk_reply_content').siblings('.gtalk_reply_btn').hide();
	$('.gtalk_reply_content').each(function() {
		var me = $(this),
		    _v = me.val(),
			replyBtn = me.siblings('.gtalk_reply_btn');
		me.focus(function() {
			if (_v == me.val()) me.val('');
			allReplyBtn.hide();
			replyBtn.show();
		}).blur(function() {
			if (me.val() == '') {
				me.val(_v);
				//replyBtn.hide();
			}
		});
	});
	
	//帖子赞操作
	$('.gtalk_digg').click(function() {
		var cid = $(this).attr('cid');
		var m = $(this).attr('m');
		var _self = $(this);
		$.getJSON('activity.php?a=zan_op&cid='+cid+'&group_id='+gid+'&m='+m, function(data) {
			if (data.error)
			{
				jAlert(data.error);
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
//jAlert('功能暂未开放！');
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
	//	jAlert(html);
	
		$(".plugin_weibo_list").append(html);
	};
});	

