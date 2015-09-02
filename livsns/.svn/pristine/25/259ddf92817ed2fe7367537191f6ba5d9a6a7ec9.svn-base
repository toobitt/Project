<?php
define('ROOT_DIR', '../../');
define('SCRIPT_NAME', 'identify');
define('MOD_UNIQUEID','screen3syn');
require(ROOT_DIR.'global.php');
class identify extends coreFrm
{
	protected $user;
	public function __construct()
	{
		parent::__construct();
		$this->user = $this->verifyToken();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	protected function delete_expired_identifier()
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'device_token WHERE '.TIMENOW.'- update_time > '.EXPIRED_IDENTIFIER;
		$this->db->query($sql);
	}
	public function show()
	{
		for($i = 1; $i <= 6; $i++)
		{
			$identifier .= rand(0,9);
		}
		$sql = 'SELECT identifier FROM '.DB_PREFIX.'device_token WHERE identifier = "'.$identifier.'"';
		$check = $this->db->query_first($sql);
		if($check)
		{
			$this->show();
		}
		$url = $this->input['weburl'];
		$url = $url ? $url : WWW_API_URL;
		$qrcode_data = 'url:' . $url . '?identifier=' .$identifier;
		if(!$qrcode = $this->build_qrcode($qrcode_data))
		{
			$this->errorOutput(BUILD_QRCODE_ERROR);
		}
		$url = $qrcode['host'] . '/' . $qrcode['dir'] . $qrcode['filepath'] . $qrcode['filename'];
		$media_data = array(
		'identifier'	=>	$identifier,
		'type'			=>	$this->input['type'],
		'timestamp'		=>	$this->input['timestamp'],
		'media_id'		=>	$this->input['media_id'],
		'update_time'	=>	TIMENOW,
		'is_change'		=>	$this->input['media_id'] && $this->input['type'] ? 1 : 0,
		'status'		=>	0,
		'qrcode'		=> 	addslashes(serialize($qrcode)),
		'm3u8'			=> '',
		);
		if($media_data['type'] == 'live' && $this->input['m3u8'])
		{
			$m3u8 = $this->get_m3u8($media_data['media_id']);
			if(!$m3u8)
			{
				$this->errorOutput(GET_M3U8_ERROR);
			}
			$media_data['m3u8'] = $m3u8;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'device_token VALUES("'.implode('","', $media_data).'")';
		$this->db->query($sql);
		$this->delete_expired_identifier();
		$this->addItem(array('identifier'=>$identifier, 'src'=>$url));
		$this->output();
	}
	public function qrcode()
	{
		$identifier = $this->input['identifier'];
		if(!$identifier)
		{
			$this->show();
		}
		$this->syn_media();
	}
	protected function build_qrcode($qrcode_data = '')
	{
		if(!$qrcode_data)
		{
			return false;
		}
		if(!class_exists('curl'))
		{
			include_once ROOT_PATH . './lib/curl.class.php';
		}
		$curl = new curl($this->settings['App_qrcode']['host'], $this->settings['App_qrcode']['dir']);
		$curl->initPostData();
		$curl->addRequestData('data', $qrcode_data);
		$ret = $curl->request('qrcode.php');
		$qrcode = $ret[0];
		return $qrcode;
	}
	public function syn_media()
	{
		$media_data = array(
		'identifier'	=>	$this->input['identifier'],
		'type'			=>	$this->input['type'],
		'timestamp'		=>	$this->input['timestamp'],
		'media_id'		=>	$this->input['media_id'],
		'update_time'	=>	TIMENOW,
		'is_change'		=>	1,
		);
		if(!$media_data['identifier'])
		{
			$this->errorOutput(INVALID_DEVICE);
		}
		$sql = 'SELECT identifier,m3u8,qrcode FROM '.DB_PREFIX.'device_token WHERE identifier="'.$media_data['identifier'].'"';
		$exists = $this->db->query_first($sql);
		if(!$exists['identifier'])
		{
			$this->errorOutput(INVALID_IDENTIFIER);
		}
		if($media_data['type'] == 'live' && $this->input['m3u8'])
		{
			$m3u8 = $exists['m3u8'];
			if(!$m3u8)
			{
				$m3u8 = $this->get_m3u8($media_data['media_id']);
				if(!$m3u8)
				{
					$this->errorOutput(GET_M3U8_ERROR);
				}
			}
			$media_data['m3u8'] = $m3u8;
		}
		$sql = 'UPDATE '.DB_PREFIX.'device_token SET ';
		foreach ($media_data as $k=>$v)
		{
			$sql .= " {$k} = \"{$v}\",";
		}
		$sql = trim($sql, ',') . ' WHERE identifier = "'.$media_data['identifier'].'"';
		$this->db->query($sql);
		$qrcode = unserialize($exists['qrcode']);
		$media_data['src'] = $qrcode['host'] . '/' . $qrcode['dir'] . $qrcode['filepath'] . $qrcode['filename'];
		$this->addItem($media_data);
		$this->output();
	}
	public  function get_m3u8($channel_id = 0)
	{
		if(!$channel_id)
		{
			return false;
		}
		if(!class_exists('curl'))
		{
			include_once ROOT_PATH . './lib/curl.class.php';
		}
		$curl = new curl($this->settings['App_live']['host'], $this->settings['App_live']['dir']);
		$curl->initPostData();
		$curl->addRequestData('a', 'show');
		$curl->addRequestData('channel_id', $channel_id);
		$channel = $curl->request('channel.php');
		$channel = $channel[0] ? $channel[0] : array();
		if(!$channel)
		{
			return false;
		}
		return preg_replace('/&starttime=\d*/', '', $channel['channel_stream'][0]['m3u8']);
	}
}
include ROOT_PATH . 'excute.php';
?>