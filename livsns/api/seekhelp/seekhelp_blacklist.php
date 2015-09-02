<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - dxtan
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014年12月11日
 * @encoding    UTF-8
 * @description rongcloud_blacklist.php
 **************************************************************************/
require 'global.php';
define('MOD_UNIQUEID', 'seekhelpBlacklistApi');
include_once(CUR_CONF_PATH . 'lib/seekhelp_blacklist_mode.php');
class seekhelpBlacklistApi extends outerReadBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new seekhelp_blacklist_mode();
	}

	public function __destruct()
	{
		parent::__destruct();
		if ($this->curl !== NULL)
		{
			$this->curl = NULL;
		}
	}
	
	/**
	 * 查询这个appId是否在群组黑名单
	 */
	public function check_blackByappId()
	{
		$app_id = intval($this->input['app_id']);
		if (!$app_id)
		{
			$this->errorOutput(NO_APPID);
		}
		$result = $this->mode->check_blackByappId($app_id);
		if($result && $result['deadline'] == -1)
		{
			$this->addItem(array('is_black' => 1,'msg' => '您的应用是黑名单','data' => $result));
		}
		elseif ($result && $result['deadline'] == 0)
		{
			$this->addItem(array('is_black' => 0,'msg' => '您的应用不是黑名单', 'data' => 'NOT BLACK'));
		}
		
		$this->output();
	}
	
	public function show(){}
	public function detail(){}
	public function count(){}
	public function unknow(){}
}
$out = new seekhelpBlacklistApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();