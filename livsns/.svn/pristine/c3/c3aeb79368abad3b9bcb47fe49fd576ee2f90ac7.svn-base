<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
class  app_append_data extends appCommonFrm
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*根据传过来的类型返回该类型下的分类*/
	public function get_app_name()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."apps ";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$return[] = $r;
		}
		
		$this->addItem($return);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}

$out = new app_append_data();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>