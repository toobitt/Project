<?php
/* $Id: avatar.tpl.php 1708 2011-01-11 03:09:46Z repheal $ */
?>
<?php include hg_load_template('head_register_login');?>

<div class="content">
	<div class="content_top"></div>	
	<div class="content_middle clear"> 
		<!-- 导航按钮  -->
		<?php include hg_load_template('userset'); ?>
		<div class="con-avatar" id = "avatar" style="padding:20px 0 20px 143px;border:">
			<form action='avatar.php' method='post' enctype='multipart/form-data'>
				<img style="border: 1px solid #B4B5AF;padding:2px;" src="<?php echo $this->user_info[0]['larger_avatar'];?>?<?php echo TIMENOW?>" />
				<?php echo $this->lang['upload_avatar'];?>：<input type='file' name='files' />
				<input type='submit' name='sub' value='<?php echo $this->lang['upload'];?>' style="margin-left:20px;"/>
				<input type='hidden' name='a' value='uploadImage' />
			</form>
		</div>
	</div>
	<div class="content_bottom"></div>
</div>

<?php include hg_load_template('foot');?>
