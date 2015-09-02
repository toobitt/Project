<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user.class.php 2953 2011-03-21 02:15:21Z chengqing $
***************************************************************************/
class email
{
	private $curl;
	function __construct()
	{
		global $gApiConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gApiConfig['host'], $gApiConfig['apidir']);
	}

	function __destruct()
	{
	}

	/*
	 * 验证邮箱链接
	 */
	public function checkLink(&$data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		foreach($data as $key => $value)
		{
			$this->curl->addRequestData( $key, $value);
		}
		$ret = $this->curl->request('users/check_link.php');
		return $ret[0];
	}

	/*
	 * 发邮件，进行账号验证
	 */
	public function send_link(&$data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		foreach($data as $key => $value)
		{
			$this->curl->addRequestData( $key, $value);
		}
		$ret = $this->curl->request('users/email.php');
		return $ret[0];
	}
	

	/*
	 * 更新邮箱
	 */
	public function update_email(&$data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		foreach($data as $key => $value)
		{
			$this->curl->addRequestData( $key, $value);
		}
		$ret = $this->curl->request('users/check_link.php');
		return $ret[0];
	}
	/*
	 * 更新密码
	 */
	public function update_pwd(&$data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		foreach($data as $key => $value)
		{
			$this->curl->addRequestData( $key, $value);
		}
		$ret = $this->curl->request('users/check_link.php');
		return $ret[0];
	}
	
}
?>