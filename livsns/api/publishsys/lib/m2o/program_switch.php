<?php

/**
 * 根据视频id取视频内容；
 * */
define('M2O_ROOT_PATH','./');
require(M2O_ROOT_PATH . 'global.php');
?>
<div id="liveDateSelector" class="date-selector">
	<ul>
		<?php
			$time = $_REQUEST['dates']?$_REQUEST['dates']:date("Y-m-d",time());
			$time_code =  strtotime($time . ' 00:00:00');
			$channel_id = $_REQUEST['channel_id'];
			$play_time = $_REQUEST['play_time'] ? $_REQUEST['play_time'] : 0;
			
			$cache_file = M2O_ROOT_PATH . '../cache/channel_' . md5($time . '_' . $channel_id . '_' . @date('Y-m-d', $_REQUEST['play_time'])) . '.json';
			if (!is_dir(M2O_ROOT_PATH . '../cache/'))
			{
				mkdir(M2O_ROOT_PATH . '../cache/');
			}
			if (is_file($cache_file))
			{
				$filemtime = filemtime($cache_file);
				if (($filemtime + 120) < time())
				{
					$recache = true;
				}
				else
				{
					$content = @file_get_contents($cache_file);
					if (!$content)
					{
						$recache = true;
					}
					else
					{
						$recache = false;
					}
					$cacheInfo = json_decode($content, 1); 
					$channel = $cacheInfo['channel'];
				}
			}
			else
			{
				$recache = true;
			}
			if ($recache)
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
				$cacheInfo['channel'] = $channel;
			}
					$channel['save_time'] = $channel['time_shift'];
					$channel['server_time'] = $channel['server_time'] ? $channel['server_time'] : $_REQUEST['time'] / 1000;
			$num_m_zh = array('','一','二','三','四','五','六','七','八','九','十','十一','十二');
			$num_w_zh = array('日','一','二','三','四','五','六');
			$current_class = ' class="disabled"';
			if ($channel['save_time']) {
				$check_time_1 =  strtotime(date("Y-m-d",$time_code) . ' 00:00:00');
				$check_time_2 =  strtotime(date("Y-m-d",$channel['server_time']) . ' 00:00:00');
				$save_day = isset($_REQUEST['shownums']) ? $_REQUEST['shownums'] : intval($channel['save_time'] / 24)-1;
				$current_class = '';
				for ($i = $save_day; $i>0; $i--) {
					$current_time_code = intval($channel['server_time']) - 86400 * ($i-1);
					$current_time = date("Y-m-d",$current_time_code);
					$current_month = $num_m_zh[intval(date("m",$current_time_code))] . '月';
					$current_day = date("d",$current_time_code);
					$current_weekday = '周' . $num_w_zh[intval(date("w",$current_time_code))];
					$current_class = $time==$current_time?' class="current"':'';
					echo '<li rel="'. $current_time .'"' . $current_class . '><a href="javascript:;" onclick="get_list(' . $channel['id'] . ',\'' . $current_time . '\',0);"><span class="month">'.$current_month.'</span><span class="day">'.$current_day.'</span><span class="weekday">'.$current_weekday.'</span></a></li>';
				}
				
			}
		?>
	</ul>
</div>
<div class="bar">
    <span class="sub-link"><a href="javascript:void(0);" onclick="get_object_list(<?php echo $channel['id']; ?>,-1 ,1);" class="golive">返回直播</a></span>
    <span id="liveChannelName" class="title"><?php echo $channel['name']?></span>
</div>
<?php
	$time = date('Y-m-d',$time_code);
	if ($recache)
	{
		$curls = new curl($gGlobalConfig['App_program']['host'], $gGlobalConfig['App_program']['dir']);
		$curls->setSubmitType('post');		
		$curls->setReturnFormat('json');
		$curls->initPostData();
		$curls->addRequestData('channel_id', $channel_id);
		$curls->addRequestData('dates', $time);
		$ret = $curls->request('program.php');
		$cacheInfo['program'] = $ret;
		//file_put_contents($cache_file, json_encode($cacheInfo));
	}
	else
	{
		$ret = $cacheInfo['program'];
	}

	$lave_time = "";
	if (is_array($ret)) {
?>
<div id="liveSchedule" class="schedule">
    <ul>
        <?php
            foreach ($ret as $key => $value) {
                //$value['start_time'] = strtotime(date('Y-m-d') . ' ' . date('H:i:s', $value['start_time']));
                $value['theme'] = $value['subtopic'] ? $value['theme'] . ":" . $value['subtopic'] : $value['theme'] ;
                $style = '';
				$onclick = ' onclick="get_object_list(' . $value['channel_id'] . ',' . $value['start_time'] . ',1, \'' . $value['m3u8'] . '\');" ';
                $zhi = '';
                if ($value['now_play']) {
                    $style = ' class="current"';
                //  $zhi = '<span class="tip">看直播</span>';
                    echo '<input type="hidden" id="now_ch_name" value="'.$value['channel_name'].'" /><input type="hidden" id="now_pr_name" value="'.$value['theme'].'" />';
                }
                if (!$value['display']) {
                    $style = ' class="future"';
                    $onclick = '';
                }
                if ($value['now_play']) {
                    $style = ' class="current"';
                    $lave_time = $value['lave_time'];
                    $zhi = '';
					$onclick = ' onclick="get_object_list(' . $value['channel_id'] . ', -1 ,1, \'' . $value['m3u8'] . '\');" ';
                }
                if ($value['zhi_play'] && $value['now_play'] && $play_time) {
                    $style = ' class="onair"';
                    $lave_time = $value['lave_time'];
                    $zhi = '<span class="tip">当前直播</span>';
                }
                if ( ($value['zhi_play'] && ($play_time == -1) ) ||  ($value['zhi_play'] && $value['now_play'] && !$play_time) || (($value['zhi_play'] && $value['now_play'] && ($play_time == $value['start_time']) ))) {
                    $style = ' class="onair current"';
                    $lave_time = $value['lave_time'];
                    $zhi = '<span class="tip">当前直播</span>';
                }

                if($play_time == $value['start_time']){
                    $style = ' class="current"';
                    $lave_time = $value['lave_time'];
                }
                if($play_time == $value['start_time'] && $value['now_play'] ){
                    $style = ' class="onair current"';
                    $lave_time = $value['lave_time'];
                }
                echo '<li' . $style. $onclick . '>' . $zhi . '<span class="time">' . $value['stime'] . '</span>' . $value['theme'] . '</li>';
            }
        ?>
    </ul>
    <input type="hidden" id="time_check" value="<?php echo $lave_time*1000;?>" />
    <input type="hidden" id="dates" value="<?php echo $time;?>" />
    <input type="hidden" id="channel_id" value="<?php echo $channel_id;?>" />
    <input type="hidden" id="now_time" value="<?php echo $channel['server_time'];?>" />
</div>
<?php
	}
?>