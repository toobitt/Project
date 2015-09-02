<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: recache.class.php 1524 2011-01-04 09:46:16Z yuna $
***************************************************************************/

/**
 * 
 * 从memcache获取数据,不存在将重建缓存
 * 
 */
class recache
{
	private $memcache;
	private $db;
	private $cache;
	
	function __construct()
	{
		global $gCache;
		$this->db = hg_checkDB();
		$this->cache = $gCache;
	}
	
	function __destruct()
	{
	}
	public function am_recache()
	{
		$sql = "SELECT  name,softvar FROM " . DB_PREFIX . "applications ORDER BY order_id asc, id desc";
		$q = $this->db->query($sql);
		$this->cache->cache['apps'] = array();
		while($row = $this->db->fetch_array($q))
		{
			$this->cache->cache['apps'][$row['softvar']] = $row['name']; 	
		}
		$this->cache->update_cache(array('name' => 'apps')); 
		$sql = 'SELECT m.mod_uniqueid, m.app_uniqueid, m.name FROM ' . DB_PREFIX . 'modules m ';
		$q = $this->db->query($sql);
		$this->cache->cache['modules'] = array();
		while($row = $this->db->fetch_array($q))
		{
			$this->cache->cache['modules'][$row['app_uniqueid']][$row['mod_uniqueid']] = $row['name']; 	
		}
		$this->cache->update_cache(array('name' => 'modules')); 
	}
	private function get_applications()
	{
		$sql = "SELECT id, name,father_id, logo, softvar,nosetting,related_app,host,dir,softvar,version FROM " . DB_PREFIX . "applications ORDER BY order_id asc, id desc";
		$q = $this->db->query($sql);
		$this->cache->cache['applications'] = array();
		$i = 0;
		while($row = $this->db->fetch_array($q))
		{
			$i++;
			$type = strtolower(strrchr($row['logo'], '.'));
			if (!in_array($type, array('.gif', '.jpeg', '.jpg', '.png')))
			{
				$row['class'] = $row['logo'];
				$row['logo'] = '';
			}
			$this->cache->cache['applications'][$row['id']] = $row; 		
		}
	}

	private function get_modules()
	{
		$sql = 'SELECT m.id, m.mod_uniqueid, m.app_uniqueid, m.name,m.icon, m.file_name, m.fatherid, m.application_id,m.menu_pos, m.relate_molude_id,m.parents, mn.node_id, mn.module_op FROM ' . DB_PREFIX . 'modules m 
					LEFT JOIN ' . DB_PREFIX . 'module_node mn 
						ON m.id=mn.module_id 
					ORDER BY m.order_id ASC, m.id desc';
		
		$q = $this->db->query($sql);
		$this->cache->cache['modules'] = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['url'] = 'run.php?mid=' . $row['id'];
			if (($row['node_id'] && !$row['module_op']) || $row['fatherid'])
			{
				$row['url'] .= '&amp;a=frame';
			}
			if (!$row['fatherid'] && !$row['file_name'])
			{
				$row['url'] = '';
			}
			elseif ($row['fatherid'] && !$row['file_name'])
			{
				$row['url'] = 'run.php?mid=' . $row['relate_molude_id'];
			}
			unset($row['node_id'], $row['file_name'], $row['relate_molude_id']);
			$type = strtolower(strrchr($row['icon'], '.'));
			if (!in_array($type, array('.gif', '.jpeg', '.jpg', '.png')))
			{
				$row['class'] = $row['icon'];
				$row['icon'] = '';
			}
			$this->cache->cache['modules'][$row['id']] = $row; 		
		}
	}
	
	private function cache_menu()
	{
		if (!$this->cache->cache['applications'])
		{
			$this->get_applications();
		}
		if (!$this->cache->cache['modules'])
		{
			$this->get_modules();
		}
		$applications = $this->cache->cache['applications'];
		$modules = $this->cache->cache['modules'];
		$this->cache->cache['menu'] = array();
		$app_modules = array();
		$app_module_childs = array();
		foreach ($modules AS $k => $v)
		{
			if ($v['menu_pos'])
			{
				continue;
			}
			if (!$v['fatherid'])
			{
				$app_modules[$v['application_id']][] = $v;
			}
			else
			{
				$app_module_childs[$v['fatherid']][] = $v;
			}
		}
		$this->cache->cache['menu'] = array();
		foreach ($applications AS $k => $v)
		{
			//第一级
			if (!$v['father_id'])
			{
				$menu = array(
					'id' => $v['id'],	
					'module_id' => $v['id'],	
					'name' => $v['name'],	
					'url' => '',	
					'appid' => 0,
					'class' => $v['class'],	
					'type' => '0',	
					'childs' => array(),	
				);
				if ($app_modules[$v['id']])
				{
					foreach ($app_modules[$v['id']] AS $kk => $vv)
					{
						if (!$vv['url'])
						{
							$mcilds = $app_module_childs[$vv['id']][0];
							$vv['id'] = $mcilds['id'];
							$vv['url'] = $mcilds['url'];
						}
						$menu['childs'][$vv['id']] = array(
							'id' => $vv['id'],	
							'module_id' => $vv['id'],	
							'name' => $vv['name'],	
							'appid' => $v['id'],
							'url' => $vv['url'],	
							'class' => $vv['class'],	
							'type' => '1',	
						);
					}
				}
				else
				{
					continue;
				}
				$this->cache->cache['menu'][$v['id']] = $menu;
			}
		}
		$this->cache->update_cache(array('name' => 'menu')); 
	}

	/**
	 * 重建菜单缓存
	 */
	public function menu_recache2()
	{
		$sql = "SELECT id, module_id, name,father_id, class, url,close FROM " . DB_PREFIX . "menu ORDER BY order_id asc, id ASC";
		
		$q = $this->db->query($sql);
		$menu = array();
		while($row = $this->db->fetch_array($q))
		{
			$menu[$row['id']] = $row; 
		}
		$smenu = array();
		foreach ($menu AS $k => $row)
		{
			if ($row['close'])
			{
				continue;
			}
			if ($row['father_id'])
			{
				if ($menu[$row['father_id']]['close'])
				{
					continue;
				}
				$smenu[$row['father_id']]['childs'][$row['id']] = $row; 
			}
			else
			{
				if ($smenu[$row['id']]['childs'])
				{
					$tmp = $smenu[$row['id']]['childs'];
				}
				else
				{
					$tmp = array();
				}
				$smenu[$row['id']] = $row; 
				$smenu[$row['id']]['childs'] = $tmp;
			}
		}
		$this->cache->cache['menu'] = $smenu;
		$this->cache->update_cache(array('name' => 'menu')); 
	}

	/*
	 *让菜单无限级输出
	 *重建菜单缓存
	 */
	public function menu_recache()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "menu ORDER BY order_id asc, id ASC";
		
		$q = $this->db->query($sql);
		$menu = array();
		while($row = $this->db->fetch_array($q))
		{
			$menu[] = $row; 
		}
		$smenu = array();
		//$this->recursion_menu($smenu,$menu,0);
		$smenu = $this->rebuild_menu($menu);
		$this->cache->cache['menu_group'] = $smenu['group'];
		$this->cache->update_cache(array('name' => 'menu_group'));
		$this->cache->cache['menu_apps'] = $smenu['apps'];
		$this->cache->update_cache(array('name' => 'menu_apps'));
		if (is_array($smenu['apps']))
		{
			foreach ($smenu['apps'] AS $id => $v)
			{
				if (is_array($v))
				{
					foreach ($v AS $kk => $vv)
					{
						$this->cache->cache['menu_module_' . $vv['app_uniqueid']] = $smenu['modules'][$kk];
						$this->cache->update_cache(array('name' => 'menu_module_' . $vv['app_uniqueid']));
					}
				}
			}
		}
	}

	private function rebuild_menu($menu)
	{
		$menus = array();
		if ($menu)
		{
			foreach ($menu AS $v)
			{
				if($v['close'])
				{
					continue;
				}
				if ($v['father_id'] == 0)
				{
					$menus['group'][$v['id']] = $v;
				}
			}
		}
		if ($menus['group'])
		{
			foreach($menus['group'] AS $g)
			{
				if ($menu)
				{
					foreach ($menu AS $v)
					{
						if($v['close'])
						{
							continue;
						}
						if ($v['father_id'] == $g['id'])
						{
							$menus['apps'][$g['id']][$v['id']] = $v;
						}
					}
				}
			}
		}
		if ($menus['apps'])
		{
			foreach($menus['apps'] AS $id => $g)
			{
				if (is_array($g))
				{
					foreach ($g AS $a)
					{
						if ($menu)
						{
							foreach ($menu AS $v)
							{
								if($v['close'])
								{
									continue;
								}
								if ($v['father_id'] == $a['id'])
								{
									$menus['modules'][$a['id']][$v['id']] = $v;
								}
							}
						}
					}
				}
				else
				{
					unset($menus['apps'][$id]);
				}
			}
		}
		return $menus;
	}

	//菜单递归
	public function recursion_menu(&$menu,$old_menu,$father_id)
	{
		foreach($old_menu AS $k => $v)
		{
			if($v['father_id'] == $father_id)
			{
				if($v['close'])
				{
					continue;
				}
				$menu[$v['id']] = $v;
				$menu[$v['id']]['childs'] = array();
				$this->recursion_menu($menu[$v['id']]['childs'],$old_menu,$v['id']);
			}
		}
	}

	/**
	 * 重建系统缓存
	 */
	public function applications_recache()
	{
		$this->get_applications();
		$applications = $this->cache->cache['applications'];
		$app_applications = array();
		foreach ($applications AS $k => $v)
		{
			$app_applications[$v['father_id']][] = $v;
		}
		foreach ($applications AS $k => $v)
		{
			if (count($app_applications[$v['id']]) > 0)
			{
				$has_child = 1;
			}
			else
			{
				$has_child = 0;
			}
			$this->cache->cache['applications'][$k]['has_child'] = $has_child;
		}
		$this->cache->update_cache(array('name' => 'applications')); 
		//$this->cache_menu();
	}

	

	/**
	 * 重建授权操作缓存
	 */
	public function authorize_op_recache()
	{
		$this->get_applications();
		$applications = $this->cache->cache['authorize_op'];
		$sql = "SELECT * FROM " . DB_PREFIX . "authorize_op ORDER BY id ASC";
		
		$q = $this->db->query($sql);
		$this->cache->cache['authorize_op'] = array();
		while($row = $this->db->fetch_array($q))
		{
			$this->cache->cache['authorize_op'][$row['id']] = $row['name']; 		
		}
		$this->cache->update_cache(array('name' => 'authorize_op')); 
	}

	/**
	 * 重建模块缓存
	 */
	public function modules_recache()
	{
		$this->get_modules();
		$this->cache->update_cache(array('name' => 'modules')); 
		//$this->cache_menu();
	}
	/**
	 * 重建栏目数据缓存
	 */
	public function columns_recache()
	{
		$this->get_columns();
		$this->cache->update_cache(array('name' => 'columns')); 
	}
	/**
	 * 重建服务器信息缓存
	 */
	public function servers_recache()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "servers ORDER BY id ASC";
		
		$q = $this->db->query($sql);
		$data = array();
		while($row = $this->db->fetch_array($q))
		{
			$data[$row['type']][$row['id']] = $row['name']; 
		}
		$this->cache->cache['servers'] = $data;
		$this->cache->update_cache(array('name' => 'servers')); 
	}
	//获取栏目以及栏目的子节点
	private function get_columns()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'columns ORDER BY id ASC';
		
		$q = $this->db->query($sql);
		$this->cache->cache['columns'] = array();
		$columns = array();
		while($row = $this->db->fetch_array($q))
		{
			$columns[$row['id']] = array('name'=>$row['name'], 'fid'=>$row['fatherid']);
		}		
		foreach($columns as $id=>$col)
		{
			$this->reset_childs();
			$childs = $id.$this->get_column_childs($columns, $id);
			$parents = $this->get_column_pars($columns, $id);
			$this->cache->cache['columns'][$id] = array('parents'=>$parents,'childs'=>$childs);
		}
	}
	private static $colchilds = '';
	//获取栏目的子节点
	private function get_column_childs($columns, $id)
	{
		foreach($columns as $k=>$v)
		{
			if($id == $v['fid'])
			{
				$this->colchilds .= ','.$k;
				$this->get_column_childs($columns,$k);
			}
		}
		return $this->colchilds;
	}
	private function reset_childs()
	{
		$this->colchilds = '';
	}
	//获取栏目的父节点
	private function get_column_pars($columns, $id)
	{
		$fid = $columns[$id]['fid'];
		if($fid)
		{
			return $id . ',' . $this->get_column_pars($columns, $fid);
		}
		return $id;
	}
}
?>