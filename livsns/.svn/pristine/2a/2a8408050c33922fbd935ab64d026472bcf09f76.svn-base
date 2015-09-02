<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: getblog.php 3873 2011-05-05 08:38:24Z repheal $
***************************************************************************/

class comment extends InitFrm
{
	private $obj_member;
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->obj_member = new Auth();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 获取评论数据
	 * @param Int $offset
	 * @param Int $count
	 * @param Array $data
	 */
	public function show($offset, $count, $data = array())
	{
		if ($count != -1)
		{
			$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'status_comments WHERE 1';
		//获取查询条件
		$condition = $this->get_condition($data);
		$sql = $sql . $condition;
		if ($data_limit) $sql .= $data_limit;
		$query = $this->db->query($sql);
		$info = array();
		$member_ids = array();
		while ($row = $this->db->fetch_array($query))
		{
			if($row['member_id'])
			{
				$member_ids[$row['member_id']] = $row['member_id'];
			}
			if($row['reply_member_id'])
			{
				$member_ids[$row['reply_member_id']] = $row['reply_member_id'];
			}
			$info[] = $row;
		}
		if ($member_ids)
		{
			$members = $this->obj_member->getMemberById(implode(',',$member_ids));
			$member_info = array();
			if(!empty($members))
			{
				foreach ($members as $key => $values)
				{
					$member_info[$values['id']] = $values;
				}			
			}
		}
		foreach ($info as $k => $v)
		{
			$info[$k]['member'] = $member_info[$v['member_id']];
			$info[$k]['reply_member'] = $member_info[$v['reply_member_id']];
		}
		return $info;
	}
	
	/**
	 * 获取评论总数
	 * @param Array $data
	 */
	public function count($data)
	{
		$condition = $this->get_condition($data);
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'status_comments WHERE 1';
		$sql .= $condition;
		return $this->db->query_first($sql);
	}
	
	/**
	 * 获取单个评论的数据
	 * @param Int $id
	 */
	public function detail($id)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'status_comments WHERE id = ' . $id;
		return $this->db->query_first($sql);
	}
	
	/**
	 * 验证数据存在
	 * @param Array $data
	 */
	public function check_exists($data)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'status_comments WHERE 1';
		//获取查询条件
		$condition = $this->get_condition($data);
		$sql = $sql . $condition;
		$query = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($query))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	/**
	 * 创建评论
	 * @param Array $data
	 */
	public function create($data)
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
		$sql = 'INSERT INTO ' . DB_PREFIX . 'status_comments SET ' . implode(',', $fields);
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		$members = $this->obj_member->getMemberById($data['member_id']);
		$data['member'] = $members[0];
		return $data;
	}
	
	/**
	 * 更新操作
	 * @param String $table
	 * @param Array $data
	 * @param Array $idsArr
	 * @param Boolean $flag
	 */
	public function update($table, $data, $idsArr, $flag = false)
	{
		$fields = array();
		foreach($data as $k=>$v)
		{
			if ($flag)
			{
				$v = $v > 0 ? '+' . $v : $v;
				$fields[] = $k . '=' . $k . $v;
			}
			else
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
		}
		$sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . implode(',', $fields) . ' WHERE 1';
		if ($idsArr)
		{
			foreach ($idsArr as $key => $val)
			{
				if (is_int($val))
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
	 * 删除评论
	 * @param Array $data
	 */
	public function delete($data)
	{
		$condition = '';
		if ($data && is_array($data))
		{
			foreach ($data as $k=>$v)
			{
				$condition .= ' AND ' . $k;
				if (is_int($v))
				{
					$condition .= ' = ' . $v;
				}
				elseif (is_string($v))
				{
					$condition .= ' IN (' . $v . ')';
				}
			}
		}
		$sql = 'DELETE FROM ' . DB_PREFIX . 'status_comments WHERE 1';
		if ($condition) $sql .= $condition;
		return $this->db->query($sql);
	}
	
	/**
	 * 获取查询条件
	 * @param Array $data
	 */
	public function get_condition($data)
	{
		$condition = '';
		if ($data['id'])
		{
			$condition .= ' AND id';
			if (is_int($data['id']))
			{
				$condition .= ' = ' . $data['id'];
			}
			elseif (is_string($data['id']))
			{
				$condition .= ' IN (' . $data['id'] . ')';
			}
		}
		if ($data['member_id'])
		{
			$condition .= ' AND member_id';
			if (is_int($data['member_id']))
			{
				$condition .= ' = ' . $data['member_id'];
			}
			elseif (is_string($data['member_id']))
			{
				$condition .= ' IN (' . $data['member_id'] . ')';
			}
		}
		if ($data['reply_member_id'])
		{
			$condition .= ' AND reply_member_id';
			if (is_int($data['reply_member_id']))
			{
				$condition .= ' = ' . $data['reply_member_id'];
			}
			elseif (is_string($data['reply_member_id']))
			{
				$condition .= ' IN (' . $data['reply_member_id'] . ')';
			}
		}
		if ($data['status_id'])
		{
			$condition .= ' AND status_id';
			if (is_int($data['status_id']))
			{
				$condition .= ' = ' . $data['status_id'];
			}
			elseif (is_string($data['status_id']))
			{
				$condition .= ' IN (' . $data['status_id'] . ')';
			}
		}
		if ($data['state'])
		{
			$condition .= ' AND flag = 1';
		}
		else
		{
			$condition .= ' AND flag = 0';
		}
		$order = ' ORDER BY create_time DESC';
		return $condition . $order;
	}
}
?>