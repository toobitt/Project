<?php
class verifyCode
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if($gGlobalConfig['App_verifycode'])
		{
			$this->curl = new curl($gGlobalConfig['App_verifycode']['host'], $gGlobalConfig['App_verifycode']['dir']);
		}
		$this->curls = new curl($gGlobalConfig['App_verifycode']['host'], $gGlobalConfig['App_verifycode']['dir'] . 'admin/');
	}

	function __destruct()
	{
	
	}
	
	/**
	 * 获取验证码种类
	 * Enter description here ...
	 */
	public function get_verify_type()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','show');
		$ret = $this->curl->request('verifycode.php');
		return $ret;
	}
	
	/**
	 * 
	 * 获取验证码的设置.
	 * @param 验证码种类 $id
	 */
	public function get_verify_type_setting($id)
	{
		if (!$this->curls)
		{
			return array();
		}
		$this->curls->setSubmitType('post');
		$this->curls->setReturnFormat('json');
		$this->curls->initPostData();
		$this->curls->addRequestData('a','detail');
		$this->curls->addRequestData('id',$id);
		$ret = $this->curls->request('verify_code.php');
		return $ret[0];
		
	}
	/**
	 * 获取验证码
	 * $code 验证码值
	 * Enter description here ...
	 * @param unknown_type $salt
	 */
	public function get_verify_code($id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'set_verify_code');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('verifycode.php');
		return $ret[0];
	}
	
	/**
	 * (重新)生成验证码
	 * $salt 重新生成
	 * $length 验证码长度 默认 6
	 * Enter description here ...
	 * @param unknown_type $data
	 
	public function set_verify_code($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'set_verify_code');
		
		if (empty($data))
		{
			return array();
		}
		
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		
		$ret = $this->curl->request('verify_code_update.php');
		return $ret[0];
	}
	*/
	
	/**
	 * 验证验证码
	 * $code 验证码值
	 * Enter description here ...
	 */
	public function check_verify_code($code,$session_id)
	{
		if (!$this->curl)
		{
			return false;
		}
		if(!$session_id)
		{
			return '验证码提交失败';
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'check_verify_code');
		$this->curl->addRequestData('session_id', $session_id);
		$this->curl->addRequestData('code', $code);
		$ret = $this->curl->request('verifycode.php');
		return $ret[0];
	}
}
?>
