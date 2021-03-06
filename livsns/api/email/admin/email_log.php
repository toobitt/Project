<?php
/***************************************************************************
* $Id: email_log.php 17907 2013-02-25 05:48:25Z repheal $
***************************************************************************/
define('MOD_UNIQUEID', 'email_log');
require('global.php');
class emailLogApi extends adminReadBase
{
	private $mEmailLog;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/email_log.class.php';
		$this->mEmailLog = new emailLog();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$condition = $this->get_condition();
		$offset = intval($this->input['offset']);
		$count = intval($this->input['count']);
		
		$emailLogList = $this->mEmailLog->show($condition, $offset, $count);
		
		if ($emailLogList)
		{
			foreach ($emailLogList AS $emailLog)
			{
				$this->addItem($emailLog);
			}
		}
		$this->output();
	}
	
	function count()
	{
		$condition = $this->get_condition();
		$info = $this->mEmailLog->count($condition);
		echo json_encode($info);
	}
		
	public function detail()
	{
		
	}
	public function index()
	{
		
	}
	
	function get_condition()
	{
		$condition = $this->mEmailLog->get_condition();
		return $condition;
	}
}

$out = new emailLogApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>