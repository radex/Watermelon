-- 
-- Structure for table `wm_benchmark`
-- 

CREATE TABLE `wm_benchmark` (
  `name` varchar(256) NOT NULL,
  `value` int(11) NOT NULL,
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_blogposts`
-- 

CREATE TABLE `wm_blogposts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Part of URL',
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `summary` text,
  `author` int(11) NOT NULL,
  `published` int(10) NOT NULL,
  `updated` int(10) NOT NULL,
  `atomID` varchar(40) NOT NULL,
  `allowComments` tinyint(1) NOT NULL DEFAULT '1',
  `commentsCount` int(11) NOT NULL DEFAULT '0',
  `approvedCommentsCount` int(11) NOT NULL DEFAULT '0',
  `status` enum('published','draft','trash') NOT NULL DEFAULT 'published',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_categories`
-- 

CREATE TABLE `wm_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0',
  `name` varchar(25) NOT NULL,
  `title` varchar(60) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_comments`
-- 

CREATE TABLE `wm_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record` int(11) NOT NULL,
  `type` varchar(40) NOT NULL,
  `authorID` int(11) DEFAULT NULL,
  `authorName` varchar(40) DEFAULT NULL,
  `authorEmail` varchar(64) DEFAULT NULL,
  `authorWebsite` varchar(40) DEFAULT NULL,
  `authorIP` varchar(15) DEFAULT NULL,
  `created` int(10) NOT NULL,
  `content` text NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `visibilityToken` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `record` (`record`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_config`
-- 

CREATE TABLE `wm_config` (
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_pages`
-- 

CREATE TABLE `wm_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Part of URL',
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `author` int(11) NOT NULL,
  `created` int(10) NOT NULL,
  `updated` int(10) NOT NULL,
  `allowComments` tinyint(1) NOT NULL DEFAULT '1',
  `commentsCount` int(11) NOT NULL DEFAULT '0',
  `approvedCommentsCount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_privileges`
-- 

CREATE TABLE `wm_privileges` (
  `user` int(11) NOT NULL,
  `privilege` varchar(64) NOT NULL,
  KEY `privileges_user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_registry`
-- 

CREATE TABLE `wm_registry` (
  `name` varchar(256) CHARACTER SET latin1 NOT NULL,
  `value` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_users`
-- 

CREATE TABLE `wm_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(40) NOT NULL COMMENT 'name used when logging in',
  `password` varchar(40) NOT NULL,
  `salt` varchar(16) NOT NULL,
  `nick` varchar(40) NOT NULL COMMENT 'displayed user name',
  `email` varchar(64) NOT NULL,
  `lastseen` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_login` (`login`),
  UNIQUE KEY `user_email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
