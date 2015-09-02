<?php
class qdxq extends InitFrm
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

	public function show($condition = '',$offset,$count)
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$sql = "SELECT * FROM " . DB_PREFIX . "sign_emot ";
		$sql.= " WHERE 1 " . $condition;
		$sql .= " ORDER BY order_id DESC".$limit;
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if(!empty($row['img']))
			{
				$row['img']=maybe_unserialize($row['img']);
			}
			if($row['description'])
			{
				$row['description']=html_entity_decode($row['description']);
			}
			$return[] = $row;
		}

		return $return;
	}

	public function detail($id)//admin参数为了区分前后台调用不同的方法使用
	{

		$condition = " WHERE id = ".intval($id);
		$sql = "SELECT * FROM " . DB_PREFIX . "sign_emot " . $condition;
		$row = $this->db->query_first($sql);
		if($row['img'])
		{
			$row['img']=maybe_unserialize($row['img']);
		}
		if($row['description'])
		{
			$row['description']=html_entity_decode($row['description']);
		}
		if(is_array($row) && $row)
		{
			return $row;
		}
		return false;
	}

}

?>