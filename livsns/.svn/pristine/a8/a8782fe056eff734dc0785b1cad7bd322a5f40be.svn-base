<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: auth.php 6701 2012-05-14 07:49:07Z zhoujiafei $
***************************************************************************/

require_once('global.php');
class manger_update extends adminUpdateBase
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
			$this->errorOutput(NONAME);
		}
		
		if($this->input['server_id'] == -1)
		{
			$this->errorOutput(NOSERVER);
		}
		
		if(!$this->input['type'])
		{
			$this->errorOutput(NOTYPE);
		}
		
		if(intval($this->input['type']) == 1)
		{
			$sql = "SELECT * FROM " .DB_PREFIX."appmanger WHERE type = 1 AND id != '" .intval($this->input['id']). "'";
			$arr = $this->db->query_first($sql);
			if($arr['id'])
			{
				$this->errorOutput(MAINEXISTS);
			}
		}

		if(!$this->input['install_dir'])
		{
			$this->errorOutput(INSTALL_DIR_EMPTY);
		}

		$data = array(
		 	'name' 			=> $this->input['name'],
		 	'server_id' 	=> intval($this->input['server_id']),
		 	'install_dir' 	=> $this->input['install_dir'],
		 	'type' 			=> intval($this->input['type']),
		 	'dns' 			=> $this->input['dns'],
		 	'version' 		=> $this->input['version'],
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
		);
		$sql = " INSERT INTO ".DB_PREFIX."appmanger SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."appmanger SET order_id = '".$vid."' WHERE id = '".$vid."'";
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
			$fields .= '  name = \''.urldecode($this->input['name']).'\',';
		}
		
		if($this->input['type'])
		{
			$sql = "SELECT * FROM " .DB_PREFIX."appmanger WHERE id = '" .intval($this->input['id']). "'";
			$arr = $this->db->query_first($sql);
			if($arr['type'] == 2)
			{
				$sql = "SELECT * FROM " .DB_PREFIX."appmanger WHERE type = 1 ";
				$arr2 = $this->db->query_first($sql);
				if($arr2['id'] && intval($this->input['type']) == 1)
				{
					$this->errorOutput(MAINEXISTS);
				}
			}
			$fields .= '  type = \''.intval($this->input['type']).'\',';
		}
		
		if($this->input['install_dir'])
		{
			$fields .= '  install_dir = \''.$this->input['install_dir'].'\',';
		}
		else 
		{
			$this->errorOutput(INSTALL_DIR_EMPTY);
		}
		
		if($this->input['dns'])
		{
			$fields .= '  dns = \''.$this->input['dns'].'\',';
		}
		
		if($this->input['version'])
		{
			$fields .= '  version = \''.$this->input['version'].'\',';
		}
		
		if($this->input['server_id'])
		{
			$fields .= '  server_id = \''.$this->input['server_id'].'\',';
		}

		$fields .= '  update_time = \''.TIMENOW.'\'';
	    $sql = "UPDATE ".DB_PREFIX.'appmanger ' . $fields .'  WHERE  id = ' . intval($this->input['id']);
		$this->db->query($sql);
		//返回数据
		$sql = "SELECT a.*,s.name AS server_name FROM ".DB_PREFIX."appmanger a LEFT JOIN ".DB_PREFIX."server s ON s.id = a.server_id  WHERE a.id = '" .intval($this->input['id'])."'";
		$ret = $this->db->query_first($sql);
		$ret['type'] = $this->settings['program_type'][$ret['type']];
		$this->addItem($ret);
		$this->output();
	}

	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = " DELETE FROM " .DB_PREFIX. "appmanger WHERE id IN (".urldecode($this->input['id']).")";
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$out = new manger_update();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>