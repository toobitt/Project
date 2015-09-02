<?php
define('MOD_UNIQUEID','member_node');//模块标识
define('SCRIPT_NAME', 'member_node_update');
require_once('global.php');
require_once ROOT_PATH . 'frm/node_frm.php';
class member_node_update extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeTable('member_node');
		$this->setNodeVar('member_node');
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
		require_once(ROOT_PATH . 'lib/class/logs.class.php');
		$this->logs = new logs();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}

		if (trim($this->input['brief']) == '这里输入描述')
		{
			$this->input['brief'] = '';
		}

		$data = array(
			'name' 		  => $name,
			'brief' 	  => trim($this->input['describes']),
			'fid'		  =>intval($this->input['father_node_id']),
			//'admin_id' => intval($this->user['user_id']),
			'user_name'   => $this->user['user_name'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' 		  => hg_getip()
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
		
	    $this->initNodeData();
	    $ret = $this->batchDeleteNode(trim($this->input['id']));
	    
	    if ($ret)
	    {
	    	$sql = "UPDATE " . DB_PREFIX . "member SET node_id=0 WHERE node_id IN (" . trim($this->input['id']) . ")";
			$this->db->query($sql);
	    }
	    
		$this->addItem(array('ids' => trim($this->input['id'])));
		$this->output();
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if (!id)
		{
			$this->errorOutput('未传入ID');
		}
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}

		$data = array(
			'id'			=>$id,
			'name' 			=> $name,
			'brief' 		=> trim($this->input['describes']),
		 	'fid'			=>intval($this->input['father_node_id']),
			//'admin_id' => intval($this->user['user_id']),
			'user_name' 	=> trim($this->user['user_name']),
			'update_time' 	=> TIMENOW
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
}
include_once ROOT_PATH . 'excute.php';
?>