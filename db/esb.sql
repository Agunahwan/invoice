/*
SQLyog Community v13.1.8 (64 bit)
MySQL - 10.1.37-MariaDB : Database - esb
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*Table structure for table `client` */

DROP TABLE IF EXISTS `client`;

CREATE TABLE `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `address` varchar(250) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `client` */

insert  into `client`(`id`,`name`,`address`,`created_by`,`created_date`,`updated_by`,`updated_date`) values 
(1,'Barrington Publishers','17 Great Suffolk Street London SE1 ONS United Kingdom',0,'2021-12-15 12:43:04',0,'0000-00-00 00:00:00');

/*Table structure for table `company` */

DROP TABLE IF EXISTS `company`;

CREATE TABLE `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `address` varchar(250) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `company` */

insert  into `company`(`id`,`name`,`address`,`created_by`,`created_date`,`updated_by`,`updated_date`) values 
(1,'Descovery Designs','41 St Vincent Place Glasgow G1 2ER Scotland',0,'2021-12-15 12:42:03',0,'0000-00-00 00:00:00');

/*Table structure for table `invoice_detail` */

DROP TABLE IF EXISTS `invoice_detail`;

CREATE TABLE `invoice_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_header_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` decimal(30,2) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_header_id` (`invoice_header_id`),
  CONSTRAINT `invoice_detail_ibfk_1` FOREIGN KEY (`invoice_header_id`) REFERENCES `invoice_header` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `invoice_detail` */

insert  into `invoice_detail`(`id`,`invoice_header_id`,`item_id`,`quantity`,`amount`,`created_by`,`created_date`,`updated_by`,`updated_date`) values 
(1,2,1,33,230.00,0,'2021-12-15 19:39:42',0,'2021-12-15 19:39:42'),
(2,2,2,22,330.00,0,'2021-12-15 19:39:42',0,'2021-12-15 19:39:42'),
(5,4,1,44,230.00,0,'2021-12-15 19:46:41',0,'2021-12-15 19:46:41'),
(6,4,3,6,60.00,0,'2021-12-15 19:46:41',0,'2021-12-15 19:46:41'),
(7,4,2,33,330.00,0,'2021-12-15 19:46:41',0,'2021-12-15 19:46:41');

/*Table structure for table `invoice_header` */

DROP TABLE IF EXISTS `invoice_header`;

CREATE TABLE `invoice_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subject` varchar(50) NOT NULL,
  `subtotal` decimal(30,2) NOT NULL,
  `tax` decimal(30,2) NOT NULL,
  `total_payments` decimal(30,2) DEFAULT NULL,
  `payments` decimal(30,2) NOT NULL,
  `amount_due` decimal(30,2) NOT NULL,
  `is_paid` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `invoice_header_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  CONSTRAINT `invoice_header_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `invoice_header` */

insert  into `invoice_header`(`id`,`company_id`,`client_id`,`issue_date`,`due_date`,`subject`,`subtotal`,`tax`,`total_payments`,`payments`,`amount_due`,`is_paid`,`created_by`,`created_date`,`updated_by`,`updated_date`) values 
(2,1,1,'2021-12-16','2021-12-16','Contoh',14850.00,1485.00,16335.00,16335.00,0.00,1,0,'2021-12-15 19:39:42',0,'2021-12-15 19:39:42'),
(4,1,1,'2021-12-16','2021-12-16','datas',21370.00,2137.00,23507.00,23000.00,507.00,1,0,'2021-12-15 19:46:40',0,'2021-12-15 19:46:40');

/*Table structure for table `item` */

DROP TABLE IF EXISTS `item`;

CREATE TABLE `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_type_id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `unit_price` decimal(30,2) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_type_id` (`item_type_id`),
  CONSTRAINT `item_ibfk_1` FOREIGN KEY (`item_type_id`) REFERENCES `item_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `item` */

insert  into `item`(`id`,`item_type_id`,`description`,`unit_price`,`created_date`,`created_by`,`updated_date`,`updated_by`) values 
(1,1,'Design',230.00,'2021-12-15 12:40:07',0,'0000-00-00 00:00:00',0),
(2,1,'Development',330.00,'2021-12-15 12:40:27',0,'0000-00-00 00:00:00',0),
(3,1,'Meetings',60.00,'2021-12-15 12:40:44',0,'0000-00-00 00:00:00',0);

/*Table structure for table `item_type` */

DROP TABLE IF EXISTS `item_type`;

CREATE TABLE `item_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `item_type` */

insert  into `item_type`(`id`,`type`,`created_date`,`created_by`,`updated_date`,`updated_by`) values 
(1,'Service','2021-12-15 12:36:08',0,'0000-00-00 00:00:00',0),
(2,'Access Fee','2021-12-15 12:36:23',0,'0000-00-00 00:00:00',0),
(3,'Blanket Order','2021-12-15 12:36:31',0,'0000-00-00 00:00:00',0);

/*Table structure for table `setting` */

DROP TABLE IF EXISTS `setting`;

CREATE TABLE `setting` (
  `key` varchar(50) NOT NULL,
  `value` varchar(250) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `setting` */

insert  into `setting`(`key`,`value`,`created_by`,`created_date`,`updated_by`,`updated_date`) values 
('TAX','10',0,'2021-12-15 12:45:29',NULL,'0000-00-00 00:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
