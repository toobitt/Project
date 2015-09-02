<?php
require ('./global.php');
require_once CUR_CONF_PATH.'lib/functions.php';
define('MOD_UNIQUEID','department');
define('SCRIPT_NAME', 'Department');
class Department extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function count(){}	
	
	public function show()
	{
		$cond = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 200;
		$limit = ' LIMIT ' . $offset . ',' . $count;
		
		$sql = "SELECT t1.id,t1.fid,t1.name,t1.department_id,t1.hospital_id FROM " . DB_PREFIX . "departments t1 
				WHERE 1 " . $cond . $limit; 
		$q = $this->db->query($sql);
		
		$data = array();
		while ($row = $this->db->fetch_array($q)) 
		{	
			if(!$row['fid'])
			{
				$data[$row['department_id']]['father'] = $row;
			}
			else if($row['fid'])
			{
				$data[$row['fid']]['depart_child'][] = $row;
			}
		}
		
		if(!empty($data))
		{
			foreach ($data as $val)
			{
				$this->addItem($val);				
			}
		}
		
		$this->output();
	}
	
	public function get_condition()
	{
		$hospital_id = intval($this->input['hospital_id']);
		if(!$hospital_id)
		{
			$this->errorOutput(NOID);
		}
		
		$condition = '';
		//站点名称
		$condition .= ' AND t1.hospital_id = ' . $hospital_id;
		if($this->input['name'])
		{
			$condition .= ' AND t1.name LIKE "%'.trim($this->input['name']).'%"';
		}
		
		//$condition .= " AND t1.status = 1";
		
		$condition .= ' ORDER BY t1.order_id  ASC ';
		
		return $condition ;
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT t1.id,t1.name,t1.position,t1.hospital_id,t1.department_id,t1.introduction,t2.name as hospital_name FROM " . DB_PREFIX . "departments t1 
				LEFT JOIN " . DB_PREFIX . "hospital t2 
					ON t1.hospital_id = t2.hospital_id 
				WHERE t1.status =1 AND t1.id = {$id}";
		$data = $this->db->query_first($sql);
		
		
		$this->addItem($data);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>