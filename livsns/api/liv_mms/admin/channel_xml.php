<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel_xml.php 4507 2011-09-19 09:57:22Z lijiaying $
***************************************************************************/
require('global.php');
class channelXmlApi extends BaseFrm
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
		$id = $this->input['id'];
		$channel_id = $this->input['channel_id'];
//		$dates = $this->input['start_time'];
		$dates = TIMENOW;
		$sql = "select id,start_time, toff, theme from " . DB_PREFIX . "program where channel_id=" . $channel_id . " AND start_time>='" . $dates . "' ORDER BY start_time ASC LIMIT 1";
		$q = $this->db->query_first($sql);
		$program['start_time'] = $q['start_time'];
		$program['toff'] = $q['toff'];
		$program['theme'] = $q['theme'];
		
		$sql = "select name,logo,stream_id from " . DB_PREFIX . "channel where id=".$id;
		$ch = $this->db->query($sql);
		$channel = array();
		while($row = $this->db->fetch_array($ch))
		{
			$channel[$id]['name'] = $row['name'];
			$channel[$id]['logo'] = $row['logo'];
			$channel[$id]['output'] = $row['stream_id'];
			$channel[$id]['program'] = $program;
		}
		foreach($channel as $key => $value)
		{
			header('Content-Type: text/xml; charset=UTF-8');
			$dom = new DOMDocument('1.0', 'utf-8');
			$channel= $dom->createElement('channel');
			$channel->setAttribute('name', $value['name']);
			$channel->setAttribute('url', $value['logo']);
			$channel->setAttribute('current', $value['stream_id']);
			$logos= $dom->createElement('logos');
			$item= $dom->createElement('item');
			$item->setAttribute('url', $value['logo']);
			$logos->appendChild($item);
			$channel->appendChild($logos);
			$dom->appendChild($channel);
			return $dom->saveXml();
		}
	}
}

$out = new channelXmlApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>