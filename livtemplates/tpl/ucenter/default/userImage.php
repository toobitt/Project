<?php
/*$Id: userImage.php 396 2011-07-28 00:52:08Z zhoujiafei $*/
?>
<div class="user">
{if $_user['id'] == $user_info['id']}
	<div class="user-set">
		<a href="<?php echo hg_build_link('userprofile.php'); ?>">个人设置</a>
		<a href="<?php echo hg_build_link('login.php' , array('a' => 'logout')); ?>">退出</a>
	</div> 
	{/if}
	<div class="user-name">
		<a href="<?php echo hg_build_link('user.php' , array('user_id' => $user_info['id'])); ?>">
			<h5>{$user_info['username']}</h5>
		</a> 
	</div>
	<a href="<?php echo hg_build_link('user.php' , array('user_id' => $user_info['id'])); ?>"><img title="{$user_info['username']}" src="{$user_info['middle_avatar']}"></a>
</div>