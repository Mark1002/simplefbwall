-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- 主機: 127.0.0.1
-- 產生時間： 2014 年 11 月 01 日 19:23
-- 伺服器版本: 5.6.15-log
-- PHP 版本： 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 資料庫： `news`
--

-- --------------------------------------------------------

--
-- 資料表結構 `msg`
--

CREATE TABLE IF NOT EXISTS `msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `parents_id` int(11) NOT NULL,
  `lastUpdatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=258 ;

--
-- 資料表的匯出資料 `msg`
--

INSERT INTO `msg` (`id`, `user_id`, `msg`, `parents_id`, `lastUpdatetime`) VALUES
(230, 2, '黑心油!', 0, '2014-10-12 08:14:49'),
(216, 2, 'dfggd', 214, '2014-09-29 06:00:53'),
(214, 2, 'dfgdfgduiouiiiiiiiiou', 0, '2014-09-29 06:00:53'),
(220, 2, 'dfgfdqweqweqew', 218, '2014-09-29 06:00:53'),
(217, 2, 'dfgd', 214, '2014-09-29 06:00:53'),
(218, 2, 'fdgdfg', 0, '2014-09-29 06:00:53'),
(219, 2, 'dfgdgfqweqwe', 218, '2014-09-29 06:00:53'),
(222, 3, 'gdfgdg', 0, '2014-09-29 06:00:53'),
(231, 1, '喝了死翹翹', 230, '2014-10-12 08:15:39'),
(242, 3, 'qweqeqweqweqwe', 0, '2014-10-18 15:42:55'),
(243, 3, 'qweqweqweqweqweqweqweqeqeqqe', 242, '2014-10-18 15:43:04'),
(257, 3, '1234', 0, '2014-10-19 07:22:06'),
(237, 1, 'dfgdgfdfgdfgd', 222, '2014-10-18 15:17:42');

-- --------------------------------------------------------

--
-- 資料表結構 `msg_like`
--

CREATE TABLE IF NOT EXISTS `msg_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `msg_id` (`msg_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=850 ;

--
-- 資料表的匯出資料 `msg_like`
--

INSERT INTO `msg_like` (`id`, `msg_id`, `user_id`) VALUES
(813, 217, 2),
(816, 219, 2),
(831, 230, 1),
(849, 217, 3),
(830, 216, 2),
(839, 242, 3),
(817, 218, 2),
(828, 220, 2),
(723, 214, 1),
(827, 214, 2),
(840, 231, 1),
(732, 216, 3),
(810, 222, 2),
(734, 219, 3),
(847, 220, 3);

-- --------------------------------------------------------

--
-- 資料表結構 `msg_user`
--

CREATE TABLE IF NOT EXISTS `msg_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` char(12) NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 資料表的匯出資料 `msg_user`
--

INSERT INTO `msg_user` (`id`, `account`, `name`, `password`) VALUES
(1, 'dell007', 'dell', 'admin'),
(2, 'mark1002', 'mark張', 'admin'),
(3, 'sliver97', '咚咚', 'admin');

-- --------------------------------------------------------

--
-- 資料表結構 `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 資料表的匯出資料 `news`
--

INSERT INTO `news` (`id`, `title`, `slug`, `text`) VALUES
(1, 'acd', 'hhhh', 'ssssssssss'),
(2, 'ddfgd', 'ddd', 'jjjjjkkkk'),
(3, 'sdf', 'sdf', 'sdf'),
(4, 'dfg', 'dfg', 'dfg'),
(5, 'dfg', 'dfg', 'dfg'),
(6, 'dfg', 'dfg', 'dfg'),
(7, 'dfg', 'dfg', 'dfg'),
(8, 'dfg', 'dfg', 'dfg'),
(9, 'e', 'e', 'e');

-- --------------------------------------------------------

--
-- 資料表結構 `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_name` varchar(64) DEFAULT NULL,
  `p_name` varchar(64) DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `state` char(2) DEFAULT NULL,
  `zip` char(10) DEFAULT NULL,
  `phone` char(20) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 資料表的匯出資料 `student`
--

INSERT INTO `student` (`id`, `s_name`, `p_name`, `address`, `city`, `state`, `zip`, `phone`, `email`) VALUES
(2, 'Peter Green', 'Len & Natalie Green', '480 West Broad Street', 'Eastbrook Canyon', 'PA', '19104', '(215) 900-2341', 'greenery@timewarner.dsl.com'),
(3, 'Jonah Ross', 'Robert & Linda Ross', '1293 Law Street', 'Eastbrook Village', 'PA', '19105', '(215) 907-1122', 'ross_boss@gmail.com'),
(4, 'Rebecca Dillon', 'Lainie and Howard Dillon', '12 Flamingo Drive', 'Westbrook Village', 'PA', '19103', '(215) 887-4313', 'ld_1975@yahoo.com'),
(5, 'Noah Singer', 'Carolyn & Peter Singer', '393 Green Lake Road, 8th Floor', 'Eastbrook Village', 'PA', '19105', '(215) 907-2344', 'candp@gmail.com'),
(6, 'Trevor Lee Logan', 'Steven Logan', '400 Green Lake Road, 9th Floor', 'Eastbrook Village', 'PA', '19105-6541', '(828) 299-9885', 'misterSAL@sbcglobal.net'),
(7, 'Audrey Christiansen', 'Lovey Christiansen', '1993 East Sunnyside Lane', 'Eastbrook Canyon', 'PA', '19104', '(215) 887-5545', 'lovey@christiansen-clan.com');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
