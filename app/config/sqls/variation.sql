-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-11-17 13:05:29
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
-- 表的结构 `variation`
--

DROP TABLE IF EXISTS `variation`;
CREATE TABLE IF NOT EXISTS `variation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `SKU` varchar(50) DEFAULT NULL,
  `EAN` varchar(20) DEFAULT NULL,
  `amazon_status` varchar(200) NOT NULL DEFAULT '10111' COMMENT '参考开发日志',
  `inventory_count` int(30) NOT NULL,
  `price_bonus` double(10,3) DEFAULT '0.000' COMMENT '加价',
  `images` varchar(200) DEFAULT NULL,
  `product_id` int(10) DEFAULT NULL COMMENT '所属产品id, 为0时变体失效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
