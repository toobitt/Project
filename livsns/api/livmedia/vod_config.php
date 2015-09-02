<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 4234 2011-07-28 05:14:16Z repheal $
***************************************************************************/
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
define('MOD_UNIQUEID', 'vod');
class vodConfig extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 显示
	 */
	function show()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'vod_config WHERE is_use=1 ORDER BY config_order_id DESC';
		$q = $this->db->query($sql);
		if ($this->db->num_rows($q))
		{
			while ($row = $this->db->fetch_array($q))
			{
				$this->addItem($row);
			}
		}
		else
		{
			$config = array(
				'output_format' => 'mp4',	
				'codec_format' => 'H264',	
				'codec_profile' => 'H264_MAIN',	
				'width' => '720',	
				'height' => '480',	
				'video_bitrate' => '900',	
				'audio_bitrate' => '48',	
				'frame_rate' => '24',	
				'gop' => '50',	
				'vpre' => 'slow',	
				'water_mark' => '',	
			);
			$this->addItem($row);
		}
		$this->output();
		
	}
	
	public function detail()
	{
		
	}
	
	public function count()
	{
	
	}
}

$out = new vodConfig();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>