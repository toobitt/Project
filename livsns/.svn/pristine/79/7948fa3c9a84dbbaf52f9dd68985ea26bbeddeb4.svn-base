<?php
//水费查询类
class bus
{	
	public function query($data = array())
	{
		$return = array();
		if(!$data)
		{
			return false;
		}
		
		$busClient = new SoapClient(BUS_API);
		$key = md5(base64_encode($data['drive_date'] . $data['rst_name'] . $data['dst_name'] . BUS_CRYPT_KEY));
		$param = array(
			'drive_date' 	=> $data['drive_date'],
			'rst_name' 		=> $data['rst_name'],
			'dst_name' 		=> $data['dst_name'],
			'verify_code' 	=> $key,
		);
		
		$obj = $busClient->queryBusData($param);
		if(!$obj || !$obj->return)
		{
			return false;
		}
		
		$ret = json_decode($obj->return,1);
		if(!$ret['data'])
		{
			$return['success'] = $ret['success'];
			$return['msg'] = $ret['msg'];
			return $return;
		}
		foreach($ret['data'] as $val)
		{
			$return['data'][] = array(
			'departDate'	 => date('Y-m-d',strtotime($val['FIELDS1'])), //发车日期
			'busCode'		 => $val['FIELDS2'],  //车次
			'departTime'	 => date('H:i',strtotime($val['FIELDS3'])),  //发车时间
			'departStation'	 => $val['FIELDS4'],  //上车站名称
			'arriveStation'	 => $val['FIELDS5'],  //到达站名称
			'terminalStation'=> $val['FIELDS6'],  //终点站名称
			'takeTime'		 => $val['FIELDS7'],  //途时
			'seats'			 => $val['FIELDS8'],  //客座数
			'busLevel'		 => $val['FIELDS9'],  //车辆等级
			'remainTickets'	 => $val['FIELDS10'], //余票
			'startStation'	 => $val['FIELDS11'], //始发站名称
			'fullPrice'		 => $val['FIELDS12'], //全票价
			'halfPrice'		 => $val['FIELDS13'], //半票价
			'verifyMessage'	 => $val['FIELDS14'], //校验信息
			'mileages'		 => $val['FIELDS15'], //里程
			'arriveTime'     => date('H:i',strtotime($val['FIELDS3'])+$val['FIELDS7']*3600), //发车时间+途时
			);
		}
		return $return;
	}
	
	public function query_xzapi($data = array())
	{
		$return = array();

		if(!$data)
		{
			return false;
		}
		
		$result = postCurl(BUS_API_XZ,$data);

		$ret = ex_json_decode($result,1);
		
		if(!$ret)
		{
			return false;
		}
		
		foreach($ret as $val)
		{
			$return['data'][] = array(
			'departDate'	 => date('Y-m-d',strtotime($data['sdate'])), //发车日期
			'busCode'		 => trim($val['attributes']['bus_code']),  //车次
			'departTime'	 => date('H:i',strtotime($val['attributes']['plan_time'])),  //发车时间
			'departStation'	 => trim($val['attributes']['sst_name']['0']),  //上车站名称
			'arriveStation'	 => trim($val['attributes']['dst_name']['0']),  //到达站名称
			'terminalStation'=> trim($val['attributes']['tst_name']['0']),  //终点站名称
			'takeTime'		 => trim($val['attributes']['driving_time']),  //途时
			'seats'			 => trim($val['attributes']['max_tickets']),  //客座数
			'busLevel'		 => trim($val['attributes']['m_name']['0']),  //车辆等级
			'remainTickets'	 => trim(html_entity_decode($val['attributes']['available_tickets'])), //余票
			'startStation'	 => trim($val['attributes']['sst_name']['0']), //始发站名称
			'fullPrice'		 => trim($val['attributes']['full_price']), //全票价
			'halfPrice'		 => '', //半票价
			'verifyMessage'	 => '', //校验信息
			'mileages'		 => trim($val['attributes']['mileage']), //里程
			'arriveTime'     => date('H:i',strtotime($val['attributes']['plan_time'])+$val['attributes']['driving_time']*3600), //发车时间+途时
		    );
		}
		return $return;
	}
}
