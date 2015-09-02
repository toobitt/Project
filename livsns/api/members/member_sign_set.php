<?php
define('MOD_UNIQUEID','member_sign_set');//模块标识
require('./global.php');
require CUR_CONF_PATH . 'lib/member_sign_set.class.php';
class member_signsetApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->signset = new signset();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 签到配置展示
	 *
	 */
	public function show()
	{
		$info 	= $this->signset->show();

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}

		$this->output();
	}
	
	public function detail()
	{
	}
	
	public function count()
	{

	}

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

}

$out = new member_signsetApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>