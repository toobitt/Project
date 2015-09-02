<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: dvr_checked_auto.php 19895 2013-04-08 02:42:01Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','clear_m3u8');
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR.'global.php');
require(CUR_CONF_PATH . 'lib/functions.php');
class clear_m3u8 extends cronBase
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
			'name' => '清理静态m3u8',	 
			'brief' => '',
			'space' => '600',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	public function clear()
	{
		$sql  = "SELECT c.code FROM " . DB_PREFIX . "channel c LEFT JOIN " . DB_PREFIX . "server_config sc ON sc.id=c.server_id WHERE sc.type='nginx'";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			hg_clear_m3u8(DATA_DIR . $r['code']);
		}
	}
}

$out = new clear_m3u8();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'clear';
}
$out->$action();
?>