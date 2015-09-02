<?php
require_once('global.php');
require(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','org');
require_once(ROOT_PATH . 'lib/class/logs.class.php');
class  admin_org_update extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->verify_setting_prms();
        $this->setNodeTable('admin_org');
        $this->setNodeVar('admin_org');
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function update() 
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
			return ;
		}
		if (!$this->input['name'])
		{
			$this->errorOutput('请填写分类名称');
		}
		
        $data = array(
			'id' => intval($this->input['id']),
			'name' => trim(urldecode($this->input['name'])),
			'brief' => trim(urldecode($this->input['brief'])),
			'update_time' =>TIMENOW,
            'user_name'=>$this->user['user_name'],
            'ip'=>  hg_getip(),
            'fid'=>intval($this->input['fid']),
		);
		$this->verify_create_node($data['fid']);
        $this->initNodeData();
        //设置新增或者需要更新的节点数据
        $this->setNodeData($data);
        //设置操作的节点ID
        $this->setNodeID($data['id']);
        //更新方法
        $this->updateNode();
        //返回快捷输入分类名称
		$this->addItem($data);
		$this->output();
	}
	public function delete(){
	    if (!$this->input['id'])
	    {
	    	$this->errorOutput(NOID);
			return ;
	    }
	    $sql = 'SELECT id FROM ' . DB_PREFIX . 'admin WHERE father_org_id IN ('.urldecode($this->input['id']).')';
	    //$this->errorOutput($sql);
	    if($this->db->query_first($sql))
	    {
	    	$this->errorOutput("该组织下存在用户，无法删除");
	    }
		//查询分类
		$sql = 'SELECT * FROM '.DB_PREFIX.'admin_org WHERE id IN ('.urldecode($this->input['id']).')';
		
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['name'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['sort'] = $row;
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		if($res['sucess'])
		{
			$this->initNodeData();
			$this->batchDeleteNode($this->input['id']);
			$this->addItem('success');
		}	
		$this->output();
	}
	public function create()
	{
		if (!$this->input['name'])
		{
			$this->errorOutput('请填写组织名称');
		}
		$data = array(
            'ip'=>hg_getip(),
            'create_time'=>TIMENOW,
            'fid'=>intval($this->input['fid']),
            'update_time'=>TIMENOW,
            'name'=>trim(urldecode($this->input['name'])),
            'brief'=>trim(urldecode($this->input['brief'])),
            'user_name'=>trim(urldecode($this->user['user_name']))
		);
		$this->verify_create_node($data['fid']);
        $this->initNodeData();
        //设置新增或者需要更新的节点数据
        $this->setNodeData($data);
        //增加节点无需设置操作节点ID
        $id = $this->addNode();
        $data['id'] = $id;
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
					$sql ="UPDATE " . DB_PREFIX . "admin_org SET";
		
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

$out = new admin_org_update();
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