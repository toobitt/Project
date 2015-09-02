<?php
require_once('./global.php');
require_once (CUR_CONF_PATH.'lib/fastInput.class.php');
define('MOD_UNIQUEID','contribute_fast_input');
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
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		//参数接收
		$data = array(
			'content'=>addslashes(trim($this->input['content'])),
			'sort_id'=>intval($this->input['sort_item']),
		);
		if (!$data['content']) 
		{
			$this->errorOutput('请输入快捷输入的内容');
		}
		if (!$data['sort_id'])
		{
			$this->errorOutput('请选择分类');
		}
		$check = $this->fastInput->check($data);
		if (!$check)
		{
			$this->errorOutput('快捷输入内容已存在');
		}
		/************权限验证开始**************/
		/*
		//节点验证
		//修改前
		$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		$node['nodes']['contribute_fastInput_sort'][$ret['sort_id']] = $ret['sort_id'];
		$this->verify_content_prms($node);
		//修改后
		if($data['sort_id'])
		{
			$node['nodes']['contribute_fastInput_sort'][$data['sort_id']] = $data['sort_id'];
		}
		else
		{
			$node['nodes']['contribute_fastInput_sort'][0] = 0;
		}
		$this->verify_content_prms($node);
		//是否修改他人数据
		$arr = array(
			'id'	  => $id,
			'user_id' => $ret['user_id'],
			'org_id'  => $ret['org_id'],
		);
		$this->verify_content_prms($arr);
		*/
		/************权限验证结束**************/

		//检测是否有数据更新
		$affected_rows = false;
		$sql = 'UPDATE '.DB_PREFIX.'fastInput SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE id = '.$id; 
		$query = $this->db->query($sql);		
		if ($this->db->affected_rows($query))
		{
			$affected_rows = true;
		}
		if ($affected_rows)
		{
			$additionalData = array(
						'update_time'		=> TIMENOW,
						'update_org_id'		=> $this->user['org_id'],
						'update_user_id'	=> $this->user['user_id'],
						'update_user_name' 	=> addslashes($this->user['user_name']),
						'update_ip'			=> $this->user['ip'],
				);
			$return = $this->fastInput->update($additionalData,$id);
			$res = array_merge($ret, $data, $additionalData);
			$this->addLogs('更新报料的快捷输入', $ret, $res, $ret['content'], $id, $ret['sort_id']);
		}
		$this->addItem($data);
		$this->output();
	}
	
	
	public function delete()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$ids = $this->input['id'];
	 	if (!$ids)
	 	{
	 		$this->errorOutput(NOID);	
	 	}
	 	/************权限验证开始**************/
	 	/*
	 	//节点验证
	 	$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput WHERE id IN ('.$ids.')';
	 	$query  = $this->db->query($sql);
	 	$nodes = array();
	 	$conInfor = array();
	 	while ($row = $this->db->fetch_array($query))
	 	{
	 		$nodes['nodes']['contribute_fastInput_sort'][$row['sort_id']] = $row['sort_id'];
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
		*/
	 	/************权限验证结束**************/
	 	$ret = $this->fastInput->delete($ids);
	 	//添加日子
	 	$this->addLogs('删除报料快捷输入', $conInfor, '', '删除报料快捷输入'.$ids);
	 	$this->addItem($ret);
	 	$this->output();
	}
	
	
	public function create()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$data = array(
			'content' => addslashes(trim($this->input['content'])),
			'sort_id' => intval($this->input['sort_item']),		
		);
		if (!$data['content']) 
		{
			$this->errorOutput('请输入快捷输入的内容');
		}
		if (!$data['sort_id'])
		{
			$this->errorOutput('请选择分类');
		}
		$ret = $this->fastInput->check($data);
		if (!$ret)
		{
			$this->errorOutput('快捷输入内容已存在');
		}
		/**************权限验证开始****************/
		/*
		if($data['sort_id'])
		{
			$nodes['nodes']['contribute_fastInput_sort'][$data['sort_id']] = $data['sort_id'];
		}
		else
		{
			$nodes['nodes']['contribute_fastInput_sort'][0] = 0;
		}
		$this->verify_content_prms($nodes);	
		*/
		/**************权限验证结束****************/
		
		$data = array(
			'content'     => addslashes(trim($this->input['content'])),
			'sort_id'	  => intval($this->input['sort_item']),
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'org_id'	  => $this->user['org_id'],
			'user_id'	  => $this->user['user_id'],
			'user_name'   => $this->user['user_name'],
			'ip'		  => $this->user['ip'],
		);
		$data['user_id'] = $data['user_id'] ? $data['user_id'] : 0;
		$data['user_name'] = $data['user_name'] ? $data['user_name'] : '匿名用户';
		$res = $this->fastInput->create($data);
		if ($res)
		{
			//添加日志
			$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput WHERE id ='.$id;
			$res = $this->db->query_first($sql);
			$this->addLogs('添加报料的快捷输入', '', $res, $res['content'], $res['id'], $res['sort_id']);
		}

		$this->addItem($res);
		$this->output();
	}
	

	public function audit()
	{
		
	}
	
	
	public function sort()
	{
		//$this->verify_content_prms();
		$ret = $this->drag_order('fastInput', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function publish()
	{
		
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
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