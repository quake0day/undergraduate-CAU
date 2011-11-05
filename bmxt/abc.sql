-- phpMyAdmin SQL Dump
-- version 2.11.2.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2008 年 07 月 29 日 21:13
-- 服务器版本: 5.0.45
-- PHP 版本: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `abc`
--

-- --------------------------------------------------------

--
-- 表的结构 `class`
--

CREATE TABLE `class` (
  `id` tinyint(1) unsigned NOT NULL auto_increment,
  `classname` varchar(50) character set gbk NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- 导出表中的数据 `class`
--

INSERT INTO `class` (`id`, `classname`) VALUES
(2, '物理'),
(3, '数学'),
(4, '语文'),
(5, '生物'),
(6, '微机'),
(7, '其他');

-- --------------------------------------------------------

--
-- 表的结构 `signup`
--

CREATE TABLE `signup` (
  `id` tinyint(1) unsigned NOT NULL auto_increment,
  `username` varchar(10) character set gbk NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `age` tinyint(3) NOT NULL,
  `tel` varchar(15) character set gbk NOT NULL,
  `address` varchar(50) character set gbk NOT NULL,
  `classid` tinyint(2) NOT NULL,
  `remark` tinytext character set gbk NOT NULL,
  `intime` date NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- 导出表中的数据 `signup`
--

INSERT INTO `signup` (`id`, `username`, `sex`, `age`, `tel`, `address`, `classid`, `remark`, `intime`) VALUES
(9, '诚信科技', 1, 26, '15843689682', '吉林省白城市海明街道新华书店', 6, 'QQ:3200116 感谢老师的教程，欢迎交流', '2008-07-29'),
(10, '11111', 1, 26, '13204367618', '吉林省白城市', 2, '测试备注哈哈哈哈', '2008-07-30');
