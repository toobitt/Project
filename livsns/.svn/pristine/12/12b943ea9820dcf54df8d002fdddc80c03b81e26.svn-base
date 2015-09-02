<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: vod_record.php 7218 2012-06-16 03:07:16Z develop_tong $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','vod');
class vodRecordApi extends adminBase
{
	function __construct()
	{
		parent::__construct();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('请指定视频id');
		}
		$condition = " WHERE v.id = {$id}";
		//source 频道ID
		$sql ="SELECT v.id,v.starttime,v.source,v.duration,v.channel_id,c.code,c.save_time,c.main_stream_name,c.record_time,c.live_delay FROM " . DB_PREFIX . "vodinfo v LEFT JOIN " . DB_PREFIX . "channel c ON v.channel_id=c.id " . $condition;
		$row = $this->db->query_first($sql);
		if(!$row)
		{
			$this->errorOutput('指定视频不存在');
		}
		if (TIMENOW - $row['starttime'] > $row['save_time'] * 3600)
		{
			$this->errorOutput('时移已经不存在');
		}
		$ret = array();
		$ret['stream'] = hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $row['code'], 'stream_name' => $row['main_stream_name']), 'internal');
		$ret['starttime'] =  $row['starttime']+$row['record_time'];
		$ret['endtime'] = $ret['starttime'] + intval($row['duration'] / 1000);
		$ret['save_time'] = $row['save_time'];
		$ret['delay_time'] = $row['live_delay'] * 60;
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		
		foreach($ret as $key => $value)
		{
			$this->curl->addRequestData($key, $value);
		}
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('channel_id', $row['channel_id']);
		$info = $this->curl->request('record.php');
		$this->addItem($info);
		$this->output();
	}
		
	/**
	 * 获取单条信息
	 */
	public function detail()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$condition = " ORDER BY id DESC LIMIT 1";
		}
		else 
		{
			$condition = " WHERE id IN(" . $id . ")";
		}
		//source 频道ID
		$sql ="SELECT id,starttime,delay_time,source,duration,create_time,update_time FROM " . DB_PREFIX . "vodinfo " . $condition;
		$row = $this->db->query_first($sql);
		$this->setXmlNode('vodinfo', 'info');
		
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['endtime'] = date('Y-m-d H:i:s' , ($row['starttime'] + $row['duration']));
			$row['starttime'] = date('Y-m-d H:i:s' , $row['starttime']);
			$this->addItem($row);
			$this->output();
		}
		else 
		{
			$this->errorOutput('录播视频不存在');
		}
	}
	
	public function create()
	{
		$ret = array();
		$ret['channel_id'] = $this->input['channel_id'];
		if(!$ret['channel_id'])
		{
			$this->errorOutput('未传入频道ID');
		}	
		//请求频道信息
		include_once(ROOT_PATH . 'lib/program.class.php');
		$program = new program();
		$channel = $program->getTimeshift($ret['channel_id']);
//		$live_channel = new curl($this->settings['App_live']['host'],$this->settings['App_live']['dir']);
//		$live_channel->setSubmitType('post');
//		$live_channel->setReturnFormat('json');
//		$live_channel->initPostData();
//		$live_channel->addRequestData('id',$ret['channel_id']);
//		$channel_ret = $live_channel->request('admin/channel.php');
//		$channel = $channel_ret[0];
		if(!$channel)
		{
			$this->errorOutput('该频道已经不存在！');
		}
		if(!$channel['open_ts'])
		{
			$this->errorOutput('该频道未启动手机流，无法获取时移数据！');
		}
		
		$channel_exists = 1;
		//视频信息入库		
		$ret['title'] = urldecode($this->input['title'])?urldecode($this->input['title']):'精彩节目';
		$ret['starttime'] = strtotime(trim(urldecode($this->input['start_time'])));
		$ret['endtime'] = strtotime(trim(urldecode($this->input['end_time'])));
		$ret['duration'] = ($ret['endtime'] - $ret['starttime']);
		if($ret['starttime'] >= $ret['endtime'])
		{
			$this->errorOutput('时间设置不正确！');
		}
		$save_time = TIMENOW-(($channel['time_shift']*3600)-($channel['delay']));
		
		if($ret['starttime'] < $save_time)
		{
			$this->errorOutput('此条录制已超过回看时间！');
		}

		if($ret['endtime'] > TIMENOW)
		{
			$this->errorOutput('录制节目的结束时间必须小于当前时间！');
		}
		$data = array(
			'title'			=> $ret['title'],
			'user_id'  	 	=> $this->user['user_id'],
			'addperson' 	=> $this->user['user_name'],
			'subtitle'  	=> $this->input['subtitle'],
			'author'    	=> $this->input['author'],
			'keywords'  	=> $this->input['keywords'], 
			'vod_sort_id'   => $this->input['item'],
			'delay_time'    => $channel['delay_time'],
			'starttime'     => $ret['starttime'],
			'channel_id'     => $ret['channel_id'],
			'duration'      => $ret['duration'],
			'column_id'     => $this->input['column_id'],
			'comment'       => $this->input['comment'],
			'create_time'       => $ret['starttime'],
			'update_time'       => $ret['starttime'],
 		);
 		$sql = 'INSERT INTO ' . DB_PREFIX .'vodinfo SET ';
 		$space = '';
 		foreach ( $data as $key => $value ) 
 		{
       		$sql .= $space . $key .'="'.$value.'"';
       		$space = ',';
		}
		$this->db->query($sql);
		$insert_id = $this->db->insert_id();
		$sql = "UPDATE " . DB_PREFIX ."vodinfo SET video_order_id = " . $insert_id ." WHERE id = " . $insert_id;
		$this->db->query($sql);
		
		//请求转码服务器路径配置
		$record_config = new curl($this->settings['App_live']['host'],$this->settings['App_live']['dir']);
		$record_config -> setSubmitType('post');
		$record_config -> setReturnFormat('json');
		$record_config -> initPostData();
		$record_config -> addRequestData('open_ts',1);
		$record_config = $record_config->request('admin/record_server_config.php');
		$record_config = $record_config[0]['mms']['record_server'];
		$this->curl = new curl($record_config['host'], $record_config['dir']);
		//向转码服务器提交数据
		$duration = $ret['duration'] .'000';
		$starttime = $ret['starttime'] . '000';
		$url = $channel['streams'][0]['m3u8_dvr'] . '?dvr&duration='.$duration . '&starttime='.$starttime;
		$callback = $this->settings['App_mediaserver']['protocol'] . $this->settings['App_mediaserver']['host'] .'/'. $this->settings['App_mediaserver']['dir'] . 'admin/record_callback.php';
		$data = array();
		$data = array(
			'id'     			 => $insert_id,
			'time_shift'         => 1,
			'video_order_id' 	 => $insert_id,
			'action'			 => 'TIMESHIFT',
			'url'   	 		 => urlencode($url),
			'callback' 			 => urlencode($callback),
			'uploadFile'		 => 0,
			'title'  			 => $ret['title'],
			'program'			 => $ret['title'],
			'appid'				 => $this->user['appid'],
			'appkey'			 => $this->input['appkey'],
			'access_token'		 => $this->user['token'],
			'vod_sort_id'   	 => $this->input['item'],
			'comment'       	 => $this->input['comment'],
		);
		foreach($data AS $k => $v)
		{
			if( empty($v) && $v != 0)
			{
				continue;
			}
			$this->curl->addRequestData($k,$v);
		}
		$record_xml = $this->curl->request('');	
		
		//返回视频信息
		$sql = "SELECT id,name as sort_name FROM " . DB_PREFIX . "vod_media_node WHERE id=".$this->input['item'];
		$sort = $this->db->query_first($sql);
		$return = array(
			'id' 				=> $insert_id,
			'row_id' 			=> $insert_id,
			'video_order_id' 	=> $insert_id,
			'title' 			=> $ret['title'],
			'bitrate' 			=> 0,
			'vod_sort_id' 		=> $sort['sort_name'],
			'status' 			=> '转码中',
			'bitrate' 			=> 0,
			'author' 			=> '',
			'addperson' 		=> 'MCP网页版',
			'comment' 			=> '',
			'starttime' 		=> '('.date('Y-m-d',$ret['starttime']).')',
			'delay_time' 		=> $ret['delay_time'],
			'copyright' 		=> '',
			'subtitle' 			=> '',
			'height' 			=> 0,
			'start'			    => 0,
			'duration' 			=> time_format(intval($ret['endtime'])-intval($ret['starttime'])),
			'width' 			=> 0,
			'keywords' 			=> $this->input['keywords'],
			'type' 				=> '',
			'transize' 			=> '',
			'totalsize' 		=> '',
			'audit' 			=> '',
			'flag' 				=> '',
			'collects' 			=> '',
			'create_time' 		=> date("Y-m-d H:i:s",TIMENOW),
			'update_time' 		=> date("Y-m-d H:i:s",TIMENOW),
			'ip' 				=> hg_getip(),
			'original_id' 		=> 0,
			'mark_count' 		=> 0,
			'mark_etime' 		=> 0,
			'isfile' 			=> 0,
			'audio' 			=> '',
			'audio_channels' 	=> '',
			'sampling_rate' 	=> '',
			'video' 			=> '',
			'frame_rate' 		=> '',
			'aspect' 			=> '',
			'trans_use_time' 	=> '',
			'sort_name' 		=> $sort['sort_name'],
			'vod_sort_color' 	=> $sort['color'],
			'bitrate_color' 	=> '#2f4974',
			'vodurl' 			=> '',
			'etime' 			=> intval($ret['duration']) + intval($ret['start']),	
			'is_go' 			=> $this->input['goon'],
		);
		$this->addItem($return);
		$this->output();	
	}
	
	function channel_show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;	
		//请求频道信息
		$live_channel = new curl($this->settings['App_live']['host'],$this->settings['App_live']['dir']);
		$live_channel->setSubmitType('post');
		$live_channel->setReturnFormat('json');
		$live_channel->initPostData();
		$live_channel->addRequestData('offset', $offset);
		$live_channel->addRequestData('count', $count);
		$channel_ret = $live_channel->request('channel.php');
		unset($channel_ret['server_info']);
		$return['data'] = $channel_ret;
		$return['offset'] = $offset;
		$return['plan_count'] = $count;
		$return['count'] = count($channel_ret);
		$this->addItem($return);
		$this->output();		
	}
}

$out = new vodRecordApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>