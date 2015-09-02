<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'block_push');
require('./global.php');
require('./lib/class/curl.class.php');
class block_push extends uiBaseFrm
{	
	
	function __construct()
	{
		parent::__construct();
		include ROOT_PATH . 'lib/class/block.class.php';
		$this->block = new block;
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	//节点
	public function show()
	{
		$fid = intval($this->input['fid']);
		$node = $this->block->get_node($fid);
		echo json_encode($node);
	}
	
	//区块
	public function get_block()
	{
		$id = $this->input['id'];
		$block = $this->block->get_block($id);
		echo json_encode($block);
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>