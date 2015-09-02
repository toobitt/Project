<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 325 2011-11-17 02:21:45Z develop_tong $
***************************************************************************/
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'modify');
define('WITH_DB', true);
require('./global.php');
class modify extends InitFrm
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		//$app_uniqueid = intval($this->input['app_uniqueid']);
		//$mod_uniqueid = intval($this->input['mod_uniqueid']);
		$app_uniqueid = trim($this->input['app_uniqueid']);
		$app_uniqueid = ($app_uniqueid == 'media_channel') ? 'livmedia' : $app_uniqueid;
		$mod_uniqueid = trim($this->input['mod_uniqueid']);
		$mod_uniqueid = ($mod_uniqueid == 'vod') ? 'livmedia' : $mod_uniqueid;
		$app = trim($this->input['app']);
		$mod = trim($this->input['mod']);
		$para = intval($this->input['para']);
		$outlink = !!$this->input['outlink'];
		$id = intval($this->input['id']);
		$fromsource = $this->input['fromsource'];
		$backurl = urlencode($this->input['backurl']);
		if ($app_uniqueid && $mod_uniqueid)
		{
			$sql = 'SELECT id FROM ' . DB_PREFIX . "modules WHERE app_uniqueid='{$app_uniqueid}' AND mod_uniqueid='{$mod_uniqueid}'";
			$mid = $this->db->query_first($sql);
			$url = 'run.php?mid=' . $mid['id'] . '&a=form'.($outlink ? '_outerlink' : '').'&id=' . $id.'&app=' . $app.'&mod=' . $mod.'&para=' . $para.'&infrm=1&backpublish=1';
			if($fromsource)
			{
				$url = $url.'&fromsource='.$fromsource.'&backurl='.$backurl;
			}
			if ($mid)
			{
				header('Location:' . $url);
			}
		}
	}
	
}
include (ROOT_PATH . 'lib/exec.php');
?>