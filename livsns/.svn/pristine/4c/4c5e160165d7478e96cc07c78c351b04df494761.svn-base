<?php
define('MOD_UNIQUEID', 'card_node');
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
		$this->setNodeTable('card_node');
		$this->setNodeVar('card_node');
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
		$this->verify_delete_node($this->input['id']);
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}

		$this->initNodeData();
	    $ret = $this->batchDeleteNode(urldecode($this->input['id']));
	    if ($ret)
	    {
	    	$sql = "UPDATE " . DB_PREFIX . "channel SET node_id=0 WHERE node_id IN (" . trim($this->input['id']) . ")";
			$this->db->query($sql);
	    }
		$this->addItem(array('ids' => urldecode($this->input['id'])));
		$this->output();
	}
	
	
	public function update()
	{
		$this->verify_create_node(intval($this->input['fid']));
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
	
	public function sort()
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
					$sql ="UPDATE " . DB_PREFIX . "card_node SET";
		
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