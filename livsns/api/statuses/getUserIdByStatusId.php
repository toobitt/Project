<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: getUserIdByStatusId.php 17941 2013-02-26 02:20:49Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
/**
 * 
 * 通过点滴ID获取用户ID
 *
 */
class getUserIdApi extends appCommonFrm
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