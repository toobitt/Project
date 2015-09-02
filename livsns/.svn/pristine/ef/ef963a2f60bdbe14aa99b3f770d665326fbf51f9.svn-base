<?php  
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  vod_add_newlist extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail()
	{	
		if(!$this->input['row_id'])
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
		
		
		$sql = "SELECT f.*,s.name AS sort_name,s.color FROM ".DB_PREFIX."vodinfo as f  left join ".DB_PREFIX."vod_media_node as s on f.vod_sort_id = s.id  WHERE f.id = '".intval($this->input["row_id"])."'";
		$arr = $this->db->query_first($sql);
	
		$return['subtitle'] = $arr['subtitle'];
		$return['comment'] = $arr['comment'];
		$return['keywords'] = $arr['keywords'];
		
		if($arr['sort_name'])
		{
			$return['vod_sort_id'] = $arr['sort_name'];
			$return['vod_sort_color'] = $arr['color'];
		}
		else
		{
			$return['vod_sort_id']    = $top_sorts[$return['vod_leixing']]['name'];
			$return['vod_sort_color'] = $top_sorts[$return['vod_leixing']]['color'];
		}
		
		if($return['starttime'])
		{
			$return['starttime'] = '('.date('Y-m-d',$return['starttime']).')';
		}
		else
		{
			$return['starttime'] = '';
		}
	
		$return["title"] = $arr['title'];
		$return['status'] = $this->settings['video_upload_status'][0];
		$return["create_time"] = date("Y-m-d H:i",TIMENOW);
		$return["row_id"] = intval($this->input["row_id"]);
		$return['addperson'] = $arr['addperson'];
		$return['bitrate'] = $arr['bitrate'];
		$return['duration'] = time_format($arr['duration']);
		$return['bitrate_color'] = $arr['bitrate_color'];
		$img_arr = $return['img_info'] = unserialize($arr['img_info']);
		$return['img'] = $img_arr['host'].$img_arr['dir'].'80x60/'.$img_arr['filepath'].$img_arr['filename'];
		$rgb = $r['bitrate']/100;
			
		if($rgb < 10)
		{
			$return['bitrate_color'] = $this->settings['bitrate_color'][$rgb];
		}
		else 
		{
			$return['bitrate_color'] = $this->settings['bitrate_color'][9];
		}
		
		if($this->input['pubinfo'])
		{
			$return['pubinfo'] = intval($this->input['pubinfo']);
		}
	    $this->addItem($return);
	    $this->output();
	}
}

$out = new vod_add_newlist();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'detail';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 

?>