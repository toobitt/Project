<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID','tuji');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class tuji_get_name extends appCommonFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_name()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."tuji ";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$return[] = $r;
		}
		
		$this->addItem($return);
		$this->output();
	}
	
	//获取想匹配的图集名称
	public function back_words()
	{
		$contents = urldecode($this->input['contents']);
	    if(!$contents)
	    {
	    	if($contents == '0')
	    	{
	    		$sql = "SELECT * FROM ".DB_PREFIX."tuji  WHERE  title  LIKE '%".$contents."%'";
	    	}
	    	else 
	    	{
	    		$sql = "SELECT * FROM ".DB_PREFIX."tuji  ORDER BY create_time DESC  LIMIT 0,20";
	    	}
	    }
	    else 
	    {
	    	$sql = "SELECT * FROM ".DB_PREFIX."tuji  WHERE title  LIKE '%".$contents."%'";
	    }
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$r['collect_name'] = $r['title'];
			$return[] = $r;
		}
		$this->addItem($return);
		$this->output();
	}
	
	public function get_tuji_sortname()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."tuji_sort ";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$return[] = $r;
		}
		
		$this->addItem($return);
		$this->output();
	}
	
	public function get_water_config()
	{
		 global $gGlobalConfig;
		 $curl = new curl($gGlobalConfig['App_material']['host'],$gGlobalConfig['App_material']['dir']);
		 $curl->setSubmitType('get');
		 $curl->initPostData();
		 $curl->addRequestData('a','get_water_config');
		 $ret = $curl->request('water.php');
		 $this->addItem($ret[0]);
		 $this->output();
	}
	
	public function water_config_list()
	{
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$material = new material();
		$ret = $material->water_config_list();
		$this->addItem($ret);
		$this->output();
	}
	
}
$out = new tuji_get_name();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'get_name';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>