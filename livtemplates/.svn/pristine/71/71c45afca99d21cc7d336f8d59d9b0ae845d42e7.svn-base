<?php
/* $Id: avatar.php 387 2011-07-26 05:31:22Z lijiaying $ */
?>
{template:head/head_register_login}
<div class="content">
	<div class="content_top"></div>	
	<div class="content_middle clear"> 
		<!-- 导航按钮  -->
		{template:unit/userset}
		<div class="con-avatar" id = "avatar" style="padding:20px 0 20px 143px;border:">
			<form action='avatar.php' method='post' enctype='multipart/form-data'>
				<img style="border: 1px solid #B4B5AF;padding:2px;" src="{$_user_info[0]['larger_avatar']}?{code} echo TIMENOW;{/code}" />
				{$_lang['upload_avatar']}：<input type='file' name='files' />
				<input type='submit' name='sub' value='{$_lang['upload']}' style="margin-left:20px;"/>
				<input type='hidden' name='a' value='uploadImage' />
			</form>
		</div>
	</div>
	<div class="content_bottom"></div>
</div>

{template:foot}