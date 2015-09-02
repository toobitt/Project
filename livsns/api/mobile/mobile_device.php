<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
define('MOD_UNIQUEID','mobile');
define('SCRIPT_NAME', 'mobileDeviceApi');
class mobileDeviceApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function count(){}
	function detail(){}
	
	public function show()
	{
		$res = array();
		if ($this->input['device_token'])
		{
			$offset = intval($this->input['offset']);
			$count = intval($this->input['count']);
			$count = $count ? $count : 1;
			$limit = " limit {$offset}, {$count}";
			
			$cond = ' AND device_token = "'.$this->input['device_token'].'"';
	
			$sql = 'SELECT a.*,b.device_name as types,c.device_os as system,d.client_name as program_name FROM ' . DB_PREFIX . 'device a
						LEFT JOIN '.DB_PREFIX.'device_library b
					ON a.types=b.id 
						LEFT JOIN '.DB_PREFIX.'device_os c 
					ON a.system=c.id 
						LEFT JOIN '.DB_PREFIX.'client d 
					ON a.program_name=d.id WHERE 1 ' . $cond . $limit;
			$res = $this->db->query_first($sql);
		}
		
		if(!$res && $this->input['uuid'])
		{
			$sql = 'SELECT a.*,b.device_name as types,c.device_os as system,d.client_name as program_name FROM ' . DB_PREFIX . 'device a
					LEFT JOIN '.DB_PREFIX.'device_library b
						ON a.types=b.id 
					LEFT JOIN '.DB_PREFIX.'device_os c 
						ON a.system=c.id 
					LEFT JOIN '.DB_PREFIX.'client d 
						ON a.program_name=d.id 
					WHERE 1 AND a.uuid = "' . $this->input['uuid'] . '"';
			$res = $this->db->query_first($sql);
		}
		$this->addItem($res);
		$this->output();
	}

}

include(ROOT_PATH . 'excute.php');

?>