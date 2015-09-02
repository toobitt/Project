-- phpMyAdmin SQL Dump
-- version 3.4.3.1
-- http://www.phpmyadmin.net
--
-- 主机: 10.0.1.31
-- 生成日期: 2012 年 07 月 03 日 16:51
-- 服务器版本: 5.5.9
-- PHP 版本: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `community_www`
--

-- --------------------------------------------------------

--
-- 表的结构 `liv_action_apply`
--

CREATE TABLE IF NOT EXISTS `liv_action_apply` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '活动报名表的主键',
  `action_id` int(10) NOT NULL COMMENT '活动主键',
  `user_id` int(10) NOT NULL COMMENT '用户关联标识',
  `action_name` varchar(200) NOT NULL COMMENT '活动名称',
  `user_name` varchar(200) NOT NULL COMMENT '用户名称',
  `apply_time` int(10) NOT NULL COMMENT '申请的时间',
  `apply_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:为待审核；2:为审核通过；3,为审核未通过',
  `pay_method` varchar(50) NOT NULL COMMENT '支付方式.1：为自行支付；2:为愿意承担费用状态',
  `leave_words` varchar(255) DEFAULT NULL COMMENT '申请时的留言',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_action_colloct`
--

CREATE TABLE IF NOT EXISTS `liv_action_colloct` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `action_id` int(10) NOT NULL COMMENT '活动标识',
  `user_id` int(10) NOT NULL COMMENT '收藏者',
  `create_at` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动收藏' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_action_thread`
--

CREATE TABLE IF NOT EXISTS `liv_action_thread` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '发起活动的主键',
  `user_id` int(10) NOT NULL COMMENT '用户关联标识',
  `user_name` varchar(100) NOT NULL COMMENT '发起活动的名字',
  `action_name` varchar(100) NOT NULL COMMENT '活动的主题',
  `action_sort` tinyint(1) DEFAULT '0' COMMENT '活动种类（0：线上，1：线下）',
  `action_type` varchar(200) DEFAULT NULL COMMENT '活动类型',
  `action_img` varchar(100) NOT NULL COMMENT '活动图标',
  `start_time` int(10) NOT NULL COMMENT '活动的开始时间',
  `end_time` int(10) NOT NULL COMMENT '活动的截止时间',
  `place` varchar(200) NOT NULL COMMENT '活动的具体地点',
  `need_pay` tinyint(1) NOT NULL COMMENT '需要花销',
  `need_num` smallint(5) NOT NULL COMMENT '活动人数的上限',
  `yet_join` smallint(5) NOT NULL DEFAULT '0' COMMENT '已经通过审核的人数',
  `apply_num` smallint(5) NOT NULL DEFAULT '0' COMMENT '已经申请的人数',
  `collect_num` int(11) DEFAULT '0' COMMENT '收藏次数',
  `sex` tinyint(2) NOT NULL DEFAULT '0' COMMENT '对性别的要求,0:不限，1：男，2：女',
  `desc` varchar(255) NOT NULL COMMENT '对活动的描述',
  `contact` tinyint(1) DEFAULT '0' COMMENT '对联系方式的要求 0：不要，1：要',
  `rights` tinyint(1) DEFAULT '0' COMMENT '对权限审核的要求 0：不要，1：要',
  `concern` tinyint(4) DEFAULT '0' COMMENT '对于是否关注该地盘（活动发起者） 0：不要，1：要',
  `edit_count` tinyint(1) DEFAULT '0' COMMENT '编辑次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_ads`
--

CREATE TABLE IF NOT EXISTS `liv_ads` (
  `ad_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '广告ID',
  `order_id` int(10) NOT NULL COMMENT '排序',
  `user_id` int(10) NOT NULL COMMENT '广告发布人ID',
  `user_name` char(18) NOT NULL COMMENT '广告发布人昵称',
  `title` varchar(60) NOT NULL COMMENT '广告标题',
  `content` varchar(1000) NOT NULL COMMENT '广告描述',
  `linkurl` varchar(60) NOT NULL COMMENT '广告链接',
  `source_path` varchar(60) NOT NULL COMMENT '广告资源路径',
  `source_name` varchar(32) NOT NULL COMMENT '资源文件名',
  `source_width` smallint(4) NOT NULL COMMENT '资源宽度',
  `source_height` smallint(4) NOT NULL COMMENT '资源高度',
  `type` tinyint(1) NOT NULL COMMENT '广告类型',
  `ad_pos` tinyint(1) NOT NULL COMMENT '广告位置',
  `start_time` int(10) NOT NULL COMMENT '开始时间',
  `end_time` int(10) NOT NULL COMMENT '结束时间',
  `state` tinyint(1) NOT NULL COMMENT '是否审核',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `click_count` int(10) NOT NULL COMMENT '点击数',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_albums`
--

CREATE TABLE IF NOT EXISTS `liv_albums` (
  `albums_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `cat_fatherid` char(20) NOT NULL,
  `albums_category_id` char(20) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员名',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '会员昵称',
  `albums_name` varchar(255) NOT NULL DEFAULT '' COMMENT '相册名',
  `albums_cover` int(10) NOT NULL DEFAULT '0' COMMENT '相册封面',
  `cover_file_name` varchar(255) NOT NULL COMMENT '封面文件名',
  `albums_password` varchar(32) NOT NULL DEFAULT '0' COMMENT '相册密码',
  `albums_description` varchar(255) NOT NULL DEFAULT '' COMMENT '相册描述',
  `picture_count` int(10) NOT NULL DEFAULT '0' COMMENT '相册图片数',
  `comment_count` int(10) NOT NULL DEFAULT '0' COMMENT '相册评论数',
  `visit_level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '相册访问级别',
  `visit_count` int(10) NOT NULL DEFAULT '0' COMMENT '相册访问数',
  `show_in_home` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否在首页显示',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '相册创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '相册更新时间',
  `school_id` int(10) NOT NULL DEFAULT '0' COMMENT '学校id',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '相册状态 1：有效',
  `location_id` char(20) NOT NULL COMMENT '所属地区',
  `albums_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '相册类型 -- -1 -- 彩信相册 不可编辑相册名 绑定手机后创建 , 0 默认 照片相册 1 非照片相册(不在好友照片中展示) ',
  `last_pics` varchar(600) NOT NULL COMMENT '最新上传照片',
  `rc_category_id` int(10) NOT NULL DEFAULT '0' COMMENT '推荐类型ID',
  `evaluation` float(4,1) NOT NULL DEFAULT '0.0' COMMENT '评分值',
  `evaluation_num` int(10) NOT NULL DEFAULT '0' COMMENT '评分人数',
  PRIMARY KEY (`albums_id`),
  KEY `albums_type` (`albums_type`),
  KEY `update_time` (`update_time`),
  KEY `rc_category_id` (`rc_category_id`),
  KEY `user_id` (`user_id`,`picture_count`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='相册表' AUTO_INCREMENT=1492 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_albums_category`
--

CREATE TABLE IF NOT EXISTS `liv_albums_category` (
  `albums_category_id` char(20) NOT NULL,
  `name` char(32) NOT NULL DEFAULT '',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `albums_count` int(10) NOT NULL COMMENT '相册数',
  PRIMARY KEY (`albums_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_announcement`
--

CREATE TABLE IF NOT EXISTS `liv_announcement` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_id` int(10) NOT NULL COMMENT '群组id',
  `title` varchar(200) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `user_id` int(10) NOT NULL COMMENT '发布人',
  `views` int(10) NOT NULL COMMENT '查看数',
  `start_date` int(10) NOT NULL COMMENT '开始时间',
  `end_date` int(10) NOT NULL COMMENT '结束时间',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `pub_date` int(10) NOT NULL DEFAULT '0',
  `user_name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pub_date` (`pub_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_append_category`
--

CREATE TABLE IF NOT EXISTS `liv_append_category` (
  `category_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `category_name` varchar(30) NOT NULL COMMENT '分类名称',
  `content_count` int(10) NOT NULL COMMENT '对应内容数',
  `orderid` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '分类类型,0-礼物分类',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_apply_domain`
--

CREATE TABLE IF NOT EXISTS `liv_apply_domain` (
  `apply_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `domain_id` int(10) NOT NULL,
  `vipno` varchar(32) NOT NULL DEFAULT ' ',
  `name` varchar(100) NOT NULL DEFAULT ' ',
  `email` varchar(60) NOT NULL DEFAULT ' ',
  `phone` varchar(20) NOT NULL DEFAULT ' ',
  `idcard` char(25) NOT NULL DEFAULT '',
  `dept` varchar(200) NOT NULL DEFAULT ' ',
  `location_id` char(20) NOT NULL DEFAULT '',
  `addr` varchar(200) NOT NULL DEFAULT ' ',
  `mobile` varchar(20) NOT NULL DEFAULT ' ',
  `state` tinyint(1) NOT NULL,
  `enname` varchar(100) NOT NULL DEFAULT '',
  `applydate` int(10) NOT NULL,
  `backtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`apply_id`),
  KEY `user_id` (`user_id`),
  KEY `domain_id` (`domain_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_apps`
--

CREATE TABLE IF NOT EXISTS `liv_apps` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  `link` varchar(32) NOT NULL,
  `app_var` varchar(30) NOT NULL,
  `class` varchar(32) NOT NULL,
  `group_type` int(10) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `order` int(10) NOT NULL DEFAULT '0',
  `close_reason` varchar(300) NOT NULL COMMENT '关闭原因',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_badword`
--

CREATE TABLE IF NOT EXISTS `liv_badword` (
  `badwordid` int(10) NOT NULL AUTO_INCREMENT,
  `badbefore` varchar(255) NOT NULL,
  `badafter` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `addtime` int(10) NOT NULL,
  PRIMARY KEY (`badwordid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=232 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_bind`
--

CREATE TABLE IF NOT EXISTS `liv_bind` (
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `check_code` char(4) NOT NULL DEFAULT '0' COMMENT '唯一序号',
  `adddate` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`user_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='用户绑定临时表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_blog`
--

CREATE TABLE IF NOT EXISTS `liv_blog` (
  `blog_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `blog_title` varchar(255) NOT NULL COMMENT '日志标题',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '会员昵称',
  `blog_description` varchar(3000) NOT NULL DEFAULT '' COMMENT '日志描述',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '写日志的人id',
  `school_id` int(10) NOT NULL DEFAULT '0' COMMENT '学校id',
  `click_count` int(10) NOT NULL DEFAULT '0' COMMENT '点击数',
  `comment_count` int(10) NOT NULL DEFAULT '0' COMMENT '评论数',
  `category_id` int(10) NOT NULL DEFAULT '0' COMMENT '日志分类id',
  `blog_pub_time` int(10) NOT NULL DEFAULT '0' COMMENT '日志发布时间',
  `blog_update_time` int(10) NOT NULL DEFAULT '0' COMMENT '日志修改时间',
  `blog_reply_time` int(10) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 1：有效',
  `from_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '当时客户端地址',
  `location_id` char(20) NOT NULL DEFAULT '' COMMENT '所属地区',
  `prms` tinyint(1) NOT NULL DEFAULT '0' COMMENT '日志权限 0 =>公开 1 =>好友  2=>私密 ',
  `sticky` tinyint(1) NOT NULL DEFAULT '0',
  `is_sys` tinyint(1) NOT NULL DEFAULT '0',
  `blog_sys_cat_id` int(10) NOT NULL DEFAULT '0' COMMENT '日志系统分类',
  `poll_id` int(10) NOT NULL DEFAULT '0' COMMENT '投票ID',
  `issuance_spaces` varchar(255) NOT NULL DEFAULT '' COMMENT '已经发布到的空间！',
  `evaluation` float(4,1) NOT NULL DEFAULT '0.0' COMMENT '被评总分',
  `evaluation_num` int(10) NOT NULL DEFAULT '0' COMMENT '被评总次数',
  `rc_category_id` int(10) NOT NULL DEFAULT '0' COMMENT '推荐类型ID',
  `contain_img` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否包含图片',
  `contain_media` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否包含视频',
  `localized` tinyint(1) NOT NULL DEFAULT '1' COMMENT '本地化的',
  `source_link` varchar(500) NOT NULL COMMENT '源链接',
  `from_type` int(10) NOT NULL COMMENT '来源',
  PRIMARY KEY (`blog_id`),
  KEY `category_id` (`category_id`),
  KEY `user_id` (`user_id`),
  KEY `sticky` (`sticky`),
  KEY `blog_sys_cat_id` (`blog_sys_cat_id`),
  KEY `poll_id` (`poll_id`),
  KEY `rc_category_id` (`rc_category_id`),
  KEY `blog_pub_time` (`blog_pub_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='日志表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_blog_category`
--

CREATE TABLE IF NOT EXISTS `liv_blog_category` (
  `category_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '日志分类id',
  `category_name` varchar(255) NOT NULL DEFAULT '' COMMENT '日志分类名',
  `blog_count` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员id',
  PRIMARY KEY (`category_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='日志类型' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_blog_content`
--

CREATE TABLE IF NOT EXISTS `liv_blog_content` (
  `blog_content_id` int(10) NOT NULL AUTO_INCREMENT,
  `blog_id` int(10) NOT NULL DEFAULT '0' COMMENT '日志id',
  `blog_content` mediumtext NOT NULL,
  PRIMARY KEY (`blog_content_id`),
  KEY `blog_id` (`blog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='日志内容表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_blog_group`
--

CREATE TABLE IF NOT EXISTS `liv_blog_group` (
  `blog_club_id` int(10) NOT NULL AUTO_INCREMENT,
  `blog_id` int(10) NOT NULL DEFAULT '0',
  `group_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`blog_club_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_blog_save`
--

CREATE TABLE IF NOT EXISTS `liv_blog_save` (
  `sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `save_hash` int(10) NOT NULL,
  `blog_title` varchar(255) NOT NULL,
  `user_id` int(10) NOT NULL,
  `category_id` int(10) NOT NULL,
  `save_time` int(10) NOT NULL,
  `blog_content` mediumtext NOT NULL,
  `prms` tinyint(1) NOT NULL,
  PRIMARY KEY (`sid`),
  KEY `save_hash` (`save_hash`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_blog_sys_cat`
--

CREATE TABLE IF NOT EXISTS `liv_blog_sys_cat` (
  `blog_sys_cat_id` int(10) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `blog_count` int(10) NOT NULL,
  PRIMARY KEY (`blog_sys_cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_bulletin`
--

CREATE TABLE IF NOT EXISTS `liv_bulletin` (
  `bulletin_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `group_id` int(10) NOT NULL DEFAULT '0',
  `space_id` int(10) NOT NULL DEFAULT '0',
  `group_name` varchar(60) NOT NULL DEFAULT '',
  `bulletin_time` int(10) NOT NULL DEFAULT '0',
  `bulletin_ip` varchar(30) NOT NULL DEFAULT '',
  `content` varchar(10000) NOT NULL DEFAULT '',
  `reply` varchar(10000) NOT NULL COMMENT '主人回复',
  `touser_id` int(10) NOT NULL DEFAULT '0',
  `primary_bulletin_id` int(10) NOT NULL DEFAULT '0',
  `reply_bulletin_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bulletin_id`),
  KEY `bulletin_time` (`bulletin_time`),
  KEY `space_id` (`space_id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  KEY `primary_bulletin_id` (`primary_bulletin_id`),
  KEY `reply_bulletin_id` (`reply_bulletin_id`),
  KEY `touser_id` (`touser_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_category`
--

CREATE TABLE IF NOT EXISTS `liv_category` (
  `category_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'rss分类ID',
  `category_name` varchar(255) NOT NULL COMMENT '分类名',
  `rss_count` int(10) NOT NULL COMMENT 'RSS数',
  `rewen_count` int(10) NOT NULL COMMENT '热文数',
  `update_time` int(10) NOT NULL COMMENT '更新时间',
  `rss_sys_cat` tinyint(1) NOT NULL DEFAULT '0',
  `category_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `category_type` (`category_type`),
  KEY `rss_sys_cat` (`rss_sys_cat`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_comment`
--

CREATE TABLE IF NOT EXISTS `liv_comment` (
  `comment_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '谁写的评论，游客呢？',
  `user_name` char(32) NOT NULL DEFAULT '',
  `avatar` tinyint(1) NOT NULL DEFAULT '0',
  `content` text NOT NULL COMMENT '评论内容',
  `pub_time` int(10) NOT NULL DEFAULT '0' COMMENT '评论发布时间',
  `from_ip` varchar(30) NOT NULL DEFAULT '' COMMENT '记录ip',
  `blog_id` int(10) NOT NULL DEFAULT '0' COMMENT '日志id',
  `reply` varchar(10000) NOT NULL COMMENT '主人回复',
  `group_id` int(10) NOT NULL DEFAULT '0',
  `albums_id` int(10) NOT NULL DEFAULT '0' COMMENT '相册id',
  `material_id` int(10) NOT NULL DEFAULT '0' COMMENT '图片id',
  `music_box_id` int(10) NOT NULL DEFAULT '0' COMMENT '音乐盒id',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 1：有效',
  `primary_comment_id` int(10) NOT NULL DEFAULT '0',
  `reply_comment_id` int(10) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '关联类型(1-个人日志 2 个人相册 3 个人照片 4 群组日志 5 群组相册 6 群组照片 7 分享)',
  `id` int(10) NOT NULL DEFAULT '0' COMMENT '关联ID',
  `from_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:网页,1:手机',
  `poll_id` int(10) NOT NULL DEFAULT '0' COMMENT '投票ID',
  PRIMARY KEY (`comment_id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`),
  KEY `id` (`type`,`id`,`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='评论表' AUTO_INCREMENT=176 ;

--
-- 触发器 `liv_comment`
--
DROP TRIGGER IF EXISTS `add_delete_comment_data_record`;
DELIMITER //
CREATE TRIGGER `add_delete_comment_data_record` AFTER DELETE ON `liv_comment`
 FOR EACH ROW begin
insert into liv_record_delete(tid,member_id,table_name,delete_time) values

(old.comment_id,old.user_id,'liv_comment',now());
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_communicate`
--

CREATE TABLE IF NOT EXISTS `liv_communicate` (
  `communicate_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '通信ID',
  `hash` char(15) NOT NULL COMMENT '通信标识',
  `code` char(20) NOT NULL COMMENT '通信号',
  `user_id` int(10) NOT NULL,
  `id` int(10) NOT NULL COMMENT '主内容id',
  `type` tinyint(1) NOT NULL COMMENT '内容类型',
  `from_type` int(10) NOT NULL COMMENT '来自客户端',
  `addtime` int(10) NOT NULL COMMENT '通信建立时间',
  `cmd` char(20) NOT NULL COMMENT '特殊命令',
  `group_id` int(10) NOT NULL COMMENT '圈子ID',
  PRIMARY KEY (`communicate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='与客户端通信表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_company`
--

CREATE TABLE IF NOT EXISTS `liv_company` (
  `company_id` int(10) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(100) NOT NULL,
  `location_id` char(20) NOT NULL,
  `member_count` int(10) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_content_map`
--

CREATE TABLE IF NOT EXISTS `liv_content_map` (
  `map_id` int(10) NOT NULL AUTO_INCREMENT,
  `key_id` int(10) NOT NULL COMMENT '模块主键ID',
  `module` char(30) NOT NULL COMMENT '内容模块',
  `action` char(30) NOT NULL COMMENT '内容链接',
  `is_visiable` tinyint(1) NOT NULL COMMENT '是否可见',
  `type` tinyint(1) NOT NULL COMMENT '类型: 1 - 日志, 2 - 话题, 3 - 照片, 4 -成员， 5- 群组',
  `title` varchar(255) NOT NULL COMMENT '内容标题',
  `full_title` text NOT NULL COMMENT '全文检索数据',
  `group_id` int(10) NOT NULL COMMENT '群组ID',
  `group_name` varchar(100) NOT NULL COMMENT '群组名称',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '加入时间',
  `user_id` int(10) NOT NULL,
  `user_name` char(30) NOT NULL,
  `updatetime` int(10) NOT NULL COMMENT '更新时间',
  `dir` char(20) NOT NULL COMMENT '目录',
  `description` varchar(3000) NOT NULL COMMENT '相关描述及内容',
  `extra` varchar(3000) NOT NULL COMMENT '额外数据',
  PRIMARY KEY (`map_id`),
  KEY `key_id` (`key_id`,`type`),
  KEY `group_id` (`group_id`),
  KEY `addtime` (`addtime`),
  FULLTEXT KEY `full_title` (`full_title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=415 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_domain`
--

CREATE TABLE IF NOT EXISTS `liv_domain` (
  `domain_id` int(10) NOT NULL AUTO_INCREMENT,
  `group_id` varchar(50) NOT NULL DEFAULT '0',
  `domain` varchar(100) NOT NULL,
  `space_id` int(10) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `is_ban` tinyint(1) NOT NULL DEFAULT '1',
  `isuse` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`domain_id`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_email_config`
--

CREATE TABLE IF NOT EXISTS `liv_email_config` (
  `config_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `smtp_host` varchar(60) NOT NULL COMMENT 'SMTP服务器',
  `username` varchar(60) NOT NULL COMMENT '用户名',
  `passwd` varchar(60) NOT NULL COMMENT '密码',
  `postfix` varchar(60) NOT NULL COMMENT '想要显示的邮箱后缀',
  `tls` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否使用TLS(保留字段)',
  `max_time` int(11) NOT NULL DEFAULT '400' COMMENT '最大发送次数',
  `today_time` int(11) NOT NULL DEFAULT '0' COMMENT '今日发送次数',
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_email_queue`
--

CREATE TABLE IF NOT EXISTS `liv_email_queue` (
  `email_id` int(10) NOT NULL AUTO_INCREMENT,
  `email` char(60) NOT NULL COMMENT 'email地址',
  `cc` varchar(20000) NOT NULL COMMENT '抄送',
  `title` varchar(200) NOT NULL COMMENT 'email标题',
  `content` text NOT NULL COMMENT 'email内容',
  `user_id` int(10) NOT NULL COMMENT '发送人id',
  `user_name` char(18) NOT NULL COMMENT '发送人用户名',
  `is_send` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已发送',
  `is_invite` tinyint(1) NOT NULL COMMENT '是否是邀请好友',
  `invite_code` char(20) NOT NULL COMMENT '邀请码',
  `invite_uname` char(18) NOT NULL COMMENT '被邀请人名',
  `send_time` int(10) NOT NULL DEFAULT '0' COMMENT '发送时间',
  `reg_user_id` int(10) NOT NULL,
  PRIMARY KEY (`email_id`),
  KEY `is_invite` (`is_invite`),
  KEY `is_send` (`is_send`),
  KEY `invite_code` (`invite_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮件队列表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_email_verify`
--

CREATE TABLE IF NOT EXISTS `liv_email_verify` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `salt` varchar(32) NOT NULL,
  `email_verify` varchar(32) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_evaluation`
--

CREATE TABLE IF NOT EXISTS `liv_evaluation` (
  `evaluation_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '评价ID',
  `user_id` int(10) NOT NULL COMMENT '评价人',
  `to_user_id` int(10) NOT NULL COMMENT '被评价内容拥有者',
  `id` int(10) NOT NULL COMMENT '被评价内容ID',
  `type` tinyint(1) NOT NULL COMMENT '被评价内容类型',
  `evaluation` float(4,2) NOT NULL DEFAULT '0.00' COMMENT '评价分值',
  `evaluation_time` int(10) NOT NULL COMMENT '评价时间',
  PRIMARY KEY (`evaluation_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_evaluation_type`
--

CREATE TABLE IF NOT EXISTS `liv_evaluation_type` (
  `et_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(30) NOT NULL COMMENT '评价文字',
  `description` varchar(255) NOT NULL COMMENT '评价描述，用于记录评论',
  `icon` varchar(20) NOT NULL COMMENT '图标文件名（存在img/eval/目录下）',
  `ispic` tinyint(1) NOT NULL COMMENT '是否图片类型专用',
  `id` int(10) NOT NULL COMMENT '关联内容ID',
  `type` tinyint(1) NOT NULL COMMENT '内容内型，与评论类型一致',
  `group_id` int(10) NOT NULL COMMENT '群组ID，标识群组中的内容',
  `isban` tinyint(1) NOT NULL COMMENT '是否禁用',
  `order_id` int(10) NOT NULL COMMENT '排序ID',
  PRIMARY KEY (`et_id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_faculties`
--

CREATE TABLE IF NOT EXISTS `liv_faculties` (
  `faculties_id` int(10) NOT NULL AUTO_INCREMENT,
  `school_id` int(10) NOT NULL DEFAULT '0',
  `faculties_name` varchar(255) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`faculties_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_favorites`
--

CREATE TABLE IF NOT EXISTS `liv_favorites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `cid` int(10) NOT NULL COMMENT '收藏ID',
  `type` tinyint(4) NOT NULL COMMENT '收藏类型 1：相册',
  `time` int(10) NOT NULL COMMENT '收藏时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_gather_rss`
--

CREATE TABLE IF NOT EXISTS `liv_gather_rss` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(500) NOT NULL,
  `type` varchar(30) NOT NULL,
  `key_id` int(10) NOT NULL,
  `rss_id` int(10) NOT NULL DEFAULT '0',
  `original_id` varchar(300) NOT NULL DEFAULT '' COMMENT '源guid',
  PRIMARY KEY (`id`),
  KEY `guid` (`guid`(300))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_ga_info`
--

CREATE TABLE IF NOT EXISTS `liv_ga_info` (
  `id` int(4) NOT NULL COMMENT '统计类型:1:地盘，2：相册',
  `total_num` int(10) NOT NULL COMMENT '总数',
  `today_num` int(10) NOT NULL COMMENT '今日新增',
  `create_time` int(10) NOT NULL COMMENT '统计时间',
  PRIMARY KEY (`id`,`create_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='地盘和相册的数据统计表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_gift`
--

CREATE TABLE IF NOT EXISTS `liv_gift` (
  `gift_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `gift_name` varchar(30) NOT NULL COMMENT '图片名称',
  `category_id` int(10) NOT NULL COMMENT '分类名称',
  `gift_img` varchar(100) NOT NULL COMMENT '礼物图片',
  `use_count` int(10) NOT NULL COMMENT '使用次数',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  `cost` smallint(5) NOT NULL,
  PRIMARY KEY (`gift_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=280 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_gift_user`
--

CREATE TABLE IF NOT EXISTS `liv_gift_user` (
  `user_gift_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `gift_id` int(10) NOT NULL COMMENT '礼物',
  `user_id` int(30) NOT NULL COMMENT '送礼人',
  `user_name` varchar(50) NOT NULL COMMENT '送礼人用户名',
  `touser_id` varchar(50) NOT NULL COMMENT '收礼人',
  `sendtime` int(11) NOT NULL COMMENT '送出时间',
  `is_silently` int(10) NOT NULL COMMENT '是否悄悄的',
  `is_anonymous` int(10) NOT NULL COMMENT '是否匿名',
  `comment` text NOT NULL COMMENT '留言',
  PRIMARY KEY (`user_gift_id`),
  KEY `user_id` (`user_id`),
  KEY `gift_id` (`gift_id`),
  KEY `touser_id` (`touser_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_group`
--

CREATE TABLE IF NOT EXISTS `liv_group` (
  `group_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '群组id',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '空间创建人id',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '创建人昵称',
  `name` varchar(255) NOT NULL COMMENT '群组名称',
  `visit_url` varchar(255) NOT NULL COMMENT '群组访问地址',
  `description` text NOT NULL COMMENT '群组描述',
  `group_rule` varchar(255) NOT NULL DEFAULT '',
  `group_head` int(10) NOT NULL DEFAULT '0' COMMENT '群组头的图象id',
  `group_background` int(10) NOT NULL DEFAULT '0' COMMENT '群组背景图id',
  `group_template_id` int(10) NOT NULL DEFAULT '0' COMMENT '群组模板id',
  `group_type` int(10) NOT NULL DEFAULT '0' COMMENT '群组类型',
  `school_id` int(10) NOT NULL DEFAULT '0' COMMENT '学校id',
  `faculties_id` int(10) NOT NULL DEFAULT '0' COMMENT '学院ID',
  `school_year` int(10) NOT NULL DEFAULT '0' COMMENT '群组的入学年份',
  `group_member_count` int(10) NOT NULL DEFAULT '0' COMMENT '群组的成员数量',
  `group_unconfirmed_member_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '未通过验证的成员数',
  `permission` bit(32) NOT NULL DEFAULT b'0' COMMENT '群组权限',
  `thread_count` int(10) NOT NULL DEFAULT '0' COMMENT '话题数',
  `post_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '贴子数',
  `albums_count` int(10) NOT NULL DEFAULT '0' COMMENT '群组相册数',
  `picture_count` int(10) NOT NULL DEFAULT '0' COMMENT '图片数',
  `bulletin_count` int(10) NOT NULL DEFAULT '0' COMMENT '留言数目',
  `today_visit` int(10) NOT NULL DEFAULT '0' COMMENT '今日访问数',
  `total_visit` int(10) NOT NULL DEFAULT '0' COMMENT '总访问数',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `last_update` int(10) NOT NULL DEFAULT '0' COMMENT '访问统计上次更新时间',
  `category_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '群组加入的分类',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，1：有效',
  `uploads_size` int(10) NOT NULL DEFAULT '0' COMMENT '上传的总大小',
  `group_addr` varchar(255) NOT NULL COMMENT '该讨论区的地址',
  `auto_delete_time` int(10) NOT NULL DEFAULT '24' COMMENT '回收站自动删除时间',
  `group_logo` int(10) NOT NULL DEFAULT '0' COMMENT '群组LOGO',
  `group_uncheck_blog_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '未审核日志数',
  `picture_size` int(10) NOT NULL DEFAULT '0',
  `rc_category_id` int(10) NOT NULL DEFAULT '0' COMMENT '推荐类型ID',
  `thread_list` tinyint(1) NOT NULL DEFAULT '30' COMMENT '圈子首页列出话题数',
  `thread_updating` varchar(2000) DEFAULT NULL COMMENT '最近更新的话题缓存',
  `lat` float(17,14) NOT NULL COMMENT '圈子所在的纬度',
  `lng` float(17,14) NOT NULL COMMENT '圈子所在的精度',
  `depth` int(2) NOT NULL COMMENT '圈子深度',
  `fatherid` int(10) NOT NULL COMMENT '上级id',
  `parents` varchar(255) DEFAULT NULL COMMENT '所有的父级id串，以,相隔',
  `is_last` tinyint(2) NOT NULL COMMENT '是否是末级',
  `living_here_count` int(10) NOT NULL COMMENT '“住在这里”人数',
  `per_add_time` int(10) NOT NULL DEFAULT '5' COMMENT '同一帖子回复间隔（同一个人）',
  `b_lat` float(17,14) NOT NULL COMMENT '百度地图的纬度',
  `g_lng` float(17,14) NOT NULL COMMENT '百度地图的精度',
  `map_type` tinyint(2) NOT NULL COMMENT '地图类型，0：谷歌，1：baidu',
  PRIMARY KEY (`group_id`),
  KEY `user_id` (`user_id`),
  KEY `group_type` (`group_type`),
  KEY `post_count` (`post_count`),
  KEY `rc_category_id` (`rc_category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='群组表' AUTO_INCREMENT=262 ;

--
-- 触发器 `liv_group`
--
DROP TRIGGER IF EXISTS `add_delete_group_data_record`;
DELIMITER //
CREATE TRIGGER `add_delete_group_data_record` AFTER DELETE ON `liv_group`
 FOR EACH ROW begin
insert into liv_record_delete(tid,member_id,table_name,delete_time) values

(old.group_id,old.user_id,'liv_group',now());
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_groupthread_s`
--

CREATE TABLE IF NOT EXISTS `liv_groupthread_s` (
  `id` int(10) NOT NULL COMMENT '统计类型1：地盘含帖量，2：地盘关注数，3：帖子点击量',
  `x_id` int(10) NOT NULL COMMENT '如果统计的是地盘就记录地盘id，否则就存储帖子id',
  `x_title` varchar(255) NOT NULL COMMENT '如果统计地盘就记录地盘名，否则就记录帖子标题',
  `sum_num` int(10) NOT NULL COMMENT '统计出的数据',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户id，地盘记录地主，帖子记录作者',
  `user_name` varchar(255) NOT NULL COMMENT '用户名',
  `create_time` int(10) NOT NULL COMMENT '统计时间',
  `range_num` int(10) NOT NULL COMMENT '排名',
  PRIMARY KEY (`id`,`create_time`,`range_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='地盘关注数、帖子数以及帖子点击数的topx排名统计';

-- --------------------------------------------------------

--
-- 表的结构 `liv_group_albums`
--

CREATE TABLE IF NOT EXISTS `liv_group_albums` (
  `group_albums_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `visit_level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '权限',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '群组id',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '创建者',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '创建昵称',
  `albums_name` varchar(255) NOT NULL DEFAULT '' COMMENT '相册名',
  `albums_cover` int(10) NOT NULL DEFAULT '0' COMMENT '相册封面',
  `albums_description` varchar(255) NOT NULL DEFAULT '' COMMENT '相册描述',
  `picture_count` int(10) NOT NULL DEFAULT '0' COMMENT '相册图片数',
  `comment_count` int(10) NOT NULL DEFAULT '0' COMMENT '相册评论数',
  `visit_count` int(10) NOT NULL DEFAULT '0' COMMENT '相册访问数',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '相册创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '相册更新时间',
  `school_id` int(10) NOT NULL DEFAULT '0' COMMENT '学校id',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '相册状态 1：有效',
  `location_id` char(20) NOT NULL COMMENT '所属地区',
  `cat_fatherid` char(20) NOT NULL,
  `albums_category_id` char(20) NOT NULL,
  `cover_file_name` varchar(255) DEFAULT '',
  `file_path` varchar(255) DEFAULT '',
  `last_pics` varchar(600) NOT NULL COMMENT '最新上传照片',
  `rc_category_id` int(10) NOT NULL DEFAULT '0' COMMENT '推荐类型ID',
  `evaluation` float(4,1) NOT NULL DEFAULT '0.0' COMMENT '评分值',
  `evaluation_num` int(10) NOT NULL DEFAULT '0' COMMENT '评分人数',
  PRIMARY KEY (`group_albums_id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`),
  KEY `rc_category_id` (`rc_category_id`),
  KEY `update_time` (`update_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_group_category`
--

CREATE TABLE IF NOT EXISTS `liv_group_category` (
  `category_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `category_name` varchar(255) NOT NULL DEFAULT '',
  `group_count` int(10) NOT NULL DEFAULT '0' COMMENT '群组数量',
  `depth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '深度',
  `order_id` smallint(6) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='群组分类表' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_group_join_category`
--

CREATE TABLE IF NOT EXISTS `liv_group_join_category` (
  `join_category_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '群组id',
  `category_id` char(20) NOT NULL DEFAULT '0' COMMENT '群组分类id',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`join_category_id`),
  KEY `category_id` (`category_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_group_material`
--

CREATE TABLE IF NOT EXISTS `liv_group_material` (
  `material_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '图片id',
  `group_albums_id` int(10) NOT NULL DEFAULT '0' COMMENT '外键，对应群组相册表',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '群组id',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '上传人名',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '上传人昵称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '图片描述',
  `file_path` varchar(255) NOT NULL DEFAULT '' COMMENT '图片路径',
  `file_name` varchar(255) DEFAULT NULL COMMENT '图片名',
  `file_thumb` varchar(255) DEFAULT NULL COMMENT '缩略图名',
  `file_middle_thumb` varchar(255) DEFAULT NULL COMMENT '缩略图名',
  `file_big_thumb` varchar(255) DEFAULT NULL COMMENT '缩略图名',
  `click_count` int(10) NOT NULL DEFAULT '0' COMMENT '图片点击数',
  `comment_count` int(10) NOT NULL DEFAULT '0' COMMENT '图片评论数',
  `pub_time` int(10) NOT NULL DEFAULT '0' COMMENT '图片发布时间',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态： 1：有效',
  `file_size` int(10) NOT NULL DEFAULT '0',
  `file_type` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '素材名称',
  PRIMARY KEY (`material_id`),
  KEY `club_id` (`group_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='群组素材表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_group_members`
--

CREATE TABLE IF NOT EXISTS `liv_group_members` (
  `group_members_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '群组id',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员id',
  `user_name` varchar(32) NOT NULL DEFAULT '',
  `user_level` int(10) NOT NULL DEFAULT '0' COMMENT '会员级别',
  `join_time` int(10) NOT NULL DEFAULT '0' COMMENT '加入时间',
  `post_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '粘子数',
  `quintessence_count` int(10) NOT NULL DEFAULT '0' COMMENT '精华贴数',
  `last_visit` int(10) NOT NULL DEFAULT '0' COMMENT '最后访问',
  `permission` bit(32) NOT NULL COMMENT '权限值',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `thread_count` int(10) NOT NULL DEFAULT '0' COMMENT '主题数',
  `blacklist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '黑名单',
  `referee` int(10) NOT NULL DEFAULT '0' COMMENT '推荐人',
  `visit_count` int(10) NOT NULL DEFAULT '0',
  `live_here` tinyint(2) NOT NULL COMMENT '成员是否住在圈子附近，0：否，1：是',
  PRIMARY KEY (`group_members_id`),
  KEY `user_id` (`user_id`),
  KEY `club_id` (`group_id`),
  KEY `blacklist` (`blacklist`),
  KEY `visit_count` (`visit_count`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='群组会员关联表' AUTO_INCREMENT=160 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_group_pictures`
--

CREATE TABLE IF NOT EXISTS `liv_group_pictures` (
  `material_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '图片id',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '群组id',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '上传人名',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '上传人昵称',
  `group_albums_id` int(10) NOT NULL DEFAULT '0' COMMENT '相册id',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '图片描述',
  `file_path` varchar(255) NOT NULL DEFAULT '' COMMENT '图片路径',
  `file_name` varchar(255) DEFAULT NULL COMMENT '图片名',
  `file_thumb` varchar(255) DEFAULT NULL COMMENT '缩略图名',
  `file_middle_thumb` varchar(255) DEFAULT NULL COMMENT '缩略图名',
  `file_big_thumb` varchar(255) DEFAULT NULL COMMENT '缩略图名',
  `click_count` int(10) NOT NULL DEFAULT '0' COMMENT '图片点击数',
  `comment_count` int(10) NOT NULL DEFAULT '0' COMMENT '图片评论数',
  `pub_time` int(10) NOT NULL DEFAULT '0' COMMENT '图片发布时间',
  `update_time` int(10) NOT NULL COMMENT '修改时间',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态： 1：有效',
  `file_size` int(10) NOT NULL DEFAULT '0',
  `file_type` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL,
  `Exif` tinyint(1) NOT NULL DEFAULT '0',
  `CameraMake` varchar(255) NOT NULL,
  `CameraModel` varchar(255) NOT NULL,
  `DateTime` int(10) NOT NULL,
  `ApertureFNumber` varchar(255) NOT NULL,
  `FocalLength` varchar(255) NOT NULL,
  `ExifImageWidth` int(10) NOT NULL DEFAULT '0',
  `ExifImageLength` int(10) NOT NULL DEFAULT '0',
  `ISOSpeedRatings` int(10) NOT NULL DEFAULT '0',
  `ExposureTime` varchar(255) NOT NULL,
  `ExposureProgram` int(10) NOT NULL DEFAULT '0',
  `ExposureBiasValue` float(3,2) NOT NULL,
  `MeteringMode` int(10) NOT NULL DEFAULT '0',
  `Lightsource` int(10) NOT NULL DEFAULT '0',
  `Flash` tinyint(1) NOT NULL DEFAULT '0',
  `FocalLengthIn35mmFilm` int(10) NOT NULL DEFAULT '0',
  `WhiteBalance` int(10) NOT NULL DEFAULT '0',
  `is_new` tinyint(1) NOT NULL DEFAULT '1',
  `rc_category_id` int(10) NOT NULL DEFAULT '0' COMMENT '推荐类型ID',
  `evaluation` float(4,1) NOT NULL DEFAULT '0.0' COMMENT '评分值',
  `evaluation_num` int(10) NOT NULL DEFAULT '0' COMMENT '评分人数',
  `order_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`material_id`),
  KEY `user_id` (`user_id`),
  KEY `rc_category_id` (`rc_category_id`),
  KEY `group_id` (`group_id`),
  KEY `group_albums_id` (`group_albums_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='群组素材表' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_group_thread`
--

CREATE TABLE IF NOT EXISTS `liv_group_thread` (
  `thread_id` int(10) NOT NULL COMMENT '话题id',
  `group_id` int(10) NOT NULL COMMENT '群组id',
  PRIMARY KEY (`thread_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='群组话题关联表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_group_type`
--

CREATE TABLE IF NOT EXISTS `liv_group_type` (
  `typeid` int(10) NOT NULL AUTO_INCREMENT COMMENT '类型id',
  `type_name` varchar(100) NOT NULL COMMENT '类型名称',
  `group_count` int(10) NOT NULL COMMENT '拥有的讨论区数量',
  PRIMARY KEY (`typeid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='讨论区类型表' AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_gt_count`
--

CREATE TABLE IF NOT EXISTS `liv_gt_count` (
  `group_id` int(10) NOT NULL COMMENT '圈子id',
  `post_count` int(10) NOT NULL COMMENT '某日发帖总数',
  `post_date` date NOT NULL,
  PRIMARY KEY (`group_id`,`post_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='圈子今日发帖总数';

-- --------------------------------------------------------

--
-- 表的结构 `liv_gt_info`
--

CREATE TABLE IF NOT EXISTS `liv_gt_info` (
  `gt_id` int(10) NOT NULL COMMENT '地盘分类id',
  `group_num` int(10) NOT NULL COMMENT '该分类下地盘总数',
  `today_new_gnum` int(10) NOT NULL COMMENT '该分类今日新增地盘数',
  `thread_num` int(10) NOT NULL COMMENT '该分类下帖子总数',
  `today_new_tnum` int(10) NOT NULL COMMENT '该分类下今日新增帖子数',
  `post_num` int(10) NOT NULL COMMENT '该分类下帖子回复总数',
  `today_new_pnum` int(10) NOT NULL COMMENT '该分类下今日新增回复数',
  `member_num` int(10) NOT NULL COMMENT '该分类下地盘的关注总数',
  `today_new_mnum` int(10) NOT NULL COMMENT '该分类下地盘今日新增关注数',
  `click_num` int(10) NOT NULL COMMENT '该分类下帖子浏览总数',
  `create_time` int(10) NOT NULL COMMENT '统计时间',
  PRIMARY KEY (`gt_id`,`create_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='地盘分类相关信息的统计数据表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_hot_content`
--

CREATE TABLE IF NOT EXISTS `liv_hot_content` (
  `hot_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `id` int(10) NOT NULL COMMENT '内容ID',
  `title` varchar(180) NOT NULL COMMENT '内容标题',
  `type` tinyint(1) NOT NULL COMMENT '内容类型(参照评论表类型)',
  `link` varchar(255) NOT NULL COMMENT '内容链接地址',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '圈子ID',
  `join_count` int(10) NOT NULL COMMENT '参与数(投票数/参与测试数/参与评论数)',
  `content` varchar(300) NOT NULL COMMENT '最后评论',
  `update_time` int(10) NOT NULL COMMENT '更新时间',
  `user_id` int(10) NOT NULL COMMENT '用户ID',
  `user_name` varchar(18) NOT NULL COMMENT '用户名',
  `time_filter` int(10) NOT NULL DEFAULT '0' COMMENT '按天统计时间戳',
  `j_uid` int(10) NOT NULL,
  `j_uname` char(10) NOT NULL,
  `user_ids` varchar(100) NOT NULL COMMENT '参与的用户ID',
  `private` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否私有',
  `is_comment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为评论(修正)',
  PRIMARY KEY (`hot_id`),
  KEY `id` (`id`,`type`),
  KEY `user_id` (`user_id`),
  KEY `update_time` (`update_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=202 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_invite_code`
--

CREATE TABLE IF NOT EXISTS `liv_invite_code` (
  `invite_id` int(10) NOT NULL AUTO_INCREMENT,
  `invite_code` char(20) NOT NULL,
  `user_id` int(10) NOT NULL,
  `user_name` char(18) NOT NULL,
  `reg_user_id` int(10) NOT NULL,
  `reg_user_name` char(18) NOT NULL,
  `invite_time` int(10) NOT NULL,
  `reg_time` int(10) NOT NULL,
  `friend_group_id` int(10) NOT NULL,
  `reg_user_count` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`invite_id`),
  KEY `invite_code` (`invite_code`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_javascript`
--

CREATE TABLE IF NOT EXISTS `liv_javascript` (
  `javascript_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL COMMENT '调用名称',
  `descript` varchar(600) NOT NULL COMMENT '调用描述',
  `js_sql` varchar(500) NOT NULL,
  `sql_valid` tinyint(1) NOT NULL,
  `loop_code` varchar(15000) NOT NULL,
  `add_time` int(10) NOT NULL,
  `cycle_time` int(10) NOT NULL COMMENT '更新周期',
  `update_time` int(10) NOT NULL,
  `is_ban` tinyint(1) NOT NULL,
  `order_by` varchar(100) NOT NULL,
  `select_field` varchar(200) NOT NULL,
  `where_cond` varchar(800) NOT NULL,
  `is_define` tinyint(1) NOT NULL,
  `filename` varchar(30) NOT NULL,
  `item_count` smallint(4) NOT NULL COMMENT '调用内容的条数',
  PRIMARY KEY (`javascript_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='javascript调用表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_location`
--

CREATE TABLE IF NOT EXISTS `liv_location` (
  `location_id` varchar(20) NOT NULL DEFAULT '',
  `location_name` varchar(255) NOT NULL DEFAULT '',
  `depth` tinyint(1) NOT NULL DEFAULT '0',
  `member_count` int(10) NOT NULL DEFAULT '0' COMMENT '会员总数',
  `space_count` int(10) NOT NULL DEFAULT '0',
  `group_count` int(10) NOT NULL DEFAULT '0' COMMENT '群组总数',
  `school_count` int(10) NOT NULL DEFAULT '0' COMMENT '学校总数',
  PRIMARY KEY (`location_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_manage_recored`
--

CREATE TABLE IF NOT EXISTS `liv_manage_recored` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `user_name` char(18) NOT NULL,
  `record_text` varchar(1000) NOT NULL,
  `op_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_material`
--

CREATE TABLE IF NOT EXISTS `liv_material` (
  `material_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '图片id',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员名',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '会员昵称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '图片描述',
  `file_path` varchar(255) NOT NULL DEFAULT '' COMMENT '图片路径',
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '素材名称',
  `file_name` varchar(255) DEFAULT NULL COMMENT '图片名',
  `file_thumb` varchar(255) DEFAULT NULL COMMENT '缩略图名',
  `file_middle_thumb` varchar(255) DEFAULT NULL COMMENT '缩略图名',
  `file_big_thumb` varchar(255) DEFAULT NULL COMMENT '缩略图名',
  `click_count` int(10) NOT NULL DEFAULT '0' COMMENT '图片点击数',
  `comment_count` int(10) NOT NULL DEFAULT '0' COMMENT '图片评论数',
  `pub_time` int(10) NOT NULL DEFAULT '0' COMMENT '图片发布时间',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态： 1：有效',
  `file_size` int(10) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `file_type` varchar(60) NOT NULL DEFAULT '' COMMENT '文件类型',
  `id` int(10) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `ismedia` tinyint(1) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL,
  `avatar` tinyint(1) NOT NULL DEFAULT '0' COMMENT '当前头像',
  `file_info` text NOT NULL,
  PRIMARY KEY (`material_id`),
  KEY `user_id` (`user_id`),
  KEY `id` (`id`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='空间素材表' AUTO_INCREMENT=207 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_moods`
--

CREATE TABLE IF NOT EXISTS `liv_moods` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `descript` varchar(32) NOT NULL,
  `src` varchar(32) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `order` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_net_picture`
--

CREATE TABLE IF NOT EXISTS `liv_net_picture` (
  `picture_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `user_name` varchar(18) NOT NULL,
  `title` varchar(180) NOT NULL,
  `description` varchar(900) NOT NULL,
  `pub_time` int(10) NOT NULL,
  `sourck_link` varchar(300) NOT NULL,
  `from_type` int(10) NOT NULL,
  `localized` int(10) NOT NULL,
  `original_pic` varchar(300) NOT NULL,
  `original_size` varchar(12) NOT NULL,
  `thumbnail` varchar(1500) NOT NULL,
  `small_thumb` varchar(300) NOT NULL,
  `thumbnail_count` int(10) NOT NULL,
  PRIMARY KEY (`picture_id`),
  KEY `user_id` (`user_id`),
  KEY `localized` (`localized`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_ns_sql`
--

CREATE TABLE IF NOT EXISTS `liv_ns_sql` (
  `sid` int(10) NOT NULL AUTO_INCREMENT,
  `cond` varchar(1000) NOT NULL,
  `data` varchar(1000) NOT NULL COMMENT '序列化数据',
  `add_time` int(10) NOT NULL,
  `order_by` varchar(100) NOT NULL COMMENT '排序方式',
  `use_uex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否使用用户经历表',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='搜索记录表' AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_op_log`
--

CREATE TABLE IF NOT EXISTS `liv_op_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(10) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `text` mediumtext NOT NULL,
  `deltime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_pictures`
--

CREATE TABLE IF NOT EXISTS `liv_pictures` (
  `material_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '图片id',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员名',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '会员昵称',
  `albums_id` int(10) NOT NULL DEFAULT '0' COMMENT '相册id',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '图片描述',
  `file_path` varchar(255) NOT NULL DEFAULT '' COMMENT '图片路径',
  `name` varchar(255) NOT NULL COMMENT '用户上传的图片名',
  `file_name` varchar(255) DEFAULT NULL COMMENT '改过之后的图片名',
  `file_thumb` varchar(255) DEFAULT NULL COMMENT '缩略图名',
  `file_middle_thumb` varchar(255) DEFAULT NULL COMMENT '缩略图名',
  `file_big_thumb` varchar(255) DEFAULT NULL COMMENT '缩略图名',
  `click_count` int(10) NOT NULL DEFAULT '0' COMMENT '图片点击数',
  `comment_count` int(10) NOT NULL DEFAULT '0' COMMENT '图片评论数',
  `pub_time` int(10) NOT NULL DEFAULT '0' COMMENT '图片发布时间',
  `update_time` int(10) NOT NULL COMMENT '更新时间',
  `is_new` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否新上传',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态： 1：有效',
  `file_size` int(10) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `file_type` varchar(60) NOT NULL DEFAULT '' COMMENT '文件类型',
  `Exif` tinyint(1) NOT NULL DEFAULT '0',
  `CameraMake` varchar(255) NOT NULL,
  `CameraModel` varchar(255) NOT NULL,
  `ApertureFNumber` varchar(255) NOT NULL,
  `FocalLength` varchar(255) NOT NULL,
  `ExifImageWidth` int(10) NOT NULL DEFAULT '0',
  `ExifImageLength` int(10) NOT NULL DEFAULT '0',
  `ISOSpeedRatings` int(10) NOT NULL,
  `ExposureTime` varchar(255) NOT NULL,
  `ExposureProgram` int(10) NOT NULL,
  `ExposureBiasValue` float(3,2) NOT NULL DEFAULT '0.00',
  `MeteringMode` int(10) NOT NULL,
  `Lightsource` int(10) NOT NULL,
  `Flash` tinyint(1) NOT NULL DEFAULT '0',
  `FocalLengthIn35mmFilm` int(10) NOT NULL,
  `WhiteBalance` int(10) NOT NULL,
  `DateTime` int(10) NOT NULL,
  `evaluation` float(4,1) NOT NULL COMMENT '被评总分',
  `evaluation_num` int(10) NOT NULL COMMENT '被评总次数',
  `rc_category_id` int(10) NOT NULL DEFAULT '0' COMMENT '推荐类型ID',
  `from_type` int(10) NOT NULL,
  `order_id` int(10) NOT NULL DEFAULT '0',
  `picid` int(10) NOT NULL,
  PRIMARY KEY (`material_id`),
  KEY `user_id` (`user_id`),
  KEY `albums_id` (`albums_id`),
  KEY `update_time` (`update_time`),
  KEY `rc_category_id` (`rc_category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='空间素材表' AUTO_INCREMENT=614 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_pm`
--

CREATE TABLE IF NOT EXISTS `liv_pm` (
  `pid` int(10) NOT NULL AUTO_INCREMENT,
  `sid` int(10) NOT NULL DEFAULT '0',
  `fromID` int(10) NOT NULL,
  `fromwho` varchar(32) NOT NULL,
  `toID` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `stime` int(10) NOT NULL DEFAULT '0',
  `rtime` int(10) NOT NULL DEFAULT '0',
  `delflag` int(10) NOT NULL DEFAULT '0',
  `flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退出群聊',
  PRIMARY KEY (`pid`),
  KEY `sid` (`sid`,`fromID`,`toID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=735 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_pm_session`
--

CREATE TABLE IF NOT EXISTS `liv_pm_session` (
  `sid` int(10) NOT NULL AUTO_INCREMENT COMMENT '会话ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型 0 对话 1 群聊',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '发起人ID',
  `uname` varchar(32) NOT NULL COMMENT '发起人',
  `ids` varchar(5000) NOT NULL COMMENT '参与人(包含自身)',
  `stime` int(10) NOT NULL DEFAULT '0' COMMENT '发起时间',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_pm_user`
--

CREATE TABLE IF NOT EXISTS `liv_pm_user` (
  `sid` int(10) NOT NULL DEFAULT '0' COMMENT '会话ID',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '所属用户ID',
  `rtime` int(10) NOT NULL DEFAULT '0' COMMENT '最后查看时间',
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '最后查看ID',
  `new` int(10) NOT NULL DEFAULT '0' COMMENT '是否有新(亦可为最后查看后一条)',
  PRIMARY KEY (`sid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_poll`
--

CREATE TABLE IF NOT EXISTS `liv_poll` (
  `poll_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '投票ID',
  `title` varchar(255) NOT NULL COMMENT '投票标题',
  `description` varchar(900) NOT NULL,
  `id` int(10) NOT NULL COMMENT '关联内容ID',
  `type` tinyint(1) NOT NULL COMMENT '关联内容类型(1-日志 2 话题 3 个人照片 4 音乐 5 群组照片 )',
  `opttype` tinyint(1) NOT NULL COMMENT '选项类型(1 option 2 checkbox)',
  `user_id` int(10) NOT NULL COMMENT '添加人ID',
  `user_name` varchar(32) NOT NULL COMMENT '添加人',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) NOT NULL COMMENT '结束时间',
  `totalpoll` int(10) NOT NULL COMMENT '参与人数',
  `pollresult` text NOT NULL COMMENT '投票结果',
  `muti_poll` tinyint(1) NOT NULL DEFAULT '0' COMMENT '允许多此投票',
  `group_id` int(10) NOT NULL COMMENT '群组ID',
  `privacy` tinyint(1) NOT NULL DEFAULT '0',
  `comment_count` int(10) NOT NULL DEFAULT '0',
  `poll_count` int(10) NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `rc_category_id` int(10) NOT NULL DEFAULT '0' COMMENT '推荐类型ID',
  PRIMARY KEY (`poll_id`),
  KEY `id` (`id`,`type`),
  KEY `user_id` (`user_id`),
  KEY `privacy` (`privacy`),
  KEY `addtime` (`addtime`),
  KEY `update_time` (`update_time`),
  KEY `rc_category_id` (`rc_category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='投票表' AUTO_INCREMENT=1428 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_poll_opt`
--

CREATE TABLE IF NOT EXISTS `liv_poll_opt` (
  `poll_opt_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `poll_id` int(11) NOT NULL COMMENT '投票ID',
  `poll_opt` varchar(255) NOT NULL COMMENT '选项内容',
  `poll_num` int(11) NOT NULL COMMENT '得票数',
  PRIMARY KEY (`poll_opt_id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9007 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_poll_user`
--

CREATE TABLE IF NOT EXISTS `liv_poll_user` (
  `user_id` int(10) NOT NULL COMMENT '用户ID',
  `poll_id` int(10) NOT NULL COMMENT '投票ID',
  `poll_time` int(10) NOT NULL COMMENT '投票时间',
  `detail` varchar(1000) NOT NULL,
  KEY `user_id` (`user_id`,`poll_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_post`
--

CREATE TABLE IF NOT EXISTS `liv_post` (
  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stair_num` int(10) NOT NULL DEFAULT '0' COMMENT '回复的楼层号',
  `pagetext` mediumtext NOT NULL COMMENT '帖子内容',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `user_name` varchar(32) NOT NULL DEFAULT '' COMMENT '会员昵称',
  `from_ip` varchar(30) NOT NULL DEFAULT '' COMMENT '发帖ip',
  `moderate` tinyint(1) NOT NULL DEFAULT '0',
  `allow_smile` tinyint(1) NOT NULL DEFAULT '0' COMMENT '使用表情图标',
  `pub_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发帖时间',
  `thread_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '话题ID',
  `anonymous` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否匿名发表',
  `logtext` mediumtext COMMENT '帖子操作日志',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '帖子状态',
  `update_user_id` int(10) NOT NULL DEFAULT '0' COMMENT '修改人',
  `update_uname` varchar(200) NOT NULL DEFAULT '',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `displayupt_log` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示在帖子中',
  `post_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '通过什么发帖的',
  `delete_flag` enum('0','1') NOT NULL DEFAULT '0',
  `reply_user_name` varchar(20) NOT NULL COMMENT '被回贴的作者名',
  `reply_des` varchar(200) NOT NULL COMMENT '被回贴',
  `reply_user_id` int(10) NOT NULL DEFAULT '0' COMMENT '被回贴的作者ID',
  `poll_id` int(10) NOT NULL DEFAULT '0' COMMENT '投票 ID',
  `floor` int(10) NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `user_id` (`user_id`),
  KEY `thread_id` (`thread_id`),
  KEY `pub_time` (`pub_time`),
  KEY `poll_id` (`poll_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=615898 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_question`
--

CREATE TABLE IF NOT EXISTS `liv_question` (
  `question_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '题目ID',
  `test_id` int(10) NOT NULL COMMENT '测试ID',
  `order_id` int(10) NOT NULL COMMENT '题目编号',
  `title` varchar(255) NOT NULL COMMENT '题目标题',
  `question_opt` text NOT NULL COMMENT '题目选项',
  PRIMARY KEY (`question_id`),
  KEY `test_id` (`test_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5040 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_read_record`
--

CREATE TABLE IF NOT EXISTS `liv_read_record` (
  `read_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `id` int(10) NOT NULL COMMENT '内容id',
  `type` tinyint(1) NOT NULL COMMENT '内容类型，1 - feed，2 - doing',
  `readtime` int(10) NOT NULL COMMENT '阅读时间',
  PRIMARY KEY (`read_id`),
  KEY `user_id` (`user_id`,`id`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户阅读记录表' AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_recent_used_modules`
--

CREATE TABLE IF NOT EXISTS `liv_recent_used_modules` (
  `user_id` int(10) NOT NULL,
  `module_id` int(10) NOT NULL,
  `module_name` varchar(32) NOT NULL,
  `last_use` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`module_name`),
  KEY `user_id` (`user_id`,`module_id`,`last_use`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_recommend_cat`
--

CREATE TABLE IF NOT EXISTS `liv_recommend_cat` (
  `category_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL COMMENT '排序',
  `type` tinyint(1) NOT NULL COMMENT '类型',
  `name` varchar(30) NOT NULL COMMENT '标题',
  `content_count` int(10) NOT NULL COMMENT '计数',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `cache_limit` smallint(6) NOT NULL COMMENT '生成缓存数量',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_recommend_content`
--

CREATE TABLE IF NOT EXISTS `liv_recommend_content` (
  `rc_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `id` int(10) NOT NULL DEFAULT '0' COMMENT '日志ID/相册ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型',
  `title` varchar(180) NOT NULL COMMENT '推荐内容标题',
  `linkurl` varchar(200) NOT NULL COMMENT '内容链接',
  `source_img` varchar(255) NOT NULL COMMENT '图片源路径',
  `img_path` varchar(60) NOT NULL COMMENT '图片路径(本地化后)',
  `img_name` varchar(32) NOT NULL COMMENT '图片名称',
  `content` varchar(600) NOT NULL COMMENT '推荐内容描述',
  `category_id` int(10) NOT NULL DEFAULT '0' COMMENT '推荐内容所属分类',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐级别',
  `recommend_time` int(10) NOT NULL DEFAULT '0' COMMENT '推荐时间',
  `group_id` int(10) NOT NULL DEFAULT '0',
  `cache_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - 文字 1- 图片 2 - 圈子 ',
  `content_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - 纯文字 1- 纯图片 2 - 混合 (日志含有图片 筛选区分)',
  `ext_data` text NOT NULL COMMENT '原始内容信息缓存',
  PRIMARY KEY (`rc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_rewen`
--

CREATE TABLE IF NOT EXISTS `liv_rewen` (
  `rewen_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `link` varchar(500) NOT NULL COMMENT '源链接',
  `description` varchar(1500) NOT NULL DEFAULT '',
  `author` varchar(80) NOT NULL,
  `category` varchar(60) NOT NULL DEFAULT '0',
  `comments` text NOT NULL,
  `enclosure` varchar(255) NOT NULL DEFAULT '',
  `guid` varchar(300) NOT NULL,
  `pubDate` int(10) DEFAULT NULL,
  `source` varchar(255) NOT NULL DEFAULT '',
  `rss_id` int(10) NOT NULL DEFAULT '0',
  `category_id` int(10) NOT NULL DEFAULT '0' COMMENT '热文分类ID',
  `click_count` int(10) NOT NULL DEFAULT '0' COMMENT '点击数',
  `comment_count` int(10) NOT NULL DEFAULT '0' COMMENT '评论数',
  `share_count` int(10) NOT NULL DEFAULT '0' COMMENT '分享数',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_bulid_desc` tinyint(1) NOT NULL DEFAULT '0' COMMENT '描述是否重建',
  `visible` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可见(1是0否)',
  `rc_category_id` int(10) NOT NULL DEFAULT '0' COMMENT '推荐类型ID',
  `contain_img` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否包含图片',
  `contain_media` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否包含视频',
  `evaluation` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`rewen_id`),
  KEY `category_id` (`category_id`),
  KEY `rss_id` (`rss_id`,`is_bulid_desc`,`visible`),
  KEY `pubDate` (`pubDate`),
  KEY `rc_category_id` (`rc_category_id`),
  KEY `is_bulid_desc` (`is_bulid_desc`,`visible`,`category_id`),
  KEY `visible` (`visible`),
  KEY `guid` (`guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_rewen_content`
--

CREATE TABLE IF NOT EXISTS `liv_rewen_content` (
  `rewen_id` int(10) NOT NULL COMMENT '热文ID',
  `rewen_content` text NOT NULL COMMENT '热文内容',
  PRIMARY KEY (`rewen_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_rss`
--

CREATE TABLE IF NOT EXISTS `liv_rss` (
  `rss_id` int(10) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `source_link` varchar(255) NOT NULL COMMENT '来源网址',
  `description` varchar(500) NOT NULL COMMENT '描述',
  `logo` varchar(255) NOT NULL COMMENT '频道logo',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `next_gather_time` int(10) NOT NULL DEFAULT '0' COMMENT '下一次获取时间',
  `inter_time` int(10) NOT NULL DEFAULT '86400' COMMENT '间隔时间',
  `stat` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已获取RSS频道信息',
  `user_id` int(10) NOT NULL COMMENT '用户ID',
  `user_name` varchar(32) NOT NULL COMMENT '用户名',
  `subscribe_count` int(10) NOT NULL COMMENT '订阅数',
  `category_id` int(10) NOT NULL,
  `is_sys` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否系统',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `is_share` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否共享',
  `gather_count` int(10) NOT NULL DEFAULT '0' COMMENT '获取次数',
  `item_count` int(10) NOT NULL DEFAULT '0' COMMENT '获取到的累计条数',
  `rss_sys_cat` tinyint(1) NOT NULL DEFAULT '0' COMMENT '系统分类',
  `data_to_tab` varchar(20) NOT NULL DEFAULT 'rewen' COMMENT '入库表名',
  `fail_count` int(10) NOT NULL DEFAULT '0' COMMENT '失败次数',
  `error_count` tinyint(1) NOT NULL DEFAULT '0' COMMENT '错误次数',
  `filter_text` varchar(300) NOT NULL DEFAULT '' COMMENT '过滤单条文字中的指定字串',
  `valid_preg` varchar(300) NOT NULL DEFAULT '' COMMENT '过滤条件正则式',
  `filter_content_preg` varchar(300) NOT NULL DEFAULT '' COMMENT '根据正则替换内容中数据',
  `icon` varchar(300) NOT NULL COMMENT '图标文件',
  `icon_state` tinyint(1) NOT NULL COMMENT '图标状态0-未获取， 1-已获取， 2-有图标，3-没有图标， 4 - 已生成文件',
  `rc_category_id` int(10) NOT NULL DEFAULT '0' COMMENT '推荐分类',
  `addtogoogle` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否订阅到google',
  PRIMARY KEY (`rss_id`),
  KEY `user_id` (`user_id`),
  KEY `is_sys` (`is_sys`),
  KEY `rss_sys_cat` (`rss_sys_cat`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=538 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_rss_subscribe`
--

CREATE TABLE IF NOT EXISTS `liv_rss_subscribe` (
  `subscribe_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '订阅ID',
  `rss_id` int(10) NOT NULL DEFAULT '0' COMMENT 'RSSID',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `subscribe_time` int(10) NOT NULL DEFAULT '0' COMMENT '订阅时间',
  `privacy` tinyint(1) NOT NULL DEFAULT '2' COMMENT '隐私设置(0 否 1-好友可见 2 所有人可见)',
  PRIMARY KEY (`subscribe_id`),
  KEY `user_id` (`user_id`),
  KEY `rss_id` (`rss_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_school`
--

CREATE TABLE IF NOT EXISTS `liv_school` (
  `school_id` int(10) NOT NULL AUTO_INCREMENT,
  `school_name` varchar(255) NOT NULL DEFAULT '',
  `school_description` tinytext NOT NULL,
  `class_count` int(11) NOT NULL DEFAULT '0',
  `blog_count` int(10) NOT NULL DEFAULT '0',
  `group_count` int(10) NOT NULL DEFAULT '0',
  `member_count` int(10) NOT NULL DEFAULT '0',
  `location_id` char(20) CHARACTER SET armscii8 COLLATE armscii8_bin NOT NULL COMMENT '所属地区',
  `full_name` varchar(255) NOT NULL COMMENT '名称全文检索数据',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型，见配置',
  PRIMARY KEY (`school_id`),
  KEY `location_id` (`location_id`),
  FULLTEXT KEY `full_name` (`full_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2727 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_score`
--

CREATE TABLE IF NOT EXISTS `liv_score` (
  `score_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '积分ID 自动编号',
  `user_rank_id` int(10) NOT NULL DEFAULT '0' COMMENT '级别ID',
  `user_group_id` int(10) NOT NULL DEFAULT '0' COMMENT '组ID',
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '升级需要积分',
  `score_order` smallint(4) NOT NULL DEFAULT '0' COMMENT '排序号',
  PRIMARY KEY (`score_id`),
  KEY `user_rank_id` (`user_rank_id`,`user_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_score_log`
--

CREATE TABLE IF NOT EXISTS `liv_score_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `user_name` varchar(32) NOT NULL,
  `score` float(5,2) NOT NULL,
  `score_type` tinyint(1) NOT NULL,
  `gettime` int(10) NOT NULL,
  `content` varchar(255) NOT NULL,
  `relative_id` int(10) NOT NULL DEFAULT '0',
  `group_id` int(10) NOT NULL DEFAULT '0',
  `num` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=315 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_search_key`
--

CREATE TABLE IF NOT EXISTS `liv_search_key` (
  `key` char(60) NOT NULL DEFAULT '',
  `s_num` int(10) NOT NULL DEFAULT '0' COMMENT '搜索次数',
  `r_num` int(10) NOT NULL DEFAULT '0' COMMENT '结果记录数',
  KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_session`
--

CREATE TABLE IF NOT EXISTS `liv_session` (
  `sessionhash` varchar(32) NOT NULL DEFAULT '0',
  `user_name` varchar(60) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `host` varchar(30) NOT NULL DEFAULT '',
  `useragent` varchar(255) NOT NULL DEFAULT '',
  `lastactivity` int(10) unsigned NOT NULL DEFAULT '0',
  `invisible` tinyint(1) NOT NULL DEFAULT '0',
  `location` varchar(250) NOT NULL DEFAULT '',
  `user_group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `in_group` int(10) unsigned NOT NULL DEFAULT '0',
  `in_space` int(10) unsigned NOT NULL DEFAULT '0',
  `avatar` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sessionhash`),
  KEY `user_id` (`user_id`),
  KEY `lastactivity` (`lastactivity`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_setting_file`
--

CREATE TABLE IF NOT EXISTS `liv_setting_file` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` char(20) NOT NULL,
  `order` smallint(5) NOT NULL DEFAULT '0',
  `file_name_zh` char(40) NOT NULL,
  `allow_del` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_setting_item`
--

CREATE TABLE IF NOT EXISTS `liv_setting_item` (
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `file_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `item_name` varchar(120) NOT NULL,
  `item_name_zh` char(40) NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `item_val` char(240) NOT NULL,
  `item_val_type` tinyint(1) NOT NULL DEFAULT '0',
  `item_val_set` varchar(1000) NOT NULL,
  `item_val_src` varchar(1000) NOT NULL,
  `showtype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `item_description` char(240) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_share`
--

CREATE TABLE IF NOT EXISTS `liv_share` (
  `share_id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `link` varchar(500) NOT NULL COMMENT '源链接',
  `link_type` varchar(20) NOT NULL,
  `action` varchar(20) NOT NULL DEFAULT '',
  `description` varchar(1500) NOT NULL,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `user_name` char(18) NOT NULL,
  `share_time` int(10) NOT NULL DEFAULT '0',
  `touser_id` int(10) NOT NULL DEFAULT '0',
  `extend_data` varchar(10000) NOT NULL DEFAULT '',
  `comment_count` smallint(4) NOT NULL DEFAULT '0' COMMENT '评论',
  `from_type` int(10) NOT NULL DEFAULT '0' COMMENT '来源',
  `localized` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否本地化',
  PRIMARY KEY (`share_id`),
  KEY `user_id` (`user_id`,`touser_id`),
  KEY `share_time` (`share_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_site_content`
--

CREATE TABLE IF NOT EXISTS `liv_site_content` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增',
  `space_id` int(10) NOT NULL COMMENT '发布到站点',
  `content_id` int(10) NOT NULL COMMENT '内容ID',
  `content_type` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '内容类型',
  `category_id` int(10) NOT NULL COMMENT '内容分类',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态',
  `pubtime` int(10) NOT NULL COMMENT '发布时间',
  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶',
  `commend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐',
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='站点内容' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_site_sort`
--

CREATE TABLE IF NOT EXISTS `liv_site_sort` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增',
  `space_id` int(10) NOT NULL COMMENT '发布到站点',
  `order_id` int(10) NOT NULL COMMENT '排序',
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '分类名',
  `blog_count` int(10) NOT NULL COMMENT '日志数',
  `albums_count` int(10) NOT NULL COMMENT '相册数',
  `picture_count` int(10) NOT NULL COMMENT '照片数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='站点内容分类' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_site_user`
--

CREATE TABLE IF NOT EXISTS `liv_site_user` (
  `user_id` int(10) NOT NULL,
  `space_id` int(10) NOT NULL,
  `join_time` int(10) NOT NULL,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`,`space_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='加入站点的会员！';

-- --------------------------------------------------------

--
-- 表的结构 `liv_smile`
--

CREATE TABLE IF NOT EXISTS `liv_smile` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `smiletext` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(12) NOT NULL COMMENT '表情说明',
  `image` varchar(128) NOT NULL DEFAULT '',
  `order_id` smallint(3) NOT NULL DEFAULT '0',
  `isban` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_sms`
--

CREATE TABLE IF NOT EXISTS `liv_sms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sendto` varchar(60) NOT NULL,
  `content` varchar(600) NOT NULL,
  `add_time` int(10) NOT NULL,
  `send_time` int(10) NOT NULL,
  `cmd` varchar(200) NOT NULL,
  `result` varchar(1000) NOT NULL COMMENT '执行结果',
  `is_exec` tinyint(1) NOT NULL COMMENT '是否执行过',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='短信发送表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_statistics`
--

CREATE TABLE IF NOT EXISTS `liv_statistics` (
  `sta_id` int(20) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(16) NOT NULL,
  `type` int(1) NOT NULL,
  `add_time` int(10) NOT NULL,
  `num` int(100) NOT NULL,
  PRIMARY KEY (`sta_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_sync_app`
--

CREATE TABLE IF NOT EXISTS `liv_sync_app` (
  `sync_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '名称',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `webaddr` varchar(60) NOT NULL COMMENT '网址',
  `url` varchar(300) NOT NULL COMMENT '地址',
  `icon` varchar(20) NOT NULL COMMENT '图标文件名',
  `type` tinyint(1) NOT NULL COMMENT '类型（1-心情,2-日志，3-图片，4-分享，5-其他）',
  `is_sys` tinyint(1) NOT NULL COMMENT '是否系统设定',
  `order_id` int(10) NOT NULL COMMENT '排序',
  `is_open` tinyint(1) NOT NULL COMMENT '是否开放启用',
  `user_count` int(10) NOT NULL COMMENT '已开启人数',
  `sync_account_desc` varchar(32) NOT NULL COMMENT '账号描述',
  `filter_text` varchar(300) NOT NULL COMMENT '过滤单条文字中的指定字串',
  `valid_preg` varchar(255) NOT NULL COMMENT '过滤条件正则式',
  `filter_content_preg` varchar(300) NOT NULL COMMENT '根据正则替换内容中数据',
  `replyfunc` varchar(60) NOT NULL COMMENT '回复到源网站的方法',
  `msg_account` varchar(60) NOT NULL COMMENT '回复帐号',
  `msg_pass` varchar(32) NOT NULL COMMENT '回复密码',
  `apiuri` varchar(60) NOT NULL COMMENT '接口地址',
  `sync_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '同步方式, 0-rss, 1-api, 2-stream',
  `stream_api` varchar(120) NOT NULL COMMENT '流地址',
  `source_link` varchar(200) NOT NULL COMMENT '源链接格式',
  `need_check` tinyint(1) NOT NULL DEFAULT '0' COMMENT '需要验证是否更新过',
  `user_param` varchar(20) NOT NULL COMMENT '用户帐号作为参数',
  PRIMARY KEY (`sync_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_sync_subscribe`
--

CREATE TABLE IF NOT EXISTS `liv_sync_subscribe` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(600) NOT NULL COMMENT '描述',
  `url` varchar(255) NOT NULL,
  `sync_id` int(10) NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `sync_time` int(10) NOT NULL DEFAULT '0',
  `sync_next_time` int(10) NOT NULL DEFAULT '0' COMMENT '下次更新时间',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `user_name` varchar(32) NOT NULL,
  `account` varchar(100) NOT NULL COMMENT '绑定的帐号',
  `data_to_tab` varchar(50) NOT NULL COMMENT '数据入库表名',
  `item_count` int(10) NOT NULL DEFAULT '0' COMMENT '同步数据条数',
  `sync_count` int(10) NOT NULL DEFAULT '0' COMMENT '同步次数',
  `fail_count` int(10) NOT NULL COMMENT '失败次数',
  `error_count` tinyint(1) NOT NULL DEFAULT '0' COMMENT '错误次数',
  `filter_text` varchar(300) NOT NULL COMMENT '过滤单条文字中的指定字串',
  `valid_preg` varchar(255) NOT NULL COMMENT '过滤条件正则式',
  `filter_content_preg` varchar(300) NOT NULL COMMENT '根据正则替换内容中数据',
  `since_id` varchar(20) NOT NULL DEFAULT '0',
  `twitter_user_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `addtime` (`addtime`,`user_id`),
  KEY `sync_id` (`sync_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_s_pm`
--

CREATE TABLE IF NOT EXISTS `liv_s_pm` (
  `sessionId` char(32) NOT NULL COMMENT 'md5之后的session id',
  `sid` int(10) NOT NULL COMMENT 'sid',
  PRIMARY KEY (`sessionId`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_task_list`
--

CREATE TABLE IF NOT EXISTS `liv_task_list` (
  `task_id` int(10) NOT NULL AUTO_INCREMENT,
  `task_name` varchar(255) NOT NULL COMMENT '任务名',
  `tmp_file` varchar(255) NOT NULL COMMENT '互斥访问文件路径',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `exec_file` varchar(255) NOT NULL COMMENT '运行对象',
  `exec` varchar(255) NOT NULL COMMENT 'eval语句',
  `interval` int(10) NOT NULL DEFAULT '1000' COMMENT '运行间隔',
  `use_random_interval` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用随机运行',
  `rand_min` int(10) NOT NULL DEFAULT '0' COMMENT '设定最小随机时间',
  `rand_max` int(10) NOT NULL DEFAULT '0' COMMENT '设定最大随机时间',
  `last_exec` int(10) NOT NULL DEFAULT '0' COMMENT '上次执行时间',
  `log` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否记录日志',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`task_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_test`
--

CREATE TABLE IF NOT EXISTS `liv_test` (
  `test_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '测试主键',
  `category_id` int(10) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(255) NOT NULL COMMENT '测试标题',
  `content` varchar(1800) NOT NULL COMMENT '测试描述',
  `question_count` int(10) NOT NULL DEFAULT '0' COMMENT '测试题数',
  `join_count` int(10) NOT NULL DEFAULT '0' COMMENT '参与人数',
  `truly_rate` float(4,2) NOT NULL COMMENT '准确度',
  `comment_count` int(10) NOT NULL DEFAULT '0' COMMENT '评论数',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `test_result` text NOT NULL COMMENT '测试结果',
  `sketch` varchar(255) NOT NULL COMMENT '示意图片',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否审核',
  `rc_category_id` int(10) NOT NULL DEFAULT '0' COMMENT '推荐类型ID',
  PRIMARY KEY (`test_id`),
  KEY `category_id` (`category_id`),
  KEY `truly_rate` (`truly_rate`),
  KEY `join_count` (`join_count`),
  KEY `rc_category_id` (`rc_category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=900 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_test_result`
--

CREATE TABLE IF NOT EXISTS `liv_test_result` (
  `result_id` int(10) NOT NULL AUTO_INCREMENT,
  `test_id` int(10) NOT NULL COMMENT '测试ID',
  `answer_var` char(2) NOT NULL COMMENT '答案标示',
  `title` varchar(255) NOT NULL,
  `answer` varchar(1000) NOT NULL COMMENT '答案内容',
  `answer_count` int(10) NOT NULL COMMENT '选此答案人数',
  `sketch` varchar(255) NOT NULL COMMENT '答案示意图',
  PRIMARY KEY (`result_id`),
  KEY `test_id` (`test_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4102 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_test_user`
--

CREATE TABLE IF NOT EXISTS `liv_test_user` (
  `user_id` int(10) NOT NULL COMMENT '用户ID',
  `test_id` int(10) NOT NULL COMMENT '测试ID',
  `result_id` int(10) NOT NULL COMMENT '测试结果ID',
  `truly_rate` tinyint(1) NOT NULL DEFAULT '1' COMMENT '准确度(0.1-1 10个等级)',
  `answer_count` int(10) NOT NULL DEFAULT '0' COMMENT '选此答案人数',
  `test_time` int(10) NOT NULL COMMENT '测试时间',
  `privacy` tinyint(1) NOT NULL DEFAULT '0' COMMENT '其他人是否可见(0 -所有人可见 1-不可见)',
  KEY `user_id` (`user_id`),
  KEY `test_id` (`test_id`),
  KEY `result_id` (`result_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_thread`
--

CREATE TABLE IF NOT EXISTS `liv_thread` (
  `thread_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) NOT NULL DEFAULT '0' COMMENT 'category_id',
  `action_id` int(10) NOT NULL COMMENT '关联发起活动表的标识',
  `title` varchar(250) NOT NULL DEFAULT '' COMMENT '话题标题',
  `open` tinyint(1) unsigned DEFAULT '1' COMMENT '是否开放讨论',
  `post_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '帖子数量',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(32) NOT NULL DEFAULT '',
  `pub_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_post_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后帖子发表时间',
  `last_poster` varchar(32) NOT NULL DEFAULT '' COMMENT '最后发帖人',
  `poll_state` tinyint(1) NOT NULL DEFAULT '0',
  `last_vote` int(10) unsigned NOT NULL DEFAULT '0',
  `click_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击数',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属群组',
  `visible` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可见',
  `sticky` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶',
  `vote_total` int(10) unsigned NOT NULL DEFAULT '0',
  `attach_count` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件数量',
  `first_post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '第一帖ID',
  `last_post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后一贴ID',
  `modposts` smallint(5) unsigned NOT NULL DEFAULT '0',
  `quintessence` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否精华',
  `logtext` mediumtext NOT NULL COMMENT '主题操作日志',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `post_table` varchar(50) NOT NULL DEFAULT '' COMMENT '帖子内容所在表',
  `titletext` text,
  `delete_flag` enum('0','1') NOT NULL DEFAULT '0' COMMENT '在回收站',
  `delete_time` int(10) NOT NULL DEFAULT '0' COMMENT '删除时间',
  `forum_id` int(10) NOT NULL DEFAULT '0' COMMENT '论坛id',
  `poll_id` int(10) NOT NULL DEFAULT '0' COMMENT '投票ID',
  `rc_category_id` int(10) NOT NULL DEFAULT '0' COMMENT '推荐类型ID',
  `evaluation` float(4,1) NOT NULL DEFAULT '0.0' COMMENT '评分值',
  `evaluation_num` int(10) NOT NULL DEFAULT '0' COMMENT '评分人数',
  `contain_img` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否包含图片',
  `contain_media` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否包含视频',
  `quintessence_time` int(10) NOT NULL DEFAULT '0' COMMENT '设置为精华时间',
  `attr` varchar(8) NOT NULL,
  `lat` float(17,14) NOT NULL COMMENT 'latitude',
  `lng` float(17,14) NOT NULL COMMENT 'lng',
  `thread_type` int(10) NOT NULL COMMENT '帖子类型',
  PRIMARY KEY (`thread_id`),
  KEY `quintessence` (`quintessence`),
  KEY `last_post_time` (`last_post_time`,`group_id`),
  KEY `user_id` (`user_id`),
  KEY `club_id` (`group_id`),
  KEY `category_id` (`category_id`),
  KEY `forum_id` (`forum_id`),
  KEY `delete_flag` (`delete_flag`),
  KEY `pub_time` (`pub_time`),
  KEY `rc_category_id` (`rc_category_id`),
  KEY `quintessence_time` (`quintessence_time`),
  FULLTEXT KEY `titletext` (`titletext`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26568 ;

--
-- 触发器 `liv_thread`
--
DROP TRIGGER IF EXISTS `add_delete_thread_data_record`;
DELIMITER //
CREATE TRIGGER `add_delete_thread_data_record` AFTER DELETE ON `liv_thread`
 FOR EACH ROW begin
insert into liv_record_delete(tid,member_id,table_name,delete_time) values

(old.thread_id,old.user_id,'liv_thread',now());
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_thread_category`
--

CREATE TABLE IF NOT EXISTS `liv_thread_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID自动增加',
  `category_name` char(100) NOT NULL COMMENT '分类名',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID 0 表示为第一级',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员号',
  `thread_count` int(10) unsigned NOT NULL DEFAULT '0',
  `post_count` int(10) NOT NULL DEFAULT '0',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '群组ID',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_thread_type`
--

CREATE TABLE IF NOT EXISTS `liv_thread_type` (
  `t_typeid` int(10) NOT NULL AUTO_INCREMENT COMMENT '帖子类型id',
  `type_name` varchar(50) NOT NULL COMMENT '类型名称',
  PRIMARY KEY (`t_typeid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_user`
--

CREATE TABLE IF NOT EXISTS `liv_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '会员id',
  `member_id` int(10) NOT NULL,
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '会员名称',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '会员密码',
  `email` varchar(80) NOT NULL COMMENT '会员email',
  `salt` varchar(6) NOT NULL DEFAULT '' COMMENT '会员email',
  `avatar` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有会员头像',
  `user_group_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员组id',
  `user_rank_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户级别',
  `user_score` float(12,2) NOT NULL DEFAULT '0.00' COMMENT '用户积分',
  `user_score_total` float(12,2) NOT NULL DEFAULT '0.00',
  `join_time` int(10) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `birthday` char(10) NOT NULL DEFAULT '0' COMMENT '出生日期',
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `lastactivity` int(10) NOT NULL DEFAULT '0' COMMENT '最后活动时间',
  `lastvisit` int(10) NOT NULL DEFAULT '0' COMMENT '最后访问时间',
  `location_id` char(20) NOT NULL COMMENT '所属地区',
  `constellations` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '星座',
  `realname` varchar(60) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL COMMENT '城市',
  `privacy` char(20) NOT NULL COMMENT '隐私设置',
  `hometown` char(20) NOT NULL COMMENT '家乡',
  `email_verified` tinyint(1) NOT NULL,
  `is_ban` tinyint(1) NOT NULL,
  `privacy_control` char(20) NOT NULL,
  `birthday_format` tinyint(1) NOT NULL DEFAULT '0' COMMENT '生日显示格式 0 默认 1 月-日',
  `grands_num` int(4) NOT NULL COMMENT '用户已做地主的个数',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `location_id` (`location_id`),
  KEY `lastactivity` (`lastactivity`),
  KEY `state` (`state`),
  KEY `avatar` (`avatar`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员表' AUTO_INCREMENT=84912 ;

--
-- 触发器 `liv_user`
--
DROP TRIGGER IF EXISTS `t_i_liv_user`;
DELIMITER //
CREATE TRIGGER `t_i_liv_user` AFTER INSERT ON `liv_user`
 FOR EACH ROW begin
	insert into community_game.liv_player (user_id,user_name) values (NEW.user_id,NEW.user_name);
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_user_apply`
--

CREATE TABLE IF NOT EXISTS `liv_user_apply` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `group_id` int(10) NOT NULL COMMENT '讨论区id',
  `apply_time` int(10) NOT NULL COMMENT '申请时间',
  `accept_time` int(10) NOT NULL COMMENT '受理时间',
  `is_agree` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否同意该用户作为“地主”',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户申请做“地主”记录表' AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_user_experience`
--

CREATE TABLE IF NOT EXISTS `liv_user_experience` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT '用户ID',
  `nid` int(10) NOT NULL COMMENT '学校或其他的id',
  `faculties_id` int(10) NOT NULL COMMENT '学院id',
  `faculties_name` varchar(100) NOT NULL COMMENT '学院名称',
  `name` varchar(60) NOT NULL COMMENT '名称',
  `description` varchar(200) NOT NULL COMMENT '描述',
  `type` tinyint(1) NOT NULL COMMENT '类型，见配置',
  `start_year` smallint(4) NOT NULL COMMENT '网络ID',
  `start_month` tinyint(2) NOT NULL COMMENT '1 - 地区，2 - 学校',
  `end_year` smallint(4) NOT NULL DEFAULT '0' COMMENT '结束年',
  `end_month` tinyint(2) NOT NULL DEFAULT '0' COMMENT '结束月',
  `add_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `nid` (`nid`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_user_extents`
--

CREATE TABLE IF NOT EXISTS `liv_user_extents` (
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员id',
  `albums_count` int(10) NOT NULL,
  `picture_count` int(10) NOT NULL,
  `comment_count` int(10) NOT NULL,
  `picture_size` int(10) NOT NULL,
  `uploads_size` int(10) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员信息扩展表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_user_group`
--

CREATE TABLE IF NOT EXISTS `liv_user_group` (
  `user_group_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '会员组id',
  `user_group_name` varchar(255) NOT NULL DEFAULT '' COMMENT '会员组名称',
  `user_group_level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '会员组级别',
  `user_group_order` tinyint(1) NOT NULL DEFAULT '0',
  `user_group_update` tinyint(1) NOT NULL DEFAULT '0',
  `user_group_member_count` int(10) NOT NULL DEFAULT '0',
  `user_group_icon` varchar(100) NOT NULL DEFAULT '',
  `canadmin` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员组表' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_user_join`
--

CREATE TABLE IF NOT EXISTS `liv_user_join` (
  `id` int(10) NOT NULL COMMENT '内容ID',
  `type` tinyint(1) NOT NULL COMMENT '内容类型(参照评论表类型)',
  `content` varchar(300) NOT NULL COMMENT '评论内容',
  `join_time` int(10) NOT NULL COMMENT '参与事件',
  `user_id` int(10) NOT NULL COMMENT '参与用户ID',
  `update_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`type`,`user_id`),
  KEY `update_time` (`update_time`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `liv_user_space`
--

CREATE TABLE IF NOT EXISTS `liv_user_space` (
  `space_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '空间id',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员id',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '会员昵称',
  `name` varchar(255) NOT NULL COMMENT '空间名称',
  `description` varchar(255) NOT NULL COMMENT '空间描述',
  `space_head` char(64) NOT NULL DEFAULT '0' COMMENT '空间头的图象',
  `sh_height` int(4) NOT NULL DEFAULT '0',
  `space_background` char(64) NOT NULL DEFAULT '0' COMMENT '空间背景图',
  `space_style_id` int(10) NOT NULL DEFAULT '0' COMMENT '空间风格id',
  `permission` int(10) NOT NULL DEFAULT '0' COMMENT '博客权限',
  `blog_count` int(10) NOT NULL DEFAULT '0' COMMENT '日志数',
  `albums_count` int(10) NOT NULL DEFAULT '0' COMMENT '相册数',
  `picture_count` int(10) NOT NULL DEFAULT '0' COMMENT '图片数',
  `comment_count` int(10) NOT NULL DEFAULT '0' COMMENT '评论数',
  `today_visit` int(10) NOT NULL DEFAULT '0' COMMENT '今日访问数',
  `total_visit` int(10) NOT NULL DEFAULT '0' COMMENT '总访问数',
  `info_visit` int(10) NOT NULL DEFAULT '0' COMMENT '个人资料页访问数',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `last_update` int(10) NOT NULL DEFAULT '0' COMMENT '访问统计上次更新时间',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '空间状态',
  `picture_size` int(10) NOT NULL DEFAULT '0',
  `uploads_size` int(10) NOT NULL DEFAULT '0',
  `space_dir` char(25) NOT NULL,
  `org_space_dir` char(20) NOT NULL,
  `domain` varchar(60) NOT NULL DEFAULT ' ',
  `location_id` char(20) NOT NULL COMMENT '所属地区',
  `html_mode` tinyint(1) NOT NULL DEFAULT '2' COMMENT '空间页面用户自定义的样式1：自由，2：分栏，默认自由',
  `html_subfieldmode` tinyint(1) NOT NULL DEFAULT '3' COMMENT '空间页面用户自定义的分栏样式[1:1-3, 2:3-1, 3:1-2-1, 4:1-1-2, 5:2-1-1, 6:2-2],默认3',
  `recommendsite` tinyint(1) NOT NULL DEFAULT '0',
  `recommendtime` int(10) NOT NULL DEFAULT '0',
  `snap` varchar(255) NOT NULL DEFAULT '',
  `boot_flash_id` int(10) NOT NULL DEFAULT '0',
  `mouse_icon_id` int(10) NOT NULL DEFAULT '0',
  `welcome_flash_id` int(10) NOT NULL DEFAULT '0',
  `head_id` int(10) NOT NULL DEFAULT '0',
  `pattern_id` int(10) NOT NULL DEFAULT '0',
  `pattern_attribute` text NOT NULL,
  `prms` tinyint(1) NOT NULL DEFAULT '4' COMMENT '是否允许加入',
  `wait_auditing_num` int(10) NOT NULL DEFAULT '0' COMMENT '等待审核会员数',
  `already_join_num` int(10) NOT NULL DEFAULT '0' COMMENT '已经加入会员数',
  `allows_issuance` tinyint(1) NOT NULL COMMENT '是否允许发布',
  `join_check` tinyint(1) NOT NULL COMMENT '加入是否需要审核',
  `issuance_check` tinyint(1) NOT NULL COMMENT '发布是否需要审核',
  PRIMARY KEY (`space_id`),
  KEY `location_id` (`location_id`),
  KEY `user_id` (`user_id`),
  KEY `recommendtime` (`recommendtime`,`recommendsite`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户空间表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_verify_code`
--

CREATE TABLE IF NOT EXISTS `liv_verify_code` (
  `user_id` int(10) NOT NULL,
  `user_name` char(24) NOT NULL,
  `verify_code` char(20) NOT NULL,
  `verify_send_time` int(10) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮件验证表';

-- --------------------------------------------------------

--
-- 表的结构 `liv_visit`
--

CREATE TABLE IF NOT EXISTS `liv_visit` (
  `visit_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '访客id',
  `user_name` varchar(60) NOT NULL COMMENT '访客名',
  `avatar` tinyint(1) NOT NULL DEFAULT '0' COMMENT '访客是否有头像',
  `user_realname` varchar(20) DEFAULT NULL COMMENT '访客真实名称',
  `visit_time` int(10) NOT NULL DEFAULT '0' COMMENT '访问时间',
  `from_ip` varchar(30) NOT NULL DEFAULT '' COMMENT '访客ip地址',
  `space_id` int(10) NOT NULL DEFAULT '0' COMMENT '空间id',
  `space_user_id` int(10) NOT NULL DEFAULT '0' COMMENT '空间主人id',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '群组id',
  `stat_type` tinyint(1) NOT NULL,
  `id` int(10) NOT NULL,
  PRIMARY KEY (`visit_id`),
  KEY `user_id` (`user_id`),
  KEY `space_id` (`space_id`),
  KEY `group_id` (`group_id`),
  KEY `visit_time` (`visit_time`),
  KEY `stat_type` (`stat_type`,`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='访问记录表' AUTO_INCREMENT=4886 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_wcache`
--

CREATE TABLE IF NOT EXISTS `liv_wcache` (
  `province` char(10) NOT NULL DEFAULT '',
  `city` char(10) NOT NULL DEFAULT '',
  `lastmodify` int(10) NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  UNIQUE KEY `city` (`city`),
  KEY `lastmodify` (`lastmodify`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `uchome_album`
--

CREATE TABLE IF NOT EXISTS `uchome_album` (
  `albumid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `albumname` varchar(50) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(15) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `picnum` smallint(6) unsigned NOT NULL DEFAULT '0',
  `pic` varchar(60) NOT NULL DEFAULT '',
  `picflag` tinyint(1) NOT NULL DEFAULT '0',
  `friend` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(10) NOT NULL DEFAULT '',
  `target_ids` text NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `new_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`albumid`),
  KEY `uid` (`uid`,`updatetime`),
  KEY `updatetime` (`updatetime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1030 ;

-- --------------------------------------------------------

--
-- 表的结构 `uchome_pic`
--

CREATE TABLE IF NOT EXISTS `uchome_pic` (
  `picid` mediumint(8) NOT NULL AUTO_INCREMENT,
  `albumid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `topicid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(15) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `postip` varchar(20) NOT NULL DEFAULT '',
  `filename` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  `filepath` varchar(60) NOT NULL DEFAULT '',
  `thumb` tinyint(1) NOT NULL DEFAULT '0',
  `remote` tinyint(1) NOT NULL DEFAULT '0',
  `hot` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `click_6` smallint(6) unsigned NOT NULL DEFAULT '0',
  `click_7` smallint(6) unsigned NOT NULL DEFAULT '0',
  `click_8` smallint(6) unsigned NOT NULL DEFAULT '0',
  `click_9` smallint(6) unsigned NOT NULL DEFAULT '0',
  `click_10` smallint(6) unsigned NOT NULL DEFAULT '0',
  `magicframe` tinyint(6) NOT NULL DEFAULT '0',
  `new_id` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`picid`),
  KEY `albumid` (`albumid`,`dateline`),
  KEY `topicid` (`topicid`,`dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19919 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
