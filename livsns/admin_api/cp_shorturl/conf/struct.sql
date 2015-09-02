-- phpMyAdmin SQL Dump
-- version 3.4.3.1
-- http://www.phpmyadmin.net
--
-- 主机: 10.0.1.31
-- 生成日期: 2012 年 07 月 03 日 16:56
-- 服务器版本: 5.5.9
-- PHP 版本: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `dev_sns_shorturl`
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7507 ;

-- --------------------------------------------------------

--
-- 表的结构 `liv_urlset`
--

CREATE TABLE IF NOT EXISTS `liv_urlset` (
  `last_number` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
