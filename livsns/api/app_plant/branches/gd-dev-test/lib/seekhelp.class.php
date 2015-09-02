<?php

class SeekHelpApi
{
	private $curl;
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_seekhelp']['host'], $gGlobalConfig['App_seekhelp']['dir']);
	}

	public function __destruct()
	{
	    unset($this->curl);
	}
	
    public function community_operate($data = array())
	{
		if (!$this->curl || !$data)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		foreach ($data as $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$result = $this->curl->request('seekhelp_node_update.php');
		if($result)
		{
		    return $result[0];
		}
		else 
		{
		    return array();
		}
	}
}