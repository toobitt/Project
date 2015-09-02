<?php
class program
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if($gGlobalConfig['App_program'])
		{
			$this->curl = new curl($gGlobalConfig['App_program']['host'], $gGlobalConfig['App_program']['dir']);
		}
	}

	function __destruct()
	{
	
	}

	public function get_program_plan($channel_id,$start,$end)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_program_plan');
		$this->curl->addRequestData('channel_id', $channel_id);
		$this->curl->addRequestData('start', $start);
		$this->curl->addRequestData('end', $end);
		$ret = $this->curl->request('program_plan.php');
		return $ret[0];
	}
	
	public function getPlanByChannel($channel_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getPlanByChannel');
		$this->curl->addRequestData('channel_id', $channel_id);
		$ret = $this->curl->request('program_plan.php');
		return $ret[0];
	}
	
	public function getGreaterProgram($channel_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getGreaterProgram');
		$this->curl->addRequestData('channel_id', $channel_id);
		$ret = $this->curl->request('program.php');
		return $ret[0];
	}
	
	public function getCurrentProgram($channel_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getCurrentProgram');
		$this->curl->addRequestData('channel_id', $channel_id);
		$ret = $this->curl->request('program.php');
		return $ret[0];
	}

	public function getCurrentNextProgram($channel_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getCurrentNextProgram');
		$this->curl->addRequestData('channel_id', $channel_id);
		$ret = $this->curl->request('program.php');
		return $ret[0];
	}
	
	public function getTimeshift($channel_id, $dates = '', $stime = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getTimeshift');
		$this->curl->addRequestData('channel_id', $channel_id);
		$this->curl->addRequestData('dates', $dates);
		$this->curl->addRequestData('stime', $stime);
		$ret = $this->curl->request('program.php');
		return $ret[0];
	}
	
	public function getTimeshiftNew($channel_id, $dates = '', $stime = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getTimeshiftNew');
		$this->curl->addRequestData('channel_id', $channel_id);
		$this->curl->addRequestData('dates', $dates);
		$this->curl->addRequestData('stime', $stime);
		$ret = $this->curl->request('program.php');
		return $ret[0];
	}
	
	/**
	 * 串联单生成节目单
	 * $channel_id 频道id
	 * $dates 日期
	 * $theme 节目
	 * $start_time 开始时间
	 * $end_time 结束时间
	 * Enter description here ...
	 */
	public function schedule2program($data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','schedule2program');
		if (empty($data))
		{
			return false;
		}
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('program_update.php');
		return $ret[0];
	}
	
	/**
	 * 删除节目单
	 * $channel_id 频道id
	 * $dates 日期
	 * Enter description here ...
	 */
	public function delete_by_channel_id($data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delete_by_channel_id');
		if (empty($data))
		{
			return false;
		}
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('program_update.php');
		return $ret[0];
	}
	
	public function get_program_info($data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_program_info');
		if (empty($data))
		{
			return false;
		}
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('program.php');
		return $ret[0];
	}
	
	public function check_program_exists($data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','check_program_exists');
		if (empty($data))
		{
			return false;
		}
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('program.php');
		return $ret[0];
	}
}
?>
