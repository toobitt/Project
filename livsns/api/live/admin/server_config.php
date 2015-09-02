<?php
/***************************************************************************
* $Id: server_config.php 46780 2015-07-24 03:34:08Z repheal $
* 视屏服务器配置 读接口文件
***************************************************************************/

define('MOD_UNIQUEID','server_config');
require('global.php');
class serverConfigApi extends adminReadBase
{
	private $mServerConfig;													//server configure class obj
	private $mLivemms;
	private $mTvie;															//浪湾
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';			//server configure class file 
		$this->mServerConfig = new serverConfig();
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
		require_once CUR_CONF_PATH . 'lib/tvie.class.php';					//浪湾 class file
		$this->mTvie = new tvie();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index()
	{
		
	}
	
	public function show()
	{
		$condition  = $this->get_condition();
		$offset		= $this->input['offset'] ? intval($this->input['offset']) : 0;	//位移
		$count		= $this->input['count']  ? intval($this->input['count'])  : 20; //count
		$info = $this->mServerConfig->show($condition, $offset, $count);            //show($condition, $offset = 0, $count = 20, $orderby = '')
		if (!empty($info))
		{
			foreach ($info AS $k => $v)
			{
				if(!$v['type'])
					$v['type'] = 'wowza';
				//$v['type'] = $v['type'] ? $v['type'] : 'wowza';
				//检测直播服务器是否通路
				//$is_access_to_server = 'check_server_'.$v['type'];					//the function name which is to check whether the server can be access to 
				//$function = 'check_server_' . $v['type'];
				//$check_server = $this->$is_access_to_server($v);
				$check_result = $this->{'check_server_'.$v['type']}($v);					//the result of checking whether the server can be access to 
				$v['is_success'] = $check_result ? 1 : 0;
				$this->addItem($v);
			}
		}
		$this->output();
	}

	public function detail()
	{
		$id = trim($this->input['id']);
		$ret = $this->mServerConfig->detail($id);
		$ret['hls_path'] = unserialize($ret['hls_path']);
		//查出备份主机
		$sql = "SELECT host,input_port,input_dir,hls_path,is_record FROM " .DB_PREFIX. "server_config WHERE fid = " .$id. " ORDER BY id ASC";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$backup[] = array(
				'host' => $row['host'],
				'input_port' => $row['input_port'],
				'input_dir' => $row['input_dir'],
				'hls_path' => unserialize($row['hls_path']),
				'is_record' => $row['is_record'],
			);
		}
		if(!empty($backup))
		{
			$ret['backup'] = $backup;
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mServerConfig->count($condition);
		echo json_encode($info);
	}
	
	public function show_opration()
	{
		$id = trim($this->input['id']);
		$ret = $this->mServerConfig->detail($id);
		$this->addItem($ret);
		$this->output();
	}
	
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= " AND name LIKE \"%" . trim(urldecode($this->input['k'])) . "%\"";
		}
		$condition .= " AND fid = 0 ";
		return $condition;
	}
	
	private function check_server_wowza($server_info)
	{
		$host 		= $server_info['host'];
		$input_port = $server_info['input_port'];
		$output_dir = $server_info['output_dir'];
		
		$application_data = array(
			'action'	=> 'select',
		);
		
		$return = $this->mLivemms->outputApplicationOperate($host . ':' . $input_port, $output_dir, $application_data);
		return $return;
	}
	
	private function check_server_tvie($server_info)
	{
		$host 			= $server_info['host'];
		$input_port 	= $server_info['input_port'];
		$tvie_dir		= $server_info['output_dir'] ? $server_info['output_dir'] : 'mediaserver/service/';
		$api_token_dir  = $this->settings['tvie']['api_token_dir'] ? $this->settings['tvie']['api_token_dir'] : 'server/api_token/';
		$super_token	= $server_info['super_token'];
		$api_token = $this->mTvie->getApiToken($host . ':' . $input_port, $api_token_dir, $super_token);
		$api_token = @$api_token['api_token'];
		$tvie_data = array(
			'api_token'	=> $api_token,
		);
		$ret_tvie_server = $this->mTvie->getServiceInfo($host . ':' . $input_port, $tvie_dir, $tvie_data);
		if(is_array($ret_tvie_server) && "enabled"==$ret_tvie_server['info']['media_server']['live'])
		{
			return true;
		}
		return false;
// 		if ($ret_tvie_server['info']['media_server']['live'] == 'enabled')
// 		{
// 			return true;//$return = 1;
// 		}
// 		else 
// 		{
// 			$return = 0;
// 		}
// 		return $return;
	}
	//13.08.01 scala 
	private function check_server_nginx($server_info)
	{
		include_once(CUR_CONF_PATH . 'lib/nginx.live.php');
		$server = new m2oLive();
		$matches = $data = array();
		preg_match("/(^[0-9]{2,})\/control\//i",$server_info['input_dir'],$matches);
		if(!empty($matches))
		{
			$data['host'] = $server_info['host'];
			$data['dir'] = ':' . $matches[0];
		}
		else
		{
			$data = array( 
					'host' => $server_info['host'], 
					'dir' => $server_info['input_dir'],
				);
		}
		$server->init_env($data);
		return $server->get_status();
	}
	public function show_stream_status()
	{
		$server_id = intval($this->input['id']);
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'server_config WHERE id = '.$server_id;
		$server_info = $this->db->query_first($sql);
		if(!$server_info)
		{
			$this->errorOutput("服务器配置已被删除");
		}
		$type = $server_info['type'];
		include_once(CUR_CONF_PATH . 'lib/'.$type.'.live.php');
		$server = new m2oLive();
		$server->init_env(array('host'=>$server_info['host'], 'dir'=>$server_info['input_dir']));
		$ret = $server->select();
		if($ret && $type == 'nginx')
		{
			$all_streams = $ret[0]['applications'][0];
			$push_streams = $all_streams['pushes'];
			unset($all_streams['pushes']);
			if($push_streams)
			{
				foreach($push_streams as $k=>$v)
				{
					$v['url'] = build_push_stream_url($v['name'], $server_info);
					$push_streams[$k] = $v;
				}
				$all_streams['pushes'] = $push_streams;
			}
			$this->addItem($all_streams);
		}
		$this->output();
	}
}
$out = new serverConfigApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>