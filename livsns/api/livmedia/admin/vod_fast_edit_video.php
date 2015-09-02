<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'vod');
require_once('global.php');
class  vod_fast_edit_video extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
    public  function fast_edit_video()
    {
    	if(!$this->input['id'])
    	{
    		$this->errorOutput(NOID);
    	}
    	
    	$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".intval($this->input['id'])."'";
    	$return = $this->db->query_first($sql);
    	$img_arr = unserialize($return['img_info']);
		$return['source_img'] = $img_arr['host'].$img_arr['dir'].$img_arr['filepath'].$img_arr['filename'];
    	$return['video_mark'] = $return['hostwork'].'/'.$return['video_path'].MAINFEST_F4M;
       	//查询临时表里面的数据
    	$sql = "SELECT * FROM " . DB_PREFIX . "fast_vcr_tmp  WHERE user_id = '" .$this->user['user_id']. "' AND main_video_id = '" .$this->input['id']. "' ORDER BY order_id ASC ";
    	$q = $this->db->query($sql);
    	$fast_vcr_tmp = array();
    	$vodinfo_id = array();
    	while($r = $this->db->fetch_array($q))
    	{
    		$fast_vcr_tmp[] = $r;
    		if(!in_array($r['vodinfo_id'],$vodinfo_id))
    		{
    			$vodinfo_id[] = $r['vodinfo_id'];
    		}
    	}
    	
    	if($vodinfo_id && !empty($vodinfo_id))
    	{
    		$sql = "SELECT * FROM " .DB_PREFIX. "vodinfo WHERE id IN (" .implode(',', $vodinfo_id). ")";
    		$q = $this->db->query($sql);
    		while($r = $this->db->fetch_array($q))
    		{
    			$video[$r['id']] = $r;
    		}
    	}
    	
    	$return['vcr_data'] = array();
    	if($fast_vcr_tmp && !empty($fast_vcr_tmp))
    	{
    		foreach($fast_vcr_tmp AS $k => $v)
    		{
    			$img = unserialize($video[$v['vodinfo_id']]['img_info']);
    			if(file_exists(FAST_EDIT_IMGDATA_PATH . $v['hash_id'] . '_start.img'))
    			{
    				$start_imgdata = file_get_contents(FAST_EDIT_IMGDATA_PATH . $v['hash_id'] . '_start.img');
    			}
    			if(file_exists(FAST_EDIT_IMGDATA_PATH . $v['hash_id'] . '_end.img'))
    			{
    				$end_imgdata   = file_get_contents(FAST_EDIT_IMGDATA_PATH . $v['hash_id'] . '_end.img');
    			}
    			$vcr = array(
    				'vodinfo_id' 	=> $v['vodinfo_id'],
    				'input_point' 	=> $v['input_point'],
    				'output_point' 	=> $v['output_point'],
    				'user_id' 		=> $v['user_id'],
    				'order_id' 		=> $v['order_id'],
    				'hash_id'		=> $v['hash_id'],
    				'src'			=> $v['img_path'],
    				'vcr_type'		=> $v['vcr_type'],
    				'start_imgdata'	=> $start_imgdata,
    				'end_imgdata'	=> $end_imgdata,
    				'frame_rate'	=> $video[$v['vodinfo_id']]['frame_rate'],
    				'duration'		=> $video[$v['vodinfo_id']]['duration'],
    				'title'			=> $video[$v['vodinfo_id']]['title'],
    				'width'			=> $video[$v['vodinfo_id']]['width'],
    				'height'		=> $video[$v['vodinfo_id']]['height'],
    				'hostwork'		=> $video[$v['vodinfo_id']]['hostwork'],
    				'video_path'	=> $video[$v['vodinfo_id']]['video_path'],
    				'video_filename'=> $video[$v['vodinfo_id']]['video_filename'],
    				'source_img' 	=> $img['host'].$img['dir'].$img['filepath'].$img['filename'],
    			);
    			$return['vcr_data'][] = $vcr;
    		}
    	}
    	//查询出快编临时添加的视频
    	$sql = "SELECT fa.*,vf.img_info,vf.title,vf.duration,vf.frame_rate,vf.width,vf.height,vf.video_path,vf.hostwork,vf.video_filename FROM " . DB_PREFIX . "fast_add_videos_tmp fa LEFT JOIN " .DB_PREFIX. "vodinfo vf ON vf.id = fa.vodinfo_id  WHERE fa.main_video_id = '" .$this->input['id']. "' AND fa.user_id = '" .$this->user['user_id']. "'";
    	$q = $this->db->query($sql);
   		while($r = $this->db->fetch_array($q))
        {
        	$img = unserialize($r['img_info']);
        	$r['source_img'] =  $img['host'].$img['dir'].$img['filepath'].$img['filename'];
        	$r['video_url'] = $r['hostwork'].'/'.$r['video_path'].MAINFEST_F4M;
    		$return['added_videos'][] = $r;
        }
        $return['vod_leixing'] = $this->settings['video_upload_type'];
        $return['date_search'] = $this->settings['date_search'];
    	$this->addItem($return);
    	$this->output();
    }
    
 	//获取片头，片尾或者片花数据
    public function getVcrData()
    {
    	$vcr_type = intval($this->input['vcr_type']);
    	$sql = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE vcr_type = '" .$vcr_type. "' AND status = 2 ORDER BY video_order_id DESC  LIMIT 0,10";
    	$q = $this->db->query($sql);
    	while($r = $this->db->fetch_array($q))
    	{
    		$img = unserialize($r['img_info']);
    		$vcr = array(
    			'id'			=> $r['id'],
    			'title' 		=> $r['title'],
    			'duration' 		=> $r['duration'],
    			'user_id' 		=> $r['user_id'],
    			'frame_rate'	=> $r['frame_rate'],
    			'width'			=> $r['width'],
    			'height'		=> $r['height'],
    			'hostwork'		=> $r['hostwork'],
    			'video_path'	=> $r['video_path'],
    			'video_filename'=> $r['video_filename'],
    			'source_img' 	=> $img['host'].$img['dir'].$img['filepath'].$img['filename'],
    			'video_url' 	=> $r['hostwork'].'/'.$r['video_path'].MAINFEST_F4M,
			    'video_m3u8' 	=> $r['hostwork'].'/'.$r['video_path'].str_replace('.mp4', '.m3u8', $r['video_filename']),
    		);
    		$this->addItem($vcr);
    	}
    	$this->output();
    }
}

$out = new vod_fast_edit_video();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'fast_edit_video';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>