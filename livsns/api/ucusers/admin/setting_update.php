<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: setting_update.php 6862 2012-05-29 07:07:58Z lijiaying $
***************************************************************************/
require('global.php');
class settingUpdateApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		require(CUR_CONF_PATH . 'lib/setting.class.php');
		$this->mSetting = new setting();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/*基本设置*/
	public function settingBasic()
	{
		$info = $this->mSetting->settingBasic();
		$this->addItem($info);
		$this->output();
	}

	/*注册设置*/
	public function settingRegister()
	{
		$info = $this->mSetting->settingRegister();
		$this->addItem($info);
		$this->output();
	}
	/*邮件设置*/
	public function settingMail()
	{
		$info = $this->mSetting->settingMail();
		$this->addItem($info);
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput('空方法');
	}
}

$out = new settingUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>