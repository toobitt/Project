<?php
class new_extend extends InitFrm{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function getALLLineInfoByModuleId($user_id = 0 , $module_id = 0)
	{
		//获取up位置
		$up_sql = "select * from ".DB_PREFIX."new_extend_line where user_id = ".$user_id." and module_id = ". $module_id . " and line_position = 1 order by order_id asc";
		$up_q = $this->db->query($up_sql);
		//获取down位置
		$down_sql = "select * from ".DB_PREFIX."new_extend_line where user_id = ".$user_id." and module_id = ". $module_id . " and line_position = 2 order by order_id desc";
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
	
	public function getAllLines($user_id = 0 , $module_id = 0 , $order = 'asc')
	{
		$sql = "select * from ".DB_PREFIX."new_extend_line where user_id = ".$user_id." and module_id = ".$module_id." order by order_id ".$order;
		$q = $this->db->query($sql);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		return $ret;
	}
	
	
	/**
	 * 获取单个数据
	 * @param String $table
	 * @param Array $data
	 * @param String $fields
	 */
	public function detail($table, $data, $fields = '*')
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
	 * 创建数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param String $pk 主键
	 */
	public function create($table, $data, $pk = 'id')
	{
		if (!$table || !is_array($data)) return false;
		$fields = '';
		foreach ($data as $k => $v)
		{
			if (is_numeric($v))
			{
				$fields .= $k . '=' . $v . ',';
			}
			elseif (is_string($v))
			{
				if ($k == 'uuid')
				{
					$fields .= $k . '=uuid()' . ',';
				}
				else
				{
					$fields .= $k . "='" . addslashes($v) . "',";
				}
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'INSERT INTO ' . DB_PREFIX . $table . ' SET ' . $fields;
		$this->db->query($sql);
		$data[$pk] = $this->db->insert_id();
		return $data;
	}
	
	/**
	 * 更新数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param Array $idsArr 条件
	 * @param Boolean $flag
	 */
	public function update($table, $data, $idsArr, $flag = false)
	{
		if (!$table || !is_array($data) || !is_array($idsArr)) return false;
		$fields = '';
		foreach ($data as $k => $v)
		{
			if ($flag)
			{
				$v = $v > 0 ? '+' . $v : $v;
				$fields .= $k . '=' . $k . $v . ',';
			}
			else
			{
				if (is_numeric($v))
				{
					$fields .= $k . '=' . $v . ',';
				}
				elseif (is_string($v))
				{
					$fields .= $k . "='" . $v . "',";
				}
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . $fields . ' WHERE 1';
		if ($idsArr)
		{
			foreach ($idsArr as $key => $val)
			{
				if (is_numeric($val))
				{
					$sql .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val))
				{
					$sql .= ' AND ' . $key . ' in (' . $val . ')';
				}
			}
		}
		return $this->db->query($sql);
	}
	
	/**
	 * 删除信息
	 * @paramString $table
	 * @param Array $data
	 */
	public function delete($table, $data)
	{
		if (empty($table) || !is_array($data)) return false;
		$sql = 'DELETE FROM ' . DB_PREFIX . $table . ' WHERE 1';
		foreach ($data as $k => $v)
		{
			if (is_numeric($v))
			{
				$sql .= ' AND ' . $k . ' = ' . $v;
			}
			elseif (is_string($v))
			{
				$sql .= ' AND ' . $k . ' IN (' . $v . ')';
			}
		}
		return $this->db->query($sql);
	}
	
	/**
	 * 查询
	 * @param unknown $table
	 * @param unknown $data
	 */
	public function getInfo($table = '',$data)
	{
		if (empty($table) || !is_array($data)) 
		{
			return false;
		}
		$sql = "select * from " . DB_PREFIX . $table . " where 1";
		foreach ($data as $k => $v)
		{
			if (is_numeric($v))
			{
				$sql .= ' AND ' . $k . ' = ' . $v;
			}
			elseif (is_string($v))
			{
				$sql .= ' AND ' . $k . ' IN (' . $v . ')';
			}
		}
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		return $ret;	
	}
	
	
	
	/**
	 * 设置扩展行前端属性
	 */
	public function setNewExtendLineUiValue($data = array())
	{
		if(!$data)
		{
			return FALSE;
		}
		//先判断对应信息是否存在
		$sql = "select * from " . DB_PREFIX . "new_extend_line_ui_attr_value where line_id = ".$data['line_id'] . " and module_id = " . $data['module_id'] . " and user_id = ".$data['user_id'] . " and ui_attr_id =  " . $data['ui_attr_id'];
		$dele_data = $this->db->query_first($sql);
		if($dele_data)
		{
			//如果存在就先删除
			$delete_sql = "delete from " . DB_PREFIX . "new_extend_line_ui_attr_value where line_id = " . $data['line_id'] . " and module_id = " . $data['module_id'];
			$this->db->query($delete_sql);
		}
		//插入
		$insert_sql = "insert into " . DB_PREFIX . "new_extend_line_ui_attr_value set ";
		foreach ($data as $k => $v)
		{
			$insert_sql .= " {$k} = '{$v}',";
		}
		$insert_sql = trim($insert_sql,',');
		$this->db->query($insert_sql);
	}
	
	/**
	 * （新建扩展行信息）
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
		$sql = "SELECT * FROM " .DB_PREFIX. "new_extend_line_attr_value WHERE line_id = '" .$data['line_id']. "' AND relate_id = '" .$data['relate_id'] . "'";
		$pre_data = $this->db->query_first($sql);
		if($pre_data)
		{
			$delete_sql = "delete from " . DB_PREFIX . "new_extend_line_attr_value where line_id = ".$data['line_id'] ." and relate_id = ".$data['relate_id'];
			$this->db->query($delete_sql);
		}
		$insert_sql = " INSERT INTO " . DB_PREFIX . "new_extend_line_attr_value SET ";
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
		$update_sql = "update " . DB_PREFIX . "new_extend_line_attr_value set attr_value = ".$data['attr_value'] . " where 1";
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
	 * 获取行的前端属性
	 */
	
	public function getFrontExtendAttributeData($line_id = 0 , $module_id = 0 , $role_id = 1 ,$ui_id = 0)
	{
		//如果line_id存在 取出这个扩展行当前保存的属性 
		if($line_id)
		{
			$sql = "select * from " . DB_PREFIX . "new_extend_line_ui_attr_value where line_id =" . $line_id . " and module_id = " . $module_id;
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
	 * 
	 * @param number $field_id
	 * @param number $line_id
	 * @param number $module_id
	 * @param number $role_id
	 * @param number $ui_id
	 * @return multitype:unknown
	 */
	public function getFrontExtendFieldAttributeData($field_id = 0 , $line_id = 0 , $module_id = 0 , $role_id = 1 ,$ui_id = 0)
	{
		//如果$field_id存在 取出这个扩展单元当前保存的前端属性
		if($field_id)
		{
			$sql = "select * from " . DB_PREFIX . "new_extend_field_ui_attr_value where line_id =" . $line_id . " and module_id = " . $module_id . " and field_id = " . $field_id;
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
		$sql = "select * from " . DB_PREFIX . "new_extend_field_ui_attr_value where  module_id = " . $data['module_id'] . " and user_id = ".$data['user_id'] . " and ui_attr_id =  " . $data['ui_attr_id'] . " and field_id = ".$data['field_id'];
		$dele_data = $this->db->query_first($sql);
		if($dele_data)
		{
			//如果存在就先删除
			$delete_sql = "delete from " . DB_PREFIX . "new_extend_field_ui_attr_value where module_id = " . $data['module_id'] . " and field_id = " . $data['field_id'];
			$this->db->query($delete_sql);
		}
		//插入
		$insert_sql = "insert into " . DB_PREFIX . "new_extend_field_ui_attr_value set ";
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
		$is_sql = "select * from ".DB_PREFIX."new_extend_field_attr_value where field_id = ".$data['field_id'] . " and relate_id = ".$data['relate_id'];
		$is_data = $this->db->query_first($is_sql);
		if($is_data)
		{
			$delete_sql = "delete from ".DB_PREFIX."new_extend_field_attr_value where field_id = ".$data['field_id'] . " and relate_id = ".$data['relate_id'];
			$this->db->query($delete_sql);
		}		
		
		$insert_sql = " INSERT INTO " . DB_PREFIX . "new_extend_field_attr_value SET ";
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
		$update_sql = "update " . DB_PREFIX . "new_extend_field_attr_value set attr_value = '".$data['attr_value'] . "' where 1";
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
	public function getAllFields($module_id = 0 , $line_id = 0 , $order = 'asc')
	{
		$sql = "select * from ".DB_PREFIX."new_extend_field where module_id = ".$module_id. " and line_id = ".$line_id. " order by order_id ".$order;
		$q = $this->db->query($sql);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		return $ret;
	}
	
	/**
	 * 页面中显示当前行中所有的扩展单元
	 */
	public function getAllFieldsInLine($module_id = 0 , $line_id = 0 , $user_id = 0)
	{
		$left_sql = "select * from ".DB_PREFIX."new_extend_field where field_position = 1 and module_id = ".$module_id." and line_id = ".$line_id." order by order_id asc";
		$left_q = $this->db->query($left_sql);		
		$right_sql = "select * from ".DB_PREFIX."new_extend_field where field_position = 2 and module_id = ".$module_id." and line_id = ".$line_id." order by order_id desc";
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
	
	public function getAllFieldsInModule($module_id = 0 , $user_id = 0)
	{
		$left_sql = "select * from ".DB_PREFIX."new_extend_field where field_position = 1 and module_id = ".$module_id." order by order_id asc";
		$left_q = $this->db->query($left_sql);		
		$right_sql = "select * from ".DB_PREFIX."new_extend_field where field_position = 2 and module_id = ".$module_id." order by order_id desc";
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
	
	
	
	
	
	/***************************打包时用************************************************/
	
	public function getInfos($table = '' , $data = array() , $order = '')
	{
		if (empty($table) || !is_array($data))
		{
			return false;
		}
		$sql = "select * from " . DB_PREFIX . $table . " where 1";
		foreach ($data as $k => $v)
		{
			if (is_numeric($v))
			{
				$sql .= ' AND ' . $k . ' = ' . $v;
			}
			elseif (is_string($v))
			{
				$sql .= ' AND ' . $k . ' IN (' . $v . ')';
			}
		}
		if($order)
		{
			$sql = $sql." ".$order;
		}
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		return $ret;
		
	}
	
	
	
	
	/***************************打包时用end************************************************/
	
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
			$sql = "select * from ".DB_PREFIX."new_extend_line_attr_value where line_id = ".$line_id;	
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
		$attribute_group_info = $this->detail('attribute_group',array('name'=>$attribute_group_line_name));
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
	 * 取扩展单元的后台属性
	 * @param number $field_id 扩展单元id
	 */
	public function getExtendFieldAttributeData($field_id = 0)
	{
		if($field_id)
		{
			//取出该field相应的属性值
			$sql = "select * from ".DB_PREFIX."new_extend_field_attr_value where field_id = ".$field_id;
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
		$attribute_group_info = $this->detail('attribute_group',array('name'=>$attribute_group_line_name));
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
	
	
	
	/**
	 * 获取指定位置的最
	 */
	public function getOne($module_id = 0 , $line_id , $order = 'asc' , $position = 1)
	{
		$sql = $sql = "select * from ".DB_PREFIX."new_extend_field where module_id = ".$module_id. " and line_id = ".$line_id. " and field_position = ".$position." order by order_id ".$order;
		$q = $this->db->query($sql);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		return $ret;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}