<?php
/***************************************************************************
* $Id: stream.class.php 20703 2013-04-19 09:25:53Z zhoujiafei $
***************************************************************************/
class stream extends InitFrm
{
	private $mLive;
	private $mLivemms;
	private $mServerConfig;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
		
		$this->mLive = $this->settings['wowza']['live_output_server'];
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition, $offset, $count)
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$orderby = " ORDER BY id DESC ";
		
		$sql  = "SELECT * FROM " . DB_PREFIX . "stream ";
		$sql .= " WHERE	1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);

		$stream_info = $server_id = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['uri'] 		= @unserialize($row['uri']);
			$row['other_info'] 	= @unserialize($row['other_info']);
			
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			
			$server_id[] 	= $row['server_id'];
			$stream_info[] 	= $row;
		}
		//服务器配置
		if (!empty($server_id))
		{
			$server_id = implode(',', @array_unique($server_id));
			$server_infos 	= $this->mServerConfig->get_server_config($server_id);
		//	$server_outputs = $this->mServerConfig->get_server_output($server_id);
		}
		
		$return = array();
		if (!empty($stream_info))
		{
			foreach ($stream_info AS $v)
			{
				$server_info = $server_infos[$v['server_id']];
				if ($v['other_info'])
				{
					$v['out_uri'] = $v['out_url'] = $v['stream_name'] = array();
					foreach($v['other_info']['input'] AS $key => $value)
					{
						if ($server_info['core_in_host'])
						{
							$wowzaip = $server_info['core_in_host'];
						}
						else 
						{
							$wowzaip = $this->settings['wowza']['core_input_server']['host'];
						}

						$suffix 	= !$v['type'] ? $this->settings['wowza']['input']['suffix'] : $this->settings['wowza']['list']['suffix'];
						$app_name 	= $this->settings['wowza']['input']['app_name'];
						
						$v['out_uri'][$value['name']] = hg_streamUrl($wowzaip, $app_name, $value['id'] . $suffix);
						$v['out_url'][] = hg_streamUrl($wowzaip, $app_name, $value['id'] . $suffix);
						
						$v['stream_name'][] = $value['name'];
					}
					$v['server_name'] = $server_info['name'];
				}
				$return[] = $v;
			}
		}
		return $return;
	}
	
	function detail($id)
	{
		if(!$id)
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id IN (' . $id .')';
		}			
		$sql = "SELECT * FROM " . DB_PREFIX . "stream " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			$row['uri'] = @unserialize($row['uri']);
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);

			$row['other_info'] 	= @unserialize($row['other_info']);

			return $row;
		}
		return false;
		
	}

	public function create($ch_name, $streams_info, $uri_arr, $type, $stream_count, $server_info)
	{
		if ($server_info['core_in_host'])	//取数据库配置
		{
			//主控
			$core_host_input   = $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			$core_apidir_input = $server_info['input_dir'];
		}
		else 	//取配置文件
		{
			//主控
			$core_host_input   = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$core_apidir_input = $this->settings['wowza']['core_input_server']['input_dir'];
		}

		$ret_select = $this->mLivemms->inputStreamSelect($core_host_input, $core_apidir_input);
		
		
		if (!empty($streams_info))
		{
			$ret_input_id = $other_info = $sourceId = $ret_chg_id = array();

			foreach ($streams_info AS $k => $v)
			{
				if (!empty($v['source_name']))
				{
					$ret_file = $this->mLivemms->inputFileListInsert($core_host_input, $core_apidir_input, implode(',', $v['source_name']));

					$ret_input_id[$k] = $ret_file['list']['id'];
					
					$sourceType = 3;
					
					if (!$ret_file['result'])
					{
						continue;
					}
					
					$other_info['file'][$k] = array(
						'source_id' 	=> $ret_input_id[$k],
						'source_type' 	=> $sourceType,
				//		'chg_stream_id' => $ret_chg_id[$k],
						'name' 			=> $v['name'],
				//		'uri' 			=> $v['uri'],
						'fileid' 		=> $v['source_name'],
						'backup_title' 	=> $v['backup_title'],
					);
				}
				
				if (!$type && empty($v['source_name']))
				{
					$ret_input = $this->mLivemms->inputStreamInsert($core_host_input, $core_apidir_input, $v['uri'], $v['wait_relay']);

					$ret_input_id[$k] = $ret_input['input']['id'];
					
					if (!$ret_input['result'])
					{
						continue;
					}
				}
				
				$other_info['input'][$k] = array(
					'id' 			=> $ret_input_id[$k],
					'name'	 		=> $v['name'],
					'ch_name' 		=> $ch_name,
					'uri' 			=> $v['uri'],
					'recover_cache' =>  '',
					'source_name' 	=> $v['source_name'],
					'backup_title' 	=> $v['backup_title'],
					'drm' 			=>  '',
					'backstore' 	=>  '',
					'wait_relay' 	=> $v['wait_relay'],
					'audio_only' 	=> $v['audio_only'],
					'bitrate' 		=> $v['bitrate']
				);
			}

			//如果流媒体有创建失败 则删除已经创建好的流
			if (!empty($ret_input_id))
			{
				$delete_back = '';

				foreach ($ret_input_id AS $k=>$v)
				{
					if (!$v)
					{	
						$ret_input_back = $this->mLivemms->inputStreamOperate($core_host_input, $core_apidir_input, 'delete', $v);
						$delete_back = 1;
					}
				}

				if ($delete_back)
				{
					return -17;//流媒体服务器没能入库
				}
			}
		}
		
		$uri_arr = !$type ? serialize($uri_arr) : '';
		
		$data = array(
	//		's_name' 		=> trim(urldecode($this->input['s_name'])),
			'ch_name' 		=> $ch_name,
			'uri' 			=> $uri_arr,
			'type' 			=> $type,
			'wait_relay' 	=> $streams_info[0]['wait_relay'],
			'audio_only' 	=> $streams_info[0]['audio_only'],
			'other_info' 	=> serialize($other_info),
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
			'ip' 			=> hg_getip(),
			'stream_count' 	=> $stream_count,
			'server_id' 	=> $server_info['id'],
		);

		$sql = "INSERT INTO " . DB_PREFIX . "stream SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		if ($data['id'])
		{	
			//记录选择备播文件数据
			if (!empty($other_info))
			{
				foreach ($other_info['input'] AS $k=>$v)
				{
					if ($v['source_name'])
					{
						foreach ($v['source_name'] AS $kk=>$vv)
						{
							$otherInfoData = array(
								'stream_id' 	=> $data['id'],
					//			's_name' 		=> trim(urldecode($this->input['s_name'])),
								'ch_name' 		=> $v['ch_name'],
								'input_id' 		=> $v['id'],
								'stream_name' 	=> $v['name'],
								'source_name' 	=> $vv,
							);
							
							$sql = "INSERT INTO " . DB_PREFIX . "stream_other_info SET ";
							$space = "";
							foreach ($otherInfoData AS $key => $value)
							{
								$sql .= $space . $key . "=" . "'" . $value . "'";
								$space = ",";
							}
					
							$this->db->query($sql);
						}
					}
					else 
					{
						continue;
					}
				}
			}
			$this->addLogs('新增信号流信息' , '' , $data , '' , '',$ch_name);
			return $data['id'];
		}
		return false;
	}

	function update($id, $streams_info, $tpl_uri, $tpl_name, $tpl_name_index, $source_name, $type, $stream_count, $server_info, $stream)
	{
		if ($server_info['core_in_host'])	//取数据库配置
		{
			//主控
			$core_host_input   = $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			$core_apidir_input = $server_info['input_dir'];
			
			//时移
			if ($server_info['is_dvr_output'])
			{
				$dvr_host_output 	= $server_info['dvr_in_host'] . ':' . $server_info['dvr_in_port'];
			}
			else 
			{
				$dvr_host_output 	= $core_host_input;
			}
			$dvr_apidir_output    = $server_info['output_dir'];
		}
		else 	//去配置文件
		{
			//主控
			$core_host_input   = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$core_apidir_input = $this->settings['wowza']['core_input_server']['input_dir'];
			
			//时移
			if ($this->settings['wowza']['dvr_output_server'])
			{
				$dvr_host_output = $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['dvr_output_server']['port'];
			}
			else 
			{
				$dvr_host_output = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			}
			
			$dvr_apidir_output = $this->settings['wowza']['core_input_server']['output_dir'];
		}
		
		if ($this->mLive)
		{
			if ($server_info['is_live_output'])
			{
				$live_host_output 	= $server_info['live_in_host'] . ':' . $server_info['live_in_port'];
				$live_apidir_output = $server_info['output_dir'];
			}
			else 
			{
				$live_host_output 	= $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['live_output_server']['port'];
				$live_apidir_output = $this->settings['wowza']['live_output_server']['output_dir'];
			}
		}
		
		$ret_select = $this->mLivemms->inputStreamSelect($core_host_input, $core_apidir_input);
		/**if (!$ret_select)
		{
			return -55;//媒体服务器未启动'
		}**/
		$stream_id = $id;
			
	//	$sql = "SELECT id, s_name, ch_name, uri, s_status, other_info, type FROM " . DB_PREFIX . "stream WHERE id = " . $id;
	//	$stream = $this->db->query_first($sql);

		$stream_other_info = @unserialize($stream['other_info']);
		
		$file_info = $stream_other_info['file'];
		
		$input_info = $stream_other_info['input'];
		
		if (!empty($input_info))
		{
			$input_name = $input_name_index = $input_info_index = array();

			foreach ($input_info AS $k=>$v)
			{
				$input_name[] = $v['name'];
				$input_name_index[$v['name']] = $v['name'];
				$input_info_index[$v['name']] = $v;
			}
		}

		//新增信号
		$add_diff = @array_diff($tpl_name, $input_name);
		//删除信号
		$del_diff = @array_diff($input_name, $tpl_name);
		$del_diff_index = @array_diff($input_name_index, $tpl_name_index);
		//更新信号
		$update_inter = @array_intersect($tpl_name, $input_name);
	
		if (!empty($streams_info))
		{
			$ret_input_id = $other_info = $ret_chg_id = array();
	
			foreach ($streams_info AS $k => $v)
			{
				if ($type == $stream['type'])	//type 不变
				{
					if ($add_diff[$k] == $v['name'])	//insert
					{
						//list
						if (!empty($v['source_name']))
						{
							$ret_file = $this->mLivemms->inputFileListInsert($core_host_input, $core_apidir_input, implode(',', $v['source_name']));
		
							if (!$ret_file['result'])
							{
								continue;
							}
							
							$ret_input_id[$k] = $ret_file['list']['id'];
					
							$sourceType = 3;
			
							$other_info['file'][$k] = array(
								'source_id' 	=> $ret_input_id[$k],
								'source_type' 	=> $sourceType,
							//	'chg_stream_id' => $ret_chg_id[$k],
								'name' 			=> $v['name'],
							//	'uri' 			=> $v['uri'],
								'fileid' 		=> $v['source_name'],
								'backup_title' 	=> $v['backup_title'],
							);
						}
						
						//insert
						if (!$type && empty($v['source_name']))
						{
							$ret_input = $this->mLivemms->inputStreamInsert($core_host_input, $core_apidir_input, $v['uri'],  $v['wait_relay']);
	
							$ret_input_id[$k] = $ret_input['input']['id'];
							if (!$ret_input['result'])
							{
								continue;
							}
						}
	
						$other_info['input'][$k] = array(
							'id' 			=> $ret_input_id[$k],
							'name' 			=> $v['name'],
							'ch_name' 		=> $stream['ch_name'],
							'uri' 			=> $v['uri'],
							'recover_cache' =>  '',
							'source_name' 	=>  $v['source_name'],
							'backup_title' 	=>  $v['backup_title'],
							'drm' 			=>  '',
							'backstore' 	=>  '',
							'wait_relay' 	=>  $v['wait_relay'],
							'audio_only' 	=>  $v['audio_only'],
							'bitrate' 		=>  $v['bitrate']
						);
						
					}
					else	//update
					{
						//list
						if (!empty($v['source_name']))
						{
							$ret_file = $this->mLivemms->inputFileListUpdate($core_host_input, $core_apidir_input, $v['id'], implode(',', $v['source_name']));
		
							if (!$ret_file['result'])
							{
								continue;
							}
							
							$ret_input_id[$k] = $v['id'];
					
							$sourceType = 3;
			
							$other_info['file'][$k] = array(
								'source_id' 	=> $ret_input_id[$k],
								'source_type' 	=> $sourceType,
							//	'chg_stream_id' => $ret_chg_id[$k],
								'name' 			=> $v['name'],
							//	'uri' 			=> $v['uri'],
								'fileid' 		=> $v['source_name'],
								'backup_title' 	=> $v['backup_title'],
							);
						}
						//input
						if (!$type && empty($v['source_name']))
						{
							$ret_input = $this->mLivemms->inputStreamUpdate($core_host_input, $core_apidir_input, $v['id'], $v['uri'], $v['wait_relay']);
							if (!$ret_input['result'])
							{
								continue;
							}
							
							$ret_input_id[$k] = $v['id'];
						}
						
						if ($ret_input_id[$k])
						{
							$other_info['input'][$k] = array(
								'id' 			=> $ret_input_id[$k],
								'name' 			=> $v['name'],
								'ch_name' 		=> $v['ch_name'],
								'uri' 			=> $v['uri'],
								'recover_cache' =>  '',
								'source_name' 	=>  $v['source_name'],
								'backup_title' 	=>  $v['backup_title'],
								'drm' 			=>  '',
								'backstore' 	=>  '',
								'wait_relay' 	=>  $v['wait_relay'],
								'audio_only' 	=>  $v['audio_only'],
								'bitrate' 		=>  $v['bitrate']
							);
						}
						else
						{
							if ($update_inter[$k] == $v['name'])
							{
								$other_info['input'][$k] = array(
									'id' 			=> $input_info_index[$v['name']]['id'],
									'name' 			=> $input_info_index[$v['name']]['name'],
									'ch_name' 		=> $input_info_index[$v['name']]['ch_name'],
									'uri' 			=> $input_info_index[$v['name']]['uri'],
									'recover_cache' => $input_info_index[$v['name']]['recover_cache'],
									'source_name' 	=> $input_info_index[$v['name']]['source_name'],
									'backup_title' 	=> $input_info_index[$v['name']]['backup_title'],
									'drm' 			=> $input_info_index[$v['name']]['drm'],
									'backstore' 	=> $input_info_index[$v['name']]['backstore'],
									'wait_relay' 	=> $input_info_index[$v['name']]['wait_relay'],
									'audio_only' 	=> $input_info_index[$v['name']]['audio_only'],
									'bitrate' 		=> $input_info_index[$v['name']]['bitrate']
								);
							}
						}
						
					}
				}
				else	//type 变化
				{
					if (!$type)	//直播流
					{
						//添加直播流
						$ret_input = $this->mLivemms->inputStreamInsert($core_host_input, $core_apidir_input, $v['uri'],  $v['wait_relay']);

						$ret_input_id[$k] = $ret_input['input']['id'];
						if (!$ret_input['result'])
						{
							continue;
						}
						
						//删除文件流
						if ($v['source_id'])
						{
							$ret_fileListDelete = $this->mLivemms->inputFileListDelete($core_host_input, $core_apidir_input, $v['source_id']);
							
							if ($ret_fileListDelete['result'])
							{
								$sql = "DELETE FROM " . DB_PREFIX . "stream_other_info WHERE input_id = " . $v['source_id'];
								$this->db->query($sql);
							}
						}
					}
					else //文件流
					{
						//添加文件流
						if (!empty($v['source_name']))
						{
							$ret_file = $this->mLivemms->inputFileListInsert($core_host_input, $core_apidir_input, implode(',', $v['source_name']));
		
							if (!$ret_file['result'])
							{
								continue;
							}
							
							$ret_input_id[$k] = $ret_file['list']['id'];
					
							$sourceType = 3;
			
							$other_info['file'][$k] = array(
								'source_id' 	=> $ret_input_id[$k],
								'source_type' 	=> $sourceType,
							//	'chg_stream_id' => $ret_chg_id[$k],
								'name' 			=> $v['name'],
							//	'uri' 			=> $v['uri'],
								'fileid' 		=> $v['source_name'],
								'backup_title' 	=> $v['backup_title'],
							);
						}
						
						//删除直播流
						if ($v['id'])
						{
							$ret_input_delete = $this->mLivemms->inputStreamOperate($core_host_input, $core_apidir_input, 'delete', $v['id']);
							
							if (!$ret_input_delete['result'])
							{
								continue;
							}
						}
					}
					
					$other_info['input'][$k] = array(
						'id' 			=> $ret_input_id[$k],
						'name' 			=> $v['name'],
						'ch_name' 		=> $stream['ch_name'],
						'uri' 			=> $v['uri'],
						'recover_cache' =>  '',
						'source_name' 	=>  $v['source_name'],
						'backup_title' 	=>  $v['backup_title'],
						'drm' 			=>  '',
						'backstore' 	=>  '',
						'wait_relay' 	=>  $v['wait_relay'],
						'audio_only' 	=>  $v['audio_only'],
						'bitrate' 		=>  $v['bitrate']
					);
				}
				
				
				
				//更新备播文件记录
				if (!empty($v['source_name']))
				{
					$sql = "DELETE FROM " . DB_PREFIX . "stream_other_info WHERE input_id = " . $ret_input_id[$k];
					$this->db->query($sql);
					 
					foreach ($v['source_name'] AS $kk=>$vv)
					{
						$otherInfoData = array(
							'stream_id' 	=> $stream_id,
					//		's_name' 		=> trim(urldecode($this->input['s_name'])),
							'ch_name' 		=> $stream['ch_name'],
							'input_id' 		=> $ret_input_id[$k],
							'stream_name' 	=> $v['name'],
							'source_name' 	=> $vv,
						);
						
						$sql = "INSERT INTO " . DB_PREFIX . "stream_other_info SET ";
						$space = "";
						foreach ($otherInfoData AS $key => $value)
						{
							$sql .= $space . $key . "=" . "'" . $value . "'";
							$space = ",";
						}
				
						$this->db->query($sql);
					}
				}
			}
			
			//如有创建过程中有失败的流 则删除以创建好的流
			if (!empty($ret_input_id))
			{
				$delete_back = '';

				foreach ($ret_input_id AS $k=>$v)
				{
					if (!$v)
					{	
						$ret_input_back = $this->mLivemms->inputStreamOperate($core_host_input, $core_apidir_input, 'delete', $v);

						$sql = "DELETE FROM " . DB_PREFIX . "stream_other_info WHERE input_id = " . $v;
						$this->db->query($sql);
						
						$delete_back = 1;
					}
					else
					{
						//重启
						if ($stream['s_status'])
						{
							if (!$type)
							{
								$ret_input = $this->mLivemms->inputStreamOperate($core_host_input, $core_apidir_input, 'start', $v);
							}
							else 
							{
								$ret_input = $this->mLivemms->inputFileListOperate($core_host_input, $core_apidir_input, 'start', $v);
							}
						/*	
							//输入的输出
							if ($ret_chg_id[$k])
							{
								$ret_chg = $this->mLivmms->inputChgStreamOperate('start', $ret_chg_id[$k]);
							}
						*/
						}
						else 
						{
							if (!$type)
							{
								$ret_input = $this->mLivemms->inputStreamOperate($core_host_input, $core_apidir_input, 'stop', $v);
							}
							else 
							{
								$ret_input = $this->mLivemms->inputFileListOperate($core_host_input, $core_apidir_input, 'stop', $v);
							}
						/*
							//输入的输出
							if ($ret_chg_id[$k])
							{
								$ret_chg = $this->mLivmms->inputChgStreamOperate('stop', $ret_chg_id[$k]);
							}
						*/
						}
					}
				}

				if ($delete_back)
				{
					$tpl_uri = $stream['uri'];
					return -17;//流媒体服务器没能入库
				}
			}
	
			//删除输入输出流
			if (!empty($input_info))
			{
				foreach ($input_info AS $k=>$v)
				{
					if ($del_diff[$k] == $v['name'])
					{
						$ret_input = $this->mLivemms->inputStreamOperate($core_host_input, $core_apidir_input, 'delete', $v['id']);
						
						//文件流
						if ($file_info)
						{
							$ret_fileList = $this->mLivemms->inputFileListDelete($core_host_input, $core_apidir_input, $file_info[$k]['source_id']);
							
					//		$ret_chgDelete = $this->mLivmms->inputChgStreamOperate('delete', $file_info[$k]['chg_stream_id']);
						}
					}
				}
			}
			
		}
			
		$tpl_uri = !$type ? serialize($tpl_uri) : '';
		
		$data = array(
		//	's_name' 		=> urldecode($this->input['s_name']),
			'uri' 			=> $tpl_uri,
			's_status' 		=> $stream['s_status'],
			'type' 			=> $type,
			'wait_relay' 	=> $streams_info[0]['wait_relay'],
			'audio_only' 	=> $streams_info[0]['audio_only'],
			'other_info' 	=> serialize($other_info),
			'update_time' 	=> TIMENOW,
			'stream_count'	=> $stream_count,
			'server_id'		=> $server_info['id'],
		);
		
		$sql_ =  "SELECT * FROM " . DB_PREFIX . "stream WHERE id = " . $id;
		$pre_data = $this->db->query_first($sql_);
		
		$sql = "UPDATE " . DB_PREFIX . "stream SET ";
		$space = "";
		foreach($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= " WHERE id=" . $id; 
		$this->db->query($sql);
		
		$data['id'] = $id;
		
		//start
		
		$channelStreamInfo = $this->getChannelStreamInfo($stream_id);

		if (!empty($channelStreamInfo))
		{
			foreach ($channelStreamInfo AS $k=>$channel)
			{
				//channel_stream
				if (!empty($channel['channel_stream']))
				{
					foreach ($channel['channel_stream'] AS $kk=>$vv)
					{
						if ($del_diff_index[$kk] == $kk)
						{
							//延时层
							if ($channel['live_delay'])
							{
								$ret_delay = $this->mLivemms->inputDelayOperate($core_host_input, $core_apidir_input, 'delete', $vv['delay_stream_id']);
							}
							
							//切播层
							$ret_chg = $this->mLivemms->inputChgStreamOperate($core_host_input, $core_apidir_input, 'delete', $vv['chg_stream_id']);
							
							//输出层
							$ret_output = $this->mLivemms->outputStreamOperate($dvr_host_output, $dvr_apidir_output, 'delete', $vv['out_stream_id']);
							
							//live
							if ($this->mLive)
							{
								$_ret_output = $this->mLivemms->outputStreamOperate($live_host_output, $live_apidir_output, 'delete', $vv['out_stream_id']);
							}
							
							if ($ret_output['result'])
							{
								$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE id = " . $vv['id'];
								$this->db->query($sql);
							}
						}
					}
				}
				//channel

				$channel_id[$k] = '';
				if ($channel['stream_info_all_index'])
				{
					foreach ($channel['stream_info_all_index'] AS $kk=>$vv)
					{
						if ($del_diff_index[$kk] == $kk)
						{
							$channel_id[$k] = $channel['id'];
							unset($channel['stream_info_all_index'][$kk]);
						}

						if ($del_diff_index[$kk] == $channel['main_stream_name'])
						{
							$main_stream_name_flag = 1;
						}
					}
					
					if (!empty($channel['stream_info_all_index']))
					{
						$stream_info_all = array();
						foreach ($channel['stream_info_all_index'] AS $v)
						{
							$stream_info_all[] = $v;
						}
					}

					if ($main_stream_name_flag)
					{
						$main_stream_name = '';
					}
					else
					{
						$main_stream_name = $channel['main_stream_name'];
					}
				}

				if ($channel_id[$k])
				{
					$sql = "UPDATE " . DB_PREFIX . "channel SET stream_info_all = '".serialize($stream_info_all)."', main_stream_name = '".$main_stream_name."' WHERE id = " . $channel_id[$k];
					$this->db->query($sql);
				}
			}
		}
		
		$this->addLogs('更新信号流信息' , $pre_data , $data , '' , '',$pre_data['ch_name']);
		//end
		
		if ($data['id'])
		{
			return $data;
		}

		return false;
	}

	public function getChannelStreamInfo($stream_id)
	{
		if (!$stream_id)
		{
			return false;
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "channel WHERE stream_id IN (" . $stream_id . ") ORDER BY id ASC";
		$q = $this->db->query($sql);

		$channel = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['stream_info_all'] = @unserialize($row['stream_info_all']);
			
			$row['stream_info_all_index'] = array();
			if (!empty($row['stream_info_all']))
			{
				foreach ($row['stream_info_all'] AS $v)
				{
					$row['stream_info_all_index'][$v] = $v;
				}
			}

			$row['beibo'] = @unserialize($row['beibo']);

			$channel[$row['id']] = $row;
		}
		
		$channel_ids = @array_keys($channel);

		if (!empty($channel))
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN (" . implode(',', $channel_ids) . ") ORDER BY id ASC";
			$q = $this->db->query($sql);

			$channel_stream = array();
			while ($row = $this->db->fetch_array($q))
			{
				$channel_stream[$row['channel_id']]['channel_stream'][$row['stream_name']] = $row;
			}
			
			$info = array();
			foreach ($channel AS $k=>$v)
			{
				if ($channel_stream[$k])
				{
					$info[$k] = @array_merge($channel[$k], $channel_stream[$k]);
				}
				else
				{
					$info[$k] = $channel[$k];
				}
			}
		}

		if (!empty($info))
		{
			return $info;
		}

		return false;
	}

	public function check_streamChName($ch_name)
	{
		$sql = "SELECT ch_name FROM " . DB_PREFIX . "stream WHERE ch_name = '" . $ch_name . "'";
		$info = $this->db->query_first($sql);
		if (!$info)
		{
			return true;	//验证通过
		}
		return false;		//验证不通过
	}

	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "stream WHERE 1 " . $condition;
		$ret = $this->db->query_first($sql);

		return $ret;
	}

	public function getBitrate($uri, $stream_id)
	{
		require_once ROOT_PATH.'lib/class/curl.class.php';
		$this->curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('stream', $uri);
		$this->curl->addRequestData('stream_id', $stream_id);
		$ret = $this->curl->request('get_bitrate.php');
		
		return $ret;
	}

	public function delete($id)
	{
		$sql = "SELECT id, uri,other_info, type, server_id FROM " . DB_PREFIX . "stream WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);

		$stream_info = $other_infos = $type = $server_id = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['uri'] 			 = @unserialize($row['uri']);
			$row['other_info'] 		 = @unserialize($row['other_info']);
			$other_infos[$row['id']] = $row['other_info'];
			$type[$row['id']]		 = $row['type'];
			$server_id[]			 = $row['server_id'];
			$stream_info[$row['id']] = $row;
		}
		
		//服务器配置
		if (!empty($server_id))
		{
			$server_id 		= implode(',', @array_unique($server_id));
			$server_info	= $this->mServerConfig->get_server_config($server_id);
		}
		
		if (!empty($stream_info))
		{
			foreach ($stream_info AS $key => $value)
			{
				if ($server_info[$value['server_id']]['core_in_host'])
				{
					$core_host_input 	= $server_info[$value['server_id']]['core_in_host'] . ':' . $server_info[$value['server_id']]['core_in_port'];
					$core_apidir_input	= $server_info[$value['server_id']]['input_dir'];
				}
				else 
				{
					$core_host_input 	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
					$core_apidir_input  = $this->settings['wowza']['core_input_server']['input_dir'];
				}

				if ($value['other_info']['file'])
				{
					$result = array();
					foreach ($value['other_info']['file'] AS $vv)
					{
						//删除文件所形成流
						if ($vv['source_id'])
						{
							$ret_FileListDelete = $this->mLivemms->inputFileListDelete($core_host_input, $core_apidir_input, $vv['source_id']);
						}
					}
				}
				
				if ($value['other_info']['input'] && !$type[$key])
				{
					$result = array();
					foreach ($value['other_info']['input'] AS $vv)
					{
						$ret_input = $this->mLivemms->inputStreamOperate($core_host_input, $core_apidir_input, 'delete', $vv['id']);
			
						$result[$vv['id']] = $ret_input['result'];
					}
				}
			}
		}
	
		if (!empty($result))
		{
			$result_flag = '';
			foreach ($result AS $k => $v)
			{
				if (!$v)
				{
					$result_flag = 1;
				}
			}
		}
		
		if (!$result_flag)
		{
			
			$sql_ =  "SELECT * FROM " . DB_PREFIX . "stream WHERE id IN (" . $id . ")";
			$q = $this->db->query($sql_);
			$ret = array();
			while($row = $this->db->fetch_array($q))
			{
				$ret[] = $row;
			}
			
			$sql = "DELETE FROM " . DB_PREFIX . "stream WHERE id IN (" . $id . ")";
			$this->db->query($sql);
			
			$sql = "DELETE FROM " . DB_PREFIX . "stream_other_info WHERE stream_id IN (" . $id . ")";
			$this->db->query($sql);
			
		}
		else 
		{
			return false;
		}
		
		$stream_id = $id;

		$channelStreamInfo = $this->getChannelStreamInfo($stream_id);

		if (!empty($channelStreamInfo))
		{
			foreach ($channelStreamInfo AS $k=>$channel)
			{
				if ($server_info[$channel['server_id']]['core_in_host'])	//取数据库配置
				{
					//主控
					$core_host_input   = $server_info[$channel['server_id']]['core_in_host'] . ':' . $server_info[$channel['server_id']]['core_in_port'];
					$core_apidir_input = $server_info[$channel['server_id']]['input_dir'];
					
					//时移
					if ($server_info[$channel['server_id']]['is_dvr_output'])
					{
						$dvr_host_output 	= $server_info[$channel['server_id']]['dvr_in_host'] . ':' . $server_info[$channel['server_id']]['dvr_in_port'];
					}
					else 
					{
						$dvr_host_output 	= $core_host_input;
					}
					$dvr_apidir_output    = $server_info[$channel['server_id']]['output_dir'];
				}
				else 	//去配置文件
				{
					//主控
					$core_host_input   = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
					$core_apidir_input = $this->settings['wowza']['core_input_server']['input_dir'];
					
					//时移
					if ($this->settings['wowza']['dvr_output_server'])
					{
						$dvr_host_output = $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['dvr_output_server']['port'];
					}
					else 
					{
						$dvr_host_output = $core_host_input;
					}
					
					$dvr_apidir_output = $this->settings['wowza']['core_input_server']['output_dir'];
				}
				
				if ($this->mLive)
				{
					if ($server_info[$channel['server_id']]['is_live_output'])
					{
						$live_host_output 	= $server_info[$channel['server_id']]['live_in_host'] . ':' . $server_info[$channel['server_id']]['live_in_port'];
						$live_apidir_output = $server_info[$channel['server_id']]['output_dir'];
					}
					else 
					{
						$live_host_output 	= $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['live_output_server']['port'];
						$live_apidir_output = $this->settings['wowza']['live_output_server']['output_dir'];
					}
				}
				
				//channel_stream
				if (!empty($channel['channel_stream']))
				{
					$ret_delay_id = $ret_chg_id = $ret_output_id = array();
					foreach ($channel['channel_stream'] AS $kk=>$vv)
					{
						//延时层
						$ret_delay = $this->mLivemms->inputDelayOperate($core_host_input, $core_apidir_input, 'delete', $vv['delay_stream_id']);
						
						//切播层
						$ret_chg = $this->mLivemms->inputChgStreamOperate($core_host_input, $core_apidir_input, 'delete', $vv['chg_stream_id']);
						
						//输出层
						$ret_output = $this->mLivemms->outputStreamOperate($dvr_host_output, $dvr_apidir_output, 'delete', $vv['out_stream_id']);

						//live
						if ($this->mLive)
						{
							$_ret_output = $this->mLivemms->outputStreamOperate($live_host_output, $live_apidir_output, 'delete', $vv['out_stream_id']);
						}
						
						$ret_delay_id[$k][$kk] = $ret_delay['result'];
						$ret_chg_id[$k][$kk] = $ret_chg['result'];
						$ret_output_id[$k][$kk] = $ret_output['result'];
						
						//暂时这样处理
						if ($ret_output['result'])
						{
							$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE id = " . $vv['id'];
							$this->db->query($sql);
						}
					}
				}

				//channel 暂时这样处理
				if ($ret_output['result'])
				{
					$sql = "UPDATE " . DB_PREFIX . "channel SET stream_info_all = '', stream_id='', stream_display_name = '', stream_mark = '', main_stream_name = '', beibo = '' WHERE id IN (" . $channel['id'] . ")";
					$this->db->query($sql);
				}
			}
		}
		
		$this->addLogs('删除信号流信息' , $ret , '', '', '','删除信号流信息'.$id);
		
		if ($ret_output['result'] || !$result_flag)
		{
			return true;
		}

		return false;
	}

	public function check_channel($stream_id)
	{
		$sql = "SELECT name FROM " . DB_PREFIX . "channel WHERE stream_id IN (" . $stream_id . ") ORDER BY id ASC";
		$q = $this->db->query($sql);

		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}

		if (!empty($info))
		{
			return $info;
		}

		return false;
	}

	public function streamStatus($id, $server_info)
	{
		if ($server_info['core_in_host'])	//取数据库配置
		{
			//主控
			$core_host_input   = $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			$core_apidir_input = $server_info['input_dir'];
		}
		else 	//去配置文件
		{
			//主控
			$core_host_input   = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$core_apidir_input = $this->settings['wowza']['core_input_server']['input_dir'];
		}
		
		$sql = "SELECT type, s_status, other_info FROM " . DB_PREFIX . "stream WHERE id = " . $id;
		$stream = $this->db->query_first($sql);

		$s_status = $stream['s_status'];
		
		$other_info = @unserialize($stream['other_info']);
		
		$new_status = 0; //操作失败
		
		if (!$s_status)	//停止
		{
			/*
			if (!empty($other_info['file']))
			{
				$ret_chg_result = array();
				foreach ($other_info['file'] AS $v)
				{
					$ret_chg = $this->mLivmms->inputChgStreamOperate('start', $v['chg_stream_id']);
					$$ret_chg_result[$v['chg_stream_id']] = $ret_chg['result'];
				}
			}
			*/
			//流媒体状态
			if (!empty($other_info['input']))
			{
				$result = array();
				foreach ($other_info['input'] AS $v)
				{
					if (!$stream['type'])
					{
						$ret_input = $this->mLivemms->inputStreamOperate($core_host_input, $core_apidir_input, 'start', $v['id']);
					}
					else 
					{
						$ret_input = $this->mLivemms->inputFileListOperate($core_host_input, $core_apidir_input, 'start', $v['id']);
					}
					
					$result[$v['id']] = $ret_input['result'];
				}
			}
			
			if (!empty($result))
			{
				$result_flag = '';
				foreach ($result AS $k => $v)
				{
					if (!$v)
					{
						if (!$stream['type'])
						{
							$ret_input = $this->mLivemms->inputStreamOperate($core_host_input, $core_apidir_input, 'stop', $k);
						}
						else 
						{
							$ret_input = $this->mLivemms->inputFileListOperate($core_host_input, $core_apidir_input, 'stop', $k);
						}
						$result_flag = 1;
					}
				}
			}
			
			if (!$result_flag)
			{
				$sql = "UPDATE " . DB_PREFIX . "stream SET s_status = 1 WHERE id = " . $id;
				$this->db->query($sql);
	
				$new_status = 1;
			}
		}
		else			//启动
		{
			//流媒体状态
			if (!empty($other_info['input']))
			{
				$result = array();
				foreach ($other_info['input'] AS $v)
				{
					if (!$stream['type'])
					{
						$ret_input = $this->mLivemms->inputStreamOperate($core_host_input, $core_apidir_input, 'stop', $v['id']);
					}
					else 
					{
						$ret_input = $this->mLivemms->inputFileListOperate($core_host_input, $core_apidir_input, 'stop', $v['id']);
					}
					$result[$v['id']] = $ret_input['result'];
				}
			}
			
			if (!empty($result))
			{
				$result_flag = '';
				foreach ($result AS $k => $v)
				{
					if (!$v)
					{
						if (!$stream['type'])
						{
							$ret_input = $this->mLivemms->inputStreamOperate($core_host_input, $core_apidir_input, 'start', $k);
						}
						else 
						{
							$ret_input = $this->mLivemms->inputFileListOperate($core_host_input, $core_apidir_input, 'start', $k);
						}
						$result_flag = 1;
					}
				}
			}
			
			if (!$result_flag)
			{
				$sql = "UPDATE " . DB_PREFIX . "stream SET s_status = 0 WHERE id = " . $id;
				$this->db->query($sql);
	
				$new_status = 2;
			}
		}

		return $new_status;
	}
	
	public function get_stream_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "stream WHERE id = " . $id;
		$ret = $this->db->query_first($sql);
		return $ret;
	}

	public function getStreamInfo($condition, $offset, $count)
	{
		$limit 		= " LIMIT " . $offset . " , " . $count;
		$orderby	= " ORDER BY id DESC ";
		
		$sql = "SELECT id, s_name, ch_name, s_status, audio_only, other_info, type, server_id FROM " . DB_PREFIX . "stream ";		
		$sql .= ' WHERE	1 ' . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		
		$stream_info = $server_id = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['other_info'] = @unserialize($row['other_info']);
			
			$server_id[] 	= $row['server_id'];
			$stream_info[] 	= $row;
		}
		//服务器配置
		if (!empty($server_id))
		{
			$server_id = implode(',', @array_unique($server_id));
			$server_infos = $this->mServerConfig->get_server_config($server_id);
		}

		$return = array();
		if (!empty($stream_info))
		{
			foreach ($stream_info AS $v)
			{
				$server_info = $server_infos[$v['server_id']];
				if ($v['other_info'])
				{
					$v['out_url'] = array();
					foreach($v['other_info']['input'] AS $vv)
					{
						if ($server_info['core_in_host'])
						{
							$wowzaip = $server_info['core_in_host'];
						}
						else 
						{
							$wowzaip = $this->settings['wowza']['core_input_server']['host'];
						}
						
						$suffix  	= !$v['type'] ? $this->settings['wowza']['input']['suffix'] : $this->settings['wowza']['list']['suffix'];
						$app_name 	= $this->settings['wowza']['input']['app_name'];
						
						$v['out_url'][] = hg_streamUrl($wowzaip, $app_name, $vv['id'] . $suffix);
					}
				}
				unset($v['other_info']);
				$return[$v['id']] = $v;
			}
		}
		return $return;
	}
	
	function getIsPlay()
	{
		$orderby = " ORDER BY id DESC ";
		
		$sql = "SELECT id, audio_only, other_info, server_id FROM " . DB_PREFIX . "stream ";
		$sql.= " WHERE type=0 AND s_status=1" . $condition . $orderby;
		
		$q = $this->db->query($sql);
		
		$stream_info = $server_info = $server_id = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['other_info'] = @unserialize($row['other_info']);
			
			$server_id[] 		= $row['server_id'];
			$stream_info[$row['id']] = $row;
		}
		
		$server_ids = @array_unique($server_id);

		//服务器配置
		if (!empty($server_id))
		{
			$server_id = implode(',', $server_ids);

			$server_infos 	= $this->mServerConfig->get_server_config($server_id);
	//		$server_outputs = $this->mServerConfig->get_server_output($server_id);
		}

		$ret_input = array();
		if (!empty($server_infos))
		{
			foreach ($server_infos AS $server_info)
			{
				if ($server_info['core_in_host'])
				{
					$core_host_input	= $server_info['core_in_host'] . ':' . $server_info['core_in_port']; 
					$core_apidir_input 	= $server_info['input_dir'];
				}
				
				$ret = $this->mLivemms->inputStreamSelect($core_host_input, $core_apidir_input);
				
				$get_is_play = array();
				if (!empty($ret['inputs']['input']))
				{
					foreach ($ret['inputs']['input'] AS $vv)
					{
						$get_is_play[$vv['id']] = $vv;
					}
					$ret_input[$server_info['id']] = $get_is_play;
				}
				
			}
		}
		else if (!empty($server_ids))
		{
			foreach ($server_ids AS $v)
			{
				if ($v != 0)
				{
					$core_host_input 	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
					$core_apidir_input 	= $this->settings['wowza']['core_input_server']['input_dir'];
		
					$ret = $this->mLivemms->inputStreamSelect($core_host_input, $core_apidir_input);
		
					$get_is_play = array();
					if (!empty($ret['inputs']['input']))
					{
						foreach ($ret['inputs']['input'] AS $vv)
						{
							$get_is_play[$vv['id']] = $vv;
						}
						$ret_input[$v] = $get_is_play;
					}
				}
			}
		}
		
		if (in_array('0',$server_ids))
		{
			$core_host_input 	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$core_apidir_input 	= $this->settings['wowza']['core_input_server']['input_dir'];

			$ret = $this->mLivemms->inputStreamSelect($core_host_input, $core_apidir_input);

			$get_is_play = array();
			if (!empty($ret['inputs']['input']))
			{
				foreach ($ret['inputs']['input'] AS $vv)
				{
					$get_is_play[$vv['id']] = $vv;
				}
				$ret_input[0] = $get_is_play;
			}
		}

		$return = array();
		if (!empty($stream_info) && !empty($ret_input))
		{
			foreach ($stream_info AS $key => $value)
			{
				if ($value['other_info']['input'])
				{
					$ret_isPlay = array();
					foreach ($value['other_info']['input'] AS $k=>$v)
					{
						$ret_isPlay[$v['id']]['id'] 		  = $ret_input[$value['server_id']][$v['id']]['id'];
						$ret_isPlay[$v['id']]['isVideoReady'] = $ret_input[$value['server_id']][$v['id']]['isVideoReady'];
						$ret_isPlay[$v['id']]['isAudioReady'] = $ret_input[$value['server_id']][$v['id']]['isAudioReady'];
						$ret_isPlay[$v['id']]['enable'] 	  = $ret_input[$value['server_id']][$v['id']]['enable'];
						$ret_isPlay[$v['id']]['name'] 		  = $v['name'];
						$ret_isPlay[$v['id']]['audio_only']   = $v['audio_only'];
					}
				}
				$return[$value['id']] = $ret_isPlay;
			}
		}
		return $return;
	}
	
	public function checked_backup_by_server_id($backup_id, $server_id, $filed = ' * ')
	{
		$sql  = "SELECT {$filed} FROM " . DB_PREFIX . "backup ";
		$sql .= " WHERE server_id = " . $server_id . " AND fileid IN (" . $backup_id . ")";
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		return $return;
	}
}
?>