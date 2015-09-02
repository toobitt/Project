<?php
define('MOD_UNIQUEID','auth');
require('./global.php');
define('SCRIPT_NAME', 'getPrivateKey');
class getPrivateKey extends coreFrm
{
	public function __construct()
	{
		parent::__construct();
		if(!$this->verify_custom())
		{
			$this->errorOutput(UNKNOWN_HG_CUSTOMER);
		}
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		if(!$this->input['user_id'])
		{
			$this->errorOutput(UNKNOWN_USER);
		}
	
		if(!$this->input['auth_api'])
		{
			$this->errorOutput(UNKNOWN_MCP);
		}
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'dynamic_sec WHERE user_id = '.intval($this->input['user_id']);
		$result = $this->db->query_first($sql);
		if($result['user_id'])
		{
			$this->errorOutput(USER_HAS_BINDMCP);
		}
		$data = array(
		'private_key'=>$this->build_private_key(),
		'expire_date'=>TIMENOW+KEY_EXPIRE_DATE,
		'user_id'=>intval($this->input['user_id']),
		'auth_api'=>urldecode($this->input['auth_api']),
		'dir'=>urldecode($this->input['auth_dir'])
		);
		if(!$this->check_verify_code($datap['private_key']))
		{
			$this->errorOutput(TOKEN_REPEAT);
		}
		else
		{
			$sql = 'INSERT INTO '.DB_PREFIX.'dynamic_sec SET ';
			foreach($data as $key=>$value)
			{
				$sql .=  $key .'="'.$value.'",';
			}
			$sql = trim($sql, ',');
			$this->db->query($sql);
			$this->addItem($data);
		}
		$this->output();
	}
	function update_private_key()
	{
		$data = array(
		'user_id'=>intval($this->input['user_id']),
		'private_key'=>$this->build_private_key(),
		'expire_date'=>TIMENOW+KEY_EXPIRE_DATE,
		'auth_api'=>urldecode($this->input['auth_api']),
		'dir'=>urldecode($this->input['auth_dir'])
		);
		if(!$this->check_verify_code($datap['private_key']))
		{
			$this->errorOutput(TOKEN_REPEAT);
		}
		else
		{
			$sql = 'UPDATE '.DB_PREFIX.'dynamic_sec SET private_key = "'.$data['private_key'].'", expire_date='. $data['expire_date'] . ', auth_api="'.$data['auth_api'].'",dir="'.$data['dir'].'" WHERE user_id = '.$data['user_id'];
			$this->db->query($sql);
			$this->addItem($data);
		}
		$this->output();
	}
	private function build_private_key()
	{
		$number_rand = floor(PRIVATE_KEY_NUM/2);
		$rand_lib = array();
		for($i=97;$i<=122;$i++)
		{
			$rand_lib[] = chr($i);
		}
		for($i=65;$i<=90;$i++)
		{
			$rand_lib[] = chr($i);
		}
		$rand_lib = array_merge($rand_lib , range(0,9));
		//print_r($rand_lib);exit;
		$count = count($rand_lib);
		$verify_code  = '';
		for($j=0;$j<PRIVATE_KEY_NUM;$j++)
		{
			$verify_code .= $rand_lib[rand(0,$count-1)];
		}
		return $verify_code;
	}
	function check_verify_code($verify_co)
	{
		$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'dynamic_sec WHERE private_key = "'.$verify_code.'"';
		$row = $this->db->query_first($sql);
		//$this->errorOutput(var_export($row,1));
		if(!$row['total'])
		{
			return true;
		}
		return false;
	}
	//验证客户有无权限创建应用
	public function verify_custom()
	{
		if(!$this->input['custom_id'])
		{
			$this->errorOutput('NO_CUSTOM_ID');
		}

		if(!$this->input['custom_key'])
		{
			$this->errorOutput('NO_CUSTOM_KEY');
		}

		$sql = " SELECT * FROM " .DB_PREFIX. "authinfo WHERE appid = '".intval($this->input['custom_id'])."' AND appkey = '".urldecode($this->input['custom_key'])."'";
		$ret = $this->db->query_first($sql);
		if($ret['appid'])
		{
			return true;
		}
		return false;
	}
}
include(ROOT_PATH . 'excute.php');