{if $formdata}
{foreach $formdata as $v}
<div class="m2o-item" _id={$v['id']}>
	<div class="list-left">
	{if $v['avatar']}
	{code}
		
		$avatar = $v['avatar']['host'].$v['avatar']['dir'].'40x30/'.$v['avatar']['filepath'].$v['avatar']['filename']
	{/code}
		<img src="{$avatar}">
	{else}
		<img src="">
	{/if}
	</div>
	<div class="list-right">
		<a class="recommend-icon">推荐</a>
	</div>
	<div class="list-main">
		<span class="user-name">{$v['member_name']}</span>
		<span class="comment-time">{$v['format_create_time']}</span>
		<p class="comment-content">{$v['content']}</p>
	</div>
</div>
{/foreach}
{/if}