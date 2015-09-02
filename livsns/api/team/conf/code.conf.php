<?php
define('PARAM_WRONG', '0x1000');             //参数错误
define('TEAM_EXISTS', '0x2000');             //小组名已存在
define('TEAM_NO_EXISTS', '0x2100');          //小组不存在或未审核
define('TEAM_NO_TOPIC', '0x2200');           //讨论组没有该帖子
define('TOPIC_CLOSE', '0x2300');             //讨论组已关闭
define('SYSTEM_LIMIT', '0x3000');            //创建小组已达到系统上限
define('APPLY_LIMIT', '0x3100');             //申请活动召集者达到系统上限
define('APPLY_HAS', '0x3200');               //已经申请过
define('FAIL_OP', '0x4000');                 //操作失败
define('SUCCESS_OP', '0x4100');              //操作成功
define('NO_PERMISSION', '0x5000');           //没有权限操作


/*

define('PARAM_NO_FULL', '0x1000');		//参数不完整 
define('PARAM_WRONG', '0x1100');        //参数错误
define('OBJECT_NULL', '0x2000');		//地盘不存在或审核未通过

define('SYSTEM_LIMIT', '0x3000');       //系统限制
define('NO_PERMISSION', '0x3100');      //没有权限操作
define('NO_MEMBERS', '0x4000');         //该用户不存在或已注销
define('IS_GRAND', '0x4100');           //已是该地盘的地主
define('POST_APPLY', '0x5000');         //已提交过申请，等待审核
define('SUCCESS_APPLY', '0x5100');      //成功提交申请，等待审核
define('SUCCESS_OP', '0x6000');         //操作成功
*/
/*****错误参数*****/

/*
define('TITLE_NULL','0x30000');			//
define('TITLE_ERROR','0x30001');
define('CODE_BADWORK','0x30002');		//
define('PAGETEXT_NULL','0x30003');		//
define('CATEGORY_NULL','0x30004');		//
define('CATEGORY_NO_STRING','0x30005');		//
define('SYSTYPE_NULL','0x30006');
define('SYSTYPE__NO_STRING','0x30007');
define('GROUP_NULL','0x30008');
define('GROUP_NO_STATE','0x30009');
define('GROUP_NO_PERM','0x30010');
define('POLL_NO_PARAMS','0x30011');
define('POLL_ERROR_PARAMS','0x30012');
define('GROUP_MEMBER_ERROR','0x30013');		//用户不是讨论组的成员
define('GROUP_MEMBER_NOROOT','0x30014');	//用户权限不足
define('GROUP_SET_NOROOT','0x30015');	//讨论组未开放对应权限
define('GROUP_NO_THREAD','0x30016');	//讨论组没有对应帖子
define('DEL_THREAD_NOROOT','0x30017');	//讨论组没有对应帖子
define('THREAD_MEMBER_NOROOT','0x30018');	//讨论组没有对应帖子
define('THREAD_BUTILD_FAST','0x30019');	//讨论组没有对应帖子
*/
?>