<?php
define('MOD_UNIQUEID', 'channel_node');
require_once('global.php');
require_once ROOT_PATH . 'frm/node_frm.php';
class channel_node_update extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$this->setNodeTable('channel_node');
		$this->setNodeVar('channel_node');
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

		$this->initNodeData();
	    $ret = $this->batchDeleteNode(urldecode($this->input['id']));
	    if ($ret)
	    {
	    	$sql = "UPDATE " . DB_PREFIX . "channel SET node_id=0 WHERE node_id IN (" . urldecode($this->input['id']) . ")";
			$this->db->query($sql);
	    }
		$this->addItem(array('ids' => urldecode($this->input['id'])));
		$this->output();
	}
	
	public function check_delete()
	{
		$node_id = urldecode($this->input['id']);
		if (!$node_id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$sql = "SELECT id,name FROM " . DB_PREFIX . "channel WHERE node_id IN(" . $node_id . ") ORDER BY id ASC";
		$q = $this->db->query($sql);
		$vote_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$vote_info[$row['id']]= $row['name'];
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
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$out = new channel_node_update();
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