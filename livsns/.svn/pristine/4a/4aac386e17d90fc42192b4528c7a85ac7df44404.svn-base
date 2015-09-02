<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','road_friendships_create');
class friendshipscreate extends cronBase
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/share.class.php');
		$this->share = new share();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
				'mod_uniqueid' => MOD_UNIQUEID,
				'name' => '关注用户计划任务',
				'brief' => '关注用户计划任务',
				'space' => '36000',	//运行时间间隔，单位秒
				'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function  create()
	{
		set_time_limit(0);
		$sql = "SELECT * FROM ".DB_PREFIX."plat_token WHERE 1 ORDER BY lastusetime";
		$q = $this->db->query($sql);
		$plat_token = array();
		while ($row = $this->db->fetch_array($q)) {
			$plat_token[$row['type']] = $row;
		}
//		print_r($plat_token);exit;
    	$sql = "SELECT id, uid, name, group_id FROM ".DB_PREFIX."user WHERE 1 AND status = 1 ORDER BY update_time ASC LIMIT 200"; 
    	$q = $this->db->query($sql);
    	while ($row = $this->db->fetch_array($q)) {
			//先用授权人账号关注这个用户
			$token = $plat_token[$row['group_id']];
			$ret = $this->share->friendships_create($token['appid'],$token['platid'],$row['uid'],$row['name'],$token['plat_token']);
			print_r($ret);
			
			$sql = "UPDATE ".DB_PREFIX."user SET update_time = " .TIMENOW . " WHERE id = " . $row['id'];
			$this->db->query($sql);			
			
			echo $row['id'] .'----'.$row['name'] . "<br/>";	
			if ($ret['error'] && $ret['error'] != 'empty') {
				break;
			}  					
			
			//sleep(1);		
    	}            		    	
	}

}
$out = new friendshipscreate();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'create';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 

?>
