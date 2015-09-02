<?php
/*
 * 嘉宾头像碰撞之后的回调，用于更新数据库已使用碰撞的字段
 **/
define('MOD_UNIQUEID','collision_callback');
define('SCRIPT_NAME', 'collision_callback');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
class collision_callback extends outerUpdateBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new member_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	public function update(){}
	public function delete(){}
	
	public function run()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$this->mode->set_collision_use($this->input['id']);
		$this->addItem(array('return' => 1));
		$this->output();
	}
}

$out = new collision_callback();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'run';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 