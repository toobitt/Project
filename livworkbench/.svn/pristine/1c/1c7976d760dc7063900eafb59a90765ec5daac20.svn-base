<?php if(!defined('PLUGIN_PATH')) exit('Access Denied');
class video
{
	protected $curl;
	function __construct()
	{
		global $_HOGE;
		$this->hoge = $_HOGE;
	}
	function __destruct()
	{
		//
	}
	//获取m2o视频库内容
	function getM2oVideo()
	{
		$this->hoge['curl']->setUrlPrefix(APIURL);
		$offset = $this->hoge['input']['p'] ? PAGE_NUMBER * ($this->hoge['input']['p']-1) : 0;
		$parameters = array(
		'a'				=> 'show',
		'offset'		=> $offset,
		'count'			=> $this->hoge['input']['count'] ? $this->hoge['input']['count'] : PAGE_NUMBER,
		'title'			=> $this->hoge['input']['title'] ? $this->hoge['input']['title'] : '',
		'_id'	=> $this->hoge['input']['vod_sort_id'] ? $this->hoge['input']['vod_sort_id'] : 0,
		'start_time'	=> trim($this->hoge['input']['start_time']) ? urldecode($this->hoge['input']['start_time']) : '',
		'end_time'		=> trim($this->hoge['input']['end_time']) ? urldecode($this->hoge['input']['end_time']) : '',
		'date_search'	=> $this->hoge['input']['date_search'] ? $this->hoge['input']['date_search'] : 0,
		'status'		=> VIDEO_STATUS,
		);
		$this->hoge['curl']->setRequestFile('vod.php');
		$this->hoge['curl']->setRequestParameters($parameters);
		$vdata = $this->hoge['curl']->request();
		return $vdata;
	}
	function getM2oVideoTotal($parameters = array())
	{
		if(!$parameters)
		{
			$parameters = array(
			'title'			=> $this->hoge['input']['title'] ? $this->hoge['input']['title'] : '',
			'_id'	=> $this->hoge['input']['vod_sort_id'] ? $this->hoge['input']['vod_sort_id'] : 0,
			'date_search'	=> $this->hoge['input']['date_search'] ? $this->hoge['input']['date_search'] : 0,
			'start_time'	=> trim($this->hoge['input']['start_time']) ? urldecode($this->hoge['input']['start_time']) : '',
			'end_time'		=> trim($this->hoge['input']['end_time']) ? urldecode($this->hoge['input']['end_time']) : '',
			'trans_status'		=> VIDEO_STATUS,
			);
		}
		$parameters['a'] = 'count';
		$this->hoge['curl']->setUrlPrefix(APIURL);;
		$this->hoge['curl']->setRequestFile('vod.php');
		$this->hoge['curl']->setRequestParameters($parameters);
		$total = $this->hoge['curl']->request();
		//print_r($total);exit;
		return $total['total'];
	}
	function getVideoSort()
	{
		$this->hoge['curl']->setUrlPrefix(APIURL);
		$parameters = array(
		'a'	=> 'show',
		);
		$this->hoge['curl']->setRequestFile('vod_media_node.php');
		$this->hoge['curl']->setRequestParameters($parameters);
		$sort = $this->hoge['curl']->request();
		return $sort;
	}
	function create()
	{
		$parameters = array(
		'a'=>'submit_transcode',
		'audit_auto'=>2,
		);
		$this->hoge['curl']->setUrlPrefix(MEDIASERVER);
		$this->hoge['curl']->setRequestFile('create.php');
		$this->hoge['curl']->setRequestParameters($parameters);
		$this->hoge['curl']->postFile($_FILES);
		$mediareturn = $this->hoge['curl']->request();
		return $mediareturn;
	}
}
?>