-- phpMyAdmin SQL Dump
-- version 2.11.9
-- http://www.phpmyadmin.net
--
-- 主机: 10.0.1.80
-- 生成日期: 2011 年 08 月 22 日 09:05
-- 服务器版本: 5.5.9
-- PHP 版本: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `tuji`
--

-- --------------------------------------------------------

--
-- 表的结构 `liv_pics`
--

CREATE TABLE IF NOT EXISTS `liv_pics` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tuji_id` int(10) NOT NULL COMMENT '关联图集ID',
  `old_name` char(50) NOT NULL COMMENT '原始文件名称',
  `new_name` char(50) NOT NULL COMMENT '上传之后的文件名',
  `thumb_name` char(50) NOT NULL COMMENT '缩略图名称',
  `desc` varchar(500) NOT NULL,
  `path` varchar(250) NOT NULL,
  `total_visit` int(10) NOT NULL,
  `status` tinyint(2) NOT NULL COMMENT '0=>新增 1=>审核通过 2=>软删除 3=>彻底删除',
  `create_time` int(10) NOT NULL COMMENT '上传时间',
  `update_time` int(10) NOT NULL COMMENT '修改时间',
  `ip` char(20) NOT NULL COMMENT '上传IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 导出表中的数据 `liv_pics`
--

INSERT INTO `liv_pics` (`id`, `tuji_id`, `old_name`, `new_name`, `thumb_name`, `desc`, `path`, `total_visit`, `status`, `create_time`, `update_time`, `ip`) VALUES
(1, 1, '3.jpg', '201108180509444e4cd6d8dc5cb.jpg', '', '', '001/', 0, 1, 1313658584, 0, '127.0.0.1'),
(2, 1, '0000000420091121132855.jpg', '201108180509454e4cd6d960498.jpg', '', '', '001/', 0, 1, 1313658585, 0, '127.0.0.1'),
(3, 1, '0000000420100112162904.jpg', '201108180509464e4cd6da11e65.jpg', '', '', '001/', 0, 1, 1313658586, 0, '127.0.0.1'),
(4, 1, 'Jellyfish.jpg', '201108180509474e4cd6db68282.jpg', '', '', '001/', 0, 1, 1313658587, 0, '127.0.0.1'),
(5, 1, 'Koala.jpg', '201108180509474e4cd6dbd5200.jpg', '', '', '001/', 0, 1, 1313658587, 0, '127.0.0.1'),
(6, 1, 'Lighthouse.jpg', '201108180509484e4cd6dc58932.jpg', '', '', '001/', 0, 1, 1313658588, 0, '127.0.0.1'),
(7, 1, 'Penguins.jpg', '201108180509484e4cd6dcc76b7.jpg', '', '', '001/', 0, 1, 1313658588, 0, '127.0.0.1'),
(8, 1, 'Tulips.jpg', '201108180509494e4cd6dd42daf.jpg', '', '', '001/', 0, 1, 1313658589, 0, '127.0.0.1');

-- --------------------------------------------------------

--
-- 表的结构 `liv_tuji`
--

CREATE TABLE IF NOT EXISTS `liv_tuji` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tuji_sort_id` int(10) NOT NULL COMMENT '关联图集分类ID',
  `title` varchar(300) NOT NULL COMMENT '图集标题',
  `desc` varchar(1000) NOT NULL COMMENT '图集描述',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `ip` char(20) NOT NULL COMMENT '创建IP',
  `update_time` int(10) NOT NULL COMMENT '修改时间',
  `status` tinyint(2) NOT NULL COMMENT '状态 0=>未审核 1=>已审核 2=>软删除 3=>彻底删除',
  `total_pic` int(10) NOT NULL COMMENT '图片总数',
  `total_visit` int(10) NOT NULL COMMENT '图集访问量',
  `total_comment` int(10) NOT NULL COMMENT '图集评论数',
  `cover_url` varchar(250) NOT NULL COMMENT '图集封面',
  `path` varchar(250) NOT NULL COMMENT '图集目录',
  `latest` varchar(200) NOT NULL COMMENT '最新图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 导出表中的数据 `liv_tuji`
--

INSERT INTO `liv_tuji` (`id`, `tuji_sort_id`, `title`, `desc`, `create_time`, `ip`, `update_time`, `status`, `total_pic`, `total_visit`, `total_comment`, `cover_url`, `path`, `latest`) VALUES
(1, 2, '我的故乡图集1', '这里用于存放故乡的图集', 1313386813, '127.0.0.1', 0, 1, 0, 0, 0, '001/tuji_201108150359224e48d1dab6b7c.jpg', '', 'a:4:{i:0;s:35:"001/201108180509474e4cd6dbd5200.jpg";i:1;s:35:"001/201108180509484e4cd6dc58932.jpg";i:2;s:35:"001/201108180509484e4cd6dcc76b7.jpg";i:3;s:35:"001/201108180509494e4cd6dd42daf.jpg";}');

-- --------------------------------------------------------

--
-- 表的结构 `liv_tuji_comment`
--

CREATE TABLE IF NOT EXISTS `liv_tuji_comment` (
  `id` int(10) NOT NULL,
  `tuji_id` int(10) NOT NULL,
  `content` varchar(600) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `time` int(10) NOT NULL,
  `status` tinyint(2) NOT NULL COMMENT '0=>新增 1=>审核通过 2=>删除',
  `ip` char(20) NOT NULL,
  `source` char(30) NOT NULL COMMENT '评论来源',
  `support` int(10) NOT NULL COMMENT '支持数',
  `reffer_id` int(10) NOT NULL COMMENT '引用评论ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 导出表中的数据 `liv_tuji_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `liv_tuji_sort`
--

CREATE TABLE IF NOT EXISTS `liv_tuji_sort` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sort_name` varchar(50) NOT NULL COMMENT '分类名称',
  `sort_desc` varchar(250) NOT NULL COMMENT '分类描述',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `ip` varchar(25) NOT NULL COMMENT '创建IP',
  `update_time` int(10) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sort_name` (`sort_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 导出表中的数据 `liv_tuji_sort`
--

INSERT INTO `liv_tuji_sort` (`id`, `sort_name`, `sort_desc`, `create_time`, `ip`, `update_time`) VALUES
(2, '我的故乡', '这个图集主要用于存放家乡的照片', 1311665043, '127.0.0.1', 1312938445),
(3, '你的家乡2', '你的家乡描', 0, '', 1312938428),
(4, '他的故乡', '坎坎坷坷', 1312944528, '127.0.0.1', 0),
(7, '你的家乡', '', 1312945565, '127.0.0.1', 0);
