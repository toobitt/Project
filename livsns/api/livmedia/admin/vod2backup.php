<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: vod2backup.php 19137 2013-03-23 06:20:53Z zhoujiafei $
***************************************************************************/
define('NOD_UNIQUEID', 'vod2backup');
//***************************
require_once('global.php');
class vod2backup extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function getVodInfoById()
	{
		if (!intval($this->input['id']))
		{
			$this->errorOutput('未传入视频id');
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id = " . intval($this->input['id']);
		$video_info = $this->db->query_first($sql);
		if ($video_info['img_info'])
		{
			$video_info['img_info'] = unserialize($video_info['img_info']);
		}
		
		$video_info['vod_url'] = $video_info['hostwork'].'/'. $video_info['video_path'] . $video_info['video_filename'];
		
		$this->addItem($video_info);
		$this->output();
	}
	
}

$out = new vod2backup();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'getVodInfoById';
}
$out->$action();
?>