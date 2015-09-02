<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'vod');
require_once('global.php');
class  vod_video_mark extends adminBase
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
	 *功能:进入标注页的时候显示的模式(重标注/新增标注)
	 *返回值:$return(包含与此视频相关的一些信息)
	 * */
    public  function video_mark()
    {
    	if(!$this->input['id'])
    	{
    		$this->errorOutput(NOID);
    	}
    	
    	$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".intval($this->input['id'])."'";
    	$return = $this->db->query_first($sql);
        $return['column_id'] = unserialize($return['column_id']);
		if(is_array($return['column_id']) && $return['column_id'])
		{
			$column_id = array();
			foreach($return['column_id'] as $k => $v)
			{
				$column_id[] = $k;
			}
			$column_id = implode(',',$column_id);
			$return['column_id'] = $column_id;
		}
    	
     	$sql = "SELECT * FROM ".DB_PREFIX."vod_media_node WHERE  fid = 4";//查询出标注归档里面的类别 
    	$q = $this->db->query($sql);
    	while($r = $this->db->fetch_array($q))
    	{
    		$return['sort_name'][] = $r; 
    	}
    	
    	$img_arr = unserialize($return['img_info']);
		$return['source_img'] = $img_arr['host'].$img_arr['dir'].$img_arr['filepath'].$img_arr['filename'];
    	
    	//(已经是标注集需要编辑标注)
    	if($return['vod_leixing'] == 4)
    	{
    	    $return['add_edit'] = 1;
    		if(!$return['original_id'])
    		{
    			$sql = "SELECT * FROM ".DB_PREFIX."vod_mark_video WHERE vodinfo_id = '".intval($this->input['id'])."' ORDER BY order_id ASC ";
	    		$arr2 = $this->db->query_first($sql);
	    		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".$arr2['original_id']."'";
    			$arr = $this->db->query_first($sql);
	    		$return['original_title'] = '多视频片段';
	    		$return['id'] = $arr['id'];
    		}
    		else 
    		{
    			$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".$return['original_id']."'";
    			$arr = $this->db->query_first($sql);
    			$return['original_title'] = $arr['title'];
    			$return['id'] = $return['original_id'];//标注flash显示从源视频
    			$sql = "SELECT * FROM ".DB_PREFIX."vod_mark_video WHERE vodinfo_id = '".intval($this->input['id'])."'";
	    		$arr2 = $this->db->query_first($sql);
    		}
	    	$return['aspect'] = $arr['aspect'];
	    	$return['start'] = $arr2['start_time'];
	    	$return['duration'] = $arr2['duration'];
	    	$return['video_mark'] = $return['hostwork'].'/'.$arr['video_path'].MAINFEST_F4M;
    	}
    	else if(!$this->input['fast_edit'])
    	{
    		//等于0说明是原视频,此时可以针对该视频进行新增标注
    		$return['add_edit'] = 0;
    		//(查询出所有源id为该视频的标注，找出其中mark_etime最靠后的)
    		$sql = "SELECT MAX(mark_etime) as maxtime FROM ".DB_PREFIX."vodinfo WHERE original_id = '".intval($this->input['id'])."' AND vod_leixing = 4 ";
    	    $arr = $this->db->query_first($sql);
    	    $return['start'] = $arr['maxtime'];
    	    $return['duration'] = "";
    		$return['author'] = "";
    		$return['comment'] = "";
    		$return['subtitle'] = "";
    		$return['source_img'] = "nopic_small.png?".time();//给一张默认图
    		
    		//查询出该视频里面所有标注的信息
    		$sql_m = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE original_id = '".intval($this->input['id'])."' AND vod_leixing = 4 ";
    		$q_m = $this->db->query($sql_m);
    		while ($r_m = $this->db->fetch_array($q_m))
    		{
    			$r_m['duration'] = time_format($r_m['duration']);
    			$return['sub_mark_info'][] = $r_m;
    		}
    		$return['video_mark'] = $return['hostwork'].'/'.$return['video_path'].MAINFEST_F4M;
    	}
    	else //快编
    	{
    		 $return['add_edit'] = -1;
    		 $return['start'] = 0;
    	     $return['duration'] = "";
    	     $return['video_mark'] = $return['hostwork'].'/'.$return['video_path'].MAINFEST_F4M;
    	}
    	
    	$return['video_pic_api'] = $this->settings['App_mediaserver']['protocol'].$this->settings['App_mediaserver']['host'].'/'.$this->settings['App_mediaserver']['dir'] . 'admin/';
    	$return['connect_name'] = 'mark_'.TIMENOW;
    	$return['time'] = TIMENOW;
    	$this->addItem($return);
    	$this->output();
    }
		
}

$out = new vod_video_mark();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'video_mark';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>