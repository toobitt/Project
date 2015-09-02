<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record.php 7586 2012-07-05 09:40:56Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','channel');//模块标识
class channelNodeApi extends adminReadBase
{
	private $mNewLive;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$this->mNewLive = new live();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function index()
	{
		
	}

	public function show()
	{
		$return = $this->mNewLive->getChannel();
		if (!empty($return))
		{
			foreach ($return AS $v)
			{
				$this->addItem(array('id' => $v['id'],'name'=>$v['name'],'input_k' => 'channel_id','is_last'=>1));
			}
		}
		$this->output();
	}
	
	public function get_selected_node_path()
	{
		$id = trim($this->input['id']);
		$channel = $this->mNewLive->get_selected_node_path($id);
		$this->addItem($channel[0]);
		$this->output();
	}
	
	private function get_condition()
	{
		$condition = '';
		return $condition;
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
}

$out = new channelNodeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>