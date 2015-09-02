<?php
/*$Id: commentDetail.php 387 2011-07-26 05:31:22Z lijiaying $*/
?>
<style>
.commbox{width:600px;background:#fff;padding-left:12px;text-align:center;padding-top:10px;}
.commbox .text{clear:both;height:50px;width:500px;line-height:18px;}
.cc{width:600px;background:#a2e0d9;text-align:left;padding-left:13px;fonct-size:13px;height:23px;padding-top:5px;}
.details dl dd{border-bottom: 1px solid #CCCCCC;padding-top:10px;}
.details{width:600px;background:#fff;padding-left:15px;}
</style>
<div class="commbox">
	<div >
		<textarea id="comm_text_{$status_id}" class="text" onkeyup="check_opt({$status_id})"></textarea> 
		<div style="font-size: 12px; padding-right: 60px; text-align: left;" onclick="changevalue({$status_id})" ><input type="checkbox" style="color:#444;height:auto;width:auto;font-size:12px;" onclick="changevalue({$status_id})" value="0" id="transmit_to_mt{$status_id}" name="transmit_to_mt">同时发布到我的点滴</div>
	</div>
	<div style="text-align:right;padding-right:45px;padding-top:8px;padding-bottom:8px;"> 
		<input type="button" id = "commBtn{$status_id}" name="comm_sub" value="{$_lang['let_me_comm']}" onClick="pushAction({$status_id})" />
	</div>
</div>

<input type="hidden" name="push_flag" value="0" id="push_flag" />
<input type="hidden" name="num" id="num_status_{$status_id}" value="{code} echo intval($cnum);{/code}" />
	<input type="hidden" name="blogger" value="{$_user['id']}" />

<div class="cc">评论<span style="padding-right:2px;">共<a id="comm_{$status_id}">{code} echo intval($cnum);{/code}</a>条</span></div>	
<div class="details">
	<dl id="status_item_{$status_id}">  
		<dd style="border-bottom:0;" id="text_{$status_id}"></dd>  
		{if(intval $comments_arr[0]) != 0}
			{code}
				$cnum = $comments_arr[0]; 
				unset($comments_arr[0]);
			{/code}
			{foreach $comments_arr  as $key => $value}
			<dd id="co_{$value['id']}_{$value['user']['id']}">
				<span style="float:right;">
				<a href="javascript:void(0);" onClick = "replyC({$value['id']},{$value['member_id']},{$value['status']['id']})">{$_lang['reply']}</a>&nbsp;&nbsp;{if ($userid == $value['member_id']) || ($userid == $value['status']['member_id'])}<a href="javascript:void(0)" onClick="deleteC({$value['id']},{$value['status']['id']})" >{$_lang['del_comment']}</a>{/if}
				</span>
				<span id="tips_{$value['id']}"><input type="hidden" name="hid" value="" /> </span>
				<a href="{code} echo hg_build_link('user.php' , array('user_id' => $value['user']['id'])); {/code}"><img src="{$value['user']['small_avatar']}" /></a>
				<a href="{code} echo hg_build_link('user.php' , array('user_id' => $value['user']['id'])); {/code}">
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