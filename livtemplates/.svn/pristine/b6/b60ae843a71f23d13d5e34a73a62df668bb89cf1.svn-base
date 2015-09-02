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
$systemname_attr = array('title'=>'系统名称');
$identifier_attr = array('title'=>'系统标识');
$adduser_attr = array('title'=>'添加人');

$is_opened_attr = array('title'=>'是否启用');
$hg_opened_data = array('-1'=>'所有状态','1'=>'是','0'=>'否');
if(!isset($_INPUT['opened']))
{
    $_INPUT['opened'] = -1;
}

{/code}
{template:new_search/input,key,$_INPUT['key'],array(),$systemname_attr}
{template:new_search/select,opened,$_INPUT['opened'],$hg_opened_data,$is_opened_attr}
{template:new_search/input,identifier,$_INPUT['identifier'],array(),$identifier_attr}
{template:new_search/input,user_name,$_INPUT['user_name'],array(),$adduser_attr}
{code}
$_INPUT['date_search'] = $_INPUT['date_search'] ? $_INPUT['date_search'] : 1;
$_attr = array('start_time' => 'start_time', 'end_time' => 'end_time');
{/code}
{template:new_search/date, date_search, $_INPUT['date_search'], $_configs['date_search'], $_attr}


 