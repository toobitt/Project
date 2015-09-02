<?php
require_once('./global.php');
require_once(CUR_CONF_PATH.'lib/archive.class.php');
define('MOD_UNIQUEID','archive');//模块标识
class archive_update extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->archive = new archive();
	}
	
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function update()
	{
	
	}
	
	public function delete()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->archive->delete($ids);
		$this->addItem($data);
		$this->output();
	}
	
	public function create()
	{
	
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
	
	public function recover_archive()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->archive->recover_archive($ids);
		if (!$ret)
		{
			$this->errorOutput('还原失败');
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$ouput= new archive_update();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();