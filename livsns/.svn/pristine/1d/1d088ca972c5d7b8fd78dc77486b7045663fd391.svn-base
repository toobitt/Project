<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
***************************************************************************/
require('global.php');
class liveApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function  emergency_change()
	{
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('请选择要切播的频道');
		}
		$stream_id = intval($this->input['stream_id']);
		$source = trim(urldecode($this->input['source']));
		$bkfile_id = intval($this->input['bkfile_id']);
		if (!$stream_id && !$source)
		{
			$this->errorOutput('请选择要切播到的流或备播文件');
		}

	
		$sql = "select main_stream_name,stream_id,live_delay,is_live,stream_state, chg_id, chg2_stream_id from " . DB_PREFIX . "channel where id=" . $channel_id;
		$channel_info = $this->db->query_first($sql);
		if(!$channel_info['stream_state'])
		{
			$this->errorOutput('频道尚未对外输出流');
		}
		if(!$channel_info['is_live'])
		{
			$this->errorOutput('该频道不支持播出控制');
		}

		$sql = "select id, ch_name,other_info,s_status from " . DB_PREFIX . "stream where id IN (" . $channel_info['stream_id'] . ',' . $stream_id . ')';
		$q = $this->db->query($sql);
		$stream_info = array();
		while($r = $this->db->fetch_array($q))
		{
			if (!$r['s_status'])
			{
				continue;
			}
			$r['other_info'] = unserialize($r['other_info']);
			$stream_info[$r['id']] = $r;
		}
		$live_stream_info = $stream_info[$channel_info['stream_id']]['other_info'];
		if (!$live_stream_info)
		{
			$this->errorOutput('频道尚未对外输出流');
		}
		$chg_type = 'stream';
		$beibo_stream_info = $stream_info[$stream_id]['other_info'];
		if (!$beibo_stream_info)
		{
			if (!$source)
			{
				$this->errorOutput('切播信号未启动，无法切播');
			}
			else
			{
				$chg_type = 'file';
			}
		}
		//与主信号流一致，回到直播
		if ($stream_id == $channel_info['stream_id'])
		{
			include(CUR_CONF_PATH . 'lib/channel_change.class.php');
			$channel_changes = new ChannelChange();
			$ret = $channel_changes->channel_resume($channel_info['chg_id']);
			if ($ret != 1)
			{
				$msg = array(
					-1 => '无法连接切播服务',	
					0 => '切播失败',	
				);
				$this->errorOutput($msg[$ret_name]);
			}
			$sql = "UPDATE " . DB_PREFIX . "channel SET chg2_stream_id=0 WHERE id=" . $channel_id;
			$this->db->query($sql);
			$ret = array(
				'msg' => $ret_name,
				'stream_id' => $stream_id,
				'chg_type' => $chg_type,
			);
			$this->addItem($ret);
			$this->output();
		}

		if ($chg_type == 'stream' && $stream_id == $channel_info['chg2_stream_id'])
		{
			$ret = array(
				'msg' => $ret_name,
				'stream_id' => $stream_id,
				'chg_type' => $chg_type,
			);
			$this->addItem($ret);
			$this->output();
		}
	
		if($channel_info['chg2_stream_id'] && $stream_id != $channel_info['chg2_stream_id'])
		{
			$this->errorOutput('频道正在切播中, 请先回到直播');
		}
		
		//匹配码流一致

		$stream_to = array();
		foreach($live_stream_info as $key => $value)
		{
			if ($value['name'] != $channel_info['main_stream_name'])
			{
				continue;
			}
			$stream_to[$value['name']] = $source;
			if ($beibo_stream_info)
			{
				foreach($beibo_stream_info as $k => $v)
				{
					if(abs($value['bitrate'] - $v['bitrate']) < 200)//流的码率相差在200以内，认为是码率一致，可以切播
					{
						$stream_to[$value['name']] =  hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $stream_info[$stream_id]['ch_name'], 'stream_name' => $v['name']));
						break;
					}
				}
			}
		}
		if(0 && $channel_info['live_delay'])
		{
			//有延时就记录数据
			$stream_uri = array();
			foreach($stream_name_beibo as $key => $value)
			{
				$stream_uri[] = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $beibo['ch_name'], 'stream_name' => $value), 'channels', 'tvie://');
			}
			
			$change_time = TIMENOW + $channel_info['live_delay']*60;
			include(CUR_CONF_PATH . 'lib/channel_change_plan.class.php');
			$channel_plan = new ChannelChangePlan();
			$ret = $channel_plan->change_plan_create($channel_id, $stream_id, $stream_name_down, $stream_uri, $channel_info['stream_state'], $change_time, '', '');
		}
		else 
		{
			if (!$stream_to[$channel_info['main_stream_name']])
			{
				$this->errorOutput('未找到匹配的流');
			}
			include(CUR_CONF_PATH . 'lib/channel_change.class.php');
			$channel_changes = new ChannelChange();

			$ret_name = $channel_changes->channel_emergency_change($channel_info['chg_id'], 'file', $stream_to[$channel_info['main_stream_name']]);
			if ($ret_name != 1)
			{
				$msg = array(
					-1 => '无法连接切播服务',	
					0 => '切播失败',	
				);
				$this->errorOutput($msg[$ret_name]);
			}
			$sql = "UPDATE " . DB_PREFIX . "channel SET chg2_stream_id=" . ($stream_id ? $stream_id : $bkfile_id) . ", chg_type='{$chg_type}' WHERE id=" . $channel_id;
			$this->db->query($sql);
		}
		$ret = array(
			'msg' => $ret_name,
			'stream_id' => $stream_id,
			'chg_type' => $chg_type,
		);
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function  resume()
	{
		$channel_id = $this->input['channel_id'] ? intval($this->input['channel_id']) : 0;
		$sql = "select main_stream_name,stream_id,live_delay,stream_state, chg_id, chg2_stream_id from " . DB_PREFIX . "channel where id=" . $channel_id;
		$channel_info = $this->db->query_first($sql);
		if(!$channel_info)
		{
			$this->errorOutput('频道不存在');
		}
		if(!$channel_info['stream_state'])
		{
			$this->errorOutput('频道尚未对外输出流');
		}
		if(!$channel_info['chg2_stream_id'])
		{
			$this->errorOutput('频道正在直播中');
		}
		if(false)
		{
			return;		//记录数据
		}
		else 
		{
			include(CUR_CONF_PATH . 'lib/channel_change.class.php');
			$channel_change = new ChannelChange();
			$ret = $channel_changes->channel_resume($channel_info['chg_id']);
			if ($ret != 1)
			{
				$msg = array(
					-1 => '无法连接切播服务',	
					0 => '切播失败',	
				);
				$this->errorOutput($msg[$ret_name]);
			}
			$sql = "UPDATE " . DB_PREFIX . "channel SET chg2_stream_id=0 WHERE id=" . $channel_id;
			$this->db->query($sql);
			$ret = array(
				'msg' => $ret_name,
				'stream_id' => $stream_id,
				'chg_type' => $chg_type,
			);
			$this->addItem($ret);
			$this->output();
		}
	}

	public function  status()
	{
		$channel_id = $this->input['channel_id'];
		if (!$channel_id)
		{
			return;
		}
	
		$sql = "select main_stream_name,stream_id,live_delay,stream_state, chg_id, chg2_stream_id, chg_type from " . DB_PREFIX . "channel where id=" . $channel_id;
		$channel_info = $this->db->query_first($sql);
		if (!$channel_info)
		{
			return;
		}

		include(CUR_CONF_PATH . 'lib/channel_change.class.php');
		$channel_change = new ChannelChange();
	
		$ret = $channel_change->channel_status($channel_info['chg_id']);
		if ($channel_info['chg2_stream_id'] && $ret == 'normal')
		{
			$sql = "UPDATE " . DB_PREFIX . "channel SET chg2_stream_id=0 WHERE id=" . $channel_id;
			$this->db->query($sql);
		}
		if($channel_info['chg_type'] == 'file')
		{
			$channel_info['chg2_stream_id'] = 0;
		}
		$channel_info['chg_status'] = $ret;
		$this->addItem($channel_info);
		$this->output();
		
	}
}
$out = new liveApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'emergency_change';
}
$out->$action();
?>