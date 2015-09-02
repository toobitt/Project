<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record.php 7586 2012-07-05 09:40:56Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','live_time_shift_node');//模块标识
require_once(ROOT_PATH . 'lib/class/live.class.php');
class live_time_shift_node extends adminBase
{
	private $mNewLive;
	function __construct()
	{
		parent::__construct();
		$this->mNewLive = new live();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$fid = intval($this->input['fid']);
		$return = $this->mNewLive->getChannelNode($fid);
		if(is_array($return) && !empty($return))
		{
			foreach ($return AS $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
}

$out = new live_time_shift_node();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>