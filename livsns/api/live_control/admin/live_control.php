<?php
/***************************************************************************
* $Id: live_control.php 33155 2013-12-30 04:04:23Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','live_control');
require('global.php');
class liveControlApi extends adminReadBase
{
	private $mLivemms;
	private $mLiveControl;
	private $mLivMedia;
	private $mLive;
	private $mProgram;
	public function __construct()
	{
		parent::__construct();
		unset($this->mPrmsMethods['audit'],$this->mPrmsMethods['sort'], $this->mPrmsMethods['create'], $this->mPrmsMethods['update'], $this->mPrmsMethods['delete']);
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
		require_once CUR_CONF_PATH . 'lib/live_control.class.php';
		$this->mLiveControl = new liveControl();
		
		require_once(ROOT_PATH . 'lib/class/livmedia.class.php');
		$this->mLivMedia = new livmedia();
		
		require_once(ROOT_PATH . 'lib/class/live.class.php');
		$this->mLive = new live();

		require_once(ROOT_PATH . 'lib/class/program.class.php');
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
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$count 	= $this->input['count'] ? intval($this->input['count']) : 20;

		$channel_data = array(
			'offset'	=> $offset,
			'count'		=> $count,
			'field'		=> 'id, name, code, server_id, is_audio, is_mobile_phone, is_control, status',
		);
		
		$info = $this->mLive->getChannelInfo($channel_data);
		
		if ($info)
		{
			$channel_ids = array();
			foreach ($info AS $v)
			{
				$channel_ids[] = $v['id'];
			}
			
			$channel_ids = implode(',', $channel_ids);
			
			//获取当前频道预览图片、节目单
			$current_info = $this->get_current_info($info, $channel_ids);
			
			foreach ($info AS $v)
			{
				$v['current_info'] = $current_info[$v['id']];
				$v['channel_ids']  = $channel_ids;
				$this->addItem($v);
			}
		}
		
		$this->output();
	}
	
	public function detail()
	{
		//频道信息
		$channel_id = intval($this->input['id']);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		$channel_data = array(
			'id'		=> $channel_id,
			'is_stream'	=> 1,
			'is_server'	=> 1,
			'field'		=> ' * ',
		);
		
		$channel = $this->mLive->getChannelInfoById($channel_data);
		$channel = $channel[0];
		
		if (empty($channel))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		if (!$channel['status'])
		{
			$this->errorOutput('该频道已停止无法播控');
		}
		
		if (!$channel['is_control'])
		{
			$this->errorOutput('该频道不支持播控');
		}
		if (!$this->settings['server_info']['host'])
		{
			$this->errorOutput('播控服务器未配置');
		}
		if (!$channel['schedule_control']['control'])
		{
			$this->errorOutput('该频道播控层未创建');
		}
		$is_audio	= $channel['is_audio'];
		$server_id 	= $channel['server_id'];
		
		$server_info = $channel['server_info'];
		
		$host		= $server_info['host'];
		$input_dir	= $server_info['input_dir'];
		$output_dir	= $server_info['output_dir'];
		
		$wowzaip_input 		= $server_info['wowzaip_input'];
		$app_name_input		= $this->settings['wowza']['input']['app_name'];
		$suffix_input		= $this->settings['wowza']['input']['suffix'];
		$wowzaip_output 	= $server_info['wowzaip_output'];
		$suffix_output 		= $this->settings['wowza']['output']['suffix'];
		$output_append_host = $server_info['output_append_host'];
		

		
		//已启动频道
		$channel_data = array(
			'offset'	=> 0,
			'count'		=> 100,
			'is_audio'	=> $is_audio,
			'status'	=> 1,
			'not_id'	=> $channel_id,
			//'server_id'	=> $server_id,
			'field'		=> 'id, name, code, server_id, is_audio, is_control, is_mobile_phone',
		);
		
		$channel_info = $this->mLive->getChannelInfo($channel_data);
		
		//检索出已创建的备播信号
		$field_stream_beibo = 'id, change_id, change_name, stream_name, input_id';
		$ret_stream_beibo = $this->mLiveControl->get_live_control_by_channel_id($channel_id, $field_stream_beibo);
		
		$stream_beibo = array();
		foreach ($ret_stream_beibo AS $k => $v)
		{
			$v['input_url'] = $v[0]['output_url_rtmp'];
			
			$stream_beibo[$k] = $v;
		}
		/*
		//默认备播频道
		$channel_beibo = @array_slice($channel_info,0,3);
		
		$_channel_beibo = $channel_beibo;
		
		if ($channel['beibo'] && !empty($channel_info))
		{
			$channel_beibo = array();
			$channel['beibo'] = @explode(',', $channel['beibo']);
			foreach ($channel['beibo'] AS $k => $v)
			{
				foreach ($channel_info AS $kk => $vv)
				{
					if ($vv['id'] == $v)
					{
						$channel_beibo[$k] = $vv;
					}
				}
				
				if (!empty($ret_stream_beibo))
				{
					foreach ($ret_stream_beibo AS $vvv)
					{
						if ($vvv['change_id'] == $v)
						{
							$channel_beibo[$k]['_input_id'] = $vvv['input_id'];
							$channel_beibo[$k]['_stream_id'] = intval($vvv['id']);
						}
					}
				}
			}
		}
					
		$stream_beibo = array();
		if (count($ret_stream_beibo) < 3)
		{
			//如果备播信号小于3,则删除以前的备播信号
			if (!empty($ret_stream_beibo) && count($channel_beibo) < 3)
			{
				foreach ($ret_stream_beibo AS $v)
				{
					if ($v['input_id'])
					{
						$input_data = array(
							'action'	=> 'delete',
							'id'		=> $v['input_id'],
						);
						
						$ret_delete = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
						
						if ($ret_delete['result'])
						{
							$this->mLiveControl->delete($v['id']);
						}
					}
				}
				
				$update_data = array(
					'id'	=> $channel_id,
					'beibo'	=> '',
				);
				
				$ret_update = $this->mLive->updateBeibo($update_data);
				
				if ($ret_update['id'])
				{
					$channel_beibo = $_channel_beibo;
				}
			}
			
			$input_data = array(
				'host'				=> $host,
				'input_dir'			=> $input_dir,
				'wowzaip_input'		=> $wowzaip_input,
				'app_name_input'	=> $app_name_input,
				'suffix_input'		=> $suffix_input,
				'wowzaip_input'		=> $wowzaip_input,
				'channel_id'		=> $channel_id,
				'channel_name'		=> $channel['name'],
				'server_id'			=> $server_id,
				'channel_beibo'		=> $channel_beibo,
			);
		
			$stream_beibo = $this->input_stream_create($input_data);
			
			if ($stream_beibo == -1)
			{
				$this->errorOutput('该频道备播信号添加失败');
			}
			else if ($stream_beibo == -2)
			{
				$this->errorOutput('该频道备播信号启动失败');
			}
		}
		else 
		{
			foreach ($ret_stream_beibo AS $k => $v)
			{
				$v['input_url'] = hg_set_stream_url($wowzaip_input, $app_name_input, $v['input_id'] . $suffix_input);
				
				$stream_beibo[$k] = $v;
			}
		}
		*/
		$stream_main = array(
			'id'			=> 0,
			'change_id'		=> $channel['id'],
			'change_name'	=> $channel['name'],
			'stream_name'	=> $channel['channel_stream'][0]['stream_name'],
			'input_id'		=> $channel['channel_stream'][0]['input_id'],
			'input_url'		=> $channel['channel_stream'][0]['output_url_rtmp']
		);
		
	//	$stream_beibo = @array_unshift($stream_beibo, $stream_main);

		//备播文件
		$vod_data = array(
			'offset' => 0,
			'count' => 7,
		);
		
		$vod_info 	= $this->getVodInfo($vod_data);
		$vod_count 	= $this->getVodCount();
		
		$backup_info = array(
			'info'	=> $vod_info,
			'total'	=> $vod_count['total'],
		);
		
		$channel['channel_info'] 	= $channel_info;
		$channel['stream_main']		= $stream_main;
		$channel['stream_beibo']	= $stream_beibo;
		$channel['backup_info']		= $backup_info;
		
		$this->addItem($channel);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mLive->getChannelCount($condition);
		echo json_encode($info);
	}
	
	/**
	 * 等待信号
	 * $id 频道id
	 * Enter description here ...
	 */
	public function wait_stream()
	{
		//频道信息
		$channel_id = intval($this->input['id']);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		$channel_data = array(
			'id'		=> $channel_id,
			'is_stream'	=> 1,
			'is_server'	=> 1,
			'field'		=> ' * ',
		);
		
		$channel = $this->mLive->getChannelInfoById($channel_data);
		$channel = $channel[0];
		
		if (empty($channel))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		if (!$channel['status'])
		{
			$this->errorOutput('该频道已停止无法播控');
		}
		
		if (!$channel['is_control'])
		{
			$this->errorOutput('该频道不支持播控');
		}
		
		$is_audio	= $channel['is_audio'];
		$server_id 	= $channel['server_id'];
		
		$server_info = $channel['server_info'];
		
		$host		= $server_info['host'];
		$input_dir	= $server_info['input_dir'];
		$output_dir	= $server_info['output_dir'];
		
		$wowzaip_input 		= $server_info['wowzaip_input'];
		$app_name_input		= $this->settings['wowza']['input']['app_name'];
		$suffix_input		= $this->settings['wowza']['input']['suffix'];
		$wowzaip_output 	= $server_info['wowzaip_output'];
		$suffix_output 		= $this->settings['wowza']['output']['suffix'];
		$output_append_host = $server_info['output_append_host'];
		
		$application_id = $channel['application_id'];
		
		if ($application_id)
		{
			$application_data = array(
				'action'	=> 'select',
				'id'		=> $application_id,
			);
			
			$ret_select = $this->mLivemms->outputApplicationOperate($host, $output_dir, $application_data);
		}
		
		if (!$ret_select)
		{
			$this->errorOutput('媒体服务器未启动');
		}
		
		//已启动频道
		$channel_data = array(
			'offset'	=> 0,
			'count'		=> 100,
			'is_audio'	=> $is_audio,
			'status'	=> 1,
			'not_id'	=> $channel_id,
			'server_id'	=> $server_id,
			'field'		=> 'id, name, code, server_id, is_audio, is_control, is_mobile_phone',
		);
		
		$channel_info = $this->mLive->getChannelInfo($channel_data);
		
		//检索出已创建的备播信号
		$field_stream_beibo = 'id, change_id, change_name, stream_name, input_id';
		$ret_stream_beibo = $this->mLiveControl->get_live_control_by_channel_id($channel_id, $field_stream_beibo);
		
		//默认备播频道
		$channel_beibo = @array_slice($channel_info,0,3);
		
		$_channel_beibo = $channel_beibo;
		
		if ($channel['beibo'] && !empty($channel_info))
		{
			$channel_beibo = array();
			$channel['beibo'] = @explode(',', $channel['beibo']);
			foreach ($channel['beibo'] AS $k => $v)
			{
				foreach ($channel_info AS $kk => $vv)
				{
					if ($vv['id'] == $v)
					{
						$channel_beibo[$k] = $vv;
					}
				}
				
				if (!empty($ret_stream_beibo))
				{
					foreach ($ret_stream_beibo AS $vvv)
					{
						if ($vvv['change_id'] == $v)
						{
							$channel_beibo[$k]['_input_id'] = $vvv['input_id'];
							$channel_beibo[$k]['_stream_id'] = intval($vvv['id']);
						}
					}
				}
			}
		}
					
		$stream_beibo = array();
		if (count($ret_stream_beibo) < 3)
		{
			//如果备播信号小于3,则删除以前的备播信号
			if (!empty($ret_stream_beibo) && count($channel_beibo) < 3)
			{
				foreach ($ret_stream_beibo AS $v)
				{
					if ($v['input_id'])
					{
						$input_data = array(
							'action'	=> 'delete',
							'id'		=> $v['input_id'],
						);
						
						$ret_delete = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
						
						if ($ret_delete['result'])
						{
							$this->mLiveControl->delete($v['id']);
						}
					}
				}
				
				$update_data = array(
					'id'	=> $channel_id,
					'beibo'	=> '',
				);
				
				$ret_update = $this->mLive->updateBeibo($update_data);
				
				if ($ret_update['id'])
				{
					$channel_beibo = $_channel_beibo;
				}
			}
			
			$input_data = array(
				'host'				=> $host,
				'input_dir'			=> $input_dir,
				'wowzaip_input'		=> $wowzaip_input,
				'app_name_input'	=> $app_name_input,
				'suffix_input'		=> $suffix_input,
				'wowzaip_input'		=> $wowzaip_input,
				'channel_id'		=> $channel_id,
				'channel_name'		=> $channel['name'],
				'server_id'			=> $server_id,
				'channel_beibo'		=> $channel_beibo,
			);
		
			$stream_beibo = $this->input_stream_create($input_data);
			
			if ($stream_beibo == -1)
			{
				$this->errorOutput('该频道备播信号添加失败');
			}
			else if ($stream_beibo == -2)
			{
				$this->errorOutput('该频道备播信号启动失败');
			}
		}
		else 
		{
			foreach ($ret_stream_beibo AS $k => $v)
			{
				$v['input_url'] = hg_set_stream_url($wowzaip_input, $app_name_input, $v['input_id'] . $suffix_input);
				
				$stream_beibo[$k] = $v;
			}
		}
		
		if (empty($stream_beibo))
		{
			$return = array('result' => 0);
		}
		else
		{
			$return = array('result' => 1);
		}
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 创建备播信号
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	private function input_stream_create($data)
	{
		$host 			= $data['host'];
		$input_dir 		= $data['input_dir'];
		$wowzaip_input	= $data['wowzaip_input'];
		$app_name_input = $data['app_name_input'];
		$suffix_input 	= $data['suffix_input'];
		
		$channel_id 	= $data['channel_id'];
		$channel_name 	= $data['channel_name'];
		$server_id		= $data['server_id'];
		
		$channel_beibo 	= $data['channel_beibo'];
		
		if (empty($channel_beibo))
		{
			return false;
		}
		
		$ret_input_id = $input_stream = array();
		foreach ($channel_beibo AS $k => $v)
		{
			if (!empty($v['channel_stream']) && $v['channel_stream'][0]['output_url_rtmp'])
			{
				if ($v['_stream_id'] && $v['_input_id'])
				{
					$ret_input_id[$k] = $v['_input_id'];
				}
				
				if (!$v['_input_id'] || !$v['_stream_id'])
				{
					$input_data = array(
						'action'	=> 'insert',
						'url'		=> $v['channel_stream'][0]['output_url_rtmp'],
						'type'		=> 0,
					);
					
					$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
	
					$ret_input_id[$k] = $ret_input['input']['id'];
							
					if (!$ret_input['result'])
					{
						continue;
					}
				}
				
				$input_stream[$k] = array(
					'channel_id'	=> $channel_id,
					'channel_name'	=> $channel_name,
					'change_id'		=> $v['id'],
					'change_name'	=> $v['name'],
					'stream_name'	=> $v['channel_stream'][0]['stream_name'],
					'url'			=> $v['channel_stream'][0]['output_url_rtmp'],
					'input_id'		=> $ret_input_id[$k],
					'_stream_id'	=> $v['_stream_id'],
				);
			}
		}
	
		//创建信号过程中有失败的，则删除已创建好的信号
		$tmp_insert = 0;
		foreach ($ret_input_id AS $k => $v)
		{
			if (!$v)
			{
				$tmp_insert = 1;
			}
		}
		
		if ($tmp_insert)
		{
			foreach ($input_stream AS $v)
			{
				if ($v['input_id'] && !$v['_stream_id'])
				{
					$input_data = array(
						'action'	=> 'delete',
						'id'		=> $v['input_id'],
					);
					
					$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
				}
			}
			
			return -1;//('切播频道数据异常，正在返回直播');
		}
		
		$ret_start = array();
		foreach ($input_stream AS $k => $v)
		{
			if ($v['input_id'])
			{
				if (!$v['_stream_id'])
				{
					$input_data = array(
						'action'	=> 'start',
						'id'		=> $v['input_id'],
					);
					
					$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
					
					$ret_start[$k] = $ret_input['result'];
					
					if (!$ret_input['result'])
					{
						continue;
					}
				}
				
				if ($v['_stream_id'])
				{
					$ret_start[$k] = 1;
				}
			}
		}
		
		//信号启动过程中有失败的，则停止已启动好的信号
		$tmp_start = 0;
		foreach ($ret_start AS $k => $v)
		{
			if (!$v)
			{
				$tmp_start = 1;
			}
		}
		if ($tmp_start)
		{
			foreach ($input_stream AS $v)
			{
				if ($v['input_id'] && !$v['_stream_id'])
				{
					$input_data = array(
						'action'	=> 'stop',
						'id'		=> $v['input_id'],
					);
					
					$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
				}
			}
			return -2;//('切播频道启动失败，正在返回直播');
		}
		
		$return = array();
		foreach ($input_stream AS $k => $v)
		{
			if (!empty($v))
			{
				$return[$k]['change_id'] 	= $v['change_id'];
				$return[$k]['change_name'] 	= $v['change_name'];
				$return[$k]['stream_name'] 	= $v['stream_name'];
				$return[$k]['input_id'] 	= $v['input_id'];
				$return[$k]['input_url'] 	= hg_set_stream_url($wowzaip_input, $app_name_input, $v['input_id'] . $suffix_input);
				
				$v['server_id']		= $server_id;
				$v['user_id']		= $this->user['user_id'];
				$v['user_name']		= $this->user['user_name'];
				$v['appid']			= $this->user['appid'];
				$v['appname']		= $this->user['display_name'];
				$v['create_time']	= TIMENOW;
				$v['update_time']	= TIMENOW;
				$v['start_time']	= TIMENOW;
				$v['ip']			= hg_getip();
				
				if (!$v['_stream_id'])
				{
					unset($v['_stream_id']);
					$ret = $this->mLiveControl->create($v);
					if (!$ret)
					{
						continue;
					}
					$return[$k]['id'] = $ret['id'];
				}
				
				if ($v['_stream_id'])
				{
					$return[$k]['id'] = $v['_stream_id'];
				}
			}
		}
		
		return $return;
	}

	/**
	 * 直播控制备播文件分页
	 * $offset
	 * $count
	 * $k
	 * Enter description here ...
	 */
	public function get_backup_info()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['counts'] ? intval($this->input['counts']) : 7;
		$data = array(
			'offset' => $offset,
			'count'  => $count,
		);
		$return = $this->getVodInfo($data);
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 获取视频库信息
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	private function getVodInfo($data = array())
	{
		$return = $this->mLivMedia->getVodInfo($data);
		return $return;
	}
	
	/**
	 * 获取视频库信息总数
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	private function getVodCount($data = array())
	{
		$return = $this->mLivMedia->getVodCount($data);
		return $return;
	}
	
	/**
	 * 获取频道当前预览图片、节目单
	 * Enter description here ...
	 * @param unknown_type $channel_info
	 */
	public function get_current_channel_info()
	{
		$channel_id = trim($this->input['channel_id']);
		if ($channel_id)
		{
			$channel_data = array(
				'id'		=> $channel_id,
				'is_stream'	=> 0,
				'field'		=> 'id, is_audio, status, logo_square, server_id, logo_rectangle, client_logo',
			);
			
			$channel_info = $this->mLive->getChannelInfoById($channel_data);
		
			if (!empty($channel_info))
			{
				$return = $this->get_current_info($channel_info, $channel_id);
			}
		}
		$this->addItem($return);
		$this->output();
	}
	
	private function get_current_info($channel_info, $channel_ids)
	{
		$ret_program = $this->mProgram->getCurrentProgram($channel_ids);
		
		$program = array();
		if (!empty($ret_program))
		{
			foreach ($ret_program AS $v)
			{
				$program[$v['channel_id']] = $v['theme'];
			}
		}
		
		$return = $item = array();
		foreach ($channel_info AS $v)
		{
			$item['program'] = $program[$v['id']] ? $program[$v['id']] : '精彩节目';
		
			$item['preview'] = LIVE_CONTROL_LIST_PREVIEWIMG_URL . date('Y') . '/' . date('m') . '/live_' . $v['id'] . '.png?time=' . TIMENOW;
			
			if ($v['is_audio'] && $v['logo_rectangle'])
			{
				$item['preview'] = hg_fetchimgurl($v['logo_rectangle']);
			}
			if (!$v['status'])
			{
				$item['preview'] = '';
			}
			
			$return[$v['id']] = $item;
		}
		return $return;
	}
	
	private function get_condition()
	{
		
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
	//播控页面心跳
	public function keep_alive()
	{
		$this->addItem(TIMENOW);
		$this->output();
	}
}

$out = new liveControlApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>