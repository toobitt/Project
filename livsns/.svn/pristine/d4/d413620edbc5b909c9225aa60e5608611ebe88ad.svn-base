<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','weibogroup_user_queue');
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
				'name' => '微博圈用户队列',
				'brief' => '把用户放入队列表',
				'space' => '600',	//运行时间间隔，单位秒
				'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	public function  user_queue()
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."user WHERE status = 1 ORDER BY last_time ASC LIMIT 0,1";
		$info = $this->db->query_first($sql);
        if ($info) {
            $this->db->update_data('last_time = '.TIMENOW, 'user','id = ' . $info['id']);
            $info['original_id'] = $info['id'];
            unset($info['id']);
            $this->db->insert_data($info, 'queue_user');
    		echo $info['name'] . '已放入队列';
        }
        else {
            echo '暂无用户';
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
