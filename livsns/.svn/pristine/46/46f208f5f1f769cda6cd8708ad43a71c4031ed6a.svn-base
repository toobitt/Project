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
define('MOD_UNIQUEID','recommond');//模块标识
require('global.php');
class columnUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/recommond.class.php');
		$this->obj = new recommond();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->obj);
	}

	public function create()
	{	
		if(empty($this->input['name']))
		{
			$this->errorOutput("请传入名称！");
		}
		$ret = $this->obj->add_column();
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput("请传入ID！");
		}
		if(empty($this->input['name']))
		{
			$this->errorOutput("请传入名称！");
		}
		
		$ret = $this->obj->update_column();
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput("请传入ID！");
		}
		$ret = $this->obj->delete_column();
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

$out = new columnUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>	