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
class followersApi extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();				
		//include_once(ROOT_DIR . 'lib/class/member.class.php');
		//$this->member = new member();
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
		
		$id = $this->input['id'];                   //关注者ID
		
		/**
		 * 从mmecache中获取粉丝用户IDS
		 */
				
		$cache_data = FOLLOWERS_MEM_PRE . $id;        
		$follows = hg_check_cache($cache_data, "followers($id)");
		$follows = explode(',', $follows);
		
		$this->setXmlNode('id_list' , 'id');
		
		foreach ($follows AS $item)
		{
			$this->addItem($item);
		}
		
		$this->output();		
	}
	
	public function show()
	{
		
	}	
}
$out = new followersApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>