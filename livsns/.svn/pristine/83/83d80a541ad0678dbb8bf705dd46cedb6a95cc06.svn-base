<?php
/***************************************************************************
* $Id: livmms.class.php 8250 2012-07-23 07:34:59Z lijiaying $
***************************************************************************/
class livmms extends InitFrm
{
	private $inputStream;
	private $output;
	private $schedul;
	private $record;
	private $live;
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/curl.class.php');
		$this->inputStream = new curl($this->settings['mms']['input_stream_server']['host'], $this->settings['mms']['input_stream_server']['dir']);
		$this->inputStream->mPostContentType('string');
		
		$this->output = new curl($this->settings['mms']['output_stream_server']['host'], $this->settings['mms']['output_stream_server']['dir']);
		$this->output->mPostContentType('string');
		
		$this->schedul = new curl($this->settings['mms']['schedul_stream_server']['host'], $this->settings['mms']['schedul_stream_server']['dir']);
		$this->schedul->mPostContentType('string');
		
		$this->record = new curl($this->settings['mms']['record_server']['host'], $this->settings['mms']['record_server']['dir']);
		$this->record->mPostContentType('string');
		
		if ($this->settings['mms']['live_stream_server'])
		{
			$this->live = new curl($this->settings['mms']['live_stream_server']['host'], $this->settings['mms']['live_stream_server']['dir']);
			$this->live->mPostContentType('string');
		}
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 *
	 * Enter description here ...
	 */

	function inputStreamSelect($id='')
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', 'select');
		
		if ($id)
		{
			$this->inputStream->addRequestData('id', $id);
		}
		
		$ret = $this->inputStream->request('input');

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
	
	function inputStreamInsert($url, $type)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', 'insert');
		$this->inputStream->addRequestData('url', $url);
		$this->inputStream->addRequestData('type', $type);
		
		$ret = $this->inputStream->request('input');

		return xml2Array($ret);
	}
	
	/**
	 * 返回 1 ， 0
	 * Enter description here ...
	 * @param unknown_type $id
	 * @param unknown_type $url
	 */
	function inputStreamUpdate($id, $url, $type)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', 'update');
		$this->inputStream->addRequestData('id', $id);
		$this->inputStream->addRequestData('url', $url);
		$this->inputStream->addRequestData('type', $type);
		
		$ret = $this->inputStream->request('input');

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
	function inputStreamOperate($action, $id)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', $action);
		$this->inputStream->addRequestData('id', $id);
		
		$ret = $this->inputStream->request('input');

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
	function inputDelayInsert($inputId, $length)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', 'insert');
		$this->inputStream->addRequestData('inputId', $inputId);
		$this->inputStream->addRequestData('length', $length);
		
		$ret = $this->inputStream->request('delay');

		return xml2Array($ret);
	}
	
	/**
	 * 延时层列表
	 * Enter description here ...
	 * @param unknown_type $streamId
	 */
	function inputDelaySelect($streamId)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', 'select');
		$this->inputStream->addRequestData('inputId', $inputId);
		
		$ret = $this->inputStream->request('delay');

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
	function inputDelayOperate($action, $id)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', $action);
		
		$this->inputStream->addRequestData('id', $id);
		
		$ret = $this->inputStream->request('delay');

		return xml2Array($ret);
	}
	
	/*----------------输出层--------------*/
	
	function outputApplicationSelect()
	{
		if (!$this->output)
		{
			return array();
		}
		
		$this->output->setSubmitType('post');
		$this->output->initPostData();
		$this->output->setReturnFormat('xml');
		$this->output->addRequestData('action', 'select');
		
		$ret = $this->output->request('application');

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
	function outputApplicationInsert($id, $name, $length, $drm, $type)
	{
		if (!$this->output)
		{
			return array();
		}
		
		$this->output->setSubmitType('post');
		$this->output->initPostData();
		$this->output->setReturnFormat('xml');
		$this->output->addRequestData('action', 'insert');
		
		$this->output->addRequestData('id', $id);
		$this->output->addRequestData('name', $name);
		$this->output->addRequestData('length', $length);
		$this->output->addRequestData('drm', $drm);
		$this->output->addRequestData('type', $type);
		
		$ret = $this->output->request('application');
			
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
	function outputApplicationUpdate($id, $name, $length, $drm, $type)
	{
		if (!$this->output)
		{
			return array();
		}
		
		$this->output->setSubmitType('post');
		$this->output->initPostData();
		$this->output->setReturnFormat('xml');
		$this->output->addRequestData('action', 'update');
		
		$this->output->addRequestData('id', $id);
		$this->output->addRequestData('name', $name);
		$this->output->addRequestData('length', $length);
		$this->output->addRequestData('drm', $drm);
		$this->output->addRequestData('type', $type);
		
		$ret = $this->output->request('application');

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
	function outputApplicationOperate($action, $id)
	{
		if (!$this->output)
		{
			return array();
		}
		
		$this->output->setSubmitType('post');
		$this->output->initPostData();
		$this->output->setReturnFormat('xml');
		$this->output->addRequestData('action', $action);
		
		$this->output->addRequestData('id', $id);
		
		$ret = $this->output->request('application');

		return xml2Array($ret);
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	function outputStreamSelect()
	{
		if (!$this->output)
		{
			return array();
		}
		
		$this->output->setSubmitType('post');
		$this->output->initPostData();
		$this->output->setReturnFormat('xml');
		$this->output->addRequestData('action', 'select');
		
		$ret = $this->output->request('stream');

		return xml2Array($ret);
	}
	
	/**
	 * 输出流创建
	 * Enter description here ...
	 * @param unknown_type $appId
	 * @param unknown_type $name
	 * @param unknown_type $url
	 */
	function outputStreamInsert($id, $applicationId, $name, $url)
	{
		if (!$this->output)
		{
			return array();
		}
		
		$this->output->setSubmitType('post');
		$this->output->initPostData();
		$this->output->setReturnFormat('xml');
		$this->output->addRequestData('action', 'insert');
		
		$this->output->addRequestData('id', $id);
		$this->output->addRequestData('applicationId', $applicationId);
		$this->output->addRequestData('name', $name);
		$this->output->addRequestData('url', $url);
		
		$ret = $this->output->request('stream');

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
	function outputStreamUpdate($id, $applicationId, $name, $url)
	{
		if (!$this->output)
		{
			return array();
		}
		
		$this->output->setSubmitType('post');
		$this->output->initPostData();
		$this->output->setReturnFormat('xml');
		$this->output->addRequestData('action', 'update');
		
		$this->output->addRequestData('id', $id);
		$this->output->addRequestData('applicationId', $applicationId);
		$this->output->addRequestData('name', $name);
		$this->output->addRequestData('url', $url);
		
		$ret = $this->output->request('stream');

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
	function outputStreamOperate($action, $id)
	{
		if (!$this->output)
		{
			return array();
		}
		
		$this->output->setSubmitType('post');
		$this->output->initPostData();
		$this->output->setReturnFormat('xml');
		$this->output->addRequestData('action', $action);
		
		$this->output->addRequestData('id', $id);
		
		$ret = $this->output->request('stream');

		return xml2Array($ret);
	}
	
	/**
	 * 切播
	 * select
	 * insert sourceId   sourceType (1-input 2-delay)
	 * delete start stop id
	 * update id sourceId sourceType (1-input 2-delay)
	 * 
	 * 
	 * change id sourceId sourceType (1-input 2-delay)
	 * Enter description here ...
	 */
	function inputChgStreamInsert($sourceId, $sourceType)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', 'insert');
		
		$this->inputStream->addRequestData('sourceId', $sourceId);
		$this->inputStream->addRequestData('sourceType', $sourceType);
		
		$ret = $this->inputStream->request('output');

		return xml2Array($ret);
	}
	
	function inputChgStreamUpdate($id, $sourceId, $sourceType)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', 'update');
		
		$this->inputStream->addRequestData('id', $id);
		$this->inputStream->addRequestData('sourceId', $sourceId);
		$this->inputStream->addRequestData('sourceType', $sourceType);
		
		$ret = $this->inputStream->request('output');

		return xml2Array($ret);
	}
	
	function inputChgStreamOperate($action, $id)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', $action);
		
		$this->inputStream->addRequestData('id', $id);
		
		$ret = $this->inputStream->request('output');

		return xml2Array($ret);
	}
	
	/**
	 * 切播
	 * Enter description here ...
	 * @param unknown_type $id ===> chg_stream_id
	 * @param unknown_type $sourceId (1-input 2-delay) ===> 1- input_id 2- delay_stream_id
	 * @param unknown_type $sourceType (1-input 2-delay)
	 */
	function inputChgStreamChange($id, $sourceId, $sourceType, $notify)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', 'change');
		
		$this->inputStream->addRequestData('id', $id);
		$this->inputStream->addRequestData('sourceId', $sourceId);
		$this->inputStream->addRequestData('sourceType', $sourceType);
		$this->inputStream->addRequestData('notify', $notify);
		
		$ret = $this->inputStream->request('output');

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
	function inputScheduleInsert($outputId, $sourceId, $sourceType, $startTime, $duration)
	{
		if (!$this->schedul)
		{
			return array();
		}
		
		$this->schedul->setSubmitType('post');
		$this->schedul->initPostData();
		$this->schedul->setReturnFormat('xml');
		$this->schedul->addRequestData('action', 'insert');
		
		$this->schedul->addRequestData('outputId', $outputId);
		$this->schedul->addRequestData('sourceId', $sourceId);
		$this->schedul->addRequestData('sourceType', $sourceType);
		$this->schedul->addRequestData('startTime', $startTime);
		$this->schedul->addRequestData('duration', $duration);
		
		$ret = $this->schedul->request('schedule');

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
	function inputScheduleOperate($action, $id)
	{
		if (!$this->schedul)
		{
			return array();
		}
		
		$this->schedul->setSubmitType('post');
		$this->schedul->initPostData();
		$this->schedul->setReturnFormat('xml');
		$this->schedul->addRequestData('action', $action);
		
		$this->schedul->addRequestData('id', $id);
		
		$ret = $this->schedul->request('schedule');

		return xml2Array($ret);
	}
	
	/**
	 * 备播文件上传
	 * Enter description here ...
	 * @param unknown_type $url
	 * @param unknown_type $callback
	 */
	function inputFileInsert($url, $callback)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', 'insert');
		
		$this->inputStream->addRequestData('url', $url);
		$this->inputStream->addRequestData('callback', $callback);
		
		$ret = $this->inputStream->request('file');

		return xml2Array($ret);
	}

	function inputFileDelete($id)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', 'delete');
		
		$this->inputStream->addRequestData('id', $id);
		
		$ret = $this->inputStream->request('file');

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
	function inputFileListInsert($id)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', 'insert');
		
		$this->inputStream->addRequestData('files', $id);
		
		$ret = $this->inputStream->request('list');

		return xml2Array($ret);
	}
	
	function inputFileListUpdate($listId, $id)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', 'update');
		
		$this->inputStream->addRequestData('id', $listId);
		$this->inputStream->addRequestData('files', $id);
		
		$ret = $this->inputStream->request('list');

		return xml2Array($ret);
	}
	
	/**
	 * 文件流删除
	 * $id 文件流id
	 * 返回 1,0
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	function inputFileListDelete($id)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', 'delete');
		
		$this->inputStream->addRequestData('id', $id);
		
		$ret = $this->inputStream->request('list');

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
	function inputFileListOperate($action, $id)
	{
		if (!$this->inputStream)
		{
			return array();
		}
		
		$this->inputStream->setSubmitType('post');
		$this->inputStream->initPostData();
		$this->inputStream->setReturnFormat('xml');
		$this->inputStream->addRequestData('action', $action);
		
		$this->inputStream->addRequestData('id', $id);
		
		$ret = $this->inputStream->request('list');

		return xml2Array($ret);
	}

	/**
	 * 获取服务器时间
	 * Enter description here ...
	 */
	function outputNtpTime()
	{
		if (!$this->output)
		{
			return array();
		}
		
		$this->output->setSubmitType('post');
		$this->output->initPostData();
		$this->output->setReturnFormat('xml');
		$this->output->addRequestData('action', 'start');
		
		$ret = $this->output->request('ntp');
		
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
	function outputStreamSnap($id)
	{
		if (!$this->output)
		{
			return array();
		}
		
		$this->output->setSubmitType('post');
		$this->output->initPostData();
		$this->output->setReturnFormat('xml');
		$this->output->addRequestData('action', 'start');
		
		$this->output->addRequestData('streamId', $id);
		
		$ret = $this->output->request('Snapshot');

		return xml2Array($ret);
	}
	
	/*----------------直播--------------*/
	
	function _outputApplicationSelect()
	{
		if (!$this->live)
		{
			return array();
		}
		
		$this->live->setSubmitType('post');
		$this->live->initPostData();
		$this->live->setReturnFormat('xml');
		$this->live->addRequestData('action', 'select');
		
		$ret = $this->live->request('application');

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
	function _outputApplicationInsert($id, $name, $length, $drm, $type)
	{
		if (!$this->live)
		{
			return array();
		}
		
		$this->live->setSubmitType('post');
		$this->live->initPostData();
		$this->live->setReturnFormat('xml');
		$this->live->addRequestData('action', 'insert');
		
		$this->live->addRequestData('id', $id);
		$this->live->addRequestData('name', $name);
		$this->live->addRequestData('length', $length);
		$this->live->addRequestData('drm', $drm);
		$this->live->addRequestData('type', $type);
		
		$ret = $this->live->request('application');
			
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
	function _outputApplicationUpdate($id, $name, $length, $drm, $type)
	{
		if (!$this->live)
		{
			return array();
		}
		
		$this->live->setSubmitType('post');
		$this->live->initPostData();
		$this->live->setReturnFormat('xml');
		$this->live->addRequestData('action', 'update');
		
		$this->live->addRequestData('id', $id);
		$this->live->addRequestData('name', $name);
		$this->live->addRequestData('length', $length);
		$this->live->addRequestData('drm', $drm);
		$this->live->addRequestData('type', $type);
		
		$ret = $this->live->request('application');

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
	function _outputApplicationOperate($action, $id)
	{
		if (!$this->live)
		{
			return array();
		}
		
		$this->live->setSubmitType('post');
		$this->live->initPostData();
		$this->live->setReturnFormat('xml');
		$this->live->addRequestData('action', $action);
		
		$this->live->addRequestData('id', $id);
		
		$ret = $this->live->request('application');

		return xml2Array($ret);
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	function _outputStreamSelect()
	{
		if (!$this->live)
		{
			return array();
		}
		
		$this->live->setSubmitType('post');
		$this->live->initPostData();
		$this->live->setReturnFormat('xml');
		$this->live->addRequestData('action', 'select');
		
		$ret = $this->live->request('stream');

		return xml2Array($ret);
	}
	
	/**
	 * 输出流创建
	 * Enter description here ...
	 * @param unknown_type $appId
	 * @param unknown_type $name
	 * @param unknown_type $url
	 */
	function _outputStreamInsert($id, $applicationId, $name, $url)
	{
		if (!$this->live)
		{
			return array();
		}
		
		$this->live->setSubmitType('post');
		$this->live->initPostData();
		$this->live->setReturnFormat('xml');
		$this->live->addRequestData('action', 'insert');
		
		$this->live->addRequestData('id', $id);
		$this->live->addRequestData('applicationId', $applicationId);
		$this->live->addRequestData('name', $name);
		$this->live->addRequestData('url', $url);
		
		$ret = $this->live->request('stream');

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
	function _outputStreamUpdate($id, $applicationId, $name, $url)
	{
		if (!$this->live)
		{
			return array();
		}
		
		$this->live->setSubmitType('post');
		$this->live->initPostData();
		$this->live->setReturnFormat('xml');
		$this->live->addRequestData('action', 'update');
		
		$this->live->addRequestData('id', $id);
		$this->live->addRequestData('applicationId', $applicationId);
		$this->live->addRequestData('name', $name);
		$this->live->addRequestData('url', $url);
		
		$ret = $this->live->request('stream');

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
	function _outputStreamOperate($action, $id)
	{
		if (!$this->live)
		{
			return array();
		}
		
		$this->live->setSubmitType('post');
		$this->live->initPostData();
		$this->live->setReturnFormat('xml');
		$this->live->addRequestData('action', $action);
		
		$this->live->addRequestData('id', $id);
		
		$ret = $this->live->request('stream');

		return xml2Array($ret);
	}
	
	/**
	 * 获取服务器时间
	 * Enter description here ...
	 */
	function _outputNtpTime()
	{
		if (!$this->live)
		{
			return array();
		}
		
		$this->live->setSubmitType('post');
		$this->live->initPostData();
		$this->live->setReturnFormat('xml');
		$this->live->addRequestData('action', 'start');
		
		$ret = $this->live->request('ntp');
		
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
	function _outputStreamSnap($id)
	{
		if (!$this->live)
		{
			return array();
		}
		
		$this->live->setSubmitType('post');
		$this->live->initPostData();
		$this->live->setReturnFormat('xml');
		$this->live->addRequestData('action', 'start');
		
		$this->live->addRequestData('streamId', $id);
		
		$ret = $this->live->request('Snapshot');

		return xml2Array($ret);
	}
	
	/**
	 * 抓取时移
	 * Enter description here ...
	 * @param unknown_type $record_info
	 * @param unknown_type $url
	 * @param unknown_type $callback
	 */
	function recordInsert($record_info, $url, $callback)
	{
		if (!$this->record)
		{
			return array();
		}
		
		$this->record->setSubmitType('get');
		$this->record->initPostData();
		$this->record->setReturnFormat('json');
		$this->record->addRequestData('action', 'TIMESHIFT');
		foreach ($record_info AS $k => $v)
		{
			$this->record->addRequestData($k, $v);
		}
		$this->record->addRequestData('url', $url);
		$this->record->addRequestData('callback', $callback);
		
		$ret = $this->record->request('');

		return xml2Array($ret);
	}
	
	/**
	 * 删除录制后的时移
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	function recordDelete($id)
	{
		if (!$this->record)
		{
			return array();
		}
		
		$this->record->setSubmitType('get');
		$this->record->initPostData();
		$this->record->setReturnFormat('json');
		$this->record->addRequestData('action', 'DELETE');
		$this->record->addRequestData('id', $id);
		
		$ret = $this->record->request('');

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
	function dvrOperate($id, $time, $duration, $callback, $action = 'delete')
	{
		if (!$this->output)
		{
			return array();
		}
		
		$this->output->setSubmitType('post');
		$this->output->initPostData();
		$this->output->setReturnFormat('xml');
		$this->output->addRequestData('action', $action);
		
		$this->output->addRequestData('id', $id);
		$this->output->addRequestData('time', $time);
		$this->output->addRequestData('duration', $duration);
		$this->output->addRequestData('callback', $callback);
		
		$ret = $this->output->request('dvr');

		return xml2Array($ret);
	}
}
?>