{code}
	$info  = $formdata['info'];
	$total = $formdata['total'];
{/code}
{if !empty($info)}
	{foreach $info AS $v}
	<li _total="{$total}">
		<a _id="custom_name_{$v['appid']}" _appid="{$v['appid']}" _appname="{$v['custom_name']}" href="javascript:;" title="{$v['custom_name']}">{$v['custom_name']}</a>
	</li>
	{/foreach}
{else}
	暂无数据
{/if}