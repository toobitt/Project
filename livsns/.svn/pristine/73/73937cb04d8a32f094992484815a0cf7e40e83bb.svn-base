<?php
class live
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if($gGlobalConfig['App_old_live'])
		{
			$this->curl = new curl($gGlobalConfig['App_old_live']['host'], $gGlobalConfig['App_old_live']['dir']);
		}
	}

	function __destruct()
	{
	}

	public function update_record_state($record_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update_record_state');
		$this->curl->addRequestData('id', $record_id);
		return $this->curl->request('admin/program_record.php');
	}

	public function get_channel($id, $f = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','channelinfo');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('fields', $f);
		return $this->curl->request('admin/channel.php');
	}
	
	public function addLogs($data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','create');
		$this->curl->addRequestData('html',1);
		if(is_array($data))
		{
			foreach($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$ret = $this->curl->request('admin/program_record_log_update.php');	
		return $ret[0];
	}
	
	public function updateLogs($data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update');
		
		$this->curl->addRequestData('html',1);
		if(is_array($data))
		{
			foreach($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$ret = $this->curl->request('admin/program_record_log_update.php');	
		return $ret[0];
	}
	
	public function delete_record($id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delete_all');
		$this->curl->addRequestData('id', $id);
		return $this->curl->request('admin/program_record_update.php');
	}
	
	public function getChannel()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_channel_info');
		$ret = $this->curl->request('channel.php');
		return $ret;
	}
	
	public function getChannelById($id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_channel_info');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('channel.php');
		return $ret;
	}
}
?>