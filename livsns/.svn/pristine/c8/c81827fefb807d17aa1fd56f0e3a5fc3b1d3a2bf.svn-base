<?php
//样式分类的数据库操作
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','mode_sort');//模块标识
class modeSort extends nodeFrm
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
			'ip'			=>hg_getip(),
			'create_time'	=>TIMENOW,
			'fid'			=>$info['fid'],
			'update_time'	=>TIMENOW,
			'name'			=>$info['name'],
			'brief'			=>'',
			'user_name'		=>trim(urldecode($this->user['user_name']))
		);
		$this->initNodeData();
		$this->setNodeTable('cell_mode_sort');
		//增加节点无需设置操作节点ID
		//$this->setCondition(',site_id='.$info['site_id']);
		//设置新增或者需要更新的节点数据
		$this->setNodeData($sort_data);
		$data['sort_id'] = $this->addNode();	
	}
	
	//更新样式和样式参数相关信息
	public function update($data)
	{	
		$create_time = TIMENOW;
		//样式数据操作
		$sql = "UPDATE " . DB_PREFIX ."cell_mode_sort SET ";
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
	
	//删除样式
	public function delete($id)
	{	
		$sql="DELETE FROM " . DB_PREFIX . "cell_mode_sort WHERE id IN(" . $id . ")";
		$this->db->query($sql);
		return ($this->db->affected_rows() > 0) ? true : false;
	}	
	
	
	
	//根据条件查询模板
	public function show($condition,$limit)	
	{		
		$sql = "SELECT id,name,is_last
				FROM  " . DB_PREFIX ."cell_mode_sort 
				WHERE 1".$condition.' ORDER BY id DESC'.$limit;
		$q = $this->db->query($sql);
		require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub = new publishconfig();
		//取站点
		//$sites = $this->pub->get_site();
		while($row = $this->db->fetch_array($q))
		{						
			/*foreach ($sites as $k=>$v){			
				if( $v['id']== $row['site_id']){
					$row['site_name'] = $v['site_name'];
				}
			}*/
			if('1' == $row['is_last'])
			{
				$row['is_last'] = '无';
			}
			else
			{
				$row['is_last'] = '有';
			}
			$ret[] = $row;
			
		}
		$info[] = $ret;
		return $info;
	}
	
	//新增模板
	public function edit_update($data)
	{	
		//插入数据操作
		$sql = "UPDATE " . DB_PREFIX ."templates SET ";
		$sql_extra = $space ='';
		foreach($data as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id = ".$data['id'];
		$this->db->query($sql);		
	}
}

?>