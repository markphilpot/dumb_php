-- phpMyAdmin SQL Dump
-- version 2.6.4-pl4
-- http://www.phpmyadmin.net
-- 
-- Host: www.mcstudios.net
-- Generation Time: May 03, 2006 at 04:57 PM
-- Server version: 4.1.16
-- PHP Version: 5.1.2
-- 
-- Database: `griphiam`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_admin`
-- 

CREATE TABLE IF NOT EXISTS `dumb_admin` (
  `username` varchar(8) NOT NULL default '',
  `password` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`username`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_calendar`
-- 

CREATE TABLE IF NOT EXISTS `dumb_calendar` (
  `calendar_id` int(11) NOT NULL auto_increment,
  `title` varchar(30) NOT NULL default '',
  `details` varchar(100) default NULL,
  `date` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`calendar_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_categories`
-- 

CREATE TABLE IF NOT EXISTS `dumb_categories` (
  `category_id` tinyint(4) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_content`
-- 

CREATE TABLE IF NOT EXISTS `dumb_content` (
  `content_id` int(11) NOT NULL auto_increment,
  `date` date NOT NULL default '0000-00-00',
  `title` varchar(40) NOT NULL default '',
  `content` text NOT NULL,
  `loc` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_instruments`
-- 

CREATE TABLE IF NOT EXISTS `dumb_instruments` (
  `instrument_id` int(11) NOT NULL auto_increment,
  `instrument` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`instrument_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_library`
-- 

CREATE TABLE IF NOT EXISTS `dumb_library` (
  `lib_id` int(11) NOT NULL auto_increment,
  `lib_dir_id` int(11) NOT NULL default '0',
  `name` varchar(60) NOT NULL default '',
  `type` varchar(60) NOT NULL default '',
  `size` int(11) NOT NULL default '0',
  `content` mediumblob NOT NULL,
  `description` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`lib_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=70 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_library_dir`
-- 

CREATE TABLE IF NOT EXISTS `dumb_library_dir` (
  `lib_dir_id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default '0',
  `dir_name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`lib_dir_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_members`
-- 

CREATE TABLE IF NOT EXISTS `dumb_members` (
  `username` varchar(8) NOT NULL default '0',
  `password` varchar(40) default '0',
  `firstname` varchar(20) default '0',
  `lastname` varchar(30) default '0',
  `instrument_id` tinyint(3) unsigned default '1',
  `pepband` char(1) default '0',
  `year` year(4) default NULL,
  `phone` varchar(14) default '0',
  `email` varchar(30) default '0',
  PRIMARY KEY  (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_officer_list`
-- 

CREATE TABLE IF NOT EXISTS `dumb_officer_list` (
  `username` varchar(8) NOT NULL default '',
  `officer_id` tinyint(4) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_officer_priv`
-- 

CREATE TABLE IF NOT EXISTS `dumb_officer_priv` (
  `officer_id` tinyint(4) NOT NULL default '0',
  `priv` varchar(20) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_officers`
-- 

CREATE TABLE IF NOT EXISTS `dumb_officers` (
  `officer_id` tinyint(4) NOT NULL auto_increment,
  `title` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`officer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_priv`
-- 

CREATE TABLE IF NOT EXISTS `dumb_priv` (
  `username` varchar(8) NOT NULL default '',
  `priv` varchar(20) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_schedule`
-- 

CREATE TABLE IF NOT EXISTS `dumb_schedule` (
  `schedule_id` int(11) NOT NULL auto_increment,
  `category_id` int(11) NOT NULL default '0',
  `opponent` varchar(30) NOT NULL default '',
  `location` varchar(30) NOT NULL default '',
  `time` varchar(20) NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  `pepband` varchar(6) NOT NULL default '',
  `tv` varchar(20) NOT NULL default '',
  `details` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`schedule_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=123 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_sec_attendance`
-- 

CREATE TABLE IF NOT EXISTS `dumb_sec_attendance` (
  `username` varchar(8) NOT NULL default '',
  `event_id` int(11) NOT NULL default '0',
  `code_id` int(4) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_sec_categories`
-- 

CREATE TABLE IF NOT EXISTS `dumb_sec_categories` (
  `category_id` int(11) NOT NULL auto_increment,
  `category_name` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_sec_codes`
-- 

CREATE TABLE IF NOT EXISTS `dumb_sec_codes` (
  `code_id` int(11) NOT NULL auto_increment,
  `category_id` int(11) NOT NULL default '0',
  `code_name` varchar(30) NOT NULL default '',
  `code` varchar(4) NOT NULL default '',
  `points` int(11) NOT NULL default '0',
  PRIMARY KEY  (`code_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_sec_events`
-- 

CREATE TABLE IF NOT EXISTS `dumb_sec_events` (
  `event_id` int(11) NOT NULL auto_increment,
  `category_id` int(11) NOT NULL default '0',
  `event_name` varchar(30) NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=253 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_setup`
-- 

CREATE TABLE IF NOT EXISTS `dumb_setup` (
  `parameter` varchar(30) NOT NULL default '',
  `value` varchar(30) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_tournament_form`
-- 

CREATE TABLE IF NOT EXISTS `dumb_tournament_form` (
  `username` varchar(8) NOT NULL default '',
  `disclaimer` tinyint(4) NOT NULL default '0',
  `prefer` char(1) NOT NULL default '',
  `exp` tinyint(4) NOT NULL default '0',
  `joined` varchar(20) NOT NULL default '',
  `abroad` varchar(10) NOT NULL default '',
  `option_1` tinyint(4) NOT NULL default '0',
  `option_2` tinyint(4) NOT NULL default '0',
  `option_3` tinyint(4) NOT NULL default '0',
  `option_4` tinyint(4) NOT NULL default '0',
  `option_5` tinyint(4) NOT NULL default '0',
  `option_6` tinyint(4) NOT NULL default '0',
  `option_6_phone` varchar(14) NOT NULL default '',
  `comments` text NOT NULL,
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_tournament_options`
-- 

CREATE TABLE IF NOT EXISTS `dumb_tournament_options` (
  `option_id` tinyint(4) NOT NULL default '0',
  `date` varchar(30) NOT NULL default '',
  `description` varchar(40) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `dumb_freshmen_form`
-- 

CREATE TABLE IF NOT EXISTS `dumb_freshmen_form` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(80) NOT NULL default '',
  `address` varchar(80) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  `phone` varchar(80) NOT NULL default '',
  `instrument` varchar(80) NOT NULL default '',
  `highschool` varchar(80) NOT NULL default '',
  `director` varchar(80) NOT NULL default '',
  `major` varchar(80) NOT NULL default '',
  `size` varchar(5) NOT NULL default '',
  `questions` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
