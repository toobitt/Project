<?php
define('MOD_UNIQUEID','article');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/article_mode.php');
class article extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new article_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		//没有管理期刊的权限就不能编辑新闻
		$this->verify_content_prms(array('_action'=>'manage_period'));
		/**************节点权限*************/
		if(!$this->input['page_id'])
		{
			$this->errorOutput('没有page_id');
		}
		$sql = 'SELECT epaper_id FROM ' . DB_PREFIX . 'article WHERE page_id = ' .$this->input['page_id'];
		$q = $this->db->query_first($sql);
		$epaper_id = $q['epaper_id'];
		$prms_epaper_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $prms_epaper_ids && implode(',', $prms_epaper_ids)!=-1 && !in_array($epaper_id,$prms_epaper_ids))
		{
			$this->errorOutput('没有权限');
		}
		/*********************************/

		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id ASC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
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
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = '';
		/*************权限判断***************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//不允许查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND org_id IN (' . $this->user['slave_org'] . ')';
			}
		}
		/**********************************/
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		if($this->input['page_id'])
		{
			$condition .= ' AND page_id = '.intval($this->input['page_id']);
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
		
		//权重
		if($this->input['start_weight'] && $this->input['start_weight'] != -1)
		{
			$condition .= " AND weight >=" . $this->input['start_weight'];
		}
		
		if($this->input['end_weight'] && $this->input['end_weight'] != -2)
		{
			$condition .= " AND weight <= " . $this->input['end_weight'];
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
		$this->verify_content_prms(array('_action'=>'manage_period'));
		if($this->input['id'])
		{
			$info = $this->mode->detail($this->input['id']);
			if($info)
			{
				$info['content'] = htmlspecialchars_decode($info['content']);
				if ($this->input['need_process'])
				{
					$info['content'] = strip_tags($info['content'], '<p><br><a><img><div>');
					$info['content'] = preg_replace('#<p[^>]*>#i','<p>',$info['content']);
				}
				$this->addItem($info);
				$this->output();
			}
		}
	}
	
	
	public function get_stack()
	{
		//权限
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms(array('_action'=>'manage_period')); //管理往期的权限
		}
		
		
		$period_id = intval($this->input['period_id']);
		if(!$period_id)
		{
			$this->errorOutput('没有期id');
		}
		/*$sql = 'SELECT stack_id FROM '.DB_PREFIX.'page WHERE period_id = '.$period_id;
		$q = $this->db->query($sql);
		
		while($r = $this->db->fetch_array($q))
		{
			$stack[$r['stack_id']] = $this->settings['stack_set'][$r['stack_id']];
		}
		if($stack)
		{
			ksort($stack);
		}*/
		
		$sql = "SELECT id,zm,name FROM ".DB_PREFIX."stack WHERE period_id = ".$period_id." ORDER BY order_id ASC";
			
		$q = $this->db->query($sql);
		
		while ($r = $this->db->fetch_array($q))
		{
			$stack[$r['id']] = array(
				'name'		=> $r['name'],
				'zm' 		=> $r['zm'],
			);
		}
		
		$this->addItem($stack);
		$this->output();
	}
	
	
	public function get_page()
	{
		$stack_id = intval($this->input['stack_id']);
		if(!$stack_id)
		{
			$this->errorOutput('没有叠id');
		}
		
		$period_id = intval($this->input['period_id']);
		if(!$period_id)
		{
			$this->errorOutput('没有期id');
		}
		
		$type = $this->input['type'];
		
		//编辑链接
		if($type == 'edit_link')
		{
			$sql = "SELECT p.id,p.page as page_num,p.pdf_id,p.hot_area,m.host,m.dir,m.filepath,m.filename FROM ".DB_PREFIX."page p
					LEFT JOIN ".DB_PREFIX."material m 
						ON p.jpg_id = m.id 
					WHERE p.period_id = ".$period_id." AND p.stack_id = ".$stack_id;
		}
		else 
		{
			$sql = 'SELECT id,page as page_num FROM '.DB_PREFIX.'page WHERE stack_id = '.$stack_id.' AND period_id='.$period_id;
		}
		
		$sql .= ' ORDER BY order_id ASC';
		
		$q = $this->db->query($sql);
		
		while($r = $this->db->fetch_array($q))
		{
			if($r['hot_area'])
			{
				$r['hot_area'] = unserialize($r['hot_area']);
			}
			$this->addItem($r);
		}
		
		$this->output();
	}
}

$out = new article();
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