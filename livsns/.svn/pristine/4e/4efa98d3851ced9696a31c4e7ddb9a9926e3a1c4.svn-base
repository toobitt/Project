<?php 
/***************************************************************************

* $Id: sms_server.class.php 33933 2014-01-28 14:58:01Z zhuld $

***************************************************************************/
class member_verifycode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function verifycode_create($data)
	{
		$sql = "REPLACE INTO " . DB_PREFIX . "verifycode SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		if ($this->db->query($sql))
		{
			return $data;
		}
		return false;
	}

	public function verifycode_delete($account,$verifycode,$type,$action)
	{
		$binary = '';//不区分大小写
		if(defined('IS_VERIFYCODE_BINARY') && IS_VERIFYCODE_BINARY)//区分大小写
		{
			$binary = 'binary ';
		}
		
		$sql = "DELETE FROM " . DB_PREFIX . "verifycode WHERE account ='" . $account . "' AND " . $binary . " verifycode = '" . $verifycode . "' AND type = ".$type." AND action = ".$action;
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}	
	/**
	 * 
	 * 验证码验证是否过期 ...
	 */
	public function verifycode_verify($db_verifycode,$in_verifycode,$type,$action)
	{		
		if(!defined('VERIFYCODE_EXPIRED_TIME'))
		{
			define('VERIFYCODE_EXPIRED_TIME', 3600);
		}
		if($db_verifycode['create_time']+VERIFYCODE_EXPIRED_TIME>TIMENOW&&$db_verifycode['verifycode']==trim($in_verifycode))
		{
			return true;
		}
		else
		{
			$this->verifycode_delete($db_verifycode['account'], $db_verifycode['verifycode'], $type, $action);
			return false;
		}
	}
/**
 * 
 * 取验证码 ...
 * @param string $account 帐号
 * @param string $verifycode 验证码
 * @param int $type 类型
 * @param int $action 用户
 */
	public function get_verifycode_info($account,$verifycode,$type,$action)
	{
		$binary = '';//不区分大小写
		
		if(defined('IS_VERIFYCODE_BINARY') && IS_VERIFYCODE_BINARY)//区分大小写
		{
			$binary = 'binary ';
		}
		$sql = "SELECT account,verifycode,create_time FROM " . DB_PREFIX . "verifycode WHERE account ='" . $account . "' AND " . $binary . " verifycode = '" . $verifycode . "' AND type = ".$type." AND action = ".$action;
		$return = $this->db->query_first($sql);
		if($return)
		{
			return $this->verifycode_verify($return, $verifycode,$type,$action);
		}
		else return false;
	}
	
	
}

?>