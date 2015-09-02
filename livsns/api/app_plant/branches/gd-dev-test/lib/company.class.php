<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: applant.class.php 387 2011-03-01 03:35:15Z chengqing $
***************************************************************************/

class CompanyApi
{
	private $curl;
	
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_company']['host'], $gGlobalConfig['App_company']['dir']);
	}

	public function __destruct()
	{
	    unset($this->curl);
	}
	
	public function modifyUserPushStatus($user_id,$status = 1)
	{
		if(!$user_id)
		{
			return;
		}
		
        $this->curl->setSubmitType('get');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','modifyUserPushStatus');
        $this->curl->addRequestData('user_id',$user_id);
        $this->curl->addRequestData('status',$status);
        $this->curl->request('user.php');
	}
	
	public function getUserInfoByUserId($user_id = '')
	{
	    if(!$user_id)
		{
			return;
		}
		
		$this->curl->setSubmitType('get');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','detail');
        $this->curl->addRequestData('uid',$user_id);
        $ret = $this->curl->request('user.php');
        if($ret && isset($ret[0]))
        {
            $ret = $ret[0];
        }
		return $ret;
	}
	
	//设置叮当用户相关字段
	public function setUserInfo($data = array())
	{
	    if(!$data)
	    {
	        return FALSE;
	    }
        $this->curl->setSubmitType('post');
        $this->curl->initPostData();
        foreach ($data AS $k => $v)
        {
            $this->curl->addRequestData($k,$v);
        }
        $ret = $this->curl->request('user.php');
        if($ret && is_array($ret) && isset($ret[0]))
        {
            $ret = $ret[0];
        }
		return $ret;
	}
	
	/**
	 * 保存自动创建leancloud应用时获得的key与id
	 * @param number $user_id
	 * @param string $app_id
	 * @param string $app_key
	 * @return multitype:|Ambigous <>
	 */
	public function saveLeancloudParam($name = '',$user_id = 0 , $app_id = '' , $app_key = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $user_id);
		$this->curl->addRequestData('app_name', $name);
		$this->curl->addRequestData('app_id', $app_id);
		$this->curl->addRequestData('app_key', $app_key);
		$this->curl->addRequestData('push_accounts_id', 3);
		$this->curl->addRequestData('push_status', 5);
		$this->curl->addRequestData('a', 'update');
		$result = $this->curl->request('admin/dingdone_user_update.php');
		return $result[0];
	}
	
	/**
	 * 根据USERID获取推送信息
	 */
	public function getPushApiConfig($user_id = 0)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('a', 'getPushApiInfo');
		$result = $this->curl->request('user.php');
		return $result[0];
	}
	
	/**
	 * 将masterkey更新到 push_api_config中
	 */
	public function updateMasterkey($user_id = 0 , $master_key = "")
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('master_key', $master_key);
		$this->curl->addRequestData('a', 'updateMasterkey');
		$result = $this->curl->request('admin/dingdone_user_update.php');
		return $result[0];
	}
	
	/**
	 * 获取用户总数
	 */
	public function getAllUserCount()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getAllUserCount');
		$result = $this->curl->request('user.php');
		return $result[0];
	}
	
	public function getDayNewUsers()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getDayNewUsers');
		$result = $this->curl->request('user.php');
		return $result[0];
	}
	
}