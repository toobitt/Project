<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  vod_add_collect extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function  add_to_collect()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo  WHERE id in (".urldecode($this->input['id']).")";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$img_arr = unserialize($r['img_info']);
			$r['img'] = $img_arr['host'].$img_arr['dir'].'80x60/'.$img_arr['filepath'].$img_arr['filename'];
			$r['duration'] = time_format($r['duration']);
			$return[] = $r;
		}
		
		$this->addItem($return);
		$this->output();		
		
	}
	
}

$out = new vod_add_collect();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'add_to_collect';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>