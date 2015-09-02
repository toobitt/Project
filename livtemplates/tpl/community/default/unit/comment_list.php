{if is_array($comment) && !empty($comment)}
{foreach $comment as $k => $v}
{code}
	if($v['user']['host'])
	{
		$avatar_link = $v['user']['host'] . $v['user']['dir'] . '29x29/'. $v['user']['filepath'] . $v['user']['filename'];
	}
	else
	{
		$avatar_link = ROOT_DIR . "img/my_img22.jpg";
	}
	$content = hg_verify($v['content']);
{/code}
<li class="clearfix" id="comment_li_{$v['id']}">
	<img src="{$avatar_link}" width="29" height="29"/>
	<div class="t_comment_det">
		<a href="#" class="t_comment_author">{$v['user']['nick_name']}:</a>
		{$content}
		{if $is_my_page || $v['is_self']}
		<a href="###" class="t_comment_delete" onclick="delete_comment({$v['id']},{$status_id}, this);">删除</a>
		{/if}
		<a href="###" class="t_comment_reply" onclick="reply_comment({$v['id']},{$status_id});">回复</a>
	</div>
</li>
{/foreach}
{/if}