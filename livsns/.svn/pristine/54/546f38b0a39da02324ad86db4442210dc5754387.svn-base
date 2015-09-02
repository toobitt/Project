<?php
require('global.php');
define('MOD_UNIQUEID','delCloudVideo');//模块标识
require_once(ROOT_PATH . 'lib/class/material.class.php');

class delCloudVideo extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '定期清理视频',	 
			'brief' => '定期清理视频',
			'space' => '3600',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		if (!$this->settings['video_cloud'])
		{
			return;
		}
		if(!defined('MAX_CLOUD_DELETE_TIME'))
		{
			return;
		}
		if(!defined('MAX_CLOUD_DELETE'))
		{
			return;
		}
		$cachefile = DATA_DIR . 'offset.txt';
		if(!file_exists($cachefile))
		{
			file_put_contents($cachefile, '0');
		}
		$offset = file_get_contents($cachefile);
		
		
		$count_sql = 'SELECT count(*) as total from ' . DB_PREFIX . 'vod_extend ve 
					LEFT JOIN ' . DB_PREFIX . 'vodinfo v ON v.id=ve.vodinfo_id WHERE 														
					v.status = 1 AND '.TIMENOW.'-v.update_time>'.MAX_CLOUD_DELETE_TIME.' ORDER BY v.id ASC ';
		$count = $this->db->query_first($count_sql);
		$count = $count['total'];
		
		$limit = 'limit ' . $offset . ', ' . MAX_CLOUD_DELETE;
		echo $sql = 'SELECT v.id, ve.content_id,v.source_path,v.source_filename,v.video_path,v.video_filename FROM ' . DB_PREFIX . 'vod_extend ve 
					LEFT JOIN ' . DB_PREFIX . 'vodinfo v ON v.id=ve.vodinfo_id WHERE 														
					v.status = 1 AND '.TIMENOW.'-v.update_time>'.MAX_CLOUD_DELETE_TIME.' ORDER BY v.id ASC ' . $limit;
		
		$q = $this->db->query($sql);
		$filepath = array();
		while($val = $this->db->fetch_array($q))
		{
			$file_array = array();
			if($this->settings['video_cloud_delete'] == 2)
			{
				$file_array[] = UPLOAD_DIR . $val['source_path'] . $val['source_filename'];
				
			}
			if($this->settings['video_cloud_delete'] == 3)
			{
				$file_array[] = TARGET_DIR . $val['video_path'] . $val['video_filename'];
				
			}
			if($this->settings['video_cloud_delete'] == 1)
			{
				$file_array[] = UPLOAD_DIR . $val['source_path'] . $val['source_filename'];
				$file_array[] = TARGET_DIR . $val['video_path'] . $val['video_filename'];
			}
			if($file_array)
			{
				print_r($file_array);
				foreach($file_array as $fi)
				{
					@unlink($fi);
				}
			}
		}
		$offset = $offset+MAX_CLOUD_DELETE;
		if($count <= $offset)
		{
			$offset = $count;
		}
		@file_put_contents($cachefile, $offset);
	}
}


$out = new delCloudVideo();
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