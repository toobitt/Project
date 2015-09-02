<?php
class components_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '',$group_key = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "components  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
		    if($r['img_info'] && unserialize($r['img_info']))
			{
				$r['img_info'] = unserialize($r['img_info']);
			}
			else 
			{
				$r['img_info'] = array();
			}
			
			if($group_key)
			{
			    $info[$r[$group_key]] = $r;
			}
			else 
			{
			    $info[] = $r;   
			}
		}
		return $info;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "components SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."components SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "components WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "components SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($id = '',$cond = '')
	{
	    if(!$id && !$cond)
		{
			return FALSE;
		}
		
	    if($id)
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "components  WHERE id = '" .$id. "'";
		}
		else
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "components  WHERE 1 " . $cond;
		}
		
		$info = $this->db->query_first($sql);
		if($info)
		{
		    if($info['img_info'] && unserialize($info['img_info']))
			{
				$info['img_info'] = unserialize($info['img_info']);
			}
			else 
			{
				$info['img_info'] = array();
			}
		    return $info;
		}
		else
		{
		    return FALSE;
		}
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "components WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "components WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "components WHERE id IN (" . $id . ")";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "components WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "components SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	//取得某个数据源的详情
	public function detailCompSource($id = '',$cond = '')
	{
	    if(!$id && !$cond)
		{
			return FALSE;
		}
		
	    if($id)
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "components_source  WHERE id = '" .$id. "'";
		}
		else
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "components_source  WHERE 1 " . $cond;
		}
		
		$info = $this->db->query_first($sql);
		if($info)
		{
		    return $info;
		}
		else
		{
		    return FALSE;
		}
	}
	
	//创建数据源
    public function createDataSource($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "components_source SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."components_source SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	//更新数据源
	public function updateDataSource($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "components_source WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "components_source SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	//创建针对组件的扩展字段
	public function createCompExtendFieldStyle($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "components_expend_field SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}
	
	//获取组件的扩展字段
    public function getCompExtendField($comp_id = '',$cond = '')
	{
	    if(!$comp_id)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT * FROM " .DB_PREFIX. "components_expend_field WHERE comp_id = '" .$comp_id. "' " .$cond. " ORDER BY position ASC ";
	    $q = $this->db->query($sql);
	    $ret = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        $ret[] = $r;
	    }
	    return $ret;
	}
	
	//删除针对组件的扩展字段
    public function deleteCompExtendFieldStyle($comp_id = '')
	{
	    if(!$comp_id)
	    {
	        return FALSE;
	    } 

		$sql = " DELETE FROM " .DB_PREFIX. "components_expend_field WHERE comp_id = '" .$comp_id. "' ";
		$this->db->query($sql);
		return TRUE;
	}
	
	//设置组件角标的值
	public function setCompCornerStyle($data = array())
	{
	    if(!$data['comp_id'])
	    {
	        return FALSE;
	    }
	    $comp_id = $data['comp_id'];
	    
	    //查询该模块有没有存在值
	    $_sql = "SELECT * FROM " .DB_PREFIX. "components_corner WHERE comp_id = '" .$comp_id. "'";
	    $pre_data = $this->db->query_first($_sql);
	    if($pre_data)
		{
		    unset($data['comp_id']);
		    $sql = " UPDATE " . DB_PREFIX . "components_corner SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql  = trim($sql,',');
    		$sql .= " WHERE comp_id = '"  .$comp_id. "'";
    		$this->db->query($sql);
		}
		else 
		{
		    $sql = " INSERT INTO " . DB_PREFIX . "components_corner SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql = trim($sql,',');
    		$this->db->query($sql);
		}
		return TRUE;
	}
	
	//获取组件的角标数据
    public function getCompCornerData($comp_id = '')
	{
	    if(!$comp_id)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT * FROM " .DB_PREFIX. "components_corner WHERE comp_id = '" .$comp_id. "'";
	    $ret = $this->db->query_first($sql);
	    return $ret;
	}
	
	//获取组件以及组件绑定的数据源
	public function getCompWithSource($condition = '',$orderby = '',$limit = '',$group_id = FALSE)
	{
	    $sql = "SELECT c.*,cs.column_id,cs.nums,cs.start_weight,cs.end_weight FROM " . DB_PREFIX . "components c LEFT JOIN " .DB_PREFIX. "components_source cs ON c.source_id = cs.id   WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
		    if($r['img_info'] && unserialize($r['img_info']))
			{
				$r['img_info'] = unserialize($r['img_info']);
			}
			else 
			{
				$r['img_info'] = array();
			}
			
			if($group_id)
			{
			    $info[$r['id']] = $r;
			}
			else 
			{
			    $info[] = $r;
			}
		}
		return $info;
	}
	
	//根据条件删除后台组件listUI值
	public function deleteCompListValue($cond = '')
	{
	    if(!$cond)
	    {
	        return FALSE;
	    }
	    
	    $sql = "DELETE FROM " . DB_PREFIX . "components_list_value WHERE 1 " . $cond;
	    $this->db->query($sql);
	    return TRUE;
	}
	
	//根据条件删除前台组件listUI值
	public function deleteCompUiListValue($cond = '')
	{
	    if(!$cond)
	    {
	        return FALSE;
	    }
	    
	    $sql = "DELETE FROM " . DB_PREFIX . "components_ui_list_value WHERE 1 " . $cond;
	    $this->db->query($sql);
	    return TRUE;
	}
	
	//获取某个模块绑定组件Id
	public function getCompIdsByCond($cond = '',$group_key = '')
	{
	    if(!$cond)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT * FROM " .DB_PREFIX. "module_comp WHERE 1 " . $cond;
	    $ret = $this->db->fetch_all($sql,$group_key);
	    return $ret;
	}
	
	//创建一条模块与组件的关系表
	public function createModuleComp($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "module_comp SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."module_comp SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	//删除模块与组件的关系数据
	public function deleteModuleCompByModuleId($module_id = '')
	{
	    if(!$module_id)
	    {
	        return FALSE;
	    }
	    
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "module_comp WHERE module_id IN (" . $module_id . ")";
		$this->db->query($sql);
		return TRUE;
	}
	
	//删除模块与组件的关系数据
	public function deleteModuleCompByCond($cond = '')
	{
	    if(!$cond)
	    {
	        return FALSE;
	    }
	    
	    $sql = " DELETE FROM " .DB_PREFIX. "module_comp WHERE 1 " . $cond;
		$this->db->query($sql);
		return TRUE;
	}
}