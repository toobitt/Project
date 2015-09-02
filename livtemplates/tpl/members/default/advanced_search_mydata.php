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
$mydataname_attr = array('title'=>'数据名称');
$mydatamark_attr = array('title'=>'数据标识');
$mydatauser_attr = array('title'=>'所属人');
$mydataselect_attr = array('title'=>'数据检索');
{/code}
{template:new_search/input,key,$_INPUT['key'],array(),$mydataname_attr}
{template:new_search/input,mark,$_INPUT['mark'],array(),$mydatamark_attr}
{template:new_search/input,member_name,$_INPUT['member_name'],array(),$mydatauser_attr}
{template:new_search/input,search,$_INPUT['search'],array(),$mydataselect_attr}
{code}
$_INPUT['date_search'] = $_INPUT['date_search'] ? $_INPUT['date_search'] : 1;
$_attr = array('start_time' => 'start_time', 'end_time' => 'end_time');
{/code}
{template:new_search/date, date_search, $_INPUT['date_search'], $_configs['date_search'], $_attr}


 