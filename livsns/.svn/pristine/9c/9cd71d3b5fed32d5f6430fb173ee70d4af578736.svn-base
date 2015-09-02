<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: dvr_checked_auto.php 19895 2013-04-08 02:42:01Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','dvr_clear');
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR.'global.php');
require(CUR_CONF_PATH . 'lib/functions.php');
class dvr_clear extends cronBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '清理时移数据',	 
			'brief' => '',
			'space' => '3600',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	public function clear()
	{
		$sql  = "SELECT cs.stream_name, cs.bitrate, c.id,c.code,c.name,c.time_shift,c.status,sc.ts_host FROM " . DB_PREFIX . "channel_stream cs LEFT JOIN " . DB_PREFIX . "channel c ON cs.channel_id = c.id LEFT JOIN " . DB_PREFIX . "server_config sc ON sc.id=c.server_id WHERE c.status=1 AND sc.type='nginx' ORDER BY cs.order_id ASC";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			if (!$r['time_shift'])
			{
				$r['time_shift'] = 1;
			}
			$channel_stream = $r['code'] . '_' .$r['stream_name'];
			$sql = 'DELETE FROM ' . DB_PREFIX . "dvr WHERE stream_name='$channel_stream' AND start_time<" . (time() * 1000 - ($r['time_shift'] * 3600000));
			$this->db->query($sql);
			$sql = 'DELETE FROM ' . DB_PREFIX . "dvr1 WHERE stream_name='$channel_stream' AND start_time<" . (time() * 1000 - ($r['time_shift'] * 3600000));
			$this->db->query($sql);
		}
	}
}

$out = new dvr_clear();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'clear';
}
$out->$action();
?>