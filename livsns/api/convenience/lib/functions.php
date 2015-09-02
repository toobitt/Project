<?php

/* * *****************************解析xml成数组************************* */

function xml2Array($xml)
{
    $xmlObj = simplexml_load_string($xml);
    if (!$xmlObj)
    {
        return false;
    }
    normalizeSimpleXML($xmlObj, $result);
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

/* * *****************************解析xml成数组************************* */

function curlRequest($url)
{
    $cu  = curl_init();
    curl_setopt($cu, CURLOPT_URL, $url);
    curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
    $ret = curl_exec($cu);
    curl_close($cu);
    return $ret;
}

//curl post数据
function postCurl($url = '', $data = array())
{
    if (!$url)
    {
        return false;
    }
    $data_str = '';
    foreach ($data AS $k => $v)
    {
        if (!$v)
            continue;
        $data_str .= $k . '=' . $v . '&';
    }
    $data_str = rtrim($data_str, '&');
    $ch       = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_str);
    $response = curl_exec($ch);
    curl_close($ch); //关闭
    return $response;
}

//数据整理
function arrangeTrainData($data = array())
{
    if (!$data)
    {
        return false;
    }

    $new_data = array();
    $key_arr  = $data['Data'][0]; //提取键值
    unset($data['Data'][0]);
    foreach ($data['Data'] AS $k => $v)
    {
        foreach ($v AS $_k => $_v)
        {
            if ($key_arr[$_k] == 'SeatList')
            {
                $SeatList_key_arr = $_v[0];
                unset($_v[0]);

                $new_seatList = array();
                foreach ($_v AS $_key => $_val)
                {
                    foreach ($_val AS $_kk => $vv)
                    {
                        $new_seatList[$_key - 1][$SeatList_key_arr[$_kk]] = $vv;
                    }
                }
                $new_data[$k - 1][$key_arr[$_k]] = $new_seatList;
            }
            else
            {
                $new_data[$k - 1][$key_arr[$_k]] = $_v;
            }
        }
    }

    return $new_data;
}

//获取缓存的数据
function getTrainBufferData($key = '')
{
    $filePath = TRAIN_DATA_CACHE . $key . '.txt';
    if (!file_exists($filePath))
    {
        return false;
    }

    $ret = file_get_contents($filePath);
    return $ret;
}

//json数据key不带引号转换成带引号的并解析
function ex_json_decode($json, $mode = false)
{
    if (!$json)
    {
        return false;
    }
    $find    = array('attributes', 'm_name', 'mileage', 'tst_name', 'full_price', 'max_tickets', 'bus_code', 'sst_name', 'driving_time', 'plan_time', 'available_tickets', 'dst_name');
    $replace = array('"attributes"', '"m_name"', '"mileage"', '"tst_name"', '"full_price"', '"max_tickets"', '"bus_code"', '"sst_name"', '"driving_time"', '"plan_time"', '"available_tickets"', '"dst_name"');
    $json    = str_replace($find, $replace, $json);
    //if(preg_match('/\w:/', $json))
    //$json = preg_replace('/(\w+)\s{0,}:/is', '"$1":', $json);
    return json_decode($json, $mode);
}

function changeTrainData($data,$departDate)
{
	if(!$data)
	{
		return false;
	}
	$ret = array();
	$items = $data['TrainItems'];
	if($items && is_array($items))
	{
		$ret[0] = array('TrainId','TrainName','IsCanBook','TrainType','TrainTypeName','SeatType'
				,'SeatTypeName','Price','TicketCount','IsBeginPort','BeginPortName','DepartPortId','DepartPort'
				,'DepartDate','DepartTime','IsDirect','IsEndPort','EndPortName','ArrivePortId','ArrivePort','ArriveDate'
				,'ArriveTime','TakeDays','EndTime','StartTime','TimesCost','TotalDistance','SeatList');
		$keys = array_flip($ret[0]);
		foreach ($items as $key=>$val)
		{
			$ret[$key+1] = array(
				    $keys['TrainId']	=> $val['TrainID'],
                  $keys['TrainName']	=> $val['TrainName'],
                  $keys['IsCanBook']	=> $val['Bookable'],
                  $keys['TrainType']	=> $val['TrainTypeID'],
              $keys['TrainTypeName']	=> $val['TrainTypeName'],
                   $keys['SeatType']	=> $val['TicketResult']['TicketItems'][0]['SeatTypeID'],
               $keys['SeatTypeName']	=> $val['TicketResult']['TicketItems'][0]['SeatTypeName'],
                      $keys['Price']	=> strval($val['TicketResult']['TicketItems'][0]['Price']),
                $keys['IsBeginPort']	=> $val['TicketResult']['DepartureIsStart'],
              $keys['BeginPortName']	=> $val['StartStationName'],
               $keys['DepartPortId']	=> $val['TicketResult']['DepartureStationID'],
                 $keys['DepartPort']	=> $val['TicketResult']['DepartureStationName'],
                 $keys['DepartDate']	=> $departDate,
                 $keys['DepartTime']	=> $val['TicketResult']['DepartureTime'],
                   $keys['IsDirect']	=> $val['IsDirect'],
                  $keys['IsEndPort']	=> $val['TicketResult']['ArrivalIsEnd'],
                $keys['EndPortName']	=> $val['EndStationName'],
               $keys['ArrivePortId']	=> $val['TicketResult']['ArrivalStationID'],
                 $keys['ArrivePort']	=> $val['TicketResult']['ArrivalStationName'],
                 $keys['ArriveDate']	=> date('Y-m-d',strtotime($departDate) + $val['TicketResult']['TakeDays']*3600*24 ),
                 $keys['ArriveTime']	=> $val['TicketResult']['ArrivalTime'],
                   $keys['TakeDays']	=> $val['TicketResult']['TakeDays'],
                    $keys['EndTime']	=> $val['EndTime'],
                  $keys['StartTime']	=> $val['StartTime'],
                  $keys['TimesCost']	=> $val['UseTime'],
              $keys['TotalDistance']	=> $val['TicketResult']['Distance'],
				$keys['TicketCount']	=> 0,
 			);
 			if($val['TicketResult']['TicketItems'] && is_array($val['TicketResult']['TicketItems']))
 			{
 				$ret[$key+1][$keys['SeatList']][0] = array('IsCanBook','Price','Quantity','SeatGrade','SeatTypeId','SeatTypeName','TicketId');		
 				$listkeys = @array_flip($ret[$key+1][$keys['SeatList']][0]);
 				foreach ($val['TicketResult']['TicketItems'] as $kk=>$vv)
 				{
 					$ret[$key+1][$keys['TicketCount']] += $vv['Inventory'];
 					$ret[$key+1][$keys['SeatList']][$kk+1]	= array(
							$listkeys['IsCanBook']		=> $vv['Bookable'],
							$listkeys['Price']			=> $vv['Price'],
							$listkeys['Quantity']		=> $vv['Inventory'],
							$listkeys['SeatGrade']		=> '',
							$listkeys['SeatTypeId']		=> $vv['SeatTypeID'],
							$listkeys['SeatTypeName'] 	=> $vv['SeatTypeName'],
							$listkeys['TicketId']		=> $vv['TicketID'],
						);
 				}
 			}
		}
	}
	if(!$ret)
	{
		return false;
	}
	return $ret; 
}
?>