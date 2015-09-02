<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 343 2011-11-26 06:12:05Z develop_tong $
***************************************************************************/
define('WITHOUT_LOGIN', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'datacount');
define('VERSION_CODE', 12);
require('./global.php');
require(ROOT_PATH . 'lib/class/curl.class.php');

if (!function_exists('hg_page_debug'))
{
	function hg_page_debug($starttime = STARTTIME) 
	{
		$mtime = explode(' ', microtime());
		$starttime = explode(' ', $starttime);
		$totaltime = sprintf('%.6f', ($mtime[1] + $mtime[0] - $starttime[1] - $starttime[0]));
		$run = 'Processed in ' . $totaltime . ' second(s), ';
		$memory = memory_get_usage() - MEMORY_INIT;
		$memory = 'Memory:' . hg_fetch_number_format($memory, 1);
		return $run . $memory;
	}
}

class datacount extends uiBaseFrm
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
	}
	
	public function stats()
	{
		if ($this->settings['mcphost'] != hg_getip())
		{
			echo 'not local';
			exit;
		}
		$datacount = new datacount_class($this->settings);
		$datacount->stats();
		echo 'success';
	}

	public function test()
	{
		$datacount = new datacount_class($this->settings);
		$data = $datacount->state_info();
		print_r($data);
	}
}
include (ROOT_PATH . 'lib/exec.php');

class datacount_class
{	
	private $mState =array();
	private $mRuntime =array();
	private $mSettime =array();
	private $settings = array();
	private $mCondition = array();
	function __construct($settings = array())
	{
		global $gCache;
		$this->cache = $gCache;
		$this->db = hg_checkDB();
		$this->settings = $settings;
	}

	function __destruct()
	{
	}
	
	public function stats()
	{
		$config_cache = CACHE_DIR . 'cacheconfig';
		if (!is_file($config_cache) || (is_file($config_cache) && (time() - filemtime($config_cache)) > 600))
		{
			$paras = array('customer' => CUSTOM_APPID, 'debug_mode' => DEBUG_MODE, 'version' => VERSION_CODE);
			$config = $this->curl('http://stat.cloud.hogesoft.com/customer_config.php', $paras);
			file_put_contents($config_cache, json_encode($config));
		}
		else
		{		
			$config = @file_get_contents($config_cache);
			$config = json_decode($config, 1);
		}
		if (!$config)
		{
			echo 'no config';
			exit;
		}
		if (is_file(CACHE_DIR . 'datacount.lock') && (time() - filemtime(CACHE_DIR . 'datacount.lock')) < 600)
		{
			echo 'running';
			exit;
		}
		$runtime = @file_get_contents(CACHE_DIR . 'datacount.php');
		$runtime = json_decode($runtime, 1);
		$this->mRuntime = $runtime;
		$this->mSettime = $config['croncycle'];
		if ((time() - $this->mRuntime['whole']) < $this->mSettime['whole'])
		{
			echo 'not arrive run time';
			exit;
		}
		file_put_contents(CACHE_DIR . 'datacount.lock', 'lock');
		if ($this->check_update($config['version']))
		{
			$this->update($config['fileurl']);			
			echo 'updated';
			//@unlink(CACHE_DIR . 'datacount.lock');
			//exit;
		}
		$this->mRuntime['whole'] = time();
		$this->mState = $config['state'];
		$this->mCondition = $config['condition'];
		$data = $this->get_data();
		$state_info = $this->state_info();
		file_put_contents(CACHE_DIR . 'datacount.php', json_encode($this->mRuntime));
		$state_info = rawurlencode(base64_encode(json_encode($state_info)));
		$paras = array('data' => json_encode($data), 'state_data' => $state_info, 'customer' => CUSTOM_APPID, 'debug_mode' => DEBUG_MODE, 'version' => VERSION_CODE);
		$this->curl('http://stat.cloud.hogesoft.com/customer_info_stat.php', $paras);
		@unlink(CACHE_DIR . 'datacount.lock');
		return true;
	}
	
	public function state_info()
	{
		$return = array();
		$runtime = $this->mRuntime['runstate'];
		$setruntime = $this->mSettime['runstate'] ? $this->mSettime['runstate'] : 43200;
		if ((time() - $runtime) > $setruntime)
		{
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
					$app_stats[$v['bundle']] = $v;
				}
			}
			if ($this->settings['App_livmedia'])
			{
				$curl = new curl($this->settings['App_livmedia']['host'], $this->settings['App_livmedia']['dir'] . 'admin/');
				$curl->setErrorReturn('');
				$curl->setCurlTimeOut(5);
				$curl->mAutoInput = false;
				$curl->initPostData();
				$curl->addRequestData('a', 'stats');
				$vod_status = $curl->request('vod.php');
				$vod_status = $vod_status[0];
			}
			$return['appstate'] = $app_stats;
			$return['vodstate'] = $vod_status;
			$this->mRuntime['runstate'] = time();
			
		}
		
		$runtime = $this->mRuntime['livedata'];
		$setruntime = $this->mSettime['livedata'] ? $this->mSettime['livedata'] : 7200;
		if ((time() - $runtime) > $setruntime && $this->settings['App_live'])
		{
			$curl = new curl($this->settings['App_live']['host'], $this->settings['App_live']['dir']);
			$curl->setErrorReturn('');
			$curl->setCurlTimeOut(5);
			$curl->mAutoInput = false;
			$curl->initPostData();
			$curl->addRequestData('a', 'show');
			$channel = $curl->request('channel.php');
			$channels = array();
			if ($channel)
			{
				foreach ($channel AS $c)
				{
					$channel_stream = array();
					if ($c['channel_stream'])
					{
						foreach ($c['channel_stream'] AS $s)
						{
							$channel_stream[] = $s['m3u8'];
						}
					}
					$channels[] = array(
						'name' => $c['name'],
						'code' => $c['code'],
						'is_audio' => $c['is_audio'],
						'time_shift' => $c['time_shift'],
						'status' => $c['status'],
						'channel_stream' => $channel_stream
					);
				}
				
				$return['channel'] = $channels;
			} 
			$this->mRuntime['livedata'] = time();
		}
		return $return;
	}
	
	private function check_update($version)
	{
		if (VERSION_CODE >= $version)
		{
			return false;
		}
		return true;
	} 
	
	private function update($url)
	{
		if (!$url)
		{
			return false;
		}
		$serverinfo = array(
			'host' => $this->settings['mcphost'],	
			'ip' => $this->settings['mcphost'],	
			'port' => 6233,	
		);		
		$socket = new hgSocket();
		$con = $socket->connect($serverinfo['host'], $serverinfo['port']);
		if (!intval($con))
		{
			echo $message = '服务器无法连接，请确认服务器ip是否正确或服务程序hogeMonitor.py是否监听在'. $serverinfo['ip'] . ':' . $serverinfo['port'] . '上';
			$socket->close();
			return false;
		}
		$socket->close();
		$app_path = realpath(ROOT_PATH);
		$app_path .= '/';
		hg_run_cmd( $serverinfo, 'download', $url, $app_path);
		return true;
	} 
	
	private function get_data()
	{
		$modules = array();
		$yesno = array(0 => '否', 1 => '是');
		$appid = intval($this->input['application_id']);
		$appid = $appid ? $appid : intval($this->input['id']);
		if ($appid)
		{
			$cond = ' AND m.application_id=' . $appid;
		}
		
		$fatherid = intval($this->input['fatherid']);
		if ($fatherid)
		{
			$cond .= ' AND m.fatherid=' . $fatherid;
		}
		$sql = 'SELECT m.*, a.host AS ahost, a.dir AS adir, a.name AS aname FROM ' . DB_PREFIX . 'modules m LEFT JOIN ' . DB_PREFIX . 'applications a ON m.application_id = a.id WHERE 1' . $cond . ' ORDER BY m.order_id ASC';
		$q = $this->db->query($sql);
		$this->cache->check_cache('modules');
		$all_modules = $this->cache->cache['modules'];
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = hg_get_date($row['create_time']);
			if (!$row['host'])
			{
				$row['host'] = $row['ahost'];
				if (!$row['dir'])
				{
					$row['dir'] = $row['adir'];
				}
			}
			if ($row['file_name'])
			{
				$row['apifile'] = 'http://' . $row['host'] . '/' . $row['dir'] . $row['file_name'] . $row['file_type'];
				$t = array(
					'apifile' => $row['apifile'],
					'app_uniqueid' => $row['app_uniqueid'],
					'mod_uniqueid' => $row['mod_uniqueid'],
					'menu_pos' => $row['menu_pos'],
				);
			$modules[$row['id']] = $t;
			}
		}

		if ($modules)
		{
			$data = array();
			foreach($modules AS $app)
			{
				$specifytime = $this->mSettime[$app['app_uniqueid'] . '_specify'][$app['mod_uniqueid']];
				$runtime = $this->mRuntime[$app['app_uniqueid']][$app['mod_uniqueid']];
				if ($specifytime)
				{
					if (!$runtime)
					{
						$runtime = time() - 86400;
					}
					
					$nextruntime = strtotime(date('Y-m-d ' . $specifytime, $runtime)) + 86400;
					if (time() < $nextruntime)
					{
						continue;
					}
				}
				else
				{
					$setruntime = $this->mSettime[$app['app_uniqueid']][$app['mod_uniqueid']];
					if (!$setruntime)
					{
						$setruntime = $this->mSettime['app'];
					}
					if ((time() - $runtime) < $setruntime)
					{
						continue;
					}
				}
				$this->mRuntime[$app['app_uniqueid']][$app['mod_uniqueid']] = time();
				if ($app['app_uniqueid'] != 'news')
				{
					//continue;
				}
				$condition = $this->mCondition[$app['app_uniqueid']][$app['mod_uniqueid']];
				if ($condition)
				{					
					$datapara = array(
							'a' => 'count', 
							'm2o_ckey' => CUSTOM_APPKEY, 
							'appid' => APPID, 
							'appkey' => APPKEY,
							'access_token' => $this->input['access_token']
						);
					foreach ($condition AS $k => $c)
					{
						$ttmp = array();
						foreach ($c AS $in => $val)
						{
							$ttmp[$in] = $val;
						}
						$ttmp = array_merge($datapara, $ttmp);
						$count = $this->curl($app['apifile'], $ttmp);
						$count = $count['total'];
						$data[$app['app_uniqueid']][$app['mod_uniqueid']][$k] = $count;
					}
				}
				if ($key = $this->mState[$app['app_uniqueid']][$app['mod_uniqueid']]['key'])
				{
					$states = $this->mState[$app['app_uniqueid']][$app['mod_uniqueid']]['state'];
					$datapara = array(
						'a' => 'count', 
						'm2o_ckey' => CUSTOM_APPKEY, 
						'appid' => APPID, 
						'appkey' => APPKEY,
						'access_token' => $this->input['access_token']
					);
					foreach ($states AS $state)
					{
						$datapara[$key] = $state;
						$count = $this->curl($app['apifile'], $datapara);
						$count = $count['total'];
						$data[$app['app_uniqueid']][$app['mod_uniqueid']][$state] = $count;
					}
				}
				else
				{
					$datapara = array(
						'a' => 'count', 
						'm2o_ckey' => CUSTOM_APPKEY, 
						'appid' => APPID, 
						'appkey' => APPKEY,
						'access_token' => $this->input['access_token']
					);
					$count = $this->curl($app['apifile'], $datapara);
					$count = $count['total'];
					if ($count)
					{
						$data[$app['app_uniqueid']][$app['mod_uniqueid']][0] = $count;
					}
				}
			}
		}
		return $data;
	}
	
	private function curl($url, $data = array())
	{		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
			
		curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		if($type == 'https')
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$ret = curl_exec($ch);
		$ret = json_decode($ret, 1);
		$head_info = curl_getinfo($ch);
		curl_close($ch);
		if ($head_info['http_code'] == 200)
		{
			return $ret;
		}
		else
		{
			return array();
		}
	}
	private function check_status($app)
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$ret = curl_exec($ch);
		$ret = json_decode($ret, 1);
		$head_info = curl_getinfo($ch);
		curl_close($ch);
		return array($head_info, $ret);
	}
}
?>