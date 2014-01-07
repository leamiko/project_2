-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 01 月 06 日 23:50
-- 服务器版本: 5.5.19
-- PHP 版本: 5.4.0RC4

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
-- 表的结构 `easy_admin_user`
--

CREATE TABLE IF NOT EXISTS `easy_admin_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `real_name` varchar(16) NOT NULL COMMENT '管理员真实姓名',
  `email` varchar(255) DEFAULT NULL COMMENT '用户邮箱',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `last_time` int(10) NOT NULL COMMENT '用户上次登录时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '用户状态(1：正常，0：禁用)',
  `desc` varchar(70) DEFAULT NULL COMMENT '管理员描述',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '管理员类型，1为系统管理员，0为普通管理员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `easy_admin_user`
--

INSERT INTO `easy_admin_user` (`id`, `username`, `password`, `real_name`, `email`, `add_time`, `last_time`, `status`, `desc`, `type`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 'admin@admin.com', 0, 1389022808, 1, '系统管理员帐号，勿删', 1);
