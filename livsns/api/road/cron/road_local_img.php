<?php
/*
 * Created on 2012-12-11
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','road');
class localImg extends cronBase
{
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->material = new material();		
	}
	
	function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
				'mod_uniqueid' => MOD_UNIQUEID,
				'name' => '本地化路况图片队列',
				'brief' => '本地化路况图片队列',
				'space' => '600',	//运行时间间隔，单位秒
				'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	function show()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."road WHERE local_img = 0 AND pic != '' ORDER BY create_time ASC LIMIT 1";
		$ret = $this->db->query_first($sql);
		if(empty($ret))
		{
			$this->errorOutput('暂不需本地化');
		}
		$pic = json_decode($ret['pic'],1);
		$picsize = json_decode($ret['picsize'],1);
		if(empty($pic))
		{
			$sql = "UPDATE ".DB_PREFIX."road SET local_img = 1 WHERE id = ". $ret['id'];
			$this->db->query($sql);
			echo $ret['id'];
			exit();
		}
		$url = $pic['host'] . $pic['dir'] . $picsize['large'] . $pic['filepath'] . $pic['filename'];
		$material = $this->material->localMaterial($url);	
		$material = $material[0];
		if($material['error'])
		{
			$this->errorOutput('本地化失败');
		}
		$material = array(
			'id'       => $material['id'],
			'host'     => $material['host'],
			'dir'      => $material['dir'],
			'filepath' => $material['filepath'],
			'filename' => $material['filename'],
 		);
		$sql = "UPDATE ".DB_PREFIX."road SET pic = '".addslashes(json_encode($material))."', local_img = 1 WHERE id = ".$ret['id'];
		$this->db->query($sql);
		echo $ret['id'] . '本地化成功';
		exit();
	}
}
$out = new localImg();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
