-- phpMyAdmin SQL Dump
-- version 2.11.9
-- http://www.phpmyadmin.net
--
-- 主机: 10.0.1.80
-- 生成日期: 2011 年 08 月 22 日 09:09
-- 服务器版本: 5.5.9
-- PHP 版本: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `vod`
--

-- --------------------------------------------------------

--
-- 表的结构 `liv_vodinfo`
--

CREATE TABLE IF NOT EXISTS `liv_vodinfo` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `vod_sort_id` int(10) DEFAULT NULL COMMENT '视频类别',
  `comment` int(10) DEFAULT NULL COMMENT '视频评论',
  `audiohz` int(10) DEFAULT NULL COMMENT '音频',
  `copyright` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '版权',
  `author` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '上传人',
  `title` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '视频标题',
  `height` smallint(10) DEFAULT '0' COMMENT '视频的高度',
  `start` float DEFAULT '0' COMMENT '视频开始时间',
  `duration` int(10) DEFAULT '0' COMMENT '视频总的时长',
  `bitrate` int(10) DEFAULT '0' COMMENT '码流',
  `width` smallint(10) DEFAULT '0' COMMENT '视频的宽度',
  `status` tinyint(4) DEFAULT '0' COMMENT '转码状态(0=>"正在转码中"，1=>"转码完成"，2=>"审核通过",3=>"审核不过")',
  `img` varchar(250) CHARACTER SET utf8 DEFAULT NULL COMMENT '图片路径',
  `vodid` char(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '视频id',
  `type` varchar(10) CHARACTER SET utf8 DEFAULT NULL COMMENT '视频类型（avi，还是3gp等等）',
  `transize` int(10) DEFAULT NULL COMMENT '转码中的视频已转码大小',
  `totalsize` int(10) DEFAULT NULL,
  `audit` tinyint(4) DEFAULT '0',
  `flag` tinyint(4) DEFAULT '0' COMMENT '判断是否已更新',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  `ip` char(20) CHARACTER SET utf8 DEFAULT NULL COMMENT 'ip',
  `vod_leixing` int(10) DEFAULT NULL COMMENT '所属类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=117 ;

--
-- 导出表中的数据 `liv_vodinfo`
--

INSERT INTO `liv_vodinfo` (`id`, `vod_sort_id`, `comment`, `audiohz`, `copyright`, `author`, `title`, `height`, `start`, `duration`, `bitrate`, `width`, `status`, `img`, `vodid`, `type`, `transize`, `totalsize`, `audit`, `flag`, `create_time`, `update_time`, `ip`, `vod_leixing`) VALUES
(101, NULL, 0, 0, '', '', '', 336, 0, 52500, 286, 448, 1, 'http://localhost/livsns/uploads/vod/091/282/816/277/91282816277.jpg\r\n\r\n\r\n', '91282816277', '.flv', 3687424, 3687343, 0, 1, 1313733599, 1313733638, '127.0.0.1', NULL),
(102, NULL, 0, 0, '', '', '', 280, 0, 431800, 295, 448, 2, 'http://localhost/livsns/uploads/vod/058/129/951/730/58129951730.jpg\r\n\r\n\r\n', '58129951730', '.flv', 29501440, 27583488, 0, 1, 1313733619, 1313733719, '127.0.0.1', NULL),
(103, NULL, 0, 0, '', '', '', 324, 0, 396200, 314, 432, 1, 'http://localhost/livsns/uploads/vod/034/743/793/727/34743793727.jpg\r\n\r\n\r\n', '34743793727', '.flv', 26289152, 25344000, 0, 1, 1313733964, 1313736927, '127.0.0.1', NULL),
(104, NULL, 0, 0, '', '', '', 288, 0, 313000, 251, 512, 2, 'http://localhost/livsns/uploads/vod/063/750/183/675/63750183675.jpg\r\n\r\n\r\n', '63750183675', '.flv', 20827136, 20031488, 0, 1, 1313733977, 1313736927, '127.0.0.1', NULL),
(105, NULL, 0, 0, '', '', '', 336, 0, 193200, 283, 448, 1, 'http://localhost/livsns/uploads/vod/033/253/724/128/33253724128.jpg\r\n\r\n\r\n', '33253724128', '.flv', 13037568, 12351488, 0, 1, 1313737030, 1313737086, '127.0.0.1', NULL),
(106, NULL, 0, 0, '', '', '', 336, 0, 255400, 283, 448, 2, 'http://localhost/livsns/uploads/vod/076/660/693/110/76660693110.jpg\r\n\r\n\r\n', '76660693110', '.flv', 17574912, 16319488, 0, 1, 1313737043, 1313737102, '127.0.0.1', NULL),
(107, NULL, 0, 0, '', '', '', 288, 0, 313000, 251, 512, 1, 'http://localhost/livsns/uploads/vod/030/920/898/215/30920898215.jpg\r\n\r\n\r\n', '30920898215', '.flv', 20827136, 20031488, 0, 1, 1313744728, 1313744792, '127.0.0.1', NULL),
(108, NULL, 0, 0, '', '', '', 336, 0, 123300, 283, 448, 1, 'http://localhost/livsns/uploads/vod/085/986/366/542/85986366542.jpg\r\n\r\n\r\n', '85986366542', '.flv', 7928832, 7929137, 0, 1, 1313747945, 1313747988, '127.0.0.1', NULL),
(109, NULL, 0, 0, '', '', '', 324, 0, 23400, 271, 432, 1, 'http://localhost/livsns/uploads/vod/079/133/075/801/79133075801.jpg\r\n\r\n\r\n', '79133075801', '.flv', 1025024, 1025289, 0, 1, 1313747947, 1313747988, '127.0.0.1', NULL),
(110, NULL, 0, 0, '', '', '', 288, 0, 429800, 277, 512, 1, 'http://localhost/livsns/uploads/vod/060/107/639/804/60107639804.jpg\r\n\r\n\r\n', '60107639804', '.flv', 28722176, 27455488, 0, 1, 1313747956, 1313748049, '127.0.0.1', NULL),
(111, NULL, 0, 0, '', '', '', 336, 0, 193200, 283, 448, 1, 'http://localhost/livsns/uploads/vod/087/797/647/016/87797647016.jpg\r\n\r\n\r\n', '87797647016', '.flv', 13037568, 12351488, 0, 1, 1313747960, 1313748016, '127.0.0.1', NULL),
(112, NULL, 0, 0, '', '', '', 336, 0, 426400, 289, 448, 1, 'http://localhost/livsns/uploads/vod/040/115/798/078/40115798078.jpg\r\n\r\n\r\n', '40115798078', '.flv', 29391872, 27264000, 0, 1, 1313748085, 1313748584, '127.0.0.1', NULL),
(113, NULL, 0, 0, '', '', '', 288, 0.008, 431200, 306, 512, 1, 'http://localhost/livsns/uploads/vod/020/023/298/915/20023298915.jpg\r\n\r\n\r\n', '20023298915', '.flv', 29186048, 27583488, 0, 1, 1313748095, 1313748584, '127.0.0.1', NULL),
(114, NULL, 0, 0, '', '', '', 288, 0.005, 422500, 263, 512, 1, 'http://localhost/livsns/uploads/vod/079/249/235/382/79249235382.jpg\r\n\r\n\r\n', '79249235382', '.flv', 29279232, 27008000, 0, 1, 1313748108, 1313748584, '127.0.0.1', NULL),
(115, NULL, 0, 0, '', '', '', 288, 0, 397600, 296, 512, 1, 'http://localhost/livsns/uploads/vod/041/897/133/830/41897133830.jpg\r\n\r\n\r\n', '41897133830', '.flv', 26911744, 25407488, 0, 1, 1313748130, 1313748584, '127.0.0.1', NULL),
(116, NULL, 0, 0, '', '', '', 288, 0, 429800, 277, 512, 1, 'http://localhost/livsns/uploads/vod/024/833/947/978/24833947978.jpg\r\n\r\n\r\n', '24833947978', '.flv', 28722176, 27455488, 0, 1, 1313748159, 1313748584, '127.0.0.1', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `liv_vod_sort`
--

CREATE TABLE IF NOT EXISTS `liv_vod_sort` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `sort_name` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '视频类名',
  `father` int(4) NOT NULL COMMENT '所属类型id:1=>编辑上传，2=>网友上传，3=>直播归档，4=>标注归档',
  `audit` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '更新时间',
  `ip` char(20) NOT NULL COMMENT 'ip',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- 导出表中的数据 `liv_vod_sort`
--

INSERT INTO `liv_vod_sort` (`id`, `sort_name`, `father`, `audit`, `create_time`, `update_time`, `ip`) VALUES
(1, '游戏类别', 1, 1, 0, 0, '127.0.0.1'),
(2, '娱乐新闻', 2, 1, 0, 0, '127.0.0.1'),
(3, '经济新闻', 3, 1, 0, 0, '127.0.0.1'),
(6, '网上采集', 4, 1, 0, 0, '127.0.0.1'),
(7, '军事视频', 2, 0, 1312960922, 1312960922, '127.0.0.1'),
(8, '时政要闻', 3, 0, 1312961015, 1312961015, '127.0.0.1'),
(9, '生活小常识', 1, 0, 1312961655, 1312961655, '127.0.0.1');
