<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 32625 2013-12-12 03:56:28Z develop_tong $
***************************************************************************/

define('ROOT_DIR', '../../');
define('MOD_UNIQUEID', 'index'); //模块标识
require(ROOT_DIR . 'global.php');
class index extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$query_str = substr($this->input['q'], 1); 
		$query_str = explode('?', $query_str);
        $query_str = $query_str[0];
		if ($query_str)
		{
			$sql = 'SELECT url, id FROM ' . DB_PREFIX  . "urls WHERE code='$query_str'";
			$result = $this->db->query_first($sql);
		}
		if (!$result)
		{
			$url = ERROR_URL;
		}
		else
		{
			$url = $result['url'];
			$sql = 'UPDATE ' . DB_PREFIX  . "urls set click_count = click_count + 1 WHERE id={$result['id']}";
			$this->db->query($sql);
		}
		header('Location:' . $url);
	}
	/**
	* 无需验证授权
	*/
	protected function verifyToken()
	{
	}
}
$out = new index();
$out->show();
?>