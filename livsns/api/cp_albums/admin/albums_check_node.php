<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: albums_check_node.php 22882 2013-05-28 10:13:58Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','albums_check_node');//模块标识
require('global.php');

class albumsCheckNode extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	public function detail(){}
	public function count(){}
	
	public function show()
	{
		foreach($this->settings['check_cond'] as $k=>$v)
		{
			$r = array('id'=>$k,"name"=>$v,"fid"=>0,"depth"=>0, 'input_k' => '_type','is_last'=>1);
			$this->addItem($r);
		}
		
		$this->output();
	}
}

$out = new albumsCheckNode();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();

?>