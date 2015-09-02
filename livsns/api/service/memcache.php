<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: memcache.php 519 2010-12-14 06:12:26Z develop_tong $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class memcache extends BaseFrm
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
		$sql = 'REPLACE INTO ' . DB_PREFIX . "memcache(name,value) VALUES ('$name', '$value')";
		$this->db->query($sql);
		echo 1;

	}

	public function get()
	{
		$name = $this->input['name'];
		if (!$name)
		{
			$this->errorOutput(KEY_NULL);
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . "memcache WHERE name='$name'";
		$cache = $this->db->query_first($sql);
		echo $cache['value'];
	}

	public function unknow()
	{
		$this->errorOutput();
	}
}
$out = new memcache();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>