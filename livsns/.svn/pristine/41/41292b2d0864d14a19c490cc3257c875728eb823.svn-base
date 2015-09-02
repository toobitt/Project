<?php
include_once (ROOT_PATH.'lib/class/curl.class.php');
class gather extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition, $orderby, $offset, $count)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT g.*, s.name AS sort_name FROM '.DB_PREFIX.'gather g
				LEFT JOIN '.DB_PREFIX.'sort s ON g.sort_id = s.id 
				WHERE 1 '.$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($query))
		{ 	
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['set_id'] = $row['set_id'] ? unserialize($row['set_id']) : '';
			$row['set_url'] = $row['set_url'] ? unserialize($row['set_url']) : '';
			$row['state'] = $row['status'];
			switch ($row['status'])
			{
				case 0 : $row['status_name'] = '待审核';break;
				case 1 : $row['status_name'] = '已审核';break;
				case 2 : $row['status_name'] = '被打回';break;
			}
			$info[] = $row;
		}
		return $info;		
	}

	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX .'gather g  WHERE 1 ' . $condition;
		$res = $this->db->query_first($sql);
		return $res;
	}	
	
	public function detail($id)
	{
		if (!$id)
		{
			return false;
		}
		$sql = 'SELECT g.*,c.content FROM '.DB_PREFIX.'gather g 
				LEFT JOIN '.DB_PREFIX.'gather_content c ON g.id = c.id 
				WHERE g.id = '. $id;
		$data = $this->db->query_first($sql);
		return $data;
	}
	
	public function insert_gather($data)
	{
		
		if (!$data || !is_array($data))
		{
			return false;
			
		}
		$sql = 'INSERT INTO ' .DB_PREFIX. 'gather SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql, ',');
		$this->db->query($sql);
		$insert_id = $this->db->insert_id();
		$sql = 'UPDATE '.DB_PREFIX.'gather SET order_id = '.$insert_id.' WHERE id = '.$insert_id;
		$this->db->query($sql);
		return $insert_id;
		
	}
	
	public function insert_content($content, $id)
	{
		if (!$content || !$id)
		{
			return false;
		}
		$sql = 'REPLACE INTO '.DB_PREFIX.'gather_content (id, content)VALUES('.$id.',"'.addslashes($content).'")';
		$this->db->query($sql);
		return $content;
	}
	//单图插入素材库
	public function insert_material($data)
	{
		if (!$data || !is_array($data))
		{
			return false;
		}
		$sql = 'INSERT INTO ' .DB_PREFIX. 'material SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$insert_id =  $this->db->insert_id();
		$data['id'] =  $insert_id;
		return $data;
	}
	/**
	 * 
	 * @Description  公共数据插入方法
	 * @author Kin
	 * @date 2013-8-20 下午05:38:49
	 */
	public function insert_data($data,$table)
	{
		if(!$table || !$data)
		{
			return false;
		}
		$sql="INSERT INTO " . DB_PREFIX .$table. " SET ";		
		if(is_array($data))
		{
			$sql_extra = $space=' ';
			foreach($data as $key => $val)
			{
				$sql_extra .= $space . $k . "='" . addslashes($val) . "'";
				$space=',';
			}
			$sql .= $sql_extra;
		}
		else
		{
			$sql .= $data;
		}
		$this->db->query($sql);
		return $this->db->insert_id();		
	}
	/**
	 * 
	 * @Description 公共数据更新方法
	 * @author Kin
	 * @date 2013-8-20 下午05:40:06
	 */
	public function update($data, $content, $id) 
	{
		if (!$data || !$id || !is_array($data))
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'gather SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql, ',');
		$sql .= ' WHERE id = '.$id;
		$this->db->query($sql);
		$sql = 'REPLACE INTO '.DB_PREFIX.'gather_content (id,content) VALUES ('.$id.',"'.addslashes($content).'")';
		$this->db->query($sql);
		return true;
		
	}
	
	public function audit($ids,$status)
	{
		$sql = 'UPDATE '.DB_PREFIX.'gather SET status = '.$status.' WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$ids = explode(',', $ids);
		$arr = array(
			'id'=>$ids,
			'status'=>$status,
		);
		return $arr;
	}
	
	public function delete($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'gather WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$sql = 'DELETE FROM '.DB_PREFIX.'gather_content WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return $ids;	
	}
	/**
	 * 
	 * @Description 根据分类获取配置
	 * @author Kin
	 * @date 2013-8-29 下午03:49:40
	 */
	public function get_config_by_sortId($sortIds)
	{
		if (!$sortIds)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'gather_set WHERE sort_id IN ('.$sortIds.')';
		$query = $this->db->query($sql);
		$config = array();
		$sorts = array();
		$data = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['parameter'] = @unserialize($row['parameter']) ? unserialize($row['parameter']) : '';
			$config[$row['id']] = $row;
			$sorts[$row['sort_id']][] = $row['id']; 
		}
		if (empty($config))
		{
			return false;
		}
		foreach ($sorts as $key=>$val)
		{
			foreach ($val as $vv)
			{
				$data[$key][]= $config[$vv]; 
			}
		}
		return $data;
	}
	/**
	 * 
	 * @Description 更新转发设置id
	 * @author Kin
	 * @date 2013-8-31 下午04:56:55
	 */
	public function update_set_url($setId, $id)
	{
		if (!$id)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'gather SET set_url ="'.addslashes($setId).'" WHERE id = '.$id;
		$this->db->query($sql);
		return true;
	}
	
	/**
	 * 
	 * @Description 插入直接转发队列
	 * @author Kin
	 * @date 2013-9-4 上午11:38:35
	 */
	public function insert_gather_plan($id, $set_id)
	{
		if (!$id || !$set_id)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'gather_plan (id, cid, set_id, create_time)
				 VALUES ("",'.$id.','.$set_id.','.TIMENOW.')';
		$this->db->query($sql);
		return true;
	}
	
}
?>
