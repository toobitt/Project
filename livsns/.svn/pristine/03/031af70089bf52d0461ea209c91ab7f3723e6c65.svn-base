<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_xml.php 4507 2011-09-16 09:57:22Z lijiaying $
***************************************************************************/
require('global.php');
class programXmlApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function show()
	{
		$channel_id = $this->input['channel_id'];
		if(!$channel_id)
		{
			$this->errorOutput('该频道不存在或者已被删除');
		}
		$dates = $this->input['dates'];
		if(!$dates)
		{
			$this->errorOutput('这一天不存在节目');
		}
		$sql = "select * from " . DB_PREFIX . "program where channel_id=" . $channel_id . " AND dates='" . $dates . "' ORDER BY start_time ASC ";
		$q = $this->db->query($sql);
		header('Content-Type: text/xml; charset=UTF-8');
		$dom = new DOMDocument('1.0', 'utf-8');
		$program= $dom->createElement('program');
		while($row = $this->db->fetch_array($q))
		{
			$item= $dom->createElement('item');
			$item->setAttribute('name', urldecode($row['theme']));
			$item->setAttribute('startTime', $row['start_time'] * 1000);
			$item->setAttribute('duration', $row['toff'] * 1000);
			$program->appendChild($item);
		}
		$dom->appendChild($program);
		echo $dom->saveXml();
	}
}

$out = new programXmlApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>