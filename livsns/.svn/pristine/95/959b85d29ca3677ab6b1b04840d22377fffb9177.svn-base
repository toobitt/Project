<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-15
 * @encoding    UTF-8
 * @description 外部接口获取崩溃报告
 **************************************************************************/
define('MOD_UNIQUEID', 'crash_report');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/crash_report_mode.php');

class crash_report extends outerReadBase
{
    private $mode;
    public function __construct()
    {
        parent::__construct();
        $this->mode = new crash_report_mode();
    }

    public function detail(){}
    public function count(){}

    public function show()
    {
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
    }
    
    public function get_condition()
	{
		$condition = '';
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		//根据应用id来查询
		if($this->input['app_id'])
		{
		    $condition .= " AND app_id = '" .$this->input['app_id']. "' ";
		}
		
		//根据应用名称来查询
		if($this->input['app_name'])
		{
		    $condition .= " AND app_name = '" .$this->input['app_name']. "' ";
		}
		
		//根据系统版本来查询
		if($this->input['systemversion'])
		{
		    $condition .= " AND systemversion = '" .$this->input['systemversion']. "' ";
		}

		//系统平台
		if($this->input['platform'])
		{
		    $condition .= " AND platform = '" .$this->input['platform']. "' ";
		}

		//应用版本
		if($this->input['version'])
		{
		    $condition .= " AND version = '" .$this->input['version']. "' ";
		}
		
		if($this->input['debug'])
		{
		    $condition .= " AND debug = 1 ";
		}
		else 
		{
		    $condition .= " AND debug = 0 ";
		}

		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = $today + 24 * 3600;
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = $today - 24 * 3600;
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = $today - 24 * 3600 * 2;
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = $today - 24 * 3600 * 6;
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}
}

$out = new crash_report();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();