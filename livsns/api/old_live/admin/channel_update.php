<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function update|channelCodeEdit|delete|stream_state|dopublish
*
* $Id: channel_mms_update.php 8251 2012-07-23 07:35:12Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','old_live');
require('global.php');
require_once(ROOT_PATH.'lib/class/statistic.class.php');
class channelUpdateApi extends adminUpdateBase
{
	private $mChannels;
	private $mServerConfig;
	function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/channels.class.php';
		$this->mChannels = new channels();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->errorOutput('频道名称不能为空');
		}
		
		$code = trim($this->input['code']);
		if (!$code)
		{
			$this->errorOutput('台号不能为空');
		}
		
		if (!hg_check_string($code))
		{
			$this->errorOutput('台号只包含字母数字下划线');
		}
		
		if (!$this->mChannels->check_channelCode($code))
		{
			$this->errorOutput('['.$code.'] 台号已存在！');
		}

		$stream_id = $this->input['stream_id'];
		if (!$stream_id)
		{
			$this->errorOutput('请选择流信息');
		}

		if (count($this->input['stream_name']) < 1)
		{
			$this->errorOutput('至少选择一个信号！');
		}
		
		//备播信号
		$field = ' id, s_name, ch_name, other_info, type, server_id ';
		$stream_info = $this->mChannels->get_stream_by_id($stream_id, $field);
		if (empty($stream_info))
		{
			$this->errorOutput('选择的信号不存在或已被删除');
		}
		
		//服务器配置
		$server_id = $stream_info['server_id'];
		if ($server_id)
		{
			$server_info = $this->mServerConfig->get_server_info($server_id);

			if ($server_info == -1)
			{
				$this->errorOutput('该服务器信息不存在或已被删除');
			}
			else if ($server_info == -2)
			{
				$this->errorOutput('该服务器已经无法再添加信号，请选择其他服务器');
			}
			
			$stream_count = count($this->input['stream_name']);
			
			if ($stream_count > $server_info['over_count'])
			{
				$offset_count = $stream_count - $server_info['over_count'];
				
				$this->errorOutput('已经超过该服务器信号的最大数目 [ ' . $offset_count . ' ] 条,请选择其他服务器！');
			}
		}
		
		//时移时间
		$save_time = intval($this->input['save_time']);
		if ($save_time > $this->settings['max_save_time'])
		{
			$save_time = $this->settings['max_save_time'];
		}
		
		//延时时间
		$live_delay = intval($this->input['live_delay']);
		if ($live_delay > $this->settings['max_live_delay'])
		{
			$live_delay = $this->settings['max_live_delay'];
		}
		
		$add_info = array(
			'stream_name'=> $this->input['stream_name'],
			'open_ts' 	 => intval($this->input['open_ts']),
			'save_time'  => $save_time,
			'live_delay' => $live_delay,
			'drm' 		 => intval($this->input['drm']),
			'code'		 => $code,
			'name'		 => $name,
			'column_id'  => trim($this->input['column_id']),
			'user_id' 	 => $this->user['user_id'],
			'user_name'  => $this->user['user_name'],
			'appid' 	 => $this->user['appid'],
			'appname' 	 => $this->user['display_name'],
		);
	
		$info = $this->mChannels->create($add_info, $stream_info, $server_info);
		
		if ($info['head'])
		{
			$this->errorOutput($info['head']['title']);
		}
		
		switch ($info)
		{
			case -55 :
				$this->errorOutput('媒体服务器未启动');
				break;
			case -20 :
				$this->errorOutput('创建时移输出层应用失败');
				break;
			case -200 :
				$this->errorOutput('创建直播输出层应用失败');
				break;
			case -16 :
				$this->errorOutput('创建延时层失败');
				break;
			case -10 :
				$this->errorOutput('创建输出层失败');
				break;
			case 0 :
				$this->errorOutput('创建失败');
				break;
			default :
				break;
		}
		$this->addItem($info);
		$this->output();
	}
	
	public function update()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入频道ID');
		}

		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->errorOutput('频道名称不能为空');
		}
		
		$code = trim($this->input['code']);
		if (!$code)
		{
			$this->errorOutput('台号不能为空');
		}
		
		if (!hg_check_string($code))
		{
			$this->errorOutput('台号只包含字母数字下划线');
		}
		
		if ($code != trim($this->input['code2']) && !$this->mChannels->check_channelCode($code))
		{
			$this->errorOutput('['.$code.'] 台号已存在！');
		}
		
		$stream_id = intval($this->input['stream_id']);
		if (!$stream_id)
		{
			$this->errorOutput('请选择流信息');
		}

		if (count($this->input['stream_name']) < 1)
		{
			$this->errorOutput('至少选择一个信号！');
		}
	
		//直播频道
		$channel_field 	= ' code, stream_id, ch_id, save_time, live_delay, drm, open_ts, stream_state, column_id, column_url, expand_id, server_id ';
		$channel_info 	= $this->mChannels->get_channel_by_id($id, $channel_field);
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		//备播信号
		$stream_field 	= ' id, s_name, ch_name, other_info, type, server_id ';
		$stream_info 	= $this->mChannels->get_stream_by_id($stream_id, $stream_field);
		if (empty($stream_info))
		{
			$this->errorOutput('选择的信号不存在或已被删除');
		}
		
		//服务器配置
		$server_id = $stream_info['server_id'];
		if ($server_id)
		{
			$server_info = $this->mServerConfig->get_server_info($server_id, '', $id);
			
			if ($server_info == -1)
			{
				$this->errorOutput('该服务器信息不存在或已被删除');
			}
			else if ($server_info == -2)
			{
				$this->errorOutput('该服务器已经无法再添加信号，请选择其他服务器');
			}
			
			$stream_count = count($this->input['stream_name']);
			
			if ($stream_count > $server_info['over_count'])
			{
				$offset_count = $stream_count - $server_info['over_count'];
				
				$this->errorOutput('已经超过该服务器信号的最大数目 [ ' . $offset_count . ' ] 条,请选择其他服务器！');
			}
		}
		
		//时移时间
		$save_time = intval($this->input['save_time']);
		if ($save_time > $this->settings['max_save_time'])
		{
			$save_time = $this->settings['max_save_time'];
		}
		
		//延时时间
		$live_delay = intval($this->input['live_delay']);
		if ($live_delay > $this->settings['max_live_delay'])
		{
			$live_delay = $this->settings['max_live_delay'];
		}
		
		$add_info = array(
			'stream_name'=> $this->input['stream_name'],
			'open_ts' 	 => intval($this->input['open_ts']),
			'save_time'  => $save_time,
			'live_delay' => $live_delay,
			'drm' 		 => intval($this->input['drm']),
			'code'		 => $code,
			'name'		 => $name,
			'column_id'  => trim($this->input['column_id']),
			'record_server_id'	=> intval($this->input['record_server_id']),
		);
		
		$info = $this->mChannels->update($id, $add_info, $channel_info, $stream_info, $server_info);

		if ($info['head'])
		{
			$this->errorOutput($info['head']['title']);
		}
		
		switch ($info)
		{
			case -55 :
				$this->errorOutput('媒体服务器未启动');
				break;
			case -20 :
				$this->errorOutput('更新时移输出层应用失败');
				break;
			case -200 :
				$this->errorOutput('更新直播输出层应用失败');
				break;
			case 0 :
				$this->errorOutput('更新失败');
				break;
			default :
				break;
		}
		$this->addItem($info);
		$this->output();
	}
	
	function delete()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入频道ID');
		}

		$info = $this->mChannels->delete($id);
		
		if ($info['head'])
		{
			$this->errorOutput($info['head']['title']);
		}
		
		switch ($info)
		{
			case -20 :
				$this->errorOutput('删除输出层应用失败');
				break;
			case -30 :
				$this->errorOutput('服务器信息不存在或已被删除');
				break;
			default :
				break;
		}
		$this->addItem($info);
		$this->output();
	}
	
	function streamState()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入频道ID');
		}

		$info = $this->mChannels->streamState($id);
		
		switch ($info)
		{
			case -1 :
				$this->errorOutput('该频道不存在或已被删除');
				break;
			case -2 :
				$this->errorOutput('该频道流地址不存在或已被删除');
				break;
			case -30 :
				$this->errorOutput('该频道的服务器信息不存在或已被删除');
				break;
			default :
				break;
		}
		$this->addItem($info);
		$this->output();
	}

	public function dopublish()
	{
		if($this->mNeedCheckIn && !$this->prms['publish'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if(!$this->input['columnid'])
		{
			$this->errorOutput(NOPUBLISHCOL);
		}
	}

	/**
	 * 即时发布
	 * @param id  int   
	 * @param column_id string  发布的栏目id
	 */
	public function publish()
	{
		if($this->mNeedCheckIn && !$this->prms['publish'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = intval($this->input['id']);
		$column_id = trim($this->input['column_id']);
		
		if(!$id)
		{
			$this->errorOutput('ID不能为空');
		}
		
		$ret = $this->mChannels->publish($id, $column_id);
		
		if(empty($ret))
		{
			$this->errorOutput('发布失败');
		}
	
		$this->addItem($ret);
		$this->output();
	}
	/**
	 * 调用分类数据
	 */
	function channel_node_show()
	{
		$id		 	= intval($this->input['id']);
		$node_id 	= intval($this->input['node_id']);
		
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_node WHERE 1 ORDER BY order_id DESC ";
		$q = $this->db->query($sql);
		$channel_node = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel_node[] = $row;
		}
		
		$ret = array(
			'id' 	  		=> $id,
			'node_id' 		=> $node_id,
			'channel_node'	=> $channel_node,
		);

		$this->addItem($ret);
		$this->output();
	}
	/**
	 * 分类更新
	 * Enter description here ...
	 */
	public function channel_node_edit()
	{
		$id		 	= intval($this->input['id']);
		$node_id 	= intval($this->input['node_id']);
		$node_name 	= trim(urldecode($this->input['node_name']));
		
		if (!$id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		if (!$node_id)
		{
			$this->errorOutput('未传入分类id');
		}
		
		$sql = "UPDATE " . DB_PREFIX . "channel SET node_id = " . $node_id . " WHERE id = " . $id;
		if ($this->db->query($sql))
		{	
			$ret = array(
				'result'	=> 1,
				'id' 	  	=> $id,
				'node_id' 	=> $node_id,
				'node_name'	=> $node_name,
			);
		}
		else 
		{
			$sql = "SELECT c.node_id, cn.name AS node_name FROM " . DB_PREFIX . "channel c ";
			$sql.= " LEFT JOIN " . DB_PREFIX . "channel_node cn ON c.node_id=cn.id ";
			$sql.= " WHERE c.id = " . $id;
			$channel_info = $this->db->query_first($sql);
			
			$ret = array(
				'result'	=> 0,
				'id' 	  	=> $id,
				'node_id' 	=> $channel_info['node_id'],
				'node_name'	=> $channel_info['node_name'],
			);
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('未定义的空方法');
	}
	public function sort()
	{
		
	}
	public function audit()
	{
		
	}
}
$out = new channelUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>