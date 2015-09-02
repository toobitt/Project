<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/reporter.class.php';
define('MOD_UNIQUEID','reporter_lib');//模块标识
class reporterUpdateApi extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		$this->reporter = new reporter();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		/**************权限控制开始**************/
		$this->verify_content_prms();
		/**************权限控制结束**************/
		$data = array(
			'account'=>trim($this->input['account']),
			'password'=>trim($this->input['password']),
			'brief'=>trim($this->input['brief']),
			'role_id'=>$this->input['role_id'],
			'domain'=>trim($this->input['domain']),
			'card_id'=>intval($this->input['card_id']),
			'name'=>trim($this->input['name']),
			'english_name'=>trim($this->input['english_name']),
			'sex'=>intval($this->input['sex']),
			'tel'=>trim($this->input['tel']),
			'ext_num'=>trim($this->input['ext_num']),
			'mobile'=>trim($this->input['mobile']),
			'email'=>trim($this->input['email']),
			'create_time'=>TIMENOW,
			'update_time'=>TIMENOW,
			'org_id'=>$this->user['org_id'],
			'user_id'=>$this->user['user_id'],
			'user_name'=>$this->user['user_name'],
			'ip'=>$this->user['ip'],
		);
		if (!$data['account'])
		{
			$this->errorOutput('请填写账号');
		}
		if (!$data['password'])
		{
			$this->errorOutput('请填写密码');
		}
		if (!$data['name'])
		{
			$this->errorOutput('请填写姓名');
		}
		//创建账号
		$accountInfor = $this->reporter->createAccount($data,$_FILES);
		if (empty($accountInfor))
		{
			$this->errorOutput('创建帐户失败');
		}
		$data['account_id'] = $accountInfor['id'];
		$data['role_id'] = $accountInfor['admin_role_id'];
		unset($data['password']);
		if ($accountInfor['avatar'])
		{
			$data['avatar'] = stripcslashes($accountInfor['avatar']);
		}
		$ret = $this->reporter->create($data);
		if (!$ret)
		{
			$this->errorOutput('数据库错误');
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = array(
			'account'=>trim($this->input['account']),
			'password'=>trim($this->input['password']),
			'brief'=>trim($this->input['brief']),
			'role_id'=>$this->input['role_id'],
			'domain'=>trim($this->input['domain']),
			'card_id'=>intval($this->input['card_id']),
			'name'=>trim($this->input['name']),
			'english_name'=>trim($this->input['english_name']),
			'sex'=>intval($this->input['sex']),
			'tel'=>trim($this->input['tel']),
			'ext_num'=>trim($this->input['ext_num']),
			'mobile'=>trim($this->input['mobile']),
			'email'=>trim($this->input['email']),
			'update_time'=>TIMENOW,
		);
		if (!$data['account'])
		{
			$this->errorOutput('请填写账号');
		}
		if (!$data['name'])
		{
			$this->errorOutput('请填写姓名');
		}
		
		/**************权限控制开始**************/
		$this->verify_content_prms();
		$sql = 'SELECT * FROM '.DB_PREFIX.'reporter WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		$arr = array(
					'id'=>$id,
					'user_id'=>$ret['user_id'],
					'org_id'=>$ret['org_id'],
				);
		$this->verify_content_prms($arr);
		/**************权限控制结束**************/
		//更新账号信息
		$accountInfor = $this->reporter->updateAccount($data, $_FILES,$id);
		if (empty($accountInfor))
		{
			$this->errorOutput('更新帐户错误');
		}
		$data['account_id'] = $accountInfor['id'];
		$data['role_id'] = $accountInfor['admin_role_id'];
		if ($accountInfor['avatar'])
		{
			$data['avatar'] = stripcslashes($accountInfor['avatar']);
		}
		$ret = $this->reporter->update($data,$id);
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		/**************权限控制开始**************/
		$this->verify_content_prms();
		$sql = 'SELECT * FROM '.DB_PREFIX.'reporter WHERE id IN ('.$this->input['id'].')';
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
		/**************权限控制结束**************/
		$ret = $this->reporter->delete($id);
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function audit()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$status = intval($this->input['status']);
		$data = $this->reporter->audit($ids,$status);
		$this->addItem($data);
		$this->output();			
	}
	
	public function sort()
	{
		$this->verify_content_prms();
		$ret = $this->drag_order('reporter', 'order_id');
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
$ouput= new reporterUpdateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();