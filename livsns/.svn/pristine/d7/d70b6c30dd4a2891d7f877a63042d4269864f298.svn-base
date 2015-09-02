<?php
//线路的数据库操作

class line extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
		
		
	public function create($info)
	{	
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."line SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		//file_put_contents('011',$sql);exit;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	public function create_stand($info)
	{	
		//插入数据操作
		foreach($info as $key=>$va)
		{
			$sql = "INSERT INTO " . DB_PREFIX ."stand SET ";
			$sql_extra = $space ='';
			foreach($va as $k=>$v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
			}
			$sql .=$sql_extra;
			$this->db->query($sql);
		}
		
	}
	
	//更新线路相关信息
	public function update($info)
	{	
		//更新数据操作
		$sql = "UPDATE " . DB_PREFIX ."line SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$info['id'];
		$this->db->query($sql);		
	}
	
	//删除线路
	public function delete($ids)
	{			
		$sqll = "SELECT routeid
				FROM ".DB_PREFIX."line WHERE id IN (".$ids.")";
		$q = $this->db->query($sqll);
		while($row = $this->db->fetch_array($q))
		{			
			$routeid[] = $row['routeid'];
		}
		$routeids = implode(',',$routeid);
		$sql = "DELETE FROM " . DB_PREFIX . "line WHERE routeid IN (".$routeids.")";
		$this->db->query($sql);
		
		$sql_ = "DELETE FROM " . DB_PREFIX . "stand WHERE routeid IN (".$routeids.")";
		$this->db->query($sql_);
		return true;
		//return ($this->db->affected_rows() > 0) ? true : false;
	}	
	
	//根据条件查询线路
	public function show($condition,$limit)	
	{		
		$sql = "SELECT id,city_name,name,brief,time,price,status,routeid
				FROM  " . DB_PREFIX ."line 
				WHERE 1".$condition.' ORDER BY id DESC'.$limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{			
			switch($row['status'])
			{
				case 0:$row['status_name'] = '未审核';break;
				case 1:$row['status_name'] = '已审核';break;
				case 2:$row['status_name'] = '已打回';break;
				default:$row['status_name'] = '未审核';break;
			}	
			$ret[] = $row;
			
		}
		return $ret;
	}
	
	
	public function audit($ids,$audit)
	{
		if(!$ids)
		{
			return false;
		}
		$arr_id = explode(',',$ids);
		if($audit == 1)
		{
			$sql = "UPDATE " . DB_PREFIX ."line SET status = 1 WHERE id IN (".$ids.")";
			$this->db->query($sql);
			return array('id' => $arr_id,'status' => 1);
		}
		else if($audit == 2) 
 		{
			$sql = "UPDATE " . DB_PREFIX ."line SET status = 2 WHERE id IN (".$ids.")";
			$this->db->query($sql);
			return array('id' => $arr_id,'status' => 2);
		}
	}
	
	public function show_stand($condition,$limit)	
	{		
		$sql = "SELECT stands,busstands
				FROM  " . DB_PREFIX ."line 
				WHERE 1".$condition.$limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{	
			$ret['stands'] = array();	
			if($row['stands'])
			{
				$stands = json_decode($row['stands'],ture);	
				if($stands)
				{
					foreach($stands as $k => $v)
					{
						$sinfo[$k] =  explode(',',$v);
					}
					$ret['stands'] = $sinfo;
				}
				
			}
			$ret['busstands'] = array();
			if($row['busstands'])
			{	
				$busstands = json_decode($row['busstands'],ture);	
				if($busstands)
				{
					foreach($busstands as $k => $v)
					{
						$buinfo[$k] =  explode(',',$v);
					}
				}
				
				$ret['busstands'] = $buinfo;
			}
			
		}
		return $ret;
	}
}


?>