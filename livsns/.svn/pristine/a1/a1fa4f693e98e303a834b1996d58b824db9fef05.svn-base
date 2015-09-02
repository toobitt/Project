<?php
define('MOD_UNIQUEID','ctrip_train');
define('SCRIPT_NAME', 'ctrip');
define('ROOT_DIR', '../../');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
require_once(CUR_CONF_PATH . 'lib/buffer.class.php');
class ctrip extends coreFrm
{
	private $buffer;
    public function __construct()
	{
		parent::__construct();
		$this->buffer = new buffercore();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$postdata = $this->get_query_parameters();
		$buffer_key = md5(serialize($postdata));
		$output = $this->buffer->select($buffer_key);
		$output = $output ? json_decode($output,1) : array();
		if(!$output)
		{
			$json_data = postCurl(CLOUND_TRAIN_API, $postdata);
			$result = json_decode($json_data,1);
			if($result['ResponseStatus']['Errors'])
			{
				$output['Message'] = $result['ResponseStatus']['Errors'][0]['Message'];
				$output['RecordCount'] = 0;
			}
			$output = array();
			$response = $result['ResponseBody'];
			$output['RecordCount'] = count($response['TrainItems']);
			$output['Data'] = $response ? changeTrainData($response,$postdata['DepartDate']) : array();
			$this->buffer->replace($buffer_key, json_encode($output));
		}
		echo json_encode($output);
		exit();
	}
	
		
	protected function get_query_parameters()
	{
		$parameters = array(
			'DepartCityId' 		=> $this->input['DepartCityId'],   //出发城市id
			'DepartCityName' 	=> $this->input['DepartCityName'], //出发城市中文名
			'DepartCity' 		=> $this->input['DepartCity'],      //出发城市英文
			'ArriveCityId' 		=> $this->input['ArriveCityId'],   //到达城市id
			'ArriveCityName' 	=> $this->input['ArriveCityName'], //到达城市中文名
			'ArriveCity' 		=> $this->input['ArriveCity'],      //到达城市英文
			'DepartDate' 		=> $this->input['DepartDate'] ? date('Y-m-d',strtotime($this->input['DepartDate'])) : date('Y-m-d',TIMENOW), //发车日期
			'TrainType' 		=> $this->input['TrainType'],
			'SortMode' 			=> $this->input['SortMode'],    //排序，1为降序，2为升序
			'SortType' 			=> $this->input['SortType'],   //排序内容 1为按出发时间,2为按耗时
			'PageNumber' 		=> intval($this->input['PageNumber']),                 //分页 每25条数据加1
			'BeginTime' 		=> $this->input['BeginTime'],   //按发车时段查询     开始时间， 00:00 06:00 12:00 18:00 
			'EndTime' 			=> $this->input['EndTime'],     //按发车时段查询  对应结束时间， 06:00 12:00 18:00 23:59
			'TrainsType' 		=> $this->input['TrainsType'],  //按车次类型查询  1为只查看高铁和动车，其他为空
		);
		//出发城市
		if(!$parameters['DepartCity'])
		{
			$this->errorOutput(NO_DEPART_CITY);
		}
		
		//到达城市
		if(!$parameters['ArriveCity'])
		{
			$this->errorOutput(NO_ARRIVE_CITY);
		}
		return $parameters;
	}
	
}
include(ROOT_PATH . 'excute.php');
?>