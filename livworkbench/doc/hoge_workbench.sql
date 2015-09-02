-- phpMyAdmin SQL Dump
-- version 2.11.9
-- http://www.phpmyadmin.net
--
-- 主机: 10.0.1.80
-- 生成日期: 2011 年 10 月 11 日 17:23
-- 服务器版本: 5.5.9
-- PHP 版本: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `hoge_workbench`
--

-- --------------------------------------------------------

--
-- 表的结构 `liv_admin`
--

CREATE TABLE IF NOT EXISTS `liv_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '关联用户',
  `admin_group_id` int(10) NOT NULL DEFAULT '3' COMMENT '所属分组',
  `user_name` char(30) NOT NULL COMMENT '帐号',
  `password` char(32) NOT NULL COMMENT '密码',
  `salt` char(6) NOT NULL COMMENT '密码干扰符',
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员表';

--
-- 导出表中的数据 `liv_admin`
--

INSERT INTO `liv_admin` (`id`, `user_id`, `admin_group_id`, `user_name`, `password`, `salt`, `create_time`) VALUES
(1, 0, 1, 'hogesoft', 'd351ca3c8a41720a31a1c253b5aa8a98', 'Y%gNd!', 0),
(2, 0, 3, 'admin', 'e57f5a9834d38d0d525376e1fcb6b1f2', 'GRF#94', 1309827979);

-- --------------------------------------------------------

--
-- 表的结构 `liv_admin_group`
--

CREATE TABLE IF NOT EXISTS `liv_admin_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL COMMENT '名称',
  `brief` varchar(255) NOT NULL,
  `group_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户组类型， 0 － 默认， 1 － 系统维护，2 － 管理员',
  `order_id` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员分组表';

--
-- 导出表中的数据 `liv_admin_group`
--

INSERT INTO `liv_admin_group` (`id`, `name`, `brief`, `group_type`, `order_id`, `create_time`) VALUES
(1, '系统维护', '', 1, 1, 1309490760),
(2, '编辑', '', 3, 0, 1309492179),
(3, '管理员', '', 2, 0, 1309492192);

-- --------------------------------------------------------

--
-- 表的结构 `liv_applications`
--

CREATE TABLE IF NOT EXISTS `liv_applications` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `softvar` varchar(30) NOT NULL COMMENT '软件标识',
  `name` char(30) NOT NULL COMMENT '名称',
  `father_id` int(10) NOT NULL DEFAULT '0' COMMENT '上级系统',
  `brief` varchar(600) NOT NULL COMMENT '描述',
  `logo` varchar(120) NOT NULL COMMENT 'logo',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建/安装时间',
  `order_id` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `host` varchar(120) NOT NULL COMMENT '主机地址',
  `port` smallint(4) NOT NULL DEFAULT '80' COMMENT '端口',
  `dir` varchar(60) NOT NULL COMMENT '目录',
  `token` char(32) NOT NULL COMMENT '操作权限token',
  PRIMARY KEY (`id`),
  UNIQUE KEY `softvar` (`softvar`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='子系统配置表';

--
-- 导出表中的数据 `liv_applications`
--

INSERT INTO `liv_applications` (`id`, `softvar`, `name`, `father_id`, `brief`, `logo`, `create_time`, `order_id`, `host`, `port`, `dir`, `token`) VALUES
(2, 'livcms', '内容管理', 0, '', '', 1307612046, 0, 'vapi.thmz.com', 80, 'admin_api/livcms/', 'e10adc3949ba59abbe56e057f20f883e'),
(6, 'shorturl', '短URL', 0, '', 'shorturl', 1306484596, 0, 'vapi.thmz.com', 80, 'admin_api/cp_shorturl/', '8sdhu9a7sdASDSiSUDs9SwiU7sGF'),
(8, 'liv_mms', '网台系统', 0, '', '', 1311824570, -1, 'vapi.thmz.com', 80, 'api/liv_mms/admin/', '8sdhu9a7sdASDSiSUDs9SwiU7sGF'),
(10, 'adv', '广告', 0, '', 'adv', 1313983514, 0, 'vapi.thmz.com', 80, 'api/adv/admin/', '');

-- --------------------------------------------------------

--
-- 表的结构 `liv_authorize_op`
--

CREATE TABLE IF NOT EXISTS `liv_authorize_op` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '操作名称',
  `brief` varchar(60) DEFAULT NULL COMMENT '操作描述',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- 导出表中的数据 `liv_authorize_op`
--

INSERT INTO `liv_authorize_op` (`id`, `name`, `brief`) VALUES
(1, 'show', '查看'),
(2, 'create', '添加'),
(3, 'update', '更新'),
(4, 'delete', '删除'),
(5, 'audit', '审核'),
(6, 'recommend', '推荐');

-- --------------------------------------------------------

--
-- 表的结构 `liv_log`
--

CREATE TABLE IF NOT EXISTS `liv_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `content` text NOT NULL COMMENT '日志内容',
  `script_name` varchar(200) NOT NULL COMMENT '脚本名称',
  `type` varchar(20) NOT NULL COMMENT '类型',
  `group_type` tinyint(1) NOT NULL COMMENT '用户角色',
  `admin_id` int(10) NOT NULL COMMENT '用户id',
  `user_name` varchar(30) NOT NULL COMMENT '用户名',
  `ip` varchar(20) NOT NULL COMMENT '操作ip',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '日志时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='操作日志表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_modules`
--

CREATE TABLE IF NOT EXISTS `liv_modules` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `icon` varchar(30) NOT NULL COMMENT 'icon图标',
  `application_id` int(10) NOT NULL COMMENT '子系统id',
  `brief` varchar(400) NOT NULL,
  `host` varchar(120) NOT NULL,
  `dir` varchar(60) NOT NULL,
  `file_name` varchar(30) NOT NULL COMMENT '文件名',
  `file_type` char(6) NOT NULL DEFAULT '.php',
  `func_name` varchar(60) NOT NULL COMMENT '方法名',
  `primary_key` varchar(30) NOT NULL DEFAULT 'id' COMMENT '主键字段',
  `paras` varchar(2000) NOT NULL COMMENT '参数',
  `request_type` enum('post','get','ajax','other') NOT NULL COMMENT '请求类型',
  `return_var` varchar(30) NOT NULL COMMENT '返回变量',
  `url` varchar(150) NOT NULL COMMENT '外部链接',
  `attr` varchar(200) NOT NULL DEFAULT 'target:mainwin' COMMENT '窗口属性，如新窗口，隐藏菜单等',
  `template` varchar(30) NOT NULL COMMENT '模板名称',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型0 - 默认， 1-纯操作',
  `fatherid` int(10) NOT NULL DEFAULT '0' COMMENT '父级id',
  `is_pages` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否按页读取',
  `return_type` enum('str','json','xml') NOT NULL DEFAULT 'json' COMMENT '返回值类型',
  `page_count` smallint(2) NOT NULL DEFAULT '0' COMMENT '每页显示条数',
  `order_id` int(10) NOT NULL DEFAULT '0',
  `create_time` int(10) NOT NULL,
  `token` char(32) NOT NULL,
  `settings` text NOT NULL COMMENT '设置',
  `form_set` text NOT NULL COMMENT '表单设置',
  `is_log` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否记录日志',
  `relate_molude_id` int(10) NOT NULL,
  `menu_pos` tinyint(1) NOT NULL DEFAULT '0' COMMENT '菜单位置',
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='功能模块表';

--
-- 导出表中的数据 `liv_modules`
--

INSERT INTO `liv_modules` (`id`, `name`, `icon`, `application_id`, `brief`, `host`, `dir`, `file_name`, `file_type`, `func_name`, `primary_key`, `paras`, `request_type`, `return_var`, `url`, `attr`, `template`, `type`, `fatherid`, `is_pages`, `return_type`, `page_count`, `order_id`, `create_time`, `token`, `settings`, `form_set`, `is_log`, `relate_molude_id`, `menu_pos`) VALUES
(12, '短URL', 'nav_shorturl', 6, '短URL管理', '', '', 'shorturl', '.php', 'show', 'id', '', 'post', '', '', 'target:mainwin', 'list', 0, 0, 1, 'json', 20, 0, 1306484670, '', 'a:13:{s:7:"primary";s:2:"id";s:8:"canorder";a:2:{s:10:"date_added";s:10:"date_added";s:11:"click_count";s:11:"click_count";}s:4:"show";a:4:{s:3:"url";s:3:"url";s:10:"date_added";s:10:"date_added";s:11:"click_count";s:11:"click_count";s:8:"shorturl";s:8:"shorturl";}s:10:"show_title";a:7:{s:2:"id";s:0:"";s:3:"url";s:9:"原始URL";s:4:"code";s:0:"";s:5:"alias";s:0:"";s:10:"date_added";s:12:"创建时间";s:11:"click_count";s:12:"点击次数";s:8:"shorturl";s:9:"映射URL";}s:11:"show_append";a:7:{s:2:"id";s:0:"";s:3:"url";s:0:"";s:4:"code";s:0:"";s:5:"alias";s:0:"";s:10:"date_added";s:0:"";s:11:"click_count";s:0:"";s:8:"shorturl";s:0:"";}s:5:"width";a:7:{s:2:"id";s:0:"";s:3:"url";s:2:"60";s:4:"code";s:0:"";s:5:"alias";s:0:"";s:10:"date_added";s:0:"";s:11:"click_count";s:0:"";s:8:"shorturl";s:0:"";}s:5:"title";N;s:5:"brief";s:12:"短URL管理";s:3:"pic";N;s:10:"cancommend";a:7:{s:2:"id";s:0:"";s:3:"url";s:0:"";s:4:"code";s:0:"";s:5:"alias";s:0:"";s:10:"date_added";s:0:"";s:11:"click_count";s:0:"";s:8:"shorturl";s:0:"";}s:4:"time";N;s:4:"link";s:3:"url";s:5:"order";a:7:{s:2:"id";s:1:"1";s:3:"url";s:1:"2";s:4:"code";s:1:"3";s:5:"alias";s:1:"4";s:10:"date_added";s:1:"5";s:11:"click_count";s:1:"6";s:8:"shorturl";s:1:"7";}}', '', 1, 0, 0);
INSERT INTO `liv_modules` (`id`, `name`, `icon`, `application_id`, `brief`, `host`, `dir`, `file_name`, `file_type`, `func_name`, `primary_key`, `paras`, `request_type`, `return_var`, `url`, `attr`, `template`, `type`, `fatherid`, `is_pages`, `return_type`, `page_count`, `order_id`, `create_time`, `token`, `settings`, `form_set`, `is_log`, `relate_molude_id`, `menu_pos`) VALUES
(31, '视频集合', 'nav_video_b', 8, '', '', '', 'vod_collect', '.php', 'show', '', '', 'post', '', '', 'target:mainwin', 'vod_collect_list', 0, 39, 1, 'json', 10, 0, 1315381943, '', '', '', 1, 33, 0),
(14, '频道管理', 'nav_mmspd', 8, '', '', '', 'channel', '.php', 'show', 'id', '', 'post', 'list', '', 'target:mainwin', 'channel_list', 0, 38, 0, 'json', 10, 0, 1311824697, '', 'a:13:{s:7:"primary";s:2:"id";s:8:"canorder";N;s:4:"show";a:9:{s:4:"code";s:4:"code";s:4:"name";s:4:"name";s:9:"save_time";s:9:"save_time";s:10:"live_delay";s:10:"live_delay";s:12:"stream_state";s:12:"stream_state";s:3:"drm";s:3:"drm";s:2:"ip";s:2:"ip";s:6:"status";s:6:"status";s:3:"img";s:3:"img";}s:10:"show_title";a:22:{s:2:"id";s:0:"";s:4:"logo";s:6:"台标";s:4:"code";s:6:"台号";s:4:"name";s:12:"频道名称";s:5:"ch_id";s:0:"";s:9:"save_time";s:14:"回看(小时)";s:10:"live_delay";s:20:"延时时间(分钟)";s:12:"stream_state";s:15:"信号流状态";s:3:"drm";s:3:"drm";s:5:"state";s:0:"";s:9:"stream_id";s:0:"";s:7:"up_name";s:0:"";s:6:"output";s:9:"输出流";s:9:"server_id";s:0:"";s:6:"is_del";s:0:"";s:11:"create_time";s:0:"";s:11:"update_time";s:0:"";s:2:"ip";s:11:"创建者ip";s:6:"status";s:12:"频道发布";s:3:"img";s:6:"台标";s:6:"larger";s:0:"";s:5:"small";s:0:"";}s:11:"show_append";a:22:{s:2:"id";s:0:"";s:4:"logo";s:0:"";s:4:"code";s:0:"";s:4:"name";s:0:"";s:5:"ch_id";s:0:"";s:9:"save_time";s:0:"";s:10:"live_delay";s:0:"";s:12:"stream_state";s:0:"";s:3:"drm";s:0:"";s:5:"state";s:0:"";s:9:"stream_id";s:0:"";s:7:"up_name";s:0:"";s:6:"output";s:0:"";s:9:"server_id";s:0:"";s:6:"is_del";s:0:"";s:11:"create_time";s:0:"";s:11:"update_time";s:0:"";s:2:"ip";s:0:"";s:6:"status";s:0:"";s:3:"img";s:0:"";s:6:"larger";s:0:"";s:5:"small";s:0:"";}s:5:"width";a:22:{s:2:"id";s:0:"";s:4:"logo";s:0:"";s:4:"code";s:0:"";s:4:"name";s:0:"";s:5:"ch_id";s:0:"";s:9:"save_time";s:0:"";s:10:"live_delay";s:0:"";s:12:"stream_state";s:0:"";s:3:"drm";s:0:"";s:5:"state";s:0:"";s:9:"stream_id";s:0:"";s:7:"up_name";s:0:"";s:6:"output";s:0:"";s:9:"server_id";s:0:"";s:6:"is_del";s:0:"";s:11:"create_time";s:0:"";s:11:"update_time";s:0:"";s:2:"ip";s:0:"";s:6:"status";s:0:"";s:3:"img";s:0:"";s:6:"larger";s:0:"";s:5:"small";s:0:"";}s:5:"title";N;s:5:"brief";s:0:"";s:3:"pic";a:1:{s:3:"img";s:3:"img";}s:10:"cancommend";a:22:{s:2:"id";s:0:"";s:4:"logo";s:0:"";s:4:"code";s:0:"";s:4:"name";s:0:"";s:5:"ch_id";s:0:"";s:9:"save_time";s:0:"";s:10:"live_delay";s:0:"";s:12:"stream_state";s:0:"";s:3:"drm";s:0:"";s:5:"state";s:0:"";s:9:"stream_id";s:0:"";s:7:"up_name";s:0:"";s:6:"output";s:0:"";s:9:"server_id";s:0:"";s:6:"is_del";s:0:"";s:11:"create_time";s:0:"";s:11:"update_time";s:0:"";s:2:"ip";s:0:"";s:6:"status";s:0:"";s:3:"img";s:0:"";s:6:"larger";s:0:"";s:5:"small";s:0:"";}s:4:"time";N;s:4:"link";N;s:5:"order";a:22:{s:2:"id";s:1:"1";s:4:"logo";s:2:"13";s:4:"code";s:1:"3";s:4:"name";s:1:"4";s:5:"ch_id";s:1:"5";s:9:"save_time";s:1:"6";s:10:"live_delay";s:1:"7";s:12:"stream_state";s:1:"8";s:3:"drm";s:1:"9";s:5:"state";s:1:"5";s:9:"stream_id";s:1:"6";s:7:"up_name";s:2:"12";s:6:"output";s:1:"7";s:9:"server_id";s:1:"8";s:6:"is_del";s:1:"9";s:11:"create_time";s:2:"10";s:11:"update_time";s:2:"11";s:2:"ip";s:2:"12";s:6:"status";s:2:"18";s:3:"img";s:1:"2";s:6:"larger";s:2:"14";s:5:"small";s:2:"15";}}', '', 1, 0, 0),
(20, '视频管理', 'nav_media', 8, 'copyright', '', '', 'vod_upload', '.php', 'show', 'id', '', 'post', '', '', 'target:mainwin', 'vodinfo_list', 0, 39, 1, 'json', 12, 0, 1312786458, '', 'a:13:{s:7:"primary";s:2:"id";s:8:"canorder";a:7:{s:2:"id";s:2:"id";s:3:"img";s:3:"img";s:5:"title";s:5:"title";s:7:"bitrate";s:7:"bitrate";s:11:"vod_sort_id";s:11:"vod_sort_id";s:6:"status";s:6:"status";s:6:"author";s:6:"author";}s:4:"show";a:6:{s:3:"img";s:3:"img";s:5:"title";s:5:"title";s:7:"bitrate";s:7:"bitrate";s:11:"vod_sort_id";s:11:"vod_sort_id";s:6:"status";s:6:"status";s:6:"author";s:6:"author";}s:10:"show_title";a:30:{s:2:"id";s:2:"ID";s:14:"video_order_id";s:0:"";s:3:"img";s:9:"缩略图";s:5:"title";s:6:"标题";s:7:"bitrate";s:6:"码流";s:11:"vod_sort_id";s:6:"分类";s:6:"status";s:6:"状态";s:6:"author";s:16:"添加人/时间";s:9:"addperson";s:0:"";s:7:"comment";s:6:"评论";s:6:"source";s:0:"";s:7:"audiohz";s:6:"音频";s:9:"copyright";s:6:"版权";s:8:"subtitle";s:0:"";s:6:"height";s:0:"";s:5:"start";s:0:"";s:8:"duration";s:6:"时长";s:5:"width";s:0:"";s:5:"vodid";s:13:"视频id&#39;";s:8:"keywords";s:0:"";s:4:"type";s:0:"";s:8:"transize";s:15:"已转码大小";s:9:"totalsize";s:15:"视频总大小";s:5:"audit";s:6:"审核";s:4:"flag";s:0:"";s:8:"collects";s:0:"";s:11:"create_time";s:13:"添加/时间";s:11:"update_time";s:0:"";s:2:"ip";s:0:"";s:11:"vod_leixing";s:0:"";}s:11:"show_append";a:30:{s:2:"id";s:0:"";s:14:"video_order_id";s:0:"";s:3:"img";s:58:"&nbsp;&nbsp;<span onclick="alert({$vodid});">预览</span>";s:5:"title";s:110:"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span onclick="alert(''发布'');" ><b  style="color:red;">发布</b></span>";s:7:"bitrate";s:0:"";s:11:"vod_sort_id";s:0:"";s:6:"status";s:0:"";s:6:"author";s:32:"<br/><span>{$create_time}</span>";s:9:"addperson";s:0:"";s:7:"comment";s:0:"";s:6:"source";s:0:"";s:7:"audiohz";s:0:"";s:9:"copyright";s:0:"";s:8:"subtitle";s:0:"";s:6:"height";s:0:"";s:5:"start";s:0:"";s:8:"duration";s:0:"";s:5:"width";s:0:"";s:5:"vodid";s:0:"";s:8:"keywords";s:0:"";s:4:"type";s:0:"";s:8:"transize";s:0:"";s:9:"totalsize";s:0:"";s:5:"audit";s:0:"";s:4:"flag";s:0:"";s:8:"collects";s:0:"";s:11:"create_time";s:0:"";s:11:"update_time";s:0:"";s:2:"ip";s:0:"";s:11:"vod_leixing";s:0:"";}s:5:"width";a:30:{s:2:"id";s:0:"";s:14:"video_order_id";s:0:"";s:3:"img";s:0:"";s:5:"title";s:0:"";s:7:"bitrate";s:0:"";s:11:"vod_sort_id";s:0:"";s:6:"status";s:0:"";s:6:"author";s:0:"";s:9:"addperson";s:0:"";s:7:"comment";s:0:"";s:6:"source";s:0:"";s:7:"audiohz";s:0:"";s:9:"copyright";s:0:"";s:8:"subtitle";s:0:"";s:6:"height";s:0:"";s:5:"start";s:0:"";s:8:"duration";s:0:"";s:5:"width";s:0:"";s:5:"vodid";s:0:"";s:8:"keywords";s:0:"";s:4:"type";s:0:"";s:8:"transize";s:0:"";s:9:"totalsize";s:0:"";s:5:"audit";s:0:"";s:4:"flag";s:0:"";s:8:"collects";s:0:"";s:11:"create_time";s:0:"";s:11:"update_time";s:0:"";s:2:"ip";s:0:"";s:11:"vod_leixing";s:0:"";}s:5:"title";s:5:"title";s:5:"brief";s:9:"copyright";s:3:"pic";a:1:{s:3:"img";s:3:"img";}s:10:"cancommend";a:30:{s:2:"id";s:0:"";s:14:"video_order_id";s:0:"";s:3:"img";s:0:"";s:5:"title";s:0:"";s:7:"bitrate";s:0:"";s:11:"vod_sort_id";s:0:"";s:6:"status";s:0:"";s:6:"author";s:0:"";s:9:"addperson";s:0:"";s:7:"comment";s:0:"";s:6:"source";s:0:"";s:7:"audiohz";s:0:"";s:9:"copyright";s:0:"";s:8:"subtitle";s:0:"";s:6:"height";s:0:"";s:5:"start";s:0:"";s:8:"duration";s:0:"";s:5:"width";s:0:"";s:5:"vodid";s:0:"";s:8:"keywords";s:0:"";s:4:"type";s:0:"";s:8:"transize";s:0:"";s:9:"totalsize";s:0:"";s:5:"audit";s:0:"";s:4:"flag";s:0:"";s:8:"collects";s:0:"";s:11:"create_time";s:0:"";s:11:"update_time";s:0:"";s:2:"ip";s:0:"";s:11:"vod_leixing";s:0:"";}s:4:"time";N;s:4:"link";N;s:5:"order";a:30:{s:2:"id";s:1:"1";s:14:"video_order_id";s:1:"2";s:3:"img";s:1:"6";s:5:"title";s:2:"10";s:7:"bitrate";s:2:"13";s:11:"vod_sort_id";s:1:"2";s:6:"status";s:2:"12";s:6:"author";s:1:"5";s:9:"addperson";s:1:"8";s:7:"comment";s:1:"2";s:6:"source";s:2:"10";s:7:"audiohz";s:1:"3";s:9:"copyright";s:1:"4";s:8:"subtitle";s:2:"11";s:6:"height";s:1:"7";s:5:"start";s:1:"8";s:8:"duration";s:1:"9";s:5:"width";s:2:"11";s:5:"vodid";s:2:"14";s:8:"keywords";s:2:"17";s:4:"type";s:2:"16";s:8:"transize";s:2:"15";s:9:"totalsize";s:2:"16";s:5:"audit";s:2:"17";s:4:"flag";s:2:"21";s:8:"collects";s:2:"25";s:11:"create_time";s:2:"18";s:11:"update_time";s:2:"19";s:2:"ip";s:2:"20";s:11:"vod_leixing";s:2:"23";}}', '', 1, 0, 0),
(22, '节目单管理', 'nav_microblogging', 8, '', '', '', 'program', '.php', 'show', '', 'channel_id', 'post', '', '', 'target:mainwin', 'program_list', 0, 38, 0, 'json', 0, 1, 1312957067, '', '', '', 1, 0, 0);
INSERT INTO `liv_modules` (`id`, `name`, `icon`, `application_id`, `brief`, `host`, `dir`, `file_name`, `file_type`, `func_name`, `primary_key`, `paras`, `request_type`, `return_var`, `url`, `attr`, `template`, `type`, `fatherid`, `is_pages`, `return_type`, `page_count`, `order_id`, `create_time`, `token`, `settings`, `form_set`, `is_log`, `relate_molude_id`, `menu_pos`) VALUES
(21, '分类管理', 'nav_sort', 8, '', '', '', 'vod_sort', '.php', 'show', '', '', 'post', '', '', 'target:mainwin', 'vod_sort_list', 0, 0, 1, 'json', 0, 0, 1312945539, '', 'a:13:{s:7:"primary";s:2:"id";s:8:"canorder";a:3:{s:2:"id";s:2:"id";s:9:"sort_name";s:9:"sort_name";s:6:"father";s:6:"father";}s:4:"show";a:3:{s:2:"id";s:2:"id";s:9:"sort_name";s:9:"sort_name";s:6:"father";s:6:"father";}s:10:"show_title";a:8:{s:2:"id";s:2:"ID";s:9:"sort_name";s:12:"视频类名";s:6:"father";s:12:"所属类型";s:5:"count";s:0:"";s:13:"collect_count";s:0:"";s:11:"create_time";s:12:"创建时间";s:11:"update_time";s:0:"";s:2:"ip";s:0:"";}s:11:"show_append";a:8:{s:2:"id";s:0:"";s:9:"sort_name";s:0:"";s:6:"father";s:0:"";s:5:"count";s:0:"";s:13:"collect_count";s:0:"";s:11:"create_time";s:0:"";s:11:"update_time";s:0:"";s:2:"ip";s:0:"";}s:5:"width";a:8:{s:2:"id";s:0:"";s:9:"sort_name";s:0:"";s:6:"father";s:0:"";s:5:"count";s:0:"";s:13:"collect_count";s:0:"";s:11:"create_time";s:0:"";s:11:"update_time";s:0:"";s:2:"ip";s:0:"";}s:5:"title";N;s:5:"brief";s:0:"";s:3:"pic";N;s:10:"cancommend";a:8:{s:2:"id";s:0:"";s:9:"sort_name";s:0:"";s:6:"father";s:0:"";s:5:"count";s:0:"";s:13:"collect_count";s:0:"";s:11:"create_time";s:0:"";s:11:"update_time";s:0:"";s:2:"ip";s:0:"";}s:4:"time";N;s:4:"link";N;s:5:"order";a:8:{s:2:"id";s:1:"1";s:9:"sort_name";s:1:"2";s:6:"father";s:1:"3";s:5:"count";s:1:"4";s:13:"collect_count";s:1:"5";s:11:"create_time";s:1:"4";s:11:"update_time";s:1:"5";s:2:"ip";s:1:"6";}}', '', 1, 0, 0),
(23, '分组管理', 'nav_ad_group', 10, '广告分组', '', '', 'adv_group', '.php', 'show', 'id', '', 'post', '', '', 'target:mainwin', 'group_list', 0, 0, 1, 'json', 10, 0, 1313983643, '', 'a:13:{s:7:"primary";s:2:"id";s:8:"canorder";a:1:{s:2:"id";s:2:"id";}s:4:"show";a:6:{s:2:"id";s:2:"id";s:4:"name";s:4:"name";s:4:"flag";s:4:"flag";s:5:"brief";s:5:"brief";s:6:"is_use";s:6:"is_use";s:9:"user_name";s:9:"user_name";}s:10:"show_title";a:9:{s:2:"id";s:2:"ID";s:4:"name";s:15:"客户端名称";s:4:"flag";s:6:"标志";s:5:"brief";s:12:"分组描述";s:6:"is_use";s:6:"启用";s:3:"pos";s:0:"";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:9:"user_name";s:12:"创建用户";}s:11:"show_append";a:9:{s:2:"id";s:0:"";s:4:"name";s:0:"";s:4:"flag";s:0:"";s:5:"brief";s:0:"";s:6:"is_use";s:0:"";s:3:"pos";s:0:"";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:9:"user_name";s:0:"";}s:5:"width";a:9:{s:2:"id";s:0:"";s:4:"name";s:0:"";s:4:"flag";s:0:"";s:5:"brief";s:0:"";s:6:"is_use";s:0:"";s:3:"pos";s:0:"";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:9:"user_name";s:0:"";}s:5:"title";N;s:5:"brief";s:12:"广告分组";s:3:"pic";N;s:10:"cancommend";a:9:{s:2:"id";s:0:"";s:4:"name";s:0:"";s:4:"flag";s:0:"";s:5:"brief";s:0:"";s:6:"is_use";s:0:"";s:3:"pos";s:0:"";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:9:"user_name";s:0:"";}s:4:"time";N;s:4:"link";N;s:5:"order";a:9:{s:2:"id";s:1:"1";s:4:"name";s:1:"2";s:4:"flag";s:1:"3";s:5:"brief";s:1:"4";s:6:"is_use";s:1:"3";s:3:"pos";s:1:"4";s:2:"ip";s:1:"7";s:11:"create_time";s:1:"8";s:9:"user_name";s:1:"9";}}', '', 1, 0, 0),
(25, '广告效果', 'nav_effect', 10, '广告效果', '', '', 'adv_animation', '.php', 'show', 'id', '', 'post', '', '', 'target:mainwin', 'animation_list', 0, 0, 1, 'json', 10, 0, 1314605086, '', 'a:13:{s:7:"primary";s:2:"id";s:8:"canorder";N;s:4:"show";a:4:{s:4:"name";s:4:"name";s:9:"developer";s:9:"developer";s:4:"cost";s:4:"cost";s:6:"is_use";s:6:"is_use";}s:10:"show_title";a:9:{s:2:"id";s:0:"";s:4:"name";s:6:"名称";s:9:"developer";s:9:"开发者";s:4:"para";s:6:"参数";s:4:"cost";s:6:"价格";s:6:"is_use";s:6:"启用";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:9:"user_name";s:0:"";}s:11:"show_append";a:9:{s:2:"id";s:0:"";s:4:"name";s:0:"";s:9:"developer";s:0:"";s:4:"para";s:0:"";s:4:"cost";s:0:"";s:6:"is_use";s:0:"";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:9:"user_name";s:0:"";}s:5:"width";a:9:{s:2:"id";s:0:"";s:4:"name";s:0:"";s:9:"developer";s:0:"";s:4:"para";s:0:"";s:4:"cost";s:0:"";s:6:"is_use";s:0:"";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:9:"user_name";s:0:"";}s:5:"title";N;s:5:"brief";s:12:"广告效果";s:3:"pic";N;s:10:"cancommend";a:9:{s:2:"id";s:0:"";s:4:"name";s:0:"";s:9:"developer";s:0:"";s:4:"para";s:0:"";s:4:"cost";s:0:"";s:6:"is_use";s:0:"";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:9:"user_name";s:0:"";}s:4:"time";N;s:4:"link";N;s:5:"order";a:9:{s:2:"id";s:1:"1";s:4:"name";s:1:"2";s:9:"developer";s:1:"3";s:4:"para";s:1:"4";s:4:"cost";s:1:"5";s:6:"is_use";s:1:"7";s:2:"ip";s:1:"7";s:11:"create_time";s:1:"8";s:9:"user_name";s:1:"9";}}', '', 1, 0, 0),
(27, '广告位', 'nav_advertising', 10, '', '', '', 'adv_pos', '.php', 'show', 'id', '', 'post', '', '', 'target:mainwin', 'pos_list', 0, 0, 1, 'json', 10, 0, 1314685515, '', 'a:13:{s:7:"primary";s:2:"id";s:8:"canorder";N;s:4:"show";a:10:{s:4:"name";s:4:"name";s:7:"zh_name";s:7:"zh_name";s:6:"is_use";s:6:"is_use";s:8:"ad_count";s:8:"ad_count";s:4:"cost";s:4:"cost";s:10:"group_flag";s:10:"group_flag";s:11:"create_time";s:11:"create_time";s:9:"user_name";s:9:"user_name";s:8:"ani_name";s:8:"ani_name";s:10:"group_name";s:10:"group_name";}s:10:"show_title";a:15:{s:2:"id";s:0:"";s:6:"ani_id";s:0:"";s:4:"name";s:6:"标识";s:7:"zh_name";s:6:"名称";s:6:"is_use";s:6:"启用";s:8:"ad_count";s:12:"广告数目";s:4:"para";s:6:"参数";s:10:"group_used";s:0:"";s:4:"cost";s:6:"价格";s:10:"group_flag";s:12:"分组标识";s:11:"create_time";s:12:"创建时间";s:11:"update_time";s:0:"";s:9:"user_name";s:9:"添加人";s:8:"ani_name";s:6:"效果";s:10:"group_name";s:9:"分组名";}s:11:"show_append";a:15:{s:2:"id";s:0:"";s:6:"ani_id";s:0:"";s:4:"name";s:0:"";s:7:"zh_name";s:0:"";s:6:"is_use";s:0:"";s:8:"ad_count";s:0:"";s:4:"para";s:0:"";s:10:"group_used";s:0:"";s:4:"cost";s:0:"";s:10:"group_flag";s:0:"";s:11:"create_time";s:0:"";s:11:"update_time";s:0:"";s:9:"user_name";s:0:"";s:8:"ani_name";s:0:"";s:10:"group_name";s:0:"";}s:5:"width";a:15:{s:2:"id";s:0:"";s:6:"ani_id";s:0:"";s:4:"name";s:0:"";s:7:"zh_name";s:0:"";s:6:"is_use";s:0:"";s:8:"ad_count";s:0:"";s:4:"para";s:0:"";s:10:"group_used";s:0:"";s:4:"cost";s:0:"";s:10:"group_flag";s:0:"";s:11:"create_time";s:0:"";s:11:"update_time";s:0:"";s:9:"user_name";s:0:"";s:8:"ani_name";s:0:"";s:10:"group_name";s:0:"";}s:5:"title";N;s:5:"brief";s:0:"";s:3:"pic";N;s:10:"cancommend";a:15:{s:2:"id";s:0:"";s:6:"ani_id";s:0:"";s:4:"name";s:0:"";s:7:"zh_name";s:0:"";s:6:"is_use";s:0:"";s:8:"ad_count";s:0:"";s:4:"para";s:0:"";s:10:"group_used";s:0:"";s:4:"cost";s:0:"";s:10:"group_flag";s:0:"";s:11:"create_time";s:0:"";s:11:"update_time";s:0:"";s:9:"user_name";s:0:"";s:8:"ani_name";s:0:"";s:10:"group_name";s:0:"";}s:4:"time";N;s:4:"link";N;s:5:"order";a:15:{s:2:"id";s:1:"1";s:6:"ani_id";s:1:"2";s:4:"name";s:1:"3";s:7:"zh_name";s:1:"4";s:6:"is_use";s:1:"4";s:8:"ad_count";s:1:"5";s:4:"para";s:1:"6";s:10:"group_used";s:1:"8";s:4:"cost";s:1:"7";s:10:"group_flag";s:1:"7";s:11:"create_time";s:1:"9";s:11:"update_time";s:1:"9";s:9:"user_name";s:2:"10";s:8:"ani_name";s:2:"12";s:10:"group_name";s:2:"11";}}', '', 1, 0, 0),
(28, '广告管理', 'nav_strategy', 10, '', '', '', 'adv_policy', '.php', 'show', 'id', '', 'post', '', '', 'target:mainwin', 'policy_list', 0, 0, 1, 'json', 10, 0, 1314685633, '', 'a:13:{s:7:"primary";s:2:"id";s:8:"canorder";N;s:4:"show";a:7:{s:5:"group";s:5:"group";s:10:"start_time";s:10:"start_time";s:8:"end_time";s:8:"end_time";s:4:"cost";s:4:"cost";s:5:"title";s:5:"title";s:4:"name";s:4:"name";s:8:"ani_name";s:8:"ani_name";}s:10:"show_title";a:31:{s:2:"id";s:0:"";s:5:"ad_id";s:0:"";s:6:"pos_id";s:0:"";s:6:"ani_id";s:0:"";s:5:"group";s:9:"发布到";s:10:"start_time";s:12:"开始时间";s:8:"end_time";s:12:"结束时间";s:8:"duration";s:6:"时长";s:4:"cost";s:6:"价格";s:5:"param";s:0:"";s:6:"adcode";s:0:"";s:8:"callback";s:0:"";s:5:"title";s:12:"广告内容";s:5:"brief";s:0:"";s:4:"link";s:0:"";s:4:"type";s:0:"";s:8:"material";s:0:"";s:4:"para";s:0:"";s:7:"user_id";s:0:"";s:9:"user_name";s:0:"";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:4:"name";s:9:"广告位";s:9:"developer";s:0:"";s:6:"is_use";s:0:"";s:8:"ani_name";s:6:"动画";s:7:"zh_name";s:0:"";s:8:"ad_count";s:0:"";s:10:"group_used";s:0:"";s:10:"group_flag";s:0:"";s:11:"update_time";s:0:"";}s:11:"show_append";a:31:{s:2:"id";s:0:"";s:5:"ad_id";s:0:"";s:6:"pos_id";s:0:"";s:6:"ani_id";s:0:"";s:5:"group";s:0:"";s:10:"start_time";s:0:"";s:8:"end_time";s:0:"";s:8:"duration";s:0:"";s:4:"cost";s:0:"";s:5:"param";s:0:"";s:6:"adcode";s:0:"";s:8:"callback";s:0:"";s:5:"title";s:0:"";s:5:"brief";s:0:"";s:4:"link";s:0:"";s:4:"type";s:0:"";s:8:"material";s:0:"";s:4:"para";s:0:"";s:7:"user_id";s:0:"";s:9:"user_name";s:0:"";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:4:"name";s:0:"";s:9:"developer";s:0:"";s:6:"is_use";s:0:"";s:8:"ani_name";s:0:"";s:7:"zh_name";s:0:"";s:8:"ad_count";s:0:"";s:10:"group_used";s:0:"";s:10:"group_flag";s:0:"";s:11:"update_time";s:0:"";}s:5:"width";a:31:{s:2:"id";s:0:"";s:5:"ad_id";s:0:"";s:6:"pos_id";s:0:"";s:6:"ani_id";s:0:"";s:5:"group";s:0:"";s:10:"start_time";s:0:"";s:8:"end_time";s:0:"";s:8:"duration";s:0:"";s:4:"cost";s:0:"";s:5:"param";s:0:"";s:6:"adcode";s:0:"";s:8:"callback";s:0:"";s:5:"title";s:0:"";s:5:"brief";s:0:"";s:4:"link";s:0:"";s:4:"type";s:0:"";s:8:"material";s:0:"";s:4:"para";s:0:"";s:7:"user_id";s:0:"";s:9:"user_name";s:0:"";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:4:"name";s:0:"";s:9:"developer";s:0:"";s:6:"is_use";s:0:"";s:8:"ani_name";s:0:"";s:7:"zh_name";s:0:"";s:8:"ad_count";s:0:"";s:10:"group_used";s:0:"";s:10:"group_flag";s:0:"";s:11:"update_time";s:0:"";}s:5:"title";s:5:"title";s:5:"brief";s:0:"";s:3:"pic";N;s:10:"cancommend";a:31:{s:2:"id";s:0:"";s:5:"ad_id";s:0:"";s:6:"pos_id";s:0:"";s:6:"ani_id";s:0:"";s:5:"group";s:0:"";s:10:"start_time";s:0:"";s:8:"end_time";s:0:"";s:8:"duration";s:0:"";s:4:"cost";s:0:"";s:5:"param";s:0:"";s:6:"adcode";s:0:"";s:8:"callback";s:0:"";s:5:"title";s:0:"";s:5:"brief";s:0:"";s:4:"link";s:0:"";s:4:"type";s:0:"";s:8:"material";s:0:"";s:4:"para";s:0:"";s:7:"user_id";s:0:"";s:9:"user_name";s:0:"";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:4:"name";s:0:"";s:9:"developer";s:0:"";s:6:"is_use";s:0:"";s:8:"ani_name";s:0:"";s:7:"zh_name";s:0:"";s:8:"ad_count";s:0:"";s:10:"group_used";s:0:"";s:10:"group_flag";s:0:"";s:11:"update_time";s:0:"";}s:4:"time";N;s:4:"link";N;s:5:"order";a:31:{s:2:"id";s:1:"1";s:5:"ad_id";s:1:"2";s:6:"pos_id";s:1:"3";s:6:"ani_id";s:1:"4";s:5:"group";s:2:"15";s:10:"start_time";s:1:"5";s:8:"end_time";s:1:"6";s:8:"duration";s:1:"7";s:4:"cost";s:1:"8";s:5:"param";s:1:"9";s:6:"adcode";s:2:"10";s:8:"callback";s:2:"11";s:5:"title";s:2:"12";s:5:"brief";s:2:"13";s:4:"link";s:2:"14";s:4:"type";s:2:"15";s:8:"material";s:2:"16";s:4:"para";s:2:"17";s:7:"user_id";s:2:"18";s:9:"user_name";s:2:"19";s:2:"ip";s:2:"20";s:11:"create_time";s:2:"21";s:4:"name";s:2:"22";s:9:"developer";s:2:"23";s:6:"is_use";s:2:"25";s:8:"ani_name";s:2:"26";s:7:"zh_name";s:2:"27";s:8:"ad_count";s:2:"27";s:10:"group_used";s:2:"29";s:10:"group_flag";s:2:"27";s:11:"update_time";s:2:"29";}}', '', 1, 0, 0),
(33, '查看集合', '', 8, '', '', '', 'vod_look_video', '.php', 'look_video', 'id', '', 'post', '', '', 'target:mainwin', 'vod_collect_video_list', 0, 39, 1, 'json', 10, 0, 1316414595, '', '', '', 1, 31, 1),
(37, '内容管理', '', 10, '测试版', '', '', 'adv_content', '.php', 'show', 'id', '', 'post', '', '', 'target:mainwin', 'content_list', 0, 0, 1, 'json', 10, 0, 1318036108, '', 'a:13:{s:7:"primary";s:2:"id";s:8:"canorder";N;s:4:"show";a:9:{s:2:"id";s:2:"id";s:5:"title";s:5:"title";s:5:"brief";s:5:"brief";s:4:"link";s:4:"link";s:4:"type";s:4:"type";s:8:"material";s:8:"material";s:9:"user_name";s:9:"user_name";s:11:"create_time";s:11:"create_time";s:12:"distribution";s:12:"distribution";}s:10:"show_title";a:12:{s:2:"id";s:8:"内容ID";s:5:"title";s:6:"标题";s:5:"brief";s:6:"描述";s:4:"link";s:6:"链接";s:4:"type";s:6:"类型";s:8:"material";s:6:"素材";s:4:"para";s:0:"";s:7:"user_id";s:0:"";s:9:"user_name";s:6:"用户";s:2:"ip";s:0:"";s:11:"create_time";s:6:"时间";s:12:"distribution";s:9:"已发布";}s:11:"show_append";a:12:{s:2:"id";s:0:"";s:5:"title";s:0:"";s:5:"brief";s:0:"";s:4:"link";s:0:"";s:4:"type";s:0:"";s:8:"material";s:0:"";s:4:"para";s:0:"";s:7:"user_id";s:0:"";s:9:"user_name";s:0:"";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:12:"distribution";s:0:"";}s:5:"width";a:12:{s:2:"id";s:0:"";s:5:"title";s:0:"";s:5:"brief";s:0:"";s:4:"link";s:0:"";s:4:"type";s:0:"";s:8:"material";s:0:"";s:4:"para";s:0:"";s:7:"user_id";s:0:"";s:9:"user_name";s:0:"";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:12:"distribution";s:0:"";}s:5:"title";s:5:"title";s:5:"brief";s:9:"测试版";s:3:"pic";a:1:{s:8:"material";s:8:"material";}s:10:"cancommend";a:12:{s:2:"id";s:0:"";s:5:"title";s:0:"";s:5:"brief";s:0:"";s:4:"link";s:0:"";s:4:"type";s:0:"";s:8:"material";s:0:"";s:4:"para";s:0:"";s:7:"user_id";s:0:"";s:9:"user_name";s:0:"";s:2:"ip";s:0:"";s:11:"create_time";s:0:"";s:12:"distribution";s:0:"";}s:4:"time";N;s:4:"link";N;s:5:"order";a:12:{s:2:"id";s:1:"1";s:5:"title";s:1:"2";s:5:"brief";s:1:"3";s:4:"link";s:1:"4";s:4:"type";s:1:"5";s:8:"material";s:1:"6";s:4:"para";s:1:"7";s:7:"user_id";s:1:"8";s:9:"user_name";s:1:"9";s:2:"ip";s:2:"10";s:11:"create_time";s:2:"11";s:12:"distribution";s:2:"12";}}', '', 1, 0, 0),
(38, '直播管理', 'nav_video', 8, '', '', '', '', '.php', '', '', '', 'post', '', '', 'target:mainwin', '', 0, 0, 0, 'json', 0, 0, 1318223362, '', '', '', 1, 0, 0),
(39, '媒资管理', 'nav_media', 8, '', '', '', '', '.php', '', '', '', 'post', '', '', 'target:mainwin', '', 0, 0, 0, 'json', 0, 0, 1318223973, '', '', '', 1, 0, 0),
(40, '备播文件', '', 8, '', '', '', 'live_backup', '.php', 'show', 'id', '', 'post', 'live_backup_list', '', 'target:mainwin', 'live_backup_list', 0, 38, 1, 'json', 10, 0, 1318294489, '', 'a:13:{s:7:"primary";s:2:"id";s:8:"canorder";N;s:4:"show";a:6:{s:2:"id";s:2:"id";s:5:"title";s:5:"title";s:8:"filename";s:8:"filename";s:5:"brief";s:5:"brief";s:11:"create_time";s:11:"create_time";s:9:"user_name";s:9:"user_name";}s:10:"show_title";a:9:{s:2:"id";s:2:"ID";s:5:"title";s:6:"标题";s:8:"filename";s:9:"文件名";s:7:"newname";s:0:"";s:5:"brief";s:6:"描述";s:4:"sort";s:0:"";s:11:"create_time";s:12:"发布时间";s:9:"user_name";s:9:"用户名";s:2:"ip";s:0:"";}s:11:"show_append";a:9:{s:2:"id";s:0:"";s:5:"title";s:0:"";s:8:"filename";s:0:"";s:7:"newname";s:0:"";s:5:"brief";s:0:"";s:4:"sort";s:0:"";s:11:"create_time";s:0:"";s:9:"user_name";s:0:"";s:2:"ip";s:0:"";}s:5:"width";a:9:{s:2:"id";s:0:"";s:5:"title";s:0:"";s:8:"filename";s:0:"";s:7:"newname";s:0:"";s:5:"brief";s:0:"";s:4:"sort";s:0:"";s:11:"create_time";s:0:"";s:9:"user_name";s:0:"";s:2:"ip";s:0:"";}s:5:"title";s:5:"title";s:5:"brief";s:0:"";s:3:"pic";N;s:10:"cancommend";a:9:{s:2:"id";s:0:"";s:5:"title";s:0:"";s:8:"filename";s:0:"";s:7:"newname";s:0:"";s:5:"brief";s:0:"";s:4:"sort";s:0:"";s:11:"create_time";s:0:"";s:9:"user_name";s:0:"";s:2:"ip";s:0:"";}s:4:"time";N;s:4:"link";N;s:5:"order";a:9:{s:2:"id";s:1:"1";s:5:"title";s:1:"2";s:8:"filename";s:1:"3";s:7:"newname";s:1:"4";s:5:"brief";s:1:"5";s:4:"sort";s:1:"6";s:11:"create_time";s:1:"7";s:9:"user_name";s:1:"8";s:2:"ip";s:1:"9";}}', '', 1, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `liv_module_append`
--

CREATE TABLE IF NOT EXISTS `liv_module_append` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `module_id` int(10) NOT NULL COMMENT '模块id',
  `module_op_id` int(10) NOT NULL DEFAULT '0',
  `op` char(20) NOT NULL,
  `host` varchar(120) NOT NULL,
  `dir` varchar(60) NOT NULL,
  `token` char(32) NOT NULL,
  `file_name` varchar(60) NOT NULL COMMENT '模块文件名',
  `file_type` char(6) NOT NULL DEFAULT '.php',
  `func_name` varchar(60) NOT NULL DEFAULT 'show' COMMENT '模块方法',
  `paras` varchar(200) NOT NULL COMMENT '参数',
  `return_type` enum('str','json','xml') NOT NULL DEFAULT 'json' COMMENT '返回值类型',
  `return_var` varchar(30) NOT NULL COMMENT '返回变量',
  `count` int(10) NOT NULL DEFAULT '0' COMMENT '获取数据条数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='模块调用数据表';

--
-- 导出表中的数据 `liv_module_append`
--

INSERT INTO `liv_module_append` (`id`, `module_id`, `module_op_id`, `op`, `host`, `dir`, `token`, `file_name`, `file_type`, `func_name`, `paras`, `return_type`, `return_var`, `count`) VALUES
(1, 13, 19, 'form', '', '', '', 'tuji_sort', '.php', 'show2', '', 'json', 'tuji_sort', 100),
(2, 16, 0, 'form', '', '', '', 'stream_server', '.php', 'show', '', 'json', 'stream_server', 100),
(3, 14, 0, 'form', '', '', '', 'stream_server', '.php', 'show', '', 'json', 'stream_server', 100),
(4, 14, 0, 'form', '', '', '', 'tvie', '.php', 'show', '', 'json', 'stream', 100),
(5, 21, 0, 'form', '', '', '', 'video_sort_type', '.php', 'get_sort_type', '', 'json', 'video_sort_type', 100),
(6, 22, 0, 'form', '', '', '', 'program', '.php', 'getType', '', 'json', 'program_type', 100),
(7, 27, 0, 'form', '', '', '', 'adv_pos', '.php', 'get_group', '', 'json', 'group', 0),
(8, 28, 0, 'form', '', '', '', 'adv_policy', '.php', 'get_adv_content', '', 'json', 'content_list', 100),
(9, 28, 0, 'form', '', '', '', 'adv_policy', '.php', 'get_adv_pos', '', 'json', 'pos_list', 0),
(10, 27, 0, 'form', '', '', '', 'adv_pos', '.php', 'get_adv_ani', '', 'json', 'ani_list', 0),
(11, 31, 0, 'form', '', '', '', 'vod_get_sort_name', '.php', 'get_sort_name', '', 'json', 'sort_name', 0),
(12, 20, 0, 'form', '', '', '', 'vod_get_status', '.php', 'get_status', '', 'json', 'video_status', 0),
(13, 7, 0, 'form', '', '', '', 'group', '.php', 'father_group', '', 'json', 'father_group', 0),
(14, 7, 0, 'form', '', '', '', 'group', '.php', 'group_type', '', 'json', 'group_type', 0),
(15, 22, 0, 'form', '', '', '', 'program', '.php', 'getItem', '', 'json', 'program_item', 100);

-- --------------------------------------------------------

--
-- 表的结构 `liv_module_node`
--

CREATE TABLE IF NOT EXISTS `liv_module_node` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `module_id` int(10) NOT NULL DEFAULT '0',
  `node_id` int(10) NOT NULL DEFAULT '0',
  `module_op` char(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_id` (`module_id`,`node_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- 导出表中的数据 `liv_module_node`
--

INSERT INTO `liv_module_node` (`id`, `module_id`, `node_id`, `module_op`) VALUES
(1, 0, 1, 'recommend'),
(2, 4, 2, ''),
(3, 8, 3, ''),
(5, 2, 1, ''),
(6, 20, 5, '');

-- --------------------------------------------------------

--
-- 表的结构 `liv_module_op`
--

CREATE TABLE IF NOT EXISTS `liv_module_op` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `module_id` int(10) NOT NULL DEFAULT '0' COMMENT '模块id，为0为默认操作',
  `op` varchar(30) NOT NULL COMMENT '操作类型',
  `group_op` varchar(3000) NOT NULL COMMENT '一组操作',
  `has_batch` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有批量操作',
  `name` varchar(30) NOT NULL COMMENT '操作名称',
  `brief` varchar(200) NOT NULL COMMENT '操作描述',
  `host` varchar(120) NOT NULL,
  `dir` varchar(60) NOT NULL,
  `token` char(32) NOT NULL,
  `file_name` varchar(60) NOT NULL COMMENT '模块文件名',
  `file_type` char(6) NOT NULL DEFAULT '.php',
  `func_name` varchar(60) NOT NULL DEFAULT 'show' COMMENT '模块方法',
  `paras` varchar(200) NOT NULL COMMENT '参数',
  `template` varchar(60) NOT NULL COMMENT '模块模板',
  `return_type` enum('str','json','xml') NOT NULL DEFAULT 'json' COMMENT '返回值类型',
  `return_var` varchar(30) NOT NULL COMMENT '返回变量',
  `need_confirm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要确认',
  `order_id` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `request_type` enum('post','get','ajax','other') NOT NULL COMMENT '请求类型',
  `is_log` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否记录日志',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示菜单',
  `callback` varchar(500) NOT NULL COMMENT 'ajax返回调用',
  `create_time` int(10) NOT NULL,
  `relate_node` tinyint(1) NOT NULL DEFAULT '0',
  `ban` varchar(2000) NOT NULL COMMENT '禁用此操作的模块',
  `op_link` varchar(250) NOT NULL COMMENT '操作链接',
  `direct_return` tinyint(1) NOT NULL DEFAULT '0',
  `exec_callback` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否直接执行回调函数',
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='模块提供的操作表';

--
-- 导出表中的数据 `liv_module_op`
--

INSERT INTO `liv_module_op` (`id`, `module_id`, `op`, `group_op`, `has_batch`, `name`, `brief`, `host`, `dir`, `token`, `file_name`, `file_type`, `func_name`, `paras`, `template`, `return_type`, `return_var`, `need_confirm`, `order_id`, `request_type`, `is_log`, `is_show`, `callback`, `create_time`, `relate_node`, `ban`, `op_link`, `direct_return`, `exec_callback`) VALUES
(13, 15, 'update', '', 0, '更新', '', '', '', '', 'stream_server_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 1, '', 1305868261, 0, '', '', 0, 0),
(14, 0, 'recommend', '', 0, '推荐', '', '', '', '', '', '.php', 'detail', '', 'recommend', 'json', '', 0, 0, 'ajax', 1, 1, 'hg_show_template', 1306143334, 1, 'a:2:{i:0;i:0;i:3;i:3;}', '', 0, 0),
(10, 14, 'form', '', 0, '编辑', '', '', '', '', '', '.php', 'detail', '', 'channel_form', 'json', '', 0, 0, 'post', 1, 1, '', 1305868261, 0, '', '', 0, 0),
(48, 21, 'form', '', 0, '添加视频类别', '', '', '', '', 'vod_sort', '.php', 'detail', '', 'vod_sort_form', 'json', '', 0, 0, 'post', 1, 0, '', 1314084010, 0, 'b:0;', '', 0, 0),
(12, 0, 'delete', '', 1, '删除', '', '', '', '', '', '.php', 'delete', '', '', 'json', '', 1, 0, 'ajax', 1, 1, 'hg_remove_row', 1305868321, 0, 'b:0;', '', 0, 0),
(15, 14, 'create', '', 0, '增加', '', '', '', '', 'channel_create', '.php', 'create', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1311832219, 0, 'a:1:{i:0;i:14;}', '', 0, 0),
(16, 15, 'form', '', 0, '编辑', '', '', '', '', '', '.php', 'detail', '', 'stream_server_form', 'json', '', 0, 0, 'post', 1, 1, '', 1311834995, 0, '', '', 0, 0),
(17, 15, 'create', '', 0, '新增', '', '', '', '', 'stream_server_create', '.php', 'create', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1311835093, 0, 'a:1:{i:0;i:15;}', '', 0, 0),
(18, 13, 'upload', '', 0, '上传', '', '', '', '', '', '.php', 'upload', '', 'upload_photo', 'json', '', 0, -1, 'ajax', 1, 1, 'hg_upload_template', 1311837028, 0, '', '', 0, 0),
(19, 13, 'form', '', 0, '编辑', '', '', '', '', 'tuji', '.php', 'detail', '', 'tuji_form', 'json', '', 0, 0, 'post', 1, 1, '', 1311837850, 0, '', '', 0, 0),
(20, 13, 'create', '', 0, '添加', '', '', '', '', 'tuji', '.php', 'create', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1311915941, 0, '', '', 0, 0),
(34, 18, 'form', '', 0, '编辑', '', '', '', '', 'tuji_sort', '.php', 'detail', '', 'tuji_sort_form', 'json', '', 0, 0, 'post', 1, 1, '', 1312881184, 0, '', '', 0, 0),
(31, 17, 'form', '', 1, '编辑', '', '', '', '', 'pics', '.php', 'detail', '', 'pic_form', 'json', '', 0, 0, 'post', 1, 1, '', 1312851692, 0, '', '', 0, 0),
(23, 16, 'form', '', 0, '编辑', '', '', '', '', '', '.php', 'detail', '', 'stream_form', 'json', '', 0, 0, 'post', 1, 1, '', 1312187929, 0, '', '', 0, 0),
(24, 16, 'update', '', 0, '更新', '', '', '', '', 'stream_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 1, '', 1312187992, 0, 'a:1:{i:0;i:16;}', '', 0, 0),
(25, 16, 'create', '', 0, '新增', '', '', '', '', 'stream_create', '.php', 'create', '', '', 'json', '', 0, 0, 'ajax', 1, 1, '', 1312190573, 0, 'a:1:{i:0;i:16;}', '', 0, 0),
(30, 20, 'form', '', 0, '编辑', '', '', '', '', 'vod_upload', '.php', 'detail', '', 'vod_update_list', 'json', '', 0, 0, 'post', 1, 1, '', 1312795877, 0, 'b:0;', '', 0, 0),
(27, 14, 'update', '', 0, '更新', '', '', '', '', 'channel_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 1, '', 1312363935, 0, 'a:1:{i:0;i:14;}', '', 0, 0),
(32, 17, 'update', '', 0, '更新', '', '', '', '', 'pics_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1312853495, 0, '', '', 0, 0),
(33, 20, 'upload', '', 0, '上传', '', '', '', '', 'vod_upload', '.php', 'upload', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1312861093, 0, 'b:0;', '', 0, 0),
(35, 18, 'update', '', 0, '更新', '', '', '', '', 'tuji_sort_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1312882310, 0, '', '', 0, 0),
(36, 13, 'update', '', 0, '更新', '', '', '', '', 'tuji_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1312937731, 0, '', '', 0, 0),
(37, 18, 'create', '', 0, '添加', '', '', '', '', 'tuji_sort_update', '.php', 'create', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1312943174, 0, '', '', 0, 0),
(38, 13, 'list', '', 0, '查看', '', '', '', '', 'pics', '.php', 'show', '', 'pics_list', 'json', 'pics_list', 0, 0, 'post', 1, 1, '', 1312953652, 0, '', './run.php?mid=17', 0, 0),
(39, 21, 'form', '', 0, '编辑', '', '', '', '', 'vod_sort', '.php', 'detail', '', 'vod_sort_form', 'json', '', 0, 0, 'post', 1, 1, '', 1313028356, 0, 'b:0;', '', 0, 0),
(40, 17, 'get_cover', '', 0, '设为封面', '', '', '', '', 'tuji_update', '.php', 'get_cover', '', 'select_cover', 'json', '', 0, 0, 'ajax', 1, 1, 'hg_show_template', 1313110979, 0, '', '', 0, 0),
(41, 17, 'set_new_cover', '', 0, '更新封面', '', '', '', '', 'tuji_update', '.php', 'set_new_cover', '', '', 'json', '', 0, 0, 'ajax', 1, 0, 'hg_dialog_close', 1313132904, 0, '', '', 0, 0),
(42, 20, 'getinfo', '', 0, '请求信息', '', '', '', '', 'vod_getvideo_info', '.php', 'getinfo', '', '', 'json', '', 0, 0, 'ajax', 0, 0, 'hg_panduan', 1313137990, 0, 'b:0;', '', 1, 1),
(43, 20, 'getVideoInfo', '', 0, '请求视频信息', '', '', '', '', 'vod_upload', '.php', 'getVideoInfo', '', '', 'json', '', 0, 0, 'ajax', 0, 0, '', 1313474055, 0, 'b:0;', '', 1, 0),
(45, 20, 'update', '', 0, '更新', '', '', '', '', 'vod_upload_update', '.php', 'update', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1314066303, 0, 'b:0;', '', 0, 0),
(44, 22, 'create', '', 0, '添加', '', '', 'livsns/api/liv_mms/admin/', '', 'program_create', '.php', 'create', '', 'program_item', 'json', 'program_item', 0, 0, 'ajax', 1, 0, 'hg_valid_program_create,i', 1314064551, 0, '', '', 0, 0),
(46, 22, 'update', '', 0, '编辑', '', '', '', '', 'program_update', '.php', 'update', '', 'program_item', 'json', 'program_item', 0, 0, 'ajax', 1, 0, 'hg_valid_program_data,id,i', 1314078346, 0, '', '', 0, 0),
(117, 7, 'form', '', 0, '编辑', '', '', '', '', '', '.php', 'detail', '', 'group_form', 'json', 'group_info', 0, 0, 'post', 1, 1, '', 1318210699, 0, 'a:0:{}', '', 0, 0),
(47, 21, 'update', '', 0, '更新', '', '', '', '', 'vod_sort_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1314082258, 0, 'b:0;', '', 0, 0),
(49, 22, 'form', '', 0, '编辑', '', '', '', '', '', '.php', 'detail', '', 'program_form', 'json', '', 0, 0, 'ajax', 1, 0, 'hg_program_show,id, channel_id,date,i', 1314090816, 0, '', '', 0, 0),
(50, 21, 'create', '', 0, '添加', '', '', '', '', 'vod_sort_update', '.php', 'create', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1314091028, 0, 'b:0;', '', 0, 0),
(51, 23, 'form', '', 0, '编辑', '', '', '', '', 'adv_group', '.php', 'detail', '', 'group_form', 'json', '', 0, 0, 'post', 1, 1, '', 1314585684, 0, 'a:0:{}', '', 0, 0),
(52, 23, 'update', '', 0, '更新', '', '', '', '', 'adv_group_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1314588427, 0, 'a:0:{}', '', 0, 0),
(53, 23, 'create', '', 0, '增加', '', '', '', '', 'adv_group_update', '.php', 'create', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1314596556, 0, 'a:0:{}', '', 0, 0),
(54, 24, 'create', '', 0, '增加', '', '', '', '', 'adv_content_update', '.php', 'create', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1314598837, 0, 'a:0:{}', '', 0, 0),
(55, 24, 'form', '', 0, '编辑', '', '', '', '', '', '.php', 'detail', '', 'content_form', 'json', '', 0, 0, 'post', 1, 1, '', 1314598893, 0, 'a:0:{}', '', 0, 0),
(56, 24, 'update', '', 0, '更新', '', '', '', '', 'adv_content_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1314598932, 0, 'a:0:{}', '', 0, 0),
(57, 20, 'audit', '', 1, '审核', '', '', '', '', 'vod_upload_update', '.php', 'audit', '', '', 'json', '', 1, 0, 'ajax', 1, 1, '', 1314604413, 0, 'b:0;', '', 0, 0),
(58, 25, 'form', '', 0, '编辑', '', '', '', '', 'adv_animation', '.php', 'detail', '', 'animation_form', 'json', '', 0, 0, 'post', 1, 1, '', 1314607023, 0, 'a:0:{}', '', 0, 0),
(59, 25, 'create', '', 0, '增加', '', '', '', '', 'adv_animation_update', '.php', 'create', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1314607826, 0, 'a:0:{}', '', 0, 0),
(60, 25, 'update', '', 0, '更新', '', '', '', '', 'adv_animation_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1314607861, 0, 'a:0:{}', '', 0, 0),
(61, 26, 'form', '', 0, '编辑', '', '', '', '', 'adv_user', '.php', 'detail', '', 'user_form', 'json', '', 0, 0, 'post', 1, 1, '', 1314610040, 0, 'a:0:{}', '', 0, 0),
(62, 26, 'create', '', 0, '增加', '', '', '', '', 'adv_user_update', '.php', 'create', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1314610448, 0, 'a:0:{}', '', 0, 0),
(63, 26, 'update', '', 0, '更新', '', '', '', '', 'adv_user_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1314610484, 0, 'a:0:{}', '', 0, 0),
(64, 20, 'form_addlist', '', 0, '添加一行列表', '', '', '', '', 'vod_add_newlist', '.php', 'detail', '', 'vod_addnewlist', 'json', '', 0, 0, 'ajax', 1, 0, 'hg_add_list', 1314684106, 0, 'b:0;', '', 0, 0),
(65, 27, 'form', '', 0, '编辑', '', '', '', '', 'adv_pos', '.php', 'detail', '', 'pos_form', 'json', '', 0, 0, 'post', 1, 1, '', 1314690068, 0, 'a:0:{}', '', 0, 0),
(66, 27, 'update', '', 0, '更新', '', '', '', '', 'adv_pos_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1314690239, 0, 'a:0:{}', '', 0, 0),
(67, 27, 'create', '', 0, '新增', '', '', '', '', 'adv_pos_update', '.php', 'create', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1314754524, 0, 'a:0:{}', '', 0, 0),
(68, 20, 'form_get_img', '', 0, '获取多张视频截图', '', '', '', '', 'vod_get_img', '.php', 'get_img', '', 'vod_img_list', 'json', '', 0, 0, 'ajax', 1, 0, 'hg_show_template', 1314781619, 0, 'b:0;', '', 0, 0),
(69, 20, 'update_img', '', 0, '更新视频截图', '', '', '', '', 'vod_update_img', '.php', 'update_img', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1314927467, 0, 'b:0;', '', 0, 0),
(71, 20, 'move', '', 1, '移动视频到某个类别下', '', '', '', '', 'vod_move', '.php', 'move', '', 'vod_select_sort', 'json', '', 0, 0, 'ajax', 1, 1, 'hg_show_template', 1315041051, 0, 'b:0;', '', 0, 0),
(72, 20, 'update_move', '', 0, '更新移动到的类别', '', '', '', '', 'vod_update_move', '.php', 'update_move', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1315184231, 0, 'b:0;', '', 0, 0),
(73, 22, 'copy', '', 0, '复制上周', '', '', '', '', 'program_update', '.php', 'copy', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1315184343, 0, '', '', 0, 0),
(74, 20, 'get_copyright', '', 0, '调出编辑版本数据', '', '', '', '', 'vod_get_copyright', '.php', 'get_copyright', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1315199743, 0, 'b:0;', '', 1, 0),
(75, 28, 'create', '', 0, '添加', '', '', '', '', 'adv_policy_update', '.php', 'create', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1315203973, 0, 'a:0:{}', '', 0, 0),
(76, 28, 'form', '', 0, '编辑', '', '', '', '', 'adv_policy', '.php', 'detail', '', 'policy_form', 'json', '', 0, 0, 'post', 1, 1, '', 1315204077, 0, 'a:0:{}', '', 0, 0),
(77, 28, 'update', '', 0, '更新', '', '', '', '', 'adv_policy_update', '.php', 'update', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1315204190, 0, 'a:0:{}', '', 0, 0),
(78, 28, 'get_adv_pos_para', '', 0, '获取广告位参数', '', '', '', '', 'adv_policy', '.php', 'get_adv_pos_para', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1315275313, 0, 'a:0:{}', '', 1, 0),
(79, 20, 'add_to_collect', '', 1, '将视频添加到集合', '', '', '', '', 'vod_add_collect', '.php', 'add_to_collect', '', 'vod_findcollect_list', 'json', '', 1, 0, 'ajax', 1, 1, 'hg_show_template', 1315291944, 0, 'b:0;', '', 0, 0),
(80, 20, 'back_words', '', 0, '返回获得的匹配的字符', '', '', '', '', 'vod_back_words', '.php', 'back_words', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1315296259, 0, 'b:0;', '', 1, 0),
(81, 20, 'video2collect', '', 0, '将所选的视频添加到指定的集合', '', '', '', '', 'vod_video2collect', '.php', 'video2collect', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1315443626, 0, '', '', 0, 0),
(82, 20, 'create_collect', '', 0, '创建视频集合', '', '', '', '', 'vod_create_collect', '.php', 'create_collect', '', 'vod_collect_form', 'json', '', 0, 0, 'ajax', 1, 0, 'hg_show_template', 1315449846, 0, '', '', 0, 0),
(83, 20, 'insert2collect', '', 0, '将填写好的创建集合的数据插入到数据库', '', '', '', '', 'vod_create_collect', '.php', 'insert2collect', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1315461567, 0, '', '', 0, 0),
(95, 20, 'add_single_video', '', 0, '单视频新增', '', '', '', '', 'vod_add_single_video', '.php', 'add_single_video', '', 'vod_single_video_form', 'json', '', 0, 0, 'ajax', 1, 0, 'hg_show_template', 1316740174, 0, '', '', 0, 0),
(85, 24, 'upload_material', '', 0, '上传', '', '', '', '', 'adv_content_update', '.php', 'upload_material', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1315533446, 0, 'a:0:{}', '', 0, 0),
(86, 31, 'remove', '', 1, '将视频从集合里面去除掉', '', '', '', '', 'vod_remove_collect_video', '.php', 'remove', '', '', 'json', '', 1, 0, 'ajax', 1, 1, '', 1315556705, 0, 'a:0:{}', '', 0, 0),
(88, 28, 'get_adv_pos', '', 0, '获取广告位', '', '', '', '', 'adv_policy', '.php', 'get_adv_pos', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1315989675, 0, 'a:0:{}', '', 1, 0),
(89, 31, 'form', '', 0, '编辑集合', '', '', '', '', 'vod_collect', '.php', 'detail', '', 'vod_collect_edit_form', 'json', '', 0, 0, 'post', 1, 0, '', 1315993654, 0, 'a:0:{}', '', 0, 0),
(92, 28, 'upload_ad_material', '', 0, '上传广告素材', '', '', '', '', 'adv_policy_update', '.php', 'upload_ad_material', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1316163188, 0, 'a:0:{}', '', 1, 0),
(91, 31, 'update', '', 0, '更新编辑好的内容', '', '', '', '', 'vod_collect_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1316140618, 0, 'a:0:{}', '', 0, 0),
(93, 20, 'get_collect_info', '', 0, '鼠标移动到集合图标上获取集合信息', '', '', '', '', 'vod_get_collect_info', '.php', 'get_collect_info', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1316220973, 0, '', '', 1, 0),
(96, 20, 'single_upload', '', 0, '单视频上传', '', '', '', '', 'vod_upload', '.php', 'single_upload', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1316855747, 0, 'b:0;', '', 1, 0),
(97, 20, 'preview_pic', '', 0, '保存预览图片', '', '', '', '', 'vod_update_img', '.php', 'preview_pic', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1317021199, 0, '', '', 1, 0),
(98, 28, 'get_video_info', '', 0, '获取视频信息', '', '', '', '', 'adv_policy_update', '.php', 'get_video_info', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1317109206, 0, 'a:0:{}', '', 1, 0),
(99, 14, 'stream_state', '', 0, '信号流状态', '', '', '', '', 'channel_update', '.php', 'stream_state', '', '', 'json', '', 0, 0, 'ajax', 1, 1, '', 1317275618, 0, '', '', 1, 0),
(100, 14, 'drm_state', '', 0, 'drm状态', '', '', '', '', 'channel_update', '.php', 'drm_state', '', '', 'json', '', 0, 0, 'ajax', 1, 1, '', 1317275952, 0, '', '', 1, 0),
(101, 14, 'channel_status', '', 0, '频道状态', '', '', '', '', 'channel_update', '.php', 'channel_status', '', '', 'json', '', 0, 0, 'ajax', 1, 1, '', 1317282185, 0, '', '', 1, 0),
(102, 33, 'drag_order', '', 0, '拖动排序', '', '', '', '', 'vod_drag_order', '.php', 'drag_order', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1317285775, 0, 'a:0:{}', '', 0, 0),
(105, 37, 'form', '', 0, '编辑', '', '', '', '', 'adv_content', '.php', 'detail', '', 'policy_form', 'json', '', 0, 0, 'post', 1, 1, '', 1318037778, 0, 'a:0:{}', '', 0, 0),
(103, 7, 'more', '', 0, '查看详情', '', '', '', '', '', '.php', 'more', '', 'group_more', 'json', '', 0, 0, 'ajax', 1, 1, 'hg_group_more,group_id', 1317348103, 0, 'a:0:{}', '', 0, 0),
(114, 36, 'check', '', 0, '检测', '', '', '', '', 'group_apply', '.php', 'check', '', '', 'json', '', 0, 0, 'ajax', 1, 0, 'hg_agree_this,user_id,group_id,type', 1318134178, 0, 'a:0:{}', '', 1, 0),
(106, 20, 'get_img_update', '', 0, '编辑视频里面获得截图', '', '', '', '', 'vod_get_img', '.php', 'get_img_update', '', 'vod_img_update_list', 'json', '', 0, 0, 'ajax', 1, 0, 'hg_get_img_update', 1318038874, 0, 'a:0:{}', '', 0, 0),
(107, 37, 'get_adv_pos', '', 0, '获取广告位', '', '', '', '', 'adv_content', '.php', 'get_adv_pos', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1318039030, 0, 'a:0:{}', '', 1, 0),
(108, 37, 'get_video_info', '', 0, '视频信息', '获取视频信息', '', '', '', 'adv_content_update', '.php', 'get_video_info', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1318039882, 0, 'a:0:{}', '', 1, 0),
(109, 37, 'create', '', 0, '添加', '发布广告', '', '', '', 'adv_content_update', '.php', 'create', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1318061536, 0, 'a:0:{}', '', 0, 0),
(110, 37, 'upload_ad_material', '', 0, '上传', '广告素材', '', '', '', 'adv_content_update', '.php', 'upload_ad_material', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1318062942, 0, 'a:0:{}', '', 1, 0),
(111, 37, 'update', '', 0, '更新', '广告内容更新', '', '', '', 'adv_content_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1318064196, 0, 'a:0:{}', '', 0, 0),
(112, 20, 'drag_order', '', 0, '视频列表拖动排序', '', '', '', '', 'vod_video_drag', '.php', 'drag_order', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1318123189, 0, 'a:0:{}', '', 0, 0),
(116, 22, 'upload_program', '', 0, '上传节目单', '', '', '', '', 'upload_program', '.php', 'uploads', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1318142417, 0, 'a:0:{}', '', 1, 0),
(115, 20, 'vod_recommend', '', 0, '视频的推荐', '', '', '', '', 'vod_upload', '.php', 'detail', '', 'recommend', 'json', '', 0, 0, 'ajax', 1, 0, 'hg_vod_recommend', 1318140060, 0, 'a:0:{}', '', 0, 0),
(118, 7, 'update', '', 0, '更新', '', '', '', '', 'group_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1318226813, 0, 'a:0:{}', '', 0, 0),
(119, 7, 'audit', '-1= - 请选择 - \n1=审核通过\n0=审核不过', 0, '审核', '', '', '', '', 'group_update', '.php', 'audit', '', '', 'json', '', 0, 0, 'ajax', 1, 1, '', 1318230288, 0, 'a:0:{}', '', 0, 0),
(120, 22, 'program2db', '', 0, '上传节目单入库', '', '', '', '', 'upload_program', '.php', 'program2db', '', '', 'json', '', 0, 0, 'ajax', 1, 0, '', 1318231841, 0, 'a:0:{}', '', 0, 0),
(121, 40, 'form', '', 0, '编辑', '', '', '', '', '', '.php', 'detail', '', 'live_backup_form', 'json', '', 0, 0, 'post', 1, 1, '', 1318299517, 0, 'a:0:{}', '', 0, 0),
(122, 40, 'create', '', 0, '创建', '', '', '', '', 'live_backup_update', '.php', 'create', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1318299679, 0, 'a:0:{}', '', 0, 0),
(123, 40, 'update', '', 0, '更新', '', '', '', '', 'live_backup_update', '.php', 'update', '', '', 'json', '', 0, 0, 'post', 1, 0, '', 1318299772, 0, 'a:0:{}', '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `liv_node`
--

CREATE TABLE IF NOT EXISTS `liv_node` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `application_id` int(10) NOT NULL COMMENT '子系统id',
  `brief` varchar(400) NOT NULL,
  `host` varchar(120) NOT NULL,
  `dir` varchar(60) NOT NULL,
  `file_name` varchar(30) NOT NULL COMMENT '文件名',
  `file_type` char(6) NOT NULL DEFAULT '.php',
  `func_name` varchar(60) NOT NULL COMMENT '方法名',
  `template` varchar(30) NOT NULL COMMENT '模板名称',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型0 - 默认， 1-纯操作',
  `create_time` int(10) NOT NULL,
  `settings` text NOT NULL COMMENT '设置',
  `token` varchar(32) NOT NULL,
  `order_id` int(10) NOT NULL DEFAULT '0',
  `return_type` enum('str','json','xml') NOT NULL DEFAULT 'json',
  `return_var` varchar(30) NOT NULL,
  `primary_key` varchar(30) NOT NULL COMMENT '节点主键',
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='功能模块表';

--
-- 导出表中的数据 `liv_node`
--

INSERT INTO `liv_node` (`id`, `name`, `application_id`, `brief`, `host`, `dir`, `file_name`, `file_type`, `func_name`, `template`, `type`, `create_time`, `settings`, `token`, `order_id`, `return_type`, `return_var`, `primary_key`) VALUES
(1, '栏目节点', 2, '', '', '', 'column', '.php', 'show', '_nodedata', 0, 1306467074, '', 'e10adc3949ba59abbe56e057f20f883e', 0, 'json', 'columns', 'columnid'),
(5, '点播节点', 8, '', '', '', 'vod_node', '.php', 'show', '_nodedata', 0, 1316502371, '', '', 0, 'json', 'columns', '');

-- --------------------------------------------------------

--
-- 表的结构 `liv_prms`
--

CREATE TABLE IF NOT EXISTS `liv_prms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL COMMENT '管理员id',
  `admin_group_id` int(10) NOT NULL COMMENT '分组id',
  `prms` text NOT NULL COMMENT '权限设置',
  `module_prms` varchar(3000) NOT NULL COMMENT '模块权限',
  `sys_prms` varchar(3000) NOT NULL COMMENT '系统权限',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='权限设置表';

--
-- 导出表中的数据 `liv_prms`
--

INSERT INTO `liv_prms` (`id`, `admin_id`, `admin_group_id`, `prms`, `module_prms`, `sys_prms`) VALUES
(1, 0, 1, '', '', 'a:1:{i:1;a:1:{s:4:"show";s:1:"1";}}');

-- --------------------------------------------------------

--
-- 表的结构 `liv_recommend`
--

CREATE TABLE IF NOT EXISTS `liv_recommend` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `module_id` int(10) NOT NULL COMMENT '所属模块',
  `content_id` int(10) NOT NULL COMMENT '内容id',
  `column_id` int(10) NOT NULL COMMENT '推荐到栏目id',
  `create_time` int(10) NOT NULL COMMENT '推荐时间',
  `title` varchar(200) NOT NULL COMMENT '推荐标题',
  `brief` varchar(2000) NOT NULL COMMENT '描述',
  `pic` varchar(120) NOT NULL COMMENT '图片',
  `link` varchar(150) NOT NULL COMMENT '原文链接',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='信息推荐表';


-- --------------------------------------------------------

--
-- 表的结构 `liv_search`
--

CREATE TABLE IF NOT EXISTS `liv_search` (
  `hash` char(32) NOT NULL,
  `search` varchar(5000) NOT NULL COMMENT '搜索内容',
  `update_time` int(10) NOT NULL,
  PRIMARY KEY (`hash`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='搜索表';

--
-- 导出表中的数据 `liv_search`

-- --------------------------------------------------------

--
-- 表的结构 `liv_setting`
--

CREATE TABLE IF NOT EXISTS `liv_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL COMMENT '管理员id',
  `desktop` varchar(150) NOT NULL COMMENT '桌面背景设置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='个人设置表';

--
-- 导出表中的数据 `liv_setting`
--

