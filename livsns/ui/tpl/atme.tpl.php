<?php 
/* $Id: atme.tpl.php 3431 2011-04-07 09:32:55Z repheal $ */
?>

<?php include hg_load_template('head');?>

<input type="hidden" value="update" name="a" id="a"/>
<input type="hidden" value="点滴" name="source" id="source"/>
<?php include hg_load_template('forward');?>

    <div class="content clear" id="equalize">
    <div class="content-left">
	

<?php
if (!empty($statusline)&&is_array($statusline))
{
?>
<?php include hg_load_template('statusline_one');?>
<?php
echo $showpages;
}
else
{
echo hg_show_null('真不给力，SORRY!',"没有提及您的点滴！");
}
?>
</div>

<div class="content-right ">

	<div class="pad-all">
	<div class="bk-top1">我的资料</div>
	<div class="wb-block1">
<?php
		if($this->user['id'] > 0)
		{
?>
	<div class="user">
	<div class="user-set">
		<h5><a href="<?php echo hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $user_info['id'])); ?>"><?php echo $user_info['username']; ?></a></h5>
		<a href="<?php echo hg_build_link(SNS_UCENTER.'userprofile.php'); ?>">个人设置</a>
		<a href="<?php echo hg_build_link(SNS_UCENTER.'login.php' , array('a' => 'logout')); ?>"><?php echo $this->lang['logout']?></a>
		<div class="user-name" style="width:270px;">
			<a><?php echo $user_info['location']; ?></a>
		</div>
	</div> 
	<a href="<?php echo hg_build_link(SNS_UCENTER.'avatar.php'); ?>"><img src="<?php echo $user_info['middle_avatar']; ?>" title="<?php echo $user_info['username']; ?>" /></a>
	</div>

	<!-- load userInfo  -->
		<?php include hg_load_template('userInfo');?>
		</div>
	<!-- end load -->
<?php }?>
		</div>

</div>
</div>

<?php include hg_load_template('foot');?>