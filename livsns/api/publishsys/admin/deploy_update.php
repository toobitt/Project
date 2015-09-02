<?php
require('global.php');
define('MOD_UNIQUEID','deploy');//模块标识
class deployUpdateApi extends adminBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/deploy.class.php');
		$this->obj = new deploy();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function create()
	{	
		$ret = $this->obj->addLogs();
		$this->addItem($ret);
		$this->output();
	}

	function delete()
	{			
		$ids = urldecode($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput("请输入需要删除得日志id");
		}
		$ret = $this->obj->delete($ids);
		$this->addItem($ret);
		$this->output();
		
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new deployUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>