<?php
define('MOD_UNIQUEID','vod');
require_once('global.php');
class  vod_request_opration  extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	/*参数:视频的记录id
	 *功能:在视频列表中,单击某一条记录时，弹出操作框,获取视频的信息，以便使用
	 *返回值:该视频的信息
	 * */
	public  function show_opration()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		//查询出顶级类别供下面没有分类的时候用
		$sql = "SELECT * FROM ".DB_PREFIX."vod_media_node WHERE fid = 0";
		$q = $this->db->query($sql);
		$top_sorts = array();
		while($r = $this->db->fetch_array($q))
		{
			$top_sorts[$r['id']] = $r;
		}

		$sql = "SELECT f.*,s.name AS sort_name  FROM ".DB_PREFIX."vodinfo as f left join  ".DB_PREFIX."vod_media_node as s on f.vod_sort_id = s.id WHERE f.id = '".intval($this->input['id'])."'";
		$return = $this->db->query_first($sql);
		if (!$return)
		{
			$this->errorOutput('视频不存在或已被删除');
		}

		if($return['isfile'])
		{
			$return['start'] = 0;
		}

		$return['format_duration'] = hg_timeFormatChinese($return['duration']);//时长
		$return['trans_use_time'] = hg_timeFormatChinese($return['trans_use_time']);//转码所用时间
		$return['is_forcecode'] = $return['is_forcecode']?'是':'否';
		$return['is_water_marked'] = $return['is_water_marked']?'是':'否';
		$return['bitrate'] = $return['bitrate'].'kbps';//码流
		$return['resolution'] = $return['width'].'*'.$return['height'];//分辨率
	    $return['vod_leixing_name'] = $top_sorts[$return['vod_leixing']]['name'];
	    $return['totalsize'] = hg_fetch_number_format($return['totalsize'],1);
	    $return['isfile'] = $return['isfile']?'是':'否';
	    $return['frame_rate'] =  number_format($return['frame_rate'],3).'fps';
	    $audio_status = check_str('L','R',$return['audio_channels']);

		switch ($audio_status)//声道
		{
			case 0  :$return['audio_channels'] = '无';break;
			case 1  :$return['audio_channels'] = '右';break;
			case 2  :$return['audio_channels'] = '左';break;
			case 3  :$return['audio_channels'] = '左右';break;
			default :$return['audio_channels'] = '无';break;
		}

		if($return['collects'])
		{
			$return['collects'] = unserialize($return['collects']);
		}

		//记录页面的所处的类型与类别
		if($this->input['frame_type'])
		{
			$return['frame_type'] = intval($this->input['frame_type']);
		}
		else
		{
			$return['frame_type'] = '';
		}

		if($this->input['frame_sort'])
		{
			$return['frame_sort'] = intval($this->input['frame_sort']);
		}
		else
		{
			$return['frame_sort'] = '';
		}

		$return['download'] = $this->settings['App_mediaserver']['protocol'].$this->settings['App_mediaserver']['host']. '/' . $this->settings['App_mediaserver']['dir'] . 'admin/download.php';
		$return['video_url'] = $return['hostwork'].'/'.$return['video_path'].MAINFEST_F4M;
		$return['video_m3u8'] = $return['hostwork'].'/'.$return['video_path'].str_replace('.mp4', '.m3u8', $return['video_filename']);
		$return['snapUrl'] = $this->settings['App_mediaserver']['protocol'].$this->settings['App_mediaserver']['host'].'/'.$this->settings['App_mediaserver']['dir'].'admin/snap.php';
		$return['display_technical'] = $this->settings['technical_swdl']['host'];
		switch($return['technical_status'])
		{
			case -1:$return['technical_status'] = '技审失败';break;
			case  1:$return['technical_status'] = '技术审核';break;
			case  2:$return['technical_status'] = '正在技审中';break;
			case  3:$return['technical_status'] = '技审成功';break;
			default:$return['technical_status'] = '技术审核';break;
		}
		$this->addItem($return);
		$this->output();

	}
}

$out = new vod_request_opration();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show_opration';
}
else
{
	$action = $_INPUT['a'];
}
$out->$action();
?>