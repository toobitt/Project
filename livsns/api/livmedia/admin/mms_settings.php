<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: vod.php 6468 2012-04-20 01:51:14Z develop_tong $
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  mms_settings extends adminReadBase
{
    public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$sql = " SELECT * FROM ".DB_PREFIX."settings ";
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			switch ($r['type'])
			{
				case 0:
					    $r['value'] = unserialize($r['value']);
						$ret['gglobal'][] = $r;
						break;
				case 1: $r['value'] = unserialize($r['value']);
						$ret['gdb'] = $r;
						break;
				case 2:$ret['gdefine'][] = $r;break;
			}
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function insert_config()
	{
		if($data)
		{
			foreach($data AS $k => $v)
			{
				$sql = '';
				$sql  = " INSERT INTO ".DB_PREFIX."app_settings SET ";
				$sql .= " type = 2,".
						" name = '图集',".
						" bundle_id = 'tuji',".
						" var_name = '".$k."',".
						" value = '".$v."',".
						" is_edit = 1,".
						" is_open = 1";
				$this->db->query($sql);
			}
		}

		$this->addItem('success');
		$this->output();
	}
}

$out = new mms_settings();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>