<?php
define('MOD_UNIQUEID','mood');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/mood_mode.php');
require_once(ROOT_DIR . 'lib/class/material.class.php');
class mood_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new mood_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{}
	
	public function update()
	{}
	
	public function delete()
	{
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms(array('_action'=>'manage')); //判断是否有创建的权限
			/*********************************/
		}
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除样式',$ret,'','删除' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function audit()
	{}

	public function sort()
	{
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order('mood', 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}
	
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new mood_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>