<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: group_node.php 4039 2011-06-07 02:05:19Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class albumsCheckNode extends BaseFrm
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