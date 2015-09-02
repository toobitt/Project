<?php
require('global.php');
define('MOD_UNIQUEID','getCloudVideInfo');//模块标识
require_once(ROOT_PATH . 'lib/class/material.class.php');

class getCloudVideInfo extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '获取云端视频状态',	 
			'brief' => '获取云端视频状态',
			'space' => '10',	//运行时间间隔，单位秒
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
		include(CUR_CONF_PATH . 'lib/cloud/' . $this->settings['video_cloud'] . '.php');
		$cloud = new $this->settings['video_cloud']();
		$cloud->setInput($this->input);
		$cloud->setSettings($this->settings);
		$cloud->setDB($this->db);
		$sql = 'SELECT v.id, ve.content_id FROM ' . DB_PREFIX . 'vodinfo v 
					LEFT JOIN ' . DB_PREFIX . 'vod_extend ve ON v.id=ve.vodinfo_id WHERE 														
					v.status < 1 ORDER BY v.update_time ASC LIMIT 5';
		$q = $this->db->query($sql);
		$ids = array();
		while($r = $this->db->fetch_array($q))
		{
			$cb = $cloud->callback($r['content_id']);
			if (!$cb)
			{
				$ids[] = $r['id'];
			}
		}
		if ($ids)
		{
			$sql = 'UPDATE ' . DB_PREFIX . 'vodinfo SET update_time=' . time() . ' WHERE ID IN (' . implode(',', $ids) . ')';
			$this->db->query($sql);
		}
	}
}


$out = new getCloudVideInfo();
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