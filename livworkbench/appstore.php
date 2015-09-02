<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2454 2013-03-26 08:03:23Z develop_tong $
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'appstore');
require('./global.php');
require (ROOT_PATH . 'lib/class/curl.class.php');
class appstore extends uiBaseFrm
{	
	private $appstore;
	private $product_server;
	function __construct()
	{
		parent::__construct();		
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->ReportError('对不起，您没有权限进入应用商店');
		}
		if ($this->input['app'])
		{
			if (!$this->settings['App_auth'] && $this->input['app'] != 'auth')
			{
				header('Location:' . ROOT_DIR . 'appstore.php?app=auth');
			}
		}
		if(!$this->settings['App_auth'])
		{
			define('APPID', '');
			define('APPKEY', '');
		}
		$this->appstore = new curl('appstore.hogesoft.com', '');
		//$this->appstore = new curl('localhost', 'livsns/api/appstore/');
		$this->appstore->mAutoInput = false;
		$this->appstore->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$this->product_server = array(
			'host' => 'upgrade.hogesoft.com',
			'port' => 80,
			'dir' => '',
		);
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{		
		/*if ($this->input['app'])
		{
			$this->appstore->initPostData();
			$this->appstore->addRequestData('a', 'detail');
			$this->appstore->addRequestData('app', $this->input['app']);
			$appinfo = $this->appstore->request('index.php');
			$appinfo = $appinfo[0];
			if ($appinfo)
			{
				if ($appinfo['sourceapp'])
				{
					$appinfo = $appinfo['sourceapp'];
				}
				$this->tpl->addVar('appinfo', $appinfo);
				$this->input['app'] = $appinfo['app_uniqueid'];
			}
		}*/
		$this->input['app'] && $this->getAppInfo(true);
		$this->appstore->initPostData();
		$this->appstore->addRequestData('a', 'get_sort');
		$menu_group = $this->appstore->request('index.php');
		$menu_group = $menu_group['sort'];

		$this->appstore->initPostData();
		$menu_apps = $this->appstore->request('index.php');
		$menu_apps = $menu_apps['apps'];
		$this->tpl->addVar('_settings', $this->settings);
		$this->tpl->addVar('menu_group', $menu_group);
		$this->tpl->addVar('menu_apps', $menu_apps);
		$this->tpl->outTemplate('appstore');
	}

	public function onekupdate($message = '')
	{
		$curl = new curl($this->product_server['host'] . ':' . $this->product_server['port'], $this->product_server['dir']);
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->initPostData();
		$postdata = array(
			'app'		=>	'livworkbench',
			'a'				=>	'checklastversion',
		);
		foreach ($postdata as $k=>$v)
		{
			$curl->addRequestData($k, $v);
		}
		$version = $curl->request('check_version.php');
		$update_apps = array();
		if ($this->settings['version'] < $version)
		{
			$update_apps[] = array(
				'app_uniqueid' => 'livworkbench',
				'name' => $this->settings['name'],
				'groupname' => '平台',
				'version' => $this->settings['version'],
			);
		}
		$this->appstore->initPostData();
		$menu_apps = $this->appstore->request('index.php');
		$this->appstore->initPostData();
		$this->appstore->addRequestData('a', 'get_sort');
		$menu_group = $this->appstore->request('index.php');
		$menu_group = $menu_group['sort'];
		$menu_apps = $menu_apps['apps'];
		foreach ($menu_apps as $group => $apps)
		{
			foreach ($apps as $app)
			{
				if ($app['status'] == -1)
				{
					$app['groupname'] = $menu_group[$app['class_id']]['name'];
					$update_apps[] = $app;
				}
			}
		}
		if (!$update_apps)
		{
			$this->ReportError('所有应用都是最新版本');
		}
		$this->tpl->addVar('message', $message);
		$this->tpl->addVar('update_apps', $update_apps);
		$this->tpl->outTemplate('appstore_onekupdate');
	}
	public function doonekupdate()
	{
		if (!$this->input['dbuser'])
		{
			$this->onekupdate('请设置数据库用户');
		}
		$curl = new curl($this->product_server['host'] . ':' . $this->product_server['port'], $this->product_server['dir']);
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->initPostData();
		$postdata = array(
			'app'		=>	'livworkbench',
			'a'				=>	'checklastversion',
		);
		foreach ($postdata as $k=>$v)
		{
			$curl->addRequestData($k, $v);
		}
		$version = $curl->request('check_version.php');
		$update_apps = array();
		if ($this->settings['version'] < $version)
		{
			$update_apps['livworkbench'] = array(
				'app_uniqueid' => 'livworkbench',
				'name' => $this->settings['name'],
				'groupname' => '平台',
				'version' => $this->settings['version'],
			);
		}
		$this->appstore->initPostData();
		$menu_apps = $this->appstore->request('index.php');
		$menu_apps = $menu_apps['apps'];
		foreach ($menu_apps as $group => $apps)
		{
			foreach ($apps as $app)
			{
				if ($app['status'] == -1)
				{
					$update_apps[$app['app_uniqueid']] = $app;
				}
			}
		}
		if (!$update_apps)
		{
			$this->ReportError('所有应用都是最新版本');
		}
		$update_apps['okupdatedbinfo'] = array(
			'dbuser' => trim($_POST['dbuser']),
			'dbpass' => trim($_POST['dbpass']),
		);
		file_put_contents(CACHE_DIR . 'onekupdate', json_encode($update_apps));
		header('Location:appstore.php?a=goonekupdate');
	}

	public function goonekupdate()
	{
		$update_apps = @file_get_contents(CACHE_DIR . 'onekupdate');
		$update_apps = json_decode($update_apps, 1);
		$okupdatedbinfo = $update_apps['okupdatedbinfo'];
		unset($update_apps['okupdatedbinfo']);
		if (!$update_apps)
		{
			$this->ReportError('所有应用已更新，没有待更新的应用');
		}
		$app = array_shift($update_apps);
		if ($app['app_uniqueid'] == 'livworkbench')
		{
			header('Location:upgrade.php?onekupdate=1&a=doupgrade&dbuser=' . urlencode($okupdatedbinfo['dbuser']) . '&dbpass=' . urlencode($okupdatedbinfo['dbpass']));
		}
		else
		{
			header('Location:appstore.php?onekupdate=1&a=doupgrade&app=' . $app['app_uniqueid'] . '&dbuser=' . urlencode($okupdatedbinfo['dbuser']) . '&dbpass=' . urlencode($okupdatedbinfo['dbpass']));
		}


	}

	public function sync_js($message = '')
	{
		chdir('res/scripts/');
		$zip = 'zip -r ' . ROOT_PATH . 'cache/js.zip common.js global.js common/';
		exec($zip);
		if (!is_file(ROOT_PATH . 'cache/js.zip'))
		{
			echo 'zip 文件到cache目录失败';
			exit();
		}
		$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$ret = $curl->request('applications.php');
		$app = array(	
			'ip' => $this->settings['mcphost'],	
			'port' => 6233,		
		);
		
		$socket = new hgSocket();
		$con = $socket->connect($app['ip'], $app['port']);
		if (!intval($con))
		{
			echo '服务器无法连接，请确认服务器ip是否正确或服务程序hogeMonitor.py是否监听在'. $app['ip'] . ':' . $app['port'] . '上';
			$socket->close();
			exit;
		}
		$socket->close();
		$host = $_SERVER['HTTP_HOST'];
		$dir = $_SERVER['SCRIPT_NAME'];
		$dir = explode('/', $dir);
		unset($dir[count($dir) - 1]);
		$url = 'http://' . $host;
		if ($dir)
		{
			$url .= implode('/', $dir);
		}
		$url .= '/cache/js.zip';
		if (is_array($ret))
		{
			foreach ($ret AS $v)
			{
				$app_path = ROOT_PATH . 'res/scripts/app_' . $v['bundle'] . '/';
				hg_run_cmd( $app, 'download', $url, $app_path);
				echo $app_path . '已更新<br />';
			}
		}
		@unlink(ROOT_PATH . 'cache/js.zip');
	}
	public function modify_serv_hosts($message = '')
	{
		$this->tpl->addVar('message', $message);
		$this->tpl->outTemplate('modify_serv_hosts');
	}

	public function domodify_serv_hosts()
	{
		$ip = trim($this->input['ip']);
		$domain = trim($this->input['domain']);
		if (!hg_checkip($ip))
		{
			$this->modify_serv_hosts('ip格式不正确');
		}
		if (!$domain || strstr($domain, '/'))
		{
			$this->modify_serv_hosts('域名格式不正确');
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . "server_domain sd LEFT JOIN " . DB_PREFIX . 'server s ON sd.server_id=s.id';
		$q = $this->db->query($sql);
		$hostscontent = '';
		$ipdomains = array();
		$alldomains = array();
		while ($r = $this->db->fetch_array($q))
		{
			$hostscontent .= "\n" . $r['ip'] . '	' . $r['domain'];
			$ipdomains[$r['ip']][] = $r['domain'];
			$alldomains[] = $r['domain'];
		}
		if ($ipdomains)
		{
			foreach($ipdomains AS $sip => $domains)
			{
				$app = array(
					'hosts' => $sip,	
					'ip' => $sip,	
					'port' => 6233,	
				);
				$socket = new hgSocket();
				$con = $socket->connect($app['ip'], $app['port']);
				if (!intval($con))
				{
					echo $app['ip'] . '服务器无法连接，请确认服务器ip是否正确或服务程序hogeMonitor.py是否监听在'. $app['ip'] . ':' . $app['port'] . '上<br />';
					$socket->close();
					continue;
				}
				$socket->close();

				$servhosts = get_serv_file( $app, '/etc/hosts');
				$existshosts = hg_get_hosts($servhosts);
				unset($existshosts[$domain]);

				$servhosts = '';
				if ($existshosts)
				{
					foreach ($existshosts AS $dm => $dip)
					{
						$servhosts .= "\n" . $dip . '	' . $dm;
					}
				}
				$servhosts .= "\n" . $ip . '	' . $domain;
				write_serv_file( $app, '/etc/hosts', $servhosts);
				echo $sip . 'hosts已更改<br />';
			}
		}
		
		$app = array(
			'hosts' => $ip,	
			'ip' => $ip,	
			'port' => 6233,	
		);
		$socket = new hgSocket();
		$con = $socket->connect($app['ip'], $app['port']);
		if (!intval($con))
		{
			echo $app['ip'] . '服务器无法连接，请确认服务器ip是否正确或服务程序hogeMonitor.py是否监听在'. $app['ip'] . ':' . $app['port'] . '上<br />';
			$socket->close();
			exit;
		}
		$socket->close();

		$servhosts = get_serv_file( $app, '/etc/hosts');
		$existshosts = hg_get_hosts($servhosts);
		unset($existshosts[$domain]);
		if ($alldomains)
		{
			foreach ($alldomains AS $dm)
			{
				unset($existshosts[$dm]);
			}
		}
		$servhosts = '';
		if ($existshosts)
		{
			foreach ($existshosts AS $dm => $dip)
			{
				$servhosts .= "\n" . $dip . '	' . $dm;
			}
		}
		$servhosts .= "\n" . $ip . '	' . $domain;
		write_serv_file( $app, '/etc/hosts', $hostscontent . $servhosts);
		echo $ip . 'hosts已更改<br />';
	}

	public function rebuild_hosts()
	{		
		$sql = 'SELECT * FROM ' . DB_PREFIX . "server_domain sd LEFT JOIN " . DB_PREFIX . 'server s ON sd.server_id=s.id';
		$q = $this->db->query($sql);
		$hostscontent = '';
		$ipdomains = array();
		$alldomains = array();
		while ($r = $this->db->fetch_array($q))
		{
			$domain = $r['domain'];
			$ipdomains[$r['ip']][] = $domain;
			$alldomains[] = $domain;
			$hostscontent .= "\n" . $r['ip'] . '	' . $domain;
		}
		if ($ipdomains)
		{
			foreach($ipdomains AS $ip => $domains)
			{
				$app = array(
					'hosts' => $ip,	
					'ip' => $ip,	
					'port' => 6233,	
				);
				$socket = new hgSocket();
				$con = $socket->connect($app['ip'], $app['port']);
				if (!intval($con))
				{
					echo $app['ip'] . '服务器无法连接，请确认服务器ip是否正确或服务程序hogeMonitor.py是否监听在'. $app['ip'] . ':' . $app['port'] . '上<br />';
					$socket->close();
					continue;
				}
				$socket->close();
				
				$servhosts = get_serv_file( $app, '/etc/hosts');
				$existshosts = hg_get_hosts($servhosts);
				if ($alldomains)
				{
					foreach ($alldomains AS $domain)
					{
						unset($existshosts[$domain]);
					}
				}
				$servhosts = '';
				if ($existshosts)
				{
					foreach ($existshosts AS $domain => $dip)
					{
						$servhosts .= "\n" . $dip . '	' . $domain;
					}
				}
				write_serv_file( $app, '/etc/hosts', $servhosts . $hostscontent);
				echo $ip . 'hosts已更改<br />';
			}
		}
	}
	public function rebuild_templates($appid = 0)
	{
		if ($appid)
		{
			$cond = ' WHERE application_id=' . $appid;
		}
		$sql = 'SELECT a.*, m.* FROM ' . DB_PREFIX . 'modules m LEFT JOIN ' . DB_PREFIX . 'applications a ON m.application_id=a.id ' . $cond;
		$q = $this->db->query($sql);
		$templates = array();
		$program = array();
		$modid = array();
		$modules = array();
		while ($mod = $this->db->fetch_array($q))
		{
			$modid[] = $mod['id'];
			if ($mod['template'])
			{
				$templates[$mod['id']][] = $mod['template'];
			}
			$program[$mod['id']][$mod['func_name']] = $mod['func_name'];
			$modules[$mod['id']] = $mod;
		}
		$cond = '';
		if ($appid && $modid)
		{
			$cond = ' WHERE module_id IN (' . implode(',', $modid) . ')';
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'module_op' . $cond;
		$q = $this->db->query($sql);
		while ($mod = $this->db->fetch_array($q))
		{
			$program[$mod['module_id']][$mod['op']] = $mod['op'];
			if ($mod['template'])
			{
				$templates[$mod['module_id']][] = $mod['template'];
			}
		}
		if ($templates)
		{
			hg_flushMsg('开始更新模板');
			$configtpls = array('settings', 'iframe_list');
			foreach ($templates AS $m => $tpl)
			{
				if ($modules[$m])
				{
					hg_flushMsg('开始更新' . $modules[$m]['name'] . '模板');
					$this->tpl->setSoftVar($modules[$m]['softvar']); //设置软件界面
					if (DEVELOP_MODE)
					{
						$this->tpl->setTemplateVersion(''); 
						$this->tpl->setScriptDir(''); 
					}
					else
					{
						$this->tpl->setScriptDir('app_' . $modules[$m]['softvar'] . '/'); 
						$this->tpl->setTemplateVersion($modules[$m]['softvar'] . '/' . $modules[$m]['version']); 
					}
					if (is_array($tpl))
					{
						foreach ($tpl AS $t)
						{
							hg_flushMsg('开始更新模板' . $t);
							$this->tpl->recacheTemplate($t);
						}
					}
					
					hg_flushMsg($modules[$m]['name'] . '模板更新完成');
				}
			}
			foreach ($configtpls AS $t)
			{
				hg_flushMsg('开始更新模板' . $t);
				$this->tpl->recacheTemplate($t);
			}
			hg_flushMsg('模板更新完成');
		}

		if ($appid && $program)
		{
			hg_flushMsg('开始重建模块程序');
			include(ROOT_PATH . 'lib/class/program.class.php');
			$rebuildprogram = new program();
			foreach ($program AS $mid => $ops)
			{
				if ($ops)
				{
					foreach ($ops AS $op)
					{
						$rebuildprogram->compile($mid, $op);
					}
				}
			}
			hg_flushMsg('模块程序重建完成');
		}
	}

	public function rebuild_global_conf()
	{	
		$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$ret = $curl->request('applications.php');
		$installed_app_conf = '';
		$instlled_apps = array();
		$need_mod_global = array();
		if (is_array($ret))
		{
			foreach ($ret AS $v)
			{
				$instlled_apps[$v['bundle']] = $v;
				$need_mod_global[$v['host']] = $v;
				$installed_app_conf .= "\n\$gGlobalConfig['App_{$v['bundle']}'] = array('name' => '{$v['name']}','protocol' => 'http://', 'port' => '{$v['port']}', 'host' => '{$v['host']}', 'dir' => '{$v['dir']}', 'token' => 'tmp');";
			}
		}
		if ($need_mod_global) //重写所有服务器的global.conf.php中的应用配置
		{
			foreach($need_mod_global AS $host => $v)
			{
				$gcurl = new curl($v['host'], $v['dir']);
				$gcurl->initPostData();
				$gcurl->addRequestData('a', 'getapp_path');
				$app_path = $gcurl->request('configuare.php');
				$app_path .= '/';
				$app_path = str_replace('api/' . $v['bundle'] . '/', '/', $app_path);
				$servinfo = array(
					'ip' => $v['host'],
					'port' => 6233,
				);
				$gconfile = $app_path . 'conf/global.conf.php';
				$ccontent = get_serv_file( $servinfo, $gconfile);
				$ccontent = preg_replace("/\\\$gGlobalConfig\s*\['App_.*'\]\s*=\s*array\s*(.*?);/is", '', $ccontent);	
				$match = preg_match("/define\('CUSTOM_APPKEY',\s*.*?\s*\);/is", $ccontent);
				if($match)
				{
					$ccontent = preg_replace("/define\('CUSTOM_APPKEY',\s*.*?\s*\);/is","define('CUSTOM_APPKEY', '" . CUSTOM_APPKEY . "');", $ccontent);
					$ccontent = preg_replace("/define\('CUSTOM_APPID',\s*.*?\s*\);/is","define('CUSTOM_APPID', '" . CUSTOM_APPID . "');", $ccontent);
				}
				else
				{
					$ccontent = preg_replace("/\?>/is", "\ndefine('CUSTOM_APPID', '" . CUSTOM_APPID . "');\ndefine('CUSTOM_APPKEY', '" . CUSTOM_APPKEY . "');\n?>", $ccontent);
				}
				$ccontent = preg_replace("/\?>/is", $installed_app_conf  . "\n?>", $ccontent);
				write_serv_file( $servinfo, $gconfile, $ccontent, 'utf8');
				echo $host .  $gconfile . '已重建<br />';
			}
		}
	}
	public function updateconfig()
	{
		$app = $this->input['app'];
		if (!$this->settings['App_' . $app])
		{
			exit('NO_APP');
		}
		$curl =  new curl($this->settings['App_' . $app]['host'], $this->settings['App_' . $app]['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'settings');
		$ret = $curl->request('configuare.php');
		$user_configs = array(
			'base' => $ret['base'],
			'define' => $ret['define'],
		);
		$curl = new curl($this->product_server['host'] . ':' . $this->product_server['port'], '');
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->setSubmitType('get');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('app', $install_app);
		$new_configs = $curl->request('config.php');

		if ($new_configs)
		{
			$doset = array();
			foreach ($new_configs AS $k => $v)
			{
				if (is_array($v))
				{
					foreach ($v AS $kk => $vv)
					{
						if (!$user_configs[$k][$kk])
						{
							$doset[$k][$kk] = $vv;
						}
					}
				}
			}
		}
		if ($doset)
		{
			$curl =  new curl($this->settings['App_' . $app]['host'], $this->settings['App_' . $app]['dir']);
			$curl->setSubmitType('post');
			$curl->setReturnFormat('json');
			$curl->initPostData();
			$curl->addRequestData('a', 'doset');
			

			foreach ($doset AS $k => $v)
			{
					foreach($v AS $kk => $vv)
					{
						if (is_array($vv))
						{
							foreach($vv AS $kkk => $vvv)
							{
								if (is_array($vvv))
								{
									foreach($vvv AS $kkkk => $vvvv)
									{
										if (is_array($vvvv))
										{
											foreach($vvvv AS $kkkkk => $vvvvv)
											{
												$curl->addRequestData($k . "[$kk][$kkk][$kkkk][$kkkkk]", $vvvvv);
											}
										}
										else
										{
											$curl->addRequestData($k . "[$kk][$kkk][$kkkk]", $vvvv);
										}
									}
								}
								else
								{
									$curl->addRequestData($k . "[$kk][$kkk]", $vvv);
								}
							}
						}
						else
						{
							$curl->addRequestData($k . "[$kk]", $vv);
						}
					}
			}
			
			$ret = $curl->request('configuare.php');
		}
		print_r($ret);
	}
	public function getAppInfo($return = false){
        $this->appstore->initPostData();
        $this->appstore->addRequestData('a', 'detail');
        $this->appstore->addRequestData('app', $this->input['app']);
        $appinfo = $this->appstore->request('index.php');
        $appinfo = $appinfo[0];
        $appinfo && $appinfo['sourceapp'] && $appinfo = $appinfo['sourceapp'];
        if($return){
            $this->tpl->addVar('appinfo', $appinfo);
            $this->input['app'] = $appinfo['app_uniqueid'];
            $this->tpl->addVar('app', $appinfo['app_uniqueid']);
            return $appinfo;
        }
        exit(json_encode($appinfo));
	}
	
	private function check_version($appinfo)
	{		
		if($appinfo['pre_condition'])
		{
			$sql = 'SELECT version,softvar, name FROM ' . DB_PREFIX . "applications WHERE softvar IN ('" . implode("','", array_keys($appinfo['pre_condition'])) . "')";
			$q = $this->db->query($sql);
			$installversion = array();
			while($r = $this->db->fetch_array($q))
			{
				$installversion[$r['softvar']] = $r;
			}
			foreach ($appinfo['pre_condition'] AS $k => $v)
			{
				if ($k == 'livworkbench' && $this->settings['version'] < $v)
				{						
					$this->ReportError('指定应用无法安装, 需要先将<a href="upgrade.php" target="_top">平台</a>更新至' . $v . '版本');
				}
				if ($installversion[$k]['version'] < $v)
				{
					$this->ReportError('指定应用无法安装, 需要先将<a href="?a=upgrade&app=' . $k . '">' . $installversion[$k]['name'] . '</a>更新至' . $v . '版本');
				}
			}
		}
	}
	
	public function install($message = '')
	{
		if (DEVELOP_MODE)
		{
			$this->ReportError('对不起，开发模式不允许安装');
		}
		if ($this->input['app'])
		{
			$this->appstore->initPostData();
			$this->appstore->addRequestData('a', 'detail');
			$this->appstore->addRequestData('app', $this->input['app']);
			$appinfo = $this->appstore->request('index.php');
			$appinfo = $appinfo[0];
			if ($appinfo['status'] != 0)
			{
				$this->install('应用' . $appinfo['name'] . '已经安装');
			}
			$this->input['app'] = $appinfo['app_uniqueid'];
		}
		
		if (!$appinfo)
		{
			$this->ReportError('指定应用不存在，无法安装');
		}
		$this->check_version($appinfo);
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'server ORDER BY id DESC';
		$q = $this->db->query($sql);
		$servers = array();
		while($r = $this->db->fetch_array($q))
		{
			if (in_array($r['type'], array('api')))
			{
				$type = 'app';
			}
			else
			{
				$type = $r['type'];
			}
			$servers[$type][$r['id']] = $r['name'] . '_' . $r['ip'];
		}
		if (!$formdata)
		{
			$formdata = $this->input;
			if (!$this->input['dbserver'])
			{
				$dbs = @array_keys($servers['db']);
				$formdata['dbserver'] = $dbs[0];
			}
			if (!$this->input['appserver'])
			{
				$apps = @array_keys($servers['app']);
				$formdata['appserver'] = $apps[0];
			}
			if (!$this->input['database'])
			{
				$formdata['database'] = ($this->settings['dbprefix'] ? $this->settings['dbprefix'] : 'm2o_' ) . $this->input['app'];
			}
			if (!$this->input['dbprefix'])
			{
				$formdata['dbprefix'] = 'm2o_';
			}
		}
		$formdata['example_domain'] = $this->input['app'] . '_api.' . $this->settings['license'];
		$formdata['apidomain'] = $this->input['app'] . '_api.' . $this->settings['license'];
		$formdata['dir'] = ($this->settings['webdir'] ? $this->settings['webdir'] : '/web/' ) . $this->settings['license'] . '/' . $this->input['app'] . '_api.' . $this->settings['license'] . '/';
		$servers['db'][0] = '新增数据库服务器';
		$servers['app'][0] = '新增应用服务器';
		$this->tpl->addHeaderCode('  <script type="text/javascript">
//<![CDATA[
' . $script . '
//]]>
  </script>
');
		$curl = new curl($this->product_server['host'] . ':' . $this->product_server['port'], '');
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		if (!$appinfo['nodb'])
		{
			$curl->initPostData();
			$curl->addRequestData('install', 1);
			$curl->addRequestData('app', $this->input['app']);
			$ret = $curl->request('db.php');
		}
		
		$this->tpl->addVar('hasdb', $ret['app']);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->addVar('servers', $servers);
		$this->tpl->addVar('appinfo', $appinfo);
		$this->tpl->addVar('message', $message);
		$this->tpl->outTemplate('appstore_install');
	}

	public function doinstall()
	{
		include(ROOT_PATH . 'appstore_install.php');
		//$this->redirect('应用安装成功', $url);		
	}

	public function upgrade($message = '')
	{
		if (DEVELOP_MODE)
		{
			$this->ReportError('对不起，开发模式不允许更新');
		}
		if ($this->input['app'])
		{
			$this->appstore->initPostData();
			$this->appstore->addRequestData('a', 'detail');
			$this->appstore->addRequestData('app', $this->input['app']);
			$appinfo = $this->appstore->request('index.php');
			$appinfo = $appinfo[0];
		}
		if (!$appinfo)
		{
			$this->ReportError('指定应用不存在或被删除，无法更新');
		}
		$this->input['app'] = $appinfo['app_uniqueid'];
		if ($appinfo['status'] == 0)
		{
			$this->ReportError('应用' . $appinfo['name'] . '尚未安装');
		}
		$this->check_version($appinfo);
		//取出已经安装应用配置

		$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$ret = $curl->request('applications.php');
		$installinfo = array();
		if (is_array($ret))
		{
			foreach ($ret AS $v)
			{
				if ($v['bundle'] == $this->input['app'])
				{
					$installinfo = $v;
					break;
				}
			}
		}
		if (!$installinfo)
		{
			$installinfo = $this->settings['App_' . $this->input['app']];
		}
		if (!$installinfo)
		{
			$this->ReportError('应用安装信息丢失，请联系软件提供商');
		}
		$installinfo['dir'] = str_replace($installinfo['admin_dir'], '', $installinfo['dir']);
		$curl =  new curl($installinfo['host'], $installinfo['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'settings');
		$ret = $curl->request('configuare.php');
		$curl->initPostData();
		$curl->addRequestData('a', 'getapp_path');
		$app_path = $curl->request('configuare.php');
		$installinfo['dir'] = $app_path;
		$this->tpl->addVar('message', $message);
		$this->tpl->addVar('appinfo', $appinfo);
		$this->tpl->addVar('installinfo', $installinfo);
		$this->tpl->addVar('dbinfo', $ret['db']);
		$this->tpl->outTemplate('appstore_upgrade');
	}

	public function doupgrade()
	{
		include(ROOT_PATH . 'appstore_upgrade.php');
		//$this->redirect('应用更新成功', $url);
	}
	public function add_dbserver()
	{
		$db = $_REQUEST['db'];
		$link = @mysql_connect($db['host'], $db['user'], $db['pass']);
		if (!$link)
		{
			$message = '此数据库服务器无法连接，请确认信息是否准确';
			$this->redirect($message, 0, 0, '', "hg_add_dbservererr_back('{$message}')");
		}
		
		$db['pass'] = hg_encript_str($db['pass']);
		$db_data = json_encode($db);
		$sql = 'INSERT INTO ' . DB_PREFIX . "server (name, brief, ip, outip, type, more_data,create_time) VALUES 
				('{$db['name']}', '', '{$db['host']}', '', 'db', '$db_data', " . TIMENOW. ")";
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$this->redirect('数据库服务器添加成功', 0, 0, '', "hg_add_dbserver_back($id, '{$db['name']}_{$db['host']}')");
	}
	public function add_appserver()
	{
		$app = $this->input['appserver'];
		if (!$app['ip'])
		{
			$message = '请填写服务器ip';
			$this->redirect($message, 0, 0, '', "hg_add_appservererr_back('{$message}')");
		}
		$app['ip'] = trim($app['ip']);
		$sql = 'SELECT * FROM ' . DB_PREFIX . "server WHERE ip = '{$app['ip']}' AND type != 'db'";
		$q = $this->db->query_first($sql);
		if ($q)
		{
			$message = '此服务器ip已存在，无需重复添加';
			$this->redirect($message, 0, 0, '', "hg_add_appservererr_back('{$message}')");
		}
		$app['port'] = 6233;
		$socket = new hgSocket();
		$con = $socket->connect($app['ip'], $app['port']);
		if (!intval($con))
		{
			$message = '服务器无法连接，请确认服务器ip是否正确或服务程序hogeMonitor.py是否监听在'. $app['ip'] . ':' . $app['port'] . '上';
			$socket->close();
			$this->redirect($message, 0, 0, '', "hg_add_appservererr_back('{$message}')");
		}
		$socket->close();
		$app_data = json_encode($app);
		$sql = 'INSERT INTO ' . DB_PREFIX . "server (name, brief, ip, outip, type, more_data,create_time) VALUES 
				('{$app['name']}', '', '{$app['ip']}', '{$app['outip']}', 'api', '$app_data', " . TIMENOW. ")";
		$this->db->query($sql);
		$id = $this->db->insert_id();

		$sql = 'SELECT * FROM ' . DB_PREFIX . "server_domain sd LEFT JOIN " . DB_PREFIX . 'server s ON sd.server_id=s.id';
		$q = $this->db->query($sql);
		$hostscontent = get_serv_file( $app, '/etc/hosts');
		$hosts = hg_get_hosts($hostscontent);
		
		while ($r = $this->db->fetch_array($q))
		{
			$domain = $r['domain'];
			if($domain && !$hosts[$domain])
			{
				$hostscontent .= "\n" . $r['ip'] . '	' . $domain;
			}
		}

		write_serv_file( $app, '/etc/hosts', $hostscontent);
		$this->redirect('应用服务器添加成功', 0, 0, '', "hg_add_appserver_back($id, '{$app['name']}_{$app['ip']}')");
	}

	function check_domains()
	{
		$appserver = intval($this->input['appserver']);
		$domain = trim($this->input['domain']);
		if (!$appserver)
		{
			$ret['errorcode'] = 1;
			$ret['errorinfo'] = '未选择服务器';
			echo json_encode($ret);
			exit;
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'server WHERE id=' . $appserver;
		$appserver = $this->db->query_first($sql);
		if (!$appserver)
		{
			$ret['errorcode'] = 1;
			$ret['errorinfo'] = '所选服务器不存在';
			echo json_encode($ret);
			exit;
		}
		if ($domain)
		{
			$cond = ' AND domain LIKE \'%' . $domain . '%\'';
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'server_domain WHERE server_id = ' . $appserver['id'] . $cond . ' ORDER BY id DESC';
		$q = $this->db->query($sql);
		$domains = array();
		while ($r = $this->db->fetch_array($q))
		{
			$t =  array('domain' => $r['domain'], 'dir' => $r['dir']);
			if ($r['domain'] == $domain)
			{
				$t['status'] = 1;
			}
			else
			{
				$t['status'] = 0;
			}
			$domains[] = $t;
		}
		if ($appserver['type'] == 'app')
		{
			$len = count($domains) - 1;
			unset($domains[$len]);
		}
		echo json_encode($domains);
		exit;
	}

	private function clearfile($dir)
	{
		if(file_exists($dir))
		{
			$open = opendir($dir);
			while(($file = readdir($open)) !== false)
			{
				if($file != '.' && $file != '..')
				{			
					$pdir = $dir . $file;
					if(is_dir($pdir))//判断是否 目录
					{
						$this->clearfile($pdir . '/');
					}
					else
					{
						unlink($pdir);
					}
				}
			}
		}
	}

	private function getDbStruct($dbname, $link)
	{
		if (!$dbname)
		{
			return array();
		}
		$tables = $this->getTables($dbname, $link);
		$struct = array();
		$indexs = array();
		foreach ($tables AS $table)
		{
			$sql = "SHOW FULL COLUMNS FROM {$table}";
			$queryid = mysql_query($sql, $link);
			while($row = mysql_fetch_array($queryid))
			{
				$struct[$table]['struct'][$row['Field']] = $row;
				if ($row['Key'])
				{
					$indexs[] = $row['Field'];
				}
			}
			$struct[$table]['index'] = $this->getIndexs($dbname, $table, $link);
		}

		return $struct;
	}
	private function getIndexs($dbname, $table = 'test', $link = '')
	{
		$sql = "SELECT DISTINCT * 
			FROM information_schema.statistics
			WHERE table_schema =  '{$dbname}'
			AND table_name =  '{$table}'";		
		$queryid = mysql_query($sql, $link);
		$indexs = array();
		while($row = mysql_fetch_array($queryid))
		{
			$indexs[$row['NON_UNIQUE']][$row['INDEX_NAME']][$row['SEQ_IN_INDEX'] - 1] = $row['COLUMN_NAME'];
		}
		return $indexs;
	}

	private function getTables($dbname, $link)
	{
		if (!$dbname)
		{
			return array();
		}
		$sql = "SHOW TABLES FROM " . $dbname;
		$queryid = mysql_query($sql, $link);
		$tables = array();
		while($row = mysql_fetch_array($queryid))
		{
			$row['TABLE_NAME'] = $row['Tables_in_' . $dbname];
			$tables[] = $row['TABLE_NAME'];
		}
		return $tables;
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>