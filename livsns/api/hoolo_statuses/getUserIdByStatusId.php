<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: getUserIdByStatusId.php 2774 2011-03-15 06:58:54Z wang $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');

/**
 * 
 * 通过点滴ID获取用户ID
 *
 */
class getUserIdApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();		
	}
	
	function __destruct()
	{
		parent::__destruct();	
	}
	
	public function get_user_id()
	{
		$status_id  = trim($this->input['status_id']);
		
		$sql = "SELECT member_id FROM " . DB_PREFIX . "status WHERE id = " . $status_id;
		
		$r =  $this->db->query_first($sql);
		
		$user_id = $r['member_id'];
		
		echo $user_id;		
	}
}

$out = new getUserIdApi();
$out->get_user_id();
?>