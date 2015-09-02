<!-- 标题 -->
{template:new_search/title, key, $_INPUT['key']}


<!-- 添加人 -->
{template:new_search/creater, user_name, $_INPUT['user_name']}


<!-- 审核状态 -->
{code}
$_INPUT['contribute_sort_audit'] = $_INPUT['contribute_sort_audit'] ? $_INPUT['contribute_sort_audit'] : 0;
{/code}
{template:new_search/status, contribute_sort_audit, $_INPUT['contribute_sort_audit'], $_configs['contribute_audit']}

<!-- 记者 -->
{code}
$_INPUT['contribute_sort_report'] = $_INPUT['contribute_sort_report'] ? $_INPUT['contribute_sort_report'] : 0;
$hg_attr = array('title' => '记者');
{/code}
{template:new_search/status,contribute_sort_report,$_INPUT['contribute_sort_report'], $_configs['contribute_report'],$hg_attr}

<!-- 记者是否跟进 -->
{code}
$_INPUT['contribute_is_follow'] = $_INPUT['contribute_is_follow'] ? $_INPUT['contribute_is_follow'] : 0;
$hg_attr = array('title' => '是否跟进');
{/code}
{template:new_search/status, contribute_is_follow, $_INPUT['contribute_is_follow'], $_configs['contribute_follow'],$hg_attr}


<!-- 创建时间 -->
{code}
$_INPUT['contribute_sort_time'] = $_INPUT['contribute_sort_time'] ? $_INPUT['contribute_sort_time'] : 1;
$_attr = array('start_time' => 'start_time', 'end_time' => 'end_time');
{/code}
{template:new_search/date, contribute_sort_time, $_INPUT['contribute_sort_time'], $_configs['date_search'], $_attr}


 