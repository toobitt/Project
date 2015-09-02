<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: isSelfChannel.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
/**
 * 
 * 是否在规定时间内开通过个人网台频道
 * @author chengqing
 *
 */
class isSelfChannel extends adminBase
{
	private $mUser;
	
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . '/lib/user/user.class.php');
		$this->mUser = new user();	
	}
	
	function __destruct()
	{
		parent::__destruct();
	}

	public function is_self_channel()
	{
		$user_info = $this->mUser->verify_credentials();		

		if(!$user_info)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$this->input['user_id'] = $this->input['user_id'] ? $this->input['user_id'] : $user_info['id'];

		$condition = '';
		
		if($this->input['start_time'])
		{
			$time  = strtotime($this->input['start_time']);
			$condition .= ' AND create_time > ' . $time;	
		}
		else
		{
			$time  = strtotime('2011-4-11');
			$condition .= ' AND create_time > ' . $time;
		}
		
		if($this->input['end_time'])
		{
			$time  = strtotime($this->input['end_time']);
			$condition .= ' AND create_time < ' . $time;	
		}
		else
		{
			$time  = strtotime('2011-6-30');
			$condition .= ' AND create_time < ' . $time;
		}
		
		if(intval($this->input['user_id']))
		{
			$user_id = $this->input['user_id'];
			$sql = 'SELECT id FROM ' . DB_PREFIX . 'network_station WHERE user_id = ' . $user_id;
			$sql = $sql . $condition;			
			$r = $this->db->query_first($sql);	
		}
		
		if($this->input['user_name'])
		{			
			$user_name = urldecode($this->input['user_name']);
			
			$user_info = $this->mUser->getUserByName($user_name);
						
			$sql = 'SELECT id FROM ' . DB_PREFIX . 'network_station WHERE user_id = ' . $user_info[0]['id'] ;
			$sql = $sql . $condition;		
			$r = $this->db->query_first($sql);				
		}
		
		if($r)
		{
			$this->addItem(true);
		}
		else
		{
			$this->addItem(false);
		}
		
		$this->output();
	}
}

$out = new isSelfChannel();
$out->is_self_channel();
