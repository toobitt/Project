-- phpMyAdmin SQL Dump
-- version 3.4.3.1
-- http://www.phpmyadmin.net
--
-- 主机: 10.0.1.31
-- 生成日期: 2012 年 07 月 06 日 15:53
-- 服务器版本: 5.5.9
-- PHP 版本: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `dev_sns_video`
--

-- --------------------------------------------------------

--
-- 表的结构 `liv_advertising`
--

CREATE TABLE IF NOT EXISTS `liv_advertising` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mark` varchar(33) NOT NULL COMMENT '广告标识',
  `name` varchar(20) NOT NULL,
  `content` varchar(4000) NOT NULL,
  `create_time` int(10) NOT NULL,
  `state` tinyint(1) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_advert_video`
--

CREATE TABLE IF NOT EXISTS `liv_advert_video` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `page_id` int(10) NOT NULL,
  `adver_id` int(10) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_album`
--

CREATE TABLE IF NOT EXISTS `liv_album` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `cover_id` int(10) NOT NULL,
  `sort_id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL,
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  `brief` varchar(500) NOT NULL,
  `video_count` int(10) NOT NULL,
  `collect_count` int(10) NOT NULL,
  `comment_count` int(10) NOT NULL,
  `play_count` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_album_video`
--

CREATE TABLE IF NOT EXISTS `liv_album_video` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `album_id` int(10) NOT NULL,
  `video_id` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=201 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_app`
--

CREATE TABLE IF NOT EXISTS `liv_app` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL COMMENT '名称',
  `bundle` varchar(20) NOT NULL COMMENT '标识',
  `father` int(10) NOT NULL DEFAULT '0' COMMENT '父级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_app_settings`
--

CREATE TABLE IF NOT EXISTS `liv_app_settings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '应用名',
  `bundle_id` varchar(20) NOT NULL COMMENT '应用标识',
  `module_id` varchar(30) NOT NULL COMMENT '模块标识',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '数据类型0=>全局配置，1=> 数据库配置，2= 常量配置',
  `var_name` varchar(20) NOT NULL COMMENT '变量名',
  `value` varchar(1000) NOT NULL COMMENT '值',
  `is_edit` tinyint(1) NOT NULL COMMENT '可否编辑',
  `is_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_collects`
--

CREATE TABLE IF NOT EXISTS `liv_collects` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `cid` int(10) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0视频、1网台、2用户',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=75 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_comments`
--

CREATE TABLE IF NOT EXISTS `liv_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `content` text NOT NULL,
  `ip` char(64) NOT NULL,
  `reply_id` int(10) NOT NULL,
  `reply_user_id` int(10) NOT NULL,
  `create_time` int(10) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '（0视频、1网台、2用户）',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=203 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_content`
--

CREATE TABLE IF NOT EXISTS `liv_content` (
  `id` int(10) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_log`
--

CREATE TABLE IF NOT EXISTS `liv_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `script_name` char(30) NOT NULL,
  `action` char(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `extra_content` int(11) NOT NULL COMMENT '操作前的数据信息，记录变化的部分',
  `ip` char(64) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_network_programme`
--

CREATE TABLE IF NOT EXISTS `liv_network_programme` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `sta_id` int(10) NOT NULL,
  `video_id` int(12) NOT NULL,
  `programe_name` varchar(30) NOT NULL,
  `brief` varchar(500) NOT NULL,
  `start_time` int(10) NOT NULL,
  `end_time` int(10) NOT NULL,
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  `order_id` int(10) NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=429 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_network_station`
--

CREATE TABLE IF NOT EXISTS `liv_network_station` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `web_station_name` varchar(30) NOT NULL,
  `tags` varchar(500) NOT NULL COMMENT '以“,”隔开',
  `brief` varchar(500) NOT NULL,
  `logo` char(10) NOT NULL,
  `collect_count` int(10) NOT NULL DEFAULT '0',
  `comment_count` int(10) NOT NULL DEFAULT '0',
  `click_count` int(10) NOT NULL,
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  `programe` varchar(10000) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0: 待审核 1:通过 2:不通过 默认为0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=73 ;

--
-- 触发器 `liv_network_station`
--
DROP TRIGGER IF EXISTS `add_delete_station_data_record`;
DELIMITER //
CREATE TRIGGER `add_delete_station_data_record` AFTER DELETE ON `liv_network_station`
 FOR EACH ROW begin
insert into liv_record_delete(tid,member_id,table_name,delete_time) values

(old.id,old.user_id,'liv_network_station',now());
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_program_history`
--

CREATE TABLE IF NOT EXISTS `liv_program_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sta_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `programe` text NOT NULL,
  `update_time` int(10) NOT NULL,
  `ip` char(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=402 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_recommend`
--

CREATE TABLE IF NOT EXISTS `liv_recommend` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL COMMENT '推荐的对象',
  `type` tinyint(1) NOT NULL COMMENT '（0视频、1网台、2用户）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_record_delete`
--

CREATE TABLE IF NOT EXISTS `liv_record_delete` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tid` int(10) NOT NULL COMMENT '删除表中的主键',
  `member_id` int(10) NOT NULL COMMENT '删除表中对应的用户',
  `table_name` varchar(50) NOT NULL COMMENT '删除的表名',
  `delete_time` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_settings`
--

CREATE TABLE IF NOT EXISTS `liv_settings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '数据类型0=>全局配置，1=> 数据库配置，2= 常量配置',
  `var_name` varchar(20) NOT NULL COMMENT '变量名',
  `value` varchar(1000) NOT NULL COMMENT '值',
  `description` varchar(500) NOT NULL COMMENT '描述',
  `is_edit` tinyint(1) NOT NULL COMMENT '可否编辑',
  `is_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '可否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_station_concern`
--

CREATE TABLE IF NOT EXISTS `liv_station_concern` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sid` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `create_time` int(10) NOT NULL COMMENT '关注的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=235 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_sv_statistic`
--

CREATE TABLE IF NOT EXISTS `liv_sv_statistic` (
  `id` int(10) NOT NULL COMMENT '类型：1：网台，2：网台中的视频',
  `total_num` int(10) NOT NULL COMMENT '总数',
  `today_num` int(10) NOT NULL COMMENT '今日新增数',
  `create_time` int(10) NOT NULL COMMENT '统计时间',
  PRIMARY KEY (`id`,`create_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网台或网台中视频的总数及今日新增数目统计';

-- --------------------------------------------------------

--
-- 表的结构 `liv_tags`
--

CREATE TABLE IF NOT EXISTS `liv_tags` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tagname` varchar(30) NOT NULL,
  `tag_count` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tagname` (`tagname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1310 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_user`
--

CREATE TABLE IF NOT EXISTS `liv_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(120) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` char(32) NOT NULL,
  `salt` char(6) NOT NULL,
  `avatar` char(10) NOT NULL,
  `collect_count` int(10) NOT NULL DEFAULT '0',
  `comment_count` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  `register_time` int(10) NOT NULL,
  `ip` char(64) NOT NULL,
  `member_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7092 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_user_extra`
--

CREATE TABLE IF NOT EXISTS `liv_user_extra` (
  `user_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_user_group`
--

CREATE TABLE IF NOT EXISTS `liv_user_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(30) NOT NULL,
  `groupdesc` varchar(120) NOT NULL,
  ` user_count` int(10) NOT NULL,
  `prms` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_vedioclick_s`
--

CREATE TABLE IF NOT EXISTS `liv_vedioclick_s` (
  `vid` int(12) NOT NULL COMMENT '视频id',
  `v_title` varchar(255) NOT NULL COMMENT '视频标题',
  `user_id` int(10) NOT NULL COMMENT '上传视频的用户uid',
  `user_name` varchar(255) NOT NULL COMMENT '上传视频的用户名',
  `click_count` int(10) NOT NULL COMMENT '点击量',
  `create_time` int(10) NOT NULL COMMENT '统计时间',
  `range_num` int(4) NOT NULL COMMENT '视频排名',
  PRIMARY KEY (`create_time`,`range_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网台中视频点击量排行统计表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_video`
--

CREATE TABLE IF NOT EXISTS `liv_video` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `sort_id` int(10) NOT NULL,
  `title` varchar(120) NOT NULL,
  `brief` varchar(500) DEFAULT NULL,
  `tags` varchar(420) DEFAULT NULL,
  `schematic` varchar(300) NOT NULL COMMENT '视频小图',
  `bschematic` varchar(300) NOT NULL COMMENT '视频大图',
  `filename` char(30) NOT NULL,
  `streaming_media` varchar(300) NOT NULL,
  `toff` int(10) NOT NULL,
  `copyright` tinyint(1) NOT NULL,
  `collect_count` int(10) NOT NULL,
  `comment_count` int(10) NOT NULL,
  `play_count` int(10) NOT NULL,
  `click_count` int(10) NOT NULL,
  `is_top` tinyint(1) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0：转码中 1：转码完成 默认为0',
  `bans` varchar(400) NOT NULL COMMENT '屏蔽字',
  `ip` char(64) NOT NULL,
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:待审核 1:未通过 2:已发布 默认为0',
  `serve_id` int(10) NOT NULL COMMENT '服务器上的ID',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0',
  `is_thread` tinyint(1) DEFAULT '0' COMMENT '判断是否发布到讨论区',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=976 ;

--
-- 触发器 `liv_video`
--
DROP TRIGGER IF EXISTS `add_delete_video_data_record`;
DELIMITER //
CREATE TRIGGER `add_delete_video_data_record` AFTER DELETE ON `liv_video`
 FOR EACH ROW begin
insert into liv_record_delete(tid,member_id,table_name,delete_time) values

(old.id,old.user_id,'liv_video',now());
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_video_tags`
--

CREATE TABLE IF NOT EXISTS `liv_video_tags` (
  `video_id` int(12) NOT NULL,
  `tag_id` int(10) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '(0视频、1网台、2用户)',
  PRIMARY KEY (`video_id`,`tag_id`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_visit_history`
--

CREATE TABLE IF NOT EXISTS `liv_visit_history` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '(1-视频，2-网台)',
  `visit_time` int(10) NOT NULL,
  `ip` char(64) NOT NULL,
  PRIMARY KEY (`user_id`,`cid`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='历史访问记录' AUTO_INCREMENT=7092 ;

-- --------------------------------------------------------

--
-- 表的结构 `sta_user`
--

CREATE TABLE IF NOT EXISTS `sta_user` (
  `user_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `video_count`
--

CREATE TABLE IF NOT EXISTS `video_count` (
  `uid` int(10) NOT NULL,
  `count` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
