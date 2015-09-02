<?php
define('MOD_UNIQUEID','food_user');
require_once('global.php');
class food_user_update extends adminUpdateBase
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
		
		if(!$this->input['password'])
		{
			$this->errorOutput('请填写密码');
		}
		
		$data = array(
			'name' 			=> $this->input['name'],
			'password' 		=> $this->input['password'],
			'tel'			=> $this->input['tel'],
			'address'		=> $this->input['address'],
			'create_time' 	=> $this->input['create_time'],
			'update_time' 	=> $this->input['update_time'],
		);
		
		$sql = " INSERT INTO ".DB_PREFIX."user SET ";
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

$out = new food_user_update();
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