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
		$name = $this->input['name'];
		if(!$name)
		{
			$this->errorOutput('请填写应用名称');
		}
		
		$platform_type = intval($this->input['platfrom_type']);
		if(!$platform_type)
		{
			$this->errorOutput('请选择平台类型');
		}
		
		//极光不用填写app_id
		if($platform_type != 2)
		{
			$access_id = $this->input['access_id'];
			if(!$access_id)
			{
				$this->errorOutput('请填写AppId');
			}
		}
		
		$app_key = $this->input['access_key'];
		if(!$app_key)
		{
			$this->errorOutput('appkey不存在');
		}
		
		if(!$this->input['secret_key'])
		{
			$this->errorOutput('SecretKey不存在');
		}
		
		//avos必填
		if($platform_type == 3 && !$this->input['channel'])
		{
			$this->errorOutput('请填写订阅频道');
		}
		
		$info['name'] 				= $name;
		$info['access_key'] 		= $app_key;
		$info['platform_type'] 		= $platform_type;
		$info['secret_key'] 		= $this->input['secret_key'];
		$info['channel']			= $this->input['channel'];
		$info['action']				= $this->input['action'];
		$info['packagename']		= $this->input['packagename'];
		
		if($access_id)
		{
			$info['access_id'] 		= $access_id;
		}
		
		$sql = 'SELECT * FROM ' . DB_PREFIX .'app_info WHERE  access_key="'.$app_key.'" AND platform_type = ' . $platform_type;
		if($this->db->query_first($sql))
		{
			$this->errorOutput('AppKey已经存在');
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
		
		$name = $this->input['name'];
		if(!$name)
		{
			$this->errorOutput('请填写应用名称');
		}
		
		$platform_type = intval($this->input['platfrom_type']);
		if(!$platform_type)
		{
			$this->errorOutput('请选择平台类型');
		}
		
		//极光不用填写app_id
		if($platform_type != 2)
		{
			$access_id = $this->input['access_id'];
			if(!$access_id)
			{
				$this->errorOutput('请填写AppId');
			}
		}
		
		$app_key = $this->input['access_key'];
		if(!$app_key)
		{
			$this->errorOutput('appkey不存在');
		}
		
		if(!$this->input['secret_key'])
		{
			$this->errorOutput('SecretKey不存在');
		}
		
		//avos必填
		if($platform_type == 3 && !$this->input['channel'])
		{
			$this->errorOutput('请填写订阅频道');
		}
		$info['name'] 				= $name;
		$info['access_key'] 		= $app_key;
		$info['platform_type'] 		= $platform_type;
		$info['secret_key'] 		= $this->input['secret_key'];
		$info['channel']			= $this->input['channel'];
		$info['action']				= $this->input['action'];
		$info['packagename']		= $this->input['packagename'];
		
		if($access_id)
		{
			$info['access_id'] 		= $access_id;
		}
		
		$sql = 'SELECT * FROM ' . DB_PREFIX .'app_info WHERE  access_key="'.$app_key.'" AND platform_type = ' . $platform_type . ' AND id != ' . $id;
		if($this->db->query_first($sql))
		{
			$this->errorOutput('AppKey已经存在');
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
		$sql = 'DELETE FROM '.DB_PREFIX.'app_info WHERE id IN ('.$ids.')';
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