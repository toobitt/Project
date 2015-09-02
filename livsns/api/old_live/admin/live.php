<?php
/***************************************************************************
* $Id: live.php 8798 2012-08-04 06:26:20Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','mms_control');
require('global.php');
class liveApi extends adminBase
{
//	private $mLivmms;
	private $mLive;
	public function __construct()
	{
		parent::__construct();
//		require_once CUR_CONF_PATH . 'lib/livmms.class.php';
//		$this->mLivmms = new livmms();
		
		require_once CUR_CONF_PATH . 'lib/live.class.php';
		$this->mLive = new live();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function emergency_change()
	{
		if($this->mNeedCheckIn && !$this->prms['control'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道ID');
		}
		
		$stream_id = intval($this->input['stream_id']);
		if (!$stream_id)
		{
			$this->errorOutput('请选择要切播到的信号流');
		}
		
		$chg_type = trim($this->input['chg_type']);
		if (!$chg_type)
		{
			$this->errorOutput('请选择要切播类型');
		}
		
		$live_back = trim($this->input['live_back']);

		$info = $this->mLive->emergency_change($channel_id, $stream_id, $chg_type, $live_back, $this->user);
		
		switch ($info)
		{
			case -55;
				$this->errorOutput('切播服务器未开启');
				break;
			case -13;
				$this->errorOutput('该频道不存在或者已被删除');
				break;
			case -14;
				$this->errorOutput('频道输出流未启动');
				break;
			case -15;
				$this->errorOutput('频道信号流不存在');
				break;
			case -16;
				$this->errorOutput('该信号不存在或已被删除');
				break;
			case -17;
				$this->errorOutput('该信号流未启动');
				break;
			case -18;
				$this->errorOutput('备播文件不存在或已损坏');
				break;
			case -19;
				$this->errorOutput('文件流创建失败');
				break;
			case 0;
				$this->errorOutput('切播失败');
				break;
			default:
				break;
		}
		
		$this->addItem($info);
		$this->output();
	}
	
	public function nowStatus()
	{
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		$info = $this->mLive->nowStatus($channel_id);
		$this->addItem($info);
		$this->output();
	}
}
$out = new liveApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'emergency_change';
}
$out->$action();
?>