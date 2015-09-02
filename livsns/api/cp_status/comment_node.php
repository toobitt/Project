<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: comment_node.php 6690 2012-05-11 09:34:23Z lijiaying $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
define('MOD_UNIQUEID', 'mblog_comment_m'); //模块标识

class commentNodeApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function count(){
		
	}
	public function detail(){
		
	}
	
	public function show()
	{
		foreach($this->settings['comment_node'] as $k=>$v)
		{
			$r = array('id'=>$k,"name"=>$v,"fid"=>0,"depth"=>0, 'input_k' => '_type','is_last'=>1);
			$this->addItem($r);
		}
		$this->output();
	}
}

$out = new commentNodeApi();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();

?>