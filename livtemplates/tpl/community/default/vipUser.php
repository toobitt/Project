{foreach $vipUser as $k => $v}
{code}
	if($v['filename'])
	{
		$avatar = $v['host'] . $v['dir'] . '50x50/' . $v['filepath'] . $v['filename'];
	}
	else
	{
		$avatar = DEFAULT_AVATAR1;
	}
	$userlink = "?uid=" . $v['id'];
{/code}
<li>
	<a href="{$userlink}"><img src="{$avatar}"></a>
	<div>
		<h4><a href="{$userlink}">{$v['username']}</a></h4>
		<p id="target_{$v['id']}">{if $v['is_friend'] == 0}
				<a href="javascript:void(0);" id="delBlock">解除黑名单</a>
		{/if}
		{if $v['is_friend'] == 1}
			<a href="javascript:void(0);" onclick="delFriends({$v['id']},'vip',{$is_my_page})">-关注</a>&nbsp;&nbsp;<a>相互关注</a>
		{/if}
		{if $v['is_friend'] == 2}
			<a href="javascript:void(0);" onclick="delFriends({$v['id']},'vip',{$is_my_page})">-关注</a>&nbsp;&nbsp;<a>已关注</a>
		{/if}						
		{if $v['is_friend'] == 3 || $v['is_friend'] == 4}
			<a href="javascript:void(0);" onclick="addFriends({$v['id']},{$v['is_friend']},'vip',{$is_my_page})">+关注</a>
		{/if}</p>					
		
		<!--<p><a href="#">10个间接关注人</a></p>-->
	</div>
</li>
{/foreach}