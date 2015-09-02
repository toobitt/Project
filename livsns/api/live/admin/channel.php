<?php
/***************************************************************************
* $Id: channel.php 44153 2015-02-11 01:49:01Z youzhenghuan $
***************************************************************************/
define('MOD_UNIQUEID','live');
require('global.php');
class channelApi extends adminReadBase
{
	private $mChannel;					//Channel object
	private $mServerConfig;				//ServerConfig object
	public function __construct()
	{
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'create'	=>'增加',
		'update'	=>'修改',
		'delete'	=>'删除',
		'audit'		=>'状态',
		//'manage'		=>'管理',
		'_node'=>array(
			'name'=>'频道分类',
			'filename'=>'channel_node.php',
			'node_uniqueid'=>'channel_node',
			),
		);
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/channel.class.php';
		$this->mChannel = new channel();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function __getConfig()
	{
		$total = $this->mServerConfig->count('');
		if ($total['total'] < 1)
		{
			$this->errorOutput('REDIRECT TO ' . APP_UNIQUEID . ' server_config');
		}
		parent::__getConfig();
	}
	public function index()
	{
		
	}

	public function show()
	{
		#######权限#######
		$this->verify_content_prms();
		#######权限#######
		
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count  = $this->input['count'] ? intval($this->input['count']) : 100;
		$appid  = intval($this->input['appid']);
		$is_sys = intval($this->input['is_sys']);	//频道接管同步参数
		
		$info = $this->mChannel->show($condition, $offset, $count);
		if ($info)
		{
			$ret_img_url = $this->mChannel->get_img_url();
			$img_url 	 = '';
			if (!empty($ret_img_url))
			{
				$img_url = $ret_img_url['define']['IMG_URL'];
			}
			
			$_server_info = $this->mServerConfig->show();
			
			foreach ($info AS $v)
			{
				$server_info = $_server_info[$v['server_id']];
				
				$type 		 = $server_info['type'] ? $server_info['type'] : 'wowza';
				$server_info = $this->mChannel->get_server_info($server_info);
				
			    
				$wowzaip_output 	   = $server_info['wowzaip_output'];
				//直播
				$live_wowzaip_output   = $server_info['live_wowzaip_output'];
				//录制
				$record_wowzaip_output = $server_info['record_wowzaip_output'];
				$output_append_host    = $server_info['output_append_host'] ? $server_info['output_append_host'] : array($_server_info[$v['server_id']]['host']);
				$set_server_info = array(
					'input_dir'				=> $server_info['input_dir'],//scala
					'output_dir'			=> $server_info['output_dir'],
					'wowzaip_output'		=> $wowzaip_output,
					'live_wowzaip_output'	=> $live_wowzaip_output,
					'record_wowzaip_output'	=> $record_wowzaip_output,
					'output_append_host'	=> $output_append_host,
					'input_port'	=> $_server_info[$v['server_id']]['input_port'] ? $_server_info[$v['server_id']]['input_port'] : '',
					'output_port'	=> $_server_info[$v['server_id']]['output_port'] ? $_server_info[$v['server_id']]['output_port'] : '',
					'type'			=> $type,
					'is_rand'		=> 1,
				);
						
				if (!empty($v['channel_stream']))
				{
					
					$channel_stream = $record_stream = array();
					foreach ($v['channel_stream'] AS $kk => $vv)
					{
						$vv['code'] 			= $v['code'];
						$vv['is_live']			= $v['is_live'];
						$vv['is_record']		= $v['is_record'];
						$vv['is_mobile_phone']	= $v['is_mobile_phone'];
						$vv['server_id']		= $v['server_id'];
						
						$set_stream_url = $this->mChannel->set_stream_url($set_server_info, $vv);
				
						foreach ($set_stream_url['channel_stream'] AS $kkk => $vvv)
						{
							$vv[$kkk] = $vvv;
						}
						
						$record_stream[] = $set_stream_url['record_stream'];
						
						unset($vv['code'], $vv['is_live'], $vv['is_record'], $vv['is_mobile_phone'], $vv['server_id']);
						$channel_stream[] = $vv;
					}
					$v['channel_stream'] = $channel_stream;
					$v['record_stream']  = $type == 'tvie' ? array() : $record_stream;
				}
				if ($is_sys)
				{
					$v['_logo_rectangle'] = $v['logo_rectangle'];
					$v['_client_logo'] = $v['client_logo'];
				}
				
				if ($v['client_logo'][$appid])
				{
					unset($v['client_logo'][$appid]['appid'], $v['client_logo'][$appid]['appname']);
					$v['logo_rectangle'] = $v['client_logo'][$appid];
				}
				unset($v['client_logo']);
				if ($v['logo_rectangle'])
				{
					$v['logo_rectangle_url'] = hg_material_link($v['logo_rectangle']['host'], $v['logo_rectangle']['dir'], $v['logo_rectangle']['filepath'], $v['logo_rectangle']['filename'], '112x43/');
				}
				
				$v['appuniqueid'] = APP_UNIQUEID;
				
				//获取截图
				if (!$v['is_audio'])
				{
					$v['snap'] = array(
						'host' => $this->mImgUrl . LIVE_CONTROL_LIST_PREVIEWIMG_URL, 
						'dir' => '',
						'filepath' => date('Y') . '/' . date('m') . '/',
						'filename' => 'live_' . $v['id'] . '.png?time=' . TIMENOW
					);
					$v['preview'] = $img_url . LIVE_CONTROL_LIST_PREVIEWIMG_URL . date('Y') . '/' . date('m') . '/live_' . $v['id'] . '.png?time=' . TIMENOW;
				}
				else
				{
					if ($v['logo_audio']['host'])
					{
						$v['snap'] = $v['logo_audio'];
						$v['preview'] = hg_material_link($v['logo_audio']['host'], $v['logo_audio']['dir'], $v['logo_audio']['filepath'], $v['logo_audio']['filename']);
					}
					else if ($v['logo_rectangle']['host'])
					{
						$v['snap'] = $v['logo_rectangle'];
						$v['preview'] = hg_material_link($v['logo_rectangle']['host'], $v['logo_rectangle']['dir'], $v['logo_rectangle']['filepath'], $v['logo_rectangle']['filename']);
					}
					else 
					{
						$v['snap'] = $v['logo_rectangle'];
						$v['preview'] = '';
					}
				}
			
				$this->addItem($v);
			}
		}
		/*
		//服务器配置信息
		if ($this->input['server_id'])
		{
			$this->addItem_withkey('server_info', $_server_info);
		}
		
		$dates = date('Y-m-d');
		$this->addItem_withkey('dates', $dates);
		*/
		$this->output();
	}
	
	public function detail()
	{
		#######权限#######
		$this->verify_content_prms(array('_action'=>'show'));
		#######权限#######
		
		$id = trim($this->input['id']);
		$info = $this->mChannel->detail($id);
		$info['schedule_control'] = unserialize($info['schedule_control']);
		if ($info['logo_rectangle'])
		{
			$info['logo_rectangle_url'] = hg_material_link($info['logo_rectangle']['host'], $info['logo_rectangle']['dir'], $info['logo_rectangle']['filepath'], $info['logo_rectangle']['filename'], '80x30/');
		}
		if ($info['logo_square'])
		{
			$info['logo_square_url'] = hg_material_link($info['logo_square']['host'], $info['logo_square']['dir'], $info['logo_square']['filepath'], $info['logo_square']['filename'], '30x30/');
		}
		
		if ($info['logo_audio'])
		{
			$info['logo_audio_url'] = hg_material_link($info['logo_audio']['host'], $info['logo_audio']['dir'], $info['logo_audio']['filepath'], $info['logo_audio']['filename'], '30x30/');
		}
		
		$server_id = $info['server_id'];
		if ($server_id)
		{
			$server_field = 'id, counts, type';
			$server_info  = $this->mServerConfig->get_server_config_by_id($server_id, $server_field);
			$info['type'] = $server_info['type'];
		}
		
		//服务器最大信号数目
		$counts 	  = $server_info['counts'] ? $server_info['counts'] : $this->settings['wowza']['counts'];
		//已用信号数目
		$stream_count = $this->mChannel->get_stream_count($server_id);
		//剩余信号数目
		$over_count   = $counts - $stream_count;
		
		$info['over_count'] = $over_count;
		
		$this->addItem($info);
		$this->output();
	}
	
	public function show_opration()
	{
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		
		$info = $this->mChannel->detail($id);
		
		$server_id = $info['server_id'];
		
		if ($server_id)
		{
			$server_info = $this->mServerConfig->get_server_config_by_id($server_id);
			$info['server_name'] = $server_info['name'];
		}

		$server_info = $this->mChannel->get_server_info($server_info);
		
		$wowzaip_output = $server_info['wowzaip_output'];
		
		$suffix_output = $this->settings['wowza']['output']['suffix'];
		
		if (!empty($info['channel_stream']))
		{
			$channel_stream = array();
			foreach ($info['channel_stream'] AS $v)
			{
				$v['output_url'] = hg_set_stream_url($wowzaip_output, $info['code'], $v['stream_name'] . $suffix_output, 'rtmp://');
				
				if ($info['is_mobile_phone'])
				{
					$v['m3u8'] 	= hg_set_stream_url($wowzaip_output, $info['code'], $v['stream_name'] . $suffix_output, 'm3u8');
				}
				$channel_stream[] = $v;
			}
			$info['channel_stream'] = $channel_stream;
		}
		//获取截图
		$ret_img_url = $this->mChannel->get_img_url();
		$img_url 	 = '';
		if (!empty($ret_img_url))
		{
			$img_url = $ret_img_url['define']['IMG_URL'];
		}
		if (!$info['is_audio'])
		{
			$info['preview'] = $img_url . LIVE_CONTROL_LIST_PREVIEWIMG_URL . date('Y') . '/' . date('m') . '/live_' . $info['id'] . '.png?time=' . TIMENOW;
		}
		else 
		{
			if ($info['logo_audio']['host'])
			{
				$info['preview'] = hg_material_link($info['logo_audio']['host'], $info['logo_audio']['dir'], $info['logo_audio']['filepath'], $info['logo_audio']['filename']);
			}
			else if ($info['logo_rectangle']['host'])
			{
				$info['preview'] = hg_material_link($info['logo_rectangle']['host'], $info['logo_rectangle']['dir'], $info['logo_rectangle']['filepath'], $info['logo_rectangle']['filename']);
			}
			else 
			{
				$info['preview'] = '';
			}
		}
		
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mChannel->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 检索条件
	 * @name get_condition
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';
		####增加权限控制 用于显示####
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else
			{
				//组织以内
				if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
				{
					$condition .= ' AND org_id IN('.$this->user['slave_org'].')';
				}
			}
			if($authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if($authnode_str === '0' && $authnode_str != '-1')
				{
					$condition .= ' AND t1.node_id IN(' . $authnode_str . ')';
				}
				if($authnode_str && $authnode_str != '-1')
				{
					$authnode_str = intval($this->input['_id']) ? $authnode_str .',' . $this->input['_id'] : $authnode_str;
					$sql = 'SELECT id,childs FROM ' . DB_PREFIX . 'channel_node WHERE id IN('.$authnode_str.')';
					$query = $this->db->query($sql);
					$authnode_array = array();
					while($row = $this->db->fetch_array($query))
					{
						$authnode_array[$row['id']]= explode(',', $row['childs']);
					}
					$authnode_str = '';
					foreach ($authnode_array as $node_id=>$n)
					{
						if($node_id == intval($this->input['_id']))
						{
							$node_father_array = $n;
							if(!in_array(intval($this->input['_id']), $authnode))
							{
								continue;
							}
						}
						$authnode_str .= implode(',', $n) . ',';
					}
					$authnode_str = true ? $authnode_str . '0' : trim($authnode_str,',');
					if(!$this->input['_id'])
					{
						$condition .= ' AND t1.node_id IN(' . $authnode_str . ')';
					}
					else
					{
						$authnode_array = explode(',', $authnode_str);
						if(!in_array($this->input['_id'], $authnode_array))
						{
							//
							if(!$auth_child_node_array = array_intersect($node_father_array, $authnode_array))
							{
								$this->errorOutput(NO_PRIVILEGE);
							}
							//$this->errorOutput(var_export($auth_child_node_array,1));
							$condition .= ' AND t1.node_id IN(' . implode(',', $auth_child_node_array) . ')';
						}
					}
				}
			}
		}
		if($this->input['_id'])
		{
			$sql = "SELECT childs FROM " . DB_PREFIX . "channel_node WHERE id = " . intval($this->input['_id']);
			$ret =  $this->db->query_first($sql);
			!$ret && $this->errorOutput('频道分类不存在');
			$condition .=" AND  t1.node_id in (" . $ret['childs'] . ")";
		}
		####增加权限控制 用于显示####
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND t1.name like \'%' . trim($this->input['k']) . '%\'';
		}
		
		if (isset($this->input['id']) && $this->input['id'])
		{
			$condition .= " AND t1.id IN (" . trim($this->input['id']) . ")";
		}
		
		if (isset($this->input['code']) && $this->input['code'])
		{
			$condition .= " AND t1.code = '" . trim($this->input['code']) . "' ";
		}
		
		if (isset($this->input['is_mobile_phone']))
		{
			$condition .= " AND t1.is_mobile_phone = " . intval($this->input['is_mobile_phone']);
		}
		
		if (isset($this->input['_node_id']) && $this->input['_node_id'] && $this->input['_node_id'] != -1)
		{
			$condition .= " AND t1.node_id = " . intval($this->input['_node_id']);
		}
		
		if (isset($this->input['server_id']) && $this->input['server_id'] != -1)
		{
			$condition .= " AND t1.server_id = " . intval($this->input['server_id']);
		}
	
		if (isset($this->input['status']) && $this->input['status'] != -1)
		{
			$condition .= " AND t1.status = " . intval($this->input['status']);
		}
		return $condition;
	}

	/**
	 * 取auth数据 (供不同客户端添加频道logo)
	 * Enter description here ...
	 */
	public function get_auth_info()
	{
		include_once ROOT_PATH . 'lib/class/auth.class.php';
		$auth = new Auth();
		
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['counts'] ? intval($this->input['counts']) : 50;
		
		$auth_data = array(
			'offset'	=> $offset,
			'count'		=> $count,
		);
		
		$auth_info  = $auth->get_auth_info($auth_data);
		$auth_count = $auth->get_auth_count();
		
		$return = array(
			'info'	=> $auth_info,
			'total'	=> $auth_count['total'],
		);
		
		$this->addItem($return);
		$this->output();
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