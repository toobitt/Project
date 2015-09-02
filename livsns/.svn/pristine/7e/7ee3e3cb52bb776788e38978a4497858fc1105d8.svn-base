<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user.php 7586 2013-04-19 09:40:56Z yaojian $
***************************************************************************/
define('MOD_UNIQUEID', 'test');//模块标识
require_once('global.php');

class test extends appCommonFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
	    $path = ROOT_DIR . 'cache/email.txt';
	    if(file_exists($path))
	    {
	        file_put_contents($path,'');
	    }
	    
	    $sql = "SELECT email FROM " .DB_PREFIX. "user";
	    $q = $this->db->query($sql);
	    $str = '';
	    while($r = $this->db->fetch_array($q))
	    {
	        $str .= trim($r['email']) . "\n";
	    }
	    file_put_contents($path,$str);
	    echo 'ok';
	}
}

$out = new test();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();