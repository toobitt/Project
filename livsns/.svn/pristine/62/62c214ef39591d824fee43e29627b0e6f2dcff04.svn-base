<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show
*
* $Id: stream_mms.php 7464 2012-07-03 01:40:09Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','stream');
require('global.php');
class streamApi extends adminReadBase
{
	private $mStream;
	private $mBackup;
	private $mServerConfig;
	function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/stream.class.php';
		$this->mStream = new stream();
		
		require_once CUR_CONF_PATH . 'lib/backup.class.php';
		$this->mBackup = new backup();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
	}
	function index()
	{
		
	}
	function __destruct()
	{
		parent::__destruct();
	}

	function show()
	{
		if($this->mNeedCheckIn && !$this->prms['show'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 100;
		
		$info = $this->mStream->show($condition, $offset, $count);
		if (!empty($info))
		{
			foreach ($info AS $stream)
			{
				$this->addItem($stream);
			}
		}
		//服务器配置信息
		if ($this->input['server_id'])
		{
			$server_condition = '';
			$server_field	  = ' id, name ';
			$server_info = $this->mServerConfig->show($server_condition, 0, 100, '', $server_field);
			$this->addItem_withkey('server_info', $server_info);
		}
		$this->output();
	}
	
	function show2()
	{
		$condition = $this->get_condition();
		$offset = intval($this->input['offset']);
		$count = intval($this->input['count']);
		
		$info = $this->mStream->show($condition, $offset, $count);
		if (!empty($info))
		{
			foreach ($info AS $stream)
			{
				if ($stream['s_status'])
				{
					$this->addItem($stream);
				}
			}
		}
		
		$this->output();
	}
	
	/**
	 * 取单条信息
	 * @name detail
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 频道信号ID
	 * @return $row array 单条频道信号信息
	 */
	function detail()
	{
		$id = trim($this->input['id']);
		$info = $this->mStream->detail($id);
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
		$info = $this->mStream->count($condition);
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
			$condition .= ' AND ch_name like \'%' . trim(urldecode($this->input['k'])) . '%\'';
		}
		
		if(isset($this->input['s_status']))
		{
			$condition .= ' AND s_status=' . intval($this->input['s_status']);
		}
		
		if(isset($this->input['audio_only']))
		{
			$condition .= ' AND audio_only=' . intval($this->input['audio_only']);
		}
	
		if (isset($this->input['server_id']) && $this->input['server_id'] && $this->input['server_id'] != -1)
		{
			$condition .= " AND server_id = " . $this->input['server_id'];
		}
		
		return $condition;
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function getstreamInfo()
	{
		$condition = $this->get_condition();
		$offset = intval($this->input['offset']);
		$count = intval($this->input['counts']);
		
		$info = $this->mStream->show($condition, $offset, $count);
		$data = array(
			'channel_id' => intval($this->input['channel_id']),
			'streamInfo' => $info,
		);
		$this->addItem($data);
		$this->output();
	}
	/**
	 * 分页初始化数据
	 * Enter description here ...
	 */
	function getBackupInfo()
	{
	//	$condition = $this->mBackup->get_condition();
		$condition  = ' AND status=1 ';
		$condition .= ' AND server_id = ' . intval($this->input['server_id']);
		$offset = intval($this->input['offset']);
		$count = intval($this->input['counts']);
		
		$width = 48;
		$height = 48;
		
		$total = $this->mBackup->count($condition);
		$info = $this->mBackup->show($condition, $offset, $count, $width, $height);
		$data = array(
			'total' => $total['total'],
			'count' => ceil($total['total']/$this->settings['stream2BackupCount']),
			'info' => $info,
		);

		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 分页调用接口
	 * Enter description here ...
	 */
	function backupPage()
	{
	//	$condition = $this->mBackup->get_condition();
		$condition  = ' AND status=1 ';
		$condition .= ' AND server_id = ' . intval($this->input['server_id']);
		$offset = intval($this->input['offset']);
		$count = intval($this->input['counts']);
		
		$width = 48;
		$height = 48;
		
		$info = $this->mBackup->show($condition, $offset, $count, $width, $height);
		$this->addItem($info);
		$this->output();
	}
	
	public function show_opration()
	{
		$id = urldecode($this->input['id']);
		$info = $this->mStream->detail($id);
	
		if ($info['server_id'])
		{
			$server_id = $info['server_id'];
			$server_info 	= $this->mServerConfig->get_server_config_by_id($server_id);
		//	$server_output 	= $this->mServerConfig->get_server_output_by_id($server_id);
		}
		$info['server_name'] = $server_info['name'];
		$info['stream_info'] = array();
		if ($info['other_info'])
		{
			$stream_info = array();
			foreach($info['other_info']['input'] AS $k => $v)
			{
				if ($server_info['core_in_host'])
				{
					$wowzaip = $server_info['core_in_host'];
				}
				else 
				{
					$wowzaip = $this->settings['wowza']['core_input_server']['host'];
				}
				
				$suffix  	= !$info['type'] ? $this->settings['wowza']['input']['suffix'] : $this->settings['wowza']['list']['suffix'];
				$app_name 	= $this->settings['wowza']['input']['app_name'];
				
				$stream_info[$k]['id'] 			= $v['id'];
				$stream_info[$k]['name'] 		= $v['name'];
				$stream_info[$k]['input_url'] 	= $v['uri'];
				$stream_info[$k]['output_url'] 	= hg_streamUrl($wowzaip, $app_name, $v['id'] . $suffix);
				$stream_info[$k]['backup_name'] = $v['backup_title'] ? implode(',', $v['backup_title']) : '';
				$stream_info[$k]['bitrate'] 	= $v['bitrate'];
			}
		}
		$info['stream_info'] = $stream_info;
		unset($info['other_info']);
		$this->addItem($info);
		$this->output();
	}
}
$out = new streamApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>