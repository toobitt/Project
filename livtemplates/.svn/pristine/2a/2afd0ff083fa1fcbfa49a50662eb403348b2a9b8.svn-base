<?php
/*$Id: userImage.php 388 2011-07-26 05:32:48Z lijiaying $*/
?>
<div class="bk-top1">
	{if $_user['id'] == $user_info['id']}我{else}TA{/if}的资料</div>
<div class="wb-block1">
	<div class="user">
	<div class="user-set">
		<h5><a href="{code} echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $user_info['id']));{/code}">
				{$user_info['username']}
			</a> </h5>
		<div class="user-name" style="margin-right:90px;_margin-right:45px;">
			{if $user_info['group_name']}<div style="font-size:12px;color:gray;">所在地盘：<a style="color:#0164CC" href="{code} echo hg_build_link(SNS_TOPIC . '?m=thread&group_id=' . $user_info['group_id']);{/code}">{$user_info['group_name']}</a></div>{/if}
		</div>
		</div> 
		
		<a href="{code} echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $user_info['id'])); {/code}"><img title="{$user_info['username']}" src="{$user_info['middle_avatar']}"></a>
	</div>