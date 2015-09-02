<?php
/*
 * 检测转码遗漏的视频
 */
require('global.php');
require_once(CUR_CONF_PATH . 'lib/transcode.class.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
define('MOD_UNIQUEID','verify_transcode_videos');//模块标识
set_time_limit(0);

class verify_transcode_videos extends cronBase
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
		/**************************************查询出数据库里面正在转码的视频******************************************/
		$sql = "SELECT * FROM " .DB_PREFIX. "vodinfo WHERE status = 0  AND vod_leixing != 4 ";
		$q = $this->db->query($sql);
		$videos = array();
		while($r = $this->db->fetch_array($q))
		{
			$videos[] = $r['id'];
		}
		
		if(!$videos)
		{
			$this->errorOutput(NO_VIDEO_IS_TRANSCODING);
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
		
		/***************算出页面中正在转码的视频与实际服务器中转码视频以及等待的视频的差集，算出来视频id就是遗漏的视频**************/
		$diffIds = array_diff($videos,$ids);
		if(!$diffIds)
		{
			$this->errorOutput(NO_VIDEO_LEAVE_OUT);
		}
		
		//将找出遗漏的视频提交重新转码
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		foreach($diffIds AS $k => $v)
		{
			$curl->initPostData();
			$curl->addRequestData('id',$v);
			$curl->addRequestData('force_recodec',1);
			$curl->request('retranscode.php');
		}
		
		$this->addItem('遗漏的视频:' . implode(',',$diffIds) . '已经提交重新转码');
		$this->output();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '检测转码遗漏的视频',
			'brief' => '检测转码中的视频是否真的在转码，如果不在提交转码',
			'space' => '3600',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
}

$out = new verify_transcode_videos();
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