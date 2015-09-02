<?php
define(ROOT_DIR, '../../');
require(ROOT_DIR . 'global.php');
require_once './lib/magazine.class.php';
define('MOD_UNIQUEID','magazine');//模块标识
class MagazineApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->maga = new MagazineClass();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	function detail()
	{
		
	}
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$orderby = ' ORDER BY m.order_id  DESC';
		$res = $this->maga->show($this->get_condition(),$orderby,$offset,$count);
		if(!empty($res))
		{
			if($this->input['need_count'])
			{
							
				$totalcount = $this->return_count();
				$this->addItem_withkey('total',$totalcount['total'] );
				$this->addItem_withkey('data',$res );
			}
			else
			{
				foreach ($res as $k=>$v)
				{
					$v['contract_way'] = unserialize($v['contract_way']);
					$v['column_url'] = unserialize($v['column_url']);
					$v['column_id'] = unserialize($v['column_id']);
					$v['index_pic']['host'] = $v['host'];
					$v['index_pic']['dir'] = $v['dir'];
					$v['index_pic']['file_path'] = $v['file_path'];
					$v['index_pic']['file_name'] = $v['file_name'];
					$this->addItem($v);	
				}
			}
			$this->output();
		}		
	}
	private function get_condition()
	{
		$condition = ' AND m.state = 1';
		if($this->input['k'])
		{
			$condition .= ' AND m.title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		//分类列表
		if ($this->input['maga_sort'] && intval($this->input['maga_sort'])!= -1)
		{
			$condition .= ' AND m.sort_id = '.$this->input['maga_sort'] ; 
		}
		//杂志id
		if ($this->input['id'])
		{
			$condition .= ' AND m.id IN ('.$this->input['id'].')'; 
		}
		
		//排除id
		if ($this->input['exclude_id'])
		{
			$condition .= ' AND m.id NOT IN (' . $this->input['exclude_id'] . ')';
		}
		
		if ($this->input['maga_audit'] && $this->input['maga_audit'] != -1)
		{
			$condition .= ' AND m.state = '.$this->input['maga_audit'] ; 
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND m.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND m.create_time <= ".$end_time;
		}
		if($this->input['maga_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['maga_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  m.create_time > ".$yesterday." AND m.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  m.create_time > ".$today." AND m.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  m.create_time > ".$last_threeday." AND m. create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND m.create_time > ".$last_sevenday." AND m.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		$condition .= " AND m.issue_id > 0 AND m.state=1";
		return $condition;
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'magazine m '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	function return_count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'magazine m '.$this->get_condition();
		$res = $this->db->query_first($sql);
		return $res;
	}
	
}
$ouput= new MagazineApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>