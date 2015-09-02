<?php
require_once('./global.php');
require_once(CUR_CONF_PATH.'lib/archive_content.class.php');
define('MOD_UNIQUEID','archive_content');//模块标识
class archive_content_update extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->archiveContent = new archiveContent();
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
		$archive_id = intval($this->input['archive_id']);
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->archiveContent->delete($ids, $archive_id);
		$this->addItem($ids);
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
	
	public function recover_content()
	{
		$ids = $this->input['id'];
		$archive_id = intval($this->input['archive_id']);
		if (!$ids || !$archive_id)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->archiveContent->recover_content($ids, $archive_id);
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
$ouput= new archive_content_update();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();