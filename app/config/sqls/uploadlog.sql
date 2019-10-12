-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-10-12 07:16:02
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
-- 表的结构 `uploadlog`
--

CREATE TABLE IF NOT EXISTS `uploadlog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `submission_id` varchar(20) CHARACTER SET utf8 NOT NULL,
  `quid` varchar(50) CHARACTER SET utf8 NOT NULL,
  `type` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `detail_url` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `product_count` int(5) DEFAULT NULL,
  `variation_count` int(5) DEFAULT NULL,
  `status` int(1) DEFAULT NULL COMMENT '1 - submitted 2 - done 0 - failed',
  `timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
