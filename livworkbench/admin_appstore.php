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
	private $appinfo = array();
	function __construct()
	{
		parent::__construct();		
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->ReportError('对不起，您没有权限进入程序发布');
		}
		$this->appstore = new curl('appstore.hogesoft.com:1912', 'admin/');
		$this->appstore->mAutoInput = false;
		$this->product_server = array(
			'host' => 'upgrade.hogesoft.com',
			'port' => 233,
			'dir' => '',
		);
		if ($this->input['app'])
		{
			$this->appstore->initPostData();
			$this->appstore->addRequestData('a', 'detail');
			$this->appstore->addRequestData('app', $this->input['app']);
			$appinfo = $this->appstore->request('index.php');
			$appinfo = $appinfo[0];
			$this->appinfo = $appinfo;
			if ($appinfo)
			{
				$this->tpl->addVar('appinfo', $appinfo);
			}
		}
		if (!$this->input['a'])
		{
			$this->input['a'] = 'show';
		}
		if (!in_array($this->input['a'], array('show', 'pre_release_all', 'release_all', 'clear_app_version', 'release_all_tpl', 'pre_release_all_tpl')) && !$appinfo)
		{
			$this->ReportError('指定应用不存在或被删除，无法发布');
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		
		$this->appstore->initPostData();
		$this->appstore->addRequestData('a', 'get_sort');
		$menu_group = $this->appstore->request('index.php');
		$menu_group = $menu_group['sort'];

		$this->appstore->initPostData();
		$menu_apps = $this->appstore->request('index.php');
		$menu_apps = $menu_apps['apps'];

		$curl = new curl($this->product_server['host'] . ':' . $this->product_server['port'], '');
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->setSubmitType('get');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('install', 1);
		$curl->addRequestData('pre_release', 1);
		$curl->addRequestData('app', $app);
		$appinfo = $this->appinfo;
		$program_url = $curl->request('check_version.php');
		$appinfo['pre_release_url'] = $program_url;
		$curl->initPostData();
		$curl->addRequestData('install', 1);
		$curl->addRequestData('app', $app);
		$program_url = $curl->request('check_version.php');
		$appinfo['release_url'] = $program_url;


		$this->tpl->addVar('appinfo', $appinfo);
		$this->tpl->addVar('_settings', $this->settings);
		$this->tpl->addVar('menu_group', $menu_group);
		$this->tpl->addVar('menu_apps', $menu_apps);
		$this->tpl->outTemplate('admin_appstore');
	}

	public function pre_release($message = '')
	{
		$this->tpl->addVar('version', 'pre_version');
		$this->tpl->addVar('action', '预发布');
		$this->tpl->addVar('doaction', 'dopre_release');
		$this->tpl->addVar('message', $message);
		$this->tpl->outTemplate('admin_appstore_release');
	}

	public function release()
	{
		$appinfo = $this->appinfo;
		$appinfo['version'] = $this->appinfo['pre_version'];
		$this->tpl->addVar('version', 'version');
		$this->tpl->addVar('action', '发布');
		$this->tpl->addVar('appinfo', $appinfo);
		$this->tpl->addVar('doaction', 'dorelease');
		$this->tpl->outTemplate('admin_appstore_release');
	}
	
	
	public function pre_release_all_tpl()
	{
		$this->appstore->initPostData();
		$menu_apps = $this->appstore->request('index.php');
		$menu_apps = $menu_apps['apps'];
		if ($menu_apps)
		{
			foreach ($menu_apps AS $k => $v)
			{
				if ($v)
				{
					foreach ($v AS $kk => $vv)
					{
						$app_uniqueid = $vv['app_uniqueid'];
						$version = $vv['version'];
						file_get_contents('http://web.innner.hogesoft.com/control/release.php?pre_release=1&app=livtemplates&forapp=' . $app_uniqueid . '&version=' . $version);
						echo $vv['name'] . '模板预发布成功。<br />';
					}
				}
			}
		}
	}
	public function release_all_tpl()
	{
		$this->appstore->initPostData();
		$menu_apps = $this->appstore->request('index.php');
		$menu_apps = $menu_apps['apps'];
		if ($menu_apps)
		{
			foreach ($menu_apps AS $k => $v)
			{
				if ($v)
				{
					foreach ($v AS $kk => $vv)
					{
						$app_uniqueid = $vv['app_uniqueid'];
						$version = $vv['version'];
						file_get_contents('http://web.innner.hogesoft.com/control/release.php?app=livtemplates&forapp=' . $app_uniqueid . '&version=' . $version);
						echo $vv['name'] . '模板发布成功。<br />';
					}
				}
			}
		}
	}
	
	public function clear_app_version()
	{
		$c = file_get_contents('http://web.innner.hogesoft.com/control/clear.php');
		if (!$c)
		{
			$c = 'No version no used was clear.';
		}
		echo $c;
	}

	public function release_all()
	{
		$this->appstore->initPostData();
		$menu_apps = $this->appstore->request('index.php');
		$menu_apps = $menu_apps['apps'];
		if ($menu_apps)
		{
			foreach ($menu_apps AS $k => $v)
			{
				if ($v)
				{
					foreach ($v AS $kk => $vv)
					{
						$app_uniqueid = $vv['app_uniqueid'];
						if ($this->input['new_version'])
						{
							$version = $vv['pre_version'];
							$this->appstore->initPostData();
							$this->appstore->addRequestData('a', 'update_version');
							$this->appstore->addRequestData('version', $version);
							$this->appstore->addRequestData('app', $app_uniqueid);
							$appinfo = $this->appstore->request('index.php');
						}
						else
						{
							$version_typ = 'version';
							$version = $vv['version'];
						}
						$version = $vv[$version_typ];
						file_get_contents('http://web.innner.hogesoft.com/control/release.php?app=' . $app_uniqueid . '&version=' . $version);
						echo $vv['name'] . '发布成功。<br />';
					}
				}
			}
		}
	}
	public function pre_release_all()
	{
		$this->appstore->initPostData();
		$menu_apps = $this->appstore->request('index.php');
		$menu_apps = $menu_apps['apps'];
		if ($menu_apps)
		{
			foreach ($menu_apps AS $k => $v)
			{
				if ($v)
				{
					foreach ($v AS $kk => $vv)
					{
						$app_uniqueid = $vv['app_uniqueid'];
						
						if ($this->input['new_version'])
						{
							$version = $this->add_version($vv['version']);
							
							$this->appstore->initPostData();
							$this->appstore->addRequestData('a', 'update_version');
							$this->appstore->addRequestData('pre_release', '1');
							$this->appstore->addRequestData('version', $version);
							$this->appstore->addRequestData('content', $content);
							$this->appstore->addRequestData('app', $app_uniqueid);
							$this->appstore->request('index.php');
						}
						else
						{
							$version = $vv['version'];
						}
						file_get_contents('http://web.innner.hogesoft.com/control/release.php?pre_release=1&app=' . $app_uniqueid . '&version=' . $version);

						echo $vv['name'] . '预发布成功。<br />';
					}
				}
			}
		}
	}
	private function add_version($version)
	{
		$version = explode('.', $version);
		$version[2] = $version[2] + 1;
		if (strlen($version[2]) > 1)
		{
			$version[2] = substr($version[2], 1);
			$version[1] = $version[1] + 1;		
			if (strlen($version[1]) > 1)
			{
				$version[1] = substr($version[1], 1);
				$version[0] = $version[0] + 1;
			}
		}
		$version = implode('.', $version);
		return $version;
	}
	public function dopre_release()
	{
		$version = trim($this->input['version']);
		$content = trim($this->input['content']);
		if (!$content)
		{
			$this->pre_release('请填写版本特性');
		}
		$match = preg_match('/^[0-9]+\.[0-9]+\.[0-9]+$/is', $version);
		if (!$match)
		{
			$this->pre_release('对不起，版本号设置错误');
		}
		if ($version < $this->appinfo['pre_version'])
		{
			$this->pre_release('对不起，预发布版本不能低于当前版本');
		}
		$ret = file_get_contents('http://web.innner.hogesoft.com/control/release.php?pre_release=1&app=' . $this->appinfo['app_uniqueid'] . '&version=' . $version);
		if ($ret == 'VERSION_FORMAT_ERROR')
		{
			$this->pre_release('对不起，版本号设置错误');
		}
		
		$curl = new curl($this->product_server['host'], $this->product_server['dir']);
		$curl->mAutoInput = false;
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$i = 0;
		$success = false;
		while (true && $i < 30)
		{				
			$curl->initPostData();
			$postdata = array(
				'app'		=>	$this->appinfo['app_uniqueid'],
				'a'				=>	'checklastversion',
				'pre_release'	=>	'1',
			);
			foreach ($postdata as $k=>$v)
			{
				$curl->addRequestData($k, $v);
			}
			$rversion = $curl->request('check_version.php');
			if ($rversion == $version)
			{
				$success = true;
				break;
			}
			sleep(1);
			$i++;
		}
		
		$this->appstore->initPostData();
		$this->appstore->addRequestData('a', 'update_version');
		$this->appstore->addRequestData('pre_release', '1');
		$this->appstore->addRequestData('version', $version);
		$this->appstore->addRequestData('content', $content);
		$this->appstore->addRequestData('app', $this->appinfo['app_uniqueid']);
		$appinfo = $this->appstore->request('index.php');
		$appinfo = $appinfo[0];
		if ($appinfo['id'] && $success)
		{
			$url = '?app=' . $appinfo['app_uniqueid'];
			$this->redirect('应用版本预发布成功', $url);
		}
		else
		{
			$this->pre_release('应用版本预发布失败');
		}
	}
	public function dorelease()
	{
		$version = $this->appinfo['pre_version'];
		$ret = file_get_contents('http://web.innner.hogesoft.com/control/release.php?app=' . $this->appinfo['app_uniqueid'] . '&version=' . $version);
		if ($ret == 'VERSION_FORMAT_ERROR')
		{
			$this->pre_release('对不起，版本号设置错误');
		}
		
		$curl = new curl($this->product_server['host'], $this->product_server['dir']);
		$curl->mAutoInput = false;
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$i = 0;
		$success = false;
		while (true && $i < 30)
		{				
			$curl->initPostData();
			$postdata = array(
				'app'		=>	$this->appinfo['app_uniqueid'],
				'a'				=>	'checklastversion',
			);
			foreach ($postdata as $k=>$v)
			{
				$curl->addRequestData($k, $v);
			}
			$rversion = $curl->request('check_version.php');
			if ($rversion == $version)
			{
				$success = true;
				break;
			}
			sleep(1);
			$i++;
		}
		$this->appstore->initPostData();
		$this->appstore->addRequestData('a', 'update_version');
		$this->appstore->addRequestData('version', $version);
		$this->appstore->addRequestData('app', $this->appinfo['app_uniqueid']);
		$appinfo = $this->appstore->request('index.php');
		$appinfo = $appinfo[0];
		if ($appinfo['id'] && $success)
		{
			$url = '?';
			$this->redirect('应用版本发布成功', $url);
		}
		else
		{
			$this->pre_release('应用版本发布失败');
		}
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>