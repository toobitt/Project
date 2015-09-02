<?php
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('SCRIPT_NAME', 'openid');
require(ROOT_PATH."global.php");
class openid extends appCommonFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->curlAuth = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$openid = $this->input['openid'];
		if(!$openid)
		{
			$this->errorOutput('OPENID IS LOST');
		}
		$sql = 'SELECT id as member_id,uc_id,nick_name,openid FROM '.DB_PREFIX.'member WHERE openid = "'.$openid.'"';
		$member = $this->db->query_first($sql);
		if(!$member)
		{
			$this->errorOutput('User is not bind');
		}
		else
		{
			$appid = $this->input['appid'];
			$appkey = $this->input['appkey'];
			$this->curlAuth->setSubmitType('post');
			$this->curlAuth->setReturnFormat('json');
			$this->curlAuth->addRequestData('a', 'show');
			$this->curlAuth->addRequestData('appid', $appid);
			$this->curlAuth->addRequestData('appkey', $appkey);
			$this->curlAuth->addRequestData('ip', hg_getip());
			$this->curlAuth->addRequestData('user_name', $member['nick_name']);
			$this->curlAuth->addRequestData('id', $member['member_id']);
			$this->curlAuth->addRequestData('admin_group_id', 0);
			$this->curlAuth->addRequestData('group_type', 0);
			$ret = $this->curlAuth->request('get_access_token.php');
			$token = $ret[0];
		}
		unset($member['openid']);
		$member['token'] = $token['token'];
		$this->addItem($member);
		$this->output();
	}
}
include ROOT_PATH . 'excute.php';