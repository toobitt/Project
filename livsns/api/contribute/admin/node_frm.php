<?php
/***************************************************************************
* LivSNS 0.1
* ©2004-2010 HOGE Software.
*
* $Id: base_frm.php 7111 2012-06-09 00:52:04Z zhuld $
***************************************************************************/

/**
 * 节点程序基类
 * @author zhuld
 *
 */
class nodeFrm extends coreFrm
{
	protected $nodeVar = '';
	protected $nodeTable = '';
	protected $nodeId = 0;
	protected $excludeNode = array();
	protected $nodeData = array();
	protected $condition = '';
	static private $nodeTree = array();
	protected $nodeTreeFields = array(
        'id',//节点ID
        'name',//节点名称
        'brief',//节点描述
        'fid',//父节点
        'create_time',
        'update_time',
        'ip',
        'user_name',
	);
	protected $extraTreeFields = array();
	protected $user = array();
	function __construct()
	{
		parent::__construct();
		if(!defined('WITH_LOGIN') || WITH_LOGIN)
		{
			$this->verifyToken();
		}
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	//
	protected function verify_delete_node($nid = 0)
	{
		if($this->user['group_type'] <= MAX_ADMIN_TYPE)
		{
			return;
		}
		if(!in_array($nid, (array)$this->user['prms']['app_prms'][APP_UNIQUEID]['nodes']))
		{
			$this->errorOutput('非管理员无权创建顶级分类数据！');
		}
	}
	protected function verify_create_node($fid = 0)
	{
		if($this->user['group_type'] <= MAX_ADMIN_TYPE)
		{
			return;
		}
		if(!$fid)
		{
			$this->errorOutput('非管理员无权创建顶级分类数据！');
		}
	}
	
	protected function verify_update_node($fid = 0)
	{
		if($this->user['group_type'] <= MAX_ADMIN_TYPE)
		{
			return;
		}
		if(!$fid)
		{
			$this->errorOutput('非管理员无权修改顶级分类数据！');
		}
	}
	
	protected function verify_setting_prms()
	{
		if($this->user['group_type'] <= MAX_ADMIN_TYPE)
		{
			return;
		}
		if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
        {
           	$this->errorOutput(NO_PRIVILEGE);
        }
	}
	
	//添加节点数据
	function addNode()
	{
		if(!$this->nodeData)
		{
			return false;
		}
		if(!$this->nodeTreeFields)
		{
			return false;
		}
		//
		unset($this->nodeTreeFields['id']);

		$sql = 'INSERT INTO '.$this->nodeTable.' SET ';

		foreach($this->nodeTreeFields as $key)
		{
            if(!$this->nodeData[$key]) continue;
			$sql .= "{$key} = '".$this->nodeData[$key]."',";
		}
		$sql = trim($sql, ',');
		//增加其他的字段  如:",site_id=2"
		if(!empty($this->condition))
		{
			$sql .= $this->condition;
		}
		$this->db->query($sql);
		//新节点的ID
		$new_node_id =  $this->db->insert_id();
		//获取父节点的祖先节点
		$this->setNodeID($this->nodeData['fid']);
		$father_node_info = $this->getOneNodeInfo();
		//新节点的深度
		if($father_node_info['parents'])
		{
			$depath = count(explode(',', $father_node_info['parents']))+1;
		}
        else
        {
            $depath = 1;
        }
		//区分是顶级节点还是普通节点 并且计算出新节点的祖先节点
		$parents = $father_node_info['parents'] ? $new_node_id . ',' .$father_node_info['parents'] : $new_node_id ;
		//更新新插入的节点数据 设置depath字段
		if($new_node_id)
		{
			$sql = 'UPDATE '.$this->nodeTable.' SET  childs = '.intval($new_node_id) . ', parents = "'.$parents.'", is_last = 1, depath = '.intval($depath).' WHERE id = '.intval($new_node_id);
			$this->db->query($sql);
		}
		//更新所有祖先节点的后代节点数据
		if($father_node_info['parents'])
		{
			$sql = 'UPDATE '.$this->nodeTable.' SET childs = CONCAT(childs, "'.','.$new_node_id.'") WHERE id IN ('.$father_node_info['parents'].')';
			//$this->errorOutput($sql);
            $this->db->query($sql);
            if($father_node_info['is_last'])
            {
                //更新父节点is_last字段
                $this->set_is_last($this->nodeData['fid'], 0);
            }
		}
		if(method_exists($this, 'addNodeCallback'))
		{
			//$sql = 'SELECT * FROM '.$this->nodeTable.' WHERE id = '.$new_node_id;
			//$nodeData = $this->db->query_first($sql);
			$this->addNodeCallback($new_node_id);
		}
		//更新排序节点为当前节点
		$this->db->query("UPDATE ".$this->nodeTable.' SET order_id = '.intval($new_node_id) . ' WHERE id = '.intval($new_node_id));
		return $new_node_id;
	}
	//批量删除节点
	function batchDeleteNode($ids = '')
	{
		if(!$ids)
		{
			return false;
		}
		$ids = is_string($ids) ? explode(',', urldecode($ids)) : $ids;
		if(!$ids)
		{
			return false;
		}
		//检测批量删除节点是否存在子节点
		if($this->isExistsChilds($ids))
		{
			$this->errorOutput('删除节点中存在子节点，无法完成删除操作！');
		}
		//循环调用单个节点的删除
		foreach($ids as $id)
		{
			$this->setNodeID($id);
			if(!$this->deleteNode())
			{
				return false;
			}
		}
		return true;
	}
	//删除单个节点
	function deleteNode()
	{
		if(!$this->nodeId)
		{
			return false;
		}
		$current_node_info = $this->getOneNodeInfo();
		if(!$current_node_info['is_last'])
		{
		    $this->errorOutput('删除失败，该节点存在子节点，请先删除子节点，再操作！');
		}
		$nodePatents = $current_node_info['parents'];
		$nodeChilds = $current_node_info['childs'];

		//更新删除节点的祖先节点子节点字段
		if($nodePatents)
		{
			$sql = 'SELECT * FROM '.$this->nodeTable.' WHERE id IN ('.$nodePatents.')';
			$q  = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				if($row['id'] == $current_node_info['id'])
				{
				    continue;
				}
				if($row['id'] == $current_node_info['fid'])
				{
				    //记录父节点信息 减少查询
				    $parent_node_info = $row;
				}
				//去除已删除的节点
				$childs_array = explode(',', $row['childs']);
				$childs_array = array_diff($childs_array, (array)$this->nodeId);
				if($childs_array)
				{
					$childs_str = implode(',', $childs_array);
					$this->db->query('UPDATE '.$this->nodeTable.' SET childs ="'.$childs_str.'" WHERE id = '.intval($row['id']));
				}
			}
		}

		//删除指定的节点
		$sql = 'DELETE FROM '.$this->nodeTable.' WHERE id = '.intval($this->nodeId)
		 . ' LIMIT 1';
		$this->db->query($sql);

		//如果删除的节点是末节点并且不存在其他兄弟节点 则更新其父节点的is_last字段
		if($current_node_info['is_last'] && !$this->getNodesBrothers($current_node_info['fid'], true))
		{
			$this->set_is_last($current_node_info['fid'], 1);
		}	
		//删除操作的回调方法
		if(method_exists($this, 'deleteNodeCallback'))
		{
			//$sql = 'SELECT * FROM '.$this->nodeTable.' WHERE id = '.intval($this->nodeId);
			//$nodeData = $this->db->query_first($sql);
			$this->deleteNodeCallback($this->nodeId);
		}
		return true;
	}
	//检测节点是否存在子节点
	function isExistsChilds($ids = array())
	{
		if(!$ids)
		{
			return false;
		}
		$ids_str = is_array($ids) ? implode(',', $ids) : $ids;
		if(!$ids)
		{
			return false;
		}
		$sql = 'SELECT childs FROM '.$this->nodeTable.' WHERE id IN('.$ids_str.')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			//只要childs存在逗号则说明必然存在子节点
			if(strpos($row['childs'], ',') !== false)
			{
				return true;
			}
		}
		return false;
	}
	//更新节点
	function updateNode()
	{
		if(!$this->nodeId)
		{
			return false;
		}
        if(!$this->nodeData)
        {
            return false;
        }
        //当前操作节点信息
		$current_node_info = $this->getOneNodeInfo();
		if($current_node_info)
		{
			$current_node_parents = $current_node_info['parents'];
			$current_node_childs = $current_node_info['childs'];
		}
        //$this->errorOutput(var_export($current_node_childs,1));
        //更新操作节点的父节点
        $this->db->query('UPDATE '.$this->nodeTable.' SET fid = '.intval($this->nodeData['fid']).' WHERE id = '.intval($this->nodeId));
		//更新移动节点的祖先节点的后代节点字段
		if($current_node_parents)
		{
			$sql = 'SELECT * FROM '.$this->nodeTable.' WHERE id IN ('.$current_node_parents.')';
			$current_node_childs_array = explode(',', $current_node_childs);
			$q  = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
                //排除自身节点
                if($row['id'] == $current_node_info['id'])
                {
                    continue;
                }
				$childs_array = explode(',', $row['childs']);
				$childs_array = array_diff($childs_array, $current_node_childs_array);
				if($childs_array)
				{
					$childs_str = implode(',', $childs_array);
					$this->db->query('UPDATE '.$this->nodeTable.' SET childs ="'.$childs_str.'" WHERE id = '.intval($row['id']));
				}
				//当前移动节点的父节点信息
				if($current_node_info['fid'] == $row['id'] && !$this->getNodesBrothers($current_node_info['fid'], true))
				{
					$this->set_is_last($row['id'],1);
				}
			}
		}
		//更新目标父节点的祖先节点的后代节点字段
		$this->setNodeID($this->nodeData['fid']);
		$father_node_info = $this->getOneNodeInfo();
		if($father_node_info)
		{
			$father_node_parents = $father_node_info['parents'];
			$father_node_childs = $father_node_info['childs'];
		}
		if($father_node_parents)
		{
            if($current_node_childs)
            {
                $this->db->query('UPDATE '.$this->nodeTable.' SET childs = CONCAT(childs,",'.$current_node_childs.'") WHERE id IN('.rtrim($father_node_parents, ',').')');
            }
            if($father_node_parents['is_last']!=0)
            {
                //设置目标父节点的is_last字段
                $this->set_is_last($this->nodeData['fid'], 0);
            }
		}
		//更新移动节点的后代节点祖先节点字段 替换为新父节点的祖先节点
		if($current_node_childs)
		{
            //操作节点的所有祖先节点
            $current_node_parents_array = explode(',', $current_node_parents);
            //排除自身节点
            $current_node_parents_array = array_diff($current_node_parents_array, (array)$current_node_info['id']);
			$sql = 'SELECT * FROM '.$this->nodeTable.' WHERE id IN('.$current_node_childs.')';
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
                $node_parents_array = explode(',', $row['parents']);
                //去除原祖先节点
				$_parents = array_diff($node_parents_array, $current_node_parents_array);
                //目标父节点是否为顶级 做不同的处理
                if($this->nodeData['fid']!=0)
                {
                    $new_parents_str = rtrim($father_node_info['parents'], ',') . ',' . implode(',', $_parents);
                }
                else
                {
                    $new_parents_str = implode(',', $_parents);
                }
                //计算后代节点的深度差
                $depath = count(explode(',', $new_parents_str));
				$this->db->query('UPDATE '.$this->nodeTable.' SET parents = "'.$new_parents_str.'",depath = '.$depath.' WHERE id = '.$row['id']);
			}
		}
        //更新节点内容
        $node_id = $this->nodeData['id'];
        unset($this->nodeData['id']);
        $sql = 'UPDATE '.$this->nodeTable.' SET ';
		foreach($this->nodeTreeFields as $key)
		{
            if(!isset($this->nodeData[$key])) continue;
			$sql .= "{$key} = '".$this->nodeData[$key]."',";
		}
		$this->db->query(trim($sql, ',') . ' WHERE id = ' . intval($node_id));
		//更新节点的回调方法
		if(method_exists($this, 'updateNodeCallback'))
		{
			//$sql = 'SELECT * FROM '.$this->nodeTable.' WHERE id = '.intval($node_id);
			//$nodeData = $this->db->query_first($sql);
			$this->updateNodeCallback($node_id);
		}
		return true;
	}
	//获取指定节点的子节点 在各节点文件的show方法中调用
	function getNodeChilds($conditions = '',  $need_prms = true)
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):500;
		$limit = " limit {$offset}, {$count}";
		$orderby = ' order by satisfy_score-unsatisfy_score desc ';
		$childs = array();
		if($this->nodeId < 0)
		{
			return $childs;
		}
		//###节点权限控制数据读取开始
		$fileds = 'id,name,fid,childs,parents,depath,is_last';
		if($need_prms && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//获取触发节点的操作和模块唯一标志
			if($this->nodeVar == 'column')
			{
				$auth_node = $this->user['prms']['publish_prms'];
				$auth_node_str = $auth_node ? implode(',', $auth_node) : '';
				$fileds .= ',site_id';
				if(!$auth_node_str && !$this->user['prms']['site_prms'])
				{
					return;
				}
			}
			else
			{
				$auth_node = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
				$auth_node_str = $auth_node && is_array($auth_node) ? implode(',', $auth_node) : '';
			
				if(!$auth_node_str)
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}
			$auth_node_parents = array();
			if($auth_node_str)
			{
				if($auth_node_str == -1)
				{
					$where = '';
				}
				else
				{
					$where = ' WHERE id IN('.$auth_node_str.')';
				}
				$sql = 'SELECT id,parents FROM '.$this->nodeTable . $where;
				$query = $this->db->query($sql);
				while($row = $this->db->fetch_array($query))
				{
					$auth_node_parents[$row['id']] = explode(',', $row['parents']);
				}
			}
		}
		//###节点权限控制数据读取开结束
		if(array_filter($this->excludeNode))
		{
			$conditions .= ' AND id NOT IN('.implode(',', $this->excludeNode).') ';
		}
		$sql = 'SELECT '.$fileds.' FROM '.$this->nodeTable.' WHERE fid = '.intval($this->nodeId) . $conditions . $orderby .$limit;
		//$this->errorOutput(var_export($auth_node_parents,1));
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$info_arr[] = $row;
			if($need_prms && $auth_node && $this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				###############非管理员用户数据过滤开始
				$row['is_auth'] = 0;
				//节点自身显示
				if($auth_node[0] == -1 || in_array($row['id'], $auth_node))
				{
					$row['is_auth'] = 1;
				}
				//
				if(!$row['is_auth'] && $auth_node_parents)
				{
					//父级节点显示
					foreach ($auth_node_parents as $auth_node_id=>$auth_node_parent)
					{
						if(in_array($row['id'], $auth_node_parent))
						{
							$row['is_auth'] = 2;
							break;
						}
					}
					//孩子节点显示
					if($auth_node[0] == -1 || array_intersect(explode(',', $row['parents']), $auth_node))
					{
						$row['is_auth'] = 3;
					}
				}
				if($this->nodeVar == 'column' && $this->user['prms']['site_prms'] && in_array($row['site_id'], $this->user['prms']['site_prms']))
				{
					$row['is_auth'] = 1;
				}
				if($row['is_auth'])
				{
					$this->addItem($row);
				}
				###############非管理员用户数据过滤结束
			}
			else
			{
				$this->addItem($row);
			}
			
		}
		$need_wufenlei = array('contribute_node');
		if(in_array($this->nodeVar, $need_wufenlei) && $this->input['_from_auth'])
		{
			$wufenlei =  array (
				    'id' => '0',
				    'name' => '无分类',
				    'fid' => '0',
				    'childs' => '0',
				    'parents' => '0',
				    'depath' => '1',
				    'is_last' => '1',
			);
			$this->addItem($wufenlei);
		}
	}
	//获取节点的父节点
	function getNodeParents()
	{
		$node_id = intval($this->input['node_id']);
		$sql = 'SELECT parents FROM '.$this->nodeTable.' WHERE id  = '.$node_id;
		$parents = $this->db->query_first($sql);
		if($parents)
		{
			$sql = 'SELECT * FROM '.$this->nodeTable.' WHERE id IN('.$parents['parents'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$parents_node[$row['id']] = $row;
			}
			$format_tree = $this->buildNodeTree($parents_node);
			if($format_tree)
			{
				foreach ($format_tree as $v)
				{
					$this->addItem($v);
				}
			}
		}
		$this->output();
	}
	//获取单节点的所有信息
	function getOneNodeInfo()
	{
		$sql = 'SELECT * FROM '.$this->nodeTable.' WHERE id = '.intval($this->nodeId);
		$ret = $this->db->query_first($sql);
		$ret['create_time'] = date('Y-m-d H:i:s');
		$ret['update_time'] = date('Y-m-d H:i:s');
		return $ret;
	}
	//获取多条记录信息 只返回ID和name字段 在节点文件的getSelectedNodes方法中调用
	function getMultiNodesInfo($ids = '')
	{
		$node_infos = array();
		if(!$ids)
		{
			return $node_infos;
		}
		$sql = 'SELECT id,name FROM '.$this->nodeTable.' WHERE id IN('.$ids.')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if(in_array($row['id'], $this->excludeNode))
			{
				continue;
			}
			$node_infos[$row['id']] = $row['name'];
		}
		$this->addItem($node_infos);
	}
	//获取所有兄弟节点 第二个参数控制检测是否存在兄弟节点 用于检测是否是末节点更新
	function getNodesBrothers($fid = 0, $detect=false)
	{
		$brothers = array();
		if(!$fid)
		{
			if(!$this->nodeId)
			{
				return $brothers;
			}
			$node = $this->getOneNodeInfo();
			$fid = $node['fid'];
		}
		if($fid)
		{
            if(!$detect)
            {
                $sql = 'SELECT * FROM '.$this->nodeTable.' WHERE fid = '.intval($fid);
                $q = $this->db->query($sql);
                while($row = $this->db->fetch_array($q))
                {
                    $brothers[$row['id']] = $row;
                }
                return $brothers;
            }
            else
            {
                $sql = 'SELECT count(*) as total FROM '.$this->nodeTable.' WHERE fid = '.intval($fid);
                $brothers = $this->db->query_first($sql);
                return $brothers['total'];
            }
		}
	}
	//更新节点的is_last字段
	private function set_is_last($id = 0, $value = 0)
	{
		$sql = 'UPDATE '.$this->nodeTable.' SET is_last = '.intval($value). ' WHERE id = '.intval($id);
		//$this->errorOutput($sql);
        $this->db->query($sql);
	}
	//初始化节点数据
	function initNodeData()
	{
		$this->nodeId = 0;
		$this->condition = '';
		$this->nodeTree = array();
		$this->nodeData = array();
	}
	//设置排除的节点
	function addExcludeNodeId($node_id = 0)
	{
		$this->excludeNode[] = $node_id;
	}
	//设置节点标识
	function setNodeVar($var = '')
	{
		$this->nodeVar = $var;
	}
	//设置节点标识
	function setCondition($var = '')
	{
		$this->condition = $var;
	}
	//设置节点字段
	function setNodeTreeFields($fields = array())
	{
		$this->nodeTreeFields = $fields;
	}
	//设置附加节点数据
	function setExtraNodeTreeFields($fields = array())
	{
		if($fields || is_array($fields))
		{
			$this->extraTreeFields = $fields;
		}
		//合并表字段
		$this->nodeTreeFields = array_merge($this->nodeTreeFields, (array)array_diff($this->extraTreeFields,$this->nodeTreeFields));
	}
	//设置节点存储表
	function setNodeTable($table = '')
	{
		$this->nodeTable = DB_PREFIX.$table;
	}
	function setNodeID($node_id = 0)
	{
		$this->nodeId = $node_id;
	}
	function setNodeData($node_data = array())
	{
		$this->nodeData = $node_data;
	}
	//获取节点树 默认取第一维 并且是格式化的节点数据 未测试（包含一下三个方法）
	function getNodeTree($depath = 0, $level = 0)
	{
		if(!$depath && !$level)
		{
			$sql = 'SELECT * FROM '.$this->nodeTable . ' WHERE depath >= 1';
		}
		else 
		{
			if($depath)
			{
				$sql = 'SELECT * FROM '.$this->nodeTable . ' WHERE depath >= '.intval($depath);
			}
			else 
			{
				$sql = 'SELECT * FROM '.$this->nodeTable . ' WHERE depath >= 1';
				
			}
			if($level)
			{
				$sql .= ' AND depath <= '.intval($level);
			}
		}
		$q = $this->db->query($sql);
		$node = array();

		while($row = $this->db->fetch_array($q))
		{
			$node[$row['id']] = $row;
		}
		//print_r($node);exit;

		if($node)
		{
			$node = $this->buildNodeTree($node);
			foreach ($node as $k=>$v)
			{
				$this->addItem($v);
			}
		}
	}
	//通过递归方式遍历节点数据
	private function buildNodeTree($data = array())
	{
		if(!$data)
		{
			return $data;
		}
		$this->nodeTree = array();
		$this->formatTree($data);
		return $this->nodeTree;
	}
	//递归格式化节点数据
	private function formatTree($data, $fid = 0)
	{
		foreach($data as $key=>$v)
		{
			if($v['fid'] == $fid)
			{
				$this->nodeTree[$v['id']] = $v;
				$this->formatTree($data, $v['id']);
			}
		}
	}
	//根据节点ID获取父节点的树 支持批量
	function getParentsTreeById($id = '', $need_prms=true, $ex_fields = '')
	{
		$table_fields = ' id,name,fid,parents,childs,is_last,depath '.$ex_fields;
		$tree = array();
		$id = is_array($id) ? implode(',', $id) : $id;
		if(!$id)
		{
			return $tree;
		}
		#####
		$auth_col_array = array();
		if($need_prms && $this->user['group_type'] > MAX_ADMIN_TYPE && $this->user['prms']['publish_prms'])
		{
			$auth_col_array = $this->user['prms']['publish_prms'];
		}
		#####
		$sql = 'SELECT '.$table_fields.' FROM '.$this->nodeTable .' WHERE id IN('.$id.')';
		$q = $this->db->query($sql);
		$nodes = $unique_parents = array();
		while($row = $this->db->fetch_array($q))
		{
			//$parents[$row['fid']][$row['id']] = $row['parents'];
			if(!$unique_parents[$row['fid']])
			{
				$unique_parents[$row['fid']] = $row['parents'];
			}
			//权限 检测 只要自身或者父级有一个节点授权 在发布时就允许显示checkbox
			if($need_prms && $auth_col_array && $this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				if(in_array($row['id'], $auth_col_array))
				{
					//自身授权
					$row['is_auth'] = 1;
				}
			}
			else
			{
				$row['is_auth'] = 1;
			}
			$nodes[$row['id']] = $row;
		}
		if($unique_parents)
		{
			$unique_parents_str = implode(',', array_unique(explode(',', implode(',', $unique_parents))));
			$sql = 'SELECT '.$table_fields.' FROM '.$this->nodeTable.' WHERE id in('.$unique_parents_str.')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				//权限 检测 只要自身或者父级有一个节点授权 在发布时就允许显示checkbox
				if($need_prms && $auth_col_array && $this->user['group_type'] > MAX_ADMIN_TYPE)
				{
					if(in_array($row['id'], $auth_col_array))
					{
						$row['is_auth'] = 1;
					}
				}
				else
				{
					$row['is_auth'] = 1;
				}
				$parents_node[$row['id']] = $row;
			}
		}
		$isFormatTree = array();
		if($nodes)
		{
			foreach ($nodes as $node_id=>$node_row)
			{
				if(!$isFormatTree[$node_row['fid']])
				{
					$node_parents_nodes = explode(',', $node_row['parents']);
					unset($node_parents_nodes[array_search($node_id, $node_parents_nodes)]);
					if($node_row['fid'] == 0)
					{
						//print_r($node_parents_nodes);exit;
					}
					//初始化递归数据
					$data = array();
					if($node_parents_nodes)
					{
						foreach($node_parents_nodes as $nid)
						{
							$data[$nid] = $parents_node[$nid];
						}
					}
					$isFormatTree[$node_row['fid']] = $this->buildNodeTree($data);
				}
				$tree[$node_id] = $isFormatTree[$node_row['fid']];
				//携带自身节点
				$tree[$node_id][$node_id] = $node_row;
			}
		}
		return $tree;
	}
	//根据节点id获取父节点树 并且格式化合并父节点
	function getMergeParentsTreeById($id = '')
	{
		$table_fields = ' id,name,fid,parents,childs,is_last,depath ';
		$id = is_array($id) ? implode(',', $id) : $id;
		if(!$id)
		{
			return $tree;
		}
		$sql = 'SELECT '.$table_fields.' FROM '.$this->nodeTable .' WHERE id IN('.$id.')';
		$q = $this->db->query($sql);
		$nodes = $unique_parents = array();
		while($row = $this->db->fetch_array($q))
		{
			//$parents[$row['fid']][$row['id']] = $row['parents'];
			if(!$unique_parents[$row['fid']])
			{
				$unique_parents[$row['fid']] = $row['parents'];
			}
			else
			{
				$unique_parents[$row['fid']] .= ','.$row['id'];
			}
			$nodes[$row['id']] = $row;
		}
		if($unique_parents)
		{
			$unique_parents_str = implode(',', array_unique(explode(',', implode(',', $unique_parents))));
			$sql = 'SELECT '.$table_fields.' FROM '.$this->nodeTable.' WHERE id in('.$unique_parents_str.')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$parents_node[$row['id']] = $row;
			}
		}
		$isFormatTree = array();
		if($nodes)
		{
			$isFormatTree = $this->buildNodeTree($parents_node);
		}
		//echo "<pre>";
		//print_r($isFormatTree);exit;
		return $isFormatTree;
	}
	//获取第一维数据 用于列表显示
	protected function getFirstLevelNode($condition = '',$fileds = '*')
	{
		if(!$this->nodeTable)
		{
			return array();
		}
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT '.$fileds.' FROM '.$this->nodeTable.' WHERE 1 AND fid = 0 ' . $condition . $limit;
		//$this->errorOutput($sql);
		$q = $this->db->query($sql);
		$this->setXmlNode('nodes','node');
		while($r = $this->db->fetch_array($q))
		{
			//$r['vod_sort_color'] = $this->settings['video_upload_type_attr'][intval($r['father'])]['color'];
			//$r['father'] = $this->settings['video_upload_type'][$r['father']];
			$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d H:i:s',$r['update_time']);
			$this->addItem($r);
		}
		$this->output();
	}
	//用于统计符合条件的节点总数
	public function count()
	{
		$sql = 'SELECT count(*) as total from '.$this->nodeTable.' WHERE 1 '.$this->get_condition();
		//$this->errorOutput($sql);
		$sort_total = $this->db->query_first($sql);
		echo json_encode($sort_total);	
	}
	//
	public function get_condition()
	{
		//
	}
	//获取节点列表 没有经过格式化 通常用于列表显示
	public function getNodesList($condition = '', $return = false)
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$order = "  ORDER BY satisfy_score-unsatisfy_score DESC  ";
		$sql = "SELECT * FROM ".$this->nodeTable."  WHERE 1 ".$condition.$order.$limit;
		$q = $this->db->query($sql);
		$this->setXmlNode($this->nodeVar , 'node');
		if($return)
		{
			$nodes = array();
		}
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d h:i:s',$r['update_time']);
			if($return)
			{
				$nodes[$r['id']] = $r;
			}
			else
			{
				$this->addItem($r);
			}
		}
		if($return)
		{
			return $nodes;
		}
	}
}
?>