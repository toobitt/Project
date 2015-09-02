<?php
define('SCRIPT_NAME', 'AddAppUpdate');
define('MOD_UNIQUEID','add_app');
require_once('./global.php');
class AddAppUpdate extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function sort()
	{
	}
	function audit()
	{
	}
	function publish()
	{
	}
	
	function create()
	{
		$app_key = $this->input['app_key'];
		if(!$app_key)
		{
			$this->errorOutput('appkey不存在');
		}
		if(!$this->input['master_secret'])
		{
			$this->errorOutput('master_secret不存在');
		}
		$info['name'] = $this->input['name'];
		$info['app_key'] = $this->input['app_key'];
		$info['master_secret'] = $this->input['master_secret'];
		$sql = 'SELECT * FROM ' . DB_PREFIX .'app_info WHERE  app_key="'.$app_key.'"';
		if($this->db->query_first($sql))
		{
			$this->errorOutput('appkey已经存在');
		}
		else 
		{
			$sql = "INSERT INTO ".DB_PREFIX."app_info SET ";
			foreach($info as $k=>$v)
			{
				$sql .= "{$k} = '".$v."',";
			}
			$sql = rtrim($sql, ',');
		}
		
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('id不存在');
		}
		$app_key = $this->input['app_key'];
		if(!$app_key)
		{
			$this->errorOutput('appkey不存在');
		}
		if(!$this->input['master_secret'])
		{
			$this->errorOutput('master_secret不存在');
		}
		$info['name'] = $this->input['name'];
		$info['app_key'] = $app_key;
		$info['master_secret'] = $this->input['master_secret'];
		$sql = 'SELECT * FROM ' . DB_PREFIX .'app_info WHERE  app_key="'.$app_key.'" AND id !='.$id;
		if($this->db->query_first($sql))
		{
			$this->errorOutput('appkey已经存在');
		}
		$sql = "UPDATE ".DB_PREFIX."app_info SET ";
		foreach($info as $k=>$v)
		{
			$sql .= "{$k} = '".$v."',";
		}
		$sql = rtrim($sql, ',');
		$sql .= " WHERE id=".$id;
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	function  delete()
	{
		$ids = trim(urldecode($this->input['id']));
		if(!$this->input['id'])
		{
			$this->errorOutput(NOAPPID);
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'app_info WHERE id in('.$ids.')';
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	function unknown()
	{
		$this->erroroutput(NO_METHOD);
	}
	
}
include(ROOT_PATH . 'excute.php');