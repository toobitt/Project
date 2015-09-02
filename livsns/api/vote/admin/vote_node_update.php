<?php
define('MOD_UNIQUEID', 'vote_node');
require_once('global.php');
require_once ROOT_PATH . 'frm/node_frm.php';
class vote_node_update extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		//检测是否具有配置权限
        $this->verify_setting_prms();
		$this->setNodeTable('vote_node');
		$this->setNodeVar('vote_node');
		$this->setExtraNodeTreeFields(array('color'));
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$this->verify_create_node(intval($this->input['fid']));
		$name = trim(urldecode($this->input['name']));
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}

		if (urldecode($this->input['brief']) == '这里输入描述')
		{
			$this->input['brief'] = '';
		}
		
		$data = array(
			'name' => $name,
			'brief' => trim(urldecode($this->input['brief'])),
			'fid'=>intval($this->input['fid']),
			//'admin_id' => intval($this->user['user_id']),
			'user_name' => urldecode($this->user['user_name']),
		    'color'     => urldecode($this->input['color']),
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip()
		);
		//$this->errorOutput(var_export($data,1));
		$this->initNodeData();
        //设置新增或者需要更新的节点数据
        $this->setNodeData($data);
        //增加节点无需设置操作节点ID
        if($node_id = $this->addNode())
        {
        	$data['id'] = intval($node_id);
        	$this->addLogs('创建投票节点','',$data,$data['name']);
        	$this->addItem($data);
        }
        $this->output();
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
 		//非管理员不能删除主分类
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
	    	//查询主分类
	    	$sql = 'SELECT fid FROM '.DB_PREFIX.'vote_node WHERE id='.$this->input['id'];
	    	$fid = $this->db->query_first($sql);
	    	if(!$fid['fid'])
	    	{
	    		$this->errorOutput(NO_PRIVILEGE);
	    	}
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$sql = 'SELECT id FROM '.DB_PREFIX.'vote_question WHERE node_id IN ('.$this->input['id'].')';
		$parkinfo = $this->db->query_first($sql);
		if($parkinfo)
		{
			$this->errorOutput('该分类下有内容！');
		}
	    
		$this->initNodeData();
	    $ret = $this->batchDeleteNode(urldecode($this->input['id']));
	    if ($ret)
	    {
	    	$sql = "UPDATE " . DB_PREFIX . "vote_question SET node_id=0 WHERE node_id IN (" . urldecode($this->input['id']) . ")";
			$this->db->query($sql);
	    }
	     $this->addLogs('删除投票节点','','','删除投票节点+' . $this->input['id']);
		$this->addItem(array('ids' => urldecode($this->input['id'])));
		$this->output();
	}
	
	public function delVoteChecked()
	{
		$node_id = urldecode($this->input['id']);
		if (!$node_id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$sql = "SELECT id,title FROM " . DB_PREFIX . "vote_question WHERE node_id IN(" . $node_id . ") ORDER BY id ASC";
		$q = $this->db->query($sql);
		$vote_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$vote_info[$row['id']]= $row['title'];
		}
		
		if (!empty($vote_info))
		{
			$vote_info = implode(' ', $vote_info);
		}
		
		$this->addItem($vote_info);
		$this->output();
	}
	
	public function update()
	{
		//$this->verify_create_node(intval($this->input['fid']));
		$id = intval($this->input['id']);
		if (!id)
		{
			$this->errorOutput('未传入ID');
		}
		$name = trim(urldecode($this->input['name']));
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}

		$data = array(
			'id'=>$id,
			'name' => $name,
			'brief' => trim(urldecode($this->input['brief'])),
		 	'fid'=>intval($this->input['fid']),
			//'admin_id' => intval($this->user['user_id']),
			'color'     => urldecode($this->input['color']),
			'user_name' => urldecode($this->user['user_name']),
			'update_time' => TIMENOW
		);
		//$this->errorOutput(var_export($data,1));
		$this->initNodeData();
        //设置新增或者需要更新的节点数据
        $this->setNodeData($data);
        //设置操作的节点ID
        $this->setNodeID($data['id']);
        //更新方法
        $this->updateNode();
		$this->addLogs('修改投票节点','',$data,$data['name']);
		$this->addItem($data);
		$this->output();
	}
	
	public function sort()
	{
		$sort = json_decode(html_entity_decode($this->input['sort']),true);
		if(!empty($sort))
		{
			$id = array();
			foreach($sort as $key=>$val)
			{
				$data = array(
					'order_id' => $val,
				);
				if(intval($key) && intval($val))
				{
					$sql ="UPDATE " . DB_PREFIX . "vote_node SET";
		
					$sql_extra=$space=' ';
					foreach($data as $k => $v)
					{
						$sql_extra .=$space . $k . "='" . $v . "'";
						$space=',';
					}
					$sql .=$sql_extra.' WHERE id='.$key;
					$this->db->query($sql);
				}
				$id[] = $key;
			}
		}
		$this->addLogs('投票节点排序','','','投票节点排序+' . implode(',',$id));
		$this->addItem('success');
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$out = new vote_node_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>