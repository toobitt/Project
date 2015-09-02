<?php
/***************************************************************************
* $Id: verify_code_auto.php 36459 2014-04-17 02:13:51Z jiyuting $
***************************************************************************/
require('../admin/global.php');
define('MOD_UNIQUEID','del_image');
class del_image extends cronBase
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
			'name' => '删除多余图片',	 
			'brief' => '删除上传时不用的图片',
			'space' => '86400',//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function run()
	{
		$beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
		$endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
		$sql = 'DELETE FROM '.DB_PREFIX.'material WHERE vid = 0 and create_time <= '.$endYesterday .' and create_time >= '.$beginYesterday;
		$query = $this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
}

$out = new del_image();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'run';
}
$out->$action();
?>