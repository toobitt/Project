<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_plan.php 5399 2011-12-20 01:29:35Z repheal $
***************************************************************************/
require("global.php");
define('MOD_UNIQUEID','program_plan');
class programPlanApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(ROOT_DIR . 'lib/class/program.class.php');
		$this->obj = new program();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show(){}
	function index(){}
	public function count(){}
	public function detail(){}
	
	function getPlanByChannel()
	{
		$channel_id = intval($this->input['channel_id']) ? intval($this->input['channel_id']) : 0;
		if(empty($channel_id))
		{
			$this->errorOutput("缺少频道ID");
		}
		$info = $this->obj->getPlanByChannel($channel_id);
		$this->addItem($info);
		$this->output();
	}
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new programPlanApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>	