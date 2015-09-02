<?php
/*
 * Created on 2012-12-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 require('global.php');
 class serverConfig extends adminBase
 {
 	function __construct()
 	{
 		parent::__construct();
 	}
 	function  __destruct()
 	{
 		parent::__destruct();
 	}
 	function show()
 	{
 		$ret = $this->__getConfig();
 	}
 }
 $obj = new serverConfig();
 $action = method_exists($obj, $_INPUT['a']) ?  $_INPUT['a'] : 'show';
 $obj->$action();
?>
