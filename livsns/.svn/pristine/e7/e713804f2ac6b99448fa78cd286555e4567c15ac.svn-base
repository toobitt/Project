<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
***************************************************************************/
class ChannelChange extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	public function  channel_emergency_change($channel_id,$source_type,$source)
	{
		$channel_id = intval($channel_id);
		$source_type = urldecode($source_type);
		$source = urldecode($source);
		$fp = @fsockopen($this->settings['tvie']['up_stream_server']['api_server_name'], $this->settings['tvie']['up_stream_server']['liveport'], $errno, $errstr, 3);
		if (!$fp) 
		{
		   return -1; //无法连接切播服务
		} 
		else 
		{
			$source_type = 'file';
			$cmd = array(
				'ch_id' => $channel_id,
				'type' => 'insert',
				'uri_type' => $source_type,
				'uri' => $source
			);
			$string = json_encode($cmd);
			//file_put_contents(ROOT_PATH . 'uploads/de.txt', $string . "\n");
			stream_set_timeout($fp, 4);
			fwrite($fp, $string);
			$ret = fgets($fp, 128);
			fclose($fp);
			//file_put_contents(ROOT_PATH . 'uploads/de.txt', $ret, FILE_APPEND);
			return 1; //切播成功
		}
	}

	public function  channel_resume($channel_id)
	{
		$channel_id = intval($channel_id);
		$fp = @fsockopen($this->settings['tvie']['up_stream_server']['api_server_name'], $this->settings['tvie']['up_stream_server']['liveport'], $errno, $errstr, 3);
		if (!$fp) 
		{
		   return -1; //无法连接切播服务
		} 
		else 
		{
			//$string = "ch_id:{$channel_id},type:cancel\n";
			$cmd = array(
				'ch_id' => $channel_id,
				'type' => 'cancel'
			);
			$string = json_encode($cmd);
			//file_put_contents(ROOT_PATH . 'uploads/de.txt', $string, FILE_APPEND);
			stream_set_timeout($fp, 4);
			fwrite($fp, $string);
			$ret = fgets($fp, 128);
			fclose($fp);
			file_put_contents(ROOT_PATH . 'uploads/de.txt', $ret, FILE_APPEND);
			return 1; //回到直播成功
		}
	}
	

	public function  channel_status($channel_id)
	{
		$channel_id = intval($channel_id);
		$fp = @fsockopen($this->settings['tvie']['up_stream_server']['api_server_name'], $this->settings['tvie']['up_stream_server']['liveport'], $errno, $errstr, 3);
		if (!$fp) 
		{
		    return 'Failed';
		} 
		else 
		{
			$string = "ch_id:{$channel_id},type:query\n";
			$cmd = array(
				'ch_id' => $channel_id,
				'type' => 'query'
			);
			$string = json_encode($cmd);
			stream_set_timeout($fp, 2);
			fwrite($fp, $string);
			if ($ret = fgets($fp, 128)) 
			{
				$ret = str_replace("\n", '', $ret);
				$ret = trim($ret);
			} 
			else 
			{
				$ret = 'Failed';
			}
			fclose($fp);
			return $ret;
		}
	}
}
?>