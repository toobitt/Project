<?php
require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('SCRIPT_NAME', 'admin_org_node');
define('MOD_UNIQUEID','org');
class admin_org_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeTable('admin_org');
		$this->setNodeVar('admin_org_node');
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	//默认列出顶级节点
	public function show()
	{
		$this->setXmlNode('nodes' , 'node');
		$this->setNodeID(intval($this->input['fid']));
		$this->addExcludeNodeId($this->input['_exclude']);
		$this->getNodeChilds($this->get_condition());
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
	//获取节点的父节点树
	public function getParentsTree()
	{
		$id = $this->input['ids'];
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		$ret = $this->getParentsTreeById($id);
		$this->addItem($ret);
			$this->output();
		if($this->input['debug'])
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	//获取节点的父节点树,合并父节点
	public function getMergeParentsTree()
	{
		$id = $this->input['ids'];
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		$ret = $this->getMergeParentsTreeById($id);
		if($this->input['debug'])
		{
			print_r($ret);exit;
		}
		$this->addItem($ret);
		$this->output();
	}

	//根据深度获取节点树
	public function getNodeTreeByDeep()
	{
		$deep = intval($this->input['deep']);
		$length = intval($this->input['length']);
		$this->getNodeTree($deep, $length);
		$this->output();
	}
	//编辑
	public function detail()
	{
		$this->initNodeData();
		$this->setNodeID(intval($this->input['id']));
		$this->addItem($this->getOneNodeInfo());
		$this->output();
	}
	//用于节点搜索
	public function nodeForSearch()
	{
		$this->getFirstLevelNode();
	}
	//获取查询条件
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= ' AND id IN('.(urldecode($this->input['id'])).')';
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
	
	public function getOrgs()
	{
		$this->setXmlNode('nodes' , 'node');
		if($this->input['ids'])
		{
			$condition = ' AND id in('.$this->input['ids'].')';
		}
		$condition .= ' AND fid in('.intval($this->input['fid']).')';
		
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$order = "  ORDER BY order_id ASC,id ASC  ";
		
		$sql = "SELECT count(id) as total FROM ".DB_PREFIX."admin_org  WHERE 1 ".$condition;
		$query = $this->db->query_first($sql);
		$data['total'] = $query['total'];
		
		$sql = "SELECT * FROM ".DB_PREFIX."admin_org  WHERE 1 ".$condition.$order.$limit;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d h:i:s',$r['update_time']);
			$org[$r['id']] = $r;
		}
		$data['data'] = $org;
		$this->addItem($data);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>
