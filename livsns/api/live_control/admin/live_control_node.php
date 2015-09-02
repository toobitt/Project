<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: live_control_node.php 19897 2013-04-08 02:42:46Z lijiaying $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','live_control');//模块标识
class liveControlNodeApi extends adminReadBase
{
	private $mLive;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$this->mLive = new live();
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
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;

		$condition = $this->get_condition();
		
		$condition['offset'] 	= $offset;
		$condition['count'] 	= $count;
		$condition['is_stream'] = 0;
		$condition['field'] = 'id, name, code, is_control, is_audio, is_mobile_phone, server_id, logo_rectangle, logo_square, node_id';
		
		$return = $this->mLive->getChannelInfo($condition);
		
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
		$channel = $this->mLive->getChannelById($id);
		$ret = array();
		if(!empty($channel))
		{
			foreach($channel AS $k => $v)
			{
				$this->addItem(array($v));
			}
		}
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

$out = new liveControlNodeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>