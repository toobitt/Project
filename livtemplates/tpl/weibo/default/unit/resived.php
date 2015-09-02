<?php
/*$Id: resived.php 8320 2012-03-16 08:08:52Z repheal $*/  
?>
 
<style>
.show_comm{font-size: 12px; padding-top: 3px;}
.show_comm .input1{height: 0;width: 10px}
.show_comm label{font-size:12px;padding-left:2px;}
b.close_comm{ border: 1px solid #CCCCCC; color: #CCCCCC; cursor: pointer; font-size: 12px;padding: 1px 3px;}
</style>

{if $cnt != 0} 		 
<p class="ping_quan"><span>共 <strong id="totalSend" >{code} echo intval($cnt);{/code}</strong> 条</span> 
   <input type="checkbox" class="checks" name="sel_all" value=0 onClick="select_all(this,{$tag});" id="_bot_"/><label for="_bot_">{$_lang['selectAll']}&nbsp; </label>|&nbsp;<a href="javascript:void(0);" onClick="deleteMore({$_user['id']},{$tag});" >{$_lang['del_comment']}</a></p>
<ul class="ping_list">

	{foreach $sendCommArr as $key => $value}
		{if $value['status']['member_id'] == $_user['id']}  
			{code}
				$eend = '点滴';
				$flag = 1;
			{/code}
		{else}
	
			{code}
				$eend='评论';
				$flag=0;
			{/code}
		{/if}

	<li id="co_{$tag}_{$value['id']}" class="default" onmouseover="report_show({$value['id']},{$value['user']['id']});" onmouseout="report_hide({$value['id']},{$value['user']['id']});" >
		<div style="display:none;" id="cons_{$value['id']}_{$value['user']['id']}">{$text_show}</div>
		<div style="display:none;" id="ava_{$value['id']}_{$value['user']['id']}">{$value['user']['small_avatar']}</div>
		<div style="display:none;" id="user_{$value['id']}_{$value['user']['id']}">{$value['user']['username']}</div>
		<div style="display:none;" id="url_{$value['id']}_{$value['user']['id']}">{code} echo SNS_MBLOG.'show.php?id='.$value['id'];{/code}</div>
		<div style="display:none;" id="type_{$value['id']}_{$value['user']['id']}">8</div>

	{if $flag}<input type="checkbox" class="ping_checkbox  checks" name="resivedComments[]" value="{$value['id']}" onclick="addThis(this,{$tag})"/>{/if}
		<a href="{code} echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $value['member_id'])); {/code}" class="ping_img" title="{$value['user']['username']}">
			<img class="pic" src="{$value['user']['middle_avatar']}">
		</a> 
		 
		<div class="ping_list_right">
			<p class="title_name"><a href="{code} echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $value['user']['id'])); {/code}">{$value['user']['username']}</a><span>：{code} echo hg_verify($value['text']);{/code} </span> <span class="ping_date">({code} echo hg_get_date($value['create_at']);{/code})</span></p>
			<p class="huifu"><span class="huifu_span" id="speak_{$value['id']}_{$tag}">{if $_user['id']}<a onclick="report_play({$value['id']},{$value['user']['id']},{$value['status']['id']});" href="javascript:void(0);" style="display:none;" id="re_{$value['id']}_{$value['user']['id']}">{$_lang['report']}</a>&nbsp;{/if}<a href="javascript:void(0);" onclick="replyComment({$value['status']['id']},{$value['id']},{$tag})">{$_lang['reply']}</a>&nbsp;{if $flag}<a href="javascript:void(0)" onclick="deleteComment({$value['id']})">{$_lang['del_comment']}</a>&nbsp;{/if}</span>
			<span class="ping_date">回复了我的{$eend}：<a href="{code} echo hg_build_link('show.php' , array('id' => $value['status']['id'])); {/code}" >{if $flag}{code} echo hg_show_face($value['status']['text']);{/code}{else}{code} echo hg_show_face($value['reply_comment_text']);{/code}{/if}</a></span>
			<input type="hidden" name="commids" value="{$value['id']}" id="commids_{$value['id']}_{$tag}" /> 
			<input type="hidden" id="rp_{$value['id']}_{$tag}" name="replyUser" value="{$value['user']['id']}_{$value['user']['username']}" />
			<input type="hidden" name="myself" value="{$_user['id']}_{$_user['username']}_{$value['status']['id']}" />
			</p>
			
<!--			<p><span class="ping_date">5分钟前 来自IPHONE</span></p>-->
		</div>
		<br class="clear">
	</li>
	{/foreach}
</ul>
<p class="ping_quan pingbg"><input type="checkbox" class="checks" name="sel_all" value=0 onClick="select_all(this,{$tag});" id="_bot_"/><label for="_bot_">{$_lang['selectAll']}&nbsp; </label>| &nbsp;<a href="javascript:void(0);" onClick="deleteMore({$_user['id']},{$tag});" >{$_lang['del_comment']}</a></p>
 
{else}
	{code}
		$null_title = "";
		$null_text = '暂无回复';
	{/code}
	{template:unit/null}
{/if}
 <input type="hidden" value="" id="sendStr_{$tag}" name="count_comm" />

 
	









































