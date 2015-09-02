<?php

/**
 * 实时公交中转
 */

function line_bus($line_id = '', $station_id = '', $direction = '1', $station_no = '20')
{
	if(!$line_id || !$station_id)
	{
		return false;
	}
	if($station_no > 20 || $station_no < 1)
	{
		$station_no = 20;
	}
	if($direction == '2')
	{
		$line_id = '1000'.$line_id;
	}
	$url = 'http://122.227.235.243:8841/bus/line_station.xml?line_id='.$line_id.'&station_id='.$station_id.'&station_no='.$station_no;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);  
	curl_setopt($ch, CURLOPT_USERPWD, 'test:test'); 
	$response  = curl_exec($ch);
	curl_close($ch);
	$result = xml2Array($response);
	$arr = array();
	if($result['line_status'] && $result['line_status']['status']) //如果有数据
	{
		if($result['line_status']['status'][0]) //有多条数据
		{
			foreach($result['line_status']['status'] as $k => $v)
			{
				if($v['station_no'] == '-1')
				{
					unset($result['line_status']['status'][$k]);
					continue;
				}
			}
			if($result['line_status']['status'])
			{
				$arr = $result['line_status']['status'];
			}
			else if($result['line']['leave_time'])
			{
				$arr['leave_time'] = $result['line']['leave_time'];
			}
			return $arr;
		}
		else //只有一条数据
		{
			if($result['line_status']['status']['station_no'] == '-1')
			{
				if($result['line']['leave_time'])
				{
					$arr['leave_time'] = $result['line']['leave_time'];
				}
			}
			else 
			{
				$arr[] = $result['line_status']['status'];
			}
			return $arr;
		}
	}
	else 
	{
		if($result['line']['leave_time'])
		{
			$arr['leave_time']  = $result['line']['leave_time'];
		}
		return $arr;
	}
}


/******************************************* 飘逸的分割线 ************************************************/

function xml2Array($xml) 
{
	normalizeSimpleXML(simplexml_load_string($xml), $result);
	return $result;
}

function normalizeSimpleXML($obj, &$result) 
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
			normalizeSimpleXML($value, $res);
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
?>