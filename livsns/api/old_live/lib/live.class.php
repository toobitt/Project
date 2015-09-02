<?php
/***************************************************************************
* $Id: live.class.php 19886 2013-04-08 02:01:25Z lijiaying $
***************************************************************************/
class live extends InitFrm
{
	private $mLivemms;
	private $mServerConfig;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();

		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 切播
	 * Enter description here ...
	 * @param unknown_type $channel_id
	 * @param unknown_type $stream_id
	 * @param unknown_type $chg_type
	 */
	public function emergency_change($channel_id, $stream_id, $chg_type, $live_back, $user)
	{	
		$sql = "SELECT * FROM " . DB_PREFIX . "channel WHERE id = " . $channel_id;
		$channel_info = $this->db->query_first($sql);
		
		if (empty($channel_info))
		{
			return -13; //该频道不存在或者已被删除
		}
		
		if (!$channel_info['stream_state'])
		{
			return -14; //频道输出流未启动
		}
		
		$server_id = $channel_info['server_id'];
		if ($server_id)
		{
			$server_info 	= $this->mServerConfig->get_server_config_by_id($server_id);
			$server_output 	= $this->mServerConfig->get_server_output_by_id($server_id);
		}
		
		if ($server_info['core_in_host'])
		{
			$host			= $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			$apidir_input 	= $server_info['input_dir'];
			$apidir_output 	= $server_info['output_dir'];
			$wowzaip		= $server_info['core_in_host'];
		}
		else 
		{
			$host			= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$apidir_input 	= $this->settings['wowza']['core_input_server']['input_dir'];
			$apidir_output 	= $this->settings['wowza']['core_input_server']['output_dir'];
			$wowzaip		= $this->settings['wowza']['core_input_server']['host'];
		}
		
		$ret_select = $this->mLivemms->outputApplicationSelect($host, $apidir_output);
		
		if (!$ret_select)
		{
			return -55;	//切播服务器未开启
		}
		
		//返回上一次切播的信息
		$chg2_stream_id = $channel_info['chg2_stream_id'] ? $channel_info['chg2_stream_id'] : $channel_info['stream_id'];
	
		if ($channel_info['chg_type'] == 'stream')
		{
			$sql = "SELECT s_name, ch_name, id, other_info, type FROM " . DB_PREFIX . "stream WHERE id = " . $chg2_stream_id;
			$prev_stream = $this->db->query_first($sql);
			
			if ($prev_stream['other_info'])
			{
				$prev_other_info = @unserialize($prev_stream['other_info']);
		//		$prev['name'] = $prev_stream['s_name'];
				$prev['name'] = $prev_stream['ch_name'];
				$prev['stream_id'] = $prev_stream['id'];
				
				$suffix_input = !$prev_stream['type'] ? $this->settings['wowza']['input']['suffix'] : $this->settings['wowza']['list']['suffix'];
				
				$prev['stream_url'] = hg_streamUrl($wowzaip, $this->settings['wowza']['input']['app_name'], $prev_other_info['input'][0]['id'] . $suffix_input);
			}
		}
		else if ($channel_info['chg_type'] == 'file')
		{
			$sql = "SELECT id, title, fileid FROM " . DB_PREFIX . "backup WHERE id = " . $chg2_stream_id;
			$prev_backup_info = $this->db->query_first($sql);
			
			if ($prev_backup_info['fileid'])
			{
				$prev['name'] 		= $prev_backup_info['title'];
				$prev['stream_id'] 	= $prev_backup_info['id'];
				$prev['stream_url'] = hg_streamUrl($wowzaip, $this->settings['wowza']['backup']['app_name'], $this->settings['wowza']['backup']['prefix'] . $this->settings['wowza']['backup']['midfix'] . $prev_backup_info['fileid'] . $this->settings['wowza']['backup']['suffix']);
			}
		}
		$prev['chg_type'] = $channel_info['chg_type'];
		
		//再次切播同一备播信号或者备播文件 返回信息
		if ($channel_info['chg2_stream_id'] == $stream_id)
		{
			$ret = array(
				'prev' => $prev,
			);
			return $ret;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_stream WHERE channel_id = " . $channel_id . " ORDER BY id ASC";
		$q = $this->db->query($sql);
		
		$channel_stream_info = $channel_stream_info_index = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel_stream_info[] = $row;
			$channel_stream_info_index[$row['stream_name']] = $row;
		}
		
		if (empty($channel_stream_info_index))
		{
			return -15; //频道信号流不存在
		}
		
		if ($chg_type == 'stream')
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "stream WHERE id = " . $stream_id;
			$stream_info = $this->db->query_first($sql);
			
			if (empty($stream_info))
			{
				return -16; //该信号不存在或已被删除
			}
			
		//	$stream_name = $stream_info['s_name'];
			$stream_name = $stream_info['ch_name'];
			
			if (!$stream_info['s_status'])
			{
				return -17; //该信号流未启动
			}
			
			$other_info = unserialize($stream_info['other_info']);
			
			if (!empty($other_info))
			{
				$input_info = $other_info['input'];
				$input_info_index = array();
				foreach ($other_info['input'] AS $k => $v)
				{
					$input_info_index[$v['name']] = $v;
				}
			}
		}
		
		//修正串联单
		$notify = 0;
		if ($live_back == 'live_back')
		{
			$notify = 1;
		}
		
		if ($channel_info['stream_id'] == $stream_id && $chg_type == 'stream')		//返回直播
		{
			if (!empty($channel_stream_info_index))
			{
				foreach ($channel_stream_info_index AS $k => $v)
				{
					if (!$channel_info['live_delay'])
					{
						$sourceId = $input_info_index[$k]['id'];
						$sourceType = !$stream_info['type'] ? 1 : 3;
					}
					else 
					{
						$sourceId = $v['delay_stream_id'];
						$sourceType = 2;
					}
					
					$chgId = $v['chg_stream_id'];
					if (!$chgId)
					{
						$msg = array(	
							0 => '未能获取切播层ID',	
						);
						return $msg;
					}
					
					$ret_chg = $this->mLivemms->inputChgStreamChange($host, $apidir_input, $chgId, $sourceId, $sourceType, $notify);
					
					if (!$ret_chg['result'])
					{
						$msg = array(
							-1 => '无法连接切播服务',	
							0 => '切播失败',	
						);
						return $ret_chg['result'];
					}
					
					if ($channel_info['list_fileid'] && $channel_info['chg_type'] == 'file')
					{
						$ret_fileListDelete = $this->mLivemms->inputFileListDelete($host, $apidir_input, $channel_info['list_fileid']);
					}
				}
				
				$up_data = array(
						'id'				=> $channel_id,
						'chg2_stream_id'	=> 0,
						'chg2_stream_name'	=> 0,
						'chg_type'			=> 'stream',
						'list_fileid'		=> 0,
				);
				$sql_ =  "SELECT id,chg2_stream_id,chg2_stream_name,chg_type,list_fileid  FROM " . DB_PREFIX . "channel WHERE id = " . $channel_id;
				$pre_data = $this->db->query_first($sql_);
				
				$sql = "UPDATE " . DB_PREFIX . "channel SET chg2_stream_id=0, chg2_stream_name=0, chg_type='stream', list_fileid=0 WHERE id=" . $channel_id;
				$this->db->query($sql);
				
				$chg_type = 'stream';
				
				$ret = array(
					'msg' => $ret_chg,
					'stream_id' => $stream_id,
					'stream_name' => $stream_name,
					'chg_type' => $chg_type,
					'prev' => $prev,
				);
				
				$ret['live_back'] = $notify ? 1 : 0;
				
				//记录日志
				$this->channelChgLog($channel_id, $channel_info['name'], 'live', $stream_id, $stream_name, $chg_type, $user);
				
				return $ret;
			}
			return false;
			
		}
		else	//切播
		{
			if ($chg_type == 'file')	//备播文件
			{
				$sql = "SELECT title, videofilename, fileid FROM " . DB_PREFIX . "backup WHERE id = " . $stream_id;
				$backup_info = $this->db->query_first($sql);
				$stream_name = $backup_info['title'];

				if (!$backup_info['fileid'])
				{
					return -18; //备播文件不存在或已损坏
				}
				//形成一条文件流
		/*		$ret_list = $this->mLivmms->inputFileListInsert($backup_info['fileid']);

				if (!$ret_list['result'])
				{
					return -19; //文件流形成失败
				}
				
				$list_fileid = $ret_list['list']['id'];
				
				if (!$list_fileid)
				{
					return -20;
				}
			
				$sourceId = $list_fileid;
		*/		
				$sourceId = $backup_info['fileid'];
				
				$sourceType = 4;
			}
			else 	//信号流
			{
				$sourceId = array();
				
				foreach ($channel_stream_info AS $k => $v)
				{
					if ($input_info[$k]['id'])
					{
						$input_id = $input_info[$k]['id'];
					}
					else 
					{
						$input_id = $input_info[0]['id'];
					}
					
					$sourceId[] = $input_id;
				}
				
				$sourceType = !$stream_info['type'] ? 1 : 3;
			}
			
			foreach ($channel_stream_info AS $k => $v)
			{
				$chgId = $v['chg_stream_id'];
				
				if ($chg_type == 'file')
				{
					$ret_chg = $this->mLivemms->inputChgStreamChange($host, $apidir_input, $chgId, $sourceId, $sourceType, $notify);
				}
				else 
				{
					$ret_chg = $this->mLivemms->inputChgStreamChange($host, $apidir_input, $chgId, $sourceId[$k], $sourceType, $notify);
				}
		
				if (!$ret_chg['result'])
				{
					$msg = array(
						-1 => '无法连接切播服务',	
						0 => '切播失败',	
					);
					return $ret_chg['result'];
				}
			}
			
			$sql_ =  "SELECT id,chg2_stream_id,chg2_stream_name  FROM " . DB_PREFIX . "channel WHERE id = " . $channel_id;
			$pre_data = $this->db->query_first($sql_);
					
			$up_data = array();
			$up_data = array(
						'id'				=> $channel_id,
						'chg2_stream_id'	=> $stream_id,
						'chg2_stream_name'	=> $stream_name,
						'chg_type'			=> $chg_type,
				);
				
			if ($ret_chg['result'])
			{
		//		$sql = "UPDATE " . DB_PREFIX . "channel SET chg2_stream_id=" . $stream_id . ", chg2_stream_name='" . $stream_name . "', chg_type='" . $chg_type . "', list_fileid = " . intval($list_fileid) . " WHERE id=" . $channel_id;
				$sql = "UPDATE " . DB_PREFIX . "channel SET chg2_stream_id=" . $stream_id . ", chg2_stream_name='" . $stream_name . "', chg_type='" . $chg_type . "' WHERE id=" . $channel_id;
				$this->db->query($sql);
	
				$ret = array(
					'msg' => $ret_chg,
					'stream_id' => $stream_id,
					'stream_name' => $stream_name,
					'chg_type' => $chg_type,
					'prev' => $prev,
				);
				
				$ret['live_back'] = $notify ? 1 : 0;
				
				//记录日志
				$this->channelChgLog($channel_id, $channel_info['name'], 'chg', $stream_id, $stream_name, $chg_type, $user);		
				
				$this->addLogs('切播' , $pre_data , $up_data , '' , '',$channel_info['name']);
				return $ret;
			}
			return false;
		}
	}
	
	/**
	 * 播控状态
	 * Enter description here ...
	 * @param unknown_type $channel_id
	 */
	public function nowStatus($channel_id)
	{
		$sql = "SELECT stream_mark, stream_id,live_delay,stream_state, chg2_stream_id, chg_type FROM " . DB_PREFIX . "channel WHERE id=" . $channel_id;
		$channel_info = $this->db->query_first($sql);
		
		if (!$channel_info)
		{
			return;
		}
		
		if (!$channel_info['stream_state'])
		{
			$ret = array(
				'status' => $channel_info['stream_state']
			);
			return $ret;
		}
		
		if (!$channel_info['chg2_stream_id'])
		{
			$ret = array(
				'stream_id' => $channel_info['stream_id'],
				'stream_name' => $channel_info['stream_mark'],
				'chg_type' => $channel_info['chg_type'],
				'status' => $channel_info['stream_state']
			);
			return $ret;
		}
		
		if ($channel_info['chg2_stream_id'] && $channel_info['chg_type'] == 'stream')
		{
			$sql = "SELECT ch_name FROM " . DB_PREFIX . "stream WHERE id = " . $channel_info['chg2_stream_id'];
			$stream_info = $this->db->query_first($sql);
			
			if (!$stream_info)
			{
				return ;
			}
			
			$name = $stream_info['ch_name'];
		}
		else 
		{
			$sql = "SELECT title FROM " . DB_PREFIX . "backup WHERE id = " . $channel_info['chg2_stream_id'];
			$backup_info = $this->db->query_first($sql);
			
			if (!$backup_info)
			{
				return ;
			}
			
			$name = $backup_info['title'];
		}
		$ret = array(
			'stream_id' => $channel_info['chg2_stream_id'],
			'stream_name' => $name,
			'chg_type' => $channel_info['chg_type'],
			'status' => $channel_info['stream_state']
		);
		return $ret;
	}
	
	/**
	 * 切播日志
	 * Enter description here ...
	 * @param unknown_type $channel_id
	 * @param unknown_type $channel_name
	 * @param unknown_type $type
	 * @param unknown_type $chg2_stream_id
	 * @param unknown_type $chg2_stream_name
	 * @param unknown_type $chg_type
	 * @param unknown_type $list_fileid
	 */
	public function channelChgLog($channel_id, $channel_name, $type, $chg2_stream_id, $chg2_stream_name, $chg_type, $user, $list_fileid = '')
	{
		$data = array(
			'channel_id' 		=> $channel_id,
			'channel_name' 		=> $channel_name,
			'type' 				=> $type,
			'chg2_stream_id' 	=> $chg2_stream_id,
			'chg2_stream_name' 	=> $chg2_stream_name,
			'chg_type' 			=> $chg_type,
			'list_fileid' 		=> $list_fileid,
			'user_id' 			=> $user['user_id'],
			'user_name' 		=> $user['user_name'],
			'create_time' 		=> TIMENOW,
			'ip' 				=> hg_getip(),
		);
		
		$sql = "INSERT INTO " . DB_PREFIX . "channel_chg_log SET ";
		$space = "";
		foreach ($data AS $key => $val)
		{
			$sql .= $space . $key . "=" . "'" . $val . "'";
			$space = ",";
		}
		
		$this->db->query($sql);
	}
}
?>