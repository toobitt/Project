<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program.php 7071 2012-06-08 05:22:40Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID', 'vod');
class itemSortApi extends adminBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	

	//节目类型
	function getType()
	{
		$this->setXmlNode('program_type' , 'info');
		foreach($this->settings['program_type'] as $key => $value)
		{
			$this->addItem($value);
		}
		$this->output();
	}
	//自动录播栏目
	function getAutoItem()
	{
		$father = array_keys($this->settings['video_upload_type'],'直播归档');
		$this->setXmlNode('record_item' , 'info');
		$sql = "select id,name from " . DB_PREFIX . "vod_media_node WHERE fid=" . $father[0];
		$q = $this->db->query($sql);
		$sort_name =  array();
		while($row = $this->db->fetch_array($q))
		{
			$sort_name[$row['id']] = $row['name'];
		}
		echo json_encode($sort_name);
	}
	//屏蔽类型
	function getTitle()
	{
		$sql = "select id,title from " . DB_PREFIX . "backup WHERE 1";
		$q = $this->db->query($sql);
		$program_screen =  array();
		while($row = $this->db->fetch_array($q))
		{
			$program_screen[$row['id']] = $row['title'];
		}
		echo json_encode($program_screen);
	}
}

$out = new itemSortApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>