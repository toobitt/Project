<?php
define('MOD_UNIQUEID','member_node');//模块标识
define('SCRIPT_NAME', 'member_node');
require_once('global.php');
require_once ROOT_PATH . 'frm/node_frm.php';
class member_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeTable('member_node');
		$this->setNodeVar('member_node');
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
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
			$condition .= ' AND id IN('.trim($this->input['id']).')';
		}
		return $conditions;
	}
	public function detail()
	{
		$id = trim($this->input['id']);
		$this->initNodeData();
		$this->setNodeID(intval($this->input['id']));
		$this->addItem($this->getOneNodeInfo());
		$this->output();
	}
	//获取选中的节点
	public function getSelectedNodes()
	{
		$id = trim($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		$this->setNodeTable('member_node');
		$this->getMultiNodesInfo($id);
		$this->output();
	}
}
include_once ROOT_PATH . 'excute.php';

?>