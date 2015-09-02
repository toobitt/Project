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
-- 数据库: `sns_shorturl`
--

-- --------------------------------------------------------

--
-- 表的结构 `liv_urls`
--

CREATE TABLE IF NOT EXISTS `liv_urls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` text CHARACTER SET utf8 NOT NULL,
  `code` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `alias` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `click_count` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_urlset`
--

CREATE TABLE IF NOT EXISTS `liv_urlset` (
  `last_number` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
