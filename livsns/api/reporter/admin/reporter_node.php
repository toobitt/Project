<?php
require_once('./global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('SCRIPT_NAME', 'reporter_node');
define('MOD_UNIQUEID', 'reporter_node');
require_once CUR_CONF_PATH.'/lib/contribute_sort.class.php';
class reporter_node extends nodeFrm
{
	public function __construct()
	{
		$this->resetPrmsMethods();
		$this->mModPrmsMethods['manage'] = array(
		'name'=>'管理',
		);
		$this->mModPrmsMethods['show'] = array(
		'name'=>'查看',
		);
		parent::__construct();
		$this->setNodeTable('sort');
		$this->setNodeVar('sort');
		$this->sort = new contribute_sort();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	//默认列出顶级节点
	public function show()
	{
		$this->setXmlNode('nodes' , 'node');
		$this->setNodeTable('sort');
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
		$this->setNodeTable('sort');
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
	public function detail()
	{
		$id = intval(trim($this->input['id']));
		$data = $this->sort->detail($id);
		$this->addItem($data);
		$this->output();
	}
	public function fastInput_sort()
	{
		$data = $this->sort->fastInput_sort();
		$this->addItem($data);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>
