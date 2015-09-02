<?php
/***************************************************************************
* $Id: live_control_update.php 36841 2014-05-08 03:45:01Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','live_control');
require('global.php');
class liveControlUpdateApi extends adminUpdateBase
{
	private $mLivemms;
	private $mBackup;
	private $mLiveControl;
	private $mLivmedia;
	private $mLive;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
		require_once CUR_CONF_PATH . 'lib/live_control.class.php';
		$this->mLiveControl = new liveControl();
		
		require_once(ROOT_PATH . 'lib/class/livmedia.class.php');
		$this->mLivmedia = new livmedia();
		
		require_once(ROOT_PATH . 'lib/class/live.class.php');
		$this->mLive = new live();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{

	}
	public function update()
	{

	}
	public function delete()
	{

	}
	public function publish()
	{

	}
	public function audit()
	{

	}
	public function sort()
	{

	}
	
	/**
	 * 切播控制
	 * Enter description here ...
	 * @param int $channel_id 频道id
	 * @param string $change_type 切播类型 (stream file)
	 * @param int $change_id 切播到id
	 * @param int $stream_id 切播到信号id (如果是备播信号,则是信号id 如果是备播文件,则是备播文件id)
	 * @param int $notify 返回直播 1返回直播带串联单的 0-返回信号
	 */
	public function change()
	{
		$channel_id 	= intval($this->input['channel_id']);
		$change_type	= trim($this->input['change_type']);
		$stream_id		= intval($this->input['stream_id']);
		$notify			= intval($this->input['notify']);
		$chgurl			= $this->input['chgurl'];
		$chgname			= $this->input['chgname'];

		//	$this->errorOutput(var_export($this->input, 1));
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		if (!$change_type)
		{
			$this->errorOutput('未传入切播类型');
		}

		if ($channel_id == $stream_id)
		{
			$this->errorOutput('不能切播到自己');
		}
		
		$channel_data = array(
			'id'		=> $channel_id,
			'is_stream'	=> 1,
			'is_server'	=> 1,
			'field'		=> ' * ',
		);
		
		$channel_info = $this->mLive->getChannelInfoById($channel_data);
		$channel_info = $channel_info[0];
		
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array();
		$all_node = $this->mLive->getFatherNodeByid($channel_info['node_id']);
		$nodes['_action'] = 'change';
		$nodes['nodes'][$channel_info['node_id']] = $all_node ? implode(',',$all_node) : '';
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		if (empty($channel_info['channel_stream']))
		{
			$this->errorOutput('该频道信号不存在或已被删除');
		}
		
		if (!$channel_info['status'])
		{
			$this->errorOutput('该频道已停止无法播控');
		}
		
		if (!$channel_info['is_control'])
		{
			$this->errorOutput('该频道不支持播控');
		}
		
		$is_audio  = $channel_info['is_audio'];

		if (!$this->settings['server_info']['host'])
		{
			$this->errorOutput('播控服务器未配置');
		}
		if (!$channel_info['schedule_control']['control'])
		{
			$this->errorOutput('该频道播控层未配置');
		}
		$output_id = $channel_info['schedule_control']['control']['output_id'];
		$source_stream_id = $channel_info['schedule_control']['control']['stream_id'];
		$server_info = $this->settings['server_info'];
		
		$host				= $server_info['host'];
		$input_dir  		= $server_info['input_dir'];
		//$output_dir 		= $server_info['output_dir'];
	
		$application_data = array(
			'action'	=> 'select',
			'id'		=> $output_id,
		);
		$ret_select = $this->mLivemms->inputOutputStreamOperate($host, $input_dir, $application_data);
		$outenabled = $ret_select['output']['enable'];
		if (!$outenabled)
		{
			$this->mLivemms->inputOutputStreamOperate($host,$input_dir,array('action'=>'start', 'id'=>$output_id));
		}
		$change_id 	 = $channel_id;
		$change_name = $channel_info['name'];
		
		$prev = array(
			'change_id'			=> $channel_info['change_id'] ? $channel_info['change_id'] : $channel_info['id'],
			'change_name'		=> $channel_info['change_name'] ? $channel_info['change_name'] : $channel_info['name'],
			'change_type'		=> $channel_info['change_type'],
		);
		
		$sql = 'SELECT * FROM ' . DB_PREFIX . "channel_stream WHERE channel_id=$channel_id";
		$channel_stream = $this->db->query_first($sql);
		if ($channel_stream['url'] == $chgurl)
		{
			$this->errorOutput('当前正切播到此流上');
		}

			//$this->errorOutput($change_type);
			//file_put_contents(CACHE_DIR . 'c.txt', '');
			//file_put_contents(CACHE_DIR . 'c.txt', "\n" . var_export($channel_stream, 1), FILE_APPEND);
		if ($chgurl)
		{
			$token = md5($chgurl);
			$sql = 'SELECT chg_id, state, source_id, source_name  FROM ' . DB_PREFIX . "stream_info WHERE token='$token'";
			$chg_stream = $this->db->query_first($sql);
			if ($change_type == 'stream')
			{
				if (!$chg_stream['chg_id'])
				{
					$input_data = array(
						'action'	=> 'insert',
						'url'		=> $chgurl,
						'type'		=> 0,
					);								
					$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);										
					if (!$ret_input['result'])
					{
						$this->errorOutput('频道[' . $chgname . ']流准备失败');
					}
					$input_id = intval($ret_input['input']['id']);
					//启动
					$input_data = array(
						'action'	=> 'start',
						'id'		=> $input_id,
					);
					$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);	
					if (!$ret_input['result'])
					{
						$this->errorOutput('频道[' . $chgname . ']流准备失败');
					}
					$sql = 'INSERT INTO ' . DB_PREFIX . 'stream_info (token, url, type, chg_id, cnt, create_time, update_time, user_id, user_name, state, source_id, source_name) VALUES ';
					$sql .= "('$token', '$chgurl', '$change_type', $input_id, 1, " . TIMENOW . ", " . TIMENOW . ", '{$this->user['user_id']}', '{$this->user['user_name']}', 1, $stream_id, '{$chgname}')";
					$this->db->query($sql);
					//file_put_contents(CACHE_DIR . 'c.txt', "\n" . $sql, FILE_APPEND);
				}
				else
				{
					$input_id = $chg_stream['chg_id'];
					$input_data = array(
						'action'	=> 'select',
						'id'		=> $input_id,
					);
					$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);	
					if (!$ret_input['result'] || !$ret_input['input']['enable'] || !$ret_input['input']['isVideoReady'])
					{
						$sql = 'DELETE FROM ' . DB_PREFIX . "stream_info WHERE token='$token'";
						$chg_stream = $this->db->query($sql);
						$this->errorOutput('频道[' . $chgname . ']流准备失败');
					}
					$sql = 'UPDATE ' . DB_PREFIX . "stream_info SET update_time=" . TIMENOW . ", cnt=cnt+1 WHERE token='$token'";
					$this->db->query($sql);
				}
				
				$sourceId 	= $input_id;
				$sourceType = 1;
			}
			else if ($change_type == 'file')
			{
				//备播文件
				if (!$chg_stream['state'])
				{
					$this->errorOutput('切播文件尚未准备好，请稍后在试');			
				}
				
				
				$sql = 'UPDATE ' . DB_PREFIX . "stream_info SET update_time=" . TIMENOW . ", cnt=cnt+1 WHERE token='$token'";
				$this->db->query($sql);
				$change_id 		= $chg_stream['source_id'];
				$change_name 	= $chg_stream['source_name'];
				$input_id		= $chg_stream['chg_id'];
				
				$sourceId 	= $chg_stream['chg_id'];
				$sourceType = 4;
			}
			if ($channel_stream['url'])
			{
				$sql = 'UPDATE ' . DB_PREFIX . "channel_stream SET url='$chgurl' WHERE channel_id=$channel_id";
				$this->db->query($sql);
			}
			else
			{
				$sql = 'INSERT INTO  ' . DB_PREFIX . "channel_stream(channel_id, url) VALUES ($channel_id, '$chgurl')";
				$this->db->query($sql);
			}
			//file_put_contents(CACHE_DIR . 'c.txt', "\n". $sql, FILE_APPEND);
		}
		else
		{
			$sql = 'DELETE FROM ' . DB_PREFIX . "channel_stream WHERE channel_id=$channel_id";
			$this->db->query($sql);
			$sourceId = $source_stream_id;
			$sourceType = 1;
			$change_id = 0;
			$change_name = '';
		}
			
		$input_url = $channel_info['channel_stream']['output_url_rtmp'];
		if ($channel_stream['url'])
		{
			$token = md5($channel_stream['url']);
			$input_url = $channel_stream['url'];
			$sql = 'UPDATE ' . DB_PREFIX . "stream_info SET update_time=" . TIMENOW . ", cnt=cnt-1 WHERE token='$token'";
			$this->db->query($sql);
		}
		
		$prev['input_url'] = $channel_stream['url'];
		$prev['stream_id'] = $channel_info['change_id'];

		$change_data = array(
			'action'		=> 'change',
			'id'			=> $output_id,
			'sourceId'		=> $sourceId,
			'sourceType'	=> $sourceType,
		);
		
		if ($change_type == 'stream' && $notify == 1 && !$stream_id)	//返回直播
		{
			$change_data['sourceId'] = $source_stream_id;
			$change_data['notify'] = 1;
		}
		else //切播
		{
			$change_data['notify'] = 0;
		}
		
		$ret_change = $this->mLivemms->inputChangeOperate($host, $input_dir, $change_data);
		
		$ret_change_result[$k] = $ret_change['result'];
		
		if (!$ret_change['result'])
		{
			$this->errorOutput('切播失败' . var_export($ret_change, 1));
		}
		
		
		//更新切播字段
		$update_data = array(
			'id'			=> $channel_id,
			'change_id'		=> $stream_id,
			'change_name'	=> $change_name,
			'change_type'	=> $change_type,
		);
		
		$ret = $this->mLive->updateChange($update_data);
		
		//记录日志
		$pre_data = array(
			'id'			=> $channel_info['id'],
			'change_id'		=> $channel_info['change_id'],
			'change_name'	=> $channel_info['change_name'],
			'change_type'	=> $channel_info['change_type'],
		);
		
		$up_data = $update_data;
		
		$this->addLogs('播控', $pre_data, $up_data, $channel_info['name'], $channel_info['id']);
		
		//播控日志
		$data_log = array(
			'channel_id'	=> $channel_id,
			'channel_name'	=> $channel_info['name'],
			'change_id'		=> $stream_id,
			'change_name'	=> $change_name,
			'change_type'	=> $change_type,
			'input_id'		=> $input_id,
			'user_id'		=> $this->user['user_id'],
			'user_name'		=> $this->user['user_name'],
			'appid'			=> $this->user['appid'],
			'appname'		=> $this->user['display_name'],
			'create_time'	=> TIMENOW,
			'update_time'	=> TIMENOW,
			'ip'			=> hg_getip(),
		);
		
		$ret_log = $this->mLiveControl->log_create($data_log);
		
		$return = array(
			'msg'			=> $ret_change_result[0],
			'channel_id'	=> $channel_id,
			'change_id'		=> $stream_id,
			'change_name'	=> $change_name,
			'change_type'	=> $change_type,
			'stream_id'		=> $stream_id,
			'notify'		=> $notify,
			'prev'			=> $prev,
		);

		$this->addItem($return);
		$this->output();
	}

	/**
	 * 更新备播频道字段
	 * Enter description here ...
	 * @param int $channel_id 频道id
	 * @param string $beibo_id 备播频道id
	 */
	public function update_beibo()
	{
		$channel_id = intval($this->input['channel_id']);
		$change_id 	= intval($this->input['change_id']);
		$stream_id	= intval($this->input['stream_id']);
		$beibo_id	= trim($this->input['beibo_id']);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		if (!$change_id)
		{
			$this->errorOutput('未传入备播频道id');
		}

		if (!$stream_id)
		{
			$this->errorOutput('未传入备播信号id');
		}
		
		if (!$beibo_id)
		{
			$this->errorOutput('未传入备播id');
		}
		
		$channel_data = array(
			'id'		=> $channel_id,
			'is_stream'	=> 0,
			'is_server'	=> 1,
			'field'		=> ' * ',
		);
		
		$channel_info = $this->mLive->getChannelInfoById($channel_data);
		$channel_info = $channel_info[0];
		
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		$change_data = array(
			'id'		=> $change_id,
			'is_stream'	=> 1,
			'is_server'	=> 0,
			'field'		=> ' * ',
		);
		
		$change_info = $this->mLive->getChannelInfoById($change_data);
		$change_info = $change_info[0];
		
		if (empty($change_info) || empty($change_info['channel_stream']))
		{
			$this->errorOutput('备播频道不存在或已被删除');
		}
		
		$stream_info = $this->mLiveControl->get_live_control_by_id($stream_id);
			
		if (empty($stream_info) || !$stream_info['input_id'])
		{
			$this->errorOutput('备播信号不存在或已被删除');
		}
		
		$server_info = $channel_info['server_info'];
		
		$host		= $server_info['host'];
		$input_dir	= $server_info['input_dir'];
		
		$wowzaip_input 		= $server_info['wowzaip_input'];
		$app_name_input		= $this->settings['wowza']['input']['app_name'];
		$suffix_input		= $this->settings['wowza']['input']['suffix'];
		$wowzaip_output 	= $server_info['wowzaip_output'];
		$suffix_output 		= $this->settings['wowza']['output']['suffix'];
		$output_append_host = $server_info['output_append_host'];
		
		$input_id = $stream_info['input_id'];
		
		if (!$input_id)
		{
			$this->errorOutput('备播信号id不存在或已被删除');
		}
		
		//被选择频道作为输入信号地址
		$stream_name = $change_info['channel_stream'][0]['stream_name'];
		$url = hg_set_stream_url($wowzaip_output, $change_info['code'], $stream_name . $suffix_output);
		
		$input_data = array(
			'action'	=> 'update',
			'id'		=> $input_id,
			'url'		=> $url,
		);
		
		$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
		
		if (!$ret_input['result'])
		{
			$this->errorOutput('更新备播信号失败');
		}
		
		$input_data = array(
			'action'	=> 'start',
			'id'		=> $input_id,
		);
		
		$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
		
		if (!$ret_input['result'])
		{
			$this->errorOutput('备播信号重启失败');
		}
		
		//输入的输出地址
		$input_url = hg_set_stream_url($wowzaip_input, $app_name_input, $input_id . $suffix_input);
		
		$data_stream = array(
			'id'			=> $stream_id,
			'stream_name'	=> $stream_name,
			'url'			=> $url,
			'change_id'		=> $change_info['id'],
			'change_name'	=> $change_info['name'],
		);
		
		$ret_stream = $this->mLiveControl->update($data_stream);
		if (empty($ret_stream))
		{
			$this->errorOutput('更新备播信号失败');
		}
		
		//更新频道里备播信号字段
		$data_channel = array(
			'id'	=> $channel_id,
			'beibo'	=> $beibo_id,
		);
		
		$ret_channel = $this->mLive->updateBeibo($data_channel);
		
		if (empty($ret_channel))
		{
			$this->errorOutput('更新备播信息失败');
		}

		//记录日志
		$pre_data = array(
			'id' 	=> $channel_info['id'],
			'beibo' => $channel_info['beibo'],
		);
		$up_data = $data_channel;
		
		$this->addLogs('更新备播信号', $pre_data, $up_data, $channel_info['name'], $channel_info['id']);
		
		$ret = array(
			'id'			=> $stream_info['id'],
			'change_id'		=> $change_info['id'],
			'change_name'	=> $change_info['name'],
			'stream_name'	=> $stream_name,
			'input_id'		=> $input_id,
			'input_url'		=> $input_url,
		);
		
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 更新备播信号开始时间
	 * Enter description here ...
	 * @param int $channel_id 频道id
	 */
	public function update_start_time()
	{
		$channel_id = intval($this->input['channel_id']);
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		$condition = ' AND is_used = 0 ';
		$field	   = 'id, start_time';
		$stream_info = $this->mLiveControl->get_live_control_by_channel_id($channel_id, $field, $condition);
		
		$return = array();
		if (!empty($stream_info))
		{
			foreach ($stream_info AS $v)
			{
				$data_stream = array(
					'id'			=> $v['id'],
					'start_time'	=> TIMENOW,
				);
				
				$return[] = $this->mLiveControl->update($data_stream);
			}
		}
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 视频库添加到备播文件
	 * $vod_id int 视频id
	 * $server_id int 服务器id
	 * $type int 上传类型 1-视频库 2-本地
	 * Enter description here ...
	 */
	public function set_backup()
	{
		$vod_id 	= intval($this->input['vod_id']);
		
		//视频库
		$vod_info = $this->mLivmedia->getVodInfoById($vod_id);
		
		$vod_info = $vod_info[0];
		
		if (empty($vod_info))
		{
			$this->errorOutput('该备播文件不存在或已删除');
		}
		$server_info = $this->settings['server_info'];
		
		if (!$server_info['host'])
		{
			$this->errorOutput('播控服务器未配置');
		}
		$host				= $server_info['host'];
		$input_dir  		= $server_info['input_dir'];
		//$output_dir 		= $server_info['output_dir'];
		$url = $vod_info['vodurl'] . $vod_info['video_filename'];
		
		$token = md5($url);
		$sql = 'SELECT chg_id FROM ' . DB_PREFIX . "stream_info WHERE token='$token'";
		$chg_stream = $this->db->query_first($sql);
		if (!$chg_stream['chg_id'])
		{
			$callback = $this->settings['App_live_control']['protocol'] . $this->settings['App_live_control']['host'] . '/' . $this->settings['App_live_control']['dir'] . 'admin/callback.php?a=backup_callback&token=' . $token . '&appid=' . intval($this->input['appid']) . '&appkey=' . trim($this->input['appkey']);
			$file_data = array(
				'action'	=> 'insert',
				'url'		=> $url,
				'callback'	=> urlencode($callback),
			);
			
			$ret_file = $this->mLivemms->inputFileOperate($host, $input_dir, $file_data);
			if (!$ret_file['result'])
			{
				$this->errorOutput('上传备播文件失败');
			}
				
			$file_id = $ret_file['file']['id'];
			$sql = 'INSERT INTO ' . DB_PREFIX . 'stream_info (token, url, type, chg_id, cnt, create_time, update_time, user_id, user_name, state, source_id, source_name) VALUES ';
			$sql .= "('$token', '$url', 'file', $file_id, 0, " . TIMENOW . ", " . TIMENOW . ", '{$this->user['user_id']}', '{$this->user['user_name']}', 0, $vod_id, '{$vod_info['title']}')";
			$this->db->query($sql);
		}
		else
		{
			$file_id = $chg_stream['chg_id'];
		}
		$return = array(
			'file_id' 	=> $file_id,
			'vod_id' 	=> $vod_id,
			'name' 		=> $vod_info['title'],
			'file_url'	=> $url,
		);
		
		$this->addItem($return);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('未被实现的空方法');
	}
}

$out = new liveControlUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>