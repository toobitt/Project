<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  vod_look_video extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function count()
	{
		if(!$this->input['collect_id'])
		{
			$this->errorOutput(NOID);
		}
		
		//取出该集合中视频个数
		$sql = "SELECT count(*) as total FROM  ".DB_PREFIX."vod_collect_video as cv  WHERE  cv.collect_id = ".urldecode($this->input['collect_id']);
	    $collect_video_nums = $this->db->query_first($sql);
	    echo json_encode($collect_video_nums);
	}
	
	/*参数:集合的id(collect_id)
	 *功能:查看集合里面视频信息
	 *返回值:该集合的信息与该集合里面所有视频的信息
	 * */
	public function look_video()
	{
		if(!$this->input['collect_id'])
		{
			$this->errorOutput(NOID);
		}
		
		//取出集合信息
		$sql = "SELECT vc.*,vs.name AS sort_name,ch.name as channel_name FROM  ".DB_PREFIX."vod_collect as vc LEFT JOIN ".DB_PREFIX."vod_media_node as vs ON vc.vod_sort_id = vs.id  LEFT JOIN ".DB_PREFIX."channel as ch ON ch.id = vc.source   WHERE vc.id = '".intval($this->input['collect_id'])."'";
	    $return['collect'] = $this->db->query_first($sql);
	    
		$return['collect']['create_time'] = date('Y-m-d',$return['collect']['create_time']);
		$return['collect']['update_time'] = date('Y-m-d',$return['collect']['update_time']);
		
		//取出该集合里面的视频信息
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		
		//查询出顶级类别供下面没有分类的时候用
		$sql = "SELECT * FROM ".DB_PREFIX."vod_media_node WHERE fid = 0";
		$q = $this->db->query($sql);
		$top_sorts = array();
		while($r = $this->db->fetch_array($q))
		{
			$top_sorts[$r['id']] = $r;
		}
		
		
		$sql = "SELECT cv.id as cid,cv.order_id,vf.*,vs.name AS sort_name,vs.color AS vod_sort_color FROM ".DB_PREFIX."vod_collect_video as cv  LEFT JOIN  ".DB_PREFIX."vodinfo as  vf 
		        ON cv.video_id = vf.id  LEFT JOIN ".DB_PREFIX."vod_media_node as vs ON vf.vod_sort_id = vs.id  WHERE cv.collect_id = '".urldecode($this->input['collect_id'])."' ORDER BY cv.order_id DESC ".$limit;

		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			if($r['sort_name'])
			{
				$r['vod_sort_id'] = $r['sort_name'];
			}
			else 
			{
				$r['vod_sort_id'] = $top_sorts[$r['vod_leixing']]['name'];
				$r['vod_sort_color'] = $top_sorts[$r['vod_leixing']]['color'];
			}
			
			$r['vod_leixing'] = $top_sorts[$r['vod_leixing']]['name'];
			
			$collects = unserialize($r['collects']);
			if($collects)
			{
				$r['collects'] = $collects;
			}
			else 
			{
				$r['collects'] = '';
			}
			$r['img_info'] = unserialize($r['img_info']);
			$r['img'] = $r['img_info']['host'].$r['img_info']['dir']  . '80x60/' . $r['img_info']['filepath'] . $r['img_info']['filename'];
			$r['duration'] = time_format($r['duration']);
			$r['status'] = $this->settings['video_upload_status'][$r['status']];
			$r['source'] = $this->settings['video_channel'][$r['source']];
			$r['create_time'] = date('Y-m-d h:i',$r['create_time']);
			$r['update_time'] = date('Y-m-d h:i',$r['update_time']);
			$return['collect_video'][] = $r;
		}
		
		$this->addItem($return);
		$this->output();
		
	}
	
}

$out = new vod_look_video();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'look_video';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>