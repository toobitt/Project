<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: my_video.php 3009 2011-03-23 00:59:35Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
include_once(ROOT_DIR . 'lib/class/gdimage.php');
set_time_limit(0);
class myVideos extends adminBase
{
	function __construct()
	{
		parent::__construct();
		include_once('./update_video_state.php');
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{		
		$update_obj = new updateUserVideoState();	
		$update_obj->updata_video_state();
	}

	public function update_video_image()
	{		
		$ext = "";
		if($this->input['id'])
		{
			$ext = " and id IN (".$this->input['id'].")";
		}
		$pp = $this->input['pp']?$this->input['pp']:20;
		$sql = "SELECT id,bschematic,title FROM " . DB_PREFIX . "video WHERE images = ''" . $ext ." limit ".$pp;
		$q = $this->db->query($sql);
		$update_obj = new updateUserVideoState();	
		while ($row = $this->db->fetch_array($q))
		{
			echo $update_obj->handle($row['id'], $row['bschematic'], $row['title']);
		}
	}
}

$out = new myVideos();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>