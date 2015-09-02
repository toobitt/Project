<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: curl.class.php 85 2011-07-08 00:56:46Z develop_tong $
***************************************************************************/

class mediainfo
{
	private $mMediaFile;
	private $mVideoInfo = array();
	function __construct($file = '')
	{
		$this->setFile($file);
	}

	function __destruct()
	{
	}

    public function setFile($file)
	{
		$this->mMediaFile = $file;
	}
    public function isMeida()
	{
	}
    public function getMeidaInfo()
	{
		$this->mediaInfo();
		return $this->mVideoInfo;
	}

    private function mediaInfo()
	{
		$cmd = MEDIAINFO_CMD . ' ' . $this->mMediaFile;
		exec($cmd, $data, $status);
		//file_put_contents('../cache/20.txt', var_export($data,1));
		if (!$data)
		{
			return false;
		}
		foreach ($data AS $k => $v)
		{
			if (!$v)
			{
				continue;
			}
			$v = explode(':', $v);
			$k = trim($v[0]);
			unset($v[0]);
			if ($v)
			{
				$v = trim(implode(':', $v));
			}
			else
			{
				$v = '';
			}
			if (!$v && $v != '0')
			{
				$this->mVideoInfo[$k] = array();
				$cur_index = $k;
			}
			else
			{
				$this->mVideoInfo[$cur_index][$k] = $v;
			}
		}
		$this->convert_general();
		$this->convert_video();
		$this->convert_audio();
	}

	private function convert_general()
	{
		if (!$this->mVideoInfo['General'])
		{
			return;
		}
		$this->mVideoInfo['General']['Duration'] = $this->dur2sec($this->mVideoInfo['General']['Duration']);
		$this->mVideoInfo['General']['File size'] = $this->size2byte($this->mVideoInfo['General']['File size']);
		$this->mVideoInfo['General']['Overall bit rate'] = $this->rate2byte($this->mVideoInfo['General']['Overall bit rate']);
	}

	private function convert_video()
	{
		if (!$this->mVideoInfo['Video'])
		{
			return;
		}
		$this->mVideoInfo['Video']['Duration'] = $this->dur2sec($this->mVideoInfo['Video']['Duration']);
		$this->mVideoInfo['Video']['Stream size'] = $this->size2byte($this->mVideoInfo['Video']['Stream size']);
		$this->mVideoInfo['Video']['Width'] = intval(str_replace(' ', '', $this->mVideoInfo['Video']['Width']));
		$this->mVideoInfo['Video']['Height'] = intval(str_replace(' ', '', $this->mVideoInfo['Video']['Height']));
		$this->mVideoInfo['Video']['Bit rate'] = $this->rate2byte($this->mVideoInfo['Video']['Bit rate']);
	}

	private function convert_audio()
	{
		if (!$this->mVideoInfo['Audio'])
		{
			return;
		}
		$this->mVideoInfo['Audio']['Duration'] = $this->dur2sec($this->mVideoInfo['Audio']['Duration']);
		$this->mVideoInfo['Audio']['Stream size'] = $this->size2byte($this->mVideoInfo['Audio']['Stream size']);
		$this->mVideoInfo['Audio']['Bit rate'] = $this->rate2byte($this->mVideoInfo['Audio']['Bit rate']);
	}

	private function dur2sec($dur)
	{
		preg_match('/(\d*)h/is', $dur, $match);
		$h = $match[1];
		preg_match('/(\d*)mn/is', $dur, $match);
		$mn = $match[1];
		preg_match('/(\d*)s/is', $dur, $match);
		$s = $match[1];
		preg_match('/(\d*)ms/is', $dur, $match);
		$ms = $match[1];
		$msec = $h * 3600 * 1000 + $mn * 60 * 1000 + $s * 1000 + $ms;
		return $msec;
	}

	private function rate2byte($size)
	{
		$size = trim($size);
		$unit = explode(' ', $size);
		$unit = $unit[count($unit) - 1];
		$size = str_replace(' ', '', $size);
		$size = floatval($size);
		if ($unit == 'Gbps')
		{
			$size = $size * 1024 * 1024;
		}
		elseif ($unit == 'Mbps')
		{
			$size = $size * 1024;
		}
		elseif ($unit == 'bps')
		{
			$size = $size / 1024;
		}
		$size = intval($size);
		return $size;
	}

	private function size2byte($size)
	{
		$size = str_replace(' ', '', $size);
		$unit = substr(trim($size), -3, 3);
		$size = floatval($size);
		if ($unit == 'GiB')
		{
			$size = $size * 1024 * 1024 * 1024;
		}
		elseif ($unit == 'MiB')
		{
			$size = $size * 1024 * 1024;
		}
		elseif ($unit == 'KiB')
		{
			$size = $size * 1024;
		}
		$size = intval($size);
		return $size;
	}
}
?>