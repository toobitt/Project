<?php
define('MOD_UNIQUEID','member_medal');//模块标识
require('./global.php');
require CUR_CONF_PATH . 'lib/member_medal.class.php';
class member_medalUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->medal = new medal();
		$this->Members=new members();
		$this->membersql = new membersql();

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

	public function audit()
	{
		$id=$this->input['id'];
		if(empty($id))
		{
			$this->errorOutput(NO_DATA_ID);
		}
		$this->addItem($this->medal->audit_member_medal(explode(',',$id), intval($this->input['type'])));
		$this->output();
	}
	public function sort()
	{
	}
	public function publish()
	{
	}


	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new member_medalUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>