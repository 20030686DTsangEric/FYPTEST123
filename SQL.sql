/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : 20220327teacherstudents

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 04/04/2022 10:30:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_table
-- ----------------------------
DROP TABLE IF EXISTS `admin_table`;
CREATE TABLE `admin_table`  (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_email_address` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `admin_password` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `admin_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `school_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `school_address` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `school_contact_no` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `school_logo` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`admin_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_table
-- ----------------------------
INSERT INTO `admin_table` VALUES (1, 'johnsmith@gmail.com', 'password', 'John smith', 'The HONG KONG POLYTECNIC UNIVERSITY', '115, Last Lane, NYC', '741287410', '');

-- ----------------------------
-- Table structure for appointment_table
-- ----------------------------
DROP TABLE IF EXISTS `appointment_table`;
CREATE TABLE `appointment_table`  (
  `appointment_id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) NULL DEFAULT NULL,
  `student_id` int(11) NULL DEFAULT NULL,
  `teacher_schedule_id` int(11) NULL DEFAULT NULL,
  `appointment_number` int(11) NULL DEFAULT NULL,
  `reason_for_appointment` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `appointment_time` time(0) NULL DEFAULT NULL,
  `status` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `student_come_into_school` enum('No','Yes') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `teacher_comment` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `start_time` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`appointment_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of appointment_table
-- ----------------------------
INSERT INTO `appointment_table` VALUES (3, 1, 3, 2, 1000, 'Pain in Stomach', '09:00:00', 'Cancel', 'No', '', '12:00');
INSERT INTO `appointment_table` VALUES (4, 1, 3, 2, 1001, 'Paint in stomach', '09:00:00', 'In Process', 'Yes', '', '12:00');
INSERT INTO `appointment_table` VALUES (5, 1, 4, 2, 1002, 'For Delivery', '09:30:00', 'In Process', 'Yes', 'She gave birth to boy baby.', '12:00');
INSERT INTO `appointment_table` VALUES (6, 5, 3, 7, 1003, 'Fever and cold.', '18:00:00', 'In Process', 'Yes', '', '12:00');
INSERT INTO `appointment_table` VALUES (7, 6, 5, 13, 1004, 'Pain in Stomach.', '15:30:00', 'In Process', 'Yes', 'Acidity Problem. ', '12:00');
INSERT INTO `appointment_table` VALUES (8, 3, 6, 15, 1005, '1231', '08:58:00', 'In Process', 'Yes', NULL, '12:03');
INSERT INTO `appointment_table` VALUES (9, 2, 6, 16, 1006, 'hahaha', '09:45:00', 'Completed', 'Yes', 'change', '12:12');
INSERT INTO `appointment_table` VALUES (10, 2, 6, 17, 1007, 'test', '07:50:00', 'Completed', 'Yes', 'wancheng', '07:51');

-- ----------------------------
-- Table structure for student_table
-- ----------------------------
DROP TABLE IF EXISTS `student_table`;
CREATE TABLE `student_table`  (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_email_address` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `student_password` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `student_first_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `student_last_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `student_date_of_birth` date NULL DEFAULT NULL,
  `student_gender` enum('Male','Female','Other') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `student_major` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `studentID` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `student_year` enum('Year 1','Year 2','Year 3','Year 4','Post Graduate') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `student_added_on` datetime(0) NULL DEFAULT NULL,
  `student_verification_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `email_verify` enum('No','Yes') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`student_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of student_table
-- ----------------------------
INSERT INTO `student_table` VALUES (3, 'jacobmartin@gmail.com', 'password', 'Jacob', 'Martin', '2021-02-26', 'Male', 'Green view, 1025, NYC', '85745635210', 'Post Graduate', '2021-02-18 16:34:55', 'b1f3f8409f7687072adb1f1b7c22d4b0', 'Yes');
INSERT INTO `student_table` VALUES (4, 'oliviabaker@gmail.com', 'password', 'Olivia', 'Baker', '2001-04-05', 'Female', 'Diamond street, 115, NYC', '7539518520', 'Post Graduate', '2021-02-19 18:28:23', '8902e16ef62a556a8e271c9930068fea', 'Yes');
INSERT INTO `student_table` VALUES (5, 'web-tutorial@programmer.net', 'password', 'Amber', 'Anderson', '1995-07-25', 'Female', '2083 Cameron Road Buffalo, NY 14202', '75394511442', 'Post Graduate', '2021-02-23 17:50:06', '1909d59e254ab7e433d92f014d82ba3d', 'Yes');
INSERT INTO `student_table` VALUES (6, '123456789@qq.com', '123456', 'sun', '666', '2022-03-28', 'Male', 'test address', '1888888888', 'Year 3', '2022-03-28 13:03:41', '30a82cd339c0f0c2b47d9386a941f5ad', 'Yes');

-- ----------------------------
-- Table structure for teacher_schedule_table
-- ----------------------------
DROP TABLE IF EXISTS `teacher_schedule_table`;
CREATE TABLE `teacher_schedule_table`  (
  `teacher_schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) NULL DEFAULT NULL,
  `teacher_schedule_date` date NULL DEFAULT NULL,
  `teacher_schedule_day` enum('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `teacher_schedule_start_time` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `teacher_schedule_end_time` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `average_consulting_time` int(5) NULL DEFAULT NULL,
  `teacher_schedule_status` enum('Active','Inactive') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`teacher_schedule_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of teacher_schedule_table
-- ----------------------------
INSERT INTO `teacher_schedule_table` VALUES (2, 1, '2021-02-19', 'Friday', '09:00', '14:00', 15, 'Active');
INSERT INTO `teacher_schedule_table` VALUES (3, 2, '2021-02-19', 'Friday', '09:00', '12:00', 15, 'Active');
INSERT INTO `teacher_schedule_table` VALUES (4, 5, '2021-02-19', 'Friday', '10:00', '14:00', 10, 'Active');
INSERT INTO `teacher_schedule_table` VALUES (5, 3, '2021-02-19', 'Friday', '13:00', '17:00', 20, 'Active');
INSERT INTO `teacher_schedule_table` VALUES (6, 4, '2021-02-19', 'Friday', '15:00', '18:00', 5, 'Active');
INSERT INTO `teacher_schedule_table` VALUES (7, 5, '2021-02-22', 'Monday', '18:00', '20:00', 10, 'Active');
INSERT INTO `teacher_schedule_table` VALUES (8, 2, '2021-02-24', 'Wednesday', '09:30', '12:30', 10, 'Active');
INSERT INTO `teacher_schedule_table` VALUES (9, 5, '2021-02-24', 'Wednesday', '11:00', '15:00', 10, 'Active');
INSERT INTO `teacher_schedule_table` VALUES (10, 1, '2021-02-24', 'Wednesday', '12:00', '15:00', 10, 'Active');
INSERT INTO `teacher_schedule_table` VALUES (15, 3, '2022-04-09', 'Saturday', '08:58', '19:58', 40, 'Active');
INSERT INTO `teacher_schedule_table` VALUES (16, 2, '2022-04-07', 'Thursday', '09:45', '18:45', 10, 'Active');
INSERT INTO `teacher_schedule_table` VALUES (17, 2, '2022-05-04', 'Wednesday', '07:50', '15:50', 45, 'Active');

-- ----------------------------
-- Table structure for teacher_table
-- ----------------------------
DROP TABLE IF EXISTS `teacher_table`;
CREATE TABLE `teacher_table`  (
  `teacher_id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_email_address` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `teacher_password` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `teacher_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `teacher_profile_image` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `teacher_phone_no` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `teacher_address` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `teacher_date_of_birth` date NULL DEFAULT NULL,
  `teacher_degree` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `teacher_expert_in` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `teacher_status` enum('Active','Inactive') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `teacher_added_on` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`teacher_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of teacher_table
-- ----------------------------
INSERT INTO `teacher_table` VALUES (1, 'peterparker@gmail.com', 'password', 'Dr. Peter Parker', '../images/10872.jpg', '7539518520', '102, Sky View, NYC', '1985-10-29', 'MBBS MS', 'Sergen', 'Active', '2021-02-15 17:04:59');
INSERT INTO `teacher_table` VALUES (2, 'adambrodly@gmail.com', 'password', 'Dr. Adam Broudly', '../images/21336.jpg', '753852963', '105, Fort, NYC', '1982-08-10', 'MBBS MD(Cardiac)', 'Cardiologist', 'Active', '2021-02-18 15:00:32');
INSERT INTO `teacher_table` VALUES (3, 'sophia.parker@gmail.com', 'password', 'Dr. Sophia Parker', '../images/13838.jpg', '7417417410', '50, Best street CA', '1989-04-03', 'MBBS', 'Gynacologist', 'Active', '2021-02-18 15:05:02');
INSERT INTO `teacher_table` VALUES (4, 'williampeterson@gmail.com', 'password', 'Dr. William Peterson', '../images/9498.jpg', '8523698520', '32, Green City, NYC', '1984-06-11', 'MBBS MD', 'Nurologist', 'Active', '2021-02-18 15:08:24');
INSERT INTO `teacher_table` VALUES (5, 'emmalarsdattor@gmail.com', 'password', 'Dr. Emma Larsdattor', '../images/1613641523.png', '9635852025', '25, Silver Arch', '1988-03-03', 'MBB', 'General Physian', 'Active', '2021-02-18 15:15:23');
INSERT INTO `teacher_table` VALUES (6, 'manuel.armstrong@gmail.com', 'password', 'Dr. Manuel Armstrong', '../images/1614081376.png', '8523697410', '2378 Fire Access Road Asheboro, NC 27203', '1989-03-01', 'MBBS MD (Medicine)', 'General Physician', 'Active', '2021-02-23 17:26:16');

SET FOREIGN_KEY_CHECKS = 1;
