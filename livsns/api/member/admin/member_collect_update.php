<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: member_collect_update.php 19006 2013-03-21 09:07:30Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member_collect');//模块标识
require('global.php');
class memberCollectUpdateApi extends adminUpdateBase
{
	private $mMemberCollect;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/member_collect.class.php';
		$this->mMemberCollect = new memberCollect();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		
	}
	public function update()
	{
		
	}
	
	public function delete()
	{
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$info = $this->mMemberCollect->delete($id);
		if (!$info)
		{
			$this->errorOutput('删除失败');
		}
		$this->addItem($id);
		$this->output();
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
		$this->errorOutput('未定义的空方法');
	}
}

$out = new memberCollectUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>