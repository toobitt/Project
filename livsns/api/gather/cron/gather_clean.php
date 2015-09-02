<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','gather_clean');//模块标识
class gatherCleanApi extends cronBase
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
			'name' => '清除采集数据',	 
			'brief' => '清除采集数据',
			'space' => '86400',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		$days = $this->input['days'] ? intval($this->input['days']) : 30;
		$time = strtotime("-".$days." days",TIMENOW);
		$sql = 'SELECT id FROM '.DB_PREFIX.'gather WHERE 1 AND create_time <'.$time;
		$query = $this->db->query($sql);
		$arr = array();
		while ($row = $this->db->fetch_array($query))
		{
			$arr[] = $row['id'];
		}
		if (!empty($arr))
		{
			$ids = implode(',', $arr);
			$sql = 'DELETE FROM '.DB_PREFIX.'gather WHERE id IN ('.$ids.')';
			$this->db->query($sql);
			$sql = 'DELETE FROM '.DB_PREFIX.'gather_content WHERE id IN ('.$ids.')';
			$this->db->query($sql);
			$sql = 'DELETE FROM '.DB_PREFIX.'gather_relation WHERE cid IN ('.$ids.')';
			$this->db->query($sql);
		}
		echo "删除了".date('Y-m-d H:i:s',$time)."时间之前的采集数据";
		exit;
	}	
}

$out = new gatherCleanApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>