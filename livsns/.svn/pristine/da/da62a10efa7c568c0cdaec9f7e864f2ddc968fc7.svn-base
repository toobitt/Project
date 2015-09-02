<?php
require_once ROOT_PATH . 'lib/class/curl.class.php';
class live extends InitFrm
{
	private $curl = null;
	function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['App_live']['host'], $this->settings['App_live']['dir'] . 'admin/');
	}	
	function __destruct()
	{
		parent::__destruct();
	}
	function create($data = array())
	{
		if(!$this->verify_data_intemgrity($data))
		{
			return false;
		}
		$data['server_id'] = $this->select_server();
		$this->curl->setSubmitType  = 'post';
		$this->curl->initPostData();
		foreach ($data as $filed => $value)
		{
			if(is_array($value))
			{
				foreach($value as $k=>$v)
				{
					$this->curl->addRequestData($k . '[]', $v);
				}
			}
			else
			{
				$this->curl->addRequestData($filed, $value);
			}
		}
		$this->curl->addRequestData('a', 'create');
		$returninfo = $this->curl->request('channel_update.php');
		return $returninfo;
	}
	function update($data = array())
	{
		if(!$this->verify_data_intemgrity($data))
		{
			return false;
		}
		$data['server_id'] = $this->select_server();
		$this->curl->setSubmitType  = 'post';
		$this->curl->initPostData();
		foreach ($data as $filed => $value)
		{
			if(is_array($value))
			{
				foreach($value as $k=>$v)
				{
					$this->curl->addRequestData($k . '[]', $v);
				}
			}
			else
			{
				$this->curl->addRequestData($filed, $value);
			}
		}
		$this->curl->addRequestData('a', 'update');
		$returninfo = $this->curl->request('channel_update.php');
		return $returninfo;
	}
	function delete(){}
	//服务器选择
	protected function select_server()
	{
		return 192;
	}
	//数据完整性过滤
	protected function verify_data_intemgrity($data)
	{
		return true;
	}
	function channel_list($conditions=array())
	{
		$this->curl->addRequestData('a', 'show');
		if(!empty($conditions))
		{
			foreach($conditions as $key=>$val)
			{
				$this->curl->addRequestData($key, $val);	
			}
		}
		$returninfo = $this->curl->request('channel.php');
		
		return $returninfo;
	}
	function count($conditions=array())
	{
		$this->curl->addRequestData('a', 'count');
		if(!empty($conditions))
		{
			foreach($conditions as $key=>$val)
			{
				$this->curl->addRequestData($key, $val);	
			}
		}
		$total = $this->curl->request('channel.php');
		
		return $total;
	}
}
?>