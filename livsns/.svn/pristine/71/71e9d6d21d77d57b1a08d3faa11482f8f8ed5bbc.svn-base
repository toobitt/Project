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
		$fid = intval($this->input['fid']);
		$return = $this->mNewLive->getChannelNode($fid);
		if (!empty($return))
		{
			$all_node = array();
			if($this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$tmp_node = implode(',',$this->user['prms']['app_prms'][APP_UNIQUEID]['nodes']);
				$all_node_tmp = $this->mNewLive->getChannelById($tmp_node);
				if(!empty($all_node_tmp))
				{
					foreach($all_node_tmp as $k => $v)
					{
						$all_node[] = $v['node_id'];
					}
				}
				$all_node = array_unique($all_node);
			}
			foreach ($return AS $v)
			{
				if($this->user['group_type'] > MAX_ADMIN_TYPE)
				{
					if($this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'] && in_array($v['id'],$all_node))
					{
						$this->addItem($v);
					}
				}
				else
				{
					$this->addItem($v);
				}
			}
		}$this->output();
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