<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 7416 2012-06-30 04:57:21Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','old_live');
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class channelApi extends outerReadBase
{
	private $mLive;
	function __construct()
	{
		$this->mNeedCheckIn = false;
		parent::__construct();

		$this->mLive = $this->settings['wowza']['live_output_server'];
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function channels()
	{
		$output_fields = explode(',', $this->input['show']);
		$suffix = 'live.m3u8';
		
		//$cond = ' AND status=1';
		if ($this->input['source'])
		{
			$cond .= ' AND t1.open_ts=1';
			if ($this->input['source'] == 2)
			{
				$suffix = 'live.mp4';
			}
		}
		if(isset($this->input['audio_only']))
		{
			$cond .= ' AND t1.audio_only=' . intval($this->input['audio_only']);
		}
		$channel_id = intval($this->input['channel_id']);
		if($channel_id)
		{
			$cond .= ' AND t1.id=' . $channel_id;
		}
		//直播分类
		$node_id = intval($this->input['node_id']);
		if ($node_id)
		{
			$cond .= ' AND t1.node_id=' . intval($this->input['node_id']);
		}
		
		$count = intval($this->input['count']);
		$count = $count ? $count : 5;
		$offset = intval($this->input['offset']);
		$sql = "SELECT t1.*, t2.core_in_host, t2.core_out_port, 
					t2.is_dvr_output, t2.dvr_in_host, t2.dvr_out_port, 
					t2.is_live_output, t2.live_in_host, t2.live_out_port 
				FROM " . DB_PREFIX . "channel t1";
		$sql.= " LEFT JOIN " . DB_PREFIX . "server_config t2 ON t2.id = t1.server_id ";
		
		$sql.= " WHERE t1.stream_state=1 {$cond} ORDER BY t1.order_id DESC LIMIT $offset, $count";

		$q = $this->db->query($sql);
		$channel_info = array();
		$imgsize = $this->input['imgsize'] ? $this->input['imgsize'] : '450x341';
		while ($r = $this->db->fetch_array($q))
		{
			
			if ($r['logo_info'])
			{
				$r['logo_info'] = unserialize($r['logo_info']);
				$r['logo']['rectangle']['host'] = $r['logo_info']['host'];
				$r['logo']['rectangle']['dir'] = $r['logo_info']['dir'];
				$r['logo']['rectangle']['filepath'] = $r['logo_info']['filepath'];
				$r['logo']['rectangle']['filename'] = $r['logo_info']['filename'];
			}
			
			if ($r['logo_mobile_info'])
			{
				$r['logo_mobile_info'] = unserialize($r['logo_mobile_info']);
				$r['logo']['square']['host'] = $r['logo_mobile_info']['host'];
				$r['logo']['square']['dir'] = $r['logo_mobile_info']['dir'];
				$r['logo']['square']['filepath'] = $r['logo_mobile_info']['filepath'];
				$r['logo']['square']['filename'] = $r['logo_mobile_info']['filename'];
			}
			
			$r['snap'] = array(
				'host' => MMS_CONTROL_LIST_PREVIEWIMG_URL, 
				'dir' => '',
				'filepath' => date('Y') . '/' . date('m') . '/',
				'filename' => 'live_' . $r['id'] . '.png?time=' . TIMENOW
			);
			if ($r['audio_only'])
			{
				$r['snap'] = $r['logo']['square'];
			}
	
			$sql = "SELECT id,theme,start_time FROM ".DB_PREFIX.'program  WHERE channel_id ='.$r['id'].' ';
			$sql .= ' AND start_time + toff >= ' . TIMENOW . ' ORDER BY start_time ASC LIMIT 2';
			$programq = $this->db->query($sql);
			$program = array();
			while($row = $this->db->fetch_array($programq))
			{
				$row['program_name'] = $row['theme'];
				$program[$row['start_time']] = $row;
			}
			if (!$program)
			{
				$sql = "SELECT *,UNIX_TIMESTAMP( CONCAT('" . date("Y-m-d", TIMENOW) . "', FROM_UNIXTIME(p.start_time + p.toff,  ' %H:%i'))) AS stime FROM " . DB_PREFIX . "program_plan p LEFT JOIN " . DB_PREFIX . "program_plan_relation r ON r.plan_id=p.id WHERE p.channel_id=" . $r['id'] . " AND r.week_num=" . date("N", TIMENOW) . " AND  UNIX_TIMESTAMP( CONCAT('" . date("Y-m-d", TIMENOW) . "', FROM_UNIXTIME(p.start_time + p.toff,  ' %H:%i'))) >= " . TIMENOW . " ORDER BY stime ASC LIMIT 2";
				$pq = $this->db->query($sql);
				while($row = $this->db->fetch_array($pq))
				{
					$program[$row['start_time']] = $row;
				}
			}
			ksort($program);
			$p = array(
				0 => array('start_time' => date("H:00",TIMENOW), 'program' => '精彩节目'),	
				1 => array('start_time' => date("H:00",TIMENOW + 3600), 'program' => '精彩节目'),	
			);
			$i = 0;
			foreach ($program AS $t => $theme)
			{
				$p[$i] = array('start_time' => date("H:i",$t), 'program' => $theme['program_name']);
				$i++;
			}
			if ($r['open_ts'])
			{
				$cur_start_time = TIMENOW - 10;
				if ($this->settings['signleliveaddr'])
				{
					$r['m3u8'] = $this->settings['signleliveaddr'] . $r['code'] . '.stream/playlist.m3u8';
				}
				else
				{
					if ($r['core_in_host'])
					{
						if ($r['is_dvr_output'])
						{
							$wowzaip = $r['dvr_in_host'] . ':' . $r['dvr_out_port'];
						}
						else 
						{
							$wowzaip = $r['core_in_host'] . ':' . $r['core_out_port'];
						}
					}
					else 
					{
						if ($this->settings['wowza']['dvr_output_server'])
						{
							$wowzaip = $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
						}
						else 
						{
							$wowzaip = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['out_port'];
						}
					}
					
					$dvr_suffix  = $this->settings['wowza']['dvr_output']['suffix'];
					//live
					if ($this->mLive)
					{
						if ($r['is_live_output'])
						{
							$_wowzaip = $r['live_in_host'] . ':' . $r['live_out_port'];
						}
						else 
						{
							$_wowzaip = $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
						}
						
						$live_suffix  = $this->settings['wowza']['live_output']['suffix'];
						
						$r['livem3u8'] = hg_streamUrl($_wowzaip, $r['code'], $r['main_stream_name'] . $_suffix, 'm3u8');//. '&starttime=' . (TIMENOW - 10);
					}
					
					$r['m3u8'] = hg_streamUrl($wowzaip, $r['code'], $r['main_stream_name'] . $dvr_suffix, 'm3u8');//. '&starttime=' . (TIMENOW - 10);
				}
			}
			$r['cur_program'] = $p[0];
			$r['next_program'] = $p[1];
			if ($output_fields)
			{
				$temp = array();
				foreach($output_fields as $v)
				{
					if(isset($r[$v]))
					{
						$temp[$v] = $r[$v];
					}
				}
			}
			else
			{
				$temp = $r;
			}
			$this->addItem($temp);
		}
		$this->output();
	}

	public function show()
	{
		$channel_id = $this->input['channel_id'];
		$channel_code = $this->input['channel_code'];
		if(!$channel_id && !$channel_code)
		{
			$this->errorOutput('未传入频道ID');
		}
		$suffix = 'live.m3u8';
		if ($this->input['source'])
		{
			$cond = " AND t1.open_ts=1 ";
			if ($this->input['source'] == 2)
			{
				$suffix = 'live.mp4';
			}
		}
		
		$sql = "SELECT t1.*, t2.core_in_host, t2.core_out_port, 
					t2.is_dvr_output, t2.dvr_in_host, t2.dvr_out_port, 
					t2.is_live_output, t2.live_in_host, t2.live_out_port 
				FROM " . DB_PREFIX . "channel t1";
		$sql.= " LEFT JOIN " . DB_PREFIX . "server_config t2 ON t2.id = t1.server_id ";
		
		if($channel_id == 'latest')
		{
			$sql.= " WHERE 1 " . $cond . " AND t1.stream_state ";
			$sql.= " ORDER BY t1.id DESC LIMIT 1";
		}
		else
		{
			if($channel_id)
			{
				$ucond = " t1.id=" . intval($channel_id);
			}
			else
			{
				$ucond = " t1.code='" . $channel_code . "'";
			}
			$sql.= " WHERE " . $ucond . $cond;
		}
		$channel_info = $this->db->query_first($sql);

		$channel_id = $channel_info['id'];
		$imgsize = $this->input['imgsize'] ? $this->input['imgsize'] : '450x341';
		$info = array();
		if(is_array($channel_info))
		{
			$info['id'] = $channel_info['id'];
			$info['channel']['name'] = $channel_info['name'];
			$info['channel']['drm'] = $channel_info['drm'];
			$info['channel']['logo'] = array();
			if ($channel_info['logo_info'])
			{
				$channel_info['logo_info'] = unserialize($channel_info['logo_info']);
				$info['channel']['logo']['rectangle']['host'] = $channel_info['logo_info']['host'];
				$info['channel']['logo']['rectangle']['dir'] = $channel_info['logo_info']['dir'];
				$info['channel']['logo']['rectangle']['filepath'] = $channel_info['logo_info']['filepath'];
				$info['channel']['logo']['rectangle']['filename'] = $channel_info['logo_info']['filename'];
				$info['channel']['logo']['rectangle']['url'] = $channel_info['logo_info']['url'];
			}
			
			if ($channel_info['logo_mobile_info'])
			{
				$channel_info['logo_mobile_info'] = unserialize($channel_info['logo_mobile_info']);
				$info['channel']['logo']['square']['host'] = $channel_info['logo_mobile_info']['host'];
				$info['channel']['logo']['square']['dir'] = $channel_info['logo_mobile_info']['dir'];
				$info['channel']['logo']['square']['filepath'] = $channel_info['logo_mobile_info']['filepath'];
				$info['channel']['logo']['square']['filename'] = $channel_info['logo_mobile_info']['filename'];
				$info['channel']['logo']['square']['url'] = $channel_info['logo_mobile_info']['url'];
			}
			//live
			$info['channel']['snap'] = array(
				'host' => MMS_CONTROL_LIST_PREVIEWIMG_URL, 
				'dir' => '',
				'filepath' => date('Y') . '/' . date('m') . '/',
				'filename' => 'live_' . $channel_info['id'] . '.png?time=' . TIMENOW
			);
			$info['channel']['audio_only'] = $channel_info['audio_only'];
			if ($channel_info['audio_only'])
			{
				$info['channel']['snap'] = $info['channel']['logo']['square'];
			}
			$sql = "SELECT id,theme,start_time FROM ".DB_PREFIX.'program  WHERE channel_id ='.$channel_id.' ';
			$sql .= ' AND start_time >= ' . TIMENOW . ' LIMIT 2';
			$programq = $this->db->query($sql);
			$program = array();
			while($r = $this->db->fetch_array($programq))
			{
				$program[$r['start_time']] = $r['theme'];
			}
			ksort($program);
			$p = array();
			foreach ($program AS $theme)
			{
				$p[] = $theme;
			}
			$info['channel']['cur_program'] = $p[0] ? $p[0] : '精彩节目';
			$info['channel']['next_program'] = $p[1] ? $p[1] : '精彩节目';
			//$info['ad'][] = array();
			
			$sql = "select * from " . DB_PREFIX . "channel_stream where channel_id=" . $channel_id;
			$main_stream = $this->db->query($sql);
			$streams = array();
			while($r = $this->db->fetch_array($main_stream))
			{
				/*
				if ($r['bitrate'])
				{
					$streams[$r['bitrate']] = array(
						'url' => hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $channel_info['code'], 'stream_name' => $r['out_stream_name'])),
						'bit' => $r['bitrate']
					);
				}
				else
				{
				*/
				if ($channel_info['core_in_host'])
				{
					if ($channel_info['is_dvr_output'])
					{
						$wowzaip = $channel_info['dvr_in_host'] . ':' . $channel_info['dvr_out_port'];
					}
					else 
					{
						$wowzaip = $channel_info['core_in_host'] . ':' . $channel_info['core_out_port'];
					}
				}
				else 
				{
					if ($this->settings['wowza']['dvr_output_server'])
					{
						$wowzaip = $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
					}
					else 
					{
						$wowzaip = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['out_port'];
					}
				}
				
				$dvr_suffix  = $this->settings['wowza']['dvr_output']['suffix'];
				//live
				if ($this->mLive)
				{
					if ($channel_info['is_live_output'])
					{
						$_wowzaip = $channel_info['live_in_host'] . ':' . $channel_info['live_out_port'];
					}
					else 
					{
						$_wowzaip = $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
					}
					
					$live_suffix  = $this->settings['wowza']['live_output']['suffix'];
				}
				
				$m3u8 = '';
				if ($channel_info['open_ts'])
				{
					//live
					if ($this->mLive)
					{
						$livem3u8 = hg_streamUrl($_wowzaip, $channel_info['code'], $r['out_stream_name'] . $live_suffix, 'm3u8', (TIMENOW - 30000) . '000', 'dvr');
					}
					
					$m3u8 = hg_streamUrl($wowzaip, $channel_info['code'], $r['out_stream_name'] . $dvr_suffix, 'm3u8', (TIMENOW - 30000) . '000', 'dvr');
				}
				//live
				if ($this->mLive)
				{
					$_liveurl = hg_streamUrl($_wowzaip, $channel_info['code'], $r['out_stream_name'] . $live_suffix, 'flv');
				}
				
				$_url = hg_streamUrl($wowzaip, $channel_info['code'], $r['out_stream_name'] . $dvr_suffix, 'flv');
				$streams[] = array(
					'live' => $_liveurl,
					'livem3u8' => $livem3u8,
					'url' => $_url,
					'm3u8' => $m3u8,
					'bit' => $r['bitrate']
				);
					
				/*
				}
				*/
			}

			if ($streams)
			{
				krsort($streams);
				foreach($streams as $key => $value)
				{
					$info['stream'][] = $value;
				}
			}
			if ($channel_info['tvieurl'])
			{
				$info['tviestream'][] = array(
					'live' => $channel_info['tvieurl'],
					'livem3u8' => '',
					'url' => $channel_info['tvieurl'],
					'm3u8' => '',
					'bit' => $channel_info['bitrate']
				);
			}
			
		}

		$this->addItem($info);
		$this->output();
	}
	
	function detail()
	{
		$channel_id = $this->input['channel_id'];
		$time_show = $this->input['time_show']?$this->input['time_show']:TIMENOW;
		if(!$channel_id)
		{
			$this->errorOutput('未传入频道ID');
		}
		$week = array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
		$sql = "select * from " . DB_PREFIX . "channel where id=" . $channel_id ;
		$f = $this->db->query_first($sql);
		$f['save_time'] = intval($f['save_time']) > 24 ? (intval($f['save_time']) - 24):intval($f['save_time']);

		$f['server_time'] = TIMENOW;
		
		$f['date_show'] = date("m月d日",$time_show);
		$f['week_show'] = $week[date(w,$time_show)];
		
		$this->addItem($f);
		$this->output();
	}
	
	function get_channel_info()
	{
		$field 		= $this->input['field'] ? urldecode($this->input['field']) : ' * ';
		$condition  = $this->get_condition();
		$offset 	= $this->input['offset'] ? $this->input['offset'] : 0;			
		$count 		= $this->input['count'] ? intval($this->input['count']) : 30;
		$limit 		= " LIMIT " . $offset . " , " . $count;
		
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "channel ";
		$sql.= " WHERE 1 " . $condition . " ORDER BY order_id DESC " . $limit;
		$q = $this->db->query($sql);
		
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			
			$row['logo_info'] = @unserialize($row['logo_info']);
			$row['column_id'] = @unserialize($row['column_id']);
			$row['column_url'] = @unserialize($row['column_url']);
			
			if ($row['logo_info'])
			{
				$row['logo_url'] = hg_material_link($row['logo_info']['host'], $row['logo_info']['dir'], $row['logo_info']['filepath'], $row['logo_info']['filename'],'112x43/');
			}
			
			$row['logo_mobile_info'] = @unserialize($row['logo_mobile_info']);
			
			if ($row['logo_mobile_info'])
			{
				$row['logo_mobile_url'] = hg_material_link($row['logo_mobile_info']['host'], $row['logo_mobile_info']['dir'], $row['logo_mobile_info']['filepath'], $row['logo_mobile_info']['filename'],'50x50/');
			}
			
			$row['beibo'] = @unserialize($row['beibo']);
			$row['stream_info_all'] = @unserialize($row['stream_info_all']);
			
			$this->addItem($row);
		}
		$this->output();
	}
	
	function get_channel_count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "channel WHERE 1" . $condition;
		$ret = $this->db->query_first($sql);
		$this->addItem($ret);
		$this->output();
	}
	
	private function get_condition()
	{
		$condition = '';
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND name like \'%'.urldecode($this->input['k']).'%\'';
		}
		
		if (isset($this->input['id']) && $this->input['id'])
		{
			$condition .= " AND id IN (" . $this->input['id'] . ")";
		}
		
		if (isset($this->input['code']) && $this->input['code'])
		{
			$condition .= " AND code = '" . trim(urldecode($this->input['code'])) . "' ";
		}
		return $condition;
	}
	
	public function count()
	{
		
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