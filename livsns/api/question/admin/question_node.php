<?php
define('SCRIPT_NAME', 'question_node');
require_once('global.php');
require_once ROOT_PATH . 'frm/node_frm.php';
class question_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeTable('question_node');
		$this->setNodeVar('question_node');
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		//$this->errorOutput(var_export($this->input,1));
		$this->setNodeID(intval($this->input['fid']));
		$this->addExcludeNodeId($this->input['_exclude']);
		$this->getNodeChilds($this->get_condition());
		//$this->addItem($this->input['_exclude']);
		$this->output();
	}
	public function get_condition()
	{
		$conditions = ' AND  fid = '.intval($this->input['fid']);
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
		//	$condition .= ' AND name like \'%'.urldecode($this->input['k']).'%\'';
		}
		if(isset($this->input['id']) && $this->input['id'])
		{
			$condition .= ' AND id IN('.trim(urldecode($this->input['id'])).')';
		}
		return $conditions;
	}
	public function detail()
	{
		$id = urldecode($this->input['id']);
		$this->initNodeData();
		$this->setNodeID(intval($this->input['id']));
		$this->addItem($this->getOneNodeInfo());
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
		$this->setNodeTable('question_node');
		$this->getMultiNodesInfo($id);
		$this->output();
	}
	//获取选中的节点树状，节点权限调用
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
include_once ROOT_PATH . 'excute.php';

?>