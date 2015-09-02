<?php
require('./global.php');
define('SCRIPT_NAME', 'mmobject');
define('MOD_UNIQUEID', 'mmobject');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class mmobject extends adminBase
{
	protected $curl;
	function __construct()
	{
		parent::__construct();
		//此处是为了判断视频库有没有安装
		if(!$this->settings['App_livmedia']['host'])
		{
			$this->errorOutput('please install livmedia first');
		}
		$this->curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		$offset     = $this->input['offset'] ? intval($this->input['offset']) : 0;//如果没有传第几页，默认是第一页	
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		//$offset = intval(($pp - 1)*$count);			
		$vod_sort_id = intval($this->input['vod_sort_id']);
		$vod_data = array(
			'offset'	  => $offset,
			'count'		  => $count,
			'vod_sort_id' => $vod_sort_id,
			'pp'		  => $pp,
			'k'	      => urldecode($this->input['k']),
			'date_search' => $this->input['date_search'],
		);
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_vod_info');
		$this->curl->addRequestData('self_group_type',$this->user['group_type']);
		
		foreach ($vod_data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('vod.php');
		$data['video'] = $ret;
		$this->addItem($data);
		$this->output();
	}
	//获取视频节点
	public function get_vod_node()
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('fid', $this->input['fid']);
		$this->curl->addRequestData('self_group_type',$this->user['group_type']);
		$ret = $this->curl->request('vod_media_node.php');
		//$this->addItem($ret);
		if($ret && is_array($ret))
		{
			foreach($ret as $r)
			{
				$this->addItem($r);
			}
		}
		$this->output();
	}
	public function count()
	{
		//echo json_encode(array('total'=>'200'));exit;
		$offset     = $this->input['offset'] ? intval($this->input['offset']) : 0;//如果没有传第几页，默认是第一页	
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		//$offset = intval(($pp - 1)*$count);	
		//获取分页的参数
		$vod_data = array(
			'offset'	  => $offset,
			'count'		  => $count,
			//'vod_sort_id' => $vod_sort_id,
			//'pp'		  => $pp,
			//'k'	      => urldecode($this->input['k']),
			//'date_search' => $this->input['date_search'],
		);
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_page_data');
		//$this->curl->addRequestData('self_group_type',$this->user['group_type']);
		foreach ($vod_data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$page_data = $this->curl->request('vod.php');
		echo json_encode(array('total'=>$page_data[0]['total_num']));exit;
	}
}
include ROOT_PATH . 'excute.php';