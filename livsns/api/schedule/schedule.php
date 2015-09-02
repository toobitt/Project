<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: schedule.php 32589 2013-12-11 08:45:19Z zhuld $
***************************************************************************/
define('MOD_UNIQUEID','schedule');
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class scheduleApi extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		
	}
	
	public function detail()
	{
		
	}
	
	public function count()
	{
		
	}
	
	private function get_condition()
	{
		
	}
	//wowza中创建的串联单层入库
	public function build_wowza_schedule()
	{
		$channel_id = $this->input['channel_id'];
		$stream_id  = $this->input['stream_id'];
		$output_id  = $this->input['output_id'];
		if(!$channel_id || !$stream_id || !$output_id)
		{
			$message = 'error';
		}
		else
		{
			$sql = 'REPLACE INTO ' . DB_PREFIX . 'channel_server VALUE('.$channel_id.','.$output_id.', '.$stream_id.')';
			$this->db->query_first($sql);
			$message = 'success';
		}
		$this->addItem($message);
		$this->output();
	}
	public function cancell_wowza_schedule()
	{
		$channel_id = $this->input['channel_id'];
		$this->db->query('DELETE FROM ' . DB_PREFIX . 'channel_server WHERE channel_id IN('.$channel_id.')');
		$this->addItem('success');
		$this->output();
	}
}

$out = new scheduleApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>