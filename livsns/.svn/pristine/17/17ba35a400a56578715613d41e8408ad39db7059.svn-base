<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'vod');
require_once('global.php');
class  vod_get_vcr_data extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:vcr_id(视频片段的id)
	 *功能:获取视频片段的信息
	 *返回值:视频片段的返回值
	 * */
	public function  get_vcr_data()
	{
		if(!$this->input['vcr_id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT mv.*,vf.vod_leixing,vf.original_id as oid,vf.duration as max_duration,vf.totalsize,vf.img_info FROM ".DB_PREFIX."vod_mark_video as mv LEFT JOIN ".DB_PREFIX."vodinfo as vf ON vf.id = mv.original_id  WHERE mv.vodinfo_id = '".intval($this->input['vcr_id'])."'  ORDER BY order_id ASC ";
		$q = $this->db->query($sql);
		$return = array();
		while ($r = $this->db->fetch_array($q))
		{
			if(intval($r['vod_leixing']) == 4 && $r['oid'])
			{
				$sql = "SELECT duration FROM ".DB_PREFIX."vodinfo WHERE id = '".$r['oid']."'";
				$arr = $this->db->query_first($sql);
				$r['max_duration'] = $arr['duration'];
			}
			
			$img_arr = unserialize($r['img_info']);
			$r['img_src'] = $img_arr['host'].$img_arr['dir'].'80x60/'.$img_arr['filepath'].$img_arr['filename'];
			$r['format_start_time'] = time_format($r['start_time']);
			$r['format_duration'] = time_format($r['duration']);
			$r['totalsize'] = hg_fetch_number_format($r['totalsize'],true);
			$return[] = $r;
		}
		
		$this->addItem($return);
		$this->output();
	}

}

$out = new vod_get_vcr_data();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'get_vcr_data';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>