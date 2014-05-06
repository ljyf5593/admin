-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `comasa_config`;
CREATE TABLE `comasa_config` (
  `group_name` varchar(50) DEFAULT NULL,
  `config_key` varchar(80) DEFAULT NULL,
  `config_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `comasa_faqs`;
CREATE TABLE `comasa_faqs` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '帮助ID',
  `label` varchar(40) NOT NULL COMMENT '帮助标识',
  `title` varchar(255) NOT NULL COMMENT '帮助标题',
  `content` text NOT NULL COMMENT '帮助内容',
  `dateline` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='站点帮助表';


DROP TABLE IF EXISTS `comasa_resources`;
CREATE TABLE `comasa_resources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `title` varchar(100) DEFAULT NULL COMMENT '文件标题',
  `targetid` int(10) NOT NULL DEFAULT '0',
  `target` varchar(100) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL COMMENT '原始文件名',
  `filesize` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `attachment` varchar(255) DEFAULT NULL COMMENT '附件路径',
  `mime` varchar(80) DEFAULT NULL COMMENT 'mime',
  `filetype` varchar(40) DEFAULT NULL COMMENT '文件后缀',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '创建者id',
  `isimage` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否图片',
  `remote` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否远程',
  `classtype` varchar(99) DEFAULT NULL COMMENT '分类',
  `description` text COMMENT '文件说明',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `ordering` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表';


DROP TABLE IF EXISTS `comasa_roles`;
CREATE TABLE `comasa_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(32) NOT NULL COMMENT '名称',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `permissions` varchar(255) NOT NULL COMMENT '权限',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

INSERT INTO `comasa_roles` (`id`, `name`, `description`, `permissions`) VALUES
(1, 'login',  'Login privileges, granted after account confirmation', ''),
(2, 'administrator',  'Administrative user, has access to everything.', '');

DROP TABLE IF EXISTS `comasa_roles_users`;
CREATE TABLE `comasa_roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`),
  CONSTRAINT `comasa_roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `comasa_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comasa_roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `comasa_roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `comasa_roles_users` (`user_id`, `role_id`) VALUES
(1, 1),
(1, 2);

DROP TABLE IF EXISTS `comasa_sessions`;
CREATE TABLE `comasa_sessions` (
  `session_id` varchar(24) NOT NULL,
  `last_active` int(10) unsigned NOT NULL,
  `contents` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_active` (`last_active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `comasa_users`;
CREATE TABLE `comasa_users` (
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

INSERT INTO `comasa_users` (`id`, `email`, `username`, `password`, `logins`, `last_login`) VALUES
(1, 'admin@admin.com',  'administrator',  '0e64f902a6ae7cc0cb7014f8fcb5bab0', 6,  1399351922);

DROP TABLE IF EXISTS `comasa_user_tokens`;
CREATE TABLE `comasa_user_tokens` (
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
  CONSTRAINT `comasa_user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `comasa_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2014-05-06 05:10:36