<?php
/***************************************************************************
* $Id: member_avatar.php 19006 2013-03-21 09:07:30Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('UC_CLIENT_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class memberAvatarApi extends appCommonFrm
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
	
	public function avatarEdit()
	{
		$member_id = intval($this->input['member_id']);
		if (!$member_id)
		{
			$this->errorOutput('未传入会员id');
		}
		
		$files = $_FILES['avatar'];
		if (!$files['tmp_name'])
		{
			$this->errorOutput('请选择头像');
		}
		
		$info = $this->mMember->avatarEdit($member_id, $files);
		$this->addItem($info);
		$this->output();
	}
	
	public function avatarDelete()
	{
		$avatar_id = intval($this->input['avatar_id']);
		if(!$avatar_id)
		{
			$this->errorOutput('未传入头像ID');
		}
		$info = $this->mMember->avatarDelete($avatar_id);
		$this->addItem($info);
		$this->output();
	}
	
	public function avatarSwitch() 
	{
		$oavatar_id = intval($this->input['avatar_id']);
		if(!$oavatar_id)
		{
			$this->errorOutput('NOAVATARID');
		}
		$info = $this->mMember->avatarSwitch($oavatar_id);
		$this->addItem($info);
		$this->output();	
	}
}

$out = new memberAvatarApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'avatarEdit';
}
$out->$action();
?>