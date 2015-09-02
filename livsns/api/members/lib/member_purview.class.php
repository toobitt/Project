<?php 
/***************************************************************************

* $Id: purview 26794 2013-08-01 04:34:02Z ayou $

***************************************************************************/
class purview extends InitFrm
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
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$sql = "SELECT * FROM " . DB_PREFIX . "purview ";
		$sql.= " WHERE 1 " . $condition.$limit;
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		
		return $return;
	}
	
	public function detail($id)
	{
		if(empty($id))
		{
			return false;
		}
		else
		{
			$condition = " WHERE id =".$id;
		}
				
		$sql = "SELECT * FROM " . DB_PREFIX . "purview " . $condition;		
		$row = $this->db->query_first($sql);
		if(is_array($row) && $row)
		{
			return $row;
		}
		return false;
	}
}

?>