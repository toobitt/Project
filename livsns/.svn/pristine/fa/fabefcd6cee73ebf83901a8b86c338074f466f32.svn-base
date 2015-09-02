<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: news.php 6930 2012-05-31 07:16:07Z repheal $
***************************************************************************/
require('global.php');
require_once(ROOT_PATH.'lib/class/share.class.php');
class callbackApi extends InitFrm
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$uri = urldecode($_SERVER['REQUEST_URI']);
		$code = $openid = $openkey = '';
		
		$start = stripos($uri,'?appplat=')+9;
		if($start)
		{
			$end = stripos($uri,'&',$start);
			$length = !$end?'':($end-$start);
			$ap = $length?substr($uri,$start,$length):substr($uri,$start);
			$aparr = explode('-',$ap);
			$appid = empty($aparr[0])?'':$aparr[0];
			$platid = empty($aparr[1])?'':$aparr[1];
		}
		
		if(empty($appid) || empty($platid))
		{
			echo "错误";
		}
		
		$start = stripos($uri,'code');
		if($start)
		{
			$end = stripos($uri,'&',$start);
			$length = !$end?'':($end-$start);
			$code = $length?substr($uri,$start,$length):substr($uri,$start);
		}
		
		$start = stripos($uri,'openid');
		if($start)
		{
			$end = stripos($uri,'&',$start);
			$length = !$end?'':($end-$start);
			$openid = $length?substr($uri,$start,$length):substr($uri,$start);
		}
		
		$start = stripos($uri,'openkey');
		if($start)
		{
			$end = stripos($uri,'&',$start);
			$length = !$end?'':($end-$start);
			$openkey = $length?substr($uri,$start,$length):substr($uri,$start);
		}
		$token = file_get_contents("http://10.0.1.40/livsns/api/mobile/api/ipad/share.php?a=accesstoken&$code&$openid&app=$appid&plat=$platid");
		$h = "<div style='display:none'>gettokenstart";
		$h .= $token;
		$h .= "gettokenend</div>";
		print_r($h);
		
		//echo '返回的token值：'.json_decode($token,true).'<br>';
		echo "授权成功";
		
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new callbackApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>


			