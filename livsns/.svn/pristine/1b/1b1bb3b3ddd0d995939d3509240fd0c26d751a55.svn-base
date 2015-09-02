<?php
class app_version_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "app_version  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['is_display_build'])
			{
				$r['version_name'] = $r['major_version_num'] . '.' . $r['minor_version_num'] . '.' . $r['build_num'];
			}
			else 
			{
				$r['version_name'] = $r['major_version_num'] . '.' . $r['minor_version_num'];
			}
			$info[] = $r;
		}
		return $info;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "app_version SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "app_version WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "app_version SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "app_version  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "app_version WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "app_version WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "app_version WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	//获取最新版本
	public function getNewestVersion($cond = '')
	{
		$sql = "SELECT a.*,ac.name,ac.mark FROM " .DB_PREFIX. "app_version a LEFT JOIN " .DB_PREFIX. "app_client ac ON ac.id = a.client_type WHERE 1 " . $cond . " ORDER BY a.create_time DESC ";
		$info = $this->db->query_first($sql);
		if($info)
		{
			if($info['is_display_build'])
			{
				$info['version_name'] = $info['major_version_num'] . '.' . $info['minor_version_num'] . '.' . $info['build_num'];
			}
			else 
			{
				$info['version_name'] = $info['major_version_num'] . '.' . $info['minor_version_num'];
			}	
		}
		return $info;	
	}
	
	//根据队列id获取版本信息
	public function getVersion($cond = '')
	{
		if(!$cond)
		{
			return false;
		}
		
		$sql = "SELECT av.*,ac.mark,ac.name FROM " .DB_PREFIX. "app_version av LEFT JOIN " .DB_PREFIX. "app_client ac ON ac.id = av.client_type WHERE 1 " .$cond;
		$info = $this->db->query_first($sql);
		if($info)
		{
			if($info['is_display_build'])
			{
				$info['version_name'] = $info['major_version_num'] . '.' . $info['minor_version_num'] . '.' . $info['build_num'];
			}
			else 
			{
				$info['version_name'] = $info['major_version_num'] . '.' . $info['minor_version_num'];
			}
		}
		return $info;
	}
	
	//重新打包重置状态
	public function rebuildBag($id = '',$cond = '',$data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		if(!$id && !$cond)
		{
			return false;
		}
		
		if($id)
		{
			$where = " AND id = '" .$id. "' ";
		}
		else 
		{
			$where = $cond;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "app_version SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE 1 " . $where;
		$this->db->query($sql);
		return true;
	}
	
	//根据应用的uuid查询出对应的最新版本
	public function getNewstVersionByUUID($uuid = '',$mark = '',$is_release = 0)
	{
		$cond = '';
		if($mark)
		{
			//查询出对应
			$sql = "SELECT * FROM " .DB_PREFIX. "app_client WHERE mark = '" .$mark. "' ";
			$client = $this->db->query_first($sql);
			if($client)
			{
				$cond .= " AND av.client_type = '" .$client['id']. "' "; 
			}
		}
		
		$cond .= " AND av.is_release = '" . $is_release .  "' ";
		$sql = "SELECT av.* FROM " .DB_PREFIX. "app_version av LEFT JOIN " . DB_PREFIX . "app_info a ON a.id = av.app_id WHERE a.uuid = '" .$uuid. "' " . $cond . " ORDER BY av.create_time DESC ";
		$info = $this->db->query_first($sql);
		if($info)
		{
			if($info['is_display_build'])
			{
				$info['version_name'] = $info['major_version_num'] . '.' . $info['minor_version_num'] . '.' . $info['build_num'];
			}
			else 
			{
				$info['version_name'] = $info['major_version_num'] . '.' . $info['minor_version_num'];
			}
			
			//如果当前版本正在打包或者打包失败则获取当前版本的上一个版本
			if($info['status'] != 1)
			{
				$sql = "SELECT * FROM " .DB_PREFIX. "app_version WHERE app_id = '" .$info['app_id']. "' AND client_type = '" .$info['client_type']. "' AND is_release = '" . $is_release . "' AND id != '" . $info['id'] . "' ORDER BY create_time DESC ";
				$last_version = $this->db->query_first($sql);
				if($last_version)
				{
					if($last_version['is_display_build'])
					{
						$last_version['version_name'] = $last_version['major_version_num'] . '.' . $last_version['minor_version_num'] . '.' . $last_version['build_num'];
					}
					else 
					{
						$last_version['version_name'] = $last_version['major_version_num'] . '.' . $last_version['minor_version_num'];
					}
					$info['history'] = $last_version;
				}
			}
			
			if($client)
			{
				$info['name'] = $client['name'];
				$info['mark'] = $client['mark'];
			}
		}
		return $info;
	}
	
	//获取版本信息
	public function getVersionInfo($cond = '')
	{
		$sql = "SELECT av.*,ac.name,ac.mark FROM " . DB_PREFIX . "app_version av LEFT JOIN " .DB_PREFIX. "app_client ac ON ac.id = av.client_type WHERE 1 " . $cond;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['is_display_build'])
			{
				$r['version_name'] = $r['major_version_num'] . '.' . $r['minor_version_num'] . '.' . $r['build_num'];
			}
			else 
			{
				$r['version_name'] = $r['major_version_num'] . '.' . $r['minor_version_num'];
			}
			$info[] = $r;
		}
		return $info;
	}
	
	//根据user_id获取应用信息包括其对应的最新的版本
	public function getAppByUserId($user_id = '')
	{
		if(!$user_id)
		{
			return false;
		}
		
		$ret = array();
		//查询出该用户对应的应用
		$sql = "SELECT * FROM " .DB_PREFIX. "app_info WHERE user_id = '" .$user_id. "' ";
		$app = $this->db->query_first($sql);
		if(!$app)
		{
			return false;
		}
		
		if($app['icon'])
		{
			$app['icon'] = @unserialize($app['icon']);
		}
		$ret['app'] = $app;
		//获取所有客户端类型
		$sql = "SELECT * FROM " .DB_PREFIX. "app_client";
		$q = $this->db->query($sql);
		$client = array();
		while ($r = $this->db->fetch_array($q))
		{
			$client[] = $r;
		}

		//获取版本
		foreach ($client AS $k => $v)
		{
			$arr = array();
			$sql = "SELECT * FROM " .DB_PREFIX. "app_version WHERE app_id = '" .$app['id']. "' AND client_type = '" .$v['id']. "' ORDER BY create_time DESC ";
			$arr = $this->db->query_first($sql);
			if($arr)
			{
				if($arr['is_display_build'])
				{
					$arr['version_name'] = $arr['major_version_num'] . '.' . $arr['minor_version_num'] . '.' . $arr['build_num'];
				}
				else 
				{
					$arr['version_name'] = $arr['major_version_num'] . '.' . $arr['minor_version_num'];
				}
				$arr['status_text'] = $this->settings['unpack'][$arr['status']];
				$arr['mark'] = $v['mark'];
				$arr['name'] = $v['mark'];
				$ret['version'][$v['mark']] = $arr;
			}
		}
		return $ret;
	}
	

	public function getPackageNums($app_id = 0)
	{
		$release_sql = "SELECT app_id, client_type,is_release,count(*) as total FROM `liv_app_version` where app_id = ".$app_id." and is_release = 1 group by client_type";
		$debug_sql ="SELECT app_id, client_type,is_release,count(*) as total FROM `liv_app_version` where app_id = ".$app_id." and is_release = 0 group by client_type";
		$release_info = $this->db->fetch_all($release_sql);
		$debug_info = $this->db->fetch_all($debug_sql);
		$info['release'] = $release_info;
		$info['debug'] = $debug_info;
		return $info;
	}
}
?>