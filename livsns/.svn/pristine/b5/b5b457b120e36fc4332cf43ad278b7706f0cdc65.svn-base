<?php
//主题分类的数据库操作
define('MOD_UNIQUEID','specialContentSort');//模块标识

class specialContentSort extends InitFrm
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
			'brief'=>$info['brief'],
			'user_name'=>trim(urldecode($this->user['user_name']))
		);
		$this->initNodeData();
		$this->setNodeTable('special_content_sort');
		$this->setCondition(',special_id='.$info['speid']);
		$this->setNodeData($sort_data);
		$data['sort_id'] = $this->addNode();
		
	}
	
	public function update($info)
	{	
		//更新数据操作
		$sql = "UPDATE " . DB_PREFIX ."special_content_sort SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$info['id'];
		$this->db->query($sql);		
	}
	
	//删除主题分类
	public function delete($id)
	{	
		$this->initNodeData();
		$this->setNodeTable('special_content_sort');
		$this->setNodeID($id);
		if(!$this->deleteNode())
		{
			$this->errorOutput("删除失败");
		}
		$this->addItem(array());
		$this->output();
		
		/*return ($this->db->affected_rows() > 0) ? true : false;*/
	}	
	
	//根据条件查询模专题分类
	public function show($condition,$limit)	
	{	
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."special_content_sort 
				WHERE 1".$condition.$limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{	
			$childs = explode(",",$row['childs']);
			$num = count($childs);
			$row['num'] = $num;
			$info[] = $row;
		}	
		return $info;
	}	
	
}
?>