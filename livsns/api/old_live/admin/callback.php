<?php
/***************************************************************************
* $Id: channel_chg_plan_create.php 
***************************************************************************/
define('MOD_UNIQUEID','callback');
require('global.php');
class callbackApi extends adminBase
{
	private $mChannelChgPlan;
	private $mLivemms;
	private $mProgramShield;
	private $mServerConfig;
	private $mBackup;
	function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/channel_chg_plan.class.php';
		$this->mChannelChgPlan = new channelChgPlan();
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
		require_once CUR_CONF_PATH . 'lib/program_shield.class.php';
		$this->mProgramShield = new programShield();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
		
		require_once CUR_CONF_PATH . 'lib/backup.class.php';
		$this->mBackup = new backup();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 时移回调函数
	 * Enter description here ...
	 */
	function record_callback()
	{
		$record = str_replace('&quot;','"',$this->input['data']);
		$record = json_decode($record,1);

		$channel_id = $record['channel_id'];
		if ($channel_id)
		{
			$channel_info = $this->mChannelChgPlan->get_channel_by_id($channel_id);
		}

		if ($channel_info['server_id'])
		{
			//服务器配置
			$server_id 			= $channel_info['server_id'];
			$server_info 		= $this->mServerConfig->get_server_config_by_id($server_id);
		}
		
		if ($server_info['core_in_host'])
		{
			$host 	 	= $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			$apidir	 	= $server_info['input_dir'];
		}
		else 
		{
			$host 		= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$apidir		= $this->settings['wowza']['core_input_server']['input_dir'];
		}
		
		if ($server_info['record_host'])
		{
			$record_protocol	= $server_info['protocol'];
			$record_host 		= $server_info['record_out_host'];
		}
		else 
		{
			$record_protocol 	= $this->settings['wowza']['record']['protocol'];
			$record_host 		= $this->settings['wowza']['record']['host'];
		}
		
		$record_protocol	= $record_protocol ? $record_protocol : 'http://';
		$record_dir			= $this->settings['wowza']['record']['dir'];
		$record_prefix 		= $this->settings['wowza']['record']['prefix'];
		
		if ($record['id'])
		{
			$chg_id = substr($record['id'], strlen($record_prefix));
		}
		
		if ($record['exit_status'])	//录制成功提交给wowza服务器
		{
			$url = $record_protocol . $record_host . '/' . $record_dir . $record['file_path'];
		
			$callback = $this->settings['App_live']['protocol'].$this->settings['App_live']['host'].'/'.$this->settings['App_live']['dir'].'admin/callback.php?a=schedule_add&chg_id='.$chg_id.'&appid=' . intval($this->input['appid']) . '&appkey=' . urldecode($this->input['appkey']);
			$ret_file = $this->mLivemms->inputFileInsert($host, $apidir, $url, urlencode($callback));

			if ($ret_file['result'])
			{
				$fileid = $ret_file['file']['id'];
				//入库
				$data = array(
					'chg_id'		=> intval($chg_id),
					'file_path'		=> $record['file_path'],
					'channel_id' 	=> $channel_id,
				//	'start_time' 	=> $record['start_time'],
				//	'toff'		 	=> $record['toff'],
					'fileid'		=> $fileid,
					'create_time' 	=> TIMENOW,
					'ip' 			=> hg_getip(),
				);
				
				$ret = $this->mChannelChgPlan->record_add($data);

			}
		}
		else 	//录制失败删除时移串联单
		{
			//更新串联单
			$add_input = array(
				'record_status'	=> 1,
			);
			$ret = $this->mChannelChgPlan->schedule_edit($add_input, intval($chg_id));
		//	$ret = $this->mChannelChgPlan->schedule_delete(intval($chg_id));
			/*		
			//删除录制失败的时移
			$ret_record_delete = $this->mLivmms->recordDelete($record['id']);
			$ret['record_delete'] = $ret_record_delete;
			*/
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 时移串联单添加
	 * Enter description here ...
	 */
	function schedule_add()
	{
		$chg_id = intval($this->input['chg_id']);
		if (!$chg_id)
		{
			$this->errorOutput('未传入串联单id');
		}
		
		$ret_record = $this->mChannelChgPlan->get_record_by_chg_id($chg_id);
	
		if (empty($ret_record))
		{
			$this->errorOutput('时移记录不存在或已被删除');
		}
		
		$server_id = $ret_record['server_id'];
		if ($server_id)
		{
			$server_info = $this->mServerConfig->get_server_config_by_id($server_id);
		}
			
		if ($server_info['core_in_host'])
		{
			$host 	 	= $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			$apidir	 	= $server_info['input_dir'];
		}
		else 
		{
			$host 		= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$apidir		= $this->settings['wowza']['core_input_server']['input_dir'];
		}
		
		$this->mChannelChgPlan->record_edit($chg_id, $ret_record['program_start_time'], $ret_record['toff']);
		//形成文件流
		if (!$ret_record['fileid'])
		{
			$this->errorOutput('服务器视频不存在或已被删除');
		}
		
		$ret_list = $this->mLivemms->inputFileListInsert($host, $apidir, $ret_record['fileid']);

		if (!$ret_list['result'])
		{
			$this->errorOutput('创建文件流失败');
		}
		
		$list_id = $ret_list['list']['id'];
		
		//添加媒体服务器串联单
		$epg_insert = $this->mLivemms->inputScheduleInsert($host, $apidir, $ret_record['out_stream_id'], $list_id, $ret_record['source_type'], $ret_record['change_time'], $ret_record['toff']);

		if (!$epg_insert['result'])
		{
			$this->errorOutput('创建媒体服务器串联单失败');
		}
		
		$epg_id = $epg_insert['schedule']['id'];
		
		if (!$epg_id)
		{
			$this->errorOutput('媒体服务器串联单id不存在');
		}
		
		//更新串联单
		$add_input = array(
			'epg_id'	=> $epg_id,
			'source_id'	=> $list_id,
			'fileid'	=> $ret_record['fileid'],
		);
		$ret = $this->mChannelChgPlan->schedule_edit($add_input, $chg_id);
		/*
		//删除录制失败的时移
		$ret_record_delete = $this->mLivmms->recordDelete($this->settings['mms']['record_server_callback']['prefix'] . $chg_id);
		$ret['record_delete'] = $ret_record_delete;
		*/
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 删除时移回调函数
	 * Enter description here ...
	 */
	function dvr_delete_callback()
	{
		/*
		if (!$this->input['result'])
		{
			$this->errorOutput('时移删除失败');
		}
		*/
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		$add_input = array(
			'dvr_delete' 	=> 1,
			'update_time'	=> TIMENOW,
		);
		
		$ret = $this->mProgramShield->update($add_input, $id);
		$this->addItem($ret);
		$this->output();
	}

	/**
	 * 提交备播文件流媒体执行的回调函数
	 * 
	 */
	public function backup_callback()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入备播文件id');
		}
		
		if (!$this->input['result'])
		{
			$add_input = array(
				'status'	=> 2,
			);
		}
		else 
		{
			$add_input = array(
				'status'	=> 1,
			);
		}
		
		$info = $this->mBackup->backup_edit($id, $add_input);
		
		$backup_info = $this->mBackup->get_backup_by_id($id);
		if ($backup_info['url'] && $backup_info['type'] == 2)
		{
			$path = CUR_CONF_PATH . BACKUP_PATH . $backup_info['filename'];
			if (file_exists($path))
			{
			//	@unlink($backup_info['url']);
				@unlink($path);
			}
		}
		
		$this->addItem($info);
		$this->output();
	}
	
	function unknow()
	{
		$this->errorOutput('未定义的空方法');
	}
	protected function verifyToken()
	{
	}
	
}
$out = new callbackApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>