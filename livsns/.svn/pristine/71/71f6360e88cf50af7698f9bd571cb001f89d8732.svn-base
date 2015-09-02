<?php 
class fastInputSort extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show($condition='',$order_by=' ORDER BY order_id DESC',$offset=0,$count=20)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput_sort WHERE 1 '.$condition.$order_by.$limit;
		$res = $this->db->query($sql);
		$k =array();
		while (!false ==($row = $this->db->fetch_array($res)))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['fid'] = 0;
			$row['parents'] = $row['id'];
			$row['is_last'] = 1;
			$row['childs'] = $row['id'];
			$row['depath'] = 1;
			$k[$row['id']] = $row;
		}
		return $k;	
	}
	public function detail($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput_sort WHERE id='.$id;
		$res = $this->db->query_first($sql);
		if (!empty($res))
		{
			return $res;
		}
		
		
	}
	public function create($data)
	{
		$sql = 'INSERT INTO '.DB_PREFIX.'fastInput_sort SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		//更新order_id，默认与ID值相同
		$u_sql = 'UPDATE '.DB_PREFIX.'fastInput_sort SET order_id = '.$id.' WHERE id ='.$id;
		$this->db->query($u_sql);
		return $id;
	}
	public function delete($id)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'fastInput_sort WHERE id IN ('.$id.')';
		$this->db->query($sql);
		return $id;
	}
	public function update($data,$id)
	{
		$sql = 'UPDATE '.DB_PREFIX.'fastInput_sort SET ';
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
	
	/**
	 * 
	 * @Description  检测分类是否存在 存在－fasle 不存在－true
	 * @author Kin
	 * @date 2013-4-24 上午10:35:20
	 */
	public function check($name)
	{
		$sql = 'SELECT id FROM '.DB_PREFIX.'fastInput_sort WHERE name="'.$name.'"';
		$res = $this->db->query_first($sql);
		if ($res['id'])
		{
			return false;
		} else 
		{
			return true;
		}
	}
	/**
	 * 删除分类时检查是否有相关内容
	 * Enter description here ...
	 */
	public function checkcon($id)
	{
		$sql = 'SELECT id FROM '.DB_PREFIX.'fastInput WHERE sort_id IN('.$id.')';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[] = $row;
		}
		if (!empty($k))
		{
			return false;
		}else 
		{
			return true;
		}
	}
	
}

?>