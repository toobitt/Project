<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: delete_video.php 1987 2011-01-30 09:01:29Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class verifyVideosApi extends adminBase
{
	function __construct()
	{
		parent::__construct();			
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function verify()
	{
		/*if(is_array($this->input['video_id']))
		{
			$video_id = $this->input['video_id'];
		}
		else
		{
			$video_id = array($this->input['video_id']);
		} 
				
		$ids = implode(',' , $video_id);*/
		
		$ids = urldecode($this->input['video_id']);
		$state = $this->input['video_state'];
		
		$sql = "UPDATE " . DB_PREFIX . "video SET is_show = " . $state . " WHERE id IN (" . $ids . ")";
		
		$this->db->query($sql);		
	}
}

$out = new verifyVideosApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'verify';
}
$out->$action();
