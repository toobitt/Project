<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: topic.php 2843 2011-03-16 09:21:25Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class verifyStatusApi extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function verify()
	{
		$id = urldecode($this->input['status_id']);
		
		$status = $this->input['states'];
		
		$sql = "UPDATE " . DB_PREFIX . "status SET status = " . $status . " WHERE id IN (" . $id .")";		
		$this->db->query($sql);
		
		return true;
	}
}

$out = new verifyStatusApi();
$action = $_POST['a'];
if(!method_exists($out, $action))
{
	$action = 'verify';
}
$out->$action();
?>
