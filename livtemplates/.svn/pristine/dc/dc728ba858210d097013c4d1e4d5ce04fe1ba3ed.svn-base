
{foreach $statusline as $key => $value}
	{code}
		$user_url = hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $value['member_id']));
		$len = strlen('#' . $keywords . '#');
		$value['text'] = substr(trim($value['text']), $len);
		$text = hg_verify($value['text']);
		$text_show = '：'.($value['text']?$value['text']:$_lang['forward_null']);
	{/code}
	{if $value['reply_status_id']}

		{code}
			$forward_show = '//@'.$value['user']['username'].' '.$text_show;
			$title = $_lang['forward_one'].$value['retweeted_status']['text'];
			$status_id = $value['reply_user_id'];
		{/code}
	
	{else}
	
		{code}
			$forward_show = '';
			$title = $_lang['forward_one'].$value['text'];
			$status_id = $value['member_id'];
		{/code}
	{/if}
	{code}
		$text_show =hg_match_red(hg_verify($text_show),$keywords);
		$transmit_info=$value['retweeted_status'];
	{/code}

		
		<li>
			<span class="zhibo_huifu"><a href="javascript:void(0);" onclick="disreplyStatus({$value['id']}, '{$value['user']['username']}');return false;">回复</a></span>
			<a href="{$user_url}" class="zhibo_name" target="_blank">{$value['user']['username']}</a>：
	
			{if $_input['is_comment']}
			 
			<span class="zhibo_detail" style="color:black;">{$text}</span>
			{else}
			<span class="zhibo_detail" >{$text}</span>
			{/if}	
			<span class="zhibo_time">{code} echo hg_get_date($value['create_at']);{/code}</span>
		</li>
{/foreach}
