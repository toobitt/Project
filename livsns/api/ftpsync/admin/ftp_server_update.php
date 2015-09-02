<?php
require('./global.php');
define('MOD_UNIQUEID', 'ftpserver');
class ftpserver extends adminBase
{
	var $filed;
	var $data = array();
	function __construct()
	{
		parent::__construct();
		$this->verify_content_prms(array('_action'=>'manage'));
		//表字段
		$this->filed = array(
		'id'=>false,
		'hostname'=>true,
		'user'=>true,
		'pass'=>true,
		'port'=>false,
		);
		$this->initdata();
	}
	protected function initdata()
	{
		if($this->input['a'] == 'update')
		{
			foreach($this->filed as $field=>$must)
			{
				if($must && !$this->input[$field])
				{
					$this->errorOutput("请检查必填项是否都已全部正确输入！".$field);
				}
				$this->data[$field] = $this->input[$field];
			}
			$this->data['pass'] = hg_encript_str($this->data['pass']);
			$this->autofill();
		}
	}
	protected function autofill()
	{
		if(!$this->data['id'])
		{
			$this->data['user_id'] = $this->user['user_id'];
			$this->data['user_name'] = $this->user['user_name'];
			//$this->data['pass'] = hg_encript_str($this->data['pass']);
			$this->data['create_time'] = TIMENOW;
		}
		else
		{
			$this->data['update_user_id'] = $this->user['user_id'];
			$this->data['update_user_name'] = $this->user['user_name'];
			
			$this->data['update_time'] = TIMENOW;
		}
	}
	function __destruct()
	{
		parent::__destruct();
	}
	protected function create()
	{
		$sql = 'INSERT INTO ' . DB_PREFIX . 'ftpserver('.implode(',', array_keys($this->data)).')';
		$sql .= ' VALUES("'.implode('","', $this->data).'")';
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$this->data['id'] = $id;
		$this->addItem($this->data);
		$this->output();
	}
	function unknown()
	{
		//
	}
	function update()
	{
		if(!$this->data['id'])
		{
			$this->create();
		}
		$id = $this->data['id'];
		unset($this->data['id']);
		$sql = 'UPDATE ' . DB_PREFIX . 'ftpserver SET ';
		foreach ($this->data as $field=>$value)
		{
			$sql .= $field . '="' . $value . '",';
		}
		$sql = rtrim($sql, ',');
		$where = ' WHERE id = '.$id;
		$this->db->query($sql . $where);
		$this->data['id'] = $id;
		$this->addItem($this->data);
		$this->output();
	}
	function delete()
	{
		$id = intval($this->input['id']);
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'ftpserver WHERE id =  '. $id;
		$server = $this->db->query_first($sql);
		if(!$server)
		{
			$this->errorOutput('服务器信息不存在或已被删除');
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'ftpsync WHERE server_id = '.$server['id'];
		$sync_ex = $this->db->query_first($sql);
		if($sync_ex)
		{
			$this->errorOutput('该服务器正在被使用无法删除');
		}
		$sql = 'DELETE FROM  ' . DB_PREFIX . 'ftpserver WHERE id = '.$id;
		$this->db->query($sql);
		$this->addItem($server);
		$this->output();
	}
}
$o = new ftpserver();
$action = $_INPUT['a'];
$action = $action && method_exists($o, $action) ? $action : 'unknown';
$o->$action();
?>