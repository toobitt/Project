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
	public function updateMasterkey($user_id = 0 , $data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('user_id', $user_id);
        if ($data && is_array($data))
        {
            foreach ($data as $k => $v)
            {
                if (is_array($v))
                {
                    $this->array_to_add($k, $v);
                }
                else
                {
                    $this->curl->addRequestData($k, $v);
                }
            }
        }
		$this->curl->addRequestData('a', 'updateMasterkey');
		$result = $this->curl->request('admin/dingdone_user_update.php');
		return $result[0];
	}
	
	/**
	 * 获取用户总数
	 */
	public function getAllUserCount($start_date = 0 , $end_date = 0)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('start_date', $start_date);
		$this->curl->addRequestData('end_date', $end_date);
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
    
    public function getIsDeveloperNums($start_time = 0 , $end_time = 0)
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('get');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('a', 'getIsDeveloperNums');
    	$this->curl->addRequestData('start_time', $start_time);
    	$this->curl->addRequestData('end_time', $end_time);
    	$result = $this->curl->request('user.php');
    	return $result[0];
    } 
    
    public function getIsPushNums()
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('get');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('a', 'getIsPushNums');
    	$result = $this->curl->request('user.php');
    	return $result[0];
    }
    
    /**
     * 获取用户总数
     */
    public function getAllActivateUsers($start_date = 0 , $end_date = 0)
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('get');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('start_date', $start_date);
    	$this->curl->addRequestData('end_date', $end_date);
    	$this->curl->addRequestData('a', 'getAllActivateUsers');
    	$result = $this->curl->request('user.php');
    	return $result[0];
    }
    
    public function getLiushiUserCount()
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('get');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('a', 'getLiushiUserCount');
    	$result = $this->curl->request('user.php');
    	return $result[0];
    }
    
    public function getTodayAddInfo()
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('get');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('a', 'getTodayAddInfo');
    	$result = $this->curl->request('user.php');
    	return $result[0];
    }
	
    public function getTodayActivateInfo()
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('get');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('a', 'getTodayActivateInfo');
    	$result = $this->curl->request('user.php');
    	return $result[0];
    }
    
    public function getTodayPushInfo()
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('get');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('a', 'getTodayPushInfo');
    	$result = $this->curl->request('user.php');
    	return $result[0];
    }
    
    public function getIsPushNumsInDate($start_time = 0,$end_time = 0)
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('get');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('a', 'getIsPushNumsInDate');
    	$this->curl->addRequestData('start_time', $start_time);
    	$this->curl->addRequestData('end_time', $end_time);
    	$result = $this->curl->request('user.php');
    	return $result[0];
    }
    
    public function getActivateInfoIndate($start_time = 0,$end_time = 0)
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('get');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('a', 'getActivateInfoIndate');
    	$this->curl->addRequestData('start_time', $start_time);
    	$this->curl->addRequestData('end_time', $end_time);
    	$result = $this->curl->request('user.php');
     	return $result[0];
    }
    
    public function getLostInfoIndate($start_time = 0,$end_time = 0)
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('get');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('a', 'getLostInfoIndate');
    	$this->curl->addRequestData('start_time', $start_time);
    	$this->curl->addRequestData('end_time', $end_time);
    	$result = $this->curl->request('user.php');
    	return $result[0];
    }
    
    public function getTodayAddDevelopInfo($start_time = 0,$end_time = 0)
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('get');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('a', 'getTodayAddDevelopInfo');
    	$this->curl->addRequestData('start_time', $start_time);
    	$this->curl->addRequestData('end_time', $end_time);
    	$result = $this->curl->request('user.php');
    	return $result[0];
    }
}