<?php
class programRecord
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if($gGlobalConfig['App_program_record'])
		{
			$this->curl = new curl($gGlobalConfig['App_program_record']['host'], $gGlobalConfig['App_program_record']['dir']);
		}
	}

	function __destruct()
	{
	
	}
	
	public function create($info = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','create');
		$this->curl->addRequestData('html',true);
		foreach($info as $k => $v)
		{	
			$this->curl->addRequestData($k, $v);	
		}
		$ret = $this->curl->request('program_record_update.php');
		return $ret[0];
	}
	
	public function update($info = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update');
		$this->curl->addRequestData('html',true);
		foreach($info as $k => $v)
		{	
			$this->curl->addRequestData($k, $v);	
		}
		$ret = $this->curl->request('program_record_update.php');
		return $ret[0];
	}
	
	public function update_program($record_id,$program_id = 0)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update_program');
		$this->curl->addRequestData('record_id',$record_id);
		$this->curl->addRequestData('program_id',$program_id);
		$ret = $this->curl->request('program_record_update.php');
		return $ret[0];
	}
	
	public function update_plan($record_id,$plan_id = 0)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update_plan');
		$this->curl->addRequestData('record_id',$record_id);
		$this->curl->addRequestData('plan_id',$plan_id);
		$ret = $this->curl->request('program_record_update.php');
		return $ret[0];
	}
	
	public function delete($id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delete');
		$this->curl->addRequestData('id',$id);
		$ret = $this->curl->request('program_record_update.php');
		return $ret[0];
	}
	
	public function delete_all($id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delete_all');
		$this->curl->addRequestData('id',$id);
		$ret = $this->curl->request('program_record_update.php');
		return $ret[0];
	}
	
	public function getRecord($data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getRecord');
		$this->curl->addRequestData('html',true);
		foreach($data as $k => $v)
		{	
			$this->curl->addRequestData($k, $v);	
		}
		$ret = $this->curl->request('program_record.php');
		return $ret[0];
	}
	
	public function getRecordByChannel($channel_id,$dates)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getRecordByChannel');
		$this->curl->addRequestData('channel_id', $channel_id);
		$this->curl->addRequestData('dates', $dates);
		$ret = $this->curl->request('program_record.php');
		return $ret;
	}
	
	public function getRecordById($record_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getRecordById');
		$this->curl->addRequestData('record_id', $record_id);
		$ret = $this->curl->request('program_record.php');
		return $ret;
	}
	
	public function getRecordByProgramId($program_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getRecordByProgramId');
		$this->curl->addRequestData('program_id', $program_id);
		$ret = $this->curl->request('program_record.php');
		return $ret[0];
	}
	
	public function getRecordByPlanId($plan_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getRecordByPlanId');
		$this->curl->addRequestData('plan_id', $plan_id);
		$ret = $this->curl->request('program_record.php');
		return $ret[0];
	}
	
	public function check_day($channel_id,$start_time,$end_time)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','check_record');
		$this->curl->addRequestData('channel_id', $channel_id);
		$this->curl->addRequestData('start_time', $start_time);
		$this->curl->addRequestData('end_time', $end_time);
		$ret = $this->curl->request('program_record_update.php');
		return $ret[0];
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
		$ret = $this->curl->request('program_record_log_update.php');	
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
		$ret = $this->curl->request('program_record_log_update.php');	
		return $ret[0];
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
		return $this->curl->request('program_record_update.php');
	}
}
?>
