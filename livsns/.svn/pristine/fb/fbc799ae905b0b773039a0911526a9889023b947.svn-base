<?php
/*
 * 扩展字段类
 */
class extendField extends InitFrm
{
	private $db1;
	public function __construct()
	{
		parent::__construct();
		$this->db1 = hg_ConnectDB();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "extendfield AS field";
		$sql.= " WHERE 1 " . $condition;		
		$q = $this->db1->query($sql);	
		$return = array();
		while ($row = $this->db1->fetch_array($q))
		{
			$row['type_name'] = $this->settings['extendFieldType'][$row['type']]['type_name'];
		 	$row['type'] = 	$this->settings['extendFieldType'][$row['type']]['type'];
			$return[] = $row;
		}
		return $return;
	}
	
	public function detail($fieldId)
	{
		if($fieldId)
		{
			$condition = " WHERE id = '" . intval($fieldId) ."'";
		}
		else{
			return array();
		}
		$row = array();		
		$sql = "SELECT * FROM " . DB_PREFIX . "extendfield " . $condition;		
		$row = $this->db1->query_first($sql);
		if(is_array($row) && $row)
		{
			return $row;
		}
		return array();
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "extendfield WHERE 1 " . $condition;
		$return = $this->db1->query_first($sql);
		return $return;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "extendfield SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}		
		if ($this->db1->query($sql))
		{
			$vid = $this->db1->insert_id();
			$this->db1->update_data(array('order_id'=>$vid), 'extendfield','id = '.$vid);
			return $data;
		}
		return false;
	}
	
	public function update($fieldId,$data)
	{
		$sql = "UPDATE " . DB_PREFIX . "extendfield SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}	
		$sql .= " WHERE id = '" . $fieldId . "'";
		if ($this->db1->query($sql))
		{
			return $data;
		}
		return false;
	}
	
	public function delete($_fieldId)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "extendfield WHERE 1".$this->where_id($_fieldId);	
		if ($this->db1->query($sql))
		{
			return true;
		}
		return false;
	}
	public function where_id($_fieldId,$op = 'AND')
	{
		$fieldId = array();
		$fieldId_str ='';
		$where = '';
		if(empty($_fieldId))
		{
			return $where;
		}
		if(($_fieldId&&is_string($_fieldId)&&(stripos($_fieldId, ',')!==false))||is_numeric($_fieldId)&&!is_array($_fieldId))
		{
			$_fieldId = explode(',', $_fieldId);
		}elseif (is_array($_fieldId))
		{
			$fieldId = $_fieldId;
		}
		if ($fieldId&&is_array($fieldId))
		{
			$fieldId=array_filter($fieldId,"clean_array_null");
			$fieldId=array_filter($fieldId,"clean_array_num_max0");
			$fieldId_str = implode(',', $fieldId);
			if($fieldId_str&&count($fieldId)>1)
			{
				$where=' '.$op.' id IN('.$fieldId_str.')';
			}
			else $where=' '.$op.' id='.($fieldId_str);
		}
		return $where;
	}
	public function deleteExtendInfo($_fieldid)
	{
		include CUR_CONF_PATH . 'lib/extendInfo.class.php';
		$extendInfo = new extendInfo();
		$sql = "SELECT field FROM " . DB_PREFIX . "extendfield WHERE 1".$this->where_id($_fieldid);
		$query = $this->db1->query($sql);
		$field = array();
		while ($row = $this->db1->fetch_array($query))
		{
			$field['field'][] = $row['field'];
		}
		$extendInfo->delete($field);
	}
	
	public function get_extendfield($condition, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "extendfield WHERE 1 " . $condition;		
		$return = $this->db1->query_first($sql);
		return $return;
	}
}
?>