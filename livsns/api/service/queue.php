<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: queue.php 519 2010-12-14 06:12:26Z develop_tong $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class queue extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function set()
	{
		$name = $this->input['name'];
		if (!$name)
		{
			$this->errorOutput(KEY_NULL);
		}
		$value = addslashes($this->input['value']);
		$sql = 'INSERT INTO ' . DB_PREFIX . "queue(name,value) VALUES ('$name', '$value')";
		$this->db->query($sql);
		echo 1;
	}

	public function get()
	{
		echo 0;
	}

	public function unknow()
	{
		$this->errorOutput();
	}
}
$out = new queue();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>