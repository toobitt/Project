<?php
require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','cook_style');//模块标识
require_once(ROOT_PATH . 'lib/class/logs.class.php');
require_once(ROOT_PATH . 'lib/class/recycle.class.php');
class cook_style_update extends nodeFrm
{
	var $logs;
	var $recycle;
	public function __construct()
	{
		parent::__construct();
		$this->logs = new logs();
		$this->recycle = new recycle();
        $this->setNodeTable('cook_style');
        $this->setNodeVar('cook_style');
        $this->setExtraNodeTreeFields(array('color'));
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
			$this->errorOutput(NOSORTNAME);
		}
        $data = array(
			'id' => intval($this->input['id']),
			'name' => trim(urldecode($this->input['name'])),
			'brief' => trim(urldecode($this->input['brief'])),
			'update_time' =>TIMENOW,
            'user_name'=>$this->user['user_name'],
        	'color'=>urldecode($this->input['color']),
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
		//记录日志
		/*
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'update', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		*/
		//记录日志结束
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
		$sql = 'SELECT * FROM '.DB_PREFIX.'cook_style WHERE id IN ('.urldecode($this->input['id']).')';
		
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['name'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['cook_style'] = $row;
		}
		 $this->initNodeData();
		//判断是否成功删除
		if($this->batchDeleteNode($this->input['id']))
		{
			//记录日志
			/*
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束
			//放入回收站
			foreach($data2 as $key => $value)
			{
				$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
			}
			*/
			$this->addItem(array('id' => urldecode($this->input['id'])));
		}
		
		$this->output();
	}
	
	public function create()
	{
		if (!$this->input['name']  || trim(urldecode($this->input['name'])=='在这里添加标题'))
		{
			$this->errorOutput(NOSORTNAME);
		}
		$data = array(
            'ip'=>hg_getip(),
            'create_time'=>TIMENOW,
            'fid'=>$this->input['fid'],
            'update_time'=>TIMENOW,
			'color'=>urldecode($this->input['color']),
            'name'=>trim(urldecode($this->input['name'])),
            'brief'=>trim(urldecode($this->input['brief'])),
            'user_name'=>trim(urldecode($this->user['user_name']))
		);
        $this->initNodeData();
        //设置新增或者需要更新的节点数据
        $this->setNodeData($data);
        //增加节点无需设置操作节点ID
        if($nid = $this->addNode())
        {
			//记录日志
			/*
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'create', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			*/
			//记录日志结束
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
					$sql ="UPDATE " . DB_PREFIX . "cook_style SET";
		
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
$out = new cook_style_update();
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