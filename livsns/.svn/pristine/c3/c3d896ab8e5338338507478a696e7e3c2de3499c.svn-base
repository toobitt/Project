<?php
require_once('global.php');
define('MOD_UNIQUEID','transcode_manger');//模块标识
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(CUR_CONF_PATH . 'lib/mediainfo.class.php');
set_time_limit (0);

class livesnap extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function startsnap()
	{
		$id = $this->input['channel_id'];
		$stream_uri = $this->input['stream_uri'];
		if (!$id || !$stream_uri)
		{
			$this->errorOutput('频道id或者流地址不能为空');
		}
		$time = $this->input['time'] ? $this->input['time'] : TIMENOW;
		$filename = TARGET_DIR . 'livesnap/' . $id . '/%d.png';
		hg_mkdir(TARGET_DIR . 'livesnap/' . $id);
		$cmd = 'ps -ef|grep ' . $stream_uri;
		exec($cmd, $out, $status);
		foreach ($out AS $v)
		{
			if (strstr($v, FFMPEG_CMD))
			{
				$v = preg_replace('/\s+/is', ' ', $v);
				$pid = explode(' ', $v);
				$pid = intval($pid[1]);
				break;
			}
		}
		if ($pid)
		{
			$snap = array(
				'channel_id' => $id,
				'pid' => $pid,
			);
			$this->addItem($snap);
			$this->output();
		}
		$snapinterval = intval($this->input['interval']);
		$snapinterval = $snapinterval ? $snapinterval : 1;
		$snapcmd = 'nohup ' . FFMPEG_CMD . ' -i ' . $stream_uri . ' -vcodec png -s 320x240 -r ' . $snapinterval . ' -y ' . $filename . ' > /dev/null &';
		exec($snapcmd, $out, $status);
		$snap = array(
			'channel_id' => $id,
			'snapcmd' => $snapcmd,
		);
		$this->addItem($snap);
		$this->output();
	}

	public function show()
	{
		$dir = TARGET_DIR . 'livesnap/';
		$time = $this->input['time'] ? $this->input['time'] : TIMENOW;
		
		$material = new material();
		$handle = dir($dir);
		$snaps = array();
		while ($file = $handle->read())
		{
			if($file == '.' || $file == '..' || !intval($file))
			{
				continue;
			}
			if (!is_dir($dir . $file . '/'))
			{
				continue;
			}
			$sdir = $dir . $file . '/';
			$handle1 = dir($sdir);
			
			$i = 0;
			while ($file1 = $handle1->read())
			{
				if(!is_file($sdir . $file1))
				{
					continue;
				}
				$vv = $sdir . $file1;
				if (!$i)
				{	
					$k = $file;
					@rename($vv, $dir . $k . '/live_' . $k . '.png');
					$vv = $dir . $k . '/live_' . $k . '.png';
					if(defined("TARGET_VIDEO_DOMAIN"))
					{
						$pic = 'http://' . ltrim(TARGET_VIDEO_DOMAIN,'http://') . '/' . 'livesnap/' . $k . '/live_' . $k . '.png';
					}
					else 
					{
						$pic = $this->settings['videouploads']['protocol'] . $this->settings['videouploads']['host'] . '/' . 'livesnap/' . $k . '/live_' . $k . '.png';
					}
					
					$img_info = $material->addMaterialNodb($pic, 1, 'livesnap/img/'. date('Y', $kk) . '/' . date('m', $kk));
					$this->addItem($img_info[0]);
				}
				else
				{
					@unlink($vv);
				}
				$i++;
			}
		}

		$this->output();
	}
}

$out = new livesnap();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>