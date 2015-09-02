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
	
	/******************************组件新扩展字段**************************************************/
	
	public function getALLLineInfoByCompId($user_id = 0 , $comp_id = 0)
	{
		//获取up位置
		$up_sql = "select * from ".DB_PREFIX."comp_extend_line where user_id = ".$user_id." and comp_id = ". $comp_id . " and line_position = 1 order by order_id asc";
		$up_q = $this->db->query($up_sql);
		//获取down位置
		$down_sql = "select * from ".DB_PREFIX."comp_extend_line where user_id = ".$user_id." and comp_id = ". $comp_id . " and line_position = 2 order by order_id desc";
		$down_q = $this->db->query($down_sql);
		$ret = array();
		while ($r = $this->db->fetch_array($up_q))
		{
			$ret['up'][] = $r;
		}
		while ($re = $this->db->fetch_array($down_q))
		{
			$ret['down'][] = $re;
		}
		return $ret;
	}
	
	
	
	
	/**
	 * 获取行的前端属性
	 * @param number $line_id
	 * @param number $comp_id
	 * @param number $role_id
	 * @param number $ui_id
	 */
	public function getFrontExtendAttributeData($line_id = 0 , $comp_id = 0 , $role_id = 1 ,$ui_id = 0)
	{
		//如果line_id存在 取出这个扩展行当前保存的属性 
		if($line_id)
		{
			$sql = "select * from " . DB_PREFIX . "comp_extend_line_ui_attr_value where line_id =" . $line_id . " and comp_id = " . $comp_id;
		}
		$attr_value_arr = array();
		
		if($sql)
		{
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$attr_value_arr[$r['ui_attr_id']] = $r['attr_value'];
			}
		}
		
		//增加角色的筛选
		$_cond = '';
		if($role_id && in_array($role_id, array(-1,1,2)))
		{
			$_cond = " AND ua.role_type_id = '" .$role_id. "' ";
		}
		else
		{
			$_cond = " AND ua.role_type_id = -1 ";//如果传入的角色id不合法就默认就取出适合所有
		}
		
		$_cond .= " AND ua.is_display = 1 ";
		 
		$order_by = " ORDER BY uag.order_id ASC,ua.order_id DESC ";

		//获取属性
		$sql = "SELECT ua.* FROM " . DB_PREFIX . "ui_attribute ua LEFT JOIN " .DB_PREFIX. "ui_attribute_group uag ON ua.group_id = uag.id WHERE ua.ui_id = '" .$ui_id. "' " . $_cond . $order_by;
		$q = $this->db->query($sql);
		$attrArr = array();
		while ($r = $this->db->fetch_array($q))
		{
			//获取样式
			if($r['style_value'] && unserialize($r['style_value']))
			{
				$r['style_value'] = unserialize($r['style_value']);
			}
			 
			//获取默认值
			if(isset($attr_value_arr[$r['id']]))
			{
				$r['default_value'] = $attr_value_arr[$r['id']];
			}
		
			if($r['default_value'] && unserialize($r['default_value']))
			{
				$r['default_value'] = unserialize($r['default_value']);
			}
			 
			$r['attr_type_uniqueid'] = $this->settings['attribute_type'][$r['attr_type_id']]['uniqueid'];
		
			if($r['default_value'])
			{
				
			}
			 
			$r['attr_style_value']   = $r['style_value'];
			$r['attr_default_value'] = $r['default_value'];
			unset($r['style_value'],$r['default_value']);
			$attrArr[] = $r;
		}
		return $attrArr;
	}
	
	/**
	 * 设置前端属性
	 */
	public function setNewExtendLineUiValue($data = array())
	{
		if(!$data)
		{
			return FALSE;
		}
		//先判断对应信息是否存在
		$sql = "select * from " . DB_PREFIX . "comp_extend_line_ui_attr_value where line_id = ".$data['line_id'] . " and comp_id = " . $data['comp_id'] . " and user_id = ".$data['user_id'] . " and ui_attr_id =  " . $data['ui_attr_id'];
		$dele_data = $this->db->query_first($sql);
		if($dele_data)
		{
			//如果存在就先删除
			$delete_sql = "delete from " . DB_PREFIX . "comp_extend_line_ui_attr_value where line_id = " . $data['line_id'] . " and comp_id = " . $data['comp_id'];
			$this->db->query($delete_sql);
		}
		//插入
		$insert_sql = "insert into " . DB_PREFIX . "comp_extend_line_ui_attr_value set ";
		foreach ($data as $k => $v)
		{
			$insert_sql .= " {$k} = '{$v}',";
		}
		$insert_sql = trim($insert_sql,',');
		$this->db->query($insert_sql);
	}
	
	
	
	
	
	/**
	 * 
	 * 对扩展行前台属性关联的后台属性统一设置值
	 * @param unknown $ui_attr_id 前端属性的id
	 * @param unknown $value 属性值
	 * @param unknown $line_id 行的id
	 */
	public function setNewExtendAttrSameToRelate($ui_attr_id = 0 , $value = '' , $line_id = 0 , $set_type = 'create')
	{
		if(!$ui_attr_id || !$line_id)
		{
			return FALSE;
		}
			
		//首先查询出关联关系
		$sql = "SELECT * FROM " .DB_PREFIX. "ui_attribute_relate WHERE ui_attr_id = '" .$ui_attr_id. "' ";
		$q = $this->db->query($sql);
		$relate_ids = array();
		while ($r = $this->db->fetch_array($q))
		{
			$relate_ids[] = $r['relate_id'];
		}
		if($relate_ids)
		{
			foreach ($relate_ids AS $_id)
			{
				if($set_type == 'create')
				{
					$this->setExtendLineAttrValue(array(
							'line_id'    	   => $line_id,
							'relate_id'        => $_id,
							'attr_value'       => $value,
					));
				}
				elseif ($set_type == 'update')
				{
					$this->editExtendLineAttrValue(array(
							'line_id'    	   => $line_id,
							'relate_id'        => $_id,
							'attr_value'       => $value,
					));
				}
			}
		}
	}
	
	
	/**
	 * （新建扩展行信息）
	 * 设置扩展行的后端属性
	 * @param unknown $data
	 */
	private function setExtendLineAttrValue($data = array())
	{
		if(!$data)
		{
			return false;
		}
	
		//判断该属性的值是否存在
		$sql = "SELECT * FROM " .DB_PREFIX. "comp_extend_line_attr_value WHERE line_id = '" .$data['line_id']. "' AND relate_id = '" .$data['relate_id'] . "'";
		$pre_data = $this->db->query_first($sql);
		if($pre_data)
		{
			$delete_sql = "delete from " . DB_PREFIX . "comp_extend_line_attr_value where line_id = ".$data['line_id'] ." and relate_id = ".$data['relate_id'];
			$this->db->query($delete_sql);
		}
		$insert_sql = " INSERT INTO " . DB_PREFIX . "comp_extend_line_attr_value SET ";
		foreach ($data AS $k => $v)
		{
			$insert_sql .= " {$k} = '{$v}',";
		}
		$insert_sql = trim($insert_sql,',');
		$this->db->query($insert_sql);
	}
	
	/**
	 * （编辑扩展行的情况下）
	 * 设置扩展行的后端属性
	 * @param unknown $data
	 */
	private function editExtendLineAttrValue($data = array())
	{
		if(!$data)
		{
			return FALSE;
		}
		$update_sql = "update " . DB_PREFIX . "comp_extend_line_attr_value set attr_value = ".$data['attr_value'] . " where 1";
		unset($data['attr_value']);
		foreach ($data as $key => $val)
		{
			if (is_numeric($val))
			{
				$update_sql .= ' AND ' . $key . ' = ' . $val;
			}
			elseif (is_string($val))
			{
				$update_sql .= ' AND ' . $key . ' in (' . $val . ')';
			}
		}
		return $this->db->query($update_sql);
	}
	
	public function getAllFieldsInModule($comp_id = 0 , $user_id = 0)
	{
		$left_sql = "select * from ".DB_PREFIX."comp_extend_field where field_position = 1 and comp_id = ".$comp_id." order by order_id asc";
		$left_q = $this->db->query($left_sql);
		$right_sql = "select * from ".DB_PREFIX."comp_extend_field where field_position = 2 and comp_id = ".$comp_id." order by order_id desc";
		$right_q = $this->db->query($right_sql);
		$ret = array();
		while($r = $this->db->fetch_array($left_q))
		{
			$ret[] = $r;
		}
		while($re = $this->db->fetch_array($right_q))
		{
			$ret[] = $re;
		}
		return $ret;
	}
	
	/**
	 * 页面中显示当前行中所有的扩展单元
	 */
	public function getAllFieldsInLine($comp_id = 0 , $line_id = 0 , $user_id = 0)
	{
		$left_sql = "select * from ".DB_PREFIX."comp_extend_field where field_position = 1 and comp_id = ".$comp_id." and line_id = ".$line_id." order by order_id asc";
		$left_q = $this->db->query($left_sql);
		$right_sql = "select * from ".DB_PREFIX."comp_extend_field where field_position = 2 and comp_id = ".$comp_id." and line_id = ".$line_id." order by order_id desc";
		$right_q = $this->db->query($right_sql);
		$ret = array();
		while($r = $this->db->fetch_array($left_q))
		{
			$ret['left'][] = $r;
		}
		while($re = $this->db->fetch_array($right_q))
		{
			$ret['right'][] = $re;
		}
		return $ret;
	}
	
	/**
	 *获取扩展单元前端属性
	 * @param number $field_id
	 * @param number $line_id
	 * @param number $module_id
	 * @param number $role_id
	 * @param number $ui_id
	 * @return multitype:unknown
	 */
	public function getFrontExtendFieldAttributeData($field_id = 0 , $line_id = 0 , $comp_id = 0 , $role_id = 1 ,$ui_id = 0)
	{
		//如果$field_id存在 取出这个扩展单元当前保存的前端属性
		if($field_id)
		{
			$sql = "select * from " . DB_PREFIX . "comp_extend_field_ui_attr_value where line_id =" . $line_id . " and comp_id = " . $comp_id . " and field_id = " . $field_id;
		}
		$attr_value_arr = array();
	
		if($sql)
		{
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$attr_value_arr[$r['ui_attr_id']] = $r['attr_value'];
			}
		}
	
		//增加角色的筛选
		$_cond = '';
		if($role_id && in_array($role_id, array(-1,1,2)))
		{
			$_cond = " AND ua.role_type_id = '" .$role_id. "' ";
		}
		else
		{
			$_cond = " AND ua.role_type_id = -1 ";//如果传入的角色id不合法就默认就取出适合所有
		}
	
		$_cond .= " AND ua.is_display = 1 ";
			
		$order_by = " ORDER BY uag.order_id ASC,ua.order_id DESC ";
	
		//获取属性
		$sql = "SELECT ua.* FROM " . DB_PREFIX . "ui_attribute ua LEFT JOIN " .DB_PREFIX. "ui_attribute_group uag ON ua.group_id = uag.id WHERE ua.ui_id = '" .$ui_id. "' " . $_cond . $order_by;
		$q = $this->db->query($sql);
		$attrArr = array();
		while ($r = $this->db->fetch_array($q))
		{
			//获取样式
			if($r['style_value'] && unserialize($r['style_value']))
			{
				$r['style_value'] = unserialize($r['style_value']);
			}
	
			//获取默认值
			if(isset($attr_value_arr[$r['id']]))
			{
				$r['default_value'] = $attr_value_arr[$r['id']];
			}
	
			if($r['default_value'] && unserialize($r['default_value']))
			{
				$r['default_value'] = unserialize($r['default_value']);
			}
	
			$r['attr_type_uniqueid'] = $this->settings['attribute_type'][$r['attr_type_id']]['uniqueid'];
	
			if($r['default_value'])
			{
	
			}
	
			$r['attr_style_value']   = $r['style_value'];
			$r['attr_default_value'] = $r['default_value'];
			unset($r['style_value'],$r['default_value']);
			$attrArr[] = $r;
		}
		return $attrArr;
	}
	
	/**
	 * 设置扩展单元的前端属性
	 * @param unknown $data
	 */
	public function setNewExtendFieldUiAttrValue($data = array())
	{
		if(!$data)
		{
			return FALSE;
		}
		//先判断对应信息是否存在
		$sql = "select * from " . DB_PREFIX . "comp_extend_field_ui_attr_value where  comp_id = " . $data['comp_id'] . " and user_id = ".$data['user_id'] . " and ui_attr_id =  " . $data['ui_attr_id'] . " and field_id = ".$data['field_id'];
		$dele_data = $this->db->query_first($sql);
		if($dele_data)
		{
			//如果存在就先删除
			$delete_sql = "delete from " . DB_PREFIX . "comp_extend_field_ui_attr_value where comp_id = " . $data['comp_id'] . " and field_id = " . $data['field_id'];
			$this->db->query($delete_sql);
		}
		//插入
		$insert_sql = "insert into " . DB_PREFIX . "comp_extend_field_ui_attr_value set ";
		foreach ($data as $k => $v)
		{
			$insert_sql .= " {$k} = '{$v}',";
		}
		$insert_sql = trim($insert_sql,',');
		$this->db->query($insert_sql);
	}
	
	
	/**
	 * 对扩展行单元属性关联的后台属性统一设置值
	 */
	public function setNewExtendFieldAttrSameToRelate($ui_attr_id = 0 , $value = '' , $field_id = 0 , $line_id = 0 , $set_type = 'create')
	{
		if(!$ui_attr_id || !$line_id || !$field_id)
		{
			return FALSE;
		}
			
		//首先查询出关联关系
		$sql = "SELECT * FROM " .DB_PREFIX. "ui_attribute_relate WHERE ui_attr_id = '" .$ui_attr_id. "' ";
		$q = $this->db->query($sql);
		$relate_ids = array();
		while ($r = $this->db->fetch_array($q))
		{
			$relate_ids[] = $r['relate_id'];
		}
		if($relate_ids)
		{
			foreach ($relate_ids AS $_id)
			{
				if($set_type == 'create')
				{
					$this->setExtendFieldAttrValue(array(
							'line_id'    	   => $line_id,
							'relate_id'        => $_id,
							'attr_value'       => $value,
							'field_id'		   => $field_id,
					));
				}
				elseif ($set_type == 'update')
				{
					$this->editExtendFieldAttrValue(array(
							'line_id'    	   => $line_id,
							'relate_id'        => $_id,
							'attr_value'       => $value,
							'field_id'		   => $field_id,
					));
				}
			}
		}
	}
	
	
	/**
	 * （新建）
	 * 设置扩展单元的后端属性
	 */
	private function setExtendFieldAttrValue($data = array())
	{
		if(!$data)
		{
			return false;
		}
		//panduan
		$is_sql = "select * from ".DB_PREFIX."comp_extend_field_attr_value where field_id = ".$data['field_id'] . " and relate_id = ".$data['relate_id'];
		$is_data = $this->db->query_first($is_sql);
		if($is_data)
		{
			$delete_sql = "delete from ".DB_PREFIX."comp_extend_field_attr_value where field_id = ".$data['field_id'] . " and relate_id = ".$data['relate_id'];
			$this->db->query($delete_sql);
		}
	
		$insert_sql = " INSERT INTO " . DB_PREFIX . "comp_extend_field_attr_value SET ";
		foreach ($data AS $k => $v)
		{
			$insert_sql .= " {$k} = '{$v}',";
		}
		$insert_sql = trim($insert_sql,',');
		$this->db->query($insert_sql);
	}
	
	/**
	 * (编辑)
	 * 设置扩展单元的后端属性
	 */
	public function editExtendFieldAttrValue($data = array())
	{
		if(!$data)
		{
			return FALSE;
		}
		$update_sql = "update " . DB_PREFIX . "comp_extend_field_attr_value set attr_value = '".$data['attr_value'] . "' where 1";
		unset($data['attr_value']);
		foreach ($data as $key => $val)
		{
			if (is_numeric($val))
			{
				$update_sql .= ' AND ' . $key . ' = ' . $val;
			}
			elseif (is_string($val))
			{
				$update_sql .= ' AND ' . $key . ' in (' . $val . ')';
			}
		}
		return $this->db->query($update_sql);
	}
	
	/**
	 * 获取所有的扩展单元的信息 按照一定的顺序
	 * （排序处理数据用）
	 */
	public function getAllFields($comp_id = 0 , $line_id = 0 , $order = 'asc')
	{
		$sql = "select * from ".DB_PREFIX."comp_extend_field where comp_id = ".$comp_id. " and line_id = ".$line_id. " order by order_id ".$order;
		$q = $this->db->query($sql);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		return $ret;
	}
	
	
	public function getAllLines($user_id = 0 , $comp_id = 0 , $order = 'asc')
	{
		$sql = "select * from ".DB_PREFIX."comp_extend_line where user_id = ".$user_id." and comp_id = ".$comp_id." order by order_id ".$order;
		$q = $this->db->query($sql);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		return $ret;
	}
	
	/**
	 * 获取指定位置的最
	 */
	public function getOne($comp_id = 0 , $line_id , $order = 'asc' , $position = 1)
	{
		$sql = $sql = "select * from ".DB_PREFIX."comp_extend_field where comp_id = ".$comp_id. " and line_id = ".$line_id. " and field_position = ".$position." order by order_id ".$order;
		$q = $this->db->query($sql);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		return $ret;
	}
	
	/******************************组件新扩展字段end**************************************************/
	
	
	
	/*********************************打包配置获取属性***************************************************/
	/**
	 * 取行的后台属性
	 * @param number $line_id 行id
	 * @return boolean|Ambigous <multitype:, boolean>
	 */
	public function getExtendLineAttributeData($line_id = 0)
	{
		if($line_id)
		{
			//取出该line相应的属性值
			$sql = "select * from ".DB_PREFIX."comp_extend_line_attr_value where line_id = ".$line_id;
		}
		$attr_value_arr = array();
		if($sql)
		{
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				$attr_value_arr[$r['relate_id']] = $r['attr_value'];
			}
		}
	
		$interface_sql = "select * from ".DB_PREFIX."user_interface where uniqueid = '".$this->settings['new_extend']['new_extend_list_ui']."'";
		$extendIdArr = $this->db->query_first($interface_sql);
		if(!$extendIdArr)
		{
			return false;
		}
		$extendUiId = $extendIdArr['id'];
		//获取关联分组id
		$attribute_group_line_name = $this->settings['new_extend']['attribute_group_line_name'];
		$attribute_group_info = $this->getOneDetail('attribute_group',array('name'=>$attribute_group_line_name));
		
		if($attribute_group_info)
		{
			$relate_group_id = $attribute_group_info['id'];
		}
	
	
		//获取属性
		$attr_sql = "SELECT a.*,ar.name AS attr_name,ar.group_id,ar.role_type_id,ar.style_value AS attr_style_value,ar.default_value AS attr_default_value,ar.id AS relate_id FROM " .DB_PREFIX. "attribute_relate ar
	    														  LEFT JOIN " .DB_PREFIX. "attribute a ON ar.attr_id = a.id
	    														  WHERE ar.ui_id = '" .$extendUiId. "' AND ar.role_type_id = 2 AND ar.is_extend = 1 and group_id = ".$relate_group_id;
		$attr_q = $this->db->query($attr_sql);
		$attrArr = array();
		while($r = $this->db->fetch_array($attr_q))
		{
			//获取样式
			if($r['attr_style_value'] && unserialize($r['attr_style_value']))
			{
				$r['attr_style_value'] = unserialize($r['attr_style_value']);
			}
			else if($r['style_value'] && unserialize($r['style_value']))
			{
				$r['attr_style_value'] = unserialize($r['style_value']);
			}
	
			//获取默认值
			if(isset($attr_value_arr[$r['relate_id']]))//说明用户自己设置过值，没有设置就用默认值
			{
				$r['attr_default_value'] = $attr_value_arr[$r['relate_id']];
			}
				
			if($r['attr_default_value'] && unserialize($r['attr_default_value']))
			{
				$r['attr_default_value'] = unserialize($r['attr_default_value']);
			}
	
			$r['attr_type_uniqueid'] = $this->settings['attribute_type'][intval($r['attr_type_id'])]['uniqueid'];
				
			if($r['attr_type_uniqueid'])
			{
				//按照打包配置文件的需要的数据结构输出
				switch ($r['attr_type_uniqueid'])
				{
					//单选
					case 'single_choice':break;
					//勾选
					case 'check':$r['attr_default_value'] = (bool)$r['attr_default_value'];break;
					//取值范围
					case 'span':break;
				}
			}
			unset($r['default_value'],$r['style_value']);
			$attrArr[$r['group_id']][] = $r;
		}
		return $attrArr;
	}
	
	/**
	 * 获取单个数据
	 * @param String $table
	 * @param Array $data
	 * @param String $fields
	 */
	private function getOneDetail($table, $data, $fields = '*')
	{
		$sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX . $table .' WHERE 1';
		foreach ($data as $k => $v)
		{
			if (is_numeric($v))
			{
				$sql .= ' AND ' . $k . ' = ' . $v;
			}
			elseif (is_string($v))
			{
				$sql .= ' AND ' . $k . ' = "' . $v . '"';
			}
		}
		return $this->db->query_first($sql);
	}
	
	/**
	 * 取扩展单元的后台属性
	 * @param number $field_id 扩展单元id
	 */
	public function getExtendFieldAttributeData($field_id = 0)
	{
		if($field_id)
		{
			//取出该field相应的属性值
			$sql = "select * from ".DB_PREFIX."comp_extend_field_attr_value where field_id = ".$field_id;
		}
		$attr_value_arr = array();
		if($sql)
		{
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				$attr_value_arr[$r['relate_id']] = $r['attr_value'];
			}
		}
		$interface_sql = "select * from ".DB_PREFIX."user_interface where uniqueid = '".$this->settings['new_extend']['new_extend_list_ui']."'";
		$extendIdArr = $this->db->query_first($interface_sql);
		if(!$extendIdArr)
		{
			return false;
		}
		$extendUiId = $extendIdArr['id'];
		//获取关联分组id
		$attribute_group_line_name = $this->settings['new_extend']['attribute_group_field_name'];
		$attribute_group_info = $this->getOneDetail('attribute_group',array('name'=>$attribute_group_line_name));
		if($attribute_group_info)
		{
			$relate_group_id = $attribute_group_info['id'];
		}
		//获取属性
		$attr_sql = "SELECT a.*,ar.name AS attr_name,ar.group_id,ar.role_type_id,ar.style_value AS attr_style_value,ar.default_value AS attr_default_value,ar.id AS relate_id FROM " .DB_PREFIX. "attribute_relate ar
	    														  LEFT JOIN " .DB_PREFIX. "attribute a ON ar.attr_id = a.id
	    														  WHERE ar.ui_id = '" .$extendUiId. "' AND ar.role_type_id = 2 AND ar.is_extend = 1 and ar.group_id = ".$relate_group_id;
	
		$attr_q = $this->db->query($attr_sql);
		$attrArr = array();
		while($r = $this->db->fetch_array($attr_q))
		{
			//获取样式
			if($r['attr_style_value'] && unserialize($r['attr_style_value']))
			{
				$r['attr_style_value'] = unserialize($r['attr_style_value']);
			}
			else if($r['style_value'] && unserialize($r['style_value']))
			{
				$r['attr_style_value'] = unserialize($r['style_value']);
			}
	
			//获取默认值
			if(isset($attr_value_arr[$r['relate_id']]))//说明用户自己设置过值，没有设置就用默认值
			{
				$r['attr_default_value'] = $attr_value_arr[$r['relate_id']];
			}
	
			if($r['attr_default_value'] && unserialize($r['attr_default_value']))
			{
				$r['attr_default_value'] = unserialize($r['attr_default_value']);
			}
	
			$r['attr_type_uniqueid'] = $this->settings['attribute_type'][intval($r['attr_type_id'])]['uniqueid'];
	
			if($r['attr_type_uniqueid'])
			{
				//按照打包配置文件的需要的数据结构输出
				switch ($r['attr_type_uniqueid'])
				{
					//单选
					case 'single_choice':break;
					//勾选
					case 'check':$r['attr_default_value'] = (bool)$r['attr_default_value'];break;
					//取值范围
					case 'span':break;
				}
			}
			unset($r['default_value'],$r['style_value']);
			$attrArr[$r['group_id']][] = $r;
		}
		return $attrArr;
	
	}
	
	/*********************************打包配置获取属性end***************************************************/
	
	
}