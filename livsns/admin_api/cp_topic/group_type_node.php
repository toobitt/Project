<?php
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class group_node_type extends BaseFrm
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
	 * 获取地盘 节点
	 */
	public function show()
	{
		$this->setXmlNode('group_types' , 'group_type');
		foreach($this->settings['group_type'] as $k=>$v)
		{
			$r = array('id'=>$k,"name"=>$v,"fid"=>0,"depth"=>1,'attr' =>$this->settings['group_type_attr'][$k],'input_k' =>'_type' ,'is_last'=>1);
			$this->addItem($r);
		}
		$this->output();
	}
		
}

/**
 * 程序入口
 */

$out = new group_node_type();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'show';	
}
$out->$action();
?>