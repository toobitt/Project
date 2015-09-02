{if is_array($statusline) && !empty($statusline)}
	{foreach $statusline as $key => $value}
	{code}
		$create_time = date('Y-m-d H:i',$value['create_at']);
		$text_show = hg_verify($value['text'] ? $value['text'] :'暂无');
		$username = $username ? $username : $value['user']['nick_name'];
	{/code}
		<div class="plugin_weibo_item">
			<div class="plugin_weibo_item_top">
				<a href="#" class="plugin_weibo_item_author">{$username}</a>
				{$text_show}
			</div>
			<div class="plugin_weibo_item_time">{$create_time}</div>
		</div>
	{/foreach}
{/if}