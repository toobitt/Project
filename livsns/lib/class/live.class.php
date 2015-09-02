<?php
class live
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if($gGlobalConfig['App_live'])
		{
			$this->curl = new curl($gGlobalConfig['App_live']['host'], $gGlobalConfig['App_live']['dir']);
		}
	}

	function __destruct()
	{
	
	}
	
	public function getChannel($is_sys = 1, $get_record = 0)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','show');
		$this->curl->addRequestData('is_sys', $is_sys);
		$this->curl->addRequestData('fetch_live', $is_sys);
		$this->curl->addRequestData('get_record', $get_record);
		$ret = $this->curl->request('channel.php');
		return $ret;
	}
	
	public function getChannelById($id, $is_sys = 1, $fetch_live=0)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','show');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('is_sys', $is_sys);
		$this->curl->addRequestData('fetch_live', $fetch_live);
		$ret = $this->curl->request('channel.php');
		return $ret;
	}
	
	/**
	 * 取频道信息,带检索、分页
	 * $offset
	 * $count
	 * $k
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function getChannelInfo($data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('channel.php');
		return $ret;
	}
	
	/**
	 * 根据频道id获取频道信息 支持多个
	 * $id 频道id 1,2,3
	 * $is_stream 是否带频道信号信息 (1-是 0-否)
	 * $is_server 是否带直播服务器信息 (1-是 0-否)
	 * $field 频道字段
	 * Enter description here ...
	 */
	public function getChannelInfoById($data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_channel_info_by_id');
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('channel.php');
		return $ret;
	}
	
	/**
	 * 频道总数
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function getChannelCount($data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'count');
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('channel.php');
		return $ret[0];
	}
	
	/**
	*根据节点ID取该节点下所有子集节点，包括子集，支持多个
	*/
	public function getChildNodeByFid($node_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getChildNodeByFid');
		$this->curl->addRequestData('node_id', $node_id);
		$ret = $this->curl->request('channel.php');
		return $ret;
	}
	
	/**
	*根据节点ID取该节点下所有父集节点，支持多个
	*/
	public function getFatherNodeByid($node_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getFatherNodeByid');
		$this->curl->addRequestData('node_id', $node_id);
		$ret = $this->curl->request('channel.php');
		return $ret;
	}
	
	public function updateBeibo($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update_beibo');
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('channel_update.php');
		return $ret[0];
	}
	
	public function updateChange($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update_change');
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('channel_update.php');
		return $ret[0];
	}
	
	public function getChannelNode($fid = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('fid', $fid);
		$ret = $this->curl->request('channel_node.php');
		return $ret;
	}
	
	public function getSelectedNodes($id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getSelectedNodes');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('channel_node.php');
		return $ret;
	}
	
	public function get_selected_node_path($id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_selected_node_path');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('channel_node.php');
		return $ret;
	}
	
}
?>
