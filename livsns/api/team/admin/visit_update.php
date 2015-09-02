<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: news.php 13202 2012-10-27 12:32:11Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','visit');//模块标识
require('global.php');
class visitUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/visit.class.php');
		$this->obj = new visit();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->obj);
	}

	public function create()
	{
		$ret = $this->obj->create();
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		
	}
	
	public function delete()
	{
		$cid = urldecode($this->input['cid']);
		$source = trim($this->input['source']);
		if(empty($cid))
		{
			$this->errorOutput("未传入内容ID！");
		}
		if(empty($source))
		{
			$this->errorOutput("未传入来源！");
		}
		$ret = $this->obj->delete($cid,$source);
		if(empty($ret))
		{
			$this->errorOutput("删除有误！");
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function audit()
	{
		
	}
	
	public function publish()
	{
		
	}
	
	public function sort()
	{
		
	}

	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new visitUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>	