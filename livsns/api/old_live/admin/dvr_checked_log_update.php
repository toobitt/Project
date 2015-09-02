<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: dvr_checked_log_update.php 17632 2013-02-23 08:53:47Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','dvr_checked_log');
require('global.php');
class dvrCheckedLogUpdateApi extends adminUpdateBase
{
	private $mDvrCheckedLog;
	function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/dvr_checked_log.class.php';
		$this->mDvrCheckedLog = new dvrCheckedLog();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function delete()
	{
		$id = urldecode($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		$ret = $this->mDvrCheckedLog->delete($id);
		if (!$ret)
		{
			$this->errorOutput('删除失败');
		}
		$this->addItem($id);
		$this->output();
	}
	
	public function create()
	{
		
	}
	
	public function update()
	{
		
	}
	
	public function audit()
	{
		
	}
	
	public function sort()
	{
		
	}
	
	public function publish()
	{
		
	}
	
	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
}

$out = new dvrCheckedLogUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>