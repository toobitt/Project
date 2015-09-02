<?php 
/***************************************************************************

* $Id: group 26794 2013-08-01 04:34:02Z purview $

***************************************************************************/
class staricon extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function detail($id)
	{
		$condition = ' AND id ='.intval($id) ;		
		$sql='SELECT * FROM '.DB_PREFIX.'staricon WHERE 1'.$condition;
			$starinfo=$this->db->query_first($sql);
			$starinfo['star']=hg_fetchimgurl(unserialize($starinfo['star']));
			$starinfo['moon']=hg_fetchimgurl(unserialize($starinfo['moon']));
			$starinfo['sun']=hg_fetchimgurl(unserialize($starinfo['sun']));
		return $starinfo;
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
					$sql .= ' AND ' . $key . ' in (' . $val . ')';
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
	 *
	 * 等级图标开关 ...
	 */
	public function display($ids)
	{
		$sql='SELECT id FROM '.DB_PREFIX.'staricon WHERE opened=1';
		$staricon=$this->db->query_first($sql);
		if(empty($staricon))
		{
		$sql = 'UPDATE '.DB_PREFIX.'staricon SET opened = 1 WHERE id = '.$ids;
		$this->db->query($sql);
		}
		else 
		{
			if($staricon['id']==$ids)
			{
				return false;
			}
			$opened=array($ids=>1,$staricon['id']=>0);
			$ids = implode(',', array_keys($opened));
		$sql = "UPDATE ".DB_PREFIX."staricon SET opened = CASE id ";
		foreach ($opened as $id => $ordinal) {
			$sql .= sprintf("WHEN %d THEN %d ", $id, $ordinal);  // 拼接SQL语句
		}
		$sql .= "END WHERE id IN ($ids)";
		$this->db->query($sql);
		}
		$arr = array(
			'id'=>$ids,
			'switch'=>1,
		);
		return $arr;
	}
	/**
	 * 验证数据
	 * @param Array $data
	 */
	public function verify($data)
	{
		if (!is_array($data)) return false;
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'staricon WHERE 1';
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
}

?>