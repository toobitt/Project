<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel_xml.php 4507 2011-09-19 09:57:22Z lijiaying $
***************************************************************************/
require('global.php');
class channelXmlApi extends adminBase
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
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		$sql = "SELECT id, start_time, toff, theme FROM " . DB_PREFIX . "program ";
		$sql.= " WHERE channel_id = " . $channel_id . " AND start_time>=" . TIMENOW;
		$sql.= " ORDER BY start_time ASC LIMIT 1";
		$program_info = $this->db->query_first($sql);
		
		$sql = "SELECT name, logo_info, stream_id FROM " . DB_PREFIX . "channel ";
		$sql.= " WHERE id = " . $channel_id;
		$channel_info = $this->db->query_first($sql);
		$logo_url = '';
		if ($channel_info['logo_info'])
		{
			$logo_info = @unserialize($channel_info['logo_info']);
			$logo_url = hg_material_link($logo_info['host'], $logo_info['dir'], $logo_info['filepath'], $logo_info['filename']);
		} 
		
		header('Content-Type: text/xml; charset=UTF-8');
		$dom = new DOMDocument('1.0', 'utf-8');
		$channel = $dom->createElement('channel');
		$channel->setAttribute('name', $channel_info['name']);
		$channel->setAttribute('url', $logo_url);
		$channel->setAttribute('current', $channel_info['stream_id']);
		$logos	= $dom->createElement('logos');
		$item 	= $dom->createElement('item');
		$item->setAttribute('url', $channel_info['logo']);
		$logos->appendChild($item);
		$channel->appendChild($logos);
		$dom->appendChild($channel);
		echo $dom->saveXml();
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