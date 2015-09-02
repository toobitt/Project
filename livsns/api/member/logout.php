<?php
/***************************************************************************
* $Id: logout.php 19006 2013-03-21 09:07:30Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('UC_CLIENT_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class memberLogoutApi extends appCommonFrm
{
	private $mMember;
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/member.class.php';
		$this->mMember = new member();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 会员退出
	 * Enter description here ...
	 */
	public function logout()
	{
		$info = $this->mMember->logout(intval($this->input['member_id']), trim(urldecode($this->input['token'])));
		$this->addItem($info);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
	

}

$out = new memberLogoutApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'logout';
}
$out->$action();
?>