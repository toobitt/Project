<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
*@public function show|count|detail|unknow
*@private function get_condition
*
* $Id: change_plan.php 
***************************************************************************/
require('global.php');
class changePlanApi extends BaseFrm
{
	/**
	 * 构造函数
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include change_plan.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/change_plan.class.php');
		$this->obj = new changePlan();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 串联单计划显示
	 * @name show
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $condition 检索条件
	 * @param $channel_id int 频道ID
	 * @return $channel_name string 频道名称
	 * @return $time_arr array 时间轴
	 * @return $info array 每天串联单计划
	 */
	function show()
	{
		$condition = $this->get_condition();
		$channel_id = intval($this->input['channel_id']);
		if($channel_id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "channel WHERE id=" . $channel_id;
			$channel_info = $this->db->query_first($sql);
			$this->addItem_withkey('channel_name', $channel_info['name']);
		}
		$info = $this->obj->show($condition);
		$time_arr = array();
		$all = array();
		foreach($info as $key => $value)
		{
			$time_arr[] = $value['start'];
			$time_arr[] = $value['end'];
			$all[$value['week_num']][] = $value;
			
		}
		$time_arr = array_unique($time_arr);
		if(!empty($time_arr))
		{
			$time_show = array(strtotime('00:00:00'));
			foreach($time_arr as $k => $v)
			{
				$time_show[] = $v;
			}
		}
		if(is_array($time_show))
		{
			sort($time_show);
		}
		$this->addItem_withkey('time_arr', $time_show);
		$this->addItem_withkey('info', $all);
		$this->output();
	}

	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count($condition);//暂时这样处理
		echo json_encode($info);
	}
			
	/**
	 * 检索条件
	 * @name get_condition
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';
		if($this->input['channel_id'])
		{
			$condition .= ' AND p.channel_id='.$this->input['channel_id'];
		}

		if($this->input['week_num'])
		{
			$condition .= ' AND r.week_num='.$this->input['week_num'];
		}

		return $condition;
	}
	
	/**
	 * 取单条信息
	 * @name detail
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道信号ID
	 * @return $info array 单条串联单计划信息
	 */
	public function detail()
	{
		if(!$this->input['channel_id'])
		{
			$this->errorOutput("缺少频道ID");
		}
		$condition = $this->get_condition();
		$info = $this->obj->detail($condition);
		$this->addItem($info);
		$this->output();
	}

	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new changePlanApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			