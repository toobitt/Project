<?php
/***************************************************************************
* $Id: email_log_update.php 17907 2013-02-25 05:48:25Z repheal $
***************************************************************************/
define('MOD_UNIQUEID', 'email_log');
require('global.php');
class emailLogUpdateApi extends adminUpdateBase
{
	private $mEmailLog;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/email_log.class.php';
		$this->mEmailLog = new emailLog();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function delete()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		$info = $this->mEmailLog->delete($id);
		
		if (!$info)
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
	
	function unknow()
	{
		$this->errorOutput('未实现的空方法');
	}
}

$out = new emailLogUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>