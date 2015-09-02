<?php
//主题分类的数据库操作
define('MOD_UNIQUEID','subway_sort');//模块标识

class subwaySort extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create($info)
	{		
		//先插入节点
		$sort_data = array(
			'ip'=>hg_getip(),
			'create_time'=>TIMENOW,
			'fid'=>$info['fid'],
			'update_time'=>TIMENOW,
			'name'=>$info['name'],
			'brief'=>'',
			'user_name'=>trim(urldecode($this->user['user_name']))
		);
		$this->initNodeData();
		$this->setNodeTable('subway_sort');
		$this->setNodeData($sort_data);
		$sort_id = $this->addNode();
		
		return $sort_id;
		
	}
	
	public function update($info)
	{	
		
		$this->initNodeData();
        //设置新增或者需要更新的节点数据
        $this->setNodeTable('subway_sort');
        $this->setNodeData($info);
        //设置操作的节点ID
        $this->setNodeID($info['id']);
        //更新方法
        $this->updateNode();
		$this->addItem($info);
		$this->output();
	}
	
	//删除主题分类
	public function delete($id)
	{	
		$this->initNodeData();
		$this->setNodeTable('subway_sort');
		$this->setNodeID($id);
		if(!$this->deleteNode())
		{
			$this->errorOutput("删除失败");
		}
		$this->addItem(array());
		$this->output();
		
	}	
	
}
?>