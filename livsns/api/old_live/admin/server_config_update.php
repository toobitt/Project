<?php
/***************************************************************************
* $Id: server_config_update.php 17632 2013-02-23 08:53:47Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','server_config');
require('global.php');
class serverConfigUpdateApi extends adminUpdateBase
{
	private $mServerConfig;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		$name 			= trim(urldecode($this->input['name']));
		$brief 			= trim(urldecode($this->input['brief']));
		$counts 		= trim(intval($this->input['counts']));
		//主控
		$core_in_host 	= trim(urldecode($this->input['core_in_host']));
		$core_in_port 	= trim(intval($this->input['core_in_port']));
		$core_out_port 	= trim(intval($this->input['core_out_port']));
		
		$dvr_append_host = $this->input['dvr_append_host'];
		//时移
		$is_dvr_output	= intval($this->input['is_dvr_output']);
		if ($is_dvr_output)
		{
			$dvr_in_host 	= trim(urldecode($this->input['dvr_in_host']));
			$dvr_in_port 	= trim(intval($this->input['dvr_in_port']));
			$dvr_out_port 	= intval($this->input['dvr_out_port']);
			
			if (!$dvr_in_host)
			{
				$this->errorOutput('时移host不能为空');
			}
			
			if (!$dvr_in_port)
			{
				$this->errorOutput('时移输入端口号不能为空');
			}
			
			if (!$dvr_out_port)
			{
				$this->errorOutput('时移输出端口号不能为空');
			}
		}
		
		//直播
		$is_live_output	= intval($this->input['is_live_output']);
		if ($is_live_output)
		{
			$live_in_host 		= trim(urldecode($this->input['live_in_host']));
			$live_in_port 		= trim(intval($this->input['live_in_port']));
			$live_out_port 		= trim(intval($this->input['live_out_port']));
			
			if (!$live_in_host)
			{
				$this->errorOutput('直播host不能为空');
			}
			
			if (!$live_in_port)
			{
				$this->errorOutput('直播输入端口号不能为空');
			}
			
			if (!$live_out_port)
			{
				$this->errorOutput('直播输出端口号不能为空');
			}
			
			$live_append_host	= $this->input['live_append_host'];
		}
		
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}
		
		if (!$core_in_host)
		{
			$this->errorOutput('host不能为空');
		}
		
		if (!$core_in_port)
		{
			$this->errorOutput('输入端口号不能为空');
		}
		
		if (!$core_out_port)
		{
			$this->errorOutput('输出端口号不能为空');
		}
		
		$protocol	= $this->settings['wowza']['core_input_server']['protocol'];
		$input_dir 	= $this->settings['wowza']['core_input_server']['input_dir'];
		$output_dir = $this->settings['wowza']['core_input_server']['output_dir'];
		
		$record_host		= trim($this->input['record_host']);
		$record_out_host	= trim($this->input['record_out_host']);
		$record_protocol   	= $this->settings['wowza']['record_server']['protocol'];
		$record_dir 		= $this->settings['wowza']['record_server']['dir'];
		$record_port 		= trim(intval($this->input['record_port']));
		
		$add_input = array(
			'name'				=> $name,
			'brief'				=> ($brief == '这里输入描述') ? '' : $brief,
			'protocol'			=> $protocol,
			'core_in_host'		=> $core_in_host,
			'core_in_port'		=> $core_in_port,
			'core_out_port'		=> $core_out_port,
			'is_dvr_output'		=> $is_dvr_output,
			'dvr_in_host'		=> $dvr_in_host,
			'dvr_in_port'		=> $dvr_in_port,
			'dvr_out_port'		=> $dvr_out_port,
			'is_live_output'	=> $is_live_output,
			'live_in_host'		=> $live_in_host,
			'live_in_port'		=> $live_in_port,
			'live_out_port'		=> $live_out_port,
			'input_dir'			=> $input_dir,
			'output_dir'		=> $output_dir,
			'record_protocol'	=> $record_protocol,
			'record_host'		=> $record_host,
			'record_out_host'	=> $record_out_host,
			'record_dir'		=> $record_dir,
			'record_port'		=> $record_port,
			'counts'			=> $counts ? $counts : 100,
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'appid'				=> $this->user['appid'],
			'appname'			=> $this->user['display_name'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'ip'				=> hg_getip(),
			'dvr_append_host'	=> @serialize($dvr_append_host),
			'live_append_host'	=> @serialize($live_append_host),
		);
		
		//添加服务器配置
		$ret = $this->mServerConfig->create($add_input);
		
		if (!$ret['id'])
		{
			$this->errorOutput('添加服务器配置失败');
		}
		
		//删除服务器输出配置
	//	$this->mServerConfig->output_delete_by_server_id($ret['id']);
		
		//添加服务器输出配置
	//	$ret['output'] = $this->output_replace($ret['id'], $add_input);
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		$name 			= trim(urldecode($this->input['name']));
		$brief 			= trim(urldecode($this->input['brief']));
		$counts 		= trim(intval($this->input['counts']));
		//主控
		$core_in_host 	= trim(urldecode($this->input['core_in_host']));
		$core_in_port 	= trim(intval($this->input['core_in_port']));
		$core_out_port 	= trim(intval($this->input['core_out_port']));
		
		$dvr_append_host = $this->input['dvr_append_host'];
		//时移
		$is_dvr_output	= intval($this->input['is_dvr_output']);
		if ($is_dvr_output)
		{
			$dvr_in_host 	= trim(urldecode($this->input['dvr_in_host']));
			$dvr_in_port 	= trim(intval($this->input['dvr_in_port']));
			$dvr_out_port 	= intval($this->input['dvr_out_port']);
			
			if (!$dvr_in_host)
			{
				$this->errorOutput('时移host不能为空');
			}
			
			if (!$dvr_in_port)
			{
				$this->errorOutput('时移输入端口号不能为空');
			}
			
			if (!$dvr_out_port)
			{
				$this->errorOutput('时移输出端口号不能为空');
			}
		}
		
		//直播
		$is_live_output	= intval($this->input['is_live_output']);
		if ($is_live_output)
		{
			$live_in_host 		= trim(urldecode($this->input['live_in_host']));
			$live_in_port 		= trim(intval($this->input['live_in_port']));
			$live_out_port 		= trim(intval($this->input['live_out_port']));
			
			if (!$live_in_host)
			{
				$this->errorOutput('直播host不能为空');
			}
			
			if (!$live_in_port)
			{
				$this->errorOutput('直播输入端口号不能为空');
			}
			
			if (!$live_out_port)
			{
				$this->errorOutput('直播输出端口号不能为空');
			}
			
			$live_append_host	= $this->input['live_append_host'];
		}
		
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}
		
		if (!$core_in_host)
		{
			$this->errorOutput('host不能为空');
		}
		
		if (!$core_in_port)
		{
			$this->errorOutput('输入端口号不能为空');
		}
		
		if (!$core_out_port)
		{
			$this->errorOutput('输出端口号不能为空');
		}
		
		$protocol	= $this->settings['wowza']['core_input_server']['protocol'];
		$input_dir 	= $this->settings['wowza']['core_input_server']['input_dir'];
		$output_dir = $this->settings['wowza']['core_input_server']['output_dir'];
		
		$record_host		= trim($this->input['record_host']);
		$record_out_host	= trim($this->input['record_out_host']);
		$record_protocol   	= $this->settings['wowza']['record_server']['protocol'];
		$record_dir 		= $this->settings['wowza']['record_server']['dir'];
		$record_port 		= trim(intval($this->input['record_port']));
		
		$add_input = array(
			'name'				=> $name,
			'brief'				=> ($brief == '这里输入描述') ? '' : $brief,
			'protocol'			=> $protocol,
			'core_in_host'		=> $core_in_host,
			'core_in_port'		=> $core_in_port,
			'core_out_port'		=> $core_out_port,
			'is_dvr_output'		=> $is_dvr_output,
			'dvr_in_host'		=> $dvr_in_host,
			'dvr_in_port'		=> $dvr_in_port,
			'dvr_out_port'		=> $dvr_out_port,
			'is_live_output'	=> $is_live_output,
			'live_in_host'		=> $live_in_host,
			'live_in_port'		=> $live_in_port,
			'live_out_port'		=> $live_out_port,
			'input_dir'			=> $input_dir,
			'output_dir'		=> $output_dir,
			'record_protocol'	=> $record_protocol,
			'record_host'		=> $record_host,
			'record_out_host'	=> $record_out_host,
			'record_dir'		=> $record_dir,
			'record_port'		=> $record_port,
			'counts'			=> $counts ? $counts : 100,
			'update_time'		=> TIMENOW,
			'dvr_append_host'	=> @serialize($dvr_append_host),
			'live_append_host'	=> @serialize($live_append_host),
		);
		
		//更新服务器配置
		$ret = $this->mServerConfig->update($add_input, $id);
		
		if (!$ret['id'])
		{
			$this->errorOutput('更新服务器配置失败');
		}
		
		//删除服务器输出配置
	//	$this->mServerConfig->output_delete_by_server_id($ret['id']);
		
		//更新服务器输出配置
	//	$ret['output'] = $this->output_replace($ret['id'], $add_input);
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		$id = urldecode($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		$ret = $this->mServerConfig->delete($id);
		if (!$ret)
		{
			$this->errorOutput('删除失败');
		}
		$this->addItem($id);
		$this->output();
	}
	
	public function output_delete()
	{
		$id = urldecode($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		$ret = $this->mServerConfig->output_delete($id);
		if (!$ret)
		{
			$this->errorOutput('删除失败');
		}
		$this->addItem($id);
		$this->output();
	}
	
	private function output_replace($server_id, $add_info)
	{
		//input
		if ($this->settings['wowza']['input'])
		{
			$input = $this->settings['wowza']['input'];
			$input_data = array(
				'server_id'		=> $server_id,
				'mark'			=> 'input',
				'protocol'		=> $input['protocol'],
				'wowzaip'		=> $add_info['core_in_host'],
				'app_name'		=> $input['app_name'],
				'prefix'		=> $input['prefix'],
				'suffix'		=> $input['suffix'],
				'type'			=> $input['type'],
			);
			
			$ret_input = $this->mServerConfig->output_replace($input_data);
		}
		//chg
		if ($this->settings['wowza']['chg'])
		{
			$chg = $this->settings['wowza']['chg'];
			$chg_data = array(
				'server_id'		=> $server_id,
				'mark'			=> 'chg',
				'protocol'		=> $chg['protocol'],
				'wowzaip'		=> $add_info['core_in_host'],
				'app_name'		=> $chg['app_name'],
				'prefix'		=> $chg['prefix'],
				'suffix'		=> $chg['suffix'],
				'type'			=> $chg['type'],
			);
			
			$ret_chg = $this->mServerConfig->output_replace($chg_data);
		}
		//list
		if ($this->settings['wowza']['list'])
		{
			$list = $this->settings['wowza']['list'];
			$list_data = array(
				'server_id'		=> $server_id,
				'mark'			=> 'list',
				'protocol'		=> $list['protocol'],
				'wowzaip'		=> $add_info['core_in_host'],
				'app_name'		=> $list['app_name'],
				'prefix'		=> $list['prefix'],
				'suffix'		=> $list['suffix'],
				'type'			=> $list['type'],
			);
			
			$ret_list = $this->mServerConfig->output_replace($list_data);
		}
		//backup
		if ($this->settings['wowza']['backup'])
		{
			$backup = $this->settings['wowza']['backup'];
			$backup_data = array(
				'server_id'		=> $server_id,
				'mark'			=> 'backup',
				'protocol'		=> $backup['protocol'],
				'wowzaip'		=> $add_info['core_in_host'],
				'app_name'		=> $backup['app_name'],
				'prefix'		=> $backup['prefix'],
				'midfix'		=> $backup['midfix'],
				'suffix'		=> $backup['suffix'],
				'type'			=> $backup['type'],
			);
			
			$ret_backup = $this->mServerConfig->output_replace($backup_data);
		}
		//delay
		if ($this->settings['wowza']['delay'])
		{
			$delay = $this->settings['wowza']['delay'];
			$delay_data = array(
				'server_id'		=> $server_id,
				'mark'			=> 'delay',
				'protocol'		=> $delay['protocol'],
				'wowzaip'		=> $add_info['core_in_host'],
				'app_name'		=> $delay['app_name'],
				'prefix'		=> $delay['prefix'],
				'suffix'		=> $delay['suffix'],
				'type'			=> $delay['type'],
			);
			
			$ret_delay = $this->mServerConfig->output_replace($delay_data);
		}
		//dvr_output
		if ($this->settings['wowza']['dvr_output'])
		{
			$dvr_output = $this->settings['wowza']['dvr_output'];
			$dvr_output_data = array(
				'server_id'		=> $server_id,
				'mark'			=> 'dvr_output',
				'protocol'		=> $dvr_output['protocol'],
				'wowzaip'		=> $add_info['is_dvr_output'] ? $add_info['dvr_in_host'] : $add_info['core_in_host'],
				'app_name'		=> $dvr_output['app_name'],
				'prefix'		=> $dvr_output['prefix'],
				'suffix'		=> $dvr_output['suffix'],
				'type'			=> $dvr_output['type'],
			);
			
			$ret_dvr_output = $this->mServerConfig->output_replace($dvr_output_data);
		}
		//live_output
		if ($this->settings['wowza']['live_output'])
		{
			$live_output = $this->settings['wowza']['live_output'];
			$live_output_data = array(
				'server_id'		=> $server_id,
				'mark'			=> 'live_output',
				'protocol'		=> $live_output['protocol'],
				'wowzaip'		=> $add_info['is_live_output'] ? $add_info['live_in_host'] : $add_info['core_in_host'],
				'app_name'		=> $live_output['app_name'],
				'prefix'		=> $live_output['prefix'],
				'suffix'		=> $live_output['suffix'],
				'type'			=> $live_output['type'],
			);
			
			$ret_live_output = $this->mServerConfig->output_replace($live_output_data);
		}
		//record
		if ($this->settings['wowza']['record'])
		{
			$record = $this->settings['wowza']['record'];
			$record_data = array(
				'server_id'		=> $server_id,
				'mark'			=> 'record',
				'protocol'		=> $record['protocol'],
				'wowzaip'		=> $add_info['record_host'],
				'app_name'		=> $record['app_name'],
				'prefix'		=> $record['prefix'],
				'suffix'		=> $record['suffix'],
				'type'			=> $record['type'],
			);
			
			$ret_record = $this->mServerConfig->output_replace($record_data);
		}
		$ret = array(
			'input'			=> $ret_input,
			'chg'			=> $ret_chg,
			'list'			=> $ret_list,
			'backup'		=> $ret_backup,
			'delay'			=> $ret_delay,
			'dvr_output'	=> $ret_dvr_output,
			'record'		=> $ret_record,
			'live_output'	=> $ret_live_output,
		);
		return $ret;
	} 
	
	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
	
	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}

}

$out = new serverConfigUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>