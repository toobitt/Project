<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: phone.php 17947 2013-02-26 02:57:46Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class phoneApi extends appCommonFrm
{
	private $mUser;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->mUser = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 
	 * 手机号码绑定
	 * @param $tel
	 * return $info 用户部分信息
	 */
	public function create()
	{
		$userinfo = $this->mUser->verify_credentials();
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$tel = $this->input['tel']?urldecode($this->input['tel']):0;
		if(!$tel)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "select id,username,password,cellphone from ".DB_PREFIX."member where cellphone = '".$tel."'";
		$y = $this->db->query_first($sql);
		if($y['id'])
		{
			$this->setXmlNode('user','info');
			$this->addItem("have");
			$this->output();
		}
		
		$sql = "select id,username,password,cellphone from ".DB_PREFIX."member where id = ".$userinfo['id'];
		$f = $this->db->query_first($sql);
		
		if($f['cellphone'] == $tel)
		{
			$this->setXmlNode('user','info');
			$this->addItem($f);
			$this->output();
		}
		else
		{
			$sql = "update ".DB_PREFIX."member set cellphone='".$tel."' where id=".$userinfo['id'];
			$this->db->query($sql);
			$f['cellphone'] = $tel;
			$this->setXmlNode('user','info');
			$this->addItem($f);
			$this->output();
		}
	}
	
	/**
	 * 
	 * 解除绑定
	 * return $info 用户id
	 */
	public function del()
	{
		$userinfo = $this->mUser->verify_credentials();
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$sql = "update ".DB_PREFIX."member set cellphone=0 where id=".$userinfo['id'];
		$this->db->query($sql);
		$this->setXmlNode('user','info');
		$this->addItem($userinfo['id']);
		$this->output();
	}
	
	/**
	 * 根据绑定手机号码返回用户登录信息
	 * @param $tel 
	 * return $info 用户部分信息
	 */
	public function show()
	{
		$tel = $this->input['tel']?urldecode($this->input['tel']):0;
		if(!$tel)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "select id,username,password,cellphone from ".DB_PREFIX."member where cellphone = '".$tel."'";
		$f = $this->db->query_first($sql);
		if($f['cellphone'])
		{
			$this->setXmlNode('user','info');
			$this->addItem($f);
			$this->output();
		}
		else 
		{
			$sql = "select id,username,password,cellphone from ".DB_PREFIX."member where id = ".MOBILE_DEFALT_USER;
			$sen = $this->db->query_first($sql);
			$this->setXmlNode('user','info');
			$this->addItem($sen);
			$this->output();
		}
	}
}
$out = new phoneApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>