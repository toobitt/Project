<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: applant.class.php 387 2011-03-01 03:35:15Z chengqing $
***************************************************************************/

class company
{
	private $curl;
	
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$host = $gGlobalConfig['App_company']['host'];
		$dir = $gGlobalConfig['App_company']['dir'];
		$this->curl = new curl($host, $dir);
	}

	public function __destruct()
	{
	    unset($this->curl);
	}
	
	/**
	 * 根据用户id获取站点id
	 * @param integer $uid
	 */
	public function getSiteByUser($uid)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('uid', $uid);
		$this->curl->addRequestData('a', 'detail');
		$result = $this->curl->request('user.php');
		return $result[0];
	}
	
	/**
	 * 获取推送配置
	 * @param int $user_id
	 */
	public function getPushApi($user_id)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('a', 'getPushApiConfig');
		$result = $this->curl->request('user.php');
		return $result[0];
	}

    /**
     * 获取推送配置
     * @param int $user_id
     */
    public function getPushApiByuids($user_ids)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('user_id', $user_ids);
        $this->curl->addRequestData('a', 'getPushApiConfigByUids');
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
}