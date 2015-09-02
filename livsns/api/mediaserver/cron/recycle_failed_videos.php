<?php
/*
 * 验证视频库中正在转码中的视频是否真的在转码，如果没有，就将这些遗漏的重新提交转码
 */
require('global.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
define('MOD_UNIQUEID','recycle_failed_videos');//模块标识
set_time_limit(0);

class recycle_failed_videos extends cronBase
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
		/**************************************查询出数据库里面失败的视频并且不是标注归档的*******************************/
		$sql = "SELECT * FROM " .DB_PREFIX. "vodinfo WHERE status = -1 AND vod_leixing != 4 ";
		$q = $this->db->query($sql);
		$videos = array();
		while($r = $this->db->fetch_array($q))
		{
			$videos[] = $r['id'];
		}
		
		if(!$videos)
		{
			$this->errorOutput(NO_VIDEO_IS_FAILED);
		}

		//将失败的视频提交重新转码
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		foreach($videos AS $k => $v)
		{
			$curl->initPostData();
			$curl->addRequestData('id',$v);
			$curl->addRequestData('force_recodec',1);
			$curl->request('retranscode.php');
		}
		
		$this->addItem('失败的视频:' . implode(',',$videos) . '已经提交重新转码');
		$this->output();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name'  => '回收转码失败的视频',
			'brief' => '将失败的视频回收重新提交转码，标注归档暂时不能回收',
			'space' => '3600',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
}

$out = new recycle_failed_videos();
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