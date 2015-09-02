<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: dingdoneuser.class.php 387 2011-03-01 03:35:15Z chengqing $
***************************************************************************/

class dingdoneuser
{
	private $curl;
	
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_dingdoneuser']['host'], $gGlobalConfig['App_dingdoneuser']['dir']);
	}

	public function __destruct()
	{
	    unset($this->curl);
	}
	
	/**
	 * 验证邀请码
	 * @param string $code
	 */
	public function validateCode($code)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('code', $code);
		$this->curl->addRequestData('a', 'validate');
		$result = $this->curl->request('invitationCode.php');
		return $result[0];
	}
	
	/**
	 * 更新邀请码状态
	 * @param string $code
	 * @param integer $user_id
	 */
	public function updateStatus($code, $user_id)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('code', $code);
		$this->curl->addRequestData('uid', $user_id);
		$this->curl->addRequestData('a', 'update');
		$result = $this->curl->request('invitationCode.php');
		return $result[0];
	}
	
	/**
	 * 创建邀请码
	 * @param int $num
	 */
	public function createCode($num = 1)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('num', $num);
		$this->curl->addRequestData('a', 'create');
		$result = $this->curl->request('admin/invitationCode.php');
		return $result[0];
	}
	
	/**
	 * 发送邀请码
	 * @param int $id
	 */
	public function sendCode($id)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('a', 'sendTo');
		$result = $this->curl->request('admin/invitationCode.php');
		return $result[0];
	}
	
	private function array_to_add($str, $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if (is_array($vv))
				{
					$this->array_to_add($str . "[$kk]" , $vv);
				}
				else
				{
					$this->curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
	}
}