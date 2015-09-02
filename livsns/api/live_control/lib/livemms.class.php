<?php
/***************************************************************************
* $Id: livemms.class.php 32028 2013-11-28 05:18:11Z tong $
***************************************************************************/
class livemms extends InitFrm
{
	private $mLiveServer;
	private $mRecordServer;
	public function __construct()
	{
		parent::__construct();

		include_once(ROOT_PATH . 'lib/class/curl.class.php');
		$this->mLiveServer = new curl();
		$this->mLiveServer->mPostContentType('string');
		
		$this->mRecordServer = new curl();
		$this->mRecordServer->mPostContentType('string');
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 输入层 创建 更新 删除 查询 启动 停止
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $apidir
	 * @param unknown_type $action insert update delete select start stop
	 * @param unknown_type $id
	 * @param unknown_type $url
	 * @param unknown_type $type 1-推 0-拉
	 */
	function inputStreamOperate($host, $apidir, $data = array())
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		
		$action = array('insert', 'update', 'delete', 'select', 'start', 'stop');
		
		if (!in_array($data['action'], $action))
		{
			return false;
		}
		
		foreach ($data AS $k => $v)
		{
			$this->mLiveServer->addRequestData($k, $v);
		}
		
		$ret = $this->mLiveServer->request('input');

		return xml2Array($ret);
	}

	/**
	 * 延时层 创建 删除 查询 启动 停止
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $apidir
	 * @param unknown_type $action insert delete select start stop
	 * @param unknown_type $id
	 * @param unknown_type $inputId
	 * @param unknown_type $length
	 */
	function inputDelayOperate($host, $apidir, $data = array())
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
	
		$action = array('insert', 'delete', 'select', 'start', 'stop');
		
		if (!in_array($data['action'], $action))
		{
			return false;
		}
		
		foreach ($data AS $k => $v)
		{
			$this->mLiveServer->addRequestData($k, $v);
		}
		
		$ret = $this->mLiveServer->request('delay');

		return xml2Array($ret);
	}
	
	/**
	 * 输入输出层 创建 更新 删除 查询 启动 停止
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $apidir
	 * @param unknown_type $action insert update delete select start stop
	 * @param unknown_type $id
	 * @param unknown_type $url
	 * @param unknown_type $type 1-推 0-拉
	 */
	function inputOutputStreamOperate($host, $apidir, $data = array())
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		
		$action = array('insert', 'update', 'delete', 'select', 'start', 'stop');
		
		if (!in_array($data['action'], $action))
		{
			return false;
		}
		
		foreach ($data AS $k => $v)
		{
			$this->mLiveServer->addRequestData($k, $v);
		}
		
		$ret = $this->mLiveServer->request('output');

		return xml2Array($ret);
	}

	/**
	 * 切播层 创建 更新 删除 查询 启动 停止 切播
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $apidir
	 * @param unknown_type $action insert update delete select start stop change
	 * @param unknown_type $id
	 * @param unknown_type $sourceId 
	 * @param unknown_type $sourceType (1-input 2-delay) 
	 * @param unknown_type $notify (1-返回直播频道 0-返回直播信号)
	 */
	function inputChangeOperate($host, $apidir, $data = array())
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		
		$action = array('insert', 'update', 'delete', 'select', 'start', 'stop', 'change');
		
		if (!in_array($data['action'], $action))
		{
			return false;
		}
		
		foreach ($data AS $k => $v)
		{
			$this->mLiveServer->addRequestData($k, $v);
		}
		
		$ret = $this->mLiveServer->request('output');

		return xml2Array($ret);
	}
	
	/**
	 * 串联单 创建 删除 列表
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $apidir
	 * @param unknown_type $action insert delete list
	 * @param unknown_type $id 串联单id
	 * @param unknown_type $outputId change_id
	 * @param unknown_type $sourceId 来源id
	 * @param unknown_type $sourceType (1-input 2-delay 3-list)
	 * @param unknown_type $startTime 开始时间
	 * @param unknown_type $duration 时长
	 */
	function inputScheduleOperate($host, $apidir, $data = array())
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		
		$action = array('insert', 'delete', 'list');
		
		if (!in_array($data['action'], $action))
		{
			return false;
		}
		
		foreach ($data AS $k => $v)
		{
			$this->mLiveServer->addRequestData($k, $v);
		}
		
		
		$ret = $this->mLiveServer->request('schedule');

		return xml2Array($ret);
	}
	
	/**
	 * 备播文件 创建 删除
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $apidir
	 * @param unknown_type $action insert delete
	 * @param unknown_type $id
	 * @param unknown_type $url
	 * @param unknown_type $callback
	 */
	function inputFileOperate($host, $apidir, $data = array())
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
	
		$action = array('insert', 'delete');
		
		if (!in_array($data['action'], $action))
		{
			return false;
		}
		
		foreach ($data AS $k => $v)
		{
			$this->mLiveServer->addRequestData($k, $v);
		}
		
		$ret = $this->mLiveServer->request('file');

		return xml2Array($ret);
	}
	
	/**
	 * 文件流 创建 更新 删除 列表 启动 停止
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $apidir
	 * @param unknown_type $action insert update delete list
	 * @param unknown_type $id
	 * @param unknown_type $files 备播文件id (','隔开)
	 */
	function inputListOperate($host, $apidir, $data = array())
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		
		$action = array('insert', 'update', 'delete', 'list');
		
		if (!in_array($data['action'], $action))
		{
			return false;
		}
		
		foreach ($data AS $k => $v)
		{
			$this->mLiveServer->addRequestData($k, $v);
		}
		
		$ret = $this->mLiveServer->request('list');

		return xml2Array($ret);
	}
	
	/**
	 * 输出层应用创建、更新、删除、查询
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $apidir
	 * @param unknown_type $action 操作 insert update delete select
	 * @param unknown_type $id 应用id
	 * @param unknown_type $name 应用名
	 * @param unknown_type $length 时移
	 * @param unknown_type $drm 防盗链
	 * @param unknown_type $type 类型
	 */
	function outputApplicationOperate($host, $apidir, $data = array())
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		
		$action = array('insert', 'update', 'delete', 'select');
		
		if (!in_array($data['action'], $action))
		{
			return false;
		}
		
		foreach ($data AS $k => $v)
		{
			$this->mLiveServer->addRequestData($k, $v);
		}
		
		$ret = $this->mLiveServer->request('application');
			
		return xml2Array($ret);
	}
	
	/**
	 * 输出层信号 创建、更新、删除、查询、启动、停止
	 * Enter description here ...
	 * @param unknown_type $action insert update delete selete start stop
	 * @param unknown_type $id
	 * @param unknown_type $applicationId
	 * @param unknown_type $name
	 * @param unknown_type $url
	 */
	function outputStreamOperate($host, $apidir, $data = array())
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
	
		$action = array('insert', 'update', 'delete', 'select', 'start', 'stop');
		
		if (!in_array($data['action'], $action))
		{
			return false;
		}
		
		foreach ($data AS $k => $v)
		{
			$this->mLiveServer->addRequestData($k, $v);
		}
		
		$ret = $this->mLiveServer->request('stream');

		return xml2Array($ret);
	}
	
	/**
	 * 获取服务器时间
	 * Enter description here ...
	 */
	function outputNtpTime($host, $apidir)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'start');
		
		$ret = $this->mLiveServer->request('ntp');
		
		return xml2Array($ret);
	}
	/**
	 * 截图
	 * $streamId 输出层id ==> out_stream_id
	 * http://10.0.1.30:8086/outputmanager/Snapshot?action=start&streamId=	
	 * 
	 * 校时
	 * http://10.0.1.30:8086/outputmanager/ntp?action=start
	 * 返回毫秒
	 */
	function outputStreamSnap($host, $apidir, $id)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'start');
		
		$this->mLiveServer->addRequestData('streamId', $id);
		
		$ret = $this->mLiveServer->request('Snapshot');

		return xml2Array($ret);
	}

	/**
	 * 时移 删除
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $apidir
	 * @param unknown_type $action delete
	 * @param unknown_type $id
	 * @param unknown_type $time
	 * @param unknown_type $duration
	 * @param unknown_type $callback
	 */
	function dvrOperate($host, $apidir, $data = array())
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
	
		$action = array('delete');
		
		if (!in_array($data['action'], $action))
		{
			return false;
		}
		
		foreach ($data AS $k => $v)
		{
			$this->mLiveServer->addRequestData($k, $v);
		}
		
		$ret = $this->mLiveServer->request('dvr');

		return xml2Array($ret);
	}
	
	/**
	 * 时移抓取
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $apidir
	 * @param unknown_type $action TIMESHIFT
	 * @param unknown_type $id
	 * @param unknown_type $uploadFile 0
	 * @param unknown_type $access_token
	 * @param unknown_type $channel_id
	 * @param unknown_type $url
	 * @param unknown_type $callback
	 */
	function recordOperate($host, $apidir, $data = array())
	{
		if (!$this->mRecordServer)
		{
			return array();
		}
		$this->mRecordServer->setUrlHost($host, $apidir);
		$this->mRecordServer->setSubmitType('get');
		$this->mRecordServer->initPostData();
		$this->mRecordServer->setReturnFormat('json');
		
		$action = array('TIMESHIFT');
		
		if (!in_array($data['action'], $action))
		{
			return false;
		}
		
		foreach ($data AS $k => $v)
		{
			$this->mRecordServer->addRequestData($k, $v);
		}
		
		$ret = $this->mRecordServer->request('');

		return xml2Array($ret);
	}
}
?>