-- 
-- Structure for table `wm_benchmark`
-- 

DROP TABLE IF EXISTS `wm_benchmark`;
CREATE TABLE IF NOT EXISTS `wm_benchmark` (
  `name` varchar(256) NOT NULL,
  `value` int(11) NOT NULL,
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_blogposts`
-- 

DROP TABLE IF EXISTS `wm_blogposts`;
CREATE TABLE IF NOT EXISTS `wm_blogposts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Part of URL',
  `title` varchar(256) NOT NULL,
  `beginning` text,
  `content` text NOT NULL,
  `author` int(11) NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_comments`
-- 

DROP TABLE IF EXISTS `wm_comments`;
CREATE TABLE IF NOT EXISTS `wm_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `authorID` int(11) DEFAULT NULL,
  `authorName` varchar(40) DEFAULT NULL,
  `authorEmail` varchar(64) DEFAULT NULL,
  `authorWebsite` varchar(40) DEFAULT NULL,
  `created` int(10) NOT NULL,
  `content` text NOT NULL,
  `awaitingModeration` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_comments_records`
-- 

DROP TABLE IF EXISTS `wm_comments_records`;
CREATE TABLE IF NOT EXISTS `wm_comments_records` (
  `record` int(11) NOT NULL,
  `comment` int(11) NOT NULL,
  `type` varchar(40) NOT NULL,
  KEY `blogpostcomm_post` (`record`),
  KEY `commrecord_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_pages`
-- 

DROP TABLE IF EXISTS `wm_pages`;
CREATE TABLE IF NOT EXISTS `wm_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'Part of URL',
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `author` int(11) NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_privileges`
-- 

DROP TABLE IF EXISTS `wm_privileges`;
CREATE TABLE IF NOT EXISTS `wm_privileges` (
  `user` int(11) NOT NULL,
  `privilege` varchar(64) NOT NULL,
  KEY `privileges_user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_registry`
-- 

DROP TABLE IF EXISTS `wm_registry`;
CREATE TABLE IF NOT EXISTS `wm_registry` (
  `name` varchar(256) CHARACTER SET latin1 NOT NULL,
  `value` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_users`
-- 

DROP TABLE IF EXISTS `wm_users`;
CREATE TABLE IF NOT EXISTS `wm_users` (
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

