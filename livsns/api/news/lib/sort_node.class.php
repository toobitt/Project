<?php

class sortNode extends BaseFrm
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

	public function fetch1()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "sort  where sort_level=1";
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ret[$r['id']] = $r['name'];
		}
		return $ret;
	}

	public function fetch2_2()
	{
		if ($this->condition)
		{
			$condition = ' WHERE ' . implode(' AND ', $this->condition) . ' AND ';
		}
		else
		{
			$condition=' WHERE ';
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "sort {$condition} sort_level=2";
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ret[$r['id']] = $r['name'];
		}
		return $ret;
	}

	public function fetch2()
	{
		if ($this->condition)
		{
			$condition = ' WHERE ' . implode(' AND ', $this->condition) . ' AND ';
		}
		else
		{
			$condition=' WHERE ';
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "sort {$condition} sort_level=2";
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r = array(
				'id' => $r['id'],
				'name' => $r['name'],
				'fid' => $r['pid'],
				'depth'  => 1,
				'is_last'  => 1 ,
				'attr'  =>  $this->settings['news_sort_type_attr'][$r['pid']],
			);
			$ret[$r['id']] = $r;
		}
		return $ret;
	}

	public function fetch3()
	{
		if ($this->condition)
		{
			$condition = ' WHERE ' . implode(' AND ', $this->condition) . ' AND ';
		}
		else
		{
			$condition=' WHERE ';
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "sort {$condition} sort_level=3";
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r = array(
				'id' => $r['id'],
				'name' => $r['name'],
				'fid' => $r['pid'],
				'depth'  => 1,
				'is_last'  => 1 ,
				'attr'  =>  $this->settings['news_sort_type_attr'][$r['pid']]);
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