<?php
define('MOD_UNIQUEID','push_accounts');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/push_accounts_mode.php');
class push_accounts_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new push_accounts_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$name 		= $this->input['name'];
		$account 	= $this->input['account'];
		$password 	= $this->input['password'];
		$brief 		= $this->input['brief'];
		$plant_type = $this->input['plant_type'];
		
		if(!$name)
		{
			$this->errorOutput(NO_NAME);
		}
		
		if(!$account)
		{
			$this->errorOutput(NO_ACCOUNT_NAME);
		}
		
		if(!$password)
		{
			$this->errorOutput(NO_PASSWORD);
		}
		
		if(!$plant_type)
		{
			$this->errorOutput(NO_PLANT_TYPE);
		}
		
		$data = array(
			'name' 			=> $name,
			'account' 		=> $account,
			'password' 		=> $password,
			'plant_type' 	=> $plant_type,
			'brief' 		=> $brief,
			'user_id' 		=> $this->user['user_id'],
			'user_name' 	=> $this->user['user_name'],
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addItem($data);
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$name 		= $this->input['name'];
		$account 	= $this->input['account'];
		$password 	= $this->input['password'];
		$brief 		= $this->input['brief'];
		$plant_type = $this->input['plant_type'];
		
		if(!$name)
		{
			$this->errorOutput(NO_NAME);
		}
		
		if(!$account)
		{
			$this->errorOutput(NO_ACCOUNT_NAME);
		}
		
		if(!$password)
		{
			$this->errorOutput(NO_PASSWORD);
		}
		
		if(!$plant_type)
		{
			$this->errorOutput(NO_PLANT_TYPE);
		}
		
		$update_data = array(
			'name' 			=> $name,
			'account' 		=> $account,
			'password' 		=> $password,
			'plant_type' 	=> $plant_type,
			'brief' 		=> $brief,
			'user_id' 		=> $this->user['user_id'],
			'user_name' 	=> $this->user['user_name'],
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
		);
		
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit(){}
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new push_accounts_update();
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