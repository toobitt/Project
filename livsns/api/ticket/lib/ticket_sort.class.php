<?php
class ticketSort extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function sort($id=0,$type='')
	{
		$sql = 'SELECT id,name FROM '.DB_PREFIX.'sort WHERE 1 ';
		
		if($id && $type)
		{
			$sql .= ' AND id IN (' . $id . ')';
		}
		else 
		{
			$sql .= ' AND fid = ' . intval($id);
		}
		$query = $this->db->query($sql);
		$k = array();
		while(!false == ($row = $this->db->fetch_array($query)))
		{
			$k[$row['id']] = $row;
		}
		return $k;
	}
}