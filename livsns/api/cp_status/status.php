<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: status.php 10399 2012-09-05 00:53:17Z develop_tong $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR.'global.php');
define('MOD_UNIQUEID', 'mblog_status'); //模块标识

require('./lib/status.dat.php');
require('./lib/media.dat.php');
require(ROOT_DIR . 'lib/user/user.class.php');
class statusShowApi extends outerReadBase
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
	}
	/**
		获取指定的微博
	*/
	function detail() 
	{
		include(ROOT_PATH . 'lib/func/functions_rewrite.php');
		$this->setXmlNode('statues', 'status');
		$statuses = $this->dat->detail($this->input['id']);
		if ($statuses)
		{
			foreach ($statuses AS $status)
			{
				$status['status'] = $status['status'] ? 0 : 2;
				$status['pubstatus'] = $status['status'] ? 1 : 0; 
				$status['status_link'] = hg_rewrite($status['status_link']);
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
		$offset = $this->input['offset']?$this->input['offset']:0;
		$count = $this->input['count']?$this->input['count']:20;
		$this->setXmlNode('statues', 'status');
		$sorders = array('id', 'create_at',);
		$seorders = array('transmit_count', 'comment_count');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (in_array($this->input['hgorder'], $sorders))
		{
			$orderby = 's.' . $this->input['hgorder']  . ' ' . $descasc ;
		}
		elseif (in_array($this->input['hgorder'], $seorders))
		{
			$orderby = 'se.' . $this->input['hgorder'] . ' ' . $descasc;
		}
		$statuses = $this->dat->status($condition, $orderby, $offset, $count);
	//	hg_pre($statuses);exit;
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
				$condition .= ' AND s.create_at >= '.$start_time;
			}
		}
		if ($this->input['end_time'])
		{
			$end_time = strtotime($this->input['end_time']);
			if ($end_time > 0)
			{
				$condition .= ' AND s.create_at <= '.$end_time;
			}
		}

		if($this->input['k'])
		{
			$condition .= ' AND s.text like \'%' . urldecode($this->input['k']) . '%\'';
		}

		if(isset($this->input['state']))
		{
			if(-1!=$this->input['state'])
			{
				$condition .= ' AND s.status = ' . intval($this->input['state']);
			}
		}
	
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  s.create_at > '".$yesterday."' AND s.create_at < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  s.create_at > '".$today."' AND s.create_at < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  s.create_at > '".$last_threeday."' AND s.create_at < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  s.create_at > '".$last_sevenday."' AND s.create_at < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		if ($this->input['_type'])
		{
			$before3hours = TIMENOW - 3*3600;
			$min_comment_count = 100;
			$min_transmit_count = 100;
			switch (intval($this->input['_type']))
			{
				case 1://最新更新
					$condition .= " AND s.create_at > '" . $before3hours . "' AND s.create_at < '" . TIMENOW . "'";
					break;
				case 2://最多评论
					$condition .= " AND se.comment_count > '" . $min_comment_count . "'";
					break;
				case 3://最多转发 
					$condition .= " AND se.transmit_count > '" . $min_transmit_count . "'";
					break;
				default://最多举报
				//	$condition .= " AND se.transmit_count > '" . $min_transmit_count . "'";
					break;
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