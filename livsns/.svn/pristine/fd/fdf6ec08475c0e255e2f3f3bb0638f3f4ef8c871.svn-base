<?php
require_once './global.php';
require_once(CUR_CONF_PATH. 'lib/account.class.php');
define('MOD_UNIQUEID','contribute_user');//模块标识
class  contributeUserUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->conAccount = new contributeAccount();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		
	}
	public function update(){
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$id =intval($this->input['id']);
		$data = array(
			'nickname'=>addslashes($this->input['nickname']),
			'con_sort'=>intval($this->input['con_sort'])
		);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		/**************权限控制开始**************/
		/*
		$this->verify_content_prms();
		$sql = 'SELECT * FROM '.DB_PREFIX.'reporter WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		$arr = array(
					'id'=>$id,
					'user_id'=>$ret['user_id'],
					'org_id'=>$ret['org_id'],
				);
		$this->verify_content_prms($arr);
		*/
		/**************权限控制结束**************/
		if (!$data['nickname'])
		{
			$this->errorOutput('请填写用户名');
		}
		if (!$data['con_sort'])
		{
			$this->errorOutput('请选择分类');
		}		
		$uid = $this->conAccount->update($data, $id);
		$this->addItem($uid);
		$this->output();
		
	}
	public function delete(){
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$id = $this->input['id'];
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		/**************权限控制开始**************/
		/*
		$this->verify_content_prms();
		$sql = 'SELECT * FROM '.DB_PREFIX.'user_token WHERE id IN ('.$id.')';
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
		$id = $this->conAccount->delete($id);
		//添加日志
		$this->addLogs('删除报料帐户', $conInfor, '','删除报料用户'.$id);
		$this->addItem($id);
		$this->output();
	}

	public function audit()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		if(empty($this->input['id']))
		{
			$this->errorOutput(NOID); 
		}
		/**************权限控制开始**************/
		//$this->verify_content_prms();
		/**************权限控制结束**************/
		$id = $this->input['id'];
		$sql = "UPDATE " . DB_PREFIX ."user_token SET audit = 1 WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		$arr = array('audit' => 1,'id'=> $id);
		$this->addItem($arr);
		$this->output();
	}
	
	
	public function back()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		if(empty($this->input['id']))
		{
			$this->errorOutput(NOID);
		}
		/**************权限控制开始**************/
		//$this->verify_content_prms();
		/**************权限控制结束**************/
		$id = $this->input['id'];
		$sql = "UPDATE " . DB_PREFIX ."user_token SET audit = 0 WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		$arr = array('audit' => 0, 'id' => $id);
		$this->addItem($arr);
		$this->output();
	}
	
	public function push_queue()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$id = $this->input['id'];
		$arr = array();
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->conAccount->check_auth($id);
		if (!$ret)
		{
			$arr = array(
				'error'=>1,
				'msg'=>'授权过期',
			);
		}else {
			$info = $this->conAccount->get_user_info($id);
			//准备放入队列
			if ($info['audit']==1)
			{
				$data = array(
				'id'=>$info['id'],
				'appid'=>$info['appid'],
				'plat_id'=>$info['plat_id'],
				'plat_token'=>$info['plat_token'],
				'since_id'=>$info['since_id'],
				'since_time'=>TIMENOW,
				'weight'=>1,       //优先获取
				'con_sort'=>$info['con_sort'],
				'name'=>$info['name'],
				);
				$this->conAccount->storedIntoDB($data, 'user_queue');
				$arr = array(
					'error'=>2,
					'msg'=>'正在获取...',
				);
			}else {
				$arr = array(
					'error'=>3,
					'msg'=>'帐号未审核',
				);
			}
		}
		$this->addItem($arr);
		$this->output();
		
	}
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
	public function sort()
	{
		//$this->verify_content_prms();
		$ret = $this->drag_order('user_token', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	public function publish()
	{
		
	}
}

$out = new contributeUserUpdate();
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