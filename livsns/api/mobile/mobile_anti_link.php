<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
define('MOD_UNIQUEID','mobile');
define('SCRIPT_NAME', 'mobileAntiLinkApi');
class mobileAntiLinkApi extends outerReadBase
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
		
		if (!$this->input['device_token'])
		{
			$this->errorOutput(NODEVICETOKEN);
		}
		
		$cond = ' AND device_token = "'.$this->input['device_token'].'"';
		
		$appid = $this->user['appid'];
		if($appid)
		{
			$cond .= ' AND appid = '.$appid;
		}
		
		$sql = 'SELECT device_token FROM ' . DB_PREFIX . 'device  WHERE 1 ' . $cond;
		$res = $this->db->query_first($sql);
		
		if($res['device_token'])
		{
			$this->addItem(MOBILE_ANTI_LINK_TOKEN);
		}
		
		$this->output();
	}

}

include(ROOT_PATH . 'excute.php');

?>