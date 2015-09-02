<?php
$data = array(
	'75110' => array(
		routeid => "7511",
		segmentid => "75110",
		segmentname => "751路上行",
		segmentname2 => "751路上行",
		station => array(
			0 => array(
				routeid => "7511",
				segmentid => "75110",
				stationid => "7086",
				stationseq => "1",
				stationname => "火车站",
				longitude => "120.301000",
				latitude => "31.587500",
				blongitude => "120.312217",
				blatitude => "31.591268",
				station_flag => 0,
				starttime => null,
				station_f => 0,
				start_station => "火车站",
				end_station => "鸿山后宅客运站"
			)
		),
	),
	
);

echo json_encode($data);
?>