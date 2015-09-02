<?php
define('MOD_UNIQUEID','userQueue');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
class userQueue extends cronBase
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
			'name' => '爆料获取微博的队列',	 
			'brief' => '获取微博队列',
			'space' => '60',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function  user_queue()
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."user_token WHERE 1 AND audit=1 ORDER BY since_time ASC LIMIT 0,1";
		$info = $this->db->query_first($sql);
		if ($info)
		{
			$sql = "UPDATE " . DB_PREFIX ."user_token SET since_time = " . TIMENOW ." WHERE id = " . $info['id'];
			$this->db->query($sql);
			$data = array(
				'id'=>$info['id'],
				'appid'=>$info['appid'],
				'plat_id'=>$info['plat_id'],
				'plat_token'=>$info['plat_token'],
				'since_id'=>$info['since_id'],
				'since_time'=>TIMENOW,
				'weight'=>0,
				'con_sort'=>$info['con_sort'],
				'name'=>$info['name'],
			);
			$sql = 'REPLACE INTO '.DB_PREFIX.'user_queue SET ';
			foreach ($data as $key=>$val)
			{
				$sql .= $key.'="'.$val.'",'; 
			}
			$sql = rtrim($sql,',');
			$this->db->query($sql);
			exit('取用户成功');
		}else {
			exit('无满足条件的用户');
		}
	}
}
$out = new userQueue();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'user_queue';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 

?>
