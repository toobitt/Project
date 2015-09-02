<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: isUploadVideo.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

/**
 * 
 * 是否在指定的时间内上传了指定的视频数目
 * @author chengqing
 *
 */
class isUploadVideo extends adminBase
{
	private $mUser;
	
	function __construct()
	{
		parent::__construct();
		include(ROOT_PATH . '/lib/user/user.class.php');
		$this->mUser = new user();	

	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function is_upload_video()
	{
		$user_info = $this->mUser->verify_credentials();		

		if(!$user_info)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
				
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
		
		$count = $this->input['count'] ? $this->input['count'] : 5;
		
		if(intval($this->input['user_id']))
		{
			$user_id = $this->input['user_id'];
			$sql = 'SELECT COUNT(*) AS nums FROM ' . DB_PREFIX . 'video WHERE user_id = ' . $user_id ;
			$sql = $sql . $condition;			
			$r = $this->db->query_first($sql);	
		}
		
		if($this->input['user_name'])
		{			
			$user_name = urldecode($this->input['user_name']);			
			$user_info = $this->mUser->getUserByName($user_name);						
			$sql = 'SELECT COUNT(*) AS nums FROM ' . DB_PREFIX . 'video WHERE user_id = ' . $user_info[0]['id'] ;
			$sql = $sql . $condition;		
			$r = $this->db->query_first($sql);				
		}
		
		if(!empty($r))
		{
			if($r['nums'] > $count)
			{
				$this->addItem(true);
			}
			else
			{
				$this->addItem(false);
			} 	
		}
		else
		{
			$this->addItem(false);
		}
		
		$this->output();
	}	
}

$out = new isUploadVideo();
$out->is_upload_video();
