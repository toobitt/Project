<?php
/***************************************************************************
* $Id: channel_update.php 31428 2013-11-07 08:02:20Z tong $
***************************************************************************/
define('MOD_UNIQUEID','live_takeover');
require('global.php');
class channelUpdateApi extends adminUpdateBase
{
	private $mChannel;
	function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/channel.class.php';
		$this->mChannel = new channel();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$name 				= trim($this->input['name']);
		$brief 				= trim($this->input['brief']);
		$url		 	 	= $this->input['url'];
		$output_url 	 	= $this->input['output_url'];
		$m3u8	  			= $this->input['m3u8'];
		$timeshift_url	  	= $this->input['timeshift_url'];
	//	$is_url				= intval($this->input['is_url']);
	//	$is_mobile_phone	= intval($this->input['is_mobile_phone']);
		$is_audio			= intval($this->input['is_audio']);
		$time_shift			= intval($this->input['time_shift']);
		
		$_appid				= $this->input['_appid'];
		$_appname			= $this->input['_appname'];
		
		//频道截图
		$snap_host			= trim($this->input['snap_host']);
		$snap_dir			= trim($this->input['snap_dir']);
		$snap_filepath		= trim($this->input['snap_filepath']);
		$snap_filename		= trim($this->input['snap_filename']);
		
		if (!$name)
		{
			$this->errorOutput('频道名称不能为空');
		}
		
		$brief = $brief == '这里输入描述' ? '' : $brief;
		
		//频道截图
		$snap = '';
		if ($snap_host)
		{
			$snap = array(
				'host'		=> $snap_host,
				'dir'		=> $snap_dir,
				'filepath'	=> $snap_filepath,
				'filename'	=> $snap_filename,
			);
			$snap = serialize($snap);
		}
		
		//时移时间
		if ($time_shift > $this->settings['max_time_shift'])
		{
			$time_shift = $this->settings['max_time_shift'];
		}
		
		$data = array(
			'name'				=> $name,
			'brief'				=> $brief,
		//	'is_url'			=> $is_url,
		//	'is_mobile_phone'	=> $is_mobile_phone,
			'is_audio'			=> $is_audio,
			'time_shift'		=> $time_shift,
			'org_id'			=> $this->user['org_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'appid'				=> $this->user['appid'],
			'appname'			=> $this->user['display_name'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'ip'				=> hg_getip(),
			'snap'				=> $snap,
			'can_record'			=> $this->input['can_record'],
			'record_uri'			=> $this->input['record_uri'],
		);
		
		$ret = $this->mChannel->create($data);
		
		if (!$ret['id'])
		{
			$this->errorOutput('添加失败');
		}
		
		$id = $ret['id'];
		$data['id'] = $id;
		
		$is_url = $is_mobile_phone = $main_stream_id = 0;
		foreach ($url AS $k => $v)
		{
			if (trim($v))
			{
				$stream_data = array(
					'channel_id'	=> $id,
					'url'			=> trim($v),
					'output_url'	=> trim($output_url[$k]),
					'm3u8'			=> trim($m3u8[$k]),
					'timeshift_url'	=> trim($timeshift_url[$k]),
					'is_main'		=> ($k == 0) ? 1 : 0,
					'order_id'		=> $k,
				);
				
				$ret_stream = $this->mChannel->channel_stream_create($stream_data);
				
				if ($ret_stream['id'] && $k == 0)
				{
					$main_stream_id = $ret_stream['id'];
				}
				
				$is_url = 1;
				$is_mobile_phone = trim($m3u8[$k]) ? 1 : 0;
			}
		}
		
		//长方形logo
		if ($_FILES['logo_rectangle']['tmp_name'])
		{
			$logo_rectangle = $this->mChannel->add_material($_FILES['logo_rectangle'], $id);
		}
		
		$data['logo_rectangle'] = $logo_rectangle ? serialize($logo_rectangle) : '';
		
		//方形logo
		if ($_FILES['logo_square']['tmp_name'])
		{
			$logo_square = $this->mChannel->add_material($_FILES['logo_square'], $id);
		}
		
		$data['logo_square'] = $logo_square ? serialize($logo_square) : '';
		
		if (!empty($_FILES['client_logo']))
		{
			$client_logo = array();
			foreach ($_FILES['client_logo'] AS $k => $v)
			{
				$$k = $v;
				foreach ($$k AS $kk => $vv)
				{
					$client_logo[$kk][$k] = $vv;
				}
			}
			
			$_client_logo = array();
			foreach ($client_logo AS $appid => $logo)
			{
				$_client_logo[$appid] = $this->mChannel->add_material($logo, $id);
				$_client_logo[$appid]['appid'] 	 = $_appid[$appid];
				$_client_logo[$appid]['appname'] = $_appname[$appid];
			}
		}
		
		$data['client_logo'] = $_client_logo ? serialize($_client_logo) : ''; 
		
		$data['order_id']	 = $id;
		
		//更新 排序id、长方形logo、方形logo
		$update_data = array(
			'id'			 => $id,
			'main_stream_id' => $main_stream_id,
			'order_id'		 => $data['order_id'],
			'logo_rectangle' => $data['logo_rectangle'],
			'logo_square'	 => $data['logo_square'],
			'client_logo'	 => $data['client_logo'],
			'is_url'		 => $is_url,
			'is_mobile_phone'=> $is_mobile_phone,
		);
		//更新数据
		$ret = $this->mChannel->update($update_data);
		
		if ($id)
		{
			//日志
			$this->addLogs('新增频道接管' , '' , $data , $data['name'], $data['id']) ;
		}
		
		$this->addItem($data);
		$this->output();
	}
	
	public function update()
	{
		$id					= intval($this->input['id']);
		$name 				= trim($this->input['name']);
		$brief 				= trim($this->input['brief']);
		$url		 	 	= $this->input['url'];
		$output_url 	 	= $this->input['output_url'];
		$m3u8	  			= $this->input['m3u8'];
		$timeshift_url	  	= $this->input['timeshift_url'];
	//	$is_url				= intval($this->input['is_url']);
	//	$is_mobile_phone	= intval($this->input['is_mobile_phone']);
		$is_audio			= intval($this->input['is_audio']);
		$time_shift			= intval($this->input['time_shift']);
		
		$_appid				= $this->input['_appid'];
		$_appname			= $this->input['_appname'];
		
		$stream_id			= $this->input['stream_id'];
		
		//频道截图
		$snap_host			= trim($this->input['snap_host']);
		$snap_dir			= trim($this->input['snap_dir']);
		$snap_filepath		= trim($this->input['snap_filepath']);
		$snap_filename		= trim($this->input['snap_filename']);
		
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		
		if (!$name)
		{
			$this->errorOutput('频道名称不能为空');
		}
		
		$brief = $brief == '这里输入描述' ? '' : $brief;
	
		//频道截图
		$snap = '';
		if ($snap_host)
		{
			$snap = array(
				'host'		=> $snap_host,
				'dir'		=> $snap_dir,
				'filepath'	=> $snap_filepath,
				'filename'	=> $snap_filename,
			);
			$snap = serialize($snap);
		}
		
		//时移时间
		if ($time_shift > $this->settings['max_time_shift'])
		{
			$time_shift = $this->settings['max_time_shift'];
		}
		
		//取频道信息
		$channel_info = $this->mChannel->get_channel_by_id($id);
		
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		$is_sys = $channel_info['is_sys'];
		
		//取channel_stream
		$channel_stream = $this->mChannel->get_channel_stream_by_channel_id($id);
		
		$stream_id_del = array();
		if (!empty($channel_stream))
		{
			$stream_id_db = array();
			foreach ($channel_stream AS $v)
			{
				$stream_id_db[] = $v['id'];
			}
			
			//delete
			$stream_id_del = array_diff($stream_id_db, $stream_id);
		}
		
		if (!$is_sys)
		{
			$data = array(
				'id'				=> $id,
				'name'				=> $name,
				'brief'				=> $brief,
			//	'is_url'			=> $is_url,
			//	'is_mobile_phone'	=> $is_mobile_phone,
				'is_audio'			=> $is_audio,
				'time_shift'		=> $time_shift,
				'snap'				=> $snap,
				'can_record'			=> $this->input['can_record'],
				'record_uri'			=> $this->input['record_uri'],
			);
			
			$ret = $this->mChannel->update($data);
			
			if (!$ret['id'])
			{
				$this->errorOutput('添加失败');
			}
			
			//更新标记
			$affected_rows = $ret['affected_rows'];
			
			$is_url = $is_mobile_phone = $main_stream_id = 0;
			foreach ($url AS $k => $v)
			{
				if (trim($v))
				{
					$stream_data = array(
						'channel_id'	=> $id,
						'url'			=> trim($v),
						'output_url'	=> trim($output_url[$k]),
						'm3u8'			=> trim($m3u8[$k]),
						'timeshift_url'	=> trim($timeshift_url[$k]),
						'is_main'		=> ($k == 0) ? 1 : 0,
						'order_id'		=> $k,
					);
					
					if (!$stream_id[$k])
					{
						$ret_stream = $this->mChannel->channel_stream_create($stream_data);
					}
					else 
					{
						$stream_data['id'] = $stream_id[$k];
						$ret_stream = $this->mChannel->channel_stream_update($stream_data);
					}
					
					if ($ret_stream['id'] && $k == 0)
					{
						$main_stream_id = $ret_stream['id'];
					}
					
					$is_url = 1;
					$is_mobile_phone = trim($m3u8[$k]) ? 1 : 0;
				}
			}
			
			//删除
			if (!empty($stream_id_del))
			{
				$stream_ids = implode(',', $stream_id_del);
				$this->mChannel->channel_stream_delete($stream_ids);
			}
			
			//长方形logo
			if ($_FILES['logo_rectangle']['tmp_name'])
			{
				$logo_rectangle = $this->mChannel->add_material($_FILES['logo_rectangle'], $id);
			}
			
			$data['logo_rectangle'] = $logo_rectangle ? serialize($logo_rectangle) : '';
			
			//方形logo
			if ($_FILES['logo_square']['tmp_name'])
			{
				$logo_square = $this->mChannel->add_material($_FILES['logo_square'], $id);
			}
			
			$data['logo_square'] = $logo_square ? serialize($logo_square) : '';
			
			//多客户端logo
			if (!empty($_FILES['client_logo']) || $channel_info['client_logo'])
			{
				$client_logo = array();
				foreach ($_FILES['client_logo'] AS $k => $v)
				{
					$$k = $v;
					foreach ($$k AS $kk => $vv)
					{
						$client_logo[$kk][$k] = $vv;
					}
				}
	
				$_client_logo = array();
				foreach ($_appid AS $appid)
				{
					if ($client_logo[$appid])
					{
						$_client_logo[$appid] = $this->mChannel->add_material($client_logo[$appid], $id);
						$_client_logo[$appid]['appid'] 	 = $_appid[$appid];
						$_client_logo[$appid]['appname'] = $_appname[$appid];
					}
					else
					{
						$_client_logo[$appid] = $channel_info['client_logo'][$appid];
						if (!$channel_info['client_logo'][$appid])
						{
							unset($_client_logo[$appid]);
						}
					}
				}
			}
	
			$data['client_logo'] = $_client_logo ? serialize($_client_logo) : '';
			
			//更新 长方形logo、方形logo、多客户端logo、音频logo
			$update_data = array(
				'id' => $id,
			);
			
			if ($data['logo_rectangle'])
			{
				$update_data['logo_rectangle'] = $data['logo_rectangle'];
			}
			
			if ($data['logo_square'])
			{
				$update_data['logo_square'] = $data['logo_square'];
			}
			
			if ($data['client_logo'])
			{
				$update_data['client_logo'] = $data['client_logo'];
			}
		
			if ($channel_info['client_logo'] && empty($_appid))
			{
				$data['client_logo'] = $channel_info['client_logo'];
				$update_data['client_logo'] = '';
			}
			
			//更新数据
			if ($data['logo_rectangle'] || $data['logo_square'] || $data['client_logo'])
			{
				$ret_logo = $this->mChannel->update($update_data);
				
				$affected_rows = $ret_logo['affected_rows'];
			}
		
			//记录日志
			if ($affected_rows)
			{
				$user_data = array(
					'id'				=> $id,
					'main_stream_id'	=> $main_stream_id,
					'is_url'		 	=> $is_url,
					'is_mobile_phone'	=> $is_mobile_phone,
					'update_time'		=> TIMENOW,
					'update_org_id' 	=> $this->user['org_id'],
					'update_user_id' 	=> $this->user['user_id'],
					'update_user_name' 	=> $this->user['user_name'],
					'update_appid' 		=> $this->user['appid'],
					'update_appname' 	=> $this->user['display_name'],
					'update_ip' 		=> hg_getip(),
				);
				
				$ret_user = $this->mChannel->update($user_data);
				
				if (!empty($ret_user))
				{
					unset($ret_user['id']);
					foreach ($ret_user AS $k => $v)
					{
						$data[$k] = $v;
					}
				}
				
				$pre_data = $channel_info;
				$this->addLogs('更新直播接管频道' , $pre_data , $data , $data['name'], $data['id']);
			}
		}
		
		$this->addItem($data);
		$this->output();
	}
	
	public function delete()
	{
		$id = trim($this->input['id']);
		
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		
		$ret = $this->mChannel->delete($id);
		
		if (!$ret)
		{
			$this->errorOutput('删除失败');
		}
		
		$this->addItem($id);
		$this->output();
	}
	
	public function audit()
	{
		$id = trim($this->input['id']);
		
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		$field = 'id, name, status';
		$channel_info = $this->mChannel->get_channel_by_id($id, 0, $field);
		
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		$status = $channel_info['status'];
		
		$ret = 0;
		
		$update_data = array(
			'id'	=> $id,
		);
		
		if (!$status) //启动
		{
			$update_data['status'] = 1;
			$ret = 1;
		}
		else	//停止
		{
			$update_data['status'] = 0;
			$ret = 2;
		}
		
		$this->mChannel->update($update_data);
		
		if ($ret)
		{
			//记录日志
			$pre_data = $channel_info;
			$up_data  = $channel_info;
			if ($ret == 1)
			{
				$up_data['status'] = 1;
			}
			else if ($ret == 2)
			{
				$up_data['status'] = 0;
			}
			
			$this->addLogs('频道接管状态' , $pre_data , $up_data , $channel_info['name'] ,$channel_info['id']);
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function sort()
	{
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order('channel', 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}
	
	public function publish(){}
	
	public function sys_live()
	{
		$channel_data = array(
			'is_sys' => 1,
		);
		
		$channel_info_sys = $this->mChannel->getChannelInfo($channel_data);
		if (empty($channel_info_sys))
		{
			$this->errorOutput('暂无频道数据同步');
		}
		
		$sql = "SELECT id, sys_id, order_id FROM " . DB_PREFIX . "channel WHERE is_sys = 1 ORDER BY id ASC ";
		$q = $this->db->query($sql);
		
		$channel = $channel_id = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel[$row['id']] = $row;
			$channel_id[] = $row['id'];
		}
		
		$channel_stream = array();
		if (!empty($channel_id))
		{
			$channel_id = implode(',', $channel_id);
			$sql = "SELECT id, channel_id, sys_id FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN (" . $channel_id . ") ORDER BY order_id ASC ";
			$q = $this->db->query($sql);
			
			while ($row = $this->db->fetch_array($q))
			{
				$channel_stream[$row['channel_id']][$row['sys_id']] = $row;
			}
		}
		
		$channel_info = array();
		foreach ($channel AS $v)
		{
			$v['channel_stream'] = $channel_stream[$v['id']];
			$channel_info[$v['sys_id']] = $v;
		}

		foreach ($channel_info_sys AS $v)
		{
			$data = array(
				'name'				=> $v['name'],
				'code'				=> $v['code'],
				'application_id'	=> $v['application_id'],
				'is_url'			=> 1,
				'is_mobile_phone'	=> $v['is_mobile_phone'],
				'is_audio'			=> $v['is_audio'],
				'is_control'		=> $v['is_control'],
				'time_shift'		=> $v['time_shift'],
				'server_id'			=> $v['server_id'],
				'node_id'			=> $v['node_id'],
				'org_id'			=> $this->user['org_id'],
				'user_id'			=> $this->user['user_id'],
				'user_name'			=> $this->user['user_name'],
				'appid'				=> $this->user['appid'],
				'appname'			=> $this->user['display_name'],
				'create_time'		=> TIMENOW,
				'update_time'		=> TIMENOW,
				'ip'				=> hg_getip(),
				'logo_rectangle' 	=> serialize($v['_logo_rectangle']),
				'client_logo'	 	=> serialize($v['_client_logo']),
				'is_sys'	 		=> 1,
				'sys_id'	 		=> $v['id'],
				'is_live'	 		=> $v['is_live'],
				'is_record'	 		=> $v['is_record'],
				'change_id'	 		=> $v['change_id'],
				'change_name'	 	=> $v['change_name'],
				'change_type'	 	=> $v['change_type'],
				'stream_id'	 		=> $v['stream_id'],
				'input_id'	 		=> $v['input_id'],
				'beibo'	 			=> $v['beibo'],
				'stream_count'	 	=> $v['stream_count'],
				'level'	 			=> $v['level'],
				'core_count'	 	=> $v['core_count'],
				'main_stream_name'	=> $v['main_stream_name'],
				'stream_name'		=> serialize($v['stream_name']),
				'drm'				=> $v['drm'],
			);
			
			if ($channel_info[$v['id']]['id'])
			{
				//update
				$data['id'] = $channel_info[$v['id']]['id'];
				
				$ret = $this->mChannel->update($data);
			}
			else
			{
				//create
				$ret = $this->mChannel->create($data);
			}
		
			if (!$ret['id'])
			{
				continue;
			}
			
			//同步channel_stream
			$main_stream_id = 0;
			foreach ($v['channel_stream'] AS $kk => $vv)
			{
				$stream_data = array(
					'channel_id'		=> intval($ret['id']),
					'stream_name'		=> trim($vv['stream_name']),
					'output_url'		=> trim($vv['output_url']),
					'output_url_rtmp'	=> trim($vv['output_url_rtmp']),
					'm3u8'				=> trim($vv['m3u8']),
					'is_main'			=> $vv['is_main'] ? 1 : 0,
					'order_id'			=> $kk,
					'sys_id'			=> $vv['id'],
					'input_id'			=> $vv['input_id'],
					'delay_id'			=> $vv['delay_id'],
					'change_id'			=> $vv['change_id'],
					'output_id'			=> $vv['output_id'],
					'bitrate'			=> $vv['bitrate'],
				);
				
				if ($channel_info[$v['id']]['channel_stream'][$vv['id']]['id'])
				{
					//update
					$stream_data['id'] = $channel_info[$v['id']]['channel_stream'][$vv['id']]['id'];

					$ret_stream = $this->mChannel->channel_stream_update($stream_data);
				}
				else 
				{
					//create
					$ret_stream = $this->mChannel->channel_stream_create($stream_data);
				}
				
				if ($ret_stream['id'] && $vv['is_main'])
				{
					$main_stream_id = $ret_stream['id'];
				}
			}
			
			$update_data = array(
				'id'				=> $ret['id'],
				'order_id'			=> $channel_info[$v['id']]['id'] ? $channel_info[$v['id']]['order_id'] : $ret['id'],
				'main_stream_id'	=> $main_stream_id,
			);
			$this->mChannel->update($update_data);
		}
		
		$this->addItem('success');
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
	
}
$out = new channelUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>