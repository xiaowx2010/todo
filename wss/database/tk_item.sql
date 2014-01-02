-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 05 月 02 日 03:28
-- 服务器版本: 5.5.8
-- PHP 版本: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `tankdb`
--

-- --------------------------------------------------------

--
-- 表的结构 `tk_item`
--

CREATE TABLE IF NOT EXISTS `tk_item` (
  `item_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `tk_item_key` varchar(60) CHARACTER SET utf8 NOT NULL,
  `tk_item_value` varchar(60) CHARACTER SET utf8 NOT NULL,
  `tk_item_title` varchar(60) CHARACTER SET utf8 NOT NULL,
  `tk_item_description` varchar(255) CHARACTER SET utf8 NOT NULL,
  `tk_item_type` varchar(20) CHARACTER SET utf8 NOT NULL,
  `tk_item_lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- 转存表中的数据 `tk_item`
--

INSERT INTO `tk_item` (`item_id`, `tk_item_key`, `tk_item_value`, `tk_item_title`, `tk_item_description`, `tk_item_type`, `tk_item_lastupdate`) VALUES
(3, 'outofdate', 'on', '是否显示过期任务提醒', '”on“为开启，”off“为关闭', 'setting', '2012-06-10 14:03:36'),
(7, 'mail_create', 'off', '新任务提醒', '当有新任务到达时提醒任务执行人 "on" 为启用该功能, "off" 为禁用该功能', 'setting_mail', '2012-07-04 22:02:47'),
(8, 'mail_update', 'off', '任务更新提醒', '当任务状态更新时提醒任务负责人(来自谁) "on" 为启用该功能, "off" 为禁用该功能', 'setting_mail', '2012-07-04 22:02:50'),
(9, 'mail_comment', 'off', '新备注提醒', '当任务有新备注时提醒任务执行人 "on" 为启用该功能, "off" 为禁用该功能', 'setting_mail', '2012-07-04 22:02:53'),
(10, 'mail_host', 'smtp.sina.com', 'SMTP邮件服务器', 'SMTP邮件服务器地址,如:smtp.sina.com', 'setting_mail', '2012-06-16 22:42:15'),
(11, 'mail_port', '25', 'SMTP邮件服务器端口', 'SMTP邮件服务器的端口号,默认为25，无需修改', 'setting_mail', '2012-06-16 23:00:04'),
(12, 'mail_username', 'yourname@sina.com', '用户名', '用户名:邮件帐号的用户名,如使用新浪邮箱，请填写完整的邮件地址,如: yourname@sina.com', 'setting_mail', '2012-07-04 22:03:05'),
(13, 'mail_password', 'yourpassword', '密码', '密码:邮件帐号的密码', 'setting_mail', '2012-07-04 22:03:19'),
(14, 'mail_from', 'yourname@sina.com', '发送邮件的邮箱', '发送邮件的邮箱,如: yourname@sina.com', 'setting_mail', '2012-07-04 22:03:10'),
(15, 'mail_fromname', 'WSS', '显示名称', '邮件发送人的显示名称', 'setting_mail', '2012-06-16 22:57:02'),
(16, 'mail_charset', 'UTF-8', '编码格式', '邮件编码格式设置，默认为UTF-8，无需修改', 'setting_mail', '2012-06-16 23:00:11'),
(17, 'mail_auth', 'true', 'SMTP验证', '启用SMTP验证功能，默认为true，无需修改', 'setting_mail', '2012-06-16 23:02:12'),
(18, 'maxrows_task', '20', '每页任务数', '任务列表页，每页显示的任务数量，只支持正整数', 'setting', '2012-06-17 14:57:57'),
(19, 'maxrows_timeout', '5', '每页过期任务数', '任务列表页，每页显示的过期任务数量，只支持正整数', 'setting', '2012-06-17 14:58:04'),
(20, 'maxrows_project', '20', '每页项目数', '项目列表页，每页显示的项目数量，只支持正整数', 'setting', '2012-06-17 15:00:32'),
(21, 'maxrows_user', '20', '每页用户数', '用户列表页，每页显示的用户数量，只支持正整数', 'setting', '2012-06-17 15:09:37'),
(22, 'maxrows_announcement', '20', '每页公告数', '公告列表页，每页显示的公告数量，只支持正整数', 'setting', '2012-06-17 15:25:23');
