<?php
require_once('./global.php');
define('SCRIPT_NAME', 'modules');
define('MOD_UNIQUEID','modules');
class modules extends coreFrm
{	
	protected $value = array(
	'id'		=>	'',
	'mod_uniqueid'	=>	'',
	'name'		=>	'',
	'app_uniqueid'		=>	'',
	'application_id'	=>	'',
	'host'		=>	'',
	'dir'		=>	'',
	'file_name'	=>	'',
	'file_type'	=>	'',
	'func_name'	=>	'',
	'need_auth'	=> 	'',
	'main_module'=>'',
	'class_id'	=>'',
	);
	protected $check_field = array(
	'mod_uniqueid', 'name', 'app_uniqueid', 'application_id',
	);
	function __construct()
	{
		parent::__construct();
		//$this->input['main_module'] = $this->input['menu_pos'];
		$this->verifyToken();
		
	}
	protected function verify_data_integrity()
	{
		foreach ($this->value as $key=>$value)
		{
			if(in_array($key, $this->check_field))
			{
				if(!$this->input[$key])
				{
					$this->errorOutput($key . ' ' . EMPTY_FIELD);
				}
			}
			$this->value[$key] = $this->input[$key];
		}
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function delete()
	{
	if(!$this->input['id'])
		{
			$this->errorOutput(NO_ID);
		}
		$sql = 'SELECT id FROM '.DB_PREFIX.'modules WHERE id IN('.$this->input['id'].')';
		$app = $this->db->query_first($sql);
		if(!$app)
		{
			$this->errorOutput(RECORD_NOT_EXISTS);
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'modules WHERE id IN('.$this->input['id'].')';
		$this->db->query($sql);
		$this->addItem($this->input['id']);
		$this->output();
	}
	function show()
	{
		$this->verify_data_integrity();
		$this->excute_sql();
		$this->addItem($this->value);
		$this->output();
	}
	protected function excute_sql()
	{
		if(!$this->value['id'])
		{
			$this->errorOutput(NO_ID);
		}
		$sql = 'REPLACE INTO '.DB_PREFIX.'modules SET ';
		foreach ($this->value as $k=>$v)
		{
			$sql .= " {$k} = \"{$v}\",";
		}
		$sql = trim($sql, ',');
		$this->db->query($sql);
	}
}
include(ROOT_PATH . 'excute.php');