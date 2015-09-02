<?php
/***************************************************************************
* $Id: channel.php 26355 2013-07-24 06:12:15Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','live');
require('global.php');
class channelApi extends adminReadBase
{
	private $mChannel;
	private $mServerConfig;
	public function __construct()
	{
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'create'	=>'增加',
		'update'	=>'修改',
		'delete'	=>'删除',
		'audit'		=>'状态',
//		'manage'		=>'管理',
		'_node'=>array(
			'name'=>'频道分类',
			'filename'=>'channel_node.php',
			'node_uniqueid'=>'channel_node',
			),
		);
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/channel.class.php';
		$this->mChannel = new channel();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count  = $this->input['count'] ? intval($this->input['count']) : 100;
		$appid  = intval($this->input['appid']);
		
		$info = $this->mChannel->show($condition, $offset, $count);
		
		if (!empty($info))
		{
			$ret_img_url = $this->mChannel->get_img_url();
			$img_url 	 = '';
			if (!empty($ret_img_url))
			{
				$img_url = $ret_img_url['define']['IMG_URL'];
			}
			
			foreach ($info AS $v)
			{
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
			
				//获取截图
				if (!$v['is_audio'])
				{
					if (!$v['snap'])
					{
						$v['preview'] = $img_url . LIVE_CONTROL_LIST_PREVIEWIMG_URL . date('Y') . '/' . date('m') . '/live_' . $v['sys_id'] . '.png?time=' . TIMENOW;
					}
					else 
					{
						$v['preview'] = hg_material_link($v['snap']['host'], $v['snap']['dir'], $v['snap']['filepath'], $v['snap']['filename']);
						if (strstr($v['preview'], '{&#036;time}'))
						{
							$v['preview'] = str_replace('{&#036;time}', TIMENOW . '000', $v['preview']);
						}
					}
				}
				else
				{
					if ($v['logo_rectangle']['host'])
					{
						$v['preview'] = hg_material_link($v['logo_rectangle']['host'], $v['logo_rectangle']['dir'], $v['logo_rectangle']['filepath'], $v['logo_rectangle']['filename']);
					}
					else 
					{
						$v['preview'] = '';
					}
				}
				
				//处理信号流
				if (!$v['is_sys'])
				{
					$channel_stream = array();
					if (!empty($v['channel_stream']))
					{
						foreach ($v['channel_stream'] AS $kk => $vv)
						{
							if ($vv['url'])
							{
								$vv['output_url'] = $vv['url'] . '/' . $vv['output_url'];
								
								if ($vv['m3u8'])
								{
									$vv['m3u8'] = $vv['url'] . '/' . $vv['m3u8'];
								}
							}
						//	unset($vv['url'], $vv['timeshift_url']);
							$channel_stream[] = $vv;
						}
					}
					
					$v['channel_stream'] = $channel_stream;
				}		
				
				$this->addItem($v);
			}
		}
		
		$this->output();
	}
	
	public function detail()
	{
		$id   = trim($this->input['id']);
		$info = $this->mChannel->detail($id);
		
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
				if($authnode_str === '0')
				{
					$condition .= ' AND node_id IN(' . $authnode_str . ')';
				}
				if($authnode_str)
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
						$condition .= ' AND node_id IN(' . $authnode_str . ')';
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
							$condition .= ' AND node_id IN(' . implode(',', $auth_child_node_array) . ')';
						}
					}
				}
			}
		}
		if($this->input['_id'])
		{
			$sql = "SELECT childs FROM " . DB_PREFIX . "channel_node WHERE id = " . intval($this->input['_id']);
			$ret =  $this->db->query_first($sql);
			$condition .=" AND  node_id in (" . $ret['childs'] . ")";
		}
		####增加权限控制 用于显示####
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND name like \'%' . trim($this->input['k']) . '%\'';
		}
		
		if (isset($this->input['id']) && $this->input['id'])
		{
			$condition .= " AND id IN (" . trim($this->input['id']) . ")";
		}
		
		if (isset($this->input['is_mobile_phone']))
		{
			$condition .= " AND is_mobile_phone = " . intval($this->input['is_mobile_phone']);
		}
		
		if (isset($this->input['_id']) && $this->input['_id'] && $this->input['_id'] != -1)
		{
			$condition .= " AND node_id = " . intval($this->input['_node_id']);
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