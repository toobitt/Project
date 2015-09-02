<?php
//输出直播流
require ('global.php');
define('MOD_UNIQUEID','live');
define('SCRIPT_NAME', 'live');
class live extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function count(){}
	public function detail(){}
	
	public function show()
	{
		//判断现场直播的时间有没有到
		if(TIMENOW < strtotime(LIVE_STIME))
		{
			$this->errorOutput(LIVE_NOT_START);
		}
		
		if(!$this->settings['live_stream']['stream_url'])
		{
			$this->errorOutput(NO_LIVE_STREAM);
		}
		
		$this->addItem($this->settings['live_stream']);
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');