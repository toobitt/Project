<?php
	define('M2O_ROOT_PATH','./');
	require(M2O_ROOT_PATH . 'global.php');
	
	$time = $_REQUEST['dates']?$_REQUEST['dates']:date("Y-m-d",time());
	$time_code =  strtotime($time . ' 00:00:00');
	$channel_id = $_REQUEST['channel_id'];
	//$play_time = $_REQUEST['play_time'] ? $_REQUEST['play_time'] : 0;
	$type = intval($_REQUEST['type']) ? 1 : 0;
	if($type)
	{
		$gApiConfig = $gGlobalConfig['App_live'];
		$curl = new curl($gApiConfig['host'], $gApiConfig['dir']);
		$curl->setSubmitType('post');		
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('channel_id', $channel_id);
		$curl->addRequestData('time_show', $time_code);
		$curl->addRequestData('a', 'detail');
		$channel = $curl->request('channel.php');
		$channel = $channel[0];
		$channel['save_time'] = $channel['time_shift'];
		$channel['server_time'] = $channel['server_time'] ? $channel['server_time'] : $_REQUEST['time'] / 1000;
		$num_m_zh = array('','一','二','三','四','五','六','七','八','九','十','十一','十二');
		$num_w_zh = array('日','一','二','三','四','五','六');
		$dateData = array();
		if ($channel['save_time']) {
			$check_time_1 =  strtotime(date("Y-m-d",$time_code) . ' 00:00:00');
			$check_time_2 =  strtotime(date("Y-m-d",$channel['server_time']) . ' 00:00:00');
			$save_day = intval($channel['save_time'] / 24);
			$current_class = '';
			for ($i = $save_day; $i>0; $i--) {
				$current_time_code = intval($channel['server_time']) - 86400 * ($i-1);
				$current_time = date("Y-m-d",$current_time_code);
				$current_month = $num_m_zh[intval(date("m",$current_time_code))] . '月';
				$current_day = date("d",$current_time_code);
				$current_weekday = '周' . $num_w_zh[intval(date("w",$current_time_code))];
				$dateData[] = array(
					'month' => $current_month,
					'day' => date("d",$current_time_code),
					"weekday" => $current_weekday,
					"date" => $current_time
				);
			}				
		}
		echo json_encode($dateData);exit;
	}
	else
	{
		$time = date('Y-m-d',$time_code);
		$curls = new curl($gGlobalConfig['App_program']['host'], $gGlobalConfig['App_program']['dir']);
		$curls->setSubmitType('post');		
		$curls->setReturnFormat('json');
		$curls->initPostData();
		$curls->addRequestData('channel_id', $channel_id);
		$curls->addRequestData('dates', $time);
		$ret = $curls->request('program.php');
		
		$programData = array();
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$programData[] = array(
					"time" => $v['stime'],
					"name" => $v['theme'],
					"start_time" => $v['start_time'],
					"detail" => array(
						'option' => $v['display'] ? true : false,
						'live' => $v['zhi_play'] ? true : false,
						)
					);
			}
		}
		echo json_encode($programData);exit;
	}
	
?>