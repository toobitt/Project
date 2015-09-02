<?php
require_once('./global.php');
require_once(CUR_CONF_PATH . 'lib/fastInputSort.class.php');
define('MOD_UNIQUEID','contribute_fast_input_sort');
class  contribute_fastInput_sort_update extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->sort = new fastInputSort();
	}

	public function __destruct()
	{
		parent::__destruct();
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
		if (!$this->input['name'])
		{
			$this->errorOutput('请填写分类名');
		}
		$check = $this->sort->check($data[name]);
		if (!$check)
		{
			$this->errorOutput('该分类已存在!');
		}
		//参数接受
		$data = array(
			'name'			=> addslashes(trim($this->input['name'])),
			'brief'			=> addslashes(trim($this->input['brief'])),
			'create_time'	=> TIMENOW,
			'org_id'		=> $this->user['org_id'],
			'user_id'		=> $this->user['user_id'],
			'user_name'		=> $this->user['user_name'],
			'ip'			=> $this->user['ip'],
			'update_time' 	=> TIMENOW,
		);
		$data['user_id'] = $data['user_id'] ? $data['user_id'] : 0;
		$data['user_name'] = $data['user_name'] ? $data['user_name'] : '匿名用户';
		
		$id = $this->sort->create($data);
		if ($id)
		{
			//添加日志
			$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput_sort WHERE id = '.$id;
			$res = $this->db->query_first($sql);
			$this->addLogs('添加报料快捷输入分类', '', $res, $res['name'],$id,'');
			$this->addItem('sucess');
		}
		$this->output();	
	}
	
	
	public function delete()
	{
		/**************权限控制开始**************/
		/*
		$this->verify_content_prms();
		$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput_sort WHERE id IN ('.$this->input['id'].')';
		$query = $this->db->query($sql);
		$conInfor = array();
		while ($row = $this->db->fetch_array($query))
		{
			$conInfor[] = $row;
		}
		//能否修改他人数据
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id']));
			}
		}
		*/
		/**************权限控制结束**************/
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$id = trim($this->input['id']);
		$r = $this->sort->checkcon($id);
		if (!$r)
		{
			$this->errorOutput('此分类下有相关内容，请先删除相关内容');
		}
		$ret = $this->sort->delete($id);
		//添加日志
		$this->addLogs('删除报料快捷输入', $conInfor, '','删除报料快捷输入'.$id);
		$this->addItem($id);
		$this->output();	
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
		/**************权限控制开始**************/
		/*
		$this->verify_content_prms();
		$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput_sort WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		$arr = array(
					'id'	  => $id,
					'user_id' => $ret['user_id'],
					'org_id'  => $ret['org_id'],
				);
		$this->verify_content_prms($arr);
		*/
		/**************权限控制结束**************/
		//参数接收
		$data = array(
			'brief'	=> trim($this->input['brief']),
		);
		$affected_rows = false;
		$sql = 'UPDATE '.DB_PREFIX.'fastInput_sort SET brief = "'. addslashes($data['brief']) . '" WHERE id = '.$id;
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
			$return = $this->sort->update($additionalData, $id);
			$res = array_merge($ret,$data,$additionalData);
			$this->addLogs('更新报料快捷输入分类', $ret, $res, $ret['name']);
		}
		$this->addItem($data);
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
		//$this->verify_content_prms();
		$ret = $this->drag_order('fastInput_sort', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function publish()
	{
		
	}
}

$out = new contribute_fastInput_sort_update();
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