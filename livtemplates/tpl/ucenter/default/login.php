<?php 
/* $Id: login.php 9818 2012-06-20 08:59:58Z repheal $ */
?>
{template:head/head_register_login}
<div class="con1">

	<div class="con-md md-pad1 clear"> 
	{if !$message}
	{$message}
	{/if}

	<form action="login.php" method="POST" name="ulf">
<table id="login" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	  
  <tr>
    <td width="150" height="54" align="right" valign="middle">用户名：</td>
    <td width="380" height="54" align="left" valign="middle">
		<div class="biankuang">
			<input type="text" tabindex="10" id="username" name="username" class="username_bg" onblur="showUser(this);" onfocus="clearUser(this);"/>
		</div>
	</td>
    <td rowspan="3" width="330" align="center" valign="top" class="submit_ok1">

   	还没有账号，赶快<a href="<?php echo hg_build_link('register.php'); ?>">注册</a>吧<br /><br />
    <a  class="register" href="<?php echo hg_build_link('register.php'); ?>"></a>
    
    {if !$is_open_register && $isopeninvite}
       {if $pubtesturl}
          <br /><a href="{$pubtesturl}">申请公测账号</a>
       {/if}
    {/if}
	</td>
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
    	<input id="login_bt"  tabindex="13" class="login-input" type="submit" value=" " name="lsubmit" />   
    	
    	{if $_settings['qq_login']}
    	   <a target="_blank" href="<?php echo SNS_UCENTER."qq_login.php?a=redirect_to_login&referto=" . ($referto ? $referto : $this->input['referto']);?>"><img style="margin-left:10px;margin-top:1px;" src="qq/img/qq_login.png"></a>
    	{/if}
    
	</td>
    </tr>
</table>
<input type="hidden" value="dologin" name="a" />
<input type="hidden" value="<?php echo $referto ? $referto : $this->input['referto'];?>" name="referto" />
</form>
<a style="margin: 40px 0pt 0pt 40px; display: block;" href="http://www.hoolo.tv/zt/iphone/" class="clear"><img style="padding: 2px; border: 1px solid rgb(237, 237, 237);" src="./res/img/2012-02-10_172444.jpg"></a>
</div>
<div class="content_bottom"></div>
  </div>
  <div class="clear"></div>
{template:foot}