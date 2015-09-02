<?php
define('SCRIPT_NAME', 'question_node_update');
require_once('global.php');
require_once ROOT_PATH . 'frm/node_frm.php';
class question_node_update extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeTable('question_node');
		$this->setNodeVar('question_node');
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
		require_once(ROOT_PATH . 'lib/class/logs.class.php');
		$this->logs = new logs();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		
	}
	public function create()
	{
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
			//记录日志
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'create', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束
        	$data['id'] = intval($node_id);
        	$this->addItem($data['id']);
        }
        $this->output();
	}
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		if(!$this->delVoteChecked())
		{
			$this->errorOutput('删除分类失败，分类已经使用！');
		}
	    $this->initNodeData();
	    $this->batchDeleteNode(urldecode($this->input['id']));
		$this->addItem(array('ids' => urldecode($this->input['id'])));
		$this->output();
	}
	protected function delVoteChecked()
	{
		$id = urldecode($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "vote WHERE group_id IN(" . $id . ") ORDER BY id ASC";
		$q = $this->db->query($sql);
		$vote_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$vote_info[$row['id']]= $row['title'];
		}
		if ($vote_info)
		{
			return false;
		}
		return true;
	}
	public function update()
	{
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
		$this->addItem($data);
		$this->output();
	}
	//排序
	public function drag_order()
	{
		$sort = json_decode(html_entity_decode($this->input['sort']),true);
		if(!empty($sort))
		{
			foreach($sort as $key=>$val)
			{
				$data = array(
					'order_id' => $val,
				);
				if(intval($key) && intval($val))
				{
					$sql ="UPDATE " . DB_PREFIX . "question_node SET";
		
					$sql_extra=$space=' ';
					foreach($data as $k => $v)
					{
						$sql_extra .=$space . $k . "='" . $v . "'";
						$space=',';
					}
					$sql .=$sql_extra.' WHERE id='.$key;
					$this->db->query($sql);
				}
			}
		}
		$this->addItem('success');
		$this->output();
	}
}
include_once ROOT_PATH . 'excute.php';
?>