<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: error.conf.php 5868 2012-02-07 06:46:44Z repheal $
***************************************************************************/

$errorConf = array(
	UNKNOW => '未知错误',
	OBJECT_NULL => '对象为空',
	SUCCESS => '成功',
	FAILED => '失败',
	APP_UNIQUEID_ERROR =>'应用ID不存在或者为空',
	MOD_UNIQUEID_ERROR =>'模块ID不存在或者为空',
	CONTENT_ID_ERROR =>'应用内容ID不存在或者为空',
	DATA_ID_ERROR =>'编目内容ID不存在或者为空',
	CATALOG_FIELD_ERROR =>'编目标识未传参',
	ADD_FAILED => '添加失败',
	UPDATE_FAILED => '更新失败',
	DELETE_FAILED => '删除失败',
	PARAM_WRONG=>'参数错误',
	NO_ACTION=>'此方法不存在或未传入方法名',
	NO_RECORD=>'该记录不存在或已被删除',
	NO_DATA_ID=>'无效的数据id或者id不存在',
	EDIT_FAILED=>'编辑失败',
	CATALOG_SORT_NOT_SELECT =>'分类未选择',
	CATALOG_SORT_NOT_NULL	=>	'分类不能为空',
	CATALOG_SORT_NAME_NOT_NULL	=>	'分类名称不能为空',
	NO_CATALOG_SORT_ID =>'分类id不存在或者为空',
	CATALOG_SORT_FIELD_NOT_NULL =>'分类字段不能为空',
	CATALOG_SORT_FIELD_EXIST =>'分类字段已存在',
	CATALOG_SORT_NAME_EXIST =>'分类名称已存在',
	CATALOG_SORT_USES_NOT_DELETE =>'该分类已被使用，禁止删除',
	NAME_EXISTS =>'不允许有重复的值',
	FORBID_UPDATE=>'不允许修改标识字段',
	CACHE_ERROR=>'Error : 缓存更新失败,请检查缓存文件夹读写权限',
	CONTENT_EXIST=>'内容已存在,无需重复创建',
	NO_CONTENT=>'未传任何内容',
	NO_INPUT=>'"输入不能为空',
	STYLE_NAME_EXIST =>'该样式名称已存在',
	NO_BIND_APP =>'此应用未绑定编目',
	NO_PRIVILEGE=>'没有权限',
);
?>