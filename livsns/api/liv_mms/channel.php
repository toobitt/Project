<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 9134 2012-08-09 08:42:43Z repheal $
***************************************************************************/
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class channelApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	/*	$this->config = array(
			1 => 'http://vod.hoolo.tv/073/973/823/012/73973823012.ssm/73973823012.m3u8',
			2 => 'http://vod.hoolo.tv/011/450/900/509/11450900509.ssm/11450900509.m3u8',
			3 => 'http://vod.hoolo.tv/040/327/582/554/40327582554.ssm/40327582554.m3u8',
			4 => 'http://vod.hoolo.tv/073/973/823/012/73973823012.ssm/73973823012.m3u8',
			5 => 'http://vod.hoolo.tv/011/450/900/509/11450900509.ssm/11450900509.m3u8',
			6 => 'http://vod.hoolo.tv/040/327/582/554/40327582554.ssm/40327582554.m3u8',
			7 => 'http://vod.hoolo.tv/073/973/823/012/73973823012.ssm/73973823012.m3u8',
			11 => 'http://vod.hoolo.tv/011/450/900/509/11450900509.ssm/11450900509.m3u8',
			13 => 'http://vod.hoolo.tv/040/327/582/554/40327582554.ssm/40327582554.m3u8',
			14 => 'http://vod.hoolo.tv/073/973/823/012/73973823012.ssm/73973823012.m3u8',
		);*/
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
			$cond .= ' AND open_ts=1';
			if ($this->input['source'] == 2)
			{
				$suffix = 'live.mp4';
			}
		}
		switch($this->input['audio_only'])
		{
			case 1://视频
				$cond .= ' AND audio_only=0';
				break;
			case 2://音频
				$cond .= ' AND audio_only=1';
				break;
			default://所有
				break;
		}
		$channel_id = intval($this->input['channel_id']);
		if($channel_id)
		{
			$cond .= ' AND id=' . $channel_id;
		}
		$count = intval($this->input['count']);
		$count = $count ? $count : 5;
		$offset = intval($this->input['offset']);
		$sql = "select * from " . DB_PREFIX . "channel where stream_state=1{$cond} ORDER BY order_id DESC LIMIT $offset, $count";
		$q = $this->db->query($sql);
		$channel_info = array();
		$imgsize = $this->input['imgsize'] ? $this->input['imgsize'] : '450x341';
		while ($r = $this->db->fetch_array($q))
		{
			$logo = hg_get_images($r['logo'], UPLOAD_URL . CHANNEL_IMG_DIR, $this->settings['channel_img_size']);
			$r['logo'] = $logo['img'];
			$r['snap'] =  MMS_CONTROL_LIST_PREVIEWIMG_URL.$r['ch_id'].'/'.$r['main_stream_name'].'/'.(TIMENOW*1000).'/' . $imgsize . '.png';
			$r['m3u8'] = isset($this->input['_config']) ? $this->config[$r['id']] : hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $r['code'], 'stream_name' => $r['main_stream_name']) , 'channels', 'http://', 'm3u8:');
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
				$sql = "SELECT *,UNIX_TIMESTAMP( CONCAT('" . date("Y-m-d", TIMENOW) . "', FROM_UNIXTIME(p.start_time + p.toff,  ' %h:%i'))) AS stime FROM " . DB_PREFIX . "program_plan p LEFT JOIN " . DB_PREFIX . "program_plan_relation r ON r.plan_id=p.id WHERE p.channel_id=" . $r['id'] . " AND r.week_num=" . date("N", TIMENOW) . " AND  UNIX_TIMESTAMP( CONCAT('" . date("Y-m-d", TIMENOW) . "', FROM_UNIXTIME(p.start_time + p.toff,  ' %H:%i'))) >= " . TIMENOW . " ORDER BY stime ASC LIMIT 2";
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
		if(!$channel_id)
		{
			$this->errorOutput('未传入频道ID');
		}
		$suffix = 'live.m3u8';
		if ($this->input['source'])
		{
			$cond = ' AND open_ts=1';
			if ($this->input['source'] == 2)
			{
				$suffix = 'live.mp4';
			}
		}
		$sql = "select * from " . DB_PREFIX . "channel where id=" . $channel_id . $cond;
		$channel_info = $this->db->query_first($sql);
		$imgsize = $this->input['imgsize'] ? $this->input['imgsize'] : '450x341';
		$info = array();
		if(is_array($channel_info))
		{
			$info['id'] = $channel_info['id'];
			$info['channel']['name'] = $channel_info['name'];
			$info['channel']['drm'] = $channel_info['drm'];
			$logo = hg_get_images($channel_info['logo'], UPLOAD_URL . CHANNEL_IMG_DIR, $this->settings['channel_img_size']);
			$info['channel']['logo'] = $logo['img'];
			$info['channel']['snap'] =  MMS_CONTROL_LIST_PREVIEWIMG_URL.$channel_info['ch_id'].'/'.$channel_info['main_stream_name'].'/'.(TIMENOW*1000).'/' . $imgsize . '.png';
			$info['channel']['m3u8'] =  isset($this->input['_config']) ? $this->config[$info['id']] : hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $channel_info['code'], 'stream_name' => $channel_info['main_stream_name']) , 'channels', 'http://', 'm3u8:');
			
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
					$streams[] = array(
						'url' => hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $channel_info['code'], 'stream_name' => $r['out_stream_name'])),
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
}

$out = new channelApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>