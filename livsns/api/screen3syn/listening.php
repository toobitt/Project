<?php
set_time_limit(30);
define('ROOT_DIR', '../../');
define('SCRIPT_NAME', 'listening');
define('MOD_UNIQUEID','listening');
require(ROOT_DIR.'global.php');
class listening extends coreFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->user = $this->verifyToken();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$clock = 1;
		$identifier = $this->input['identifier'];
		if(!$identifier)
		{
			$this->errorOutput(INVALID_IDENTIFIER);
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'device_token WHERE identifier = "'.$identifier.'" AND is_change = 1';
		while(True)
		{
			if($clock >= 15)
			{
				break;
			}
			$new_media = $this->db->query_first($sql);
			if($new_media)
			{
				$new_media['qrcode'] = unserialize($new_media['qrcode']);
				//取发布类容同时更新内容状态
				if(intval($this->input['media_url']))
				{
					$url = $this->media2url($new_media);
					if(!$url)
					{
						$url = 'about:blank';
					}
					$new_media['url'] = $url;
				}
				$this->db->query('UPDATE '.DB_PREFIX.'device_token SET is_change = 0 WHERE identifier = "'.$identifier.'"');
				$this->addItem($new_media);
				$this->output();
				break;
			}
			sleep(SLEEP_TIME);
			$clock++;
		}
	}
	public function get_media()
	{
		$identifier = $this->input['identifier'];
		if(!$identifier)
		{
			$this->errorOutput(INVALID_IDENTIFIER);
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'device_token WHERE identifier = "'.$identifier.'"';
		$media = $this->db->query_first($sql);
		if(!$media)
		{
			$this->errorOutput(MEDIA_NOT_EXISTS);
		}
		$media['qrcode'] = unserialize($media['qrcode']);
		if($media['type'] == 'live')
		{
			$media['m3u8'] = $media['m3u8'] ? $media['m3u8'] . '&starttime=' . $media['timestamp'] : '';
			$media['day'] = $media['start_time'] ? intval((TIMENOW-$media['media']['start_time'])/(3600*24)) : 0;
		}
		if(intval($this->input['media_url']))
		{
			$media['url'] = $this->media2url($media);
		}
		$this->addItem($media);
		$this->output();
	}
	protected function media2url($media = array())
	{
		$content = '';
		if(!$media['media_id'])
		{
			return $content;
		}
		$media_id = $media['media_id'];
		switch ($media['type'])
		{
			case 'vod'://点播
				{
					require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
					$publish_content = new publishcontent();
					$content = $publish_content->get_content(array('content_id'=>$media_id));
					$content = $content[0]['content_url'];
					break;
				}
			case 'live'://直播
				{
					$content = $this->get_channel_url($media_id);
					break;
				}
		}
		return $content;
	}
	public function test()
	{
		$data = array(
		'identifier'	=>	'159638',
		'type'			=>	2,
		'id'		=>	5,
		'update_time'	=>	TIMENOW,
		'is_change'		=>	1,
		);
		echo $this->media2url($data);
	}
	public function get_channel_url($channel_id = 0)
	{
		if(!$channel_id)
		{
			return '';
		}
		$url = $this->input['liveurl'];
		$url = $url ? $url : CHANNEL_WEBURL;
		return $url . $channel_id;
	}
}
include ROOT_PATH . 'excute.php';
?>