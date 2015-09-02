<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  vod_back_words extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function  back_words()
	{
	    $contents = urldecode($this->input['contents']);
	    if(!$contents)
	    {
	    	if($contents == '0')
	    	{
	    		$sql = "SELECT collect_name,id FROM ".DB_PREFIX."vod_collect  WHERE is_auto=0 AND  collect_name  LIKE '%".$contents."%'";
	    	}
	    	else 
	    	{
	    		$sql = "SELECT collect_name,id  FROM ".DB_PREFIX."vod_collect  WHERE is_auto=0  ORDER BY create_time DESC  LIMIT 0,20";
	    	}
	    }
	    else 
	    {
	    	$sql = "SELECT collect_name,id FROM ".DB_PREFIX."vod_collect  WHERE is_auto=0 AND  collect_name  LIKE '%".$contents."%'";
	    }
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$return[] = $r;
		}
		$this->addItem($return);
		$this->output();
	}
}

$out = new vod_back_words();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'back_words';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>