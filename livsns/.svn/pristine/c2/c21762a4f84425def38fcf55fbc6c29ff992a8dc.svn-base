<?php
class group extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->Members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition,$offset,$count)
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$sql = "SELECT * FROM " . DB_PREFIX . "group ";
		$sql.= " WHERE 1 " . $condition;
		$sql .= " ORDER BY order_id ASC".$limit;
		$q = $this->db->query($sql);
		$staricon=$this->Members->staricon();
		$return = array();
		$pid=$this->Members->showpurview();
		while ($row = $this->db->fetch_array($q))
		{
			unset($row['rules']);
			if(!empty($row['icon']))
			{
				$row['icon']=maybe_unserialize($row['icon']);
			}
			$row['pid']=$pid[$row['id']];
			$row['showstar']=$this->Members->showstar($row['starnum'], $staricon);
			unset($row['starnum']);
			$row['updatetype']=$this->settings['updatetype'][$row['isupdate']];
			$return[] = $row;
		}

		return $return;
	}

	public function detail($id,$admin=false)//admin参数为了区分前后台调用不同的方法使用
	{

		$condition = " WHERE id IN ('" . $id ."')";
		$sql = "SELECT * FROM " . DB_PREFIX . "group " . $condition;
		$row = $this->db->query_first($sql);
		if($row['id'])
		{
			if($admin)
			{
				$pid=$this->Members->showpurview($row['id']);
			}
			else {
				$pid=$this->showpurview($row['id']);
			}
			$row['pid']=$pid[$row['id']];
		}
		if($row['icon'])
		{
			$row['icon']=maybe_unserialize($row['icon']);
		}
		if(is_array($row) && $row)
		{
			$staricon=$this->Members->staricon();
			$row['showstar']=$this->Members->showstar($row['starnum'],$staricon);
			unset($row['starnum']);
			unset($row['rules']);
			return $row;
		}
		return false;
	}
	
	//权限id获取
	public function showpurview($gid)
	{
		$where='';
		if($gid)
		{
			$where=" AND gid =".$gid;
		}
		$row=array();
		$sql = "SELECT gid,pid FROM " . DB_PREFIX . "purview_bind WHERE 1".$where;
		$query=$this->db->query($sql);
		while ($ret = $this->db->fetch_array($query))
		{
			$row[$ret['gid']][]=$ret['pid'];
		}
		return $row?$row:false;
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
			if (is_string($v))
			{
				$fields .= $k . "='" . $v . "',";
			}
			elseif (is_int($v) || is_float($v))
			{
				$fields .= $k . '=' . $v . ',';
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'INSERT INTO ' . DB_PREFIX . $table . ' SET ' . $fields;
		$this->db->query($sql);
		$id = $this->db->insert_id();
		if($table=='group')//更新附加信息表排序
		{
			$sql = 'UPDATE '.DB_PREFIX. $table . ' set order_id = '.$id.' WHERE id = '.$id;
			$this->db->query($sql);
		}
		$data[$pk] = $id;
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
				if (is_string($v))
				{
					$fields .= $k . "='" . $v . "',";
				}
				elseif (is_int($v) || is_float($v))
				{
					$fields .= $k . '=' . $v . ',';
				}
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . $fields . ' WHERE 1';
		if ($idsArr)
		{
			foreach ($idsArr as $key => $val)
			{
				if (is_int($val) || is_float($val))
				{
					$sql .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val))
				{
					$sql .= ' AND ' . $key . ' = \'' . $val.'\'';
					//		            return $this->detail($val);
				}
				elseif (is_array($var))
				{
					$sql .= ' AND ' . $key . ' in (\'' .implode("','", $val ) . '\')';
					//		            return $this->detail($val);
				}
			}
		}

		$this->db->query($sql);
		if ($idsArr)
		{
			foreach ($idsArr as $key => $val)
			{
				if (is_int($val) || is_float($val))
				{
					return $this->detail($val);
				}
				elseif (is_string($val))
				{
					return $this->detail($val);
				}
			}
		}

	}

	/**
	 * 删除
	 * @paramString $table
	 * @param Array $data
	 */
	public function delete($table, $data)
	{
		if (empty($table) || !is_array($data)) return false;
		$sql = 'DELETE FROM ' . DB_PREFIX . $table . ' WHERE 1';
		foreach ($data as $k => $v)
		{
			if (is_int($v) || is_float($v))
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
	 * 验证数据
	 * @param Array $data
	 */
	public function verify($data)
	{
		if (!is_array($data)) return false;
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'group WHERE 1';
		$condition = '';
		foreach ($data as $k => $v)
		{
			if (is_int($v) || is_float($v))
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
	 * 设置用户组是否启用
	 */
	public function enable($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "group WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		/**********************************以下状态只是示例，根据情况而定************************************/
		switch (intval($pre_data['enable']))
		{
			case 0:$status = 1;break;//启用
			case 1:$status = 0;break;//关闭
		}
		
		$sql = " UPDATE " .DB_PREFIX. "group SET enable = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('enable' => $status,'id' => $id);
	}

}

?>