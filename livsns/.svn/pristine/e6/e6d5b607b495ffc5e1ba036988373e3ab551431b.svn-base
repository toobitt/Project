<?php
/*
 * 计划任务执行的强制转码
 */
require('global.php');
require_once(CUR_CONF_PATH . 'lib/transcode.class.php');
require_once(CUR_CONF_PATH . 'lib/TranscodeRoute.class.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
define('MOD_UNIQUEID','transcode_manger');//模块标识
set_time_limit(0);

class mandatory_transcode extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		//查询出所有转码服务器是否空闲
		/*
		$route = select_servers();//选取服务器	
		if(!$route)
		{
			$this->errorOutput(NO_SELECT_TRANSERVER);
		}
		$transcode = new transcode($route);
		$task_info = json_decode($transcode->get_transcode_tasks(),1);
		$ids = 0;
		if($task_info['transcoding_tasks'])
		{
			$this->errorOutput(EXECAFTERTRANSCODE);
		}
		*/

		$sql = "SELECT id,status FROM " .DB_PREFIX. "vodinfo WHERE is_forcecode = 0 AND status NOT IN (0,4,-1,5) AND vod_leixing != 4 ORDER BY create_time DESC LIMIT 0,2";
		$q = $this->db->query($sql);
		$video_ids = array();
		while($r = $this->db->fetch_array($q))
		{
			$video_ids[] = $r['id'];
			$video_status[] = $r['status'];
		}
		
		if(!$video_ids)
		{
			$this->errorOutput(NOID);
		}

		foreach ($video_ids AS $k => $v)
		{
			//判断选取的视频在不在转码中,在的话就不提交这个视频了
			if(checkStatusFromAllServers($v) || checkStatusFromAllServers($v . '_more'))
			{
				unset($video_ids[$k]);
				continue;
			}
			
			//判断选取的视频有没有正在拆条的视频正在转码中
			$sql = "SELECT * FROM " .DB_PREFIX. "vodinfo WHERE original_id = '" .$v. "'";
			$q   = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				if(checkStatusFromAllServers($r['id']))
				{
					unset($video_ids[$k]);
					break;
				}
			}
		}
		
		if(!$video_ids)
		{
			$this->errorOutput(NOID);
		}
		
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		if($video_ids && !empty($video_ids))
		{
			foreach($video_ids AS $k => $v)
			{
				$curl->initPostData();
				$curl->addRequestData('id',$v);
				$curl->addRequestData('force_recodec',1);
				$curl->addRequestData('retain_status',1);
				$curl->addRequestData('audit_auto','retain_status');//保持最后转码完成的状态不变，默默执行
				$curl->request('retranscode.php');
			}
		}
		
		$ids = implode(',',$video_ids);
		$this->addItem($ids);
		$this->output();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '强制转码',	 
			'brief' => '找出没有经过转码强制进行一次转码',
			'space' => '180',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
}

$out = new mandatory_transcode();
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