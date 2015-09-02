<?php
define('MOD_UNIQUEID','shops');
require_once('global.php');
class shops_update extends adminUpdateBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		if(!$this->input['name'])
		{
			$this->errorOutput(NONAME);
		}
		
		$data = array(
			'name'			=> $this->input['name'],
			'tel'  			=> $this->input['tel'],
			'linkman' 		=> $this->input['linkman'],
			'address' 		=> $this->input['address'],
			'district_id' 	=> $this->input['district_id'],
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
		);
		
		$sql = " INSERT INTO ".DB_PREFIX."shops SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."shops SET order_id = {$vid} WHERE id = {$vid}";
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$data = array(
			'name'			=> $this->input['name'],
			'tel'  			=> $this->input['tel'],
			'linkman' 		=> $this->input['linkman'],
			'address' 		=> $this->input['address'],
			'district_id' 	=> $this->input['district_id'],
			'update_time' 	=> TIMENOW,
		);
		$sql = " UPDATE ".DB_PREFIX."shops SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '" .$this->input['id']. "'"; 
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	public function delete()
	{
		
	}
	
	public function audit()
	{
		
	}
	
	public function sort()
	{
		
	}
	
	public function publish()
	{
		
	}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new shops_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>