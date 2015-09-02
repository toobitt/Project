<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: webapp_update.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/webapp.class.php';
define('MOD_UNIQUEID', 'webapp'); //模块标识

class webappUpdateApi extends adminUpdateBase
{
	private $webapp;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->webapp = new webappClass();
		
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->webapp);
	}
	

	/**
	** 信息更新操作
	**/
	public function update()
	{
		
	}
	
	public function delete()
	{
		$ids = trim(urldecode($this->input['id']));
		$ids = hg_filter_ids($ids);
		if(empty($ids))
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$result = $this->webapp->delete($ids);
		$this->addItem($result);
		$this->output();
	}

	public function create()
	{
		
	}
	

	
	//审核
	public function audit()
	{
		
	}

	public function publish()
	{
		
	}

	public function sort()
	{
		
	}

	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
}
$out = new webappUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>