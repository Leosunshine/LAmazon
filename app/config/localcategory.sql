-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-07-04 04:22:40
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
  `amazon_node_path` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `amazon_nodeId` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `localcategory`
--

INSERT INTO `localcategory` (`id`, `name`, `remark`, `level`, `is_end_point`, `parent_id`, `amazon_category_id`, `amazon_node_path`, `amazon_nodeId`) VALUES
(1, 'root', '根', 0, 0, 0, 0, NULL, NULL),
(5, '汽车配件', NULL, 1, 1, 1, 60, NULL, NULL),
(6, '汽车配件杂项', NULL, 2, 1, 5, 55, 'Auto & Motorrad', '﻿79899031'),
(7, '汽车零件', NULL, 2, 1, 5, 56, NULL, NULL),
(8, '婴儿', NULL, 1, 1, 1, 76, NULL, NULL),
(9, '婴儿用品', NULL, 2, 1, 8, 76, 'Baby/Baby- & Kleinkindspielzeug/Lernuhren', '509879031'),
(10, '服装', NULL, 1, 1, 1, 9, '服装/男孩/西服和萨克斯/套装', '2795102031');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
