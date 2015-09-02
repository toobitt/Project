<?php
define('MOD_UNIQUEID','ctrip_flight');
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
			$output = array();
			if($postdata)
			{
				$param = '';
				foreach ($postdata as $k=>$v)
				{
					$param .= '&'.$k.'='.$v;
				}
				$url = CLOUND_FLIGHT_API.'?'.ltrim($param,'&');
			}
			$jsondata = curlRequest($url);
		    $data = json_decode($jsondata,1);
		    if(!$data)
		    {
		    	$output['Message'] = '对不起，暂时无法查询此航班信息';
		    }
		    if($data['ResponseStatus']['Errors'])
		    {
		    	$output['Message'] = $data['ResponseStatus']['Errors'];
		    }
		    $response = $this->changeFlightData($data);
		    $output['Data'] = $response ? $response : array();
			$this->buffer->replace($buffer_key, json_encode($output));
		}
		echo json_encode($output);
		exit();
	}
		
	protected function get_query_parameters()
	{
		$parameters = array(
			'queryDate'			=> $this->input['DepartDate'] ? date('Y/m/d',strtotime($this->input['DepartDate'])) : date('Y/m/d'),
			'dPort'				=> $this->input['DepartAirCode'],
			'aPort'				=> $this->input['ArriveAirCode'],
			'flightNo'			=> $this->input['FlightNo'],
			'queryType'			=> 0,
			'ver'				=> 0,
		);
		if(!$parameters['flightNo'] && (!$parameters['dPort'] || !$parameters['aPort']))
		{
			$this->errorOutput(PARAMETERS_ERROR);
		}
		return $parameters;
	}
		
	private function changeFlightData($data)
	{
		if(!$data)
		{
			return false;
		}
		$datalist = $data['items'];
		if(!$datalist)
		{
			return false;
		}
		foreach ($datalist as $k=>$v)
		{
			$ports[] = $v['aPort'];
			$ports[] = $v['dPort'];
			$moni[] = array(
				'ActualArriveTime' 		=> $v['aaTime'] ? date('H:i',strtotime($v['aaTime'])) : '--:--' ,
				'ActualDepartTime' 		=> $v['adTime'] ? date('H:i',strtotime($v['adTime'])) : '--:--',
				'ArriveAirportCode'	 	=> $v['aPort'],
				'ArriveTerminal' 		=> $v['aTerminal'],
				'DepartAirportCode' 	=> $v['dPort'],
				'DepartTerminal' 		=> $v['dTerminal'],
				'EstimateArriveTime' 	=> $v['eaTime'] ? $v['eaTime'] : '0000',//$v['eaTime'] ,
				'EstimateDeparTime' 	=> $v['edTime'] ? $v['edTime'] : '0000',//$v['edTime'] ,
				'FlightNo' 				=> $v['flightNo'],
				'PlanArriveTime' 		=> $v['paTime'] ? date('H:i',strtotime($v['paTime'])) : '--:--',
				'PlanDepartTime' 		=> $v['pdTime'] ? date('H:i',strtotime($v['pdTime'])) : '--:--',
				'StatusRemark' 			=> $v['status'],
				'StopAirport' 			=> $v['stopPort'],
				'StopCity' 				=> $v['stopCty'],
				'AirCompanyName' 		=> $v['airlineName'],
			);
		}
		if($ports)
		{
			$ports = array_unique($ports);
			$port = "'".implode("','",$ports)."'";
			$sql = "SELECT air_port_code,air_port_name FROM ".DB_PREFIX."airports_city WHERE air_port_code in (".$port.")";
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				$airport_name[$r['air_port_code']] = $r['air_port_name'];
			}
		}
		if($moni)
		{
			foreach ($moni as $k=>$v)
			{
				$moni[$k]['ArriveAirportName'] = $airport_name[$v['ArriveAirportCode']];
				$moni[$k]['DepartAirportName'] = $airport_name[$v['DepartAirportCode']];
			}
		}
		return $moni;
	}
	
}
include(ROOT_PATH . 'excute.php');
?>