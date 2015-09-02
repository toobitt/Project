<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'live_split');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class  live_split extends adminReadBase
{
	var $livecurl;
	var $mediacurl;
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
		$this->mediacurl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
		
		
		if(!$this->settings['App_live']['host'])
		{
			$this->errorOutput('please install live first');
		}
		$this->livecurl = new curl($this->settings['App_live']['host'],$this->settings['App_live']['dir']);
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
		if($this->input['live_id'])
		{
			$this->livecurl->setSubmitType('post');
			$this->livecurl->setReturnFormat('json');
			$this->livecurl->initPostData();
			$this->livecurl->addRequestData('a','show');
			$this->livecurl->addRequestData('id',$this->input['live_id']);
			$cur_video = $this->livecurl->request('channel.php');
			$data['cur_video'] = $cur_video[0];
		}
    	$this->addItem($data);
    	$this->output();
	}
	
	public function get_video_info($video_id)
	{
		$ret = array();
		if($video_id)
		{
			$this->mediacurl->setSubmitType('post');
			$this->mediacurl->setReturnFormat('json');
			$this->mediacurl->initPostData();
			$this->mediacurl->addRequestData('a','get_vod_info');
			$this->mediacurl->addRequestData('id',$this->input['video_id']);
			$cur_video = $this->mediacurl->request('vod.php');
			$ret = $cur_video[0];
		}
    	return $ret;
	}
	
	public function getConfig()
	{
		$config_data = array();
		$config_data['live_time_shift_open'] = $this->settings['live_time_shift_open'];
		$config_data['date_time'] = TIMENOW;
		if(!$this->settings['App_live']['host'])
		{
			$config_data['live_time_shift_open'] = 0;
		}
		$this->setAddItemValueType();
		$this->addItem($config_data);
		$this->output();
	}
	public function get_info()
	{
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['counts'] ? intval($this->input['counts']) : 20;
		$offset = intval(($pp - 1)*$count);			
		$live_sort_id = intval($this->input['live_sort_id']);
		$vod_data = array(
			'offset'	  => $offset,
			'count'		  => $count,
			'node_id' => $live_sort_id,
			'pp'		  => $pp,
			'k'	      	  => $this->input['title'],
			'date_search' => $this->input['date_search'],
			'status'	  => '1',//取状态是待审核,已审核,已打回的视频
			'start_time'  => $this->input['start_time'],
			'end_time'    => $this->input['end_time'],
			'user_name'	  => $this->input['user_name'],
		);
		$this->livecurl->setSubmitType('post');
		$this->livecurl->setReturnFormat('json');
		$this->livecurl->initPostData();
		$this->livecurl->addRequestData('a','show');
		$this->livecurl->addRequestData('self_group_type',$this->user['group_type']);
		
		foreach ($vod_data AS $k => $v)
		{
			$this->livecurl->addRequestData($k, $v);
		}
		$ret = $this->livecurl->request('channel.php');
		foreach ($ret as $k => $v)
		{
			$ret[$k]['mark_count'] = count((array)$this->get_split_live_video($v['id']));
		}
		$data['video'] = $ret;
		//获取分页的参数
		$this->livecurl->initPostData();
		$this->livecurl->addRequestData('a','get_page_data');
		$this->livecurl->addRequestData('self_group_type',$this->user['group_type']);
		foreach ($vod_data AS $k => $v)
		{
			$this->livecurl->addRequestData($k, $v);
		}
		$page_data = $this->livecurl->request('channel.php');
		$data['page'] = $page_data;
		$data['date_search'] = $this->settings['date_search'];
		$this->addItem($data);
		$this->output();
	}
	
	//获取直播节点
	public function get_node()
	{
		$this->livecurl->setSubmitType('get');
		$this->livecurl->initPostData();
		$this->livecurl->addRequestData('fid', $this->input['fid']);
		$this->livecurl->addRequestData('self_group_type',$this->user['group_type']);
		$ret = $this->livecurl->request('channel_node.php');
		$this->addItem($ret);
		$this->output();
	}
	public function get_make_livevideo_status()
	{
		$live_data_id = (int)$this->input['live_data_id'];
		if(!$live_data_id)
		{
			$this->errorOutput('直播拆条数据ID不能为空');
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'live_data WHERE id ='.$live_data_id;
		$data = $this->db->query_first($sql);
		if(empty($data))
		{
			$this->errorOutput('此直播拆条数据不存在');
		}
		
		if($data['status'] == -1)
		{
			$this->errorOutput('直播视频截取失败');
		}
		
		if($data['status'] == 0)
		{
			$this->errorOutput('直播视频截取请求失败');
		}
		
		if($data['status'] == 1 && $data['create_time'] + $this->settings['live_time_shift_timeout'] < TIMENOW)
		{
			$this->errorOutput('直播视频创建超时');
		}
		
		if($data['status'] == 1)
		{
			$ret['status'] = 1;
			$ret['message'] =  '直播视频生成中,请稍后...';
		}
		
		if($data['status'] == 2)
		{
			
			$this->errorOutput('直播视频转码提交失败');
		}
		if($data['status'] == 3)
		{
			$videoInfo = $this->get_video_info($data['video_id']);
			if($videoInfo['status_display'] == 0)
			{
			  $ret['status'] = 4;
			  $ret['message'] =  '直播视频转码中';
			}
			else if ($videoInfo['status_display'] == -1)
			{
				$this->errorOutput('直播视频转码失败');
			}
			else {
				$ret['status'] = 3;
				$ret['message'] =  '直播视频生成成功';
			    $ret['videoinfo'] = $videoInfo;
			}
		}
		
		$this->setAddItemValueType();
		$this->addItem($ret);
		$this->output();
		
	}
	//获取视频的进度
	public function get_live_video_status()
	{
		if(!$this->input['live_data_id'])
		{
			$this->errorOutput('直播拆条数据ID不能为空');
		}
		$live_data = $this->get_live_data($this->input['live_data_id']);
		if(!$live_data['live_mark_video_id'])
		{
			$this->errorOutput('拆条失败');
		}
		$this->mediacurl->setSubmitType('get');
		$this->mediacurl->setReturnFormat('json');
		$this->mediacurl->initPostData();
		$this->mediacurl->addRequestData('a','get_video_status');
		$this->mediacurl->addRequestData('id',$live_data['live_mark_video_id']);
		$ret = $this->mediacurl->request('vod.php');
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
	
	
	public function get_live_data($live_data_id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'live_data WHERE id ='.(int)$live_data_id;
		return $this->db->query_first($sql);
	}
	
 	//获取所有从该视频拆分出去的视频
    public function get_split_live_videos()
    {
    	if(!$this->input['live_data_id'] && !$this->input['live_id'])
		{
			$this->errorOutput('直播频道ID不能为空');
		}
		$live_data = $this->get_live_data($this->input['live_data_id']);
		$channel_id = $live_data['channel_id'] ? $live_data['channel_id'] : (int)$this->input['live_id'];
		if(!$channel_id)
		{
			$this->errorOutput('直播频道ID不能为空');
		}
    	$video = $this->get_split_live_video($channel_id);
		$this->addItem($video);
   	 	$this->output();
    }
    
    private function get_split_live_video($channel_id)
    {
    	$this->mediacurl->setSubmitType('get');
		$this->mediacurl->initPostData();
		$this->mediacurl->addRequestData('live_id',$channel_id);
		$this->mediacurl->addRequestData('a','get_split_live_videos');
		$video = $this->mediacurl->request('vod.php');
		return $video[0];
    }
}

$out = new live_split();
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