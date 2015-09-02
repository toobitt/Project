<?php
/***************************************************************************
* $Id: category.class.php 17481 2013-07-05 09:36:46Z yaojian $
***************************************************************************/
require_once ROOT_PATH . 'frm/node_frm.php';

class category extends nodeFrm
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
	 * 获取相册分类
	 * @param Array $data
	 */
	public function show($data)
	{
		if (!is_array($data))
		{
			return false;
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'category WHERE 1';
		if ($data['condition']) $sql .= $this->get_conditions($data['condition']);
		if ($data['count'] != -1)
		{
			$data_limit = ' LIMIT ' . $data['offset'] . ' , ' . $data['count'];
		}
		if ($data_limit) $sql .= $data_limit;
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$info[] = $rows;
		}
		return $info;
	}
	
	/**
	 * 获取相册分类总数
	 * @param Array $data 查询条件
	 */
	public function count($data = array())
	{
		if (!is_array($data))
		{
			return false;
		}
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'category WHERE 1';
		if ($data) $sql .= $this->get_conditions($data);
		return $this->db->query_first($sql);
	}
	
	/**
	 * 获取单个相册分类
	 * @param Array $data 查询条件
	 * @param String $fields 读取的字段
	 */
	public function detail($data, $fields = '*')
	{
		if (!is_array($data))
		{
			return false;
		}
		$sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX . 'category WHERE 1';
		foreach ($data as $k => $v)
		{
			if (is_int($v) || is_float($v))
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
	 * @param String $data 参数
	 */
	public function create($data)
	{
		$this->initNodeData();
		$this->setNodeTable('category');
		//增加节点无需设置操作节点ID
		$this->setCondition('');
		//设置新增或者需要更新的节点数据
		$this->setNodeData($data);
		return $this->addNode();
	}
	
	/**
	 * 更新数据
	 * @param String $table 更新的表名
	 * @param Array $data 更新的数据
	 * @param Array $idsArr 更新的条件
	 * @param Boolean $flag
	 */
	public function update($table, $data, $idsArr, $flag = false)
	{
		if (empty($table) || !is_array($data) || !is_array($idsArr)) 
		{
			return false;
		}
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
				}
			}
		}
		return $this->db->query($sql);
	}
	
	/**
	 * 删除数据
	 * @param Array $data 删除条件
	 */
	public function delete($data)
	{
		if (!is_array($data))
		{
			return false;
		}
		$sql = 'DELETE FROM ' . DB_PREFIX . 'category WHERE 1';
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
	 * 获取查询条件
	 * @param Array $data
	 */
	public function get_conditions($data)
	{
		$condition = '';
		
		//查询的关键字
		if ($data['keyword'])
		{
			$condition .= " AND name LIKE '%" . $data['keyword'] . "%'";
		}
		
		//根据ID获取数据
		if ($data['id'])
		{
			if (is_int($data['id']))
			{
				$condition .= " AND id = " . $data['id'];
			}
			elseif (is_string($data['id']))
			{
				$condition .= " AND id IN (" . $data['id'] . ")";
			}
		}
		
		//根据fid获取数据
		if ($data['fid'])
		{
			if (is_int($data['fid']))
			{
				$condition .= " AND fid = " . $data['fid'];
			}
			elseif (is_string($data['fid']))
			{
				$condition .= " AND fid IN (" . $data['fid'] . ")";
			}
		}
		
		if ($data['start_time'])
		{
			$start_time = strtotime($data['start_time']);
			$condition .= " AND create_time >= " . $start_time;
		}
		
		if ($data['end_time'])
		{
			$end_time = strtotime($data['end_time']);
			$condition .= " AND create_time < " . $end_time;
		}
		
		//查询发布的时间
        if ($data['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d', TIMENOW+24*3600));
			switch ($data['date_search'])
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d', TIMENOW-24*3600));
					$condition .= " AND create_time > '" . $yesterday . "' AND create_time < '" . $today . "'";
					break;
				case 3://今天的数据
					$condition .= " AND create_time > '" . $today . "' AND create_time < '" . $tomorrow . "'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d', TIMENOW-2*24*3600));
					$condition .= " AND create_time > '" . $last_threeday . "' AND create_time < '" . $tomorrow . "'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d', TIMENOW-6*24*3600));
					$condition .= " AND create_time > '" . $last_sevenday . "' AND create_time < '" . $tomorrow . "'";
					break;
				default://所有时间段
					break;
			}
		}
		
		//排序
		if ($data['order'] && is_array($data['order']))
		{
			$sort = ' ORDER BY ';
			foreach ($data['order'] as $k => $v)
			{
				$sort .= $k . ' ' . $v . ', ';
			}
			$sort = rtrim($sort, ', ');
			$condition .= $sort;
		}
		
		return $condition;
	}
}
?>