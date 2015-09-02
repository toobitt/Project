<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_shield.php 38126 2014-07-10 02:12:38Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','program_shield');
require('global.php');
class programShieldApi extends adminReadBase
{
	private $mProgramShield;
	private $mChannel;
	public function __construct()
	{
		parent::__construct();
		require_once (CUR_CONF_PATH . 'lib/program_shield.class.php');
		$this->mProgramShield = new programShield();
		
		require_once (CUR_CONF_PATH . 'lib/channel.class.php');
		$this->mChannel = new channel();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$channel_id = intval($this->input['channel_id']);
		$dates 		= $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d', TIMENOW);
		$appid		= intval($this->input['appid']);
		if (!$channel_id)
		{
			$this->errorOutput('NO_CHANNEL_ID');
		}
		
		//频道信息
		$channel_info = $this->mChannel->get_channel_by_id($channel_id);
		
		if (empty($channel_info))
		{
			$this->errorOutput('NO_CHANNEL_INFO');
		}
		
		if ($channel_info['client_logo'][$appid])
		{
			unset($channel_info['client_logo'][$appid]['appid'], $channel_info['client_logo'][$appid]['appname']);
			$channel_info['logo_rectangle'] = $channel_info['client_logo'][$appid];
		}
		unset($channel_info['client_logo']);
		if ($channel_info['logo_rectangle'])
		{
			$channel_info['logo_url'] 	= hg_material_link($channel_info['logo_rectangle']['host'], $channel_info['logo_rectangle']['dir'], $channel_info['logo_rectangle']['filepath'], $channel_info['logo_rectangle']['filename'], '113x43/');
		}
	//	unset($channel_info['logo_rectangle']);
		$this->addItem_withkey('channel_info', $channel_info);
		$this->addItem_withkey('dates', $dates);
		
		//节目屏蔽
		$condition 	= $this->get_condition();
		$orderby 	= " ORDER BY start_time ASC ";
		
		$ret_shield = $this->mProgramShield->show($channel_id, $dates, $condition, $orderby);
		
		$start 	 	= strtotime($dates . ' ' . '00:00:00');
		$end	 	= strtotime($dates . ' ' . '23:59:59');
		$tmp_time 	= 0;
		
		$shield = array();
		if (!empty($ret_shield))
		{
			foreach ($ret_shield AS $v)
			{
				if (!$tmp_time && $v['start_time'] > $start)
				{
					$shield[] = $this->get_blank_time($start, $v['start_time'], $dates);
				}
				
				if($tmp_time && $tmp_time != $v['start_time'])//中
				{
					$shield[] = $this->get_blank_time($tmp_time,$v['start_time'],$dates); 
				}
				
				$tmp_time = $v['start_time']+$v['toff'];
				$shield[] = $v;
			}
		}
		
		$this->addItem($shield);
		$this->output();
	}
	
	private function get_blank_time($start, $end, $dates)
	{
		$info = array(
			'start_time' 	=> $start,	
			'toff' 			=> $end-$start,
			'dates' 		=> $dates,
			'start' 		=> date("H:i:s", $start),	
			'end' 			=> date("H:i:s", $end),
		);
		return $info;
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
	
	private function get_condition()
	{
		$condition = '';
		
		return $condition;
	}
	
	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new programShieldApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>