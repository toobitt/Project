<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 5241 2011-12-06 13:28:44Z develop_tong $
***************************************************************************/
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID', 'livmedia');
require(ROOT_PATH."global.php");
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
}

$out = new index();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>