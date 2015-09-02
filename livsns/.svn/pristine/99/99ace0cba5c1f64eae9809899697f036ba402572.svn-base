<?php
require_once('./global.php');
class version_update extends adminUpdateBase
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
	
	public function create()
	{
		
	}
	
	public function sort()
	{
		
	}
	
	public function publish()
	{
		
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}

		$fields = ' SET  ';
		if($this->input['version_name'])
		{
			$fields .= '  version_name = \''.$this->input['version_name'].'\',';
		}
		
		if($this->input['content'])
		{
			$fields .= '  content = \''.$this->input['content'].'\',';
		}
		
		$fields .= '  update_time = \''.TIMENOW.'\'';
	    $sql = "UPDATE ".DB_PREFIX.'version ' . $fields .'  WHERE  id = ' . intval($this->input['id']);
		$this->db->query($sql);
		//返回数据
		$ret['id'] = $this->input['id'];
		$ret['version_name'] = $this->input['version_name'];
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput();
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'version where id in('.$this->input['id'].')';
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	public function save_diff()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);	
		}
		
		$ids = explode(',',$this->input['id']);
		$sql = " SELECT * FROM " .DB_PREFIX. "version WHERE id = '" .$ids[0]. "'";
		$vinfo = $this->db->query_first($sql);
		if(!$vinfo['app_id'])
		{
			$this->errorOutput('传入的id有误');	
		}
		$sql = "SELECT * FROM " .DB_PREFIX. "version_diff WHERE app_id = '" .$vinfo['app_id']. "' AND version_id = '" .$ids[0]. "_" .$ids[1] . "'";
		$diff = $this->db->query_first($sql);
		if($diff['id'])
		{
			$sql = " UPDATE " .DB_PREFIX. "version_diff SET diff_content = '" .$this->input['diff_content']. "',update_time = '".TIMENOW."' WHERE id = {$diff['id']}";
		}
		else 
		{
			$sql = "INSERT INTO " .DB_PREFIX. "version_diff SET ";
			$data = array(
				'diff_content'	=> $this->input['diff_content'],
				'version_id'	=> $ids[0] .'_'.$ids[1],
				'app_id'		=> $vinfo['app_id'],
				'create_time'	=> TIMENOW,
				'update_time'	=> TIMENOW,
			);
			foreach($data AS $k => $v)
			{
				$sql .= " {$k} = '{$v}',";
			}
			$sql = rtrim($sql,',');
		}
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
$object = new version_update();
if(!method_exists($object, $action))
{
	$action = 'unknow';
}
$object->$action();