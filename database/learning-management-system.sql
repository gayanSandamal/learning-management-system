/*
Navicat MariaDB Data Transfer

Source Server         : localhost
Source Server Version : 100419
Source Host           : localhost:3306
Source Database       : akurata

Target Server Type    : MariaDB
Target Server Version : 100419
File Encoding         : 65001

Date: 2021-10-15 22:21:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for approvals
-- ----------------------------
DROP TABLE IF EXISTS `approvals`;
CREATE TABLE `approvals` (
  `id` tinyint(11) NOT NULL,
  `state` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of approvals
-- ----------------------------
INSERT INTO `approvals` VALUES ('0', 'pending');
INSERT INTO `approvals` VALUES ('1', 'approved');
INSERT INTO `approvals` VALUES ('2', 'rejected');

-- ----------------------------
-- Table structure for app_list
-- ----------------------------
DROP TABLE IF EXISTS `app_list`;
CREATE TABLE `app_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_type` int(1) DEFAULT NULL,
  `download_url` varchar(255) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  `feature` tinyint(1) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `app_type_fk` (`app_type`),
  CONSTRAINT `app_type_fk` FOREIGN KEY (`app_type`) REFERENCES `app_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of app_list
-- ----------------------------
INSERT INTO `app_list` VALUES ('1', '1', 'https://yourdomain.com/downloadable/windows/akurata-lk-beta-testing Setup 0.1.0.exe', '0.1.0', '1', '2021-10-15 22:16:05');
INSERT INTO `app_list` VALUES ('2', '3', 'https://yourdomain.com/downloadable/windows/akurata-lk-beta-testing Setup 0.1.0.exe', '0.1.0', '1', '2021-10-15 22:16:12');
INSERT INTO `app_list` VALUES ('3', '4', 'https://yourdomain.com/downloadable/windows/akurata-lk-beta-testing Setup 0.1.0.exe', '0.1.0', '1', '2021-10-15 22:16:19');

-- ----------------------------
-- Table structure for app_types
-- ----------------------------
DROP TABLE IF EXISTS `app_types`;
CREATE TABLE `app_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `line1` varchar(255) DEFAULT NULL,
  `line2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of app_types
-- ----------------------------
INSERT INTO `app_types` VALUES ('1', 'Windows OS', 'Click here to download windows standalone \"desktop\" application', 'For windows 7 / 8 / 10');
INSERT INTO `app_types` VALUES ('2', 'Mac OS', 'Click here to download MacOS standalone \"desktop\" application', 'For Mac OS');
INSERT INTO `app_types` VALUES ('3', 'android OS', 'Click here to download mobile application from Google Playstore', 'For Android OS');
INSERT INTO `app_types` VALUES ('4', 'IOS', 'Click here to download mobile application from Apple Appstore', 'For iOS');

-- ----------------------------
-- Table structure for assigned_categories
-- ----------------------------
DROP TABLE IF EXISTS `assigned_categories`;
CREATE TABLE `assigned_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_type_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `fee` float(255,0) DEFAULT NULL,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `demo_video_src` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ass_cat_post_type_fk` (`post_type_id`),
  KEY `ass_cat_fk` (`category_id`),
  KEY `ass_cat_user_fk` (`user_id`),
  CONSTRAINT `ass_cat_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `ass_cat_post_type_fk` FOREIGN KEY (`post_type_id`) REFERENCES `post_types` (`id`),
  CONSTRAINT `ass_cat_user_fk` FOREIGN KEY (`user_id`) REFERENCES `useres` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of assigned_categories
-- ----------------------------

-- ----------------------------
-- Table structure for bank_slips
-- ----------------------------
DROP TABLE IF EXISTS `bank_slips`;
CREATE TABLE `bank_slips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) DEFAULT NULL,
  `created_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bank_slips
-- ----------------------------

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_type_id` int(11) DEFAULT NULL,
  `parent_category_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT '',
  `slug` varchar(255) DEFAULT NULL,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `thumbnail` varchar(255) DEFAULT '',
  `image` varchar(255) DEFAULT '',
  `display_order` int(11) DEFAULT NULL,
  `has_child` tinyint(1) DEFAULT 0,
  `is_show` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `cat_type_fk` (`post_type_id`),
  CONSTRAINT `cat_type_fk` FOREIGN KEY (`post_type_id`) REFERENCES `post_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES ('1', '1', null, 'Job seekers', 'job-seekers', '2021-02-03 21:44:12', '', '', '2', '0', '1');
INSERT INTO `categories` VALUES ('2', '1', null, 'Professionals', 'professionals', '2021-02-03 21:36:59', '', '', '3', '0', '1');
INSERT INTO `categories` VALUES ('3', '1', null, 'University seekers', 'university-seekers', '2021-02-03 21:37:41', '', '', '4', '0', '0');
INSERT INTO `categories` VALUES ('4', '1', null, 'Under graduates', 'under-graduates', '2021-02-03 21:37:47', '', '', '5', '0', '0');
INSERT INTO `categories` VALUES ('5', '1', null, 'Post graduate', 'post-graduate', '2021-02-03 21:37:51', '', '', '6', '0', '0');
INSERT INTO `categories` VALUES ('6', '1', null, 'Grade 1 to A/L', 'grade-1-to-al', '2021-05-05 23:25:32', '', '', '1', '1', '1');
INSERT INTO `categories` VALUES ('7', '1', '6', 'Grade 1 - 5 (Scholarship)', 'grade-1-to-AL-grade-1---5-scholarship', '2021-02-20 15:19:34', '', '', '1', '1', '1');
INSERT INTO `categories` VALUES ('8', '1', '6', 'Grade 6 - 11 (O/L)', 'grade-1-to-AL-grade-6---11-ol', '2021-02-20 15:17:30', '', '', '2', '1', '1');
INSERT INTO `categories` VALUES ('9', '1', '6', 'Grade 12 - 13 (A/L)', 'grade-1-to-AL-grade-12---13-al', '2021-02-20 15:18:35', '', '', '3', '1', '1');
INSERT INTO `categories` VALUES ('10', '1', '7', 'Grade 3', 'grade-1-5-grade-3', '2021-02-20 15:02:23', '', '', '1', '0', '1');
INSERT INTO `categories` VALUES ('11', '1', '7', 'Grade 4', 'grade-1-5-grade-4', '2021-02-20 15:17:19', '', '', '2', '0', '1');
INSERT INTO `categories` VALUES ('12', '1', '7', 'Grade 5', 'grade-1-5-grade-5', '2021-02-20 15:17:24', '', '', '3', '0', '1');
INSERT INTO `categories` VALUES ('13', '1', '8', 'Grade 6', 'grade-1-to-AL-grade-6---11-ol-grade-6', '2021-02-20 15:18:17', '', '', '1', '0', '1');
INSERT INTO `categories` VALUES ('14', '1', '8', 'Grade 7', 'grade-1-to-AL-grade-6---11-ol-grade-7', '2021-02-20 15:18:20', '', '', '2', '0', '1');
INSERT INTO `categories` VALUES ('15', '1', '8', 'Grade 8', 'grade-1-to-AL-grade-6---11-ol-grade-8', '2021-02-20 15:18:23', '', '', '3', '0', '1');
INSERT INTO `categories` VALUES ('16', '1', '8', 'Grade 9', 'grade-1-to-AL-grade-6---11-ol-grade-9', '2021-02-20 15:18:26', '', '', '4', '0', '1');
INSERT INTO `categories` VALUES ('17', '1', '8', 'Grade 10', 'grade-1-to-AL-grade-6---11-ol-grade-10', '2021-02-20 15:18:30', '', '', '5', '0', '1');
INSERT INTO `categories` VALUES ('18', '1', '8', 'Grade 11', 'grade-1-to-AL-grade-6---11-ol-grade-11', '2021-02-20 15:18:33', '', '', '6', '0', '1');
INSERT INTO `categories` VALUES ('19', '1', '9', 'Grade 12', 'grade-1-to-AL-grade-12---13-al-grade-12', '2021-02-20 15:18:39', '', '', '1', '0', '1');
INSERT INTO `categories` VALUES ('20', '1', '9', 'Grade 13', 'grade-1-to-AL-grade-12---13-al-grade-13', '2021-02-20 15:18:45', '', '', '2', '0', '1');
INSERT INTO `categories` VALUES ('21', '1', null, 'Language classes', 'language-classes', '2021-02-03 21:36:59', '', '', '7', '1', '1');
INSERT INTO `categories` VALUES ('22', '1', '21', 'English', 'language-classes-english', '2021-02-20 15:18:54', '', '', '1', '1', '1');
INSERT INTO `categories` VALUES ('23', '1', '21', 'Sinhala', 'language-classes-sinhala', '2021-02-20 15:18:56', '', '', '2', '0', '1');
INSERT INTO `categories` VALUES ('24', '1', '21', 'Tamil', 'language-classes-tamil', '2021-02-20 15:18:59', '', '', '3', '0', '1');
INSERT INTO `categories` VALUES ('25', '1', '21', 'Chinese', 'language-classes-chinese', '2021-02-20 15:19:02', '', '', '4', '0', '1');
INSERT INTO `categories` VALUES ('26', '1', '21', 'French', 'language-classes-french', '2021-02-20 15:19:07', '', '', '5', '0', '1');
INSERT INTO `categories` VALUES ('27', '1', '21', 'Spanish', 'language-classes-spanish', '2021-02-20 15:19:11', '', '', '6', '0', '1');
INSERT INTO `categories` VALUES ('28', '1', '21', 'Hindi', 'language-classes-hindi', '2021-02-20 15:19:15', '', '', '7', '0', '1');
INSERT INTO `categories` VALUES ('29', '1', '21', 'German', 'language-classes-german', '2021-02-20 15:19:19', '', '', '8', '0', '1');
INSERT INTO `categories` VALUES ('30', '1', '21', 'Korean', 'language-classes-korean', '2021-02-20 15:19:23', '', '', '9', '0', '1');
INSERT INTO `categories` VALUES ('31', '1', '21', 'Japanese', 'language-classes-japanese', '2021-02-20 15:19:25', '', '', '10', '0', '1');
INSERT INTO `categories` VALUES ('32', '1', '7', 'Grade 1', 'grade-1-to-AL-grade-1---5-scholarship-grade-1', '2021-05-06 00:22:55', '', '', '-1', '1', '1');
INSERT INTO `categories` VALUES ('33', '1', '7', 'Grade 2', 'grade-1-5-grade-2', '2021-02-20 15:02:19', '', '', '0', '1', '1');
INSERT INTO `categories` VALUES ('35', '1', '32', 'Maths', 'grade-1-5-grade-1-maths', '2021-02-21 23:55:58', '', '', '1', '0', '0');
INSERT INTO `categories` VALUES ('36', '1', '32', 'Sinhala', 'grade-1-5-grade-1-sinhala', '2021-02-21 23:56:10', '', '', '2', '0', '0');

-- ----------------------------
-- Table structure for cities
-- ----------------------------
DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `district_id` int(11) DEFAULT 25,
  `city` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `city_district_fk` (`district_id`),
  CONSTRAINT `city_district_fk` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=421 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cities
-- ----------------------------
INSERT INTO `cities` VALUES ('1', '1', 'Colombo 1 : Colombo Fort (Kotuwa)');
INSERT INTO `cities` VALUES ('2', '1', 'Colombo 2 : Slave island (Kompannavidiya)');
INSERT INTO `cities` VALUES ('3', '1', 'Colombo 3 : Kollupitiya');
INSERT INTO `cities` VALUES ('4', '1', 'Colombo 4 : Bambalapitiya');
INSERT INTO `cities` VALUES ('5', '1', 'Colombo 5 : Narahenpita, Havelock Town , Kirulapona North');
INSERT INTO `cities` VALUES ('6', '1', 'Colombo 6 : Wellawatta, Pamankada, Kirulapona South');
INSERT INTO `cities` VALUES ('7', '1', 'Colombo 7 : Kurunduwatta');
INSERT INTO `cities` VALUES ('8', '1', 'Colombo 8 : Borella');
INSERT INTO `cities` VALUES ('9', '1', 'Colombo 9 : Dematagoda');
INSERT INTO `cities` VALUES ('10', '1', 'Colombo 10 : Maradana');
INSERT INTO `cities` VALUES ('11', '1', 'Colombo 11: Pettah (Pitakotuwa)');
INSERT INTO `cities` VALUES ('12', '1', 'Colombo 12:Aluthkade');
INSERT INTO `cities` VALUES ('13', '1', 'Colombo 13:Kotahena, Kochchikade');
INSERT INTO `cities` VALUES ('14', '1', 'Colombo 14:Grandpass');
INSERT INTO `cities` VALUES ('15', '1', 'Colombo 15:Mattakkuliya, Modara, Mutwal, Madampitiya');
INSERT INTO `cities` VALUES ('16', '1', 'Dehiwala');
INSERT INTO `cities` VALUES ('17', '1', 'Homagama');
INSERT INTO `cities` VALUES ('18', '1', 'Kaduwela');
INSERT INTO `cities` VALUES ('19', '1', 'Kesbewa');
INSERT INTO `cities` VALUES ('20', '1', 'Kolonnawa');
INSERT INTO `cities` VALUES ('21', '1', 'Kotte');
INSERT INTO `cities` VALUES ('22', '1', 'Maharagama');
INSERT INTO `cities` VALUES ('23', '1', 'Moratuwa');
INSERT INTO `cities` VALUES ('24', '1', 'Padukka');
INSERT INTO `cities` VALUES ('25', '1', 'Ratmalana');
INSERT INTO `cities` VALUES ('26', '1', 'Seethawaka');
INSERT INTO `cities` VALUES ('27', '1', 'Thimbirigasyaya');
INSERT INTO `cities` VALUES ('28', '1', 'Nugegoda');
INSERT INTO `cities` VALUES ('29', '1', 'Piliyandala');
INSERT INTO `cities` VALUES ('30', '1', 'Athurugiriya');
INSERT INTO `cities` VALUES ('31', '1', 'Kottawa ');
INSERT INTO `cities` VALUES ('32', '1', 'Malabe');
INSERT INTO `cities` VALUES ('33', '1', 'Battaramulla');
INSERT INTO `cities` VALUES ('34', '1', 'Rajagiriya');
INSERT INTO `cities` VALUES ('35', '1', 'Boralesgamuwa');
INSERT INTO `cities` VALUES ('36', '1', 'Thalawathugoda');
INSERT INTO `cities` VALUES ('37', '1', 'Pannipitiya');
INSERT INTO `cities` VALUES ('38', '1', 'Kohuwala');
INSERT INTO `cities` VALUES ('39', '1', 'Mount Lavinia');
INSERT INTO `cities` VALUES ('40', '1', 'Kotte');
INSERT INTO `cities` VALUES ('41', '1', 'Wellampitiya');
INSERT INTO `cities` VALUES ('42', '1', 'Angoda');
INSERT INTO `cities` VALUES ('43', '1', 'Nawala');
INSERT INTO `cities` VALUES ('44', '1', 'Padukka');
INSERT INTO `cities` VALUES ('45', '1', 'Avissawella');
INSERT INTO `cities` VALUES ('46', '1', 'Hanwella');
INSERT INTO `cities` VALUES ('47', '2', 'Attanagalla');
INSERT INTO `cities` VALUES ('48', '2', 'Biyagama');
INSERT INTO `cities` VALUES ('49', '2', 'Divulapitiya');
INSERT INTO `cities` VALUES ('50', '2', 'Dompe');
INSERT INTO `cities` VALUES ('51', '2', 'Gampaha');
INSERT INTO `cities` VALUES ('52', '2', 'Ja-Ela');
INSERT INTO `cities` VALUES ('53', '2', 'Katana');
INSERT INTO `cities` VALUES ('54', '2', 'Kelaniya');
INSERT INTO `cities` VALUES ('55', '2', 'Mahara');
INSERT INTO `cities` VALUES ('56', '2', 'Minuwangoda');
INSERT INTO `cities` VALUES ('57', '2', 'Mirigama');
INSERT INTO `cities` VALUES ('58', '2', 'Negombo');
INSERT INTO `cities` VALUES ('59', '2', 'Wattala');
INSERT INTO `cities` VALUES ('60', '2', 'Kiribathgoda');
INSERT INTO `cities` VALUES ('61', '2', 'Kadawatha');
INSERT INTO `cities` VALUES ('62', '2', 'Delgoda');
INSERT INTO `cities` VALUES ('63', '2', 'Dompe');
INSERT INTO `cities` VALUES ('64', '2', 'Kirindiwela');
INSERT INTO `cities` VALUES ('65', '2', 'Nittambuwa');
INSERT INTO `cities` VALUES ('66', '2', 'Veyangoda');
INSERT INTO `cities` VALUES ('67', '2', 'Kandana');
INSERT INTO `cities` VALUES ('68', '2', 'Ragama');
INSERT INTO `cities` VALUES ('69', '2', 'Katunayake');
INSERT INTO `cities` VALUES ('70', '2', 'Ganemulla');
INSERT INTO `cities` VALUES ('71', '2', 'Peliyagoda');
INSERT INTO `cities` VALUES ('72', '2', 'Yakkala');
INSERT INTO `cities` VALUES ('73', '3', 'Agalawatta');
INSERT INTO `cities` VALUES ('74', '3', 'Bandaragama');
INSERT INTO `cities` VALUES ('75', '3', 'Beruwala');
INSERT INTO `cities` VALUES ('76', '3', 'Bulathsinhala');
INSERT INTO `cities` VALUES ('77', '3', 'Dodangoda');
INSERT INTO `cities` VALUES ('78', '3', 'Horana');
INSERT INTO `cities` VALUES ('79', '3', 'Ingiriya');
INSERT INTO `cities` VALUES ('80', '3', 'Kalutara');
INSERT INTO `cities` VALUES ('81', '3', 'Madurawela');
INSERT INTO `cities` VALUES ('82', '3', 'Mathugama');
INSERT INTO `cities` VALUES ('83', '3', 'Millaniya');
INSERT INTO `cities` VALUES ('84', '3', 'Palindanuwara');
INSERT INTO `cities` VALUES ('85', '3', 'Panadura');
INSERT INTO `cities` VALUES ('86', '3', 'Walallavita');
INSERT INTO `cities` VALUES ('87', '3', 'Wadduwa');
INSERT INTO `cities` VALUES ('88', '3', 'Aluthgama');
INSERT INTO `cities` VALUES ('89', '4', 'Akurana');
INSERT INTO `cities` VALUES ('90', '4', 'Delthota');
INSERT INTO `cities` VALUES ('91', '4', 'Doluwa');
INSERT INTO `cities` VALUES ('92', '4', 'Ganga Ihala Korale');
INSERT INTO `cities` VALUES ('93', '4', 'Harispattuwa');
INSERT INTO `cities` VALUES ('94', '4', 'Hatharaliyadda');
INSERT INTO `cities` VALUES ('95', '4', 'Kandy');
INSERT INTO `cities` VALUES ('96', '4', 'Kundasale');
INSERT INTO `cities` VALUES ('97', '4', 'Medadumbara');
INSERT INTO `cities` VALUES ('98', '4', 'Minipe');
INSERT INTO `cities` VALUES ('99', '4', 'Panvila');
INSERT INTO `cities` VALUES ('100', '4', 'Pasbage Korale');
INSERT INTO `cities` VALUES ('101', '4', 'Pathadumbara');
INSERT INTO `cities` VALUES ('102', '4', 'Pathahewaheta');
INSERT INTO `cities` VALUES ('103', '4', 'Poojapitiya');
INSERT INTO `cities` VALUES ('104', '4', 'Thumpane');
INSERT INTO `cities` VALUES ('105', '4', 'Udadumbara');
INSERT INTO `cities` VALUES ('106', '4', 'Udapalatha');
INSERT INTO `cities` VALUES ('107', '4', 'Udunuwara');
INSERT INTO `cities` VALUES ('108', '4', 'Yatinuwara');
INSERT INTO `cities` VALUES ('109', '4', 'Katugastota');
INSERT INTO `cities` VALUES ('110', '4', 'Gampola');
INSERT INTO `cities` VALUES ('111', '4', 'Peradeniya');
INSERT INTO `cities` VALUES ('112', '4', 'Pilimathalawa');
INSERT INTO `cities` VALUES ('113', '4', 'Digana');
INSERT INTO `cities` VALUES ('114', '4', 'Gelioya');
INSERT INTO `cities` VALUES ('115', '4', 'Nawalapitiya');
INSERT INTO `cities` VALUES ('116', '4', 'Galagedara');
INSERT INTO `cities` VALUES ('117', '4', 'Kadugannawa');
INSERT INTO `cities` VALUES ('118', '4', 'Wattegama');
INSERT INTO `cities` VALUES ('119', '4', 'Ampitiya');
INSERT INTO `cities` VALUES ('120', '4', 'Madawala Bazaar');
INSERT INTO `cities` VALUES ('121', '5', 'Ambanganga Korale');
INSERT INTO `cities` VALUES ('122', '5', 'Dambulla');
INSERT INTO `cities` VALUES ('123', '5', 'Galewela');
INSERT INTO `cities` VALUES ('124', '5', 'Laggala-Pallegama');
INSERT INTO `cities` VALUES ('125', '5', 'Matale');
INSERT INTO `cities` VALUES ('126', '5', 'Naula');
INSERT INTO `cities` VALUES ('127', '5', 'Pallepola');
INSERT INTO `cities` VALUES ('128', '5', 'Rattota');
INSERT INTO `cities` VALUES ('129', '5', 'Ukuwela');
INSERT INTO `cities` VALUES ('130', '5', 'Wilgamuwa');
INSERT INTO `cities` VALUES ('131', '5', 'Yatawatta');
INSERT INTO `cities` VALUES ('132', '5', 'Sigiriya');
INSERT INTO `cities` VALUES ('133', '5', 'Palapathwela');
INSERT INTO `cities` VALUES ('134', '6', 'Ambagamuwa');
INSERT INTO `cities` VALUES ('135', '6', 'Hanguranketha');
INSERT INTO `cities` VALUES ('136', '6', 'Kothmale');
INSERT INTO `cities` VALUES ('137', '6', 'Nuwara Eliya');
INSERT INTO `cities` VALUES ('138', '6', 'Walapane');
INSERT INTO `cities` VALUES ('139', '6', 'Hatton');
INSERT INTO `cities` VALUES ('140', '6', 'Madulla');
INSERT INTO `cities` VALUES ('141', '6', 'Ginigathhena');
INSERT INTO `cities` VALUES ('142', '7', 'Alawwa');
INSERT INTO `cities` VALUES ('143', '7', 'Ambanpola');
INSERT INTO `cities` VALUES ('144', '7', 'Bamunakotuwa');
INSERT INTO `cities` VALUES ('145', '7', 'Bingiriya');
INSERT INTO `cities` VALUES ('146', '7', 'Ehetuwewa');
INSERT INTO `cities` VALUES ('147', '7', 'Galgamuwa');
INSERT INTO `cities` VALUES ('148', '7', 'Ganewatta');
INSERT INTO `cities` VALUES ('149', '7', 'Giribawa');
INSERT INTO `cities` VALUES ('150', '7', 'Ibbagamuwa');
INSERT INTO `cities` VALUES ('151', '7', 'Katupotha');
INSERT INTO `cities` VALUES ('152', '7', 'Kobeigane');
INSERT INTO `cities` VALUES ('153', '7', 'Kotavehera');
INSERT INTO `cities` VALUES ('154', '7', 'Kuliyapitiya East');
INSERT INTO `cities` VALUES ('155', '7', 'Kuliyapitiya West');
INSERT INTO `cities` VALUES ('156', '7', 'Kurunegala');
INSERT INTO `cities` VALUES ('157', '7', 'Mahawa');
INSERT INTO `cities` VALUES ('158', '7', 'Mallawapitiya');
INSERT INTO `cities` VALUES ('159', '7', 'Maspotha');
INSERT INTO `cities` VALUES ('160', '7', 'Mawathagama');
INSERT INTO `cities` VALUES ('161', '7', 'Narammala');
INSERT INTO `cities` VALUES ('162', '7', 'Nikaweratiya');
INSERT INTO `cities` VALUES ('163', '7', 'Panduwasnuwara');
INSERT INTO `cities` VALUES ('164', '7', 'Pannala');
INSERT INTO `cities` VALUES ('165', '7', 'Polgahawela');
INSERT INTO `cities` VALUES ('166', '7', 'Polpithigama');
INSERT INTO `cities` VALUES ('167', '7', 'Rasnayakapura');
INSERT INTO `cities` VALUES ('168', '7', 'Rideegama');
INSERT INTO `cities` VALUES ('169', '7', 'Udubaddawa');
INSERT INTO `cities` VALUES ('170', '7', 'Wariyapola');
INSERT INTO `cities` VALUES ('171', '7', 'Weerambugedara');
INSERT INTO `cities` VALUES ('172', '7', 'Hettipola');
INSERT INTO `cities` VALUES ('173', '7', 'Giriulla');
INSERT INTO `cities` VALUES ('174', '8', 'Anamaduwa');
INSERT INTO `cities` VALUES ('175', '8', 'Arachchikattuwa');
INSERT INTO `cities` VALUES ('176', '8', 'Chilaw');
INSERT INTO `cities` VALUES ('177', '8', 'Dankotuwa');
INSERT INTO `cities` VALUES ('178', '8', 'Kalpitiya');
INSERT INTO `cities` VALUES ('179', '8', 'Karuwalagaswewa');
INSERT INTO `cities` VALUES ('180', '8', 'Madampe');
INSERT INTO `cities` VALUES ('181', '8', 'Mahakumbukkadawala');
INSERT INTO `cities` VALUES ('182', '8', 'Mahawewa');
INSERT INTO `cities` VALUES ('183', '8', 'Mundalama');
INSERT INTO `cities` VALUES ('184', '8', 'Nattandiya');
INSERT INTO `cities` VALUES ('185', '8', 'Nawagattegama');
INSERT INTO `cities` VALUES ('186', '8', 'Pallama');
INSERT INTO `cities` VALUES ('187', '8', 'Puttalam');
INSERT INTO `cities` VALUES ('188', '8', 'Vanathavilluwa');
INSERT INTO `cities` VALUES ('189', '8', 'Wennappuwa');
INSERT INTO `cities` VALUES ('190', '8', 'Marawila');
INSERT INTO `cities` VALUES ('191', '9', 'Akmeemana');
INSERT INTO `cities` VALUES ('192', '9', 'Ambalangoda');
INSERT INTO `cities` VALUES ('193', '9', 'Baddegama');
INSERT INTO `cities` VALUES ('194', '9', 'Balapitiya');
INSERT INTO `cities` VALUES ('195', '9', 'Benthota');
INSERT INTO `cities` VALUES ('196', '9', 'Bope-Poddala');
INSERT INTO `cities` VALUES ('197', '9', 'Elpitiya');
INSERT INTO `cities` VALUES ('198', '9', 'Galle');
INSERT INTO `cities` VALUES ('199', '9', 'Gonapinuwala');
INSERT INTO `cities` VALUES ('200', '9', 'Habaraduwa');
INSERT INTO `cities` VALUES ('201', '9', 'Hikkaduwa');
INSERT INTO `cities` VALUES ('202', '9', 'Imaduwa');
INSERT INTO `cities` VALUES ('203', '9', 'Karandeniya');
INSERT INTO `cities` VALUES ('204', '9', 'Nagoda');
INSERT INTO `cities` VALUES ('205', '9', 'Neluwa');
INSERT INTO `cities` VALUES ('206', '9', 'Niyagama');
INSERT INTO `cities` VALUES ('207', '9', 'Thawalama');
INSERT INTO `cities` VALUES ('208', '9', 'Welivitiya-Divithura');
INSERT INTO `cities` VALUES ('209', '9', 'Yakkalamulla');
INSERT INTO `cities` VALUES ('210', '9', 'Karapitiya');
INSERT INTO `cities` VALUES ('211', '9', 'Ahangama');
INSERT INTO `cities` VALUES ('212', '9', 'Batapola');
INSERT INTO `cities` VALUES ('213', '10', 'Ambalantota');
INSERT INTO `cities` VALUES ('214', '10', 'Angunakolapelessa');
INSERT INTO `cities` VALUES ('215', '10', 'Beliatta');
INSERT INTO `cities` VALUES ('216', '10', 'Hambantota');
INSERT INTO `cities` VALUES ('217', '10', 'Katuwana');
INSERT INTO `cities` VALUES ('218', '10', 'Lunugamvehera');
INSERT INTO `cities` VALUES ('219', '10', 'Okewela');
INSERT INTO `cities` VALUES ('220', '10', 'Sooriyawewa');
INSERT INTO `cities` VALUES ('221', '10', 'Tangalle');
INSERT INTO `cities` VALUES ('222', '10', 'Thissamaharama');
INSERT INTO `cities` VALUES ('223', '10', 'Walasmulla');
INSERT INTO `cities` VALUES ('224', '10', 'Weeraketiya');
INSERT INTO `cities` VALUES ('225', '11', 'Akuressa');
INSERT INTO `cities` VALUES ('226', '11', 'Athuraliya');
INSERT INTO `cities` VALUES ('227', '11', 'Devinuwara');
INSERT INTO `cities` VALUES ('228', '11', 'Dickwella');
INSERT INTO `cities` VALUES ('229', '11', 'Hakmana');
INSERT INTO `cities` VALUES ('230', '11', 'Kamburupitiya');
INSERT INTO `cities` VALUES ('231', '11', 'Kirinda Puhulwella');
INSERT INTO `cities` VALUES ('232', '11', 'Kotapola');
INSERT INTO `cities` VALUES ('233', '11', 'Malimbada');
INSERT INTO `cities` VALUES ('234', '11', 'Matara');
INSERT INTO `cities` VALUES ('235', '11', 'Mulatiyana');
INSERT INTO `cities` VALUES ('236', '11', 'Pasgoda');
INSERT INTO `cities` VALUES ('237', '11', 'Pitabeddara');
INSERT INTO `cities` VALUES ('238', '11', 'Thihagoda');
INSERT INTO `cities` VALUES ('239', '11', 'Weligama');
INSERT INTO `cities` VALUES ('240', '11', 'Welipitiya');
INSERT INTO `cities` VALUES ('241', '11', 'Deniyaya');
INSERT INTO `cities` VALUES ('242', '11', 'Kamburugamuwa');
INSERT INTO `cities` VALUES ('243', '11', 'Kakannadurra');
INSERT INTO `cities` VALUES ('244', '12', 'Ayagama');
INSERT INTO `cities` VALUES ('245', '12', 'Balangoda');
INSERT INTO `cities` VALUES ('246', '12', 'Eheliyagoda');
INSERT INTO `cities` VALUES ('247', '12', 'Elapattha');
INSERT INTO `cities` VALUES ('248', '12', 'Embilipitiya');
INSERT INTO `cities` VALUES ('249', '12', 'Godakawela');
INSERT INTO `cities` VALUES ('250', '12', 'Imbulpe');
INSERT INTO `cities` VALUES ('251', '12', 'Kahawatta');
INSERT INTO `cities` VALUES ('252', '12', 'Kalawana');
INSERT INTO `cities` VALUES ('253', '12', 'Kiriella');
INSERT INTO `cities` VALUES ('254', '12', 'Kolonna');
INSERT INTO `cities` VALUES ('255', '12', 'Kuruvita');
INSERT INTO `cities` VALUES ('256', '12', 'Nivithigala');
INSERT INTO `cities` VALUES ('257', '12', 'Opanayaka');
INSERT INTO `cities` VALUES ('258', '12', 'Pelmadulla');
INSERT INTO `cities` VALUES ('259', '12', 'Ratnapura');
INSERT INTO `cities` VALUES ('260', '12', 'Weligepola');
INSERT INTO `cities` VALUES ('261', '13', 'Aranayaka');
INSERT INTO `cities` VALUES ('262', '13', 'Bulathkohupitiya');
INSERT INTO `cities` VALUES ('263', '13', 'Dehiowita');
INSERT INTO `cities` VALUES ('264', '13', 'Deraniyagala');
INSERT INTO `cities` VALUES ('265', '13', 'Galigamuwa');
INSERT INTO `cities` VALUES ('266', '13', 'Kegalle');
INSERT INTO `cities` VALUES ('267', '13', 'Mawanella');
INSERT INTO `cities` VALUES ('268', '13', 'Rambukkana');
INSERT INTO `cities` VALUES ('269', '13', 'Ruwanwella');
INSERT INTO `cities` VALUES ('270', '13', 'Warakapola');
INSERT INTO `cities` VALUES ('271', '13', 'Yatiyanthota');
INSERT INTO `cities` VALUES ('272', '13', 'Kitulgala');
INSERT INTO `cities` VALUES ('273', '14', 'Galnewa');
INSERT INTO `cities` VALUES ('274', '14', 'Galenbindunuwewa');
INSERT INTO `cities` VALUES ('275', '14', 'Horowpothana');
INSERT INTO `cities` VALUES ('276', '14', 'Ipalogama');
INSERT INTO `cities` VALUES ('277', '14', 'Kahatagasdigiliya');
INSERT INTO `cities` VALUES ('278', '14', 'Kebithigollewa');
INSERT INTO `cities` VALUES ('279', '14', 'Kekirawa');
INSERT INTO `cities` VALUES ('280', '14', 'Mahavilachchiya');
INSERT INTO `cities` VALUES ('281', '14', 'Medawachchiya');
INSERT INTO `cities` VALUES ('282', '14', 'Mihinthale');
INSERT INTO `cities` VALUES ('283', '14', 'Nachchadoowa');
INSERT INTO `cities` VALUES ('284', '14', 'Nochchiyagama');
INSERT INTO `cities` VALUES ('285', '14', 'Nuwaragam Palatha Central');
INSERT INTO `cities` VALUES ('286', '14', 'Nuwaragam Palatha East');
INSERT INTO `cities` VALUES ('287', '14', 'Padaviya');
INSERT INTO `cities` VALUES ('288', '14', 'Palagala');
INSERT INTO `cities` VALUES ('289', '14', 'Palugaswewa');
INSERT INTO `cities` VALUES ('290', '14', 'Rajanganaya');
INSERT INTO `cities` VALUES ('291', '14', 'Rambewa');
INSERT INTO `cities` VALUES ('292', '14', 'Thalawa');
INSERT INTO `cities` VALUES ('293', '14', 'Thambuttegama');
INSERT INTO `cities` VALUES ('294', '14', 'Thirappane');
INSERT INTO `cities` VALUES ('295', '14', 'Anuradhapura');
INSERT INTO `cities` VALUES ('296', '14', 'Eppawala');
INSERT INTO `cities` VALUES ('297', '14', 'Habarana');
INSERT INTO `cities` VALUES ('298', '15', 'Dimbulagala');
INSERT INTO `cities` VALUES ('299', '15', 'Elahera');
INSERT INTO `cities` VALUES ('300', '15', 'Hingurakgoda');
INSERT INTO `cities` VALUES ('301', '15', 'Lankapura');
INSERT INTO `cities` VALUES ('302', '15', 'Medirigiriya');
INSERT INTO `cities` VALUES ('303', '15', 'Thamankaduwa');
INSERT INTO `cities` VALUES ('304', '15', 'Welikanda');
INSERT INTO `cities` VALUES ('305', '15', 'Polonnaruwa');
INSERT INTO `cities` VALUES ('306', '16', 'Addalachchenai');
INSERT INTO `cities` VALUES ('307', '16', 'Akkaraipattu');
INSERT INTO `cities` VALUES ('308', '16', 'Alayadiwembu');
INSERT INTO `cities` VALUES ('309', '16', 'Ampara');
INSERT INTO `cities` VALUES ('310', '16', 'Damana');
INSERT INTO `cities` VALUES ('311', '16', 'Dehiattakandiya');
INSERT INTO `cities` VALUES ('312', '16', 'Eragama');
INSERT INTO `cities` VALUES ('313', '16', 'Kalmunai ');
INSERT INTO `cities` VALUES ('314', '16', 'Karaitivu');
INSERT INTO `cities` VALUES ('315', '16', 'Lahugala');
INSERT INTO `cities` VALUES ('316', '16', 'Mahaoya');
INSERT INTO `cities` VALUES ('317', '16', 'Navithanveli');
INSERT INTO `cities` VALUES ('318', '16', 'Ninthavur');
INSERT INTO `cities` VALUES ('319', '16', 'Padiyathalawa');
INSERT INTO `cities` VALUES ('320', '16', 'Pothuvil');
INSERT INTO `cities` VALUES ('321', '16', 'Sainthamarathu');
INSERT INTO `cities` VALUES ('322', '16', 'Samanthurai');
INSERT INTO `cities` VALUES ('323', '16', 'Thirukkovil');
INSERT INTO `cities` VALUES ('324', '16', 'Uhana');
INSERT INTO `cities` VALUES ('325', '17', 'Eravur Pattu');
INSERT INTO `cities` VALUES ('326', '17', 'Eravur Town');
INSERT INTO `cities` VALUES ('327', '17', 'Kattankudy');
INSERT INTO `cities` VALUES ('328', '17', 'Koralai Pattu');
INSERT INTO `cities` VALUES ('329', '17', 'Manmunai ');
INSERT INTO `cities` VALUES ('330', '17', 'Porativu Pattu');
INSERT INTO `cities` VALUES ('331', '17', 'Batticaloa');
INSERT INTO `cities` VALUES ('332', '18', 'Gomarankadawala');
INSERT INTO `cities` VALUES ('333', '18', 'Kantalai');
INSERT INTO `cities` VALUES ('334', '18', 'Kinniya');
INSERT INTO `cities` VALUES ('335', '18', 'Kuchchaveli');
INSERT INTO `cities` VALUES ('336', '18', 'Morawewa');
INSERT INTO `cities` VALUES ('337', '18', 'Muttur');
INSERT INTO `cities` VALUES ('338', '18', 'Padavi Sri Pura');
INSERT INTO `cities` VALUES ('339', '18', 'Seruvila');
INSERT INTO `cities` VALUES ('340', '18', 'Thambalagamuwa');
INSERT INTO `cities` VALUES ('341', '18', 'Trincomalee');
INSERT INTO `cities` VALUES ('342', '18', 'Verugal');
INSERT INTO `cities` VALUES ('343', '18', 'Trincomalee');
INSERT INTO `cities` VALUES ('344', '19', 'Badulla');
INSERT INTO `cities` VALUES ('345', '19', 'Bandarawela');
INSERT INTO `cities` VALUES ('346', '19', 'Ella');
INSERT INTO `cities` VALUES ('347', '19', 'Haldummulla');
INSERT INTO `cities` VALUES ('348', '19', 'Hali-Ela');
INSERT INTO `cities` VALUES ('349', '19', 'Haputale');
INSERT INTO `cities` VALUES ('350', '19', 'Kandaketiya');
INSERT INTO `cities` VALUES ('351', '19', 'Lunugala');
INSERT INTO `cities` VALUES ('352', '19', 'Mahiyanganaya');
INSERT INTO `cities` VALUES ('353', '19', 'Meegahakivula');
INSERT INTO `cities` VALUES ('354', '19', 'Passara');
INSERT INTO `cities` VALUES ('355', '19', 'Rideemaliyadda');
INSERT INTO `cities` VALUES ('356', '19', 'Soranathota');
INSERT INTO `cities` VALUES ('357', '19', 'Uva-Paranagama');
INSERT INTO `cities` VALUES ('358', '19', 'Welimada');
INSERT INTO `cities` VALUES ('359', '19', 'Diyathalawa');
INSERT INTO `cities` VALUES ('360', '20', 'Badalkumbura');
INSERT INTO `cities` VALUES ('361', '20', 'Bibile');
INSERT INTO `cities` VALUES ('362', '20', 'Buttala');
INSERT INTO `cities` VALUES ('363', '20', 'Katharagama');
INSERT INTO `cities` VALUES ('364', '20', 'Madulla');
INSERT INTO `cities` VALUES ('365', '20', 'Medagama');
INSERT INTO `cities` VALUES ('366', '20', 'Moneragala');
INSERT INTO `cities` VALUES ('367', '20', 'Sevanagala');
INSERT INTO `cities` VALUES ('368', '20', 'Siyambalanduwa');
INSERT INTO `cities` VALUES ('369', '20', 'Thanamalvila');
INSERT INTO `cities` VALUES ('370', '20', 'Wellawaya');
INSERT INTO `cities` VALUES ('371', '21', 'Delft');
INSERT INTO `cities` VALUES ('372', '21', 'Karainagar');
INSERT INTO `cities` VALUES ('373', '21', 'Nallur');
INSERT INTO `cities` VALUES ('374', '21', 'Thenmaradchi');
INSERT INTO `cities` VALUES ('375', '21', 'Vadamaradchi ');
INSERT INTO `cities` VALUES ('376', '21', 'Jaffna');
INSERT INTO `cities` VALUES ('377', '21', 'Valikamam ');
INSERT INTO `cities` VALUES ('378', '22', 'Kandavalai');
INSERT INTO `cities` VALUES ('379', '22', 'Karachchi');
INSERT INTO `cities` VALUES ('380', '22', 'Pachchilaipalli');
INSERT INTO `cities` VALUES ('381', '22', 'Poonakary');
INSERT INTO `cities` VALUES ('382', '23', 'Madhu');
INSERT INTO `cities` VALUES ('383', '23', 'Mannar');
INSERT INTO `cities` VALUES ('384', '23', 'Manthai');
INSERT INTO `cities` VALUES ('385', '23', 'Musalai');
INSERT INTO `cities` VALUES ('386', '23', 'Nanaddan');
INSERT INTO `cities` VALUES ('387', '24', 'Manthai ');
INSERT INTO `cities` VALUES ('388', '24', 'Maritimepattu');
INSERT INTO `cities` VALUES ('389', '24', 'Oddusuddan');
INSERT INTO `cities` VALUES ('390', '24', 'Puthukudiyiruppu');
INSERT INTO `cities` VALUES ('391', '24', 'Thunukkai');
INSERT INTO `cities` VALUES ('392', '24', 'Welioya');
INSERT INTO `cities` VALUES ('393', '24', 'Mullaitivu');
INSERT INTO `cities` VALUES ('394', '25', 'Vavuniya');
INSERT INTO `cities` VALUES ('395', '25', 'Vengalacheddikulam');
INSERT INTO `cities` VALUES ('396', '1', 'Other');
INSERT INTO `cities` VALUES ('397', '2', 'Other');
INSERT INTO `cities` VALUES ('398', '3', 'Other');
INSERT INTO `cities` VALUES ('399', '4', 'Other');
INSERT INTO `cities` VALUES ('400', '5', 'Other');
INSERT INTO `cities` VALUES ('401', '6', 'Other');
INSERT INTO `cities` VALUES ('402', '7', 'Other');
INSERT INTO `cities` VALUES ('403', '8', 'Other');
INSERT INTO `cities` VALUES ('404', '9', 'Other');
INSERT INTO `cities` VALUES ('405', '10', 'Other');
INSERT INTO `cities` VALUES ('406', '11', 'Other');
INSERT INTO `cities` VALUES ('407', '12', 'Other');
INSERT INTO `cities` VALUES ('408', '13', 'Other');
INSERT INTO `cities` VALUES ('409', '14', 'Other');
INSERT INTO `cities` VALUES ('410', '15', 'Other');
INSERT INTO `cities` VALUES ('411', '16', 'Other');
INSERT INTO `cities` VALUES ('412', '17', 'Other');
INSERT INTO `cities` VALUES ('413', '18', 'Other');
INSERT INTO `cities` VALUES ('414', '19', 'Other');
INSERT INTO `cities` VALUES ('415', '20', 'Other');
INSERT INTO `cities` VALUES ('416', '21', 'Other');
INSERT INTO `cities` VALUES ('417', '22', 'Other');
INSERT INTO `cities` VALUES ('418', '23', 'Other');
INSERT INTO `cities` VALUES ('419', '24', 'Other');
INSERT INTO `cities` VALUES ('420', '25', 'Other');

-- ----------------------------
-- Table structure for countries
-- ----------------------------
DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of countries
-- ----------------------------
INSERT INTO `countries` VALUES ('1', 'Sri Lanka');

-- ----------------------------
-- Table structure for custom_fields
-- ----------------------------
DROP TABLE IF EXISTS `custom_fields`;
CREATE TABLE `custom_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `post_type_id` int(11) DEFAULT NULL,
  `mandatory` tinyint(1) DEFAULT 0,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `position` tinyint(2) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `placeholder` varchar(255) DEFAULT NULL,
  `appear_on` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_post_type_fk` (`post_type_id`),
  CONSTRAINT `custom_post_type_fk` FOREIGN KEY (`post_type_id`) REFERENCES `post_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of custom_fields
-- ----------------------------
INSERT INTO `custom_fields` VALUES ('1', 'Assign instructor', 'assign-instructor', '2', null, '1', '1', '2021-02-18 22:40:46', '2', '1', 'Assign an instructor to this lesson', null, 'posts');
INSERT INTO `custom_fields` VALUES ('2', 'Lesson fee (Rs.)', 'lesson-fee', '1', null, '1', '0', '2021-02-18 22:40:55', '2', '2', null, 'Example: 2500', 'posts');
INSERT INTO `custom_fields` VALUES ('3', 'Lesson fee (Rs.)', 'lesson-fee', '1', null, '1', '1', '2021-02-18 22:47:07', '2', '2', null, 'Example: 2500', 'user-edit');

-- ----------------------------
-- Table structure for districts
-- ----------------------------
DROP TABLE IF EXISTS `districts`;
CREATE TABLE `districts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `district_state_fk` (`state_id`),
  CONSTRAINT `district_state_fk` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of districts
-- ----------------------------
INSERT INTO `districts` VALUES ('1', '1', 'Colombo');
INSERT INTO `districts` VALUES ('2', '1', 'Gampaha');
INSERT INTO `districts` VALUES ('3', '1', 'Kaluthara');
INSERT INTO `districts` VALUES ('4', '2', 'Kandy');
INSERT INTO `districts` VALUES ('5', '2', 'Matale');
INSERT INTO `districts` VALUES ('6', '2', 'NuwaraEliya');
INSERT INTO `districts` VALUES ('7', '3', 'Kurunegala');
INSERT INTO `districts` VALUES ('8', '3', 'Puttalam');
INSERT INTO `districts` VALUES ('9', '4', 'Galle');
INSERT INTO `districts` VALUES ('10', '4', 'Hambantota');
INSERT INTO `districts` VALUES ('11', '4', 'Matara');
INSERT INTO `districts` VALUES ('12', '5', 'Ratnapura');
INSERT INTO `districts` VALUES ('13', '5', 'Kegalle');
INSERT INTO `districts` VALUES ('14', '6', 'Anuradhapura');
INSERT INTO `districts` VALUES ('15', '6', 'Polonnaruwa');
INSERT INTO `districts` VALUES ('16', '7', 'Ampara');
INSERT INTO `districts` VALUES ('17', '7', 'Batticaloa');
INSERT INTO `districts` VALUES ('18', '7', 'Trincomalee');
INSERT INTO `districts` VALUES ('19', '8', 'Badulla');
INSERT INTO `districts` VALUES ('20', '8', 'Moneragala');
INSERT INTO `districts` VALUES ('21', '9', 'Jaffna');
INSERT INTO `districts` VALUES ('22', '9', 'Kilinochchi');
INSERT INTO `districts` VALUES ('23', '9', 'Mannar');
INSERT INTO `districts` VALUES ('24', '9', 'Mullaitivu');
INSERT INTO `districts` VALUES ('25', '9', 'Vavuniya');

-- ----------------------------
-- Table structure for enrollments
-- ----------------------------
DROP TABLE IF EXISTS `enrollments`;
CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enrollment_mode` tinyint(4) DEFAULT NULL,
  `post_type_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `assignee_id` int(11) DEFAULT NULL,
  `fee` float(255,0) DEFAULT NULL,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `approval` tinyint(1) DEFAULT 0,
  `enrolled_date` varchar(255) DEFAULT '',
  `reference` varchar(50) DEFAULT '',
  `slip_ref` varchar(25) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user_enrollment_fk` (`user_id`),
  KEY `assignee_fk` (`assignee_id`),
  KEY `enrollment_post_fk` (`item_id`),
  KEY `approval_types_fk` (`approval`),
  KEY `enrollment_mode_fk` (`enrollment_mode`),
  CONSTRAINT `approval_types_fk` FOREIGN KEY (`approval`) REFERENCES `approvals` (`id`),
  CONSTRAINT `assign_class_enroll_fk` FOREIGN KEY (`item_id`) REFERENCES `assigned_categories` (`id`),
  CONSTRAINT `assignee_fk` FOREIGN KEY (`assignee_id`) REFERENCES `useres` (`id`),
  CONSTRAINT `enrollment_mode_fk` FOREIGN KEY (`enrollment_mode`) REFERENCES `enrollment_modes` (`id`),
  CONSTRAINT `user_enrollment_fk` FOREIGN KEY (`user_id`) REFERENCES `useres` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of enrollments
-- ----------------------------

-- ----------------------------
-- Table structure for enrollment_modes
-- ----------------------------
DROP TABLE IF EXISTS `enrollment_modes`;
CREATE TABLE `enrollment_modes` (
  `id` tinyint(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of enrollment_modes
-- ----------------------------
INSERT INTO `enrollment_modes` VALUES ('1', 'category-wise');
INSERT INTO `enrollment_modes` VALUES ('2', 'post-wise');

-- ----------------------------
-- Table structure for languages
-- ----------------------------
DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `local` varchar(2) DEFAULT NULL,
  `language` varchar(20) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of languages
-- ----------------------------
INSERT INTO `languages` VALUES ('1', 'en', 'english');
INSERT INTO `languages` VALUES ('2', 'si', 'sinhalese');
INSERT INTO `languages` VALUES ('3', 'ta', 'tamil');

-- ----------------------------
-- Table structure for lang_section
-- ----------------------------
DROP TABLE IF EXISTS `lang_section`;
CREATE TABLE `lang_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_section` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of lang_section
-- ----------------------------
INSERT INTO `lang_section` VALUES ('1', 'home');

-- ----------------------------
-- Table structure for localize
-- ----------------------------
DROP TABLE IF EXISTS `localize`;
CREATE TABLE `localize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `local_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `en` varchar(255) DEFAULT NULL,
  `si` varchar(255) CHARACTER SET utf8mb4 DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `lang_fk` (`local_id`),
  KEY `section_fk` (`section_id`),
  CONSTRAINT `lang_fk` FOREIGN KEY (`local_id`) REFERENCES `languages` (`id`),
  CONSTRAINT `section_fk` FOREIGN KEY (`section_id`) REFERENCES `lang_section` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of localize
-- ----------------------------
INSERT INTO `localize` VALUES ('1', '1', '1', 'login', 'login', 'ඇතුල් වන්න');
INSERT INTO `localize` VALUES ('2', '1', '1', 'register', 'register', 'ලියාපදිංචි වන්න');

-- ----------------------------
-- Table structure for payment_types
-- ----------------------------
DROP TABLE IF EXISTS `payment_types`;
CREATE TABLE `payment_types` (
  `id` tinyint(1) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `available` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of payment_types
-- ----------------------------
INSERT INTO `payment_types` VALUES ('1', 'Bank Transfer', 'bank-transfer', '1');
INSERT INTO `payment_types` VALUES ('2', 'Credit / Debit Card Payment', 'credit-debit-card-payment', '0');

-- ----------------------------
-- Table structure for posts
-- ----------------------------
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_type_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  `desc` varchar(255) DEFAULT '',
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `thumbnail` varchar(255) DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `start` varchar(255) DEFAULT '',
  `end` varchar(255) DEFAULT '',
  `user_id` int(11) NOT NULL,
  `assignee_id` int(11) DEFAULT NULL,
  `fee` int(11) DEFAULT NULL,
  `assigned_date` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `post_cat_fk` (`category_id`),
  KEY `post_type_fk` (`post_type_id`),
  KEY `post_user_fk` (`user_id`),
  KEY `post_assignee_fk` (`assignee_id`),
  CONSTRAINT `post_assignee_fk` FOREIGN KEY (`assignee_id`) REFERENCES `useres` (`id`),
  CONSTRAINT `post_cat_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `post_type_fk` FOREIGN KEY (`post_type_id`) REFERENCES `post_types` (`id`),
  CONSTRAINT `post_user_fk` FOREIGN KEY (`user_id`) REFERENCES `useres` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of posts
-- ----------------------------

-- ----------------------------
-- Table structure for post_types
-- ----------------------------
DROP TABLE IF EXISTS `post_types`;
CREATE TABLE `post_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `slug` varchar(255) DEFAULT NULL,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `post_type` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of post_types
-- ----------------------------
INSERT INTO `post_types` VALUES ('1', 'Education types', 'education-types', '2021-01-09 22:28:16');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` int(11) DEFAULT NULL,
  `label` varchar(18) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `roleId` (`roleId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', '1023', 'student');
INSERT INTO `roles` VALUES ('2', '1024', 'instructor');
INSERT INTO `roles` VALUES ('3', '1025', 'editor');
INSERT INTO `roles` VALUES ('4', '1026', 'admin');

-- ----------------------------
-- Table structure for states
-- ----------------------------
DROP TABLE IF EXISTS `states`;
CREATE TABLE `states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `state_country_fk` (`country_id`),
  CONSTRAINT `state_country_fk` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of states
-- ----------------------------
INSERT INTO `states` VALUES ('1', '1', 'Western 		');
INSERT INTO `states` VALUES ('2', '1', 'Central');
INSERT INTO `states` VALUES ('3', '1', 'North Western');
INSERT INTO `states` VALUES ('4', '1', 'Southern Province');
INSERT INTO `states` VALUES ('5', '1', 'Sabaragamuwa');
INSERT INTO `states` VALUES ('6', '1', 'North Central	');
INSERT INTO `states` VALUES ('7', '1', 'Eastern');
INSERT INTO `states` VALUES ('8', '1', 'Uva');
INSERT INTO `states` VALUES ('9', '1', 'Nothern ');

-- ----------------------------
-- Table structure for transaction_state
-- ----------------------------
DROP TABLE IF EXISTS `transaction_state`;
CREATE TABLE `transaction_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of transaction_state
-- ----------------------------
INSERT INTO `transaction_state` VALUES ('1', 'approved');
INSERT INTO `transaction_state` VALUES ('2', 'rejected');
INSERT INTO `transaction_state` VALUES ('3', 'deleted');
INSERT INTO `transaction_state` VALUES ('4', 'pending');

-- ----------------------------
-- Table structure for transctions
-- ----------------------------
DROP TABLE IF EXISTS `transctions`;
CREATE TABLE `transctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) DEFAULT NULL,
  `to` int(11) DEFAULT NULL,
  `enrollment_id` int(11) DEFAULT NULL,
  `transaction_time` varchar(255) DEFAULT NULL,
  `amount` float(255,0) DEFAULT NULL,
  `payment_types` tinyint(1) DEFAULT NULL,
  `slip_id` int(11) DEFAULT NULL,
  `transaction_state` int(11) DEFAULT 0,
  `item` varchar(255) DEFAULT '',
  `item_slug` varchar(255) DEFAULT '',
  `conductor` varchar(255) DEFAULT NULL,
  `payer_name` varchar(255) DEFAULT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_types` (`payment_types`),
  KEY `transaction_from_fk` (`from`),
  KEY `transction_to_fk` (`to`),
  KEY `enrollment_fk` (`enrollment_id`),
  KEY `transaction_slip_fk` (`slip_id`),
  KEY `transaction_state_fk` (`transaction_state`),
  CONSTRAINT `payment_type_fk` FOREIGN KEY (`payment_types`) REFERENCES `payment_types` (`id`),
  CONSTRAINT `transaction_from_fk` FOREIGN KEY (`from`) REFERENCES `useres` (`id`),
  CONSTRAINT `transaction_slip_fk` FOREIGN KEY (`slip_id`) REFERENCES `bank_slips` (`id`),
  CONSTRAINT `transaction_state_fk` FOREIGN KEY (`transaction_state`) REFERENCES `transaction_state` (`id`),
  CONSTRAINT `transction_to_fk` FOREIGN KEY (`to`) REFERENCES `useres` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of transctions
-- ----------------------------

-- ----------------------------
-- Table structure for useres
-- ----------------------------
DROP TABLE IF EXISTS `useres`;
CREATE TABLE `useres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) DEFAULT '',
  `firstname` varchar(25) DEFAULT '',
  `lastname` varchar(25) DEFAULT '',
  `email` varchar(255) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password` varchar(255) DEFAULT NULL,
  `is_email_verified` tinyint(1) DEFAULT 0,
  `email_reset_expiration` date DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `roleId` int(11) DEFAULT 1023,
  PRIMARY KEY (`id`),
  KEY `roleId` (`roleId`),
  CONSTRAINT `user_role_fk` FOREIGN KEY (`roleId`) REFERENCES `roles` (`roleId`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of useres
-- ----------------------------

-- ----------------------------
-- Table structure for users_location
-- ----------------------------
DROP TABLE IF EXISTS `users_location`;
CREATE TABLE `users_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `latitude` float(255,0) DEFAULT NULL,
  `longtitude` float(255,0) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_location_fk` (`user_id`),
  KEY `country_fk` (`country_id`),
  KEY `state_fk` (`state_id`),
  KEY `district_fk` (`district_id`),
  KEY `city_dk` (`city_id`),
  CONSTRAINT `city_dk` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `country_fk` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `district_fk` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`),
  CONSTRAINT `state_fk` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`),
  CONSTRAINT `user_location_fk` FOREIGN KEY (`user_id`) REFERENCES `useres` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users_location
-- ----------------------------

-- ----------------------------
-- Table structure for users_meta
-- ----------------------------
DROP TABLE IF EXISTS `users_meta`;
CREATE TABLE `users_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_gender` tinyint(1) DEFAULT NULL,
  `user_dob` varchar(255) DEFAULT NULL,
  `edu_post_type_id` int(11) DEFAULT NULL,
  `edu_cat_id` int(11) DEFAULT NULL,
  `edu_cat2_id` int(11) DEFAULT NULL,
  `edu_cat3_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_meta_fk` (`user_id`),
  CONSTRAINT `user_meta_fk` FOREIGN KEY (`user_id`) REFERENCES `useres` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users_meta
-- ----------------------------

-- ----------------------------
-- Table structure for users_phone
-- ----------------------------
DROP TABLE IF EXISTS `users_phone`;
CREATE TABLE `users_phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_phone` varchar(255) DEFAULT NULL,
  `is_parent` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_phone_fk` (`user_id`),
  CONSTRAINT `user_phone_fk` FOREIGN KEY (`user_id`) REFERENCES `useres` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users_phone
-- ----------------------------

-- ----------------------------
-- Table structure for videos
-- ----------------------------
DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `src` varchar(255) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `given_attempts` tinyint(1) DEFAULT -1,
  PRIMARY KEY (`id`),
  KEY `post_videos_fk` (`post_id`),
  CONSTRAINT `post_videos_fk` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of videos
-- ----------------------------

-- ----------------------------
-- Table structure for video_attempts
-- ----------------------------
DROP TABLE IF EXISTS `video_attempts`;
CREATE TABLE `video_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `video_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `attempts` tinyint(1) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `video_attempt_fk` (`video_id`),
  KEY `post_video_attempt_fk` (`post_id`),
  KEY `user_video_attempt_fk` (`student_id`),
  CONSTRAINT `post_video_attempt_fk` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  CONSTRAINT `user_video_attempt_fk` FOREIGN KEY (`student_id`) REFERENCES `useres` (`id`),
  CONSTRAINT `video_attempt_fk` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of video_attempts
-- ----------------------------
