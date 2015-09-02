<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 4234 2011-07-28 05:14:16Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','transcode_config');
class vod_config_type extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	public function index(){}
	
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();

		$sql = "SELECT * FROM ".DB_PREFIX."vod_config_type  WHERE 1 " . $condition . " ORDER BY id  DESC  ".$limit;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d',$r['create_time']);
			$this->addItem($r);
		}
			
		$this->output();	
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."vod_config WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	public function get_condition()
	{
		$condition = "";
		if($this->input['id'])
		{
			$condition .= " AND id = '".intval($this->input['id'])."'";
		}
		
		if($this->input['k'] || urldecode($this->input['k'])== '0')
		{
			$condition .= ' AND  name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
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
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	public function getVodConfig()
	{
		//$sql = "SELECT v.name AS type_name,c.*,c.id AS config_id FROM ".DB_PREFIX."vod_config c 
				//LEFT JOIN ".DB_PREFIX."vod_config_type v ON v.id=c.type_id";
		$sql_one = "SELECT id,name FROM " .DB_PREFIX. "vod_config_type";
		$sql_two = "SELECT * FROM " .DB_PREFIX. "vod_config";
		
		$ret1 = $this->db->fetch_all($sql_one);
		$ret2 = $this->db->fetch_all($sql_two);
		
		foreach($ret1 as $key => $val)
		{
			foreach($ret2 as $k => $v)
			{
				if($val['id'] == $v['type_id'])
				{
					$ret1[$key]['include'][] = $v;
				}
			}
		}
		
		$this->addItem($ret1);
		$this->output(); 
	}
	public function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."vod_config_type  WHERE id = '".intval($this->input['id'])."'"; 
		$return = $this->db->query_first($sql);
		$this->addItem($return);
		$this->output();
	}
	
}

$out = new vod_config_type();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>