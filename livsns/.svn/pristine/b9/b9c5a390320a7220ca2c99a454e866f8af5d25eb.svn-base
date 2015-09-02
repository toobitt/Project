<?php 
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  vod_get_collect_info extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//获取集合信息
	public function  get_collect_info()
	{
		if(!$this->input['video_id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT collect_name FROM ".DB_PREFIX."vod_collect WHERE id in 
		       (SELECT collect_id FROM ".DB_PREFIX."vod_collect_video WHERE video_id = ".urldecode($this->input['video_id']).") ";
		
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$return[] = $r;
		}
	
		$this->addItem($return);
		$this->output();
	}
	
}

$out = new vod_get_collect_info();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'get_collect_info';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 

?>