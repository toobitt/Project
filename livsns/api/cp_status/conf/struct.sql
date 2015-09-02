-- phpMyAdmin SQL Dump
-- version 3.4.3.1
-- http://www.phpmyadmin.net
--
-- 主机: 10.0.1.31
-- 生成日期: 2012 年 07 月 03 日 16:46
-- 服务器版本: 5.5.9
-- PHP 版本: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `dev_sns_mblog`
--

-- --------------------------------------------------------

--
-- 表的结构 `liv_keywords`
--

CREATE TABLE IF NOT EXISTS `liv_keywords` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(20) NOT NULL,
  `count` int(10) NOT NULL,
  `result_count` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`),
  UNIQUE KEY `keyword_2` (`keyword`),
  UNIQUE KEY `keyword_3` (`keyword`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=145 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_media`
--

CREATE TABLE IF NOT EXISTS `liv_media` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '媒体信息id 标识 媒体信息表liv_media',
  `status_id` int(10) NOT NULL COMMENT '微博id status_id 属于 媒体信息表liv_media',
  `type` tinyint(1) NOT NULL COMMENT '图片，视频',
  `source` tinyint(1) NOT NULL COMMENT '来源 source 属于 媒体信息表liv_media',
  `title` varchar(120) NOT NULL,
  `link` varchar(120) NOT NULL,
  `img` varchar(120) NOT NULL,
  `dir` varchar(20) NOT NULL,
  `url` varchar(120) NOT NULL COMMENT '信息地址 url 属于 媒体信息表liv_media',
  `ip` char(24) NOT NULL COMMENT '上传ip 属于 媒体信息表liv_media',
  `create_at` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status_id` (`status_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='媒体信息表' AUTO_INCREMENT=758 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_member_session`
--

CREATE TABLE IF NOT EXISTS `liv_member_session` (
  `member_id` int(10) NOT NULL COMMENT '用户id memberid 标识 在线用户表liv_member_session',
  `username` char(30) NOT NULL COMMENT '用户名 username 属于 在线用户表liv_member_session',
  `last_activity` int(10) NOT NULL COMMENT '最后活动时间 last_activity 属于 在线用户表liv_member_session',
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='在线用户表';

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
-- 表的结构 `liv_record_delete`
--

CREATE TABLE IF NOT EXISTS `liv_record_delete` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL COMMENT '原始表中的数据ID',
  `member_id` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `table_name` varchar(30) DEFAULT NULL COMMENT '删除表的名称',
  `table_identify` int(4) NOT NULL DEFAULT '-1' COMMENT '与举报表中的type字段一致',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='记录删除的数据 触发器自动添加记录' AUTO_INCREMENT=209 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_report`
--

CREATE TABLE IF NOT EXISTS `liv_report` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL,
  `uid` int(10) NOT NULL COMMENT '被举报人',
  `user_id` int(10) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '举报内容类型：0:举报地盘,1：帖子，2：视频，3：微博，4：相册，5：视频评论，6：照片，7：帖子回复，8：微博评论，9：频道，10：用户，11：频道评论,12:活动',
  `url` varchar(300) NOT NULL COMMENT '举报地址',
  `content` varchar(500) NOT NULL,
  `create_time` int(10) NOT NULL,
  `ip` char(64) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0--删除 1--存在',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='举报信息' AUTO_INCREMENT=39 ;

--
-- 触发器 `liv_report`
--
DROP TRIGGER IF EXISTS `adrecord_delete_report_data`;
DELIMITER //
CREATE TRIGGER `adrecord_delete_report_data` AFTER DELETE ON `liv_report`
 FOR EACH ROW begin
insert into liv_record_delete(tid,member_id,table_name,time,table_identify) values(old.id,old.uid,null,now(),old.type);
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_settings`
--

CREATE TABLE IF NOT EXISTS `liv_settings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `mark` varchar(30) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `last_time` int(10) NOT NULL,
  `ip` char(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_status`
--

CREATE TABLE IF NOT EXISTS `liv_status` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '微博id 标识 微博客表liv_status',
  `member_id` int(10) NOT NULL COMMENT '用户id memberid 属于 微博客表liv_status',
  `pic` tinyint(1) NOT NULL DEFAULT '0',
  `video` tinyint(1) NOT NULL DEFAULT '0',
  `text` varchar(2000) NOT NULL COMMENT '微博内容 status 属于 微博客表liv_status',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 status 0-默认， 1-屏蔽',
  `reply_status_id` int(10) NOT NULL COMMENT '回复id reply_status_id 属于 微博客表liv_status',
  `reply_user_id` int(10) NOT NULL COMMENT '回复用户id reply_user_id 属于 微博客表liv_status',
  `source` varchar(200) NOT NULL COMMENT '来源 source 属于 微博客表liv_status',
  `create_at` int(10) NOT NULL COMMENT '创建时间 created_at 属于 微博客表liv_status',
  `ip` varchar(64) NOT NULL COMMENT '发布者ip ip 属于 微博客表liv_status',
  `medias` varchar(500) NOT NULL COMMENT '媒体信息 medias 属于 微博客表liv_status',
  `location` char(30) NOT NULL COMMENT '地理位置 location 属于 微博客表liv_status',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `lat` float(17,14) NOT NULL COMMENT 'latitude',
  `lng` float(17,14) NOT NULL COMMENT 'lng',
  `bans` varchar(300) NOT NULL COMMENT '含有的屏蔽字',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='微博客表' AUTO_INCREMENT=75687 ;

--
-- 触发器 `liv_status`
--
DROP TRIGGER IF EXISTS `adrecord_delete_status_data`;
DELIMITER //
CREATE TRIGGER `adrecord_delete_status_data` AFTER DELETE ON `liv_status`
 FOR EACH ROW begin
insert into liv_record_delete(tid,member_id,table_name,time) values(old.id,old.member_id,'liv_status',now());
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_status_comments`
--

CREATE TABLE IF NOT EXISTS `liv_status_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '评论id 标识 微博评论表liv_status_comments',
  `status_id` int(10) NOT NULL COMMENT '微博status_id 属于 微博评论表liv_status_comments',
  `member_id` int(10) NOT NULL COMMENT '用户id memberid 属于 微博评论表liv_status_comments',
  `content` varchar(420) NOT NULL COMMENT '评论内容 content 属于 微博评论表liv_status_comments',
  `comment_time` int(10) NOT NULL COMMENT '评论时间 comment_time 属于 微博评论表liv_status_comments',
  `ip` char(24) NOT NULL COMMENT '评论ip ip 属于 微博评论表liv_status_comments',
  `reply_comment_id` int(10) NOT NULL COMMENT '回复评论id reply_comment_id 属于 微博评论表liv_status_comments',
  `reply_member_id` int(10) NOT NULL COMMENT '评论的用户 reply_member_id 属于 微博评论表liv_status_comments',
  `flag` tinyint(1) NOT NULL COMMENT '删除标记，0：未删除；1：已删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='微博评论表' AUTO_INCREMENT=1038 ;

--
-- 触发器 `liv_status_comments`
--
DROP TRIGGER IF EXISTS `adrecord_delete_comments_data`;
DELIMITER //
CREATE TRIGGER `adrecord_delete_comments_data` AFTER DELETE ON `liv_status_comments`
 FOR EACH ROW begin
insert into liv_record_delete(tid,member_id,time,table_name) values(old.id,old.member_id,now(),'liv_status_comments');
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_status_extra`
--

CREATE TABLE IF NOT EXISTS `liv_status_extra` (
  `status_id` int(10) NOT NULL COMMENT '微博id status_id 标识 微博扩展表liv_status_extra',
  `transmit_count` int(10) NOT NULL COMMENT '转发次数 transmit_count 属于 微博扩展表liv_status_extra',
  `reply_count` int(10) NOT NULL COMMENT '回复次数 reply_count 属于 微博扩展表liv_status_extra',
  `comment_count` int(10) NOT NULL COMMENT '评论次数 comment_count 属于 微博扩展表liv_status_extra',
  PRIMARY KEY (`status_id`),
  KEY `transmit_count` (`transmit_count`,`reply_count`),
  KEY `comment_count` (`comment_count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微博扩展表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_status_favorites`
--

CREATE TABLE IF NOT EXISTS `liv_status_favorites` (
  `status_id` int(10) NOT NULL COMMENT '微博id status_id 标识 微博收藏表liv_status_favorites',
  `member_id` int(10) NOT NULL COMMENT '用户id member_id 部分标识 微博收藏表liv_status_favorites',
  `favorite_time` int(10) NOT NULL COMMENT '收藏时间 favorite_time 属于 微博收藏表liv_status_favorites',
  PRIMARY KEY (`status_id`,`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微博收藏表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_status_member`
--

CREATE TABLE IF NOT EXISTS `liv_status_member` (
  `status_id` int(10) NOT NULL COMMENT '话题id topic_id 标识 微博相关话题表liv_statu',
  `member_id` int(10) NOT NULL COMMENT '微博id status_id 部分标识 微博相关话题表liv_status_topic',
  PRIMARY KEY (`status_id`,`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微博信息相关用户表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_status_read`
--

CREATE TABLE IF NOT EXISTS `liv_status_read` (
  `member_id` int(10) NOT NULL COMMENT '用户id memberid 标识 信息阅读偏移表liv_status_read',
  `status_id` int(10) NOT NULL COMMENT '微博id status_id 部分标识 信息阅读偏移表liv_status_read',
  `read_time` int(10) NOT NULL COMMENT '阅读时间 read_time 属于 信息阅读偏移表liv_status_read',
  PRIMARY KEY (`member_id`,`status_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='信息阅读偏移表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_status_statistic`
--

CREATE TABLE IF NOT EXISTS `liv_status_statistic` (
  `id` int(10) NOT NULL COMMENT '自增id',
  `total_num` int(10) NOT NULL COMMENT '总数',
  `today_new` int(10) NOT NULL COMMENT '今日新增',
  `create_time` int(10) NOT NULL COMMENT '统计时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='微博总数及每日新增数目统计表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_status_topic`
--

CREATE TABLE IF NOT EXISTS `liv_status_topic` (
  `topic_id` int(10) NOT NULL COMMENT '话题id topic_id 标识 微博相关话题表liv_status_topic',
  `status_id` int(10) NOT NULL COMMENT '微博id status_id 部分标识 微博相关话题表liv_status_topic',
  PRIMARY KEY (`topic_id`,`status_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微博相关话题表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_topic`
--

CREATE TABLE IF NOT EXISTS `liv_topic` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '话题id 标识 话题表liv_topic',
  `title` char(60) NOT NULL COMMENT '话题标题 title 部分标识 话题表liv_topic',
  `relate_count` int(10) NOT NULL COMMENT '话题相关数 relate_count 属于 话题表liv_topic',
  `status` tinyint(1) NOT NULL COMMENT '0 - 默认 1 - 关闭',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1535 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_topic_member`
--

CREATE TABLE IF NOT EXISTS `liv_topic_member` (
  `member_id` int(10) NOT NULL,
  `topic_id` int(10) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`member_id`,`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
