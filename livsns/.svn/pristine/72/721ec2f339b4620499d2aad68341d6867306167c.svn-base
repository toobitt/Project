<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record.php 7586 2012-07-05 09:40:56Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_library');//模块标识

class libraryApi extends adminReadBase
{
	private $obj;
	
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/library.class.php');
		$this->obj = new library();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->obj);
	}
	
	public function show()
	{
		if($this->mNeedCheckIn && !$this->prms['show'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$condition = $this->get_condition();
		/*
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = " LIMIT " . $offset . " , " . $count;
		$sql = "select p.*,c.name as channel,c.stream_state from " . DB_PREFIX . "program_record p left join " . DB_PREFIX . "channel c on c.id=p.channel_id ";
		$sql .= " where 1 " . $condition . " ORDER BY p.is_record ASC,p.start_time ASC " . $data_limit;
		$q = $this->db->query($sql);
		$week_day_arr = array('1' => '一', '2' => '二', '3' => '三', '4' => '四', '5' => '五', '6' => '六', '7' => '日');
		
		include_once(ROOT_PATH . 'lib/class/livmedia.class.php');
		$livmedia = new livmedia();
		
		include_once(ROOT_PATH . 'lib/class/logs.class.php');
		$obj_logs = new logs();
		
		*/
		$this->addItem();
		$this->output();
	}
	
	public function count()
	{
	
	}
	
	public function detail()
	{
		
	}
	
	public function index()
	{
		
	}
	/**
	 * 获取条件
	 */
	private function get_condition()
	{
		$condition = '';
		return $condition;
	}
}

$out = new libraryApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>