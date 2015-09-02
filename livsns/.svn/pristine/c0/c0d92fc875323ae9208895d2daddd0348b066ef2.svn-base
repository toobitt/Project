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
		$this->input['text'] = trim($this->input['text']);
		if (!$this->input['text'])
		{
			echo 'no text';
			exit;
		}
		$appid = $this->input['appid'];
		
		$pushNotify = new pushNotify();
		if (!$appid)
		{
			$pushNotify->setCert(ZS_DIR . 'apply_push.pems');
			$pushNotify->setAPNsHost(0);
		}
		else
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX .'certificate WHERE  appid='.$appid;
			$cert = $this->db->query_first($sql);
			$pushNotify->setCert(ZS_DIR . $cert['apply']);
			$pushNotify->setAPNsHost(0);
		}
		$connect = $pushNotify->connectToAPNS();
		if (!$connect)
		{
			$this->errorOutput('CONNECT_APPLE_FAILED');
		}
		$message = array(
			'badge' => 1,	
			'sound' => 'default',	
			'text' => $this->input['text'],	
			'module_id' => $this->input['module_id'],	
			'content_id' => $this->input['id'],	
		);
		$token = $this->input['token'];
		if (!$token)
		{
			$token = 'f03a63a9 ba2d44aa f17f5afa 32240d46 0216dd17 b96e00ad d3972c78 fd29eacf';
		}
		$suc = $pushNotify->send($token, $message);
		if ($suc)
		{
			echo 'send ' . $suc. '<pre>';
			print_r($message);
		}
		$pushNotify->closeConnections();
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