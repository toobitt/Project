<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_update.php 4728 2011-10-12 10:38:02Z lijiaying $
***************************************************************************/
require('global.php');
require_once(CUR_CONF_PATH . 'lib/server.class.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
define('MOD_UNIQUEID','time_shift_server');
class serverUpdateApi extends adminUpdateBase
{
	private $obj;
	function __construct()
	{
		parent::__construct();
		$this->obj = new server();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function audit(){}
	public function sort(){}
	public function publish(){}
	
	public function create()
	{
		$info = array(
			'name' 			=> trim($this->input['name']) ? trim($this->input['name']) : '',
			'host' 			=> trim($this->input['host']) ? trim($this->input['host']) : '',
			'port' 			=> intval($this->input['port']) ? intval($this->input['port']) : 0,	
			'is_open' 		=> intval($this->input['is_open']) ? intval($this->input['is_open']) : 0,	
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
		);
		
		if(empty($info['name']))
		{
			$this->errorOutput('请传入服务器名');
		}
		
		if(empty($info['host']))
		{
			$this->errorOutput('请传入服务器地址');
		}
		
		$time_shift_server = array(
			'protocol'	=> 'http://',
			'host' 		=> $info['host'],
			'port' 		=> $info['port']
		);
		$server_url = $time_shift_server['protocol'] . $time_shift_server['host'] . ($time_shift_server['port']? ':' . $time_shift_server['port']:'');
		if(!check_shift_server($server_url))
		{
			$this->errorOutput('连不上服务器，请检查主机与端口是否正确');
		}
		//此处更新时移服务器上面的目录配置
		$this->update_time_shift_server_dir($time_shift_server);
		
		$ret = $this->obj->create($info);
		if(empty($ret))
		{
			$this->errorOutput('数据更新有误！');
		}
		$this->addLogs('新增时移服务配置','',$ret,$ret['name']);
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		$id = intval($this->input['id'] ? $this->input['id'] : 0);
		$info = array(
			'name' 			=> trim($this->input['name']) ? trim($this->input['name']) : '',
			'host' 			=> trim($this->input['host']) ? trim($this->input['host']) : '',
			'port' 			=> intval($this->input['port']) ? intval($this->input['port']) : 0,
			'is_open' 		=> intval($this->input['is_open']) ? intval($this->input['is_open']) : 0,
			'update_time' 	=> TIMENOW,
		);
		if(empty($id))
		{
			$this->errorOutput('请传入更新的服务器ID');
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "time_shift_server WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			$this->errorOutput('此服务配置已不存在');
		}
		
		if(empty($info['name']))
		{
			$this->errorOutput('请传入服务器名');
		}
		
		if(empty($info['host']))
		{
			$this->errorOutput('请传入服务器host');
		}
		
		$time_shift_server = array(
			'protocol'	=> 'http://',
			'host' 		=> $info['host'],
			'port' 		=> $info['port']
		);
		$server_url = $time_shift_server['protocol'] . $time_shift_server['host'] . ($time_shift_server['port']? ':' . $time_shift_server['port']:'');
		if(!check_shift_server($server_url))
		{
			$this->errorOutput('连不上服务器，请检查主机与端口是否正确');
		}
		//此处更新时移服务器上面的目录配置
		$this->update_time_shift_server_dir($time_shift_server);
		$ret = $this->obj->update($id,$info);
		if(empty($ret))
		{
			$this->errorOutput('数据更新有误！');
		}
		$this->addLogs('更新时移服务配置',$f,$ret,$ret['name']);
		$this->addItem($ret);
		$this->output();
	}
	
	public function update_state()
	{
		$id = trim($this->input['id'] ? $this->input['id'] : '');
		$state = intval($this->input['is_open']) ? intval($this->input['is_open']) : 0;
		if(empty($id))
		{
			$this->errorOutput('请传入更新的服务器ID');
		}
		$ret = $this->obj->update_state($id,$state);
		if(empty($ret))
		{
			$this->errorOutput('数据更新有误！');
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		$id = intval($this->input['id'] ? $this->input['id'] : 0);
		if(empty($id))
		{
			$this->errorOutput('请传入更新的服务器ID');
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "time_shift_server WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			$this->errorOutput('此服务配置已不存在');
		}
		$ret = $this->obj->delete($id);
		$this->addLogs('删除时移服务器配置',$f,'',$f['name'].$id);
		$this->addItem(array('id' => $ret));
		$this->output();
	}

	private function update_time_shift_server_dir($time_shift_server)
	{
		//获取mediaserver里面的目录配置
		$config = $this->get_mediaserver_config();
		//获取文件目录的配置
		$get_data = array(
			'action'	=> 'GET_CONFIG',
		);
		//修改文件目录的配置
		$edit_data = array(
			'action' 						=> 'MODIFY_CONFIG',
			'default_timeshift_file_path' 	=> $config['default_timeshift_file_path'],
		);

		//先获取配置进行比对(如果相同就不需要更新)
		$ret_config = $this->media_server_operate($time_shift_server, $get_data);
		if ($ret_config['default_timeshift_file_path'] == $config['default_timeshift_file_path'])
		{
			return;
		}
		else
		{
			$ret = $this->media_server_operate($time_shift_server, $edit_data);
			if(!$ret['result'])
			{
				$this->errorOutput('更新服务器目录配置失败');
			}
		}
	}
	
	private function get_mediaserver_config()
	{
		//获取需要修改的配置
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'settings');
		$settings = $curl->request('configuare.php');
		$config = array(
			'default_timeshift_file_path' 	=> $settings['define']['UPLOAD_DIR'],
		);
		return $config;
	}

	private function media_server_operate($time_shift_server, $data = array())
	{
		$curl = new curl();
		$curl->setUrlHost($time_shift_server['host'] . ($time_shift_server['port']? ':' . $time_shift_server['port']:''));
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->setReturnFormat('json');
		foreach ($data AS $k => $v)
		{
			$curl->addRequestData($k, $v);
		}
		$ret = $curl->request('');
		return xml2Array($ret);
	}
}

$out = new serverUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>