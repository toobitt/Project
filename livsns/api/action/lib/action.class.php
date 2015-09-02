<?php
class action extends InitFrm
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function insert($table,$data)
	{
		$result = false;
		if(is_array($data))
		{
			$sql = $sp = "";
			$sql .= "insert into " . DB_PREFIX . $table . " set ";
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
	
	public function update($table, $data = array(), $conds = array(), $ck = array())
	{
		$result = false;
		if($data)
		{
			$sql = $sp = '';
			$sql .= "update " . DB_PREFIX . $table . " set ";
			foreach ($data as $k => $v)
			{
				$sql .= $sp . $k . "=";
				if($ck[$k])
				{
					$sql .= $k . "+";
				}
				$sql .= "'" . $v . "'";
				$sp = ',';
			}
			$sql .= " where 1";
			if($conds)
			{
				foreach ($conds as $k => $v)
				{
					if(strpos($v, ','))
					{
						$sql .= " and " . $k . " in (" . $v . ")";
					}
					else 
					{
						$sql .= " and " . $k . " = '" . $v . "'";
					}
				}
			}
			$result = $this->db->query($sql);
		}	
		return $result;
	}
	
	public function get($table, $vals = '*', $conds = array(),  $offset = 0, $count = 1,  $sequence = array())
	{
		$result = false;
		$sql = " select ". $vals ." from " . DB_PREFIX . $table." ";
		$sql .= " where 1 ";
		
		if($conds)
		{
			foreach($conds as $k =>$v)
			{
				if(strpos($v, ','))
				{
					$sql .= " and " . $k . " in (" . $v . ")";
				}
				else 
				{
					$sql .= " and " . $k . " = '" . $v . "'";
				}
			}
		}
		if($sequence)
		{
			$sql .= " ORDER BY ";$sp = '';
			foreach ($sequence as $k=>$v)
			{
				$sql .= $sp . " " . $k . " " . $v;
				$sp = ',';
			}
		}
		if($count > 0)
		{
			$sql .= ' LIMIT ' . $offset . ' , ' . $count;
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
	public function delete($table, $conds = array())
	{
		$result = false;
		if($conds)
		{
			$sql = '';
			$sql .= "delete from " . DB_PREFIX .  $table . " ";
			
			$sql .= " where 1 ";
			if($conds)
			{
				foreach ($conds as $k => $v)
				{
					if(strpos($v, ','))
					{
						$sql .= " and " . $k . " in (" . $v . ")";
					}
					else 
					{
						$sql .= " and " . $k . " = '" . $v . "'";
					}
				}
			}
			$result = $this->db->query($sql);
		}
		return $result;
	}
}
?>