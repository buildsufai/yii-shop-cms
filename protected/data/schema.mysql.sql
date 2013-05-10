-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 08, 2013 at 05:28 PM
-- Server version: 5.5.31-0ubuntu0.13.04.1
-- PHP Version: 5.4.9-4ubuntu2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yii-shop-cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `administration`
--

CREATE TABLE IF NOT EXISTS `administration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `title` varchar(100) NOT NULL,
  `place` varchar(100) DEFAULT NULL,
  `postalcode` varchar(7) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone_nb` varchar(45) DEFAULT NULL,
  `fax_nb` varchar(45) DEFAULT NULL,
  `language` char(5) NOT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `subdomain` varchar(100) DEFAULT NULL,
  `google_maps_key` varchar(45) DEFAULT NULL,
  `ga_profile_id` varchar(45) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `description` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_category_category1` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `meta_description` text,
  `create_date` datetime NOT NULL,
  `update_date` datetime DEFAULT NULL,
  `meta_title` varchar(100) DEFAULT NULL,
  `meta_keywords` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `static` tinyint(1) NOT NULL DEFAULT '0',
  `administration_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_content_administration1` (`administration_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `content_category`
--

CREATE TABLE IF NOT EXISTS `content_category` (
  `content_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`content_id`,`category_id`),
  KEY `fk_content_has_category_category1` (`category_id`),
  KEY `fk_content_has_category_content1` (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `content_has_media`
--

CREATE TABLE IF NOT EXISTS `content_has_media` (
  `content_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`content_id`,`media_id`),
  KEY `fk_content_has_media_media1` (`media_id`),
  KEY `fk_content_has_media_content1` (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE IF NOT EXISTS `coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `order_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_coupon_order1` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `password` varchar(35) NOT NULL,
  `register_date` datetime NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `phone_nb` varchar(45) NOT NULL,
  `address` varchar(128) NOT NULL,
  `postalcode` varchar(10) NOT NULL,
  `city` varchar(128) NOT NULL,
  `country_code` varchar(2) NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `newsletter` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `description` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `filename` varchar(255) CHARACTER SET latin1 NOT NULL,
  `path` varchar(255) CHARACTER SET latin1 NOT NULL,
  `create_date` datetime DEFAULT NULL,
  `file_type` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `administration_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2942 ;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` varchar(45) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `payment_status` varchar(45) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `shipping_name` varchar(150) NOT NULL,
  `shipping_phone_nb` varchar(100) DEFAULT NULL,
  `shipping_address` varchar(128) NOT NULL,
  `shipping_postalcode` varchar(10) NOT NULL,
  `shipping_city` varchar(128) NOT NULL,
  `shipping_country_code` char(2) NOT NULL,
  `comment` text,
  `payment_methode` int(11) NOT NULL,
  `shipping_methode` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_customer1` (`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE IF NOT EXISTS `order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(45) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` float NOT NULL,
  `vat_percentage` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_detail_order1` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

CREATE TABLE IF NOT EXISTS `order_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_added` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `customer_notified` tinyint(1) NOT NULL,
  `order_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_history_order1` (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `pixmania`
--

CREATE TABLE IF NOT EXISTS `pixmania` (
  `category` varchar(255) DEFAULT NULL,
  `sub_category` varchar(255) DEFAULT NULL,
  `subsub_category` varchar(255) DEFAULT NULL,
  `code` varchar(45) NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `price_discount` varchar(45) DEFAULT NULL,
  `delivery_costs` varchar(45) DEFAULT NULL,
  `price_before_discount` varchar(45) DEFAULT NULL,
  `picture_url` varchar(255) DEFAULT NULL,
  `availability` varchar(45) DEFAULT NULL,
  `volumetric_weight` varchar(45) DEFAULT NULL,
  `weight` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(45) NOT NULL,
  `name` varchar(150) NOT NULL,
  `alias` varchar(150) NOT NULL,
  `manufacturer` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `weight` varchar(45) DEFAULT NULL,
  `description` text,
  `description2` text,
  `price` double NOT NULL,
  `sale_price` double DEFAULT NULL,
  `stock_price` double NOT NULL DEFAULT '0',
  `btw_group` int(11) NOT NULL DEFAULT '1',
  `create_date` datetime NOT NULL,
  `update_date` datetime DEFAULT NULL,
  `meta_title` varchar(100) DEFAULT NULL,
  `meta_keywords` varchar(100) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `votes_like` int(11) NOT NULL DEFAULT '0',
  `votes_dont_like` int(11) NOT NULL DEFAULT '0',
  `is_bargain` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_product_category1` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1518 ;

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE IF NOT EXISTS `product_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `alias` varchar(100) NOT NULL,
  `media_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_product_category_media1` (`media_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=86 ;

-- --------------------------------------------------------

--
-- Table structure for table `product_export`
--

CREATE TABLE IF NOT EXISTS `product_export` (
  `product_id` int(11) NOT NULL,
  `beslist` tinyint(1) NOT NULL DEFAULT '0',
  `kieskeurig` tinyint(1) NOT NULL DEFAULT '0',
  `vergelijk` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_has_media`
--

CREATE TABLE IF NOT EXISTS `product_has_media` (
  `product_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `name` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `description` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`product_id`,`media_id`),
  KEY `fk_product_has_media_media1` (`media_id`),
  KEY `fk_product_has_media_product1` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_has_property`
--

CREATE TABLE IF NOT EXISTS `product_has_property` (
  `product_id` int(11) NOT NULL,
  `property_group_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`property_id`),
  KEY `fk_product_has_property_product1` (`product_id`),
  KEY `fk_product_has_property_property_group1` (`property_group_id`),
  KEY `fk_product_has_property_property1` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE IF NOT EXISTS `property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `property_group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_property_property_group1` (`property_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=713 ;

-- --------------------------------------------------------

--
-- Table structure for table `property_group`
--

CREATE TABLE IF NOT EXISTS `property_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `position` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `product_category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_property_group_product_category1` (`product_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=216 ;

-- --------------------------------------------------------

--
-- Table structure for table `related_product`
--

CREATE TABLE IF NOT EXISTS `related_product` (
  `product_id` int(11) NOT NULL,
  `product_id1` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`product_id1`),
  KEY `fk_product_has_product_product2` (`product_id1`),
  KEY `fk_product_has_product_product1` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE IF NOT EXISTS `review` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(100) NOT NULL,
  `create_date` datetime NOT NULL,
  `description` text NOT NULL,
  `rate` tinyint(1) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_recentie_product1` (`product_id`),
  KEY `fk_recentie_customer1` (`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_cost`
--

CREATE TABLE IF NOT EXISTS `shipping_cost` (
  `weight` double NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nicename` varchar(100) DEFAULT NULL,
  `register_date` datetime DEFAULT NULL,
  `role` int(11) DEFAULT '1',
  `administration_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_administration1` (`administration_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `fk_category_category1` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `fk_content_administration1` FOREIGN KEY (`administration_id`) REFERENCES `administration` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `content_category`
--
ALTER TABLE `content_category`
  ADD CONSTRAINT `fk_content_has_category_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_content_has_category_content1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `content_has_media`
--
ALTER TABLE `content_has_media`
  ADD CONSTRAINT `content_has_media_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `content_has_media_ibfk_2` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `coupon`
--
ALTER TABLE `coupon`
  ADD CONSTRAINT `fk_coupon_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_customer1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `fk_order_detail_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_history`
--
ALTER TABLE `order_history`
  ADD CONSTRAINT `fk_order_history_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `product_category` (`id`);

--
-- Constraints for table `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `product_category_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `product_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_category_ibfk_2` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `product_export`
--
ALTER TABLE `product_export`
  ADD CONSTRAINT `product_export_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_has_media`
--
ALTER TABLE `product_has_media`
  ADD CONSTRAINT `product_has_media_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_has_media_ibfk_2` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `product_has_property`
--
ALTER TABLE `product_has_property`
  ADD CONSTRAINT `product_has_property_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_has_property_ibfk_3` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_has_property_ibfk_4` FOREIGN KEY (`property_group_id`) REFERENCES `property_group` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `property`
--
ALTER TABLE `property`
  ADD CONSTRAINT `property_ibfk_1` FOREIGN KEY (`property_group_id`) REFERENCES `property_group` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `property_group`
--
ALTER TABLE `property_group`
  ADD CONSTRAINT `fk_property_group_product_category1` FOREIGN KEY (`product_category_id`) REFERENCES `product_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `related_product`
--
ALTER TABLE `related_product`
  ADD CONSTRAINT `related_product_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `related_product_ibfk_2` FOREIGN KEY (`product_id1`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_recentie_customer1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_recentie_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_administration1` FOREIGN KEY (`administration_id`) REFERENCES `administration` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
