<?php
define('MOD_UNIQUEID','livmedia_node');
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require_once(ROOT_PATH . 'frm/node_frm.php');
define('SCRIPT_NAME', 'vod_media_node');
class vod_media_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeTable('vod_media_node');
		$this->setNodeVar('vod_media_node');
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
		$this->getNodeChilds($this->get_condition(), 0);
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
	//获取选中的节点树状，节点权限调用
	public function get_selected_node_path()
	{
		$ids = urldecode($this->input['id']);
		if(!$ids)
		{
			$this->errorOutput(NO_ID);
		}
		$tree = $this->getParentsTreeById($ids, 0);
		if($tree)
		{
			foreach($tree as $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
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
		//查询出当前节点的信息
		$ret = $this->getOneNodeInfo();
		$this->addItem($ret);
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
		####增加权限控制 用于显示####
		if($this->input['self_group_type'] > MAX_ADMIN_TYPE && $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'][0] != '-1')
		{
			$authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if($authnode)
			{
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if($authnode_str)
				{
					$sql = 'SELECT id,childs,parents FROM '.DB_PREFIX.'vod_media_node WHERE id IN('.$authnode_str.')';
					$query = $this->db->query($sql);
					$authnode_array = array();
					while($row = $this->db->fetch_array($query))
					{
						$parents_nodes = explode(',', $row['parents']);
						$childs_nodes  = explode(',', $row['childs']);
						$authnode_array[$row['id']]= array_unique(array_merge($parents_nodes,$childs_nodes));
					}
					//算出所有允许的节点
					$auth_nodes = array();
					foreach($authnode_array AS $k => $v)
					{
						$auth_nodes = array_merge($auth_nodes,$v);
					}
					$auth_nodes = array_unique($auth_nodes);
					$condition .= " AND  id IN (".implode(',', $auth_nodes).")";
				}
			}
		}
		####增加权限控制 用于显示####
		
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
include(ROOT_PATH . 'excute.php');
?>
