<?php
define('MOD_UNIQUEID','survey_result');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/survey_mode.php');
class survey_result extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new survey_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$id = $this->input['id']; //问卷id
		if(!$id)
		{
			$this->errorOutput("没有问卷id");
		}
		$ret = $this->mode->result($id);
		$this->addItem($ret);
		$this->output();
	}
	
	public function detail()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$sql = 'SELECT title,start_time,end_time,create_time FROM '.DB_PREFIX.'survey WHERE id = '. $id ;
		$info = $this->db->query_first($sql);
		$info['start_time'] ? $info['start_time'] : $info['create_time'];
		$info['create_time'] = date('Y-m-d H:i:s',$info['create_time']);
		if(!$info)
		{
			$this->errorOutput(NODATA);
		}
		if($info['end_time'] && date('Y-m-d',$info['start_time']) == date('Y-m-d',$info['end_time']))
		{
			$unit = 'Hour';
		}else 
		{
			$unit = 'Today';
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'statistics WHERE id = '. $id ;
		$query = $this->db->query_first($sql);
		if($query && (TIMENOW - $query['update_time'] < RESULT_CACHE_TIME || $info['end_time'] < TIMENOW+RESULT_CACHE_TIME+1) )
		{
			$ret = array(
				'total'		=> intval($query['total']),
				'click'		=> intval($query['click']),
				'new_total'		=> $query['total'] - $query['last_total'],
				'new_click'		=> $query['click'] - $query['last_click'],
				'realtime'		=> $query['realtime'] ? unserialize($query['realtime']) : array(),
			);
		}
		else
		{
			$ct = $this->getTotalClick($id); //获取总点击量
			$st = $this->getTotalSubmit($id); //获取总提交量
			$r = $this->get_clicknum($id,1);//获取实时数据
			$data =array(
				'id'			=> $id,
				'total'			=> $st['total'],
				'click'			=> $ct['total'],
				'realtime'		=> $r ? serialize($r) : '',
				'update_time'	=> TIMENOW,
			);
			if(!$query || date('Y-m-d',$query['update_time']) != date('Y-m-d',TIMENOW))
			{
				$yestoday_e = strtotime(date('Y-m-d 23:59:59',TIMENOW-86400));
				$cl = $this->getTotalClick($id,$yestoday_s,$yestoday_e);//获取昨日总点击量
				$sl = $this->getTotalSubmit($id,$yestoday_s,$yestoday_e); //获取昨日总提交量
				$data['last_total'] = $sl['total'];
				$data['last_click'] = $cl['total'];
			}else if($query)
			{
				$data['last_total'] = $query['last_total'];
				$data['last_click'] = $query['last_click'];
			}
			if(!$query)
			{
				$this->mode->create('statistics',$data,false);
			}else 
			{
				$this->mode->update($id,'statistics',$data);
			}
			$ret = array(
				'total'		=> intval($data['total']),
				'click'		=> intval($data['click']),
				'new_total'		=> $data['total'] - $data['last_total'],
				'new_click'		=> $data['click'] - $data['last_click'],
				'realtime'		=> $r ? $r : array(),
			);
		}
		$ret['title'] = $info['title'];
		$ret['unit'] = $unit;
		$ret['create_time'] = $info['create_time'];
		$ret['id'] = $id;
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_click()
	{
		$type = intval($this->input['type']);
		$unit = trim($this->input['unit']);
		$start_time = $this->input['start_time'];
		$end_time = $this->input['end_time'];
		$id = $this->input['id'];
		$func = $this->input['func']; //浏览量还是提交量
		$func = $func ? $func : 'submit';
		$start_time = $start_time ? strtotime($start_time) : 0;
		$end_time = $end_time ? strtotime($end_time) : TIMENOW;
		$param = array(
			'start_time'	=> $start_time,
			'end_time'		=> $end_time,
			'id'			=> $id,
			'func'			=> $func,
			'type'			=> $type,
			'unit'			=> $unit,
		);
		$condition = md5(json_encode($param));
		//优先从缓存中获取数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'search_cache WHERE search_data = "'. $condition .'" AND cache_time > '. (TIMENOW-RESULT_CACHE_TIME);
		$query = $this->db->query_first($sql);
		
		if(!$query)
		{
			if($func == 'click')
			{
				$ret = $this->get_clicknum($id,$type,$start_time,$end_time,$condition,$unit); //实时数据
			}else{
				$ret = $this->get_submitnum($id,$type,$start_time,$end_time,$condition,$unit); //实时数据
			}
			
			$data = array(
				'id'			=> $id,
				'search_data' 	=> $condition,
				'data'			=> serialize($ret),
				'cache_time'	=> TIMENOW,
			);
			$this->mode->create('search_cache',$data,0);
		}else
		{
			$ret = $query['data'] ? unserialize($query['data']) : array();
		}
		$this->addItem($ret);
		$this->output();
	}
	
	private function get_clicknum($id,$type = '',$start_time = '',$end_time = '',$condition = '',$unit ='')
	{
		if(intval($type) === 1 || $type == 2 || $unit == 'hour') //查询实时数据
		{
			if(intval($type) === 1 ) 
			{
				$start_time = strtotime(date('Y-m-d 00:00:00',TIMENOW));
				$end_time = TIMENOW;
			}
			$sql = 'SELECT start_time FROM '.DB_PREFIX.'clicks WHERE sid = '. $id .' AND start_time >= '.$start_time .' AND start_time <= '.$end_time;
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$hour = intval(date('H',$r['start_time']));
				$ret[$hour]++;
			}
		}else //查询其他数据
		{
			$group = $unit ? ','.$unit : '';
			$sql = 'SELECT count(*) as total'.$group.' FROM '.DB_PREFIX.'clicks WHERE sid = '. $id ;
			if($start_time)
			{
				$sql .= ' AND start_time >= '.$start_time ;
			}
			if($end_time)
			{
				$sql .= ' AND start_time <= '.$end_time ;
			}
			$sql .= ' GROUP BY '.$unit;
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$ret[$r[$unit]] = intval($r['total']);
			}
		}
		return $ret ? $ret : array();
	}
	
	private function get_submitnum($id,$type = '',$start_time = '',$end_time = '',$condition = '',$unit ='')
	{
		if(intval($type) === 1 || $type == 2 || $unit == 'hour') //查询实时数据
		{
			if(intval($type) === 1 ) 
			{
				$start_time = strtotime(date('Y-m-d 00:00:00',TIMENOW));
				$end_time = TIMENOW;
			}
			$sql = 'SELECT create_time FROM '.DB_PREFIX.'record_person WHERE survey_id = '. $id .' AND create_time >= '.$start_time .' AND create_time <= '.$end_time;
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$hour = intval(date('H',$r['create_time']));
				$ret[$hour]++;
			}
		}else //查询其他数据
		{
			$group = $unit ? ','.$unit : '';
			$sql = 'SELECT count(*) as total'.$group.' FROM '.DB_PREFIX.'record_person WHERE survey_id = '. $id ;
			if($start_time)
			{
				$sql .= ' AND start_time >= '.$start_time ;
			}
			if($end_time)
			{
				$sql .= ' AND start_time <= '.$end_time ;
			}
			$sql .= 'GROUP BY '.$unit;
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$ret[$r[$unit]] = intval($r['total']);
			}
		}
		return $ret;
	}
	
	private function getTotalClick($id,$start_time = '',$end_time = '')
	{
		if(!$id)
		{
			return false;
		}
		$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'clicks WHERE sid = '. $id ;
		if($start_time)
		{
			$sql .= ' AND start_time >= '.$start_time ;
		}
		if($end_time)
		{
			$sql .= ' AND start_time <= '.$end_time ;
		}
		$q = $this->db->query_first($sql);
		$ret['total'] = intval($q['total']);
		return $ret;
	}

	private function getTotalSubmit($id,$start_time = '',$end_time = '')
	{
		if(!$id)
		{
			return false;
		}
		$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'record_person WHERE survey_id = '. $id ;
		if($start_time)
		{
			$sql .= ' AND create_time >= '.$start_time ;
		}
		if($end_time)
		{
			$sql .= ' AND create_time <= '.$end_time ;
		}
		$q = $this->db->query_first($sql);
		$ret['total'] = intval($q['total']);
		return $ret;
	}
	
	public function count()
	{}
	
}

$out = new survey_result();
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