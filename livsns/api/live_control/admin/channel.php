<?php
/***************************************************************************
* $Id: channel.php 22542 2013-05-21 02:23:15Z lijiaying $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','channel');
class channelApi extends adminReadBase
{
	private $mLive;
	private $mProgram;
	public function __construct()
	{
		$this->mPrmsMethods = array(
		'show'			=>'查看',
		'change'		=>'切播',
	//	'manage'		=>'管理',
		'_node'=>array(
			'name'=>'频道分类',
			'filename'=>'channel_node.php',
			'node_uniqueid'=>'channel_node',
			),
		);
		parent::__construct();
		require_once ROOT_PATH . 'lib/class/live.class.php';
		$this->mLive = new live();
		
		require_once(ROOT_PATH . 'lib/class/program.class.php');
		$this->mProgram = new program();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index()
	{
		
	}

	/**
	 * 取频道信息
	 * $offset 分页参数
	 * $count 分页参数
	 * $is_audio 是否是音频 (1-音频 0-视频)
	 * Enter description here ...
	 */
	public function show()
	{
		#####
		$this->verify_content_prms();
		#####
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$count 	= $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		
		$condition['offset'] 	= $offset;
		$condition['count'] 	= $count;
		$condition['field'] 	= 'id, name, code, is_control, is_audio, is_mobile_phone, server_id, logo_rectangle, logo_square, node_id, status, client_logo';
		
		$info = $this->mLive->getChannelInfo($condition);
		
		if ($info)
		{
			$channel_ids = array();
			foreach ($info AS $v)
			{
				$channel_ids[] = $v['id'];
			}
			
			$channel_ids = implode(',', $channel_ids);
			
			//获取当前频道预览图片、节目单
			$current_info = $this->get_current_info($info, $channel_ids);
			
			foreach ($info AS $v)
			{
				$v['current_info'] = $current_info[$v['id']];
				$v['channel_ids']  = $channel_ids;
				$this->addItem($v);
			}
		}
		
		$this->output();
	}
	
	public function detail()
	{
		#####
		$this->verify_content_prms();
		#####
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$return = $this->mLive->getChannelCount($condition);
		echo json_encode($return);
	}
	
	private function get_condition()
	{
		$condition = array();
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$is_action = trim($this->input['a']) == 'show' ? true:false;
			if($is_action && $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$tmp_node = implode(',',$this->user['prms']['app_prms'][APP_UNIQUEID]['nodes']);
				$all_node = $this->mLive->getChildNodeByFid($tmp_node);
				$all_node = array_unique(explode(',',implode(',',$all_node)));
				if(intval($this->input['_id']))
				{
					if(in_array(intval($this->input['_id']),$all_node))
					{
						$condition['node_id'] = intval($this->input['_id']);
					}
					else
					{
						$condition['node_id'] = -1;
					}
				}
				else
				{
					$condition['node_id'] = implode(',',$this->user['prms']['app_prms'][APP_UNIQUEID]['nodes']);
				}
			}
		}
		else
		{
			if(intval($this->input['_id']))
			{
				$condition['node_id'] = intval($this->input['_id']);
			}
		}
		return $condition;
	}
	
	/**
	 * 获取频道当前预览图片、节目单
	 * Enter description here ...
	 * @param unknown_type $channel_info
	 */
	public function get_current_channel_info()
	{
		$channel_id = trim($this->input['channel_id']);
		if ($channel_id)
		{
			$channel_data = array(
				'id'		=> $channel_id,
				'is_stream'	=> 0,
				'field'		=> 'id, is_audio, status, logo_square, server_id, logo_rectangle, client_logo',
			);
			
			$channel_info = $this->mLive->getChannelInfoById($channel_data);
		
			if (!empty($channel_info))
			{
				$return = $this->get_current_info($channel_info, $channel_id);
			}
		}
		$this->addItem($return);
		$this->output();
	}
	
	private function get_current_info($channel_info, $channel_ids)
	{
		$ret_program = $this->mProgram->getCurrentProgram($channel_ids);
		
		$program = array();
		if (!empty($ret_program))
		{
			foreach ($ret_program AS $v)
			{
				$program[$v['channel_id']] = $v['theme'];
			}
		}
		
		$ret_img_url = $this->get_img_url();
		$img_url	 = '';
		if (!empty($ret_img_url))
		{
			$img_url 	 = $ret_img_url['define']['IMG_URL'];
		}
		
		$return = $item = array();
		foreach ($channel_info AS $v)
		{
			$item['program'] = $program[$v['id']] ? $program[$v['id']] : '精彩节目';
		
			$item['preview'] = $img_url . LIVE_CONTROL_LIST_PREVIEWIMG_URL . date('Y') . '/' . date('m') . '/live_' . $v['id'] . '.png?time=' . TIMENOW;
		
			if ($v['is_audio'] && $v['logo_rectangle'])
			{
				$item['preview'] = hg_fetchimgurl($v['logo_rectangle']);
			}
			if (!$v['status'])
			{
				$item['preview'] = '';
			}
			
			$return[$v['id']] = $item;
		}

		return $return;
	}
	
	private function get_img_url()
	{
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['App_material']['host'], $this->settings['App_material']['dir']);
		
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_config');
		$retutn = $this->curl->request('configuare.php');
		return $retutn;
	}
}

$out = new channelApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>