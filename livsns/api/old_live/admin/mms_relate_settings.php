<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: vod.php 6468 2012-04-20 01:51:14Z develop_tong $
***************************************************************************/
require_once('global.php');
class  mms_relate_settings extends adminReadBase
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
		$sql = " SELECT * FROM ".DB_PREFIX."app_settings ";
		$q = $this->db->query($sql);
		$arr = array();
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			if(!in_array($r['bundle_id'], $arr))
			{
				$arr[] = $r['bundle_id'];
				$ret[] = array('bundle_id' => $r['bundle_id'],'name' => $r['name']);
			}
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_app_config()
	{
		if(!$this->input['bundle_id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = " SELECT * FROM ".DB_PREFIX."app_settings WHERE bundle_id = '".urldecode($this->input['bundle_id'])."'";
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			if($r['type'] == 0)
			{
				$r['value'] = var_export(unserialize($r['value']),1);
			}
			$ret[$r['type']][] = $r;
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function insert_data()
	{
		$data = array(
			'DB_PREFIX' => 'liv_',//定义数据库表前缀
			'IMG_DIR' => ROOT_PATH . 'uploads/',//附件绝对路径
		);
		
		foreach($data AS $k => $v)
		{
			$sql  = " INSERT INTO ".DB_PREFIX."app_settings SET ";
			$sql .= " name = '附件',".
					" bundle_id = 'material',".
					" type = 2,".
					" var_name = '".$k."',".
					" value = '".$v."',".
					" is_edit = 1,".
					" is_open = 1 ";
			$this->db->query($sql);
		}
		
		$this->addItem('success');
		$this->output();
	}

	public function count()
	{
		
	}
	public function detail()
	{
		
	}
	public function index()
	{
		
	}
}

$out = new mms_relate_settings();
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