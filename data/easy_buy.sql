-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 03 月 18 日 11:05
-- 服务器版本: 5.5.35
-- PHP 版本: 5.4.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `easy_buy`
--

-- --------------------------------------------------------

--
-- 表的结构 `easy_address`
--

CREATE TABLE IF NOT EXISTS `easy_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT 'user_id',
  `name` varchar(30) NOT NULL COMMENT 'consignee name',
  `phone` varchar(30) NOT NULL COMMENT 'consignee phone',
  `zip` varchar(30) NOT NULL COMMENT 'zip code',
  `address` varchar(255) NOT NULL COMMENT 'consignee address',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'default address(0:no,1:yes)',
  `add_time` int(11) NOT NULL COMMENT 'add time',
  `update_time` int(11) DEFAULT NULL COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='user address table' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `easy_address`
--

INSERT INTO `easy_address` (`id`, `user_id`, `name`, `phone`, `zip`, `address`, `is_default`, `add_time`, `update_time`) VALUES
(1, 1, 'lzjjie', '13437563074', '518029', 'Room #207,2F,No.43,Jinhu 1st Street,Yinhu Road,Luohu District, Shenzhen, P.R.C', 1, 1393227625, 1393302552),
(2, 1, 'lzjjie', '202-33228866', '510000', 'Room #207,2F,No.43,Jinhu 1st Street,Yinhu Road,Luohu District, Shenzhen, P.R.C', 0, 1393227625, 1393302510);

-- --------------------------------------------------------

--
-- 表的结构 `easy_admin_user`
--

CREATE TABLE IF NOT EXISTS `easy_admin_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `real_name` varchar(16) NOT NULL COMMENT '管理员真实姓名',
  `email` varchar(255) DEFAULT NULL COMMENT '用户邮箱',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `last_time` int(11) DEFAULT NULL COMMENT '用户上次登录时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '用户状态(1：正常，0：禁用)',
  `desc` varchar(255) DEFAULT NULL COMMENT '管理员描述',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '管理员类型，1为系统管理员，0为普通管理员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='administrators table' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `easy_admin_user`
--

INSERT INTO `easy_admin_user` (`id`, `username`, `password`, `real_name`, `email`, `add_time`, `last_time`, `status`, `desc`, `type`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 'admin@admin.com', 0, 1394945199, 1, 'Administrator!Do not delete!', 1),
(4, 'demo', 'e10adc3949ba59abbe56e057f20f883e', 'demo', '', 1393059963, 1393060050, 1, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `easy_child_category`
--

CREATE TABLE IF NOT EXISTS `easy_child_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'child category id',
  `parent_id` int(11) NOT NULL COMMENT 'parent category id',
  `name` varchar(255) NOT NULL COMMENT 'child category name',
  `image` varchar(255) NOT NULL COMMENT 'child category image',
  `add_time` int(11) NOT NULL COMMENT 'add time',
  `update_time` int(11) DEFAULT NULL COMMENT 'update time',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'deleted?(0:no,1:yes)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='child category table' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `easy_child_category`
--

INSERT INTO `easy_child_category` (`id`, `parent_id`, `name`, `image`, `add_time`, `update_time`, `is_delete`) VALUES
(1, 1, 'apple', '/uploads/cate_13947779364321.jpg', 1394777927, 1394777939, 0);

-- --------------------------------------------------------

--
-- 表的结构 `easy_goods`
--

CREATE TABLE IF NOT EXISTS `easy_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'goods id',
  `c_cate_id` int(11) NOT NULL COMMENT 'child category id',
  `p_cate_id` int(11) NOT NULL COMMENT 'parent category id',
  `name` varchar(255) NOT NULL COMMENT 'goods name',
  `item_number` varchar(255) NOT NULL COMMENT 'item number',
  `price` decimal(12,2) NOT NULL COMMENT 'goods price',
  `business_model` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'goods''s business model(1:b2c,2:b2b)',
  `sale_amount` int(11) DEFAULT NULL COMMENT 'amount for sale',
  `unit` varchar(30) NOT NULL COMMENT 'goods unit',
  `size` varchar(30) DEFAULT NULL COMMENT 'goods size',
  `quality` int(11) DEFAULT NULL COMMENT 'goods quality',
  `color` varchar(30) DEFAULT NULL COMMENT 'goods color',
  `area` varchar(255) DEFAULT NULL COMMENT 'goods area',
  `pay_method` varchar(255) NOT NULL COMMENT 'pay method',
  `guarantee` varchar(255) DEFAULT NULL COMMENT 'guarantee service',
  `stock` int(11) NOT NULL COMMENT 'goods stock',
  `description` varchar(255) DEFAULT NULL COMMENT 'goods description',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'deleted?(0:no,1:yes)',
  `add_time` int(11) NOT NULL COMMENT 'add time',
  `update_time` int(11) DEFAULT NULL COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='goods table' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `easy_goods`
--

INSERT INTO `easy_goods` (`id`, `c_cate_id`, `p_cate_id`, `name`, `item_number`, `price`, `business_model`, `sale_amount`, `unit`, `size`, `quality`, `color`, `area`, `pay_method`, `guarantee`, `stock`, `description`, `is_delete`, `add_time`, `update_time`) VALUES
(1, 1, 1, 'red apple', '100', 10.00, 1, 1000, 'g', '10', 100, 'red', 'ShanXi China', 'Paypal', '10 days return', 10000, 'redredredredredredredredredredredredredredredredredredredredredredredredred', 0, 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `easy_member`
--

CREATE TABLE IF NOT EXISTS `easy_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'user_id',
  `account` varchar(20) NOT NULL COMMENT 'user account',
  `password` varchar(32) NOT NULL COMMENT 'user password',
  `phone` varchar(30) DEFAULT NULL COMMENT 'user phone',
  `avatar` varchar(255) DEFAULT NULL COMMENT 'user avatar',
  `sex` tinyint(1) DEFAULT NULL COMMENT 'gender（1：man 0：woman）',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user status(0:unverified,1:verified)',
  `is_vip` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'is a vip account(0:no,1:yes)',
  `email` varchar(255) NOT NULL COMMENT 'user email',
  `register_time` int(11) NOT NULL COMMENT 'user register time',
  `last_time` int(11) DEFAULT NULL COMMENT 'user last login time',
  `upgrade_time` int(11) DEFAULT NULL COMMENT 'upgrade time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='member table' AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `easy_member`
--

INSERT INTO `easy_member` (`id`, `account`, `password`, `phone`, `avatar`, `sex`, `status`, `is_vip`, `email`, `register_time`, `last_time`, `upgrade_time`) VALUES
(1, 'lzjjie', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 1, 1, 1, 'lzjjie@163.com', 1, 1394777610, 1393234186),
(2, 'hxk', 'e10adc3949ba59abbe56e057f20f883e', '134565655', NULL, 0, 0, 0, 'dfdfdsfsdf', 0, NULL, NULL),
(6, 'tester', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, NULL, 0, 0, '971318606@qq.com', 1393688224, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `easy_parent_category`
--

CREATE TABLE IF NOT EXISTS `easy_parent_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'parent category id',
  `name` varchar(255) NOT NULL COMMENT 'parent category name',
  `image` varchar(255) NOT NULL COMMENT 'parent category image',
  `add_time` int(11) NOT NULL COMMENT 'add time',
  `update_time` int(11) DEFAULT NULL COMMENT 'update time',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'deleted?(0:no,1:yes)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='parent category table' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `easy_parent_category`
--

INSERT INTO `easy_parent_category` (`id`, `name`, `image`, `add_time`, `update_time`, `is_delete`) VALUES
(1, 'Agriculture', '/uploads/cate_13947778694621.jpg', 1394777853, 1394777870, 0);
