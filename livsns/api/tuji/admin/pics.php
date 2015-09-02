<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID','tuji');//模块标识
class pics extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	function index()
	{
		
	}
	
	function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
		$sql = 'SELECT p.*,t.title as tuji_title,t.path as tuji_path,s.sort_name FROM '.DB_PREFIX.'pics p LEFT JOIN '
		.DB_PREFIX.'tuji t ON p.tuji_id = t.id LEFT JOIN '
		.DB_PREFIX.'tuji_sort s ON s.id = t.tuji_sort_id WHERE 1 '.$condition.'  ORDER BY p.order_id DESC '.$limit;
		
		$this->setXmlNode('pics', 'pic');
		$q  = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d h:i:s',$r['update_time']);
			$r['img_info'] = unserialize($r['img_info']);
			$r['pic_url'] = $r['img_info']['host'] . $r['img_info']['dir'] . '160x/' . $r['img_info']['filepath'] . $r['img_info']['filename'];
			$r['status'] = $r['status'] ? '已审核' : '待审核';
			$this->addItem($r);
		}
		
		$this->output();		
	}
	
	function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX.'pics as p WHERE 1 '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	function get_condition()
	{
		$condition = "";
		
		if($this->input['k'] || trim(urldecode($this->input['k']))== '0')
		{
			$condition .= ' AND  p.old_name  LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		
		if(intval($this->input['image_status']) == 1)
		{
			$condition .= " AND p.status = 0 ";
		}
		else if(intval($this->input['image_status']) == 2)
		{
			$condition .= " AND p.status = 1 ";
		}
		
		if($this->input['tuji_name'] && intval($this->input['tuji_name'])!= -1)
		{
			$condition .= " AND p.tuji_id = '".intval($this->input['tuji_name'])."'";
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND p.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND p.create_time <= '".$end_time."'";
		}

		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  p.create_time > '".$yesterday."' AND p.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  p.create_time > '".$today."' AND p.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  p.create_time > '".$last_threeday."' AND p.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  p.create_time > '".$last_sevenday."' AND p.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		return $condition;
	}
	
	function detail()
	{
		$ret = array();
		if($this->input['id'])
		{
			$condition = ' WHERE id in(' . urldecode($this->input['id']) .') ORDER BY order_id DESC ';
			$sql = " SELECT * FROM " . DB_PREFIX . "pics ".$condition;
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				$r['img_info'] = unserialize($r['img_info']);
				$r['pic_url'] = hg_fetchimgurl($r['img_info'], 160);
				$ret['info'][] = $r;
			}
		}
		else 
		{
			$ret['mode'] = intval($this->input['mode']);
		}
		
		$this->addItem($ret);
		$this->output();
	}	
}
$out = new pics();
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