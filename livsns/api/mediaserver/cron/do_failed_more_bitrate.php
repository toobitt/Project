<?php
/*
 * 处理经过多码流，但是未成功的视频，使这些视频能够重新被多码流
 */
require('global.php');
require_once(CUR_CONF_PATH . 'lib/transcode.class.php');
define('MOD_UNIQUEID','do_failed_more_bitrate');//模块标识
set_time_limit(0);

class do_failed_more_bitrate extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//开始运行
	public function run()
	{
		/**************************************查询出数据库里面经过多码流但未成功的视频**********************************/
		$sql = "SELECT * FROM " .DB_PREFIX. "vodinfo WHERE is_morebitrate = 0 AND clarity != '' ";
		$q = $this->db->query($sql);
		$videos = array();
		while($r = $this->db->fetch_array($q))
		{
			$videos[] = $r['id'] . '_more';//多码流的任务格式
		}
		
		if(!$videos)
		{
			$this->errorOutput(NO_FAILED_VIDEOS_FROM_MORE);
		}
		
		/**************************************查询出当前正在开启的服务器********************************************/
		$sql = " SELECT * FROM " .DB_PREFIX. "transcode_center WHERE is_open = 1 ";
		$q 	 = $this->db->query($sql);
		$servers = array();
		while ($r = $this->db->fetch_array($q))
		{
			$servers[] = $r;
		}
		
		if(!$servers)
		{
			$this->errorOutput(NO_TRANSERVER_CAN_USE);
		}
		
		/**************************************查询出所有正在使用的转码服务器中正在转码的视频*****************************/
		$ids = array();//存储正在转码中视频id
		foreach($servers AS $k => $v)
		{
			$trans = new transcode(array('host' => $v['trans_host'],'port' => $v['trans_port']));
			$task = json_decode($trans->get_transcode_status(),1);
			if($task['return'] == 'success' && $task['running'])
			{
				if($task['waiting'])
				{
					$task['running'] = array_merge($task['running'],$task['waiting']);
				}

				foreach($task['running'] AS $_k => $_v)
				{
					if(!in_array($_v['id'],$ids))
					{
						$ids[] = $_v['id'];
					}
				}
			}
		}
		
		/***************算出失败的视频与实际服务器中转码视频以及等待的视频的差集，算出来视频id就是正真多码流失败的视频**************/
		$diffIds = array_diff($videos,$ids);
		if(!$diffIds)
		{
			$this->errorOutput(NO_VIDEO_LEAVE_OUT);
		}
		
		$real_failed = array();
		foreach ($diffIds AS $v)
		{
			$real_failed[] = str_replace('_more','',$v);
		}
		
		//更新数据库
		$sql = "UPDATE " .DB_PREFIX. "vodinfo SET clarity = '' WHERE id IN (" .implode(',',$real_failed). ")";
		$this->db->query($sql);
		$this->addItem('多码流失败的视频:' . implode(',',$diffIds) . '已经提交处理');
		$this->output();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name'  => '处理多码流失败的视频',
			'brief' => '处理多码流失败的视频',
			'space' => '3600',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
}

$out = new do_failed_more_bitrate();
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