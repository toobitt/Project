<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','publishplan_clean');//模块标识
class cleanApi extends cronBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
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
			'name' => '清除发布日志',	 
			'brief' => '清除发布日志',
			'space' => '86400',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		$days = $this->input['days']?intval($this->input['days']):30;
		$time = strtotime("last month",TIMENOW);
		$sql = "DELETE FROM ".DB_PREFIX."plan_log WHERE publish_time<".$time;
		$this->db->query($sql);
		echo "删除了".date('Y-m-d H:i:s',$time)."时间之前的发布日志";
		exit;
	}	
}

$out = new cleanApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>