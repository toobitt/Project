<?php 
/* $Id: login.php 87 2011-06-21 07:10:24Z repheal $ */
?>
{template:head}
<form action="<?php echo hg_build_link(SNS_UCENTER."login.php");?>" method="POST">
<table id="login" width="300px" border="0" align="center" cellpadding="0" cellspacing="0" style="margin: 70px auto;">
	  
  <tr>
    <td width="169" height="44" align="right" valign="middle">用户名：</td>
    <td width="430" height="44" align="left" valign="middle">
		<div >
			<input type="text" id="username" name="username" style="width:150px;" />
		</div>
	</td>
    <td rowspan="3" width="25" align="center" valign="top" >
   	</td>
  </tr>
  <tr>
    <td height="40" align="right" valign="middle">密&nbsp;&nbsp;码：</td>
    <td width="430" height="41" align="left" valign="middle">
		<div >
			<input type="password" id="password" name="password" style="width:150px;" />
		</div>
	</td>
    </tr>
    <tr>
    <td height="30" colspan="2" align="center" valign="bottom">
    	<input id="login_bt"  type="submit" value="登录" name="submit"/>    
    	{if $_settings['qq_login']}
    		<a href="<?php echo SNS_UCENTER."qq_login.php?a=redirect_to_login"?>"><img style="margin-left:10px;margin-top:1px;" src="qq/img/qq_login.png"></a>
    	{/if}
	</td>
    </tr>
</table>
<input type="hidden" value="dologin" name="a" />
<input type="hidden" value="{$_INPUT['referto']}" name="referto" />
</form>
{template:foot}