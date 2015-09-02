<?php
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
define('MOD_UNIQUEID','cp_thread_m');//模块标识
class thread_node extends outerReadBase
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
		$this->setXmlNode('thread_types' , 'thread_type');
		foreach($this->settings['thread_type'] as $k=>$v)
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

$out = new thread_node();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'show';	
}
$out->$action();
?>