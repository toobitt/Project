<?php
define('MOD_UNIQUEID','department');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/department_mode.php');
class department extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new department_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show_hospital()
	{
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_depart'));
		
		if($this->input['hospital_id'])
		{
			$id = intval($this->input['hospital_id']);
			$sql = "SELECT t1.*,t2.content,t3.host,t3.dir,t3.filepath,t3.filename FROM " . DB_PREFIX . "hospital t1 
					LEFT JOIN " . DB_PREFIX . "content t2
						ON t1.id = t2.cid 
					LEFT JOIN " . DB_PREFIX . "materials t3
						ON t1.indexpic_id = t3.id
					WHERE t1.id = {$id}";
			$info = $this->db->query_first($sql);
			
			$info['level'] = $this->settings['hospital_level'][$info['level']];
			
			if($info['telephone'])
			{
				$info['telephone'] = unserialize($info['telephone']);
			}
			
			if($info['logo'])
			{
				$info['logo'] = unserialize($info['logo']);
			}
		
			//判断索引图
			if($info['host'] && $info['dir'] && $info['filepath'] && $info['filename'])
			{
				$info['indexpic'] = array(
					'host'		=> $info['host'],
					'dir'		=> $info['dir'],
					'filepath'	=> $info['filepath'],
					'filename'	=> $info['filename'],
				);
			}	
			else
			{
				$info['indexpic'] = array();
			}
			unset($info['host'],$info['dir'],$info['filepath'],$info['filename']);
			$data['hospital_info'] = $info;
		}
		if(!empty($data))
		{
			$this->addItem($data);
		}
		
		$this->output();
	}

	public function show()
	{
		if(!$this->input['hospital_id'])
		{
			return false;
		}
		
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 100;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id ASC,id ASC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		
		
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "departments WHERE hospital_id = {$this->input['hospital_id']}";
		$res = $this->db->query_first($sql);
		
		$data = array();
		$data['count'] = $res['total'] ? $res['total'] : 0;
		
		$data['data'] = $ret;
		if(!empty($data))
		{
			$this->addItem($data);
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
		
		if($this->input['hospital_id'])
		{
			$condition .= " AND hospital_id = {$this->input['hospital_id']}";
		}
		
		if($this->input['fid'])
		{
			$condition .= " AND fid = {$this->input['fid']}";
		}
		else 
		{
			$condition .= " AND fid = ''";
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
	
	public function detail()
	{
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_depart'));
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

$out = new department();
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