<?php
require_once './global.php';
require_once ROOT_PATH . 'frm/node_frm.php';
define('SCRIPT_NAME', 'staff_node');
define('MOD_UNIQUEID', 'staff_node');
class staff_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeTable('department');
		$this->setNodeVar('staff_node');
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	//默认列出顶级节点
	public function show()
	{
		$this->setXmlNode('nodes' , 'node');
		$this->setNodeTable('department');
		$this->setNodeID(intval($this->input['fid']));
		$this->addExcludeNodeId($this->input['_exclude']);
		$this->getNodeChilds();
		//$this->addItem($this->input['_exclude']);
		$this->output();
	}
	//获取选中的节点
	public function getSelectedNodes()
	{
		$id = trim(urldecode($this->input['id']));
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		$this->setNodeTable('department');
		$this->getMultiNodesInfo($id);
		$this->output();
	}
	//获取选中的节点树状
	public function get_selected_node_path()
	{
		$ids = urldecode($this->input['id']);
		if(!$ids)
		{
			$this->errorOutput(NO_ID);
		}
		$tree = $this->getParentsTreeById($ids);
		if($tree)
		{
			foreach($tree as $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>
