<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
***************************************************************************/
define('MOD_UNIQUEID','lbs_clear_cache');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH."global.php");
define('SCRIPT_NAME', 'LbsCache');
class LbsCache extends cronBase
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
			'name' => '清除lbs缓存数据',	 
			'brief' => '清除lbs缓存数据',
			'space' => '300',			//运行时间间隔，单位秒
			'is_use' => 1,				//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		//缓存时间
		$cache_time = $this->settings['cache_time'];
		
		if(!$cache_time)
		{
			return true;
		}
		
		$_time = TIMENOW - ($cache_time*60);
		
		$sql = "DELETE FROM " . DB_PREFIX . "lbs_cache WHERE create_time < " . $_time;
		$this->db->query($sql);
		
		$this->addItem('success');
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');
?>