<?php
class buffercore extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function select($key)
	{
		$sql = 'SELECT `value`,create_time FROM ' . DB_PREFIX . 'buffer_data WHERE `key` = "' . $key . '"';
		$buffer_data = $this->db->query_first($sql);
		if(!$buffer_data)
		{
			return false;
		}
		if(defined('TRAIN_BUFFER_TIME')) 
		{
			$buffer_time = TRAIN_BUFFER_TIME;
		}
		if (defined('BUS_BUFFER_TIME'))
		{
			$buffer_time = BUS_BUFFER_TIME;
		}
	    if (defined('AIR_BUFFER_TIME'))
		{
			$buffer_time = AIR_BUFFER_TIME;
		}
		$buffer_time = isset($buffer_time) ? $buffer_time : BUFFER_TIME;
		if($buffer_data['create_time'] + $buffer_time < TIMENOW)
		{
			return false;
		}
		unset($buffer_data['create_time']);
		return $buffer_data['value'];
	}
	
	public function query($data = array())
	{
		$return = array();
		if(!$data)
		{
			return false;
		}
		
		$busClient = new SoapClient(BUS_API_YC);
		$param = array(
			'fields1' 	=> $data['date'],
			'fields2' 	=> '',
			'fields3' 	=> $data['sst'],
			'fields4' 	=> $data['tst'],
		);
		$key = md5($param['fields1'] . $param['fields2'] . $param['fields3']. $param['fields4'] . BUS_CRYPT_KEY_YC);
		$param['fields5'] = $key;
		$obj = $busClient->queryBusData($param);
		if(!$obj || !$obj->return)
		{
			return false;
		}
		
		$ret = json_decode($obj->return,1);
		if(!$ret['msg'])
		{
			$return['success'] = $ret['success'];
			$return['msg'] = $ret['msg'];
			return $return;
		}
		foreach($ret['msg'] as $val)
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
				'startStation'	 => $val['FIELDS17'], //始发站名称
				'fullPrice'		 => $val['FIELDS14'], //全票价
				'halfPrice'		 => $val['FIELDS14']/2, //半票价
				'verifyMessage'	 => '', //校验信息
				'mileages'		 => $val['FIELDS16'], //里程
				'arriveTime'     => date('H:i',strtotime($val['FIELDS3'])+$val['FIELDS7']*3600), //发车时间+途时
				'terminalStationCode'=> $val['FIELDS11'],  //终点站代码
				'childsPrice'	 => $val['FIELDS15'],  //童票价
				'takePersonName' => $val['FIELDS18'],  //承运人名称
				'childsNumber'	 => $val['FIELDS19'],  //免票儿童数
			);
		}
		return $return;
	}
	
	public function get_bus($data,$type = 0)
	{

		$sql = 'SELECT * FROM ' . DB_PREFIX . 'bus_query WHERE `departDate` = "' . strtotime($data['date']). '" AND departStation ="'.$data['sst'].'" AND concat(arriveStation, terminalStation) like "%'.$data['tst'].'%"'.' AND type = '.(int)$type;
		$sql .=' ORDER BY departDate,departTime ASC ';
		$busdata = $this->db->fetch_all($sql);
		if(!$busdata)
		{
			return false;
		}
		foreach ($busdata as $key =>$val)
		{
			$busdata[$key]['departDate']=date('Y-m-d',$busdata[$key]['departDate']);
			$busdata[$key]['departTime']=date('H:i',$busdata[$key]['departTime']);
			$busdata[$key]['arriveTime']=date('H:i',$busdata[$key]['arriveTime']);
			unset($busdata[$key]['create_time']);
			unset($busdata[$key]['order_id']);
			unset($busdata[$key]['id']);
		}
		
		return $busdata;
	}
	public function delete($key)
	{
		$sql = 'DELETE FROM ' . DB_PREFIX . 'buffer_data WHERE `key`="'.$key.'"';
		$this->db->query($sql);
		return true;
	}
	public function replace($key, $val)
	{
		$sql = 'REPLACE INTO ' .DB_PREFIX . 'buffer_data SET `key`="'.$key.'", `value`="'.addslashes($val).'", create_time='.TIMENOW;
		$this->db->query($sql);
		return true;
	}
}