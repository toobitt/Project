<?php 
/***************************************************************************

* $Id: member_extension_field.class.php 38583 2014-07-25 03:45:35Z youzhenghuan $

***************************************************************************/
class memberExtensionField extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition = '',$offset = 0 ,$count = 0)
	{
		$limit = $count ? " LIMIT " . $offset . " , " . $count : '';
		$sql = "SELECT extension_field_id,extension_field,extension_field_name,field.extension_sort_id,extension_sort_name,is_unique,type,field.order_id FROM " . DB_PREFIX . "member_extension_field AS field LEFT JOIN ". DB_PREFIX . "member_extension_sort AS sort ON field.extension_sort_id =sort.extension_sort_id";
		$sql.= " WHERE 1 " . $condition;
		$sql .= " ORDER BY order_id DESC".$limit;
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['type_name'] = $this->settings['extension_field_type'][$row['type']]['type_name'];
		 	$row['type'] = 	$this->settings['extension_field_type'][$row['type']]['type'];
			$return[] = $row;
		}
		
		return $return;
	}
	
	public function detail($extension_field_id)
	{
		if(!$extension_field_id)
		{
			$condition = " ORDER BY extension_field_id DESC LIMIT 1 ";
		}
		else
		{
			$condition = " WHERE extension_field_id IN ('" . $extension_field_id ."')";
		}
				
		$sql = "SELECT * FROM " . DB_PREFIX . "member_extension_field " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			return $row;
		}
		return false;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member_extension_field AS field WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "member_extension_field SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		if ($this->db->query($sql))
		{
			$vid = $this->db->insert_id();
			$sql = "UPDATE ".DB_PREFIX."member_extension_field SET order_id = {$vid}  WHERE extension_field_id = {$vid}";
			$this->db->query($sql);
			return $data;
		}
		return false;
	}
	
	public function update($extension_field_id,$data)
	{
		$sql = "UPDATE " . DB_PREFIX . "member_extension_field SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE extension_field_id = '" . $extension_field_id . "'";
		if ($this->db->query($sql))
		{
			return $data;
		}
		return false;
	}
	
	public function delete($extension_field_id)
	{
		$tmp = explode(',', $extension_field_id);
		$extension_field_id = implode("','", $tmp);
		
		$sql = "DELETE FROM " . DB_PREFIX . "member_extension_field WHERE extension_field_id IN ('" . $extension_field_id . "')";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	public function delete_member_info($extension_field_id)
	{
		$tmp = explode(',', $extension_field_id);
		$extension_field_id = implode("','", $tmp);
		$sql = "SELECT extension_field FROM " . DB_PREFIX . "member_extension_field WHERE extension_field_id IN ('" . $extension_field_id . "')";
		$query = $this->db->query($sql);
		$field = array();
		while ($row = $this->db->fetch_array($query))
		{
			$field[] = $row['extension_field'];
		}
		$field_str=trim("'".implode("','", $field )."'");
		if($field_str)
		{
			$where = '';
			if(stripos($field_str, ',')!==false)
			{
				$where = ' AND field IN ( '.$field_str.')';
			}
			else {
				$where = ' AND field = '.$field_str;
			}
			$sql = 'DELETE FROM '.DB_PREFIX.'member_info WHERE 1 '.$where;
			if($where) 
			{
				return $this->db->query($sql);
			}
		}
		return false;
	}
	
	public function get_member_extension_field($condition, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "member_extension_field WHERE 1 " . $condition;		
		$return = $this->db->query_first($sql);
		return $return;
	}
}

?>