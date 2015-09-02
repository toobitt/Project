{if !empty($comment)}
{foreach $comment as $k => $v}
<li>
	<div class="user-pic"><a href="#"><img src="{code} echo hg_bulid_img($v['member']['avatar'],30,30);{/code}" /></a></div>
	<p class="twitter-title"><span style="float: right;">{code}echo hg_get_date($v['create_time']);{/code}</span><a href="#" class="user-name">{code} echo $_user['id'] == $v['member']['id'] ? '我' : $v['member']['user_name']{/code}:</a>{$v['content']}</p>
</li> 
{/foreach}
{/if}
{if $hasMoreComment}
<span class="commentMoreBtn" sid="{$sid}"><a>浏览更多&raquo;</a></span>
<script type="text/javascript">
$(function() {
	var page = 2;
	$('.commentMoreBtn').click(function() {
		var _this = $(this);
		var _v = _this.html();
		var status_id = _this.attr('sid');
		$.ajax({
			type : 'get',
			url : 'user.php',
			data : 'a=get_more_comment&sid='+status_id+'&p='+page+'&ajax=1',
			cache : false,
			async : true,
			dataType : 'json',
			success : function(data) {
				var result = eval(data.callback);
				if (page < result.totalPages)
				{
					page++;
					_this.before(result.html);
					_this.html(_v);
				}
				else
				{
					_this.before(result.html);
					_this.remove();
				}
			},
			beforeSend : function() {
				_this.html('<br /><img src="'+RESOURCE_URL+'loading.gif" />');
			}
		});
	});

	function hg_load_more(data, total)
	{
		return {
			html : data,
			totalPages : total
		};
	}
});
</script>
{/if}













