<?php
require_once('global.php');
class  services_update extends adminUpdateBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
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
	
	public function create()
	{
		if(!$this->input['server_id'])
		{
			$this->errorOutput(NOID);
		}
		
		if($this->input['name'])
		{
			$sql = " SELECT count(*) as total FROM ".DB_PREFIX."services WHERE name = '".urldecode($this->input['name'])."' AND server_id = '" .intval($this->input['server_id'])."'";
			$arr = $this->db->query_first($sql);
			if(intval($arr['total']) >= 1)
			{
				$this->errorOutput('服务已经存在,请换个名字');
			}

			$fields .= '  name = \''.urldecode($this->input['name']).'\',';
		}
		else
		{
			$this->errorOutput('服务名称不能为空');
		}
		
		$conf = array();
		$conf_name = $this->input['conf_name'];
		$conf_path = $this->input['conf_path'];
		foreach($conf_name AS $k => $v)
		{
			$conf[] = array('name' => urldecode($v),'path' => urldecode($conf_path[$k]));
		}

		$data = array(
		 	'name' 			=> urldecode($this->input['name']),
			'server_id'		=> intval($this->input['server_id']),
			'start_cmd'		=> urldecode($this->input['start_cmd']),
			'stop_cmd'		=> urldecode($this->input['stop_cmd']),
			'restart_cmd'	=> urldecode($this->input['restart_cmd']),
		    'conf'			=> serialize($conf),
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
		);
		$sql = " INSERT INTO ".DB_PREFIX."services SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."services SET order_id = '".$vid."' WHERE id = '".$vid."'";
		$this->db->query($sql);
		//把值返回
		$data['id'] = $vid;
		$this->addItem($data);
		$this->output();
	}

	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		if(!$this->input['server_id'])
		{
			$this->errorOutput(NOID);
		}

		$fields = ' SET  ';
		if($this->input['name'])
		{
			$sql = " SELECT count(*) as total FROM ".DB_PREFIX."services WHERE name = '".urldecode($this->input['name'])."' AND id != '".intval($this->input['id'])."' AND server_id = '" .intval($this->input['server_id'])."'";
			$arr = $this->db->query_first($sql);
			if(intval($arr['total']) >= 1)
			{
				$this->errorOutput('服务已经存在,请换个名字');
			}

			$fields .= '  name = \''.urldecode($this->input['name']).'\',';
		}
		else
		{
			$this->errorOutput('服务名称不能为空');
		}
		
		$conf = array();
		$conf_name = $this->input['conf_name'];
		$conf_path = $this->input['conf_path'];
		foreach($conf_name AS $k => $v)
		{
			$conf[] = array('name' => urldecode($v),'path' => urldecode($conf_path[$k]));
		}

		$fields .= '  conf      = \''.serialize($conf).'\',';
		$fields .= '  start_cmd = \''.urldecode($this->input['start_cmd']).'\',';
		$fields .= '  stop_cmd  = \''.urldecode($this->input['stop_cmd']).'\',';
		$fields .= '  restart_cmd   = \''.urldecode($this->input['restart_cmd']).'\',';
		$fields .= '  update_time = \''.TIMENOW.'\'';
	    $sql = "UPDATE ".DB_PREFIX.'services ' . $fields .'  WHERE  id = ' . intval($this->input['id']);
		$this->db->query($sql);
		//返回数据
		$sql = "SELECT * FROM ".DB_PREFIX."services WHERE id = '".intval($this->input['id'])."'";
		$ret = $this->db->query_first($sql);
		$this->addItem($ret);
		$this->output();
	}

	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}

		$sql = " DELETE FROM " .DB_PREFIX. "services WHERE id IN (".urldecode($this->input['id']).")";
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}

	public function save_service()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "DELETE FROM " .DB_PREFIX. "services WHERE server_id = '" .intval($this->input['id']). "'";
		$this->db->query($sql);
		
		$name 		= $this->input['name'];
		$conf_path 	= $this->input['conf_path'];
		
		foreach($name AS $k => $v)
		{
			$sql  = " INSERT INTO " .DB_PREFIX. "services SET ";
			$sql .= " name 		= '" .$v. "',".
					" conf_path = '" .urldecode($conf_path[$k]). "',".
					" server_id = '" .intval($this->input['id']). "'";
			$this->db->query($sql);
		}
		$this->addItem('success');
		$this->output();
	}
	
	//保存服务器配置
	public function save_config()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = " SELECT * FROM " .DB_PREFIX. "server WHERE id = '" .intval($this->input['id']). "'";
		$server = $this->db->query_first($sql);
		$cmd = array(
			'action' => 'write2file',
			'para' => urldecode($this->input['config_path']),
			'data' => urldecode($this->input['service_content']),
			'user' => $server['user'],
			'pass' => hg_encript_str($server['password'],0),
			'charset' => 'utf8',
		);
		$sock = new hgSocket();
		if(!($status = $sock->connect($server['ip'], $server['port'])))
		{
			$this->errorOutput('未连接上服务器，请检查python有没有启动');
		}
		$sock->sendCmd($cmd);
		$this->addItem('success');
		$this->output();
	}
	
	//执行启动或停止命令
	public function exec_cmd()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		if(!$this->input['serverid'])
		{
			$this->errorOutput(NOID);
		}
		
		$cmd = array('stop','start','restart');
		$cmd_field = array('stop_cmd','start_cmd','restart_cmd');
		$sql = "SELECT * FROM " .DB_PREFIX. "services WHERE id = '" .intval($this->input['id']). "'";
		$arr = $this->db->query_first($sql);
		$sql = " SELECT * FROM " .DB_PREFIX. "server WHERE id = '" .intval($this->input['serverid']). "'";
		$server = $this->db->query_first($sql);
		
		$cmd = array(
			'action' => $cmd[intval($this->input['cmd'])],
			'para' => $arr[$cmd_field[$this->input['cmd']]],
			'user' => $server['user'],
			'pass' => hg_encript_str($server['password'],0),
			'charset' => 'utf8',
		);
		$sock = new hgSocket();
		if(!($status = $sock->connect($server['ip'], $server['port'])))
		{
			$this->errorOutput('未连接上服务器，请检查python有没有启动');
		}
		$sock->sendCmd($cmd);
		$this->addItem('success');
		$this->output();
	}
}

$out = new services_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'known';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>