<?php
//页面的数据库操作
define('MOD_UNIQUEID','page_manage');//模块标识
require_once(ROOT_PATH . 'frm/node_frm.php');
class pageManage extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//根据操作进行页面存储
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
		$this->setNodeTable('page_manage');
		//增加节点无需设置操作节点ID
		//$this->setCondition(',site_id='.$info['site_id']);
		//$this->setCondition(',sort_dir ='.$info['sort_dir']);
		//$this->setCondition(",title='".$this->input['name']."',site_id='".$info['site_id']."'");
		$this->setCondition(",title='".$this->input['name']."',site_id='".$info['site_id']."',org_id='".$info['org_id']."'");
		//设置新增或者需要更新的节点数据
		$this->setNodeData($sort_data);
		$sort_id = $this->addNode();
		return $sort_id;
		
	}
	
	//根据操作进行页面更新
	public function update($info)
	{	
		//更新数据操作
		$sql = "UPDATE " . DB_PREFIX ."page_manage SET ";
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
	
	//删除页面
	public function delete($id)
	{	
		$this->initNodeData();
		$this->setNodeTable('page_manage');
		$this->setNodeID($id);
		if(!$this->deleteNode())
		{
			$this->errorOutput("删除失败");
		}
		$this->addItem(array());
		$this->output();
	}	
	
	//根据条件查询页面
	public function show($limit,$condition)	
	{	
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."page_manage 
				WHERE 1".$condition.' ORDER BY order_id'.$limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{			
			$ret[] = $row;
		}
		return $ret;
	}	
	
	public function get_page($con)
	{
		$info = array();
		$sql = "select * from ".DB_PREFIX."page_manage where 1 ".$con;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{	
			$info[] = $row;
		}	
		return $info;
	}
}
?>