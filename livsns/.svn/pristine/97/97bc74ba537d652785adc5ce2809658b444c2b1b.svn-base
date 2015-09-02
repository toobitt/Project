<?php
//后台客运管理类
class bustypes extends InitFrm
{	
	public function show($orderby,$limit,$condition = '')
	{
		$sql = "SELECT departDate,departStation FROM " . DB_PREFIX . "bus_query WHERE 1 $condition GROUP BY departDate,departStation" .$orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['departDate'] = date('Y-m-d',$r['departDate']);
			$info[] = $r;
		}
		return $info;
	}
	/*
	public function showdetail($condition,$orderby,$limit)
	{
		$sql = "SELECT id,departDate,busCode,departTime,departStation,arriveStation,terminalStation,takeTime,seats,busLevel,remainTickets,startStation,fullPrice,halfPrice,verifyMessage,mileages,arriveTime FROM " . DB_PREFIX . "bus_query WHERE 1 " .$condition. $orderby . $limit;
		$q = $this->db->query($sql);
			$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['departDate'] = date('Y-m-d',$r['departDate']);
			$r['departTime'] = date('H:i',$r['departTime']);
			$r['arriveTime'] = date('H:i',$r['arriveTime']);
			$info[] = $r;
		}
		return $info;
	}
	public function detail($condition)
	{
		$sql = "SELECT id,departDate,busCode,departTime,departStation,arriveStation,terminalStation,takeTime,seats,busLevel,remainTickets,startStation,fullPrice,halfPrice,verifyMessage,mileages,arriveTime FROM " . DB_PREFIX . "bus_query WHERE 1 " . $condition;
		$q = $this->db->query($sql);
			$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['departDate'] = date('Y-m-d',$r['departDate']);
			$r['departTime'] = date('H:i',$r['departTime']);
			$r['arriveTime'] = date('H:i',$r['arriveTime']);
			$info[] = $r;
		}
		return $info;
	}
	*/
	public function copydetail($condition)
	{
		$sql = "SELECT type,departDate,busCode,departTime,departStation,arriveStation,terminalStation,takeTime,seats,busLevel,remainTickets,startStation,fullPrice,halfPrice,verifyMessage,mileages,arriveTime FROM " . DB_PREFIX . "bus_query WHERE 1 " . $condition;
		$q = $this->db->query($sql);
			$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['departDate'] = date('Y-m-d',$r['departDate']);
			$r['departTime'] = date('H:i',$r['departTime']);
			$r['arriveTime'] = date('H:i',$r['arriveTime']);
			$info[] = $r;
		}
		return $info;
	}
		
	public function copy($condition)
	{
		$ret = $this->copydetail($condition);
		foreach ($ret as $key =>$val)
		{
			$ret[$key]['departDate'] = intval(strtotime($ret[$key]['departDate'])+24*3600);
			$ret[$key]['departTime'] = intval(strtotime($ret[$key]['departTime'])+24*3600);
			$ret[$key]['arriveTime'] = intval(strtotime($ret[$key]['arriveTime'])+24*3600);
			if($this->copydetail($this->where($ret[$key])))
			{
				$rets[] = $ret[$key];
				unset($ret[$key]);
			}
			else $ret[$key]['create_time']= TIMENOW;
		}
		if($ret&&is_array($ret))
		{
		 foreach ($ret as $key=>$val)
		 {
	   	   $rets[]=$this->create($ret[$key]);
		 }
		}
		else {
			return $rets;//返回数据原因为如果数据存在，则认为复制成功
		}
	   return $rets;
	}
	
	private function where($idsArr,$paramType = array())
	{		
		if (is_array($idsArr))
		{			
			foreach ($idsArr as $key => $val)
			{				
				if (is_array($val))
				{
					$Idcount = count($val);
					if($Idcount>1)
					{
					 $where .= ' AND ' . $key . ' in (\'' .implode("','", $val ) . '\')';
					}
					else if ($Idcount == 1)
					{
						 $where .= ' AND ' . $key . ' = \'' . $val[0] . '\'';
					}
				}
				elseif($paramType[$key]['fuzzy'])
				{
					$where .= ' AND '.$key.' LIKE \'%' . $val . '%\'';
				}
				elseif($paramType[$key]['math'])
				{
					$where .= ' AND ' . $key . $paramType[$key]['math'].' \'' . $val . '\'';
				}
				elseif (is_int($val) || is_float($val)||is_numeric($val))
				{
					$where .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val)&&(stripos($val, ',')!==false))
				{
					$where .= ' AND ' . $key . ' in (' . $val . ')';
				}
				elseif(is_string($val))
				{
					$where .= ' AND ' . $key . ' = \'' . $val . '\'';
				}
			}
		}
		elseif (is_string($idsArr))
		{
			$where .= $idsArr;
		}
		return $where;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		$sql = " INSERT INTO " . DB_PREFIX . "bus_query SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$data['id'] = $vid;
		return $data;
	}
	/*
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "bus_query WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "bus_query SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	*/
	public function delete($condition)
	{
		if(!$condition)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "bus_query WHERE 1".$condition;
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "bus_query WHERE 1".$condition;
		$this->db->query($sql);
		return $pre_data;
	}
	public function count($condition = '')
	{
		$sql = "select count(*) as total FROM (SELECT departDate,departStation FROM " . DB_PREFIX . "bus_query WHERE 1 $condition GROUP BY departDate,departStation" . ' ) AS count';
		$total = $this->db->query_first($sql);
		return $total;
	}
	public function date($date)//时间戳转换
	{
		$datearray=explode(',', $date);
		if(is_array($datearray))
		foreach ($datearray as $key=>$value)
		{
			$datearray[$key]=strtotime($value);
		}
		$re=implode(',', $datearray);
		return $re;
	}
}
