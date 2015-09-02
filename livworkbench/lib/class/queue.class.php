<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: queue.class.php 508 2012-01-14 07:28:22Z repheal $
***************************************************************************/
class queue
{	
	private $site;
	private $db;
	private $input;
	function __construct()
	{
		global $_INPUT;
		$this->db = hg_checkDB();
		$this->input = $_INPUT;
	}
	function __destruct()
	{
	}
	function create($pid, $type)
	{
		if(!$pid)
		{
			return false;
		}

		$sql = 'REPLACE INTO '.DB_PREFIX.'queue(pid,type,update_time) values';
		$sql_extra = $space = '';
		if(!empty($pid))
		{
			$pid = explode(',',$pid);
			foreach($pid as $i)
			{
				$sql_extra .= $space . "(" . intval($i) . "," . intval($type) . "," . TIMENOW . ")";
				$space = ',';
			}
			$sql .= $sql_extra;
			$this->db->query($sql);
		}
		return true;
	}

	function delete($pid)
	{	
		if($pid)
		{
			if(is_array($pid))
			{
				$pid = implode(',', $pid);
			}
			$sql = 'DELETE FROM '.DB_PREFIX.'queue WHERE pid IN(' . $pid . ')';
			$this->db->query($sql);
			return true;
		}
		return false;
	}

	function update_time($pid)
	{
		if($pid)
		{
			if(is_array($pid))
			{
				$pid = implode(',', $pid);
			}
			$sql = "UPDATE " . DB_PREFIX . "queue SET update_time=" . TIMENOW . " WHERE pid IN(" . $pid . ")";
			$this->db->query($sql);
		}
	}	
}