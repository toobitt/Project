<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH . 'global.php');
define('MOD_UNIQUEID', 'cdn'); //模块标识
require_once(CUR_CONF_PATH . '/lib/Core.class.php');
require_once(CUR_CONF_PATH . '/lib/ICdnConf.php');
require_once(CUR_CONF_PATH . '/lib/ICdnFile.php');
class pushAPI extends cronBase
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
			'name' => '数据推送',	 
			'brief' => '对推送失败的数据再次推送',
			'space' => '18',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		global $gGlobalConfig;
		foreach ($gGlobalConfig['cdn']['type'] as $type)
		{
			include CUR_CONF_PATH."lib/".$type.".class.php";
			$typeobj = $type."obj";
			if(!$this->$typeobj)
				$this->$typeobj = new $type();
		}
		$id = $this->input['id'];
		$db = new Core();
		$datas = $db->show('cdn_log',$cond=' where state=0 limit 0,100',$fields='*');
		foreach($datas as $data)
		{
			$cdntype = $data['type']."obj";
            $this->$cdntype->check_curl();
			$this->$cdntype->delete($data['id']);
			$this->$cdntype->pushfordb(unserialize($data['data']));
		}
        $this->$cdntype->close_curl();
		$this->addItem($cdntype);
		$this->output();
	}	
}

$out = new pushAPI();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>