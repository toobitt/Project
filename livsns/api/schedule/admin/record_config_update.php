<?php
/***************************************************************************
* $Id: record_config_update.php 23181 2013-06-05 09:47:01Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','record_config');
require('global.php');
class recordConfigUpdateApi extends adminUpdateBase
{
	private $mRecordConfig;
	private $mLivemms;
	private $curl;
	public function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/record_config.class.php';
		$this->mRecordConfig = new recordConfig();
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		$this->verify_setting_prms();
		$name 	= trim($this->input['name']);
		$brief 	= trim($this->input['brief']);

		$record_protocol   	= $this->settings['record_server']['protocol'];
		$record_host		= trim($this->input['record_host']);
		$record_dir 		= $this->settings['record_server']['dir'];
		$record_port 		= trim(intval($this->input['record_port']));
		$record_output_host	= trim($this->input['record_output_host']);
	//	$record_output_dir	= $this->settings['record']['dir'];
	
		$record_protocol	= $record_protocol ? $record_protocol : 'http://';
		
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}
		
		if (!$record_host)
		{
			$this->errorOutput('主机不能为空');
		}
		
		if (!$record_port)
		{
			$this->errorOutput('端口号不能为空');
		}
		
		if (!$record_output_host)
		{
			$this->errorOutput('源视频目录地址不能为空');
		}
		
		//检测时移服务器是否通路
        $ret_check_server = $this->mRecordConfig->check_server($record_host . ':' . $record_port);
		if (!$ret_check_server)
		{
			$this->errorOutput('服务器配置有误或者已停止服务');
		}
		
		//修改时移服务器路径
		$record_server = array(
			'protocol'	=> $record_protocol,
			'host' 		=> $record_host,
			'dir' 		=> $record_dir,
			'port' 		=> $record_port,
		);
		
		$ret_mediaserver = $this->record_server_edit($record_server);
		if (!$ret_mediaserver['result'])
		{
			$this->errorOutput('修改时移服务器路径失败');
		}
		
		$data = array(
			'name'				=> $name,
			'brief'				=> ($brief == '这里输入描述') ? '' : $brief,
			'record_protocol'	=> $record_protocol,
			'record_host'		=> $record_host,
			'record_dir'		=> $record_dir,
			'record_port'		=> $record_port,
			'record_output_host'=> $record_output_host,
		//	'record_output_dir'	=> $record_output_dir,
			'timeshift_filepath'=> $ret_mediaserver['default_timeshift_file_path'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'appid'				=> $this->user['appid'],
			'appname'			=> $this->user['display_name'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'ip'				=> hg_getip(),
		);
		
		//添加服务器配置
		$ret = $this->mRecordConfig->create($data);
		
		if (!$ret['id'])
		{
			$this->errorOutput('添加服务器配置失败');
		}
		
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
		
		$name 	= trim($this->input['name']);
		$brief 	= trim($this->input['brief']);

		$record_protocol   	= $this->settings['record_server']['protocol'];
		$record_host		= trim($this->input['record_host']);
		$record_dir 		= $this->settings['record_server']['dir'];
		$record_port 		= trim(intval($this->input['record_port']));
		$record_output_host	= trim($this->input['record_output_host']);
	//	$record_output_dir	= $this->settings['record']['dir'];
	
		$record_protocol 	= $record_protocol ? $record_protocol : 'http://';
		
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}
		
		if (!$record_host)
		{
			$this->errorOutput('主机不能为空');
		}
		
		if (!$record_port)
		{
			$this->errorOutput('端口号不能为空');
		}
		
		if (!$record_output_host)
		{
			$this->errorOutput('源视频目录地址不能为空');
		}
	
		//检测时移服务器是否通路
        $ret_check_server = $this->mRecordConfig->check_server($record_host . ':' . $record_port);
		if (!$ret_check_server)
		{
			$this->errorOutput('服务器配置有误或者已停止服务');
		}
		
		//修改时移服务器路径
		$record_server = array(
			'protocol'	=> $record_protocol,
			'host' 		=> $record_host,
			'dir' 		=> $record_dir,
			'port' 		=> $record_port,
		);
		
		$ret_mediaserver = $this->record_server_edit($record_server);
		if (!$ret_mediaserver['result'])
		{
			$this->errorOutput('修改时移服务器路径失败');
		}
		
		$data = array(
			'id'				=> $id,
			'name'				=> $name,
			'brief'				=> ($brief == '这里输入描述') ? '' : $brief,
			'record_protocol'	=> $record_protocol,
			'record_host'		=> $record_host,
			'record_dir'		=> $record_dir,
			'record_port'		=> $record_port,
			'record_output_host'=> $record_output_host,
		//	'record_output_dir'	=> $record_output_dir,
			'timeshift_filepath'=> $ret_mediaserver['default_timeshift_file_path'],
			'update_time'		=> TIMENOW,
		);

		//更新服务器配置
		$ret = $this->mRecordConfig->update($data);
		
		if (!$ret['id'])
		{
			$this->errorOutput('更新服务器配置失败');
		}
		
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
		$ret = $this->mRecordConfig->delete($id);
		if (!$ret)
		{
			$this->errorOutput('删除失败');
		}
		$this->addItem($id);
		$this->output();
	}
	
	private function record_server_edit($record_server)
	{
		if (empty($record_server))
		{
			return false;
		}
		
		$config = $this->mRecordConfig->get_mediaserver_config();
		
		if (empty($config))
		{
			return false;
		}
		
		$host = $record_server['host'] . ':' . $record_server['port'];
		$dir  = $record_server['dir'];
		
		if($record_server['host'])
		{
			//获取文件配置
			$get_data = array(
				'action'	=> 'GET_CONFIG',
			);
			
			//修改文件路径
			$edit_data = array(
				'action' 						=> 'MODIFY_CONFIG',
				'default_record_file_path' 		=> $config['default_record_file_path'],
				'default_timeshift_file_path' 	=> $config['default_timeshift_file_path'],
			);
			
			$ret_config = $this->mediaServerOperate($host, $dir, $get_data);
			
			if ($ret_config['default_timeshift_file_path'] == $config['default_timeshift_file_path'])
			{
				$ret = array(
					'result' => '1',
				);
			}
			else
			{
				$ret = $this->mediaServerOperate($host, $dir, $edit_data);
			}
			
			$ret['default_record_file_path'] 		= $config['default_record_file_path'];
    		$ret['default_timeshift_file_path'] 	= $config['default_timeshift_file_path'];
    		
			return $ret;
		}
		return false;
	}

	private function mediaServerOperate($host, $dir, $data = array())
	{
		$this->curl = new curl();
		if (!$this->curl)
		{
			return array();
		}
		
		$this->curl->setUrlHost($host, $dir);
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->setReturnFormat('json');
		
		$action = array('MODIFY_CONFIG', 'GET_CONFIG');
		
		if (!in_array($data['action'], $action))
		{
			return array();
		}
		
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		
		$ret = $this->curl->request('');
		return xml2Array($ret);
	}

	public function audit()
	{
		$this->verify_setting_prms();
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
	
		$record_info = $this->mRecordConfig->get_record_config_by_id($id);
		
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
			$this->mRecordConfig->update($update_data);
			
			$ret = 1;
		}
		else
		{
			$update_data['status'] = 0;
			$this->mRecordConfig->update($update_data);
			
			$ret = 2;
		}
		$this->addItem($ret);
		$this->output();
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

$out = new recordConfigUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>