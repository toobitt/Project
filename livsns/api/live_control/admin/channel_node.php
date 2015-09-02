<?php
define('MOD_UNIQUEID','channel');
require('global.php');
class channel_node extends adminReadBase
{
	private $mLive;
	public function __construct()
	{
		parent::__construct();
		require_once ROOT_PATH . 'lib/class/live.class.php';
		$this->mLive = new live();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	public function count(){}
	public function detail(){}
	
	public function show()
	{
		$fid = intval($this->input['fid']);
		
		$return = $this->mLive->getChannelNode($fid);
	
		if (!empty($return))
		{
			$all_node = array();
			if($this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$tmp_node = implode(',',$this->user['prms']['app_prms'][APP_UNIQUEID]['nodes']);
				$all_node = $this->mLive->getChildNodeByFid($tmp_node);
				$all_node = array_unique(explode(',',implode(',',$all_node)));
			}
			foreach ($return AS $v)
			{
				$tmp = $data = array();
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
		}
		$this->output();
	}

	public function get_selected_node_path()
	{
		$id = trim($this->input['id']);
		$channel = $this->mLive->get_selected_node_path($id);
		$this->addItem($channel[0]);
		$this->output();
	}
	
	private function get_condition()
	{
		$condition = '';
		return $condition;
	}
}
$out = new channel_node();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>