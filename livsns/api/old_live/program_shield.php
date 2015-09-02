<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_shield.php 19886 2013-04-08 02:01:25Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','program_shield');
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class programShieldApi extends outerReadBase
{
	private $mProgramShield;
	function __construct()
	{
		parent::__construct();

		include_once CUR_CONF_PATH . 'lib/program_shield.class.php';
		$this->mProgramShield = new programShield();
		
	}

	function __destruct()
	{
		parent::__destruct();
	}

	function show()
	{
		$channel_id = intval($this->input['channel_id']);
		$start_time = intval($this->input['start_time']);
		
		$ret = array();
		if ($channel_id && $start_time)
		{
			$ret = $this->mProgramShield->get_shield_by_time($channel_id, $start_time);
		}
		$this->addItem($ret);
		$this->output();
	}
	public function count()
	{
		
	}
	public function detail()
	{
		
	}
}

$out = new programShieldApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>