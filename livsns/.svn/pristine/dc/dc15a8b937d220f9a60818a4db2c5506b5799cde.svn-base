<?php
class signset extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->Members=new members();
		$this->membersql = new membersql();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($field='*')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "sign_set";
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if($row['credits'])
			{
				$row['credits']=maybe_unserialize($row['credits']);
			}
					if($row['limit_time'])
			{
				$row['limit_time']=maybe_unserialize($row['limit_time']);
			}
			$return[] = $row;
		}

		return $return;
	}

	public function detail($id)
	{

		$sql = "SELECT * FROM " . DB_PREFIX . "sign_set WHERE id = " . $id;
		$row = $this->db->query_first($sql);
		if(is_array($row) && $row)
		{
			if($row['credits'])
			{
				$row['credits']=maybe_unserialize($row['credits']);
			}
			if($row['limit_time'])
			{
				$row['limit_time']=maybe_unserialize($row['limit_time']);
			}
			return $row;
		}
		return false;
	}

	/**
	 *
	 * 开关 ...
	 */
	public function display($ids, $opened)
	{
		$sql = 'UPDATE '.DB_PREFIX.'sign_set SET is_on = '.$opened.' WHERE id = '.$ids;
		$this->db->query($sql);
		$arr = array(
			'id'=>$ids,
			'opened'=>$opened,
		);
		return $arr;
	}
}

?>