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
    $_INPUT['status'] = $_INPUT['status'] ? $_INPUT['status'] : -1;
    if (empty($_configs['state_search'])) {
        $_configs['state_search'] = array(
        '-1'   => '全部状态',
        '0'   => '未审核',
        '1'   => '已审核',
        '2'   => '已打回',
        );
    }
{/code}
{template:new_search/status, status, $_INPUT['status'], $_configs['state_search']}

{code}
$_INPUT['date_search'] = $_INPUT['date_search'] ? $_INPUT['date_search'] : 1;
$_attr = array('start_time' => 'start_time', 'end_time' => 'end_time');
{/code}
{template:new_search/date, date_search, $_INPUT['date_search'], $_configs['date_search'], $_attr}