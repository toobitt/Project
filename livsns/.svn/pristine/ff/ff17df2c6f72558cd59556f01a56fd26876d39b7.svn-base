<?php
require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('SCRIPT_NAME', 'column_node');
class column_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/column.class.php');
		$this->obj= new column();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	//默认列出顶级节点
	public function show()
	{
		$fid = urldecode($this->input['fid']);
		if(empty($fid))
		{
			$sites = $this->obj->get_site('*');
			foreach($sites as $k=>$v)
			{
				$m = array('id'=>'site'.$v['id'],"name"=>$v['site_name'],"fid"=>'site'.$v['id'],"depth"=>1);
				$column = $this->obj->get_column(' id ',' AND site_id='.$v['id']);
				if(empty($column))
				{
					$m['is_last'] = 1;
				}
				$this->addItem($m);
			}
		}
		else if(strstr($fid,"site")!==false)
		{
			$site_id = str_replace('site','',$fid);
			$column = $this->obj->get_column_all(0,$site_id);
			foreach($column as $k=>$v)
			{
				$m = array('id'=>$v['id'],"name"=>$v['name'],"fid"=>$v['id'],"depth"=>1);
				$columnlast = $this->obj->get_column(' id ',' AND fid='.$v['id']);
				if(empty($columnlast))
				{
					$m['is_last'] = 1;
				}
				$this->addItem($m);
			}
		}
		else
		{
			$this->setXmlNode('nodes' , 'node');
			$this->setNodeTable('column');
			$this->setNodeID($fid);
			$this->addExcludeNodeId($this->input['_exclude']);
			$this->getNodeChilds();
			//$this->addItem($this->input['_exclude']);
		}
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
		$this->setNodeTable('column_sort');
		$this->getMultiNodesInfo($id);
		$this->output();
	}
	
	public function insert_node($table,$data,$condition = '')
	{
		$this->initNodeData();
		$this->setNodeTable($table);
		$this->setCondition($condition);
		//设置新增或者需要更新的节点数据
		$this->setNodeData($data);
		return $this->addNode();
	}
}
include(ROOT_PATH . 'excute.php');
?>
