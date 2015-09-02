<?php
define('MOD_UNIQUEID','tv_play');
require_once('global.php');
class updateTvPlayToLivmedia extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function getVideoIdFromTvPlay()
	{
		//查询出所有电视剧里面的视频
		$sql = "SELECT * FROM " .DB_PREFIX. "tv_episode ";
		$q = $this->db->query($sql);
		$tvPlay = array();
		while ($r = $this->db->fetch_array($q))
		{
			$tvPlay[$r['tv_play_id']][] =  $r['video_id'];
		}
		$this->addItem($tvPlay);
		$this->output();
	}
	
	protected function verifyToken(){}
}

$out = new updateTvPlayToLivmedia();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'getVideoIdFromTvPlay';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>