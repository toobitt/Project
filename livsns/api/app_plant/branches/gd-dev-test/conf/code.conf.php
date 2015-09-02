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
 * @description 错误码常量
 **************************************************************************/

define('UNKNOW', 'Unknow');               //未知错误
define('OBJECT_NULL','0x0000');           //对象为空
define('PARAM_WRONG', '0x1000');          //参数错误
define('NAME_EXISTS', '0x2000');          //中文名重复
define('ENGLISH_EXISTS', '0x2100');       //英文名重复
define('NO_APPID', '0x3000');             //应用id不存在
define('TYPE_ERROR', '0x4000');           //类型错误
define('TYPE_ERROR1', '0x4100');          //引导图类型错误
define('TYPE_ERROR2', '0x4200');          //应用图标类型错误
define('TYPE_ERROR3', '0x4300');          //启动画面类型错误
define('TYPE_ERROR4', '0x4400');          //导航栏标题类型错误
define('TYPE_ERROR5', '0x4500');          //首页背景类型错误
define('SIZE_ERROR', '0x5000');           //尺寸错误
define('SIZE_ERROR1', '0x5100');          //引导图尺寸错误
define('SIZE_ERROR2', '0x5200');          //应用图标尺寸错误
define('SIZE_ERROR3', '0x5300');          //启动画面尺寸错误
define('SIZE_ERROR4', '0x5400');          //导航栏标题尺寸错误
define('SIZE_ERROR5', '0x5500');          //首页背景尺寸错误
define('OVER_LIMIT', '0x6000');           //超过限制个数
define('URL_NOT_VALID', '0x7000');        //URL地址无效
define('CHAR_OVER', '0x8000');            //超过限定字符长度
define('SUCCESS',true);                   //成功
define('FAILED',false);                   //失败
define('COLOR_ERROR', '0x9000');          //颜色值错误
define('FILE_TYPE_ERROR', '0x10000');     //模块上传图标压缩包类型有误
define('APP_ICON_ERROR', '0x11000');      //APP图标未上传或上传有误
define('APP_STARTPIC_ERROR', '0x12000');  //APP启动画面未上传或上传有误
define('MARK_EXISTS', '0x13000');         //标识重复
define('NAME_REPEAT', '0x14000');         //名称重复
define('NO_SOLIDIFY_ID', '0x15000');      //没有固化模块id
define('NO_USER_ID', '0x16000');          //没有用户id
define('NO_SOLIDIFY_PARAM', '0x17000');   //没有用户id
define('NO_CONFIG_ID', '0x18000');        //没有配置id
define('PROPERTY_AUTH_FAIL', '0X19000');  //属性验证失败
define('COLUMN_SORT_WRONG', '0x20000');   //栏目排序错误
define('NOID','0x21000');//没有id
define('APP_ID_EXISTS_ERROR', '0x22000');//所传应用id有误
define('CLIENT_INFO_NOT_EXISTS', '0x23000');//客户端信息不存在
define('APP_NOT_EXISTS', '0x24000');//该应用不存在
define('NO_CLIENT_TYPE', '0x25000');//没有客户端类型
define('NO_APP_ID', '0x26000');//没有应用id
define('CUR_VERSION_TOO_LOW', '0x27000');//当前版本小于上一个版本
define('NO_VERSION_INFO', '0x28000');//没有版本信息
define('NO_APP_ID_OR_CLIENT_TYPE', '0x29000');//没有应用id或者没有客户端类型
define('NO_VERSION_ID', '0x30000');//没有版本id
define('NO_QUEUE_ID', '0x31000');//没有队列id
define('NO_VERSION_ID_OR_QUEUE_ID', '0x32000');//没有版本id或者队列id
define('NO_VERSION_NUM', '0x33000');//没有版本号
define('VERSION_NUM_ERROR', '0x34000');//版本号有错
define('ERR_SHARE_DATA', '0x35000');//分享数据有误
define('NO_UUID', '0x36000');//没有uuid
define('NO_SYSTEM_ICON_URL', '0x37000');//系统图标下载地址不能为空
define('NO_NAME', '0x38000');//没有用户名
define('NO_DINGDONE_NAME', '0x39000');//没有叮当用户名
define('NO_DINGDONE_USER_ID', '0x40000');//没有叮当用户id
define('NO_TYPE', '0x41000');//没有申请类型
define('NO_IDENTITY_NUM', '0x42000');//没有证件号
define('NO_ID_OR_QUEUE_ID', '0x43000');//没有版本id或者queue_id
define('NO_TPL_NAME', '0x44000');//没有模板名称
define('NO_TPL_HTML', '0x45000');//没有正文html
define('NO_LOGIN', '0x46000');//未登陆
define('NO_STATUS', '0x47000');//没有状态
define('NO_PROVINCE_CODE', '0x48000');//没有省的地区码
define('NO_ACCOUNT_NAME', '0x49000');//没有账号名
define('NO_PASSWORD', '0x50000');//没有密码
define('NO_PLANT_TYPE', '0x51000');//没有平台类型
define('NO_CITY_CODE', '0x52000');//没有城市的地区码
define('THIS_USER_NOT_PUSH_API', '0x53000');//该用户还未配置推送接口
define('MSG_CAN_NOT_EMPTY', '0x54000');//推送的消息不能为空
define('NO_MODULE_ID', '0x55000');//没有模块id
define('NO_CONTENT_ID', '0x56000');//没有内容id
define('NO_MODULE_MARK', '0x57000');//没有模块标识
define('NO_PUSH_URL', '0x58000');//没有推送链接
define('NO_SELECT_OPEN_MODE', '0x59000');//没有选择打开模式
define('NO_SELECT_DEVICE_TYPE', '0x60000');//未选择终端类型
define('DEVICE_TYPE_ERR', '0x61000');//终端类型有误
define('IDENTITY_AUTH_HAS_EXISTS', '0x62000');//您的申请已存在
define('MSG_IS_TOO_LONG', '0x63000');//消息过长
define('GUIDE_PIC_ERROR', '0x64000');//引导图上传错误
define('NO_SESSID', '0x65000');//没有session_id
define('NO_TOKEN', '0x66000');//没有token
define('NO_CLIENT_ID', '0x67000');//没有客户端id
define('NO_TPL_ID', '0x68000');//没有模板id
define('FAIL_UPLOAD_TO_MATARIAL', '0x69000');//提交图片到附件失败
define('NO_PIC_ID', '0x70000');//没有图片id
define('ORDER_ERROR', '0x71000');//排序错误
define('NO_SEEKHELP_ID', '0x72000');//没有互助id
define('NO_APP_ICON_ID', '0x73000');//没有应用图标id
define('TEXT_SIZE_ERROR', '0x74000');//文字大小有误
define('TPL_ATTR_ERROR', '0x75000');//模板id有误
define('PIC_NOT_EXISTS', '0x76000');//图片不存在
define('MODULE_NOT_EXISTS', '0x77000');//模块不存在
define('WEBVIEW_NOT_EXISTS', '0x78000');//webview不存在
define('UPLOAD_ERROR', '0x79000');//上传错误
define('ATTR_GROUP_NOT_EXISTS', '0x80000');//属性组不存在
define('NO_ATTR_GROUP_NAME', '0x81000');//没有属性组的名称
define('NO_ATTR_GROUP_MARK', '0x82000');//没有属性组的标识
define('NO_ATTR_GROUP_TYPE', '0x83000');//没有属性组的类型
define('ATTR_NOT_EXISTS', '0x84000');//属性不存在
define('INTERFACE_NOT_EXISTS', '0x85000');//界面不存在
define('TEMPLATE_NOT_EXISTS', '0x86000');//模板不存在
define('ATTR_NAME_NOT_EXISTS', '0x87000');//属性名不存在
define('ATTR_MARK_NOT_EXISTS', '0x88000');//属性标识不存在
define('ATTR_TYPE_NOT_EXISTS', '0x89000');//属性类型不存在
define('NO_CLIENT_NAME', '0x90000');//没有客户端名称
define('NO_CLIENT_MARK', '0x91000');//没有客户端标识
define('NO_CLIENT_MARK', '0x91000');//没有客户端标识
define('NO_INTERFACE_NAME', '0x92000');//没有界面名称
define('NO_INTERFACE_TYPE', '0x93000');//没有界面标识
define('NO_MODULE_NAME', '0x94000');//没有模块名称
define('NO_TPL_MARK', '0x95000');//没有模块标识
define('IS_DIR_CAN_WRITE', '0x96000');//检查目录可写权限
define('DATA_ERROR', '0x97000');//数据有误
define('CREATE_FILE_ERROR', '0x98000');//生成文件有误
define('NO_MODULE_URL', '0x99000');//没有模块图片url
define('NO_CATEGORY_NAME', '0x1000000');//没有图标分类名称
define('NO_CATEGORY_MARK', '0x1000001');//没有图标分类标识
define('ICON_NOT_EXISTS', '0x1000002');//图标不存在
define('PIC_NUM_IS_TOO_MORE','0x1000003');//图片数目查过预设的数目
define('ERROR_SELECTED_BG_ID','0x1000004');//选中的背景图有误
define('NO_BIND_ID', '0x1100005');//没有绑定的第三方节点
define('NO_UI_NAME','0x1000005');//没有UI名称
define('NO_UI_TYPE','0x1000006');//没有UI类型
define('NO_UNIQUEID','0x1000007');//没有标识
define('UNIQUEID_HAS_EXISTS','0x1000008');//标识已经存在
define('NO_TYPE_NAME','0x1000009');//没有类型名称
define('NO_ATTR_NAME','0x1000010');//没有属性名称
define('NO_ATTR_TYPE','0x1000011');//没有属性类型
define('DEFAULT_VALUE_OVER','0x1000012');//默认值超出范围
define('THIS_UI_UNIQUEID_HAS_EXISTS','0x1000013');//该UI下已经存在该标识
define('NO_ROLE_ID','0x1000014');//未选择角色
define('NO_GROUP','0x1000015');//未选择分组
define('BIND_CONTENT_INVALID', '0x1000016');//不能绑定非内容模块
define('THIS_UI_GROUP_ROLE_HAS_EXISTS', '0x1000017');//该UI下的分组下的该角色已经存在该属性
define('NO_UI_ID', '0x1000018');//未传ui的id
define('NOT_EXISTS_UI', '0x1000019');//该UI不存在
define('NOT_EXISTS_ATTR_IN_UI', '0x1000020');//该ui下不存在属性
define('USER_NOT_EXISTS', '0x1000021');//用户不存在
define('THIS_UI_ALREADY_HAS_ATTR', '0x1000022');//该UI已经存在属性
define('THIS_UI_NOT_HAS_ATTR', '0x1000023');//该UI不存在属性
define('NO_ATTR_VALUE', '0x1000024');//没有属性的值
define('THIS_MODULE_NOT_BIND_LIST_UI', '0x1000025');//该模块未绑定LIST_UI
define('NO_MAIN_UI_ID', '0x1000026');//没有MainUI的ID
define('NO_SELECT_SET_VALUE_TYPE', '0x1000027');//未选择设置值的方式
define('ATTR_IDS_ERROR', '0x1000028');//未选取后台属性
define('NO_HOME_BG', '0x1000029');//首页背景不存在
define('SOLID_NOT_EXISTS', '0x1000030');//固话模块不存在
define('YOU_SHOULD_CREATE_TEAM', '0x1000031');//请先在会员里面创建群组才能使用此功能
define('DELETE_FAIL', '0x1000032');//删除失败
define('NO_COND', '0x1000033');//没有查询条件
define('DATA_URL_NULL', '0x1000034');//data_url不能为空
define('APP_ID_NULL', '0x1000035');//app_id不能为空
define('MODULE_ID_NULL', '0x1000036');//module_id不能为空
define('TYPE_WRONG','0x1000037');//TYPE错误
define('NO_FRAME_MARK', '0x1000038');//没有框架标识
define('NO_TPL_BODY_MARK', '0x1000039');//没有正文模板标识
define('NO_COLUMN_ID', '0x1000040');//没有栏目id
define('THIS_MOUDLE_NOT_EXIST', '0x1000041');//此模块不存在
define('BODY_TPL_NOT_EXIST', '0x1000042');//正文模板不存在
define('BIND_UPDATE_FAIL','0x1000043');//百姓网数据bind失败
define('NO_TPL_UNIQUEID','0x1000044');//没有正文模板标识
define('TOKEN_VALIDATE_FAIL','0x1000045');//aceescc_token验证失败
define('NO_DATA','0x1000046');//没有数据
define('NO_EMAIL','0x1000047');//没有邮箱
define('YOU_HAVE_SUBMIT_APPLY','0x1000048');//您已经提交了申请
define('PUSH_FAIL','0x1000049');//推送失败
define('YOU_SHOULD_CREATE_COMMUNITY_FIRST','0x1000050');//请先创建社区再选择此模块
define('NO_REPORT_CONTENT','0x1000051');//举报内容不能为空
define('NO_DEVICE_TOKEN','0x1000052');//没有设备号
define('REPORT_MEMBERID_ERROR','0x1000053');//举报时memberId 不在对应的应用下
define('NO_MODEL_INFO','0x1000054');//没有设备信息
define('CLIENT_TYPE_WRONG','0x1000055');//客户端类型错误
define('NO_SYSTEM_INFO','0x1000056');//没有系统信息
define('APP_ID_WRONG','0x1000057');//app_id错误
define('PAY_LOG_ERROR','0x1000058');//付款日志出错
define('YOU_HAVE_NOT_APPLY','0x1000059');//您还未提交申请
define('YOU_CAN_NOT_RE_SUBMIT_APPLY','0x1000060');//您不能重新提交申请
define('NO_GUID','0x1000061');//没有guid
define('YOU_HAVE_NOT_THIS_COMP','0x1000062');//您没有此组件
define('YOU_SELECTED_LISTUI_ERROR','0x1000063');//您选择的listUI有误
define('NO_SELECT_DATA_SOURCE','0x1000064');//您未选择数据源
define('YOU_HAVE_NOT_THIS_DATA_SOURCE','0x1000065');//您没有数据源
define('IM_IS_BLACK','0x1000066');//群组是黑名单
define('SEEKHELP_IS_BLACK','0x1000067');//社区是黑名单
define('NO_COMP_ID','0x1000068');//没有组件ID
define('COMP_NOT_EXTSTS','0x1000069');//组件不存在
define('THIS_COMP_NOT_BIND_LIST_UI','0x1000070');//组件未绑定listUI
define('THIS_COMP_HAS_SELECTED','0x1000071');//该组件已经选取
define('COMP_ID_FEI_FA','0x1000072');//组件ID不合法
define('NO_APP_NAME','0x1000073');//没有应用名称
define('WEIGHT_ERROR','0x1000074');//权重设置有误
define('THIS_COMP_HAS_USED','0x1000075');//该组件已经被使用
define('NO_DOMAIN','0x1000076');//没有域名
define('NO_API_NAME','0x1000077');//没有接口名称
define('NO_URL','0x1000078');//没有链接
define('URL_NOT_IN_WHITE','0x1000079');//域名不在白名单内
define('NO_COND_TYPE','0x1000080');//域名不在白名单内
define('NO_SUPERSCRIPT_NAME','0x1000081');//没有角标名称
define('SUPERSCRIPT_ID_FEI_FA','0x1000082');//角标ID非法
define('NO_SUPERSCRIPT_ID','0x1000083');//没有角标ID
define('SUPERSCRIPT_NOT_EXIST','0x1000084');//角标不存在
define('HAS_SEEKHELP','0x1000085');//已经绑定微社区
define('CORNER_NUM_IS_OVER','0x1000086');//角标个数已经超过限制
define('NO_SELECT_IMG','0x1000087');//未选择图片
define('NO_SELECT_IMG_TYPE','0x1000088');//未选择图片类型
define('IMG_ERROR','0x1000089');//图片有误
define('NO_SHOW_TYPE','0x1000090');//没有显示类型
define('NO_USE_CORNER','0x1000091');//未使用角标
define('CORNER_IS_USE','0x1000092');//未使用角标
