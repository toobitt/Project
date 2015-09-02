<?php
class mkqueue extends LivcmsFrm
{
	public $queue = array();
	private $siteinfo = array();
	function __construct($siteinfo)
	{
		parent::__construct();
		$this->siteinfo = $siteinfo;
		$this->queue = array();
	}
	function __destruct()
	{
		parent::__destruct();
	}

	function set($id, $type)
	{
		$this->queue[] = array($id, $type);
	}
	function getSiteinfo()
	{
	}
	function build_queue()
	{
		if ($this->queue)
		{
			foreach ($this->queue AS $queue)
			{
				$queuedata = array(
						'siteid' => $this->siteinfo['siteid'],
						'cid' => $queue[0],
						'type' => $queue[1],
						'intime' => time(),
				);
				//fetch_query_sql($queuedata, 'mk_queue', '', DB_PREFIX, 'INSERT IGNORE');//进入生成队列表
				$sql = 'INSERT INTO '.DB_PREFIX.'mk_queue SET ';
				foreach($queuedata as $k=>$v)
				{
					$sql .= "{$k} = '$v',";
				}
				$sql = trim($sql, ',');
				$this->db->query($sql);
			}
		}
	}
	function cancell_queue($cid = array(), $type=0)
	{
		if ($cid && is_array($cid))
		{
			foreach($cid as $mapid=>$info)
			{
				$sql = 'DELETE FROM '.DB_PREFIX.'mk_queue WHERE cid='.intval($mapid).', type='.intval($type).', siteid='.$this->siteinfo['siteid'];
				$this->db->query($sql);
			}
		}
	}
}
?>