<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record.php 7586 2012-07-05 09:40:56Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','schedule');//模块标识
class nodeApi extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	public function index()
	{
		
	}

	/**
	 * 显示录播节目单
	 */
	function show()
	{
		include_once(ROOT_PATH . 'lib/class/new_live.class.php');
		$newLive = new newLive();
		$channel = $newLive->getChannel();
		if(!empty($channel))
		{
			foreach($channel as $k => $v)
			{
				$this->addItem(array('id' => $v['id'],'name'=>$v['name'],'input_k' => 'channel_id','is_last'=>1));
			}
			$this->output();
		}	
	}
	
	/**
	 * Enter description here ...
	 */
	public function count()
	{
	
	}

	/**
	 * 获取单条信息
	 */
	public function detail()
	{
		
	}
	
	/**
	 * 获取条件
	 */
	private function get_condition()
	{
		$condition = '';
		return $condition;
	}
}

$out = new nodeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>