<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: dvr_checked_log.php 19895 2013-04-08 02:42:01Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','dvr_checked_log');
require('global.php');
class dvrCheckedLogApi extends adminReadBase
{
	private $mDvrCheckedLog;
	function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/dvr_checked_log.class.php';
		$this->mDvrCheckedLog = new dvrCheckedLog();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$condition  = $this->get_condition();
		$offset		= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count		= $this->input['count'] ? intval($this->input['count']) : 20;
		
		$info = $this->mDvrCheckedLog->show($condition, $offset, $count);
		if (!empty($info))
		{
			foreach ($info AS $k => $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mDvrCheckedLog->count($condition);
		echo json_encode($info);
	}
	
	private function get_condition()
	{
		$condition = '';
		//频道名
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= " AND name LIKE \"%" . trim(urldecode($this->input['k'])) . "%\"";
		}

		//频道id
		if (isset($this->input['channel_id']) && $this->input['channel_id'])
		{
			$condition .= " AND channel_id = " . intval($this->input['channel_id']);
		}
		
		//服务器id
		if (isset($this->input['server_id']) && $this->input['server_id'])
		{
			$condition .= " AND server_id = " . intval($this->input['server_id']);
		}
		
		//创建时间
		if(isset($this->input['start_time']) && !empty($this->input['start_time']))
		{
			$start_time = strtotime(trim($this->input['start_time']));
			$condition .= " AND create_time >= '" . $start_time . "'";
		}
		
		if(isset($this->input['end_time']) && !empty($this->input['end_time']))
		{
			$end_time = strtotime(trim($this->input['end_time']));
			$condition .= " AND create_time <= '" . $end_time . "'";
		}
		
		if(isset($this->input['date_search']) && !empty($this->input['date_search']))
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
			
		return $condition;
	}
	
	public function detail()
	{
		
	}
	public function index()
	{
		
	}
}

$out = new dvrCheckedLogApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>