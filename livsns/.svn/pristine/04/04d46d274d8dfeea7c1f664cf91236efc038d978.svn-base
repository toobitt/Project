<?php 
class smsLog extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	public function replace($mobile = '')
	{
		if(!$mobile)
		{
			return false;
		}
		$sql =  'SELECT mobile,total,last_send_time FROM  ' . DB_PREFIX  . 'sms_log WHERE mobile = "'.$mobile.'"';
		$result = $this->db->query_first($sql);
		if($result['mobile'] && is_in_today($result['last_send_time']))
		{
			$sql = 'UPDATE ' . DB_PREFIX . 'sms_log SET total = total + 1,last_send_time = ' . TIMENOW . ' WHERE mobile = "'.$mobile.'"';
		}
		else
		{
			$sql = 'REPLACE INTO ' . DB_PREFIX . 'sms_log SET mobile = "'.$mobile.'",total = 1, last_send_time='.TIMENOW;
		}
		$this->db->query($sql);
		return true;
	}
	public function check_max_limits($mobile = '')
	{
		if(!$mobile)
		{
			return false;
		}
		$sql =  'SELECT mobile,total FROM  ' . DB_PREFIX  . 'sms_log WHERE mobile = "'.$mobile.'" AND last_send_time > ' . strtotime(date('Y-m-d')) . ' AND last_send_time < '.strtotime(date('Y-m-d',strtotime('+1 day')));
		$result = $this->db->query_first($sql);
		if($result['total'] && $result['total'] >= MAX_SENDSMS_LIMITS)
		{
			return true; // 超出限制
		}
		return false;
	}
}
?>