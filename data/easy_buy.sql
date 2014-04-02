/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50534
Source Host           : localhost:3306
Source Database       : easy_buy

Target Server Type    : MYSQL
Target Server Version : 50534
File Encoding         : 65001

Date: 2014-04-02 18:08:41
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='user address table';

-- ----------------------------
-- Records of easy_address
-- ----------------------------
INSERT INTO `easy_address` VALUES ('1', '1', 'Zonkee', null, null, null, 'BeiJing', '0', '1396159315', '1396160574');
INSERT INTO `easy_address` VALUES ('2', '1', 'Zonkee', '13751670932', '0750-3645012', '201293', 'ShangHai', '1', '1396159427', '1396160736');
INSERT INTO `easy_address` VALUES ('3', '1', 'Lzjjie', null, null, '30120', 'JiangSu', '0', '1396159470', '1396160839');
INSERT INTO `easy_address` VALUES ('4', '1', 'Zonkee', null, '78765332', null, 'JiangSu', '0', '1396159508', '1396160891');
INSERT INTO `easy_address` VALUES ('5', '1', 'Zonkee', '18989888026', null, null, 'GuangZhou', '0', '1396159551', '1396160943');

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
INSERT INTO `easy_admin_user` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 'admin@admin.com', '0', '1396415725', '1', 'Administrator!Do not delete!', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='child category table';

-- ----------------------------
-- Records of easy_child_category
-- ----------------------------
INSERT INTO `easy_child_category` VALUES ('1', '3', 'Apple', '1', '/uploads/cate_13961062273505.jpg', '1396105055', '1396106251', '0');
INSERT INTO `easy_child_category` VALUES ('2', '2', 'Cosmetic', '2', '/uploads/cate_13961080166936.jpg', '1396108017', null, '0');

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
  `pay_method` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'pay method(1:paypal, 2:alipay)',
  `sale_amount` int(11) DEFAULT NULL COMMENT 'amount for sale',
  `size` varchar(30) DEFAULT NULL COMMENT 'goods size',
  `weight` int(11) DEFAULT NULL COMMENT 'goods weight',
  `color` varchar(30) DEFAULT NULL COMMENT 'goods color',
  `area` varchar(255) DEFAULT NULL COMMENT 'goods area',
  `quality` varchar(255) DEFAULT NULL COMMENT 'goods quality',
  `guarantee` varchar(255) DEFAULT NULL COMMENT 'guarantee service',
  `description` varchar(255) DEFAULT NULL COMMENT 'goods description',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'deleted?(0:no,1:yes)',
  `add_time` int(11) NOT NULL COMMENT 'add time',
  `update_time` int(11) DEFAULT NULL COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='goods table';

-- ----------------------------
-- Records of easy_goods
-- ----------------------------
INSERT INTO `easy_goods` VALUES ('1', '1', '3', 'Red Apple', '16E9E6812604419F', '12.00', '1000', '1', '30', '2', null, '12', '100', 'red', 'China', '120', 'nothing', 'very good', '0', '1396107481', '1396111129');
INSERT INTO `easy_goods` VALUES ('2', '2', '2', 'SkII', '9A7B3FAC5B75ABD6', '1200.00', '100000', '2', 'g', '1', null, '50', '500', 'while', 'Japan', '1000', 'nothing', 'very good', '0', '1396108398', null);
INSERT INTO `easy_goods` VALUES ('3', '1', '3', 'Green Apple', 'CA1C871B7129A769', '13.00', '1000', '1', 'g', '1', null, '30', '200', 'Green', 'China', 'very good', 'very good', 'very good', '0', '1396368171', '1396369445');

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of easy_goods_image
-- ----------------------------
INSERT INTO `easy_goods_image` VALUES ('1', '1', '3', '1', '/uploads/goods_13961074791465.jpg', '0', '1396107481', '1396111129');
INSERT INTO `easy_goods_image` VALUES ('2', '1', '3', '1', '/uploads/goods_13961074798873.jpg', '0', '1396107481', '1396111129');
INSERT INTO `easy_goods_image` VALUES ('3', '1', '3', '1', '/uploads/goods_13961074795665.jpg', '1', '1396107481', '1396110493');
INSERT INTO `easy_goods_image` VALUES ('4', '2', '2', '2', '/uploads/goods_13961083964957.jpg', '0', '1396108398', null);
INSERT INTO `easy_goods_image` VALUES ('5', '2', '2', '2', '/uploads/goods_13961083967116.jpg', '1', '1396108398', null);
INSERT INTO `easy_goods_image` VALUES ('6', '2', '2', '2', '/uploads/goods_13961083962608.jpg', '1', '1396108398', null);
INSERT INTO `easy_goods_image` VALUES ('7', '2', '2', '2', '/uploads/goods_13961083968649.jpg', '1', '1396108398', null);
INSERT INTO `easy_goods_image` VALUES ('8', '2', '2', '2', '/uploads/goods_13961083969207.jpg', '1', '1396108398', null);
INSERT INTO `easy_goods_image` VALUES ('9', '3', '3', '1', '/uploads/goods_13963681691656.jpg', '0', '1396368171', '1396369445');
INSERT INTO `easy_goods_image` VALUES ('10', '3', '3', '1', '/uploads/goods_13963681692633.jpg', '0', '1396368171', '1396369445');
INSERT INTO `easy_goods_image` VALUES ('11', '3', '3', '1', '/uploads/goods_13963681704356.jpg', '0', '1396368171', '1396369445');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='member table';

-- ----------------------------
-- Records of easy_member
-- ----------------------------
INSERT INTO `easy_member` VALUES ('1', 'lzjjie', 'e10adc3949ba59abbe56e057f20f883e', '13698987864', null, '1', '1', '1', 'lzjjie@163.com', '1', '1396416741', '1393234186');
INSERT INTO `easy_member` VALUES ('2', 'hxk', 'e10adc3949ba59abbe56e057f20f883e', '134565655', null, '0', '0', '0', 'dfdfdsfsdf', '0', null, null);
INSERT INTO `easy_member` VALUES ('6', 'temo', 'e10adc3949ba59abbe56e057f20f883e', '13698987864', null, null, '0', '0', '971318606@qq.com', '1393688224', null, null);
INSERT INTO `easy_member` VALUES ('10', 'demo', 'e10adc3949ba59abbe56e057f20f883e', null, null, null, '0', '0', '971318606@qq.com', '1396084684', null, null);
INSERT INTO `easy_member` VALUES ('11', '???', 'b842f1db09ef1bfda2ae1c1f70ec57c7', null, null, null, '0', '0', 'a@a', '1396091448', null, null);
INSERT INTO `easy_member` VALUES ('12', '???1', '0d1b08c34858921bc7c662b228acb7ba', null, null, null, '0', '0', '229204897@qq.com', '1396091846', null, null);
INSERT INTO `easy_member` VALUES ('13', '???2', '0d1b08c34858921bc7c662b228acb7ba', null, null, null, '0', '0', '229204897@qq.com', '1396092492', null, null);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='parent category table';

-- ----------------------------
-- Records of easy_parent_category
-- ----------------------------
INSERT INTO `easy_parent_category` VALUES ('1', 'Chemical', '1', '/uploads/cate_13961032061915.jpg', '1396103208', null, '0');
INSERT INTO `easy_parent_category` VALUES ('2', 'Chemical', '2', '/uploads/cate_13961033087104.jpg', '1396103221', '1396103309', '0');
INSERT INTO `easy_parent_category` VALUES ('3', 'Agriculture', '1', '/uploads/cate_13961043595595.jpg', '1396104360', null, '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of easy_publish
-- ----------------------------
INSERT INTO `easy_publish` VALUES ('1', '1', '1', 'Green Apple', 'ZonKee', 'Lee', 'China', '1', '020-88665593', '13751689897', 'abc@abc.com', 'Tencent', null, null, null, null, '10', '20', '30', '40', '500', 'Green', 'Eating', '300', 'apple', 'This is a test', '1396415728');
