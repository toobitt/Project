<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/interview_old.class.php';
define('MOD_UNIQUEID','interview_old');//模块标识
class interview_old extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->int = new interviewInfo_old();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$id = $this->input['interview_id'];
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->int->detail($id);
		$this->addItem($data);
		$this->output();
	}
	
	public function count()
	{
		
	}
	
	public function detail()
	{
		
	}
}
$out = new interview_old();
$action = $_INPUT['a'];
if(!$_INPUT['a'])
{
	$action = 'show';
}
$out->$action();