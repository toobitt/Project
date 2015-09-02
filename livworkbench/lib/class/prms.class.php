<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: recache.class.php 1524 2011-01-04 09:46:16Z yuna $
***************************************************************************/

class prms
{
	private $db;
	private $user;
	
	function __construct()
	{
		global $gUser;
		$this->user = &$gUser;
	}
	
	function __destruct()
	{
	}
	/**
	*
	* 
	*/
	public function load_prms($type = 'show')
	{
		$admin_group_id = $this->user['admin_group_id'];
		$admin_id = $this->user['id'];
		if (!@include (CACHE_DIR . 'prms/ag_' . $admin_group_id . '.php'))
		{
			$hg_group_prms = $this->rebuild_prms($admin_group_id);
		}
		$prms = $hg_group_prms[$type];
		if (!$prms)
		{
			$prms = array();
		}
		return $prms;
	}
	
	public function rebuild_prms($group_id)
	{
		$this->db = hg_checkDB();
		//取出所有模块
		$sql = 'SELECT id, application_id FROM ' . DB_PREFIX . 'modules';
		$q = $this->db->query($sql);
		$modules = array();
		$module_application = array();
		while($row = $this->db->fetch_array($q))
		{
			$modules[$row['application_id']][] = $row['id'];
			$module_application[$row['id']] = $row['application_id']; //记录模块所属的系统id
		}
		//取权限设置
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'prms
					WHERE admin_group_id=' . $group_id;
		$prms = $this->db->query_first($sql);
		$sys_prms = unserialize($prms['sys_prms']);
		$module_prms = unserialize($prms['module_prms']);
		//重建权限格式
		$re_prms = array();
		$set_module_appid = array();
		if ($module_prms)
		{
			foreach ($module_prms AS $mid => $op)
			{
				foreach ($op AS $k => $v)
				{
					if ($v)
					{
						$appid = $module_application[$mid];
						$set_module_appid[$k][$appid] = $appid; //记录已经设置模块权限的系统
						$re_prms[$k][] = $mid;
					}
				}
			}
		}
		if ($sys_prms)
		{
			foreach ($sys_prms AS $appid => $op)
			{
				foreach ($op AS $k => $v)
				{
					if ($v)
					{
						//设置模块权限的系统不合并权限
						if ($set_module_appid[$k] && in_array($appid, $set_module_appid[$k]))
						{
							//do nothing
						}
						else
						{
							if ($re_prms[$k])
							{
								$re_prms[$k] = @array_merge($re_prms[$k], $modules[$appid]);
							}
							else
							{
								$re_prms[$k] = $modules[$appid];
							}
						}
					}
				}
			}
		}
		
		$prms_str = '<?php $hg_group_prms = ' . var_export($re_prms, TRUE) . '; ?>';
		if (hg_mkdir(CACHE_DIR . 'prms/'))
		{
			hg_file_write(CACHE_DIR . 'prms/ag_' . $group_id . '.php', $prms_str);
			return $re_prms;
		}
		else
		{
			exit(CACHE_DIR . 'program/' . $type . '目录创建失败，请检查目录权限.');
		}
	}
}
?>