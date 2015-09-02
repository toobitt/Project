<?php
define('MOD_UNIQUEID','vod');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class  vod_video2physics extends adminBase
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
	 *功能:将标注后的视频妆花为物理文件
	 *返回值:无
	 * */
	public function video2physics()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."vod_mark_video  WHERE vodinfo_id = '".intval($this->input['id'])."'";
		$q = $this->db->query($sql);
		$svodid = array();//记录原视频的vodid
		$start = array();//记录标注的开始时间
		$duration = array();//记录标注的时长
		while($r = $this->db->fetch_array($q))
		{
			$svodid[] = $r['vodid'];
			$start[] = $r['start_time'];
			$duration[] = $r['duration'];
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".intval($this->input['id'])."'";
		$arr = $this->db->query_first($sql);
		
		if(!$arr['isfile'])
		{
			$arr['vodid'] = '';
		}
		
		if(!empty($svodid))
		{
			$this->request_create_physics(intval($this->input['id']), $svodid, $arr['vodid'], $start, $duration);
		}
		
		$this->addItem('success');
		$this->output();
	}
	
	/*$id:视频的记录id
	 *$original_id:视频物理化之前的vodid(可以是一个索引数组)
	 *$vodid:视频物理话之后的vodid,一开始没有物理化的时候为空
	 *$start:需要物理化的开始时间(可以是一个索引数组)
	 *$duration:需要物理化的时间段
	 **/
    private function request_create_physics($id, $svodid, $vodid, $start, $duration)
	{
		$curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',$id);
		$curl->addRequestData('vodid',$vodid);
		foreach($svodid AS $k => $v)
		{
			$curl->addRequestData('start[' . $k . ']',$start[$k]);
			$curl->addRequestData('duration[' . $k . ']',$duration[$k]);
			$curl->addRequestData('svodid[' . $k . ']',$svodid[$k]);
		}
		$arr = $curl->request('save_section.php');
		return $arr;
	}
	
}

$out = new vod_video2physics();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'video2physics';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>