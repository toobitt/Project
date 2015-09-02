-- phpMyAdmin SQL Dump
-- version 2.11.9
-- http://www.phpmyadmin.net
--
-- 主机: 10.0.1.80
-- 生成日期: 2011 年 03 月 10 日 20:58
-- 服务器版本: 5.1.31
-- PHP 版本: 5.2.6

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

--
-- 导出表中的数据 `liv_user_set`
--

INSERT INTO `liv_user_set` (`id`, `name`, `identi`, `status`, `descripion`, `creattime`, `updatetime`, `creator`, `style`) VALUES
(47, 'dddddd', 'dd', '是', 'ddddddddddd', 1299745035, 1299754447, 'top625', 1),
(45, 'az', 'AZ', '文本框', 'AZ', 1299744951, 1299754447, 'top625', 3),
(56, 'dee', 'eee', '否', 'eeee', 1299753228, 1299754447, 'top625', 1),
(48, '邮件账号', 'Email', 'hr@hr.com', '用户的', 1299747181, 1299754447, 'top625', 3),
(59, 'cf', 'cf', '是', 'cf', 1299753443, 1299754447, 'top625', 1);


--
-- 记录email `liv_email`
--
CREATE TABLE  `sns_ucenter`.`liv_email` (
`email` CHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'email---记录存储',
PRIMARY KEY (  `email` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci