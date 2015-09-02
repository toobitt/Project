<?php
$get_segment = array(
	0 => array(
		removing => "336米",
		stationid => "55110627083902973018",
		stationname => "青祁桥",
		longitude => "120.265172",
		latitude => "31.559889",
		blongitude => "120.276251",
		blatitude => "31.563958",
		segment => array(
			0 => array(
				routeid => "27",
				segmentid => "30181404",
				segmentname => "27下环环行",
				stationseq => "2",
				segmentname2 => "27下环环行",
				starttime => "河埒口5:40-22:00"
			),
			1 => array(
				routeid => "82",
				segmentid => "33452823",
				segmentname => "82路环行",
				stationseq => "30",
				segmentname2 => "82路环行",
				starttime => "三国城6:15-18:30|火车站6:30-19:20"
			),
			2 => array(
				routeid => "95",
				segmentid => "33493739",
				segmentname => "95路环行",
				stationseq => "29",
				segmentname2 => "95路环行",
				starttime => "大学城停车场6:00-19:30|华晶新村6:27-20:12"
			)
		)
	),
	1 => array(
		removing => "344米",
		stationid => "55110627085911950054",
		stationname => "青祁桥",
		longitude => "120.265610",
		latitude => "31.560073",
		blongitude => "120.276692",
		blatitude => "31.564134",
		segment => array(
			0 => array(
				routeid => "27",
				segmentid => "30152403",
				segmentname => "27上环环行",
				stationseq => "30",
				segmentname2 => "27上环环行",
				starttime => "河埒口5:40-22:00"
			),
			1 => array(
				routeid => "59",
				segmentid => "30133706",
				segmentname => "59环行",
				stationseq => "60",
				segmentname2 => "59环行",
				starttime => "河埒口5:40-20:00|华庄街道6:20-20:40"
			)
		)
	),
	2 => array(
		removing => "457米",
		stationid => "55110627083939914018",
		stationname => "滨湖区老年综合服务中心",
		longitude => "120.265100",
		latitude => "31.553050",
		blongitude => "120.276172",
		blatitude => "31.557118",
		segment => array(
			0 => array(
				routeid => "27",
				segmentid => "30181404",
				segmentname => "27下环环行",
				stationseq => "3",
				segmentname2 => "27下环环行",
				starttime => "河埒口5:40-22:00"
			),
			1 => array(
				routeid => "59",
				segmentid => "30133706",
				segmentname => "59环行",
				stationseq => "8",
				segmentname2 => "59环行",
				starttime => "河埒口5:40-20:00|华庄街道6:20-20:40"
			)
		)
	),
	3 => array(
		removing => "457米",
		stationid => "55110627083939914018",
		stationname => "交运大厦",
		longitude => "120.265100",
		latitude => "31.553050",
		blongitude => "120.276172",
		blatitude => "31.557118",
		segment => array(
			0 => array(
				routeid => "27",
				segmentid => "30181404",
				segmentname => "27下环环行",
				stationseq => "3",
				segmentname2 => "27下环环行",
				starttime => "河埒口5:40-22:00"
			),
			1 => array(
				routeid => "59",
				segmentid => "30133706",
				segmentname => "59环行",
				stationseq => "8",
				segmentname2 => "59环行",
				starttime => "河埒口5:40-20:00|华庄街道6:20-20:40"
			)
		)
	),
	
);

echo json_encode($get_segment);


?>