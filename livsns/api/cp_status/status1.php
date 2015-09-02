<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: status.php 4079 2011-06-16 08:29:10Z develop_tong $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . 'core/status/status.dat.php');
require(ROOT_DIR . 'core/status/media.dat.php');
require(ROOT_DIR . 'lib/user/user.class.php');
class statusShowApi extends BaseFrm
{
	var $dat;
	function __construct() 
	{
		parent::__construct();
		$this->dat = new status_data();
	}
	function __destruct() 
	{
		parent::__destruct();
		$this->db->close();
	}
	/**
		获取指定的微博
	*/
	function detail() 
	{
		$this->setXmlNode('statues', 'status');
		$statuses = $this->dat->detail($this->input['id']);
		if ($statuses)
		{
			foreach ($statuses AS $status)
			{
				$this->addItem($status);
			}
		}
		$this->output();
	}

	/**
		批量获取微博记录
	*/
	function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'];
		$count = $this->input['count'];
		$this->setXmlNode('statues', 'status');
		$statuses = $this->dat->status($condition, $offset, $count);
		if ($statuses)
		{
			foreach ($statuses AS $status)
			{
				$this->addItem($status);
			}
		}
		$this->output();
	}

	/**
		取出总的微博记录数
	*/
	function count()
	{
		$condition = $this->get_condition();
		$total = array();
		$total['total'] = $this->dat->count($condition);
		echo json_encode($total);
	}
	/**
	 * 条件筛选
	 */
	private function get_condition()
	{
		$condition = '';
		if ($this->input['start_time'])
		{
			$start_time = strtotime($this->input['start_time']);
			if ($start_time > 0)
			{
				$condition .= 'and create_at >= '.$start_time;
			}
		}
		if ($this->input['end_time'])
		{
			$end_time = strtotime($this->input['end_time']);
			if ($end_time > 0)
			{
				$condition .= 'and create_at <= '.$end_time;
			}
		}
		
		if($this->input['k'])
		{
			$condition .= ' and text like \'%' . $this->input['k'] . '%\'';
		}
		
		if(isset($this->input['state']))
		{
			if(-1!=$this->input['state'])
			{
				$condition .= ' and status = '.(int)$this->input['state'];
			}
		}
		return $condition;
	}
}
$obj = new statusShowApi();
if(!method_exists($obj, $_INPUT['a']))
{
	$_INPUT['a'] = 'show';
}
$obj->$_INPUT['a']();
?>