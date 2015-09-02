<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_screen.php 6070 2012-03-12 03:18:13Z repheal $
***************************************************************************/
require('global.php');
class programScreenApi extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/program_screen.class.php');
		$this->obj = new programScreen();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 显示屏蔽节目单
	 */
	function show()
	{
		$condition = $this->get_condition();
		$ret = $this->obj->show($condition);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}			
		}
		$this->output();
	}

	function detail()
	{
		$condition = $this->get_condition();
		$ret = $this->obj->detail($condition);
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * Enter description here ...
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count($condition);
		//暂时这样处理
		echo json_encode($info);
	}
	
	/**
	 * 显示屏蔽节目单在列表中
	 */
	function getItem()
	{
		$condition = '';
		
		if($this->input['channel_id'])
		{
			$condition .= ' AND channel_id=' . $this->input['channel_id'];
		}

		$condition .= " AND date='" . ($this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d')) . "'";

		$ret = $this->obj->show($condition);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}

	function getOne()
	{
		if(!$this->input['channel_id'])
		{
			$this->errorOutput("未传入频道ID");
		}
		if(!$this->input['dates'])
		{
			$this->errorOutput("未传入日期");
		}
		$start_time = $this->input['start_time'] ? $this->input['start_time'] : 0;
		$ret = $this->obj->get_one($this->input['channel_id'],$this->input['dates'],$start_time);
		if(!empty($ret))
		{	
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}

	function get_name()
	{
		if(!$this->input['channel_id_back'])
		{
			$this->errorOutput("未传入视频ID");
		}
		$ret = $this->obj->get_back(trim($this->input['channel_id_back']));
		if(empty($ret))
		{
			$this->errorOutput("无视频！");
		}
		$this->addItem(array($ret['title']));
		$this->output();
	}
	
	/**
	 * 获取条件
	 */
	private function get_condition()
	{
		$condition = '';

		if($this->input['channel_id'])
		{
			$condition .= ' AND channel_id='.$this->input['channel_id'];
		}

		return $condition;
	}
	
	function index()
	{
		
	}

	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new programScreenApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>