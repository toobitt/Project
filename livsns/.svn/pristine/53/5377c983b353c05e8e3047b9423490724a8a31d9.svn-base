<?php
require('global.php');
define('MOD_UNIQUEID','live_time_shift');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/program.class.php');
require_once(ROOT_PATH . 'lib/class/livmedia.class.php');
require_once(ROOT_PATH . 'lib/class/live.class.php');
require_once(CUR_CONF_PATH . 'lib/live_time_shift_mode.php');
class live_time_shift extends adminReadBase
{
	private $mode;
	public function __construct()
	{
		$this->mPrmsMethods = array(
			'manger' => '管理',
		);
		parent::__construct();
		$this->mode = new live_time_shift_mode();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	public function detail(){}
	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		
		$video_ids = array();
		$channel_ids = array();
		foreach($ret AS $k => $v)
		{
			$video_ids[] = $v['video_id'];
			$channel_ids[] = $v['channel_id'];
		}
		/*******************此处是为了请求视频库的图片******************/
		if($video_ids)
		{
			$livmedia = new livmedia();
			$video = $livmedia->get_videos(implode(',',$video_ids));
			$video_img = array();
			$video_url = array();
			if($video && $video[0])
			{
				foreach($video[0] AS $k => $v)
				{
					$video_img[$v['id']] = @unserialize($v['img_info']);
					$video_url[$v['id']] = $v['hostwork'] . '/' .$v['video_path'] . $v['video_filename'];
				}
			}
		}
		/*******************此处是为了请求视频库的图片*****************/
		
		
		/*******************此处是为了频道名称***********************/
		if($channel_ids)
		{
			$mLive = new live();
			$channel = $mLive->getChannelById(implode(',',$channel_ids), -1);
			$channel_name[] = array();
			if($channel)
			{
				foreach($channel AS $k => $v)
				{
					$channel_name[$v['id']] = $v['name'];
				}
			}
		}
		/*******************此处是为了频道名称***********************/
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				if($video_img)
				{
					$v['img_info'] = $video_img[$v['video_id']];
				}
				
				if($channel_name)
				{
					$v['channel_name'] = $channel_name[$v['channel_id']];
				}
				
				if($video_url)
				{
					$v['video_url'] = $video_url[$v['video_id']];
				}

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
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  title  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
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
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		$condition .=' AND live_split_callback = 0';

		return $condition;
	}

	public function load_time_shift()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 50;
		$cond = array(
			'offset' => $offset,
			'count' => $count,
			'is_stream' => 0,
			'fetch_live' => 1,
		);
		$mLive = new live();
		$channel = $mLive->getChannelInfo($cond);
		$return['channel'] = $channel;
		$this->addItem($return);
		$this->output();
	}
	
	//获取频道对应的节目单
	public function get_program()
	{
		if(!$this->input['channel_id'])
		{
			$this->errorOutput(NOID);
		}
		//根据频道id查询出该频道对应的节目单
		$program = new program();
		$program_data = $program->getTimeshift($this->input['channel_id']);
		if($program_data)
		{
			$this->addItem($program_data);
			$this->output();
		}
	}
	
	//获取可选的时移服务器
	private function get_time_shift_server()
	{
		//查询出数据库中打开的服务器
		$sql = " SELECT * FROM " .DB_PREFIX. "time_shift_server WHERE is_open = 1";
		$q = $this->db->query($sql);
		$server = array();
		while($r = $this->db->fetch_array($q))
		{
			$url = 'http://' . $r['host'] . ($r['port'] ? ':' . $r['port'] : '');
			if(check_shift_server($url))
			{
				$server[$r['id']] = $r;
			}
		}
		return $server;
	}

	//获取时移的分类（从视频库去取）
	public function get_live_sort_name()
	{
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','show');
		$curl->addRequestData('fid','3');
		$node = $curl->request('vod_media_node.php');
		if($node)
		{
			$this->addItem($node);
			$this->output();
		}
	}
}

$out = new live_time_shift();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>