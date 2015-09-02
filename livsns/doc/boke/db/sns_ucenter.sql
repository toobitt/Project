-- phpMyAdmin SQL Dump
-- version 2.11.10
-- http://www.phpmyadmin.net
--
-- 主机: 192.168.0.110
-- 生成日期: 2011 年 08 月 10 日 16:55
-- 服务器版本: 5.1.48
-- PHP 版本: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `sns_ucenter`
--

-- --------------------------------------------------------

--
-- 表的结构 `liv_admins`
--

CREATE TABLE IF NOT EXISTS `liv_admins` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL DEFAULT '',
  `allowadminsetting` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminapp` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminuser` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminbadword` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmintag` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminpm` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmincredits` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmindomain` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmindb` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminnote` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmincache` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminlog` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_applications`
--

CREATE TABLE IF NOT EXISTS `liv_applications` (
  `appid` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `type` char(16) NOT NULL DEFAULT '',
  `is_cp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否是应用后台',
  `name` char(20) NOT NULL DEFAULT '',
  `url` char(255) NOT NULL DEFAULT '',
  `authkey` char(255) NOT NULL DEFAULT '',
  `ip` char(15) NOT NULL DEFAULT '',
  `viewprourl` char(255) NOT NULL,
  `apifilename` char(30) NOT NULL DEFAULT 'uc.php',
  `charset` char(8) NOT NULL DEFAULT '',
  `dbcharset` char(8) NOT NULL DEFAULT '',
  `synlogin` tinyint(1) NOT NULL DEFAULT '0',
  `recvnote` tinyint(1) DEFAULT '0',
  `extra` mediumtext NOT NULL,
  `tagtemplates` mediumtext NOT NULL,
  PRIMARY KEY (`appid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_badwords`
--

CREATE TABLE IF NOT EXISTS `liv_badwords` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `admin` varchar(15) NOT NULL DEFAULT '',
  `find` varchar(255) NOT NULL DEFAULT '',
  `replacement` varchar(255) NOT NULL DEFAULT '',
  `findpattern` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `find` (`find`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_email`
--

CREATE TABLE IF NOT EXISTS `liv_email` (
  `email` varchar(35) NOT NULL COMMENT 'email---记录存储',
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_failedlogins`
--

CREATE TABLE IF NOT EXISTS `liv_failedlogins` (
  `ip` char(15) NOT NULL DEFAULT '',
  `count` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_favorites`
--

CREATE TABLE IF NOT EXISTS `liv_favorites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fa_id` int(10) NOT NULL,
  `member_id` int(10) NOT NULL COMMENT '用户id',
  `sort_id` int(10) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '收藏的内容标题',
  `cid` int(10) NOT NULL COMMENT '收藏的内容id',
  `type_id` int(10) NOT NULL COMMENT '收藏类型id 1:视频 2:相册 3:帖子',
  `link` varchar(200) NOT NULL COMMENT '收藏内容的链接',
  `schematic` varchar(200) NOT NULL COMMENT '缩略图',
  `create_time` int(10) NOT NULL COMMENT '收藏时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_favorites_sort`
--

CREATE TABLE IF NOT EXISTS `liv_favorites_sort` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '分类id memberid 标识 收藏分类表liv_favorites_sort',
  `member_id` int(10) NOT NULL COMMENT '用户id 标识 收藏分类表liv_favorites_sort',
  `sort_name` varchar(30) NOT NULL COMMENT '分类名称 name 属于 收藏分类表liv_favorites_sort',
  `description` varchar(200) NOT NULL COMMENT '分类描述description 属于 收藏分类表liv_favorites_sort',
  `fav_count` int(10) NOT NULL COMMENT '收藏数 fav_count 属于 收藏分类表liv_favorites_sort',
  `create_time` int(10) NOT NULL COMMENT '创建时间 create_time 属于 收藏分类表liv_favorites_sort',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏分类表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_invite_code`
--

CREATE TABLE IF NOT EXISTS `liv_invite_code` (
  `code` char(8) CHARACTER SET utf8 NOT NULL,
  `member_id` int(10) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='邀请码表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_location`
--

CREATE TABLE IF NOT EXISTS `liv_location` (
  `code` char(15) NOT NULL COMMENT '地区编码',
  `pinyin` varchar(15) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL,
  `gh` varchar(5) NOT NULL DEFAULT '' COMMENT '国号',
  `qh` varchar(5) NOT NULL DEFAULT '' COMMENT '区号',
  `postcode` varchar(6) NOT NULL DEFAULT '' COMMENT '邮编',
  `content` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `depth` int(11) NOT NULL COMMENT '深度',
  `last` int(11) NOT NULL DEFAULT '1' COMMENT '末级',
  `lat` float(17,14) NOT NULL COMMENT '该地区的纬度',
  `lng` float(17,14) NOT NULL COMMENT '经度',
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标准地区表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_location_latlng`
--

CREATE TABLE IF NOT EXISTS `liv_location_latlng` (
  `name` varchar(50) NOT NULL COMMENT '地区名',
  `lng` varchar(50) NOT NULL COMMENT '精度',
  `lat` int(50) NOT NULL COMMENT '纬度'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_member`
--

CREATE TABLE IF NOT EXISTS `liv_member` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户id 标识 用户表liv_member',
  `email` varchar(120) NOT NULL COMMENT '邮箱email 属于 用户表liv_member',
  `username` char(30) NOT NULL COMMENT '用户名username 属于 用户表liv_member',
  `truename` varchar(30) NOT NULL,
  `user_group_id` int(10) NOT NULL COMMENT '用户组id',
  `password` char(32) NOT NULL COMMENT '密码 password 属于 用户表liv_member',
  `salt` char(6) NOT NULL COMMENT '密码干扰串 salt 属于 用户表liv_member',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0--未知，1--男，2--女',
  `location` char(30) NOT NULL COMMENT '所在地 采用标准地区码信息',
  `location_code` char(20) NOT NULL COMMENT '地区编码',
  `birthday` date NOT NULL COMMENT '生日 birthday 属于 用户表liv_member',
  `avatar` char(100) NOT NULL DEFAULT '0.jpg' COMMENT '头像 avatar 属于 用户表liv_member 存储图片路径',
  `qq` char(15) NOT NULL COMMENT 'QQ号 qq 属于 用户表liv_member',
  `qq_login` char(35) DEFAULT NULL COMMENT 'QQ同步登陆',
  `mobile` char(15) NOT NULL COMMENT '手机号 mobile 属于 用户表liv_member',
  `msn` varchar(120) NOT NULL COMMENT 'MSN msn 属于 用户表liv_member',
  `join_time` int(10) NOT NULL COMMENT '注册时间jointime 属于 用户表liv_member',
  `last_login` int(10) NOT NULL COMMENT '上次登录时间lastlogin 属于 用户表liv_member',
  `group_id` int(10) NOT NULL COMMENT '用户标注讨论区ID 属于 用户表liv_member',
  `privacy` char(20) NOT NULL DEFAULT '10111100000000000000' COMMENT '用户隐私，各隐私表示，见global.conf.php',
  `digital_tv` char(30) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `email_check` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否邮箱验证',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `qq_login` (`qq_login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=212 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_memberfields`
--

CREATE TABLE IF NOT EXISTS `liv_memberfields` (
  `uid` mediumint(8) unsigned NOT NULL,
  `blacklist` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_member_apply`
--

CREATE TABLE IF NOT EXISTS `liv_member_apply` (
  `member_id` int(10) NOT NULL COMMENT '用户id memberid 标识 用户应用表liv_member_apply',
  `apply_id` int(10) NOT NULL COMMENT '应用id apply_id 部分标识 用户应用表liv_member_apply',
  `info_num` int(10) NOT NULL COMMENT '信息数量 info_num 属于 用户应用表liv_member_apply',
  `add_time` int(10) NOT NULL COMMENT '添加时间 add_time 属于 用户应用表liv_member_apply',
  PRIMARY KEY (`apply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户应用表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_member_block`
--

CREATE TABLE IF NOT EXISTS `liv_member_block` (
  `member_id` int(10) NOT NULL COMMENT '用户id memberid 标识 用户黑名单表 liv_member_block',
  `bmemberid` int(10) NOT NULL COMMENT '被黑人id bmemberid 属于 用户黑名单表 liv_member_block',
  `block_time` int(10) NOT NULL COMMENT '拉黑时间 block_time 属于 用户黑名单表 liv_member_block',
  PRIMARY KEY (`member_id`,`bmemberid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户黑名单表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_member_config`
--

CREATE TABLE IF NOT EXISTS `liv_member_config` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `web_name` varchar(30) NOT NULL,
  `last_key` varchar(1000) NOT NULL,
  `uid` varchar(30) NOT NULL,
  `type` tinyint(6) NOT NULL,
  `time` int(10) NOT NULL,
  `state` tinyint(4) NOT NULL,
  `is_bind` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_member_credit_log`
--

CREATE TABLE IF NOT EXISTS `liv_member_credit_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `rid` int(10) NOT NULL,
  `oid` int(10) NOT NULL,
  `credit` int(10) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_member_credit_rule`
--

CREATE TABLE IF NOT EXISTS `liv_member_credit_rule` (
  `rid` int(10) NOT NULL AUTO_INCREMENT,
  `rule_name` varchar(30) CHARACTER SET utf8 NOT NULL,
  `cycle_type` tinyint(4) NOT NULL,
  `reward_num` smallint(6) NOT NULL,
  `add_person` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '积分规则添加者',
  `credit` smallint(6) NOT NULL,
  `is_use` tinyint(4) NOT NULL COMMENT '是否开启这项积分',
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_member_extra`
--

CREATE TABLE IF NOT EXISTS `liv_member_extra` (
  `member_id` int(10) NOT NULL COMMENT '用户id memberid 标识 用户扩展表 liv_member_extra',
  `last_activity` int(10) NOT NULL COMMENT '活动时间last_activity 属于 用户扩展表 liv_member_ex',
  `followers_count` int(10) NOT NULL COMMENT '粉丝数followers_count 属于 用户扩展表 liv_member_extra',
  `attention_count` int(10) NOT NULL COMMENT '关注人数attention_count 属于 用户扩展表 liv_member_extra',
  `ip` char(24) NOT NULL COMMENT '注册ip ip 属于 用户扩展表 liv_member_extra',
  `status_count` int(10) NOT NULL COMMENT '微博数',
  `thread_count` int(10) NOT NULL COMMENT '用户视频数目',
  `post_count` int(10) NOT NULL COMMENT '用户视频数目',
  `video_count` int(10) NOT NULL COMMENT '用户视频数目',
  `credit` int(10) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `last_status_id` int(10) NOT NULL COMMENT '最新微博id',
  `reffer_user` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户扩展表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_member_group`
--

CREATE TABLE IF NOT EXISTS `liv_member_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户组ID 标识 用户组表liv_member_group',
  `groupname` varchar(30) NOT NULL COMMENT '用户组名称 groupname 属于 用户组表liv_member_group',
  `groupdesc` varchar(120) NOT NULL COMMENT '组描述 groupdesc 属于 用户组表liv_member_group',
  `member_count` int(10) NOT NULL COMMENT '用户数 member_count 属于 用户组表liv_member_group',
  `prms` varchar(500) NOT NULL COMMENT '组权限 prms 属于 用户组表liv_member_group',
  `order_id` int(10) NOT NULL COMMENT '排序id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_member_location`
--

CREATE TABLE IF NOT EXISTS `liv_member_location` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `member_id` int(10) NOT NULL COMMENT '用户id memberid 标识 用户地理信息表 liv_member_location',
  `group_name` varchar(255) NOT NULL COMMENT '用户设置的默认讨论区',
  `frequency` char(10) NOT NULL COMMENT '频度 frequency 属于 用户地理信息表 liv_member_location',
  `lat` float(17,14) NOT NULL COMMENT '默认发帖纬度',
  `lng` float(17,14) NOT NULL COMMENT '默认发帖精度',
  `group_id` int(10) NOT NULL COMMENT '默认讨论区id',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户地理信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_member_relation`
--

CREATE TABLE IF NOT EXISTS `liv_member_relation` (
  `member_id` int(10) NOT NULL COMMENT '用户memberid 标识 用户关系表liv_member_relation',
  `fmember_id` int(10) NOT NULL COMMENT '关注用户fmemberid 部分标识 用户关系表liv_member_relation',
  `follow_time` int(10) NOT NULL COMMENT '关注时间 follow_time 属于 用户关系表liv_member_relation',
  PRIMARY KEY (`member_id`,`fmember_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户关注关系表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_member_relation_verify`
--

CREATE TABLE IF NOT EXISTS `liv_member_relation_verify` (
  `member_id` int(10) NOT NULL COMMENT '用户id memberid 标识 待验证关注用户liv_member_relation_verify',
  `fmember_id` int(10) NOT NULL COMMENT '关注用户 fmemberid 属于 待验证关注用户liv_member_relation_verify',
  `follow_time` int(10) NOT NULL COMMENT '关注时间 follow_time 属于 待验证关注用户liv_member_relation_verify',
  PRIMARY KEY (`member_id`,`fmember_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='待验证关注用户表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_member_syn_relation`
--

CREATE TABLE IF NOT EXISTS `liv_member_syn_relation` (
  `status_id` int(10) NOT NULL COMMENT '微博ID',
  `syn_id` varchar(20) NOT NULL COMMENT '同步微博ID',
  `type` tinyint(4) NOT NULL COMMENT '同步微博的类型'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_mergemembers`
--

CREATE TABLE IF NOT EXISTS `liv_mergemembers` (
  `appid` smallint(6) unsigned NOT NULL,
  `username` char(15) NOT NULL,
  PRIMARY KEY (`appid`,`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_new_member`
--

CREATE TABLE IF NOT EXISTS `liv_new_member` (
  `member_id` int(10) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_notelist`
--

CREATE TABLE IF NOT EXISTS `liv_notelist` (
  `noteid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `operation` char(32) NOT NULL,
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `totalnum` smallint(6) unsigned NOT NULL DEFAULT '0',
  `succeednum` smallint(6) unsigned NOT NULL DEFAULT '0',
  `getdata` mediumtext NOT NULL,
  `postdata` mediumtext NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `pri` tinyint(3) NOT NULL DEFAULT '0',
  `app2` tinyint(4) NOT NULL,
  `app8` tinyint(4) NOT NULL,
  `app9` tinyint(4) NOT NULL,
  PRIMARY KEY (`noteid`),
  KEY `closed` (`closed`,`pri`,`noteid`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_notify`
--

CREATE TABLE IF NOT EXISTS `liv_notify` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '通知ID',
  `content` varchar(20000) NOT NULL COMMENT '通知内容',
  `member_id` int(10) NOT NULL COMMENT '给用户的通知，为0给全部用户',
  `type` tinyint(1) NOT NULL COMMENT '0-系统通知',
  `notify_time` int(10) NOT NULL COMMENT '通知时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_notify_read`
--

CREATE TABLE IF NOT EXISTS `liv_notify_read` (
  `notify_id` int(10) NOT NULL COMMENT '通知ID',
  `member_id` int(10) NOT NULL COMMENT '用户id',
  `read_time` int(10) NOT NULL COMMENT '阅读时间',
  `type` tinyint(4) NOT NULL,
  PRIMARY KEY (`notify_id`,`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_notify_total`
--

CREATE TABLE IF NOT EXISTS `liv_notify_total` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_protectedmembers`
--

CREATE TABLE IF NOT EXISTS `liv_protectedmembers` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` char(15) NOT NULL DEFAULT '',
  `appid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `admin` char(15) NOT NULL DEFAULT '0',
  UNIQUE KEY `username` (`username`,`appid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_search_result`
--

CREATE TABLE IF NOT EXISTS `liv_search_result` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `keywords` varchar(150) NOT NULL COMMENT '最多搜索50个字',
  `search_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_settings`
--

CREATE TABLE IF NOT EXISTS `liv_settings` (
  `k` varchar(32) NOT NULL DEFAULT '',
  `v` text NOT NULL,
  PRIMARY KEY (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_sqlcache`
--

CREATE TABLE IF NOT EXISTS `liv_sqlcache` (
  `sqlid` char(6) NOT NULL DEFAULT '',
  `data` char(100) NOT NULL,
  `expiry` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sqlid`),
  KEY `expiry` (`expiry`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_statistics`
--

CREATE TABLE IF NOT EXISTS `liv_statistics` (
  `user_id` int(10) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `statistic_num` int(10) NOT NULL COMMENT '总数',
  `statistic_type` tinyint(2) NOT NULL COMMENT '统计类型，1：用户粉丝总数统计，2：用户发帖总数，3：用户点滴，4用户视频，',
  `range_type` tinyint(2) NOT NULL COMMENT '排行类型，1：日排行，2：周排行，3：月排行',
  `statistic_time` int(10) NOT NULL COMMENT '统计时间',
  `ranges` int(10) NOT NULL COMMENT '该用户的排名'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户信息统计表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_type`
--

CREATE TABLE IF NOT EXISTS `liv_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `host` varchar(50) NOT NULL,
  `apidir` varchar(60) NOT NULL,
  `function` varchar(10) NOT NULL,
  `param` varchar(10) NOT NULL,
  `show_api` char(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_user_set`
--

CREATE TABLE IF NOT EXISTS `liv_user_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户设置id',
  `name` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '用户设置名称',
  `identi` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '用户设置的标识',
  `status` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '用户设置的值',
  `descripion` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户设置的描述',
  `creattime` int(10) NOT NULL DEFAULT '0' COMMENT '用户设置的创建时间',
  `updatetime` int(10) NOT NULL COMMENT '用户设置的更该时间',
  `creator` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '用户设置是由谁创建',
  `style` tinyint(1) NOT NULL COMMENT '对应显示的样式,1代表单选框，2代表文本框，3代表单行文本',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `name_2` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_user_statistic`
--

CREATE TABLE IF NOT EXISTS `liv_user_statistic` (
  `id` int(10) NOT NULL COMMENT '自增id',
  `total_num` int(10) NOT NULL COMMENT '用户总数',
  `today_new` int(10) NOT NULL COMMENT '今日新增用户数目',
  `create_time` int(10) NOT NULL COMMENT '统计时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='网站用户总数及每日新增数目统计表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_user_status_change`
--

CREATE TABLE IF NOT EXISTS `liv_user_status_change` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `before_group_id` tinyint(3) NOT NULL DEFAULT '1',
  `after_group_id` tinyint(3) NOT NULL DEFAULT '1',
  `ip` char(24) NOT NULL,
  `time` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_vars`
--

CREATE TABLE IF NOT EXISTS `liv_vars` (
  `name` char(32) NOT NULL DEFAULT '',
  `value` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`name`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_verify_code`
--

CREATE TABLE IF NOT EXISTS `liv_verify_code` (
  `user_id` int(10) NOT NULL,
  `user_name` char(30) CHARACTER SET utf8 NOT NULL,
  `verify_code` char(20) CHARACTER SET utf8 NOT NULL,
  `verify_send_time` int(10) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
