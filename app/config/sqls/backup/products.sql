-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-07-30 08:43:31
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
-- 表的结构 `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) CHARACTER SET utf8 DEFAULT 'No name' COMMENT '商品名称',
  `keywords` varchar(300) CHARACTER SET utf8 DEFAULT NULL COMMENT '产品关键字',
  `keypoints` varchar(600) CHARACTER SET utf8 DEFAULT NULL COMMENT '要点说明',
  `description` varchar(1000) CHARACTER SET utf8 DEFAULT NULL COMMENT '产品描述',
  `abbr_en` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '英文简称',
  `abbr_cn` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `ASIN` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `SKU` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '卖家库存单位',
  `images` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `main_image_id` int(10) DEFAULT NULL,
  `product_count` int(10) DEFAULT NULL COMMENT '库存数量',
  `perpackage_count` int(10) DEFAULT NULL COMMENT '每包数量',
  `review_status` int(4) DEFAULT '2' COMMENT '1-通过; 2-待审核;3-过滤;4-侵权;5-屏蔽',
  `appear_status` int(4) DEFAULT '2' COMMENT '1-上架; 2-下架; 3-失效',
  `amazon_status` int(4) DEFAULT '1' COMMENT '1-待上传; 2-已上传; 3-待删除; 4-已删除',
  `security_status` int(4) DEFAULT '1' COMMENT '1-未分级; 2-没图案设计; 3-有图案设计; 4-国内品牌; 5-高风险',
  `product_level` int(4) DEFAULT '2' COMMENT '1-重点; 2-原创; 3-海外; 4-抓取; 5-导入',
  `brand` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '品牌',
  `label` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '标签',
  `developer` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '产品开发',
  `artist` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '美工',
  `manufacturer` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '制造商',
  `manufacturer_id` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '产商编号',
  `origin_place` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '原产地区',
  `catalog_number` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '商品目录',
  `amazon_category_id` int(10) DEFAULT NULL,
  `category_local` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '商品本地分类',
  `amazon_node_path` varchar(200) CHARACTER SET utf8 COLLATE utf8_esperanto_ci DEFAULT NULL,
  `amazon_nodeId` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `customs_hscode` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `customs_price` double(10,3) DEFAULT '0.000' COMMENT '海关申报价格(美元)',
  `package_weight` double(10,3) DEFAULT '0.000' COMMENT '包装毛重(g)',
  `package_length` double(10,3) DEFAULT '0.000' COMMENT '包装长(cm)',
  `package_width` double(10,3) DEFAULT '0.000' COMMENT '包装宽(cm)',
  `package_height` double(10,3) DEFAULT '0.000' COMMENT '包装高(cm)',
  `package_remark` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT 'purebattery|Li-battery|liquid|powder|magnetic|fragile|oversized',
  `supplier_id` int(10) DEFAULT NULL,
  `item_serial_number` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '供应货号',
  `resource_url` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '来源网址',
  `supply_remark` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '商品供应附加备注',
  `design_for_id` int(10) DEFAULT NULL COMMENT '适用人群: 1- 成人通用; 2-婴儿通用; 3- 婴儿男性; 4 - 婴儿女性; 5- 儿童男性; 6- 儿童女性; 7-成人男性; 8- 成人女性;',
  `matel_type_id` int(4) DEFAULT NULL COMMENT '金属类型',
  `package_material_type` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '包装材料',
  `jewel_type_id` int(4) DEFAULT NULL COMMENT '珠宝类型',
  `ringsize` varchar(10) CHARACTER SET utf8 DEFAULT NULL COMMENT '戒指尺寸',
  `number_of_items` int(10) DEFAULT '1',
  `package_quantity` int(100) DEFAULT '1',
  `part_number` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `product_group` varchar(200) CHARACTER SET utf8 DEFAULT 'unknown' COMMENT '商品分类',
  `product_type_name` varchar(200) CHARACTER SET utf8 DEFAULT 'unknown' COMMENT '商品类型',
  `publisher` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '出版商',
  `studio` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '工作室',
  `color` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '商品颜色',
  `binding` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `is_adult_product` varchar(10) CHARACTER SET utf8 DEFAULT 'false',
  `is_memorabilia` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT 'false',
  `material_type` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '材质',
  `weight` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `small_image` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `price` double(10,3) DEFAULT NULL,
  `currency` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `distribution_price` double(10,3) DEFAULT '0.000' COMMENT '分销单价',
  `is_distribution` int(1) DEFAULT '0' COMMENT '0-禁止分销; 1-允许分销',
  `fixed_shipping` double(10,2) DEFAULT '0.00' COMMENT '固定运费',
  `international_shipping_id` int(50) DEFAULT '0' COMMENT '国家运费外链id, 若无挂号费,则为0',
  `variation_theme` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '变体主题',
  `variation_node` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `products`
--

INSERT INTO `products` (`id`, `title`, `keywords`, `keypoints`, `description`, `abbr_en`, `abbr_cn`, `ASIN`, `SKU`, `images`, `main_image_id`, `product_count`, `perpackage_count`, `review_status`, `appear_status`, `amazon_status`, `security_status`, `product_level`, `brand`, `label`, `developer`, `artist`, `manufacturer`, `manufacturer_id`, `origin_place`, `catalog_number`, `amazon_category_id`, `category_local`, `amazon_node_path`, `amazon_nodeId`, `customs_hscode`, `customs_price`, `package_weight`, `package_length`, `package_width`, `package_height`, `package_remark`, `supplier_id`, `item_serial_number`, `resource_url`, `supply_remark`, `design_for_id`, `matel_type_id`, `package_material_type`, `jewel_type_id`, `ringsize`, `number_of_items`, `package_quantity`, `part_number`, `product_group`, `product_type_name`, `publisher`, `studio`, `color`, `binding`, `is_adult_product`, `is_memorabilia`, `material_type`, `weight`, `small_image`, `price`, `currency`, `distribution_price`, `is_distribution`, `fixed_shipping`, `international_shipping_id`, `variation_theme`, `variation_node`) VALUES
(6, 'title', 'product', '', '', '', '', '6090810260903', 'LAMAZON-8F7B714F', '23|22|25|24', 0, 0, 2, 1, 1, 1, 1, 1, 'lightlamp', NULL, '0', '0', 'lightlamp', 'lightlam', 'lightlamp', '', 230, '家/床浴', '厨房和家庭/浴室/浴室配件/浴帘及配件', '3273900031', '10020010', 0.000, 0.000, 0.000, 0.000, 0.000, NULL, 0, '', '', '', 0, 0, '', 0, '', 1, 1, NULL, 'unknown', 'unknown', NULL, NULL, NULL, NULL, 'false', 'false', '', NULL, NULL, 44444.000, '人民币', 0.000, 0, 55.00, 0, 'Size', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
