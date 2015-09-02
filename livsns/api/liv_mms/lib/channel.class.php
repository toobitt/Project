<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
*@public function channelsInfo|channelStreams
*
* $Id: channel.class.php 5753 2012-01-30 06:45:16Z lijiaying $
***************************************************************************/
class channels extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
		
	/**
	 * 所有频道信息
	 * @name channelsInfo
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $condition 检索条件
	 * @param $offset 分页参数
	 * @param $count 分页显示记录数
	 * @return $info array 所有频道内容信息
	 */
	public function channelsInfo($condition,$offset,$count)
	{
		$data_limit = " LIMIT " . $offset . " , " . $count;
		$sql = "SELECT c.*,s.*,s.id as s_id,c.id as c_id,c.ch_id as c_ch_id,c.save_time as c_save_time, c.live_delay as c_live_delay, s.ch_id as s_ch_id,s.save_time as s_save_time, s.live_delay as s_live_delay FROM " . DB_PREFIX . "channel c LEFT JOIN " . DB_PREFIX . "stream s on c.stream_id=s.id ";
		$sql .= " WHERE 1 " . $condition . " ORDER BY c.order_id DESC " . $data_limit;
		$q = $this->db->query($sql);
		
		$info = $channel_stream = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['id'] = $row['c_id'];
			$row['ch_id'] = $row['c_ch_id'];
			$row['save_time'] = $row['c_save_time'];
			$row['live_delay'] = $row['c_live_delay'];
			
			$row['logo_info'] = unserialize($row['logo_info']);
			
			if ($row['logo_info'])
			{
				$row['logo_url'] = hg_material_link($this->settings['material_server']['img4']['host'], $this->settings['material_server']['img4']['dir'], $row['logo_info']['filepath'], $row['logo_info']['filename']);
			}
			
			if(!$row['stream_state'])
			{
		//		$row['stream_state'] = "启动流输出";
				$row['stream_state_tag'] = 0;
			}
			else 
			{
		//		$row['stream_state'] = "停止流输出";
				$row['stream_state_tag'] = 1;
			}
			$row['beibo'] = unserialize($row['beibo']);

			$row['uri'] = unserialize($row['uri']);
			$row['stream_info_all'] = unserialize($row['stream_info_all']);
			$row['other_info'] = unserialize($row['other_info']);
			$channel_streams = array();
			if($row['other_info'])
			{
				foreach($row['other_info'] AS $k => $v)
				{
					$channel_streams[$k]['id'] = $v['id'];
					$channel_streams[$k]['name'] = $v['name'];
					$channel_streams[$k]['code'] = $row['code'];
					$channel_streams[$k]['bitrate'] = $v['bitrate'];
					$channel_streams[$k]['open_ts'] = $row['open_ts'];
					$channel_streams[$k]['stream_mark'] = $row['stream_mark'];
					$channel_streams[$k]['out_stream_name'] = $v['out_stream_name'];
				}
			}
			$channel_stream[$row['id']] = $channel_streams;
			$info[$row['id']] = $row;
		}
		if($info)
		{
			//基础流信息
			$stream = $this->channelStreams(array_keys($info));
			$stream_info = array();
			foreach ($stream AS $k => $v)
			{
				foreach ($channel_stream AS $kk => $vv)
				{
					if ($k == $kk)
					{
						if ($v['name'] == $vv['stream_name'])
						{
							for($i=0 ; $i < count($v) ; $i++)
							{
								$stream_info[$k]['streams'][$i]['id'] = $v[$i]['id'];
								$stream_info[$k]['streams'][$i]['name'] = $v[$i]['stream_name'];
								$stream_info[$k]['streams'][$i]['out_stream_name'] = $v[$i]['out_stream_name'];
								$stream_info[$k]['streams'][$i]['uri'] = hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $vv[$i]['code'], 'stream_name' => $v[$i]['out_stream_name']));
								$stream_info[$k]['streams'][$i]['stream_uri'] = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $vv[$i]['stream_mark'], 'stream_name' => $v[$i]['stream_name']));
								if ($vv[$i]['open_ts'])
								{
									$stream_info[$k]['streams'][$i]['m3u8'] = hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $vv[$i]['code'], 'stream_name' => $v[$i]['out_stream_name']), 'channels', 'http://', 'm3u8:');
								}
								$stream_info[$k]['streams'][$i]['is_main'] = $v[$i]['is_main'];
								$stream_info[$k]['streams'][$i]['bitrate'] = $v[$i]['bitrate'];
							}
						}
					}
				}
			}
		}
		//频道内容信息
		$channel_info = array();
		foreach ($info AS $k => $v)
		{
			foreach ($stream_info AS $kk => $vv)
			{
				if ($k == $kk)
				{
					$channel_info[$k] = array_merge($v,$vv);
				}
			}
		}
		return $channel_info;
	}
	/**
	 * 频道流信息
	 * @name channelStreams
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_ids array 所有频道ID
	 * @return $return array 所有频道流信息
	 */
	public function channelStreams($channel_ids)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN(" . implode(',', $channel_ids) .")";
		$q = $this->db->query($sql);
		$return = array();
		while ($r = $this->db->fetch_array($q))
		{
			$return[$r['channel_id']][] = $r;
		}
		return $return;
	}
}
?>