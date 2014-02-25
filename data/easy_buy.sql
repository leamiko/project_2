-- phpMyAdmin SQL Dump
-- version 3.3.8
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 02 月 25 日 12:44
-- 服务器版本: 5.5.34
-- PHP 版本: 5.3.18

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `easy_admin_user`
--

INSERT INTO `easy_admin_user` (`id`, `username`, `password`, `real_name`, `email`, `add_time`, `last_time`, `status`, `desc`, `type`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 'admin@admin.com', 0, 1393302460, 1, 'Administrator!Do not delete!', 1),
(4, 'demo', 'e10adc3949ba59abbe56e057f20f883e', 'demo', '', 1393059963, 1393060050, 1, '', 0);

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
  `is_vip` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'is a vip account(0:no,1:yes)',
  `email` varchar(255) NOT NULL COMMENT 'user email',
  `register_time` int(11) NOT NULL COMMENT 'user register time',
  `last_time` int(11) DEFAULT NULL COMMENT 'user last login time',
  `upgrade_time` int(11) DEFAULT NULL COMMENT 'upgrade time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='member table' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `easy_member`
--

INSERT INTO `easy_member` (`id`, `account`, `password`, `phone`, `avatar`, `sex`, `is_vip`, `email`, `register_time`, `last_time`, `upgrade_time`) VALUES
(1, 'lzjjie', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 1, 1, 'lzjjie@163.com', 1, 1393303307, 1393234186),
(2, 'hxk', 'e10adc3949ba59abbe56e057f20f883e', '134565655', NULL, 0, 0, 'dfdfdsfsdf', 0, NULL, NULL);
