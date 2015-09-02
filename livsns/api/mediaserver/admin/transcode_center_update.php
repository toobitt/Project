<?php
require_once('global.php');
define('MOD_UNIQUEID','mediaserver');//模块标识
require_once(CUR_CONF_PATH . 'lib/transcode.class.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class transcode_center_update extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function audit(){}
	public function sort(){}
	public function publish(){}
	
	public function create()
	{
		if(!$this->input['name'])
		{
			$this->errorOutput('转码服务器名称');
		}
		
		if(!$this->input['trans_host'] || !$this->input['trans_port'])
		{
			$this->errorOutput('host或者端口不能为空');
		}
		
		$task = $this->get_transcode_tasks(array('host' => $this->input['trans_host'],'port' => $this->input['trans_port']));
		if($task['return'] == 'fail')
		{
			$this->errorOutput('该转码服务器连不上');
		}

		//查询数据库里面有没有该转码服务器名称
		$sql = " SELECT id FROM ".DB_PREFIX."transcode_center WHERE name = '".$this->input['name']."'";
		$arr = $this->db->query_first($sql);
		if($arr['id'])
		{
			$this->errorOutput('该转码服务器已经存在');
		}

		$data = array(
			'name' 			=> $this->input['name'],
			'trans_host' 	=> $this->input['trans_host'],
			'trans_port' 	=> $this->input['trans_port'],
			'is_open'		=> intval($this->input['is_open']),
			'is_carry_file'	=> intval($this->input['is_carry_file']),
			'create_time' 	=> TIMENOW,
			'update_time'	=> TIMENOW,
		);
		$sql  = " INSERT INTO ".DB_PREFIX."transcode_center SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."transcode_center SET order_id = '".$vid."' WHERE id = '".$vid."'";
		$this->db->query($sql);
		$data['id'] = $vid;
		/**********************更新转码服务器上面的目录配置************/
		$this->hg_update_transcode_config();
		/********************************************************/
		$this->addLogs('创建转码服务器','',$data,$data['title']);
		$this->addItem($data);
		$this->output();
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		//先查询出原来的数据用于记录日志
		$sql = " SELECT * FROM " .DB_PREFIX. "transcode_center WHERE id = '" .$this->input['id']. "'";
		$pre_data = $this->db->query_first($sql);

		$fields = ' SET  ';
		if($this->input['name'])
		{
			$sql = " SELECT count(*) as total FROM ".DB_PREFIX."transcode_center WHERE name = '".$this->input['name']."' AND id != '".intval($this->input['id'])."'";
			$arr = $this->db->query_first($sql);
			if(intval($arr['total']) >= 1)
			{
				$this->errorOutput('转码服务器已经存在');
			}

			$fields .= '  name = \''.$this->input['name'].'\',';
		}
		else
		{
			$this->errorOutput('转码服务器名不能为空');
		}
		
		if(!$this->input['trans_host'] || !$this->input['trans_port'])
		{
			$this->errorOutput('host或者端口不能为空');
		}
		
		/*****此处用于判断是否要更新转码服务器上的目录配置，起到目录同步的作用********************/
		$task = $this->get_transcode_tasks(array('host' => $this->input['trans_host'],'port' => $this->input['trans_port']));
		if($task['return'] == 'fail')
		{
			$this->errorOutput('您提交的这台转码服务器连不上，不能更新！');
		}
		$state = $this->is_update_transcode_dir();
		if(!$state)
		{
			$this->errorOutput('您提交的这台转码服务器目录配置获取失败,暂时不能更新！');
		}
		/***************************************************************************/
		$fields .= '  is_carry_file = \''.intval($this->input['is_carry_file']).'\',';
		$fields .= '  is_open = \''.intval($this->input['is_open']).'\',';
		$fields .= '  trans_host = \''.$this->input['trans_host'].'\',';
		$fields .= '  trans_port = \'' .$this->input['trans_port'].'\',';
		$fields .= '  update_time = \''.TIMENOW.'\'';
		
	    $sql = "UPDATE ".DB_PREFIX.'transcode_center ' . $fields .'  WHERE  id = ' . intval($this->input['id']);
		$this->db->query($sql);
		
		//返回数据
		$sql = "SELECT * FROM ".DB_PREFIX."transcode_center WHERE id = '".intval($this->input['id'])."'";
		$ret = $this->db->query_first($sql);
		$this->addLogs('更新转码服务器', $pre_data,$ret,$ret['name']);
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = " SELECT * FROM " .DB_PREFIX. "transcode_center WHERE id IN (" .$this->input['id']. ")";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] = $r;
		}
		$sql = " DELETE FROM " .DB_PREFIX. "transcode_center WHERE id IN (".$this->input['id'].")";
		$this->db->query($sql);
		$this->addLogs('删除转码服务器', $pre_data,'',$pre_data['name']);
		$this->addItem('success');
		$this->output();
	}
	
	/************************************************以下是其他方法*******************************************************/
	
	//更新转码服务器配置
	private function update_transcode_config($t_server,$m_config)
	{
		$trans = new transcode($t_server);
		$ret = $trans->update_transcode_config($m_config);
		$ret = json_decode($ret,1);
		return $ret;
	}
	//更新转码服务器配置
	public function hg_update_transcode_config()
	{
		//转码转码服务器
		$t_server = array(
			'host' => $this->input['trans_host'],
			'port' => $this->input['trans_port'],
		);
		
		//获取需要修改的配置
		$m_config = array(
			'source_path' => '/',
			'target_path' => '/',
		);
		$t_ret = $this->update_transcode_config($t_server,$m_config);
	}
		
	//通过获取当前正在转码的个数来判断能不能连通转码服务器
	public function get_transcode_tasks($arr)
	{
		$trans = new transcode($arr);
		$ret = $trans->get_transcode_tasks();
		$ret = json_decode($ret,1);
		return $ret;
	}
	
	//是否更新转码服务器上的目录配置
	public function is_update_transcode_dir()
	{
		$m_config = array(
			'source_path' => '/',
			'target_path' => '/',
		);
		//获取转码服务器上的配置
		$t_server = array(
			'host' => $this->input['trans_host'],
			'port' => $this->input['trans_port'],
		);
		$transcode_config = $this->get_transcode_config($t_server);
		//如果没有获取配置直接报错
		if(!is_array($transcode_config) || !$transcode_config)
		{
			return false;
		}
		if($m_config['source_path'] != $transcode_config['default_transcode_file_source_path'] || $m_config['target_path'] != $transcode_config['default_transcode_file_destination_path'])
		{
			$this->update_transcode_config($t_server,$m_config);
		}
		return true;
	}
	
	//获取转码服务器配置
	private function get_transcode_config($arr)
	{
		$trans = new transcode($arr);
		$ret = $trans->get_transcode_config();
		$ret = json_decode($ret,1);
		return $ret;
	}
	
	//设置是否启用某台服务器
	public function set_transcode_server_state()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = " SELECT * FROM " . DB_PREFIX . "transcode_center WHERE id = '" .$this->input['id']. "'";
		$server = $this->db->query_first($sql);
		if($server)
		{
			$is_open = $server['is_open']?0:1;
			$sql = " UPDATE " .DB_PREFIX. "transcode_center SET is_open = '" .$is_open. "' WHERE id = '" .$server['id']. "'";
			$this->db->query($sql);
		}
		$this->addItem('success');
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput(NOFUC);
	}
}

$out = new transcode_center_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>