<?php
/***************************************************************************
* $Id: schedule.php 31675 2013-11-19 09:07:41Z zhangfeihu $
***************************************************************************/
define('MOD_UNIQUEID','schedule');
require('global.php');
class scheduleApi extends adminReadBase
{
	private $mLive;
	private $mSchedule;
	private $mLivMedia;
	private $mProgram;
	public function __construct()
	{
		parent::__construct();
		unset($this->mPrmsMethods['audit'],$this->mPrmsMethods['sort'], $this->mPrmsMethods['create'], $this->mPrmsMethods['update']);
		
		require_once ROOT_PATH . 'lib/class/live.class.php';
		$this->mLive = new live();
		
		require_once CUR_CONF_PATH . 'lib/schedule.class.php';
		$this->mSchedule = new schedule();
		
		require_once ROOT_PATH . 'lib/class/livmedia.class.php';
		$this->mLivMedia = new livmedia();
		
		require_once ROOT_PATH . 'lib/class/program.class.php';
		$this->mProgram = new program();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index()
	{
		
	}

	public function show()
	{
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		$channel_data = array(
			'id'		=> $channel_id,
			'is_stream'	=> 1,
			'is_server'	=> 0,
			'field'		=> ' id, name, code, server_id, main_stream_name, is_mobile_phone, is_control, is_audio ',
		);
		
		$channel_info = $this->mLive->getChannelInfoById($channel_data);
		$channel_info = $channel_info[0];
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		if (!$channel_info['is_control'])
		{
			$this->errorOutput('该频道不支持串联单，请到频道设置允许播控');
		}
		
		$condition  = $this->get_condition();
		$orderby	= ' ORDER BY order_id ASC ';
		$schedule_info = $this->mSchedule->show($condition, $orderby);
		
		$dates = $this->input['dates'] ? trim($this->input['dates']) : date('Y-m-d');
		
		$is_expired = 0;
		if ($this->input['dates'] && trim($this->input['dates']) < date('Y-m-d'))
		{
			$is_expired = 1;
		}
		
		//检测是否存在节目单
		$program_data = array(
			'channel_id'	=> $channel_id,
			'dates'			=> $dates,
		);
		$program = $this->mProgram->check_program_exists($program_data);
		
		$is_program = !empty($program) ? 1 : 0;
		
		$this->addItem_withkey('today', date('Y-m-d'));
		$this->addItem_withkey('dates', $dates);
		$this->addItem_withkey('stime', date('H:i:s'));
		$this->addItem_withkey('is_expired', $is_expired);
		$this->addItem_withkey('is_program', $is_program);
		$this->addItem_withkey('channel_info', $channel_info);
		$this->addItem_withkey('schedule_info', $schedule_info);
		$this->output();
	}
	
	public function detail()
	{
		
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$return = $this->mSchedule->count($condition);
		echo json_encode($return);
	}
	
	/**
	 * 取串联单信息
	 * $channel_id 频道id
	 * $dates 日期 默认当天
	 * Enter description here ...
	 */
	public function get_schedule_info()
	{
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		$condition  = $this->get_condition();
		
		$return = $this->mSchedule->show($condition);
		
		$this->addItem($return);
		$this->output();
	}

	/**
	 * 取节目单 (按照日期)
	 * $channel_id 频道id
	 * $dates 日期 (2013-04-18)
	 * Enter description here ...
	 */
	public function get_program_info()
	{
		$channel_id = trim($this->input['channel_id']);
		$dates		= trim($this->input['dates']);
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		if (!$dates)
		{
			$this->errorOutput('未传入日期');
		}

		$program_data = array(
			'channel_id' => $channel_id,
			'dates'		 => $dates,
			'field'		 => 'id, channel_id, schedule_id, theme, dates',
		);
		
		$return = $this->mProgram->get_program_info($program_data);
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 取频道信息
	 * $offset 分页参数
	 * $count 分页参数
	 * $is_audio 是否是音频 (1-音频 0-视频)
	 * $server_id 直播服务器id
	 * $node_id 直播频道分类id
	 * Enter description here ...
	 */
	public function get_channel_info()
	{
		$is_audio  = intval($this->input['is_audio']);
		$server_id = intval($this->input['server_id']);
		$node_id   = intval($this->input['node_id']);
		
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$count  = $this->input['counts'] ? intval($this->input['counts']) : 20;
		
		$channel_data = array(
			'offset'	=> $offset,
			'count'		=> $count,
			'is_stream'	=> 0,
			'is_audio'	=> $is_audio,
	//		'server_id'	=> $server_id,
			'node_id'	=> $node_id,
			'field'		=> 'id, name, code, is_control, is_audio, is_mobile_phone, server_id, logo_rectangle, logo_square, client_logo',
		);
		
		$channel = $this->mLive->getChannelInfo($channel_data);
		
		$return = array();
		if (!empty($channel))
		{
			foreach ($channel AS $v)
			{
				if ($v['snap'])
				{
					$v['logo_rectangle'] = $v['snap'];
					$v['logo_rectangle_url'] = hg_material_link($v['snap']['host'], $v['snap']['dir'], $v['snap']['filepath'], $v['snap']['filename']);
				}
				unset($v['snap']);
				$return[] = $v;
			}
		}
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 取视频库信息
	 * $offset 分页参数
	 * $count 分页参数
	 * $vod_sort_id 视频分类
	 * $pp 分页参数
	 * $title 标题
	 * $date_search 日期
	 * Enter description here ...
	 */
	public function get_vod_info()
	{
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['counts'] ? intval($this->input['counts']) : 20;
		$offset = intval(($pp - 1)*$count);			
		$vod_sort_id = intval($this->input['vod_sort_id']);
		
		$vod_data = array(
			'offset'	  => $offset,
			'count'		  => $count,
			'vod_sort_id' => $vod_sort_id,
			'pp'		  => $pp,
			'k'	      	  => trim($this->input['title']),
			'date_search' => trim($this->input['date_search']),
		);
		
		$return = array();
		$ret_vod = $this->mLivMedia->getVodInfo($vod_data);
		$return['video'] = $ret_vod;
		
		$ret_page = $this->mLivMedia->getPageData($vod_data);
		
		$return['page'] = $ret_page;
		$return['date_search'] = $this->settings['date_search'];
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 取时移频道信息
	 * $offset 分页参数
	 * $count 分页参数
	 * Enter description here ...
	 */
	public function get_time_shift_channel()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$count  = $this->input['counts'] ? intval($this->input['counts']) : 20;
		
		$channel_data = array(
			'offset'	=> $offset,
			'count'		=> $count,
			'is_stream'	=> 0,
			'field'		=> 'id, name, code, is_control, is_audio, is_mobile_phone, server_id',
			'is_mobile_phone' => 1,
		);
		
		$return = $this->mLive->getChannelInfo($channel_data);
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 取单个频道时移信息
	 * $channel_id 频道id
	 * $dates 日期 (2013-3-20)
	 * $stime 开始时间 (08:00:00)
	 * Enter description here ...
	 */
	public function get_time_shift_info()
	{
		$channel_id = intval($this->input['channel_id']);
		$dates		= $this->input['dates'] ? trim($this->input['dates']) : date('Y-m-d');
		$stime		= $this->input['stime'] ? trim($this->input['stime']) : date('H:i:s');
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		$return = $this->mProgram->getTimeshift($channel_id, $dates, $stime);
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 取视频库节点
	 * $fid 父级id
	 * Enter description here ...
	 */
	public function get_vod_node()
	{
		$fid = intval($this->input['fid']);
		
		$return = $this->mLivMedia->getVodNode($fid);
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 取频道节点
	 * $fid 父级id
	 * Enter description here ...
	 */
	public function get_channel_node()
	{
		$fid = intval($this->input['fid']);
		
		$return = $this->mLive->getChannelNode($fid);
		
		$this->addItem($return);
		$this->output();
	}
	
	private function get_condition()
	{
		$condition = '';
		
		if (isset($this->input['channel_id']) && $this->input['channel_id'])
		{
			$condition .= " AND channel_id = " . intval($this->input['channel_id']);
		}
		
		$dates = $this->input['dates'] ? trim($this->input['dates']) : date('Y-m-d');
		$condition .= " AND dates = '" . $dates . "'";
		
		return $condition;
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