/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : awz

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-03-07 10:31:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for awz_sessions
-- ----------------------------
DROP TABLE IF EXISTS `awz_sessions`;
CREATE TABLE `awz_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `awz_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of awz_sessions
-- ----------------------------
INSERT INTO `awz_sessions` VALUES ('bljfu4g3860jheal8g3f0g9jioaj39ki', '127.0.0.1', '1488619937', 0x5F5F63695F6C6173745F726567656E65726174657C693A313438383631393933373B);
INSERT INTO `awz_sessions` VALUES ('1llh2m4r0kn93p56jp78g76qj6vo8s3f', '127.0.0.1', '1488621782', 0x5F5F63695F6C6173745F726567656E65726174657C693A313438383632313738323B);
INSERT INTO `awz_sessions` VALUES ('l8vsg62s9lfgvtj3pvi48flc7o64e59g', '127.0.0.1', '1488621782', 0x5F5F63695F6C6173745F726567656E65726174657C693A313438383632313738323B);
INSERT INTO `awz_sessions` VALUES ('io7vrmoq16f99p5983drn5uae9opuqq1', '127.0.0.1', '1488853862', 0x5F5F63695F6C6173745F726567656E65726174657C693A313438383835333836323B);
INSERT INTO `awz_sessions` VALUES ('okd5v7bq1ap47012g0fhvlaig7n5joa3', '127.0.0.1', '1488853862', 0x5F5F63695F6C6173745F726567656E65726174657C693A313438383835333836323B);
