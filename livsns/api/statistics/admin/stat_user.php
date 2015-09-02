<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: news.php 6930 2012-05-31 07:16:07Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','statistics_user');//模块标识
class stat_userApi extends adminBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/statistics.class.php');
		$this->obj = new statistics();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$uniqueid = '';
		$appname = $record = array();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
		
		$user = $this->obj->get_user('',$offset,$count,'user_id');
		$user_id_arr = array_keys($user);
		
		if($user_id_arr)
		{
			$record = $this->obj->get_records_num_by_user($this->get_usercondition($user_id_arr));
			foreach($user_id_arr as $k=>$v)
			{
				$sort_user[$v] = $record[$v]['all']?$record[$v]['all']:0;
			}
			arsort($sort_user);
			foreach($sort_user as $k=>$v)
			{
				$ret_user[$k] = $user[$k];
			}
		}
		$app = $this->obj->get_apps();
		$result['user'] = $ret_user;
		$result['record'] = $record;
		$result['app'] = $app;
		$this->addItem($result);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."user WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

	private function get_condition()
	{
		$condition = '';
		return $condition;	
	}
	
	private function get_usercondition($user_id_arr = array())
	{
		$app_uniqueid = urldecode($this->input['_id']);
		$module_uniqueid = urldecode($this->input['module_uniqueid']);
		$start_time = urldecode($this->input['start_time']);
		$end_time = urldecode($this->input['end_time']);
		$con = "";
		if($user_id_arr)
		{
			$con .= " AND douser_id in(".implode(',',$user_id_arr).") ";
		}
		if($app_uniqueid && $app_uniqueid!=-1)
		{
			$con .= " AND app_uniqueid='".$app_uniqueid."' ";
		}
		if($module_uniqueid && $module_uniqueid!=-1)
		{
			$con .= " AND module_uniqueid='".$module_uniqueid."' ";
		}
		$start_time = trim(urldecode($this->input['start_time']));
		if($start_time)
		{
			$start_time = strtotime($start_time);
			$con .= " AND create_time >= '".$start_time."'";
		}
		$end_time = trim(urldecode($this->input['end_time']));
		if($end_time)
		{
			$end_time = strtotime($end_time);
			$con .= " AND create_time < '".$end_time."'";
		}
		$today = strtotime(date('Y-m-d'));
		$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
		switch(intval($this->input['date_search']))
		{
			case -1://所有时间段
				break;
			case 2://昨天的数据
				$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
				$con .= " AND  create_time >= '".$yesterday."' AND create_time < '".$today."'";
				break;
			case 3://今天的数据
				$con .= " AND  create_time >= '".$today."' AND create_time < '".$tomorrow."'";
				break;
			case 4://最近3天
				$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
				$con .= " AND  create_time >= '".$last_threeday."' AND create_time < '".$tomorrow."'";
				break;
			case 5://最近7天
				$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
				$con .= " AND  create_time >= '".$last_sevenday."' AND create_time < '".$tomorrow."'";
				break;
			default://所有时间段
				break;
		}
		return $con;	
	}
	
	public function delete()
	{
		$user_ids = $this->input['stat_user_id'];
		$module_id = $this->input['module_id'];
		$date_search = intval($this->input['date_search']);
		$start_time = trim(urldecode($this->input['start_time']));
		$end_time = trim(urldecode($this->input['end_time']));
		if(!$user_ids)
		{
			$this->errorOutput('NO_USER_ID');
		}
		
		$con = '';
		$con .= " AND douser_id in(".$user_ids.") ";
		
		if($module_id && $module_id!=-1)
		{
			$con .= " AND module_uniqueid='".$module_id."' ";
		}
		
		if($start_time)
		{
			$start_time = strtotime($start_time);
			$con .= " AND create_time >= '".$start_time."'";
		}
		if($end_time)
		{
			$end_time = strtotime($end_time);
			$con .= " AND create_time < '".$end_time."'";
		}
		$today = strtotime(date('Y-m-d'));
		$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
		switch($date_search)
		{
			case -1://所有时间段
				break;
			case 2://昨天的数据
				$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
				$con .= " AND  create_time >= '".$yesterday."' AND create_time < '".$today."'";
				break;
			case 3://今天的数据
				$con .= " AND  create_time >= '".$today."' AND create_time < '".$tomorrow."'";
				break;
			case 4://最近3天
				$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
				$con .= " AND  create_time >= '".$last_threeday."' AND create_time < '".$tomorrow."'";
				break;
			case 5://最近7天
				$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
				$con .= " AND  create_time >= '".$last_sevenday."' AND create_time < '".$tomorrow."'";
				break;
			default://所有时间段
				break;
		}
		$this->obj->delete($con);
		
		$this->addItem($user_ids);
		$this->output();
	}

	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new stat_userApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			