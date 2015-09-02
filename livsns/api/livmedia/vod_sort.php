<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program.php 7071 2012-06-08 05:22:40Z repheal $
***************************************************************************/
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
define('MOD_UNIQUEID','vod_sort');//模块标识
class vodSortApi extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	//自动录播栏目
	function show()
	{
		$father = array_keys($this->settings['video_upload_type'],'直播归档');
		$this->setXmlNode('record_item' , 'info');
		$sql = "select id,name from " . DB_PREFIX . "vod_media_node WHERE fid=" . $father[0];
		$q = $this->db->query($sql);
		$sort_name =  array();
		while($row = $this->db->fetch_array($q))
		{
			$sort_name[$row['id']] = $row;
		}
		echo json_encode($sort_name);
	}
	
	public function detail()
	{
		
	}
	
	public function count()
	{
	
	}
}

$out = new vodSortApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>