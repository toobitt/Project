<?php
define('ROOT_DIR', '../../');
define('WITHOUT_DB', false);
define('MOD_UNIQUEID','notice');
require(ROOT_DIR . 'global.php');
require('./lib/push_notify.class.php');
class notify extends outerReadBase
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	function count()
	{
		
	}
	function detail()
	{
		
	}
	public function show()
	{
		$this->input['message'] = trim($this->input['message']);
		if (!$this->input['message'])
		{
			$this->errorOutput('NO_MESSAGE');
		}
		
		$token = trim($this->input['device_token']);
		if (!$token)
		{
			$this->errorOutput('NO_DEVICE_TOKEN');
		}
		
		if (strlen($token) > 60)
		{
			$appidkey = 'iosAppid';
		}
		else
		{
			$appidkey = 'androidAppid';
		}
		$appid = intval($this->input['appid']);
		
		$appid = $appid ? $appid : $this->settings[$appidkey];
		if (!$appid)
		{
			$this->errorOutput('NO_APPID');
		}
		if ($appidkey == 'iosAppid')
		{
			$pushNotify = new pushNotify();
			$sql = 'SELECT * FROM ' . DB_PREFIX .'certificate WHERE  appid='.$appid;
			$cert = $this->db->query_first($sql);
			$pushNotify->setCert(ZS_DIR . $cert['apply']);
			$pushNotify->setAPNsHost(0);
			$connect = $pushNotify->connectToAPNS();
			if (!$connect)
			{
				$this->errorOutput('CONNECT_APPLE_FAILED');
			}
			$message = array(
				'badge' => 1,	
				'sound' => 'default',	
				'text' => $this->input['message'],	
				'module_id' => $this->input['module'],	
				'content_id' => $this->input['content_id'],	
			);
			$suc = $pushNotify->send($token, $message);
			$pushNotify->closeConnections();
		}
		$this->addItem_withkey('result', ($suc ? 1 : 0));
		$this->output();
	}
}
$module = 'notify';
$$module = new $module();  

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
	$func = 'show';	
}
$$module->$func();
?>