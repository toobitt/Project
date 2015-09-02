<?php
/***************************************************************************
* $Id: verify_code_auto.php 36459 2014-04-17 02:13:51Z jiyuting $
***************************************************************************/
define('MOD_UNIQUEID','verify_code_auto');
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR.'global.php');
require(CUR_CONF_PATH."lib/functions.php");
class verifyCodeAutoApi extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '删除已过期验证码',	 
			'brief' => '删除已过期验证码',
			'space' => '30',//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		$sql = "DELETE FROM " . DB_PREFIX . "verify_code ";
		$sql.= " WHERE create_time < " . (TIMENOW - intval($this->settings['verify_code_valid']));
		
		$this->db->query($sql);
		
		$this->addItem('success');
		$this->output();
	}
}

$out = new verifyCodeAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>