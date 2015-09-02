<?php
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . 'global.php');
require(CUR_CONF_PATH . 'lib/appstore_frm.php');
class index extends appstore_frm
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
		$offset = intval($this->input['offset']);
		$count = intval($this->input['count']);
		$count = $count ? $count : 100;
		if ($this->user['install_type'] == 'pre-release')
		{
			$version = 'pre_version';
		}
		else
		{
			$version = 'version';
			$cond = ' AND only_pre_release = 0';
		}
		if ($this->user['app_limit'])
		{
			$applimit = explode(',', $this->user['app_limit']);
			$cond .= " AND app_uniqueid IN('" . implode("','", $applimit) . "')";
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'apps WHERE customer_id IN (0, ' . $this->user['id'] . ')' . $cond . ' ORDER BY order_id DESC LIMIT ' . $offset . ',' . $count;
		$q = $this->db->query($sql);
		$all_apps = array();
		$app_uniqueids = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['version'] = $r[$version];
			$all_apps[$r['id']] = $r;
			$app_uniqueids[] = $r['app_uniqueid'];
		}
		$sql = 'SELECT id FROM ' . DB_PREFIX . 'app_class WHERE fid=' . intval($this->input['fid']) . ' ORDER BY order_id ASC';
		$q = $this->db->query($sql);
		$apps = array();
		while($r = $this->db->fetch_array($q))
		{
			$apps[$r['id']] = array();
		}
		if ($all_apps)
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'customer_apps WHERE customer_id = ' . intval($this->user['id']) . ' AND app_uniqueid IN (\'' . implode("','", $app_uniqueids) . '\')';
			$q = $this->db->query($sql);
			$installed_apps = array();
			while($r = $this->db->fetch_array($q))
			{
				$installed_apps[$r['app_uniqueid']] = $r;
			}
			foreach ($all_apps AS $appid => $v)
			{
				if (!$installed_apps[$v['app_uniqueid']])
				{
					$v['status'] = 0;
					$v['url'] = 'appstore.php?app=' . $v['app_uniqueid'];
				}
				else
				{
					if ($installed_apps[$v['app_uniqueid']]['version'] < $v[$version])
					{
						$v['status'] = -1;
						$v['url'] = 'appstore.php?app=' . $v['app_uniqueid'];
					}
					else
					{
						$v['status'] = 1;
						$v['url'] = 'run.php?a=relate_module_show&app_uniq=' . $v['app_uniqueid'];
					}
				}
				$apps[$v['class_id']][$v['id']] = $v;
			}
		}
		$this->addItem_withkey('apps', $apps);
		$this->output();
	}

	
	public function detail()
	{
		if ($this->user['install_type'] == 'pre-release')
		{
			$version = 'pre_version';
			$pre_release = 1;
		}
		else
		{
			$version = 'version';
			$pre_release = 0;
		}
		$app = $this->input['app'];
		$sql = 'SELECT * FROM ' . DB_PREFIX . "apps WHERE app_uniqueid='$app'";
		$appinfo = $this->db->query_first($sql);
		if ($appinfo)
		{
			$appinfo['version'] = $appinfo[$version];
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'customer_apps WHERE customer_id = ' . intval($this->user['id']) . " AND app_uniqueid = '{$appinfo['app_uniqueid']}'";
			$installed_app = $this->db->query_first($sql);
			if (!$installed_app)
			{
				$appinfo['status'] = 0;
				
				if($appinfo['relyonapps'])
				{
					$relyonapps = explode(',', $appinfo['relyonapps']);
					$sql = 'SELECT * FROM ' . DB_PREFIX . 'customer_apps WHERE customer_id = ' . intval($this->user['id']) . " AND app_uniqueid IN( '" . implode("','", $relyonapps) .  "')";
					$q = $this->db->query($sql);
					$installed_apps = array();
					while($r = $this->db->fetch_array($q))
					{
						$installed_apps[$r['app_uniqueid']] = $r;
					}
					foreach($relyonapps AS $app)
					{
						if (!$installed_apps[$app])
						{
							$sql = 'SELECT * FROM ' . DB_PREFIX . "apps WHERE app_uniqueid='$app'";
							$relyappinfo = $this->db->query_first($sql);
							$relyappinfo['sourceapp'] = $appinfo;
							$appinfo = $relyappinfo;
							$appinfo['relyon'] = 1;
							break;
						}
					}
				}
				$appinfo['url'] = 'appstore.php?app=' . $appinfo['app_uniqueid'];
			}
			else
			{
				if ($installed_app['version'] < $appinfo[$version])
				{
					$appinfo['status'] = -1;
					$appinfo['url'] = 'appstore.php?app=' . $appinfo['app_uniqueid'];
					
					$sql = 'SELECT * FROM ' . DB_PREFIX . "version_features WHERE app_uniqueid='{$appinfo['app_uniqueid']}' AND pre_release='$pre_release' AND version > '{$installed_app['version']}' ORDER BY version DESC";
					$q = $this->db->query($sql);
					$version_features = array();
					while($r = $this->db->fetch_array($q))
					{
						$version_features[$r['version']] = $r['content'];
					}
					$appinfo['install_version'] = $installed_app['version'];
					$appinfo['version_features'] = $version_features;
				}
				else
				{
					$appinfo['status'] = 1;
					$appinfo['url'] = 'run.php?a=relate_module_show&app_uniq=' . $appinfo['app_uniqueid'];
				}
			}
		}
		$this->addItem($appinfo);
		$this->output();
	}

	public function get_installed_version()
	{
		$app = trim($this->input['app']);

		$sql = 'SELECT * FROM ' . DB_PREFIX . 'customer_apps WHERE customer_id = ' . intval($this->user['id']) . " AND app_uniqueid = '{$app}'";
		$app = $this->db->query_first($sql);
		$this->addItem($app);
		$this->output();
	}

	public function installed()
	{
		$app = trim($this->input['app']);
		if ($app == 'livworkbench')
		{
			$appinfo = array(
				'app_uniqueid' => $app,
			);
		}
		else
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . "apps WHERE app_uniqueid='$app'";
			$appinfo = $this->db->query_first($sql);
		}
		if ($appinfo)
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'customer_apps WHERE customer_id = ' . intval($this->user['id']) . " AND app_uniqueid = '{$appinfo['app_uniqueid']}'";
			$installed_app = $this->db->query_first($sql);
			if (!$installed_app)
			{
				$curl = new curl($this->settings['App_upgradeServer']['host'], $this->settings['App_upgradeServer']['dir']);
				$curl->initPostData();
				$postdata = array(
					'appid'			=>	$this->input['appid'],
					'appkey'		=>	$this->input['appkey'],
					'app'		=>	$appinfo['app_uniqueid'],
					'a'				=>	'checklastversion',
				);
				foreach ($postdata as $k=>$v)
				{
					$curl->addRequestData($k, $v);
				}
				$version = $curl->request('check_version.php');
				$sql = 'INSERT INTO ' . DB_PREFIX . "customer_apps (customer_id,app_uniqueid,version,create_time,update_time ) VALUES ({$this->user['id']}, '{$appinfo['app_uniqueid']}', '{$version}', " . TIMENOW . ", " . TIMENOW . ")";
				$this->db->query($sql);
			}
		}
		$this->addItem($appinfo);
		$this->output();
	}
	public function update_version()
	{
		$app = trim($this->input['app']);
		$version = trim($this->input['version']);		
		$pre_release = intval($this->input['pre_release']);		
		$match = preg_match('/^[0-9]+\.[0-9]+\.[0-9]+$/is', $version);
		if (!$match)
		{
			$this->errorOutput('VERSION_FORMAT_ERROR');
		}
		if ($pre_release)
		{
			$field = 'pre_version';
		}
		else
		{
			$field = 'version';
		}

		$sql = 'UPDATE ' . DB_PREFIX . "apps SET $field='$version' WHERE app_uniqueid='$app'";
		$this->db->query($sql);
		$sql = 'SELECT * FROM ' . DB_PREFIX . "apps WHERE app_uniqueid='$app'";
		$appinfo = $this->db->query_first($sql);
		$this->addItem($appinfo);
		$this->output();
	}
	public function updated()
	{
		$app = trim($this->input['app']);
		if ($app == 'livworkbench')
		{
			$appinfo = array(
				'app_uniqueid' => $app,
			);
		}
		else
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . "apps WHERE app_uniqueid='$app'";
			$appinfo = $this->db->query_first($sql);
		}
		if ($appinfo)
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'customer_apps WHERE customer_id = ' . intval($this->user['id']) . " AND app_uniqueid = '{$appinfo['app_uniqueid']}'";
			$installed_app = $this->db->query_first($sql);
			$curl = new curl($this->settings['App_upgradeServer']['host'], $this->settings['App_upgradeServer']['dir']);
			$curl->initPostData();
			$postdata = array(
				'appid'			=>	$this->input['appid'],
				'appkey'		=>	$this->input['appkey'],
				'app'		=>	$appinfo['app_uniqueid'],
				'a'				=>	'checklastversion',
			);
			foreach ($postdata as $k=>$v)
			{
				$curl->addRequestData($k, $v);
			}
			$version = $curl->request('check_version.php');
			if ($installed_app)
			{
				$sql = 'UPDATE ' . DB_PREFIX . "customer_apps  SET version='{$version}', update_time=" . TIMENOW . " WHERE customer_id='{$this->user['id']}' AND app_uniqueid='{$appinfo['app_uniqueid']}'";
			}
			else
			{
				$sql = 'INSERT INTO ' . DB_PREFIX . "customer_apps (customer_id,app_uniqueid,version,create_time,update_time ) VALUES ({$this->user['id']}, '{$appinfo['app_uniqueid']}', '{$version}', " . TIMENOW . ", " . TIMENOW . ")";
			}
			$this->db->query($sql);
		}
		$this->addItem($appinfo);
		$this->output();
	}
	public function get_sort()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'app_class WHERE fid=' . intval($this->input['fid']) . ' ORDER BY order_id ASC';
		$q = $this->db->query($sql);
		$sort = array();
		while($r = $this->db->fetch_array($q))
		{
			$sort[$r['id']] = $r;
		}

		$this->addItem_withkey('sort', $sort);
		$this->output();
	}

	public function rebuild_installed()
	{
		$app = trim($this->input['app']);
		$version = trim($this->input['version']);
		$sql = 'SELECT * FROM ' . DB_PREFIX . "apps WHERE app_uniqueid='$app'";
		$appinfo = $this->db->query_first($sql);
		if ($appinfo)
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'customer_apps WHERE customer_id = ' . intval($this->user['id']) . " AND app_uniqueid = '{$appinfo['app_uniqueid']}'";
			$installed_app = $this->db->query_first($sql);
			if (!$installed_app)
			{
				$sql = 'INSERT INTO ' . DB_PREFIX . "customer_apps (customer_id,app_uniqueid,version,create_time,update_time ) VALUES ({$this->user['id']}, '{$appinfo['app_uniqueid']}', '{$version}', " . TIMENOW . ", " . TIMENOW . ")";
				$this->db->query($sql);
			}
		}
		$this->addItem($appinfo);
		$this->output();
	}
}
$module = 'index';
$$module = new $module();  

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
	$func = 'show';	
}
$$module->$func();
?>