<?php
/**
 * Created by livtemplates.
 * User: wangleyuan
 * Date: 14-5-28
 * Time: 上午11:13
 */
?>
{code}
$search_condition = $_configs['used_search_condition'];
{/code}

{template:new_search/title, key, $_INPUT['key']}

{template:new_search/creater, user_name, $_INPUT['user_name']}

{code}
    $_INPUT['status'] = $_INPUT['status'] ? $_INPUT['status'] : 0;
    if (empty($_configs['state_search'])) {
        $_configs['state_search'] = array(
        '0'   => '全部状态',
        '1'   => '未审核',
        '2'   => '已审核',
        '3'   => '已打回',
        );
    }
    $_attr = array('title' => '审核状态');
{/code}
{template:new_search/status, status, $_INPUT['status'], $_configs['state_search'], $_attr}

{code}
$_INPUT['date_search'] = $_INPUT['date_search'] ? $_INPUT['date_search'] : 1;
$_attr = array('start_time' => 'start_time', 'end_time' => 'end_time', 'title' => '创建时间');
{/code}
{template:new_search/date, date_search, $_INPUT['date_search'], $_configs['date_search'], $_attr}


{code}
$_INPUT['time_status'] = $_INPUT['time_status'] ? $_INPUT['time_status'] : '-1';
$_attr = array('title' => '话题状态');
{/code}
{template:new_search/time_status, time_status, $_INPUT['time_status'], $_configs['time_status'], $_attr}


{code}
$_INPUT['time_frame'] = $_INPUT['time_frame'] ? $_INPUT['time_frame'] : 1;
$_attr = array('start_time' => 'time_frame_start', 'end_time' => 'time_frame_end', 'title' => '话题时间');
{/code}
{template:new_search/date, time_frame, $_INPUT['time_frame'], $_configs['date_search'], $_attr}



























 