<?php 
require_once('./global.php');
require_once(CUR_CONF_PATH.'lib/archive_content.class.php');
define('MOD_UNIQUEID','archive_content');//模块标识
class archive_contentApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->archiveContent = new archiveContent();
		$this->archive_id = intval($this->input['archive_id']);
		$this->tableName = $this->archiveContent->get_tableName_by_id($this->archive_id);
	}
		
	public function __destruct()
	{
		parent::__destruct();
	}
		
	public function index()
	{
	
	}
	
	public function show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 10;
		$orderby = ' ORDER BY order_id  DESC';
		$condition = $this->get_condition();
		$data = $this->archiveContent->show($condition,$orderby,$offset,$count,$this->tableName, $this->archive_id);
		if (!empty($data))
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	public function count()
	{
		$ret = $this->archiveContent->count($this->get_condition(), $this->tableName, $this->archive_id);
		echo json_encode($ret);
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND tb.title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		//分类列表
		if ($this->input['archive_sort'] && intval($this->input['archive_sort'])!= -1)
		{
			$condition .= ' AND sort_id = '.$this->input['archive_sort'] ; 
		}
		
		if ($this->input['_id'])
		{
			$condition .= ' AND sort_id = '.$this->input['_id'] ; 
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= ".$end_time;
		}
		if($this->input['archive_sort_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['archive_sort_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > ".$yesterday." AND create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > ".$today." AND create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > ".$last_threeday." AND create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
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
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->archiveContent->detail( $id );
		$this->addItem($data);
		$this->output();
	}
}

$output = new archive_contentApi();
if(!method_exists($output, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$output->$action();