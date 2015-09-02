<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 5622 2012-01-12 05:20:59Z develop_tong $
***************************************************************************/
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."help_frm.php");
class sorts extends HelpFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function show()
	{
		$sortid = intval($this->input['sortid']);
		$keywords = urldecode($this->input['keywords']);
		$cond = '';
		if ($sortid)
		{
			$cond .= ' AND sort_id=' . $sortid;
		}
		if ($keywords)
		{
			$cond .= " AND concat(subject, content) LIKE '%{$keywords}%'";
		}
		$sql = "select * from " . DB_PREFIX . "help WHERE 1 " . $cond . ' ORDER BY order_id ASC, id ASC LIMIT 1000';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$this->addItem($r);
		}
		$this->output();
	}
}

$out = new sorts();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>