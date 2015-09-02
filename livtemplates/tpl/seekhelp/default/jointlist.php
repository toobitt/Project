{if $formdata}
{foreach $formdata as $v}
<div class="m2o-item user-list">
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
		<span class="tel">{$v['tel']}</span>
	</div>
	<div class="list-main">
		<span class="user-name">{$v['name']}</span>
	</div>
</div>
{/foreach}
{/if}