-- phpMyAdmin SQL Dump
-- version 2.11.2.1
-- http://www.phpmyadmin.net
--
-- ����: localhost
-- ��������: 2008 �� 07 �� 29 �� 21:13
-- �������汾: 5.0.45
-- PHP �汾: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- ���ݿ�: `abc`
--

-- --------------------------------------------------------

--
-- ��Ľṹ `class`
--

CREATE TABLE `class` (
  `id` tinyint(1) unsigned NOT NULL auto_increment,
  `classname` varchar(50) character set gbk NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- �������е����� `class`
--

INSERT INTO `class` (`id`, `classname`) VALUES
(2, '����'),
(3, '��ѧ'),
(4, '����'),
(5, '����'),
(6, '΢��'),
(7, '����');

-- --------------------------------------------------------

--
-- ��Ľṹ `signup`
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
-- �������е����� `signup`
--

INSERT INTO `signup` (`id`, `username`, `sex`, `age`, `tel`, `address`, `classid`, `remark`, `intime`) VALUES
(9, '���ſƼ�', 1, 26, '15843689682', '����ʡ�׳��к����ֵ��»����', 6, 'QQ:3200116 ��л��ʦ�Ľ̳̣���ӭ����', '2008-07-29'),
(10, '11111', 1, 26, '13204367618', '����ʡ�׳���', 2, '���Ա�ע��������', '2008-07-30');
