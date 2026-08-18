/*
SQLyog Ultimate v12.4.1 (64 bit)
MySQL - 8.3.0 : Database - mediserve
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`mediserve` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

insert  into `cache`(`key`,`value`,`expiration`) values 
('mediserve-cache-customer-otp-request:8896326943|127.0.0.1','i:1;',1786895489),
('mediserve-cache-customer-otp-request:8896326943|127.0.0.1:timer','i:1786895489;',1786895489),
('mediserve-cache-customer-otp-request:8896326943|192.168.31.155','i:1;',1786895332),
('mediserve-cache-customer-otp-request:8896326943|192.168.31.155:timer','i:1786895332;',1786895332),
('mediserve-cache-set_config.SITELOGO','s:20:\"assets/logo/logo.png\";',2102252335);

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_batches` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `locations` */

DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=729 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `locations` */

insert  into `locations`(`id`,`name`,`parent_id`,`status`) values 
(1,'INDIA',0,'1'),
(2,'ANDAMAN AND NICOBAR ISLANDS',1,'1'),
(3,'ANDHRA PRADESH',1,'1'),
(4,'ARUNACHAL PRADESH',1,'1'),
(5,'ASSAM',1,'1'),
(6,'BIHAR',1,'1'),
(7,'CHANDIGARH',1,'1'),
(8,'CHHATTISGARH',1,'1'),
(9,'DADRA AND NAGAR HAVELI',1,'1'),
(10,'DAMAN AND DIU',1,'1'),
(11,'DELHI',1,'1'),
(12,'GOA',1,'1'),
(13,'GUJARAT',1,'1'),
(14,'HARYANA',1,'1'),
(15,'HIMACHAL PRADESH',1,'1'),
(16,'JAMMU AND KASHMIR',1,'1'),
(17,'JHARKHAND',1,'1'),
(18,'KARNATAKA',1,'1'),
(19,'KERALA',1,'1'),
(20,'LAKSHADWEEP',1,'1'),
(21,'MADHYA PRADESH',1,'1'),
(22,'MAHARASHTRA',1,'1'),
(23,'MANIPUR',1,'1'),
(24,'MEGHALAYA',1,'1'),
(25,'MIZORAM',1,'1'),
(26,'NAGALAND',1,'1'),
(27,'ORISSA',1,'1'),
(28,'PUDUCHERRY',1,'1'),
(29,'PUNJAB',1,'1'),
(30,'RAJASTHAN',1,'1'),
(31,'SIKKIM',1,'1'),
(32,'TAMIL NADU',1,'1'),
(33,'TRIPURA',1,'1'),
(34,'UTTAR PRADESH',1,'1'),
(35,'UTTARAKHAND',1,'1'),
(36,'WEST BENGAL',1,'1'),
(37,'JORHAT',5,'1'),
(39,'NIZAMGARH',37,'1'),
(40,'CIVIL LINES',37,'1'),
(41,'LUCKNOW',34,'1'),
(42,'INDIRANAGAR',41,'1'),
(43,'ALIGARH',34,'1'),
(44,'AGRA',34,'1'),
(45,'PATNA',6,'1'),
(46,'DEOGHAR',17,'1'),
(47,'MUZAFFARPUR',6,'1'),
(48,'MORADABAD',34,'1'),
(49,'KARIMGANJ',5,'1'),
(50,'SHAHARANPUR',34,'1'),
(51,'DARBHANGA',6,'1'),
(52,'JAMSHEDPUR',17,'1'),
(53,'BAREILLY',34,'1'),
(54,'BASTI',34,'1'),
(55,'ETAH',34,'1'),
(56,'ETAWA',34,'1'),
(57,'BHAGALPUR',6,'1'),
(58,'VARANASI',34,'1'),
(59,'ALLAHABAD',34,'1'),
(60,'FAIZABAD',34,'1'),
(61,'GONDA',34,'1'),
(62,'GAZIPUR',34,'1'),
(63,'ARARIA',6,'1'),
(64,'ARRAH',6,'1'),
(65,'BAGAHA',6,'1'),
(66,'BEGUSARAI',6,'1'),
(67,'BETTIAH',6,'1'),
(68,'BUXAR',6,'1'),
(69,'CHAPRA',6,'1'),
(70,'DEHRIONSONE',6,'1'),
(71,'DINAPURNIZAMAT',6,'1'),
(72,'GAYA',6,'1'),
(73,'GOPALGANJ',6,'1'),
(74,'HAJIPUR',6,'1'),
(75,'JAMALPUR',6,'1'),
(76,'JAMUI',6,'1'),
(77,'JEHANABAD',6,'1'),
(78,'KATIHAR',6,'1'),
(79,'KISHANGANJ',6,'1'),
(80,'LAKHISARAI',6,'1'),
(81,'MADHUBANI',6,'1'),
(82,'MOKAMA',6,'1'),
(83,'MOTIHARI',6,'1'),
(84,'MUNGER',6,'1'),
(85,'NAWADA',6,'1'),
(86,'PHULWARISHARIF',6,'1'),
(87,'PURNIA',6,'1'),
(88,'SAHARSA',6,'1'),
(89,'SAMASTIPUR',6,'1'),
(90,'SASARAM',6,'1'),
(91,'SITAMARHI',6,'1'),
(92,'SUPAUL',6,'1'),
(93,'NEW DELHI',11,'1'),
(94,'GOLMURI',52,'1'),
(95,'KUSHINAGER',34,'1'),
(98,'CHARBAGH',41,'1'),
(99,'KANPUR',34,'1'),
(100,'KOKRAJHAR',5,'1'),
(101,'DIPHU',5,'1'),
(102,'LAKHIMPUR',5,'1'),
(104,'GUNTUR',3,'1'),
(105,'ABU ROAD',30,'1'),
(106,'BHOPAL',21,'1'),
(107,'AMBEDKAR NAGAR',34,'1'),
(108,'BARABANKI',34,'1'),
(109,'GORAKHPUR',34,'1'),
(110,'DEORIA',34,'1'),
(112,'CHANDAULI',34,'1'),
(113,'MAU',34,'1'),
(114,'ALMORA',35,'1'),
(115,'BAGESHWAR',35,'1'),
(116,'CHAMOLI',35,'1'),
(117,'CHAMPAWAT',35,'1'),
(118,'DEHRADUN',35,'1'),
(119,'HARIDWAR',35,'1'),
(120,'NAINITAL',35,'1'),
(121,'PAURI GARHWAL',35,'1'),
(122,'PITHORAGARH',35,'1'),
(123,'RUDRAPRAYAG',35,'1'),
(124,'TEHRI GARHWAL',35,'1'),
(125,'UDHAM SINGH NAGAR',35,'1'),
(126,'UTTARKASHI',35,'1'),
(127,'CAR NICOBAR',2,'1'),
(128,'GREAT NICOBAR ISLAND',2,'1'),
(129,'NANCOWRY',2,'1'),
(130,'ANANTAPUR',3,'1'),
(131,'CHITTOOR',3,'1'),
(132,'EAST GODAVARI',3,'1'),
(133,'KADAPA',3,'1'),
(134,'KRISHNA',3,'1'),
(135,'KURNOOL',3,'1'),
(136,'NELLORE',3,'1'),
(137,'PRAKASAM',3,'1'),
(138,'SRIKAKULAM',3,'1'),
(139,'VISAKHAPATNAM',3,'1'),
(140,'VIZIANAGARAM',3,'1'),
(141,'WEST GODAVARI',3,'1'),
(142,'HAWAI',4,'1'),
(143,'CHANGLANG',4,'1'),
(144,'SEPPA',4,'1'),
(145,'PASIGHAT',4,'1'),
(146,'TEZU',4,'1'),
(147,'LONGDING',4,'1'),
(148,'ZIRO',4,'1'),
(149,'YUPIA',4,'1'),
(150,'TAWANG TOWN',4,'1'),
(151,'KHONSA',4,'1'),
(152,'ROING',4,'1'),
(153,'YINGKIONG',4,'1'),
(154,'DAPORIJO',4,'1'),
(155,'BOMDILA',4,'1'),
(156,'ALONG',4,'1'),
(157,'ANINI',4,'1'),
(158,'KOLORIANG',4,'1'),
(159,'BARPETA',5,'1'),
(160,'BONGAIGAON',5,'1'),
(161,'SILCHAR',5,'1'),
(162,'MANGALDAI',5,'1'),
(163,'DHEMAJI',5,'1'),
(164,'DHUBRI',5,'1'),
(165,'DIBRUGARH',5,'1'),
(166,'GOALPARA',5,'1'),
(167,'GOLAGHAT',5,'1'),
(168,'HAILAKANDI',5,'1'),
(169,'MORIGAON',5,'1'),
(170,'NAGAON',5,'1'),
(171,'NALBARI',5,'1'),
(172,'HAFLONG',5,'1'),
(173,'SIVASAGAR',5,'1'),
(174,'TEZPUR',5,'1'),
(175,'TINSUKIA',5,'1'),
(176,'AMINGAON',5,'1'),
(177,'GUWAHATI',5,'1'),
(178,'MUSHALPUR',5,'1'),
(179,'UDALGURI',5,'1'),
(180,'KALAIGAON',5,'1'),
(181,'BISHWANATH CHARIALI',5,'1'),
(182,'HOJAI',5,'1'),
(183,'SONARI',5,'1'),
(184,'HATSINGIMARI',5,'1'),
(185,'BANKA',6,'1'),
(186,'BALOD',8,'1'),
(187,'BALODA BAZAR',8,'1'),
(188,'BALRAMPUR',8,'1'),
(189,'JAGDALPUR',8,'1'),
(190,'BEMETARA',8,'1'),
(191,'BIJAPUR',8,'1'),
(192,'BILASPUR',8,'1'),
(193,'DANTEWADA',8,'1'),
(194,'DHAMTARI',8,'1'),
(195,'DURG',8,'1'),
(196,'GARIABAND',8,'1'),
(197,'NAILA JANJGIR',8,'1'),
(198,'JASHPUR',8,'1'),
(199,'KAWARDHA',8,'1'),
(200,'KANKER',8,'1'),
(201,'KONDAGAON',8,'1'),
(202,'KORBA',8,'1'),
(203,'BAIKUNTHPUR',8,'1'),
(204,'MAHASAMUND',8,'1'),
(205,'MUNGELI',8,'1'),
(206,'NARAYANPUR',8,'1'),
(207,'RAIGARH',8,'1'),
(208,'RAIPUR',8,'1'),
(209,'RAJNANDGAON',8,'1'),
(210,'SUKMA',8,'1'),
(211,'SURAJPUR',8,'1'),
(212,'AMBIKAPUR',8,'1'),
(213,'MAPUSA',12,'1'),
(214,'PANAJI',12,'1'),
(215,'MARGOA',12,'1'),
(216,'PONDA',12,'1'),
(217,'VASCO',12,'1'),
(218,'AHMEDABAD',13,'1'),
(219,'AMRELI',13,'1'),
(220,'ANAND',13,'1'),
(221,'MODASA',13,'1'),
(222,'PALANPUR',13,'1'),
(223,'BHARUCH',13,'1'),
(224,'BHAVNAGAR',13,'1'),
(225,'BOTAD',13,'1'),
(226,'CHHOTA UDAIPUR',13,'1'),
(227,'DAHOD',13,'1'),
(228,'AHWA',13,'1'),
(229,'KHAMBHALIA',13,'1'),
(230,'GANDHINAGAR',13,'1'),
(231,'VERAVAL',13,'1'),
(232,'JAMNAGAR',13,'1'),
(233,'JUNAGADH',13,'1'),
(234,'BHUJ',13,'1'),
(235,'NADIAD',13,'1'),
(236,'LUNAVADA',13,'1'),
(237,'MEHSANA',13,'1'),
(238,'MORBI',13,'1'),
(239,'RAJPIPLA',13,'1'),
(240,'NAVSARI',13,'1'),
(241,'GODHRA',13,'1'),
(242,'PATAN',13,'1'),
(243,'PORBANDAR',13,'1'),
(244,'RAJKOT',13,'1'),
(245,'HIMMATNAGAR',13,'1'),
(246,'SURAT',13,'1'),
(247,'SURENDRANAGAR',13,'1'),
(248,'TAPI',13,'1'),
(249,'VADODARA',13,'1'),
(250,'VALSAD',13,'1'),
(251,'AMBALA',14,'1'),
(252,'BHIWANI',14,'1'),
(253,'FARIDABAD',14,'1'),
(254,'FATEHABAD',14,'1'),
(255,'GURGAON',14,'1'),
(256,'HISAR',14,'1'),
(257,'JHAJJAR',14,'1'),
(258,'JIND',14,'1'),
(259,'KAITHAL',14,'1'),
(260,'KARNAL',14,'1'),
(261,'KURUKSHETRA',14,'1'),
(262,'MAHENDRAGARH',14,'1'),
(263,'NUH',14,'1'),
(264,'PALWAL',14,'1'),
(265,'PANCHKULA',14,'1'),
(266,'PANIPAT',14,'1'),
(267,'REWARI',14,'1'),
(268,'ROHTAK',14,'1'),
(269,'SIRSA',14,'1'),
(270,'SONIPAT',14,'1'),
(271,'YAMUNANAGAR',14,'1'),
(272,'BILASPUR',15,'1'),
(273,'CHAMBA',15,'1'),
(274,'HAMIRPUR',15,'1'),
(275,'KANGRA',15,'1'),
(276,'KINNAUR',15,'1'),
(277,'KULLU',15,'1'),
(278,'LAHAUL AND SPITI',15,'1'),
(279,'MANDI',15,'1'),
(280,'SHIMLA',15,'1'),
(281,'SIRMAUR',15,'1'),
(282,'SOLAN',15,'1'),
(283,'UNA',15,'1'),
(284,'JAMMU',16,'1'),
(285,'DODA',16,'1'),
(286,'KISHTWAR',16,'1'),
(287,'RAJOURI',16,'1'),
(288,'REASI',16,'1'),
(289,'UDHAMPUR',16,'1'),
(290,'RAMBAN',16,'1'),
(291,'KATHUA',16,'1'),
(292,'SAMBA',16,'1'),
(293,'POONCH',16,'1'),
(294,'SRINAGAR',16,'1'),
(295,'ANANTNAG',16,'1'),
(296,'TELANGANA',1,'1'),
(297,'KULGAM',16,'1'),
(298,'PULWAMA',16,'1'),
(299,'SHOPIAN',16,'1'),
(300,'BUDGAM',16,'1'),
(301,'GANDERBAL',16,'1'),
(302,'BANDIPORA',16,'1'),
(303,'BARAMULLA',16,'1'),
(304,'KUPWARA',16,'1'),
(305,'KARGIL',16,'1'),
(306,'LEH',16,'1'),
(307,'GARHWA',17,'1'),
(308,'GIRIDIH',17,'1'),
(309,'SIMDEGA',17,'1'),
(310,'JAMTARA',17,'1'),
(311,'PALAMU',17,'1'),
(312,'LATEHAR',17,'1'),
(313,'CHATRA',17,'1'),
(314,'HAZARIBAGH',17,'1'),
(315,'KODERMA',17,'1'),
(316,'RAMGARH',17,'1'),
(317,'BOKARO',17,'1'),
(318,'DHANBAD',17,'1'),
(319,'LOHARDAGA',17,'1'),
(320,'GUMLA',17,'1'),
(321,'RANCHI',17,'1'),
(322,'KHUNTI',17,'1'),
(323,'WEST SINGHBHUM',17,'1'),
(324,'SARAIKELA KHARSAWAN',17,'1'),
(325,'EAST SINGHBHUM',17,'1'),
(326,'DUMKA',17,'1'),
(327,'PAKUR',17,'1'),
(328,'GODDA',17,'1'),
(329,'SAHEBGANJ',17,'1'),
(330,'BAGALKOT',18,'1'),
(331,'BENGALURU',18,'1'),
(332,'BELAGAVI',18,'1'),
(333,'BALLARI',18,'1'),
(334,'BIDAR',18,'1'),
(335,'VIJAYAPURA',18,'1'),
(336,'CHAMARAJANAGAR',18,'1'),
(337,'CHIKBALLAPUR',18,'1'),
(338,'CHIKKAMAGALURU',18,'1'),
(339,'CHITRADURGA',18,'1'),
(340,'MANGALURU',18,'1'),
(341,'DAVANAGERE',18,'1'),
(342,'DHARWAD',18,'1'),
(343,'GADAG',18,'1'),
(344,'KALABURAGI',18,'1'),
(345,'HASSAN',18,'1'),
(346,'HAVERI',18,'1'),
(347,'MADIKERI',18,'1'),
(348,'KOLAR',18,'1'),
(349,'KOPPAL',18,'1'),
(350,'MANDYA',17,'1'),
(351,'MYSURU',18,'1'),
(352,'RAICHUR',18,'1'),
(353,'RAMANAGARA',18,'1'),
(354,'SHIVAMOGGA',18,'1'),
(355,'TUMAKURU',18,'1'),
(356,'UDUPI',18,'1'),
(357,'KARWAR',18,'1'),
(358,'YADGIR',18,'1'),
(359,'ALAPPUZHA',19,'1'),
(360,'KAKKANAD',19,'1'),
(361,'PAINAVU',19,'1'),
(362,'KANNUR',19,'1'),
(363,'KASARAGOD',19,'1'),
(364,'KOLLAM',19,'1'),
(365,'KOTTAYAM',19,'1'),
(366,'KOZHIKODE',19,'1'),
(367,'MALAPPURAM',19,'1'),
(368,'PALAKKAD',19,'1'),
(369,'PATHANAMTHITTA',19,'1'),
(370,'THIRUVANANTHAPURAM',19,'1'),
(371,'THRISSUR',19,'1'),
(372,'KALPETTA',19,'1'),
(373,'AGARMALWA',21,'1'),
(374,'ALIRAJPUR',21,'1'),
(375,'ANUPPUR',21,'1'),
(376,'ASHOKNAGAR',21,'1'),
(377,'BALAGHAT',21,'1'),
(378,'BARWANI',21,'1'),
(379,'BETUL',21,'1'),
(380,'BHIND',21,'1'),
(381,'BURHANPUR',21,'1'),
(382,'CHHATARPUR',21,'1'),
(383,'CHHINDWARA',21,'1'),
(384,'DAMOH',21,'1'),
(385,'DATIA',21,'1'),
(386,'DEWAS',21,'1'),
(387,'DHAR',21,'1'),
(388,'DINDORI',21,'1'),
(389,'GUNA',21,'1'),
(390,'GWALIOR',21,'1'),
(391,'HARDA',21,'1'),
(392,'HOSHANGABAD',21,'1'),
(393,'INDORE',21,'1'),
(394,'JABALPUR',21,'1'),
(395,'JHABUA',21,'1'),
(396,'KATNI',21,'1'),
(397,'KHANDWA',21,'1'),
(398,'KHARGONE',21,'1'),
(399,'MANDLA',21,'1'),
(400,'MANDSAUR',21,'1'),
(401,'MORENA',21,'1'),
(402,'NARSINGHPUR',21,'1'),
(403,'NEEMUCH',21,'1'),
(404,'PANNA',21,'1'),
(405,'RAJGARH',21,'1'),
(406,'RATLAM',21,'1'),
(407,'REWA',21,'1'),
(408,'SAGAR',21,'1'),
(409,'SATNA',21,'1'),
(410,'SEHORE',21,'1'),
(411,'SEONI',21,'1'),
(412,'SINGRAULI',21,'1'),
(413,'SHAHDOL',21,'1'),
(414,'SHAJAPUR',21,'1'),
(415,'SHEOPUR',21,'1'),
(416,'SHIVPURI',21,'1'),
(417,'SIDHI',21,'1'),
(418,'TIKAMGARH',21,'1'),
(419,'UJJAIN',21,'1'),
(420,'UMARIA',21,'1'),
(421,'VIDISHA',21,'1'),
(422,'AHMEDNAGAR',22,'1'),
(423,'AKOLA',22,'1'),
(424,'AMRAVATI',22,'1'),
(425,'AURANGABAD',22,'1'),
(426,'BEED',22,'1'),
(427,'BHANDARA',22,'1'),
(428,'BULDHANA',22,'1'),
(429,'CHANDRAPUR',22,'1'),
(430,'DHULE',22,'1'),
(431,'GADCHIROLI',22,'1'),
(432,'GONDIA',22,'1'),
(433,'HINGOLI',22,'1'),
(434,'THANE',22,'1'),
(435,'JALNA',22,'1'),
(436,'KOLHAPUR',22,'1'),
(437,'LATUR',22,'1'),
(438,'MUMBAI',22,'1'),
(439,'BANDRA',22,'1'),
(440,'NAGPUR',22,'1'),
(441,'NANDED',22,'1'),
(442,'NANDURBAR',22,'1'),
(443,'NASHIK',22,'1'),
(444,'OSMANABAD',22,'1'),
(445,'PARBHANI',22,'1'),
(446,'PUNE',22,'1'),
(447,'ALIBAG',22,'1'),
(448,'RATNAGIRI',22,'1'),
(449,'SANGLI',22,'1'),
(450,'SATARA',22,'1'),
(451,'OROS',22,'1'),
(452,'SOLAPUR',22,'1'),
(453,'WARDHA',22,'1'),
(454,'WASHIM',22,'1'),
(455,'YAVATMAL',22,'1'),
(456,'PALGHAR',22,'1'),
(457,'BISHNUPUR',23,'1'),
(458,'CHURACHANDPUR',23,'1'),
(459,'CHANDEL',23,'1'),
(460,'POROMPAT',23,'1'),
(461,'SENAPATI',23,'1'),
(462,'TAMENGLONG',23,'1'),
(463,'THOUBAL',23,'1'),
(464,'UKHRUL',23,'1'),
(465,'LAMPHELPAT',23,'1'),
(466,'WILLIAMNAGAR',24,'1'),
(467,'SHILLONG',24,'1'),
(468,'JOWAI',24,'1'),
(469,'NONGPOH',24,'1'),
(470,'BAGHMARA',24,'1'),
(471,'TURA',24,'1'),
(472,'NONGSTOIN',24,'1'),
(473,'AIZAWL',25,'1'),
(474,'KOLASIB',25,'1'),
(475,'LUNGLEI',25,'1'),
(476,'LAWNGTLAI',25,'1'),
(477,'MAMIT',25,'1'),
(478,'SIAHA',25,'1'),
(479,'SERCHHIP',25,'1'),
(480,'CHAMPHAI',25,'1'),
(481,'DIMAPUR',26,'1'),
(482,'KIPHIRE',26,'1'),
(483,'KOHIMA',26,'1'),
(484,'LONGLENG',26,'1'),
(485,'MOKOKCHUNG',26,'1'),
(486,'MON',26,'1'),
(487,'PEREN',26,'1'),
(488,'PHEK',26,'1'),
(489,'TUENSANG',26,'1'),
(490,'WOKHA',26,'1'),
(491,'ZUNHEBOTO',26,'1'),
(492,'ANUGUL',27,'1'),
(493,'BOUDH',27,'1'),
(494,'BHADRAK',27,'1'),
(495,'BALANGIR',27,'1'),
(496,'BARGARH',27,'1'),
(497,'BALASORE',27,'1'),
(498,'CUTTACK',27,'1'),
(499,'DEBAGARH',27,'1'),
(500,'DHENKANAL',27,'1'),
(501,'CHHATRAPUR',27,'1'),
(502,'PARALAKHEMUNDI',27,'1'),
(503,'JHARSUGUDA',27,'1'),
(504,'JAJPUR',27,'1'),
(505,'JAGATSINGHAPUR',27,'1'),
(506,'KHORDHA',27,'1'),
(507,'KENDUJHAR',27,'1'),
(508,'BHAWANIPATNA',27,'1'),
(509,'PHULBANI',27,'1'),
(510,'KORAPUT',27,'1'),
(511,'KENDRAPARA',27,'1'),
(512,'MALKANGIRI',27,'1'),
(513,'BARIPADA',27,'1'),
(514,'NABARANGAPUR',27,'1'),
(515,'NUAPADA',27,'1'),
(516,'NAYAGARH',27,'1'),
(517,'PURI',27,'1'),
(518,'RAYAGADA',27,'1'),
(519,'SAMBALPUR',27,'1'),
(520,'SUBARNAPUR',27,'1'),
(521,'SUNDARGARH',27,'1'),
(522,'BHUBANESWAR',27,'1'),
(523,'KARAIKAL',28,'1'),
(524,'MAHE',28,'1'),
(525,'YANAM',28,'1'),
(526,'HOSHIARPUR',29,'1'),
(527,'JALANDHAR',29,'1'),
(528,'AMRITSAR',29,'1'),
(529,'BARNALA',29,'1'),
(530,'BATHINDA',29,'1'),
(531,'FARIDKOT',29,'1'),
(532,'FATEHGARH SAHIB',29,'1'),
(533,'FAZILKA',29,'1'),
(534,'FIROZPUR',29,'1'),
(535,'GURDASPUR',29,'1'),
(536,'LUDHIANA',29,'1'),
(537,'KAPURTHALA',29,'1'),
(538,'MANSA',29,'1'),
(539,'MOGA',29,'1'),
(540,'MOHALI',29,'1'),
(541,'ROPAR',29,'1'),
(542,'SRI MUKTSAR SAHIB',29,'1'),
(543,'NAWAN SHAHR',29,'1'),
(544,'SANGRUR',29,'1'),
(545,'PATIALA',29,'1'),
(546,'PATHANKOT',29,'1'),
(547,'TARN TARAN',29,'1'),
(548,'AJMER',30,'1'),
(549,'ALWAR',30,'1'),
(550,'BANSWARA',30,'1'),
(551,'BARAN',30,'1'),
(552,'BARMER',30,'1'),
(553,'BHARATPUR',30,'1'),
(554,'BHILWARA',30,'1'),
(555,'BIKANER',30,'1'),
(556,'BUNDI',30,'1'),
(557,'CHITTORGARH',30,'1'),
(558,'CHURU',30,'1'),
(559,'DAUSA',30,'1'),
(560,'DHOLPUR',30,'1'),
(561,'DUNGARPUR',30,'1'),
(562,'HANUMANGARH',30,'1'),
(563,'JAIPUR',30,'1'),
(564,'JAISALMER',30,'1'),
(565,'JALORE',30,'1'),
(566,'JHALAWAR',30,'1'),
(567,'JHUNJHUNU',30,'1'),
(568,'JODHPUR',30,'1'),
(569,'KARAULI',30,'1'),
(570,'KOTA',30,'1'),
(571,'NAGAUR',30,'1'),
(572,'PALI',30,'1'),
(573,'PRATAPGARH',30,'1'),
(574,'RAJSAMAND',30,'1'),
(575,'SAWAI MADHOPUR',30,'1'),
(576,'SIKAR',30,'1'),
(577,'SIROHI',30,'1'),
(578,'SRI GANGANAGAR',30,'1'),
(579,'TONK',30,'1'),
(580,'UDAIPUR',30,'1'),
(581,'GANGTOK',31,'1'),
(582,'MANGAN',31,'1'),
(583,'NAMCHI',31,'1'),
(584,'GEYZING',31,'1'),
(585,'ARIYALUR',32,'1'),
(586,'CHENNAI',32,'1'),
(587,'COIMBATORE',32,'1'),
(588,'CUDDALORE',32,'1'),
(589,'DHARMAPURI',32,'1'),
(590,'DINDIGUL',32,'1'),
(591,'ERODE',32,'1'),
(592,'KANCHIPURAM',32,'1'),
(593,'NAGERCOIL',32,'1'),
(594,'KARUR',32,'1'),
(595,'KRISHNAGIRI',32,'1'),
(596,'MADURAI',32,'1'),
(597,'NAGAPATTINAM',32,'1'),
(598,'NAMAKKAL',32,'1'),
(599,'OOTY',32,'1'),
(600,'PERAMBALUR',32,'1'),
(601,'PUDUKOTTAI',32,'1'),
(602,'RAMANATHAPURAM',32,'1'),
(603,'SALEM',32,'1'),
(604,'SIVAGANGA',32,'1'),
(605,'THANJAVUR',32,'1'),
(606,'THENI',32,'1'),
(607,'THOOTHUKUDI',32,'1'),
(608,'TIRUCHIRAPPALLI',32,'1'),
(609,'TIRUNELVELI',32,'1'),
(610,'TIRUPPUR',32,'1'),
(611,'TIRUVALLUR',32,'1'),
(612,'TIRUVANNAMALAI',32,'1'),
(613,'THIRUVARUR',32,'1'),
(614,'VELLORE',32,'1'),
(615,'VILUPPURAM',32,'1'),
(616,'VIRUDHUNAGAR',32,'1'),
(617,'AMBASSA',33,'1'),
(618,'BISHRAMGANJ',33,'1'),
(619,'KHOWAI',33,'1'),
(620,'UDAIPUR',33,'1'),
(621,'KAILASHAHAR',33,'1'),
(622,'DHARMANAGAR',33,'1'),
(623,'BELONIA',33,'1'),
(624,'AGARTALA',33,'1'),
(625,'ADILABAD',296,'1'),
(626,'HYDERABAD',296,'1'),
(627,'KARIMNAGAR',296,'1'),
(628,'KHAMMAM',296,'1'),
(629,'MAHBUBNAGAR',296,'1'),
(630,'SANGAREDDY',296,'1'),
(631,'NALGONDA',296,'1'),
(632,'NIZAMABAD',296,'1'),
(633,'VIKARABAD',296,'1'),
(634,'WARANGAL',296,'1'),
(635,'AZAMGARH',34,'1'),
(636,'MEERUT',34,'1'),
(637,'ALIPURDUAR',36,'1'),
(638,'BANKURA',36,'1'),
(639,'BARDHAMAN',36,'1'),
(640,'BIRBHUM',36,'1'),
(641,'COOCH BEHAR',36,'1'),
(642,'DARJEELING',36,'1'),
(643,'TAMLUK',36,'1'),
(644,'HOOGHLY',36,'1'),
(645,'HOWRAH',36,'1'),
(646,'JALPAIGURI',36,'1'),
(647,'KOLKATA',36,'1'),
(648,'MALDA',36,'1'),
(649,'BAHARAMPUR',36,'1'),
(650,'KRISHNANAGAR',36,'1'),
(651,'BARASAT',36,'1'),
(652,'RAIGANJ',36,'1'),
(653,'PURULIA',36,'1'),
(654,'ALIPORE',36,'1'),
(655,'BALURGHAT',36,'1'),
(656,'MEDINIPUR',36,'1'),
(657,'CHITRAKOOT',34,'1'),
(659,'RAMPUR',34,'1'),
(660,'JHANSI',34,'1'),
(661,'MIRZAPUR',34,'1'),
(663,'AURAIYA',34,'1'),
(664,'BADAUN',34,'1'),
(665,'BAHRAICH',34,'1'),
(666,'BIJNOR',34,'1'),
(667,'BALLIA',34,'1'),
(668,'BANDA',34,'1'),
(669,'BULANDSHAHR',34,'1'),
(670,'FIROZABAD',34,'1'),
(671,'FARRUKHABAD',34,'1'),
(672,'FATEHPUR',34,'1'),
(673,'NOIDA',34,'1'),
(674,'GHAZIABAD',34,'1'),
(675,'HAPUR',34,'1'),
(676,'HAMIRPUR',34,'1'),
(677,'HARDOI',34,'1'),
(678,'HATHRAS',34,'1'),
(679,'AMROHA',35,'1'),
(680,'JAUNPUR',34,'1'),
(681,'AKBARPUR',34,'1'),
(682,'KANNAUJ',34,'1'),
(683,'KASGANJ',34,'1'),
(684,'MANJHANPUR',34,'1'),
(685,'LALITPUR',34,'1'),
(686,'LAKHIMPUR KHERI',34,'1'),
(687,'MAHARAJGANJ',34,'1'),
(688,'MAHOBA',34,'1'),
(689,'MATHURA',34,'1'),
(690,'MUZAFFARNAGAR',34,'1'),
(691,'PILIBHIT',34,'1'),
(692,'RAE BARELI',34,'1'),
(693,'SITAPUR',34,'1'),
(694,'SHAHJAHANPUR',34,'1'),
(695,'SHAMLI',34,'1'),
(696,'SAMBHAL',34,'1'),
(697,'NAVGARH',34,'1'),
(698,'ROBERTSGANJ',34,'1'),
(699,'GYANPUR',34,'1'),
(700,'SULTANPUR',34,'1'),
(701,'SHRAVASTI',34,'1'),
(702,'UNNAO',34,'1'),
(703,'KHALILABAD',34,'1'),
(705,'CHANDIGARH',7,'1'),
(706,'DADRA AND NAGAR HAVELI',9,'1'),
(707,'DAMAN AND DIU',10,'1'),
(709,'RAURKELA',27,'1'),
(710,'BALRAMPUR',34,'1'),
(715,'MAINPURI',34,'1'),
(716,'SOMBHADRA',34,'1'),
(717,'SIWAN',6,'1'),
(718,'Amroha',34,'1'),
(719,'AYODHAYA',34,'1'),
(720,'ETAWAH',34,'1'),
(721,'PRAYAGRAJ',34,'1'),
(722,'BAGPAT',34,'1'),
(723,'BIJNAUR',34,'1'),
(724,'BUDAUN',34,'1'),
(725,'MAHRAJGANJ',34,'1'),
(726,'BHADOHI',34,'1'),
(727,'UDAIGANJ',34,'1'),
(728,'KALAGARH',35,'1');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_05_21_164430_create_locations_table',1),
(5,'2026_08_12_151922_add_login_id_to_users_table',1),
(6,'2026_08_12_161238_create_permission_tables',1),
(7,'2026_08_12_164258_add_registration_fields_to_users_table',1),
(8,'2026_08_12_164302_create_stores_table',1),
(9,'2026_08_12_165805_rename_distributer_role_to_captain',1),
(10,'2026_08_12_180000_create_set_config_table',1),
(11,'2026_08_15_051417_create_products_table',2),
(12,'2026_08_16_060000_relax_users_table_for_customer_signup',3),
(13,'2026_08_16_060100_create_prescriptions_table',3),
(14,'2026_08_16_090000_add_payment_and_delivery_fields_to_prescriptions_table',4),
(15,'2026_08_16_100000_create_prescription_messages_table',5),
(16,'2026_08_16_110000_add_alternate_mobile_to_users_table',6),
(17,'2026_08_16_142601_create_notifications_table',7),
(18,'2026_08_16_145111_rename_customer_decision_note_to_rejection_remark_on_prescriptions_table',8),
(19,'2026_08_16_120000_make_first_name_nullable_on_users_table',9);

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_permissions` */

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_roles` */

insert  into `model_has_roles`(`role_id`,`model_type`,`model_id`) values 
(1,'App\\Models\\User',1);

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `notifications` */

insert  into `notifications`(`id`,`type`,`notifiable_type`,`notifiable_id`,`data`,`read_at`,`created_at`,`updated_at`) values 
('05f1a5bb-7fcd-454b-aead-aa2fc5ae4209','App\\Notifications\\PrescriptionEventNotification','App\\Models\\User',4,'{\"prescription_id\":54,\"title\":\"Store picked up your prescription\",\"body\":\"MediServe Demo Pharmacy is now reviewing your prescription #54.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/prescriptions\\/54\",\"icon\":\"ri-store-2-line\",\"color\":\"info\"}','2026-08-16 14:43:56','2026-08-16 14:41:44','2026-08-16 14:43:56'),
('37ef4b4e-1d86-4fc8-bfc4-1f80fbd0164d','App\\Notifications\\PrescriptionEventNotification','App\\Models\\User',2,'{\"prescription_id\":54,\"title\":\"Customer accepted the estimate\",\"body\":\"Customer accepted the \\u20b9256.00 estimate for prescription #54. You can now assign a Captain.\",\"url\":\"http:\\/\\/192.168.31.155:8000\\/store\\/prescriptions\\/54\",\"icon\":\"ri-thumb-up-line\",\"color\":\"success\"}','2026-08-16 14:46:07','2026-08-16 14:44:55','2026-08-16 14:46:07'),
('56e58147-6892-497b-a4d9-603b63388ab2','App\\Notifications\\PrescriptionEventNotification','App\\Models\\User',12,'{\"prescription_id\":37,\"title\":\"COD settlement recorded\",\"body\":\"MediServe Demo Pharmacy marked \\u20b986.00 (1 order) as settled.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/captain\\/collections\",\"icon\":\"ri-hand-coin-line\",\"color\":\"success\"}',NULL,'2026-08-16 14:34:32','2026-08-16 14:34:32'),
('bb049492-d15d-4c07-af08-a6e06e5ee313','App\\Notifications\\PrescriptionEventNotification','App\\Models\\User',12,'{\"prescription_id\":54,\"title\":\"New delivery assigned\",\"body\":\"Deliver prescription #54 to T3-305, hcl it city. Lucknow \\u2014 collect \\u20b9256.00 cash on delivery.\",\"url\":\"\\/captain\\/dashboard\",\"icon\":\"ri-e-bike-2-line\",\"color\":\"primary\"}',NULL,'2026-08-16 14:45:59','2026-08-16 14:45:59'),
('bf8578dd-f112-47a3-b0c9-1454e1a4b2d1','App\\Notifications\\PrescriptionEventNotification','App\\Models\\User',4,'{\"prescription_id\":54,\"title\":\"Out for delivery\",\"body\":\"Abhishek is bringing your order for prescription #54.\",\"url\":\"\\/customer\\/prescriptions\\/54\",\"icon\":\"ri-e-bike-2-line\",\"color\":\"success\"}',NULL,'2026-08-16 14:45:59','2026-08-16 14:45:59'),
('d4d27b20-c87d-4423-8610-e4de01c95740','App\\Notifications\\PrescriptionEventNotification','App\\Models\\User',4,'{\"prescription_id\":54,\"title\":\"Price estimate ready\",\"body\":\"Review and accept\\/reject your \\u20b9256.00 estimate for prescription #54.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/customer\\/prescriptions\\/54\",\"icon\":\"ri-price-tag-3-line\",\"color\":\"primary\"}','2026-08-16 14:43:49','2026-08-16 14:43:06','2026-08-16 14:43:49');

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permissions` */

insert  into `permissions`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values 
(1,'roles.manage','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(2,'users.manage','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(3,'stores.view','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(4,'stores.approve','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(5,'stores.manage','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(6,'captains.manage','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(7,'catalog.manage','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(8,'orders.view','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(9,'orders.manage','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(10,'prescriptions.review','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(11,'coupons.manage','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(12,'content.manage','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(13,'reports.view','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(14,'settings.manage','web','2026-08-12 17:10:36','2026-08-12 17:10:36');

/*Table structure for table `prescription_messages` */

DROP TABLE IF EXISTS `prescription_messages`;

CREATE TABLE `prescription_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prescription_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prescription_messages_prescription_id_foreign` (`prescription_id`),
  KEY `prescription_messages_user_id_foreign` (`user_id`),
  CONSTRAINT `prescription_messages_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prescription_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `prescription_messages` */

insert  into `prescription_messages`(`id`,`prescription_id`,`user_id`,`body`,`created_at`,`updated_at`) values 
(3,37,2,'hi','2026-08-16 14:00:30','2026-08-16 14:00:30'),
(4,37,4,'hello','2026-08-16 14:00:49','2026-08-16 14:00:49'),
(5,37,2,'ek dawai nahi hai apki','2026-08-16 14:01:09','2026-08-16 14:01:09'),
(6,37,4,'Ok','2026-08-16 14:04:14','2026-08-16 14:04:14'),
(7,37,2,'we are sending the medicine to you soon','2026-08-16 14:04:35','2026-08-16 14:04:35');

/*Table structure for table `prescriptions` */

DROP TABLE IF EXISTS `prescriptions`;

CREATE TABLE `prescriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `store_id` bigint unsigned DEFAULT NULL,
  `captain_id` bigint unsigned DEFAULT NULL,
  `payment_method` enum('prepaid','cod') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` enum('not_required','pending','collected','settled') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `files` json NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `delivery_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `status` enum('pending','reviewing','contacted','awaiting_confirmation','confirmed','dispatched','delivered','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `items` json DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `call_notes` text COLLATE utf8mb4_unicode_ci,
  `called_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `collected_at` timestamp NULL DEFAULT NULL,
  `settled_at` timestamp NULL DEFAULT NULL,
  `customer_decided_at` timestamp NULL DEFAULT NULL,
  `rejection_remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prescriptions_user_id_foreign` (`user_id`),
  KEY `prescriptions_store_id_foreign` (`store_id`),
  KEY `prescriptions_captain_id_foreign` (`captain_id`),
  CONSTRAINT `prescriptions_captain_id_foreign` FOREIGN KEY (`captain_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prescriptions_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prescriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `prescriptions` */

insert  into `prescriptions`(`id`,`user_id`,`store_id`,`captain_id`,`payment_method`,`payment_status`,`files`,`remark`,`delivery_address`,`latitude`,`longitude`,`status`,`items`,`total_amount`,`call_notes`,`called_at`,`reviewed_at`,`delivered_at`,`collected_at`,`settled_at`,`customer_decided_at`,`rejection_remark`,`created_at`,`updated_at`) values 
(10,4,2,12,NULL,NULL,'[\"prescriptions/4/pQxQMBmaaijBNm6Ag9Q5NKQ5KBY3vm1dyTBXFd3V.pdf\"]','test','Address',26.7925267,81.0246256,'delivered','[{\"name\": \"Dolo 625 mg\", \"price\": \"36\", \"quantity\": \"1\"}]',36.00,'test','2026-08-16 13:28:51','2026-08-16 13:07:17','2026-08-16 13:41:43',NULL,NULL,'2026-08-16 13:28:36',NULL,'2026-08-16 13:05:38','2026-08-16 13:41:43'),
(31,4,2,12,'prepaid','not_required','[\"prescriptions/4/WVZVWAPiZj2wXkamyHya8HCo7MNw6wcJtCMVb2cN.jpg\"]','Ye dawaaye available he to estimate bataye?','T3-305, hcl it city',26.7925267,81.0246256,'delivered','[{\"name\": \"Dolo 650\", \"price\": \"36\", \"quantity\": \"1\"}, {\"name\": \"Xyz\", \"price\": \"500\", \"quantity\": \"1\"}]',536.00,'send complete number and phone number \r\nalso one medicine missing','2026-08-16 13:50:27','2026-08-16 13:42:52','2026-08-16 13:54:25',NULL,NULL,'2026-08-16 13:45:39',NULL,'2026-08-16 13:42:23','2026-08-16 13:54:25'),
(37,4,2,12,'cod','settled','[\"prescriptions/4/YGX2pEEhsX7AMkVSGJQ2XzRpf0vDVJxvrVqjc8dw.png\"]','hi check these medicines','T3-305, hcl it city',26.7925267,81.0246256,'delivered','[{\"name\": \"dolo 625\", \"price\": \"36\", \"quantity\": \"1\"}, {\"name\": \"levoceterzine 5mg\", \"price\": \"50\", \"quantity\": \"1\"}]',86.00,NULL,'2026-08-16 14:06:11','2026-08-16 13:57:12','2026-08-16 14:11:32','2026-08-16 14:11:32','2026-08-16 14:34:32',NULL,NULL,'2026-08-16 13:55:25','2026-08-16 14:34:32'),
(54,4,2,12,'cod','pending','[\"prescriptions/4/8eJKvBd5ABALnsYBrg5OR33GaEWzaxkVROcFePie.jpg\"]','make ready these medicines','T3-305, hcl it city. Lucknow',26.7925267,81.0246256,'dispatched','[{\"name\": \"dolo 625\", \"price\": \"36\", \"quantity\": \"1\"}, {\"name\": \"dolo 500\", \"price\": \"100\", \"quantity\": \"1\"}, {\"name\": \"zifi 200\", \"price\": \"120\", \"quantity\": \"1\"}]',256.00,NULL,'2026-08-16 14:43:06','2026-08-16 14:41:44',NULL,NULL,NULL,'2026-08-16 14:44:55',NULL,'2026-08-16 14:14:05','2026-08-16 14:45:59');

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `composition` text COLLATE utf8mb4_unicode_ci,
  `manufacturer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mrp` decimal(10,2) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `packaging` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uses` text COLLATE utf8mb4_unicode_ci,
  `images` json DEFAULT NULL,
  `requires_prescription` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_item_id_unique` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `products` */

insert  into `products`(`id`,`item_id`,`name`,`composition`,`manufacturer`,`mrp`,`price`,`packaging`,`uses`,`images`,`requires_prescription`,`is_active`,`created_at`,`updated_at`) values 
(1,'jr-cold-oral-suspension-1084098','JR Cold Oral Suspension','Chlorpheniramine Maleate (1mg/5ml) + Paracetamol (125mg/5ml) + Phenylephrine (2.5mg/5ml)','Helplab Healthcare Pvt Ltd',60.38,NULL,'60.0 ml in 1 bottle','Treatment of Common cold','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/a28786ba7d754deeac31aec3d5ab5b48.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/68c553da27c14e0b8de066fcc3ff3328.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/28019341f85f459fbebd97a014c8fbb9.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(2,'jastinib-tablet-979900','Jastinib Tablet','Axitinib (5mg)','Jasgur Life Sciences',3501.56,NULL,'28.0 tablets in 1 bottle','Kidney cancer','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/7e1dfbf139f443fe93d9116482ea9f68.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/dffb7653a0da46039a1410acdcf7e217.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/719b9193b25b403db2ee8b8ba57bf620.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/9e4a94a8ba654646b70276d6b7ca839d.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/81ba8d136e7d45ad81e4539c8a6617b0.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/834cb099fddc4a019345d313f2b935bc.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(3,'joyven-xr-37.5-tablet-984971','Joyven XR 37.5 Tablet','Venlafaxine (37.5mg)','Altura Lifesciences Pvt. Ltd.',67.50,NULL,'10.0 Tablet pr in 1 strip','Depression\nAnxiety disorder','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/08fd7fbb81384288b3c4ed13c9696543.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/a89b92929b314a228b8427b31c084acb.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/862d8d01e1fb4265aba48494792a281e.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/238b48fc90f64413bd021b0372b5f75c.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/5cf0591f8b1d4a13bdf20e21304e3036.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(4,'justmet-safe-2-tablet-er-622715','Justmet Safe 2 Tablet ER','Glimepiride (2mg) + Metformin (500mg) + Voglibose (0.2mg)','Cadbless Life Care',107.81,NULL,'10.0 tablet er in 1 strip','Treatment of Type 2 diabetes mellitus','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/222987148c8042cd8874b9d745996b11.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/a04a1ddcef3043a7b4f55ec6beffe0ed.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/55d74ab4114842c198967d62319b0404.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(5,'jenberg-dsr-capsule-824195','Jenberg DSR Capsule','Domperidone (30mg) + Rabeprazole (20mg)','Hitzelberger Life Sciences Pvt Ltd',91.22,NULL,'10.0 capsule sr in 1 strip','Treatment of Gastroesophageal reflux disease (Acid reflux)\nTreatment of Peptic ulcer disease','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/9652d556eeba4f99a73a5ed154ccdfc8.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/86e6583845e24ea087e755d983f8d38b.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/08c1449878b14e5297a4b0878430dfe2.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/901127edab064e6ab421a2a03df3eabc.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/eb93d4b7d00841cfaf2181114574cf72.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(6,'jetmino-infusion-1105910','Jetmino Infusion','Amino Acids (NA)','Cafoli Lifecare Pvt. Ltd.',561.56,NULL,'100.0 ml in 1 bottle','Nutritional deficiencies','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/03d0492fc2724c08b2fff00a068a306c.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/8a74ace61e424553921790080de5e3ec.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/7e8b67d703d24e378cbf724bb82787b5.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(7,'justlina-m-forte-tablet-1073029','Justlina-M Forte Tablet','Metformin (1000mg) + Linagliptin (2.5mg)','Cadbless Life Care',158.44,NULL,'10.0 tablets in 1 strip','Treatment of Type 2 diabetes mellitus','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/c2e568f184e441ecb1cc828497d22794.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/ba56a71d68d544edbe77b60a57bb5ab7.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/e576fd83d0314283baea564dfc765cab.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(8,'jagforce-sb-130-capsule-1107687','Jagforce SB 130 Capsule','Itraconazole (130mg)','Jagsam Pharma',202.50,NULL,'10.0 capsules in 1 strip','Treatment of Fungal infections','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/31ade10075ea42fb931ab014456c2b26.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/71f90bad30d340a38647b527e7f60f35.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/f5f5ce7b827b4d42b081184908a42f92.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(9,'jekvita-plus-tablet-1086080','Jekvita Plus Tablet','Calcium Carbonate (500mg) + Benfotiamine (150mg) + Alpha Lipoic Acid (100mg) + Inositol (100mg) + Chromium Picolinate (200mcg) + Methylcobalamin (1500mcg) + Folic Acid (1.5mg) + Pyridoxine Hydrochloride (3mg) + Vitamin D3 (1000IU)','Jekar Pharmaceuticals',356.25,NULL,'10.0 tablets in 1 strip','Treatment of Nutritional deficiencies','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/2e5af6a5a4894e20b8a087a49c8d47d0.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/8d97768a814c495783cb268c3df54e4d.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/dcab9e0ae34c4c5da4609247aecf44e4.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/65d8c32077be493d8bb6e6b692255c99.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/c0688c89bcd94234b657c5544a42be09.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(10,'jaktofa-5-tablet-1122969','Jaktofa 5 Tablet','Tofacitinib (5mg)','Renauxe Pharma India Pvt Ltd',186.56,NULL,'10.0 tablets in 1 strip','Treatment of Ulcerative colitis\nTreatment of Ankylosing spondylitis\nTreatment of Rheumatoid arthritis\nTreatment of Psoriatic arthritis','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/452ef48a5d0141f8aa9614ec409ccc3c.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/7a5fa055c79a49f0900a7655f5a2776b.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/5be7a61faf984f8c85c756b9aab31a1e.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/15f09ddb55d849c39de7ab5ba8ec5dab.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(11,'jupiros-f-160mg-5mg-tablet-559088','Jupiros F 160mg/5mg Tablet','Fenofibrate (160mg) + Rosuvastatin (5mg)','Alkem Laboratories Ltd',278.40,262.00,'10.0 tablets in 1 strip','Prevention of Heart attack\nTreatment of High cholesterol','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/09da0c36b8d34aecb78841b681ba8674.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/1ab5d110396c43ce915984cc593d2c83.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/7a0e04e241434d25b12e1b72d805e569.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/ddb69dc4003f46c7a7fca40c2f552e7c.jpeg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/8827a3094a9848a8ad50624e9b19b4d5.jpeg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/88d2e6a05be24294b36ce9d8b8f21dc4.jpeg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(12,'janpra-roll-on-oil-881448','Janpra Roll On Oil','Diclofenac (2% w/v) + Methyl Salicylate (10% w/v) + Menthol (5% w/v)','Janjaes Pharmaceutical Private Limited',117.19,NULL,'50.0 ml in 1 Roll-on bottle','Pain relief','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/3e33545476b04aefb0df5a12885bfd72.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/775914d87ac3484a924c8523cf7dafd3.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(13,'jointfix-tablet-544905','Jointfix Tablet','Glucosamine (750mg) + Diacerein (50mg) + Methyl Sulfonyl Methane (250mg)','Deon Healthcare',126.56,NULL,'10.0 tablets in 1 strip','Osteoarthritis','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/qcwtjttshfvc6bfg5izg.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(14,'jublin-m-capsule-159667','Jublin-M Capsule','Methylcobalamin (750mcg) + Pregabalin (75mg)','Dr. Johns Laboratories Pvt Ltd',187.17,NULL,'10.0 capsules in 1 strip','Treatment of Neuropathic pain','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/4c160dde8b8a440aa31b3b3f688094fb.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/d53a427345e846598bc00b52a0c48104.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/3e9825c12ceb4a96b0b72b61961fafc0.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(15,'jesodom-30mg-40mg-capsule-sr-422760','Jesodom 30mg/40mg Capsule SR','Domperidone (30mg) + Pantoprazole (40mg)','Jessuns Pharma',106.88,103.00,'10.0 capsule sr in 1 strip','Treatment of Gastroesophageal reflux disease (Acid reflux)\nTreatment of Erosive esophagitis\nTreatment of Gastritis\nTreatment of Indigestion','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/ba4eddace25e48fcb8d7682181f9f078.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/b1de2bee66eb43ed9e06a5b63efb156f.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/dcf24826c6584505a349a19430e8b329.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/f30e2310039e426184e5aa11125b2437.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/99e360ab0d974c0e9ee5f6ce9cb78303.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(16,'justoza-s-10-100-tablet-1027625','Justoza-S 10/100 Tablet','Dapagliflozin (10mg) + Sitagliptin (100mg)','Mankind Pharma Ltd',166.86,150.00,'10.0 tablets in 1 strip','Treatment of Type 2 diabetes mellitus','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/df51e8e130a842368a5664a8d52eb478.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/c6d306dddaed408dacb4a40c59ef2412.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/3fb2ae3f80c241cd874f6d914e4d65db.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(17,'jupirab-d-capsule-sr-984471','Jupirab-D Capsule SR','Domperidone (30mg) + Rabeprazole (20mg)','Jupiven Pharmaceuticals Pvt Ltd',112.50,NULL,'10.0 capsule sr in 1 strip','Treatment of Gastroesophageal reflux disease (Acid reflux)\nTreatment of Peptic ulcer disease','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/694565cabb8d470dbf3fb3b4b6315120.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/83f421b124454edd91e9aa3129dec514.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(18,'ji-lmt-od-200mg-tablet-sr-690691','JI Lmt OD 200mg Tablet SR','Lamotrigine (200mg)','Ishjas Pharma Pvt Ltd',304.69,NULL,'10.0 tablet sr in 1 strip','Epilepsy\nMania\nMigraine\nBipolar disorder\nTrigeminal neuralgia\nSeizures\nEpilepsy/Seizures','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/i4jit7s9thdpgbglmelt.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/bworzrj7ubb9otachh4q.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(19,'jk-d3-60k-capsule-661086','JK-D3 60K Capsule','Vitamin D3 (60000IU)','Jk India Healthcare Pvt Ltd',121.88,NULL,'4.0 soft gelatin capsules in 1 strip','Treatment of Osteoporosis\nTreatment of Vitamin D deficiency','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/n9c8ylvw9853yp2ycswk.png\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/yfrhrqn6joq9gzgbq061.png\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/rghh5a9tulemzzyznkzh.png\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/ybmbne5lqr2nipc8sawk.png\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(20,'jupitab-40-tablet-820653','Jupitab 40 Tablet','Rosuvastatin (40mg)','Dashak Pharmaceuticals',327.19,NULL,'10.0 tablets in 1 strip','High cholesterol\nHigh triglycerides\nPrevention of heart attack and stroke','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/8f554be5aabe4ebda4b99eb45fe548a7.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/efb835e1187645f5a3b2c793c512f4b3.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(21,'je-shield-sd-vaccine-370276','JE-Shield SD Vaccine','Inactivated Japanese Encephalitis virus protein (5mcg)','Abbott',685.00,NULL,'0.5 ml in 1 prefilled syringe','Prevention of Japanese Encephalitis','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/mlfuzod7h5bp8s60g0st.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/u7gpekh1lembui7s9ecj.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(22,'jceler-mv-2-tablet-sr-730189','Jceler MV 2 Tablet SR','Glimepiride (2mg) + Metformin (500mg) + Voglibose (0.2mg)','Vernoxy Pharma Pvt Ltd',138.54,NULL,'15.0 tablet sr in 1 strip','Treatment of Type 2 diabetes mellitus','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/c3j5mcupjojhwfhddx3w.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(23,'jalaesta-20-tablet-895075','Jalaesta 20 Tablet','Escitalopram Oxalate (20mg)','Ajala Pharmaceuticals',131.25,NULL,'10.0 tablets in 1 strip','Depression\nAnxiety disorder\nObsessive-compulsive disorder\nPhobia\nPost traumatic stress disorder\nTreatment of Panic disorder','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/791db5fd9e6b4f06b25702ec6a3d556e.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/f804c0ef7a2744c0855900f8f40f6e7e.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/f34ae4148d334ffea53da36f077a19c2.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/34ac40360bd843d685a935979926f071.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(24,'jetflam-as-tablet-478857','Jetflam-AS Tablet','Aceclofenac (100mg) + Paracetamol (325mg) + Serratiopeptidase (15mg)','Olympus Life Sciences',117.19,110.00,'10.0 tablets in 1 strip','Pain relief','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/e19b64c2504d4d1a80e607802d279f2e.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/65f78f43454f41ea990998ebc31752bf.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/34a64e1fa7c2476ebcce2d3ce244d40f.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(25,'jufate-o-1000mg-10ml-20mg-10ml-suspension-320164','Jufate O 1000mg/10ml/20mg/10ml Suspension','Sucralfate (1000mg/10ml) + Oxetacaine (20mg/10ml)','Dr. Johns Laboratories Pvt Ltd',153.10,144.00,'100.0 ml in 1 bottle','Treatment of Acidity\nTreatment of Heartburn\nTreatment of Stomach ulcers','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/d41e782df86a4d10bb342bb51845dcb3.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/3e53fd5829254b15938b77c47bf18582.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/fa84db482a424e04a9603da532f6d5c0.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/55384445800a43d980a23e396b2168de.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/3571e6de6e514e7283100859b593657c.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/1cf4fe742dc7476b80eaefed967718fe.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/9d43e3359e4f4eb0913d3200471e640a.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(26,'joylift-12.5-tablet-er-1108507','Joylift 12.5 Tablet ER','Paroxetine (12.5mg)','Nuvenex Labs Pvt Ltd',112.50,NULL,'10.0 tablet er in 1 strip','Depression\nAnxiety disorder\nObsessive-compulsive disorder\nPhobia\nPost traumatic stress disorder\nTreatment of Panic disorder','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/46003432a9cb4507aae5b85e0522b88f.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/1057b4d583ff4431b46ac16cf7c340e0.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(27,'jimlig-sb-130-capsule-897964','Jimlig SB 130 Capsule','Itraconazole (130mg)','Encore Pharmaceuticals Inc.',234.30,NULL,'10.0 capsules in 1 strip','Treatment of Fungal infections','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/12f392f5c7b34d84a6b8c91ae8cafa3e.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/8330a8b640b14914a37aeacf8f8b59d7.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(28,'judibat-40-capsule-674812','Judibat 40 Capsule','Domperidone (30mg) + Esomeprazole (40mg)','Pranyog Healthcare',131.25,126.00,'10.0 capsules in 1 strip','Treatment of Gastroesophageal reflux disease (Acid reflux)','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/deeqvl80ebq8zd1rfeam.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(29,'joycita-10-tablet-1098354','Joycita 10 Tablet','Escitalopram Oxalate (10mg)','Altura Lifesciences Pvt. Ltd.',93.75,84.50,'10.0 tablets in 1 strip','Depression\nAnxiety disorder\nObsessive-compulsive disorder\nPhobia\nPost traumatic stress disorder\nTreatment of Panic disorder','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/aebe56bd0dfe472bb9f952e9c47b1593.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/16159bfa02ec4e88b63ca9a197650ae8.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/a63aefb0391b4f0d965a1d7bd2cc2eae.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(30,'janclav-cv-625-tablet-660364','Janclav-CV 625 Tablet','Amoxycillin  (500mg) +  Clavulanic Acid (125mg)','Janjaes Pharmaceutical Private Limited',178.41,NULL,'10.0 tablets in 1 strip','Treatment of Bacterial infections','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/q1cnmosa5vtyzk6hulzz.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/hqcpmdtd33txsnahhq2x.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/nxwoyvyykjplh1il9i1j.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(31,'jack-t-tablet-1061618','Jack-T Tablet','Tofacitinib (5mg)','Rebanta Health Care (P) Limited',315.00,NULL,'14.0 tablets in 1 strip','Treatment of Ulcerative colitis\nTreatment of Ankylosing spondylitis\nTreatment of Rheumatoid arthritis\nTreatment of Psoriatic arthritis','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/9MVALhIAQEAJCQAgIASEgBISAEBACQkAIFBF0TQIhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAgIASEgBISAEBACQkAICAEh0AMERNB7MAhqghAQAkJACAgBISAEhIAQEAJCQAgIARF0zQEhIASEgBAQAkJACAgBISAEhIAQEAI9QEAEvQeDoCYIASEgBISAEBACQkAICAEhIASEgBAQQdccEAJCQAj8f+3XMQ0AAADCMP+uZ2NHHZDCAwECBAgQIECAAAECAwEHfVCCCAQIECBAgAABAgQIECBAwEG3AQIECBAgQIAAAQIECBAgMBBw0AcliECAAAECBAgQIECAAAECBBx0GyBAgAABAgQIECBAgAABAgMBB31QgggECBAgQIAAAQIECBAgQMBBtwECBAgQIECAAAECBAgQIDAQcNAHJYhAgAABAgQIECBAgAABAgQcdBsgQIAAAQIECBAgQIAAAQIDAQd9UIIIBAgQIECAAAECBAgQIEDAQbcBAgQIECBAgAABAgQIECAwEHDQByWIQIAAAQIECBAgQIAAAQIEHHQbIECAAAECBAgQIECAAAECAwEHfVCCCAQIECBAgAABAgQIECBAwEG3AQIECBAgQIAAAQIECBAgMBBw0AcliECAAAECBAgQIECAAAECBBx0GyBAgAABAgQIECBAgAABAgMBB31QgggECBAgQIAAAQIECBAgQMBBtwECBAgQIECAAAECBAgQIDAQcNAHJYhAgAABAgQIECBAgAABAgQcdBsgQIAAAQIECBAgQIAAAQIDAQd9UIIIBAgQIECAAAECBAgQIEDAQbcBAgQIECBAgAABAgQIECAwEHDQByWIQIAAAQIECBAgQIAAAQIEHHQbIECAAAECBAgQIECAAAECAwEHfVCCCAQIECBAgAABAgQIECBAwEG3AQIECBAgQIAAAQIECBAgMBBw0AcliECAAAECBAgQIECAAAECBBx0GyBAgAABAgQIECBAgAABAgMBB31QgggECBAgQIAAAQIECBAgQMBBtwECBAgQIECAAAECBAgQIDAQcNAHJYhAgAABAgQIECBAgAABAgQcdBsgQIAAAQIECBAgQIAAAQIDAQd9UIIIBAgQIECAAAECBAgQIEDAQbcBAgQIECBAgAABAgQIECAwEHDQByWIQIAAAQIECBAgQIAAAQIEHHQbIECAAAECBAgQIECAAAECAwEHfVCCCAQIECBAgAABAgQIECBAwEG3AQIECBAgQIAAAQIECBAgMBBw0AcliECAAAECBAgQIECAAAECBBx0GyBAgAABAgQIECBAgAABAgMBB31QgggECBAgQIAAAQIECBAgQMBBtwECBAgQIECAAAECBAgQIDAQcNAHJYhAgAABAgQIECBAgAABAgQcdBsgQIAAAQIECBAgQIAAAQIDAQd9UIIIBAgQIECAAAECBAgQIEDAQbcBAgQIEIv7qQoAAAksSURBVCBAgAABAgQIECAwEHDQByWIQIAAAQIECBAgQIAAAQIEHHQbIECAAAECBAgQIECAAAECAwEHfVCCCAQIECBAgAABAgQIECBAwEG3AQIECBAgQIAAAQIECBAgMBBw0AcliECAAAECBAgQIECAAAECBBx0GyBAgAABAgQIECBAgAABAgMBB31QgggECBAgQIAAAQIECBAgQMBBtwECBAgQIECAAAECBAgQIDAQcNAHJYhAgAABAgQIECBAgAABAgQcdBsgQIAAAQIECBAgQIAAAQIDAQd9UIIIBAgQIECAAAECBAgQIEDAQbcBAgQIECBAgAABAgQIECAwEHDQByWIQIAAAQIECBAgQIAAAQIEHHQbIECAAAECBAgQIECAAAECAwEHfVCCCAQIECBAgAABAgQIECBAwEG3AQIECBAgQIAAAQIECBAgMBBw0AcliECAAAECBAgQIECAAAECBBx0GyBAgAABAgQIECBAgAABAgMBB31QgggECBAgQIAAAQIECBAgQMBBtwECBAgQIECAAAECBAgQIDAQcNAHJYhAgAABAgQIECBAgAABAgQcdBsgQIAAAQIECBAgQIAAAQIDAQd9UIIIBAgQIECAAAECBAgQIEDAQbcBAgQIECBAgAABAgQIECAwEHDQByWIQIAAAQIECBAgQIAAAQIEHHQbIECAAAECBAgQIECAAAECAwEHfVCCCAQIECBAgAABAgQIECBAwEG3AQIECBAgQIAAAQIECBAgMBBw0AcliECAAAECBAgQIECAAAECBBx0GyBAgAABAgQIECBAgAABAgMBB31QgggECBAgQIAAAQIECBAgQMBBtwECBAgQIECAAAECBAgQIDAQcNAHJYhAgAABAgQIECBAgAABAgQcdBsgQIAAAQIECBAgQIAAAQIDAQd9UIIIBAgQIECAAAECBAgQIEDAQbcBAgQIECBAgAABAgQIECAwEHDQByWIQIAAAQIECBAgQIAAAQIEHHQbIECAAAECBAgQIECAAAECAwEHfVCCCAQIECBAgAABAgQIECBAwEG3AQIECBAgQIAAAQIECBAgMBBw0AcliECAAAECBAgQIECAAAECBBx0GyBAgAABAgQIECBAgAABAgMBB31QgggECBAgQIAAAQIECBAgQMBBtwECBAgQIECAAAECBAgQIDAQcNAHJYhAgAABAgQIECBAgAABAgQcdBsgQIAAAQIECBAgQIAAAQIDAQd9UIIIBAgQIECAAAECBAgQIEDAQbcBAgQIECBAgAABAgQIECAwEHDQByWIQIAAAQIECBAgQIAAAQIEHHQbIECAAAECBAgQIECAAAECAwEHfVCCCAQIECBAgAABAgQIECBAwEG3AQIECBAgQIAAAQIECBAgMBBw0AcliECAAAECBAgQIECAAAECBBx0GyBAgAABAgQIECBAgAABAgMBB31QgggECBAgQIAAAQIECBAgQMBBtwECBAgQIECAAAECBAgQIDAQcNAHJYhAgAABAgQIECBAgAABAgQcdBsgQIAAAQIECBAgQIAAAQIDAQd9UIIIBAgQIECAAAECBAgQIEDAQbcBAgQIECBAgAABAgQIECAwEHDQByWIQIAAAQIECBAgQIAAAQIEHHQbIECAAAECBAgQIECAAAECAwEHfVCCCAQIECBAgAABAgQIECBAwEG3AQIECBAgQIAAAQIECBAgMBBw0AcliECAAAECBAgQIECAAAECBBx0GyBAgAABAgQIECBAgAABAgMBB31QgggECBAgQIAAAQIECBAgQMBBtwECBAgQIECAAAECBAgQIDAQcNAHJYhAgAABAgQIECBAgAABAgQcdBsgQIAAAQIECBAgQIAAAQIDAQd9UIIIBAgQIECAAAECBAgQIEDAQbcBAgQIECBAgAABAgQIECAwEHDQByWIQIAAAQIECBAgQIAAAQIEHHQbIECAAAECBAgQIECAAAECAwEHfVCCCAQIECBAgAABAgQIECBAwEG3AQIECBAgQIAAAQIECBAgMBBw0AcliECAAAECBAgQIECAAAECBBx0GyBAgAABAgQIECBAgAABAgMBB31QgggECBAgQIAAAQIECBAgQMBBtwECBAgQIECAAAECBAgQIDAQcNAHJYhAgAABAgQIECBAgAABAgQcdBsgQIAAAQIECBAgQIAAAQIDAQd9UIIIBAgQIECAAAECBAgQIEDAQbcBAgQIECBAgAABAgQIECAwEHDQByWIQIAAAQIECBAgQIAAAQIEHHQbIECAAAECBAgQIECAAAECAwEHfVCCCAQIECBAgAABAgQIECBAwEG3AQIECBAgQIAAAQIECBAgMBBw0AcliECAAAECBAgQIECAAAECBBx0GyBAgAABAgQIECBAgAABAgMBB31QgggECBAgQIAAAQIECBAgQMBBtwECBAgQIECAAAECBAgQIDAQcNAHJYhAgAABAgQIECBAgAABAgQcdBsgQIAAAQIECBAgQIAAAQIDAQd9UIIIBAgQIECAAAECBAgQIEDAQbcBAgQIECBAgAABAgQIECAwEHDQByWIQIAAAQIECBAgQIAAAQIEHHQbIECAAAECBAgQIECAAAECAwEHfVCCCAQIECBAgAABAgQIECBAwEG3AQIECBAgQIAAAQIECBAgMBBw0AcliECAAAECBAgQIECAAAECBBx0GyBAgAABAgQIECBAgAABAgMBB31QgggECBAgQIAAAQIECBAgQMBBtwECBAgQIECAAAECBAgQIDAQcNAHJYhAgAABAgQIECBAgAABAgQcdBsgQIAAAQIECBAgQIAAAQIDAQd9UIIIBAgQIECAAAECBAgQIEDAQbcBAgQIECBAgAABAgQIECAwEHDQByWIQIAAAQIECBAgQIAAAQIEHHQbIECAAAECBAgQIECAAAECAwEHfVCCCAQIECBAgAABAgQIECBAwEG3AQIECBAgQIAAAQIECBAgMBBw0AcliECAAAECBAgQIECAAAECBBx0GyBAgAABAgQIECBAgAABAgMBB31QgggECBAgQIAAAQIECBAgQMBBtwECBAgQIECAAAECBAgQIDAQcNAHJYhAgAABAgQIECBAgAABAgQcdBsgQIAAAQIECBAgQIAAAQIDgQALEvawtOZgzQAAAABJRU5ErkJggg==\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/w93A+6zd4BlhQAAAABJRU5ErkJggg==\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/SkI8jwACCCCAAAJ1CRCg11VepBYBBBBAAAEEEEAAAQQQQKBRAQL0RguWbCGAAAIIIIAAAggggAACCNQlQIBeV3mRWgQQQAABBBBAAAEEEEAAgUYFCNAbLViyhQACCCCAAAIIIIAAAgggUJcAAXpd5UVqEUAAAQQQQAABBBBAAAEEGhUgQG+0YMkWAggggAACCCCAAAIIIIBAXQIE6HWVF6lFAAEEEEAAAQQQQAABBBBoVIAAvdGCJVsIIIAAAggggAACCCCAAAJ1CRCg11VepBYBBBBAAAEEEEAAAQQQQKBRAQL0RguWbCGAAAIIIIAAAggggAACCNQlQIBeV3mRWgQQQAABBBBAAAEEEEAAgUYFCNAbLViyhQACCCCAAAIIIIAAAgggUJcAAXpd5UVqEUAAAQQQQAABBBBAAAEEGhUgQG+0YMkWAggggAACCCCAAAIIIIBAXQIE6HWVF6lFAAEEEEAAAQQQQAABBBBoVIAAvdGCJVsIIIAAAggggAACCCCAAAJ1CRCg11VepBYBBBBAAAEEEEAAAQQQQKBRAQL0RguWbCGAAAIIIIAAAggggAACCNQlQIBeV3mRWgQQQAABBBBAAAEEEEAAgUYFCNAbLViyhQACCCCAAAIIIIAAAgggUJcAAXpd5UVqEUAAAQQQQAABBBBAAAEEGhUgQG+0YMkWAggggAACCCCAAAIIIIBAXQIE6HWVF6lFAAEEEEAAAQQQQAABBBBoVIAAvdGCJVsIIIAAAggggAACCCCAAAJ1CRCg11VepBYBBBBAAAEEEEAAAQQQQKBRAQL0RguWbCGAAAIIIIAAAggggAACCNQlQIBeV3mRWgQQQAABBBBAAAEEEEAAgUYFCNAbLViyhQACCCCAAAIIIIAAAgggUJcAAXpd5UVqEUAAAQQQQAABBBBAAAEEGhUgQG+0YMkWAggggAACCCCAAAIIIIBAXQIE6HWVF6lFAAEEEEAAAQQQQAABBBBoVIAAvdGCJVsIIIAAAggggAACCCCAAAJ1CRCg11VepBYBBBBAAAEEEEAAAQQQQKBRAQL0RguWbCGAAAIIIIAAAggggAACCNQlQIBeV3mRWgQQQAABBBBAAAEEEEAAgUYFCNAbLViyhQACCCCAAAIIIIAAAgggUJcAAXpd5UVqEUAAAQQQQAABBBBAAAEEGhUgQG+0YMkWAggggAACCCCAAAIIIIBAXQIE6HWVF6lFAAEEEEAAAQQQQAABBBBoVIAAvdGCJVsIIIAAAggggAACCCCAAAJ1CRCg11VepBYBBBBAAAEEEEAAAQQQQKBRAQL0RguWbCGAAAIIIIAAAggggAACCNQlQIBeV3mRWgQQQAABBBBAAAEEEEAAgUYFCNAbLViyhQACCCCAAAIIIIAAAgggUJcAAXpd5UVqEUAAAQQQQAABBBBAAAEEGhUgQG+0YMkWAggggAACCCCAAAIIIIBAXQIE6HWVF6lFAAEEEEAAAQQQQAABBBBoVIAAvdGCJVsIIIAAAggggAACCCCAAAJ1CRCg11VepBYBBBBAAAEEEEAAAQQQQKBRAQL0RguWbCGAAAIIIIAAAggggAACCNQlQIBeV3mRWgQQQAABBBBAAAEEEEAAgUYFCNAbLViyhQACCCCAAAIIIIAAAgggUJcAAXpd5UVqEUAAAQQQQAABBBBAAAEEGhUgQG+0YMkWAggggAACCCCAAAIIIIBAXQIE6HWVF6lFAAEEEEAAAQQQQAABBBBoVIAAvdGCJVsIIIAAAggggAACCCCAAAJ1CRCg11VepBYBBBBAAAEEEEAAAQQQQKBRAQL0RguWbCGAAAIIIIAAAggggAACCNQlQIBeV3mRWgQQQAABBBBAAAEEEEAAgUYFCNAbLViyhQACCCCAAAIIIIAAAgggUJcAAXpd5UVqEUAAAQQQQAABBBBAAAEEGhUgQG+0YMkWAggggAACCCCAAAIIIIBAXQIE6HWVF6lFAAEEEEAAAQQQQAABBBBoVIAAvdGCJVsIIIAAAggggAACCCCAAAJ1CRCg11VepBYBBBBAAAEEEEAAAQQQQKBRAQL0RguWbCGAAAIIIIAAAggggAACCNQlQIBeV3mRWgQQQAABBBBAAAEEEEAAgUYFCNAbLViyhQACCCCAAAIIIIAAAgggUJcAAXpd5UVqEUAAAQQQQAABBBBAAAEEGhUgQG+0YMkWAggggAACCCCAAAIIIIBAXQIE6HWVF6lFAAEEEEAAAQQQQAABBBBoVIAAvdGCJVsIIIAAAggggAACCCCAAAJ1CRCg11VepBYBBBBAAAEEEEAAAQQQQKBRAQL0RguWbCGAAAIIIIAAAggggAACCNQlQIBeV3mRWgQQQAABBBBAAAEEEEAAgUYFCNAbLViyhQACCCCAAAIIIIAAAgggUJcAAXpd5UVqEUAAAQQQQAABBBBAAAEEGhUgQG+0YMkWAggggAACCCCAAAIIIIBAXQIE6HWVF6lFAAEEEEAAAQQQQAABBBBoVIAAvdGCJVsIIIAAAggggAACCCCAAAJ1CRCg11VepBYBBBBAAAEEEEAAAQQQQKBRAQL0RguWbCGAAAIIIIAAAggggAACCNQlQIBeV3mRWgQQQAABBBBAAAEEEEAAgUYFCNAbLViyhQACCCCAAAIIIIAAAgggUJcAAXpd5UVqEUAAAQQQQAABBBBAAAEEGhUgQG+0YMkWAggggAACCCCAAAIIIIBAXQIE6HWVF6lFAAEEEEAAAQQQQAABBBBoVIAAvdGCJVsIIIAAAggggAACCCCAAAJ1CfwfFwWaorw3U08AAAAASUVORK5CYII=\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(32,'jac-up-tablet-132055','Jac-UP Tablet','Glucosamine (750mg) + Diacerein (50mg) + Methyl Sulfonyl Methane (250mg)','Athens Labs Ltd',280.30,269.00,'10.0 tablets in 1 strip','Osteoarthritis','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/ssfwx6kofapth3l1p2jh.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/r8pwlz1lgpq94dqzn1bx.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/lrhf5lmzmrg2eyrip5ab.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/idcq3l5qpqfhkuht785k.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/gi9i9yoebt3mkal3p7di.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/pquej2gdpwotxso8bufq.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(33,'jupinion-20-tablet-984470','Jupinion 20 Tablet','Isotretinoin (20mg)','Jupiven Pharmaceuticals Pvt Ltd',159.38,NULL,'10.0 tablets in 1 strip','Treatment of Acne','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/d4867c98d26a4b1b8c6a9f8082ac492b.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/cddd6949b2ac4c69b54d780843331a35.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(34,'jocare-od-tablet-165634','Jocare -OD Tablet','Calcium Citrate Malate (1250mg) + Magnesium Oxide (50mg) + Methylcobalamin (1500mcg) + Vitamin D3 (1000IU) + Vitamin K2-7 (90mcg) + Zinc Oxide (15mg)','Dr. Johns Laboratories Pvt Ltd',247.90,226.00,'10.0 tablets in 1 strip','Treatment of Nutritional deficiencies','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/fntyd2ekfwyxspt7gnyw.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/r8s1tyxztkge2pdp9tj0.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/vwadq1pmgj614yvvj2dj.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/eeptn3bvljhppotc7hkq.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/cxefysp1pkaurqvj2glx.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(35,'jesmeth-tablet-422776','Jesmeth Tablet','Methylcobalamin (1500mcg) + Vitamin B6 (Pyridoxine) (3mg) + Folic Acid (1.5mg)','Jessuns Pharma',195.90,188.00,'10.0 tablets in 1 strip','Treatment of Nutritional deficiencies','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/01576d430cba4962aff240a3b8efb028.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/00511c5a27f54c3e8867d364444e075d.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/9bfdf4bbf7a442b9b95972ccf6597501.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/e64c8259e3ec4e71a87913914ea78e71.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/5a5f1d7250074f5a9e3d3f97af6b97ee.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(36,'jakavi-10mg-tablet-987389','JakAVI 10mg Tablet','Ruxolitinib (10mg)','Novartis India Ltd',43214.00,40617.00,'10.0 tablets in 1 strip','Chronic idiopathic myelofibrosis\nPolycythemia vera','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/6439f516220248b88a4502298ff8f0f9.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/f03086f296814649ad220fd69d8ce7ca.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/a2a325ce017743d09c231eec170c2ca2.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/0b2a45b1c4b6439183338f8713f49328.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/5050c9feb5e4411ab86b86f01f0e4bd8.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(37,'jakseas-5mg-tablet-714382','Jakseas 5mg Tablet','Tofacitinib (5mg)','Overseas Healthcare Pvt Ltd',375.00,352.00,'10.0 tablets in 1 strip','Treatment of Ulcerative colitis\nTreatment of Ankylosing spondylitis\nTreatment of Rheumatoid arthritis\nTreatment of Psoriatic arthritis','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/22ebaf7c471f48f18a4ec11cfc2f1c43.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/b15eb1543daf42a1852bf4d3c9b17e38.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/2aeab02e786d4f9ca1fb3431544557f8.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/c78683345604419cb6189b0656868898.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(38,'jubiglim-m-1-forte-tablet-pr-758603','Jubiglim M 1 Forte Tablet PR','Glimepiride (1mg) + Metformin (1000mg)','Jubilant Life Sciences',119.11,107.00,'15.0 Tablet pr in 1 strip','Treatment of Type 2 diabetes mellitus','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/09c9d5c32cc042aca2b44e57c6d08cf1.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/1a9c824eb49e456596315a54b52d993b.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/22d90d216c0e46ae9b699810cbcf2c07.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(39,'jardiance-met-12.5mg-1000mg-tablet-429199','Jardiance Met 12.5mg/1000mg Tablet','Empagliflozin (12.5mg) + Metformin (1000mg)','Boehringer Ingelheim',450.00,415.00,'10.0 tablets in 1 strip','Treatment of Type 2 diabetes mellitus','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/059e283f026e481d9bfda0a0bb5eb70b.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/f320b86448104cbeb7beb842260a228d.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/9688e0fde43247cdafb1a4caaf13bccb.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/24ce0540aeac4538b43ca31449485927.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/906034c0a1a34e608f19875a309150c5.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/dd4493e9797b4b3cb3c3601511c02872.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36'),
(40,'janumet-1000mg-50mg-tablet-297270','Janumet 1000mg/50mg Tablet','Sitagliptin  (50mg) +  Metformin (1000mg)','MSD Pharmaceuticals Pvt Ltd',356.25,321.00,'15.0 tablets in 1 strip','Treatment of Type 2 diabetes mellitus','[\"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/475bf74b8be849bfab73c67a5d68fbde.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/6cf17f3128b046d1ad3900a000312de8.jpg\", \"https://onemg.gumlet.io/a_ignore,w_1000,h_1000,c_fit,q_auto,f_auto/862b9dacd9104c789b7ea9ec711f08ae.jpg\"]',0,1,'2026-08-15 05:17:35','2026-08-15 05:17:36');

/*Table structure for table `role_has_permissions` */

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_has_permissions` */

insert  into `role_has_permissions`(`permission_id`,`role_id`) values 
(1,1),
(2,1),
(3,1),
(4,1),
(5,1),
(6,1),
(7,1),
(8,1),
(9,1),
(10,1),
(11,1),
(12,1),
(13,1),
(14,1),
(3,2),
(4,2),
(5,2),
(6,2),
(7,3),
(11,3),
(8,4),
(10,4),
(12,4),
(8,5),
(13,5);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values 
(1,'Super Admin','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(2,'Store Manager','web','2026-08-12 17:10:36','2026-08-12 17:10:36'),
(3,'Catalog Manager','web','2026-08-12 17:10:37','2026-08-12 17:10:37'),
(4,'Support Admin','web','2026-08-12 17:10:37','2026-08-12 17:10:37'),
(5,'Finance Admin','web','2026-08-12 17:10:37','2026-08-12 17:10:37');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('aamKye1VzO6Mdywfr7WkooHnVdSUZ7nK2LtIWtR9',NULL,'192.168.31.117','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36','eyJfdG9rZW4iOiJqQkZNdjNNdktkVjVHcW1rVVlmb1RKSVdHbkZNdVVYdWx4eFRPYjExIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTkyLjE2OC4zMS4xNTU6ODAwMFwvY3VzdG9tZXJcL3ByZXNjcmlwdGlvbnNcLzM3In0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC8xOTIuMTY4LjMxLjE1NTo4MDAwXC9jdXN0b21lclwvbG9naW4iLCJyb3V0ZSI6ImN1c3RvbWVyLmxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1786888967),
('Ax24f3m5Ffx2xTVSwJraNJnzmUa8eh3g5BZ8UG7i',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','eyJfdG9rZW4iOiJLM3JWRFBCc0dMTlpiT1FrN3hCUmF0YTVPNDVlWWwwY3JmSFQySkxZIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3N0b3JlXC9wcmVzY3JpcHRpb25zXC8zNyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1786888974),
('EXMIxpcNzMcdIsHqxDXjSzjOkvkkYAzqwuxHqyGb',85,'192.168.31.155','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','eyJfdG9rZW4iOiIzVjQwZDdJMDZEaGpmQkJsaUNHVjJJWGRYM1Q0M3oxa3duN0N0M0hHIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMzEuMTU1OjgwMDBcL2N1c3RvbWVyXC9wcmVzY3JpcHRpb25zXC9jcmVhdGUiLCJyb3V0ZSI6ImN1c3RvbWVyLnByZXNjcmlwdGlvbnMuY3JlYXRlIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTkyLjE2OC4zMS4xNTU6ODAwMFwvc3RvcmVcL3ByZXNjcmlwdGlvbnNcLzU0In0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo4NX0=',1786894857),
('g6Qq2UIK8grH2sFQHOREQS3Qr8dQ3MPNzOpqMj3f',85,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJwOWlrUExvT2dZZU1SeVdkREdSNVBvSDQ5U2JuNHdVZXBseVJOd0xUIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jdXN0b21lclwvcHJlc2NyaXB0aW9uc1wvY3JlYXRlIiwicm91dGUiOiJjdXN0b21lci5wcmVzY3JpcHRpb25zLmNyZWF0ZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo4NX0=',1786894895),
('knE0lpE8emAXspO31jDHJaBqofa9QlemIgtf4avj',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','eyJfdG9rZW4iOiJMMGU4NHh5WEtFVzBlZGd2ZGxhZk50RkhtVEpWYm9sWFpBeFBRSVlVIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9zdG9yZVwvZGFzaGJvYXJkIiwicm91dGUiOiJzdG9yZS5kYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Mn0=',1786894865),
('UhIXgr4PmqJJJ8UqLYuU5X3K0xeRuAFgeBwMMmyx',12,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJseTBvbWVjNTNNclU1Y0t0bDNOaUtQZktObFlsSTVpOXF6VzZqU213IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2NhcHRhaW5cL2Rhc2hib2FyZCIsInJvdXRlIjoiY2FwdGFpbi5kYXNoYm9hcmQifSwidXJsIjpbXSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEyfQ==',1786890205),
('VlLWaQpyT3TlRxVZLxmqRr87gUKEHL7IS9nbYrT3',4,'192.168.31.117','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36','eyJfdG9rZW4iOiJxVWN1dVM1TGtHcmhQMlJKemQ1VnNiaFFhQWNqQWxwbWZySWdiaXdaIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTkyLjE2OC4zMS4xNTU6ODAwMFwvY3VzdG9tZXJcL3ByZXNjcmlwdGlvbnNcLzU0Iiwicm91dGUiOiJjdXN0b21lci5wcmVzY3JpcHRpb25zLnNob3cifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjR9',1786891497),
('WmmH0jyU1XukVnH1zTo7SiRr481nYNwPmFKDaLZz',NULL,'192.168.31.116','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJ0RVdjWEpRZ0EwNlRKc05Hd2RIOUhuOHVtZ0w3OGhIalRucXZpZUN3IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTkyLjE2OC4zMS4xNTU6ODAwMCIsInJvdXRlIjoiaG9tZSJ9fQ==',1786891051),
('WYK3KIgULV6wMoRxtW5LAkv7Vsra1Li0WHF8MQDi',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJRU0hLOHZMcTRCamNSNWVwQ2R6bTVJS2xvNFViNTVMUEFpWUl6aEJGIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1786890976);

/*Table structure for table `set_config` */

DROP TABLE IF EXISTS `set_config`;

CREATE TABLE `set_config` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `set_config_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `set_config` */

insert  into `set_config`(`id`,`key`,`value`,`created_at`,`updated_at`) values 
(1,'SITELOGO','assets/logo/logo.png','2026-08-12 17:10:38','2026-08-12 17:21:16'),
(2,'SITENAME','MediServe','2026-08-12 17:10:38','2026-08-12 17:10:38'),
(3,'SITEEMAIL',NULL,'2026-08-12 17:10:38','2026-08-12 17:21:16'),
(4,'SITEPHONE',NULL,'2026-08-12 17:10:38','2026-08-12 17:21:16'),
(5,'SITEADDRESS',NULL,'2026-08-12 17:10:38','2026-08-12 17:21:16');

/*Table structure for table `stores` */

DROP TABLE IF EXISTS `stores`;

CREATE TABLE `stores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `shop_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `delivery_radius_km` smallint unsigned DEFAULT NULL,
  `delivery_speed_kmph` smallint unsigned DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stores_user_id_unique` (`user_id`),
  CONSTRAINT `stores_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `stores` */

insert  into `stores`(`id`,`user_id`,`shop_name`,`license_no`,`gst_no`,`latitude`,`longitude`,`delivery_radius_km`,`delivery_speed_kmph`,`status`,`created_at`,`updated_at`) values 
(1,5,'Maa Durga Medical store','234234235','GHy85w8583',26.8681940,80.9750348,6,10,'approved','2026-08-12 17:34:02','2026-08-12 17:34:21'),
(2,2,'MediServe Demo Pharmacy','DL-DEMO-0001',NULL,NULL,NULL,NULL,NULL,'approved','2026-08-15 07:10:41','2026-08-15 07:10:41');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternate_mobile` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adhaar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `city` int DEFAULT NULL,
  `state` int DEFAULT NULL,
  `pincode` int DEFAULT NULL,
  `address` int DEFAULT NULL,
  `address_line` text COLLATE utf8mb4_unicode_ci,
  `role` enum('admin','store','customer','captain') COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` bigint unsigned DEFAULT NULL,
  `vehicle_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_login_id_unique` (`login_id`),
  UNIQUE KEY `users_mobile_unique` (`mobile`),
  KEY `users_store_id_foreign` (`store_id`),
  CONSTRAINT `users_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`first_name`,`second_name`,`login_id`,`mobile`,`alternate_mobile`,`adhaar`,`pan`,`email`,`gender`,`otp`,`otp_expires_at`,`dob`,`city`,`state`,`pincode`,`address`,`address_line`,`role`,`store_id`,`vehicle_type`,`isActive`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Mukesh','Shakya','MS0001','9898989898',NULL,'100000000001','ABCPA0001A','admin@mediserve.test','male','123456',NULL,'1990-01-01',41,34,226001,1,NULL,'admin',NULL,NULL,1,'2026-08-15 07:10:41','$2y$12$aThgm3ZWqWM1YZE5g/nzdOZgmQP8zOCVfr9gV8NXk.YPpApd7LCgq',NULL,'2026-08-12 17:10:37','2026-08-15 07:10:41'),
(2,'Abhishek','Shakya','MS0002','9595959595',NULL,'100000000002','ABCPA0002B','store@mediserve.test','male','123456',NULL,'1990-01-02',41,34,226001,2,NULL,'store',NULL,NULL,1,'2026-08-15 07:10:41','$2y$12$dvi5ttpgKE6BEuhs3oFgJuuGoqLHMjh7dAIwy.8XLWKufJUvkISmi',NULL,'2026-08-12 17:10:38','2026-08-15 07:10:41'),
(3,'Captain','User','MS0003','9292929292',NULL,'100000000003','ABCPA0003C','captain@mediserve.test','female','123456',NULL,'1990-01-03',41,34,226001,3,NULL,'captain',NULL,NULL,1,'2026-08-15 07:10:41','$2y$12$4xMxIwq0p1w7qYxjJZ786.NjP8JsD3LroojGY1cg26lCe1h7i4Op2',NULL,'2026-08-12 17:10:38','2026-08-15 07:10:41'),
(4,'Customer','User','MS0004','9000000004',NULL,'100000000004','ABCPA0004D','customer@mediserve.test','other','123456',NULL,'1990-01-04',41,34,226001,4,NULL,'customer',NULL,NULL,1,'2026-08-15 07:10:42','$2y$12$Z/iqCLmg7S9DxkrQmlxage3TwXTdAe2hAvdRTGlgM9bE9HOq522zq',NULL,'2026-08-12 17:10:38','2026-08-15 07:10:42'),
(5,'Rajesh','Kumar','MS0005','845678958',NULL,'4535345346346','NKUJL3915F','rajesh@gmail.com','male','123456',NULL,'1993-01-12',41,34,226002,NULL,'Moh Talabkala Town and Post Talgram','store',NULL,NULL,1,'2026-08-12 17:34:02','$2y$12$Tt/g0X0Fb.7vd8oK6jH3bOE4Y6QB7m9u30JgqKvcrgEM0Y9gsKGAe',NULL,'2026-08-12 17:34:02','2026-08-12 17:34:21'),
(12,'Abhishek','Kumar','MS0006','08896326943',NULL,'530836664238','ISNPK1349M','shakya.shivam6@gmail.com','male','123456',NULL,'1994-07-07',41,34,209731,NULL,'MOH TALABKALA','captain',2,'bike',1,'2026-08-16 13:13:39','$2y$12$SLNHXsDHMpUn89kqKl0XfeTlTpuces2/fSztPa87tPPxudT9LD5v.',NULL,'2026-08-16 13:13:39','2026-08-16 13:13:39'),
(55,'Mukesh',NULL,'MS0007','8892205223','9453803053',NULL,NULL,NULL,NULL,'620290',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'customer',NULL,NULL,1,'2026-08-16 14:23:26','$2y$12$iJKGNFthG.REn36XHDSqGemG7zDEPF989snAo1zCUR/LzgiZ5Z7c.',NULL,'2026-08-16 14:23:26','2026-08-16 14:23:34'),
(85,'Abhishek','Shakya','MS0008','8896326943','7985941363',NULL,NULL,NULL,NULL,'885561',NULL,NULL,NULL,NULL,226016,NULL,'E-4029 Rajajipuram Lko','customer',NULL,NULL,1,'2026-08-16 15:38:52','$2y$12$HNf1OTkCFSk3byBaw4Lf.e3Yla2rDIzLSiQLQ4uPXYOq2OzsnR10e',NULL,'2026-08-16 15:38:52','2026-08-16 15:41:33');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
