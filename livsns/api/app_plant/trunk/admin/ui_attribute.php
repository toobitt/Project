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
 * @description 前端属性后台取值接口
 **************************************************************************/

define('MOD_UNIQUEID','ui_attribute');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/ui_attribute_mode.php');
require_once(CUR_CONF_PATH . 'lib/attribute_relate_mode.php');
class ui_attribute extends adminReadBase
{
	private $mode;
	private $attr_relate_mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new ui_attribute_mode();
		$this->attr_relate_mode = new attribute_relate_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY ua.order_id DESC ';
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

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND ua.id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND ua.name  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if($this->input['ui_id'])
		{
		    $condition .= " AND ua.ui_id = '" .$this->input['ui_id']. "' ";
		}
		
		if($this->input['group_id'])
		{
		    $condition .= " AND ua.group_id = '" .$this->input['group_id']. "' ";
		}
		
	    if($this->input['role_type_id'])
		{
		    $condition .= " AND ua.role_type_id = '" .$this->input['role_type_id']. "' ";
		}
		
	    if($this->input['attr_type_id'])
		{
		    $condition .= " AND ua.attr_type_id = '" .$this->input['attr_type_id']. "' ";
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND ua.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND ua.create_time <= '".$end_time."'";
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
					$condition .= " AND  ua.create_time > '".$yesterday."' AND ua.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  ua.create_time > '".$today."' AND ua.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  ua.create_time > '".$last_threeday."' AND ua.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  ua.create_time > '".$last_sevenday."' AND ua.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}
	
	public function detail()
	{
		if($this->input['id'])
		{
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	
	//获取某个UI下的属性
    public function get_attr_by_ui()
	{
	    $ui_id = $this->input['ui_id'];
	    if(!$ui_id)
	    {
	        $this->errorOutput(NO_UI_ID);
	    }
	    $ret = array();
		$offset = $this->input['_offset'] ? $this->input['_offset'] : 0;			
		$count = $this->input['_count'] ? intval($this->input['_count']) : 20;
		$condition = " AND ar.ui_id = '" . $ui_id . "' AND ar.role_type_id = 2 ";
		$orderby = '  ORDER BY ar.order_id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$data = $this->attr_relate_mode->show($condition,$orderby,$limit);
		$total = $this->attr_relate_mode->count($condition);
		$this->addItem_withkey('data', $data);
		$this->addItem_withkey('total', $total);
		$this->output();
	}
}

$out = new ui_attribute();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();