<?php
define('MOD_UNIQUEID','survey');
/*
if($_REQUEST['a'] == 'check_voted' || $_REQUEST['a'] == 'get_result_cache')
{
	define('WITHOUT_DB',true);
}
*/
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/survey_mode.php');
/*
$_INPUT['appid'] = 55;
$_INPUT['appkey'] = 'GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7';
$_INPUT['device_token'] = '44297956-6bce-4e61-9cfc-1b5c2037d92e';
*/
class survey extends outerReadBase
{
	private $mode;
	private $is_redis;
    public function __construct()
	{
		parent::__construct();
		$device_token = $this->input['device_token'];
		$salt = $this->input['salt'];
		if($device_token && IS_ENDEVICE) //设备号加密解密
		{
			if(!$salt || !is_numeric($salt)  || strlen($salt) != 13)
			{
				$this->errorOutput(SALT_ERROR);
			}
			$dt = substr($device_token,0,strlen($device_token) - 8);
			$this->input['device_token'] = substr($dt,0,10).substr($dt,15);
		}
		$this->mode = new survey_mode();
		$this->is_redis = $this->settings['redis'] ? 1 : 0;
		if($this->is_redis)
		{
			$this->redis = new Redis();
			$this->redis->connect($this->settings['redis']['redis1']['host'], $this->settings['redis']['redis1']['port']);
			$this->redis->auth(REDIS_KEY);
		}
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
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show_list($condition,$orderby,$limit);
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
		$condition = $this->get_condition();;
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->detail($this->input['id']);
		if(!$ret)
		{
			$this->errorOutput(NODATA);
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = ' AND status = 1 ';
		
		if(trim($this->input['id']))
		{			
			$condition .= ' AND id IN ('.trim($this->input['id']) .')';
		}
		
		if(trim($this->input['node_id']))
		{			
			$condition .= ' AND node_id IN ('.trim($this->input['node_id']) .')';
		}
						
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  name  LIKE "%'.trim(($this->input['k'])).'%"';
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
		
		return $condition;
	}
	
	/**
	 * 得到统计结果
	 * Enter description here ...
	 */
	public function show_result()
	{
		$id = $this->input['id']; //问卷id
		if(!$id)
		{
			$this->errorOutput("没有问卷id");
		}
		$ret = $this->mode->get_result($id);
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 得到其他的答案以及对应用户信息
	 * Enter description here ...
	 */
	public function show_other_result()
	{
		$problem_id = $this->input['problem_id']; //问题id
		if(!$problem_id)
		{
			$this->errorOutput("没有问题id");
		}
		$ret = $this->mode->get_other_result($problem_id);
		$this->addItem($ret);
		$this->output();
	}
	
	
	//查询是否投过票
	public function check_voted()
	{
		$id = intval($this->input['id']);
		$device_token = trim($this->input['device_token']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		if($this->is_redis)
		{
			$survey = $this->redis->get('survey_'.$id);
			$survey = $survey ? json_decode($survey,1) : array();
		}else 
		{
			$survey = $this->mode->get_survey('id='.$id);
		}
		if($this->is_redis)
		{
			$dVote = $this->redis->hgetall(md5($device_token).'_'.$id);
	        if($device_token)
	        {
	        	$verifysalt = md5(TIMENOW);
	        	
	        	$redis2 = new Redis();
				$redis2->connect($this->settings['redis']['redis2']['host'], $this->settings['redis']['redis2']['port']);
				$redis2->auth(REDIS_KEY);
		        $redis2->setex('vs_'.md5($device_token).'_'.$id,SALT_TIME,$verifysalt);
		        setcookie('vote_sid',$verifysalt,TIMENOW+SALT_TIME);
		        
	         	if((TIMENOW - $dVote['last_time']) <= $survey['device_limit_time'] * 3600)
	        	{
	        		$data['back'] = 2;
	        	}
	        	elseif($dVote['vote_num'] && $dVote['vote_num'] >= $survey['device_limit_num'] )
		        {
		        	$data['back'] = 1;
		        }
		        else 
		        {
			       $data['back'] = 0;
		        }
	        }else 
	        {
	        	$data['back'] = 0;
	        }
	        $data['last_time'] = intval($dVote['last_time']);
	        $data['vote_num'] = intval($dVote['vote_num']);
	        $data['total'] = $this->redis->get('g_'.$id) + $this->redis->get('inig_'.$id);
	        $this->addItem($data);
			$this->output();
		}
        else 
        {
	        if($device_token && !$this->user['user_id']) //用户未登录，但是有设备号
			{			
				$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'record_person WHERE survey_id = '.$id .' and device_token = "'.md5($device_token) .'"';
				$backinfo = $this->db->query_first($sql);
				if($backinfo['total'] && $backinfo['total'] >= $survey['device_limit_num'])
				{
					$data['back'] = 1;
				}
				else 
				{
					$data['back'] = 0;
				}
			}
			elseif($this->user['user_id']) //登录用户
			{
				$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'record_person WHERE survey_id = '.$id .' and user_id = "'.$this->user['user_id'] .'"';
				$backinfo = $this->db->query_first($sql);
				if($backinfo['id'])
				{
					$data['back'] = 1;
				}
				else 
				{
					$data['back'] = 0;
				}
			}
			else
			{
				$data['back'] = 0;
			}
			if($data['back'])
			{
				$file = CACHE_DIR.'r'.$id.'.json';
				if(file_exists($file))
				{
					$ret = json_decode(@file_get_contents($file),1);
				}else 
				{
					$ret = $this->mode->getResult($id);
					@file_put_contents($file, json_encode($ret));
				}
				$ret = $ret ? $ret : array();
			}else 
			{
				$ret['total'] = $survey['submit_num'] + $survey['ini_num'];
			}
			$sql = 'SELECT max(create_time) as last_time FROM '.DB_PREFIX.'record_person WHERE survey_id = '.$id .' and device_token = "'.md5($device_token) .'"';
			$ba = $this->db->query_first($sql);
			$ret['last_time'] = intval($ba['last_time']);
	        $ret['vote_num'] = intval($backinfo['total']);
			$ret['back'] = $data['back'];
			$this->addItem($ret);
			$this->output();
        }	
	
	}
	
	
	public function get_result_cache()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		if($this->is_redis)
		{
			$ret = $this->getredis($id);
		}else 
		{
			if(file_exists($file))
			{
				$ret = json_decode(@file_get_contents($file),1);
			}else 
			{
				$ret = $this->mode->getResult($id);
				@file_put_contents($file, json_encode($ret));
			}
		}
		$this->addItem($ret);
		$this->output();
		
	}
	
	private function getredis($id)
	{
		$init = $this->redis->hgetall('inis_'.$id);
		$more = $this->redis->hgetall('s_'.$id);
		if($init)
		{
			foreach ($init as $kk=>$vv)
			{
				$data[$kk] = $vv + intval($more[$kk]);
			}
		}
		$total = $this->redis->get('g_'.$id) + $this->redis->get('inig_'.$id); 
		$ret['data'] = $data;
		$ret['total'] = $total;
		return $ret;
	}
}

$out = new survey();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>