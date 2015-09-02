<?php
require('global.php');

class thread_node extends BaseFrm
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
			$r = array(
				'id' => $k,
				"name" => $v,
				"fid" => 0,
				"depth" => 1,
				'attr' => $this->settings['group_type_attr'][$k],
				'input_k' => '_type',
				'is_last' => 1
			);
			$this->addItem($r);
		}
		$this->output();
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