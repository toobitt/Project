{foreach $statusline as $key => $value}
	{code}
		$user_url = hg_build_link('user.php' , array('user_id' => $value['member_id']));
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
		$text_show = hg_verify($text_show);
		$transmit_info=$value['retweeted_status'];
	{/code}
	<li class="clear" id="mid_{$value['id']}"  onmouseover="report_show({$value['id']},{$value['user']['id']});" onmouseout="report_hide({$value['id']},{$value['user']['id']});">
		<div style="display:none;" id="cons_{$value['id']}_{$value['user']['id']}">{$text_show}</div>
		<div style="display:none;" id="ava_{$value['id']}_{$value['user']['id']}">{$value['user']['small_avatar']}</div>
		<div style="display:none;" id="user_{$value['id']}_{$value['user']['id']}">{$value['user']['username']}</div>
		<div style="display:none;" id="url_{$value['id']}_{$value['user']['id']}">{code} echo SNS_MBLOG.'show.php?id='.$value['id'];{/code}</div>
		<div style="display:none;" id="type_{$value['id']}_{$value['user']['id']}">3</div>
		
		<div class="blog-content">
			<p class="subject clear"><a href="{code} echo SNS_UCENTER;{/code}{$user_url}">{$value['user']['username']}</a>
		{$text_show}<br/>
				
		</p>
			{template:unit/statusline_content}
			<div class="speak clear">
				<div class="hidden" id="t_{$value['id']}">{$title}</div>
				<div class="hidden" id="f_{$value['id']}">{$forward_show}</div>
					<span id = "fa{$value['id']}" style="position:relative;">
					{if $value['user']['id'] == $_user['id']}
					<a href="javascript:void(0);" onclick="unfshowd({$value['id']})">{$_lang['delete']}</a>|	
					{/if}	
				
					<a href="javascript:void(0);" onclick="OpenForward('{$value['id']}','{$status_id}')">{$_lang['forward']}{code}'('.($value['transmit_count']+ $value['reply_count']).')';{/code}</a>|
					<a  id="fal{$value['id']}" href="javascript:void(0);" onclick="favorites('{$value['id']}','{$_user['id']}')">{$_lang['collect']}</a>|
					<a href="javascript:void(0);" onclick="getCommentList({$value['id']},{$_user['id']})">{$_lang['comment']}(<span id="comm_{$value['id']}">{$value['comment_count']}</span>)</a>
				</span>
				<input class="timestamp" type="hidden" value="{$value['create_at']}" />
				<strong class="publishtime">{code} echo hg_get_date($value['create_at']);{/code}</strong>
				<strong class="overflow" style="max-width:230px">{$_lang['source']}{$value['source']}</strong>
				<a onclick="report_play({$value['id']},{$value['user']['id']});" href="javascript:void(0);" style="display:none;" id="re_{$value['id']}_{$value['user']['id']}">
				{$_lang['report']}</a>	
			</div> 
			<input type="hidden" name="count_comm" id="cnt_comm_{$value['id']}" value="{$value['comment_count']}"/>
			<div id="comment_list_{$value['id']}"></div>
		</div> 
		<a href="{code} echo SNS_UCENTER;{/code}{$user_url}">
		<img src="{$value['user']['middle_avatar']}"/>
		</a>
	</li>
{/foreach}