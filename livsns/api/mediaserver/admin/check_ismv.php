<?php
require_once('global.php');
define('MOD_UNIQUEID','transcode_manger');//模块标识
require_once(ROOT_PATH . 'lib/class/curl.class.php');
set_time_limit(0);
class check_ismv extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function run()
	{
		if(!$this->input['stime'])
		{
			$stime = TIMENOW - 24 * 3600;
		}
		else 
		{
			$stime = strtotime($this->input['stime']);
		}
		
		if(!$this->input['etime'])
		{
			$etime = TIMENOW;
		}
		else 
		{
			$etime = strtotime($this->input['etime']);
		}

		$sql = "SELECT * FROM " .DB_PREFIX. "vodinfo WHERE 1 AND create_time >= '" .$stime. "' AND create_time <= '" .$etime. "' AND status NOT IN (0,4,5) AND vod_leixing != 4";
		$q = $this->db->query($sql);
		$video_path = array();
		$videos = array();
		while($r = $this->db->fetch_array($q))
		{
			$video_tmp 	= explode('.',$r['video_filename']);
			$video_path[$r['id']] = $r['video_base_path'] . $r['video_path'] . $video_tmp[0];
			$videos[$r['id']] = $r;
		}
		
		if(!$video_path)
		{
			$this->errorOutput('此时间段不存在视频');
		}
		
		$no_ismv = array();
		foreach($video_path AS $k => $v)
		{
			if(!file_exists($v . '.ismv') || !file_exists($v . '.ism'))
			{
				$no_ismv[] = $k;
			}
		}
		
		if(!$no_ismv)
		{
			$this->errorOutput('此时间段的视频都存在ismv与ism文件');
		}
		
		//检测视频有没有转码中的，主要为了防止强制转码的任务
		foreach ($no_ismv AS $k => $v)
		{
			if(checkStatusFromAllServers($v))
			{
				unset($no_ismv[$k]);
			}
		}

		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		foreach($no_ismv AS $k => $v)
		{
			$curl->initPostData();
			$curl->addRequestData('id',$v);
			$curl->addRequestData('force_recodec',1);
			if(in_array($videos[$v]['status'],array(2,3)))
			{
				$curl->addRequestData('audit_auto',$videos[$v]['status']);
			}
			$curl->request('retranscode.php');
		}
		
		$ids = implode(',',$no_ismv);
		$this->addItem('没有ismv的视频有:' .$ids. ',已经提交重新转码');
		$this->output();
	}
}

$out = new check_ismv();
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