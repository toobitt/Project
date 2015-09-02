<?php
define('MOD_UNIQUEID','livmedia_node');
require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class  vod_media_node_update extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		//检测是否具有管理权限
		$this->setNodeTable('vod_media_node');
		$this->setNodeVar('vod_media_node');
		$this->setExtraNodeTreeFields(array('color'));
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:类别名称,父类的id
	 *功能:添加视频类别
	 *返回值:新增类别的id
	 * */
	public function create()
	{
		//增加顶级分类权限判断
		if($this->input['fid'] == '0' && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->errorOutput(NO_PURVIEW);
		}
		if (!$this->input['name']  || trim(urldecode($this->input['name'])=='在这里添加标题'))
		{
			$this->errorOutput('请填写分类名称');
		}
		
		$brief = trim(urldecode($this->input['brief']));
		if($brief == '这里输入描述')
		{
			$brief = '';
		}
		
		$data = array(
            'ip'=>hg_getip(),
            'create_time'=>TIMENOW,
            'fid'=>intval($this->input['fid']),
            'update_time'=>TIMENOW,
			'color'=>urldecode($this->input['color']),
            'name'=>trim(urldecode($this->input['name'])),
            'user_name'=>trim(urldecode($this->user['user_name'])),
			'brief' => $brief,
		);
        $this->initNodeData();
        //设置新增或者需要更新的节点数据
        $this->setNodeData($data);
        //增加节点无需设置操作节点ID
        if($node_id = $this->addNode())
        {
			//记录日志
			//$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'create', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束
        	$data['id'] = intval($node_id);
        	$this->addItem($data);
        }
        $this->output();
	}

	/*参数:sort_id(类别id)
	 *功能:删除指定的类别
	 *返回值:所删除掉的视频的id
	 * */
	public function  delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		//删除顶级分类权限判断
		$sql = "SELECT fid FROM " .DB_PREFIX. "vod_media_node WHERE id = " .$this->input['id'];
		$q = $this->db->query_first($sql);
		if($q['fid'] == '0' && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->errorOutput(NO_PURVIEW);
		}
		$video_upload_type = array_keys($this->settings['video_upload_type']);//取出基本类别（视频类型）
		$arr = explode(',',urldecode($this->input['id']));
		foreach($arr AS $v)
		{
			if(in_array(intval($v),$video_upload_type))
			{
				$this->errorOutput('类型不能删除');
			}
		}
		//查看类别下面是否有视频,如果有,不能删除
		$sql = "SELECT * FROM " .DB_PREFIX. "vodinfo WHERE vod_sort_id = ".$this->input['id'];
		$q = $this->db->query_first($sql);
		if($q)
		{
			$this->errorOutput('该类下面还有视频,不能删除');
		}
	    $this->initNodeData();
	    $this->batchDeleteNode(urldecode($this->input['id']));
		$this->addItem(array('ids' => urldecode($this->input['id'])));
		$this->output();
	}
	
	/*参数:sort_id(类别id)
	 *功能:更新类别
	 *返回值:更新后类别的信息
	 * */
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		//修改顶级分类权限判断
		$sql = "SELECT fid FROM " .DB_PREFIX. "vod_media_node WHERE id = " .$this->input['id'];
		$q = $this->db->query_first($sql);
		if($q['fid'] == '0' && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->errorOutput(NO_PURVIEW);
		}
		$fields = ' SET  ';
		
		if (!$this->input['name'] || trim(urldecode($this->input['name'])) == '在这里添加标题')
		{
			$this->errorOutput('请填写分类名称');
		}
		
		$brief = trim(urldecode($this->input['brief']));
		if($brief == '这里输入描述')
		{
			$brief = '';
		}
		
        $data = array(
			'id' => intval($this->input['id']),
			'name' => trim(urldecode($this->input['name'])),
			'brief' => $brief,
			'update_time' =>TIMENOW,
            'user_name'=>$this->user['user_name'],
            'ip'=>  hg_getip(),
			'color'=>urldecode($this->input['color']),
            'fid'=>intval($this->input['fid']),
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
					$sql ="UPDATE " . DB_PREFIX . "vod_media_node SET";
		
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

$out = new vod_media_node_update();
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