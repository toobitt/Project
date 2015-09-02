<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: applant.class.php 387 2011-03-01 03:35:15Z chengqing $
***************************************************************************/

class applant
{
	private $curl;
	
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_app_plant']['host'], $gGlobalConfig['App_app_plant']['dir']);
	}

	public function __destruct()
	{
	    unset($this->curl);
	}
	
	/**
	 * 获取模板风格数据
	 * @param Array $data
	 */
	public function getTemplate($data)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
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
		$this->curl->addRequestData('a', 'show');
		$result = $this->curl->request('app_template.php');
		return $result;
	}
	
	/**
	 * 获取界面数据
	 * @param Array $data
	 */
	public function getInterface($data)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
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
		$this->curl->addRequestData('a', 'show');
		$result = $this->curl->request('app_interface.php');
		return $result;
	}
	
	/**
	 * 获取正文模板数据
	 */
	public function getBodyTpl()
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$result = $this->curl->request('body_tpl.php');
		return $result;
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
	/**
	 * 获取该应用所属用户的相关信息
	 * @param unknown $app_id
	 * @return multitype:|Ambigous <string, unknown>
	 */
	public function getUserInfoByAppId($app_id){
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'detail');
		$this->curl->addRequestData('id', $app_id);
		$this->curl->addRequestData('flag', 1);
		$result = $this->curl->request('app.php');
		foreach ($result as $key =>$val){
			if($val['id']==$app_id){
				$ret = $val;
			}
		}
		return $ret;
	}
	/**
	 * 根据appid得到模块信息
	 * @param unknown $module_id模块ID
	 * @return multitype:|Ambigous <>
	 * @author jitao
	 */
	public function getModuleInfoByAppIdAndModuleId($module_id){
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'detail');
		$this->curl->addRequestData('id', $module_id);
		$this->curl->addRequestData('flag', 1);
		$result = $this->curl->request('app_module.php');
		return $result[0];
	}

    /**
     * 根据顶级栏目id获取对应模块数据
     * @param int $column_id 栏目id
     * @return array 栏目信息
     * @authorjitao
     */
    public function getAllModuleByColumnIds($ids)
    {
        if (!$this->curl) return array();
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('ids', $ids);
        $this->curl->addRequestData('a', 'getAllModuleByIds');
        $result = $this->curl->request('app_module.php');
        if($result && isset($result[0]))
        {
            $result = $result[0];
        }
        return $result;
    }

	/**
	 * 根据模块获取栏目(除webview类型的模块)
	 */
	public function getColumnsByModule($app_id)
	{
		if (!$this->curl) return array();
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('app_id', $app_id);
		$this->curl->addRequestData('a', 'getColumnsByModule');
		$result = $this->curl->request('app_module.php');
		return $result;
	}
	
	/**
	 * 更新iOS端发布版与测试版的数量
	 * @param string $app_id
	 * @param string $source
	 * @return multitype:|Ambigous <>
	 */
	public function updateIosCounts($app_id = "" , $debug = "")
	{
		if (!$this->curl)
		{	
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'updateIosCounts');
		$this->curl->addRequestData('app_id', $app_id);
		$this->curl->addRequestData('debug', $debug);
		$result = $this->curl->request('app.php');
		return $result[0];
	}
	
	/**
	 * 获取appinfo
	 * @param unknown $appid
	 * @return unknown
	 */
	public function getAppinfo($appid)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','detail');
		$this->curl->addRequestData('id', $appid);
		$this->curl->addRequestData('flag', true);
		$ret = $this->curl->request('app.php');
		return $ret[0];
	}
	
	/**
	 * 获取appinfo
	 * @param unknown $appid
	 * @return unknown
	 */
	public function getAppinfoByAppids($appid)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getAppsByIds');
		$this->curl->addRequestData('id', $appid);
		$ret = $this->curl->request('app.php');
		return $ret;
	}
	
	/**
	 * 获取appinfo
	 * @param unknown $guid
	 * @return unknown
	 */
	public function getAppinfoByGuid($guid)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','getAppInfoByGuid');
        $this->curl->addRequestData('guid', $guid);
        $ret = $this->curl->request('app.php');
	    return $ret[0];
	}
	
	/**
	 * 获取appinfo
	 * @param unknown user_id
	 * @return unknown
	 */
	public function getAppinfoByuid($user_id)
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
	    $this->curl->setSubmitType('get');
	    $this->curl->setReturnFormat('json');
	    $this->curl->initPostData();
	    $this->curl->addRequestData('a','getAppByUserId');
	    $this->curl->addRequestData('user_id', $user_id);
	    $ret = $this->curl->request('app.php');
	    if($ret && isset($ret[0]))
        {
        	return $ret[0];
        }
        else 
        {
        	return array();
        }
	}
	
	/**
	 * 获取推送账号
	 * @param unknown user_id
	 * @return unknown
	 */
	public function getPushAccounts()
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
	    $this->curl->setSubmitType('get');
	    $this->curl->setReturnFormat('json');
	    $this->curl->initPostData();
	    $ret = $this->curl->request('push_accounts.php');
	    if($ret && isset($ret))
	    {
	        return $ret;
	    }
	    else
	    {
	        return array();
	    }
	}
	
	/**
	 * 获取扩展字段相关设置
	 */
	public function getCatalogNumLimit($user_id = 0)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getCatalogNumLimit');
		$this->curl->addRequestData('user_id', $user_id);
		$ret = $this->curl->request('app.php');
		if($ret && isset($ret))
		{
			return $ret[0];
		}
		else
		{
			return array();
		}
	}
}