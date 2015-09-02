<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-7-31
 * Time: 上午11:05
 */

/**
 * 根据开始时间、结束时间判断状态
 */
function hg_process_time_status($start_time, $end_time)
{
    if ($end_time < TIMENOW)   //已结束
    {
        $ret['time_status'] = 2;
        $ret['time_status_text'] = '已结束';
    }
    else if ($start_time > TIMENOW)   //即将开始
    {
        $ret['time_status'] = 0;
        $ret['time_status_text'] = '即将开始';
    }
    else   //进行中
    {
        $ret['time_status'] = 1;
        $ret['time_status_text'] = '进行中';
    }
    return $ret;
}

/* End of file function.php */
 