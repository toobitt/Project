<?php
define('MOD_UNIQUEID','win_info');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/win_info_mode.php');
class win_info extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
	    	if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'] || count(explode(MOD_UNIQUEID, $this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])) <= 1)
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$this->mode = new win_info_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		
		//抽奖id
		$id = intval($this->input['lottery_id']);
		if($this->input['need_lottery'] && $id)
		{
			$sql = "SELECT l.title,l.start_time,l.end_time,l.time_limit,m.host,m.dir,m.filepath,m.filename FROM " . DB_PREFIX . "lottery l 
					LEFT JOIN " . DB_PREFIX ."materials m 
						ON l.indexpic_id = m.id
					WHERE l.id = {$id} limit 0,1";
			
			$res = $this->db->query_first($sql);
			
			
			$res['start_times'] = date('m.d',$res['start_time']);
			$res['end_times'] = date('m.d',$res['end_time']);
			
			if($res['time_limit'])
			{
				$res['effective_time'] = $res['start_times'] . '-' . $res['end_times'];
				
				if($res['start_time'] <= TIMENOW && $res['end_time'] >= TIMENOW)
				{
					$res['activ_status'] = 1;
				}
				elseif ($res['start_time'] > TIMENOW)
				{
					$res['activ_status'] = 0;
				}
				elseif ($res['end_time'] < TIMENOW)
				{
					$res['activ_status'] = 2;
				}
			}
			else 
			{
				$res['effective_time'] = '永久有效';
				$res['activ_status'] = 1;
			}
			
		}
		
		if($this->input['need_prize'] && $id)
		{
			$sql = "SELECT id,prize,name,prize_num,prize_win FROM " . DB_PREFIX . "prize WHERE lottery_id = {$id} ORDER BY id ASC";
			$q = $this->db->query($sql);
			
			while ($r = $this->db->fetch_array($q))
			{
				if(!$r['prize'])
				{
					continue;
				}
				$prize_info[$r['id']] = $r['prize'];
				$prizes[] = $r;
			}
		}
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY w.create_time DESC,w.id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		
		$data = array();
		if($ret)
		{
			$data['win_info'] = $ret;
		}
		if($res)
		{
			$data['lottery_info'] = $res;
		}
		
		if($prize_info)
		{
			$data['prize_info'] = $prize_info;
		}
		if($prizes)
		{
			$data['prizes'] = $prizes;
		}
		if(!empty($data))
		{
			foreach($data as $k => $v)
			{
				$this->addItem_withkey($k, $v);
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
		
		//$condition .= " AND w.confirm = 1 AND w.prize_id != ''";
		$condition .= " AND w.confirm = 1";
		
		if($this->input['win_status'] == 1)
		{
			$condition .= " AND w.prize_id != ''";
		}
		elseif ($this->input['win_status'] == 2)
		{
			$condition .= " AND w.prize_id = ''";
		}
		
		if($this->input['member_id'])
		{
			$condition .= " AND w.member_id = '" . $this->input['member_id'] . "'";
		}
		
		if($this->input['tel'])
		{
			$condition .= " AND w.phone_num = '" . $this->input['tel'] . "'";
		}
		
		if($this->input['name'])
		{
			$condition .= " AND w.address LIKE '%" . $this->input['name'] . "%'";
		}
		
		if($this->input['exchange_code'])
		{
			$condition .= " AND w.exchange_code = '" . $this->input['exchange_code'] . "'";
		}
		
		if($this->input['lottery_id'])
		{
			$condition .= " AND w.lottery_id = " . intval($this->input['lottery_id']);
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND w.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND w.create_time <= '".$end_time."'";
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
					$condition .= " AND  w.create_time > '".$yesterday."' AND w.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  w.create_time > '".$today."' AND w.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  w.create_time > '".$last_threeday."' AND w.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  w.create_time > '".$last_sevenday."' AND w.create_time < '".$tomorrow."'";
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
}

$out = new win_info();
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