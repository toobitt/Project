<?php
require('global.php');
define('MOD_UNIQUEID','dynpro');
class dynpro extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	public function show()
	{
//		if($this->user['group_type'] > MAX_ADMIN_TYPE)
//		{
//			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
//			if(!in_array('dynpro',$action))
//			{
//				$this->errorOutput("NO_PRIVILEGE");
//			}
//		}
		
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? $this->input['count'] : 20;
		$orderfield = $this->input['orderfield'] ? $this->input['orderfield'] . ' ' : 'id ';
		$descasc = $this->input['descasc'] ? $this->input['descasc'] : ' DESC';
		$orderby = " ORDER BY " . $orderfield . $descasc;
		$data_limit = " LIMIT " . $offset . ", " . $count;
		$sql = "SELECT * 
				FROM " . DB_PREFIX . "dynpro WHERE 1 " . $condition . $orderby . $data_limit;
		$q = $this->db->query($sql);
		include(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publishconfig = new publishconfig();
		$sites = $this->publishconfig->get_sites();
		while ($row = $this->db->fetch_array($q)) {
			$row['create_time_show'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time_show'] = date('Y-m-d H:i:s', $row['update_time']);
			$row['site_name']        = $row['site_id'] ? $sites[$row['site_id']] : '全局共享';
			//$row['paraset']  = $row['paraset'] ? unserialize($row['paraset']) : array();
			$this->addItem($row);
		}
		$this->output();
	}
	
	public function detail()
	{	
		$id = intval($this->input['id']);
		if ($id) {
			$data_limit = " AND id = " . $id;
		} 
		else {
			$data_limit = " LIMIT 1 ";
		}
		$sql = "SELECT * 
			    FROM " . DB_PREFIX . "dynpro WHERE 1 " . $data_limit;
		$info = $this->db->query_first($sql);
		if ($info) {
			$info['create_time_show'] = date('Y-m-d H:i', $info['create_time']);
			$info['update_time_show'] = date('Y-m-d H:i', $info['update_time']);
			$info['paraset'] = $info['paraset'] ? unserialize($info['paraset']) : array();
		}
		$this->addItem($info);
		$this->output();
	}
	
	public function count()
	{	
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(*) AS total
				FROM " . DB_PREFIX . "dynpro WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		echo json_encode($total);
	}
	
	private function get_condition()
	{		
		$condition = '';
		if (isset($this->input['site_id'])) {
			$site_id = intval($this->input['site_id']) . ',0';
			$condition .= " AND site_id IN (" . $site_id . ")";
		}
		return $condition;
	}
	
	public function data_source_list()
	{
		include(CUR_CONF_PATH . 'lib/common.php');
		$data_source = common::get_data_source();
		$this->addItem($data_source);
		$this->output();
	}	
}
$out = new dynpro();
$action = $_INPUT['a'];
if (!method_exists($out,$action)) {
	$action = 'show';
}
$out->$action();

?>
