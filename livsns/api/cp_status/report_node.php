<?php
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
define('MOD_UNIQUEID','mblog_report_m');
class report_node extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 
	 * 获取帖子 节点
	 */
	public function show()
	{
		$this->setXmlNode('report_types' , 'report_type');
		foreach($this->settings['report_node_type'] as $k=>$v)
		{
			$r = array('id'=>$k,"name"=>$v,"fid"=>0,"depth"=>1, 'input_k' => '_type' ,'is_last'=>1);
			$this->addItem($r);
		}
		$this->output();
	}
	
	public function count()
	{
		
	}
	
	public function detail()
	{
		
	}
}

/**
 * 程序入口
 */

$out = new report_node();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'show';	
}
$out->$action();
?>