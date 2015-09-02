<?php
/***************************************************************************
* $Id: backup.class.php 20757 2013-04-20 06:18:23Z gaoyuan $
***************************************************************************/
class backup extends InitFrm
{
	private $mLivemms;
	private $mLive;
	private $mVod;
	private $mServerConfig;
	public function __construct()
	{
		parent::__construct();
		
		require_once ROOT_PATH.'lib/class/curl.class.php';
		
		$this->mVod = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);

		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show($condition, $offset, $count, $width = '40', $height = '30')
	{
		$limit	 = " LIMIT " . $offset . " , " . $count;
		$orderby = " ORDER BY id DESC ";
		
		$sql = "SELECT * FROM " . DB_PREFIX . "backup ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		
		$backup_info = $server_id = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s',$row['create_time']);
			$row['toff_s'] 	= $row['toff']/1000;
			$row['toff'] 	= time_format($row['toff']);
			
			$row['img'] = @unserialize($row['img']);
			if ($row['img'])
			{
				$imgsize = $width . 'x' . $height . '/';
				$row['img'] = hg_material_link($row['img']['host'], $row['img']['dir'], $row['img']['filepath'], $row['img']['filename'], $imgsize);
			}
			
			$server_id[] = $row['server_id'];
			$backup_info[] = $row;
		}
		//服务器配置
		if (!empty($server_id))
		{
			$server_id	   	= implode(',', @array_unique($server_id));
			$server_infos   = $this->mServerConfig->get_server_config($server_id);
		//	$server_outputs = $this->mServerConfig->get_server_output($server_id);
		}
		$return = array();
		if (!empty($backup_info))
		{
			foreach ($backup_info AS $v)
			{
				if($v['fileid'])
				{
					$server_info   = $server_infos[$v['server_id']];
					if ($server_info['core_in_host'])
					{
						$wowzaip  = $server_info['core_in_host'];
					}
					else 
					{
						$wowzaip  = $this->settings['wowza']['core_input_server']['host'];
					}
					
					$app_name = $this->settings['wowza']['backup']['app_name'];
					$prefix   = $this->settings['wowza']['backup']['prefix'];
					$midfix   = $this->settings['wowza']['backup']['midfix'];
					$suffix   = $this->settings['wowza']['backup']['suffix'];
					
					$v['file_uri'] = hg_streamUrl($wowzaip, $app_name, $prefix . $midfix . $v['fileid'] . $suffix);
					$v['beibo_file_url'] = $v['file_uri'];		
				}
				$v['server_name'] = $server_info['name'];
				$return[] = $v;
			}
		}
		return $return;
	}

	public function detail($id, $width = '40', $height = '30')
	{
		if(!$id)
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id IN (' . $id .')';
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "backup " . $condition;
		$row = $this->db->query_first($sql);
		
		if ($row && is_array($row))
		{
			$row['img'] = @unserialize($row['img']);
			if ($row['img'])
			{
				$imgsize = $width . 'x' . $height . '/';
				$row['img'] = hg_material_link($row['img']['host'], $row['img']['dir'], $row['img']['filepath'], $row['img']['filename'], $imgsize);
			}
			$row['toff'] = time_format($row['toff']);
		
			if($row['fileid'])
			{
				$server_info   = $this->mServerConfig->get_server_config_by_id($row['server_id']);
				if ($server_info['core_in_host'])
				{
					$wowzaip  = $server_info['core_in_host'];
				}
				else 
				{
					$wowzaip  = $this->settings['wowza']['core_input_server']['host'];
				}
				
				$app_name = $this->settings['wowza']['backup']['app_name'];
				$prefix   = $this->settings['wowza']['backup']['prefix'];
				$midfix   = $this->settings['wowza']['backup']['midfix'];
				$suffix   = $this->settings['wowza']['backup']['suffix'];
				
				$row['file_uri'] = hg_streamUrl($wowzaip, $app_name, $prefix . $midfix . $row['fileid'] . $suffix);
				$row['beibo_file_url'] = $row['file_uri'];		
			}
			return $row;
		}
		return FALSE;
	}
	
	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "backup WHERE 1 " . $condition;
		$ret = $this->db->query_first($sql);

		return $ret;
	}
	
	public function create($add_input, $user)
	{
		$video_info = $add_input['video_info'];
		$file_info 	= $add_input['file_info'];
		if (!empty($video_info))
		{
			$img = '';
			if (is_array($video_info['img_info']))
			{
				$img = @serialize($video_info['img_info']);
			}
	
			$filename 	= $video_info['title'];
			$toff 		= $video_info['duration'];
			$newname 	= $video_info['video_filename'];
			$url 		= $video_info['vod_url'];
		}
		else if (!empty($file_info))
		{
			$img = '';
	
			$filename 	= $file_info['name'];
			$toff 		= $this->settings['backup_file_toff'] . '000';
			$newname 	= $add_input['title'];
			$url 		= $this->settings['App_live']['protocol'] . $this->settings['App_live']['host'] . '/' . $this->settings['App_live']['dir'] . BACKUP_PATH . $file_info['name'];
		}

		//服务器配置
		$server_id  = $add_input['server_id'];
		if ($server_id)
		{
			$server_info = $this->mServerConfig->get_server_config_by_id($server_id);
		}
		
		if ($server_info['core_in_host'])
		{
			$host	= $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			$apidir	= $server_info['input_dir'];
		}
		else 
		{
			$host	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$apidir	= $this->settings['wowza']['core_input_server']['input_dir'];
		}
		
		$data = array(
			'vodinfo_id' 	=> intval($video_info['id']),
			'img' 			=> $img,
			'title' 		=> $add_input['title'],
			'brief' 		=> $add_input['brief'],
			'toff' 			=> $toff,
			'user_id' 		=> $user['user_id'],
			'user_name' 	=> $user['user_name'],
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
			'filename' 		=> $filename,
			'newname' 		=> $newname,
	//		'filepath' 		=> $filepath,
			'ip' 			=> hg_getip(),
			'url' 			=> $url,
			'server_id' 	=> $server_id,
			'type'			=> $add_input['type'],
			'status'		=> 3,
		);
	
		$sql = "INSERT INTO " . DB_PREFIX . "backup SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		if ($data['id'] && $url)
		{
			$callback = $this->settings['App_live']['protocol'] . $this->settings['App_live']['host'] . '/' . $this->settings['App_live']['dir'] . 'admin/callback.php?a=backup_callback&id=' . $data['id'] . '&access_token=' . $user['token'];

			$ret_videofile = $this->mLivemms->inputFileInsert($host, $apidir, $url, urlencode($callback));

			if (!$ret_videofile['result'])
			{
				//提交媒体库失败时，删除本地库内容
			//	$sql = "DELETE FROM " . DB_PREFIX . "backup WHERE id = " . $data['id'];
			//	$this->db->query($sql);
				return -20;
			}
			
			$videofileid = $ret_videofile['file']['id'];
			$sql = "UPDATE " . DB_PREFIX . "backup SET fileid = '" . $videofileid . "' WHERE id = " . $data['id'];
			$this->db->query($sql);
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX ."backup WHERE id = " . $data['id'];
		$ret = $this->db->query_first($sql);
		$this->addLogs('新增备播文件' , '' , $ret , $ret['title']);
		
		if ($data['id'])
		{	
			return $data['id'];
		}
		return false;
	}

	public function update($id, $add_input, $user)
	{
		$sql = "SELECT id, title, vodinfo_id, videofilename, fileid FROM " . DB_PREFIX . "backup WHERE id = " . $id;
		$backup_info = $this->db->query_first($sql);
		
		if (empty($backup_info))
		{
			return false;
		}
		
		$video_info = $add_input['video_info'];
		$file_info 	= $add_input['file_info'];
		
		//服务器配置
		$server_id  = $add_input['server_id'];
		if ($server_id)
		{
			$server_info = $this->mServerConfig->get_server_config_by_id($server_id);
		}
		
		if ($server_info['core_in_host'])
		{
			$host	= $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			$apidir	= $server_info['input_dir'];
		}
		else 
		{
			$host	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$apidir	= $this->settings['wowza']['core_input_server']['input_dir'];
		}
		
		$data = array(
			'title' 		=> $add_input['title'],
			'brief' 		=> $add_input['brief'],
			'update_time' 	=> TIMENOW,
			'server_id'   	=> $add_input['server_id'],
			'type'   		=> $add_input['type'],
		//	'status'		=> 3,
		);
		
		if (!empty($video_info))
		{
			$vodinfo_id = $video_info['id'];
			if($vodinfo_id && $vodinfo_id != $backup_info['vodinfo_id'])
			{
				$img = '';
				if (is_array($video_info['img_info']))
				{
					$img = @serialize($video_info['img_info']);
				}
		
				$data['filename'] 	= $video_info['title'];
				$data['toff'] 		= $video_info['duration'];
				$data['vodinfo_id'] = $vodinfo_id;
				$data['img'] 		= $img;
				$data['newname'] 	= $video_info['video_filename'];
				$data['url'] 		= $video_info['vod_url'];
				$data['status'] 	= 3;
			}
		}
		else if (!empty($file_info))
		{
			$data['filename'] 	= $file_info['name'];
			$data['toff'] 		= $this->settings['backup_file_toff'] . '000';;
			$data['vodinfo_id'] = '00';
			$data['img'] 		= '';
			$data['newname'] 	= $add_input['title'];
			$data['url'] 		= $this->settings['App_live']['protocol'] . $this->settings['App_live']['host'] . '/' . $this->settings['App_live']['dir'] . BACKUP_PATH . $file_info['name'];
			$data['status'] 	= 3;
		}
		
		$sql_ =  "SELECT * FROM " . DB_PREFIX . "backup WHERE id = " . $id;
		$pre_data = $this->db->query_first($sql_);
		
		$sql = "UPDATE " . DB_PREFIX . "backup SET ";
		$space = "";
		foreach($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= " WHERE id=" . $id; 
		$this->db->query($sql);
		
		$data['id'] = $id;
		
		if ($data['id'] && $data['url'])
		{
			if ($backup_info['fileid'])
			{
				$ret_videofile_del = $this->mLivemms->inputFileDelete($host, $apidir, $backup_info['fileid']);
			}
			
			$callback = $this->settings['App_live']['protocol'].$this->settings['App_live']['host'].'/'.$this->settings['App_live']['dir'].'admin/callback.php?a=backup_callback&id='.$data['id'].'&access_token=' . $user['token'];
			
			$ret_videofile = $this->mLivemms->inputFileInsert($host, $apidir, $data['url'], urlencode($callback));
			
			if (!$ret_videofile['result'])
			{
				return -20;
			}
			
			$new_videofileid = $ret_videofile['file']['id'];
			$sql = "UPDATE " . DB_PREFIX . "backup SET fileid = '" . $new_videofileid . "' WHERE id = " . $data['id'];
			$this->db->query($sql);

			//更新备播文件所涉及的相关信息
			$this->updateRelevantInfo($host, $apidir, $backup_info['id'], $backup_info['fileid'], $new_videofileid, $data['title'], $data['toff']);
		}
		else
		{
			if ($data['title'] != $backup_info['title'])
			{
				//更新备播文件所涉及的相关信息
				$this->updateRelevantInfo($host, $apidir, $backup_info['id'], $backup_info['fileid'], $backup_info['fileid'], $data['title'], $data['toff']);
			}
		}
		
		$this->addLogs('更新备播文件' , $pre_data , $data , $pre_data['title']);
		
		if ($data['id'])
		{
			return $data;
		}

		return false;
	}
	
	public function delete($id)
	{
		$sql = "SELECT id, fileid, videofilename, server_id FROM " . DB_PREFIX . "backup WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		
		$backup_info = $server_id = array();
		while ($row = $this->db->fetch_array($q))
		{
			$server_id[] = $row['server_id'];
			$backup_info[$row['id']] = $row['fileid'];
		}
		
		//服务器配置
		if (!empty($server_id))
		{
			$server_id	   	= implode(',', @array_unique($server_id));
			$server_infos   = $this->mServerConfig->get_server_config($server_id);
		//	$server_outputs = $this->mServerConfig->get_server_output($server_id);
		}
		
		if (!empty($backup_info))
		{
			$ret_videofile_del = array();
			foreach ($backup_info AS $k=>$v)
			{
				if ($v)
				{
					$server_info = $server_infos[$v['server_id']];
					if ($server_info['core_in_host'])
					{
						$host	= $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
						$apidir = $server_info['input_dir'];
					}
					else 
					{
						$host	= $this->settings['wowza']['core_input_server']['host'];
						$apidir	= $this->settings['wowza']['core_input_server']['input_dir'];
					}
					
					$ret_videofile_del[$k] = $this->mLivemms->inputFileDelete($host, $apidir, $v);

					if (!$ret_videofile_del[$k]['result'])
					{
						continue;
					}
				}
			}
		}
		$sql_ =  "SELECT * FROM " . DB_PREFIX . "backup WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql_);
		$ret = array();
		while($row = $this->db->fetch_array($q))
		{
			$ret[] = $row;
		}
		
		$sql = "DELETE FROM " . DB_PREFIX . "backup WHERE id IN (" . $id . ")";
		
		if ($this->db->query($sql))
		{	
			$this->addLogs('删除备播文件' , $ret , '', '删除备播文件'.$id);
			return $id;
		}
		return false;
	}
	
	/**
	 * 更新相关信息 (信号流、切播、串联单、串联单计划)
	 * Enter description here ...
	 */
	public function updateRelevantInfo($host, $apidir, $backupId, $videofileid, $new_videofileid, $backupTitle, $file_toff)
	{
		if (!$backupId && !$videofileid && !$new_videofileid && !$backupTitle)
		{
			return false;
		}
		
		$return = array();
		
		//更新信号流
		$return['stream'] = $this->streamInfoById($videofileid);
		if ($return['stream'])
		{
			$this->updateStream($host, $apidir, $return['stream'], $videofileid, $new_videofileid, $backupTitle);
		}
		
		//返回直播
		$return['chg'] = $this->channelChgByBackupId($backupId);
		if ($return['chg'])
		{
			$this->updateChg($return['chg']);
		}
		
		//更新串联单
		$return['chg_paln'] = $this->channelChgPlanByBackupId($backupId);
		if ($return['chg_paln'])
		{
			$this->updateChgPlan($host, $apidir, $return['chg_paln'], $new_videofileid, $backupTitle, $file_toff);
		}
		
		//更新串联单计划
		$return['change_plan'] = $this->channelChangePlanByBackupId($backupId);
		if ($return['change_plan'])
		{
			$this->updateChangePlan($return['change_plan'], $new_videofileid, $backupTitle, $file_toff);
		}
	}
	
	/**
	 * 更新信号流
	 * Enter description here ...
	 * @param unknown_type $stream
	 * @param unknown_type $videofileid
	 * @param unknown_type $new_videofileid
	 * @param unknown_type $backupTitle
	 */
	public function updateStream($host, $apidir, $stream, $videofileid, $new_videofileid, $backupTitle)
	{
		if (empty($stream))
		{
			return false;
		}

		$stream_info = array();
		foreach ($stream AS $key=>$val)
		{
			if ($val['other_info']['input'])
			{
				$other_info = array();
				foreach ($val['other_info']['input'] AS $k=>$v)
				{
					if (!empty($v['source_name']))
					{
						$source_name[$k] = $backup_title[$k] = array();
						foreach ($v['source_name'] AS $kk=>$vv)
						{
							if ($vv == $videofileid)
							{
								$vv = $new_videofileid;
								$v['backup_title'][$kk] = $backupTitle;
								//更新备播文件记录
								$sql = "DELETE FROM " . DB_PREFIX . "stream_other_info WHERE input_id = " . $v['id'] . " AND source_name = '" . $videofileid . "'";
								$this->db->query($sql);
								
								$otherInfoData = array(
									'stream_id' => $val['id'],
								//	's_name' => $val['s_name'],
									'ch_name' => $val['ch_name'],
									'input_id' => $v['id'],
									'stream_name' => $v['name'],
									'source_name' => $new_videofileid,
								);
								
								$sql = "INSERT INTO " . DB_PREFIX . "stream_other_info SET ";
								$space = "";
								foreach ($otherInfoData AS $kkk => $vvv)
								{
									$sql .= $space . $kkk . "=" . "'" . $vvv . "'";
									$space = ",";
								}
								$this->db->query($sql);
							}
							
							$source_name[$k][$kk] = $vv;
							$backup_title[$k][$kk] = $v['backup_title'][$kk];
						}
						
						if (!empty($source_name[$k]))
						{
							$v['source_name'] = $source_name[$k];
							$v['backup_title'] = $backup_title[$k];
						}
	
						$ret_file = $this->mLivemms->inputFileListUpdate($host, $apidir, $v['id'], implode(',', $v['source_name']));
		
						$sourceType = 3;
						
						if (!$ret_file['result'])
						{
							continue;
						}
						
						$other_info['file'][$k] = array(
							'source_id' => $v['id'],
							'source_type' => $sourceType,
					//		'chg_stream_id' => $ret_chg_id[$k],
							'name' => $v['name'],
					//		'uri' => $v['uri'],
							'fileid' => $v['source_name'],
							'backup_title' => $v['backup_title'],
						);
											
						$other_info['input'][$k] = $v;
						
						//重启信号流
						if ($val['s_status'])
						{
							$this->mLivemms->inputFileListOperate($host, $apidir, 'start', $v['id']);
						}
						else 
						{
							$this->mLivemms->inputFileListOperate($host, $apidir, 'stop', $v['id']);
						}
					}
				}

				if (!empty($other_info))
				{
					$val['other_info'] = $other_info;
				}
				
				$up_data = array();
				$up_data = array(
						'id'				=>		$val['id'],
						'other_info'		=>		serialize($val['other_info']),
						'update_time'		=>		TIMENOW,
				);
				$sql_ =  "SELECT id,other_info,update_time FROM " . DB_PREFIX . "stream WHERE id = " . $val['id'];
				$pre_data = $this->db->query_first($sql_);
				
				$sql = "UPDATE " . DB_PREFIX . "stream SET other_info = '" . serialize($val['other_info']) . "', update_time = " . TIMENOW . " WHERE id = " . $val['id'];
				$this->db->query($sql);
				
				$stream_info[$key] = $val;
				
				$this->addLogs('update' , $pre_data , $up_data , '' , '');
			}
		}
	}
	
	/**
	 * 返回直播
	 * Enter description here ...
	 * @param unknown_type $channelChg
	 */
	public function updateChg($chg)
	{
		if (empty($chg))
		{
			return false;
		}

		require_once CUR_CONF_PATH . 'lib/live.class.php';
		$this->mLive = new live();
		
		foreach ($chg AS $k=>$v)
		{
			$this->mLive->emergency_change($v['id'], $v['stream_id'], 'stream');
		}
	}
	
	/**
	 * 更新串联单
	 * Enter description here ...
	 * @param unknown_type $chg_plan
	 * @param unknown_type $new_videofileid
	 * @param unknown_type $backupTitle
	 */
	public function updateChgPlan($host, $apidir, $chg_plan, $new_videofileid, $backupTitle, $file_toff)
	{
		if (empty($chg_plan))
		{
			return false;
		}

		foreach ($chg_plan AS $k=>$v)
		{
			if ($v['epg_id'])
			{
				$epg_delete = $this->mLivemms->inputScheduleOperate($host, $apidir, 'delete', $v['epg_id']);
			}
			
			if (!$epg_delete['result'])
			{
				continue;
			}
			
			$ret_list = $this->mLivemms->inputFileListInsert($host, $apidir, $new_videofileid);
			
			if (!$ret_list['result'])
			{
				continue;
			}
			
			$sourceId = $ret_list['list']['id']; //$new_videofileid
			
			$sourceType = 3;
			
			if ($v['source_id'])
			{
				$ret_list_delete = $this->mLivemms->inputFileListOperate($host, $apidir, 'delete', $v['source_id']);
			}
			
			$epg_insert = $this->mLivemms->inputScheduleInsert($host, $apidir, $v['out_stream_id'], $sourceId, $sourceType, $v['change_time'], $v['toff']);
					
			if (!$epg_insert['result'])
			{
				continue;
			}
			
			$up_data = array();
			$up_data = array(
						'id'				=>		$v['id'],
						'epg_id'			=>		$epg_insert['schedule']['id'],
						'source_id'			=>		$sourceId,
						'channel2_name'		=>		$backupTitle,
						'file_toff'			=>		$file_toff,
				);
			$sql_ =  "SELECT id,epg_id,source_id,channel2_name,file_toff  FROM " . DB_PREFIX . "channel_chg_plan WHERE id = " . $v['id'];
			$pre_data = $this->db->query_first($sql_);
			
			$sql = "UPDATE " . DB_PREFIX . "channel_chg_plan SET epg_id = " . $epg_insert['schedule']['id'] . ", source_id = " . $sourceId . ", channel2_name ='" . $backupTitle . "', file_toff=" . $file_toff . " WHERE id = " . $v['id'];
			$this->db->query($sql);
			
			$this->addLogs('update' , $pre_data , $up_data , '' , '');
		}
	}
	
	/**
	 * 更新串联单计划
	 * Enter description here ...
	 * @param unknown_type $change_plan
	 * @param unknown_type $new_videofileid
	 * @param unknown_type $backupTitle
	 */
	public function updateChangePlan($change_plan, $new_videofileid, $backupTitle, $file_toff)
	{
		if (empty($change_plan) && !$new_videofileid && !$backupTitle)
		{
			return false;
		}
		
	
		foreach ($change_plan AS $k=>$v)
		{
			$up_data = array();
			$up_data = array(
						'id'				=>		$v['id'],
						'source_id'			=>		$new_videofileid,
						'channel2_name'		=>		$backupTitle,
						'file_toff'			=>		$file_toff,
				);
			$sql_ =  "SELECT * FROM " . DB_PREFIX . "change_plan WHERE id = " . $v['id'] ." AND source_id = " . $new_videofileid . ", file_toff=" . $file_toff . ", channel2_name = '" . $backupTitle;
			$pre_data = $this->db->query_first($sql_);
		
			$sql = "UPDATE " . DB_PREFIX . "change_plan SET source_id = " . $new_videofileid . ", file_toff=" . $file_toff . ", channel2_name = '" . $backupTitle . "' WHERE id = " . $v['id'];
			$this->db->query($sql);
			
			$this->addLogs('update' , $pre_data , $up_data , '' , '');
		}
	}
	
	/**
	 * 信号流
	 * Enter description here ...
	 * @param unknown_type $videofileid
	 */
	public function streamInfoById($videofileid)
	{
		if (!$videofileid)
		{
			return false;
		}
		
		$sql = "SELECT stream_id FROM " . DB_PREFIX . "stream_other_info WHERE source_name IN ('" . $videofileid . "')";
		$q = $this->db->query($sql);
		
		$stream_other_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$stream_other_info[$row['stream_id']] = $row['stream_id'];
		}

		if (!empty($stream_other_info))
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "stream WHERE id IN (" . implode(',', $stream_other_info) . ")";
			$q = $this->db->query($sql);
			
			$stream = array();
			while ($row = $this->db->fetch_array($q))
			{
				$row['uri'] = unserialize($row['uri']);
				$row['other_info'] = unserialize($row['other_info']);
				$stream[$row['id']] = $row;
			}
			
			if (!empty($stream))
			{
				return $stream;
			}
			return false;
		}
	}
	
	/**
	 * 频道切播
	 * Enter description here ...
	 * @param unknown_type $backupId
	 */
	public function channelChgByBackupId($backupId)
	{
		if (!$backupId)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "channel WHERE chg_type='file' AND chg2_stream_id IN (" . $backupId . ")";
		$q = $this->db->query($sql);
		
		$channel = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel[$row['id']] = $row;
		}
		
		if (!empty($channel))
		{
			return $channel;
		}
		return false;
	}
	
	/**
	 * 串联单
	 * Enter description here ...
	 * @param unknown_type $backupId
	 */
	public function channelChgPlanByBackupId($backupId)
	{
		if (!$backupId)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_chg_plan WHERE channel2_id IN (" . $backupId . ") AND type = 2 AND change_time > " . TIMENOW;
		$q = $this->db->query($sql);
		
		$channel_chg_plan = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel_chg_plan[$row['id']] = $row;
		}
		
		if (!empty($channel_chg_plan))
		{
			return $channel_chg_plan;
		}
		return false;
	}
	
	/**
	 * 串联单计划
	 * Enter description here ...
	 * @param unknown_type $backupId
	 */
	public function channelChangePlanByBackupId($backupId)
	{
		if (!$backupId)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "change_plan WHERE type = 2 AND channel2_id IN (" . $backupId . ")";
		$q = $this->db->query($sql);
		
		$change_plan = array();
		while ($row = $this->db->fetch_array($q))
		{
			$change_plan[$row['id']] = $row;
		}
		
		if (!empty($change_plan))
		{
			return $change_plan;
		}
		return false;
	}
	
	/**
	 * 检测备播文件是否被占用
	 * Enter description here ...
	 */
	public function check_backup($backupId)
	{
		$sql = "SELECT id, title, fileid FROM " . DB_PREFIX . "backup WHERE id IN (" . $backupId . ")";
		$q = $this->db->query($sql);
		
		$backup_info = $videofileid = array();
		while ($row = $this->db->fetch_array($q))
		{
			$backup_info[$row['id']] = $row;
			$videofileid[$row['id']] = "'".$row['fileid']."'";
			$backupTitle[$row['id']] = $row['title']; 
		}
		
		if (empty($backup_info))
		{
			return false;
		}
				
		$return = array();
		
		//信号流
		if ($this->check_stream($videofileid))
		{
			$return['stream'] = $this->check_stream($videofileid);
		}
			
		//切播
		if ($this->check_chg($backupId))
		{
			$return['chg'] = $this->check_chg($backupId);
		}
	
		//串联单
		if ($this->check_chg_plan($backupId))
		{
			$return['chg_plan'] = $this->check_chg_plan($backupId);
		}
		
		//串联单计划
		if ($this->check_change_plan($backupId))
		{
			$return['change_plan'] = $this->check_change_plan($backupId);
		}
		
		if (!empty($return))
		{
			return $return;
		}
		
		return false;
	}
	
	/**
	 * 检测该备播文件是否被 信号流 所占用
	 * Enter description here ...
	 */
	public function check_stream($videofileid)
	{
		if ($videofileid)
		{
			$videofileid = implode(',', $videofileid);
		}
		else 
		{
			return false;
		}
		
		$sql = "SELECT ch_name, stream_id FROM " . DB_PREFIX . "stream_other_info WHERE source_name IN (" . $videofileid . ")";
		$q = $this->db->query($sql);
		
		$stream_other_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$stream_other_info[$row['stream_id']] = $row['ch_name'];
		}

		if (!empty($stream_other_info))
		{
			return $stream_other_info;
		}
		return false;
	}
	
	/**
	 * 检测该备播文件是否被 切播 所占用
	 * Enter description here ...
	 */
	public function check_chg($backupId)
	{
		if (!$backupId)
		{
			return false;
		}
		
		$sql = "SELECT name, id FROM " . DB_PREFIX . "channel WHERE chg_type = 'file' AND chg2_stream_id IN (" . $backupId . ")";
		$q = $this->db->query($sql);
		
		$channel_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel_info[$row['id']] = $row['name'];
		}

		if (!empty($channel_info))
		{
			return $channel_info;
		}
		return false;
	}
	
	/**
	 * 检测该备播文件是否被 串联单 所占用
	 * Enter description here ...
	 */
	public function check_chg_plan($backupId)
	{
		if (!$backupId)
		{
			return false;
		}
		
		$sql = "SELECT channel_id, id FROM " . DB_PREFIX . "channel_chg_plan WHERE channel2_id IN (" . $backupId . ") AND type = 2 AND change_time > " . TIMENOW;
		$q = $this->db->query($sql);
		
		$channel_ids = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel_ids[$row['id']] = $row['channel_id'];
		}

		if (!empty($channel_ids))
		{
			$sql = "SELECT name, id FROM " . DB_PREFIX . "channel WHERE id IN (" . implode(',', $channel_ids) . ")";
			$q = $this->db->query($sql);
			
			$channel_info = array();
			while ($row = $this->db->fetch_array($q))
			{
				$channel_info[$row['id']] = $row['name'];
			}
		}

		if (!empty($channel_info))
		{
			return $channel_info;
		}
		return false;
	}
	
	/**
	 * 检测该备播文件是否被 串联单计划 所占用
	 * Enter description here ...
	 */
	public function check_change_plan($backupId)
	{
		if (!$backupId)
		{
			return false;
		}
		
		$sql = "SELECT channel_id, id FROM " . DB_PREFIX . "change_plan WHERE type = 2 AND channel2_id IN (" . $backupId . ")";
		$q = $this->db->query($sql);
		
		$channel_ids = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel_ids[$row['id']] = $row['channel_id'];
		}

		if (!empty($channel_ids))
		{
			$sql = "SELECT name, id FROM " . DB_PREFIX . "channel WHERE id IN (" . implode(',', $channel_ids) . ")";
			$q = $this->db->query($sql);
			
			$channel_info = array();
			while ($row = $this->db->fetch_array($q))
			{
				$channel_info[$row['id']] = $row['name'];
			}
		}

		if (!empty($channel_info))
		{
			return $channel_info;
		}
		return false;
	}
	
	/**
	 * 流媒体执行的回调函数
	 * Enter description here ...
	 */
	public function backup_edit($id, $data)
	{
		$sql = "UPDATE " . DB_PREFIX . "backup SET ";
		$space = "";
		foreach($data as $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= " WHERE id=" . $id; 
		
		if ($this->db->query($sql))
		{
			return $data;
		}
		return false;
	}
	
	/**
	 * 获取媒体库视频信息
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public function getVodInfoById($id)
	{
		if (!$this->mVod)
		{
			return array();
		}
		
		$this->mVod->setSubmitType('post');
		$this->mVod->initPostData();
		$this->mVod->addRequestData('a','getVodInfoById');
		$this->mVod->addRequestData('id',$id);
		$return = $this->mVod->request('admin/vod2backup.php');
		return $return[0];
	}
	
	public function getBackupInfo($condition, $offset, $count, $width='40', $height='30')
	{
		$limit = " LIMIT " . $offset . " , " . $count;

		$orderby = " ORDER BY id DESC ";
		
		$sql = "SELECT id, vodinfo_id, title, img, status, toff, fileid, server_id FROM " . DB_PREFIX . "backup ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		
		$backup_info = $server_id = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['toff_s'] 	= $row['toff']/1000;
			$row['toff'] 	= time_format($row['toff']);
			
			$row['img'] = @unserialize($row['img']);
			if ($row['img'])
			{
				$imgsize = $width . 'x' . $height . '/';
				$row['img'] = hg_material_link($row['img']['host'], $row['img']['dir'], $row['img']['filepath'], $row['img']['filename'], $imgsize);
			}
			$server_id[] = $row['server_id'];
			$backup_info[$row['id']] = $row;
		}
		//服务器配置
		if (!empty($server_id))
		{
			$server_id	   	= implode(',', @array_unique($server_id));
			$server_infos   = $this->mServerConfig->get_server_config($server_id);
		}

		$return = array();
		if (!empty($backup_info))
		{
			foreach ($backup_info AS $v)
			{
				if($v['fileid'])
				{
					$server_info = $server_infos[$v['server_id']];
					if ($server_info['core_in_host'])
					{
						$wowzaip  = $server_info['core_in_host'];
					}
					else 
					{
						$wowzaip  = $this->settings['wowza']['core_input_server']['host'];
					}
					
					$app_name = $this->settings['wowza']['backup']['app_name'];
					$prefix   = $this->settings['wowza']['backup']['prefix'];
					$midfix   = $this->settings['wowza']['backup']['midfix'];
					$suffix   = $this->settings['wowza']['backup']['suffix'];
						
					$v['file_uri'] = hg_streamUrl($wowzaip, $app_name, $prefix . $midfix . $v['fileid'] . $suffix);
				}
				$return[] = $v;
			}
		}
		return $return;
	}
	
	public function get_backup_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "backup WHERE id = " . $id;
		$ret = $this->db->query_first($sql);
		return $ret;
	} 
}
?>