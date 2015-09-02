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
define('MOD_UNIQUEID','statistics');//模块标识
class statlistApi extends adminReadBase
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
	
	public function index()
	{
		
	}
	
	public function show()
	{
		$uniqueid = '';
		$data = $record = array();
		$app_uniqueid = urldecode($this->input['_id']);
		$module_uniqueid = urldecode($this->input['module_uniqueid']);
		$douser_id = urldecode($this->input['douser_id']);
		$stat_type = urldecode($this->input['stat_type']);
		$start_time = urldecode($this->input['start_time']);
		$end_time = urldecode($this->input['end_time']);
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
		$app = $this->obj->get_apps($this->get_condition());
		if($this->input['_id'])
		{
			include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
			$module = $auth->get_module('',urldecode($this->input['_id']));
		}
		$user = $this->obj->get_user();
		$record = $this->get_statlist($offset,$count,$app_uniqueid,$module_uniqueid,$douser_id,$stat_type,$start_time,$end_time);
		$data['app_uniqueid'] = $app_uniqueid;
		$data['module_uniqueid'] = $module_uniqueid;
		$data['douser_id'] = $douser_id;
		$data['stat_type'] = $stat_type;
		$data['app'] = $app;
		$data['user'] = $user;
		$data['start_time'] = $start_time;
		$data['end_time'] = $end_time;
		$data['record'] = $record;
		if($record)
		{
			$numdata = $this->getcount();
			$data['num'] = $numdata['total'];
		}
		//print_r($data['app']);exit;
		$this->addItem($data);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."records r WHERE 1 ".$this->get_usercondition();
		echo json_encode($this->db->query_first($sql));
	}
	
	public function detail()
	{
		
	}
	
	public function getcount()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."records r WHERE 1 ".$this->get_usercondition();
		return $this->db->query_first($sql);
	}
	
	private function get_condition()
	{
		$bundle = urldecode($this->input['_id']);
		$condition = " WHERE a1.father!=0";
		if(!empty($bundle))
		{
			$condition = " WHERE a1.father!=0 AND a2.bundle='".$bundle."'";
		}
		return $condition;		
	}
	
	private function get_usercondition()
	{
		$app_uniqueid = urldecode($this->input['_id']);
		$module_uniqueid = urldecode($this->input['module_uniqueid']);
		$douser_id = urldecode($this->input['douser_id']);
		$stat_type = urldecode($this->input['stat_type']);
		$start_time = urldecode($this->input['start_time']);
		$end_time = urldecode($this->input['end_time']);
		$con = "";
		if($app_uniqueid && $app_uniqueid!=-1)
		{
			$con .= " AND r.app_uniqueid='".$app_uniqueid."' ";
		}
		if($module_uniqueid && $module_uniqueid!=-1)
		{
			$con .= " AND r.module_uniqueid='".$module_uniqueid."' ";
		}
		if($douser_id && $douser_id!=-1)
		{
			$con .= " AND r.douser_id='".$douser_id."' ";
		}
		if($stat_type && $stat_type!=-1)
		{
			$con .= " AND r.type='".$stat_type."' ";
		}
		$start_time = trim(urldecode($this->input['start_time']));
		if($start_time)
		{
			$start_time = strtotime($start_time);
			$con .= " AND r.create_time >= '".$start_time."'";
		}
		$end_time = trim(urldecode($this->input['end_time']));
		if($end_time)
		{
			$end_time = strtotime($end_time);
			$con .= " AND r.create_time < '".$end_time."'";
		}
		$today = strtotime(date('Y-m-d'));
		$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
		switch(intval($this->input['date_search']))
		{
			case -1://所有时间段
				break;
			case 2://昨天的数据
				$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
				$con .= " AND  r.create_time >= '".$yesterday."' AND r.create_time < '".$today."'";
				break;
			case 3://今天的数据
				$con .= " AND  r.create_time >= '".$today."' AND r.create_time < '".$tomorrow."'";
				break;
			case 4://最近3天
				$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
				$con .= " AND  r.create_time >= '".$last_threeday."' AND r.create_time < '".$tomorrow."'";
				break;
			case 5://最近7天
				$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
				$con .= " AND  r.create_time >= '".$last_sevenday."' AND r.create_time < '".$tomorrow."'";
				break;
			default://所有时间段
				break;
		}
			
		return $con;	
	}

	public function get_statlist($offset,$count,$app_uniqueid,$module_uniqueid,$user_id,$stat_type,$start_time,$end_time)
	{	
		$records = array();
		$con = $this->get_usercondition();
//		if($module_uniqueid && $user_id && $stat_type )
//		{
			$records = $this->obj->get_records($offset,$count,$con);
//		}
		return $records;
	}
	
	public function delete()
	{
		if(!$ret = $this->obj->delete_by_id())
		{
			$this->errorOutput('删除失败');
		}
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

$out = new statlistApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			