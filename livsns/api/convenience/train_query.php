<?php
define('MOD_UNIQUEID','train');
define('SCRIPT_NAME', 'trainapi');
define('ROOT_DIR', '../../');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
require_once(CUR_CONF_PATH . 'lib/buffer.class.php');
class trainapi extends adminBase
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
			$json_data = postCurl(TRAIN_API, $postdata);
                        
			$result = json_decode($json_data,1);
                        
			if($result['Message'])
			{
				$this->errorOutput($result['Message']);
			}
			$output = array();
			$output['total'] = $result['RecordCount'];
			$output['data'] = $result['Data'] ? arrangeTrainData($result) : array();
			$this->buffer->replace($buffer_key, json_encode($output));
		}
		$this->addItem($output);
		$this->output();
	}
	protected function get_query_parameters()
	{
		$page_number = $this->input['offset']/25+1;
		$parameters = array(
			'DepartCityId' 		=> $this->input['depart_city_id'],   //出发城市id
			'DepartCityName' 	=> $this->input['depart_city_name'], //出发城市中文名
			'DepartCity' 		=> $this->input['depart_city'],      //出发城市英文
			'ArriveCityId' 		=> $this->input['arrive_city_id'],   //到达城市id
			'ArriveCityName' 	=> $this->input['arrive_city_name'], //到达城市中文名
			'ArriveCity' 		=> $this->input['arrive_city'],      //到达城市英文
			'DepartDate' 		=> $this->input['depart_date'] ? $this->input['depart_date'] : date('Y-m-d',TIMENOW), //发车日期
			'TrainType' 		=> $this->input['train_type'],
			'SortMode' 			=> $this->input['sort_mode'],    //排序，1为降序，2为升序
			'SortType' 			=> $this->input['sort_stype'],   //排序内容 1为按出发时间,2为按耗时
			'PageNumber' 		=> $page_number,                 //分页 每25条数据加1
			'BeginTime' 		=> $this->input['begin_time'],   //按发车时段查询     开始时间， 00:00 06:00 12:00 18:00 
			'EndTime' 			=> $this->input['end_time'],     //按发车时段查询  对应结束时间， 06:00 12:00 18:00 23:59
			'TrainsType' 		=> $this->input['trains_type'],  //按车次类型查询  1为只查看高铁和动车，其他为空
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
	public function select_by_train_code()
	{
		$parameters = array(
		'start' =>$this->input['offset'] ? $this->input['offset'] : 0,
		'num' =>$this->input['count'] ? $this->input['count'] : 10,
		'checi'=>$this->input['train_code'],
		'ActionName'=>'checi',
		);
		if(!$parameters['checi'])
		{
			$this->errorOutput(UNKNOWN_TRAIN_CODE);
		}
		$html = postCurl(TRAIN_API2, $parameters);
		if(!$html)
		{
			$this->errorOutput(GET_DATA_ERROR);
		}
		$match = array();
		preg_match_all('/(.*?)<div\s{0,}class="ct">(.*?)<\/div>(.*?)/is', $html, $match);
		
		//hg_pre($match);exit;
		$output = array();
		if($match)
		{
			$_tmp = array();
			
			preg_match('/\s*<p>(.*?)<b>(.*?)<\/b><br\/>(.*?)<\/p>\s*/is', $match[2][0], $_tmp);
			$output['trainName'] = trim($_tmp[2]);
			$trainDirection = trim(str_replace('方向:', '', $_tmp[3]));
			$trainDirection = explode('-',$trainDirection);
			$output['beginStation'] = $trainDirection[0];
			$output['terminalStation'] = $trainDirection[1];
			$_tmp = array();
			preg_match_all('/\s*(<p>){0,1}(.*?)<br\/>(<\/p>)?\s*/is', $match[2][1], $_tmp);
			//print_r($_tmp);exit;
			if($_tmp[2])
			{
				$startEndTime = str_replace('时间:', '', $_tmp[2][1]);
				$startEndTime = explode('-', $startEndTime);
				$output['startTime'] = $startEndTime[0];
				$output['endTime'] = $startEndTime[1];
				$output['takeTime'] = str_replace('用时:', '', $_tmp[2][2]);
				for($i=3;$_tmp[2][$i];$i++)
				{
					if($_tmp[2][$i] && $_tmp[2][$i]!='元')
					{
						$output['seatsPrice'][] = $_tmp[2][$i];
					}
				}
			}
			$_tmp = array();
			preg_match_all('/(?:.*?)<span\s*>\d{2}\.(.*?)\((.*?)\)<\/span>(?:.*?)/is', $match[2][2], $_tmp);
			if($_tmp[1])
			{
				foreach ($_tmp[1] as $key=>$val)
				{
					$arrive_depart_time = explode('--', $_tmp[2][$key]);
					$output['stopStations'][] = array('name'=>trim($val), 'arriveTime'=>$arrive_depart_time[0],'departTime'=>$arrive_depart_time[1]);
				}
			}			
		}
		$this->addItem($output);
		$this->output();
	}
	
	public function select_by_train_station()
	{
		$parameters = array(
			'start' =>$this->input['offset'] ? intval($this->input['offset']) : 0,
			//'num' =>$this->input['count'] ? intval($this->input['count']) : 10,
			'chezhan'=>trim($this->input['depart_city']),
			'fchezhan'=>$this->input['fchezhan'] ? intval($this->input['fchezhan']) : 15, //13-所有 14-终点站 15-始发站
			'ActionName'=>'chezhan',
		);
		if(!$parameters['chezhan'])
		{
			$this->errorOutput(UNKNOWN_TRAIN_STATION);
		}
		$buffer_key = md5(serialize($parameters));
		$output = $this->buffer->select($buffer_key);
		$output = $output ? json_decode($output,1) : array();
		if(!$output)
		{
			$html = postCurl(TRAIN_API2, $parameters);
			if(!$html)
			{
				$this->errorOutput(GET_DATA_ERROR);
			}
	
			$match = array();
			preg_match_all('/(.*?)<div\s{0,}class="ct">(.*?)<\/div>(.*?)/is', $html, $match);

			$output = array();
			if($match)
			{
				$_tmp = array();
				preg_match('/\s*<p>.*<b>(\d*?)<\/b>(.*?)<\/p>\s*/is', $match[2][0], $_tmp);
			    $total = $_tmp[1];
				$_tmp = array();    
				preg_match_all('/\s*<tr>{0,1}(.*?)<td\s.*?>.*?<a\s.*?>(.*?)<\/a>.*?<br\/>(.*?)<\/td>.*?<td\s.*?>(.*?)<br\/>(.*?)<\/td>.*?<td\s.*?>(.*?)<\/td>.*?<\/tr>?\s*/is', $match[2][0], $_tmp);
				if(is_array($_tmp[2]))
				{
					foreach ($_tmp[2] as $k=>$v)
					{
						$train_code = explode(' ',$v);
						$train_station = explode('-',$_tmp[3][$k]);
						$seats = explode('<br/>',$_tmp[6][$k]);
						$seat = array();
						foreach ($seats as $vs)
						{
							if(trim($vs))
							{
								preg_match('/\s*(.*?)(\d+\.?\d+)(.*)/',$vs,$matchs);
								list(,$seat_type,$price)=$matchs;
								$seat[] =array(
								'SeatTypeName'=>trim($seat_type),
								'Price'    =>$price,
								);
							}
						}
						$data[$k] = array(
						'IsCanBook' => 0,
						'TrainName' => trim($train_code[0]),
						'BeginPortName' => trim($train_station[0]),
						'DepartPort' => trim($train_station[0]),
						'DepartTime' => trim($_tmp[4][$k]),
						'EndPortName' => trim($train_station[1]),
						'StartTime' => trim($_tmp[4][$k]),
						'TrainTypeName' => trim($_tmp[5][$k]),
						'SeatList'=> $seat,
						);
					}	
				}	
				$output['total'] = $total;
				$output['data'] = $data;
				$this->buffer->replace($buffer_key, json_encode($output));
			}
		}
		$this->addItem($output);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>