<?php

class vodSort extends InitFrm
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
		$sql = "SELECT * FROM ".DB_PREFIX."vod_sort{$condition}";
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r = array(
				'id' => $r['id'],
				'name' => $r['sort_name'],
				'fid' => $r['father'],
				'depth'  => 1,
				'is_last'  => 1 ,
				'attr'  =>  $this->settings['video_upload_type_attr'][$r['father']]);
			$ret[$r['id']] = $r;
		}
		return $ret;
	}

	public function count()
	{
		if ($this->condition)
		{
			$condition = ' WHERE ' . implode(' AND ', $this->condition);
		}
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vod_sort";
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);
		return $r;
	}
}

?>