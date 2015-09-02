<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: isUploadPhoto.php 3513 2011-04-10 10:54:56Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

/**
 * 
 * 是否在规定的时间内上传了指定的图片数目
 * @author chengqing
 *
 */
class isUploadPhoto extends BaseFrm
{
	private $mUser; 
	function __construct()
	{
		parent::__construct();
		include(ROOT_DIR . '/lib/user/user.class.php');		
		$this->mUser = new user();  
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function is_upload_photo()
	{
		$user_info = $this->mUser->verify_credentials();
		if(!$user_info)
		{
			$this->errorOutput('未登录');
		}
		
		$condition = '';
		
		if($this->input['start_time'])
		{
			$time  = strtotime($this->input['start_time']);
			$condition .= ' AND pub_time > ' . $time;	
		}
		else
		{
			$time  = strtotime('2011-4-11');
			$condition .= ' AND pub_time > ' . $time;
		}
		
		if($this->input['end_time'])
		{
			$time  = strtotime($this->input['end_time']);
			$condition .= ' AND pub_time < ' . $time;	
		}
		else
		{
			$time  = strtotime('2011-6-30');
			$condition .= ' AND pub_time < ' . $time;
		}
		
		$count = $this->input['count'] ? $this->input['count'] : 10;
		
		if(intval($this->input['user_id']))
		{
			$user_id = $this->input['user_id'];
			$sql = 'SELECT COUNT(*) AS nums FROM ' . DB_PREFIX . 'pictures WHERE user_id = ' . $user_id ;
			$sql = $sql . $condition;			
			$r = $this->db->query_first($sql);	
		}
		
		if($this->input['user_name'])
		{			
			$user_name = urldecode($this->input['user_name']);
			$sql = 'SELECT COUNT(*) AS nums FROM ' . DB_PREFIX . 'pictures WHERE user_name = "' . $user_name . '"';
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

$out = new isUploadPhoto();
$out->is_upload_photo();

