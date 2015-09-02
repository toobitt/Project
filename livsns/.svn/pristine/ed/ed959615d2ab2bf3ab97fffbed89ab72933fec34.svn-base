<?php
function hg_build_sql($table_name = '', $data = array())
{
	if(!$table_name)
	{
		return false;
	}
	
	if(!$data[0]) return false;
	
	$fields = array_keys($data[0]);
	if(!$fields)
	{
		return false;
	}
	$sql = 'INSERT INTO '.DB_PREFIX.$table_name.'('.implode(',', $fields).') VALUES ';
	foreach($data as $k=>$v)
	{
		if(is_array($v))
		{
			$arr = array();
			foreach($v AS $kk => $vv)
			{
				$arr[] = urldecode($vv);
			}
			$sql .= '("'.implode('","', $arr).'"),';
		}
	}
	return $sql = rtrim($sql, ',');
}
function hg_check_prms($user = array())
{
	$complex = array();
	if(!$user['slave_group'])
	{
		return $complex;
	}
	$user_role_ids = explode(',', $user['slave_group']);
	foreach ($user_role_ids as $role_id)
	{
        if ($ret = read_role_prms_form_redis($role_id)) {
            $complex[$role_id] = json_decode($ret, 1);
        } else {
            $role_prms_file = get_prms_cache_dir($role_id);
            if(!file_exists($role_prms_file))
            {
                break;
            }
            $complex[$role_id] = include($role_prms_file);
        }
	}
	if((count($complex) == count($user_role_ids)) && !DEBUG_MODE)
	{
		return $complex;
	}
	return hg_update_role_prms($user['slave_group']);
}

// 从 redis 获取 role_prms
function read_role_prms_form_redis($key)
{
    // 为了防止 redis 中 key 重复，这里定义一个前缀（同 set）
    $key = 'role_prms_cache' . $key;
    global $gGlobalConfig;
    if (isset($gGlobalConfig['use_redis']) and $gGlobalConfig['use_redis']) {
        $redis       = new Redis();
        $conn_status = $redis->pconnect($gGlobalConfig['redis_read']['host'], $gGlobalConfig['redis_read']['port'], $gGlobalConfig['redis_read']['timeout']);

        if ($conn_status) {
            return $redis->get($key);
        }
    }

    return FALSE;
}

// 将 role_prms 写入到 redis
function write_role_prms_to_redis($key, $value, $expire = 600)
{
    // 为了防止 redis 中 key 重复，这里定义一个前缀（同 get）
    $key = 'role_prms_cache' . $key;
    global $gGlobalConfig;
    if (isset($gGlobalConfig['use_redis']) and $gGlobalConfig['use_redis']) {
        $redis       = new Redis();
        $conn_status = $redis->pconnect($gGlobalConfig['redis_write']['host'], $gGlobalConfig['redis_write']['port'], $gGlobalConfig['redis_write']['timeout']);
        if ($conn_status) {
            return $redis->set($key, $value, $expire);
        }
    }

    return FALSE;
}

function hg_update_role_prms($role_ids = '')
{
	$complex = array();
	global $gDB;
	//获取节点和发布栏目的权限
	$sql = 'SELECT * FROM '.DB_PREFIX.'role_prms  WHERE admin_role_id IN(' . $role_ids . ')';
	$query = $gDB->query($sql);
	while($row = $gDB->fetch_array($query))
	{
		$temp = array();
		$temp['action']      = trim($row['func_prms']) ? explode(',', $row['func_prms']) : array();
		$temp['nodes'] 		 = strlen($row['node_prms']) ? explode(',', $row['node_prms']) : array();
		$temp['setting']	 = $row['setting_prms'];
		$temp['is_complete'] = $row['is_complete'];
		$complex[$row['admin_role_id']]['app_prms'][$row['app_uniqueid']] = $temp;
	}
	//栏目节点
	$sql = 'SELECT publish_prms,extend_prms,id,site_prms FROM '.DB_PREFIX.'admin_role WHERE id IN('.$role_ids.')';
	$query = $gDB->query($sql);
	while($publish_prms = $gDB->fetch_array($query))
	{
		$complex[$publish_prms['id']]['site_prms'] = $publish_prms['site_prms'] ? explode(',',$publish_prms['site_prms']) : array();
		$complex[$publish_prms['id']]['publish_prms'] = $publish_prms['publish_prms'] ? explode(',',$publish_prms['publish_prms']) : array();
		$complex[$publish_prms['id']]['default_setting'] = $publish_prms['extend_prms'] ? unserialize($publish_prms['extend_prms']) : array();
	}
	if($complex)
	{
		foreach ($complex as $role_id=>$prms)
		{
            if ( ! write_role_prms_to_redis($role_id, json_encode($prms))) {
                $cache_dir = get_prms_cache_dir();
                if(!is_dir($cache_dir))
                {
                    hg_mkdir($cache_dir);
                }
                $role_prms_file = get_prms_cache_dir($role_id);
                //$prms = hg_merger_show_node($prms);
                $content = '<?php
/*
权限测试注意事项
1、授权和测试在同一服务器上 因为读取是缓存文件
2、确定自己应用的主模块的标识符号
3、辅助模块 即除了主模块以外的 都是通过settings这个选项判定是否具有增删改查的权限
4、app_prms 存储的是应用标志 主要用于主模块的操作和节点控制
5、site_prms 全站栏目授权
6、publish_prms 控制发布权限 有栏目即即有发布操作权限
7、default_setting 控制其他选项 同之前版本
*/
return '.var_export($prms,1).';?>';
                hg_file_write($role_prms_file, $content);
            }
		}
	}
	return $complex;
}
/*
function hg_merger_show_node($perms = array())
{
	if(!$perms || !is_array($perms))
	{
		return $perms;
	}
	foreach ($perms as $k=>$v)
	{
		if(in_array($k, array('site_prms','default_setting','publish_prms')))
		{
			continue;
		}
		if(!is_array($v) || !$v)
		{
			continue;
		}
		$node = array();
		foreach ($v as $op=>$vv)
		{
			if(!in_array($op, array('update', 'delete', 'audit', 'create','show')))
			{
				continue;
			}
			if(!is_array($vv['node']) || !$vv['node'])
			{
				continue;
			}
			foreach ($vv['node'] as $nvar=>$nid)
			{
				$node[$nvar]  = $node[$nvar] ? $node[$nvar] : array();
				if($nid)
				{
					$node[$nvar] = array_merge($node[$nvar], (array)$nid);
				}
			}
		}
		if($node)
		{
			foreach ($node as $nvar=>$nodeinfo)
			{
				if(!$perms[$k]['show']['func'])
				{
					$perms[$k]['show']['func'] = 1;
					$perms[$k]['detail']['func'] = 1;
					$perms[$k]['show']['node'][$nvar] = array_unique($nodeinfo);
				}
				else
				{
					if($perms[$k]['show']['node'][$nvar])
					{
						$perms[$k]['show']['node'][$nvar] = array_unique($nodeinfo);
					}
				}
			}
		}
	}
	return $perms;
}
*/
function get_prms_cache_dir($role_id = 0)
{
	$cache_dir = CUR_CONF_PATH . 'cache/prms/';
	if(!$role_id)
	{
		return $cache_dir;
	}
	return $cache_dir.$role_id.'.php';
}
function hg_rmove_cache_file($role_id = 0)
{
	if($role_id)
	{
		$role_file = get_prms_cache_dir($role_id);
		if(!is_file($role_file))
		{
			return;
		}
		//$handle = opendir($dir);
		//while($file = readdir($handle))
		//{
		//	$filename = $dir . $file;
		//	unlink($filename); 
		//}
		unlink($role_file);
	}
}
function merge_user_prms($prms = array())
{
	$merge_prms = array();
	if(!$prms)
	{
		return $merge_prms;
	}
	if(count($prms) == 1)
	{
		list($role_id, $merge_prms) = each($prms);
	}
	else 
	{
		foreach ($prms as $role_id=>$role_prms)
		{
			foreach ($role_prms as $k=>$v)
			{
				//角色默认设置合并
				if($k == 'default_setting')
				{
					foreach ($v as $setting_name=>$setting_value)
					{
						if($setting_name == 'set_weight_limit')
						{
							if(!$setting_value)
							{
								$merge_prms[$k][$setting_name] = -1;
							}
							if($merge_prms[$k][$setting_name]!=-1 && ($setting_value >= $merge_prms[$k][$setting_name]))
							{
								$merge_prms[$k][$setting_name] = $setting_value;
							}
						}
						else
						{
							if($setting_value >= $merge_prms[$k][$setting_name])
							{
								$merge_prms[$k][$setting_name] = $setting_value;
							}
						}
					}
				}
				//发布栏目合并
				elseif($k == 'publish_prms' || $k == 'site_prms')
				{
					$merge_prms[$k] = array_unique(array_merge((array)$merge_prms[$k], $v));
				}
				//模块角色合并
				elseif($k == 'app_prms')
				{
					if(!is_array($v) || !$v)
					{
						continue;
					}
					foreach ($v as $app_unqiqueid=>$priviliege)
					{
						if($priviliege['setting'])
						{
							$merge_prms[$k][$app_unqiqueid]['setting'] =  $priviliege['setting'];
						}
						if($priviliege['is_complete'])
						{
							$merge_prms[$k][$app_unqiqueid]['is_complete'] =  $priviliege['is_complete'];
						}
						$merge_prms[$k][$app_unqiqueid]['action'] = array_unique(array_merge((array)$merge_prms[$k][$app_unqiqueid]['action'],(array)$priviliege['action']));
						$merge_prms[$k][$app_unqiqueid]['nodes'] = array_unique(array_merge((array)$merge_prms[$k][$app_unqiqueid]['nodes'],(array)$priviliege['nodes']));
					}
				}
			}
		}
	}
	if($merge_prms['publish_prms'] || $merge_prms['site_prms'])
	{
		foreach($merge_prms['app_prms'] as $k=>$v)
		{
			$merge_prms['app_prms'][$k]['action'][] = 'publish';
		}
	}
	if($merge_prms['default_setting']['set_weight_limit'] == -1)
	{
		$merge_prms['default_setting']['set_weight_limit'] = 0;
	}
	return $merge_prms;
}

function hg_load_login_serv()
{
	@include(CACHE_DIR . 'loginserv.php');
	return $servers;
}
//配合array_filter使用,清空所有数组空value值
function clean_array_null($v)
{
	$v=trimall($v);
	if(!empty($v))return true;
	return false;
}
//配合array_filter使用,清空所有数组非数字值
function clean_array_num($v)
{
	if(is_numeric($v))return true;
	return false;
}
//配合array_filter使用,清空所有数组非数字值
function clean_array_num_max0($v)
{
	if(is_numeric($v)&&$v>0)return true;
	return false;
}
?>