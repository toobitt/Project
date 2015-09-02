<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','weibogroup_update_user_queue');
class UpdateUserQueue extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	public function initcron()
	{
		$array = array(
				'mod_uniqueid' => MOD_UNIQUEID,
				'name' => '微博圈更新用户信息队列',
				'brief' => '微博圈更新用户信息队列',
				'space' => '3600',	//运行时间间隔，单位秒
				'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	public function  show()
	{
		$sql = "SELECT id,uid, name,group_id FROM ".DB_PREFIX."user WHERE 1 ORDER BY update_time ASC LIMIT 1";
		$q = $this->db->query_first($sql);
		$sql = "SELECT * FROM " . DB_PREFIX ."plat_token WHERE appid = " . intval($this->user['appid']) ."  AND type = " . intval($q['group_id']) . " ORDER BY lastusetime ASC ";
		$plat_token = $this->db->query_first($sql);
		include_once(ROOT_PATH . 'lib/class/share.class.php');
		$this->share = new share();		
		$user_info = $this->share->get_user($q['uid'],$q['name'],$plat_token['plat_token']);	
		$user_info = $user_info[0];
		if($user_info && $user_info['uid'] && !$user_info['error'])
		{
			$avatar = array('host'=>$user_info['avatar'],'dir' => '','filepath' =>'','filename' => '');
			$user_info['avatar'] = $avatar;
			$sql = "UPDATE " . DB_PREFIX ."user SET  uid = '".$user_info['uid']."', avatar = '" . (serialize($avatar)) . "', user_info = '".(serialize($user_info)) . "' WHERE id = " . $q['id'];
			$this->db->query($sql);
		}
		$sql = "UPDATE ".DB_PREFIX."user SET update_time = " .TIMENOW . " WHERE id = " . $q['id'];
		$this->db->query($sql);							
		echo $q['id'];
		echo "<pre>";
		print_r($user_info);
		exit();
	}
	
	public function batch_show() {
		set_time_limit(0);
		$sql = "SELECT * FROM ".DB_PREFIX."plat_token WHERE 1 ORDER BY lastusetime";
		$q = $this->db->query($sql);
		$plat_token = array();
		while ($row = $this->db->fetch_array($q)) {
			$plat_token[$row['type']] = $row;
		}	

		include_once(ROOT_PATH . 'lib/class/share.class.php');
		$this->share = new share();	
    	$sql = "SELECT id, uid, name, group_id FROM ".DB_PREFIX."user WHERE 1 AND status = 1 ORDER BY update_time ASC LIMIT 100"; 
    	$q = $this->db->query($sql);
    	while ($row = $this->db->fetch_array($q)) {
			//先用授权人账号关注这个用户
			$token = $plat_token[$row['group_id']];
			$user_info = $this->share->get_user($row['uid'],$row['name'],$token['plat_token']);	
			$user_info = $user_info[0];
			if($user_info && $user_info['uid'] && !$user_info['error'])
			{
				$avatar = array('host'=>$user_info['avatar'],'dir' => '','filepath' =>'','filename' => '');
				$user_info['avatar'] = $avatar;
				$sql = "UPDATE " . DB_PREFIX ."user SET  uid = '".$user_info['uid']."', avatar = '" . (serialize($avatar)) . "', user_info = '".(serialize($user_info)) . "' WHERE id = " . $row['id'];
				$this->db->query($sql);
			}
			$sql = "UPDATE ".DB_PREFIX."user SET update_time = " .TIMENOW . " WHERE id = " . $row['id'];
			$this->db->query($sql);							
			echo $row['id'];
			echo "<pre>";
			print_r($user_info);
			if ($user_info['error'] && $user_info['error'] != 'empty') {
				break;
			}			
    	}    			
	}
}
$out = new UpdateUserQueue();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'batch_show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 

?>
