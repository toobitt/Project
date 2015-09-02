<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: site.class.php 6931 2012-05-31 07:33:56Z repheal $
***************************************************************************/
class block_sort extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function update($data,$tablename = 'block')
	{
		$sql="UPDATE " . DB_PREFIX . $tablename." SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id=".$data['id'];
		$this->db->query($sql);
	}
	
	public function get_sort_by_id($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."block_sort WHERE id=".$id;
		return $this->db->query_first($sql);
	}
	
	public function get_block_sort($con,$limit)
	{
		$ret = array();
		$sql = "SELECT * FROM ".DB_PREFIX."block_sort WHERE 1 ".$con.$limit;
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[] = $row;
		}
		return $ret;
	}
}

?>