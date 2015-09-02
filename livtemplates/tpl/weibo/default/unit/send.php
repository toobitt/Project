<?php
/*$Id: send.php 8320 2012-03-16 08:08:52Z repheal $*/
?>
{if $cnt != 0}
<p class="ping_quan"><span>共<strong id="totalSend" >{code} echo intval($cnt);{/code}</strong>条</span>
	  <input type="checkbox" name="sel_all" value=0 class="checks" onClick="select_all(this,{$tag});" id="_top_" />&nbsp;<label for="_top_" style="cursor:pointer;">{$_lang['selectAll']}</label>&nbsp; | &nbsp;<a href="javascript:void(0);" onClick="deleteMore({$_user['id']},{$tag});" >{$_lang['del_comment']}</a></p>
<ul class="ping_list">		

		{foreach $sendCommArr as $key => $value}
			{if $value['reply_comment_id'] != 0}
				{code}
				$begin = '回复了';
				$eend = '评论';
				$flag = 0;
				{/code}
			{else}
				{code}
				$begin = '评论了';
				$eend='点滴';
				$flag=1;
				{/code}
			{/if} 

			<li class="default" id="co_{$tag}_{$value['id']}" onmouseover="report_show({$value['id']},{$value['user']['id']});" onmouseout="report_hide({$value['id']},{$value['user']['id']});"> 
				<div style="display:none;" id="cons_{$value['id']}_{$value['user']['id']}">{$text_show}</div>
				<div style="display:none;" id="ava_{$value['id']}_{$value['user']['id']}">{$value['user']['small_avatar']}</div>
				<div style="display:none;" id="user_{$value['id']}_{$value['user']['id']}">{$value['user']['username']}</div>
				<div style="display:none;" id="url_{$value['id']}_{$value['user']['id']}">{code} echo SNS_MBLOG.'show.php?id='.$value['id'];{/code}</div>
				<div style="display:none;" id="type_{$value['id']}_{$value['user']['id']}">8</div>

				<input type="checkbox" class="ping_checkbox  checks" name="sendComments[]" value="{$value['id']}" onclick="addThis(this,{$tag});"/> 
				<a href="{code} echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $value['status']['user']['id'])); {/code}"  class="ping_img" title="{$value['status']['user']['username']}">
					<img src="{$value['status']['user']['middle_avatar']}">
				</a>
				<div class="ping_list_right">
					<p class="title_name">
						<span>{code} echo hg_verify($value['text']);{/code}</span> <span class="ping_date">({code} echo hg_get_date($value['create_at']);{/code})</span>
						<input type="hidden" name="commids" value="{$value['id']}" id="commids_{$value['id']}_{$tag}" />
					</p>
					<p class="huifu" id="confirm_{$value['id']}"><span class="huifu_span">{if $_user['id']}<a onclick="report_play({$value['id']},{$value['user']['id']},{$value['status']['id']});" href="javascript:void(0);" style="display:none;" id="re_{$value['id']}_{$value['user']['id']}">{$_lang['report']}</a>&nbsp;{/if}<a href="javascript:void(0);" onClick="deleteComment({$value['id']},{$tag});" >{$_lang['del_comment']}</a></span>
						<span class="ping_date">{$begin}<a href="{code} echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $value['status']['user']['id'])); {/code}">{$value['status']['user']['username']}</a>{$eend}:<a href="{code} echo hg_build_link('show.php' , array('id' => $value['status']['id'])); {/code}" >{code} echo $text = ($flag == 1) ? hg_show_face($value['status']['text']) : hg_show_face($value['reply_comment_text']);{/code}</a></span>
					</p>  
				</div>
				<br class="clear" />
			</li>

		{/foreach}
		</ul>
		<p class="ping_quan pingbg">
		 <input type="checkbox" name="sel_all" value=0 class="checks" onClick="select_all(this,{$tag});" id="_bot_" />&nbsp;<label for="_bot_" style="cursor:pointer;">{$_lang['selectAll']}</label>&nbsp; | &nbsp;<a href="javascript:void(0);" onClick="deleteMore({$_user['id']},{$tag});" >{$_lang['del_comment']}</a></p>
  		
{else}
	{code}
		$null_title = "";
		$null_text = '暂无评论';
	{/code}
	{template:unit/null}
{/if}
 	<input type="hidden" value="" id="sendStr_{$_input['t']}" name="count_comm" />
