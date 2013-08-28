/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50532
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50532
File Encoding         : 65001

Date: 2013-07-27 22:54:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admins`
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `username` varchar(55) NOT NULL,
  `password` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of admins
-- ----------------------------

-- ----------------------------
-- Table structure for `elements`
-- ----------------------------
DROP TABLE IF EXISTS `elements`;
CREATE TABLE `elements` (
  `id` int(55) NOT NULL AUTO_INCREMENT,
  `title` varchar(55) DEFAULT NULL,
  `methodname` varchar(55) DEFAULT NULL,
  `type` int(1) DEFAULT NULL,
  `refreshrate` int(55) DEFAULT NULL,
  `parameters` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of elements
-- ----------------------------
INSERT INTO `elements` VALUES ('0', 'Close Application', 'CloseApplication', '1', '0', null);
INSERT INTO `elements` VALUES ('1', 'Running Time', 'RunningTime', '0', '1', null);
INSERT INTO `elements` VALUES ('2', 'My Counter', 'MyCounter', '0', '2', null);
INSERT INTO `elements` VALUES ('3', 'My Text', 'MyText', '0', '1', null);
INSERT INTO `elements` VALUES ('4', 'User info', 'getuserinfo', '2', '0', 'username,age,');

-- ----------------------------
-- Procedure structure for `test_multi_sets`
-- ----------------------------
DROP PROCEDURE IF EXISTS `test_multi_sets`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `test_multi_sets`()
    DETERMINISTIC
begin
        select user() as first_col;
        select user() as first_col, now() as second_col;
        select user() as first_col, now() as second_col, now() as third_col;
        end
;;
DELIMITER ;
