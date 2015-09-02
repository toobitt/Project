<?php
define('MOD_UNIQUEID','configSet');//模块标识
require('./global.php');
class configSetApi extends appCommonFrm
{
	private $configSet;
	public function __construct()
	{
		parent::__construct();
		$this->configSet = new configSet();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 *
	 * 获取配置
	 */
	public function getConfig()
	{
		$app_uniqueid = trim($this->input['app_uniqueid']);
		$config = $this->configSet->getConfig(array($app_uniqueid));
		foreach ($config as $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

}

$out = new configSetApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>