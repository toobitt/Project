<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'video_split');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class  video_split extends adminReadBase
{
	var $curl;
    public function __construct()
	{
		$this->mPrmsMethods = array(
			'manger' => '管理',
		);
		parent::__construct();
		//此处是为了判断视频库有没有安装
		if(!$this->settings['App_livmedia']['host'])
		{
			$this->errorOutput('please install livmedia first');
		}
		$this->curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail(){}
	public function count(){}
	public function index(){}
	
	public function show()
	{
		$data = array('date_time' => date('Y-m-d',TIMENOW));
		if($this->input['video_id'])
		{
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a','get_vod_info');
			$this->curl->addRequestData('id',$this->input['video_id']);
			$cur_video = $this->curl->request('vod.php');
			$data['cur_video'] = $cur_video[0];
		}
    	$this->addItem($data);
    	$this->output();
	}
	
	public function get_vod_info()
	{
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['counts'] ? intval($this->input['counts']) : 20;
		$offset = intval(($pp - 1)*$count);			
		$vod_sort_id = intval($this->input['vod_sort_id']);
		$vod_data = array(
			'offset'	  => $offset,
			'count'		  => $count,
			'vod_sort_id' => $vod_sort_id,
			'pp'		  => $pp,
			'k'	      	  => $this->input['title'],
			'date_search' => $this->input['date_search'],
			'status'	  => '1,2,3',//取状态是待审核,已审核,已打回的视频
			'start_time'  => $this->input['start_time'],
			'end_time'    => $this->input['end_time'],
			'user_name'	  => $this->input['user_name'],
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
		//获取分页的参数
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_page_data');
		$this->curl->addRequestData('self_group_type',$this->user['group_type']);
		foreach ($vod_data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$page_data = $this->curl->request('vod.php');
		$data['page'] = $page_data;
		$data['date_search'] = $this->settings['date_search'];
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
		$this->addItem($ret);
		$this->output();
	}
	//获取视频的进度
	public function get_video_status()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_video_status');
		$this->curl->addRequestData('id',$this->input['id']);
		$ret = $this->curl->request('vod.php');
		foreach($ret[0]['status_data'] as $k => $v)
		{
			if($v['transcode_percent'] < 0)
			{
				$ret[0]['status_data'][$k]['transcode_percent'] = 0;
			}
		}
		$this->addItem($ret[0]);
		$this->output();
	}
	
 	//获取所有从该视频拆分出去的视频
    public function get_split_videos()
    {
    		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('id',$this->input['id']);
		$this->curl->addRequestData('a','get_split_videos');
		$video = $this->curl->request('vod.php');
		$this->addItem($video[0]);
   	 	$this->output();
    }
}

$out = new video_split();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>