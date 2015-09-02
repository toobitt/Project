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
$mydatafieldmark_attr = array('title'=>'字段标识');
$mydatauser_attr = array('title'=>'创建人');

$mark_attr = array('title'=>'我的模块');
$hg_mark_data = array('-1'=>'所有模块');
if(is_array($mySetInfo)&&$mySetInfo)
{
	foreach($mySetInfo as $k=>$v)
	{
		$hg_mark_data[$v['msid']] = $v['mstitle'];
	}
}
if(!isset($_INPUT['msid']))
{
	 $_INPUT['msid'] = -1;
}

$is_search_attr = array('title'=>'是否检索');
$hg_search_data = array('-1'=>'所有状态','1'=>'是','0'=>'否');
if(!isset($_INPUT['issearch']))
{
    $_INPUT['issearch'] = -1;
}

$is_isrequired_attr = array('title'=>'是否必填');
$hg_isrequired_data = array('-1'=>'所有状态','1'=>'是','0'=>'否');
if(!isset($_INPUT['isrequired']))
{
    $_INPUT['isrequired'] = -1;
}

$hg_addStatus_data = array('-1'=>'所有状态',
					'0'=>'用户传值',
					'1'=>'系统默认',
					'2'=>'系统时间',
					'3'=>'用户IP',
				);
				
$is_addStatus_attr = array('title'=>'添加方式');

if(!isset($_INPUT['addstatus']))
{
    $_INPUT['addstatus'] = -1;
}

$is_is_unique_attr = array('title'=>'重复数据');

$hg_is_unique_data = array('-1'=>'不限制',
					'0'=>'允许',
					'1'=>'禁止',
				);
if(!isset($_INPUT['is_unique']))
{
    $_INPUT['is_unique'] = -1;
}

{/code}
{template:new_search/input,key,$_INPUT['key'],array(),$mydataname_attr}
{template:new_search/select,msid,$_INPUT['msid'],$hg_mark_data,$mark_attr}
{template:new_search/select,is_unique,$_INPUT['is_unique'],$hg_is_unique_data,$is_is_unique_attr}
{template:new_search/select,addstatus,$_INPUT['addstatus'],$hg_addStatus_data,$is_addStatus_attr}
{template:new_search/select,issearch,$_INPUT['issearch'],$hg_search_data,$is_search_attr}
{template:new_search/select,isrequired,$_INPUT['isrequired'],$hg_isrequired_data,$is_isrequired_attr}
{template:new_search/input,fieldmark,$_INPUT['fieldmark'],array(),$mydatafieldmark_attr}
{template:new_search/input,user_name,$_INPUT['user_name'],array(),$mydatauser_attr}
{code}
$_INPUT['date_search'] = $_INPUT['date_search'] ? $_INPUT['date_search'] : 1;
$_attr = array('start_time' => 'start_time', 'end_time' => 'end_time');
{/code}
{template:new_search/date, date_search, $_INPUT['date_search'], $_configs['date_search'], $_attr}


 