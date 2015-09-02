<?php
/***************************************************************************
* $Id: livemms.class.php 16887 2013-01-21 07:31:10Z lijiaying $
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
	 *
	 * Enter description here ...
	 */
	function inputStreamSelect($host, $apidir, $id='')
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'select');
		if ($id)
		{
			$this->mLiveServer->addRequestData('id', $id);
		}
		
		$ret = $this->mLiveServer->request('input');
		return xml2Array($ret);
	}
	/**
	 * 输入流 的 输出流 rtmp://ip/input/$id
	 * 延时层 的 输出流 trmp://ip/input/delay_$id
	 * 
	 * $url
	 * $type 0-拉  1-推
	 * 返回 id
	 */
	
	function inputStreamInsert($host, $apidir, $url, $type)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'insert');
		$this->mLiveServer->addRequestData('url', $url);
		$this->mLiveServer->addRequestData('type', $type);
		
		$ret = $this->mLiveServer->request('input');

		return xml2Array($ret);
	}
	
	/**
	 * 返回 1 ， 0
	 * Enter description here ...
	 * @param unknown_type $id
	 * @param unknown_type $url
	 */
	function inputStreamUpdate($host, $apidir, $id, $url, $type)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'update');
		$this->mLiveServer->addRequestData('id', $id);
		$this->mLiveServer->addRequestData('url', $url);
		$this->mLiveServer->addRequestData('type', $type);
		
		$ret = $this->mLiveServer->request('input');

		return xml2Array($ret);
	}
	
	/**
	 * 输入流 操作
	 * 列出 当前 输入流 的 延时层 所有流
	 * 启动  停止 将 延时层 输出层 流都重启
	 * 
	 * $action 
	 * list 当前输入流的延时层列表
	 * delete 删除流 返回 1 ， 0
	 * start 启动流 返回 1 ， 0
	 * stop 停止流 返回 1 ， 0
	 * 
	 * Enter description here ...
	 */
	function inputStreamOperate($host, $apidir, $action, $id)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', $action);
		$this->mLiveServer->addRequestData('id', $id);
		
		$ret = $this->mLiveServer->request('input');

		return xml2Array($ret);
	}

	
	/**
	 * streamId
	 * length
	 * 返回id
	 * 
	 * 
	 * 没有update
	 * insert delete select start stop 
	 * 
	 * 停止 重启  传 延时层 id
	 * Enter description here ...
	 */
	
	/**
	 * 创建延时层
	 * 返回 id
	 * Enter description here ...
	 * @param unknown_type $streamId
	 * @param unknown_type $length
	 */
	function inputDelayInsert($host, $apidir, $inputId, $length)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'insert');
		$this->mLiveServer->addRequestData('inputId', $inputId);
		$this->mLiveServer->addRequestData('length', $length);
		
		$ret = $this->mLiveServer->request('delay');

		return xml2Array($ret);
	}
	
	/**
	 * 延时层列表
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	function inputDelaySelect($host, $apidir, $id = '')
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'select');
		$this->mLiveServer->addRequestData('id', $id);
		
		$ret = $this->mLiveServer->request('delay');

		return xml2Array($ret);
	}
	
	/**
	 * 延时层流操作
	 * $action
	 * delete 删除 返回 1 ， 0
	 * start 启动流 返回 1 ， 0
	 * stop 停止流 返回 1 ， 0
	 * 
	 * Enter description here ...
	 * @param unknown_type $action
	 * @param unknown_type $id
	 */
	function inputDelayOperate($host, $apidir, $action, $id)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', $action);
		
		$this->mLiveServer->addRequestData('id', $id);
		
		$ret = $this->mLiveServer->request('delay');

		return xml2Array($ret);
	}
	
	/**
	 * 输入的输出 (切播层列表)
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	function inputChgStreamSelect($host, $apidir, $id = '')
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'select');
		
		$this->mLiveServer->addRequestData('id', $id);
		
		$ret = $this->mLiveServer->request('output');

		return xml2Array($ret);
	}
	/**
	 * 输入的输出 (切播层)
	 * select
	 * insert sourceId   sourceType (1-input 2-delay)
	 * delete start stop id
	 * update id sourceId sourceType (1-input 2-delay)
	 * 
	 * 
	 * change id sourceId sourceType (1-input 2-delay)
	 * Enter description here ...
	 */
	function inputChgStreamInsert($host, $apidir, $sourceId, $sourceType)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'insert');
		
		$this->mLiveServer->addRequestData('sourceId', $sourceId);
		$this->mLiveServer->addRequestData('sourceType', $sourceType);
		
		$ret = $this->mLiveServer->request('output');

		return xml2Array($ret);
	}
	
	function inputChgStreamUpdate($host, $apidir, $id, $sourceId, $sourceType)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'update');
		
		$this->mLiveServer->addRequestData('id', $id);
		$this->mLiveServer->addRequestData('sourceId', $sourceId);
		$this->mLiveServer->addRequestData('sourceType', $sourceType);
		
		$ret = $this->mLiveServer->request('output');

		return xml2Array($ret);
	}
	
	function inputChgStreamOperate($host, $apidir, $action, $id)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', $action);
		
		$this->mLiveServer->addRequestData('id', $id);
		
		$ret = $this->mLiveServer->request('output');

		return xml2Array($ret);
	}
	
	/**
	 * 输入的输出 (切播)
	 * Enter description here ...
	 * @param unknown_type $id ===> chg_stream_id
	 * @param unknown_type $sourceId (1-input 2-delay) ===> 1- input_id 2- delay_stream_id
	 * @param unknown_type $sourceType (1-input 2-delay)
	 */
	function inputChgStreamChange($host, $apidir, $id, $sourceId, $sourceType, $notify)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'change');
		
		$this->mLiveServer->addRequestData('id', $id);
		$this->mLiveServer->addRequestData('sourceId', $sourceId);
		$this->mLiveServer->addRequestData('sourceType', $sourceType);
		$this->mLiveServer->addRequestData('notify', $notify);
		
		$ret = $this->mLiveServer->request('output');

		return xml2Array($ret);
	}
	
	/**
	 * 串联单列表
	 * Enter description here ...
	 */
	function inputScheduleList($host, $apidir)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'list');
		
		$ret = $this->mLiveServer->request('schedule');

		return xml2Array($ret);
	}
	
	/**
	 * 串联单 
	 * insert 
	 * outputId ==> chg_stream_id
	 * sourceId sourceType (1-input 2-delay) startTime duration 返回id 
	 * delete id
	 * list
	 */
	function inputScheduleInsert($host, $apidir, $outputId, $sourceId, $sourceType, $startTime, $duration)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'insert');
		
		$this->mLiveServer->addRequestData('outputId', $outputId);
		$this->mLiveServer->addRequestData('sourceId', $sourceId);
		$this->mLiveServer->addRequestData('sourceType', $sourceType);
		$this->mLiveServer->addRequestData('startTime', $startTime);
		$this->mLiveServer->addRequestData('duration', $duration);
		
		$ret = $this->mLiveServer->request('schedule');

		return xml2Array($ret);
	}
	
	/**
	 * 串联单操作 (删除)
	 * $action 
	 * delete id
	 * Enter description here ...
	 * @param unknown_type $action
	 * @param unknown_type $id
	 */
	function inputScheduleOperate($host, $apidir, $action, $id)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', $action);
		
		$this->mLiveServer->addRequestData('id', $id);
		
		$ret = $this->mLiveServer->request('schedule');

		return xml2Array($ret);
	}
	
	/**
	 * 备播文件上传
	 * Enter description here ...
	 * @param unknown_type $url
	 * @param unknown_type $callback
	 */
	function inputFileInsert($host, $apidir, $url, $callback)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'insert');
		
		$this->mLiveServer->addRequestData('url', $url);
		$this->mLiveServer->addRequestData('callback', $callback);
		
		$ret = $this->mLiveServer->request('file');

		return xml2Array($ret);
	}

	function inputFileDelete($host, $apidir, $id)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'delete');
		
		$this->mLiveServer->addRequestData('id', $id);
		
		$ret = $this->mLiveServer->request('file');

		return xml2Array($ret);
	}

	/**
	 * 文件流列表
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	function inputFileListSelect($host, $apidir, $id = '')
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'list');
		
		$this->mLiveServer->addRequestData('id', $id);
		
		$ret = $this->mLiveServer->request('list');

		return xml2Array($ret);
	}
	
	/**
	 * 创建文件流
	 * $action 
	 * insert ===> id (','隔开)  返回 id
	 * delete ===> id
	 *
	 * Enter description here ...
	 * @param unknown_type $action
	 * @param unknown_type $id
	 */
	function inputFileListInsert($host, $apidir, $id)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'insert');
		
		$this->mLiveServer->addRequestData('files', $id);
		
		$ret = $this->mLiveServer->request('list');

		return xml2Array($ret);
	}
	
	function inputFileListUpdate($host, $apidir, $listId, $id)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'update');
		
		$this->mLiveServer->addRequestData('id', $listId);
		$this->mLiveServer->addRequestData('files', $id);
		
		$ret = $this->mLiveServer->request('list');

		return xml2Array($ret);
	}
	
	/**
	 * 文件流删除
	 * $id 文件流id
	 * 返回 1,0
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	function inputFileListDelete($host, $apidir, $id)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'delete');
		
		$this->mLiveServer->addRequestData('id', $id);
		
		$ret = $this->mLiveServer->request('list');

		return xml2Array($ret);
	}
	/**
	 * 文件流操作
	 * delete start stop
	 * $id 文件流id
	 * 返回 1,0
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	function inputFileListOperate($host, $apidir, $action, $id)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', $action);
		
		$this->mLiveServer->addRequestData('id', $id);
		
		$ret = $this->mLiveServer->request('list');

		return xml2Array($ret);
	}

	/**
	 * 输出应用列表
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	function outputApplicationSelect($host, $apidir, $id = '')
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'select');
		$this->mLiveServer->addRequestData('id', $id);
		
		$ret = $this->mLiveServer->request('application');

		return xml2Array($ret);
	}
	/**
	 * 输出层应用创建
	 * Enter description here ...
	 * @param unknown_type $name
	 * @param unknown_type $length
	 * @param unknown_type $drm
	 * @param unknown_type $type
	 */
	function outputApplicationInsert($host, $apidir, $id, $name, $length, $drm, $type)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'insert');
		
		$this->mLiveServer->addRequestData('id', $id);
		$this->mLiveServer->addRequestData('name', $name);
		$this->mLiveServer->addRequestData('length', $length);
		$this->mLiveServer->addRequestData('drm', $drm);
		$this->mLiveServer->addRequestData('type', $type);
		
		$ret = $this->mLiveServer->request('application');
			
		return xml2Array($ret);
	}
	
	/**
	 * 输出层应用更新
	 * Enter description here ...
	 * @param unknown_type $id
	 * @param unknown_type $name
	 * @param unknown_type $length
	 * @param unknown_type $drm
	 * @param unknown_type $type
	 */
	function outputApplicationUpdate($host, $apidir, $id, $name, $length, $drm, $type)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'update');
		
		$this->mLiveServer->addRequestData('id', $id);
		$this->mLiveServer->addRequestData('name', $name);
		$this->mLiveServer->addRequestData('length', $length);
		$this->mLiveServer->addRequestData('drm', $drm);
		$this->mLiveServer->addRequestData('type', $type);
		
		$ret = $this->mLiveServer->request('application');

		return xml2Array($ret);
	}
	
	/**
	 * 输出层应用操作
	 * $action
	 * delete
	 * start
	 * stop
	 * 
	 * Enter description here ...
	 * @param unknown_type $action
	 * @param unknown_type $id
	 */
	function outputApplicationOperate($host, $apidir, $action, $id)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', $action);
		
		$this->mLiveServer->addRequestData('id', $id);
		
		$ret = $this->mLiveServer->request('application');

		return xml2Array($ret);
	}
	
	/**
	 * 输出应用输出流列表
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	function outputStreamSelect($host, $apidir, $id = '')
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'select');
		$this->mLiveServer->addRequestData('id', $id);
		
		$ret = $this->mLiveServer->request('stream');

		return xml2Array($ret);
	}
	
	/**
	 * 输出流创建
	 * Enter description here ...
	 * @param unknown_type $appId
	 * @param unknown_type $name
	 * @param unknown_type $url
	 */
	function outputStreamInsert($host, $apidir, $id, $applicationId, $name, $url)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'insert');
		
		$this->mLiveServer->addRequestData('id', $id);
		$this->mLiveServer->addRequestData('applicationId', $applicationId);
		$this->mLiveServer->addRequestData('name', $name);
		$this->mLiveServer->addRequestData('url', $url);
		
		$ret = $this->mLiveServer->request('stream');

		return xml2Array($ret);
	}
	
	/**
	 * 输出流更新
	 * Enter description here ...
	 * @param unknown_type $id
	 * @param unknown_type $applicationId
	 * @param unknown_type $name
	 * @param unknown_type $url
	 */
	function outputStreamUpdate($host, $apidir, $id, $applicationId, $name, $url)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', 'update');
		
		$this->mLiveServer->addRequestData('id', $id);
		$this->mLiveServer->addRequestData('applicationId', $applicationId);
		$this->mLiveServer->addRequestData('name', $name);
		$this->mLiveServer->addRequestData('url', $url);
		
		$ret = $this->mLiveServer->request('stream');

		return xml2Array($ret);
	}
	
	/**
	 * 输出流操作
	 * $action
	 * delete
	 * start
	 * stop
	 * 
	 * Enter description here ...
	 * @param unknown_type $action
	 * @param unknown_type $id
	 */
	function outputStreamOperate($host, $apidir, $action, $id)
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', $action);
		
		$this->mLiveServer->addRequestData('id', $id);
		
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
	 * 删除时移
	 * $action	delete
	 * 
	 * Enter description here ...
	 * @param unknown_type $id
	 * @param unknown_type $time
	 * @param unknown_type $callback
	 * @param unknown_type $action
	 */
	function dvrOperate($host, $apidir, $id, $time, $duration, $callback, $action = 'delete')
	{
		if (!$this->mLiveServer)
		{
			return array();
		}
		$this->mLiveServer->setUrlHost($host, $apidir);
		$this->mLiveServer->setSubmitType('post');
		$this->mLiveServer->initPostData();
		$this->mLiveServer->setReturnFormat('xml');
		$this->mLiveServer->addRequestData('action', $action);
		
		$this->mLiveServer->addRequestData('id', $id);
		$this->mLiveServer->addRequestData('time', $time);
		$this->mLiveServer->addRequestData('duration', $duration);
		$this->mLiveServer->addRequestData('callback', $callback);
		
		$ret = $this->mLiveServer->request('dvr');

		return xml2Array($ret);
	}
	
	/**
	 * 抓取时移
	 * Enter description here ...
	 * @param unknown_type $record_info
	 * @param unknown_type $url
	 * @param unknown_type $callback
	 */
	function recordInsert($host, $apidir, $record_info, $url, $callback)
	{
		if (!$this->mRecordServer)
		{
			return array();
		}
		$this->mRecordServer->setUrlHost($host, $apidir);
		$this->mRecordServer->setSubmitType('get');
		$this->mRecordServer->initPostData();
		$this->mRecordServer->setReturnFormat('json');
		$this->mRecordServer->addRequestData('action', 'TIMESHIFT');
		foreach ($record_info AS $k => $v)
		{
			$this->mRecordServer->addRequestData($k, $v);
		}
		$this->mRecordServer->addRequestData('url', $url);
		$this->mRecordServer->addRequestData('callback', $callback);
		
		$ret = $this->mRecordServer->request('');

		return xml2Array($ret);
	}
	/**
	 * 删除录制后的时移
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	function recordDelete($host, $apidir, $id)
	{
		if (!$this->mRecordServer)
		{
			return array();
		}
		$this->mRecordServer->setUrlHost($host, $apidir);
		$this->mRecordServer->setSubmitType('get');
		$this->mRecordServer->initPostData();
		$this->mRecordServer->setReturnFormat('json');
		$this->mRecordServer->addRequestData('action', 'DELETE');
		$this->mRecordServer->addRequestData('id', $id);
		
		$ret = $this->mRecordServer->request('');

		return xml2Array($ret);
	}
}
?>