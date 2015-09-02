<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: video_xml.php 4085 2011-06-17 02:06:42Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
class videoxml extends uiBaseFrm
{
	private $mVideo;
	function __construct()
	{
		parent::__construct();	
		include_once (ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$xml = $this->mVideo->video_xml();
		file_put_contents(ROOT_PATH . "vui/xml/videos.xml",$xml);
		return "success";
	}
}

$out = new videoxml();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();

?>