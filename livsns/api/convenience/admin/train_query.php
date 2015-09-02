<?php
define('MOD_UNIQUEID','water_query');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/train_query.class.php');
class train_query_api extends adminReadBase
{
	private $train;
    public function __construct()
	{
		parent::__construct();
		$this->train = new train();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	public function count(){}
	public function detail(){}

	public function show()
	{
		//出发城市
		if(!$this->input['depart_city'])
		{
			$this->errorOutput(NO_DEPART_CITY);
		}
		
		//到达城市
		if(!$this->input['arrive_city'])
		{
			$this->errorOutput(NO_ARRIVE_CITY);
		}
		
		//出发日期
		$depart_date = $this->input['depart_date'];
		if(!$depart_date)
		{
			$depart_date = date('Y-m-d',TIMENOW);
		}
		
		$data = array(
			'DepartCityId' 		=> $this->input['depart_city_id'],
			'DepartCityName' 	=> $this->input['depart_city_name'],
			'DepartCity' 		=> $this->input['depart_city'],
			'ArriveCityId' 		=> $this->input['arrive_city_id'],
			'ArriveCityName' 	=> $this->input['arrive_city_name'],
			'ArriveCity' 		=> $this->input['arrive_city'],
			'DepartDate' 		=> $depart_date,
			'TrainType' 		=> $this->input['train_type'],
			'SortMode' 			=> $this->input['sort_mode'],
			'SortType' 			=> $this->input['sort_stype'],
			'PageNumber' 		=> $this->input['page_number'],
			'BeginTime' 		=> $this->input['begin_time'],
			'EndTime' 			=> $this->input['end_time'],
			'TrainsType' 		=> $this->input['trains_type'],
		);
		
		//先查看缓存有没有存在,不存在就去请求接口查询，存在就用缓存数据
		if(!IS_CACHE_TRAIN || !$ret = getTrainBufferData($this->input['depart_city'] . '#' . $this->input['arrive_city'] . '#' . $depart_date))
		{
			$ret = $this->train->query($data);
		}
		$ret = json_decode($ret,1);
		if(!$ret || !$ret['Data'])
		{
			$this->errorOutput(NO_DATA);
		}
		
		if(!$new_data = arrangeTrainData($ret))
		{
			$this->errorOutput(NO_DATA);
		}
		
		$this->addItem($new_data);
		$this->output();
	}
}

$out = new train_query_api();
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