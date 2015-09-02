<?php
//页面的数据库操作
require_once(ROOT_PATH . 'frm/node_frm.php');
class page extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//根据条件查询模专题分类
	public function show($condition,$limit)	
	{	
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."page 
				WHERE 1".$condition.$limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{	
			$info[] = $row;
		}	
		return $info;
	}	
	
	//新增模板
	public function create($info)
	{	
		//先插入节点
		$sort_data = array(
			'ip'=>hg_getip(),
			'create_time'=>TIMENOW,
			'fid'=>$info['fid'],
			'update_time'=>TIMENOW,
			'name'=>$info['name'],
			'brief'=>$info['brief'],
			'user_name'=>trim(urldecode($this->user['user_name']))
		);
		//$this->setCondition(',page_type='.$info['page_type'].',dir ='."'".$info['dir']."'".',file_name ='."'".$info['file_name']."'".',file_type ='."'".$info['file_type']."'".',domain_name ='."'".$info['domain_name']."'");
		
		$this->initNodeData();
		$this->setNodeTable('page');
		$this->setCondition(',site_id='.$info['site_id'].',file_type ='."'".$info['file_type']."'".',file_name ='."'".$info['file_name']."'");
		$this->setNodeData($sort_data);
		$sort_id = $this->addNode();
		return $sort_id;
		
	}
	
	//更新模板相关信息
	public function update($data)
	{	
		
		//插入数据操作
		$sql = "UPDATE " . DB_PREFIX ."page SET ";
		$sql_extra = $space ='';
		foreach($data as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$data['id'];
		$this->db->query($sql);		
	}
	
	//删除日志
	public function delete($id)
	{	
		$this->initNodeData();
		$this->setNodeTable('page');
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