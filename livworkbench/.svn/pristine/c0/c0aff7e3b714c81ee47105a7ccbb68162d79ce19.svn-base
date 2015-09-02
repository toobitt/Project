<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'installed');
require('./global.php');
require('./lib/class/curl.class.php');
class installed extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
		if ($this->user['group_type'] != 1)
		{
			$this->ReportError('对不起，您没有权限管理系统!');
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$this->appstore = new curl('appstore.hogesoft.com:233', '');
		$this->appstore->mAutoInput = false;
		$this->appstore->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'applications ORDER BY id desc';
		$q = $this->db->query($sql);
		$applications = array();
		while ($row = $this->db->fetch_array($q))
		{
			$this->appstore->initPostData();	
			$this->appstore->addRequestData('a', 'rebuild_installed');
			$this->appstore->addRequestData('app', $row['softvar']);
			$this->appstore->addRequestData('version', $row['version']);
			$this->appstore->request('index.php');
			echo $row['name'] . ' installed <br />';
		}
	}
	public function auth()
	{
		$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
		$curl->mAutoInput = false;
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'applications ORDER BY id desc';
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			echo $row['name'] . '<br />';
			$row['dir'] = str_replace($row['admin_dir'], '', $row['dir']);
			$curl->initPostData();
			foreach ($row AS $k => $v)
			{
				$curl->addRequestData($k, $v);
			}
			$curl->addRequestData('bundle', $row['softvar']);
			$ret = $curl->request('admin/apps.php');
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'modules ORDER BY id desc';
		$q = $this->db->query($sql);
		$app_main_module = array();
		while ($row = $this->db->fetch_array($q))
		{
			echo $row['name'] . '<br />';
			$row['dir'] = str_replace($row['admin_dir'], '', $row['dir']);
			$row['main_module'] = 0;
			if($row['app_uniqueid'] == $row['mod_uniqueid'])
			{
				$row['main_module'] = 1;
			}
			if($row['menu_pos'] == -1)
			{
				$row['main_module'] = 2;
			}
			
			$modules[$row['id']] = $row;
			
			if($row['main_module'])
			{
				//纪录各个应用主模块 menu_pos优先
				if($modules[$app_main_module[$row['app_uniqueid']]]['main_module'] <= $row['main_module'])
				{
					$app_main_module[$row['app_uniqueid']] = $row['id'];
				}
			}
		}
		if($modules)
		{
			foreach ($modules as $mid=>$row)
			{
				if($row['main_module'] && in_array($row['id'], $app_main_module))
				{
					$row['main_module'] = 1;
				}
				else
				{
					$row['main_module'] = 0;
				}
				$curl->initPostData();
				foreach ($row AS $k => $v)
				{
					$curl->addRequestData($k, $v);
				}
				$ret = $curl->request('admin/modules.php');
			}
		}
	}

	public function menu()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'modules ORDER BY order_id ASC, id ASC';
		$q = $this->db->query($sql);
		$menu = array();
		while($row = $this->db->fetch_array($q))
		{
			echo $row['name'] . '<br />';
			if ($row['target'])
			{
				$atarget = '';
			}
			else
			{
				$atarget = 'a=frame&';
			}
			if (!$row['class_id'])
			{
				$row['class_id'] = 7;
			}
			if ($row['menu_pos'] == -1)
			{
				$menu[$row['application_id']][-1] = array(
					'name' => $row['name'], 	 	
					'module_id' => $row['id'],
					'app_uniqueid' => $row['app_uniqueid'],
					'mod_uniqueid' => $row['mod_uniqueid'],
					'url' => 'run.php?' . $atarget . 'mid=' . $row['id'],
					'close' => 0,
					'order_id' => $row['order_id'],
					'father_id' => $row['class_id'],
					'include_apps'=>$row['app_uniqueid'],
					'`index`'=>0,
				);
			}
			if ($row['app_uniqueid'] == $row['mod_uniqueid'])
			{
				if (!$menu[$row['application_id']][-1])
				{
					$menu[$row['application_id']][-1] = array(
						'name' => $row['name'], 	 	
						'module_id' => $row['id'],
						'app_uniqueid' => $row['app_uniqueid'],
						'mod_uniqueid' => $row['mod_uniqueid'],
						'url' => 'run.php?' . $atarget . 'mid=' . $row['id'],
						'close' => 0,
						'father_id' => $row['class_id'],
						'order_id' => $row['order_id'],
						'include_apps'=>$row['app_uniqueid'],
						'`index`'=>0,
					);
				}
			}
			else
			{
				if ($row['menu_pos'] == 0)
				{
					$menu[$row['application_id']][] = array(
						'name' => $row['name'],  	
						'module_id' => $row['id'],
						'app_uniqueid' => $row['app_uniqueid'],
						'mod_uniqueid' => $row['mod_uniqueid'],
						'url' => 'run.php?mid=' . $row['id'],
						'close' => 0,
						'father_id' => 0,
						'order_id' => $row['order_id'],
						'include_apps'=>$row['app_uniqueid'],
						'`index`'=>0,
					);
				}
			}
		}
		$sql = 'TRUNCATE TABLE ' . DB_PREFIX . 'menu';
		$this->db->query($sql);
		$file = 'conf/init.data';
		if (is_file($file))
		{
		 	$content = file_get_contents($file);
			if ($content)
			{
				$this->db = hg_checkDB();
				preg_match_all('/INSERT\s+INTO\s+(.*?)\(.*?\)\s*;;/is', $content, $match);
				$insertsql = $match[0];
				if ($insertsql)
				{
					//$this->db->mErrorExit = false;
					foreach ($insertsql AS $sql)
					{
						echo $sql = preg_replace('/INSERT\s+INTO\s+([`]{0,1})liv_/is', 'INSERT INTO \\1' . DB_PREFIX, $sql);
						$this->db->query($sql);
					}
					//$this->db->mErrorExit = true;
				}
			}
		}
		$menus = $menu;
		foreach ($menus AS $menu)
		{
			if ($menu[-1])
			{
				$mmenu = $menu[-1];
				$sql = 'SELECT * FROM ' . DB_PREFIX . "menu WHERE app_uniqueid='{$mmenu['app_uniqueid']}' AND mod_uniqueid='{$mmenu['mod_uniqueid']}'";
				$q = $this->db->query_first($sql);
				if ($q)
				{
					$sql = 'UPDATE ' . DB_PREFIX . "menu SET name='{$mmenu['name']}', father_id={$mmenu['father_id']},url='{$mmenu['url']}', module_id='{$mmenu['module_id']}' WHERE id={$q['id']} ";
					$this->db->query($sql);
				}
				else
				{
					$sql = 'INSERT INTO ' . DB_PREFIX . 'menu (' . implode(',', array_keys($mmenu)) . ') VALUES ';
					$sql .= "('" . implode("','", $mmenu) . "')";
					
					$this->db->query($sql);
					$q['id'] = $this->db->insert_id();
					$sql = 'UPDATE ' . DB_PREFIX . "menu set include_apps=concat(include_apps, '{$mmenu['app_uniqueid']}', ',') WHERE id=" . intval($mmenu['father_id']);
					$this->db->query($sql);
				}
				foreach ($menu AS $k => $mmenu)
				{
					if($k != -1)
					{
						$mmenu['father_id'] = $q['id'];
						$sql = 'SELECT * FROM ' . DB_PREFIX . "menu WHERE app_uniqueid='{$mmenu['app_uniqueid']}' AND mod_uniqueid='{$mmenu['mod_uniqueid']}'";
						$exist = $this->db->query_first($sql);
						if ($exist)
						{
							$sql = 'UPDATE ' . DB_PREFIX . "menu SET name='{$mmenu['name']}', father_id={$mmenu['father_id']},url='{$mmenu['url']}', module_id='{$mmenu['module_id']}' WHERE id={$exist['id']} ";
							$this->db->query($sql);
						}
						else
						{
							$sql = 'INSERT INTO ' . DB_PREFIX . 'menu (' . implode(',', array_keys($mmenu)) . ') VALUES ';
							$sql .= "('" . implode("','", $mmenu) . "')";
							
							$this->db->query($sql);
						}
					}
				}
			}
		}
		
		$this->cache->recache('applications');
		$this->cache->recache('modules');
		$this->cache->recache('menu');
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>