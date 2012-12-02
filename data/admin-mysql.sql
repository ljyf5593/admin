-- Adminer 3.6.1 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `comasa_comments`;
CREATE TABLE `comasa_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `targetid` int(10) unsigned NOT NULL COMMENT '主体ID',
  `targettype` varchar(50) NOT NULL COMMENT '主体类型',
  `author_id` varchar(50) NOT NULL COMMENT '评论者ID',
  `author` varchar(80) NOT NULL COMMENT '评论者',
  `content` text NOT NULL COMMENT '评论内容',
  `dateline` int(10) NOT NULL COMMENT '评论时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表';

INSERT INTO `comasa_comments` (`id`, `targetid`, `targettype`, `author_id`, `author`, `content`, `dateline`, `status`) VALUES
(1,	8,	'coach',	'',	'刘杰',	'测试留言啊啊',	1348834877,	1),
(2,	2,	'coach',	'9',	'刘杰',	'测试留言啊啊啊',	1348837569,	0);

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='站点帮助表';

INSERT INTO `comasa_faqs` (`id`, `label`, `title`, `content`) VALUES
(1,	'about',	'关于我们',	'<h1 style=\"font-size:14px;color:#FD7E01;font-family:Simsun, Arial, sans-serif;text-align:center;text-decoration:none;\">\n	关于爱教练\n</h1>\n<h2 style=\"font-size:14px;color:#FD7E01;font-family:Simsun, Arial, sans-serif;text-decoration:none;\">\n	网站简介\n</h2>\n<p style=\"color:#666666;font-family:Simsun, Arial, sans-serif;text-decoration:none;\">\n	&nbsp;&nbsp;&nbsp;&nbsp;爱教练私教网是深圳市万正科技有限公司旗下的全资子公司，是一家专业的私人教练、家教信息发布平台。通过与政府机构和各民间机构合作联合开展教练培训、赛事承办、职业技能认证服务。我们致力于为社会推荐专业的私人教练家教人才。&nbsp;\n</p>\n<h2 style=\"font-size:14px;color:#FD7E01;font-family:Simsun, Arial, sans-serif;text-decoration:none;\">\n	爱教练的特点\n</h2>\n<p style=\"color:#666666;font-family:Simsun, Arial, sans-serif;text-decoration:none;\">\n	&nbsp;&nbsp;&nbsp;&nbsp;权威：爱教练网站通过与政府和专业机构联合开展职业技能认证培训，权威性高。\n</p>\n<p style=\"color:#666666;font-family:Simsun, Arial, sans-serif;text-decoration:none;\">\n	&nbsp;&nbsp;&nbsp;&nbsp;可靠：爱教练网站通过对信息发布人身份信息的确认、职业证书的认证以及对其受训机构的追踪，最大程度的确保每条信息的真实有效。\n</p>\n<p style=\"color:#666666;font-family:Simsun, Arial, sans-serif;text-decoration:none;\">\n	&nbsp;&nbsp;&nbsp;&nbsp;专业：爱教练通过和培训机构联合推荐人才，确保每一位人才都受过严格专业的职业技能训练。\n</p>\n<h2 style=\"font-size:14px;color:#FD7E01;font-family:Simsun, Arial, sans-serif;text-decoration:none;\">\n	核心理念\n</h2>\n<p style=\"color:#666666;font-family:Simsun, Arial, sans-serif;text-decoration:none;\">\n	&nbsp;&nbsp;&nbsp;&nbsp;爱精彩 共分享。\n</p>\n<h2 style=\"font-size:14px;color:#FD7E01;font-family:Simsun, Arial, sans-serif;text-decoration:none;\">\n	我们的文化\n</h2>\n<p style=\"font-size:14px;color:#FD7E01;font-family:Simsun, Arial, sans-serif;text-decoration:none;\">\n	<span style=\"font-size:13px;color:#666666;\">&nbsp;&nbsp;&nbsp;专心：脚踏实地，专心致志，心无旁骛。</span> \n</p>\n<p style=\"font-size:14px;color:#FD7E01;font-family:Simsun, Arial, sans-serif;text-decoration:none;\">\n	<span style=\"font-size:13px;color:#666666;\">&nbsp;&nbsp;&nbsp;专注：一百年只做一件事——专注于提高私人教练家教服务。</span> \n</p>\n<p style=\"font-size:14px;color:#FD7E01;font-family:Simsun, Arial, sans-serif;text-decoration:none;\">\n	<span style=\"font-size:13px;color:#666666;\">&nbsp;&nbsp;&nbsp;专业：不断努力，持续进步成为行业的顶级专家。</span> \n</p>\n<br />\n<span></span>'),
(2,	'contact',	'联系我们',	'<div>\n	<h1 style=\"font-size:14px;color:#FD7E01;font-family:Simsun, Arial, sans-serif;text-align:center;text-decoration:none;\">\n		<span style=\"font-size:18px;\">联系我们</span>\n	</h1>\n	<h2 style=\"font-size:14px;color:#FD7E01;font-family:Simsun, Arial, sans-serif;text-decoration:none;\">\n		<span style=\"font-size:16px;color:#ff6600;\">客户服务：</span>\n	</h2>\n	<p>\n		<span style=\"font-size:13px;color:#666666;\">我们不能保证您对我们的服务一定满意，但我们可以保证竭尽全力。</span>\n	</p>\n	<p>\n		<span style=\"font-size:13px;color:#666666;\">客服邮箱：kf</span><a href=\"mailto:i@ijiaolian.com\"><span style=\"font-size:13px;color:#666666;\">@ijiaolian.com</span></a>\n	</p>\n	<p>\n		<span style=\"font-size:13px;color:#666666;\">在线QQ：1715600843&nbsp; 1145409596</span>\n	</p>\n	<p>\n		<span style=\"font-size:13px;color:#666666;\">客服电话：0755—82438625&nbsp; 0755—82592273</span>\n	</p>\n	<p>\n		<span style=\"font-size:13px;color:#666666;\"></span>&nbsp;\n	</p>\n	<p>\n		<span style=\"font-size:18px;color:#ff6600;\"><strong>客户投诉：</strong></span>\n	</p>\n	<p>\n		<strong><span style=\"font-size:13px;color:#666666;\"></span></strong><span style=\"color:#666666;\">&nbsp;我们愿意聆听您的任何建议与真诚投诉。</span>\n	</p>\n	<p>\n		<span style=\"font-size:13px;color:#666666;\">投诉邮箱：ts</span><a href=\"mailto:i@ijiaolian.com\"><span style=\"font-size:13px;color:#666666;\">@ijiaolian.com</span></a>\n	</p>\n	<p>\n		<span style=\"font-size:13px;color:#666666;\">投诉电话：13631552022</span><span style=\"color:#666666;\"></span>&nbsp;\n	</p>\n	<p>\n		&nbsp;\n	</p>\n	<p>\n		<span style=\"color:#666666;\"></span><strong>&nbsp;<span style=\"font-size:16px;color:#ff6600;\">商务合作：</span></strong>\n	</p>\n	<p>\n		<span style=\"font-size:13px;color:#666666;\">广告、推广、合作、加盟请发邮件给我们，我们期待与您合作。</span>\n	</p>\n	<p>\n		<span style=\"font-size:13px;color:#666666;\">商务邮箱：</span><a href=\"mailto:sw@ijiaolian.com\"><span style=\"font-size:13px;color:#666666;\">sw@ijiaolian.com</span></a>\n	</p>\n	<p>\n		&nbsp;\n	</p>\n	<p>\n		<span style=\"font-size:16px;color:#ff6600;\"><strong>公司地址：</strong></span>\n	</p>\n	<p>\n		<span style=\"font-size:13px;color:#666666;\">深圳市罗湖区红岗路1064号108——110室</span><span style=\"font-size:13px;color:#666666;\">，邮编：518000</span>\n	</p>\n</div>\n<br />');

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

INSERT INTO `comasa_resources` (`id`, `title`, `targetid`, `target`, `filename`, `filesize`, `attachment`, `mime`, `filetype`, `userid`, `isimage`, `remote`, `classtype`, `description`, `dateline`, `ordering`, `type`) VALUES
(14,	'笔会在线下方广告',	0,	NULL,	'languagecode.jpg',	151867,	'201110/17/4e9bbf5600362languagecode.jpg',	'image/jpeg',	'jpg',	9,	1,	0,	NULL,	'',	1318829909,	0,	NULL);

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
(1,	'login',	'Login privileges, granted after account confirmation',	''),
(2,	'admin',	'Administrative user, has access to everything.',	'');

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
(1,	1),
(1,	2);

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
(1,	'admin@admin.com',	'admin',	'46c2a14598fcc55d75827284e73782d65ec5d18aaceea02a750e55940ae484ed',	4,	1354417151);

-- 2012-12-02 11:09:47
