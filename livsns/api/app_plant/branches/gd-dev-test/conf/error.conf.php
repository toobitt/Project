<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-18
 * @encoding    UTF-8
 * @description 错误对应的文本
 **************************************************************************/

$errorConf = array(
    UNKNOW 					=> '未知错误',
    OBJECT_NULL 			=> '对象为空',
    SUCCESS 				=> '成功',
    FAILED 					=> '失败',
    NO_APPID 				=> '应用id不存在',
    PARAM_WRONG 			=> '参数错误',
    NAME_EXISTS 			=> '中文名重复',
    ENGLISH_EXISTS 			=> '英文名重复',
    TYPE_ERROR 				=> '类型错误',
    TYPE_ERROR1 			=> '引导图类型错误',
    TYPE_ERROR2 			=> '应用图标类型错误',
    TYPE_ERROR3 			=> '启动画面类型错误',
    TYPE_ERROR4 			=> '导航栏标题类型错误',
    TYPE_ERROR5 			=> '首页背景类型错误',
    SIZE_ERROR 				=> '尺寸错误',
    SIZE_ERROR1 			=> '引导图尺寸错误',
    SIZE_ERROR2 			=> '应用图标尺寸错误',
    SIZE_ERROR3 			=> '启动画面尺寸错误',
    SIZE_ERROR4 			=> '导航栏标题尺寸错误',
    SIZE_ERROR5 			=> '首页背景尺寸错误',
    OVER_LIMIT 				=> '超过限制个数',
    URL_NOT_VALID 			=> 'URL地址无效',
    CHAR_OVER 				=> '超过限定字符长度',
    COLOR_ERROR 			=> '颜色值错误',
    FILE_TYPE_ERROR 		=> '模块上传图标压缩包类型有误',
    APP_ICON_ERROR 			=> 'APP图标未上传或上传有误',
    APP_STARTPIC_ERROR 		=> 'APP启动画面未上传或上传有误',
    MARK_EXISTS 			=> '标识重复',
    NAME_REPEAT 			=> '名称重复',
    NO_SOLIDIFY_ID 			=> '没有固化模块id',
    NO_USER_ID 				=> '没有用户id',
    NO_SOLIDIFY_PARAM 		=> '没有固化参数',
    NO_CONFIG_ID 			=> '没有配置id',
    PROPERTY_AUTH_FAIL 		=> '属性验证失败',
    COLUMN_SORT_WRONG 		=> '栏目排序错误',
    NOID 					=> '没有id',
    APP_ID_EXISTS_ERROR 	=> '所传应用id有误',
    CLIENT_INFO_NOT_EXISTS 	=> '客户端信息不存在',
    APP_NOT_EXISTS 			=> '该应用不存在',
    NO_CLIENT_TYPE 			=> '没有客户端类型',
    NO_APP_ID 				=> '没有应用id',
    CUR_VERSION_TOO_LOW 	=> '当前版本小于上一个版本',
    NO_VERSION_INFO 		=> '没有版本信息',
    NO_APP_ID_OR_CLIENT_TYPE=> '没有应用id或者没有客户端类型',
    NO_VERSION_ID			=> '没有版本id',
    NO_QUEUE_ID				=> '没有队列id',
    NO_VERSION_ID_OR_QUEUE_ID	=> '没有版本id或者队列id',
    NO_VERSION_NUM			=> '没有版本号',
    VERSION_NUM_ERROR		=> '版本号有错',
    ERR_SHARE_DATA			=> '分享数据有误',
    NO_UUID					=> '没有uuid',
    NO_SYSTEM_ICON_URL		=> '系统图标下载地址不能为空',
    NO_NAME					=> '没有用户名',
    NO_DINGDONE_NAME		=> '没有叮当用户名',
    NO_DINGDONE_USER_ID		=> '没有叮当用户id',
    NO_TYPE					=> '没有申请类型',
    NO_IDENTITY_TYPE		=> '没有身份类型',
    NO_ID_OR_QUEUE_ID		=> '没有版本id或者queue_id',
    NO_TPL_NAME				=> '没有模板名称',
    NO_TPL_HTML				=> '没有正文HTML',
    NO_LOGIN				=> '未登陆',
    NO_STATUS				=> '没有状态',
    NO_PROVINCE_CODE		=> '没有省的地区码',
    NO_ACCOUNT_NAME			=> '没有账号名',
    NO_PASSWORD				=> '没有密码',
    NO_PLANT_TYPE			=> '没有平台类型',
    NO_CITY_CODE			=> '没有城市的地区码',
    THIS_USER_NOT_PUSH_API	=> '该用户还未配置推送接口',
    MSG_CAN_NOT_EMPTY		=> '推送的消息不能为空',
    NO_MODULE_ID			=> '没有模块id',
    NO_CONTENT_ID			=> '没有内容id',
    NO_MODULE_MARK			=> '没有模块标识',
    NO_PUSH_URL				=> '没有推送链接',
    NO_SELECT_OPEN_MODE		=> '没有选择打开模式',
    NO_SELECT_DEVICE_TYPE	=> '未选择终端类型',
    DEVICE_TYPE_ERR			=> '终端类型有误',
    IDENTITY_AUTH_HAS_EXISTS=> '您的申请已存在',
    MSG_IS_TOO_LONG			=> '消息过长',
    GUIDE_PIC_ERROR         => '引导图上传错误',
    NO_SESSID        	 	=> '没有session_id',
    NO_TOKEN        	 	=> '没有token',
    NO_CLIENT_ID        	=> '没有客户端id',
    NO_TPL_ID        	    => '没有模板id',
    FAIL_UPLOAD_TO_MATARIAL => '提交图片到附件失败',
    NO_PIC_ID               => '没有图片id',
    ORDER_ERROR             => '排序id错误',
    NO_SEEKHELP_ID          => '没有互助id',
    NO_APP_NAME             => '没有应用名称',
    NO_APP_ICON_ID          => '没有应用图标id',
    TEXT_SIZE_ERROR         => '文字大小有误',
    TPL_ATTR_ERROR          => '模板属性有误',
    PIC_NOT_EXISTS          => '图片不存在',
    MODULE_NOT_EXISTS       => '模块不存在',
    WEBVIEW_NOT_EXISTS      => 'webview不存在',
    UPLOAD_ERROR            => '上传错误',
    ATTR_GROUP_NOT_EXISTS   => '属性组不存在',
    NO_ATTR_GROUP_NAME      => '没有属性组的名称',
    NO_ATTR_GROUP_MARK      => '没有属性组的标识',
    NO_ATTR_GROUP_TYPE      => '没有属性组的类型',
    ATTR_NOT_EXISTS         => '属性不存在',
    INTERFACE_NOT_EXISTS    => '界面不存在',
    TEMPLATE_NOT_EXISTS     => '模板不存在',
    ATTR_NAME_NOT_EXISTS    => '属性名不存在',
    ATTR_MARK_NOT_EXISTS    => '属性标识不存在',
    ATTR_TYPE_NOT_EXISTS    => '属性类型不存在',
    NO_CLIENT_NAME          => '没有客户端名称',
    NO_CLIENT_MARK          => '没有客户端标识',
    NO_INTERFACE_NAME       => '没有界面名称',
    NO_INTERFACE_MARK       => '没有界面标识',
    NO_MODULE_NAME          => '没有模块名称',
    NO_TPL_MARK             => '没有模板标识',
    IS_DIR_CAN_WRITE        => '检查目录可写权限',
    DATA_ERROR              => '数据有误',
    CREATE_FILE_ERROR       => '生成文件有误',
    NO_MODULE_URL           => '没有模块图片url',
    NO_CATEGORY_NAME        => '没有图片分类名称',
    NO_CATEGORY_MARK        => '没有图片分类标识',
    ICON_NOT_EXISTS         => '图标不存在',
    PIC_NUM_IS_TOO_MORE     => '图片数目查过预设的数目',
    ERROR_SELECTED_BG_ID    => '选中的背景图有误',
    NO_UI_NAME              => '没有UI名称',
    NO_UI_TYPE              => '没有UI类型',
    NO_UNIQUEID             => '没有标识',
    UNIQUEID_HAS_EXISTS     => '标识已经存在',
    NO_TYPE_NAME            => '没有类型名称',
    NO_ATTR_NAME            => '没有属性名称',
    NO_ATTR_TYPE            => '没有属性类型',
    DEFAULT_VALUE_OVER      => '默认值超出范围',
    THIS_UI_UNIQUEID_HAS_EXISTS  => '该已经存在该标识',
    NO_ROLE_ID              => '未选择角色',
    NO_GROUP                => '未选择分组',
    NO_BIND_ID              => '没有绑定的第三方节点',
    BIND_CONTENT_INVALID    => '不能绑定非内容模块',
    THIS_UI_GROUP_ROLE_HAS_EXISTS    => '该UI下的分组下的该角色已经存在该属性',
    NO_UI_ID                => '未传ui的id',
    NOT_EXISTS_UI           => '该UI不存在',
    NOT_EXISTS_ATTR_IN_UI   => '该ui下不存在属性',
    USER_NOT_EXISTS         => '用户不存在',
    THIS_UI_ALREADY_HAS_ATTR=> '该UI已经存在属性，请清除再复制',
    THIS_UI_NOT_HAS_ATTR    => '该UI下不存在属性',
    NO_ATTR_VALUE           => '没有属性值',
    THIS_MODULE_NOT_BIND_LIST_UI  => '该模块未绑定LIST_UI',
    NO_MAIN_UI_ID           => '没有MainUI的ID',
    NO_SELECT_SET_VALUE_TYPE  => '未选择设置值的方式',
    ATTR_IDS_ERROR          => '未选取后台属性',
    NO_HOME_BG              => '首页背景不存在',
    SOLID_NOT_EXISTS        => '固话模块不存在',
    YOU_SHOULD_CREATE_TEAM  => '请在群聊里面先创建群组才能使用此功能',
    DELETE_FAIL             => '删除失败',
    NO_COND                 => '没有查询条件',
	DATA_URL_NULL  			=> 'dataUrl不能为空',
	APP_ID_NULL             => 'appId不能为空',
	MODULE_ID_NULL          => 'moduleId不能为空',
	TYPE_WRONG				=> 'type错误',
	NO_FRAME_MARK           => '没有框架标识',
    NO_TPL_BODY_MARK        => '没有正文模板标识',
    NO_COLUMN_ID            => '没有栏目id',
    THIS_MOUDLE_NOT_EXIST   => '此模块不存在',
    BODY_TPL_NOT_EXIST      => '正文模板不存在',
	BIND_UPDATE_FAIL		=> '绑定失败',
	NO_TPL_UNIQUEID         => '没有正文模板标识',
	TOKEN_VALIDATE_FAIL		=> 'access_token验证失败',
	NO_DATA                 => '没有数据',
	NO_IDENTITY_NUM         => '没有证件号',
	NO_EMAIL                => '没有邮箱',
	YOU_HAVE_SUBMIT_APPLY   => '您已经提交了申请',
	PUSH_FAIL               => '推送失败',
	NO_REPORT_CONTENT       => '举报内容为空',
	NO_DEVICE_TOKEN			=> '没有设备号',
	REPORT_MEMBERID_ERROR	=> '会员不属于对应应用',
	YOU_SHOULD_CREATE_COMMUNITY_FIRST   => '请先创建社区再选择此模块',
	NO_MODEL_INFO           => '没有设备信息',
	CLIENT_TYPE_WRONG       => '客户端类型错误',
	NO_SYSTEM_INFO			=> '系统信息错误',
	APP_ID_WRONG			=> 'app_id错误',
	PAY_LOG_ERROR			=> '付款日志出错',
	YOU_HAVE_NOT_APPLY      => '您还未提交申请',
	YOU_CAN_NOT_RE_SUBMIT_APPLY      => '您不能重新提交申请',
	NO_GUID				    => 'guid信息错误',
	YOU_HAVE_NOT_THIS_COMP	=> '您没有此组件',
	YOU_SELECTED_LISTUI_ERROR => '您选择的listUI有误',
	NO_SELECT_DATA_SOURCE   => '您未选择数据源',
	YOU_HAVE_NOT_THIS_DATA_SOURCE   => '您没有此数据源',
	IM_IS_BLACK             => '您的群组被列为黑名单，请联系管理员',
	SEEKHELP_IS_BLACK       => '您的社区被列为黑名单，请联系管理员',
	NO_COMP_ID              => '没有组件ID',
	COMP_NOT_EXTSTS         => '组件不存在',
	THIS_COMP_NOT_BIND_LIST_UI => '组件未绑定listUI',
	THIS_COMP_HAS_SELECTED  => '该组件已经选取',
	COMP_ID_FEI_FA          => '组件ID不合法',
	NO_APP_NAME             => '没有应用名称',
	WEIGHT_ERROR            => '权重设置有误',
	THIS_COMP_HAS_USED      => '该组件已经被使用，请先在模块设置里面取消该组件再关闭',
	NO_DOMAIN               => '没有域名',
	NO_API_NAME             => '没有接口名称',
	NO_URL                  => '没有链接',
	URL_NOT_IN_WHITE        => '域名不在白名单内',
	NO_COND_TYPE            => '没有条件类型',
	NO_SUPERSCRIPT_NAME     => '没有角标名称',
	SUPERSCRIPT_ID_FEI_FA   => '角标ID不合法',
	NO_SUPERSCRIPT_ID       => '没有角标ID',
	SUPERSCRIPT_NOT_EXIST   => '该角标不存在',
    HAS_SEEKHELP            => '您已经绑定了一个微社区',
    CORNER_NUM_IS_OVER      => '角标个数超过最大限制',
    NO_SELECT_IMG           => '未选择图片',
    NO_SELECT_IMG_TYPE      => '未选择图片类型',
    IMG_ERROR               => '图片有误',
    NO_SHOW_TYPE            => '没有显示类型',
    NO_USE_CORNER           => '未使用角标',
	CORNER_IS_USE			=> '角标正在使用',
);