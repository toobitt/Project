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
 * 获取黑名单IDS接口
 *
 */
class getBlockIdsApi extends BaseFrm
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
		
		$id = $this->input['id'];                   //当前用户ID
		
		/**
		 * 从mmecache中获取黑名单IDS
		 */
		$cache_data = BLOCKERS_MEM_PRE . $id;
		$blockers   = hg_check_cache($cache_data, "blockers($id)");

		$blockers    = explode(',', $blockers);
		
		$this->setXmlNode('id_list' , 'id');
		
		foreach ($blockers AS $item)
		{
			$this->addItem($item);
		}		
		$this->output();		
	}	
}

$out = new getBlockIdsApi();
$out->get_ids();
?>