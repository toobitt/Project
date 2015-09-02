<?php
define('MOD_UNIQUEID', 'vod');
require_once('global.php');
class  vod_mark_one_video extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:video_id(视频的id)
	 *功能:添加一个视频片段时获取一些视频片段的信息
	 *返回值:视频信息
	 * */
	public function  mark_one_video()
	{
		/*新增标注*/
		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = ".intval($this->input['video_id']);
		$return = $this->db->query_first($sql);
		
		if(intval($return['vod_leixing']) == 4 && $return['original_id'])
		{
			$sql = "SELECT start,duration FROM ".DB_PREFIX."vodinfo WHERE id = '".$return['original_id']."'";
			$arr = $this->db->query_first($sql);
			$return['start'] = $arr['start'];
			$return['duration'] = $arr['duration'];
		}
		
		$return['max_duration'] = $return['duration'];
		
		if($this->input['start_time'])
		{
			$return['start'] = intval($this->input['start_time']);
		}
		
		if($this->input['duration'])
		{
			$return['duration'] = intval($this->input['duration']);
		}

		if($this->input['is_current'])
		{
			$return['is_current'] = 1;
		}
		else
		{
			$return['is_current'] = 0;
		}
		$img_arr = unserialize($return['img_info']);
		$return['img_src'] = $img_arr['host'].$img_arr['dir'].'80x60/'.$img_arr['filepath'].$img_arr['filename'];
		$return['format_start_time'] = time_format($return['start']);
		$return['format_duration'] = time_format($return['duration']);
		$return['vcr_num'] = intval($this->input['vcr_num']);
		$this->addItem($return);
		$this->output();
	}
	
	public function get_unselect_videos()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$start 	  = explode(',',urldecode($this->input['start']));
		$duration = explode(',',urldecode($this->input['duration']));
		
		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".intval($this->input['id'])."'";
		$arr = $this->db->query_first($sql);
		$img_arr = unserialize($arr['img_info']);
		$img_src = $img_arr['host'].$img_arr['dir'].'80x60/'.$img_arr['filepath'].$img_arr['filename'];
		$ret = array();
		for($i = 0;$i<count($start);$i++)
		{
			$is_current = ($i == (count($start) -1))?0:1;
			$row = array(
				'id'	   		  => $arr['id'],
				'start'    		  => $start[$i],
				'duration' 		  => $duration[$i] - 40,
				'title'    		  => $arr['title'],
				'max_duration'    => $arr['duration'],
				'img_src'    	  => $img_src,
				'vcr_num'		  => $i+1,
				'format_duration' => time_format($duration[$i]),
				'is_current'	  => $is_current,
			);
			$ret[] = $row;
		}
		$this->addItem($ret);
		$this->output();
	}
}

$out = new vod_mark_one_video();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'mark_one_video';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>