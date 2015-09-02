<?php
define('SCRIPT_NAME', 'vote_node');
define('MOD_UNIQUEID', 'vote_node');
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR."global.php");
require(CUR_CONF_PATH."lib/functions.php");
require_once ROOT_PATH . 'frm/node_frm.php';
class vote_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeTable('vote_node');
		$this->setNodeVar('vote_node');
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
		$this->getNodeChilds($this->get_condition(), false);
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
		if(isset($this->input['_exclude']) && $this->input['_exclude'])
		{
			$condition .= ' AND id NOT IN('.trim(urldecode($this->input['_exclude'])).')';
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
		$this->setNodeTable('vote_node');
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
	
	public function nodelist()
	{
		$field = 'id,name,fid,color,brief,parents,childs,depath,is_last';
		$condition = $this->get_condition();
		$sql = "select ". $field. " from " . DB_PREFIX . "vote_node WHERE 1 ";
		$sql .= $condition;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data[] = $row;
		}
		if($data)
		{
			foreach($data as $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
}
include_once ROOT_PATH . 'excute.php';

?>