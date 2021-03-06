-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2019-06-01 16:25:31
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
-- 表的结构 `amazoncategory`
--

CREATE TABLE IF NOT EXISTS `amazoncategory` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_en` varchar(100) NOT NULL COMMENT '英文名称',
  `name_cn` varchar(100) DEFAULT NULL,
  `parent_name` varchar(100) DEFAULT NULL,
  `level` int(4) DEFAULT NULL,
  `is_end_point` int(1) DEFAULT NULL COMMENT '0-中间节点 1-末节点',
  `parent` int(10) DEFAULT NULL COMMENT '0: means root',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=458 ;

--
-- 插入之前先把表清空（truncate） `amazoncategory`
--

TRUNCATE TABLE `amazoncategory`;
--
-- 转存表中的数据 `amazoncategory`
--

INSERT INTO `amazoncategory` (`id`, `name_en`, `name_cn`, `parent_name`, `level`, `is_end_point`, `parent`) VALUES
(1, 'root', '根', '', 0, 0, 0),
(2, 'AutoAccessory', '汽车配件', 'root', 1, 0, 1),
(3, 'Arts', '艺术类', 'root', 1, 0, 1),
(4, 'Baby', '婴儿', 'root', 1, 0, 1),
(5, 'Beauty', '美女', 'root', 1, 0, 1),
(6, 'Books', '书', 'root', 1, 0, 1),
(7, 'CameraPhoto', '相机与相片', 'root', 1, 0, 1),
(8, 'CE', '总工程师', 'root', 1, 0, 1),
(9, 'Clothing', '服装', 'root', 1, 1, 1),
(10, 'ClothingAccessories', '服装配件', 'root', 1, 1, 1),
(11, 'Coins', '硬币', 'root', 1, 0, 1),
(12, 'Collectibles', '收藏品', 'root', 1, 0, 1),
(13, 'Computers', '计算机', 'root', 1, 0, 1),
(14, 'EducationSupplies', '教育供给', 'root', 1, 0, 1),
(15, 'EntertainmentCollectibles', '娱乐收藏品', 'root', 1, 0, 1),
(16, 'FoodAndBeverages', '食品和饮料', 'root', 1, 0, 1),
(17, 'FoodServiceAndJanSan', '食品服务', 'root', 1, 0, 1),
(18, 'Furniture', '家具', 'root', 1, 0, 1),
(19, 'GiftCard', '礼品卡', 'root', 1, 0, 1),
(20, 'Gourmet', '美食', 'root', 1, 0, 1),
(21, 'Health', '健康', 'root', 1, 0, 1),
(22, 'Home', '家', 'root', 1, 0, 1),
(23, 'HomeImprovement', '家庭改善', 'root', 1, 0, 1),
(24, 'Industrial', '工业品', 'root', 1, 0, 1),
(25, 'Jewelry', '珠宝', 'root', 1, 0, 1),
(26, 'LabSupplies', '实验室用品', 'root', 1, 0, 1),
(27, 'Lighting', '照明', 'root', 1, 0, 1),
(28, 'LightMotor', '光马达', 'root', 1, 0, 1),
(29, 'Luggage', '行李', 'root', 1, 0, 1),
(30, 'LuxuryBeauty', '奢侈美妆', 'root', 1, 0, 1),
(31, 'MechanicalFasteners', '机械紧固件', 'root', 1, 0, 1),
(32, 'Miscellaneous', '其他', 'root', 1, 0, 1),
(33, 'Motorcycles', '摩托车', 'root', 1, 0, 1),
(34, 'Music', '音乐', 'root', 1, 0, 1),
(35, 'MusicalInstruments', '音乐设备', 'root', 1, 0, 1),
(36, 'Office', '办公室', 'root', 1, 0, 1),
(37, 'Outdoor', '室外', 'root', 1, 0, 1),
(38, 'PetSupplies', '宠物用品', 'root', 1, 0, 1),
(39, 'PowerTransmission', '动力传动', 'root', 1, 0, 1),
(40, 'ProfessionalHealthCare', '职业卫生保健', 'root', 1, 0, 1),
(41, 'RawMaterials', '原材料', 'root', 1, 0, 1),
(42, 'Shoes', '鞋', 'root', 1, 0, 1),
(43, 'Sports', '体育', 'root', 1, 0, 1),
(44, 'SportsMemorabilia', '体育纪念品', 'root', 1, 0, 1),
(45, 'SWVG', 'SWVG', 'root', 1, 0, 1),
(46, 'ThreeDPrinting', '3D打印', 'root', 1, 0, 1),
(47, 'TiresAndWheels', '轮胎与车轮', 'root', 1, 0, 1),
(48, 'Tools', '工具', 'root', 1, 1, 1),
(49, 'Toys', '玩具', 'root', 1, 0, 1),
(50, 'ToysBaby', '婴儿玩具', 'root', 1, 0, 1),
(51, 'Video', '视频', 'root', 1, 0, 1),
(52, 'Wine', '葡萄酒', 'root', 1, 0, 1),
(53, 'Wireless', '无线', 'root', 1, 0, 1),
(54, 'LargeAppliances', '大型器具', 'root', 1, 0, 1),
(55, 'AutoAccessoryMisc', '汽车配件杂项', 'AutoAccessory', 2, 1, 2),
(56, 'AutoPart', '汽车零件', 'AutoAccessory', 2, 1, 2),
(57, 'PowersportsPart', '机动零件', 'AutoAccessory', 2, 1, 2),
(58, 'PowersportsVehicle', '动力端口车辆', 'AutoAccessory', 2, 1, 2),
(59, 'ProtectiveGear', '保护齿轮', 'AutoAccessory', 2, 1, 2),
(60, 'Helmet', '头盔', 'AutoAccessory', 2, 1, 2),
(61, 'RidingApparel', '骑装', 'AutoAccessory', 2, 1, 2),
(62, 'Tire', '轮胎', 'AutoAccessory', 2, 1, 2),
(63, 'Rims', '轮辋', 'AutoAccessory', 2, 1, 2),
(64, 'TireAndWheel', '提花轮', 'AutoAccessory', 2, 1, 2),
(65, 'Vehicle', '车辆', 'AutoAccessory', 2, 1, 2),
(66, 'Motorcyclepart', '摩托车零件', 'AutoAccessory', 2, 1, 2),
(67, 'Motorcycleaccessory', '摩托车配件', 'AutoAccessory', 2, 1, 2),
(68, 'Ridinggloves', '骑行手套', 'AutoAccessory', 2, 1, 2),
(69, 'Ridingboots', '脊靴', 'AutoAccessory', 2, 1, 2),
(70, 'Autooil', '汽车润滑油', 'AutoAccessory', 2, 1, 2),
(71, 'Autobattery', '汽车电池', 'AutoAccessory', 2, 1, 2),
(72, 'Autochemical', '汽车化工品', 'AutoAccessory', 2, 1, 2),
(73, 'CleaningOrRepairKit', '清洁或修复', 'AutoAccessory', 2, 1, 2),
(74, 'FineArt', '艺术品', 'Arts', 2, 1, 3),
(75, 'FineArtEditioned', '精制艺术品', 'Arts', 2, 1, 3),
(76, 'BabyProducts', '婴儿用品', 'Baby', 2, 1, 4),
(77, 'InfantToddlerCarSeat', '幼儿座椅', 'Baby', 2, 1, 4),
(78, 'Stroller', '童车', 'Baby', 2, 1, 4),
(79, 'BeautyMisc', '美妆杂项', 'Beauty', 2, 1, 5),
(80, 'SkinCareProduct', '护肤品', 'Beauty', 2, 1, 5),
(81, 'HairCareProduct', '美发产品', 'Beauty', 2, 1, 5),
(82, 'BodyCareProduct', '身体护理产品', 'Beauty', 2, 1, 5),
(83, 'MakeUp', '化妆品', 'Beauty', 2, 1, 5),
(84, 'Fragrance', '香水', 'Beauty', 2, 1, 5),
(85, 'HairRemovalAndShavingProduct', '脱毛和剃须产品', 'Beauty', 2, 1, 5),
(86, 'BooksMisc', '书籍杂项', 'Books', 2, 1, 6),
(87, 'FilmCamera', '摄影机', 'CameraPhoto', 2, 1, 7),
(88, 'Camcorder', '摄像机', 'CameraPhoto', 2, 1, 7),
(89, 'DigitalCamera', '数码相机', 'CameraPhoto', 2, 1, 7),
(90, 'DigitalFrame', '数字相框', 'CameraPhoto', 2, 1, 7),
(91, 'Binocular', '双目望远镜', 'CameraPhoto', 2, 1, 7),
(92, 'SurveillanceSystem', '监控系统', 'CameraPhoto', 2, 1, 7),
(93, 'Telescope', '望远镜', 'CameraPhoto', 2, 1, 7),
(94, 'Microscope', '显微镜', 'CameraPhoto', 2, 1, 7),
(95, 'Darkroom', '暗室', 'CameraPhoto', 2, 1, 7),
(96, 'Lens', '透镜', 'CameraPhoto', 2, 1, 7),
(97, 'LensAccessory', '透镜附件', 'CameraPhoto', 2, 1, 7),
(98, 'Filter', '滤波器', 'CameraPhoto', 2, 1, 7),
(99, 'Film', '电影', 'CameraPhoto', 2, 1, 7),
(100, 'BagCase', '袋套', 'CameraPhoto', 2, 1, 7),
(101, 'BlankMedia', '空白媒体', 'CameraPhoto', 2, 1, 7),
(102, 'PhotoPaper', '照相胶片', 'CameraPhoto', 2, 1, 7),
(103, 'Cleaner', '清洗设备', 'CameraPhoto', 2, 1, 7),
(104, 'Flash', '闪光', 'CameraPhoto', 2, 1, 7),
(105, 'TripodStand', '三脚架', 'CameraPhoto', 2, 1, 7),
(106, 'Projection', '投影', 'CameraPhoto', 2, 1, 7),
(107, 'PhotoStudio', '摄影棚', 'CameraPhoto', 2, 1, 7),
(108, 'LightMeter', '照度计', 'CameraPhoto', 2, 1, 7),
(109, 'PowerSupply', '电源', 'CameraPhoto', 2, 1, 7),
(110, 'OtherAccessory', '其他附件', 'CameraPhoto', 2, 1, 7),
(111, 'Lighting', '照明', 'CameraPhoto', 2, 1, 7),
(112, 'Antenna', '天线', 'CE', 2, 1, 8),
(113, 'AudioVideoAccessory', '音频视频附件', 'CE', 2, 1, 8),
(114, 'AVFurniture', 'AV设备', 'CE', 2, 1, 8),
(115, 'BarCodeReader', '条形码读取机', 'CE', 2, 1, 8),
(116, 'CEBinocular', '双目望远镜', 'CE', 2, 1, 8),
(117, 'CECamcorder', 'CE摄像机', 'CE', 2, 1, 8),
(118, 'CameraBagsAndCases', '照相机包和箱子', 'CE', 2, 1, 8),
(119, 'CEBattery', 'CE电池', 'CE', 2, 1, 8),
(120, 'CEBlankMedia', 'CE空白媒体', 'CE', 2, 1, 8),
(121, 'CableOrAdapter', '电缆适配器', 'CE', 2, 1, 8),
(122, 'CECameraFlash', 'CE相机闪光设备', 'CE', 2, 1, 8),
(123, 'CameraLenses', '摄影镜头', 'CE', 2, 1, 8),
(124, 'CameraOtherAccessories', '摄像机其他配件', 'CE', 2, 1, 8),
(125, 'CameraPowerSupply', '摄像机电源', 'CE', 2, 1, 8),
(126, 'CarAlarm', '汽车警报器', 'CE', 2, 1, 8),
(127, 'CarAudioOrTheater', '汽车音频与车载影院', 'CE', 2, 1, 8),
(128, 'CarElectronics', '汽车电子产品', 'CE', 2, 1, 8),
(129, 'ConsumerElectronics', '消费电子', 'CE', 2, 1, 8),
(130, 'CEDigitalCamera', 'CE数码相机', 'CE', 2, 1, 8),
(131, 'DigitalPictureFrame', '数码相框', 'CE', 2, 1, 8),
(132, 'DigitalVideoRecorder', '数字录像机', 'CE', 2, 1, 8),
(133, 'DVDPlayerOrRecorder', 'DVD放录机', 'CE', 2, 1, 8),
(134, 'CEFilmCamera', '电影摄影机', 'CE', 2, 1, 8),
(135, 'GPSOrNavigationAccessory', 'GPS与导航配件', 'CE', 2, 1, 8),
(136, 'GPSOrNavigationSystem', 'GPS导航系统', 'CE', 2, 1, 8),
(137, 'HandheldOrPDA', '手持电子设备(PDA)', 'CE', 2, 1, 8),
(138, 'Headphones', '耳机', 'CE', 2, 1, 8),
(139, 'HomeTheaterSystemOrHTIB', '家庭影院系统', 'CE', 2, 1, 8),
(140, 'KindleAccessories', 'Kindle配件', 'CE', 2, 1, 8),
(141, 'KindleEReaderAccessories', 'Kindle EReader配件', 'CE', 2, 1, 8),
(142, 'KindleFireAccessories', 'Kindle Fire 配件', 'CE', 2, 1, 8),
(143, 'MediaPlayer', '媒体播放器', 'CE', 2, 1, 8),
(144, 'MediaPlayerOrEReaderAccessory', '媒体播放器或电子书配件', 'CE', 2, 1, 8),
(145, 'MediaStorage', '媒体存储', 'CE', 2, 1, 8),
(146, 'MiscAudioComponents', '其他音频组件', 'CE', 2, 1, 8),
(147, 'PC', '个人计算机', 'CE', 2, 1, 8),
(148, 'PDA', '掌上电脑', 'CE', 2, 1, 8),
(149, 'Phone', '电话', 'CE', 2, 1, 8),
(150, 'PhoneAccessory', '电话附件', 'CE', 2, 1, 8),
(151, 'PhotographicStudioItems', '摄影工作室产品', 'CE', 2, 1, 8),
(152, 'PortableAudio', '便携式音频设备', 'CE', 2, 1, 8),
(153, 'PortableAvDevice', '便携式AV(音视频)设备', 'CE', 2, 1, 8),
(154, 'PowerSuppliesOrProtection', '电源或保护', 'CE', 2, 1, 8),
(155, 'RadarDetector', '无线电探测器', 'CE', 2, 1, 8),
(156, 'RadioOrClockRadio', '无线电或时钟无线电', 'CE', 2, 1, 8),
(157, 'ReceiverOrAmplifier', '接收器或放大器', 'CE', 2, 1, 8),
(158, 'RemoteControl', '远程控制', 'CE', 2, 1, 8),
(159, 'Speakers', '扬声器', 'CE', 2, 1, 8),
(160, 'StereoShelfSystem', '立体声系统', 'CE', 2, 1, 8),
(161, 'CETelescope', 'CE望远镜', 'CE', 2, 1, 8),
(162, 'Television', '电视机', 'CE', 2, 1, 8),
(163, 'Tuner', '调谐器', 'CE', 2, 1, 8),
(164, 'TVCombos', '电视功放音箱', 'CE', 2, 1, 8),
(165, 'TwoWayRadio', '双向收音机', 'CE', 2, 1, 8),
(166, 'VCR', '录像机', 'CE', 2, 1, 8),
(167, 'CEVideoProjector', 'CE投影仪', 'CE', 2, 1, 8),
(168, 'VideoProjectorsAndAccessories', '视频投影仪和附件', 'CE', 2, 1, 8),
(169, 'NetworkAdapter', '网络适配器', 'CE', 2, 1, 8),
(170, 'CellularPhoneCase', '手机壳', 'CE', 2, 1, 8),
(171, 'ScreenProtector', '屏幕保护器', 'CE', 2, 1, 8),
(172, 'Coin', '钱币', 'Coins', 2, 1, 11),
(173, 'CollectibleCoins', '收藏钱币', 'Coins', 2, 1, 11),
(174, 'Bullion', '金条', 'Coins', 2, 1, 11),
(175, 'AdvertisementCollectibles', '广告收藏品', 'Collectibles', 2, 1, 12),
(176, 'HistoricalCollectibles', '古董', 'Collectibles', 2, 1, 12),
(177, 'CarryingCaseOrBag', '携带箱袋', 'Computers', 2, 1, 13),
(178, 'ComputerAddOn', '计算机插件', 'Computers', 2, 1, 13),
(179, 'ComputerComponent', '计算机组件', 'Computers', 2, 1, 13),
(180, 'ComputerCoolingDevice', '计算机冷却装置', 'Computers', 2, 1, 13),
(181, 'ComputerDriveOrStorage', '计算机驱动器与存储', 'Computers', 2, 1, 13),
(182, 'ComputerInputDevice', '计算机输入设备', 'Computers', 2, 1, 13),
(183, 'ComputerProcessor', '计算机处理器', 'Computers', 2, 1, 13),
(184, 'ComputerSpeaker', '电脑扬声器', 'Computers', 2, 1, 13),
(185, 'Computer', '电脑类', 'Computers', 2, 1, 13),
(186, 'FlashMemory', '闪存', 'Computers', 2, 1, 13),
(187, 'InkOrToner', '墨水/碳粉', 'Computers', 2, 1, 13),
(188, 'Keyboards', '键盘', 'Computers', 2, 1, 13),
(189, 'MemoryReader', '记忆读出器', 'Computers', 2, 1, 13),
(190, 'Monitor', '监测器', 'Computers', 2, 1, 13),
(191, 'Motherboard', '主板', 'Computers', 2, 1, 13),
(192, 'NetworkingDevice', '网络设备', 'Computers', 2, 1, 13),
(193, 'NotebookComputer', '笔记本电脑', 'Computers', 2, 1, 13),
(194, 'PersonalComputer', '个人计算机', 'Computers', 2, 1, 13),
(195, 'Printer', '打印机', 'Computers', 2, 1, 13),
(196, 'RamMemory', '随机存储器', 'Computers', 2, 1, 13),
(197, 'Scanner', '扫描仪', 'Computers', 2, 1, 13),
(198, 'SoundCard', '声卡', 'Computers', 2, 1, 13),
(199, 'SystemCabinet', '机箱', 'Computers', 2, 1, 13),
(200, 'SystemPowerDevice', '系统电源设备', 'Computers', 2, 1, 13),
(201, 'TabletComputer', '平板电脑', 'Computers', 2, 1, 13),
(202, 'VideoCard', '显卡', 'Computers', 2, 1, 13),
(203, 'VideoProjector', '电视放映机', 'Computers', 2, 1, 13),
(204, 'Webcam', '网络摄像机', 'Computers', 2, 1, 13),
(205, 'TeachingEquipment', '教学设备', 'EducationSupplies', 2, 1, 14),
(206, 'EntertainmentCollectibles', '娱乐收藏品', 'EntertainmentCollectibles', 2, 1, 15),
(207, 'Food', '食物', 'FoodAndBeverages', 2, 1, 16),
(208, 'HouseholdSupplies', '家庭用品', 'FoodAndBeverages', 2, 1, 16),
(209, 'Beverages', '饮料', 'FoodAndBeverages', 2, 1, 16),
(210, 'HardLiquor', '烈性酒', 'FoodAndBeverages', 2, 1, 16),
(211, 'AlcoholicBeverages', '酒精饮料', 'FoodAndBeverages', 2, 1, 16),
(212, 'Wine', '葡萄酒', 'FoodAndBeverages', 2, 1, 16),
(213, 'Beer', '啤酒', 'FoodAndBeverages', 2, 1, 16),
(214, 'Spirits', '蒸馏酒', 'FoodAndBeverages', 2, 1, 16),
(215, 'BabyFood', '婴儿食品', 'FoodAndBeverages', 2, 1, 16),
(216, 'FoodServiceAndJanSan', '食品服务', 'FoodServiceAndJanSan', 2, 1, 17),
(217, 'Furniture', '家具', 'Furniture', 2, 1, 18),
(218, 'GiftCard', '礼品卡', 'GiftCard', 2, 1, 19),
(219, 'PhysicalGiftCard', '实体礼品卡', 'GiftCard', 2, 1, 19),
(220, 'ElectronicGiftCard', '电子礼品卡', 'GiftCard', 2, 1, 19),
(221, 'GourmetMisc', '美食家', 'Gourmet', 2, 1, 20),
(222, 'HealthMisc', '卫生保健杂项', 'Health', 2, 1, 21),
(223, 'PersonalCareAppliances', '个人护理器械', 'Health', 2, 1, 21),
(224, 'PrescriptionDrug', '处方药', 'Health', 2, 1, 21),
(225, 'DietarySupplements', '营养补充剂', 'Health', 2, 1, 21),
(226, 'OTCMedication', '非处方药', 'Health', 2, 1, 21),
(227, 'PrescriptionEyewear', '处方眼镜', 'Health', 2, 1, 21),
(228, 'SexualWellness', '性健康', 'Health', 2, 1, 21),
(229, 'MedicalSupplies', '医疗用品', 'Health', 2, 1, 21),
(230, 'BedAndBath', '床浴', 'Home', 2, 1, 22),
(231, 'FurnitureAndDecor', '家具和装饰', 'Home', 2, 1, 22),
(232, 'Kitchen', '厨房', 'Home', 2, 1, 22),
(233, 'OutdoorLiving', '户外生活', 'Home', 2, 1, 22),
(234, 'SeedsAndPlants', '种子与植物', 'Home', 2, 1, 22),
(235, 'Art', '艺术', 'Home', 2, 1, 22),
(236, 'Fabric', '织物', 'Home', 2, 1, 22),
(237, 'VacuumCleaner', '真空吸尘器', 'Home', 2, 1, 22),
(238, 'Mattress', '床垫', 'Home', 2, 1, 22),
(239, 'Bed', '床', 'Home', 2, 1, 22),
(240, 'Headboard', '床头板', 'Home', 2, 1, 22),
(241, 'Dresser', '梳妆台', 'Home', 2, 1, 22),
(242, 'Cabinet', '橱柜', 'Home', 2, 1, 22),
(243, 'Chair', '椅子', 'Home', 2, 1, 22),
(244, 'Table', '桌子', 'Home', 2, 1, 22),
(245, 'Bench', '凳子', 'Home', 2, 1, 22),
(246, 'Sofa', '沙发', 'Home', 2, 1, 22),
(247, 'Desk', '书桌', 'Home', 2, 1, 22),
(248, 'FloorCover', '地板覆盖物', 'Home', 2, 1, 22),
(249, 'Bakeware', '烘焙用具', 'Home', 2, 1, 22),
(250, 'Cookware', '炊具', 'Home', 2, 1, 22),
(251, 'Cutlery', '刀具', 'Home', 2, 1, 22),
(252, 'Dinnerware', '餐具', 'Home', 2, 1, 22),
(253, 'Serveware', '餐桌用具', 'Home', 2, 1, 22),
(254, 'KitchenTools', '厨房用具', 'Home', 2, 1, 22),
(255, 'SmallHomeAppliances', '小型家用电器', 'Home', 2, 1, 22),
(256, 'Home', '家', 'Home', 2, 1, 22),
(257, 'BuildingMaterials', '建筑材料', 'HomeImprovement', 2, 1, 23),
(258, 'Hardware', '硬件', 'HomeImprovement', 2, 1, 23),
(259, 'Electrical', '电气', 'HomeImprovement', 2, 1, 23),
(260, 'PlumbingFixtures', '水管固定装置', 'HomeImprovement', 2, 1, 23),
(261, 'Tools', '工具', 'HomeImprovement', 2, 1, 23),
(262, 'OrganizersAndStorage', '组织和存储', 'HomeImprovement', 2, 1, 23),
(263, 'MajorHomeAppliances', '大型家用电器', 'HomeImprovement', 2, 1, 23),
(264, 'SecurityElectronics', '电子安全设备', 'HomeImprovement', 2, 1, 23),
(265, 'Abrasives', '磨料', 'Industrial', 2, 1, 24),
(266, 'AdhesivesAndSealants', '粘合剂和密封剂', 'Industrial', 2, 1, 24),
(267, 'CuttingTools', '刀具', 'Industrial', 2, 1, 24),
(268, 'ElectronicComponents', '电子元器件', 'Industrial', 2, 1, 24),
(269, 'Gears', '齿轮', 'Industrial', 2, 1, 24),
(270, 'Grommets', '索环与扣眼', 'Industrial', 2, 1, 24),
(271, 'IndustrialHose', '工业软管', 'Industrial', 2, 1, 24),
(272, 'IndustrialWheels', '工业轮具', 'Industrial', 2, 1, 24),
(273, 'MechanicalComponents', '机械部件', 'Industrial', 2, 1, 24),
(274, 'ORings', 'O形圈', 'Industrial', 2, 1, 24),
(275, 'PrecisionMeasuring', '精密测量', 'Industrial', 2, 1, 24),
(276, 'AdhesiveTapes', '胶粘物', 'Industrial', 2, 1, 24),
(277, 'Watch', '手表', 'Jewelry', 2, 1, 25),
(278, 'FashionNecklaceBraceletAnklet', '时尚手镯脚镯', 'Jewelry', 2, 1, 25),
(279, 'FashionRing', '时尚戒指', 'Jewelry', 2, 1, 25),
(280, 'FashionEarring', '时尚耳环', 'Jewelry', 2, 1, 25),
(281, 'FashionOther', '其他时尚', 'Jewelry', 2, 1, 25),
(282, 'FineNecklaceBraceletAnklet', '精致项链手镯', 'Jewelry', 2, 1, 25),
(283, 'FineRing', '精致戒指', 'Jewelry', 2, 1, 25),
(284, 'FineEarring', '精致耳环', 'Jewelry', 2, 1, 25),
(285, 'FineOther', '其他精致', 'Jewelry', 2, 1, 25),
(286, 'LabSupply', '实验室用品', 'LabSupplies', 2, 1, 26),
(287, 'SafetySupply', '安全用品', 'LabSupplies', 2, 1, 26),
(288, 'LightsAndFixtures', '灯具', 'Lighting', 2, 1, 27),
(289, 'LightingAccessories', '照明配件', 'Lighting', 2, 1, 27),
(290, 'LightBulbs', '灯泡', 'Lighting', 2, 1, 27),
(291, 'LightMotorVehicle', '轻型机动车辆', 'LightMotor', 2, 1, 28),
(292, 'Luggage', '行李', 'Luggage', 2, 1, 29),
(293, 'LuxuryBeauty', '奢侈美妆', 'LuxuryBeauty', 2, 1, 30),
(294, 'MechanicalFasteners', '机械紧固件', 'MechanicalFasteners', 2, 1, 31),
(295, 'Antiques', '古董', 'Miscellaneous', 2, 1, 32),
(296, 'Art', '艺术', 'Miscellaneous', 2, 1, 32),
(297, 'Car_Parts_and_Accessories', '汽车配件', 'Miscellaneous', 2, 1, 32),
(298, 'Coins', '硬币', 'Miscellaneous', 2, 1, 32),
(299, 'Collectibles', '收藏品', 'Miscellaneous', 2, 1, 32),
(300, 'Crafts', '工艺品', 'Miscellaneous', 2, 1, 32),
(301, 'Event_Tickets', '事件门票', 'Miscellaneous', 2, 1, 32),
(302, 'Flowers', '花', 'Miscellaneous', 2, 1, 32),
(303, 'Gifts_and_Occasions', '礼物和场所', 'Miscellaneous', 2, 1, 32),
(304, 'Gourmet_Food_and_Wine', '美食和葡萄酒', 'Miscellaneous', 2, 1, 32),
(305, 'Hobbies', '业余爱好', 'Miscellaneous', 2, 1, 32),
(306, 'Home_Furniture_and_Decor', '家居装饰', 'Miscellaneous', 2, 1, 32),
(307, 'Home_Lighting_and_Lamps', '家庭照明灯具', 'Miscellaneous', 2, 1, 32),
(308, 'Home_Organizers_and_Storage', '家庭收纳和存储', 'Miscellaneous', 2, 1, 32),
(309, 'Jewelry_and_Gems', '珠宝和宝石', 'Miscellaneous', 2, 1, 32),
(310, 'Luggage', '行李', 'Miscellaneous', 2, 1, 32),
(311, 'Major_Home_Appliances', '大型家用电器', 'Miscellaneous', 2, 1, 32),
(312, 'Medical_Supplies', '医疗用品', 'Miscellaneous', 2, 1, 32),
(313, 'Motorcycles', '摩托车', 'Miscellaneous', 2, 1, 32),
(314, 'Musical_Instruments', '乐器', 'Miscellaneous', 2, 1, 32),
(315, 'Pet_Supplies', '宠物用品', 'Miscellaneous', 2, 1, 32),
(316, 'Pottery_and_Glass', '陶器和玻璃', 'Miscellaneous', 2, 1, 32),
(317, 'Prints_and_Posters', '打印和海报', 'Miscellaneous', 2, 1, 32),
(318, 'Scientific_Supplies', '科学用品', 'Miscellaneous', 2, 1, 32),
(319, 'Sporting_and_Outdoor_Goods', '体育用品', 'Miscellaneous', 2, 1, 32),
(320, 'Sports_Memorabilia', '体育纪念品', 'Miscellaneous', 2, 1, 32),
(321, 'Stamps', '邮票', 'Miscellaneous', 2, 1, 32),
(322, 'Teaching_and_School_Supplies', '教学用品', 'Miscellaneous', 2, 1, 32),
(323, 'Watches', '手表', 'Miscellaneous', 2, 1, 32),
(324, 'Wholesale_and_Industrial', '批发业', 'Miscellaneous', 2, 1, 32),
(325, 'Misc_Other', '其他杂项', 'Miscellaneous', 2, 1, 32),
(326, 'Vehicles', '车辆', 'Motorcycles', 2, 1, 33),
(327, 'ProtectiveClothing', '绝缘保护', 'Motorcycles', 2, 1, 33),
(328, 'Helmets', '头盔', 'Motorcycles', 2, 1, 33),
(329, 'RidingBoots', '脊靴', 'Motorcycles', 2, 1, 33),
(330, 'Gloves', '手套', 'Motorcycles', 2, 1, 33),
(331, 'Accessories', '配件', 'Motorcycles', 2, 1, 33),
(332, 'Parts', '零件', 'Motorcycles', 2, 1, 33),
(333, 'MusicPopular', '流行音乐', 'Music', 2, 1, 34),
(334, 'MusicClassical', '经典音乐', 'Music', 2, 1, 34),
(335, 'BrassAndWoodwindInstruments', '黄铜和木管乐器', 'MusicalInstruments', 2, 1, 35),
(336, 'Guitars', '吉他', 'MusicalInstruments', 2, 1, 35),
(337, 'InstrumentPartsAndAccessories', '乐器零件和配件', 'MusicalInstruments', 2, 1, 35),
(338, 'KeyboardInstruments', '键盘乐器', 'MusicalInstruments', 2, 1, 35),
(339, 'MiscWorldInstruments', '其他乐器', 'MusicalInstruments', 2, 1, 35),
(340, 'PercussionInstruments', '打击乐器', 'MusicalInstruments', 2, 1, 35),
(341, 'SoundAndRecordingEquipment', '音响和音响设备', 'MusicalInstruments', 2, 1, 35),
(342, 'StringedInstruments', '弦乐器', 'MusicalInstruments', 2, 1, 35),
(343, 'ArtSupplies', '艺术用品', 'Office', 2, 1, 36),
(344, 'EducationalSupplies', '教育用品', 'Office', 2, 1, 36),
(345, 'OfficeProducts', '办公用品', 'Office', 2, 1, 36),
(346, 'PaperProducts', '纸制品', 'Office', 2, 1, 36),
(347, 'WritingInstruments', '书写工具', 'Office', 2, 1, 36),
(348, 'BarCode', '条形码', 'Office', 2, 1, 36),
(349, 'Calculator', '计算器', 'Office', 2, 1, 36),
(350, 'InkToner', '墨粉', 'Office', 2, 1, 36),
(351, 'MultifunctionDevice', '多功能设备', 'Office', 2, 1, 36),
(352, 'OfficeElectronics', '办公电子设备', 'Office', 2, 1, 36),
(353, 'OfficePhone', '办公电话', 'Office', 2, 1, 36),
(354, 'OfficePrinter', '办公打印机', 'Office', 2, 1, 36),
(355, 'OfficeScanner', '办公扫描仪', 'Office', 2, 1, 36),
(356, 'VoiceRecorder', '录音设备', 'Office', 2, 1, 36),
(357, 'PrinterConsumable', '打印耗材', 'Office', 2, 1, 36),
(358, 'OutdoorRecreationProduct', '户外创意产品', 'Outdoor', 2, 1, 37),
(359, 'CampingEquipment', '露营设备', 'Outdoor', 2, 1, 37),
(360, 'CyclingEquipment', '自行车设备', 'Outdoor', 2, 1, 37),
(361, 'FishingEquipment', '渔具', 'Outdoor', 2, 1, 37),
(362, 'PetSuppliesMisc', '宠物供给', 'PetSupplies', 2, 1, 38),
(363, 'BearingsAndBushings', '轴承和衬套', 'PowerTransmission', 2, 1, 39),
(364, 'Belts', '传动皮带', 'PowerTransmission', 2, 1, 39),
(365, 'CompressionSprings', '压缩弹簧', 'PowerTransmission', 2, 1, 39),
(366, 'ExtensionSprings', '拉伸弹簧', 'PowerTransmission', 2, 1, 39),
(367, 'FlexibleCouplings', '挠性联轴节', 'PowerTransmission', 2, 1, 39),
(368, 'Gears', '齿轮', 'PowerTransmission', 2, 1, 39),
(369, 'RigidCouplings', '刚性联轴器', 'PowerTransmission', 2, 1, 39),
(370, 'ShaftCollar', '轴套', 'PowerTransmission', 2, 1, 39),
(371, 'TorsionSprings', '扭转弹簧', 'PowerTransmission', 2, 1, 39),
(372, 'LinearGuidesAndRails', '线性向导和轨', 'PowerTransmission', 2, 1, 39),
(373, 'Pulleys', '滑轮', 'PowerTransmission', 2, 1, 39),
(374, 'RollerChain', '滚轮链', 'PowerTransmission', 2, 1, 39),
(375, 'CouplingsCollarsAndUniversalJoints', '套管式联轴器和万向节', 'PowerTransmission', 2, 1, 39),
(376, 'Springs', '弹簧', 'PowerTransmission', 2, 1, 39),
(377, 'Sprockets', '链轮', 'PowerTransmission', 2, 1, 39),
(378, 'UniversalJoints', '万向节', 'PowerTransmission', 2, 1, 39),
(379, 'ProfessionalHealthCare', '职业卫生保健', 'ProfessionalHealthCare', 2, 1, 40),
(380, 'MedicalDevice', '医疗器械', 'ProfessionalHealthCare', 2, 1, 40),
(381, 'CeramicBalls', '陶瓷球', 'RawMaterials', 2, 1, 41),
(382, 'CeramicTubing', '陶瓷管', 'RawMaterials', 2, 1, 41),
(383, 'Ceramics', '陶瓷', 'RawMaterials', 2, 1, 41),
(384, 'MetalBalls', '金属球', 'RawMaterials', 2, 1, 41),
(385, 'MetalMesh', '金属丝网', 'RawMaterials', 2, 1, 41),
(386, 'MetalTubing', '金属管', 'RawMaterials', 2, 1, 41),
(387, 'Metals', '金属', 'RawMaterials', 2, 1, 41),
(388, 'PlasticBalls', '塑料球', 'RawMaterials', 2, 1, 41),
(389, 'PlasticMesh', '塑性网格', 'RawMaterials', 2, 1, 41),
(390, 'PlasticTubing', '塑料管', 'RawMaterials', 2, 1, 41),
(391, 'Plastics', '塑料', 'RawMaterials', 2, 1, 41),
(392, 'RawMaterials', '原材料', 'RawMaterials', 2, 1, 41),
(393, 'Wire', '线材', 'RawMaterials', 2, 1, 41),
(394, 'Accessory', '附件', 'Shoes', 2, 1, 42),
(395, 'Bag', '袋子', 'Shoes', 2, 1, 42),
(396, 'Shoes', '鞋', 'Shoes', 2, 1, 42),
(397, 'ShoeAccessory', '鞋垫', 'Shoes', 2, 1, 42),
(398, 'Handbag', '手提包', 'Shoes', 2, 1, 42),
(399, 'Eyewear', '眼镜', 'Shoes', 2, 1, 42),
(400, 'Boot', '靴子', 'Shoes', 2, 1, 42),
(401, 'TechnicalSportShoe', '技术性运动鞋', 'Shoes', 2, 1, 42),
(402, 'Sandal', '凉鞋', 'Shoes', 2, 1, 42),
(403, 'SportingGoods', '体育用品', 'Sports', 2, 1, 43),
(404, 'GlofClubHybrid', '复合材料高尔夫用品', 'Sports', 2, 1, 43),
(405, 'GolfClubIron', '高尔夫铁杆', 'Sports', 2, 1, 43),
(406, 'GolfClubPutter', '高尔夫球棍', 'Sports', 2, 1, 43),
(407, 'GolfClubWedge', '高尔夫球杆', 'Sports', 2, 1, 43),
(408, 'GolfClubWood', '高尔夫木杆', 'Sports', 2, 1, 43),
(409, 'GolfClubs', '高尔夫球俱乐部', 'Sports', 2, 1, 43),
(410, 'SportGloves', '运动手套', 'Sports', 2, 1, 43),
(411, 'SportsMemorabilia', '体育纪念品', 'SportsMemorabilia', 2, 1, 44),
(412, 'TradingCardsCardsSets', '交易卡片集', 'SportsMemorabilia', 2, 1, 44),
(413, 'TradingCardsGradedCardsInserts', '交易卡分级', 'SportsMemorabilia', 2, 1, 44),
(414, 'TradingCardsUngradedInserts', '交易卡不分级', 'SportsMemorabilia', 2, 1, 44),
(415, 'TradingCardsFactorySealed', '交易卡原装', 'SportsMemorabilia', 2, 1, 44),
(416, 'TradingCardsMiscTradingCards', '交易卡杂项', 'SportsMemorabilia', 2, 1, 44),
(417, 'Software', '软件', 'SWVG', 2, 1, 45),
(418, 'HandheldSoftwareDownloads', '手持软件下载', 'SWVG', 2, 1, 45),
(419, 'SoftwareGames', '软件游戏', 'SWVG', 2, 1, 45),
(420, 'VideoGames', '视频游戏', 'SWVG', 2, 1, 45),
(421, 'VideoGamesAccessories', '视频游戏附件', 'SWVG', 2, 1, 45),
(422, 'VideoGamesHardware', '电子游戏硬件', 'SWVG', 2, 1, 45),
(423, 'DigitalDesigns', '数字设计', 'ThreeDPrinting', 2, 1, 46),
(424, 'ThreeDPrintedProduct', '3D打印产品', 'ThreeDPrinting', 2, 1, 46),
(425, 'ThreeDPrintableDesigns', '三D打印设计', 'ThreeDPrinting', 2, 1, 46),
(426, 'Tires', '轮胎', 'TiresAndWheels', 2, 1, 47),
(427, 'Wheels', '车轮', 'TiresAndWheels', 2, 1, 47),
(428, 'TireAndWheelAssemblies', '轮胎和车轮总成', 'TiresAndWheels', 2, 1, 47),
(429, 'ToysAndGames', '玩具游戏', 'Toys', 2, 1, 49),
(430, 'Hobbies', '业余爱好', 'Toys', 2, 1, 49),
(431, 'CollectibleCard', '卡片收集', 'Toys', 2, 1, 49),
(432, 'Costume', '服装', 'Toys', 2, 1, 49),
(433, 'Puzzles', '拼图', 'Toys', 2, 1, 49),
(434, 'Games', '游戏', 'Toys', 2, 1, 49),
(435, 'Models', '模型', 'Toys', 2, 1, 49),
(436, 'ChildrensCostume', '儿童服装', 'Toys', 2, 1, 49),
(437, 'PartySupplies', '聚会用品', 'Toys', 2, 1, 49),
(438, 'ToysAndGames', '玩具游戏', 'ToysBaby', 2, 1, 50),
(439, 'BabyProducts', '婴儿用品', 'ToysBaby', 2, 1, 50),
(440, 'VideoDVD', '视频显示', 'Video', 2, 1, 51),
(441, 'VideoVHS', '视频录像', 'Video', 2, 1, 51),
(442, 'Wine', '葡萄酒', 'Wine', 2, 1, 52),
(443, 'Spirits', '蒸馏酒', 'Wine', 2, 1, 52),
(444, 'Beer', '啤酒', 'Wine', 2, 1, 52),
(445, 'WirelessAccessories', '无线附件', 'Wireless', 2, 1, 53),
(446, 'WirelessDownloads', '无线下载', 'Wireless', 2, 1, 53),
(447, 'AirConditioner', '空调器', 'LargeAppliances', 2, 1, 54),
(448, 'ApplianceAccessory', '家电配件', 'LargeAppliances', 2, 1, 54),
(449, 'CookingOven', '烤炉', 'LargeAppliances', 2, 1, 54),
(450, 'Cooktop', '灶具', 'LargeAppliances', 2, 1, 54),
(451, 'Dishwasher', '洗碗机', 'LargeAppliances', 2, 1, 54),
(452, 'LaundryAppliance', '洗衣机', 'LargeAppliances', 2, 1, 54),
(453, 'MicrowaveOven', '微波炉', 'LargeAppliances', 2, 1, 54),
(454, 'Range', '灶', 'LargeAppliances', 2, 1, 54),
(455, 'RefrigerationAppliance', '制冷机', 'LargeAppliances', 2, 1, 54),
(456, 'TrashCompactor', '垃圾压实机', 'LargeAppliances', 2, 1, 54),
(457, 'VentHood', '通风罩', 'LargeAppliances', 2, 1, 54);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
