<?php
require_once('global.php');
require(ROOT_PATH . 'frm/node_frm.php');
class  site_col_con_sort_update extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();

        //节点基类的方法
        //初始化
        //设定节点表
        $this->setNodeTable('site_col_con_sort');
        //设置节点标识
        $this->setNodeVar('site_col_con_sort');
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function update() {
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
			return ;
		}
		if (!$this->input['sort_name'])
		{
			$this->errorOutput('请填写分类名称');
		}
        $data = array(
			'id' => intval($this->input['id']),
			'name' => trim(urldecode($this->input['sort_name'])),
			'brief' => trim(urldecode($this->input['sort_desc'])),
			'update_time' =>TIMENOW,
            'user_name'=>$this->user['user_name'],
            'ip'=>  hg_getip(),
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
	public function delete(){
	    if (!$this->input['id'])
	    {
	    	$this->errorOutput(NOID);
			return ;
	    }
        $this->initNodeData();
		//判断是否成功删除
		if($this->batchDeleteNode($this->input['id']))
		{
			$this->addItem('success');
		}
		$this->output();

	}
	public function create(){
		file_put_contents('cccccc.txt','ccccc');
	}
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}

$out = new site_col_con_sort_update();
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