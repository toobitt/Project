<?php
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','sysorg');//模块标识
require_once(CUR_CONF_PATH . 'lib/functions.php');
require_once(ROOT_DIR.'global.php');
class sysorgApi extends cronBase
{
	function __construct()
	{
		parent::__construct();
		include_once(CUR_CONF_PATH . 'lib/company.class.php');
        $this->obj = new company();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '自动同步权限分类',	 
			'brief' => '收录直播视频',
			'space' => '3600',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		$data = $this->obj->sysOrg();
		hg_pre($data);
	}
}

$out = new sysorgApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();


?>