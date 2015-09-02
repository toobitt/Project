<?php
/***************************************************************************

* $Id: group 26794 2013-08-01 04:34:02Z purview $

***************************************************************************/
class grade extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->Members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition,$offset,$count,$field = '*')
	{
		if($count){
			$limit 	 = " LIMIT " . $offset . " , " . $count;
		}
		$sql = "SELECT $field FROM " . DB_PREFIX . "grade ";
		$sql.= " WHERE 1 " . $condition;
		$sql .= " ORDER BY digital DESC".$limit;
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if(!empty($row['icon']))
			{
				$row['icon']=maybe_unserialize($row['icon']);
			}elseif($row['graicon']) {
				$row['graicon'] = maybe_unserialize($row['graicon']);
			}
			if($row['digital'])
			{
				$row['digitalname'] = (defined('GRADEDIGITAL_PREFIX')?GRADEDIGITAL_PREFIX:'LV.').$row['digital'];
			}
			$return[] = $row;
		}

		return $return;
	}

	public function detail($id,$field = '*')
	{

		$condition = " WHERE id = ".intval($id);
		$sql = "SELECT $field FROM " . DB_PREFIX . "grade " . $condition;
		$row = $this->db->query_first($sql);
		if($row['icon'])
		{
			$row['icon']=maybe_unserialize($row['icon']);
		}
		if($row['digital'])
		{
			$row['digitalname'] = (defined('GRADEDIGITAL_PREFIX')?GRADEDIGITAL_PREFIX:'LV.').$row['digital'];
		}
		if(is_array($row) && $row)
		{
			return $row;
		}
		return false;
	}

}

?>