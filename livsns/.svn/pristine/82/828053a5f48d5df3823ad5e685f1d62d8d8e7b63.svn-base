<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: authority.php 1366 2010-12-28 10:10:31Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');

/**
 * 
 * 获取权限
 */

class getAuthorityApi extends appCommonFrm
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
	
	public function get()
	{
		//可获取批量用户权限		
		$id = $this->input['id'];			
				
		$sql = "SELECT id , privacy FROM " . DB_PREFIX . "member WHERE id IN ($id)";

		$q = $this->db->query($sql);
				
		$authority  = array();
		while($row = $this->db->fetch_array($q))
		{
			$authority[$row['id']] = $row['privacy'];		
		}

		$this->setXmlNode('users_authority' , 'user');
				
		foreach ($authority AS $item)
		{
			$this->addItem($item);
		}			
	
		$this->output();		
	}	
}

$out = new getAuthorityApi();

$out->get();
?>