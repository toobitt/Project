<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: ids.php 776 2010-12-17 05:47:15Z yuna $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');

/**
 * 
 * 获取关注者对象IDS接口
 *
 */
class getfriendsIdsApi extends BaseFrm
{
	var $mUserlib;
	
	function __construct()
	{
		parent::__construct();
						
		$this->mUserlib = new user();
	}
	
	function __destruct()
	{
		parent::__destruct();	
	}
	
	public function get_ids()
	{		
		$userinfo = $this->mUserlib->verify_user(); //验证用户是否登录
				
		if(!$userinfo)
		{
			$this -> errorOutput(USENAME_NOLOGIN);  //用户未登录
		}
		
		$id = $this->input['id'];                   //被关注ID
		
		/**
		 * 从mmecache中获取关注用户IDS
		 */
				
		$cache_data = FRIENDS_MEM_PRE . $id;        
		$firends = hg_check_cache($cache_data, "friends($id)");
		$firends = explode(',', $firends);
		
		$this->setXmlNode('id_list' , 'id');
		
		foreach ($firends AS $item)
		{
			$this->addItem($item);
		}
		
		$this->output();		
	}	
}

$out = new getfriendsIdsApi();
$out->get_ids();
?>