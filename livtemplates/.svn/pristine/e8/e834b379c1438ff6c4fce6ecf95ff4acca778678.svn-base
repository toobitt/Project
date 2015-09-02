<?php
/*$Id: show_comment_detail.php 2471 2011-10-31 21:29:13Z repheal $*/
?>
<style>
.commbox{width:565px;text-align:center;padding-top:10px;}
.commbox .text{clear:both;
border:1px solid #AAADB2;
height:25px;
margin-top:10px;
width:548px;}
.cc{width:548px;background:#BDDAFA;text-align:left;padding-left:13px;fonct-size:13px;height:23px;padding-top:5px;}
.details dl dd{border-bottom: 1px solid #CCCCCC;padding-top:10px;padding-bottom:10px;}
.details{width:560px;background:#fff;}
.details a img{padding:2px;border:1px solid #ccc;}
</style>
{if $_user['id']}

<div class="commbox">
	<div >
		<input id="comm_text_{$status_id}" class="text" onkeyup="check_opt({$status_id})" />
		<div style="font-size:12px;text-align:right;line-height: 23px;margin-top:10px;margin-bottom:10px;position: relative;">
		<a  onclick="global_face('comm_text_{$status_id}','com_face_{$status_id}');" href="javascript:void(0);" class="choiceface">
			<img alt="" src="{code} echo RESOURCE_DIR;{/code}img/smiles/17.gif">
		</a>
		<span onclick="changevalue({$status_id})" style="padding:7px 0px;font-size:12px;cursor:pointer;">
		{code}
			$checked = $_settings['default_sync']['comm_main']?' checked':'';
		{/code}
		<input onclick="changevalue({$status_id})" type="checkbox" style="color:#444;height:auto;width:auto;font-size:12px;margin-top:10px;vertical-align: top;" value="0" id="transmit_to_mt{$status_id}" name="transmit_to_mt" {$checked}>&nbsp;同时发布到我的点滴&nbsp;</span>
		<div id="com_face_{$status_id}" class="face_content" style="position: absolute; visibility: visible; top: 25px; left: 300px;display:none;text-align: left;"></div>
		<input type="button" style="height:28px;" id = "commBtn{$status_id}" name="comm_sub" value="{$_lang['let_me_comm']}" onClick="pushAction({$status_id})" /></div>		
	</div>
</div>
{/if}

<input type="hidden" name="push_flag" value="0" id="push_flag" />
<input type="hidden" name="num" id="num_status_{$status_id}" value="{code} echo intval($cnum);{/code}" />
	<input type="hidden" name="blogger" value="{$_user['id']}" />

<div class="cc">评论<span style="padding-right:2px;">共<span id="comm_{$status_id}">{code} echo intval($comments_arr[0]);{/code}</span>条</span></div>	
<div class="details">
	<dl id="status_item_{$status_id}">  
		<dd style="border-bottom:0;" id="text_{$status_id}"></dd> 
		{if intval($comments_arr[0]) != 0}
			{code}
				$cnum = $comments_arr[0]; 
				unset($comments_arr[0]); 
			{/code}
			{foreach $comments_arr  as $key => $value}
			<dd id="co_{$value['id']}_{$value['user']['id']}" onmouseover="report_show({$value['id']},{$value['user']['id']});" onmouseout="report_hide({$value['id']},{$value['user']['id']});">
				<a name="{$value['id']}" id="{$value['id']}"></a>
				<div style="display:none;" id="cons_{$value['id']}_{$value['user']['id']}">{code} echo hg_verify($value['content']);{/code}</div>
				<div style="display:none;" id="ava_{$value['id']}_{$value['user']['id']}">{$value['user']['small_avatar']}</div>
				<div style="display:none;" id="user_{$value['id']}_{$value['user']['id']}">{$value['user']['username']}</div>
				<div style="display:none;" id="url_{$value['id']}_{$value['user']['id']}">{code} echo SNS_MBLOG.'show.php?id='.$value['status']['id'] .'#'.$value['id'];{/code}</div>
				<div style="display:none;" id="type_{$value['id']}_{$value['user']['id']}">8</div>
				
				<span style="float:right;">
				{if $_user['id']}<a onclick="report_play({$value['id']},{$value['user']['id']});" href="javascript:void(0);" style="display:none;" id="re_{$value['id']}_{$value['user']['id']}">{$_lang['report']}</a>&nbsp;<a href="javascript:void(0);" onClick = "replyC({$value['id']},{$value['member_id']},{$value['status']['id']})">{$_lang['reply']}</a>&nbsp;{/if}{if ($userid == $value['member_id']) || ($userid == $value['status']['member_id'])}<a href="javascript:void(0)" onClick="deleteC({$value['id']},{$value['status']['id']})" >{$_lang['del_comment']}</a>&nbsp;{/if}</span>
				<span id="tips_{$value['id']}"><input type="hidden" name="hid" value="" /> </span>
				<a href="{code} echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $value['user']['id']));{/code}"><img src="{$value['user']['small_avatar']}" /></a>
				<a href="{code} echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $value['user']['id']));{/code}">
					{$value['user']['username']}
				</a>：{code} echo hg_verify($value['content']);{/code}
				<span>
					{code} echo hg_get_date($value['create_at']);{/code}
					<input type="hidden" id="user_{$value['member_id']}_{$value['id']}" name="user_{$value['member_id']}" value="{$value['user']['username']}" />
				</span>
			</dd> 	
			{/foreach}
	</dl>
		{/if}
</div>