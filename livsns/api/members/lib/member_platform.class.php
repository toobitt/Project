<?php 
/***************************************************************************

* $Id: member_platform.class.php 42600 2014-12-10 03:06:33Z youzhenghuan $

***************************************************************************/
class memberPlatform extends InitFrm
{
	private $selectField = '*';
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition, $offset = 0, $count = 20, $orderby = '')
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$orderby = $orderby ? $orderby : " ORDER BY order_id DESC ";
		
		$sql = "SELECT $this->selectField FROM " . DB_PREFIX . "member_platform ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] && $row['create_time']  = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] && $row['update_time']  = date('Y-m-d H:i:s', $row['update_time']);
			$row['logo_display']&& $row['logo_display'] = unserialize($row['logo_display']);
			$row['logo_login']  && $row['logo_login']	 = unserialize($row['logo_login']);
			$row['limit_version']&&$row['limit_version']	 = maybe_unserialize($row['limit_version']);
			
			$return[] = $row;
		}
		
		return $return;
	}
	
	public function setSelectField($field)
	{
		if($field && is_array($field))
		{
			$this->selectField = implode(',', $field);
		}
		else if($field)
		{
			$this->selectField = $field;
		}
		return $this->selectField;
	}
	
	public function detail($id)
	{
		if(!$id)
		{
			$condition = " ORDER BY id DESC LIMIT 1 ";
		}
		else
		{
			$condition = " WHERE id IN (" . $id .")";
		}
				
		$sql = "SELECT * FROM " . DB_PREFIX . "member_platform " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			$row['create_time']  = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time']  = date('Y-m-d H:i:s', $row['update_time']);
			$row['logo_display'] = unserialize($row['logo_display']);
			$row['logo_login']	 = unserialize($row['logo_login']);
			$row['limit_version']	 = maybe_unserialize($row['limit_version']);
			
			return $row;
		}
		return false;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "member_platform WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "member_platform SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function update($data)
	{
		$sql = "UPDATE " . DB_PREFIX . "member_platform SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = " . $data['id'];
		
		$this->db->query($sql);
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "member_platform WHERE id IN (" . $id . ")";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function get_member_platform_info($condition, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "member_platform WHERE 1 " . $condition;
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if ($row['create_time'])
			{
				$row['create_time']  = date('Y-m-d H:i:s', $row['create_time']);
			}
			
			if ($row['update_time'])
			{
				$row['update_time']  = date('Y-m-d H:i:s', $row['update_time']);
			}
			
			if ($row['logo_display'])
			{
				$row['logo_display'] = unserialize($row['logo_display']);
			}
			
			if ($row['logo_login'])
			{
				$row['logo_login']   = unserialize($row['logo_login']);
			}
			
			$return[] = $row;
		}
		return $return;
	}

	function mark_exists($mark, $id = '') 
	{
		$condition = '';
		
		if ($id)
		{
			$condition .= " AND id NOT IN (" . $id . ")";
		}
		
		$sql = "SELECT id, mark FROM " . DB_PREFIX . "member_platform WHERE mark = '" . $mark . "'" . $condition;
		$data = $this->db->query_first($sql);
		return $data;
	}
	
	/**
	 * 入素材库
	 * Enter description here ...
	 * @param unknown_type $file
	 * @param unknown_type $id
	 */
	public function add_material($file, $id)
	{
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$mMaterial = new material();
		if (!$mMaterial)
		{
			return false;
		}
		
		$files['Filedata'] = $file;
		$material = $mMaterial->addMaterial($files, $id);
		$return = array();
		if (!empty($material))
		{
			$return['host'] 	= $material['host'];
			$return['dir'] 		= $material['dir'];
			$return['filepath'] = $material['filepath'];
			$return['filename'] = $material['filename'];
		}
		
		return $return;
	}
}

?>