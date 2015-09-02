<?php
require(ROOT_PATH . 'frm/node_frm.php');
class sortUpdateApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeTable('catalog');
		$this->setNodeVar('article_sort');
		
		$this->setExtraNodeTreeFields(array('issue_id'));
	}

	public function __destruct()
	{
		parent::__destruct();
	}
  
	public function create()
	{
		if (!$this->input['sort']  || trim(urldecode($this->input['sort'])=='在这里添加标题'))
		{
			return false;
		}
		$data = array(
            'ip'				=>	hg_getip(),
            'create_time'		=>	TIMENOW,
            'fid'				=>	$this->input['father_node_id'],
			'issue_id' 			=> 	intval($this->input['issue_id']),
            'update_time'		=>	TIMENOW,
            'name'				=>	trim(urldecode($this->input['sort'])),
            'brief'				=>	trim(urldecode($this->input['sort_desc'])),
            'user_name'			=>	trim(urldecode($this->user['user_name'])),
		);
        $this->initNodeData();
        //设置新增或者需要更新的节点数据
        $this->setNodeData($data);
        //增加节点无需设置操作节点ID
        if($new_id = $this->addNode())
        {
            return($new_id);
        }
	}

	public function update()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput('ID不存在');
			return false;
		}
		if (!$this->input['sort_name'])
		{
			$this->errorOutput('请填写分类名称');
		}
        $data = array(
			'id' 			=> 	intval($this->input['id']),
			'name' 			=> 	trim(urldecode($this->input['sort_name'])),
			'brief' 		=> 	trim(urldecode($this->input['sort_desc'])),
			'update_time' 	=>	TIMENOW,
            'user_name'		=>	$this->user['user_name'],
            'ip'			=>  hg_getip(),
            'fid'			=>	$this->input['father_node_id'],
		);
        //初始化
        $this->initNodeData();
        //设置新增或者需要更新的节点数据
        $this->setNodeData($data);
        //设置操作的节点ID
        $this->setNodeID($data['id']);
        //更新方法
        $this->updateNode();
		return $data;
	}


    /**
	* 根据ID删除栏目
	* @ name delete
	* @ access public
	* @ category hogesoft
	* @ copyright hogesoft
	* @ param $id int 栏目id
	*/
	public function delete()
	{
	    if(!$this->input['id'])
	    {
	    	$this->errorOutput(NOID);
			return ;
	    }
		$id = urldecode($this->input['id']);
		$id = explode(',',$id);
		foreach($id as $k => $v)
		{
			$this->setNodeID($v);
			$node_info=$this->getOneNodeInfo();
			$data[$node_info['id']] = array(
				'title' => $node_info['name'],
				'delete_people' => trim(urldecode($this->user['user_name'])),
				'cid' => $node_info['id'],
			);
			$data[$node_info['id']]['content']['sort'] = $node_info;
		}
        $node_info = $this->initNodeData();
		//判断是否成功删除
		if($this->batchDeleteNode($this->input['id']))
		{
			return 'success';
		}
		
	}
}

?>