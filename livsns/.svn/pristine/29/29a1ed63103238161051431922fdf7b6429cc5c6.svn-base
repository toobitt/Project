<?php
/**
 * 出行类型
 */

if($_GET['m'] == 'trip_types')
{
	$trip_typs = array(
	'airport'	=> array(
			'en'=>'airport', 
			'zh'=>'航班', 
			'logo'=>array('host'=>'','dir'=>'','filepath'=>'','filename'=>'')),
	'coach'		=> array(
			'en'=>'coach', 
			'zh'=>'航班', 
			'logo'=>array('host'=>'','dir'=>'','filepath'=>'','filename'=>'')),
	'train'		=> array(
			'en'=>'train', 
			'zh'=>'列车', 
			'logo'=>array('host'=>'','dir'=>'','filepath'=>'','filename'=>'')),
	);
	exit(json_encode($trip_typs));
}
/**
 * 城市查询接口
 * @keywords 模糊查询
 */
if($_GET['m'] == 'search_city')
{
	$city = array(
	'pingying'=>'beijing',
	'cityname'=>'北京',
	'abbr'=>'BJS',
	'id'=>1,
	);
}
##########http://t.qunar.com/index.jsp####铁路###############
/**
 * 站站查询接受参数
 * @param start_station
 * @param arrive_station
 * @param date
 * @orderby time desc asc
 */

/**
 * 列车票查询输出
 */
if($_GET['m'] == 'sta2sta')
{
	$remain = array(
	'result'	=>array(
		0=>array(
		'arrive_station'=>'无锡',
		'start_station'=>'南京',
		'start_time'=>'17:05',
		'end_time'=>'20:06',
		'train_code'=>'G158',
		'train_grade'=>'高铁',
 		'price'=>array(
			'一等软座'=>'353.5',
			'二等软座'=>'267.5',
		),
		1=>array('arrive_station'=>'无锡',
		'start_station'=>'南京',
		'start_time'=>'17:05',
		'end_time'=>'20:06',
		'take_time'=>'00:30',
		'train_code'=>'G158',
		'train_grade'=>'高铁',
 		'price'=>array(
			'一等软座'=>'353.5',
			'二等软座'=>'267.5',
		)
		),
		)
	),
	'date'	=>'2013/09/10',
	'title'	=> '无锡－南京',
	'total'=>38,
	'orderby'=>'time|price'
	);
	exit(json_encode($remain));
}

/**
 * 车次查询接受参数
 * @param train_code
 * 
 */
if($_GET['m'] == 'searchbytraincode')
{
	$remain = array(
	'train_code'	=>'D20(动车组)北京',
	'direction'	=> '无锡－南京',
	'duration' => '11:20-17:53',
	'take_time'=>'6小时33分',
	'date'=>'2013-09-10',
	'price'=>array(
	'一等软座'=>'353.5',
	'二等软座'=>'267.5',
	),
	'schedule'	=>array( 
	0=>array('station_name'=>'长春', 'start_time'=>'-', 'end_time'=>'11:20'),
	1=>array('station_name'=>'公主岭南', 'start_time'=>'11:45', 'end_time'=>'11:47'), 
	2=>array('station_name'=>'长春', 'start_time'=>'11:50', 'end_time'=>'-'),                      
	),
	);
	exit(json_encode($remain));
}
#################http://m.ctrip.com/html5/Flight/Schedule/###机票查询####
/***
 * 航班动态查询
 * start_city
 * end_city
 * date
 * flight_no
 */
if($_GET['m'] == 'city2city')
{
	
	$result = array(
	'result'=>array(
	0=>array(
	 		"actual_arrive_time"=> "09:04",
            "actual_depart_time"=> "07:21",
            "arrive_airport_code"=> "CAN",
            "arrive_terminal"=> "",
            "depart_airport_code"=> "SHA",
            "depart_terminal"=> "T1",
            "estimate_arrive_time"=> "09:25",
            "estimate_depart_time"=> "07:15",
            "flight_no"=> "9C8835",
            "plan_arrive_time"=> "09:35",
            "plan_depart_time"=> "07:15",
            "status_remark"=> "到达",
            "stop_airport"=> null,
            "stop_city"=> null,
            "air_company_name"=> "春秋航空",
            "arrive_airport_name"=> "新白云机场",
            "depart_airport_name"=> "虹桥机场",
			"status"=>"到达|计划|起飞",
			"bgcolor"=>"#ccc",
			"status_text_color"=>"#fff",
		),
	1=>array(
	 		"actual_arrive_time"=> "09:04",
            "actual_depart_time"=> "07:21",
            "arrive_airport_code"=> "CAN",
            "arrive_terminal"=> "",
            "depart_airport_code"=> "SHA",
            "depart_terminal"=> "T1",
            "estimate_arrive_time"=> "09:25",
            "estimate_depart_time"=> "07:15",
            "flight_no"=> "9C8835",
            "plan_arrive_time"=> "09:35",
            "plan_depart_time"=> "07:15",
            "status_remark"=> "到达",
            "stop_airport"=> null,
            "stop_city"=> null,
            "air_company_name"=> "春秋航空",
            "arrive_airport_name"=> "新白云机场",
            "depart_airport_name"=> "虹桥机场",
			"bgcolor"=>"#ccc",
			"status_text_color"=>"#fff",
		),
		),
	'date'=>'2013-09-10',
	'total'=>58,
	'title'=>'南京－上海|航班号',
	);
	exit(json_encode($result));
}
#####客运接口输出############s
if($_GET['m'] == 'coach')
{
	$result = array(
	'result'=>array(
	0=>array(
	 		"depart_station_time"=> "09:04",
            "depart_station_name"=> "离站",
			"arrive_station_time"=> "11:04",
			"arrive_station_name"=>"到站",
			"terminal_station_name"=>"终点站",
			"start_station_name"=>"始发站",
			"duration"=>"01:30:00",
			"seats"=>50,
			"level"=>"2",
			"remain_tickets"=>30,
			"full_price"=>300,
			'half_price'=>150,
			'mileage'=>'300km',
			'coach_number'=>2,
		),
	1=>array(
	 		"depart_station_time"=> "09:04",
            "depart_station_name"=> "离站",
			"arrive_station_time"=> "11:04",
			"arrive_station_name"=>"到站",
			"terminal_station_name"=>"终点站",
			"start_station_name"=>"始发站",
			"duration"=>"01:30:00",
			"seats"=>50,
			"level"=>"2",
			"remain_tickets"=>30,
			"full_price"=>300,
			'half_price'=>150,
			'mileage'=>'300km',
			'coach_number'=>2,		
		),
	),
	'date'=>'2013-09-10',
	'total'=>58,
	'title'=>'南京－上海|航班号',
	);
	exit(json_encode($result));
}
