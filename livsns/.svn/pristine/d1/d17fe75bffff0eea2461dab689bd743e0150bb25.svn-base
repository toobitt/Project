<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','weibogroup_cleardata');
class clearData extends cronBase
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	public function initcron()
	{
		$array = array(
				'mod_uniqueid' => MOD_UNIQUEID,
				'name' => '微博圈清除微博',
				'brief' => '微博圈清除微博',
				'space' => '86400',	//运行时间间隔，单位秒
				'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	function clear()
	{
	    if (CLEAR_TYPE == 2) {   //按时间清理
	        $retention_time = MAX_RETENTION_TIME * 3600 * 24;
            $retention_time = TIMENOW - $retention_time;
            $sql = "DELETE FROM ".DB_PREFIX."weibo WHERE create_time < " . $retention_time;
            $this->db->query($sql);
            $sql = "DELETE FROM ".DB_PREFIX."weibo_circle WHERE create_time < " . $retention_time;
            $this->db->query($sql);
	    }  
        else if (CLEAR_TYPE == 1) {    //按条数清理
    		$sql = "SELECT id FROM ".DB_PREFIX."circle";
    		$q = $this->db->query($sql);
    		while($circle_info = $this->db->fetch_array($q)) {
    			$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."weibo_circle WHERE circle_id = " . $circle_info['id'];
    			$total_num = $this->db->query_first($sql);
    			$limit_num = $total_num['total'] - MAX_RECORD_NUM;
    			if($limit_num > 0) {
    				$limit_num = $limit_num > 2000 ? 2000 : $limit_num;
    				$sql = "SELECT weibo_id FROM " . DB_PREFIX . "weibo_circle WHERE circle_id = " . $circle_info['id'] ." ORDER BY create_time ASC, weibo_id ASC LIMIT 0," . $limit_num;
    				$ret = $this->db->query($sql);
    				$ids = array();
    				while($row = $this->db->fetch_array($ret)) {
    					$ids[] = $row['weibo_id'];
    				}
    				$sql = "DELETE FROM " . DB_PREFIX ."weibo_circle WHERE circle_id = ".$circle_info['id']." ORDER BY create_time ASC, weibo_id ASC LIMIT " . $limit_num;
    				$this->db->query($sql);
    				if (!empty($ids)) {
    					$ids = implode(',',$ids);
    					$sql = "UPDATE ".DB_PREFIX."weibo SET distribute_count = distribute_count-1 WHERE id IN(".$ids.")";
    					$this->db->query($sql);
    				}
    				$sql = "DELETE FROM " . DB_PREFIX ."weibo WHERE distribute_count <= 0";
    				$this->db->query($sql);			
    			}
    		}
		}
		echo '清理完成';
		exit(); 	
	}
}
$out = new clearData();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'clear';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>
