<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: recache.class.php 1524 2011-01-04 09:46:16Z yuna $
***************************************************************************/

class hglog
{
	private $db;
	private $user;
	private $input;
	private $settings;
	
	function __construct()
	{
		global $_INPUT, $gUser, $gGlobalConfig;
		$this->user = &$gUser;
		$this->input = &$_INPUT;
		$this->settings = $gGlobalConfig;
	}
	
	function __destruct()
	{
	}
	private function add2log($content, $operation = 'login')
	{
		$curl = new curl($this->settings['App_logs']['host'], $this->settings['App_logs']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','create');
		$curl->addRequestData('bundle_id', 'm2o');
		$curl->addRequestData('moudle_id', 'm2o');
		$curl->addRequestData('operation',$operation);
		$curl->addRequestData('title',$content);
		$ret = $curl->request('logs_update.php');
		return $ret;
	}
	public function add_log($content, $type = 'login')
	{
		if ($this->settings['App_logs'])
		{
			$this->add2log($content, $type);
		}
		else
		{	
			$this->db = hg_checkDB();
			if ($this->input['id'])
			{
				$ids = '(' . $this->input['id'] . ')';
			}
			$ip = hg_getip();
			$ipaddr = hg_getIpInfo($ip, 5);
			if ($ipaddr)
			{
				$zone = $ipaddr[0]['zone'];
				$service = $ipaddr[0]['service'];
			}
			$data = array(
				'content' => $content . $ids, 	
				'type' => $type, 	
				'admin_id' => intval($this->user['id']), 	
				'user_name' => $this->user['user_name'], 	
				'group_type' => $this->user['group_type'], 	
				'ip' => $ip, 	
				'ip_info' => $zone . ' ' . $service,
				'create_time' => TIMENOW, 	
				'script_name' => SCRIPT_NAME . '::' . REFERRER, 	
			);
			hg_fetch_query_sql($data, 'log');
		}
	}
	
	
}
?>