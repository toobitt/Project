<?php
/***************************************************************************
* $Id: tvie.class.php 27724 2013-08-22 01:16:23Z tong $
***************************************************************************/
class tvie extends InitFrm
{
	private $mTvie;
	public function __construct()
	{
		parent::__construct();

		include_once ROOT_PATH . 'lib/class/curl.class.php';
		$this->mTvie = new curl();
//		$this->mTvie->mPostContentType('string');
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function getApiToken($host, $dir, $super_token)
	{
		if (!$this->mTvie)
		{
			return array();
		}
		$this->mTvie->setUrlHost($host, $dir);
		$this->mTvie->setSubmitType('get');
		$this->mTvie->initPostData();
		$this->mTvie->setReturnFormat('json');
		$this->mTvie->addRequestData('super_token', $super_token);
		$ret = $this->mTvie->request('list');
		return $ret;
	}
	
	public function getServiceInfo($host, $dir, $data = array())
	{
		if (!$this->mTvie)
		{
			return array();
		}
		$this->mTvie->setUrlHost($host, $dir);
		$this->mTvie->setSubmitType('get');
		$this->mTvie->initPostData();
		$this->mTvie->setReturnFormat('json');
		
		/*
		$action = array('insert', 'update', 'delete', 'select', 'start', 'stop');
		
		if (!in_array($data['action'], $action))
		{
			return false;
		}
		*/
		foreach ($data AS $k => $v)
		{
			$this->mTvie->addRequestData($k, $v);
		}
		
		$ret = $this->mTvie->request('info');
		return $ret;
	}
	
	/**
	 * 根据 显示名称、流名称 查询 (支持模糊查询) post
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $dir
	 * @param unknown_type $data
	 * $data = array(
			'api_token' 	=> '123456',
			'page_size' 	=> '10',				//每页的个数；最大返回的个数 	可以为空
			'offset' 		=> '0',					//从全部满足条件的第几个输出	可以为空
			'output_urls' 	=> false,				//是否显示单码率地址	true or false
			'query' 		=> array(				//查询条件	可以为空
				'type'	=> 'item',					//查询条件类型	and, or, item
				'item'	=> array(					//单个查询条件	当type为item时存在
					'name'		  => 'display_name',//查询名称
					'value'		  => 'bjws',		//查询值
					'exact_match' => true,			//是否完全匹配
				),
			),
			'order_by'		=> 'name',				//按某类型排序	可以为空
			'order_type'	=> 'desc',				//排序方法	Desc或asc  可以为空
		);
		返回
		200 OK
		{"streams": [
			{
				"username": null,
				"display_name": "adfaa",
				"name": "abca",
				"upstreams": "http://10.33.0.62/channels/preview/3/flv:2000K",
				"stream_map": "0:0:0;0:1:1",
				"emulate_rate": 1,
				"state": "stopped",
				"start_on_server_startup": 0,
				"urls": {
					"hds": {
						"single_bitrate": [],
						"multi_bitrate": "http://127.0.0.1/live/abca/manifest.f4m"
					},
					"hls": {
						"single_bitrate": ["http://127.0.0.1/live/abca/manifest.m3u8"],
						"multi_bitrate": "http://127.0.0.1/live/abca/manifest.m3u8"
					}, 
					"ts": {
						"single_bitrate": ["http://127.0.0.1/live/abca/manifest.ts"]
					}, 
					"flv": {
						"single_bitrate": ["http://127.0.0.1/live/abca/manifest.flv"]
					}
				},
				"type": "pull",
				"auto_recover": 1,
				"password": null,
				"save_time": 2,
				"id": 2,
				"pubpoints": "http://10.33.0.167/ldsfive/abca/manifest.ismv"
			}
		}
		或者
		200 OK
		{"result":  “error”,  “error_code”: xxx,  sub_codes:{“code”: “desc”} }

	 */
	public function getLiveSearch($host, $dir, $data = array())
	{
		$file = 'search';
		$ret = $this->curl_json($host, $dir, $file, $data);
		return json_decode($ret, true);
	}

	/**
	 * 直播流编辑 (add、edit) post
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $dir
	 * @param unknown_type $file
	 * @param unknown_type $data
	 * 
	 * add
	 * $data = array(
			'api_token' 	=> '123456',
			'type' 			=> '流类型, 推送或拉取',//push, pull
			'display_name' 	=> '显示名称',
			'auto_recover' 	=> '是否自动恢复',
			'name' 			=> '流名称',
			'pubpoints' 	=> '发布点',
			'emulate_rate' 	=> '模拟码率',//true=>文件流, false=>直播流
			'save_time' 	=> '时移数据保存时间',//秒
			'stream_map' 	=> '0:0:0;0:1:1',
			'upstreams' 	=> '上游地址',
			'username' 		=> '',
			'password' 		=> '',
			'start_on_server_startup' => '是否在推送流服务启动时启动',
		);
		返回
		200 OK
		{"result": "ok"}
		或者
		200 OK
	   	{"result":  “error”,  “error_code”: xxx,  sub_codes:{“code”: “desc”}}
	 *
	 * edit
	 * $data = array(
			'api_token' 	=> '123456',
			'type' 			=> '流类型, 推送或拉取',//push, pull
			'display_name' 	=> '显示名称',
			'auto_recover' 	=> '是否自动恢复',
			'pubpoints' 	=> '发布点',
			'emulate_rate' 	=> '模拟码率',//true=>文件流, false=>直播流
			'save_time' 	=> '时移数据保存时间',//秒
			'stream_map' 	=> '0:0:0;0:1:1',
			'upstreams' 	=> '上游地址',
			'username' 		=> '',
			'password' 		=> '',
			'start_on_server_startup' => '是否在推送流服务启动时启动',
		);
		返回
		200 OK
		{"result": "ok"}
		或者
		200 OK
	   	{"result":  “error”,  “error_code”: xxx,  sub_codes:{“code”: “desc”}}
	 */
	public function liveEdit($host, $dir, $file, $data = array())
	{
		$data['secure_link'] = false;
		$data['secure_link_period'] = 14400;
		//file_put_contents('../cache/t.txt', var_export($data, 1));
		$ret = $this->curl_json($host, $dir, $file, $data);
		return json_decode($ret, true);
	}

	/**
	 * 直播流 启动、停止、重启、删除 (start、stop、restart、delete) get
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $dir
	 * @param unknown_type $file
	 * @param unknown_type $data
	 * 
	 * $data = array(
			'api_token' => '123456',
		);
		返回
		200 OK
		{"result": "ok"}
		或者
		200 OK
		{"result":  “error”,  “error_code”: xxx,  sub_codes:{“code”: “desc”} }
	 */
	public function liveOperate($host, $dir, $file, $data = array())
	{
		if (!$this->mTvie)
		{
			return array();
		}
		$this->mTvie->setUrlHost($host, $dir);
		$this->mTvie->setSubmitType('get');
		$this->mTvie->initPostData();
		$this->mTvie->setReturnFormat('json');
		
		$action = array('start', 'stop', 'restart', 'delete');
		
		if (!in_array($file, $action))
		{
			return false;
		}
		foreach ($data AS $k => $v)
		{
			$this->mTvie->addRequestData($k, $v);
		}
		
		$ret = $this->mTvie->request($file);
		return $ret;
	}
	
	/**
	 * 用于将数组直接用json的方式提交到某一个地址
	 */
    public function curl_json($host, $dir, $file, $data, $protocol = 'http://')
	{
		$url = $protocol . $host . '/' . $dir . $file;
		//file_put_contents('../cache/t1.txt', $url . var_export($data, 1));
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		$response  = curl_exec($ch);
		//file_put_contents('../cache/t2.txt', $response);
		$head_info = curl_getinfo($ch);
		if($head_info['http_code']!= 200)
		{
			$error = array('result' =>'fail');
			return json_encode($error);
		}
		curl_close($ch);//关闭
		return $response;
	}
}
?>