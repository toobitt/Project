<?php

class  vodCopyright extends InitFrm
{
	private $condition = array();
	private $limit = '';
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
	
	/*设置获得的个数,以及获取的范围*/
	public function fetch_num($offset,$count)
	{
		$this->limit =  "  limit {$offset}, {$count}";
	}
	
	public function show()
	{
		if ($this->condition)
		{
			$condition = ' WHERE ' . implode(' AND ', $this->condition);
		}
		else 
		{
			$condition= '';
		}
		$sql = "SELECT * FROM ".DB_PREFIX."vod_copyright{$condition}  ORDER BY 	update_time DESC  ".$this->limit;
		$q = $this->db->query($sql);
		$return = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['update_time'] = date("Y-m-d h:i",$r['update_time']);
			$return[] = $r;
		}
		
		return $return;
	}
	
	public function count()
	{
		if ($this->condition)
		{
			$condition = ' WHERE ' . implode(' AND ', $this->condition);
		}
		$sql = "SELECT count(*) as total FROM ".DB_PREFIX."vod_copyright{$condition}";
		$arr = $this->db->query_first($sql);
		$count = $arr['total'];
		return $count;
	}
}

?>