<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record.php 7586 2012-07-05 09:40:56Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_record');//模块标识
class nodeApi extends adminReadBase
{
	private $newLive;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$this->newLive = new live();
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
		$channel = $this->newLive->getChannel();
		if(!empty($channel))
		{
			foreach($channel as $k => $v)
			{
				if($this->user['group_type'] > MAX_ADMIN_TYPE)
				{
					if($this->user['prms'][MOD_UNIQUEID]['show']['node'] && in_array($v['id'],$this->user['prms'][MOD_UNIQUEID]['show']['node']['program_record_node']))
					{
						$this->addItem(array('id' => $v['id'],'name'=>$v['name'],'input_k' => 'channel_id','is_last'=>1));
					}
				}
				else
				{
					$this->addItem(array('id' => $v['id'],'name'=>$v['name'],'input_k' => 'channel_id','is_last'=>1));
				}				
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
	
	public function get_selected_node_path()
	{
		$id = trim($this->input['id']);
		$this->newLive->getSelectedNodes($id);
		$ret = array();
		if(!empty($channel))
		{
			foreach($channel as $k => $v)
			{
				$tmp = array(
					'id' => $v['id'],
					'name' => $v['name'],
					'fid' => 0,
					'parents' => 1,
					'childs' => 1,
					'is_last' => 1,
					'depath' => 1,
					'is_auth' => 1,
				);
				$this->addItem(array($tmp));
			}
			$this->output();
		}
	}
	
	//获取选中的节点
	public function getSelectedNodes()
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