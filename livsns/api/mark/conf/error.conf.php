<?php 
$errorConf = array(
	PARAM_NO_FULL => '参数缺失，未传递地盘名称，或用户未登录', 
	OBJECT_NULL => '地盘不存在或未通过审核',
	TITLE_NULL => '帖子缺少标题',
	TITLE_ERROR => '帖子含有非法字符',
	PAGETEXT_NULL => '帖子缺少内容',
	CODE_BADWORK => '内容非法信息',
	CATEGORY_NULL => '帖子类型错误',
	SYSTYPE_NULL => '帖子所属类型错误',
	GROUP_NULL => '讨论组没有开放相应权限',
	GROUP_NO_STATE => '讨论组不存在或者已经关闭',
	GROUP_NO_PERM => '讨论组没有开放发帖权限',
	POLL_NO_PARAMS => '投票参数错误',
	POLL_ERROR_PARAMS => '投票选项出错',
	GROUP_MEMBER_ERROR => '用户与讨论组关系出错',
	GROUP_MEMBER_NOROOT => '该用户没有对应的权限',
	GROUP_SET_NOROOT => '讨论组未开放对应权限',
	GROUP_NO_THREAD => '讨论组没有该帖子',
	DEL_THREAD_NOROOT =>'不许删除全站置顶帖子',
);
?>