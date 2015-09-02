<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'video_fast_edit');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class  vod_fast_edit_video extends adminReadBase
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
		//查询出已经选择的视频片段
		$sql = "SELECT * FROM " .DB_PREFIX. "fast_vcr_tmp WHERE user_id = '" .$this->user['user_id']. "' AND main_id = '".intval($this->input['video_id'])."' ORDER BY order_id ASC ";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['duration'] = time_format(intval($r['end_time']) - intval($r['start_time']));
			$r['start_imgdata'] = '';
			$r['end_imgdata'] = '';
			$imgdata_path = FAST_EDIT_IMGDATA_PATH . $r['hash_id'];
			if(file_exists($imgdata_path . '_start.img'))
			{
				$r['start_imgdata'] = file_get_contents($imgdata_path . '_start.img');
			}
			
			if(file_exists($imgdata_path . '_end.img'))
			{
				$r['end_imgdata'] = file_get_contents($imgdata_path . '_end.img');
			}
			$data['videos'][] = $r;
		}
		//如果存在当前的视频，获取当前视频的信息
		if($this->input['video_id'] && !isset($data['videos']))
		{
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a','get_vod_info');
			$this->curl->addRequestData('id',$this->input['video_id']);
			$cur_video = $this->curl->request('vod.php');
			$data['videos'][] = $cur_video[0];
		}
		$data['main_id'] = intval($this->input['video_id']);
		$data['date_time'] = date('Y-m-d',TIMENOW);
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
	
    public  function fast_edit_video()
    {
    	if(!$this->input['id'])
    	{
    		$this->errorOutput(NOID);
    	}
    	
    	$return = $this->get_video_info($this->input['id']);
       	//查询临时表里面的数据
    	$sql = "SELECT * FROM " . DB_PREFIX . "fast_vcr_tmp  WHERE user_id = '" .$this->user['user_id']. "' AND main_video_id = '" .$this->input['id']. "' ORDER BY order_id ASC ";
    	$q = $this->db->query($sql);
    	$fast_vcr_tmp = array();
    	$vodinfo_id = array();
    	while($r = $this->db->fetch_array($q))
    	{
    		$fast_vcr_tmp[] = $r;
    		if(!in_array($r['vodinfo_id'],$vodinfo_id))
    		{
    			$vodinfo_id[] = $r['vodinfo_id'];
    		}
    	}
    	
    	if($vodinfo_id && !empty($vodinfo_id))
    	{
    		$video = $this->get_videos(implode(',', $vodinfo_id));
    	}
    	
    	$return['vcr_data'] = array();
    	if($fast_vcr_tmp && !empty($fast_vcr_tmp))
    	{
    		foreach($fast_vcr_tmp AS $k => $v)
    		{
    			$img = unserialize($video[$v['vodinfo_id']]['img_info']);
    			if(file_exists(FAST_EDIT_IMGDATA_PATH . $v['hash_id'] . '_start.img'))
    			{
    				$start_imgdata = file_get_contents(FAST_EDIT_IMGDATA_PATH . $v['hash_id'] . '_start.img');
    			}
    			if(file_exists(FAST_EDIT_IMGDATA_PATH . $v['hash_id'] . '_end.img'))
    			{
    				$end_imgdata   = file_get_contents(FAST_EDIT_IMGDATA_PATH . $v['hash_id'] . '_end.img');
    			}
    			$vcr = array(
    				'vodinfo_id' 	=> $v['vodinfo_id'],
    				'input_point' 	=> $v['input_point'],
    				'output_point' 	=> $v['output_point'],
    				'user_id' 		=> $v['user_id'],
    				'order_id' 		=> $v['order_id'],
    				'hash_id'		=> $v['hash_id'],
    				'src'			=> $v['img_path'],
    				'vcr_type'		=> $v['vcr_type'],
    				'start_imgdata'	=> $start_imgdata,
    				'end_imgdata'	=> $end_imgdata,
    				'frame_rate'	=> $video[$v['vodinfo_id']]['frame_rate'],
    				'duration'		=> $video[$v['vodinfo_id']]['duration'],
    				'title'			=> $video[$v['vodinfo_id']]['title'],
    				'width'			=> $video[$v['vodinfo_id']]['width'],
    				'height'		=> $video[$v['vodinfo_id']]['height'],
    				'hostwork'		=> $video[$v['vodinfo_id']]['hostwork'],
    				'video_path'	=> $video[$v['vodinfo_id']]['video_path'],
    				'video_filename'=> $video[$v['vodinfo_id']]['video_filename'],
    				'source_img' 	=> $img['host'].$img['dir'].$img['filepath'].$img['filename'],
    			);
    			$return['vcr_data'][] = $vcr;
    		}
    	}
    	//查询出快编临时添加的视频
    	$sql = " SELECT * FROM " .DB_PREFIX. "fast_add_videos_tmp WHERE main_video_id = '" .$this->input['id']. "' AND user_id = '" .$this->user['user_id']. "'";
    	$q = $this->db->query($sql);
    	$v_video_id = array();
    	$f_add_videos_tmp = array();
    	while($r = $this->db->fetch_array($q))
    	{
    		$f_add_videos_tmp[] = $r;
    		if(!in_array($r['vodinfo_id'],$v_video_id))
    		{
    			$v_video_id[] = $r['vodinfo_id'];
    		}
    	}
    	
    	if($v_video_id && !empty($v_video_id))
    	{
    		$_video = $this->get_videos(implode(',', $v_video_id));
    	}
    	
    	if($f_add_videos_tmp && !empty($f_add_videos_tmp))
    	{
    		foreach($f_add_videos_tmp AS $k => $v)
    		{
        		$v['img_info']  		=  $_video[$v['vodinfo_id']]['img_info'];
        		$v['title']  			=  $_video[$v['vodinfo_id']]['title'];
        		$v['duration']  		=  $_video[$v['vodinfo_id']]['duration'];
        		$v['frame_rate']  		=  $_video[$v['vodinfo_id']]['frame_rate'];
        		$v['width']  			=  $_video[$v['vodinfo_id']]['width'];
        		$v['height']  			=  $_video[$v['vodinfo_id']]['height'];
        		$v['video_path']  		=  $_video[$v['vodinfo_id']]['video_path'];
        		$v['hostwork']  		=  $_video[$v['vodinfo_id']]['hostwork'];
        		$v['video_filename']  	=  $_video[$v['vodinfo_id']]['video_filename'];
        		$v['source_img'] 		=  $_video[$v['vodinfo_id']]['source_img'];
        		$v['video_url']  		=  $_video[$v['vodinfo_id']]['video_url'];
    			$return['added_videos'][] = $v;
    		}
    	}
        $return['vod_leixing'] = $this->settings['video_upload_type'];
        $return['date_search'] = $this->settings['date_search'];
    	$this->addItem($return);
    	$this->output();
    }
    
 	//获取片头，片尾或者片花数据
    public function getVcrData()
    {
    	$vcr_type = intval($this->input['vcr_type']);
    	$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
    	$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('a','get_vod_info');
		$curl->addRequestData('vcr_type',$vcr_type);
		$curl->addRequestData('count',10);
		$video = $curl->request('vod.php');
		$ret = array();
		foreach($video AS $k => $v)
		{
			$vcr = array(
    			'id'			=> $v['id'],
    			'title' 		=> $v['title'],
    			'duration' 		=> $v['duration_num'],
    			'user_id' 		=> $v['user_id'],
    			'frame_rate'	=> $v['frame_rate'],
    			'width'			=> $v['width'],
    			'height'		=> $v['height'],
    			'hostwork'		=> $v['hostwork'],
    			'video_path'	=> $v['video_path'],
    			'video_filename'=> $v['video_filename'],
    			'source_img' 	=> $v['img'],
    			'video_url' 	=> $v['video_url'],
			    'video_m3u8' 	=> $v['video_m3u8'],
    		);
    		$this->addItem($vcr);
		}
		$this->output();
    }
    
	//请求视频库接口获取某个视频信息
    public function get_video_info($id)
    {
    	$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
    	$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',$id);
		$curl->addRequestData('a','detail');
		$video = $curl->request('vod.php');
		return $video[0];
    }
    
    public function get_videos($id)
    {
    	$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
    	$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',$id);
		$curl->addRequestData('a','get_videos');
		$video = $curl->request('vod.php');
		return $video[0];
    }
}

$out = new vod_fast_edit_video();
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