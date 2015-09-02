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
$default_weight = array(
    'title' => "所有权重",
    'begin_w' => 0,
    'end_w' => 100,
    'default' => 1
);
array_unshift(  $formdata['weight'],1);
$formdata['weight'][0] = $default_weight;

{/code}

{template:new_search/title, key, $_INPUT['key']}

{template:new_search/author, author, $_INPUT['author']}

{template:new_search/pub_column, pub_column_id, $_INPUT['pub_column_id']}

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
{/code}
{template:new_search/status, status, $_INPUT['status'], $_configs['state_search']}

{code}
$_INPUT['date_search'] = $_INPUT['date_search'] ? $_INPUT['date_search'] : 1;
$_attr = array('start_time' => 'start_time', 'end_time' => 'end_time');
{/code}
{template:new_search/date, date_search, $_INPUT['date_search'], $_configs['date_search'], $_attr}

{code}
$_INPUT['weight'] = $_INPUT['weight'] ? $_INPUT['weight'] : 0;
$_attr = array('start_weight' => 'start_weight', 'end_weight' => 'end_weight');
{/code}
{template:new_search/weight, weight, $_INPUT['weight'], $formdata['weight'], $_attr}

{code}
$_configs['outlink_list'] = array(
    0   => '全部内容',
    1   => '外链',
    2    => '非外链'
);
$_attr = array(
    'title'  => '外链',
);
if (!$_INPUT['outlink_status']) {
    $_INPUT['outlink_status'] = 0;
}
{/code}
{template:new_search/select, outlink_status, $_INPUT['outlink_status'], $_configs['outlink_list'], $_attr}


 