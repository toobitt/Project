<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'vod');
require_once('global.php');
class  vod_tagging extends adminBase
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
    public  function video_tagging()
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
    	$sql = "SELECT vt.*,it.img_path FROM " . DB_PREFIX . "vcr_tmp vt LEFT JOIN " .DB_PREFIX. "img_tmp it ON it.hash_id = vt.hash_id WHERE vt.user_id = '" .$this->user['user_id']. "' AND vt.main_video_id = '" .$this->input['id']. "' ORDER BY vt.order_id ASC ";
    	$q = $this->db->query($sql);
    	$vcr_tmp = array();
    	$vodinfo_id = array();
    	while($r = $this->db->fetch_array($q))
    	{
    		$vcr_tmp[] = $r;
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
    	if($vcr_tmp && !empty($vcr_tmp))
    	{
    		foreach($vcr_tmp AS $k => $v)
    		{
    			$img = unserialize($video[$v['vodinfo_id']]['img_info']);
    			$vcr = array(
    				'vodinfo_id' 	=> $v['vodinfo_id'],
    				'input_point' 	=> $v['input_point'],
    				'output_point' 	=> $v['output_point'],
    				'user_id' 		=> $v['user_id'],
    				'order_id' 		=> $v['order_id'],
    				'hash_id'		=> $v['hash_id'],
    				'src'			=> $v['img_path'],
    				'vcr_title'		=> $v['title'],
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
    	
    	$this->addItem($return);
    	$this->output();
    }
		
}

$out = new vod_tagging();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'video_tagging';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>