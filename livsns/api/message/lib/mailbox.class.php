<?php

class MessageModule extends BaseFrm
{
	private $condition = array();
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function set($condition)
	{
		if ($condition)
		{
			$this->condition[] = $condition;
		}
	}

	public function reset()
	{
		$this->condition = array();
	}

	public function fetch()
	{
		if ($this->condition)
		{
			$condition = ' WHERE ' . implode(' AND ', $this->condition);
		}
		$sql = "SELECT * FROM ".DB_PREFIX."mailbox_type{$condition}";
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r = array(
				'id' => $r['id'],
				'name' => $r['type_name'],
				'fid' => $r['fid'],
				'depth'  => 1,
				'is_last'  => 1 
				);
			$ret[$r['id']] = $r;
		}
		return $ret;
	}
}

?>