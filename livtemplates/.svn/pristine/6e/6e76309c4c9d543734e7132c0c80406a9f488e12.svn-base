<?php
/**
 * Created by livtemplates.
 * User: wangleyuan
 * Date: 14-5-28
 * Time: 上午11:13
 */
?>
{template:new_search/title, k, $_INPUT['k']}
{code}
$_attr_content_url = array('title' => '内容链接');
{/code}
{template:new_search/input, content_url, $_INPUT['content_url'], '', $_attr_content_url}

{template:new_search/creater, user_name, $_INPUT['user_name']}

{code}
$_attr = array('title' => '评论id');
{/code}
{template:new_search/input, comm_id, $_INPUT['comm_id'], '',$_attr}

{code}
$_INPUT['message_status'] = $_INPUT['message_status'] ? $_INPUT['message_status'] : 0;
if (empty($_configs['state_search'])) {
$_configs['state_search'] = array(
    '0'   => '全部状态',
    '1'   => '未审核',
    '2'   => '已审核',
    '3'   => '已打回',
);
}
{/code}
{code}
$_attr_ip = array('title' => 'ip地址');
{/code}
{template:new_search/input, ip, $_INPUT['ip'], '',$_attr_ip}
{template:new_search/status, message_status, $_INPUT['message_status'], $_configs['state_search']}

{code}
$_INPUT['date_search'] = $_INPUT['date_search'] ? $_INPUT['date_search'] : 1;
$_attr = array('start_time' => 'start_time', 'end_time' => 'end_time');
{/code}
{template:new_search/date, date_search, $_INPUT['date_search'], $_configs['date_search'], $_attr}



<!--引入按搜索条件删除标识-->
<div class="search-delete-conditions" style="display:none;">
	{template:new_search/input, ip, $_INPUT['ip'], '',$_attr_ip}
</div>