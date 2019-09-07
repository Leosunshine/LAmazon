-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-09-07 07:40:17
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
-- 表的结构 `shop`
--

CREATE TABLE IF NOT EXISTS `shop` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(300) CHARACTER SET utf8 NOT NULL DEFAULT 'No name' COMMENT '店铺名称',
  `amazon_merchant_id` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '亚马逊商家ID',
  `amazon_token` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '亚马逊MWS授权令牌',
  `user` int(10) DEFAULT NULL COMMENT '所属用户， 0 为无效店铺',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `shop`
--

INSERT INTO `shop` (`id`, `name`, `amazon_merchant_id`, `amazon_token`, `user`) VALUES
(1, 'Default Shop(DADAM)', 'AB0EMHVN49K0J', 'amzn.mws.02b4f06d-85c4-26fd-f030-06456b5a1dfa', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
