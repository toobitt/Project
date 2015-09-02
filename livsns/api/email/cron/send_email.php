<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','send_email');//模块标识
define('SCRIPT_NAME', 'send_email');
require_once(ROOT_DIR.'global.php');
require_once CUR_CONF_PATH . 'core/Cemail.core.php';
class send_email extends cronBase
{
	function __construct()
	{
		parent::__construct();
		$this->email_core = new Cemail();

	}
	function __destruct()
	{
		parent::__destruct();
	}
	function initcron()
	{
		$array = array(
			'mod_uniqueid' 	=> 'send_email',	 
			'name' 			=> '发送邮件',	 
			'brief' 		=> '发送邮件',
			'space'			=> '1',	//运行时间间隔，单位秒
			'is_use' 		=> 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	function show()
	{
		$ret=$this->email_core->send_mail();
		$this->addItem($ret);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>