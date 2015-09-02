<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 4234 2011-07-28 05:14:16Z repheal $
***************************************************************************/
require('global.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
define('MOD_UNIQUEID','transcode_config');//模块标识
class vod_config_type_update extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function audit(){}
	function sort(){}
	function publish(){}

	public function  create()
	{
		$name = trim($this->input['name']);
		if(!$name)
		{
			$this->errorOutput('请填写名称');
		} 
		//查询名称是否已经存在
		$sql = "SELECT * FROM " .DB_PREFIX. "vod_config_type WHERE name = '" .$name. "'";
		$udata = $this->db->query_first($sql);
		if($udata)
		{
			$this->errorOutput('该名称已经存在，请换一个');
		}
		if(intval($this->input['is_default']))
	    {
	    	$this->reset_default_config();
	    }
		$sql = "INSERT INTO ".DB_PREFIX."vod_config_type  SET name='".$name."',is_default=".intval($this->input['is_default']).",user_name='".$this->user['user_name']."',create_time=".TIMENOW;
		$this->db->query($sql);
	    $vid = $this->db->insert_id();
	    if($vid)
	    {
	    		$return['name'] = $name;
	    }
	    //$this->addLogs('创建转码配置分类','',$return,$return['name']);
		$this->addItem($return);
		$this->output();
	}
	protected function reset_default_config()
	{
		$sql = 'UPDATE ' . DB_PREFIX . 'vod_config_type SET is_default=0 WHERE is_default=1';
		$this->db->query($sql);
	}
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$name = trim($this->input['name']);
		if(!$name)
		{
			$this->errorOutput('请填写名称');
		}
		//查询名称是否已经存在
		$sql = "SELECT * FROM " .DB_PREFIX. "vod_config_type WHERE name = '" .$name. "' AND id != '" .$this->input['id']. "'";
		$udata = $this->db->query_first($sql);
		if($udata)
		{
			$this->errorOutput('该名称已经存在，请换一个');
		}

	    //获取原来的数据
	    $sql =  "SELECT * FROM " . DB_PREFIX . "vod_config_type WHERE id = " . intval($this->input['id']) ;
		$pre_data = $this->db->query_first($sql);
		
		if(intval($this->input['is_default']))
	    {
	    	$this->reset_default_config();
	    }
	    elseif($pre_data['is_default'])
	    {
	    	$this->errorOutput("无法取消默认配置");
	    }
		$sql = "UPDATE ".DB_PREFIX."vod_config_type  SET name='".$name."', is_default=".intval($this->input['is_default'])." WHERE id=".$this->input['id'];
	    $this->db->query($sql);
		
		//返回数据
	    $sql = "SELECT * FROM ".DB_PREFIX."vod_config_type WHERE id = '".intval($this->input['id'])."'";
	    $return = $this->db->query_first($sql);
	    //记录日志
	    //$this->addLogs('更新转码配置', $pre_data, $return,$return['name']);
	    $this->addItem($return);
	    $this->output();
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql =  "SELECT * FROM " . DB_PREFIX . "vod_config_type WHERE id IN (" . $this->input['id'] . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while($row = $this->db->fetch_array($q))
		{
			$pre_data[] = $row;
		}
		//查看类别下面是否有数据
		$sql = "SELECT * FROM " . DB_PREFIX . "vod_config WHERE type_id IN (" . $this->input['id'] . ")";
		$re = $this->db->query($sql);
		while($row = $this->db->fetch_array($re))
		{
			$check_data[] = $row;
		}
		if($check_data)
		{
			$this->errorOutput('分类下面还有数据,不能删除');
		}
		$sql = "DELETE FROM ".DB_PREFIX."vod_config_type  WHERE  1  AND  id  in (".$this->input['id'].")";
		$this->db->query($sql);
		//记录日志
		//$this->addLogs('删除转码配置', $pre_data, '','删除转码配置' . $this->input['id']);
		$this->addItem($pre_data);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}

$out = new vod_config_type_update();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>