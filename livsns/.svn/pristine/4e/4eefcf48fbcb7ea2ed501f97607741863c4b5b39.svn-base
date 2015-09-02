<?php
/*$Id:$*/
?>
<div class="user">
<?php if($this->user['id'] == $user_info['id']){?>
	<div class="user-set">
		<a href="<?php echo hg_build_link('userprofile.php'); ?>">个人设置</a>
		<a href="<?php echo hg_build_link('login.php' , array('a' => 'logout')); ?>">退出</a>
	</div> 
	<?php }?>
	<div class="user-name">
		<a href="<?php echo hg_build_link('user.php' , array('user_id' => $user_info['id'])); ?>">
			<h5><?php echo $user_info['username']?></h5>
		</a> 
	</div>
	<a href="<?php echo hg_build_link('user.php' , array('user_id' => $user_info['id'])); ?>"><img title="<?php echo $user_info['username'];?>" src="<?php echo $user_info['middle_avatar'];?>"></a>
</div>