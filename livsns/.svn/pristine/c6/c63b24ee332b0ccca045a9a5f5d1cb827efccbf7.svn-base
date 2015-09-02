<?php
define('MOD_UNIQUEID','notice');
require ('./global.php');
define('SCRIPT_NAME', 'getNotice');
class getNotice extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function detail(){}
	public function count(){}	
	
	public function show()
	{
		$cond = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$limit = ' LIMIT ' . $offset . ',' . $count;
		
		//站点公告
		if($this->input['station_id'])
		{
			$sql = "SELECT " . $this->input['fields'] . " FROM ".DB_PREFIX."notice t1 WHERE 1 " . $cond . $limit;
		}
		else
		{
			$sql = "SELECT " . $this->input['fields'] . "t2.name station_name, t3.name region_name FROM " . DB_PREFIX . "notice t1 
					LEFT JOIN " . DB_PREFIX . "station t2 
						ON t1.station_id = t2.id 
					LEFT JOIN " . DB_PREFIX . "region t3
						ON t2.region_id = t3.id
					WHERE 1 " . $cond . $limit; 
		}
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q)) 
		{	
			$this->addItem($row);
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		$this->input['fields'] = 't1.id,t1.title,t1.create_time,t1.station_id,';
		//通知id
		if($this->input['id'])
		{
			$condition .=" AND t1.id = " . intval($this->input['id']);
			$this->input['fields'] = $this->input['fields'] . 't1.content,';
		}
		//根据站点查询
		if($this->input['station_id'])
		{
			$this->input['fields'] = 't1.id,t1.title,t1.create_time ';
			$condition .=" AND t1.station_id = " . intval($this->input['station_id']);
		}
		
		$region_flag = false;
		//区域公告
		if($this->input['region_id'])
		{
			$sql = "SELECT id FROM ".DB_PREFIX."station WHERE region_id = ".$this->input['region_id'];
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$station_id[] = $r['id'];
			}
			
			if($station_id)
			{
				$station_ids = implode(',', $station_id);
				$condition .= " AND t1.station_id IN (" . $station_ids . ")";
			}
			else //站点id不存在，就查询全局公告
			{
				$region_flag = true;
				$condition .= " AND t1.station_id = -1";
			}
		}
		
		//查询已审核通知
		$condition .= " AND t1.state=1";
		
		//查询单个公告时不查全局
		if(!$this->input['id'] && !$region_flag)
		{
			//附加查询全局公告
			$condition .= " OR t1.station_id=-1 AND t1.state=1";
		}
		
		//查询排序方式(升序或降序,默认为降序)
		$hgupdown .= $this->input['descasc'] ? $this->input['descasc'] : ' DESC ';
		
		//create_time<orderid
		$condition .= " ORDER BY t1.create_time ". $hgupdown . ",t1.order_id " . $hgupdown;
		
		return $condition ;
	}
}
include(ROOT_PATH . 'excute.php');
?>