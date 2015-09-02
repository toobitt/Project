<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID','dverify_code');//模块标识
define('SCRIPT_NAME', 'dverifycode');
require('./global.php');
class dverifycode extends cronBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function initcron()
	{
		$array = array(
			'mod_uniqueid' 	=> 'dverify_code',	 
			'name' 			=> '删除过期验证码',	 
			'brief' 		=> '删除过期验证码',
			'space'			=> '60',	//运行时间间隔，单位秒
			'is_use' 		=> 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	function show()
	{
		if(!defined('VERIFYCODE_EXPIRED_TIME'))
		{
			define('VERIFYCODE_EXPIRED_TIME', 3600);
		}
		//删除手机验证码表
		$where = 'create_time < ' .intval(TIMENOW - VERIFYCODE_EXPIRED_TIME);
		$sql = 'DELETE FROM ' . DB_PREFIX . 'mobile_verifycode WHERE ' . $where;
		$this->db->query($sql);
		//删除其它验证码表
		$where .= ' AND action = 1 ';
		$sql = 'DELETE FROM ' . DB_PREFIX . 'verifycode WHERE ' . $where;
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>