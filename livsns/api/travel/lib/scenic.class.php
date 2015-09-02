<?php
//模板的数据库操作
require_once(ROOT_PATH . 'frm/node_frm.php');
class scenic extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
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
			'user_name'=>trim(urldecode($this->user['user_name']))
		);
		
		$this->initNodeData();
		$this->setNodeTable('scenic');
		$this->setCondition(',sort_id='.$info['sort_id'].',appid='.$info['appid'].',country='.$info['country'].',province='.$info['province'].',city='.$info['city'].',area='.$info['area'].',user_id='.$info['user_id'].',grade ='."'".$info['grade']."'".',brief ='."'".$info['brief']."'".',address ='."'".$info['address']."'".',keywords ='."'".$info['keywords']."'".',indexpic ='."'".$info['indexpic']."'");
		$this->setNodeData($sort_data);
		$sort_id = $this->addNode();
		return $sort_id;
	}
	
	//更新模板相关信息
	public function update($info)
	{	
		//更新数据操作
		$sql = "UPDATE " . DB_PREFIX ."scenic SET ";
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
	
	//删除主题
	public function delete($id)
	{			
		$this->initNodeData();
		$this->setNodeTable('scenic');
		$this->setNodeID($id);
		if(!$this->deleteNode())
		{
			$this->errorOutput("删除失败");
		}
		$this->addItem(array());
		$this->output();
	}	
	
	//根据条件查询专题
	public function show($condition,$limit)	
	{		
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."scenic 
				WHERE 1".$condition.' ORDER BY orderid DESC '.$limit;
		$q = $this->db->query($sql);
		$sql_ = "select name,id from " . DB_PREFIX . "scenic_sort where 1";
		$sorts = $this->db->fetch_all($sql_);
		
		while($row = $this->db->fetch_array($q))
		{				
			foreach ($sorts as $k=>$v){			
				if( $v['id']== $row['sort_id']){
					$row['sort_name'] = $v['name'];
				}
				$row['cre_time'] = date("Y-m-d H:i",$row['create_time']);
			}	
			$ret[] = $row;
		}
		//file_put_contents('00',var_export($ret,1));
		return $ret;
	}
	
	//新增介绍
	public function insert_content($scenic_id,$argument)
	{	
		foreach($argument['argument_name'] as $k=>$v)
		{	
			$info = array(
				'title'			=>	$v,
				'scenic_id'		=>	$scenic_id,
				'introduce'		=>	$argument['value'][$k],
			);
			$sql = "INSERT INTO " . DB_PREFIX ."scenic_introduce SET ";
			$sql_extra = $space ='';
			foreach($info as $k=>$v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
			}
			$sql .=$sql_extra;
			$this->db->query($sql);
		}
	}
	
	//更新介绍
	public function update_content($id,$argument)
	{	
		foreach($argument['argument_name'] as $k=>$v)
		{	
			$info = array(
				'title'			=>	$v,
				'scenic_id'		=>	$scenic_id,
				'introduce'		=>	$argument['value'][$k],
			);
			$sql = 'REPLACE INTO '.DB_PREFIX.'scenic_introduce SET ';
			foreach ($data as $key=>$val)
			{
			$sql .= $key.'="'.$val.'",';
			}
			$sql = rtrim($sql,',');
			$this->db->query($sql);
		}
	}
}


?>