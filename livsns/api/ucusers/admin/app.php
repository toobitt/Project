<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: app.php 6902 2012-05-30 05:15:54Z lijiaying $
***************************************************************************/
require('global.php');
class appApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		require(CUR_CONF_PATH . 'lib/app.class.php');
		$this->mApp = new app();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$info = $this->mApp->getAppInfo();
		$this->addItem($info);
		$this->output();
	}

	public function detail()
	{
		if ($this->input['id'])
		{
			$row = $this->mApp->detail();
			$row['conf'] = $this->mApp->getConfig();
			$row['conf']['UC_CONNECT'] = 'mysql';
			$row['conf']['UC_KEY'] = $row['authkey'];
			$row['conf']['UC_APPID'] = $row['appid'];

			$this->addItem($row);
			$this->output();
		}
		else
		{
			$this->errorOutput('该应用不存在');	
		} 
		
	}
	
}

$out = new appApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>