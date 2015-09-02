<?php
//模板分类的数据库操作
define('MOD_UNIQUEID','template_classify');//模块标识
require_once(ROOT_PATH . 'frm/node_frm.php');
class templateClassify extends nodeFrm
{
	public function __construct()
	{
		$this->setNodeTable('template_classify');
		$this->setNodeVar('template_classify');
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//根据操作进行日志存储
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
		$this->setNodeTable('template_sort');
		//增加节点无需设置操作节点ID
		$this->setCondition(',site_id='.$info['site_id']);
		//$this->setCondition(',sort_dir ='.$info['sort_dir']);
		//设置新增或者需要更新的节点数据
		$this->setNodeData($sort_data);
		$sort_id = $this->addNode();
		return $sort_id;
		
	}
	
	//根据操作进行模板分类存储
	public function update($info)
	{	
		//更新数据操作
		$sql = "UPDATE " . DB_PREFIX ."template_sort SET ";
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
	
	//删除模板分类
	public function delete()
	{	
		$ids=urldecode($this->input['id']);
		$this->initNodeData();
		$this->setNodeTable('template_sort');
		$this->setNodeID($ids);
		if(!$this->deleteNode())
		{
			$this->errorOutput("删除失败");
		}
		$this->addItem(array());
		$this->output();
	}	
	
	//根据条件查询模板分类
	public function  show()
	{	
		$this->setXmlNode('nodes' , 'node');
		$this->setNodeID(intval($this->input['fid']));
		$this->addExcludeNodeId($this->input['_exclude']);
		$this->getNodeChilds();
		$this->output();		
	}
	
	public function get_father_column($column_id , &$data = array())
	{
		$column_detail =  $this->get_column_first(' id,name,fid ',$column_id);
		$column_all    =  $this->get_column_by_fid(' id,name,fid ',$column_detail['fid']);
		$column_all['select_column'] = $column_id;
		if(!empty($column_all))
		{
			array_unshift($data,$column_all);
		}
		if($column_detail['fid'])
		{
			$this->get_father_column($column_detail['fid'] , $data);
		}		
	}
	
	public function get_column_first($field = '*',$id)
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."template_sort WHERE id=".$id;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_column_by_fid($field,$fid)
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."template_sort WHERE 1 AND fid=".$fid;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{				
			$ret[] = $row;
		}
		return $ret;
	}
	
	
}
?>