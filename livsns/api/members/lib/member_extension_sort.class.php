<?php 
/***************************************************************************

* $Id: member_extension_field.class.php 26794 2013-08-01 04:34:02Z lijiaying $

***************************************************************************/
class mmemberextensionsort extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition = '',$offset,$count)
	{
		if($count){
			$limit 	 = " LIMIT " . $offset . " , " . $count;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "member_extension_sort ";
		$sql.= " WHERE 1 " . $condition;
		$sql .= " ORDER BY order_id DESC".$limit;
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		
		return $return;
	}
	
	public function detail($extension_sort_id)
	{
		if(!$extension_sort_id)
		{
			$condition = " ORDER BY extension_sort DESC LIMIT 1 ";
		}
		else
		{
			$condition = " WHERE extension_sort_id IN ('" . $extension_sort_id ."')";
		}
				
		$sql = "SELECT * FROM " . DB_PREFIX . "member_extension_sort " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			return $row;
		}
		return false;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member_extension_sort WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "member_extension_sort SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		if ($this->db->query($sql))
		{
			$vid = $this->db->insert_id();
			$sql = " UPDATE ".DB_PREFIX."member_extension_sort SET order_id = {$vid}  WHERE extension_sort_id = {$vid}";
			$this->db->query($sql);
			return $data;
		}
		return false;
	}
	
	public function update($extension_sort_id,$data)
	{
		$sql = "UPDATE " . DB_PREFIX . "member_extension_sort SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE extension_sort_id = '" . $extension_sort_id . "'";
		
		if ($this->db->query($sql))
		{
			return $data;
		}
		return false;
	}
	
	public function delete($extension_sort_id)
	{
		$tmp = explode(',', $extension_sort_id);
		$extension_sort_id = implode("','", $tmp);
		
		$sql = "DELETE FROM " . DB_PREFIX . "member_extension_sort WHERE extension_sort_id IN ('" . $extension_sort_id . "')";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function get_member_extension_sort($condition, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "member_extension_sort WHERE 1 " . $condition;		
		$return = $this->db->query_first($sql);
		return $return;
	}
}

?>