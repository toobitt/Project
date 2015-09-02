<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function update|channelCodeEdit|delete|stream_state|dopublish
*
* $Id: channel_update.php 7439 2012-07-02 01:09:44Z zhuld $
***************************************************************************/
define('MODULE_UNIQUEID','channel');
require('global.php');
require_once(ROOT_PATH.'lib/class/statistic.class.php');
class channelUpdateApi extends BaseFrm
{
	private $mMaterial;
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 更新频道数据 (若改变流信息则删除后重新创建流信息)
	 * @name update
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $name string 频道名称
	 * @param $logo string 台标
	 * @param $stream_id int 信号ID
	 * @param $save_time int 回看时间(小时)
	 * @param $live_delay int 延时时间 (分钟)
	 * @param $is_live tinyint 是否播控 (1-是 0-否)
	 * @param $stream_name string 流名称	not null
	 * @param $main_stream_name string 主信号名称
	 * @param $beibo string 备播信号  (暂最多支持2个)
	 * @param $uri_in_num tinyint 输入流数目
	 * @param $uri_out_num tinyint 输出流数目
	 * @param $level tinyint 频道层数
	 * @param $open_ts tinyint 是否支持手机流 (1-是 0-否)
	 * @param $record_time int 自动收录节目时间偏差设置 (±30秒 大于30秒就等于30秒，小于-30秒就等于-30秒)
	 * @param $audio_only tinyint 记录是否是音频 (1-是 0-否 )
	 * @param $update_time int 更新时间
	 * @param $channel_id int 频道ID
	 * @param $delay_stream_id int 延时层流ID
	 * @param $chg_stream_id int 切播层流ID
	 * @param $out_stream_id int 输出层流ID
	 * @param $stream_name string 流名称
	 * @param $out_stream_name string 输出流名称
	 * @param $is_main tinyint 是否主流 (1-是 0-否)
	 * @param $bitrate int 码流
	 * @param $flag_stream 标识(单流改为多流)
	 * @param $drm tyinint 防盗链设置 (1-启用  0-关闭)
	 * @param $logo_info array logo素材信息
	 * @return $ret['id'] int 频道ID
	 * @include tvie_api.php
	 */
	function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('频道id不存在或已被删除');
		}
		$name = urldecode($this->input['name']);
		if(!$name)
		{
			$this->errorOutput('频道名称不能为空！');
		}	
		$save_time = $this->input['save_time'] ? intval($this->input['save_time']) : 0;		//回看时间
		$live_delay = $this->input['live_delay'] ? intval($this->input['live_delay']) : 0;	//延时时间
		$is_live = intval($this->input['is_live']);	//是否播控    1表示有播控  0表示无播控
		
		$drm = intval($this->input['drm']);	//防盗链设置
		
		$stream_name = $this->input['stream_name'];
		if (!$stream_name)
		{
			$this->errorOutput('至少为频道选择一条流');
		}
		$main_stream_name = $this->input['main_stream_name'];
		//输入流的数目
		$uri_in_num = count($stream_name); 
		//层数目
		if($uri_in_num > 1) //多流
		{
			$level = 1;
		}
		else //单流
		{
			if (!$is_live) //无播控
			{
				$level = 1;
			}
			elseif(!$live_delay) //有播控无延时
			{
				$level = 2;
			}
			else //有播控有延时
			{
				$level = 3;
			}
		}
		
		if($this->input['beibo'])
		{
			if($is_live && count($this->input['beibo']) > 2)
			{
				$this->errorOutput('最多两个备播信号，请重新选择！');
			}
			foreach($this->input['beibo'] as $value)
			{
				$beibo_key_value = explode('#',urldecode($value));
				$beibo[$beibo_key_value[0]] = $beibo_key_value[1];
			}
			$beibo = serialize($beibo);
		}
		//频道信息	
		$sql = "SELECT * FROM " . DB_PREFIX . "channel WHERE id=" . $id;
		$channel_info = $this->db->query_first($sql);
		
		$code = $channel_info['code_2'];
		$stream_id = $channel_info['stream_id'];
		if($stream_id != intval($this->input['stream_id']))
		{
			$stream_id = intval($this->input['stream_id']);
		}
	
		$delay_id = $channel_info['delay_id'];
		$chg_id = $channel_info['chg_id'];
		$out_id = $channel_info['ch_id'];
//		$level = $channel_info['level'];
	
		//频道流信息
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN(" . $id . ") ";//ORDER BY id DESC";
		$q = $this->db->query($sql);
		$channel_stream = $channel_stream_withkey = $channel_stream_name = array();
		
		while($row = $this->db->fetch_array($q))
		{
			$channel_stream[] = $row;
			$channel_stream_withkey[$row['stream_name']] = $row;
			$channel_stream_name[] = $row['stream_name'];
		} 
		
		//信号信息
		$sql = "SELECT * FROM " . DB_PREFIX . "stream WHERE id=" .$stream_id;
		$streams = $this->db->query_first($sql);
		if (!$streams)
		{
			$this->errorOutput('所选信号不存在，请重新选择');
		}
		$other_info = unserialize($streams['other_info']);
		
		if (!$other_info)
		{
			$this->errorOutput('所选信号没有信号流');
		}
		//模板和stream 组成的数据
		$stream_name_arr = array();
		foreach($other_info as $v)
		{
			$stream_name_arr[$v['name']] = $v;
		}
	 	$stream_info = array();
		foreach ($stream_name AS $n)
		{
			if ($stream_name_arr[$n])
			{
				$stream_info[] = $stream_name_arr[$n];
			}
		}
		//模板和channel_stream 组成的数据
	
		$stream_name_arr_new = array();
		foreach($channel_stream as $v)
		{
			$stream_name_arr_new[$v['stream_name']] = $v;
		}
		$stream_info_new = array();
		foreach($stream_name as $n)
		{
			if($stream_name_arr_new[$n])
			{
				$stream_info_new[] = $stream_name_arr_new[$n];
			}
		}
		//模板流名称和channel_stream差集
		if($uri_in_num < intval($channel_info['uri_in_num']))
		{
			$input_channel_stream_diff = array_diff($channel_stream_name, $stream_name);
		}
		else
		{	
			$input_channel_stream_diff = array_diff($stream_name, $channel_stream_name);
		}
		
		$diff_stream_info = array();
		foreach($channel_stream as $v)
		{
			foreach($input_channel_stream_diff as $vv)
			{
				if($v['stream_name'] == $vv)
				{
					$diff_stream_info[] = $v;
				}
			}
		}
		//模板、stream差集  组成的新数据
		$diff_stream_info_other = array();
		foreach($stream_info as $v)
		{
			foreach($input_channel_stream_diff as $vv)
			{
				if($v['name'] == $vv)
				{
					$diff_stream_info_other[] = $v;
				}
			}
		}
		//channel_stream、模板 	 差集
		$channel_stream_diff = array_diff($channel_stream_name, $stream_name);	
		$del_channel_stream = array();
		foreach ($channel_stream AS $v)
		{
			foreach ($channel_stream_diff AS $vv)
			{
				if($v['stream_name'] == $vv)
				{
					$del_channel_stream[$v['stream_name']] = $v;
				}
			}
		}

		if (!$stream_info)
		{
			$this->errorOutput('所选流不存在或已被删除');
		}

		//开启tvie
		if($this->settings['tvie']['open'])
		{
			include(CUR_CONF_PATH . 'lib/tvie_api.php');
	
			$delay_tvie = new TVie_api($this->settings['tvie']['up_stream_server']);
			$delay_type = 'normal_virtual';
			$chg_tvie = $delay_tvie;
			$out_tvie = new TVie_api($this->settings['tvie']['stream_server']);
			
			//层数为   1
			if($level ==1)
			{
				//只更新输出层
				$out_ret_channel = $out_tvie->update_channel(
																$name,
																$save_time,
																$live_delay,
																$out_id
															);
			 
			/* 	if($out_ret_channel['message'] != 'Updated')
			 	{
			 		$this->errorOutput('频道更新失败，原因：' . $out_ret_channel['message'] . $out_ret_channel['errors']);
			 	}*/
				if($channel_info['stream_id'] != intval($this->input['stream_id']))
				{
					//改变主信号时，删除原有的信号以及流地址
					foreach ($channel_stream as $value)
					{
						$delay_ret_stream = $delay_tvie->delete_stream($value['delay_stream_id']);
						$chg_ret_stream = $chg_tvie->delete_stream($value['chg_stream_id']);
						$out_ret_stream = $out_tvie->delete_stream($value['out_stream_id']);
						$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE out_stream_id=" . $value['out_stream_id'];
						$this->db->query($sql);
					}
					//创建新的流地址
				
					foreach($stream_info as $key => $value)
					{
						//上游流地址   直接是信号流地址 
						$delay_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $value['name']), 'channels', 'tvie://');
						$chg_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => 'delay_'.$channel_info['code_2'], 'stream_name' => $value['name']));
						$out_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $value['name']), 'channels', 'tvie://');
						if ($this->input['open_ts'])
						{
							$value['backstore'] = array(
								0 => 'flv',
								1 => 'ts'
							);
						}
						if(is_array($value['backstore']))
						{
							$backstore = implode(',', $value['backstore']);
						}
						else 
						{
							$backstore = $value['backstore'];
						}
						$delay_create_stream = $delay_tvie->create_channel_stream(
																				$value['name'],
																				$value['recover_cache'],
																				$value['source_name'],
																				$delay_uri,
																				$value['drm'],
																				'flv',
																				$value['wait_relay'],
																				0,
																				$value['bitrate'],
																				$delay_id
																			);
						$chg_create_stream = $chg_tvie->create_channel_stream(
																				$value['name'],
																				$value['recover_cache'],
																				$value['source_name'],
																				$chg_uri,
																				$value['drm'],
																				'flv',
																				$value['wait_relay'],
																				0,
																				$value['bitrate'],
																				$chg_id
																			);
						$out_create_stream = $out_tvie->create_channel_stream(
																				$value['name'],
																				$value['recover_cache'],
																				$value['source_name'],
																				$out_uri,
																				$drm,
																				$backstore,
																				$value['wait_relay'],
																				0,
																				$value['bitrate'],
																				$out_id
																			);
						
						if($channel_info['stream_state'])
						{
							$out_tvie->stop_stream($out_create_stream['stream_id']);
							$out_tvie->start_stream($out_create_stream['stream_id']);
						}
						else 
						{
							$out_tvie->start_stream($out_create_stream['stream_id']);
							$out_tvie->stop_stream($out_create_stream['stream_id']);
						}
						/*if(!$out_create_stream['stream_id'])
						{
							$this->errorOutput('更新频道失败，原因：'.$out_create_stream['message']);
						}*/
						if($out_create_stream['stream_id'])
						{
							//返回创建成功后的流信息
							if($main_stream_name[0] == $value['name'])
							{
								$is_main = 1;
							}
							else 
							{
								$is_main = 0;
							}
							$channel_stream_info = array(
									'channel_id' => $id,
									'stream_id' => $stream_id,
									'delay_stream_id' => $delay_create_stream['stream_id'],
									'chg_stream_id' => $chg_create_stream['stream_id'],
									'out_stream_id' => $out_create_stream['stream_id'],
									'stream_name' => $value['name'],
									'out_stream_name' => $value['name'],
									'is_main' => $is_main,
									'bitrate' => $value['bitrate'],
									'flag_stream' => '',
									'create_time' => TIMENOW,
									'update_time' => TIMENOW,
									'ip' => hg_getip()
							);
							$cresql = "INSERT INTO " . DB_PREFIX . "channel_stream SET ";
							$space = "";
							foreach($channel_stream_info as $k => $v)
							{
								$cresql .= $space . $k . "=" . "'" . $v . "'";
								$space = ",";
							}
							$this->db->query($cresql);
						}
					}
				}
				else
				{
					if($stream_info_new)
					{
						if($input_channel_stream_diff)	//模板流名称和channel_stream差集
						{
							//有差集   删除被剔除的流信息 (暂时只支持删除)$del_channel_stream
							if($diff_stream_info)
							{
								foreach($diff_stream_info AS $value)
								{
									$delay_ret_stream = $delay_tvie->delete_stream($value['delay_stream_id']);
									$chg_ret_stream = $chg_tvie->delete_stream($value['chg_stream_id']);
									$out_ret_stream = $out_tvie->delete_stream($value['out_stream_id']);
									$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE out_stream_id=" . $value['out_stream_id'];
									$this->db->query($sql);
								}
							}
							else 
							{
								foreach($del_channel_stream AS $value)
								{
									$delay_ret_stream = $delay_tvie->delete_stream($value['delay_stream_id']);
									$chg_ret_stream = $chg_tvie->delete_stream($value['chg_stream_id']);
									$out_ret_stream = $out_tvie->delete_stream($value['out_stream_id']);
									$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE out_stream_id=" . $value['out_stream_id'];
									$this->db->query($sql);
								}
							}
							
							//更新主信号
							foreach ($stream_info_new AS $value)
							{
								if($main_stream_name[0] == $value['stream_name'])
								{
									$is_main = 1;
									$sql = "UPDATE " . DB_PREFIX . "channel_stream SET is_main=" . $is_main . " WHERE channel_id=" . $id . " AND stream_name='" . $main_stream_name[0] . "'";
									$this->db->query($sql);
								}
								else
								{
									$is_main = 0;
									$sql = "UPDATE " . DB_PREFIX . "channel_stream SET is_main=" . $is_main . " WHERE channel_id=" . $id. " AND stream_name='" . $value['stream_name'] . "'";
									$this->db->query($sql);
								}
							}
						}
						else 
						{
							//无信号流改变时数据
							$channel_stream_merge = array();
							foreach($stream_name_arr AS $key => $value)
							{
								$channel_stream_merge[$key] = array_merge($stream_name_arr[$key],$channel_stream_withkey[$key]);
							}
							//没有差集   更新流信息
							foreach ($channel_stream_merge AS $value)
							{
								//流地址
								$out_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $value['name']),'channels', 'tvie://');
								if($this->input['open_ts'])
								{
									$value['backstore'] = array(
										0 => 'flv',
										1 => 'ts'
									);
								}
								if(is_array($value['backstore']))
								{
									$backstore = implode(',', $value['backstore']);
								}
								else 
								{
									$backstore = $value['backstore'];
								}
								
								$out_update_stream = $out_tvie->update_channel_stream(
																					$value['out_stream_id'],
												    								$drm,
												    								$backstore,
												    								$value['recover_cache'],
												    								$out_uri,
												    								0,
												    								$value['wait_relay'],
												    								$value['source_name']
																				);
								if($channel_info['stream_state'])
								{
									$out_tvie->stop_stream($value['out_stream_id']);
									$out_tvie->start_stream($value['out_stream_id']);
								}
								else 
								{
									$out_tvie->start_stream($value['out_stream_id']);
									$out_tvie->stop_stream($value['out_stream_id']);
								}
								
								//更新channel_stream
								if($main_stream_name[0] == $value['name'])
								{
									$is_main = 1;
									$sql = "UPDATE " . DB_PREFIX . "channel_stream SET is_main=" . $is_main . " WHERE channel_id=" . $id . " AND stream_name='" . $main_stream_name[0] . "'";
									$this->db->query($sql);
								}
								else
								{
									$is_main = 0;
									$sql = "UPDATE " . DB_PREFIX . "channel_stream SET is_main=" . $is_main . " WHERE channel_id=" . $id. " AND stream_name='" . $value['name'] . "'";
									$this->db->query($sql);
								}
							}
						}
					}
					else 
					{
						//流名称全换的情况下，删除原有的
						foreach ($channel_stream AS $value)
						{
							$delay_ret_stream = $delay_tvie->delete_stream($value['delay_stream_id']);
							$chg_ret_stream = $chg_tvie->delete_stream($value['chg_stream_id']);
							$out_ret_stream = $out_tvie->delete_stream($value['out_stream_id']);
							$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE out_stream_id=" . $value['out_stream_id'];
							$this->db->query($sql);
						}
						
					}
					if($input_channel_stream_diff && $diff_stream_info_other)
					{
						//有差集	  添加流信息(暂时只支持添加信息)
						foreach($diff_stream_info_other AS $value)
						{
							//上游流地址   直接是信号流地址 
							$delay_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $value['name']), 'channels', 'tvie://');
							$chg_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => 'delay_'.$channel_info['code_2'], 'stream_name' => $value['name']));
							$out_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $value['name']), 'channels', 'tvie://');
							if ($this->input['open_ts'])
							{
								$value['backstore'] = array(
									0 => 'flv',
									1 => 'ts'
								);
							}
							if(is_array($value['backstore']))
							{
								$backstore = implode(',', $value['backstore']);
							}
							else 
							{
								$backstore = $value['backstore'];
							}
							$delay_create_stream = $delay_tvie->create_channel_stream(
																					$value['name'],
																					$value['recover_cache'],
																					$value['source_name'],
																					$delay_uri,
																					$value['drm'],
																					'flv',
																					$value['wait_relay'],
																					0,
																					$value['bitrate'],
																					$delay_id
																				);
							$chg_create_stream = $chg_tvie->create_channel_stream(
																					$value['name'],
																					$value['recover_cache'],
																					$value['source_name'],
																					$chg_uri,
																					$value['drm'],
																					'flv',
																					$value['wait_relay'],
																					0,
																					$value['bitrate'],
																					$chg_id
																				);
							$out_create_stream = $out_tvie->create_channel_stream(
																					$value['name'],
																					$value['recover_cache'],
																					$value['source_name'],
																					$out_uri,
																					$drm,
																					$backstore,
																					$value['wait_relay'],
																					0,
																					$value['bitrate'],
																					$out_id
																				);
							if($channel_info['stream_state'])
							{
								$out_tvie->stop_stream($out_create_stream['stream_id']);
								$out_tvie->start_stream($out_create_stream['stream_id']);
							}
							else
							{
								$out_tvie->start_stream($out_create_stream['stream_id']);
								$out_tvie->stop_stream($out_create_stream['stream_id']);
							}
							/*if(!$out_create_stream['stream_id'])
							{
								$this->errorOutput('更新频道失败，原因：'.$out_create_stream['message']);
							}*/
							if($out_create_stream['stream_id'])
							{
								if($main_stream_name[0] == $value['name'])
								{
									$is_main = 1;
								}
								else 
								{
									$is_main = 0;
								}
								//返回创建成功后的流信息
								$channel_stream_info = array(
										'channel_id' => $id,
										'stream_id' => $stream_id,
										'delay_stream_id' => $delay_create_stream['stream_id'],
										'chg_stream_id' => $chg_create_stream['stream_id'],
										'out_stream_id' => $out_create_stream['stream_id'],
										'stream_name' => $value['name'],
										'out_stream_name' => $value['name'],
										'is_main' => $is_main,
										'bitrate' => $value['bitrate'],
										'flag_stream' => '',
										'create_time' => TIMENOW,
										'update_time' => TIMENOW,
										'ip' => hg_getip()
								);
								$cresql = "INSERT INTO " . DB_PREFIX . "channel_stream SET ";
								$space = "";
								foreach($channel_stream_info as $k => $v)
								{
									$cresql .= $space . $k . "=" . "'" . $v . "'";
									$space = ",";
								}
								$this->db->query($cresql);
							}
						}
					}
					else 
					{
						foreach($stream_info AS $value)
						{
							//上游流地址   直接是信号流地址 
							$delay_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $value['name']), 'channels', 'tvie://');
							$chg_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => 'delay_'.$channel_info['code_2'], 'stream_name' => $value['name']));
							$out_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $value['name']), 'channels', 'tvie://');
							if ($this->input['open_ts'])
							{
								$value['backstore'] = array(
									0 => 'flv',
									1 => 'ts'
								);
							}
							if(is_array($value['backstore']))
							{
								$backstore = implode(',', $value['backstore']);
							}
							else 
							{
								$backstore = $value['backstore'];
							}
							$delay_create_stream = $delay_tvie->create_channel_stream(
																					$value['name'],
																					$value['recover_cache'],
																					$value['source_name'],
																					$delay_uri,
																					$value['drm'],
																					'flv',
																					$value['wait_relay'],
																					0,
																					$value['bitrate'],
																					$delay_id
																				);
							$chg_create_stream = $chg_tvie->create_channel_stream(
																					$value['name'],
																					$value['recover_cache'],
																					$value['source_name'],
																					$chg_uri,
																					$value['drm'],
																					'flv',
																					$value['wait_relay'],
																					0,
																					$value['bitrate'],
																					$chg_id
																				);
							$out_create_stream = $out_tvie->create_channel_stream(
																					$value['name'],
																					$value['recover_cache'],
																					$value['source_name'],
																					$out_uri,
																					$drm,
																					$backstore,
																					$value['wait_relay'],
																					0,
																					$value['bitrate'],
																					$out_id
																				);
							if($channel_info['stream_state'])
							{
								$out_tvie->stop_stream($out_create_stream['stream_id']);
								$out_tvie->start_stream($out_create_stream['stream_id']);
							}
							else
							{
								$out_tvie->start_stream($out_create_stream['stream_id']);
								$out_tvie->stop_stream($out_create_stream['stream_id']);
							}
							/*if(!$out_create_stream['stream_id'])
							{
								$this->errorOutput('更新频道失败，原因：'.$out_create_stream['message']);
							}*/
							if($out_create_stream['stream_id'])
							{
								if($main_stream_name[0] == $value['name'])
								{
									$is_main = 1;
								}
								else 
								{
									$is_main = 0;
								}
								//返回创建成功后的流信息
								$channel_stream_info = array(
										'channel_id' => $id,
										'stream_id' => $stream_id,
										'delay_stream_id' => $delay_create_stream['stream_id'],
										'chg_stream_id' => $chg_create_stream['stream_id'],
										'out_stream_id' => $out_create_stream['stream_id'],
										'stream_name' => $value['name'],
										'out_stream_name' => $value['name'],
										'is_main' => $is_main,
										'bitrate' => $value['bitrate'],
										'flag_stream' => '',
										'create_time' => TIMENOW,
										'update_time' => TIMENOW,
										'ip' => hg_getip()
								);
								$cresql = "INSERT INTO " . DB_PREFIX . "channel_stream SET ";
								$space = "";
								foreach($channel_stream_info as $k => $v)
								{
									$cresql .= $space . $k . "=" . "'" . $v . "'";
									$space = ",";
								}
								$this->db->query($cresql);
							}
						}
					}
				}
			}
			
			//层数为   2    ，跟新切播层、输出层
			if($level == 2)
			{	
				if($input_channel_stream_diff)
				{
					//有差集   删除被剔除的流信息
					if(count($diff_stream_info) > 1)
					{
						unset($diff_stream_info[0]);//保留一条数据用作更新
					}
					foreach($diff_stream_info as $value)
					{
						$delay_ret_stream = $delay_tvie->delete_stream($value['delay_stream_id']);
						$chg_ret_stream = $chg_tvie->delete_stream($value['chg_stream_id']);
						$out_ret_stream = $out_tvie->delete_stream($value['out_stream_id']);
						$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE out_stream_id=" . $value['out_stream_id'];
						$this->db->query($sql);
					}
				}
				if($input_channel_stream_diff)
				{
					//流名称改变后组成新数据
					$channel_stream_2 = array();
					foreach($stream_info as $key => $value)
					{
						$channel_stream_2[$key] = array_merge($stream_info[$key],$channel_stream[$key]);
					}
				}
				else
				{
					//other_info与channel_stream组成的数据
					$channel_stream_2 = array();
					foreach($stream_info as $key => $value)
					{
						$channel_stream_2[$key] = array_merge($stream_info[$key],$stream_info_new[$key]);
					}
				}
				//更新切播层
				$chg_ret_channel = $chg_tvie->update_channel(
																$name,
																1,
																$live_delay,
																$chg_id
															);
				//更新切播层流
				foreach($channel_stream_2 as $key => $value)
				{
					//流地址
					$chg_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $value['name']));
					
					if(is_array($value['backstore']))
					{
						$backstore = implode(',', $value['backstore']);
					}
					else 
					{
						$backstore = $value['backstore'];
					}
					
					$chg_update_stream = $chg_tvie->update_channel_stream(
																		$value['chg_stream_id'],
									    								$value['drm'],
									    								$backstore,
									    								$value['recover_cache'],
									    								$chg_uri,
									    								0,
									    								$value['wait_relay'],
									    								$value['source_name']
																	);
					if($channel_info['stream_state'])
					{
						$chg_tvie->stop_stream($value['chg_stream_id']);
						$chg_tvie->start_stream($value['chg_stream_id']);
					}
					else 
					{
						$chg_tvie->start_stream($value['chg_stream_id']);
						$chg_tvie->stop_stream($value['chg_stream_id']);
					}
					
				}
				
				//更新输出层
				$out_ret_channel = $out_tvie->update_channel(
																$name,
																$save_time,
																$live_delay,
																$out_id
															);
				//更新输出层流
				foreach($channel_stream_2 as $key => $value)
				{
					//流地址
					$out_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => 'chg_' .$channel_info['code_2'], 'stream_name' => $channel_stream[0]['out_stream_name']),'channels', 'tvie://');
					if($this->input['open_ts'])
					{
						$value['backstore'] = array(
							0 => 'flv',
							1 => 'ts'
						);
					}
					if(is_array($value['backstore']))
					{
						$backstore = implode(',', $value['backstore']);
					}
					else 
					{
						$backstore = $value['backstore'];
					}
					
					$out_update_stream = $out_tvie->update_channel_stream(
																		$value['out_stream_id'],
									    								$drm,
									    								$backstore,
									    								$value['recover_cache'],
									    								$out_uri,
									    								0,
									    								$value['wait_relay'],
									    								$value['source_name']
																	);
					if($channel_info['stream_state'])
					{
						$out_tvie->stop_stream($value['out_stream_id']);
						$out_tvie->start_stream($value['out_stream_id']);
					}
					else 
					{
						$out_tvie->start_stream($value['out_stream_id']);
						$out_tvie->stop_stream($value['out_stream_id']);
					}												
					
					
					//更新channel_stream
					
					$channel_stream_info = array(
													'stream_id' => $stream_id,
													'stream_name' => $value['name'],
													'is_main' => 1,
													'bitrate' => $value['bitrate'],
													'flag_stream' => '',
													'update_time' => TIMENOW,
												);
					$sql = "UPDATE " . DB_PREFIX . "channel_stream SET ";
					$space = "";
					$sql_extra = "";
					foreach($channel_stream_info as $k => $v)
					{
						if($v)
						{
							$sql_extra .= $space . $k . "=" . "'" . $v . "'";
							$space = ",";
						}
					}
					if($sql_extra)
					{
						$sql .= $sql_extra . " WHERE channel_id=" . $id;
						$this->db->query($sql);
					}
				}	
			}
			//更新延时层、切播层、输出层
			if($level == 3)
			{
				if($input_channel_stream_diff)
				{
					//有差集   删除被剔除的流信息
					if(count($diff_stream_info) > 1)
					{
						unset($diff_stream_info[0]);//保留一条数据用作更新
					}
					foreach($diff_stream_info as $value)
					{
						$delay_ret_stream = $delay_tvie->delete_stream($value['delay_stream_id']);
						$chg_ret_stream = $chg_tvie->delete_stream($value['chg_stream_id']);
						$out_ret_stream = $out_tvie->delete_stream($value['out_stream_id']);
						$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE out_stream_id=" . $value['out_stream_id'];
						$this->db->query($sql);
					}
				}
				if($input_channel_stream_diff)
				{
					//流名称改变后组成新数据
					$channel_stream_2 = array();
					foreach($stream_info as $key => $value)
					{
						$channel_stream_2[$key] = array_merge($stream_info[$key],$channel_stream[$key]);
					}
				}
				else
				{
					//other_info与channel_stream组成的数据
					$channel_stream_2 = array();
					foreach($stream_info as $key => $value)
					{
						$channel_stream_2[$key] = array_merge($stream_info[$key],$stream_info_new[$key]);
					}
				}
			
				//更新延时层
				$delay_ret_channel = $delay_tvie->update_channel(
																$name,
																1,
																$live_delay,
																$delay_id
															);
				//更新延时层流
				foreach($channel_stream_2 as $key => $value)
				{
					//流地址
					$delay_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $value['name']),'channels', 'tvie://');
					
					if(is_array($value['backstore']))
					{
						$backstore = implode(',', $value['backstore']);
					}
					else 
					{
						$backstore = $value['backstore'];
					}
					
					$delay_update_stream = $delay_tvie->update_channel_stream(
																		$value['delay_stream_id'],
									    								$value['drm'],
									    								$backstore,
									    								$value['recover_cache'],
									    								$delay_uri,
									    								0,
									    								$value['wait_relay'],
									    								$value['source_name']
																	);
					if($channel_info['stream_state'])
					{
						$delay_tvie->stop_stream($value['delay_stream_id']);
						$delay_tvie->start_stream($value['delay_stream_id']);
					}
					else 
					{
						$delay_tvie->start_stream($value['delay_stream_id']);
						$delay_tvie->stop_stream($value['delay_stream_id']);
					}
					
				}
				
				//更新切播层
				$chg_ret_channel = $chg_tvie->update_channel(
																$name,
																1,
																$live_delay,
																$chg_id
															);
				//更新切播层流
				foreach($channel_stream_2 as $key => $value)
				{
					//流地址
					$chg_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => 'delay_' .$channel_info['code_2'], 'stream_name' => $channel_stream[0]['out_stream_name']));
					if(is_array($value['backstore']))
					{
						$backstore = implode(',', $value['backstore']);
					}
					else 
					{
						$backstore = $value['backstore'];
					}
					
					$chg_update_stream = $chg_tvie->update_channel_stream(
																		$value['chg_stream_id'],
									    								$value['drm'],
									    								$backstore,
									    								$value['recover_cache'],
									    								$chg_uri,
									    								0,
									    								$value['wait_relay'],
									    								$value['source_name']
																	);
					if($channel_info['stream_state'])
					{
						$chg_tvie->stop_stream($value['chg_stream_id']);
						$chg_tvie->start_stream($value['chg_stream_id']);
					}
					else
					{
						$chg_tvie->start_stream($value['chg_stream_id']);
						$chg_tvie->stop_stream($value['chg_stream_id']);
					}
				}
				
				//更新输出层
				$out_ret_channel = $out_tvie->update_channel(
																$name,
																$save_time,
																$live_delay,
																$out_id
															);
				//更新输出层流
				foreach($channel_stream_2 as $key => $value)
				{
					//流地址
					$out_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => 'chg_' .$channel_info['code_2'], 'stream_name' => $channel_stream[0]['out_stream_name']),'channels', 'tvie://');
					if($this->input['open_ts'])
					{
						$value['backstore'] = array(
							0 => 'flv',
							1 => 'ts'
						);
					}
					if(is_array($value['backstore']))
					{
						$backstore = implode(',', $value['backstore']);
					}
					else 
					{
						$backstore = $value['backstore'];
					}
					
					$out_update_stream = $out_tvie->update_channel_stream(
																		$value['out_stream_id'],
									    								$drm,
									    								$backstore,
									    								$value['recover_cache'],
									    								$out_uri,
									    								0,
									    								$value['wait_relay'],
									    								$value['source_name']
																	);
					if($channel_info['stream_state'])
					{
						$out_tvie->stop_stream($value['out_stream_id']);
						$out_tvie->start_stream($value['out_stream_id']);
					}
					else 
					{
						$out_tvie->start_stream($value['out_stream_id']);
						$out_tvie->stop_stream($value['out_stream_id']);
					}
				
					//更新channel_stream
					$channel_stream_info = array(
													'stream_id' => $stream_id,
													'stream_name' => $value['name'],
													'is_main' => 1,
													'bitrate' => $value['bitrate'],
													'flag_stream' => '',
													'update_time' => TIMENOW,
												);
					$sql = "UPDATE " . DB_PREFIX . "channel_stream SET ";
					$space = "";
					$sql_extra = "";
					foreach($channel_stream_info as $k => $v)
					{
						if($v)
						{
							$sql_extra .= $space . $k . "=" . "'" . $v . "'";
							$space = ",";
						}
					}
					if($sql_extra)
					{
						$sql .= $sql_extra . " WHERE channel_id=" . $id;
						$this->db->query($sql);
					}
				}
			}
		}
	
		//录制节目时间偏差设置
		if($this->input['record_time'] >= 0)
		{
			if($this->input['record_time'] > 30)
			{
				$record_time = 30;
			}
			else
			{
				$record_time = $this->input['record_time'];
			}
		}
		else 
		{
			if($this->input['record_time'] < -30)
			{
				$record_time = -30;
			}
			else
			{
				$record_time = $this->input['record_time'];
			}
		}
		
		$info = array(
			'name' => $name,
			'save_time' => $save_time ? $save_time : '00',
			'live_delay' => $live_delay ? $live_delay : '00',
			'stream_id' => $stream_id,
			'is_live' => $is_live ? $is_live : '00',
			'drm' => $drm ? $drm : '00', 
			'uri_in_num' => $uri_in_num,
			'stream_info_all' => serialize($stream_name),
			'main_stream_name' => $main_stream_name[0],
			'stream_display_name' => $streams['s_name'],
			'stream_mark' => $streams['ch_name'],
			'level' => $level,
			'beibo' => $beibo,
			'open_ts' => $this->input['open_ts'] ? intval($this->input['open_ts']) : '00',
			'update_time' => TIMENOW,
			'record_time' => $record_time ? $record_time : '00',
			'audio_only' => $stream_info[0]['audio_only'] ? $stream_info[0]['audio_only'] : $channel_stream_2['audio_only']
		);
	
		$sql = "UPDATE " . DB_PREFIX . "channel SET ";
		$space = "";
		$sql_extra = "";
		foreach($info as $key => $value)
		{
			if($value)
			{
				$sql_extra .= $space . $key . "=" . "'" . $value . "'";
				$space = ",";
			}
		}
		if($sql_extra)
		{
			$sql .= $sql_extra . " WHERE id=" . $id;
			$this->db->query($sql);
		}
		
		//插入工作量统计
    	$statistic = new statistic();
    	$statistics_data = array(
	    	'content_id' => $id,
			'contentfather_id' => '',
			'type' => 'update',
			'user_id' => $channel_info['user_id'],/**$channel_info['user_id']*/
			'user_name' => $channel_info['user_name'],/**$channel_info['user_name']*/
			'app_uniqueid' => APP_UNIQUEID,
			'module_uniqueid' => MODULE_UNIQUEID,
			'before_data' => '',
			'last_data' => $name,
			'num' => 1,
    	);
    	$statistic->insert_record($statistics_data);
		$ret['id'] = $id;
		
		if ($_FILES['files']['tmp_name'])
		{
			include_once ROOT_PATH . 'lib/class/material.class.php';
			$this->mMaterial = new material();

			$file['Filedata'] = $_FILES['files'];
			
			$material = $this->mMaterial->addMaterial($file, $ret['id'], intval($this->input['mmid']), 'img4');
			
			if (!empty($material))
			{
				$logo_info['id'] = $material['id'];
				$logo_info['type'] = $material['type'];
				$logo_info['server_mark'] = $material['server_mark'];
				$logo_info['filepath'] = $material['filepath'];
				$logo_info['name'] = $material['name'];
				$logo_info['filename'] = $material['filename'];
				$logo_info['url'] = $material['url'];
				
				$sql = "UPDATE " . DB_PREFIX . "channel SET logo_info = '" . serialize($logo_info) . "' WHERE id=" . $id;	
				$this->db->query($sql);
			}
		}
		
		$this->setXmlNode('channel','info');
		$this->addItem($ret['id']);
		$this->output();
	}
	
	/**
	 * 修改台号 (code 只修改输出层和本地)
	 * @name channelCodeEdit
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道id
	 * @param $code_2 string 台号
	 * @return $code_2 string 修改后台号 
	 * @include tvie_api.php
	 */
	public function channelCodeEdit()
	{
		$channel_id = intval($this->input['channel_id']);
		if(!$channel_id)
		{
			$this->errorOutput('未传入频道ID');
		}
		$code_2 = urldecode($this->input['code_2']);
		//频道信息	
		$sql = "SELECT * FROM " . DB_PREFIX . "channel WHERE id=" . $channel_id;
		$channel_info = $this->db->query_first($sql);
		
		$name = $channel_info['name'];
		$code = $channel_info['code'];
		$stream_id = $channel_info['stream_id'];

		$delay_id = $channel_info['delay_id'];
		$chg_id = $channel_info['chg_id'];
		$out_id = $channel_info['ch_id'];
		$level = $channel_info['level'];
		$open_ts = $channel_info['open_ts'];
		$live_delay = $channel_info['live_delay'];
		$save_time = $channel_info['save_time'];
		
		$drm = $channel_info['drm'];
		
		//频道流信息
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN(" . $channel_id . ")";
		$q = $this->db->query($sql);
		$channel_stream = $channel_stream_name = array();
		while($row = $this->db->fetch_array($q))
		{
			$channel_stream[$row['stream_name']] = $row;
			$channel_stream_name[] = $row['stream_name'];
		} 
		
		//信号信息
		$sql = "SELECT * FROM " . DB_PREFIX . "stream WHERE id=" . $stream_id;
		$streams = $this->db->query_first($sql);
		$other_info = unserialize($streams['other_info']);
	
		//channel_stream和stream 组成的数据
		$stream_name_arr = array();
		foreach($other_info as $v)
		{
			$stream_name_arr[$v['name']] = $v;
		}
	 	$stream_info_merge = array();
		foreach ($channel_stream_name AS $n)
		{
			if ($stream_name_arr[$n])
			{
				$stream_info_merge[$n] = $stream_name_arr[$n];
			}
		}
		
		//合并数据
		$stream_info = array();
		foreach ($channel_stream AS $k => $v)
		{
			$stream_info[] =  array_merge($stream_info_merge[$k],$channel_stream[$k]);
		}
	
		//开启tvie
		if($this->settings['tvie']['open'])
		{
			include(CUR_CONF_PATH . 'lib/tvie_api.php');
			$delay_type = 'normal_virtual';
			$out_tvie = new TVie_api($this->settings['tvie']['stream_server']);
			//删除输出层
			if($code_2 && $code_2 != $code)
			{
				$ret_out = $out_tvie->delete_channel($out_id);
				
				$out_live_delay = $live_delay;
				if($level != 1)
				{
					$out_live_delay = 0;
				}
				else
				{
					if (!$save_time)
					{
						$save_time = 1;
					}
				}
				//创建输出层
				if($ret_out['message'] == 'Handled')
				{
					//无切播层，直接信号流地址
					if($level == 1)
					{
						$out_stream_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $stream_info[0]['out_stream_name']), 'channels', 'tvie://');
					}
					else
					{
						$out_stream_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => 'chg_' . $channel_info['code_2'], 'stream_name' => $stream_info[0]['out_stream_name']), 'channels', 'tvie://');
					}
					
					if ($open_ts)
					{
						$stream_info[0]['backstore'] = array(
							0 => 'flv',
							1 => 'ts'
						);
					}
					if (is_array($stream_info[0]['backstore']))
					{
						$backstore = implode(',', $stream_info[0]['backstore']);
					}
					else
					{
						$backstore = $stream_info[0]['backstore'];
					}
					
					$out_channel = $out_tvie->create_channel(
													$code_2,
													$name,
													$streams['server_id'],
													$save_time,
													$out_live_delay,
													$delay_type,
													$stream_info[0]['out_stream_name'],
													$stream_info[0]['recover_cache'],
													$stream_info[0]['source_name'],
													$out_stream_uri,
													$stream_info[0]['bitrate'],
													$drm,
													$stream_info[0]['wait_relay'],
													$backstore
												);
					$out_channel_id = $out_channel['channel_id'];	//返回频道id	
				//	unset($stream_info[0]);
					$ret_out_channel_info = $out_tvie->get_channel_by_id($out_channel_id);
		    		$ret_out_stream_info = $ret_out_channel_info['channel']['streams'];
			    	$first_out_stream_id = $ret_out_stream_info[0]['id'];
			    	$stream_ids = array(
						$ret_out_stream_info[0]['name'] => $first_out_stream_id
					);
			    	if($out_channel_id)
					{
						foreach($stream_info as $value)
						{
							if($level == 1)
							{
								$out_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $value['out_stream_name']), 'channels', 'tvie://');
							}
							else
							{
								$out_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => 'chg_' . $channel_info['code_2'], 'stream_name' => $value['out_stream_name']), 'channels', 'tvie://');
							}
							
							if ($open_ts)
							{
								$value['backstore'] = array(
									0 => 'flv',
									1 => 'ts'
								);
							}
							if(is_array($value['backstore']))
							{
								$backstore = implode(',', $value['backstore']);
							}
							else 
							{
								$backstore = $value['backstore'];
							}
							$out_create_stream = $out_tvie->create_channel_stream(
																					$value['out_stream_name'],
																					$value['recover_cache'],
																					$value['source_name'],
																					$out_uri,
																					$drm,
																					$backstore,
																					$value['wait_relay'],
																					0,
																					$value['bitrate'],
																					$out_channel_id
																				);
							
							if($out_create_stream['stream_id'])
							{
								$stream_ids[$value['out_stream_name']] = $out_create_stream['stream_id'];
							}
						}
						foreach($stream_info as $value)
						{
							//更新channel_stream
							$updateSql = "UPDATE " . DB_PREFIX . "channel_stream SET stream_id=" . $stream_id . ", out_stream_id=" . $stream_ids[$value['out_stream_name']] . ", stream_name='" . $value['name'] . "', update_time=" . TIMENOW;
							$updateSql .= " WHERE channel_id=" . $channel_id ." AND stream_name='" . $value['name'] . "'";
			
							$this->db->query($updateSql);
							if($channel_info['stream_state'])
							{
								$out_tvie->stop_stream($stream_ids[$value['out_stream_name']]);
								$out_tvie->start_stream($stream_ids[$value['out_stream_name']]);
							}
							else 
							{
								$out_tvie->start_stream($stream_ids[$value['out_stream_name']]);
								$out_tvie->stop_stream($stream_ids[$value['out_stream_name']]);
							}
							
						}
						$up_sql = "UPDATE " . DB_PREFIX ."channel SET ch_id=" . $out_channel_id . ", code='" . $code_2 . "' WHERE id=" .$channel_id;
						$this->db->query($up_sql);
					}
					
					if($out_channel_id)
					{
						$this->addItem($code_2);
					}
					else 
					{
						return;
					}
				}
			}
			else 
			{
				$this->addItem($code);
			}
		}
		$this->output();
	}
	
	/**
	 * 删除频道 (频道及其所属频道 节目单 , 节目单计划 , 串联单 , 串联单计划 , 自动收录, 屏蔽节目, 信号流信息)
	 * 删除顺序：延时层-->切播层-->输出层-->本地 	(只有每一步成功后在进行下一步删除)
	 * @name delete
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 频道id
	 * @return $ret['id'] int 删除频道的ID 
	 * @include tvie_api.php
	 */
	function delete()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		$sql = "SELECT user_id,user_name,delay_id,chg_id,ch_id FROM " . DB_PREFIX . "channel WHERE id IN(" . $id . ")";
		$q = $this->db->query($sql);
		$ch_id_all = $delay_id_all = $chg_id_all = array();
		while($row = $this->db->fetch_array($q))
		{
			$ch_id_all[] = $row['ch_id'];
			$delay_id_all[] = $row['delay_id'];
			$chg_id_all[] = $row['chg_id'];
			$ch_user_id[] = $row['user_id'];
			$ch_user_name[] = $row['user_name'];
		}

		if($this->settings['tvie']['open'])
		{	
			include(CUR_CONF_PATH . 'lib/tvie_api.php');
			$tvie_api = new TVie_api($this->settings['tvie']['stream_server']);
			$virtual_api = new TVie_api($this->settings['tvie']['up_stream_server']);
			if($delay_id_all)
			{
				foreach ($delay_id_all as $delay_id)
				{
					//32延时层
					$delay_channel_info = $virtual_api->get_channel_by_id($delay_id);				//查询的频道信息
					$delay_stream_info = $delay_channel_info['channel']['streams'];
					
					if(!$delay_id)
					{
						$this->errorOutput('删除失败请重试');
					}
				/*	foreach($delay_stream_info as $key => $value)
					{
						$ret = $virtual_api->delete_stream($value['id']);			//删除流信息
					}
					*/
					if($delay_id)
					{
						$ret_delay = $virtual_api->delete_channel($delay_id);				//删除频道
					}
				}
			}
			if($chg_id_all && $ret_delay['message'] == 'Handled')
			{
				foreach ($chg_id_all as $chg_id)
				{
					//32切播层
					$chg_channel_info = $virtual_api->get_channel_by_id($chg_id);				//查询的频道信息
					$chg_stream_info = $chg_channel_info['channel']['streams'];
					
					if(!$chg_id)
					{
						$this->errorOutput('删除失败请重试');
					}
					/*foreach($chg_stream_info as $key => $value)
					{
						$ret = $virtual_api->delete_stream($value['id']);			//删除流信息
					}*/
					
					if($chg_id)
					{
						$ret_chg = $virtual_api->delete_channel($chg_id);				//删除频道
					}
				}
			}
			if($ch_id_all && $ret_chg['message'] == 'Handled')
			{
				foreach($ch_id_all as $ch_id)
				{
					//21输出层
					$channel_info = $tvie_api->get_channel_by_id($ch_id);				//查询的频道信息
					$stream_info = $channel_info['channel']['streams'];
					
					if(!$ch_id)
					{
						$this->errorOutput('删除失败请重试');
					}
					/*foreach($stream_info as $key => $value)
					{
						$ret = $tvie_api->delete_stream($value['id']);			//删除流信息
					}*/
					
					if($ch_id)
					{
						$ret_out = $tvie_api->delete_channel($ch_id);				//删除频道
					}
				}
			}
		}
				
		if($ret_out['message'] == 'Handled')
		{
			//删除节目单
			$sql = "DELETE FROM " . DB_PREFIX . "program WHERE channel_id IN (" . $id . ")";
			$this->db->query($sql);
			//删除自动收录
			$sql = "DELETE FROM " . DB_PREFIX . "program_record WHERE channel_id IN (" . $id . ")";
			$this->db->query($sql);
			//删除屏蔽节目
			$sql = "DELETE FROM " . DB_PREFIX . "program_screen WHERE channel_id IN (" . $id . ")";
			$this->db->query($sql);
			//删除频道
			$sql = "DELETE FROM " . DB_PREFIX . "channel WHERE id IN (" . $id . ")";
			$this->db->query($sql);
			
			//插入工作量统计
			$statistic = new statistic();
			$statistics_data = array(
				'content_id' => $id,
				'contentfather_id' => '',
				'type' => 'delete',
				'user_id' => implode(',',$ch_user_id),/**implode(',',$ch_user_id)*/
				'user_name' => implode(',',$ch_user_name),/**implode(',',$ch_user_name)*/
				'app_uniqueid' => APP_UNIQUEID,
				'module_uniqueid' => MODULE_UNIQUEID,
				'before_data' => '',
				'last_data' => '',
				'num' => 1,
			);
			$statistic->insert_record($statistics_data);
    	
			//删除信号流信息
			$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN (" . $id . ")";
			$this->db->query($sql);
			//删除串联单
			$sql = "DELETE FROM " . DB_PREFIX . "channel_chg_plan WHERE channel_id IN (" . $id . ")";
			$this->db->query($sql);
			
			$programPlanSql = "SELECT id FROM " . DB_PREFIX . "program_plan WHERE channel_id IN (" . $id . ")";
			$q = $this->db->query($programPlanSql);
			$program_plan_id = array();
			while($row = $this->db->fetch_array($q))
			{
				$program_plan_id[] = $row['id'];
			}
			//删除节目单周数
			if(is_array($program_plan_id) && $program_plan_id)
			{
				foreach($program_plan_id as $v)
				{
					$sql = "DELETE FROM " . DB_PREFIX . "program_plan_relation WHERE plan_id IN (" . $v . ")";
					$this->db->query($sql);
				}
			}
			
			//删除节目单计划
			$sql = "DELETE FROM " . DB_PREFIX . "program_plan WHERE channel_id IN (" . $id . ")";
			$this->db->query($sql);
			
			$changePlanSql = "SELECT id FROM " . DB_PREFIX . "change_plan WHERE channel_id IN (" . $id . ")";
			$q = $this->db->query($changePlanSql);
			$plan_id = array();
			while($row = $this->db->fetch_array($q))
			{
				$plan_id[] = $row['id'];
			}
			//删除串联单周数
			if(is_array($plan_id) && $plan_id)
			{
				foreach($plan_id as $v)
				{
					$sql = "DELETE FROM " . DB_PREFIX . "change_plan_relation WHERE plan_id IN (" . $v . ")";
					$this->db->query($sql);
				}
			}
			
			//删除串联单计划
			$sql = "DELETE FROM " . DB_PREFIX . "change_plan WHERE channel_id IN (" . $id . ")";
			$this->db->query($sql);
		}
		
		$ret['id'] = $id;
		$this->addItem($ret['id']);
		$this->output();
	}
	
	/**
	 * 信号流状态 (1-启动 0-停止)
	 * @name stream_state
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 频道ID
	 * @return $tip array (status：1-->启动, 2-->停止, 0-->操作失败)
	 * @include tvie_api.php
	 */
	public function stream_state()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		else
		{
			$updsql = "UPDATE " . DB_PREFIX . "channel SET chg2_stream_id=" . 0 . " WHERE id=" . $id;
			$this->db->query($updsql);
		}
		$sql = "SELECT delay_id,chg_id,ch_id,stream_state,level,uri_in_num FROM " . DB_PREFIX . "channel WHERE id=" . $id;
		$ret = $this->db->query_first($sql);
		$delay_id = $ret['delay_id'];
		$chg_id = $ret['chg_id'];
		$out_id = $ret['ch_id'];
		$stream_state = $ret['stream_state'];
		$level = $ret['level'];
		$uri_in_num = $ret['uri_in_num'];
		
		if($this->settings['tvie']['open'])
		{
			include(CUR_CONF_PATH . 'lib/tvie_api.php');
			//延时层
			$delay_tvie = new TVie_api($this->settings['tvie']['up_stream_server']);
			$delay_channel_info = $delay_tvie->get_channel_by_id($delay_id);				
			$delay_stream_info = $delay_channel_info['channel']['streams'];
			//切播层
			$chg_tvie = $delay_tvie;
			$chg_channel_info = $chg_tvie->get_channel_by_id($chg_id);
			$chg_stream_info = $chg_channel_info['channel']['streams'];
			//输出层
			$out_tvie = new TVie_api($this->settings['tvie']['stream_server']);
			$out_channel_info = $out_tvie->get_channel_by_id($out_id);
			$out_stream_info = $out_channel_info['channel']['streams'];
		}
		
		$new_stream_state = "";
		
		if(!$stream_state)
		{
			$sql = "UPDATE " . DB_PREFIX . "channel SET stream_state = 1 WHERE id=" . $id;
			if ($level == 2 && $chg_stream_info)
			{
				foreach($chg_stream_info as $value)
				{
					$ret_chg = $chg_tvie->start_stream($value['id']);
				}
			}
			
			if ($level == 3 && $delay_stream_info && $chg_stream_info)
			{
				foreach($delay_stream_info as $value)
				{
					$ret_delay = $delay_tvie->start_stream($value['id']);
				}
				foreach($chg_stream_info as $value)
				{
					$ret_chg = $chg_tvie->start_stream($value['id']);
				}
			}
			
			if($out_stream_info)
			{
				foreach($out_stream_info as $value)
				{
					$ret_out = $out_tvie->start_stream($value['id']);
				}
			}
			
			if($ret_delay['message'] == 'Handled' || $ret_chg['message'] == 'Handled' || $ret_out['message'] == 'Handled')
			{
				$new_stream_state = 1;
			}
			else
			{
				$new_stream_state = 0;
			}
		}
		else 
		{
			$sql = "UPDATE " . DB_PREFIX . "channel SET stream_state = 0 WHERE id=" . $id;
			
			if ($level == 2 && $chg_stream_info)
			{
				foreach($chg_stream_info as $value)
				{
					$ret_chg = $chg_tvie->stop_stream($value['id']);
				}
			}
			if ($level == 3 && $delay_stream_info && $chg_stream_info)
			{
				foreach($delay_stream_info as $value)
				{
					$ret_delay = $delay_tvie->stop_stream($value['id']);
				}
				foreach($chg_stream_info as $value)
				{
					$ret_chg = $chg_tvie->stop_stream($value['id']);
				}
			}
			if($out_stream_info)
			{
				foreach($out_stream_info as $value)
				{
					$ret_out = $out_tvie->stop_stream($value['id']);
				}
			}
			
			if($ret_delay['message'] == 'Handled' || $ret_chg['message'] == 'Handled' || $ret_out['message'] == 'Handled')
			{
				$new_stream_state = 2;
			}
			else
			{
				$new_stream_state = 0;
			}
		}
		
		if($this->db->query($sql))
		{
			$tip = array('status'=>$new_stream_state);
			$this->addItem($tip);
			$this->output();
		}
		else
		{
			$this->errorOutput();
		}
		
	}

	public function dopublish()
	{
		if(!$this->input['columnid'])
		{
			$this->errorOutput(NOPUBLISHCOL);
		}
	}
	
}
$out = new channelUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'update';
}
$out->$action();
?>