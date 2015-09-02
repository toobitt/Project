<div class="pop" id="pop">
<span style="font-size:12px;color:#0082CB;width:auto;" onclick="closevideo()">关闭</span>
<div id="pop_s"></div>
</div>	
<div class="news-latest">
<div class="tp"></div>
<div class="md"></div>
</div>
<ul class="list clear">
{foreach $statusline as $key => $value}
	{code}
	$user_url = hg_build_link(USER_URL, array('user_id' => $value['member_id']));
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

	<li class="clear" id="mid_{$value['id']}">
		<div class="blog-content">
			<p class="subject clear"><a href="{$user_url}">{$value['user']['username']}</a>
		{$text_show}<br/>	
		</p>
{template:unit/statusline_content}
			<div class="speak clear">
				<div class="hidden" id="t_{$value['id']}">{code} echo hg_verify($title);{/code}</div>
				<div class="hidden" id="f_{$value['id']}">{$forward_show}</div>
					<span id = "fa{$value['id']}" style="position:relative;">
					{if $value['user']['id'] == $user_info['id']}
					<a href="javascript:void(0);" onclick="unfshowd({$value['id']})">{$_lang['delete']}</a>|	
					{/if}
					<a href="javascript:void(0);" onclick="OpenForward('{$value['id']}','{$status_id}')">{$_lang['forward']}({code} echo $value['transmit_count']+ $value['reply_count']{/code})</a>|
					<a  id="fal{$value['id']}" href="javascript:void(0);" onclick="favorites('{$value['id']}','{$_user['id']}')">{$_lang['collect']}</a>|
					<a href="javascript:void(0);" onclick="getCommentList({$value['id']},{$_user['id']})">{$_lang['comment']}(<span id="comm_{$value['id']}">{$value['comment_count']}</span>)</a>
				</span>
				<strong date="{$value['create_at']}" >{code} echo hg_get_date($value['create_at']){/code}</strong>
				<strong>{$_lang['source']}{$value['source']}</strong>
			</div> 
			<input type="hidden" name="count_comm" id="cnt_comm_{$value['id']}" value="{$value['comment_count']}"/>
			<div id="comment_list_{$value['id']}"></div>
		</div> 
		<a href="{$user_url}">
		<img src="{$value['user']['middle_avatar']}"/>
		</a>
	</li>
{/foreach}
 <li class="more">{$showpages}</li>
 </ul>