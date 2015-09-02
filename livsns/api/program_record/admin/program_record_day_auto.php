<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_auto.php 6444 2012-04-18 05:18:47Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_record');
class programRecordDayAutoApi extends cronBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function tips($str,$pub_time)
	{
		echo $str . "----------------------------" . date("Y-m-d H:i:s",$pub_time) . '<br/>';
	}

	/**
	 * 显示录播节目单
	 */
	function show()
	{
		$sql = "SELECT week_day, id, start_time, toff
					FROM " . DB_PREFIX . "program_record 
				WHERE (start_time + toff) < " . (TIMENOW - 1200);
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$week_day = unserialize($row['week_day']);
			if (is_array($week_day) && $week_day)
			{				
				$start_time = hg_update_time($row['start_time'],$week_day);
				$is_record = 0;
				$sql_update = "UPDATE " . DB_PREFIX . "program_record SET is_record=" . $is_record . ",start_time=" . $start_time . " WHERE id=" . $row['id'];
				$this->db->query($sql_update);
			}
			else
			{
				$sql_update = "UPDATE " . DB_PREFIX . "program_record SET is_record=2 WHERE id=" . $row['id'];
				$this->db->query($sql_update);
			}	
		}
	}
}

$out = new programRecordDayAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>