<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: auth.php 6701 2012-05-14 07:49:07Z zhoujiafei $
***************************************************************************/

require_once('global.php');
class server_update extends adminUpdateBase
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
		if(!$this->input['name'])
		{
			$this->errorOutput('没有服务器');
		}

		if(!$this->input['uniqueid'])
		{
			$this->errorOutput('没有标识');
		}

		//查询数据库里面有没有该服务器
		$sql = " SELECT id FROM ".DB_PREFIX."server WHERE name = '".urldecode($this->input['name'])."'";
		$arr = $this->db->query_first($sql);
		if($arr['id'])
		{
			$this->errorOutput('该服务器已经存在');
		}

		$sql = " SELECT id FROM ".DB_PREFIX."server WHERE uniqueid = '" . urldecode($this->input['uniqueid'])."'";
		$arr = $this->db->query_first($sql);
		if($arr['id'])
		{
			$this->errorOutput('该服务器标识已存在');
		}
		
		$ip = urldecode($this->input['ip']);
		if(!hg_checkip ($ip) && $ip)
		{
			$this->errorOutput('ip有误');
		}
		
		$outside_ip = urldecode($this->input['outside_ip']);
		if(!hg_checkip ($outside_ip) && $outside_ip)
		{
			$this->errorOutput('外网ip有误');
		}

		$data = array(
		 	'name' 			=> urldecode($this->input['name']),
			'uniqueid' 		=> urldecode($this->input['uniqueid']),
			'ip' 			=> $ip,
			'outside_ip' 	=> $outside_ip,
			'port' 			=> intval($this->input['port']),
			'user' 			=> urldecode($this->input['user']),
			'password' 		=> hg_encript_str(urldecode($this->input['password'])),
			'iscur' 		=> $this->input['iscur']?1:0,
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
		);
		
		$sql = " INSERT INTO ".DB_PREFIX."server SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."server SET order_id = '".$vid."' WHERE id = '".$vid."'";
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

		$fields = ' SET  ';

		if($this->input['name'])
		{
			$sql = " SELECT count(*) as total FROM ".DB_PREFIX."server WHERE name = '".urldecode($this->input['name'])."' AND id != '".intval($this->input['id'])."'";
			$arr = $this->db->query_first($sql);
			if(intval($arr['total']) >= 1)
			{
				$this->errorOutput('服务器已经存在');
			}

			$fields .= '  name = \''.urldecode($this->input['name']).'\',';
		}
		else
		{
			$this->errorOutput('服务器名称不能为空');
		}

		if($this->input['uniqueid'])
		{
			$sql = " SELECT count(*) as total FROM ".DB_PREFIX."server WHERE uniqueid = '".urldecode($this->input['uniqueid'])."' AND id != '".intval($this->input['id'])."'";
			$arr = $this->db->query_first($sql);
			if(intval($arr['total']) >= 1)
			{
				$this->errorOutput('标识已经存在');
			}

			$fields .= '  uniqueid = \''.urldecode($this->input['uniqueid']).'\',';
		}
		else
		{
			$this->errorOutput('标识不能为空');
		}

		if($this->input['ip'])
		{
			if(!hg_checkip (urldecode($this->input['ip'])))
			{
				$this->errorOutput('ip有误');
			}
			$fields .= '  ip = \''.urldecode($this->input['ip']).'\',';
		}
		
		if($this->input['outside_ip'])
		{
			if(!hg_checkip (urldecode($this->input['outside_ip'])))
			{
				$this->errorOutput('外网ip有误');
			}
			$fields .= '  outside_ip = \''.urldecode($this->input['outside_ip']).'\',';
		}
		
		if($this->input['port'])
		{
			$fields .= '  port = \''.intval($this->input['port']).'\',';
		}
		
		if($this->input['user'])
		{
			$fields .= '  user = \''.urldecode($this->input['user']).'\',';
		}
		
		if($this->input['password'])
		{
			$fields .= '  password = \''.hg_encript_str(urldecode($this->input['password'])).'\',';
		}
		
		if($this->input['iscur'])
		{
			$fields .= '  iscur = 1,';
		}
		else 
		{
			$fields .= '  iscur = 0,';
		}

		$fields .= '  update_time = \''.TIMENOW.'\'';

	    $sql = "UPDATE ".DB_PREFIX.'server ' . $fields .'  WHERE  id = ' . intval($this->input['id']);
		$this->db->query($sql);

		//返回数据
		$sql = "SELECT * FROM ".DB_PREFIX."server WHERE id = '".intval($this->input['id'])."'";
		$ret = $this->db->query_first($sql);
		$ret['password'] = hg_encript_str($ret['password'],0);
		$this->addItem($ret);
		$this->output();
	}

	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}

		$sql = " DELETE FROM " .DB_PREFIX. "server WHERE id IN (".urldecode($this->input['id']).")";
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$out = new server_update();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>