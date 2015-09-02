<?php
/***************************************************************************
* $Id: server_config_update.php 38506 2014-07-23 05:39:23Z kangxiaoqiang $
***************************************************************************/
define('MOD_UNIQUEID','server_config');
require('global.php');
class serverConfigUpdateApi extends adminUpdateBase
{
	private $mServerConfig;
	private $mLivemms;
	private $mTvie;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();

		require_once CUR_CONF_PATH . 'lib/tvie.class.php';
		$this->mTvie = new tvie();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		$this->verify_setting_prms();
		$name 			= trim($this->input['name']);
		$brief 			= trim($this->input['brief']);
		$counts 		= trim(intval($this->input['counts']));

		$host 				= trim($this->input['host']);
		$input_port 		= trim(intval($this->input['input_port']));
		$output_port 		= trim(intval($this->input['output_port']));
		$output_append_host = $this->input['output_append_host'];

		$live_host 			= trim($this->input['live_host']);
		$live_input_port 	= trim(intval($this->input['live_input_port']));
		$live_output_port 	= trim(intval($this->input['live_output_port']));
		
		$record_host 		= trim($this->input['record_host']);
		$record_input_port 	= trim(intval($this->input['record_input_port']));
		$record_output_port = trim(intval($this->input['record_output_port']));
		
		$type 		 = $this->input['type'] ? trim($this->input['type']) : 'nginx';
		$super_token = trim($this->input['super_token']);
		//2013.08.01
		if ($type == 'wowza')
		{
			$input_port_text  = '输入端口不能为空';
			$output_port_text = '输出端口不能为空';
			
			$live_input_port_text  	 = '直播服务器输入端口不能为空';
			$live_output_port_text 	 = '直播服务器输出端口不能为空';
			$record_input_port_text  = '录制服务器输入端口不能为空';
			$record_output_port_text = '录制服务器输出端口不能为空';
			
			$protocol	= $this->settings['wowza']['live_server']['protocol'];
			$input_dir 	= $this->settings['wowza']['live_server']['input_dir'];
			$output_dir = $this->settings['wowza']['live_server']['output_dir'];
		}
		else if($type == 'nginx')
		{
			$input_port_text  = '输入端口不能为空';
			$output_port_text = '输出端口不能为空';
			
			$live_input_port_text  	 = '直播服务器输入端口不能为空';
			$live_output_port_text 	 = '直播服务器输出端口不能为空';
			$record_input_port_text  = '录制服务器输入端口不能为空';
			$record_output_port_text = '录制服务器输出端口不能为空';
			
			$output_dir = trim($this->input['nginx_dir']);
			$input_dir = trim($this->input['input_dir']);

		}
		else if($type == 'tvie')
		{
			if (!$super_token)
			{
				$this->errorOutput('SUPER_TOKEN不能为空');
			}
			
			$input_port_text  = 'API端口不能为空';
			$output_port_text = '服务器端口不能为空';
			
			$live_input_port_text    = '直播服务器API端口不能为空';
			$live_output_port_text   = '直播服务器端口不能为空';
			$record_input_port_text  = '录制服务器API端口不能为空';
			$record_output_port_text = '录制服务器端口不能为空';
			
			$protocol	= $this->settings['tvie']['tvie_server']['protocol'];
			$input_dir 	= $this->settings['tvie']['tvie_server']['api_dir'] ? $this->settings['tvie']['tvie_server']['api_dir'] : 'mediaserver/media/live/';
			
			$tvie_dir  	   = $this->settings['tvie']['tvie_server']['server_dir'] ? $this->settings['tvie']['tvie_server']['server_dir'] : 'mediaserver/service/';
			$api_token_dir = $this->settings['tvie']['api_token_dir'] ? $this->settings['tvie']['api_token_dir'] : 'server/api_token/';
			$api_token     = $this->mTvie->getApiToken($host . ':' . $input_port, $api_token_dir, $super_token);
			if ($api_token['sub_codes'])
			{
				$this->errorOutput(var_export($api_token,1));
			}
			
			$api_token = $api_token['api_token'];
			
			$tvie_data = array(
				'api_token'	=> $api_token,
			);
			$output_dir = $tvie_dir;
		}

		
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}
		
		if (!$host)
		{
			$this->errorOutput('主机不能为空');
		}
		
		if (!$input_port)
		{
			//$this->errorOutput($input_port_text);
		}
		
		if (!$output_port)
		{
			//$this->errorOutput($output_port_text);
		}
		
		if ($this->input['is_live'] && $this->input['is_record'])
		{
			if ($live_host == $record_host)
			{
				$this->errorOutput('直播服务器和录制服务器主机不能相同');
			}
		}
		
		if ($type == 'wowza')
		{
			//检测主控服务器是否通路
			$application_data = array(
				'action'	=> 'select',
			);
			
			$ret_select = $this->mLivemms->outputApplicationOperate($host . ':' . $input_port, $output_dir, $application_data);
	
			if (!$ret_select)
			{
				$this->errorOutput('主控服务器配置有误或已停止服务');
			}
		}
		else if ($type == 'tvie')
		{
			$ret_tvie_server = $this->mTvie->getServiceInfo($host . ':' . $input_port, $tvie_dir, $tvie_data);
			if ($ret_tvie_server['info']['media_server']['live'] != 'enabled')
			{
				$this->errorOutput(var_export($ret_tvie_server,1));
			}
		}
		
		//直播服务器配置
		if ($this->input['is_live'])
		{
			if (!$live_host)
			{
				$this->errorOutput('直播服务器主机不能为空');
			}
			
			if (!$live_input_port)
			{
				//$this->errorOutput($live_input_port_text);
			}
			
			if (!$live_output_port)
			{
				//$this->errorOutput($live_output_port_text);
			}
			
			if ($type == 'wowza')
			{
				//检测直播服务器是否通路
				$application_data = array(
					'action'	=> 'select',
				);
				
				$ret_select = $this->mLivemms->outputApplicationOperate($live_host . ':' . $live_input_port, $output_dir, $application_data);
		
				if (!$ret_select)
				{
					$this->errorOutput('直播服务器配置有误或已停止服务');
				}
			}
			else if ($type == 'tvie')
			{
				$ret_tvie_server = $this->mTvie->getServiceInfo($host . ':' . $input_port, $tvie_dir, $tvie_data);
				if ($ret_tvie_server['info']['media_server']['live'] != 'enabled')
				{
					$this->errorOutput(var_export($ret_tvie_server,1));
				}
			}
		}
		else 
		{
			$live_host 			= '';
			$live_input_port 	= 0;
			$live_output_port 	= 0;
		}
		
		//录制服务器配置
		if ($this->input['is_record'])
		{
			if (!$record_host)
			{
				$this->errorOutput('录制服务器主机不能为空');
			}
			
			if (!$record_input_port)
			{
				//$this->errorOutput($record_input_port_text);
			}
			
			if (!$record_output_port)
			{
				//$this->errorOutput($record_output_port_text);
			}
		
			if ($type == 'wowza')
			{
				//检测录制服务器是否通路
				$application_data = array(
					'action'	=> 'select',
				);
				
				$ret_select = $this->mLivemms->outputApplicationOperate($record_host . ':' . $record_input_port, $output_dir, $application_data);
		
				if (!$ret_select)
				{
					$this->errorOutput('录制服务器配置有误或已停止服务');
				}
			}
			else if ($type == 'tvie')
			{
				$ret_tvie_server = $this->mTvie->getServiceInfo($host . ':' . $input_port, $tvie_dir, $tvie_data);
				if ($ret_tvie_server['info']['media_server']['live'] != 'enabled')
				{
					$this->errorOutput(var_export($ret_tvie_server,1));
				}
			}
		}
		else 
		{
			$record_host 		= '';
			$record_input_port 	= 0;
			$record_output_port = 0;
		}
		
		$_output_append_host = array();
		if (!empty($output_append_host))
		{
			foreach ($output_append_host AS $v)
			{
				$_output_append_host[] = trim($v);
			}
		}
		
		$data = array(
			'name'				=> $name,
			'brief'				=> ($brief == '这里输入描述') ? '' : $brief,
			'counts'			=> $counts ? $counts : 100,
			'protocol'			=> $protocol ? $protocol : 'http://',
			'host'				=> $host,
			'input_port'		=> $input_port,
			'output_port'		=> $output_port,
			'input_dir'			=> $input_dir ? $input_dir : '',
			'output_dir'		=> $output_dir ? $output_dir : 'outputmanager/',
			'output_append_host'=> !empty($_output_append_host[0]) ? serialize($_output_append_host) : '',
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'appid'				=> $this->user['appid'],
			'appname'			=> $this->user['display_name'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'ip'				=> hg_getip(),
			'live_host'			=> $live_host,
			'live_input_port'	=> $live_input_port,
			'live_output_port'	=> $live_output_port,
			'record_host'		=> $record_host,
			'record_input_port'	=> $record_input_port,
			'record_output_port'=> $record_output_port,
			'type'				=> $type,
			'super_token'		=> $super_token,
			//'ts_host'		=> $this->input['ts_host'],
			'ts_host'		=> 'http://'.$host,
			'is_used'		=> 1, //默认使用主控
			'out_host'		=> $this->input['out_host'],
			'hls_path'		=> serialize(array('base_hls_path'=>$this->input['base_hls_path'],'hls_path'=>'/'.trim($this->input['hls_path'],'/'))),
		);
		if(trim($this->input['record_default']) && trim($this->input['record_default']) == $data['host'])
		{
			$data['is_record'] = 1; //作为录制主机
		}
		//添加服务器配置
		$ret = $this->mServerConfig->create($data);
		
		if (!$ret['id'])
		{
			$this->errorOutput('添加服务器配置失败');
		}
		
		//添加备份主机
		$tmp = array_filter($this->input['b_host']);
		if(!empty($tmp))
		{
			$b_host = $this->input['b_host'];
			$b_input_port = $this->input['b_input_port'];
			$b_input_dir = $this->input['b_input_dir'];
			$b_base_hls_path = $this->input['b_base_hls_path'];
			$b_hls_path = $this->input['b_hls_path'];
			//$b_data = $data;
			for($i=0;$i<count($b_host);$i++)
			{
				if(trim($b_host[$i]))
				{
					$b_data['fid'] = $ret['id'];
					$b_data['host'] = $b_host[$i];
					$b_data['input_port'] = $b_input_port[$i];
					$b_data['input_dir'] = $b_input_dir[$i];
					$b_data['hls_path'] = serialize(array('base_hls_path'=>$b_base_hls_path[$i],'hls_path'=>'/'.trim($b_hls_path[$i],'/')));
					$b_data['ts_host'] = 'http://'.$b_host[$i];
					if(trim($this->input['record_default']) && trim($this->input['record_default']) == $b_host[$i])
					{
						$b_data['is_record'] = 1; //作为录制主机
					}
					$b_ret = $this->mServerConfig->create($b_data);
				}
			}
		}
		
		//缓存直播服务器配置信息
		$this->get_server_config();
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		$this->verify_setting_prms();
		
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		$name 			= trim($this->input['name']);
		$brief 			= trim($this->input['brief']);
		$counts 		= trim(intval($this->input['counts']));

		$host 				= trim($this->input['host']);
		$input_port 		= trim(intval($this->input['input_port']));
		$output_port 		= trim(intval($this->input['output_port']));
		$output_append_host = $this->input['output_append_host'];
				
		$live_host 			= trim($this->input['live_host']);
		$live_input_port 	= trim(intval($this->input['live_input_port']));
		$live_output_port 	= trim(intval($this->input['live_output_port']));
		
		$record_host 		= trim($this->input['record_host']);
		$record_input_port 	= trim(intval($this->input['record_input_port']));
		$record_output_port = trim(intval($this->input['record_output_port']));
		
		//$type 		 = $this->input['type'] ? trim($this->input['type']) : 'wowza';
		$type = 'nginx';
		$super_token = trim($this->input['super_token']);
		if($type=='nginx')
		{
			$input_port_text  = '输入端口不能为空';
			$output_port_text = '输出端口不能为空';
			
			$live_input_port_text  	 = '直播服务器输入端口不能为空';
			$live_output_port_text 	 = '直播服务器输出端口不能为空';
			$record_input_port_text  = '录制服务器输入端口不能为空';
			$record_output_port_text = '录制服务器输出端口不能为空';
			$output_dir = trim($this->input['nginx_dir']);
			$input_dir = trim($this->input['input_dir']);
			
		}
		else if ($type == 'wowza')
		{
			$input_port_text  = '输入端口不能为空';
			$output_port_text = '输出端口不能为空';
			
			$live_input_port_text  	 = '直播服务器输入端口不能为空';
			$live_output_port_text 	 = '直播服务器输出端口不能为空';
			$record_input_port_text  = '录制服务器输入端口不能为空';
			$record_output_port_text = '录制服务器输出端口不能为空';
			
			$protocol	= $this->settings['wowza']['live_server']['protocol'];
			$input_dir 	= $this->settings['wowza']['live_server']['input_dir'];
			$output_dir = $this->settings['wowza']['live_server']['output_dir'];
		}
		else if($type == 'tvie')
		{
			if (!$super_token)
			{
				$this->errorOutput('SUPER_TOKEN不能为空');
			}
			
			$input_port_text  = 'API端口不能为空';
			$output_port_text = '服务器端口不能为空';
			
			$live_input_port_text    = '直播服务器API端口不能为空';
			$live_output_port_text   = '直播服务器端口不能为空';
			$record_input_port_text  = '录制服务器API端口不能为空';
			$record_output_port_text = '录制服务器端口不能为空';
			
			$protocol	= $this->settings['tvie']['tvie_server']['protocol'];
			$input_dir 	= $this->settings['tvie']['tvie_server']['api_dir'] ? $this->settings['tvie']['tvie_server']['api_dir'] : 'mediaserver/media/live/';
			
			$tvie_dir  	   = $this->settings['tvie']['tvie_server']['server_dir'] ? $this->settings['tvie']['tvie_server']['server_dir'] : 'mediaserver/service/';
			$api_token_dir = $this->settings['tvie']['api_token_dir'] ? $this->settings['tvie']['api_token_dir'] : 'server/api_token/';
			$api_token	   = $this->mTvie->getApiToken($host . ':' . $input_port, $api_token_dir, $super_token);
			if ($api_token['sub_codes'])
			{
				$this->errorOutput(var_export($api_token,1));
			}
			
			$api_token = $api_token['api_token'];
			
			$tvie_data = array(
				'api_token'	=> $api_token,
			);
			$output_dir = $tvie_dir;
		}
		
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}
		
		if (!$host)
		{
			$this->errorOutput('主机不能为空');
		}
		
		if (!$input_port)
		{
			//$this->errorOutput($input_port_text);
		}
		
		if (!$output_port)
		{
			//$this->errorOutput($output_port_text);
		}
		
		if ($this->input['is_live'] && $this->input['is_record'])
		{
			if ($live_host == $record_host)
			{
				$this->errorOutput('直播服务器和录制服务器主机不能相同');
			}
		}
		
		if ($type == 'wowza')
		{
			//检测主控服务器是否通路
			$application_data = array(
				'action'	=> 'select',
			);
			
			$ret_select = $this->mLivemms->outputApplicationOperate($host . ':' . $input_port, $output_dir, $application_data);
	
			if (!$ret_select)
			{
				$this->errorOutput('主控服务器配置有误或已停止服务');
			}
		}
		else if ($type == 'tvie')
		{
			$ret_tvie_server = $this->mTvie->getServiceInfo($host . ':' . $input_port, $tvie_dir, $tvie_data);
			if ($ret_tvie_server['info']['media_server']['live'] != 'enabled')
			{
				$this->errorOutput(var_export($ret_tvie_server,1));
			}
		}
		
		//直播服务器配置
		if ($this->input['is_live'])
		{
			if (!$live_host)
			{
				$this->errorOutput('直播服务器主机不能为空');
			}
			
			if (!$live_input_port)
			{
				//$this->errorOutput($live_input_port_text);
			}
			
			if (!$live_output_port)
			{
				//$this->errorOutput($live_output_port_text);
			}
			
			if ($type == 'wowza')
			{
				//检测直播服务器是否通路
				$application_data = array(
					'action'	=> 'select',
				);
				
				$ret_select = $this->mLivemms->outputApplicationOperate($live_host . ':' . $live_input_port, $output_dir, $application_data);
		
				if (!$ret_select)
				{
					$this->errorOutput('直播服务器配置有误或已停止服务');
				}
			}
			else if ($type == 'tvie')
			{
				$ret_tvie_server = $this->mTvie->getServiceInfo($host . ':' . $input_port, $tvie_dir, $tvie_data);
				if ($ret_tvie_server['info']['media_server']['live'] != 'enabled')
				{
					$this->errorOutput(var_export($ret_tvie_server,1));
				}
			}
			
		}
		else 
		{
			$live_host 			= '';
			$live_input_port 	= 0;
			$live_output_port 	= 0;
		}
		
		//录制服务器配置
		if ($this->input['is_record'])
		{
			if (!$record_host)
			{
				$this->errorOutput('录制服务器主机不能为空');
			}
			
			if (!$record_input_port)
			{
				//$this->errorOutput($record_input_port_text);
			}
			
			if (!$record_output_port)
			{
				//$this->errorOutput($record_output_port_text);
			}
		
			if ($type == 'wowza')
			{
				//检测录制服务器是否通路
				$application_data = array(
					'action'	=> 'select',
				);
				
				$ret_select = $this->mLivemms->outputApplicationOperate($record_host . ':' . $record_input_port, $output_dir, $application_data);
		
				if (!$ret_select)
				{
					$this->errorOutput('录制服务器配置有误或已停止服务');
				}
			}
			else if ($type == 'tvie')
			{
				$ret_tvie_server = $this->mTvie->getServiceInfo($host . ':' . $input_port, $tvie_dir, $tvie_data);
				if ($ret_tvie_server['info']['media_server']['live'] != 'enabled')
				{
					$this->errorOutput(var_export($ret_tvie_server,1));
				}
			}
		}
		else 
		{
			$record_host 		= '';
			$record_input_port 	= 0;
			$record_output_port = 0;
		}
	
		$_output_append_host = array();
		if (!empty($output_append_host))
		{
			foreach ($output_append_host AS $v)
			{
				$_output_append_host[] = trim($v);
			}
		}
		
		$data = array(
			'id'				=> $id,
			'name'				=> $name,
			'brief'				=> ($brief == '这里输入描述') ? '' : $brief,
			'counts'			=> $counts ? $counts : 100,
			'protocol'			=> $protocol ? $protocol : 'http://',
			'host'				=> $host,
			'input_port'		=> $input_port,
			'output_port'		=> $output_port,
			'input_dir'			=> $input_dir ? $input_dir : '',
			'output_dir'		=> $output_dir ? $output_dir : 'outputmanager/',
			'output_append_host'=> !empty($_output_append_host[0]) ? serialize($_output_append_host) : '',
			'update_time'		=> TIMENOW,
			'live_host'			=> $live_host,
			'live_input_port'	=> $live_input_port,
			'live_output_port'	=> $live_output_port,
			'record_host'		=> $record_host,
			'record_input_port'	=> $record_input_port,
			'record_output_port'=> $record_output_port,
			'super_token'		=> $super_token,
			'ts_host'		=> 'http://'.$host,
			'out_host'		=> $this->input['out_host'],
			//'hls_path'		=> trim($this->input['hls_path']),
			'hls_path'		=> serialize(array('base_hls_path'=>$this->input['base_hls_path'],'hls_path'=>'/'.trim($this->input['hls_path'],'/'))),
		);
		if(trim($this->input['record_default']) && trim($this->input['record_default']) == $data['host'])
		{
			$data['is_record'] = 1; //作为录制主机
		}
		else
		{
			$data['is_record'] = 0;
		}
		//更新服务器配置
		$ret = $this->mServerConfig->update($data);
		
		if (!$ret['id'])
		{
			$this->errorOutput('更新服务器配置失败');
		}
		//更新备份主机
		$tmp = array_filter($this->input['b_host']);
		if(!empty($tmp))
		{
			$sql = "DELETE FROM " .DB_PREFIX. "server_config WHERE fid = " .$this->input['id'];
			$this->db->query($sql);
			
			$b_host = $this->input['b_host'];
			$b_input_port = $this->input['b_input_port'];
			$b_input_dir = $this->input['b_input_dir'];
			$b_base_hls_path = $this->input['b_base_hls_path'];
			$b_hls_path = $this->input['b_hls_path'];
			for($i=0;$i<count($b_host);$i++)
			{
				if(trim($b_host[$i]))
				{
					$b_data['fid'] = $this->input['id'];
					$b_data['host'] = $b_host[$i];
					$b_data['input_port'] = $b_input_port[$i];
					$b_data['input_dir'] = $b_input_dir[$i];
					//$b_data['hls_path'] = '/'. trim($b_hls_path[$i],'/');
					$b_data['hls_path'] = serialize(array('base_hls_path'=>$b_base_hls_path[$i],'hls_path'=>'/'.trim($b_hls_path[$i],'/')));
					$b_data['ts_host'] = 'http://'.$b_host[$i];
					if(trim($this->input['record_default']) && trim($this->input['record_default']) == $b_host[$i])
					{
						$b_data['is_record'] = 1;
					}
					else
					{
						$b_data['is_record'] = 0;
					}
					$b_ret = $this->mServerConfig->create($b_data);
				}
			}
		}
		else
		{
			$sql = "DELETE FROM " .DB_PREFIX. "server_config WHERE fid = " .$this->input['id'];
			$this->db->query($sql);
		}
		//缓存直播服务器配置信息
		$this->get_server_config();
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		$this->verify_setting_prms();
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
		//缓存直播服务器配置信息
		$this->get_server_config();
		
		$this->addItem($id);
		$this->output();
	}
	
	private function get_server_config()
	{
		$alive_filename = $this->settings['alive_filename'] ? $this->settings['alive_filename'] : 'alive';
		$filename 		= $alive_filename . '.php';
		
		$sql = "SELECT id, host, output_dir, input_port, type FROM " . DB_PREFIX . "server_config ";
		$sql.= " WHERE status = 1 ORDER BY id DESC ";
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['host'] = $row['host'] . ':' . $row['input_port'];
			$return[] = $row;
		}
		
		$content = '<?php
			if (!IS_READ)
			{		
				exit();
			}
			$return = ' . var_export($return, 1) . ';
		?>';
		hg_file_write(CACHE_DIR . $filename, $content);
	
		return $return;
	}
	
	public function audit()
	{
		$this->verify_setting_prms();
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
	
		$record_info = $this->mServerConfig->get_server_config_by_id($id);
		
		if (empty($record_info))
		{
			$this->errorOutput('该配置不存在或已被删除');
		}
		
		$status = $record_info['status'];
		
		$update_data = array(
			'id'	 => $id,
		);
		
		$ret = 0;
		if (!$status)
		{
			$update_data['status'] = 1;
			$this->mServerConfig->update($update_data);
			
			$ret = 1;
		}
		else
		{
			$update_data['status'] = 0;
			$this->mServerConfig->update($update_data);
			
			$ret = 2;
		}
		//缓存直播服务器配置信息
		$this->get_server_config();
		
		$this->addItem($ret);
		$this->output();
	}
	
	/*
	 * 切换主机
	 */
	public function switch_host()
	{
		$id = $this->input['id']; //配置id
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$is_on = $this->input['is_on']; //操作,1是切到备播,0是切到主控
		
		//获取使用该配置的所有频道标识
		$sql = "SELECT code,table_name FROM " .DB_PREFIX. "channel WHERE server_id = " .$id;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$codes[] = array(
				'code' => $row['code'],
				'table_name' => $row['table_name'],
			); 
		}

		/***************** 获取主备服务器信息 *******************/
		$sql = "SELECT fid,host,input_dir,ts_host FROM " .DB_PREFIX. "server_config WHERE id = " .$id. " OR fid = " .$id;
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			if(!$row['fid'])
				$host['m_host'] = $row; //主控
			else
				$host['b_host'] = $row; //备播
		}
		include_once(CUR_CONF_PATH . 'lib/nginx.live.php');
		$server = new m2oLive();
		/******************************************************/

		if($is_on) //打开开关 (开启备播,关闭主控)
		{
			//备播服务器状态验证
			$server->init_env(array('host'=>$host['b_host']['host'], 'dir'=>$host['b_host']['input_dir']));
			if(!$server->get_status())
			{
				$this->errorOutput('备播主机异常,不能开启');
			}
			//重写缓存
			foreach((array)$codes as $k => $v)
			{
				@include(CACHE_DIR . 'channel/' . $v['code'] . '.php');
				$v['table_name'] == 'dvr' ? $tablename = 'dvr1' : $tablename = $v['table_name'].'_1';
				$channel_info['channel']['table_name'] = $tablename;
				$channel_info['channel']['config']['ts_host'] = $host['b_host']['ts_host'];
				file_put_contents(CACHE_DIR . 'channel/' . $v['code'] . '.php', '<?php $channel_info = ' . var_export($channel_info, 1) . ';?>');
			}
			//打开开关
			$sql = "UPDATE " .DB_PREFIX. "server_config SET is_used = 0 WHERE id = " .$id;
			$this->db->query($sql);
		}
		else //关闭开关 (关闭备播,打开主控)
		{
			//主控服务器状态验证
			$server->init_env(array('host'=>$host['m_host']['host'], 'dir'=>$host['m_host']['input_dir']));
			if(!$server->get_status())
			{
				$this->errorOutput('主控主机异常,不能开启');
			}
			//重建缓存
			foreach((array)$codes as $k => $v)
			{
				@unlink(CACHE_DIR	 . 'channel/' . $v['code'] . '.php');
			}
			//关闭开关
			$sql = "UPDATE " .DB_PREFIX. "server_config SET is_used = 1 WHERE id = " .$id;
			$this->db->query($sql);
		}
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