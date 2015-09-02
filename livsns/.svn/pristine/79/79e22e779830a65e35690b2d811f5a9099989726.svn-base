<?php
require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class movie_node_update extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		require_once(ROOT_PATH . 'lib/class/logs.class.php');
        $this->logs = new logs();
		$this->setNodeTable('movie_node');
		$this->setNodeVar('movie_node');
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
		if(!$this->input['father_node_id'])
		{
			$this->errorOutput('不能直接添加类型，请选择一个类型或者一个父级分类之后再添加分类');
		}
		
		if (!$this->input['sort_name']  || trim(urldecode($this->input['sort_name'])=='在这里添加标题'))
		{
			$this->errorOutput('请填写分类名称');
		}
		
		$brief = trim(urldecode($this->input['sort_desc']));
		if($brief == '这里输入描述')
		{
			$brief = '';
		}
		
		$data = array(
            'ip'=>hg_getip(),
            'create_time'=>TIMENOW,
            'fid'=>$this->input['father_node_id'],
            'update_time'=>TIMENOW,
			'color'=>urldecode($this->input['color']),
            'name'=>trim(urldecode($this->input['sort_name'])),
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
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'create', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束
        	$data['id'] = intval($node_id);
        	$this->addItem($data['id']);
        }
        $this->output();
	}

	/*参数:sort_id(类别id)
	 *功能:删除指定的类别
	 *返回值:所删除掉的视频的id
	 * */
	public function  delete()
	{
		if(!$this->input['sort_id'])
		{
			$this->errorOutput(NOID);
		}
		
		$video_upload_type = array_keys($this->settings['video_upload_type']);//取出基本类别（视频类型）
		$arr = explode(',',urldecode($this->input['sort_id']));
		foreach($arr AS $v)
		{
			if(in_array(intval($v),$video_upload_type))
			{
				$this->errorOutput('类型不能删除');
			}
		}
		
	    $this->initNodeData();
	    $this->batchDeleteNode(urldecode($this->input['sort_id']));
		$this->addItem(array('ids' => urldecode($this->input['sort_id'])));
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
		
		$fields = ' SET  ';
		
		if (!$this->input['sort_name'] || trim(urldecode($this->input['sort_name'])) == '在这里添加标题')
		{
			$this->errorOutput('请填写分类名称');
		}
		
		$brief = trim(urldecode($this->input['sort_desc']));
		if($brief == '这里输入描述')
		{
			$brief = '';
		}
		
        $data = array(
			'id' => intval($this->input['id']),
			'name' => trim(urldecode($this->input['sort_name'])),
			'brief' => $brief,
			'update_time' =>TIMENOW,
            'user_name'=>$this->user['user_name'],
            'ip'=>  hg_getip(),
			'color'=>urldecode($this->input['color']),
            'fid'=>$this->input['father_node_id'],
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
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}

$out = new movie_node_update();
$action = $_INPUT['a'];
if (!method_exists( $out , $action))
{
	$action = 'unknow';
}
$out->$action();

?>