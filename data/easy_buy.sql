/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50534
Source Host           : localhost:3306
Source Database       : easy_buy

Target Server Type    : MYSQL
Target Server Version : 50534
File Encoding         : 65001

Date: 2014-04-22 17:40:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `easy_address`
-- ----------------------------
DROP TABLE IF EXISTS `easy_address`;
CREATE TABLE `easy_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT 'user_id',
  `name` varchar(30) NOT NULL COMMENT 'consignee name',
  `phone` varchar(30) DEFAULT NULL COMMENT 'consignee phone',
  `telephone` varchar(30) DEFAULT NULL COMMENT 'consignee telephone',
  `zip` varchar(30) DEFAULT NULL COMMENT 'zip code',
  `address` varchar(255) NOT NULL COMMENT 'consignee address',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'default address(0:no,1:yes)',
  `add_time` int(11) NOT NULL COMMENT 'add time',
  `update_time` int(11) DEFAULT NULL COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='user address table';

-- ----------------------------
-- Records of easy_address
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_admin_user`
-- ----------------------------
DROP TABLE IF EXISTS `easy_admin_user`;
CREATE TABLE `easy_admin_user` (
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='administrators table';

-- ----------------------------
-- Records of easy_admin_user
-- ----------------------------
INSERT INTO `easy_admin_user` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 'admin@admin.com', '0', '1398137593', '1', 'Administrator!Do not delete!', '1');

-- ----------------------------
-- Table structure for `easy_area`
-- ----------------------------
DROP TABLE IF EXISTS `easy_area`;
CREATE TABLE `easy_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `zip_code` varchar(30) NOT NULL COMMENT 'Zip code',
  `name_zh` varchar(255) NOT NULL COMMENT 'Chinese name',
  `name_en` varchar(255) NOT NULL COMMENT 'English name',
  `name_ar` varchar(255) NOT NULL COMMENT 'Arab name',
  `add_time` int(11) NOT NULL COMMENT 'Add time',
  `update_time` int(11) DEFAULT NULL COMMENT 'Update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='area table';

-- ----------------------------
-- Records of easy_area
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_bidding`
-- ----------------------------
DROP TABLE IF EXISTS `easy_bidding`;
CREATE TABLE `easy_bidding` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_id` int(11) NOT NULL COMMENT 'goods id',
  `c_cate_id` int(11) NOT NULL COMMENT 'goods child category id',
  `user_id` int(11) NOT NULL COMMENT 'user id',
  `price` decimal(12,2) NOT NULL COMMENT 'bid price',
  `bidding_time` int(11) NOT NULL COMMENT 'bidding time',
  `remark` text COMMENT 'Bidding remark',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='bidding goods table';

-- ----------------------------
-- Records of easy_bidding
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_child_category`
-- ----------------------------
DROP TABLE IF EXISTS `easy_child_category`;
CREATE TABLE `easy_child_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'child category id',
  `parent_id` int(11) NOT NULL COMMENT 'parent category id',
  `name` varchar(255) NOT NULL COMMENT 'child category name',
  `business_model` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'business model(1:b2c,2:b2b)',
  `image` varchar(255) NOT NULL COMMENT 'child category image',
  `add_time` int(11) NOT NULL COMMENT 'add time',
  `update_time` int(11) DEFAULT NULL COMMENT 'update time',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'deleted?(0:no,1:yes)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='child category table';

-- ----------------------------
-- Records of easy_child_category
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_goods`
-- ----------------------------
DROP TABLE IF EXISTS `easy_goods`;
CREATE TABLE `easy_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'goods id',
  `c_cate_id` int(11) NOT NULL COMMENT 'child category id',
  `p_cate_id` int(11) NOT NULL COMMENT 'parent category id',
  `name` varchar(255) NOT NULL COMMENT 'goods name',
  `item_number` varchar(255) NOT NULL COMMENT 'item number',
  `price` decimal(12,2) NOT NULL COMMENT 'goods price',
  `stock` int(11) NOT NULL COMMENT 'goods stock',
  `business_model` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'goods''s business model(1:b2c,2:b2b)',
  `unit` varchar(30) NOT NULL COMMENT 'goods unit',
  `is_bidding` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'is bidding goods?(1:yes,0:no)',
  `pay_method` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'pay method(1:paypal, 2:alipay)',
  `sale_amount` int(11) DEFAULT NULL COMMENT 'amount for sale',
  `size` varchar(30) DEFAULT NULL COMMENT 'goods size',
  `weight` int(11) DEFAULT NULL COMMENT 'goods weight',
  `color` varchar(30) DEFAULT NULL COMMENT 'goods color',
  `area` int(11) NOT NULL COMMENT 'goods area id',
  `quality` varchar(255) DEFAULT NULL COMMENT 'goods quality',
  `guarantee` varchar(255) DEFAULT NULL COMMENT 'guarantee service',
  `description` varchar(255) DEFAULT NULL COMMENT 'goods description',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'deleted?(0:no,1:yes)',
  `add_time` int(11) NOT NULL COMMENT 'add time',
  `update_time` int(11) DEFAULT NULL COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='goods table';

-- ----------------------------
-- Records of easy_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_goods_image`
-- ----------------------------
DROP TABLE IF EXISTS `easy_goods_image`;
CREATE TABLE `easy_goods_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_id` int(11) NOT NULL COMMENT 'Goods Id',
  `p_cate_id` int(11) NOT NULL COMMENT 'parent category id',
  `c_cate_id` int(11) NOT NULL COMMENT 'child category id',
  `image` varchar(255) NOT NULL COMMENT 'goods picture',
  `is_delete` tinyint(1) NOT NULL COMMENT 'is deleted?(1: yes, 0: no)',
  `add_time` int(11) NOT NULL COMMENT 'add time',
  `update_time` int(11) DEFAULT NULL COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='goods image table';

-- ----------------------------
-- Records of easy_goods_image
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_member`
-- ----------------------------
DROP TABLE IF EXISTS `easy_member`;
CREATE TABLE `easy_member` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='member table';

-- ----------------------------
-- Records of easy_member
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_news`
-- ----------------------------
DROP TABLE IF EXISTS `easy_news`;
CREATE TABLE `easy_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(255) NOT NULL COMMENT 'news title',
  `content` text NOT NULL COMMENT 'news content',
  `language` tinyint(1) NOT NULL DEFAULT '2' COMMENT 'news language(1:Chinese,2:English,3:Arabic)',
  `add_time` int(11) NOT NULL COMMENT 'add time',
  `update_time` int(11) DEFAULT NULL COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='news table\r\n';

-- ----------------------------
-- Records of easy_news
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_news_image`
-- ----------------------------
DROP TABLE IF EXISTS `easy_news_image`;
CREATE TABLE `easy_news_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `news_id` int(11) NOT NULL COMMENT 'news id',
  `image` varchar(255) NOT NULL COMMENT 'news image',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'is deleted?(0:no,1:yes)',
  `add_time` int(11) NOT NULL COMMENT 'add time',
  `update_time` int(11) DEFAULT NULL COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='news images table';

-- ----------------------------
-- Records of easy_news_image
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_notification`
-- ----------------------------
DROP TABLE IF EXISTS `easy_notification`;
CREATE TABLE `easy_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `content` varchar(255) NOT NULL COMMENT 'notification content',
  `add_time` int(11) NOT NULL COMMENT 'add notification time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='system notification table\r\n';

-- ----------------------------
-- Records of easy_notification
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_order`
-- ----------------------------
DROP TABLE IF EXISTS `easy_order`;
CREATE TABLE `easy_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT 'user id',
  `address_id` int(11) NOT NULL COMMENT 'address id',
  `shipping_type` int(11) NOT NULL COMMENT 'shipping type',
  `pay_method` varchar(255) NOT NULL COMMENT 'pay method',
  `order_number` varchar(32) NOT NULL COMMENT 'order number',
  `status` tinyint(1) NOT NULL COMMENT 'order status(1:paid,0:unpaid)',
  `order_time` int(11) NOT NULL COMMENT 'order time',
  `remark` text COMMENT 'order leave',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='order table';

-- ----------------------------
-- Records of easy_order
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `easy_order_goods`;
CREATE TABLE `easy_order_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_id` int(11) NOT NULL COMMENT 'goods id',
  `goods_price` decimal(12,2) NOT NULL COMMENT 'goods price',
  `goods_amount` int(11) NOT NULL COMMENT 'goods amount',
  `order_id` int(11) NOT NULL COMMENT 'order id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='order goods';

-- ----------------------------
-- Records of easy_order_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_parent_category`
-- ----------------------------
DROP TABLE IF EXISTS `easy_parent_category`;
CREATE TABLE `easy_parent_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'parent category id',
  `name` varchar(255) NOT NULL COMMENT 'parent category name',
  `business_model` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'business model(1:b2c,2:b2b)',
  `image` varchar(255) NOT NULL COMMENT 'parent category image',
  `add_time` int(11) NOT NULL COMMENT 'add time',
  `update_time` int(11) DEFAULT NULL COMMENT 'update time',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'deleted?(0:no,1:yes)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='parent category table';

-- ----------------------------
-- Records of easy_parent_category
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_publish`
-- ----------------------------
DROP TABLE IF EXISTS `easy_publish`;
CREATE TABLE `easy_publish` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT 'user id',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'publish type(1:buy,0:sell)',
  `goods_name` varchar(255) NOT NULL COMMENT 'goods name',
  `publisher_second_name` varchar(255) NOT NULL COMMENT 'publisher second name',
  `publisher_first_name` varchar(255) NOT NULL COMMENT 'publisher first name',
  `country` varchar(255) NOT NULL COMMENT 'country',
  `carton` int(11) NOT NULL COMMENT 'carton',
  `telephone` varchar(30) NOT NULL COMMENT 'telephone',
  `phone` varchar(30) NOT NULL COMMENT 'cellphone',
  `email` varchar(255) NOT NULL COMMENT 'email',
  `company` varchar(255) DEFAULT NULL COMMENT 'company',
  `image_1` varchar(255) DEFAULT NULL COMMENT 'goods first image',
  `image_2` varchar(255) DEFAULT NULL COMMENT 'goods second image',
  `image_3` varchar(255) DEFAULT NULL COMMENT 'goods third image',
  `image_4` varchar(255) DEFAULT NULL COMMENT 'goods fourth image',
  `length` int(11) DEFAULT NULL COMMENT 'goods length',
  `width` int(11) DEFAULT NULL COMMENT 'goods width',
  `height` int(11) DEFAULT NULL COMMENT 'goods height',
  `thickness` int(11) DEFAULT NULL COMMENT 'goods thickness',
  `weight` int(11) DEFAULT NULL COMMENT 'goods weight',
  `color` varchar(255) DEFAULT NULL COMMENT 'goods color',
  `use` varchar(255) DEFAULT NULL COMMENT 'goods use',
  `quantity` int(11) DEFAULT NULL COMMENT 'goods quantity',
  `material` varchar(255) DEFAULT NULL COMMENT 'goods material',
  `remark` varchar(255) DEFAULT NULL COMMENT 'remark',
  `publish_time` int(11) NOT NULL COMMENT 'Publish time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='user publish table';

-- ----------------------------
-- Records of easy_publish
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_shipping`
-- ----------------------------
DROP TABLE IF EXISTS `easy_shipping`;
CREATE TABLE `easy_shipping` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `business_model` tinyint(1) NOT NULL COMMENT 'business model(1:b2c,2:b2b)',
  `type` tinyint(1) NOT NULL COMMENT 'shipping type(1:air,2:ship,3:highway)',
  `name` varchar(255) NOT NULL COMMENT 'shipping company name',
  `add_time` int(11) NOT NULL COMMENT 'add time',
  `update_time` int(11) DEFAULT NULL COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='shipping type table';

-- ----------------------------
-- Records of easy_shipping
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_shipping_agency`
-- ----------------------------
DROP TABLE IF EXISTS `easy_shipping_agency`;
CREATE TABLE `easy_shipping_agency` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT 'User id',
  `first_name` varchar(255) NOT NULL COMMENT 'First name',
  `last_name` varchar(255) NOT NULL COMMENT 'Last name',
  `telephone` varchar(255) NOT NULL COMMENT 'Telephone',
  `phone` varchar(255) NOT NULL COMMENT 'Phone',
  `email` varchar(255) NOT NULL COMMENT 'Email',
  `company` varchar(255) NOT NULL COMMENT 'Company',
  `country` varchar(255) NOT NULL COMMENT 'Country',
  `goods_name` varchar(255) NOT NULL COMMENT 'Goods name',
  `shipping_type` varchar(255) NOT NULL COMMENT 'Shipping type',
  `quanlity` int(11) NOT NULL COMMENT 'Quanlity',
  `shipping_port` varchar(255) NOT NULL COMMENT 'Shipping port',
  `destination_port` varchar(255) NOT NULL COMMENT 'Destination port',
  `container` int(11) DEFAULT NULL COMMENT 'Container',
  `wish_shipping_line` varchar(255) DEFAULT NULL COMMENT 'Wish shipping line',
  `loading_time` int(11) DEFAULT NULL COMMENT 'Loading time',
  `weight` int(11) DEFAULT NULL COMMENT 'Weight',
  `remark` varchar(255) DEFAULT NULL COMMENT 'Remark',
  `document_type` varchar(255) DEFAULT NULL COMMENT 'Document type',
  `add_time` int(11) NOT NULL COMMENT 'Add time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='shipping agency table';

-- ----------------------------
-- Records of easy_shipping_agency
-- ----------------------------

-- ----------------------------
-- Table structure for `easy_subscription`
-- ----------------------------
DROP TABLE IF EXISTS `easy_subscription`;
CREATE TABLE `easy_subscription` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `p_cate_id` int(11) NOT NULL COMMENT 'parent category id',
  `c_cate_id` int(11) NOT NULL COMMENT 'child category id',
  `user_id` int(11) NOT NULL COMMENT 'user id',
  `subscribe_time` int(11) NOT NULL COMMENT 'subscribe time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='vip user subscription table';

-- ----------------------------
-- Records of easy_subscription
-- ----------------------------
