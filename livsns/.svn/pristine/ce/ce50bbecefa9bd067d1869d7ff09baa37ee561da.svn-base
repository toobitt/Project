<?php

require_once('global.php');
class  water_request_info extends BaseFrm
{
    public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:
	 *功能:查询水印列表
	 *返回值:
	 * */
	public function water_request()
	{
		$info=$this->mater->water_request();
		$this->addItem($info);
		$this->output();
	}
	
}

$out = new water_request_info();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'water_request';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 

?>