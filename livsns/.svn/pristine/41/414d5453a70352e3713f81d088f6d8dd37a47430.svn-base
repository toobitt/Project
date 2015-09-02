<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'live_split');
define('CUR_CONF_PATH', '../');
require_once('global.php');
require(CUR_CONF_PATH."lib/functions.php");
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
class  live_split_update extends adminUpdateBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	public function update(){}
	public function delete(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}
	
	public function create_live_to_vod()
	{
		if(!$this->settings['App_live_time_shift'])
		{
			$this->errorOutput('时移应用未安装');
		}
		$curl = new curl($this->settings['App_live_time_shift']['host'],$this->settings['App_live_time_shift']['dir'] . 'admin/');
  	    $curl->setSubmitType('post');
		$curl->initPostData();
		$live_id = (int)$this->input['live_id'];
		/**
		 * 
		 * 日期处理
		 * @var unknown_type
		 */
		$datetime = date('Y-m-d');
		$start_time = trim(urldecode($this->input['start_time']));
		if(empty($start_time))
		{
			$this->errorOutput('开始时间不能为空');
		}
		$s_start_time = strtotime($start_time);		
		$start_time = $datetime .' '.date('H:i:s',$s_start_time);
		$s_start_time = strtotime($start_time);
		
		$end_time = trim(urldecode($this->input['end_time']));
		if(empty($end_time))
		{
			$this->errorOutput('结束时间不能为空');
		}
		$s_end_time = strtotime($end_time);
		$end_time = $datetime .' '.date('H:i:s',$s_end_time);
		$s_end_time = strtotime($end_time);
		
		
		if($s_end_time > TIMENOW)
		{
			//$this->errorOutput('结束时间不能大于当前时间');
			$s_end_time = TIMENOW;
		}	
		if ($s_start_time > $s_end_time)
		{
			$this->errorOutput('开始时间不能大于结束时间');
		}
		else if ($s_start_time == $s_end_time)
		{
			$this->errorOutput('开始时间与结束时间不能相同');
		}
		else if ($s_end_time - $s_start_time > (int)$this->settings['live_time_shift_max_time'] * 60)
		{
			$this->errorOutput('直播拆条拆过最大限制时间');
		}
		
		/*************检测频道流信息******************************************/
		$condition['id'] = $live_id;
		$condition['fetch_live'] = 1;
		$channel = $this->getChannelInfo($condition);
		if(!$channel)
		{
			$this->errorOutput('该频道已经不存在！');
		}
		
		if(!$channel['status'])
		{
			$this->errorOutput('该频道流未开启');
		}
		
		if(!$channel['is_mobile_phone'])
		{
			$this->errorOutput('该频道未启动手机流，无法获取时移数据！');
		}
		$save_time = TIMENOW-(($channel['time_shift']*3600)-($channel['delay']));
		if($s_start_time < $save_time)
		{
			$this->errorOutput('此条时移已超过回看时间！');
		}
		/*************监测频道流信息******************************************/
		
		$input = array(
		    'channel_id'  => $live_id,
		    'live_time_shift_title' => '直播拆条_'.$id.'_'.$live_id.'_'.$start_time.'_'.$end_time,
			'start_time' => $s_start_time,
			'end_time'   => $s_end_time,
			'create_time' => TIMENOW,
		);
		$id = $this->db->insert_data($input, 'live_data');
		$input['start_time'] = $start_time;
		$input['end_time'] = $end_time;
		if($id <= 0)
		{
			$this->errorOutput('拆条数据创建失败');
		}
		$input['title'] = $input['live_time_shift_title'];
		unset($input['live_time_shift_title']);
		$input['access_token'] = $this->user['token'];
		$input['outputtype'] = 1;
		$input['live_split_callback'] = $id;
		if(is_array($input))
		{
			foreach ($input as $k => $v)
			{
				$this->array_to_add($curl,$k, $v);
			}
		}
		$curl->addRequestData('a', 'create');
		$ret = $curl -> request('live_time_shift_update.php');
		if(empty($ret))
		{
			$this->errorOutput('直播视频截取请求失败');
		}
		if((isset($ret['ErrorText']) && $ret['ErrorText']) || (isset($ret['ErrorCode']) && $ret['ErrorCode']))
		{
			$_message = $ret['ErrorText'] ? $ret['ErrorText'] : $ret['ErrorCode'] ;
			$this->errorOutput($_message);
		}
		$update_data = array('status'=> 1, 'live_time_shift_id' => $ret[0]['id']); 
		$this->db->update_data($update_data, 'live_data' ,'id = '.$id);
		$data = $this->db->query_first('SELECT * FROM '.DB_PREFIX.'live_data WHERE id = '.$id);
		$this->setAddItemValueType();
		$this->addItem($data);
		$this->output();
		
	}
	
	public function array_to_add(curl $curl,$str, $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if (is_array($vv))
				{
					$this->array_to_add($curl, $str . "[$kk]" , $vv);
				}
				else
				{
					$curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
		else
		{
			$curl->addRequestData($str, $data);
		}
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
		if($this->settings['App_live'])
		{
			$curl = new curl($this->settings['App_live']['host'], $this->settings['App_live']['dir']);
		}
		else 
		{
			$this->errorOutput('直播应用未安装');
		}
		if (!$curl)
		{
			return array();
		}
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'show');
		foreach ($data AS $k => $v)
		{
			$curl->addRequestData($k, $v);
		}
		$ret = $curl->request('channel.php');
		return $ret[0];
	}
	
	public function time_shift_callbck()
	{
		$live_split_id = (int)$this->input['live_split_id'];
		$video_id = (int)$this->input['video_id'];
		$shift = json_decode(html_entity_decode($this->input['data']),1);
		if(!empty($shift) && $shift['extend'])
		{
			$shift['extend'] = json_decode(base64_decode($shift['extend']),1);
		}
		if($shift)
		{
			switch ($shift['exit_status'])
			{
				case 0:$status = -1;break;
			}
			if($video_id)
			{
				$update['video_id'] = $video_id;
				$status = 3;
			}
			else if($status != -1)
			{
				$status = 2;//如果视频id不存在则认为提交转码失败
			}
			$update['status'] = $status;
			$shift['duration'] && $update['duration']  = $shift['duration'];
			$shift['file_path'] && $update['file_path'] = $shift['file_path'];
			$live_split_id && $this->db->update_data($update, 'live_data','id = '.$live_split_id);
		}
		$this->addItem($update);
		$this->output();
	}
	
	//提交拆条
	public function add_to_live_mark()
	{	
		$live_data_id = (int)$this->input['live_data_id'];
		if(!$live_data_id)
		{
			$this->errorOutput('直播视频数据ID不存在');
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'live_data WHERE id = '.$live_data_id;
		$live_data = $this->db->query_first($sql);
		$live_id = $live_data['channel_id'];
		if(!$live_id)
		{
			$this->errorOutput('直播频道ID');
		}
		
		$this->input['id'] = $live_data['video_id'];
		
		if(!$this->input['id'])
		{
			$this->errorOutput('直播视频ID不存在');
		}
		
		$vod_sort_id = $this->input['vod_sort_id']?$this->input['vod_sort_id']:4;
		
		$duration = intval($this->input['end_time']) - intval($this->input['start_time']);
		if($duration <= 0)
		{
			//$this->errorOutput(END_TIME_CAN_NOT_SMALLER_START_TIME);
		}
		$original_id = $this->input['id'];//来自于哪个视频（不管是拆条还是编辑拆条都是传过来的id）
		$is_url = 0;
		//此处是为了判断图片是不是链接，如果是链接就没有必要传过去更新（因为只有在编辑拆条的时候才会出现图片是链接）
		if($this->check_img_url($this->input['imgdata']))
		{
			$is_url = 1;
		}
		else 
		{
			$img_info = serialize($this->imgdata2pic($this->input['imgdata']));
		}
		if($this->input['split_id'])
		{
			$this->input['id'] = $this->input['split_id'];
			$a = 'edit_mark';
			/*********************权限管理*****************/
			if($this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				$prms['_action'] 	= 'update';
				$prms['id'] 		= $this->input['id'];
				$prms['node'] 		= $vod_sort_id ;
				$this->verify_self_prms($prms);
			}
			/*********************权限管理*****************/
		}
		else 
		{
			$a = 'add_live_mark';
			/*********************权限管理*****************/
			if($this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				$prms['_action'] 	= 'create';
				$prms['node'] 		= $vod_sort_id ;
				$this->verify_self_prms($prms);
			}
			/*********************权限管理*****************/
		}
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',$this->input['id']);
		$curl->addRequestData('a',$a);
		$curl->addRequestData('title',$this->input['title']);//标题
		$curl->addRequestData('comment',$this->input['comment']);//描述
		$curl->addRequestData('keywords',$this->input['keywords']);//关键字
		$curl->addRequestData('vod_sort_id',$vod_sort_id);//分类
		$curl->addRequestData('live_id',$live_id);//$live_id
		if($this->input['column_id'])//发布的栏目
		{
			$curl->addRequestData('column_id',$this->input['column_id']);
		}
		
		if(!$is_url)
		{
			$curl->addRequestData('img_info',$img_info);
		}
		$curl->addRequestData('start_time[0]',$this->input['start_time']);
		$curl->addRequestData('duration[0]',$duration);
		$curl->addRequestData('original_id[0]',$original_id);
		$curl->addRequestData('name[0]',$this->input['title']);
		$curl->addRequestData('order_id[0]',1);
		$ret = $curl->request('vod_add_video_mark.php');
		if($ret && $ret[0] && $ret[0]['column_id'] && !is_array($ret[0]['column_id']))
		{
			$ret[0]['column_id'] = unserialize($ret[0]['column_id']);
		}
		$ret && $ret[0] && $this->update_live_data($live_data_id, $ret[0]);
		$this->addItem($ret);
		$this->output();
	}
	public function update_live_data($live_data_id,$data)
	{
		$update_data = array('status'=> 4, 'live_mark_video_id' => $data['id']); 
		$live_data_id && $this->db->update_data($update_data, 'live_data' ,'id = '.$live_data_id);
	}
	
	public function imgdata2pic($imgdata)
	{
		//生成图片
		$data  = explode(',',$imgdata);
		$data1 = explode(';',$data[0]);
		$type  = explode('/',$data1[0]);
		$material = new material();
    	$img_info = $material->imgdata2pic($data[1],$type[1]);
		$img_info = $img_info[0];
		$image_info = array(
			'host' 		=> $img_info['host'],
			'dir' 		=> $img_info['dir'],
			'filepath' 	=> $img_info['filepath'],
			'filename' 	=> $img_info['filename'],
		);
		return $image_info;
	}
	
	//判断是不是图片链接
	private function check_img_url($url = '')
	{
		if(substr($url,0,7) == 'http://')
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	private function verify_self_prms($data = array())
	{
		$action  = $data['_action'] ? $data['_action'] : $this->input['a'];
		if ($this->user['user_id'] < 1)
		{
			$this->errorOutput(USER_NOT_LOGIN);
		}
		
		if($this->user['group_type'] <= MAX_ADMIN_TYPE)
		{
			return;
		}
		
		if(!in_array($action,(array)$this->user['prms']['app_prms']['livmedia']['action']))
		{
			$this->errorOutput(NO_PRIVILEGE);
		}
		
		if($data['id'])
		{
			$manage_other_data = $this->user['prms']['default_setting']['manage_other_data'];
			if(!$manage_other_data)
			{
				if($this->user['user_id'] != $data['user_id'])
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}
			//1 代表组织机构以内
			if($manage_other_data == 1 && $this->user['slave_org'])
			{
				if(!in_array($data['org_id'], explode(',', $this->user['slave_org'])))
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}
		}
		
		if($data['node'])
		{
			$all_prms_nodes = $this->get_childs_nodes();
			if(!in_array($data['node'],$all_prms_nodes))
			{
				$this->errorOutput(NO_PRIVILEGE);
			}
		}
	}
	
	private function get_childs_nodes()
	{
		$prms_nodes = implode(',',$this->user['prms']['app_prms']['livmedia']['nodes']);
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
    	$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',$prms_nodes);
		$curl->addRequestData('a','get_childs_nodes');
		$nodes = array();
		$nodes = $curl->request('vod.php');
		if($nodes && $nodes[0])
		{
			$nodes = $nodes[0];
		}
		return $nodes;
	}
	
}
$out = new live_split_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'add_to_vod_mark';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>