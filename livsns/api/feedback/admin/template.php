<?php
define('MOD_UNIQUEID','template');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/template_mode.php');
require_once(CUR_CONF_PATH . 'lib/feedback_mode.php');
class template extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new template_mode();
		$this->feed = new feedback_mode();
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
		$ret = $this->mode->detail($id);
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
		$id = intval($this->input['id']);//获取模板的 id
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->get_component($id);
		if(!$ret)
		{
			$this->errorOutput('获取模板失败');
		}
		if($this->settings['standard']) //标准组件
		{
			foreach ($this->settings['standard'] as $k=>$v)
			{
				$standard[] = array(
					'id'	=> $k,
					'form_type'	=> $k,
					'mode_type' => $v,
					'name' => $this->settings['form_type'][$k]['title'],
					'html'	=> $ret[$v],
				);
			}
		}
		if($this->settings['fixed']) //固定组件
		{
			foreach ($this->settings['fixed'] as $k=>$v)
			{
				$fixed[] = array(
					'id'	=> $k,
					'fixed_id'	=> $k,
					'mode_type' 	=> $v,
					'name' => $this->settings['fixed_type'][$k]['title'],
					'html'	=> $ret[$v],
				);
			}
		}
		$data['standard'] = $standard;
		$data['fixed'] = $fixed;
		$this->addItem($data);
		$this->output();
	}
	
	public function get_template()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->get_style($id);
		if(!$ret)
		{
			$this->errorOutput('获取模板失败');
		}
		if($this->settings['standard']) //标准组件
		{
			foreach ($this->settings['standard'] as $k=>$v)
			{
				$standard[] = array(
					'id'	=> $k,
					'form_type'	=> $k,
					'mode_type' => $v,
					'name' => $this->settings['form_type'][$k]['title'],
					'type'	=> 'standard',
				);
			}
		}
		if($this->settings['fixed']) //固定组件
		{
			foreach ($this->settings['fixed'] as $k=>$v)
			{
				$conf = '';
				$element = array();
				if($k == 6)
				{
					$element = 
					array(
						array( 'id'	=> 1, 'mode_type' => 'select', 'form_type' => 4, 'value' => $this->settings['hour'], 'other_sign'=> 'hour', 'selected' => 1),
						array( 'id'	=> 2, 'mode_type' => 'select', 'form_type' => 4, 'value' => $this->settings['minit'], 'other_sign'=> 'minit','selected' => 1),
						array( 'id'	=> 3, 'mode_type' => 'select', 'form_type' => 4, 'value' => $this->settings['second'], 'other_sign'=> 'second','selected' => 1),
					);
					$conf = '1,2,3';
				}
				if($k == 4)
				{
					$element = 
					array(
						array( 'id'	=> 8, 'mode_type' => 'select', 'form_type' => 4,'value' => $this->feed->show_province(), 'other_sign'=> 'province','selected' => 1),
						array( 'id'	=> 9, 'mode_type' => 'select', 'form_type' => 4, 'value' => $this->feed->show_city(PROVINCE_ID), 'other_sign'=> 'city','selected' => 1),
						array( 'id'	=> 10, 'mode_type' => 'select', 'form_type' => 4, 'value' => $this->feed->show_area(CITY_ID), 'other_sign'=> 'area','selected' => 1),
						array( 'id'	=> 11, 'mode_type' => 'input', 'form_type' => 1, 'other_sign' => 'detail','selected' => 1),
					);
					$conf = '8,9,10,11';
				}
				$fixed[] = array(
					'id'	=> $k,
					'fixed_id'	=> $k,
					'mode_type' 	=> $v,
					'name' => $this->settings['fixed_type'][$k]['title'],
					'type'	=> 'fixed',
					'element'	=> $element,
					'conf'		=> $conf,
				);
			}
		}
		$data['standard'] = $standard;
		$data['fixed'] = $fixed;
		$data['info'] = $ret;
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