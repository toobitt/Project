<?php
/*
 * 计划任务执行的强制转码
 */
require('global.php');
define('MOD_UNIQUEID','clean_expire_message');//模块标识
require_once(CUR_CONF_PATH . 'lib/market_message_mode.php');

class clean_expire_message extends cronBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new market_message_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function run()
	{
		$this->mode->clean_expire_message();
		$this->addItem('success');
		$this->output();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '定时清除过期的消息',	 
			'brief' => '定时清除过期的消息',
			'space' => '600',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
}

$out = new clean_expire_message();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'run';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>