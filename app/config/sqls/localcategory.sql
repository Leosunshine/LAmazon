-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-07-30 08:42:53
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lamazon`
--

-- --------------------------------------------------------

--
-- 表的结构 `localcategory`
--

DROP TABLE IF EXISTS `localcategory`;
CREATE TABLE IF NOT EXISTS `localcategory` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `remark` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `level` int(5) NOT NULL COMMENT '级别, 0-root',
  `is_end_point` int(1) NOT NULL,
  `parent_id` int(10) NOT NULL,
  `amazon_category_id` int(11) NOT NULL,
  `variation_theme` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `amazon_node_path` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `amazon_nodeId` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `localcategory`
--

INSERT INTO `localcategory` (`id`, `name`, `remark`, `level`, `is_end_point`, `parent_id`, `amazon_category_id`, `variation_theme`, `amazon_node_path`, `amazon_nodeId`) VALUES
(1, 'root', '根', 0, 0, 0, 0, NULL, NULL, NULL),
(2, '汽车配件', NULL, 1, 1, 1, 55, NULL, '汽车和摩托车/配件', '79923031'),
(3, '汽车配件杂项', NULL, 2, 1, 2, 256, 'Size|Color', '汽车和摩托车/配件', '79923031'),
(4, '蓝牙音箱', NULL, 1, 1, 1, 256, 'Size|Color', '电子产品和照片/高保真音响/扬声器/低音炮', '761268'),
(5, '家', NULL, 1, 1, 1, 230, 'Size|Color|Scent|StyleName|CustomerPackageType', '厨房和家庭', '﻿3169011'),
(6, '床浴', NULL, 2, 1, 5, 230, 'Size|Color|Scent|StyleName|CustomerPackageType', '厨房和家庭/浴室/浴室配件/浴帘及配件', '3273900031');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
