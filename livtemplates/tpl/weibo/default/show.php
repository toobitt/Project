<?php
/*
 * $Id: show.php 2468 2011-10-31 21:07:36Z repheal $
 */
?>
{template:head}
<style type="text/css">
.comment .middle{width:493px;}
</style>
<script type="text/javascript">
setFous = function(sid,uid)
{
	if(!uid)
	{
		location.href = SNS_UCENTER + "login.php";
	}
	else
	{
		$("#comm_text_"+sid). focus();
	}
}
</script>
{code}
	$text = hg_verify($statusline['text']);
	$text_show = '：'.($statusline['text']?$statusline['text']:$_lang['forward_null']);
{/code}
{if $statusline['reply_status_id']}
	{code}
		$forward_show = '//@'.$statusline['user']['username'].' '.$text_show;
		$title = $_lang['forward_one'].$statusline['retweeted_status']['text'];
		$uid = $statusline['reply_user_id'];
	{/code}
	{else}
	{code}
		$forward_show = '';
		$title = $_lang['forward_one'].$statusline['text'];
		$uid = $statusline['member_id'];
	{/code}
{/if}
<div class="content clear" id="mid_{$statusline['id']}">
	
	<div class="content-left">
		<p class="weibo_bor1"></p>
		<div style="background:#fff;height:auto;border-left:1px solid #CCCCCC;border-right:1px solid #CCCCCC;" >
			<ul>
				<li class="clear liv_commemt" > 	
					<div style="float:right;width:565px;">	
						<p class="subject">
							{code} echo  hg_verify($statusline['text']);{/code}
						</p>
						{code} 
							$value = array();
							$value = $statusline; 
							$transmit_info = array();
							$transmit_info = $statusline['retweeted_status'];
						{/code}
		
							{template:unit/statusline_content}
						<div class="speak clear"> 
							<div class="hidden" id="t_{$statusline['id']}">{code} echo hg_verify($title);{/code}</div>
							<div class="hidden" id="f_{$statusline['id']}">{$forward_show}</div>
							<span style="position: relative;">
								<a onclick="OpenForward('{$statusline['id']}','{$uid}')" href="javascript:void(0);">转发({code} echo $statusline['transmit_count'] + $statusline['reply_count']; {/code})</a> 
					            <a id="fa{$statusline['id']}" onclick="favorites('{$statusline['id']}','{$_user['id']}')" href="javascript:void(0);">收藏</a>
					            <a onclick="setFous({$status_id},{$_user['id']})" href="javascript:void(0);">评论(<span id="coms_{$statusline['id']}">{$statusline['comment_count']}</span>)</a>
						    </span>
						    <strong>{code} echo hg_get_date($statusline['create_at']);{/code}</strong>
							<strong>来自 {$statusline['source']}</strong> 
							{if $_user['id']}
							<strong><a href="javascript:void(0);" onclick="report_play({$statusline['id']},{$statusline['user']['id']});">{$_lang['report']}</a></strong>
							{/if}
							
							<div style="display:none;" id="cons_{$statusline['id']}_{$statusline['user']['id']}">
							{code} echo  hg_verify($statusline['text']);{/code}
							{template:unit/statusline_content}</div>
							<div style="display:none;" id="ava_{$statusline['id']}_{$statusline['user']['id']}">{$statusline['user']['small_avatar']}</div>
							<div style="display:none;" id="user_{$statusline['id']}_{$statusline['user']['id']}">{$statusline['user']['username']}</div>
							<div style="display:none;" id="url_{$statusline['id']}_{$statusline['user']['id']}">{code} echo SNS_MBLOG.'show.php?id='.$statusline['id'];{/code}</div>
							<div style="display:none;" id="type_{$statusline['id']}_{$statusline['user']['id']}">3</div>
						
						</div>
					</div>
						<input type="hidden" value="0" id="cnt_comm_{$statusline['id']}" name="count_comm" /> 
					<div style="width: 580px;" class="comment_list " id="comment_list_{$statusline['id']}">				
					</div>
				</li>
				<li class="clear liv_commemt" >
				{template:unit/show_comment_detail}
				</li> 
			</ul>
		</div> 
		
		{$showpages}	
	</div>

	<div class="content-right">
		<div class="pad-all">
		{code}
			$user_info = array();
			$user_info = $statusline['user'];
			$topic = $status->getTopic();
		{/code}
		{template:unit/userImage}
		{template:unit/userInfo}
		</div>
	</div>
	</div>
 
{template:unit/forward}
<div class="pop" id="pop">
<span style="font-size:12px;color:#0082CB;width:auto;" onclick="closevideo()">关闭</span>
<div id="pop_s"></div>
</div>	
{template:foot}