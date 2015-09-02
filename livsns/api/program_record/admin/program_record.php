<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record.php 7586 2012-07-05 09:40:56Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_record');//模块标识
class programRecordApi extends adminReadBase
{
	private $obj;
	function __construct()
	{
		######分类和操作追加######
		$this->mNodes = array(
			'program_record_node' => '录制频道',
		);
		$this->mModPrmsMethods = array(
		//'publish'=>array('name'=>'快速发布'),
		);
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'create'	=>'增加',
		'update'	=>'修改',
		'delete'	=>'删除',
		'_node'=>array(
			'name'=>'频道',
			'filename'=>'channel_node.php',
			'node_uniqueid'=>'channel_node',
			'ext_parameter'=>array('fetch_live'=>1),
			),
		);
		unset($this->mPrmsMethods['audit'],$this->mPrmsMethods['sort']);
		######分类和操作追加######
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/program_record.class.php');
		$this->obj = new programRecord();
		require_once ROOT_PATH . 'lib/class/live.class.php';
		$this->mNewLive = new live();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	public function index()
	{
		$app = array('appid' => $this->input['appid'],'appkey' => $this->input['appkey']);
		$this->input = $this->settings['mms']['record_server'];
		$this->input['appid'] = $app['appid'];
		$this->input['appkey'] = $app['appkey'];
		$record_status = $this->check_api_state();
		$array = array(
			'record_status' => $record_status
		);
		$this->addItem($array);
		$this->output();
	}

	/**
	 * 显示录播节目单
	 */
	function show()
	{
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array();
		if(intval($this->input['channel_id']) > 0)
		{
			$nodes['nodes'][intval($this->input['channel_id'])] = intval($this->input['channel_id']);
			$this->verify_content_prms($nodes);
		}
		#####
		$condition = $this->get_condition();
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if($this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$condition .= " AND channel_id IN (" . implode(',',$this->user['prms']['app_prms'][APP_UNIQUEID]['nodes']) . ")";
			}
			else
			{
				$this->errorOutput(NO_PRIVILEGE);
			}
		}
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = " LIMIT " . $offset . " , " . $count;
		$info = $this->obj->show($condition,$data_limit,trim($this->input['dates']));
		if(!empty($info))
		{
			include_once(ROOT_PATH . 'lib/class/program.class.php');
			$program_plan = new program();
			foreach($info as $key => $value)
			{
				$spa = '';
				$start_time = strtotime($value['dates'] . " ". $value['start_time']);
				$end_time = strtotime($value['dates'] . " ". $value['start_time']) + $value['toff'];
				$value['title'] = $value['title'] ? $value['title'] : (trim($program_plan->get_program_plan($value['channel_id'],$start_time,$end_time)) ? $program_plan->get_program_plan($value['channel_id'],$start_time,$end_time) : '精彩节目');
				$value['source'] = $value['program_id'] ? '节目单' : ($value['plan_id'] ? '节目单计划' : '计划收录');
				//hg_pre($value);
				$this->addItem($value);
			}
			$this->output();
		}		
	}
	
	/**
	 * Enter description here ...
	 */
	public function count()
	{
		$condition = $this->get_condition();
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if($this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$condition .= " AND channel_id IN (" . implode(',',$this->user['prms']['app_prms'][APP_UNIQUEID]['nodes']) . ")";
			}
			else
			{
				$condition .= " AND channel_id IN (-1)";
			}
		}
		$ret = $this->obj->count($condition);
		//暂时这样处理
		echo json_encode($ret);
	}

	public function get_item()
	{
		include_once(ROOT_PATH . 'lib/class/livmedia.class.php');
		$livmedia = new livmedia();
		$sort_name = $livmedia->getAutoItem();
		foreach($sort_name as $k => $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	/**
	 * 获取单条信息
	 */
	public function detail()
	{	
		#####
		$nodes = array(
			'_action' => 'show',
		);
		$nodes = array();
		if(intval($this->input['channel_id']) > 0)
		{
			$nodes['nodes'][intval($this->input['channel_id'])] = intval($this->input['channel_id']);
			//hg_pre($nodes);exit;
			$this->verify_content_prms($nodes);
		}
		#####
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$condition = " ORDER BY p.id DESC LIMIT 1";
		}
		else 
		{
			$condition = " WHERE p.id IN(" . $id . ")";
		}
		$ret = $this->obj->detail($condition);
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			switch($this->user['prms']['default_setting']['show_other_data'])
			{
				case 0://不允许
					if($this->user['user_id'] != $ret['user_id'])
					{
						$this->errorOutput(NO_PRIVILEGE);
					}
				break;
				case 1:
					if($this->user['org_id'] != $ret['org_id'])
					{
						$this->errorOutput(NO_PRIVILEGE);
					}
				break;
				case 5:
				break;
				default:
				break;
			}					
		}
		if(!empty($ret))
		{
			$this->addItem($ret);
			$this->output();
		}
		else 
		{
			//$this->errorOutput('录播节目不存在');
		}
	}
	
	public function getChannel()
	{
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannel(-1, 1);
		if(!empty($channel))
		{
			$all_node = array();
			if($this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$all_node = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			}
			
			foreach($channel as $k => $v)
			{
				if($this->user['group_type'] > MAX_ADMIN_TYPE)
				{
					if($this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'] && in_array($v['id'],$all_node))
					{
						$this->addItem($v);
					}
				}
				else
				{
					$this->addItem($v);
				}			
			}			
			$this->output();
		}
	}
	
	/**
	 * 获取条件
	 */
	private function get_condition()
	{
		$condition = '';
		####增加权限控制 用于显示####
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel_condition = '';
	
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$is_action = trim($this->input['a']) == 'count' ? true:false;
			if($this->user['prms']['app_prms'][APP_UNIQUEID]['action'])
			{				
				foreach($this->user['prms']['app_prms'][APP_UNIQUEID]['action'] as $k => $v)
				{
					if($v == $this->input['a'])
					{
						$is_action = true;
					}
				}			
				if($is_action && $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
				{
					if(!$this->user['prms']['default_setting']['show_other_data'])
					{					
						//$this->errorOutput(NO_PRIVILEGE);
					}
					switch($this->user['prms']['default_setting']['show_other_data'])
					{
						case 0://不允许
							$condition .= ' AND p.user_id = '.$this->user['user_id'];
						break;
						case 1:
							$condition .= ' AND p.org_id = '.$this->user['org_id'];
						break;
						case 5:
						break;
						default:
						break;
					}
					$tmp_node = implode(',',$this->user['prms']['app_prms'][APP_UNIQUEID]['nodes']);
					$all_node_tmp = $this->mNewLive->getChannelById($tmp_node, -1);
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
					$cond = array();
					if(intval($this->input['_id']))
					{
						$tmp_node = !empty($channel_id_info[$this->input['_id']]) ? $channel_id_info[$this->input['_id']] : array();
						if(!empty($tmp_node))
						{
							$cond['channel_id'] = implode(',',$tmp_node);
						}
						else
						{
							$cond['channel_id'] = -1;
						}
					}
					else
					{
						$cond['channel_id'] = implode(',',$all_node);
					}
					if($cond)
					{
						$cond['is_sys'] = -1;
						$cond['is_stream'] = 0;
						$cond['field'] = 'id, name, code, is_control, is_audio, is_mobile_phone, server_id, logo_rectangle, logo_square, node_id';
						$channel = $this->mNewLive->getChannelInfo($cond);
						$channel_id = array();
						if (!empty($channel))
						{
							foreach ($channel AS $v)
							{
								$channel_id[] = $v['id'];
							}
						}
						if($this->input['channel_id'] > 0)
						{
							if(!in_array($this->input['channel_id'], $channel_id))
							{
								$this->errorOutput(NO_PRIVILEGE);
							}
							else
							{
								$channel_condition = intval($this->input['channel_id']);
							}
						}
						else
						{
							$channel_condition = $channel_id ? implode(',', $channel_id) : '';
						}
					}
					$channel_condition = $channel_condition ? $channel_condition : -1;
				}
			}
		}
		else
		{
			if(intval($this->input['_id']))
			{
				$cond['node_id'] = intval($this->input['_id']);
				$cond['is_stream'] = 0;
				$cond['is_sys'] = -1;
				$cond['field'] = 'id, name, code, is_control, is_audio, is_mobile_phone, server_id, logo_rectangle, logo_square, node_id';
				$channel = $this->mNewLive->getChannelInfo($cond);
				$channel_id = array();
				if (!empty($channel))
				{
					foreach ($channel AS $v)
					{
						$channel_id[] = $v['id'];
					}
				}
				if($this->input['channel_id'])
				{
					if($channel_id && !in_array($this->input['channel_id'], $channel_id))
					{
						$channel_condition = -1;
					}
					else
					{
						$channel_id = array();
						$channel_condition = intval($this->input['channel_id']);
					}
				}
				else
				{
					if (!empty($channel_id))
					{
						$channel_id = implode(',', $channel_id);
						$channel_condition = $channel_condition ? $channel_condition . ',' . $channel_id : $channel_id;
					}
				}
				$channel_condition = $channel_condition ? $channel_condition : -1;			
			}
			else
			{
				if($this->input['channel_id'] > 0)
				{
					$channel_condition = intval($this->input['channel_id']);
				}
			}
		}
		$condition .= $channel_condition ? ' AND p.channel_id IN(' . $channel_condition . ')' : '';
		
		if($this->input['dates'])
		{
			$condition .= " AND r.week_num=".(date('w',strtotime($this->input['dates'])) ? date('w',strtotime($this->input['dates'])) : 7);
		}
		####增加权限控制 用于显示####
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  p.start_time > " . $yesterday . " AND p.start_time < " . $today;
					break;
				case 3://今天的数据
					$condition .= " AND  p.start_time > " . $today . " AND p.start_time < " . $tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  p.start_time > " . $last_threeday . " AND p.start_time < " . $tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  p.start_time > " . $last_sevenday . " AND p.start_time < " . $tomorrow;
					break;
				case 'other'://所有时间段
					$start = urldecode($this->input['start_time']) ? strtotime(urldecode($this->input['start_time'])) : 0;
					if($start)
					{
						$condition .= " AND start_time > '" . $start . "'";
					}
					$end = urldecode($this->input['end_time']) ? strtotime(urldecode($this->input['end_time']) . " 23:59:59") : 0;
					if($end)
					{
						$condition .= " AND start_time < '" . $end . "'";
					}
					break;
				default://所有时间段
					break;
			}
		}
		
		if($this->input['key'])
		{
			$condition .= " AND title like '%" . trim($this->input['key']) . "%'";
		}
		return $condition;
	}
}

$out = new programRecordApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>