<?php
require_once('global.php');
define('MOD_UNIQUEID','market_product_sort');//模块标识
require_once(ROOT_PATH . 'frm/node_frm.php');
class market_product_sort_update extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
        $this->setNodeTable('market_product_sort');
        $this->setNodeVar('market_product_sort');
        $this->setExtraNodeTreeFields(array('color'));
		//权限
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if (!$this->user['prms']['app_prms']['supermarket']['setting'])
			{
				$this->errorOutput('您没有权限操作配置');
			}
		}
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
		}
		if (!$this->input['name'])
		{
			$this->errorOutput(NOSORTNAME);
		}
        $data = array(
			'id' => intval($this->input['id']),
			'name' => trim($this->input['name']),
			'brief' => trim($this->input['brief']),
			'update_time' =>TIMENOW,
            'user_name'=>$this->user['user_name'],
        	'color'=>$this->input['color'],
            'ip'=>  hg_getip(),
            'fid'=>$this->input['fid'],
		);
        //初始化
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

	public function delete()
	{
	    if (!$this->input['id'])
	    {
	    	$this->errorOutput(NOID);
	    }
		//查询分类
		$sql = 'SELECT * FROM '.DB_PREFIX.'market_product_sort WHERE id IN ('.$this->input['id'].')';
		
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim($this->user['user_name']),
					'title' => $row['name'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['market_product_sort'] = $row;
		}
		 $this->initNodeData();
		//判断是否成功删除
		if($this->batchDeleteNode($this->input['id']))
		{
			$this->addItem(array('id' => $this->input['id']));
		}
		
		$this->output();
	}
	
	public function create()
	{
		if (!$this->input['name']  || trim($this->input['name'])=='在这里添加标题')
		{
			$this->errorOutput(NOSORTNAME);
		}
		$data = array(
            'ip'			=>hg_getip(),
            'create_time'	=>TIMENOW,
            'fid'			=>$this->input['fid'],
            'update_time'	=>TIMENOW,
			'color'			=>$this->input['color'],
            'name'			=>trim($this->input['name']),
            'brief'			=>trim($this->input['brief']),
            'user_name'		=>trim($this->user['user_name'])
		);
        $this->initNodeData();
        //设置新增或者需要更新的节点数据
        $this->setNodeData($data);
        //增加节点无需设置操作节点ID
        if($nid = $this->addNode())
        {
			$data['id'] = $nid;
        	$this->addItem($data);
        }
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
					$sql ="UPDATE " . DB_PREFIX . "market_product_sort SET";
		
					$sql_extra = $space=' ';
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
		$this->errorOutput(UNKNOW);
	}
	
}
$out = new market_product_sort_update();
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