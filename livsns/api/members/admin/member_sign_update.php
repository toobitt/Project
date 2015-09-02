<?php
define('MOD_UNIQUEID','member_sign');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_sign.class.php';
require_once(ROOT_PATH.'lib/class/material.class.php');
class member_signUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->sign = new sign();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		
	}

	/**
	 *
	 * 更新
	 */
	public function update()
	{

	}

	/**
	 * 删除
	 */
	public function delete()
	{
				
	}
	/**
	 * 
	 * 屏蔽今日最想说内容
	 */
	public function ban()
	{
		$id=trim($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		$ret = $this->sign->ban($id);
		$this->addItem($ret);
		$this->output();
	}

	public function audit()
	{
		//
	}
	public function sort()
	{
	}
	public function publish()
	{
		//
	}

	/**
	 * 处理提交的数据
	 */
	private function filter_data()
	{
	}


	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new member_signUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>