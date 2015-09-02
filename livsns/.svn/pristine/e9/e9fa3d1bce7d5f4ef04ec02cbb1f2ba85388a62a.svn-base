<?php
define('ROOT_PATH', '../../');
require(ROOT_PATH . 'global.php');
define('MOD_UNIQUEID','tuwenol_node');//模块标识
require_once(ROOT_PATH . 'frm/node_frm.php');
define('SCRIPT_NAME', 'tuwenol_node');
class tuwenol_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeTable('sort');
		$this->setNodeVar('tuwenol_node');
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	//默认列出顶级节点
	public function show()
	{
		$this->setXmlNode('nodes' , 'node');
		$this->setNodeID(intval($this->input['fid']));
		$this->addExcludeNodeId($this->input['_exclude']);
		$this->getNodeChilds($this->get_condition(),false);
		$this->output();
	}

	//编辑
	public function detail()
	{
		$this->initNodeData();
		$this->setNodeID(intval($this->input['id']));
		//查询出当前节点的信息
		$ret = $this->getOneNodeInfo();
		$this->addItem($ret);
		$this->output();
	}

	
	//用于分页
	public function count()
	{
		parent::count($this->get_condition());	
	}

}
include(ROOT_PATH . 'excute.php');
?>