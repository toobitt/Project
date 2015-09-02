<?php
define('MOD_UNIQUEID','template');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/template_mode.php');
class template extends adminReadBase
{
	private $template;
    public function __construct()
	{
		parent::__construct();
		$this->template = new template_mode();
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
		$ret = $this->template->show($condition,$orderby,$limit);
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
		$info = $this->template->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = '';
		
		$condition .= " AND  is_display = 1";
		
		if($this->input['sort_id'])
		{
			$condition .= " AND sort_id = ".intval($this->input['sort_id']) ;
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
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->template->detail($id);
		if(!$ret)
		{
			$this->errorOutput('获取模板失败');
		}
		$this->addItem($ret);
		$this->output();
	}
	
	//根据套系获取组件
	public function get_component()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->template->get_component($id);
		if(!$ret)
		{
			$this->errorOutput('获取模板失败');
		}
		if(is_array($this->settings['mode_type']))
		{
			$mode_type = array_flip($this->settings['mode_type']);
		}
		
		foreach ($ret as $k=>$v)
		{
			$info[] = array(
				'form_type'		=> $mode_type[$k],
				'name'			=> $this->settings['type'][$mode_type[$k]] ? $this->settings['type'][$mode_type[$k]] : $this->settings['other_mode'][$k]['name'],
				'html'			=> $v,
				'mode_type'		=> $k,
			);
		}
		$data['info'] = $info;
		$this->addItem($info);
		$this->output();
	}
	
	public function get_template()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->template->get_style($id);
		if(!$ret)
		{
			$this->errorOutput('获取模板失败');
		}
		if($this->settings['mode_type'])
		{
			foreach ($this->settings['mode_type'] as $k=>$v)
			{
				$info[] = array(
					'mode_type'	=> $v,
					'form_type'	=> $k,
					'name'		=> $this->settings['type'][$k],
				);
			}
		}
		$data['info'] = $ret;
		$data['standard'] = $info;
		$this->addItem($data);
		$this->output();
	}
}

$out = new template();
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