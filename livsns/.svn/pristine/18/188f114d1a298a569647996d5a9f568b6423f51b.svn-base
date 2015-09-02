<?php
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  vod_upload_update extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	function batch_review_publish()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE status = 2 AND column_id !='' AND column_id != 'a:0:{}' AND expand_id = 0 AND create_time > 1364439200";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{	
			publish_insert_query($row,'insert');
		}	
		exit('成功');		
	}
}

$out = new vod_upload_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'batch_review_publish';
}
else
{
	$action = $_INPUT['a'];
}
$out->$action();
?>