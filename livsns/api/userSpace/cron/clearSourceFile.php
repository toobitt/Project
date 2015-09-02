<?php
require('global.php');
define('MOD_UNIQUEID','clearSourceFile');//模块标识
require_once '../lib/upyun.class.php';
class clearSourceFile extends cronBase
{
	public function __construct()
	{
		parent::__construct();		
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '队列删除转码源文件',	 
			'brief' => '队列删除转码源文件',
			'space' => '3',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'delete_file ';
		$orderby = ' ORDER BY create_time ASC';
		$limit = ' limit 0, 1';
		$data = $this->db->query_first($sql . $orderby . $limit);
		
		if($data['bucket_name'] && $data['file_path'])
		{
			$upyun = new UpYun($data['bucket_name'], SPACEOPERATORS, SPACEOPERATORSPASSWORD);
			if($upyun->delete($data['file_path'])===true)
			{
				$this->db->query('DELETE FROM ' . DB_PREFIX . 'delete_file  WHERE id = '.$data['id']);
			}
			else 
			{
				$this->db->query('UPDATE ' . DB_PREFIX . 'delete_file SET times=times-1,create_time='.TIMENOW.' WHERE id = '.$data['id']);
			}
		}
		$this->db->query('DELETE FROM ' . DB_PREFIX .'delete_file where times<=0');
	}
}


$out = new clearSourceFile();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>