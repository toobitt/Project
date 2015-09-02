<?php
define('MOD_UNIQUEID','member_set');//模块标识
require('./global.php');
class member_setApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 新会员配置输出
	 *
	 */
	public function show()
	{
		$data['is_verifycode']=IS_VERIFYCODE;
		$this->addItem($data);
		$this->output();
	}
	public function detail()
	{
	}
	public function count()
	{

	}


}

$out = new member_setApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>