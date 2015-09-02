<?php
/***************************************************************************

* (C)2004-2015 HOGE Software.
*
* $Id: channel.php 19895 2013-04-08 02:42:01Z lijiaying $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR."global.php");
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','live');
class channelApi extends outerReadBase
{
	private $mChannel;
	private $mServerConfig;
	private $mProgram;
	private $mImgUrl;
	private $members;
	function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/channel.class.php';
		$this->mChannel = new channel();

		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();

		include ROOT_PATH . 'lib/class/program.class.php';
		$this->mProgram = new program();
		
		require ROOT_PATH . 'lib/class/members.class.php';
		$this->members = new members();

		$ret_img_url   = $this->mChannel->get_img_url();
		$this->mImgUrl = '';
		if (!empty($ret_img_url))
		{
			$this->mImgUrl = $ret_img_url['define']['IMG_URL'];
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 协议     主机        dir   hoge台标 sd输出标识
	 * http://10.0.1.21:80/live/hoge_sd/playlist.m3u8
	 * rtmp://10.0.1.21:80/live/hoge_sd
	 */
	public function show()
	{
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count 		= $this->input['count'] ? intval($this->input['count']) : 50;
		$is_server 	= intval($this->input['is_server']);
		$field 		= $this->input['field'] ? trim($this->input['field']) : ' * ';
		$is_stream	= isset($this->input['is_stream']) ? intval($this->input['is_stream']) : 1;
		$appid		= intval($this->input['appid']);

		$is_sys		= isset($this->input['is_sys']) ? intval($this->input['is_sys']) : 1;
		$get_record		= intval($this->input['get_record']);

		$_server_info  = $this->mServerConfig->show();

		//默认取频道接管数据
		if ($this->settings['App_live_takeover'] && !$this->input['fetch_live'])
		{
			$data = array(
				'offset'	=> $offset,
				'count'		=> $count,
			//'is_sys'	=> $is_sys,
				'get_record'	=> $get_record,
			);
			$channel_id = trim($this->input['id']);
			if (!$channel_id)
			{
				$channel_id = trim($this->input['channel_id']);
			}
			if ($channel_id)
			{
				$data['id'] = $channel_id;
			}
				
			$info = $this->get_live_takeover_info($data);
				
			if ($info)
			{
				foreach ($info AS $v)
				{
					$server_info 	= $this->mChannel->get_server_info($_server_info[$v['server_id']]);
						
					$v['server_info'] = array();
					if ($is_server && $v['server_id'])
					{
						$v['server_info'] = $server_info;
					}
						
					$this->addItem($v);
				}
			}
		}
		else
		{
			$condition 	= $this->get_condition();
			$info = $this->mChannel->get_channel_info($condition, $offset, $count, '', $field, $is_stream);
				
			if ($info)
			{
				foreach ($info AS $v)
				{
					$type 		 	= $_server_info[$v['server_id']]['type'] ? $_server_info[$v['server_id']]['type'] : 'wowza';
						
					$v['server_type'] = $type;
					$server_info 	= $this->mChannel->get_server_info($_server_info[$v['server_id']]);
					$wowzaip_output = $server_info['wowzaip_output'];
					//直播
					$live_wowzaip_output   = $server_info['live_wowzaip_output'];
					//录制
					$record_wowzaip_output = $server_info['record_wowzaip_output'];
					$output_append_host    = $server_info['output_append_host'] ? $server_info['output_append_host'] : array($_server_info[$v['server_id']]['host']);
						
					$set_server_info = array(
						'input_dir'				=> $server_info['input_dir'],
						'output_dir'			=> $server_info['output_dir'],
						'wowzaip_output'		=> $wowzaip_output,
						'rtmp_output'			=> $server_info['rtmp_output'],
						'live_wowzaip_output'	=> $live_wowzaip_output,
						'record_wowzaip_output'	=> $record_wowzaip_output,
						'output_append_host'	=> $output_append_host,
						'input_port'	=> $_server_info[$v['server_id']]['input_port'],
						'output_port'	=> $_server_info[$v['server_id']]['output_port'],
						'type'			=> $type,
						'is_rand'		=> 1,
					);
					if (!empty($v['channel_stream']))
					{
						$channel_stream = $record_stream = array();
						foreach ($v['channel_stream'] AS $kk => $vv)
						{
							$vv['code'] 			= $v['code'];
							$vv['is_live']			= $v['is_live'];
							$vv['is_record']		= $v['is_record'];
							$vv['is_mobile_phone']	= $v['is_mobile_phone'];
							$vv['server_id']		= $v['server_id'];
								
							$set_stream_url = $this->mChannel->set_stream_url($set_server_info, $vv);
								
							foreach ($set_stream_url['channel_stream'] AS $kkk => $vvv)
							{
								$vv[$kkk] = $vvv;
							}
								
							$record_stream[] = $set_stream_url['record_stream'];
								
							unset($vv['code'], $vv['is_live'], $vv['is_record'], $vv['is_mobile_phone'], $vv['server_id']);
							$channel_stream[] = $vv;
						}
						$v['channel_stream'] = $channel_stream;
						$v['m3u8'] = $channel_stream[0]['live_m3u8'];
						$v['record_stream']  = $type == 'tvie' ? array() : $record_stream;
					}
						
					if ($v['client_logo'][$appid])
					{
						unset($v['client_logo'][$appid]['appid'], $v['client_logo'][$appid]['appname']);
						$v['logo_rectangle'] = $v['client_logo'][$appid];
					}
					$v['square'] = $v['logo_rectangle'];
					unset($v['client_logo']);
						
					if ($v['logo_rectangle'])
					{
						$v['logo_rectangle_url'] = hg_material_link($v['logo_rectangle']['host'], $v['logo_rectangle']['dir'], $v['logo_rectangle']['filepath'], $v['logo_rectangle']['filename'], '112x43/');
					}
						
					//频道截图
					if (!$v['is_audio'])
					{
						$v['snap'] = array(
							'host' => $this->mImgUrl . LIVE_CONTROL_LIST_PREVIEWIMG_URL, 
							'dir' => '',
							'filepath' => date('Y') . '/' . date('m') . '/',
							'filename' => 'live_' . $v['id'] . '.png?time=' . TIMENOW
						);
					}
					else
					{
						if ($v['logo_audio'])
						{
							$v['snap'] = $v['logo_audio'];
						}
						else
						{
							$v['snap'] = $v['logo_rectangle'];
						}
					}
						
					if ($is_server)
					{
						$v['server_info'] = $server_info;
					}
					unset($v['record_uri']);
					$this->addItem($v);
				}
			}
		}
		$this->output();
	}

	public function detail()
	{
		$id 	= trim($this->input['id']);
		if (!$id)
		{
			$id = trim($this->input['channel_id']);
		}
		$appid  = intval($this->input['appid']);

		$info = $this->mChannel->detail($id);

		if ($info['client_logo'][$appid])
		{
			unset($info['client_logo'][$appid]['appid'], $info['client_logo'][$appid]['appname']);
			$info['logo_rectangle'] = $info['client_logo'][$appid];
		}
		$info['square'] = $info['logo_rectangle'];
		unset($info['client_logo']);

		$info['save_time'] = $info['time_shift'];

		$server_id = $info['server_id'];
		if ($server_id)
		{
			$server_info   = $this->mServerConfig->get_server_config_by_id($server_id);
			$type 		   = $server_info['type'] ? $server_info['type'] : 'wowza';
			$server_config = $this->mChannel->get_server_info($server_info);
				
			$host		= $server_config['host'];
			$output_dir	= $server_config['output_dir'];
				
			$ntpTime = 0;
			if ($host && $output_dir && $type == 'wowza')
			{
				include CUR_CONF_PATH . 'lib/livemms.class.php';
				$mLivemms = new livemms();
				$ret_ntpTime = $mLivemms->outputNtpTime($host, $output_dir);

				if ($ret_ntpTime['result'])
				{
					$ntpTime = $ret_ntpTime['ntp']['utc'];
				}
			}
		}

		$info['server_type'] = $type;
		$info['server_time'] = $ntpTime ? intval($ntpTime/1000) : TIMENOW;
		unset($info['record_uri']);
		if(($this->input['id']||$this->input['channel_id'])&&$info['id']&&$this->user['user_id']&&$this->input['iscreditsrule'])
		{
			$credit_rules = $this->callMemberCreditsRules($this->user['user_id'], APP_UNIQUEID, MOD_UNIQUEID,0, $info['id']);
				/**积分文案处理**/
			$info['copywriting_credit'] = $this->members->copywriting_credit(array($credit_rules)); 
		}
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$info = $this->getcount();
		$this->addItem($info);
		$this->output();
	}
	
	private function getcount()
	{
		//默认取频道接管数据
		if ($this->settings['App_live_takeover'] && !$this->input['fetch_live'])
		{
			$is_sys		= isset($this->input['is_sys']) ? intval($this->input['is_sys']) : 1;
			$data = array(
				'is_sys'	=> $is_sys,
			);
			$info = $this->get_live_takeover_count($data);
		}
		else
		{
			$condition = $this->get_condition();
			$info = $this->mChannel->count($condition);
		}
		return $info;
	}

	/**
	 * 根据频道id获取频道信息
	 * $id 频道id 1,2,3
	 * $is_stream 是否带频道信号信息 (1-是 0-否)
	 * $is_server 是否带直播服务器信息 (1-是 0-否)
	 * $field 频道字段
	 * Enter description here ...
	 */
	public function get_channel_info_by_id()
	{
		$id 		= trim($this->input['id']);
		$is_stream 	= intval($this->input['is_stream']);
		$field 		= $this->input['field'] ? trim($this->input['field']) : ' * ';
		$is_server 	= intval($this->input['is_server']);
		$appid 		= intval($this->input['appid']);

		if (!$id)
		{
			$this->errorOutput('未传入频道id');
		}
		if ($this->input['live'])
		{
			$this->settings['App_live_takeover'] = array();
		}
		if ($this->settings['App_live_takeover'] && !$this->input['fetch_live'])
		{
			$data = array(
				'id'	=> $id,
			);
			$channel_info = $this->get_live_takeover_info($data);
		}
		else
		{
			$channel_info = $this->mChannel->get_channel_info_by_id($id, $field, $is_stream);
		}

		if (!empty($channel_info))
		{
			$server_id = array();
			foreach ($channel_info AS $v)
			{
				$server_id[] = $v['server_id'];
			}
		}

		//服务器配置
		if (!empty($server_id))
		{
			$server_id	   	= implode(',', @array_unique($server_id));
			$_server_info	= $this->mServerConfig->get_server_config($server_id);
		}
		if ($this->settings['App_live_takeover'] && !$this->input['fetch_live'])
		{
			foreach ($channel_info AS $v)
			{
				$server_info = $_server_info[$v['server_id']];
				$server_info = $this->mChannel->get_server_info($server_info);
				if ($is_server && $v['server_id'])
				{
					$v['server_info'] = $server_info;
				}
				if (!$is_stream)
				{
					unset($v['record_uri']);
				}
				if($this->settings['schedule_control_wowza']['is_wowza'])
				{
					$v['schedule_control'] = unserialize($v['schedule_control']);
				}
				$this->addItem($v);
			}
		}
		else
		{
			foreach ($channel_info AS $v)
			{
				$server_info = $_server_info[$v['server_id']];
				$type 		 = $server_info['type'] ? $server_info['type'] : 'wowza';
				$v['server_type'] = $type;
				$server_info = $this->mChannel->get_server_info($server_info);
					
				$wowzaip_output = $server_info['wowzaip_output'];
				//直播
				$live_wowzaip_output   = $server_info['live_wowzaip_output'];
				//录制
				$record_wowzaip_output = $server_info['record_wowzaip_output'];

				$set_server_info = array(
					'input_dir'				=> $server_info['input_dir'],
					'output_dir'			=> $server_info['output_dir'],
					'wowzaip_output'		=> $wowzaip_output,
					'rtmp_output'		=> $server_info['rtmp_output'],
					'live_wowzaip_output'	=> $live_wowzaip_output,
					'record_wowzaip_output'	=> $record_wowzaip_output,
					'output_append_host'	=> $output_append_host,
					'input_port'	=> $_server_info[$v['server_id']]['input_port'],
					'output_port'	=> $_server_info[$v['server_id']]['output_port'],
					'type'			=> $type,
					'is_rand'		=> 0,
				);
				if (!empty($v['channel_stream']))
				{
					$channel_stream = $record_stream = array();
					foreach ($v['channel_stream'] AS $kk => $vv)
					{
						$vv['code'] 			= $v['code'];
						$vv['is_live']			= $v['is_live'];
						$vv['is_record']		= $v['is_record'];
						$vv['is_mobile_phone']	= $v['is_mobile_phone'];
						$vv['server_id']		= $v['server_id'];

						$set_stream_url = $this->mChannel->set_stream_url($set_server_info, $vv);

						foreach ($set_stream_url['channel_stream'] AS $kkk => $vvv)
						{
							$vv[$kkk] = $vvv;
						}

						$record_stream[] = $set_stream_url['record_stream'];

						unset($vv['code'], $vv['is_live'], $vv['is_record'], $vv['is_mobile_phone'], $vv['server_id']);
						$channel_stream[] = $vv;
					}
					$v['channel_stream'] = $channel_stream;
					$v['record_stream']	 = ($type == 'tvie') ? array() : $record_stream;
				}

				if ($v['client_logo'][$appid])
				{
					unset($v['client_logo'][$appid]['appid'], $v['client_logo'][$appid]['appname']);
					$v['logo_rectangle'] = $v['client_logo'][$appid];
				}
				unset($v['client_logo']);

				if ($is_server)
				{
					$v['server_info'] = $server_info;
				}

				//频道截图
				if (!$v['is_audio'])
				{
					$v['snap'] = array(
						'host' => $this->mImgUrl . LIVE_CONTROL_LIST_PREVIEWIMG_URL, 
						'dir' => '',
						'filepath' => date('Y') . '/' . date('m') . '/',
						'filename' => 'live_' . $v['id'] . '.png?time=' . TIMENOW
					);
				}
				else
				{
					if ($v['logo_audio'])
					{
						$v['snap'] = $v['logo_audio'];
					}
					else
					{
						$v['snap'] = $v['logo_rectangle'];
					}
				}
				if (!$is_stream)
				{
					unset($v['record_uri']);
				}
					
				if($this->settings['schedule_control_wowza']['is_wowza'])
				{
					$v['schedule_control'] = unserialize($v['schedule_control']);
				}

				$this->addItem($v);
			}
		}
		$this->output();
	}

	/**
	 * 获取频道输出信息 (包含当前节目单和下一个节目单,输出url,m3u8,频道截图)
	 * $channel_id (1,2,3,4)
	 * Enter description here ...
	 */
	public function get_channel_by_id()
	{
		$channel_id 	= trim($this->input['channel_id']);
		$appid			= intval($this->input['appid']);

		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}

		if ($this->settings['App_live_takeover'] && !$this->input['fetch_live'])
		{
			$data = array(
				'channel_id' => $channel_id,
			);
			$channel_info = $this->get_live_takeover_info($data);
		}
		else
		{
			$field = 'id, name, aspect, code, main_stream_name, is_mobile_phone, logo_square, logo_rectangle, server_id, is_audio, status, drm, client_logo, logo_audio, is_live, is_record';
				
			$channel_info = $this->mChannel->get_channel_info_by_id($channel_id, $field);
		}

		if (!empty($channel_info))
		{
			$server_id = array();
			foreach ($channel_info AS $v)
			{
				$server_id[] = $v['server_id'];
			}
		}
		//服务器配置
		if (!empty($server_id))
		{
			$server_id	   	= implode(',', @array_unique($server_id));
			$_server_info	= $this->mServerConfig->get_server_config($server_id);
		}

		$channel = array();
		if ($this->settings['App_live_takeover'] && !$this->input['fetch_live'])
		{
			foreach ($channel_info AS $v)
			{
				$channel['id'] = $v['id'];
				$channel['channel'] = array(
					'name'		=> $v['name'],
					'is_audio'	=> $v['is_audio'],
					'drm'		=> $v['drm'],
					'logo'		=> $v['logo_rectangle'],
					'snap'		=> $v['snap'],
				);

				//节目单
				$program_info = $this->mProgram->getCurrentNextProgram($v['id']);

				$channel['channel']['cur_program']  = $program_info[0]['theme'] ? $program_info[0]['theme'] : '精彩节目';
				$channel['channel']['next_program'] = $program_info[1]['theme'] ? $program_info[1]['theme'] : '精彩节目';

				if (!empty($v['channel_stream']))
				{
					foreach ($v['channel_stream'] AS $kk => $vv)
					{
						if (!$v['is_sys'])
						{
							if ($vv['url'])
							{
								if ($vv['timeshift_url'])
								{
									$vv['m3u8'] = $vv['timeshift_url'];
								}
								else
								{
									$vv['m3u8'] = $vv['m3u8'];
								}

								if (strstr($vv['m3u8'], '{&#036;starttime}'))
								{
									$vv['m3u8'] = str_replace('{&#036;starttime}', TIMENOW . '000', $vv['m3u8']);
										
								}
								if (strstr($vv['m3u8'], '{&#036;duration}'))
								{
									$vv['m3u8'] = substr($vv['m3u8'], 0, -26);
								}

								if (strstr($vv['m3u8'], '{&#036;endtime}'))
								{
									$vv['m3u8'] = str_replace('{&#036;endtime}', (TIMENOW + 3600) . '000', $vv['m3u8']);
								}
							}
						}
						$channel['stream'][$kk] = array(
							'stream_name'		=> $vv['stream_name'],
							'url'		=> $vv['output_url'],
							'm3u8'		=> $vv['m3u8'],
							'bitrate'	=> $vv['bitrate'],
							'live_url'	=> $vv['live_url'],
							'live_m3u8'	=> $vv['m3u8'],
							'is_default'	=> $vv['is_default'],
						);

						$channel['record_stream'][$kk] = array(
							'url'		=> $vv['output_url'],
							'm3u8'		=> $vv['m3u8'],
						);
					}
				}


				if (!empty($v['record_stream']) && $v['is_sys'])
				{
					foreach ($v['record_stream'] AS $kk => $vv)
					{
						$channel['record_stream'][$kk] = array(
							'url'		=> $vv['output_url'],
							'm3u8'		=> $vv['m3u8'],
						);
					}
				}
				$this->addItem($channel);
			}
		}
		else
		{
			foreach ($channel_info AS $v)
			{
				if ($v['client_logo'][$appid])
				{
					unset($v['client_logo'][$appid]['appid'], $v['client_logo'][$appid]['appname']);
					$v['logo_rectangle'] = $v['client_logo'][$appid];
				}
				unset($v['client_logo']);

				$channel['id'] = $v['id'];
				$channel['channel'] = array(
					'name'		=> $v['name'],
					'is_audio'	=> $v['is_audio'],
					'drm'		=> $v['drm'],
					'aspect'		=> $v['aspect'],
					'logo'		=> $v['logo_rectangle'],
				);

				$channel['channel']['snap'] = array(
					'host' => $this->mImgUrl . LIVE_CONTROL_LIST_PREVIEWIMG_URL, 
					'dir' => '',
					'filepath' => date('Y') . '/' . date('m') . '/',
					'filename' => 'live_' . $v['id'] . '.png?time=' . TIMENOW
				);

				if ($v['is_audio'])
				{
					if ($v['logo_audio'])
					{
						$channel['channel']['snap'] = $v['logo_audio'];
					}
					else
					{
						$channel['channel']['snap'] = $v['logo_rectangle'];
					}
				}

				//节目单
				$program_info = $this->mProgram->getCurrentNextProgram($v['id']);
				$channel['channel']['cur_program']  = $program_info[0]['theme'] ? $program_info[0]['theme'] : '精彩节目';
				$channel['channel']['next_program'] = $program_info[1]['theme'] ? $program_info[1]['theme'] : '精彩节目';

				$server_info = $_server_info[$v['server_id']];
				$type		 = $server_info['type'] ? $server_info['type'] : 'wowza';
				$server_info = $this->mChannel->get_server_info($server_info);

				$wowzaip_output = $server_info['wowzaip_output'];
				//直播
				$live_wowzaip_output   = $server_info['live_wowzaip_output'];
				//录制
				$record_wowzaip_output = $server_info['record_wowzaip_output'];

				if (!empty($v['channel_stream']))
				{
					$channel_stream = $record_stream = array();
					foreach ($v['channel_stream'] AS $kk => $vv)
					{
						//if ($v['main_stream_name'] == $vv['stream_name'])
						{
							$vv['code'] = $v['code'];
							$function = 'set_url_info_' . $type;
							$set_url_info = $this->mChannel->$function(array(), $vv);
								
							$app_name 	 = $set_url_info['app_name'];
							$stream_name = $set_url_info['stream_name'];
							$m3u8_type	 = $set_url_info['m3u8_type'];
							$flv		 = $set_url_info['flv'];
							$starttime	 = $set_url_info['starttime'];
							$dvr		 = $set_url_info['dvr'];

							$vv['url'] = hg_set_stream_url($wowzaip_output, $app_name, $stream_name, $flv, $type);
								
							if ($v['is_mobile_phone'])
							{
								if ($this->settings['signleliveaddr'])
								{
									$vv['m3u8'] = $this->settings['signleliveaddr'] . $v['code'] . '.stream/playlist.m3u8';
								}
								else
								{
									$vv['m3u8'] = hg_set_stream_url($wowzaip_output, $app_name, $stream_name, $m3u8_type, $starttime, $dvr, '', 'http://', '/' . $vv['stream_name'] . '/live.m3u8');
								}
							}
								
							//直播
							if ($live_wowzaip_output && $v['is_live'])
							{
								$vv['live_url'] = hg_set_stream_url($live_wowzaip_output, $app_name, $stream_name, $flv);

								if ($v['is_mobile_phone'])
								{
									if ($this->settings['signleliveaddr'])
									{
										$vv['live_m3u8'] = $this->settings['signleliveaddr'] . $v['code'] . '.stream/playlist.m3u8';
									}
									else
									{
										$vv['live_m3u8'] = hg_set_stream_url($live_wowzaip_output, $app_name, $stream_name, $m3u8_type, $starttime, $dvr);
									}
								}
							}
							else
							{
								$vv['live_url']  = $vv['url'];
								$vv['live_m3u8'] = $vv['m3u8'];
							}
								
							//录制
							if ($record_wowzaip_output && $v['is_record'])
							{
								$record_stream[$kk]['url'] = hg_set_stream_url($record_wowzaip_output, $app_name, $stream_name, $flv);

								if ($v['is_mobile_phone'])
								{
									if ($this->settings['signleliveaddr'])
									{
										$record_stream[$kk]['m3u8'] = $this->settings['signleliveaddr'] . $v['code'] . '.stream/playlist.m3u8';
									}
									else
									{
										$record_stream[$kk]['m3u8'] = hg_set_stream_url($record_wowzaip_output, $app_name, $stream_name, $m3u8_type, $starttime, $dvr);
									}
								}
							}
							else
							{
								$record_stream[$kk]['url']  = $vv['url'];
								$record_stream[$kk]['m3u8'] = $vv['m3u8'];
							}
								
							$channel_stream[] = array(
								'stream_name'		=> $vv['stream_name'],
								'url'		=> $vv['url'],
								'm3u8'		=> $vv['m3u8'],
								'bitrate'	=> $vv['bitrate'],
								'live_url'	=> $vv['live_url'],
								'live_m3u8'	=> $vv['live_m3u8'],
								'is_default'	=> $vv['is_default'],
							);
						}
					}
					$channel['stream'] 		  = $channel_stream;
					$channel['record_stream'] = ($type == 'tvie') ? array() : $record_stream;
				}
				$this->addItem($channel);
			}
		}
		$this->output();
	}
	/**
	 * 直播频道对外输出接口
	 * Enter description here ...
	 */
	public function channels()
	{
		$condition 	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count 		= $this->input['count'] ? intval($this->input['count']) : 20;
		$appid		= intval($this->input['appid']);

		if ($this->settings['App_live_takeover'] && !$this->input['fetch_live'])
		{
			$channel_id = trim($this->input['id']);
			if (!$channel_id)
			{
				$channel_id = trim($this->input['channel_id']);
			}
			$data = array(
				'offset' 	 => $offset,
				'count' 	 => $count,
				'channel_id' => $channel_id,
			);
			$channel_info = $this->get_live_takeover_info($data);
		}
		else
		{
			$field 		  = 'id, name, code, main_stream_name, is_mobile_phone, logo_square, logo_rectangle, server_id, is_audio, status, drm, client_logo, time_shift, is_live, is_record, logo_audio, aspect';
			$channel_info = $this->mChannel->get_channel_info($condition, $offset, $count, '', $field);
		}
		if (!empty($channel_info))
		{
			$server_id = array();
			foreach ($channel_info AS $channel)
			{
				$server_id[]  = $channel['server_id'];
			}
				
			//服务器配置
			if (!empty($server_id))
			{
				$server_id	   	= implode(',', @array_unique($server_id));
				$_server_info	= $this->mServerConfig->get_server_config($server_id);
			}
				
			if ($this->settings['App_live_takeover'] && !$this->input['fetch_live'])
			{
				foreach ($channel_info AS $channel)
				{
					$logo = array(
						'rectangle' => $channel['logo_rectangle'],
						'square' 	=> $channel['logo_rectangle'],
					);
						
					//节目单
					$program_info = $this->mProgram->getCurrentNextProgram($channel['id']);
						
					$cur_program = array(
						'start_time' => $program_info[0]['start'] ? $program_info[0]['start'] : date('H:i'),
						'program' 	 => $program_info[0]['theme'] ? $program_info[0]['theme'] : '精彩节目',
					);
						
					$next_program = array(
						'start_time' => $program_info[1]['start'] ? $program_info[1]['start'] : date('H:i', (TIMENOW + 3600)),
						'program' 	 => $program_info[1]['theme'] ? $program_info[1]['theme'] : '精彩节目',
					);
						
					$m3u8 = $channel['channel_stream'][0]['m3u8'];
						
					$channel_stream = array();
					if (!empty($channel['channel_stream']))
					{
						foreach ($channel['channel_stream'] AS $kk => $vv)
						{
							$channel_stream[$kk] = array(
								'url'		=> $vv['output_url'],
								'm3u8'		=> $vv['m3u8'],
								'bitrate'	=> $vv['bitrate'],
								'live_url'	=> $vv['live_url'],
								'live_m3u8'	=> $vv['live_m3u8'],
							);
						}
					}
					$record_stream = array();
					if (!empty($channel['record_stream']))
					{
						foreach ($channel['record_stream'] AS $kk => $vv)
						{
							$record_stream[$kk] = array(
								'url'		=> $vv['output_url'],
								'm3u8'		=> $vv['m3u8'],
							);
						}
					}
						
					$return = array(
						'id' 			=> $channel['id'],
						'name' 			=> $channel['name'],
						'logo' 			=> $logo,
						'snap' 			=> $channel['snap'],
						'm3u8' 			=> $m3u8,
						'cur_program' 	=> $cur_program,
						'save_time' 	=> $channel['time_shift'],
						'next_program' 	=> $next_program,
						'audio_only' 	=> $channel['is_audio'],
						'aspect' 			=> $channel['aspect'],
						'cmid' 			=> $channel['cmid'],
						'channel_stream'=> $channel_stream,
						'record_stream' => $record_stream,
					);
					if(($this->input['id']||$this->input['channel_id'])&&$return['id']&&$this->user['user_id']&&$this->input['iscreditsrule'])
					{
						$credit_rules = $this->callMemberCreditsRules($this->user['user_id'], APP_UNIQUEID, MOD_UNIQUEID,0, $return['id']);
						/**积分文案处理**/
						$return['copywriting_credit'] = $this->members->copywriting_credit(array($credit_rules)); 
					}
					$this->addItem($return);
				}
			}
			else
			{
				foreach ($channel_info AS $channel)
				{
					$type 		 = $_server_info[$channel['server_id']]['type'] ? $_server_info[$channel['server_id']]['type'] : 'wowza';
					$server_info = $this->mChannel->get_server_info($_server_info[$channel['server_id']]);
					$channel['server_info'] = $server_info;
						
					$wowzaip_output 	 	= $server_info['wowzaip_output'];
					//直播
					$live_wowzaip_output 	= $server_info['live_wowzaip_output'];
					//录制
					$record_wowzaip_output 	= $server_info['record_wowzaip_output'];
						
					if ($channel['client_logo'][$appid])
					{
						unset($channel['client_logo'][$appid]['appid'], $channel['client_logo'][$appid]['appname']);
						$channel['logo_rectangle'] = $channel['client_logo'][$appid];
					}
						
					$logo = array(
						'rectangle' => $channel['logo_rectangle'],
						'square' 	=> $channel['logo_rectangle'],
					);
						
					$snap = array(
						'host'	   => $this->mImgUrl . LIVE_CONTROL_LIST_PREVIEWIMG_URL, 
						'dir' 	   => '',
						'filepath' => date('Y') . '/' . date('m') . '/',
						'filename' => 'live_' . $channel['id'] . '.png?time=' . TIMENOW
					);
						
					if ($channel['is_audio'])
					{
						if ($channel['logo_audio'])
						{
							$snap = $channel['logo_audio'];
						}
						else
						{
							$snap = $channel['logo_rectangle'];
						}
					}
						
					$channel_stream = $record_stream = array();
					if ($channel['channel_stream'])
					{
						foreach ($channel['channel_stream'] AS $kk => $vv)
						{
							if ($channel['m3u8_stream_name'])
							{
								$stream_n = $channel['m3u8_stream_name'];
							}
							else
							{
								$stream_n = $channel['main_stream_name'];
							}
							//if ($stream_n == $vv['stream_name'])
							{
								$vv['code'] = $channel['code'];
								$function = 'set_url_info_' . $type;
								$set_url_info = $this->mChannel->$function(array(), $vv);
									
								$app_name 	 = $set_url_info['app_name'];
								$stream_name = $set_url_info['stream_name'];
								$m3u8_type	 = $set_url_info['m3u8_type'];
								$flv		 = $set_url_info['flv'];
	
								$url = hg_set_stream_url($wowzaip_output, $app_name, $stream_name, $flv);
									
								if ($channel['is_mobile_phone'])
								{
									if ($this->settings['signleliveaddr'])
									{
										$m3u8 = $this->settings['signleliveaddr'] . $channel['code'] . '.stream/playlist.m3u8';
									}
									else
									{
										$m3u8 = hg_set_stream_url($wowzaip_output, $app_name, $stream_name, $m3u8_type, '', '', '', 'http://', '/' . $vv['stream_name'] . '/live.m3u8');
									}
	
									if ($live_wowzaip_output && $channel['is_live'])
									{
										$m3u8 = hg_set_stream_url($live_wowzaip_output, $app_name, $stream_name, $m3u8_type);
									}
								}
	
								//直播
								if ($live_wowzaip_output && $channel['is_live'])
								{
									$live_url = hg_set_stream_url($live_wowzaip_output, $app_name, $stream_name, $flv);
	
									if ($channel['is_mobile_phone'])
									{
										if ($this->settings['signleliveaddr'])
										{
											$live_m3u8 = $this->settings['signleliveaddr'] . $channel['code'] . '.stream/playlist.m3u8';
										}
										else
										{
											$live_m3u8 = hg_set_stream_url($live_wowzaip_output, $app_name, $stream_name, $m3u8_type);
										}
									}
								}
								else
								{
									$live_url  = $url;
									$live_m3u8 = $m3u8;
								}
									
								//录制
								if ($record_wowzaip_output && $channel['is_record'])
								{
									$record_stream[$kk]['url'] = hg_set_stream_url($record_wowzaip_output, $app_name, $stream_name, $flv);
	
									if ($channel['is_mobile_phone'])
									{
										if ($this->settings['signleliveaddr'])
										{
											$record_stream[$kk]['m3u8'] = $this->settings['signleliveaddr'] . $channel['code'] . '.stream/playlist.m3u8';
										}
										else
										{
											$record_stream[$kk]['m3u8'] = hg_set_stream_url($record_wowzaip_output, $app_name, $stream_name, $m3u8_type);
										}
									}
								}
								else
								{
									$record_stream[$kk]['url']  = $url;
									$record_stream[$kk]['m3u8'] = $m3u8;
								}
									
								$bitrate = $vv['bitrate'];
								$channel_stream[] = array(
									'url'		=> $url,
									'name' => $vv['name'],
									'stream_name' => $vv['stream_name'],
									'm3u8'		=> $m3u8,
									'bitrate' 	=> $bitrate,
									//'live_url' 	=> $live_url,
									//'live_m3u8' => $live_m3u8,
								);
							}
						}
					}
						
					//节目单
					$program_info = $this->mProgram->getCurrentNextProgram($channel['id']);
						
					$cur_program = array(
						'start_time' => $program_info[0]['start'] ? $program_info[0]['start'] : date('H:i'),
						'program' 	 => $program_info[0]['theme'] ? $program_info[0]['theme'] : '精彩节目',
					);
						
					$next_program = array(
						'start_time' => $program_info[1]['start'] ? $program_info[1]['start'] : date('H:i', (TIMENOW + 3600)),
						'program' 	 => $program_info[1]['theme'] ? $program_info[1]['theme'] : '精彩节目',
					);
						
					$return = array(
						'id' 			=> $channel['id'],
						'name' 			=> $channel['name'],
						'logo' 			=> $logo,
						'snap' 			=> $snap,
						'm3u8' 			=> $url,
						'cur_program' 	=> $cur_program,
						'save_time' 	=> $channel['time_shift'],
						'next_program' 	=> $next_program,
						'audio_only' 	=> $channel['is_audio'],
						'aspect' 			=> $channel['aspect'],
						'cmid' 			=> $channel['cmid'],
						'channel_stream'=> $channel_stream,
					);
					if(($this->input['id']||$this->input['channel_id'])&&$return['id']&&$this->user['user_id']&&$this->input['iscreditsrule'])
					{
						$credit_rules = $this->callMemberCreditsRules($this->user['user_id'], APP_UNIQUEID, MOD_UNIQUEID,0, $return['id']);
						/**积分文案处理**/
						$return['copywriting_credit'] = $this->members->copywriting_credit(array($credit_rules)); 
					}
                                        
                                        //统计
                if($this->input['need_access'])
                {
                    include_once(ROOT_PATH.'lib/class/access.class.php');
                    $access_obj = new access();
                    $ret = $access_obj->add_access($return['id'],0,APP_UNIQUEID,MOD_UNIQUEID,$return['name']);
                }
                                        
					if($this->input['ad_group'])
					{
						$return['ad'] = $this->getAds($this->input['ad_group'], $return);
					}
					$this->addItem($return);
				}
			}
		}
		$this->output();
	}

	public function getChildNodeByFid()
	{
		if(empty($this->input['node_id']))
		{
			$this->errorOutput('未传入节点id');
		}

		$sql = "SELECT * FROM " . DB_PREFIX ."channel_node WHERE id IN(" . trim($this->input['node_id']) . ")";
		$q = $this->db->query($sql);
		$all_node = array();
		while($row = $this->db->fetch_array($q))
		{
			if($row['childs'])
			{
				$all_node[] = $row['childs'];
				$this->addItem($row['childs']);
			}
		}
		if(!$all_node)
		{
			$this->addItem(trim($this->input['node_id']));
		}
		$this->output();
	}

	public function getFatherNodeByid()
	{
		if(empty($this->input['node_id']))
		{
			$this->errorOutput('未传入节点id');
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."channel_node WHERE id IN(" . trim($this->input['node_id']) . ")";
		$q = $this->db->query($sql);
		$all_node = array();
		while($row = $this->db->fetch_array($q))
		{
			if($row['parents'])
			{
				$all_node[] = $row['parents'];
				$this->addItem($row['parents']);
			}
		}
		if(!$all_node)
		{
			$this->addItem(trim($this->input['node_id']));
		}
		$this->output();
	}
	
    //获取分页的一些参数
    public function get_page_data()
    {
        $countinfo = $this->getcount();
        $total_num = $countinfo['total']; //总的记录数
        $page_num  = $this->input['count'] ? intval($this->input['count']) : 20;
        //总页数
        if (intval($total_num % $page_num) == 0)
        {
            $return['total_page'] = intval($total_num / $page_num);
        }
        else
        {
            $return['total_page'] = intval($total_num / $page_num) + 1;
        }
        $return['total_num']    = $total_num; //总的记录数
        $return['page_num']     = $page_num; //每页显示的个数
        $return['current_page'] = $this->input['pp']; //当前页码
        $this->addItem($return);
        $this->output();
    }

	private function get_condition()
	{
		$condition = ' AND status = 1';

		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND name like \'%' . trim($this->input['k']) . '%\'';
		}

		if (isset($this->input['id']) && $this->input['id'])
		{
			$this->input['id'] = hg_filter_ids($this->input['id']);
			$condition .= " AND id IN (" . trim($this->input['id']) . ")";
		}

		if (isset($this->input['channel_id']) && $this->input['channel_id'])
		{
				
			$this->input['channel_id'] = hg_filter_ids($this->input['channel_id']);
			$condition .= " AND id IN (" . trim($this->input['channel_id']) . ")";
		}

		if (isset($this->input['code']) && $this->input['code'])
		{
			$condition .= " AND code = '" . trim($this->input['code']) . "' ";
		}

		if (isset($this->input['is_mobile_phone']))
		{
			$condition .= " AND is_mobile_phone = " . intval($this->input['is_mobile_phone']);
		}

		if (isset($this->input['is_control']))
		{
			$condition .= " AND is_control = " . intval($this->input['is_control']);
		}
		if (intval($this->input['get_record']))
		{
			$condition .= " AND can_record = 1";
		}

		if (isset($this->input['is_audio']))
		{
			$condition .= " AND is_audio = " . intval($this->input['is_audio']);
		}

		if (isset($this->input['audio_only']))
		{
			$condition .= " AND is_audio = " . intval($this->input['audio_only']);
		}

		if (isset($this->input['status']))
		{
			$condition .= " AND status = " . intval($this->input['status']);
		}

		if (isset($this->input['not_id']) && $this->input['not_id'])
		{
			$condition .= " AND id NOT IN (" . trim($this->input['not_id']) . ")";
		}

		if (isset($this->input['node_id']) && $this->input['node_id'])
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."channel_node WHERE id IN(" . trim($this->input['node_id']) . ")";
			$q = $this->db->query($sql);
			$all_node = $space = '';
			while($row = $this->db->fetch_array($q))
			{
				if($row['childs'])
				{
					$all_node .= $space . $row['childs'];
					$space = ',';
				}
			}
			if($all_node)
			{
				$all_node .= ',' .  trim($this->input['node_id']);
				$node_id = implode(',',array_unique(explode(',',$all_node)));
			}
			else
			{
				$node_id = trim($this->input['node_id']);
			}
			$condition .= " AND node_id IN (" . $node_id . ")";
		}

		if (isset($this->input['server_id']))
		{
			$condition .= " AND server_id = " . intval($this->input['server_id']);
		}
		return $condition;
	}

	public function get_live_takeover_info($data = array())
	{
		if ($this->settings['App_live_takeover'])
		{
			if (!class_exists('curl'))
			{
				include ROOT_PATH . 'lib/class/curl.class.php';
			}
			$this->mLiveTakeover = new curl($this->settings['App_live_takeover']['host'], $this->settings['App_live_takeover']['dir']);
		}

		if (!$this->mLiveTakeover)
		{
			return array();
		}

		$this->mLiveTakeover->setSubmitType('post');
		$this->mLiveTakeover->setReturnFormat('json');
		$this->mLiveTakeover->initPostData();
		$this->mLiveTakeover->addRequestData('a', 'show');

		foreach ($data AS $k => $v)
		{
			$this->mLiveTakeover->addRequestData($k, $v);
		}

		$ret = $this->mLiveTakeover->request('channel.php');
		//hg_pre($ret);
		return $ret;
	}

	public function get_live_takeover_count($data = array())
	{
		if ($this->settings['App_live_takeover'])
		{
			if (!class_exists('curl'))
			{
				include ROOT_PATH . 'lib/class/curl.class.php';
			}
			$this->mLiveTakeover = new curl($this->settings['App_live_takeover']['host'], $this->settings['App_live_takeover']['dir']);
		}

		if (!$this->mLiveTakeover)
		{
			return array();
		}

		$this->mLiveTakeover->setSubmitType('post');
		$this->mLiveTakeover->setReturnFormat('json');
		$this->mLiveTakeover->initPostData();
		$this->mLiveTakeover->addRequestData('a', 'count');

		foreach ($data AS $k => $v)
		{
			$this->mLiveTakeover->addRequestData($k, $v);
		}

		$ret = $this->mLiveTakeover->request('channel.php');
		//hg_pre($ret);
		return $ret[0];
	}
	/**
	 *
	 * 用户观看直播增加积分 ...
	 */
	private function callMemberCreditsRules($member_id,$appUniqueid,$modUniqueid,$sortId,$contentId)
	{
		$this->members->Initoperation();
		$this->members->Setoperation(APP_UNIQUEID);
		return $this->members->get_credit_rules($member_id,$appUniqueid,$modUniqueid,$sortId,$contentId);
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