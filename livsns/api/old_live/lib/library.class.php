<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_plan.class.php 5568 2011-12-31 09:08:23Z repheal $
***************************************************************************/
class library extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		
	}

	public function count()
	{
		
	}
	
	public function detail()
	{
		
	}
	
	public function create()
	{
		
	}

	public function update()
	{
		
	}	

	public function delete()
	{
		
	}
	
	/**
	 * 获取属性数据
	 * @param Int $offset
	 * @param Int $count
	 * @param Array $data
	 */
	public function show_property($offset, $count, $data)
	{
		if ($count != -1)
		{
			$data_limit = ' LIMIT ' . $offset . ', ' . $count;
		}
		$condition = $this->get_condition_property($data);
		$sql = 'SELECT id,name,append FROM ' . DB_PREFIX . 'library_property WHERE 1' . $condition;
		if ($data_limit)
		{
			$sql .= $data_limit;
		}
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	/**
	 * 获取单个属性数据
	 * @param Int $id
	 */
	public function detail_property($id)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'library_property WHERE id = ' . $id;
		$result = $this->db->query_first($sql);
		return $result;
	}
	
	/**
	 * 获取属性总数
	 * @param Array $data
	 */
	public function count_property($data)
	{
		$condition = $this->get_condition_property($data);
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'library_property WHERE 1' . $condition;
		return $this->db->query_first($sql);
	}
	
	/**
	 * 检测属性是否存在
	 * @param Int(id) | String(name) $data
	 */
	public function check_property_exists($data)
	{
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'library_property WHERE 1';
		$condition = '';
		if (is_int($data))
		{
			$condition .= ' AND id = ' . $data;
		}
		elseif (is_string($data))
		{
			$condition .= ' AND name = "' . $data . '"';
		}
		$sql .= $condition;
		$result = $this->db->query_first($sql);
		return $result['total'];
	}
	
	/**
	 * 创建属性
	 * @param Array $data
	 */
	public function create_property($data)
	{	
		/*$tmp_append = $this->auto_create_append($data['name'],$data['append']);
		if($tmp_append)
		{
			$data['append'] = $tmp_append;
			$sql = "SELECT * FROM " . DB_PREFIX . "library_property WHERE name='" . $data['name'] . "' AND append='" . $data['append'] . "'";
			$f = $this->db->query_first($sql);
			if(!empty($f))
			{
				$data['id'] = $f['id'];
			}
			else
			{
				$sql = "INSERT INTO " . DB_PREFIX . "library_property(name,append) VALUES('" . $data['name'] . "','" . $data['append'] . "')";
				$this->db->query($sql);
				$data['id'] = $this->db->insert_id();
				
			}
		}
		else
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "library_property WHERE name='" . $data['name'] . "' AND append='" . $data['append'] . "'";
			$f = $this->db->query_first($sql);
			$data =  $f;
		}
		
		return $data;*/
		
		$fields = array();
		foreach($data as $k=>$v)
		{
			if (is_string($v))
			{
				$fields[] = $k . "='" . $v . "'";
			}
			elseif (is_int($v))
			{
				$fields[] = $k . '=' . $v;
			}
		}
		$sql = 'INSERT INTO ' . DB_PREFIX . 'library_property SET ' . implode(',', $fields);
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		return $data;
	}
	
	/**
	 * 更新属性
	 * @param Array $data
	 * @param Int|String $id
	 */
	public function update_property($data, $id)
	{
		$fields = array();
		foreach($data as $k=>$v)
		{
			if (is_string($v))
			{
				$fields[] = $k . "='" . $v . "'";
			}
			elseif (is_int($v))
			{
				$fields[] = $k . '=' . $v;
			}
		}
		$sql = 'UPDATE ' . DB_PREFIX . 'library_property SET ' . implode(',', $fields) . ' WHERE 1';
		if ($id)
		{
			if (is_int($id))
			{
				$sql .= ' AND id = ' . $id;
			}
			elseif (is_string($id))
			{
				$sql .= ' AND id in (' . $id . ')';
			}
		}
		return $this->db->query($sql);
	}
	
	/**
	 * 删除属性
	 * @param Int|String $id
	 */
	public function delete_property($id)
	{
		$sql = 'DELETE FROM ' . DB_PREFIX . 'library_property WHERE 1';
		$condition = '';
		if (is_int($id))
		{
			$condition .= ' AND id = ' . $id;
		}
		elseif (is_string($id))
		{
			$condition .= ' AND id in (' . $id . ')';
		}
		$sql .= $condition;
		return $this->db->query($sql);
	}
	
	/**
	 * 删除属性与类型关系
	 * @param Array $data
	 * Int|String type_id
	 * Int|String property_id
	 */
	public function drop_relation($data)
	{
		if (!is_array($data)) return false;
		$sql = 'DELETE FROM ' . DB_PREFIX . 'library_relation WHERE 1';
		$condition = '';
		if ($data['type_id'])
		{
			if (is_int($data['type_id']))
			{
				$condition .= ' AND t_id = ' . $data['type_id'];
			}
			elseif (is_string($data['type_id']))
			{
				$condition .= ' AND t_id in (' . $data['type_id'] . ')';
			}
		}
		elseif ($data['property_id'])
		{
			if (is_int($data['property_id']))
			{
				$condition .= ' AND p_id = ' . $data['property_id'];
			}
			elseif (is_string($data['property_id']))
			{
				$condition .= ' AND p_id in (' . $data['property_id'] . ')';
			}
		}
		$sql .= $condition;
		return $this->db->query($sql);
	}
	
	/**
	 * 删除栏目或节目对应的属性
	 * @param Int|String $pid  属性ID
	 */
	public function delete_link($pid)
	{
		$sql = 'DELETE FROM ' . DB_PREFIX . 'library_property_val WHERE 1';
		$condition = '';
		if (is_int($pid))
		{
			$condition .= ' AND p_id = ' . $pid;
		}
		elseif (is_string($pid))
		{
			$condition .= ' AND p_id in (' . $pid . ')';
		}
		$sql .= $condition;
		return $this->db->query($sql);
	}
	
	/**
	 * 重置属性
	 */
	public function reset_property()
	{
		$sql = "DESC " . DB_PREFIX . "library_property_content";
		$q = $this->db->query($sql);
		$data = array();
		$default = array('id','column_id','program_id');
		$drop_sql = "";
		while($row = $this->db->fetch_array($q))
		{
			if(!in_array($row['Field'],$default))
			{
				$drop_sql = "ALTER TABLE `" . DB_PREFIX . "library_property_content` DROP `" . $row['Field'] . "`;";
				$this->db->query($drop_sql);
			}
			$data[] = $row['Field'];
		}
		$sql = "SELECT name,append,state FROM " . DB_PREFIX . "library_property WHERE 1";
		$q = $this->db->query($sql);
		$info = array();
		$add_sql = "";
		while($row = $this->db->fetch_array($q))
		{
			if(empty($row['state']))
			{
				$info[$row['append']] = $row['name'];
				$add_sql = "ALTER TABLE  `" . DB_PREFIX . "library_property_content` ADD  `" . $row['append'] . "` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '" . $row['name'] . "';";
				$this->db->query($add_sql);
			}
		}
		return true;	
	}
	
	//验证name和append同时不存在这个库中
	private function auto_create_append($name,$append)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "library_property WHERE name='" . $name . "'";
		$f = $this->db->query_first($sql);
		if(empty($f))//没有名字，那就返回为空
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "library_property WHERE append='" . $append . "'";
			$sen = $this->db->query_first($sql);
			if(empty($sen))//在不存在名字的情况下，验证标识是否存在
			{
				return $append;
			}
			else//标识已经被占用，重新生成标识
			{
				$append = hg_getPinyin(hg_utf82gb($name),1);
				return $this->auto_create_append($name,$append);
			}
		}
		else//名字存在,已经有记录，那就返回
		{
			return false;
		}
	}
	
	/**
	 * 获取类型的数据
	 * @param Int $offset
	 * @param Int $count
	 * @param Array $data
	 */
	public function show_type($offset, $count, $data)
	{
		if ($count != -1)
		{
			$data_limit = ' LIMIT ' . $offset . ', ' . $count;
		}
		$condition = $this->get_condition_type($data);
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'library_type WHERE 1';
		$sql .= $condition;
		if ($data_limit)
		{
			$sql .= $data_limit;
		}
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$info[] = $rows;
		}
		return $info;
	}
	
	/**
	 * 获取类型总数
	 * @param Array $data
	 */
	public function count_type($data)
	{
		$condition = $this->get_condition_type($data);
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'library_type WHERE 1' . $condition;
		return $this->db->query_first($sql);
	}
	
	/**
	 * 获取单个类型数据
	 * @param Int $id
	 */
	public function detail_type($id)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'library_type WHERE id = ' . $id;
		$type_info = $this->db->query_first($sql);
		if ($type_info)
		{
			//获取该类型对应的属性
			$sql = 'SELECT r.t_id, p.* FROM ' . DB_PREFIX . 'library_relation r 
			LEFT JOIN ' . DB_PREFIX .'library_property p ON r.p_id = p.id WHERE r.t_id = ' . $id;
			$query = $this->db->query($sql);
			$info = array();
			while ($rows = $this->db->fetch_array($query))
			{
				$info[] = $rows;
			}
			$type_info['property'] = $info;
		}
		return $type_info;
	}
	
	/**
	 * 检测类型是否存在
	 * @param Int(id) | String(name) $data
	 */
	public function check_type_exists($data)
	{
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'library_type WHERE 1';
		$condition = '';
		if (is_int($data))
		{
			$condition .= ' AND id = ' . $data;
		}
		elseif (is_string($data))
		{
			$condition .= ' AND name = "' . $data . '"';
		}
		$sql .= $condition;
		$result = $this->db->query_first($sql);
		return $result['total'];
	}
	
	/**
	 * 创建类型
	 * @param Array $data
	 */
	public function create_type($data)
	{	
		$fields = array();
		foreach($data as $k=>$v)
		{
			if (is_string($v))
			{
				$fields[] = $k . "='" . $v . "'";
			}
			elseif (is_int($v))
			{
				$fields[] = $k . '=' . $v;
			}
		}
		$sql = 'INSERT INTO ' . DB_PREFIX . 'library_type SET ' . implode(',', $fields);
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		return $data;
	}
	
	/**
	 * 创建类型与属性的关系
	 * @param Int $type_id
	 * @param Int|String $property_ids
	 */
	public function create_relation($type_id, $property_ids)
	{
		$valArr = array();
		foreach ($property_ids as $v)
		{
			$valArr[] = '(' . $type_id . ', ' . $v . ')';
		}
		$valArr = implode(', ', $valArr);
		$sql = 'INSERT INTO ' . DB_PREFIX . 'library_relation (t_id, p_id) VALUES' . $valArr;
		return $this->db->query($sql);
	}
	
	/**
	 * 更新类型
	 * @param Array $data
	 */
	public function update_type($data, $id)
	{
		$fields = array();
		foreach($data as $k=>$v)
		{
			if (is_string($v))
			{
				$fields[] = $k . "='" . $v . "'";
			}
			elseif (is_int($v))
			{
				$fields[] = $k . '=' . $v;
			}
		}
		$sql = 'UPDATE ' . DB_PREFIX . 'library_type SET ' . implode(',', $fields) . ' WHERE 1';
		if ($id)
		{
			if (is_int($id))
			{
				$sql .= ' AND id = ' . $id;
			}
			elseif (is_string($id))
			{
				$sql .= ' AND id in (' . $id . ')';
			}
		}
		return $this->db->query($sql);
	}
	
	/**
	 * 删除类型
	 * @param Int|String $id
	 */
	public function delete_type($id)
	{
		$sql = 'DELETE FROM ' . DB_PREFIX . 'library_type WHERE 1';
		$condition = '';
		if (is_int($id))
		{
			$condition .= ' AND id = ' . $id;
		}
		elseif (is_string($id))
		{
			$condition .= ' AND id in (' . $id . ')';
		}
		$sql .= $condition;
		return $this->db->query($sql);
	}
	
	private function get_condition_property($data)
	{
		$condition = '';
		if($data['key'])
		{
			$condition .= " AND name LIKE '%" . $data['key'] . "%' ";
		}
		$condition .= ' ORDER BY id DESC';
		return $condition;
	}
	
	private function get_condition_type($data)
	{
		$condition = '';
		if($data['key'])
		{
			$condition .= " AND name LIKE '%" . $data['key'] . "%' ";
		}
		$condition .= ' ORDER BY id DESC';
		return $condition;
	}
}

?>