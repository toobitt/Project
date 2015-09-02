<?php
define('MOD_UNIQUEID','airline_search');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/airline.class.php');
class airline_search extends adminReadBase
{
	private $airline;
    public function __construct()
	{
		parent::__construct();
		$this->air = new airline();
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
		//出发日期
		if(!$this->input['date'])
		{
			$depart_date = date('Y-m-d',TIMENOW);
		}
		else
		{
			$depart_date = $this->input['date'];
		}
		
		//出发城市
		if(!$this->input['start_city'])
		{
			$this->errorOutput(NO_DEPART_CITY);//没有出发城市
		}
		
		//到达城市
		if(!$this->input['end_city'])
		{
			$this->errorOutput(NO_ARRIVE_CITY);//没有到达城市
		}
		
		//航班号
		if(!$this->input['flight_no'])
		{
			//$this->errorOutput(NO_QUERY_MONTH);//没有到航班号
		}
		
		$post_data = array(
		    'DepartDate'    => $depart_date,
		    'DepartAirCode' => $this->input['start_city'],
		    'ArriveAirCode' => $this->input['end_city'],
		);
		
		$data = $this->air->search_airline($post_data);
		if(!$data)
		{
			$this->errorOutput(NO_DATA);
		}
		
		$data_arr = json_decode($data,1);
		if(!$data_arr)
		{
			$this->errorOutput(DATA_ERROR);
		}
		
		$this->addItem($data_arr);
		$this->output();
	}
	
	public function condition()
	{
		
	}
}

$out = new airline_search();
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