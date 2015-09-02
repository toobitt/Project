<?php 
/* $Id: login.php 87 2011-06-21 07:10:24Z repheal $ */
?>
{template:head}
<div class="vui usr_login">
<div class="con-left">	<div class="station_content">		<h3 class="con_top">登陆</h3>	<div class="show_info">
<form action="<?php echo hg_build_link(SNS_UCENTER."login.php");?>" method="POST">
<h1>请先登录</h1>
<table id="login" width="300px" border="0" align="center" cellpadding="0" cellspacing="0">
	  
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
	</td>
    </tr>
</table>
<input type="hidden" value="dologin" name="a" />
<input type="hidden" value="{$_INPUT['referto']}" name="referto" />
</form>
</div>
<div class="con_bottom clear"></div>
</div>
</div>
<div class="con-right"><h2>没有账号<a href="http://bbs.sjzntv.cn/member.php?mod=gdwreg.php">现在注册</a></h2></div>
<div class="clear"></div>
</div>

{template:foot}