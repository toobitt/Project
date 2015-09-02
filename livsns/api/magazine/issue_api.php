<?php
define(ROOT_DIR, '../../');
require(ROOT_DIR . 'global.php');
require_once './lib/issue.class.php';
define('MOD_UNIQUEID','issue');//模块标识
class IssueApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->issue = new IssueClass();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		if(!$this->input['maga_id'])
		{
			$this->addItem(array());
			$this->output();
		}
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$orderby = ' ORDER BY i.total_issue DESC';
		
		$res = $this->issue->show($this->get_condition(),$orderby,$offset,$count);
		if($res)
		{
			if($this->input['need_count'])
			{
							
				$totalcount = $this->return_count();
				$this->addItem_withkey('total',$totalcount['total'] );
				$this->addItem_withkey('data',$res );
			}
			else
			{			
				foreach ($res as $k=>$v  )
				{
					$this->addItem($v);
				}
			}
		}
		$this->output();
	}
	private function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND i.issue LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if ($this->input['maga_id'] && intval($this->input['maga_id'])!= -1)
		{
			$condition .= ' AND i.magazine_id = '.intval($this->input['maga_id']);
		}
		//分类列表
		if ($this->input['contribute_sort'] && intval($this->input['contribute_sort'])!= -1)
		{
			$condition .= ' AND sort_id = '.$this->input['contribute_sort'] ; 
		}
		
		if ($this->input['issue_audit'] && $this->input['issue_audit'] != -1)
		{
			
			$condition .= ' AND i.state = '.$this->input['issue_audit'] ; 
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND i.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND i.create_time <= ".$end_time;
		}
		if($this->input['issue_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['issue_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  i.create_time > ".$yesterday." AND i.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  i.create_time > ".$today." AND i.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  i.create_time > ".$last_threeday." AND i. create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND i.create_time > ".$last_sevenday." AND i.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		$condition .= " AND i.state = 1 ";
		return $condition;
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'issue i WHERE 1 '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	public function return_count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'issue i WHERE 1 '.$this->get_condition();
		$res = $this->db->query_first($sql);
		return $res;
	}
	function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('没有id');
		}
		$res = $this->issue->detail($id);
		$this->addItem($res);
		$this->output();		
	}
	
	public function next_pre_issue()
	{
		
		$issue_id = intval($this->input['id']);
		if(!$issue_id)
		{
			return false;
		}
		$sql = 'SELECT magazine_id FROM '.DB_PREFIX.'issue WHERE id='.$issue_id;
		$res = $this->db->query_first($sql);
		$magazine_id = $res['magazine_id'];
		if($magazine_id)
		{
			
			$sql = 'SELECT id FROM '.DB_PREFIX.'issue WHERE magazine_id = '.$magazine_id.' ORDER BY id DESC';
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				$ids[] = $r['id'];
			}
			$k = array_search($issue_id,$ids);
			$data['pre_id'] = $ids[$k-1];//上一期id
			if(!$data['pre_id'])
			{
				$data['pre_id'] = 0;
			}
			$data['next_id'] = $ids[$k+1];//下一期id
	
			if(!$data['next_id'])
			{
				$data['next_id'] = -1;
			}
			$data['maga_id'] = $magazine_id;
			$this->addItem($data);
		}
		
		$this->output();

	}
	//查询文章信息
	function form_article()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('没有文章id');
		}
		$data = $this->issue->form_article($id);
		$sql = 'SELECT id FROM '.DB_PREFIX.'article ORDER BY order_id DESC';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$ids[] = $r['id'];
		}
		$k = array_search($id,$ids);
		$data['pre_id'] = $ids[$k-1];//上一篇文章id
		$data['next_id'] = $ids[$k+1];//下篇文章id
		$this->addItem($data);
		$this->output();
	}
	//查询期刊下的文章
	function get_article()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('没有期刊id');
		}
		$info = $this->issue->get_article($id);
		$this->addItem($info);
		$this->output();
	}
	
}
$ouput= new IssueApi();
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