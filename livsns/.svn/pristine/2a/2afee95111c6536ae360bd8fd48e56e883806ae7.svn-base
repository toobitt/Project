<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: vote.php 6440 2012-04-17 09:29:53Z lijiaying $
***************************************************************************/
require('global.php');
class settingApi extends BaseFrm
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

	public function show()
	{
		$info = $this->mSetting->getSettingInfo();
		
		$this->addItem($info);
		$this->output();
	}

	public function detail()
	{
		$id = urldecode($this->input['id']);
		if($id)
		{
			$info = $this->mSetting->detail();
			
			$this->addItem($row);
			$this->output();
		}
		else
		{
			$this->errorOutput('设置信息不存在');	
		} 	
	}

}

$out = new settingApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>