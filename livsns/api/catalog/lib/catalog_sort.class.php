<?php 

class catalogsort extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail($condition = '')
	{
				
		$sql = "SELECT * FROM " . DB_PREFIX . "field_sort WHERE 1" . $condition;
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			return $row;
		}
		return false;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "field_sort WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "field_sort SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		if ($this->db->query($sql))
		{
			$vid = $this->db->insert_id();
			$sql = " UPDATE ".DB_PREFIX."field_sort SET order_id = {$vid}  WHERE id = {$vid}";
			$this->db->query($sql);
		return $data;
		}
		return false;
	}
	
	public function update($catalog_sort_id,$data)
	{
		$sql = "UPDATE " . DB_PREFIX . "field_sort SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = '" . $catalog_sort_id . "'";

		if ($this->db->query($sql))
		{
			return $data;
		}
		return false;
	}
	
	public function delete($catalog_sort_ids)
	{

		$sql = "DELETE FROM " . DB_PREFIX . "field_sort WHERE id IN (" . $catalog_sort_ids . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function get_member_catalog_sort($condition, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "field_sort WHERE 1 " . $condition;		
		$return = $this->db->query_first($sql);
		return $return;
	}
}

?>