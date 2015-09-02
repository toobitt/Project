<?php
define('SCRIPT_NAME', 'mobile_api_settings');
require_once('./global.php');
class mobile_api_settings extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function create()
	{
		
	}
	function sort()
	{
		
	}
	function audit()
	{
		
	}
	function publish()
	{
		
	}
	function delete()
	{
	
	}
	function show()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'api_settings LIMIT 0,1';
		$this->addItem($this->db->query_first($sql));
		$this->output();
	}
	function update()
	{
		//$this->erroroutput(var_export($this->input,1));
		$data = array(
		'protocol'=>intval($this->input['protocol']),	
		'host'=>urldecode($this->input['host']),	
		'directory'=>urldecode($this->input['directory']),	
		'port'=>urldecode($this->input['port']),	
		'data_format'=>urldecode($this->input['data_format']),	
		'ps'=>urldecode($this->input['ps']),	
		'uname'=>urldecode($this->input['uname']),	
		'pwd'=>urldecode($this->input['pwd']),	
		'token'=>urldecode($this->input['token']),	
		'status'=>intval($this->input['status']),	
		);
		$this->db->query('DELETE FROM '.DB_PREFIX.'api_settings');
		$sql = 'INSERT INTO '.DB_PREFIX.'api_settings SET ';
		foreach($data as $k=>$v)
		{
			$sql .= "{$k} = '".$v."',";
		}
		$this->db->query(rtrim($sql, ','));
		unset($data['ps']);
		$configs = '
<?php
/*
	Build-Date：'.date('Y-m-d h:i:s', TIMENOW).'
*/
$api_configs = '.var_export($data,1).';
?>';
		file_put_contents('../api/config.php', trim($configs));
		$this->addItem($data);
		$this->output();
	}
	function unknown()
	{
		$this->erroroutput(NO_METHOD);
	}
	function verifyToken()
	{
	}
}
include(ROOT_PATH . 'excute.php');