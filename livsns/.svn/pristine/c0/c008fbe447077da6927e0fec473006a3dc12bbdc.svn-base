<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|getUriname|show_channel_info|detail|count|publish
* @private function get_condition
* 
* $Id: channel_mms.php 7751 2012-07-10 05:16:10Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','old_live');
require('global.php');
class channelApi extends adminReadBase
{
	private $mChannels;
	private $mServerConfig;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/channels.class.php';
		$this->mChannels = new channels();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index()
	{
//		$this->input = $this->settings['mms']['output_stream_server'];
		if ($this->settings['wowza']['dvr_output_server']['host'])
		{
			$host = $this->settings['wowza']['dvr_output_server']['host'] . ':' .  $this->settings['wowza']['dvr_output_server']['port'];
		}
		else 
		{
			$host = $this->settings['wowza']['core_input_server']['host'] . ':' .  $this->settings['wowza']['core_input_server']['port'];
		}
		$dir = $this->settings['wowza']['core_input_server']['output_dir'];
		$dvr_output_server = array(
			'host'	=> $host,
			'dir'	=> $dir,
		);
		$this->input = $dvr_output_server;
		
		$live_status = $this->check_api_state();
		$array = array(
			'live_status' => $live_status	
		);
		$this->addItem($array);
		$this->output();
	}

	/**
	 * 频道列表显示
	 * @name show
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $condition 检索条件
	 * @param $offset 分页参数
	 * @param $count 分页显示记录数
	 * @param $info array 频道的核心数据 
	 * @return $v array 所有频道内容信息
	 */
	public function show()
	{
		if($this->mNeedCheckIn && !$this->prms['show'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 100;
		
		$info = $this->mChannels->show($condition, $offset, $count);
		
		if ($info)
		{
			foreach ($info as $v)
			{
				if (!empty($v['streams']))
				{
					foreach ($v['streams'] AS $kk => $vv)
					{
						$v['stream_uri'][$vv['name']] = $vv['stream_uri'];
						$v['out_streams'][$vv['out_stream_name']] = $vv['uri'];
					}
				}
				$this->addItem($v);
			}
		}
		/*
		//服务器配置信息
		if ($this->input['server_id'])
		{
			$server_condition = '';
			$server_field	  = ' id, name ';
			$server_info = $this->mServerConfig->show($server_condition, 0, 100, '', $server_field);
			$this->addItem_withkey('server_info', $server_info);
		}
		*/
		$this->output();
	}
	
	/**
	 * 获取主信号流名称
	 * @name getUriname
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 信号ID
	 * @return $uri_info string json串
	 */
	public function getUriName()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传人信号id');
		}
		$info = $this->mChannels->getUriName($id);
		//echo json_encode($info);
		if (!$info)
		{
			$this->errorOutput('信号不存在或已被删除');
		}
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 调出频道信息操作界面
	 * @name show_channel_info
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 频道ID
	 * @return $row array 单条频道所涉及信息
	 */
	public function show_channel_info()
	{
		$id = urldecode($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入频道id');
		}
		$info = $this->mChannels->detail($id);
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 单条信息
	 * @name detail
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 频道ID
	 * @return $row array 单条频道信息
	 */
	public function detail()
	{
		$id = trim($this->input['id']);
		$info = $this->mChannels->detail($id);
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 只取频道信息
	 * @name detail
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 频道ID
	 * @return $row array 单条频道信息
	 */
	public function channelinfo()
	{
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入频道id');
		}
		$f = $this->input['fields'];
		$info = $this->mChannels->getChannel($id, $f);
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
	 * @return $ret string 总数，json串
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mChannels->count($condition);
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
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND c.name like \'%' . trim(urldecode($this->input['k'])) . '%\'';
		}
		
		if (isset($this->input['id']) && $this->input['id'])
		{
			$condition .= " AND c.id IN (" . $this->input['id'] . ")";
		}
		
		if (isset($this->input['code']) && $this->input['code'])
		{
			$condition .= " AND c.code = '" . trim(urldecode($this->input['code'])) . "' ";
		}
		
		if (isset($this->input['open_ts']) && $this->input['open_ts'])
		{
			$condition .= " AND c.open_ts = " . intval($this->input['open_ts']);
		}
		
		if (isset($this->input['_node_id']) && $this->input['_node_id'] && $this->input['_node_id'] != -1)
		{
			$condition .= " AND c.node_id = " . $this->input['_node_id'];
		}
		
		if (isset($this->input['_id']) && $this->input['_id'])
		{
			$condition .= " AND c.node_id = " . $this->input['_id'];
		}
		
		if (isset($this->input['server_id']) && $this->input['server_id'] && $this->input['server_id'] != -1)
		{
			$condition .= " AND c.server_id = " . $this->input['server_id'];
		}
		return $condition;
	}
	
	/**
	 * 发布操作
	 * @name publish
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param id int 频道ID
	 * @return $row array 单条频道所涉及信息
	 */
	public function publish()
	{
		if(!$this->input['id'])
		{
			return;
		}
		$this->detail();
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