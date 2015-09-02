<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: alive.php 19886 2013-04-08 02:01:25Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','alive');
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class aliveApi extends outerReadBase
{
	private $mLivemms;
	private $mLive;
	function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
		$this->mLive = $this->settings['wowza']['dvr_server'];
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function show()
	{
		//live
		if ($this->mLive)
		{
			$_host 			= $this->settings['wowza']['dvr_server']['host'];
			$_apidir_output = $this->settings['wowza']['dvr_server']['dir'];
			
			$ret_ntpTime = $this->mLivemms->outputNtpTime($_host, $_apidir_output);
		}
		else 
		{
			$host 			= $this->settings['wowza']['live_server']['host'];
			$apidir_output 	= $this->settings['wowza']['live_server']['output_dir'];
			
			$ret_ntpTime = $this->mLivemms->outputNtpTime($host, $apidir_output);
		}
		
		if ($ret_ntpTime['result'])
		{
			$ntpTime = $ret_ntpTime['ntp']['utc'];
		}
		
		$info = array(
			'current' => $ntpTime ? $ntpTime : TIMENOW . '000',
		);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		
	}
	public function detail()
	{
		
	}
}

$out = new aliveApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>