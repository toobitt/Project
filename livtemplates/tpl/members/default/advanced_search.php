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
$member_name_attr = array('title'=>'会员名');
$mobile_select_attr = array('title'=>'手机');
$email_select_attr = array('title'=>'邮箱');
$device_token_attr = array('title'=>'注册设备');
$ip_attr = array('title'=>'注册IP');
$invite_user_attr = array('title'=>'邀请人');
$spread_code_attr = array('title'=>'推广码');
$is_avatar_attr = array('title'=>'头像');
$is_mobile_attr = array('title'=>'手机');
$is_sort_attr = array('title'=>'排序');
$is_member_type_attr = array('title'=>'会员类型');
$is_memberapp_info_attr = array('title'=>'注册来源');
$identifierUserSystemId_attr = array('title'=>'系统ID');
$identifierUserSystemName_attr = array('title'=>'系统名称');
$is_membermedal_attr = array('title'=>'拥有勋章');
$is_gradeinfo_attr = array('title'=>'等级');
$isBlackList_attr = array('title'=>'黑名单');
$isVerify_attr = array('title'=>'实名认证');
$hg_avatar_data = array('-1'=>'头像状态','1'=>'已上传','0'=>'未上传');
$hg_mobile_data = array('-1'=>'填写状态','1'=>'已填写','0'=>'未填写');
$hg_regtime_sort_data = array('-1'=>'注册时间','1'=>'升序','0'=>'降序');
$hg_credits_sort_data = array('-1'=>'总积分','1'=>'升序','0'=>'降序');
$hg_isVerify_data = array('-1'=>'实名状态','1'=>'已认证','0'=>'未认证');
$hg_member_type_data = array('-1'=>'所有类型');
$hg_black_data = array(0 => '否',1 => '是');
if(is_array($get_member_type)&&$get_member_type)
{
	foreach($get_member_type as $k=>$v)
	{
		$hg_member_type_data[$v['type']] = $v['type_name'];
	}
}
if(!isset($_INPUT['member_type']))
{
	 $_INPUT['member_type'] = -1;
}
$hg_memberapp_info_data = array('-1'=>'所有来源');
if(is_array($get_member_app)&&$get_member_app)
{
	foreach($get_member_app as $k=>$v)
	{
		$hg_memberapp_info_data[$v['appid']] = $v['appname'];
	}
}
if(!isset($_INPUT['member_appid']))
{
	$_INPUT['member_appid'] = -1;
}

$hg_membermedal_data = array('-1'=>'所有勋章');
if(is_array($medal_info)&&$medal_info)
{
	foreach($medal_info as $k=>$v)
	{
		$hg_membermedal_data[$v['id']] = $v['name'];
	}
}	
if(!isset($_INPUT['medalid']))
{
	$_INPUT['medalid'] = -1;
}

$hg_gradeinfo_data = array('-1'=>'所有等级');
if(is_array($grade_info)&&$grade_info)
{
	foreach($grade_info as $k=>$v)
	{
		$hg_gradeinfo_data[$v['id']] = $v['name'];
	}
}

if(!isset($_INPUT['gradeid']))
{
	$_INPUT['gradeid'] = -1;
}
			
if(!isset($_INPUT['is_avatar']))
{
    $_INPUT['is_avatar'] = -1;
}
if(!isset($_INPUT['is_mobile']))
{
    $_INPUT['is_mobile'] = -1;
}
if(!isset($_INPUT['is_regtime_sort']))
{
    $_INPUT['is_regtime_sort'] = -1;
}
if(!isset($_INPUT['is_credits_sort']))
{
    $_INPUT['is_credits_sort'] = -1;
}
if(!isset($_INPUT['isVerify']))
{
    $_INPUT['isVerify'] = -1;
}
if(!isset($_INPUT['isBlackList']))
{
    $_INPUT['isBlackList'] = 0;
}
if(!isset($_INPUT['isBlackList']))
{
    $_INPUT['isBlackList'] = 0;
}
{/code}
{template:new_search/input,key,$_INPUT['key'],array(),$member_name_attr}
{template:new_search/input,mobile,$_INPUT['mobile'],array(),$mobile_select_attr}
{template:new_search/input,email,$_INPUT['email'],array(),$email_select_attr}
{template:new_search/input,device_token,$_INPUT['device_token'],0,$device_token_attr}
{template:new_search/input,ip,$_INPUT['ip'],0,$ip_attr}
{template:new_search/input,invite_user,$_INPUT['invite_user'],0,$invite_user_attr}
{template:new_search/input,identifierName,$_INPUT['identifierName'],0,$identifierUserSystemName_attr}
{template:new_search/input,identifier,$_INPUT['identifier'],0,$identifierUserSystemId_attr}
{template:new_search/input,spreadCode,$_INPUT['spreadCode'],0,$spread_code_attr}
{template:new_search/select,is_avatar,$_INPUT['is_avatar'],$hg_avatar_data,$is_avatar_attr}
{template:new_search/select,is_mobile,$_INPUT['is_mobile'],$hg_mobile_data,$is_mobile_attr}
{template:new_search/select,member_type,$_INPUT['member_type'],$hg_member_type_data,$is_member_type_attr}
{template:new_search/select,member_appid,$_INPUT['member_appid'],$hg_memberapp_info_data,$is_memberapp_info_attr}
{template:new_search/select,medalid,$_INPUT['medalid'],$hg_membermedal_data,$is_membermedal_attr}
{template:new_search/select,gradeid,$_INPUT['gradeid'],$hg_gradeinfo_data,$is_gradeinfo_attr}
{template:new_search/select,is_regtime_sort,$_INPUT['is_regtime_sort'],$hg_regtime_sort_data,$is_sort_attr}
{template:new_search/select,is_credits_sort,$_INPUT['is_credits_sort'],$hg_credits_sort_data,$is_sort_attr}
{template:new_search/select,isVerify,$_INPUT['isVerify'],$hg_isVerify_data,$isVerify_attr}
{template:new_search/select,isBlackList,$_INPUT['isBlackList'],$hg_black_data,$isBlackList_attr}

{code}
if(!isset($_INPUT['status']))
{
    $_INPUT['status'] = -1;
}
{/code}
{template:new_search/status, status, $_INPUT['status'], $_configs['member_state']}

{code}
$_INPUT['date_search'] = $_INPUT['date_search'] ? $_INPUT['date_search'] : 1;
$_attr = array('start_time' => 'start_time', 'end_time' => 'end_time', 'hasSecond' => true);
{/code}
{template:new_search/date, date_search, $_INPUT['date_search'], $_configs['date_search'], $_attr}


 