/*
 Navicat Premium Data Transfer

 Source Server         : main
 Source Server Type    : MySQL
 Source Server Version : 100410
 Source Host           : localhost:3306
 Source Schema         : asana_telegram_integration

 Target Server Type    : MySQL
 Target Server Version : 100410
 File Encoding         : 65001

 Date: 17/01/2020 20:35:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for asanaaccount
-- ----------------------------
DROP TABLE IF EXISTS `asanaaccount`;
CREATE TABLE `asanaaccount`  (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `api_key` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of asanaaccount
-- ----------------------------
INSERT INTO `asanaaccount` VALUES (1, '0/22e1e5c6f60f9c4515dd965905cc135d');

-- ----------------------------
-- Table structure for asanaaccount_user
-- ----------------------------
DROP TABLE IF EXISTS `asanaaccount_user`;
CREATE TABLE `asanaaccount_user`  (
  `ID_user` int(10) UNSIGNED NOT NULL,
  `ID_asana` int(10) UNSIGNED NOT NULL,
  `user_id_in_asana` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`ID_user`, `ID_asana`) USING BTREE,
  INDEX `fk_AsanaAccount_User_AsanaAccount_1`(`ID_asana`) USING BTREE,
  CONSTRAINT `fk_AsanaAccount_User_AsanaAccount_1` FOREIGN KEY (`ID_asana`) REFERENCES `asanaaccount` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_AsanaAccount_User_User_1` FOREIGN KEY (`ID_user`) REFERENCES `user` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of asanaaccount_user
-- ----------------------------
INSERT INTO `asanaaccount_user` VALUES (1, 1, '1157331118523004');

-- ----------------------------
-- Table structure for task
-- ----------------------------
DROP TABLE IF EXISTS `task`;
CREATE TABLE `task`  (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ID_user` int(10) UNSIGNED NOT NULL,
  `ID_in_asana` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `time_to_notify` datetime(0) NOT NULL,
  `sent` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`) USING BTREE,
  UNIQUE INDEX `unq_task_id`(`ID_user`, `ID_in_asana`) USING BTREE,
  CONSTRAINT `fk_Task_User_1` FOREIGN KEY (`ID_user`) REFERENCES `user` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of task
-- ----------------------------
INSERT INTO `task` VALUES (1, 1, '123', 'namwe', NULL, '2020-01-17 19:20:45', 0);
INSERT INTO `task` VALUES (2, 1, '1157614414534089', '4byAPI_updated2', '', '2020-01-22 09:00:00', 0);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ID_telegram` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'Yaraday');

SET FOREIGN_KEY_CHECKS = 1;
