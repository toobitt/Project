<?php
	$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_convenience',
'charset'  => 'utf8',
'pconnect' => '',
);
define('APP_UNIQUEID','convenience');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('BUS_ON_OFF', 1);
define('SHIP_ON_OFF', 1);
define('INITED_APP', true);

/*******************************水费查询的相关配置******************************/
define('WATER_API','http://www.czcgewater.com/index.php?controller=wsfwsn&action=MobileSfcx');//查询水费信息的api
/*******************************水费查询的相关配置******************************/

define('TRAINS_CITY_API', 'http://m.ctrip.com/html5/trains/ClientData/GetTrainsCity');
define('AIRPORTS_CITY_API', 'http://m.ctrip.com/html5/flight/ClientData/GetAirPortCitys');

/*******************************火车票查询相关配置******************************/
define('TRAIN_API','http://api.cloud.hogesoft.com/convenience/ctrip_train.php');//查询火车票api
define('CLOUND_TRAIN_API','http://m.ctrip.com/restapi/soa2/10103/json/GetBookingByStation');//查询火车票api
define('CLOUND_FLIGHT_API','http://m.ctrip.com/restapi/soa2/10400/Flight/Domestic/FlightVarList/Query');//查询火车票api
define('CLOUND_AIRPORTS_CITY_API', 'http://m.ctrip.com/restapi/Data/AirportCity');

//根据车次查询火车票api
define('TRAIN_API2', 'http://t.qunar.com/QueryServlet');
define('TRAIN_DATA_CACHE',CACHE_DIR . 'train/');
define('IS_CACHE_TRAIN',TRUE);//是否缓存火车票数据
/*******************************火车票查询相关配置******************************/

/*******************************航班查询相关配置******************************/
define('AIRLINE_API','http://m.ctrip.com/html5/flight/AirState/GetAirLineList');   //查询飞机票api
define('AIRLINE_API2','http://m.ctrip.com/wap/Schedule/FlightSchedule.aspx');  
/*******************************航班票查询相关配置******************************/

/*******************************客车查询相关配置******************************/

$gGlobalConfig['get_bus_start_stations'] = array(
	'011'		=> '盐城汽车客运站',
	'002'		=> '盐城汽车北站',
	'004'		=> '盐都汽车站',
	//'001' 		=> '沭阳客运站',
	/*'002' 		=> '徐州南站',
	'003' 		=> '徐州西站',
	'021' 		=> '沛县汽车站',
	'031' 		=> '丰县汽车站',
	'056' 		=> '邳州汽车站',
	'053'		=> '邳州新城站',
	'054'		=> '邳州铁富站',
	'061'		=> '新沂北站',
	'062'		=> '新沂南站',
	'071'		=> '睢宁汽车站',
	*/
);
/*
$gGlobalConfig['get_bus_start_stations'] = array(
0=>'常州汽车总站',
1=>'武进南站',
2=>'常州花园站',
3=>'常州北站',
);
*/
define('BUS_API','');  //查询客车api
define('BUS_API_XZ','');  //查询客车api
define('BUS_CRYPT_KEY','');

define('BUS_API_YC','http://www.yc5s.com:8083/ticketYCGDService/services/ticketBuy?wsdl'); //盐城客运接口
define('BUS_CRYPT_KEY_YC','ycgd1234567890nj'); //盐城客运KEY

define('RST_TYPE',0);//始发站查询类型，0根据用户输入始发站名称查找，1为根据始发站代号查找。
/*******************************客车查询相关配置******************************/
define('MAX_SEARCH_TIME_RANGE', 1);//最大查询时间范围,空值或者为0代表只可以查询当天数据.
define('MAX_SEARCH_TIME_RANGE_SHIP', 2);//船期最大查询时间范围,空值或者为0代表只可以查询当天数据.
define('BUFFER_TIME', 300);

$gGlobalConfig['used_search_condition'] =  array (
);
?>