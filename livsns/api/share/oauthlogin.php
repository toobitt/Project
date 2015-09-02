<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require_once(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH."lib/functions.php");
define(MOD_UNIQUEID,'oauthlogin');
class oauthloginApi extends adminBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/share.class.php');
		$this->obj = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 根据系统id,分享平台id  
	 * @name share
	 * @access public
	 * @author 
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return 
	 */
	public function oauthlogin()
	{
		$appid = intval($this->input['appid']);
		$platid = intval($this->input['id']);
		$access_plat_token = $this->input['access_plat_token'];
		include(CUR_CONF_PATH.'lib/oauthlogin.class.php');
		$oauthlogin = new oauthlogin();
		$ret = $oauthlogin->oauthlogin($appid,$platid,$access_plat_token);
		if($ret == 'NO_PLAT_DATA')
		{
			$this->errorOutput('NO_PLAT_DATA');
		}
		else
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright 	ho	gesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new oauthloginApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'oauthlogin';
}
$out->$action();
?>
