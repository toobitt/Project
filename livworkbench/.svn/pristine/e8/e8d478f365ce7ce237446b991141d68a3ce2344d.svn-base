<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 343 2011-11-26 06:12:05Z develop_tong $
***************************************************************************/ 
define('DEBUG_MODE', true);
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', '_default');
require('./global.php');
require(ROOT_PATH . 'lib/class/curl.class.php');
class _default extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		include(ROOT_DIR . 'lib/class/cron.class.php');
		$crond = new crond();
		if ($this->settings['croncmd'])
		{
			$crond->setCronCmd($this->settings['croncmd']);
		}
		$cron_status = $crond->isRun();
		

		$curl = new curl($this->settings['verify_custom_api']['host'], $this->settings['verify_custom_api']['dir']);
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->setToken('');
		$curl->setCurlTimeOut(5);
		$curl->setErrorReturn('');
		$curl->mAutoInput = false;
		$curl->initPostData();
		$postdata = array(
			'a'				=>	'get_user_info',
		);
		foreach ($postdata as $k=>$v)
		{
			$curl->addRequestData($k, $v);
		}
		$license = $curl->request('get_access_token.php');
		if (!$license)
		{
			$this->ReportError('未获取到授权信息，请确认服务器网络正常或联系软件提供商');
		}
		if ($license['ErrorCode'] == 'APP_AUTH_EXPIRED')
		{
			$this->ReportError('授权已到期，请联系软件提供商');
		}
		$license = $license[0];
		if ($license['appid'])
		{
			$license['expire'] = date('Y-m-d', $license['expire_time']);
			$license['leftday'] = intval(($license['expire_time'] - TIMENOW) / 86400);
			$this->tpl->addVar('license', $license);
		}

		
			
		$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
		$curl->setSubmitType('post');
		$curl->setCurlTimeOut(5);
		$curl->setReturnFormat('json');
		$ret = $curl->request('applications.php');
		$instlled_apps = array();
		$app_stats = array();
		if (is_array($ret))
		{
			foreach ($ret AS $v)
			{
				$start_time = microtime();
				$result = $this->check_status($v);
				$v['runtime'] = hg_page_debug($start_time);
				$v['inited'] = $result[1]['define']['INITED_APP'];
				$v['debuged'] = $result[1]['debuged'];
				$v['http_code'] = $result[0]['http_code'];
				$v['db'] = $result[1]['db'];
				$v['dbconnected'] = $result[1]['dbconnected'];
				$v['connect_time'] = $result[1]['connect_time'];
				$v['ip'] = gethostbyname($v['host']);
				$v['db']['ip'] = gethostbyname($v['db']['host']);
				$v['api_dir'] = $result[1]['api_dir'];
				$v['config_file_purview'] = $result[1]['config_file_purview'];
				$v['data_file_purview'] = $result[1]['data_file_purview'];
				$v['cache_file_purview'] = $result[1]['cache_file_purview'];
				$v['freespace'] = $result[1]['freespace'];
				if ($v['bundle'] == $_GET['b'])
				{
				print_r($v);exit;
				}
				$app_stats[$v['bundle']] = $v;
			}
		}
		if ($this->settings['App_livmedia'])
		{
			$curl = new curl($this->settings['App_livmedia']['host'], $this->settings['App_livmedia']['dir'] . 'admin/');
			$curl->setErrorReturn('');
			$curl->setCurlTimeOut(30);
			$curl->mAutoInput = false;
			$curl->initPostData();
			$curl->addRequestData('a', 'stats');
			$vod_status = $curl->request('vod.php');
			$vod_status = $vod_status[0];
		}
		$this->tpl->addVar('app_stats', $app_stats);
		$this->tpl->addVar('vod_status', $vod_status);
		$this->tpl->addVar('cron_status', $cron_status);
		$this->tpl->outTemplate('stats');
	}

	public function check_status($app)
	{
		$url = 'http://' . $app['host'] . '/' . $app['dir'] . 'configuare.php';
	//	echo $url . '<br />';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
			
		curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		if($type == 'https')
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('a' => 'settings', 'appid' => APPID, 'appkey' => APPKEY,'is_writes'=>1));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$ret = curl_exec($ch);
		$ret = json_decode($ret, 1);
		$head_info = curl_getinfo($ch);
		curl_close($ch);
		return array($head_info, $ret);
	}
	
} 
include (ROOT_PATH . 'lib/exec.php');
?>