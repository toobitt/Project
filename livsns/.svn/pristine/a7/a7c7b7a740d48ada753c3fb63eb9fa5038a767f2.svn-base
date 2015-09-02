<?php
require_once('./global.php');
class appstore_update extends adminUpdateBase
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
		//首先查看应用商店有没有此应用,如果存在只增加版本
		$sql = " SELECT * FROM " .DB_PREFIX. "apps WHERE uniqueid = '" .trim($this->input['softvar']). "'";
		$app = $this->db->query_first($sql);
		if(!$app['id'])
		{
			$data = array(
				'name'			=>trim($this->input['name']),
				'uniqueid'		=>trim($this->input['softvar']),
				'brief'			=>trim($this->input['brief']),
				'create_time'	=>TIMENOW,
				'update_time'	=>TIMENOW,
			);
			$sql = " INSERT INTO ".DB_PREFIX."apps set ";
			foreach($data as $k => $v)
			{
				$sql .= "{$k} = '{$v}',";
			}
			$sql = trim($sql, ',');
			$this->db->query($sql);
			$vid = $this->db->insert_id();
			$sql = "UPDATE " .DB_PREFIX. "apps SET order_id = {$vid} WHERE id = {$vid}";
			$this->db->query($sql);
			$app['id'] = $vid;
		}
		
		//新增一条版本
		$version_data = array(
				'app_id'		=>$app['id'],
				'content'		=>$this->input['content'],
				'version_name'	=>trim($this->input['name']) . $this->input['version'] . '_' . date('Y/m/d',TIMENOW),
				'create_time'	=>TIMENOW,
				'update_time'	=>TIMENOW,
		);
		$sql = " INSERT INTO " .DB_PREFIX. "version SET ";
		foreach($version_data as $k => $v)
		{
			$sql .= "{$k} = '{$v}',";
		}
		$sql = trim($sql, ',');
		$this->db->query($sql);
		
		//查询出该应用的版本是否超过5条，超过就删掉最老的版本，始终控制版本在5条以内
		/*
		$sql = "SELECT count(*) AS num FROM " .DB_PREFIX. "version WHERE app_id = '" .$app['id']. "'";
		$arr = $this->db->query_first($sql);
		if($arr['num'] > 5)
		{
			$sql  = " SELECT * FROM " .DB_PREFIX. "version WHERE app_id = '" .$app['id']. "' ORDER BY create_time ASC ";
			$arr2 = $this->db->query_first($sql);
			$sql  = " DELETE FROM " .DB_PREFIX. "version WHERE id = '" .$arr2['id']. "'";
			$this->db->query($sql);
		}
		*/
		$this->addItem('success');
		$this->output();
	}
	
	public function update()
	{
		
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput();
		}
		$sql = ' DELETE FROM '.DB_PREFIX.'apps WHERE id IN ('.$this->input['id'].')';
		$this->db->query($sql);
		//再删除与该应用对应的版本
		$sql = ' DELETE FROM ' .DB_PREFIX. 'version WHERE app_id IN (' .$this->input['id']. ')';
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$action = $_INPUT['a'];
$object = new appstore_update();
if(!method_exists($object, $action))
{
	$action = 'unknow';
}
$object->$action();
