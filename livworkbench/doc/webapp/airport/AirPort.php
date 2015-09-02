<?php
/**
 * 徐州观音机场
 */
class xzAirport
{
	public function __construct()
	{
		$this->can = array(
			'fDate'  => date("Y-m-d"),	//trim($_REQUEST['fDate']),	//航班日期
			'fType'  => trim($_REQUEST['fType']) ? trim($_REQUEST['fType']) : '出港',	//传入类型(进港,出港)
		);
		$this->C = new SoapClient('http://58.218.240.21/Web/WebServiceFlightDyn.asmx?WSDL');
		//$this->map = array('GE3687','CI550',);
		//出港 (个别航班号重复,以星期号为下标)
		$this->map_chu = array(
			'GE3687' => array(
				1 => '19:30',
				4 => '18:00',
			),
			'CI550' => array(
				1 => '10:35',
				3 => '10:10',
				6 => '10:10',
			),
			//'CI550' => '11',
		
			'GE317' => '18:50',
			//'CZ6962' => '11',
			'CZ6962' => '15:00',
			'3U8814' => '21:50',
			'3U8813' => '17:35',
			'CZ3635' => '12:05',
			'CZ3257' => '20:05',
			'HU7066' => '12:30',
			'HU7318' => '20:30',
			'MF8152' => '21:55',
			//'HU7066' => '11',
			'HU7038' => '15:45',
			//'HU7038' => '11',
			'JD5370' => '12:35',
			'HU7732' => '19:00',
			//'JD5370' => '11',
			'3U8503' => '12:30',
			'8L9838' => '13:40',
			//'8L9838' => '11',
			'8L9864' => '12:25',
			'3U8854' => '11:25',
			//'8L9864' => '11',
			'3U8808' => '17:30',
			//'HU7732' => '11',
			'MF8151' => '17:40',
			'3U8807' => '11:50',
			'3U8504' => '18:45',
			'3U8853' => '18:40',
		);
		//进港
		$this->map_jin = array(
			'GE3688' => array(
				1 => '18:40',
				4 => '17:10',
			),
			'CI549' => '09:30',
			'CI5549' => '09:10',
			'GE318' => '17:50',
			'CZ6961' => '14:05',
			//'CZ6961' => '14:05',
			'3U8813' => '16:45',
			'3U8814' => '21:00',
			'CZ3635' => '11:00',
			'CZ3257' => '19:05',
			'HU7065' => '11:45',
			'HU7317' => '19:35',
			'MF8151' => '16:55',
			//'HU7065' => '11:45',
			'HU7037' => '15:00',
			//'HU7037' => '15:00',
			'JD5369' => '11:45',
			'HU7731' => '18:00',
			//'JD5369' => '11:45',
			'3U8504' => '17:55',
			'8L9837' => '12:10',
			//'8L9837' => '12:10',
			'8L9863' => '11:45',
			'3U8853' => '17:50',
			//'8L9863' => '11:45',
			'3U8807' => '11:00',
			//'HU7731' => '18:00',
			'MF8152' => '21:10',
			'3U8808' => '16:30',
			'3U8854' => '10:40',
			'3U8503' => '11:40',
		);
	}
	
	
	public function GetFlightDynForZSXZ()
	{
		try
		{
			$obj = $this->C->GetFlightDynForZSXZ($this->can);
			$re = $this->objectToArray($obj);
			$tmp = $re['GetFlightDynForZSXZResult']['Flight'];
			$map = $this->can['fType'] == '出港' ? $this->map_chu : $this->map_jin;
			//$week = date('w');
			foreach((array)$tmp AS $key => $val)
			{
				//计划时间处理
				$val['P_time'] = !is_array($map[$val['Flightnum']]) ? $map[$val['Flightnum']] : $map[$val['Flightnum'][date('w')]];
				$out[] = $val;
			}
			echo json_encode($out);exit;
		}catch(SoapFault $e){
			echo $e->getMessage();
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	/******************************************* 飘逸的分割线 ****************************************************************/
	
	public function xml2Array($xml)
	{
		//$this->normalizeSimpleXML(simplexml_load_string(mb_convert_encoding($xml,'UTF-8')), $result);
		$this->normalizeSimpleXML(simplexml_load_string(str_replace('encoding="GBK"','encoding="UTF-8"',$xml)), $result);
		return $result;
	}
	public function objectToArray($e)
	{
		$e=(array)$e;
		foreach($e as $k=>$v)
		{
			if( gettype($v)=='resource' ) return;
			if( gettype($v)=='object' || gettype($v)=='array' )
			$e[$k]=(array)$this->objectToArray($v);
		}
		return $e;
	}
	public function normalizeSimpleXML($obj, &$result)
	{
		$data = $obj;
		if (is_object($data))
		{
			$data = get_object_vars($data);
		}
		if (is_array($data))
		{
			foreach ($data as $key => $value)
			{
				$res = null;
				$this->normalizeSimpleXML($value, $res);
				if (($key == '@attributes') && ($key))
				{
					$result = $res;
				}
				else
				{
					$result[$key] = $res;
				}
			}
		}
		else
		{
			$result = $data;
		}
	}
	public function show()
	{
		$fun = $_REQUEST['f'];
		if(!$fun)
		{
			$fun = 'GetFlightDynForZSXZ';
		}
		if(!method_exists('xzAirport',$fun))
		{
			echo 'FUNTION NO EXISTS';exit;
		}
		$this->$fun();
	}


}

$out = new xzAirport();
$out->show();