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
-- 数据库: `sns_banword`
--

-- --------------------------------------------------------

--
-- 表的结构 `liv_banword`
--

CREATE TABLE IF NOT EXISTS `liv_banword` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `banname` char(30) NOT NULL,
  `banwd` varchar(120) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;
