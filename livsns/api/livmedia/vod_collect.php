<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
define('MOD_UNIQUEID', 'vod');
class  vod_collect extends outerReadBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
		
		if($this->input['sort_name'])
		{
			$return['sort'] = urldecode($this->input['sort_name']);
		}
		else 
		{
			$return['sort'] = 0;
		}
		
		$sql = "SELECT vc.*,vs.sort_name,vf.starttime FROM ".DB_PREFIX."vod_collect as vc  LEFT JOIN ".DB_PREFIX."vod_sort as vs ON  vc.vod_sort_id = vs.id LEFT JOIN ".DB_PREFIX."vodinfo as vf ON vf.id = vc.vodinfo_id  WHERE  1 ". $condition ."  ORDER BY collect_order_id DESC ".$limit;
		$q  = $this->db->query($sql);
		$this->setXmlNode('collect','sort');
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d h:i',$r['create_time']);
			$r['update_time'] = date('Y-m-d h:i',$r['update_time']);
			if($r['starttime'])
			{
				$r['starttime'] = '('.date('Y-m-d',$r['starttime']).')';
			}
			else
			{
				$r['starttime'] = '';
			}
			$return['collect_info'][] = $r;
		}
		$this->addItem($return);
		$this->output();
	}
	
	public function count()
	{
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'vod_collect as vc  WHERE 1 '.$this->get_condition();
		$collect_total = $this->db->query_first($sql);
		echo json_encode($collect_total);		
	}
	
	public function get_condition()
	{
		$condition = "";
		if($this->input['id'])
		{
			$condition .= " AND vc.id = '".intval($this->input['id'])."'";
		}
		
		if($this->input['collect_name'])
		{
			$condition .= ' AND vc.collect_name LIKE "%'.urldecode($this->input['collect_name']).'%"';
		}
		
		if($this->input['vod_sort_id'])
		{
			$condition .= " AND vc.vod_sort_id = '".intval($this->input['vod_sort_id'])."'";
		}
		
		if($this->input['create_time'])
		{
			$condition .= " AND vc.create_time = '".urldecode($this->input['create_time'])."'";
		}

		if($this->input['k'] || urldecode($this->input['k'])== '0')
		{
			$condition .= ' AND  vc.collect_name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		
		if($this->input['sort_name'])
		{
			$condition .= " AND vc.vod_sort_id = '".intval($this->input['sort_name'])."'";
		}
		
		if($this->input['collect_sort_id'] && intval($this->input['collect_sort_id']) != -1)
		{
			$condition .= " AND vc.vod_sort_id = '".intval($this->input['collect_sort_id'])."'";
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND vc.create_time >= ".$start_time;
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND vc.create_time <= ".$end_time;
		}
		
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			
			switch(intval($this->input['date_search']))
			{
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  vc.create_time > ".$yesterday." AND vc.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  vc.create_time > ".$today." AND vc.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  vc.create_time > ".$last_threeday." AND vc.create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  vc.create_time > ".$last_sevenday." AND vc.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		$condition .= " AND vc.is_auto = 0 ";
		return $condition;
 
	}
	
	/*根据集合id查询出集合里面所有的视频*/
	public function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = " SELECT * FROM ".DB_PREFIX."vod_collect_video WHERE collect_id = '".intval($this->input['id'])."'";
		$q   = $this->db->query($sql);
		$video_ids = array();
		while ($r = $this->db->fetch_array($q))
		{
			$video_ids[] = $r['video_id'];
		}
		
		$ids = implode(',',$video_ids);
		$video_info = array();
		if($ids)
		{
			$sql = " SELECT * FROM " .DB_PREFIX. "vodinfo WHERE id IN (".$ids.")";
			$q   = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$video_info[] = $r;
			}
		}
		
		$this->addItem($video_info);
		$this->output();
	}
	
	public function get_video_count()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = " SELECT count(*) as total FROM ".DB_PREFIX."vod_collect_video WHERE collect_id = '".intval($this->input['id'])."'";
		$total = $this->db->query_first($sql);
		echo json_encode($total);		
	}

}

$out = new vod_collect();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>