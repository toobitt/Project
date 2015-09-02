<?php
class developer_auth_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "identity_auth  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['dev_type_text'] = $this->settings['identity_auth_type'][$r['dev_type']];
			$r['type_text'] = $this->settings['identity_auth_type'][$r['type']];
			$r['status_text'] = $this->settings['identity_auth_status'][$r['dev_status']];
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "identity_auth SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."identity_auth SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "identity_auth WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "identity_auth SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($data = array())
	{
		if ($data && is_array($data))
		{
		    $condition = '';
		    $alias = 'ia';
    		foreach ($data as $k => $v)
    		{
    			if (is_numeric($v))
    			{
    				$condition .= ' AND ' . $alias . '.' . $k . ' = ' . $v;
    			}
    			elseif (is_string($v))
    			{
    				$condition .= ' AND ' . $alias . '.' . $k . ' = "' . $v . '"';
    			}
    		}
		}
		$sql = "SELECT ia.*,p.name AS province_name,c.name AS city_name,x.name AS district_name FROM " . DB_PREFIX . "identity_auth ia LEFT JOIN " . DB_PREFIX . "province p 
										ON p.code = ia.province LEFT JOIN " . DB_PREFIX . "city c 
										ON c.code = ia.city LEFT JOIN " . DB_PREFIX . "area x 
										ON x.code = ia.district WHERE 1";
		if ($condition) $sql .= $condition;
		$info = array();
		$info = $this->db->query_first($sql);
		if($info)
		{
			$info['status_text'] = $this->settings['identity_auth_status'][$info['dev_status']];
			$info['type_text'] 	= $this->settings['identity_auth_type'][$info['type']];
			$info['dev_type_text'] 	= $this->settings['identity_auth_type'][$info['dev_type']];			
			$info['identity_type_text'] = $this->settings['identity_type'][$info['identity_type']];
			$info['create_time'] = date('Y-m-d H:i',$info['create_time']);
			if($info['identity_photo'])
			{
				$info['identity_photo'] = @unserialize($info['identity_photo']);
			}
			else 
			{
				$info['identity_photo'] = array();
			}
			
			//获取营销能力
			if($info['is_has_market'])
			{
			    $hasMarket = explode(',', $info['is_has_market']);
			    $_has_market = '';
			    foreach ($hasMarket AS $_item)
			    {
			        $_has_market .= $this->settings['marketing'][$_item] . ',';
			    }
			    $_has_market = rtrim($_has_market,',');
			    $info['has_market'] = $_has_market;
			}
			
			//获取技术要求
		    if($info['tech'])
			{
			    $hasTech = explode(',', $info['tech']);
			    $_has_tech = '';
			    foreach ($hasTech AS $_item)
			    {
			        $_has_tech .= $this->settings['developer_tech'][$_item] . ',';
			    }
			    $_has_tech = rtrim($_has_tech,',');
			    $info['has_tech'] = $_has_tech;
			}
		}
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "identity_auth WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "identity_auth WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "identity_auth WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "identity_auth WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		/**********************************以下状态只是示例，根据情况而定************************************/
		switch (intval($pre_data['status']))
		{
			case 1:$status = 2;break;//审核
			case 2:$status = 3;break;//打回
			case 3:$status = 2;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "identity_auth SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	public function getAllNoAppName()
	{
		$getSql = "select * from " . DB_PREFIX . "identity_auth where app_name = ''";
		$info = array();
		$query = $this->db->query($getSql);
		while($row = $this->db->fetch_array($query))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	public function updateAppNameByDingdoneUserId($app_name = "",$dingdone_user_id = "")
	{
		$updateSql = "update " . DB_PREFIX . "identity_auth set app_name = '" . $app_name . "' where dingdone_user_id = ".$dingdone_user_id;
		$ret = $this->db->query($updateSql);
		return $ret;
	}
	
	public function getAddDevelpoer($start_time = 0,$end_time = 0)
	{
		$sql = "select count(*) as total from ".DB_PREFIX."identity_auth where is_developer_time >".$start_time . " and is_developer_time < ".$end_time." and is_developer = 1";
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function getTodayAddDevelopInfo($time = 0)
	{
		$sql = "select count(*) as total,t as hour from (SELECT id,dingdone_name, FROM_UNIXTIME(develop_create_time,'%H') as t,develop_create_time FROM ".DB_PREFIX."identity_auth where develop_create_time > ".$time." ORDER BY `id` DESC) as new_table  group by new_table.t";
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$info[] = $rows;
		}
		$ret = array();
		if($info && is_array($info))
		{
			foreach ($info as $k => $v)
			{
				$ret[intval($v['hour'])] =  intval($v['total']);
			}
		}
		return $ret;
	}
	
}