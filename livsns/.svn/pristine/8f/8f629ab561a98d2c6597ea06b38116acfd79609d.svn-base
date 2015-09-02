<?php
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class videoStaInfoApi extends adminBase
{
	var $mType,$mBeginTime,$mEndTime;
	function __construct()
	{
		parent::__construct(); 
		//处理时间参数，如果传递了参数就返回要查询的那天的数据，否则就返回今天的数据
		$this->dealTime();
		if(!$this->mType)
		{
			exit('参数缺失');
		}
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	//查询网台/网台视频/网台关注的总数及今日新增数
	function show()
	{
		$result = array();
		$result = $this->db->query_first('select * from ' . DB_PREFIX . 'sv_statistic where id = ' . $this->mType . ' and create_time between ' . $this->mBeginTime . ' and ' . $this->mEndTime);
		
		if(!$result)
		{
			$result = $this->query_statics();
		}
		
		$this->setXmlNode("stationInfos",'stationInfo');
		$this->addItem($result);
		$this->output();
	}
	
	//查询相关统计数据并入库
	private function query_statics()
	{
		if($this->mType == 1)
		{
			$sql = 'Select sum( case when state = 1 then 1 else 0 end) as total_num from ' . DB_PREFIX . 'network_station where create_time <= ' . $this->mEndTime;
			$today_sql = 'Select sum( case when state = 1 then 1 else 0 end) as total_num from ' . DB_PREFIX . 'network_station where create_time between ' . $this->mBeginTime . ' and ' . $this->mEndTime;
		}
		else if($this->mType == 2)
		{
			$sql = 'Select sum(1) as total_num from ' . DB_PREFIX . 'network_programme where  create_time <= ' . $this->mEndTime;
			$today_sql = 'Select sum(1) as total_num from ' . DB_PREFIX . 'network_programme where  create_time between ' . $this->mBeginTime . ' and ' . $this->mEndTime;
		}
		else
		{
			$sql = 'Select sum(1) from ' . DB_PREFIX . 'station_concern where create_time <=' . $this->mEndTime;
			$today_sql = 'Select sum(1) as total_num from ' . DB_PREFIX . 'station_concern where  create_time between ' . $this->mBeginTime . ' and ' . $this->mEndTime;
		}
		$total = $this->db->query_first($sql);
		$total = intval($total['total_num']);
		$today_total = $this->db->query_first($today_sql);
		$today_total = intval($today_total['total_num']);
		
		$num = array(
			'id' => $this->mType,
			'total' => $total,
			'today_total' => $today_total
		);
		
		$this->db->query('insert ignore into ' . DB_PREFIX . 'sv_statistic values (' . $this->mType . ',' . $total . ',' . $today_total . ',' . $this->mBeginTime .')');
		return $num;
	}
	
	//处理时间,及类型
	private function dealTime()
	{ 
		$this->mBeginTime = urldecode($this->input['begin_time'])? strtotime(urldecode($this->input['begin_time']) . ' 00:00:00') : strtotime(date('Y-m-d',time()) . ' 00:00:00');
		$this->mEndTime = urldecode($this->input['end_time'])? strtotime(urldecode($this->input['end_time']) . ' 23:59:59') : strtotime(date('Y-m-d',time()) . ' 23:59:59');
		$this->mType = intval($this->input['id']);//1:网台总数/今日新增数   2:网台中的视频总数/今日新增数
	}
	
	//获取网台中视频点击量topx
	public function get_vediorange()
	{
		$result = array();
		$qid = $this->db->query('select * from ' . DB_PREFIX . '_vedioclick_s where create_time between ' . $this->mBeginTime . ' and ' . $this->mEndTime . ' limit 0,10');
		while(false != ($r = $this->db->fetch_array($qid)))
		{
			$result[$r['range_num']] = $r;
		}
		
		if(!$result)
		{
			$result = $this->query_vc_num();
		}
		
		$this->setXmlNode('vedioClickTopxs','vedioClickTopx');
		$this->addItem($result);
		$this->output();  
	}
	
	private function query_vc_num()
	{
		$sql = 'Select  distinct  v.id, v.title,v.user_id,u.username,v.click_count  from ' . DB_PREFIX . 'network_programme p left join ' . DB_PREFIX . 'video v on p.video_id = v.id  left join ' . DB_PREFIX . 'user u on v.user_id = u.id order by v.click_count desc limit 0,10';
		$qid = $this->db->query($sql);
		$arr = array();
		$i = 1;
		$insert_sqls = 'insert ignore into ' . DB_PREFIX . '_vedioclick_s(`vid`,`v_title`,`user_id` ,`user_name`,`click_count`,`create_time`,`range_num` ) values ';
		$sp = '';
		while(false != ($r = $this->db->fetch_array($qid)))
		{
			$arr[$i] = $r;
			$insert_sqls .= $sp . '(' . intval($r['id']) . ',"' . addslashes($r['title']) . '",' . $r['user_id'] . ',"' . addslashes($r['username']) . '",' . intval($r['click_count']) . ',' . $this->mBeginTime . ',' . $i . ')';
			$sp = ',';
			$i++;
		}
		
		$this->db->query($insert_sqls);
		return $arr;
	}
}
$videoStaInfoApi = new videoStaInfoApi();
$action = $_REQUEST['a'];
if (!method_exists($videoStaInfoApi,$action))
{
	$action = 'show';
} 
$videoStaInfoApi->$action();