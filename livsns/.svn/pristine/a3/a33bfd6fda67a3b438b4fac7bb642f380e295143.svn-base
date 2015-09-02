<?php
define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','auth');
define('NEED_CHECKIN', false);
require(ROOT_DIR . 'global.php');
define('SCRIPT_NAME', 'verify_user');
class verify_user extends coreFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	//验证用户身份
	public function show()
	{
		$verify_code = trim(urldecode($this->input['verify_code']));
		if(!$verify_code)
		{
			$this->errorOutput(PARAMETER_ERROR);
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'dynamic_sec WHERE private_key = "'.$verify_code.'" AND expire_date >='.TIMENOW;
		$row = $this->db->query_first($sql);
		if($row['private_key'])
		{
			$row['success'] = 1;
			$row['expire_date'] = date('Y-m-d h:i:s',$row['expire_date']);
			$this->addItem($row);
		}
		else
		{
			$this->addItem(array('success'=>0));
		}
		
		$this->output();
	}
	//定时请求口令
	public function get_dynamic_token()
	{
		$verify_code = trim(urldecode($this->input['verify_code']));
		if(!$verify_code)
		{
			$this->errorOutput(NO_VERIFY_CODE);
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'dynamic_sec WHERE private_key = "'.$verify_code.'" limit 1';
		$row = $this->db->query_first($sql);
		if($row['auth_api'])
		{
			include_once(ROOT_DIR.'lib/class/curl.class.php');
			$curl = new curl($row['auth_api'],$row['dir']);
			$curl->addRequestData('user_id', $row['user_id']);
			$ret = $curl->request('admin/set_dynamic_token.php');
			if(!$ret['ErrorCode'])
			{
				$ret = $ret[0];
				$this->addItem(array('token'=>$ret['token']));
			}
			$this->output();
		}
	}
}
include(ROOT_PATH . 'excute.php');
?>