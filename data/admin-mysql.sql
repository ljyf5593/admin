-- Adminer 3.6.1 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `kohana` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `kohana`;

DROP TABLE IF EXISTS `kohana_config`;
CREATE TABLE `kohana_config` (
  `group_name` varchar(50) DEFAULT NULL,
  `config_key` varchar(80) DEFAULT NULL,
  `config_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `kohana_roles`;
CREATE TABLE `kohana_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(32) NOT NULL COMMENT '名称',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `permissions` varchar(255) NOT NULL COMMENT '权限',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

INSERT INTO `kohana_roles` (`id`, `name`, `description`, `permissions`) VALUES
(1,	'login',	'Login privileges, granted after account confirmation',	''),
(2,	'admin',	'Administrative user, has access to everything.',	'');

DROP TABLE IF EXISTS `kohana_roles_users`;
CREATE TABLE `kohana_roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`),
  CONSTRAINT `kohana_roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `kohana_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kohana_roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `kohana_roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `kohana_roles_users` (`user_id`, `role_id`) VALUES
(1,	1),
(1,	2);

DROP TABLE IF EXISTS `kohana_user_tokens`;
CREATE TABLE `kohana_user_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_agent` varchar(40) NOT NULL,
  `token` varchar(40) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`),
  KEY `expires` (`expires`),
  CONSTRAINT `kohana_user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `kohana_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `kohana_users`;
CREATE TABLE `kohana_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `email` varchar(254) NOT NULL COMMENT 'Email',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL COMMENT '密码',
  `logins` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `last_login` int(10) unsigned DEFAULT NULL COMMENT '最后登录',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

INSERT INTO `kohana_users` (`id`, `email`, `username`, `password`, `logins`, `last_login`) VALUES
(1,	'admin@admin.com',	'admin',	'46c2a14598fcc55d75827284e73782d65ec5d18aaceea02a750e55940ae484ed',	1,	1354071746);

-- 2012-11-28 11:42:57
