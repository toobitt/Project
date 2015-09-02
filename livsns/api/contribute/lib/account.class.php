<?php
class contributeAccount extends InitFrm
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
	 * 
	 * 公共入库方法 ...
	 * @param array $data 数据
	 * @param string $dbName  数据库名
	 */
	public function storedIntoDB($data,$dbName,$flag=0)
	{		
		if (!$data || !is_array($data) || !$dbName)
		{
			return false;
		}
		$sql = 'REPLACE INTO '.DB_PREFIX.$dbName.' SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		if ($flag)
		{
			return $this->db->insert_id();
		}
		return true;
	}
	public function show($condition,$orderby,$offset,$count)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = "SELECT * FROM ".DB_PREFIX."user_token WHERE 1 ".$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['expired_time'] = date("Y-m-d H:i:s",$row['expired_time']);
			$row['update_time'] = date("Y-m-d H:i:s",$row['update_time']);
			$k[$row['id']] = $row;
		}
		return $k;
	}
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'user_token WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	public function check_auth($id)
	{
		$sql = 'SELECT can_access FROM '.DB_PREFIX.'user_token WHERE id ='.$id;
		$ret = $this->db->query_first($sql);
		if ($ret['can_access'])
		{
			return true;
		}else{
			return false;
		}	
	}
	public function get_user_info($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'user_token WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	//获取报料的分类
	public function sort()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'sort ';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[$row['id']] = $row['name'];
		}
		return $k;
	}
	public function delete($id)
	{
		$sql =  'DELETE FROM '.DB_PREFIX.'user_token WHERE id IN ( '.$id.')';
		$this->db->query($sql);
		return $id; 
	}
	public function detail($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'user_token WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	public function update($data,$id)
	{
		$sql = 'UPDATE '.DB_PREFIX.'user_token SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE id ='.$id;
		$this->db->query($sql);
		return $id;
	}
	//获取过期用户信息
	function get_userinfo_by_id($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'user_token WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
}