<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
define('MOD_UNIQUEID','interview');//模块标识
class interview extends adminReadBase
{
	function __construct()
	{
		$this->mPrmsMethods = array(
			'manage'		=>'管理',
		);
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
    public function index()
    {
    	
    }
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$orderby = ' ORDER BY order_id  DESC';
		$condition = $this->get_condition();
		$sql = 'SELECT * FROM '.DB_PREFIX.'interview WHERE 1 '.$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$a = unserialize($r['moderator']);
			$r['moderator'] = $a[key($a)];
			$r['time_out'] = $this->interval_time(($r['end_time']-$r['start_time']));
			$r['start_time'] = @date('m-d H:i',$r['start_time']);
				
			if ($r['end_time'] < TIMENOW)
			{
				$r['end_time'] = '<span style="color:red">已经结束</span>';
			}else {
				$r['end_time'] = @date('m-d H:i',$r['end_time']);
			}
			$r['moderator'] = $r['moderator'] ? $r['moderator'] : '&nbsp';
			/*
			$r['isclose'] = $r['isclose'] ? '<span style="color:red">已关闭</span>' : '<span style="color:green">未关闭</span>';
			$r['is_pre_ask'] = $r['is_pre_ask'] ? '<span style="color:green">能</span>' : '<span style="color:red">不能</span>';
			$r['need_login'] = $r['need_login'] ? '<span style="color:red">是</span>' : '<span style="color:green">否</span>';
			$r['is_lishi'] = $r['is_lishi'] ? '<span style="color:red">是</span>' : '<span style="color:green">否</span>';
			*/
			$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			$r['user_name'] = $r['user_name'] ? $r['user_name'] : '匿名用户';
			$this->addItem($r);
		}
		$this->output();
	}

	/**
	 * 日期处理函数
	 * 
	 */
	function interval_time($time){
		$days = $this->interval_day($time);        //多少天
		$hour = $this->interval_hour($time-86400*$days);
		$minute = $this->interval_minute($time-86400*$days-3600*$hour);

		$str = "";
		$str.= $days ? $days."天":"";
		$str.= $hour ? $hour."小时":"";
		$str.= $minute ? $minute."分":"";
		return $str;
	}
	function interval_day($time){
		if ($time>=86400){
			return floor($time/86400);        //多少天
		}
	}
	function interval_hour($time){
		if ($time>=3600 and $time<86400){
			return floor($time/3600);        //多少小时
		}
	}
	function interval_minute($time){
		if ($time>=60 and $time<3600){
			return floor($time/60);        //多少分钟
		}
	}



	public function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'interview '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

	public function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND start_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND start_time <= ".$end_time;
		}
		if($this->input['interview_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['interview_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  start_time > ".$yesterday." AND start_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  start_time > ".$today." AND start_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  start_time > ".$last_threeday." AND start_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND start_time > ".$last_sevenday." AND start_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	function detail()
	{
		if (!$this->input['id'])
		{
			return ;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'interview WHERE	id = '.urldecode($this->input['id']);
		$r = $this->db->query_first($sql);
		if (($r['end_time']-$r['start_time'])%86400==0)
		{
			$r['input_time'] = ($r['end_time']-$r['start_time'])/86400;
			$r['input_time_unit'] = 'day'; 
		}elseif (($r['end_time']-$r['start_time'])%3600==0)
		{
			$r['input_time'] = ($r['end_time']-$r['start_time'])/3600;
			$r['input_time_unit'] = 'hour';
		}elseif (($r['end_time']-$r['start_time'])%60==0)
		{
			$r['input_time'] = ($r['end_time']-$r['start_time'])/60;
			$r['input_time_unit'] = 'minute';
		}
		$r['notice_time'] = date('Y-m-d H:i:s',$r['notice_time']);
		$r['start_time'] = date('Y-m-d H:i:s',$r['start_time']);
		$r['live_source'] = explode(',', $r['live_source']);
		$this->addItem($r);
		$this->output();
	}
	//显示直播源信息
	public function show_liv()
	{
		$this->curl = new curl($this->settings['channel']['host'], $this->settings['channel']['dir']);
		$this->curl->initPostData();
		$this->curl->addRequestData('a','show');
		$channel = $this->curl->request('channel.php');
		foreach ($channel as $key=>$val)
		{
			$arr[$val['id']] = $val['name'];
		}
		$this->addItem($arr);
		$this->output();
	}
	
	//显示权限内容
	function  show_authority()
	{
		if (!$this->input['id']){
			return ;
		}
		$sql = 'SELECT prms FROM '.DB_PREFIX.'interview WHERE	id = '.urldecode($this->input['id']);
		$r = $this->db->query_first($sql);
		$arr = unserialize($r['prms']);
		$this->addItem($arr);
		$this->output();
	}
	function show_opration(){
		if (!$this->input['id'])
		{
			return ;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'interview WHERE	id = '.urldecode($this->input['id']);
		$res = $this->db->query($sql);
		while ($r = $this->db->fetch_array($res))
		{
			$a = unserialize($r['moderator']);
			$r['moderator'] = implode(',', $a);
			$r['moderator'] = $a[key($a)];
			$r['time_out'] = $this->interval_time(($r['end_time']-$r['start_time']));
			$r['start_time'] = @date('Y-m-d H:i:s',$r['start_time']);
				
			if ($r['end_time'] < TIMENOW)
			{
				$r['end_time'] = '<span style="color:red">已经结束</span>';
			}else {
				$r['end_time'] = @date('Y-m-d H:i:s',$r['end_time']);
			}
			$r['moderator'] = $r['moderator'] ? $r['moderator'] : '&nbsp';
	        $b = unserialize($r['honor_guests']);
	        $r['honor_guests'] = implode(',', $b);
	        $r['honor_guests'] = $r['honor_guests'] ? $r['honor_guests'] : '&nbsp';
			$r['isclose'] = $r['isclose'] ? '<span style="color:red">已关闭</span>' : '<span style="color:green">未关闭</span>';
			$r['is_pre_ask'] = $r['is_pre_ask'] ? '<span style="color:green">能</span>' : '<span style="color:red">不能</span>';
			$r['need_login'] = $r['need_login'] ? '<span style="color:red">是</span>' : '<span style="color:green">否</span>';
			$r['is_lishi'] = $r['is_lishi'] ? '<span style="color:red">是</span>' : '<span style="color:green">否</span>';
			$this->addItem($r);
		}
		$this->output();
	}

}

$ouput= new interview();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
