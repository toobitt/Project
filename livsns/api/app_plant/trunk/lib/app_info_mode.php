<?php
class app_info_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail($id = '',$cond = '')
	{
	    if(!$id && !$cond)
		{
			return false;
		}
		
		if($id)
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "app_info  WHERE id = '" .$id. "'";
		}
		else if($cond)
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "app_info  WHERE 1 " . $cond;
		}
		$info = $this->db->query_first($sql);
		if($info)
		{
    		if(empty($info['package_name']))
    		{
                $android_package_name = $this->settings['package'] ? $this->settings['package']['android'].$info['id'] : 'com.hoge.android.app'.$info['id'];
    		    $info['ios_package_name'] = $this->settings['package'] ? $this->settings['package']['ios'].$info['id'] : 'com.hoge.ios.app'.$info['id'];
    		    $info['android_package_name'] = $info['android_package_name'] ? $info['android_package_name'] : $android_package_name;
    		}
    		else
    		{
    		    $info['ios_package_name'] = $info['package_name'];
    		    $info['android_package_name'] = $info['android_package_name'] ? $info['android_package_name'] : $info['package_name'];
    		}
		    return $info;
		}
		else 
		{
		    return FALSE;
		}
	}

	public function update($id = '',$data = array())
	{
	    if(!$data || !$id)
		{
			return false;
		}
		
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "app_info WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "app_info SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	//创建栏目与默认栏目的关联关系
	public function createColumnRelate($data = array())
	{
        if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "column_relate SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}
	
	public function deleteDemoColumns($cond = '')
	{
	    if(!$cond)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "column_relate WHERE 1 " . $cond;
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
		$sql = " DELETE FROM " .DB_PREFIX. "column_relate WHERE 1 " . $cond;
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function getColumnRelateData($cond = '')
	{
	    if(!$cond)
	    {
	        return FALSE;
	    }
	    $sql = " SELECT * FROM " .DB_PREFIX. "column_relate WHERE 1 " . $cond;
	    $q = $this->db->query($sql);
	    $ret = array();
	    while ($r = $this->db->fetch_array($q)) 
	    {
	        $ret[] = $r;
	    }
	    
	    if($ret)
	    {
	        return $ret;
	    }
	    else 
	    {
	        return FALSE;
	    }
	}
	
	/**
	 * 得到所有app_mark为空的应用信息，之后遍历更新，增加APP_MARK
	 * @return array $info
	 */
	public function getALLAppInfo()
	{
		$sql = "select id from " . DB_PREFIX . "app_info where app_mark = ''";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	/**
	 * 得到所有app_mark为空的应用信息，之后遍历更新，增加APP_MARK
	 * @return array $info
	 */
	public function getALLAppInfoGuid()
	{
		$sql = "select id from " . DB_PREFIX . "app_info where guid = ''";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
}