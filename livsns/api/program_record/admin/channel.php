<?php
/***************************************************************************
* $Id: channel.php 19272 2013-03-26 02:54:03Z lijiaying $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','channel');
class channelApi extends adminReadBase
{
	private $mNewLive;
	public function __construct()
	{
		######分类和操作追加######
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'create'	=>'增加',
		'update'	=>'修改',
		'delete'	=>'删除',
		'_node'=>array(
			'name'=>'频道',
			'filename'=>'channel_node.php',
			'node_uniqueid'=>'channel_node',
			),
		);
		######分类和操作追加######
		parent::__construct();
		require_once ROOT_PATH . 'lib/class/live.class.php';
		$this->mNewLive = new live();
		
		include(CUR_CONF_PATH . 'lib/server.class.php');
		$this->server = new server();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function __getConfig()
	{
		$total = $this->server->count();
		if ($total['total'] < 1)
		{
			$this->errorOutput('REDIRECT TO ' . APP_UNIQUEID . ' program_record_server');
		}
		parent::__getConfig();
	}

	public function index()
	{
		
	}

	
	/**
	 * 取频道信息
	 * $offset 分页参数
	 * $count 分页参数
	 * $is_audio 是否是音频 (1-音频 0-视频)
	 * Enter description here ...
	 */
	public function show()
	{
		#####
		//$this->verify_content_prms();
		#####
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 10;

		$condition = $this->get_condition();
		
		$condition['offset'] 	= $offset;
		$condition['count'] 	= $count;
		$condition['is_stream'] = 0;
		$condition['field'] = 'id, name, code, is_control, is_audio, is_mobile_phone, server_id, logo_rectangle, logo_square, node_id';
		
		$channel = $this->mNewLive->getChannelInfo($condition);
		
		$channel_id = array();
		if (!empty($channel))
		{
			foreach ($channel AS $v)
			{
				$channel_id[] = $v['id'];
			}
		}
		
		if (!empty($channel_id))
		{
			$channel_id = implode(',', $channel_id);
			$dates = $this->getWeekInfo($channel_id);
		}
		$week = array('日', '一', '二', '三', '四', '五', '六');
		$get_week = $this->getWeek();
		
		$_dates = $short_week = array();
		foreach ($get_week AS $k => $v)
		{
			$short_week[] = $week[date('w', strtotime($v))];
			$_dates[$k] = 0;
		}
		
		$channel_info = array();
		if (!empty($channel))
		{
			foreach ($channel AS $v)
			{
				$v['is_schedule'] = $dates[$v['id']] ? $dates[$v['id']]['is_schedule'] : $_dates;
				$channel_info[] = $v;
			}
		}
		$this->addItem_withkey('dates', date('Y-m-d'));
		$this->addItem_withkey('month', date('m'));
	//	$this->addItem_withkey('this_week', date('W'));
		$this->addItem_withkey('week', $get_week);
		$this->addItem_withkey('short_week', $short_week);
		$this->addItem_withkey('channel_info', $channel_info);
		$this->output();
	}
	
	public function detail()
	{
		#####
		$this->verify_content_prms();
		#####
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$return = $this->mNewLive->getChannelCount($condition);
		echo json_encode($return);
	}
	
	private function get_condition()
	{
		$condition = array();
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$is_action = trim($this->input['a']) == 'show' || trim($this->input['a']) == 'count' ? true:false;
			if($is_action && $this->user['prms']['app_prms']['program_record']['nodes'])
			{
				$tmp_node = implode(',',$this->user['prms']['app_prms']['program_record']['nodes']);
				$all_node_tmp = $this->mNewLive->getChannelById($tmp_node);
				$channel_id_info = array();
				if(!empty($all_node_tmp))
				{
					foreach($all_node_tmp as $k => $v)
					{
						$all_node[] = $v['id'];
						$channel_id_info[$v['node_id']][] = $v['id'];
					}
				}
				$all_node = array_unique($all_node);
				if(intval($this->input['_id']))
				{
					$tmp_node = !empty($channel_id_info[$this->input['_id']]) ? $channel_id_info[$this->input['_id']] : array();
					if(!empty($tmp_node))
					{
						$condition['channel_id'] = implode(',',$tmp_node);
					}
					else
					{
						$condition['channel_id'] = -1;
					}
				}
				else
				{
					$condition['channel_id'] = implode(',',$all_node);
				}
			}
		}
		else
		{
			if(intval($this->input['_id']))
			{
				$condition['node_id'] = intval($this->input['_id']);
			}
		}
		return $condition;
	}
	
/**
	 * 按周获取日期 (默认本周)
	 * $week 周数 (14)
	 * Enter description here ...
	 */
	public function get_week()
	{
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('请传入频道id');
		}
		
		$week		= $this->input['week'];
		$dates 		= ($this->input['dates'] && ($week == -1 || $week == 1)) ? trim($this->input['dates']) : date('Y-m-d');
	
		$dates_arr	= explode('-', $dates);
		if (!checkdate($dates_arr[1], $dates_arr[2], $dates_arr[0]))
		{
			$dates = date('Y-m-d');
		}
		
		$dates_format = strtotime($dates);
		
		if ($week == -1)
		{
			$dates_format = $dates_format - 86400;
		}
		elseif ($week == 1) 
		{
			$dates_format = $dates_format + 86400;
		}
		
		$ret = $this->getWeekInfo($channel_id, $dates_format);
		
		$return = array(
			'dates'			=> date('Y-m-d'),
			'month'			=> date('m', $dates_format),
		//	'this_week'		=> date('W'),
			'week'			=> $ret[$channel_id]['week'],
			'is_schedule'	=> $ret[$channel_id]['is_schedule'],
		);
		
		$this->addItem($return);
		$this->output();
	}
	
	private function getWeek($today_format = '')
	{
		$today_format = $today_format ? $today_format : strtotime(date('Y-m-d'));

		$dates = array();
		for ($i = 0; $i < 7; $i ++)
		{
			$time = $today_format + ($i * 86400);
			$dates[$i] = date('Y-m-d', $time);
		}
		return $dates;
	}
	
	private function getWeekInfo($channel_id, $today_format = '')
	{
		$dates = $this->getWeek($today_format);
		$week_num = array();
		foreach($dates as $k => $v)
		{
			$week_num[$v] = date('w',strtotime($v)) ? date('w',strtotime($v)) : 7;
		}
		
		$sql = "SELECT channel_id,week_num FROM " . DB_PREFIX . "program_record_relation WHERE 1 AND channel_id IN(" . $channel_id . ") ORDER BY week_num ASC";
		$q = $this->db->query($sql);
		$schedule = array();
		while ($row = $this->db->fetch_array($q))
		{
			if(in_array($row['week_num'],$week_num))
			{
				$week_num = array_flip($week_num);
				$schedule[$row['channel_id']][$week_num[$row['week_num']]] = $week_num[$row['week_num']];
				$week_num = array_flip($week_num);
			}
		}

		$return = array();
		if (!empty($schedule))
		{
			foreach ($schedule AS $k => $v)
			{
				for ($i = 0; $i < 7; $i ++)
				{
					$return[$k]['week'][$i] = $dates[$i];
					$return[$k]['is_schedule'][$i] = $v[$dates[$i]] ? 1 : 0;
				}
			}
		}
		else 
		{
			$channel_id = explode(',', $channel_id);
			foreach ($channel_id AS $k => $v)
			{
				for ($i = 0; $i < 7; $i ++)
				{
					$return[$v]['week'][$i] = $dates[$i];
					$return[$v]['is_schedule'][$i] = 0;
				}
			}
		}
		
		return $return;
	}
}

$out = new channelApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>