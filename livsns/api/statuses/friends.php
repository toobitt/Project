<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: ids.php 776 2010-12-17 05:47:15Z yuna $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . 'global.php');
class friendsApi extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();	
	}
	
	public function getIds()
	{	
		if(!$this->user['user_id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);  //用户未登录
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
	
	public function show()
	{
		
	}	
}
$out = new friendsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>