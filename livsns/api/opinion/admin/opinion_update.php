<?php
require_once 'global.php';
require_once '../lib/opinion.class.php';
class opinionUpdateApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->opinion = new opinion();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function delete()
	{

	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
}

$ouput = new opinionUpdateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();

?>


			