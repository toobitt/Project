<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: isFans.php 3504 2011-04-10 03:25:51Z chengqing $
***************************************************************************/
/**
 * 
 * 是否在规定的时间达到了指定的粉丝数目
 * @author chengqing
 *
 */
require('global.php');
//ROOT_PATH
class isFansApi extends BaseFrm
{
	private $mUserlib;
	
	function __construct()
	{
		parent::__construct();
		$this->mUserlib = new user();	
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 
	 * 支持用户ID和用户名
	 */
	public function is_fans()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);  //用户未登录
		}
		
		$count = $this->input['count'] ? $this->input['count'] : 10;
		
		if($this->input['user_id'])
		{
			$sql = 'SELECT COUNT(*) AS nums FROM ' . DB_PREFIX . 'member_relation WHERE member_id = ' . $this->user['user_id'];		
			$r = $this->db->query_first($sql);						
		}
				
		if($this->input['user_name'])
		{
			$username = urldecode($this->input['user_name']);
			$sql = "SELECT id FROM " . DB_PREFIX . "member WHERE username = '" . $username . "'";
			
			$r = $this->db->query_first($sql);
			
			$id = $r['id'];
			
			$sql = 'SELECT COUNT(*) AS nums FROM ' . DB_PREFIX . 'member_relation WHERE member_id = ' . $this->user['user_id'];		
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

$out = new isFansApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'is_fans';
}
$out->$action();
?>