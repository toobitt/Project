<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2118 2011-02-18 05:15:34Z yuna $
***************************************************************************/

define('ROOT_DIR', '../');
define('SCRIPTNAME', 'ustatus');
require('./global.php');
class ustatus extends uiBaseFrm
{	
	
	private $info;
	private $status;
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{		
		if($this->user['id'] > 0)
		{
			$str = '<table width="270" border="1">
					  <tr>
						<td  align="right" height="25" style="color:#444; text-align:right">用户名：</td>
						<td width="186"><a href="http://sns.hcrt.cn/ucenter/user.php">' . $this->user['username'] . '</a>&nbsp;&nbsp;<a href="http://sns.hcrt.cn/t/login.php?a=logout">退出</a></td>
					  </tr>
					</table>';
		}
		else
		{
			$str = '<form method="post" name="loginform" action="http://sns.hcrt.cn/t/login.php?a=dologin">
			<input type="hidden" name="referto" value="http://api.hcrt.cn/www/" />
					<table width="270" border="1">
					  <tr>
						<td  align="right" height="25" style="color:#444; text-align:right">用户名：</td>
						<td width="186"><input name="username" type="text"  width="120" style="width:120px;border:1px solid #c1c1c1;" /></td>
					  </tr>
					  <tr>
						<td align="right" height="25" style="color:#444;">密&nbsp;&nbsp;码：</td>
						<td><input name="password" type="password" width="120" style="width:120px;border:1px solid #c1c1c1;" /></td>
					  </tr>
					  <tr>
						<td colspan="2" style=" text-align:center;height:25px;color:#007ED6;"><input name="" type="checkbox" value="" align="middle" style="vertical-align:middle; margin-top:-2px;"/> 下次自动登录 &nbsp;&nbsp;<a href="#" style="color:#666; text-decoration:underline;">忘记密码</a></td>
						</tr>
					  <tr>
						<td colspan="2" align="center"><input type="submit" value="" name="su" style="background:url(http://api.hcrt.cn/www/res/templates/images/login1.jpg) no-repeat;width:96px;height:26px;border:0;cursor:pointer" /><a href="http://sns.hcrt.cn/t/register.php"><img src="http://api.hcrt.cn/www/res/templates/images/reg.jpg"  /></a></a></td>
						</tr>
					</table>
					</form>';
		}
		$str = str_replace(array("\r", "\n"), '', $str);
		?>
		document.write('<?php echo $str;?>');
		<?php		
	}
}
$out = new ustatus ();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>