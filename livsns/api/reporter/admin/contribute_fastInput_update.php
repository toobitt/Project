<?php
require_once('global.php');
require_once (CUR_CONF_PATH.'lib/fastInput.class.php');
define('MOD_UNIQUEID','reporter_fast_input');
class  contribute_fastInput_update extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->fastInput = new fastInput();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function update() 
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
			return ;
		}
		//参数接收
		$data = array(
			'content'=>addslashes(trim($this->input['content'])),
			'sort_id'=>intval($this->input['sort_item']),
			'update_time'=>TIMENOW,
		);
		if (!$data['content']) {
			$this->errorOutput('请输入快捷输入的内容');
		}
		if (!$data['sort_id'])
		{
			$this->errorOutput('请选择分类');
		}
		/************权限验证开始**************/
		//修改前
		$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		$node['nodes']['reporter_fastInput_sort'][$ret['sort_id']] = $ret['sort_id'];
		$this->verify_content_prms($node);
		//修改后
		if($data['sort_id'])
		{
			$node['nodes']['reporter_fastInput_sort'][$data['sort_id']] = $data['sort_id'];
		}
		else
		{
			$node['nodes']['reporter_fastInput_sort'][0] = 0;
		}
		$this->verify_content_prms($node);
		//是否修改他人数据
		$arr = array(
			'id'=>$id,
			'user_id'=>$ret['user_id'],
			'org_id'=>$ret['org_id'],
		);
		$this->verify_content_prms($arr);
		/************权限验证结束**************/
		$this->fastInput->check($data);
		$this->fastInput->update($data,$id);
		$this->addItem($data);
		$this->output();
	}
	public function delete()
	{
	 	$id = $this->input['id'];
		if (!$id)
	 	{
	 		$this->errorOutput(NOID);	
	 	}
	 	/************权限验证开始**************/
	 	$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput WHERE id IN ('.$id.')';
	 	$query  = $this->db->query($sql);
	 	$nodes = array();
	 	$conInfor = array();
	 	while ($row = $this->db->fetch_array($query))
	 	{
	 		$nodes['nodes']['reporter_fastInput_sort'][$row['sort_id']] = $row['sort_id'];
	 		$conInfor[] = $row; 
	 	}
	 	$this->verify_content_prms($nodes);
		//能否修改他人数据
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id']));
			}
		}
	 	/************权限验证结束**************/
	 	$ret = $this->fastInput->delete($id);
	 	$this->addItem($ret);
	 	$this->output();
	}

	public function create()
	{
		//参数接收
		$data = array(
			'content'=>addslashes(trim($this->input['content'])),
			'sort_id'=>urldecode($this->input['sort_item']),		
		);
		
		if (!$data['content']) {
			$this->errorOutput('请输入快捷输入的内容');
		}
		if (!$data['sort_id'])
		{
			$this->errorOutput('请选择分类');
		}
		/**************权限验证开始****************/
		if($data['sort_id'])
		{
			$nodes['nodes']['reporter_fastInput_sort'][$data['sort_id']] = $data['sort_id'];
		}
		else
		{
			$nodes['nodes']['reporter_fastInput_sort'][0] = 0;
		}
		$this->verify_content_prms($nodes);	
		/**************权限验证结束****************/
		$ret = $this->fastInput->check($data);
		$data = array(
			'content'=>addslashes(trim(urldecode($this->input['content']))),
			'sort_id'=>urldecode($this->input['sort_item']),
			'create_time'=>TIMENOW,
			'org_id'=>$this->user['org_id'],
			'user_id'=>urldecode($this->user['user_id']),
			'user_name'=>urldecode($this->user['user_name']),
			'ip'=>urldecode($this->user['ip']),
			'update_time'=>TIMENOW
		);
		$data['user_id'] = $data['user_id'] ? $data['user_id'] : 0;
		$data['user_name'] = $data['user_name'] ? $data['user_name'] : '匿名用户';
		if ($ret)
		{		
			$res = $this->fastInput->create($data);
		}else {
			$this->errorOutput('快捷输入内容已存在');
		}
		$this->addItem($res);
		$this->output();
		
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
	public function audit()
	{
		
	}
	public function sort()
	{
		$this->verify_content_prms();
		$ret = $this->drag_order('fastInput', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	public function publish()
	{
		
	}
}

$out = new contribute_fastInput_update();
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