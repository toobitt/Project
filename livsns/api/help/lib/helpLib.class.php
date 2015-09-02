<?php
class helpLib extends InitFrm
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}

	//insert
	public function insert($table = array(), $data = array())
	{
		$result = false;
		if(is_array($data) && $data)
		{
			$sql = $sp = "";
			$sql .= "insert into " ;
			if($table && is_array($table))
			{
				$sp = '';
				foreach($table as $k => $v)
				{
					$sql .= $sp . DB_PREFIX . $k . " " . $v;
					$sp = ',';
				}
				
			}
			else 
			{
				$sql .= DB_PREFIX . $table;
			}
			$sql .= " set ";
			foreach ($data as $k =>$v)
			{
				$sql .= $sp . $k ."='" . $v . "'";
				$sp = ','; 
			}
			$this->db->query($sql);
			$result = $this->db->insert_id();
		}
		return $result;
	}
	public function update($table = array(), $data = array(), $where = array(), $other = array(), $otherWhere = array())
	{
		$result = false;
		if($data && is_array($data))
		{
			$sql = '';
			$sql .= 'update ';
			if($table && is_array($table))
			{
				$sp = '';
				foreach($table as $k => $v)
				{
					$sql .= $sp . DB_PREFIX . $k . " " . $v;
					$sp = ',';
				}
				
			}
			else 
			{
				$sql .= DB_PREFIX . $table;
			}
			$sql .= " set ";
			$sp = '';
			foreach ($data as $k => $v)
			{
				$sql .= $sp . $k;
				$sql .= ($other[$k]) ? $other[$k] : ' = ';
				$sql .= is_numeric($v) ? $v : "'" . $v . "'";
				$sp = ',';
			}
			$sql .= " where 1 ";
			if($where)
			{
				foreach($where as $k => $v)
				{
					if(strpos($v, ','))
					{
						//给$v字符串增加
						$v = "'" .str_replace(",", "','", $v) . "'";
						$sql .= " and " . $k . " in (" . $v . ")";
					}
					else 
					{
						$sql .= " and " . $k ;
						$sql .= ($otherWhere[$k]) ? $otherWhere[$k] : ' = ';
						if(is_numeric($v))
						{
							$sql .= "" . $v . "";
						}
						else 
						{
							$sql .= "'" . $v . "'";
						}
						
					}
				}
			}
			$result = $this->db->query($sql);
		}
		return $result;
	}
	//获取
	public function get($table = array(), $vals = array(), $where = array(),  $offset = 0, $count = 1, $order = array(), $group = array(), $func = array(), $otherWhere = array())
	{
		$result = false;
		$sql = " select ";
		if($vals && is_array($vals))
		{
			$sql .= implode(',', $vals);
		}
		else 
		{
			$sql .= $vals;
		}
		$sql .= " from ";
		if($table && is_array($table))
		{
			$sp = '';
			foreach($table as $k => $v)
			{
				$sql .= $sp . DB_PREFIX . $k . " " . $v;
				$sp = ',';
			}
		}
		else 
		{
			$sql .= DB_PREFIX . $table;
		}
		$sql .= " where 1 ";
		if($func)
		{
			foreach($func as $k =>$v)
			{
				$sql .= " and " . $k . " " . $v . " ";

			}
		}
		if($where)
		{
			foreach($where as $k =>$v)
			{
				if(strpos($v, ','))
				{
					//给$v字符串增加
					$v = "'" .str_replace(",", "','", $v) . "'";
					$sql .= " and " . $k . " in (" . $v . ")";
				}
				else 
				{
					$sql .= " and " . $k ;
					$sql .= ($otherWhere[$k]) ? $otherWhere[$k] : '=';
					if(is_numeric($v))
					{
						$sql .= "" . $v . "";
					}
					else 
					{
						$sql .= "'" . $v . "'";
					}
					
				}
			}
		}

		if($group)
		{
			$sql .= " GROUP BY ";$sp = '';
			foreach ($group as $k => $v)
			{
				$sql .= $sp . " " . $k . " " . $v;
				$sp = ',';
			}
		}
		
		if($order)
		{
			$sql .= " ORDER BY ";$sp = '';
			foreach ($order as $k => $v)
			{
				$sql .= $sp . " " . $k . " " . $v;
				$sp = ',';
			}
		}
		//count = -1 取全部
		if($count >= 0)
		{
			$sql .= ' LIMIT ';
			$sql .= ($offset)  ? $offset . ' , '  : '';
			$sql .= $count;
		}
		
		$query = $this->db->query($sql);
		
		if($count != 1)
		{
			while($row = $this->db->fetch_array($query))
			{
				$result[] = $row;
			}
		}
		else 
		{
			if(strpos($vals, ',') || $vals =='*')
			{
				while($row = $this->db->fetch_array($query))
				{
					$result = $row;
				}
			}
			else 
			{
				while($row = $this->db->fetch_row($query))
				{
					$result = $row['0'];
				}
			}
			
		}

		return $result;
	}
	
	public function delete($table = array(), $where = array(), $otherWhere = array())
	{
		$result = false;
		if($table)
		{
			$sql = '';
			$sql .= "delete from ";
			if(is_array($table))
			{
				$sp = '';
				foreach($table as $k => $v)
				{
					$sql .= $sp . DB_PREFIX . $k . " " . $v;
					$sp = ',';
				}
			}
			else 
			{
				$sql .= DB_PREFIX . $table;
			}
			$sql .= " where 1 ";
			if($where)
			{
				foreach($where as $k =>$v)
				{
					if(strpos($v, ','))
					{
						//给$v字符串增加
						$v = "'" .str_replace(",", "','", $v) . "'";
						$sql .= " and " . $k . " in (" . $v . ")";
					}
					else 
					{
						$sql .= " and " . $k ;
						$sql .= ($otherWhere[$k]) ? $otherWhere[$k] : '=';
						if(is_numeric($v))
						{
							$sql .= "" . $v . "";
						}
						else 
						{
							$sql .= "'" . $v . "'";
						}
						
					}
				}
			}
			$result = $this->db->query($sql);
		}
		return $result;
	}
}