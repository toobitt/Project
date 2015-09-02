<?php
define('MOD_UNIQUEID','jpush');//模块标识
require './global.php';
require CUR_CONF_PATH . 'lib/vendor/autoload.php';

use JPush\Model as M;
use JPush\JPushClient;
use JPush\JPushLog;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;


class jpush extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function push()
	{
		$app_key = $this->settings['jpushkey'];
		$master_secret = $this->settings['jpushsecret']; //'46867b6b0c46b34ee6848b56';
		$client = new JPushClient($app_key, $master_secret);
		$device_token = $this->input['device_token'];
		if (!$device_token)
		{
			$this->errorOutput('NO_DEVICE_TOKEN');
		}
		$message = $this->input['message'];		
		if (!$message)
		{
			$this->errorOutput('NO_MESSAGE');
		}
		$module  = $this->input['module'];
		$content_id  = $this->input['content_id'];
		if ($module)
		{
			$options = array(
				$module => $content_id
			);
		}
		else
		{
			$options = array();
		}
		
		$result = $client->push()
			->setPlatform(M\Platform('android'))
			->setAudience(M\audience(M\alias(array($device_token))))
			->setNotification(M\notification($message, M\android($message, '', 1,$options)))
			->send();
		$this->addItem_withkey('result', ($result->isOk ? 1 : 0));
		$this->output();
	}
	
	public function show()
	{
	}
	public function detail()
	{
	}
	public function count()
	{
	}
}
$out = new jpush();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'push';
}
$out->$action();

?>