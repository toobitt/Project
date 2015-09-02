<?php
/***************************************************************************
* $Id: appTemplate.class.php 17481 2013-04-19 09:36:46Z yaojian $
***************************************************************************/
class appTemplate extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 获取APP数据
	 * @param Array $data
	 */
	public function show($data)
	{
		if ($data['count'] != -1)
		{
			$data_limit = ' LIMIT ' . $data['offset'] . ' , ' . $data['count'];
		}
		$fields = $data['fields'] ? $data['fields'] : '*';
		$sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX . 'app_template WHERE 1';
		//获取查询条件
		$condition = $this->get_condition($data['condition']);
		$sql .= $condition;
		if ($data_limit) $sql .= $data_limit;
		$query = $this->db->query($sql);
		$info = $temp_ids = array();
		while ($rows = $this->db->fetch_array($query))
		{
		    $rows['create_time'] = date('Y-m-d H:i',$rows['create_time']);
			if (unserialize($rows['pic']))
			{
				$rows['pic'] = unserialize($rows['pic']);
			}
			if (unserialize($rows['module_pic_zip']))
			{
				$rows['module_pic_zip'] = unserialize($rows['module_pic_zip']);
			}
			$temp_ids[$rows['id']] = $rows['id'];
			$info[] = $rows;
		}
		if ($temp_ids)
		{
			$pic_info = $this->get_pic(implode(',', $temp_ids));
			if ($pic_info)
			{
				foreach ($info as $k => $v)
				{
					$info[$k]['example_pic'] = $pic_info[$v['id']];
				}
			}
		}
		return $info;
	}
	
	/**
	 * 获取数据总数
	 * @param Array $data
	 */
	public function count($data)
	{
		$condition = $this->get_condition($data);
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'app_template WHERE 1';
		if ($condition) $sql .= $condition;
		return $this->db->query_first($sql);
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
	 * 验证数据
	 * @param Array $data
	 */
	public function verify($data)
	{
		if (!is_array($data)) return false;
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'app_template WHERE 1';
		$condition = '';
		foreach ($data as $k => $v)
		{
			if (is_numeric($v))
			{
				$condition .= ' AND ' . $k . ' = ' . $v;
			}
			elseif (is_string($v))
			{
				$condition .= ' AND ' . $k . ' = "' . $v . '"';
			}
		}
		$sql .= $condition;
		$result = $this->db->query_first($sql);
		return $result['total'];
	}
	
	/**
	 * 获取模板对应的图片
	 * @param Int|String $temp_id
	 */
	public function get_pic($temp_id)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'picture_template WHERE 1';
		if (is_numeric($temp_id))
		{
			$condition .= ' AND temp_id = ' . $temp_id;
		}
		elseif (is_string($temp_id))
		{
			$condition .= ' AND temp_id IN (' . $temp_id . ')';
		}
		if ($condition) $sql .= $condition;
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			if (unserialize($rows['info']))
			{
				$rows['info'] = unserialize($rows['info']);
			}
			$info[$rows['temp_id']][] = $rows;
		}
		return $info;
	}
	
	/**
	 * 根据模板的id获取属性节点数据
	 * @param Int $temp_id
	 * @param Int $app_id
	 * @param Boolean $flag 是否关联查询
	 */
	public function get_attribute($temp_id, $app_id = 0, $flag = false)
	{
		$fields = $flag ? 't.' : '';
		if (intval($temp_id) > 0)
		{
			$condition = ' AND ' . $fields . 'temp_id = ' . $temp_id;
		}
		if ($flag)
		{
			$sql = 'SELECT a.*, t.temp_id, t.def_val AS defVal, t.name AS new_name, t.brief AS new_brief, t.sort_order, t.owning_group 
			FROM ' . DB_PREFIX . 'app_attribute a, ' . DB_PREFIX . 'temp_attr t WHERE a.id = t.attr_id' . $condition;
			if (intval($app_id) > 0)
			{
				$sql = 'SELECT t.*, tv.attr_value, tv.app_id,tv.selected_value FROM (' . $sql . ') AS t 
				LEFT JOIN ' . DB_PREFIX . 'temp_value tv ON t.temp_id = tv.temp_id 
				AND t.id = tv.attr_id AND tv.app_id = ' . $app_id;
			}
		}
		else
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'temp_attr WHERE 1' . $condition;
		}
		$sql .= ' ORDER BY ' . $fields . 'sort_order ASC';
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			if ($flag)
			{
				$arr = $rows;
				$arr['name'] = $rows['new_name'] ? $rows['new_name'] : $rows['name'];
				$arr['brief'] = $rows['new_brief'] ? $rows['new_brief'] : $rows['brief'];
				$arr['def_val'] = $rows['defVal'] ? $rows['defVal'] : $rows['def_val'];
				if ($arr['def_val'] && unserialize($arr['def_val']))
				{
					$arr['def_val'] = unserialize($arr['def_val']);
					foreach ($arr['def_val'] as $k => $def)
					{
					    if (strpos($def['value'], '|'))
					    {
        				    $val_arr = explode('|', $def['value']);
        				    $v_arr = array();
        				    foreach ($val_arr as $v)
        				    {
        				        $vv = explode(':', $v);
        				        $v_arr[$vv[0]] = $vv[1];
        				    }
        				    $arr['def_val'][$k]['value'] = $v_arr;
					    }
					}
				}
				
				/*
				if ($arr['attr_value'] && unserialize($arr['attr_value']))
				{
					$arr['attr_value'] = unserialize($arr['attr_value']);
				}
				if (($arr['type'] == 'singlefile' || $arr['type'] == 'multiplefiles') && $arr['attr_value'])
				{
					 $material_info = $this->get_material($arr['attr_value']);
					 if ($material_info && $arr['type'] == 'singlefile')
					 {
					 	$arr['attr_value'] = $material_info[0];
					 }
					 elseif ($material_info && $arr['type'] == 'multiplefiles')
					 {
					 	$arr['attr_value'] = $material_info;
					 }
				}
				*/
				
				 //单图的情况
				if (($arr['type'] == 'singlefile') && $arr['attr_value'])
				{
					 $material_info = $this->get_material($arr['attr_value']);
					 if ($material_info && isset($material_info[0]) && $material_info[0])
					 {
					 	$arr['attr_value'] = $material_info[0];
					 }
				}
				
				//多图的情况处理
			    if ($arr['type'] == 'multiplefiles')
				{
				    //如果自己的值不存在，就取默认值
				    if(!$arr['attr_value'])
				    {
                        if($arr['def_val'])
                        {
                            $arr['attr_value'] = $arr['def_val'];
                        }
				    }
				    
				    //选中的默认图
				    if(!$arr['selected_value'])
				    {
				        if($arr['def_val'])
                        {
                            $arr['selected_value'] = $arr['def_val'];
                        }
				    }
				    
				    if($arr['attr_value'])
				    {
    				    $material_info = $this->get_material($arr['attr_value']);
    					if ($material_info)
    					{
    					    foreach ($material_info AS $_kk => $_vv)
    					    {
    					        if(intval($arr['selected_value']) == intval($_vv['id']))
    					        {
    					            $material_info[$_kk]['is_selected'] = 1;
    					        }
    					        else 
    					        {
    					            $material_info[$_kk]['is_selected'] = 0;
    					        }
    					    }
    					    $arr['attr_value'] = $material_info;
    					}
    					else 
    					{
    					    $arr['attr_value'] = '';
    					}
				    }
				}
				
				unset($arr['new_name']);
				unset($arr['new_brief']);
				unset($arr['defVal']);
				$info[$rows['temp_id']][] = $arr;
			}
			else
			{
				if (unserialize($rows['def_val']))
				{
					$rows['def_val'] = unserialize($rows['def_val']);
				}
				$info[] = $rows;
			}
		}
		return $info;
	}
	
	public function get_template_attr($data)
	{
	    $sql = 'SELECT * FROM ' . DB_PREFIX . 'temp_value WHERE 1';
	    if ($data && is_array($data))
	    {
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
	    }
	    $query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$info[$rows['id']] = $rows;
		}
		return $info;
	}
	
	public function getGroup($ids)
	{
	    $sql = 'SELECT * FROM ' . DB_PREFIX . 'attr_group WHERE 1';
	    if (is_numeric($ids))
		{
			$sql .= ' AND id = ' . $ids;
		}
		elseif (is_string($ids))
		{
			$sql .= ' AND id IN (' . $ids . ')';
		}
	    $query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$info[$rows['id']] = $rows;
		}
		return $info;
	}
	
	/**
	 * 获取对应的素材信息
	 * @param Int|String $ids
	 */
	public function get_material($ids)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'app_material WHERE 1';
		if (is_numeric($ids))
		{
			$condition = ' AND id =' . intval($ids);
		}
		elseif (is_string($ids))
		{
			$condition = ' AND id IN (' . $ids . ')';
		}
		if ($condition) $sql .= $condition;
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$info[] = $rows;
		}
		return $info;
	}
	
	public function getTemplateAttr($temp_id)
	{
	    $sql = 'SELECT ta.*, a.type, a.id, a.def_val as dVal FROM ' . DB_PREFIX . 'temp_attr ta, ' . DB_PREFIX . 'app_attribute a 
	    WHERE ta.attr_id = a.id AND ta.temp_id = ' . $temp_id;
	    $query = $this->db->query($sql);
	    $info = array();
	    while ($rows = $this->db->fetch_array($query))
	    {
	        $info[$rows['id']] = $rows;
	    }
	    return $info;
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
				$fields .= $k . "='" . $v . "',";
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
	 * 获取查询条件
	 * @param Array $data
	 */
	private function get_condition($data)
	{
		$condition = '';
		
		//查询的关键字
		if ($data['keyword'])
		{
			$condition .= " AND name LIKE '%" . $data['keyword'] . "%'";
		}
		
		//根据ID获取数据
		if (isset($data['id']))
		{
			if (is_numeric($data['id']))
			{
				$condition .= " AND id = " . $data['id'];
			}
			elseif (is_string($data['id']))
			{
				$condition .= " AND id IN (" . $data['id'] . ")";
			}
		}
		
		//排序
		$sort = ' ORDER BY ';
		if ($data['order'] && is_array($data['order']))
		{
			foreach ($data['order'] as $k => $v)
			{
				$sort .= $k . ' ' . $v . ', ';
			}
			$sort = rtrim($sort, ', ');
		}
		else
		{
			$sort .= 'id DESC';
		}
		$condition = $condition . $sort;
		return $condition;
	}
}
?>