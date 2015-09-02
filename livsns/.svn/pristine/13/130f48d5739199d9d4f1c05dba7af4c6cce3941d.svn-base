<?php
require_once('global.php');
define('MOD_UNIQUEID','transcode_manger');//模块标识
set_time_limit(0);
class snap extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function snappics()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		
		if (isset($this->input['stime']))
		{
			$stime = intval($this->input['stime']); //开始时间 单位秒
			$stime = $stime ? $stime : 200; //开始时间 单位秒
		}
		$etime = intval($this->input['etime']); //结束时间 可以不设置
		$aspect = $this->input['aspect'];
		if ($etime && $etime < $stime)
		{
			$this->errorOutput(TIMEERROR);
		}
		
		$info = $this->getMediaInfo($id);//获取视频信息
		if ($info)
		{
			if (!$info['aspect'])
			{
				$info['aspect'] = '4:3';
			}
			if (!$info['width'])
			{
				$info['width'] = 400;
			}
			if (!$info['height'])
			{
				$info['height'] = 300;
			}
			$aspect = explode(':',$info['aspect']);
			if(!intval($aspect[0]))
			{
				$rate = 4 / 3;
			}
			else
			{
				$rate = $aspect[1] / $aspect[0];
			}
			
			$vrate = $info['height'] / $info['width'];
			if ($rate != $vrate)
			{
				$info['height'] = intval($info['width'] * $vrate);
			}
		}

		if (!$stime)
		{
			$stime = $info['duration'] / 3;
		}
		if (!$etime)
		{
			//$etime = $stime;
			$etime = $info['duration'];
		}
		$count = intval($this->input['count']); //截取图片数
		$count = $count ? $count : 1;
		
		$dir = ceil($stime / 6000) . '/';
		$section = $etime - $stime ;
		if ($section >= $count)
		{
			if(($count - 1) == 0)
			{
				$timestep = $section;
			}
			else 
			{
				$timestep = intval($section / ($count - 1));
			}
		}
		else
		{
			$timestep = 1;
		}
		$snapdir = TARGET_DIR . $info['video_path'] . 'snap/' . $dir;
		hg_mkdir($snapdir);
		
		if(defined("TARGET_VIDEO_DOMAIN"))
		{
			$visit_url = 'http://' . ltrim(TARGET_VIDEO_DOMAIN,'http://') . '/' . $info['video_path'] . 'snap/' . $dir;
		}
		else 
		{
			$visit_url = $this->settings['videouploads']['protocol'] . $this->settings['videouploads']['host'] . '/' . $info['video_path'] . 'snap/' . $dir; 
		}

		$width = intval($this->input['width']);
		$height = intval($this->input['height']);
		if (!$width && !$height)
		{
			$width = intval($info['width']);
			$height = intval($info['height']);
		}
		elseif (!$height)
		{
			$height = $width * $info['height'] / $info['width'];
		}
		elseif (!$width)
		{
			$width = $height * $info['width'] / $info['height'];
		}
		$snaps = array();
		if ($this->input['data'])
		{
			$visit_url = '';
		}
		//$source = TARGET_DIR . $info['video_path'] .$info['video_filename'];
		$source = $info['video_base_path'] . $info['video_path'] .$info['video_filename'];
		for ($i = 0; $i < $count;  $i++)
		{
			$time = $i * $timestep + $stime;
			$jpg = hg_snap($time, $snapdir, $width, $height, $source);
			$pre = $visit_url;
			if (!is_file($snapdir . $jpg))
			{
				$jpg = $width . '_fail.jpg';
				$pre = '';
			}
			$snaps[] = $pre . $jpg;
		}
		if ($this->input['data'] && count($snaps) == 1)
		{
			$file = $snapdir . $snaps[0];
			$filesize = @filesize($file);
			header('Content-Type: image/jpeg');
			if (!$filesize)
			{
				exit;
			}
			header('Cache-control: max-age=31536000');
			header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 31536000) . ' GMT');
			header('Content-Disposition: inline; filename="snap.jpg"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . $filesize);
			readfile($file);
			exit;
		}
		else
		{
			if ($this->input['debug'])
			{
				foreach ($snaps AS $k => $v)
				{
					$snaps[$k] = '<img src="' . $v . '", width="300" alt="' . $v . '" /><br />' . $v;
				}
			}
			$this->addItem($snaps);
			$this->output();
		}
	}
	
	//获取视频信息
	private function getMediaInfo($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = {$id} ";
		$media = $this->db->query_first($sql);
		return $media;
	}
	
	//重写基类验证token方法(由于播放器会调到)
	protected function verifyToken()
	{
		
	}
}

$out = new snap();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'snappics';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>