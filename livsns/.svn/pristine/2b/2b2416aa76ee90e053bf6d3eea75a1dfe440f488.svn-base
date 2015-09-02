<?php
require_once(ROOT_PATH . 'frm/node_frm.php');
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
	
	public function update_node($column_id,$column_fid)
	{
		$nodedata['fid'] = $column_fid;
		$this->initNodeData();
		$this->setNodeTable('column');
		$this->setNodeID($column_id);
		$this->setNodeData($nodedata);
		$this->updateNode();
	}
	
	public function insert_node($column_name,$column_fid,$data)
	{
		//先插入节点
		$sort_data = array(
			'ip'=>hg_getip(),
			'create_time'=>TIMENOW,
			'fid'=>$column_fid,
			'update_time'=>TIMENOW,
			'name'=>$column_name,
			'brief'=>'',
			'user_name'=>trim(urldecode($this->user['user_name']))
		);
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$this->initNodeData();
		$this->setNodeTable('column');
		$this->setCondition(','.$sql_extra);
		$this->setNodeData($sort_data);
		return $this->addNode();
	}
	
	public function delete_node($ids)
	{
		$this->initNodeData();
		$this->setNodeTable('column');
		$this->batchDeleteNode($ids);
	}
	
	function getMergeParents($id)
	{
		$this->initNodeData();
		$this->setNodeTable('column');
		return $this->getMergeParentsTreeById($id);
	}
	
}
?>