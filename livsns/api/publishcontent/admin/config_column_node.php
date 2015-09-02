<?php
require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('SCRIPT_NAME', 'column_node');
define('MOD_UNIQUEID','column');
class column_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	//默认列出顶级节点
	public function show()
	{
		$con = '';
		$site_id = $this->input['site_id'];
		$this->setXmlNode('nodes' , 'node');
		$this->setNodeTable('column');
		$this->setNodeID(intval($this->input['fid']));
		$this->addExcludeNodeId($this->input['_exclude']);
		if($site_id)
		{
			$con = ' AND site_id='.$site_id;
		}
		$this->input['count'] = 1000;
		$this->getNodeChilds($con);
		$this->output();
	}
	
	//编辑
	public function detail()
	{
		$this->setXmlNode('nodes' , 'node');
		$this->setNodeTable('column');
		$this->initNodeData();
		$this->setNodeID(intval($this->input['id']));
		//查询出当前节点的信息
		$ret = $this->getOneNodeInfo();
		$this->addItem($ret);
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
		$this->setXmlNode('nodes' , 'node');
		$this->setNodeTable('column');
		$this->initNodeData();
		$this->getMultiNodesInfo($id);
		$this->output();
	}
	
	public function get_selected_column_path()
	{
		$this->setNodeTable('column');
		$ids = urldecode($this->input['id']);
		if(!$ids)
		{
			$this->output();
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
	public function get_authored_columns()
	{
		$this->initNodeData();
		$this->setXmlNode('columns' , 'column');
		$this->setNodeTable('column');
		$this->setNodeID(intval($this->input['fid']));
		$this->setNodeVar('column');
		$conditions = " and site_id = ".($this->input['siteid'] ? intval($this->input['siteid']) : 1);
		$this->getNodeChilds($conditions);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>
