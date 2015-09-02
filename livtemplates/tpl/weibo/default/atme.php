<?php 
/* $Id: atme.php 8320 2012-03-16 08:08:52Z repheal $ */
?>
{template:head}
<input type="hidden" value="update" name="a" id="a"/>
<input type="hidden" value="点滴" name="source" id="source"/>
{template:unit/forward}
<div class="content clear" id="equalize">
<div class="content-left">	
{if !empty($statusline)&&is_array($statusline)}
{template:unit/statusline_one}
{$showpages}
{else}
	{code}
		$null_title = "真不给力，SORRY!";
		$null_text = "没有提及您的点滴！";
	{/code}
	{template:unit/null}
{/if}
</div>
<div class="content-right ">
	<div class="pad-all">
	<div class="bk-top1">我的资料</div>
	<div class="wb-block1">
{if $_user['id'] > 0}
	<div class="user">
	<div class="user-set">
		<h5><a href="{code} echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $user_info['id']));{/code}">{$user_info['username']}</a></h5>
		<a href="{code} echo hg_build_link(SNS_UCENTER.'userprofile.php');{/code}">个人设置</a>
		<a href="{code} echo hg_build_link(SNS_UCENTER.'login.php' , array('a' => 'logout'));{/code}">{$_lang['logout']}</a>
		<div class="user-name" style="width:270px;">
			<a>{$user_info['location']}</a>
		</div>
	</div> 
	<a href="<?php echo hg_build_link(SNS_UCENTER.'avatar.php'); ?>"><img src="{$user_info['middle_avatar']}" title="{$user_info['username']}>" /></a>
	</div>
	<!-- load userInfo  -->
	{template:unit/userInfo}
	</div>
	<!-- end load -->
{/if}
</div> 
</div>
</div>
{template:foot}
