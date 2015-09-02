<?php
define('MOD_UNIQUEID','seekhelp_node');
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class seekhelp_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeTable('sort');
		$this->setNodeVar('seekhelp_node');
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	//默认载入第一维数据
	public function show()
	{
		$this->setXmlNode('nodes' , 'node');
		$this->setNodeID(intval($this->input['fid']));
		$this->addExcludeNodeId($this->input['_exclude']);
		
		
		
		if($this->input['trigger_mod_uniqueid'] == 'seekhelp' && !$this->input['nodevar'])
		{
			
			if(defined('NODE_COUNT') && NODE_COUNT)
			{
				$node_count =  NODE_COUNT;
			}
			else 
			{
				$node_count = 20;
			}
			$this->input['count'] = $this->input['count'] ? $this->input['count'] : $node_count;
			
			$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "sort WHERE fid=0";
			$res = $this->db->query_first($sql);
			
			$row['total'] = $res['total'];
			$this->addItem_withkey('total', $row);
		}
		
		$this->getNodeChilds();
		
		
		//$this->addItem($this->input['_exclude']);
		$this->output();
	}
	
	//编辑
	public function detail()
	{
		$this->initNodeData();
		$this->setNodeID(intval($this->input['id']));
		//查询出当前节点的信息
		$ret = $this->getOneNodeInfo();
		$this->addItem($ret);
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
		$this->getMultiNodesInfo($id);
		$this->output();
	}
	//获取选中的节点树状路径	
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
	//获取查询条件
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= ' AND id = '.intval(urldecode($this->input['id']));
		}
		
		if($this->input['sort_name'])
		{
			$condition .= ' AND name = '.urldecode($this->input['id']);
		}

		if($this->input['time'])
		{
			$condition .= ' AND create_time > "'.intval(strtotime(urldecode($this->input['time']))).'"';
		}
		
		if($this->input['k'] || urldecode($this->input['k'])== '0')
		{
			$condition .= ' AND  name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > ".$yesterday." AND create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > ".$today." AND create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > ".$last_threeday." AND create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		
		return $condition;
	}
	
	//用于分页
	public function count()
	{
		parent::count($this->get_condition());	
	}

}

$out=new seekhelp_node();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();
?>