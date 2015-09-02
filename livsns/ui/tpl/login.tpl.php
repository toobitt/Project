<?php 
/* $Id: login.tpl.php 4004 2011-05-27 00:50:28Z repheal $ */
?>
<?php include hg_load_template('head');?>
<div class="con1">

		<div class="con-md md-pad1 clear"> 

	  <?php 
	if(!$message)
	{
	?>
	<?php echo $message;?>
	<?php 
	}
	?>
	<form action="<?php echo hg_build_link(SNS_UCENTER."login.php");?>" method="POST">
<table id="login" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	  
  <tr>
    <td width="150" height="54" align="right" valign="middle">用户名：</td>
    <td width="380" height="54" align="left" valign="middle">
		<div class="biankuang">
			<input type="text" tabindex="10" id="username" name="username" class="username_bg" onblur="showUser(this);" onfocus="clearUser(this);"/>
		</div>
	</td>
    <td rowspan="3" width="330" align="center" valign="top" class="submit_ok1">
   	还没有账号，赶快<a href="<?php echo hg_build_link(SNS_UCENTER.'register.php'); ?>">注册</a>吧<br /><br />

    <a  class="register" href="<?php echo hg_build_link(SNS_UCENTER.'register.php'); ?>"></a></td>
  </tr>
  <tr>
    <td height="50" align="right" valign="middle">密&nbsp;&nbsp;码：</td>
    <td height="41" align="left" valign="middle">
		<div class="biankuang">
			<input type="password" tabindex="12" id="password" name="password" />
		</div>
	</td>
    </tr>
    <tr>
    <td></td>
    <td height="40"  valign="bottom">
    	<input id="login_bt"  tabindex="13" class="login-input" type="submit" value=" " name="submit"/>   
    	<?php 
    	if($this->settings['qq_login'])
    	{?>
    		<a href="<?php echo SNS_UCENTER."qq_login.php?a=redirect_to_login"?>"><img style="margin-left:10px;margin-top:1px;" src="qq/img/qq_login.png"></a>
    	<?php	
    	}
    	?> 
	</td>
    </tr>
</table>
<input type="hidden" value="dologin" name="a" />
<input type="hidden" value="<?php echo $this->input['referto'];?>" name="referto" />
</form>

</div>
<div class="content_bottom"></div>
  </div>
<?php include hg_load_template("foot");?>