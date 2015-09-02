<?php
/***************************************************************************
 * $Id: member.php 46975 2015-07-29 11:24:05Z jitao $
 ***************************************************************************/
define('MOD_UNIQUEID','members');//模块标识
require('./global.php');
class dingdonememberApi extends outerReadBase
{
	private $mMember;
	public function __construct()
	{
		parent::__construct();

		require_once CUR_CONF_PATH . 'lib/member.class.php';
		$this->mMember = new member();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		
	}
	
	public function detail()
	{
		
	}
	
	public function count()
	{
	
	}
	
	/**
	 * 叮当生产出的APP覆盖会员用户数的存量
	 */
	public function getActivateMemberCount()
	{
		$start_time = intval($this->input['start_time']);
		$end_time = intval($this->input['end_time']);
		$info = $this->mMember->getActivateMemberCount($start_time,$end_time);
		if($info)
		{
			$this->addItem($info);
		}
		$this->output();
	}

	public function getTodayMemberInfo()
	{
		$start_time = intval($this->input['start_time']);
		$info = $this->mMember->getTodayMemberInfo($start_time);
		$this->addItem($info);
		$this->output();	
	}
    

	
}

$out = new dingdonememberApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>