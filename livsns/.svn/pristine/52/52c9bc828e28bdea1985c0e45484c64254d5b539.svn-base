<?php 
/***************************************************************************

* $Id: member.class.php 8115 2012-07-19 10:06:15Z lijiaying $

***************************************************************************/
define('APPID', '55');
define('APPKEY', 'GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7');
class fastInput extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show($condition='',$orderby=' ORDER BY id DESC',$offset=0,$count=20)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT fi.* ,fis.name
				FROM '.DB_PREFIX.'fastInput AS fi
				LEFT JOIN '.DB_PREFIX.'fastInput_sort AS fis ON fi.sort_id = fis.id
				WHERE 1 '.$condition.$orderby.$limit;
		$res = $this->db->query($sql);
		$k =array();
		while (!false ==($row = $this->db->fetch_array($res)))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$k[$row['id']] = $row;
		}
		return $k;	
	}
	public function detail($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput WHERE id='.$id;
		$res = $this->db->query_first($sql);
		if (!empty($res))
		{
			return $res;
		}else {
			return false;
		}
	}
	public function get_sort()
	{
		$sql = 'SELECT id,name FROM '.DB_PREFIX.'fastInput_sort';
		$query = $this->db->query($sql);
		$k = array();
		while (!false==($row = $this->db->fetch_array($query)))
		{
			$k[$row['id']] = $row['name'];
		}
		return $k;
	}
	public function create($data)
	{
		$sql = 'INSERT INTO '.DB_PREFIX.'fastInput SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		//更新order_id，默认与ID值相同
		$u_sql = 'UPDATE '.DB_PREFIX.'fastInput SET order_id = '.$id.' WHERE id ='.$id;
		$this->db->query($u_sql);
		return $id;
	}
	public function check($data)
	{
		if (!is_array($data))
		{
			return false;	
		}
		
		$sql = 'SELECT id FROM '.DB_PREFIX.'fastInput WHERE 1 ';
		foreach ($data as $key=>$val)
		{
			$sql .= ' AND '.$key.'="'.$val.'"';
		}
		$res = $this->db->query_first($sql);
		if ($res['id'])
		{
			return false;
		} else 
		{
			return true;
		}
	}
	public function update($data,$id)
	{
		$sql = 'UPDATE '.DB_PREFIX.'fastInput SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE  id='.$id;
		$this->db->query($sql);
		$data['id'] = $id;
		return $data;
	}
	public function delete($id)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'fastInput WHERE id IN ('.$id.')';
		$this->db->query($sql);
		return $id;
	}
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'fastInput '.$condition;
		return  json_encode($this->db->query_first($sql));
	}
}

?>