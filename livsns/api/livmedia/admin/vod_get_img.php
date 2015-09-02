<?php
define('MOD_UNIQUEID', 'vod');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class  vod_get_img  extends adminBase
{
    private $curl;
    public function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:视频的记录id,需要获得视频的张数img_count,开始时间stime,结束时间etime
	 *功能:获取截图
	 *返回值:指定张数的截图链接
	 * */
	public  function get_img()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		//传入所要获取截图的张数
		if(!$this->input['img_count'])
		{
			$this->input['img_count'] = 9;
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".intval($this->input['id'])."'";
		$arr = $this->db->query_first($sql);

		if($this->input['stime'])
		{
			$stime = urldecode($this->input['stime']);
		}
		else 
		{
			$stime = $arr['start'] + 1;
		}
		
		if($this->input['etime'])
		{
			$etime = urldecode($this->input['etime']);
		}
		else 
		{
			$etime = $stime + $arr['duration'];
		}
		
		//如果视频是标注的话，要到vod_mark_video表里查询开始时间与结束时间
		if($arr['vod_leixing'] == 4 && intval($this->input['img_count']) != 1)
		{
			$sql = "SELECT duration FROM ".DB_PREFIX."vod_mark_video WHERE vodinfo_id = '".intval($this->input['id'])."'";
			$q = $this->db->query($sql);
			$duration_time = 0;
			while ($r = $this->db->fetch_array($q))
			{
				$duration_time += intval($r['duration']); 
			}
			
			$etime = $duration_time;
			$stime = 0;
		}
		
		//获得到截图，并且把这些截图的地址保存在数组中
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('id',$this->input['id']);
		$this->curl->addRequestData('count',intval($this->input["img_count"]));
		$this->curl->addRequestData('stime',$stime);
		if(intval($this->input['img_count']) != 1)
		{
			$this->curl->addRequestData('etime',$etime);
		}
	
		$img_arr = $this->curl->request('snap.php');
		$return['new_img'] = $img_arr[0];

		//获取原图
		$img_arr = unserialize($arr['img_info']);
		$return['source_img'] = $img_arr['host'].$img_arr['dir'].$img_arr['filepath'].$img_arr['filename'];
		$return['id'] = intval($this->input['id']);
		$this->addItem($return);
		$this->output();
	}
}

$out = new vod_get_img();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'get_img';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
	
?>