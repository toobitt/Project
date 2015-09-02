<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
class  append_data extends appCommonFrm
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//获取服务器名称
	public function get_server()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."server ";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$return[] = $r;
		}
		
		$this->addItem($return);
		$this->output();
	}

	public function unkonw()
	{
		$this->errorOutput(NOMETHOD);
	}
}

$out = new append_data();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unkonw';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>