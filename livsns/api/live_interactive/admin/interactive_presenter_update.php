<?php
/***************************************************************************
* $Id: interactive_presenter_update.php 15116 2012-12-10 02:01:58Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','interactive_presenter');
require('global.php');
class interactivePresenterUpdateApi extends BaseFrm
{
	private $mInteractive;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/interactive.class.php';
		$this->mInteractive = new interactive();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 标记主持人是否已读听众来信
	 * Enter description here ...
	 */
	public function is_read()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		$ret = $this->mInteractive->is_read($id, 'is_read');
		
		$this->addItem($ret);
		$this->output();
	}
	
	function unknow()
	{
		$this->errorOutput('为实现的空方法');
	}
}

$out = new interactivePresenterUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>