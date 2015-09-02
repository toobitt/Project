<?php
define('MOD_UNIQUEID','littlestore_node');
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class news_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeTable('sort');
		$this->setNodeVar('news_node');
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	//默认载入第一维数据
	public function show()
	{
		$this->setXmlNode('nodes' , 'node');
		$this->setNodeID(intval($this->input['fid']));
		$this->addExcludeNodeId($this->input['_exclude']);
		$this->getNodeChilds('', false);
		//$this->addItem($this->input['_exclude']);
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
	
	//获取选中的节点
	public function getSelectedNodes()
	{
		$id = trim(urldecode($this->input['id']));
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		$this->getMultiNodesInfo($id);
		$this->output();
	}
	//获取选中的节点树状路径	
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
	//获取查询条件
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= ' AND id = '.intval(urldecode($this->input['id']));
		}
		
		if($this->input['sort_name'])
		{
			$condition .= ' AND name = '.urldecode($this->input['id']);
		}
		return $condition;
	}
	
	//用于分页
	public function count()
	{
		parent::count($this->get_condition());	
	}

}

$out=new news_node();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();
?>