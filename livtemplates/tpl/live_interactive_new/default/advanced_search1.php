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
$_attr = array('start_time' => 'start_time', 'end_time' => 'end_time');
{/code}
{template:new_search/date, date_search, $_INPUT['date_search'], $_configs['date_search'], $_attr}


 